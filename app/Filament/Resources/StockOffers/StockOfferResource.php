<?php

namespace App\Filament\Resources\StockOffers;

use App\Filament\Resources\StockOffers\Pages\CreateStockOffer;
use App\Filament\Resources\StockOffers\Pages\EditStockOffer;
use App\Filament\Resources\StockOffers\Pages\ListStockOffers;
use App\Filament\Resources\StockOffers\Schemas\StockOfferForm;
use App\Filament\Resources\StockOffers\Tables\StockOffersTable;
use App\Models\StockOffer;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class StockOfferResource extends Resource
{
    use Translatable;

    protected static ?string $model = StockOffer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;
    protected static string|UnitEnum|null $navigationGroup = "Marketplace";
    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return StockOfferForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StockOffersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStockOffers::route('/'),
            'create' => CreateStockOffer::route('/create'),
            'edit' => EditStockOffer::route('/{record}/edit'),
        ];
    }
}
