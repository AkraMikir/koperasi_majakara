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
        Schema::create('owner_withdrawals', function (Blueprint $table) {
            $table->string('id', 30)->primary(); // OWD-DDMMYY-XXX
            $table->foreignId('user_id')->constrained('users');
            $table->decimal('nominal_cash', 15, 2)->default(0);
            $table->decimal('nominal_tf', 15, 2)->default(0);
            $table->string('sumber'); // tabungan, petty_cash, other
            $table->string('keterangan')->nullable();
            $table->string('bukti_foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('owner_withdrawals');
    }
};
