<?php

namespace App\Filament\Resources\FactoryBankDetails;

use App\Filament\Resources\FactoryBankDetails\Pages\CreateFactoryBankDetail;
use App\Filament\Resources\FactoryBankDetails\Pages\EditFactoryBankDetail;
use App\Filament\Resources\FactoryBankDetails\Pages\ListFactoryBankDetails;
use App\Filament\Resources\FactoryBankDetails\Schemas\FactoryBankDetailForm;
use App\Filament\Resources\FactoryBankDetails\Tables\FactoryBankDetailsTable;
use App\Models\FactoryBankDetail;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FactoryBankDetailResource extends Resource
{
    protected static ?string $model = FactoryBankDetail::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;
    protected static string|UnitEnum|null $navigationGroup = "Users & Factories";
    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'bank_name';

    public static function form(Schema $schema): Schema
    {
        return FactoryBankDetailForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FactoryBankDetailsTable::configure($table);
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
            'index' => ListFactoryBankDetails::route('/'),
            'create' => CreateFactoryBankDetail::route('/create'),
            'edit' => EditFactoryBankDetail::route('/{record}/edit'),
        ];
    }
}
