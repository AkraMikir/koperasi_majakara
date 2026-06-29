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

        $globalStats = [
            'active' => GadaiActive::where('status', 'active')->count(),
            'grace_period' => GadaiActive::where('status', 'grace_period')->count(),
            'expired_final' => GadaiActive::where('status', 'expired_final')->count(),
            'lunas' => GadaiActive::where('status', 'lunas')->count(),
            'returned' => GadaiActive::where('status', 'returned')->count(),
            'auctioned' => GadaiActive::where('status', 'auctioned')->count(),
        ];

        $page = (int) $request->get('page', 1);
        $limit = 25;
        $totalRecords = $query->count();
        $gadiList = $query->orderBy('created_at', 'desc')
                          ->skip(($page - 1) * $limit)
                          ->take($limit)
                          ->get();
        $hasMore = ($page * $limit) < $totalRecords;

        $kategoriList = GadaiMasterKategori::all();
        $lokasiList = JnsLokasiPerusahaan::all();

        return view('admin.gadai_baru.index', compact('gadiList', 'kategoriList', 'lokasiList', 'globalStats', 'page', 'hasMore'));
    }

    public function create()
    {
        $nasabahs = Nasabah::with(['user', 'dataRek', 'dataKtp'])->get();
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
        $kategori = \App\Models\GadaiMasterKategori::find($request->kategori_id);
        if ($kategori && $kategori->kode_kategori === 'vehicle') {
            $request->merge(['rate_inap_persen' => 0]);
        }

        $request->validate([
            'nasabah_id' => 'required|exists:tbl_nasabah,id',
            'kategori_id' => 'required|exists:tbl_gadai_master_kategori,id',
            'item_id' => 'required|exists:tbl_gadai_master_item,id',
            'nama_barang_manual' => 'nullable|string|max:255',
            'lokasi_id' => 'required|exists:jns_lokasi_perusahaan,id',
            'slot_kode' => 'required|string',
            'nominal_deal' => 'required|numeric|min:1',
            'rate_jasa' => 'required|numeric|min:0',
            'rate_inap_persen' => 'required|numeric|min:0',
            'metode_pencairan' => 'required|in:cash,transfer',
            'foto_barang' => 'required|array|min:1',
            'foto_barang.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'foto_transaksi' => 'required|array|min:1',
            'foto_transaksi.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'foto_administrasi' => 'required|array|min:1',
            'foto_administrasi.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'no_mesin_rangka' => 'nullable|string|max:255',
            'no_imei_sn' => 'nullable|string|max:255',
            'kelengkapan' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        $item = GadaiMasterItem::findOrFail($request->item_id);
        if ($request->nominal_deal > $item->nominal_high) {
            return back()->with('error', 'Nominal deal tidak boleh melebihi taksiran maksimal (Rp ' . number_format($item->nominal_high, 0, ',', '.') . ')');
        }

        $kategori = GadaiMasterKategori::findOrFail($request->kategori_id);
        
        // Calculate Fees using custom inputs
        $rateJasa = $request->rate_jasa;
        $biayaJasa = ($request->nominal_deal * $rateJasa) / 100;

        // Calculate Biaya Inap upfront (flat for vehicles, percentage for gold/electronics)
        $biayaInap = 0;
        if ($item->nominal_inap > 0) {
            $biayaInap = $item->nominal_inap;
        } else {
            $biayaInap = ($request->nominal_deal * $request->rate_inap_persen) / 100;
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
                'nama_barang_manual' => $request->nama_barang_manual,
                'lokasi_id' => $request->lokasi_id,
                'slot_kode' => $slotData->kode_slot,
                'slot_table' => $kategori->kode_kategori,
                'no_mesin_rangka' => $request->no_mesin_rangka,
                'no_imei_sn' => $request->no_imei_sn,
                'kelengkapan' => $request->kelengkapan,
                'catatan' => $request->catatan,
                'nominal_deal' => $request->nominal_deal,
                'rate_jasa' => $request->rate_jasa,
                'biaya_jasa' => $biayaJasa,
                'rate_inap_persen' => $request->rate_inap_persen,
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

            // Upload Foto Barang
            if ($request->hasFile('foto_barang')) {
                foreach ($request->file('foto_barang') as $file) {
                    $path = $file->store('gadai_files', 'public');
                    GadaiFile::create([
                        'gadai_active_id' => $gadai->id,
                        'path_file' => $path,
                        'tipe_foto' => 'barang'
                    ]);
                }
            }

            // Upload Foto Transaksi
            if ($request->hasFile('foto_transaksi')) {
                foreach ($request->file('foto_transaksi') as $file) {
                    $path = $file->store('gadai_files', 'public');
                    GadaiFile::create([
                        'gadai_active_id' => $gadai->id,
                        'path_file' => $path,
                        'tipe_foto' => 'penyerahan'
                    ]);
                }
            }

            // Upload Foto Administrasi
            if ($request->hasFile('foto_administrasi')) {
                foreach ($request->file('foto_administrasi') as $file) {
                    $path = $file->store('gadai_files', 'public');
                    GadaiFile::create([
                        'gadai_active_id' => $gadai->id,
                        'path_file' => $path,
                        'tipe_foto' => 'lainnya'
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
        $gadai = GadaiActive::with(['nasabah.user', 'kategori', 'item', 'lokasi', 'files', 'history', 'paymentLogs', 'pengajuans'])->findOrFail($id);
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
                'tbl_gadai_active.id as active_gadai_id',
                'tbl_gadai_active.tgl_ambil_limit'
            )
            ->orderBy('baris', 'desc')
            ->orderBy('kolom', 'asc')
            ->get();
            
        $groupedGrid = $grid->groupBy('baris');
        $settings = \App\Models\SettingsStruk::getSettings();

        return view('admin.gadai_baru.storage', compact('groupedGrid', 'kategori', 'settings'));
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

    public function ambilBarang(Request $request, $id)
    {
        $request->validate([
            'foto_bukti' => 'required|array|min:1',
            'foto_bukti.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'struk_hilang' => 'nullable|boolean',
            'metode_denda' => 'nullable|required_if:struk_hilang,1|in:cash,transfer'
        ], [
            'foto_bukti.required' => 'Wajib melampirkan minimal 1 foto bukti pengambilan/penyerahan.',
            'foto_bukti.min' => 'Wajib melampirkan minimal 1 foto bukti pengambilan/penyerahan.',
            'foto_bukti.*.image' => 'File bukti harus berupa foto/gambar.',
            'foto_bukti.*.max' => 'Ukuran foto maksimal adalah 2MB.',
            'metode_denda.required_if' => 'Pilih metode pembayaran denda jika struk hilang.'
        ]);

        $gadai = GadaiActive::findOrFail($id);

        if ($gadai->status !== 'lunas') {
            return back()->with('error', 'Barang gadai ini belum berstatus lunas atau sudah diambil.');
        }

        $strukHilang = $request->boolean('struk_hilang');
        $dendaAmount = 0;
        if ($strukHilang) {
            $settings = \App\Models\SettingsStruk::getSettings();
            $dendaAmount = (float)($settings->extra_nilai_kehilangan ?? 0);
        }

        DB::beginTransaction();
        try {
            // 1. Update Grid Slot (Set occupied to false, active_gadai_id to null)
            $table = $this->getGridTableName($gadai->slot_table);

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

            // 3. Update Gadai Active status to 'returned'
            $gadaiUpdateData = [
                'status' => 'returned'
            ];

            if ($strukHilang && $dendaAmount > 0) {
                $gadaiUpdateData['extra_pinjaman_nominal'] = $dendaAmount;
                $gadaiUpdateData['extra_pinjaman_reason'] = 'Kehilangan Struk';
                $gadaiUpdateData['extra_pinjaman_admin_id'] = Auth::id();
                $gadaiUpdateData['extra_pinjaman_set_at'] = now();
            }

            $gadai->update($gadaiUpdateData);

            // 4. Create History
            GadaiHistory::create([
                'gadai_active_id' => $gadai->id,
                'aksi' => 'return',
                'catatan' => 'Barang gadai telah diserahkan/diambil oleh nasabah.' . ($strukHilang ? ' (Denda Kehilangan Struk Rp ' . number_format($dendaAmount, 0, ',', '.') . ')' : '')
            ]);

            // 5. Save proof photos to tbl_gadai_files
            if ($request->hasFile('foto_bukti')) {
                foreach ($request->file('foto_bukti') as $file) {
                    $path = $file->store('gadai_files', 'public');
                    \App\Models\GadaiFile::create([
                        'gadai_active_id' => $gadai->id,
                        'path_file' => $path,
                        'tipe_foto' => 'penyerahan'
                    ]);
                }
            }

            // 6. Process Denda Transaction and Petty Cash Mutation if any
            if ($strukHilang && $dendaAmount > 0) {
                $adminId = Auth::id();
                $metodeDenda = $request->input('metode_denda', 'cash');

                if ($metodeDenda === 'transfer') {
                    $owner = \App\Models\User::where('role', 'admin_utama')->first();
                    if ($owner) {
                        $ownerTransId = \App\Helpers\IdGenerator::generate('petty_cash_owner_transaksi', 'PCOW', 'OW', 'TR');
                        \App\Models\PettyCashOwnerTransaksi::create([
                            'id'           => $ownerTransId,
                            'user_id'      => $owner->id,
                            'tipe'         => 'terima_setoran',
                            'sumber'       => \App\Services\PettyCashConstants::SUMBER_GADAI,
                            'nominal_cash' => 0,
                            'nominal_tf'   => $dendaAmount,
                            'keterangan'   => "Denda Kehilangan Struk Gadai: " . ($gadai->nasabah->user->nama ?? '-') . " (#{$gadai->id})",
                            'ref_table'    => 'tbl_gadai_active',
                            'ref_id'       => $gadai->id,
                        ]);

                        \App\Models\PettyCashSaldo::buatMutasi(
                            $owner->id, 
                            'owner', 
                            $dendaAmount,
                            "Denda Kehilangan Struk Gadai (#{$gadai->id})",
                            $gadai->id, 
                            'tbl_gadai_active', 
                            'transfer', 
                            'gadai'
                        );
                    }
                } else {
                    PettyCashSaldo::buatMutasi(
                        $adminId,
                        'admin',
                        $dendaAmount,
                        'Denda Kehilangan Struk Gadai ' . $gadai->slot_kode . ' (via cash)',
                        $gadai->id,
                        'tbl_gadai_active',
                        'cash',
                        'gadai'
                    );
                }

                \App\Models\PettyCashTransaksiNasabah::create([
                    'id' => \App\Helpers\IdGenerator::generate('petty_cash_transaksi_nasabah', 'PCTN', 'AD', 'TR'),
                    'admin_id' => $adminId,
                    'nasabah_id' => $gadai->nasabah_id,
                    'id_jns_transaksi' => \App\Services\PettyCashConstants::JNS_PMB,
                    'id_jns_via' => ($metodeDenda === 'transfer') ? \App\Services\PettyCashConstants::VIA_TF : \App\Services\PettyCashConstants::VIA_CS,
                    'id_jns_fitur' => \App\Services\PettyCashConstants::FITUR_GADAI,
                    'nominal' => $dendaAmount,
                    'status' => 'approved',
                    'keterangan' => 'Denda Kehilangan Struk Gadai ' . $gadai->slot_kode,
                    'ref_table' => 'tbl_gadai_active',
                    'ref_id' => $gadai->id,
                    'tgl_transaksi' => now()
                ]);
            }

            DB::commit();
            return back()->with('success', 'Barang pada slot ' . $gadai->slot_kode . ' berhasil diserahkan ke nasabah' . ($strukHilang ? ' dengan denda kehilangan struk' : '') . ' dan slot telah dikosongkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
