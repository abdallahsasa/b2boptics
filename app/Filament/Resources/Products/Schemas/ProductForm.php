<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Product Details')
                    ->columns(2)
                    ->schema([
                        Select::make('factory_id')
                            ->relationship('factory', 'official_name')
                            ->required()
                            ->searchable(),
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn (callable $set) => $set('subcategory_id', null)),
                        Select::make('subcategory_id')
                            ->relationship('subcategory', 'name', fn ($query, $get) => $query->where('category_id', $get('category_id')))
                            ->searchable(),
                    ]),

                Section::make('Pricing & Origin')
                    ->columns(2)
                    ->schema([
                        TextInput::make('starting_price')
                            ->numeric()
                            ->prefix('$'),
                        Select::make('currency')
                            ->options(['USD' => 'USD', 'EUR' => 'EUR', 'TRY' => 'TRY'])
                            ->default('USD'),
                        Select::make('country_of_origin_id')
                            ->relationship('countryOfOrigin', 'name')
                            ->searchable(),
                    ]),

                Section::make('Media & Description')
                    ->schema([
                        FileUpload::make('image')
                            ->image()
                            ->disk('public')
                            ->directory('products'),
                        Textarea::make('description')
                            ->rows(5)
                            ->columnSpanFull(),
                    ]),

                Section::make('Status & Visibility')
                    ->columns(2)
                    ->schema([
                        Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                                'inactive' => 'Inactive',
                            ])
                            ->default('draft')
                            ->required(),
                        Toggle::make('is_featured')
                            ->label('Featured Product'),
                        DateTimePicker::make('published_at'),
                    ]),
            ]);
    }
}
