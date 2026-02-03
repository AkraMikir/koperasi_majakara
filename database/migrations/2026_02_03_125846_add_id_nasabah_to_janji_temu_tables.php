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
        // Add id_nasabah and keterangan to janji temu tables
        // Note: tbl_janji_temu_tabungan, tbl_janji_temu_pinjaman, and tbl_janji_temu_pembayaran_pinjaman 
        // are created with id_nasabah already in 2026_02_03_131000_create_janji_temu_tables.php
        // This migration only handles deposito and gadai tables

        // 4. tbl_janji_temu_deposito (if exists)
        if (Schema::hasTable('tbl_janji_temu_deposito')) {
            Schema::table('tbl_janji_temu_deposito', function (Blueprint $table) {
                if (!Schema::hasColumn('tbl_janji_temu_deposito', 'id_nasabah')) {
                    $table->unsignedBigInteger('id_nasabah')->after('deposito_id');
                    $table->foreign('id_nasabah')->references('id')->on('tbl_nasabah')->onDelete('cascade');
                    $table->index('id_nasabah');
                }
                
                // Rename catatan to keterangan if exists
                if (Schema::hasColumn('tbl_janji_temu_deposito', 'catatan')) {
                    $table->renameColumn('catatan', 'keterangan');
                }
            });
        }

        // 5. tbl_janji_temu_gadai (if exists)
        if (Schema::hasTable('tbl_janji_temu_gadai')) {
            Schema::table('tbl_janji_temu_gadai', function (Blueprint $table) {
                if (!Schema::hasColumn('tbl_janji_temu_gadai', 'id_nasabah')) {
                    $table->unsignedBigInteger('id_nasabah')->after('gadai_id');
                    $table->foreign('id_nasabah')->references('id')->on('tbl_nasabah')->onDelete('cascade');
                    $table->index('id_nasabah');
                }
                
                // Rename catatan to keterangan if exists
                if (Schema::hasColumn('tbl_janji_temu_gadai', 'catatan')) {
                    $table->renameColumn('catatan', 'keterangan');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // tbl_janji_temu_tabungan, tbl_janji_temu_pinjaman, and tbl_janji_temu_pembayaran_pinjaman 
        // not handled here as they're created in separate migration (2026_02_03_131000_create_janji_temu_tables.php)

        if (Schema::hasTable('tbl_janji_temu_deposito')) {
            Schema::table('tbl_janji_temu_deposito', function (Blueprint $table) {
                if (Schema::hasColumn('tbl_janji_temu_deposito', 'id_nasabah')) {
                    $table->dropForeign(['id_nasabah']);
                    $table->dropColumn('id_nasabah');
                }
            });
        }

        if (Schema::hasTable('tbl_janji_temu_gadai')) {
            Schema::table('tbl_janji_temu_gadai', function (Blueprint $table) {
                if (Schema::hasColumn('tbl_janji_temu_gadai', 'id_nasabah')) {
                    $table->dropForeign(['id_nasabah']);
                    $table->dropColumn('id_nasabah');
                }
            });
        }
    }
};
