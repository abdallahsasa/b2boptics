<?php

namespace App\Filament\Factory\Resources\ProductResource\Pages;

use App\Filament\Factory\Resources\ProductResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\EditRecord\Concerns\Translatable;

class EditProduct extends EditRecord
{
    use Translatable;

    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (empty($data['images']) && !empty($data['image'])) {
            $data['images'] = [$data['image']];
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $maxImages = auth()->user()?->factory?->maxImagesPerProduct() ?? 10;

        if (isset($data['images']) && is_array($data['images'])) {
            $data['images'] = array_slice(array_values(array_filter($data['images'])), 0, $maxImages);
            $data['image'] = $data['images'][0] ?? null;
        }

        return $data;
    }
}
