<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\ProgramStudiModel;
use App\Models\WilayahModel;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Kirim data ini ke semua view
        View::composer('*', function ($view) {
            $programStudi = ProgramStudiModel::all();
            $wilayah = WilayahModel::all();

            $view->with(compact('programStudi', 'wilayah'));
        });
    }

}
