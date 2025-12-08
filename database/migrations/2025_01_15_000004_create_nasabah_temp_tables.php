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
        Schema::create('tbl_nasabah_temp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->char('no_kk', 16)->unique();
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('foto_ktp')->nullable();
            $table->string('foto_kk')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('tbl_pekerjaan_temp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nasabah_id')->constrained('tbl_nasabah_temp')->onDelete('cascade');
            $table->string('pekerjaan')->nullable();
            $table->decimal('penghasilan', 10, 2)->nullable();
            $table->string('nama_perusahaan')->nullable();
            $table->string('nama_bank')->nullable();
            $table->timestamps();
        });

        Schema::create('tbl_data_ktp_temp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nasabah_id')->unique()->constrained('tbl_nasabah_temp')->onDelete('cascade');
            $table->string('nik', 16)->unique();
            $table->string('nama_lengkap', 100);
            $table->string('tempat_lahir', 100);
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('file_ktp');
            $table->timestamps();
        });

        Schema::create('tbl_data_rek_temp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nasabah_id')->constrained('tbl_nasabah_temp')->onDelete('cascade');
            $table->char('no_rekening', 16)->unique();
            $table->string('nama_pemilik_rekening');
            $table->string('jenis_atm', 20);
            $table->timestamps();
        });

        Schema::create('tbl_darurat_temp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_nasabah')->constrained('tbl_nasabah_temp')->onDelete('cascade');
            $table->string('nama_lengkap');
            $table->string('hubungan_peminjam', 100);
            $table->char('no_telepon', 12)->unique();
            $table->text('alamat');
            $table->string('pekerjaan', 100);
            $table->string('email');
            $table->string('no_ktp', 16)->unique();
            $table->string('foto_ktp');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_darurat_temp');
        Schema::dropIfExists('tbl_data_rek_temp');
        Schema::dropIfExists('tbl_data_ktp_temp');
        Schema::dropIfExists('tbl_pekerjaan_temp');
        Schema::dropIfExists('tbl_nasabah_temp');
    }
};

