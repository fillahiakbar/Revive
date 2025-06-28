<?php

namespace App\Filament\Resources\AnimeTypeResource\Pages;

use App\Filament\Resources\AnimeTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAnimeTypes extends ListRecords
{
    protected static string $resource = AnimeTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
