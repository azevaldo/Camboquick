<?php

namespace App\Providers;

use App\Models\Empresa;
use Illuminate\Support\Facades\View;
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
        //
 $empresa= Empresa::first();
  View::share('empresa',$empresa);
    }
}
