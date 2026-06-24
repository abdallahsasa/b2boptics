@extends('layouts.app')

@section('title', __('Products'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Filters -->
        <aside class="w-full lg:w-64 flex-shrink-0">
            <form action="{{ route('marketplace.index') }}" method="GET" class="space-y-8 sticky top-24">
                <!-- Search -->
                <div>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">{{ __('Search') }}</h3>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search lenses, frames, machines...') }}" 
                            class="w-full pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-600 focus:border-transparent text-sm">
                        <svg class="w-5 h-5 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                </div>

                <!-- Categories -->
                <div>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">{{ __('Categories') }}</h3>
                    <div class="space-y-2">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="radio" name="category" value="" {{ !request('category') ? 'checked' : '' }} onchange="this.form.submit()" class="w-4 h-4 text-primary-600 border-slate-300 focus:ring-primary-500">
                            <span class="text-sm text-slate-600 group-hover:text-primary-600">{{ __('All Categories') }}</span>
                        </label>
                        @foreach($categories as $category)
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="radio" name="category" value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'checked' : '' }} onchange="this.form.submit()" class="w-4 h-4 text-primary-600 border-slate-300 focus:ring-primary-500">
                            <span class="text-sm text-slate-600 group-hover:text-primary-600">{{ $category->getTranslation('name', app()->getLocale()) }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Origin -->
                <div>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">{{ __('Country of Origin') }}</h3>
                    <select name="country" onchange="this.form.submit()" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-primary-600">
                        <option value="">{{ __('Any Country') }}</option>
                        @foreach($countries as $country)
                        <option value="{{ $country->code }}" {{ request('country') == $country->code ? 'selected' : '' }}>{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Price Range -->
                <div>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">{{ __('Price Range') }}</h3>
                    <div class="flex items-center gap-2">
                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="{{ __('Min') }}" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm">
                        <span class="text-slate-400">-</span>
                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="{{ __('Max') }}" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm">
                    </div>
                    <button type="submit" class="w-full mt-4 py-2 bg-slate-900 text-white text-xs font-bold rounded-xl hover:bg-black transition-all">{{ __('Apply Price') }}</button>
                </div>
            </form>
        </aside>

        <!-- Product Grid -->
        <div class="flex-1">
            <!-- Top Bar -->
            <div class="flex flex-col sm:flex-row justify-between items-center mb-8 gap-4">
                <p class="text-sm text-slate-500">{!! __('Showing :first - :last of :total products', ['first' => '<span class="font-bold text-slate-900">' . ($products->firstItem() ?? 0) . '</span>', 'last' => '<span class="font-bold text-slate-900">' . ($products->lastItem() ?? 0) . '</span>', 'total' => '<span class="font-bold text-slate-900">' . $products->total() . '</span>']) !!}</p>
                
                <div class="flex items-center gap-4">
                    <label class="text-sm text-slate-500">{{ __('Sort by:') }}</label>
                    <select name="sort" onchange="window.location.href = '{{ request()->fullUrlWithQuery(['sort' => '']) }}'.replace('sort=', 'sort=' + this.value)" class="bg-white border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-primary-600">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ __('Newest') }}</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>{{ __('Price: Low to High') }}</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>{{ __('Price: High to Low') }}</option>
                    </select>
                </div>
            </div>

            <!-- Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($products as $product)
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden card-hover group">
                    <a href="{{ route('marketplace.product.show', $product) }}" class="block aspect-square overflow-hidden relative bg-slate-100">
                        <img src="{{ $product->getFirstMediaUrl('images') ?: 'https://images.unsplash.com/photo-1574258495973-f010dfbb5371?q=80&w=600&auto=format&fit=crop' }}" 
                            alt="{{ $product->getTranslation('name', app()->getLocale()) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1 bg-white/90 backdrop-blur text-[10px] font-bold text-primary-900 rounded-full uppercase tracking-wider shadow-sm">{{ $product->category->getTranslation('name', app()->getLocale()) ?? 'Optics' }}</span>
                        </div>
                    </a>
                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ $product->factory->country->name ?? 'Global' }}</span>
                            @if($product->factory)
                                <span class="w-1 h-1 bg-slate-200 rounded-full"></span>
                                <a href="{{ route('factories.show', $product->factory) }}" class="text-xs font-medium text-primary-600 hover:underline">{{ $product->factory->official_name }}</a>
                            @endif
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-1 group-hover:text-primary-600 transition-colors">
                            <a href="{{ route('marketplace.product.show', $product) }}">{{ $product->getTranslation('name', app()->getLocale()) }}</a>
                        </h3>
                        <p class="text-slate-500 text-sm line-clamp-1 mb-4">{{ strip_tags($product->getTranslation('description', app()->getLocale())) }}</p>
                        
                        <div class="flex items-center justify-between pt-4 border-t border-slate-50">
                            <div>
                                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">{{ __('Starting at') }}</p>
                                <p class="text-lg font-bold text-slate-900">{{ $product->currency }} {{ number_format($product->starting_price, 2) }}</p>
                            </div>
                            <a href="{{ route('marketplace.product.show', $product) }}" class="w-10 h-10 bg-primary-50 text-primary-600 rounded-xl flex items-center justify-center hover:bg-primary-600 hover:text-white transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-20 text-center">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900">{{ __('No products found') }}</h3>
                    <p class="text-slate-500 mt-2">{{ __('Try adjusting your filters or search keywords.') }}</p>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
