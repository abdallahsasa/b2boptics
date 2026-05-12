<?php

namespace App\Filament\Resources\BuyerRequestUnlocks;

use App\Filament\Resources\BuyerRequestUnlocks\Pages\CreateBuyerRequestUnlock;
use App\Filament\Resources\BuyerRequestUnlocks\Pages\EditBuyerRequestUnlock;
use App\Filament\Resources\BuyerRequestUnlocks\Pages\ListBuyerRequestUnlocks;
use App\Filament\Resources\BuyerRequestUnlocks\Schemas\BuyerRequestUnlockForm;
use App\Filament\Resources\BuyerRequestUnlocks\Tables\BuyerRequestUnlocksTable;
use App\Models\BuyerRequestUnlock;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BuyerRequestUnlockResource extends Resource
{
    protected static ?string $model = BuyerRequestUnlock::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLockOpen;
    protected static string|UnitEnum|null $navigationGroup = "Marketplace";
    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return BuyerRequestUnlockForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BuyerRequestUnlocksTable::configure($table);
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
            'index' => ListBuyerRequestUnlocks::route('/'),
            'create' => CreateBuyerRequestUnlock::route('/create'),
            'edit' => EditBuyerRequestUnlock::route('/{record}/edit'),
        ];
    }
}
