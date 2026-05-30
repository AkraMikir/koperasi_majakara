<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Gadai Master Kategori
        Schema::create('tbl_gadai_master_kategori', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kategori', 255)->unique();
            $table->string('nama_kategori', 255);
            $table->decimal('rate_jasa', 5, 2)->default(0.00);
            $table->decimal('rate_denda', 5, 2)->default(0.00);
            $table->decimal('rate_inap_persen', 5, 2)->default(0.00);
            $table->integer('max_extend_default')->default(3);
            $table->integer('masa_gadai_hari')->default(30);
            $table->integer('masa_tenggang_hari')->default(15);
            $table->timestamps();
        });

        // 2. Gadai Master Item
        Schema::create('tbl_gadai_master_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('tbl_gadai_master_kategori')->onDelete('cascade');
            $table->string('head_1', 255);
            $table->string('head_2', 255)->nullable();
            $table->decimal('nominal_real', 15, 2)->default(0.00);
            $table->decimal('bunga_low', 5, 2)->default(0.00);
            $table->decimal('nominal_low', 15, 2)->default(0.00);
            $table->decimal('bunga_high', 5, 2)->default(0.00);
            $table->decimal('nominal_high', 15, 2)->default(0.00);
            $table->decimal('nominal_inap', 15, 2)->default(0.00);
            $table->string('file_pic', 255)->nullable();
            $table->decimal('max_taksiran', 15, 2)->default(0.00);
            $table->decimal('rate_inap_nominal', 15, 2)->default(0.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Gadai Master Inap Kendaraan
        Schema::create('tbl_gadai_master_inap_kendaraan', function (Blueprint $table) {
            $table->id();
            $table->string('golongan', 10)->unique();
            $table->string('jenis_kendaraan', 255);
            $table->decimal('nominal_inap', 15, 2)->default(0.00);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // 4. Gadai Active
        Schema::create('tbl_gadai_active', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nasabah_id')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->foreignId('kategori_id')->constrained('tbl_gadai_master_kategori');
            $table->foreignId('item_id')->constrained('tbl_gadai_master_item');
            $table->foreignId('lokasi_id')->constrained('jns_lokasi_perusahaan');
            $table->string('slot_kode', 255);
            $table->enum('slot_table', ['electronic', 'vehicle', 'gold']);
            $table->decimal('nominal_deal', 15, 2);
            $table->decimal('biaya_jasa', 15, 2);
            $table->decimal('denda_aktif', 15, 2)->default(0.00);
            $table->decimal('extra_pinjaman_nominal', 15, 2)->default(0.00);
            $table->text('extra_pinjaman_reason')->nullable();
            $table->foreignId('extra_pinjaman_admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->datetime('extra_pinjaman_set_at')->nullable();
            $table->decimal('biaya_inap', 15, 2)->default(0.00);
            $table->datetime('tgl_mulai');
            $table->datetime('tgl_jatuh_tempo');
            $table->datetime('tgl_tenggang');
            $table->integer('jumlah_perpanjangan')->default(0);
            $table->enum('status', ['active', 'grace_period', 'lunas', 'extended', 'auctioned', 'expired_final', 'returned'])->default('active');
            $table->foreignId('admin_id')->constrained('users');
            $table->timestamps();
        });

        // 5. Gadai Pengajuan
        Schema::create('tbl_gadai_pengajuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nasabah_id')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->foreignId('gadai_active_id')->nullable()->constrained('tbl_gadai_active')->onDelete('cascade');
            $table->enum('jenis_pengajuan', ['lunas', 'perpanjang', 'baru'])->default('lunas');
            $table->enum('metode', ['cash', 'transfer'])->default('cash');
            $table->decimal('nominal', 15, 2);
            $table->datetime('tgl_janji_temu')->nullable();
            $table->string('bukti_transfer', 255)->nullable();
            $table->text('keterangan')->nullable();
            $table->text('admin_keterangan')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->datetime('processed_at')->nullable();
            $table->timestamps();
        });

        // 6. Gadai Files
        Schema::create('tbl_gadai_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gadai_active_id')->nullable()->constrained('tbl_gadai_active')->onDelete('cascade');
            $table->foreignId('pengajuan_id')->nullable()->constrained('tbl_gadai_pengajuan')->onDelete('cascade');
            $table->string('path_file', 255);
            $table->enum('tipe_foto', ['barang', 'penyerahan', 'lainnya'])->default('barang');
            $table->timestamps();
        });

        // 7. Grid Tables
        Schema::create('tbl_gadai_grid_electronic', function (Blueprint $table) {
            $table->id();
            $table->string('kode_slot', 255)->unique();
            $table->integer('baris');
            $table->integer('kolom');
            $table->boolean('is_occupied')->default(false);
            $table->foreignId('active_gadai_id')->nullable()->constrained('tbl_gadai_active')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('tbl_gadai_grid_gold', function (Blueprint $table) {
            $table->id();
            $table->string('kode_slot', 255)->unique();
            $table->integer('baris');
            $table->integer('kolom');
            $table->boolean('is_occupied')->default(false);
            $table->foreignId('active_gadai_id')->nullable()->constrained('tbl_gadai_active')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('tbl_gadai_grid_vehicle', function (Blueprint $table) {
            $table->id();
            $table->string('kode_slot', 255)->unique();
            $table->integer('baris');
            $table->integer('kolom');
            $table->boolean('is_occupied')->default(false);
            $table->foreignId('active_gadai_id')->nullable()->constrained('tbl_gadai_active')->onDelete('set null');
            $table->timestamps();
        });

        // 8. History
        Schema::create('tbl_gadai_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gadai_active_id')->constrained('tbl_gadai_active')->onDelete('cascade');
            $table->enum('aksi', ['create', 'extend', 'lunas', 'auction', 'return', 'expired']);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // 9. Payment Log
        Schema::create('tbl_gadai_payment_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gadai_active_id')->constrained('tbl_gadai_active')->onDelete('cascade');
            $table->enum('jenis_pembayaran', ['jasa', 'denda', 'inap', 'tebus', 'perpanjangan', 'lelang']);
            $table->decimal('nominal', 15, 2);
            $table->enum('metode', ['cash', 'transfer']);
            $table->string('petty_cash_ref', 255)->nullable();
            $table->timestamps();
        });

        // 10. Slot Log
        Schema::create('tbl_gadai_slot_log', function (Blueprint $table) {
            $table->id();
            $table->string('slot_kode', 255);
            $table->string('kategori', 255);
            $table->enum('aksi', ['fill', 'empty']);
            $table->foreignId('active_gadai_id')->nullable()->constrained('tbl_gadai_active')->onDelete('set null');
            $table->timestamps();
        });

        // 11. Old Gadai Tables
        Schema::create('tbl_item_gadai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_nasabah')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->foreignId('id_master_barang')->constrained('m_barang_gadai');
            $table->dateTime('tgl_buat');
            $table->string('head_1', 64)->nullable();
            $table->string('head_2', 64)->nullable();
            $table->decimal('nominal_real', 15, 2);
            $table->decimal('bunga_low', 5, 4);
            $table->decimal('nominal_low', 15, 2);
            $table->decimal('bunga_high', 5, 4);
            $table->decimal('nominal_high', 15, 2);
            $table->string('file_pic', 255);
            $table->timestamps();
        });

        Schema::create('tbl_pengajuan_gadai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_nasabah')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->foreignId('id_item_gadai')->constrained('tbl_item_gadai');
            $table->decimal('nominal_diajukan', 15, 2);
            $table->enum('metode', ['datang_langsung', 'pickup']);
            $table->string('foto_bukti_barang', 255)->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status', ['1', '2', '3'])->default('1');
            $table->timestamps();
        });

        Schema::create('tbl_gadai_h', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengajuan')->nullable()->constrained('tbl_pengajuan_gadai');
            $table->foreignId('id_nasabah')->constrained('tbl_nasabah')->onDelete('cascade');
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

        Schema::create('tempo_gadai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gadai_id')->constrained('tbl_gadai_h')->onDelete('cascade');
            $table->foreignId('nasabah_id')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->integer('no_urut');
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

        Schema::create('tbl_janji_temu_gadai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gadai_id')->constrained('tbl_gadai_h')->onDelete('cascade');
            $table->foreignId('id_nasabah')->nullable()->constrained('tbl_nasabah')->onDelete('cascade');
            $table->foreignId('lokasi_temu')->constrained('jns_lokasi_perusahaan');
            $table->dateTime('tanggal_janji_temu');
            $table->timestamp('waktu_janji_temu')->useCurrent();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('tbl_lelang_gadai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gadai_id')->constrained('tbl_gadai_h')->onDelete('cascade');
            $table->foreignId('id_item_gadai')->constrained('tbl_item_gadai');
            $table->decimal('harga_laku', 15, 2);
            $table->decimal('selisih_ke_nasabah', 15, 2);
            $table->text('catatan')->nullable();
            $table->enum('status', ['pending', 'terjual', 'ditutup'])->default('pending');
            $table->timestamps();
        });

        // 12. Deposito Kategori & Paket
        Schema::create('kategori_depositos', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori', 255);
            $table->text('keterangan')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });

        Schema::create('paket_depositos', function (Blueprint $table) {
            $table->id();
            $table->string('nama_paket', 100);
            $table->integer('tenor_bulan');
            $table->decimal('suku_bunga', 5, 2);
            $table->bigInteger('minimal_nominal');
            $table->bigInteger('maksimal_nominal')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->foreignId('kategori_id')->nullable()->constrained('kategori_depositos')->onDelete('set null');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // 13. Deposito Tables
        Schema::create('tbl_pengajuan_deposito', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_nasabah')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->decimal('nominal', 15, 2);
            $table->foreignId('tenor_id')->constrained('jns_tenor_deposito');
            $table->enum('metode_setor', ['transfer', 'saldo_tabungan']);
            $table->string('foto_bukti_tf', 255)->nullable();
            $table->enum('status', ['1', '2', '3'])->default('1');
            $table->text('catatan')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('paket_id')->nullable()->constrained('paket_depositos')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('tbl_deposito_h', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengajuan')->nullable()->constrained('tbl_pengajuan_deposito')->onDelete('set null');
            $table->foreignId('id_nasabah')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->char('nomor_deposito', 16)->unique();
            $table->decimal('nominal_awal', 15, 2);
            $table->foreignId('tenor_id')->constrained('jns_tenor_deposito');
            $table->decimal('bunga', 5, 4);
            $table->dateTime('tgl_mulai');
            $table->dateTime('tgl_jatuh_tempo');
            $table->enum('metode_pencairan', ['pencairan_ke_rekening']);
            $table->enum('status', ['aktif', 'dicairkan', 'ditutup', 'gagal'])->default('aktif');
            $table->enum('status_peringatan', ['tidak_perlu', 'tentatif', 'need_prepare'])->default('tidak_perlu');
            $table->date('tgl_peringatan')->nullable();
            $table->foreignId('paket_id')->nullable()->constrained('paket_depositos')->onDelete('set null');
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
            $table->enum('jenis_pencairan', ['rek_nasabah', 'saldo_tabungan', 'petty_cash_operator'])->default('rek_nasabah');
            $table->decimal('nominal_akhir', 15, 2);
            $table->string('foto_bukti_tf', 255)->nullable();
            $table->enum('metode_pencairan', ['rek_nasabah', 'saldo_tabungan']);
            $table->enum('status', ['pending', 'diproses', 'selesai', 'ditolak'])->default('pending');
            $table->boolean('is_cancel')->default(false);
            $table->text('catatan')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('bank_pengirim', 255)->nullable();
            $table->decimal('biaya_transfer', 15, 2)->default(0.00);
            $table->timestamps();
        });

        Schema::create('deposito_persiapan_cair', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deposito_id')->constrained('tbl_deposito_h')->onDelete('cascade');
            $table->foreignId('nasabah_id')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->decimal('pokok', 15, 2);
            $table->decimal('bunga_kotor', 15, 2);
            $table->decimal('pajak', 15, 2);
            $table->decimal('bunga_bersih', 15, 2);
            $table->decimal('total_dibayar', 15, 2);
            $table->enum('metode_cair', ['saldo_tabungan', 'rek_nasabah', 'petty_cash_operator'])->default('saldo_tabungan');
            $table->enum('status', ['tentatif', 'diproses', 'selesai', 'dibatalkan'])->default('tentatif');
            $table->date('tgl_peringatan');
            $table->date('tgl_target_cair');
            $table->foreignId('pencairan_id')->nullable()->constrained('tbl_pencairan_deposito')->onDelete('set null');
            $table->text('catatan')->nullable();
            $table->timestamps();
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
            $table->foreignId('id_nasabah')->nullable()->constrained('tbl_nasabah')->onDelete('cascade');
            $table->foreignId('lokasi_temu')->constrained('jns_lokasi_perusahaan');
            $table->dateTime('tanggal_janji_temu');
            $table->timestamp('waktu_janji_temu')->useCurrent();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_janji_temu_deposito');
        Schema::dropIfExists('trans_deposito');
        Schema::dropIfExists('deposito_persiapan_cair');
        Schema::dropIfExists('tbl_pencairan_deposito');
        Schema::dropIfExists('deposito_bunga_harian');
        Schema::dropIfExists('tbl_deposito_h');
        Schema::dropIfExists('tbl_pengajuan_deposito');
        Schema::dropIfExists('paket_depositos');
        Schema::dropIfExists('kategori_depositos');

        Schema::dropIfExists('tbl_lelang_gadai');
        Schema::dropIfExists('tbl_janji_temu_gadai');
        Schema::dropIfExists('trans_gadai');
        Schema::dropIfExists('tempo_gadai');
        Schema::dropIfExists('tbl_gadai_h');
        Schema::dropIfExists('tbl_pengajuan_gadai');
        Schema::dropIfExists('tbl_item_gadai');

        Schema::dropIfExists('tbl_gadai_slot_log');
        Schema::dropIfExists('tbl_gadai_payment_log');
        Schema::dropIfExists('tbl_gadai_history');
        Schema::dropIfExists('tbl_gadai_grid_vehicle');
        Schema::dropIfExists('tbl_gadai_grid_gold');
        Schema::dropIfExists('tbl_gadai_grid_electronic');
        Schema::dropIfExists('tbl_gadai_files');
        Schema::dropIfExists('tbl_gadai_pengajuan');
        Schema::dropIfExists('tbl_gadai_active');
        Schema::dropIfExists('tbl_gadai_master_inap_kendaraan');
        Schema::dropIfExists('tbl_gadai_master_item');
        Schema::dropIfExists('tbl_gadai_master_kategori');
    }
};
