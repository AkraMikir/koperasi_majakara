<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use App\Models\PengajuanPinjaman;
use App\Models\PinjamanH;
use App\Models\TempoPinjamanB;
use App\Models\TempoPinjamanM;
use App\Models\JanjiTemuPinjaman;
use App\Models\JnsLokasiPerusahaan;
use App\Models\PengajuanPembayaranPinjaman;
use App\Models\JanjiTemuPembayaranPinjaman;
use App\Models\BuktiFotoPembayaranPinjaman;
use App\Models\MasterBungaPinjaman;
use App\Models\MasterDendaPinjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PinjamanController extends Controller
{
    /**
     * Show the pinjaman dashboard.
     */
    public function index()
    {
        $idAnggota = $this->getIdAnggota();

        // Get pinjaman aktif
        $pinjamanAktif = PinjamanH::where('id_anggota', $idAnggota)
            ->whereIn('status', ['pencairan', 'telaksana'])
            ->where('lunas', 'belum')
            ->with(['pengajuan', 'tempoBulanan', 'tempoMingguan'])
            ->latest()
            ->get();

        // Calculate total pinjaman aktif
        $totalPinjamanAktif = $pinjamanAktif->sum('jumlah_pinjam') ?? 0;

        // Calculate sisa pinjaman (total pinjaman - total terbayar)
        // Sistem bunga di awal: jumlah_pinjam sudah dikurangi bunga_rp
        // Total tagihan = nominal = jumlah_pinjam + bunga_rp
        $sisaPinjaman = 0;
        foreach ($pinjamanAktif as $pinjaman) {
            $totalTerbayar = 0;
            if ($pinjaman->jenis === 'bulanan') {
                $totalTerbayar = $pinjaman->tempoBulanan->sum('jumlah_terbayar') ?? 0;
            } else {
                $totalTerbayar = $pinjaman->tempoMingguan->sum('jumlah_terbayar') ?? 0;
            }
            // Total tagihan = nominal = jumlah_pinjam + bunga_rp (karena bunga sudah dipotong di awal)
            $totalTagihan = $pinjaman->jumlah_pinjam + $pinjaman->bunga_rp;
            $sisaPinjaman += max(0, $totalTagihan - $totalTerbayar);
        }

        // Get angsuran terdekat (jatuh tempo dalam 7 hari ke depan)
        $angsuranTerdekat = collect();
        foreach ($pinjamanAktif as $pinjaman) {
            if ($pinjaman->jenis === 'bulanan') {
                $tempo = $pinjaman->tempoBulanan()
                    ->where('status_bayar', 'belum')
                    ->whereBetween('tgl_jatuh_tempo', [now(), now()->addDays(7)])
                    ->orderBy('tgl_jatuh_tempo')
                    ->first();
            } else {
                $tempo = $pinjaman->tempoMingguan()
                    ->where('status_bayar', 'belum')
                    ->whereBetween('tgl_jatuh_tempo', [now(), now()->addDays(7)])
                    ->orderBy('tgl_jatuh_tempo')
                    ->first();
            }
            if ($tempo) {
                $tempo->pinjaman = $pinjaman;
                $angsuranTerdekat->push($tempo);
            }
        }
        $angsuranTerdekat = $angsuranTerdekat->sortBy('tgl_jatuh_tempo')->take(5);

        // Get total angsuran telat
        $totalAngsuranTelat = 0;
        foreach ($pinjamanAktif as $pinjaman) {
            if ($pinjaman->jenis === 'bulanan') {
                $telat = $pinjaman->tempoBulanan()
                    ->where('status_bayar', 'telat')
                    ->orWhere(function($q) {
                        $q->where('status_bayar', 'belum')
                          ->whereDate('tgl_jatuh_tempo', '<', now());
                    })
                    ->count();
            } else {
                $telat = $pinjaman->tempoMingguan()
                    ->where('status_bayar', 'telat')
                    ->orWhere(function($q) {
                        $q->where('status_bayar', 'belum')
                          ->whereDate('tgl_jatuh_tempo', '<', now());
                    })
                    ->count();
            }
            $totalAngsuranTelat += $telat;
        }

        // Get all angsuran untuk tabel
        $semuaAngsuran = collect();
        foreach ($pinjamanAktif as $pinjaman) {
            if ($pinjaman->jenis === 'bulanan') {
                $tempos = $pinjaman->tempoBulanan()->orderBy('no_urut')->get();
            } else {
                $tempos = $pinjaman->tempoMingguan()->orderBy('no_urut')->get();
            }
            foreach ($tempos as $tempo) {
                $tempo->pinjaman = $pinjaman;
                $semuaAngsuran->push($tempo);
            }
        }
        $semuaAngsuran = $semuaAngsuran->sortBy('tgl_jatuh_tempo')->take(10);

        return view('nasabah.pinjaman.index', [
            'pinjamanAktif' => $pinjamanAktif,
            'totalPinjamanAktif' => $totalPinjamanAktif,
            'sisaPinjaman' => $sisaPinjaman,
            'angsuranTerdekat' => $angsuranTerdekat,
            'totalAngsuranTelat' => $totalAngsuranTelat,
            'semuaAngsuran' => $semuaAngsuran,
        ]);
    }

    /**
     * Show the pengajuan pinjaman page (pilihan metode).
     */
    public function pengajuanPinjaman()
    {
        $idAnggota = $this->getIdAnggota();

        // Get riwayat pengajuan
        $riwayatPengajuan = PengajuanPinjaman::where('id_anggota', $idAnggota)
            ->with('pinjaman')
            ->latest()
            ->take(10)
            ->get();

        return view('nasabah.pinjaman.pengajuan-pinjaman', [
            'riwayatPengajuan' => $riwayatPengajuan,
        ]);
    }

    /**
     * Show the pengajuan transfer page.
     */
    public function pengajuanTransfer()
    {
        // Get master data bunga untuk info
        $masterBunga = MasterBungaPinjaman::where('status_aktif', true)->orderBy('durasi_min')->get();
        
        return view('nasabah.pinjaman.pengajuan-transfer', [
            'masterBunga' => $masterBunga,
        ]);
    }

    /**
     * Get simulasi angsuran (AJAX).
     */
    public function simulasiAngsuran(Request $request)
    {
        $request->validate([
            'nominal' => 'required|numeric|min:100000',
            'durasi' => 'required|integer|min:1|max:24',
        ]);

        $nominal = $request->nominal;
        $durasi = (int) $request->durasi;

        // Get bunga berdasarkan durasi
        $masterBunga = MasterBungaPinjaman::getBungaByDurasi($durasi);
        
        if (!$masterBunga) {
            return response()->json([
                'success' => false,
                'message' => 'Bunga untuk durasi ini belum diatur'
            ], 400);
        }

        $bungaPersen = $masterBunga->bunga_persen;
        $bungaRp = ($nominal * $bungaPersen) / 100;
        
        // Bunga dibagi ke setiap angsuran
        $bungaPerBulan = $bungaRp / $durasi;
        
        // Pokok per bulan
        $pokokPerBulan = $nominal / $durasi;
        
        // Total per angsuran (pokok + bunga)
        $totalPerAngsuran = $pokokPerBulan + $bungaPerBulan;

        // Generate simulasi per bulan
        $simulasi = [];
        $tanggalMulai = now();
        
        for ($i = 1; $i <= $durasi; $i++) {
            $tanggalJatuhTempo = $tanggalMulai->copy()->addMonths($i);
            
            $simulasi[] = [
                'bulan' => $i,
                'tanggal' => $tanggalJatuhTempo->format('d/m/Y'),
                'pokok' => round($pokokPerBulan, 2),
                'bunga' => round($bungaPerBulan, 2),
                'total' => round($totalPerAngsuran, 2),
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'nominal' => $nominal,
                'durasi' => $durasi,
                'bunga_persen' => $bungaPersen,
                'bunga_total' => round($bungaRp, 2),
                'total_yang_harus_dibayar' => round($nominal + $bungaRp, 2),
                'angsuran_per_bulan' => round($totalPerAngsuran, 2),
                'simulasi' => $simulasi,
            ]
        ]);
    }

    /**
     * Show the janji temu pinjaman page.
     */
    public function janjiTemuPinjaman(Request $request)
    {
        $lokasi = JnsLokasiPerusahaan::where('status_aktif', true)->get();
        
        return view('nasabah.pinjaman.janji-temu', [
            'lokasi' => $lokasi,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
        ]);
    }

    /**
     * Submit pengajuan pinjaman via transfer.
     */
    public function submitPengajuanTransfer(Request $request)
    {
        \Log::info('Submit pengajuan request received', [
            'all_data' => $request->except('pin'),
            'has_pin' => $request->has('pin'),
        ]);

        try {
            $validated = $request->validate([
                'nominal' => 'required|numeric|min:100000',
                'durasi' => 'required|integer|min:1|max:24',
                'pin' => 'required|numeric|digits:6',
                'keterangan' => 'nullable|string|max:500',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed', [
                'errors' => $e->errors(),
                'request' => $request->except('pin'),
            ]);
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput($request->except('pin'));
        }

        // Verify PIN
        $user = auth()->user();
        
        if (!$user->pin) {
            return redirect()->back()
                ->with('error', 'PIN belum diatur. Silakan atur PIN terlebih dahulu di profil Anda.')
                ->withInput($request->except('pin'));
        }

        // Convert both to integer for comparison
        $userPin = (int) $user->pin;
        $inputPin = (int) $request->pin;

        if ($userPin !== $inputPin) {
            return redirect()->back()
                ->with('error', 'PIN yang Anda masukkan salah!')
                ->withInput($request->except('pin'));
        }

        $idAnggota = $this->getIdAnggota();

        \Log::info('Submitting pengajuan pinjaman', [
            'user_id' => $user->id,
            'id_anggota' => $idAnggota,
            'nominal' => $request->nominal,
            'jenis' => $request->jenis,
            'durasi' => $request->durasi,
            'jenis_pencairan' => $request->jenis_pencairan,
        ]);

        try {
            // Create pengajuan (transfer) - hanya bulanan
            $pengajuan = PengajuanPinjaman::create([
                'id_anggota' => $idAnggota,
                'tgl_pengajuan' => now(),
                'nominal' => $request->nominal,
                'jenis' => 'bulanan', // Hanya bulanan
                'durasi' => (int)$request->durasi,
                'jenis_pencairan' => 'transfer',
                'status' => '1', // Pending
                'keterangan' => $request->keterangan,
            ]);

            \Log::info('Pengajuan transfer created successfully', [
                'pengajuan_id' => $pengajuan->id,
                'id_anggota' => $pengajuan->id_anggota,
            ]);

            return redirect()->route('nasabah.pinjaman.pengajuan')
                ->with('success', 'Pengajuan pinjaman berhasil dikirim!');
        } catch (\Exception $e) {
            \Log::error('Error creating pengajuan pinjaman: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'id_anggota' => $idAnggota,
                'request' => $request->except('pin'),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput($request->except('pin'));
        }
    }

    /**
     * Verify PIN for pengajuan pinjaman (AJAX).
     */
    public function verifyPin(Request $request)
    {
        try {
            $request->validate([
                'pin' => 'required|numeric|digits:6',
            ]);

            $user = auth()->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak terautentikasi.'
                ], 401);
            }
            
            if (!$user->pin) {
                return response()->json([
                    'success' => false,
                    'message' => 'PIN belum diatur. Silakan atur PIN terlebih dahulu.'
                ], 400);
            }

            $userPin = (int) $user->pin;
            $inputPin = (int) $request->pin;

            \Log::info('Verifying PIN', [
                'user_id' => $user->id,
                'user_pin' => $userPin,
                'input_pin' => $inputPin,
                'match' => $userPin === $inputPin
            ]);

            if ($userPin !== $inputPin) {
                return response()->json([
                    'success' => false,
                    'message' => 'PIN yang Anda masukkan salah.'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'PIN berhasil diverifikasi.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error verifying PIN: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat verifikasi PIN.'
            ], 500);
        }
    }

    /**
     * Submit pengajuan pinjaman via janji temu (tunai).
     */
    public function submitJanjiTemuPinjaman(Request $request)
    {
        \Log::info('Submit janji temu pinjaman request received', [
            'all_data' => $request->except('pin'),
            'has_pin' => $request->has('pin'),
        ]);

        try {
            $validated = $request->validate([
                'nominal' => 'required|numeric|min:100000',
                'durasi' => 'required|integer|min:1|max:24',
                'pin' => 'required|numeric|digits:6',
                'lokasi_temu' => 'required|exists:jns_lokasi_perusahaan,id',
                'tanggal_janji_temu' => 'required|date|after:today',
                'waktu_janji_temu' => 'required|date_format:H:i',
                'keterangan' => 'nullable|string|max:500',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed', [
                'errors' => $e->errors(),
                'request' => $request->except('pin'),
            ]);
            return redirect()->route('nasabah.pinjaman.janji-temu', [
                'nominal' => $request->nominal ?? '',
                'keterangan' => $request->keterangan ?? '',
            ])
                ->withErrors($e->errors())
                ->withInput($request->except('pin'));
        }

        // Verify PIN
        $user = auth()->user();
        
        if (!$user->pin) {
            return redirect()->route('nasabah.pinjaman.janji-temu', [
                'nominal' => $request->nominal ?? '',
                'keterangan' => $request->keterangan ?? '',
            ])
                ->with('error', 'PIN belum diatur. Silakan atur PIN terlebih dahulu di profil Anda.')
                ->withInput($request->except('pin'));
        }

        $userPin = (int) $user->pin;
        $inputPin = (int) $request->pin;

        if ($userPin !== $inputPin) {
            return redirect()->route('nasabah.pinjaman.janji-temu', [
                'nominal' => $request->nominal ?? '',
                'keterangan' => $request->keterangan ?? '',
            ])
                ->with('error', 'PIN yang Anda masukkan salah!')
                ->withInput($request->except('pin'));
        }

        $idAnggota = $this->getIdAnggota();

        \Log::info('Submitting janji temu pinjaman', [
            'user_id' => $user->id,
            'id_anggota' => $idAnggota,
            'nominal' => $request->nominal,
            'jenis' => $request->jenis,
            'durasi' => $request->durasi,
        ]);

        try {
            // Create pengajuan (cash) - hanya bulanan
            $pengajuan = PengajuanPinjaman::create([
                'id_anggota' => $idAnggota,
                'tgl_pengajuan' => now(),
                'nominal' => $request->nominal,
                'jenis' => 'bulanan', // Hanya bulanan
                'durasi' => (int)$request->durasi,
                'jenis_pencairan' => 'cash',
                'status' => '1', // Pending
                'keterangan' => $request->keterangan,
            ]);

            \Log::info('Pengajuan cash created successfully', [
                'pengajuan_id' => $pengajuan->id,
                'id_anggota' => $pengajuan->id_anggota,
            ]);

            // Create janji temu
            JanjiTemuPinjaman::create([
                'id_pengajuan' => $pengajuan->id,
                'lokasi_temu' => $request->lokasi_temu,
                'nominal' => $request->nominal,
                'tanggal_janji_temu' => $request->tanggal_janji_temu,
                'waktu_janji_temu' => $request->waktu_janji_temu,
                'keterangan' => $request->keterangan,
            ]);

            return redirect()->route('nasabah.pinjaman.pengajuan')
                ->with('success', 'Pengajuan pinjaman berhasil dikirim!');
        } catch (\Exception $e) {
            \Log::error('Error creating pengajuan pinjaman: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'id_anggota' => $idAnggota,
                'request' => $request->except('pin'),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('nasabah.pinjaman.janji-temu', [
                'nominal' => $request->nominal ?? '',
                'keterangan' => $request->keterangan ?? '',
            ])
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput($request->except('pin'));
        }
    }

    /**
     * Show status pengajuan pinjaman.
     */
    public function statusPengajuan(Request $request)
    {
        $idAnggota = $this->getIdAnggota();

        $query = PengajuanPinjaman::where('id_anggota', $idAnggota)
            ->with('pinjaman')
            ->latest();

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
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

        $pengajuan = $query->paginate(15);

        return view('nasabah.pinjaman.status-pengajuan', [
            'pengajuan' => $pengajuan,
        ]);
    }

    /**
     * Show detail pengajuan pinjaman.
     */
    public function detailPengajuan($id)
    {
        $idAnggota = $this->getIdAnggota();

        $pengajuan = PengajuanPinjaman::where('id_anggota', $idAnggota)
            ->with(['pinjaman', 'nasabah.user'])
            ->findOrFail($id);

        return view('nasabah.pinjaman.detail-pengajuan', [
            'pengajuan' => $pengajuan,
        ]);
    }

    /**
     * Show pinjaman aktif list.
     */
    public function pinjamanAktif(Request $request)
    {
        $idAnggota = $this->getIdAnggota();

        $query = PinjamanH::where('id_anggota', $idAnggota)
            ->whereIn('status', ['pencairan', 'telaksana'])
            ->where('lunas', 'belum')
            ->with(['pengajuan', 'tempoBulanan', 'tempoMingguan'])
            ->latest();

        // Filter by jenis
        if ($request->has('jenis') && $request->jenis !== '') {
            $query->where('jenis', $request->jenis);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $pinjaman = $query->paginate(15);

        return view('nasabah.pinjaman.pinjaman-aktif', [
            'pinjaman' => $pinjaman,
        ]);
    }

    /**
     * Show detail pinjaman.
     */
    public function detailPinjaman($id)
    {
        $idAnggota = $this->getIdAnggota();

        $pinjaman = PinjamanH::where('id_anggota', $idAnggota)
            ->with([
                'pengajuan',
                'nasabah.user',
                'tempoBulanan',
                'tempoMingguan'
            ])
            ->findOrFail($id);

        // Get angsuran berdasarkan jenis
        $angsuran = $pinjaman->jenis === 'bulanan' 
            ? $pinjaman->tempoBulanan()->orderBy('no_urut')->get()
            : $pinjaman->tempoMingguan()->orderBy('no_urut')->get();

        // Calculate statistics
        // Sistem bunga di awal: jumlah_pinjam sudah dikurangi bunga_rp
        // Total tagihan = nominal = jumlah_pinjam + bunga_rp
        $totalTagihan = $pinjaman->jumlah_pinjam + $pinjaman->bunga_rp;
        $totalTerbayar = $angsuran->sum('jumlah_terbayar') ?? 0;
        $sisaPinjaman = max(0, $totalTagihan - $totalTerbayar);
        $progress = $totalTagihan > 0 ? ($totalTerbayar / $totalTagihan) * 100 : 0;
        $angsuranLunas = $angsuran->where('status_bayar', 'lunas')->count();
        $totalAngsuran = $angsuran->count();

        return view('nasabah.pinjaman.detail-pinjaman', [
            'pinjaman' => $pinjaman,
            'angsuran' => $angsuran,
            'totalTagihan' => $totalTagihan,
            'totalTerbayar' => $totalTerbayar,
            'sisaPinjaman' => $sisaPinjaman,
            'progress' => $progress,
            'angsuranLunas' => $angsuranLunas,
            'totalAngsuran' => $totalAngsuran,
        ]);
    }

    /**
     * Show angsuran list.
     */
    public function angsuran(Request $request)
    {
        $idAnggota = $this->getIdAnggota();

        $jenis = $request->get('jenis', 'bulanan');
        $query = null;

        if ($jenis === 'bulanan') {
            $query = TempoPinjamanB::where('anggota_id', $idAnggota)
                ->with(['pinjaman.pengajuan'])
                ->latest('tgl_jatuh_tempo');
        } else {
            $query = TempoPinjamanM::where('anggota_id', $idAnggota)
                ->with(['pinjaman.pengajuan'])
                ->latest('tgl_jatuh_tempo');
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status_bayar', $request->status);
        }

        // Filter by date
        if ($request->has('tanggal_dari') && $request->tanggal_dari !== '') {
            $query->whereDate('tgl_jatuh_tempo', '>=', $request->tanggal_dari);
        }

        if ($request->has('tanggal_sampai') && $request->tanggal_sampai !== '') {
            $query->whereDate('tgl_jatuh_tempo', '<=', $request->tanggal_sampai);
        }

        $angsuran = $query->paginate(20);

        return view('nasabah.pinjaman.angsuran', [
            'angsuran' => $angsuran,
            'jenis' => $jenis,
        ]);
    }

    /**
     * Show detail angsuran.
     */
    public function detailAngsuran(Request $request, $id)
    {
        $idAnggota = $this->getIdAnggota();
        $jenis = $request->get('jenis', 'bulanan');

        if ($jenis === 'bulanan') {
            $angsuran = TempoPinjamanB::where('anggota_id', $idAnggota)
                ->with(['pinjaman.pengajuan', 'nasabah.user'])
                ->findOrFail($id);
        } else {
            $angsuran = TempoPinjamanM::where('anggota_id', $idAnggota)
                ->with(['pinjaman.pengajuan', 'nasabah.user'])
                ->findOrFail($id);
        }

        $sisaTagihan = max(0, $angsuran->jumlah_tagihan - ($angsuran->jumlah_terbayar ?? 0));
        $isTelat = $angsuran->tgl_jatuh_tempo < now() && $angsuran->status_bayar !== 'lunas';
        
        // Hitung denda
        $denda = $this->hitungDenda($angsuran);
        $totalTagihanPlusDenda = $angsuran->jumlah_tagihan + $denda;

        return view('nasabah.pinjaman.detail-angsuran', [
            'angsuran' => $angsuran,
            'jenis' => $jenis,
            'sisaTagihan' => $sisaTagihan,
            'isTelat' => $isTelat,
            'denda' => $denda,
            'totalTagihanPlusDenda' => $totalTagihanPlusDenda,
        ]);
    }

    /**
     * Hitung denda untuk angsuran yang telat (untuk nasabah).
     * 
     * Aturan:
     * - Denda 0.3% per hari dari jumlah tagihan angsuran
     * - Denda mulai dihitung 1 hari setelah tanggal jatuh tempo
     * - Denda berhenti jika sudah ada pembayaran (walaupun sedikit)
     */
    private function hitungDenda($angsuran)
    {
        // Jika sudah lunas, tidak ada denda
        if ($angsuran->status_bayar === 'lunas') {
            return $angsuran->denda ?? 0;
        }

        // Jika sudah ada pembayaran, denda berhenti (gunakan denda yang sudah ada)
        if ($angsuran->jumlah_terbayar > 0) {
            return $angsuran->denda ?? 0;
        }

        // Hitung hari telat (1 hari setelah jatuh tempo)
        $tanggalMulaiDenda = $angsuran->tgl_jatuh_tempo->copy()->addDay();
        $hariTelat = now()->diffInDays($tanggalMulaiDenda, false);
        
        // Jika belum telat (belum 1 hari setelah jatuh tempo), tidak ada denda
        if ($hariTelat <= 0) {
            return 0;
        }

        $pinjaman = $angsuran->pinjaman;
        if (!$pinjaman) {
            return 0;
        }

        // Get denda persen dari pinjaman
        $dendaPersen = $pinjaman->denda_persen ?? 0.30; // Default 0.3% per hari
        
        // Denda dihitung dari jumlah tagihan angsuran
        $denda = $angsuran->jumlah_tagihan * ($dendaPersen / 100) * $hariTelat;

        return round($denda, 2);
    }

    /**
     * Show form pembayaran pinjaman.
     */
    public function pembayaran(Request $request)
    {
        $idAnggota = $this->getIdAnggota();
        $pinjamanId = $request->get('pinjaman_id');
        $tempoId = $request->get('tempo_id');
        $jenis = $request->get('jenis', 'bulanan');

        // Get pinjaman aktif
        $pinjamanAktif = PinjamanH::where('id_anggota', $idAnggota)
            ->whereIn('status', ['pencairan', 'telaksana'])
            ->where('lunas', 'belum')
            ->with(['pengajuan'])
            ->get();

        $selectedPinjaman = null;
        $selectedAngsuran = null;
        $angsuranList = collect();

        if ($pinjamanId) {
            $selectedPinjaman = PinjamanH::where('id_anggota', $idAnggota)
                ->where('id', $pinjamanId)
                ->with(['pengajuan'])
                ->first();

            if ($selectedPinjaman) {
                // Get angsuran yang belum lunas
                if ($selectedPinjaman->jenis === 'bulanan') {
                    $angsuranList = $selectedPinjaman->tempoBulanan()
                        ->where('status_bayar', '!=', 'lunas')
                        ->orderBy('no_urut')
                        ->get();
                } else {
                    $angsuranList = $selectedPinjaman->tempoMingguan()
                        ->where('status_bayar', '!=', 'lunas')
                        ->orderBy('no_urut')
                        ->get();
                }

                if ($tempoId) {
                    if ($selectedPinjaman->jenis === 'bulanan') {
                        $selectedAngsuran = TempoPinjamanB::where('id', $tempoId)
                            ->where('anggota_id', $idAnggota)
                            ->first();
                    } else {
                        $selectedAngsuran = TempoPinjamanM::where('id', $tempoId)
                            ->where('anggota_id', $idAnggota)
                            ->first();
                    }
                }
            }
        }

        // Get lokasi untuk janji temu
        $lokasi = JnsLokasiPerusahaan::all();

        return view('nasabah.pinjaman.pembayaran', [
            'pinjamanAktif' => $pinjamanAktif,
            'selectedPinjaman' => $selectedPinjaman,
            'selectedAngsuran' => $selectedAngsuran,
            'angsuranList' => $angsuranList,
            'lokasi' => $lokasi,
            'jenis' => $jenis,
        ]);
    }

    /**
     * Submit pembayaran via transfer.
     */
    public function submitPembayaranTransfer(Request $request)
    {
        $validated = $request->validate([
            'pinjaman_id' => 'required|exists:tbl_pinjaman_h,id',
            'tempo_id' => 'required',
            'jenis_tempo' => 'required|in:bulanan,mingguan',
            'nominal' => 'required|numeric|min:10000',
            'rekening_tujuan' => 'required|string|max:255',
            'pin' => 'required|numeric|digits:6',
            'bukti_foto.*' => 'required|image|max:5120',
            'keterangan' => 'nullable|string|max:500',
        ]);

        // Verify PIN
        $user = auth()->user();
        
        if (!$user->pin) {
            return redirect()->back()
                ->with('error', 'PIN belum diatur. Silakan atur PIN terlebih dahulu di profil Anda.')
                ->withInput($request->except('pin'));
        }

        $userPin = (int) $user->pin;
        $inputPin = (int) $request->pin;

        if ($userPin !== $inputPin) {
            return redirect()->back()
                ->with('error', 'PIN yang Anda masukkan salah!')
                ->withInput($request->except('pin'));
        }

        $idAnggota = $this->getIdAnggota();

        // Verify pinjaman belongs to nasabah
        $pinjaman = PinjamanH::where('id', $request->pinjaman_id)
            ->where('id_anggota', $idAnggota)
            ->firstOrFail();

        try {
            // Create pengajuan pembayaran
            $pengajuan = PengajuanPembayaranPinjaman::create([
                'id_anggota' => $idAnggota,
                'pinjaman_id' => $request->pinjaman_id,
                'tempo_id' => $request->tempo_id,
                'jenis_tempo' => $request->jenis_tempo,
                'nominal' => $request->nominal,
                'rekening_tujuan' => $request->rekening_tujuan,
                'keterangan' => $request->keterangan,
                'status' => '1', // Pending
            ]);

            // Upload bukti foto
            if ($request->hasFile('bukti_foto')) {
                foreach ($request->file('bukti_foto') as $file) {
                    $path = $file->store('bukti-pembayaran-pinjaman', 'public');
                    
                    BuktiFotoPembayaranPinjaman::create([
                        'id_pengajuan' => $pengajuan->id,
                        'file_photo' => $path,
                        'jenis' => 'bukti_transfer',
                        'keterangan' => $request->keterangan,
                    ]);
                }
            }

            return redirect()->route('nasabah.pinjaman.status-pembayaran')
                ->with('success', 'Pengajuan pembayaran berhasil dikirim!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput($request->except('pin'));
        }
    }

    /**
     * Submit janji temu pembayaran cash.
     */
    public function submitJanjiTemuPembayaran(Request $request)
    {
        $validated = $request->validate([
            'pinjaman_id' => 'required|exists:tbl_pinjaman_h,id',
            'tempo_id' => 'required',
            'jenis_tempo' => 'required|in:bulanan,mingguan',
            'nominal' => 'required|numeric|min:10000',
            'lokasi_temu' => 'required|exists:jns_lokasi_perusahaan,id',
            'tanggal_janji_temu' => 'required|date|after:today',
            'waktu_janji_temu' => 'required|date_format:H:i',
            'pin' => 'required|numeric|digits:6',
            'keterangan' => 'nullable|string|max:500',
        ]);

        // Verify PIN
        $user = auth()->user();
        
        if (!$user->pin) {
            return redirect()->back()
                ->with('error', 'PIN belum diatur. Silakan atur PIN terlebih dahulu di profil Anda.')
                ->withInput($request->except('pin'));
        }

        $userPin = (int) $user->pin;
        $inputPin = (int) $request->pin;

        if ($userPin !== $inputPin) {
            return redirect()->back()
                ->with('error', 'PIN yang Anda masukkan salah!')
                ->withInput($request->except('pin'));
        }

        $idAnggota = $this->getIdAnggota();

        // Verify pinjaman belongs to nasabah
        $pinjaman = PinjamanH::where('id', $request->pinjaman_id)
            ->where('id_anggota', $idAnggota)
            ->firstOrFail();

        try {
            // Create pengajuan pembayaran
            $pengajuan = PengajuanPembayaranPinjaman::create([
                'id_anggota' => $idAnggota,
                'pinjaman_id' => $request->pinjaman_id,
                'tempo_id' => $request->tempo_id,
                'jenis_tempo' => $request->jenis_tempo,
                'nominal' => $request->nominal,
                'keterangan' => $request->keterangan,
                'status' => '1', // Pending
            ]);

            // Create janji temu
            JanjiTemuPembayaranPinjaman::create([
                'id_pengajuan' => $pengajuan->id,
                'lokasi_temu' => $request->lokasi_temu,
                'nominal' => $request->nominal,
                'tanggal_janji_temu' => $request->tanggal_janji_temu,
                'waktu_janji_temu' => $request->waktu_janji_temu,
                'keterangan' => $request->keterangan,
            ]);

            return redirect()->route('nasabah.pinjaman.status-pembayaran')
                ->with('success', 'Pengajuan janji temu pembayaran berhasil dikirim!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput($request->except('pin'));
        }
    }

    /**
     * Show status pengajuan pembayaran.
     */
    public function statusPembayaran(Request $request)
    {
        $idAnggota = $this->getIdAnggota();

        $query = PengajuanPembayaranPinjaman::where('id_anggota', $idAnggota)
            ->with(['pinjaman.pengajuan', 'janjiTemu.lokasi', 'buktiFoto'])
            ->latest();

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $pengajuan = $query->paginate(15);

        return view('nasabah.pinjaman.status-pembayaran', [
            'pengajuan' => $pengajuan,
        ]);
    }

    /**
     * Show detail pengajuan pembayaran.
     */
    public function detailPembayaran($id)
    {
        $idAnggota = $this->getIdAnggota();

        $pengajuan = PengajuanPembayaranPinjaman::where('id_anggota', $idAnggota)
            ->with(['pinjaman.pengajuan', 'janjiTemu.lokasi', 'buktiFoto'])
            ->findOrFail($id);

        return view('nasabah.pinjaman.detail-pembayaran', [
            'pengajuan' => $pengajuan,
        ]);
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
