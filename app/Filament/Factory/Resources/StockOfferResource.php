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

class StockOfferResource extends Resource
{
    protected static ?string $model = StockOffer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;
    protected static string|UnitEnum|null $navigationGroup = "My Catalog";
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'My Stock Deals';
    protected static ?string $modelLabel = 'Stock Deal';
    protected static ?string $pluralModelLabel = 'Stock Deals';

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
                Section::make('Deal Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->required()
                            ->searchable(),
                    ]),

                Section::make('Pricing')
                    ->columns(2)
                    ->schema([
                        TextInput::make('price')
                            ->numeric()
                            ->required()
                            ->prefix('$'),
                        Select::make('currency')
                            ->options(['USD' => 'USD', 'EUR' => 'EUR', 'TRY' => 'TRY'])
                            ->default('USD')
                            ->required(),
                    ]),

                Section::make('Duration')
                    ->columns(2)
                    ->schema([
                        DateTimePicker::make('starts_at')
                            ->label('Starts At'),
                        DateTimePicker::make('ends_at')
                            ->label('Ends At'),
                    ]),

                Section::make('Media & Description')
                    ->schema([
                        FileUpload::make('image')
                            ->image()
                            ->disk('public')
                            ->directory('stock-offers'),
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
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->sortable(),
                TextColumn::make('price')
                    ->money(fn ($record) => $record->currency)
                    ->sortable(),
                TextColumn::make('status')
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
                    ->label('Expires')
                    ->sortable(),
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
            'index' => Pages\ListStockOffers::route('/'),
            'create' => Pages\CreateStockOffer::route('/create'),
            'edit' => Pages\EditStockOffer::route('/{record}/edit'),
        ];
    }
}
