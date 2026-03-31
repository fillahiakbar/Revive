<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Anime;
use App\Models\AnimeLink;
use App\Models\Batch;
use App\Models\Comment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $now = now();
        $startOfWeek  = $now->copy()->startOfWeek();

        /** ---------- 14-day sparkline buckets ---------- */
        $days14 = collect(range(13, 0))
            ->map(fn (int $i) => $now->copy()->subDays($i)->toDateString());
        $from14 = $days14->first();

        // New users per day (for sparkline)
        $userDaily = User::selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->whereDate('created_at', '>=', $from14)
            ->groupBy('d')
            ->pluck('c', 'd');

        $usersSpark14 = $days14->map(fn (string $d) => (int) ($userDaily[$d] ?? 0))->all();

        // Visits per day (SUM of `count`) – only if anime_visits exists
        $visitDaily = Schema::hasTable('anime_visits')
            ? DB::table('anime_visits')
                ->selectRaw('visited_date as d, SUM(`count`) as c')
                ->where('visited_date', '>=', $from14)
                ->groupBy('d')
                ->pluck('c', 'd')
            : collect();

        $visitsSpark14 = $days14->map(fn (string $d) => (int) ($visitDaily[$d] ?? 0))->all();

        /** ---------- Aggregates ---------- */
        $totalUsers    = User::count();
        $newUsersWeek  = User::where('created_at', '>=', $startOfWeek)->count();

        // Sum total visits across all rows (using `count` column)
        $totalVisits = Schema::hasTable('anime_visits')
            ? (int) DB::table('anime_visits')->sum('count')
            : 0;

        $visitsToday = Schema::hasTable('anime_visits')
            ? (int) DB::table('anime_visits')
                ->whereDate('visited_date', $now->toDateString())
                ->sum('count')
            : 0;

        /** ---------- 7-day sparkline buckets ---------- */
        $days7  = collect(range(6, 0))
            ->map(fn (int $i) => $now->copy()->subDays($i)->toDateString());
        $from7  = $days7->first();

        $userDaily7 = User::selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->whereDate('created_at', '>=', $from7)
            ->groupBy('d')
            ->pluck('c', 'd');

        $usersSpark7 = $days7->map(fn (string $d) => (int) ($userDaily7[$d] ?? 0))->all();

        // Anime added per day (7 days)
        $animeDaily7 = Anime::selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->whereDate('created_at', '>=', $from7)
            ->groupBy('d')
            ->pluck('c', 'd');
            
        $animeSpark7 = $days7->map(fn (string $d) => (int) ($animeDaily7[$d] ?? 0))->all();

        // Episodes added per day (7 days)
        $episodeDaily7 = AnimeLink::selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->whereDate('created_at', '>=', $from7)
            ->groupBy('d')
            ->pluck('c', 'd');
            
        $episodeSpark7 = $days7->map(fn (string $d) => (int) ($episodeDaily7[$d] ?? 0))->all();

        // Batch uploads per day (7 days)
        $batchDaily7 = Batch::selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->whereDate('created_at', '>=', $from7)
            ->groupBy('d')
            ->pluck('c', 'd');
            
        $batchSpark7 = $days7->map(fn (string $d) => (int) ($batchDaily7[$d] ?? 0))->all();

        // Comments per day (7 days)
        $commentDaily7 = Comment::selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->whereDate('created_at', '>=', $from7)
            ->groupBy('d')
            ->pluck('c', 'd');
            
        $commentSpark7 = $days7->map(fn (string $d) => (int) ($commentDaily7[$d] ?? 0))->all();

        /** ---------- New Stats Aggregates ---------- */
        $totalAnime = Anime::count();
        $newAnimeWeek = Anime::where('created_at', '>=', $startOfWeek)->count();
        
        $totalEpisodes = AnimeLink::count();
        $totalBatch = Batch::count();
        
        $totalComments = Comment::count();
        $newCommentsWeek = Comment::where('created_at', '>=', $startOfWeek)->count();

        return [
            Stat::make('Total Users', number_format($totalUsers))
                ->description("Total All User")
                ->icon('heroicon-o-users')
                ->color('primary')
                ->chart($usersSpark14),

            Stat::make('Total Visits', number_format($totalVisits))
                ->description("Today: {$visitsToday}")
                ->icon('heroicon-o-chart-bar')
                ->color('success')
                ->chart($visitsSpark14),

            Stat::make('New Users (This Week)', number_format($newUsersWeek))
                ->description('Last 7 days')
                ->descriptionIcon('heroicon-m-arrow-trending-up', 'before')
                ->icon('heroicon-o-user-plus')
                ->color('info')
                ->chart($usersSpark7),

            Stat::make('Total Anime', number_format($totalAnime))
                ->description("{$newAnimeWeek} added this week")
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->icon('heroicon-o-film')
                ->color('danger')
                ->chart($animeSpark7),

            Stat::make('Total Episodes / Links', number_format($totalEpisodes))
                ->description('Total episodes and links completely')
                ->icon('heroicon-o-link')
                ->color('warning')
                ->chart($episodeSpark7),
                
            Stat::make('Total Batch Uploads', number_format($totalBatch))
                ->description('Total complete batch releases')
                ->icon('heroicon-o-archive-box')
                ->color('gray')
                ->chart($batchSpark7),

            Stat::make('Total Comments', number_format($totalComments))
                ->description("{$newCommentsWeek} interactions this week")
                ->descriptionIcon('heroicon-m-chat-bubble-bottom-center-text')
                ->icon('heroicon-o-chat-bubble-left-right')
                ->color('success')
                ->chart($commentSpark7),
        ];
    }
}

