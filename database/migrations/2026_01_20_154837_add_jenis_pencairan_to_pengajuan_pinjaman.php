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
            $table->enum('jenis_pencairan', ['transfer', 'cash'])->default('transfer')->after('durasi');
        });

        Schema::table('tbl_pinjaman_h', function (Blueprint $table) {
            $table->string('foto_bukti_transfer')->nullable()->after('saldo_lebih');
            $table->string('foto_serah_terima')->nullable()->after('foto_bukti_transfer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_pengajuan_pinjaman', function (Blueprint $table) {
            $table->dropColumn('jenis_pencairan');
        });

        Schema::table('tbl_pinjaman_h', function (Blueprint $table) {
            $table->dropColumn(['foto_bukti_transfer', 'foto_serah_terima']);
        });
    }
};
