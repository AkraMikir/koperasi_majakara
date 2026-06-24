<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterBungaPinjaman;
use App\Models\MasterDendaPinjaman;
use App\Models\MasterDendaDeposito;
use App\Models\SukuBunga;
use App\Models\JnsTenorDeposito;
use App\Models\SukuBungaDeposito;
use App\Models\MBarangGadai;
use App\Models\JnsLokasiPerusahaan;
use App\Models\AdminOperasional;
use App\Models\MasterDataBankRegis;
use App\Models\JnsBank;
use App\Models\LogoBank;
use App\Models\User;
use App\Models\MasterTujuanPinjaman;
use App\Models\SyaratKetentuanLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

class MasterDataController extends Controller
{
    /**
     * Check if user has permission to CRUD master data
     */
    protected function checkCrudPermission()
    {
        if (!app(\App\Services\AdminPermissionService::class)->canCrudMasterData(auth()->user())) {
            abort(403, 'Anda tidak memiliki akses untuk fitur ini. Hanya Admin Utama yang dapat mengelola Master Data.');
        }
    }

    /**
     * Display master data dashboard.
     */
    public function index()
    {
        $stats = [
            'total_bunga_pinjaman' => MasterBungaPinjaman::where('status_aktif', true)->count(),
            'total_denda_pinjaman' => MasterDendaPinjaman::where('status_aktif', true)->count(),
            'total_suku_bunga_tabungan' => 0, // Tabel suku_bunga / fitur suku bunga tabungan sudah tidak dipakai
            'total_tenor_deposito' => JnsTenorDeposito::where('aktif', 'y')->count(),
            'total_barang_gadai' => \App\Models\GadaiMasterItem::count(),
            'total_kategori_gadai' => \App\Models\GadaiMasterKategori::count(),
            'total_lokasi_perusahaan' => JnsLokasiPerusahaan::where('status_aktif', true)->count(),
            'total_jenis_deposito' => 0, 
            'total_biaya_transfer' => 0,
            'total_kategori_deposito' => \App\Models\KategoriDeposito::where('status', 'aktif')->count(),
            'total_admin_operasional' => AdminOperasional::where('status', 'aktif')->count(),
            'total_rekening_perusahaan' => JnsBank::count(),
            'total_inap_kendaraan' => \App\Models\GadaiMasterInapKendaraan::count(),
            'total_denda_deposito' => MasterDendaDeposito::where('status_aktif', true)->count(),
            'total_tujuan_pinjaman' => MasterTujuanPinjaman::count(),
            'total_bank_regis' => MasterDataBankRegis::where('status', true)->count(),
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
        $this->checkCrudPermission();
        return view('admin.master-data.bunga-pinjaman.create');
    }

    public function bungaPinjamanStore(Request $request)
    {
        $this->checkCrudPermission();
        $request->validate([
            'durasi_min' => 'required|integer|min:1',
            'durasi_max' => 'required|integer|min:1|gte:durasi_min',
            'durasi_pilihan' => 'nullable|integer|min:1',
            'bunga_persen' => 'required|numeric|min:0|max:100',
            'keterangan' => 'nullable|string|max:500',
        ]);

        MasterBungaPinjaman::create($request->all());

        return redirect()->route('admin.master-data.bunga-pinjaman.index')
            ->with('success', 'Data berhasil ditambahkan');
    }

    public function bungaPinjamanEdit($id)
    {
        $this->checkCrudPermission();
        $data = MasterBungaPinjaman::findOrFail($id);
        return view('admin.master-data.bunga-pinjaman.edit', compact('data'));
    }

    public function bungaPinjamanUpdate(Request $request, $id)
    {
        $this->checkCrudPermission();
        $request->validate([
            'durasi_min' => 'required|integer|min:1',
            'durasi_max' => 'required|integer|min:1|gte:durasi_min',
            'durasi_pilihan' => 'nullable|integer|min:1',
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
        $this->checkCrudPermission();
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
        $this->checkCrudPermission();
        return view('admin.master-data.denda-pinjaman.create');
    }

    public function dendaPinjamanStore(Request $request)
    {
        $this->checkCrudPermission();
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
        $this->checkCrudPermission();
        $data = MasterDendaPinjaman::findOrFail($id);
        return view('admin.master-data.denda-pinjaman.edit', compact('data'));
    }

    public function dendaPinjamanUpdate(Request $request, $id)
    {
        $this->checkCrudPermission();
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
        $this->checkCrudPermission();
        $data = MasterDendaPinjaman::findOrFail($id);
        $data->status_aktif = !$data->status_aktif;
        $data->save();

        return redirect()->back()->with('success', 'Status berhasil diubah');
    }

    // ==================== MASTER DENDA DEPOSITO ====================
    
    public function dendaDepositoIndex()
    {
        $data = MasterDendaDeposito::paginate(15);
        return view('admin.master-data.denda-deposito.index', compact('data'));
    }

    public function dendaDepositoCreate()
    {
        $this->checkCrudPermission();
        return view('admin.master-data.denda-deposito.create');
    }

    public function dendaDepositoStore(Request $request)
    {
        $this->checkCrudPermission();
        $request->validate([
            'denda_persen' => 'required|numeric|min:0|max:100',
            'keterangan' => 'nullable|string|max:500',
        ]);

        MasterDendaDeposito::create($request->all());

        return redirect()->route('admin.master-data.denda-deposito.index')
            ->with('success', 'Data berhasil ditambahkan');
    }

    public function dendaDepositoEdit($id)
    {
        $this->checkCrudPermission();
        $data = MasterDendaDeposito::findOrFail($id);
        return view('admin.master-data.denda-deposito.edit', compact('data'));
    }

    public function dendaDepositoUpdate(Request $request, $id)
    {
        $this->checkCrudPermission();
        $request->validate([
            'denda_persen' => 'required|numeric|min:0|max:100',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $data = MasterDendaDeposito::findOrFail($id);
        $data->update($request->all());

        return redirect()->route('admin.master-data.denda-deposito.index')
            ->with('success', 'Data berhasil diupdate');
    }

    public function dendaDepositoDestroy($id)
    {
        $data = MasterDendaDeposito::findOrFail($id);
        $data->delete();

        return redirect()->route('admin.master-data.denda-deposito.index')
            ->with('success', 'Data berhasil dihapus');
    }

    public function dendaDepositoToggleStatus($id)
    {
        $this->checkCrudPermission();
        $data = MasterDendaDeposito::findOrFail($id);
        $data->status_aktif = !$data->status_aktif;
        $data->save();

        return redirect()->back()->with('success', 'Status berhasil diubah');
    }

    // ==================== SUKU BUNGA TABUNGAN ====================

    public function sukuBungaTabunganIndex()
    {
        $data = SukuBunga::orderBy('jenis_bunga')->paginate(15);
        return view('admin.master-data.suku-bunga-tabungan.index', compact('data'));
    }

    public function sukuBungaTabunganCreate()
    {
        $this->checkCrudPermission();
        return view('admin.master-data.suku-bunga-tabungan.create');
    }

    public function sukuBungaTabunganStore(Request $request)
    {
        $this->checkCrudPermission();
        $request->validate([
            'jenis_bunga' => 'required|string|max:255',
            'opsi_val' => 'required|numeric|min:0|max:100',
        ]);

        SukuBunga::create($request->only(['jenis_bunga', 'opsi_val']));

        return redirect()->route('admin.master-data.suku-bunga-tabungan.index')
            ->with('success', 'Data berhasil ditambahkan');
    }

    public function sukuBungaTabunganEdit($id)
    {
        $data = SukuBunga::findOrFail($id);
        return view('admin.master-data.suku-bunga-tabungan.edit', compact('data'));
    }

    public function sukuBungaTabunganUpdate(Request $request, $id)
    {
        $request->validate([
            'jenis_bunga' => 'required|string|max:255',
            'opsi_val' => 'required|numeric|min:0|max:100',
        ]);

        $data = SukuBunga::findOrFail($id);
        $data->update($request->only(['jenis_bunga', 'opsi_val']));

        return redirect()->route('admin.master-data.suku-bunga-tabungan.index')
            ->with('success', 'Data berhasil diupdate');
    }

    public function sukuBungaTabunganDestroy($id)
    {
        $data = SukuBunga::findOrFail($id);
        $data->delete();

        return redirect()->route('admin.master-data.suku-bunga-tabungan.index')
            ->with('success', 'Data berhasil dihapus');
    }

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
        $request->merge([
            'biaya_admin' => preg_replace('/[^\d]/', '', $request->biaya_admin),
            'min_saldo_non_bca' => preg_replace('/[^\d]/', '', $request->min_saldo_non_bca),
        ]);

        $request->validate([
            'bank_pengirim' => 'required|string|max:50',
            'bank_penerima' => 'required|string|max:50',
            'biaya_admin' => 'required|numeric|min:0',
            'min_saldo_non_bca' => 'nullable|numeric|min:0',
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
        $request->merge([
            'biaya_admin' => preg_replace('/[^\d]/', '', $request->biaya_admin),
            'min_saldo_non_bca' => preg_replace('/[^\d]/', '', $request->min_saldo_non_bca),
        ]);

        $request->validate([
            'bank_pengirim' => 'required|string|max:50',
            'bank_penerima' => 'required|string|max:50',
            'biaya_admin' => 'required|numeric|min:0',
            'min_saldo_non_bca' => 'nullable|numeric|min:0',
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

    // ==================== REKENING PERUSAHAAN (JNS_BANK) ====================

    public function rekeningPerusahaanIndex()
    {
        $data = JnsBank::orderBy('pemilik')->orderBy('bank')->paginate(15);
        return view('admin.master-data.rekening-perusahaan.index', compact('data'));
    }

    public function rekeningPerusahaanCreate()
    {
        $this->checkCrudPermission();
        $logos = LogoBank::all();
        return view('admin.master-data.rekening-perusahaan.create', compact('logos'));
    }

    public function rekeningPerusahaanStore(Request $request)
    {
        $this->checkCrudPermission();
        $request->validate([
            'pemilik' => 'required|string|max:100',
            'nama' => 'required|string|max:100',
            'no_rek' => 'required|string|max:30',
            'bank' => 'required|string|max:50',
            'cabang' => 'nullable|string|max:100',
            'kode_bank' => 'nullable|string|max:10',
            'status' => 'required|in:aktif,non-aktif',
            'logo' => 'nullable|string',
        ]);
        JnsBank::create($request->all());
        return redirect()->route('admin.master-data.rekening-perusahaan.index')
            ->with('success', 'Rekening perusahaan berhasil ditambahkan');
    }

    public function rekeningPerusahaanEdit($id)
    {
        $this->checkCrudPermission();
        $data = JnsBank::findOrFail($id);
        $logos = LogoBank::all();
        return view('admin.master-data.rekening-perusahaan.edit', compact('data', 'logos'));
    }

    public function rekeningPerusahaanUpdate(Request $request, $id)
    {
        $this->checkCrudPermission();
        $request->validate([
            'pemilik' => 'required|string|max:100',
            'nama' => 'required|string|max:100',
            'no_rek' => 'required|string|max:30',
            'bank' => 'required|string|max:50',
            'cabang' => 'nullable|string|max:100',
            'kode_bank' => 'nullable|string|max:10',
            'status' => 'required|in:aktif,non-aktif',
            'logo' => 'nullable|string',
        ]);
        $data = JnsBank::findOrFail($id);
        $data->update($request->all());
        return redirect()->route('admin.master-data.rekening-perusahaan.index')
            ->with('success', 'Rekening perusahaan berhasil diupdate');
    }

    public function rekeningPerusahaanDestroy($id)
    {
        $this->checkCrudPermission();
        $data = JnsBank::findOrFail($id);
        $data->delete();
        return redirect()->route('admin.master-data.rekening-perusahaan.index')
            ->with('success', 'Rekening perusahaan berhasil dihapus');
    }

    // ==================== MANAJEMEN ADMIN OPERASIONAL ====================

    public function adminOperasionalIndex(Request $request)
    {
        $this->checkCrudPermission();

        $query = AdminOperasional::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nomor_hp', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $adminList = $query->latest()->paginate(10)->withQueryString();

        return view('admin.master-data.admin-operasional.index', compact('adminList'));
    }

    public function adminOperasionalCreate()
    {
        $this->checkCrudPermission();
        return view('admin.master-data.admin-operasional.create');
    }

    public function adminOperasionalStore(Request $request)
    {
        $this->checkCrudPermission();

        $request->validate([
            'nama'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email',
            'nomor_hp'              => 'required|string|max:20',
            'password'              => 'required|string|min:8|confirmed',
        ], [
            'nama.required'                 => 'Nama wajib diisi.',
            'email.required'                => 'Email wajib diisi.',
            'email.email'                   => 'Format email tidak valid.',
            'email.unique'                  => 'Email sudah digunakan.',
            'nomor_hp.required'             => 'Nomor HP wajib diisi.',
            'password.required'             => 'Password wajib diisi.',
            'password.min'                  => 'Password minimal 8 karakter.',
            'password.confirmed'            => 'Konfirmasi password tidak cocok.',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'nama'              => $request->nama,
                'email'             => $request->email,
                'nomor_hp'          => $request->nomor_hp,
                'password'          => Hash::make($request->password),
                'pin'               => null,
                'foto'              => 'default-avatar.jpg',
                'role'              => 'admin_operasional',
                'email_verified_at' => now(),
            ]);

            AdminOperasional::create([
                'user_id' => $user->id,
                'status'  => 'aktif',
            ]);
        });

        $newAdmin = AdminOperasional::with('user')->where('user_id', User::where('email', $request->email)->value('id'))->first();
        app(\App\Services\ActivityLogService::class)->logAdminOperasionalAction('create', $request->nama, $newAdmin->id ?? null);

        return redirect()->route('admin.master-data.admin-operasional.index')
            ->with('success', 'Akun Admin Operasional berhasil ditambahkan.');
    }

    public function adminOperasionalEdit($id)
    {
        $this->checkCrudPermission();
        $adminOp = AdminOperasional::with('user')->findOrFail($id);
        return view('admin.master-data.admin-operasional.edit', compact('adminOp'));
    }

    public function adminOperasionalUpdate(Request $request, $id)
    {
        $this->checkCrudPermission();

        $adminOp = AdminOperasional::with('user')->findOrFail($id);

        $request->validate([
            'nama'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $adminOp->user_id,
            'nomor_hp'  => 'required|string|max:20',
            'password'  => 'nullable|string|min:8|confirmed',
        ], [
            'nama.required'         => 'Nama wajib diisi.',
            'email.required'        => 'Email wajib diisi.',
            'email.email'           => 'Format email tidak valid.',
            'email.unique'          => 'Email sudah digunakan akun lain.',
            'nomor_hp.required'     => 'Nomor HP wajib diisi.',
            'password.min'          => 'Password minimal 8 karakter.',
            'password.confirmed'    => 'Konfirmasi password tidak cocok.',
        ]);

        DB::transaction(function () use ($request, $adminOp) {
            $userData = [
                'nama'     => $request->nama,
                'email'    => $request->email,
                'nomor_hp' => $request->nomor_hp,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $adminOp->user->update($userData);
        });

        app(\App\Services\ActivityLogService::class)->logAdminOperasionalAction('update', $adminOp->user->nama, $adminOp->id);

        return redirect()->route('admin.master-data.admin-operasional.index')
            ->with('success', 'Akun Admin Operasional berhasil diperbarui.');
    }

    public function adminOperasionalDestroy($id)
    {
        $this->checkCrudPermission();

        $adminOp = AdminOperasional::with('user')->findOrFail($id);

        // Cegah menghapus diri sendiri
        if ($adminOp->user_id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $namaAdmin = $adminOp->user->nama ?? 'N/A';
        $adminId = $adminOp->id;
        DB::transaction(function () use ($adminOp) {
            $user = $adminOp->user;
            $adminOp->delete();
            $user->delete();
        });

        app(\App\Services\ActivityLogService::class)->logAdminOperasionalAction('delete', $namaAdmin, $adminId);

        return redirect()->route('admin.master-data.admin-operasional.index')
            ->with('success', 'Akun Admin Operasional berhasil dihapus.');
    }

    public function adminOperasionalToggleStatus($id)
    {
        $this->checkCrudPermission();

        $adminOp = AdminOperasional::findOrFail($id);
        $adminOp->status = $adminOp->status === 'aktif' ? 'nonaktif' : 'aktif';
        $adminOp->save();

        $statusText = $adminOp->status === 'aktif' ? 'diaktifkan' : 'dinonaktifkan';
        $action = $adminOp->status === 'aktif' ? 'toggle_aktif' : 'toggle_nonaktif';
        $adminOp->load('user');
        app(\App\Services\ActivityLogService::class)->logAdminOperasionalAction($action, $adminOp->user->nama ?? 'N/A', $adminOp->id);

        return redirect()->back()->with('success', "Akun Admin Operasional berhasil {$statusText}.");
    }
    // ==================== GADAI DEBUGGER (TIME TRAVEL) ====================

    public function gadaiDebuggerIndex()
    {
        $this->checkCrudPermission();
        $gadaiList = \App\Models\GadaiActive::with('item')->get();
        return view('admin.master-data.gadai-debugger.index', compact('gadaiList'));
    }

    public function gadaiDebuggerMajuHari(Request $request)
    {
        $this->checkCrudPermission();
        $request->validate([
            'days' => 'required|integer|min:1|max:365'
        ]);

        $days = (int) $request->days;

        // Kurangi tanggal jatuh tempo, tenggang, dan mulai di semua gadai_active (mensimulasikan waktu maju)
        DB::table('tbl_gadai_active')->update([
            'tgl_mulai' => DB::raw("DATE_SUB(tgl_mulai, INTERVAL $days DAY)"),
            'tgl_jatuh_tempo' => DB::raw("DATE_SUB(tgl_jatuh_tempo, INTERVAL $days DAY)"),
            'tgl_tenggang' => DB::raw("DATE_SUB(tgl_tenggang, INTERVAL $days DAY)"),
        ]);

        // Jalankan artisan command untuk cek status secara paksa
        \Illuminate\Support\Facades\Artisan::call('gadai:check-status');
        $output = \Illuminate\Support\Facades\Artisan::output();

        return redirect()->route('admin.master-data.gadai-debugger.index')
            ->with('success', "Berhasil mensimulasikan waktu maju $days hari dan mengecek status Gadai!")
            ->with('output', $output);
    }

    // ==================== PINJAMAN DEBUGGER (TIME TRAVEL) ====================

    public function pinjamanDebuggerIndex()
    {
        $this->checkCrudPermission();
        // Load some active pinjaman data to show
        $pinjamanList = \App\Models\PinjamanH::where('lunas', 'belum')
            ->with(['nasabah.user', 'tempoBulanan', 'tempoMingguan'])
            ->latest()
            ->take(10)
            ->get();
        return view('admin.master-data.pinjaman-debugger.index', compact('pinjamanList'));
    }

    public function pinjamanDebuggerMajuHari(Request $request)
    {
        $this->checkCrudPermission();
        $request->validate([
            'days' => 'required|integer|min:1|max:365'
        ]);

        $days = (int) $request->days;

        // Kurangi tanggal jatuh tempo di angsuran bulanan
        DB::table('tempo_pinjaman_b')->update([
            'tgl_jatuh_tempo' => DB::raw("DATE_SUB(tgl_jatuh_tempo, INTERVAL $days DAY)"),
        ]);

        // Kurangi tanggal jatuh tempo di angsuran mingguan
        DB::table('tempo_pinjaman_m')->update([
            'tgl_jatuh_tempo' => DB::raw("DATE_SUB(tgl_jatuh_tempo, INTERVAL $days DAY)"),
        ]);

        // Jalankan Cron Job untuk meng-update denda di database seketika
        Artisan::call('pinjaman:update-telat-status');
        $output = Artisan::output();

        return redirect()->route('admin.master-data.pinjaman-debugger.index')
            ->with('success', "Berhasil memundurkan tanggal jatuh tempo angsuran pinjaman sebanyak $days hari dan denda telah di-generate ke database!")
            ->with('output', $output);
    }

    // ==================== DEPOSITO DEBUGGER (TIME TRAVEL) ====================

    public function depositoDebuggerIndex()
    {
        $this->checkCrudPermission();
        $depositoList = \App\Models\DepositoH::where('status', 'aktif')
            ->with(['nasabah.user'])
            ->latest()
            ->take(10)
            ->get();
        return view('admin.master-data.deposito-debugger.index', compact('depositoList'));
    }

    public function depositoDebuggerMajuHari(Request $request)
    {
        $this->checkCrudPermission();
        $request->validate([
            'days' => 'required|integer|min:1|max:365'
        ]);

        $days = (int) $request->days;

        // Kurangi tanggal mulai dan jatuh tempo di deposito aktif
        DB::table('tbl_deposito_h')->update([
            'tgl_mulai'       => DB::raw("DATE_SUB(tgl_mulai, INTERVAL $days DAY)"),
            'tgl_jatuh_tempo' => DB::raw("DATE_SUB(tgl_jatuh_tempo, INTERVAL $days DAY)"),
        ]);

        // Jalankan Cron Job deposito
        Artisan::call('deposito:generate-peringatan');
        $output = Artisan::output();

        return redirect()->route('admin.master-data.deposito-debugger.index')
            ->with('success', "Berhasil memundurkan tanggal deposito sebanyak $days hari dan mengecek jatuh tempo!")
            ->with('output', $output);
    }

    // ==================== JNS FITUR, VIA, & TRANSAKSI VIEW ====================

    public function fiturIndex()
    {
        $data = DB::table('jns_fitur')->paginate(15);
        return view('admin.master-data.fitur.index', compact('data'));
    }

    public function viaIndex()
    {
        $data = DB::table('jns_via')->paginate(15);
        return view('admin.master-data.via.index', compact('data'));
    }

    public function transaksiIndex()
    {
        $data = DB::table('jns_transaksi')->paginate(15);
        return view('admin.master-data.transaksi.index', compact('data'));
    }

    // ==================== MASTER TUJUAN PINJAMAN ====================

    protected function checkTujuanPermission()
    {
        $user = auth()->user();
        if (!app(\App\Services\AdminPermissionService::class)->canCrudMasterData($user) && $user->role !== 'operasional') {
            abort(403, 'Anda tidak memiliki akses untuk mengelola data ini. Hanya Admin Utama dan Operasional yang diizinkan.');
        }
    }

    public function tujuanPinjamanIndex()
    {
        $data = MasterTujuanPinjaman::latest()->paginate(15);
        return view('admin.master-data.tujuan-pinjaman.index', compact('data'));
    }

    public function tujuanPinjamanCreate()
    {
        $this->checkTujuanPermission();
        return view('admin.master-data.tujuan-pinjaman.create');
    }

    public function tujuanPinjamanStore(Request $request)
    {
        $this->checkTujuanPermission();
        $request->validate([
            'tujuan' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:500',
        ]);

        MasterTujuanPinjaman::create([
            'tujuan' => $request->tujuan,
            'keterangan' => $request->keterangan,
            'status' => $request->has('status') ? (bool) $request->status : true,
        ]);

        return redirect()->route('admin.master-data.tujuan-pinjaman.index')
            ->with('success', 'Tujuan pinjaman berhasil ditambahkan');
    }

    public function tujuanPinjamanEdit($id)
    {
        $this->checkTujuanPermission();
        $data = MasterTujuanPinjaman::findOrFail($id);
        return view('admin.master-data.tujuan-pinjaman.edit', compact('data'));
    }

    public function tujuanPinjamanUpdate(Request $request, $id)
    {
        $this->checkTujuanPermission();
        $request->validate([
            'tujuan' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $data = MasterTujuanPinjaman::findOrFail($id);
        $data->update([
            'tujuan' => $request->tujuan,
            'keterangan' => $request->keterangan,
            'status' => $request->has('status') ? (bool) $request->status : true,
        ]);

        return redirect()->route('admin.master-data.tujuan-pinjaman.index')
            ->with('success', 'Tujuan pinjaman berhasil diperbarui');
    }

    public function tujuanPinjamanDestroy($id)
    {
        $this->checkTujuanPermission();
        $data = MasterTujuanPinjaman::findOrFail($id);
        $data->delete();

        return redirect()->route('admin.master-data.tujuan-pinjaman.index')
            ->with('success', 'Tujuan pinjaman berhasil dihapus');
    }

    public function tujuanPinjamanToggleStatus($id)
    {
        $this->checkTujuanPermission();
        $data = MasterTujuanPinjaman::findOrFail($id);
        $data->status = !$data->status;
        $data->save();
        return redirect()->back()->with('success', 'Status tujuan pinjaman berhasil diubah');
    }

    // ==================== SYARAT & KETENTUAN LAYANAN ====================

    public function syaratKetentuanLayananIndex()
    {
        $data = SyaratKetentuanLayanan::first();
        if (!$data) {
            $data = SyaratKetentuanLayanan::create([
                'konten' => '<h3>KETENTUAN PENGGUNAAN LAYANAN MAJAKARAPINJAM</h3><p>Tulis syarat ketentuan di sini...</p>'
            ]);
        }
        return view('admin.master-data.syarat-ketentuan-layanan.index', compact('data'));
    }

    public function syaratKetentuanLayananUpdate(Request $request)
    {
        $this->checkCrudPermission();
        $request->validate([
            'konten' => 'required|string',
        ]);

        $data = SyaratKetentuanLayanan::first();
        if (!$data) {
            SyaratKetentuanLayanan::create(['konten' => $request->konten]);
        } else {
            $data->update(['konten' => $request->konten]);
        }

        return redirect()->back()->with('success', 'Syarat & Ketentuan Layanan berhasil diperbarui.');
    }

    // ==================== SYARAT & KETENTUAN LAYANAN GADAI ====================

    public function syaratKetentuanLayananGadaiIndex()
    {
        $data = \App\Models\SyaratKetentuanLayananGadai::first();
        if (!$data) {
            $data = \App\Models\SyaratKetentuanLayananGadai::create([
                'konten' => '<h3>KETENTUAN PENGGUNAAN LAYANAN MAJAKARAGADAI</h3><p>Tulis syarat ketentuan gadai di sini...</p>'
            ]);
        }
        return view('admin.master-data.syarat-ketentuan-layanan-gadai.index', compact('data'));
    }

    public function syaratKetentuanLayananGadaiUpdate(Request $request)
    {
        $this->checkCrudPermission();
        $request->validate([
            'konten' => 'required|string',
        ]);

        $data = \App\Models\SyaratKetentuanLayananGadai::first();
        if (!$data) {
            \App\Models\SyaratKetentuanLayananGadai::create(['konten' => $request->konten]);
        } else {
            $data->update(['konten' => $request->konten]);
        }

        return redirect()->back()->with('success', 'Syarat & Ketentuan Layanan Gadai berhasil diperbarui.');
    }

    // ==================== MASTER DATA BANK REGIS ====================

    public function bankRegisIndex()
    {
        $data = MasterDataBankRegis::latest()->paginate(15);
        return view('admin.master-data.bank-regis.index', compact('data'));
    }

    public function bankRegisCreate()
    {
        $this->checkCrudPermission();
        return view('admin.master-data.bank-regis.create');
    }

    public function bankRegisStore(Request $request)
    {
        $this->checkCrudPermission();
        $request->validate([
            'nama_bank' => 'required|string|max:100|unique:master_data_bank_regis,nama_bank',
            'kode_bank' => 'nullable|string|max:20',
            'status'    => 'boolean',
        ], [
            'nama_bank.required' => 'Nama bank wajib diisi.',
            'nama_bank.unique'   => 'Nama bank sudah terdaftar.',
            'nama_bank.max'      => 'Nama bank maksimal 100 karakter.',
            'kode_bank.max'      => 'Kode bank maksimal 20 karakter.',
        ]);

        MasterDataBankRegis::create([
            'nama_bank' => $request->nama_bank,
            'kode_bank' => $request->kode_bank,
            'status'    => $request->boolean('status', true),
        ]);

        return redirect()->route('admin.master-data.bank-regis.index')
            ->with('success', 'Data bank berhasil ditambahkan.');
    }

    public function bankRegisEdit($id)
    {
        $this->checkCrudPermission();
        $data = MasterDataBankRegis::findOrFail($id);
        return view('admin.master-data.bank-regis.edit', compact('data'));
    }

    public function bankRegisUpdate(Request $request, $id)
    {
        $this->checkCrudPermission();
        $data = MasterDataBankRegis::findOrFail($id);
        $request->validate([
            'nama_bank' => 'required|string|max:100|unique:master_data_bank_regis,nama_bank,' . $id,
            'kode_bank' => 'nullable|string|max:20',
            'status'    => 'boolean',
        ], [
            'nama_bank.required' => 'Nama bank wajib diisi.',
            'nama_bank.unique'   => 'Nama bank sudah terdaftar.',
            'nama_bank.max'      => 'Nama bank maksimal 100 karakter.',
            'kode_bank.max'      => 'Kode bank maksimal 20 karakter.',
        ]);

        $data->update([
            'nama_bank' => $request->nama_bank,
            'kode_bank' => $request->kode_bank,
            'status'    => $request->boolean('status', true),
        ]);

        return redirect()->route('admin.master-data.bank-regis.index')
            ->with('success', 'Data bank berhasil diperbarui.');
    }

    public function bankRegisDestroy($id)
    {
        $this->checkCrudPermission();
        $data = MasterDataBankRegis::findOrFail($id);
        $data->delete();
        return redirect()->route('admin.master-data.bank-regis.index')
            ->with('success', 'Data bank berhasil dihapus.');
    }

    public function bankRegisToggleStatus($id)
    {
        $this->checkCrudPermission();
        $data = MasterDataBankRegis::findOrFail($id);
        $data->status = !$data->status;
        $data->save();
        return redirect()->back()
            ->with('success', 'Status bank berhasil diubah.');
    }
}
