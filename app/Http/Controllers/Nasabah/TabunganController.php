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
        $idAnggota = $this->getIdAnggota();
        
        // Calculate saldo from database
        $saldo = $this->getSaldoNasabah($idAnggota);
        
        // Tabungan info from database
        $tabunganInfo = (object) [
            'saldo' => $saldo,
            'bunga' => 3.5,
            'status' => 'Aktif',
        ];
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
            'user' => auth()->user(),
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
        $idAnggota = $this->getIdAnggota();
        
        // Get riwayat setoran from database
        $riwayatTabungan = TransTabungan::where('id_anggota', $idAnggota)
            ->where('jenis', 'setoran')
            ->latest('tgl_transaksi')
            ->take(10)
            ->get();

        return view('nasabah.tabungan.nabung-sekarang', [
            'user' => auth()->user(),
            'riwayatTabungan' => $riwayatTabungan,
        ]);
    }

    /**
     * Show the penarikan tabungan page.
     */
    public function penarikanTabungan()
    {
        $idAnggota = $this->getIdAnggota();
        
        // Calculate saldo from database
        $saldo = $this->getSaldoNasabah($idAnggota);
        
        // Tabungan info from database
        $tabunganInfo = (object) [
            'saldo' => $saldo,
            'bunga' => 3.5,
            'status' => 'Aktif',
        ];

        // Get riwayat penarikan from database
        $riwayatPenarikan = TransTabungan::where('id_anggota', $idAnggota)
            ->where('jenis', 'penarikan')
            ->latest('tgl_transaksi')
            ->take(10)
            ->get();

        return view('nasabah.tabungan.penarikan-tabungan', [
            'user' => auth()->user(),
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

        // Get nasabah ID from auth
        $idAnggota = $this->getIdAnggota();

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

        $idAnggota = $this->getIdAnggota();

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

        $idAnggota = $this->getIdAnggota();

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
        $idAnggota = $this->getIdAnggota();
        
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
        $idAnggota = $this->getIdAnggota();
        
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
        $idAnggota = $this->getIdAnggota();
        
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
        $idAnggota = $this->getIdAnggota();
        
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
        $idAnggota = $this->getIdAnggota();
        
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
        $idAnggota = $this->getIdAnggota();
        
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
     * Get saldo nasabah (same method as Admin controller).
     */
    private function getSaldoNasabah($idAnggota)
    {
        // Hitung dari trans_tabungan yang sudah ada
        $totalSetoran = TransTabungan::where('id_anggota', $idAnggota)
            ->where('jenis', 'setoran')
            ->sum('nominal') ?? 0;

        $totalPenarikan = TransTabungan::where('id_anggota', $idAnggota)
            ->where('jenis', 'penarikan')
            ->sum('nominal') ?? 0;

        // Tambahkan setoran dari pengajuan yang sudah approved tapi belum ada transaksi
        $pengajuanApproved = PengajuanTabungan::where('id_anggota', $idAnggota)
            ->where('status', '2') // Approved
            ->whereDoesntHave('transTabungan')
            ->with('buktiFoto', 'janjiTemu')
            ->get();

        foreach ($pengajuanApproved as $pengajuan) {
            $nominal = 0;
            if ($pengajuan->buktiFoto && $pengajuan->buktiFoto->count() > 0) {
                $nominal = $pengajuan->buktiFoto->sum('nominal');
            } elseif ($pengajuan->janjiTemu) {
                $nominal = $pengajuan->janjiTemu->nominal ?? 0;
            }
            $totalSetoran += $nominal;
        }

        return max(0, $totalSetoran - $totalPenarikan);
    }

    /**
     * Get ID anggota from authenticated user.
     */
    private function getIdAnggota()
    {
        $user = auth()->user();
        
        if (!$user) {
            abort(401, 'Unauthorized');
        }

        $nasabah = $user->nasabah;
        
        if (!$nasabah) {
            abort(403, 'User tidak memiliki data nasabah');
        }

        return $nasabah->id;
    }
}

