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
        Schema::table('tbl_pinjaman_h', function (Blueprint $table) {
            $table->string('bank_pengirim')->nullable();
            $table->decimal('biaya_transfer', 15, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_pinjaman_h', function (Blueprint $table) {
            $table->dropColumn(['bank_pengirim', 'biaya_transfer']);
        });
    }
};
