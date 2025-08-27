<?php

namespace App\Providers;

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
        // Caso production
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');

            // Forzar subdirectorio si el backend vive en /identia-backend
            if (request()->is('identia-backend/*')) {
                $rootUrl = config('app.url') . '/identia-backend';
                URL::forceRootUrl($rootUrl);
            }
        }
    }
}
