# 📱 Integrasi Fonnte WhatsApp untuk Notifikasi Transaksi – Koperasi Majakara

Dokumentasi ini menjelaskan **cara mengintegrasikan Fonnte WhatsApp API** dengan sistem koperasi Anda, dari **OTP (yang sudah berjalan)** hingga **notifikasi transaksi** berupa struk yang dikirim ke WhatsApp nasabah setiap kali ada transaksi tabungan (setor/tarik).

---

## 📋 Daftar Isi

1. [Ringkasan: Apa yang Sudah Ada vs Yang Akan Ditambah](#1-ringkasan)
2. [Alur Notifikasi: Pengajuan Setoran TF (Pending → Disetujui → WA)](#2-alur-notifikasi-pengajuan-setoran-tf)
3. [Arsitektur Integrasi Fonnte di Proyek Ini](#3-arsitektur-integrasi-fonnte-di-proyek-ini)
4. [Cara Kerja Fonnte API (Singkat)](#4-cara-kerja-fonnte-api)
5. [Langkah Integrasi: Notifikasi Transaksi (Struk)](#5-langkah-integrasi-notifikasi-transaksi)
6. [Contoh Kode: Perluasan WhatsAppService](#6-contoh-kode-perluasan-whatsappservice)
7. [Titik Pemasangan di Controller](#7-titik-pemasangan-di-controller)
8. [Format Struk (Teks) dan Opsi PDF](#8-format-struk-dan-opsi-pdf)
9. [Konfigurasi & Environment](#9-konfigurasi--environment)
10. [Best Practice: Queue, Error Handling, Log](#10-best-practice)
11. [Troubleshooting & Referensi](#11-troubleshooting--referensi)

---

## 1. Ringkasan: Apa yang Sudah Ada vs Yang Akan Ditambah

| Fitur | Status | Keterangan |
|-------|--------|------------|
| **OTP via WhatsApp** | ✅ Sudah jalan | Menggunakan `WhatsAppService::sendOTP()` + Fonnte API |
| **Notifikasi transaksi (struk WA)** | 🎯 Akan ditambah | Setiap transaksi tabungan (setor/tarik) → kirim struk ke WA nasabah |

**Alur yang diinginkan:**

- Nasabah melakukan transaksi (setor/tarik) → transaksi tercatat di sistem → **notifikasi WhatsApp** dikirim ke nomor nasabah berisi **struk transaksi** (bisa teks saja, atau teks + lampiran PDF yang bisa di-download).

Integrasi tetap memakai **Fonnte** dan **service yang sama** (`WhatsAppService`), hanya ditambah method untuk kirim pesan umum dan method khusus struk transaksi.

---

## 2. Alur Notifikasi: Pengajuan Setoran TF (Pending → Disetujui → WA)

Ini alur lengkap dari **pengajuan setoran TF oleh nasabah** sampai **notifikasi WhatsApp “transaksi berhasil”** terkirim ke nomor nasabah.

### 2.1 Ringkasan alur

```
Nasabah ajukan setoran TF  →  Status PENDING  →  Admin setujui  →  Sistem ambil nomor HP  →  Kirim WA "Transaksi berhasil"
```

### 2.2 Step-by-step

| Step | Pelaku | Yang terjadi |
|------|--------|------------------|
| **1** | Nasabah | Nasabah melakukan **pengajuan setoran** (transfer/TF) lewat aplikasi. Data masuk ke tabel pengajuan (mis. `pengajuan_tabungan`). |
| **2** | Sistem | Status pengajuan = **PENDING** (belum ada transaksi resmi, belum ada notifikasi WA). |
| **3** | Admin | Admin membuka menu pengajuan setoran, memeriksa data, lalu klik **“Setujui”** (approve). |
| **4** | Sistem | Setelah validasi nominal dll, sistem: (a) membuat **satu record transaksi** (`TransTabungan`), (b) mengubah status pengajuan menjadi **Disetujui**. |
| **5** | Sistem | Sistem **otomatis mengambil nomor telepon nasabah** dari data yang terkait transaksi tersebut (relasi: transaksi → nasabah → user → `nomor_hp`). |
| **6** | Sistem | Sistem memanggil **Fonnte** (via `WhatsAppService::sendTransactionReceipt($transaksi)`): mengirim pesan WA ke nomor tersebut dengan isi **“transaksi berhasil”** + ringkasan struk (jenis, nominal, tanggal, ID transaksi, dll). |
| **7** | Nasabah | Nasabah menerima **notifikasi WhatsApp** di HP-nya bahwa setoran telah diproses dan berhasil. |

Jadi: **notifikasi WA hanya dikirim ketika pengajuan setoran TF sudah disetujui oleh admin**, bukan saat masih pending. Nomor HP diambil otomatis dari data nasabah yang punya transaksi tersebut.

### 2.3 Diagram alur (setoran TF)

```
┌──────────────────┐     ┌──────────────────┐     ┌──────────────────┐
│     NASABAH      │     │      SISTEM     │     │      ADMIN      │
└────────┬─────────┘     └────────┬─────────┘     └────────┬─────────┘
         │                        │                        │
         │ 1. Ajukan setoran TF   │                        │
         │ ──────────────────────►│                        │
         │                        │ 2. Status = PENDING    │
         │                        │    (belum kirim WA)    │
         │                        │                        │
         │                        │ 3. Admin buka &        │
         │                        │    klik "Setujui"      │
         │                        │◄───────────────────────│
         │                        │                        │
         │                        │ 4. Buat TransTabungan  │
         │                        │    Update status       │
         │                        │    pengajuan = 2       │
         │                        │                        │
         │                        │ 5. Ambil nomor HP     │
         │                        │    nasabah (user)      │
         │                        │                        │
         │                        │ 6. Kirim WA via Fonnte │
         │                        │    (struk transaksi)   │
         │                        │ ──────────► Fonnte     │
         │                        │                        │
         │ 7. Terima WA:         │                        │
         │    "Transaksi berhasil"│                        │
         │◄──────────────────────│                        │
         │    (struk)             │                        │
```

### 2.4 Hal penting

- **Pending = belum ada notifikasi.** Jangan kirim WA saat nasabah baru submit pengajuan; kirim hanya setelah admin approve dan transaksi benar-benar tercatat.
- **Nomor HP** diambil dari `transaksi → nasabah → user → nomor_hp`. Pastikan data nasabah punya relasi ke user dan `nomor_hp` terisi.
- Alur yang **sama** bisa dipakai untuk **pengajuan penarikan** (transfer): setelah admin approve penarikan dan transaksi dibuat, sistem ambil nomor HP nasabah dan kirim WA bahwa transaksi penarikan berhasil.

---

## 3. Arsitektur Integrasi Fonnte di Proyek Ini

```
┌─────────────────────────────────────────────────────────────────┐
│                     Aplikasi Laravel (Koperasi)                   │
├─────────────────────────────────────────────────────────────────┤
│  Controllers (Admin/Tabungan, dll)                                │
│       │                                                           │
│       ▼                                                           │
│  WhatsAppService (app/Services/WhatsAppService.php)               │
│       │  • sendOTP(phone, otp)        ← sudah ada (OTP)            │
│       │  • sendMessage(phone, msg)    ← tambah: pesan umum         │
│       │  • sendTransactionReceipt(transaksi)  ← tambah: struk     │
│       │  • (opsional) sendDocument(phone, url/file, caption)      │
│       ▼                                                           │
│  HTTP POST → https://api.fonnte.com/send                          │
│       Header: Authorization: FONNTE_API_KEY                       │
│       Body: target, message, countryCode, [url/file]              │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                    Fonnte (api.fonnte.com)                        │
│  • Menerima request kirim pesan / file                            │
│  • Mengirim ke WhatsApp nomor tujuan (device terhubung Fonnte)    │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                    WhatsApp Nasabah                               │
│  • OTP (sudah)                                                    │
│  • Notifikasi struk transaksi (setor/tarik)                       │
└─────────────────────────────────────────────────────────────────┘
```

- **OTP**: sudah memakai `WhatsAppService::sendOTP()` → Fonnte → WA.
- **Notifikasi transaksi**: nanti memakai method baru di `WhatsAppService` (misalnya `sendMessage` + `sendTransactionReceipt`) yang dipanggil setelah transaksi berhasil dibuat (setelah approve setor/tarik, atau setelah input transaksi manual).

---

## 4. Cara Kerja Fonnte API (Singkat)

- **Endpoint**: `POST https://api.fonnte.com/send`
- **Header**: `Authorization: YOUR_FONNTE_API_KEY` (tanpa prefix "Bearer")
- **Body (form/json)**:
  - `target` (wajib): nomor tujuan, format `08xxx` atau `628xxx` (Fonnte bisa pakai `countryCode: 62` untuk konversi `08` → `62`)
  - `message`: isi pesan (teks)
  - `countryCode`: `62` untuk Indonesia
  - **Lampiran** (opsional, tergantung paket Fonnte):
    - `url`: URL publik ke file (gambar/PDF/dokumen) – untuk struk PDF
    - `file`: upload file dari server (binary)
    - `filename`: nama file yang terlihat di WA (berguna untuk PDF/dokumen)

Response sukses contoh:

```json
{
  "status": true,
  "detail": "success! message in queue",
  "target": ["628xxxxxxxxxx"]
}
```

Detail parameter lengkap dan limit file (ukuran, format) bisa dilihat di: [Fonnte – Sending API Messages](https://docs.fonnte.com/api-send-message/) dan [File limitation](https://docs.fonnte.com/file-limitation/).

---

## 5. Langkah Integrasi: Notifikasi Transaksi (Struk)

Secara garis besar:

1. **Perluas `WhatsAppService`**  
   - Tambah method **generic** `sendMessage($phoneNumber, $message)` yang memanggil Fonnte dengan `target` + `message` (sama seperti OTP, tanpa template OTP).  
   - Tambah method **khusus struk** `sendTransactionReceipt($transaksi)` yang:  
     - Mengambil data transaksi + nasabah (nama, nominal, jenis setor/tarik, tanggal, dll).  
     - Membentuk teks struk (lihat contoh di bawah).  
     - Mengambil nomor HP dari `nasabah->user->nomor_hp`.  
     - Memanggil `sendMessage(nomor, teksStruk)` (dan kelak bisa ditambah kirim PDF jika ada).

2. **Panggil notifikasi di tempat transaksi dibuat**  
   - Setelah `TransTabungan::create(...)` dan commit sukses di:  
     - `approveSetor`  
     - `approveTarik`  
     - Transaksi manual (jika ada)  
     - Dan titik lain yang membuat transaksi tabungan  
   - Panggil `WhatsAppService::sendTransactionReceipt($transaksi)` (atau lewat job/queue, lebih disarankan).

3. **Struk**  
   - Versi minimal: **pesan teks** berisi ringkasan transaksi (seperti struk).  
   - Opsional lanjutan: generate **PDF struk** (misalnya dengan DomPDF / Laravel), simpan sementara atau di storage, lalu kirim ke Fonnte pakai `url` (link publik) atau `file` (upload), sehingga nasabah bisa “mengunduh” struk dari WA.

Dengan ini, Fonnte terintegrasi ke proyek koperasi untuk **OTP** (sudah) dan **notifikasi transaksi (struk)**.

---

## 6. Contoh Kode: Perluasan WhatsAppService

Berikut contoh perluasan `app/Services/WhatsAppService.php` (tetap pakai `formatPhoneNumber` dan konfigurasi Fonnte yang sudah ada).

### 6.1 Method generic: kirim pesan teks

```php
/**
 * Kirim pesan teks ke WhatsApp via Fonnte (untuk notifikasi umum / struk teks).
 *
 * @param string $phoneNumber Nomor tujuan (08xxx atau 628xxx)
 * @param string $message Isi pesan
 * @return array ['success' => bool, 'message' => string, 'data' => array|null]
 */
public function sendMessage(string $phoneNumber, string $message): array
{
    try {
        if (empty($this->apiKey)) {
            Log::error('Fonnte API key belum di-set.');
            return [
                'success' => false,
                'message' => 'Server WhatsApp belum dikonfigurasi.',
                'data' => null,
            ];
        }

        $formattedPhone = $this->formatPhoneNumber($phoneNumber);
        $target = (substr($formattedPhone, 0, 2) === '62')
            ? '0' . substr($formattedPhone, 2)
            : $formattedPhone;

        $response = Http::withHeaders(['Authorization' => $this->apiKey])
            ->timeout(30)
            ->post($this->apiUrl, [
                'target' => $target,
                'message' => $message,
                'countryCode' => '62',
            ]);

        $responseBody = $response->json() ?? [];
        $statusVal = $responseBody['status'] ?? $responseBody['success'] ?? false;
        $ok = ($statusVal === true || $statusVal === 1);

        if ($response->successful() && $ok) {
            return [
                'success' => true,
                'message' => 'Pesan berhasil dikirim ke WhatsApp',
                'data' => $responseBody,
            ];
        }

        return [
            'success' => false,
            'message' => $responseBody['message'] ?? $responseBody['reason'] ?? 'Gagal mengirim pesan',
            'data' => $responseBody,
        ];
    } catch (\Exception $e) {
        Log::error('WhatsApp sendMessage Error', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        return [
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            'data' => null,
        ];
    }
}
```

### 6.2 Method khusus: struk transaksi (teks)

```php
use App\Models\TransTabungan;

/**
 * Kirim notifikasi struk transaksi tabungan ke WhatsApp nasabah.
 * Dipanggil setelah transaksi berhasil dicatat (setor/tarik).
 *
 * @param TransTabungan $transaksi Model transaksi (dengan relasi nasabah.user, jnsTransaksi, jnsVia)
 * @return array ['success' => bool, 'message' => string]
 */
public function sendTransactionReceipt(TransTabungan $transaksi): array
{
    $transaksi->load(['nasabah.user', 'jnsTransaksi', 'jnsVia']);
    $user = $transaksi->nasabah?->user;
    if (!$user || empty($user->nomor_hp)) {
        Log::warning('Transaksi receipt: nasabah/user/nomor_hp tidak tersedia', ['transaksi_id' => $transaksi->id]);
        return ['success' => false, 'message' => 'Nomor WhatsApp nasabah tidak tersedia.'];
    }

    $nama = $user->nama ?? 'Nasabah';
    $jenis = $transaksi->jenis ?? ($transaksi->jnsTransaksi?->kode === 'STR' ? 'Setoran' : 'Penarikan');
    $nominal = number_format($transaksi->nominal ?? 0, 0, ',', '.');
    $tanggal = $transaksi->tgl_transaksi
        ? $transaksi->tgl_transaksi->format('d/m/Y H:i')
        : now()->format('d/m/Y H:i');
    $via = $transaksi->via ?? $transaksi->jnsVia?->nama ?? '-';
    $keterangan = $transaksi->keterangan ?? '-';

    $message = "━━━━━━━━━━━━━━━━━━━━\n";
    $message .= "📋 *STRUK TRANSAKSI TABUNGAN*\n";
    $message .= "   Koperasi Majakara\n";
    $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
    $message .= "Halo *{$nama}*,\n\n";
    $message .= "Transaksi *{$jenis}* Anda telah diproses:\n\n";
    $message .= "• Jenis   : {$jenis}\n";
    $message .= "• Nominal : Rp {$nominal}\n";
    $message .= "• Via     : {$via}\n";
    $message .= "• Tanggal : {$tanggal}\n";
    $message .= "• ID      : {$transaksi->id}\n";
    if ($keterangan !== '-') {
        $message .= "• Keterangan : {$keterangan}\n";
    }
    $message .= "\n━━━━━━━━━━━━━━━━━━━━\n";
    $message .= "Simpan pesan ini sebagai bukti transaksi.\n";
    $message .= "Terima kasih.\n";

    return $this->sendMessage($user->nomor_hp, $message);
}
```

- Struk sengaja pakai teks saja dulu agar tidak bergantung paket Fonnte yang support file.  
- Jika nanti ada PDF, Anda bisa tambah method terpisah (misalnya `sendDocument`) dan dari `sendTransactionReceipt` panggil `sendMessage` + `sendDocument` (atau hanya salah satu, sesuai kebutuhan).

---

## 7. Titik Pemasangan di Controller

Panggil notifikasi **setelah** transaksi berhasil dibuat dan `DB::commit()`, agar hanya kirim saat data sudah benar-benar tersimpan.

### 7.1 Approve Setoran (`TabunganController::approveSetor`)

Setelah `DB::commit();` dan sebelum `return redirect()->route(...)`:

```php
// Setelah DB::commit();
$transaksi = TransTabungan::find($idTransaksi);
if ($transaksi) {
    try {
        app(\App\Services\WhatsAppService::class)->sendTransactionReceipt($transaksi);
    } catch (\Throwable $e) {
        Log::error('Gagal kirim notifikasi WA setoran', [
            'transaksi_id' => $idTransaksi,
            'error' => $e->getMessage(),
        ]);
        // Jangan gagalkan redirect; transaksi sudah sukses
    }
}
return redirect()->route('admin.tabungan.pengajuan-setor')...
```

Catatan: `$idTransaksi` sudah Anda generate di method yang sama sebelum `TransTabungan::create(...)`.

### 7.2 Approve Penarikan (`TabunganController::approveTarik`)

Sama seperti setor: setelah transaksi dibuat dan commit, ambil model `TransTabungan` yang baru dibuat, lalu panggil `sendTransactionReceipt($transaksi)` (dengan try/catch dan log seperti di atas).

### 7.3 Transaksi manual / lainnya

Di semua tempat yang melakukan `TransTabungan::create(...)` (misalnya transaksi manual di admin, atau dari Janji Temu), setelah commit panggil:

```php
app(\App\Services\WhatsAppService::class)->sendTransactionReceipt($transaksi);
```

(dengan penanganan error yang sama).

Dengan begitu, setiap transaksi tabungan yang tercatat bisa mengirim notifikasi struk ke WA nasabah melalui Fonnte.

---

## 8. Format Struk (Teks) dan Opsi PDF

### 8.1 Struk teks (contoh yang dipakai di atas)

- Header: Judul “STRUK TRANSAKSI TABUNGAN”, nama koperasi.  
- Isi: Nama nasabah, jenis (Setoran/Penarikan), nominal, via, tanggal, ID transaksi, keterangan.  
- Footer: Pesan simpan sebagai bukti.

Anda bisa ubah format teks di `sendTransactionReceipt` sesuai branding (emoji, pemisah, dll).

### 8.2 Opsi lanjutan: struk PDF yang bisa di-download

- **Generate PDF** di Laravel (misalnya package `barryvdh/laravel-dompdf` atau `laravel/snapshots`), isi dengan data transaksi yang sama.  
- Simpan file sementara (atau di `storage/app/receipts/`) dan bisa:  
  - Expose via route sementara (signed URL) dan kirim ke Fonnte dengan parameter `url` + `filename`, atau  
  - Pakai parameter `file` Fonnte (upload binary) jika paket Fonnte Anda mendukung.  
- Batasan Fonnte: format dan ukuran file mengikuti [file limitation](https://docs.fonnte.com/file-limitation/) (misalnya PDF max 10MB tergantung paket).

Alur: setelah `sendTransactionReceipt` kirim teks struk, bisa ditambah satu panggilan `sendDocument($user->nomor_hp, $urlAtauPathPdf, 'Struk transaksi ' . $transaksi->id)` jika Anda sudah siap generate PDF.

---

## 9. Konfigurasi & Environment

Anda sudah memakai Fonnte untuk OTP, jadi konfigurasi yang dipakai sama:

- **`.env`**  
  - `FONNTE_API_KEY=...`  
  - `FONNTE_API_URL=https://api.fonnte.com/send`  
  - `FONNTE_SENDER_NUMBER=...` (opsional, tergantung kebutuhan)

- **`config/services.php`**  
  - Bagian `fonnte` sudah ada dan di-load oleh `WhatsAppService`.

Tidak perlu variabel baru khusus untuk notifikasi transaksi; cukup pastikan API key dan URL Fonnte valid dan device Fonnte online.

---

## 10. Best Practice: Queue, Error Handling, Log

- **Queue (disarankan)**  
  Agar response ke admin tidak menunggu Fonnte, buat Job Laravel yang menerima `TransTabungan::$id` (atau object), lalu di dalam job panggil `WhatsAppService::sendTransactionReceipt($transaksi)`. Setelah `DB::commit()` di controller, dispatch job tersebut. Jalankan worker: `php artisan queue:work`.

- **Error handling**  
  Jika kirim WA gagal, jangan gagalkan proses approve transaksi. Cukup log error (seperti contoh di atas) dan biarkan redirect sukses. Nasabah tetap bisa lihat transaksi di aplikasi/dashboard.

- **Log**  
  Log setiap kirim struk (success/fail) dan nomor tujuan (bisa di-mask) untuk audit dan debugging.

- **Rate / kuota**  
  Fonnte punya batas kuota dan rate; dengan queue + delay kecil antarpesan, risiko rate limit bisa dikurangi.

---

## 11. Troubleshooting & Referensi

- **Pesan tidak sampai**  
  Cek: device Fonnte online, API key benar, nomor HP format benar (08xxx / 62xxx), log Laravel dan response Fonnte.

- **OTP jalan, notifikasi tidak**  
  Pastikan memanggil `sendMessage` / `sendTransactionReceipt` dengan nomor yang sama formatnya seperti di OTP (`formatPhoneNumber` + `target` dengan `countryCode 62`).

- **Ingin kirim file/PDF**  
  Cek paket Fonnte (super/advanced/ultra) dan dokumentasi parameter `url` / `file` dan [file limitation](https://docs.fonnte.com/file-limitation/).

**Referensi resmi Fonnte:**

- Sending API: https://docs.fonnte.com/api-send-message/  
- File limitation: https://docs.fonnte.com/file-limitation/  
- Token/API Key: https://docs.fonnte.com/token-api-key/

---

**Ringkasan:** Fonnte sudah terintegrasi untuk OTP. Dengan menambah method `sendMessage` dan `sendTransactionReceipt` di `WhatsAppService`, serta memanggil `sendTransactionReceipt` setelah setiap pembuatan transaksi tabungan (approve setor/tarik dan transaksi manual), sistem koperasi Anda bisa mengirim notifikasi struk transaksi ke WhatsApp nasabah. Struk tahap pertama berupa teks; tahap lanjutan bisa ditambah PDF yang bisa di-download via Fonnte jika paket Anda mendukung pengiriman file.
