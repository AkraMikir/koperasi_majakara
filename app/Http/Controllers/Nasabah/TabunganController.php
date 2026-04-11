<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use App\Models\PengajuanTabungan;
use App\Models\PengajuanPenarikanTabungan;
use App\Models\JanjiTemuTabungan;
use App\Models\BuktiFotoTabungan;
use App\Models\JnsBank;
use App\Models\JnsLokasiPerusahaan;
use App\Models\Nasabah;
use App\Models\TransTabungan;
use App\Models\BuktiFoto;
use App\Models\User;
use App\Helpers\IdGenerator;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class TabunganController extends Controller
{
    /**
     * Show the tabungan dashboard.
     */
    public function index(Request $request)
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

        // Optimized Transaksi selection - Unique paginator 'page_trans'
        $transTabungans = TransTabungan::select('id', 'id_jns_transaksi', 'nominal', 'tgl_transaksi', 'id_jns_via')
            ->where('id_anggota', $idAnggota)
            ->with(['jnsTransaksi', 'jnsVia'])
            ->latest('tgl_transaksi')
            ->paginate(10, ['*'], 'page_trans')
            ->withQueryString();

        // Optimized Janji Temu selection - Unique paginator 'page_jt'
        $janjiTemuTabungans = JanjiTemuTabungan::select('id', 'nominal', 'status', 'tanggal_janji_temu', 'waktu_janji_temu', 'jenis', 'lokasi_temu')
            ->where('id_nasabah', $idAnggota)
            ->with('lokasi')
            ->latest('tanggal_janji_temu')
            ->paginate(10, ['*'], 'page_jt')
            ->withQueryString();

        // Optimized Pengajuan Setor (Transfer) - Unique paginator 'page_setor'
        $pengajuanSetors = PengajuanTabungan::where('id_anggota', $idAnggota)
            ->latest()
            ->paginate(10, ['*'], 'page_setor')
            ->withQueryString();

        // Optimized Pengajuan Tarik (Transfer) - Unique paginator 'page_tarik'
        $pengajuanTariks = PengajuanPenarikanTabungan::where('id_anggota', $idAnggota)
            ->where('metode_transfer', 'transfer')
            ->latest()
            ->paginate(10, ['*'], 'page_tarik')
            ->withQueryString();

        // Handle AJAX Pagination
        if ($request->ajax()) {
            if ($request->section === 'trans') {
                return view('nasabah.tabungan.partials._table_trans', compact('transTabungans'))->render();
            }
            if ($request->section === 'jt') {
                return view('nasabah.tabungan.partials._table_jt', compact('janjiTemuTabungans'))->render();
            }
            if ($request->section === 'setor') {
                return view('nasabah.tabungan.partials._table_setor', compact('pengajuanSetors'))->render();
            }
            if ($request->section === 'tarik') {
                return view('nasabah.tabungan.partials._table_tarik', compact('pengajuanTariks'))->render();
            }
        }

        return view('nasabah.tabungan.index', [
            'user' => Auth::user(),
            'tabunganInfo' => $tabunganInfo,
            'transTabungans' => $transTabungans,
            'janjiTemuTabungans' => $janjiTemuTabungans,
            'pengajuanSetors' => $pengajuanSetors,
            'pengajuanTariks' => $pengajuanTariks,
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
            ->whereHas('jnsTransaksi', function($q) {
                $q->where('kode', 'STR');
            })
            ->with(['jnsTransaksi', 'jnsVia'])
            ->latest('tgl_transaksi')
            ->take(10)
            ->get();

        // Get lokasi untuk janji temu
        $lokasi = JnsLokasiPerusahaan::where('status_aktif', true)->get();

        // Get data bank aktif
        $banks = JnsBank::where('status', 'aktif')->get();

        return view('nasabah.tabungan.nabung-sekarang', [
            'user' => Auth::user(),
            'riwayatTabungan' => $riwayatTabungan,
            'lokasi' => $lokasi,
            'banks' => $banks,
        ]);
    }

    /**
     * Halaman pengajuan transfer tidak dipakai; form sudah ada di Nabung Sekarang.
     * Redirect ke nabung-sekarang.
     */
    public function pengajuanTransfer()
    {
        return redirect()->route('nasabah.tabungan.nabung-sekarang');
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
            ->whereHas('jnsTransaksi', function($q) {
                $q->where('kode', 'PNR');
            })
            ->with(['jnsTransaksi', 'jnsVia'])
            ->latest('tgl_transaksi')
            ->take(10)
            ->get();

        // Get active office locations
        $lokasi = JnsLokasiPerusahaan::where('status_aktif', true)->get();

        // Data rekening nasabah untuk auto-fill form transfer (bank & no rekening)
        $rekeningNasabah = Nasabah::with('dataRek')->find($idAnggota)?->dataRek;

        return view('nasabah.tabungan.penarikan-tabungan', [
            'user' => Auth::user(),
            'tabunganInfo' => $tabunganInfo,
            'riwayatPenarikan' => $riwayatPenarikan,
            'lokasi' => $lokasi,
            'rekeningNasabah' => $rekeningNasabah,
        ]);
    }

    /**
     * Verify PIN before submit.
     */
    public function verifyPin(Request $request)
    {
        $request->validate([
            'pin' => 'required|numeric|digits:6',
        ]);

        /** @var User $user */
        $user = Auth::user();
        
        if (!$user->pin) {
            return response()->json([
                'success' => false,
                'message' => 'PIN belum diatur. Silakan atur PIN terlebih dahulu.'
            ], 400);
        }

        if ($user->pin != $request->pin) {
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
     * Submit pengajuan setoran tabungan.
     */
    public function submitSetoran(Request $request)
    {
        // Check authentication first
        if (Auth::user() === null) {
            return redirect()->route('login')
                ->with('error', 'Session Anda telah berakhir. Silakan login kembali.');
        }

        $request->validate([
            'pin' => 'required|numeric|digits:6',
            'nominal' => 'required|numeric|min:10000',
            'keterangan' => 'nullable|string|max:500',
            'bukti_foto.*' => 'required|image|max:5120',
        ]);

        // Verify PIN
        /** @var User $user */
        $user = Auth::user();
        
        // Check if user has PIN
        if (!$user->pin) {
            return redirect()->back()
                ->with('error', 'PIN belum diatur. Silakan atur PIN terlebih dahulu di profil Anda.')
                ->withInput($request->except('pin'));
        }

        // Convert both to integer for comparison (handles string/int mismatch)
        $userPin = (int) $user->pin;
        $inputPin = (int) $request->pin;

        if ($userPin !== $inputPin) {
            return redirect()->route('nasabah.tabungan.nabung-sekarang')
                ->with('error', 'PIN yang Anda masukkan salah!')
                ->withInput($request->except('pin'));
        }

        try {
            // Get nasabah ID from auth
            $idAnggota = $this->getIdAnggota();

            // This method is now only for transfer (fixed: use parentheses so ($request->metode ?? 'transfer') === 'transfer')
            $isTransfer = ($request->metode ?? 'transfer') === 'transfer';
            // #region agent log
            $logPath = base_path('.cursor/debug.log');
            if (!is_dir(dirname($logPath))) {
                @mkdir(dirname($logPath), 0755, true);
            }
            file_put_contents($logPath, json_encode(['id' => 'log_' . uniqid(), 'timestamp' => round(microtime(true) * 1000), 'location' => 'TabunganController(Nasabah):submitSetoran', 'message' => 'metode branch check', 'data' => ['request_metode' => $request->metode ?? null, 'isTransfer' => $isTransfer, 'branch_taken' => $isTransfer ? 'transfer' : 'other'], 'hypothesisId' => 'H1', 'runId' => 'post-fix']) . "\n", FILE_APPEND | LOCK_EX);
            // #endregion
            if ($isTransfer) {
                // Validate bukti foto exists
                if (!$request->hasFile('bukti_foto') || count($request->file('bukti_foto')) == 0) {
                    return redirect()->route('nasabah.tabungan.nabung-sekarang')
                        ->with('error', 'Minimal upload 1 bukti transfer')
                        ->withInput($request->except('pin'));
                }

                // Generate ID: Tabungan (T), Transfer (T), Setoran (STR)
                $idPengajuan = IdGenerator::generate('tbl_pengajuan_tabungan', 'T', 'T', 'STR');

                // Create pengajuan tabungan with nominal
                $pengajuan = PengajuanTabungan::create([
                    'id' => $idPengajuan,
                    'id_anggota' => $idAnggota,
                    'nominal' => $request->nominal,
                    // 'foto_bukti_tf' => 'transfer', // REMOVED
                    'keterangan' => $request->keterangan,
                    'status' => '1', // Pending
                ]);

                // Handle multiple bukti foto (hanya file, no nominal/keterangan)
                // id wajib diisi: tbl_bukti_foto pakai id string (bukan auto-increment)
                if ($request->hasFile('bukti_foto')) {
                    foreach ($request->file('bukti_foto') as $file) {
                        $path = $file->store('bukti_tabungan', 'public');
                        $idBuktiFoto = IdGenerator::generate('tbl_bukti_foto', 'T', 'T', 'STR');
                        BuktiFoto::create([
                            'id' => $idBuktiFoto,
                            'owner_id' => $idPengajuan,
                            'owner_fitur' => 'T',
                            'owner_trans' => 'STR',
                            'file_path' => $path,
                            'keterangan' => 'Bukti Transfer'
                        ]);
                    }
                }

                // Notifikasi untuk admin
                \App\Models\AdminNotification::notify(
                    'tabungan_setor',
                    'Pengajuan setoran tabungan baru',
                    'Nasabah mengajukan setoran transfer Rp ' . number_format((float) ($pengajuan->nominal ?? 0), 0, ',', '.'),
                    route('admin.tabungan.detail-pengajuan-setor', $pengajuan->id),
                    $pengajuan->id,
                    'pengajuan_tabungan'
                );

                app(ActivityLogService::class)->logSubmitSetoran($pengajuan->id, $pengajuan->nominal, 'transfer');

                return redirect()->route('nasabah.tabungan.status-pengajuan-setor')
                    ->with('success', 'Pengajuan setoran via transfer berhasil dikirim!');
            }

            return redirect()->route('nasabah.tabungan.nabung-sekarang')
                ->with('error', 'Metode tidak valid')
                ->withInput($request->except('pin'));
                
        } catch (\Illuminate\Auth\AuthenticationException $e) {
            return redirect()->route('login')
                ->with('error', 'Session Anda telah berakhir. Silakan login kembali.');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return redirect()->route('nasabah.dashboard')
                ->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->route('nasabah.tabungan.nabung-sekarang')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput($request->except('pin'));
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
        // Check authentication first
        if (Auth::user() === null) {
            return redirect()->route('login')
                ->with('error', 'Session Anda telah berakhir. Silakan login kembali.');
        }

        try {
            $validated = $request->validate([
                'pin' => 'required|numeric|digits:6',
                'nominal' => 'required|numeric|min:10000',
                'lokasi_temu' => 'required|exists:jns_lokasi_perusahaan,id',
                'tanggal_janji_temu' => 'required|date|after:today',
                'waktu_janji_temu' => 'required|date_format:H:i',
                'keterangan' => 'nullable|string|max:500',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('nasabah.tabungan.janji-temu', [
                'nominal' => $request->nominal ?? '',
                'keterangan' => $request->keterangan ?? '',
            ])
                ->withErrors($e->validator)
                ->withInput($request->except('pin'));
        }

        // Verify PIN
        /** @var User $user */
        $user = Auth::user();
        
        // Check if user has PIN
        if (!$user->pin) {
            return redirect()->route('nasabah.tabungan.janji-temu', [
                'nominal' => $request->nominal ?? '',
                'keterangan' => $request->keterangan ?? '',
            ])
                ->with('error', 'PIN belum diatur. Silakan atur PIN terlebih dahulu di profil Anda.')
                ->withInput($request->except('pin'));
        }

        // Convert both to integer for comparison (handles string/int mismatch)
        $userPin = (int) $user->pin;
        $inputPin = (int) $request->pin;

        if ($userPin !== $inputPin) {
            return redirect()->route('nasabah.tabungan.janji-temu', [
                'nominal' => $request->nominal ?? '',
                'keterangan' => $request->keterangan ?? '',
            ])
                ->with('error', 'PIN yang Anda masukkan salah!')
                ->withInput($request->except('pin'));
        }

        try {
            // Get ID anggota after PIN verification
            $idAnggota = $this->getIdAnggota();

            // Generate ID untuk janji temu
            // Format: DDMMYYYYNNNN + T + CS + JNJT (dengan sequence number untuk uniqueness)
            // Contoh: 04022026001TCSJNJT, 04022026002TCSJNJT
            $id = IdGenerator::generate('tbl_janji_temu_tabungan', 'T', 'CS', 'JNJT');
            
            // Parse dates
            $tanggalJanjiTemu = \Carbon\Carbon::parse($request->tanggal_janji_temu . ' ' . $request->waktu_janji_temu);
            $waktuJanjiTemu = \Carbon\Carbon::parse($request->waktu_janji_temu)->format('H:i:s');
            
            // Create janji temu
            JanjiTemuTabungan::create([
                'id' => $id,                    // ✅ Generated ID
                'id_nasabah' => $idAnggota,
                'lokasi_temu' => $request->lokasi_temu,
                'nominal' => $request->nominal,
                'tanggal_janji_temu' => $tanggalJanjiTemu,
                'waktu_janji_temu' => $waktuJanjiTemu,
                'keterangan' => $request->keterangan,
                'status' => '1',                // ✅ Default: Menunggu
            ]);

            app(ActivityLogService::class)->logSubmitJanjiTemuTabungan($id, $request->nominal, 'setoran', $request->tanggal_janji_temu);

            // Redirect ke status janji temu
            return redirect()->route('nasabah.tabungan.status-janji-temu')
                ->with('success', 'Janji temu berhasil dibuat!');
                
        } catch (\Illuminate\Auth\AuthenticationException $e) {
            return redirect()->route('login')
                ->with('error', 'Session Anda telah berakhir. Silakan login kembali.');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return redirect()->route('nasabah.dashboard')
                ->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->route('nasabah.tabungan.janji-temu', [
                'nominal' => $request->nominal ?? '',
                'keterangan' => $request->keterangan ?? '',
            ])
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput($request->except('pin'));
        }
    }

    /**
     * Submit pengajuan penarikan tabungan.
     */
    public function submitPenarikan(Request $request)
    {
        $request->validate([
            'pin' => 'required|numeric|digits:6',
            'metode' => 'required|in:tunai,transfer',
            'nominal' => 'required|numeric|min:10000',
            'keterangan' => 'nullable|string|max:500',
            'nama_bank' => 'nullable|required_if:metode,transfer|string|max:100',
            'no_rekening' => 'nullable|required_if:metode,transfer|string|max:50',
            'lokasi_temu' => 'nullable|required_if:metode,tunai|exists:jns_lokasi_perusahaan,id',
            'tanggal_janji_temu' => 'nullable|required_if:metode,tunai|date|after_or_equal:today',
            'waktu_janji_temu' => 'nullable|required_if:metode,tunai',
        ]);

        // Verify PIN
        /** @var User $user */
        $user = Auth::user();
        if (!$user->pin || (int)$user->pin !== (int)$request->pin) {
            return redirect()->back()
                ->with('error', 'PIN yang Anda masukkan salah!')
                ->withInput($request->except('pin'));
        }

        $idAnggota = $this->getIdAnggota();

        // Check saldo
        $saldo = $this->getSaldoNasabah($idAnggota);
        if ($saldo < $request->nominal) {
            return redirect()->back()
                ->with('error', 'Saldo tidak mencukupi!')
                ->withInput($request->except('pin'));
        }

        try {
            DB::beginTransaction();

            $kodeVia = $request->metode === 'transfer' ? 'TF' : 'TN';
            $idPengajuan = IdGenerator::generate('tbl_pengajuan_penarikan_tabungan', 'T', $kodeVia, 'PNR');

            // 1. Create pengajuan penarikan record (always, for history consistency)
            PengajuanPenarikanTabungan::create([
                'id' => $idPengajuan,
                'id_anggota' => $idAnggota,
                'tgl_pengajuan' => now(),
                'nominal' => $request->nominal,
                'metode_transfer' => $request->metode,
                'nama_bank' => $request->metode === 'transfer' ? $request->nama_bank : null,
                'no_rekening' => $request->metode === 'transfer' ? $request->no_rekening : null,
                'lokasi_temu' => $request->metode === 'tunai' ? $request->lokasi_temu : null,
                'tanggal_janji_temu' => $request->metode === 'tunai' ? $request->tanggal_janji_temu : null,
                'waktu_janji_temu' => $request->metode === 'tunai' ? $request->waktu_janji_temu : null,
                'keterangan' => $request->keterangan,
                'status' => '1', // Pending
            ]);

            // Notifikasi admin hanya untuk penarikan via transfer (tunai diproses lewat Janji Temu)
            if ($request->metode === 'transfer') {
                \App\Models\AdminNotification::notify(
                    'tabungan_tarik',
                    'Pengajuan penarikan tabungan (transfer)',
                    'Nasabah mengajukan penarikan transfer Rp ' . number_format($request->nominal, 0, ',', '.'),
                    route('admin.tabungan.detail-pengajuan-tarik', $idPengajuan),
                    $idPengajuan,
                    'pengajuan_penarikan_tabungan'
                );
            }

            // 2. If Tunai, also create JanjiTemuTabungan for Universal System
            if ($request->metode === 'tunai') {
                $idJanjiTemu = IdGenerator::generate('tbl_janji_temu_tabungan', 'T', 'CS', 'JNJT');
                
                JanjiTemuTabungan::create([
                    'id' => $idJanjiTemu,
                    'id_nasabah' => $idAnggota,
                    'lokasi_temu' => $request->lokasi_temu,
                    'jenis' => 'penarikan',  // ✅ Set jenis as penarikan
                    'nominal' => $request->nominal,
                    'tanggal_janji_temu' => $request->tanggal_janji_temu,
                    'waktu_janji_temu' => $request->waktu_janji_temu,
                    'keterangan' => $request->keterangan,
                    'status' => '1', // Menunggu
                ]);
            }

            DB::commit();

            app(ActivityLogService::class)->logSubmitPenarikan($idPengajuan, $request->nominal, $request->metode);

            $redirectRoute = $request->metode === 'tunai' 
                ? 'nasabah.tabungan.status-janji-temu' 
                : 'nasabah.tabungan.status-pengajuan-tarik';

            return redirect()->route($redirectRoute)
                ->with('success', 'Pengajuan penarikan berhasil dikirim!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput($request->except('pin'));
        }
    }

    /**
     * Show status pengajuan setoran.
     */
    public function statusPengajuanSetor()
    {
        $idAnggota = $this->getIdAnggota();
        
        $pengajuan = PengajuanTabungan::where('id_anggota', $idAnggota)
            ->with(['buktiFoto'])  // Removed janjiTemu relation
            ->latest()
            ->paginate(10);

        return view('nasabah.tabungan.status-pengajuan-setor', [
            'pengajuan' => $pengajuan,
        ]);
    }

    /**
     * Show status janji temu (setoran tunai) - hanya dari tbl_janji_temu_tabungan.
     */
    public function statusJanjiTemu()
    {
        $idAnggota = $this->getIdAnggota();
        
        $janjiTemu = JanjiTemuTabungan::where('id_nasabah', $idAnggota)
            ->with(['lokasi', 'transTabungan'])
            ->latest('tanggal_janji_temu')
            ->paginate(10);

        return view('nasabah.tabungan.status-janji-temu', [
            'janjiTemu' => $janjiTemu,
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
            ->with(['buktiFoto'])
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
            ->with(['pengajuanSetor.buktiFoto', 'pengajuanTarik', 'jnsTransaksi', 'jnsVia', 'janjiTemuTabungan.buktiFoto'])
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
        
        $janjiTemu = JanjiTemuTabungan::where('id_nasabah', $idAnggota)
            ->with(['lokasi', 'transTabungan'])
            ->findOrFail($id);

        $isPast = $janjiTemu->tanggal_janji_temu && $janjiTemu->tanggal_janji_temu->isPast();

        return view('nasabah.tabungan.detail-janji-temu', [
            'janjiTemu' => $janjiTemu,
            'isPast' => $isPast,
        ]);
    }

    /**
     * Get saldo nasabah.
     */
    private function getSaldoNasabah($idAnggota)
    {
        // Hitung dari trans_tabungan yang sudah ada
        $totalSetoran = TransTabungan::where('id_anggota', $idAnggota)
            ->whereHas('jnsTransaksi', function($q) { $q->where('kode', 'STR'); })
            ->sum('nominal') ?? 0;

        $totalPenarikan = TransTabungan::where('id_anggota', $idAnggota)
            ->whereHas('jnsTransaksi', function($q) { $q->where('kode', 'PNR'); })
            ->sum('nominal') ?? 0;

        // Tambahkan setoran dari pengajuan yang sudah approved tapi belum ada transaksi
        $pengajuanApproved = PengajuanTabungan::where('id_anggota', $idAnggota)
            ->where('status', '2') // Approved
            ->whereDoesntHave('transTabungan')
            ->get();

        $approvedNoTransSum = 0;
        foreach ($pengajuanApproved as $pengajuan) {
            $approvedNoTransSum += $pengajuan->nominal ?? 0;
            $totalSetoran += $pengajuan->nominal ?? 0;
        }
        $saldo = max(0, $totalSetoran - $totalPenarikan);
        // #region agent log
        $logPath = base_path('.cursor/debug.log');
        if (!is_dir(dirname($logPath))) {
            @mkdir(dirname($logPath), 0755, true);
        }
        file_put_contents($logPath, json_encode(['id' => 'log_' . uniqid(), 'timestamp' => round(microtime(true) * 1000), 'location' => 'TabunganController(Nasabah):getSaldoNasabah', 'message' => 'saldo calculation', 'data' => ['id_anggota' => $idAnggota, 'totalSetoran_trans' => $totalSetoran - $approvedNoTransSum, 'totalPenarikan_trans' => $totalPenarikan, 'approvedNoTrans_count' => $pengajuanApproved->count(), 'approvedNoTrans_sum' => $approvedNoTransSum, 'final_saldo' => $saldo], 'hypothesisId' => 'H2']) . "\n", FILE_APPEND | LOCK_EX);
        // #endregion
        return $saldo;
    }

    /**
     * Get ID anggota from authenticated user.
     */
    private function getIdAnggota()
    {
        /** @var User|null $user */
        $user = Auth::user();
        
        if (!$user) {
            // Don't use abort here as it causes redirect issues
            // Throw exception instead and handle in try-catch at caller
            throw new \Illuminate\Auth\AuthenticationException('Unauthenticated');
        }

        $nasabah = $user->nasabah;
        
        if (!$nasabah) {
            // Throw exception instead of abort
            throw new \Illuminate\Auth\Access\AuthorizationException('User tidak memiliki data nasabah');
        }

        return $nasabah->id;
    }
}

