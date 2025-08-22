<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommentResource\Pages;
use App\Models\Comment;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'التعليقات';
    protected static ?string $pluralLabel = 'جميع التعليقات';
    protected static ?string $modelLabel = 'تعليق';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Textarea::make('body')
                    ->label('نص التعليق')
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('اسم المستخدم')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('animeLink.title')
                    ->label('الأنمي')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('body')
                    ->label('التعليق')
                    ->limit(60),

                TextColumn::make('created_at')
                    ->label('تم الإنشاء')
                    ->since(),
            ])
            ->filters([
                SelectFilter::make('anime_link_id')
                    ->label('تصفية حسب الأنمي')
                    ->relationship('animeLink', 'title')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('عرض'),
                Tables\Actions\DeleteAction::make()
                    ->label('حذف'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->label('حذف المحدد'),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComments::route('/'),

            'edit' => Pages\EditComment::route('/{record}/edit'),
        ];
    }
}
