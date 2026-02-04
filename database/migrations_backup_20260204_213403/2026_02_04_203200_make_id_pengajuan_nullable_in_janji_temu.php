<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_janji_temu_tabungan', function (Blueprint $table) {
            // Make id_pengajuan nullable because janji temu can be created without pengajuan
            $table->string('id_pengajuan', 30)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('tbl_janji_temu_tabungan', function (Blueprint $table) {
            // Revert back to NOT NULL
            $table->string('id_pengajuan', 30)->nullable(false)->change();
        });
    }
};
