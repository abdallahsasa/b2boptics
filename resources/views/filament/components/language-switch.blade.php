@php
    $currentLocale = app()->getLocale();
    $languages = [
        'en' => ['name' => 'English', 'native' => 'English', 'flag' => '🇬🇧'],
        'ar' => ['name' => 'Arabic', 'native' => 'العربية', 'flag' => '🇸🇦', 'dir' => 'rtl'],
        'tr' => ['name' => 'Turkish', 'native' => 'Türkçe', 'flag' => '🇹🇷'],
        'zh' => ['name' => 'Chinese', 'native' => '中文', 'flag' => '🇨🇳'],
        'de' => ['name' => 'German', 'native' => 'Deutsch', 'flag' => '🇩🇪'],
        'es' => ['name' => 'Spanish', 'native' => 'Español', 'flag' => '🇪🇸'],
        'fr' => ['name' => 'French', 'native' => 'Français', 'flag' => '🇫🇷'],
        'it' => ['name' => 'Italian', 'native' => 'Italiano', 'flag' => '🇮🇹'],
        'ru' => ['name' => 'Russian', 'native' => 'Русский', 'flag' => '🇷🇺'],
    ];
    $current = $languages[$currentLocale] ?? $languages['en'];
@endphp

<div class="relative flex items-center mx-2" x-data="{ open: false }" @keydown.escape.stop="open = false" @click.outside="open = false">
    <!-- Trigger Button -->
    <button type="button" 
            @click="open = !open" 
            class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg text-xs font-bold text-gray-700 hover:text-gray-900 dark:text-gray-200 dark:hover:text-white bg-gray-50 dark:bg-gray-800/80 hover:bg-gray-100 dark:hover:bg-gray-700/80 border border-gray-200 dark:border-gray-700 transition-all focus:outline-none focus:ring-2 focus:ring-primary-500/50 shadow-sm"
            title="{{ __('Switch Language') }}"
            aria-haspopup="true"
            :aria-expanded="open">
        <span class="text-base leading-none">{{ $current['flag'] }}</span>
        <span class="hidden sm:inline-block font-semibold tracking-wide uppercase">{{ $currentLocale }}</span>
        <svg class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Dropdown Menu -->
    <div x-show="open" 
         x-cloak
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95 translate-y-1"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-1"
         class="absolute top-full mt-2 ltr:right-0 rtl:left-0 w-48 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-xl py-1.5 z-50 divide-y divide-gray-100 dark:divide-gray-800">
        
        <div class="px-3 py-1.5 text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
            {{ __('Select Interface Language') }}
        </div>

        <div class="py-1 max-h-64 overflow-y-auto">
            @foreach($languages as $code => $lang)
                <a href="{{ route('lang.switch', ['locale' => $code]) }}" 
                   class="flex items-center justify-between px-3 py-2 text-xs font-medium transition-colors hover:bg-primary-50 dark:hover:bg-primary-950/40 hover:text-primary-600 dark:hover:text-primary-400 {{ $currentLocale === $code ? 'bg-primary-50/80 dark:bg-primary-950/50 text-primary-600 dark:text-primary-400 font-bold' : 'text-gray-700 dark:text-gray-200' }}">
                    <span class="flex items-center gap-2.5">
                        <span class="text-base leading-none">{{ $lang['flag'] }}</span>
                        <span>{{ $lang['native'] }}</span>
                    </span>
                    @if($currentLocale === $code)
                        <svg class="w-4 h-4 text-primary-600 dark:text-primary-400 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</div>
