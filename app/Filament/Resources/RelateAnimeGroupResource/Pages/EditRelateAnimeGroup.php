<?php

namespace App\Filament\Resources\RelateAnimeGroupResource\Pages;

use App\Filament\Resources\RelateAnimeGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRelateAnimeGroup extends EditRecord
{
    protected static string $resource = RelateAnimeGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
