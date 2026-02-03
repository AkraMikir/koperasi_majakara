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
        // Tabel janji temu untuk TABUNGAN (setoran tunai)
        Schema::create('tbl_janji_temu_tabungan', function (Blueprint $table) {
            $table->id();
            $table->string('id_pengajuan', 30); // FK to tbl_pengajuan_tabungan (Complex ID)
            $table->unsignedBigInteger('id_nasabah');
            $table->foreignId('lokasi_temu')->constrained('jns_lokasi_perusahaan')->onDelete('cascade');
            $table->decimal('nominal', 15, 2);
            $table->dateTime('tanggal_janji_temu');
            $table->time('waktu_janji_temu');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('id_pengajuan')->references('id')->on('tbl_pengajuan_tabungan')->onDelete('cascade');
            $table->foreign('id_nasabah')->references('id')->on('tbl_nasabah')->onDelete('cascade');
            
            // Indexes
            $table->index('id_pengajuan');
            $table->index('id_nasabah');
        });

        // Tabel janji temu untuk PINJAMAN (pencairan tunai)
        Schema::create('tbl_janji_temu_pinjaman', function (Blueprint $table) {
            $table->id();
            $table->string('id_pengajuan', 30); // FK to tbl_pengajuan_pinjaman (Complex ID)
            $table->unsignedBigInteger('id_nasabah');
            $table->foreignId('lokasi_temu')->constrained('jns_lokasi_perusahaan')->onDelete('cascade');
            $table->decimal('nominal', 15, 2);
            $table->dateTime('tanggal_janji_temu');
            $table->time('waktu_janji_temu');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('id_pengajuan')->references('id')->on('tbl_pengajuan_pinjaman')->onDelete('cascade');
            $table->foreign('id_nasabah')->references('id')->on('tbl_nasabah')->onDelete('cascade');
            
            // Indexes
            $table->index('id_pengajuan');
            $table->index('id_nasabah');
        });

        // Tabel janji temu untuk PEMBAYARAN PINJAMAN (pembayaran tunai)
        Schema::create('tbl_janji_temu_pembayaran_pinjaman', function (Blueprint $table) {
            $table->id();
            $table->string('id_pengajuan', 30); // FK to tbl_pengajuan_pembayaran_pinjaman (Complex ID)
            $table->unsignedBigInteger('id_nasabah');
            $table->foreignId('lokasi_temu')->constrained('jns_lokasi_perusahaan')->onDelete('cascade');
            $table->decimal('nominal', 15, 2);
            $table->dateTime('tanggal_janji_temu');
            $table->time('waktu_janji_temu');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('id_pengajuan')->references('id')->on('tbl_pengajuan_pembayaran_pinjaman')->onDelete('cascade');
            $table->foreign('id_nasabah')->references('id')->on('tbl_nasabah')->onDelete('cascade');
            
            // Indexes
            $table->index('id_pengajuan');
            $table->index('id_nasabah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_janji_temu_pembayaran_pinjaman');
        Schema::dropIfExists('tbl_janji_temu_pinjaman');
        Schema::dropIfExists('tbl_janji_temu_tabungan');
    }
};
