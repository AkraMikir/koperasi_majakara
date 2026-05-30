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
        Schema::create('tbl_gadai_master_inap_kendaraan', function (Blueprint $table) {
            $table->id();
            $table->string('golongan', 10)->unique();
            $table->string('jenis_kendaraan', 255);
            $table->decimal('nominal_inap', 15, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_gadai_master_inap_kendaraan');
    }
};
