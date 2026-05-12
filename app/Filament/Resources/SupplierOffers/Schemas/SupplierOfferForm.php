<?php

namespace App\Filament\Resources\SupplierOffers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SupplierOfferForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Offer Details')
                    ->columns(2)
                    ->schema([
                        Select::make('buyer_request_id')
                            ->relationship('buyerRequest', 'title')
                            ->required()
                            ->searchable(),
                        Select::make('factory_id')
                            ->relationship('factory', 'official_name')
                            ->required()
                            ->searchable(),
                        TextInput::make('offered_price')
                            ->numeric()
                            ->label('Price Offered'),
                        Select::make('currency')
                            ->options(['USD' => 'USD', 'EUR' => 'EUR', 'TRY' => 'TRY'])
                            ->default('USD'),
                        TextInput::make('delivery_time')
                            ->maxLength(255),
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'sent' => 'Sent',
                                'accepted' => 'Accepted',
                                'rejected' => 'Rejected',
                            ])
                            ->default('pending')
                            ->required(),
                        Textarea::make('message')
                            ->required()
                            ->rows(5)
                            ->columnSpanFull(),
                    ]),

                Section::make('Visibility')
                    ->columns(2)
                    ->schema([
                        Toggle::make('admin_visible')
                            ->label('Visible to Admin')
                            ->default(true),
                        Toggle::make('buyer_visible')
                            ->label('Visible to Buyer')
                            ->default(false),
                    ]),
            ]);
    }
}
