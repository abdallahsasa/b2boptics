<?php

namespace App\Filament\Resources\StockOfferRequests\Pages;

use App\Filament\Resources\StockOfferRequests\StockOfferRequestResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStockOfferRequest extends EditRecord
{
    protected static string $resource = StockOfferRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
