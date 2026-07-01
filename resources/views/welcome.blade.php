@extends('layouts.app')

@section('title', __('OpticB2B - Global Optical Marketplace'))

@php
    $categories = \App\Models\Category::where('type', 'product')->get();
    $countries = \App\Models\Country::where('status', 'active')->get();
    
    $products = \App\Models\Product::with(['factory.country', 'category'])
        ->where('status', 'approved')
        ->latest()
        ->take(8)
        ->get();
        
    $stockOffers = \App\Models\StockOffer::with(['factory', 'category'])
        ->where('status', 'active')
        ->where('ends_at', '>', now())
        ->latest()
        ->take(4)
        ->get();
        
    $factoriesCount = \App\Models\Factory::where('status', 'approved')->count();
    $productsCount = \App\Models\Product::where('status', 'approved')->count();
    $countriesCount = \App\Models\Country::where('status', 'active')->count();
    
    // Round down to neat numbers for trust strip
    $factoriesMetric = floor($factoriesCount / 10) * 10;
    $productsMetric = floor($productsCount / 50) * 50;
    $countriesMetric = floor($countriesCount / 5) * 5;
@endphp

@section('content')

    <!-- SECTION 1: HERO (Search-first interface with video background) -->
    <section class="relative overflow-hidden py-20 lg:py-32 min-h-[500px] flex items-center">
        <!-- Video Background -->
        <video autoplay loop muted playsinline class="absolute inset-0 w-full h-full object-cover z-0">
            <source src="{{ asset('uploads/videos/hero.mov') }}" type="video/mp4">
        </video>
        <!-- Dark Overlay -->
        <div class="absolute inset-0 bg-slate-950/70 z-10"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-20 w-full">
            <div class="flex flex-col lg:flex-row gap-12 items-center">
                <!-- Search Interface -->
                <div class="flex-1 w-full">
                    <h1 class="text-4xl lg:text-5xl font-bold text-white leading-tight mb-4">
                        {{ __('Find Wholesale Optical Products & Reliable Factories') }}
                    </h1>
                    <p class="text-lg text-slate-200 mb-8 max-w-xl">
                        {{ __('The leading B2B sourcing platform for lenses, frames, and optical machinery.') }}
                    </p>
                    
                    <div class="bg-white p-4 rounded-2xl shadow-2xl border border-slate-100">
                        <form action="{{ route('marketplace.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
                            <div class="flex-1 relative">
                                <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                <input type="text" name="search" placeholder="{{ __('Search lenses, frames, machines...') }}" class="w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-primary-600 text-slate-900 font-medium">
                            </div>
                            <div class="md:w-48 relative">
                                <select name="category" class="w-full px-4 py-4 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-primary-600 text-slate-900 font-medium appearance-none">
                                    <option value="">{{ __('All Categories') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->slug }}">{{ $category->getTranslation('name', app()->getLocale()) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:w-48 relative">
                                <select name="country" class="w-full px-4 py-4 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-primary-600 text-slate-900 font-medium appearance-none">
                                    <option value="">{{ __('All Countries') }}</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->code }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="px-8 py-4 bg-primary-600 text-white rounded-xl font-bold hover:bg-primary-700 transition-colors whitespace-nowrap">
                                {{ __('Search') }}
                            </button>
                        </form>
                    </div>
                    
                    <div class="mt-6 flex flex-wrap items-center gap-3 text-sm">
                        <span class="text-slate-300 font-medium">{{ __('Popular:') }}</span>
                        <a href="{{ route('marketplace.index', ['search' => 'Resin Lenses']) }}" class="px-3 py-1.5 bg-white/10 backdrop-blur border border-white/20 rounded-lg text-white font-medium hover:bg-white/20 hover:border-white transition-colors">{{ __('Resin Lenses') }}</a>
                        <a href="{{ route('marketplace.index', ['search' => 'Titanium Frames']) }}" class="px-3 py-1.5 bg-white/10 backdrop-blur border border-white/20 rounded-lg text-white font-medium hover:bg-white/20 hover:border-white transition-colors">{{ __('Titanium Frames') }}</a>
                        <a href="{{ route('marketplace.index', ['search' => 'Edger Machine']) }}" class="px-3 py-1.5 bg-white/10 backdrop-blur border border-white/20 rounded-lg text-white font-medium hover:bg-white/20 hover:border-white transition-colors">{{ __('Edger Machines') }}</a>
                    </div>
                </div>

                <!-- Live Preview -->
                @if($products->isNotEmpty())
                <div class="hidden lg:block w-96 relative">
                    <div class="absolute -inset-4 bg-gradient-to-r from-white/10 to-white/5 rounded-[2rem] transform rotate-3"></div>
                    <div class="relative bg-white rounded-2xl border border-slate-200 shadow-xl overflow-hidden p-4">
                        <div class="flex items-center justify-between mb-4 pb-4 border-b border-slate-100">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('Live Products') }}</span>
                            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                        </div>
                        <div class="space-y-4">
                            @foreach($products->take(3) as $preview)
                                <a href="{{ route('marketplace.product.show', $preview) }}" class="flex items-center gap-4 group">
                                    <div class="w-16 h-16 rounded-xl overflow-hidden bg-slate-50 shrink-0 border border-slate-100">
                                        <img src="{{ $preview->getFirstMediaUrl('images') ?: 'https://images.unsplash.com/photo-1574258495973-f010dfbb5371?q=80&w=200&auto=format&fit=crop' }}" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900 group-hover:text-primary-600 transition-colors line-clamp-1">{{ $preview->getTranslation('name', app()->getLocale()) }}</p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-xs text-slate-500">{{ $preview->factory->country->name ?? 'Global' }}</span>
                                            <span class="text-xs font-bold text-primary-600">{{ $preview->currency }} {{ number_format($preview->starting_price, 2) }}</span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>

    <!-- SECTION 2: TRUST STRIP -->
    <section class="bg-white border-b border-slate-200 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 divide-x divide-slate-100">
                <div class="text-center px-4">
                    <p class="text-3xl font-bold text-slate-900">{{ max(120, $factoriesMetric) }}+</p>
                    <p class="text-sm text-slate-500 font-medium uppercase tracking-wider mt-1">{{ __('Verified Factories') }}</p>
                </div>
                <div class="text-center px-4">
                    <p class="text-3xl font-bold text-slate-900">{{ max(500, $productsMetric) }}+</p>
                    <p class="text-sm text-slate-500 font-medium uppercase tracking-wider mt-1">{{ __('Products Listed') }}</p>
                </div>
                <div class="text-center px-4">
                    <p class="text-3xl font-bold text-slate-900">{{ max(10, $countriesMetric) }}+</p>
                    <p class="text-sm text-slate-500 font-medium uppercase tracking-wider mt-1">{{ __('Countries') }}</p>
                </div>
                <div class="text-center px-4">
                    <p class="text-3xl font-bold text-slate-900">100%</p>
                    <p class="text-sm text-slate-500 font-medium uppercase tracking-wider mt-1">{{ __('B2B Focused') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 3: HOW IT WORKS -->
    <section class="py-16 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-2xl font-bold text-slate-900">{{ __('How the Marketplace Works') }}</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-2xl border border-slate-200 text-center shadow-sm">
                    <div class="w-12 h-12 bg-primary-50 text-primary-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">1. {{ __('Search Products') }}</h3>
                    <p class="text-slate-600 text-sm">{{ __('Browse thousands of optical products directly from verified manufacturers.') }}</p>
                </div>
                <div class="bg-white p-8 rounded-2xl border border-slate-200 text-center shadow-sm relative">
                    <div class="hidden md:block absolute top-1/2 -translate-y-1/2 -left-4 w-8 h-8 bg-slate-50 border border-slate-200 rounded-full flex items-center justify-center text-slate-400 z-10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </div>
                    <div class="w-12 h-12 bg-primary-50 text-primary-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">2. {{ __('Compare Suppliers') }}</h3>
                    <p class="text-slate-600 text-sm">{{ __('Review factory profiles, certifications, and country of origin.') }}</p>
                </div>
                <div class="bg-white p-8 rounded-2xl border border-slate-200 text-center shadow-sm relative">
                    <div class="hidden md:block absolute top-1/2 -translate-y-1/2 -left-4 w-8 h-8 bg-slate-50 border border-slate-200 rounded-full flex items-center justify-center text-slate-400 z-10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </div>
                    <div class="w-12 h-12 bg-primary-50 text-primary-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">3. {{ __('Request Quotes') }}</h3>
                    <p class="text-slate-600 text-sm">{{ __('Contact suppliers directly to negotiate bulk pricing and shipping.') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 4: PRODUCT PREVIEW -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-10">
                <h2 class="text-2xl font-bold text-slate-900">{{ __('Latest Products') }}</h2>
                <a href="{{ route('marketplace.index') }}" class="text-sm font-bold text-primary-600 hover:text-primary-700 transition-colors flex items-center gap-2">
                    {{ __('View Products') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <a href="{{ route('marketplace.product.show', $product) }}" class="group bg-white border border-slate-200 rounded-2xl overflow-hidden hover:border-primary-600 transition-colors flex flex-col h-full shadow-sm">
                        <div class="aspect-square bg-slate-50 relative overflow-hidden border-b border-slate-100">
                            <img src="{{ $product->getFirstMediaUrl('images') ?: 'https://images.unsplash.com/photo-1574258495973-f010dfbb5371?q=80&w=600&auto=format&fit=crop' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute top-3 left-3 flex flex-col gap-2">
                                <span class="bg-white/90 backdrop-blur-sm px-2 py-1 text-[10px] font-bold text-slate-900 rounded-md shadow-sm border border-slate-100 uppercase tracking-wider">
                                    {{ $product->category->getTranslation('name', app()->getLocale()) ?? 'Optics' }}
                                </span>
                            </div>
                        </div>
                        <div class="p-4 flex-1 flex flex-col">
                            <h3 class="font-bold text-slate-900 text-sm mb-1 group-hover:text-primary-600 transition-colors line-clamp-2">
                                {{ $product->getTranslation('name', app()->getLocale()) }}
                            </h3>
                            <div class="flex items-center gap-1.5 mb-4">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                <span class="text-xs font-medium text-slate-500">{{ $product->factory->country->name ?? 'Global' }}</span>
                            </div>
                            <div class="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('From') }}</span>
                                <span class="text-base font-bold text-slate-900">{{ $product->currency }} {{ number_format($product->starting_price, 2) }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- SECTION 5: REQUEST-BASED SYSTEM -->
    <section class="py-20 bg-slate-900 text-white overflow-hidden relative">
        <div class="absolute top-0 right-0 -translate-y-12 translate-x-1/3 w-96 h-96 bg-primary-600/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 translate-y-1/3 -translate-x-1/3 w-96 h-96 bg-primary-600/20 rounded-full blur-3xl"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="px-3 py-1 bg-white/10 text-white/80 text-xs font-bold rounded-full uppercase tracking-widest border border-white/20 mb-4 inline-block">{{ __('Core Feature') }}</span>
                <h2 class="text-3xl lg:text-4xl font-bold mb-6">{{ __('Submit a Request – Let Suppliers Compete') }}</h2>
                <p class="text-lg text-slate-300">
                    {{ __('Don\'t want to search? Post your exact optical requirements and let verified global factories send you their best offers.') }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-12">
                <div class="bg-white/5 border border-white/10 rounded-2xl p-6 relative">
                    <div class="text-2xl font-bold text-primary-400 mb-4">01</div>
                    <h3 class="font-bold text-white mb-2">{{ __('Buyer Submits Request') }}</h3>
                    <p class="text-sm text-slate-400">{{ __('Specify product details, target price, and quantity.') }}</p>
                    <div class="hidden md:block absolute top-1/2 -right-4 text-white/20 -translate-y-1/2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </div>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-2xl p-6 relative">
                    <div class="text-2xl font-bold text-primary-400 mb-4">02</div>
                    <h3 class="font-bold text-white mb-2">{{ __('Suppliers Notified') }}</h3>
                    <p class="text-sm text-slate-400">{{ __('Relevant factories receive your anonymous sourcing request.') }}</p>
                    <div class="hidden md:block absolute top-1/2 -right-4 text-white/20 -translate-y-1/2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </div>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-2xl p-6 relative">
                    <div class="text-2xl font-bold text-primary-400 mb-4">03</div>
                    <h3 class="font-bold text-white mb-2">{{ __('Offers Received') }}</h3>
                    <p class="text-sm text-slate-400">{{ __('Factories compete by sending competitive bids and PDFs.') }}</p>
                    <div class="hidden md:block absolute top-1/2 -right-4 text-white/20 -translate-y-1/2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </div>
                </div>
                <div class="bg-white/5 border border-primary-500/30 rounded-2xl p-6 shadow-[0_0_30px_rgba(34,84,197,0.2)]">
                    <div class="text-2xl font-bold text-primary-400 mb-4">04</div>
                    <h3 class="font-bold text-white mb-2">{{ __('Select Best Deal') }}</h3>
                    <p class="text-sm text-slate-400">{{ __('Review offers, select the winner, and finalize the deal.') }}</p>
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('sourcing.index') }}" class="inline-flex items-center gap-3 px-8 py-4 bg-primary-600 text-white rounded-xl font-bold hover:bg-primary-500 transition-colors">
                    {{ __('Post Your Request') }}
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                </a>
            </div>
        </div>
    </section>

    <!-- SECTION 6: STOCK OFFERS -->
    @if($stockOffers->isNotEmpty())
    <section class="py-20 bg-slate-50 border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-10">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-red-500 animate-pulse"></span>
                        {{ __('Live Stock Clearance') }}
                    </h2>
                    <p class="text-slate-500 text-sm mt-1">{{ __('Limited-time inventory deals from manufacturers.') }}</p>
                </div>
                <a href="{{ route('stock-offers.index') }}" class="text-sm font-bold text-primary-600 hover:text-primary-700 transition-colors flex items-center gap-2">
                    {{ __('View All Deals') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($stockOffers as $offer)
                    <div class="bg-white border border-red-100 rounded-2xl overflow-hidden relative shadow-sm hover:shadow-md transition-shadow flex flex-col h-full">
                        <div class="absolute top-3 right-3 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm z-10 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            {{ \Carbon\Carbon::parse($offer->ends_at)->diffForHumans() }}
                        </div>
                        <div class="aspect-[4/3] bg-slate-100 relative">
                            <img src="{{ collect($offer->images)->first() ?: 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?q=80&w=400&auto=format&fit=crop' }}" class="w-full h-full object-cover">
                        </div>
                        <div class="p-5 flex-1 flex flex-col">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">{{ $offer->category->getTranslation('name', app()->getLocale()) }}</span>
                            <h3 class="font-bold text-slate-900 text-sm mb-4 line-clamp-2">{{ $offer->getTranslation('title', app()->getLocale()) }}</h3>
                            
                            <div class="mt-auto">
                                <div class="flex items-end justify-between mb-4">
                                    <div>
                                        <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold">{{ __('Price') }}</p>
                                        <p class="text-xl font-bold text-red-600">${{ number_format($offer->price, 2) }} <span class="text-xs text-slate-500 font-medium lowercase">/ {{ __('unit') }}</span></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold">{{ __('MOQ') }}</p>
                                        <p class="text-sm font-bold text-slate-900">{{ $offer->moq }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('stock-offers.show', $offer) }}" class="block w-full py-2.5 bg-slate-900 text-white text-xs font-bold uppercase tracking-wider rounded-lg text-center hover:bg-black transition-colors">
                                    {{ __('Grab Deal') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- SECTION 7 & 8: FOR FACTORIES & FINAL CTA -->
    <section class="py-24 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-primary-50 rounded-3xl p-8 md:p-12 border border-primary-100 text-center">
                <span class="px-3 py-1 bg-white text-primary-600 text-xs font-bold rounded-full uppercase tracking-widest border border-primary-100 mb-6 inline-block">{{ __('For Manufacturers') }}</span>
                <h2 class="text-3xl font-bold text-slate-900 mb-4">{{ __('Expand Your Reach to International Buyers') }}</h2>
                <p class="text-slate-600 mb-8 max-w-2xl mx-auto">
                    {{ __('List your factory, showcase your product catalog professionally, and receive qualified sourcing requests from global optical buyers.') }}
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('marketplace.index') }}" class="w-full sm:w-auto px-8 py-4 bg-slate-900 text-white rounded-xl font-bold hover:bg-black transition-colors">
                        {{ __('Search Products') }}
                    </a>
                    <a href="{{ url('/admin/register') }}" class="w-full sm:w-auto px-8 py-4 bg-white text-primary-600 border-2 border-primary-100 rounded-xl font-bold hover:border-primary-600 transition-colors">
                        {{ __('List Your Factory') }}
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection
