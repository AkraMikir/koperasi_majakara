<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_pencairan_deposito', function (Blueprint $table) {
            // Tipe pencairan (TF ke rekening atau langsung ke tabungan)
            $table->enum('jenis_pencairan', ['rek_nasabah', 'saldo_tabungan'])
                  ->default('rek_nasabah')
                  ->after('id_nasabah');
            // Foto bukti TF yang diupload oleh admin
            $table->string('foto_bukti_tf')->nullable()->after('nominal_akhir');
            // Admin yang melakukan pencairan
            $table->unsignedBigInteger('approved_by')->nullable()->after('catatan');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });

        // Update enum metode_pencairan to allow null (will keep for backward compat)
        // jenis_pencairan is the new source of truth
    }

    public function down(): void
    {
        Schema::table('tbl_pencairan_deposito', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['jenis_pencairan', 'foto_bukti_tf', 'approved_by']);
        });
    }
};
