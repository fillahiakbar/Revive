<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BatchLinkResource\Pages;
use App\Models\BatchLink;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BatchLinkResource extends Resource
{
    protected static ?string $model = BatchLink::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?string $navigationLabel = 'روابط الحلقات';
    protected static ?string $modelLabel = 'رابط الحلقة';
    protected static ?string $pluralModelLabel = 'روابط الحلقات';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('batch_id')
                ->relationship('batch', 'name')
                ->label('الدفعة')
                ->required()
                ->searchable()
                ->preload(),

            Forms\Components\TextInput::make('codec')
                ->label('الترميز / Codec')
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

            Forms\Components\TextInput::make('resolution')
                ->label('الدقة')
                ->required()
                ->placeholder('مثال: 1080p, 720p'),

            Forms\Components\Section::make('روابط التحميل')
                ->label('روابط التحميل')
                ->schema([
                    Forms\Components\TextInput::make('url_torrent')
                        ->label('رابط التورنت')
                        ->url()
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('url_rr_torrent')
                        ->label('رابط RR التورنت')
                        ->url()
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('url_mega')
                        ->label('رابط Mega')
                        ->url()
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('url_gdrive')
                        ->label('رابط Google Drive')
                        ->url()
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('url_megaHard')
                        ->label('رابط Mega (هاردسب)')
                        ->url()
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('url_gdriveHard')
                        ->label('رابط Google Drive (هاردسب)')
                        ->url()
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('batch.name')
                    ->label('الدفعة')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('codec')
                    ->label('الترميز')
                    ->colors([
                        'success' => 'x265',
                        'primary' => 'x264',
                    ]),

                Tables\Columns\TextColumn::make('resolution')
                    ->label('الدقة')
                    ->sortable(),

                Tables\Columns\IconColumn::make('url_torrent')
                    ->label('تورنت')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),

                Tables\Columns\IconColumn::make('url_mega')
                    ->label('Mega')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('codec')
                    ->label('الترميز')
                    ->options([
                        'x264' => 'x264 (AVC)',
                        'x265' => 'x265 (HEVC)',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('batch_id', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBatchLinks::route('/'),
            'create' => Pages\CreateBatchLink::route('/create'),
            'edit' => Pages\EditBatchLink::route('/{record}/edit'),
        ];
    }
}
