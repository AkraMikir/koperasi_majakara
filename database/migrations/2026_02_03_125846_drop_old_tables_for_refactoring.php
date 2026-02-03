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
        // Drop old bukti foto tables that have been replaced by tbl_bukti_foto_universal
        Schema::dropIfExists('tbl_bukti_foto_pembayaran_pinjaman');
        Schema::dropIfExists('tbl_bukti_foto_pinjaman');
        Schema::dropIfExists('tbl_bukti_foto_tabungan');
        
        // Note: jns_akun, jns_deposito, and suku_bunga are NOT dropped here 
        // because they may still be referenced by other tables (deposito, gadai).
        // They will be replaced gradually as those modules are refactored.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: Reverting this migration would require recreating all dropped tables
        // with their original structure, which is complex and not recommended
        // This is a one-way migration for refactoring
    }
};
