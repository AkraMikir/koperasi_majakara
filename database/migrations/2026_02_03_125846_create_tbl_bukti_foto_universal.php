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
        Schema::create('tbl_bukti_foto', function (Blueprint $table) {
            $table->id();
            $table->string('owner_id', 30); // Complex ID dari table manapun
            $table->char('owner_fitur', 1); // T, P, D, G
            $table->string('owner_trans', 10); // STR, PNR, PMB, dll
            $table->string('file_path', 255); // Path ke storage
            $table->string('keterangan', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            
            // Indexes for performance
            $table->index('owner_id', 'idx_owner');
            $table->index(['owner_fitur', 'owner_trans'], 'idx_owner_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_bukti_foto');
    }
};
