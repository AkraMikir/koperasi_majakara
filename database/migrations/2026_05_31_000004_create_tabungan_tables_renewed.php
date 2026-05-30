<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_pengajuan_tabungan', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->foreignId('id_anggota')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->decimal('nominal', 15, 2);
            $table->enum('metode_bayar', ['transfer_koperasi', 'transfer_admin', 'cash'])->default('transfer_koperasi');
            $table->text('keterangan')->nullable();
            $table->text('keterangan_admin')->nullable();
            $table->char('status', 1)->default('1');
            $table->foreignId('approved_by_user_id')->nullable()->constrained('users')->onDelete('set null');
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
            $table->decimal('biaya_transfer', 15, 2)->nullable();
            $table->string('foto_bukti_tf_admin', 255)->nullable();
            $table->foreignId('lokasi_temu')->nullable()->constrained('jns_lokasi_perusahaan')->onDelete('set null');
            $table->date('tanggal_janji_temu')->nullable();
            $table->time('waktu_janji_temu')->nullable();
            $table->text('keterangan')->nullable();
            $table->text('keterangan_admin')->nullable();
            $table->enum('status', ['1', '2', '3'])->default('1');
            $table->timestamps();
        });

        Schema::create('trans_tabungan', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->string('id_pengajuan_setor', 30)->nullable();
            $table->string('id_pengajuan_tarik', 30)->nullable();
            $table->string('id_janji_temu_tabungan', 30)->nullable();
            $table->string('petty_cash_ref', 30)->nullable()->index();
            $table->tinyInteger('is_petty_cash')->default(0)->index();
            $table->foreignId('admin_pengelola_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('metode_bayar', 30)->nullable();
            $table->foreignId('id_anggota')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->foreignId('id_jns_transaksi')->nullable()->constrained('jns_transaksi')->onDelete('set null');
            $table->foreignId('id_jns_via')->nullable()->constrained('jns_via')->onDelete('set null');
            $table->decimal('nominal', 15, 2);
            $table->text('keterangan')->nullable();
            $table->timestamp('tgl_transaksi')->useCurrent();
            $table->timestamps();
        });

        Schema::create('tbl_janji_temu_tabungan', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->foreignId('id_nasabah')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->foreignId('lokasi_temu')->constrained('jns_lokasi_perusahaan')->onDelete('cascade');
            $table->enum('jenis', ['setoran', 'penarikan'])->default('setoran');
            $table->decimal('nominal', 15, 2);
            $table->enum('metode_bayar', ['transfer_koperasi', 'transfer_admin', 'cash'])->default('cash');
            $table->dateTime('tanggal_janji_temu');
            $table->time('waktu_janji_temu');
            $table->text('keterangan')->nullable();
            $table->text('keterangan_admin')->nullable();
            $table->enum('status', ['1', '2', '3'])->default('1');
            $table->timestamps();
        });

        Schema::create('tbl_bukti_foto', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('owner_id', 50);
            $table->string('owner_fitur', 10);
            $table->string('owner_trans', 20);
            $table->string('file_path', 255);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index(['owner_id', 'owner_fitur', 'owner_trans']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_bukti_foto');
        Schema::dropIfExists('tbl_janji_temu_tabungan');
        Schema::dropIfExists('trans_tabungan');
        Schema::dropIfExists('tbl_pengajuan_penarikan_tabungan');
        Schema::dropIfExists('tbl_pengajuan_tabungan');
    }
};
