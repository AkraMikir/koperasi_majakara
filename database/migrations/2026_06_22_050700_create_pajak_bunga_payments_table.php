<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pajak_bunga_payments', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis_pajak', ['pph_pinjaman', 'pph_gadai', 'pph_deposito']);
            $table->unsignedTinyInteger('periode_bulan'); // 1-12
            $table->unsignedSmallInteger('periode_tahun');
            $table->decimal('jumlah_kotor', 15, 2)->default(0);    // basis perhitungan (realisasi)
            $table->decimal('tarif_persen', 5, 2)->default(0);      // 15.00 atau 20.00
            $table->decimal('jumlah_pajak', 15, 2)->default(0);     // kotor × tarif%
            $table->decimal('jumlah_bersih', 15, 2)->default(0);    // kotor - pajak
            $table->date('tanggal_bayar')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('bukti_bayar')->nullable();               // path file foto/PDF
            $table->enum('status', ['belum_bayar', 'sudah_bayar'])->default('belum_bayar');
            $table->unsignedBigInteger('dibuat_oleh')->nullable();
            $table->foreign('dibuat_oleh')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['jenis_pajak', 'periode_tahun', 'periode_bulan'], 'idx_pajak_jenis_periode');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pajak_bunga_payments');
    }
};
