<?php

namespace App\Http\Controllers;

use App\Models\GadaiMasterKategori;
use App\Models\GadaiMasterItem;
use App\Models\GadaiActive;
use App\Models\JnsLokasiPerusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\BankAccessService;

class NasabahGadaiBaruController extends Controller
{
    public function index()
    {
        $kategoriElektronik = GadaiMasterKategori::with('items')->where('kode_kategori', 'electronic')->first();
        $kategoriKendaraan = GadaiMasterKategori::with('items')->where('kode_kategori', 'vehicle')->first();
        $kategoriEmas = GadaiMasterKategori::with('items')->where('kode_kategori', 'gold')->first();

        $pengajuanLunas = collect();
        $pengajuanPerpanjang = collect();
        $gadaiSelesai = collect();

        $nasabah = Auth::user()->nasabah;
        
        // BANK ACCESS GUARD dihapus dari index agar nasabah selalu bisa melihat daftar gadainya

        if ($nasabah) {
            $nasabahId = $nasabah->id;
            
            $gadaiAktif = GadaiActive::with(['kategori', 'item', 'lokasi'])
                ->where('nasabah_id', $nasabahId)
                ->whereIn('status', ['active', 'grace_period'])
                ->orderBy('created_at', 'desc')
                ->get();

            $pengajuanLunas = \App\Models\GadaiPengajuan::with(['gadaiActive.item'])
                ->where('nasabah_id', $nasabahId)
                ->where('jenis_pengajuan', 'lunas')
                ->latest()
                ->take(5)
                ->get();

            $pengajuanPerpanjang = \App\Models\GadaiPengajuan::with(['gadaiActive.item'])
                ->where('nasabah_id', $nasabahId)
                ->whereIn('jenis_pengajuan', ['perpanjang', 'perpanjangan'])
                ->latest()
                ->take(5)
                ->get();

            $gadaiSelesai = GadaiActive::with(['kategori', 'item'])
                ->where('nasabah_id', $nasabahId)
                ->whereNotIn('status', ['active', 'grace_period'])
                ->orderBy('updated_at', 'desc')
                ->take(5)
                ->get();
        }

        return view('nasabah.gadai_baru.index', compact(
            'kategoriElektronik', 
            'kategoriKendaraan', 
            'kategoriEmas', 
            'gadaiAktif',
            'pengajuanLunas',
            'pengajuanPerpanjang',
            'gadaiSelesai'
        ));
    }

    public function show($kategori_kode, $item_id)
    {
        $kategori = GadaiMasterKategori::where('kode_kategori', $kategori_kode)->firstOrFail();
        $item = GadaiMasterItem::where('id', $item_id)->where('kategori_id', $kategori->id)->firstOrFail();
        $lokasi = JnsLokasiPerusahaan::all();
        $nasabah = Auth::user()->nasabah;

        // ── BANK ACCESS GUARD ──────────────────────────────────────
        if ($nasabah) {
            $access = app(BankAccessService::class)->checkPremiumAccess($nasabah->id);
            if (!$access['allowed']) {
                return redirect()->route('nasabah.dashboard')
                    ->with('error', $access['reason']);
            }
        }
        // ──────────────────────────────────────────────────────────

        return view('nasabah.gadai_baru.show', compact('kategori', 'item', 'lokasi', 'nasabah'));
    }

    public function showActiveDetail($id)
    {
        $nasabah = Auth::user()->nasabah;
        
        // BANK ACCESS GUARD dihapus dari showActiveDetail agar nasabah selalu bisa melihat detail gadai aktifnya

        $gadai = GadaiActive::with(['kategori', 'item', 'lokasi', 'files', 'paymentLogs', 'history'])
            ->where('nasabah_id', $nasabah->id)
            ->findOrFail($id);

        return view('nasabah.gadai_baru.aktif_detail', compact('gadai'));
    }

