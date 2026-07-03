<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Drop unique constraints dari tabel temp agar multiple registration
     * attempts dengan data yang sama bisa coexist di temp table.
     * Validasi uniqueness dialihkan hanya ke tabel permanent.
     */
    public function up(): void
    {
        // Drop unique constraint dari users_temp.email
        Schema::table('users_temp', function (Blueprint $table) {
            $table->dropUnique(['email']);
        });

        // Drop unique constraint dari tbl_nasabah_temp.no_kk
        Schema::table('tbl_nasabah_temp', function (Blueprint $table) {
            $table->dropUnique(['no_kk']);
        });

        // Drop unique constraint dari tbl_data_ktp_temp.nik
        Schema::table('tbl_data_ktp_temp', function (Blueprint $table) {
            $table->dropUnique(['nik']);
        });
    }

    /**
     * Restore unique constraints jika rollback.
     */
    public function down(): void
    {
        Schema::table('users_temp', function (Blueprint $table) {
            $table->unique('email');
        });

        Schema::table('tbl_nasabah_temp', function (Blueprint $table) {
            $table->unique('no_kk');
        });

        Schema::table('tbl_data_ktp_temp', function (Blueprint $table) {
            $table->unique('nik');
        });
    }
};
