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
        Schema::table('tbl_pengajuan_tabungan', function (Blueprint $table) {
            $table->decimal('nominal', 15, 2)->after('id_anggota')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_pengajuan_tabungan', function (Blueprint $table) {
            $table->dropColumn('nominal');
        });
    }
};
