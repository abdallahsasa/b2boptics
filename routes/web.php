<?php

use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\FactoryController;
use App\Http\Controllers\SourcingController;
use App\Http\Controllers\StockOfferController;
use Illuminate\Support\Facades\Route;

// Localization Group
Route::group([
    'prefix' => '{locale}',
    'where' => ['locale' => 'en|ar|tr|zh|ru|de|es|fr|it'],
    'middleware' => ['web']
], function () {

    // Landing Page
    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    // Marketplace
    Route::group(['prefix' => 'marketplace', 'as' => 'marketplace.'], function () {
        Route::get('/', [MarketplaceController::class, 'index'])->name('index');
        Route::get('/product/{product:slug}', [MarketplaceController::class, 'show'])->name('product.show');
    });

    // Factories
    Route::get('/factories/{factory:slug}', [FactoryController::class, 'show'])->name('factories.show');

    // Stock Deals
    Route::group(['prefix' => 'stock-deals', 'as' => 'stock-offers.'], function () {
        Route::get('/', [StockOfferController::class, 'index'])->name('index');
        Route::get('/{stockOffer}', [StockOfferController::class, 'show'])->name('show');
        Route::post('/{stockOffer}/request', [StockOfferController::class, 'request'])->name('request');
    });
    // Sourcing Requests ("Find Your Best Deal")
    Route::group(['prefix' => 'find-deal', 'as' => 'sourcing.'], function () {
        Route::get('/', [SourcingController::class, 'index'])->name('index');
        Route::get('/create', [SourcingController::class, 'create'])->name('create')->middleware('auth');
        Route::post('/', [SourcingController::class, 'store'])->name('store')->middleware('auth');
        Route::get('/{buyerRequest}', [SourcingController::class, 'show'])->name('show');
    });

    // Social Auth Placeholders
    Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::get('/{provider}/redirect', function ($provider) {
            return "Redirecting to $provider...";
        })->name('redirect');
        Route::get('/{provider}/callback', function ($provider) {
            return "Callback from $provider...";
        })->name('callback');
    });
});

// Language Switcher - Handles switching and redirects to the same page with new prefix
Route::get('/lang/{locale}', function ($locale) {
    $locales = ['en', 'ar', 'tr', 'zh', 'ru', 'de', 'es', 'fr', 'it'];
    if (in_array($locale, $locales)) {
        session(['locale' => $locale]);
        
        $previousUrl = url()->previous();
        $path = parse_url($previousUrl, PHP_URL_PATH);
        $segments = explode('/', trim($path, '/'));
        
        if (count($segments) > 0 && in_array($segments[0], $locales)) {
            $segments[0] = $locale;
            return redirect('/' . implode('/', $segments));
        }
        
        return redirect('/' . $locale);
    }
    return redirect()->back();
})->name('lang.switch');

// Fallback for root (optional, middleware usually handles it but good to have)
Route::get('/', function () {
    return redirect('/' . (session('locale') ?? 'en'));
});
