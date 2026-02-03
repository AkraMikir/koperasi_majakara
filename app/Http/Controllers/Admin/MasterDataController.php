<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterBungaPinjaman;
use App\Models\MasterDendaPinjaman;
// use App\Models\SukuBunga; // REMOVED
use App\Models\JnsTenorDeposito;
use App\Models\SukuBungaDeposito;
use App\Models\MBarangGadai;
use App\Models\JnsLokasiPerusahaan;
use App\Models\JnsDeposito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterDataController extends Controller
{
    /**
     * Display master data dashboard.
     */
    public function index()
    {
        $stats = [
            'total_bunga_pinjaman' => MasterBungaPinjaman::where('status_aktif', true)->count(),
            'total_denda_pinjaman' => MasterDendaPinjaman::where('status_aktif', true)->count(),
            // 'total_suku_bunga_tabungan' => SukuBunga::count(), // REMOVED
            'total_tenor_deposito' => JnsTenorDeposito::where('aktif', true)->count(),
            'total_barang_gadai' => MBarangGadai::count(),
            'total_lokasi_perusahaan' => JnsLokasiPerusahaan::where('status_aktif', true)->count(),
            'total_jenis_deposito' => 0, 
            'total_jns_akun' => 0, 
            'total_biaya_transfer' => 0, 
        ];

        return view('admin.master-data.index', compact('stats'));
    }

    // ==================== MASTER BUNGA PINJAMAN ====================
    
    public function bungaPinjamanIndex()
    {
        $data = MasterBungaPinjaman::orderBy('durasi_min')->paginate(15);
        return view('admin.master-data.bunga-pinjaman.index', compact('data'));
    }

    public function bungaPinjamanCreate()
    {
        return view('admin.master-data.bunga-pinjaman.create');
    }

    public function bungaPinjamanStore(Request $request)
    {
        $request->validate([
            'durasi_min' => 'required|integer|min:1',
            'durasi_max' => 'required|integer|min:1|gte:durasi_min',
            'bunga_persen' => 'required|numeric|min:0|max:100',
            'keterangan' => 'nullable|string|max:500',
        ]);

        MasterBungaPinjaman::create($request->all());

        return redirect()->route('admin.master-data.bunga-pinjaman.index')
            ->with('success', 'Data berhasil ditambahkan');
    }

    public function bungaPinjamanEdit($id)
    {
        $data = MasterBungaPinjaman::findOrFail($id);
        return view('admin.master-data.bunga-pinjaman.edit', compact('data'));
    }

    public function bungaPinjamanUpdate(Request $request, $id)
    {
        $request->validate([
            'durasi_min' => 'required|integer|min:1',
            'durasi_max' => 'required|integer|min:1|gte:durasi_min',
            'bunga_persen' => 'required|numeric|min:0|max:100',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $data = MasterBungaPinjaman::findOrFail($id);
        $data->update($request->all());

        return redirect()->route('admin.master-data.bunga-pinjaman.index')
            ->with('success', 'Data berhasil diupdate');
    }

    public function bungaPinjamanDestroy($id)
    {
        $data = MasterBungaPinjaman::findOrFail($id);
        $data->delete();

        return redirect()->route('admin.master-data.bunga-pinjaman.index')
            ->with('success', 'Data berhasil dihapus');
    }

    public function bungaPinjamanToggleStatus($id)
    {
        $data = MasterBungaPinjaman::findOrFail($id);
        $data->status_aktif = !$data->status_aktif;
        $data->save();

        return redirect()->back()->with('success', 'Status berhasil diubah');
    }

    // ==================== MASTER DENDA PINJAMAN ====================
    
    public function dendaPinjamanIndex()
    {
        $data = MasterDendaPinjaman::paginate(15);
        return view('admin.master-data.denda-pinjaman.index', compact('data'));
    }

    public function dendaPinjamanCreate()
    {
        return view('admin.master-data.denda-pinjaman.create');
    }

    public function dendaPinjamanStore(Request $request)
    {
        $request->validate([
            'denda_persen' => 'required|numeric|min:0|max:100',
            'keterangan' => 'nullable|string|max:500',
        ]);

        MasterDendaPinjaman::create($request->all());

        return redirect()->route('admin.master-data.denda-pinjaman.index')
            ->with('success', 'Data berhasil ditambahkan');
    }

    public function dendaPinjamanEdit($id)
    {
        $data = MasterDendaPinjaman::findOrFail($id);
        return view('admin.master-data.denda-pinjaman.edit', compact('data'));
    }

    public function dendaPinjamanUpdate(Request $request, $id)
    {
        $request->validate([
            'denda_persen' => 'required|numeric|min:0|max:100',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $data = MasterDendaPinjaman::findOrFail($id);
        $data->update($request->all());

        return redirect()->route('admin.master-data.denda-pinjaman.index')
            ->with('success', 'Data berhasil diupdate');
    }

    public function dendaPinjamanDestroy($id)
    {
        $data = MasterDendaPinjaman::findOrFail($id);
        $data->delete();

        return redirect()->route('admin.master-data.denda-pinjaman.index')
            ->with('success', 'Data berhasil dihapus');
    }

    public function dendaPinjamanToggleStatus($id)
    {
        $data = MasterDendaPinjaman::findOrFail($id);
        $data->status_aktif = !$data->status_aktif;
        $data->save();

        return redirect()->back()->with('success', 'Status berhasil diubah');
    }

    // ==================== SUKU BUNGA TABUNGAN (REMOVED) ====================
    /*
    public function sukuBungaTabunganIndex() ...
    */

    // ==================== TENOR DEPOSITO ====================
    
    public function tenorDepositoIndex()
    {
        $data = JnsTenorDeposito::with('sukuBunga')->orderBy('tenor_hari')->paginate(15);
        return view('admin.master-data.tenor-deposito.index', compact('data'));
    }

    public function tenorDepositoCreate()
    {
        return view('admin.master-data.tenor-deposito.create');
    }

    public function tenorDepositoStore(Request $request)
    {
        $request->validate([
            'tenor_hari' => 'required|integer|min:1',
            'tenor_bulan' => 'required|integer|min:1',
        ]);

        JnsTenorDeposito::create($request->all());

        return redirect()->route('admin.master-data.tenor-deposito.index')
            ->with('success', 'Data berhasil ditambahkan');
    }

    public function tenorDepositoEdit($id)
    {
        $data = JnsTenorDeposito::findOrFail($id);
        return view('admin.master-data.tenor-deposito.edit', compact('data'));
    }

    public function tenorDepositoUpdate(Request $request, $id)
    {
        $request->validate([
            'tenor_hari' => 'required|integer|min:1',
            'tenor_bulan' => 'required|integer|min:1',
        ]);

        $data = JnsTenorDeposito::findOrFail($id);
        $data->update($request->all());

        return redirect()->route('admin.master-data.tenor-deposito.index')
            ->with('success', 'Data berhasil diupdate');
    }

    public function tenorDepositoDestroy($id)
    {
        $data = JnsTenorDeposito::findOrFail($id);
        
        // Check if tenor has bunga deposito
        if ($data->sukuBunga()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus tenor yang masih memiliki data suku bunga');
        }

        $data->delete();

        return redirect()->route('admin.master-data.tenor-deposito.index')
            ->with('success', 'Data berhasil dihapus');
    }

    public function tenorDepositoToggleStatus($id)
    {
        $data = JnsTenorDeposito::findOrFail($id);
        $data->aktif = !$data->aktif;
        $data->save();

        return redirect()->back()->with('success', 'Status berhasil diubah');
    }

    // ==================== SUKU BUNGA DEPOSITO ====================
    
    public function sukuBungaDepositoIndex()
    {
        $data = SukuBungaDeposito::with('tenor')->orderBy('tenor_id')->paginate(15);
        return view('admin.master-data.suku-bunga-deposito.index', compact('data'));
    }

    public function sukuBungaDepositoCreate()
    {
        $tenors = JnsTenorDeposito::where('aktif', true)->orderBy('tenor_hari')->get();
        return view('admin.master-data.suku-bunga-deposito.create', compact('tenors'));
    }

    public function sukuBungaDepositoStore(Request $request)
    {
        $request->validate([
            'tenor_id' => 'required|exists:jns_tenor_deposito,id',
            'min_nominal' => 'required|numeric|min:0',
            'max_nominal' => 'required|numeric|min:0|gte:min_nominal',
            'bunga' => 'required|numeric|min:0|max:100',
        ]);

        SukuBungaDeposito::create($request->all());

        return redirect()->route('admin.master-data.suku-bunga-deposito.index')
            ->with('success', 'Data berhasil ditambahkan');
    }

    public function sukuBungaDepositoEdit($id)
    {
        $data = SukuBungaDeposito::findOrFail($id);
        $tenors = JnsTenorDeposito::where('aktif', true)->orderBy('tenor_hari')->get();
        return view('admin.master-data.suku-bunga-deposito.edit', compact('data', 'tenors'));
    }

    public function sukuBungaDepositoUpdate(Request $request, $id)
    {
        $request->validate([
            'tenor_id' => 'required|exists:jns_tenor_deposito,id',
            'min_nominal' => 'required|numeric|min:0',
            'max_nominal' => 'required|numeric|min:0|gte:min_nominal',
            'bunga' => 'required|numeric|min:0|max:100',
        ]);

        $data = SukuBungaDeposito::findOrFail($id);
        $data->update($request->all());

        return redirect()->route('admin.master-data.suku-bunga-deposito.index')
            ->with('success', 'Data berhasil diupdate');
    }

    public function sukuBungaDepositoDestroy($id)
    {
        $data = SukuBungaDeposito::findOrFail($id);
        $data->delete();

        return redirect()->route('admin.master-data.suku-bunga-deposito.index')
            ->with('success', 'Data berhasil dihapus');
    }

    public function sukuBungaDepositoToggleStatus($id)
    {
        $data = SukuBungaDeposito::findOrFail($id);
        $data->status = !$data->status;
        $data->save();

        return redirect()->back()->with('success', 'Status berhasil diubah');
    }

    // ==================== MASTER BARANG GADAI ====================
    
    public function barangGadaiIndex()
    {
        $data = MBarangGadai::paginate(15);
        return view('admin.master-data.barang-gadai.index', compact('data'));
    }

    public function barangGadaiCreate()
    {
        return view('admin.master-data.barang-gadai.create');
    }

    public function barangGadaiStore(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        MBarangGadai::create($request->all());

        return redirect()->route('admin.master-data.barang-gadai.index')
            ->with('success', 'Data berhasil ditambahkan');
    }

    public function barangGadaiEdit($id)
    {
        $data = MBarangGadai::findOrFail($id);
        return view('admin.master-data.barang-gadai.edit', compact('data'));
    }

    public function barangGadaiUpdate(Request $request, $id)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $data = MBarangGadai::findOrFail($id);
        $data->update($request->all());

        return redirect()->route('admin.master-data.barang-gadai.index')
            ->with('success', 'Data berhasil diupdate');
    }

    public function barangGadaiDestroy($id)
    {
        $data = MBarangGadai::findOrFail($id);
        
        // Check if barang has item gadai
        if ($data->itemGadai()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus barang yang masih digunakan');
        }

        $data->delete();

        return redirect()->route('admin.master-data.barang-gadai.index')
            ->with('success', 'Data berhasil dihapus');
    }

    // ==================== LOKASI PERUSAHAAN ====================
    
    public function lokasiPerusahaanIndex()
    {
        $data = JnsLokasiPerusahaan::paginate(15);
        return view('admin.master-data.lokasi-perusahaan.index', compact('data'));
    }

    public function lokasiPerusahaanCreate()
    {
        return view('admin.master-data.lokasi-perusahaan.create');
    }

    public function lokasiPerusahaanStore(Request $request)
    {
        $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'alamat_lengkap' => 'required|string',
            'kota' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'tipe_lokasi' => 'required|string|max:50',
        ]);

        JnsLokasiPerusahaan::create($request->all());

        return redirect()->route('admin.master-data.lokasi-perusahaan.index')
            ->with('success', 'Data berhasil ditambahkan');
    }

    public function lokasiPerusahaanEdit($id)
    {
        $data = JnsLokasiPerusahaan::findOrFail($id);
        return view('admin.master-data.lokasi-perusahaan.edit', compact('data'));
    }

    public function lokasiPerusahaanUpdate(Request $request, $id)
    {
        $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'alamat_lengkap' => 'required|string',
            'kota' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'tipe_lokasi' => 'required|string|max:50',
        ]);

        $data = JnsLokasiPerusahaan::findOrFail($id);
        $data->update($request->all());

        return redirect()->route('admin.master-data.lokasi-perusahaan.index')
            ->with('success', 'Data berhasil diupdate');
    }

    public function lokasiPerusahaanDestroy($id)
    {
        $data = JnsLokasiPerusahaan::findOrFail($id);
        $data->delete();

        return redirect()->route('admin.master-data.lokasi-perusahaan.index')
            ->with('success', 'Data berhasil dihapus');
    }

    public function lokasiPerusahaanToggleStatus($id)
    {
        $data = JnsLokasiPerusahaan::findOrFail($id);
        $data->status_aktif = !$data->status_aktif;
        $data->save();

        return redirect()->back()->with('success', 'Status berhasil diubah');
    }

    // ==================== JENIS DEPOSITO ====================
    
    public function jenisDepositoIndex()
    {
        $data = JnsDeposito::paginate(15);
        return view('admin.master-data.jenis-deposito.index', compact('data'));
    }

    public function jenisDepositoCreate()
    {
        return view('admin.master-data.jenis-deposito.create');
    }

    public function jenisDepositoStore(Request $request)
    {
        $request->validate([
            'nama_jenis' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        JnsDeposito::create($request->all());

        return redirect()->route('admin.master-data.jenis-deposito.index')
            ->with('success', 'Data berhasil ditambahkan');
    }

    public function jenisDepositoEdit($id)
    {
        $data = JnsDeposito::findOrFail($id);
        return view('admin.master-data.jenis-deposito.edit', compact('data'));
    }

    public function jenisDepositoUpdate(Request $request, $id)
    {
        $request->validate([
            'nama_jenis' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $data = JnsDeposito::findOrFail($id);
        $data->update($request->all());

        return redirect()->route('admin.master-data.jenis-deposito.index')
            ->with('success', 'Data berhasil diupdate');
    }

    public function jenisDepositoDestroy($id)
    {
        $data = JnsDeposito::findOrFail($id);
        
        // Check if jenis deposito has pengajuan
        if ($data->pengajuan()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus jenis deposito yang masih digunakan');
        }

        $data->delete();

        return redirect()->route('admin.master-data.jenis-deposito.index')
            ->with('success', 'Data berhasil dihapus');
    }

    public function jenisDepositoToggleStatus($id)
    {
        $data = JnsDeposito::findOrFail($id);
        $data->status_aktif = !$data->status_aktif;
        $data->save();

        return redirect()->back()->with('success', 'Status berhasil diubah');
    }

    // ===== JNS AKUN (REMOVED) =====
    /*
    public function jnsAkunIndex() ...
    */

    // ===== BIAYA TRANSFER =====

    public function biayaTransferIndex()
    {
        $data = \App\Models\BiayaTransfer::latest()->paginate(15);
        return view('admin.master-data.biaya-transfer.index', compact('data'));
    }

    public function biayaTransferCreate()
    {
        return view('admin.master-data.biaya-transfer.create');
    }

    public function biayaTransferStore(Request $request)
    {
        $request->validate([
            'bank_pengirim' => 'required|string|max:50',
            'bank_penerima' => 'required|string|max:50',
            'biaya_admin' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        \App\Models\BiayaTransfer::create($request->all());

        return redirect()->route('admin.master-data.biaya-transfer.index')
            ->with('success', 'Data berhasil ditambahkan');
    }

    public function biayaTransferEdit($id)
    {
        $data = \App\Models\BiayaTransfer::findOrFail($id);
        return view('admin.master-data.biaya-transfer.edit', compact('data'));
    }

    public function biayaTransferUpdate(Request $request, $id)
    {
        $request->validate([
            'bank_pengirim' => 'required|string|max:50',
            'bank_penerima' => 'required|string|max:50',
            'biaya_admin' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $data = \App\Models\BiayaTransfer::findOrFail($id);
        $data->update($request->all());

        return redirect()->route('admin.master-data.biaya-transfer.index')
            ->with('success', 'Data berhasil diupdate');
    }

    public function biayaTransferDestroy($id)
    {
        $data = \App\Models\BiayaTransfer::findOrFail($id);
        $data->delete();

        return redirect()->route('admin.master-data.biaya-transfer.index')
            ->with('success', 'Data berhasil dihapus');
    }

    public function biayaTransferToggleStatus($id)
    {
        $data = \App\Models\BiayaTransfer::findOrFail($id);
        $data->is_active = !$data->is_active;
        $data->save();

        return redirect()->back()->with('success', 'Status berhasil diubah');
    }
}
