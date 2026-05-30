<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deposito_persiapan_cair', function (Blueprint $table) {
            $table->id();

            // Referensi ke deposito & nasabah
            $table->foreignId('deposito_id')
                  ->constrained('tbl_deposito_h')
                  ->onDelete('cascade');
            $table->foreignId('nasabah_id')
                  ->constrained('tbl_nasabah')
                  ->onDelete('cascade');

            // Kalkulasi pre-computed (tidak perlu hitung ulang saat pencairan)
            $table->decimal('pokok', 15, 2);
            $table->decimal('bunga_kotor', 15, 2);
            $table->decimal('pajak', 15, 2);          // 20% dari bunga_kotor
            $table->decimal('bunga_bersih', 15, 2);
            $table->decimal('total_dibayar', 15, 2);  // pokok + bunga_bersih

            // Arah pencairan yang direncanakan
            $table->enum('metode_cair', [
                'saldo_tabungan',
                'rek_nasabah',
                'petty_cash_operator',
            ])->default('saldo_tabungan');

            // Lifecycle status
            $table->enum('status', [
                'tentatif',   // warning tampil, belum ada aksi
                'diproses',   // nasabah ajukan / proses aktif
                'selesai',    // pencairan berhasil
                'dibatalkan', // deposito ditutup/batal sebelum jatuh tempo
            ])->default('tentatif');

            // Tanggal referensi
            $table->date('tgl_peringatan');   // hari sistem mulai warning
            $table->date('tgl_target_cair');  // = tgl_jatuh_tempo DepositoH

            // Link ke record pencairan aktual (diisi saat nasabah ajukan)
            $table->foreignId('pencairan_id')
                  ->nullable()
                  ->constrained('tbl_pencairan_deposito')
                  ->onDelete('set null');

            // Catatan Admin / sistem
            $table->text('catatan')->nullable();

            $table->timestamps();

            // Index untuk query dashboard Owner
            $table->index(['status', 'tgl_target_cair']);
            $table->index(['deposito_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deposito_persiapan_cair');
    }
};
