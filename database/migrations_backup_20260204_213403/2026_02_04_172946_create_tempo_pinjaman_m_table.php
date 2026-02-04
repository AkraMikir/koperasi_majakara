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
        Schema::create('tempo_pinjaman_m', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->string('pinjaman_id', 30)->index();
            $table->integer('no_urut');
            $table->date('tgl_jatuh_tempo');
            $table->decimal('jumlah_tagihan', 15, 2);
            $table->decimal('jumlah_terbayar', 15, 2)->nullable()->default(0);
            $table->decimal('denda', 15, 2)->nullable()->default(0);
            $table->timestamp('tgl_bayar')->nullable();
            $table->enum('status_bayar', ['belum', 'lunas', 'telat'])->nullable()->default('belum');
            $table->timestamps();
            
            $table->foreign('pinjaman_id')->references('id')->on('tbl_pinjaman_h')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tempo_pinjaman_m');
    }
};
