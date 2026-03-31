<?php

namespace App\Filament\Widgets;

use Filament\Widgets\BarChartWidget;
use App\Models\AnimeLink;
use Illuminate\Support\Str;

class TopAnime extends BarChartWidget
{
    protected static ?string $heading = 'Top Anime by Visits';
    protected int|string|array $columnSpan = 'full';
    protected static ?string $maxHeight = '340px';

    protected function getFilters(): ?array
    {
        return [
            '5'  => 'Top 5',
            '10' => 'Top 10',
            '20' => 'Top 20',
        ];
    }

    protected function getData(): array
    {
        $limit = (int) ($this->filter ?? 10);

        $rows = AnimeLink::query()
            ->select(['title', 'click_count'])
            ->orderByDesc('click_count')
            ->limit($limit)
            ->get();

        $labels = $rows->pluck('title')->map(fn ($t) => Str::limit($t, 24))->all();
        $data   = $rows->pluck('click_count')->map(fn ($v) => (int) $v)->all();

        // Generate gradient-like colors per bar
        $colors = [
            '#f43f5e', '#fb923c', '#facc15', '#4ade80', '#22d3ee',
            '#818cf8', '#c084fc', '#f472b6', '#38bdf8', '#a3e635',
            '#f97316', '#e879f9', '#2dd4bf', '#fbbf24', '#a78bfa',
            '#34d399', '#f87171', '#60a5fa', '#fb7185', '#84cc16',
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Visits',
                    'data'  => $data,
                    'backgroundColor' => array_slice($colors, 0, count($data)),
                    'borderRadius' => 4,
                    'borderWidth' => 0,
                ],
            ],
            'labels' => $labels,
        ];
    }
}
