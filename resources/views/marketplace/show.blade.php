@extends('layouts.app')

@php
    $locale = app()->getLocale();
    $productName = $product->getTranslation('name', $locale);
    $categoryName = $product->category?->getTranslation('name', $locale) ?? __('Optical Product');
    $factoryName = $product->factory?->official_name ?? 'OpticB2B';
    $rawDesc = trim(preg_replace('/\s+/', ' ', strip_tags($product->getTranslation('description', $locale) ?? '')));
    $seoDescription = \Illuminate\Support\Str::limit(
        $rawDesc ?: __('Source :product (:category) wholesale directly from :factory on OpticB2B.', ['product' => $productName, 'category' => $categoryName, 'factory' => $factoryName]),
        160
    );
    $productImage = $product->getFirstMediaUrl('images') ?: 'https://images.unsplash.com/photo-1574258495973-f010dfbb5371?q=80&w=1200&auto=format&fit=crop';
@endphp

@section('title', $productName)
@section('meta_title', $productName . ' - ' . $categoryName . ' | ' . $factoryName . ' | OpticB2B')
@section('meta_description', $seoDescription)
@section('meta_keywords', implode(', ', array_filter([$productName, $categoryName, $factoryName, $product->factory?->country?->name, __('wholesale optical supply'), 'OpticB2B'])))
@section('og_type', 'product')
@section('og_image', $productImage)

@push('meta_extra')
    @if($product->starting_price)
        <meta property="product:price:amount" content="{{ number_format((float) $product->starting_price, 2, '.', '') }}">
        <meta property="product:price:currency" content="{{ $product->currency ?: 'USD' }}">
    @endif
@endpush

@push('structured_data')
@php
    $productSchema = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    [
                        '@type' => 'ListItem',
                        'position' => 1,
                        'name' => __('Home'),
                        'item' => route('home'),
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 2,
                        'name' => __('Products'),
                        'item' => route('marketplace.index'),
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 3,
                        'name' => $productName,
                        'item' => route('marketplace.product.show', $product),
                    ],
                ],
            ],
            [
                '@type' => 'Product',
                'name' => $productName,
                'description' => $seoDescription,
                'image' => [$productImage],
                'category' => $categoryName,
                'brand' => [
                    '@type' => 'Brand',
                    'name' => $factoryName,
                ],
                'manufacturer' => [
                    '@type' => 'Organization',
                    'name' => $factoryName,
                    'url' => $product->factory ? route('factories.show', $product->factory) : url('/'),
                ],
                'offers' => [
                    '@type' => 'Offer',
                    'url' => route('marketplace.product.show', $product),
                    'priceCurrency' => $product->currency ?: 'USD',
                    'price' => number_format((float) ($product->starting_price ?? 0), 2, '.', ''),
                    'availability' => 'https://schema.org/InStock',
                    'seller' => [
                        '@type' => 'Organization',
                        'name' => $factoryName,
                    ],
                ],
            ],
        ],
    ];
