<?php

namespace App\Providers;

use App\Helpers\ApiResponse;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('ApiResponse', function () {
            return new ApiResponse();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('ApiResponse', \App\Facades\ApiResponseFacade::class);
    }
}
