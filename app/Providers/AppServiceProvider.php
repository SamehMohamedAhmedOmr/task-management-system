<?php

namespace App\Providers;

use App\Helpers\ApiResponse;
use App\Helpers\Pagination;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerFacades();
    }

    private function registerFacades()
    {
        $this->app->bind('ApiResponse', function () {
            return $this->app->make(ApiResponse::class);
        });

        $this->app->bind('Pagination', function () {
            return $this->app->make(Pagination::class);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}
