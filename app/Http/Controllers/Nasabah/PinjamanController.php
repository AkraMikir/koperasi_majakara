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
use App\Models\BuktiFoto;
use App\Models\MasterBungaPinjaman;
use App\Models\MasterDendaPinjaman;
use App\Helpers\IdGenerator;
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
     * Show the pengajuan pinjaman page (satu halaman: pilihan metode + form transfer/tunai inline).
     */
    public function pengajuanPinjaman(Request $request)
    {
        $idAnggota = $this->getIdAnggota();

        $riwayatPengajuan = PengajuanPinjaman::where('id_anggota', $idAnggota)
            ->with('pinjaman')
            ->latest()
            ->take(10)
            ->get();

        $masterBunga = MasterBungaPinjaman::where('status_aktif', true)->orderBy('durasi_min')->get();
        $durasiList = \App\Models\JnsAngsuranBulan::where('aktif', 'y')->orderBy('bulan')->get();
        if ($durasiList->isEmpty()) {
            $durasiList = collect(range(1, 24))->map(fn ($b) => (object)['bulan' => $b, 'ket' => (string)$b]);
        }
        $lokasi = JnsLokasiPerusahaan::where('status_aktif', true)->get();

        return view('nasabah.pinjaman.pengajuan-pinjaman', [
            'riwayatPengajuan' => $riwayatPengajuan,
            'masterBunga' => $masterBunga,
            'durasiList' => $durasiList,
            'lokasi' => $lokasi,
            'openMetode' => $request->get('metode'), // 'transfer' | 'tunai'
        ]);
    }

    /**
     * Redirect ke halaman pengajuan (satu halaman) dengan metode transfer.
     */
    public function pengajuanTransfer()
    {
        return redirect()->route('nasabah.pinjaman.pengajuan', ['metode' => 'transfer']);
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

        $bungaPersen = (float) $masterBunga->bunga_persen;
        $bungaRp = round($nominal * $bungaPersen / 100, 2);
        $totalKewajiban = $nominal + $bungaRp; // Total yang harus dibayar tepat

        // Angsuran: n-1 pertama dibulatkan ke bawah ke ratusan, bulan terakhir = sisa
        $angsuranBulanan = (int) floor($totalKewajiban / $durasi / 100) * 100;
        $akumulasi = $angsuranBulanan * ($durasi - 1);
        $angsuranTerakhir = (int) round($totalKewajiban - $akumulasi, 0);

        $simulasi = [];
        $tanggalMulai = now();
        for ($i = 1; $i <= $durasi; $i++) {
            $tanggalJatuhTempo = $tanggalMulai->copy()->addMonths($i);
            $totalBulan = ($i < $durasi) ? $angsuranBulanan : $angsuranTerakhir;
            $simulasi[] = [
                'bulan' => $i,
                'tanggal' => $tanggalJatuhTempo->format('d/m/Y'),
                'pokok' => 0,
                'bunga' => 0,
                'total' => (int) $totalBulan,
            ];
        }

        $displayAngsuran = $durasi > 1 ? $angsuranBulanan : (int) $totalKewajiban;

        return response()->json([
            'success' => true,
            'data' => [
                'nominal' => (float) $nominal,
                'durasi' => $durasi,
                'bunga_persen' => $bungaPersen,
                'bunga_total' => $bungaRp,
                'total_yang_harus_dibayar' => (int) round($totalKewajiban, 0),
                'angsuran_per_bulan' => $displayAngsuran,
                'simulasi' => $simulasi,
            ]
        ]);
    }

    /**
     * Redirect ke halaman pengajuan (satu halaman) dengan metode tunai.
     */
    public function janjiTemuPinjaman(Request $request)
    {
        return redirect()->route('nasabah.pinjaman.pengajuan', ['metode' => 'tunai']);
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

        // Clean nominal dari format rupiah
        $nominalRaw = $request->input('nominal_raw') ?? str_replace(['.', ',', ' '], '', $request->input('nominal'));
        $request->merge(['nominal' => $nominalRaw]);
        
        $rules = [
            'nominal' => 'required|numeric|min:100000',
            'durasi' => 'required|integer|min:1|max:24',
            'pin' => 'required|numeric|digits:6',
            'keterangan' => 'nullable|string|max:500',
        ];
        
        try {
            $validated = $request->validate($rules);
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
        $jenisPencairan = 'transfer'; // Auto set to transfer for this form

        // Bunga dari master_bunga_pinjaman sesuai durasi
        $durasi = (int) $request->durasi;
        $bungaMaster = MasterBungaPinjaman::where('status_aktif', true)
            ->where('durasi_min', '<=', $durasi)
            ->where('durasi_max', '>=', $durasi)
            ->first();
        $bungaPersen = $bungaMaster ? (float) $bungaMaster->bunga_persen : 10.00;

        // ID dari 3 master: P (pinjaman), TF (transfer), PNJ (pengajuan)
        $idPengajuan = IdGenerator::generate('tbl_pengajuan_pinjaman', 'P', 'TF', 'PNJ');

        try {
            $pengajuan = PengajuanPinjaman::create([
                'id' => $idPengajuan,
                'id_anggota' => $idAnggota,
                'tgl_pengajuan' => now(),
                'nominal' => $request->nominal,
                'jenis' => 'bulanan', // Auto set to bulanan for transfer
                'durasi' => $durasi,
                'jenis_pencairan' => $jenisPencairan,
                'status' => '1', // Status 1 = pending
                'keterangan' => $request->keterangan,
                'bunga_persen' => $bungaPersen,
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
        // Clean nominal dari format rupiah (sama seperti transfer)
        $nominalRaw = $request->input('nominal_raw') ?? str_replace(['.', ',', ' '], '', $request->input('nominal'));
        $request->merge(['nominal' => $nominalRaw]);

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
        $durasi = (int) $request->durasi;

        // Bunga dari master_bunga_pinjaman sesuai durasi
        $bungaMaster = MasterBungaPinjaman::where('status_aktif', true)
            ->where('durasi_min', '<=', $durasi)
            ->where('durasi_max', '>=', $durasi)
            ->first();
        $bungaPersen = $bungaMaster ? (float) $bungaMaster->bunga_persen : 10.00;

        // ID dari 3 master: P (pinjaman), TN (tunai), PNJ (pengajuan)
        $idPengajuan = IdGenerator::generate('tbl_pengajuan_pinjaman', 'P', 'TN', 'PNJ');

        \Log::info('Submitting janji temu pinjaman', [
            'user_id' => $user->id,
            'id_anggota' => $idAnggota,
            'nominal' => $request->nominal,
            'durasi' => $durasi,
        ]);

        try {
            // Create pengajuan (tunai) - hanya bulanan
            $pengajuan = PengajuanPinjaman::create([
                'id' => $idPengajuan,
                'id_anggota' => $idAnggota,
                'tgl_pengajuan' => now(),
                'nominal' => $request->nominal,
                'jenis' => 'bulanan',
                'durasi' => $durasi,
                'jenis_pencairan' => 'tunai',
                'status' => '1', // Pending
                'keterangan' => $request->keterangan,
                'bunga_persen' => $bungaPersen,
            ]);

            \Log::info('Pengajuan cash created successfully', [
                'pengajuan_id' => $pengajuan->id,
                'id_anggota' => $pengajuan->id_anggota,
            ]);

            // Create janji temu
            JanjiTemuPinjaman::create([
                'id_pengajuan' => $pengajuan->id,
                'id_nasabah' => $idAnggota,
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
            $query = TempoPinjamanB::whereHas('pinjaman', function($q) use ($idAnggota) {
                    $q->where('id_anggota', $idAnggota);
                })
                ->with(['pinjaman.pengajuan'])
                ->latest('tgl_jatuh_tempo');
        } else {
            $query = TempoPinjamanM::whereHas('pinjaman', function($q) use ($idAnggota) {
                    $q->where('id_anggota', $idAnggota);
                })
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
            $angsuran = TempoPinjamanB::whereHas('pinjaman', function($q) use ($idAnggota) {
                    $q->where('id_anggota', $idAnggota);
                })
                ->with(['pinjaman.pengajuan', 'pinjaman.nasabah.user'])
                ->findOrFail($id);
        } else {
            $angsuran = TempoPinjamanM::whereHas('pinjaman', function($q) use ($idAnggota) {
                    $q->where('id_anggota', $idAnggota);
                })
                ->with(['pinjaman.pengajuan', 'pinjaman.nasabah.user'])
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
     * Aturan REVISI TERBARU:
     * - Denda 0.3% per hari dari POKOK ANGSURAN per bulan (bukan total tagihan)
     * - Denda mulai dihitung 1 hari SETELAH tanggal jatuh tempo (H+1)
     * - Denda BERHENTI jika sudah ada pembayaran (walaupun Rp 1)
     * 
     * Contoh:
     * Pinjaman 3 juta, 3 bulan
     * Pokok per bulan = 1 juta
     * Denda = 1.000.000 × 0.3% × hari_telat
     * Jika telat 1 hari = Rp 3.000
     * Jika telat 2 hari = Rp 6.000
     */
    private function hitungDenda($angsuran)
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

        $pinjaman = $angsuran->pinjaman;
        if (!$pinjaman) {
            return 0;
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
                            ->whereHas('pinjaman', function($q) use ($idAnggota) {
                                $q->where('id_anggota', $idAnggota);
                            })
                            ->first();
                    } else {
                        $selectedAngsuran = TempoPinjamanM::where('id', $tempoId)
                            ->whereHas('pinjaman', function($q) use ($idAnggota) {
                                $q->where('id_anggota', $idAnggota);
                            })
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
            'tempo_id' => 'required|exists:tempo_pinjaman_b,id',
            'jenis_tempo' => 'required|in:bulanan,mingguan',
            'nominal' => 'required|numeric|min:1',
            'rekening_tujuan' => 'required|string|max:255',
            'pin' => 'required|numeric|digits:6',
            'bukti_foto.*' => 'nullable|image|max:5120',
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

        // ID dari 3 master: P (pinjaman), TF (transfer), PMB (pembayaran)
        $idPengajuanPembayaran = IdGenerator::generate('tbl_pengajuan_pembayaran_pinjaman', 'P', 'TF', 'PMB');

        try {
            // Create pengajuan pembayaran (tempo_id FK ke tempo_pinjaman_b)
            $pengajuan = PengajuanPembayaranPinjaman::create([
                'id' => $idPengajuanPembayaran,
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
            'tempo_id' => 'required|exists:tempo_pinjaman_b,id',
            'jenis_tempo' => 'required|in:bulanan,mingguan',
            'nominal' => 'required|numeric|min:1',
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

        // ID dari 3 master: P (pinjaman), TN (tunai), PMB (pembayaran)
        $idPengajuanPembayaran = IdGenerator::generate('tbl_pengajuan_pembayaran_pinjaman', 'P', 'TN', 'PMB');

        try {
            // Create pengajuan pembayaran (tempo_id FK ke tempo_pinjaman_b)
            $pengajuan = PengajuanPembayaranPinjaman::create([
                'id' => $idPengajuanPembayaran,
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
