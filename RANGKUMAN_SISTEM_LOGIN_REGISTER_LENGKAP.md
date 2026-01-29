# RANGKUMAN SISTEM LOGIN & REGISTER KOPERASI MAJAKARA

> **Dibuat**: 30 Januari 2026  
> **Status**: Dokumentasi Lengkap & Analisis  
> **Tujuan**: Melanjutkan pengembangan sistem login dan register

---

## 📋 DAFTAR ISI

1. [Executive Summary](#executive-summary)
2. [Arsitektur Sistem](#arsitektur-sistem)
3. [Alur Registrasi Lengkap](#alur-registrasi-lengkap)
4. [Alur Login Lengkap](#alur-login-lengkap)
5. [Database Schema](#database-schema)
6. [Status Implementasi](#status-implementasi)
7. [Fitur yang Sudah Berjalan](#fitur-yang-sudah-berjalan)
8. [Fitur yang Belum Berjalan](#fitur-yang-belum-berjalan)
9. [Masalah & Bug](#masalah--bug)
10. [Rekomendasi Langkah Selanjutnya](#rekomendasi-langkah-selanjutnya)
11. [Rencana Perbaikan](#rencana-perbaikan)

---

## 📊 EXECUTIVE SUMMARY

### Status Sistem: **75% Selesai**

Sistem login dan register Koperasi Majakara menggunakan pendekatan **multi-step registration** dengan data sementara disimpan di tabel **temp**. Sistem ini dirancang agar admin dapat melakukan approval sebelum data nasabah aktif di sistem.

### Yang Sudah Berfungsi ✅:
- ✅ **Multi-step Registration Form** (6 langkah)
- ✅ **Data Storage di Temp Tables** (users_temp, nasabah_temp, dll)
- ✅ **Login System** dengan PIN Verification
- ✅ **File Upload** (KTP, KK, Foto Profil)
- ✅ **OCR KTP Integration** (ekstrak data dari foto KTP)
- ✅ **Role-based Redirect** (Nasabah vs Admin)
- ✅ **Session Management** untuk multi-step form

### Yang Belum Berfungsi ❌:
- ❌ **OTP Verification via WhatsApp** (Step 2 belum diimplementasi)
- ❌ **Email Verification**
- ❌ **Admin Approval System** (memindahkan data dari temp ke tabel utama)
- ❌ **Forgot Password**

---

## 🏗️ ARSITEKTUR SISTEM

### Konsep Dual Storage (Temp → Production)

```
┌──────────────────────────────────────────────────────────────┐
│                    REGISTRASI FLOW                            │
└──────────────────────────────────────────────────────────────┘

User Register → Step 1-6 Forms → Data Temp Tables → [PENDING]
                                                          ↓
                                        Admin Approval [BELUM ADA]
                                                          ↓
                                    Production Tables ← Move Data
                                                          ↓
                                          User Can Login Fully


┌──────────────────────────────────────────────────────────────┐
│                      LOGIN FLOW                               │
└──────────────────────────────────────────────────────────────┘

User Input Email + Password → Validation → Check PIN
                                                ↓
                                    PIN Exists? → Show PIN Modal
                                                ↓
                                    Verify PIN → Role-based Redirect
                                                ↓
                                    - Nasabah → /nasabah/dashboard
                                    - Admin → /admin/dashboard
```

### Tabel Data Storage:

```
TEMPORARY TABLES (Pending Approval):
- users_temp          → Data user sementara
- tbl_nasabah_temp    → Data nasabah sementara
- tbl_pekerjaan_temp  → Data pekerjaan sementara
- tbl_data_rek_temp   → Data rekening sementara
- tbl_data_ktp_temp   → Data KTP sementara
- tbl_darurat_temp    → Data kontak darurat sementara

PRODUCTION TABLES (Active):
- users               → User aktif (bisa login)
- tbl_nasabah         → Nasabah aktif
- tbl_pekerjaan       → Pekerjaan aktif
- tbl_data_rek        → Rekening aktif
- tbl_data_ktp        → KTP aktif
- tbl_darurat         → Kontak darurat aktif
```

---

## 🔄 ALUR REGISTRASI LENGKAP

### Overview 3 Step Besar:

```
STEP 1: FORM DATA (6 Sub-steps)
  └─> Sub-step 1: Data Diri
  └─> Sub-step 2: Detail Nasabah
  └─> Sub-step 3: Pekerjaan
  └─> Sub-step 4: Rekening
  └─> Sub-step 5: Data KTP (OCR)
  └─> Sub-step 6: Kontak Darurat

STEP 2: OTP VERIFICATION [BELUM IMPLEMENTASI]
  └─> Send OTP via WhatsApp
  └─> Verify 6-digit OTP

STEP 3: PIN CREATION
  └─> Create 6-digit PIN
  └─> Confirm PIN
  └─> Move data from temp to production
  └─> Auto login user
```

---

### STEP 1: Form Data (Detail Per Sub-step)

#### **Sub-step 1: Data Diri** 
📍 Route: `/register?step=1&substep=1`  
📄 Controller: `RegisterController::handleStep1()`

**Form Fields:**
```
- nama (required, string, max:255)
- email (required, email, unique:users + unique:users_temp)
- nomor_hp (required, string, max:20)
- password (required, min:8, confirmed)
- password_confirmation (required)
- foto (optional, image, max:2MB)
```

**Flow:**
1. User mengisi form data diri
2. Validasi data
3. **Create or Update UserTemp**:
   - Jika email sudah ada di `users_temp` → Update
   - Jika email belum ada → Create baru
4. Upload foto ke `storage/public/registrasi/temp/data_diri/foto_profil/`
5. Simpan `user_temp_id` ke session
6. Redirect ke sub-step 2

**Storage:**
- Tabel: `users_temp`
- Session: `register_user_temp_id`

---

#### **Sub-step 2: Detail Nasabah**
📍 Route: `/register?step=1&substep=2`

**Form Fields:**
```
- no_kk (optional, max:16, unique:tbl_nasabah_temp)
- tempat_lahir (optional)
- tanggal_lahir (optional, date)
- jenis_kelamin (optional, enum: L/P)
- alamat (optional, text)
- foto_ktp (optional, image, max:5MB)
- foto_kk (optional, image, max:5MB)
```

**Flow:**
1. User mengisi data nasabah
2. Validasi data
3. **Create or Update NasabahTemp**:
   - Link ke `user_temp_id` dari session
   - Jika sudah ada → Update
   - Jika belum ada → Create
4. Upload foto ke `storage/public/registrasi/temp/users_{id}/`
5. Simpan `nasabah_temp_id` ke session
6. Redirect ke sub-step 3

**Special Handling:**
- Field `no_kk` boleh kosong (NULL) - karena nullable
- Jika `no_kk` kosong, sistem set NULL (bukan temporary value)
- Multiple NULL values diizinkan (unique constraint)

**Storage:**
- Tabel: `tbl_nasabah_temp`
- Session: `register_nasabah_temp_id`

---

#### **Sub-step 3: Pekerjaan**
📍 Route: `/register?step=1&substep=3`

**Form Fields:**
```
- pekerjaan (optional, string)
- penghasilan (optional, enum: range values)
  Options:
  * < Rp1.000.000
  * Rp1.000.000 – Rp2.500.000
  * Rp2.500.000 – Rp5.000.000
  * Rp5.000.000 – Rp7.500.000
  * Rp7.500.000 – Rp10.000.000
  * Rp10.000.000 – Rp15.000.000
  * >Rp15.000.000
- nama_perusahaan (optional, string)
- nama_bank (optional, string) [DEPRECATED - tidak ada di tabel pekerjaan utama]
```

**Flow:**
1. User mengisi data pekerjaan
2. Validasi data
3. **Create or Update PekerjaanTemp**
4. Link ke `nasabah_temp_id`
5. Redirect ke sub-step 4

**Note:**
- Field `nama_bank` disimpan di temp tapi tidak ada di tabel pekerjaan utama
- Penghasilan menggunakan string (range), bukan decimal

**Storage:**
- Tabel: `tbl_pekerjaan_temp`

---

#### **Sub-step 4: Rekening**
📍 Route: `/register?step=1&substep=4`

**Form Fields:**
```
- no_rekening (optional, numeric, max:16)
- nama_pemilik_rekening (optional, string)
- jenis_atm (optional, enum: BCA/Mandiri/BNI/BRI)
```

**Flow:**
1. User mengisi data rekening
2. Validasi: no_rekening hanya angka
3. **Create or Update DataRekTemp**
4. Link ke `nasabah_temp_id`
5. Redirect ke sub-step 5

**Special Handling:**
- Input validation: hanya angka yang diizinkan (regex pattern)
- JavaScript validation: prevent non-numeric input

**Storage:**
- Tabel: `tbl_data_rek_temp`

---

#### **Sub-step 5: Data KTP (dengan OCR)**
📍 Route: `/register?step=1&substep=5`

**Form Fields:**
```
- file_ktp (required via OCR or upload)
- nik (optional, string, max:16)
- nama_lengkap_ktp (optional, string, max:100)
- tempat_lahir_ktp (optional, string)
- tanggal_lahir_ktp (optional, date)
- rt_rw (optional, string)
- kel_desa (optional, string)
- kecamatan (optional, string)
- alamat_ktp (optional, text - fallback)
- jenis_kelamin_ktp (optional, enum: Laki-laki/Perempuan)
```

**Flow:**
1. User upload foto KTP (atau ambil dari kamera)
2. **OCR Processing** (optional):
   - POST ke `/register/ocr`
   - Ekstrak data dari foto KTP menggunakan Tesseract OCR
   - Auto-fill form fields dengan data hasil OCR
3. User bisa edit data jika OCR tidak akurat
4. Submit form
5. **Create or Update DataKtpTemp**
6. Alamat dari RT/RW, Kel/Desa, Kecamatan digabung menjadi satu string
7. Upload file KTP ke storage
8. Redirect ke sub-step 6

**OCR Features:**
- Camera capture atau file upload
- Preview foto sebelum OCR
- Auto-fill fields: NIK, Nama, Tempat/Tanggal Lahir, Alamat, Jenis Kelamin
- Manual edit jika OCR gagal

**Special Notes:**
```
OCR Requirements:
- Foto harus landscape (mendatar)
- Foto harus jelas dan tidak gelap
- Tidak boleh over-exposed (terlalu terang)
- User diminta check ulang setelah OCR
```

**Storage:**
- Tabel: `tbl_data_ktp_temp`
- File: `storage/public/registrasi/temp/users_{id}/file_ktp/`

---

#### **Sub-step 6: Kontak Darurat** (Optional)
📍 Route: `/register?step=1&substep=6`

**Form Fields:**
```
- darurat_nama_lengkap (optional)
- hubungan_peminjam (optional, enum)
  Options: Suami/Istri, Orang Tua, Anak, Saudara, Lainnya
- darurat_no_telepon (optional, max:20)
- darurat_email (optional, email)
- darurat_alamat (optional, text)
- darurat_pekerjaan (optional)
- darurat_no_ktp (optional, max:16)
- darurat_foto_ktp (optional, image)
```

**Flow:**
1. User mengisi data kontak darurat (OPTIONAL)
2. Validasi data
3. **Create or Update DaruratTemp**
4. Upload foto KTP darurat
5. **Move all photos to permanent storage**:
   - Semua foto dipindahkan dari `registrasi/temp/` ke `user/{userId}/dataori/`
6. Redirect ke **STEP 2** (OTP Verification)

**Special Handling:**
- Semua field optional
- Data darurat hanya dibuat di production jika `nama_lengkap` tidak kosong
- Foto dipindahkan ke permanent storage setelah step ini

**Storage:**
- Tabel: `tbl_darurat_temp`
- Photo move: `registrasi/temp/` → `user/{userId}/dataori/`

---

### STEP 2: OTP VERIFICATION ⚠️ [BELUM DIIMPLEMENTASI]

📍 Route: `/register?step=2`  
📄 Controller: `RegisterController::handleStep2Otp()`

**Status**: 🔴 **BELUM BERFUNGSI - Perlu Implementasi**

**Yang Sudah Ada:**
- ✅ Form OTP (6 digit)
- ✅ UI untuk input OTP
- ✅ Session check: `register_otp_verified`

**Yang Belum Ada:**
- ❌ Generate OTP code
- ❌ Send OTP via WhatsApp (integration)
- ❌ Save OTP to database (`tbl_otp`)
- ❌ Verify OTP from database
- ❌ OTP expiration (5 menit)
- ❌ Resend OTP functionality

**Temporary Workaround:**
```php
// Saat ini, sistem langsung set session:
$request->session()->put('register_otp_verified', true);

// Dan redirect ke Step 3
return redirect()->route('register', ['step' => 3]);
```

**Flow yang Direncanakan:**
```
1. User selesai Step 1 (semua sub-steps)
   ↓
2. System generate 6-digit OTP
   ↓
3. System save OTP ke tbl_otp:
   - user_id
   - otp_code
   - expires_at (5 menit dari sekarang)
   - verified (boolean, default: false)
   ↓
4. System kirim OTP via WhatsApp API
   ↓
5. User input OTP di form
   ↓
6. System verify OTP:
   - Check code
   - Check expiration
   - Update verified = true
   ↓
7. Set session: register_otp_verified = true
   ↓
8. Redirect ke Step 3 (PIN Creation)
```

**Referensi Implementasi:**
- Lihat: `ANALISIS_SISTEM_OTP_WHATSAPP.md`
- Lihat: `SARAN_IMPLEMENTASI_OTP.md`

---

### STEP 3: PIN CREATION & FINALIZATION

📍 Route: `/register?step=3`  
📄 Controller: `RegisterController::handleStep3Pin()`

**Status**: ✅ **BERFUNGSI PENUH**

**Form Fields:**
```
- pin (required, 6 digits, confirmed)
- pin_confirmation (required, 6 digits)
```

**Flow:**
1. Check: OTP sudah verified? (session `register_otp_verified`)
   - Jika belum → Redirect ke Step 2
2. User input PIN 6 digit
3. User konfirmasi PIN
4. Validasi: PIN harus sama
5. **Database Transaction Start**
6. Move all photos to permanent storage (double check)
7. **Create User di tabel `users`**:
   ```php
   User::create([
       'nama' => $userTemp->nama,
       'email' => $userTemp->email,
       'password' => $userTemp->password, // already hashed
       'nomor_hp' => $userTemp->nomor_hp,
       'foto' => $userTemp->foto,
       'pin' => $request->pin, // PIN langsung ke users
       'role' => 'nasabah',
   ]);
   ```
8. **Update UserTemp** dengan `user_id`
9. **Create Nasabah di tabel `tbl_nasabah`** (dari nasabah_temp)
10. **Create Pekerjaan** (dari pekerjaan_temp)
11. **Create DataRek** (dari data_rek_temp)
12. **Create DataKtp** (dari data_ktp_temp)
13. **Create Darurat** (dari darurat_temp) - hanya jika nama_lengkap tidak kosong
14. **Commit Transaction**
15. Clear semua session registration
16. **Auto login user**: `Auth::login($user)`
17. Redirect ke `/nasabah/dashboard`

**Special Handling - Darurat Data:**
```php
// Hanya create Darurat jika nama_lengkap ada
if (!empty($daruratTemp->nama_lengkap)) {
    // Generate unique values untuk field yang unique
    $noTelepon = !empty($daruratTemp->no_telepon) 
        ? $daruratTemp->no_telepon 
        : str_pad($nasabah->id, 12, '0', STR_PAD_LEFT);
    
    $noKtp = !empty($daruratTemp->no_ktp)
        ? $daruratTemp->no_ktp
        : str_pad($nasabah->id . time(), 16, '0', STR_PAD_LEFT);
    
    Darurat::create([...]);
}
```

**Session yang Di-clear:**
```
- register_step1
- register_step2
- register_step3
- register_step4
- register_step5
- register_step6
- register_user_temp_id
- register_nasabah_temp_id
- register_otp_verified
- register_session_id
```

**Result:**
- User langsung login
- Data sudah masuk ke production tables
- Data temp masih ada (untuk audit trail)

---

## 🔐 ALUR LOGIN LENGKAP

### Overview Flow:

```
User Input Email + Password
        ↓
    Validasi Form
        ↓
    Auth::validate($credentials)
        ↓
    Get User from Email
        ↓
  User Punya PIN?
    ↙       ↘
  YES        NO
   ↓          ↓
Show PIN    Direct
Modal       Login
   ↓          ↓
Verify      Auth::login()
PIN         ↓
   ↓      Role-based
Auth::login()  Redirect
   ↓
Role-based
Redirect
```

---

### Step-by-Step Detail:

#### **1. Show Login Form**
📍 Route: `GET /login`  
📄 Controller: `LoginController::showLoginForm()`

**Features:**
- Form dengan email & password
- Remember me checkbox
- Link ke register page
- Link "Lupa password?" (belum ada handler)
- Error & success message display
- PIN Modal (hidden by default)

---

#### **2. Submit Login**
📍 Route: `POST /login`  
📄 Controller: `LoginController::login()`

**Validation:**
```php
'email' => 'required|email',
'password' => 'required|string'
```

**Flow:**
```php
// 1. Validate credentials WITHOUT logging in
if (Auth::validate($credentials)) {
    // 2. Get user
    $user = User::where('email', $credentials['email'])->first();
    
    // 3. Check if user has PIN
    if ($user->pin !== null && $user->pin !== '') {
        // 3a. Store user ID in session
        session()->put('login_user_id', $user->id);
        session()->put('login_remember', $remember);
        
        // 3b. Return JSON for AJAX
        return response()->json([
            'success' => true,
            'requires_pin' => true,
            'message' => 'Silakan masukkan PIN Anda'
        ]);
    }
    
    // 4. No PIN required, login directly
    Auth::login($user, $remember);
    session()->regenerate();
    return $this->redirectAfterLogin($user);
}

// 5. Credentials invalid
return back()->withErrors([
    'email' => 'Email atau password yang Anda masukkan salah.'
]);
```

**AJAX Support:**
- Form submit via JavaScript fetch
- Response JSON jika requires_pin = true
- Show PIN modal dinamis

---

#### **3. PIN Verification**
📍 Route: `POST /login/verify-pin`  
📄 Controller: `LoginController::verifyPin()`

**Validation:**
```php
'pin' => 'required|string|size:6'
```

**Flow:**
```php
// 1. Get user ID from session
$userId = session()->get('login_user_id');

// 2. Get user
$user = User::find($userId);

// 3. Verify PIN (convert to integer)
$inputPin = (int) str_replace(['.', ','], '', $request->pin);
$userPin = (int) $user->pin;

if ($inputPin !== $userPin) {
    return response()->json([
        'success' => false,
        'message' => 'PIN yang Anda masukkan salah.'
    ], 422);
}

// 4. PIN verified, login user
$remember = session()->get('login_remember', false);
Auth::login($user, $remember);

// 5. Clear session
session()->forget(['login_user_id', 'login_remember']);
session()->regenerate();

// 6. Return redirect URL
return response()->json([
    'success' => true,
    'message' => 'Login berhasil!',
    'redirect_url' => $this->getRedirectUrl($user)
]);
```

**PIN Auto-submit:**
- JavaScript auto-submit form ketika user input 6 digit
- Hanya angka yang diizinkan

---

#### **4. Role-based Redirect**

**Logic:**
```php
private function redirectAfterLogin($user)
{
    if ($user->role === 'nasabah') {
        return redirect()->intended(route('nasabah.dashboard'))
            ->with('success', 'Selamat datang, ' . $user->nama . '!');
    } 
    elseif ($user->role === 'admin_operasional' || $user->role === 'admin_utama') {
        return redirect()->intended('/admin/dashboard')
            ->with('success', 'Selamat datang, ' . $user->nama . '!');
    }
    
    return redirect()->intended('/')
        ->with('success', 'Selamat datang!');
}
```

**Redirect Targets:**
- **Nasabah** → `/nasabah/dashboard`
- **Admin Operasional** → `/admin/dashboard`
- **Admin Utama** → `/admin/dashboard`
- **Default** → `/`

---

#### **5. Logout**
📍 Route: `POST /logout`  
📄 Controller: `LoginController::logout()`

**Flow:**
```php
Auth::logout();
$request->session()->invalidate();
$request->session()->regenerateToken();
return redirect('/');
```

**Security:**
- Session invalidated
- CSRF token regenerated
- Redirect ke home

---

## 🗄️ DATABASE SCHEMA

### 1. **users** (Production)
```sql
CREATE TABLE users (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    pin INT NULL,
    password VARCHAR(255) NOT NULL,
    nomor_hp CHAR(12) NOT NULL,
    foto VARCHAR(255) NOT NULL,
    role ENUM('nasabah', 'admin_operasional', 'admin_utama') DEFAULT 'nasabah',
    email_verified_at TIMESTAMP NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

**Relasi:**
- `hasOne`: Nasabah
- `hasOne`: AdminOperasional
- `hasOne`: AdminUtama
- `hasMany`: Otp

---

### 2. **users_temp** (Temporary)
```sql
CREATE TABLE users_temp (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NULL,
    nama VARCHAR(255) NULL,
    email VARCHAR(255) UNIQUE NULL,
    pin INT NULL,
    password VARCHAR(255) NULL,
    nomor_hp CHAR(12) NULL,
    foto VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

**Note:**
- Semua field nullable (fleksibel untuk multi-step)
- `user_id` di-set setelah user di-create di production

**Relasi:**
- `belongsTo`: User
- `hasOne`: NasabahTemp

---

### 3. **tbl_nasabah_temp** (Temporary)
```sql
CREATE TABLE tbl_nasabah_temp (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    no_kk CHAR(16) UNIQUE NULL,
    tempat_lahir VARCHAR(255) NULL,
    tanggal_lahir DATE NULL,
    jenis_kelamin ENUM('L', 'P') NULL,
    alamat TEXT NULL,
    foto_ktp VARCHAR(255) NULL,
    foto_kk VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users_temp(id) ON DELETE CASCADE
);
```

**Note:**
- Field `alamat` ditambahkan via migration update
- `no_kk` unique tapi nullable (multiple NULL allowed)

**Relasi:**
- `belongsTo`: UserTemp
- `hasOne`: PekerjaanTemp
- `hasOne`: DataKtpTemp
- `hasMany`: DataRekTemp
- `hasMany`: DaruratTemp

---

### 4. **tbl_pekerjaan_temp**
```sql
CREATE TABLE tbl_pekerjaan_temp (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    nasabah_id BIGINT NOT NULL,
    pekerjaan VARCHAR(255) NULL,
    penghasilan DECIMAL(10,2) NULL,
    nama_perusahaan VARCHAR(255) NULL,
    nama_bank VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (nasabah_id) REFERENCES tbl_nasabah_temp(id) ON DELETE CASCADE
);
```

**Note:**
- `nama_bank` ada di temp tapi tidak ada di production table
- `penghasilan` berupa range string (bukan decimal di implementasi)

---

### 5. **tbl_data_rek_temp**
```sql
CREATE TABLE tbl_data_rek_temp (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    nasabah_id BIGINT NOT NULL,
    no_rekening CHAR(16) UNIQUE NULL,
    nama_pemilik_rekening VARCHAR(255) NULL,
    jenis_atm VARCHAR(20) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (nasabah_id) REFERENCES tbl_nasabah_temp(id) ON DELETE CASCADE
);
```

**Note:**
- `no_rekening` unique tapi nullable
- `jenis_atm` di-map ke `nama_bank` di production

---

### 6. **tbl_data_ktp_temp**
```sql
CREATE TABLE tbl_data_ktp_temp (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    nasabah_id BIGINT UNIQUE NOT NULL,
    nik VARCHAR(16) UNIQUE NULL,
    nama_lengkap VARCHAR(100) NULL,
    tempat_lahir VARCHAR(100) NULL,
    tanggal_lahir DATE NULL,
    alamat TEXT NULL,
    jenis_kelamin ENUM('Laki-laki', 'Perempuan') NULL,
    file_ktp VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (nasabah_id) REFERENCES tbl_nasabah_temp(id) ON DELETE CASCADE
);
```

**Note:**
- `nasabah_id` unique (one-to-one relationship)
- `nik` unique tapi nullable

---

### 7. **tbl_darurat_temp**
```sql
CREATE TABLE tbl_darurat_temp (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    id_nasabah BIGINT NOT NULL,
    nama_lengkap VARCHAR(255) NULL,
    hubungan_peminjam VARCHAR(100) NULL,
    no_telepon CHAR(12) UNIQUE NULL,
    alamat TEXT NULL,
    pekerjaan VARCHAR(100) NULL,
    email VARCHAR(255) NULL,
    no_ktp VARCHAR(16) UNIQUE NULL,
    foto_ktp VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (id_nasabah) REFERENCES tbl_nasabah_temp(id) ON DELETE CASCADE
);
```

**Note:**
- Semua field nullable (optional data)
- `no_telepon` dan `no_ktp` unique tapi nullable

---

### Database Relationships:

```
users
  ├─ hasOne → nasabah
  ├─ hasOne → admin_operasional
  ├─ hasOne → admin_utama
  └─ hasMany → otps

users_temp
  ├─ belongsTo → users (via user_id)
  └─ hasOne → nasabah_temp

nasabah_temp
  ├─ belongsTo → users_temp
  ├─ hasOne → pekerjaan_temp
  ├─ hasOne → data_ktp_temp
  ├─ hasMany → data_rek_temp
  └─ hasMany → darurat_temp
```

---

## ✅ STATUS IMPLEMENTASI

### Fitur yang SUDAH BERFUNGSI:

#### Login System ✅
- [x] Form login dengan email & password
- [x] Validation (email required, password required)
- [x] Auth::validate() sebelum login
- [x] PIN verification (jika user punya PIN)
- [x] PIN modal dengan AJAX
- [x] Remember me functionality
- [x] Role-based redirect
- [x] Session regeneration
- [x] Logout functionality
- [x] Error handling dengan pesan Indonesia

#### Register System ✅
- [x] Multi-step form (6 sub-steps + 2 main steps)
- [x] Progress indicator visual
- [x] Step 1.1: Data Diri (nama, email, password, foto)
- [x] Step 1.2: Detail Nasabah (KK, KTP, KK photos)
- [x] Step 1.3: Pekerjaan (pekerjaan, penghasilan, perusahaan)
- [x] Step 1.4: Rekening (no rekening, bank)
- [x] Step 1.5: Data KTP dengan OCR
- [x] Step 1.6: Kontak Darurat (optional)
- [x] Step 3: PIN Creation & Finalization
- [x] Session storage untuk multi-step
- [x] File upload dengan preview
- [x] OCR KTP integration (Tesseract)
- [x] Camera capture support
- [x] Data storage di temp tables
- [x] Move data temp → production
- [x] Auto login setelah register
- [x] Database transaction
- [x] Photo management (temp → permanent)

#### Data Management ✅
- [x] UserTemp model & migration
- [x] NasabahTemp model & migration
- [x] PekerjaanTemp model & migration
- [x] DataRekTemp model & migration
- [x] DataKtpTemp model & migration
- [x] DaruratTemp model & migration
- [x] Update migration untuk field nullable
- [x] Field `alamat` di nasabah_temp

#### File Storage ✅
- [x] Upload foto profil
- [x] Upload foto KTP
- [x] Upload foto KK
- [x] Upload file KTP untuk OCR
- [x] Upload foto KTP darurat
- [x] Move photos temp → permanent

#### UI/UX ✅
- [x] Responsive design (Tailwind CSS)
- [x] Form validation display
- [x] Error & success messages
- [x] Image preview
- [x] Progress indicator
- [x] Loading states
- [x] Modal untuk PIN
- [x] Camera support

---

## ❌ FITUR YANG BELUM BERJALAN

### 1. **OTP Verification via WhatsApp** 🔴 PRIORITAS TINGGI

**Status**: Belum diimplementasi

**Yang Belum Ada:**
- Generate OTP 6-digit
- Save OTP ke database (`tbl_otp`)
- Send OTP via WhatsApp API
- Verify OTP code
- OTP expiration (5 menit)
- Resend OTP functionality
- OTP timer countdown

**Current Workaround:**
```php
// Skip OTP verification
$request->session()->put('register_otp_verified', true);
return redirect()->route('register', ['step' => 3]);
```

**Yang Perlu Dibuat:**
1. Setup WhatsApp API (Twilio / Fonnte / Wablas)
2. Create `tbl_otp` table jika belum ada
3. OTP generation logic
4. Send OTP service
5. Verify OTP logic
6. OTP expiration handling
7. Resend OTP dengan rate limiting

**Referensi:**
- `ANALISIS_SISTEM_OTP_WHATSAPP.md`
- `SARAN_IMPLEMENTASI_OTP.md`

---

### 2. **Email Verification** 🟡 PRIORITAS SEDANG

**Status**: Belum diimplementasi

**Yang Belum Ada:**
- Email verification after registration
- Send verification email
- Verify email link
- Email verified status

**Yang Ada:**
- Field `email_verified_at` di tabel `users` (sudah ada tapi tidak digunakan)

**Yang Perlu Dibuat:**
1. Email template untuk verification
2. Generate verification token
3. Send email service (Laravel Mail)
4. Verify email route & controller
5. Update `email_verified_at` setelah verify

---

### 3. **Admin Approval System** 🔴 PRIORITAS TINGGI

**Status**: Belum diimplementasi

**Yang Belum Ada:**
- Admin panel untuk lihat pending registrations
- Approval/reject mechanism
- Move data dari temp tables ke production tables
- Notification ke user setelah approved/rejected

**Current Behavior:**
- User register → Data langsung masuk ke production
- Tidak ada approval process
- Data temp tidak pernah di-move

**Yang Perlu Dibuat:**
1. Admin dashboard untuk pending registrations
2. View detail registrasi temp
3. Button Approve / Reject
4. Move data temp → production (jika approve)
5. Email/WhatsApp notification ke user
6. Status tracking (pending, approved, rejected)

**Alur yang Direncanakan:**
```
User Register → Data di Temp Tables → Status: PENDING
                                            ↓
                                    Admin Review
                                    ↙        ↘
                              APPROVE      REJECT
                                ↓            ↓
                          Move to        Delete
                          Production     Temp Data
                                ↓            ↓
                          Notify User   Notify User
```

---

### 4. **Forgot Password** 🟢 PRIORITAS RENDAH

**Status**: Belum diimplementasi

**Yang Belum Ada:**
- Forgot password page
- Send reset link via email
- Reset password page
- Update password

**Yang Ada:**
- Link "Lupa password?" di login page (belum ada handler)
- Tabel `password_reset_tokens` (sudah ada di migration)

**Yang Perlu Dibuat:**
1. Forgot password form (email input)
2. Generate reset token
3. Send reset email
4. Reset password form
5. Update password logic

---

### 5. **Rate Limiting** 🟡 PRIORITAS SEDANG

**Status**: Belum diimplementasi

**Yang Belum Ada:**
- Rate limiting untuk login attempts
- Rate limiting untuk OTP resend
- Rate limiting untuk register attempts

**Yang Perlu Dibuat:**
1. Login rate limit (max 5 attempts per 5 menit)
2. OTP resend rate limit (max 3 kali per 30 menit)
3. Register rate limit (max 3 kali per jam per IP)

---

### 6. **Middleware Protection** 🟡 PRIORITAS SEDANG

**Status**: Partially implemented

**Yang Belum Ada:**
- Middleware untuk check email verified
- Middleware untuk check account status (approved/pending)

**Yang Ada:**
- Middleware `auth` di routes nasabah ✅

**Yang Perlu Dibuat:**
1. `verified` middleware untuk check email
2. `approved` middleware untuk check account status
3. Apply middleware ke routes yang perlu protection

---

## 🐛 MASALAH & BUG

### 1. **No KK Unique Constraint Issue** 🟡

**Lokasi**: `RegisterController::handleStep1()` Sub-step 2

**Masalah**:
- Field `no_kk` di `tbl_nasabah_temp` unique tapi nullable
- Multiple NULL values diizinkan
- Tapi jika user input same `no_kk`, akan error

**Current Handling:**
```php
// Controller sudah handle dengan baik:
try {
    $nasabahTemp->update($updateData);
} catch (\Illuminate\Database\QueryException $e) {
    // Catch unique constraint violation
    // Remove no_kk dari update dan retry
}
```

**Status**: ✅ Sudah di-handle dengan try-catch

---

### 2. **Data Darurat Unique Constraints** 🟡

**Lokasi**: `RegisterController::handleStep3Pin()`

**Masalah**:
- Field `no_telepon` dan `no_ktp` di darurat unique
- Jika user tidak isi, sistem generate dummy value
- Bisa bentrok jika timing sama

**Current Handling:**
```php
// Generate unique value with timestamp
$noTelepon = str_pad($nasabah->id, 12, '0', STR_PAD_LEFT);
$noKtp = str_pad($nasabah->id . time(), 16, '0', STR_PAD_LEFT);

// Jika error, retry dengan microtime
try {
    Darurat::create($daruratData);
} catch (\Exception $e) {
    $daruratData['no_telepon'] = str_pad($nasabah->id . microtime(true), 12, '0', STR_PAD_LEFT);
    Darurat::create($daruratData);
}
```

**Status**: ✅ Sudah di-handle dengan retry logic

---

### 3. **Photo Storage Inconsistency** 🟢

**Lokasi**: File upload di semua sub-steps

**Masalah**:
- Foto di-upload ke `registrasi/temp/` dulu
- Lalu di-move ke `user/{userId}/dataori/`
- Tapi jika user cancel di tengah, foto tidak di-delete

**Impact**: Orphaned files di storage

**Solusi yang Disarankan**:
1. Background job untuk cleanup orphaned files
2. Set expiration untuk files di temp folder (7 hari)

---

### 4. **OCR Accuracy** 🟢

**Lokasi**: Step 1 Sub-step 5 (Data KTP)

**Masalah**:
- OCR tidak selalu akurat
- User perlu manual check

**Current Handling**:
- ✅ User bisa edit setelah OCR
- ✅ Warning message di UI

**Status**: ✅ Acceptable - user aware

---

## 🎯 REKOMENDASI LANGKAH SELANJUTNYA

### Prioritas 1: OTP Implementation 🔴

**Estimasi**: 4-6 jam

**Tasks:**
1. Setup WhatsApp API (Fonnte recommended)
2. Create `tbl_otp` migration jika belum ada
3. Implementasi OTP generation
4. Implementasi send OTP
5. Implementasi verify OTP
6. Implementasi OTP expiration
7. Implementasi resend OTP
8. Testing

**Files yang Perlu Diubah:**
- `RegisterController::handleStep2Otp()`
- Create `app/Services/OtpService.php`
- Create `app/Services/WhatsAppService.php`
- Create migration `tbl_otp` (jika belum ada)

---

### Prioritas 2: Admin Approval System 🔴

**Estimasi**: 6-8 jam

**Tasks:**
1. Create admin dashboard untuk pending registrations
2. List all pending users
3. View detail registrasi
4. Approve button dengan logic move data
5. Reject button dengan delete temp data
6. Notification service (email/WhatsApp)
7. Status tracking

**Files yang Perlu Dibuat:**
- `AdminRegistrationController.php`
- Views: `admin/registrations/index.blade.php`
- Views: `admin/registrations/show.blade.php`
- Service: `RegistrationApprovalService.php`

---

### Prioritas 3: Email Verification 🟡

**Estimasi**: 3-4 jam

**Tasks:**
1. Setup Laravel Mail
2. Create verification email template
3. Generate verification token
4. Send email after registration
5. Verify email route
6. Update `email_verified_at`

**Files yang Perlu Dibuat:**
- `EmailVerificationController.php`
- Mail template: `VerifyEmailMail.php`
- Views: `emails/verify-email.blade.php`

---

### Prioritas 4: Forgot Password 🟢

**Estimasi**: 2-3 jam

**Tasks:**
1. Forgot password page
2. Send reset email
3. Reset password page
4. Update password

**Files yang Perlu Dibuat:**
- `ForgotPasswordController.php`
- `ResetPasswordController.php`
- Views: `auth/forgot-password.blade.php`
- Views: `auth/reset-password.blade.php`

---

## 📝 RENCANA PERBAIKAN

### Phase 1: Core Features (Prioritas Tinggi)

**Timeline**: 1-2 minggu

1. **Implementasi OTP** (4-6 jam)
   - WhatsApp API integration
   - OTP generation & verification
   - Expiration handling

2. **Admin Approval System** (6-8 jam)
   - Admin dashboard
   - Approve/reject mechanism
   - Data migration temp → production

3. **Email Verification** (3-4 jam)
   - Email template
   - Verification link
   - Status update

**Total**: 13-18 jam

---

### Phase 2: Security & UX (Prioritas Sedang)

**Timeline**: 1 minggu

1. **Rate Limiting** (2-3 jam)
   - Login rate limit
   - OTP resend limit
   - Register rate limit

2. **Middleware Protection** (2 jam)
   - Email verified middleware
   - Account approved middleware

3. **Forgot Password** (2-3 jam)
   - Reset password flow
   - Email notification

4. **Cleanup Orphaned Files** (2 jam)
   - Background job
   - Scheduled command

**Total**: 8-10 jam

---

### Phase 3: Enhancement (Prioritas Rendah)

**Timeline**: 1 minggu

1. **Notification System** (4-6 jam)
   - Email notifications
   - WhatsApp notifications
   - In-app notifications

2. **Audit Trail** (3-4 jam)
   - Log semua actions
   - Admin activity log

3. **User Profile Management** (4-5 jam)
   - Edit profile
   - Change password
   - Change PIN

**Total**: 11-15 jam

---

## 🚀 CARA MELANJUTKAN DEVELOPMENT

### Step 1: Setup OTP Service

**File: `app/Services/WhatsAppService.php`**
```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('services.whatsapp.api_url');
        $this->apiKey = config('services.whatsapp.api_key');
    }

    /**
     * Send OTP via WhatsApp
     */
    public function sendOtp(string $phone, string $otpCode): bool
    {
        try {
            $message = "Kode OTP Koperasi Majakara Anda adalah: {$otpCode}\n\nKode berlaku selama 5 menit.\n\nJangan bagikan kode ini kepada siapa pun.";

            $response = Http::post($this->apiUrl . '/send-message', [
                'api_key' => $this->apiKey,
                'phone' => $this->formatPhone($phone),
                'message' => $message,
            ]);

            if ($response->successful()) {
                Log::info('OTP sent successfully', ['phone' => $phone]);
                return true;
            }

            Log::error('Failed to send OTP', [
                'phone' => $phone,
                'response' => $response->body()
            ]);
            return false;

        } catch (\Exception $e) {
            Log::error('Error sending OTP', [
                'phone' => $phone,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Format phone number (08xxx → 628xxx)
     */
    private function formatPhone(string $phone): string
    {
        // Remove spaces, dashes, etc
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Convert 08xxx to 628xxx
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }
        
        return $phone;
    }
}
```

**File: `app/Services/OtpService.php`**
```php
<?php

namespace App\Services;

use App\Models\Otp;
use Carbon\Carbon;
use Illuminate\Support\Str;

class OtpService
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Generate and send OTP
     */
    public function generateAndSend(int $userId, string $phone): array
    {
        // Generate 6-digit OTP
        $otpCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Save to database
        $otp = Otp::create([
            'user_id' => $userId,
            'otp_code' => $otpCode,
            'expires_at' => Carbon::now()->addMinutes(5),
            'verified' => false,
        ]);

        // Send via WhatsApp
        $sent = $this->whatsappService->sendOtp($phone, $otpCode);

        return [
            'success' => $sent,
            'otp_id' => $otp->id,
            'expires_at' => $otp->expires_at,
        ];
    }

    /**
     * Verify OTP
     */
    public function verify(int $userId, string $otpCode): array
    {
        $otp = Otp::where('user_id', $userId)
            ->where('otp_code', $otpCode)
            ->where('verified', false)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$otp) {
            return [
                'success' => false,
                'message' => 'Kode OTP tidak valid.',
            ];
        }

        if (Carbon::now()->greaterThan($otp->expires_at)) {
            return [
                'success' => false,
                'message' => 'Kode OTP sudah kadaluarsa.',
            ];
        }

        // Mark as verified
        $otp->update(['verified' => true]);

        return [
            'success' => true,
            'message' => 'OTP berhasil diverifikasi.',
        ];
    }
}
```

**File: `config/services.php`**
```php
return [
    // ... existing services

    'whatsapp' => [
        'api_url' => env('WHATSAPP_API_URL'),
        'api_key' => env('WHATSAPP_API_KEY'),
    ],
];
```

**File: `.env`**
```
WHATSAPP_API_URL=https://api.fonnte.com
WHATSAPP_API_KEY=your_fonnte_api_key_here
```

---

### Step 2: Update RegisterController

**Update Method: `handleStep2Otp()`**
```php
use App\Services\OtpService;

private function handleStep2Otp(Request $request)
{
    // Check if step 1 data exists
    $userTempId = $request->session()->get('register_user_temp_id');
    if (!$userTempId) {
        return redirect()->route('register', ['step' => 1])
            ->with('error', 'Silakan lengkapi data diri terlebih dahulu');
    }

    $userTemp = \App\Models\UserTemp::find($userTempId);
    if (!$userTemp || !$userTemp->nomor_hp) {
        return redirect()->route('register', ['step' => 1])
            ->with('error', 'Nomor HP belum diisi');
    }

    // Store phone in session for display
    $request->session()->put('register_phone', $userTemp->nomor_hp);

    // If OTP not sent yet, send it automatically
    if (!$request->has('otp_code')) {
        // NEW: Send OTP
        $otpService = app(OtpService::class);
        $result = $otpService->generateAndSend($userTemp->id, $userTemp->nomor_hp);
        
        if (!$result['success']) {
            return redirect()->route('register', ['step' => 2])
                ->with('error', 'Gagal mengirim OTP. Silakan coba lagi.');
        }
        
        $request->session()->put('register_otp_sent_at', now());
        
        return view('auth.register', [
            'step' => 2,
            'sessionData' => [],
            'formData' => [],
        ]);
    }

    // Verify OTP
    $validator = Validator::make($request->all(), [
        'otp_code' => 'required|string|size:6',
    ]);

    if ($validator->fails()) {
        return redirect()->route('register', ['step' => 2])
            ->withErrors($validator)
            ->withInput();
    }

    // NEW: Verify OTP
    $otpService = app(OtpService::class);
    $verifyResult = $otpService->verify($userTemp->id, $request->otp_code);
    
    if (!$verifyResult['success']) {
        return redirect()->route('register', ['step' => 2])
            ->with('error', $verifyResult['message'])
            ->withInput();
    }

    // OTP verified
    $request->session()->put('register_otp_verified', true);
    
    return redirect()->route('register', ['step' => 3])
        ->with('success', 'OTP berhasil diverifikasi. Silakan buat PIN Anda.');
}
```

---

### Step 3: Create OTP Migration (jika belum ada)

**File: `database/migrations/xxxx_create_tbl_otp_table.php`**
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_otp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users_temp')->onDelete('cascade');
            $table->string('otp_code', 6);
            $table->timestamp('expires_at');
            $table->boolean('verified')->default(false);
            $table->timestamps();
            
            $table->index(['user_id', 'otp_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_otp');
    }
};
```

---

## 📚 REFERENSI DOKUMEN

1. **ANALISIS_SISTEM_LOGIN_REGISTER.md** - Analisis lama (sudah outdated)
2. **ANALISIS_SISTEM_OTP_WHATSAPP.md** - Rekomendasi implementasi OTP
3. **SARAN_IMPLEMENTASI_OTP.md** - Detail implementasi OTP
4. **README_REGISTRASI.md** - Dokumentasi registrasi (jika ada)

---

## 🎯 KESIMPULAN

### Yang Sudah Baik ✅:
1. ✅ **Multi-step Registration** berfungsi dengan baik
2. ✅ **Login dengan PIN** sudah berfungsi
3. ✅ **Data Storage di Temp Tables** sudah proper
4. ✅ **File Upload & OCR** sudah berfungsi
5. ✅ **Auto Login setelah Register** sudah berfungsi
6. ✅ **UI/UX** sudah bagus dan responsive

### Yang Perlu Dikerjakan 🔨:
1. 🔴 **OTP Verification** - PRIORITAS TINGGI
2. 🔴 **Admin Approval System** - PRIORITAS TINGGI
3. 🟡 **Email Verification** - PRIORITAS SEDANG
4. 🟡 **Rate Limiting** - PRIORITAS SEDANG
5. 🟢 **Forgot Password** - PRIORITAS RENDAH

### Estimasi Total Waktu:
- **Phase 1** (Core Features): 13-18 jam
- **Phase 2** (Security & UX): 8-10 jam
- **Phase 3** (Enhancement): 11-15 jam
- **Total**: 32-43 jam (4-5 hari kerja)

---

**Dokumen ini dibuat untuk melanjutkan pengembangan sistem login dan register Koperasi Majakara.**

**Last Updated**: 30 Januari 2026  
**Next Review**: Setelah implementasi OTP & Admin Approval
