<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Carbon;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $slug = 'users';

    protected static ?string $navigationLabel = 'المستخدمون';
    protected static ?string $pluralModelLabel = 'المستخدمون';
    protected static ?string $modelLabel = 'مستخدم';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('الاسم الكامل')
                ->required(),

            Forms\Components\TextInput::make('email')
                ->label('البريد الإلكتروني')
                ->required()
                ->email()
                ->unique(),
        ]);
    }

   public static function table(Tables\Table $table): Tables\Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('name')->label('الاسم'),
            Tables\Columns\TextColumn::make('email')->label('البريد الإلكتروني'),
            Tables\Columns\TextColumn::make('email_verified_at')
                ->label('التحقق')
                ->formatStateUsing(fn ($state) => $state ? '✅ مفعل' : '❌ غير مفعل'),
        ])
        ->actions([
            Tables\Actions\EditAction::make()->label('تعديل'),
            Tables\Actions\DeleteAction::make()->label('حذف'),

            // ✅ Tombol aktif/nonaktif verifikasi
            Action::make('toggleVerification')
                ->label(fn ($record) => $record->email_verified_at ? 'إلغاء التحقق' : 'تحقق الآن')
                ->color(fn ($record) => $record->email_verified_at ? 'warning' : 'success')
                ->icon(fn ($record) => $record->email_verified_at ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                ->action(function ($record) {
                    $record->email_verified_at = $record->email_verified_at ? null : Carbon::now();
                    $record->save();
                })
                ->requiresConfirmation()
                ->visible(fn ($record) => true),
        ]);
}

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
