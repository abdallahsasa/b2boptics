<?php

namespace App\Filament\Factory\Resources\ProductResource\Pages;

use App\Filament\Factory\Resources\ProductResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\ListRecords\Concerns\Translatable;

class ListProducts extends ListRecords
{
    use Translatable;

    protected static string $resource = ProductResource::class;

    public function getSubheading(): ?string
    {
        $factory = auth()->user()?->factory;
        if (!$factory) {
            return null;
        }

        return __(':plan: :used / :limit product listings used • Up to :images images per product', [
            'plan' => $factory->subscriptionPlanLabel(),
            'used' => $factory->productsCount(),
            'limit' => $factory->productLimit(),
            'images' => $factory->maxImagesPerProduct(),
        ]);
    }

    protected function getHeaderActions(): array
    {
        $factory = auth()->user()?->factory;
        $canCreate = $factory ? $factory->canCreateMoreProducts() : false;

        return [
            LocaleSwitcher::make(),
            $canCreate
                ? CreateAction::make()
                    ->label(__('New Product (:remaining left)', [
                        'remaining' => $factory->remainingProductSlots(),
                    ]))
                : Action::make('limitReached')
                    ->label(__('Plan Limit Reached (:limit/:limit)', [
                        'limit' => $factory?->productLimit() ?? 10,
                    ]))
                    ->color('gray')
                    ->icon('heroicon-o-lock-closed')
                    ->action(function () use ($factory) {
                        Notification::make()
                            ->title(__('Product Listing Limit Reached'))
                            ->body(__('Your current :plan allows up to :limit product listings for free. Delete an existing product or upgrade your subscription to add more.', [
                                'plan' => $factory?->subscriptionPlanLabel() ?? __('Free Plan'),
                                'limit' => $factory?->productLimit() ?? 10,
                            ]))
                            ->warning()
                            ->send();
                    }),
        ];
    }
}

