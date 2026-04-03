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
    public function penerimaanCreate()
    {
        $admins = User::where('role', 'admin_operasional')->get();
        return view('admin.petty-cash.penerimaan-create', compact('admins'));
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

        PettyCashPenerimaan::create([
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

        PettyCashLog::catat(Auth::id(), 'kirim_dana_ke_admin', $nominalTf + $nominalCash, [
            'admin_id' => $request->admin_id,
            'penerimaan_id' => $id,
        ], $id, 'petty_cash_penerimaan');

        return redirect()->route('admin.petty-cash.dashboard')
            ->with('success', 'Dana berhasil dikirim. Menunggu konfirmasi Admin.');
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

        return back()->with('success', 'Penerimaan ditolak.');
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
                'pctn_id'     => $t->id,
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
            $saldoOwner = PettyCashSaldo::getSaldo($userId, 'owner');
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
                'saldoOwner', 'pendingSetoran', 'admins', 'setoranPending', 'grafik'
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

            // Tambah saldo owner (Owner hanya punya 1 saldo total)
            PettyCashSaldo::buatMutasi(
                Auth::id(), 'owner', (float) $setoran->total_setor,
                "Setoran dari Admin {$setoran->admin->nama} (#{$id})",
                $id, 'petty_cash_setoran_kantor', 'cash'
            );

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
        PettyCashTransaksiNasabah::where('setoran_kantor_id', $id)
            ->update(['setoran_kantor_id' => null]);

        return back()->with('success', 'Setoran ditolak dan saldo admin dikembalikan.');
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
        $query = PettyCashSetoranKantor::with(['admin'])->latest();

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

        $totalPenerimaan = PettyCashPenerimaan::where('status', 'approved')
            ->sum(DB::raw('nominal_tf + nominal_cash'));
        $totalSetoran    = PettyCashSetoranKantor::where('status', 'approved_owner')
            ->sum('total_setor');

        return view('admin.petty-cash.laporan', compact('laporan', 'admins', 'totalPenerimaan', 'totalSetoran'));
    }
}
