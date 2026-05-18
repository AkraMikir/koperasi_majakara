<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\GadaiMasterKategori;

class MasterSlotStorageController extends Controller
{
    public function index(Request $request)
    {
        $kategoriList = GadaiMasterKategori::all();
        $selectedKategori = $request->get('kategori', 'electronic');

        $table = '';
        switch ($selectedKategori) {
            case 'electronic': $table = 'tbl_gadai_grid_electronic'; break;
            case 'vehicle': $table = 'tbl_gadai_grid_vehicle'; break;
            case 'gold': $table = 'tbl_gadai_grid_gold'; break;
            default: $table = 'tbl_gadai_grid_electronic'; break;
        }

        $maxBaris = DB::table($table)->max('baris') ?? 0;
        $maxKolom = DB::table($table)->max('kolom') ?? 0;

        return view('admin.master-data.slot-storage.index', compact('kategoriList', 'selectedKategori', 'maxBaris', 'maxKolom'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|in:electronic,vehicle,gold',
            'jenis' => 'required|in:baris,kolom',
            'jumlah' => 'required|integer|min:1|max:10'
        ]);

        $kategori = $request->kategori;
        $jenis = $request->jenis;
        $jumlah = $request->jumlah;

        $table = '';
        $prefix = '';
        switch ($kategori) {
            case 'electronic': $table = 'tbl_gadai_grid_electronic'; $prefix = 'EL'; break;
            case 'vehicle': $table = 'tbl_gadai_grid_vehicle'; $prefix = 'VH'; break;
            case 'gold': $table = 'tbl_gadai_grid_gold'; $prefix = 'GL'; break;
        }

        $maxBaris = DB::table($table)->max('baris') ?? 0;
        $maxKolom = DB::table($table)->max('kolom') ?? 0;

        $inserts = [];

        if ($jenis == 'baris') {
            for ($b = 1; $b <= $jumlah; $b++) {
                $newBaris = $maxBaris + $b;
                for ($k = 1; $k <= $maxKolom; $k++) {
                    $kode = $prefix . '-' . str_pad($newBaris, 2, '0', STR_PAD_LEFT) . str_pad($k, 2, '0', STR_PAD_LEFT);
                    $inserts[] = [
                        'baris' => $newBaris,
                        'kolom' => $k,
                        'kode_slot' => $kode,
                        'is_occupied' => false,
                        'active_gadai_id' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        } else {
            for ($b = 1; $b <= $maxBaris; $b++) {
                for ($k = 1; $k <= $jumlah; $k++) {
                    $newKolom = $maxKolom + $k;
                    $kode = $prefix . '-' . str_pad($b, 2, '0', STR_PAD_LEFT) . str_pad($b, 2, '0', STR_PAD_LEFT) . str_pad($newKolom, 2, '0', STR_PAD_LEFT);
                    // wait wait. The logic was: $prefix . '-' . str_pad($b,2) . str_pad($newKolom, 2). I had a typo.
                    $kode = $prefix . '-' . str_pad($b, 2, '0', STR_PAD_LEFT) . str_pad($newKolom, 2, '0', STR_PAD_LEFT);
                    $inserts[] = [
                        'baris' => $b,
                        'kolom' => $newKolom,
                        'kode_slot' => $kode,
                        'is_occupied' => false,
                        'active_gadai_id' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        if (count($inserts) > 0) {
            DB::table($table)->insert($inserts);
        }

        return redirect()->back()->with('success', "Berhasil menambahkan $jumlah $jenis baru pada penyimpanan " . ucfirst($kategori) . ".");
    }
}
