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
            $table->foreignId('id_tujuan')->nullable()->after('id_anggota')->constrained('master_tujuan_pinjaman')->onDelete('set null');
        });

        Schema::table('tbl_pinjaman_h', function (Blueprint $table) {
            $table->foreignId('id_tujuan')->nullable()->after('id_anggota')->constrained('master_tujuan_pinjaman')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_pengajuan_pinjaman', function (Blueprint $table) {
            $table->dropForeign(['id_tujuan']);
            $table->dropColumn('id_tujuan');
        });

        Schema::table('tbl_pinjaman_h', function (Blueprint $table) {
            $table->dropForeign(['id_tujuan']);
            $table->dropColumn('id_tujuan');
        });
    }
};
