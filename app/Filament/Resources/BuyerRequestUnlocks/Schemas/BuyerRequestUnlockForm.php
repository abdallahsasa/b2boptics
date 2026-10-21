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
                            ->searchable()
                            ->preload(),
                        Select::make('factory_id')
                            ->relationship('factory', 'official_name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Select::make('unlocked_by')
                            ->relationship('unlockedBy', 'name')
                            ->searchable()
                            ->preload(),
                        Select::make('unlock_method')
                            ->options([
                                'manual' => 'Manual',
                                'credit' => 'Credit',
                                'payment' => 'Payment',
                            ])
                            ->searchable()
                            ->default('manual')
                            ->required(),
                        TextInput::make('amount')
                            ->numeric(),
                        Select::make('currency')
                            ->options(['USD' => 'USD', 'EUR' => 'EUR', 'TRY' => 'TRY'])
                            ->searchable()
                            ->default('USD'),
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'unlocked' => 'Unlocked',
                                'refunded' => 'Refunded',
                            ])
                            ->searchable()
                            ->default('unlocked')
                            ->required(),
                    ]),
            ]);
    }
}
