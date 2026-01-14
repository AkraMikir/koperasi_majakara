<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use App\Models\PengajuanTabungan;
use App\Models\PengajuanPenarikanTabungan;
use App\Models\JanjiTemuTabungan;
use App\Models\BuktiFotoTabungan;
use App\Models\JnsLokasiPerusahaan;
use App\Models\TransTabungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TabunganController extends Controller
{
    /**
     * Show the tabungan dashboard.
     */
    public function index()
    {
        // Dummy data for frontend preview
        $dummyUser = (object) [
            'id' => 1,
            'nama' => 'Ahmad Rizki',
            'email' => 'ahmad.rizki@example.com',
            'nomor_hp' => '081234567890',
            'foto' => null,
            'role' => 'nasabah',
        ];

        // Dummy data for tabungan info
        $tabunganInfo = (object) [
            'saldo' => 5000000,
            'bunga' => 3.5,
            'status' => 'Aktif',
        ];

        // Get transaksi tabungan from database
        $idAnggota = 1; // TODO: Get from auth
        $transaksiTabungan = TransTabungan::where('id_anggota', $idAnggota)
            ->latest('tgl_transaksi')
            ->take(10)
            ->get();

        // Get riwayat janji temu from database
        $riwayatJanjiTemu = JanjiTemuTabungan::whereHas('pengajuan', function($q) use ($idAnggota) {
                $q->where('id_anggota', $idAnggota);
            })
            ->with('lokasi')
            ->latest('tanggal_janji_temu')
            ->take(10)
            ->get();

        return view('nasabah.tabungan.index', [
            'user' => $dummyUser,
            'tabunganInfo' => $tabunganInfo,
            'transaksiTabungan' => $transaksiTabungan,
            'riwayatJanjiTemu' => $riwayatJanjiTemu,
        ]);
    }

    /**
     * Show the nabung sekarang page.
     */
    public function nabungSekarang()
    {
        // Dummy data for frontend preview
        $dummyUser = (object) [
            'id' => 1,
            'nama' => 'Ahmad Rizki',
            'email' => 'ahmad.rizki@example.com',
            'nomor_hp' => '081234567890',
            'foto' => null,
            'role' => 'nasabah',
        ];

        // Get riwayat setoran from database
        $idAnggota = 1; // TODO: Get from auth
        $riwayatTabungan = TransTabungan::where('id_anggota', $idAnggota)
            ->where('jenis', 'setoran')
            ->latest('tgl_transaksi')
            ->take(10)
            ->get();

        return view('nasabah.tabungan.nabung-sekarang', [
            'user' => $dummyUser,
            'riwayatTabungan' => $riwayatTabungan,
        ]);
    }

    /**
     * Show the penarikan tabungan page.
     */
    public function penarikanTabungan()
    {
        // Dummy data for frontend preview
        $dummyUser = (object) [
            'id' => 1,
            'nama' => 'Ahmad Rizki',
            'email' => 'ahmad.rizki@example.com',
            'nomor_hp' => '081234567890',
            'foto' => null,
            'role' => 'nasabah',
        ];

        // Dummy data for tabungan info
        $tabunganInfo = (object) [
            'saldo' => 5000000,
            'bunga' => 3.5,
            'status' => 'Aktif',
        ];

        // Get riwayat penarikan from database
        $idAnggota = 1; // TODO: Get from auth
        $riwayatPenarikan = TransTabungan::where('id_anggota', $idAnggota)
            ->where('jenis', 'penarikan')
            ->latest('tgl_transaksi')
            ->take(10)
            ->get();

        return view('nasabah.tabungan.penarikan-tabungan', [
            'user' => $dummyUser,
            'tabunganInfo' => $tabunganInfo,
            'riwayatPenarikan' => $riwayatPenarikan,
        ]);
    }

    /**
     * Submit pengajuan setoran tabungan.
     */
    public function submitSetoran(Request $request)
    {
        $request->validate([
            'metode' => 'required|in:tunai,transfer',
            'nominal' => 'required|numeric|min:10000',
            'keterangan' => 'nullable|string|max:500',
            'bukti_foto.*' => 'required_if:metode,transfer|image|max:5120',
            'nominal_foto.*' => 'required_if:metode,transfer|string',
            'keterangan_foto.*' => 'nullable|string|max:255',
        ]);

        // Get nasabah ID (dummy untuk sekarang)
        $idAnggota = 1; // TODO: Get from auth

        if ($request->metode === 'transfer') {
            // Validate bukti foto exists
            if (!$request->hasFile('bukti_foto') || count($request->file('bukti_foto')) == 0) {
                return redirect()->back()
                    ->with('error', 'Minimal upload 1 bukti transfer')
                    ->withInput();
            }

            // Create pengajuan tabungan
            $pengajuan = PengajuanTabungan::create([
                'id_anggota' => $idAnggota,
                'foto_bukti_tf' => 'transfer', // Indikator bahwa ini transfer
                'keterangan' => $request->keterangan,
                'status' => '1', // Pending
            ]);

            // Handle multiple bukti foto
            if ($request->hasFile('bukti_foto')) {
                foreach ($request->file('bukti_foto') as $index => $file) {
                    $path = $file->store('bukti_tabungan', 'public');
                    
                    // Parse nominal from formatted currency string
                    $nominalStr = $request->nominal_foto[$index] ?? '0';
                    $nominal = (float) str_replace(['.', ','], '', $nominalStr);
                    
                    BuktiFotoTabungan::create([
                        'id_pengajuan' => $pengajuan->id,
                        'file_photo' => $path,
                        'jenis' => 'tabungan',
                        'nominal' => $nominal > 0 ? $nominal : $request->nominal,
                        'keterangan' => $request->keterangan_foto[$index] ?? 'Bukti transfer',
                    ]);
                }
            }

            return redirect()->route('nasabah.tabungan.status-pengajuan-setor')
                ->with('success', 'Pengajuan setoran berhasil dikirim!');
        } else {
            // For tunai, redirect to janji temu
            return redirect()->route('nasabah.tabungan.janji-temu', [
                'nominal' => $request->nominal,
                'keterangan' => $request->keterangan,
            ]);
        }
    }

    /**
     * Show janji temu page for setoran tunai.
     */
    public function janjiTemu(Request $request)
    {
        $lokasi = JnsLokasiPerusahaan::where('status_aktif', true)->get();
        
        return view('nasabah.tabungan.janji-temu', [
            'lokasi' => $lokasi,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
        ]);
    }

    /**
     * Submit janji temu.
     */
    public function submitJanjiTemu(Request $request)
    {
        $request->validate([
            'nominal' => 'required|numeric|min:10000',
            'lokasi_temu' => 'required|exists:jns_lokasi_perusahaan,id',
            'tanggal_janji_temu' => 'required|date|after:today',
            'waktu_janji_temu' => 'required|date_format:H:i',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $idAnggota = 1; // TODO: Get from auth

        // Create pengajuan tabungan
        $pengajuan = PengajuanTabungan::create([
            'id_anggota' => $idAnggota,
            'foto_bukti_tf' => 'tunai',
            'keterangan' => $request->keterangan,
            'status' => '1', // Pending
        ]);

        // Create janji temu
        $tanggalWaktu = \Carbon\Carbon::parse($request->tanggal_janji_temu . ' ' . $request->waktu_janji_temu);
        
        JanjiTemuTabungan::create([
            'id_pengajuan' => $pengajuan->id,
            'lokasi_temu' => $request->lokasi_temu,
            'nominal' => $request->nominal,
            'tanggal_janji_temu' => $tanggalWaktu,
            'waktu_janji_temu' => $tanggalWaktu,
        ]);

        return redirect()->route('nasabah.tabungan.status-pengajuan-setor')
            ->with('success', 'Janji temu berhasil dibuat!');
    }

    /**
     * Submit pengajuan penarikan tabungan.
     */
    public function submitPenarikan(Request $request)
    {
        $request->validate([
            'metode' => 'required|in:tunai,transfer',
            'nominal' => 'required|numeric|min:10000',
            'keterangan' => 'nullable|string|max:500',
            'no_rekening' => 'required_if:metode,transfer|string|max:50',
        ]);

        $idAnggota = 1; // TODO: Get from auth

        // Check saldo
        $saldo = $this->getSaldoNasabah($idAnggota);
        if ($saldo < $request->nominal) {
            return redirect()->back()
                ->with('error', 'Saldo tidak mencukupi!')
                ->withInput();
        }

        // Create pengajuan penarikan
        PengajuanPenarikanTabungan::create([
            'id_anggota' => $idAnggota,
            'tgl_pengajuan' => now(),
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan . ($request->metode === 'transfer' ? ' | Rekening: ' . $request->no_rekening : ''),
            'status' => '1', // Pending
        ]);

        return redirect()->route('nasabah.tabungan.status-pengajuan-tarik')
            ->with('success', 'Pengajuan penarikan berhasil dikirim!');
    }

    /**
     * Show status pengajuan setoran.
     */
    public function statusPengajuanSetor()
    {
        $idAnggota = 1; // TODO: Get from auth
        
        $pengajuan = PengajuanTabungan::where('id_anggota', $idAnggota)
            ->with(['buktiFoto', 'janjiTemu.lokasi'])
            ->latest()
            ->paginate(10);

        return view('nasabah.tabungan.status-pengajuan-setor', [
            'pengajuan' => $pengajuan,
        ]);
    }

    /**
     * Show status pengajuan penarikan.
     */
    public function statusPengajuanTarik()
    {
        $idAnggota = 1; // TODO: Get from auth
        
        $pengajuan = PengajuanPenarikanTabungan::where('id_anggota', $idAnggota)
            ->latest()
            ->paginate(10);

        return view('nasabah.tabungan.status-pengajuan-tarik', [
            'pengajuan' => $pengajuan,
        ]);
    }

    /**
     * Show detail pengajuan setor.
     */
    public function detailPengajuanSetor($id)
    {
        $idAnggota = 1; // TODO: Get from auth
        
        $pengajuan = PengajuanTabungan::where('id_anggota', $idAnggota)
            ->with(['buktiFoto', 'janjiTemu.lokasi'])
            ->findOrFail($id);

        return view('nasabah.tabungan.detail-pengajuan-setor', [
            'pengajuan' => $pengajuan,
        ]);
    }

    /**
     * Show detail pengajuan tarik.
     */
    public function detailPengajuanTarik($id)
    {
        $idAnggota = 1; // TODO: Get from auth
        
        $pengajuan = PengajuanPenarikanTabungan::where('id_anggota', $idAnggota)
            ->findOrFail($id);

        return view('nasabah.tabungan.detail-pengajuan-tarik', [
            'pengajuan' => $pengajuan,
        ]);
    }

    /**
     * Show detail transaksi tabungan.
     */
    public function detailTransaksi($id)
    {
        $idAnggota = 1; // TODO: Get from auth
        
        $transaksi = TransTabungan::where('id_anggota', $idAnggota)
            ->with(['pengajuanSetor.buktiFoto', 'pengajuanTarik'])
            ->findOrFail($id);

        return view('nasabah.tabungan.detail-transaksi', [
            'transaksi' => $transaksi,
        ]);
    }

    /**
     * Show detail janji temu.
     */
    public function detailJanjiTemu($id)
    {
        $idAnggota = 1; // TODO: Get from auth
        
        $janjiTemu = JanjiTemuTabungan::whereHas('pengajuan', function($q) use ($idAnggota) {
                $q->where('id_anggota', $idAnggota);
            })
            ->with(['pengajuan', 'lokasi'])
            ->findOrFail($id);

        return view('nasabah.tabungan.detail-janji-temu', [
            'janjiTemu' => $janjiTemu,
        ]);
    }

    /**
     * Get saldo nasabah.
     */
    private function getSaldoNasabah($idAnggota)
    {
        $totalSetoran = TransTabungan::where('id_anggota', $idAnggota)
            ->where('jenis', 'setoran')
            ->sum('nominal') ?? 0;

        $totalPenarikan = TransTabungan::where('id_anggota', $idAnggota)
            ->where('jenis', 'penarikan')
            ->sum('nominal') ?? 0;

        return max(0, $totalSetoran - $totalPenarikan);
    }
}

