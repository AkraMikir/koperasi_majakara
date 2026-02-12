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
        // Force modify to TIME. 
        // Using DB::statement because Schema::table sometimes has issues with specific type modifications on existing data
        DB::statement('ALTER TABLE tbl_janji_temu_tabungan MODIFY COLUMN waktu_janji_temu TIME NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to nullable or original state if needed, but TIME is likely what we always wanted.
        // Keeping it as TIME is safer.
    }
};
