<?php

namespace App\Filament\Resources\RelateAnimeGroupResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AnimeLinksRelationManager extends RelationManager
{
    protected static string $relationship = 'animeLinks';

    protected static ?string $inverseRelationship = 'relatedGroup';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('relation_title')
                    ->label('Relation Type')
                    ->placeholder('Season 1, Movie, OVA, Special ...')
                    ->maxLength(64),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\ImageColumn::make('poster')->label('Poster'),
                Tables\Columns\TextColumn::make('title')->label('Title')->searchable(),
                Tables\Columns\TextColumn::make('title_english')->label('English Title')->searchable(),
                Tables\Columns\TextColumn::make('type')->label('Type'),
                Tables\Columns\TextColumn::make('relation_title')->label('Relation'),
            ])
            ->headerActions([
                Tables\Actions\AssociateAction::make()
                    ->preloadRecordSelect()
                    ->recordSelectSearchColumns(['title', 'title_english'])
                    ->form(fn (Tables\Actions\AssociateAction $action): array => [
                        $action->getRecordSelect(),
                        Forms\Components\TextInput::make('relation_title')
                            ->label('Relation Type')
                            ->placeholder('Season 1, Movie, OVA, Special ...')
                            ->maxLength(64),
                    ])
                    ->using(function (array $data, RelationManager $livewire): void {
                        $record = \App\Models\AnimeLink::find($data['recordId']);
                        if ($record) {
                            $record->update([
                                'related_anime_group_id' => $livewire->getOwnerRecord()->getKey(),
                                'relation_title' => $data['relation_title'] ?? null,
                            ]);
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DissociateAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DissociateBulkAction::make(),
                ]),
            ]);
    }
}
