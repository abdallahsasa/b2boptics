@extends('layouts.app')

@section('title', $stockOffer->getTranslation('title', app()->getLocale()))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Breadcrumbs -->
    <nav class="flex text-sm text-slate-400 mb-8 gap-2">
        <a href="{{ route('home') }}" class="hover:text-primary-600">{{ __('Home') }}</a>
        <span>/</span>
        <a href="{{ route('stock-offers.index') }}" class="hover:text-primary-600">{{ __('Stock Deals') }}</a>
        <span>/</span>
        <span class="text-slate-900 font-medium">{{ $stockOffer->getTranslation('title', app()->getLocale()) }}</span>
    </nav>

    @if(session('success'))
        <div class="mb-8 bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl flex items-center gap-4">
            <svg class="w-6 h-6 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-12">
        <!-- Deal Info -->
        <div class="flex-1">
            <div class="bg-white rounded-[2rem] overflow-hidden border border-slate-100 shadow-sm p-4 mb-8">
                <div class="aspect-[4/3] bg-slate-50 rounded-[1.5rem] overflow-hidden relative">
                    <img src="{{ collect($stockOffer->images)->first() ?: 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?q=80&w=1200&auto=format&fit=crop' }}" 
                        alt="{{ $stockOffer->getTranslation('title', app()->getLocale()) }}" class="w-full h-full object-cover">
                    <div class="absolute top-4 right-4 bg-red-500 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-lg flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        {{ __('Ends') }} {{ \Carbon\Carbon::parse($stockOffer->ends_at)->diffForHumans() }}
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <div class="flex items-center gap-3 mb-4">
                    <span class="px-4 py-1.5 bg-primary-50 text-primary-700 text-xs font-bold rounded-full uppercase tracking-wider">{{ $stockOffer->category->getTranslation('name', app()->getLocale()) }}</span>
                    @if($stockOffer->factory)
                    <span class="flex items-center gap-1.5 text-xs font-bold text-slate-400 uppercase tracking-widest">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        {{ $stockOffer->factory->country->name ?? 'Global' }}
                    </span>
                    @endif
                </div>

                <h1 class="text-3xl lg:text-4xl font-bold text-slate-900 mb-6">{{ $stockOffer->getTranslation('title', app()->getLocale()) }}</h1>
                
                <div class="flex items-center gap-12 pb-8 border-b border-slate-100 mb-8">
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-widest font-bold mb-1">{{ __('Deal Price') }}</p>
                        <p class="text-3xl font-bold text-red-600">${{ number_format($stockOffer->price, 2) }} <span class="text-base text-slate-500 font-medium lowercase">/ {{ __('unit') }}</span></p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-widest font-bold mb-1">{{ __('Minimum Order (MOQ)') }}</p>
                        <p class="text-xl font-bold text-slate-900">{{ $stockOffer->moq }} {{ __('units') }}</p>
                    </div>
                </div>

                <div class="prose prose-slate prose-sm">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">{{ __('Offer Details') }}</h3>
                    <p class="text-slate-600 leading-relaxed">{!! nl2br(e($stockOffer->getTranslation('description', app()->getLocale()))) !!}</p>
                </div>
            </div>
        </div>

        <!-- Request Form -->
        <div class="lg:w-1/3">
            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-xl p-8 sticky top-24">
                <h3 class="text-xl font-bold text-slate-900 mb-2">{{ __('Request This Deal') }}</h3>
                <p class="text-sm text-slate-500 mb-6">{{ __('Submit your request and our team will connect you directly with the manufacturer to finalize the deal.') }}</p>

                <form action="{{ route('stock-offers.request', $stockOffer) }}" method="POST">
                    @csrf
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">{{ __('Full Name') }} *</label>
                            <input type="text" name="name" required value="{{ old('name', auth()->user()->name ?? '') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-600 focus:border-primary-600 text-sm">
                            @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">{{ __('Email Address') }} *</label>
                            <input type="email" name="email" required value="{{ old('email', auth()->user()->email ?? '') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-600 focus:border-primary-600 text-sm">
                            @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">{{ __('Phone Number') }} *</label>
                            <input type="tel" name="phone" required value="{{ old('phone') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-600 focus:border-primary-600 text-sm">
                            @error('phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">{{ __('Company Name') }}</label>
                            <input type="text" name="company_name" value="{{ old('company_name') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-600 focus:border-primary-600 text-sm">
                            @error('company_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">{{ __('Quantity Needed') }} *</label>
                            <input type="number" name="quantity_requested" required min="{{ $stockOffer->moq ?? 1 }}" value="{{ old('quantity_requested', $stockOffer->moq) }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-600 focus:border-primary-600 text-sm">
                            <p class="text-[10px] text-slate-500 mt-1">{{ __('Minimum Order Quantity is') }} {{ $stockOffer->moq }}</p>
                            @error('quantity_requested') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">{{ __('Additional Message') }}</label>
                            <textarea name="message" rows="3" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-600 focus:border-primary-600 text-sm placeholder:text-slate-400" placeholder="{{ __('Any specific requirements, shipping questions, etc.') }}">{{ old('message') }}</textarea>
                            @error('message') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <button type="submit" class="w-full mt-8 py-4 bg-slate-900 text-white rounded-xl font-bold uppercase tracking-wider hover:bg-black transition-colors flex items-center justify-center gap-2">
                        {{ __('Submit Request') }}
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </button>
                    
                    <p class="text-center text-[10px] text-slate-400 mt-4">
                        <svg class="w-3 h-3 inline text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        {{ __('Your information is secure and will only be shared with the verified manufacturer.') }}
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
