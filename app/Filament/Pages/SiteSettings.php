<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Pages\Page;
use App\Models\Setting;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Toggle;

class SiteSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-8-tooth';

    protected static string $view = 'filament.pages.site-settings';

    protected static ?string $navigationLabel = 'إعدادات الموقع';
    protected static ?string $title = 'إعدادات الموقع';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'public_registration_enabled' => isPublicRegistrationEnabled(),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Toggle::make('public_registration_enabled')
                ->label('تفعيل التسجيل العام')
        ])->statePath('data');
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
