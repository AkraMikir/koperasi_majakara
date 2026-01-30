<?php

use Illuminate\Support\Facades\Route;
use App\Services\WhatsAppService;

Route::get('/test-whatsapp', function (WhatsAppService $whatsAppService) {
    try {
        // Test connection first
        $connectionTest = $whatsAppService->testConnection();
        
        echo "<h2>Test Fonnte Connection</h2>";
        echo "<pre>";
        print_r($connectionTest);
        echo "</pre>";
        
        // Test send OTP
        $testPhone = '089512543086'; // Nomor dari screenshot
        $testOTP = '123456';
        
        echo "<h2>Test Send OTP</h2>";
        echo "<p>Sending to: $testPhone</p>";
        echo "<p>OTP Code: $testOTP</p>";
        
        $result = $whatsAppService->sendOTP($testPhone, $testOTP);
        
        echo "<h3>Result:</h3>";
        echo "<pre>";
        print_r($result);
        echo "</pre>";
        
        if ($result['success']) {
            echo "<p style='color: green; font-weight: bold;'>✅ SUCCESS! Check WhatsApp!</p>";
        } else {
            echo "<p style='color: red; font-weight: bold;'>❌ FAILED: " . $result['message'] . "</p>";
        }
        
        // Show config
        echo "<h2>Configuration</h2>";
        echo "<pre>";
        echo "API Key: " . (config('services.fonnte.api_key') ? substr(config('services.fonnte.api_key'), 0, 10) . '...' : 'NOT SET') . "\n";
        echo "API URL: " . config('services.fonnte.api_url') . "\n";
        echo "Sender: " . config('services.fonnte.sender_number') . "\n";
        echo "</pre>";
        
    } catch (\Exception $e) {
        echo "<p style='color: red;'>Exception: " . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
});
