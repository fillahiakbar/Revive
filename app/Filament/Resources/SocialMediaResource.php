<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SocialMediaResource\Pages;
use App\Models\SocialMedia;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;

class SocialMediaResource extends Resource
{
    protected static ?string $model = SocialMedia::class;

    protected static ?string $navigationIcon = 'heroicon-o-share';

    protected static ?string $navigationLabel = 'وسائل التواصل الاجتماعي';
    protected static ?string $pluralModelLabel = 'وسائل التواصل الاجتماعي';
    protected static ?string $modelLabel = 'وسيلة تواصل اجتماعي';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('platform')
                    ->label('اسم المنصة')
                    ->required()
                    ->maxLength(50),

                Forms\Components\TextInput::make('url')
                    ->label('الرابط')
                    ->url()
                    ->required(),

                Forms\Components\Toggle::make('is_active')
                    ->label('نشطة؟')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('platform')
                    ->label('المنصة')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('url')
                    ->label('الرابط')
                    ->url(fn ($record) => $record->url)
                    ->openUrlInNewTab(),

                Tables\Columns\BooleanColumn::make('is_active')
                    ->label('نشطة'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإضافة')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('تعديل'),
                Tables\Actions\DeleteAction::make()->label('حذف'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->label('حذف متعدد'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSocialMedia::route('/'),
            'create' => Pages\CreateSocialMedia::route('/create'),
            'edit' => Pages\EditSocialMedia::route('/{record}/edit'),
        ];
    }
}
