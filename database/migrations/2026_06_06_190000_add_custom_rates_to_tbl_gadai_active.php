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
        Schema::table('tbl_gadai_active', function (Blueprint $table) {
            $table->decimal('rate_jasa', 5, 2)->nullable()->after('nominal_deal');
            $table->decimal('rate_inap_persen', 5, 2)->nullable()->after('biaya_jasa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_gadai_active', function (Blueprint $table) {
            $table->dropColumn(['rate_jasa', 'rate_inap_persen']);
        });
    }
};
