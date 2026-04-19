<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmailBlacklistResource\Pages;
use App\Models\EmailBlacklist;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EmailBlacklistResource extends Resource
{
    protected static ?string $model = EmailBlacklist::class;

    protected static ?string $navigationIcon = 'heroicon-o-no-symbol';
    protected static ?string $navigationGroup = 'User Management';
    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'القائمة السوداء للبريد';
    protected static ?string $pluralModelLabel = 'القوائم السوداء';
    protected static ?string $modelLabel = 'قائمة سوداء';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('email')
                            ->label('البريد الإلكتروني')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('أدخل البريد الإلكتروني الذي تريد حظره'),


                        Forms\Components\Toggle::make('is_active')
                            ->label('نشط')
                            ->default(true)
                            ->helperText('إذا كان غير نشط، لن يتم حظر هذا البريد'),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('email')
                    ->label('البريد الإلكتروني')
                    ->searchable()
                    ->sortable()
                    ->copyable(),


                Tables\Columns\IconColumn::make('is_active')
                    ->label('الحالة')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->tooltip(fn ($state): string => $state ? 'نشط' : 'غير نشط'),

                Tables\Columns\TextColumn::make('admin.name')
                    ->label('تم الحظر بواسطة')
                    ->placeholder('نظام')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإضافة')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('الحالة')
                    ->placeholder('الكل')
                    ->trueLabel('نشط')
                    ->falseLabel('غير نشط'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('تعديل'),

                Tables\Actions\DeleteAction::make()
                    ->label('حذف'),

                Tables\Actions\Action::make('toggleActive')
                    ->label(fn ($record) => $record->is_active ? 'تعطيل' : 'تفعيل')
                    ->color(fn ($record) => $record->is_active ? 'warning' : 'success')
                    ->icon(fn ($record) => $record->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->action(function ($record) {
                        $record->update(['is_active' => !$record->is_active]);
                    })
                    ->requiresConfirmation(false),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->label('حذف المحدد'),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('لا توجد رسائل محظورة')
            ->emptyStateDescription('أضف بريد إلكتروني إلى القائمة السوداء للبدء')
            ->emptyStateIcon('heroicon-o-no-symbol');
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
            'index' => Pages\ListEmailBlacklists::route('/'),
            'create' => Pages\CreateEmailBlacklist::route('/create'),
            'edit' => Pages\EditEmailBlacklist::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['email'];
    }
}
