<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

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
         // Response macro for success response
         Response::macro('success', function ($data = [], $status = 200) {
            return Response::json([
                'success' => true,
                'data' => $data,
            ], $status);
        });

        // Response macro for error response
        Response::macro('error', function ($message = 'An error occurred', $status = 400) {
            return Response::json([
                'success' => false,
                'error' => $message,
            ], $status);
        });
    }
}
