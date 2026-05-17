<?php

namespace App\Filament\Resources\StockOfferRequests\Pages;

use App\Filament\Resources\StockOfferRequests\StockOfferRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStockOfferRequests extends ListRecords
{
    protected static string $resource = StockOfferRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
