<?php

namespace App\Filament\Resources\EmailBlacklistResource\Pages;

use App\Filament\Resources\EmailBlacklistResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEmailBlacklist extends CreateRecord
{
    protected static string $resource = EmailBlacklistResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): string
    {
        return 'تم إضافة البريد الإلكتروني إلى القائمة السوداء بنجاح';
    }

    public function getTitle(): string
    {
        return 'إضافة بريد إلكتروني إلى القائمة السوداء';
    }
}
