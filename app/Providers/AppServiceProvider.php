<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

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
    public function boot()
    {
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
        Blade::component('components.buttons.primary', 'buttons.primary');
        Blade::component('components.buttons.secondary', 'buttons.secondary');
        Blade::component('components.card', 'card');
        Blade::component('components.modal', 'modal');
    }
}
