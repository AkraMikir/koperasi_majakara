<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JnsAkunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'kode_akun' => 'TAB',
                'nama_akun' => 'Tabungan',
                'deskripsi' => 'Akun untuk transaksi tabungan/simpanan',
                'prefix_id' => 'TAB',
                'is_active' => true,
            ],
            [
                'kode_akun' => 'PNJ',
                'nama_akun' => 'Pinjaman',
                'deskripsi' => 'Akun untuk transaksi pinjaman',
                'prefix_id' => 'PNJ',
                'is_active' => true,
            ],
            [
                'kode_akun' => 'DEP',
                'nama_akun' => 'Deposito',
                'deskripsi' => 'Akun untuk transaksi deposito',
                'prefix_id' => 'DEP',
                'is_active' => true,
            ],
            [
                'kode_akun' => 'GDI',
                'nama_akun' => 'Gadai',
                'deskripsi' => 'Akun untuk transaksi gadai',
                'prefix_id' => 'GDI',
                'is_active' => true,
            ],
        ];

        foreach ($data as $item) {
            \App\Models\JnsAkun::create($item);
        }
    }
}
