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
        Schema::create('master_denda_pinjaman', function (Blueprint $table) {
            $table->id();
            $table->decimal('denda_persen', 5, 2)->default(0.30); // Persentase denda per hari (0.30 = 0.3%)
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
        Schema::dropIfExists('master_denda_pinjaman');
    }
};
