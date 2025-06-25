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

            Forms\Components\Select::make('status')
                ->label('الحالة')
                ->options([
                    'active' => 'نشط',
                    'blocked' => 'محظور',
                ])
                ->required(),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')
                ->label('الاسم'),

            Tables\Columns\TextColumn::make('email')
                ->label('البريد الإلكتروني'),

            Tables\Columns\TextColumn::make('status')
                ->label('الحالة'),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('status')
                ->label('تصفية حسب الحالة')
                ->options([
                    'active' => 'نشط',
                    'blocked' => 'محظور',
                ]),
        ])
        ->actions([
            Tables\Actions\EditAction::make()->label('تعديل'),
            Tables\Actions\DeleteAction::make()->label('حذف'),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make()->label('حذف متعدد'),
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
