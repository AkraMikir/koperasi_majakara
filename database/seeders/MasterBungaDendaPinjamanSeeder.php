<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterBungaPinjaman;
use App\Models\MasterDendaPinjaman;
use Illuminate\Support\Facades\DB;

class MasterBungaDendaPinjamanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('master_bunga_pinjaman')->truncate();
        DB::table('master_denda_pinjaman')->truncate();

        // Insert master data bunga pinjaman
        // Range: 1-3 bulan 10%, 4-6 bulan 12%, bertambah 2% setiap 3 bulan sampai 24 bulan
        $bungaData = [
            ['durasi_min' => 1, 'durasi_max' => 3, 'bunga_persen' => 10.00, 'keterangan' => 'Pinjaman 1-3 bulan'],
            ['durasi_min' => 4, 'durasi_max' => 6, 'bunga_persen' => 12.00, 'keterangan' => 'Pinjaman 4-6 bulan'],
            ['durasi_min' => 7, 'durasi_max' => 9, 'bunga_persen' => 14.00, 'keterangan' => 'Pinjaman 7-9 bulan'],
            ['durasi_min' => 10, 'durasi_max' => 12, 'bunga_persen' => 16.00, 'keterangan' => 'Pinjaman 10-12 bulan'],
            ['durasi_min' => 13, 'durasi_max' => 15, 'bunga_persen' => 18.00, 'keterangan' => 'Pinjaman 13-15 bulan'],
            ['durasi_min' => 16, 'durasi_max' => 18, 'bunga_persen' => 20.00, 'keterangan' => 'Pinjaman 16-18 bulan'],
            ['durasi_min' => 19, 'durasi_max' => 21, 'bunga_persen' => 22.00, 'keterangan' => 'Pinjaman 19-21 bulan'],
            ['durasi_min' => 22, 'durasi_max' => 24, 'bunga_persen' => 24.00, 'keterangan' => 'Pinjaman 22-24 bulan'],
        ];

        foreach ($bungaData as $data) {
            MasterBungaPinjaman::create($data);
        }

        // Insert master data denda pinjaman
        MasterDendaPinjaman::create([
            'denda_persen' => 0.30, // 0.3% per hari
            'status_aktif' => true,
            'keterangan' => 'Denda pinjaman 0.3% per hari',
        ]);

        $this->command->info('Master data bunga dan denda pinjaman berhasil di-seed!');
    }
}
