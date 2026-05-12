<?php

namespace App\Filament\Resources\FactoryBankDetails\Pages;

use App\Filament\Resources\FactoryBankDetails\FactoryBankDetailResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFactoryBankDetail extends EditRecord
{
    protected static string $resource = FactoryBankDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
