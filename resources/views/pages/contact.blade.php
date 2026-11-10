@extends('layouts.app')

@section('title', __('Contact Us') . ' | OpticB2B')
@section('meta_title', __('Contact Us - B2B Optical Marketplace Support | OpticB2B'))
@section('meta_description', __('Get in touch with the OpticB2B support team for buyer assistance, supplier verification, partnership inquiries, and technical support.'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
    <!-- Header -->
    <div class="text-center max-w-3xl mx-auto mb-14">
        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-primary-50 text-primary-700 text-xs font-bold uppercase tracking-wider border border-primary-100 mb-4">
            {{ __('24/7 Global B2B Desk') }}
        </span>
        <h1 class="text-4xl sm:text-5xl font-extrabold text-slate-900 tracking-tight">
            {{ __('Contact Us') }}
        </h1>
        <p class="mt-4 text-lg text-slate-600">
            {{ __('Have questions about sourcing optical products, verifying your supplier account, or upgrading your package? Our team is ready to help.') }}
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <!-- Left Contact Cards -->
        <div class="space-y-6">
            <div class="bg-white rounded-3xl p-7 border border-slate-100 shadow-sm">
                <div class="w-12 h-12 rounded-2xl bg-primary-50 text-primary-600 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-1">{{ __('Email Support') }}</h3>
                <p class="text-sm text-slate-500 mb-3">{{ __('Direct line for buyers, factories, brands, and wholesalers.') }}</p>
                <a href="mailto:support@opticb2b.com" class="text-primary-600 font-bold text-base hover:underline">support@opticb2b.com</a>
            </div>

            <div class="bg-white rounded-3xl p-7 border border-slate-100 shadow-sm">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-1">{{ __('Supplier Verification') }}</h3>
                <p class="text-sm text-slate-500 mb-3">{{ __('Fast-track approval for factories, optical brands, and wholesale distributors.') }}</p>
                <a href="{{ route('filament.factory.auth.register') }}" class="text-emerald-600 font-bold text-sm hover:underline">{{ __('Open Supplier Portal') }} &rarr;</a>
            </div>

            <div class="bg-slate-900 text-white rounded-3xl p-7 shadow-lg">
                <h3 class="text-lg font-bold mb-2">{{ __('Response Time') }}</h3>
                <p class="text-sm text-slate-300 leading-relaxed">
                    {{ __('Our multilingual B2B trade specialists respond to all inquiries within 24 business hours.') }}
                </p>
            </div>
        </div>

        <!-- Right Contact Form -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-8 sm:p-10 border border-slate-100 shadow-sm">
            <h2 class="text-2xl font-bold text-slate-900 mb-2">{{ __('Send Us a Message') }}</h2>
            <p class="text-sm text-slate-500 mb-6">{{ __('Fill out the form below and our team will get back to you promptly.') }}</p>

            @if(session('contact_success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                    <span>{{ session('contact_success') }}</span>
                </div>
            @endif

            <form action="{{ route('pages.contact.submit') }}" method="POST" class="space-y-5">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">{{ __('Full Name') }} *</label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary-600 focus:bg-white outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">{{ __('Email Address') }} *</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary-600 focus:bg-white outline-none transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">{{ __('Company Name') }}</label>
                        <input type="text" name="company" value="{{ old('company') }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary-600 focus:bg-white outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">{{ __('Subject') }} *</label>
                        <select name="subject" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary-600 focus:bg-white outline-none transition-all">
                            <option value="Buyer Sourcing Assistance">{{ __('Buyer Sourcing Assistance') }}</option>
                            <option value="Supplier Verification (Factory / Brand / Wholesaler)">{{ __('Supplier Verification (Factory / Brand / Wholesaler)') }}</option>
                            <option value="Subscription & Packages">{{ __('Subscription & Packages') }}</option>
                            <option value="Technical Support">{{ __('Technical Support') }}</option>
                            <option value="General Inquiry">{{ __('General Inquiry') }}</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">{{ __('Message') }} *</label>
                    <textarea name="message" rows="5" required placeholder="{{ __('How can we help your business?') }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary-600 focus:bg-white outline-none transition-all">{{ old('message') }}</textarea>
                </div>

                <button type="submit" class="px-8 py-4 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl shadow-lg shadow-primary-600/20 transition-all text-sm">
                    {{ __('Send Message') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
