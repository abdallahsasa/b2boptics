<?php

namespace App\Filament\Resources\Factories\Pages;

use App\Filament\Resources\Factories\FactoryResource;
use Filament\Resources\Pages\CreateRecord;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateFactory extends CreateRecord
{
    use Translatable;

    protected static string $resource = FactoryResource::class;
}
