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
        Schema::create('tbl_persetujuan_syarat_gadai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nasabah_id')->constrained('tbl_nasabah')->onDelete('cascade');
            $table->timestamp('agreed_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_persetujuan_syarat_gadai');
    }
};
