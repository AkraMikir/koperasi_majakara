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
        // Tambah kolom tipe ke petty_cash_saldo
        Schema::table('petty_cash_saldo', function (Blueprint $table) {
            $table->enum('tipe', ['cash', 'transfer'])->default('cash')->after('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('petty_cash_saldo', function (Blueprint $table) {
            $table->dropColumn('tipe');
        });
    }
};
