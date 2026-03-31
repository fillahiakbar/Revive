<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Comment;
use Illuminate\Support\Carbon;

class CommentsActivityChart extends ChartWidget
{
    protected static ?string $heading = 'Comments Activity';
    protected int|string|array $columnSpan = 1;
    protected static ?string $maxHeight = '300px';
    protected static ?int $sort = 6;

    protected function getFilters(): ?array
    {
        return [
            '7d'  => '7 days',
            '14d' => '14 days',
            '30d' => '30 days',
        ];
    }

    protected function getData(): array
    {
        $len = match ($this->filter ?? '14d') {
            '7d' => 7, '14d' => 14, '30d' => 30, default => 14,
        };

        $start = now()->copy()->subDays($len - 1)->toDateString();

        $dailyRows = Comment::selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->whereDate('created_at', '>=', $start)
            ->groupBy('d')
            ->pluck('c', 'd');

        $labels = [];
        $data = [];

        for ($i = 0; $i < $len; $i++) {
            $d = Carbon::parse($start)->addDays($i)->toDateString();
            $labels[] = Carbon::parse($d)->format('d M');
            $data[] = (int) ($dailyRows[$d] ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Comments',
                    'data'  => $data,
                    'borderColor' => '#10b981', // Emerald 500
                    'backgroundColor' => 'rgba(16, 185, 129, 0.15)',
                    'fill' => true,
                    'tension' => 0.4,
                    'pointRadius' => 3,
                    'pointBackgroundColor' => '#10b981',
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
