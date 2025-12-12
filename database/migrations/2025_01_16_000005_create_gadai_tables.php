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
        Schema::create('m_barang_gadai', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang', 100);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        Schema::create('tbl_item_gadai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_nasabah')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->foreignId('id_master_barang')->constrained('m_barang_gadai')->onDelete('cascade');
            $table->dateTime('tgl_buat');
            $table->string('head_1', 64)->nullable();
            $table->string('head_2', 64)->nullable();
            $table->decimal('nominal_real', 15, 2);
            $table->decimal('bunga_low', 5, 4);
            $table->decimal('nominal_low', 15, 2);
            $table->decimal('bunga_high', 5, 4);
            $table->decimal('nominal_high', 15, 2);
            $table->string('file_pic');
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

        Schema::create('tbl_pengajuan_gadai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_nasabah')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->foreignId('id_item_gadai')->constrained('tbl_item_gadai')->onDelete('cascade');
            $table->decimal('nominal_diajukan', 15, 2);
            $table->enum('metode', ['datang_langsung', 'pickup']);
            $table->string('foto_bukti_barang')->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status', ['1', '2', '3'])->default('1');
            $table->timestamps();
        });

        Schema::create('tbl_gadai_h', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengajuan')->nullable()->constrained('tbl_pengajuan_gadai')->onDelete('set null');
            $table->foreignId('id_nasabah')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->foreignId('id_item_gadai')->constrained('tbl_item_gadai')->onDelete('cascade');
            $table->char('nomor_gadai', 16)->unique();
            $table->decimal('jumlah_pinjaman', 15, 2);
            $table->decimal('bunga', 5, 4);
            $table->decimal('bunga_rp', 15, 2);
            $table->dateTime('tgl_mulai');
            $table->dateTime('tgl_jatuh_tempo');
            $table->enum('status', ['aktif', 'dilelang', 'lunas', 'gagal'])->default('aktif');
            $table->enum('metode_pencairan', ['transfer', 'cash']);
            $table->timestamps();
        });

        Schema::create('tempo_gadai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gadai_id')->constrained('tbl_gadai_h')->onDelete('cascade');
            $table->foreignId('nasabah_id')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->dateTime('tgl_jatuh_tempo');
            $table->decimal('jumlah_tagihan', 15, 2);
            $table->decimal('jumlah_terbayar', 15, 2)->default(0);
            $table->enum('status_bayar', ['belum', 'lunas', 'telat'])->default('belum');
            $table->timestamps();
        });

        Schema::create('trans_gadai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gadai_id')->constrained('tbl_gadai_h')->onDelete('cascade');
            $table->foreignId('nasabah_id')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->enum('jenis', ['bunga', 'pelunasan', 'pelunasan_akhir', 'denda', 'lelang']);
            $table->decimal('nominal', 15, 2);
            $table->text('keterangan')->nullable();
            $table->timestamp('tgl_transaksi')->useCurrent();
            $table->timestamps();
        });

        Schema::create('tbl_lelang_gadai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gadai_id')->constrained('tbl_gadai_h')->onDelete('cascade');
            $table->foreignId('id_item_gadai')->constrained('tbl_item_gadai')->onDelete('cascade');
            $table->decimal('harga_laku', 15, 2);
            $table->decimal('selisih_ke_nasabah', 15, 2);
            $table->text('catatan')->nullable();
            $table->enum('status', ['pending', 'terjual', 'ditutup'])->default('pending');
            $table->timestamps();
        });

        Schema::create('tbl_janji_temu_gadai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gadai_id')->constrained('tbl_gadai_h')->onDelete('cascade');
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
        Schema::dropIfExists('tbl_janji_temu_gadai');
        Schema::dropIfExists('tbl_lelang_gadai');
        Schema::dropIfExists('trans_gadai');
        Schema::dropIfExists('tempo_gadai');
        Schema::dropIfExists('tbl_gadai_h');
        Schema::dropIfExists('tbl_pengajuan_gadai');
        Schema::dropIfExists('tbl_gadai_spesial');
        Schema::dropIfExists('tbl_item_gadai');
        Schema::dropIfExists('m_barang_gadai');
    }
};



