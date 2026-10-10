<?php

namespace App\Filament\Factory\Resources\StockOfferResource\Pages;

use App\Filament\Factory\Resources\StockOfferResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\ListRecords\Concerns\Translatable;

class ListStockOffers extends ListRecords
{
    use Translatable;

    protected static string $resource = StockOfferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
            CreateAction::make(),
        ];
    }
}
