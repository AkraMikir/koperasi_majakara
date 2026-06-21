<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_limit_pinjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_nasabah')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->decimal('limit_nominal', 15, 2)->default(1000000.00);
            $table->decimal('nominal_terpakai', 15, 2)->default(0.00);
            $table->timestamps();
        });

        Schema::create('tbl_log_limit_pinjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_nasabah')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->foreignId('id_user_admin')->constrained('users')->onDelete('cascade');
            $table->decimal('limit_sebelum', 15, 2);
            $table->decimal('limit_sesudah', 15, 2);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // Inisialisasi limit awal untuk nasabah yang sudah terdaftar
        $nasabahs = DB::table('tbl_nasabah')->get();
        foreach ($nasabahs as $nasabah) {
            DB::table('tbl_limit_pinjaman')->insert([
                'id_nasabah' => $nasabah->id,
                'limit_nominal' => 1000000.00,
                'nominal_terpakai' => 0.00,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_log_limit_pinjaman');
        Schema::dropIfExists('tbl_limit_pinjaman');
    }
};
