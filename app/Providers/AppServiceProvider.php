<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Activity;
use Illuminate\Support\Facades\Schema;
use App\Services\TenantManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * TenantManager is registered as a singleton so the same instance
     * is shared across the entire request lifecycle.
     */
    public function register(): void
    {
        $this->app->singleton(TenantManager::class, fn() => new TenantManager());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Activity counts in nav — already tenant-scoped by HasTenant global scope
        View::composer('layouts.master', function ($view) {
            if (Schema::hasTable('activities')) {
                $unreadCount = Activity::where('is_read', false)->count();
                $latest      = Activity::latest()->limit(5)->get();
            } else {
                $unreadCount = 0;
                $latest      = collect();
            }

            $view->with('activityUnreadCount', $unreadCount)
                 ->with('latestActivities', $latest);
        });
    }
}
