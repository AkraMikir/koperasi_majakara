<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JanjiTemuUniversal;
use App\Models\TransTabungan;
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

        // Untuk baris Tabungan: nominal yang ditampilkan = dari trans_tabungan jika sudah diproses
        $nominalTabunganFromTrans = [];
        $tabunganIds = collect($janjiTemu->items())->where('fitur', 'Tabungan')->pluck('id_asli')->unique()->filter();
        if ($tabunganIds->isNotEmpty()) {
            $nominalTabunganFromTrans = TransTabungan::whereIn('id_janji_temu_tabungan', $tabunganIds)
                ->pluck('nominal', 'id_janji_temu_tabungan')
                ->toArray();
        }

        return view('admin.janji-temu.index', compact('janjiTemu', 'nominalTabunganFromTrans'));
    }

    /**
     * Show detail of a specific janji temu.
     */
    public function detail($id)
    {
        // Directly load from JanjiTemuTabungan since ID is from tbl_janji_temu_tabungan
        $janjiTemu = \App\Models\JanjiTemuTabungan::with(['nasabah', 'lokasi', 'transTabungan'])
            ->findOrFail($id);

        return view('admin.janji-temu.detail', [
            'janjiTemu' => $janjiTemu,
            'fitur' => 'Tabungan',
        ]);
    }
}
