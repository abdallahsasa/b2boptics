<?php

namespace App\Filament\Resources\FactoryBankDetails\Pages;

use App\Filament\Resources\FactoryBankDetails\FactoryBankDetailResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFactoryBankDetails extends ListRecords
{
    protected static string $resource = FactoryBankDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
