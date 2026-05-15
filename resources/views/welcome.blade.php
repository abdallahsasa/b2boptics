@extends('layouts.app')

@section('title', __('Welcome'))

@section('content')
    <!-- Hero Slider -->
    <section class="relative h-[70vh] lg:h-[85vh] overflow-hidden bg-slate-900">
        @php
            $sliders = \App\Models\HeroSlider::where('is_active', true)->orderBy('order')->get();
        @endphp

        <div id="hero-slider" class="h-full relative">
            @forelse($sliders as $index => $slider)
                <div class="slider-item absolute inset-0 transition-opacity duration-1000 ease-in-out {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}" data-index="{{ $index }}">
                    <img src="{{ Storage::url($slider->image) }}" class="w-full h-full object-cover opacity-60">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/40 to-transparent flex items-center">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                            <div class="max-w-2xl text-white">
                                <h1 class="text-5xl lg:text-7xl font-bold leading-tight mb-6 transform translate-y-4 transition-transform duration-700 delay-300">{{ $slider->getTranslation('title', app()->getLocale()) }}</h1>
                                <p class="text-xl text-white/80 mb-10 transform translate-y-4 transition-transform duration-700 delay-500">{{ $slider->getTranslation('subtitle', app()->getLocale()) }}</p>
                                @if($slider->button_url)
                                    <a href="{{ $slider->button_url }}" class="inline-flex items-center gap-3 px-8 py-4 bg-primary-600 text-white rounded-2xl font-bold text-lg shadow-xl shadow-primary-600/30 hover:scale-105 transition-all transform translate-y-4 transition-transform duration-700 delay-700">
                                        {{ $slider->getTranslation('button_text', app()->getLocale()) ?: __('Explore Now') }}
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Fallback Slider -->
                <div class="slider-item absolute inset-0">
                    <img src="https://images.unsplash.com/photo-1574258495973-f010dfbb5371?q=80&w=1600&auto=format&fit=crop" class="w-full h-full object-cover opacity-50">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/20 to-transparent flex items-center">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                            <div class="max-w-2xl text-white">
                                <h1 class="text-5xl lg:text-7xl font-bold leading-tight mb-6">{{ __('Connecting the Global Optics Industry') }}</h1>
                                <p class="text-xl text-white/80 mb-10">{{ __('The leading B2B marketplace for optical lenses, frames, and machinery.') }}</p>
                                <a href="{{ route('marketplace.index') }}" class="inline-flex items-center gap-3 px-8 py-4 bg-primary-600 text-white rounded-2xl font-bold text-lg shadow-xl shadow-primary-600/30 hover:scale-105 transition-all">
                                    {{ __('Join Now') }}
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforelse

            <!-- Slider Controls -->
            @if($sliders->count() > 1)
                <div class="absolute bottom-10 left-1/2 -translate-x-1/2 z-20 flex gap-3">
                    @foreach($sliders as $index => $slider)
                        <button class="slider-dot w-3 h-3 rounded-full bg-white/30 hover:bg-white/60 transition-all {{ $index === 0 ? 'bg-white scale-125' : '' }}" data-index="{{ $index }}"></button>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- Categories -->
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <h2 class="text-3xl font-bold text-slate-900">{{ __('Featured Categories') }}</h2>
                    <p class="text-slate-500 mt-2">{{ __('Everything you need for your optical business') }}</p>
                </div>
                <a href="{{ route('marketplace.index') }}" class="text-primary-600 font-semibold hover:underline">{{ __('View All') }}</a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Static for now, but following the multi-lang rule -->
                <div class="group relative overflow-hidden rounded-[2.5rem] bg-slate-100 h-96 card-hover cursor-pointer">
                    <img src="https://images.unsplash.com/photo-1591076482161-42ce6da69f67?q=80&w=800&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 opacity-80">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-8 left-8 text-white">
                        <h3 class="text-3xl font-bold">{{ __('Optical Lenses') }}</h3>
                        <p class="text-sm opacity-80 mt-2">{{ __('RX, Stock, and Specialized coatings') }}</p>
                    </div>
                </div>

                <div class="group relative overflow-hidden rounded-[2.5rem] bg-slate-100 h-96 card-hover cursor-pointer">
                    <img src="https://images.unsplash.com/photo-1511499767390-a73350266627?q=80&w=800&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 opacity-80">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-8 left-8 text-white">
                        <h3 class="text-3xl font-bold">{{ __('Designer Frames') }}</h3>
                        <p class="text-sm opacity-80 mt-2">{{ __('Acetate, Metal, and Carbon Fiber') }}</p>
                    </div>
                </div>

                <div class="group relative overflow-hidden rounded-[2.5rem] bg-slate-100 h-96 card-hover cursor-pointer">
                    <img src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?q=80&w=800&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 opacity-80">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-8 left-8 text-white">
                        <h3 class="text-3xl font-bold">{{ __('Lab Machinery') }}</h3>
                        <p class="text-sm opacity-80 mt-2">{{ __('Edgers, Blockers, and Generators') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest Requests -->
    <section class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-slate-900">{{ __('Latest Buyer Requests') }}</h2>
                <p class="text-slate-500 mt-4 text-lg">{{ __('Active opportunities for manufacturers worldwide') }}</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @php
                    $requests = \App\Models\BuyerRequest::with('category')->latest()->take(3)->get();
                @endphp
                @forelse($requests as $request)
                    <div class="p-8 bg-white rounded-[2rem] border border-slate-100 shadow-sm card-hover">
                        <div class="flex items-center justify-between mb-6">
                            <span class="px-4 py-1.5 bg-primary-50 text-primary-700 text-[10px] font-bold rounded-full uppercase tracking-widest">{{ $request->category->getTranslation('name', app()->getLocale()) }}</span>
                            <span class="text-slate-400 text-xs font-medium">{{ $request->created_at->diffForHumans() }}</span>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-3">{{ $request->title }}</h3>
                        <p class="text-slate-600 text-sm line-clamp-2 mb-6 leading-relaxed">{{ $request->description }}</p>
                        <div class="flex items-center justify-between mt-6 pt-6 border-t border-slate-50">
                            <div>
                                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest">{{ __('Quantity') }}</p>
                                <p class="text-lg font-bold text-slate-900">{{ $request->quantity }}</p>
                            </div>
                            <a href="{{ route('sourcing.show', $request) }}" class="px-6 py-2.5 bg-primary-600 text-white rounded-xl font-bold text-sm hover:bg-primary-700 transition-all">{{ __('Send Quote') }}</a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-20 bg-white rounded-[3rem] border-2 border-dashed border-slate-200">
                        <p class="text-slate-500">{{ __('No active requests found. Be the first to post!') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <h2 class="text-4xl lg:text-5xl font-bold text-slate-900">{{ __('Built for the Optics Industry') }}</h2>
                <p class="mt-6 text-slate-600 max-w-2xl mx-auto text-xl">{{ __('The specialized B2B tools you need to grow your global supply chain.') }}</p>
            </div>

            <div class="grid md:grid-cols-2 gap-10">
                <div class="bg-slate-50 p-12 rounded-[3rem] border border-slate-100 card-hover group">
                    <div class="w-16 h-16 bg-white text-primary-600 rounded-2xl flex items-center justify-center mb-8 shadow-sm group-hover:bg-primary-600 group-hover:text-white transition-all">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                    </div>
                    <h3 class="text-3xl font-bold text-slate-900 mb-6">{{ __('For Buyers') }}</h3>
                    <ul class="space-y-6 text-slate-600">
                        <li class="flex items-start gap-4">
                            <div class="w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            </div>
                            <span class="text-lg">{{ __('Source directly from verified global factories.') }}</span>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            </div>
                            <span class="text-lg">{{ __('Post sourcing requests and receive competitive bids.') }}</span>
                        </li>
                    </ul>
                </div>

                <div class="bg-primary-900 p-12 rounded-[3rem] shadow-2xl text-white card-hover group">
                    <div class="w-16 h-16 bg-white/10 text-white rounded-2xl flex items-center justify-center mb-8 group-hover:bg-white group-hover:text-primary-900 transition-all">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    </div>
                    <h3 class="text-3xl font-bold mb-6">{{ __('For Manufacturers') }}</h3>
                    <ul class="space-y-6 text-white/80">
                        <li class="flex items-start gap-4">
                            <div class="w-6 h-6 bg-primary-700 text-primary-300 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            </div>
                            <span class="text-lg">{{ __('Expand your reach to international buyers.') }}</span>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-6 h-6 bg-primary-700 text-primary-300 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            </div>
                            <span class="text-lg">{{ __('Liquidate stock inventory with exclusive offers.') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sliders = document.querySelectorAll('.slider-item');
            const dots = document.querySelectorAll('.slider-dot');
            let current = 0;

            if (sliders.length > 1) {
                const nextSlide = () => {
                    sliders[current].classList.replace('opacity-100', 'opacity-0');
                    dots[current].classList.remove('bg-white', 'scale-125');
                    dots[current].classList.add('bg-white/30');

                    current = (current + 1) % sliders.length;

                    sliders[current].classList.replace('opacity-0', 'opacity-100');
                    dots[current].classList.add('bg-white', 'scale-125');
                    dots[current].classList.remove('bg-white/30');
                };

                let interval = setInterval(nextSlide, 6000);

                dots.forEach(dot => {
                    dot.addEventListener('click', () => {
                        clearInterval(interval);
                        const target = parseInt(dot.dataset.index);
                        
                        sliders[current].classList.replace('opacity-100', 'opacity-0');
                        dots[current].classList.remove('bg-white', 'scale-125');
                        dots[current].classList.add('bg-white/30');

                        current = target;

                        sliders[current].classList.replace('opacity-0', 'opacity-100');
                        dots[current].classList.add('bg-white', 'scale-125');
                        dots[current].classList.remove('bg-white/30');
                        
                        interval = setInterval(nextSlide, 6000);
                    });
                });
            }
        });
    </script>
@endsection
