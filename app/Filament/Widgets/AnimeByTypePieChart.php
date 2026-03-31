<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\AnimeLink;
use Illuminate\Support\Facades\DB;

class AnimeByTypePieChart extends ChartWidget
{
    protected static ?string $heading = 'Anime by Type';
    protected int|string|array $columnSpan = 1;
    protected static ?string $maxHeight = '300px';
    protected static ?int $sort = 7;

    protected function getData(): array
    {
        $rows = AnimeLink::select('type', DB::raw('COUNT(*) as total'))
            ->whereNotNull('type')
            ->where('type', '!=', '')
            ->groupBy('type')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        $labels = $rows->pluck('type')->map(fn ($t) => ucfirst($t))->all();
        $data   = $rows->pluck('total')->map(fn ($v) => (int) $v)->all();

        // Beautiful color palette
        $colors = [
            '#f43f5e', // Rose
            '#f59e0b', // Amber
            '#0ea5e9', // Sky
            '#8b5cf6', // Violet
            '#10b981', // Emerald
            '#ec4899', // Pink
            '#06b6d4', // Cyan
            '#6366f1', // Indigo
        ];

        return [
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => array_slice($colors, 0, count($data)),
                    'borderColor' => '#1e293b',
                    'borderWidth' => 2,
                    'hoverOffset' => 8,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
