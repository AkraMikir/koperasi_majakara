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
        // Add id_nasabah and keterangan to all janji temu tables
        
        // 1. tbl_janji_temu_tabungan
        Schema::table('tbl_janji_temu_tabungan', function (Blueprint $table) {
            $table->unsignedBigInteger('id_nasabah')->after('id_pengajuan');
            $table->text('keterangan')->nullable()->after('waktu_janji_temu');
            
            $table->foreign('id_nasabah')->references('id')->on('tbl_nasabah')->onDelete('cascade');
            $table->index('id_nasabah');
        });

        // 2. tbl_janji_temu_pinjaman
        Schema::table('tbl_janji_temu_pinjaman', function (Blueprint $table) {
            $table->unsignedBigInteger('id_nasabah')->after('id_pengajuan');
            // keterangan already exists in this table
            
            $table->foreign('id_nasabah')->references('id')->on('tbl_nasabah')->onDelete('cascade');
            $table->index('id_nasabah');
        });

        // 3. tbl_janji_temu_pembayaran_pinjaman
        Schema::table('tbl_janji_temu_pembayaran_pinjaman', function (Blueprint $table) {
            $table->unsignedBigInteger('id_nasabah')->after('id_pengajuan');
            // keterangan already exists in this table
            
            $table->foreign('id_nasabah')->references('id')->on('tbl_nasabah')->onDelete('cascade');
            $table->index('id_nasabah');
        });

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
        // Remove added columns
        if (Schema::hasTable('tbl_janji_temu_tabungan')) {
            Schema::table('tbl_janji_temu_tabungan', function (Blueprint $table) {
                $table->dropForeign(['id_nasabah']);
                $table->dropColumn('id_nasabah');
                $table->dropColumn('keterangan');
            });
        }

        if (Schema::hasTable('tbl_janji_temu_pinjaman')) {
            Schema::table('tbl_janji_temu_pinjaman', function (Blueprint $table) {
                $table->dropForeign(['id_nasabah']);
                $table->dropColumn('id_nasabah');
            });
        }

        if (Schema::hasTable('tbl_janji_temu_pembayaran_pinjaman')) {
            Schema::table('tbl_janji_temu_pembayaran_pinjaman', function (Blueprint $table) {
                $table->dropForeign(['id_nasabah']);
                $table->dropColumn('id_nasabah');
            });
        }

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
