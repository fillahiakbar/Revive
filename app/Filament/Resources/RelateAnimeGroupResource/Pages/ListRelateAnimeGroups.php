<?php

namespace App\Filament\Resources\RelateAnimeGroupResource\Pages;

use App\Filament\Resources\RelateAnimeGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRelateAnimeGroups extends ListRecords
{
    protected static string $resource = RelateAnimeGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
