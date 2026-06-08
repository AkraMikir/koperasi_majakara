<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jns_fitur', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 5)->unique();
            $table->string('nama', 50);
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('jns_via', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 5)->unique();
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

        Schema::create('jns_lokasi_perusahaan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lokasi', 150);
            $table->text('alamat_lengkap');
            $table->text('google_maps_embed')->nullable();
            $table->string('kota', 100);
            $table->string('provinsi', 100);
            $table->string('tipe_lokasi', 50);
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });

        Schema::create('jns_angsuran_bulan', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('bulan')->nullable();
            $table->string('ket', 5)->nullable();
            $table->enum('aktif', ['y', 'n'])->default('y');
            $table->timestamps();
        });

        // Seed 1-24 months
        $now = now();
        $data = [];
        for ($i = 1; $i <= 24; $i++) {
            $data[] = [
                'bulan' => $i,
                'ket' => (string) $i,
                'aktif' => 'y',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        DB::table('jns_angsuran_bulan')->insert($data);

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

        Schema::create('suku_bunga', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_bunga', 255);
            $table->decimal('opsi_val', 5, 4);
            $table->timestamps();
        });

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

        Schema::create('biaya_transfer', function (Blueprint $table) {
            $table->id();
            $table->string('bank_pengirim', 50);
            $table->string('bank_penerima', 50);
            $table->decimal('biaya_admin', 15, 2)->default(0.00);
            $table->decimal('min_saldo_non_bca', 15, 2)->default(0.00);
            $table->string('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('jns_bank', function (Blueprint $table) {
            $table->id();
            $table->string('pemilik', 100);
            $table->string('nama', 100);
            $table->string('no_rek', 30);
            $table->string('cabang', 255)->nullable();
            $table->string('bank', 50);
            $table->string('status', 255)->default('aktif');
            $table->text('logo')->nullable();
            $table->string('kode_bank', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('logo_bank', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bank', 255)->unique();
            $table->text('logo_url');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logo_bank');
        Schema::dropIfExists('jns_bank');
        Schema::dropIfExists('biaya_transfer');
        Schema::dropIfExists('suku_bunga_deposito');
        Schema::dropIfExists('jns_tenor_deposito');
        Schema::dropIfExists('tbl_gadai_spesial');
        Schema::dropIfExists('m_barang_gadai');
        Schema::dropIfExists('suku_bunga');
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
