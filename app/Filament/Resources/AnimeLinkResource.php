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
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'روابط الأنمي';
    protected static ?string $pluralModelLabel = 'روابط الأنمي';

    public static function form(Form $form): Form
    {
        return $form->schema([
            self::animeInfoSection(),
            self::batchesSection(),
        ]);
    }

    protected static function animeInfoSection(): Section
    {
        return Section::make('معلومات الأنمي')
            ->schema([
                TextInput::make('mal_id')
                    ->label('معرّف MyAnimeList')
                    ->required()
                    ->numeric()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        if (blank($state)) return;

                        $response = Http::timeout(10)->get("https://api.jikan.moe/v4/anime/{$state}");
                        if (! $response->successful()) return;

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
                            ->map(fn($s) => trim((string) $s))
                            ->implode(', ');
                        $set('genres', $genresStr);

                        $set('mal_score', $data['score'] ?? null);

                        $totalEpisodes = $data['episodes'] ?? null;
                        if (is_int($totalEpisodes) && $totalEpisodes > 1) {
                            $set('episodes', "1-{$totalEpisodes}");
                        } elseif ($totalEpisodes === 1) {
                            $set('episodes', '1');
                        } else {
                            $set('episodes', null);
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

                TextInput::make('title')->label('العنوان')->required(),
                TextInput::make('poster')->label('رابط الصورة'),

                TextInput::make('episodes')
                    ->label('عدد الحلقات')
                    ->placeholder('مثال: 1-6')
                    ->rule('regex:/^\d+(-\d+)?$/')
                    ->helperText('أدخل رقماً أو نطاقاً، مثل: 1-6'),

                TextInput::make('title_english')->label('العنوان بالإنجليزية'),

                TextInput::make('genres')
                    ->label('الأنواع')
                    ->dehydrated(true)
                    ->dehydrateStateUsing(function ($state) {
                        if (is_array($state)) {
                            return implode(', ', array_filter(array_map(fn($s) => trim((string) $s), $state)));
                        }
                        $decoded = json_decode((string) $state, true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                            return implode(', ', array_filter(array_map(fn($s) => trim((string) $s), $decoded)));
                        }
                        $str = (string) $state;
                        $str = preg_replace('/\s*,\s*/', ', ', $str);
                        return trim($str);
                    })
                    ->formatStateUsing(function ($state) {
                        if (is_array($state)) {
                            return implode(', ', array_map(fn($s)=>trim((string)$s), $state));
                        }
                        $decoded = json_decode((string)$state, true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                            return implode(', ', array_map(fn($s)=>trim((string)$s), $decoded));
                        }
                        return (string) $state;
                    }),

                RichEditor::make('synopsis')->label('الملخص')->columnSpan('full'),
                Select::make('anime_types')
                    ->label('الوسوم (Tags)')
                    ->relationship('types', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('season')->label('الموسم'),
                TextInput::make('year')->label('السنة'),
                TextInput::make('type')->label('النوع'),
                TextInput::make('duration')->label('المدة')->placeholder('Ex : دقائق 24 or Minutes 24')->maxLength(100),
                TextInput::make('mal_score')->label('تقييم MAL')->numeric()->dehydrated(true),
                TextInput::make('imdb_score')->label('تقييم IMDb')->numeric()->dehydrated(true),
                TextInput::make('imdb_id')->label('معرّف IMDb')->dehydrated(true),

                Select::make('related_anime_group_id')
                    ->label('المجموعة المرتبطة')
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
            ->label('حزم الحلقات')
            ->schema([
                Hidden::make('anime_link_id')->default(fn (\Filament\Forms\Get $get) => $get('../../id')),
                TextInput::make('name')->label('الاسم')->required(),
                Textarea::make('episodes')->label('قائمة الحلقات')->rows(3)->required(),
                self::batchLinksRepeater(),
            ]);
    }

    protected static function batchLinksRepeater(): Repeater
    {
        return Repeater::make('batchLinks')
            ->relationship()
            ->label('روابط التحميل')
            ->schema([
                Select::make('resolution')
                    ->label('الدقة')
                    ->options([
                        '360' => '360p',
                        '480' => '480p',
                        '720' => '720p',
                        '1080' => '1080p',
                    ])
                    ->required(),
                Textarea::make('url_torrent')->label('روابط التورنت')->rows(1),
                Textarea::make('url_mega')->label('روابط ميجا')->rows(1),
                Textarea::make('url_gdrive')->label('روابط Google Drive')->rows(1),
                Textarea::make('url_megaHard')->label('روابط ميجا Hardsub')->rows(1),
                Textarea::make('url_gdriveHard')->label('روابط Google Drive Hardsub')->rows(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('العنوان')->searchable(),
                TextColumn::make('type')->label('النوع الرسمي'),
                TextColumn::make('types.name')->label('النوع')->limit(30),
                TextColumn::make('season')->label('الموسم'),
                TextColumn::make('year')->label('السنة'),
                TextColumn::make('relatedGroup.name')->label('المجموعة المرتبطة'),
                TextColumn::make('synopsis')->label('الملخص')->html()->formatStateUsing(fn ($state) => "<div style='text-align: justify;'>{$state}</div>")->limit(200),
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
