<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration//adawad
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tbl_pengajuan_penarikan_tabungan', function (Blueprint $table) {
            $table->foreignId('lokasi_temu')->nullable()->constrained('jns_lokasi_perusahaan')->onDelete('set null');
            $table->date('tanggal_janji_temu')->nullable();
            $table->time('waktu_janji_temu')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_pengajuan_penarikan_tabungan', function (Blueprint $table) {
            //
        });
    }
};
