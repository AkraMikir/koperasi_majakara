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

        // RESET ALL SLOTS
        DB::table('tbl_gadai_grid_electronic')->update([
            'is_occupied' => false,
            'active_gadai_id' => null
        ]);

        // 1. GADAI: SUDAH PENGAJUAN LUNAS TRANSFER (status pengajuan = pending, metode = transfer)
        $gLunasTfPending = GadaiActive::create([
            'nasabah_id' => $nasabahId,
            'kategori_id' => $kategoriId,
            'item_id' => $itemId,
            'lokasi_id' => $lokasiId,
            'slot_kode' => 'EL-0101',
            'slot_table' => 'electronic',
            'nominal_deal' => 1000000,
            'biaya_jasa' => 50000,
            'tgl_mulai' => $now->copy()->subDays(20),
            'tgl_jatuh_tempo' => $now->copy()->addDays(10)->endOfDay(),
            'tgl_tenggang' => $now->copy()->addDays(25)->endOfDay(),
            'status' => 'active',
            'admin_id' => $adminId,
            'denda_aktif' => 0,
            'biaya_inap' => 0
        ]);
        DB::table('tbl_gadai_grid_electronic')->where('kode_slot', 'EL-0101')->update(['is_occupied' => true, 'active_gadai_id' => $gLunasTfPending->id]);

        GadaiPengajuan::create([
            'nasabah_id' => $nasabahId,
            'gadai_active_id' => $gLunasTfPending->id,
            'jenis_pengajuan' => 'lunas',
            'metode' => 'transfer',
            'nominal' => 1050000,
            'status' => 'pending',
            'created_at' => $now->copy()->subHours(2)
        ]);

        // 2. GADAI: SUDAH PENGAJUAN LUNAS CASH (status pengajuan = pending, metode = cash)
        $gLunasCashPending = GadaiActive::create([
            'nasabah_id' => $nasabahId,
            'kategori_id' => $kategoriId,
            'item_id' => $itemId,
            'lokasi_id' => $lokasiId,
            'slot_kode' => 'EL-0102',
            'slot_table' => 'electronic',
            'nominal_deal' => 2000000,
            'biaya_jasa' => 100000,
            'tgl_mulai' => $now->copy()->subDays(20),
            'tgl_jatuh_tempo' => $now->copy()->addDays(10)->endOfDay(),
            'tgl_tenggang' => $now->copy()->addDays(25)->endOfDay(),
            'status' => 'active',
            'admin_id' => $adminId,
            'denda_aktif' => 0,
            'biaya_inap' => 0
        ]);
        DB::table('tbl_gadai_grid_electronic')->where('kode_slot', 'EL-0102')->update(['is_occupied' => true, 'active_gadai_id' => $gLunasCashPending->id]);

        GadaiPengajuan::create([
            'nasabah_id' => $nasabahId,
            'gadai_active_id' => $gLunasCashPending->id,
            'jenis_pengajuan' => 'lunas',
            'metode' => 'cash',
            'nominal' => 2100000,
            'status' => 'pending',
            'created_at' => $now->copy()->subHours(1)
        ]);

        // 3. GADAI: COUNTDOWN PENGAMBILAN BARANG TRANSFER (status gadai = lunas, metode pengajuan lunas = transfer)
        $gCountdownTf = GadaiActive::create([
            'nasabah_id' => $nasabahId,
            'kategori_id' => $kategoriId,
            'item_id' => $itemId,
            'lokasi_id' => $lokasiId,
            'slot_kode' => 'EL-0103',
            'slot_table' => 'electronic',
            'nominal_deal' => 3000000,
            'biaya_jasa' => 150000,
            'tgl_mulai' => $now->copy()->subDays(30),
            'tgl_jatuh_tempo' => $now->copy()->subDays(2)->endOfDay(),
            'tgl_tenggang' => $now->copy()->addDays(13)->endOfDay(),
            'status' => 'lunas',
            'tgl_ambil_limit' => $now->copy()->addDays(12)->endOfDay(),
            'admin_id' => $adminId,
            'denda_aktif' => 0,
            'biaya_inap' => 0
        ]);
        DB::table('tbl_gadai_grid_electronic')->where('kode_slot', 'EL-0103')->update(['is_occupied' => true, 'active_gadai_id' => $gCountdownTf->id]);

        GadaiPengajuan::create([
            'nasabah_id' => $nasabahId,
            'gadai_active_id' => $gCountdownTf->id,
            'jenis_pengajuan' => 'lunas',
            'metode' => 'transfer',
            'nominal' => 3150000,
            'status' => 'approved',
            'admin_id' => $adminId,
            'processed_at' => $now->copy()->subDays(1)
        ]);

        GadaiPaymentLog::create([
            'gadai_active_id' => $gCountdownTf->id,
            'jenis_pembayaran' => 'tebus',
            'nominal' => 3150000,
            'metode' => 'transfer'
        ]);

        // 4. GADAI: COUNTDOWN PENGAMBILAN BARANG CASH (status gadai = lunas, metode pengajuan lunas = cash)
        $gCountdownCash = GadaiActive::create([
            'nasabah_id' => $nasabahId,
            'kategori_id' => $kategoriId,
            'item_id' => $itemId,
            'lokasi_id' => $lokasiId,
            'slot_kode' => 'EL-0104',
            'slot_table' => 'electronic',
            'nominal_deal' => 1500000,
            'biaya_jasa' => 75000,
            'tgl_mulai' => $now->copy()->subDays(30),
            'tgl_jatuh_tempo' => $now->copy()->subDays(2)->endOfDay(),
            'tgl_tenggang' => $now->copy()->addDays(13)->endOfDay(),
            'status' => 'lunas',
            'tgl_ambil_limit' => $now->copy()->addDays(12)->endOfDay(),
            'admin_id' => $adminId,
            'denda_aktif' => 0,
            'biaya_inap' => 0
        ]);
        DB::table('tbl_gadai_grid_electronic')->where('kode_slot', 'EL-0104')->update(['is_occupied' => true, 'active_gadai_id' => $gCountdownCash->id]);

        GadaiPengajuan::create([
            'nasabah_id' => $nasabahId,
            'gadai_active_id' => $gCountdownCash->id,
            'jenis_pengajuan' => 'lunas',
            'metode' => 'cash',
            'nominal' => 1575000,
            'status' => 'approved',
            'admin_id' => $adminId,
            'processed_at' => $now->copy()->subDays(1)
        ]);

        GadaiPaymentLog::create([
            'gadai_active_id' => $gCountdownCash->id,
            'jenis_pembayaran' => 'tebus',
            'nominal' => 1575000,
            'metode' => 'cash'
        ]);
    }
}
