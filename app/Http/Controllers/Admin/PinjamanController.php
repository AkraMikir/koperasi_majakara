<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanPinjaman;
use App\Models\PinjamanH;
use App\Models\TempoPinjamanB;
use App\Models\TempoPinjamanM;
use App\Models\Nasabah;
use App\Models\PengajuanPembayaranPinjaman;
use App\Models\JanjiTemuPembayaranPinjaman;
use App\Models\BuktiFoto;
use App\Models\JnsLokasiPerusahaan;
use App\Models\MasterBungaPinjaman;
use App\Models\MasterDendaPinjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Helpers\IdGenerator; // Add Helper

class PinjamanController extends Controller
{
    /**
     * Display dashboard pinjaman admin.
     */
    public function index()
    {
        // Statistik pinjaman
        $stats = [
            'total_pengajuan_pending' => PengajuanPinjaman::whereDoesntHave('pinjaman')->count(),
            'total_pinjaman_aktif' => PinjamanH::where('lunas', 'belum')->count(),
            'total_pinjaman_lunas' => PinjamanH::where('lunas', 'lunas')->count(),
            'total_pinjaman_hari_ini' => PinjamanH::whereDate('created_at', today())->count(),
            'total_nominal_pinjaman_aktif' => PinjamanH::where('lunas', 'belum')->sum('jumlah_pinjam') ?? 0,
            'total_angsuran_telat' => $this->getTotalAngsuranTelat(),
            'total_pembayaran_pending' => PengajuanPembayaranPinjaman::where('status', '1')->count(),
        ];

        // Pengajuan terbaru (pending)
        $pengajuan_terbaru = PengajuanPinjaman::whereDoesntHave('pinjaman')
            ->with('nasabah.user')
            ->latest()
            ->take(5)
            ->get();

        // Pinjaman aktif terbaru
        $pinjaman_aktif_terbaru = PinjamanH::where('lunas', 'belum')
            // ->whereIn('status', ['pencairan', 'telaksana']) // Removed status check
            ->with('nasabah.user')
            ->latest()
            ->take(5)
            ->get();

        // Angsuran jatuh tempo hari ini
        $angsuran_jatuh_tempo = $this->getAngsuranJatuhTempo();

        return view('admin.pinjaman.index', compact(
            'stats',
            'pengajuan_terbaru',
            'pinjaman_aktif_terbaru',
            'angsuran_jatuh_tempo'
        ));
    }

