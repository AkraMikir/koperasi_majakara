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
        Schema::create('master_bunga_pinjaman', function (Blueprint $table) {
            $table->id();
            $table->integer('durasi_min'); // Durasi minimal (bulan)
            $table->integer('durasi_max'); // Durasi maksimal (bulan)
            $table->decimal('bunga_persen', 5, 2); // Persentase bunga (misal 10.00 untuk 10%)
            $table->boolean('status_aktif')->default(true);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_bunga_pinjaman');
    }
};
