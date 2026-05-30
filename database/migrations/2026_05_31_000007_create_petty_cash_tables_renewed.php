<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('petty_cash_saldo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('role', ['admin', 'owner']);
            $table->enum('tipe', ['cash', 'transfer'])->default('cash');
            $table->string('sumber', 50)->nullable();
            $table->decimal('mutasi', 15, 2);
            $table->decimal('saldo_akhir', 15, 2);
            $table->string('ref_id', 30)->nullable()->index();
            $table->string('ref_table', 50)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'role']);
        });

        Schema::create('petty_cash_penerimaan', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $table->string('sumber', 255)->nullable();
            $table->decimal('nominal_tf', 15, 2)->default(0.00);
            $table->decimal('nominal_cash', 15, 2)->default(0.00);
            $table->decimal('nominal_total', 15, 2)->storedAs('nominal_tf + nominal_cash')->nullable();
            $table->string('bukti_tf', 255)->nullable();
            $table->string('foto_cash', 255)->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('keterangan')->nullable();
            $table->text('keterangan_admin')->nullable();
            $table->timestamp('tgl_penerimaan')->useCurrent();
            $table->timestamps();

            $table->index(['admin_id', 'status']);
            $table->index(['owner_id', 'status']);
        });

        Schema::create('petty_cash_transaksi_nasabah', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('nasabah_id')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->foreignId('id_jns_transaksi')->nullable()->constrained('jns_transaksi')->onDelete('set null');
            $table->foreignId('id_jns_via')->nullable()->constrained('jns_via')->onDelete('set null');
            $table->foreignId('id_jns_fitur')->nullable()->constrained('jns_fitur')->onDelete('set null');
            $table->decimal('nominal', 15, 2);
            $table->string('bukti_tf', 255)->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');
            $table->text('keterangan')->nullable();
            $table->string('ref_table', 50)->nullable();
            $table->string('ref_id', 30)->nullable();
            $table->string('setoran_kantor_id', 30)->nullable()->index();
            $table->timestamp('tgl_transaksi')->useCurrent();
            $table->timestamps();

            $table->index(['admin_id', 'tgl_transaksi']);
            $table->index(['nasabah_id']);
        });

        Schema::create('petty_cash_setoran_kantor', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('owner_id')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('total_setor', 15, 2);
            $table->decimal('nominal_cash', 15, 2)->default(0.00);
            $table->decimal('nominal_tf', 15, 2)->default(0.00);
            $table->json('data_potongan');
            $table->integer('jumlah_nasabah')->default(0);
            $table->string('foto_setoran', 255)->nullable();
            $table->boolean('sudah_setor_fisik')->default(false);
            $table->enum('status', ['pending', 'approved_owner', 'rejected'])->default('pending');
            $table->text('keterangan_admin')->nullable();
            $table->text('keterangan_owner')->nullable();
            $table->timestamp('tgl_setoran')->useCurrent();
            $table->timestamp('tgl_approval')->nullable();
            $table->timestamps();

            $table->index(['admin_id', 'status']);
            $table->index(['owner_id', 'status']);
        });

        Schema::create('petty_cash_owner_transaksi', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('tipe', ['masuk', 'keluar', 'kirim_admin_hold', 'terima_setoran']);
            $table->string('sumber', 255)->nullable();
            $table->decimal('nominal_cash', 15, 2)->default(0.00);
            $table->decimal('nominal_tf', 15, 2)->default(0.00);
            $table->string('keterangan', 255);
            $table->string('bukti_foto_cash', 255)->nullable();
            $table->string('bukti_foto_tf', 255)->nullable();
            $table->string('ref_id', 255)->nullable();
            $table->string('ref_table', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('petty_cash_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('aksi', 100)->index();
            $table->string('ref_id', 30)->nullable();
            $table->string('ref_table', 50)->nullable();
            $table->decimal('nominal', 15, 2)->nullable();
            $table->text('detail')->nullable();
            $table->string('ip_address', 50)->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('petty_cash_logs');
        Schema::dropIfExists('petty_cash_owner_transaksi');
        Schema::dropIfExists('petty_cash_setoran_kantor');
        Schema::dropIfExists('petty_cash_transaksi_nasabah');
        Schema::dropIfExists('petty_cash_penerimaan');
        Schema::dropIfExists('petty_cash_saldo');
    }
};
