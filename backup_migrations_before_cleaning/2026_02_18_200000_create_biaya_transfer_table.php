<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biaya_transfer', function (Blueprint $table) {
            $table->id();
            $table->string('bank_pengirim', 50);
            $table->string('bank_penerima', 50);
            $table->decimal('biaya_admin', 15, 2)->default(0);
            $table->string('keterangan', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('biaya_transfer');
    }
};
