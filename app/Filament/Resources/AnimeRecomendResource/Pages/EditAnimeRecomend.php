<?php

namespace App\Filament\Resources\AnimeRecomendResource\Pages;

use App\Filament\Resources\AnimeRecomendResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAnimeRecomend extends EditRecord
{
    protected static string $resource = AnimeRecomendResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
