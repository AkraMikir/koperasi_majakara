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
        Schema::table('master_bunga_pinjaman', function (Blueprint $table) {
            $table->decimal('bunga_flat_hari', 5, 2)->nullable()->after('bunga_persen');
        });

        Schema::table('tbl_pengajuan_pinjaman', function (Blueprint $table) {
            $table->decimal('bunga_flat_hari', 5, 2)->nullable()->after('bunga_persen');
        });

        Schema::table('tbl_pinjaman_h', function (Blueprint $table) {
            $table->decimal('bunga_flat_hari', 5, 2)->nullable()->after('bunga');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_bunga_pinjaman', function (Blueprint $table) {
            $table->dropColumn('bunga_flat_hari');
        });

        Schema::table('tbl_pengajuan_pinjaman', function (Blueprint $table) {
            $table->dropColumn('bunga_flat_hari');
        });

        Schema::table('tbl_pinjaman_h', function (Blueprint $table) {
            $table->dropColumn('bunga_flat_hari');
        });
    }
};
