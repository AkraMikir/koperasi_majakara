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
        // Update tbl_nasabah_temp: tambahkan alamat dan buat field nullable
        Schema::table('tbl_nasabah_temp', function (Blueprint $table) {
            if (!Schema::hasColumn('tbl_nasabah_temp', 'alamat')) {
                $table->text('alamat')->nullable()->after('jenis_kelamin');
            }
            // Buat field-field menjadi nullable untuk fleksibilitas
            $table->char('no_kk', 16)->nullable()->change();
            $table->string('tempat_lahir')->nullable()->change();
            $table->date('tanggal_lahir')->nullable()->change();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->change();
        });

        // Update tbl_data_ktp_temp: buat field nullable
        Schema::table('tbl_data_ktp_temp', function (Blueprint $table) {
            $table->string('nik', 16)->nullable()->change();
            $table->string('nama_lengkap', 100)->nullable()->change();
            $table->string('tempat_lahir', 100)->nullable()->change();
            $table->date('tanggal_lahir')->nullable()->change();
            $table->text('alamat')->nullable()->change();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable()->change();
            $table->string('file_ktp')->nullable()->change();
        });

        // Update tbl_data_rek_temp: buat field nullable
        Schema::table('tbl_data_rek_temp', function (Blueprint $table) {
            $table->char('no_rekening', 16)->nullable()->change();
            $table->string('nama_pemilik_rekening')->nullable()->change();
            $table->string('jenis_atm', 20)->nullable()->change();
        });

        // Update tbl_darurat_temp: buat field nullable
        Schema::table('tbl_darurat_temp', function (Blueprint $table) {
            $table->string('nama_lengkap')->nullable()->change();
            $table->string('hubungan_peminjam', 100)->nullable()->change();
            $table->char('no_telepon', 12)->nullable()->change();
            $table->text('alamat')->nullable()->change();
            $table->string('pekerjaan', 100)->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->string('no_ktp', 16)->nullable()->change();
            $table->string('foto_ktp')->nullable()->change();
        });

        // Update users_temp: buat field nullable
        Schema::table('users_temp', function (Blueprint $table) {
            $table->string('nama')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->string('password')->nullable()->change();
            $table->char('nomor_hp', 12)->nullable()->change();
            $table->string('foto')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert changes (optional, bisa di-skip jika tidak perlu)
        Schema::table('tbl_nasabah_temp', function (Blueprint $table) {
            if (Schema::hasColumn('tbl_nasabah_temp', 'alamat')) {
                $table->dropColumn('alamat');
            }
            $table->char('no_kk', 16)->nullable(false)->change();
            $table->string('tempat_lahir')->nullable(false)->change();
            $table->date('tanggal_lahir')->nullable(false)->change();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable(false)->change();
        });

        Schema::table('tbl_data_ktp_temp', function (Blueprint $table) {
            $table->string('nik', 16)->nullable(false)->change();
            $table->string('nama_lengkap', 100)->nullable(false)->change();
            $table->string('tempat_lahir', 100)->nullable(false)->change();
            $table->date('tanggal_lahir')->nullable(false)->change();
            $table->text('alamat')->nullable(false)->change();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable(false)->change();
            $table->string('file_ktp')->nullable(false)->change();
        });

        Schema::table('tbl_data_rek_temp', function (Blueprint $table) {
            $table->char('no_rekening', 16)->nullable(false)->change();
            $table->string('nama_pemilik_rekening')->nullable(false)->change();
            $table->string('jenis_atm', 20)->nullable(false)->change();
        });

        Schema::table('tbl_darurat_temp', function (Blueprint $table) {
            $table->string('nama_lengkap')->nullable(false)->change();
            $table->string('hubungan_peminjam', 100)->nullable(false)->change();
            $table->char('no_telepon', 12)->nullable(false)->change();
            $table->text('alamat')->nullable(false)->change();
            $table->string('pekerjaan', 100)->nullable(false)->change();
            $table->string('email')->nullable(false)->change();
            $table->string('no_ktp', 16)->nullable(false)->change();
            $table->string('foto_ktp')->nullable(false)->change();
        });

        Schema::table('users_temp', function (Blueprint $table) {
            $table->string('nama')->nullable(false)->change();
            $table->string('email')->nullable(false)->change();
            $table->string('password')->nullable(false)->change();
            $table->char('nomor_hp', 12)->nullable(false)->change();
            $table->string('foto')->nullable(false)->change();
        });
    }
};
