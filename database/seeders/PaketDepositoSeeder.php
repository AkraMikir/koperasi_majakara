<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaketDeposito;
use App\Models\KategoriDeposito;

class PaketDepositoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Ensure we have some categories
        $kategoriNames = [
            'Paling Diminati',
            'Promo Merdeka',
            'Flash Sale',
            'Special New Member',
            'High Return',
            'Investasi Aman',
            'Pilihan Utama',
        ];

        $kategoriIds = [];
        foreach ($kategoriNames as $name) {
            $kat = KategoriDeposito::firstOrCreate(
                ['nama_kategori' => $name],
                ['keterangan' => 'Kategori promo ' . $name, 'status' => 'aktif']
            );
            $kategoriIds[] = $kat->id;
        }

        $tenors = [1, 3, 6, 12, 24, 36];

        // 2. Create 10 Packages WITH category
        for ($i = 1; $i <= 10; $i++) {
            $tenor = $tenors[array_rand($tenors)];
            $kategoriId = $kategoriIds[array_rand($kategoriIds)];
            
            PaketDeposito::create([
                'nama_paket' => 'Deposito Promo ' . $i . ' (' . $tenor . ' Bulan)',
                'tenor_bulan' => $tenor,
                'suku_bunga' => rand(50, 120) / 10, // 5.0 to 12.0
                'minimal_nominal' => rand(1, 5) * 1000000, // 1jt to 5jt
                'maksimal_nominal' => rand(10, 50) * 10000000, // 100jt to 500jt
                'status' => 'aktif',
                'kategori_id' => $kategoriId,
                'keterangan' => 'Paket deposito unggulan dengan benefit ekstra.',
            ]);
        }

        // 3. Create 10 Packages WITHOUT category
        for ($i = 1; $i <= 10; $i++) {
            $tenor = $tenors[array_rand($tenors)];
            
            PaketDeposito::create([
                'nama_paket' => 'Deposito Reguler ' . $i . ' (' . $tenor . ' Bulan)',
                'tenor_bulan' => $tenor,
                'suku_bunga' => rand(30, 80) / 10, // 3.0 to 8.0
                'minimal_nominal' => rand(1, 5) * 1000000, // 1jt to 5jt
                'maksimal_nominal' => rand(10, 50) * 10000000, // 100jt to 500jt
                'status' => 'aktif',
                'kategori_id' => null,
                'keterangan' => 'Paket deposito standar untuk investasi aman.',
            ]);
        }
    }
}
