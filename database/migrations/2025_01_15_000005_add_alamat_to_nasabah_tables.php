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
            $table->text('alamat')->after('jenis_kelamin');
        });

        Schema::table('tbl_nasabah_temp', function (Blueprint $table) {
            $table->text('alamat')->after('jenis_kelamin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_nasabah', function (Blueprint $table) {
            $table->dropColumn('alamat');
        });

        Schema::table('tbl_nasabah_temp', function (Blueprint $table) {
            $table->dropColumn('alamat');
        });
    }
};


