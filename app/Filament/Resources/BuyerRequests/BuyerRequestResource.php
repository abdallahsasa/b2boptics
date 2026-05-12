<?php

namespace App\Filament\Resources\BuyerRequests;

use App\Filament\Resources\BuyerRequests\Pages\CreateBuyerRequest;
use App\Filament\Resources\BuyerRequests\Pages\EditBuyerRequest;
use App\Filament\Resources\BuyerRequests\Pages\ListBuyerRequests;
use App\Filament\Resources\BuyerRequests\Schemas\BuyerRequestForm;
use App\Filament\Resources\BuyerRequests\Tables\BuyerRequestsTable;
use App\Models\BuyerRequest;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BuyerRequestResource extends Resource
{
    protected static ?string $model = BuyerRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInboxArrowDown;
    protected static string|UnitEnum|null $navigationGroup = "Marketplace";
    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return BuyerRequestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BuyerRequestsTable::configure($table);
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
            'index' => ListBuyerRequests::route('/'),
            'create' => CreateBuyerRequest::route('/create'),
            'edit' => EditBuyerRequest::route('/{record}/edit'),
        ];
    }
}
