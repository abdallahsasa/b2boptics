<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

use LaraZeus\SpatieTranslatable\SpatieTranslatablePlugin;

class FactoryPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('factory')
            ->path('factory')
            ->login()
            ->registration(\App\Filament\Factory\Pages\Auth\RegisterFactory::class)
            ->brandName('OpticB2B Factory Portal')
            ->brandLogo(asset('images/logo.png'))
            ->brandLogoHeight('2.5rem')
            ->colors([
                'primary' => Color::hex('#2254C5'),
            ])
            ->discoverResources(in: app_path('Filament/Factory/Resources'), for: 'App\Filament\Factory\Resources')
            ->discoverPages(in: app_path('Filament/Factory/Pages'), for: 'App\Filament\Factory\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Factory/Widgets'), for: 'App\Filament\Factory\Widgets')
            ->widgets([
                AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                \App\Http\Middleware\SetFilamentLocale::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->renderHook(
                \Filament\View\PanelsRenderHook::USER_MENU_BEFORE,
                fn (): string => \Illuminate\Support\Facades\Blade::render('@include("filament.components.language-switch")'),
            )
            ->renderHook(
                \Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE,
                fn (): string => \Illuminate\Support\Facades\Blade::render('<div class="flex justify-end mb-4">@include("filament.components.language-switch")</div>'),
            )
            ->renderHook(
                \Filament\View\PanelsRenderHook::AUTH_REGISTER_FORM_BEFORE,
                fn (): string => \Illuminate\Support\Facades\Blade::render('<div class="flex justify-end mb-4">@include("filament.components.language-switch")</div>'),
            )
            ->plugin(
                SpatieTranslatablePlugin::make()
                    ->defaultLocales(['en', 'ar', 'tr', 'zh', 'ru', 'de', 'es', 'fr', 'it']),
            );
    }
}
