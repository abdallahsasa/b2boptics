@extends('layouts.app')

@section('title', __('Find Your Best Deal'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="relative bg-primary-900 rounded-[3rem] overflow-hidden p-10 lg:p-20 mb-16 shadow-2xl">
        <div class="absolute inset-0 opacity-20">
             <img src="https://images.unsplash.com/photo-1556761175-5973dc0f32e7?q=80&w=1600&auto=format&fit=crop" class="w-full h-full object-cover">
        </div>
        <div class="relative z-10 text-center max-w-3xl mx-auto">
            <h1 class="text-4xl lg:text-6xl font-bold text-white mb-6 leading-tight">{{ __('Find Your') }} <span class="text-primary-400 text-italic">{{ __('Best Deal') }}</span> {{ __('Today') }}</h1>
            <p class="text-white/80 text-lg lg:text-xl mb-10 leading-relaxed">{{ __('Submit your request once, and let the world\'s leading optics manufacturers compete for your business.') }}</p>
            <a href="{{ route('sourcing.create') }}" class="inline-flex items-center gap-3 px-10 py-5 bg-white text-primary-900 rounded-full font-bold text-xl hover:bg-primary-50 transition-all shadow-xl shadow-black/20">
                {{ __('Post Your Request') }}
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
            </a>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row justify-between items-end mb-10 gap-4">
        <div>
            <h2 class="text-3xl font-bold text-slate-900">{{ __('Active Requests') }}</h2>
            <p class="text-slate-500 mt-2">{{ __('Latest sourcing opportunities for manufacturers') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6">
        @forelse($requests as $request)
        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm flex flex-col lg:flex-row lg:items-center gap-8 card-hover">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-4">
                    <span class="px-4 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-full uppercase tracking-widest">{{ $request->category->getTranslation('name', app()->getLocale()) }}</span>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ $request->created_at->diffForHumans() }}</span>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-3"><a href="{{ route('sourcing.show', $request) }}" class="hover:text-primary-600">{{ $request->title }}</a></h3>
                <p class="text-slate-600 line-clamp-2 max-w-2xl">{{ $request->description }}</p>
            </div>
            
            <div class="flex items-center gap-8 lg:text-right shrink-0">
                <div>
                    <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest mb-1">{{ __('Quantity') }}</p>
                    <p class="text-xl font-bold text-slate-900">{{ $request->quantity }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest mb-1">{{ __('Target Price') }}</p>
                    <p class="text-xl font-bold text-primary-600">{{ $request->currency }} {{ number_format($request->target_price, 0) }}</p>
                </div>
                <a href="{{ route('sourcing.show', $request) }}" class="px-8 py-4 bg-slate-900 text-white rounded-2xl font-bold hover:bg-black transition-all">{{ __('View Details') }}</a>
            </div>
        </div>
        @empty
        <div class="py-20 text-center bg-white rounded-[3rem] border border-slate-100 shadow-sm">
             <p class="text-slate-500">{{ __('No active sourcing requests at the moment.') }}</p>
        </div>
        @endforelse
    </div>

    <div class="mt-10">
        {{ $requests->links() }}
    </div>
</div>
@endsection
