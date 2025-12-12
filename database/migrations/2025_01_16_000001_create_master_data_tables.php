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

        Schema::create('suku_bunga', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_bunga', 32);
            $table->decimal('opsi_val', 5, 4);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suku_bunga');
        Schema::dropIfExists('jns_angsuran_minggu');
        Schema::dropIfExists('jns_angsuran_bulan');
        Schema::dropIfExists('jns_lokasi_perusahaan');
    }
};



