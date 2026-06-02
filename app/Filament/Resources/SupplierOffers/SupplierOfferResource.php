<?php

namespace App\Filament\Resources\SupplierOffers;

use App\Filament\Resources\SupplierOffers\Pages\CreateSupplierOffer;
use App\Filament\Resources\SupplierOffers\Pages\EditSupplierOffer;
use App\Filament\Resources\SupplierOffers\Pages\ListSupplierOffers;
use App\Filament\Resources\SupplierOffers\Schemas\SupplierOfferForm;
use App\Filament\Resources\SupplierOffers\Tables\SupplierOffersTable;
use App\Models\SupplierOffer;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SupplierOfferResource extends Resource
{
    protected static ?string $model = SupplierOffer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;
    protected static string|UnitEnum|null $navigationGroup = "Tenders";
    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return SupplierOfferForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SupplierOffersTable::configure($table);
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
            'index' => ListSupplierOffers::route('/'),
            'create' => CreateSupplierOffer::route('/create'),
            'edit' => EditSupplierOffer::route('/{record}/edit'),
        ];
    }
}
