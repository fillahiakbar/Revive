<?php

namespace App\Filament\Resources\BatchLinkResource\Pages;

use App\Filament\Resources\BatchLinkResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBatchLink extends EditRecord
{
    protected static string $resource = BatchLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
