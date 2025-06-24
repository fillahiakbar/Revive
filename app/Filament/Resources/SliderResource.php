<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SliderResource\Pages;
use App\Models\Slider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;

class SliderResource extends Resource
{
    protected static ?string $model = Slider::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationGroup = 'Konten Website';
    protected static ?string $navigationLabel = 'Slider Beranda';
    protected static ?string $pluralLabel = 'Slider';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('image')
                    ->label('Gambar')
                    ->directory('sliders')
                    ->image()
                    ->required(),

                TextInput::make('title')
                    ->label('Judul')
                    ->maxLength(255),

                Textarea::make('description')
                    ->label('Deskripsi'),

                TextInput::make('type')
                    ->label('Tipe')
                    ->default('TV'),

                TextInput::make('duration')
                    ->label('Durasi')
                    ->default('24m'),

                TextInput::make('year')
                    ->label('Tahun')
                    ->default(date('Y')),

                TextInput::make('quality')
                    ->label('Kualitas')
                    ->default('HD'),

                TextInput::make('episodes')
                    ->label('Episode')
                    ->default('12 Eps'),

                TextInput::make('order')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0),

                Toggle::make('is_active')
                    ->label('Tampilkan?')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Gambar')
                    ->square()
                    ->height(60),

                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->limit(30),

                TextColumn::make('type')
                    ->label('Tipe'),

                TextColumn::make('year')
                    ->label('Tahun'),

                TextColumn::make('episodes')
                    ->label('Episode'),

                TextColumn::make('order')
                    ->label('Urutan')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->defaultSort('order')
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSliders::route('/'),
            'create' => Pages\CreateSlider::route('/create'),
            'edit' => Pages\EditSlider::route('/{record}/edit'),
        ];
    }
}
