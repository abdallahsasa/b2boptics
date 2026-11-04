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
        $canCreateMore = $usedProducts < $productLimit;
    @endphp

    <style>
        .ob-sub-wrap { display: flex; flex-direction: column; gap: 28px; }
        .ob-sub-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 28px;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
        }
        .dark .ob-sub-card { background: #111827; border-color: #1f2937; }
        .ob-sub-header {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding-bottom: 22px;
            border-bottom: 1px solid #f1f5f9;
        }
        .dark .ob-sub-header { border-bottom-color: #1f2937; }
        .ob-sub-badges { display: flex; flex-wrap: wrap; align-items: center; gap: 10px; margin-bottom: 10px; }
        .ob-badge-green {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
        }
        .ob-badge-blue {
            display: inline-flex;
            align-items: center;
            padding: 5px 12px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }
        .ob-sub-title { font-size: 24px; font-weight: 800; color: #0f172a; margin: 0; line-height: 1.25; }
        .dark .ob-sub-title { color: #f8fafc; }
        .ob-sub-desc { font-size: 14px; color: #64748b; margin: 6px 0 0 0; }
        .dark .ob-sub-desc { color: #94a3b8; }
        .ob-limits-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            padding-top: 22px;
        }
        .ob-limit-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 20px;
        }
        .dark .ob-limit-box { background: #1f2937; border-color: #374151; }
        .ob-limit-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 13px;
            font-weight: 700;
            color: #334155;
        }
        .dark .ob-limit-top { color: #e2e8f0; }
        .ob-progress-track {
            width: 100%;
            height: 10px;
            background: #e2e8f0;
            border-radius: 9999px;
            overflow: hidden;
            margin-bottom: 10px;
        }
        .dark .ob-progress-track { background: #374151; }
        .ob-progress-fill {
            height: 100%;
            border-radius: 9999px;
            background: linear-gradient(90deg, #2254c5, #3b82f6);
            transition: width 0.4s ease;
        }
        .ob-progress-fill.ob-danger { background: #ef4444; }
        .ob-pkg-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 24px;
        }
        .ob-pkg-card {
            background: #ffffff;
            border: 2px solid #2254c5;
            border-radius: 20px;
            padding: 28px;
            box-shadow: 0 10px 25px rgba(34, 84, 197, 0.08);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            max-width: 520px;
        }
        .dark .ob-pkg-card { background: #111827; border-color: #3b82f6; }
        .ob-pkg-price-row {
            display: flex;
            align-items: baseline;
            gap: 10px;
            margin: 14px 0 18px 0;
            padding-bottom: 18px;
            border-bottom: 1px solid #f1f5f9;
        }
        .dark .ob-pkg-price-row { border-bottom-color: #1f2937; }
        .ob-pkg-price { font-size: 36px; font-weight: 900; color: #059669; line-height: 1; }
        .ob-pkg-quotas {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 20px;
        }
        .ob-pkg-quota-item {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px;
            text-align: center;
        }
        .dark .ob-pkg-quota-item { background: #1f2937; border-color: #374151; }
        .ob-pkg-quota-num { font-size: 24px; font-weight: 900; color: #2254c5; line-height: 1.1; }
        .dark .ob-pkg-quota-num { color: #60a5fa; }
        .ob-pkg-quota-lbl { font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748b; margin-top: 4px; }
        .ob-feature-list { list-style: none; padding: 0; margin: 0 0 24px 0; display: flex; flex-direction: column; gap: 11px; }
        .ob-feature-item { display: flex; align-items: flex-start; gap: 10px; font-size: 13.5px; font-weight: 600; color: #334155; }
        .dark .ob-feature-item { color: #cbd5e1; }
        .ob-check-icon {
            width: 20px;
            height: 20px;
            border-radius: 9999px;
            background: #d1fae5;
            color: #059669;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 12px;
            font-weight: 900;
            margin-top: 1px;
        }
    </style>

    <div class="ob-sub-wrap">
        <!-- Current Active Plan & Limitations Summary -->
        <div class="ob-sub-card">
            <div class="ob-sub-header">
                <div>
                    <div class="ob-sub-badges">
                        <span class="ob-badge-green">● {{ __('Current Active Package') }}</span>
                        <span class="ob-badge-blue">{{ $priceLabel }}</span>
                    </div>
                    <h2 class="ob-sub-title">{{ $planLabel }}</h2>
                    <p class="ob-sub-desc">
                        {{ __('Your factory account includes :limit free product listings and up to :images images per product.', [
                            'limit' => $productLimit,
                            'images' => $maxImages,
                        ]) }}
                    </p>
                </div>

                <div>
                    @if($canCreateMore)
                        <x-filament::button tag="a" href="{{ url('/factory/products/create') }}" icon="heroicon-o-plus" size="lg">
                            {{ __('Add New Product (:remaining left)', ['remaining' => $remainingProducts]) }}
                        </x-filament::button>
                    @else
                        <x-filament::button color="gray" disabled icon="heroicon-o-lock-closed" size="lg">
                            {{ __('Limit Reached (:limit/:limit)', ['limit' => $productLimit]) }}
                        </x-filament::button>
                    @endif
                </div>
            </div>

            <!-- Plan Limitations & Live Usage -->
            <div class="ob-limits-grid">
                <div class="ob-limit-box">
                    <div class="ob-limit-top">
                        <span>{{ __('Product Listings Limitation') }}</span>
                        <span style="color: {{ $usedProducts >= $productLimit ? '#dc2626' : '#2254c5' }};">
                            {{ $usedProducts }} / {{ $productLimit }} {{ __('Products Used') }}
                        </span>
                    </div>
                    <div class="ob-progress-track">
                        <div class="ob-progress-fill {{ $usedProducts >= $productLimit ? 'ob-danger' : '' }}"
                             style="width: {{ max($usagePercent, 4) }}%"></div>
                    </div>
                    <div style="font-size: 12.5px; color: #64748b;">
                        @if($remainingProducts > 0)
                            {{ __('You can publish :remaining more product listings for free on your current package.', ['remaining' => $remainingProducts]) }}
                        @else
                            {{ __('You have reached the maximum of :limit free product listings on your current package.', ['limit' => $productLimit]) }}
                        @endif
                    </div>
                </div>

                <div class="ob-limit-box">
                    <div class="ob-limit-top">
                        <span>{{ __('Product Photo Gallery Limitation') }}</span>
                        <span style="color: #059669;">
                            {{ __('Max :images Images / Product', ['images' => $maxImages]) }}
                        </span>
                    </div>
                    <div style="font-size: 18px; font-weight: 800; color: #0f172a; margin-bottom: 6px;">
                        {{ __('Up to :images High-Resolution Photos per Product', ['images' => $maxImages]) }}
                    </div>
                    <div style="font-size: 12.5px; color: #64748b; line-height: 1.5;">
                        {{ __('Each of your :limit product listings supports up to :images high-resolution photos with drag-and-drop gallery ordering.', [
                            'limit' => $productLimit,
                            'images' => $maxImages,
                        ]) }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Subscription Packages Grid -->
        <div>
            <div style="margin-bottom: 16px;">
                <h3 style="font-size: 18px; font-weight: 800; color: #0f172a; margin: 0 0 4px 0;">
                    {{ __('Available Subscription Packages') }}
                </h3>
                <p style="font-size: 13.5px; color: #64748b; margin: 0;">
                    {{ __('Choose the subscription package for your factory showroom and product catalog.') }}
                </p>
            </div>

            <div class="ob-pkg-grid">
                @foreach($packages as $pkg)
                    @php
                        $isCurrent = ($activePackage && (int) $activePackage->id === (int) $pkg->id)
                            || (!$activePackage && $pkg->is_default);
                        $features = is_array($pkg->features) && count($pkg->features) > 0
                            ? $pkg->features
                            : [
                                __('Up to :limit Product Listings for Free', ['limit' => $pkg->product_limit]),
                                __('Up to :images High-Resolution Images per Product', ['images' => $pkg->max_images_per_product]),
                                __('Direct Buyer Messaging & Email Notifications'),
                                __('Verified Factory Showroom Profile (Video & 360 VR)'),
                                __('Access to Buyer Tenders & Stock Deals'),
                                __('Multi-Language Catalog Support (9 Languages)'),
                            ];
                    @endphp

                    <div class="ob-pkg-card">
                        <div>
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 14px;">
                                <span class="ob-badge-green">
                                    {{ $pkg->isFree() ? __('100% Free Package') : strtoupper($pkg->billing_cycle) }}
                                </span>
                                @if($isCurrent)
                                    <span class="ob-badge-blue">✓ {{ __('Current Plan') }}</span>
                                @endif
                            </div>

                            <h4 style="font-size: 22px; font-weight: 800; color: #0f172a; margin: 0;">
                                {{ __($pkg->name) }}
                            </h4>

                            <div class="ob-pkg-price-row">
                                @if($pkg->isFree())
                                    <span class="ob-pkg-price">{{ __('FREE') }}</span>
                                    <span style="font-size: 14px; font-weight: 700; color: #64748b;">($0.00)</span>
                                @else
                                    <span class="ob-pkg-price" style="color: #0f172a;">{{ $pkg->currency }} {{ number_format((float) $pkg->price, 2) }}</span>
                                    <span style="font-size: 14px; font-weight: 700; color: #64748b;">/ {{ $pkg->billing_cycle }}</span>
                                @endif
                            </div>

                            @if($pkg->description)
                                <p style="font-size: 13.5px; color: #64748b; margin: 0 0 18px 0; line-height: 1.55;">
                                    {{ __($pkg->description) }}
                                </p>
                            @endif

                            <!-- Package Quota Badges -->
                            <div class="ob-pkg-quotas">
                                <div class="ob-pkg-quota-item">
                                    <div class="ob-pkg-quota-num">{{ $pkg->product_limit }}</div>
                                    <div class="ob-pkg-quota-lbl">{{ __('Products Limit') }}</div>
                                </div>
                                <div class="ob-pkg-quota-item">
                                    <div class="ob-pkg-quota-num">{{ $pkg->max_images_per_product }}</div>
                                    <div class="ob-pkg-quota-lbl">{{ __('Images / Product') }}</div>
                                </div>
                            </div>

                            <!-- Feature Checklist -->
                            <ul class="ob-feature-list">
                                @foreach($features as $feature)
                                    <li class="ob-feature-item">
                                        <span class="ob-check-icon">✓</span>
                                        <span>{{ __($feature) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div>
                            @if($isCurrent)
                                <x-filament::button color="success" disabled class="w-full" size="lg" icon="heroicon-m-check-circle">
                                    {{ __('Active on Your Account') }}
                                </x-filament::button>
                            @else
                                <x-filament::button wire:click="selectPackage({{ $pkg->id }})" class="w-full" size="lg" icon="heroicon-o-bolt">
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
