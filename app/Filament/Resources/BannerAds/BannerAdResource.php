<?php

namespace App\Filament\Resources\BannerAds;

use App\Filament\Resources\BannerAds\Pages\CreateBannerAd;
use App\Filament\Resources\BannerAds\Pages\EditBannerAd;
use App\Filament\Resources\BannerAds\Pages\ListBannerAds;
use App\Filament\Resources\BannerAds\Schemas\BannerAdForm;
use App\Filament\Resources\BannerAds\Tables\BannerAdsTable;
use App\Models\BannerAd;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;
use UnitEnum;

class BannerAdResource extends Resource
{
    use Translatable;

    protected static ?string $model = BannerAd::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;
    protected static string|UnitEnum|null $navigationGroup = 'Marketing';
    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return BannerAdForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BannerAdsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBannerAds::route('/'),
            'create' => CreateBannerAd::route('/create'),
            'edit' => EditBannerAd::route('/{record}/edit'),
        ];
    }
}

