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
        DB::table('suku_bunga')->truncate();
        DB::table('jns_angsuran_minggu')->truncate();
        DB::table('jns_angsuran_bulan')->truncate();
        DB::table('jns_lokasi_perusahaan')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Seed Lokasi Perusahaan
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

        // Seed Angsuran Bulan (ket harus 1 karakter)
        DB::table('jns_angsuran_bulan')->insert([
            ['ket' => '1', 'aktif' => 'y', 'created_at' => now(), 'updated_at' => now()], // 1 bulan
            ['ket' => '3', 'aktif' => 'y', 'created_at' => now(), 'updated_at' => now()], // 3 bulan
            ['ket' => '6', 'aktif' => 'y', 'created_at' => now(), 'updated_at' => now()], // 6 bulan
            ['ket' => 'A', 'aktif' => 'y', 'created_at' => now(), 'updated_at' => now()], // 12 bulan
            ['ket' => 'B', 'aktif' => 'y', 'created_at' => now(), 'updated_at' => now()], // 24 bulan
        ]);

        // Seed Angsuran Minggu (ket harus 1 karakter)
        DB::table('jns_angsuran_minggu')->insert([
            ['ket' => '4', 'aktif' => 'y', 'created_at' => now(), 'updated_at' => now()], // 4 minggu
            ['ket' => '8', 'aktif' => 'y', 'created_at' => now(), 'updated_at' => now()], // 8 minggu
            ['ket' => 'A', 'aktif' => 'y', 'created_at' => now(), 'updated_at' => now()], // 12 minggu
            ['ket' => 'B', 'aktif' => 'y', 'created_at' => now(), 'updated_at' => now()], // 16 minggu
            ['ket' => 'C', 'aktif' => 'y', 'created_at' => now(), 'updated_at' => now()], // 20 minggu
        ]);

        // Seed Suku Bunga
        DB::table('suku_bunga')->insert([
            [
                'jenis_bunga' => 'Bunga Pinjaman Reguler',
                'opsi_val' => 0.0150, // 1.5% per bulan
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jenis_bunga' => 'Bunga Pinjaman Khusus',
                'opsi_val' => 0.0200, // 2% per bulan
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jenis_bunga' => 'Bunga Tabungan',
                'opsi_val' => 0.0025, // 0.25% per bulan
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
