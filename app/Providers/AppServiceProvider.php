<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\TextComponent;
use App\Observers\TextComponentObserver;

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
    }
}
