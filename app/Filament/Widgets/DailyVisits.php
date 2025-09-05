<?php

namespace App\Filament\Widgets;

use Filament\Widgets\LineChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DailyVisits extends LineChartWidget
{
    protected static ?string $heading = 'Total Visits per Day';
    protected int|string|array $columnSpan = 'full';
    protected static ?string $maxHeight = '300px';

    protected function getFilters(): ?array
    {
        return [
            '7d'  => '7 days',
            '14d' => '14 days',
            '30d' => '30 days',
            '90d' => '90 days',
        ];
    }

    protected function getData(): array
    {
        $len = match ($this->filter ?? '30d') {
            '7d' => 7, '14d' => 14, '30d' => 30, '90d' => 90, default => 30,
        };

        $start = now()->copy()->subDays($len - 1)->toDateString();

        // Sum visits per day across all anime (using visited_date + SUM(count))
        $rows = DB::table('anime_visits')
            ->selectRaw('visited_date, SUM(`count`) AS total')
            ->where('visited_date', '>=', $start)
            ->groupBy('visited_date')
            ->orderBy('visited_date')
            ->pluck('total', 'visited_date');

        $labels = [];
        $data   = [];

        for ($i = 0; $i < $len; $i++) {
            $d = Carbon::parse($start)->addDays($i)->toDateString();
            $labels[] = Carbon::parse($d)->format('d M'); // no intl required
            $data[]   = (int) ($rows[$d] ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Visits',
                    'data'  => $data,
                ],
            ],
            'labels' => $labels,
        ];
    }
}
