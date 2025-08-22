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
    protected static ?string $navigationGroup = 'محتوى الموقع';
    protected static ?string $navigationLabel = 'الشرائح الرئيسية';
    protected static ?string $pluralLabel = 'الشرائح';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('image')
                    ->label('الصورة')
                    ->directory('sliders')
                    ->image()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg'])
                    ->required(),
                    
                TextInput::make('choice')
                    ->label('مُختارات'),

                TextInput::make('title')
                    ->label('العنوان')
                    ->maxLength(255),
                
                TextInput::make('mal_id')
                    ->label('MyAnimeList ID')
                    ->numeric()
                    ->required()
                    ->helperText('أدخل معرف الأنمي من MyAnimeList'),

                Textarea::make('description')
                    ->label('الوصف'),

                TextInput::make('type')
                    ->label('النوع')
                    ->default('مسلسل'),

                TextInput::make('duration')
                    ->label('المدة')
                    ->default('24 دقيقة'),

                TextInput::make('year')
                    ->label('السنة')
                    ->default(date('Y')),

                TextInput::make('quality')
                    ->label('الجودة')
                    ->default('HD'),

                TextInput::make('episodes')
                    ->label('عدد الحلقات')
                    ->default('12 حلقة'),

                TextInput::make('order')
                    ->label('الترتيب')
                    ->numeric()
                    ->default(0),

                Toggle::make('is_active')
                    ->label('هل تريد عرضه؟')
                    ->default(true),

                TextInput::make('duration_ms')
                    ->label('مدة التبديل (بالميلي ثانية)')
                    ->numeric()
                    ->default(5000)
                    ->required()
                    ->minValue(1000)
                    ->maxValue(30000)
                    ->helperText('المدة بين التبديل التلقائي للشرائح بالميلي ثانية (1000 = 1 ثانية)'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('الصورة')
                    ->square()
                    ->height(60),

                TextColumn::make('title')
                    ->label('العنوان')
                    ->searchable()
                    ->limit(30),

                TextColumn::make('type')
                    ->label('النوع'),

                TextColumn::make('year')
                    ->label('السنة'),

                TextColumn::make('episodes')
                    ->label('عدد الحلقات'),

                TextColumn::make('order')
                    ->label('الترتيب')
                    ->sortable(),

                TextColumn::make('duration_ms')
                    ->label('المدة (ms)')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('نشط')
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
