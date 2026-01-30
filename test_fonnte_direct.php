<?php

// Direct test WhatsApp Fonnte API
require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Http;

// Load .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "=== TEST FONNTE API ===\n\n";

$apiKey = $_ENV['FONNTE_API_KEY'] ?? null;
$apiUrl = $_ENV['FONNTE_API_URL'] ?? 'https://api.fonnte.com/send';
$targetPhone = '089512543086'; // Nomor HP dari screenshot

echo "API Key: " . ($apiKey ? substr($apiKey, 0, 10) . '...' : 'NOT SET') . "\n";
echo "API URL: $apiUrl\n";
echo "Target: $targetPhone\n\n";

if (!$apiKey) {
    die("ERROR: FONNTE_API_KEY not set in .env\n");
}

// Format phone number
$formattedPhone = $targetPhone;
if (substr($formattedPhone, 0, 1) === '0') {
    $formattedPhone = '62' . substr($formattedPhone, 1);
}

echo "Formatted Phone: $formattedPhone\n\n";

// Message
$message = "TEST OTP Koperasi Majakara\n";
$message .= "Kode OTP: *123456*\n";
$message .= "Berlaku 5 menit.\n";

echo "Sending to Fonnte...\n";

try {
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'target' => $formattedPhone,
        'message' => $message,
        'countryCode' => '62',
    ]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: ' . $apiKey,
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    
    curl_close($ch);
    
    echo "HTTP Code: $httpCode\n";
    echo "Response: $response\n";
    
    if ($error) {
        echo "CURL Error: $error\n";
    }
    
    $data = json_decode($response, true);
    
    if ($data) {
        echo "\nParsed Response:\n";
        print_r($data);
        
        if (isset($data['status'])) {
            if ($data['status'] === true || $data['status'] === 'true') {
                echo "\n✅ SUCCESS: OTP sent to WhatsApp!\n";
            } else {
                echo "\n❌ FAILED: " . ($data['message'] ?? 'Unknown error') . "\n";
            }
        }
    }
    
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}

echo "\n=== END TEST ===\n";
