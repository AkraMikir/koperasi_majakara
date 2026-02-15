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
        $query = JanjiTemuUniversal::query();

        // Search by Nama Anggota
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where('nama_anggota', 'like', "%{$search}%");
        }

        // Filter by Tanggal
        if ($request->has('tanggal_dari') && $request->tanggal_dari !== '') {
            $query->whereDate('tanggal_janji_temu', '>=', $request->tanggal_dari);
        }

        if ($request->has('tanggal_sampai') && $request->tanggal_sampai !== '') {
            $query->whereDate('tanggal_janji_temu', '<=', $request->tanggal_sampai);
        }

        // Filter by Fitur
        if ($request->has('fitur') && $request->fitur !== '') {
            $query->where('fitur', $request->fitur);
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
}
