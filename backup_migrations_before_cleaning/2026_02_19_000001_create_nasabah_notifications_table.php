<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nasabah_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_anggota')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->string('type', 50); // tabungan_setor, tabungan_tarik, pinjaman, pinjaman_pembayaran, janji_temu, profil, dll
            $table->string('title');
            $table->text('message')->nullable();
            $table->string('link', 500)->nullable();
            $table->string('related_id', 50)->nullable();
            $table->string('related_type', 50)->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['id_anggota', 'read_at', 'created_at']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nasabah_notifications');
    }
};
