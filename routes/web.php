<?php

use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\FactoryController;
use App\Http\Controllers\SourcingController;
use App\Http\Controllers\StockOfferController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Fallback login route for the auth middleware
Route::get('/login', function () {
    return redirect()->route('filament.admin.auth.login');
})->name('login');

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

    // Factory Subscription Packages Page
    Route::get('/packages', function () {
        $packages = \App\Models\SubscriptionPackage::where('is_active', true)
            ->orderByDesc('is_default')
            ->orderBy('sort_order')
            ->get();

        return view('packages.index', compact('packages'));
    })->name('packages.index');

    // Informational & Support Pages
    Route::view('/how-it-works', 'pages.how-it-works')->name('pages.how-it-works');
    Route::view('/contact-us', 'pages.contact')->name('pages.contact');
    Route::post('/contact-us', function (\Illuminate\Http\Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'company' => 'nullable|string|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:3000',
        ]);

        try {
            \Illuminate\Support\Facades\Mail::raw(
                "New Contact Us Inquiry on OpticB2B\n\n" .
                "Name: {$validated['name']}\n" .
                "Email: {$validated['email']}\n" .
                "Company: " . ($validated['company'] ?: 'N/A') . "\n" .
                "Subject: {$validated['subject']}\n\n" .
                "Message:\n{$validated['message']}",
                function ($msg) use ($validated) {
                    $msg->to('support@opticb2b.com')
                        ->replyTo($validated['email'], $validated['name'])
                        ->subject('[OpticB2B Contact] ' . $validated['subject']);
                }
            );
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Contact form email error: ' . $e->getMessage());
        }

        return redirect()->back()->with('contact_success', __('Thank you! Your message has been sent to our support team.'));
    })->name('pages.contact.submit');
    Route::view('/privacy-policy', 'pages.privacy')->name('pages.privacy');

    // Marketplace
    Route::group(['prefix' => 'marketplace', 'as' => 'marketplace.'], function () {
        Route::get('/', [MarketplaceController::class, 'index'])->name('index');
        Route::get('/product/{product:slug}', [MarketplaceController::class, 'show'])->name('product.show');
    });

    // Factories
    Route::group(['prefix' => 'factories', 'as' => 'factories.'], function () {
        Route::get('/', [FactoryController::class, 'index'])->name('index');
        Route::get('/{factory:slug}', [FactoryController::class, 'show'])->name('show');
        Route::post('/{factory:slug}/rate', [FactoryController::class, 'rate'])->name('rate')->middleware('auth');
        Route::post('/{factory:slug}/inquire', [FactoryController::class, 'inquire'])->name('inquire')->middleware('auth');
    });

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

    // Chat
    Route::group(['prefix' => 'chat', 'as' => 'chat.', 'middleware' => 'auth'], function () {
        Route::post('/start', [ChatController::class, 'start'])->name('start');
        Route::post('/message', [ChatController::class, 'message'])->name('message');
        Route::post('/rate', [ChatController::class, 'rate'])->name('rate');
    });

    // Frontend Auth
    Route::group(['prefix' => 'api-auth', 'as' => 'api-auth.'], function () {
        Route::post('/login', [AuthController::class, 'login'])->name('login');
        Route::post('/register', [AuthController::class, 'register'])->name('register');
    });
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

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

        if (auth()->check() && auth()->user()->factory) {
            auth()->user()->factory->update(['language' => $locale]);
        }
        
        $previousUrl = url()->previous();
        $path = parse_url($previousUrl, PHP_URL_PATH);
        $segments = explode('/', trim($path, '/'));
        
        if (count($segments) > 0 && in_array($segments[0], ['factory', 'admin'])) {
            return redirect($previousUrl);
        }

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
