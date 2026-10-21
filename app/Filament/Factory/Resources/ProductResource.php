<?php

namespace App\Filament\Factory\Resources;

use App\Filament\Factory\Resources\ProductResource\Pages;
use App\Models\Product;
use BackedEnum;
use UnitEnum;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Section;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class ProductResource extends Resource
{
    use Translatable;

    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;
    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return __('My Catalog');
    }

    public static function getNavigationLabel(): string
    {
        return __('My Products');
    }

    public static function getModelLabel(): string
    {
        return __('Product');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Products');
    }

    protected static ?string $recordTitleAttribute = 'name';

    /**
     * Scope all queries to only show products belonging to the logged-in factory owner.
     */
    public static function getEloquentQuery(): Builder
    {
        $factory = auth()->user()->factory;

        return parent::getEloquentQuery()
            ->where('factory_id', $factory?->id ?? 0);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Product Details'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Product Name'))
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
                            ->label(__('Slug'))
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->helperText(__('Auto-generated from name. You can also customize it.')),
                        Select::make('category_id')
                            ->label(__('Category'))
                            ->relationship('category', 'name')
                            ->required()
                            ->reactive()
                            ->searchable()
                            ->preload()
                            ->afterStateUpdated(fn (callable $set) => $set('subcategory_id', null)),
                        Select::make('subcategory_id')
                            ->label(__('Subcategory'))
                            ->relationship('subcategory', 'name', fn ($query, $get) => $query->where('category_id', $get('category_id')))
                            ->searchable()
                            ->preload(),
                    ]),

                Section::make(__('Pricing & Origin'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('starting_price')
                            ->label(__('Starting Price'))
                            ->numeric()
                            ->prefix('$'),
                        Select::make('currency')
                            ->label(__('Currency'))
                            ->options(['USD' => 'USD', 'EUR' => 'EUR', 'TRY' => 'TRY'])
                            ->searchable()
                            ->default('USD'),
                        Select::make('country_of_origin_id')
                            ->label(__('Country of Origin'))
                            ->relationship('countryOfOrigin', 'name')
                            ->searchable()
                            ->preload(),
                    ]),

                Section::make(__('Media & Description'))
                    ->schema([
                        FileUpload::make('image')
                            ->label(__('Product Image'))
                            ->image()
                            ->disk('public')
                            ->directory('products'),
                        Textarea::make('description')
                            ->label(__('Product Description'))
                            ->rows(5)
                            ->columnSpanFull(),
                    ]),

                Section::make(__('Basic Info & Specifications'))
                    ->description(__('Technical specifications displayed in the Basic Info table on the product page'))
                    ->schema([
                        KeyValue::make('specifications')
                            ->label(__('Basic Info (Specifications)'))
                            ->keyLabel(__('Attribute / Property (e.g. Transmittance, Material, Shape)'))
                            ->valueLabel(__('Value (e.g. >95%, Sr55, Ball, Hemisphere)'))
                            ->reorderable()
                            ->columnSpanFull(),
                    ]),

                Section::make(__('Status & Visibility'))
                    ->columns(2)
                    ->schema([
                        Select::make('status')
                            ->label(__('Status'))
                            ->options([
                                'pending' => __('Submit for Review (Pending)'),
                                'draft' => __('Draft (Private)'),
                            ])
                            ->default('pending')
                            ->disabled(fn ($record) => $record && in_array($record->status, ['approved', 'rejected']))
                            ->helperText(fn ($record) => $record && $record->status === 'approved' 
                                ? __('This product is approved and visible in the marketplace.') 
                                : __('New products require admin approval before appearing publicly.')),
                        DateTimePicker::make('published_at')
                            ->label(__('Available / Published Date')),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label(__('Image'))
                    ->disk('public')
                    ->circular(),
                TextColumn::make('name')
                    ->label(__('Product Name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label(__('Category'))
                    ->sortable(),
                TextColumn::make('starting_price')
                    ->label(__('Starting Price'))
                    ->money(fn ($record) => $record->currency)
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'pending' => 'warning',
                        'draft' => 'gray',
                        'rejected' => 'danger',
                        'inactive' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label(__('Date Created'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
