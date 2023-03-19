<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    protected $namespace = 'App\\Http\\Controllers';

    public function map(): void
    {
        $this->mapMiscRoutes();
    }

    protected function mapMiscRoutes(): void
    {
        Route::namespace('App\\Http\\MiscControllers')->group(base_path('routes/misc.php'));
    }
}
