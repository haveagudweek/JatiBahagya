<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\UrlGenerator; // Tambahkan baris ini

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
    public function boot(UrlGenerator $url): void // Tambahkan UrlGenerator sebagai parameter
    {
        Paginator::useBootstrap();

        // Tambahkan blok kode ini untuk memaksa HTTPS di produksi
        if (env('APP_ENV') === 'production') {
            $url->forceScheme('https');
        }
    }
}