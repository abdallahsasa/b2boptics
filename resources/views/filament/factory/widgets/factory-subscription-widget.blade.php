<x-filament-widgets::widget>
    @php
        $factory = auth()->user()?->factory;
        $package = $factory?->activePackage();
        $planName = $factory?->subscriptionPlanLabel() ?? __('Free Starter Package');
        $priceLabel = $factory?->subscriptionPriceLabel() ?? __('FREE ($0)');
        $productLimit = $factory?->productLimit() ?? 10;
        $maxImages = $factory?->maxImagesPerProduct() ?? 10;
        $usedProducts = $factory?->productsCount() ?? 0;
        $remainingProducts = max(0, $productLimit - $usedProducts);
        $usagePercent = $productLimit > 0 ? min(100, (int) round(($usedProducts / $productLimit) * 100)) : 0;
    @endphp

    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 shadow-sm">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <!-- Left: Package Name & Free Badge -->
            <div class="space-y-2">
                <div class="flex flex-wrap items-center gap-2.5">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        {{ __('Active Subscription Plan') }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider bg-primary-50 text-primary-700 dark:bg-primary-950/60 dark:text-primary-300 border border-primary-200 dark:border-primary-800">
                        {{ $priceLabel }}
                    </span>
                </div>

                <h3 class="text-xl font-bold text-gray-950 dark:text-white">
                    {{ $planName }}
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $package?->description ?? __('Free factory membership with up to :limit product listings and up to :images images per product.', ['limit' => $productLimit, 'images' => $maxImages]) }}
                </p>
            </div>

            <!-- Right: Action Buttons -->
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                @if($factory && $factory->canCreateMoreProducts())
                    <x-filament::button tag="a" href="{{ url('/factory/products/create') }}" icon="heroicon-o-plus">
                        {{ __('Add Product (:remaining left)', ['remaining' => $remainingProducts]) }}
                    </x-filament::button>
                @else
                    <x-filament::button color="gray" disabled icon="heroicon-o-lock-closed">
                        {{ __('Limit Reached (:limit/:limit)', ['limit' => $productLimit]) }}
                    </x-filament::button>
                @endif

                <x-filament::button tag="a" href="{{ url('/factory/my-subscription') }}" color="gray" outlined icon="heroicon-o-sparkles">
                    {{ __('Subscription & Packages') }}
                </x-filament::button>
            </div>
        </div>

        <!-- Plan Limitations & Live Usage -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6 pt-5 border-t border-gray-100 dark:border-gray-800">
            <!-- 1. Product Listing Limit -->
            <div class="rounded-xl bg-gray-50 dark:bg-gray-800/60 p-4 border border-gray-100 dark:border-gray-800">
                <div class="flex items-center justify-between text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1.5">
                    <span>{{ __('Product Listings Limit') }}</span>
                    <span class="{{ $usedProducts >= $productLimit ? 'text-red-600' : 'text-primary-600' }}">
                        {{ $usedProducts }} / {{ $productLimit }} {{ __('Used') }}
                    </span>
                </div>
                <div class="w-full h-2.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden mb-2">
                    <div class="h-full rounded-full transition-all duration-500 {{ $usedProducts >= $productLimit ? 'bg-red-500' : 'bg-primary-600' }}"
                         style="width: {{ $usagePercent }}%"></div>
                </div>
                <p class="text-xs text-gray-600 dark:text-gray-300 font-medium">
                    @if($remainingProducts > 0)
                        {{ __(':remaining of :limit free product slots remaining', ['remaining' => $remainingProducts, 'limit' => $productLimit]) }}
                    @else
                        {{ __('Maximum of :limit free product listings reached', ['limit' => $productLimit]) }}
                    @endif
                </p>
            </div>

            <!-- 2. Images Per Product Limit -->
            <div class="rounded-xl bg-gray-50 dark:bg-gray-800/60 p-4 border border-gray-100 dark:border-gray-800 flex flex-col justify-between">
                <div class="flex items-center justify-between text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1">
                    <span>{{ __('Images Per Product Limit') }}</span>
                    <span class="text-emerald-600">{{ __('Max :images Photos', ['images' => $maxImages]) }}</span>
                </div>
                <p class="text-lg font-bold text-gray-900 dark:text-white">
                    {{ __(':images Images / Product', ['images' => $maxImages]) }}
                </p>
                <p class="text-xs text-gray-600 dark:text-gray-300 font-medium">
                    {{ __('Each product listing supports up to :images gallery images', ['images' => $maxImages]) }}
                </p>
            </div>

            <!-- 3. Plan Price & Status -->
            <div class="rounded-xl bg-gray-50 dark:bg-gray-800/60 p-4 border border-gray-100 dark:border-gray-800 flex flex-col justify-between">
                <div class="flex items-center justify-between text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1">
                    <span>{{ __('Package Price') }}</span>
                    <span class="text-emerald-600">{{ __('Active') }}</span>
                </div>
                <p class="text-lg font-bold text-emerald-600">
                    {{ $priceLabel }}
                </p>
                <p class="text-xs text-gray-600 dark:text-gray-300 font-medium">
                    {{ __('Includes Direct Buyer Messaging, Showroom & Tenders') }}
                </p>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>

