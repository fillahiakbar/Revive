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

        return [
            'datasets' => [
                [
                    'label' => 'Visits',
                    'data'  => $data,
                ],
            ],
            'labels' => $labels,
        ];
    }
}
