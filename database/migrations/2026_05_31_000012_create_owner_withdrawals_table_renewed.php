<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('owner_withdrawals', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('nominal_cash', 15, 2)->default(0.00);
            $table->decimal('nominal_tf', 15, 2)->default(0.00);
            $table->string('sumber', 255);
            $table->string('keterangan', 255)->nullable();
            $table->string('bukti_foto', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('owner_withdrawals');
    }
};
