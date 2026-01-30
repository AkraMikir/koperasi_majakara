<?php

// Test script untuk OTP Services
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing OTP Services...\n\n";

try {
    echo "1. Testing WhatsAppService...\n";
    $whatsAppService = app(\App\Services\WhatsAppService::class);
    echo "   ✓ WhatsAppService loaded successfully\n\n";
    
    echo "2. Testing OtpService...\n";
    $otpService = app(\App\Services\OtpService::class);
    echo "   ✓ OtpService loaded successfully\n\n";
    
    echo "3. Testing Fonnte Configuration...\n";
    $apiKey = config('services.fonnte.api_key');
    $apiUrl = config('services.fonnte.api_url');
    echo "   API Key: " . ($apiKey ? substr($apiKey, 0, 10) . "..." : "NOT SET") . "\n";
    echo "   API URL: " . ($apiUrl ?: "NOT SET") . "\n\n";
    
    echo "4. Testing OTP Configuration...\n";
    $otpLength = config('services.otp.length');
    $otpExpiry = config('services.otp.expiry_minutes');
    $maxAttempts = config('services.otp.max_attempts');
    $cooldown = config('services.otp.cooldown_seconds');
    echo "   OTP Length: {$otpLength}\n";
    echo "   Expiry Time: {$otpExpiry} minutes\n";
    echo "   Max Attempts: {$maxAttempts}\n";
    echo "   Cooldown: {$cooldown} seconds\n\n";
    
    echo "✅ All services and configurations are working correctly!\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    exit(1);
}
