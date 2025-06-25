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
    protected static ?string $navigationLabel = 'روابط الأنمي';
    protected static ?string $pluralModelLabel = 'روابط الأنمي';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('معلومات الأنمي')
                    ->schema([
                        TextInput::make('mal_id')
                            ->label('معرّف MyAnimeList')
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

                        TextInput::make('title')->label('العنوان')->required()->maxLength(255),
                        TextInput::make('poster')->label('رابط الصورة')->maxLength(512),
                        \Filament\Forms\Components\RichEditor::make('synopsis')
    ->label('الملخص')
    ->toolbarButtons([
        'bold',
        'italic',
        'strike',
        'underline',
        'h2',
        'h3',
        'bulletList',
        'orderedList',
        'blockquote',
        'link',
    ])
    ->columnSpan('full'),

                        Select::make('anime_types')
                            ->label('النوع')
                            ->relationship('types', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('name')->label('الاسم')->required(),
                                TextInput::make('color')
                                    ->label('اللون (Hex)')
                                    ->required()
                                    ->helperText('مثال: #FF0000'),
                            ])
                            ->required(),

                        TextInput::make('season')->label('الموسم')->maxLength(50),
                        TextInput::make('year')->label('السنة')->maxLength(4),
                    ]),

                Repeater::make('batches')
                    ->relationship()
                    ->label('حزم الحلقات')
                    ->schema([
                        Hidden::make('anime_link_id')
                            ->default(fn (\Filament\Forms\Get $get) => $get('../../id')),

                        TextInput::make('name')->label('الاسم')->required(),
                        Textarea::make('episodes')
                            ->label('قائمة الحلقات (مفصولة بفواصل)')
                            ->required()
                            ->rows(3)
                            ->maxLength(65535)
                            ->helperText('مثال: 1,2,3 أو 1-12'),

                        Repeater::make('batchLinks')
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

                                Textarea::make('url_torrent')
                                    ->label('روابط التورنت')
                                    ->rows(1),

                                Textarea::make('url_mega')
                                    ->label('روابط ميجا')
                                    ->rows(1),

                                Textarea::make('url_gdrive')
                                    ->label('روابط Google Drive')
                                    ->rows(1),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('title')->label('العنوان')->searchable(),
                \Filament\Tables\Columns\TextColumn::make('types.name')->label('Type')->limit(30),
                \Filament\Tables\Columns\TextColumn::make('season')->label('الموسم'),
                \Filament\Tables\Columns\TextColumn::make('year')->label('السنة'),
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

        File::ensureDirectoryExists(public_path('rss'));
        File::put(public_path('rss/' . $record->slug . '.xml'), $rssXml);
    }
}
