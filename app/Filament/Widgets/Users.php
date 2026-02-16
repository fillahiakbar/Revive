<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\User;
use Illuminate\Support\Carbon;

class Users extends ChartWidget
{
    protected static ?string $heading = 'New Users';
    protected int|string|array $columnSpan = 'full';
    protected static ?string $maxHeight = '300px';

    protected function getFilters(): ?array
    {
        return [
            '14d' => '14 days',
            '30d' => '30 days',
            '12m' => '12 months',
        ];
    }

    protected function getData(): array
    {
        $filter = $this->filter ?? '30d';

        // Monthly (last 12 months)
        if ($filter === '12m') {
            $start  = now()->startOfMonth()->subMonths(12 - 1);

            $months = collect(range(0, 11))->map(fn (int $i) => $start->copy()->addMonths($i));
            $keys   = $months->map(fn ($m) => $m->format('Y-m'));

            $rows = User::selectRaw('DATE_FORMAT(created_at, "%Y-%m") m, COUNT(*) c')
                ->where('created_at', '>=', $start)
                ->groupBy('m')
                ->pluck('c', 'm');

            $data = $keys->map(fn ($k) => (int) ($rows[$k] ?? 0))->all();

            return [
                'datasets' => [
                    ['label' => 'New Users', 'data' => $data],
                ],
                'labels' => $months->map(fn ($m) => $m->format('M Y'))->all(),
            ];
        }

        // Daily (last 14 or 30 days)
        $len   = $filter === '14d' ? 14 : 30;
        $start = now()->copy()->subDays($len - 1)->startOfDay();

        $days = collect(range(0, $len - 1))
            ->map(fn (int $i) => $start->copy()->addDays($i)->toDateString());

        $rows = User::selectRaw('DATE(created_at) d, COUNT(*) c')
            ->where('created_at', '>=', $start)
            ->groupBy('d')
            ->pluck('c', 'd');

        $data = $days->map(fn (string $d) => (int) ($rows[$d] ?? 0))->all();

        return [
            'datasets' => [
                ['label' => 'New Users', 'data' => $data],
            ],
            'labels' => $days->map(fn (string $d) => Carbon::parse($d)->format('d M'))->all(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
