<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MostVisitedResource\Pages;
use App\Models\AnimeLink;
use App\Models\SiteSetting;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MostVisitedResource extends Resource
{
    protected static ?string $model = AnimeLink::class;

    protected static ?string $navigationIcon   = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel  = 'Most Visited';
    protected static ?string $pluralModelLabel = 'Most Visited';

    public static function form(Form $form): Form
    {

        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Anime Title')
                    ->searchable(),

                Tables\Columns\TextColumn::make('period_clicks')
                    ->label('Visits in Period')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => (int) ($state ?? 0)),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable(),
            ])

          
            ->modifyQueryUsing(function (Builder $query) {
                $filters = request()->input('tableFilters', []);
                $period  = data_get($filters, 'period.value', 'all_time');

     
                $query->withPeriodClicks($period);


                if (in_array($period, ['weekly', 'monthly'], true)) {
                    $query->whereHasVisitsInPeriod($period);
                }
            })

            ->filters([
                SelectFilter::make('period')
                    ->label('Period')
                    ->options([
                        'weekly'   => 'Weekly',
                        'monthly'  => 'Monthly',
                        'all_time' => 'All Time',
                    ])
                    ->default('all_time')
                    ->query(fn (Builder $query, array $data): Builder => $query),
            ])

            ->defaultSort('period_clicks', 'desc')

            ->headerActions([
                Action::make('setPeriod')
                    ->label('Set Global Period')
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->form([
                        Select::make('period')
                            ->label('Most Visited Period')
                            ->options([
                                'weekly'   => 'Weekly',
                                'monthly'  => 'Monthly',
                                'all_time' => 'All Time',
                            ])
                            ->required()
                            ->default(SiteSetting::getMostVisitedPeriod()),
                    ])
                    ->action(function (array $data) {
                        SiteSetting::setMostVisitedPeriod($data['period']);
                        Notification::make()
                            ->title('Most Visited period updated')
                            ->success()
                            ->send();
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMostVisiteds::route('/'),
        ];
    }
    public static function canCreate(): bool
{
    return false;
}
    protected function getHeaderActions(): array
    {
        return [];
    }
}
