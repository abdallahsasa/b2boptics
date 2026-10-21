<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $operation, $state, Set $set, Get $get) {
                        if ($operation === 'create' || empty($get('slug'))) {
                            $nameStr = is_array($state) ? ($state['en'] ?? reset($state)) : $state;
                            $set('slug', Str::slug($nameStr));
                        }
                    }),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->helperText('Auto-generated from name. You can also customize it.'),
                Select::make('type')
                    ->options([
                        'product' => 'Product',
                        'stock_offer' => 'Stock Offer',
                        'request' => 'Buyer Request',
                    ])
                    ->searchable()
                    ->default('product')
                    ->required(),
                Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ])
                    ->searchable()
                    ->default('active'),
                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
            ]);
    }
}
