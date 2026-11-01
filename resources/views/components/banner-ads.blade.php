@props(['banners' => collect(), 'page' => 'products'])

@php
    $locale = app()->getLocale();

    if ($banners->isEmpty()) {
        $fallback = $page === 'factories'
            ? [
                'badge' => __('Featured OEM / ODM Partner'),
                'title' => __('Partner With Audited Titanium & Acetate Frame Manufacturers'),
                'subtitle' => __('Custom private-label eyewear production, low MOQ starting at 300 pcs, 3D prototyping, and global door-to-door logistics.'),
                'image_url' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?q=80&w=1600&auto=format&fit=crop',
                'button_text' => __('Submit Custom RFQ'),
                'button_url' => route('sourcing.index'),
            ]
            : [
                'badge' => __('Sponsored Showcase'),
                'title' => __('Next-Gen High-Index 1.74 Freeform Optical Lenses'),
                'subtitle' => __('Ultra-thin aspheric precision with Blue-Cut & super-hydrophobic AR coating. Direct wholesale factory pricing with ISO & CE certification.'),
                'image_url' => 'https://images.unsplash.com/photo-1574258495973-f010dfbb5371?q=80&w=1600&auto=format&fit=crop',
                'button_text' => __('Request Wholesale Quote'),
                'button_url' => route('sourcing.index'),
            ];

        $slides = collect([$fallback]);
    } else {
        $slides = $banners->map(function ($banner) use ($locale) {
            return [
                'badge' => $banner->getTranslation('badge', $locale) ?: __('Sponsored'),
                'title' => $banner->getTranslation('title', $locale),
                'subtitle' => $banner->getTranslation('subtitle', $locale),
                'image_url' => $banner->image_url ?: 'https://images.unsplash.com/photo-1574258495973-f010dfbb5371?q=80&w=1600&auto=format&fit=crop',
                'button_text' => $banner->getTranslation('button_text', $locale),
                'button_url' => $banner->resolved_button_url,
            ];
        })->values();
    }
@endphp

@if($slides->isNotEmpty())
<div 
    x-data="{
        activeSlide: 0,
        direction: 1,
        totalSlides: {{ $slides->count() }},
        duration: 6000,
        progress: 0,
        isPaused: false,
        rafId: null,
        lastTick: null,
        initSlider() {
            if (this.totalSlides > 1) {
                this.lastTick = performance.now();
                this.tick(this.lastTick);
            }
        },
        tick(now) {
            if (!this.isPaused) {
                const delta = now - this.lastTick;
                this.progress += (delta / this.duration) * 100;
                if (this.progress >= 100) {
                    this.progress = 0;
                    this.next();
                }
            }
            this.lastTick = now;
            this.rafId = requestAnimationFrame((t) => this.tick(t));
        },
        pause() {
            this.isPaused = true;
        },
        resume() {
            this.isPaused = false;
            this.lastTick = performance.now();
        },
        goTo(index) {
            if (index === this.activeSlide) return;
            this.direction = index > this.activeSlide ? 1 : -1;
            this.activeSlide = index;
            this.progress = 0;
            this.lastTick = performance.now();
        },
        next() {
            this.direction = 1;
            this.activeSlide = (this.activeSlide + 1) % this.totalSlides;
            this.progress = 0;
            this.lastTick = performance.now();
        },
        prev() {
            this.direction = -1;
            this.activeSlide = (this.activeSlide - 1 + this.totalSlides) % this.totalSlides;
            this.progress = 0;
            this.lastTick = performance.now();
        }
    }"
    x-init="initSlider()"
    @mouseenter="pause()"
    @mouseleave="resume()"
    class="group/banner relative mb-10 rounded-3xl overflow-hidden shadow-xl border border-slate-200/80 bg-slate-950 select-none"
