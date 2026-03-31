<?php

namespace App\Filament\Widgets;

use App\Models\Anime;
use App\Models\AnimeLink;
use Filament\Widgets\BarChartWidget;
use Illuminate\Support\Carbon;

class ContentReleaseChart extends BarChartWidget
{
    protected static ?string $heading = 'Anime & Episode Releases (Last 14 Days)';
    
    // We can put columnSpan full or 1 depending on layout
    protected int|string|array $columnSpan = 'full';
    
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $len = 14;
        $start = now()->copy()->subDays($len - 1)->toDateString();

        $animeRows = Anime::selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->whereDate('created_at', '>=', $start)
            ->groupBy('d')
            ->pluck('c', 'd');

        $episodeRows = AnimeLink::selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->whereDate('created_at', '>=', $start)
            ->groupBy('d')
            ->pluck('c', 'd');

        $labels = [];
        $animeData = [];
        $episodeData = [];

        for ($i = 0; $i < $len; $i++) {
            $d = Carbon::parse($start)->addDays($i)->toDateString();
            $labels[] = Carbon::parse($d)->format('d M');
            $animeData[]   = (int) ($animeRows[$d] ?? 0);
            $episodeData[] = (int) ($episodeRows[$d] ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'New Anime',
                    'data'  => $animeData,
                    'backgroundColor' => '#f43f5e', // Tailwind Rose 500
                    'borderRadius' => 4,
                ],
                [
                    'label' => 'New Episodes/Links',
                    'data'  => $episodeData,
                    'backgroundColor' => '#f59e0b', // Tailwind Amber 500
                    'borderRadius' => 4,
                ],
            ],
            'labels' => $labels,
        ];
    }
}
