@extends('layouts.app')

@section('title', $factory->official_name)

@section('content')
<div x-data="{
    activeTab: 'products',
    searchQuery: '',
    selectedCategory: 'all',
    copiedText: null,
    contactModalOpen: false,
    inquiryTopic: 'Wholesale Price List',
    inquiryMessage: '',

    copyToClipboard(text, label) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text);
        } else {
            const el = document.createElement('textarea');
            el.value = text;
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
        }
        this.copiedText = label;
        setTimeout(() => this.copiedText = null, 2500);
    }
}" class="min-h-screen bg-slate-50/60 pb-20">

    <!-- Toast Notification -->
    <div x-show="copiedText"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-2"
         x-cloak
         class="fixed bottom-6 right-6 z-50 bg-slate-900 text-white text-sm font-semibold px-5 py-3 rounded-2xl shadow-2xl flex items-center gap-3 border border-slate-700/50">
        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
        </svg>
        <span><span x-text="copiedText" class="font-bold"></span> {{ __('copied to clipboard!') }}</span>
    </div>

    <!-- Main Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">

        <!-- Breadcrumbs Navigation -->
        <nav class="flex items-center justify-between text-xs sm:text-sm text-slate-500 mb-6 font-medium">
            <ol class="flex items-center gap-2 overflow-x-auto py-1">
                <li>
                    <a href="{{ route('home') }}" class="hover:text-primary-600 transition-colors">{{ __('Home') }}</a>
                </li>
                <li class="text-slate-300">/</li>
                <li>
                    <a href="{{ route('factories.index') }}" class="hover:text-primary-600 transition-colors">{{ __('Factories') }}</a>
                </li>
                <li class="text-slate-300">/</li>
                <li class="text-slate-900 font-semibold truncate max-w-[200px] sm:max-w-none">
                    {{ $factory->official_name }}
                </li>
            </ol>

            <a href="{{ route('factories.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-primary-600 hover:text-primary-700 bg-white border border-slate-200 hover:border-primary-200 px-3 py-1.5 rounded-xl shadow-sm transition-all shrink-0">
                <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>{{ __('All Factories') }}</span>
            </a>
        </nav>

        <!-- Hero Section -->
        <div class="relative">
            <!-- Cover Banner Container -->
            <div class="h-56 sm:h-72 lg:h-96 rounded-3xl lg:rounded-[2.5rem] overflow-hidden relative shadow-sm border border-slate-200/80 bg-slate-900">
                <img src="{{ $factory->banner_url ?: 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?q=80&w=1600&auto=format&fit=crop' }}"
                    alt="{{ $factory->official_name }}"
                    class="w-full h-full object-cover opacity-90 transition-transform duration-700 hover:scale-105">
                
                <!-- Modern Gradient Overlay for Contrast -->
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/30 to-transparent"></div>

                <!-- Floating Banner Badges (Top Right) -->
                <div class="absolute top-4 right-4 sm:top-6 sm:right-6 flex flex-wrap items-center justify-end gap-2.5 z-10">
                    <!-- Diamond Member Badge -->
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-gradient-to-r from-amber-500 to-amber-600 backdrop-blur-md text-white text-xs font-bold rounded-full shadow-lg shadow-amber-500/20 border border-amber-300/40">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 9l10 13 10-13-10-7zm0 3.3L17.7 9H6.3L12 5.3zm-6.8 5.2h4.5l-3.3 6.9-1.2-6.9zm6.8 8.1l-2.6-6.6h5.2L12 18.6zm2.3-8.1h4.5l-1.2 6.9-3.3-6.9z" />
                        </svg>
                        <span>{{ __('Diamond Member') }}</span>
                        <span class="text-amber-100 font-medium">({{ __('Since :year', ['year' => $factory->member_since ?? '2017']) }})</span>
                    </span>

                    @if($factory->is_verified)
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-emerald-600/90 backdrop-blur-md text-white text-xs font-bold rounded-full shadow-lg shadow-emerald-600/20 border border-emerald-400/40">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            {{ __('Audited Supplier') }}
                        </span>
                    @endif

                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-slate-900/80 backdrop-blur-md text-amber-400 text-xs font-bold rounded-full shadow-sm border border-slate-700/60">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <span class="text-white">{{ number_format($factory->rating_score ?? 5.0, 1) }} / 5.0</span>
                    </span>

                    @if($factory->country)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/90 backdrop-blur-md text-slate-800 text-xs font-semibold rounded-full shadow-sm border border-white">
                            <svg class="w-3.5 h-3.5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $factory->country->name }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Elevated Identity Card -->
            <div class="relative -mt-16 sm:-mt-20 mx-4 sm:mx-8 bg-white rounded-3xl p-6 sm:p-8 shadow-xl shadow-slate-200/60 border border-slate-100 z-20">
                <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                    
                    <!-- Left: Logo & Details -->
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5 sm:gap-6 w-full lg:w-auto">
                        <!-- Factory Logo -->
                        <div class="w-24 h-24 sm:w-32 sm:h-32 lg:w-36 lg:h-36 bg-white p-3 rounded-2xl sm:rounded-3xl shadow-md border-2 border-slate-100 shrink-0 overflow-hidden flex items-center justify-center">
                            <img src="{{ $factory->logo_url ?: 'https://ui-avatars.com/api/?name=' . urlencode($factory->official_name) . '&size=256' }}"
                                alt="{{ $factory->official_name }}"
                                class="w-full h-full object-contain">
                        </div>

                        <!-- Brand Info -->
                        <div class="space-y-2">
                            <div class="flex flex-wrap items-center gap-2.5">
                                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight">
                                    {{ $factory->official_name }}
                                </h1>
                                @if($factory->is_verified)
                                    <span class="inline-flex sm:hidden items-center gap-1 px-2.5 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-bold rounded-full">
                                        {{ __('Verified') }}
                                    </span>
                                @endif
                            </div>

                            <!-- Meta Chips -->
                            <div class="flex flex-wrap items-center gap-3 text-xs sm:text-sm text-slate-600">
                                @if($factory->category)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-primary-50 text-primary-700 font-bold rounded-xl text-xs">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        {{ $factory->category->getTranslation('name', app()->getLocale()) }}
                                    </span>
                                @endif

                                @if($factory->country)
                                    <span class="inline-flex items-center gap-1 font-medium text-slate-700">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {{ $factory->country->name }}
                                    </span>
                                @endif

                                <span class="w-1 h-1 bg-slate-300 rounded-full hidden sm:block"></span>

                                <span class="inline-flex items-center gap-1 text-slate-500 font-medium">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    {{ __(':count Products Listed', ['count' => $factory->products->count()]) }}
                                </span>

                                @if($factory->language)
                                    <span class="w-1 h-1 bg-slate-300 rounded-full hidden sm:block"></span>
                                    <span class="inline-flex items-center gap-1 text-slate-500 font-medium">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5c-.345 3.522-1.782 6.78-4.102 9.21m0 0A15.933 15.933 0 013 9m3.412 0A15.975 15.975 0 0010 5C8.855 8.162 7.425 10.828 5.6 13" />
                                        </svg>
                                        {{ strtoupper($factory->language) }}
                                    </span>
                                @endif
                            </div>

                            @if($factory->address)
                                <div class="flex items-center gap-2 text-xs text-slate-600 pt-1">
                                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="text-slate-600">{{ $factory->address }}</span>
                                </div>
                            @endif

                            @if(!empty($factory->solutions))
                                <div class="flex flex-wrap items-center gap-2 pt-2">
                                    @foreach($factory->solutions as $sol)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-50 hover:bg-emerald-50 text-slate-700 hover:text-emerald-800 text-xs font-semibold rounded-lg border border-slate-200/80 transition-colors">
                                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                            </svg>
                                            {{ __($sol) }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Right: Call to Actions -->
                    <div class="flex items-center gap-3 w-full lg:w-auto pt-2 lg:pt-0">
                        <!-- Primary CTA: Contact Factory -->
                        <button @click="contactModalOpen = true"
                            class="flex-1 lg:flex-none inline-flex items-center justify-center gap-2.5 px-6 py-3.5 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-900 text-white font-bold rounded-2xl shadow-lg shadow-primary-600/25 hover:shadow-primary-600/40 transition-all duration-300 transform active:scale-95 text-sm sm:text-base">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <span>{{ __('Contact Factory') }}</span>
                        </button>

                        <!-- Share Button -->
                        <button @click="copyToClipboard(window.location.href, '{{ __('Profile link') }}')"
                            class="p-3.5 bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200/80 rounded-2xl font-bold transition-all hover:border-slate-300"
                            title="{{ __('Share Profile') }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                            </svg>
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <!-- Metric & Trust Strip -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Specialization') }}</p>
                    <p class="text-sm font-bold text-slate-900 truncate max-w-[140px]">{{ $factory->category ? $factory->category->getTranslation('name', app()->getLocale()) : __('Optical Solutions') }}</p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Supplier Trust') }}</p>
                    <p class="text-sm font-bold text-slate-900">{{ $factory->is_verified ? __('Audited & Verified') : __('Registered Partner') }}</p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Origin') }}</p>
                    <p class="text-sm font-bold text-slate-900">{{ $factory->country?->name ?? __('Global') }}</p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Live Catalog') }}</p>
                    <p class="text-sm font-bold text-slate-900">{{ __(':count Products', ['count' => $factory->products->count()]) }}</p>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs Bar -->
        <div class="mt-10 border-b border-slate-200">
            <div class="flex items-center gap-4 sm:gap-8 overflow-x-auto no-scrollbar">
                <!-- Tab 1: Products Catalog -->
                <button @click="activeTab = 'products'"
                    :class="activeTab === 'products' ? 'text-primary-600 border-primary-600' : 'text-slate-500 border-transparent hover:text-slate-900'"
                    class="flex items-center gap-2.5 pb-4 font-bold text-sm sm:text-base border-b-2 transition-all whitespace-nowrap">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <span>{{ __('Products Catalog') }}</span>
                    <span class="px-2 py-0.5 rounded-full text-xs"
                          :class="activeTab === 'products' ? 'bg-primary-100 text-primary-700' : 'bg-slate-100 text-slate-600'">
                        {{ $factory->products->count() }}
                    </span>
                </button>

                <!-- Tab 2: About Factory & Capabilities -->
                <button @click="activeTab = 'about'"
                    :class="activeTab === 'about' ? 'text-primary-600 border-primary-600' : 'text-slate-500 border-transparent hover:text-slate-900'"
                    class="flex items-center gap-2.5 pb-4 font-bold text-sm sm:text-base border-b-2 transition-all whitespace-nowrap">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span>{{ __('About Factory') }}</span>
                </button>

                <!-- Tab 3: Stock Deals -->
                @if($factory->stockOffers && $factory->stockOffers->count() > 0)
                <button @click="activeTab = 'stock'"
                    :class="activeTab === 'stock' ? 'text-primary-600 border-primary-600' : 'text-slate-500 border-transparent hover:text-slate-900'"
                    class="flex items-center gap-2.5 pb-4 font-bold text-sm sm:text-base border-b-2 transition-all whitespace-nowrap">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V4a2 2 0 10-2 2h2m-8 6h16a2 2 0 012 2v5a2 2 0 01-2 2H4a2 2 0 01-2-2v-5a2 2 0 012-2z" />
                    </svg>
                    <span>{{ __('Stock Deals') }}</span>
                    <span class="px-2 py-0.5 rounded-full text-xs bg-amber-100 text-amber-800 font-bold">
                        {{ $factory->stockOffers->count() }}
                    </span>
                </button>
                @endif

                <!-- Tab 4: Credentials & Compliance -->
                <button @click="activeTab = 'credentials'"
                    :class="activeTab === 'credentials' ? 'text-primary-600 border-primary-600' : 'text-slate-500 border-transparent hover:text-slate-900'"
                    class="flex items-center gap-2.5 pb-4 font-bold text-sm sm:text-base border-b-2 transition-all whitespace-nowrap">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>{{ __('Credentials & Contact') }}</span>
                </button>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 1: PRODUCTS SHOWROOM                   -->
        <!-- ========================================== -->
        <div x-show="activeTab === 'products'" class="mt-8">
            <!-- Filter Bar -->
            <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4 mb-8">
                <!-- Search within factory products -->
                <div class="relative w-full md:w-80">
                    <input type="text"
                        x-model="searchQuery"
                        placeholder="{{ __('Search in :name...', ['name' => $factory->official_name]) }}"
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-600 focus:bg-white focus:outline-none transition-all">
                    <svg class="w-5 h-5 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <!-- Category Filter Pills -->
                @php
                    $productCategories = $factory->products->pluck('category')->filter()->unique('id');
                @endphp
                <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-1 md:pb-0">
                    <button @click="selectedCategory = 'all'"
                        :class="selectedCategory === 'all' ? 'bg-primary-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                        class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all shrink-0">
                        {{ __('All') }} ({{ $factory->products->count() }})
                    </button>
                    @foreach($productCategories as $cat)
                        <button @click="selectedCategory = '{{ $cat->slug }}'"
                            :class="selectedCategory === '{{ $cat->slug }}' ? 'bg-primary-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                            class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all shrink-0">
                            {{ $cat->getTranslation('name', app()->getLocale()) }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Products Grid (3 Columns on Desktop) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @forelse($factory->products as $product)
                    @php
                        $prodCategoryName = $product->category ? $product->category->getTranslation('name', app()->getLocale()) : 'Optics';
                        $prodCategorySlug = $product->category ? $product->category->slug : 'other';
                        $prodTitle = $product->getTranslation('name', app()->getLocale());
                    @endphp
                    <div x-show="(selectedCategory === 'all' || selectedCategory === '{{ $prodCategorySlug }}') && ('{{ strtolower(addslashes($prodTitle)) }}'.includes(searchQuery.toLowerCase()) || searchQuery === '')"
                         class="bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:border-slate-200 transition-all duration-300 overflow-hidden flex flex-col group">
                        
                        <!-- Image Container with Aspect Square -->
                        <a href="{{ route('marketplace.product.show', $product) }}"
                           class="block aspect-square overflow-hidden relative bg-slate-50 group-hover:opacity-95">
                            <img src="{{ $product->getFirstMediaUrl('images') ?: 'https://images.unsplash.com/photo-1574258495973-f010dfbb5371?q=80&w=600&auto=format&fit=crop' }}"
                                alt="{{ $prodTitle }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            
                            <!-- Category Pill -->
                            <div class="absolute top-4 left-4 z-10">
                                <span class="px-3 py-1 bg-white/90 backdrop-blur-md text-[10px] font-bold text-slate-800 rounded-full uppercase tracking-wider shadow-sm border border-white">
                                    {{ $prodCategoryName }}
                                </span>
                            </div>
                        </a>

                        <!-- Product Information -->
                        <div class="p-6 flex flex-col flex-1 justify-between">
                            <div>
                                <h3 class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-primary-600 transition-colors line-clamp-1 mb-2">
                                    <a href="{{ route('marketplace.product.show', $product) }}">
                                        {{ $prodTitle }}
                                    </a>
                                </h3>

                                @if($product->description)
                                    <p class="text-slate-500 text-xs sm:text-sm line-clamp-2 mb-4 leading-relaxed">
                                        {{ strip_tags($product->getTranslation('description', app()->getLocale())) }}
                                    </p>
                                @endif
                            </div>

                            <!-- Price & Action Bottom Bar -->
                            <div class="flex items-center justify-between pt-4 border-t border-slate-100 mt-2">
                                <div>
                                    <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">{{ __('Starting at') }}</p>
                                    <p class="text-lg font-extrabold text-slate-900">
                                        <span class="text-xs font-bold text-slate-500">{{ $product->currency }}</span> {{ number_format($product->starting_price, 2) }}
                                    </p>
                                </div>

                                <a href="{{ route('marketplace.product.show', $product) }}"
                                   class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-primary-50 text-primary-600 font-bold rounded-xl text-xs hover:bg-primary-600 hover:text-white transition-all shadow-sm">
                                    <span>{{ __('View Details') }}</span>
                                    <svg class="w-3.5 h-3.5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 text-center bg-white rounded-3xl border border-slate-100 shadow-sm">
                        <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-bold text-slate-900">{{ __('No products listed yet') }}</h4>
                        <p class="text-slate-500 text-sm mt-1 max-w-md mx-auto">{{ __('This manufacturer is currently updating their product portfolio. Feel free to contact them directly for inquiries.') }}</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 2: ABOUT FACTORY & CAPABILITIES        -->
        <!-- ========================================== -->
        <div x-show="activeTab === 'about'" class="mt-8" x-cloak>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left 2 Cols: Story & Manufacturing Focus -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Factory Bio Card -->
                    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                        <h2 class="text-2xl font-bold text-slate-900 mb-6 flex items-center gap-3">
                            <span class="w-2.5 h-6 bg-primary-600 rounded-full"></span>
                            {{ __('About :name', ['name' => $factory->official_name]) }}
                        </h2>
                        
                        <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed text-base">
                            @if($factory->getTranslation('description', app()->getLocale()))
                                {!! nl2br(e($factory->getTranslation('description', app()->getLocale()))) !!}
                            @else
                                <p class="text-slate-400 italic">{{ __('No factory description provided.') }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Manufacturing Highlights Card -->
                    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                        <h3 class="text-xl font-bold text-slate-900 mb-6">{{ __('Key Capabilities & Standards') }}</h3>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100">
                                <div class="w-10 h-10 rounded-xl bg-primary-100 text-primary-600 flex items-center justify-center mb-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h4 class="font-bold text-slate-900 text-sm mb-1">{{ __('Precision Optics') }}</h4>
                                <p class="text-xs text-slate-500">{{ __('Advanced optical engineering with stringent quality inspection.') }}</p>
                            </div>

                            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100">
                                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                </div>
                                <h4 class="font-bold text-slate-900 text-sm mb-1">{{ __('OEM / ODM Service') }}</h4>
                                <p class="text-xs text-slate-500">{{ __('Custom packaging, lens coatings, and private label branding.') }}</p>
                            </div>

                            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100">
                                <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mb-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <h4 class="font-bold text-slate-900 text-sm mb-1">{{ __('Global Shipping') }}</h4>
                                <p class="text-xs text-slate-500">{{ __('Reliable international logistics with competitive wholesale freight.') }}</p>
                            </div>
                        </div>
                    </div>

                    @if($factory->video_url || $factory->vr_url)
                    <!-- Factory Video & VR Tour Card -->
                    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                            <div>
                                <h3 class="text-xl font-bold text-slate-900">{{ __('Facility Tour & Cleanroom Production') }}</h3>
                                <p class="text-xs text-slate-500 mt-1">{{ __('Live look inside automated coating chambers and precision optical inspection lines.') }}</p>
                            </div>
                            @if($factory->vr_url)
                                <a href="{{ $factory->vr_url }}" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl text-xs shadow-md transition-all shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                    <span>{{ __('Visit 360° VR Tour') }}</span>
                                </a>
                            @endif
                        </div>

                        <div class="rounded-2xl overflow-hidden bg-slate-900 aspect-video relative group shadow-inner">
                            @if($factory->video_url)
                                <video controls class="w-full h-full object-cover" poster="{{ $factory->banner_url }}">
                                    <source src="{{ $factory->video_url }}" type="video/mp4">
                                    {{ __('Your browser does not support the video tag.') }}
                                </video>
                            @else
                                <img src="{{ $factory->banner_url }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                    </div>
                    @endif

                    @if(!empty($factory->main_products))
                    <!-- Main Product Categories Portfolio -->
                    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                        <h3 class="text-xl font-bold text-slate-900 mb-4">{{ __('Main Products Portfolio') }}</h3>
                        <div class="flex flex-wrap gap-2.5">
                            @foreach($factory->main_products as $mp)
                                <span class="px-4 py-2 bg-slate-50 hover:bg-primary-50 hover:text-primary-700 hover:border-primary-200 border border-slate-200/80 rounded-xl text-xs font-bold text-slate-800 transition-all cursor-default shadow-xs">
                                    {{ $mp }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Right Col: Quick Snapshot -->
                <div class="space-y-6">
                    <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white p-7 rounded-3xl shadow-xl">
                        <h3 class="text-lg font-bold mb-4">{{ __('Partner with this Factory') }}</h3>
                        <p class="text-slate-300 text-xs sm:text-sm leading-relaxed mb-6">
                            {{ __('Connect directly with verified manufacturing representatives. Inquire about custom MOQ, price tiers, and sample testing.') }}
                        </p>
                        
                        <button @click="contactModalOpen = true"
                            class="w-full py-3.5 bg-primary-600 hover:bg-primary-500 text-white font-bold rounded-xl shadow-lg transition-all flex items-center justify-center gap-2 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <span>{{ __('Send Inquiry Now') }}</span>
                        </button>
                    </div>

                    <!-- Contact Details Summary Card -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-4">
                        <h4 class="font-bold text-slate-900 text-sm uppercase tracking-wider">{{ __('Quick Contacts') }}</h4>
                        
                        @if($factory->contact_person_first_name || $factory->contact_person_last_name)
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 uppercase font-bold">{{ __('Representative') }}</p>
                                <p class="text-sm font-bold text-slate-800">{{ trim($factory->contact_person_first_name . ' ' . $factory->contact_person_last_name) }}</p>
                            </div>
                        </div>
                        @endif

                        @if($factory->email)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] text-slate-400 uppercase font-bold">{{ __('Email') }}</p>
                                    <p class="text-sm font-semibold text-slate-800 truncate max-w-[160px]">{{ $factory->email }}</p>
                                </div>
                            </div>
                            <button @click="copyToClipboard('{{ $factory->email }}', '{{ __('Email') }}')" class="text-xs text-primary-600 font-bold hover:underline">
                                {{ __('Copy') }}
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 3: STOCK DEALS                         -->
        <!-- ========================================== -->
        @if($factory->stockOffers && $factory->stockOffers->count() > 0)
        <div x-show="activeTab === 'stock'" class="mt-8" x-cloak>
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">{{ __('Ready-to-Ship Stock Deals') }}</h2>
                    <p class="text-sm text-slate-500 mt-1">{{ __('Surplus lots and fast-dispatch inventory directly from this manufacturer.') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($factory->stockOffers as $offer)
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden flex flex-col group hover:shadow-xl transition-all">
                        <div class="aspect-video bg-slate-100 overflow-hidden relative">
                            <img src="{{ $offer->image_url ?: 'https://images.unsplash.com/photo-1574258495973-f010dfbb5371?q=80&w=600&auto=format&fit=crop' }}"
                                alt="{{ $offer->getTranslation('title', app()->getLocale()) }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <span class="absolute top-4 left-4 px-3 py-1 bg-amber-500 text-white text-[10px] font-bold rounded-full uppercase tracking-wider shadow">
                                {{ __('Surplus Deal') }}
                            </span>
                        </div>
                        <div class="p-6 flex flex-col justify-between flex-1">
                            <div>
                                <h3 class="font-bold text-slate-900 text-lg mb-2">
                                    <a href="{{ route('stock-offers.show', $offer) }}" class="hover:text-primary-600 transition-colors">
                                        {{ $offer->getTranslation('title', app()->getLocale()) }}
                                    </a>
                                </h3>
                                @if($offer->description)
                                    <p class="text-xs text-slate-500 line-clamp-2 mb-4 leading-relaxed">
                                        {{ strip_tags($offer->getTranslation('description', app()->getLocale())) }}
                                    </p>
                                @endif
                            </div>
                            <div class="flex items-center justify-between pt-4 border-t border-slate-100 mt-2">
                                <div>
                                    <p class="text-[10px] text-slate-400 uppercase font-bold">{{ __('Offer Price') }}</p>
                                    <p class="text-lg font-extrabold text-slate-900">{{ $offer->currency }} {{ number_format($offer->price, 2) }}</p>
                                </div>
                                <a href="{{ route('stock-offers.show', $offer) }}"
                                    class="px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-black transition-colors">
                                    {{ __('View Offer') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- ========================================== -->
        <!-- TAB 4: CREDENTIALS & CONTACT               -->
        <!-- ========================================== -->
        <div x-show="activeTab === 'credentials'" class="mt-8" x-cloak>
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm space-y-8">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900 mb-2">{{ __('Factory Registration & Contact Dossier') }}</h2>
                        <p class="text-sm text-slate-500">{{ __('Verified manufacturer business records and verified contact lines.') }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Official Tax / Business ID -->
                        @if($factory->tax_number)
                        <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100 flex items-start justify-between">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-primary-600 shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">{{ __('Tax / Registration ID') }}</p>
                                    <p class="text-base font-bold text-slate-900 font-mono mt-0.5">{{ $factory->tax_number }}</p>
                                </div>
                            </div>
                            <button @click="copyToClipboard('{{ $factory->tax_number }}', '{{ __('Tax ID') }}')" class="p-2 text-slate-400 hover:text-primary-600 rounded-lg hover:bg-white transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            </button>
                        </div>
                        @endif

                        <!-- Country & Region -->
                        @if($factory->country)
                        <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100 flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-primary-600 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">{{ __('Factory Country') }}</p>
                                <p class="text-base font-bold text-slate-900 mt-0.5">{{ $factory->country->name }}</p>
                            </div>
                        </div>
                        @endif

                        <!-- Official Email -->
                        @if($factory->email)
                        <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100 flex items-start justify-between">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-primary-600 shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">{{ __('Official Email') }}</p>
                                    <a href="mailto:{{ $factory->email }}" class="text-base font-bold text-slate-900 hover:text-primary-600 transition-colors mt-0.5 block">
                                        {{ $factory->email }}
                                    </a>
                                </div>
                            </div>
                            <button @click="copyToClipboard('{{ $factory->email }}', '{{ __('Email') }}')" class="p-2 text-slate-400 hover:text-primary-600 rounded-lg hover:bg-white transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            </button>
                        </div>
                        @endif

                        <!-- Phone -->
                        @if($factory->phone)
                        <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100 flex items-start justify-between">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-primary-600 shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">{{ __('Phone / Mobile') }}</p>
                                    <a href="tel:{{ $factory->phone }}" class="text-base font-bold text-slate-900 hover:text-primary-600 transition-colors mt-0.5 block">
                                        {{ $factory->phone }}
                                    </a>
                                </div>
                            </div>
                            <button @click="copyToClipboard('{{ $factory->phone }}', '{{ __('Phone') }}')" class="p-2 text-slate-400 hover:text-primary-600 rounded-lg hover:bg-white transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            </button>
                        </div>
                        @endif

                        <!-- Contact Person -->
                        @if($factory->contact_person_first_name || $factory->contact_person_last_name)
                        <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100 flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-primary-600 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">{{ __('Representative') }}</p>
                                <p class="text-base font-bold text-slate-900 mt-0.5">{{ trim($factory->contact_person_first_name . ' ' . $factory->contact_person_last_name) }}</p>
                            </div>
                        </div>
                        @endif

                        <!-- Primary Language -->
                        @if($factory->language)
                        <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100 flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-primary-600 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5c-.345 3.522-1.782 6.78-4.102 9.21m0 0A15.933 15.933 0 013 9m3.412 0A15.975 15.975 0 0010 5C8.855 8.162 7.425 10.828 5.6 13" /></svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">{{ __('Primary Business Language') }}</p>
                                <p class="text-base font-bold text-slate-900 mt-0.5">
                                    {{ match(strtolower($factory->language)) {
                                        'en' => 'English',
                                        'ar' => 'العربية (Arabic)',
                                        'tr' => 'Türkçe (Turkish)',
                                        'zh' => '中文 (Chinese)',
                                        'ru' => 'Русский (Russian)',
                                        'de' => 'Deutsch (German)',
                                        'es' => 'Español (Spanish)',
                                        'fr' => 'Français (French)',
                                        'it' => 'Italiano (Italian)',
                                        default => strtoupper($factory->language)
                                    } }}
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- ON-PAGE DIRECT INQUIRY SECTION (Made-in-China style) -->
        <!-- ========================================== -->
        <div class="mt-16 bg-white rounded-3xl p-8 sm:p-12 border border-slate-100 shadow-sm">
            <div class="max-w-4xl mx-auto">
                <div class="border-b border-slate-100 pb-6 mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">{{ __('Send your message to this supplier') }}</h2>
                        <p class="text-sm text-slate-500 mt-1">{{ __('Direct communication with the verified factory sales and engineering department.') }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            {{ __('Online & Fast Response') }}
                        </span>
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- From -->
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-2 sm:gap-6 items-center">
                        <label class="text-sm font-bold text-slate-700 sm:text-right">
                            <span class="text-rose-500">*</span> {{ __('From') }}:
                        </label>
                        <div class="sm:col-span-3">
                            <input type="email" placeholder="{{ auth()->check() ? auth()->user()->email : 'your.email@company.com' }}"
                                value="{{ auth()->check() ? auth()->user()->email : '' }}"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-600 focus:bg-white focus:outline-none transition-all">
                        </div>
                    </div>

                    <!-- To -->
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-2 sm:gap-6 items-center">
                        <label class="text-sm font-bold text-slate-700 sm:text-right">
                            <span class="text-rose-500">*</span> {{ __('To') }}:
                        </label>
                        <div class="sm:col-span-3 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-slate-100 border border-slate-200 overflow-hidden shrink-0">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($factory->contact_person_first_name ?? 'Kay') }}&background=0284c7&color=fff" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-900">{{ trim(($factory->contact_person_first_name ?? 'Kay') . ' ' . ($factory->contact_person_last_name ?? '(Sales Manager)')) }}</p>
                                <p class="text-xs text-slate-500">{{ $factory->official_name }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Message -->
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-2 sm:gap-6 items-start">
                        <label class="text-sm font-bold text-slate-700 sm:text-right pt-3">
                            <span class="text-rose-500">*</span> {{ __('Message') }}:
                        </label>
                        <div class="sm:col-span-3 space-y-3">
                            <!-- Quick Chips -->
                            <div class="flex flex-wrap gap-2">
                                <button type="button" @click="inquiryMessage = 'Hello, please send us your latest wholesale price list and product catalog for optical lenses. Thank you.'"
                                    class="px-3 py-1 bg-slate-100 hover:bg-primary-50 hover:text-primary-700 text-slate-600 rounded-lg text-xs font-medium transition-colors">
                                    + {{ __('Request Wholesale Catalog') }}
                                </button>
                                <button type="button" @click="inquiryMessage = 'Hello, we would like to inquire about sample availability, testing procedures, and MOQ for bulk orders.'"
                                    class="px-3 py-1 bg-slate-100 hover:bg-primary-50 hover:text-primary-700 text-slate-600 rounded-lg text-xs font-medium transition-colors">
                                    + {{ __('Inquire About Samples & MOQ') }}
                                </button>
                                <button type="button" @click="inquiryMessage = 'Hello, we are looking for OEM / private label packaging for our optical brand. Please contact us with options.'"
                                    class="px-3 py-1 bg-slate-100 hover:bg-primary-50 hover:text-primary-700 text-slate-600 rounded-lg text-xs font-medium transition-colors">
                                    + {{ __('OEM / Private Label Inquiry') }}
                                </button>
                            </div>

                            <textarea x-model="inquiryMessage" rows="5"
                                placeholder="{{ __('Detail your product requirements here: lens indexes (1.49, 1.56, 1.61, 1.67, 1.74), coating types (HMC, SHMC), diopter power range, estimated order quantity, and shipping destination.') }}"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-600 focus:bg-white focus:outline-none transition-all"></textarea>
                            
                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pt-2">
                                <p class="text-xs text-slate-400">{{ __('Enter between 20 to 4,000 characters for an accurate factory quotation.') }}</p>
                                
                                <button type="button" @click="contactModalOpen = true"
                                    class="px-8 py-3.5 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl shadow-lg shadow-primary-600/25 transition-all text-sm flex items-center gap-2">
                                    <span>{{ __('Send Inquiry Now') }}</span>
                                    <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ========================================== -->
    <!-- INTERACTIVE CONTACT MODAL                  -->
    <!-- ========================================== -->
    <div x-show="contactModalOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 sm:p-6 bg-slate-950/60 backdrop-blur-sm">
        
        <div @click.away="contactModalOpen = false"
             x-show="contactModalOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden">
            
            <!-- Modal Header -->
            <div class="px-6 sm:px-8 pt-8 pb-5 border-b border-slate-100 flex items-start justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-slate-50 border border-slate-100 p-1.5 shrink-0">
                        <img src="{{ $factory->logo_url ?: 'https://ui-avatars.com/api/?name=' . urlencode($factory->official_name) . '&size=256' }}"
                            alt="{{ $factory->official_name }}" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">{{ __('Inquire with :name', ['name' => $factory->official_name]) }}</h3>
                        <p class="text-xs text-slate-500">{{ $factory->country?->name ?? 'Global' }} • {{ __('Direct B2B Channel') }}</p>
                    </div>
                </div>
                <button @click="contactModalOpen = false" class="p-2 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 sm:p-8 space-y-5">
                <!-- Topic Selection -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">{{ __('Select Inquiry Topic') }}</label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach([
                            'Wholesale Price List' => __('Price List'),
                            'OEM / Private Label' => __('OEM / Private Label'),
                            'Sample Testing' => __('Sample Request'),
                            'Custom Bulk Order' => __('Bulk Order')
                        ] as $key => $topicName)
                            <button type="button"
                                @click="inquiryTopic = '{{ $key }}'"
                                :class="inquiryTopic === '{{ $key }}' ? 'bg-primary-50 border-primary-600 text-primary-700 font-bold' : 'bg-slate-50 border-slate-200 text-slate-600 font-medium hover:bg-slate-100'"
                                class="p-2.5 rounded-xl border text-xs text-left transition-all">
                                {{ $topicName }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Fast Actions -->
                <div class="space-y-3 pt-2">
                    @if($factory->email)
                    <a href="mailto:{{ $factory->email }}?subject=B2B%20Inquiry%20via%20OpticB2B:%20"
                        class="w-full py-3.5 px-4 bg-primary-600 hover:bg-primary-700 text-white rounded-2xl font-bold text-sm shadow-lg shadow-primary-600/20 flex items-center justify-center gap-2.5 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        <span>{{ __('Send Email to :email', ['email' => $factory->email]) }}</span>
                    </a>
                    @endif

                    @if($factory->phone)
                    <a href="tel:{{ $factory->phone }}"
                        class="w-full py-3.5 px-4 bg-slate-900 hover:bg-black text-white rounded-2xl font-bold text-sm flex items-center justify-center gap-2.5 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                        <span>{{ __('Call Direct: :phone', ['phone' => $factory->phone]) }}</span>
                    </a>
                    @endif
                </div>

                <!-- Copy Quick Contacts -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span>{{ __('Need direct records?') }}</span>
                    <div class="flex items-center gap-3">
                        @if($factory->email)
                        <button @click="copyToClipboard('{{ $factory->email }}', '{{ __('Email') }}')" class="font-bold text-primary-600 hover:underline">
                            {{ __('Copy Email') }}
                        </button>
                        @endif
                        @if($factory->phone)
                        <button @click="copyToClipboard('{{ $factory->phone }}', '{{ __('Phone') }}')" class="font-bold text-primary-600 hover:underline">
                            {{ __('Copy Phone') }}
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection