<?php

namespace App\Services;

use App\Models\Otp;
use App\Models\UserTemp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class OtpService
{
    protected $whatsAppService;
    protected $otpLength;
    protected $expiryMinutes;
    protected $maxAttempts;
    protected $cooldownSeconds;

    public function __construct(WhatsAppService $whatsAppService)
    {
        $this->whatsAppService = $whatsAppService;
        $this->otpLength = (int) config('services.otp.length', 6);
        $this->expiryMinutes = (int) config('services.otp.expiry_minutes', 5);
        $this->maxAttempts = (int) config('services.otp.max_attempts', 3);
        $this->cooldownSeconds = min(60, (int) config('services.otp.cooldown_seconds', 60)); // max 1 menit
    }

    /**
     * Generate dan kirim OTP ke WhatsApp
     * 
     * @param string $phoneNumber Nomor telepon tujuan
     * @param string $sessionId Session ID registrasi
     * @param int|null $userTempId ID dari users_temp (optional)
     * @param string $type Tipe OTP (registration, transaction, login, pin)
     * @return array ['success' => bool, 'message' => string, 'otp' => Otp|null]
     */
    public function generateAndSend($phoneNumber, $sessionId, $userTempId = null, $type = 'registration')
    {
        try {
            // Bersihkan OTP yang sudah expired agar tidak menumpuk statusnya
            $this->cleanUpExpired($phoneNumber);

            // Cek rate limiting - cooldown 60 detik
            if (!$this->checkCooldown($phoneNumber)) {
                Log::warning('OTP cooldown active', ['phone' => $phoneNumber]);
                return [
                    'success' => false,
                    'message' => 'Mohon tunggu ' . $this->cooldownSeconds . ' detik sebelum meminta OTP lagi.',
                    'otp' => null,
                ];
            }

            // Cek rate limiting - maksimal attempts
            if (!$this->checkRateLimit($phoneNumber)) {
                Log::warning('OTP rate limit exceeded', ['phone' => $phoneNumber]);
                return [
                    'success' => false,
                    'message' => 'Terlalu banyak permintaan OTP. Silakan coba lagi dalam 15 menit.',
                    'otp' => null,
                ];
            }

            // Generate OTP code (6 digit)
            $otpCode = $this->generateOtpCode();

            // Set expired time (1 menit dari sekarang)
            $expiredAt = Carbon::now()->addMinutes($this->expiryMinutes);

            Log::info('Generating OTP', [
                'phone' => $phoneNumber,
                'session_id' => $sessionId,
                'type' => $type,
                'expired_at' => $expiredAt->toDateTimeString(),
            ]);

            // Simpan OTP ke database (is_verified eksplisit 0 agar tidak salah baca oleh DB/driver)
            $otp = Otp::create([
                'user_id' => $userTempId, // Bisa null untuk registration
                'otp_code' => $otpCode,
                'phone_number' => $phoneNumber,
                'session_id' => $sessionId,
                'type' => $type,
                'channel' => 'whatsapp',
                'expired_at' => $expiredAt->format('Y-m-d H:i:s'), // Convert to string
                'is_verified' => 0,
                'created_at' => now()->format('Y-m-d H:i:s'), // Add created_at manually
            ]);

            Log::info('OTP saved to database', ['otp_id' => $otp->id]);

            // Kirim OTP via WhatsApp
            $sendResult = $this->whatsAppService->sendOTP($phoneNumber, $otpCode);

            if ($sendResult['success']) {
                Log::info('OTP sent successfully via WhatsApp', [
                    'otp_id' => $otp->id,
                    'phone' => $phoneNumber,
                ]);

                return [
                    'success' => true,
                    'message' => 'Kode OTP telah dikirim ke WhatsApp Anda. Silakan cek pesan masuk.',
                    'otp' => $otp,
                ];
            } else {
                // Jika gagal kirim, hapus OTP dari database
                $otp->delete();

                Log::error('Failed to send OTP via WhatsApp', [
                    'phone' => $phoneNumber,
                    'error' => $sendResult['message'],
                ]);

                return [
                    'success' => false,
                    'message' => 'Gagal mengirim OTP: ' . $sendResult['message'],
                    'otp' => null,
                ];
            }
        } catch (\Exception $e) {
            Log::error('OTP Service Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengirim OTP. Silakan coba lagi.',
                'otp' => null,
            ];
        }
    }

    /**
     * Verifikasi OTP
     * 
     * @param string $otpCode Kode OTP yang diinput user
     * @param string $phoneNumber Nomor telepon
     * @param string $sessionId Session ID
     * @return array ['success' => bool, 'message' => string]
     */
    public function verify($otpCode, $phoneNumber, $sessionId)
    {
        try {
            Log::info('Verifying OTP', [
                'phone' => $phoneNumber,
                'session_id' => $sessionId,
            ]);

            // Cari OTP yang valid
            $otp = Otp::where('otp_code', $otpCode)
                ->where('phone_number', $phoneNumber)
                ->where('session_id', $sessionId)
                ->where('is_verified', false)
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$otp) {
                Log::warning('OTP not found or already used', [
                    'phone' => $phoneNumber,
                    'session_id' => $sessionId,
                ]);

                return [
                    'success' => false,
                    'message' => 'Kode OTP tidak valid atau sudah digunakan.',
                ];
            }

            // Cek apakah OTP sudah expired (expired_at bisa string dari DB, parse ke Carbon)
            $expiredAt = $otp->expired_at instanceof \Carbon\Carbon
                ? $otp->expired_at
                : Carbon::parse($otp->expired_at);
            if (Carbon::now()->greaterThan($expiredAt)) {
                Log::warning('OTP expired', [
                    'otp_id' => $otp->id,
                    'expired_at' => $expiredAt->toDateTimeString(),
                    'now' => Carbon::now()->toDateTimeString(),
                ]);

                return [
                    'success' => false,
                    'message' => 'Kode OTP sudah kadaluarsa. Silakan minta kode baru.',
                ];
            }

            // Mark OTP sebagai verified
            $otp->update(['is_verified' => true]);

            Log::info('OTP verified successfully', ['otp_id' => $otp->id]);

            return [
                'success' => true,
                'message' => 'Kode OTP berhasil diverifikasi.',
            ];
        } catch (\Exception $e) {
            Log::error('OTP Verification Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat verifikasi OTP. Silakan coba lagi.',
            ];
        }
    }

    /**
     * Generate random OTP code
     * 
     * @return string
     */
    private function generateOtpCode()
    {
        return str_pad(rand(0, 999999), $this->otpLength, '0', STR_PAD_LEFT);
    }

    /**
     * Cek cooldown (1 menit antara request OTP)
     * 
     * @param string $phoneNumber
     * @return bool True jika boleh request, False jika masih cooldown
     */
    private function checkCooldown($phoneNumber)
    {
        $lastOtp = Otp::where('phone_number', $phoneNumber)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$lastOtp) {
            return true; // Tidak ada OTP sebelumnya
        }

        $secondsSinceLastOtp = Carbon::now()->diffInSeconds($lastOtp->created_at);

        return $secondsSinceLastOtp >= $this->cooldownSeconds;
    }

    /**
     * Cek rate limiting (maksimal 3 request dalam durasi expiry)
     * 
     * @param string $phoneNumber
     * @return bool True jika boleh request, False jika limit exceeded
     */
    private function checkRateLimit($phoneNumber)
    {
        // Gunakan durasi expiry sebagai window rate limit (ditambah 1 menit buffer)
        // Jadi jika expiry 5 menit, rate limit window adalah 6 menit.
        // Setelah kode expired, user bisa mencoba lagi segera.
        $windowMinutes = $this->expiryMinutes + 1;
        
        $count = Otp::where('phone_number', $phoneNumber)
            ->where('created_at', '>', Carbon::now()->subMinutes($windowMinutes))
            ->count();

        return $count < $this->maxAttempts;
    }

    /**
     * Clean up expired OTPs for a number to prevent database clutter
     * 
     * @param string $phoneNumber
     */
    private function cleanUpExpired($phoneNumber)
    {
        Otp::where('phone_number', $phoneNumber)
           ->where('is_verified', false)
           ->where('expired_at', '<', Carbon::now()) // Sudah lewat masa expired
           ->update(['is_verified' => true]); // Mark as "finish/used" (walaupun technically expired)
    }

    /**
     * Resend OTP: hapus data tbl_otp lama untuk nomor+session ini, lalu kirim OTP baru via WhatsApp.
     *
     * @param string $phoneNumber
     * @param string $sessionId
     * @param int|null $userTempId
     * @param string $type
     * @return array
     */
    public function resend($phoneNumber, $sessionId, $userTempId = null, $type = 'registration')
    {
        // Hapus SEMUA OTP lama untuk nomor ini + type (agar data lama pasti terhapus, lalu buat & kirim baru)
        $deleted = Otp::where('phone_number', $phoneNumber)
            ->where('type', $type)
            ->delete();

        if ($deleted > 0) {
            Log::info('Resend OTP: deleted old OTP records', [
                'phone' => $phoneNumber,
                'type' => $type,
                'deleted' => $deleted,
            ]);
        }

        // Generate dan kirim OTP baru via WhatsApp
        return $this->generateAndSend($phoneNumber, $sessionId, $userTempId, $type);
    }

    /**
     * Sisa waktu cooldown kirim ulang (maks 1 menit). Jika OTP terakhir sudah kadaluarsa, return 0 agar bisa kirim ulang.
     *
     * @param string $phoneNumber
     * @return int Detik tersisa (0 = boleh kirim ulang)
     */
    public function getRemainingCooldown($phoneNumber)
    {
        $maxCooldown = 60; // 1 menit
        $lastOtp = Otp::where('phone_number', $phoneNumber)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$lastOtp) {
            return 0;
        }

        // Jika OTP terakhir sudah kadaluarsa, tidak usah cooldown — user boleh kirim ulang
        $expiredAt = $lastOtp->expired_at instanceof \Carbon\Carbon
            ? $lastOtp->expired_at
            : Carbon::parse($lastOtp->expired_at);
        if (Carbon::now()->greaterThan($expiredAt)) {
            return 0;
        }

        $secondsSinceLastOtp = Carbon::now()->diffInSeconds($lastOtp->created_at);
        $remaining = min($this->cooldownSeconds, $maxCooldown) - $secondsSinceLastOtp;

        return min(max(0, (int) $remaining), $maxCooldown);
    }
}
