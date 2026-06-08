<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GadaiActive;
use App\Models\GadaiMasterItem;
use App\Models\GadaiMasterKategori;
use App\Models\Nasabah;
use App\Models\JnsLokasiPerusahaan;
use App\Models\User;
use App\Models\GadaiPengajuan;
use App\Models\GadaiPaymentLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GadaiTestSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign keys for safe truncation
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('tbl_gadai_pengajuan')->truncate();
        DB::table('tbl_gadai_payment_log')->truncate();
        DB::table('tbl_gadai_active')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Reset electronic grid slots
        DB::table('tbl_gadai_grid_electronic')->update([
            'is_occupied' => false,
            'active_gadai_id' => null
        ]);

        $nasabahId = Nasabah::first()?->id ?? 1;
        $kategoriId = GadaiMasterKategori::first()?->id ?? 1;
        $itemId = GadaiMasterItem::first()?->id ?? 1;
        $lokasiId = JnsLokasiPerusahaan::first()?->id ?? 1;
        $adminId = User::where('role', 'admin_operasional')->first()?->id ?? 1;

        $now = Carbon::now();

        // 1. GADAI AWAL (AKTIF)
        $gAwal = GadaiActive::create([
            'nasabah_id' => $nasabahId,
            'kategori_id' => $kategoriId,
            'item_id' => $itemId,
            'lokasi_id' => $lokasiId,
            'slot_kode' => 'EL-0101',
            'slot_table' => 'electronic',
            'nominal_deal' => 1000000,
            'biaya_jasa' => 50000,
            'tgl_mulai' => $now->copy()->subDays(5),
            'tgl_jatuh_tempo' => $now->copy()->addDays(25)->endOfDay(),
            'tgl_tenggang' => $now->copy()->addDays(40)->endOfDay(),
            'status' => 'active',
            'admin_id' => $adminId,
            'denda_aktif' => 0,
            'biaya_inap' => 0
        ]);
        DB::table('tbl_gadai_grid_electronic')->where('kode_slot', 'EL-0101')->update(['is_occupied' => true, 'active_gadai_id' => $gAwal->id]);

        // 2. GADAI DENGAN PERPANJANGAN
        $gPerpanjang = GadaiActive::create([
            'nasabah_id' => $nasabahId,
            'kategori_id' => $kategoriId,
            'item_id' => $itemId,
            'lokasi_id' => $lokasiId,
            'slot_kode' => 'EL-0102',
            'slot_table' => 'electronic',
            'nominal_deal' => 2000000,
            'biaya_jasa' => 100000,
            'tgl_mulai' => $now->copy()->subDays(35),
            'tgl_jatuh_tempo' => $now->copy()->addDays(25)->endOfDay(),
            'tgl_tenggang' => $now->copy()->addDays(40)->endOfDay(),
            'status' => 'active',
            'admin_id' => $adminId,
            'denda_aktif' => 0,
            'biaya_inap' => 0,
            'jumlah_perpanjangan' => 1
        ]);
        DB::table('tbl_gadai_grid_electronic')->where('kode_slot', 'EL-0102')->update(['is_occupied' => true, 'active_gadai_id' => $gPerpanjang->id]);

        // Pengajuan perpanjangan approved
        $pPerpanjang = GadaiPengajuan::create([
            'nasabah_id' => $nasabahId,
            'gadai_active_id' => $gPerpanjang->id,
            'jenis_pengajuan' => 'perpanjang',
            'metode' => 'transfer',
            'nominal' => 100000,
            'status' => 'approved',
            'admin_id' => $adminId,
            'processed_at' => $now->copy()->subDays(5)
        ]);

        GadaiPaymentLog::create([
            'gadai_active_id' => $gPerpanjang->id,
            'jenis_pembayaran' => 'perpanjangan',
            'nominal' => 100000,
            'metode' => 'transfer'
        ]);

        // 3. GADAI SELESAI CASH (Lunas tunai & barang dikembalikan)
        $gSelesaiCash = GadaiActive::create([
            'nasabah_id' => $nasabahId,
            'kategori_id' => $kategoriId,
            'item_id' => $itemId,
            'lokasi_id' => $lokasiId,
            'slot_kode' => 'EL-0103',
            'slot_table' => 'electronic',
            'nominal_deal' => 1500000,
            'biaya_jasa' => 75000,
            'tgl_mulai' => $now->copy()->subDays(30),
            'tgl_jatuh_tempo' => $now->copy()->subDays(2)->endOfDay(),
            'tgl_tenggang' => $now->copy()->addDays(13)->endOfDay(),
            'status' => 'returned',
            'admin_id' => $adminId,
            'denda_aktif' => 0,
            'biaya_inap' => 0
        ]);
        // Slot is freed (is_occupied = false)

        // Pengajuan lunas cash approved
        GadaiPengajuan::create([
            'nasabah_id' => $nasabahId,
            'gadai_active_id' => $gSelesaiCash->id,
            'jenis_pengajuan' => 'lunas',
            'metode' => 'cash',
            'nominal' => 1575000,
            'status' => 'approved',
            'admin_id' => $adminId,
            'processed_at' => $now->copy()->subDays(1)
        ]);

        GadaiPaymentLog::create([
            'gadai_active_id' => $gSelesaiCash->id,
            'jenis_pembayaran' => 'tebus',
            'nominal' => 1575000,
            'metode' => 'cash'
        ]);

        // 4. GADAI SELESAI TRANSFER & BARANG SUDAH DIAMBIL
        $gSelesaiTf = GadaiActive::create([
            'nasabah_id' => $nasabahId,
            'kategori_id' => $kategoriId,
            'item_id' => $itemId,
            'lokasi_id' => $lokasiId,
            'slot_kode' => 'EL-0104',
            'slot_table' => 'electronic',
            'nominal_deal' => 3000000,
            'biaya_jasa' => 150000,
            'tgl_mulai' => $now->copy()->subDays(30),
            'tgl_jatuh_tempo' => $now->copy()->subDays(2)->endOfDay(),
            'tgl_tenggang' => $now->copy()->addDays(13)->endOfDay(),
            'status' => 'returned', // returned means barang sudah diambil
            'admin_id' => $adminId,
            'denda_aktif' => 0,
            'biaya_inap' => 0
        ]);
        // Slot is freed

        // Pengajuan lunas transfer approved
        $pLunasTf = GadaiPengajuan::create([
            'nasabah_id' => $nasabahId,
            'gadai_active_id' => $gSelesaiTf->id,
            'jenis_pengajuan' => 'lunas',
            'metode' => 'transfer',
            'nominal' => 3150000,
            'status' => 'approved',
            'admin_id' => $adminId,
            'processed_at' => $now->copy()->subDays(2)
        ]);

        GadaiPaymentLog::create([
            'gadai_active_id' => $gSelesaiTf->id,
            'jenis_pembayaran' => 'tebus',
            'nominal' => 3150000,
            'metode' => 'transfer'
        ]);
    }
}
