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
        if (Schema::hasColumn('tbl_bukti_foto_tabungan', 'nominal')) {
            Schema::table('tbl_bukti_foto_tabungan', function (Blueprint $table) {
                $table->dropColumn(['nominal', 'keterangan']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_bukti_foto_tabungan', function (Blueprint $table) {
            $table->decimal('nominal', 15, 2)->after('jenis');
            $table->string('keterangan')->after('nominal');
        });
    }
};
