<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Penerimaan Dana dari Owner ke Admin
        Schema::create('petty_cash_penerimaan', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $table->decimal('nominal_tf', 15, 2)->default(0);
            $table->decimal('nominal_cash', 15, 2)->default(0);
            $table->decimal('nominal_total', 15, 2)->storedAs('nominal_tf + nominal_cash');
            $table->string('bukti_tf', 255)->nullable();     // Foto bukti transfer
            $table->string('foto_cash', 255)->nullable();    // Foto kantong uang
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('keterangan')->nullable();
            $table->text('keterangan_admin')->nullable();    // Catatan ACC/reject
            $table->timestamp('tgl_penerimaan')->useCurrent();
            $table->timestamps();

            $table->index(['admin_id', 'status']);
            $table->index(['owner_id', 'status']);
        });

        // 2. Transaksi Nasabah via Admin (Petty Cash)
        Schema::create('petty_cash_transaksi_nasabah', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('nasabah_id')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->foreignId('id_jns_transaksi')->nullable()->constrained('jns_transaksi')->onDelete('set null');
            $table->foreignId('id_jns_via')->nullable()->constrained('jns_via')->onDelete('set null');
            $table->foreignId('id_jns_fitur')->nullable()->constrained('jns_fitur')->onDelete('set null');
            $table->decimal('nominal', 15, 2);
            $table->string('bukti_tf', 255)->nullable();     // Bukti transfer nasabah (jika TF)
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');
            // Cash → langsung approved; TF → pending sampai konfirmasi admin
            $table->text('keterangan')->nullable();
            $table->string('setoran_kantor_id', 30)->nullable(); // FK ke petty_cash_setoran_kantor setelah disetor
            $table->timestamp('tgl_transaksi')->useCurrent();
            $table->timestamps();

            $table->index(['admin_id', 'tgl_transaksi']);
            $table->index(['nasabah_id']);
            $table->index('setoran_kantor_id');
        });

        // 3. Setoran Admin ke Kantor (Owner)
        Schema::create('petty_cash_setoran_kantor', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('owner_id')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('total_setor', 15, 2);
            $table->json('data_potongan');  // Array detail nasabah: [{nasabah_id, nama, nominal, transaksi, via, fitur}]
            $table->integer('jumlah_nasabah')->default(0);
            $table->string('foto_setoran', 255)->nullable();   // Foto penyerahan ke kantor
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

        // 4. Saldo Real-time (Petty Cash per User)
        Schema::create('petty_cash_saldo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('role', ['admin', 'owner']);
            $table->decimal('mutasi', 15, 2);        // + = masuk, - = keluar
            $table->decimal('saldo_akhir', 15, 2);   // Saldo setelah mutasi
            $table->string('ref_id', 30)->nullable(); // ID referensi (penerimaan/setoran/transaksi)
            $table->string('ref_table', 50)->nullable(); // Nama tabel referensi
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'role']);
            $table->index('ref_id');
        });

        // 5. Audit Log Petty Cash
        Schema::create('petty_cash_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('aksi', 100);             // 'terima_penerimaan', 'input_setoran', 'setor_kantor', 'approve_setoran'
            $table->string('ref_id', 30)->nullable();
            $table->string('ref_table', 50)->nullable();
            $table->decimal('nominal', 15, 2)->nullable();
            $table->text('detail')->nullable();      // JSON detail aksi
            $table->string('ip_address', 50)->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index('aksi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('petty_cash_logs');
        Schema::dropIfExists('petty_cash_saldo');
        Schema::dropIfExists('petty_cash_setoran_kantor');
        Schema::dropIfExists('petty_cash_transaksi_nasabah');
        Schema::dropIfExists('petty_cash_penerimaan');
    }
};
