<?php

namespace App\Filament\Resources\LeaderboardSeasonResource\Pages;

use App\Filament\Resources\LeaderboardSeasonResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeaderboardSeasons extends ListRecords
{
    protected static string $resource = LeaderboardSeasonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('start_new_season')
                ->label('Start New Season')
                ->color('success')
                ->icon('heroicon-o-plus')
                ->form([
                    \Filament\Forms\Components\TextInput::make('name')
                        ->label('Season Name')
                        ->placeholder('e.g. Season 2')
                        ->required(),
                ])
                ->action(function (array $data) {
                    \Illuminate\Support\Facades\DB::transaction(function () use ($data) {
                        // 1. Archive current active season
                        \App\Models\LeaderboardSeason::where('is_active', true)
                            ->update([
                                'is_active' => false,
                                'end_date' => now(),
                            ]);

                        // 2. Create new active season
                        \App\Models\LeaderboardSeason::create([
                            'name' => $data['name'],
                            'start_date' => now(),
                            'is_active' => true,
                        ]);
                    });

                    \Filament\Notifications\Notification::make()
                        ->success()
                        ->title('New season started successfully!')
                        ->send();
                })
                ->requiresConfirmation(),
            Actions\CreateAction::make(),
        ];
    }
}
