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
        Schema::table('petty_cash_transaksi_nasabah', function (Blueprint $table) {
            $table->string('ref_table', 50)->nullable()->after('keterangan');
            $table->string('ref_id', 30)->nullable()->after('ref_table');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('petty_cash_transaksi_nasabah', function (Blueprint $table) {
            $table->dropColumn(['ref_table', 'ref_id']);
        });
    }
};
