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

<div class="fi-language-switch" style="display: inline-flex; align-items: center;">
    <x-filament::dropdown max-height="20rem" placement="bottom-end" teleport>
        <x-slot name="trigger">
            <x-filament::button
                color="gray"
                size="sm"
                outlined
                :icon="\Filament\Support\Icons\Heroicon::ChevronDown"
                icon-position="after"
                aria-label="{{ __('Switch Language') }}"
            >
                <span style="font-size: 1.05rem; line-height: 1; margin-right: 0.25rem;">{{ $current['flag'] }}</span>
                <span style="text-transform: uppercase; font-weight: 600; letter-spacing: 0.025em;">{{ $currentLocale }}</span>
            </x-filament::button>
        </x-slot>

        <x-filament::dropdown.header :icon="\Filament\Support\Icons\Heroicon::Language">
            {{ __('Select Language') }}
        </x-filament::dropdown.header>

        <x-filament::dropdown.list>
            @foreach($languages as $code => $lang)
                <x-filament::dropdown.list.item
                    :href="route('lang.switch', ['locale' => $code])"
                    tag="a"
                    :color="$currentLocale === $code ? 'primary' : 'gray'"
                    :icon="$currentLocale === $code ? \Filament\Support\Icons\Heroicon::Check : null"
                >
                    <span style="display: inline-flex; align-items: center; gap: 0.5rem;">
                        <span style="font-size: 1.125rem; line-height: 1;">{{ $lang['flag'] }}</span>
                        <span style="font-weight: {{ $currentLocale === $code ? '600' : '400' }};">{{ $lang['native'] }}</span>
                    </span>
                </x-filament::dropdown.list.item>
            @endforeach
        </x-filament::dropdown.list>
    </x-filament::dropdown>
</div>
