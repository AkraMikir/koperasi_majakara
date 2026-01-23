<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tbl_pengajuan_pinjaman', function (Blueprint $table) {
            // Ubah durasi dari char(1) menjadi integer untuk mendukung durasi lebih dari 9
            $table->integer('durasi')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_pengajuan_pinjaman', function (Blueprint $table) {
            // Kembalikan ke char(1) jika rollback
            $table->char('durasi', 1)->change();
        });
    }
};
