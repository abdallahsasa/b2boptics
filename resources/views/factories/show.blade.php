@extends('layouts.app')

@section('title', $factory->official_name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Banner -->
    <div class="h-64 lg:h-96 bg-slate-200 rounded-[3rem] overflow-hidden relative shadow-sm">
        <img src="{{ $factory->getFirstMediaUrl('banners') ?: 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?q=80&w=1600&auto=format&fit=crop' }}" 
            class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
        
        <div class="absolute -bottom-1 left-8 lg:left-12 translate-y-1/2 flex items-end gap-6">
            <div class="w-32 h-32 lg:w-48 lg:h-48 bg-white p-2 rounded-[2rem] shadow-xl border border-slate-100 overflow-hidden">
                <img src="{{ $factory->getFirstMediaUrl('logos') ?: 'https://ui-avatars.com/api/?name=' . urlencode($factory->official_name) . '&size=256' }}" 
                    class="w-full h-full object-cover rounded-[1.5rem]">
            </div>
            <div class="pb-6 hidden sm:block">
                <h1 class="text-3xl lg:text-4xl font-bold text-white drop-shadow-md">{{ $factory->official_name }}</h1>
                <div class="flex items-center gap-3 text-white/90 mt-2">
                    <span class="flex items-center gap-1.5 text-sm font-semibold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        {{ $factory->country->name }}
                    </span>
                    @if($factory->is_verified)
                    <span class="flex items-center gap-1.5 px-3 py-1 bg-green-500 text-white text-[10px] font-bold rounded-full uppercase tracking-widest">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                        {{ __('Verified Factory') }}
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-12 mt-24 lg:mt-32">
        <!-- Factory Bio -->
        <div class="lg:w-1/3">
            <div class="sticky top-24">
                <h2 class="text-2xl font-bold text-slate-900 mb-6">{{ __('About Factory') }}</h2>
                <div class="prose prose-slate prose-sm text-slate-600 leading-relaxed mb-8">
                    {!! nl2br(e($factory->getTranslation('description', app()->getLocale()))) !!}
                </div>

                <div class="space-y-4 mb-10">
                    <div class="flex items-center gap-4 p-4 bg-white rounded-2xl border border-slate-100 shadow-sm">
                        <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-primary-600">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest">{{ __('Official Email') }}</p>
                            <p class="text-sm font-bold text-slate-900">{{ $factory->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 p-4 bg-white rounded-2xl border border-slate-100 shadow-sm">
                        <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-primary-600">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest">{{ __('Phone') }}</p>
                            <p class="text-sm font-bold text-slate-900">{{ $factory->phone }}</p>
                        </div>
                    </div>
                </div>

                <button class="w-full py-4 bg-slate-900 text-white rounded-2xl font-bold hover:bg-black transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                    {{ __('Contact Factory') }}
                </button>
            </div>
        </div>

        <!-- Factory Products -->
        <div class="flex-1">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-bold text-slate-900">{{ __('Products Catalog') }}</h2>
                <span class="text-sm text-slate-500 font-medium">{{ __(':count Products', ['count' => $factory->products->count()]) }}</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                @foreach($factory->products as $product)
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden card-hover group flex">
                    <a href="{{ route('marketplace.product.show', $product) }}" class="w-32 sm:w-40 aspect-square overflow-hidden bg-slate-50 shrink-0">
                         <img src="{{ $product->getFirstMediaUrl('images') ?: 'https://images.unsplash.com/photo-1574258495973-f010dfbb5371?q=80&w=400&auto=format&fit=crop' }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    </a>
                    <div class="p-6 flex flex-col justify-center">
                        <p class="text-[10px] font-bold text-primary-600 uppercase tracking-widest mb-1">{{ $product->category->getTranslation('name', app()->getLocale()) }}</p>
                        <h3 class="font-bold text-slate-900 group-hover:text-primary-600 transition-colors mb-2">
                            <a href="{{ route('marketplace.product.show', $product) }}">{{ $product->getTranslation('name', app()->getLocale()) }}</a>
                        </h3>
                        <p class="text-sm font-bold text-slate-900">{{ $product->currency }} {{ number_format($product->starting_price, 2) }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
