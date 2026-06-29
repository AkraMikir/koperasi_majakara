<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings_struk', function (Blueprint $table) {
            $table->text('syarat_ketentuan_gadai_elektronik')->nullable();
            $table->text('syarat_ketentuan_gadai_kendaraan')->nullable();
            $table->text('info_box_gadai_elektronik')->nullable();
            $table->text('info_box_gadai_kendaraan')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('settings_struk', function (Blueprint $table) {
            $table->dropColumn([
                'syarat_ketentuan_gadai_elektronik',
                'syarat_ketentuan_gadai_kendaraan',
                'info_box_gadai_elektronik',
                'info_box_gadai_kendaraan'
            ]);
        });
    }
};
