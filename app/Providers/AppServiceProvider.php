<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use OwenIt\Auditing\AuditingServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        AuditingServiceProvider::class;
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
