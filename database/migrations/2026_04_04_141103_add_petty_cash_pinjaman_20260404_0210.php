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
        Schema::table('tbl_pinjaman_h', function (Blueprint $table) {
            $table->boolean('is_petty_cash')->default(0)->after('lunas');
            $table->string('petty_cash_ref')->nullable()->after('is_petty_cash');
            $table->string('metode_pencairan')->nullable()->after('petty_cash_ref');
        });

        Schema::table('tbl_pengajuan_pembayaran_pinjaman', function (Blueprint $table) {
            $table->string('setoran_kantor_id')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_pinjaman_h', function (Blueprint $table) {
            $table->dropColumn(['is_petty_cash', 'petty_cash_ref', 'metode_pencairan']);
        });

        Schema::table('tbl_pengajuan_pembayaran_pinjaman', function (Blueprint $table) {
            $table->dropColumn('setoran_kantor_id');
        });
    }
};
