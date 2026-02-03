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
        // Drop old bukti foto tables
        Schema::dropIfExists('tbl_bukti_foto_pembayaran_pinjaman');
        Schema::dropIfExists('tbl_bukti_foto_pinjaman');
        Schema::dropIfExists('tbl_bukti_foto_tabungan');
        
        // Drop old master tables
        Schema::dropIfExists('jns_akun');
        Schema::dropIfExists('jns_deposito');
        Schema::dropIfExists('suku_bunga');
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
