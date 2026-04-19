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
use Filament\Tables\Actions\Action;
use Illuminate\Support\Carbon;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'User Management';
    protected static ?string $slug = 'users';

    protected static ?string $navigationLabel = 'المستخدمون';
    protected static ?string $pluralModelLabel = 'المستخدمون';
    protected static ?string $modelLabel = 'مستخدم';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('الاسم الكامل')
                ->required()
                ->minLength(3)
                ->maxLength(30)
                ->rules([new \App\Rules\ValidUsername])
                ->helperText('3-30 حرف، أحرف وأرقام و _ و . فقط'),

            Forms\Components\TextInput::make('email')
                ->label('البريد الإلكتروني')
                ->required()
                ->email()
                ->unique(ignoreRecord: true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('البريد الإلكتروني')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('التحقق')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->tooltip(fn($record) => $record->email_verified_at ? 'مفعل' : 'غير مفعل')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ التسجيل')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('email_verified_at')
                    ->label('التحقق من البريد')
                    ->placeholder('الكل')
                    ->trueLabel('مفعل')
                    ->falseLabel('غير مفعل')
                    ->nullable(),

                Tables\Filters\Filter::make('registered_today')
                    ->label('المسجلون اليوم')
                    ->query(fn(Builder $query): Builder => $query->whereDate('created_at', today()))
                    ->toggle(),

                Tables\Filters\Filter::make('registered_this_week')
                    ->label('المسجلون هذا الأسبوع')
                    ->query(fn(Builder $query): Builder => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('تعديل'),
                Tables\Actions\DeleteAction::make()->label('حذف'),

                Action::make('toggleVerification')
                    ->label(fn($record) => $record->email_verified_at ? 'إلغاء التحقق' : 'تحقق الآن')
                    ->color(fn($record) => $record->email_verified_at ? 'warning' : 'success')
                    ->icon(fn($record) => $record->email_verified_at ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->action(function ($record) {
                        $record->email_verified_at = $record->email_verified_at ? null : Carbon::now();
                        $record->save();
                    })
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->label('حذف المحدد'),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('30s');
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
