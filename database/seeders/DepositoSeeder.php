<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepositoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Jenis Deposito
        DB::table('jns_deposito')->insert([
            [
                'nama_jenis' => 'Deposito Reguler',
                'deskripsi' => 'Deposito dengan bunga standar untuk semua nasabah',
                'status_aktif' => 'y',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jenis' => 'Deposito Berjangka',
                'deskripsi' => 'Deposito dengan jangka waktu tertentu dan bunga lebih tinggi',
                'status_aktif' => 'y',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jenis' => 'Deposito On Call',
                'deskripsi' => 'Deposito dengan fleksibilitas pencairan',
                'status_aktif' => 'y',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Seed Tenor Deposito
        DB::table('jns_tenor_deposito')->insert([
            [
                'tenor_hari' => 30,
                'tenor_bulan' => 1,
                'aktif' => 'y',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenor_hari' => 90,
                'tenor_bulan' => 3,
                'aktif' => 'y',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenor_hari' => 180,
                'tenor_bulan' => 6,
                'aktif' => 'y',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenor_hari' => 365,
                'tenor_bulan' => 12,
                'aktif' => 'y',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Seed Suku Bunga Deposito
        $tenors = DB::table('jns_tenor_deposito')->get();
        
        foreach ($tenors as $tenor) {
            // Bunga untuk nominal kecil (< 10 juta)
            DB::table('suku_bunga_deposito')->insert([
                [
                    'tenor_id' => $tenor->id,
                    'min_nominal' => 1000000.00,
                    'max_nominal' => 9999999.99,
                    'bunga' => 0.0450 + ($tenor->tenor_bulan * 0.001), // 4.5% + tambahan per bulan
                    'status' => 'aktif',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);

            // Bunga untuk nominal sedang (10-50 juta)
            DB::table('suku_bunga_deposito')->insert([
                [
                    'tenor_id' => $tenor->id,
                    'min_nominal' => 10000000.00,
                    'max_nominal' => 49999999.99,
                    'bunga' => 0.0500 + ($tenor->tenor_bulan * 0.001), // 5% + tambahan per bulan
                    'status' => 'aktif',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);

            // Bunga untuk nominal besar (>= 50 juta)
            DB::table('suku_bunga_deposito')->insert([
                [
                    'tenor_id' => $tenor->id,
                    'min_nominal' => 50000000.00,
                    'max_nominal' => null,
                    'bunga' => 0.0550 + ($tenor->tenor_bulan * 0.001), // 5.5% + tambahan per bulan
                    'status' => 'aktif',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }
}
