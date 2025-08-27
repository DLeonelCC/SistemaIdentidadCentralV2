<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //Tiempo de expiración de tokens password
        Passport::personalAccessTokensExpireIn(now()->addDays(1));

        //Tiempo de expiración de tokens client_credentials
        Passport::tokensExpireIn(now()->addDays(1));
    }
}
