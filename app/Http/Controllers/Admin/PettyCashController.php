<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PettyCashPenerimaan;
use App\Models\PettyCashTransaksiNasabah;
use App\Models\PettyCashSetoranKantor;
use App\Models\PettyCashSaldo;
use App\Models\PettyCashLog;
use App\Models\TransTabungan;
use App\Models\Nasabah;
use App\Models\User;
use App\Models\PettyCashOwnerTransaksi;
use App\Models\BuktiFoto;
use App\Helpers\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PettyCashController extends Controller
{
    // =========================================================================
    // OWNER: Kirim Dana ke Admin
    // =========================================================================

    /**
     * [Owner] Form kirim dana ke admin.
     * GET /admin/petty-cash/penerimaan/create
     */
    public function penerimaanCreate(Request $request)
    {
        $query = PettyCashPenerimaan::with(['admin', 'owner'])->latest();

        // 🔍 Filter Logic
        if ($request->admin_id) {
            $query->where('admin_id', $request->admin_id);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->tgl_dari) {
            $query->whereDate('created_at', '>=', $request->tgl_dari);
        }
        if ($request->tgl_sampai) {
            $query->whereDate('created_at', '<=', $request->tgl_sampai);
        }

        $pengiriman = $query->paginate(15);
        
        // 📊 Recalculate Stats based on filters
        $stats = [
            'total'         => (clone $query)->count(),
            'pending'       => (clone $query)->where('status', 'pending')->count(),
            'approved'      => (clone $query)->where('status', 'approved')->count(),
            'rejected'      => (clone $query)->where('status', 'rejected')->count(),
            'total_nominal' => (clone $query)->where('status', 'approved')->get()->sum(function($item) {
                return (float)$item->nominal_tf + (float)$item->nominal_cash;
            })
        ];

        $admins = User::where('role', 'admin_operasional')->get();
        $saldoCash = PettyCashSaldo::getSaldo(Auth::id(), 'owner', 'cash');
        $saldoTf   = PettyCashSaldo::getSaldo(Auth::id(), 'owner', 'transfer');
        
        if ($request->ajax()) {
            return response()->json([
                'html'       => view('admin.petty-cash.partials._penerimaan_table', compact('pengiriman'))->render(),
                'stats'      => $stats,
                'pagination' => $pengiriman->links()->render()
            ]);
        }

        return view('admin.petty-cash.penerimaan-owner', compact('pengiriman', 'stats', 'admins', 'saldoCash', 'saldoTf'));
    }

    /**
     * [Owner] Submit pengiriman dana.
     * POST /admin/petty-cash/penerimaan
     */
    public function penerimaanStore(Request $request)
    {
        $request->validate([
            'admin_id'     => 'required|exists:users,id',
            'nominal_tf'   => 'nullable|numeric|min:0',
            'nominal_cash' => 'nullable|numeric|min:0',
            'bukti_tf'     => 'nullable|image|max:5120',
            'foto_cash'    => 'nullable|image|max:5120',
            'keterangan'   => 'nullable|string|max:500',
        ]);

        $nominalTf   = (float) ($request->nominal_tf   ?? 0);
        $nominalCash = (float) ($request->nominal_cash ?? 0);

        if (($nominalTf + $nominalCash) <= 0) {
            return back()->withErrors(['nominal_tf' => 'Minimal satu nominal harus diisi.'])->withInput();
        }

        $buktiFoto = null;
        if ($request->hasFile('bukti_tf')) {
            $buktiFoto = $request->file('bukti_tf')->store('petty_cash/bukti_tf', 'public');
        }

        $fotoCash = null;
        if ($request->hasFile('foto_cash')) {
            $fotoCash = $request->file('foto_cash')->store('petty_cash/foto_cash', 'public');
        }

        $id = IdGenerator::generate('petty_cash_penerimaan', 'PCP', 'OW', 'KRM');

        try {
            DB::beginTransaction();

            // 🔥 VALIDASI SALDO OWNER (Governance 2.0)
            if (!PettyCashSaldo::validateKirimOwner(Auth::id(), $nominalCash, $nominalTf)) {
                return back()->withErrors(['nominal_cash' => 'Saldo Owner tidak mencukupi untuk pengiriman ini. Silakan isi saldo utama terlebih dahulu.'])->withInput();
            }

            $penerimaan = PettyCashPenerimaan::create([
                'id'          => $id,
                'owner_id'    => Auth::id(),
                'admin_id'    => $request->admin_id,
                'nominal_tf'  => $nominalTf,
                'nominal_cash'=> $nominalCash,
                'bukti_tf'    => $buktiFoto,
                'foto_cash'   => $fotoCash,
                'keterangan'  => $request->keterangan,
                'status'      => 'pending',
            ]);

            // 🔥 POTONG SALDO OWNER (HOLD)
            if ($nominalCash > 0) {
                PettyCashSaldo::buatMutasi(
                    Auth::id(), 'owner', -$nominalCash,
                    "Kirim Dana ke Admin ({$penerimaan->admin->nama}) - HOLD",
                    $id, 'petty_cash_penerimaan', 'cash'
                );
            }
            if ($nominalTf > 0) {
                PettyCashSaldo::buatMutasi(
                    Auth::id(), 'owner', -$nominalTf,
                    "Kirim Transfer ke Admin ({$penerimaan->admin->nama}) - HOLD",
                    $id, 'petty_cash_penerimaan', 'transfer'
                );
            }

            // Catat di Transaksi Owner Utama
            PettyCashOwnerTransaksi::create([
                'id'              => IdGenerator::generate('petty_cash_owner_transaksi', 'PCOW', 'OW', 'TR'),
                'user_id'         => Auth::id(),
                'tipe'            => 'kirim_admin_hold',
                'nominal_cash'    => $nominalCash,
                'nominal_tf'      => $nominalTf,
                'keterangan'      => "Kirim ke Admin: " . ($penerimaan->admin->nama ?? '-') . ". " . $request->keterangan,
                'bukti_foto_cash' => $fotoCash,
                'bukti_foto_tf'   => $buktiFoto,
                'ref_id'          => $id,
                'ref_table'       => 'petty_cash_penerimaan',
            ]);

            PettyCashLog::catat(Auth::id(), 'kirim_dana_ke_admin', $nominalTf + $nominalCash, [
                'admin_id' => $request->admin_id,
                'penerimaan_id' => $id,
            ], $id, 'petty_cash_penerimaan');

            DB::commit();

            return redirect()->route('admin.petty-cash.penerimaan.create')
                ->with('success', 'Dana berhasil dikirim dan saldo Owner telah dipotong. Menunggu konfirmasi Admin.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PettyCash penerimaanStore error', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    // =========================================================================
    // ADMIN: Terima Dana dari Owner
    // =========================================================================

    /**
     * [Admin] Daftar penerimaan dana (pending & history).
     * GET /admin/petty-cash/penerimaan
     */
    public function penerimaanIndex(Request $request)
    {
        $query = PettyCashPenerimaan::with(['owner', 'admin'])
            ->where('admin_id', Auth::id())
            ->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $penerimaan = $query->paginate(15);
        $saldoAdmin  = PettyCashSaldo::getSaldo(Auth::id(), 'admin');

        return view('admin.petty-cash.penerimaan', compact('penerimaan', 'saldoAdmin'));
    }

    /**
     * [Admin] ACC penerimaan dari Owner → tambah saldo admin.
     * POST /admin/petty-cash/penerimaan/{id}/approve
     */
    public function penerimaanApprove(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $penerimaan = PettyCashPenerimaan::where('admin_id', Auth::id())
                ->where('status', 'pending')
                ->findOrFail($id);

            $penerimaan->update([
                'status'            => 'approved',
                'keterangan_admin'  => $request->keterangan_admin,
            ]);

            // 🔥 TF & CASH TERPISAH!
            $nominalTotal = 0;
            if ($penerimaan->nominal_tf > 0) {
                PettyCashSaldo::buatMutasi(
                    Auth::id(), 'admin', (float)$penerimaan->nominal_tf,
                    "Penerimaan TF dari Owner ({$penerimaan->owner->nama})",
                    $penerimaan->id, 'petty_cash_penerimaan', 'transfer'
                );
                $nominalTotal += (float)$penerimaan->nominal_tf;
            }

            if ($penerimaan->nominal_cash > 0) {
                PettyCashSaldo::buatMutasi(
                    Auth::id(), 'admin', (float)$penerimaan->nominal_cash,
                    "Penerimaan Cash dari Owner ({$penerimaan->owner->nama})",
                    $penerimaan->id, 'petty_cash_penerimaan', 'cash'
                );
                $nominalTotal += (float)$penerimaan->nominal_cash;
            }

            PettyCashLog::catat(Auth::id(), 'approve_penerimaan', $nominalTotal, [
                'owner_id'        => $penerimaan->owner_id,
                'penerimaan_id'   => $id,
                'detil_tf'        => $penerimaan->nominal_tf,
                'detil_cash'      => $penerimaan->nominal_cash,
            ], $id, 'petty_cash_penerimaan');

            DB::commit();

            return back()->with('success', 'Penerimaan disetujui. Saldo Cash & TF Anda telah diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PettyCash penerimaanApprove error', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * [Admin] Reject penerimaan.
     * POST /admin/petty-cash/penerimaan/{id}/reject
     */
    public function penerimaanReject(Request $request, $id)
    {
        $request->validate(['keterangan_admin' => 'required|string']);

        $penerimaan = PettyCashPenerimaan::where('admin_id', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($id);

        $penerimaan->update([
            'status'           => 'rejected',
            'keterangan_admin' => $request->keterangan_admin,
        ]);

        // 🔥 REFUND SALDO OWNER
        if ($penerimaan->nominal_cash > 0) {
            PettyCashSaldo::buatMutasi(
                $penerimaan->owner_id, 'owner', (float)$penerimaan->nominal_cash,
                "REFUND: Penolakan dana oleh Admin ({$penerimaan->admin->nama})",
                $id, 'petty_cash_penerimaan', 'cash'
            );
        }
        if ($penerimaan->nominal_tf > 0) {
            PettyCashSaldo::buatMutasi(
                $penerimaan->owner_id, 'owner', (float)$penerimaan->nominal_tf,
                "REFUND: Penolakan transfer oleh Admin ({$penerimaan->admin->nama})",
                $id, 'petty_cash_penerimaan', 'transfer'
            );
        }

        return back()->with('success', 'Penerimaan ditolak dan saldo Owner telah dikembalikan.');
    }

    // =========================================================================
    // ADMIN: Input Setoran Nasabah (Petty Cash)
    // =========================================================================



    /**
     * [Admin] Approve setoran TF nasabah (konfirmasi mutasi bank).
     * POST /admin/petty-cash/transaksi/{id}/approve
     */
    public function approveSetoranTf(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $pctn = PettyCashTransaksiNasabah::where('admin_id', Auth::id())
                ->where('status', 'pending')
                ->findOrFail($id);

            $pctn->update(['status' => 'approved']);

            // Tambah saldo admin (TF masuk ke rekening admin/kantor)
            PettyCashSaldo::buatMutasi(
                Auth::id(), 'admin', (float) $pctn->nominal,
                "Konfirmasi TF nasabah: {$pctn->nasabah->user->nama}",
                $pctn->id, 'petty_cash_transaksi_nasabah', 'transfer'
            );

            DB::commit();
            return back()->with('success', 'Transfer nasabah dikonfirmasi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // ADMIN: Setor Cash ke Kantor
    // =========================================================================

    /**
     * [Admin] Halaman setor ke kantor.
     * GET /admin/petty-cash/setoran-kantor
     */
    public function setoranKantorIndex()
    {
        $adminId = Auth::id();

        // Semua transaksi yang belum disetor (mengabaikan tanggal agar uang nyangkut tetap ke-cover)
        $transaksiPending = PettyCashTransaksiNasabah::with(['nasabah.user', 'jnsVia', 'jnsTransaksi', 'jnsFitur'])
            ->where('admin_id', $adminId)
            ->where('status', 'approved')
            ->whereNull('setoran_kantor_id')
            ->get();

        $totalCash = $transaksiPending
            ->filter(fn($t) => in_array($t->jnsVia?->kode, ['CS', 'TN']))
            ->sum('nominal');

        $totalTf = $transaksiPending
            ->filter(fn($t) => $t->jnsVia?->kode === 'TF')
            ->sum('nominal');

        $saldoCash = PettyCashSaldo::getSaldoCash($adminId);
        $saldoTransfer = PettyCashSaldo::getSaldoTransfer($adminId);

        // Riwayat setoran sebelumnya
        $riwayatSetoran = PettyCashSetoranKantor::where('admin_id', $adminId)
            ->latest()
            ->take(10)
            ->get();

        return view('admin.petty-cash.setoran-kantor', compact(
            'transaksiPending', 'totalCash', 'totalTf', 'saldoCash', 'saldoTransfer', 'riwayatSetoran'
        ));
    }

    /**
     * [Admin] Submit setoran ke kantor.
     * POST /admin/petty-cash/setoran-kantor
     */
    public function setoranKantorStore(Request $request)
    {
        $request->validate([
            'foto_setoran'       => 'nullable|image|max:5120',
            'sudah_setor_fisik'  => 'required|in:1,0,true,false',
            'keterangan_admin'   => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $adminId = Auth::id();

            // Ambil semua transaksi yang belum disetor (mengabaikan tanggal agar dana nyangkut tidak hilang)
            $transaksi = PettyCashTransaksiNasabah::with(['nasabah.user', 'jnsVia', 'jnsTransaksi', 'jnsFitur'])
                ->where('admin_id', $adminId)
                ->where('status', 'approved')
                ->whereNull('setoran_kantor_id')
                ->get();

            if ($transaksi->isEmpty()) {
                return back()->with('error', 'Tidak ada transaksi yang bisa disetor hari ini.');
            }

            $totalSetor = $transaksi->sum('nominal');

            // Build data potongan untuk JSON
            $dataPotongan = $transaksi->map(fn($t) => [
                'nasabah_id'  => $t->nasabah_id,
                'nama'        => $t->nasabah->user->nama ?? '-',
                'nominal'     => (float) $t->nominal,
                'transaksi'   => $t->jnsTransaksi?->nama ?? '-',
                'via'         => $t->jnsVia?->nama ?? '-',
                'via_kode'    => $t->jnsVia?->kode ?? '-',
                'fitur'       => $t->jnsFitur?->nama ?? '-',
                'jenis_transaksi' => $t->jenis_transaksi,
                'pctn_id'     => $t->id,
                'ref_id'      => $t->ref_id,
            ])->values()->toArray();

            $fotoSetoran = null;
            if ($request->hasFile('foto_setoran')) {
                $fotoSetoran = $request->file('foto_setoran')->store('petty_cash/setoran', 'public');
            }

            // Cari owner (admin_utama) — ambil pertama dulu
            $owner = User::where('role', 'admin_utama')->first();

            $idSetoran = IdGenerator::generate('petty_cash_setoran_kantor', 'PCS', 'AD', 'STR');

            $setoran = PettyCashSetoranKantor::create([
                'id'                => $idSetoran,
                'admin_id'          => $adminId,
                'owner_id'          => $owner?->id,
                'total_setor'       => $totalSetor,
                'data_potongan'     => $dataPotongan,
                'jumlah_nasabah'    => $transaksi->count(),
                'foto_setoran'      => $fotoSetoran,
                'sudah_setor_fisik' => (bool) $request->sudah_setor_fisik,
                'status'            => 'pending',
                'keterangan_admin'  => $request->keterangan_admin,
            ]);

            // Update transaksi: tandai sudah masuk setoran ini
            PettyCashTransaksiNasabah::whereIn('id', $transaksi->pluck('id'))
                ->update(['setoran_kantor_id' => $idSetoran]);
                
            foreach ($transaksi as $t) {
                if ($t->ref_table === 'tbl_pengajuan_pembayaran_pinjaman' || $t->ref_table === 'tbl_pengajuan_tabungan') {
                    DB::table($t->ref_table)->where('id', $t->ref_id)->update(['setoran_kantor_id' => $idSetoran]);
                }
            }

            // 🔥 PEMISAHAN PENGURANGAN SALDO ADMIN
            $nominalCash = $transaksi->filter(fn($t) => in_array($t->jnsVia?->kode, ['CS', 'TN']))->sum('nominal');
            $nominalTf = $transaksi->filter(fn($t) => $t->jnsVia?->kode === 'TF')->sum('nominal');

            if ($nominalCash > 0) {
                PettyCashSaldo::buatMutasi(
                    $adminId, 'admin', -$nominalCash,
                    "Setor Cash ke Kantor (#{$idSetoran})",
                    $idSetoran, 'petty_cash_setoran_kantor', 'cash'
                );
            }

            if ($nominalTf > 0) {
                PettyCashSaldo::buatMutasi(
                    $adminId, 'admin', -$nominalTf,
                    "Setor TF (Hollow) ke Kantor (#{$idSetoran})",
                    $idSetoran, 'petty_cash_setoran_kantor', 'transfer'
                );
            }

            PettyCashLog::catat($adminId, 'setor_ke_kantor', $totalSetor, [
                'setoran_id'     => $idSetoran,
                'jml_nasabah'    => $transaksi->count(),
                'cash'           => $nominalCash,
                'tf'             => $nominalTf,
            ], $idSetoran, 'petty_cash_setoran_kantor');

            DB::commit();

            return redirect()->route('admin.petty-cash.setoran-kantor.index')
                ->with('success', "Setoran Rp " . number_format((float) $totalSetor, 0, ',', '.') . " berhasil dikirim ke Owner untuk diverifikasi.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PettyCash setoranKantorStore error', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // OWNER: Dashboard & Verifikasi
    // =========================================================================

    /**
     * [Owner] Dashboard petty cash.
     * GET /admin/petty-cash/dashboard
     */
    public function dashboard()
    {
        $userId = Auth::id();
        $userRole = Auth::user()->role ?? 'admin_operasional';
        
        if ($userRole === 'admin_utama') {
            // 🔥 OWNER: Helicopter View
            $saldoOwnerCash = PettyCashSaldo::getSaldo($userId, 'owner', 'cash');
            $saldoOwnerTf   = PettyCashSaldo::getSaldo($userId, 'owner', 'transfer');
            $saldoOwnerTotal = $saldoOwnerCash + $saldoOwnerTf;
            
            $pendingSetoran = PettyCashSetoranKantor::where('status', 'pending')->count();
            
            // Ambil admin yang role-nya admin_operasional
            $admins = User::where('role', 'admin_operasional')->get()->map(function($u) {
                $u->saldo_petty_cash = PettyCashSaldo::getTotalAdmin($u->id);
                $u->saldo_cash = PettyCashSaldo::getSaldoCash($u->id);
                $u->saldo_tf = PettyCashSaldo::getSaldoTransfer($u->id);
                return $u;
            });

            // Setoran pending spesifik dari admin (Alert card Owner)
            $setoranPending = PettyCashSetoranKantor::with('admin')
                ->where('status', 'pending')
                ->latest()
                ->take(5)
                ->get();

            // Statistik Grafik 7 Hari
            $grafik = [];
            for ($i = 6; $i >= 0; $i--) {
                $tgl = now()->subDays($i)->toDateString();
                $grafik[] = [
                    'tanggal'   => $tgl,
                    'penerimaan'=> PettyCashPenerimaan::whereDate('created_at', $tgl)
                                    ->where('status', 'approved')
                                    ->sum(DB::raw('nominal_tf + nominal_cash')),
                    'setoran'   => PettyCashSetoranKantor::whereDate('created_at', $tgl)
                                    ->where('status', 'approved_owner')
                                    ->sum('total_setor'),
                ];
            }

            return view('admin.petty-cash.owner-dashboard', compact(
                'saldoOwnerCash', 'saldoOwnerTf', 'saldoOwnerTotal', 'pendingSetoran', 'admins', 'setoranPending', 'grafik'
            ));
        }

        // 🔥 ADMIN: Operasional Harian
        $saldoCash = PettyCashSaldo::getSaldoCash($userId);
        $saldoTransfer = PettyCashSaldo::getSaldoTransfer($userId);
        
        $isLowSaldo = ($saldoCash < 500000);
        $alertMessage = $isLowSaldo ? 'SALDO CASH RENDAH!' : 'OK';

        $mySetoranPending = PettyCashSetoranKantor::where('admin_id', $userId)
            ->where('status', 'pending')
            ->count();

        // Mutasi terakhir admin (gabungan table PettyCashSaldo untuk melihat penambahan/pengurangan)
        $mutasiTerakhir = PettyCashSaldo::where('user_id', $userId)
            ->where('role', 'admin')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.petty-cash.admin-dashboard', compact(
            'saldoCash', 'saldoTransfer', 'isLowSaldo', 'alertMessage', 
            'mySetoranPending', 'mutasiTerakhir'
        ));
    }

    /**
     * [Owner] Daftar setoran kantor pending.
     * GET /admin/petty-cash/setoran-approval
     */
    public function setoranApprovalIndex(Request $request)
    {
        $query = PettyCashSetoranKantor::with('admin')->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'pending');
        }

        $setoran = $query->paginate(15);

        return view('admin.petty-cash.setoran-approval', compact('setoran'));
    }

    /**
     * [Owner] Detail setoran kantor.
     * GET /admin/petty-cash/setoran-approval/{id}
     */
    public function setoranApprovalDetail($id)
    {
        $setoran = PettyCashSetoranKantor::with(['admin', 'transaksiNasabah.nasabah.user'])
            ->findOrFail($id);

        return view('admin.petty-cash.setoran-approval-detail', compact('setoran'));
    }

    /**
     * [Owner] Approve setoran kantor → tambah saldo owner.
     * POST /admin/petty-cash/setoran-approval/{id}/approve
     */
    public function setoranApprovalApprove(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $setoran = PettyCashSetoranKantor::where('status', 'pending')->findOrFail($id);

            $setoran->update([
                'status'           => 'approved_owner',
                'owner_id'         => Auth::id(),
                'keterangan_owner' => $request->keterangan_owner,
                'tgl_approval'     => now(),
            ]);

            // 🔥 PEMISAHAN SALDO OWNER (Governance 2.0)
            $dataPotongan = collect($setoran->data_potongan);
            $nominalCash = $dataPotongan->filter(fn($t) => in_array($t['via_kode'], ['CS', 'TN']))->sum('nominal');
            $nominalTf   = $dataPotongan->filter(fn($t) => $t['via_kode'] === 'TF')->sum('nominal');

            if ($nominalCash > 0) {
                PettyCashSaldo::buatMutasi(
                    Auth::id(), 'owner', (float)$nominalCash,
                    "Terima Setoran Cash dari Admin {$setoran->admin->nama} (#{$id})",
                    $id, 'petty_cash_setoran_kantor', 'cash'
                );
            }

            if ($nominalTf > 0) {
                PettyCashSaldo::buatMutasi(
                    Auth::id(), 'owner', (float)$nominalTf,
                    "Terima Setoran TF dari Admin {$setoran->admin->nama} (#{$id})",
                    $id, 'petty_cash_setoran_kantor', 'transfer'
                );
            }

            // Catat di Transaksi Owner Utama
            PettyCashOwnerTransaksi::create([
                'id'              => IdGenerator::generate('petty_cash_owner_transaksi', 'PCOW', 'OW', 'TR'),
                'user_id'         => Auth::id(),
                'tipe'            => 'terima_setoran',
                'nominal_cash'    => $nominalCash,
                'nominal_tf'      => $nominalTf,
                'keterangan'      => "Terima Setoran dari Admin " . ($setoran->admin->nama ?? '-') . ". " . $request->keterangan_owner,
                'bukti_foto_cash' => $setoran->foto_setoran, // Gunakan foto setoran admin sebagai bukti
                'ref_id'          => $id,
                'ref_table'       => 'petty_cash_setoran_kantor',
            ]);

            PettyCashLog::catat(Auth::id(), 'approve_setoran_kantor', $setoran->total_setor, [
                'admin_id'   => $setoran->admin_id,
                'setoran_id' => $id,
            ], $id, 'petty_cash_setoran_kantor');

            DB::commit();

            return back()->with('success', 'Setoran disetujui. Saldo Anda bertambah Rp ' . number_format((float) $setoran->total_setor, 0, ',', '.'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * [Owner] Reject setoran kantor.
     * POST /admin/petty-cash/setoran-approval/{id}/reject
     */
    public function setoranApprovalReject(Request $request, $id)
    {
        $request->validate(['keterangan_owner' => 'required|string']);

        $setoran = PettyCashSetoranKantor::where('status', 'pending')->findOrFail($id);
        $setoran->update([
            'status'           => 'rejected',
            'keterangan_owner' => $request->keterangan_owner,
        ]);

        // 🔥 PEMISAHAN PENGEMBALIAN SALDO ADMIN
        $dataPotongan = collect($setoran->data_potongan);
        $nominalCash = $dataPotongan->filter(fn($t) => in_array($t['via_kode'], ['CS', 'TN']))->sum('nominal');
        $nominalTf = $dataPotongan->filter(fn($t) => $t['via_kode'] === 'TF')->sum('nominal');

        if ($nominalCash > 0) {
            PettyCashSaldo::buatMutasi(
                $setoran->admin_id, 'admin', (float) $nominalCash,
                "Setoran ditolak Owner, saldo Cash dikembalikan",
                $id, 'petty_cash_setoran_kantor', 'cash'
            );
        }

        if ($nominalTf > 0) {
            PettyCashSaldo::buatMutasi(
                $setoran->admin_id, 'admin', (float) $nominalTf,
                "Setoran ditolak Owner, saldo TF dikembalikan",
                $id, 'petty_cash_setoran_kantor', 'transfer'
            );
        }

        // Update transaksi nasabah: lepas dari setoran ini agar bisa disetor ulang
        $transaksis = PettyCashTransaksiNasabah::where('setoran_kantor_id', $id)->get();
        foreach ($transaksis as $t) {
            if ($t->ref_table === 'tbl_pengajuan_pembayaran_pinjaman' || $t->ref_table === 'tbl_pengajuan_tabungan') {
                DB::table($t->ref_table)->where('id', $t->ref_id)->update(['setoran_kantor_id' => null]);
            }
        }
        
        PettyCashTransaksiNasabah::where('setoran_kantor_id', $id)
            ->update(['setoran_kantor_id' => null]);

        return back()->with('success', 'Setoran ditolak dan saldo admin dikembalikan.');
    }

    // =========================================================================
    // OWNER: Main Wallet Management (CRUD)
    // =========================================================================

    /**
     * [Owner] Halaman kelola saldo utama.
     */
    public function ownerWalletIndex(Request $request)
    {
        $userId = Auth::id();
        $query = PettyCashOwnerTransaksi::where('user_id', $userId)->latest();

        if ($request->tipe) {
            $query->where('tipe', $request->tipe);
        }
        if ($request->tgl_dari) {
            $query->whereDate('created_at', '>=', $request->tgl_dari);
        }
        if ($request->tgl_sampai) {
            $query->whereDate('created_at', '<=', $request->tgl_sampai);
        }

        $transaksi = $query->paginate(15);
        $saldoCash = PettyCashSaldo::getSaldo($userId, 'owner', 'cash');
        $saldoTf   = PettyCashSaldo::getSaldo($userId, 'owner', 'transfer');

        return view('admin.petty-cash.owner-wallet', compact('transaksi', 'saldoCash', 'saldoTf'));
    }

    /**
     * [Owner] Simpan transaksi manual saldo utama (In/Out).
     */
    public function ownerWalletStore(Request $request)
    {
        $request->validate([
            'tipe'           => 'required|in:masuk,keluar',
            'nominal_cash'   => 'nullable|numeric|min:0',
            'nominal_tf'     => 'nullable|numeric|min:0',
            'keterangan'     => 'required|string|max:500',
            'bukti_foto_cash'=> 'nullable|image|max:5120',
            'bukti_foto_tf'  => 'nullable|image|max:5120',
        ]);

        $nominalCash = (float)($request->nominal_cash ?? 0);
        $nominalTf   = (float)($request->nominal_tf   ?? 0);
        $userId      = Auth::id();

        if (($nominalCash + $nominalTf) <= 0) {
            return back()->with('error', 'Nominal harus lebih dari 0.')->withInput();
        }

        // Validasi Saldo Keluar
        if ($request->tipe === 'keluar') {
            if ($nominalCash > PettyCashSaldo::getSaldo($userId, 'owner', 'cash')) {
                return back()->withErrors(['nominal_cash' => 'Saldo Cash tidak mencukupi untuk pengeluaran ini.'])->withInput();
            }
            if ($nominalTf > PettyCashSaldo::getSaldo($userId, 'owner', 'transfer')) {
                return back()->withErrors(['nominal_tf' => 'Saldo Transfer tidak mencukupi untuk pengeluaran ini.'])->withInput();
            }
        }

        try {
            DB::beginTransaction();

            $fotoCash = null;
            if ($request->hasFile('bukti_foto_cash')) {
                $fotoCash = $request->file('bukti_foto_cash')->store('petty_cash/owner/cash', 'public');
            }

            $fotoTf = null;
            if ($request->hasFile('bukti_foto_tf')) {
                $fotoTf = $request->file('bukti_foto_tf')->store('petty_cash/owner/tf', 'public');
            }

            $idTrans = IdGenerator::generate('petty_cash_owner_transaksi', 'PCOW', 'OW', 'TR');

            // 1. Simpan Log Transaksi Owner
            $transaksi = PettyCashOwnerTransaksi::create([
                'id'              => $idTrans,
                'user_id'         => $userId,
                'tipe'            => $request->tipe,
                'nominal_cash'    => $nominalCash,
                'nominal_tf'      => $nominalTf,
                'keterangan'      => $request->keterangan,
                'bukti_foto_cash' => $fotoCash,
                'bukti_foto_tf'   => $fotoTf,
            ]);

            // 2. Buat Mutasi Saldo (Cash)
            if ($nominalCash > 0) {
                $mutasi = ($request->tipe === 'masuk') ? $nominalCash : -$nominalCash;
                PettyCashSaldo::buatMutasi(
                    $userId, 'owner', $mutasi,
                    "Transaksi {$request->tipe}: {$request->keterangan}",
                    $idTrans, 'petty_cash_owner_transaksi', 'cash'
                );
            }

            // 3. Buat Mutasi Saldo (TF)
            if ($nominalTf > 0) {
                $mutasi = ($request->tipe === 'masuk') ? $nominalTf : -$nominalTf;
                PettyCashSaldo::buatMutasi(
                    $userId, 'owner', $mutasi,
                    "Transaksi {$request->tipe} (TF): {$request->keterangan}",
                    $idTrans, 'petty_cash_owner_transaksi', 'transfer'
                );
            }

            PettyCashLog::catat($userId, 'owner_manual_transaksi', $nominalCash + $nominalTf, [
                'type' => $request->tipe,
                'owner_trans_id' => $idTrans
            ], $idTrans, 'petty_cash_owner_transaksi');

            DB::commit();
            return redirect()->route('admin.petty-cash.owner-wallet.index')->with('success', 'Transaksi berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PettyCash ownerWalletStore error', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * [Owner] Hapus transaksi manual.
     */
    public function ownerWalletDestroy($id)
    {
        try {
            DB::beginTransaction();

            $transaksi = PettyCashOwnerTransaksi::findOrFail($id);
            $userId = $transaksi->user_id;

            // Ambil semua mutasi saldo yang terkait dengan transaksi ini
            $mutasis = PettyCashSaldo::where('ref_id', $id)
                ->where('ref_table', 'petty_cash_owner_transaksi')
                ->get();

            foreach ($mutasis as $m) {
                $nominalReversal = -$m->mutasi;
                $tipe = $m->tipe;
                $role = $m->role;

                // Hapus record saldo asli
                $m->delete();

                // Perbaiki saldo_akhir untuk semua record setelahnya (untuk user & tipe yang sama)
                $subsequent = PettyCashSaldo::where('user_id', $userId)
                    ->where('role', $role)
                    ->where('tipe', $tipe)
                    ->where('id', '>', $m->id)
                    ->get();

                foreach ($subsequent as $s) {
                    $s->decrement('saldo_akhir', (float)$m->mutasi);
                }
            }

            // Hapus Transaksi Utama
            $transaksi->delete();

            // Log
            PettyCashLog::catat($userId, 'owner_manual_transaksi_deleted', 0, [
                'deleted_id' => $id,
                'keterangan_asal' => $transaksi->keterangan
            ], $id, 'petty_cash_owner_transaksi');

            DB::commit();
            return back()->with('success', 'Transaksi berhasil dihapus dan saldo telah disesuaikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PettyCash ownerWalletDestroy error', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // LAPORAN
    // =========================================================================

    /**
     * [Owner/Admin] Laporan lengkap petty cash.
     * GET /admin/petty-cash/laporan
     */
    public function laporan(Request $request)
    {
        $query = PettyCashSetoranKantor::with([
            'admin', 
            'transaksiNasabah.transTabungan',
            'transaksiNasabah.pengajuanPembayaran',
            'transaksiNasabah.pengajuanTabungan'
        ])->latest();

        if ($request->admin_id) {
            $query->where('admin_id', $request->admin_id);
        }
        if ($request->tanggal_dari) {
            $query->whereDate('tgl_setoran', '>=', $request->tanggal_dari);
        }
        if ($request->tanggal_sampai) {
            $query->whereDate('tgl_setoran', '<=', $request->tanggal_sampai);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $laporan   = $query->paginate(20);
        $admins    = User::where('role', 'admin_operasional')->get();

        // ── Card Hijau: Penerimaan (Owner -> Admin) ──
        $penerimaanQuery = PettyCashPenerimaan::where('status', 'approved');
        // (Optional: Filter stats by dates/admin if wanted, but current code is global)
        $totalPenerimaan     = $penerimaanQuery->sum(DB::raw('nominal_tf + nominal_cash'));
        $totalPenerimaanCash = $penerimaanQuery->sum('nominal_cash');
        $totalPenerimaanTf   = $penerimaanQuery->sum('nominal_tf');

        // ── Card Biru: Setoran (Admin -> Owner) ──
        $setoranQuery = PettyCashSetoranKantor::where('status', 'approved_owner');
        $totalSetoran = $setoranQuery->sum('total_setor');
        
        // Untuk detail cash/tf setoran, kita ambil dari transaksi nasabah yang terkait
        $approvedSetoranIds = (clone $setoranQuery)->pluck('id');
        $totalSetoranCash = \App\Models\PettyCashTransaksiNasabah::whereIn('setoran_kantor_id', $approvedSetoranIds)
            ->where('id_jns_via', \App\Services\PettyCashConstants::VIA_CS)
            ->sum('nominal');
        $totalSetoranTf = \App\Models\PettyCashTransaksiNasabah::whereIn('setoran_kantor_id', $approvedSetoranIds)
            ->where('id_jns_via', \App\Services\PettyCashConstants::VIA_TF)
            ->sum('nominal');

        // ── Card Dompet Utama: Owner Internal (Manual In/Out) ──
        $manualQuery = \App\Models\PettyCashOwnerTransaksi::where('user_id', Auth::id());
        if ($request->tanggal_dari) {
            $manualQuery->whereDate('created_at', '>=', $request->tanggal_dari);
        }
        if ($request->tanggal_sampai) {
            $manualQuery->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        $manualIn   = (clone $manualQuery)->where('tipe', 'masuk')->sum(DB::raw('nominal_cash + nominal_tf'));
        $manualOut  = (clone $manualQuery)->where('tipe', 'keluar')->sum(DB::raw('nominal_cash + nominal_tf'));

        // Saldo Saat Ini (Untuk Rekonsiliasi)
        $currentSaldoCash = \App\Models\PettyCashSaldo::getSaldo(Auth::id(), 'owner', 'cash');
        $currentSaldoTf   = \App\Models\PettyCashSaldo::getSaldo(Auth::id(), 'owner', 'transfer');

        return view('admin.petty-cash.laporan', compact(
            'laporan', 'admins', 
            'totalPenerimaan', 'totalPenerimaanCash', 'totalPenerimaanTf', 
            'totalSetoran', 'totalSetoranCash', 'totalSetoranTf',
            'manualIn', 'manualOut', 'currentSaldoCash', 'currentSaldoTf'
        ));
    }
}
