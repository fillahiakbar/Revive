<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RelateAnimeGroupResource\Pages;
use App\Models\RelateAnimeGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\{Section, TextInput, Repeater};
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Http;

class RelateAnimeGroupResource extends Resource
{
    protected static ?string $model = RelateAnimeGroup::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';
    protected static ?string $navigationLabel = 'مجموعات الأنمي المرتبطة';
    protected static ?string $pluralModelLabel = 'مجموعات الأنمي المرتبطة';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('بيانات المجموعة')
                ->schema([
                    TextInput::make('name')
                        ->label('اسم المجموعة')
                        ->required(),
                ]),

            Section::make('أنميات مرتبطة')
                ->schema([
                    Repeater::make('relatedAnimes')
                        ->relationship()
                        ->label('قائمة الأنميات المرتبطة')
                        ->schema([
                            TextInput::make('mal_id')
                                ->label('MAL ID')
                                ->numeric()
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    $response = Http::get("https://api.jikan.moe/v4/anime/{$state}");

                                    if ($response->successful()) {
                                        $data = $response->json('data');

                                        if (is_array($data)) {
                                            $set('title', $data['title'] ?? '');
                                            $set('title_english', $data['title_english'] ?? '');
                                            $set('poster', $data['images']['jpg']['image_url'] ?? '');
                                        }
                                    }
                                }),

                            TextInput::make('poster')
                                ->label('رابط الصورة')
                                ->required()
                                ->maxLength(512),

                            TextInput::make('title')
                                ->label('عنوان الأنمي')
                                ->required(),

                            TextInput::make('title_english')
                                ->label('العنوان الإنجليزي')
                                ->nullable(),
                        ])
                        ->createItemButtonLabel('➕ أضف أنمي')
                        ->columns(2)
                        ->defaultItems(1),
                ])
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('اسم المجموعة')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('relatedAnimes_count')
                    ->label('عدد الأنميات')
                    ->counts('relatedAnimes'),
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
            'index' => Pages\ListRelateAnimeGroups::route('/'),
            'create' => Pages\CreateRelateAnimeGroup::route('/create'),
            'edit' => Pages\EditRelateAnimeGroup::route('/{record}/edit'),
        ];
    }
}
