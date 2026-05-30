<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings_struk', function (Blueprint $table) {
            $table->dropColumn(['bunga_admin_gadai', 'slot_gadai', 'biaya_denda']);
        });
    }

    public function down(): void
    {
        Schema::table('settings_struk', function (Blueprint $table) {
            $table->decimal('bunga_admin_gadai', 5, 2)->default(0)->after('syarat_ketentuan_gadai');
            $table->integer('slot_gadai')->default(0)->after('bunga_admin_gadai');
            $table->decimal('biaya_denda', 15, 2)->default(0)->after('slot_gadai');
        });
    }
};
