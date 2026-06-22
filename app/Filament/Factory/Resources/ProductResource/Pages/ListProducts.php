<?php

namespace App\Filament\Factory\Resources\ProductResource\Pages;

use App\Filament\Factory\Resources\ProductResource;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;
}
