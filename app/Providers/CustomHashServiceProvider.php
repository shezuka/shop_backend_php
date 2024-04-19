<?php

namespace App\Providers;

use App\Services\CustomHasher;
use Illuminate\Support\ServiceProvider;

class CustomHashServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('customhash', function ($app) {
            return new CustomHasher();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
