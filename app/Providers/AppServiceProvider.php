<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Notification;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

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
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $sharedNotifications = Notification::where('id_user', Auth::id())
                    ->latest()
                    ->take(5)
                    ->get();

                $sharedUnreadCount = Notification::where('id_user', Auth::id())
                    ->where('is_read', false)
                    ->count();
            } else {
                $sharedNotifications = collect();
                $sharedUnreadCount   = 0;
            }

            $view->with([
                'sharedNotifications' => $sharedNotifications,
                'sharedUnreadCount'   => $sharedUnreadCount,
            ]);
        });
    }
}
