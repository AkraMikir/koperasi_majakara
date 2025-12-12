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
        Schema::create('tbl_pengajuan_pinjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_anggota')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->dateTime('tgl_pengajuan');
            $table->decimal('nominal', 15, 2);
            $table->enum('jenis', ['bulanan', 'mingguan']);
            $table->char('durasi', 1);
            $table->timestamps();
        });

        Schema::create('tbl_pinjaman_h', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_anggota')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->foreignId('id_pengajuan')->nullable()->constrained('tbl_pengajuan_pinjaman')->onDelete('set null');
            $table->decimal('jumlah_pinjam', 15, 2);
            $table->integer('lama_pinjam');
            $table->enum('jenis', ['bulanan', 'mingguan']);
            $table->decimal('bunga', 5, 4);
            $table->decimal('bunga_rp', 15, 2);
            $table->decimal('denda_persen', 5, 2);
            $table->char('ags_bulan', 1)->nullable();
            $table->char('ags_minggu', 1)->nullable();
            $table->dateTime('tgl_pinjam');
            $table->decimal('saldo_lebih', 15, 2)->default(0);
            $table->enum('status', ['menunggu', 'pencairan', 'telaksana'])->default('menunggu');
            $table->enum('lunas', ['belum', 'lunas'])->default('belum');
            $table->timestamps();
        });

        Schema::create('tempo_pinjaman_b', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pinjaman_id')->constrained('tbl_pinjaman_h')->onDelete('cascade');
            $table->foreignId('anggota_id')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->integer('no_urut');
            $table->dateTime('tgl_jatuh_tempo');
            $table->decimal('jumlah_tagihan', 15, 2);
            $table->decimal('jumlah_terbayar', 15, 2)->default(0);
            $table->enum('status_bayar', ['belum', 'lunas', 'telat'])->default('belum');
            $table->timestamps();
        });

        Schema::create('tempo_pinjaman_m', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pinjaman_id')->constrained('tbl_pinjaman_h')->onDelete('cascade');
            $table->foreignId('anggota_id')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->integer('no_urut');
            $table->dateTime('tgl_jatuh_tempo');
            $table->decimal('jumlah_tagihan', 15, 2);
            $table->decimal('jumlah_terbayar', 15, 2)->default(0);
            $table->enum('status_bayar', ['belum', 'lunas', 'telat'])->default('belum');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tempo_pinjaman_m');
        Schema::dropIfExists('tempo_pinjaman_b');
        Schema::dropIfExists('tbl_pinjaman_h');
        Schema::dropIfExists('tbl_pengajuan_pinjaman');
    }
};



