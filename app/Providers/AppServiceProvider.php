<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use App\Models\AdminNotification;
use App\Services\AdminPermissionService;
use App\Services\ActivityLogService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register ActivityLogService as singleton
        $this->app->singleton(ActivityLogService::class, function ($app) {
            return new ActivityLogService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share permission service with all views
        View::composer('*', function ($view) {
            $view->with('permissionService', app(AdminPermissionService::class));
        });

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

        // Register custom Blade directives for role checking
        $this->registerBladeDirectives();
    }

    /**
     * Register custom Blade directives for permission checking
     */
    protected function registerBladeDirectives(): void
    {
        // Check if user is Admin Utama
        Blade::if('isAdminUtama', function () {
            return auth()->check() && auth()->user()->role === 'admin_utama';
        });

        // Check if user is Admin Operasional
        Blade::if('isAdminOperasional', function () {
            return auth()->check() && auth()->user()->role === 'admin_operasional';
        });

        // Check if user is any admin
        Blade::if('isAdmin', function () {
            return auth()->check() && in_array(auth()->user()->role, ['admin_utama', 'admin_operasional']);
        });

        // Check if user can CRUD Tabungan Transaksi
        Blade::if('canCrudTabungan', function () {
            return auth()->check() && app(AdminPermissionService::class)->canCrudTabunganTransaksi(auth()->user());
        });

        // Check if user can CRUD Pinjaman
        Blade::if('canCrudPinjaman', function () {
            return auth()->check() && app(AdminPermissionService::class)->canCrudPinjamanAktif(auth()->user());
        });

        // Check if user can do Pelunasan Dipercepat
        Blade::if('canPelunasanDipercepat', function () {
            return auth()->check() && app(AdminPermissionService::class)->canPelunasanDipercepat(auth()->user());
        });

        // Check if user can CRUD Master Data
        Blade::if('canCrudMasterData', function () {
            return auth()->check() && app(AdminPermissionService::class)->canCrudMasterData(auth()->user());
        });

        // Check if user can manage Nasabah (approve changes, reset PIN)
        Blade::if('canManageNasabah', function () {
            return auth()->check() && app(AdminPermissionService::class)->canManageNasabah(auth()->user());
        });
    }
}
