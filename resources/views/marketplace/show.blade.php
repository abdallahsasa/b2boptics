@extends('layouts.app')

@section('title', $product->getTranslation('name', app()->getLocale()))

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- Breadcrumbs -->
        <nav class="flex text-sm text-slate-400 mb-8 gap-2">
            <a href="{{ route('home') }}" class="hover:text-primary-600">{{ __('Home') }}</a>
            <span>/</span>
            <a href="{{ route('marketplace.index') }}" class="hover:text-primary-600">{{ __('Products') }}</a>
            <span>/</span>
            <span class="text-slate-900 font-medium">{{ $product->getTranslation('name', app()->getLocale()) }}</span>
        </nav>

        <div class="flex flex-col lg:flex-row gap-12">
            <!-- Image Gallery -->
            <div class="flex-1">
                <div class="bg-white rounded-[2rem] overflow-hidden border border-slate-100 shadow-sm p-4">
                    <div class="aspect-square bg-slate-50 rounded-[1.5rem] overflow-hidden">
                        <img src="{{ $product->getFirstMediaUrl('images') ?: 'https://images.unsplash.com/photo-1574258495973-f010dfbb5371?q=80&w=1200&auto=format&fit=crop' }}"
                            alt="{{ $product->getTranslation('name', app()->getLocale()) }}"
                            class="w-full h-full object-cover">
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="lg:w-1/2">
                <div class="sticky top-24">
                    <div class="flex items-center gap-3 mb-6">
                        <span
                            class="px-4 py-1.5 bg-primary-50 text-primary-700 text-xs font-bold rounded-full uppercase tracking-wider">{{ $product->category->getTranslation('name', app()->getLocale()) }}</span>
                        <span class="flex items-center gap-1.5 text-xs font-bold text-slate-400 uppercase tracking-widest">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $product->factory->country->name ?? 'Global' }}
                        </span>
                    </div>

                    <h1 class="text-4xl lg:text-5xl font-bold text-slate-900 mb-4">
                        {{ $product->getTranslation('name', app()->getLocale()) }}</h1>

                    @if($product->factory)
                        <div class="flex items-center gap-4 mb-8 p-6 bg-white rounded-3xl border border-slate-100 shadow-sm">
                            <div class="w-14 h-14 bg-slate-100 rounded-2xl overflow-hidden flex-shrink-0">
                                <img src="{{ $product->factory->logo_url ?: 'https://ui-avatars.com/api/?name=' . urlencode($product->factory->official_name) }}"
                                    class="w-full h-full object-contain p-1">
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest mb-0.5">
                                    {{ __('Manufactured by') }}</p>
                                <a href="{{ route('factories.show', $product->factory) }}"
                                    class="text-lg font-bold text-slate-900 hover:text-primary-600 transition-colors">{{ $product->factory->official_name }}</a>
                            </div>
                            <div class="ml-auto">
                                <div class="flex items-center gap-1 text-amber-400">
                                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <span class="text-sm font-bold text-slate-900">4.9</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="mb-10">
                        <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest mb-2">{{ __('Pricing') }}
                        </p>
                        <div class="flex items-baseline gap-2">
                            <span class="text-4xl font-bold text-slate-900">{{ $product->currency }}
                                {{ number_format($product->starting_price, 2) }}</span>
                            <span class="text-slate-400 font-medium">{{ __('starting price') }}</span>
                        </div>
                    </div>

                    <div class="prose prose-slate prose-sm mb-12">
                        <p class="text-slate-600 leading-relaxed">
                            {!! nl2br(e($product->getTranslation('description', app()->getLocale()))) !!}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-10">
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest mb-1">
                                {{ __('Subcategory') }}</p>
                            <p class="font-bold text-slate-900">
                                {{ $product->subcategory->getTranslation('name', app()->getLocale()) ?? 'N/A' }}</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest mb-1">
                                {{ __('Origin') }}</p>
                            <p class="font-bold text-slate-900">{{ $product->factory->country->name ?? 'Global' }}</p>
                        </div>
                    </div>

                    <button x-data @click="$dispatch('open-quote-chat')"
                        class="w-full py-5 bg-primary-600 text-white rounded-3xl font-bold text-lg shadow-xl shadow-primary-600/30 hover:bg-primary-700 hover:scale-[1.02] transition-all flex items-center justify-center gap-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        {{ __('Request a Quote') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Similar Products -->
        @if($similarProducts->count())
            <div class="mt-32">
                <h2 class="text-3xl font-bold text-slate-900 mb-10">{{ __('Similar Products') }}</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($similarProducts as $similar)
                        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden card-hover group">
                            <a href="{{ route('marketplace.product.show', $similar) }}"
                                class="block aspect-square overflow-hidden relative bg-slate-100">
                                <img src="{{ $similar->getFirstMediaUrl('images') ?: 'https://images.unsplash.com/photo-1574258495973-f010dfbb5371?q=80&w=600&auto=format&fit=crop' }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </a>
                            <div class="p-6">
                                <h3 class="font-bold text-slate-900 truncate"><a
                                        href="{{ route('marketplace.product.show', $similar) }}">{{ $similar->getTranslation('name', app()->getLocale()) }}</a>
                                </h3>
                                <p class="text-primary-600 font-bold mt-1">{{ $similar->currency }}
                                    {{ number_format($similar->starting_price, 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Chat Widget -->
        <div x-data="quoteChat()" x-on:open-quote-chat.window="isOpen = true; startChat()"
            class="fixed bottom-0 right-0 sm:bottom-6 sm:right-6 z-50 flex flex-col items-end pointer-events-none w-full sm:w-auto"
            style="height: 100vh; justify-content: flex-end;">

            <!-- Chat Window -->
            <div x-show="isOpen" x-cloak x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 translate-y-10 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-10 scale-95"
                class="pointer-events-auto w-full sm:w-[400px] bg-[#0b141a] sm:rounded-2xl shadow-2xl flex flex-col overflow-hidden border border-slate-700 h-full sm:h-[600px] max-h-[100vh]">

                <!-- Header -->
                <div class="bg-[#202c33] px-4 py-3 flex items-center justify-between border-b border-slate-800 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-slate-700 overflow-hidden">
                            <img src="https://ui-avatars.com/api/?name=Jane&background=10b981&color=fff" alt="Jane"
                                class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="text-slate-200 font-medium leading-tight">Jane (AI Assistant)</h4>
                            <p class="text-xs text-slate-400">Online</p>
                        </div>
                    </div>
                    <button @click="isOpen = false"
                        class="text-slate-400 hover:text-slate-200 p-2 rounded-full hover:bg-slate-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Messages Area -->
                <div class="flex-1 overflow-y-auto p-4 space-y-4 relative" x-ref="chatContainer">
                    <!-- Background Pattern -->
                    <div class="absolute inset-0 z-0 opacity-10 pointer-events-none"
                        style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
                    </div>

                    <template x-for="msg in messages" :key="msg.id">
                        <div class="flex flex-col z-10 relative">
                            <div
                                :class="msg.sender === 'user' ? 'self-end flex flex-col items-end' : 'self-start flex flex-col items-start'">

                                <!-- If message is an option selection by user, show it differently (optional) -->
                                <div class="px-3 py-2 rounded-xl max-w-[85%] shadow-sm relative text-[15px] leading-snug"
                                    :class="msg.sender === 'user' ? 'bg-[#005c4b] text-slate-100 rounded-tr-sm' : 'bg-[#202c33] text-slate-200 rounded-tl-sm'">
                                    <span x-html="msg.text.replace(/\n/g, '<br>')"></span>
                                    <div class="text-[10px] text-right mt-1 opacity-70"
                                        :class="msg.sender === 'user' ? 'text-green-200' : 'text-slate-400'"
                                        x-text="msg.time"></div>
                                </div>

                                <!-- Options Buttons -->
                                <template x-if="msg.options && msg.options.length > 0">
                                    <div class="flex flex-wrap gap-2 mt-2">
                                        <template x-for="option in msg.options">
                                            <button @click="sendMessage(option); msg.options = []"
                                                class="px-4 py-1.5 bg-[#2a3942] hover:bg-[#374b57] text-slate-200 text-sm rounded-full transition-colors border border-slate-700">
                                                <span x-text="option"></span>
                                            </button>
                                        </template>
                                    </div>
                                </template>

                            </div>
                        </div>
                    </template>
                </div>

                <!-- Input Area -->
                <div class="p-3 bg-[#202c33] shrink-0 border-t border-slate-800">
                    <form @submit.prevent="sendMessage()" class="flex items-center gap-2">
                        <div class="relative flex-1">
                            <input x-model="newMessage" type="text"
                                class="w-full bg-[#2a3942] border-none text-slate-200 text-sm rounded-full pl-4 pr-10 py-3 focus:ring-1 focus:ring-[#00a884] placeholder-slate-500"
                                placeholder="Type a message...">
                            <button type="submit"
                                class="absolute right-2 top-1/2 -translate-y-1/2 p-1.5 text-slate-400 hover:text-[#00a884] transition-colors"
                                :disabled="!newMessage.trim()">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- FAB button if chat is closed but was opened once -->
            <button x-show="!isOpen && messages.length > 0" @click="isOpen = true" x-transition
                class="pointer-events-auto bg-primary-600 text-white p-4 rounded-full shadow-2xl hover:bg-primary-700 transition-colors mt-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
            </button>
        </div>

    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('quoteChat', () => ({
                isOpen: false,
                messages: [],
                newMessage: '',
                step: 0,

                startChat() {
                    if (this.messages.length === 0) {
                        setTimeout(() => {
                            this.addMessage('bot', 'Hello  This is Jane, how can help you ?');
                            this.step = 1;
                        }, 500);
                    }
                },

                addMessage(sender, text, options = []) {
                    this.messages.push({
                        id: Date.now(),
                        sender: sender,
                        text: text,
                        options: options,
                        time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                    });
                    this.scrollToBottom();
                },

                sendMessage(text = null) {
                    let msgText = text || this.newMessage;
                    if (!msgText.trim()) return;

                    this.addMessage('user', msgText);
                    this.newMessage = '';

                    setTimeout(() => {
                        this.handleBotResponse(msgText);
                    }, 1000);
                },

                handleBotResponse(userMessage) {
                    userMessage = userMessage.toLowerCase();

                    if (this.step === 1) {
                        // Trigger pricing response
                        this.addMessage('bot', 'The price is {{ $product->currency }} {{ number_format($product->starting_price, 2) }}\nWas this answer enough and clear?', ['Yes', 'No']);
                        this.step = 2;
                    } else if (this.step === 2) {
                        if (userMessage === 'yes') {
                            this.addMessage('bot', 'thank you fro contacting us, is there any further help I can assist you', ['Yes', 'No']);
                            this.step = 3;
                        } else if (userMessage === 'no') {
                            this.addMessage('bot', 'How can I assist you more?\nContact real agent?', ['Contact Agent']);
                            this.step = 5;
                        }
                    } else if (this.step === 3) {
                        if (userMessage === 'yes') {
                            this.addMessage('bot', 'Please let me know');
                            this.step = 1;
                        } else if (userMessage === 'no') {
                            this.addMessage('bot', 'thank you contacting, , rating this chat\nHelpfull ***\nfast action ***\nclear infromation ***');
                            this.step = 4;
                        }
                    } else if (this.step === 5) {
                        if (userMessage === 'contact agent') {
                            this.addMessage('bot', 'Transferring you to a human agent... Please wait.');
                        }
                    }
                },

                scrollToBottom() {
                    setTimeout(() => {
                        const container = this.$refs.chatContainer;
                        if (container) {
                            container.scrollTop = container.scrollHeight;
                        }
                    }, 50);
                }
            }));
        });
    </script>
@endsection