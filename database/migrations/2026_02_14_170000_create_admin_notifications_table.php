<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50); // tabungan_setor, tabungan_tarik, pinjaman, pinjaman_pembayaran, janji_temu, dll
            $table->string('title');
            $table->text('message')->nullable();
            $table->string('link', 500)->nullable(); // URL untuk redirect saat diklik
            $table->string('related_id', 50)->nullable(); // ID pengajuan/related
            $table->string('related_type', 50)->nullable(); // pengajuan_tabungan, pengajuan_pinjaman, dll
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['read_at', 'created_at']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_notifications');
    }
};
