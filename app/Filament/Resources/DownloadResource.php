<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DownloadResource\Pages;
use App\Models\Download;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Http;

class DownloadResource extends Resource
{
    protected static ?string $model = Download::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
{
    return $form->schema([
        Forms\Components\Select::make('title')
            ->label('Search Anime Title')
            ->searchable()
            ->getSearchResultsUsing(function (string $search): array {
                $results = Http::get('https://api.jikan.moe/v4/anime', [
                    'q' => $search,
                    'limit' => 10,
                ]);

                if (! $results->successful()) {
                    return [];
                }

                return collect($results['data'])->mapWithKeys(function ($anime) {
                    return [$anime['title'] => $anime['title']];
                })->toArray();
            })
            ->afterStateUpdated(function ($state, callable $set) {
                $response = Http::get('https://api.jikan.moe/v4/anime', [
                    'q' => $state,
                    'limit' => 1,
                ]);

                if ($response->successful() && isset($response['data'][0])) {
                    $set('mal_id', $response['data'][0]['mal_id']);
                }
            })
            ->required(),

        Forms\Components\TextInput::make('mal_id')
            ->label('MAL ID')
            ->readOnly()
            ->required()
            ->numeric(),

        Forms\Components\TextInput::make('episode_number')
            ->label('Episode -')
            ->numeric()
            ->required()
            ->minValue(1),

        Forms\Components\TextInput::make('links.torrent')
            ->label('Link Torrent')
            ->url()
            ->nullable(),

        Forms\Components\TextInput::make('links.mp4upload')
            ->label('Link Mp4upload')
            ->url()
            ->nullable(),

        Forms\Components\TextInput::make('links.gdrive')
            ->label('Link Google Drive')
            ->url()
            ->nullable(),

        Forms\Components\TextInput::make('links.arabic_sub')
            ->label('Link Mp4 مع الترجمة')
            ->url()
            ->nullable(),

    ]);
}


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Title')->searchable(),
                Tables\Columns\TextColumn::make('mal_id')->label('MAL ID'),
                Tables\Columns\TextColumn::make('episode_number')->label('Episode')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Tanggal')->dateTime(),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDownloads::route('/'),
            'create' => Pages\CreateDownload::route('/create'),
            'edit' => Pages\EditDownload::route('/{record}/edit'),
        ];
    }
}
