@extends('layouts.app')

@section('title', __('Privacy Policy') . ' | OpticB2B')
@section('meta_title', __('Privacy Policy & Data Protection | OpticB2B'))
@section('meta_description', __('Read the OpticB2B Privacy Policy covering how we protect buyer and supplier business data, trade inquiries, and account information.'))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
    <div class="bg-white rounded-3xl p-8 sm:p-12 border border-slate-100 shadow-sm">
        <span class="inline-flex items-center px-3.5 py-1 rounded-full bg-primary-50 text-primary-700 text-xs font-bold uppercase tracking-wider mb-4">
            {{ __('Legal & Compliance') }}
        </span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mb-3">
            {{ __('Privacy Policy') }}
        </h1>
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-8">
            {{ __('Effective Date') }}: {{ date('F Y') }}
        </p>

        <div class="space-y-8 text-slate-600 text-sm sm:text-base leading-relaxed">
            <section>
                <h2 class="text-xl font-bold text-slate-900 mb-2">1. {{ __('Overview') }}</h2>
                <p>
                    {{ __('OpticB2B ("we", "our", or "the Platform") operates a global business-to-business (B2B) optical marketplace connecting optical buyers and retailers with verified factories, brands, and wholesalers. We are committed to protecting your corporate and personal data.') }}
                </p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-slate-900 mb-2">2. {{ __('Information We Collect') }}</h2>
                <p>
                    {{ __('We collect account registration details (name, business email, phone number, company name, tax/registration number, and business type), product catalog data, sourcing requests (tenders), and direct messages exchanged between buyers and suppliers on the platform.') }}
                </p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-slate-900 mb-2">3. {{ __('How We Use Your Information') }}</h2>
                <p>
                    {{ __('Your information is used strictly to operate the B2B marketplace: displaying approved supplier showrooms and products, delivering buyer inquiries and email notifications, processing tender quotes, and maintaining platform security.') }}
                </p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-slate-900 mb-2">4. {{ __('B2B Communications & Data Sharing') }}</h2>
                <p>
                    {{ __('When a buyer sends an inquiry, claims a stock deal, or posts a sourcing tender, relevant business contact details are shared with the corresponding supplier so both parties can communicate and trade directly. We never sell your personal or corporate data to third-party advertisers.') }}
                </p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-slate-900 mb-2">5. {{ __('Data Security & Retention') }}</h2>
                <p>
                    {{ __('We implement industry-standard encryption (HTTPS/TLS), hashed credentials, role-based access controls, and secure cloud storage to safeguard your account. You may update or request deletion of your account at any time.') }}
                </p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-slate-900 mb-2">6. {{ __('Contact Us Regarding Privacy') }}</h2>
                <p>
                    {{ __('For any questions regarding this Privacy Policy or your data rights, please contact our support team at:') }}
                    <a href="mailto:support@opticb2b.com" class="text-primary-600 font-bold hover:underline">support@opticb2b.com</a>.
                </p>
            </section>
        </div>
    </div>
</div>
@endsection

