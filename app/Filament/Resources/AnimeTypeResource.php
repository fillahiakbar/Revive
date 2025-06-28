<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnimeTypeResource\Pages;
use App\Models\AnimeType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AnimeTypeResource extends Resource
{
    protected static ?string $model = AnimeType::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationLabel = 'وسوم الأنمي';
    protected static ?string $pluralModelLabel = 'وسوم الأنمي';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('الاسم')
                ->required(),

            Forms\Components\TextInput::make('color')
                ->label('اللون (Hex)')
                ->required()
                ->regex('/^#([A-Fa-f0-9]{6})$/')
                ->afterStateUpdated(function ($state, callable $set) {
                    $set('color', strtoupper($state));
                })
                ->helperText('مثال: #FF0000'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable(),

                Tables\Columns\TextColumn::make('color')
                    ->label('اللون')
                    ->formatStateUsing(fn ($state) => "<span style='color: {$state}'>{$state}</span>")
                    ->html(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnimeTypes::route('/'),
            'create' => Pages\CreateAnimeType::route('/create'),
            'edit' => Pages\EditAnimeType::route('/{record}/edit'),
        ];
    }
}
