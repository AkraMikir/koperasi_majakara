<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Kolom waktu_janji_temu harus TIME agar nilai '08:00:00' valid.
     * Jika kolom masih TIMESTAMP/DATE, MySQL bisa salah baca '08:00:00' sebagai '2008-00-00'.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE tbl_janji_temu_tabungan MODIFY waktu_janji_temu TIME NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE tbl_janji_temu_tabungan MODIFY waktu_janji_temu TIMESTAMP NOT NULL');
    }
};
