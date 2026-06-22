<?php

namespace App\Filament\Factory\Resources\ProductResource\Pages;

use App\Filament\Factory\Resources\ProductResource;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;
}
