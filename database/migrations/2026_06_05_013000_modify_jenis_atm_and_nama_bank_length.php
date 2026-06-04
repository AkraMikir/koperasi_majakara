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
        Schema::table('tbl_data_rek_temp', function (Blueprint $table) {
            $table->string('jenis_atm', 255)->change();
        });

        Schema::table('tbl_data_rek', function (Blueprint $table) {
            $table->string('nama_bank', 255)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_data_rek_temp', function (Blueprint $table) {
            $table->string('jenis_atm', 20)->change();
        });

        Schema::table('tbl_data_rek', function (Blueprint $table) {
            $table->string('nama_bank', 20)->change();
        });
    }
};
