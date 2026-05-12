<?php

namespace App\Filament\Resources\StockOffers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Schemas\Schema;

class StockOfferForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Stock Offer Information')
                    ->columns(2)
                    ->schema([
                        Select::make('factory_id')
                            ->relationship('factory', 'official_name')
                            ->required(),
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->required(),
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->rows(5)
                            ->columnSpanFull(),
                        TextInput::make('quantity')
                            ->required(),
                        TextInput::make('price')
                            ->numeric(),
                        Select::make('currency')
                            ->options(['USD' => 'USD', 'EUR' => 'EUR', 'TRY' => 'TRY'])
                            ->default('USD'),
                    ]),

                Section::make('Media & Status')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('image')
                            ->collection('stock_offer_images')
                            ->image(),
                        Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                                'sold_out' => 'Sold Out',
                            ])
                            ->default('active')
                            ->required(),
                    ]),
            ]);
    }
}
