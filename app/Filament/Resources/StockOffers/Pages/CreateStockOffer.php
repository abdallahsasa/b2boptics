<?php

namespace App\Filament\Resources\StockOffers\Pages;

use App\Filament\Resources\StockOffers\StockOfferResource;
use Filament\Resources\Pages\CreateRecord;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateStockOffer extends CreateRecord
{
    use Translatable;

    protected static string $resource = StockOfferResource::class;
}
