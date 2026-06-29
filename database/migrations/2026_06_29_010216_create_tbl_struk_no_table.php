<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_struk_no', function (Blueprint $table) {
            $table->id();
            $table->string('pinjaman_id', 255)->nullable()->index();
            $table->unsignedBigInteger('gadai_id')->nullable()->index();
            $table->unsignedInteger('no_global');
            $table->unsignedInteger('no_harian_all');
            $table->unsignedInteger('no_harian_jenis');
            $table->string('no_struk', 30);
            $table->enum('jenis', ['pinjaman', 'gadai']);
            $table->date('tanggal')->index();
            $table->timestamps();

            $table->foreign('pinjaman_id')->references('id')->on('tbl_pinjaman_h')->onDelete('set null');
            $table->foreign('gadai_id')->references('id')->on('tbl_gadai_active')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_struk_no');
    }
};
