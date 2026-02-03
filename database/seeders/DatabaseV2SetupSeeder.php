<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseV2SetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting Database V2 Setup...');

        // 1. Jalankan Struktur V2 Utama (Drop & Recreate Tables)
        // Ini akan create tabel master data, trans_tabungan v2, dll.
        $this->call(RefactoringV2Seeder::class);
        
        // 2. Fix Master Data Pinjaman (isi ulang data bunga/denda)
        $this->call(MasterBungaDendaPinjamanSeeder::class);

        // 3. Fix Tipe Data Janji Temu (agar kompatibel dengan string ID)
        $this->call(FixJanjiTemuSeeder::class);

        // 4. Fix Collation & Create View Universal
        // Ini step paling penting untuk tampilan admin/nasabah
        $this->call(FixCollationViewSeeder::class);

        $this->command->info('=========================================');
        $this->command->info(' DATABASE V2 SETUP COMPLETED SUCCESSFULLY');
        $this->command->info('=========================================');
    }
}
