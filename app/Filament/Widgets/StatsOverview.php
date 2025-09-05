<?php

namespace App\Filament\Widgets;

use App\Models\User;
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
        $newUsersToday = User::whereDate('created_at', $now->toDateString())->count();
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

        /** ---------- 7-day sparkline for weekly registrations ---------- */
        $days7  = collect(range(6, 0))
            ->map(fn (int $i) => $now->copy()->subDays($i)->toDateString());
        $from7  = $days7->first();

        $userDaily7 = User::selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->whereDate('created_at', '>=', $from7)
            ->groupBy('d')
            ->pluck('c', 'd');

        $usersSpark7 = $days7->map(fn (string $d) => (int) ($userDaily7[$d] ?? 0))->all();

        return [
            Stat::make('Total Users', number_format($totalUsers))
                ->description("Total All User")
                ->icon('heroicon-o-users')
                ->chart($usersSpark14),

            Stat::make('Total Visits', number_format($totalVisits))
                ->description("Today: {$visitsToday}")
                ->icon('heroicon-o-chart-bar')
                ->chart($visitsSpark14),

            Stat::make('New Users (This Week)', number_format($newUsersWeek))
                ->description('Last 7 days')
                ->icon('heroicon-o-user-plus')
                ->chart($usersSpark7),
        ];
    }
}
