<?php

namespace App\Filament\Resources\BuyerRequests\Pages;

use App\Filament\Resources\BuyerRequests\BuyerRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBuyerRequests extends ListRecords
{
    protected static string $resource = BuyerRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
