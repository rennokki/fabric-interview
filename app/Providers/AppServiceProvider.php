<?php

namespace App\Providers;

use App\Services\OmdbClient;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
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
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(OmdbClient::class, function (Application $app) {
            return new OmdbClient(
                $app->config['services.omdb.api_url'],
                $app->config['services.omdb.api_key'],
            );
        });
    }
}
