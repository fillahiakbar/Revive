<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RelateAnimeGroupResource\Pages;
use App\Filament\Resources\RelateAnimeGroupResource\RelationManagers;
use App\Models\RelateAnimeGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\{Section, TextInput, Repeater};
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Http;

class RelateAnimeGroupResource extends Resource
{
    protected static ?string $model = RelateAnimeGroup::class;

    protected static ?string $navigationGroup = 'Anime Meta Data';
    protected static ?string $navigationLabel = 'Related Anime Groups';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('بيانات المجموعة')
                ->schema([
                    TextInput::make('name')
                        ->label('اسم المجموعة')
                        ->required(),
                ]),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('اسم المجموعة')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('relatedAnimes_count')
                    ->label('عدد الأنميات')
                    ->counts('relatedAnimes'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\AnimeLinksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRelateAnimeGroups::route('/'),
            'create' => Pages\CreateRelateAnimeGroup::route('/create'),
            'edit' => Pages\EditRelateAnimeGroup::route('/{record}/edit'),
        ];
    }
}
