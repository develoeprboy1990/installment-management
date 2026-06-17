<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Activity;
use Illuminate\Support\Facades\Schema;
//use Illuminate\Support\Facades\Schema;

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
        //
        //Schema::defaultStringLength(191);
        View::composer('layouts.master', function ($view) {
            if (Schema::hasTable('activities')) {
                $unreadCount = Activity::where('is_read', false)->count();
                $latest = Activity::latest()->limit(5)->get();
            } else {
                $unreadCount = 0;
                $latest = collect();
            }

            $view->with('activityUnreadCount', $unreadCount)
                 ->with('latestActivities', $latest);
        });
    }
}
