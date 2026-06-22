@extends('layouts.app')

@section('title', $product->getTranslation('name', app()->getLocale()))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Breadcrumbs -->
    <nav class="flex text-sm text-slate-400 mb-8 gap-2">
        <a href="{{ route('home') }}" class="hover:text-primary-600">{{ __('Home') }}</a>
        <span>/</span>
        <a href="{{ route('marketplace.index') }}" class="hover:text-primary-600">{{ __('Marketplace') }}</a>
        <span>/</span>
        <span class="text-slate-900 font-medium">{{ $product->getTranslation('name', app()->getLocale()) }}</span>
    </nav>

    <div class="flex flex-col lg:flex-row gap-12">
        <!-- Image Gallery -->
        <div class="flex-1">
            <div class="bg-white rounded-[2rem] overflow-hidden border border-slate-100 shadow-sm p-4">
                <div class="aspect-square bg-slate-50 rounded-[1.5rem] overflow-hidden">
                    <img src="{{ $product->getFirstMediaUrl('images') ?: 'https://images.unsplash.com/photo-1574258495973-f010dfbb5371?q=80&w=1200&auto=format&fit=crop' }}" 
                        alt="{{ $product->getTranslation('name', app()->getLocale()) }}" class="w-full h-full object-cover">
                </div>
            </div>
        </div>

        <!-- Product Info -->
        <div class="lg:w-1/2">
            <div class="sticky top-24">
                <div class="flex items-center gap-3 mb-6">
                    <span class="px-4 py-1.5 bg-primary-50 text-primary-700 text-xs font-bold rounded-full uppercase tracking-wider">{{ $product->category->getTranslation('name', app()->getLocale()) }}</span>
                    <span class="flex items-center gap-1.5 text-xs font-bold text-slate-400 uppercase tracking-widest">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        {{ $product->factory->country->name ?? 'Global' }}
                    </span>
                </div>

                <h1 class="text-4xl lg:text-5xl font-bold text-slate-900 mb-4">{{ $product->getTranslation('name', app()->getLocale()) }}</h1>
                
                @if($product->factory)
                <div class="flex items-center gap-4 mb-8 p-6 bg-white rounded-3xl border border-slate-100 shadow-sm">
                    <div class="w-14 h-14 bg-slate-100 rounded-2xl overflow-hidden flex-shrink-0">
                         <img src="{{ $product->factory->getFirstMediaUrl('logos') ?: 'https://ui-avatars.com/api/?name=' . urlencode($product->factory->official_name) }}" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest mb-0.5">{{ __('Manufactured by') }}</p>
                        <a href="{{ route('factories.show', $product->factory) }}" class="text-lg font-bold text-slate-900 hover:text-primary-600 transition-colors">{{ $product->factory->official_name }}</a>
                    </div>
                    <div class="ml-auto">
                         <div class="flex items-center gap-1 text-amber-400">
                             <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                             <span class="text-sm font-bold text-slate-900">4.9</span>
                         </div>
                    </div>
                </div>
                @endif

                <div class="mb-10">
                    <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest mb-2">{{ __('Pricing') }}</p>
                    <div class="flex items-baseline gap-2">
                        <span class="text-4xl font-bold text-slate-900">{{ $product->currency }} {{ number_format($product->starting_price, 2) }}</span>
                        <span class="text-slate-400 font-medium">{{ __('starting price') }}</span>
                    </div>
                </div>

                <div class="prose prose-slate prose-sm mb-12">
                    <p class="text-slate-600 leading-relaxed">{!! nl2br(e($product->getTranslation('description', app()->getLocale()))) !!}</p>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-10">
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest mb-1">{{ __('Subcategory') }}</p>
                        <p class="font-bold text-slate-900">{{ $product->subcategory->getTranslation('name', app()->getLocale()) ?? 'N/A' }}</p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest mb-1">{{ __('Origin') }}</p>
                        <p class="font-bold text-slate-900">{{ $product->factory->country->name ?? 'Global' }}</p>
                    </div>
                </div>

                <button class="w-full py-5 bg-primary-600 text-white rounded-3xl font-bold text-lg shadow-xl shadow-primary-600/30 hover:bg-primary-700 hover:scale-[1.02] transition-all flex items-center justify-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                    {{ __('Request a Quote') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Similar Products -->
    <div class="mt-32">
        <h2 class="text-3xl font-bold text-slate-900 mb-10">{{ __('Similar Products') }}</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($similarProducts as $similar)
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden card-hover group">
                <a href="{{ route('marketplace.product.show', $similar) }}" class="block aspect-square overflow-hidden relative bg-slate-100">
                    <img src="{{ $similar->getFirstMediaUrl('images') ?: 'https://images.unsplash.com/photo-1574258495973-f010dfbb5371?q=80&w=600&auto=format&fit=crop' }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </a>
                <div class="p-6">
                    <h3 class="font-bold text-slate-900 truncate"><a href="{{ route('marketplace.product.show', $similar) }}">{{ $similar->getTranslation('name', app()->getLocale()) }}</a></h3>
                    <p class="text-primary-600 font-bold mt-1">{{ $similar->currency }} {{ number_format($similar->starting_price, 2) }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
