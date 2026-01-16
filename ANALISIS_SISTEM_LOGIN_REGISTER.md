# ANALISIS SISTEM LOGIN & REGISTER

## 📋 DAFTAR ISI
1. [Overview Sistem](#overview-sistem)
2. [Database Schema](#database-schema)
3. [Flow Registrasi](#flow-registrasi)
4. [Flow Login](#flow-login)
5. [Controllers](#controllers)
6. [Views](#views)
7. [Routes](#routes)
8. [Status Implementasi](#status-implementasi)
9. [Masalah yang Ditemukan](#masalah-yang-ditemukan)
10. [Rekomendasi Perbaikan](#rekomendasi-perbaikan)

---

## 📊 OVERVIEW SISTEM

Sistem login dan register menggunakan:
- **Authentication**: Laravel Auth dengan email & password
- **Registration**: Multi-step form (6 langkah) dengan session storage
- **User Storage**: Data sementara disimpan di tabel `*_temp` sebelum di-approve admin
- **Role-based Redirect**: Redirect otomatis berdasarkan role user setelah login

### Konsep Sistem:
```
REGISTRASI:
User → Step 1-6 → Data disimpan di *_temp → Menunggu approval admin → Data dipindah ke tabel utama

LOGIN:
User → Email + Password → Auth Check → Role-based Redirect
```

---

## 🗄️ DATABASE SCHEMA

### 1. Tabel Utama: `users`
**Tujuan**: Menyimpan data user yang sudah aktif

| Field | Type | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| nama | string | Nama lengkap |
| email | string (unique) | Email untuk login |
| pin | integer (nullable) | PIN untuk transaksi (opsional) |
| password | string (hashed) | Password untuk login |
| nomor_hp | char(12) | Nomor HP |
| foto | string | Path foto profil |
| role | enum | 'nasabah', 'admin_operasional', 'admin_utama' |
| email_verified_at | timestamp (nullable) | Email verification |
| remember_token | string | Remember me token |
| timestamps | - | created_at, updated_at |

### 2. Tabel Temp: `users_temp`
**Tujuan**: Menyimpan data user yang sedang registrasi (belum di-approve)

| Field | Type | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| user_id | foreignId (nullable) | FK ke users (jika sudah dibuat) |
| nama | string | Nama lengkap |
| email | string (unique) | Email |
| pin | integer (nullable) | PIN |
| password | string (hashed) | Password |
| nomor_hp | char(12) | Nomor HP |
| foto | string | Path foto profil |
| timestamps | - | created_at, updated_at |

**Catatan**: Tabel ini sepertinya tidak digunakan di RegisterController. Data langsung disimpan ke `users` setelah step 6.

### 3. Tabel Temp: `tbl_nasabah_temp`
**Tujuan**: Menyimpan data nasabah yang sedang registrasi

| Field | Type | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| user_id | foreignId | FK ke users_temp (tapi di controller FK ke users) |
| no_kk | char(16) (unique) | Nomor KK |
| tempat_lahir | string | Tempat lahir |
| tanggal_lahir | date | Tanggal lahir |
| jenis_kelamin | enum('L','P') | Jenis kelamin |
| alamat | string | Alamat (tidak ada di migration, tapi ada di model) |
| foto_ktp | string (nullable) | Path foto KTP |
| foto_kk | string (nullable) | Path foto KK |
| timestamps | - | created_at, updated_at |

**Catatan**: Migration tidak punya field `alamat`, tapi model punya di fillable. Perlu dicek.

### 4. Tabel Temp: `tbl_pekerjaan_temp`
**Tujuan**: Menyimpan data pekerjaan sementara

| Field | Type | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| nasabah_id | foreignId | FK ke tbl_nasabah_temp |
| pekerjaan | string (nullable) | Nama pekerjaan |
| penghasilan | decimal(10,2) (nullable) | Penghasilan |
| nama_perusahaan | string (nullable) | Nama perusahaan |
| nama_bank | string (nullable) | Nama bank |

### 5. Tabel Temp: `tbl_data_rek_temp`
**Tujuan**: Menyimpan data rekening sementara

| Field | Type | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| nasabah_id | foreignId | FK ke tbl_nasabah_temp |
| no_rekening | char(16) (unique) | Nomor rekening |
| nama_pemilik_rekening | string | Nama pemilik rekening |
| jenis_atm | string(20) | Jenis ATM (BCA, Mandiri, dll) |

### 6. Tabel Temp: `tbl_data_ktp_temp`
**Tujuan**: Menyimpan data KTP sementara

| Field | Type | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| nasabah_id | foreignId (unique) | FK ke tbl_nasabah_temp |
| nik | string(16) (unique) | NIK |
| nama_lengkap | string(100) | Nama lengkap dari KTP |
| tempat_lahir | string(100) | Tempat lahir |
| tanggal_lahir | date | Tanggal lahir |
| alamat | text | Alamat dari KTP |
| jenis_kelamin | enum | 'Laki-laki' atau 'Perempuan' |
| file_ktp | string | Path file KTP |

### 7. Tabel Temp: `tbl_darurat_temp`
**Tujuan**: Menyimpan data kontak darurat sementara

| Field | Type | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| id_nasabah | foreignId | FK ke tbl_nasabah_temp |
| nama_lengkap | string | Nama kontak darurat |
| hubungan_peminjam | string(100) | Hubungan (Istri, Suami, dll) |
| no_telepon | char(12) (unique) | Nomor telepon |
| alamat | text | Alamat |
| pekerjaan | string(100) | Pekerjaan |
| email | string | Email |
| no_ktp | string(16) (unique) | NIK kontak darurat |
| foto_ktp | string | Path foto KTP kontak darurat |

---

## 🔄 FLOW REGISTRASI

### Step-by-Step Flow:

```
1. User mengakses /register
   ↓
2. Step 1: Data Diri
   - Nama, Email, Nomor HP, Password, Foto Profil
   - Data disimpan di session: register_step1
   ↓
3. Step 2: Detail Nasabah
   - No KK, Tempat/Tanggal Lahir, Jenis Kelamin, Alamat, Foto KTP/KK
   - Data disimpan di session: register_step2
   ↓
4. Step 3: Data Pekerjaan
   - Pekerjaan, Penghasilan, Nama Perusahaan, Nama Bank
   - Data disimpan di session: register_step3
   ↓
5. Step 4: Data Rekening
   - No Rekening, Nama Pemilik, Jenis ATM
   - Data disimpan di session: register_step4
   ↓
6. Step 5: Data KTP
   - NIK, Nama Lengkap, Tempat/Tanggal Lahir, Alamat, Jenis Kelamin, File KTP
   - Data disimpan di session: register_step5
   ↓
7. Step 6: Kontak Darurat
   - Semua data kontak darurat + Foto KTP darurat
   - Data disimpan di session: register_step6
   ↓
8. Final Submission (Step 6)
   - Semua data dari session diambil
   - DB Transaction dimulai
   - Create User di tabel users
   - Create NasabahTemp di tbl_nasabah_temp
   - Create PekerjaanTemp, DataRekTemp, DataKtpTemp, DaruratTemp
   - Commit transaction
   - Clear session
   - Redirect ke login dengan success message
```

### Catatan Penting:
- **Data disimpan di session** selama proses registrasi (bukan di database)
- **Final submission** baru menyimpan ke database (tabel temp)
- **Tidak ada approval process** di controller saat ini (data langsung masuk ke temp)
- **User bisa login langsung** setelah registrasi (karena user sudah dibuat di tabel users)

---

## 🔐 FLOW LOGIN

### Step-by-Step Flow:

```
1. User mengakses /login
   ↓
2. User input Email & Password
   ↓
3. Validasi form (email required, password required)
   ↓
4. Auth::attempt($credentials, $remember)
   ↓
5. Jika berhasil:
   - Session regenerate
   - Cek role user
   - Redirect berdasarkan role:
     * nasabah → /nasabah/dashboard
     * admin_operasional / admin_utama → /admin/dashboard
     * lainnya → /
   ↓
6. Jika gagal:
   - Return error: "Email atau password yang Anda masukkan salah."
   - Keep input email
```

### Fitur:
- ✅ Remember me (checkbox)
- ✅ Session regeneration untuk security
- ✅ Role-based redirect
- ✅ Error handling

---

## 🎮 CONTROLLERS

### 1. `LoginController`

#### Method: `showLoginForm()`
**Status**: ✅ Berfungsi
- Menampilkan view `auth.login`
- Tidak ada logic khusus

#### Method: `login(Request $request)`
**Status**: ✅ Berfungsi
- Validasi: email (required, email), password (required)
- Auth attempt dengan remember me support
- Session regeneration
- Role-based redirect:
  - `nasabah` → `nasabah.dashboard`
  - `admin_operasional` / `admin_utama` → `/admin/dashboard`
  - default → `/`
- Error handling dengan pesan Indonesia

#### Method: `logout(Request $request)`
**Status**: ✅ Berfungsi
- Auth logout
- Session invalidate
- Session regenerate token
- Redirect ke `/`

### 2. `RegisterController`

#### Method: `showRegistrationForm(Request $request)`
**Status**: ✅ Berfungsi
- Menerima parameter `step` (1-6)
- Validasi step (default: 1)
- Return view dengan step

#### Method: `register(Request $request)`
**Status**: ⚠️ Ada Masalah

**Step 1: Data Diri**
- ✅ Validasi: nama, email (unique), nomor_hp, password (min 8, confirmed), foto (optional)
- ✅ Upload foto ke `storage/app/public/profiles`
- ✅ Simpan data di session `register_step1`
- ✅ Redirect ke step 2

**Step 2: Detail Nasabah**
- ✅ Validasi: no_kk (unique di tbl_nasabah_temp), tempat_lahir, tanggal_lahir, jenis_kelamin, alamat, foto_ktp/kk (optional)
- ✅ Upload foto ke `storage/app/public/ktp`
- ✅ Simpan data di session `register_step2`
- ✅ Redirect ke step 3
- ⚠️ **Masalah**: Validasi `unique:tbl_nasabah_temp` tapi data belum ada di database

**Step 3: Data Pekerjaan**
- ✅ Validasi: semua field nullable
- ✅ Simpan data di session `register_step3`
- ✅ Redirect ke step 4

**Step 4: Data Rekening**
- ✅ Validasi: no_rekening (unique di tbl_data_rek_temp), nama_pemilik_rekening, jenis_atm
- ✅ Simpan data di session `register_step4`
- ✅ Redirect ke step 5
- ⚠️ **Masalah**: Validasi `unique:tbl_data_rek_temp` tapi data belum ada

**Step 5: Data KTP**
- ✅ Validasi: nik (unique di tbl_data_ktp_temp), nama_lengkap, tempat_lahir, tanggal_lahir, alamat, jenis_kelamin, file_ktp (required)
- ✅ Upload file KTP ke `storage/app/public/ktp`
- ✅ Simpan data di session `register_step5`
- ✅ Redirect ke step 6
- ⚠️ **Masalah**: Validasi `unique:tbl_data_ktp_temp` tapi data belum ada

**Step 6: Kontak Darurat**
- ✅ Validasi: semua field required (kecuali optional di label)
- ✅ Upload foto KTP darurat
- ✅ Simpan data di session `register_step6`
- ✅ **Final Submission**:
  - DB Transaction
  - Create User di `users` (bukan users_temp!)
  - Create NasabahTemp di `tbl_nasabah_temp`
  - Create PekerjaanTemp, DataRekTemp, DataKtpTemp, DaruratTemp
  - Commit transaction
  - Clear session
  - Redirect ke login

**Masalah yang Ditemukan:**
1. ⚠️ **Inkonsistensi**: Data user langsung masuk ke `users` (bukan `users_temp`), tapi data nasabah masuk ke `*_temp`
2. ⚠️ **Validasi Unique**: Validasi unique di step 2, 4, 5 menggunakan tabel temp, tapi data belum ada
3. ⚠️ **Tidak Ada Approval Process**: User bisa langsung login setelah registrasi
4. ⚠️ **Field Alamat**: Migration `tbl_nasabah_temp` tidak punya field `alamat`, tapi model dan controller menggunakannya

---

## 👁️ VIEWS

### 1. `auth/login.blade.php`
**Status**: ✅ Sudah Ada
- Form login dengan email & password
- Remember me checkbox
- Link ke register
- Link "Lupa password?" (belum ada handler)
- Error display
- Success message display
- Styling dengan Tailwind CSS
- Responsive design

### 2. `auth/register.blade.php`
**Status**: ✅ Sudah Ada
- Multi-step form (6 steps)
- Progress indicator dengan visual step
- Step labels:
  1. Data Diri
  2. Detail Nasabah
  3. Pekerjaan
  4. Rekening
  5. Data KTP
  6. Kontak Darurat (Optional)
- Form validation display
- Image preview untuk upload foto
- Navigation: Next/Previous buttons
- Styling dengan Tailwind CSS
- Responsive design

---

## 🛣️ ROUTES

### Authentication Routes:

| Route | Method | Controller | Status |
|-------|--------|------------|--------|
| `/register` | GET | RegisterController::showRegistrationForm | ✅ |
| `/register` | POST | RegisterController::register | ✅ |
| `/login` | GET | LoginController::showLoginForm | ✅ |
| `/login` | POST | LoginController::login | ✅ |
| `/logout` | POST | LoginController::logout | ✅ |

**Catatan**: Routes tidak menggunakan middleware auth (public access).

---

## ✅ STATUS IMPLEMENTASI

### Login System:
- ✅ Form login sudah ada
- ✅ Validasi sudah ada
- ✅ Authentication sudah berfungsi
- ✅ Role-based redirect sudah ada
- ✅ Remember me sudah ada
- ✅ Logout sudah ada
- ⚠️ Lupa password belum ada handler

### Register System:
- ✅ Multi-step form sudah ada (6 steps)
- ✅ Session storage sudah ada
- ✅ Validasi per step sudah ada
- ✅ File upload sudah ada
- ✅ Final submission sudah ada
- ✅ DB transaction sudah ada
- ⚠️ Inkonsistensi: User langsung masuk ke `users` (bukan temp)
- ⚠️ Tidak ada approval process
- ⚠️ Validasi unique di tabel temp sebelum data ada

---

## ⚠️ MASALAH YANG DITEMUKAN

### 1. **Inkonsistensi Data Storage** 🔴 KRITIS
**Lokasi**: `RegisterController::register()` step 6
**Masalah**: 
- User langsung dibuat di tabel `users` (bukan `users_temp`)
- Tapi data nasabah dibuat di `tbl_nasabah_temp`
- Ini membuat user bisa langsung login, padahal data nasabah belum di-approve

**Dampak**: 
- User bisa login sebelum data nasabah di-approve admin
- Inkonsistensi antara user dan nasabah data

**Solusi**: 
- Buat user di `users_temp` dulu
- Setelah admin approve, baru pindahkan ke `users`
- Atau buat flag `status` di user untuk menandai pending approval

### 2. **Validasi Unique di Tabel Temp** 🟡 PENTING
**Lokasi**: Step 2, 4, 5 di `RegisterController`
**Masalah**: 
- Validasi `unique:tbl_nasabah_temp` di step 2, padahal data belum ada
- Validasi `unique:tbl_data_rek_temp` di step 4
- Validasi `unique:tbl_data_ktp_temp` di step 5

**Dampak**: 
- Validasi akan selalu pass (karena tabel masih kosong atau data user lain)
- Tidak bisa prevent duplicate dari user yang sama yang registrasi ulang

**Solusi**: 
- Validasi unique juga terhadap tabel utama (`tbl_nasabah`, `tbl_data_rek`, `tbl_data_ktp`)
- Atau cek di session apakah user sudah input data tersebut sebelumnya

### 3. **Field Alamat Tidak Ada di Migration** 🟡 PENTING
**Lokasi**: `tbl_nasabah_temp` migration
**Masalah**: 
- Migration tidak punya field `alamat`
- Tapi model `NasabahTemp` punya `alamat` di fillable
- Controller juga menggunakan `alamat` di step 2

**Dampak**: 
- Error saat insert data (field tidak ada)

**Solusi**: 
- Tambahkan field `alamat` di migration `tbl_nasabah_temp`
- Atau hapus dari model fillable jika memang tidak diperlukan

### 4. **Tidak Ada Approval Process** 🟡 PENTING
**Masalah**: 
- Setelah registrasi, user langsung bisa login
- Tidak ada mekanisme admin untuk approve data nasabah dari temp ke tabel utama

**Dampak**: 
- Data nasabah di temp tidak pernah dipindah ke tabel utama
- User bisa login tapi tidak punya data nasabah aktif

**Solusi**: 
- Buat sistem approval di admin panel
- Pindahkan data dari temp ke tabel utama setelah approve

### 5. **Lupa Password Belum Ada** 🟢 MINOR
**Lokasi**: `auth/login.blade.php` line 81
**Masalah**: 
- Link "Lupa password?" ada tapi belum ada handler

**Solusi**: 
- Buat route dan controller untuk reset password
- Atau hapus link jika tidak diperlukan

### 6. **Tidak Ada Middleware Auth di Routes** 🟡 PENTING
**Lokasi**: `routes/web.php`
**Masalah**: 
- Routes nasabah tidak protected dengan auth middleware
- Siapa saja bisa akses routes nasabah

**Solusi**: 
- Tambahkan middleware auth di routes nasabah
- Redirect ke login jika belum login

---

## 🔧 REKOMENDASI PERBAIKAN

### Prioritas Tinggi:

1. **Perbaiki Inkonsistensi Data Storage**
   - Buat user di `users_temp` dulu
   - Setelah admin approve, baru pindahkan ke `users`
   - Atau tambahkan flag `status` di user untuk pending approval

2. **Tambahkan Field Alamat di Migration**
   - Run migration untuk menambahkan field `alamat` di `tbl_nasabah_temp`
   - Atau hapus dari model jika tidak diperlukan

3. **Perbaiki Validasi Unique**
   - Validasi unique juga terhadap tabel utama
   - Atau gunakan validasi custom untuk cek di session

4. **Tambahkan Middleware Auth**
   - Protect routes nasabah dengan auth middleware
   - Redirect ke login jika belum login

### Prioritas Sedang:

5. **Buat Sistem Approval**
   - Admin panel untuk approve data nasabah dari temp
   - Pindahkan data dari temp ke tabel utama setelah approve
   - Update status user setelah approve

6. **Perbaiki Flow Registrasi**
   - Setelah registrasi, user tidak bisa login sampai di-approve
   - Tampilkan status "Menunggu Approval" di halaman khusus

### Prioritas Rendah:

7. **Implementasi Lupa Password**
   - Buat route dan controller untuk reset password
   - Kirim email reset link
   - Atau hapus link jika tidak diperlukan

8. **Tambah Email Verification**
   - Kirim email verifikasi setelah registrasi
   - Verifikasi email sebelum bisa login

---

## 📝 KESIMPULAN

### Status Sistem: **70% Berfungsi**

**Yang Sudah Baik:**
- ✅ Form login dan register sudah lengkap
- ✅ Multi-step registration sudah berfungsi
- ✅ Session storage sudah bekerja
- ✅ File upload sudah berfungsi
- ✅ Authentication sudah berfungsi
- ✅ Role-based redirect sudah ada
- ✅ UI/UX sudah bagus

**Yang Perlu Diperbaiki:**
- 🔴 Inkonsistensi data storage (KRITIS)
- 🟡 Field alamat tidak ada di migration (PENTING)
- 🟡 Validasi unique tidak tepat (PENTING)
- 🟡 Tidak ada approval process (PENTING)
- 🟡 Tidak ada middleware auth (PENTING)
- 🟢 Lupa password belum ada (MINOR)

**Estimasi Waktu Perbaikan:**
- Prioritas Tinggi: 2-3 jam
- Prioritas Sedang: 3-4 jam
- Prioritas Rendah: 1-2 jam

**Total: 6-9 jam untuk perbaikan lengkap**

---

**Dokumen ini dibuat untuk membantu memahami sistem login dan register yang sudah ada.**
