<?php

namespace App\Filament\Resources\BuyerRequests\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BuyerRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Buyer Information')
                    ->columns(2)
                    ->schema([
                        Select::make('buyer_id')
                            ->relationship('buyer', 'name')
                            ->required(),
                        TextInput::make('contact_name')
                            ->maxLength(255),
                        TextInput::make('contact_email')
                            ->email()
                            ->maxLength(255),
                        TextInput::make('contact_phone')
                            ->tel()
                            ->maxLength(255),
                    ]),

                Section::make('Request Details')
                    ->columns(2)
                    ->schema([
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->required(),
                        Select::make('subcategory_id')
                            ->relationship('subcategory', 'name'),
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->rows(5)
                            ->columnSpanFull(),
                    ]),

                Section::make('Quantities & Pricing')
                    ->columns(2)
                    ->schema([
                        TextInput::make('quantity')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('target_price')
                            ->numeric(),
                        Select::make('currency')
                            ->options(['USD' => 'USD', 'EUR' => 'EUR', 'TRY' => 'TRY'])
                            ->default('USD'),
                        Select::make('country_id')
                            ->relationship('country', 'name'),
                    ]),

                Section::make('Status')
                    ->schema([
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                                'expired' => 'Expired',
                            ])
                            ->default('pending')
                            ->required(),
                        Textarea::make('admin_notes'),
                    ]),
            ]);
    }
}
