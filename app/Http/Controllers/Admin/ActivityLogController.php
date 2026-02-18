<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\AdminOperasional;
use App\Models\Nasabah;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Log aktivitas nasabah (hanya Admin Utama yang bisa akses)
     */
    public function nasabah(Request $request)
    {
        $query = ActivityLog::with('user')
            ->forNasabah()
            ->orderByDesc('created_at');

        // Filter: search nama nasabah
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter: modul
        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        // Filter: action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter: tanggal range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(25)->withQueryString();

        // Daftar aksi unik untuk dropdown filter
        $availableActions = ActivityLog::forNasabah()
            ->select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        // Statistik ringkas
        $stats = [
            'total_hari_ini' => ActivityLog::forNasabah()->whereDate('created_at', today())->count(),
            'total_minggu_ini' => ActivityLog::forNasabah()->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'total_bulan_ini' => ActivityLog::forNasabah()->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'total_keseluruhan' => ActivityLog::forNasabah()->count(),
        ];

        return view('admin.activity-log.nasabah', compact('logs', 'availableActions', 'stats'));
    }

    /**
     * Log aktivitas admin operasional & admin utama (hanya Admin Utama yang bisa akses)
     */
    public function adminOperasional(Request $request)
    {
        $query = ActivityLog::with('user')
            ->forAdmin()
            ->orderByDesc('created_at');

        // Filter: search nama admin
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter: hanya admin operasional atau semua admin
        if ($request->filled('role')) {
            $query->where('user_role', $request->role);
        }

        // Filter: modul
        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        // Filter: action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter: tanggal range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(25)->withQueryString();

        // Daftar aksi unik
        $availableActions = ActivityLog::forAdmin()
            ->select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        // Daftar admin untuk dropdown filter
        $adminList = User::whereIn('role', ['admin_operasional', 'admin_utama'])
            ->orderBy('nama')
            ->get(['id', 'nama', 'role']);

        // Statistik ringkas
        $stats = [
            'total_hari_ini' => ActivityLog::forAdmin()->whereDate('created_at', today())->count(),
            'total_minggu_ini' => ActivityLog::forAdmin()->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'total_bulan_ini' => ActivityLog::forAdmin()->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'total_keseluruhan' => ActivityLog::forAdmin()->count(),
        ];

        return view('admin.activity-log.admin-operasional', compact('logs', 'availableActions', 'adminList', 'stats'));
    }
}