>
    <!-- Stacked Grid Container (prevents any layout jump during crossfade) -->
    <div class="grid grid-cols-1 grid-rows-1 relative min-h-[230px] sm:min-h-[260px] lg:min-h-[280px]">
        @foreach($slides as $index => $slide)
            <div 
                :class="activeSlide === {{ $index }} 
                    ? 'opacity-100 z-10 pointer-events-auto scale-100 blur-0' 
                    : 'opacity-0 z-0 pointer-events-none scale-[1.02] blur-[2px]'"
                style="transition: opacity 850ms cubic-bezier(0.22, 1, 0.36, 1), transform 950ms cubic-bezier(0.22, 1, 0.36, 1), filter 850ms cubic-bezier(0.22, 1, 0.36, 1);"
                class="col-start-1 row-start-1 w-full h-full relative flex items-center overflow-hidden will-change-[opacity,transform,filter]"
                :aria-hidden="activeSlide !== {{ $index }}"
            >
                <!-- Background Image with Cinematic Ken Burns Zoom & Pan -->
                @if(!empty($slide['image_url']))
                    <img 
                        src="{{ $slide['image_url'] }}" 
                        alt="{{ $slide['title'] }}" 
                        :class="activeSlide === {{ $index }} ? 'scale-100 translate-x-0' : 'scale-110 translate-x-3'"
                        style="transition: transform 6500ms cubic-bezier(0.16, 1, 0.3, 1);"
                        class="absolute inset-0 w-full h-full object-cover object-center opacity-45 will-change-transform"
                    >
                @endif

                <!-- Multi-layer Optical Gradient & Ambient Glows -->
                <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/85 to-slate-900/35 rtl:bg-gradient-to-l"></div>
                <div 
                    :class="activeSlide === {{ $index }} ? 'opacity-100 scale-100' : 'opacity-0 scale-75'"
                    style="transition: opacity 1200ms ease-out, transform 1400ms ease-out;"
                    class="absolute -top-24 -left-24 w-80 h-80 bg-primary-600/30 rounded-full blur-3xl pointer-events-none"
                ></div>
                <div 
                    :class="activeSlide === {{ $index }} ? 'opacity-100 scale-100' : 'opacity-0 scale-75'"
                    style="transition: opacity 1200ms ease-out 200ms, transform 1400ms ease-out 200ms;"
                    class="absolute -bottom-24 right-1/4 w-80 h-80 bg-cyan-500/20 rounded-full blur-3xl pointer-events-none"
                ></div>

                <!-- Staggered Content Container -->
                <div class="relative z-10 w-full px-6 py-8 sm:px-12 sm:py-10 lg:px-14 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div class="max-w-2xl">
                        <!-- Badge (Stagger 1) -->
                        <div 
                            :class="activeSlide === {{ $index }} ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                            style="transition: opacity 650ms cubic-bezier(0.22, 1, 0.36, 1) 120ms, transform 650ms cubic-bezier(0.22, 1, 0.36, 1) 120ms;"
                            class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-[11px] font-bold uppercase tracking-wider text-cyan-300 mb-3.5 shadow-sm"
                        >
                            <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                            <span>{{ $slide['badge'] }}</span>
                        </div>

                        <!-- Headline (Stagger 2) -->
                        <h2 
                            :class="activeSlide === {{ $index }} ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-5'"
                            style="transition: opacity 750ms cubic-bezier(0.22, 1, 0.36, 1) 220ms, transform 750ms cubic-bezier(0.22, 1, 0.36, 1) 220ms;"
                            class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight leading-tight"
                        >
                            {{ $slide['title'] }}
                        </h2>

                        <!-- Subtitle (Stagger 3) -->
                        @if(!empty($slide['subtitle']))
                            <p 
                                :class="activeSlide === {{ $index }} ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-5'"
                                style="transition: opacity 750ms cubic-bezier(0.22, 1, 0.36, 1) 340ms, transform 750ms cubic-bezier(0.22, 1, 0.36, 1) 340ms;"
                                class="mt-3 text-sm sm:text-base text-slate-300 leading-relaxed line-clamp-2"
                            >
                                {{ $slide['subtitle'] }}
                            </p>
                        @endif
                    </div>

                    <!-- CTA Button (Stagger 4) -->
                    @if(!empty($slide['button_text']) && !empty($slide['button_url']))
                        <div 
                            :class="activeSlide === {{ $index }} ? 'opacity-100 translate-y-0 scale-100' : 'opacity-0 translate-y-4 scale-95'"
                            style="transition: opacity 750ms cubic-bezier(0.22, 1, 0.36, 1) 440ms, transform 750ms cubic-bezier(0.22, 1, 0.36, 1) 440ms;"
                            class="flex-shrink-0"
                        >
                            <a 
                                href="{{ $slide['button_url'] }}" 
                                class="relative overflow-hidden inline-flex items-center gap-2.5 px-7 py-4 rounded-2xl bg-primary-600 hover:bg-primary-500 text-white font-bold text-sm shadow-xl shadow-primary-900/50 hover:shadow-primary-500/30 hover:-translate-y-0.5 transition-all duration-300 group/btn"
                            >
                                <!-- Subtle Shimmer Sweep on Hover -->
                                <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover/btn:translate-x-full transition-transform duration-700 ease-out pointer-events-none"></span>
                                <span class="relative z-10">{{ $slide['button_text'] }}</span>
                                <svg class="relative z-10 w-4 h-4 transition-transform duration-300 group-hover/btn:translate-x-1 rtl:rotate-180 rtl:group-hover/btn:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Ad Label Corner Tag -->
                <span class="absolute top-3.5 right-4 rtl:right-auto rtl:left-4 z-20 text-[10px] font-semibold uppercase tracking-widest text-white/50 bg-black/35 backdrop-blur-md border border-white/10 px-2.5 py-0.5 rounded-full">
                    {{ __('Ad') }}
                </span>
            </div>
        @endforeach
    </div>

    <!-- Slider Navigation & Progress Bar (if multiple banners) -->
    @if($slides->count() > 1)
        <!-- Prev / Next Floating Glass Arrows (appear on hover) -->
        <button 
            type="button"
            @click="prev()"
            class="absolute left-3 rtl:left-auto rtl:right-3 top-1/2 -translate-y-1/2 z-20 w-9 h-9 rounded-full bg-black/35 hover:bg-black/60 backdrop-blur-md border border-white/15 text-white/80 hover:text-white flex items-center justify-center opacity-0 group-hover/banner:opacity-100 -translate-x-2 group-hover/banner:translate-x-0 rtl:translate-x-2 rtl:group-hover/banner:translate-x-0 transition-all duration-300 shadow-lg"
            aria-label="{{ __('Previous slide') }}"
        >
            <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
            </svg>
        </button>

        <button 
            type="button"
            @click="next()"
            class="absolute right-3 rtl:right-auto rtl:left-3 top-1/2 -translate-y-1/2 z-20 w-9 h-9 rounded-full bg-black/35 hover:bg-black/60 backdrop-blur-md border border-white/15 text-white/80 hover:text-white flex items-center justify-center opacity-0 group-hover/banner:opacity-100 translate-x-2 group-hover/banner:translate-x-0 rtl:-translate-x-2 rtl:group-hover/banner:translate-x-0 transition-all duration-300 shadow-lg"
            aria-label="{{ __('Next slide') }}"
        >
            <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
            </svg>
        </button>

        <!-- Pill Dots with Active Progress Fill -->
        <div class="absolute bottom-3.5 right-6 rtl:right-auto rtl:left-6 z-20 flex items-center gap-2 bg-black/30 backdrop-blur-md px-3 py-1.5 rounded-full border border-white/10">
            @foreach($slides as $index => $slide)
                <button 
                    type="button"
                    @click="goTo({{ $index }})"
                    :class="activeSlide === {{ $index }} ? 'w-8 bg-white/25' : 'w-2 bg-white/40 hover:bg-white/70'"
                    class="relative h-2 rounded-full overflow-hidden transition-all duration-500 ease-out"
                    aria-label="Slide {{ $index + 1 }}"
                >
                    <span 
                        x-show="activeSlide === {{ $index }}"
                        :style="`width: ${progress}%`"
                        class="absolute inset-y-0 left-0 rtl:left-auto rtl:right-0 bg-cyan-400 rounded-full"
                    ></span>
                </button>
            @endforeach
        </div>

        <!-- Subtle Bottom Edge Progress Line -->
        <div class="absolute bottom-0 inset-x-0 h-[2px] bg-white/5 z-20 overflow-hidden">
            <div 
                :style="`width: ${progress}%`"
                class="h-full bg-gradient-to-r from-primary-500 via-cyan-400 to-primary-400"
            ></div>
        </div>
    @endif
</div>
@endif
