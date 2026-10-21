<?php

namespace App\Filament\Factory\Resources\StockOfferResource\Pages;

use App\Filament\Factory\Resources\StockOfferResource;
use Filament\Resources\Pages\CreateRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateStockOffer extends CreateRecord
{
    use Translatable;

    protected static string $resource = StockOfferResource::class;

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
        $data['status'] = $data['status'] ?? 'active';

        return $data;
    }
}
