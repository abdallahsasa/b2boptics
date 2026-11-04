<x-filament-panels::page>
    @php
        $factory = auth()->user()?->factory;
        $activePackage = $factory?->activePackage();
        $packages = \App\Models\SubscriptionPackage::where('is_active', true)
            ->orderByDesc('is_default')
            ->orderBy('sort_order')
            ->get();
        $planLabel = $factory?->subscriptionPlanLabel() ?? __('Free Starter Package');
        $priceLabel = $factory?->subscriptionPriceLabel() ?? __('FREE ($0)');
        $productLimit = $factory?->productLimit() ?? 10;
        $maxImages = $factory?->maxImagesPerProduct() ?? 10;
        $usedProducts = $factory?->productsCount() ?? 0;
        $remainingProducts = max(0, $productLimit - $usedProducts);
        $usagePercent = $productLimit > 0 ? min(100, (int) round(($usedProducts / $productLimit) * 100)) : 0;
    @endphp

    <div class="space-y-8">
        <!-- Current Active Plan & Limitations Summary -->
        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-gray-100 dark:border-gray-800 pb-6">
                <div>
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            {{ __('Current Active Package') }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider bg-primary-50 text-primary-700 dark:bg-primary-950/60 dark:text-primary-300 border border-primary-200 dark:border-primary-800">
                            {{ $priceLabel }}
                        </span>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-950 dark:text-white">
                        {{ $planLabel }}
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        {{ __('Your factory account includes :limit free product listings and up to :images images per product.', [
                            'limit' => $productLimit,
                            'images' => $maxImages,
                        ]) }}
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    @if($factory && $factory->canCreateMoreProducts())
                        <x-filament::button tag="a" href="{{ url('/factory/products/create') }}" icon="heroicon-o-plus">
                            {{ __('Add New Product (:remaining left)', ['remaining' => $remainingProducts]) }}
                        </x-filament::button>
                    @else
                        <x-filament::button color="gray" disabled icon="heroicon-o-lock-closed">
                            {{ __('Limit Reached (:limit/:limit)', ['limit' => $productLimit]) }}
                        </x-filament::button>
                    @endif
                </div>
            </div>

            <!-- Plan Limitations & Live Usage -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6">
                <div class="rounded-xl bg-gray-50 dark:bg-gray-800/60 p-5 border border-gray-100 dark:border-gray-800">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">{{ __('Product Listings Limitation') }}</span>
                        <span class="text-sm font-bold {{ $usedProducts >= $productLimit ? 'text-red-600' : 'text-primary-600' }}">
                            {{ $usedProducts }} / {{ $productLimit }} {{ __('Products Used') }}
                        </span>
                    </div>
                    <div class="w-full h-3 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-500 {{ $usedProducts >= $productLimit ? 'bg-red-500' : 'bg-primary-600' }}"
                             style="width: {{ $usagePercent }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                        @if($remainingProducts > 0)
                            {{ __('You can publish :remaining more product listings for free on your current package.', ['remaining' => $remainingProducts]) }}
                        @else
                            {{ __('You have reached the maximum of :limit free product listings on your current package.', ['limit' => $productLimit]) }}
                        @endif
                    </p>
                </div>

                <div class="rounded-xl bg-gray-50 dark:bg-gray-800/60 p-5 border border-gray-100 dark:border-gray-800">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">{{ __('Product Photo Gallery Limitation') }}</span>
                        <span class="text-sm font-bold text-emerald-600">
                            {{ __('Max :images Images / Product', ['images' => $maxImages]) }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 leading-relaxed">
                        {{ __('Each of your :limit product listings supports up to :images high-resolution photos with drag-and-drop gallery ordering.', [
                            'limit' => $productLimit,
                            'images' => $maxImages,
                        ]) }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Subscription Packages Grid -->
        <div>
            <div class="mb-4">
                <h3 class="text-lg font-bold text-gray-950 dark:text-white">{{ __('Available Subscription Packages') }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Choose the subscription package for your factory showroom and product catalog.') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($packages as $pkg)
                    @php
                        $isCurrent = ($activePackage && (int) $activePackage->id === (int) $pkg->id)
                            || (!$activePackage && $pkg->is_default);
                        $features = is_array($pkg->features) && count($pkg->features) > 0
                            ? $pkg->features
                            : [
                                __('Up to :limit Product Listings', ['limit' => $pkg->product_limit]),
                                __('Up to :images Images per Product', ['images' => $pkg->max_images_per_product]),
                                __('Direct Buyer Messaging & Email Notifications'),
                                __('Verified Factory Showroom Profile'),
                                __('Access to Buyer Tenders & Stock Deals'),
                            ];
                    @endphp

                    <div class="rounded-2xl border-2 {{ $isCurrent ? 'border-primary-600 dark:border-primary-500 ring-2 ring-primary-600/10' : 'border-gray-200 dark:border-gray-800' }} bg-white dark:bg-gray-900 p-6 shadow-sm flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $pkg->isFree() ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300' : 'bg-primary-50 text-primary-700' }}">
                                    {{ $pkg->isFree() ? __('100% Free Package') : strtoupper($pkg->billing_cycle) }}
                                </span>
                                @if($isCurrent)
                                    <span class="inline-flex items-center gap-1 text-xs font-bold text-primary-600 dark:text-primary-400">
                                        <x-filament::icon icon="heroicon-m-check-badge" class="w-4 h-4" />
                                        {{ __('Current Plan') }}
                                    </span>
                                @endif
                            </div>

                            <h4 class="text-xl font-bold text-gray-950 dark:text-white">{{ $pkg->name }}</h4>

                            <div class="mt-3 mb-4 flex items-baseline gap-2">
                                @if($pkg->isFree())
                                    <span class="text-3xl font-extrabold text-emerald-600">{{ __('FREE') }}</span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">($0.00)</span>
                                @else
                                    <span class="text-3xl font-extrabold text-gray-950 dark:text-white">{{ $pkg->currency }} {{ number_format((float) $pkg->price, 2) }}</span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">/ {{ $pkg->billing_cycle }}</span>
                                @endif
                            </div>

                            @if($pkg->description)
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-5 leading-relaxed">
                                    {{ $pkg->description }}
                                </p>
                            @endif

                            <!-- Package Quota Badges -->
                            <div class="grid grid-cols-2 gap-2.5 mb-5">
                                <div class="rounded-xl bg-gray-50 dark:bg-gray-800 p-3 text-center border border-gray-100 dark:border-gray-700">
                                    <p class="text-lg font-extrabold text-gray-950 dark:text-white">{{ $pkg->product_limit }}</p>
                                    <p class="text-[11px] font-semibold text-gray-500 dark:text-gray-400">{{ __('Products Limit') }}</p>
                                </div>
                                <div class="rounded-xl bg-gray-50 dark:bg-gray-800 p-3 text-center border border-gray-100 dark:border-gray-700">
                                    <p class="text-lg font-extrabold text-gray-950 dark:text-white">{{ $pkg->max_images_per_product }}</p>
                                    <p class="text-[11px] font-semibold text-gray-500 dark:text-gray-400">{{ __('Images / Product') }}</p>
                                </div>
                            </div>

                            <!-- Feature Checklist -->
                            <ul class="space-y-2.5 text-sm border-t border-gray-100 dark:border-gray-800 pt-4 mb-6">
                                @foreach($features as $feature)
                                    <li class="flex items-start gap-2.5 text-gray-700 dark:text-gray-300">
                                        <x-filament::icon icon="heroicon-o-check-circle" class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" />
                                        <span class="text-xs font-medium">{{ $feature }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div>
                            @if($isCurrent)
                                <x-filament::button color="success" disabled class="w-full" icon="heroicon-m-check-circle">
                                    {{ __('Active on Your Account') }}
                                </x-filament::button>
                            @else
                                <x-filament::button wire:click="selectPackage({{ $pkg->id }})" class="w-full" icon="heroicon-o-bolt">
                                    {{ __('Activate :name', ['name' => $pkg->name]) }}
                                </x-filament::button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-filament-panels::page>
