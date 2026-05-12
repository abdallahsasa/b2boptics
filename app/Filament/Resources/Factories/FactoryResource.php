<?php

namespace App\Filament\Resources\Factories;

use App\Filament\Resources\Factories\Pages\CreateFactory;
use App\Filament\Resources\Factories\Pages\EditFactory;
use App\Filament\Resources\Factories\Pages\ListFactories;
use App\Filament\Resources\Factories\Schemas\FactoryForm;
use App\Filament\Resources\Factories\Tables\FactoriesTable;
use App\Models\Factory;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class FactoryResource extends Resource
{
    use Translatable;

    protected static ?string $model = Factory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;
    protected static string|UnitEnum|null $navigationGroup = "Users & Factories";
    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'official_name';

    public static function form(Schema $schema): Schema
    {
        return FactoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FactoriesTable::configure($table);
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
            'index' => ListFactories::route('/'),
            'create' => CreateFactory::route('/create'),
            'edit' => EditFactory::route('/{record}/edit'),
        ];
    }
}
