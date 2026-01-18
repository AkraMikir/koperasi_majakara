# 📊 ANALISIS LENGKAP SISTEM PENARIKAN TABUNGAN

**Tanggal Analisis**: 18 Januari 2026  
**Lokasi Project**: `d:\project\koperasi_majakara`  
**Sistem**: Penarikan Tabungan (Withdrawal System) - Nasabah & Admin

---

## 📋 DAFTAR ISI

1. [Overview Sistem](#overview-sistem)
2. [Struktur Database](#struktur-database)
3. [Flow Sistem Penarikan](#flow-sistem-penarikan)
4. [File dan Komponen](#file-dan-komponen)
5. [Fitur yang Sudah Berjalan](#fitur-yang-sudah-berjalan)
6. [Masalah yang Ditemukan](#masalah-yang-ditemukan)
7. [Rekomendasi Perbaikan](#rekomendasi-perbaikan)
8. [Perbandingan dengan Sistem Setoran](#perbandingan-dengan-sistem-setoran)

---

## 🔍 OVERVIEW SISTEM

### Deskripsi
Sistem penarikan tabungan memungkinkan nasabah untuk melakukan pengajuan penarikan dana dari tabungan mereka. Sistem ini memiliki dua metode penarikan:
1. **Tunai** - Nasabah mengambil uang langsung di kantor
2. **Transfer** - Dana ditransfer ke rekening nasabah

### Karakteristik Utama
- ✅ **Approval System**: Semua penarikan memerlukan persetujuan admin
- ✅ **Saldo Validation**: Sistem memvalidasi saldo sebelum pengajuan
- ✅ **Multi-Method**: Mendukung penarikan tunai dan transfer
- ✅ **Transaction History**: Semua penarikan tercatat di `trans_tabungan`
- ⚠️ **Tidak Ada PIN Verification**: Berbeda dengan setoran, penarikan tidak memerlukan PIN verification

---

## 🗄️ STRUKTUR DATABASE

### 1. `tbl_pengajuan_penarikan_tabungan`
**Tujuan**: Menyimpan pengajuan penarikan tabungan dari nasabah

| Field | Type | Keterangan |
|-------|------|------------|
| `id` | bigint | Primary key |
| `id_anggota` | foreignId | FK ke `tbl_nasabah` |
| `tgl_pengajuan` | datetime | Tanggal pengajuan |
| `nominal` | decimal(15,2) | Nominal penarikan |
| `keterangan` | text | Keterangan (opsional), termasuk no rekening jika transfer |
| `status` | enum('1','2','3') | `1`=Pending, `2`=Approved, `3`=Rejected |
| `created_at` | timestamp | Waktu pembuatan |
| `updated_at` | timestamp | Waktu update |

**Relationship**:
- `belongsTo` Nasabah (`id_anggota`)
- `hasMany` TransTabungan (`id_pengajuan_tarik`)

### 2. `trans_tabungan`
**Tujuan**: Menyimpan transaksi penarikan yang sudah approved

| Field | Type | Keterangan |
|-------|------|------------|
| `id` | bigint | Primary key |
| `id_pengajuan_setor` | foreignId | FK ke `tbl_pengajuan_tabungan` (nullable) |
| `id_pengajuan_tarik` | foreignId | FK ke `tbl_pengajuan_penarikan_tabungan` (nullable) |
| `id_anggota` | foreignId | FK ke `tbl_nasabah` |
| `nominal` | decimal(15,2) | Nominal transaksi |
| `keterangan` | text | Keterangan |
| `jenis` | enum | `'setoran'` atau `'penarikan'` |
| `via` | enum | `'transfer'` atau `'cash'` |
| `tgl_transaksi` | timestamp | Tanggal transaksi |

**Catatan**: 
- Transaksi penarikan memiliki `jenis = 'penarikan'`
- `via` diisi berdasarkan metode penarikan (tunai = 'cash', transfer = 'transfer')
- Transaksi ini digunakan untuk menghitung saldo nasabah

---

## 🔄 FLOW SISTEM PENARIKAN

### **Flow Nasabah (Submit Pengajuan)**

```
1. Nasabah Login
   ↓
2. Navigate ke "Penarikan Tabungan"
   ↓
3. Sistem menampilkan:
   - Saldo tersedia (dari getSaldoNasabah())
   - Pilihan metode (Tunai/Transfer)
   - Riwayat penarikan terbaru
   ↓
4. Nasabah memilih metode:
   - **Tunai**: Form muncul tanpa field rekening
   - **Transfer**: Form muncul dengan field rekening
   ↓
5. Nasabah mengisi form:
   - Nominal (min Rp 10.000)
   - Nomor rekening (jika transfer)
   - Keterangan (opsional)
   ↓
6. Validasi Frontend:
   - Check saldo cukup (JavaScript)
   - Format currency
   ↓
7. Submit Form → POST /nasabah/tabungan/penarikan
   ↓
8. Controller: submitPenarikan()
   - Validasi request
   - Check saldo cukup (backend validation)
   - Create PengajuanPenarikanTabungan dengan status '1' (Pending)
   ↓
9. Redirect ke "Status Pengajuan Penarikan"
   ↓
10. Nasabah bisa lihat status pengajuan
```

### **Flow Admin (Approval)**

```
1. Admin Login
   ↓
2. Navigate ke "Pengajuan Penarikan"
   ↓
3. Admin melihat list pengajuan pending
   ↓
4. Admin klik "Detail" → Lihat:
   - Data nasabah
   - Nominal penarikan
   - Saldo nasabah saat ini
   - Keterangan (termasuk no rekening jika transfer)
   ↓
5. Admin memilih aksi:
   
   A. **APPROVE** (approveTarik()):
      - Validasi saldo masih cukup
      - Update status pengajuan ke '2' (Approved)
      - Create TransTabungan dengan:
        * jenis = 'penarikan'
        * via = 'transfer' atau 'cash'
        * nominal = nominal pengajuan
        * id_pengajuan_tarik = id pengajuan
      - Saldo nasabah otomatis berkurang
      ↓
      - Redirect dengan success message
   
   B. **REJECT** (rejectTarik()):
      - Admin wajib isi keterangan penolakan
      - Update status pengajuan ke '3' (Rejected)
      - Update keterangan dengan alasan penolakan
      ↓
      - Redirect dengan success message
```

### **Flow Perhitungan Saldo**

```
Saldo Nasabah = Total Setoran - Total Penarikan

Dimana:
- Total Setoran = SUM(trans_tabungan.nominal) WHERE jenis='setoran' + 
                   SUM(pengajuan approved yang belum ada transaksi)

- Total Penarikan = SUM(trans_tabungan.nominal) WHERE jenis='penarikan'

Method: getSaldoNasabah($idAnggota)
```

---

## 📁 FILE DAN KOMPONEN

### **Controller**

#### 1. `app/Http/Controllers/Nasabah/TabunganController.php`

**Method yang Terlibat**:

```php
// Menampilkan form penarikan
public function penarikanTabungan()
```
- **Input**: Tidak ada
- **Output**: View `penarikan-tabungan.blade.php`
- **Data yang dikirim**:
  - `user`: User yang sedang login
  - `tabunganInfo`: Object dengan saldo, bunga, status
  - `riwayatPenarikan`: 10 transaksi penarikan terbaru

```php
// Submit pengajuan penarikan
public function submitPenarikan(Request $request)
```
- **Validasi**:
  - `metode`: required, in:tunai,transfer
  - `nominal`: required, numeric, min:10000
  - `keterangan`: nullable, string, max:500
  - `no_rekening`: required_if:metode,transfer, string, max:50
- **Logic**:
  1. Get ID anggota dari auth
  2. Check saldo cukup
  3. Create pengajuan dengan status '1' (Pending)
  4. Jika transfer, tambahkan no rekening ke keterangan
- **Output**: Redirect ke status pengajuan dengan success message

```php
// Menampilkan status pengajuan
public function statusPengajuanTarik()
```
- **Output**: View `status-pengajuan-tarik.blade.php`
- **Data**: List semua pengajuan penarikan (paginated)

```php
// Detail pengajuan penarikan
public function detailPengajuanTarik($id)
```
- **Output**: View `detail-pengajuan-tarik.blade.php`
- **Data**: Single pengajuan penarikan

#### 2. `app/Http/Controllers/Admin/TabunganController.php`

**Method yang Terlibat**:

```php
// Approve pengajuan penarikan
public function approveTarik(Request $request, $id)
```
- **Logic**:
  1. Get pengajuan
  2. Check saldo masih cukup (double validation)
  3. Update status ke '2' (Approved)
  4. Create `TransTabungan` dengan jenis='penarikan'
  5. Redirect dengan success

```php
// Reject pengajuan penarikan
public function rejectTarik(Request $request, $id)
```
- **Validasi**: `keterangan` required
- **Logic**:
  1. Update status ke '3' (Rejected)
  2. Update keterangan dengan alasan penolakan
  3. Redirect dengan success

### **Model**

#### 1. `app/Models/PengajuanPenarikanTabungan.php`

**Fillable Fields**:
- `id_anggota`
- `tgl_pengajuan`
- `nominal`
- `keterangan`
- `status`

**Relationships**:
- `belongsTo` Nasabah
- `hasMany` TransTabungan

#### 2. `app/Models/TransTabungan.php`

**Fillable Fields**:
- `id_pengajuan_setor` (nullable)
- `id_pengajuan_tarik` (nullable)
- `id_anggota`
- `nominal`
- `keterangan`
- `jenis` ('setoran' atau 'penarikan')
- `via` ('transfer' atau 'cash')
- `tgl_transaksi`

### **View**

#### 1. `resources/views/nasabah/tabungan/penarikan-tabungan.blade.php`

**Komponen**:
- **Info Saldo**: Menampilkan saldo tersedia
- **Pilihan Metode**: 2 button (Tunai & Transfer)
- **Form Section** (hidden sampai metode dipilih):
  - Input nominal (dengan currency formatter)
  - Input rekening (muncul jika transfer)
  - Textarea keterangan
  - Button submit
- **Riwayat Penarikan**: List 10 penarikan terbaru

**JavaScript**:
- `selectMethod(method)`: Toggle form & rekening section
- `formatCurrency(input)`: Format input ke currency
- `checkSaldo()`: Validasi saldo sebelum submit

#### 2. `resources/views/nasabah/tabungan/status-pengajuan-tarik.blade.php`

**Komponen**:
- **Header**: Title dan description
- **List Pengajuan**: 
  - ID pengajuan
  - Status (badge warna)
  - Tanggal pengajuan
  - Nominal
  - Keterangan
  - Link ke detail
- **Empty State**: Jika belum ada pengajuan

#### 3. `resources/views/nasabah/tabungan/detail-pengajuan-tarik.blade.php`

**Komponen**:
- **Status Card**: Status pengajuan dengan badge
- **Informasi Pengajuan**:
  - Nominal
  - Keterangan
  - Tanggal pengajuan

### **Route**

```php
// Nasabah Routes
Route::get('/penarikan', [TabunganController::class, 'penarikanTabungan'])
     ->name('penarikan');
Route::post('/penarikan', [TabunganController::class, 'submitPenarikan'])
     ->name('submit-penarikan');
Route::get('/status-pengajuan-tarik', [TabunganController::class, 'statusPengajuanTarik'])
     ->name('status-pengajuan-tarik');
Route::get('/pengajuan-tarik/{id}', [TabunganController::class, 'detailPengajuanTarik'])
     ->name('detail-pengajuan-tarik');
```

---

## ✅ FITUR YANG SUDAH BERJALAN

### **Nasabah Side**:

1. ✅ **Tampilkan Halaman Penarikan**
   - Saldo tersedia ditampilkan
   - Pilihan metode (Tunai/Transfer)
   - Riwayat penarikan terbaru

2. ✅ **Form Penarikan**
   - Input nominal dengan currency formatter
   - Input rekening (conditional untuk transfer)
   - Validasi saldo di frontend (JavaScript)
   - Validasi saldo di backend (PHP)

3. ✅ **Submit Pengajuan**
   - Validasi lengkap (nominal, metode, rekening)
   - Check saldo cukup
   - Create pengajuan dengan status '1' (Pending)
   - Redirect ke status pengajuan

4. ✅ **Status Pengajuan**
   - List semua pengajuan (paginated)
   - Tampilkan status dengan badge warna
   - Link ke detail

5. ✅ **Detail Pengajuan**
   - Informasi lengkap pengajuan
   - Status dengan badge

### **Admin Side**:

1. ✅ **List Pengajuan Penarikan**
   - Filter dan search
   - Status pengajuan

2. ✅ **Detail Pengajuan**
   - Data nasabah lengkap
   - Validasi saldo
   - Informasi pengajuan

3. ✅ **Approve Pengajuan**
   - Double validation saldo
   - Update status ke '2'
   - Auto create `TransTabungan`
   - Update saldo nasabah

4. ✅ **Reject Pengajuan**
   - Wajib isi keterangan penolakan
   - Update status ke '3'
   - Update keterangan

---

## ⚠️ MASALAH YANG DITEMUKAN

### 1. **Tidak Ada PIN Verification** ⚠️

**Lokasi**: `submitPenarikan()` method

**Masalah**:
- Berbeda dengan sistem setoran yang memerlukan PIN verification
- Penarikan langsung submit tanpa PIN verification
- Potensi security issue jika session hijacked

**Perbandingan**:
- Setoran → Ada PIN verification popup
- Penarikan → Tidak ada PIN verification

**Dampak**:
- Kurang aman untuk transaksi financial
- Tidak konsisten dengan sistem setoran
- User tidak ada konfirmasi tambahan sebelum submit

**Solusi yang Disarankan**:
Tambahkan PIN verification popup sebelum submit (mirip dengan setoran).

### 2. **Field `via` Di TransTabungan Tidak Konsisten** ⚠️

**Lokasi**: `approveTarik()` method di Admin Controller

**Masalah**:
```php
TransTabungan::create([
    'via' => 'transfer', // Hardcoded!
    ...
]);
```

**Dampak**:
- Semua penarikan dianggap 'transfer' padahal bisa 'tunai'
- Data tidak akurat untuk reporting
- Riwayat transaksi tidak sesuai kenyataan

**Solusi**:
- Simpan metode penarikan di pengajuan (tambah field `metode` di `tbl_pengajuan_penarikan_tabungan`)
- Atau parse dari keterangan (jika ada "Rekening:" berarti transfer)
- Set `via` berdasarkan metode saat create transaksi

### 3. **Tidak Ada Field `metode` di Pengajuan** ⚠️

**Lokasi**: `tbl_pengajuan_penarikan_tabungan` table

**Masalah**:
- Metode penarikan (tunai/transfer) hanya tersimpan di keterangan
- Tidak ada field dedicated untuk metode
- Sulit untuk filter/query berdasarkan metode

**Dampak**:
- Parsing keterangan untuk extract metode (risky)
- Tidak bisa filter pengajuan berdasarkan metode
- Data kurang terstruktur

**Solusi**:
Tambahkan field `metode` (enum: 'tunai', 'transfer') di table `tbl_pengajuan_penarikan_tabungan`.

### 4. **Nomor Rekening Disimpan di Keterangan** ⚠️

**Lokasi**: `submitPenarikan()` method

**Masalah**:
```php
'keterangan' => $request->keterangan . 
    ($request->metode === 'transfer' ? ' | Rekening: ' . $request->no_rekening : ''),
```

**Dampak**:
- Nomor rekening tidak terstruktur
- Sulit untuk extract jika perlu
- Bisa tercampur dengan keterangan lain

**Solusi**:
Tambahkan field `no_rekening` (nullable) di table `tbl_pengajuan_penarikan_tabungan`.

### 5. **Tidak Ada Validasi Nomor Rekening** ⚠️

**Lokasi**: Validasi request di `submitPenarikan()`

**Masalah**:
- Nomor rekening hanya required jika metode transfer
- Tidak ada validasi format rekening (harus numeric)
- Tidak ada validasi panjang rekening

**Solusi**:
Tambahkan validasi:
```php
'no_rekening' => 'required_if:metode,transfer|string|min:10|max:20|regex:/^[0-9]+$/',
```

### 6. **Detail Pengajuan Tidak Menampilkan Metode** ⚠️

**Lokasi**: `detail-pengajuan-tarik.blade.php`

**Masalah**:
- View detail tidak menampilkan metode penarikan (tunai/transfer)
- User/admin tidak tahu metode yang dipilih nasabah

**Solusi**:
Tambahkan display metode penarikan di detail view.

---

## 💡 REKOMENDASI PERBAIKAN

### **Priority 1 (Kritis)** 🔴

1. **Tambahkan PIN Verification**
   - Konsistensi dengan sistem setoran
   - Keamanan transaksi financial
   - Implementasi: Tambahkan popup PIN seperti di setoran

2. **Fix Field `via` di TransTabungan**
   - Set `via` berdasarkan metode penarikan
   - Jangan hardcode 'transfer'
   - Update admin controller untuk set `via` yang benar

3. **Tambahkan Field `metode` di Database**
   - Migration untuk tambah field `metode`
   - Update model dan controller
   - Update view untuk display metode

### **Priority 2 (Penting)** 🟡

4. **Tambahkan Field `no_rekening` di Database**
   - Pisahkan nomor rekening dari keterangan
   - Data lebih terstruktur
   - Mudah untuk query

5. **Validasi Nomor Rekening**
   - Format numeric
   - Panjang minimal/maksimal
   - Regex validation

6. **Update Detail View**
   - Tampilkan metode penarikan
   - Tampilkan nomor rekening (jika transfer)
   - UI lebih informatif

### **Priority 3 (Nice to Have)** 🟢

7. **Tambah Indikator Metode di Status Pengajuan**
   - Badge "Tunai" atau "Transfer" di setiap card
   - Konsisten dengan status setoran

8. **Riwayat Penarikan Per Metode**
   - Filter riwayat berdasarkan metode
   - Summary penarikan per metode

---

## 🔄 PERBANDINGAN DENGAN SISTEM SETORAN

| Aspek | Setoran | Penarikan | Status |
|-------|---------|-----------|--------|
| **PIN Verification** | ✅ Ada | ❌ Tidak ada | ⚠️ Inconsistent |
| **Metode** | Transfer & Janji Temu | Tunai & Transfer | ✅ Sama |
| **Form Terpisah** | ✅ Ada (pengajuan-transfer.blade.php) | ❌ Tidak ada | ⚠️ Bisa diperbaiki |
| **Validasi Saldo** | ✅ Ada | ✅ Ada | ✅ Consistent |
| **Status Badge** | ✅ Ada (Via Transfer/Janji Temu) | ❌ Tidak ada | ⚠️ Bisa ditambahkan |
| **Database Field** | ✅ `foto_bukti_tf` untuk metode | ❌ Tidak ada field metode | ⚠️ Bisa ditambahkan |
| **Admin Approval** | ✅ Ada | ✅ Ada | ✅ Consistent |
| **Auto Create Transaksi** | ✅ Ada | ✅ Ada | ✅ Consistent |

---

## 📊 KESIMPULAN

### **Strengths**:
- ✅ Validasi saldo berjalan dengan baik
- ✅ Flow approval admin sudah lengkap
- ✅ UI/UX sudah baik dan konsisten
- ✅ Perhitungan saldo sudah benar

### **Weaknesses**:
- ⚠️ Tidak ada PIN verification (security concern)
- ⚠️ Metode penarikan tidak tersimpan dengan baik
- ⚠️ Nomor rekening tercampur dengan keterangan
- ⚠️ Field `via` di transaksi hardcoded

### **Recommendation**:
1. **Immediate**: Tambahkan PIN verification
2. **Short-term**: Tambah field `metode` dan `no_rekening` di database
3. **Long-term**: Perbaiki detail view dan tambahkan filter berdasarkan metode

---

**Dokumen ini dibuat untuk keperluan analisis dan perbaikan sistem penarikan tabungan.**
