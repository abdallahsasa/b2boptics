<?php

namespace App\Filament\Resources\StockOffers\Pages;

use App\Filament\Resources\StockOffers\StockOfferResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStockOffer extends EditRecord
{
    protected static string $resource = StockOfferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
