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
        Schema::create('master_default_otp', function (Blueprint $table) {
            $table->id();
            $table->string('otp_code_hashed');
            $table->integer('used')->default(0);
            $table->timestamps();
        });

        Schema::create('log_default_otp_usage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('phone_number', 20);
            $table->string('session_id')->nullable();
            $table->string('type', 50)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_default_otp_usage');
        Schema::dropIfExists('master_default_otp');
    }
};
