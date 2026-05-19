@extends('layouts.app')

@section('title', __('Post a Sourcing Request'))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-10">
        <h1 class="text-3xl lg:text-4xl font-bold text-slate-900 mb-2">{{ __('Post Your Sourcing Request') }}</h1>
        <p class="text-slate-500">{{ __('Provide details about what you need, and factories will submit their best offers.') }}</p>
    </div>

    @if($errors->any())
        <div class="mb-8 bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('sourcing.store') }}" method="POST" class="bg-white p-8 lg:p-12 rounded-[2rem] border border-slate-100 shadow-sm">
        @csrf
        
        <div class="space-y-8">
            <!-- Product Details -->
            <div>
                <h3 class="text-lg font-bold text-slate-900 mb-4 border-b border-slate-100 pb-2">{{ __('Product Details') }}</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">{{ __('Category') }} *</label>
                        <select name="category_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-600 focus:border-primary-600">
                            <option value="">{{ __('Select Category') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->getTranslation('name', app()->getLocale()) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">{{ __('Product Title / Need') }} *</label>
                    <input type="text" name="title" required value="{{ old('title') }}" placeholder="{{ __('e.g., 10,000 pairs of Polycarbonate 1.56 Lenses') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-600 focus:border-primary-600">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">{{ __('Detailed Description') }} *</label>
                    <textarea name="description" required rows="5" placeholder="{{ __('Provide as much detail as possible about specifications, materials, and timeline...') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-600 focus:border-primary-600">{{ old('description') }}</textarea>
                </div>
            </div>

            <!-- Volume and Pricing -->
            <div>
                <h3 class="text-lg font-bold text-slate-900 mb-4 border-b border-slate-100 pb-2">{{ __('Volume & Target Pricing') }}</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">{{ __('Quantity Needed') }} *</label>
                        <input type="text" name="quantity" required value="{{ old('quantity') }}" placeholder="{{ __('e.g., 500 units, 10 pallets') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-600 focus:border-primary-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">{{ __('Target Price') }}</label>
                        <input type="number" step="0.01" name="target_price" value="{{ old('target_price') }}" placeholder="{{ __('e.g., 5.50') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-600 focus:border-primary-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">{{ __('Currency') }} *</label>
                        <select name="currency" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-600 focus:border-primary-600">
                            <option value="USD" {{ old('currency', 'USD') == 'USD' ? 'selected' : '' }}>USD</option>
                            <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Contact Details -->
            <div>
                <h3 class="text-lg font-bold text-slate-900 mb-4 border-b border-slate-100 pb-2">{{ __('Your Contact Information') }}</h3>
                <p class="text-xs text-slate-500 mb-4">{{ __('This information will only be shared with verified suppliers who unlock your request.') }}</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">{{ __('Contact Name') }} *</label>
                        <input type="text" name="contact_name" required value="{{ old('contact_name', auth()->user()->name ?? '') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-600 focus:border-primary-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">{{ __('Contact Email') }} *</label>
                        <input type="email" name="contact_email" required value="{{ old('contact_email', auth()->user()->email ?? '') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-600 focus:border-primary-600">
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-10">
            <button type="submit" class="w-full py-4 bg-primary-600 text-white rounded-xl font-bold uppercase tracking-wider hover:bg-primary-700 transition-colors flex items-center justify-center gap-2">
                {{ __('Submit Request') }}
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
            </button>
        </div>
    </form>
</div>
@endsection
