<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GadaiBaruSeeder extends Seeder
{
    public function run(): void
    {   
        // 3. Grid Slots (Contoh masing-masing 5x5 grid)
        $this->seedGrid('tbl_gadai_grid_electronic', 'EL', 5, 5);
        $this->seedGrid('tbl_gadai_grid_vehicle', 'VC', 5, 5);
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
