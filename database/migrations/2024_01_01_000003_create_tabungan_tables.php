<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Pengajuan
        Schema::create('tbl_pengajuan_tabungan', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->foreignId('id_anggota')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->decimal('nominal', 15, 2);
            $table->text('keterangan')->nullable();
            $table->text('keterangan_admin')->nullable();
            $table->char('status', 1)->default('1');
            $table->timestamps();
        });

        Schema::create('tbl_pengajuan_penarikan_tabungan', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->foreignId('id_anggota')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->dateTime('tgl_pengajuan');
            $table->decimal('nominal', 15, 2);
            $table->string('metode_transfer', 50)->nullable();
            $table->string('no_rekening', 50)->nullable();
            $table->string('nama_bank', 100)->nullable();
            $table->string('foto_bukti_tf_admin')->nullable();
            $table->text('keterangan')->nullable();
            $table->text('keterangan_admin')->nullable();
            $table->enum('status', ['1', '2', '3'])->default('1')->index();
            $table->timestamps();
        });

        // 2. Janji Temu
        Schema::create('tbl_janji_temu_tabungan', function (Blueprint $table) {
            $table->id();
            $table->string('id_pengajuan', 30);
            $table->foreignId('id_nasabah')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->foreignId('lokasi_temu')->constrained('jns_lokasi_perusahaan');
            $table->decimal('nominal', 15, 2);
            $table->dateTime('tanggal_janji_temu');
            $table->timestamp('waktu_janji_temu');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Constraint internal manual (string vs id not supported by constrained() helper sometimes if mismatched types)
            // But here id_pengajuan is string, so we can't use constrained().
        });

        // 3. Transaksi
        Schema::create('trans_tabungan', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->string('id_pengajuan_setor', 30)->nullable();
            $table->string('id_pengajuan_tarik', 30)->nullable();
            $table->foreignId('id_anggota')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->foreignId('id_jns_trans')->nullable()->constrained('jns_transaksi');
            $table->foreignId('id_via')->nullable()->constrained('jns_via');
            $table->decimal('nominal', 15, 2);
            $table->text('keterangan')->nullable();
            $table->timestamp('tgl_transaksi')->useCurrent();
            $table->timestamps();
        });

        // 4. Bukti Foto Universal
        Schema::create('tbl_bukti_foto', function (Blueprint $table) {
            $table->id();
            $table->string('owner_id', 30)->index();
            $table->char('owner_fitur', 1)->index();
            $table->string('owner_trans', 10); // 'pengajuan' etc
            $table->string('file_path');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_bukti_foto');
        Schema::dropIfExists('trans_tabungan');
        Schema::dropIfExists('tbl_janji_temu_tabungan');
        Schema::dropIfExists('tbl_pengajuan_penarikan_tabungan');
        Schema::dropIfExists('tbl_pengajuan_tabungan');
    }
};
