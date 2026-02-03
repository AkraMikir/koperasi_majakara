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
        // IMPORTANT: This migration drops and recreates tables
        // Only safe for development environment with no production data
        
        // ==========================================
        // 1. DROP & RECREATE: tbl_pengajuan_tabungan
        // ==========================================
        Schema::dropIfExists('tbl_pengajuan_tabungan');
        
        Schema::create('tbl_pengajuan_tabungan', function (Blueprint $table) {
            $table->string('id', 30)->primary(); // Complex ID: 300120260001TTSTR
            $table->unsignedBigInteger('id_anggota');
            $table->decimal('nominal', 15, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->text('keterangan_admin')->nullable(); // BARU
            $table->enum('status', ['1', '2', '3'])->default('1');
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('id_anggota')->references('id')->on('tbl_nasabah')->onDelete('cascade');
            
            // Indexes
            $table->index('id_anggota');
            $table->index('status');
        });

        // Remove foto_bukti_tf column - replaced by tbl_bukti_foto
        
        // ==========================================
        // 2. DROP & RECREATE: trans_tabungan
        // ==========================================
        Schema::dropIfExists('trans_tabungan');
        
        Schema::create('trans_tabungan', function (Blueprint $table) {
            $table->string('id', 30)->primary(); // Complex ID: 30012026000...TTTRKT
            $table->string('id_pengajuan_setor', 30)->nullable();
            $table->string('id_pengajuan_tarik', 30)->nullable();
            $table->unsignedBigInteger('id_anggota');
            $table->unsignedBigInteger('id_jns_fitur')->nullable(); // BARU - FK ke jns_fitur
            $table->unsignedBigInteger('id_jns_via')->nullable(); // BARU - FK ke jns_via
            $table->unsignedBigInteger('id_jns_transaksi')->nullable(); // BARU - FK ke jns_transaksi
            $table->decimal('nominal', 15, 2);
            $table->text('keterangan')->nullable();
            $table->timestamp('tgl_transaksi')->useCurrent();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('id_anggota')->references('id')->on('tbl_nasabah')->onDelete('cascade');
            $table->foreign('id_jns_fitur')->references('id')->on('jns_fitur')->onDelete('set null');
            $table->foreign('id_jns_via')->references('id')->on('jns_via')->onDelete('set null');
            $table->foreign('id_jns_transaksi')->references('id')->on('jns_transaksi')->onDelete('set null');
            
            // Indexes
            $table->index('id_anggota');
            $table->index('id_pengajuan_setor');
            $table->index('id_pengajuan_tarik');
        });

        // Removed: id_transaksi (merged into id)
        // Removed: id_jns_akun (replaced with id_jns_fitur)
        // Removed: jenis (redundant with id_jns_transaksi)
        // Removed: via (redundant with id_jns_via)

        // ==========================================
        // 3. DROP & RECREATE: tbl_pengajuan_penarikan_tabungan
        // ==========================================
        Schema::dropIfExists('tbl_pengajuan_penarikan_tabungan');
        
        Schema::create('tbl_pengajuan_penarikan_tabungan', function (Blueprint $table) {
            $table->string('id', 30)->primary(); // Complex ID: 300120260001TTPNR
            $table->unsignedBigInteger('id_anggota');
            $table->datetime('tgl_pengajuan');
            $table->decimal('nominal', 15, 2);
            $table->string('metode_transfer', 50)->nullable();
            $table->string('no_rekening', 50)->nullable();
            $table->string('nama_bank', 100)->nullable();
            $table->string('foto_bukti_tf_admin', 255)->nullable();
            $table->text('keterangan')->nullable();
            $table->text('keterangan_admin')->nullable(); // BARU
            $table->enum('status', ['1', '2', '3'])->default('1');
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('id_anggota')->references('id')->on('tbl_nasabah')->onDelete('cascade');
            
            // Indexes
            $table->index('id_anggota');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: This is a destructive migration
        // Reverting would require recreating old structure
        Schema::dropIfExists('tbl_pengajuan_penarikan_tabungan');
        Schema::dropIfExists('trans_tabungan');
        Schema::dropIfExists('tbl_pengajuan_tabungan');
    }
};
