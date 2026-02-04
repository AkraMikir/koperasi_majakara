<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_pengajuan_tabungan', function (Blueprint $table) {
            // Add column to differentiate transfer vs tunai (janji temu)
            $table->enum('foto_bukti_tf', ['transfer', 'tunai'])->default('transfer')->after('nominal');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_pengajuan_tabungan', function (Blueprint $table) {
            $table->dropColumn('foto_bukti_tf');
        });
    }
};
