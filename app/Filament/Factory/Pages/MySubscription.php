<?php

namespace App\Filament\Factory\Pages;

use App\Models\SubscriptionPackage;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class MySubscription extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static ?int $navigationSort = 0;

    protected string $view = 'filament.factory.pages.my-subscription';

    public static function getNavigationLabel(): string
    {
        return __('My Subscription');
    }

    public function getTitle(): string
    {
        return __('Subscription & Packages');
    }

    public static function getNavigationBadge(): ?string
    {
        $factory = auth()->user()?->factory;
        if (!$factory) {
            return __('FREE');
        }

        $pkg = $factory->activePackage();
        if (!$pkg || $pkg->isFree()) {
            return __('FREE');
        }

        return strtoupper($pkg->slug);
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'success';
    }

    public function selectPackage(int $packageId): void
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

        $package = SubscriptionPackage::where('is_active', true)->findOrFail($packageId);

        $factory->update([
            'subscription_package_id' => $package->id,
            'subscription_plan' => $package->slug,
            'product_limit' => $package->product_limit,
            'max_images_per_product' => $package->max_images_per_product,
        ]);

        Notification::make()
            ->title(__('Subscription Package Activated'))
            ->body(__('Your factory is now subscribed to :plan (:limit products, :images images per product).', [
                'plan' => $package->name,
                'limit' => $package->product_limit,
                'images' => $package->max_images_per_product,
            ]))
            ->success()
            ->send();
    }
}
