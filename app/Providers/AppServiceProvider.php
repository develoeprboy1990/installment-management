<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use App\Models\Activity;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // ── Activity sidebar data ────────────────────────────────────────
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

        // ── Global date format: "14 June 2026" ──────────────────────────
        // Usage in Blade: @fdate($date)
        Blade::directive('fdate', function ($expression) {
            return "<?php echo ($expression) ? \Carbon\Carbon::parse($expression)->format('d F Y') : '-'; ?>";
        });

        // ── Carbon macro ─────────────────────────────────────────────────
        // Usage in PHP: $model->created_at->toDisplayDate()
        Carbon::macro('toDisplayDate', function () {
            return $this->format('d F Y');
        });
    }
}
