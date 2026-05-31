<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_pengajuan_pinjaman', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->foreignId('id_anggota')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->date('tgl_pengajuan');
            $table->decimal('nominal', 15, 2);
            $table->string('jenis', 20)->nullable()->default('bulanan');
            $table->integer('durasi');
            $table->string('jenis_pencairan', 20)->nullable()->default('transfer');
            $table->char('status', 1)->default('1');
            $table->text('keterangan')->nullable();
            $table->text('keterangan_admin')->nullable();
            $table->date('tgl_cair')->nullable();
            $table->decimal('bunga_persen', 5, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('tbl_pinjaman_h', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->foreignId('id_anggota')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->string('id_pengajuan', 30)->nullable();
            $table->decimal('jumlah_pinjam', 15, 2);
            $table->integer('lama_pinjam');
            $table->string('jenis', 20)->nullable()->default('bulanan');
            $table->decimal('bunga', 5, 2);
            $table->decimal('bunga_rp', 15, 2);
            $table->decimal('denda_persen', 5, 2)->nullable()->default(0.30);
            $table->decimal('ags_bulan', 15, 2);
            $table->decimal('ags_minggu', 15, 2)->nullable();
            $table->date('tgl_pinjam');
            $table->decimal('saldo_lebih', 15, 2)->nullable()->default(0);
            $table->enum('lunas', ['belum', 'lunas'])->nullable()->default('belum');
            $table->boolean('is_petty_cash')->default(false);
            $table->string('petty_cash_ref', 255)->nullable();
            $table->string('metode_pencairan', 255)->nullable();
            $table->string('bank_pengirim', 255)->nullable();
            $table->decimal('biaya_transfer', 15, 2)->default(0.00);
            $table->timestamps();
        });

        Schema::create('tempo_pinjaman_b', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->string('pinjaman_id', 30)->index();
            $table->integer('no_urut');
            $table->date('tgl_jatuh_tempo');
            $table->decimal('jumlah_tagihan', 15, 2);
            $table->decimal('jumlah_terbayar', 15, 2)->nullable()->default(0);
            $table->decimal('denda', 15, 2)->nullable()->default(0);
            $table->timestamp('tgl_bayar')->nullable();
            $table->enum('status_bayar', ['belum', 'lunas', 'telat'])->nullable()->default('belum');
            $table->timestamps();

            $table->foreign('pinjaman_id')->references('id')->on('tbl_pinjaman_h')->onDelete('cascade');
        });

        Schema::create('tempo_pinjaman_m', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->string('pinjaman_id', 30)->index();
            $table->integer('no_urut');
            $table->date('tgl_jatuh_tempo');
            $table->decimal('jumlah_tagihan', 15, 2);
            $table->decimal('jumlah_terbayar', 15, 2)->nullable()->default(0);
            $table->decimal('denda', 15, 2)->nullable()->default(0);
            $table->timestamp('tgl_bayar')->nullable();
            $table->enum('status_bayar', ['belum', 'lunas', 'telat'])->nullable()->default('belum');
            $table->timestamps();

            $table->foreign('pinjaman_id')->references('id')->on('tbl_pinjaman_h')->onDelete('cascade');
        });

        Schema::create('tbl_pengajuan_pembayaran_pinjaman', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->foreignId('id_anggota')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->string('pinjaman_id', 30);
            $table->string('tempo_id', 30);
            $table->string('jenis_tempo', 20)->nullable()->default('bulanan');
            $table->decimal('nominal', 15, 2);
            $table->string('metode_pembayaran', 20)->nullable()->default('transfer');
            $table->string('rekening_tujuan', 50)->nullable();
            $table->text('keterangan')->nullable();
            $table->text('keterangan_admin')->nullable();
            $table->char('status', 1)->default('1');
            $table->string('setoran_kantor_id', 255)->nullable();
            $table->timestamp('tgl_pembayaran')->nullable();
            $table->timestamps();

            $table->foreign('pinjaman_id')->references('id')->on('tbl_pinjaman_h')->onDelete('cascade');
            $table->foreign('tempo_id')->references('id')->on('tempo_pinjaman_b')->onDelete('cascade');
        });

        Schema::create('tbl_janji_temu_pinjaman', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->string('id_pengajuan', 30)->nullable();
            $table->foreignId('id_nasabah')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->foreignId('lokasi_temu')->constrained('jns_lokasi_perusahaan')->onDelete('cascade');
            $table->decimal('nominal', 15, 2);
            $table->dateTime('tanggal_janji_temu');
            $table->time('waktu_janji_temu');
            $table->text('keterangan')->nullable();
            $table->text('keterangan_admin')->nullable();
            $table->enum('status', ['1', '2', '3'])->default('1');
            $table->timestamps();

            $table->foreign('id_pengajuan')->references('id')->on('tbl_pengajuan_pinjaman')->onDelete('set null');
        });

        Schema::create('tbl_janji_temu_pembayaran_pinjaman', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->string('id_pengajuan', 30);
            $table->foreignId('lokasi_temu')->constrained('jns_lokasi_perusahaan')->onDelete('cascade');
            $table->decimal('nominal', 15, 2);
            $table->dateTime('tanggal_janji_temu');
            $table->time('waktu_janji_temu');
            $table->text('keterangan')->nullable();
            $table->text('keterangan_admin')->nullable();
            $table->enum('status', ['1', '2', '3'])->default('1');
            $table->timestamps();

            $table->foreign('id_pengajuan')->references('id')->on('tbl_pengajuan_pembayaran_pinjaman')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_janji_temu_pembayaran_pinjaman');
        Schema::dropIfExists('tbl_janji_temu_pinjaman');
        Schema::dropIfExists('tbl_pengajuan_pembayaran_pinjaman');
        Schema::dropIfExists('tempo_pinjaman_m');
        Schema::dropIfExists('tempo_pinjaman_b');
        Schema::dropIfExists('tbl_pinjaman_h');
        Schema::dropIfExists('tbl_pengajuan_pinjaman');
    }
};
