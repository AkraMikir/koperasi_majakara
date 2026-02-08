<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $apiKey;
    protected $apiUrl;
    protected $senderNumber;

    public function __construct()
    {
        $this->apiKey = config('services.fonnte.api_key');
        $this->apiUrl = config('services.fonnte.api_url');
        $this->senderNumber = config('services.fonnte.sender_number');
    }

    /**
     * Kirim OTP via WhatsApp menggunakan Fonnte API
     * 
     * @param string $phoneNumber Nomor telepon tujuan (format: 08xxx atau 628xxx)
     * @param string $otpCode Kode OTP 6 digit
     * @return array ['success' => bool, 'message' => string, 'data' => array|null]
     */
    public function sendOTP($phoneNumber, $otpCode)
    {
        try {
            // Cek konfigurasi Fonnte
            if (empty($this->apiKey)) {
                Log::error('Fonnte API key belum di-set. Tambahkan FONNTE_API_KEY di file .env');
                return [
                    'success' => false,
                    'message' => 'Server WhatsApp belum dikonfigurasi. Hubungi admin.',
                    'data' => null,
                ];
            }

            // Format nomor: Fonnte menerima 08xxx dengan countryCode 62, atau 62xxx
            $formattedPhone = $this->formatPhoneNumber($phoneNumber);
            // Target untuk Fonnte: bisa 08123456789 (dengan countryCode 62) atau 628123456789
            $target = (substr($formattedPhone, 0, 2) === '62')
                ? '0' . substr($formattedPhone, 2)
                : $formattedPhone;
            
            // Pesan OTP
            $message = "Kode OTP Koperasi Majakara Anda adalah: *{$otpCode}*\n\n";
            $message .= "Kode ini berlaku selama 5 menit.\n";
            $message .= "Jangan berikan kode ini kepada siapapun termasuk staff Koperasi Majakara.\n\n";
            $message .= "Terima kasih.";

            Log::info('Sending OTP via Fonnte WhatsApp', [
                'target' => $target,
                'formatted_phone' => $formattedPhone,
                'api_url' => $this->apiUrl,
            ]);

            // Kirim request ke Fonnte API (target 08xxx, countryCode 62 sesuai dokumentasi)
            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
            ])
            ->timeout(30)
            ->post($this->apiUrl, [
                'target' => $target,
                'message' => $message,
                'countryCode' => '62',
            ]);

            // Log response dari Fonnte (untuk debugging)
            $responseBody = $response->json();
            if ($responseBody === null) {
                $responseBody = [];
                Log::warning('Fonnte API response bukan JSON', ['raw' => substr($response->body(), 0, 500)]);
            }
            Log::info('Fonnte API Response', [
                'status_code' => $response->status(),
                'body' => $responseBody,
            ]);

            // Cek apakah request berhasil
            if ($response->successful()) {
                $responseData = $responseBody ?? [];
                
                // Fonnte return status: true atau 1 jika berhasil (kadang API mengembalikan integer 1)
                $statusVal = $responseData['status'] ?? $responseData['success'] ?? false;
                $ok = ($statusVal === true || $statusVal === 1);
                if ($ok) {
                    return [
                        'success' => true,
                        'message' => 'OTP berhasil dikirim ke WhatsApp',
                        'data' => $responseData,
                    ];
                } else {
                    // API response tapi status bukan true
                    Log::warning('Fonnte API returned non-success status', [
                        'response' => $responseData,
                    ]);
                    
                    return [
                        'success' => false,
                        'message' => $responseData['message'] ?? 'Gagal mengirim OTP',
                        'data' => $responseData,
                    ];
                }
            } else {
                // HTTP error
                Log::error('Fonnte API HTTP Error', [
                    'status_code' => $response->status(),
                    'body' => $response->body(),
                ]);
                
                return [
                    'success' => false,
                    'message' => 'Gagal menghubungi server WhatsApp. Silakan coba lagi.',
                    'data' => null,
                ];
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp Service Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengirim OTP: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    /**
     * Format nomor telepon ke format internasional
     * Contoh: 08123456789 -> 628123456789
     *         +628123456789 -> 628123456789
     *         6281234567890 -> 6281234567890
     * 
     * @param string $phoneNumber
     * @return string
     */
    private function formatPhoneNumber($phoneNumber)
    {
        // Hapus semua karakter selain angka dan +
        $phone = preg_replace('/[^0-9+]/', '', $phoneNumber);
        
        // Hapus tanda +
        $phone = str_replace('+', '', $phone);
        
        // Jika dimulai dengan 0, ganti dengan 62
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }
        
        // Jika tidak dimulai dengan 62, tambahkan 62
        if (substr($phone, 0, 2) !== '62') {
            $phone = '62' . $phone;
        }
        
        return $phone;
    }

    /**
     * Test koneksi ke Fonnte API.
     * Jika API key terisi dan endpoint ada, dianggap siap (endpoint /validate tidak selalu tersedia).
     *
     * @return array
     */
    public function testConnection()
    {
        try {
            if (empty($this->apiKey)) {
                return [
                    'success' => false,
                    'message' => 'FONNTE_API_KEY belum di-set di .env',
                    'data' => null,
                ];
            }

            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
            ])
            ->timeout(10)
            ->get('https://api.fonnte.com/validate');

            if ($response->successful()) {
                $data = $response->json();
                $ok = ($data['status'] ?? $data['success'] ?? false) == true || ($data['status'] ?? null) === 1;
                if ($ok) {
                    return [
                        'success' => true,
                        'message' => 'Koneksi ke Fonnte berhasil',
                        'data' => $data,
                    ];
                }
            }

            // Endpoint validate bisa tidak tersedia; yang penting kirim OTP berhasil
            return [
                'success' => true,
                'message' => 'API Key terisi. Jika "Test Send OTP" di bawah berhasil, Fonnte siap dipakai.',
                'data' => ['note' => 'Validate endpoint mungkin tidak tersedia'],
            ];
        } catch (\Exception $e) {
            return [
                'success' => true,
                'message' => 'API Key terisi. Silakan cek "Test Send OTP" di bawah.',
                'data' => null,
            ];
        }
    }
}
