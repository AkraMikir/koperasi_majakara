<?php

namespace App\Http\Controllers;

use App\Models\GadaiActive;
use App\Models\GadaiMasterKategori;
use App\Models\GadaiMasterItem;
use App\Models\JnsLokasiPerusahaan;
use App\Models\Nasabah;
use App\Models\GadaiHistory;
use App\Models\GadaiFile;
use App\Models\GadaiSlotLog;
use App\Models\PettyCashSaldo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminGadaiBaruController extends Controller
{
    public function index(Request $request)
    {
        $query = GadaiActive::with(['nasabah.user', 'kategori', 'item', 'lokasi']);

        if ($request->filled('kategori')) {
            $query->whereHas('kategori', function($q) use ($request) {
                $q->where('kode_kategori', $request->kategori);
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('cabang')) {
            $query->where('lokasi_id', $request->cabang);
        }

        $gadiList = $query->orderBy('created_at', 'desc')->get();
        $kategoriList = GadaiMasterKategori::all();
        $lokasiList = JnsLokasiPerusahaan::all();

        return view('admin.gadai_baru.index', compact('gadiList', 'kategoriList', 'lokasiList'));
    }

    public function create()
    {
        $nasabahs = Nasabah::with(['user', 'dataRek'])->get();
        $bankService = app(\App\Services\BankAccessService::class);
        $nasabahs->each(function ($n) use ($bankService) {
            $n->saldo_tabungan = $bankService->getSaldoTabungan($n->id);
        });

        $kategoriList = GadaiMasterKategori::all();
        $itemList = GadaiMasterItem::all();
        $lokasiList = JnsLokasiPerusahaan::all();

        $availableSlots = [
            'electronic' => DB::table('tbl_gadai_grid_electronic')->where('is_occupied', false)->orderBy('baris')->orderBy('kolom')->get(),
            'vehicle' => DB::table('tbl_gadai_grid_vehicle')->where('is_occupied', false)->orderBy('baris')->orderBy('kolom')->get(),
            'gold' => DB::table('tbl_gadai_grid_gold')->where('is_occupied', false)->orderBy('baris')->orderBy('kolom')->get(),
        ];

        $adminId = Auth::id();
        $adminSaldoCash = PettyCashSaldo::getSaldo($adminId, 'admin', 'cash', 'other');
        $adminSaldoTransfer = PettyCashSaldo::getSaldo($adminId, 'admin', 'transfer', 'other');
        $biayaTransfer = \App\Models\BiayaTransfer::where('is_active', true)->get();

        return view('admin.gadai_baru.create', compact(
            'nasabahs', 
            'kategoriList', 
            'itemList', 
            'lokasiList', 
            'availableSlots', 
            'adminSaldoCash', 
            'adminSaldoTransfer', 
            'biayaTransfer'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nasabah_id' => 'required|exists:tbl_nasabah,id',
            'kategori_id' => 'required|exists:tbl_gadai_master_kategori,id',
            'item_id' => 'required|exists:tbl_gadai_master_item,id',
            'lokasi_id' => 'required|exists:jns_lokasi_perusahaan,id',
            'slot_kode' => 'required|string',
            'nominal_deal' => 'required|numeric|min:1',
            'metode_pencairan' => 'required|in:cash,transfer',
            'foto_bukti.*' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $item = GadaiMasterItem::findOrFail($request->item_id);
        if ($request->nominal_deal > $item->nominal_high) {
            return back()->with('error', 'Nominal deal tidak boleh melebihi taksiran maksimal (Rp ' . number_format($item->nominal_high, 0, ',', '.') . ')');
        }

        $kategori = GadaiMasterKategori::findOrFail($request->kategori_id);
        
        // Calculate Fees
        $rateJasa = $kategori->rate_jasa;
        $biayaJasa = ($request->nominal_deal * $rateJasa) / 100;

        // Calculate Biaya Inap upfront (flat for vehicles, percentage for gold/electronics)
        $biayaInap = 0;
        if ($item->nominal_inap > 0) {
            $biayaInap = $item->nominal_inap;
        } else {
            $biayaInap = ($request->nominal_deal * $kategori->rate_inap_persen) / 100;
        }
        
        // Petty Cash Check & Mutasi (Uang keluar dari Admin ke Nasabah dari Modal Awal)
        $adminId = Auth::id();
        $metode = $request->metode_pencairan;
        if (!PettyCashSaldo::validatePenarikan($adminId, $request->nominal_deal, $metode, 'other')) {
            return back()->with('error', 'Saldo Petty Cash (' . strtoupper($metode) . ') pada Modal Awal tidak mencukupi untuk pencairan gadai.');
        }

        DB::beginTransaction();
        try {
            // Allocate Slot
            $slotData = $this->allocateSlot($kategori->kode_kategori, $request->slot_kode);

            $tglMulai = now();
            $tglJatuhTempo = now()->addDays($kategori->masa_gadai_hari)->endOfDay();
            $tglTenggang = $tglJatuhTempo->copy()->addDays($kategori->masa_tenggang_hari)->endOfDay();

            $gadai = GadaiActive::create([
                'nasabah_id' => $request->nasabah_id,
                'kategori_id' => $kategori->id,
                'item_id' => $item->id,
                'lokasi_id' => $request->lokasi_id,
                'slot_kode' => $slotData->kode_slot,
                'slot_table' => $kategori->kode_kategori,
                'nominal_deal' => $request->nominal_deal,
                'biaya_jasa' => $biayaJasa,
                'denda_aktif' => 0,
                'biaya_inap' => $biayaInap,
                'tgl_mulai' => $tglMulai,
                'tgl_jatuh_tempo' => $tglJatuhTempo,
                'tgl_tenggang' => $tglTenggang,
                'status' => 'active',
                'admin_id' => $adminId
            ]);

            // Update Slot with active ID
            $gridTable = $this->getGridTableName($kategori->kode_kategori);
            DB::table($gridTable)->where('id', $slotData->id)->update(['active_gadai_id' => $gadai->id]);

            // Log Slot
            GadaiSlotLog::create([
                'slot_kode' => $slotData->kode_slot,
                'kategori' => $kategori->kode_kategori,
                'aksi' => 'fill',
                'gadai_active_id' => $gadai->id
            ]);

            // History
            GadaiHistory::create([
                'gadai_active_id' => $gadai->id,
                'aksi' => 'create',
                'catatan' => 'Gadai baru dibuat. Slot: ' . $slotData->kode_slot
            ]);

            // Upload Files
            if ($request->hasFile('foto_bukti')) {
                foreach ($request->file('foto_bukti') as $file) {
                    $path = $file->store('gadai_files', 'public');
                    GadaiFile::create([
                        'gadai_active_id' => $gadai->id,
                        'path_file' => $path,
                        'tipe_foto' => 'barang'
                    ]);
                }
            }

            // Petty Cash Withdrawal (Dari Modal Awal)
            PettyCashSaldo::buatMutasi(
                $adminId, 
                'admin', 
                -$request->nominal_deal, 
                'Pencairan Gadai Baru ' . $gadai->slot_kode, 
                $gadai->id, 
                'tbl_gadai_active', 
                $metode, 
                'other'
            );

            // 🔥 INTEGRASI BIAYA TRANSFER ANTARBANK
            if ($metode === 'transfer') {
                $bankService = app(\App\Services\BankAccessService::class);
                $namaBank = $bankService->getNamaBank($request->nasabah_id);
                
                if ($namaBank && !$bankService->isBcaUser($request->nasabah_id)) {
                    $potong = $bankService->potongBiayaTransfer(
                        $request->nasabah_id,
                        $namaBank,
                        'Pencairan Gadai Baru ' . $gadai->slot_kode,
                        $adminId
                    );
                    
                    if (!$potong['success']) {
                        throw new \Exception($potong['message']);
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.gadai_baru.detail', $gadai->id)->with('success', 'Gadai berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function detail($id)
    {
        $gadai = GadaiActive::with(['nasabah.user', 'kategori', 'item', 'lokasi', 'files', 'history', 'paymentLogs'])->findOrFail($id);
        return view('admin.gadai_baru.detail', compact('gadai'));
    }

    private function allocateSlot($kategoriKode, $slotKode = null)
    {
        $table = $this->getGridTableName($kategoriKode);
        
        $query = DB::table($table)->where('is_occupied', false);
        
        if ($slotKode) {
            $query->where('kode_slot', $slotKode);
        } else {
            $query->orderBy('baris', 'asc')->orderBy('kolom', 'asc');
        }

        $slot = $query->first();
            
        if (!$slot) {
            if ($slotKode) {
                throw new \Exception("Slot {$slotKode} tidak tersedia atau sudah terisi.");
            }
            throw new \Exception("Kapasitas Penuh untuk kategori ini.");
        }
        
        DB::table($table)->where('id', $slot->id)->update(['is_occupied' => true]);
        
        return $slot;
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

    public function storage(Request $request)
    {
        $kategori = $request->get('kategori', 'electronic');
        
        $table = '';
        switch ($kategori) {
            case 'electronic': $table = 'tbl_gadai_grid_electronic'; break;
            case 'vehicle': $table = 'tbl_gadai_grid_vehicle'; break;
            case 'gold': $table = 'tbl_gadai_grid_gold'; break;
            default: $table = 'tbl_gadai_grid_electronic'; break;
        }

        $grid = DB::table($table)
            ->leftJoin('tbl_gadai_active', "$table.active_gadai_id", '=', 'tbl_gadai_active.id')
            ->leftJoin('tbl_gadai_master_item', 'tbl_gadai_active.item_id', '=', 'tbl_gadai_master_item.id')
            ->leftJoin('tbl_nasabah', 'tbl_gadai_active.nasabah_id', '=', 'tbl_nasabah.id')
            ->leftJoin('users', 'tbl_nasabah.user_id', '=', 'users.id')
            ->select(
                "$table.*", 
                'users.nama as nasabah_nama', 
                'tbl_gadai_master_item.head_1 as item_nama', 
                'tbl_gadai_active.status as gadai_status', 
                'tbl_gadai_active.id as active_gadai_id'
            )
            ->orderBy('baris', 'desc')
            ->orderBy('kolom', 'asc')
            ->get();
            
        $groupedGrid = $grid->groupBy('baris');

        return view('admin.gadai_baru.storage', compact('groupedGrid', 'kategori'));
    }

    public function emptyAuction(Request $request)
    {
        $request->validate([
            'gadai_id' => 'required|exists:tbl_gadai_active,id',
            'catatan' => 'required|string|min:5',
            'foto_bukti' => 'required|array|min:1',
            'foto_bukti.*' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'catatan.required' => 'Catatan alasan/detail pengambilan barang wajib diisi.',
            'catatan.min' => 'Catatan minimal 5 karakter.',
            'foto_bukti.required' => 'Wajib melampirkan minimal 1 foto bukti pengambilan.',
            'foto_bukti.min' => 'Wajib melampirkan minimal 1 foto bukti pengambilan.',
            'foto_bukti.*.image' => 'File bukti harus berupa foto/gambar.',
            'foto_bukti.*.max' => 'Ukuran foto maksimal adalah 2MB.'
        ]);

        $gadai = GadaiActive::findOrFail($request->gadai_id);

        if ($gadai->status !== 'expired_final') {
            return back()->with('error', 'Barang gadai ini belum berstatus hangus, tidak bisa dikosongkan untuk lelang.');
        }

        DB::beginTransaction();
        try {
            // 1. Update Grid Slot (Set occupied to false, active_gadai_id to null)
            $table = '';
            switch ($gadai->slot_table) {
                case 'electronic': $table = 'tbl_gadai_grid_electronic'; break;
                case 'vehicle': $table = 'tbl_gadai_grid_vehicle'; break;
                case 'gold': $table = 'tbl_gadai_grid_gold'; break;
                default: throw new \Exception("Kategori slot tidak valid.");
            }

            DB::table($table)->where('kode_slot', $gadai->slot_kode)->update([
                'is_occupied' => false,
                'active_gadai_id' => null
            ]);

            // 2. Create record for GadaiSlotLog (empty)
            GadaiSlotLog::create([
                'slot_kode' => $gadai->slot_kode,
                'kategori' => $gadai->slot_table,
                'aksi' => 'empty',
                'gadai_active_id' => $gadai->id
            ]);

            // 3. Update Gadai Active status to 'auctioned'
            $gadai->update([
                'status' => 'auctioned'
            ]);

            // 4. Create History
            GadaiHistory::create([
                'gadai_active_id' => $gadai->id,
                'aksi' => 'auction',
                'catatan' => 'Barang diambil dari penyimpanan untuk proses lelang. Catatan: ' . $request->catatan
            ]);

            // 5. Save proof photos to tbl_gadai_files
            if ($request->hasFile('foto_bukti')) {
                foreach ($request->file('foto_bukti') as $file) {
                    $path = $file->store('gadai_files', 'public');
                    GadaiFile::create([
                        'gadai_active_id' => $gadai->id,
                        'path_file' => $path,
                        'tipe_foto' => 'lainnya'
                    ]);
                }
            }

            DB::commit();
            return back()->with('success', 'Barang pada slot ' . $gadai->slot_kode . ' berhasil diambil untuk dilelang dan kapasitas slot telah dikosongkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
