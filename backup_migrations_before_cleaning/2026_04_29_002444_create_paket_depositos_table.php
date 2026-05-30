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
        Schema::create('paket_depositos', function (Blueprint $table) {
            $table->id();
            $table->string('nama_paket', 100);
            $table->integer('tenor_bulan');
            $table->decimal('suku_bunga', 5, 2);
            $table->bigInteger('minimal_nominal');
            $table->bigInteger('maksimal_nominal')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket_depositos');
    }
};
