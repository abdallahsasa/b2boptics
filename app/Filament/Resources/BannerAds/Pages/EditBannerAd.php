<?php

namespace App\Filament\Resources\BannerAds\Pages;

use App\Filament\Resources\BannerAds\BannerAdResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\EditRecord\Concerns\Translatable;

class EditBannerAd extends EditRecord
{
    use Translatable;

    protected static string $resource = BannerAdResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
            DeleteAction::make(),
        ];
    }
}

