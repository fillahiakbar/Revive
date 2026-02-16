<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static string $view = 'filament.pages.dashboard';

    public static function getNavigationLabel(): string
    {
        return 'Dashboard';
    }

    // ← penting: daftar widget
    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\StatsOverview::class,
            \App\Filament\Widgets\TopAnime::class,
            \App\Filament\Widgets\Users::class,
            \App\Filament\Widgets\DailyVisits::class,
        ];
    }

    
}
