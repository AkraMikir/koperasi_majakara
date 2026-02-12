<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration//qadawd
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengajuan_perubahan_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_nasabah');
            $table->enum('jenis_data', [
                'data_pribadi',      // tbl_nasabah
                'data_ktp',          // data_ktp
                'pekerjaan',         // pekerjaan
                'rekening',          // data_rek
                'kontak_darurat'     // darurat
            ]);
            $table->json('data_lama')->nullable();  // Data sebelum diubah
            $table->json('data_baru');              // Data yang ingin diubah
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('catatan_admin')->nullable(); // Alasan reject atau catatan
            $table->unsignedBigInteger('approved_by')->nullable(); // ID admin yang approve/reject
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('id_nasabah')->references('id')->on('tbl_nasabah')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');

            // Indexes
            $table->index('id_nasabah');
            $table->index('status');
            $table->index('jenis_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_perubahan_data');
    }
};
