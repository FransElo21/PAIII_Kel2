<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            // Daftarkan API routes (stateless, tanpa CSRF)
            Route::prefix('api')
                 ->middleware('api')
                 ->group(base_path('routes/api.php'));

            // Daftarkan Web routes (session, CSRF, dll)
            Route::middleware('web')
                 ->group(base_path('routes/web.php'));
        });
    }

    // …
}
