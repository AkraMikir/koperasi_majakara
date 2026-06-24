<?php
require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$env = file_get_contents(__DIR__ . '/../.env');
preg_match('/GEMINI_API_KEY=([^\r\n]+)/', $env, $m);
$apiKey = trim($m[1] ?? '');

$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash:generateContent?key=" . $apiKey;

$payload = [
    'systemInstruction' => [
        'parts' => [['text' => 'Kamu = asisten AI Koperasi Majakara. Nama: Maja. Jawab singkat dalam bahasa Indonesia.']]
    ],
    'contents' => [
        ['role' => 'user', 'parts' => [['text' => 'halo']]]
    ],
    'generationConfig' => ['maxOutputTokens' => 100, 'temperature' => 0.4]
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
$data = json_decode($response, true);
if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
    echo "Response: " . $data['candidates'][0]['content']['parts'][0]['text'] . "\n";
} else {
    echo "Error: " . $response . "\n";
}
