<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'OpticB2B') - {{ __('Global Optics Marketplace') }}</title>
    
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
    <nav class="fixed top-0 w-full z-50 glass-nav" x-data="{ open: false }" x-effect="document.body.style.overflow = open ? 'hidden' : ''">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <a href="{{ route('home') }}" class="flex items-center">
                    <img src="{{ asset('images/logo.png') }}" alt="OpticB2B Logo" class="h-14 lg:h-16 w-auto object-contain">
                </a>
                
                <div class="hidden lg:flex items-center space-x-10 text-[15px] font-semibold text-slate-600">
                    <a href="{{ route('marketplace.index') }}" class="hover:text-primary-600 transition-colors {{ request()->routeIs('marketplace.*') ? 'text-primary-600' : '' }}">{{ __('Products') }}</a>
                    <a href="{{ route('sourcing.index') }}" class="hover:text-primary-600 transition-colors {{ request()->routeIs('sourcing.*') ? 'text-primary-600' : '' }}">{{ __('Find Deals') }}</a>
                    <a href="{{ route('stock-offers.index') }}" class="hover:text-primary-600 transition-colors {{ request()->routeIs('stock-offers.index') ? 'text-primary-600' : '' }}">{{ __('Stock Deals') }}</a>
                    <a href="{{ route('factories.index') }}" class="hover:text-primary-600 transition-colors {{ request()->routeIs('factories.*') ? 'text-primary-600' : '' }}">{{ __('Factories') }}</a>
                </div>

                <div class="flex items-center gap-3 sm:gap-4">
                    <!-- Language Switcher -->
                    <div class="relative group h-full flex items-center">
                        <button class="flex items-center gap-2 px-4 py-2.5 bg-slate-50 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-100 transition-all uppercase">
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
                    <button @click="open = !open" class="lg:hidden p-2 rounded-xl bg-slate-50 text-slate-600 hover:bg-slate-100 transition-all focus:outline-none" aria-label="Toggle Menu">
                        <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                        <svg x-show="open" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Drawer Backdrop -->
        <div x-show="open" 
             x-cloak
             x-transition:opacity
             class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 lg:hidden"
             @click="open = false">
        </div>

        <!-- Mobile Menu Drawer Panel -->
        <div x-show="open" 
             x-cloak 
             x-transition:enter="transform transition ease-in-out duration-300"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transform transition ease-in-out duration-300"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full"
             class="fixed inset-y-0 right-0 z-50 w-full max-w-xs bg-white shadow-2xl border-l border-slate-100 p-6 flex flex-col justify-between lg:hidden"
             @click.away="open = false">
            
            <div>
                <!-- Top Section -->
                <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-100">
                    <img src="{{ asset('images/logo.png') }}" alt="OpticB2B Logo" class="h-8 w-auto object-contain">
                    <button @click="open = false" class="p-2 rounded-xl bg-slate-50 text-slate-600 hover:bg-slate-100 transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <!-- Navigation Links -->
                <div class="flex flex-col space-y-4">
                    <a href="{{ route('marketplace.index') }}" class="text-base font-bold text-slate-800 hover:text-primary-600 transition-colors py-2 border-b border-slate-50 {{ request()->routeIs('marketplace.*') ? 'text-primary-600' : '' }}">{{ __('Products') }}</a>
                    <a href="{{ route('sourcing.index') }}" class="text-base font-bold text-slate-800 hover:text-primary-600 transition-colors py-2 border-b border-slate-50 {{ request()->routeIs('sourcing.*') ? 'text-primary-600' : '' }}">{{ __('Find Deals') }}</a>
                    <a href="{{ route('stock-offers.index') }}" class="text-base font-bold text-slate-800 hover:text-primary-600 transition-colors py-2 border-b border-slate-50 {{ request()->routeIs('stock-offers.index') ? 'text-primary-600' : '' }}">{{ __('Stock Deals') }}</a>
                    <a href="{{ route('factories.index') }}" class="text-base font-bold text-slate-800 hover:text-primary-600 transition-colors py-2 border-b border-slate-50 {{ request()->routeIs('factories.*') ? 'text-primary-600' : '' }}">{{ __('Factories') }}</a>
                </div>
            </div>

            <!-- Bottom Auth Section -->
            <div class="pt-6 border-t border-slate-100 flex flex-col gap-3">
                @auth
                    @if(auth()->user()->hasRole(['super_admin', 'admin']))
                        <a href="{{ url('/admin') }}" class="w-full text-center py-3 bg-primary-600 text-white rounded-xl font-bold shadow-lg shadow-primary-600/20 hover:bg-primary-700 transition-all text-sm">{{ __('Admin Panel') }}</a>
                    @elseif(auth()->user()->hasRole(['factory_owner', 'factory_staff']))
                        <a href="{{ url('/factory') }}" class="w-full text-center py-3 bg-primary-600 text-white rounded-xl font-bold shadow-lg shadow-primary-600/20 hover:bg-primary-700 transition-all text-sm">{{ __('Factory Portal') }}</a>
                    @else
                        <div class="text-center font-bold text-slate-700 mb-2">{{ auth()->user()->name }}</div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-center py-3 border border-red-200 rounded-xl font-bold text-red-600 hover:bg-red-50 transition-all text-sm">{{ __('Logout') }}</button>
                        </form>
                    @endif
                @else
                    <button @click="$dispatch('open-auth-modal', { source: 'nav' }); open = false" class="w-full text-center py-3 border border-slate-200 rounded-xl font-bold text-slate-700 hover:bg-slate-50 transition-all text-sm">{{ __('Log in / Register') }}</button>
                    <a href="{{ route('filament.factory.auth.register') }}" class="w-full text-center py-3 bg-primary-900 text-white rounded-full font-bold hover:bg-black transition-all text-sm">{{ __('Join as Factory') }}</a>
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
