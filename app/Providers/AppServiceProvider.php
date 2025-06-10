<?php

namespace App\Providers;

use App\Models\PengajuanMagangModel;
use App\Observers\PengajuanMagangObserver;
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
         PengajuanMagangModel::observe(PengajuanMagangObserver::class);
    }
}
