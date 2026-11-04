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
        $canCreateMore = $usedProducts < $productLimit;
    @endphp

    <style>
        .ob-dash-sub {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 24px 28px;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
        }
        .dark .ob-dash-sub { background: #111827; border-color: #1f2937; }
        .ob-dash-top {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding-bottom: 20px;
            border-bottom: 1px solid #f1f5f9;
        }
        .dark .ob-dash-top { border-bottom-color: #1f2937; }
        .ob-dash-pills { display: flex; flex-wrap: wrap; align-items: center; gap: 8px; margin-bottom: 8px; }
        .ob-pill-emerald {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
        }
        .ob-pill-blue {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }
        .ob-dash-actions { display: flex; flex-wrap: wrap; align-items: center; gap: 10px; }
        .ob-dash-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 16px;
            padding-top: 20px;
        }
        .ob-dash-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 16px 18px;
        }
        .dark .ob-dash-box { background: #1f2937; border-color: #374151; }
        .ob-dash-box-hdr {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            margin-bottom: 8px;
        }
        .ob-dash-bar-bg {
            width: 100%;
            height: 9px;
            background: #e2e8f0;
            border-radius: 9999px;
            overflow: hidden;
            margin-bottom: 8px;
        }
        .dark .ob-dash-bar-bg { background: #374151; }
        .ob-dash-bar-fg {
            height: 100%;
            border-radius: 9999px;
            background: linear-gradient(90deg, #2254c5, #3b82f6);
        }
    </style>

    <div class="ob-dash-sub">
        <div class="ob-dash-top">
            <div>
                <div class="ob-dash-pills">
                    <span class="ob-pill-emerald">● {{ __('Active Subscription Plan') }}</span>
                    <span class="ob-pill-blue">{{ $priceLabel }}</span>
                </div>
                <h3 style="font-size: 20px; font-weight: 800; color: #0f172a; margin: 0;">
                    {{ $planName }}
                </h3>
                <p style="font-size: 13.5px; color: #64748b; margin: 4px 0 0 0;">
                    {{ $package?->description ?? __('Free factory membership with up to :limit product listings and up to :images images per product.', ['limit' => $productLimit, 'images' => $maxImages]) }}
                </p>
            </div>

            <div class="ob-dash-actions">
                @if($canCreateMore)
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

        <div class="ob-dash-grid">
            <!-- 1. Product Listing Limit -->
            <div class="ob-dash-box">
                <div class="ob-dash-box-hdr">
                    <span>{{ __('Product Listings Limit') }}</span>
                    <span style="color: {{ $usedProducts >= $productLimit ? '#dc2626' : '#2254c5' }};">
                        {{ $usedProducts }} / {{ $productLimit }} {{ __('Used') }}
                    </span>
                </div>
                <div class="ob-dash-bar-bg">
                    <div class="ob-dash-bar-fg" style="width: {{ max($usagePercent, 4) }}%; {{ $usedProducts >= $productLimit ? 'background:#ef4444;' : '' }}"></div>
                </div>
                <div style="font-size: 12px; font-weight: 600; color: #475569;">
                    @if($remainingProducts > 0)
                        {{ __(':remaining of :limit free product slots remaining', ['remaining' => $remainingProducts, 'limit' => $productLimit]) }}
                    @else
                        {{ __('Maximum of :limit free product listings reached', ['limit' => $productLimit]) }}
                    @endif
                </div>
            </div>

            <!-- 2. Images Per Product Limit -->
            <div class="ob-dash-box">
                <div class="ob-dash-box-hdr">
                    <span>{{ __('Images Per Product Limit') }}</span>
                    <span style="color: #059669;">{{ __('Max :images Photos', ['images' => $maxImages]) }}</span>
                </div>
                <div style="font-size: 18px; font-weight: 800; color: #0f172a; margin-bottom: 4px;">
                    {{ __(':images Images / Product', ['images' => $maxImages]) }}
                </div>
                <div style="font-size: 12px; font-weight: 600; color: #475569;">
                    {{ __('Each product listing supports up to :images gallery images', ['images' => $maxImages]) }}
                </div>
            </div>

            <!-- 3. Plan Price & Status -->
            <div class="ob-dash-box">
                <div class="ob-dash-box-hdr">
                    <span>{{ __('Package Price') }}</span>
                    <span style="color: #059669;">{{ __('Active') }}</span>
                </div>
                <div style="font-size: 18px; font-weight: 800; color: #059669; margin-bottom: 4px;">
                    {{ $priceLabel }}
                </div>
                <div style="font-size: 12px; font-weight: 600; color: #475569;">
                    {{ __('Includes Direct Buyer Messaging, Showroom & Tenders') }}
                </div>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
