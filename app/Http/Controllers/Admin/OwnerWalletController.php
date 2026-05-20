<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PettyCashOwnerTransaksi;
use App\Models\PettyCashSaldo;
use App\Models\OwnerWithdrawal;
use App\Models\User;
use App\Helpers\IdGenerator;
use App\Services\PettyCashConstants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class OwnerWalletController extends Controller
{
    /**
     * Display the owner's wallet dashboard using the running balance view.
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        
        $query = DB::table('vw_saldo_owner_detail')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc');

        if ($request->tipe) {
            $query->where('tipe', $request->tipe);
        }
        if ($request->sumber) {
            $query->where('sumber', $request->sumber);
        }
        if ($request->tgl_dari) {
            $query->whereDate('created_at', '>=', $request->tgl_dari);
        }
        if ($request->tgl_sampai) {
            $query->whereDate('created_at', '<=', $request->tgl_sampai);
        }

        $transaksi = $query->paginate(20);
        
        // 🔥 Hitung saldo per sumber dengan breakdown Cash/TF untuk container detail
        $sourceDetails = DB::table('vw_saldo_owner_detail')
            ->where('user_id', $userId)
            ->select('sumber', 
                DB::raw('SUM(nominal_cash) as total_cash'),
                DB::raw('SUM(nominal_tf) as total_tf')
            )
            ->groupBy('sumber')
            ->get()
            ->keyBy('sumber')
            ->toArray();

        // Calculate total saldo cash and tf from the view to ensure consistency
        $saldoCash = 0;
        $saldoTf = 0;
        foreach ($sourceDetails as $source) {
            $saldoCash += $source->total_cash;
            $saldoTf += $source->total_tf;
        }

        return view('admin.petty-cash.owner-wallet', compact('transaksi', 'saldoCash', 'saldoTf', 'sourceDetails'));
    }

    /**
     * Store a manual owner transaction (In/Out).
     */
    public function store(Request $request)
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
            // 1. Validasi saldo owner secara keseluruhan (Cash & TF)
            if (!PettyCashSaldo::validateKirimOwner($userId, $nominalCash, $nominalTf)) {
                return back()->withErrors(['nominal_cash' => 'Saldo Owner tidak mencukupi untuk pengeluaran ini.'])->withInput();
            }

            // 2. Validasi spesifik terhadap saldo Modal Awal (SUMBER_LAIN)
            $otherBalance = DB::table('vw_saldo_owner_detail')
                ->where('user_id', $userId)
                ->where('sumber', PettyCashConstants::SUMBER_LAIN)
                ->select(
                    DB::raw('SUM(nominal_cash) as total_cash'),
                    DB::raw('SUM(nominal_tf) as total_tf')
                )
                ->first();

            $maxCash = (float)($otherBalance->total_cash ?? 0);
            $maxTf   = (float)($otherBalance->total_tf   ?? 0);

            if ($nominalCash > $maxCash || $nominalTf > $maxTf) {
                $msg = "Saldo Modal Awal tidak mencukupi. (Tersedia Cash: Rp " . number_format($maxCash, 0, ',', '.') . 
                       ", Bank: Rp " . number_format($maxTf, 0, ',', '.') . ")";
                return back()->withErrors(['nominal_cash' => $msg])->withInput();
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

            PettyCashOwnerTransaksi::create([
                'id'              => $idTrans,
                'user_id'         => $userId,
                'tipe'            => $request->tipe,
                'sumber'          => PettyCashConstants::SUMBER_LAIN,
                'nominal_cash'    => $nominalCash,
                'nominal_tf'      => $nominalTf,
                'keterangan'      => $request->keterangan,
                'bukti_foto_cash' => $fotoCash,
                'bukti_foto_tf'   => $fotoTf,
            ]);

            // 2. Update Saldo Terpusat
            if ($nominalCash > 0) {
                $mutasi = ($request->tipe === 'masuk') ? $nominalCash : -$nominalCash;
                PettyCashSaldo::buatMutasi($userId, 'owner', $mutasi, $request->keterangan, $idTrans, 'petty_cash_owner_transaksi', 'cash', PettyCashConstants::SUMBER_LAIN);
            }
            if ($nominalTf > 0) {
                $mutasi = ($request->tipe === 'masuk') ? $nominalTf : -$nominalTf;
                PettyCashSaldo::buatMutasi($userId, 'owner', $mutasi, $request->keterangan, $idTrans, 'petty_cash_owner_transaksi', 'transfer', PettyCashConstants::SUMBER_LAIN);
            }

            DB::commit();
            return back()->with('success', 'Transaksi berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('OwnerWallet store error', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Process Owner Withdrawal.
     */
    public function withdraw(Request $request)
    {
        $request->validate([
            'sumber'       => 'required|in:tabungan,pinjaman,deposito,petty_cash,other',
            'nominal_cash' => 'nullable|numeric|min:0',
            'nominal_tf'   => 'nullable|numeric|min:0',
            'keterangan'   => 'required|string|max:500',
            'bukti_foto'   => 'nullable|image|max:5120',
        ]);

        $nominalCash = (float)($request->nominal_cash ?? 0);
        $nominalTf   = (float)($request->nominal_tf   ?? 0);
        $userId      = Auth::id();

        if (($nominalCash + $nominalTf) <= 0) {
            return back()->with('error', 'Nominal harus lebih dari 0.')->withInput();
        }

        // Validasi Saldo
        if (!PettyCashSaldo::validateKirimOwner($userId, $nominalCash, $nominalTf)) {
            return back()->withErrors(['nominal_cash' => 'Saldo Owner tidak mencukupi untuk penarikan ini.'])->withInput();
        }

        try {
            DB::beginTransaction();

            $foto = null;
            if ($request->hasFile('bukti_foto')) {
                $foto = $request->file('bukti_foto')->store('petty_cash/owner/withdrawals', 'public');
            }

            $idWithdraw = IdGenerator::generate('owner_withdrawals', 'OWD', 'OW', 'WD');

            OwnerWithdrawal::create([
                'id'           => $idWithdraw,
                'user_id'      => $userId,
                'nominal_cash' => $nominalCash,
                'nominal_tf'   => $nominalTf,
                'sumber'       => $request->sumber,
                'keterangan'   => $request->keterangan,
                'bukti_foto'   => $foto,
            ]);

            // Update Saldo Terpusat
            if ($nominalCash > 0) {
                PettyCashSaldo::buatMutasi($userId, 'owner', -$nominalCash, "Penarikan Owner ({$request->sumber})", $idWithdraw, 'owner_withdrawals', 'cash', $request->sumber);
            }
            if ($nominalTf > 0) {
                PettyCashSaldo::buatMutasi($userId, 'owner', -$nominalTf, "Penarikan Owner ({$request->sumber})", $idWithdraw, 'owner_withdrawals', 'transfer', $request->sumber);
            }

            DB::commit();
            return back()->with('success', 'Penarikan berhasil diproses');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('OwnerWallet withdraw error', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Destroy manual transaction (ONLY manual TR).
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $trans = PettyCashOwnerTransaksi::where('user_id', Auth::id())->findOrFail($id);

            // Refund Saldo
            if ($trans->nominal_cash > 0) {
                $mutasi = ($trans->tipe === 'masuk') ? -$trans->nominal_cash : $trans->nominal_cash;
                PettyCashSaldo::buatMutasi(Auth::id(), 'owner', $mutasi, "Hapus Transaksi #{$id}", $id, 'petty_cash_owner_transaksi', 'cash', $trans->sumber);
            }
            if ($trans->nominal_tf > 0) {
                $mutasi = ($trans->tipe === 'masuk') ? -$trans->nominal_tf : $trans->nominal_tf;
                PettyCashSaldo::buatMutasi(Auth::id(), 'owner', $mutasi, "Hapus Transaksi #{$id}", $id, 'petty_cash_owner_transaksi', 'transfer', $trans->sumber);
            }

            $trans->delete();
            DB::commit();
            return back()->with('success', 'Transaksi berhasil dihapus dan saldo disesuaikan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    /**
     * Internal transfer between sources (Owner level).
     */
    public function internalTransfer(Request $request)
    {
        $request->validate([
            'sumber_asal'  => 'required|in:tabungan,pinjaman,deposito,gadai,petty_cash',
            'nominal_cash' => 'nullable|numeric|min:0',
            'nominal_tf'   => 'nullable|numeric|min:0',
            'keterangan'   => 'nullable|string|max:500',
        ]);

        $nominalCash = (float)($request->nominal_cash ?? 0);
        $nominalTf   = (float)($request->nominal_tf   ?? 0);
        $userId      = Auth::id();
        $sumberAsal  = $request->sumber_asal;
        $sumberTujuan= PettyCashConstants::SUMBER_LAIN; // Modal Awal

        if (($nominalCash + $nominalTf) <= 0) {
            return back()->with('error', 'Nominal harus lebih dari 0.')->withInput();
        }

        // Validasi Saldo Asal
        $sourceBalance = DB::table('vw_saldo_owner_detail')
            ->where('user_id', $userId)
            ->where('sumber', $sumberAsal)
            ->select(
                DB::raw('SUM(nominal_cash) as total_cash'),
                DB::raw('SUM(nominal_tf) as total_tf')
            )
            ->first();

        if ($nominalCash > (float)($sourceBalance->total_cash ?? 0) || $nominalTf > (float)($sourceBalance->total_tf ?? 0)) {
            return back()->with('error', "Saldo sumber {$sumberAsal} tidak mencukupi.")->withInput();
        }

        try {
            DB::beginTransaction();

            $idAsal = IdGenerator::generate('petty_cash_owner_transaksi', 'PCOW', 'OW', 'TR');
            $ket = "Pindah Dana ke Modal Awal: " . ($request->keterangan ?? '-');

            // 1. Potong Saldo Asal
            PettyCashOwnerTransaksi::create([
                'id'           => $idAsal,
                'user_id'      => $userId,
                'tipe'         => 'keluar',
                'sumber'       => $sumberAsal,
                'nominal_cash' => $nominalCash,
                'nominal_tf'   => $nominalTf,
                'keterangan'   => $ket,
            ]);

            if ($nominalCash > 0) {
                PettyCashSaldo::buatMutasi($userId, 'owner', -$nominalCash, $ket, $idAsal, 'petty_cash_owner_transaksi', 'cash', $sumberAsal);
            }
            if ($nominalTf > 0) {
                PettyCashSaldo::buatMutasi($userId, 'owner', -$nominalTf, $ket, $idAsal, 'petty_cash_owner_transaksi', 'transfer', $sumberAsal);
            }

            // 2. Tambah Saldo Tujuan (Modal Awal)
            $idTujuan = IdGenerator::generate('petty_cash_owner_transaksi', 'PCOW', 'OW', 'TR');
            $ket2 = "Terima Pindahan dari {$sumberAsal}: " . ($request->keterangan ?? '-');

            PettyCashOwnerTransaksi::create([
                'id'           => $idTujuan,
                'user_id'         => $userId,
                'tipe'            => 'masuk',
                'sumber'          => $sumberTujuan,
                'nominal_cash'    => $nominalCash,
                'nominal_tf'      => $nominalTf,
                'keterangan'      => $ket2,
            ]);

            if ($nominalCash > 0) {
                PettyCashSaldo::buatMutasi($userId, 'owner', $nominalCash, $ket2, $idTujuan, 'petty_cash_owner_transaksi', 'cash', $sumberTujuan);
            }
            if ($nominalTf > 0) {
                PettyCashSaldo::buatMutasi($userId, 'owner', $nominalTf, $ket2, $idTujuan, 'petty_cash_owner_transaksi', 'transfer', $sumberTujuan);
            }

            DB::commit();
            return back()->with('success', "Dana dari {$sumberAsal} berhasil dipindahkan ke Modal Awal.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('internalTransfer error', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
