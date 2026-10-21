<?php

namespace App\Filament\Factory\Resources;

use App\Filament\Factory\Resources\StockOfferResource\Pages;
use App\Models\StockOffer;
use BackedEnum;
use UnitEnum;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class StockOfferResource extends Resource
{
    use Translatable;

    protected static ?string $model = StockOffer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;
    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return __('My Catalog');
    }

    public static function getNavigationLabel(): string
    {
        return __('My Stock Deals');
    }

    public static function getModelLabel(): string
    {
        return __('Stock Deal');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Stock Deals');
    }

    /**
     * Scope queries to only show this factory's stock offers.
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
                Section::make(__('Deal Details'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label(__('Title'))
                            ->required()
                            ->maxLength(255),
                        Select::make('category_id')
                            ->label(__('Category'))
                            ->relationship('category', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                    ]),

                Section::make(__('Pricing & Availability'))
                    ->columns(3)
                    ->schema([
                        TextInput::make('price')
                            ->label(__('Price'))
                            ->numeric()
                            ->required()
                            ->prefix('$'),
                        Select::make('currency')
                            ->label(__('Currency'))
                            ->options(['USD' => 'USD', 'EUR' => 'EUR', 'TRY' => 'TRY'])
                            ->searchable()
                            ->default('USD')
                            ->required(),
                        Select::make('status')
                            ->label(__('Status'))
                            ->options([
                                'active' => __('Active'),
                                'sold_out' => __('Sold Out'),
                                'inactive' => __('Inactive'),
                                'pending' => __('Pending Review'),
                            ])
                            ->default('active')
                            ->required()
                            ->searchable(),
                    ]),

                Section::make(__('Duration'))
                    ->columns(2)
                    ->schema([
                        DateTimePicker::make('starts_at')
                            ->label(__('Starts At')),
                        DateTimePicker::make('ends_at')
                            ->label(__('Ends At')),
                    ]),

                Section::make(__('Media & Attachments'))
                    ->columns(2)
                    ->schema([
                        FileUpload::make('image')
                            ->label(__('Deal Image'))
                            ->image()
                            ->disk('public')
                            ->directory('stock-offers'),
                        FileUpload::make('pdf_file')
                            ->label(__('Brochure / Specification PDF'))
                            ->acceptedFileTypes(['application/pdf'])
                            ->disk('public')
                            ->directory('stock-offers/catalogs')
                            ->helperText(__('Upload lot manifest, technical spec sheet, or PDF brochure.')),
                        Textarea::make('description')
                            ->label(__('Deal Description'))
                            ->rows(5)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label(__('Category'))
                    ->sortable(),
                TextColumn::make('price')
                    ->label(__('Price'))
                    ->money(fn ($record) => $record->currency)
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'pending' => 'warning',
                        'expired' => 'gray',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('ends_at')
                    ->dateTime()
                    ->label(__('Expires'))
                    ->sortable(),
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
            'index' => Pages\ListStockOffers::route('/'),
            'create' => Pages\CreateStockOffer::route('/create'),
            'edit' => Pages\EditStockOffer::route('/{record}/edit'),
        ];
    }
}
