<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JanjiTemuUniversal;
use App\Models\JanjiTemuPinjaman;
use Illuminate\Http\Request;

class JanjiTemuController extends Controller
{
    /**
     * Display list of all janji temu.
     */
    public function index(Request $request)
    {
        // Tandai sudah dibuka agar badge notifikasi di sidebar hilang
        session(['admin_janji_temu_seen_at' => now()->toDateTimeString()]);

        $query = JanjiTemuUniversal::query();

        // Search by Nama Anggota
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_anggota', 'like', "%{$search}%");
        }

        // Filter by Tanggal — gunakan filled() bukan has() untuk hindari illegal operator error
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_janji_temu', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_janji_temu', '<=', $request->tanggal_sampai);
        }

        // Filter by Fitur
        if ($request->filled('fitur')) {
            $query->where('fitur', $request->fitur);
        }

        // Filter by Status (termasuk computed: terlewat & akan-datang)
        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'terlewat') {
                // Status pending (1) dan waktu janji sudah lewat
                $query->where('status', '1')
                      ->whereRaw("CONCAT(tanggal_janji_temu, ' ', IFNULL(waktu_janji_temu, '00:00:00')) < NOW()");
            } elseif ($status === 'akan-datang') {
                // Status pending (1) dan waktu janji belum lewat
                $query->where('status', '1')
                      ->whereRaw("CONCAT(tanggal_janji_temu, ' ', IFNULL(waktu_janji_temu, '00:00:00')) >= NOW()");
            } else {
                // Filter langsung by kode: '2' (Terlaksana) atau '3' (Dibatalkan)
                $query->where('status', $status);
            }
        }

        $janjiTemu = $query->orderBy('tanggal_janji_temu', 'desc')
                           ->orderBy('waktu_janji_temu', 'asc')
                           ->paginate(15);

        return view('admin.janji-temu.index', compact('janjiTemu'));
    }

    /**
     * Show detail of a specific janji temu (Tabungan).
     */
    public function detail($id)
    {
        $janjiTemu = \App\Models\JanjiTemuTabungan::with(['nasabah', 'lokasi'])
            ->findOrFail($id);

        return view('admin.janji-temu.detail', [
            'janjiTemu' => $janjiTemu,
            'fitur' => 'Tabungan',
        ]);
    }

    /**
     * Show detail janji temu pinjaman (tunai). Data utama dari tbl_janji_temu_pinjaman.
     * Form proses mengupdate janji temu dan memicu setujui + cairkan pengajuan (jika ada).
     */
    public function detailPinjaman($id)
    {
        $janjiTemu = JanjiTemuPinjaman::with(['nasabah.user', 'nasabah.dataKtp', 'lokasi', 'pengajuan', 'buktiFoto'])
            ->findOrFail($id);

        $masterBunga = null;
        $masterDenda = null;
        if ($janjiTemu->id_pengajuan && $janjiTemu->pengajuan) {
            $masterBunga = \App\Models\MasterBungaPinjaman::getBungaByDurasi($janjiTemu->pengajuan->durasi);
            $masterDenda = \App\Models\MasterDendaPinjaman::getDendaAktif();
        }

        return view('admin.janji-temu.detail-pinjaman', compact('janjiTemu', 'masterBunga', 'masterDenda'));
    }

    /**
     * Cancel Janji Temu Tabungan.
     */
    public function cancelTabungan(Request $request, $id)
    {
        $request->validate([
            'keterangan_admin' => 'required|string|max:255',
        ]);

        $janjiTemu = \App\Models\JanjiTemuTabungan::findOrFail($id);
        $janjiTemu->update([
            'status' => '3',
            'keterangan_admin' => $request->keterangan_admin,
        ]);

        return redirect()->back()->with('success', 'Janji temu tabungan berhasil dibatalkan.');
    }

    /**
     * Cancel Janji Temu Pinjaman.
     */
    public function cancelPinjaman(Request $request, $id)
    {
        $request->validate([
            'keterangan_admin' => 'required|string|max:255',
        ]);

        $janjiTemu = JanjiTemuPinjaman::findOrFail($id);
        $janjiTemu->update([
            'status' => '3',
            'keterangan_admin' => $request->keterangan_admin,
        ]);

        return redirect()->back()->with('success', 'Janji temu pinjaman berhasil dibatalkan.');
    }
}