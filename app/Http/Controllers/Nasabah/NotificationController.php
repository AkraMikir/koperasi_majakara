<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use App\Models\NasabahNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    private function getIdAnggota()
    {
        /** @var \App\Models\User|null $user */
        $user = auth()->user();
        if (!$user || !$user->nasabah) {
            abort(403, 'User tidak memiliki data nasabah');
        }
        return $user->nasabah->id;
    }

    /**
     * Halaman daftar notifikasi nasabah.
     */
    public function index(Request $request)
    {
        $idAnggota = $this->getIdAnggota();
        $query = NasabahNotification::forAnggota($idAnggota);

        if ($request->filled('unread_only')) {
            $query->unread();
        }

        $notifications = $query->orderByDesc('created_at')->paginate(20);

        return view('nasabah.notifications.index', compact('notifications'));
    }

    /**
     * Tandai satu notifikasi sebagai dibaca.
     */
    public function markAsRead(string $id)
    {
        $idAnggota = $this->getIdAnggota();
        $notification = NasabahNotification::forAnggota($idAnggota)->findOrFail($id);
        $notification->markAsRead();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        $redirect = request('redirect', $notification->link ?? route('nasabah.notifications.index'));
        return redirect()->to($redirect);
    }

    /**
     * Tandai semua notifikasi sebagai dibaca.
     */
    public function markAllRead()
    {
        $idAnggota = $this->getIdAnggota();
        NasabahNotification::forAnggota($idAnggota)->unread()->update(['read_at' => now()]);

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('nasabah.notifications.index')->with('success', 'Semua notifikasi ditandai dibaca');
    }
}
