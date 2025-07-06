<?php

namespace App\Filament\Resources\WorkInProgressResource\Pages;

use App\Filament\Resources\WorkInProgressResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWorkInProgress extends ListRecords
{
    protected static string $resource = WorkInProgressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
