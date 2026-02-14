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
        Schema::table('trans_tabungan', function (Blueprint $table) {
            $table->string('id_janji_temu_tabungan', 30)->nullable()->after('id_pengajuan_tarik');
            $table->foreign('id_janji_temu_tabungan')->references('id')->on('tbl_janji_temu_tabungan')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trans_tabungan', function (Blueprint $table) {
            $table->dropForeign(['id_janji_temu_tabungan']);
            $table->dropColumn('id_janji_temu_tabungan');
        });
    }
};
