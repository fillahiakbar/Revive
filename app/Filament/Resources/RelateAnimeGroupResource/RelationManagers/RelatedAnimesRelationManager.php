<?php

namespace App\Filament\Resources\RelateAnimeGroupResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\AnimeLink;

class RelatedAnimesRelationManager extends RelationManager
{
    protected static string $relationship = 'relatedAnimes';

    protected static ?string $title = 'Related Animes';

    protected static ?string $modelLabel = 'Related Anime';
    
    protected static ?string $pluralModelLabel = 'Related Animes';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('anime_link_id')
                    ->label('Select Anime')
                    ->searchable()
                    ->getSearchResultsUsing(
                        fn (string $search) => AnimeLink::where('title', 'like', "%{$search}%")
                            ->orWhere('title_english', 'like', "%{$search}%")
                            ->limit(50)
                            ->pluck('title', 'id')
                    )
                    ->getOptionLabelUsing(
                        fn ($value) => AnimeLink::find($value)?->title
                    )
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $anime = AnimeLink::find($state);
                        if ($anime) {
                            $set('mal_id', $anime->mal_id);
                            $set('title', $anime->title);
                            $set('title_english', $anime->title_english);
                            $set('poster', $anime->poster);
                        }
                    })
                    ->required(),
                
                Forms\Components\TextInput::make('relation_title')
                    ->label('Relation Type (e.g., Sequel, Prequel)')
                    ->placeholder('Season 1, Movie, OVA, Special ...')
                    ->required(),

                // Hidden fields — auto-filled from selected AnimeLink
                Forms\Components\Hidden::make('mal_id'),
                Forms\Components\Hidden::make('title'),
                Forms\Components\Hidden::make('title_english'),
                Forms\Components\Hidden::make('poster'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\ImageColumn::make('poster')
                    ->label('Poster')
                    ->circular(),
                Tables\Columns\TextColumn::make('title')
                     ->label('Title')
                     ->searchable(),
                Tables\Columns\TextColumn::make('relation_title')
                     ->label('Relation Type'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add Related Anime'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
