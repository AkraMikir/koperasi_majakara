# 📝 Dokumentasi Sistem Registrasi

## 🎯 Overview

Sistem registrasi Koperasi Majakara menggunakan **3 langkah sederhana** untuk mendaftarkan nasabah baru. Semua data disimpan sementara di tabel `*_temp` sebelum dipindahkan ke tabel utama setelah proses selesai.

---

## 📋 Alur Registrasi

### **Step 1: Data Lengkap** 
Mengisi semua informasi yang diperlukan dalam satu form:

1. **Data Diri**
   - Nama lengkap
   - Email (unik, untuk login)
   - Nomor HP (untuk verifikasi OTP)
   - Password (minimal 8 karakter)
   - Foto profil (opsional)

2. **Detail Nasabah**
   - Nomor KK (16 digit)
   - Tempat & Tanggal lahir
   - Jenis kelamin
   - Alamat lengkap
   - Foto KTP & KK (opsional)

3. **Data Pekerjaan** (Opsional)
   - Pekerjaan
   - Penghasilan
   - Nama perusahaan
   - Nama bank

4. **Data Rekening Bank** (Opsional)
   - Nomor rekening (16 digit)
   - Nama pemilik rekening
   - Jenis bank (BCA, Mandiri, BNI, BRI)

5. **Data KTP dengan OCR**
   - Upload/Ambil foto KTP (dari kamera atau file)
   - **Fitur OCR**: Otomatis mengekstrak data dari foto KTP
   - NIK (16 digit)
   - Nama lengkap sesuai KTP
   - Tempat & Tanggal lahir
   - Alamat sesuai KTP
   - Jenis kelamin

6. **Kontak Darurat** (Opsional)
   - Nama lengkap
   - Hubungan (Suami/Istri, Orang Tua, Anak, Saudara, Lainnya)
   - Nomor telepon
   - Email
   - Alamat
   - Pekerjaan
   - NIK
   - Foto KTP

**Setelah Step 1 selesai**, data disimpan di tabel temp dan user diarahkan ke Step 2.

---

### **Step 2: Verifikasi OTP**
- OTP dikirim ke nomor WhatsApp yang didaftarkan
- User memasukkan kode OTP 6 digit
- Setelah OTP terverifikasi, lanjut ke Step 3

> ⚠️ **Catatan**: Fitur OTP sedang dalam pengembangan. Untuk sementara, masukkan kode OTP apa saja untuk melanjutkan.

---

### **Step 3: Buat PIN**
- User membuat PIN 6 digit
- PIN digunakan untuk keamanan transaksi
- Setelah PIN dibuat:
  - Data dipindahkan dari tabel `*_temp` ke tabel utama
  - User otomatis login
  - Diarahkan ke dashboard nasabah

---

## 🗄️ Struktur Database

### Tabel Temp (Data Sementara)
Data disimpan sementara di tabel-tabel berikut sebelum dipindahkan ke tabel utama:

1. **`users_temp`** - Data user sementara
2. **`tbl_nasabah_temp`** - Data nasabah sementara
3. **`tbl_pekerjaan_temp`** - Data pekerjaan sementara
4. **`tbl_data_rek_temp`** - Data rekening sementara
5. **`tbl_data_ktp_temp`** - Data KTP sementara
6. **`tbl_darurat_temp`** - Data kontak darurat sementara

### Tabel Utama (Data Final)
Setelah Step 3 selesai, data dipindahkan ke:

1. **`users`** - Data user aktif (dengan PIN)
2. **`tbl_nasabah`** - Data nasabah aktif
3. **`tbl_pekerjaan`** - Data pekerjaan aktif
4. **`tbl_data_rek`** - Data rekening aktif
5. **`tbl_data_ktp`** - Data KTP aktif
6. **`tbl_darurat`** - Data kontak darurat aktif

---

## 🔧 Teknologi & Fitur

### Framework & Tools
- **Laravel** - Backend framework
- **Blade** - Template engine
- **Tailwind CSS** - Styling

