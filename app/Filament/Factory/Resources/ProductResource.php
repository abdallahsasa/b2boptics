<?php

namespace App\Filament\Factory\Resources;

use App\Filament\Factory\Resources\ProductResource\Pages;
use App\Models\Product;
use BackedEnum;
use UnitEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
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

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;
    protected static string|UnitEnum|null $navigationGroup = "My Catalog";
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'My Products';

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
                Section::make('Product Details')
                    ->columns(2)
                    ->schema([
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
                            ->directory('products'),
                        Textarea::make('description')
                            ->rows(5)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->circular(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->sortable(),
                TextColumn::make('starting_price')
                    ->money(fn ($record) => $record->currency)
                    ->sortable(),
                TextColumn::make('status')
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
