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
        Schema::create('tbl_pekerjaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nasabah_id')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->string('pekerjaan')->nullable();
            $table->decimal('penghasilan', 10, 2)->nullable();
            $table->string('nama_perusahaan')->nullable();
            $table->timestamps();
        });

        Schema::create('tbl_data_ktp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nasabah_id')->unique()->constrained('tbl_nasabah')->onDelete('cascade');
            $table->string('nik', 16)->unique();
            $table->string('nama_lengkap', 100);
            $table->string('tempat_lahir', 100);
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('file_ktp');
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
        Schema::dropIfExists('tbl_darurat');
        Schema::dropIfExists('tbl_data_rek');
        Schema::dropIfExists('tbl_data_ktp');
        Schema::dropIfExists('tbl_pekerjaan');
    }
};

