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
            // Format nomor telepon ke format internasional (62xxx)
            $formattedPhone = $this->formatPhoneNumber($phoneNumber);
            
            // Pesan OTP
            $message = "Kode OTP Koperasi Majakara Anda adalah: *{$otpCode}*\n\n";
            $message .= "Kode ini berlaku selama 5 menit.\n";
            $message .= "Jangan berikan kode ini kepada siapapun termasuk staff Koperasi Majakara.\n\n";
            $message .= "Terima kasih.";

            Log::info('Sending OTP via Fonnte WhatsApp', [
                'phone' => $formattedPhone,
                'otp_length' => strlen($otpCode),
            ]);

            // Kirim request ke Fonnte API
            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
            ])
            ->timeout(30)
            ->post($this->apiUrl, [
                'target' => $formattedPhone,
                'message' => $message,
                'countryCode' => '62',
            ]);

            // Log response dari Fonnte
            Log::info('Fonnte API Response', [
                'status_code' => $response->status(),
                'body' => $response->json(),
            ]);

            // Cek apakah request berhasil
            if ($response->successful()) {
                $responseData = $response->json();
                
                // Fonnte biasanya return status: true jika berhasil
                if (isset($responseData['status']) && $responseData['status'] === true) {
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
     * Test koneksi ke Fonnte API
     * 
     * @return array
     */
    public function testConnection()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
            ])
            ->timeout(30)
            ->get('https://api.fonnte.com/validate');

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Koneksi ke Fonnte berhasil',
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'message' => 'Koneksi ke Fonnte gagal',
                'data' => null,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
}
