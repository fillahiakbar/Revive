<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\User;
use Illuminate\Support\Carbon;

class UserGrowthChart extends ChartWidget
{
    protected static ?string $heading = 'User Growth (Cumulative)';
    protected int|string|array $columnSpan = 'full';
    protected static ?string $maxHeight = '300px';
    protected static ?int $sort = 5;

    protected function getFilters(): ?array
    {
        return [
            '30d' => '30 days',
            '60d' => '60 days',
            '90d' => '90 days',
        ];
    }

    protected function getData(): array
    {
        $len = match ($this->filter ?? '30d') {
            '30d' => 30, '60d' => 60, '90d' => 90, default => 30,
        };

        $start = now()->copy()->subDays($len - 1)->toDateString();

        // Count total users registered up to $start
        $baseCount = User::where('created_at', '<', $start)->count();

        $dailyRows = User::selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->whereDate('created_at', '>=', $start)
            ->groupBy('d')
            ->pluck('c', 'd');

        $labels = [];
        $cumulativeData = [];
        $running = $baseCount;

        for ($i = 0; $i < $len; $i++) {
            $d = Carbon::parse($start)->addDays($i)->toDateString();
            $labels[] = Carbon::parse($d)->format('d M');
            $running += (int) ($dailyRows[$d] ?? 0);
            $cumulativeData[] = $running;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Users',
                    'data'  => $cumulativeData,
                    'borderColor' => '#8b5cf6', // Violet 500
                    'backgroundColor' => 'rgba(139, 92, 246, 0.15)',
                    'fill' => true,
                    'tension' => 0.4,
                    'pointRadius' => 2,
                    'pointBackgroundColor' => '#8b5cf6',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
