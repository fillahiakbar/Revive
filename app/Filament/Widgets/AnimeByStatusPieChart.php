<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\AnimeLink;
use Illuminate\Support\Facades\DB;

class AnimeByStatusPieChart extends ChartWidget
{
    protected static ?string $heading = 'Anime by Status';
    protected int|string|array $columnSpan = 1;
    protected static ?string $maxHeight = '300px';
    protected static ?int $sort = 8;

    protected function getData(): array
    {
        $rows = AnimeLink::select('status', DB::raw('COUNT(*) as total'))
            ->whereNotNull('status')
            ->where('status', '!=', '')
            ->groupBy('status')
            ->orderByDesc('total')
            ->get();

        $labels = $rows->pluck('status')->map(fn ($s) => ucfirst($s))->all();
        $data   = $rows->pluck('total')->map(fn ($v) => (int) $v)->all();

        // Distinct color palette for status
        $colors = [
            '#10b981', // Emerald  — Finished Airing
            '#f59e0b', // Amber    — Currently Airing
            '#6366f1', // Indigo   — Not Yet Aired
            '#ef4444', // Red      
            '#06b6d4', // Cyan     
            '#ec4899', // Pink     
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
        return 'doughnut';
    }
}
