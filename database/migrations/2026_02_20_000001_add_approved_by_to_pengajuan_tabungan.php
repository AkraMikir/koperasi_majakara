<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_pengajuan_tabungan', function (Blueprint $table) {
            $table->foreignId('approved_by_user_id')->nullable()->after('status')->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_pengajuan_tabungan', function (Blueprint $table) {
            $table->dropForeign(['approved_by_user_id']);
        });
    }
};
