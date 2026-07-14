<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Koperasi Majakara</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, sans-serif;
            background-color: #f5f5f0;
            color: #333333;
            line-height: 1.6;
        }
        .wrapper {
            width: 100%;
            padding: 40px 20px;
            background-color: #f5f5f0;
        }
        .container {
            max-width: 560px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
        }
        /* Header */
        .header {
            background: linear-gradient(135deg, #674c1d 0%, #8b6f2f 60%, #d4af37 100%);
            padding: 40px 40px 32px;
            text-align: center;
        }
        .header-logo {
            width: 64px;
            height: 64px;
            background: rgba(255,255,255,0.18);
            border-radius: 50%;
            margin: 0 auto 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .header h1 {
            color: #ffffff;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 0.3px;
            margin-bottom: 4px;
        }
        .header p {
            color: rgba(255,255,255,0.80);
            font-size: 13px;
        }
        /* Body */
        .body {
            padding: 40px 40px 32px;
        }
        .greeting {
            font-size: 15px;
            color: #555555;
            margin-bottom: 20px;
        }
        /* OTP Box */
        .otp-box {
            background: linear-gradient(135deg, #fdf8ee 0%, #fef3d0 100%);
            border: 2px solid #d4af37;
            border-radius: 12px;
            padding: 28px 20px;
            text-align: center;
            margin: 24px 0;
        }
        .otp-label {
            font-size: 12px;
            font-weight: 600;
            color: #8b6f2f;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 12px;
        }
        .otp-code {
            font-size: 44px;
            font-weight: 800;
            letter-spacing: 12px;
            color: #674c1d;
            font-family: 'Courier New', Courier, monospace;
            line-height: 1;
            padding-left: 12px; /* offset letter-spacing */
            white-space: nowrap;
            word-break: keep-all;
            display: inline-block;
            -webkit-user-select: all;
            user-select: all;
        }
        .otp-expiry {
            margin-top: 14px;
            font-size: 13px;
            color: #8b6f2f;
        }
        .otp-expiry strong {
            color: #c0392b;
        }
        /* Info */
        .info-box {
            background-color: #fff8f8;
            border-left: 4px solid #e74c3c;
            border-radius: 4px;
            padding: 14px 16px;
            margin-top: 20px;
        }
        .info-box p {
            font-size: 13px;
            color: #c0392b;
        }
        /* Footer */
        .footer {
            background-color: #f9f7f3;
            border-top: 1px solid #ede8de;
            padding: 24px 40px;
            text-align: center;
        }
        .footer p {
            font-size: 12px;
            color: #999999;
            margin-bottom: 4px;
        }
        .footer a {
            color: #8b6f2f;
            text-decoration: none;
        }
        .divider {
            height: 1px;
            background: #ede8de;
            margin: 20px 0;
        }
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
            <!-- Header -->
            <div class="header">
                <div class="header-logo">
                    <!-- Lock icon -->
                    <svg width="32" height="32" fill="none" stroke="#ffffff" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0110 0v4"></path>
                    </svg>
                </div>
                <h1>Koperasi Majakara</h1>
                <p>Verifikasi Kode OTP</p>
            </div>

            <!-- Body -->
            <div class="body">
                <p class="greeting">
                    Kami menerima permintaan verifikasi dari akun Anda. Gunakan kode OTP di bawah ini untuk melanjutkan proses.
                </p>

                <div class="otp-box">
                    <div class="otp-label">Kode OTP Anda</div>
                    <div class="otp-code">{{ $otpCode }}</div>
                    <div style="margin-top: 16px;">
                        <span style="font-size: 12px; color: #8b6f2f; background-color: #fff9e6; border: 1px dashed #d4af37; padding: 6px 12px; border-radius: 6px; display: inline-block;">
                            💡 Klik 2x / tekan lama angka untuk menyalin cepat
                        </span>
                    </div>
                    <div class="otp-expiry">
                        Kode ini berlaku selama <strong>{{ $expiryMinutes }} menit</strong>
                    </div>
                </div>

                <div class="divider"></div>

                <div class="info-box">
                    <p>
                        ⚠️ <strong>Jangan bagikan kode ini kepada siapapun</strong>, termasuk staff Koperasi Majakara. Kode OTP bersifat rahasia dan hanya untuk Anda.
                    </p>
                </div>

                <div class="divider"></div>

                <p style="font-size: 13px; color: #777777;">
                    Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini. Akun Anda tetap aman selama kode tidak digunakan.
                </p>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p><strong>Koperasi Majakara</strong></p>
                <p>Email ini dikirim secara otomatis, harap tidak membalas.</p>
                <p style="margin-top: 8px;">
                    &copy; {{ date('Y') }} Koperasi Majakara. Semua hak dilindungi.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
