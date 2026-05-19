<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaketDeposito;
use App\Models\KategoriDeposito;
use Illuminate\Support\Facades\DB;

class PaketDepositoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Bersihkan data lama
        DB::table('paket_depositos')->delete();

        $kategoriId = null;
        $kategori = KategoriDeposito::firstOrCreate(
            ['nama_kategori' => 'Pilihan Utama'],
            ['keterangan' => 'Kategori Pilihan Utama', 'status' => 'aktif']
        );
        $kategoriId = $kategori->id;

        $pakets = [
            [
                'nama_paket' => 'Deposito 1 Bulan',
                'tenor_bulan' => 1,
                'suku_bunga' => 3.75,
                'minimal_nominal' => 1000000,
                'maksimal_nominal' => null,
                'status' => 'aktif',
                'kategori_id' => $kategoriId,
                'keterangan' => 'Mulai dari Rp 1.000.000',
            ],
            [
                'nama_paket' => 'Deposito 3 Bulan',
                'tenor_bulan' => 3,
                'suku_bunga' => 4.50,
                'minimal_nominal' => 1000000,
                'maksimal_nominal' => null,
                'status' => 'aktif',
                'kategori_id' => $kategoriId,
                'keterangan' => 'Mulai dari Rp 1.000.000',
            ],
            [
                'nama_paket' => 'Deposito 6 Bulan',
                'tenor_bulan' => 6,
                'suku_bunga' => 5.25,
                'minimal_nominal' => 1000000,
                'maksimal_nominal' => null,
                'status' => 'aktif',
                'kategori_id' => $kategoriId,
                'keterangan' => 'Mulai dari Rp 1.000.000',
            ],
            [
                'nama_paket' => 'Deposito 12 Bulan',
                'tenor_bulan' => 12,
                'suku_bunga' => 6.00,
                'minimal_nominal' => 1000000,
                'maksimal_nominal' => null,
                'status' => 'aktif',
                'kategori_id' => $kategoriId,
                'keterangan' => 'Mulai dari Rp 1.000.000',
            ],
        ];

        foreach ($pakets as $paket) {
            PaketDeposito::create($paket);
        }
        
        $this->command->info('✅ Paket Deposito berhasil di-seed:');
        $this->command->info('   1 bln → 3.75% | 3 bln → 4.50% | 6 bln → 5.25% | 12 bln → 6.00%');
    }
}
