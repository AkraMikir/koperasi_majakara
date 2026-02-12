<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration//ikkkkm
{
    public function up(): void
    {
        // 1. Jenis Fitur / Via / Transaksi
        Schema::create('jns_fitur', function (Blueprint $table) {
            $table->id();
            $table->char('kode', 1)->unique();
            $table->string('nama', 50);
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('jns_via', function (Blueprint $table) {
            $table->id();
            $table->char('kode', 2)->unique(); // Diganti dari 1 ke 2 (TF, TN)
            $table->string('nama', 50);
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('jns_transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 5)->unique();
            $table->string('nama', 50);
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Lokasi
        Schema::create('jns_lokasi_perusahaan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lokasi', 150);
            $table->text('alamat_lengkap');
            $table->string('kota', 100);
            $table->string('provinsi', 100);
            $table->string('tipe_lokasi', 50);
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });

        // 3. Pinjaman Masters
        Schema::create('jns_angsuran_bulan', function (Blueprint $table) {
            $table->id();
            $table->char('ket', 1)->unique();
            $table->enum('aktif', ['y', 'n'])->default('y');
            $table->timestamps();
        });

        Schema::create('jns_angsuran_minggu', function (Blueprint $table) {
            $table->id();
            $table->char('ket', 1)->unique();
            $table->enum('aktif', ['y', 'n'])->default('y');
            $table->timestamps();
        });

        Schema::create('master_bunga_pinjaman', function (Blueprint $table) {
            $table->id();
            $table->integer('durasi_min');
            $table->integer('durasi_max');
            $table->decimal('bunga_persen', 5, 2);
            $table->string('keterangan')->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });

        Schema::create('master_denda_pinjaman', function (Blueprint $table) {
            $table->id();
            $table->decimal('denda_persen', 5, 2);
            $table->boolean('status_aktif')->default(true);
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });

        // 4. Gadai Masters
        Schema::create('m_barang_gadai', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang', 100);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        Schema::create('tbl_gadai_spesial', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 32);
            $table->enum('tmpl_250_ribu', ['y', 'n'])->default('n');
            $table->enum('tmpl_500_ribu', ['y', 'n'])->default('n');
            $table->enum('tmpl_1_juta', ['y', 'n'])->default('n');
            $table->enum('tmpl_2_juta', ['y', 'n'])->default('n');
            $table->enum('tmpl_3_juta', ['y', 'n'])->default('n');
            $table->enum('tmpl_4_juta', ['y', 'n'])->default('n');
            $table->enum('tmpl_lebih_dari_5_juta', ['y', 'n'])->default('n');
            $table->timestamps();
        });

        // 5. Deposito Masters
        Schema::create('jns_tenor_deposito', function (Blueprint $table) {
            $table->id();
            $table->integer('tenor_hari');
            $table->integer('tenor_bulan')->nullable();
            $table->enum('aktif', ['y', 'n'])->default('y');
            $table->timestamps();
        });

        Schema::create('suku_bunga_deposito', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenor_id')->constrained('jns_tenor_deposito');
            $table->decimal('min_nominal', 15, 2)->nullable();
            $table->decimal('max_nominal', 15, 2)->nullable();
            $table->decimal('bunga', 5, 4);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suku_bunga_deposito');
        Schema::dropIfExists('jns_tenor_deposito');
        Schema::dropIfExists('tbl_gadai_spesial');
        Schema::dropIfExists('m_barang_gadai');
        Schema::dropIfExists('master_denda_pinjaman');
        Schema::dropIfExists('master_bunga_pinjaman');
        Schema::dropIfExists('jns_angsuran_minggu');
        Schema::dropIfExists('jns_angsuran_bulan');
        Schema::dropIfExists('jns_lokasi_perusahaan');
        Schema::dropIfExists('jns_transaksi');
        Schema::dropIfExists('jns_via');
        Schema::dropIfExists('jns_fitur');
    }
};
