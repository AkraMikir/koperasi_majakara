<?php
require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$apiKey = $_ENV['GEMINI_API_KEY'] ?? '';
if (!$apiKey) {
    // Coba baca langsung dari .env
    $env = file_get_contents(__DIR__ . '/../.env');
    preg_match('/GEMINI_API_KEY=([^\r\n]+)/', $env, $m);
    $apiKey = trim($m[1] ?? '');
}

echo "API Key prefix: " . substr($apiKey, 0, 10) . "...\n";

$url = 'https://generativelanguage.googleapis.com/v1beta/models?key=' . $apiKey;

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

echo "\n=== Flash models available ===\n";
foreach (($data['models'] ?? []) as $model) {
    if (str_contains(strtolower($model['name']), 'flash')) {
        echo $model['name'] . "\n";
    }
}
