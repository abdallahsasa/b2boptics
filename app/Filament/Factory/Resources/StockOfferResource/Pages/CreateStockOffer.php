<?php

namespace App\Filament\Factory\Resources\StockOfferResource\Pages;

use App\Filament\Factory\Resources\StockOfferResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStockOffer extends CreateRecord
{
    protected static string $resource = StockOfferResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $factory = auth()->user()->factory;
        $data['factory_id'] = $factory->id;
        $data['status'] = 'pending';

        return $data;
    }
}
