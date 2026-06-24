<?php

namespace App\Filament\Resources\Factories\Pages;

use App\Filament\Resources\Factories\FactoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use LaraZeus\SpatieTranslatable\Resources\Pages\EditRecord\Concerns\Translatable;

class EditFactory extends EditRecord
{
    use Translatable;

    protected static string $resource = FactoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
