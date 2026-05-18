<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GadaiActive;
use App\Models\GadaiHistory;
use App\Models\GadaiPaymentLog;
use App\Models\GadaiSlotLog;
use App\Models\GadaiPengajuan;
use App\Models\PettyCashSaldo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminPengajuanGadaiController extends Controller
{
    public function index()
    {
        $pengajuan = GadaiPengajuan::with(['nasabah.user', 'gadaiActive.item', 'gadaiActive.kategori', 'files'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();
            
        return view('admin.gadai_baru.pengajuan_index', compact('pengajuan'));
    }

    public function approve(Request $request, $id)
    {
        $pengajuan = GadaiPengajuan::with('gadaiActive.kategori')->findOrFail($id);
        
        if ($pengajuan->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $gadai = $pengajuan->gadaiActive;

        DB::beginTransaction();
        try {
            $adminId = Auth::id();

            // 1. Process Petty Cash Mutation
            PettyCashSaldo::buatMutasi(
                $adminId,
                'admin',
                $pengajuan->nominal,
                ucfirst($pengajuan->jenis_pengajuan) . ' Gadai ' . $gadai->slot_kode . ' (via ' . $pengajuan->metode . ')',
                $gadai->id,
                'tbl_gadai_active',
                $pengajuan->metode,
                'gadai'
            );

            // 2. Process Gadai Logic based on type
            if ($pengajuan->jenis_pengajuan == 'perpanjang' || $pengajuan->jenis_pengajuan == 'perpanjangan') {
                if ($gadai->status !== 'grace_period') {
                    throw new \Exception('Perpanjangan hanya dapat dilakukan pada masa tenggang.');
                }
                if ($gadai->jumlah_perpanjangan >= 3) {
                    throw new \Exception('Maksimal perpanjangan adalah 3 kali.');
                }
                $newJatuhTempo = $gadai->tgl_jatuh_tempo->copy()->addDays($gadai->kategori->masa_gadai_hari)->endOfDay();
                $newTenggang = $newJatuhTempo->copy()->addDays($gadai->kategori->masa_tenggang_hari)->endOfDay();

                // Recalculate interest for the next period
                $rateJasa = ($gadai->item->bunga_high > 0) ? $gadai->item->bunga_high : $gadai->kategori->rate_jasa;
                $newBiayaJasa = ($gadai->nominal_deal * $rateJasa) / 100;

                $gadai->update([
                    'tgl_jatuh_tempo' => $newJatuhTempo,
                    'tgl_tenggang' => $newTenggang,
                    'jumlah_perpanjangan' => $gadai->jumlah_perpanjangan + 1,
                    'status' => 'active',
                    'biaya_jasa' => $newBiayaJasa, // Interest for the new period
                    'denda_aktif' => 0,
                    'biaya_inap' => 0
                ]);

                GadaiHistory::create([
                    'gadai_active_id' => $gadai->id,
                    'aksi' => 'extend',
                    'catatan' => 'Perpanjangan ke-' . $gadai->jumlah_perpanjangan . ' (Approved from Pengajuan #' . $pengajuan->id . ')'
                ]);
            } else if ($pengajuan->jenis_pengajuan == 'lunas') {
                $gadai->update(['status' => 'lunas']);

                // Free the slot
                $gridTable = $this->getGridTableName($gadai->slot_table);
                DB::table($gridTable)
                    ->where('kode_slot', $gadai->slot_kode)
                    ->update(['is_occupied' => false, 'active_gadai_id' => null]);

                GadaiSlotLog::create([
                    'slot_kode' => $gadai->slot_kode,
                    'kategori' => $gadai->slot_table,
                    'aksi' => 'empty',
                    'gadai_active_id' => $gadai->id
                ]);

                GadaiHistory::create([
                    'gadai_active_id' => $gadai->id,
                    'aksi' => 'lunas',
                    'catatan' => 'Gadai telah dilunasi (Approved from Pengajuan #' . $pengajuan->id . ')'
                ]);
            }

            // 3. Log Payment
            GadaiPaymentLog::create([
                'gadai_active_id' => $gadai->id,
                'jenis_pembayaran' => ($pengajuan->jenis_pengajuan == 'lunas') ? 'tebus' : 'perpanjangan',
                'nominal' => $pengajuan->nominal,
                'metode' => $pengajuan->metode
            ]);

            // 4. Update Pengajuan Status
            $pengajuan->update([
                'status' => 'approved',
                'admin_id' => $adminId,
                'admin_keterangan' => $request->admin_keterangan,
                'processed_at' => now()
            ]);

            // 5. Create record for Setoran Kantor queue
            \App\Models\PettyCashTransaksiNasabah::create([
                'id' => \App\Helpers\IdGenerator::generate('petty_cash_transaksi_nasabah', 'PCTN', 'AD', 'TR'),
                'admin_id' => $adminId,
                'nasabah_id' => $pengajuan->nasabah_id,
                'id_jns_transaksi' => \App\Services\PettyCashConstants::JNS_PMB, // Pembayaran
                'id_jns_via' => ($pengajuan->metode == 'transfer' ? \App\Services\PettyCashConstants::VIA_TF : \App\Services\PettyCashConstants::VIA_CS),
                'id_jns_fitur' => \App\Services\PettyCashConstants::FITUR_GADAI,
                'nominal' => $pengajuan->nominal,
                'status' => 'approved',
                'keterangan' => ucfirst($pengajuan->jenis_pengajuan) . ' Gadai ' . $gadai->slot_kode,
                'ref_table' => \App\Services\PettyCashConstants::REF_GADAI_P,
                'ref_id' => $pengajuan->id,
                'tgl_transaksi' => now()
            ]);

            // 6. Handle Admin Proof Photos
            if ($request->hasFile('admin_bukti_foto')) {
                foreach ($request->file('admin_bukti_foto') as $file) {
                    $path = $file->store('admin_bukti_gadai', 'public');
                    \App\Models\GadaiFile::create([
                        'gadai_active_id' => $gadai->id,
                        'pengajuan_id' => $pengajuan->id,
                        'path_file' => $path,
                        'tipe_foto' => 'penyerahan' // We use 'penyerahan' for admin proof
                    ]);
                }
            }

            DB::commit();
            return back()->with('success', 'Pengajuan ' . $pengajuan->jenis_pengajuan . ' berhasil disetujui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        $pengajuan = GadaiPengajuan::findOrFail($id);
        
        if ($pengajuan->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $pengajuan->update([
            'status' => 'rejected',
            'admin_id' => Auth::id(),
            'processed_at' => now(),
            'keterangan' => $request->keterangan ?? 'Ditolak oleh admin'
        ]);

        return back()->with('success', 'Pengajuan berhasil ditolak.');
    }

    private function getGridTableName($kategoriKode)
    {
        switch ($kategoriKode) {
            case 'electronic': return 'tbl_gadai_grid_electronic';
            case 'vehicle': return 'tbl_gadai_grid_vehicle';
            case 'gold': return 'tbl_gadai_grid_gold';
            default: throw new \Exception("Kategori tidak valid");
        }
    }
}
