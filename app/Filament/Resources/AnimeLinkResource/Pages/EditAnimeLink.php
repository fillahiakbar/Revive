<?php

namespace App\Filament\Resources\AnimeLinkResource\Pages;

use App\Filament\Resources\AnimeLinkResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAnimeLink extends EditRecord
{
    protected static string $resource = AnimeLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
