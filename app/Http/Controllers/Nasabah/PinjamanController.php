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
        $sisaPinjaman = 0;
        foreach ($pinjamanAktif as $pinjaman) {
            $totalTerbayar = 0;
            if ($pinjaman->jenis === 'bulanan') {
                $totalTerbayar = $pinjaman->tempoBulanan->sum('jumlah_terbayar') ?? 0;
            } else {
                $totalTerbayar = $pinjaman->tempoMingguan->sum('jumlah_terbayar') ?? 0;
            }
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
     * Show the pengajuan pinjaman page.
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

        // Get lokasi untuk janji temu
        $lokasi = JnsLokasiPerusahaan::all();

        return view('nasabah.pinjaman.pengajuan-pinjaman', [
            'riwayatPengajuan' => $riwayatPengajuan,
            'lokasi' => $lokasi,
        ]);
    }

    /**
     * Submit pengajuan pinjaman.
     */
    public function submitPengajuan(Request $request)
    {
        $validated = $request->validate([
            'nominal' => 'required|numeric|min:100000',
            'jenis' => 'required|in:bulanan,mingguan',
            'durasi' => 'required|integer|min:1|max:12',
            'jenis_pencairan' => 'required|in:transfer,cash',
            'pin' => 'required|numeric|digits:6',
            'keterangan' => 'nullable|string|max:500',
            // Fields untuk janji temu (jika cash)
            'lokasi_temu' => 'required_if:jenis_pencairan,cash|exists:jns_lokasi_perusahaan,id',
            'tanggal_janji_temu' => 'required_if:jenis_pencairan,cash|date|after:today',
            'waktu_janji_temu' => 'required_if:jenis_pencairan,cash|date_format:H:i',
        ]);

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

        try {
            // Create pengajuan
            $pengajuan = PengajuanPinjaman::create([
                'id_anggota' => $idAnggota,
                'tgl_pengajuan' => now(),
                'nominal' => $request->nominal,
                'jenis' => $request->jenis,
                'durasi' => (string)$request->durasi,
                'jenis_pencairan' => $request->jenis_pencairan,
                'status' => '1', // Pending
                'keterangan' => $request->keterangan,
            ]);

            // Jika jenis pencairan = cash, buat janji temu
            if ($request->jenis_pencairan === 'cash') {
                JanjiTemuPinjaman::create([
                    'id_pengajuan' => $pengajuan->id,
                    'lokasi_temu' => $request->lokasi_temu,
                    'nominal' => $request->nominal,
                    'tanggal_janji_temu' => $request->tanggal_janji_temu,
                    'waktu_janji_temu' => $request->waktu_janji_temu,
                    'keterangan' => $request->keterangan,
                ]);
            }

            return redirect()->route('nasabah.pinjaman.status-pengajuan')
                ->with('success', 'Pengajuan pinjaman berhasil dikirim!');
        } catch (\Exception $e) {
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
        $request->validate([
            'pin' => 'required|numeric|digits:6',
        ]);

        $user = auth()->user();
        
        if (!$user->pin) {
            return response()->json([
                'success' => false,
                'message' => 'PIN belum diatur. Silakan atur PIN terlebih dahulu.'
            ], 400);
        }

        $userPin = (int) $user->pin;
        $inputPin = (int) $request->pin;

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
     */
    private function hitungDenda($angsuran)
    {
        // Jika sudah lunas, tidak ada denda
        if ($angsuran->status_bayar === 'lunas') {
            return $angsuran->denda ?? 0;
        }

        // Jika denda sudah dihitung sebelumnya, gunakan nilai tersebut
        if ($angsuran->denda && $angsuran->denda > 0) {
            return $angsuran->denda;
        }

        // Hitung hari telat
        $hariTelat = now()->diffInDays($angsuran->tgl_jatuh_tempo, false);
        
        // Jika belum telat, tidak ada denda
        if ($hariTelat <= 0) {
            return 0;
        }

        $pinjaman = $angsuran->pinjaman;
        if (!$pinjaman) {
            return 0;
        }

        // Hitung denda berdasarkan persentase dari pinjaman
        $dendaPersen = $pinjaman->denda_persen ?? 0.02; // Default 2% per hari
        
        // Sisa tagihan yang belum dibayar
        $sisaTagihan = max(0, $angsuran->jumlah_tagihan - ($angsuran->jumlah_terbayar ?? 0));
        
        // Hitung denda: sisa tagihan * (denda persen * hari telat)
        $denda = $sisaTagihan * ($dendaPersen / 100) * $hariTelat;
        
        // Batasi denda maksimal 50% dari jumlah tagihan
        $dendaMax = $angsuran->jumlah_tagihan * 0.5;
        $denda = min($denda, $dendaMax);

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
