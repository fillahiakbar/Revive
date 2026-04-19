<?php

namespace App\Filament\Resources\EmailBlacklistResource\Pages;

use App\Filament\Resources\EmailBlacklistResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmailBlacklists extends ListRecords
{
    protected static string $resource = EmailBlacklistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('إضافة إلى القائمة السوداء'),
        ];
    }

    public function getTitle(): string
    {
        return 'القائمة السوداء للبريد الإلكتروني';
    }
}
