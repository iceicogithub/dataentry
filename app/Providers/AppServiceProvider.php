<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

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
        Paginator::useBootstrap(); // This enables Bootstrap pagination styles
        Paginator::defaultView('vendor.pagination.bootstrap-5'); // Adjust if you're using a different version of Bootstrap
        Paginator::defaultSimpleView('vendor.pagination.simple-bootstrap-5'); // Optional for simple pagination
        
    }
}
