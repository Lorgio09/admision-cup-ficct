<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <-- 1. AÑADIMOS ESTA IMPORTACIÓN

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
        // 2. AÑADIMOS ESTA CONDICIÓN PARA PRODUCCIÓN
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }
    }
}