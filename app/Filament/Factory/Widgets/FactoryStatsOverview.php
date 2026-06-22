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
                Stat::make('Status', 'No Factory Profile')
                    ->description('Complete your factory registration')
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
            Stat::make('Total Products', $totalProducts)
                ->description($approvedProducts . ' approved, ' . $pendingProducts . ' pending')
                ->color('primary')
                ->icon('heroicon-o-shopping-bag'),

            Stat::make('Stock Deals', $totalStockOffers)
                ->description($activeStockOffers . ' currently active')
                ->color('success')
                ->icon('heroicon-o-tag'),

            Stat::make('Offers Submitted', $totalSupplierOffers)
                ->description('Your bids on buyer tenders')
                ->color('warning')
                ->icon('heroicon-o-chat-bubble-left-right'),

            Stat::make('Open Tenders', $activeTenders)
                ->description('Available opportunities')
                ->color('info')
                ->icon('heroicon-o-inbox-arrow-down'),
        ];
    }
}
