<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings_struk', function (Blueprint $table) {
            $table->text('syarat_ketentuan_pinjaman')->nullable()->after('syarat_ketentuan_gadai');
            $table->text('info_box_pinjaman')->nullable()->after('syarat_ketentuan_pinjaman');
        });
    }

    public function down(): void
    {
        Schema::table('settings_struk', function (Blueprint $table) {
            $table->dropColumn(['syarat_ketentuan_pinjaman', 'info_box_pinjaman']);
        });
    }
};
