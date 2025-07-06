<?php

namespace App\Filament\Resources\AnimeRecomendResource\Pages;

use App\Filament\Resources\AnimeRecomendResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAnimeRecomends extends ListRecords
{
    protected static string $resource = AnimeRecomendResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
