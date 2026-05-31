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
        Schema::table('petty_cash_setoran_kantor', function (Blueprint $table) {
            $table->decimal('nominal_cash', 15, 2)->default(0)->after('total_setor');
            $table->decimal('nominal_tf', 15, 2)->default(0)->after('nominal_cash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('petty_cash_setoran_kantor', function (Blueprint $table) {
            $table->dropColumn(['nominal_cash', 'nominal_tf']);
        });
    }
};
