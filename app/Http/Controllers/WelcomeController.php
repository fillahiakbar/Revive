<?php

namespace App\Http\Controllers;

use App\Models\Anime;
use App\Models\AnimeLink;
use App\Models\Slider;
use App\Models\SocialMedia;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WelcomeController extends Controller
{
    public function index()
    {
        // ====== SETTINGS ======
        $period = SiteSetting::getMostVisitedPeriod(); // 'weekly' | 'monthly' | 'all_time'

        // Atur limit agar "banyak" (ubah angka ini sesuai kebutuhan UI)
        $LATEST_RELEASES_LIMIT = 5;
        $MOST_VISITED_LIMIT    = 5;

        // ====== SLIDER & SOSMED ======
        $sliders = Slider::where('is_active', true)
            ->orderBy('order')
            ->get([
                'image', 'choice', 'title', 'type', 'duration', 'duration_ms',
                'year', 'quality', 'episodes', 'description', 'mal_id'
            ]);

        $socialMedias = SocialMedia::where('is_active', true)->get();

        // ====== ANIME SECTIONS (work_in_progress, recommendation) ======
        $animes = Anime::query()
            ->orderByRaw("FIELD(type, 'work_in_progress', 'recommendation')")
            ->orderByDesc('created_at')
            ->get()
            ->unique(fn ($anime) => $anime->mal_id . '-' . $anime->type)
            ->map(function ($anime) {
                $anime->genres = is_string($anime->genres)
                    ? array_map(fn ($g) => ['name' => trim($g)], explode(',', $anime->genres))
                    : (is_array($anime->genres) ? $anime->genres : []);
                return $anime;
            })
            ->values();

        // ====== LATEST RELEASES (tetap: ambil 1 batch terbaru per anime dari 15 anime terakhir) ======
        $animeLinks = AnimeLink::with('batches')
            ->whereNotNull('mal_id')
            ->orderByDesc('id')
            ->limit($LATEST_RELEASES_LIMIT)
            ->get();

        $latestReleases = collect();
        foreach ($animeLinks as $anime) {
            $batch = $anime->batches->sortByDesc('created_at')->first();
            if (!$batch || !$batch->name) {
                continue;
            }
            $episodes = $anime->episodes ?? '؟';

            // Hindari call API di loop agar landing cepat. Bila ingin tetap fallback,
            // Anda bisa aktifkan blok try-catch berikut, tapi disarankan di-cache.
            /*
            if (empty($anime->episodes) && $anime->mal_id) {
                try {
                    $resp = Http::timeout(5)->get("https://api.jikan.moe/v4/anime/{$anime->mal_id}");
                    if ($resp->successful()) {
                        $episodes = $resp['data']['episodes'] ?? '؟';
                    }
                } catch (\Throwable $e) {
                    Log::warning("Jikan fail {$anime->mal_id}: {$e->getMessage()}");
                }
            }
            */

            $latestReleases->push([
                'mal_id'            => $anime->mal_id,
                'title'             => $anime->title,
                'title_english'     => $anime->title_english ?? $anime->title,
                'score'             => $anime->mal_score ?? 'N/A',
                'imdb_score'        => $anime->imdb_score ?? 'N/A',
                'type'              => $anime->type ?? '-',
                'episodes'          => $episodes,
                'synopsis'          => $batch->name ?? '-',
                'latest_batch_name' => $batch->name ?? '-',
                'images' => [
                    'jpg' => ['image_url' => $anime->poster ?? '/img/default-poster.jpg']
                ],
                'genres' => is_string($anime->genres)
                    ? array_map(fn ($g) => ['name' => trim($g)], explode(',', $anime->genres))
                    : [],
                'created_at'  => $batch->created_at,
                'click_count' => (int) ($anime->click_count ?? 0),
            ]);
        }

        // urut terbaru berdasarkan waktu batch dibuat
        $latestReleases = $latestReleases->sortByDesc('created_at')->values()->take(5);

        // ====== MOST VISITED (by period) ======
        if ($period === 'all_time') {
            // Mode all-time: gunakan lifetime click_count
            $mostVisitedLinks = AnimeLink::query()
                ->with(['batches' => fn ($q) => $q->orderByDesc('created_at')])
                ->whereNotNull('mal_id')
                ->orderByDesc('click_count')
                ->limit($MOST_VISITED_LIMIT)
                ->get();
        } else {
            // Mode weekly / monthly: hanya yang punya visit di periode tsb
            $mostVisitedLinks = AnimeLink::query()
                ->with(['batches' => fn ($q) => $q->orderByDesc('created_at')])
                ->whereNotNull('mal_id')
                ->whereHasVisitsInPeriod($period)   // hanya yang punya visit minggu/bulan ini
                ->withPeriodClicks($period)         // sum(count) sebagai alias -> period_clicks
                ->orderByDesc('period_clicks')      // urut berdasarkan visit di periode
                ->limit($MOST_VISITED_LIMIT)
                ->get();
        }

        $mostVisited = $mostVisitedLinks->map(function (AnimeLink $anime) {
            $batch    = $anime->batches->first();
            $episodes = $anime->episodes ?? '؟';

            return [
                'mal_id'            => $anime->mal_id,
                'title'             => $anime->title,
                'title_english'     => $anime->title_english ?? $anime->title,
                'score'             => $anime->mal_score ?? 'N/A',
                'imdb_score'        => $anime->imdb_score ?? 'N/A',
                'type'              => $anime->type ?? '-',
                'episodes'          => $episodes,
                'synopsis'          => $batch?->name ?? '-',
                'latest_batch_name' => $batch?->name ?? '-',
                'images' => [
                    'jpg' => ['image_url' => $anime->poster ?? '/img/default-poster.jpg']
                ],
                'genres' => is_string($anime->genres)
                    ? array_map(fn ($g) => ['name' => trim($g)], explode(',', $anime->genres))
                    : [],
                'created_at'  => $batch?->created_at ?? $anime->created_at,
                // pakai angka sesuai konteks (period_clicks jika ada, fallback all-time click_count)
                'click_count' => (int) ($anime->period_clicks ?? $anime->click_count ?? 0),
            ];
        })->values();

        return view('welcome', [
            'animes'         => $animes,
            'latestReleases' => $latestReleases,
            'mostVisited'    => $mostVisited,
            'sliders'        => $sliders,
            'socialMedias'   => $socialMedias,
            'period'         => $period,
        ]);
    }

    public function about()   { return view('about'); }
    public function terms()   { return view('terms'); }
    public function cookies() { return view('cookies'); }
    public function privacy() { return view('privacy'); }
}
