<?php

namespace App\Providers;
use Illuminate\Support\Facades\Route;


use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected function mapApiRoutes()
    {
        Route::prefix('api') // The API prefix 
            ->middleware('api') // The API middleware group
            ->group(base_path('routes/api.php')); 
        }
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
        //
    }
}
