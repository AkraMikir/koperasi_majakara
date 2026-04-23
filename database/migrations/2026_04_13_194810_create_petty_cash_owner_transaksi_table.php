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
        Schema::create('petty_cash_owner_transaksi', function (Blueprint $table) {
            $table->string('id', 30)->primary();  // PCOW-130426-001
            $table->foreignId('user_id')->constrained('users');
            $table->enum('tipe', ['masuk', 'keluar', 'kirim_admin_hold', 'terima_setoran']);
            $table->decimal('nominal_cash', 15, 2)->default(0);
            $table->decimal('nominal_tf', 15, 2)->default(0);
            $table->string('keterangan');
            $table->string('bukti_foto_cash')->nullable();
            $table->string('bukti_foto_tf')->nullable();
            $table->string('ref_id')->nullable();
            $table->string('ref_table')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('petty_cash_owner_transaksi');
    }
};
