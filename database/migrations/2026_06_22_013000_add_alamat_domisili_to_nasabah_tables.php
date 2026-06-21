<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_nasabah', function (Blueprint $table) {
            $table->text('alamat_domisili')->nullable()->after('alamat');
        });

        Schema::table('tbl_nasabah_temp', function (Blueprint $table) {
            $table->text('alamat_domisili')->nullable()->after('alamat');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_nasabah', function (Blueprint $table) {
            $table->dropColumn('alamat_domisili');
        });

        Schema::table('tbl_nasabah_temp', function (Blueprint $table) {
            $table->dropColumn('alamat_domisili');
        });
    }
};
