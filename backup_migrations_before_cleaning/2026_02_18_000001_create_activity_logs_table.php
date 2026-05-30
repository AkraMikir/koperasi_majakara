<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('user_name');              // Nama user saat aksi (snapshot)
            $table->string('user_role', 30);          // nasabah | admin_operasional | admin_utama
            $table->string('action', 100);            // approve_setoran, submit_pengajuan_pinjaman, dll.
            $table->string('module', 50);             // tabungan | pinjaman | nasabah | master_data | akun
            $table->text('description');              // Kalimat deskriptif untuk tampilan
            $table->string('subject_type')->nullable(); // Model class (e.g. PengajuanTabungan)
            $table->string('subject_id')->nullable();   // ID objek yang terdampak
            $table->json('properties')->nullable();     // Data tambahan: nominal, nama nasabah, dll.
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();

            // Indexes untuk query performa
            $table->index('user_id');
            $table->index('user_role');
            $table->index('module');
            $table->index('action');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
