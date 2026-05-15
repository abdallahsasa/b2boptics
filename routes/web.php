<?php

use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\FactoryController;
use App\Http\Controllers\SourcingController;
use App\Http\Controllers\StockOfferController;
use Illuminate\Support\Facades\Route;

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
Route::get('/stock-deals', [StockOfferController::class, 'index'])->name('stock-offers.index');

// Sourcing Requests ("Find Your Best Deal")
Route::group(['prefix' => 'find-deal', 'as' => 'sourcing.'], function () {
    Route::get('/', [SourcingController::class, 'index'])->name('index');
    Route::get('/create', [SourcingController::class, 'create'])->name('create')->middleware('auth');
    Route::post('/', [SourcingController::class, 'store'])->name('store')->middleware('auth');
    Route::get('/{buyerRequest}', [SourcingController::class, 'show'])->name('show');
});

// Language Switcher
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar', 'tr', 'zh', 'ru', 'de', 'es', 'fr', 'it'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

// Social Auth Placeholders
Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::get('/{provider}/redirect', function ($provider) {
        return "Redirecting to $provider...";
    })->name('redirect');
    Route::get('/{provider}/callback', function ($provider) {
        return "Callback from $provider...";
    })->name('callback');
});
