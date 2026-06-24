<?php

namespace App\Filament\Resources\StockOffers\Pages;

use App\Filament\Resources\StockOffers\StockOfferResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use LaraZeus\SpatieTranslatable\Resources\Pages\EditRecord\Concerns\Translatable;

class EditStockOffer extends EditRecord
{
    use Translatable;

    protected static string $resource = StockOfferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
