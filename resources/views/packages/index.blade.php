@extends('layouts.app')

@section('title', __('Factory Subscription Packages - Free 10 Product Listings'))
@section('meta_title', __('Factory Subscription Packages | Join OpticB2B for Free'))
@section('meta_description', __('Explore OpticB2B factory membership packages. Start for free with 10 product listings, up to 10 images per product, direct buyer messaging, and a verified factory showroom.'))

@section('content')
    @php
        $userFactory = auth()->check() ? auth()->user()->factory : null;
        $activePackage = $userFactory?->activePackage();
        $usedProducts = $userFactory?->productsCount() ?? 0;
        $productLimit = $userFactory?->productLimit() ?? 10;
        $maxImages = $userFactory?->maxImagesPerProduct() ?? 10;
        $remainingProducts = max(0, $productLimit - $usedProducts);
        $usagePercent = $productLimit > 0 ? min(100, (int) round(($usedProducts / $productLimit) * 100)) : 0;
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-14">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-extrabold uppercase tracking-widest mb-4">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                {{ __('Factory Membership Program • 100% Free Now') }}
            </div>
            <h1 class="text-4xl sm:text-5xl font-extrabold text-slate-900 tracking-tight mb-4">
                {{ __('Factory Subscription Packages') }}
            </h1>
            <p class="text-lg text-slate-500 leading-relaxed">
                {{ __('List your optical frames, lenses, and lab machinery on OpticB2B for free. Every factory receives 10 free product listings with up to 10 high-resolution photos per product.') }}
            </p>
        </div>

        <!-- If Logged-in Factory Owner: Live Plan & Limitations Banner -->
        @if($userFactory)
            <div class="mb-14 bg-white rounded-3xl border-2 border-primary-600/20 shadow-xl shadow-primary-900/5 p-6 sm:p-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 pb-6 border-b border-slate-100">
                    <div>
                        <div class="flex flex-wrap items-center gap-2.5 mb-2">
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200">
                                {{ __('Your Active Factory Plan') }}
                            </span>
                            <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider bg-primary-50 text-primary-700 border border-primary-200">
                                {{ $userFactory->subscriptionPriceLabel() }}
                            </span>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-900">
                            {{ $userFactory->subscriptionPlanLabel() }} — {{ $userFactory->official_name }}
                        </h2>
                        <p class="text-sm text-slate-500 mt-1">
                            {{ __('Plan Limitations: Up to :limit product listings for free • Up to :images images per product', ['limit' => $productLimit, 'images' => $maxImages]) }}
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ url('/factory/products') }}"
                           class="px-6 py-3.5 bg-primary-600 hover:bg-primary-700 text-white rounded-2xl font-bold text-sm shadow-lg shadow-primary-600/20 transition-all">
                            {{ __('Manage My Products (:used/:limit)', ['used' => $usedProducts, 'limit' => $productLimit]) }}
                        </a>
                        <a href="{{ url('/factory/my-subscription') }}"
                           class="px-5 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-2xl font-bold text-sm transition-all">
                            {{ __('Open Factory Dashboard') }}
                        </a>
                    </div>
                </div>

                <!-- Live Quota & Limitations Bars -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 pt-6">
                    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100">
                        <div class="flex items-center justify-between text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">
                            <span>{{ __('Product Listings Limit') }}</span>
                            <span class="{{ $usedProducts >= $productLimit ? 'text-red-600' : 'text-primary-600' }}">
                                {{ $usedProducts }} / {{ $productLimit }} {{ __('Used') }}
                            </span>
                        </div>
                        <div class="w-full h-2.5 bg-slate-200 rounded-full overflow-hidden mb-2">
                            <div class="h-full rounded-full {{ $usedProducts >= $productLimit ? 'bg-red-500' : 'bg-primary-600' }}"
                                 style="width: {{ $usagePercent }}%"></div>
                        </div>
                        <p class="text-xs font-semibold text-slate-600">
                            {{ __(':remaining free product slots remaining', ['remaining' => $remainingProducts]) }}
                        </p>
                    </div>

                    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100">
                        <div class="flex items-center justify-between text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">
                            <span>{{ __('Images Per Product Limit') }}</span>
                            <span class="text-emerald-600">{{ __('Included') }}</span>
                        </div>
                        <p class="text-xl font-extrabold text-slate-900">
                            {{ __('Up to :images Images / Product', ['images' => $maxImages]) }}
                        </p>
                        <p class="text-xs text-slate-500 mt-1">
                            {{ __('Multi-photo gallery for every product listing') }}
                        </p>
                    </div>

                    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100">
                        <div class="flex items-center justify-between text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">
                            <span>{{ __('Subscription Cost') }}</span>
                            <span class="text-emerald-600">{{ __('Active') }}</span>
                        </div>
                        <p class="text-xl font-extrabold text-emerald-600">
                            {{ $userFactory->subscriptionPriceLabel() }}
                        </p>
                        <p class="text-xs text-slate-500 mt-1">
                            {{ __('Direct buyer inquiries & email notifications included') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Packages Cards -->
        <div class="flex justify-center">
            <div class="w-full {{ $packages->count() === 1 ? 'max-w-xl' : 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8' }}">
                @foreach($packages as $pkg)
                    @php
                        $isCurrent = $userFactory && (($activePackage && (int) $activePackage->id === (int) $pkg->id) || (!$activePackage && $pkg->is_default));
                        $features = is_array($pkg->features) && count($pkg->features) > 0
                            ? $pkg->features
                            : [
                                __('Up to :limit Product Listings for Free', ['limit' => $pkg->product_limit]),
                                __('Up to :images High-Resolution Images per Product', ['images' => $pkg->max_images_per_product]),
                                __('Direct Customer Messaging & Email Notifications'),
                                __('Verified Factory Showroom Profile (Video & 360 VR)'),
                                __('Access to Buyer Tenders & Stock Deals'),
                                __('Multi-Language Catalog Support (9 Languages)'),
                            ];
                    @endphp

                    <div class="bg-white rounded-[2.5rem] border-2 {{ $pkg->is_default ? 'border-primary-600 shadow-2xl shadow-primary-900/10' : 'border-slate-100 shadow-lg' }} p-8 sm:p-10 relative overflow-hidden flex flex-col justify-between">
                        <div>
                            <!-- Top Badge -->
                            <div class="flex items-center justify-between gap-3 mb-6">
                                <span class="px-4 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-wider {{ $pkg->isFree() ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-primary-50 text-primary-700' }}">
                                    {{ $pkg->isFree() ? __('Current Free Offer') : strtoupper($pkg->billing_cycle) }}
                                </span>
                                @if($isCurrent)
                                    <span class="px-3.5 py-1 rounded-full text-xs font-bold bg-primary-600 text-white">
                                        {{ __('Your Active Plan') }}
                                    </span>
                                @endif
                            </div>

                            <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mb-2">
                                {{ __($pkg->name) }}
                            </h3>

                            @if($pkg->description)
                                <p class="text-sm text-slate-500 leading-relaxed mb-6">
                                    {{ __($pkg->description) }}
                                </p>
                            @endif

                            <!-- Price Box -->
                            <div class="flex items-baseline gap-2 mb-8 pb-8 border-b border-slate-100">
                                @if($pkg->isFree())
                                    <span class="text-5xl font-black text-emerald-600">{{ __('FREE') }}</span>
                                    <span class="text-lg font-bold text-slate-400">($0.00)</span>
                                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 ml-1">{{ __('• No Credit Card Required') }}</span>
                                @else
                                    <span class="text-5xl font-black text-slate-900">{{ $pkg->currency }} {{ number_format((float) $pkg->price, 2) }}</span>
                                    <span class="text-sm font-bold text-slate-400">/ {{ $pkg->billing_cycle }}</span>
                                @endif
                            </div>

                            <!-- Key Package Limitations Highlight -->
                            <div class="grid grid-cols-2 gap-4 mb-8">
                                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                                    <span class="block text-3xl font-black text-primary-600">{{ $pkg->product_limit }}</span>
                                    <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">{{ __('Free Product Listings') }}</span>
                                </div>
                                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                                    <span class="block text-3xl font-black text-primary-600">{{ $pkg->max_images_per_product }}</span>
                                    <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">{{ __('Images Per Product') }}</span>
                                </div>
                            </div>

                            <!-- Included Features -->
                            <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-4">
                                {{ __('What is included in this package') }}
                            </p>
                            <ul class="space-y-3.5 mb-10">
                                @foreach($features as $feature)
                                    <li class="flex items-start gap-3">
                                        <div class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                        </div>
                                        <span class="text-sm font-semibold text-slate-700">{{ __($feature) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- CTA -->
                        <div>
                            @if($userFactory)
                                <a href="{{ url('/factory/my-subscription') }}"
                                   class="w-full py-4 bg-primary-600 hover:bg-primary-700 text-white rounded-2xl font-bold text-base shadow-xl shadow-primary-600/25 transition-all flex items-center justify-center gap-2">
                                    <span>{{ __('Manage Subscription in Factory Portal') }}</span>
                                    <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                                </a>
                            @else
                                <a href="{{ route('filament.factory.auth.register') }}"
                                   class="w-full py-4 bg-primary-600 hover:bg-primary-700 text-white rounded-2xl font-bold text-base shadow-xl shadow-primary-600/25 transition-all flex items-center justify-center gap-2">
                                    <span>{{ __('Join as Factory for Free') }}</span>
                                    <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

