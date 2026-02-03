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
        // 1. DROP & RECREATE: tbl_pengajuan_pinjaman
        // ==========================================
        Schema::dropIfExists('tbl_pengajuan_pinjaman');
        
        Schema::create('tbl_pengajuan_pinjaman', function (Blueprint $table) {
            $table->string('id', 30)->primary(); // Complex ID: 300120260001PTPNJ
            $table->unsignedBigInteger('id_anggota');
            $table->datetime('tgl_pengajuan');
            $table->decimal('nominal', 15, 2);
            $table->enum('jenis', ['bulanan', 'mingguan'])->default('bulanan');
            $table->integer('durasi'); // 1-24 bulan
            $table->enum('status', ['1', '2', '3', '4'])->default('1');
            $table->text('keterangan')->nullable();
            $table->text('keterangan_admin')->nullable(); // BARU
            $table->datetime('tgl_cair')->nullable();
            $table->decimal('bunga_persen', 5, 2)->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('id_anggota')->references('id')->on('tbl_nasabah')->onDelete('cascade');
            
            // Indexes
            $table->index('id_anggota');
            $table->index('status');
        });

        // Removed: jenis_pencairan (info ada di Complex ID via)

        // ==========================================
        // 2. DROP & RECREATE: tbl_pinjaman_h
        // ==========================================
        Schema::dropIfExists('tbl_pinjaman_h');
        
        Schema::create('tbl_pinjaman_h', function (Blueprint $table) {
            $table->string('id', 30)->primary(); // Complex ID: 30012026000...PTDPNJM
            $table->unsignedBigInteger('id_anggota');
            $table->string('id_pengajuan', 30)->nullable(); // FK to tbl_pengajuan_pinjaman
            $table->decimal('jumlah_pinjam', 15, 2);
            $table->integer('lama_pinjam');
            $table->enum('jenis', ['bulanan', 'mingguan'])->default('bulanan');
            $table->decimal('bunga', 10, 4);
            $table->decimal('bunga_rp', 15, 2);
            $table->decimal('denda_persen', 5, 2);
            $table->datetime('tgl_pinjam');
            $table->enum('lunas', ['belum', 'lunas'])->default('belum');
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('id_anggota')->references('id')->on('tbl_nasabah')->onDelete('cascade');
            
            // Indexes
            $table->index('id_anggota');
            $table->index('id_pengajuan');
            $table->index('lunas');
        });

        // Removed: ags_bulan (not used)
        // Removed: ags_minggu (not used)
        // Removed: saldo_lebih (reserved for future overpayment feature)
        // Removed: foto_bukti_transfer (moved to tbl_bukti_foto)
        // Removed: foto_serah_terima (moved to tbl_bukti_foto)
        // Removed: status (always 'telaksana')

        // ==========================================
        // 3. DROP & RECREATE: tempo_pinjaman_b
        // ==========================================
        Schema::dropIfExists('tempo_pinjaman_b');
        
        Schema::create('tempo_pinjaman_b', function (Blueprint $table) {
            $table->string('id', 30)->primary(); // Complex ID: 30012026000...PTTPNJM
            $table->string('pinjaman_id', 30); // FK to tbl_pinjaman_h
            $table->integer('no_urut'); // Angsuran ke-berapa
            $table->datetime('tgl_jatuh_tempo');
            $table->decimal('jumlah_tagihan', 15, 2);
            $table->decimal('jumlah_terbayar', 15, 2)->default(0);
            $table->decimal('denda', 15, 2)->default(0);
            $table->datetime('tgl_bayar')->nullable();
            $table->enum('status_bayar', ['belum', 'lunas', 'telat'])->default('belum');
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('pinjaman_id')->references('id')->on('tbl_pinjaman_h')->onDelete('cascade');
            
            // Indexes
            $table->index('pinjaman_id');
            $table->index('status_bayar');
            $table->index('tgl_jatuh_tempo');
        });

        // Removed: anggota_id (can be retrieved from pinjaman_id)

        // ==========================================
        // 4. DROP & RECREATE: tbl_pengajuan_pembayaran_pinjaman
        // ==========================================
        Schema::dropIfExists('tbl_pengajuan_pembayaran_pinjaman');
        
        Schema::create('tbl_pengajuan_pembayaran_pinjaman', function (Blueprint $table) {
            $table->string('id', 30)->primary(); // Complex ID: 300120260001PTPMB
            $table->unsignedBigInteger('id_anggota');
            $table->string('pinjaman_id', 30); // FK to tbl_pinjaman_h
            $table->string('tempo_id', 30); // FK to tempo_pinjaman_b - FIXED
            $table->enum('jenis_tempo', ['bulanan', 'mingguan'])->default('bulanan');
            $table->decimal('nominal', 15, 2);
            $table->string('rekening_tujuan', 255)->nullable();
            $table->text('keterangan')->nullable();
            $table->text('keterangan_admin')->nullable(); // BARU
            $table->enum('status', ['1', '2', '3'])->default('1');
            $table->datetime('tgl_pembayaran')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('id_anggota')->references('id')->on('tbl_nasabah')->onDelete('cascade');
            $table->foreign('pinjaman_id')->references('id')->on('tbl_pinjaman_h')->onDelete('cascade');
            $table->foreign('tempo_id')->references('id')->on('tempo_pinjaman_b')->onDelete('cascade'); // FIXED
            
            // Indexes
            $table->index('id_anggota');
            $table->index('pinjaman_id');
            $table->index('tempo_id');
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
        Schema::dropIfExists('tbl_pengajuan_pembayaran_pinjaman');
        Schema::dropIfExists('tempo_pinjaman_b');
        Schema::dropIfExists('tbl_pinjaman_h');
        Schema::dropIfExists('tbl_pengajuan_pinjaman');
    }
};
