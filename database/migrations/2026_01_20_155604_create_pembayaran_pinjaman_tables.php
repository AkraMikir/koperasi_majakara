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
        // Tabel pengajuan pembayaran pinjaman via transfer
        Schema::create('tbl_pengajuan_pembayaran_pinjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_anggota')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->foreignId('pinjaman_id')->constrained('tbl_pinjaman_h')->onDelete('cascade');
            $table->unsignedBigInteger('tempo_id')->nullable(); // Bisa bulanan atau mingguan (no FK constraint)
            $table->enum('jenis_tempo', ['bulanan', 'mingguan'])->nullable();
            $table->decimal('nominal', 15, 2);
            $table->string('rekening_tujuan')->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['1', '2', '3', '4'])->default('1'); // 1=Pending, 2=Ditolak, 3=Disetujui, 4=Terlaksana
            $table->dateTime('tgl_pembayaran')->nullable();
            $table->timestamps();
        });

        // Tabel janji temu pembayaran pinjaman via cash
        Schema::create('tbl_janji_temu_pembayaran_pinjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengajuan')->constrained('tbl_pengajuan_pembayaran_pinjaman')->onDelete('cascade');
            $table->foreignId('lokasi_temu')->constrained('jns_lokasi_perusahaan')->onDelete('cascade');
            $table->decimal('nominal', 15, 2);
            $table->dateTime('tanggal_janji_temu');
            $table->time('waktu_janji_temu');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // Tabel bukti foto pembayaran pinjaman
        Schema::create('tbl_bukti_foto_pembayaran_pinjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengajuan')->constrained('tbl_pengajuan_pembayaran_pinjaman')->onDelete('cascade');
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
        Schema::dropIfExists('tbl_bukti_foto_pembayaran_pinjaman');
        Schema::dropIfExists('tbl_janji_temu_pembayaran_pinjaman');
        Schema::dropIfExists('tbl_pengajuan_pembayaran_pinjaman');
    }
};
