<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GadaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Master Barang Gadai
        DB::table('m_barang_gadai')->insert([
            [
                'nama_barang' => 'Emas Batangan',
                'deskripsi' => 'Emas batangan dengan sertifikat resmi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_barang' => 'Perhiasan Emas',
                'deskripsi' => 'Perhiasan emas seperti kalung, gelang, cincin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_barang' => 'Laptop',
                'deskripsi' => 'Laptop dengan spesifikasi lengkap',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_barang' => 'Sepeda Motor',
                'deskripsi' => 'Sepeda motor dengan surat lengkap',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_barang' => 'Handphone',
                'deskripsi' => 'Smartphone dengan aksesoris lengkap',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_barang' => 'Elektronik',
                'deskripsi' => 'Barang elektronik seperti TV, Kulkas, AC',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Seed Gadai Spesial
        DB::table('tbl_gadai_spesial')->insert([
            [
                'nama' => 'Paket Reguler',
                'tmpl_250_ribu' => 'y',
                'tmpl_500_ribu' => 'y',
                'tmpl_1_juta' => 'n',
                'tmpl_2_juta' => 'n',
                'tmpl_3_juta' => 'n',
                'tmpl_4_juta' => 'n',
                'tmpl_lebih_dari_5_juta' => 'n',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Paket Premium',
                'tmpl_250_ribu' => 'y',
                'tmpl_500_ribu' => 'y',
                'tmpl_1_juta' => 'y',
                'tmpl_2_juta' => 'y',
                'tmpl_3_juta' => 'n',
                'tmpl_4_juta' => 'n',
                'tmpl_lebih_dari_5_juta' => 'n',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Paket VIP',
                'tmpl_250_ribu' => 'y',
                'tmpl_500_ribu' => 'y',
                'tmpl_1_juta' => 'y',
                'tmpl_2_juta' => 'y',
                'tmpl_3_juta' => 'y',
                'tmpl_4_juta' => 'y',
                'tmpl_lebih_dari_5_juta' => 'y',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
