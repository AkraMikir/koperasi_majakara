<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanDeposito;
use App\Models\DepositoH;
use App\Models\JnsTenorDeposito;
use App\Models\SukuBungaDeposito;
use App\Models\Nasabah;
use App\Models\NasabahNotification;
use App\Models\TransTabungan;
use App\Models\TransDeposito;
use App\Models\PencairanDeposito;
use App\Helpers\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
            'pengajuan_approved'     => PengajuanDeposito::where('status', '2')->count(),
            'pengajuan_rejected'     => PengajuanDeposito::where('status', '3')->count(),
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

        return view('admin.deposito.index', compact('stats', 'pengajuan_terbaru', 'deposito_terbaru', 'jatuh_tempo'));
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
            $sukuBunga = SukuBungaDeposito::where('tenor_id', $tenor->id)
                ->where('status', 'aktif')
                ->where(fn($q) => $q->whereNull('min_nominal')->orWhere('min_nominal', '<=', $nominal))
                ->where(fn($q) => $q->whereNull('max_nominal')->orWhere('max_nominal', '>=', $nominal))
                ->orderBy('min_nominal')->first();

            $bungaFallback = [1 => 0.0375, 3 => 0.0450, 6 => 0.0525, 12 => 0.0600];
            $bunga = $sukuBunga ? (float) $sukuBunga->bunga : ($bungaFallback[$tenor->tenor_bulan] ?? 0.05);

            if ($pengajuan->metode_setor === 'saldo_tabungan') {
                $nasabah = $pengajuan->nasabah;
                $saldo   = $this->getSaldoNasabah($nasabah->id);
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

            $tglMulai      = now();
            $tglJatuhTempo = now()->addDays($tenor->tenor_hari);

            $nomorDeposito = 'DP' . now()->format('ymd') . str_pad(
                DepositoH::whereDate('created_at', today())->count() + 1,
                4, '0', STR_PAD_LEFT
            );

            $deposito = DepositoH::create([
                'id_pengajuan'    => $pengajuan->id,
                'id_nasabah'      => $pengajuan->id_nasabah,
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
     * Detail deposito aktif
     */
    public function depositoDetail($id)
    {
        $deposito = DepositoH::with(['nasabah.user', 'tenor', 'transDeposito', 'pencairan'])
            ->findOrFail($id);

        return view('admin.deposito.deposito-detail', compact('deposito'));
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

        if (!$pencairan->isPending()) {
            return redirect()->route('admin.deposito.pencairan-tf.index')
                ->with('error', 'Pencairan ini sudah diproses sebelumnya.');
        }

        return view('admin.deposito.pencairan-tf-form', compact('pencairan'));
    }

    /**
     * Proses pencairan TF (POST)
     */
    public function pencairanTfProses(Request $request, $id)
    {
        $this->checkDepositoPermission();

        $request->validate([
            'nominal_akhir' => 'required|numeric|min:1',
            'foto_bukti_tf' => 'required|image|max:5120',
            'catatan'       => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $pencairan = PencairanDeposito::with(['deposito', 'nasabah'])->findOrFail($id);

            if (!$pencairan->isPending()) {
                return back()->with('error', 'Pencairan sudah diproses sebelumnya.');
            }

            // Upload foto bukti TF
            $fotoPath = $request->file('foto_bukti_tf')->store('deposito/bukti-tf-pencairan', 'public');

            // Update pencairan record
            $pencairan->update([
                'nominal_akhir' => $request->nominal_akhir,
                'foto_bukti_tf' => $fotoPath,
                'catatan'       => $request->catatan,
                'status'        => 'selesai',
                'approved_by'   => auth()->id(),
            ]);

            // Record di trans_deposito
            TransDeposito::create([
                'deposito_id'   => $pencairan->deposito_id,
                'jenis'         => 'pencairan',
                'nominal'       => $request->nominal_akhir,
                'keterangan'    => 'Pencairan via Transfer ke Rekening Nasabah',
                'tgl_transaksi' => now(),
            ]);

            // Update status deposito → dicairkan
            $pencairan->deposito->update(['status' => 'dicairkan']);

            DB::commit();

            // Notifikasi nasabah
            NasabahNotification::notify(
                $pencairan->id_nasabah, 'deposito',
                'Deposito Anda Telah Dicairkan',
                'Deposito No. ' . $pencairan->deposito->nomor_deposito . ' senilai Rp ' .
                    number_format($request->nominal_akhir, 0, ',', '.') . ' telah ditransfer ke rekening Anda.',
                route('nasabah.deposito.detail', $pencairan->deposito_id),
                (string) $pencairan->deposito_id, 'pencairan_deposito'
            );

            return redirect()->route('admin.deposito.pencairan-tf.index')
                ->with('success', 'Pencairan TF berhasil diproses dan nasabah telah diberi notifikasi.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Proses pencairan TF error', ['id' => $id, 'error' => $e->getMessage()]);
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

        return view('admin.deposito.pencairan-tabungan', compact('pencairans', 'pendingCount'));
    }

    /**
     * Proses pencairan ke Tabungan (POST)
     */
    public function pencairanTabunganProses(Request $request, $id)
    {
        $this->checkDepositoPermission();

        $request->validate([
            'catatan' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $pencairan = PencairanDeposito::with(['deposito.tenor', 'nasabah'])->findOrFail($id);

            if (!$pencairan->isPending()) {
                return back()->with('error', 'Pencairan sudah diproses sebelumnya.');
            }

            $nominal = (float) $pencairan->nominal_akhir;

            // Buat TransTabungan → setoran masuk ke tabungan nasabah
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
                'admin_pengelola_id' => auth()->id(),
            ]);

            // Record di trans_deposito
            TransDeposito::create([
                'deposito_id'   => $pencairan->deposito_id,
                'jenis'         => 'pencairan',
                'nominal'       => $nominal,
                'keterangan'    => 'Pencairan ke Saldo Tabungan - ' . ($request->catatan ?? ''),
                'tgl_transaksi' => now(),
            ]);

            // Update pencairan record
            $pencairan->update([
                'catatan'     => $request->catatan,
                'status'      => 'selesai',
                'approved_by' => auth()->id(),
            ]);

            // Update status deposito → dicairkan
            $pencairan->deposito->update(['status' => 'dicairkan']);

            DB::commit();

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
                ->with('success', 'Pencairan ke tabungan berhasil diproses. Saldo tabungan nasabah sudah bertambah.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Proses pencairan tabungan error', ['id' => $id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /* ══════════════════════════════════════════════════════════
     *  Helper – Hitung saldo nasabah
     * ══════════════════════════════════════════════════════════ */
    private function getSaldoNasabah($idAnggota): float
    {
        $totalSetoran  = TransTabungan::where('id_anggota', $idAnggota)
            ->whereHas('jnsTransaksi', fn($q) => $q->where('kode', 'STR'))->sum('nominal') ?? 0;

        $totalPenarikan = TransTabungan::where('id_anggota', $idAnggota)
            ->whereHas('jnsTransaksi', fn($q) => $q->where('kode', 'PNR'))->sum('nominal') ?? 0;

        $pendingDeposito = \App\Models\PengajuanDeposito::where('id_nasabah', $idAnggota)
            ->where('status', '1')->where('metode_setor', 'saldo_tabungan')->sum('nominal') ?? 0;

        return max(0, $totalSetoran - $totalPenarikan - $pendingDeposito);
    }
}
