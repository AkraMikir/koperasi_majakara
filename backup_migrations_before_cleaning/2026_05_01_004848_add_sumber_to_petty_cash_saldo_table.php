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
        Schema::table('petty_cash_saldo', function (Blueprint $table) {
            $table->string('sumber', 50)->nullable()->after('tipe')->comment('tabungan, pinjaman, deposito, other, petty_cash');
        });

        // Update existing records: default to 'other' (Modal Awal) for Modal injections, 
        // and try to map based on ref_table for others.
        DB::table('petty_cash_saldo')->update(['sumber' => 'other']);
    }

    public function down(): void
    {
        Schema::table('petty_cash_saldo', function (Blueprint $table) {
            $table->dropColumn('sumber');
        });
    }
};
