<?php

namespace App\Filament\Factory\Widgets;

use App\Models\Product;
use App\Models\StockOffer;
use App\Models\SupplierOffer;
use App\Models\BuyerRequest;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FactoryStatsOverview extends StatsOverviewWidget
{
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

        return [
            Stat::make(__('Total Products'), $totalProducts)
                ->description(__('approved: :approved, pending: :pending', ['approved' => $approvedProducts, 'pending' => $pendingProducts]))
                ->color('primary')
                ->icon('heroicon-o-shopping-bag'),

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
