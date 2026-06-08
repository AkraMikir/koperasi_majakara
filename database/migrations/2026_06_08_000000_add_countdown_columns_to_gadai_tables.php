<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_gadai_master_kategori', function (Blueprint $table) {
            $table->integer('countdown_ambil_hari')->default(14)->after('masa_tenggang_hari');
        });

        Schema::table('tbl_gadai_active', function (Blueprint $table) {
            $table->dateTime('tgl_ambil_limit')->nullable()->after('tgl_tenggang');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_gadai_active', function (Blueprint $table) {
            $table->dropColumn('tgl_ambil_limit');
        });

        Schema::table('tbl_gadai_master_kategori', function (Blueprint $table) {
            $table->dropColumn('countdown_ambil_hari');
        });
    }
};
