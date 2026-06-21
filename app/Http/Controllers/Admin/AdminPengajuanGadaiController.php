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
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');
        
        $query = GadaiPengajuan::with(['nasabah.user', 'gadaiActive.item', 'gadaiActive.kategori', 'gadaiActive.pengajuans', 'files']);
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $orderBy = $status === 'pending' ? 'asc' : 'desc';
        
        $pengajuan = $query->orderBy('created_at', $orderBy)->get();
        $gadaiCounts = [
            'pending' => GadaiPengajuan::where('status', 'pending')->count(),
            'approved' => GadaiPengajuan::where('status', 'approved')->count(),
            'rejected' => GadaiPengajuan::where('status', 'rejected')->count(),
            'all' => GadaiPengajuan::count()
        ];
            
        return view('admin.gadai_baru.pengajuan_index', compact('pengajuan', 'status', 'gadaiCounts'));
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

            // 1. Process Petty Cash / Owner Mutation
            if ($pengajuan->metode === 'transfer') {
                $owner = \App\Models\User::where('role', 'admin_utama')->first();
                if ($owner) {
                    $ownerTransId = \App\Helpers\IdGenerator::generate('petty_cash_owner_transaksi', 'PCOW', 'OW', 'TR');
                    \App\Models\PettyCashOwnerTransaksi::create([
                        'id'           => $ownerTransId,
                        'user_id'      => $owner->id,
                        'tipe'         => 'terima_setoran',
                        'sumber'       => \App\Services\PettyCashConstants::SUMBER_GADAI,
                        'nominal_cash' => 0,
                        'nominal_tf'   => (float) $pengajuan->nominal,
                        'keterangan'   => ucfirst($pengajuan->jenis_pengajuan) . " Gadai: " . ($pengajuan->nasabah->user->nama ?? '-') . " (#{$pengajuan->id})",
                        'ref_table'    => \App\Services\PettyCashConstants::REF_GADAI_P,
                        'ref_id'       => $pengajuan->id,
                    ]);

                    \App\Models\PettyCashSaldo::buatMutasi(
                        $owner->id, 
                        'owner', 
                        (float)$pengajuan->nominal,
                        ucfirst($pengajuan->jenis_pengajuan) . " Gadai (#{$pengajuan->id})",
                        $pengajuan->id, 
                        \App\Services\PettyCashConstants::REF_GADAI_P, 
                        'transfer', 
                        'gadai'
                    );
                }
            } else {
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
            }

            // 2. Process Gadai Logic based on type
            if ($pengajuan->jenis_pengajuan == 'perpanjang' || $pengajuan->jenis_pengajuan == 'perpanjangan') {
                if (!in_array($gadai->status, ['active', 'grace_period'])) {
                    throw new \Exception('Perpanjangan hanya dapat dilakukan untuk gadai aktif atau dalam masa tenggang.');
                }
                if ($gadai->jumlah_perpanjangan >= $gadai->kategori->max_extend_default) {
                    throw new \Exception('Maksimal perpanjangan adalah ' . $gadai->kategori->max_extend_default . ' kali.');
                }
                $newJatuhTempo = $gadai->tgl_jatuh_tempo->copy()->addDays($gadai->kategori->masa_gadai_hari)->endOfDay();
                $newTenggang = $newJatuhTempo->copy()->addDays($gadai->kategori->masa_tenggang_hari)->endOfDay();

                // Recalculate interest and inap for the next period
                $rateJasa = $gadai->rate_jasa ?? $gadai->kategori->rate_jasa;
                $newBiayaJasa = ($gadai->nominal_deal * $rateJasa) / 100;

                $newBiayaInap = 0;
                if ($gadai->item->nominal_inap > 0) {
                    $newBiayaInap = $gadai->item->nominal_inap;
                } else {
                    $rateInap = $gadai->rate_inap_persen ?? $gadai->kategori->rate_inap_persen;
                    $newBiayaInap = ($gadai->nominal_deal * $rateInap) / 100;
                }

                $gadai->update([
                    'tgl_jatuh_tempo' => $newJatuhTempo,
                    'tgl_tenggang' => $newTenggang,
                    'jumlah_perpanjangan' => $gadai->jumlah_perpanjangan + 1,
                    'status' => 'active',
                    'biaya_jasa' => $newBiayaJasa, // Interest for the new period
                    'denda_aktif' => 0,
                    'biaya_inap' => $newBiayaInap
                ]);

                GadaiHistory::create([
                    'gadai_active_id' => $gadai->id,
                    'aksi' => 'extend',
                    'catatan' => 'Perpanjangan ke-' . $gadai->jumlah_perpanjangan . ' (Approved from Pengajuan #' . $pengajuan->id . ')'
                ]);
            } else if ($pengajuan->jenis_pengajuan == 'lunas') {
                $gadaiUpdateData = [];
                
                // Handle Extra Pinjaman for LUNAS
                if ($request->filled('extra_pinjaman_nominal') && $request->extra_pinjaman_nominal > 0) {
                    if (!$request->filled('extra_pinjaman_reason')) {
                        throw new \Exception('Alasan extra pinjaman harus diisi jika nominal extra lebih dari 0.');
                    }
                    $gadaiUpdateData['extra_pinjaman_nominal'] = $request->extra_pinjaman_nominal;
                    $gadaiUpdateData['extra_pinjaman_reason'] = $request->extra_pinjaman_reason;
                    $gadaiUpdateData['extra_pinjaman_admin_id'] = $adminId;
                    $gadaiUpdateData['extra_pinjaman_set_at'] = now();
                    
                    // Create mutation for the extra pinjaman (cash only)
                    PettyCashSaldo::buatMutasi(
                        $adminId,
                        'admin',
                        $request->extra_pinjaman_nominal,
                        'Extra Pinjaman/Denda Gadai ' . $gadai->slot_kode . ' (' . $request->extra_pinjaman_reason . ')',
                        $gadai->id,
                        'tbl_gadai_active',
                        'cash',
                        'gadai'
                    );
                }

                // Unify cash and transfer: both get lunas status and tgl_ambil_limit countdown
                $gadaiUpdateData['status'] = 'lunas';
                $countdownHari = $gadai->kategori->countdown_ambil_hari ?? 14;
                $gadaiUpdateData['tgl_ambil_limit'] = now()->addDays($countdownHari)->endOfDay();
                $gadai->update($gadaiUpdateData);

                GadaiHistory::create([
                    'gadai_active_id' => $gadai->id,
                    'aksi' => 'lunas',
                    'catatan' => 'Gadai telah dilunasi via ' . ucfirst($pengajuan->metode) . '. Batas pengambilan barang set s/d ' . $gadaiUpdateData['tgl_ambil_limit']->format('d M Y H:i') . ' (Approved from Pengajuan #' . $pengajuan->id . ')'
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

            // 5. Create record for Setoran Kantor queue (ONLY for cash payment, because transfer goes directly to owner)
            if ($pengajuan->metode === 'cash') {
                \App\Models\PettyCashTransaksiNasabah::create([
                    'id' => \App\Helpers\IdGenerator::generate('petty_cash_transaksi_nasabah', 'PCTN', 'AD', 'TR'),
                    'admin_id' => $adminId,
                    'nasabah_id' => $pengajuan->nasabah_id,
                    'id_jns_transaksi' => \App\Services\PettyCashConstants::JNS_PMB, // Pembayaran
                    'id_jns_via' => \App\Services\PettyCashConstants::VIA_CS,
                    'id_jns_fitur' => \App\Services\PettyCashConstants::FITUR_GADAI,
                    'nominal' => $pengajuan->nominal,
                    'status' => 'approved',
                    'keterangan' => ucfirst($pengajuan->jenis_pengajuan) . ' Gadai ' . $gadai->slot_kode,
                    'ref_table' => \App\Services\PettyCashConstants::REF_GADAI_P,
                    'ref_id' => $pengajuan->id,
                    'tgl_transaksi' => now()
                ]);
            }

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

            app(\App\Services\ActivityLogService::class)->logApprovePengajuanGadai((string)$pengajuan->id, (float)$pengajuan->nominal, $pengajuan->nasabah->user->nama ?? 'N/A');

            if ($pengajuan->jenis_pengajuan == 'lunas') {
                app(\App\Services\ActivityLogService::class)->logPelunasanGadai((string)$gadai->id, (float)$pengajuan->nominal, $pengajuan->nasabah->user->nama ?? 'N/A');
            }

            return back()->with('success', 'Pengajuan ' . $pengajuan->jenis_pengajuan . ' berhasil disetujui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        $pengajuan = GadaiPengajuan::with('nasabah.user')->findOrFail($id);
        
        if ($pengajuan->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $pengajuan->update([
            'status' => 'rejected',
            'admin_id' => Auth::id(),
            'processed_at' => now(),
            'keterangan' => $request->keterangan ?? 'Ditolak oleh admin'
        ]);

        app(\App\Services\ActivityLogService::class)->logRejectPengajuanGadai((string)$pengajuan->id, (float)$pengajuan->nominal, $pengajuan->nasabah->user->nama ?? 'N/A', $request->keterangan ?? 'Ditolak oleh admin');

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
