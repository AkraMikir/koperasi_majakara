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
        Schema::create('jns_deposito', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jenis', 100);
            $table->text('deskripsi')->nullable();
            $table->enum('status_aktif', ['y', 'n'])->default('y');
            $table->timestamps();
        });

        Schema::create('jns_tenor_deposito', function (Blueprint $table) {
            $table->id();
            $table->integer('tenor_hari');
            $table->integer('tenor_bulan')->nullable();
            $table->enum('aktif', ['y', 'n'])->default('y');
            $table->timestamps();
        });

        Schema::create('suku_bunga_deposito', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenor_id')->constrained('jns_tenor_deposito')->onDelete('cascade');
            $table->decimal('min_nominal', 15, 2)->nullable();
            $table->decimal('max_nominal', 15, 2)->nullable();
            $table->decimal('bunga', 5, 4);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });

        Schema::create('tbl_pengajuan_deposito', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_nasabah')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->decimal('nominal', 15, 2);
            $table->foreignId('tenor_id')->constrained('jns_tenor_deposito')->onDelete('cascade');
            $table->foreignId('jenis_deposito')->constrained('jns_deposito')->onDelete('cascade');
            $table->enum('metode_setor', ['transfer', 'saldo_tabungan']);
            $table->string('foto_bukti_tf')->nullable();
            $table->enum('status', ['1', '2', '3'])->default('1');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        Schema::create('tbl_deposito_h', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengajuan')->nullable()->constrained('tbl_pengajuan_deposito')->onDelete('set null');
            $table->foreignId('id_nasabah')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->char('nomor_deposito', 16)->unique();
            $table->decimal('nominal_awal', 15, 2);
            $table->foreignId('tenor_id')->constrained('jns_tenor_deposito')->onDelete('cascade');
            $table->decimal('bunga', 5, 4);
            $table->dateTime('tgl_mulai');
            $table->dateTime('tgl_jatuh_tempo');
            $table->enum('metode_pencairan', ['pencairan_ke_rekening']);
            $table->enum('status', ['aktif', 'dicairkan', 'ditutup', 'gagal'])->default('aktif');
            $table->timestamps();
        });

        Schema::create('deposito_bunga_harian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deposito_id')->constrained('tbl_deposito_h')->onDelete('cascade');
            $table->date('tanggal');
            $table->decimal('bunga_harian', 15, 2);
            $table->decimal('saldo_akhir', 15, 2);
            $table->timestamps();
        });

        Schema::create('tbl_pencairan_deposito', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deposito_id')->constrained('tbl_deposito_h')->onDelete('cascade');
            $table->foreignId('id_nasabah')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->decimal('nominal_akhir', 15, 2);
            $table->enum('metode_pencairan', ['rek_nasabah', 'saldo_tabungan']);
            $table->enum('status', ['pending', 'diproses', 'selesai', 'ditolak'])->default('pending');
            $table->text('catatan')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('trans_deposito', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deposito_id')->constrained('tbl_deposito_h')->onDelete('cascade');
            $table->enum('jenis', ['setor_awal', 'bunga', 'pencairan']);
            $table->decimal('nominal', 15, 2);
            $table->text('keterangan')->nullable();
            $table->timestamp('tgl_transaksi')->useCurrent();
            $table->timestamps();
        });

        Schema::create('tbl_janji_temu_deposito', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deposito_id')->constrained('tbl_deposito_h')->onDelete('cascade');
            $table->foreignId('lokasi_temu')->constrained('jns_lokasi_perusahaan')->onDelete('cascade');
            $table->dateTime('tanggal_janji_temu');
            $table->timestamp('waktu_janji_temu');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_janji_temu_deposito');
        Schema::dropIfExists('trans_deposito');
        Schema::dropIfExists('tbl_pencairan_deposito');
        Schema::dropIfExists('deposito_bunga_harian');
        Schema::dropIfExists('tbl_deposito_h');
        Schema::dropIfExists('tbl_pengajuan_deposito');
        Schema::dropIfExists('suku_bunga_deposito');
        Schema::dropIfExists('jns_tenor_deposito');
        Schema::dropIfExists('jns_deposito');
    }
};



