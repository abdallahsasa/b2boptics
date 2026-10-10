# OpticB2B Platform Documentation

Welcome to the OpticB2B platform! This document provides a high-level overview of the architecture, tech stack, and key features of the application to help any new developer (human or AI) quickly understand where everything is located.

## Tech Stack
- **Framework**: Laravel 11 (PHP 8.2+)
- **Admin & Factory Panels**: Filament v3 (TALL Stack - Tailwind, Alpine, Laravel, Livewire)
- **Frontend (Marketplace)**: Blade Templates, Alpine.js, Tailwind CSS (Vanilla CSS in `index.css`)
- **Real-time WebSockets**: Laravel Reverb & Laravel Echo
- **AI Integration**: OpenAI API (for AI Chat Agent)
- **Translations**: Google Translate API (for seamless cross-language chat)

---

## System Architecture

The platform is divided into three main interfaces:

### 1. The Global Marketplace (Frontend)
This is the public-facing application where buyers browse products, find stock deals, and post sourcing requests.
- **Routes**: `routes/web.php` (Wrapped in a multi-language `{locale}` group via `SetLocale` middleware).
- **Controllers**: `app/Http/Controllers/MarketplaceController`, `FactoryController`, `SourcingController`.
- **Views**: `resources/views/marketplace`, `resources/views/sourcing`, `resources/views/layouts/app.blade.php`.
- **Authentication**: Custom frontend Auth via `AuthController` with a seamless Alpine.js inline modal (`resources/views/layouts/app.blade.php`).

### 2. Factory Portal (For Sellers/Factories)
Factories register and manage their products, stock offers, and reply to buyer requests.
- **URL Prefix**: `/factory`
- **Framework**: Filament Panel Provider (`app/Providers/Filament/FactoryPanelProvider.php`)
- **Code Location**: `app/Filament/Factory/Resources/...`
- **Authentication**: Filament's built-in auth, checking for the `factory_owner` or `factory_staff` roles.

### 3. Super Admin Panel (For Platform Owners)
Platform administrators oversee everything (approving factories, managing categories, users).
- **URL Prefix**: `/admin`
- **Framework**: Filament Panel Provider (`app/Providers/Filament/AdminPanelProvider.php`)
- **Code Location**: `app/Filament/Resources/...`
- **Authentication**: Filament's built-in auth, checking for the `super_admin` or `admin` roles.

---

## Core Features & Workflows

### AI Chat & Real-Time Messaging
A standout feature of this platform is the AI-powered WebSocket Chat System.
- **How it Works**: Buyers click "Request a Quote" on a product. This opens a WebSocket chat (`resources/views/marketplace/show.blade.php`) connecting to Laravel Reverb.
- **The AI Agent**: `app/Services/Communication/ConversationManager.php` intercepts messages. The OpenAI API tries to answer product questions automatically.
- **Escalation**: If the AI confidence drops below 50%, it changes the Conversation status to `human_required`.
- **Factory Reply**: The Factory Owner logs into the Factory Portal and opens the conversation. They chat back using a **Livewire Component** (`app/Livewire/FactoryChat.php` / `resources/views/livewire/factory-chat.blade.php`) which broadcasts events (`App\Events\MessageSent`) back to the buyer in real-time.

### Auto-Translation Engine
Since buyers and factories might speak different languages, all messages are automatically translated.
- **Service**: `app/Services/Communication/TranslationManager.php`
- **Mechanism**: Translates the message into English for the AI, and translates it into the receiver's local language on the fly. 

### Multi-Language Router (SEO Friendly)
- **Middleware**: `app/Http/Middleware/SetLocale.php`
- **Behavior**: Forces URLs to include the language code (e.g., `b2boptics.com/en/marketplace`). 
- **Exceptions**: API endpoints, WebSocket auth (`/broadcasting/auth`), and Filament panels (`/admin`, `/factory`) are whitelisted from this middleware to prevent 404 redirect loops.

---

## Important Commands

**Start the Web Server**:
```bash
php artisan serve
```

**Start the WebSocket Server (Laravel Reverb)**:
```bash
php artisan reverb:start
```
*(Note: Reverb MUST be running for the chat widget to work!)*

**Clear Caches (If making config or view changes)**:
```bash
php artisan optimize:clear
```

## Where to Edit Features?
- **Changing Frontend UI?** -> Edit Blade files in `resources/views/`. Use Tailwind.
- **Updating Factory Dashboard?** -> Edit files in `app/Filament/Factory/Resources/`.
- **Updating Admin Dashboard?** -> Edit files in `app/Filament/Resources/`.
- **Modifying Chat/AI Logic?** -> Edit `app/Services/Communication/ConversationManager.php`.
- **Fixing Routing/404s?** -> Check `SetLocale.php` middleware or `routes/web.php`.