### Fitur Khusus
- ✅ **Multi-step form** dengan progress indicator
- ✅ **OCR (Optical Character Recognition)** untuk ekstrak data KTP otomatis
- ✅ **Kamera langsung** untuk mengambil foto KTP
- ✅ **Upload file** untuk foto KTP
- ✅ **Validasi real-time** pada setiap field
- ✅ **Session management** untuk menyimpan progress
- ✅ **Auto-login** setelah registrasi selesai

---

## 📁 File Penting

### Controller
- `app/Http/Controllers/Auth/RegisterController.php`
  - `showRegistrationForm()` - Menampilkan form registrasi
  - `register()` - Menangani submit form (3 step)
  - `processOcr()` - Memproses OCR untuk ekstrak data KTP

### View
- `resources/views/auth/register.blade.php` - Form registrasi lengkap

### Routes
```php
GET  /register              - Tampilkan form registrasi
POST /register              - Submit form registrasi
POST /register/ocr          - Proses OCR KTP
```

### Models
- `UserTemp` - Model untuk users_temp
- `NasabahTemp` - Model untuk tbl_nasabah_temp
- `PekerjaanTemp` - Model untuk tbl_pekerjaan_temp
- `DataRekTemp` - Model untuk tbl_data_rek_temp
- `DataKtpTemp` - Model untuk tbl_data_ktp_temp
- `DaruratTemp` - Model untuk tbl_darurat_temp

---

## 🔄 Flow Diagram

```
User → Step 1 (Isi Data) 
    → Simpan ke *_temp
    → Step 2 (Verifikasi OTP)
    → Step 3 (Buat PIN)
    → Pindahkan data dari *_temp ke tabel utama
    → Auto Login
    → Dashboard Nasabah
```

---

## ⚙️ Konfigurasi

### Session Management
Sistem menggunakan session Laravel untuk menyimpan:
- `register_session_id` - ID session registrasi
- `register_user_temp_id` - ID user temp
- `register_nasabah_temp_id` - ID nasabah temp
- `register_otp_verified` - Status verifikasi OTP

### File Upload
- Foto profil: `storage/app/public/profiles/`
- Foto KTP: `storage/app/public/ktp/`
- Foto KK: `storage/app/public/kk/`

---

## 🚀 Cara Menggunakan

### Untuk User (Nasabah)
1. Buka halaman `/register`
2. Isi semua data di Step 1 (bisa diisi bertahap, data otomatis tersimpan)
3. Klik "Simpan & Lanjutkan"
4. Verifikasi OTP di Step 2
5. Buat PIN di Step 3
6. Selesai! Akun aktif dan langsung login

### Untuk Developer
1. Pastikan semua migration sudah dijalankan
2. Pastikan storage link sudah dibuat: `php artisan storage:link`
3. Pastikan OCR service sudah dikonfigurasi (jika menggunakan)
4. Test flow registrasi dari Step 1 sampai Step 3

---

## 📝 Catatan Penting

1. **Email harus unik** - Tidak boleh duplikat dengan user yang sudah ada
2. **Password minimal 8 karakter** - Harus dikonfirmasi
3. **PIN 6 digit** - Hanya angka, digunakan untuk transaksi
4. **Data opsional** - Beberapa field bisa dikosongkan (pekerjaan, rekening, kontak darurat)
5. **OCR tidak wajib** - Jika OCR gagal, user bisa isi manual
6. **OTP sedang development** - Saat ini bisa skip dengan kode apa saja

---

## 🐛 Troubleshooting

### Data tidak tersimpan
- Cek koneksi database
- Cek validasi form (pastikan field required terisi)
- Cek session storage

### OCR tidak bekerja
- Pastikan file KTP jelas dan tidak blur
- Cek konfigurasi OCR service
- User bisa isi manual jika OCR gagal

### OTP tidak terkirim
- Fitur OTP sedang dalam pengembangan
- Untuk testing, masukkan kode OTP apa saja

### File upload gagal
- Pastikan folder storage writable
- Pastikan `php artisan storage:link` sudah dijalankan
- Cek ukuran file (max 5MB untuk KTP, 2MB untuk foto profil)

---

## 📞 Support

Jika ada pertanyaan atau masalah terkait sistem registrasi, hubungi tim development.

---

**Terakhir diperbarui**: 2025
