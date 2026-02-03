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
        // Create jns_fitur table
        Schema::create('jns_fitur', function (Blueprint $table) {
            $table->id();
            $table->char('kode', 1)->unique();
            $table->string('nama', 50);
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Create jns_via table
        Schema::create('jns_via', function (Blueprint $table) {
            $table->id();
            $table->char('kode', 1)->unique();
            $table->string('nama', 50);
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Create jns_transaksi table
        Schema::create('jns_transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 5)->unique();
            $table->string('nama', 50);
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed data for jns_fitur
        DB::table('jns_fitur')->insert([
            ['kode' => 'T', 'nama' => 'Tabungan', 'deskripsi' => 'Fitur tabungan', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'P', 'nama' => 'Pinjaman', 'deskripsi' => 'Fitur pinjaman', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'D', 'nama' => 'Deposito', 'deskripsi' => 'Fitur deposito', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'G', 'nama' => 'Gadai', 'deskripsi' => 'Fitur gadai', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Seed data for jns_via
        DB::table('jns_via')->insert([
            ['kode' => 'T', 'nama' => 'Transfer', 'deskripsi' => 'Via transfer bank', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'C', 'nama' => 'Cash', 'deskripsi' => 'Via tunai/cash', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Seed data for jns_transaksi
        DB::table('jns_transaksi')->insert([
            ['kode' => 'STR', 'nama' => 'Setoran', 'deskripsi' => 'Setoran tabungan', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'PNR', 'nama' => 'Penarikan', 'deskripsi' => 'Penarikan tabungan', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'TRKT', 'nama' => 'Transaksi Tabungan', 'deskripsi' => 'Transaksi tabungan final', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'PNJ', 'nama' => 'Pengajuan Pinjaman', 'deskripsi' => 'Pengajuan pinjaman', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'PMB', 'nama' => 'Pembayaran', 'deskripsi' => 'Pembayaran angsuran', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'DPNJM', 'nama' => 'Data Pinjaman', 'deskripsi' => 'Header pinjaman', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'TPNJM', 'nama' => 'Tempo Pinjaman', 'deskripsi' => 'Jadwal angsuran', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jns_transaksi');
        Schema::dropIfExists('jns_via');
        Schema::dropIfExists('jns_fitur');
    }
};
