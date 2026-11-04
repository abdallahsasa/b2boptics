<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $currentLocale = app()->getLocale();
        $supportedLocales = ['en', 'ar', 'tr', 'zh', 'ru', 'de', 'es', 'fr', 'it'];
        $decodeMeta = fn (string $val): string => html_entity_decode(trim($val), ENT_QUOTES | ENT_HTML5, 'UTF-8');

        $rawTitle = $decodeMeta($__env->yieldContent('meta_title') ?: $__env->yieldContent('title', 'OpticB2B'));
        $brandSuffix = __('Global Optical B2B Marketplace');
        $fullTitle = str_contains(strtolower($rawTitle), 'opticb2b')
            ? $rawTitle
            : $rawTitle . ' | OpticB2B - ' . $brandSuffix;
        $metaDescription = $decodeMeta($__env->yieldContent('meta_description', __('Source optical lenses, eyewear frames, sunglasses, and ophthalmic lab equipment directly from 150+ audited global manufacturers on OpticB2B.')));
        $metaKeywords = $decodeMeta($__env->yieldContent('meta_keywords', __('B2B optical marketplace, wholesale eyewear frames, optical lenses manufacturer, OEM eyewear factory, ophthalmic lab machinery, bulk sunglasses supplier, OpticB2B')));
        $metaRobots = $decodeMeta($__env->yieldContent('meta_robots', 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1'));
        $canonicalUrl = $decodeMeta($__env->yieldContent('canonical_url', url()->current()));
        $ogType = $decodeMeta($__env->yieldContent('og_type', 'website'));
        $ogImage = $decodeMeta($__env->yieldContent('og_image', asset('uploads/hero_showcase.jpg')));

        // Build localized path for hreflang alternates
        $requestSegments = request()->segments();
        if (!empty($requestSegments) && in_array($requestSegments[0], $supportedLocales, true)) {
            array_shift($requestSegments);
        }
        $relativePathAfterLocale = implode('/', $requestSegments);

        $localeRegionMap = [
            'en' => 'en_US',
            'ar' => 'ar_AE',
            'tr' => 'tr_TR',
            'zh' => 'zh_CN',
            'ru' => 'ru_RU',
            'de' => 'de_DE',
            'es' => 'es_ES',
            'fr' => 'fr_FR',
            'it' => 'it_IT',
        ];
        $ogLocale = $localeRegionMap[$currentLocale] ?? $currentLocale;
    @endphp

    <!-- Primary SEO Meta Tags -->
    <title>{{ $fullTitle }}</title>
    <meta name="title" content="{{ $fullTitle }}">
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="keywords" content="{{ $metaKeywords }}">
    <meta name="robots" content="{{ $metaRobots }}">
    <meta name="author" content="OpticB2B">
    <meta name="theme-color" content="#2254C5">

    <!-- Canonical & Pagination Links -->
    <link rel="canonical" href="{{ $canonicalUrl }}">
    @stack('seo_pagination')

    <!-- Multilingual Hreflang Alternate Links -->
    @foreach($supportedLocales as $langCode)
        <link rel="alternate" hreflang="{{ $langCode }}" href="{{ url('/' . $langCode . ($relativePathAfterLocale ? '/' . $relativePathAfterLocale : '')) }}">
    @endforeach
    <link rel="alternate" hreflang="x-default" href="{{ url('/en' . ($relativePathAfterLocale ? '/' . $relativePathAfterLocale : '')) }}">

    <!-- Open Graph / Facebook / LinkedIn / WhatsApp -->
    <meta property="og:site_name" content="OpticB2B">
    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:locale" content="{{ $ogLocale }}">
    @foreach($localeRegionMap as $langKey => $regionVal)
        @if($langKey !== $currentLocale)
            <meta property="og:locale:alternate" content="{{ $regionVal }}">
        @endif
    @endforeach
    <meta property="og:title" content="{{ $fullTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="{{ $rawTitle }}">

    <!-- Twitter / X Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $fullTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ $ogImage }}">

    @stack('meta_extra')

    <!-- Global Organization & WebSite Structured Data (JSON-LD) -->
    @php
        $globalSchema = [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'Organization',
                    '@id' => url('/') . '#organization',
                    'name' => 'OpticB2B',
                    'url' => url('/' . $currentLocale),
                    'logo' => [
                        '@type' => 'ImageObject',
                        'url' => asset('images/logo.png'),
                    ],
                    'description' => $metaDescription,
                ],
                [
                    '@type' => 'WebSite',
                    '@id' => url('/') . '#website',
                    'url' => url('/' . $currentLocale),
                    'name' => 'OpticB2B',
                    'inLanguage' => $currentLocale,
                    'publisher' => [
                        '@id' => url('/') . '#organization',
                    ],
                    'potentialAction' => [
                        '@type' => 'SearchAction',
                        'target' => [
                            '@type' => 'EntryPoint',
                            'urlTemplate' => route('marketplace.index') . '?search={search_term_string}',
                        ],
                        'query-input' => 'required name=search_term_string',
                    ],
                ],
            ],
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($globalSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @stack('structured_data')
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Laravel Echo & Pusher -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>
    <script>
        window.Pusher = Pusher;
        window.Echo = new Echo({
            broadcaster: 'reverb',
            key: '{{ config('broadcasting.connections.reverb.key') }}',
            wsHost: '{{ config('broadcasting.connections.reverb.options.host') }}',
            wsPort: {{ config('broadcasting.connections.reverb.options.port') ?? 80 }},
            wssPort: {{ config('broadcasting.connections.reverb.options.port') ?? 443 }},
            forceTLS: {{ config('broadcasting.connections.reverb.options.useTLS') ? 'true' : 'false' }},
            enabledTransports: ['ws', 'wss'],
            authEndpoint: '/broadcasting/auth',
            auth: {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }
        });
    </script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eef4ff',
                            100: '#d9e5ff',
                            200: '#bcd2fe',
                            300: '#8eb6fc',
                            400: '#5990f8',
                            500: '#326df2',
                            600: '#2254C5',
                            700: '#1b439e',
                            800: '#193982',
                            900: '#122d69',
                            950: '#0c1b40',
                        },
                        secondary: '#f8fafc',
                    }
                }
            }
        }
    </script>

    <style>
        .glass-nav {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>
<body class="bg-secondary text-slate-900 antialiased font-sans">
    <!-- Navigation -->
    <nav class="fixed top-0 inset-x-0 z-50" x-data="{ open: false }" x-effect="document.body.style.overflow = open ? 'hidden' : ''" @keydown.escape.window="open = false">
        <div class="glass-nav">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20 items-center">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <img src="{{ asset('images/logo.png') }}" alt="OpticB2B Logo" class="h-14 lg:h-16 w-auto object-contain">
                    </a>
                    
                    <div class="hidden lg:flex items-center gap-10 text-[15px] font-semibold text-slate-600">
                        <a href="{{ route('marketplace.index') }}" class="hover:text-primary-600 transition-colors {{ request()->routeIs('marketplace.*') ? 'text-primary-600' : '' }}">{{ __('Products') }}</a>
                        <a href="{{ route('sourcing.index') }}" class="hover:text-primary-600 transition-colors {{ request()->routeIs('sourcing.*') ? 'text-primary-600' : '' }}">{{ __('Tenders') }}</a>
                        <a href="{{ route('stock-offers.index') }}" class="hover:text-primary-600 transition-colors {{ request()->routeIs('stock-offers.index') ? 'text-primary-600' : '' }}">{{ __('Stock Deals') }}</a>
                        <a href="{{ route('factories.index') }}" class="hover:text-primary-600 transition-colors {{ request()->routeIs('factories.*') ? 'text-primary-600' : '' }}">{{ __('Factories') }}</a>
                        <a href="{{ route('packages.index') }}" class="hover:text-primary-600 transition-colors {{ request()->routeIs('packages.*') ? 'text-primary-600' : '' }}">{{ __('Packages') }}</a>
                    </div>

                    <div class="flex items-center gap-3 sm:gap-4">
                        <!-- Language Switcher -->
                        <div class="relative group h-full flex items-center">
                            <button class="flex items-center gap-2 px-4 py-2.5 bg-slate-50 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-100 transition-all uppercase">
                                {{ app()->getLocale() }}
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                            <div class="absolute right-0 rtl:right-auto rtl:left-0 top-full pt-2 w-32 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50">
                                <div class="bg-white rounded-2xl shadow-xl border border-slate-100 py-2">
                                    @foreach(['en', 'ar', 'tr', 'zh', 'ru', 'de', 'es', 'fr', 'it'] as $lang)
                                        <a href="{{ route('lang.switch', ['locale' => $lang]) }}" class="block px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-primary-600 uppercase {{ app()->getLocale() == $lang ? 'text-primary-600' : '' }}">
                                            {{ $lang }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Desktop Auth Buttons -->
                        <div class="hidden lg:flex items-center gap-4">
                            @auth
                                @if(auth()->user()->hasRole(['super_admin', 'admin']))
                                    <a href="{{ url('/admin') }}" class="px-7 py-3 bg-primary-600 text-white rounded-full font-semibold shadow-lg shadow-primary-600/20 hover:bg-primary-700 transition-all text-[15px]">{{ __('Admin Panel') }}</a>
                                @elseif(auth()->user()->hasRole(['factory_owner', 'factory_staff']))
                                    <a href="{{ url('/factory') }}" class="px-7 py-3 bg-primary-600 text-white rounded-full font-semibold shadow-lg shadow-primary-600/20 hover:bg-primary-700 transition-all text-[15px]">{{ __('Factory Portal') }}</a>
                                @else
                                    <span class="font-bold text-slate-700">{{ auth()->user()->name }}</span>
                                    <form method="POST" action="{{ route('logout') }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-[15px] font-semibold text-red-600 hover:text-red-700 transition-colors">{{ __('Logout') }}</button>
                                    </form>
                                @endif
                            @else
                                <button @click="$dispatch('open-auth-modal', { source: 'nav' })" class="text-[15px] font-semibold text-slate-700 hover:text-primary-600 transition-colors">{{ __('Log in / Register') }}</button>
                                <a href="{{ route('filament.factory.auth.register') }}" class="px-7 py-3 bg-primary-900 text-white rounded-full font-semibold hover:bg-black transition-all text-[15px]">{{ __('Join as Factory') }}</a>
                            @endauth
                        </div>

                        <!-- Hamburger Button -->
                        <button @click="open = !open" type="button" class="lg:hidden p-2.5 rounded-xl bg-slate-50 text-slate-700 hover:bg-slate-100 transition-all focus:outline-none" aria-label="Toggle Menu">
                            <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                            <svg x-show="open" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Drawer Backdrop -->
        <div x-show="open" 
             x-cloak
             x-transition:enter="transition-opacity ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-[60] lg:hidden"
             @click="open = false">
        </div>

        <!-- Mobile Menu Drawer Panel -->
        <div x-show="open" 
             x-cloak 
             x-transition:enter="transform transition ease-out duration-300"
             x-transition:enter-start="translate-x-full rtl:-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transform transition ease-in duration-200"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full rtl:-translate-x-full"
             class="fixed top-0 bottom-0 right-0 rtl:right-auto rtl:left-0 z-[70] h-[100dvh] w-full max-w-xs bg-white shadow-2xl border-l rtl:border-l-0 rtl:border-r border-slate-100 p-6 flex flex-col justify-between overflow-y-auto lg:hidden">
            
            <div>
                <!-- Top Section -->
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <img src="{{ asset('images/logo.png') }}" alt="OpticB2B Logo" class="h-10 w-auto object-contain">
                    </a>
                    <button @click="open = false" type="button" class="p-2 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 hover:text-slate-900 transition-all" aria-label="Close Menu">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <!-- Navigation Links -->
                <div class="flex flex-col space-y-1.5">
                    <a href="{{ route('marketplace.index') }}" class="flex items-center justify-between px-3.5 py-3 rounded-xl text-base font-bold transition-colors {{ request()->routeIs('marketplace.*') ? 'bg-primary-50 text-primary-600' : 'text-slate-800 hover:bg-slate-50 hover:text-primary-600' }}">
                        <span>{{ __('Products') }}</span>
                        <svg class="w-4 h-4 opacity-50 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                    <a href="{{ route('sourcing.index') }}" class="flex items-center justify-between px-3.5 py-3 rounded-xl text-base font-bold transition-colors {{ request()->routeIs('sourcing.*') ? 'bg-primary-50 text-primary-600' : 'text-slate-800 hover:bg-slate-50 hover:text-primary-600' }}">
                        <span>{{ __('Tenders') }}</span>
                        <svg class="w-4 h-4 opacity-50 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                    <a href="{{ route('stock-offers.index') }}" class="flex items-center justify-between px-3.5 py-3 rounded-xl text-base font-bold transition-colors {{ request()->routeIs('stock-offers.*') ? 'bg-primary-50 text-primary-600' : 'text-slate-800 hover:bg-slate-50 hover:text-primary-600' }}">
                        <span>{{ __('Stock Deals') }}</span>
                        <svg class="w-4 h-4 opacity-50 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                    <a href="{{ route('factories.index') }}" class="flex items-center justify-between px-3.5 py-3 rounded-xl text-base font-bold transition-colors {{ request()->routeIs('factories.*') ? 'bg-primary-50 text-primary-600' : 'text-slate-800 hover:bg-slate-50 hover:text-primary-600' }}">
                        <span>{{ __('Factories') }}</span>
                        <svg class="w-4 h-4 opacity-50 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                    <a href="{{ route('packages.index') }}" class="flex items-center justify-between px-3.5 py-3 rounded-xl text-base font-bold transition-colors {{ request()->routeIs('packages.*') ? 'bg-primary-50 text-primary-600' : 'text-slate-800 hover:bg-slate-50 hover:text-primary-600' }}">
                        <span>{{ __('Packages') }}</span>
                        <svg class="w-4 h-4 opacity-50 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </div>
            </div>

            <!-- Bottom Auth Section -->
            <div class="pt-6 mt-6 border-t border-slate-100 flex flex-col gap-3">
                @auth
                    @if(auth()->user()->hasRole(['super_admin', 'admin']))
                        <a href="{{ url('/admin') }}" class="w-full text-center py-3.5 bg-primary-600 text-white rounded-xl font-bold shadow-lg shadow-primary-600/20 hover:bg-primary-700 transition-all text-sm">{{ __('Admin Panel') }}</a>
                    @elseif(auth()->user()->hasRole(['factory_owner', 'factory_staff']))
                        <a href="{{ url('/factory') }}" class="w-full text-center py-3.5 bg-primary-600 text-white rounded-xl font-bold shadow-lg shadow-primary-600/20 hover:bg-primary-700 transition-all text-sm">{{ __('Factory Portal') }}</a>
                    @else
                        <div class="text-center font-bold text-slate-700 mb-1">{{ auth()->user()->name }}</div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-center py-3 border border-red-200 rounded-xl font-bold text-red-600 hover:bg-red-50 transition-all text-sm">{{ __('Logout') }}</button>
                        </form>
                    @endif
                @else
                    <button @click="$dispatch('open-auth-modal', { source: 'nav' }); open = false" class="w-full text-center py-3.5 border border-slate-200 rounded-xl font-bold text-slate-700 hover:bg-slate-50 transition-all text-sm">{{ __('Log in / Register') }}</button>
                    <a href="{{ route('filament.factory.auth.register') }}" class="w-full text-center py-3.5 bg-primary-900 text-white rounded-full font-bold hover:bg-black transition-all text-sm">{{ __('Join as Factory') }}</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="pt-24 min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white pt-20 pb-10 border-t border-slate-100 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-12 mb-16">
                <div class="col-span-1 md:col-span-1">
                    <div class="flex items-center gap-2 mb-6">
                        <img src="{{ asset('images/logo.png') }}" alt="OpticB2B Logo" class="h-10 w-auto object-contain">
                    </div>
                    <p class="text-slate-500">{{ __('Global marketplace for optical industry professionals.') }}</p>
                </div>
                <div>
                    <h4 class="font-bold mb-6">{{ __('Products') }}</h4>
                    <ul class="space-y-4 text-slate-500 text-sm">
                        <li><a href="{{ route('marketplace.index') }}" class="hover:text-primary-600">{{ __('All Products') }}</a></li>
                        <li><a href="{{ route('sourcing.index') }}" class="hover:text-primary-600">{{ __('Buyer Requests') }}</a></li>
                        <li><a href="{{ route('stock-offers.index') }}" class="hover:text-primary-600">{{ __('Stock Deals') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-6">{{ __('Support') }}</h4>
                    <ul class="space-y-4 text-slate-500 text-sm">
                        <li><a href="{{ route('packages.index') }}" class="hover:text-primary-600 font-semibold text-primary-600">{{ __('Factory Packages (Free)') }}</a></li>
                        <li><a href="#" class="hover:text-primary-600">{{ __('How it Works') }}</a></li>
                        <li><a href="#" class="hover:text-primary-600">{{ __('Contact Us') }}</a></li>
                        <li><a href="#" class="hover:text-primary-600">{{ __('Privacy Policy') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-6">{{ __('Newsletter') }}</h4>
                    <div class="flex gap-2">
                        <input type="email" placeholder="{{ __('Email address') }}" class="bg-slate-100 border-none rounded-xl px-4 py-2 text-sm w-full focus:ring-2 focus:ring-primary-600">
                        <button class="bg-primary-600 text-white p-2 rounded-xl hover:bg-primary-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </button>
                    </div>
                </div>
            </div>
            <div class="border-t border-slate-100 pt-8 text-center text-slate-400 text-xs">
                <p>&copy; {{ date('Y') }} {{ __('OpticB2B Marketplace.') }}</p>
            </div>
        </div>
    </footer>
    <!-- Global Auth Modal -->
    <div x-data="authModal()"
         x-show="isOpen"
         x-on:open-auth-modal.window="isOpen = true; source = $event.detail?.source || 'nav'"
         x-cloak
         class="fixed inset-0 z-[100] flex items-center justify-center">
        
        <div x-show="isOpen"
             x-transition.opacity
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
             @click="isOpen = false"></div>
        
        <div x-show="isOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl p-8 overflow-hidden z-10 mx-4">
            
            <button @click="isOpen = false" class="absolute top-4 right-4 p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-full transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>

            <!-- Tabs -->
            <div class="flex gap-4 mb-8 border-b border-slate-100 pb-4">
                <button @click="tab = 'login'" :class="tab === 'login' ? 'text-primary-600 border-primary-600' : 'text-slate-500 border-transparent hover:text-slate-800'" class="pb-4 -mb-4 font-bold border-b-2 transition-colors">Sign In</button>
                <button @click="tab = 'register'" :class="tab === 'register' ? 'text-primary-600 border-primary-600' : 'text-slate-500 border-transparent hover:text-slate-800'" class="pb-4 -mb-4 font-bold border-b-2 transition-colors">Create Account</button>
            </div>

            <!-- Login Form -->
            <form x-show="tab === 'login'" @submit.prevent="submitLogin" class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Email</label>
                    <input type="email" x-model="loginForm.email" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary-600 focus:border-primary-600 transition-all outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Password</label>
                    <input type="password" x-model="loginForm.password" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary-600 focus:border-primary-600 transition-all outline-none">
                </div>
                <div x-show="error" class="text-red-500 text-sm font-bold" x-text="error"></div>
                <button type="submit" :disabled="loading" class="w-full bg-primary-600 text-white font-bold rounded-xl py-3 shadow-lg shadow-primary-600/20 hover:bg-primary-700 transition-colors disabled:opacity-50">
                    <span x-show="!loading">Sign In</span>
                    <span x-show="loading">Please wait...</span>
                </button>
            </form>

            <!-- Register Form -->
            <form x-show="tab === 'register'" @submit.prevent="submitRegister" class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Full Name</label>
                    <input type="text" x-model="registerForm.name" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary-600 focus:border-primary-600 transition-all outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Email</label>
                    <input type="email" x-model="registerForm.email" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary-600 focus:border-primary-600 transition-all outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Password</label>
                    <input type="password" x-model="registerForm.password" required minlength="8" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary-600 focus:border-primary-600 transition-all outline-none">
                </div>
                <div x-show="error" class="text-red-500 text-sm font-bold" x-text="error"></div>
                <button type="submit" :disabled="loading" class="w-full bg-primary-600 text-white font-bold rounded-xl py-3 shadow-lg shadow-primary-600/20 hover:bg-primary-700 transition-colors disabled:opacity-50">
                    <span x-show="!loading">Create Account</span>
                    <span x-show="loading">Please wait...</span>
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('authModal', () => ({
                isOpen: false,
                source: 'nav',
                tab: 'login',
                loading: false,
                error: null,
                loginForm: { email: '', password: '' },
                registerForm: { name: '', email: '', password: '' },

                async submitLogin() {
                    this.loading = true;
                    this.error = null;
                    try {
                        const res = await fetch('{{ route('api-auth.login') }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                            body: JSON.stringify(this.loginForm)
                        });
                        const data = await res.json();
                        if (res.ok) {
                            this.isOpen = false;
                            window.dispatchEvent(new CustomEvent('auth-success'));
                            if (this.source === 'nav') {
                                window.location.reload();
                            }
                        } else {
                            this.error = data.message || 'Invalid credentials';
                        }
                    } catch (e) {
                        this.error = 'A network error occurred.';
                    }
                    this.loading = false;
                },

                async submitRegister() {
                    this.loading = true;
                    this.error = null;
                    try {
                        const res = await fetch('{{ route('api-auth.register') }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                            body: JSON.stringify(this.registerForm)
                        });
                        const data = await res.json();
                        if (res.ok) {
                            this.isOpen = false;
                            window.dispatchEvent(new CustomEvent('auth-success'));
                            if (this.source === 'nav') {
                                window.location.reload();
                            }
                        } else {
                            this.error = data.message || 'Registration failed.';
                        }
                    } catch (e) {
                        this.error = 'A network error occurred.';
                    }
                    this.loading = false;
                }
            }));
        });
    </script>
</body>
</html>
