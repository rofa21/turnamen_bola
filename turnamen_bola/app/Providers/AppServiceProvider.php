<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

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
        Paginator::useBootstrapFive();

        if (! $this->app->runningInConsole()) {
            view()->composer('*', function ($view) {
                try {
                    $activeEvent = \App\Models\Event::active() ?? new \App\Models\Event([
                        'name' => 'Piala Disdikpora Grassroot Regional Kebumen 2026',
                        'organizer' => 'Dinas Pendidikan, Kepemudaan, dan Olahraga Kab. Kebumen',
                        'location' => 'Stadion Chandradimuka Kebumen',
                        'season' => '2026/2027',
                    ]);
                    $view->with('activeEvent', $activeEvent);
                } catch (\Throwable $e) {
                    // Fail silently if DB not seeded/migrated yet
                }
            });
        }
    }
}
