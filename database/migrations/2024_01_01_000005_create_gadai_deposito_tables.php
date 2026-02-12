<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration//ikkkkm
{
    public function up(): void
    {
        // ----------------- GADAI ----------------- //

        // 1. Item Gadai
        Schema::create('tbl_item_gadai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_nasabah')->constrained('tbl_nasabah');
            $table->foreignId('id_master_barang')->constrained('m_barang_gadai');
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

        // 2. Pengajuan Gadai
        Schema::create('tbl_pengajuan_gadai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_nasabah')->constrained('tbl_nasabah');
            $table->foreignId('id_item_gadai')->constrained('tbl_item_gadai');
            $table->decimal('nominal_diajukan', 15, 2);
            $table->enum('metode', ['datang_langsung', 'pickup']);
            $table->string('foto_bukti_barang')->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status', ['1', '2', '3'])->default('1');
            $table->timestamps();
        });

        // 3. Header Gadai
        Schema::create('tbl_gadai_h', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengajuan')->nullable()->constrained('tbl_pengajuan_gadai');
            $table->foreignId('id_nasabah')->constrained('tbl_nasabah');
            $table->foreignId('id_item_gadai')->constrained('tbl_item_gadai');
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

        // 4. Tempo Gadai
        Schema::create('tempo_gadai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gadai_id')->constrained('tbl_gadai_h');
            $table->foreignId('nasabah_id')->constrained('tbl_nasabah');
            $table->integer('no_urut');
            $table->dateTime('tgl_jatuh_tempo');
            $table->decimal('jumlah_tagihan', 15, 2);
            $table->decimal('jumlah_terbayar', 15, 2)->default(0);
            $table->enum('status_bayar', ['belum', 'lunas', 'telat'])->default('belum');
            $table->timestamps();
        });

        // 5. Trans Gadai
        Schema::create('trans_gadai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gadai_id')->constrained('tbl_gadai_h');
            $table->foreignId('nasabah_id')->constrained('tbl_nasabah');
            $table->enum('jenis', ['bunga', 'pelunasan', 'pelunasan_akhir', 'denda', 'lelang']);
            $table->decimal('nominal', 15, 2);
            $table->text('keterangan')->nullable();
            $table->timestamp('tgl_transaksi')->useCurrent();
            $table->timestamps();
        });

        // 6. Janji Temu Gadai
        Schema::create('tbl_janji_temu_gadai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gadai_id')->constrained('tbl_gadai_h');
            $table->foreignId('id_nasabah')->nullable()->constrained('tbl_nasabah');
            $table->foreignId('lokasi_temu')->constrained('jns_lokasi_perusahaan');
            $table->dateTime('tanggal_janji_temu');
            $table->timestamp('waktu_janji_temu');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // 7. Lelang Gadai
        Schema::create('tbl_lelang_gadai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gadai_id')->constrained('tbl_gadai_h');
            $table->foreignId('id_item_gadai')->constrained('tbl_item_gadai');
            $table->decimal('harga_laku', 15, 2);
            $table->decimal('selisih_ke_nasabah', 15, 2);
            $table->text('catatan')->nullable();
            $table->enum('status', ['pending', 'terjual', 'ditutup'])->default('pending');
            $table->timestamps();
        });

        // ----------------- DEPOSITO ----------------- //

        // 8. Pengajuan Deposito
        Schema::create('tbl_pengajuan_deposito', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_nasabah')->constrained('tbl_nasabah');
            $table->decimal('nominal', 15, 2);
            $table->foreignId('tenor_id')->constrained('jns_tenor_deposito');
            $table->unsignedBigInteger('jenis_deposito'); // No FK (Missing Table)
            $table->enum('metode_setor', ['transfer', 'saldo_tabungan']);
            $table->string('foto_bukti_tf')->nullable();
            $table->enum('status', ['1', '2', '3'])->default('1');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // 9. Deposito Header
        Schema::create('tbl_deposito_h', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengajuan')->nullable()->constrained('tbl_pengajuan_deposito');
            $table->foreignId('id_nasabah')->constrained('tbl_nasabah');
            $table->char('nomor_deposito', 16)->unique();
            $table->decimal('nominal_awal', 15, 2);
            $table->foreignId('tenor_id')->constrained('jns_tenor_deposito');
            $table->decimal('bunga', 5, 4);
            $table->dateTime('tgl_mulai');
            $table->dateTime('tgl_jatuh_tempo');
            $table->enum('metode_pencairan', ['pencairan_ke_rekening']);
            $table->enum('status', ['aktif', 'dicairkan', 'ditutup', 'gagal'])->default('aktif');
            $table->timestamps();
        });

        // 10. Deposito Bunga Harian
        Schema::create('deposito_bunga_harian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deposito_id')->constrained('tbl_deposito_h');
            $table->date('tanggal');
            $table->decimal('bunga_harian', 15, 2);
            $table->decimal('saldo_akhir', 15, 2);
            $table->timestamps();
        });

        // 11. Pencairan Deposito
        Schema::create('tbl_pencairan_deposito', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deposito_id')->constrained('tbl_deposito_h');
            $table->foreignId('id_nasabah')->constrained('tbl_nasabah');
            $table->decimal('nominal_akhir', 15, 2);
            $table->enum('metode_pencairan', ['rek_nasabah', 'saldo_tabungan']);
            $table->enum('status', ['pending', 'diproses', 'selesai', 'ditolak'])->default('pending');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // 12. Trans Deposito
        Schema::create('trans_deposito', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deposito_id')->constrained('tbl_deposito_h');
            $table->enum('jenis', ['setor_awal', 'bunga', 'pencairan']);
            $table->decimal('nominal', 15, 2);
            $table->text('keterangan')->nullable();
            $table->timestamp('tgl_transaksi')->useCurrent();
            $table->timestamps();
        });

        // 13. Janji Temu Deposito
        Schema::create('tbl_janji_temu_deposito', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deposito_id')->constrained('tbl_deposito_h');
            $table->foreignId('id_nasabah')->nullable()->constrained('tbl_nasabah');
            $table->foreignId('lokasi_temu')->constrained('jns_lokasi_perusahaan');
            $table->dateTime('tanggal_janji_temu');
            $table->timestamp('waktu_janji_temu');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_janji_temu_deposito');
        Schema::dropIfExists('trans_deposito');
        Schema::dropIfExists('tbl_pencairan_deposito');
        Schema::dropIfExists('deposito_bunga_harian');
        Schema::dropIfExists('tbl_deposito_h');
        Schema::dropIfExists('tbl_pengajuan_deposito');
        Schema::dropIfExists('tbl_lelang_gadai');
        Schema::dropIfExists('tbl_janji_temu_gadai');
        Schema::dropIfExists('trans_gadai');
        Schema::dropIfExists('tempo_gadai');
        Schema::dropIfExists('tbl_gadai_h');
        Schema::dropIfExists('tbl_pengajuan_gadai');
        Schema::dropIfExists('tbl_item_gadai');
    }
};
