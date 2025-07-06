<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkInProgressResource\Pages;
use App\Filament\Resources\WorkInProgressResource\RelationManagers;
use App\Models\WorkInProgress;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WorkInProgressResource extends Resource
{
    protected static ?string $model = WorkInProgress::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('mal_id')
                ->required()
                ->label('MAL ID')
                ->numeric(),
            Forms\Components\TextInput::make('background')
                ->required()
                ->label('Background Image URL'),
            Forms\Components\TextInput::make('progress')
                ->label('Progress (%)')
                ->default(0)
                ->numeric()
                ->minValue(0)->maxValue(100),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\ImageColumn::make('poster')->square()->height(60),
            Tables\Columns\TextColumn::make('title')->searchable(),
            Tables\Columns\TextColumn::make('genres'),
            Tables\Columns\TextColumn::make('progress')->suffix('%')->sortable(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorkInProgress::route('/'),
            'create' => Pages\CreateWorkInProgress::route('/create'),
            'edit' => Pages\EditWorkInProgress::route('/{record}/edit'),
        ];
    }
}
