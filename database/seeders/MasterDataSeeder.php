<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\MasterBungaPinjaman;
use App\Models\MasterDendaPinjaman;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // 1. Lokasi Perusahaan
        DB::table('jns_lokasi_perusahaan')->truncate();
        DB::table('jns_lokasi_perusahaan')->insert([
            [
                'nama_lokasi' => 'Kantor Pusat',
                'alamat_lengkap' => 'Jl. Raya Majakara No. 123, Jakarta Pusat',
                'kota' => 'Jakarta',
                'provinsi' => 'DKI Jakarta',
                'tipe_lokasi' => 'Kantor Pusat',
                'status_aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 2. Angsuran
        DB::table('jns_angsuran_bulan')->truncate();
        DB::table('jns_angsuran_bulan')->insert([
            ['ket' => '1', 'aktif' => 'y', 'created_at' => now(), 'updated_at' => now()],
            ['ket' => '3', 'aktif' => 'y', 'created_at' => now(), 'updated_at' => now()],
            ['ket' => '6', 'aktif' => 'y', 'created_at' => now(), 'updated_at' => now()],
            ['ket' => '9', 'aktif' => 'y', 'created_at' => now(), 'updated_at' => now()],
            ['ket' => '12', 'aktif' => 'y', 'created_at' => now(), 'updated_at' => now()],
            ['ket' => '15', 'aktif' => 'y', 'created_at' => now(), 'updated_at' => now()],
            ['ket' => '18', 'aktif' => 'y', 'created_at' => now(), 'updated_at' => now()],
            ['ket' => '21', 'aktif' => 'y', 'created_at' => now(), 'updated_at' => now()],
            ['ket' => '24', 'aktif' => 'y', 'created_at' => now(), 'updated_at' => now()],
        ]);
        
        DB::table('jns_angsuran_minggu')->truncate();
        // ... (Optional, jika diperlukan)

        // 3. V2 Master Tables
        DB::table('jns_fitur')->truncate();
        DB::table('jns_fitur')->insert([
            ['kode' => 'T', 'nama' => 'Tabungan', 'deskripsi' => 'Tabungan. ID: ddmmyyyy[SEQ(4)]TTFSTR/TCSSTR. Petty Cash Ref: PCTN/PC.', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'P', 'nama' => 'Pinjaman', 'deskripsi' => 'Pinjaman. ID: ddmmyyyy[SEQ(4)]PTFPNJ. Petty Cash Ref: PCTN/PC.', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'D', 'nama' => 'Deposito', 'deskripsi' => 'Deposito. No Rek: DP+yymmdd+[SEQ(4)]. Petty Cash Ref: PCP.', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'G', 'nama' => 'Gadai', 'deskripsi' => 'Gadai. ID: Auto-increment Integer. Petty Cash Ref: PCTN/PC.', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            // Petty Cash & Owner
            ['kode' => 'PCOW', 'nama' => 'Petty Cash Owner', 'deskripsi' => 'Transaksi Owner Wallet', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'PCTN', 'nama' => 'Petty Cash Transaksi Nasabah', 'deskripsi' => 'Transaksi Petty Cash Kasir & Nasabah', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'PCP', 'nama' => 'Petty Cash Penerimaan', 'deskripsi' => 'Dana Drop dari Owner ke Admin', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'PCS', 'nama' => 'Petty Cash Setoran Kantor', 'deskripsi' => 'Setoran Kasir ke Owner', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('jns_via')->truncate();
        DB::table('jns_via')->insert([
            ['kode' => 'TF', 'nama' => 'Transfer', 'deskripsi' => 'Via Transfer Bank (ID Via: TF)', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'CS', 'nama' => 'Cash', 'deskripsi' => 'Via Tunai/Cash (ID Via: CS/TN)', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            // Petty Cash & Owner Via
            ['kode' => 'OW', 'nama' => 'Owner Wallet', 'deskripsi' => 'Dompet Rekening Utama Owner', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'AD', 'nama' => 'Admin Petty Cash', 'deskripsi' => 'Kas Pegangan Admin/Operator', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('jns_transaksi')->truncate();
        DB::table('jns_transaksi')->insert([
            // Tabungan
            ['kode' => 'STR', 'nama' => 'Setoran', 'deskripsi' => 'Setoran (ID Transaksi: STR/PCS/KRM/PCR)', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'PNR', 'nama' => 'Penarikan', 'deskripsi' => 'Penarikan (ID Transaksi: PNR)', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'TRKT', 'nama' => 'Transaksi Tabungan', 'deskripsi' => 'Transaksi Tabungan (ID Transaksi: TRKT/TR)', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            
            // Pinjaman
            ['kode' => 'PNJ', 'nama' => 'Pengajuan', 'deskripsi' => 'Pengajuan Pinjaman (ID Transaksi: PNJ)', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'PMB', 'nama' => 'Pembayaran', 'deskripsi' => 'Pembayaran Angsuran Pinjaman (ID Transaksi: PMB/LUNAS)', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'PNCR', 'nama' => 'Pencairan', 'deskripsi' => 'Pencairan Pinjaman (ID Transaksi: PNCR)', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'DPNJM', 'nama' => 'Data Pinjaman', 'deskripsi' => 'Data Pinjaman (ID Transaksi: DPNJM)', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'TPNJM', 'nama' => 'Tempo Pinjaman', 'deskripsi' => 'Tempo Pinjaman (ID Transaksi: TPNJM)', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            
            // Universal
            ['kode' => 'JNJT', 'nama' => 'Janji Temu', 'deskripsi' => 'Janji Temu (ID Transaksi: JNJT)', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],

            // Petty Cash & Owner Transaksi
            ['kode' => 'TR', 'nama' => 'Transaksi Saldo', 'deskripsi' => 'Mutasi/Jurnal Kas Masuk-Keluar', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'KRM', 'nama' => 'Kirim Dana', 'deskripsi' => 'Kirim Petty Cash ke Operator', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'WD', 'nama' => 'Withdrawal', 'deskripsi' => 'Penarikan Kas oleh Owner', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 4. Tenor Deposito
        DB::table('jns_tenor_deposito')->truncate();
        DB::table('jns_tenor_deposito')->insert([
            ['tenor_hari' => 30, 'tenor_bulan' => 1, 'aktif' => 'y', 'created_at' => now(), 'updated_at' => now()],
            ['tenor_hari' => 90, 'tenor_bulan' => 3, 'aktif' => 'y', 'created_at' => now(), 'updated_at' => now()],
            ['tenor_hari' => 180, 'tenor_bulan' => 6, 'aktif' => 'y', 'created_at' => now(), 'updated_at' => now()],
            ['tenor_hari' => 365, 'tenor_bulan' => 12, 'aktif' => 'y', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 5. Bunga & Denda Pinjaman
        DB::table('master_bunga_pinjaman')->truncate();
        $bungaData = [
            ['durasi_min' => 1, 'durasi_max' => 3, 'durasi_pilihan' => 1, 'bunga_persen' => 10.00, 'bunga_flat_hari' => 0.33, 'keterangan' => 'Pinjaman 1-3 bulan', 'status_aktif' => true, 'created_at' => now(), 'updated_at' => now()],
            ['durasi_min' => 1, 'durasi_max' => 3, 'durasi_pilihan' => 3, 'bunga_persen' => 10.00, 'bunga_flat_hari' => 0.33, 'keterangan' => 'Pinjaman 1-3 bulan', 'status_aktif' => true, 'created_at' => now(), 'updated_at' => now()],
            ['durasi_min' => 4, 'durasi_max' => 6, 'durasi_pilihan' => 6, 'bunga_persen' => 12.00, 'bunga_flat_hari' => 0.40, 'keterangan' => 'Pinjaman 4-6 bulan', 'status_aktif' => true, 'created_at' => now(), 'updated_at' => now()],
            ['durasi_min' => 7, 'durasi_max' => 9, 'durasi_pilihan' => 9, 'bunga_persen' => 14.00, 'bunga_flat_hari' => 0.46, 'keterangan' => 'Pinjaman 7-9 bulan', 'status_aktif' => true, 'created_at' => now(), 'updated_at' => now()],
            ['durasi_min' => 10, 'durasi_max' => 12, 'durasi_pilihan' => 12, 'bunga_persen' => 16.00, 'bunga_flat_hari' => 0.53, 'keterangan' => 'Pinjaman 10-12 bulan', 'status_aktif' => true, 'created_at' => now(), 'updated_at' => now()],
            ['durasi_min' => 13, 'durasi_max' => 15, 'durasi_pilihan' => 15, 'bunga_persen' => 18.00, 'bunga_flat_hari' => 0.60, 'keterangan' => 'Pinjaman 13-15 bulan', 'status_aktif' => true, 'created_at' => now(), 'updated_at' => now()],
            ['durasi_min' => 16, 'durasi_max' => 18, 'durasi_pilihan' => 18, 'bunga_persen' => 20.00, 'bunga_flat_hari' => 0.66, 'keterangan' => 'Pinjaman 16-18 bulan', 'status_aktif' => true, 'created_at' => now(), 'updated_at' => now()],
            ['durasi_min' => 19, 'durasi_max' => 21, 'durasi_pilihan' => 21, 'bunga_persen' => 22.00, 'bunga_flat_hari' => 0.73, 'keterangan' => 'Pinjaman 19-21 bulan', 'status_aktif' => true, 'created_at' => now(), 'updated_at' => now()],
            ['durasi_min' => 22, 'durasi_max' => 24, 'durasi_pilihan' => 24, 'bunga_persen' => 24.00, 'bunga_flat_hari' => 0.80, 'keterangan' => 'Pinjaman 22-24 bulan', 'status_aktif' => true, 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('master_bunga_pinjaman')->insert($bungaData);
        
        DB::table('master_denda_pinjaman')->truncate();
        DB::table('master_denda_pinjaman')->insert([
            'denda_persen' => 0.30,
            'status_aktif' => true,
            'keterangan' => 'Denda pinjaman 0.3% per hari',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 6. Default Fallback OTP
        DB::table('master_default_otp')->truncate();
        DB::table('master_default_otp')->insert([
            'otp_code_hashed' => \Illuminate\Support\Facades\Hash::make('341234'),
            'used' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}