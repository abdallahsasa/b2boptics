<?php

namespace App\Filament\Resources\StockOffers\Pages;

use App\Filament\Resources\StockOffers\StockOfferResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStockOffers extends ListRecords
{
    protected static string $resource = StockOfferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
