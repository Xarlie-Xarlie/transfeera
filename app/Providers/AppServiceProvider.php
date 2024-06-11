<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\ReceiverRepositoryInterface;
use App\Repositories\ReceiverRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ReceiverRepositoryInterface::class, ReceiverRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
