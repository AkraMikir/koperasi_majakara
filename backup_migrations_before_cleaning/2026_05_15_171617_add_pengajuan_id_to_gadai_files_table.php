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
        Schema::table('tbl_gadai_files', function (Blueprint $table) {
            $table->unsignedBigInteger('gadai_active_id')->nullable()->change();
            $table->foreignId('pengajuan_id')->nullable()->after('gadai_active_id')->constrained('tbl_gadai_pengajuan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_gadai_files', function (Blueprint $table) {
            $table->dropForeign(['pengajuan_id']);
            $table->dropColumn('pengajuan_id');
            $table->unsignedBigInteger('gadai_active_id')->nullable(false)->change();
        });
    }
};
