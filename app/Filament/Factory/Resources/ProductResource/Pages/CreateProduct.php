<?php

namespace App\Filament\Factory\Resources\ProductResource\Pages;

use App\Filament\Factory\Resources\ProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $factory = auth()->user()->factory;
        $data['factory_id'] = $factory->id;
        $data['status'] = 'pending'; // Always pending, admin approves

        return $data;
    }
}
