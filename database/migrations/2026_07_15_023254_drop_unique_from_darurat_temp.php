<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_darurat_temp', function (Blueprint $table) {
            $table->dropUnique(['no_telepon']);
            $table->dropUnique(['no_ktp']);
        });
    }

    public function down(): void
    {
        Schema::table('tbl_darurat_temp', function (Blueprint $table) {
            $table->unique('no_telepon');
            $table->unique('no_ktp');
        });
    }
};
