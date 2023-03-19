<?php

namespace Modules\Crypto\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The module namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected $moduleNamespace = 'Modules\Crypto\Http\Controllers';

    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern based filters.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes('shop-api', 'v1', 'shop-api');
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes(string $module, string $version, string $middleware)
    {
        $namespace = implode(
            '\\',
            collect([$this->moduleNamespace, $module, $version])
                ->transform(fn ($value) => Str::studly($value ?? ''))
                ->toArray()
        );

        $domain        = sprintf('%s.%s', Str::kebab(Str::camel($module)), config('app.base_domain'));
        $routeFilePath = base_path(
            sprintf(
                'Modules/Crypto/Routes/%s_%s.php',
                Str::snake(Str::camel($module)),
                Str::lower($version)
            )
        );

        Route::domain($domain)
            ->prefix($version)
            ->middleware($middleware)
            ->namespace($namespace)
            ->group($routeFilePath);
    }
}
