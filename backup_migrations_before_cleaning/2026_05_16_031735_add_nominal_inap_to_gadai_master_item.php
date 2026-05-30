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
        Schema::table('tbl_gadai_master_item', function (Blueprint $table) {
            $table->decimal('nominal_inap', 15, 2)->default(0)->after('nominal_high');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_gadai_master_item', function (Blueprint $table) {
            $table->dropColumn('nominal_inap');
        });
    }
};
