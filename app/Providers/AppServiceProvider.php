<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\AdminNotification;

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
        // Data notifikasi untuk header admin (badge + preview dropdown)
        View::composer('components.admin.header', function ($view) {
            $notificationsUnreadCount = 0;
            $notificationsRecent = collect([]);
            if (auth()->check()) {
                $notificationsUnreadCount = AdminNotification::unread()->count();
                $notificationsRecent = AdminNotification::query()
                    ->recent(8)
                    ->orderByDesc('created_at')
                    ->get();
            }
            $view->with(compact('notificationsUnreadCount', 'notificationsRecent'));
        });
    }
}
