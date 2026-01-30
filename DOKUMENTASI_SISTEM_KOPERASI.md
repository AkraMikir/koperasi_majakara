# 📘 Dokumentasi Sistem Login & Registrasi Koperasi Majakara

**Status**: Production Ready (Verified)  
**Versi**: 2.0  
**Tanggal Update**: 30 Januari 2026

Dokumen ini merangkum secara teknis dan fungsional bagaimana sistem Autentikasi dan Registrasi Nasabah bekerja, mulai dari layer Frontend, Backend logic, hingga Database structure.

---

## 1. 🏗️ Arsitektur Database

Sistem ini menggunakan pendekatan **Two-Stage Registration** (Temporary → Permanent) untuk menjaga kebersihan data. Data sampah (registrasi tidak tuntas) tidak akan mengotori tabel utama user/nasabah.

### A. Tabel Utama
| Nama Tabel | Fungsi | Relasi |
| :--- | :--- | :--- |
| **`users`** | Menyimpan akun akses login (email, phone, password, role). | Parent Table |
| **`tbl_nasabah`** | Data profil lengkap nasabah (No KK, KTP, Foto, dll) yang sah. | `user_id` -> `users.id` |
| **`tbl_otp`** | Menyimpan kode OTP sementara untuk verifikasi. | `user_id` -> `users.id` (Nullable) |

### B. Tabel Sementara (Temporary)
| Nama Tabel | Fungsi | Karakteristik Penting |
| :--- | :--- | :--- |
| **`users_temp`** | Menampung data formulir registrasi yang **BELUM** selesai/verifikasi. | Tidak memiliki relasi FK ketat ke `users` |

### 🛠️ Perubahan Penting Database (Update Terbaru)
1.  **`tbl_otp.user_id`** sekarang **NULLABLE**. Ini memungkinkan pembuatan OTP untuk calon nasabah yang belum memiliki akun di tabel `users`.
2.  **`users.pin`**: Kolom hash PIN 6 digit untuk keamanan level 2.

---

## 2. 🔄 Alur Registrasi (Data Flow)

Alur registrasi dibagi menjadi 3 Tahap Utama. Berikut adalah perjalanan data "si calon nasabah":

### Step 1: Input Data & Dokumen (Temporary State)
**UI**: Form Wizard (Data Diri, Alamat, Upload KTP/KK, Selfie).  
**Backend**:
1.  User input data -> Controller `RegisterController@handleStep1`.
2.  Data validasi -> Disimpan/Update ke tabel **`users_temp`**.
3.  Foto diupload ke folder sementara.
4.  Tidak ada data masuk ke `users` atau `tbl_nasabah`.

### Step 2: Verifikasi OTP WhatsApp (Verification State)
**UI**: Talaman Konfirmasi No HP -> Loading Kirim OTP -> Form Input OTP.  
**Backend**:
1.  User klik "Kirim OTP".
2.  **`OtpService`** generate 6 digit kode.
3.  Simpan ke **`tbl_otp`** (dengan `user_id` NULL, session bound).
4.  Kirim ke WhatsApp via **Fonnte API**.
5.  User input kode -> `OtpService` verifikasi ke database.
6.  Jika valid -> Set session `register_otp_verified = true`.

### Step 3: Finalisasi & PIN (Migration State)
**UI**: Form Pembuatan PIN 6 Digit.  
**Backend**:
1.  User input PIN -> Controller `RegisterController@handleStep3Pin`.
2.  **DB Transaction (CRITICAL)**:
    -   Ambil data dari `users_temp`.
    -   Create User baru di tabel **`users`** (password & PIN di-hash).
    -   Create Profil di tabel **`tbl_nasabah`** (relasi ke User baru).
    -   Pindahkan file foto dari temp ke folder permanent.
    -   Hapus data di `users_temp` (Clean up).
    -   Hapus record OTP terkait.
3.  Auto Login user baru.
4.  Redirect ke Dashboard Nasabah.

---

## 3. 🔐 Alur Login & Security

Login menggunakan *Multi-Factor Authentication* sederhana (Password + PIN option).

### Flow Login
1.  **Halaman Login**: User input Email/No HP & Password.
2.  **Validasi Password**: Check hash password standard Laravel.
3.  **Cek Status**: Pastikan user aktif dan role benar.
4.  **Redirect**:
    -   Admin -> Dashboard Admin.
    -   Nasabah -> Dashboard Nasabah.

*(Catatan: Fitur PIN prompt opsional saat melakukan transaksi sensitif di dalam dashboard, bukan step login wajib untuk UX yang lebih cepat, namun bisa dikonfigurasi).*

---

## 4. 🧩 Integrasi Sistem (Under The Hood)

### A. WhatsApp Gateway (Fonnte)
-   **File**: `app/Services/WhatsAppService.php`
-   **Logic**: Menggunakan HTTP Client untuk POST request ke API Fonnte.
-   **Config**: `.env` (API Key & URL).
-   **Handling**: Dilengkapi `try-catch` dan logging.
-   **SSL**: Menggunakan certificate `cacert.pem` lokal untuk keamanan HTTPS.

### B. OTP System
-   **File**: `app/Services/OtpService.php`
-   **Logic**:
    -   Generate 6 angka random.
    -   **Rate Limiting**: Maks 3x request per 15 menit.
    -   **Cooldown**: Tunggu 60 detik sebelum kirim ulang.
    -   **Expiry**: Kode hangus dalam 5 menit.

---

## 5. 📱 Urutan Tampilan (User Interface Flow)

1.  **Landing Page** -> Klik "Daftar Anggota".
2.  **Register Step 1 (Multi-page form)**:
    -   Substep 1: Data Akun (Nama, NIK, HP, Email, Pass).
    -   Substep 2: Data Pribadi (TTL, Ibu Kandung).
    -   Substep 3: Alamat (Provinsi - Kelurahan api wilayah).
    -   Substep 4: Pekerjaan & Keuangan.
    -   Substep 5: Upload Dokumen (OCR auto-fill capability).
    -   Substep 6: Review Data & Submit.
3.  **Register Step 2 (Dual State)**:
    -   *State A*: Konfirmasi Nomor HP (Tampil No HP User).
    -   *State B*: Input Kode OTP (Countdown Timer berjalan).
4.  **Register Step 3**:
    -   Input PIN Numeric (6 digit).
    -   Input Konfirmasi PIN.
5.  **Dashboard Nasabah**: Tampilan utama setelah sukses.

---

## 6. ⚠️ Troubleshooting Guide Umum

| Masalah | Penyebab Umum | Solusi |
| :--- | :--- | :--- |
| **Gagal Kirim OTP** | Koneksi Fonnte / SSL Error. | Cek log, pastikan `cacert.pem` di php.ini benar. |
| **Error 500 saat Submit** | Database Constraint. | Cek migration, pastikan `user_id` di `tbl_otp` nullable (Sudah fixed). |
| **Carbon Error** | Format Tanggal/Jam. | Pastikan config OTP di `.env` sudah di-cast ke Integer di Service. |
| **Nomor HP 08xxxx** | Session hilang. | Controller sudah memprioritaskan session value untuk display. |

---

*Dibuat oleh Tim IT Koperasi Majakara (AI Assistant)*
