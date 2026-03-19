<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
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
            'funding_information' => Setting::get('funding_information', ''),
            'season_end_date' => Setting::get('season_end_date', ''),
            'rank1_achievement' => Setting::get('rank1_achievement', 'CHAMPION'),
            'leaderboard_prize' => Setting::get('leaderboard_prize', ''),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Toggle::make('public_registration_enabled')
                    ->label('تفعيل التسجيل العام'),
                RichEditor::make('funding_information')
                    ->label('معلومات التمويل')
                    ->columnSpanFull(),

                Section::make('Leaderboard Settings')
                    ->description('إعدادات لوحة المتصدرين')
                    ->schema([
                        DateTimePicker::make('season_end_date')
                            ->label('تاريخ انتهاء الموسم (Season End Date)')
                            ->helperText('حدد التاريخ والوقت الذي ينتهي فيه الموسم الحالي')
                            ->native(false),
                        TextInput::make('rank1_achievement')
                            ->label('إنجاز المركز الأول (Rank #1 Achievement)')
                            ->helperText('النص أو اللقب الذي يظهر للمركز الأول في لوحة المتصدرين')
                            ->placeholder('e.g. CHAMPION, LEGEND, MVP')
                            ->maxLength(50),
                        TextInput::make('leaderboard_prize')
                            ->label('جائزة المتصدرين (Prize Text)')
                            ->helperText('النص الذي يظهر كجائزة في بطاقات المتصدرين — اتركه فارغاً لإخفاء قسم الجائزة')
                            ->placeholder('e.g. Premium Account 1 Month, VIP Badge')
                            ->maxLength(100),
                    ]),
            ])
            ->statePath('data');
    }

    public function save()
    {
        Setting::set('public_registration_enabled', $this->data['public_registration_enabled']);
        Setting::set('funding_information', $this->data['funding_information']);
        Setting::set('season_end_date', $this->data['season_end_date'] ?? '');
        Setting::set('rank1_achievement', $this->data['rank1_achievement'] ?? 'CHAMPION');
        Setting::set('leaderboard_prize', $this->data['leaderboard_prize'] ?? '');

        Notification::make()
            ->title('تم حفظ الإعدادات بنجاح!')
            ->success()
            ->send();
    }
}
