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
        Schema::create('tbl_pengajuan_tabungan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_anggota')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->string('foto_bukti_tf');
            $table->text('keterangan')->nullable();
            $table->enum('status', ['1', '2', '3'])->default('1');
            $table->timestamps();
        });

        Schema::create('tbl_pengajuan_penarikan_tabungan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_anggota')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->dateTime('tgl_pengajuan');
            $table->decimal('nominal', 15, 2);
            $table->text('keterangan')->nullable();
            $table->enum('status', ['1', '2', '3'])->default('1');
            $table->timestamps();
        });

        Schema::create('tbl_bukti_foto_tabungan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengajuan')->constrained('tbl_pengajuan_tabungan')->onDelete('cascade');
            $table->string('file_photo');
            $table->enum('jenis', ['tabungan', 'penarikan']);
            $table->decimal('nominal', 15, 2);
            $table->string('keterangan');
            $table->timestamps();
        });

        Schema::create('trans_tabungan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengajuan_setor')->nullable()->constrained('tbl_pengajuan_tabungan')->onDelete('set null');
            $table->foreignId('id_pengajuan_tarik')->nullable()->constrained('tbl_pengajuan_penarikan_tabungan')->onDelete('set null');
            $table->foreignId('id_anggota')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->decimal('nominal', 15, 2);
            $table->text('keterangan')->nullable();
            $table->enum('jenis', ['setoran', 'penarikan']);
            $table->enum('via', ['transfer', 'cash']);
            $table->timestamp('tgl_transaksi')->useCurrent();
            $table->timestamps();
        });

        Schema::create('tbl_janji_temu_tabungan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengajuan')->constrained('tbl_pengajuan_tabungan')->onDelete('cascade');
            $table->foreignId('lokasi_temu')->constrained('jns_lokasi_perusahaan')->onDelete('cascade');
            $table->decimal('nominal', 15, 2);
            $table->dateTime('tanggal_janji_temu');
            $table->timestamp('waktu_janji_temu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_janji_temu_tabungan');
        Schema::dropIfExists('trans_tabungan');
        Schema::dropIfExists('tbl_bukti_foto_tabungan');
        Schema::dropIfExists('tbl_pengajuan_penarikan_tabungan');
        Schema::dropIfExists('tbl_pengajuan_tabungan');
    }
};



