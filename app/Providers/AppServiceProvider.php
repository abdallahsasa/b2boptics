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
    }
}
