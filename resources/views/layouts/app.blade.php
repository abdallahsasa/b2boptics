<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'OpticB2B') - {{ __('Global Optics Marketplace') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#edf2fa',
                            100: '#e1e9f6',
                            600: '#2254C5',
                            700: '#1b439e',
                            900: '#122d69',
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
    </style>
</head>
<body class="bg-secondary text-slate-900 antialiased font-sans">
    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 glass-nav">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center shadow-lg shadow-primary-600/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                    <span class="text-2xl font-bold tracking-tight text-primary-900">Optic<span class="text-primary-600">B2B</span></span>
                </a>
                
                <div class="hidden lg:flex items-center space-x-8 text-sm font-medium text-slate-600">
                    <a href="{{ route('marketplace.index') }}" class="hover:text-primary-600 transition-colors {{ request()->routeIs('marketplace.*') ? 'text-primary-600' : '' }}">{{ __('Marketplace') }}</a>
                    <a href="{{ route('sourcing.index') }}" class="hover:text-primary-600 transition-colors {{ request()->routeIs('sourcing.*') ? 'text-primary-600' : '' }}">{{ __('Find Deals') }}</a>
                    <a href="{{ route('stock-offers.index') }}" class="hover:text-primary-600 transition-colors {{ request()->routeIs('stock-offers.index') ? 'text-primary-600' : '' }}">{{ __('Stock Deals') }}</a>
                    <a href="{{ route('factories.index') }}" class="hover:text-primary-600 transition-colors {{ request()->routeIs('factories.*') ? 'text-primary-600' : '' }}">{{ __('Factories') }}</a>
                </div>

                <div class="flex items-center gap-4">
                    <!-- Language Switcher -->
                    <div class="relative group h-full flex items-center">
                        <button class="flex items-center gap-2 px-3 py-2 bg-slate-50 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition-all uppercase">
                            {{ app()->getLocale() }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div class="absolute right-0 top-full pt-2 w-32 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50">
                            <div class="bg-white rounded-2xl shadow-xl border border-slate-100 py-2">
                                @foreach(['en', 'ar', 'tr', 'zh', 'ru', 'de', 'es', 'fr', 'it'] as $lang)
                                    <a href="{{ route('lang.switch', ['locale' => $lang]) }}" class="block px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-primary-600 uppercase {{ app()->getLocale() == $lang ? 'text-primary-600' : '' }}">
                                        {{ $lang }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    @auth
                        @if(auth()->user()->hasRole(['super_admin', 'admin']))
                            <a href="{{ url('/admin') }}" class="px-6 py-2.5 bg-primary-600 text-white rounded-full font-semibold shadow-lg shadow-primary-600/20 hover:bg-primary-700 transition-all text-sm">{{ __('Admin Panel') }}</a>
                        @else
                            <a href="{{ url('/factory') }}" class="px-6 py-2.5 bg-primary-600 text-white rounded-full font-semibold shadow-lg shadow-primary-600/20 hover:bg-primary-700 transition-all text-sm">{{ __('Factory Portal') }}</a>
                        @endif
                    @else
                        <a href="{{ url('/factory/login') }}" class="text-sm font-semibold text-slate-700 hover:text-primary-600 transition-colors">{{ __('Log in') }}</a>
                        <a href="{{ route('filament.factory.auth.register') }}" class="px-6 py-2.5 bg-primary-900 text-white rounded-full font-semibold hover:bg-black transition-all text-sm">{{ __('Join as Factory') }}</a>
                    @endauth
                </div>
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
                        <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center shadow-lg shadow-primary-600/20">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        </div>
                        <span class="text-xl font-bold tracking-tight text-primary-900">Optic<span class="text-primary-600">B2B</span></span>
                    </div>
                    <p class="text-slate-500">{{ __('Global marketplace for optical industry professionals.') }}</p>
                </div>
                <div>
                    <h4 class="font-bold mb-6">{{ __('Marketplace') }}</h4>
                    <ul class="space-y-4 text-slate-500 text-sm">
                        <li><a href="{{ route('marketplace.index') }}" class="hover:text-primary-600">{{ __('All Products') }}</a></li>
                        <li><a href="{{ route('sourcing.index') }}" class="hover:text-primary-600">{{ __('Buyer Requests') }}</a></li>
                        <li><a href="{{ route('stock-offers.index') }}" class="hover:text-primary-600">{{ __('Stock Deals') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-6">{{ __('Support') }}</h4>
                    <ul class="space-y-4 text-slate-500 text-sm">
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
</body>
</html>
