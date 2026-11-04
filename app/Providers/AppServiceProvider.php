<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (in_array(config('mail.default'), ['log', 'array'], true) || in_array(config('mail.mailers.smtp.host'), ['127.0.0.1', 'localhost', null, ''], true)) {
            config([
                'mail.default' => 'smtp',
                'mail.mailers.smtp.transport' => 'smtp',
                'mail.mailers.smtp.scheme' => 'smtps',
                'mail.mailers.smtp.host' => 'smtp.hostinger.com',
                'mail.mailers.smtp.port' => 465,
                'mail.mailers.smtp.username' => 'support@opticb2b.com',
                'mail.mailers.smtp.password' => 'Support@op123.#',
                'mail.from.address' => 'support@opticb2b.com',
                'mail.from.name' => 'OpticB2B',
            ]);
        }

        // Automatically clear stale route or Filament component caches on the server after deployment
        try {
            $filamentCacheDir = base_path('bootstrap/cache/filament');
            if (is_dir($filamentCacheDir)) {
                \Illuminate\Support\Facades\File::deleteDirectory($filamentCacheDir);
            }

            if (app()->routesAreCached() && !\Illuminate\Support\Facades\Route::has('filament.factory.resources.conversations.index')) {
                $routeCacheFile = app()->getCachedRoutesPath();
                if (is_file($routeCacheFile)) {
                    @unlink($routeCacheFile);
                }
            }
        } catch (\Throwable $e) {
            // Ignore filesystem permission issues
        }
    }
}
