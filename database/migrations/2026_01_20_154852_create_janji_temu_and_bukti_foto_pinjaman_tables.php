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
        // Tabel janji temu untuk pencairan tunai
        Schema::create('tbl_janji_temu_pinjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengajuan')->constrained('tbl_pengajuan_pinjaman')->onDelete('cascade');
            $table->foreignId('lokasi_temu')->constrained('jns_lokasi_perusahaan')->onDelete('cascade');
            $table->decimal('nominal', 15, 2);
            $table->dateTime('tanggal_janji_temu');
            $table->time('waktu_janji_temu');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // Tabel bukti foto untuk transfer dan serah terima
        Schema::create('tbl_bukti_foto_pinjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pinjaman')->nullable()->constrained('tbl_pinjaman_h')->onDelete('cascade');
            $table->foreignId('id_pengajuan')->nullable()->constrained('tbl_pengajuan_pinjaman')->onDelete('cascade');
            $table->string('file_photo');
            $table->enum('jenis', ['bukti_transfer', 'serah_terima']);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_bukti_foto_pinjaman');
        Schema::dropIfExists('tbl_janji_temu_pinjaman');
    }
};
