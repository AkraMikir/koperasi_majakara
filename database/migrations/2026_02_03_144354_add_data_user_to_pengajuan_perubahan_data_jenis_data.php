<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration//adad
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah enum jenis_data untuk menambahkan 'data_user'
        DB::statement("ALTER TABLE pengajuan_perubahan_data MODIFY COLUMN jenis_data ENUM('data_user', 'data_pribadi', 'data_ktp', 'pekerjaan', 'rekening', 'kontak_darurat') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke enum asli
        DB::statement("ALTER TABLE pengajuan_perubahan_data MODIFY COLUMN jenis_data ENUM('data_pribadi', 'data_ktp', 'pekerjaan', 'rekening', 'kontak_darurat') NOT NULL");
    }
};
