<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_nasabah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->char('no_kk', 16)->unique();
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->text('alamat');
            $table->string('foto_ktp')->nullable();
            $table->string('foto_kk')->nullable();
            $table->timestamps();
        });

        Schema::create('tbl_nasabah_temp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users_temp')->onDelete('cascade');
            $table->char('no_kk', 16)->nullable()->unique();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->text('alamat');
            $table->string('foto_ktp')->nullable();
            $table->string('foto_kk')->nullable();
            $table->timestamps();
        });

        Schema::create('tbl_data_ktp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nasabah_id')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->string('nik', 16)->unique();
            $table->string('nama_lengkap', 100);
            $table->string('tempat_lahir', 100);
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('file_ktp', 255);
            $table->timestamps();
        });

        Schema::create('tbl_data_ktp_temp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nasabah_id')->constrained('tbl_nasabah_temp')->onDelete('cascade');
            $table->string('nik', 16)->nullable()->unique();
            $table->string('nama_lengkap', 100)->nullable();
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable();
            $table->string('file_ktp', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('tbl_data_rek', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nasabah_id')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->char('no_rekening', 16)->unique();
            $table->string('nama_pemilik_rekening');
            $table->string('nama_bank', 20);
            $table->timestamps();
        });

        Schema::create('tbl_data_rek_temp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nasabah_id')->constrained('tbl_nasabah_temp')->onDelete('cascade');
            $table->char('no_rekening', 16)->nullable()->unique();
            $table->string('nama_pemilik_rekening')->nullable();
            $table->string('jenis_atm', 20)->nullable();
            $table->timestamps();
        });

        Schema::create('tbl_pekerjaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nasabah_id')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->string('pekerjaan')->nullable();
            $table->string('penghasilan', 100)->nullable();
            $table->string('nama_perusahaan')->nullable();
            $table->timestamps();
        });

        Schema::create('tbl_pekerjaan_temp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nasabah_id')->constrained('tbl_nasabah_temp')->onDelete('cascade');
            $table->string('pekerjaan')->nullable();
            $table->string('penghasilan', 100)->nullable();
            $table->string('nama_perusahaan')->nullable();
            $table->string('nama_bank')->nullable();
            $table->timestamps();
        });

        Schema::create('tbl_darurat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_nasabah')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->string('nama_lengkap');
            $table->string('hubungan_peminjam', 100);
            $table->char('no_telepon', 12)->unique();
            $table->text('alamat');
            $table->string('pekerjaan', 100);
            $table->string('email');
            $table->string('no_ktp', 16)->unique();
            $table->string('foto_ktp', 255);
            $table->timestamps();
        });

        Schema::create('tbl_darurat_temp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_nasabah')->constrained('tbl_nasabah_temp')->onDelete('cascade');
            $table->string('nama_lengkap')->nullable();
            $table->string('hubungan_peminjam', 100)->nullable();
            $table->char('no_telepon', 12)->nullable()->unique();
            $table->text('alamat')->nullable();
            $table->string('pekerjaan', 100)->nullable();
            $table->string('email')->nullable();
            $table->string('no_ktp', 16)->nullable()->unique();
            $table->string('foto_ktp', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('admin_operasional', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });

        Schema::create('admin_utama', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('tbl_otp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->enum('type', ['registration', 'transaction', 'login', 'pin', 'password_reset'])->default('registration');
            $table->string('otp_code', 6);
            $table->enum('channel', ['whatsapp', 'sms', 'email'])->default('whatsapp');
            $table->string('phone_number', 20)->nullable();
            $table->string('session_id')->nullable();
            $table->timestamp('expired_at');
            $table->boolean('is_verified')->default(false);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_otp');
        Schema::dropIfExists('admin_utama');
        Schema::dropIfExists('admin_operasional');
        Schema::dropIfExists('tbl_darurat_temp');
        Schema::dropIfExists('tbl_darurat');
        Schema::dropIfExists('tbl_pekerjaan_temp');
        Schema::dropIfExists('tbl_pekerjaan');
        Schema::dropIfExists('tbl_data_rek_temp');
        Schema::dropIfExists('tbl_data_rek');
        Schema::dropIfExists('tbl_data_ktp_temp');
        Schema::dropIfExists('tbl_data_ktp');
        Schema::dropIfExists('tbl_nasabah_temp');
        Schema::dropIfExists('tbl_nasabah');
    }
};
