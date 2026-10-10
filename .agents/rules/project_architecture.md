# OpticB2B Project Architecture & Guidelines

This rule provides critical context for AI agents working on the OpticB2B workspace. Always adhere to these structural guidelines when making modifications.

## Tech Stack Overview
- **Framework**: Laravel 11 (PHP 8.2+)
- **Admin & Factory Panels**: Filament v3
- **Frontend (Marketplace)**: Blade Templates, Alpine.js, Tailwind CSS (Vanilla CSS in `index.css`)
- **Real-time WebSockets**: Laravel Reverb & Laravel Echo
- **AI Integration**: OpenAI API (for AI Chat Agent)
- **Translations**: Google Translate API

## Code Modification Locations

1. **Frontend (Marketplace)**:
   - **Routes**: `routes/web.php` (Wrapped in a multi-language `{locale}` group via `SetLocale` middleware).
   - **Controllers**: `app/Http/Controllers/MarketplaceController.php`, `FactoryController.php`, `SourcingController.php`.
   - **Views**: `resources/views/marketplace/`, `resources/views/sourcing/`, `resources/views/layouts/app.blade.php`.
   - **Authentication**: Frontend uses custom Auth via `AuthController.php` with an Alpine.js modal in `app.blade.php`. DO NOT route frontend users to Filament login.

2. **Factory Portal (Sellers)**:
   - **URL Prefix**: `/factory`
   - **Framework**: Filament Panel Provider (`app/Providers/Filament/FactoryPanelProvider.php`)
   - **Code Location**: `app/Filament/Factory/Resources/...`
   - **Authentication**: Uses Filament auth (Roles: `factory_owner`, `factory_staff`).

3. **Super Admin Panel**:
   - **URL Prefix**: `/admin`
   - **Framework**: Filament Panel Provider (`app/Providers/Filament/AdminPanelProvider.php`)
   - **Code Location**: `app/Filament/Resources/...`
   - **Authentication**: Uses Filament auth (Roles: `super_admin`, `admin`).

4. **AI Chat & WebSocket System**:
   - **Backend Agent Logic**: `app/Services/Communication/ConversationManager.php`.
   - **WebSockets Engine**: Laravel Reverb.
   - **Buyer Interface**: Alpine.js widget in `resources/views/marketplace/show.blade.php`.
   - **Factory Interface**: Livewire Component in `app/Livewire/FactoryChat.php` / `resources/views/livewire/factory-chat.blade.php` (embedded in Factory Filament panel).

## Important Rules for Agents
- **Multi-Language Middleware**: Do not add routes that handle API calls (like WebSockets `/broadcasting/auth`, or `/api-auth/login`) inside the localized `{locale}` route group in `routes/web.php`, otherwise they will break due to 404 redirects in `SetLocale.php`.
- **Restarting Servers**: If the system restarts, you must manually run `php artisan serve` and `php artisan reverb:start` to bring the environment back online.
- **Frontend Styling**: Always use Tailwind CSS classes. Do not use external UI libraries unless requested. Keep the design vibrant, glassmorphic, and dynamic.
