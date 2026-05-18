<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GadaiMasterInapKendaraanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tbl_gadai_master_inap_kendaraan')->insert([
            [
                'golongan' => 'A',
                'jenis_kendaraan' => 'motor',
                'nominal_inap' => 50000.00,
                'keterangan' => 'motor matic dll',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'golongan' => 'B',
                'jenis_kendaraan' => 'mobil',
                'nominal_inap' => 100000.00,
                'keterangan' => 'mobil keluarga, sedan dll',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'golongan' => 'C',
                'jenis_kendaraan' => 'kendaraan C',
                'nominal_inap' => 150000.00,
                'keterangan' => 'kelas C',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'golongan' => 'D',
                'jenis_kendaraan' => 'kendaraan D',
                'nominal_inap' => 200000.00,
                'keterangan' => 'kelas D',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'golongan' => 'E',
                'jenis_kendaraan' => 'kendaraan E',
                'nominal_inap' => 250000.00,
                'keterangan' => 'kelas E',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'golongan' => 'F',
                'jenis_kendaraan' => 'kendaraan F',
                'nominal_inap' => 300000.00,
                'keterangan' => 'kelas F',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
