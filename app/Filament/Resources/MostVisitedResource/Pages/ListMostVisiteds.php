<?php

namespace App\Filament\Resources\MostVisitedResource\Pages;

use App\Filament\Resources\MostVisitedResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMostVisiteds extends ListRecords
{
    protected static string $resource = MostVisitedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
