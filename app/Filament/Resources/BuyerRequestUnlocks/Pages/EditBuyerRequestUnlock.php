<?php

namespace App\Filament\Resources\BuyerRequestUnlocks\Pages;

use App\Filament\Resources\BuyerRequestUnlocks\BuyerRequestUnlockResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBuyerRequestUnlock extends EditRecord
{
    protected static string $resource = BuyerRequestUnlockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
