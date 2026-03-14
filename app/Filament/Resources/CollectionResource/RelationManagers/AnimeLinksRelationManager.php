<?php

namespace App\Filament\Resources\CollectionResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AnimeLinksRelationManager extends RelationManager
{
    protected static string $relationship = 'animeLinks';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('collection_label')
                    ->label('Collection Label')
                    ->placeholder('Main Story, Recommended, Canon, dll')
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\ImageColumn::make('poster'),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('year'),
                Tables\Columns\TextInputColumn::make('sort_order')
                    ->label('Order')
                    ->type('number')
                    ->sortable(),
                Tables\Columns\TextColumn::make('collection_label')
                    ->label('Label')
                    ->getStateUsing(fn($record) => $record->pivot->collection_label)
                    ->placeholder('-'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->recordSelectSearchColumns(['title', 'title_english'])
                    ->form(fn(Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        Forms\Components\TextInput::make('collection_label')
                            ->label('Collection Label')
                            ->placeholder('Main Story, Recommended, Canon, dll')
                            ->maxLength(255),
                    ]),
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
                Tables\Actions\EditAction::make()
                    ->mutateRecordDataUsing(function (array $data, $record): array {
                        $data['collection_label'] = $record->pivot->collection_label;
                        return $data;
                    })
                    ->using(function ($record, array $data): void {
                        $record->update(['title' => $data['title']]);
                        $record->pivot->update(['collection_label' => $data['collection_label'] ?? null]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order', 'asc');
    }
}
