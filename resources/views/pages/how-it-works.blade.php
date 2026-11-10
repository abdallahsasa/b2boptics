@extends('layouts.app')

@section('title', __('How it Works') . ' | OpticB2B')
@section('meta_title', __('How it Works - B2B Optical Sourcing & Supplier Guide | OpticB2B'))
@section('meta_description', __('Learn how optical buyers, retailers, factories, brands, and wholesalers connect, trade, and grow on OpticB2B with zero commission fees.'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
    <!-- Hero Header -->
    <div class="text-center max-w-3xl mx-auto mb-16">
        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-primary-50 text-primary-700 text-xs font-bold uppercase tracking-wider border border-primary-100 mb-4">
            {{ __('Platform Guide') }}
        </span>
        <h1 class="text-4xl sm:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight">
            {{ __('How OpticB2B Works') }}
        </h1>
        <p class="mt-4 text-lg text-slate-600 leading-relaxed">
            {{ __('OpticB2B connects optical retailers and wholesale buyers directly with verified factories, optical brands, and wholesalers worldwide—with zero middleman markups.') }}
        </p>
    </div>

    <!-- For Buyers Section -->
    <div class="mb-20">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10 gap-4">
            <div>
                <span class="text-xs font-extrabold uppercase tracking-widest text-primary-600">{{ __('For Buyers & Optical Retailers') }}</span>
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 mt-1">{{ __('Source Optical Products in 3 Simple Steps') }}</h2>
            </div>
            <a href="{{ route('marketplace.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-primary-600 hover:text-primary-700">
                <span>{{ __('Browse Product Catalog') }}</span>
                <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm relative">
                <div class="w-12 h-12 rounded-2xl bg-primary-600 text-white font-extrabold text-lg flex items-center justify-center mb-6 shadow-lg shadow-primary-600/25">1</div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">{{ __('Discover Products & Suppliers') }}</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    {{ __('Explore thousands of optical lenses, frames, sunglasses, and lab machines, or filter our verified directory by Factory, Brand, or Wholesaler.') }}
                </p>
            </div>

            <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm relative">
                <div class="w-12 h-12 rounded-2xl bg-primary-600 text-white font-extrabold text-lg flex items-center justify-center mb-6 shadow-lg shadow-primary-600/25">2</div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">{{ __('Message Directly or Post a Tender') }}</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    {{ __('Contact suppliers directly from any product or showroom page, or publish a custom buying tender (RFQ) to receive competitive factory quotes.') }}
                </p>
            </div>

            <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm relative">
                <div class="w-12 h-12 rounded-2xl bg-primary-600 text-white font-extrabold text-lg flex items-center justify-center mb-6 shadow-lg shadow-primary-600/25">3</div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">{{ __('Negotiate & Trade Direct') }}</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    {{ __('Discuss MOQ, OEM/ODM branding, samples, and shipping terms directly with the supplier—and claim ready-to-ship stock clearance deals.') }}
                </p>
            </div>
        </div>
    </div>

    <!-- For Suppliers Section -->
    <div class="bg-slate-900 text-white rounded-[2.5rem] p-8 sm:p-12 lg:p-16 mb-16">
        <div class="max-w-3xl mb-12">
            <span class="inline-flex items-center px-3.5 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-bold uppercase tracking-wider border border-emerald-400/30 mb-4">
                {{ __('For Factories, Brands & Wholesalers') }}
            </span>
            <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight">
                {{ __('Grow Your Global B2B Exports') }}
            </h2>
            <p class="text-slate-300 mt-3 text-base leading-relaxed">
                {{ __('Every verified supplier starts with our Free Starter Package ($0)—including 10 product listings and up to 10 high-resolution photos per product.') }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <div class="bg-slate-800/80 border border-slate-700/70 rounded-3xl p-7">
                <div class="text-emerald-400 font-black text-sm uppercase tracking-wider mb-2">{{ __('Step 1') }}</div>
                <h3 class="text-lg font-bold text-white mb-2">{{ __('Register as Factory, Brand, or Wholesaler') }}</h3>
                <p class="text-slate-300 text-sm leading-relaxed">
                    {{ __('Create your supplier account, select your business type (Factory, Brand, or Wholesaler), and complete your digital showroom profile.') }}
                </p>
            </div>

            <div class="bg-slate-800/80 border border-slate-700/70 rounded-3xl p-7">
                <div class="text-emerald-400 font-black text-sm uppercase tracking-wider mb-2">{{ __('Step 2') }}</div>
                <h3 class="text-lg font-bold text-white mb-2">{{ __('Publish Products & Stock Deals') }}</h3>
                <p class="text-slate-300 text-sm leading-relaxed">
                    {{ __('Upload your product catalog with multi-image galleries, technical specifications, and ready-to-ship stock offers in 9 languages.') }}
                </p>
            </div>

            <div class="bg-slate-800/80 border border-slate-700/70 rounded-3xl p-7">
                <div class="text-emerald-400 font-black text-sm uppercase tracking-wider mb-2">{{ __('Step 3') }}</div>
                <h3 class="text-lg font-bold text-white mb-2">{{ __('Receive Instant Inquiries & Quote RFQs') }}</h3>
                <p class="text-slate-300 text-sm leading-relaxed">
                    {{ __('Get instant email notifications when buyers message you, reply in real time from your portal, and submit quotes on global buyer tenders.') }}
                </p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-4">
            <a href="{{ route('filament.factory.auth.register') }}" class="px-7 py-3.5 bg-primary-600 hover:bg-primary-500 text-white font-bold rounded-2xl transition-all text-sm shadow-lg shadow-primary-600/30">
                {{ __('Register as Supplier for Free') }}
            </a>
            <a href="{{ route('packages.index') }}" class="px-7 py-3.5 bg-white/10 hover:bg-white/20 text-white font-bold rounded-2xl transition-all text-sm border border-white/15">
                {{ __('View Supplier Packages') }}
            </a>
        </div>
    </div>
</div>
@endsection
