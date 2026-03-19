<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RefClick;
use App\Models\RefStat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReferralController extends Controller
{
    public function trackClick(Request $request)
    {
        $request->validate([
            'ref' => 'required|string',
            'anime_id' => 'required|integer',
        ]);

        $refCode = $request->input('ref');
        $animeId = $request->input('anime_id');

        $user = User::where('ref_code', $refCode)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Invalid ref code']);
        }

        $userAgent = strtolower($request->header('User-Agent'));
        if (preg_match('/bot|crawl|curl|slurp|spider|python|php/i', $userAgent)) {
            return response()->json(['success' => false, 'message' => 'Bot detected']);
        }

        if (auth()->check() && auth()->id() === $user->id) {
            return response()->json(['success' => false, 'message' => 'Self referral']);
        }

        $ip = $request->ip();
        
        // Get active season
        $season = \App\Models\LeaderboardSeason::active();
        if (!$season) {
            // Create initial season if none exists
            $season = \App\Models\LeaderboardSeason::create([
                'name' => 'Season 1',
                'is_active' => true,
                'start_date' => now(),
            ]);
        }

        $cookieName = 'ref_viewer_id';
        $viewerCookie = $request->cookie($cookieName);
        $needsCookie = false;

        if (!$viewerCookie) {
            $viewerCookie = Str::uuid()->toString();
            $needsCookie = true;
        }

        $clicksFromIpLast24h = RefClick::where('viewer_ip', $ip)
            ->where('created_at', '>=', now()->subHours(24))
            ->count();

        if ($clicksFromIpLast24h >= 3) {
            return response()->json(['success' => false, 'message' => 'Rate limit exceeded']);
        }

        $alreadyClicked = RefClick::where('ref_user_id', $user->id)
            ->where('anime_id', $animeId)
            ->where('season_id', $season->id)
            ->where(function($q) use ($ip, $viewerCookie) {
                $q->where('viewer_cookie', $viewerCookie)
                  ->orWhere('viewer_ip', $ip);
            })
            ->exists();

        DB::beginTransaction();
        try {
            $click = RefClick::create([
                'ref_user_id' => $user->id,
                'anime_id' => $animeId,
                'viewer_ip' => $ip,
                'viewer_cookie' => $viewerCookie,
                'viewer_user_id' => auth()->id(),
                'season_id' => $season->id,
            ]);

            $stat = RefStat::firstOrCreate(
                ['user_id' => $user->id, 'season_id' => $season->id],
                ['total_click' => 0, 'unique_click' => 0, 'anime_shared' => 0]
            );

            $stat->total_click += 1;

            if (!$alreadyClicked) {
                $stat->unique_click += 1;
                
                $hasOtherClicksForThisAnime = RefClick::where('ref_user_id', $user->id)
                    ->where('anime_id', $animeId)
                    ->where('id', '!=', $click->id)
                    ->exists();
                
                if (!$hasOtherClicksForThisAnime) {
                    $stat->anime_shared += 1;
                }
            }
            
            $stat->last_updated = now();
            $stat->save();

            DB::commit();

            $response = response()->json(['success' => true]);
            if ($needsCookie) {
                $response->cookie($cookieName, $viewerCookie, 60 * 24 * 365);
            }
            
            return $response;

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    public function getLeaderboard()
    {
        $season = \App\Models\LeaderboardSeason::active();
        $topUsers = RefStat::with('user:id,name')
            ->when($season, fn($q) => $q->where('season_id', $season->id))
            ->orderBy('unique_click', 'desc')
            ->limit(100)
            ->get()
            ->map(function ($stat, $index) {
                return [
                    'rank' => $index + 1,
                    'username' => $stat->user->name ?? 'Unknown',
                    'unique_click' => $stat->unique_click,
                ];
            });

        return response()->json($topUsers);
    }

    public function showLeaderboard()
    {
        $season = \App\Models\LeaderboardSeason::active();
        $topUsers = RefStat::with('user:id,name')
            ->when($season, fn($q) => $q->where('season_id', $season->id))
            ->orderBy('unique_click', 'desc')
            ->limit(100)
            ->get();

        $seasonEndDate = \App\Models\Setting::get('season_end_date', '');
        $rank1Achievement = \App\Models\Setting::get('rank1_achievement', 'CHAMPION');
        $leaderboardPrize = \App\Models\Setting::get('leaderboard_prize', '');
            
        return view('leaderboard', compact('topUsers', 'seasonEndDate', 'rank1Achievement', 'leaderboardPrize'));
    }
}
