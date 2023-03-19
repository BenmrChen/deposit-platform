<?php

namespace App\Providers;

use App\Observers\GeneratePointApiSecret;
use App\Observers\GenerateUuid;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerObservers();
    }

    /**
     * Register observers
     *
     * @return void
     */
    protected function registerObservers(): void
    {
        User::observe(GenerateUuid::class);
        Client::observe(GenerateUuid::class);
        Client::observe(GeneratePointApiSecret::class);
    }
}
