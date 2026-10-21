<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

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
                            ->searchable()
                            ->preload(),
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
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->required()
                            ->reactive()
                            ->searchable()
                            ->preload()
                            ->afterStateUpdated(fn (callable $set) => $set('subcategory_id', null)),
                        Select::make('subcategory_id')
                            ->relationship('subcategory', 'name', fn ($query, $get) => $query->where('category_id', $get('category_id')))
                            ->searchable()
                            ->preload(),
                    ]),

                Section::make('Pricing & Origin')
                    ->columns(2)
                    ->schema([
                        TextInput::make('starting_price')
                            ->numeric()
                            ->prefix('$'),
                        Select::make('currency')
                            ->options(['USD' => 'USD', 'EUR' => 'EUR', 'TRY' => 'TRY'])
                            ->searchable()
                            ->default('USD'),
                        Select::make('country_of_origin_id')
                            ->relationship('countryOfOrigin', 'name')
                            ->searchable()
                            ->preload(),
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

                Section::make('Basic Info & Specifications')
                    ->description('Technical specifications displayed in the Basic Info table on the product page')
                    ->schema([
                        KeyValue::make('specifications')
                            ->label('Basic Info (Specifications)')
                            ->keyLabel('Attribute / Property (e.g. Transmittance, Material, Shape)')
                            ->valueLabel('Value (e.g. >95%, Sr55, Ball, Hemisphere)')
                            ->reorderable()
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