    /**
     * Display list of pengajuan pinjaman.
     */
    public function pengajuan(Request $request)
    {
        $query = PengajuanPinjaman::with('nasabah.user')
            ->latest();

        // Filter by status: kosong / Semua Status = tampilkan semua pengajuan
        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $query->whereDoesntHave('pinjaman');
            } elseif ($request->status === 'approved') {
                $query->whereHas('pinjaman');
            }
        }

        // Filter by jenis
        if ($request->has('jenis') && $request->jenis !== '') {
            $query->where('jenis', $request->jenis);
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->whereHas('nasabah.user', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $pengajuan = $query->paginate(15);

        return view('admin.pinjaman.pengajuan', compact('pengajuan'));
    }

    /**
     * Display detail pengajuan pinjaman.
     */
    public function detailPengajuan($id)
    {
        $pengajuan = PengajuanPinjaman::with(['nasabah.user', 'nasabah.dataKtp', 'nasabah.pekerjaan', 'pinjaman'])
            ->findOrFail($id);

        // Get bunga dari master data berdasarkan durasi
        $masterBunga = MasterBungaPinjaman::getBungaByDurasi($pengajuan->durasi);
        $masterDenda = MasterDendaPinjaman::getDendaAktif();

        return view('admin.pinjaman.detail-pengajuan', compact('pengajuan', 'masterBunga', 'masterDenda'));
    }

    /**
     * Approve pengajuan pinjaman (Status 1 -> 3).
     * BARU: Update status, buat pinjaman header, tapi BELUM generate jadwal angsuran.
     */
    public function approvePengajuan(Request $request, $id)
    {
        $request->validate([
            'keterangan_admin' => 'nullable|string|max:500',
        ]);

        $pengajuan = PengajuanPinjaman::findOrFail($id);

        // Cek status harus '1' (pending)
        if ($pengajuan->status !== '1') {
            return redirect()->back()
                ->with('error', 'Pengajuan ini tidak bisa disetujui karena statusnya bukan pending');
        }

        // Cek apakah sudah punya pinjaman
        if ($pengajuan->pinjaman) {
            return redirect()->back()
                ->with('error', 'Pengajuan ini sudah memiliki data pinjaman');
        }

        // Get bunga dari master data berdasarkan durasi
        $masterBunga = MasterBungaPinjaman::getBungaByDurasi($pengajuan->durasi);
        if (!$masterBunga) {
            return redirect()->back()
                ->with('error', 'Bunga untuk durasi ' . $pengajuan->durasi . ' bulan belum diatur di master data');
        }

        // Get denda dari master data
        $masterDenda = MasterDendaPinjaman::getDendaAktif();
        if (!$masterDenda) {
            return redirect()->back()
                ->with('error', 'Denda belum diatur di master data');
        }

        try {
            DB::beginTransaction();

            // Hitung bunga
            $nominal = $pengajuan->nominal;
            $bungaPersen = $masterBunga->bunga_persen;
            $bungaRp = ($nominal * $bungaPersen) / 100;
            $jumlahPinjam = $nominal;

            // Generate ID Pinjaman: P (Pinjaman) TF/TN (Transfer/Tunai) DPNJM (Detail Pinjaman Header)
            $kodeVia = $pengajuan->jenis_pencairan === 'transfer' ? 'TF' : 'TN';
            $idPinjaman = IdGenerator::generate('tbl_pinjaman_h', 'P', $kodeVia, 'DPNJM', now());

            // Create pinjaman header (BELUM ada jadwal angsuran)
            $pinjaman = PinjamanH::create([
                'id' => $idPinjaman,
                'id_anggota' => $pengajuan->id_anggota,
                'id_pengajuan' => $pengajuan->id,
                'jumlah_pinjam' => $jumlahPinjam,
                'lama_pinjam' => (int)$pengajuan->durasi,
                'ags_bulan' => ($jumlahPinjam + $bungaRp) / (int)$pengajuan->durasi,
                'jenis' => 'bulanan',
                'bunga' => $bungaPersen,
                'bunga_rp' => $bungaRp,
                'denda_persen' => $masterDenda->denda_persen,
                'tgl_pinjam' => now(), // Tanggal approval
                'lunas' => 'belum',
            ]);

            // Update status pengajuan menjadi '3' (Disetujui)
            $pengajuan->update([
                'status' => '3',
                'bunga_persen' => $masterBunga->bunga_persen,
                'keterangan_admin' => $request->keterangan_admin,
            ]);

            DB::commit();

            return redirect()->route('admin.pinjaman.detail-pengajuan', $id)
                ->with('success', 'Pengajuan berhasil disetujui dan data pinjaman dibuat. Silakan klik "Cairkan" untuk generate jadwal angsuran dan pencairan dana.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Cairkan pinjaman (Status 3 -> 4).
     * BARU: Hanya generate jadwal angsuran dan save bukti foto. Pinjaman header sudah dibuat di status 3.
     */
    public function cairkanPinjaman(Request $request, $id)
    {
        $request->validate([
            'tgl_cair' => 'required|date',
            'bukti_transfer' => 'nullable|image|max:5120',
        ]);

        $pengajuan = PengajuanPinjaman::with('pinjaman')->findOrFail($id);

        // Cek status harus '3' (disetujui)
        if ($pengajuan->status !== '3') {
            return redirect()->back()
                ->with('error', 'Pengajuan ini tidak bisa dicairkan karena statusnya bukan disetujui');
        }

        // Cek apakah sudah punya pinjaman
        if (!$pengajuan->pinjaman) {
            return redirect()->back()
                ->with('error', 'Data pinjaman belum dibuat. Silakan setujui pengajuan terlebih dahulu.');
        }

        $pinjaman = $pengajuan->pinjaman;

        // Cek apakah jadwal angsuran sudah dibuat
        $existingTempo = TempoPinjamanB::where('pinjaman_id', $pinjaman->id)->exists();
        if ($existingTempo) {
            return redirect()->back()
                ->with('error', 'Pinjaman ini sudah dicairkan sebelumnya (jadwal angsuran sudah ada)');
        }

        try {
            DB::beginTransaction();

            // Update tanggal pinjam di pinjaman header
            $pinjaman->update([
                'tgl_pinjam' => $request->tgl_cair,
            ]);

            // Generate jadwal angsuran
            $this->generateJadwalAngsuran($pinjaman);

            // Upload bukti transfer dan simpan ke tbl_bukti_foto dengan kode PNCR
            if ($request->hasFile('bukti_transfer')) {
                $file = $request->file('bukti_transfer');
                $path = $file->store('bukti-pencairan-pinjaman', 'public');
                
                // Generate ID for bukti foto: P (pinjaman) + TF (transfer) + PNCR (pencairan)
                // Format: 120220260001PTFPNCR (tanggal + seq + P + TF + PNCR)
                $idBuktiFoto = IdGenerator::generate('tbl_bukti_foto', 'P', 'TF', 'PNCR', $request->tgl_cair);
                
                BuktiFoto::create([
                    'id' => $idBuktiFoto,
                    'owner_id' => $pengajuan->id, // ID pengajuan
                    'owner_fitur' => 'P', // Pinjaman
                    'owner_trans' => 'PNCR', // Pencairan
                    'file_path' => $path,
                    'keterangan' => 'Bukti transfer pencairan pinjaman',
                ]);
            }

            // Update status pengajuan menjadi '4' (Terlaksana/Tercair)
            $pengajuan->update([
                'status' => '4',
                'tgl_cair' => $request->tgl_cair,
            ]);

            DB::commit();

            return redirect()->route('admin.pinjaman.detail-pinjaman', $pinjaman->id)
                ->with('success', 'Pinjaman berhasil dicairkan dan jadwal angsuran telah dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Reject pengajuan pinjaman (Status 1 -> 2).
     */
    public function rejectPengajuan(Request $request, $id)
    {
        $request->validate([
            'keterangan_admin' => 'required|string|max:500'
        ]);

        $pengajuan = PengajuanPinjaman::findOrFail($id);
        
        // Cek status harus '1' (pending)
        if ($pengajuan->status !== '1') {
            return redirect()->back()
                ->with('error', 'Pengajuan ini tidak bisa ditolak karena statusnya bukan pending');
        }

        // Update status menjadi '2' (Ditolak)
        $pengajuan->update([
            'status' => '2',
            'keterangan_admin' => $request->keterangan_admin
        ]);

        return redirect()->route('admin.pinjaman.pengajuan')
            ->with('success', 'Pengajuan pinjaman berhasil ditolak');
    }

    /**
     * Display list of active pinjaman.
     */
    public function pinjamanAktif(Request $request)
    {
        $query = PinjamanH::with('nasabah.user')
            ->where('lunas', 'belum')
            // ->whereIn('status', ['pencairan', 'telaksana']) // Removed status check
            ->latest();

        // Filter by jenis
        if ($request->has('jenis') && $request->jenis !== '') {
            $query->where('jenis', $request->jenis);
        }

        // Filter by status (removed because PinjamanH status column dropped)
        /* if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        } */

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->whereHas('nasabah.user', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $pinjaman = $query->paginate(15);

        return view('admin.pinjaman.pinjaman-aktif', compact('pinjaman'));
    }

    /**
     * Display list of pinjaman lunas (sudah lunas).
     */
    public function pinjamanLunas(Request $request)
    {
        $query = PinjamanH::with('nasabah.user')
            ->where('lunas', 'lunas')
            ->latest();

        if ($request->has('jenis') && $request->jenis !== '') {
            $query->where('jenis', $request->jenis);
        }

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->whereHas('nasabah.user', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $pinjaman = $query->paginate(15);

        return view('admin.pinjaman.pinjaman-lunas', compact('pinjaman'));
    }

    /**
     * Display detail pinjaman.
     */
    public function detailPinjaman($id)
    {
        $pinjaman = PinjamanH::with([
            'nasabah.user',
            'nasabah.dataKtp',
            'nasabah.pekerjaan',
            'pengajuan',
            'tempoBulanan',
            'tempoMingguan'
        ])->findOrFail($id);

        // Get angsuran berdasarkan jenis
        $angsuran = $pinjaman->jenis === 'bulanan' 
            ? $pinjaman->tempoBulanan()->orderBy('no_urut')->get()
            : $pinjaman->tempoMingguan()->orderBy('no_urut')->get();

        return view('admin.pinjaman.detail-pinjaman', compact('pinjaman', 'angsuran'));
    }

    /**
     * Display list of angsuran grouped by pinjaman (satu baris per pinjaman, dengan tabel kecil detail angsuran & status pembayaran).
     */
    public function angsuran(Request $request)
    {
        $jenis = $request->get('jenis', 'bulanan');

        $query = PinjamanH::with(['nasabah.user'])
            ->where('lunas', 'belum')
            ->when($jenis === 'bulanan', fn ($q) => $q->with(['tempoBulanan' => fn ($q) => $q->orderBy('no_urut')]))
            ->when($jenis === 'mingguan', fn ($q) => $q->with(['tempoMingguan' => fn ($q) => $q->orderBy('no_urut')]))
            ->latest();

        // Filter by status (pinjaman yang punya minimal satu angsuran dengan status ini)
        if ($request->filled('status')) {
            if ($jenis === 'bulanan') {
                $query->whereHas('tempoBulanan', fn ($q) => $q->where('status_bayar', $request->status));
            } else {
                $query->whereHas('tempoMingguan', fn ($q) => $q->where('status_bayar', $request->status));
            }
        }

        // Filter by date (jatuh tempo)
        if ($request->filled('tanggal_dari')) {
            if ($jenis === 'bulanan') {
                $query->whereHas('tempoBulanan', fn ($q) => $q->whereDate('tgl_jatuh_tempo', '>=', $request->tanggal_dari));
            } else {
                $query->whereHas('tempoMingguan', fn ($q) => $q->whereDate('tgl_jatuh_tempo', '>=', $request->tanggal_dari));
            }
        }
        if ($request->filled('tanggal_sampai')) {
            if ($jenis === 'bulanan') {
                $query->whereHas('tempoBulanan', fn ($q) => $q->whereDate('tgl_jatuh_tempo', '<=', $request->tanggal_sampai));
            } else {
                $query->whereHas('tempoMingguan', fn ($q) => $q->whereDate('tgl_jatuh_tempo', '<=', $request->tanggal_sampai));
            }
        }

        // Search by nasabah
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('nasabah.user', fn ($q) => $q->where('nama', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%"));
        }

        $pinjamanList = $query->paginate(10);

        return view('admin.pinjaman.angsuran', compact('pinjamanList', 'jenis'));
    }

    /**
     * Generate jadwal angsuran untuk pinjaman.
     * 
     * Sistem: Bunga tidak dipotong di awal, tapi dibagi ke setiap angsuran
     * - jumlah_pinjam = nominal (jumlah yang diterima nasabah)
     * - bunga_rp = total bunga yang harus dibayar
     * - Total kewajiban = jumlah_pinjam + bunga_rp
     * - Angsuran n-1 pertama: dibulatkan ke bawah ke ratusan terdekat
     * - Angsuran terakhir: sisa dari total kewajiban
     * 
     * Logika pembulatan ini sama dengan simulasiAngsuran di NasabahController.
     */
    private function generateJadwalAngsuran(PinjamanH $pinjaman)
    {
        $jumlahAngsuran = $pinjaman->lama_pinjam;
        $jumlahPinjam = $pinjaman->jumlah_pinjam; // Jumlah yang diterima nasabah
        $bungaRp = $pinjaman->bunga_rp; // Total bunga
        
        // Total kewajiban yang harus dibayar
        $totalKewajiban = $jumlahPinjam + $bungaRp;
        
        // Angsuran bulanan: n-1 pertama dibulatkan ke bawah ke ratusan, bulan terakhir = sisa
        $angsuranBulanan = (int) floor($totalKewajiban / $jumlahAngsuran / 100) * 100;
        $akumulasi = $angsuranBulanan * ($jumlahAngsuran - 1);
        $angsuranTerakhir = (int) round($totalKewajiban - $akumulasi, 0);

        $tanggalMulai = \Carbon\Carbon::parse($pinjaman->tgl_pinjam); // Correct carbon parsing
        
        // Generate Base ID for Tempo: P (Pinjaman) T (Transfer) TPNJM (Tempo Pinjaman)
        // Kita generate ID pertama, lalu increment manual untuk performa
        $baseId = IdGenerator::generate('tempo_pinjaman_b', 'P', 'T', 'TPNJM', $tanggalMulai);
        
        // Extract sequence dari baseId (misal 300120260001PTTPNJM) -> 0001
        // Format: DATE(8) + SEQ(4) + STR
        $datePrefix = substr($baseId, 0, 8);
        $seqStart = (int)substr($baseId, 8, 4);
        $suffix = substr($baseId, 12); // PTTPNJM

        for ($i = 1; $i <= $jumlahAngsuran; $i++) {
            $tanggalJatuhTempo = $tanggalMulai->copy()->addMonths($i);
            
            // Generate Sequence Manual
            $currentSeq = $seqStart + ($i - 1);
            $seqStr = str_pad($currentSeq, 4, '0', STR_PAD_LEFT);
            $currentId = $datePrefix . $seqStr . $suffix;

            // Tentukan jumlah tagihan: 
            // - Untuk angsuran 1 sampai n-1: angsuranBulanan (sudah dibulatkan)
            // - Untuk angsuran terakhir (ke-n): sisa total kewajiban
            $jumlahTagihan = ($i < $jumlahAngsuran) ? $angsuranBulanan : $angsuranTerakhir;

            $data = [
                'id' => $currentId,
                'pinjaman_id' => $pinjaman->id,
                'no_urut' => $i,
                'tgl_jatuh_tempo' => $tanggalJatuhTempo,
                'jumlah_tagihan' => $jumlahTagihan,
                'jumlah_terbayar' => 0,
                'denda' => 0,
                'status_bayar' => 'belum',
            ];

            TempoPinjamanB::create($data);
        }
    }

    /**
     * Get total angsuran telat.
     */
    private function getTotalAngsuranTelat()
    {
        $bulanan = TempoPinjamanB::where('status_bayar', 'telat')
            ->whereDate('tgl_jatuh_tempo', '<', now())
            ->count();

        // Fitur mingguan dinonaktifkan sementara
        /* $mingguan = TempoPinjamanM::where('status_bayar', 'telat')
            ->whereDate('tgl_jatuh_tempo', '<', now())
            ->count(); */
        $mingguan = 0;

        return $bulanan + $mingguan;
    }

    /**
     * Get angsuran jatuh tempo hari ini.
     */
    private function getAngsuranJatuhTempo()
    {
        $bulanan = TempoPinjamanB::whereDate('tgl_jatuh_tempo', today())
            ->where('status_bayar', 'belum')
            ->with(['pinjaman.nasabah.user']) // Removed nasabah.user direct relation from tempo if not exists
            ->get()
            ->map(function($item) {
                $item->jenis = 'bulanan';
                // Helper to get nasabah from pinjaman relation
                $item->nasabah = $item->pinjaman->nasabah ?? null;
                return $item;
            });

        /* $mingguan = TempoPinjamanM::whereDate('tgl_jatuh_tempo', today())
            // ... commented out ...
            ->get(); */
        $mingguan = collect([]);

        return $bulanan->merge($mingguan)->take(5);
    }

    /**
     * Display detail angsuran.
     */
    public function detailAngsuran(Request $request, $id)
    {
        $jenis = $request->get('jenis', 'bulanan');
        
        if ($jenis === 'bulanan') {
            $angsuran = TempoPinjamanB::with(['pinjaman.nasabah.user', 'pinjaman'])
                ->findOrFail($id);
        } else {
            $angsuran = TempoPinjamanM::with(['pinjaman.nasabah.user', 'pinjaman'])
                ->findOrFail($id);
        }

        return view('admin.pinjaman.detail-angsuran', compact('angsuran', 'jenis'));
    }

    /**
     * Hitung denda untuk angsuran yang telat.
     * 
     * Aturan REVISI TERBARU:
     * - Denda 0.3% per hari dari POKOK ANGSURAN per bulan (bukan total tagihan)
     * - Denda mulai dihitung 1 hari SETELAH tanggal jatuh tempo (H+1)
     * - Denda BERHENTI jika sudah ada pembayaran (walaupun Rp 1)
     * 
     * Perhitungan:
     * - Pokok per bulan = jumlah_pinjam / lama_pinjam
     * - Denda = pokok per bulan × (denda_persen / 100) × hari_telat
     * 
     * Contoh:
     * Pinjaman 3 juta, 3 bulan
     * Pokok per bulan = 1 juta
     * Denda = 1.000.000 × 0.3% × hari_telat
     * Jika telat 1 hari = Rp 3.000
     * Jika telat 2 hari = Rp 6.000
     */
    private function hitungDenda($angsuran, $pinjaman)
    {
        // Jika sudah lunas, return denda yang tersimpan
        if ($angsuran->status_bayar === 'lunas') {
            return $angsuran->denda ?? 0;
        }

        // Jika sudah ada pembayaran (walaupun sebagian), denda BERHENTI
        // Return denda yang sudah tersimpan
        if ($angsuran->jumlah_terbayar > 0) {
            return $angsuran->denda ?? 0;
        }

        // Hitung hari telat mulai dari H+1 setelah jatuh tempo
        $tanggalMulaiDenda = $angsuran->tgl_jatuh_tempo->copy()->addDay();
        
        // Jika belum mencapai H+1, tidak ada denda
        if (now() < $tanggalMulaiDenda) {
            return 0;
        }
        
        // Hitung jumlah hari telat (dari H+1 sampai sekarang)
        $hariTelat = now()->diffInDays($tanggalMulaiDenda, false);
        
        // Jika belum ada hari telat, return 0
        if ($hariTelat < 0) {
            return 0;
        }

        // Get denda persen dari pinjaman
        $dendaPersen = $pinjaman->denda_persen ?? 0.30; // Default 0.3% per hari
        
        // **PENTING:** Hitung POKOK per bulan (bukan total tagihan!)
        // Pokok per bulan = jumlah_pinjam / lama_pinjam
        $pokokPerBulan = $pinjaman->jumlah_pinjam / $pinjaman->lama_pinjam;
        
        // Denda = POKOK per bulan × (denda_persen / 100) × hari_telat
        $denda = $pokokPerBulan * ($dendaPersen / 100) * $hariTelat;

        return round($denda, 2);
    }

    /**
     * Update pembayaran angsuran.
     */
    public function updatePembayaranAngsuran(Request $request, $id)
    {
        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:0',
            'jenis' => 'required|in:bulanan,mingguan',
        ]);

        if ($request->jenis === 'bulanan') {
            $angsuran = TempoPinjamanB::findOrFail($id);
        } else {
            $angsuran = TempoPinjamanM::findOrFail($id);
        }

        $pinjaman = $angsuran->pinjaman;
        if (!$pinjaman) {
            return redirect()->back()
                ->with('error', 'Pinjaman tidak ditemukan');
        }

        // Hitung denda sebelum pembayaran
        $denda = $this->hitungDenda($angsuran, $pinjaman);
        
        // Total yang harus dibayar = tagihan + denda
        $totalTagihanPlusDenda = $angsuran->jumlah_tagihan + $denda;
        
        // Hitung jumlah terbayar setelah pembayaran baru
        $jumlahTerbayarSebelumnya = $angsuran->jumlah_terbayar ?? 0;
        $jumlahTerbayar = $jumlahTerbayarSebelumnya + $request->jumlah_bayar;
        
        // Tentukan status bayar
        $statusBayar = 'belum';
        $tglBayar = null;
        
        if ($jumlahTerbayar >= $totalTagihanPlusDenda) {
            // Jika sudah membayar lebih dari atau sama dengan tagihan + denda
            $statusBayar = 'lunas';
            $jumlahTerbayar = $totalTagihanPlusDenda; // Jangan lebih dari tagihan + denda
            $denda = 0; // Reset denda jika sudah lunas
            $tglBayar = now();
        } elseif ($jumlahTerbayar >= $angsuran->jumlah_tagihan) {
            // Jika sudah membayar tagihan pokok, tapi masih ada denda
            $statusBayar = $angsuran->tgl_jatuh_tempo < now() ? 'telat' : 'belum';
            $tglBayar = now();
        } elseif ($angsuran->tgl_jatuh_tempo < now() && $jumlahTerbayar < $angsuran->jumlah_tagihan) {
            // Jika sudah telat dan belum lunas
            $statusBayar = 'telat';
        }

        $angsuran->update([
            'jumlah_terbayar' => $jumlahTerbayar,
            'denda' => $denda,
            'status_bayar' => $statusBayar,
            'tgl_bayar' => $tglBayar,
        ]);

        // Check if all angsuran sudah lunas, update pinjaman
        if ($pinjaman) {
            $allAngsuran = $pinjaman->jenis === 'bulanan' 
                ? $pinjaman->tempoBulanan 
                : $pinjaman->tempoMingguan;
            
            $allLunas = $allAngsuran->every(function($item) {
                return $item->status_bayar === 'lunas';
            });

            if ($allLunas) {
                $pinjaman->update(['lunas' => 'lunas']);
            }
        }

        return redirect()->back()
            ->with('success', 'Pembayaran angsuran berhasil diperbarui');
    }

    /**
     * Pelunasan dipercepat (early payment).
     */
    public function pelunasanDipercepat(Request $request, $id)
    {
        $request->validate([
            'potongan' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $pinjaman = PinjamanH::with(['tempoBulanan', 'tempoMingguan'])
            ->findOrFail($id);

        // Cek apakah pinjaman sudah lunas
        if ($pinjaman->lunas === 'lunas') {
            return redirect()->back()
                ->with('error', 'Pinjaman ini sudah lunas');
        }

        // Hitung sisa tagihan
        // Sistem bunga di awal: jumlah_pinjam sudah dikurangi bunga_rp
        // Total tagihan = nominal = jumlah_pinjam + bunga_rp
        $totalTagihan = $pinjaman->jumlah_pinjam + $pinjaman->bunga_rp;
        $totalTerbayar = $pinjaman->jenis === 'bulanan' 
            ? $pinjaman->tempoBulanan->sum('jumlah_terbayar')
            : $pinjaman->tempoMingguan->sum('jumlah_terbayar');
        
        $sisaTagihanPokok = $totalTagihan - $totalTerbayar;

        // Hitung total denda dari semua angsuran yang belum lunas
        $totalDenda = 0;
        $angsuranBelumLunas = $pinjaman->jenis === 'bulanan' 
            ? $pinjaman->tempoBulanan()->where('status_bayar', '!=', 'lunas')->get()
            : $pinjaman->tempoMingguan()->where('status_bayar', '!=', 'lunas')->get();

        foreach ($angsuranBelumLunas as $a) {
            $denda = $this->hitungDenda($a, $pinjaman);
            $totalDenda += $denda;
        }

        // Hitung potongan (opsional)
        $potongan = $request->potongan ?? 0;
        $jumlahBayar = $sisaTagihanPokok + $totalDenda - $potongan;

        // Update semua angsuran yang belum lunas
        foreach ($angsuranBelumLunas as $a) {
            $denda = $this->hitungDenda($a, $pinjaman);
            $totalPerAngsuran = $a->jumlah_tagihan + $denda;
            
            $a->update([
                'jumlah_terbayar' => $totalPerAngsuran,
                'denda' => 0, // Denda sudah dibayar
                'status_bayar' => 'lunas',
                'tgl_bayar' => now(),
            ]);
        }
        
        // Update pinjaman menjadi lunas
        $pinjaman->update(['lunas' => 'lunas']);

        return redirect()->route('admin.pinjaman.detail-pinjaman', $pinjaman->id)
            ->with('success', 'Pinjaman berhasil dilunasi dipercepat. Total pembayaran: Rp ' . number_format($jumlahBayar, 0, ',', '.'));
    }

    /**
     * Display list of pengajuan pembayaran pinjaman.
     */
    public function pembayaran(Request $request)
    {
        $query = PengajuanPembayaranPinjaman::with(['nasabah.user', 'pinjaman.pengajuan', 'janjiTemu.lokasi'])
            ->latest();

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        } else {
            // Default show pending
            $query->where('status', '1');
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->whereHas('nasabah.user', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $pengajuan = $query->paginate(15);

        return view('admin.pinjaman.pembayaran', compact('pengajuan'));
    }

    /**
     * Display detail pengajuan pembayaran.
     */
    public function detailPembayaran($id)
    {
        $pengajuan = PengajuanPembayaranPinjaman::with([
            'nasabah.user',
            'nasabah.dataKtp',
            'pinjaman.pengajuan',
            'janjiTemu.lokasi',
            'buktiFoto'
        ])->findOrFail($id);

        // Get angsuran yang terkait
        $angsuran = null;
        if ($pengajuan->tempo_id && $pengajuan->jenis_tempo) {
            if ($pengajuan->jenis_tempo === 'bulanan') {
                $angsuran = TempoPinjamanB::where('id', $pengajuan->tempo_id)->first();
            } else {
                $angsuran = TempoPinjamanM::where('id', $pengajuan->tempo_id)->first();
            }
        }

        // Get lokasi untuk janji temu (jika cash)
        $lokasi = JnsLokasiPerusahaan::all();

        return view('admin.pinjaman.detail-pembayaran', compact('pengajuan', 'angsuran', 'lokasi'));
    }

    /**
     * Approve pengajuan pembayaran.
     * BARU: Simpan keterangan_admin, update tgl_pembayaran, dan update tempo_pinjaman_b
     */
    public function approvePembayaran(Request $request, $id)
    {
        $request->validate([
            'keterangan_admin' => 'nullable|string|max:500',
        ]);

        $pengajuan = PengajuanPembayaranPinjaman::with(['pinjaman'])->findOrFail($id);

        if ($pengajuan->status !== '1') {
            return redirect()->back()
                ->with('error', 'Status pengajuan tidak valid untuk disetujui');
        }

        try {
            DB::beginTransaction();

            // Get angsuran yang terkait
            $angsuran = null;
            if ($pengajuan->tempo_id && $pengajuan->jenis_tempo) {
                if ($pengajuan->jenis_tempo === 'bulanan') {
                    $angsuran = TempoPinjamanB::where('id', $pengajuan->tempo_id)->first();
                } else {
                    $angsuran = TempoPinjamanM::where('id', $pengajuan->tempo_id)->first();
                }
            }

            if (!$angsuran) {
                return redirect()->back()
                    ->with('error', 'Data angsuran tidak ditemukan');
            }

            $pinjaman = $pengajuan->pinjaman;
            if (!$pinjaman) {
                return redirect()->back()
                    ->with('error', 'Data pinjaman tidak ditemukan');
            }

            // Hitung denda
            $denda = $this->hitungDenda($angsuran, $pinjaman);
            $totalTagihanPlusDenda = $angsuran->jumlah_tagihan + $denda;

            // Update angsuran dengan pembayaran
            $jumlahTerbayarBaru = ($angsuran->jumlah_terbayar ?? 0) + $pengajuan->nominal;
            
            $statusBayar = 'belum';
            $tglBayar = null;
            
            if ($jumlahTerbayarBaru >= $totalTagihanPlusDenda) {
                $statusBayar = 'lunas';
                $jumlahTerbayarBaru = $totalTagihanPlusDenda;
                $denda = 0;
                $tglBayar = now();
            } elseif ($jumlahTerbayarBaru >= $angsuran->jumlah_tagihan) {
                $statusBayar = $angsuran->tgl_jatuh_tempo < now() ? 'telat' : 'belum';
                $tglBayar = now();
            } elseif ($angsuran->tgl_jatuh_tempo < now() && $jumlahTerbayarBaru < $angsuran->jumlah_tagihan) {
                $statusBayar = 'telat';
                // Jika ada pembayaran parsial, tetap catat tanggal bayar
                if ($jumlahTerbayarBaru > 0) {
                    $tglBayar = now();
                }
            }

            $angsuran->update([
                'jumlah_terbayar' => $jumlahTerbayarBaru,
                'denda' => $denda,
                'status_bayar' => $statusBayar,
                'tgl_bayar' => $tglBayar,
            ]);

            // Check if all angsuran sudah lunas
            $allAngsuran = $pinjaman->jenis === 'bulanan' 
                ? $pinjaman->tempoBulanan 
                : $pinjaman->tempoMingguan;
            
            $allLunas = $allAngsuran->every(function($item) {
                return $item->status_bayar === 'lunas';
            });

            if ($allLunas) {
                $pinjaman->update(['lunas' => 'lunas']);
            }

            // Update status pengajuan pembayaran menjadi disetujui
            $pengajuan->update([
                'status' => '3', // Disetujui
                'keterangan_admin' => $request->keterangan_admin,
                'tgl_pembayaran' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.pinjaman.pembayaran')
                ->with('success', 'Pengajuan pembayaran berhasil disetujui dan angsuran diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Reject pengajuan pembayaran.
     */
    public function rejectPembayaran(Request $request, $id)
    {
        $request->validate([
            'keterangan_admin' => 'required|string|max:500',
        ]);

        $pengajuan = PengajuanPembayaranPinjaman::findOrFail($id);

        if ($pengajuan->status !== '1') {
            return redirect()->back()
                ->with('error', 'Status pengajuan tidak valid untuk ditolak');
        }

        $pengajuan->update([
            'status' => '2', // Ditolak
            'keterangan_admin' => $request->keterangan_admin,
        ]);

        return redirect()->route('admin.pinjaman.pembayaran')
            ->with('success', 'Pengajuan pembayaran ditolak');
    }

    /**
     * Upload bukti transfer / konfirmasi pembayaran.
     */
    public function konfirmasiPembayaran(Request $request, $id)
    {
        $pengajuan = PengajuanPembayaranPinjaman::with(['pinjaman'])->findOrFail($id);

        if ($pengajuan->status !== '3') {
            return redirect()->back()
                ->with('error', 'Pembayaran harus disetujui terlebih dahulu');
        }

        try {
            DB::beginTransaction();

            // Get angsuran
            $angsuran = null;
            if ($pengajuan->jenis_tempo === 'bulanan') {
                $angsuran = TempoPinjamanB::where('id', $pengajuan->tempo_id)->first();
            } else {
                $angsuran = TempoPinjamanM::where('id', $pengajuan->tempo_id)->first();
            }

            if (!$angsuran) {
                return redirect()->back()
                    ->with('error', 'Data angsuran tidak ditemukan');
            }

            $pinjaman = $pengajuan->pinjaman;

            // Hitung denda
            $denda = $this->hitungDenda($angsuran, $pinjaman);
            $totalTagihanPlusDenda = $angsuran->jumlah_tagihan + $denda;

            // Update angsuran
            $jumlahTerbayarBaru = ($angsuran->jumlah_terbayar ?? 0) + $pengajuan->nominal;
            
            $statusBayar = 'belum';
            if ($jumlahTerbayarBaru >= $totalTagihanPlusDenda) {
                $statusBayar = 'lunas';
                $jumlahTerbayarBaru = $totalTagihanPlusDenda;
                $denda = 0;
            } elseif ($angsuran->tgl_jatuh_tempo < now() && $jumlahTerbayarBaru < $angsuran->jumlah_tagihan) {
                $statusBayar = 'telat';
            }

            $angsuran->update([
                'jumlah_terbayar' => $jumlahTerbayarBaru,
                'denda' => $denda,
                'status_bayar' => $statusBayar,
                'tgl_bayar' => now(),
            ]);

            // Jika transfer, upload bukti bisa dilakukan disini
            if ($request->hasFile('bukti_transfer')) {
                $file = $request->file('bukti_transfer');
                $path = $file->store('bukti-pembayaran-pinjaman', 'public');
                
                // Generate ID for bukti foto: P (pinjaman) + TF (transfer) + PMB (pembayaran)
                $idBuktiFoto = IdGenerator::generate('tbl_bukti_foto', 'P', 'TF', 'PMB');
                
                BuktiFoto::create([
                    'id' => $idBuktiFoto,
                    'owner_id' => $pengajuan->id,
                    'owner_fitur' => 'P', // Pinjaman
                    'owner_trans' => 'PMB', // Pembayaran
                    'file_path' => $path,
                    'keterangan' => $request->keterangan,
                ]);
            }

            // Update status pengajuan menjadi terlaksana
            $pengajuan->update([
                'status' => '4', // Terlaksana
                'tgl_pembayaran' => now(),
            ]);

            // Check if all angsuran sudah lunas
            $allAngsuran = $pinjaman->jenis === 'bulanan' 
                ? $pinjaman->tempoBulanan 
                : $pinjaman->tempoMingguan;
            
            $allLunas = $allAngsuran->every(function($item) {
                return $item->status_bayar === 'lunas';
            });

            if ($allLunas) {
                $pinjaman->update(['lunas' => 'lunas']);
            }

            DB::commit();

            return redirect()->route('admin.pinjaman.pembayaran')
                ->with('success', 'Pembayaran berhasil dikonfirmasi dan angsuran diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Upload foto serah terima untuk pembayaran cash.
     */
    public function uploadSerahTerima(Request $request, $id)
    {
        $request->validate([
            'foto_serah_terima' => 'required|image|max:5120',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $pengajuan = PengajuanPembayaranPinjaman::findOrFail($id);

        if ($pengajuan->status !== '3') {
            return redirect()->back()
                ->with('error', 'Pembayaran harus disetujui terlebih dahulu');
        }

        try {
            DB::beginTransaction();

            // Upload foto serah terima
            $file = $request->file('foto_serah_terima');
            $path = $file->store('bukti-pembayaran-pinjaman', 'public');
            
            // Generate ID for bukti foto: P (pinjaman) + CS (cash) + PMB (pembayaran)
            $idBuktiFoto = IdGenerator::generate('tbl_bukti_foto', 'P', 'CS', 'PMB');
            
            BuktiFoto::create([
                'id' => $idBuktiFoto,
                'owner_id' => $pengajuan->id,
                'owner_fitur' => 'P', // Pinjaman
                'owner_trans' => 'PMB', // Pembayaran
                'file_path' => $path,
                'keterangan' => $request->keterangan,
            ]);

            // Get angsuran dan update
            $angsuran = null;
            if ($pengajuan->jenis_tempo === 'bulanan') {
                $angsuran = TempoPinjamanB::where('id', $pengajuan->tempo_id)->first();
            } else {
                $angsuran = TempoPinjamanM::where('id', $pengajuan->tempo_id)->first();
            }

            if ($angsuran) {
                $pinjaman = $pengajuan->pinjaman;
                $denda = $this->hitungDenda($angsuran, $pinjaman);
                $totalTagihanPlusDenda = $angsuran->jumlah_tagihan + $denda;

                $jumlahTerbayarBaru = ($angsuran->jumlah_terbayar ?? 0) + $pengajuan->nominal;
                
                $statusBayar = 'belum';
                if ($jumlahTerbayarBaru >= $totalTagihanPlusDenda) {
                    $statusBayar = 'lunas';
                    $jumlahTerbayarBaru = $totalTagihanPlusDenda;
                    $denda = 0;
                } elseif ($angsuran->tgl_jatuh_tempo < now() && $jumlahTerbayarBaru < $angsuran->jumlah_tagihan) {
                    $statusBayar = 'telat';
                }

                $angsuran->update([
                    'jumlah_terbayar' => $jumlahTerbayarBaru,
                    'denda' => $denda,
                    'status_bayar' => $statusBayar,
                    'tgl_bayar' => now(),
                ]);

                // Check if all angsuran sudah lunas
                $allAngsuran = $pinjaman->jenis === 'bulanan' 
                    ? $pinjaman->tempoBulanan 
                    : $pinjaman->tempoMingguan;
                
                $allLunas = $allAngsuran->every(function($item) {
                    return $item->status_bayar === 'lunas';
                });

                if ($allLunas) {
                    $pinjaman->update(['lunas' => 'lunas']);
                }
            }

            // Update status pengajuan menjadi terlaksana
            $pengajuan->update([
                'status' => '4', // Terlaksana
                'tgl_pembayaran' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.pinjaman.pembayaran')
                ->with('success', 'Foto serah terima berhasil diupload dan pembayaran dikonfirmasi');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show form create pinjaman (untuk yang janji temu/ketemu langsung).
     */
    public function createPinjaman()
    {
        $nasabah = Nasabah::with('user')->get();
        $masterBunga = MasterBungaPinjaman::where('status_aktif', true)->orderBy('durasi_min')->get();
        
        return view('admin.pinjaman.create-pinjaman', compact('nasabah', 'masterBunga'));
    }

    /**
     * Store pinjaman baru (untuk yang janji temu/ketemu langsung).
     */
    public function storePinjaman(Request $request)
    {
        $request->validate([
            'id_anggota' => 'required|exists:tbl_nasabah,id',
            'nominal' => 'required|numeric|min:100000',
            'durasi' => 'required|integer|min:1|max:24',
            'tgl_pinjam' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            // Get bunga dari master data
            $masterBunga = MasterBungaPinjaman::getBungaByDurasi($request->durasi);
            if (!$masterBunga) {
                return redirect()->back()
                    ->with('error', 'Bunga untuk durasi ' . $request->durasi . ' bulan belum diatur di master data')
                    ->withInput();
            }

            // Get denda dari master data
            $masterDenda = MasterDendaPinjaman::getDendaAktif();
            if (!$masterDenda) {
                return redirect()->back()
                    ->with('error', 'Denda belum diatur di master data')
                    ->withInput();
            }

            $nominal = $request->nominal;
            $bungaPersen = $masterBunga->bunga_persen;
            $bungaRp = ($nominal * $bungaPersen) / 100;

            // Create pinjaman langsung (tanpa pengajuan)
            $pinjaman = PinjamanH::create([
                'id_anggota' => $request->id_anggota,
                'id_pengajuan' => null, // Tidak ada pengajuan
                'jumlah_pinjam' => $nominal,
                'lama_pinjam' => (int)$request->durasi,
                'jenis' => 'bulanan',
                'bunga' => $bungaPersen / 100,
                'bunga_rp' => $bungaRp,
                'denda_persen' => $masterDenda->denda_persen,
                'tgl_pinjam' => $request->tgl_pinjam,
                'status' => 'telaksana', // Langsung terlaksana karena ketemu langsung
                'lunas' => 'belum',
            ]);

            // Generate jadwal angsuran
            $this->generateJadwalAngsuran($pinjaman);

            DB::commit();

            return redirect()->route('admin.pinjaman.pinjaman-aktif')
                ->with('success', 'Pinjaman berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show form edit pinjaman.
     */
    public function editPinjaman($id)
    {
        $pinjaman = PinjamanH::with(['nasabah.user'])->findOrFail($id);
        $nasabah = Nasabah::with('user')->get();
        $masterBunga = MasterBungaPinjaman::where('status_aktif', true)->orderBy('durasi_min')->get();
        
        return view('admin.pinjaman.edit-pinjaman', compact('pinjaman', 'nasabah', 'masterBunga'));
    }

    /**
     * Update pinjaman.
     */
    public function updatePinjaman(Request $request, $id)
    {
        $pinjaman = PinjamanH::findOrFail($id);

        // Cek apakah pinjaman sudah ada angsuran yang dibayar
        $hasPayment = TempoPinjamanB::where('pinjaman_id', $id)
            ->where('jumlah_terbayar', '>', 0)
            ->exists();

        if ($hasPayment) {
            return redirect()->back()
                ->with('error', 'Pinjaman tidak dapat diubah karena sudah ada pembayaran')
                ->withInput();
        }

        $request->validate([
            'id_anggota' => 'required|exists:tbl_nasabah,id',
            'nominal' => 'required|numeric|min:100000',
            'durasi' => 'required|integer|min:1|max:24',
            'tgl_pinjam' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            // Get bunga dari master data
            $masterBunga = MasterBungaPinjaman::getBungaByDurasi($request->durasi);
            if (!$masterBunga) {
                return redirect()->back()
                    ->with('error', 'Bunga untuk durasi ' . $request->durasi . ' bulan belum diatur di master data')
                    ->withInput();
            }

            $nominal = $request->nominal;
            $bungaPersen = $masterBunga->bunga_persen;
            $bungaRp = ($nominal * $bungaPersen) / 100;

            // Update pinjaman
            $pinjaman->update([
                'id_anggota' => $request->id_anggota,
                'jumlah_pinjam' => $nominal,
                'lama_pinjam' => (int)$request->durasi,
                'bunga' => $bungaPersen / 100,
                'bunga_rp' => $bungaRp,
                'tgl_pinjam' => $request->tgl_pinjam,
            ]);

            // Hapus angsuran lama dan buat baru
            TempoPinjamanB::where('pinjaman_id', $id)->delete();
            $this->generateJadwalAngsuran($pinjaman->fresh());

            DB::commit();

            return redirect()->route('admin.pinjaman.detail-pinjaman', $id)
                ->with('success', 'Pinjaman berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete pinjaman.
     */
    public function deletePinjaman($id)
    {
        $pinjaman = PinjamanH::findOrFail($id);

        // Cek apakah pinjaman sudah ada angsuran yang dibayar
        $hasPayment = TempoPinjamanB::where('pinjaman_id', $id)
            ->where('jumlah_terbayar', '>', 0)
            ->exists();

        if ($hasPayment) {
            return redirect()->back()
                ->with('error', 'Pinjaman tidak dapat dihapus karena sudah ada pembayaran');
        }

        try {
            DB::beginTransaction();

            // Hapus angsuran
            TempoPinjamanB::where('pinjaman_id', $id)->delete();
            
            // Hapus pinjaman
            $pinjaman->delete();

            DB::commit();

            return redirect()->route('admin.pinjaman.pinjaman-aktif')
                ->with('success', 'Pinjaman berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
