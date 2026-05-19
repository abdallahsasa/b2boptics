@extends('layouts.app')

@section('title', 'Stock Deals')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-12">
        <h1 class="text-4xl font-bold text-slate-900">{{ __('Stock Clearance') }} <span class="text-primary-600">{{ __('Deals') }}</span></h1>
        <p class="text-slate-500 mt-3 text-lg">{{ __('Limited-time offers on ready-to-ship inventory directly from manufacturers.') }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($offers as $offer)
        <div class="bg-white rounded-[2rem] border-2 border-primary-100 shadow-xl overflow-hidden card-hover group relative">
            <!-- Countdown Placeholder -->
            <div class="absolute top-4 right-4 z-10 px-4 py-2 bg-red-600 text-white text-[10px] font-bold rounded-full uppercase tracking-widest animate-pulse">
                {{ __('Ends in 2 days') }}
            </div>

            <div class="aspect-video overflow-hidden bg-slate-100">
                <img src="{{ $offer->image ?: 'https://images.unsplash.com/photo-1591076482161-42ce6da69f67?q=80&w=800&auto=format&fit=crop' }}" 
                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
            </div>

            <div class="p-8">
                <div class="flex items-center gap-2 mb-4">
                    <span class="px-3 py-1 bg-primary-50 text-primary-700 text-[10px] font-bold rounded-full uppercase tracking-wider">{{ $offer->category->getTranslation('name', app()->getLocale()) }}</span>
                    <span class="w-1.5 h-1.5 bg-slate-200 rounded-full"></span>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ $offer->factory->country->name ?? 'Global' }}</span>
                </div>

                <h3 class="text-2xl font-bold text-slate-900 mb-3 group-hover:text-primary-600 transition-colors">{{ $offer->getTranslation('title', app()->getLocale()) }}</h3>
                <p class="text-slate-500 text-sm line-clamp-2 mb-6">{{ $offer->getTranslation('description', app()->getLocale()) }}</p>

                <div class="flex items-end justify-between pt-6 border-t border-slate-50">
                    <div>
                        <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest mb-1">{{ __('Deal Price') }}</p>
                        <p class="text-3xl font-bold text-primary-900">{{ $offer->currency }} {{ number_format($offer->price, 2) }} <span class="text-sm font-medium text-slate-400">/{{ __('unit') }}</span></p>
                    </div>
                    <a href="{{ route('stock-offers.show', $offer) }}" class="inline-block px-6 py-3 bg-primary-600 text-white rounded-xl font-bold hover:bg-primary-700 transition-all shadow-lg shadow-primary-600/20 text-center">{{ __('Grab Deal') }}</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 text-center bg-white rounded-[3rem] border border-slate-100">
             <p class="text-slate-500">No active clearance deals at the moment.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-12">
        {{ $offers->links() }}
    </div>
</div>
@endsection
