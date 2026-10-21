<?php

namespace App\Filament\Factory\Resources\ProductResource\Pages;

use App\Filament\Factory\Resources\ProductResource;
use Filament\Resources\Pages\CreateRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateProduct extends CreateRecord
{
    use Translatable;

    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $factory = auth()->user()->factory;
        $data['factory_id'] = $factory->id;
        $data['status'] = in_array($data['status'] ?? '', ['draft', 'pending']) ? $data['status'] : 'pending';

        return $data;
    }
}
