<?php

namespace App\Filament\Widgets;

use App\Models\AnimeLink;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestAnimeWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';
    
    protected static ?string $heading = 'Latest Added Anime';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                AnimeLink::query()->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\ImageColumn::make('poster')
                    ->label('Poster')
                    ->circular(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Type'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date Added')
                    ->since(),
            ])
            ->paginated(false);
    }
}
