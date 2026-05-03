<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnimeLinkResource\Pages;
use App\Models\AnimeLink;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class AnimeLinkResource extends Resource
{
    protected static ?string $model = AnimeLink::class;
    protected static ?string $navigationGroup = 'Content';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Anime Links';
    protected static ?string $pluralModelLabel = 'Anime Links';

    public static function form(Form $form): Form
    {
        return $form->schema([
            self::animeInfoSection(),
            self::batchesSection(),
        ]);
    }

    protected static function animeInfoSection(): Section
    {
        return Section::make('Anime Information')
            ->schema([
            TextInput::make('mal_id')
            ->label('MyAnimeList ID')
            ->required()
            ->numeric()
            ->reactive()
            ->afterStateUpdated(function ($state, callable $set) {
            if (blank($state))
                return;

            $response = Http::timeout(10)
                ->get("https://api.jikan.moe/v4/anime/{$state}");

            if (!$response->successful())
                return;

            $data = $response->json('data');

            $set('title', $data['title'] ?? '');
            $set('title_english', $data['title_english'] ?? '');
            $set('poster', $data['images']['jpg']['image_url'] ?? '');
            $set('synopsis', $data['synopsis'] ?? '');
            $set('season', $data['season'] ?? null);
            $set('year', $data['year'] ?? null);
            $set('type', $data['type'] ?? '');

            $genresStr = collect($data['genres'] ?? [])
                ->pluck('name')
                ->filter()
                ->implode(', ');

            $set('genres', $genresStr);
            $set('mal_score', $data['score'] ?? null);

            $totalEpisodes = $data['episodes'] ?? null;
            if (is_int($totalEpisodes) && $totalEpisodes > 1) {
                $set('episodes', "1-{$totalEpisodes}");
            }
            elseif ($totalEpisodes === 1) {
                $set('episodes', '1');
            }
            else {
                $set('episodes', null);
            }

            $incomingStatus = $data['status'] ?? null;
            if ($incomingStatus === 'Finished')
                $incomingStatus = 'Finished Airing';

            if (in_array($incomingStatus, ['Currently Airing', 'Finished Airing'], true)) {
                $set('status', $incomingStatus);
            }

            $omdb = Http::timeout(10)->get(config('services.omdb.url'), [
                    'apikey' => config('services.omdb.key'),
                    't' => $data['title'] ?? '',
                ]);

            if ($omdb->ok()) {
                $set('imdb_id', $omdb->json('imdbID') ?? null);
                $set('imdb_score', $omdb->json('imdbRating') ?? null);
            }
        }),

            TextInput::make('title')->label('Title')->required(),
            TextInput::make('poster')->label('Poster URL'),

            TextInput::make('episodes')
            ->label('Total Episodes')
            ->placeholder('Example: 1-6')
            ->rule('regex:/^\d+(-\d+)?$/')
            ->helperText('Enter a number or range, e.g., 1-6'),

            TextInput::make('title_english')->label('English Title'),

            TextInput::make('genres')
            ->label('Genres')
            ->dehydrated(true)
            ->dehydrateStateUsing(function ($state) {
            if (is_array($state)) {
                return implode(', ', array_filter($state));
            }
            return trim((string)$state);
        }),

            Select::make('status')
            ->label('Status')
            ->options([
                'Currently Airing' => 'Currently Airing',
                'Finished Airing' => 'Finished Airing',
            ])
            ->required(),

            RichEditor::make('synopsis')
            ->label('Synopsis')
            ->columnSpan('full'),

            Select::make('anime_types')
            ->label('Tags')
            ->relationship('types', 'name')
            ->multiple()
            ->searchable()
            ->preload()
            ->required(),

            TextInput::make('season')->label('Season'),
            TextInput::make('year')->label('Year'),
            TextInput::make('type')->label('Type'),
            TextInput::make('duration')
            ->label('Duration')
            ->placeholder('Ex: 24 min')
            ->maxLength(100),

            TextInput::make('mal_score')
            ->label('MAL Score')
            ->numeric()
            ->dehydrated(true),

            TextInput::make('imdb_score')
            ->label('IMDb Score')
            ->numeric()
            ->dehydrated(true),

            TextInput::make('imdb_id')
            ->label('IMDb ID')
            ->dehydrated(true),

            Section::make('Subtitles')
                ->description('Manage subtitle files or links.')
                ->schema([
                    TextInput::make('subtitle_url')
                        ->label('Subtitle URL')
                        ->URL()
                        ->placeholder('https://...'),
                    TextInput::make('subtitle_url_pixeldrain')
                        ->label('PixelDrain URL')
                        ->URL()
                        ->placeholder('https://pixeldrain.com/...'),
                ]),

            Select::make('related_anime_group_id')
            ->label('Related Group')
            ->relationship('relatedGroup', 'name')
            ->searchable()
            ->preload()
            ->nullable(),
        ]);
    }

    protected static function batchesSection(): Repeater
    {
        return Repeater::make('batches')
            ->relationship()
            ->label('Episode Batches')
            ->columnSpan('full')
            ->schema([
            Hidden::make('anime_link_id')
            ->default(fn(\Filament\Forms\Get $get) => $get('../../id')),

            TextInput::make('name')->label('Name')->required(),

            Textarea::make('episodes')
            ->label('Episode List')
            ->rows(3)
            ->required(),

            self::batchLinksRepeater(),
        ]);
    }

    protected static function batchLinksRepeater(): Repeater
    {
        return Repeater::make('batchLinks')
            ->relationship()
            ->label('Download Links')
            ->grid(3)
            ->schema([
            TextInput::make('codec')
            ->label('Codec / Format')
            ->datalist([
                'x264' => 'H.264 (x264)',
                'x265' => 'HEVC (x265)',
                'AV1' => 'AV1',
                'Hardsub' => 'Hardsub',
                'Dual Audio' => 'Dual Audio',
                'RAW' => 'RAW',
                'xvid' => 'XViD',
            ])
            ->required()
            ->default('x264')
            ->helperText('Select from preset or type custom codec/format'),

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
            ->label('Torrent URL')
            ->rows(1),

            TextInput::make('url_rr_torrent')
            ->label('RR Torrent URL')
            ->placeholder('torrent file path'),

            Textarea::make('url_mega')->label('Mega URL')->rows(1),
            Textarea::make('url_gdrive')->label('Google Drive URL')->rows(1),
            Textarea::make('url_megaHard')->label('Mega Hardsub URL')->rows(1),
            Textarea::make('url_gdriveHard')->label('Google Drive Hardsub URL')->rows(1),
            Textarea::make('url_pixeldrain')->label('PixelDrain URL')->rows(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('title')->label('Title')->searchable(),
            TextColumn::make('status')->label('Status')->badge(),
            TextColumn::make('type')->label('Official Type'),
            TextColumn::make('types.name')->label('Tags')->limit(30),
            TextColumn::make('season')->label('Season'),
            TextColumn::make('year')->label('Year'),
            TextColumn::make('relatedGroup.name')->label('Related Group'),
            TextColumn::make('synopsis')
            ->label('Synopsis')
            ->html()
            ->limit(200),
            TextColumn::make('updated_at')
                ->label('Last Updated')
                ->dateTime()
                ->sortable(),
        ])
            ->defaultSort('updated_at', 'desc')
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
        self::generateRssFeed($record);
    }

    public static function afterSave(Form $form, $record): void
    {
        self::generateRssFeed($record);
    }

    protected static function generateRssFeed($record): void
    {
        $record->load('batches.batchLinks');
        $rssXml = view('rss.feed', ['anime' => $record])->render();

        File::ensureDirectoryExists(public_path('rss'));
        File::put(public_path("rss/{$record->slug}.xml"), $rssXml);
    }
}
