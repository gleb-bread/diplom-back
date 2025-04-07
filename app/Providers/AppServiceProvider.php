<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\TextComponent;
use App\Observers\TextComponentObserver;
use App\Models\ApiComponents;
use App\Observers\ApiComponentObserver;

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
        TextComponent::observe(TextComponentObserver::class);
        ApiComponents::observe(ApiComponentObserver::class);
    }
}
