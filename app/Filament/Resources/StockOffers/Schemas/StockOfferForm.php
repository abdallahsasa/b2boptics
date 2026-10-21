<?php

namespace App\Filament\Resources\StockOffers\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
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
                            ->required()
                            ->searchable()
                            ->preload(),
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->rows(5)
                            ->columnSpanFull(),
                        TextInput::make('price')
                            ->numeric()
                            ->prefix('$'),
                        Select::make('currency')
                            ->options(['USD' => 'USD', 'EUR' => 'EUR', 'TRY' => 'TRY'])
                            ->searchable()
                            ->default('USD'),
                    ]),

                Section::make('Validity & Duration')
                    ->columns(2)
                    ->schema([
                        DateTimePicker::make('starts_at')
                            ->label('Starts At'),
                        DateTimePicker::make('ends_at')
                            ->label('Ends At'),
                    ]),

                Section::make('Media & Status')
                    ->columns(2)
                    ->schema([
                        FileUpload::make('image')
                            ->disk('public')
                            ->directory('stock-offers')
                            ->image(),
                        FileUpload::make('pdf_file')
                            ->label('Brochure / Specification PDF')
                            ->acceptedFileTypes(['application/pdf'])
                            ->disk('public')
                            ->directory('stock-offers/catalogs')
                            ->helperText('Upload lot manifest, spec sheet, or PDF brochure.'),
                        Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'sold_out' => 'Sold Out',
                                'inactive' => 'Inactive',
                                'pending' => 'Pending Review',
                            ])
                            ->default('active')
                            ->required()
                            ->searchable(),
                    ]),
            ]);
    }
}
