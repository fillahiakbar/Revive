<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnimeResource\Pages;
use App\Models\Anime;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Http;

class AnimeResource extends Resource
{
    protected static ?string $model = Anime::class;

    protected static ?string $navigationIcon = 'heroicon-o-film';
    protected static ?string $navigationGroup = 'Content';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('mal_id')
                        ->label('MyAnimeList ID')
                        ->required()
                        ->numeric()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            if (!$state) return;

                            $response = Http::get("https://api.jikan.moe/v4/anime/{$state}");
                            if ($response->successful()) {
                                $data = $response->json('data');

                                $set('title', $data['title'] ?? '');
                                $set('title_english', $data['title_english'] ?? '');
                                $set('poster', $data['images']['jpg']['image_url'] ?? '');
                                $set('genres', collect($data['genres'])->pluck('name')->join(', '));
                                $set('score', $data['score'] ?? null);
                                $set('episodes', $data['episodes'] ?? null); 
                            }
                        }),

                    Forms\Components\Select::make('type')
                        ->required()
                        ->options([
                            'work_in_progress' => 'Work in Progress',
                            'recommendation' => 'Recommendation',
                        ]),
                ]),

                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('title_english')
                    ->maxLength(255),

                Forms\Components\TextInput::make('poster')
                    ->label('Poster (URL)')
                    ->url(),

                Forms\Components\Textarea::make('genres')
                    ->rows(2)
                    ->helperText('Contoh: Action, Drama, Sci-Fi'),
                
                Forms\Components\TextInput::make('score')
                    ->numeric()
                    ->label('Score (from MyAnimeList)'),

                Forms\Components\TextInput::make('episodes')
                    ->numeric()
                    ->label('Total Episodes'),

                Forms\Components\FileUpload::make('background')
                    ->disk('public')
                    ->directory('anime/backgrounds')
                    ->image()
                    ->imagePreviewHeight('100')
                    ->helperText('Make sure the background is 1024x768 in size.')
                    ->label('Background'),

                Forms\Components\TextInput::make('progress')
                    ->label('Progress (%)')
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->maxValue(100),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mal_id')->sortable(),
                Tables\Columns\TextColumn::make('title')->limit(40)->sortable()->searchable(),
                Tables\Columns\TextColumn::make('type')->badge(),
                Tables\Columns\TextColumn::make('progress')->suffix('%'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'work_in_progress' => 'Work in Progress',
                        'recommendation' => 'Recommendation',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnimes::route('/'),
            'create' => Pages\CreateAnime::route('/create'),
            'edit' => Pages\EditAnime::route('/{record}/edit'),
        ];
    }
}
