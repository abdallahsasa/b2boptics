<?php

namespace App\Filament\Resources\BuyerRequestUnlocks\Pages;

use App\Filament\Resources\BuyerRequestUnlocks\BuyerRequestUnlockResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBuyerRequestUnlocks extends ListRecords
{
    protected static string $resource = BuyerRequestUnlockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
