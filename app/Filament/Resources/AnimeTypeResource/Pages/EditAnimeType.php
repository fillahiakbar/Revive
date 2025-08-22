<?php

namespace App\Filament\Resources\AnimeTypeResource\Pages;

use App\Filament\Resources\AnimeTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAnimeType extends EditRecord
{
    protected static string $resource = AnimeTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
