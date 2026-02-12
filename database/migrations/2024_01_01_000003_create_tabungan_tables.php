<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration//ikkkkm
{
    public function up(): void
    {
        // 1. Pengajuan Setoran Tabungan
        Schema::create('tbl_pengajuan_tabungan', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->foreignId('id_anggota')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->decimal('nominal', 15, 2);
            $table->text('keterangan')->nullable();
            $table->text('keterangan_admin')->nullable();
            $table->char('status', 1)->default('1'); // 1=Pending, 2=Disetujui, 3=Ditolak
            $table->timestamps();
        });

        // 2. Pengajuan Penarikan Tabungan
        Schema::create('tbl_pengajuan_penarikan_tabungan', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->foreignId('id_anggota')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->datetime('tgl_pengajuan');
            $table->decimal('nominal', 15, 2);
            $table->string('metode_transfer', 50)->nullable();
            $table->string('no_rekening', 50)->nullable();
            $table->string('nama_bank', 100)->nullable();
            $table->string('foto_bukti_tf_admin', 255)->nullable();
            $table->foreignId('lokasi_temu')->nullable()->constrained('jns_lokasi_perusahaan')->onDelete('set null'); // Added
            $table->date('tanggal_janji_temu')->nullable(); // Added
            $table->time('waktu_janji_temu')->nullable(); // Added
            $table->text('keterangan')->nullable();
            $table->text('keterangan_admin')->nullable();
            $table->enum('status', ['1', '2', '3'])->default('1');
            $table->timestamps();
        });

        // 3. Transaksi Tabungan (Setoran & Penarikan)
        Schema::create('trans_tabungan', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->string('id_pengajuan_setor', 30)->nullable();
            $table->string('id_pengajuan_tarik', 30)->nullable();
            $table->foreignId('id_anggota')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->foreignId('id_jns_transaksi')->nullable()->constrained('jns_transaksi')->onDelete('set null');
            $table->foreignId('id_jns_via')->nullable()->constrained('jns_via')->onDelete('set null');
            $table->decimal('nominal', 15, 2);
            $table->text('keterangan')->nullable();
            $table->timestamp('tgl_transaksi')->useCurrent();
            $table->timestamps();
        });

        // 4. Janji Temu Tabungan (untuk setoran tunai)
        Schema::create('tbl_janji_temu_tabungan', function (Blueprint $table) {
            $table->string('id', 30)->primary(); // ✅ Generated ID
            $table->foreignId('id_nasabah')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->foreignId('lokasi_temu')->constrained('jns_lokasi_perusahaan')->onDelete('cascade');
            $table->enum('jenis', ['setoran', 'penarikan'])->default('setoran'); // Added
            $table->decimal('nominal', 15, 2);
            $table->datetime('tanggal_janji_temu');
            $table->time('waktu_janji_temu'); // Ensure TIME type
            $table->text('keterangan')->nullable();
            $table->text('keterangan_admin')->nullable(); // Added
            $table->enum('status', ['1', '2', '3'])->default('1'); // Added: 1=Menunggu, 2=Selesai, 3=Batal
            $table->timestamps();
        });

        // 5. Bukti Foto Universal (untuk semua fitur)
        Schema::create('tbl_bukti_foto', function (Blueprint $table) {
            $table->id();
            $table->string('owner_id', 30); // ID dari tabel owner (pengajuan, janji temu, transaksi, dll)
            $table->string('owner_fitur', 10); // T=Tabungan, P=Pinjaman, G=Gadai, D=Deposito
            $table->string('owner_trans', 20); // Type transaksi: STR, PNR, PNJ, JNJT, dll
            $table->string('file_path', 255);
            $table->text('keterangan')->nullable();
            $table->timestamps();
            
            // Index untuk pencarian
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
