<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use App\Services\PettyCashConstants;
use App\Models\PengajuanPinjaman;
use App\Models\PinjamanH;
use App\Models\TempoPinjamanB;
use App\Models\TempoPinjamanM;
use App\Models\Nasabah;
use App\Models\PengajuanPembayaranPinjaman;
use App\Models\JanjiTemuPembayaranPinjaman;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\PettyCashSaldo;
use App\Models\PettyCashTransaksiNasabah;
use App\Models\JanjiTemuPinjaman;
use App\Models\BuktiFoto;
use App\Models\JnsLokasiPerusahaan;
use App\Models\MasterBungaPinjaman;
use App\Models\MasterDendaPinjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Helpers\IdGenerator;
use App\Models\NasabahNotification;

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
        // Hanya pengajuan via transfer (tunai/janji temu muncul di halaman Janji Temu Universal)
        $pengajuan_terbaru = PengajuanPinjaman::whereDoesntHave('pinjaman')
            ->where('jenis_pencairan', 'transfer')
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

        // Pembayaran terbaru (pengajuan pembayaran yang baru ditambahkan nasabah)
        $pembayaran_terbaru = PengajuanPembayaranPinjaman::with(['nasabah.user', 'pinjaman'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.pinjaman.index', compact(
            'stats',
            'pengajuan_terbaru',
            'pinjaman_aktif_terbaru',
            'angsuran_jatuh_tempo',
            'pembayaran_terbaru'
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
        $pengajuan = PengajuanPinjaman::with(['nasabah.user', 'nasabah.dataKtp', 'nasabah.dataRek', 'nasabah.pekerjaan', 'pinjaman'])
            ->findOrFail($id);

        // Get bunga dari master data berdasarkan durasi
        $masterBunga = MasterBungaPinjaman::getBungaByDurasi($pengajuan->durasi);
        $masterDenda = MasterDendaPinjaman::getDendaAktif();

        // Get admin petty cash balances
        $adminSaldoCash = PettyCashSaldo::getSaldoCash(Auth::id());
        $adminSaldoTransfer = PettyCashSaldo::getSaldoTransfer(Auth::id());
        $adminSaldo = (object) [
            'cash' => $adminSaldoCash,
            'transfer' => $adminSaldoTransfer
        ];

        return view('admin.pinjaman.detail-pengajuan', compact('pengajuan', 'masterBunga', 'masterDenda', 'adminSaldo'));
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
            $nominal = (float) $pengajuan->nominal;
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

            app(ActivityLogService::class)->logApprovePengajuanPinjaman($pengajuan->id, (float) $pengajuan->nominal, $pengajuan->nasabah->user->nama ?? 'N/A');

            NasabahNotification::notify(
                $pengajuan->id_anggota,
                'pinjaman',
                'Pengajuan pinjaman disetujui',
                'Pengajuan pinjaman Anda sebesar Rp ' . number_format((float) $pengajuan->nominal ?? 0, 0, ',', '.') . ' telah disetujui. Silakan menunggu proses pencairan.',
                route('nasabah.pinjaman.detail-pengajuan', $pengajuan->id),
                (string) $pengajuan->id,
                'pengajuan_pinjaman'
            );

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
            'metode_pencairan' => 'required|in:kas_utama,petty_cash,petty_tf',
            'bukti_transfer' => 'required|image|max:5120',
        ], [
            'bukti_transfer.required' => 'Bukti transaksi wajib diupload.',
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
            $metode = $request->metode_pencairan;
            $isPettyCash = in_array($metode, ['petty_cash', 'petty_tf']);
            
            if ($isPettyCash) {
                $tipe = $metode == 'petty_cash' ? 'cash' : 'transfer';
                if (!\App\Models\PettyCashSaldo::validatePenarikan(Auth::id(), (float) $pinjaman->jumlah_pinjam, $tipe)) {
                    throw new \Exception('Saldo ' . ucfirst($tipe) . ' Petty Cash tidak mencukupi untuk pencairan.');
                }
                
                // Create Transaction Record for Disbursement
                $pettyId = \App\Helpers\IdGenerator::generate('petty_cash_transaksi_nasabah', 'PC', 'AN', 'P', now());
                \App\Models\PettyCashTransaksiNasabah::create([
                    'id' => $pettyId,
                    'admin_id' => Auth::id(),
                    'nasabah_id' => $pengajuan->id_anggota,
                    'nominal' => $pinjaman->jumlah_pinjam,
                    'id_jns_transaksi' => PettyCashConstants::JNS_PNCR,
                    'id_jns_via' => ($tipe == 'cash' ? PettyCashConstants::VIA_CS : PettyCashConstants::VIA_TF),
                    'id_jns_fitur' => PettyCashConstants::FITUR_PINJAMAN,
                    'keterangan' => 'Pencairan Pinjaman #' . $pinjaman->id,
                    'ref_table' => PettyCashConstants::REF_PINJAMAN_H,
                    'ref_id' => $pinjaman->id,
                    'status' => 'approved',
                    'tgl_transaksi' => now(),
                ]);

                \App\Models\PettyCashSaldo::updateSaldo(Auth::id(), $tipe, -(float)$pinjaman->jumlah_pinjam, $pettyId, 'Pencairan Pinjaman', 'petty_cash_transaksi_nasabah');
            }

            $pinjaman->update([
                'tgl_pinjam' => $request->tgl_cair,
                'is_petty_cash' => $isPettyCash ? 1 : 0,
                'petty_cash_ref' => $isPettyCash ? $pinjaman->id : null,
                'metode_pencairan' => $metode,
            ]);

            // Generate jadwal angsuran
            $this->generateJadwalAngsuran($pinjaman);

            // Upload bukti transaksi dan simpan ke tbl_bukti_foto dengan kode PNCR
            $file = $request->file('bukti_transfer');
            $path = $file->store('bukti-pencairan-pinjaman', 'public');
            $idBuktiFoto = IdGenerator::generate('tbl_bukti_foto', 'P', 'TF', 'PNCR', $request->tgl_cair);
            BuktiFoto::create([
                'id' => $idBuktiFoto,
                'owner_id' => $pengajuan->id,
                'owner_fitur' => 'P',
                'owner_trans' => 'PNCR',
                'file_path' => $path,
                'keterangan' => 'Bukti transaksi pencairan pinjaman',
            ]);

            // Update status pengajuan menjadi '4' (Terlaksana/Tercair)
            $pengajuan->update([
                'status' => '4',
                'tgl_cair' => $request->tgl_cair,
            ]);

            DB::commit();

            app(ActivityLogService::class)->logCairkanPinjaman($pinjaman->id, $pinjaman->jumlah_pinjam, $pengajuan->nasabah->user->nama ?? 'N/A');

            NasabahNotification::notify(
                $pengajuan->id_anggota,
                'pinjaman',
                'Pinjaman telah dicairkan',
                'Pinjaman Anda sebesar Rp ' . number_format($pinjaman->jumlah_pinjam ?? 0, 0, ',', '.') . ' telah dicairkan. Dana telah ditransfer sesuai metode pencairan.',
                route('nasabah.pinjaman.detail-pinjaman', $pinjaman->id),
                (string) $pinjaman->id,
                'pinjaman'
            );

            return redirect()->route('admin.pinjaman.detail-pinjaman', $pinjaman->id)
                ->with('success', 'Pinjaman berhasil dicairkan dan jadwal angsuran telah dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Proses janji temu pinjaman: update tbl_janji_temu_pinjaman, lalu setujui + cairkan pengajuan (sama seperti flow pengajuan).
     * Dipanggil dari halaman Detail Janji Temu Pinjaman (bukan dari detail pengajuan).
     */
    public function prosesJanjiTemuPinjaman(Request $request, $id)
    {
        $request->validate([
            'tgl_cair' => 'required|date',
            'keterangan_admin' => 'nullable|string|max:500',
            'bukti_transfer' => 'nullable|image|max:5120',
        ]);

        $janjiTemu = JanjiTemuPinjaman::findOrFail($id);

        if ($janjiTemu->status == '2') {
            return redirect()->back()->with('error', 'Janji temu ini sudah diproses sebelumnya.');
        }

        if (!$janjiTemu->id_pengajuan) {
            return redirect()->back()->with('error', 'Janji temu ini belum terhubung ke pengajuan pinjaman.');
        }

        $pengajuan = PengajuanPinjaman::with('pinjaman')->find($janjiTemu->id_pengajuan);
        if (!$pengajuan) {
            return redirect()->back()->with('error', 'Data pengajuan tidak ditemukan.');
        }

        try {
            DB::beginTransaction();

            // 1. Update janji temu (keterangan + status selesai)
            $janjiTemu->update([
                'status' => '2',
                'keterangan_admin' => $request->keterangan_admin,
            ]);

            // 2. Bukti foto: simpan dengan owner = janji temu (mirip tabungan)
            if ($request->hasFile('bukti_transfer')) {
                $file = $request->file('bukti_transfer');
                $path = $file->store('bukti-pencairan-pinjaman', 'public');
                $idBuktiFoto = IdGenerator::generate('tbl_bukti_foto', 'P', 'TN', 'PNCR', $request->tgl_cair);
                BuktiFoto::create([
                    'id' => $idBuktiFoto,
                    'owner_id' => $janjiTemu->id,
                    'owner_fitur' => 'P',
                    'owner_trans' => 'PNCR',
                    'file_path' => $path,
                    'keterangan' => 'Bukti pencairan pinjaman (janji temu tunai)',
                ]);
            }

            // 3. Jika pengajuan masih pending (1): setujui dulu (buat pinjaman header)
            if ($pengajuan->status === '1') {
                $masterBunga = MasterBungaPinjaman::getBungaByDurasi($pengajuan->durasi);
                $masterDenda = MasterDendaPinjaman::getDendaAktif();
                if (!$masterBunga || !$masterDenda) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Master bunga/denda belum diatur.');
                }
                $nominal = (float) $pengajuan->nominal;
                $bungaPersen = $masterBunga->bunga_persen;
                $bungaRp = ($nominal * $bungaPersen) / 100;
                $kodeVia = 'TN';
                $idPinjaman = IdGenerator::generate('tbl_pinjaman_h', 'P', $kodeVia, 'DPNJM', now());
                PinjamanH::create([
                    'id' => $idPinjaman,
                    'id_anggota' => $pengajuan->id_anggota,
                    'id_pengajuan' => $pengajuan->id,
                    'jumlah_pinjam' => $nominal,
                    'lama_pinjam' => (int) $pengajuan->durasi,
                    'ags_bulan' => ($nominal + $bungaRp) / (int) $pengajuan->durasi,
                    'jenis' => 'bulanan',
                    'bunga' => $bungaPersen,
                    'bunga_rp' => $bungaRp,
                    'denda_persen' => $masterDenda->denda_persen,
                    'tgl_pinjam' => $request->tgl_cair,
                    'lunas' => 'belum',
                ]);
                $pengajuan->update([
                    'status' => '3',
                    'bunga_persen' => $bungaPersen,
                    'keterangan_admin' => $request->keterangan_admin,
                ]);
                $pengajuan->load('pinjaman');
            }

            $pinjaman = $pengajuan->pinjaman;
            if (!$pinjaman) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Data pinjaman belum dibuat.');
            }

            // 4. Cairkan: generate jadwal angsuran, update status 4
            if (TempoPinjamanB::where('pinjaman_id', $pinjaman->id)->exists()) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Pinjaman ini sudah dicairkan sebelumnya.');
            }

            $pinjaman->update(['tgl_pinjam' => $request->tgl_cair]);
            $this->generateJadwalAngsuran($pinjaman);
            $pengajuan->update([
                'status' => '4',
                'tgl_cair' => $request->tgl_cair,
            ]);

            DB::commit();

            return redirect()->route('admin.pinjaman.janji-temu.detail-pinjaman', $janjiTemu->id)
                ->with('success', 'Janji temu selesai. Pinjaman telah disetujui dan dicairkan; jadwal angsuran telah dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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

        app(ActivityLogService::class)->logRejectPengajuanPinjaman($pengajuan->id, (float) $pengajuan->nominal, $pengajuan->nasabah->user->nama ?? 'N/A', $request->keterangan_admin);

        NasabahNotification::notify(
            $pengajuan->id_anggota,
            'pinjaman',
            'Pengajuan pinjaman ditolak',
            'Pengajuan pinjaman Anda ditolak. ' . ($request->keterangan_admin ?? ''),
            route('nasabah.pinjaman.detail-pengajuan', $pengajuan->id),
            (string) $pengajuan->id,
            'pengajuan_pinjaman'
        );

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
        $query = PinjamanH::with(['nasabah.user', 'tempoBulanan', 'tempoMingguan'])
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

        // Bukti transfer untuk angsuran ini (jika sudah lunas)
        $buktiTransferAngsuran = collect();
        if ($angsuran->status_bayar === 'lunas') {
            $pengajuanBayar = PengajuanPembayaranPinjaman::where('tempo_id', $id)
                ->where('jenis_tempo', $jenis)
                ->whereIn('status', ['3', '4'])
                ->with('buktiFoto')
                ->get();
            $buktiTransferAngsuran = $pengajuanBayar->pluck('buktiFoto')->flatten()->filter(fn($b) => $b && ($b->file_path ?? null));
        }

        // Denda yang dihitung (untuk tampilan angsuran telat, konsisten dengan nasabah)
        $dendaDisplay = $this->hitungDenda($angsuran, $angsuran->pinjaman);

        return view('admin.pinjaman.detail-angsuran', compact('angsuran', 'jenis', 'buktiTransferAngsuran', 'dendaDisplay'));
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
        
        // Hitung jumlah hari telat (dari H+1 sampai sekarang) — dari tanggalMulaiDenda ke now
        $hariTelat = (int) $tanggalMulaiDenda->diffInDays(now(), true);
        if ($hariTelat <= 0) {
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
     * Pelunasan dipercepat (early payment).
     */
    public function pelunasanDipercepat(Request $request, $id)
    {
        // Authorization: Only Admin Utama can do pelunasan dipercepat
        if (!app(\App\Services\AdminPermissionService::class)->canPelunasanDipercepat(Auth::user())) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk fitur ini. Hanya Admin Utama yang dapat melakukan pelunasan dipercepat.'
            ], 403);
        }

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

        app(ActivityLogService::class)->logPelunasanDipercepat($pinjaman->id, $jumlahBayar, $pinjaman->nasabah->user->nama ?? 'N/A');

        return redirect()->route('admin.pinjaman.detail-pinjaman', $pinjaman->id)
            ->with('success', 'Pinjaman berhasil dilunasi dipercepat. Total pembayaran: Rp ' . number_format($jumlahBayar, 0, ',', '.'));
    }

    /**
     * Display list of pengajuan pembayaran pinjaman.
     */
    public function pembayaran(Request $request)
    {
        // Hanya tampilkan pengajuan pembayaran via transfer. Pembayaran tunai/janji temu dikelola dari Janji Temu Universal.
        $query = PengajuanPembayaranPinjaman::with(['nasabah.user', 'pinjaman.pengajuan', 'janjiTemu.lokasi'])
            ->where(function ($q) {
                $q->where('metode_pembayaran', 'transfer')->orWhereNull('metode_pembayaran');
            })
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
            'metode_penerimaan' => 'required|in:rek_koperasi,rek_admin,cash_admin',
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
            $jumlahTerbayarBaru = ($angsuran->jumlah_terbayar ?? 0) + (float) $pengajuan->nominal;
            
            $statusBayar = 'belum';
            $tglBayar = null;
            
            if ($jumlahTerbayarBaru >= $totalTagihanPlusDenda) {
                $statusBayar = 'lunas';
                $jumlahTerbayarBaru = $totalTagihanPlusDenda;
                $denda = 0;
                $tglBayar = now();
            } else {
                // Jika belum lunas, cek apakah telat (lewat jatuh tempo)
                $statusBayar = $angsuran->tgl_jatuh_tempo < now() ? 'telat' : 'belum';
                $tglBayar = $jumlahTerbayarBaru > 0 ? now() : null;
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

            // Proses Penerimaan Petty Cash (Jika bukan Rek Koperasi Utama)
            $metode = $request->metode_penerimaan;
            if (in_array($metode, ['rek_admin', 'cash_admin'])) {
                $pettyId = \App\Helpers\IdGenerator::generate('petty_cash_transaksi_nasabah', 'PC', 'AN', 'P', now());
                $tipeVia = $metode == 'cash_admin' ? PettyCashConstants::VIA_CS : PettyCashConstants::VIA_TF; 
                
                \App\Models\PettyCashTransaksiNasabah::create([
                    'id' => $pettyId,
                    'admin_id' => Auth::id(),
                    'nasabah_id' => $pengajuan->id_anggota,
                    'nominal' => (float) $pengajuan->nominal,
                    'id_jns_transaksi' => PettyCashConstants::JNS_PMB,
                    'id_jns_via' => $tipeVia,
                    'id_jns_fitur' => PettyCashConstants::FITUR_PINJAMAN,
                    'keterangan' => 'Angsuran Pinjaman #' . $pinjaman->id,
                    'ref_table' => PettyCashConstants::REF_PINJAMAN_D,
                    'ref_id' => $pengajuan->id,
                    'status' => 'approved',
                    'tgl_transaksi' => now(),
                ]);
                
                $tipeSaldo = $metode == 'cash_admin' ? 'cash' : 'transfer';
                \App\Models\PettyCashSaldo::updateSaldo(Auth::id(), $tipeSaldo, (float) $pengajuan->nominal, $pettyId, 'Angsuran Masuk', 'petty_cash_transaksi_nasabah');
            }

            // Update status pengajuan pembayaran menjadi disetujui
            $pengajuan->update([
                'status' => '3', // Disetujui
                'keterangan_admin' => $request->keterangan_admin,
                'tgl_pembayaran' => now(),
            ]);

            DB::commit();

            app(ActivityLogService::class)->logApprovePembayaranPinjaman($pengajuan->id, (float) $pengajuan->nominal, $pengajuan->nasabah->user->nama ?? 'N/A');

            NasabahNotification::notify(
                $pengajuan->id_anggota,
                'pinjaman_pembayaran',
                'Pembayaran angsuran disetujui',
                'Pembayaran angsuran Anda telah disetujui dan dicatat.',
                route('nasabah.pinjaman.detail-pembayaran', $pengajuan->id),
                (string) $pengajuan->id,
                'pengajuan_pembayaran_pinjaman'
            );

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

        app(ActivityLogService::class)->logRejectPembayaranPinjaman($pengajuan->id, (float) $pengajuan->nominal, $pengajuan->nasabah->user->nama ?? 'N/A', $request->keterangan_admin);

        NasabahNotification::notify(
            $pengajuan->id_anggota,
            'pinjaman_pembayaran',
            'Pembayaran angsuran ditolak',
            'Pengajuan pembayaran angsuran Anda ditolak. ' . ($request->keterangan_admin ?? ''),
            route('nasabah.pinjaman.detail-pembayaran', $pengajuan->id),
            (string) $pengajuan->id,
            'pengajuan_pembayaran_pinjaman'
        );

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
            $jumlahTerbayarBaru = ($angsuran->jumlah_terbayar ?? 0) + (float) $pengajuan->nominal;
            
            $statusBayar = 'belum';
            if ($jumlahTerbayarBaru >= $totalTagihanPlusDenda) {
                $statusBayar = 'lunas';
                $jumlahTerbayarBaru = $totalTagihanPlusDenda;
                $denda = 0;
            } else {
                $statusBayar = $angsuran->tgl_jatuh_tempo < now() ? 'telat' : 'belum';
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

            app(ActivityLogService::class)->logKonfirmasiPembayaranPinjaman($id, (float) $pengajuan->nominal, $pengajuan->nasabah->user->nama ?? 'N/A');

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

        // Tunai/janji temu: boleh upload langsung (status 1) atau setelah setujui (status 3)
        $isTunai = ($pengajuan->metode_pembayaran ?? '') === 'tunai' || (!$pengajuan->rekening_tujuan && $pengajuan->janjiTemu);
        if (!in_array($pengajuan->status, ['1', '3'])) {
            return redirect()->back()
                ->with('error', 'Status pembayaran tidak valid untuk upload bukti.');
        }
        if ($pengajuan->status === '1' && !$isTunai) {
            return redirect()->back()
                ->with('error', 'Pembayaran transfer harus disetujui terlebih dahulu.');
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

                $jumlahTerbayarBaru = ($angsuran->jumlah_terbayar ?? 0) + (float) $pengajuan->nominal;
                
                $statusBayar = 'belum';
                if ($jumlahTerbayarBaru >= $totalTagihanPlusDenda) {
                    $statusBayar = 'lunas';
                    $jumlahTerbayarBaru = $totalTagihanPlusDenda;
                    $denda = 0;
                } else {
                    $statusBayar = $angsuran->tgl_jatuh_tempo < now() ? 'telat' : 'belum';
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
                'setoran_kantor_id' => null, // Prepare for petty cash
            ]);

            // Cek transaksi petty cash (hindari duplikasi jika entah kenapa panggil 2x)
            $existingPc = \App\Models\PettyCashTransaksiNasabah::where('ref_table', 'tbl_pengajuan_pembayaran_pinjaman')
                ->where('ref_id', $pengajuan->id)
                ->first();

            if (!$existingPc) {
                // Catat ke Petty Cash Admin (Cash Fisik)
                $pettyId = \App\Helpers\IdGenerator::generate('petty_cash_transaksi_nasabah', 'PC', 'AN', 'P', now());
                
                \App\Models\PettyCashTransaksiNasabah::create([
                    'id' => $pettyId,
                    'admin_id' => Auth::id(),
                    'nasabah_id' => $pengajuan->id_anggota,
                    'nominal' => (float) $pengajuan->nominal,
                    'id_jns_transaksi' => PettyCashConstants::JNS_PMB,
                    'id_jns_via' => PettyCashConstants::VIA_CS,
                    'id_jns_fitur' => PettyCashConstants::FITUR_PINJAMAN, // Pinjaman
                    'keterangan' => 'Angsuran Pinjaman (Janji Temu) #' . $pengajuan->pinjaman->id,
                    'ref_table' => PettyCashConstants::REF_PINJAMAN_D,
                    'ref_id' => $pengajuan->id,
                    'status' => 'approved',
                    'tgl_transaksi' => now(),
                ]);
                
                \App\Models\PettyCashSaldo::updateSaldo(Auth::id(), 'cash', (float) $pengajuan->nominal, $pettyId, 'Angsuran Masuk (JT)', 'petty_cash_transaksi_nasabah');
            }

            // Update janji temu pembayaran jika ada (status selesai, keterangan_admin)
            $janjiTemu = $pengajuan->janjiTemu;
            if ($janjiTemu) {
                $janjiTemu->update([
                    'status' => '2',
                    'keterangan_admin' => $request->keterangan,
                ]);
            }

            DB::commit();

            $pengajuan->load('nasabah.user');
            app(ActivityLogService::class)->logProsesJanjiTemuPembayaranPinjaman($pengajuan->id, (float) $pengajuan->nominal, $pengajuan->nasabah->user->nama ?? 'N/A');

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
        // Authorization: Only Admin Utama can create manual pinjaman
        if (!app(\App\Services\AdminPermissionService::class)->canCrudPinjamanAktif(Auth::user())) {
            abort(403, 'Anda tidak memiliki akses untuk fitur ini. Hanya Admin Utama yang dapat membuat pinjaman manual.');
        }

        $nasabah = Nasabah::with('user')->get();
        $masterBunga = MasterBungaPinjaman::where('status_aktif', true)->orderBy('durasi_min')->get();
        
        return view('admin.pinjaman.create-pinjaman', compact('nasabah', 'masterBunga'));
    }

    /**
     * Store pinjaman baru (untuk yang janji temu/ketemu langsung).
     */
    public function storePinjaman(Request $request)
    {
        // Authorization: Only Admin Utama can create manual pinjaman
        if (!app(\App\Services\AdminPermissionService::class)->canCrudPinjamanAktif(Auth::user())) {
            abort(403, 'Anda tidak memiliki akses untuk fitur ini. Hanya Admin Utama yang dapat membuat pinjaman manual.');
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

            $nasabahRecord = Nasabah::with('user')->find($request->id_anggota);
            app(ActivityLogService::class)->logCreatePinjamanManual($pinjaman->id, $pinjaman->jumlah_pinjam, $nasabahRecord->user->nama ?? 'N/A');

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
        // Authorization: Only Admin Utama can edit manual pinjaman
        if (!app(\App\Services\AdminPermissionService::class)->canCrudPinjamanAktif(Auth::user())) {
            abort(403, 'Anda tidak memiliki akses untuk fitur ini. Hanya Admin Utama yang dapat mengedit pinjaman manual.');
        }

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
        // Authorization: Only Admin Utama can update manual pinjaman
        if (!app(\App\Services\AdminPermissionService::class)->canCrudPinjamanAktif(Auth::user())) {
            abort(403, 'Anda tidak memiliki akses untuk fitur ini. Hanya Admin Utama yang dapat mengupdate pinjaman manual.');
        }

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
        // Authorization: Only Admin Utama can delete manual pinjaman
        if (!app(\App\Services\AdminPermissionService::class)->canCrudPinjamanAktif(Auth::user())) {
            abort(403, 'Anda tidak memiliki akses untuk fitur ini. Hanya Admin Utama yang dapat menghapus pinjaman manual.');
        }

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
            $nasabahNama = $pinjaman->nasabah->user->nama ?? 'N/A';
            $pinjaman->delete();

            DB::commit();

            app(ActivityLogService::class)->logDeletePinjamanManual($id, $nasabahNama);

            return redirect()->route('admin.pinjaman.pinjaman-aktif')
                ->with('success', 'Pinjaman berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
