<?php

namespace App\Filament\Resources\AnimeLinkResource\Pages;

use App\Filament\Resources\AnimeLinkResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAnimeLinks extends ListRecords
{
    protected static string $resource = AnimeLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
