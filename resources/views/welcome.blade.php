<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OpticB2B - Global Optics Marketplace</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (via CDN for simplicity in this demo, but utilizing premium aesthetics) -->
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
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            600: '#0284c7',
                            700: '#0369a1',
                            900: '#0c4a6e',
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
        .hero-gradient {
            background: radial-gradient(circle at top right, #e0f2fe, transparent),
                        radial-gradient(circle at bottom left, #f0f9ff, transparent);
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
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center shadow-lg shadow-primary-600/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                    <span class="text-2xl font-bold tracking-tight text-primary-900">Optic<span class="text-primary-600">B2B</span></span>
                </div>
                
                <div class="hidden md:flex items-center space-x-8 text-sm font-medium text-slate-600">
                    <a href="#" class="hover:text-primary-600 transition-colors">Marketplace</a>
                    <a href="#" class="hover:text-primary-600 transition-colors">Suppliers</a>
                    <a href="#" class="hover:text-primary-600 transition-colors">Buyer Requests</a>
                </div>

                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/admin') }}" class="px-6 py-2.5 bg-primary-600 text-white rounded-full font-semibold shadow-lg shadow-primary-600/20 hover:bg-primary-700 transition-all">Dashboard</a>
                    @else
                        <a href="{{ route('filament.admin.auth.login') }}" class="text-sm font-semibold text-slate-700 hover:text-primary-600 transition-colors">Log in</a>
                        <a href="{{ route('filament.admin.auth.register') }}" class="px-6 py-2.5 bg-primary-900 text-white rounded-full font-semibold hover:bg-black transition-all">Join Marketplace</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden hero-gradient">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center lg:text-left lg:flex items-center justify-between">
                <div class="lg:w-1/2">
                    <h1 class="text-5xl lg:text-7xl font-bold text-slate-900 leading-tight">
                        Connecting the <span class="text-primary-600">Global Optics</span> Industry.
                    </h1>
                    <p class="mt-6 text-xl text-slate-600 leading-relaxed max-w-2xl">
                        The leading B2B marketplace for optical lenses, frames, and machinery. Source directly from factories or find buyers worldwide.
                    </p>
                    <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="#" class="px-8 py-4 bg-primary-600 text-white rounded-2xl font-bold text-lg shadow-xl shadow-primary-600/30 hover:scale-105 transition-transform text-center">Post a Request</a>
                        <a href="#" class="px-8 py-4 bg-white text-slate-900 border border-slate-200 rounded-2xl font-bold text-lg hover:bg-slate-50 transition-all text-center">Explore Products</a>
                    </div>
                    <div class="mt-8 flex items-center gap-4 text-sm text-slate-500 justify-center lg:justify-start">
                        <div class="flex -space-x-2">
                            <div class="w-8 h-8 rounded-full bg-blue-100 border-2 border-white"></div>
                            <div class="w-8 h-8 rounded-full bg-indigo-100 border-2 border-white"></div>
                            <div class="w-8 h-8 rounded-full bg-sky-100 border-2 border-white"></div>
                        </div>
                        <span>Joined by 500+ global factories</span>
                    </div>
                </div>
                <div class="hidden lg:block lg:w-5/12">
                    <div class="relative">
                        <div class="absolute -inset-4 bg-primary-100 rounded-3xl blur-2xl opacity-50"></div>
                        <div class="relative bg-white p-2 rounded-3xl shadow-2xl border border-slate-100">
                             <img src="https://images.unsplash.com/photo-1574258495973-f010dfbb5371?q=80&w=800&auto=format&fit=crop" alt="Optics Industry" class="rounded-2xl">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Categories -->
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <h2 class="text-3xl font-bold text-slate-900">Featured Categories</h2>
                    <p class="text-slate-500 mt-2">Everything you need for your optical business</p>
                </div>
                <a href="#" class="text-primary-600 font-semibold hover:underline">View All</a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Lenses -->
                <div class="group relative overflow-hidden rounded-3xl bg-slate-100 h-80 card-hover cursor-pointer">
                    <img src="https://images.unsplash.com/photo-1591076482161-42ce6da69f67?q=80&w=800&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 opacity-80">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-6 left-6 text-white">
                        <h3 class="text-2xl font-bold">Optical Lenses</h3>
                        <p class="text-sm opacity-80 mt-1">RX, Stock, and Specialized coatings</p>
                    </div>
                </div>

                <!-- Frames -->
                <div class="group relative overflow-hidden rounded-3xl bg-slate-100 h-80 card-hover cursor-pointer">
                    <img src="https://images.unsplash.com/photo-1511499767390-a73350266627?q=80&w=800&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 opacity-80">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-6 left-6 text-white">
                        <h3 class="text-2xl font-bold">Designer Frames</h3>
                        <p class="text-sm opacity-80 mt-1">Acetate, Metal, and Carbon Fiber</p>
                    </div>
                </div>

                <!-- Machinery -->
                <div class="group relative overflow-hidden rounded-3xl bg-slate-100 h-80 card-hover cursor-pointer">
                    <img src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?q=80&w=800&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 opacity-80">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-6 left-6 text-white">
                        <h3 class="text-2xl font-bold">Lab Machinery</h3>
                        <p class="text-sm opacity-80 mt-1">Edgers, Blockers, and Generators</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest Requests -->
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-slate-900">Latest Buyer Requests</h2>
                <p class="text-slate-500 mt-2">Active opportunities for manufacturers</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                    $requests = \App\Models\BuyerRequest::with('category')->latest()->take(3)->get();
                @endphp
                @forelse($requests as $request)
                    <div class="p-6 bg-white rounded-3xl border border-slate-100 shadow-sm card-hover">
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-3 py-1 bg-primary-50 text-primary-700 text-xs font-bold rounded-full uppercase">{{ $request->category->name['en'] ?? 'Optics' }}</span>
                            <span class="text-slate-400 text-sm">{{ $request->created_at->diffForHumans() }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">{{ $request->title }}</h3>
                        <p class="text-slate-600 text-sm line-clamp-2 mb-4">{{ $request->description }}</p>
                        <div class="flex items-center justify-between mt-6 pt-6 border-t border-slate-50">
                            <div>
                                <p class="text-xs text-slate-400 uppercase font-bold tracking-wider">Quantity</p>
                                <p class="font-bold text-slate-900">{{ $request->quantity }}</p>
                            </div>
                            <a href="#" class="text-primary-600 font-bold text-sm hover:underline">Send Quote</a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200">
                        <p class="text-slate-500">No active requests found. Be the first to post!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="py-24 bg-slate-50 border-y border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-slate-900">Built for Professionals</h2>
                <p class="mt-4 text-slate-600 max-w-2xl mx-auto text-lg">Streamline your supply chain with our specialized B2B tools.</p>
            </div>

            <div class="grid md:grid-cols-2 gap-12">
                <div class="bg-white p-10 rounded-3xl shadow-sm border border-slate-100">
                    <div class="w-14 h-14 bg-blue-50 text-primary-600 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-4">For Buyers & Opticians</h3>
                    <ul class="space-y-4 text-slate-600">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            <span>Post public requests and get quotes from multiple factories.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            <span>Access exclusive factory stock offers at liquidation prices.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            <span>Direct communication with manufacturers worldwide.</span>
                        </li>
                    </ul>
                </div>

                <div class="bg-primary-900 p-10 rounded-3xl shadow-xl text-white">
                    <div class="w-14 h-14 bg-white/10 text-white rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-white">For Manufacturers</h3>
                    <ul class="space-y-4 text-white/80">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-primary-400 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            <span>Showcase your products to a global audience of buyers.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-primary-400 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            <span>Bid on open buyer requests and win new production contracts.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-primary-400 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            <span>List stock offers to move inventory quickly.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white pt-20 pb-10 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-12 mb-16">
                <div class="col-span-1 md:col-span-1">
                    <div class="flex items-center gap-2 mb-6">
                        <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center shadow-lg shadow-primary-600/20">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        </div>
                        <span class="text-xl font-bold tracking-tight text-primary-900">Optic<span class="text-primary-600">B2B</span></span>
                    </div>
                    <p class="text-slate-500">The world's most trusted marketplace for the optics industry professionals.</p>
                </div>
                <div>
                    <h4 class="font-bold mb-6">Platform</h4>
                    <ul class="space-y-4 text-slate-500 text-sm">
                        <li><a href="#" class="hover:text-primary-600 transition-colors">Marketplace</a></li>
                        <li><a href="#" class="hover:text-primary-600 transition-colors">Supplier Directory</a></li>
                        <li><a href="#" class="hover:text-primary-600 transition-colors">Buyer Requests</a></li>
                        <li><a href="#" class="hover:text-primary-600 transition-colors">Stock Offers</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-6">Company</h4>
                    <ul class="space-y-4 text-slate-500 text-sm">
                        <li><a href="#" class="hover:text-primary-600 transition-colors">About Us</a></li>
                        <li><a href="#" class="hover:text-primary-600 transition-colors">Contact</a></li>
                        <li><a href="#" class="hover:text-primary-600 transition-colors">Privacy Policy</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-6">Newsletter</h4>
                    <p class="text-slate-500 text-sm mb-4">Stay updated with the latest industry leads.</p>
                    <div class="flex gap-2">
                        <input type="email" placeholder="Email address" class="bg-slate-100 border-none rounded-xl px-4 py-2 text-sm w-full focus:ring-2 focus:ring-primary-600">
                        <button class="bg-primary-600 text-white p-2 rounded-xl hover:bg-primary-700 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </button>
                    </div>
                </div>
            </div>
            <div class="border-t border-slate-100 pt-8 text-center text-slate-400 text-xs">
                <p>&copy; {{ date('Y') }} OpticB2B Marketplace. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
