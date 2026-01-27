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
        Schema::table('tbl_pengajuan_penarikan_tabungan', function (Blueprint $table) {
            $table->string('metode_transfer', 50)->nullable()->after('nominal');
            $table->string('no_rekening', 50)->nullable()->after('metode_transfer');
            $table->string('nama_bank', 100)->nullable()->after('no_rekening');
            $table->string('foto_bukti_tf_admin')->nullable()->after('nama_bank');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_pengajuan_penarikan_tabungan', function (Blueprint $table) {
            $table->dropColumn(['metode_transfer', 'no_rekening', 'nama_bank', 'foto_bukti_tf_admin']);
        });
    }
};
