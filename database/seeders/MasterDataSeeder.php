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
            ['ket' => 'A', 'aktif' => 'y', 'created_at' => now(), 'updated_at' => now()],
            ['ket' => 'B', 'aktif' => 'y', 'created_at' => now(), 'updated_at' => now()],
        ]);
        
        DB::table('jns_angsuran_minggu')->truncate();
        // ... (Optional, jika diperlukan)

        // 3. V2 Master Tables
        DB::table('jns_fitur')->truncate();
        DB::table('jns_fitur')->insert([
            ['kode' => 'T', 'nama' => 'Tabungan', 'deskripsi' => 'Fitur tabungan', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'P', 'nama' => 'Pinjaman', 'deskripsi' => 'Fitur pinjaman', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'D', 'nama' => 'Deposito', 'deskripsi' => 'Fitur deposito', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'G', 'nama' => 'Gadai', 'deskripsi' => 'Fitur gadai', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('jns_via')->truncate();
        DB::table('jns_via')->insert([
            ['kode' => 'TF', 'nama' => 'Transfer', 'deskripsi' => 'Via transfer bank', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'CS', 'nama' => 'Cash', 'deskripsi' => 'Via tunai/cash', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('jns_transaksi')->truncate();
        DB::table('jns_transaksi')->insert([
            // Tabungan
            ['kode' => 'STR', 'nama' => 'Setoran', 'deskripsi' => 'Setoran tabungan', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'PNR', 'nama' => 'Penarikan', 'deskripsi' => 'Penarikan tabungan', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'TRKT', 'nama' => 'Transaksi Tabungan', 'deskripsi' => 'Transaksi umum tabungan', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            
            // Pinjaman
            ['kode' => 'PNJ', 'nama' => 'Pengajuan', 'deskripsi' => 'Pengajuan pinjaman', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'PMB', 'nama' => 'Pembayaran', 'deskripsi' => 'Pembayaran angsuran pinjaman', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'PNCR', 'nama' => 'Pencairan', 'deskripsi' => 'Pencairan pinjaman', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'DPNJM', 'nama' => 'Data Pinjaman', 'deskripsi' => 'Data pinjaman', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'TPNJM', 'nama' => 'Tempo Pinjaman', 'deskripsi' => 'Tempo/jatuh tempo pinjaman', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            
            // Universal
            ['kode' => 'JNJT', 'nama' => 'Janji Temu', 'deskripsi' => 'Janji temu untuk transaksi', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
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
            ['durasi_min' => 1, 'durasi_max' => 3, 'bunga_persen' => 10.00, 'keterangan' => 'Pinjaman 1-3 bulan', 'status_aktif' => true, 'created_at' => now(), 'updated_at' => now()],
            ['durasi_min' => 4, 'durasi_max' => 6, 'bunga_persen' => 12.00, 'keterangan' => 'Pinjaman 4-6 bulan', 'status_aktif' => true, 'created_at' => now(), 'updated_at' => now()],
            ['durasi_min' => 7, 'durasi_max' => 9, 'bunga_persen' => 14.00, 'keterangan' => 'Pinjaman 7-9 bulan', 'status_aktif' => true, 'created_at' => now(), 'updated_at' => now()],
            ['durasi_min' => 10, 'durasi_max' => 12, 'bunga_persen' => 16.00, 'keterangan' => 'Pinjaman 10-12 bulan', 'status_aktif' => true, 'created_at' => now(), 'updated_at' => now()],
            ['durasi_min' => 13, 'durasi_max' => 15, 'bunga_persen' => 18.00, 'keterangan' => 'Pinjaman 13-15 bulan', 'status_aktif' => true, 'created_at' => now(), 'updated_at' => now()],
            ['durasi_min' => 16, 'durasi_max' => 18, 'bunga_persen' => 20.00, 'keterangan' => 'Pinjaman 16-18 bulan', 'status_aktif' => true, 'created_at' => now(), 'updated_at' => now()],
            ['durasi_min' => 19, 'durasi_max' => 21, 'bunga_persen' => 22.00, 'keterangan' => 'Pinjaman 19-21 bulan', 'status_aktif' => true, 'created_at' => now(), 'updated_at' => now()],
            ['durasi_min' => 22, 'durasi_max' => 24, 'bunga_persen' => 24.00, 'keterangan' => 'Pinjaman 22-24 bulan', 'status_aktif' => true, 'created_at' => now(), 'updated_at' => now()],
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

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
