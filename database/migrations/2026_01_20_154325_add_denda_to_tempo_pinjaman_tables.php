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
        Schema::table('tempo_pinjaman_b', function (Blueprint $table) {
            $table->decimal('denda', 15, 2)->default(0)->after('jumlah_terbayar');
            $table->dateTime('tgl_bayar')->nullable()->after('denda');
        });

        Schema::table('tempo_pinjaman_m', function (Blueprint $table) {
            $table->decimal('denda', 15, 2)->default(0)->after('jumlah_terbayar');
            $table->dateTime('tgl_bayar')->nullable()->after('denda');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tempo_pinjaman_b', function (Blueprint $table) {
            $table->dropColumn(['denda', 'tgl_bayar']);
        });

        Schema::table('tempo_pinjaman_m', function (Blueprint $table) {
            $table->dropColumn(['denda', 'tgl_bayar']);
        });
    }
};
