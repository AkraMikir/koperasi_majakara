<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Tambah ke tbl_pengajuan_tabungan
        Schema::table('tbl_pengajuan_tabungan', function (Blueprint $table) {
            $table->enum('metode_bayar', ['transfer_koperasi', 'transfer_admin', 'cash'])
                  ->default('transfer_koperasi')
                  ->after('nominal');
        });

        // Tambah ke tbl_janji_temu_tabungan  
        Schema::table('tbl_janji_temu_tabungan', function (Blueprint $table) {
            $table->enum('metode_bayar', ['transfer_koperasi', 'transfer_admin', 'cash'])
                  ->default('cash')
                  ->after('nominal');
        });
    }

    public function down()
    {
        Schema::table('tbl_pengajuan_tabungan', function (Blueprint $table) {
            $table->dropColumn('metode_bayar');
        });
        Schema::table('tbl_janji_temu_tabungan', function (Blueprint $table) {
            $table->dropColumn('metode_bayar');
        });
    }
};
