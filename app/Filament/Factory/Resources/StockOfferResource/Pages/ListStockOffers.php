<?php

namespace App\Filament\Factory\Resources\StockOfferResource\Pages;

use App\Filament\Factory\Resources\StockOfferResource;
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
