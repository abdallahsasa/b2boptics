<?php

namespace App\Filament\Widgets;

use App\Models\BuyerRequest;
use App\Models\Factory;
use App\Models\Product;
use App\Models\StockOffer;
use App\Models\SupplierOffer;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Factories', Factory::count())
                ->description('Active and pending factories')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->icon('heroicon-o-building-office-2')
                ->color('success'),
            Stat::make('Pending Factories', Factory::where('status', 'pending')->count())
                ->description('Requires review')
                ->icon('heroicon-o-clock')
                ->color('warning'),
            Stat::make('Total Products', Product::count())
                ->icon('heroicon-o-shopping-bag')
                ->description('All listed products'),
            Stat::make('Pending Products', Product::where('status', 'pending')->count())
                ->description('Requires review')
                ->icon('heroicon-o-exclamation-circle')
                ->color('warning'),
            Stat::make('Buyer Requests', BuyerRequest::count())
                ->icon('heroicon-o-inbox-arrow-down')
                ->description('Total deal requests'),
            Stat::make('Supplier Offers', SupplierOffer::count())
                ->icon('heroicon-o-chat-bubble-left-right')
                ->description('Total offers sent'),
            Stat::make('Stock Offers', StockOffer::count())
                ->icon('heroicon-o-tag')
                ->description('Active stock deals'),
        ];
    }
}
