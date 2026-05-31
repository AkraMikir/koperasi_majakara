<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_perubahan_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_nasabah')->index()->constrained('tbl_nasabah')->onDelete('cascade');
            $table->enum('jenis_data', ['data_user', 'data_pribadi', 'data_ktp', 'pekerjaan', 'rekening', 'kontak_darurat'])->index();
            $table->json('data_lama')->nullable();
            $table->json('data_baru');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->index();
            $table->text('catatan_admin')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_perubahan_data');
    }
};
