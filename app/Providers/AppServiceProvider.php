<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <--- 1. JANGAN LUPA BARIS INI

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
        // 2. Tambahkan logika ini:
        // Jika aplikasi sedang berjalan di mode production (Vercel), paksa pakai HTTPS
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}