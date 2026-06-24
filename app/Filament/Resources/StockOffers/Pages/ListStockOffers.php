<?php

namespace App\Filament\Resources\StockOffers\Pages;

use App\Filament\Resources\StockOffers\StockOfferResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use LaraZeus\SpatieTranslatable\Resources\Pages\ListRecords\Concerns\Translatable;

class ListStockOffers extends ListRecords
{
    use Translatable;

    protected static string $resource = StockOfferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
