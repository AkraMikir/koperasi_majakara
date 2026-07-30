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

        $sourceDetails = DB::table('vw_saldo_owner_detail')
            ->where('user_id', Auth::id())
            ->select('sumber', 
                DB::raw('SUM(nominal_cash) as total_cash'),
                DB::raw('SUM(nominal_tf) as total_tf')
            )
            ->groupBy('sumber')
            ->get()
            ->keyBy('sumber')
            ->toArray();
        
        if ($request->ajax()) {
            return response()->json([
                'html'       => view('admin.petty-cash.partials._penerimaan_table', compact('pengiriman'))->render(),
                'stats'      => $stats,
                'pagination' => $pengiriman->links()->render()
            ]);
        }

        return view('admin.petty-cash.penerimaan-owner', compact('pengiriman', 'stats', 'admins', 'saldoCash', 'saldoTf', 'sourceDetails'));
    }

    /**
     * [Owner] Submit pengiriman dana.
     * POST /admin/petty-cash/penerimaan
     */
    public function penerimaanStore(Request $request)
    {
        $request->validate([
            'admin_id'     => 'required|exists:users,id',
            'sumber'       => 'required|in:other', // Force Modal Awal
            'nominal_tf'   => 'nullable|numeric|min:0',
            'nominal_cash' => 'nullable|numeric|min:0',
            'bukti_tf'     => 'nullable|image|max:5120',
            'foto_cash'    => 'nullable|image|max:5120',
            'keterangan'   => 'nullable|string|max:500',
        ]);

        $nominalTf   = (float) ($request->nominal_tf   ?? 0);
        $nominalCash = (float) ($request->nominal_cash ?? 0);
        $sumber      = $request->sumber;

        // Anti Double Submit Protection (Delay 2 Detik)
        $lastSubmit = PettyCashPenerimaan::where('owner_id', Auth::id())
            ->where('admin_id', $request->admin_id)
            ->where('nominal_tf', $nominalTf)
            ->where('nominal_cash', $nominalCash)
            ->where('created_at', '>=', now()->subSeconds(2))
            ->first();

        if ($lastSubmit) {
            return back()->with('error', 'Terdeteksi pengiriman ganda. Mohon tunggu beberapa saat sebelum mengirim lagi.');
        }

        if (($nominalTf + $nominalCash) <= 0) {
            return back()->withErrors(['nominal_tf' => 'Minimal satu nominal harus diisi.'])->withInput();
        }

        $ownerId = Auth::id();
        $sourceDetails = DB::table('vw_saldo_owner_detail')
            ->where('user_id', $ownerId)
            ->where('sumber', $sumber) 
            ->select(
                DB::raw('SUM(nominal_cash) as total_cash'),
                DB::raw('SUM(nominal_tf) as total_tf')
            )
            ->first();

        if ($nominalCash > (float)$sourceDetails->total_cash) {
            return back()->withErrors(['nominal_cash' => "Saldo Tunai untuk sumber ini tidak mencukupi (Max: " . number_format($sourceDetails->total_cash, 0, ',', '.') . ")"])->withInput();
        }
        if ($nominalTf > (float)$sourceDetails->total_tf) {
            return back()->withErrors(['nominal_tf' => "Saldo Transfer untuk sumber ini tidak mencukupi (Max: " . number_format($sourceDetails->total_tf, 0, ',', '.') . ")"])->withInput();
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

            $penerimaan = PettyCashPenerimaan::create([
                'id'          => $id,
                'owner_id'    => $ownerId,
                'admin_id'    => $request->admin_id,
                'sumber'      => $sumber,
                'nominal_tf'  => $nominalTf,
                'nominal_cash'=> $nominalCash,
                'bukti_tf'    => $buktiFoto,
                'foto_cash'   => $fotoCash,
                'keterangan'  => $request->keterangan,
                'status'      => 'pending',
            ]);

            // 🔥 POTONG SALDO OWNER (HOLD) - Tetap gunakan buatMutasi global karena view saldo detail akan menghitungnya berdasarkan PettyCashOwnerTransaksi
            if ($nominalCash > 0) {
                PettyCashSaldo::buatMutasi(
                    $ownerId, 'owner', -$nominalCash,
                    "Kirim Dana ke Admin ({$penerimaan->admin->nama}) - HOLD",
                    $id, 'petty_cash_penerimaan', 'cash', $sumber
                );
            }
            if ($nominalTf > 0) {
                PettyCashSaldo::buatMutasi(
                    $ownerId, 'owner', -$nominalTf,
                    "Kirim Transfer ke Admin ({$penerimaan->admin->nama}) - HOLD",
                    $id, 'petty_cash_penerimaan', 'transfer', $sumber
                );
            }

            // Catat di Transaksi Owner Utama (Ini yang muncul di Dashboard History)
            PettyCashOwnerTransaksi::create([
                'id'              => IdGenerator::generate('petty_cash_owner_transaksi', 'PCOW', 'OW', 'TR'),
                'user_id'         => $ownerId,
                'tipe'            => 'kirim_admin_hold',
                'sumber'          => $sumber,
                'nominal_cash'    => $nominalCash,
                'nominal_tf'      => $nominalTf,
                'keterangan'      => "Kirim ke Admin: " . ($penerimaan->admin->nama ?? '-') . ". " . $request->keterangan,
                'bukti_foto_cash' => $fotoCash,
                'bukti_foto_tf'   => $buktiFoto,
                'ref_id'          => $id,
                'ref_table'       => 'petty_cash_penerimaan',
            ]);

            PettyCashLog::catat($ownerId, 'kirim_dana_ke_admin', $nominalTf + $nominalCash, [
                'admin_id' => $request->admin_id,
                'penerimaan_id' => $id,
                'sumber' => $sumber
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
        
        // 🛡️ Menghitung Saldo Modal Awal dari SUM mutasi agar menghitung dari semua data yang ada
        $saldoCash = PettyCashSaldo::where('user_id', Auth::id())
            ->where('role', 'admin')
            ->where('tipe', 'cash')
            ->where('sumber', 'other')
            ->sum('mutasi');
            
        $saldoTf = PettyCashSaldo::where('user_id', Auth::id())
            ->where('role', 'admin')
            ->where('tipe', 'transfer')
            ->where('sumber', 'other')
            ->sum('mutasi');
            
        $saldoAdmin = $saldoCash + $saldoTf;

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
                    $penerimaan->id, 'petty_cash_penerimaan', 'transfer', $penerimaan->sumber
                );
                $nominalTotal += (float)$penerimaan->nominal_tf;
            }

            if ($penerimaan->nominal_cash > 0) {
                PettyCashSaldo::buatMutasi(
                    Auth::id(), 'admin', (float)$penerimaan->nominal_cash,
                    "Penerimaan Cash dari Owner ({$penerimaan->owner->nama})",
                    $penerimaan->id, 'petty_cash_penerimaan', 'cash', $penerimaan->sumber
                );
                $nominalTotal += (float)$penerimaan->nominal_cash;
            }

            PettyCashLog::catat(Auth::id(), 'approve_penerimaan', $nominalTotal, [
                'owner_id'        => $penerimaan->owner_id,
                'penerimaan_id'   => $id,
                'detil_tf'        => $penerimaan->nominal_tf,
                'detil_cash'      => $penerimaan->nominal_cash,
                'sumber'          => $penerimaan->sumber,
            ], $id, 'petty_cash_penerimaan');

            //  DEPOSITO INTEGRATION
            if ($penerimaan->sumber === 'deposito' && $penerimaan->ref_id) {
                // Link ke PencairanDeposito (Langsung)
                \App\Models\PencairanDeposito::where('id', $penerimaan->ref_id)
                    ->whereIn('status', ['pending', 'diproses'])
                    ->update(['status' => 'diproses']);

                // Link ke DepositoPersiapanCair (Proaktif)
                \App\Models\DepositoPersiapanCair::where('id', $penerimaan->ref_id)
                    ->whereIn('status', ['tentatif', 'diproses'])
                    ->update(['status' => 'diproses']);
            }

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
                $id, 'petty_cash_penerimaan', 'cash', $penerimaan->sumber
            );
        }
        if ($penerimaan->nominal_tf > 0) {
            PettyCashSaldo::buatMutasi(
                $penerimaan->owner_id, 'owner', (float)$penerimaan->nominal_tf,
                "REFUND: Penolakan transfer oleh Admin ({$penerimaan->admin->nama})",
                $id, 'petty_cash_penerimaan', 'transfer', $penerimaan->sumber
            );
        }

        // Catat di Transaksi Owner Utama (Refund)
        PettyCashOwnerTransaksi::create([
            'id'              => IdGenerator::generate('petty_cash_owner_transaksi', 'PCOW', 'OW', 'TR'),
            'user_id'         => $penerimaan->owner_id,
            'tipe'            => 'masuk', // Kembali masuk
            'sumber'          => $penerimaan->sumber, // Kembalikan ke sumber asal
            'nominal_cash'    => $penerimaan->nominal_cash,
            'nominal_tf'      => $penerimaan->nominal_tf,
            'keterangan'      => "REFUND: Dana ditolak oleh Admin " . ($penerimaan->admin->nama ?? '-') . ". " . $request->keterangan_admin,
            'ref_id'          => $id,
            'ref_table'       => 'petty_cash_penerimaan',
        ]);

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
        // 🛡️ Tugas 14: Exclude pencairan pinjaman (ref_table='tbl_pinjaman_h') — itu uang KELUAR, bukan MASUK ke kantor
        $transaksiPending = PettyCashTransaksiNasabah::with(['nasabah.user', 'jnsVia', 'jnsTransaksi', 'jnsFitur'])
            ->where('admin_id', $adminId)
            ->where('status', 'approved')
            ->whereNull('setoran_kantor_id')
            ->where('ref_table', '!=', 'tbl_pinjaman_h')
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
            'tipe_setoran'       => 'required|in:transaksi,manual',
            'transaksi_ids'      => 'nullable|array',
            'manual_cash'        => 'nullable|numeric|min:0',
            'manual_tf'          => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $adminId = Auth::id();
            $tipeSetoran = $request->tipe_setoran;

            $transaksi = collect([]);
            $dataPotongan = [];
            $jumlahNasabah = 0;
            $totalSetor = 0;
            $nominalCash = 0;
            $nominalTf = 0;

            if ($tipeSetoran === 'transaksi') {
                $transaksiIds = $request->transaksi_ids ?? [];
                if (empty($transaksiIds)) {
                    return back()->with('error', 'Pilih minimal 1 transaksi untuk disetor.');
                }

                $transaksi = PettyCashTransaksiNasabah::with(['nasabah.user', 'jnsVia', 'jnsTransaksi', 'jnsFitur'])
                    ->where('admin_id', $adminId)
                    ->where('status', 'approved')
                    ->whereNull('setoran_kantor_id')
                    ->whereIn('id', $transaksiIds)
                    ->get();

                if ($transaksi->isEmpty()) {
                    return back()->with('error', 'Transaksi yang dipilih tidak valid atau sudah disetor.');
                }

                $totalSetor = $transaksi->sum('nominal');
                $jumlahNasabah = $transaksi->count();
                $nominalCash = $transaksi->filter(fn($t) => in_array($t->jnsVia?->kode, ['CS', 'TN']))->sum('nominal');
                $nominalTf = $transaksi->filter(fn($t) => $t->jnsVia?->kode === 'TF')->sum('nominal');

                $dataPotongan = $transaksi->map(fn($t) => [
                    'nasabah_id'  => $t->nasabah_id,
                    'nama'        => $t->nasabah->user->nama ?? '-',
                    'nominal'     => (float) $t->nominal,
                    'transaksi'   => $t->jnsTransaksi?->nama ?? '-',
                    'via'         => $t->jnsVia?->nama ?? '-',
                    'via_kode'    => $t->jnsVia?->kode ?? '-',
                    'fitur'       => $t->jnsFitur?->nama ?? '-',
                    'fitur_id'    => $t->id_jns_fitur,
                    'jenis_transaksi' => $t->jenis_transaksi,
                    'pctn_id'     => $t->id,
                    'ref_id'      => $t->ref_id,
                ])->values()->toArray();

            } else {
                $nominalCash = (float) $request->manual_cash;
                $nominalTf = (float) $request->manual_tf;
                $totalSetor = $nominalCash + $nominalTf;

                if ($totalSetor <= 0) {
                    return back()->with('error', 'Nominal setor manual tidak boleh 0.');
                }

                $saldoCash = PettyCashSaldo::getSaldoCash($adminId);
                $saldoTransfer = PettyCashSaldo::getSaldoTransfer($adminId);

                if ($nominalCash > $saldoCash || $nominalTf > $saldoTransfer) {
                    return back()->with('error', 'Saldo Anda tidak mencukupi untuk setor manual sebesar itu.');
                }

                // Populate data_potongan for manual setoran to show in detail rows
                if ($nominalCash > 0) {
                    $dataPotongan[] = [
                        'nama'      => 'Setoran Manual (Tunai)',
                        'nominal'   => $nominalCash,
                        'via'       => 'Cash',
                        'via_kode'  => 'CS',
                        'fitur'     => 'Manual',
                        'transaksi' => 'Manual',
                    ];
                }
                if ($nominalTf > 0) {
                    $dataPotongan[] = [
                        'nama'      => 'Setoran Manual (Transfer)',
                        'nominal'   => $nominalTf,
                        'via'       => 'Transfer',
                        'via_kode'  => 'TF',
                        'fitur'     => 'Manual',
                        'transaksi' => 'Manual',
                    ];
                }
            }

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
                'nominal_cash'      => $nominalCash,
                'nominal_tf'        => $nominalTf,
                'data_potongan'     => $dataPotongan,
                'jumlah_nasabah'    => $jumlahNasabah,
                'foto_setoran'      => $fotoSetoran,
                'sudah_setor_fisik' => (bool) $request->sudah_setor_fisik,
                'status'            => 'pending',
                'keterangan_admin'  => $request->keterangan_admin,
            ]);

            if ($transaksi->isNotEmpty()) {
                // Update transaksi: tandai sudah masuk setoran ini
                PettyCashTransaksiNasabah::whereIn('id', $transaksi->pluck('id'))
                    ->update(['setoran_kantor_id' => $idSetoran]);
                    
                foreach ($transaksi as $t) {
                    if ($t->ref_table === 'tbl_pengajuan_pembayaran_pinjaman' || $t->ref_table === 'tbl_pengajuan_tabungan') {
                        DB::table($t->ref_table)->where('id', $t->ref_id)->update(['setoran_kantor_id' => $idSetoran]);
                    }
                }
            }

            // 🔥 PEMISAHAN PENGURANGAN SALDO ADMIN (Governance 2.0)
            $groupedAdmin = [];
            foreach ($transaksi as $t) {
                $fiturId = $t->id_jns_fitur;
                $sumber = match ($fiturId) {
                    \App\Services\PettyCashConstants::FITUR_TABUNGAN => \App\Services\PettyCashConstants::SUMBER_TABUNGAN,
                    \App\Services\PettyCashConstants::FITUR_PINJAMAN => \App\Services\PettyCashConstants::SUMBER_PINJAMAN,
                    \App\Services\PettyCashConstants::FITUR_DEPOSITO => \App\Services\PettyCashConstants::SUMBER_DEPOSITO,
                    \App\Services\PettyCashConstants::FITUR_GADAI    => \App\Services\PettyCashConstants::SUMBER_GADAI,
                    default => \App\Services\PettyCashConstants::SUMBER_LAIN,
                };
                
                // Cek via
                $via = DB::table('jns_via')->where('id', $t->id_jns_via)->value('kode');
                $tipe = ($via === 'TF') ? 'transfer' : 'cash';

                if (!isset($groupedAdmin[$sumber][$tipe])) {
                    $groupedAdmin[$sumber][$tipe] = 0;
                }
                $groupedAdmin[$sumber][$tipe] += (float)$t->nominal;
            }

            // Jika setoran manual tanpa detail (Setor Manual tab)
            if (empty($groupedAdmin)) {
                if ($nominalCash > 0) $groupedAdmin[\App\Services\PettyCashConstants::SUMBER_LAIN]['cash'] = $nominalCash;
                if ($nominalTf > 0) $groupedAdmin[\App\Services\PettyCashConstants::SUMBER_LAIN]['transfer'] = $nominalTf;
            }

            foreach ($groupedAdmin as $sumber => $tipes) {
                foreach ($tipes as $tipe => $nominal) {
                    if ($nominal > 0) {
                        PettyCashSaldo::buatMutasi(
                            $adminId, 'admin', -$nominal,
                            "Setor {$tipe} ({$sumber}) ke Kantor (#{$idSetoran})",
                            $idSetoran, 'petty_cash_setoran_kantor', $tipe, $sumber
                        );
                    }
                }
            }

            PettyCashLog::catat($adminId, 'setor_ke_kantor', $totalSetor, [
                'setoran_id'     => $idSetoran,
                'tipe_setoran'   => $tipeSetoran,
                'jml_nasabah'    => $jumlahNasabah,
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
            // Gunakan vw_saldo_owner_detail (SUM semua mutasi per sumber) agar
            // konsisten dengan halaman Kelola Saldo Utama — bukan getSaldo()
            // yang hanya mengambil record terakhir tanpa filter sumber.
            $ownerTotals = DB::table('vw_saldo_owner_detail')
                ->where('user_id', $userId)
                ->selectRaw('SUM(nominal_cash) as total_cash, SUM(nominal_tf) as total_tf')
                ->first();

            $saldoOwnerCash  = (float) ($ownerTotals->total_cash ?? 0);
            $saldoOwnerTf    = (float) ($ownerTotals->total_tf   ?? 0);
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
        $userId = Auth::id();
        
        // 1. Saldo Modal Awal (Saku yang bisa digunakan untuk pengeluaran)
        $saldoModalCash = PettyCashSaldo::getSaldo($userId, 'admin', 'cash', 'other');
        $saldoModalTf   = PettyCashSaldo::getSaldo($userId, 'admin', 'transfer', 'other');

        // 2. Detail Saldo Clearing per Produk (Dana nasabah yang menunggu setor)
        $clearingDetails = [];
        $sources = [
            'tabungan' => 'Tabungan',
            'pinjaman' => 'Pinjaman',
            'deposito' => 'Deposito',
            'gadai'    => 'Gadai',
            'petty_cash' => 'Lainnya'
        ];

        foreach ($sources as $key => $label) {
            $cash = PettyCashSaldo::getSaldo($userId, 'admin', 'cash', $key);
            $tf   = PettyCashSaldo::getSaldo($userId, 'admin', 'transfer', $key);
            
            $clearingDetails[$key] = [
                'label' => $label,
                'cash'  => $cash,
                'tf'    => $tf,
                'total' => $cash + $tf
            ];
        }

        // Total Clearing untuk Ringkasan Utama
        $saldoClearingCash = collect($clearingDetails)->sum('cash');
        $saldoClearingTf   = collect($clearingDetails)->sum('tf');

        $isLowSaldo = ($saldoModalCash < 500000);
        $alertMessage = $isLowSaldo ? 'MODAL CASH RENDAH!' : 'OK';

        $mySetoranPending = PettyCashSetoranKantor::where('admin_id', $userId)
            ->where('status', 'pending')
            ->count();

        // Mutasi terakhir admin
        $mutasiTerakhir = PettyCashSaldo::where('user_id', $userId)
            ->where('role', 'admin')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.petty-cash.admin-dashboard', compact(
            'saldoModalCash', 'saldoModalTf', 
            'saldoClearingCash', 'saldoClearingTf',
            'clearingDetails',
            'isLowSaldo', 'alertMessage', 
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

            // 🔥 PEMISAHAN SALDO OWNER BERDASARKAN SUMBER (Governance 2.0)
            $dataPotongan = $setoran->data_potongan ?? [];
            $grouped = [];

            foreach ($dataPotongan as $item) {
                $fiturId = $item['fitur_id'] ?? null;
                $sumber = match ($fiturId) {
                    \App\Services\PettyCashConstants::FITUR_TABUNGAN => \App\Services\PettyCashConstants::SUMBER_TABUNGAN,
                    \App\Services\PettyCashConstants::FITUR_PINJAMAN => \App\Services\PettyCashConstants::SUMBER_PINJAMAN,
                    \App\Services\PettyCashConstants::FITUR_DEPOSITO => \App\Services\PettyCashConstants::SUMBER_DEPOSITO,
                    \App\Services\PettyCashConstants::FITUR_GADAI    => \App\Services\PettyCashConstants::SUMBER_GADAI,
                    default => \App\Services\PettyCashConstants::SUMBER_LAIN,
                };

                if (!isset($grouped[$sumber])) {
                    $grouped[$sumber] = ['cash' => 0, 'tf' => 0];
                }

                if (($item['via_kode'] ?? '') === 'TF') {
                    $grouped[$sumber]['tf'] += (float)$item['nominal'];
                } else {
                    $grouped[$sumber]['cash'] += (float)$item['nominal'];
                }
            }

            // Jika setoran manual tanpa detail, pastikan tetap tercatat
            if (empty($grouped) && ($setoran->nominal_cash > 0 || $setoran->nominal_tf > 0)) {
                $grouped[\App\Services\PettyCashConstants::SUMBER_LAIN] = [
                    'cash' => (float)$setoran->nominal_cash,
                    'tf'   => (float)$setoran->nominal_tf
                ];
            }

            foreach ($grouped as $sumber => $nominal) {
                if ($nominal['cash'] > 0) {
                    PettyCashSaldo::buatMutasi(
                        Auth::id(), 'owner', (float)$nominal['cash'],
                        "Terima Setoran Cash ({$sumber}) dari Admin {$setoran->admin->nama} (#{$id})",
                        $id, 'petty_cash_setoran_kantor', 'cash', $sumber
                    );
                }

                if ($nominal['tf'] > 0) {
                    PettyCashSaldo::buatMutasi(
                        Auth::id(), 'owner', (float)$nominal['tf'],
                        "Terima Setoran TF ({$sumber}) dari Admin {$setoran->admin->nama} (#{$id})",
                        $id, 'petty_cash_setoran_kantor', 'transfer', $sumber
                    );
                }

                // Catat di Transaksi Owner Utama per Sumber
                PettyCashOwnerTransaksi::create([
                    'id'              => IdGenerator::generate('petty_cash_owner_transaksi', 'PCOW', 'OW', 'TR'),
                    'user_id'         => Auth::id(),
                    'tipe'            => 'terima_setoran',
                    'sumber'          => $sumber,
                    'nominal_cash'    => $nominal['cash'],
                    'nominal_tf'      => $nominal['tf'],
                    'keterangan'      => "Terima Setoran [{$sumber}] dari Admin " . ($setoran->admin->nama ?? '-') . ". " . $request->keterangan_owner,
                    'bukti_foto_cash' => $setoran->foto_setoran,
                    'ref_id'          => $id,
                    'ref_table'       => 'petty_cash_setoran_kantor',
                ]);
            }

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

        try {
            DB::beginTransaction();

            $setoran = PettyCashSetoranKantor::where('status', 'pending')->findOrFail($id);
            $setoran->update([
                'status'           => 'rejected',
                'keterangan_owner' => $request->keterangan_owner,
            ]);

            // 🔥 PEMISAHAN PENGEMBALIAN SALDO ADMIN BERDASARKAN SUMBER
            $dataPotongan = $setoran->data_potongan ?? [];
            $grouped = [];

            foreach ($dataPotongan as $item) {
                $fiturId = $item['fitur_id'] ?? null;
                $sumber = match ($fiturId) {
                    \App\Services\PettyCashConstants::FITUR_TABUNGAN => \App\Services\PettyCashConstants::SUMBER_TABUNGAN,
                    \App\Services\PettyCashConstants::FITUR_PINJAMAN => \App\Services\PettyCashConstants::SUMBER_PINJAMAN,
                    \App\Services\PettyCashConstants::FITUR_DEPOSITO => \App\Services\PettyCashConstants::SUMBER_DEPOSITO,
                    \App\Services\PettyCashConstants::FITUR_GADAI    => \App\Services\PettyCashConstants::SUMBER_GADAI,
                    default => \App\Services\PettyCashConstants::SUMBER_LAIN,
                };

                if (!isset($grouped[$sumber])) {
                    $grouped[$sumber] = ['cash' => 0, 'tf' => 0];
                }

                if (($item['via_kode'] ?? '') === 'TF') {
                    $grouped[$sumber]['tf'] += (float)$item['nominal'];
                } else {
                    $grouped[$sumber]['cash'] += (float)$item['nominal'];
                }
            }

            // Jika setoran manual tanpa detail
            if (empty($grouped) && ($setoran->nominal_cash > 0 || $setoran->nominal_tf > 0)) {
                $grouped[\App\Services\PettyCashConstants::SUMBER_LAIN] = [
                    'cash' => (float)$setoran->nominal_cash,
                    'tf'   => (float)$setoran->nominal_tf
                ];
            }

            foreach ($grouped as $sumber => $nominal) {
                if ($nominal['cash'] > 0) {
                    PettyCashSaldo::buatMutasi(
                        $setoran->admin_id, 'admin', (float)$nominal['cash'],
                        "Setoran Cash ({$sumber}) ditolak Owner, saldo dikembalikan (#{$id})",
                        $id, 'petty_cash_setoran_kantor', 'cash', $sumber
                    );
                }

                if ($nominal['tf'] > 0) {
                    PettyCashSaldo::buatMutasi(
                        $setoran->admin_id, 'admin', (float)$nominal['tf'],
                        "Setoran TF ({$sumber}) ditolak Owner, saldo dikembalikan (#{$id})",
                        $id, 'petty_cash_setoran_kantor', 'transfer', $sumber
                    );
                }
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

            DB::commit();

            return back()->with('success', 'Setoran ditolak dan saldo admin dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
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
        
        // Untuk detail cash/tf setoran
        $totalSetoranCash = (clone $setoranQuery)->sum('nominal_cash');
        $totalSetoranTf   = (clone $setoranQuery)->sum('nominal_tf');

        // ── Card Dompet Utama: Owner Internal (Manual In/Out) ──
        $manualQuery = \App\Models\PettyCashOwnerTransaksi::where('user_id', Auth::id());
        if ($request->tanggal_dari) {
            $manualQuery->whereDate('created_at', '>=', $request->tanggal_dari);
        }
        if ($request->tanggal_sampai) {
            $manualQuery->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        $manualIn   = (clone $manualQuery)->where('tipe', 'masuk')
            ->whereNull('ref_table')
            ->sum(DB::raw('nominal_cash + nominal_tf'));
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

    // =========================================================================
    // DEPOSITO: Pencairan via Petty Cash Operator
    // =========================================================================

    /**
     * [Admin] Konfirmasi menerima dana deposito dari Owner untuk diserahkan ke nasabah.
     * Dipanggil setelah Owner membuat PettyCashPenerimaan dengan sumber='deposito'.
     * POST /admin/petty-cash/penerimaan/{id}/approve-deposito
     */
    public function approvePenerimaanDeposito(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $penerimaan = PettyCashPenerimaan::where('admin_id', Auth::id())
                ->where('status', 'pending')
                ->where('sumber', 'deposito')
                ->findOrFail($id);

            $penerimaan->update([
                'status'           => 'approved',
                'keterangan_admin' => $request->keterangan_admin,
            ]);

            // Tambah saldo Admin (tunai yang diterima dari Owner) — sumber='deposito' untuk audit trail
            if ($penerimaan->nominal_cash > 0) {
                PettyCashSaldo::buatMutasi(
                    Auth::id(), 'admin', (float) $penerimaan->nominal_cash,
                    "Terima Dana Deposito dari Owner untuk Pencairan Tunai",
                    $penerimaan->id, 'petty_cash_penerimaan', 'cash', 'deposito'
                );
            }

            // Jika ada ref_id, update status penyiapan/pencairan
            if ($penerimaan->ref_id) {
                // Link ke PencairanDeposito
                \App\Models\PencairanDeposito::where('id', $penerimaan->ref_id)
                    ->where('status', 'pending')
                    ->update(['status' => 'diproses']);

                // Link ke DepositoPersiapanCair
                \App\Models\DepositoPersiapanCair::where('id', $penerimaan->ref_id)
                    ->where('status', 'tentatif')
                    ->update(['status' => 'diproses']);
            }

            PettyCashLog::catat(Auth::id(), 'approve_penerimaan_deposito', (float) $penerimaan->nominal_cash, [
                'penerimaan_id' => $id,
                'ref_id'        => $penerimaan->ref_id,
            ], $id, 'petty_cash_penerimaan');

            DB::commit();

            return back()->with('success', 'Dana deposito diterima. Silakan serahkan tunai ke nasabah.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('approvePenerimaanDeposito error', ['id' => $id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * [Admin] Proses penyerahan uang tunai deposito ke nasabah (Cash Out).
     * Dipanggil setelah Admin approve penerimaan dan nasabah datang.
     * POST /admin/deposito/pencairan/petty-cash/{id}/serahkan
     */
    public function pencairanDepositoCash(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $pencairan = \App\Models\PencairanDeposito::with(['deposito', 'nasabah.user'])
                ->where('jenis_pencairan', 'petty_cash_operator')
                ->where('status', 'diproses')
                ->findOrFail($id);

            $nominal = (float) $pencairan->nominal_akhir;

            // Validasi saldo Admin (bucket deposito) mencukupi
            if (!PettyCashSaldo::validatePenarikan(Auth::id(), $nominal, 'cash', 'deposito')) {
                $saldoAdmin = PettyCashSaldo::getSaldo(Auth::id(), 'admin', 'cash', 'deposito');
                return back()->with('error',
                    'Saldo CASH Dana Deposito Anda tidak mencukupi. Saldo: Rp ' . number_format($saldoAdmin, 0, ',', '.') .
                    ', Dibutuhkan: Rp ' . number_format($nominal, 0, ',', '.')
                );
            }

            // Ambil id fitur deposito
            $idJnsFitur = DB::table('jns_fitur')->where('kode', 'DP')->value('id')
                ?? DB::table('jns_fitur')->first()?->id;
            $idJnsVia   = DB::table('jns_via')->where('kode', 'CS')->value('id');
            $idJnsTrans = DB::table('jns_transaksi')->where('kode', 'PCR')->value('id')
                ?? DB::table('jns_transaksi')->where('kode', 'PNR')->value('id');

            $pctnId = \App\Helpers\IdGenerator::generate('petty_cash_transaksi_nasabah', 'T', 'CS', 'PCR');

            // Catat di PettyCashTransaksiNasabah (KELUAR dari Admin ke nasabah)
            PettyCashTransaksiNasabah::create([
                'id'               => $pctnId,
                'admin_id'         => Auth::id(),
                'nasabah_id'       => $pencairan->id_nasabah,
                'id_jns_transaksi' => $idJnsTrans,
                'id_jns_via'       => $idJnsVia,
                'id_jns_fitur'     => $idJnsFitur,
                'nominal'          => $nominal,
                'status'           => 'approved',
                'keterangan'       => 'Pencairan Deposito ' . $pencairan->deposito->nomor_deposito . ' - Tunai',
                'ref_table'        => 'tbl_pencairan_deposito',
                'ref_id'           => $pencairan->id,
                'tgl_transaksi'    => now(),
            ]);

            // Kurangi saldo Admin (tunai keluar ke nasabah) — sumber='deposito' konsisten dengan penerimaan
            PettyCashSaldo::buatMutasi(
                Auth::id(), 'admin', -$nominal,
                'Pencairan Deposito ' . $pencairan->deposito->nomor_deposito . ' - Tunai ke Nasabah',
                $pctnId, 'petty_cash_transaksi_nasabah', 'cash', 'deposito'
            );

            // Catat di TransDeposito
            \App\Models\TransDeposito::create([
                'deposito_id'   => $pencairan->deposito_id,
                'jenis'         => 'pencairan',
                'nominal'       => $nominal,
                'keterangan'    => 'Pencairan via Petty Cash (Tunai) - ' . ($request->catatan ?? ''),
                'tgl_transaksi' => now(),
            ]);

            // Update PencairanDeposito → selesai
            $pencairan->update([
                'catatan'     => $request->catatan,
                'status'      => 'selesai',
                'approved_by' => Auth::id(),
            ]);

            // Update DepositoH → dicairkan
            $pencairan->deposito->update(['status' => 'dicairkan']);

            // Sinkronisasi deposito_persiapan_cair
            \App\Models\DepositoPersiapanCair::where('deposito_id', $pencairan->deposito_id)
                ->whereIn('status', ['tentatif', 'diproses'])
                ->update(['status' => 'selesai', 'pencairan_id' => $pencairan->id]);

            PettyCashLog::catat(Auth::id(), 'pencairan_deposito_cash', $nominal, [
                'pencairan_id' => $pencairan->id,
                'nasabah_id'   => $pencairan->id_nasabah,
            ], (string) $pencairan->id, 'tbl_pencairan_deposito');

            DB::commit();

            // Notifikasi nasabah
            \App\Models\NasabahNotification::notify(
                $pencairan->id_nasabah, 'deposito',
                'Deposito Anda Telah Dicairkan',
                'Deposito No. ' . $pencairan->deposito->nomor_deposito . ' senilai Rp ' .
                    number_format($nominal, 0, ',', '.') . ' telah diserahkan secara tunai.',
                route('nasabah.deposito.detail', $pencairan->deposito_id),
                (string) $pencairan->deposito_id, 'pencairan_deposito'
            );

            return redirect()->route('admin.deposito.pencairan-tf.index')
                ->with('success', 'Pencairan tunai deposito berhasil. Saldo Petty Cash Admin berkurang Rp ' . number_format($nominal, 0, ',', '.'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('pencairanDepositoCash error', ['id' => $id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

