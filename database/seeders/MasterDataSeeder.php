<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Bersihkan tabel master data untuk mencegah duplikasi saat seeding ulang
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Truncate tables if exist
        DB::table('jns_lokasi_perusahaan')->truncate();
        DB::table('jns_angsuran_minggu')->truncate();
        DB::table('jns_angsuran_bulan')->truncate();
        
        // New Master Tables
        DB::table('jns_fitur')->truncate();
        DB::table('jns_via')->truncate();
        DB::table('jns_transaksi')->truncate();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Seed Lokasi Perusahaan
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
            [
                'nama_lokasi' => 'Cabang Bandung',
                'alamat_lengkap' => 'Jl. Sudirman No. 45, Bandung',
                'kota' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'tipe_lokasi' => 'Cabang',
                'status_aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_lokasi' => 'Cabang Surabaya',
                'alamat_lengkap' => 'Jl. Pemuda No. 78, Surabaya',
                'kota' => 'Surabaya',
                'provinsi' => 'Jawa Timur',
                'tipe_lokasi' => 'Cabang',
                'status_aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 2. Seed Angsuran Bulan (ket harus 1 karakter)
        DB::table('jns_angsuran_bulan')->insert([
            ['ket' => '1', 'aktif' => 'y', 'created_at' => now(), 'updated_at' => now()], // 1 bulan
            ['ket' => '3', 'aktif' => 'y', 'created_at' => now(), 'updated_at' => now()], // 3 bulan
            ['ket' => '6', 'aktif' => 'y', 'created_at' => now(), 'updated_at' => now()], // 6 bulan
            ['ket' => 'A', 'aktif' => 'y', 'created_at' => now(), 'updated_at' => now()], // 12 bulan
            ['ket' => 'B', 'aktif' => 'y', 'created_at' => now(), 'updated_at' => now()], // 24 bulan
        ]);

        // 3. Seed Angsuran Minggu (ket harus 1 karakter)
        DB::table('jns_angsuran_minggu')->insert([
            ['ket' => '4', 'aktif' => 'y', 'created_at' => now(), 'updated_at' => now()], // 4 minggu
            ['ket' => '8', 'aktif' => 'y', 'created_at' => now(), 'updated_at' => now()], // 8 minggu
            ['ket' => 'A', 'aktif' => 'y', 'created_at' => now(), 'updated_at' => now()], // 12 minggu
            ['ket' => 'B', 'aktif' => 'y', 'created_at' => now(), 'updated_at' => now()], // 16 minggu
            ['ket' => 'C', 'aktif' => 'y', 'created_at' => now(), 'updated_at' => now()], // 20 minggu
        ]);

        // 4. Seed jns_fitur
        DB::table('jns_fitur')->insert([
            ['kode' => 'T', 'nama' => 'Tabungan', 'deskripsi' => 'Fitur tabungan', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'P', 'nama' => 'Pinjaman', 'deskripsi' => 'Fitur pinjaman', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'D', 'nama' => 'Deposito', 'deskripsi' => 'Fitur deposito', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'G', 'nama' => 'Gadai', 'deskripsi' => 'Fitur gadai', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 5. Seed jns_via
        DB::table('jns_via')->insert([
            ['kode' => 'T', 'nama' => 'Transfer', 'deskripsi' => 'Via transfer bank', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'C', 'nama' => 'Cash', 'deskripsi' => 'Via tunai/cash', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 6. Seed jns_transaksi
        DB::table('jns_transaksi')->insert([
            ['kode' => 'STR', 'nama' => 'Setoran', 'deskripsi' => 'Setoran tabungan', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'PNR', 'nama' => 'Penarikan', 'deskripsi' => 'Penarikan tabungan', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'TRKT', 'nama' => 'Transaksi Tabungan', 'deskripsi' => 'Transaksi tabungan final', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'PNJ', 'nama' => 'Pengajuan Pinjaman', 'deskripsi' => 'Pengajuan pinjaman', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'PMB', 'nama' => 'Pembayaran', 'deskripsi' => 'Pembayaran angsuran', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'DPNJM', 'nama' => 'Data Pinjaman', 'deskripsi' => 'Header pinjaman', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'TPNJM', 'nama' => 'Tempo Pinjaman', 'deskripsi' => 'Jadwal angsuran', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
