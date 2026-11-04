<?php

namespace App\Filament\Factory\Widgets;

use App\Models\Conversation;
use App\Models\Product;
use App\Models\StockOffer;
use App\Models\SupplierOffer;
use App\Models\BuyerRequest;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FactoryStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $factory = auth()->user()->factory;

        if (!$factory) {
            return [
                Stat::make(__('Status'), __('No Factory Profile'))
                    ->description(__('Complete your factory registration'))
                    ->color('danger')
                    ->icon('heroicon-o-exclamation-triangle'),
            ];
        }

        $totalProducts = Product::where('factory_id', $factory->id)->count();
        $approvedProducts = Product::where('factory_id', $factory->id)->where('status', 'approved')->count();
        $pendingProducts = Product::where('factory_id', $factory->id)->where('status', 'pending')->count();
        $totalStockOffers = StockOffer::where('factory_id', $factory->id)->count();
        $activeStockOffers = StockOffer::where('factory_id', $factory->id)->where('status', 'active')->count();
        $totalSupplierOffers = SupplierOffer::where('factory_id', $factory->id)->count();
        $activeTenders = BuyerRequest::where('status', 'approved')->count();
        $totalMessages = Conversation::where('factory_id', $factory->id)->count();
        $unreadMessages = Conversation::where('factory_id', $factory->id)
            ->whereIn('status', ['human_required', 'open', 'ai_active'])
            ->count();

        $productLimit = $factory->productLimit();
        $maxImages = $factory->maxImagesPerProduct();

        return [
            Stat::make(__('Subscription Plan'), $factory->subscriptionPlanLabel())
                ->description(__(':used/:limit products • :images images/product', [
                    'used' => $totalProducts,
                    'limit' => $productLimit,
                    'images' => $maxImages,
                ]))
                ->color('success')
                ->icon('heroicon-o-sparkles')
                ->url(url('/factory/my-subscription')),

            Stat::make(__('Customer Messages'), $totalMessages)
                ->description(__('awaiting reply: :unread', ['unread' => $unreadMessages]))
                ->color($unreadMessages > 0 ? 'warning' : 'success')
                ->icon('heroicon-o-envelope')
                ->url(url('/factory/conversations')),

            Stat::make(__('Product Listings'), "{$totalProducts} / {$productLimit}")
                ->description(__('approved: :approved, pending: :pending', ['approved' => $approvedProducts, 'pending' => $pendingProducts]))
                ->color($totalProducts >= $productLimit ? 'danger' : 'primary')
                ->icon('heroicon-o-shopping-bag')
                ->url(url('/factory/products')),

            Stat::make(__('Stock Deals'), $totalStockOffers)
                ->description(__('currently active: :active', ['active' => $activeStockOffers]))
                ->color('success')
                ->icon('heroicon-o-tag'),

            Stat::make(__('Offers Submitted'), $totalSupplierOffers)
                ->description(__('Your bids on buyer tenders'))
                ->color('warning')
                ->icon('heroicon-o-chat-bubble-left-right'),

            Stat::make(__('Open Tenders'), $activeTenders)
                ->description(__('Available opportunities'))
                ->color('info')
                ->icon('heroicon-o-inbox-arrow-down'),
        ];
    }
}
