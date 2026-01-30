# 📱 Panduan Setup Fonnte API untuk OTP WhatsApp

## Apa itu Fonnte?

Fonnte adalah layanan penyedia API WhatsApp Indonesia yang memungkinkan Anda mengirim pesan WhatsApp secara otomatis menggunakan API.

## 🚀 Cara Daftar Fonnte

### 1. Buka Website Fonnte

Kunjungi: [https://fonnte.com](https://fonnte.com)

### 2. Registrasi Akun

1. Klik tombol **"Daftar"** atau **"Register"**
2. Isi data:
   - Email
   - Password
   - Nomor WhatsApp
3. Verifikasi email Anda
4. Login ke dashboard

### 3. Connect WhatsApp

1. Di dashboard, klik **"Connect Device"** atau **"Tambah Device"**
2. Scan QR Code menggunakan WhatsApp Web di HP Anda:
   - Buka WhatsApp di HP
   - Tap menu (3 titik) → **"Linked Devices"** / **"Perangkat Tertaut"**
   - Tap **"Link a Device"** / **"Tautkan Perangkat"**
   - Scan QR Code yang muncul di Fonnte
3. WhatsApp Anda sekarang terhubung dengan Fonnte

### 4. Dapatkan API Key

1. Di dashboard Fonnte, buka menu **"Setting"** atau **"Pengaturan"**
2. Cari bagian **"API Key"** atau **"Token"**
3. Copy API Key Anda
4. Paste ke file `.env` di project Laravel:

```env
FONNTE_API_KEY=paste_api_key_disini
```

## 💰 Paket Fonnte

### Paket Gratis (Trial)
- **Harga**: Gratis
- **Kuota**: 100 pesan/bulan
- **Fitur**:
  - Kirim pesan teks
  - Kirim ke multiple nomor
  - API access
- **Cocok untuk**: Testing dan development

### Paket Berbayar

#### Paket Starter - Rp 50.000/bulan
- Unlimited pesan teks
- 1 device WhatsApp
- API akses penuh
- Support via chat

#### Paket Pro - Rp 150.000/bulan
- Unlimited pesan teks
- Kirim gambar/video/dokumen
- Multiple device (3 device)
- Auto reply
- Webhook support
- Priority support

#### Paket Business - Rp 300.000/bulan
- Semua fitur Pro
- 10 device
- Dedicated server
- Custom domain
- 24/7 priority support

## 🔧 Konfigurasi API

### Endpoint API

```
https://api.fonnte.com/send
```

### Headers Required

```
Authorization: YOUR_API_KEY
```

### Request Format (POST)

```json
{
  "target": "628123456789",
  "message": "Kode OTP Anda: 123456",
  "countryCode": "62"
}
```

### Response Success

```json
{
  "status": true,
  "message": "Message sent successfully",
  "data": {
    "id": "MESSAGE_ID",
    "target": "628123456789",
    "status": "sent"
  }
}
```

## 🧪 Testing API Fonnte

### Menggunakan Postman/Insomnia

1. Buat request baru dengan method **POST**
2. URL: `https://api.fonnte.com/send`
3. Headers:
   ```
   Authorization: YOUR_API_KEY
   Content-Type: application/json
   ```
4. Body (JSON):
   ```json
   {
     "target": "628123456789",
     "message": "Test pesan dari Fonnte API",
     "countryCode": "62"
   }
   ```
5. Send request

### Menggunakan cURL

```bash
curl -X POST https://api.fonnte.com/send \
  -H "Authorization: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "target": "628123456789",
    "message": "Test pesan dari Fonnte API",
    "countryCode": "62"
  }'
```

## 📝 Tips Penggunaan

### 1. Format Nomor WhatsApp

Gunakan format internasional tanpa tanda +:
- ✅ Benar: `628123456789`
- ❌ Salah: `+628123456789`
- ❌ Salah: `08123456789`

### 2. Rate Limiting

- Paket gratis: Max 100 pesan/bulan
- Paket berbayar: Unlimited
- Jeda antar pesan: Min 1 detik (recommended)

### 3. Monitoring

1. Cek status device di dashboard:
   - Online/Offline
   - Last activity
   - Message quota

2. Cek logs di Laravel:
   ```bash
   tail -f storage/logs/laravel.log
   ```

### 4. Backup Device

Jika device disconnect:
1. Login ke Fonnte dashboard
2. Reconnect device dengan scan QR ulang
3. API Key tetap sama, tidak perlu update

## 🐛 Troubleshooting

### Device Disconnected

**Penyebab**:
- HP mati/internet mati
- WhatsApp logout
- QR expired

**Solusi**:
1. Pastikan HP online
2. Scan QR ulang di dashboard Fonnte
3. Jangan logout WhatsApp Web

### API Error: "Invalid Token"

**Penyebab**: API Key salah atau expired

**Solusi**:
1. Cek API Key di dashboard Fonnte
2. Copy ulang dan paste ke `.env`
3. Restart Laravel server: `php artisan serve`

### Pesan Tidak Terkirim

**Penyebab**:
- Kuota habis (paket gratis)
- Nomor tidak terdaftar di WhatsApp
- Device offline

**Solusi**:
1. Cek sisa kuota di dashboard
2. Verifikasi nomor tujuan aktif di WhatsApp
3. Pastikan device online di dashboard

### Rate Limit Exceeded

**Penyebab**: Terlalu banyak request dalam waktu singkat

**Solusi**:
1. Tambahkan delay antar request
2. Upgrade ke paket berbayar
3. Implementasi queue system di Laravel

## 🔐 Keamanan

### 1. Jangan Expose API Key

❌ **JANGAN** commit API Key ke Git:
```env
# .env (sudah di .gitignore)
FONNTE_API_KEY=secret_key_here
```

✅ **GUNAKAN** environment variables:
```php
$apiKey = config('services.fonnte.api_key');
```

### 2. Validasi Input

Selalu validasi nomor tujuan:
```php
$validator = Validator::make($request->all(), [
    'phone_number' => 'required|regex:/^(08|\+628|628)[0-9]{8,12}$/',
]);
```

### 3. Rate Limiting

Implementasikan cooldown untuk mencegah spam:
```php
// Sudah diimplementasi di OtpService
protected $cooldownSeconds = 60;
```

## 📊 Monitoring & Analytics

### Dashboard Fonnte

Fitur yang tersedia:
- Total pesan terkirim
- Success rate
- Device status
- Monthly quota usage
- Message history
- Failed messages log

### Laravel Logs

Check logs untuk debugging:
```bash
tail -f storage/logs/laravel.log | grep "OTP"
```

## 🎯 Best Practices

1. **Gunakan Queue** untuk kirim pesan (optional tapi recommended)
   ```bash
   php artisan queue:work
   ```

2. **Implement Retry Logic** jika gagal kirim
   ```php
   retry(3, function () use ($phone, $otp) {
       return $this->whatsAppService->sendOTP($phone, $otp);
   }, 2000);
   ```

3. **Log Semua Activity**
   ```php
   Log::info('OTP sent', ['phone' => $phone, 'status' => $result]);
   ```

4. **Handle Errors Gracefully**
   ```php
   try {
       $this->whatsAppService->sendOTP($phone, $otp);
   } catch (Exception $e) {
       Log::error('Failed to send OTP: ' . $e->getMessage());
       return back()->with('error', 'Gagal mengirim OTP. Coba lagi.');
   }
   ```

## 📞 Support Fonnte

- **Website**: [https://fonnte.com](https://fonnte.com)
- **WhatsApp**: Tersedia di dashboard setelah login
- **Email**: support@fonnte.com
- **Documentation**: [https://docs.fonnte.com](https://docs.fonnte.com)

## 🔄 Alternatif Fonnte

Jika Fonnte tidak cocok, alternatif lain:

1. **Wablas** - Provider lokal Indonesia
2. **WooWA** - Provider lokal dengan harga kompetitif
3. **Twilio** - Provider internasional (lebih mahal)
4. **Vonage (Nexmo)** - Provider internasional

---

**Selamat menggunakan Fonnte! 🚀**

Jika ada pertanyaan, silakan buka issue di repository atau hubungi support Fonnte.