    public function riwayat()
    {
        $nasabah = Auth::user()->nasabah;
        
        // BANK ACCESS GUARD dihapus dari riwayat agar nasabah selalu bisa melihat riwayat gadainya
        
        $gadaiAktif = GadaiActive::with(['kategori', 'item', 'lokasi'])
            ->where('nasabah_id', $nasabah->id)
            ->whereIn('status', ['active', 'grace_period'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        $gadaiSelesai = GadaiActive::with(['kategori', 'item', 'lokasi'])
            ->where('nasabah_id', $nasabah->id)
            ->whereNotIn('status', ['active', 'grace_period'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('nasabah.gadai_baru.riwayat', compact('gadaiAktif', 'gadaiSelesai'));
    }

    public function createPengajuan($id, $jenis)
    {
        $gadai = GadaiActive::with(['kategori', 'item', 'lokasi'])->findOrFail($id);
        
        // Safety check: ensure gadai belongs to current nasabah
        if ($gadai->nasabah_id !== Auth::user()->nasabah->id) {
            abort(403);
        }

        // ── BANK ACCESS GUARD ──────────────────────────────────────
        $access = app(BankAccessService::class)->checkPremiumAccess($gadai->nasabah_id);
        if (!$access['allowed']) {
            return redirect()->route('nasabah.dashboard')
                ->with('error', $access['reason']);
        }
        // ──────────────────────────────────────────────────────────

        // 🛡️ Guard Perpanjangan rules
        if (in_array($jenis, ['perpanjang', 'perpanjangan'])) {
            if (!in_array($gadai->status, ['active', 'grace_period'])) {
                return redirect()->route('nasabah.gadai_baru.index')->with('warning', 'Perpanjangan hanya dapat dilakukan untuk gadai yang aktif atau dalam masa tenggang.');
            }
            if ($gadai->jumlah_perpanjangan >= 3) {
                return redirect()->route('nasabah.gadai_baru.index')->with('warning', 'Maksimal perpanjangan adalah 3 kali.');
            }
        }

        // Calculate totals (re-using logic from dashboard)
        $totalTagihan = $gadai->nominal_deal + $gadai->biaya_jasa + $gadai->biaya_inap + ($gadai->extra_pinjaman_nominal ?? 0); // Denda hanya untuk perpanjang
        $totalPerpanjang = $gadai->biaya_jasa + $gadai->denda_aktif + $gadai->biaya_inap;
        
        $nominal = ($jenis == 'lunas') ? $totalTagihan : $totalPerpanjang;
        $lokasi = JnsLokasiPerusahaan::where('status_aktif', true)->get();
        $banks = \App\Models\JnsBank::where('status', 'aktif')->get();

        return view('nasabah.gadai_baru.pengajuan', compact('gadai', 'jenis', 'nominal', 'lokasi', 'banks'));
    }

    public function storePengajuan(Request $request, $id, $jenis)
    {
        $request->validate([
            'pin' => 'required|numeric|digits:6',
            'metode' => 'required|in:cash,transfer',
            'tgl_janji_temu' => 'required_if:metode,cash|nullable|date|after:now',
            'bukti_transfer.*' => 'required_if:metode,transfer|nullable|image|max:2048',
            'keterangan' => 'nullable|string'
        ]);

        $gadai = GadaiActive::findOrFail($id);
        
        // Safety check
        if ($gadai->nasabah_id !== Auth::user()->nasabah->id) {
            abort(403);
        }

        // ── BANK ACCESS GUARD ──────────────────────────────────────
        $access = app(BankAccessService::class)->checkPremiumAccess($gadai->nasabah_id);
        if (!$access['allowed']) {
            return redirect()->route('nasabah.dashboard')
                ->with('error', $access['reason']);
        }
        // ──────────────────────────────────────────────────────────

        // Verify PIN
        $user = Auth::user();
        if (!$user->pin || (int)$user->pin !== (int)$request->pin) {
            return redirect()->back()
                ->with('error', 'PIN yang Anda masukkan salah!')
                ->withInput($request->except('pin'));
        }

        // 🛡️ Guard Duplikasi: check if there's already a pending request for this gadai
        $pending = \App\Models\GadaiPengajuan::where('gadai_active_id', $id)
            ->where('status', 'pending')
            ->first();
            
        if ($pending) {
            return redirect()->route('nasabah.gadai_baru.index')->with('warning', 'Anda sudah memiliki pengajuan yang sedang menunggu verifikasi untuk gadai ini.');
        }

        // 🛡️ Guard Perpanjangan rules
        if (in_array($jenis, ['perpanjang', 'perpanjangan'])) {
            if (!in_array($gadai->status, ['active', 'grace_period'])) {
                return redirect()->route('nasabah.gadai_baru.index')->with('warning', 'Perpanjangan hanya dapat dilakukan untuk gadai yang aktif atau dalam masa tenggang.');
            }
            if ($gadai->jumlah_perpanjangan >= 3) {
                return redirect()->route('nasabah.gadai_baru.index')->with('warning', 'Maksimal perpanjangan adalah 3 kali.');
            }
        }

        // Calculate nominal again to be safe
        $totalTagihan = $gadai->nominal_deal + $gadai->biaya_jasa + $gadai->biaya_inap + ($gadai->extra_pinjaman_nominal ?? 0); // Denda hanya untuk perpanjang
        $totalPerpanjang = $gadai->biaya_jasa + $gadai->denda_aktif + $gadai->biaya_inap;
        $nominal = ($jenis == 'lunas') ? $totalTagihan : $totalPerpanjang;

        $pengajuan = new \App\Models\GadaiPengajuan();
        $pengajuan->nasabah_id = Auth::user()->nasabah->id;
        $pengajuan->gadai_active_id = $id;
        $pengajuan->jenis_pengajuan = $jenis;
        $pengajuan->metode = $request->metode;
        $pengajuan->nominal = $nominal;
        $pengajuan->keterangan = $request->keterangan;
        
        if ($request->metode == 'cash') {
            $pengajuan->tgl_janji_temu = $request->tgl_janji_temu;
        }

        $pengajuan->save();

        app(\App\Services\ActivityLogService::class)->logSubmitPengajuanGadai(
            $pengajuan->id,
            $nominal
        );

        // Handle Multiple Files
        if ($request->metode == 'transfer' && $request->hasFile('bukti_transfer')) {
            foreach ($request->file('bukti_transfer') as $index => $file) {
                $path = $file->store('bukti_transfer_gadai', 'public');
                
                // Save to tbl_gadai_files for multi-file support
                \App\Models\GadaiFile::create([
                    'gadai_active_id' => $gadai->id,
                    'pengajuan_id' => $pengajuan->id,
                    'path_file' => $path,
                    'tipe_foto' => 'lainnya' // We use 'lainnya' for payment proof
                ]);

                // Also save the FIRST one to pengajuan.bukti_transfer for backward compatibility/simplicity in list view
                if ($index === 0) {
                    $pengajuan->update(['bukti_transfer' => $path]);
                }
            }
        }

        return redirect()->route('nasabah.gadai_baru.status-pengajuan')->with('success', 'Pengajuan berhasil dikirim. Mohon tunggu verifikasi admin.');
    }

    public function statusPengajuan()
    {
        $nasabah = Auth::user()->nasabah;

        // ── BANK ACCESS GUARD ──────────────────────────────────────
        if ($nasabah) {
            $access = app(BankAccessService::class)->checkPremiumAccess($nasabah->id);
            if (!$access['allowed']) {
                return redirect()->route('nasabah.dashboard')
                    ->with('error', $access['reason']);
            }
        }
        // ──────────────────────────────────────────────────────────

        $pengajuan = \App\Models\GadaiPengajuan::with(['gadaiActive.item', 'gadaiActive.kategori', 'files'])
            ->where('nasabah_id', $nasabah->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('nasabah.gadai_baru.status_pengajuan', compact('pengajuan'));
    }
}
