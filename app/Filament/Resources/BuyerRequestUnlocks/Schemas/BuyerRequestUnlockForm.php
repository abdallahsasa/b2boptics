<?php

namespace App\Filament\Resources\BuyerRequestUnlocks\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BuyerRequestUnlockForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Unlock Information')
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
                        Select::make('unlocked_by')
                            ->relationship('unlockedBy', 'name')
                            ->searchable(),
                        Select::make('unlock_method')
                            ->options([
                                'manual' => 'Manual',
                                'credit' => 'Credit',
                                'payment' => 'Payment',
                            ])
                            ->default('manual')
                            ->required(),
                        TextInput::make('amount')
                            ->numeric(),
                        Select::make('currency')
                            ->options(['USD' => 'USD', 'EUR' => 'EUR', 'TRY' => 'TRY'])
                            ->default('USD'),
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'unlocked' => 'Unlocked',
                                'refunded' => 'Refunded',
                            ])
                            ->default('unlocked')
                            ->required(),
                    ]),
            ]);
    }
}
