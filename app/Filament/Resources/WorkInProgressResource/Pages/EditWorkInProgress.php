<?php

namespace App\Filament\Resources\WorkInProgressResource\Pages;

use App\Filament\Resources\WorkInProgressResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWorkInProgress extends EditRecord
{
    protected static string $resource = WorkInProgressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
