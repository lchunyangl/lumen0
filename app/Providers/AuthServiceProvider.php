<?php

namespace App\Providers;

use App\Extensions\Auth\ProxyGuard;
use App\Facades\Proxy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.
        Auth::extend('proxy', function ($app) {
            return $app->make(ProxyGuard::class);
        });

        $this->app['auth']->viaRequest('api', function ($request) {
            if ($request->cookie('api_token')) {
                return Proxy::user($request->cookie('api_token'));
            }
        });
    }
}