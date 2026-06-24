@extends('layouts.app')

@section('title', __('Factory Directory'))

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- Hero Section -->
        <div class="mb-12">
            <h1 class="text-4xl lg:text-5xl font-bold text-slate-900 leading-tight">
                {{ __('Discover Verified') }} <span class="text-primary-600">{{ __('Manufacturers') }}</span>
            </h1>
            <p class="text-slate-500 mt-4 text-lg max-w-2xl">
                {{ __('Browse our curated directory of top-tier optical factories and suppliers from around the world. Connect directly with the source.') }}
            </p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Filters -->
            <aside class="w-full lg:w-64 flex-shrink-0">
                <form action="{{ route('factories.index') }}" method="GET" class="space-y-8 sticky top-24">
                    <!-- Search -->
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">{{ __('Search') }}</h3>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search factories...') }}" 
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
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">{{ __('Country') }}</h3>
                        <select name="country" onchange="this.form.submit()" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-primary-600">
                            <option value="">{{ __('Any Country') }}</option>
                            @foreach($countries as $country)
                            <option value="{{ $country->code }}" {{ request('country') == $country->code ? 'selected' : '' }}>{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </aside>

            <!-- Factory Grid Container -->
            <div class="flex-1">
                <!-- Top Info Bar -->
                <div class="flex flex-col sm:flex-row justify-between items-center mb-8 gap-4">
                    <p class="text-sm text-slate-500">{!! __('Showing :first - :last of :total factories', ['first' => '<span class="font-bold text-slate-900">' . ($factories->firstItem() ?? 0) . '</span>', 'last' => '<span class="font-bold text-slate-900">' . ($factories->lastItem() ?? 0) . '</span>', 'total' => '<span class="font-bold text-slate-900">' . $factories->total() . '</span>']) !!}</p>
                    
                    <div class="flex items-center gap-4 w-full sm:w-auto">
                        <label class="text-sm text-slate-500 whitespace-nowrap">{{ __('Sort by:') }}</label>
                        <select name="sort" onchange="window.location.href = '{{ request()->fullUrlWithQuery(['sort' => '']) }}'.replace('sort=', 'sort=' + this.value)" class="bg-white border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-primary-600 w-full sm:w-auto">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ __('Newest') }}</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>{{ __('Popular') }}</option>
                            <option value="most_deals" {{ request('sort') == 'most_deals' ? 'selected' : '' }}>{{ __('Most Deals') }}</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>{{ __('Rating') }}</option>
                            <option value="az" {{ request('sort') == 'az' ? 'selected' : '' }}>{{ __('A to Z') }}</option>
                        </select>
                    </div>
                </div>

                <!-- Factory Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                    @forelse($factories as $factory)
                        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-xl transition-shadow duration-300 overflow-hidden flex flex-col relative group">
                            <!-- Cover Image or Logo Placeholder -->
                            <div class="h-40 bg-slate-50 border-b border-slate-100 relative overflow-hidden">
                                @if($factory->logo_url)
                                    <img src="{{ $factory->logo_url }}" alt="{{ $factory->official_name }}"
                                        class="w-full h-full object-cover opacity-90 group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <!-- Abstract placeholder if no logo -->
                                    <div class="w-full h-full bg-gradient-to-br from-primary-50 to-primary-100 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-primary-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                @endif

                                <!-- Country Badge -->
                                @if($factory->country)
                                    <div class="absolute top-4 left-4 bg-white/90 backdrop-blur px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest text-slate-700 shadow-sm border border-white flex items-center gap-2">
                                        <span class="w-4 h-4 rounded-full overflow-hidden inline-block border border-slate-100">
                                            <img src="https://flagcdn.com/w40/{{ strtolower($factory->country->code ?? 'us') }}.png"
                                                alt="{{ $factory->country->name }}" class="w-full h-full object-cover">
                                        </span>
                                        {{ $factory->country->name }}
                                    </div>
                                @endif
                            </div>

                            <div class="p-6 flex-1 flex flex-col">
                                <!-- Category Badge -->
                                @if($factory->category)
                                    <div class="mb-4">
                                        <span class="px-3 py-1 bg-primary-50 text-primary-700 text-[10px] font-bold rounded-full uppercase tracking-wider inline-block">
                                            {{ $factory->category->getTranslation('name', app()->getLocale()) }}
                                        </span>
                                    </div>
                                @endif

                                <h3 class="text-xl font-bold text-slate-900 mb-2 group-hover:text-primary-600 transition-colors line-clamp-1">
                                    {{ $factory->official_name }}
                                </h3>

                                @if($factory->description)
                                    <p class="text-slate-500 text-sm line-clamp-3 mb-6 flex-1">
                                        {{ $factory->getTranslation('description', app()->getLocale()) ?? $factory->description }}
                                    </p>
                                @else
                                    <div class="flex-1 mb-6"></div>
                                @endif

                                <div class="pt-4 border-t border-slate-100 mt-auto">
                                    <a href="{{ route('factories.show', $factory) }}"
                                        class="flex items-center justify-between group/btn text-slate-900 hover:text-primary-600 transition-colors">
                                        <span class="text-xs font-bold uppercase tracking-wider">{{ __('View Profile') }}</span>
                                        <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center group-hover/btn:bg-primary-50 group-hover/btn:text-primary-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                            </svg>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-20 bg-white rounded-[3rem] border border-slate-100 text-center">
                            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 mb-2">{{ __('No Manufacturers Found') }}</h3>
                            <p class="text-slate-500">{{ __('There are currently no approved factories listed in the directory.') }}</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-12">
                    {{ $factories->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection