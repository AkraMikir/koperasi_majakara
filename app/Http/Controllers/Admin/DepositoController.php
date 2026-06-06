<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanDeposito;
use App\Models\DepositoH;
use App\Models\DepositoPersiapanCair;
use App\Models\JnsTenorDeposito;
use App\Models\SukuBungaDeposito;
use App\Models\PaketDeposito;
use App\Models\Nasabah;
use App\Models\NasabahNotification;
use App\Models\TransTabungan;
use App\Models\TransDeposito;
use App\Models\PencairanDeposito;
use App\Models\PettyCashPenerimaan;
use App\Models\PettyCashOwnerTransaksi;
use App\Models\PettyCashSaldo;
use App\Helpers\IdGenerator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\PettyCashTransaksiNasabah;
use App\Services\PettyCashConstants;

class DepositoController extends Controller
{
    /**
     * Cek apakah admin dapat approve/reject deposito.
     */
    protected function checkDepositoPermission()
    {
        $user = auth()->user();
        if (!in_array($user->role, ['admin_utama', 'admin_operasional'])) {
            abort(403, 'Anda tidak memiliki akses halaman ini.');
        }
    }

    /**
     * Dashboard Deposito Admin
     */
    public function index()
    {
        $stats = [
            'pengajuan_pending'      => PengajuanDeposito::where('status', '1')->count(),
            'jatuh_tempo_bulan_ini'  => DepositoH::where('status', 'aktif')
                                        ->whereMonth('tgl_jatuh_tempo', now()->month)
                                        ->whereYear('tgl_jatuh_tempo', now()->year)->count(),
            'bunga_dibayar_bulan_ini'=> TransDeposito::where('jenis', 'pencairan_bunga')
                                        ->whereMonth('tgl_transaksi', now()->month)
                                        ->whereYear('tgl_transaksi', now()->year)->sum('nominal'),
            'total_deposito_aktif'   => DepositoH::where('status', 'aktif')->count(),
            'total_nominal_aktif'    => DepositoH::where('status', 'aktif')->sum('nominal_awal'),
            'pending_transfer'       => PengajuanDeposito::where('status', '1')->where('metode_setor', 'transfer')->count(),
            'pending_tabungan'       => PengajuanDeposito::where('status', '1')->where('metode_setor', 'saldo_tabungan')->count(),
            // Pencairan stats
            'pencairan_tf_pending'   => PencairanDeposito::where('jenis_pencairan', 'rek_nasabah')->where('status', 'pending')->count(),
            'pencairan_tab_pending'  => PencairanDeposito::where('jenis_pencairan', 'saldo_tabungan')->where('status', 'pending')->count(),
        ];

        $pengajuan_terbaru = PengajuanDeposito::with(['nasabah.user', 'tenor'])
            ->where('status', '1')->latest()->take(5)->get();

        $deposito_terbaru = DepositoH::with(['nasabah.user', 'tenor'])
            ->where('status', 'aktif')->latest()->take(5)->get();

        // Deposito jatuh tempo (pending pencairan)
        $jatuh_tempo = DepositoH::with(['nasabah.user', 'tenor'])
            ->where('status', 'aktif')
            ->where('tgl_jatuh_tempo', '<=', now())
            ->latest('tgl_jatuh_tempo')
            ->take(5)
            ->get();

        // Trend data for chart (6 months)
        $trend_data = [];
        $trend_labels = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $trend_labels[] = $date->translatedFormat('M Y');
            $trend_data[] = DepositoH::where('status', 'aktif')
                                     ->whereMonth('tgl_mulai', $date->month)
                                     ->whereYear('tgl_mulai', $date->year)
                                     ->sum('nominal_awal');
        }

        return view('admin.deposito.index', compact('stats', 'pengajuan_terbaru', 'deposito_terbaru', 'jatuh_tempo', 'trend_labels', 'trend_data'));
    }

    /**
     * Daftar semua pengajuan deposito
     */
    public function pengajuanList(Request $request)
    {
        $query = PengajuanDeposito::with(['nasabah.user', 'tenor'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', '1');
        }

        if ($request->filled('metode')) {
            $query->where('metode_setor', $request->metode);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('nasabah.user', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $pengajuan = $query->paginate(15)->withQueryString();

        return view('admin.deposito.pengajuan-list', compact('pengajuan'));
    }

    /**
     * Detail satu pengajuan deposito
     */
    public function detailPengajuan($id)
    {
        $pengajuan = PengajuanDeposito::with(['nasabah.user', 'nasabah.dataKtp', 'nasabah.dataRek', 'tenor'])
            ->findOrFail($id);

        return view('admin.deposito.detail-pengajuan', compact('pengajuan'));
    }

    /**
     * Approve pengajuan deposito
     */
    public function approve(Request $request, $id)
    {
        $this->checkDepositoPermission();

        try {
            DB::beginTransaction();

            $pengajuan = PengajuanDeposito::with(['nasabah', 'tenor'])->findOrFail($id);

            if ($pengajuan->status !== '1') {
                return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
            }

            $tenor  = $pengajuan->tenor;
            $nominal = (float) $pengajuan->nominal;

            // Cari suku bunga
            if ($pengajuan->paket_id && $pengajuan->paket) {
                // Gunakan suku bunga dari paket (PaketDeposito suku_bunga is in percentage, e.g. 5.25 for 5.25%)
                $bunga = (float) $pengajuan->paket->suku_bunga / 100;
            } else {
                // Fallback sistem lama
                $sukuBunga = SukuBungaDeposito::where('tenor_id', $tenor->id)
                    ->where('status', 'aktif')
                    ->where(fn($q) => $q->whereNull('min_nominal')->orWhere('min_nominal', '<=', $nominal))
                    ->where(fn($q) => $q->whereNull('max_nominal')->orWhere('max_nominal', '>=', $nominal))
                    ->orderBy('min_nominal')->first();

                $bungaFallback = [1 => 0.0375, 3 => 0.0450, 6 => 0.0525, 12 => 0.0600];
                $bunga = $sukuBunga ? (float) $sukuBunga->bunga : ($bungaFallback[$tenor->tenor_bulan] ?? 0.05);
            }

            if ($pengajuan->metode_setor === 'saldo_tabungan') {
                $nasabah = $pengajuan->nasabah;
                // Kecualikan pengajuan ini sendiri dari perhitungan hold,
                // karena yang diperiksa adalah saldo SEBELUM pengajuan ini memblokir.
                $saldo   = $this->getSaldoNasabah($nasabah->id, $pengajuan->id);
                if ($saldo < $nominal) {
                    DB::rollBack();
                    return back()->with('error', 'Saldo tabungan nasabah tidak mencukupi (Rp ' . number_format($saldo, 0, ',', '.') . ').');
                }

                $idVia   = DB::table('jns_via')->where('kode', 'TF')->value('id');
                $idTrans = DB::table('jns_transaksi')->where('kode', 'PNR')->value('id');
                $idTransaksi = IdGenerator::generate('trans_tabungan', 'T', 'TF', 'PNR');

                TransTabungan::create([
                    'id'                 => $idTransaksi,
                    'id_anggota'         => $nasabah->id,
                    'id_jns_via'         => $idVia,
                    'id_jns_transaksi'   => $idTrans,
                    'nominal'            => $nominal,
                    'keterangan'         => 'Penempatan Deposito #' . $pengajuan->id,
                    'tgl_transaksi'      => now(),
                    'admin_pengelola_id' => auth()->id(),
                ]);
            }

            // 🔥 INTEGRASI PETTY CASH / OWNER LEDGER (Hanya untuk Transfer Bank)
            if ($pengajuan->metode_setor === 'transfer') {
                $metodeBayar = $request->metode_bayar ?? 'transfer_koperasi';
                $pettyId = null;

                if ($metodeBayar === 'transfer_admin' || $metodeBayar === 'cash') {
                    // Simpan ke Petty Cash Admin
                    $pettyId = IdGenerator::generate('petty_cash_transaksi_nasabah', 'P', 'CS', 'STR');
                    $idTransStr = DB::table('jns_transaksi')->where('kode', 'STR')->value('id');

                    PettyCashTransaksiNasabah::create([
                        'id'               => $pettyId,
                        'admin_id'         => auth()->id(),
                        'nasabah_id'       => $pengajuan->id_nasabah,
                        'id_jns_transaksi' => $idTransStr,
                        'id_jns_via'       => ($metodeBayar === 'cash') ? PettyCashConstants::VIA_CS : PettyCashConstants::VIA_TF,
                        'id_jns_fitur'     => PettyCashConstants::FITUR_DEPOSITO,
                        'nominal'          => $nominal,
                        'status'           => 'approved',
                        'keterangan'       => 'Otomatis dari Pengajuan Deposito #' . $pengajuan->id,
                        'ref_table'        => PettyCashConstants::REF_DEPOSITO_P,
                        'ref_id'           => (string)$pengajuan->id,
                        'tgl_transaksi'    => now(),
                    ]);

                    $pettyType = ($metodeBayar === 'cash') ? 'cash' : 'transfer';
                    PettyCashSaldo::updateOrCreateSaldo(
                        auth()->id(),
                        'admin',
                        $nominal,
                        $pettyId,
                        'Setoran Deposito dari Pengajuan #' . $pengajuan->id,
                        'petty_cash_transaksi_nasabah',
                        $pettyType,
                        'deposito'
                    );
                } else {
                    // Simpan ke Koperasi Utama (Owner Wallet)
                    $owner = User::where('role', 'admin_utama')->first();
                    if ($owner) {
                        PettyCashOwnerTransaksi::create([
                            'id'              => IdGenerator::generate('petty_cash_owner_transaksi', 'PCOW', 'OW', 'TR'),
                            'user_id'         => $owner->id,
                            'tipe'            => 'terima_setoran',
                            'sumber'          => PettyCashConstants::SUMBER_DEPOSITO,
                            'nominal_cash'    => 0,
                            'nominal_tf'      => $nominal,
                            'keterangan'      => "Setoran Deposito Nasabah: " . ($pengajuan->nasabah->user->nama ?? '-') . " (#{$pengajuan->id})",
                            'bukti_foto_tf'   => $pengajuan->foto_bukti_tf,
                            'ref_id'          => (string)$pengajuan->id,
                            'ref_table'       => PettyCashConstants::REF_DEPOSITO_P,
                        ]);

                        PettyCashSaldo::buatMutasi(
                            $owner->id, 'owner', $nominal,
                            "Setoran Deposito (#{$pengajuan->id})",
                            $pengajuan->id, 'tbl_pengajuan_deposito', 'transfer',
                            \App\Services\PettyCashConstants::SUMBER_DEPOSITO
                        );
                    }
                }
            }

            $tglMulai      = now();
            $tglJatuhTempo = now()->addDays($tenor->tenor_hari);

            $nomorDeposito = 'DP' . now()->format('ymd') . str_pad(
                DepositoH::whereDate('created_at', today())->count() + 1,
                4, '0', STR_PAD_LEFT
            );

            $deposito = DepositoH::create([
                'id_pengajuan'    => $pengajuan->id,
                'id_nasabah'      => $pengajuan->id_nasabah,
                'paket_id'        => $pengajuan->paket_id,
                'nomor_deposito'  => $nomorDeposito,
                'nominal_awal'    => $nominal,
                'tenor_id'        => $pengajuan->tenor_id,
                'bunga'           => $bunga,
                'tgl_mulai'       => $tglMulai,
                'tgl_jatuh_tempo' => $tglJatuhTempo,
                'metode_pencairan'=> 'pencairan_ke_rekening',
                'status'          => 'aktif',
            ]);

            TransDeposito::create([
                'deposito_id'   => $deposito->id,
                'jenis'         => 'setor_awal',
                'nominal'       => $nominal,
                'keterangan'    => 'Setoran awal deposito - ' . ucfirst(str_replace('_', ' ', $pengajuan->metode_setor)),
                'tgl_transaksi' => now(),
            ]);

            $pengajuan->update([
                'status'        => '2',
                'catatan_admin' => $request->catatan_admin ?? 'Pengajuan disetujui',
                'approved_by'   => auth()->id(),
            ]);

            DB::commit();

            app(\App\Services\ActivityLogService::class)->logApprovePengajuanDeposito((string)$pengajuan->id, (float)$nominal, $pengajuan->nasabah->user->nama ?? 'N/A');

            NasabahNotification::notify(
                $pengajuan->id_nasabah, 'deposito',
                'Pengajuan Deposito Disetujui',
                'Deposito Anda sebesar Rp ' . number_format($nominal, 0, ',', '.') . ' (' . $tenor->tenor_bulan . ' bulan) telah aktif! No. ' . $nomorDeposito,
                route('nasabah.deposito.detail', $deposito->id),
                (string) $pengajuan->id, 'pengajuan_deposito'
            );

            return redirect()->route('admin.deposito.pengajuan-list')
                ->with('success', 'Pengajuan deposito berhasil disetujui. Nomor Deposito: ' . $nomorDeposito);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Admin approve deposito error', ['id' => $id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Reject pengajuan deposito
     */
    public function reject(Request $request, $id)
    {
        $this->checkDepositoPermission();

        $request->validate(['catatan_admin' => 'required|string|max:500']);

        $pengajuan = PengajuanDeposito::with('nasabah.user', 'tenor')->findOrFail($id);

        if ($pengajuan->status !== '1') {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $pengajuan->update(['status' => '3', 'catatan_admin' => $request->catatan_admin]);

        app(\App\Services\ActivityLogService::class)->logRejectPengajuanDeposito((string)$pengajuan->id, (float)$pengajuan->nominal, $pengajuan->nasabah->user->nama ?? 'N/A', $request->catatan_admin);

        NasabahNotification::notify(
            $pengajuan->id_nasabah, 'deposito',
            'Pengajuan Deposito Ditolak',
            'Pengajuan deposito Anda ditolak. ' . $request->catatan_admin,
            route('nasabah.deposito.status-pengajuan', $pengajuan->id),
            (string) $pengajuan->id, 'pengajuan_deposito'
        );

        return redirect()->route('admin.deposito.pengajuan-list')
            ->with('success', 'Pengajuan deposito telah ditolak.');
    }

    /**
     * Daftar semua deposito
     */
    public function depositoList(Request $request)
    {
        $query = DepositoH::with(['nasabah.user', 'tenor'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_deposito', 'like', "%{$search}%")
                  ->orWhereHas('nasabah.user', fn($q2) => $q2->where('nama', 'like', "%{$search}%"));
            });
        }

        $depositos = $query->paginate(15)->withQueryString();

        return view('admin.deposito.deposito-list', compact('depositos'));
    }

    /**
     * Export daftar deposito ke PDF
     */
    public function exportPdf(Request $request)
    {
        $this->checkDepositoPermission();

        $query = DepositoH::with(['nasabah.user', 'tenor'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $depositos = $query->get();

        $pdf = Pdf::loadView('admin.deposito.export-pdf', compact('depositos'));
        return $pdf->download('laporan-deposito-' . now()->format('Ymd-Hi') . '.pdf');
    }

    /**
     * Detail deposito aktif
     */
    public function depositoDetail($id)
    {
        $deposito = DepositoH::with(['nasabah.user', 'tenor', 'transDeposito', 'pencairan', 'persiapanCair'])
            ->findOrFail($id);

        $admins = [];
        if (auth()->user()->role === 'admin_utama') {
            $admins = \App\Models\User::where('role', 'admin_operasional')->get();
        }

        return view('admin.deposito.deposito-detail', compact('deposito', 'admins'));
    }

    /* ══════════════════════════════════════════════════════════
     *  PENCAIRAN – Transfer (TF ke Rekening)
     * ══════════════════════════════════════════════════════════ */

    /**
     * Daftar request pencairan via TF
     */
    public function pencairanTfIndex(Request $request)
    {
        $this->checkDepositoPermission();

        $query = PencairanDeposito::with(['deposito.tenor', 'nasabah.user', 'nasabah.dataRek'])
            ->where('jenis_pencairan', 'rek_nasabah')
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->whereHas('nasabah.user', fn($q2) => $q2->where('nama', 'like', "%$s%"))
                  ->orWhereHas('deposito', fn($q2) => $q2->where('nomor_deposito', 'like', "%$s%"));
            });
        }

        $pencairans = $query->paginate(15)->withQueryString();
        $pendingCount = PencairanDeposito::where('jenis_pencairan', 'rek_nasabah')->where('status', 'pending')->count();

        return view('admin.deposito.pencairan-tf', compact('pencairans', 'pendingCount'));
    }

    /**
     * Form proses pencairan TF (GET)
     */
    public function pencairanTfFormShow($id)
    {
        $this->checkDepositoPermission();

        $pencairan = PencairanDeposito::with(['deposito.tenor', 'nasabah.user', 'nasabah.dataRek'])
            ->where('jenis_pencairan', 'rek_nasabah')
            ->findOrFail($id);

        if ($pencairan->isSelesai()) {
            return redirect()->route('admin.deposito.pencairan-tf.index')
                ->with('error', 'Pencairan ini sudah selesai diproses.');
        }

        $admins = User::where('role', 'admin_operasional')->get();
        $biayaTransfer = \App\Models\BiayaTransfer::where('is_active', true)->get();
        $saldoTabunganNasabah = app(\App\Services\BankAccessService::class)->getSaldoTabungan($pencairan->id_nasabah);
        $adminSaldoTransfer = PettyCashSaldo::getSaldo(Auth::id(), 'admin', 'transfer', 'other');

        return view('admin.deposito.pencairan-tf-form', compact('pencairan', 'admins', 'biayaTransfer', 'saldoTabunganNasabah', 'adminSaldoTransfer'));
    }

    /**
     * Proses pencairan TF (POST)
     */
    public function pencairanTfProses(Request $request, $id)
    {
        $this->checkDepositoPermission();

        $request->validate([
            'admin_id'      => 'required|exists:users,id',
            'nominal_akhir' => 'required|numeric|min:1',
            'catatan'       => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $pencairan = PencairanDeposito::with(['deposito', 'nasabah'])->findOrFail($id);

            if (!$pencairan->isTf()) {
                return back()->with('error', 'Jenis pencairan ini bukan Transfer Rekening.');
            }
            if (!$pencairan->isPending()) {
                return back()->with('error', 'Pencairan sudah diproses sebelumnya.');
            }

            $nominal = (float) $request->nominal_akhir;
            $ownerId = Auth::id();
            $adminId = $request->admin_id;
            $nomorDep = $pencairan->deposito->nomor_deposito;

            // Validasi saldo Owner (transfer) mencukupi
            $saldoTfOwner = PettyCashSaldo::getSaldo($ownerId, 'owner', 'transfer');
            if ($saldoTfOwner < $nominal) {
                return back()->with('error',
                    'Saldo Transfer Owner tidak mencukupi. Tersedia: Rp ' . number_format($saldoTfOwner, 0, ',', '.') .
                    ', Dibutuhkan: Rp ' . number_format($nominal, 0, ',', '.')
                );
            }

            $penerimaanId = IdGenerator::generate('petty_cash_penerimaan', 'PCP', 'OW', 'KRM');

            // 1. Buat PettyCashPenerimaan (Owner -> Admin, sumber=deposito, tipe=transfer untuk dikirim ke nasabah)
            PettyCashPenerimaan::create([
                'id'          => $penerimaanId,
                'owner_id'    => $ownerId,
                'admin_id'    => $adminId,
                'sumber'      => 'deposito',
                'nominal_tf'  => $nominal,
                'nominal_cash'=> 0,
                'keterangan'  => 'Dana Pencairan Deposito ' . $nomorDep . ' (TF Rekening) untuk dikirim ke nasabah',
                'status'      => 'pending',
                'ref_id'      => (string) $pencairan->id,
            ]);

            // Transfer Koperasi: Mutasi Saldo Owner (TF - DEPOSITO) berkurang
            PettyCashSaldo::buatMutasi(
                $ownerId, 'owner', -$nominal,
                "Pencairan Deposito (Transfer) #{$pencairan->id}",
                $pencairan->id, 'tbl_pencairan_deposito', 'transfer',
                \App\Services\PettyCashConstants::SUMBER_DEPOSITO
            );

            // 3. Catat di PettyCashOwnerTransaksi
            PettyCashOwnerTransaksi::create([
                'id'           => IdGenerator::generate('petty_cash_owner_transaksi', 'PCOW', 'OW', 'TR'),
                'user_id'      => $ownerId,
                'tipe'         => 'kirim_admin_hold',
                'sumber'       => 'deposito',
                'nominal_tf'   => $nominal,
                'nominal_cash' => 0,
                'keterangan'   => 'HOLD: Kirim dana TF ke Admin untuk Pencairan Deposito ' . $nomorDep,
                'ref_id'       => $penerimaanId,
                'ref_table'    => 'petty_cash_penerimaan',
            ]);

            // 4. Update status pencairan record
            $pencairan->update([
                'nominal_akhir' => $nominal,
                'catatan'       => $request->catatan,
                'status'        => 'diproses', // Menunggu Admin terima dana
            ]);

            // 5. Sinkronisasi deposito_persiapan_cair
            DepositoPersiapanCair::where('deposito_id', $pencairan->deposito_id)
                ->whereIn('status', ['tentatif', 'diproses'])
                ->update(['status' => 'diproses', 'pencairan_id' => $pencairan->id]);

            DB::commit();

            return redirect()->route('admin.deposito.pencairan-tf.index')
                ->with('success', 'Dana pencairan telah dikirim ke Admin. Pencairan akan selesai setelah Admin mengkonfirmasi transfer ke nasabah.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Inisiasi pencairan TF error', ['id' => $id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function selesaikanPencairanTf(Request $request, $id)
    {
        $this->checkDepositoPermission();

        $request->validate([
            'foto_bukti_tf' => 'required|image|max:5120',
            'nominal_akhir' => 'required|numeric|min:1',
            'catatan'       => 'nullable|string|max:500',
            'bank_pengirim' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $pencairan = PencairanDeposito::with(['deposito', 'nasabah'])->findOrFail($id);

            if (!in_array($pencairan->status, ['pending', 'diproses'])) {
                return back()->with('error', 'Pencairan tidak dalam status valid untuk diselesaikan.');
            }

            // Direct Approval check
            $isDirect = ($pencairan->status === 'pending');
            if ($isDirect && auth()->user()->role !== 'admin_operasional') {
                return back()->with('error', 'Hanya Admin Operasional yang dapat melakukan persetujuan langsung.');
            }

            $nominal = (float) $request->nominal_akhir;
            $adminId = auth()->id();

            // Cek saldo Admin (transfer) mencukupi dari MODAL AWAL (Rule #1)
            $saldoTfAdmin = PettyCashSaldo::getSaldo($adminId, 'admin', 'transfer', 'other');
            if ($saldoTfAdmin < $nominal) {
                return back()->with('error', 'Saldo Transfer MODAL AWAL Anda tidak mencukupi untuk melakukan transfer ini.');
            }

            // 1. Upload foto bukti TF
            $fotoPath = $request->file('foto_bukti_tf')->store('deposito/bukti-tf-pencairan', 'public');

            // 2. Potong saldo Petty Cash Admin (MODAL AWAL) - Rule #1
            // Ini MENGURANGI saldo Admin. Reimbursement dilakukan terpisah oleh Owner (Rule #3).
            PettyCashSaldo::buatMutasi(
                $adminId, 'admin', -$nominal,
                'Pencairan Deposito ' . $pencairan->deposito->nomor_deposito . ' (TF ke Nasabah)',
                (string) $pencairan->id, 'tbl_pencairan_deposito', 'transfer', 'deposito'
            );

            // 🔥 Catat ke PettyCashTransaksiNasabah (pengeluaran ke nasabah - mencegah duplikasi)
            $existingPctf = PettyCashTransaksiNasabah::where('ref_table', 'tbl_pencairan_deposito')
                ->where('ref_id', (string) $pencairan->id)
                ->first();
            if (!$existingPctf) {
                PettyCashTransaksiNasabah::create([
                    'id'               => IdGenerator::generate('petty_cash_transaksi_nasabah', 'PC', 'DEP', 'TF'),
                    'admin_id'         => $adminId,
                    'nasabah_id'       => $pencairan->id_nasabah,
                    'id_jns_transaksi' => PettyCashConstants::JNS_PNCR,
                    'id_jns_via'       => PettyCashConstants::VIA_TF,
                    'id_jns_fitur'     => PettyCashConstants::FITUR_DEPOSITO,
                    'nominal'          => $nominal,
                    'status'           => 'approved',
                    'keterangan'       => 'Pencairan Deposito ' . $pencairan->deposito->nomor_deposito . ' → Rek Nasabah',
                    'ref_table'        => 'tbl_pencairan_deposito',
                    'ref_id'           => (string) $pencairan->id,
                    'tgl_transaksi'    => now(),
                ]);
            }

            // 3. Record di trans_deposito
            TransDeposito::create([
                'deposito_id'   => $pencairan->deposito_id,
                'jenis'         => 'pencairan',
                'nominal'       => $nominal,
                'keterangan'    => 'Pencairan TF ke Nasabah',
                'tgl_transaksi' => now(),
            ]);

            // 🔥 INTEGRASI BIAYA TRANSFER ANTARBANK
            $bankService = app(\App\Services\BankAccessService::class);
            $namaBank = $bankService->getNamaBank($pencairan->deposito->id_nasabah);
            $bankPengirim = $request->input('bank_pengirim', 'BCA');
            $biayaTransfer = 0;
            
            if ($namaBank && !$bankService->isBcaUser($pencairan->deposito->id_nasabah)) {
                $potong = $bankService->potongBiayaTransfer(
                    $pencairan->deposito->id_nasabah,
                    $namaBank,
                    'Pencairan Deposito ' . $pencairan->deposito->nomor_deposito . ' (TF)',
                    $adminId,
                    $bankPengirim
                );
                
                if (!$potong['success']) {
                    throw new \Exception($potong['message']);
                }
                $biayaTransfer = $potong['biaya'] ?? 0;
            }

            // 4. Update status pencairan
            $pencairan->update([
                'nominal_akhir' => $nominal,
                'foto_bukti_tf' => $fotoPath,
                'status'        => 'selesai',
                'approved_by'   => $adminId,
                'bank_pengirim' => $bankPengirim,
                'biaya_transfer' => $biayaTransfer,
            ]);

            // 5. Update status deposito → dicairkan atau ditutup jika is_cancel
            $statusDep = $pencairan->is_cancel ? 'ditutup' : 'dicairkan';
            $pencairan->deposito->update(['status' => $statusDep]);

            // 6. Sinkronisasi persiapan cair
            DepositoPersiapanCair::where('deposito_id', $pencairan->deposito_id)
                ->update(['status' => 'selesai']);

            DB::commit();

            app(\App\Services\ActivityLogService::class)->logPencairanDeposito((string)$pencairan->deposito_id, (float)$nominal, $pencairan->nasabah->user->nama ?? 'N/A');

            return redirect()->route('admin.deposito.pencairan-tf.index')
                ->with('success', 'Pencairan deposito berhasil diselesaikan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Selesaikan pencairan TF error', ['id' => $id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    /* ══════════════════════════════════════════════════════════
     *  PENCAIRAN – Tabungan (transfer langsung ke saldo tab)
     * ══════════════════════════════════════════════════════════ */

    /**
     * Daftar request pencairan ke Tabungan
     */
    public function pencairanTabunganIndex(Request $request)
    {
        $this->checkDepositoPermission();

        $query = PencairanDeposito::with(['deposito.tenor', 'nasabah.user'])
            ->where('jenis_pencairan', 'saldo_tabungan')
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->whereHas('nasabah.user', fn($q2) => $q2->where('nama', 'like', "%$s%"))
                  ->orWhereHas('deposito', fn($q2) => $q2->where('nomor_deposito', 'like', "%$s%"));
            });
        }

        $pencairans = $query->paginate(15)->withQueryString();
        $pendingCount = PencairanDeposito::where('jenis_pencairan', 'saldo_tabungan')->where('status', 'pending')->count();
        $admins = User::where('role', 'admin_operasional')->get();
        $adminSaldoTransfer = PettyCashSaldo::getSaldo(Auth::id(), 'admin', 'transfer', 'other');

        return view('admin.deposito.pencairan-tabungan', compact('pencairans', 'pendingCount', 'admins', 'adminSaldoTransfer'));
    }

    /**
     * [Owner] Inisiasi pencairan deposito ke Tabungan — kirim dana ke Admin Operasional.
     * POST /admin/deposito/pencairan-tabungan/{id}/proses
     */
    public function pencairanTabunganProses(Request $request, $id)
    {
        $this->checkDepositoPermission();

        $request->validate([
            'admin_id' => 'required|exists:users,id',
            'catatan'  => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $pencairan = PencairanDeposito::with(['deposito', 'nasabah'])->findOrFail($id);

            if (!$pencairan->isTabungan()) {
                return back()->with('error', 'Jenis pencairan ini bukan Saldo Tabungan.');
            }
            if (!$pencairan->isPending()) {
                return back()->with('error', 'Pencairan sudah diproses sebelumnya.');
            }

            $nominal  = (float) $pencairan->nominal_akhir;
            $ownerId  = Auth::id();
            $adminId  = $request->admin_id;
            $nomorDep = $pencairan->deposito->nomor_deposito;

            // Validasi saldo Owner (transfer/internal) mencukupi
            $saldoTfOwner = PettyCashSaldo::getSaldo($ownerId, 'owner', 'transfer');
            if ($saldoTfOwner < $nominal) {
                return back()->with('error', 'Saldo Transfer/Internal Owner tidak mencukupi untuk membiayai pencairan tabungan ini.');
            }

            $penerimaanId = IdGenerator::generate('petty_cash_penerimaan', 'PCP', 'OW', 'KRM');

            // 1. Buat PettyCashPenerimaan (Owner -> Admin, sumber=deposito, tipe=transfer untuk virtual)
            PettyCashPenerimaan::create([
                'id'          => $penerimaanId,
                'owner_id'    => $ownerId,
                'admin_id'    => $adminId,
                'sumber'      => 'deposito',
                'nominal_tf'  => $nominal,
                'nominal_cash'=> 0,
                'keterangan'  => 'Dana Pencairan Deposito ' . $nomorDep . ' (ke Tabungan) untuk dikelola Admin',
                'status'      => 'pending',
                'ref_id'      => (string) $pencairan->id,
            ]);

            // 2. Hold saldo Owner
            PettyCashSaldo::buatMutasi(
                $ownerId, 'owner', -$nominal,
                'HOLD: Dana Pencairan Deposito ' . $nomorDep . ' (Tabungan) ke Admin',
                $penerimaanId, 'petty_cash_penerimaan', 'transfer'
            );

            // 3. Catat di PettyCashOwnerTransaksi
            PettyCashOwnerTransaksi::create([
                'id'           => IdGenerator::generate('petty_cash_owner_transaksi', 'PCOW', 'OW', 'TR'),
                'user_id'      => $ownerId,
                'tipe'         => 'kirim_admin_hold',
                'sumber'       => 'deposito',
                'nominal_tf'   => $nominal,
                'nominal_cash' => 0,
                'keterangan'   => 'HOLD: Kirim dana virtual ke Admin untuk Pencairan Deposito ' . $nomorDep . ' ke Tabungan',
                'ref_id'       => $penerimaanId,
                'ref_table'    => 'petty_cash_penerimaan',
            ]);

            // 4. Update status pencairan
            $pencairan->update([
                'catatan' => $request->catatan,
                'status'  => 'diproses',
            ]);

            DB::commit();

            return redirect()->route('admin.deposito.pencairan-tabungan.index')
                ->with('success', 'Pencairan sedang diproses. Menunggu Admin menerima dana dan menambahkannya ke tabungan nasabah.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Inisiasi pencairan tabungan error', ['id' => $id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * [Admin] Finalisasi pencairan ke Tabungan (POST)
     */
    public function selesaikanPencairanTabungan(Request $request, $id)
    {
        $this->checkDepositoPermission();

        $request->validate([
            'nominal_akhir' => 'required|numeric|min:1',
            'foto_bukti_tf' => 'nullable|image|max:5120',
        ]);

        try {
            DB::beginTransaction();

            $pencairan = PencairanDeposito::with(['deposito.tenor', 'nasabah'])->findOrFail($id);

            if (!in_array($pencairan->status, ['pending', 'diproses'])) {
                return back()->with('error', 'Pencairan tidak dalam status valid untuk diselesaikan.');
            }

            $isDirect = ($pencairan->status === 'pending');
            if ($isDirect && auth()->user()->role !== 'admin_operasional') {
                return back()->with('error', 'Hanya Admin Operasional yang dapat melakukan persetujuan langsung.');
            }

            $nominal = (float) $request->nominal_akhir;
            $adminId = auth()->id();

            // Cek saldo Admin (transfer) mencukupi dari MODAL AWAL (Rule #1)
            $saldoTfAdmin = PettyCashSaldo::getSaldo($adminId, 'admin', 'transfer', 'other');
            if ($saldoTfAdmin < $nominal) {
                return back()->with('error', 'Saldo Transfer MODAL AWAL Anda tidak mencukupi.');
            }

            // 0. Handle Foto Bukti
            $fotoPath = $pencairan->foto_bukti_tf;
            if ($request->hasFile('foto_bukti_tf')) {
                $fotoPath = $request->file('foto_bukti_tf')->store('deposito/bukti-tabungan', 'public');
            }

            // 1. Buat TransTabungan (STR)
            $idVia   = DB::table('jns_via')->where('kode', 'TF')->value('id');
            $idTrans = DB::table('jns_transaksi')->where('kode', 'STR')->value('id');
            $idTransaksi = IdGenerator::generate('trans_tabungan', 'T', 'TF', 'STR');

            TransTabungan::create([
                'id'                 => $idTransaksi,
                'id_anggota'         => $pencairan->id_nasabah,
                'id_jns_via'         => $idVia,
                'id_jns_transaksi'   => $idTrans,
                'nominal'            => $nominal,
                'keterangan'         => 'Pencairan Deposito ' . $pencairan->deposito->nomor_deposito . ' ke Tabungan',
                'tgl_transaksi'      => now(),
                'admin_pengelola_id' => $adminId,
            ]);

            // 2. Potong saldo Admin (MODAL AWAL) - Rule #1
            // Ini MENGURANGI saldo. TopUp dilakukan terpisah oleh Owner (Rule #3).
            PettyCashSaldo::buatMutasi(
                $adminId, 'admin', -$nominal,
                'Pencairan Deposito ' . $pencairan->deposito->nomor_deposito . ' → Tabungan Nasabah',
                (string) $pencairan->id, 'tbl_pencairan_deposito', 'transfer', 'deposito'
            );

            // 🔥 Catat ke PettyCashTransaksiNasabah (pengeluaran ke tabungan nasabah - mencegah duplikasi)
            $existingPctab = PettyCashTransaksiNasabah::where('ref_table', 'tbl_pencairan_deposito')
                ->where('ref_id', (string) $pencairan->id)
                ->first();
            if (!$existingPctab) {
                PettyCashTransaksiNasabah::create([
                    'id'               => IdGenerator::generate('petty_cash_transaksi_nasabah', 'PC', 'DEP', 'TB'),
                    'admin_id'         => $adminId,
                    'nasabah_id'       => $pencairan->id_nasabah,
                    'id_jns_transaksi' => PettyCashConstants::JNS_PNCR,
                    'id_jns_via'       => PettyCashConstants::VIA_TF,
                    'id_jns_fitur'     => PettyCashConstants::FITUR_DEPOSITO,
                    'nominal'          => $nominal,
                    'status'           => 'approved',
                    'keterangan'       => 'Pencairan Deposito ' . $pencairan->deposito->nomor_deposito . ' → Saldo Tabungan',
                    'ref_table'        => 'tbl_pencairan_deposito',
                    'ref_id'           => (string) $pencairan->id,
                    'tgl_transaksi'    => now(),
                ]);
            }

            // 3. Record di trans_deposito
            TransDeposito::create([
                'deposito_id'   => $pencairan->deposito_id,
                'jenis'         => 'pencairan',
                'nominal'       => $nominal,
                'keterangan'    => 'Pencairan ke Saldo Tabungan',
                'tgl_transaksi' => now(),
            ]);

            // 4. Update status pencairan
            $pencairan->update([
                'nominal_akhir' => $nominal,
                'foto_bukti_tf' => $fotoPath,
                'status'        => 'selesai',
                'approved_by'   => $adminId,
            ]);

            // 5. Update status deposito → dicairkan atau ditutup jika is_cancel
            $statusDep = $pencairan->is_cancel ? 'ditutup' : 'dicairkan';
            $pencairan->deposito->update(['status' => $statusDep]);

            // 6. Sinkronisasi persiapan cair
            DepositoPersiapanCair::where('deposito_id', $pencairan->deposito_id)
                ->update(['status' => 'selesai']);

            DB::commit();

            app(\App\Services\ActivityLogService::class)->logPencairanDeposito((string)$pencairan->deposito_id, (float)$nominal, $pencairan->nasabah->user->nama ?? 'N/A');

            // Notifikasi nasabah
            NasabahNotification::notify(
                $pencairan->id_nasabah, 'deposito',
                'Deposito Dicairkan ke Tabungan',
                'Deposito No. ' . $pencairan->deposito->nomor_deposito . ' senilai Rp ' .
                    number_format($nominal, 0, ',', '.') . ' telah ditambahkan ke saldo tabungan Anda.',
                route('nasabah.deposito.detail', $pencairan->deposito_id),
                (string) $pencairan->deposito_id, 'pencairan_deposito'
            );

            return redirect()->route('admin.deposito.pencairan-tabungan.index')
                ->with('success', 'Pencairan ke tabungan berhasil.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Finalisasi pencairan tabungan error', ['id' => $id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /* ══════════════════════════════════════════════════════════
     *  PENCAIRAN – Petty Cash Operator (dana tunai via Admin)
     * ══════════════════════════════════════════════════════════ */

    /**
     * [Owner] Inisiasi pencairan deposito via Petty Cash — kirim dana ke Admin Operasional.
     * POST /admin/deposito/pencairan/petty-cash/{id}/proses
     */
    public function pencairanPettyCashProses(Request $request, $id)
    {
        $this->checkDepositoPermission();

        $request->validate([
            'admin_id' => 'required|exists:users,id',
            'catatan'  => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $pencairan = PencairanDeposito::with(['deposito', 'nasabah'])->findOrFail($id);

            if (!$pencairan->isPettyCash()) {
                return back()->with('error', 'Jenis pencairan ini bukan Petty Cash Operator.');
            }
            if (!$pencairan->isPending()) {
                return back()->with('error', 'Pencairan ini sudah diproses sebelumnya.');
            }

            $nominal  = (float) $pencairan->nominal_akhir;
            $ownerId  = Auth::id();
            $adminId  = $request->admin_id;
            $nomorDep = $pencairan->deposito->nomor_deposito;

            // Validasi saldo Owner (cash) mencukupi
            $saldoCashOwner = PettyCashSaldo::getSaldo($ownerId, 'owner', 'cash');
            if ($saldoCashOwner < $nominal) {
                return back()->with('error',
                    'Saldo Cash Owner tidak mencukupi. Tersedia: Rp ' . number_format($saldoCashOwner, 0, ',', '.') .
                    ', Dibutuhkan: Rp ' . number_format($nominal, 0, ',', '.')
                );
            }

            $penerimaanId = IdGenerator::generate('petty_cash_penerimaan', 'PCP', 'OW', 'KRM');

            // Buat PettyCashPenerimaan (Owner → Admin, sumber=deposito)
            PettyCashPenerimaan::create([
                'id'          => $penerimaanId,
                'owner_id'    => $ownerId,
                'admin_id'    => $adminId,
                'sumber'      => 'deposito',
                'nominal_cash'=> $nominal,
                'nominal_tf'  => 0,
                'keterangan'  => 'Dana Pencairan Deposito ' . $nomorDep . ' untuk diserahkan ke nasabah',
                'status'      => 'pending',
                'ref_id'      => (string) $pencairan->id,  // link ke pencairan
            ]);

            // Hold saldo Owner (cash) — akan dikembalikan jika Admin reject
            PettyCashSaldo::buatMutasi(
                $ownerId, 'owner', -$nominal,
                'HOLD: Dana Pencairan Deposito ' . $nomorDep . ' ke Admin (Petty Cash)',
                $penerimaanId, 'petty_cash_penerimaan', 'cash'
            );

            // Catat di PettyCashOwnerTransaksi untuk audit trail / vw_saldo_owner_detail
            PettyCashOwnerTransaksi::create([
                'id'           => IdGenerator::generate('petty_cash_owner_transaksi', 'PCOW', 'OW', 'TR'),
                'user_id'      => $ownerId,
                'tipe'         => 'kirim_admin_hold',
                'sumber'       => 'deposito',
                'nominal_cash' => $nominal,
                'nominal_tf'   => 0,
                'keterangan'   => 'HOLD: Kirim dana Petty Cash ke Admin untuk Pencairan Deposito ' . $nomorDep,
                'ref_id'       => $penerimaanId,
                'ref_table'    => 'petty_cash_penerimaan',
            ]);

            // Update status pencairan → diproses (menunggu Admin konfirmasi)
            $pencairan->update(['status' => 'diproses']);

            // Sinkronisasi deposito_persiapan_cair
            DepositoPersiapanCair::where('deposito_id', $pencairan->deposito_id)
                ->where('status', 'tentatif')
                ->update([
                    'status'      => 'diproses',
                    'metode_cair' => 'petty_cash_operator',
                    'pencairan_id'=> $pencairan->id,
                ]);

            DB::commit();

            // Notifikasi nasabah bahwa pencairan sedang diproses
            NasabahNotification::notify(
                $pencairan->id_nasabah, 'deposito',
                'Pencairan Deposito Sedang Diproses',
                'Pencairan Deposito No. ' . $nomorDep . ' sedang diproses. Silakan datang ke kantor untuk pengambilan tunai.',
                route('nasabah.deposito.detail', $pencairan->deposito_id),
                (string) $pencairan->deposito_id, 'pencairan_deposito'
            );

            return redirect()->route('admin.deposito.pencairan-tf.index')
                ->with('success', 'Dana deposito Rp ' . number_format($nominal, 0, ',', '.') . ' telah dikirim ke Admin. Menunggu konfirmasi penerimaan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('pencairanPettyCashProses error', ['id' => $id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * [Admin] Finalisasi pencairan deposito via Petty Cash Operator (Tunai ke Nasabah).
     *
     * @deprecated Tidak digunakan oleh route manapun.
     *             Route `pencairan-petty-cash.serahkan` diarahkan ke
     *             PettyCashController@pencairanDepositoCash (dengan sumber='deposito').
     *             Method ini dipertahankan sebagai referensi fallback.
     */
    public function selesaikanPencairanPettyCash(Request $request, $id)
    {
        $this->checkDepositoPermission();

        $request->validate([
            'catatan' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $pencairan = PencairanDeposito::with(['deposito', 'nasabah'])->findOrFail($id);

            if (!in_array($pencairan->status, ['pending', 'diproses'])) {
                return back()->with('error', 'Pencairan tidak dalam status valid untuk diselesaikan.');
            }

            $nominal = (float) $pencairan->nominal_akhir;
            $adminId = auth()->id();
            $nomorDep = $pencairan->deposito->nomor_deposito;

            // Cek saldo Admin (cash) mencukupi
            $saldoCashAdmin = PettyCashSaldo::getSaldo($adminId, 'admin', 'cash');
            if ($saldoCashAdmin < $nominal) {
                return back()->with('error',
                    'Saldo Cash Admin tidak mencukupi. Tersedia: Rp ' . number_format($saldoCashAdmin, 0, ',', '.') .
                    ', Dibutuhkan: Rp ' . number_format($nominal, 0, ',', '.')
                );
            }

            // 1. Potong saldo Admin (cash) — sumber deposito
            PettyCashSaldo::buatMutasi(
                $adminId, 'admin', -$nominal,
                'Pencairan Deposito ' . $nomorDep . ' → Tunai ke Nasabah',
                (string) $pencairan->id, 'tbl_pencairan_deposito', 'cash', 'deposito'
            );

            // 🔥 2. Catat ke PettyCashTransaksiNasabah (pengeluaran tunai ke nasabah - guard anti-duplikasi)
            $existingPc = PettyCashTransaksiNasabah::where('ref_table', 'tbl_pencairan_deposito')
                ->where('ref_id', (string) $pencairan->id)
                ->first();
            if (!$existingPc) {
                PettyCashTransaksiNasabah::create([
                    'id'               => IdGenerator::generate('petty_cash_transaksi_nasabah', 'PC', 'DEP', 'CS'),
                    'admin_id'         => $adminId,
                    'nasabah_id'       => $pencairan->id_nasabah,
                    'id_jns_transaksi' => PettyCashConstants::JNS_PNCR,
                    'id_jns_via'       => PettyCashConstants::VIA_CS,
                    'id_jns_fitur'     => PettyCashConstants::FITUR_DEPOSITO,
                    'nominal'          => $nominal,
                    'status'           => 'approved',
                    'keterangan'       => 'Pencairan Deposito ' . $nomorDep . ' → Tunai ke Nasabah',
                    'ref_table'        => 'tbl_pencairan_deposito',
                    'ref_id'           => (string) $pencairan->id,
                    'tgl_transaksi'    => now(),
                ]);
            }

            // 3. Record di trans_deposito
            TransDeposito::create([
                'deposito_id'   => $pencairan->deposito_id,
                'jenis'         => 'pencairan',
                'nominal'       => $nominal,
                'keterangan'    => 'Pencairan Tunai (Petty Cash) ke Nasabah',
                'tgl_transaksi' => now(),
            ]);

            // 4. Update status pencairan → selesai
            $pencairan->update([
                'status'      => 'selesai',
                'approved_by' => $adminId,
                'catatan'     => $request->catatan ?? 'Pencairan tunai dikonfirmasi Admin',
            ]);

            // 5. Update status deposito → dicairkan atau ditutup
            $statusDep = $pencairan->is_cancel ? 'ditutup' : 'dicairkan';
            $pencairan->deposito->update(['status' => $statusDep]);

            // 6. Sinkronisasi persiapan cair
            DepositoPersiapanCair::where('deposito_id', $pencairan->deposito_id)
                ->update(['status' => 'selesai']);

            DB::commit();

            app(\App\Services\ActivityLogService::class)->logPencairanDeposito(
                (string) $pencairan->deposito_id,
                $nominal,
                $pencairan->nasabah->user->nama ?? 'N/A'
            );

            NasabahNotification::notify(
                $pencairan->id_nasabah, 'deposito',
                'Deposito Dicairkan (Tunai)',
                'Deposito No. ' . $nomorDep . ' senilai Rp ' .
                    number_format($nominal, 0, ',', '.') . ' telah dicairkan secara tunai.',
                route('nasabah.deposito.detail', $pencairan->deposito_id),
                (string) $pencairan->deposito_id, 'pencairan_deposito'
            );

            return redirect()->route('admin.deposito.pencairan-tf.index')
                ->with('success', 'Pencairan tunai deposito ' . $nomorDep . ' berhasil diselesaikan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('selesaikanPencairanPettyCash error', ['id' => $id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /* ══════════════════════════════════════════════════════════
     *  PERINGATAN – Dashboard peringatan jatuh tempo Owner
     * ══════════════════════════════════════════════════════════ */

    /**
     * [Owner/Admin] Dashboard peringatan deposito akan jatuh tempo.
     * GET /admin/deposito/peringatan
     */
    public function peringatanIndex(Request $request)
    {
        $this->checkDepositoPermission();

        $query = DepositoPersiapanCair::with(['deposito.nasabah.user', 'deposito.tenor', 'nasabah.user'])
            ->whereIn('status', ['tentatif', 'diproses'])
            ->orderBy('tgl_target_cair');

        if ($request->filled('metode_cair')) {
            $query->where('metode_cair', $request->metode_cair);
        }

        if ($request->filled('tanggal_dari')) {
            $query->where('tgl_target_cair', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->where('tgl_target_cair', '<=', $request->tanggal_sampai);
        }

        $persiapan = $query->paginate(15)->withQueryString();

        // Agregasi per hari untuk summary Owner
        $summary = DepositoPersiapanCair::whereIn('status', ['tentatif', 'diproses'])
            ->selectRaw('tgl_target_cair, metode_cair, COUNT(*) as jumlah, SUM(total_dibayar) as total_dana')
            ->groupBy('tgl_target_cair', 'metode_cair')
            ->orderBy('tgl_target_cair')
            ->get()
            ->groupBy('tgl_target_cair');

        // Stats card
        $stats = [
            'total_persiapan'    => DepositoPersiapanCair::whereIn('status', ['tentatif', 'diproses'])->count(),
            'total_dana'         => DepositoPersiapanCair::whereIn('status', ['tentatif', 'diproses'])->sum('total_dibayar'),
            'butuh_transfer'     => DepositoPersiapanCair::whereIn('status', ['tentatif', 'diproses'])
                                        ->where('metode_cair', 'rek_nasabah')->sum('total_dibayar'),
            'butuh_petty_cash'   => DepositoPersiapanCair::whereIn('status', ['tentatif', 'diproses'])
                                        ->where('metode_cair', 'petty_cash_operator')->sum('total_dibayar'),
            'ke_tabungan'        => DepositoPersiapanCair::whereIn('status', ['tentatif', 'diproses'])
                                        ->where('metode_cair', 'saldo_tabungan')->sum('total_dibayar'),
            'jatuh_tempo_hari_ini' => DepositoPersiapanCair::whereIn('status', ['tentatif', 'diproses'])
                                        ->where('tgl_target_cair', today())->count(),
        ];

        return view('admin.deposito.peringatan-index', compact('persiapan', 'summary', 'stats'));
    }

    /**
     * Update detail persiapan pencairan (metode cair / catatan).
     */
    public function updatePersiapanCair(Request $request, $id)
    {
        $this->checkDepositoPermission();
        $item = DepositoPersiapanCair::findOrFail($id);

        $request->validate([
            'metode_cair' => 'required|in:rek_nasabah,saldo_tabungan,petty_cash_operator',
            'catatan'     => 'nullable|string|max:500',
        ]);

        $item->update([
            'metode_cair' => $request->metode_cair,
            'catatan'     => $request->catatan,
        ]);

        return back()->with('success', 'Rencana pencairan berhasil diperbarui.');
    }

    /**
     * [Owner] Kirim dana persiapan ke Admin (Khusus Petty Cash).
     */
    public function sendDanaPersiapan(Request $request, $id)
    {
        $this->checkDepositoPermission();
        $item = DepositoPersiapanCair::with(['deposito', 'nasabah'])->findOrFail($id);

        if ($item->status !== 'tentatif') {
            return back()->with('error', 'Persiapan dana ini sudah diproses.');
        }

        if ($item->metode_cair !== 'petty_cash_operator') {
            return back()->with('error', 'Pengiriman dana hanya tersedia untuk metode Petty Cash.');
        }

        $request->validate([
            'admin_id' => 'required|exists:users,id',
        ]);

        try {
            DB::beginTransaction();

            $nominal  = (float) $item->total_dibayar;
            $ownerId  = Auth::id();
            $adminId  = $request->admin_id;
            $nomorDep = $item->deposito->nomor_deposito;

            // Validasi saldo Owner (cash)
            $saldoCashOwner = PettyCashSaldo::getSaldo($ownerId, 'owner', 'cash');
            if ($saldoCashOwner < $nominal) {
                return back()->with('error', 'Saldo Cash Owner tidak mencukupi. Tersedia: Rp ' . number_format($saldoCashOwner, 0, ',', '.'));
            }

            $penerimaanId = IdGenerator::generate('petty_cash_penerimaan', 'PCP', 'OW', 'KRM');

            // 1. Buat PettyCashPenerimaan
            PettyCashPenerimaan::create([
                'id'          => $penerimaanId,
                'owner_id'    => $ownerId,
                'admin_id'    => $adminId,
                'sumber'      => 'deposito',
                'nominal_cash'=> $nominal,
                'nominal_tf'  => 0,
                'keterangan'  => 'Persiapan Dana Deposito ' . $nomorDep . ' (Jatuh Tempo: ' . $item->tgl_target_cair->format('d/m/Y') . ')',
                'status'      => 'pending',
                'ref_id'      => (string) $item->id, // link ke persiapan cair
            ]);

            // Tunai Koperasi (CASH Owner - DEPOSITO) berkurang
            PettyCashSaldo::buatMutasi(
                $ownerId, 'owner', -$nominal,
                "Pencairan Deposito (Tunai Koperasi) #{$item->id}",
                $item->id, 'tbl_pencairan_deposito', 'cash',
                \App\Services\PettyCashConstants::SUMBER_DEPOSITO
            );

            // 3. Catat di Owner Wallet Detail
            PettyCashOwnerTransaksi::create([
                'id'           => IdGenerator::generate('petty_cash_owner_transaksi', 'PCOW', 'OW', 'TR'),
                'user_id'      => $ownerId,
                'tipe'         => 'kirim_admin_hold',
                'sumber'       => 'deposito',
                'nominal_cash' => $nominal,
                'nominal_tf'   => 0,
                'keterangan'   => 'HOLD: Kirim dana Petty Cash ke Admin untuk Persiapan Deposito ' . $nomorDep,
                'ref_id'       => $penerimaanId,
                'ref_table'    => 'petty_cash_penerimaan',
            ]);

            // 4. Update status persiapan → diproses
            $item->update(['status' => 'diproses']);

            DB::commit();
            return back()->with('success', 'Dana persiapan berhasil dikirim ke Admin. Menunggu konfirmasi Admin.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengirim dana: ' . $e->getMessage());
        }
    }

    /* ══════════════════════════════════════════════════════════
     *  PAKET DEPOSITO (Owner Only)
     * ══════════════════════════════════════════════════════════ */

    public function paketIndex()
    {
        if (auth()->user()->role !== 'admin_utama') abort(403);
        $paket = PaketDeposito::orderBy('tenor_bulan')->orderBy('minimal_nominal')->get();
        return view('admin.deposito.paket.index', compact('paket'));
    }

    public function paketCreate()
    {
        if (auth()->user()->role !== 'admin_utama') abort(403);
        $kategoris = \App\Models\KategoriDeposito::where('status', 'aktif')->get();
        $tenors = \App\Models\JnsTenorDeposito::where('aktif', 'y')->orderBy('tenor_bulan')->get();
        return view('admin.deposito.paket.create', compact('kategoris', 'tenors'));
    }

    public function paketStore(Request $request)
    {
        if (auth()->user()->role !== 'admin_utama') abort(403);
        $validated = $request->validate([
            'nama_paket'       => 'required|string|max:100',
            'tenor_bulan'      => 'required|integer|min:1',
            'suku_bunga'       => 'required|numeric|min:0',
            'minimal_nominal'  => 'required|numeric|min:0',
            'maksimal_nominal' => 'nullable|numeric|gte:minimal_nominal',
            'status'           => 'required|in:aktif,nonaktif',
            'kategori_id'      => 'nullable|exists:kategori_depositos,id',
            'keterangan'       => 'nullable|string'
        ]);

        PaketDeposito::create($validated);
        return redirect()->route('admin.deposito.paket.index')->with('success', 'Paket Deposito berhasil ditambahkan.');
    }

    public function paketEdit($id)
    {
        if (auth()->user()->role !== 'admin_utama') abort(403);
        $paket = PaketDeposito::findOrFail($id);
        $kategoris = \App\Models\KategoriDeposito::where('status', 'aktif')->get();
        $tenors = \App\Models\JnsTenorDeposito::where('aktif', 'y')->orderBy('tenor_bulan')->get();
        return view('admin.deposito.paket.edit', compact('paket', 'kategoris', 'tenors'));
    }

    public function paketUpdate(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin_utama') abort(403);
        $paket = PaketDeposito::findOrFail($id);
        
        $validated = $request->validate([
            'nama_paket'       => 'required|string|max:100',
            'tenor_bulan'      => 'required|integer|min:1',
            'suku_bunga'       => 'required|numeric|min:0',
            'minimal_nominal'  => 'required|numeric|min:0',
            'maksimal_nominal' => 'nullable|numeric|gte:minimal_nominal',
            'status'           => 'required|in:aktif,nonaktif',
            'kategori_id'      => 'nullable|exists:kategori_depositos,id',
            'keterangan'       => 'nullable|string'
        ]);

        $paket->update($validated);
        return redirect()->route('admin.deposito.paket.index')->with('success', 'Paket Deposito berhasil diperbarui.');
    }

    public function paketDestroy($id)
    {
        if (auth()->user()->role !== 'admin_utama') abort(403);
        $paket = PaketDeposito::findOrFail($id);
        $paket->update(['status' => 'nonaktif']);
        return redirect()->route('admin.deposito.paket.index')->with('success', 'Paket Deposito berhasil dinonaktifkan (soft delete).');
    }

    /* ══════════════════════════════════════════════════════════
     *  Helper – Hitung saldo nasabah
     * ══════════════════════════════════════════════════════════ */

    /**
     * Hitung saldo tabungan nasabah.
     *
     * @param  int|string  $idAnggota
     * @param  int|string|null  $excludePengajuanId  ID pengajuan deposito yang sedang diproses (dikecualikan dari hold)
     * @return float
     */
    private function getSaldoNasabah($idAnggota, $excludePengajuanId = null): float
    {
        $totalSetoran = TransTabungan::where('id_anggota', $idAnggota)
            ->whereHas('jnsTransaksi', fn($q) => $q->where('kode', 'STR'))->sum('nominal') ?? 0;

        $totalPenarikan = TransTabungan::where('id_anggota', $idAnggota)
            ->whereHas('jnsTransaksi', fn($q) => $q->where('kode', 'PNR'))->sum('nominal') ?? 0;

        // Pengajuan deposito pending via saldo_tabungan yang BELUM diproses
        // (dikecualikan: pengajuan yang sedang di-approve agar tidak double-count)
        $pendingDepositoQuery = \App\Models\PengajuanDeposito::where('id_nasabah', $idAnggota)
            ->where('status', '1')
            ->where('metode_setor', 'saldo_tabungan');

        if ($excludePengajuanId !== null) {
            $pendingDepositoQuery->where('id', '!=', $excludePengajuanId);
        }

        $pendingDeposito = $pendingDepositoQuery->sum('nominal') ?? 0;

        return max(0, $totalSetoran - $totalPenarikan - $pendingDeposito);
    }
}
