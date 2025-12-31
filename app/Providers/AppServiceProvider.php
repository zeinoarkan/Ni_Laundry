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
        // 2. TAMBAHKAN KODE INI DI BARIS PERTAMA FUNGSI BOOT
        // Ini memaksa semua CSS/JS/Gambar dipanggil via HTTPS saat di Vercel
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // --- Logika kodingan kamu yang lain (jika ada) biarkan di bawah sini ---
    }
}
