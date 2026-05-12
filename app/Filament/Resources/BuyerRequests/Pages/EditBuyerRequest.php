<?php

namespace App\Filament\Resources\BuyerRequests\Pages;

use App\Filament\Resources\BuyerRequests\BuyerRequestResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBuyerRequest extends EditRecord
{
    protected static string $resource = BuyerRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
