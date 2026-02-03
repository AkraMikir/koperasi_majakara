<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JanjiTemuUniversal;
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
}