@endphp
<script type="application/ld+json">{!! json_encode($productSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@section('content')
    <div x-data="{ directInquiryOpen: false }" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- Breadcrumbs -->
        <nav class="flex text-sm text-slate-400 mb-8 gap-2">
            <a href="{{ route('home') }}" class="hover:text-primary-600">{{ __('Home') }}</a>
            <span>/</span>
            <a href="{{ route('marketplace.index') }}" class="hover:text-primary-600">{{ __('Products') }}</a>
            <span>/</span>
            <span class="text-slate-900 font-medium">{{ $product->getTranslation('name', app()->getLocale()) }}</span>
        </nav>

        @if(session('inquiry_success'))
            <div class="mb-8 bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-2xl flex items-center gap-3 shadow-sm">
                <svg class="w-6 h-6 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span class="font-semibold text-sm">{{ session('inquiry_success') }}</span>
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-12">
            <!-- Image Gallery -->
            <div class="flex-1">
                <div class="bg-white rounded-[2rem] overflow-hidden border border-slate-100 shadow-sm p-4">
                    <div class="aspect-square bg-slate-50 rounded-[1.5rem] overflow-hidden">
                        <img src="{{ $product->getFirstMediaUrl('images') ?: 'https://images.unsplash.com/photo-1574258495973-f010dfbb5371?q=80&w=1200&auto=format&fit=crop' }}"
                            alt="{{ $product->getTranslation('name', app()->getLocale()) }}"
                            class="w-full h-full object-cover">
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="lg:w-1/2">
                <div class="sticky top-24">
                    <div class="flex items-center gap-3 mb-6">
                        <span
                            class="px-4 py-1.5 bg-primary-50 text-primary-700 text-xs font-bold rounded-full uppercase tracking-wider">{{ $product->category->getTranslation('name', app()->getLocale()) }}</span>
                        <span class="flex items-center gap-1.5 text-xs font-bold text-slate-400 uppercase tracking-widest">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $product->factory->country->name ?? 'Global' }}
                        </span>
                    </div>

                    <h1 class="text-4xl lg:text-5xl font-bold text-slate-900 mb-4">
                        {{ $product->getTranslation('name', app()->getLocale()) }}</h1>

                    @if($product->factory)
                        <div class="flex items-center gap-4 mb-8 p-6 bg-white rounded-3xl border border-slate-100 shadow-sm">
                            <div class="w-14 h-14 bg-slate-100 rounded-2xl overflow-hidden flex-shrink-0">
                                <img src="{{ $product->factory->logo_url ?: 'https://ui-avatars.com/api/?name=' . urlencode($product->factory->official_name) }}"
                                    class="w-full h-full object-contain p-1">
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest mb-0.5">
                                    {{ __('Manufactured by') }}</p>
                                <a href="{{ route('factories.show', $product->factory) }}"
                                    class="text-lg font-bold text-slate-900 hover:text-primary-600 transition-colors">{{ $product->factory->official_name }}</a>
                            </div>
                            <div class="ml-auto">
                                <div class="flex items-center gap-1 text-amber-400">
                                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <span class="text-sm font-bold text-slate-900">4.9</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="mb-10">
                        <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest mb-2">{{ __('Pricing') }}
                        </p>
                        <div class="flex items-baseline gap-2">
                            <span class="text-4xl font-bold text-slate-900">{{ $product->currency }}
                                {{ number_format($product->starting_price, 2) }}</span>
                            <span class="text-slate-400 font-medium">{{ __('starting price') }}</span>
                        </div>
                    </div>

                    <div class="prose prose-slate prose-sm mb-12">
                        <p class="text-slate-600 leading-relaxed">
                            {!! nl2br(e($product->getTranslation('description', app()->getLocale()))) !!}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-10">
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest mb-1">
                                {{ __('Subcategory') }}</p>
                            <p class="font-bold text-slate-900">
                                {{ $product->subcategory->getTranslation('name', app()->getLocale()) ?? 'N/A' }}</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest mb-1">
                                {{ __('Origin') }}</p>
                            <p class="font-bold text-slate-900">{{ $product->factory->country->name ?? 'Global' }}</p>
                        </div>
                    </div>

                    <button type="button" @click="directInquiryOpen = true"
                        class="w-full py-5 bg-primary-600 text-white rounded-3xl font-bold text-lg shadow-xl shadow-primary-600/30 hover:bg-primary-700 hover:scale-[1.01] transition-all flex items-center justify-center gap-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        {{ __('Contact Factory Directly') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Specifications / Basic Info Section -->
        @if(!empty($product->specifications) && count($product->specifications) > 0)
            @php
                $rawSpecs = $product->specifications;
                $preferredKeys = [
                    'Transmittance', 'Material', 'Certification', 'Coating', 'Coating Color',
                    'Abbe Value', 'Specification', 'Origin', 'Production Capacity',
                    'Shape', 'Lenses Color', 'Customized', 'Power Range', 'Specific Gravity',
                    'Transport Package', 'Trademark', 'HS Code'
                ];
                $orderedSpecs = [];
                foreach ($preferredKeys as $pk) {
                    if (array_key_exists($pk, $rawSpecs)) {
                        $orderedSpecs[$pk] = $rawSpecs[$pk];
                    }
                }
                foreach ($rawSpecs as $rk => $rv) {
                    if (!array_key_exists($rk, $orderedSpecs)) {
                        $orderedSpecs[$rk] = $rv;
                    }
                }
                $specs = $orderedSpecs;
                $keys = array_keys($specs);
                $total = count($keys);
                $half = (int) ceil($total / 2);
                $leftSpecs = array_slice($specs, 0, $half, true);
                $rightSpecs = array_slice($specs, $half, null, true);
            @endphp
            <div class="mt-16 bg-white rounded-3xl border border-slate-100 shadow-sm p-6 sm:p-10">
                <div class="border-b border-slate-100 pb-5 mb-8 flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">{{ __('Basic Info.') }}</h2>
                        <p class="text-sm text-slate-400 mt-1">{{ __('Detailed technical specifications and manufacturing standards') }}</p>
                    </div>
                    <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-primary-50 text-primary-700 border border-primary-100">
                        <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ __('Factory Certified') }}
                    </span>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">
                    <!-- Left Column Table -->
                    <div class="overflow-hidden rounded-2xl border border-slate-200/80 shadow-sm">
                        <table class="w-full text-sm text-left">
                            <tbody class="divide-y divide-slate-200/80">
                                @foreach($leftSpecs as $specKey => $specVal)
                                    <tr class="divide-x divide-slate-200/80 hover:bg-slate-50/50 transition-colors">
                                        <th scope="row" class="w-5/12 sm:w-2/5 bg-slate-50/90 px-4 py-3.5 font-medium text-slate-700 text-xs sm:text-sm">
                                            {{ __($specKey) }}
                                        </th>
                                        <td class="w-7/12 sm:w-3/5 bg-white px-4 py-3.5 text-slate-900 text-xs sm:text-sm font-semibold">
                                            {{ $specVal }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Right Column Table -->
                    <div class="overflow-hidden rounded-2xl border border-slate-200/80 shadow-sm">
                        <table class="w-full text-sm text-left">
                            <tbody class="divide-y divide-slate-200/80">
                                @foreach($rightSpecs as $specKey => $specVal)
                                    <tr class="divide-x divide-slate-200/80 hover:bg-slate-50/50 transition-colors">
                                        <th scope="row" class="w-5/12 sm:w-2/5 bg-slate-50/90 px-4 py-3.5 font-medium text-slate-700 text-xs sm:text-sm">
                                            {{ __($specKey) }}
                                        </th>
                                        <td class="w-7/12 sm:w-3/5 bg-white px-4 py-3.5 text-slate-900 text-xs sm:text-sm font-semibold">
                                            {{ $specVal }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Product Description Section below Basic Info -->
                <div class="mt-12 pt-8 border-t border-slate-100">
                    <h3 class="text-xl sm:text-2xl font-bold text-slate-900 mb-4">{{ __('Product Description') }}</h3>
                    <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed text-sm sm:text-base">
                        @if($product->description)
                            <p>{!! nl2br(e($product->getTranslation('description', app()->getLocale()))) !!}</p>
                        @else
                            <p class="text-slate-400 italic">{{ __('High quality optical product manufactured under strict ISO quality standards.') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <!-- Fallback: Display Product Description if no specifications defined -->
            @if($product->description)
                <div class="mt-16 bg-white rounded-3xl border border-slate-100 shadow-sm p-6 sm:p-10">
                    <h3 class="text-xl sm:text-2xl font-bold text-slate-900 mb-4">{{ __('Product Description') }}</h3>
                    <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed text-sm sm:text-base">
                        <p>{!! nl2br(e($product->getTranslation('description', app()->getLocale()))) !!}</p>
                    </div>
                </div>
            @endif
        @endif

        <!-- Similar Products -->
        @if($similarProducts->count())
            <div class="mt-32">
                <h2 class="text-3xl font-bold text-slate-900 mb-10">{{ __('Similar Products') }}</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($similarProducts as $similar)
                        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden card-hover group">
                            <a href="{{ route('marketplace.product.show', $similar) }}"
                                class="block aspect-square overflow-hidden relative bg-slate-100">
                                <img src="{{ $similar->getFirstMediaUrl('images') ?: 'https://images.unsplash.com/photo-1574258495973-f010dfbb5371?q=80&w=600&auto=format&fit=crop' }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </a>
                            <div class="p-6">
                                <h3 class="font-bold text-slate-900 truncate"><a
                                        href="{{ route('marketplace.product.show', $similar) }}">{{ $similar->getTranslation('name', app()->getLocale()) }}</a>
                                </h3>
                                <p class="text-primary-600 font-bold mt-1">{{ $similar->currency }}
                                    {{ number_format($similar->starting_price, 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Direct Factory Inquiry Modal -->
        @if($product->factory)
        <div x-show="directInquiryOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 sm:p-6 bg-slate-950/60 backdrop-blur-sm">
            <div @click.away="directInquiryOpen = false"
                 class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden">
                <div class="px-6 sm:px-8 pt-7 pb-5 border-b border-slate-100 flex items-start justify-between">
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-100 p-1.5 shrink-0">
                            <img src="{{ $product->factory->logo_url ?: 'https://ui-avatars.com/api/?name=' . urlencode($product->factory->official_name) }}"
                                 alt="{{ $product->factory->official_name }}" class="w-full h-full object-contain">
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">{{ __('Message :factory', ['factory' => $product->factory->official_name]) }}</h3>
                            <p class="text-xs text-slate-500">{{ __('Product') }}: {{ $product->getTranslation('name', app()->getLocale()) }}</p>
                        </div>
                    </div>
                    <button type="button" @click="directInquiryOpen = false" class="p-2 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form action="{{ route('factories.inquire', $product->factory) }}" method="POST" class="p-6 sm:p-8 space-y-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="redirect_to_product" value="1">
                    <input type="hidden" name="topic" value="Product Quote Request: {{ $product->getTranslation('name', 'en') }}">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">{{ __('Estimated Quantity') }}</label>
                            <input type="text" name="quantity" placeholder="{{ __('e.g. 500 pcs') }}"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-600 focus:bg-white focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">{{ __('Phone / WhatsApp') }}</label>
                            <input type="text" name="phone" placeholder="+1 555 000 0000"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-600 focus:bg-white focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">{{ __('Company Name') }}</label>
                        <input type="text" name="company_name" placeholder="{{ __('Your Optical Company / Lab') }}"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-600 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">{{ __('Message to Factory') }} *</label>
                        <textarea name="message" rows="4" required minlength="5"
                            placeholder="{{ __('Specify required lens index, coating, frame colors, MOQ, or shipping destination...') }}"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-600 focus:bg-white focus:outline-none"></textarea>
                    </div>

                    <div class="pt-2">
                        @auth
                            <button type="submit"
                                class="w-full py-3.5 bg-primary-600 hover:bg-primary-700 text-white rounded-2xl font-bold text-sm shadow-lg shadow-primary-600/20 transition-all">
                                {{ __('Send Message Directly to Factory') }}
                            </button>
                        @else
                            <button type="button" @click="directInquiryOpen = false; $dispatch('open-auth-modal', { source: 'product-inquiry' })"
                                class="w-full py-3.5 bg-primary-600 hover:bg-primary-700 text-white rounded-2xl font-bold text-sm shadow-lg shadow-primary-600/20 transition-all">
                                {{ __('Log in to Send Message') }}
                            </button>
                        @endauth
                    </div>
                </form>
            </div>
        </div>
        @endif

    </div>
@endsection