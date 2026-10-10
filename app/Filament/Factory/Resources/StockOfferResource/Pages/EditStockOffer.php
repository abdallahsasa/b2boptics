<?php

namespace App\Filament\Factory\Resources\StockOfferResource\Pages;

use App\Filament\Factory\Resources\StockOfferResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\EditRecord\Concerns\Translatable;

class EditStockOffer extends EditRecord
{
    use Translatable;

    protected static string $resource = StockOfferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
            DeleteAction::make(),
        ];
    }
}
