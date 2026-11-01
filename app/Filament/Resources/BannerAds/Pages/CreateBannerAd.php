<?php

namespace App\Filament\Resources\BannerAds\Pages;

use App\Filament\Resources\BannerAds\BannerAdResource;
use Filament\Resources\Pages\CreateRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateBannerAd extends CreateRecord
{
    use Translatable;

    protected static string $resource = BannerAdResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }
}

