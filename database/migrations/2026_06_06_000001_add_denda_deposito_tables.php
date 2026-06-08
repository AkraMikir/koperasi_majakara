<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel master konfigurasi denda deposito
        Schema::create('master_denda_deposito', function (Blueprint $table) {
            $table->id();
            $table->decimal('denda_persen', 5, 2);
            $table->boolean('status_aktif')->default(true);
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });

        // Seed data awal: 0.25%
        DB::table('master_denda_deposito')->insert([
            'denda_persen'  => 0.25,
            'status_aktif'  => true,
            'keterangan'    => 'Denda pembatalan deposito sebelum jatuh tempo',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        // 2. Tambah kolom nominal_denda di tbl_pencairan_deposito
        Schema::table('tbl_pencairan_deposito', function (Blueprint $table) {
            $table->decimal('nominal_denda', 15, 2)->default(0)->after('nominal_akhir');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_pencairan_deposito', function (Blueprint $table) {
            $table->dropColumn('nominal_denda');
        });

        Schema::dropIfExists('master_denda_deposito');
    }
};
