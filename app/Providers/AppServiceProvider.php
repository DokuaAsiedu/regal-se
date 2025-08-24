<?php

namespace App\Providers;

use App\View\Composers\CategoryComposer;
use Illuminate\Support\Facades\Blade;
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
        Blade::directive('role', function ($code) {
            return "<?php if(auth()->check() && auth()->user()->hasRole($code)): ?>";
        });

        Blade::directive('endrole', function () {
            return "<?php endif; ?>";
        });

        View::composer('components.layouts.app.header', CategoryComposer::class);
    }
}
