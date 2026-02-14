<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Halaman daftar notifikasi.
     */
    public function index(Request $request)
    {
        $query = AdminNotification::query();

        if ($request->filled('unread_only')) {
            $query->unread();
        }

        $notifications = $query->orderByDesc('created_at')->paginate(20);

        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Tandai satu notifikasi sebagai dibaca.
     */
    public function markAsRead(string $id)
    {
        $notification = AdminNotification::findOrFail($id);
        $notification->markAsRead();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        $redirect = request('redirect', $notification->link ?? route('admin.notifications.index'));
        return redirect()->to($redirect);
    }

    /**
     * Tandai semua notifikasi sebagai dibaca.
     */
    public function markAllRead()
    {
        AdminNotification::unread()->update(['read_at' => now()]);

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.notifications.index')->with('success', 'Semua notifikasi ditandai dibaca');
    }
}
