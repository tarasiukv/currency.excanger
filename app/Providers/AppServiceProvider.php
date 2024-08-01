<?php

namespace App\Providers;

use App\Interfaces\ExchangeRateRepositoryInterface;
use App\Repositories\ExchangeRateRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ExchangeRateRepositoryInterface::class, ExchangeRateRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
