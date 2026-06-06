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
        $motorA = DB::table('tbl_gadai_master_item')->where('head_1', 'Motor Kelas A')->first();
        $mobilB = DB::table('tbl_gadai_master_item')->where('head_1', 'Mobil Kelas B')->first();

        DB::table('tbl_gadai_master_inap_kendaraan')->truncate();

        DB::table('tbl_gadai_master_inap_kendaraan')->insert([
            [
                'golongan' => 'A',
                'jenis_kendaraan' => '< 150 cc',
                'nominal_inap' => 50000.00,
                'keterangan' => json_encode($motorA ? [$motorA->id] : []),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'golongan' => 'B',
                'jenis_kendaraan' => '>= 150 cc',
                'nominal_inap' => 100000.00,
                'keterangan' => json_encode($mobilB ? [$mobilB->id] : []),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
