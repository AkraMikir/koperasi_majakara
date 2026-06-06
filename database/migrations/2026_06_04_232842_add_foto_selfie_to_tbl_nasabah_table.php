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
            $table->string('foto_selfie')->nullable()->after('foto_kk');
        });

        Schema::table('tbl_nasabah_temp', function (Blueprint $table) {
            $table->string('foto_selfie')->nullable()->after('foto_kk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_nasabah', function (Blueprint $table) {
            $table->dropColumn('foto_selfie');
        });

        Schema::table('tbl_nasabah_temp', function (Blueprint $table) {
            $table->dropColumn('foto_selfie');
        });
    }
};
