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
        Schema::table('tbl_otp', function (Blueprint $table) {
            $table->enum('type', ['registration', 'transaction', 'login', 'pin'])->default('registration')->after('user_id');
            $table->enum('channel', ['whatsapp', 'sms', 'email'])->default('whatsapp')->after('otp_code');
            $table->string('phone_number', 20)->nullable()->after('channel');
            $table->string('session_id')->nullable()->after('phone_number'); // Untuk tracking session registrasi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_otp', function (Blueprint $table) {
            $table->dropColumn(['type', 'channel', 'phone_number', 'session_id']);
        });
    }
};
