<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;

class SiteSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-8-tooth';
    protected static string $view = 'filament.pages.site-settings';
    protected static ?string $navigationLabel = 'إعدادات الموقع';
    protected static ?string $title = 'إعدادات الموقع';

    public ?array $data = [];

    public function mount(): void
    {
$this->data = [
    'public_registration_enabled' => isPublicRegistrationEnabled() === true,
];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Toggle::make('public_registration_enabled')
                    ->label('تفعيل التسجيل العام'),
            ])
            ->statePath('data');
    }

    public function save()
    {
        Setting::set('public_registration_enabled', $this->data['public_registration_enabled']);

        Notification::make()
            ->title('تم حفظ الإعدادات بنجاح!')
            ->success()
            ->send();
    }
}
