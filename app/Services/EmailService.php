<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class EmailService
{
    protected string $apiKey;
    protected string $mailboxResourceId;
    protected string $fromAddress;
    protected string $fromName;
    protected string $apiBase = 'https://api.mail.hostinger.com';

    public function __construct()
    {
        $this->apiKey            = config('services.hostinger_email.api_key', '');
        $this->mailboxResourceId = config('services.hostinger_email.mailbox_resource_id', 'ACfcce24a5a4159cb4284a002e888d');
        $this->fromAddress       = config('services.hostinger_email.from', 'koperasi@majakara.com');
        $this->fromName          = config('services.hostinger_email.name', 'Koperasi Majakara');
    }

    /**
     * Kirim OTP via Email (Hostinger REST API)
     *
     * @param string $email   Alamat email tujuan
     * @param string $otpCode Kode OTP 6 digit
     * @return array ['success' => bool, 'message' => string]
     */
    public function sendOTP(string $email, string $otpCode): array
    {
        try {
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                Log::error('EmailService: alamat email tidak valid', ['email' => $email]);
                return [
                    'success' => false,
                    'message' => 'Alamat email tidak valid. Silakan hubungi admin.',
                ];
            }

            $expiryMinutes = (int) config('services.otp.expiry_minutes', 5);
            $subject       = 'Kode OTP Koperasi Majakara';
            $htmlBody      = $this->buildOtpEmailHtml($otpCode, $expiryMinutes);

            Log::info('EmailService: mengirim OTP via Hostinger API', [
                'email' => $email,
                'from'  => $this->fromAddress,
            ]);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ])->post($this->apiBase . '/api/v1/mailboxes/' . $this->mailboxResourceId . '/send', [
                'from'    => $this->fromAddress,
                'to'      => [$email],
                'subject' => $subject,
                'html'    => $htmlBody,
            ]);

            if ($response->successful()) {
                Log::info('EmailService: OTP berhasil dikirim', [
                    'email'  => $email,
                    'status' => $response->status(),
                ]);
                return [
                    'success' => true,
                    'message' => 'Kode OTP berhasil dikirim ke email Anda.',
                ];
            } else {
                $body    = $response->json();
                $errMsg  = $body['message'] ?? $response->body();
                Log::error('EmailService: gagal kirim OTP via API', [
                    'email'   => $email,
                    'status'  => $response->status(),
                    'error'   => $errMsg,
                ]);
                return [
                    'success' => false,
                    'message' => 'Tidak dapat mengirim, harap coba lagi.',
                ];
            }
        } catch (\Exception $e) {
            Log::error('EmailService: exception saat kirim OTP', [
                'email' => $email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return [
                'success' => false,
                'message' => 'Tidak dapat mengirim, harap coba lagi.',
            ];
        }
    }

    /**
     * Kirim pesan bebas via Hostinger API
     *
     * @param string $email   Alamat email tujuan
     * @param string $subject Subjek email
     * @param string $body    Isi email (HTML)
     * @return array
     */
    public function sendMessage(string $email, string $subject, string $body): array
    {
        try {
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return ['success' => false, 'message' => 'Alamat email tidak valid.'];
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ])->post($this->apiBase . '/api/v1/mailboxes/' . $this->mailboxResourceId . '/send', [
                'from'    => $this->fromAddress,
                'to'      => [$email],
                'subject' => $subject,
                'html'    => $body,
            ]);

            if ($response->successful()) {
                return ['success' => true, 'message' => 'Email berhasil dikirim.'];
            } else {
                $errMsg = $response->json()['message'] ?? $response->body();
                return ['success' => false, 'message' => $errMsg];
            }
        } catch (\Exception $e) {
            Log::error('EmailService: gagal kirim pesan', [
                'email' => $email,
                'error' => $e->getMessage(),
            ]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Build HTML body untuk email OTP
     */
    protected function buildOtpEmailHtml(string $otpCode, int $expiryMinutes): string
    {
        $year = date('Y');
        return <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Koperasi Majakara</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: #f5f5f0; color: #333; line-height: 1.6; }
        .wrapper { width: 100%; padding: 40px 20px; background-color: #f5f5f0; }
        .container { max-width: 560px; margin: 0 auto; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,.10); }
        .header { background: linear-gradient(135deg, #674c1d 0%, #8b6f2f 60%, #d4af37 100%); padding: 40px 40px 32px; text-align: center; }
        .header h1 { color: #fff; font-size: 22px; font-weight: 700; margin-bottom: 4px; }
        .header p { color: rgba(255,255,255,.80); font-size: 13px; }
        .body { padding: 40px 40px 32px; }
        .greeting { font-size: 15px; color: #555; margin-bottom: 20px; }
        .otp-box { background: linear-gradient(135deg, #fdf8ee 0%, #fef3d0 100%); border: 2px solid #d4af37; border-radius: 12px; padding: 28px 20px; text-align: center; margin: 24px 0; }
        .otp-label { font-size: 12px; font-weight: 600; color: #8b6f2f; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 12px; }
        .otp-code { font-size: 44px; font-weight: 800; letter-spacing: 12px; color: #674c1d; font-family: 'Courier New', monospace; line-height: 1; padding-left: 12px; white-space: nowrap; word-break: keep-all; display: inline-block; -webkit-user-select: all; user-select: all; }
        .otp-expiry { margin-top: 14px; font-size: 13px; color: #8b6f2f; }
        .otp-expiry strong { color: #c0392b; }
        .info-box { background-color: #fff8f8; border-left: 4px solid #e74c3c; border-radius: 4px; padding: 14px 16px; margin-top: 20px; }
        .info-box p { font-size: 13px; color: #c0392b; }
        .divider { height: 1px; background: #ede8de; margin: 20px 0; }
        .footer { background-color: #f9f7f3; border-top: 1px solid #ede8de; padding: 24px 40px; text-align: center; }
        .footer p { font-size: 12px; color: #999; margin-bottom: 4px; }
        @media only screen and (max-width: 480px) {
            .wrapper { padding: 10px !important; }
            .header { padding: 24px 20px 20px !important; }
            .header h1 { font-size: 18px !important; }
            .body { padding: 24px 16px !important; }
            .otp-box { padding: 20px 10px !important; }
            .otp-code { font-size: 32px !important; letter-spacing: 6px !important; padding-left: 6px !important; }
            .greeting { font-size: 13px !important; }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <h1>Koperasi Majakara</h1>
                <p>Verifikasi Kode OTP</p>
            </div>
            <div class="body">
                <p class="greeting">Kami menerima permintaan verifikasi dari akun Anda. Gunakan kode OTP di bawah ini untuk melanjutkan proses.</p>
                <div class="otp-box">
                    <div class="otp-label">Kode OTP Anda</div>
                    <div class="otp-code">{$otpCode}</div>
                    <div style="margin-top: 16px;">
                        <span style="font-size: 12px; color: #8b6f2f; background-color: #fff9e6; border: 1px dashed #d4af37; padding: 6px 12px; border-radius: 6px; display: inline-block;">
                            💡 Klik 2x / tekan lama angka untuk menyalin cepat
                        </span>
                    </div>
                    <div class="otp-expiry">Kode ini berlaku selama <strong>{$expiryMinutes} menit</strong></div>
                </div>
                <div class="divider"></div>
                <div class="info-box">
                    <p>&#9888;&#65039; <strong>Jangan bagikan kode ini kepada siapapun</strong>, termasuk staff Koperasi Majakara. Kode OTP bersifat rahasia dan hanya untuk Anda.</p>
                </div>
                <div class="divider"></div>
                <p style="font-size:13px;color:#777;">Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini. Akun Anda tetap aman selama kode tidak digunakan.</p>
            </div>
            <div class="footer">
                <p><strong>Koperasi Majakara</strong></p>
                <p>Email ini dikirim secara otomatis, harap tidak membalas.</p>
                <p style="margin-top:8px;">&copy; {$year} Koperasi Majakara. Semua hak dilindungi.</p>
            </div>
        </div>
    </div>
</body>
</html>
HTML;
    }
}
