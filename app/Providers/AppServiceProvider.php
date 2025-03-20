<?php

namespace App\Providers;

use App\Services\FirebaseSatpamService;
use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\Kreait\Firebase\Auth::class, function ($app) {
            return (new Factory)
                ->withServiceAccount(config('firebase.projects.app.credentials'))
                ->createAuth();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        app(FirebaseSatpamService::class)->checkSatpamNodeExist();
    }
}
