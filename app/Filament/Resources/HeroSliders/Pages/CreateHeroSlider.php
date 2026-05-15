<?php

namespace App\Filament\Resources\HeroSliders\Pages;

use App\Filament\Resources\HeroSliders\HeroSliderResource;
use Filament\Resources\Pages\CreateRecord;

use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateHeroSlider extends CreateRecord
{
    use Translatable;
    protected static string $resource = HeroSliderResource::class;
}
