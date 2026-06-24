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
        Schema::table('tbl_nasabah', function (Blueprint $table) {
            $table->char('kode_pos', 5)->nullable()->after('alamat');
        });
        Schema::table('tbl_nasabah_temp', function (Blueprint $table) {
            $table->char('kode_pos', 5)->nullable()->after('alamat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_nasabah', function (Blueprint $table) {
            $table->dropColumn('kode_pos');
        });
        Schema::table('tbl_nasabah_temp', function (Blueprint $table) {
            $table->dropColumn('kode_pos');
        });
    }
};
