<?php

namespace App\Filament\Factory\Resources\ProductResource\Pages;

use App\Filament\Factory\Resources\ProductResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateProduct extends CreateRecord
{
    use Translatable;

    protected static string $resource = ProductResource::class;

    public function mount(): void
    {
        $factory = auth()->user()?->factory;

        if (!$factory) {
            Notification::make()
                ->title(__('Please complete your Factory Profile first.'))
                ->warning()
                ->send();

            $this->redirect(url('/factory/my-factory'));
            return;
        }

        if (!$factory->canCreateMoreProducts()) {
            Notification::make()
                ->title(__('Product Listing Limit Reached'))
                ->body(__('Your :plan includes :limit free product listings (up to :images images each). You have used all :limit slots.', [
                    'plan' => $factory->subscriptionPlanLabel(),
                    'limit' => $factory->productLimit(),
                    'images' => $factory->maxImagesPerProduct(),
                ]))
                ->danger()
                ->send();

            $this->redirect(ProductResource::getUrl('index'));
            return;
        }

        parent::mount();
    }

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }

    protected function beforeCreate(): void
    {
        $factory = auth()->user()?->factory;

        if (!$factory || !$factory->canCreateMoreProducts()) {
            Notification::make()
                ->title(__('Product Listing Limit Reached'))
                ->body(__('You have reached the maximum of :limit product listings allowed on your current plan.', [
                    'limit' => $factory?->productLimit() ?? 10,
                ]))
                ->danger()
                ->send();

            $this->halt();
        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $factory = auth()->user()->factory;
        $data['factory_id'] = $factory->id;
        $data['status'] = in_array($data['status'] ?? '', ['draft', 'pending']) ? $data['status'] : 'pending';

        if (!empty($data['images']) && is_array($data['images'])) {
            $data['images'] = array_slice(array_values(array_filter($data['images'])), 0, $factory->maxImagesPerProduct());
            $data['image'] = $data['images'][0] ?? null;
        }

        return $data;
    }
}

