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
            $table->string('no_mesin_rangka', 255)->nullable()->after('slot_table');
            $table->string('no_imei_sn', 255)->nullable()->after('no_mesin_rangka');
            $table->string('kelengkapan', 255)->nullable()->after('no_imei_sn');
            $table->text('catatan')->nullable()->after('kelengkapan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_gadai_active', function (Blueprint $table) {
            $table->dropColumn(['no_mesin_rangka', 'no_imei_sn', 'kelengkapan', 'catatan']);
        });
    }
};
