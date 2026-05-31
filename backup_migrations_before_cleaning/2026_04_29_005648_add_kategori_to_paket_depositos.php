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
        Schema::table('paket_depositos', function (Blueprint $table) {
            $table->enum('kategori', ['biasa', 'rekomendasi', 'flash_sale'])->default('biasa')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paket_depositos', function (Blueprint $table) {
            $table->dropColumn('kategori');
        });
    }
};
