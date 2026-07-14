<?php

namespace App\Services;

use App\Models\Otp;
use App\Models\UserTemp;
use App\Models\MasterDefaultOtp;
use App\Models\LogDefaultOtpUsage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class OtpService
{
    protected $emailService;
    protected $otpLength;
    protected $expiryMinutes;
    protected $maxAttempts;
    protected $cooldownSeconds;

    public function __construct(EmailService $emailService)
    {
        $this->emailService    = $emailService;
        $this->otpLength       = (int) config('services.otp.length', 6);
        $this->expiryMinutes   = (int) config('services.otp.expiry_minutes', 5);
        $this->maxAttempts     = (int) config('services.otp.max_attempts', 3);
        $this->cooldownSeconds = min(60, (int) config('services.otp.cooldown_seconds', 60)); // max 1 menit
    }

    /**
     * Generate dan kirim OTP ke Email
     * 
     * @param string      $phoneNumber Nomor telepon (digunakan untuk rate-limit & lookup di tbl_otp)
     * @param string      $sessionId   Session ID registrasi / transaksi
     * @param int|null    $userTempId  ID dari users / users_temp (optional)
     * @param string      $type        Tipe OTP (registration, transaction, login, pin, password_reset)
     * @param string|null $email       Alamat email tujuan. Jika null, akan di-resolve dari User::find($userTempId)
     * @return array ['success' => bool, 'message' => string, 'otp' => Otp|null]
     */
    public function generateAndSend($phoneNumber, $sessionId, $userTempId = null, $type = 'registration', $email = null)
    {
        try {
            // Resolve email jika tidak diberikan
            if (!$email && $userTempId) {
                $user = User::find($userTempId);
                if ($user && $user->email) {
                    $email = $user->email;
                }
            }

            if (empty($email)) {
                Log::error('OtpService: email tidak tersedia untuk kirim OTP', [
                    'phone'       => $phoneNumber,
                    'user_id'     => $userTempId,
                    'type'        => $type,
                    'session_id'  => $sessionId,
                ]);
                return [
                    'success' => false,
                    'message' => 'Alamat email tidak ditemukan. Silakan hubungi admin.',
                    'otp'     => null,
                ];
            }

            // Bersihkan OTP yang sudah expired agar tidak menumpuk statusnya
            $this->cleanUpExpired($phoneNumber);

            // Cek rate limiting - cooldown 60 detik
            if (!$this->checkCooldown($phoneNumber)) {
                Log::warning('OTP cooldown active', ['phone' => $phoneNumber]);
                return [
                    'success' => false,
                    'message' => 'Mohon tunggu ' . $this->cooldownSeconds . ' detik sebelum meminta OTP lagi.',
                    'otp'     => null,
                ];
            }

            // Cek rate limiting - maksimal attempts
            if (!$this->checkRateLimit($phoneNumber)) {
                Log::warning('OTP rate limit exceeded', ['phone' => $phoneNumber]);
                return [
                    'success' => false,
                    'message' => 'Terlalu banyak permintaan OTP. Silakan coba lagi dalam 15 menit.',
                    'otp'     => null,
                ];
            }

            // Generate OTP code (6 digit)
            $otpCode = $this->generateOtpCode();

            // Set expired time
            $expiredAt = Carbon::now()->addMinutes($this->expiryMinutes);

            Log::info('Generating OTP', [
                'phone'      => $phoneNumber,
                'email'      => $email,
                'session_id' => $sessionId,
                'type'       => $type,
                'expired_at' => $expiredAt->toDateTimeString(),
            ]);

            // Simpan OTP ke database
            $otp = Otp::create([
                'user_id'      => $userTempId, // Bisa null untuk registration
                'otp_code'     => $otpCode,
                'phone_number' => $phoneNumber,
                'session_id'   => $sessionId,
                'type'         => $type,
                'channel'      => 'email',
                'expired_at'   => $expiredAt,
                'is_verified'  => 0,
                'created_at'   => now(),
            ]);

            Log::info('OTP saved to database', ['otp_id' => $otp->id]);

            // Kirim OTP via Email
            $sendResult = $this->emailService->sendOTP($email, $otpCode);

            if ($sendResult['success']) {
                Log::info('OTP sent successfully via Email', [
                    'otp_id' => $otp->id,
                    'email'  => $email,
                ]);

                return [
                    'success' => true,
                    'message' => 'Kode OTP telah dikirim ke email Anda. Silakan cek kotak masuk.',
                    'otp'     => $otp,
                ];
            } else {
                // Jika gagal kirim, hapus OTP dari database
                $otp->delete();

                Log::error('Failed to send OTP via Email', [
                    'email' => $email,
                    'error' => $sendResult['message'],
                ]);

                return [
                    'success' => false,
                    'message' => 'Gagal mengirim OTP: ' . $sendResult['message'],
                    'otp'     => null,
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
                'otp'     => null,
            ];
        }
    }

    /**
     * Verifikasi OTP
     * 
     * @param string $otpCode    Kode OTP yang diinput user
     * @param string $phoneNumber Nomor telepon
     * @param string $sessionId   Session ID
     * @return array ['success' => bool, 'message' => string]
     */
    public function verify($otpCode, $phoneNumber, $sessionId)
    {
        try {
            Log::info('Verifying OTP', [
                'phone'      => $phoneNumber,
                'session_id' => $sessionId,
            ]);

            // Check if default OTP exists and matches
            $masterOtp = MasterDefaultOtp::first();
            if ($masterOtp && Hash::check($otpCode, $masterOtp->otp_code_hashed)) {
                Log::info('Verified via Default Master OTP', [
                    'phone'      => $phoneNumber,
                    'session_id' => $sessionId,
                ]);

                // Increment used count
                $masterOtp->increment('used');

                // Try to find matching user in users table
                $digits = preg_replace('/[^0-9]/', '', $phoneNumber);
                if (str_starts_with($digits, '62') && strlen($digits) > 10) {
                    $digits = '0' . substr($digits, 2);
                }
                $user = User::where('nomor_hp', $digits)->first();

                // Determine the verification type
                $type = 'unknown';
                if ($sessionId) {
                    if (str_contains($sessionId, 'pwd-reset-guest')) {
                        $type = 'password_reset';
                    } elseif (str_contains($sessionId, 'pin-lupa-reset')) {
                        $type = 'pin';
                    } else {
                        // Lookup last OTP record to infer type
                        $lastOtp = Otp::where('phone_number', $phoneNumber)
                            ->where('session_id', $sessionId)
                            ->orderBy('created_at', 'desc')
                            ->first();
                        if ($lastOtp) {
                            $type = $lastOtp->type;
                        } else {
                            $type = 'registration'; // default fallback
                        }
                    }
                }

                // Log the usage
                LogDefaultOtpUsage::create([
                    'user_id'      => $user ? $user->id : null,
                    'phone_number' => $phoneNumber,
                    'session_id'   => $sessionId,
                    'type'         => $type,
                ]);

                return [
                    'success' => true,
                    'message' => 'Kode OTP berhasil diverifikasi.',
                ];
            }

            // Cari OTP yang valid
            $otp = Otp::where('otp_code', $otpCode)
                ->where('phone_number', $phoneNumber)
                ->where('session_id', $sessionId)
                ->where('is_verified', false)
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$otp) {
                Log::warning('OTP not found or already used', [
                    'phone'      => $phoneNumber,
                    'session_id' => $sessionId,
                ]);

                return [
                    'success' => false,
                    'message' => 'Kode OTP tidak valid atau sudah digunakan.',
                ];
            }

            // Cek apakah OTP sudah expired
            $expiredAt = $otp->expired_at instanceof \Carbon\Carbon
                ? $otp->expired_at
                : Carbon::parse($otp->expired_at);
            if (Carbon::now()->greaterThan($expiredAt)) {
                Log::warning('OTP expired', [
                    'otp_id'     => $otp->id,
                    'expired_at' => $expiredAt->toDateTimeString(),
                    'now'        => Carbon::now()->toDateTimeString(),
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

        $secondsSinceLastOtp = abs(Carbon::now()->diffInSeconds($lastOtp->created_at));

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
     * Resend OTP: hapus data tbl_otp lama untuk nomor+session ini, lalu kirim OTP baru via Email.
     *
     * @param string      $phoneNumber
     * @param string      $sessionId
     * @param int|null    $userTempId
     * @param string      $type
     * @param string|null $email
     * @return array
     */
    public function resend($phoneNumber, $sessionId, $userTempId = null, $type = 'registration', $email = null)
    {
        // Hapus SEMUA OTP lama untuk nomor ini + type (agar data lama pasti terhapus, lalu buat & kirim baru)
        $deleted = Otp::where('phone_number', $phoneNumber)
            ->where('type', $type)
            ->delete();

        if ($deleted > 0) {
            Log::info('Resend OTP: deleted old OTP records', [
                'phone'   => $phoneNumber,
                'type'    => $type,
                'deleted' => $deleted,
            ]);
        }

        // Generate dan kirim OTP baru via Email
        return $this->generateAndSend($phoneNumber, $sessionId, $userTempId, $type, $email);
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
