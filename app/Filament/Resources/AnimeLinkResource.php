<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnimeLinkResource\Pages;
use App\Models\AnimeLink;
use App\Models\AnimeType;
use Filament\Forms\Components\{
    Hidden, TextInput, Textarea, Select, Repeater, Section
};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\View;

class AnimeLinkResource extends Resource
{
    protected static ?string $model = AnimeLink::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Anime Links';
    protected static ?string $pluralModelLabel = 'Anime Links';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Anime Information')
                    ->schema([
                        TextInput::make('mal_id')
                            ->label('MyAnimeList ID')
                            ->required()
                            ->numeric()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $response = Http::get("https://api.jikan.moe/v4/anime/{$state}");

                                if ($response->successful()) {
                                    $data = $response->json()['data'] ?? null;

                                    if ($data) {
                                        $set('title', $data['title']);
                                        $set('poster', $data['images']['jpg']['image_url'] ?? '');
                                        $set('synopsis', $data['synopsis']);
                                        $set('season', $data['season'] ?? null);
                                        $set('year', $data['year'] ?? null);
                                    }
                                }
                            }),

                        TextInput::make('title')->required()->maxLength(255),
                        TextInput::make('poster')->label('Poster URL')->maxLength(512),
                        Textarea::make('synopsis')->rows(4),
                        Select::make('anime_types')
                            ->label('Type')
                            ->relationship('types', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('name')->required(),
                            ])
                            ->required(),
                        TextInput::make('season')->maxLength(50),
                        TextInput::make('year')->maxLength(4),
                    ]),

                Repeater::make('batches')
                    ->relationship()
                    ->label('Batch Episodes')
                    ->schema([
                        Hidden::make('anime_link_id')
                            ->default(fn (\Filament\Forms\Get $get) => $get('../../id')),

                        TextInput::make('name')->label('Name')->required(),
                        Textarea::make('episodes')
                            ->label('Episode List (comma separated)')
                            ->required()
                            ->rows(3)
                            ->maxLength(65535)
                            ->helperText('Example: 1,2,3 or 1-12'),

                        Repeater::make('batchLinks')
                            ->relationship()
                            ->label('Download Links')
                            ->schema([
                                Select::make('resolution')
                                    ->label('Resolution')
                                    ->options([
                                        '360' => '360p',
                                        '480' => '480p',
                                        '720' => '720p',
                                        '1080' => '1080p',
                                    ])
                                    ->required(),

                                Textarea::make('url_torrent')
                                    ->label('Torrent URLs')
                                    ->rows(1),

                                Textarea::make('url_mega')
                                    ->label('Mega URLs')
                                    ->rows(1),

                                Textarea::make('url_gdrive')
                                    ->label('GDrive URLs')
                                    ->rows(1),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('title')->searchable(),
                \Filament\Tables\Columns\TextColumn::make('types.name')->label('Type')->limit(30),
                \Filament\Tables\Columns\TextColumn::make('season'),
                \Filament\Tables\Columns\TextColumn::make('year'),
            ])
            ->actions([
                \Filament\Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                \Filament\Tables\Actions\BulkActionGroup::make([
                    \Filament\Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnimeLinks::route('/'),
            'create' => Pages\CreateAnimeLink::route('/create'),
            'edit' => Pages\EditAnimeLink::route('/{record}/edit'),
        ];
    }

    public static function afterCreate(Form $form, $record): void
    {
        $record->load('batches.batchLinks');

        $rssXml = View::make('rss.feed', ['anime' => $record])->render();

        File::ensureDirectoryExists(public_path('rss'));
        File::put(public_path('rss/' . $record->slug . '.xml'), $rssXml);
    }

    public static function afterSave(Form $form, $record): void
{
    $record->load('batches.batchLinks');

    $rssXml = view('rss.feed', ['anime' => $record])->render();

    \Illuminate\Support\Facades\File::ensureDirectoryExists(public_path('rss'));
    \Illuminate\Support\Facades\File::put(public_path('rss/' . $record->slug . '.xml'), $rssXml);
}
}