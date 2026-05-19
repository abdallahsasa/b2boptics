@extends('layouts.app')

@section('title', $buyerRequest->title)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Breadcrumbs -->
    <nav class="flex text-sm text-slate-400 mb-8 gap-2">
        <a href="{{ route('home') }}" class="hover:text-primary-600">{{ __('Home') }}</a>
        <span>/</span>
        <a href="{{ route('sourcing.index') }}" class="hover:text-primary-600">{{ __('Find Deal') }}</a>
        <span>/</span>
        <span class="text-slate-900 font-medium">{{ __('Request') }} #{{ str_pad($buyerRequest->id, 5, '0', STR_PAD_LEFT) }}</span>
    </nav>

    @if(session('success'))
        <div class="mb-8 bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl flex items-center gap-4">
            <svg class="w-6 h-6 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-8 lg:p-12">
        <div class="flex items-center gap-3 mb-6">
            <span class="px-4 py-1.5 bg-primary-50 text-primary-700 text-xs font-bold rounded-full uppercase tracking-wider">{{ $buyerRequest->category->getTranslation('name', app()->getLocale()) }}</span>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ $buyerRequest->created_at->diffForHumans() }}</span>
            
            @if($buyerRequest->status === 'pending')
                <span class="ml-auto px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-bold rounded-full uppercase">{{ __('Pending Approval') }}</span>
            @elseif($buyerRequest->status === 'approved')
                <span class="ml-auto px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full uppercase">{{ __('Active') }}</span>
            @endif
        </div>

        <h1 class="text-3xl lg:text-4xl font-bold text-slate-900 mb-8">{{ $buyerRequest->title }}</h1>

        <div class="flex flex-wrap gap-8 pb-8 border-b border-slate-100 mb-8">
            <div>
                <p class="text-xs text-slate-500 uppercase tracking-widest font-bold mb-1">{{ __('Quantity Required') }}</p>
                <p class="text-xl font-bold text-slate-900">{{ $buyerRequest->quantity }}</p>
            </div>
            @if($buyerRequest->target_price)
            <div>
                <p class="text-xs text-slate-500 uppercase tracking-widest font-bold mb-1">{{ __('Target Price') }}</p>
                <p class="text-xl font-bold text-primary-600">{{ $buyerRequest->currency }} {{ number_format($buyerRequest->target_price, 2) }}</p>
            </div>
            @endif
        </div>

        <div class="prose prose-slate prose-sm max-w-none mb-10">
            <h3 class="text-lg font-bold text-slate-900 mb-4">{{ __('Detailed Requirements') }}</h3>
            <div class="text-slate-600 leading-relaxed whitespace-pre-wrap">{{ $buyerRequest->description }}</div>
        </div>

        @auth
            <!-- Factory Owner / Supplier View -->
            <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200 text-center">
                <h4 class="font-bold text-slate-900 mb-2">{{ __('Can you fulfill this request?') }}</h4>
                <p class="text-sm text-slate-500 mb-4">{{ __('Submit your best offer to win this business.') }}</p>
                <button class="px-8 py-3 bg-primary-600 text-white rounded-xl font-bold hover:bg-primary-700 transition-colors">
                    {{ __('Submit Offer') }}
                </button>
            </div>
        @else
            <!-- Guest View -->
            <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200 text-center">
                <p class="text-sm text-slate-600 mb-4">{{ __('Are you a manufacturer? Log in to your factory account to view contact details and submit an offer.') }}</p>
                <a href="{{ route('login') }}" class="inline-block px-8 py-3 bg-slate-900 text-white rounded-xl font-bold hover:bg-black transition-colors">
                    {{ __('Login to Submit Offer') }}
                </a>
            </div>
        @endauth
    </div>
</div>
@endsection
