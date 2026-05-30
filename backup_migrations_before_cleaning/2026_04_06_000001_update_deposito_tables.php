<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Hapus tabel janji_temu_deposito (tidak digunakan)
        Schema::dropIfExists('tbl_janji_temu_deposito');

        // 2. Perbaiki tbl_pengajuan_deposito:
        //    - Hapus kolom jenis_deposito (tidak dipakai)
        //    - Tambah catatan_admin dan approved_by
        Schema::table('tbl_pengajuan_deposito', function (Blueprint $table) {
            // Hapus kolom jenis_deposito jika ada
            if (Schema::hasColumn('tbl_pengajuan_deposito', 'jenis_deposito')) {
                $table->dropColumn('jenis_deposito');
            }
            // Tambah kolom baru untuk tracking admin
            if (!Schema::hasColumn('tbl_pengajuan_deposito', 'catatan_admin')) {
                $table->text('catatan_admin')->nullable()->after('catatan');
            }
            if (!Schema::hasColumn('tbl_pengajuan_deposito', 'approved_by')) {
                $table->unsignedBigInteger('approved_by')->nullable()->after('catatan_admin');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tbl_pengajuan_deposito', function (Blueprint $table) {
            $table->dropColumn(['catatan_admin', 'approved_by']);
            $table->unsignedBigInteger('jenis_deposito')->nullable()->after('tenor_id');
        });
    }
};
