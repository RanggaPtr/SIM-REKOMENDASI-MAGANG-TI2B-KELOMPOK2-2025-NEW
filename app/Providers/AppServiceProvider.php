<?php

namespace App\Providers;

use App\Models\PengajuanMagangModel;
use App\Observers\PengajuanMagangObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        if (app()->environment('local')) {
            // Uncomment to use HTTPS for Ngrok
            // URL::forceScheme('https');
        }
    }
}
