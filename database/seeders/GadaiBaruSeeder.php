<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GadaiBaruSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Kategori
        $kategoriElektronik = DB::table('tbl_gadai_master_kategori')->insertGetId([
            'kode_kategori' => 'electronic',
            'nama_kategori' => 'Elektronik',
            'rate_jasa' => 10.00,
            'rate_denda' => 5.00,
            'rate_inap_persen' => 0.00,
            'max_extend_default' => 3,
            'masa_gadai_hari' => 30,
            'masa_tenggang_hari' => 15,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $kategoriKendaraan = DB::table('tbl_gadai_master_kategori')->insertGetId([
            'kode_kategori' => 'vehicle',
            'nama_kategori' => 'Kendaraan',
            'rate_jasa' => 10.00,
            'rate_denda' => 5.00,
            'rate_inap_persen' => 0.00,
            'max_extend_default' => 3,
            'masa_gadai_hari' => 30,
            'masa_tenggang_hari' => 15,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $kategoriEmas = DB::table('tbl_gadai_master_kategori')->insertGetId([
            'kode_kategori' => 'gold',
            'nama_kategori' => 'Emas',
            'rate_jasa' => 3.50,
            'rate_denda' => 2.00,
            'rate_inap_persen' => 1.00,
            'max_extend_default' => 3,
            'masa_gadai_hari' => 30,
            'masa_tenggang_hari' => 15,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 2. Items
        DB::table('tbl_gadai_master_item')->insert([
            [
                'kategori_id' => $kategoriElektronik,
                'head_1' => 'Smartphone / HP',
                'file_pic' => null,
                'max_taksiran' => 5000000,
                'rate_inap_nominal' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kategori_id' => $kategoriElektronik,
                'head_1' => 'Laptop',
                'file_pic' => null,
                'max_taksiran' => 10000000,
                'rate_inap_nominal' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kategori_id' => $kategoriKendaraan,
                'head_1' => 'Motor Kelas A',
                'file_pic' => null,
                'max_taksiran' => 15000000,
                'rate_inap_nominal' => 50000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kategori_id' => $kategoriKendaraan,
                'head_1' => 'Mobil Kelas B',
                'file_pic' => null,
                'max_taksiran' => 50000000,
                'rate_inap_nominal' => 100000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kategori_id' => $kategoriEmas,
                'head_1' => 'Emas Batangan (per gram)',
                'file_pic' => null,
                'max_taksiran' => 1000000,
                'rate_inap_nominal' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // 3. Grid Slots (Contoh masing-masing 5x5 grid)
        $this->seedGrid('tbl_gadai_grid_electronic', 'EL', 5, 5);
        $this->seedGrid('tbl_gadai_grid_vehicle', 'VK', 5, 5);
        $this->seedGrid('tbl_gadai_grid_gold', 'EM', 5, 5);
    }

    private function seedGrid(string $tableName, string $prefix, int $maxBaris, int $maxKolom)
    {
        $data = [];
        for ($baris = 1; $baris <= $maxBaris; $baris++) {
            for ($kolom = 1; $kolom <= $maxKolom; $kolom++) {
                $barisStr = str_pad((string)$baris, 2, '0', STR_PAD_LEFT);
                $kolomStr = str_pad((string)$kolom, 2, '0', STR_PAD_LEFT);
                $kodeSlot = $prefix . '-' . $barisStr . $kolomStr;

                $data[] = [
                    'kode_slot' => $kodeSlot,
                    'baris' => $baris,
                    'kolom' => $kolom,
                    'is_occupied' => false,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }
        DB::table($tableName)->insert($data);
    }
}
