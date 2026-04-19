<?php

namespace App\Filament\Resources\EmailBlacklistResource\Pages;

use App\Filament\Resources\EmailBlacklistResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmailBlacklist extends EditRecord
{
    protected static string $resource = EmailBlacklistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('حذف'),
        ];
    }

    protected function getSavedNotificationTitle(): string
    {
        return 'تم تحديث القائمة السوداء بنجاح';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string
    {
        return 'تعديل القائمة السوداء';
    }
}
