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
        Schema::create('jns_akun', function (Blueprint $table) {
            $table->id();
            $table->string('kode_akun', 20)->unique();
            $table->string('nama_akun', 100);
            $table->text('deskripsi')->nullable();
            $table->string('prefix_id', 10);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jns_akun');
    }
};
