@extends('layouts.app')

@section('title', __('OpticB2B - Global Optical Marketplace'))

@php
    $categories = \App\Models\Category::where('type', 'product')->get();
    $countries = \App\Models\Country::where('status', 'active')->orderBy('name')->get();
    
    // Featured & latest products with relations
    $products = \App\Models\Product::with(['factory.country', 'category'])
        ->where('status', 'approved')
        ->orderBy('is_featured', 'desc')
        ->latest()
        ->take(8)
        ->get();
        
    $featuredFactories = \App\Models\Factory::with('country')
        ->where('status', 'approved')
        ->orderBy('is_verified', 'desc')
        ->latest()
        ->take(3)
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
    
    // Calculated trust metrics
    $factoriesMetric = max(150, floor($factoriesCount / 10) * 10);
    $productsMetric = max(500, floor($productsCount / 50) * 50);
    $countriesMetric = max(25, floor($countriesCount / 5) * 5);
@endphp

@section('content')

    <!-- ========================================================================= -->
    <!-- SECTION 1: MASTER HERO (Optical Precision & Dual-Mode Search Terminal)     -->
    <!-- ========================================================================= -->
    <section class="relative overflow-hidden pt-28 pb-20 lg:pt-36 lg:pb-28 min-h-[640px] flex items-center bg-gradient-to-br from-slate-950 via-[#0a1222] to-slate-900" x-data="{ searchMode: 'products' }">
        <!-- Video Background with Fallback -->
        <video autoplay loop muted playsinline poster="{{ asset('uploads/hero_showcase.jpg') }}" class="absolute inset-0 w-full h-full object-cover z-0 opacity-30 mix-blend-luminosity">
            <source src="{{ asset('uploads/videos/hero.mp4') }}" type="video/mp4">
            <source src="{{ asset('uploads/videos/hero.mov') }}" type="video/quicktime">
        </video>
        
        <!-- Ambient Radial Glows & Optical Grid -->
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/80 to-transparent z-10 pointer-events-none"></div>
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-primary-600/20 rounded-full blur-[128px] pointer-events-none"></div>
        <div class="absolute top-1/2 -right-40 w-96 h-96 bg-cyan-500/15 rounded-full blur-[140px] pointer-events-none"></div>
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#1e293b08_1px,transparent_1px),linear-gradient(to_bottom,#1e293b08_1px,transparent_1px)] bg-[size:32px_32px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-20 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                
                <!-- Left Column: Value Proposition & Sourcing Terminal -->
                <div class="lg:col-span-7 text-start">
                    
                    <!-- Eyebrow Pill -->
                    <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-xs font-semibold text-cyan-300 mb-6 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                        <span>{{ __('Global Optical B2B Sourcing Network') }}</span>
                        <span class="text-white/30">•</span>
                        <span class="text-slate-200">{{ __('150+ Audited Factories') }}</span>
                    </div>

                    <!-- Main H1 Headline -->
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight leading-[1.15] mb-5">
                        {{ __('Source Optical Lenses, Frames & Lab Machinery Direct From Verified Factories') }}
                    </h1>

                    <!-- Subtitle -->
                    <p class="text-base sm:text-lg text-slate-300 font-normal leading-relaxed mb-8 max-w-2xl">
                        {{ __('The premier international B2B optical network connecting global buyers, opticians, and labs directly to audited manufacturers.') }}
                    </p>

                    <!-- Dual-Mode Search Terminal Box -->
                    <div class="bg-white/95 backdrop-blur-xl p-3 sm:p-4 rounded-2xl shadow-2xl border border-white/20">
                        
                        <!-- Search Mode Switcher Tabs -->
                        <div class="flex items-center gap-2 pb-3 mb-3 border-b border-slate-100">
                            <button type="button" 
                                    @click="searchMode = 'products'" 
                                    :class="searchMode === 'products' ? 'bg-primary-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'"
                                    class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                {{ __('Search Products & Specifications') }}
                            </button>
                            <button type="button" 
                                    @click="searchMode = 'factories'" 
                                    :class="searchMode === 'factories' ? 'bg-primary-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'"
                                    class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                {{ __('Search Verified Factories') }}
                            </button>
                        </div>

                        <!-- Form 1: Products Mode -->
                        <form x-show="searchMode === 'products'" action="{{ route('marketplace.index') }}" method="GET" class="flex flex-col md:flex-row gap-2.5">
                            <div class="flex-1 relative">
                                <svg class="w-5 h-5 absolute left-3.5 rtl:left-auto rtl:right-3.5 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                <input type="text" name="search" placeholder="{{ __('Search by product name, index, material, or keyword...') }}" class="w-full pl-11 pr-4 rtl:pl-4 rtl:pr-11 py-3.5 bg-slate-50 border border-slate-200/80 rounded-xl focus:ring-2 focus:ring-primary-600 focus:bg-white text-slate-900 text-sm font-medium transition-all placeholder:text-slate-400">
                            </div>
                            <div class="md:w-44 relative">
                                <select name="category" class="w-full px-3.5 py-3.5 bg-slate-50 border border-slate-200/80 rounded-xl focus:ring-2 focus:ring-primary-600 focus:bg-white text-slate-800 text-sm font-medium transition-all appearance-none cursor-pointer">
                                    <option value="">{{ __('All Categories') }}</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->slug }}">{{ $cat->getTranslation('name', app()->getLocale()) }}</option>
                                    @endforeach
                                </select>
                                <svg class="w-4 h-4 absolute right-3 rtl:right-auto rtl:left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                            <div class="md:w-40 relative">
                                <select name="country" class="w-full px-3.5 py-3.5 bg-slate-50 border border-slate-200/80 rounded-xl focus:ring-2 focus:ring-primary-600 focus:bg-white text-slate-800 text-sm font-medium transition-all appearance-none cursor-pointer">
                                    <option value="">{{ __('All Countries') }}</option>
                                    @foreach($countries as $cntry)
                                        <option value="{{ $cntry->code }}">{{ $cntry->name }}</option>
                                    @endforeach
                                </select>
                                <svg class="w-4 h-4 absolute right-3 rtl:right-auto rtl:left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                            <button type="submit" class="px-7 py-3.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-bold text-sm shadow-lg shadow-primary-600/30 hover:shadow-primary-600/50 transition-all flex items-center justify-center gap-2 whitespace-nowrap">
                                <span>{{ __('Search') }}</span>
                                <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                            </button>
                        </form>

                        <!-- Form 2: Factories Mode -->
                        <form x-show="searchMode === 'factories'" x-cloak action="{{ route('factories.index') }}" method="GET" class="flex flex-col md:flex-row gap-2.5">
                            <div class="flex-1 relative">
                                <svg class="w-5 h-5 absolute left-3.5 rtl:left-auto rtl:right-3.5 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                <input type="text" name="search" placeholder="{{ __('Search factory name, city, certification...') }}" class="w-full pl-11 pr-4 rtl:pl-4 rtl:pr-11 py-3.5 bg-slate-50 border border-slate-200/80 rounded-xl focus:ring-2 focus:ring-primary-600 focus:bg-white text-slate-900 text-sm font-medium transition-all placeholder:text-slate-400">
                            </div>
                            <div class="md:w-56 relative">
                                <select name="country" class="w-full px-3.5 py-3.5 bg-slate-50 border border-slate-200/80 rounded-xl focus:ring-2 focus:ring-primary-600 focus:bg-white text-slate-800 text-sm font-medium transition-all appearance-none cursor-pointer">
                                    <option value="">{{ __('All Factory Locations') }}</option>
                                    @foreach($countries as $cntry)
                                        <option value="{{ $cntry->code }}">{{ $cntry->name }}</option>
                                    @endforeach
                                </select>
                                <svg class="w-4 h-4 absolute right-3 rtl:right-auto rtl:left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                            <button type="submit" class="px-7 py-3.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-bold text-sm shadow-lg shadow-primary-600/30 hover:shadow-primary-600/50 transition-all flex items-center justify-center gap-2 whitespace-nowrap">
                                <span>{{ __('Find Factories') }}</span>
                                <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                            </button>
                        </form>

                    </div>

                    <!-- Quick Sourcing Tags -->
                    <div class="mt-5 flex flex-wrap items-center gap-2 text-xs">
                        <span class="text-slate-400 font-medium">{{ __('Popular Optical Searches:') }}</span>
                        <a href="{{ route('marketplace.index', ['search' => '1.67 Blue Cut']) }}" class="px-2.5 py-1 bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/15 rounded-lg text-slate-200 hover:text-white font-medium transition-colors">1.67 Blue Cut</a>
                        <a href="{{ route('marketplace.index', ['search' => '1.56 Progressive']) }}" class="px-2.5 py-1 bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/15 rounded-lg text-slate-200 hover:text-white font-medium transition-colors">1.56 Progressive</a>
                        <a href="{{ route('marketplace.index', ['search' => 'CR-39 Single Vision']) }}" class="px-2.5 py-1 bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/15 rounded-lg text-slate-200 hover:text-white font-medium transition-colors">CR-39 UV400</a>
                        <a href="{{ route('marketplace.index', ['search' => 'Titanium Frames']) }}" class="px-2.5 py-1 bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/15 rounded-lg text-slate-200 hover:text-white font-medium transition-colors">Titanium Frames</a>
                        <a href="{{ route('marketplace.index', ['search' => 'Auto Edger']) }}" class="px-2.5 py-1 bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/15 rounded-lg text-slate-200 hover:text-white font-medium transition-colors">Auto Edger CNC</a>
                    </div>
                </div>

                <!-- Right Column: Interactive Optical Showcase Widget -->
                <div class="lg:col-span-5 relative hidden lg:block">
                    
                    <!-- Ambient Glow Effect -->
                    <div class="absolute -inset-2 bg-gradient-to-r from-primary-600/30 via-cyan-500/20 to-primary-800/30 rounded-3xl blur-2xl"></div>

                    <!-- Glassmorphism Card Frame -->
                    <div class="relative rounded-3xl bg-slate-900/80 backdrop-blur-xl border border-white/15 p-5 shadow-2xl overflow-hidden">
                        
                        <!-- Top Meta Header -->
                        <div class="flex items-center justify-between pb-3.5 mb-3.5 border-b border-white/10">
                            <div class="flex items-center gap-2.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-300">{{ __('Verified Optical Laboratories') }}</span>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full bg-cyan-500/10 border border-cyan-400/30 text-[11px] font-semibold text-cyan-300">ISO9001 • CE</span>
                        </div>

                        <!-- Showcase Main Hero Image -->
                        <div class="relative rounded-2xl overflow-hidden aspect-[16/10] bg-slate-950 mb-4 border border-white/10 group">
                            <img src="{{ asset('uploads/hero_showcase.jpg') }}" alt="Optical Lens Precision Manufacturing" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent"></div>
                            
                            <!-- Overlay Floating Badges -->
                            <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between">
                                <div class="bg-black/60 backdrop-blur-md px-3 py-1.5 rounded-xl border border-white/15">
                                    <p class="text-[11px] font-bold text-white leading-tight">Danyang Boris Optical Co., Ltd.</p>
                                    <p class="text-[10px] text-cyan-300 font-medium">{{ __('Capacity: 20,000 PCS/Day') }}</p>
                                </div>
                                <span class="px-2.5 py-1 bg-emerald-500/90 text-white text-[11px] font-bold rounded-lg shadow-sm flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    {{ __('Audited Lab') }}
                                </span>
                            </div>
                        </div>

                        <!-- 3 Live Metrics Strip -->
                        <div class="grid grid-cols-3 gap-2.5 text-center">
                            <div class="p-2.5 rounded-xl bg-white/5 border border-white/10">
                                <p class="text-xs font-bold text-white">< 2.5 hrs</p>
                                <p class="text-[10px] text-slate-400 uppercase tracking-wider mt-0.5">{{ __('Fast RFQ Response') }}</p>
                            </div>
                            <div class="p-2.5 rounded-xl bg-white/5 border border-white/10">
                                <p class="text-xs font-bold text-emerald-400">100%</p>
                                <p class="text-[10px] text-slate-400 uppercase tracking-wider mt-0.5">{{ __('Direct Lab Price') }}</p>
                            </div>
                            <div class="p-2.5 rounded-xl bg-white/5 border border-white/10">
                                <p class="text-xs font-bold text-cyan-400">0%</p>
                                <p class="text-[10px] text-slate-400 uppercase tracking-wider mt-0.5">{{ __('Broker Fee') }}</p>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- SECTION 2: ENTERPRISE TRUST & CERTIFICATION MATRIX                         -->
    <!-- ========================================================================= -->
    <section class="bg-white border-b border-slate-200/80 py-8 relative z-20 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 lg:gap-8 divide-y md:divide-y-0 md:divide-x rtl:md:divide-x-reverse divide-slate-100">
                
                <!-- Stat 1 -->
                <div class="text-center pt-4 md:pt-0 px-3">
                    <div class="flex items-center justify-center gap-1.5 mb-1">
                        <span class="text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">{{ $factoriesMetric }}+</span>
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    </div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('Verified Optical Factories') }}</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">{{ __('Danyang, Wenzhou & Europe') }}</p>
                </div>

                <!-- Stat 2 -->
                <div class="text-center pt-4 md:pt-0 px-3">
                    <div class="flex items-center justify-center gap-1.5 mb-1">
                        <span class="text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">{{ $productsMetric }}+</span>
                    </div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('Optical SKUs & Blanks') }}</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">{{ __('Indexes 1.49 to 1.74 & Rx') }}</p>
                </div>

                <!-- Stat 3 -->
                <div class="text-center pt-4 md:pt-0 px-3">
                    <div class="flex items-center justify-center gap-1.5 mb-1">
                        <span class="text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">{{ $countriesMetric }}+</span>
                    </div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('Trade Destination Countries') }}</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">{{ __('Air Express & Sea Cargo') }}</p>
                </div>

                <!-- Stat 4 -->
                <div class="text-center pt-4 md:pt-0 px-3">
                    <div class="flex items-center justify-center gap-1.5 mb-1">
                        <span class="text-2xl lg:text-3xl font-extrabold text-primary-600 tracking-tight">100%</span>
                    </div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('Factory Direct Assurance') }}</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">{{ __('Direct Lab Escrow & Inspection') }}</p>
                </div>

            </div>

            <!-- International Compliance Standards Ribbon -->
            <div class="mt-8 pt-6 border-t border-slate-100 flex flex-wrap items-center justify-center gap-6 sm:gap-12 text-slate-400 text-xs font-semibold">
                <span class="text-slate-500 uppercase tracking-wider text-[11px]">{{ __('Compliance Standards:') }}</span>
                <span class="flex items-center gap-1.5 text-slate-700">
                    <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    ISO 9001:2015 Lab Management
                </span>
                <span class="flex items-center gap-1.5 text-slate-700">
                    <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    CE Mark (EN ISO 14889)
                </span>
                <span class="flex items-center gap-1.5 text-slate-700">
                    <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    FDA 21 CFR Impact Resistant
                </span>
                <span class="flex items-center gap-1.5 text-slate-700">
                    <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    SGS & TÜV Audited Lines
                </span>
            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- SECTION 3: OPTICAL SECTOR SHOWCASE (Browse by Industry Department)        -->
    <!-- ========================================================================= -->
    <section class="py-16 lg:py-24 bg-slate-50 border-b border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
                <div>
                    <span class="px-3 py-1 rounded-full bg-primary-100 text-primary-700 text-xs font-bold uppercase tracking-wider mb-3 inline-block">{{ __('Browse by Optical Sector') }}</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">{{ __('Wholesale Optical Manufacturing Departments') }}</h2>
                    <p class="text-sm sm:text-base text-slate-600 mt-2 max-w-2xl">{{ __('Explore specialized categories manufactured to international optical lab standards.') }}</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('marketplace.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-primary-600 hover:text-primary-700 transition-colors">
                        <span>{{ __('View Complete Catalog') }}</span>
                        <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Sector 1: Precision Optical Lenses -->
                <div class="group bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-xl hover:border-primary-500/50 transition-all duration-300 flex flex-col">
                    <div class="aspect-[16/10] relative overflow-hidden bg-slate-100">
                        <img src="{{ asset('uploads/categories/lenses.jpg') }}" alt="Precision Optical Lenses & Blanks" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                        <div class="absolute bottom-3.5 left-4 right-4 flex items-center justify-between text-white">
                            <span class="text-xs font-bold uppercase tracking-wider bg-white/20 backdrop-blur-md px-2.5 py-1 rounded-lg">1.49 ~ 1.74 Index</span>
                            <span class="text-xs font-semibold text-cyan-300">ISO / CE Certified</span>
                        </div>
                    </div>
                    <div class="p-6 flex-1 flex flex-col">
                        <h3 class="text-xl font-extrabold text-slate-900 group-hover:text-primary-600 transition-colors mb-2">
                            {{ __('Precision Optical Lenses & Blanks') }}
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-4">
                            {{ __('Finished & semi-finished stock lenses, Blue Cut UV420, Progressive HMC, Photochromic spin, and custom Rx surfaced lenses.') }}
                        </p>
                        
                        <!-- Pills -->
                        <div class="flex flex-wrap gap-1.5 mb-6 text-[11px] font-medium text-slate-600">
                            <span class="px-2 py-0.5 bg-slate-100 rounded-md">CR-39 Single Vision</span>
                            <span class="px-2 py-0.5 bg-slate-100 rounded-md">1.61 MR-8</span>
                            <span class="px-2 py-0.5 bg-slate-100 rounded-md">1.67 Blue Block</span>
                            <span class="px-2 py-0.5 bg-slate-100 rounded-md">1.74 Ultra Thin</span>
                            <span class="px-2 py-0.5 bg-slate-100 rounded-md">Photochromic Grey</span>
                        </div>

                        <div class="mt-auto pt-4 border-t border-slate-100">
                            <a href="{{ route('marketplace.index', ['category' => 'lenses']) }}" class="w-full py-2.5 px-4 bg-slate-900 group-hover:bg-primary-600 text-white rounded-xl text-xs font-bold flex items-center justify-center gap-2 transition-colors">
                                <span>{{ __('Explore Optical Lenses') }}</span>
                                <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Sector 2: Designer Eyewear & Frames -->
                <div class="group bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-xl hover:border-primary-500/50 transition-all duration-300 flex flex-col">
                    <div class="aspect-[16/10] relative overflow-hidden bg-slate-100">
                        <img src="{{ asset('uploads/categories/frames.jpg') }}" alt="Designer Eyewear & Frames" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                        <div class="absolute bottom-3.5 left-4 right-4 flex items-center justify-between text-white">
                            <span class="text-xs font-bold uppercase tracking-wider bg-white/20 backdrop-blur-md px-2.5 py-1 rounded-lg">OEM / ODM Available</span>
                            <span class="text-xs font-semibold text-cyan-300">Low MOQ</span>
                        </div>
                    </div>
                    <div class="p-6 flex-1 flex flex-col">
                        <h3 class="text-xl font-extrabold text-slate-900 group-hover:text-primary-600 transition-colors mb-2">
                            {{ __('Designer Eyewear & Frames') }}
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-4">
                            {{ __('Handmade Italian acetate frames, Japanese ultra-light pure titanium, TR90 memory flex, and private label optical collections.') }}
                        </p>
                        
                        <!-- Pills -->
                        <div class="flex flex-wrap gap-1.5 mb-6 text-[11px] font-medium text-slate-600">
                            <span class="px-2 py-0.5 bg-slate-100 rounded-md">Pure Titanium</span>
                            <span class="px-2 py-0.5 bg-slate-100 rounded-md">Mazzucchelli Acetate</span>
                            <span class="px-2 py-0.5 bg-slate-100 rounded-md">TR90 Ultem</span>
                            <span class="px-2 py-0.5 bg-slate-100 rounded-md">Kids Flexible</span>
                            <span class="px-2 py-0.5 bg-slate-100 rounded-md">Polarized Sun</span>
                        </div>

                        <div class="mt-auto pt-4 border-t border-slate-100">
                            <a href="{{ route('marketplace.index', ['category' => 'frames']) }}" class="w-full py-2.5 px-4 bg-slate-900 group-hover:bg-primary-600 text-white rounded-xl text-xs font-bold flex items-center justify-center gap-2 transition-colors">
                                <span>{{ __('Explore Eyewear Frames') }}</span>
                                <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Sector 3: Optical Machinery & Lab Equipment -->
                <div class="group bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-xl hover:border-primary-500/50 transition-all duration-300 flex flex-col">
                    <div class="aspect-[16/10] relative overflow-hidden bg-slate-100">
                        <img src="{{ asset('uploads/categories/machinery.jpg') }}" alt="Optical Machinery & Lab Equipment" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                        <div class="absolute bottom-3.5 left-4 right-4 flex items-center justify-between text-white">
                            <span class="text-xs font-bold uppercase tracking-wider bg-white/20 backdrop-blur-md px-2.5 py-1 rounded-lg">High Precision CNC</span>
                            <span class="text-xs font-semibold text-cyan-300">Lab Automation</span>
                        </div>
                    </div>
                    <div class="p-6 flex-1 flex flex-col">
                        <h3 class="text-xl font-extrabold text-slate-900 group-hover:text-primary-600 transition-colors mb-2">
                            {{ __('Optical Machinery & Lab Equipment') }}
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-4">
                            {{ __('Patternless automatic lens edgers, auto-lensmeters, digital blocking units, ultrasonic cleaners, and coating chambers.') }}
                        </p>
                        
                        <!-- Pills -->
                        <div class="flex flex-wrap gap-1.5 mb-6 text-[11px] font-medium text-slate-600">
                            <span class="px-2 py-0.5 bg-slate-100 rounded-md">Auto Lens Edger</span>
                            <span class="px-2 py-0.5 bg-slate-100 rounded-md">Digital Lensmeter</span>
                            <span class="px-2 py-0.5 bg-slate-100 rounded-md">Centering System</span>
                            <span class="px-2 py-0.5 bg-slate-100 rounded-md">Hard Coating Line</span>
                        </div>

                        <div class="mt-auto pt-4 border-t border-slate-100">
                            <a href="{{ route('marketplace.index', ['category' => 'machinery']) }}" class="w-full py-2.5 px-4 bg-slate-900 group-hover:bg-primary-600 text-white rounded-xl text-xs font-bold flex items-center justify-center gap-2 transition-colors">
                                <span>{{ __('Explore Lab Machinery') }}</span>
                                <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- SECTION 4: INTERACTIVE RFQ SOURCING TERMINAL (Find Your Best Deal)        -->
    <!-- ========================================================================= -->
    <section class="py-16 lg:py-24 bg-gradient-to-br from-slate-950 via-[#0a152e] to-slate-900 text-white relative overflow-hidden">
        <!-- Ambient Glow Elements -->
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-primary-600/20 rounded-full blur-[140px] pointer-events-none"></div>
        <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-cyan-500/20 rounded-full blur-[140px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <!-- Left: Value Proposition & 4 Steps -->
                <div class="lg:col-span-7 text-start">
                    <span class="px-3.5 py-1.5 rounded-full bg-cyan-500/10 border border-cyan-400/30 text-cyan-300 text-xs font-bold uppercase tracking-wider mb-4 inline-block">
                        {{ __('Custom Sourcing & RFQ Engine') }}
                    </span>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight leading-tight mb-4">
                        {{ __('Can\'t Find Your Exact Prescription or Private Label Specs?') }}
                    </h2>
                    <p class="text-slate-300 text-sm sm:text-base leading-relaxed mb-8 max-w-xl">
                        {{ __('Submit your required lens powers, coating formulas, frame designs, or machinery requirements. Our automated system routes your request to verified manufacturing directors for immediate competitive bidding.') }}
                    </p>

                    <!-- 4 Process Milestones -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm">
                            <span class="text-cyan-400 text-xs font-bold uppercase tracking-widest block mb-1">01. {{ __('Submit Specs') }}</span>
                            <p class="text-xs text-slate-300 leading-normal">{{ __('Define quantity, target FOB price, optical index, and private label packaging requirements.') }}</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm">
                            <span class="text-cyan-400 text-xs font-bold uppercase tracking-widest block mb-1">02. {{ __('Factories Notified') }}</span>
                            <p class="text-xs text-slate-300 leading-normal">{{ __('Audited optical laboratories in Danyang, Cadore & Istanbul receive instant notifications.') }}</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm">
                            <span class="text-cyan-400 text-xs font-bold uppercase tracking-widest block mb-1">03. {{ __('Receive Bids') }}</span>
                            <p class="text-xs text-slate-300 leading-normal">{{ __('Compare wholesale quotes, production lead times, and lab inspection reports.') }}</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm">
                            <span class="text-cyan-400 text-xs font-bold uppercase tracking-widest block mb-1">04. {{ __('Sample & Escrow') }}</span>
                            <p class="text-xs text-slate-300 leading-normal">{{ __('Request trial samples with express air dispatch and finalize contracts with escrow protection.') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Right: Quick RFQ Launcher Box -->
                <div class="lg:col-span-5">
                    <div class="bg-white/10 backdrop-blur-2xl rounded-3xl p-6 sm:p-8 border border-white/20 shadow-2xl relative">
                        <div class="flex items-center justify-between pb-4 mb-5 border-b border-white/10">
                            <div>
                                <h3 class="text-base font-extrabold text-white">{{ __('Post Sourcing Request (RFQ)') }}</h3>
                                <p class="text-xs text-cyan-300 mt-0.5">{{ __('Free service for optical buyers & distributors') }}</p>
                            </div>
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        </div>

                        <!-- Direct form jumping to Sourcing Creation or Filter -->
                        <form action="{{ route('sourcing.index') }}" method="GET" class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">{{ __('Select Optical Sector') }}</label>
                                <select name="category" class="w-full px-3.5 py-3 bg-slate-900/80 border border-white/15 rounded-xl text-white text-xs sm:text-sm font-medium focus:ring-2 focus:ring-cyan-400 appearance-none">
                                    <option value="">{{ __('All Sectors (Lenses, Frames, Machines)') }}</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->slug }}">{{ $cat->getTranslation('name', app()->getLocale()) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">{{ __('Target Volume / Quantity') }}</label>
                                <select class="w-full px-3.5 py-3 bg-slate-900/80 border border-white/15 rounded-xl text-white text-xs sm:text-sm font-medium focus:ring-2 focus:ring-cyan-400 appearance-none">
                                    <option>500 - 1,000 Pairs / Units</option>
                                    <option>1,000 - 5,000 Pairs / Units</option>
                                    <option>5,000 - 20,000 Pairs / Units</option>
                                    <option>20,000+ Wholesale Full Container</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">{{ __('Requirement Details') }}</label>
                                <input type="text" name="search" placeholder="{{ __('e.g. 1.67 Blue Cut HMC Green Coating, 70mm diameter...') }}" class="w-full px-3.5 py-3 bg-slate-900/80 border border-white/15 rounded-xl text-white text-xs sm:text-sm font-medium focus:ring-2 focus:ring-cyan-400 placeholder:text-slate-500">
                            </div>

                            <button type="submit" class="w-full py-3.5 px-6 bg-gradient-to-r from-cyan-500 to-primary-600 hover:from-cyan-400 hover:to-primary-500 text-white rounded-xl font-bold text-sm shadow-lg shadow-cyan-500/25 transition-all flex items-center justify-center gap-2">
                                <span>{{ __('Post RFQ & Get Factory Quotes') }}</span>
                                <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                            </button>
                        </form>
                        
                        <div class="mt-4 text-center">
                            <span class="text-[11px] text-slate-400 flex items-center justify-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                                {{ __('100% Confidential RFQ • No spam guarantee') }}
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- SECTION 5: CURATED OPTICAL PRODUCTS (Specification-Rich Catalog)          -->
    <!-- ========================================================================= -->
    <section class="py-16 lg:py-24 bg-white" x-data="{ selectedCategory: 'all' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-10">
                <div>
                    <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-bold uppercase tracking-wider mb-3 inline-block">{{ __('Curated Optical Catalog') }}</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">{{ __('Certified Optical Products & Components') }}</h2>
                    <p class="text-sm sm:text-base text-slate-600 mt-2 max-w-2xl">{{ __('Hand-inspected products from audited manufacturers with complete specification sheets.') }}</p>
                </div>
                
                <!-- Category Switcher Tabs -->
                <div class="mt-6 md:mt-0 flex items-center gap-1.5 p-1 bg-slate-100 rounded-2xl overflow-x-auto text-xs font-bold text-slate-600">
                    <button type="button" 
                            @click="selectedCategory = 'all'" 
                            :class="selectedCategory === 'all' ? 'bg-white text-slate-900 shadow-sm' : 'hover:text-slate-900'"
                            class="px-3.5 py-2 rounded-xl transition-all whitespace-nowrap">
                        {{ __('All Products') }}
                    </button>
                    @foreach($categories as $cat)
                        <button type="button" 
                                @click="selectedCategory = '{{ $cat->slug }}'" 
                                :class="selectedCategory === '{{ $cat->slug }}' ? 'bg-white text-slate-900 shadow-sm' : 'hover:text-slate-900'"
                                class="px-3.5 py-2 rounded-xl transition-all whitespace-nowrap">
                            {{ $cat->getTranslation('name', app()->getLocale()) }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Product Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <div x-show="selectedCategory === 'all' || selectedCategory === '{{ $product->category->slug ?? '' }}'"
                         x-transition
                         class="group bg-white border border-slate-200 rounded-3xl overflow-hidden hover:border-primary-500 hover:shadow-xl transition-all duration-300 flex flex-col h-full">
                        
                        <!-- Image Container with Badges -->
                        <div class="aspect-square bg-slate-50 relative overflow-hidden border-b border-slate-100">
                            @php
                                $imgUrl = $product->image ? asset('uploads/' . $product->image) : ($product->getFirstMediaUrl('images') ?: asset('uploads/products/boris_optical_lens.jpg'));
                            @endphp
                            <img src="{{ $imgUrl }}" alt="{{ $product->getTranslation('name', app()->getLocale()) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            
                            <!-- Category Badge -->
                            <div class="absolute top-3 left-3 rtl:left-auto rtl:right-3 flex flex-col gap-1.5">
                                <span class="bg-white/95 backdrop-blur-md px-2.5 py-1 text-[10px] font-extrabold text-slate-900 rounded-lg shadow-sm border border-slate-200/80 uppercase tracking-wider">
                                    {{ $product->category->getTranslation('name', app()->getLocale()) ?? 'Optics' }}
                                </span>
                                @if($product->is_featured)
                                    <span class="bg-amber-500 text-white px-2 py-0.5 text-[9px] font-extrabold rounded-md shadow-sm uppercase tracking-wider">
                                        ★ {{ __('Featured') }}
                                    </span>
                                @endif
                            </div>

                            <!-- Country Origin Pill -->
                            <div class="absolute top-3 right-3 rtl:right-auto rtl:left-3">
                                <span class="bg-black/60 backdrop-blur-md px-2 py-1 text-[10px] font-bold text-white rounded-lg border border-white/20 flex items-center gap-1">
                                    <span>{{ $product->factory->country->name ?? 'Global' }}</span>
                                </span>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-5 flex-1 flex flex-col text-start">
                            
                            <!-- Factory Attribution with Verified Check -->
                            <div class="flex items-center gap-1.5 mb-2">
                                <svg class="w-3.5 h-3.5 text-primary-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                <span class="text-xs font-semibold text-slate-500 truncate">{{ $product->factory->official_name ?? 'Audited Optical Lab' }}</span>
                            </div>

                            <!-- Product Title -->
                            <h3 class="font-extrabold text-slate-900 text-sm mb-3 group-hover:text-primary-600 transition-colors line-clamp-2">
                                <a href="{{ route('marketplace.product.show', $product) }}">
                                    {{ $product->getTranslation('name', app()->getLocale()) }}
                                </a>
                            </h3>

                            <!-- Optical Specification Chips -->
                            @if(!empty($product->specifications) && is_array($product->specifications))
                                <div class="flex flex-wrap gap-1 mb-4">
                                    @php
                                        $chips = [];
                                        if(!empty($product->specifications['Material'])) $chips[] = $product->specifications['Material'];
                                        if(!empty($product->specifications['Coating'])) $chips[] = $product->specifications['Coating'];
                                        if(!empty($product->specifications['Abbe Value'])) $chips[] = 'Abbe ' . $product->specifications['Abbe Value'];
                                        if(empty($chips) && !empty($product->specifications['Transmittance'])) $chips[] = 'T ' . $product->specifications['Transmittance'];
                                    @endphp
                                    @foreach(array_slice($chips, 0, 3) as $chip)
                                        <span class="px-2 py-0.5 rounded bg-slate-100 text-[10px] font-bold text-slate-600">{{ $chip }}</span>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Price & Action Footer -->
                            <div class="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between">
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">{{ __('Starting Price') }}</span>
                                    <span class="text-base font-extrabold text-slate-900">{{ $product->currency }} {{ number_format($product->starting_price, 2) }}</span>
                                </div>
                                <a href="{{ route('marketplace.product.show', $product) }}" class="p-2.5 rounded-xl bg-slate-50 group-hover:bg-primary-600 group-hover:text-white text-slate-700 transition-colors">
                                    <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                </a>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

            <!-- View More Button -->
            <div class="mt-12 text-center">
                <a href="{{ route('marketplace.index') }}" class="inline-flex items-center gap-2 px-8 py-3.5 bg-slate-900 hover:bg-black text-white rounded-xl text-sm font-bold shadow-sm transition-all">
                    <span>{{ __('Explore Complete Product Catalog') }}</span>
                    <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                </a>
            </div>

        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- SECTION 6: VERIFIED FACTORY SHOWROOMS (Made-in-China Style Audited Labs)  -->
    <!-- ========================================================================= -->
    <section class="py-16 lg:py-24 bg-slate-50 border-t border-b border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
                <div>
                    <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wider mb-3 inline-block">{{ __('Verified Optical Showrooms') }}</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">{{ __('Direct Factory Showrooms & Production Cleanrooms') }}</h2>
                    <p class="text-sm sm:text-base text-slate-600 mt-2 max-w-2xl">{{ __('Connect directly with certified manufacturers featuring audited cleanrooms, ISO/CE compliance, and export facilities.') }}</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('factories.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-primary-600 hover:text-primary-700 transition-colors">
                        <span>{{ __('Browse All Factories') }}</span>
                        <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                @foreach($featuredFactories as $factory)
                    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-xl hover:border-emerald-500/50 transition-all duration-300 flex flex-col">
                        
                        <!-- Factory Banner & Verification Badges -->
                        <div class="aspect-[16/8] relative overflow-hidden bg-slate-900">
                            @php
                                $bannerUrl = $factory->banner ? asset('uploads/' . $factory->banner) : asset('uploads/factories/boris_banner.jpg');
                            @endphp
                            <img src="{{ $bannerUrl }}" alt="{{ $factory->official_name }}" class="w-full h-full object-cover opacity-85">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/30 to-transparent"></div>
                            
                            <!-- Badges -->
                            <div class="absolute top-3 left-3 rtl:left-auto rtl:right-3 flex items-center gap-1.5">
                                <span class="bg-amber-500 text-white text-[10px] font-black px-2 py-0.5 rounded shadow-sm flex items-center gap-1">
                                    ★ {{ __('Diamond Member') }}
                                </span>
                                <span class="bg-emerald-600 text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-sm flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    {{ __('Audited Supplier') }}
                                </span>
                            </div>

                            <div class="absolute bottom-3 right-3 rtl:right-auto rtl:left-3 text-white text-xs font-bold flex items-center gap-1 bg-black/50 backdrop-blur-md px-2 py-1 rounded-lg">
                                <span class="text-amber-400">★ 5.0</span>
                                <span class="text-slate-300">({{ __('100% On-time') }})</span>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6 flex-1 flex flex-col text-start">
                            
                            <h3 class="text-lg font-extrabold text-slate-900 mb-1 leading-snug">
                                <a href="{{ route('factories.show', $factory) }}" class="hover:text-primary-600 transition-colors">
                                    {{ $factory->official_name }}
                                </a>
                            </h3>

                            <div class="flex items-center gap-2 text-xs text-slate-500 mb-4">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /></svg>
                                <span class="font-medium">{{ $factory->country->name ?? 'Global' }}</span>
                                <span class="text-slate-300">•</span>
                                <span>{{ $factory->business_type ?? __('Manufacturer / Factory') }}</span>
                            </div>

                            <!-- Capability Chips -->
                            @if(!empty($factory->solutions))
                                <div class="flex flex-wrap gap-1.5 mb-6 text-[11px] font-medium text-slate-600">
                                    @foreach(array_slice($factory->solutions, 0, 3) as $sol)
                                        <span class="px-2.5 py-1 bg-slate-100 rounded-lg">{{ $sol }}</span>
                                    @endforeach
                                </div>
                            @endif

                            <div class="mt-auto pt-4 border-t border-slate-100 flex items-center gap-3">
                                <a href="{{ route('factories.show', $factory) }}" class="flex-1 py-2.5 px-4 bg-slate-900 hover:bg-black text-white rounded-xl text-xs font-bold text-center transition-colors">
                                    {{ __('Visit Factory Showroom') }}
                                </a>
                                @if(!empty($factory->vr_url))
                                    <a href="{{ $factory->vr_url }}" target="_blank" rel="noopener noreferrer" class="p-2.5 rounded-xl bg-primary-50 text-primary-600 hover:bg-primary-100 transition-colors" title="{{ __('360° VR Tour') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                    </a>
                                @endif
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- SECTION 7: LIVE STOCK DEALS (Limited-Time Inventory Clearance)            -->
    <!-- ========================================================================= -->
    @if($stockOffers->isNotEmpty())
    <section class="py-16 lg:py-24 bg-white border-b border-slate-200">
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
                    <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($stockOffers as $offer)
                    <div class="bg-white border border-red-100 rounded-3xl overflow-hidden relative shadow-sm hover:shadow-xl transition-all flex flex-col h-full">
                        <div class="absolute top-3 right-3 rtl:right-auto rtl:left-3 bg-red-500 text-white text-[10px] font-bold px-2.5 py-1 rounded-lg shadow-sm z-10 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            {{ \Carbon\Carbon::parse($offer->ends_at)->diffForHumans() }}
                        </div>
                        <div class="aspect-[4/3] bg-slate-100 relative">
                            <img src="{{ collect($offer->images)->first() ?: asset('uploads/products/boris_optical_lens.jpg') }}" class="w-full h-full object-cover">
                        </div>
                        <div class="p-5 flex-1 flex flex-col text-start">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">{{ $offer->category->getTranslation('name', app()->getLocale()) }}</span>
                            <h3 class="font-bold text-slate-900 text-sm mb-4 line-clamp-2">{{ $offer->getTranslation('title', app()->getLocale()) }}</h3>
                            
                            <div class="mt-auto">
                                <div class="flex items-end justify-between mb-4">
                                    <div>
                                        <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold">{{ __('Price') }}</p>
                                        <p class="text-xl font-bold text-red-600">${{ number_format($offer->price, 2) }} <span class="text-xs text-slate-500 font-medium lowercase">/ {{ __('unit') }}</span></p>
                                    </div>
                                    <div class="text-right rtl:text-left">
                                        <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold">{{ __('MOQ') }}</p>
                                        <p class="text-sm font-bold text-slate-900">{{ $offer->moq ?? '500 pcs' }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('stock-offers.show', $offer) }}" class="block w-full py-2.5 bg-slate-900 text-white text-xs font-bold uppercase tracking-wider rounded-xl text-center hover:bg-black transition-colors">
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

    <!-- ========================================================================= -->
    <!-- SECTION 8: WHY OPTICAL PROFESSIONALS CHOOSE OPTICB2B                      -->
    <!-- ========================================================================= -->
    <section class="py-16 lg:py-24 bg-slate-50 border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="px-3.5 py-1.5 rounded-full bg-primary-100 text-primary-700 text-xs font-bold uppercase tracking-wider mb-3 inline-block">
                    {{ __('Why Optical Professionals Choose OpticB2B') }}
                </span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight">
                    {{ __('Built Specifically for the Precision Eyewear Industry') }}
                </h2>
                <p class="text-slate-600 text-sm sm:text-base mt-3">
                    {{ __('Standard B2B marketplaces don\'t understand optical parameters. OpticB2B delivers native prescription and laboratory specification tracking.') }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                
                <!-- Pillar 1 -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm text-start">
                    <div class="w-12 h-12 rounded-2xl bg-primary-50 text-primary-600 flex items-center justify-center mb-5">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-base mb-2">{{ __('Direct-From-Lab Pricing') }}</h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        {{ __('Cut out 3rd party distributors and brokers. Save up to 40% on optical blanks and finished lenses.') }}
                    </p>
                </div>

                <!-- Pillar 2 -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm text-start">
                    <div class="w-12 h-12 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center mb-5">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-base mb-2">{{ __('Complete Specification Transparency') }}</h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        {{ __('Every lens listing features certified Abbe values, refractive index, UVA/UVB blocking, and coating durability.') }}
                    </p>
                </div>

                <!-- Pillar 3 -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm text-start">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-5">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-base mb-2">{{ __('Fast Sample Dispatch') }}</h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        {{ __('Request production-grade lens and frame samples with air express delivery prior to volume commitments.') }}
                    </p>
                </div>

                <!-- Pillar 4 -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm text-start">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-5">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" /></svg>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-base mb-2">{{ __('Multilingual Trade Hub') }}</h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        {{ __('Seamless real-time translation across English, Arabic, Turkish, and Chinese for frictionless negotiation.') }}
                    </p>
                </div>

            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- SECTION 9: DUAL CONVERSION CTA (For Optical Buyers & Optical Factories)   -->
    <!-- ========================================================================= -->
    <section class="py-16 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <!-- Card 1: For Buyers -->
                <div class="rounded-3xl p-8 sm:p-10 bg-gradient-to-br from-slate-900 via-slate-950 to-primary-950 text-white relative overflow-hidden flex flex-col justify-between">
                    <div class="relative z-10">
                        <span class="px-3 py-1 rounded-full bg-white/10 text-cyan-300 text-xs font-bold uppercase tracking-wider mb-4 inline-block">{{ __('For Buyers & Labs') }}</span>
                        <h3 class="text-2xl sm:text-3xl font-extrabold mb-3">{{ __('Start Sourcing Optical Inventory Direct') }}</h3>
                        <p class="text-slate-300 text-xs sm:text-sm leading-relaxed mb-6">
                            {{ __('Access direct wholesale pricing on ophthalmic lenses, frames, and machinery from verified global manufacturers.') }}
                        </p>
                    </div>
                    <div class="relative z-10 flex flex-wrap gap-3">
                        <a href="{{ route('marketplace.index') }}" class="px-6 py-3 bg-primary-600 hover:bg-primary-500 text-white rounded-xl text-xs sm:text-sm font-bold shadow-lg transition-all">
                            {{ __('Search Products') }}
                        </a>
                        <a href="{{ route('sourcing.index') }}" class="px-6 py-3 bg-white/10 hover:bg-white/20 text-white border border-white/20 rounded-xl text-xs sm:text-sm font-bold transition-all">
                            {{ __('Post Sourcing Request') }}
                        </a>
                    </div>
                </div>

                <!-- Card 2: For Manufacturers -->
                <div class="rounded-3xl p-8 sm:p-10 bg-primary-50 border border-primary-100 text-start flex flex-col justify-between">
                    <div>
                        <span class="px-3 py-1 rounded-full bg-primary-100 text-primary-700 text-xs font-bold uppercase tracking-wider mb-4 inline-block">{{ __('For Manufacturers') }}</span>
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mb-3">{{ __('Expand Your Reach to International Buyers') }}</h3>
                        <p class="text-slate-600 text-xs sm:text-sm leading-relaxed mb-6">
                            {{ __('List your factory showroom, showcase your product catalog professionally, and receive qualified sourcing requests from global optical buyers.') }}
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('filament.factory.auth.register') }}" class="px-6 py-3 bg-slate-900 hover:bg-black text-white rounded-xl text-xs sm:text-sm font-bold transition-all">
                            {{ __('List Your Factory') }}
                        </a>
                        <a href="{{ route('factories.index') }}" class="px-6 py-3 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl text-xs sm:text-sm font-bold transition-all">
                            {{ __('Explore Showrooms') }}
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection
