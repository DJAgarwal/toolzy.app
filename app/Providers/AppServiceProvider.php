<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Vite;

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
        Vite::useStyleTagAttributes(fn () => [
            'media' => 'print',
            'data-async-style' => 'true',
        ]);

        \App\Models\Tool::observe(\App\Observers\ToolObserver::class);
        \App\Models\StaticPage::observe(\App\Observers\StaticPageObserver::class);
    }
}
