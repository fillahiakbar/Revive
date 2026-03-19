<?php

namespace App\Filament\Resources\LeaderboardSeasonResource\Pages;

use App\Filament\Resources\LeaderboardSeasonResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLeaderboardSeason extends EditRecord
{
    protected static string $resource = LeaderboardSeasonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
