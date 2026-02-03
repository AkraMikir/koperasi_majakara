<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix Janji Temu Tabungan
        if (Schema::hasTable('tbl_janji_temu_tabungan')) {
            // Drop FK constraint if exists (mysql doesn't strictly enforce generic names, try standard naming)
            // Usually constraints are named table_column_foreign.
            // But since we are modifying column, simpler is to use raw SQL to modification.
            
            // Change id_pengajuan to VARCHAR(30)
            DB::statement("ALTER TABLE tbl_janji_temu_tabungan MODIFY COLUMN id_pengajuan VARCHAR(30) NOT NULL");
            
            // Convert collation to match user's default (likely utf8mb4_unicode_ci)
            DB::statement("ALTER TABLE tbl_janji_temu_tabungan CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        }

        // Fix Janji Temu Pinjaman
        if (Schema::hasTable('tbl_janji_temu_pinjaman')) {
            DB::statement("ALTER TABLE tbl_janji_temu_pinjaman MODIFY COLUMN id_pengajuan VARCHAR(30) NOT NULL");
            DB::statement("ALTER TABLE tbl_janji_temu_pinjaman CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        }

        // Fix Janji Temu Pembayaran Pinjaman
        if (Schema::hasTable('tbl_janji_temu_pembayaran_pinjaman')) {
            DB::statement("ALTER TABLE tbl_janji_temu_pembayaran_pinjaman MODIFY COLUMN id_pengajuan VARCHAR(30) NOT NULL");
            DB::statement("ALTER TABLE tbl_janji_temu_pembayaran_pinjaman CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert is risky as data might be strings now.
    }
};
