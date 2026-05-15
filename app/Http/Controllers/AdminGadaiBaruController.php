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
        $nasabahs = Nasabah::with('user')->get();
        $kategoriList = GadaiMasterKategori::all();
        $itemList = GadaiMasterItem::all();
        $lokasiList = JnsLokasiPerusahaan::all();

        return view('admin.gadai_baru.create', compact('nasabahs', 'kategoriList', 'itemList', 'lokasiList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nasabah_id' => 'required|exists:tbl_nasabah,id',
            'kategori_id' => 'required|exists:tbl_gadai_master_kategori,id',
            'item_id' => 'required|exists:tbl_gadai_master_item,id',
            'lokasi_id' => 'required|exists:jns_lokasi_perusahaan,id',
            'nominal_deal' => 'required|numeric|min:1',
            'metode_pencairan' => 'required|in:cash,transfer',
            'foto_bukti.*' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $item = GadaiMasterItem::findOrFail($request->item_id);
        if ($request->nominal_deal > $item->nominal_high) {
            return back()->with('error', 'Nominal deal tidak boleh melebihi taksiran maksimal (Rp ' . number_format($item->nominal_high, 0, ',', '.') . ')');
        }

        $kategori = GadaiMasterKategori::findOrFail($request->kategori_id);
        
        // Calculate Fees using item's bunga_high (or interpolate if needed)
        // For now we use bunga_high as the default rate for this item
        $rateJasa = $item->bunga_high > 0 ? $item->bunga_high : $kategori->rate_jasa;
        $biayaJasa = ($request->nominal_deal * $rateJasa) / 100;
        
        // Petty Cash Check & Mutasi (Uang keluar dari Admin ke Nasabah dari Modal Awal)
        $adminId = Auth::id();
        $metode = $request->metode_pencairan;
        if (!PettyCashSaldo::validatePenarikan($adminId, $request->nominal_deal, $metode, 'other')) {
            return back()->with('error', 'Saldo Petty Cash (' . strtoupper($metode) . ') pada Modal Awal tidak mencukupi untuk pencairan gadai.');
        }

        DB::beginTransaction();
        try {
            // Allocate Slot
            $slotData = $this->allocateSlot($kategori->kode_kategori);

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
                'biaya_inap' => 0,
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

    private function allocateSlot($kategoriKode)
    {
        $table = $this->getGridTableName($kategoriKode);
        
        $slot = DB::table($table)
            ->where('is_occupied', false)
            ->orderBy('baris', 'asc')
            ->orderBy('kolom', 'asc')
            ->first();
            
        if (!$slot) {
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
}
