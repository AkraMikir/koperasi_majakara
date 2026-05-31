<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings_struk', function (Blueprint $table) {
            $table->id();
            $table->string('nama_koperasi')->nullable();
            $table->text('alamat_koperasi')->nullable();
            $table->string('no_telp')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('nama_pt')->nullable();
            $table->string('format_no_struk')->default('STRK-{YYYYMMDD}-{XXXX}');
            $table->text('syarat_ketentuan_gadai')->nullable();
            $table->decimal('extra_nilai_kehilangan', 15, 2)->default(0.00);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings_struk');
    }
};
