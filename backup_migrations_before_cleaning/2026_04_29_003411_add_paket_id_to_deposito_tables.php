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
        Schema::table('tbl_pengajuan_deposito', function (Blueprint $table) {
            $table->foreignId('paket_id')->nullable()->constrained('paket_depositos')->nullOnDelete();
        });

        Schema::table('tbl_deposito_h', function (Blueprint $table) {
            $table->foreignId('paket_id')->nullable()->constrained('paket_depositos')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_pengajuan_deposito', function (Blueprint $table) {
            $table->dropForeign(['paket_id']);
            $table->dropColumn('paket_id');
        });

        Schema::table('tbl_deposito_h', function (Blueprint $table) {
            $table->dropForeign(['paket_id']);
            $table->dropColumn('paket_id');
        });
    }
};
