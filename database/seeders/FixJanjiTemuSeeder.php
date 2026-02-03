<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixJanjiTemuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fix Janji Temu Tabungan
        if (Schema::hasTable('tbl_janji_temu_tabungan')) {
            $this->command->info('Fixing tbl_janji_temu_tabungan...');
            
            // Perlu hapus data lama dulu karena BIGINT -> VARCHAR bisa error jika ada data kotor
            // Tapi karena ini dev, truncate mungkin opsi.
            // Namun user mungkin mau simpan data. Kita coba modify langsung.
            
            // Drop FK constraints IF EXISTS (raw check)
            try {
                // Mencoba menebak nama constraint, atau abaikan error jika gagal
                // DB::statement("ALTER TABLE tbl_janji_temu_tabungan DROP FOREIGN KEY tbl_janji_temu_tabungan_id_pengajuan_foreign");
            } catch (\Exception $e) {}

            try {
                DB::statement("ALTER TABLE tbl_janji_temu_tabungan MODIFY COLUMN id_pengajuan VARCHAR(30) NOT NULL");
                DB::statement("ALTER TABLE tbl_janji_temu_tabungan CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                $this->command->info('tbl_janji_temu_tabungan FIXED');
            } catch (\Exception $e) {
                $this->command->error('Failed to fix tbl_janji_temu_tabungan: ' . $e->getMessage());
            }
        }

        // Fix Janji Temu Pinjaman
        if (Schema::hasTable('tbl_janji_temu_pinjaman')) {
            $this->command->info('Fixing tbl_janji_temu_pinjaman...');
            try {
                DB::statement("ALTER TABLE tbl_janji_temu_pinjaman MODIFY COLUMN id_pengajuan VARCHAR(30) NOT NULL");
                DB::statement("ALTER TABLE tbl_janji_temu_pinjaman CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                $this->command->info('tbl_janji_temu_pinjaman FIXED');
            } catch (\Exception $e) {
                $this->command->error('Failed to fix tbl_janji_temu_pinjaman: ' . $e->getMessage());
            }
        }

        // Fix Janji Temu Pembayaran Pinjaman
        if (Schema::hasTable('tbl_janji_temu_pembayaran_pinjaman')) {
            $this->command->info('Fixing tbl_janji_temu_pembayaran_pinjaman...');
            try {
                DB::statement("ALTER TABLE tbl_janji_temu_pembayaran_pinjaman MODIFY COLUMN id_pengajuan VARCHAR(30) NOT NULL");
                DB::statement("ALTER TABLE tbl_janji_temu_pembayaran_pinjaman CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                $this->command->info('tbl_janji_temu_pembayaran_pinjaman FIXED');
            } catch (\Exception $e) {
                $this->command->error('Failed to fix tbl_janji_temu_pembayaran_pinjaman: ' . $e->getMessage());
            }
        }
    }
}
