<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_janji_temu_tabungan', function (Blueprint $table) {
            // Remove foreign key constraint on id_pengajuan
            // So we can store generated ID without having actual pengajuan record
            // This allows janji temu to have its own unique ID without creating pengajuan
            
            // Note: Since id_pengajuan is string and pengajuan table uses string ID,
            // we don't have a named constraint. Just make sure it's nullable and no constraint.
            // In the original migration, there was no constrained() call, so we're good.
            
            // Just to be safe, let's ensure the column exists and is properly configured
            $table->string('id_pengajuan', 30)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('tbl_janji_temu_tabungan', function (Blueprint $table) {
            // No change needed on rollback
        });
    }
};
