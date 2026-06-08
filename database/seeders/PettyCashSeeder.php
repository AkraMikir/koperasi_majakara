<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Nasabah;
use App\Models\PettyCashSaldo;
use App\Models\PettyCashOwnerTransaksi;
use App\Models\PettyCashPenerimaan;
use App\Models\PettyCashTransaksiNasabah;
use App\Models\PettyCashSetoranKantor;
use App\Helpers\IdGenerator;

class PettyCashSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('petty_cash_saldo')->truncate();
        DB::table('petty_cash_owner_transaksi')->truncate();
        DB::table('petty_cash_penerimaan')->truncate();
        DB::table('petty_cash_transaksi_nasabah')->truncate();
        DB::table('petty_cash_setoran_kantor')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Get Users
        $owner = User::where('role', 'admin_utama')->first();
        $admin = User::where('role', 'admin_operasional')->first();
        $nasabahUser = User::where('role', 'nasabah')->first();
        $nasabah = Nasabah::where('user_id', $nasabahUser->id)->first();

        if (!$owner || !$admin || !$nasabah) {
            return;
        }

        // 1. MODAL MASUK (Owner injects capital to Owner Ledger)
        $idOwnerTrans1 = IdGenerator::generate('petty_cash_owner_transaksi', 'PCOW', 'OW', 'TR');
        PettyCashOwnerTransaksi::create([
            'id' => $idOwnerTrans1,
            'user_id' => $owner->id,
            'tipe' => 'masuk',
            'sumber' => 'other',
            'nominal_cash' => 50000000.00, // 50 Million Cash
            'nominal_tf' => 100000000.00,  // 100 Million TF
            'keterangan' => 'Inject Modal Awal Koperasi Utama',
            'ref_id' => null,
            'ref_table' => null,
        ]);

        PettyCashSaldo::buatMutasi($owner->id, 'owner', 50000000.00, 'Modal Awal Cash Koperasi', $idOwnerTrans1, 'petty_cash_owner_transaksi', 'cash', 'other');
        PettyCashSaldo::buatMutasi($owner->id, 'owner', 100000000.00, 'Modal Awal Transfer Koperasi', $idOwnerTrans1, 'petty_cash_owner_transaksi', 'transfer', 'other');

        // 2. TF KE ADMIN (Owner sends Cash & Transfer balance to Admin Petty Cash)
        $idPenerimaan1 = IdGenerator::generate('petty_cash_penerimaan', 'PCP', 'OW', 'KRM');
        PettyCashPenerimaan::create([
            'id' => $idPenerimaan1,
            'owner_id' => $owner->id,
            'admin_id' => $admin->id,
            'sumber' => 'other',
            'nominal_tf' => 20000000.00, // 20 Million TF
            'nominal_cash' => 10000000.00, // 10 Million Cash
            'status' => 'approved',
            'keterangan' => 'Alokasi Petty Cash awal untuk Admin Operasional',
            'tgl_penerimaan' => now(),
        ]);

        // Deduct Owner Saldo
        PettyCashSaldo::buatMutasi($owner->id, 'owner', -10000000.00, 'Kirim Petty Cash ke Admin (Cash)', $idPenerimaan1, 'petty_cash_penerimaan', 'cash', 'other');
        PettyCashSaldo::buatMutasi($owner->id, 'owner', -20000000.00, 'Kirim Petty Cash ke Admin (Transfer)', $idPenerimaan1, 'petty_cash_penerimaan', 'transfer', 'other');

        // Record in Owner Trans
        $idOwnerTrans2 = IdGenerator::generate('petty_cash_owner_transaksi', 'PCOW', 'OW', 'TR');
        PettyCashOwnerTransaksi::create([
            'id' => $idOwnerTrans2,
            'user_id' => $owner->id,
            'tipe' => 'keluar',
            'sumber' => 'other',
            'nominal_cash' => 10000000.00,
            'nominal_tf' => 20000000.00,
            'keterangan' => 'Kirim dana operasional Petty Cash ke Admin',
            'ref_id' => $idPenerimaan1,
            'ref_table' => 'petty_cash_penerimaan',
        ]);

        // Add Admin Saldo
        PettyCashSaldo::buatMutasi($admin->id, 'admin', 10000000.00, 'Terima Petty Cash awal dari Owner (Cash)', $idPenerimaan1, 'petty_cash_penerimaan', 'cash', 'other');
        PettyCashSaldo::buatMutasi($admin->id, 'admin', 20000000.00, 'Terima Petty Cash awal dari Owner (Transfer)', $idPenerimaan1, 'petty_cash_penerimaan', 'transfer', 'other');

        // 3. TRANSAKSI NASABAH (Setoran & Penarikan Tabungan)
        $idViaCash = DB::table('jns_via')->where('kode', 'CS')->value('id');
        $idViaTf = DB::table('jns_via')->where('kode', 'TF')->value('id');
        $idTransStr = DB::table('jns_transaksi')->where('kode', 'STR')->value('id');
        $idTransPnr = DB::table('jns_transaksi')->where('kode', 'PNR')->value('id');
        $idFiturTab = DB::table('jns_fitur')->where('kode', 'T')->value('id');

        // A. Setoran Cash Nasabah (Admin receives cash from Nasabah)
        $idTransNas1 = IdGenerator::generate('petty_cash_transaksi_nasabah', 'PCTN', 'AD', 'TR');
        PettyCashTransaksiNasabah::create([
            'id' => $idTransNas1,
            'admin_id' => $admin->id,
            'nasabah_id' => $nasabah->id,
            'id_jns_transaksi' => $idTransStr,
            'id_jns_via' => $idViaCash,
            'id_jns_fitur' => $idFiturTab,
            'nominal' => 2500000.00, // 2.5 Million
            'status' => 'approved',
            'keterangan' => 'Setoran Tunai Tabungan Budi Santoso',
            'ref_table' => 'trans_tabungan',
            'ref_id' => '080620260001TCSSTR',
            'tgl_transaksi' => now(),
        ]);
        PettyCashSaldo::buatMutasi($admin->id, 'admin', 2500000.00, 'Setoran Tabungan Nasabah (Tunai)', $idTransNas1, 'petty_cash_transaksi_nasabah', 'cash', 'tabungan');

        // B. Penarikan Cash Nasabah (Admin gives cash to Nasabah)
        $idTransNas2 = IdGenerator::generate('petty_cash_transaksi_nasabah', 'PCTN', 'AD', 'TR');
        PettyCashTransaksiNasabah::create([
            'id' => $idTransNas2,
            'admin_id' => $admin->id,
            'nasabah_id' => $nasabah->id,
            'id_jns_transaksi' => $idTransPnr,
            'id_jns_via' => $idViaCash,
            'id_jns_fitur' => $idFiturTab,
            'nominal' => 1000000.00, // 1 Million
            'status' => 'approved',
            'keterangan' => 'Penarikan Tunai Tabungan Budi Santoso',
            'ref_table' => 'trans_tabungan',
            'ref_id' => '080620260001TCSPNR',
            'tgl_transaksi' => now(),
        ]);
        PettyCashSaldo::buatMutasi($admin->id, 'admin', -1000000.00, 'Penarikan Tabungan Nasabah (Tunai)', $idTransNas2, 'petty_cash_transaksi_nasabah', 'cash', 'other');

        // 4. SETORAN KANTOR (Admin returns/deposits accumulated cash to Owner)
        $idSetoran1 = IdGenerator::generate('petty_cash_setoran_kantor', 'PCS', 'AD', 'STR');
        PettyCashSetoranKantor::create([
            'id' => $idSetoran1,
            'admin_id' => $admin->id,
            'owner_id' => $owner->id,
            'total_setor' => 5000000.00,
            'nominal_cash' => 5000000.00,
            'nominal_tf' => 0.00,
            'data_potongan' => json_encode([]),
            'jumlah_nasabah' => 2,
            'sudah_setor_fisik' => true,
            'status' => 'approved_owner',
            'keterangan_admin' => 'Setoran sisa kas fisik admin operasional',
            'tgl_setoran' => now(),
        ]);

        // Deduct Admin Cash
        PettyCashSaldo::buatMutasi($admin->id, 'admin', -5000000.00, 'Setor sisa kas fisik ke Owner', $idSetoran1, 'petty_cash_setoran_kantor', 'cash', 'other');

        // Add Owner Cash
        PettyCashSaldo::buatMutasi($owner->id, 'owner', 5000000.00, 'Terima setoran kas fisik dari Admin', $idSetoran1, 'petty_cash_setoran_kantor', 'cash', 'other');

        // Owner Trans Log
        $idOwnerTrans3 = IdGenerator::generate('petty_cash_owner_transaksi', 'PCOW', 'OW', 'TR');
        PettyCashOwnerTransaksi::create([
            'id' => $idOwnerTrans3,
            'user_id' => $owner->id,
            'tipe' => 'masuk',
            'sumber' => 'other',
            'nominal_cash' => 5000000.00,
            'nominal_tf' => 0.00,
            'keterangan' => 'Terima Setoran Kantor dari Admin',
            'ref_id' => $idSetoran1,
            'ref_table' => 'petty_cash_setoran_kantor',
        ]);
    }
}
