<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentMethodResource\Pages;
use App\Filament\Resources\PaymentMethodResource\RelationManagers;
use App\Models\PaymentMethod;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentMethodResource extends Resource
{
    protected static ?string $model = PaymentMethod::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('General Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('type')
                            ->options([
                                'paypal' => 'PayPal',
                                'crypto' => 'Crypto',
                                'stc_pay' => 'STC Pay',
                                'link' => 'Other Link',
                            ])
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, Forms\Set $set) => match ($state) {
                                'paypal' => $set('content_label', 'PayPal Link'),
                                'stc_pay' => $set('content_label', 'Phone Number'),
                                default => $set('content_label', 'Content'),
                            }),
                        Forms\Components\TextInput::make('content')
                            ->label(fn (Forms\Get $get) => match ($get('type')) {
                                'paypal' => 'PayPal Link',
                                'stc_pay' => 'Phone Number',
                                'link' => 'Url',
                                default => 'Content (General)',
                            })
                            ->required(fn (Forms\Get $get) => $get('type') !== 'crypto')
                            ->visible(fn (Forms\Get $get) => $get('type') !== 'crypto')
                            ->maxLength(255),
                        Forms\Components\Toggle::make('is_active')
                            ->required()
                            ->default(true),
                    ])->columns(2),

                Forms\Components\Section::make('Instructions & Images')
                    ->schema([
                        Forms\Components\RichEditor::make('instruction')
                            ->label('Instructions')
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('icon')
                            ->label('Method Icon (Main)')
                            ->image()
                            ->directory('payment-methods')
                            ->columnSpan(1),
                        Forms\Components\FileUpload::make('qr_code')
                            ->label('Main QR Code (Optional)')
                            ->image()
                            ->directory('payment-methods')
                            ->visible(fn (Forms\Get $get) => in_array($get('type'), ['stc_pay', 'link']))
                            ->columnSpan(1),
                    ])->columns(2),

                Forms\Components\Section::make('Crypto Options')
                    ->heading('Crypto Currencies & Networks')
                    ->visible(fn (Forms\Get $get) => $get('type') === 'crypto')
                    ->schema([
                        Forms\Components\Repeater::make('options.coins')
                            ->label('Coins')
                            ->schema([
                                Forms\Components\TextInput::make('coin_name')
                                    ->label('Coin Symbol (e.g. USDT)')
                                    ->required(),
                                Forms\Components\Repeater::make('networks')
                                    ->label('Networks')
                                    ->schema([
                                        Forms\Components\TextInput::make('network_name')
                                            ->label('Network (e.g. TRC20)')
                                            ->required(),
                                        Forms\Components\TextInput::make('wallet_address')
                                            ->label('Wallet Address')
                                            ->required(),
                                        Forms\Components\FileUpload::make('qr_image')
                                            ->label('QR Image')
                                            ->image()
                                            ->directory('payment-methods/crypto'),
                                    ])
                                    ->columns(3),
                            ])
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['coin_name'] ?? null),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('icon'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'paypal' => 'PayPal',
                        'crypto' => 'Crypto',
                        'stc_pay' => 'STC Pay',
                        'link' => 'Link',
                        'manual' => 'Manual',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'link' => 'info',
                        'manual' => 'warning',
                        'paypal' => 'primary',
                        'crypto' => 'success',
                        'stc_pay' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('content')
                    ->limit(30),
                Tables\Columns\ToggleColumn::make('is_active'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListPaymentMethods::route('/'),
            'create' => Pages\CreatePaymentMethod::route('/create'),
            'edit' => Pages\EditPaymentMethod::route('/{record}/edit'),
        ];
    }
}
