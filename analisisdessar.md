# ANALISIS LENGKAP SISTEM TABUNGAN DAN PINJAMAN KOPERASI MAJAKARA

> 📅 **Tanggal Analisis:** 3 Februari 2026  
> 🔍 **Tipe Analisis:** Full System Flow - Form, Controller, Model, Database  
> 📋 **Status:** Complete Documentation

---

## 📌 DAFTAR ISI

1. [SISTEM TABUNGAN](#sistem-tabungan)
   - [A. Pengajuan Setoran Transfer](#a-pengajuan-setoran-transfer)
   - [B. Pengajuan Setoran Tunai (Janji Temu)](#b-pengajuan-setoran-tunai)
   - [C. Admin Menerima & Approve Pengajuan](#c-admin-menerima-approve)
   - [D. Riwayat Transaksi Tabungan](#d-riwayat-transaksi)
   - [E. Pengajuan Penarikan Tabungan](#e-pengajuan-penarikan)
   
2. [SISTEM PINJAMAN](#sistem-pinjaman)
   - [A. Pengajuan Pinjaman Transfer](#a-pengajuan-pinjaman-transfer)
   - [B. Pengajuan Pinjaman Tunai (Janji Temu)](#b-pengajuan-pinjaman-tunai)
   - [C. Admin Approve & Cairkan Pinjaman](#c-admin-approve-cairkan)
   - [D. Sistem Tempo & Angsuran](#d-sistem-tempo-angsuran)
   - [E. Pembayaran Angsuran](#e-pembayaran-angsuran)

---

# SISTEM TABUNGAN

## A. PENGAJUAN SETORAN TRANSFER

### 📄 HALAMAN FORM
**File:** `resources/views/nasabah/tabungan/pengajuan-transfer.blade.php`

**Route:** `nasabah.tabungan.pengajuan-transfer`

### 🔍 FORM DATA YANG DIKIRIM

```php
// Method: POST
// Action: route('nasabah.tabungan.submit-setoran')
// Enctype: multipart/form-data

Data yang dikirim:
├── metode: "transfer" (hidden input)
├── nominal: integer (min: 10000) - Format: diinput dengan separator, dikirim tanpa separator
├── bukti_foto[]: array of files (image, max 5MB each) - Bisa multiple file
├── keterangan: string|nullable (max 500 chars)
└── pin: string (6 digit) - Ditambahkan via JavaScript setelah verifikasi
```

**Validasi JavaScript:**
- Nominal minimal Rp 10.000
- Minimal upload 1 bukti transfer
- PIN harus 6 digit
- Format currency otomatis dengan `Intl.NumberFormat('id-ID')`

### 🎯 CONTROLLER - TabunganController (Nasabah)
**File:** `app/Http/Controllers/Nasabah/TabunganController.php`

**Method:** `submitSetoran(Request $request)` (Line 150-239)

#### Alur Proses:

```php
1. Validasi Input:
   ├── pin: required|numeric|digits:6
   ├── nominal: required|numeric|min:10000
   ├── keterangan: nullable|string|max:500
   └── bukti_foto.*: required|image|max:5120

2. Verifikasi PIN:
   ├── Cek user->pin exists
   ├── Convert to integer: (int)$user->pin === (int)$request->pin
   └── Return error jika salah

3. Validasi Bukti Foto:
   └── Minimal 1 file bukti transfer

4. Simpan ke Database:
   a. Tabel: tbl_pengajuan_tabungan
      ├── id_anggota: dari auth()->user()->nasabah->id
      ├── nominal: dari request
      ├── foto_bukti_tf: 'transfer' (indikator transfer)
      ├── keterangan: dari request
      └── status: '1' (Pending)
   
   b. Tabel: tbl_bukti_foto_tabungan (multiple rows)
      ├── id_pengajuan: dari pengajuan yang baru dibuat
      ├── file_photo: path hasil Storage::store('bukti_tabungan', 'public')
      └── jenis: 'tabungan'

5. Redirect ke halaman status pengajuan
```

### 💾 MODEL & DATABASE

#### Model: `PengajuanTabungan`
**File:** `app/Models/PengajuanTabungan.php`

```php
Table: tbl_pengajuan_tabungan

Fillable:
├── id_anggota (FK to tbl_nasabah)
├── nominal (decimal)
├── foto_bukti_tf (string) - 'transfer' atau 'tunai'
├── keterangan (text)
└── status (string) - '1' = Pending, '2' = Rejected, '3' = Approved

Relations:
├── nasabah() -> BelongsTo Nasabah
├── buktiFoto() -> HasMany BuktiFotoTabungan
├── janjiTemu() -> HasOne JanjiTemuTabungan
└── transTabungan() -> HasMany TransTabungan (via id_pengajuan_setor)
```

#### Model: `BuktiFotoTabungan`
**File:** `app/Models/BuktiFotoTabungan.php`

```php
Table: tbl_bukti_foto_tabungan

Fillable:
├── id_pengajuan (FK to tbl_pengajuan_tabungan)
├── file_photo (string) - Path ke storage/app/public/bukti_tabungan/
└── jenis (string) - 'tabungan'

Relations:
└── pengajuan() -> BelongsTo PengajuanTabungan
```

---

## B. PENGAJUAN SETORAN TUNAI (JANJI TEMU)

### 📄 HALAMAN FORM
**File:** `resources/views/nasabah/tabungan/janji-temu.blade.php`

**Route:** `nasabah.tabungan.janji-temu`

### 🔍 FORM DATA YANG DIKIRIM

```php
// Method: POST
// Action: route('nasabah.tabungan.submit-janji-temu')

Data yang dikirim:
├── pin: string (6 digit)
├── nominal: integer (min: 10000)
├── lokasi_temu: integer (FK to jns_lokasi_perusahaan)
├── tanggal_janji_temu: date (after: today)
├── waktu_janji_temu: time (format H:i)
└── keterangan: string|nullable (max 500 chars)
```

### 🎯 CONTROLLER - TabunganController (Nasabah)

**Method:** `submitJanjiTemu(Request $request)` (Line 258-350)

#### Alur Proses:

```php
1. Validasi Input:
   ├── pin: required|numeric|digits:6
   ├── nominal: required|numeric|min:10000
   ├── lokasi_temu: required|exists:jns_lokasi_perusahaan,id
   ├── tanggal_janji_temu: required|date|after:today
   ├── waktu_janji_temu: required|date_format:H:i
   └── keterangan: nullable|string|max:500

2. Verifikasi PIN (sama seperti transfer)

3. Simpan ke Database:
   a. Tabel: tbl_pengajuan_tabungan
      ├── id_anggota
      ├── foto_bukti_tf: 'tunai' (indikator tunai)
      ├── keterangan
      └── status: '1' (Pending)
      ⚠️ CATATAN: nominal TIDAK disimpan di tabel pengajuan untuk tunai
   
   b. Tabel: tbl_janji_temu_tabungan
      ├── id_pengajuan: dari pengajuan yang baru dibuat
      ├── lokasi_temu: dari request
      ├── nominal: dari request (⭐ nominal disimpan di sini)
      ├── tanggal_janji_temu: gabungan tanggal + waktu
      └── waktu_janji_temu: gabungan tanggal + waktu

4. Redirect ke status pengajuan
```

### 💾 MODEL - JanjiTemuTabungan
**File:** `app/Models/JanjiTemuTabungan.php`

```php
Table: tbl_janji_temu_tabungan

Fillable:
├── id_pengajuan (FK to tbl_pengajuan_tabungan)
├── lokasi_temu (FK to jns_lokasi_perusahaan)
├── nominal (decimal) - Nominal disimpan di SINI untuk tunai
├── tanggal_janji_temu (datetime)
└── waktu_janji_temu (datetime)

Relations:
├── pengajuan() -> BelongsTo PengajuanTabungan
└── lokasi() -> BelongsTo JnsLokasiPerusahaan
```

---

## C. ADMIN MENERIMA & APPROVE PENGAJUAN

### 📄 HALAMAN DAFTAR PENGAJUAN
**File:** `resources/views/admin/tabungan/pengajuan-setor.blade.php`

**Route:** `admin.tabungan.pengajuan-setor`

### 🎯 CONTROLLER - TabunganController (Admin)
**File:** `app/Http/Controllers/Admin/TabunganController.php`

#### Method 1: `pengajuanSetor(Request $request)` (Line 63-88)

```php
// Menampilkan list pengajuan setoran

Query:
├── Ambil dari: tbl_pengajuan_tabungan
├── With relations: nasabah.user, buktiFoto, janjiTemu
├── Default filter: status = '1' (Pending)
└── Pagination: 15 items per page

Filter tersedia:
├── status: '' (all) / '1' (pending) / '2' (rejected) / '3' (approved)
└── search: by nama atau email nasabah
```

#### Method 2: `detailPengajuanSetor($id)` (Line 93-99)

```php
// Menampilkan detail pengajuan

Data yang ditampilkan:
├── Pengajuan data (tbl_pengajuan_tabungan)
├── Data nasabah lengkap (user, dataKtp, dataRek)
├── Bukti foto (jika transfer)
└── Data janji temu (jika tunai)
```

### 📋 PROSES APPROVE PENGAJUAN

#### Method: `approveSetor(Request $request, $id)` (Line 104-147)

```php
Alur Approve:

1. Ambil data pengajuan dengan relations (buktiFoto, janjiTemu, transTabungan)

2. Tentukan Nominal:
   ├── Jika ada pengajuan->nominal: gunakan itu
   └── Jika nominal = 0 DAN ada janjiTemu: gunakan janjiTemu->nominal
   
3. Validasi nominal >= 10000

4. Update Status Pengajuan:
   └── status: '3' (Approved)

5. Buat Transaksi Tabungan (JIKA belum ada):
   
   Tabel: trans_tabungan
   ├── id_transaksi: generate dengan format YYYYMMDD-SEQ-TAB
   ├── id_pengajuan_setor: id pengajuan
   ├── id_anggota: dari pengajuan
   ├── id_jns_akun: ambil dari jns_akun (kode_akun = 'TAB')
   ├── nominal: yang sudah ditentukan
   ├── keterangan: dari pengajuan
   ├── jenis: 'setoran'
   ├── via: 'cash' (jika janji temu) atau 'transfer'
   └── tgl_transaksi: now()

⚠️ IMPORTANT: 
- Cek dulu apakah sudah ada transaksi (pengajuan->transTabungan->count() == 0)
- Jangan buat transaksi duplikat
```

### 💾 MODEL - TransTabungan
**File:** `app/Models/TransTabungan.php`

```php
Table: trans_tabungan

Fillable:
├── id_transaksi (string) - Format: YYYYMMDD-SEQ-TAB
├── id_pengajuan_setor (FK to tbl_pengajuan_tabungan)
├── id_pengajuan_tarik (FK to tbl_pengajuan_penarikan_tabungan)
├── id_anggota (FK to tbl_nasabah)
├── id_jns_akun (FK to jns_akun)
├── nominal (decimal)
├── keterangan (text)
├── jenis (enum) - 'setoran' atau 'penarikan'
├── via (enum) - 'transfer' atau 'cash'
└── tgl_transaksi (datetime)

Relations:
├── nasabah() -> BelongsTo Nasabah
├── pengajuanSetor() -> BelongsTo PengajuanTabungan
├── pengajuanTarik() -> BelongsTo PengajuanPenarikanTabungan
└── jnsAkun() -> BelongsTo JnsAkun

Static Method:
└── generateIdTransaksi($prefix) -> Generate ID transaksi unique
```

---

## D. RIWAYAT TRANSAKSI TABUNGAN

### 📄 HALAMAN NASABAH - Riwayat Transaksi

**Route:** `nasabah.tabungan.index`

**Controller Method:** `TabunganController@index` (Line 20-53)

```php
Data yang ditampilkan:

1. Saldo Tabungan:
   └── Dihitung dari method: getSaldoNasabah($idAnggota)
   
   Rumus Saldo:
   ├── Total Setoran = SUM(trans_tabungan WHERE jenis='setoran')
   ├── Total Penarikan = SUM(trans_tabungan WHERE jenis='penarikan')
   ├── Pengajuan Approved (belum jadi transaksi):
   │   └── SUM(nominal dari pengajuan status='2' yang belum punya transaksi)
   └── Saldo = (Total Setoran + Pengajuan Approved) - Total Penarikan

2. Transaksi Tabungan (Latest 10):
   ├── Dari: trans_tabungan
   ├── Filter: id_anggota, jenis='setoran'
   └── Order: latest tgl_transaksi

3. Riwayat Janji Temu (Latest 10):
   ├── Dari: tbl_janji_temu_tabungan
   ├── Join: tbl_pengajuan_tabungan, jns_lokasi_perusahaan
   └── Order: latest tanggal_janji_temu
```

### 📄 HALAMAN ADMIN - Transaksi Tabungan

**Route:** `admin.tabungan.transaksi`

**Controller Method:** `TabunganController@transaksi` (Line 292-323)

```php
Filter tersedia:
├── jenis: 'setoran' / 'penarikan'
├── tanggal_dari: date
├── tanggal_sampai: date
└── search: by nama/email nasabah

Pagination: 20 items per page
```

---

## E. PENGAJUAN PENARIKAN TABUNGAN

### 📄 HALAMAN FORM
**File:** `resources/views/nasabah/tabungan/penarikan-tabungan.blade.php`

**Route:** `nasabah.tabungan.penarikan`

### 🔍 FORM DATA YANG DIKIRIM

```php
// Method: POST
// Action: route('nasabah.tabungan.submit-penarikan')

Data:
├── metode: 'tunai' atau 'transfer'
├── nominal: integer (min: 10000)
├── keterangan: string|nullable
├── nama_bank: required_if metode='transfer'
└── no_rekening: required_if metode='transfer'
```

### 🎯 CONTROLLER - submitPenarikan (Line 355-389)

```php
Alur:

1. Validasi Input + Cek Saldo:
   ├── Hitung saldo dengan getSaldoNasabah()
   ├── Jika saldo < nominal: return error
   └── Validasi rekening (jika transfer)

2. Simpan ke Database:
   
   Tabel: tbl_pengajuan_penarikan_tabungan
   ├── id_anggota
   ├── tgl_pengajuan: now()
   ├── nominal
   ├── metode_transfer: 'tunai' atau 'transfer'
   ├── nama_bank: (jika transfer)
   ├── no_rekening: (jika transfer)
   ├── keterangan
   └── status: '1' (Pending)

3. Redirect ke status pengajuan penarikan
```

### 💾 MODEL - PengajuanPenarikanTabungan
**File:** `app/Models/PengajuanPenarikanTabungan.php`

```php
Table: tbl_pengajuan_penarikan_tabungan

Fillable:
├── id_anggota
├── tgl_pengajuan
├── nominal
├── metode_transfer ('tunai' / 'transfer')
├── no_rekening
├── nama_bank
├── foto_bukti_tf_admin (diisi admin saat approve transfer)
├── keterangan
└── status ('1' = Pending, '2' = Approved, '3' = Rejected)

Relations:
├── nasabah() -> BelongsTo Nasabah
└── transTabungan() -> HasMany TransTabungan (via id_pengajuan_tarik)
```

### 🎯 ADMIN APPROVE PENARIKAN

**Route:** `admin.tabungan.pengajuan-tarik`

**Controller Method:** `approveTarik(Request $request, $id)` (Line 215-268)

```php
Alur Approve Penarikan:

1. Validasi (jika metode = 'transfer'):
   ├── foto_bukti_tf_admin: required|image|max:5120
   └── bank_pengirim: required|string

2. Cek Saldo:
   ├── Hitung saldo nasabah
   └── Jika saldo < nominal: return error

3. Upload Bukti TF Admin (jika transfer):
   └── Store ke: storage/app/public/bukti_tf_admin/

4. Update Pengajuan:
   ├── status: '2' (Approved)
   └── foto_bukti_tf_admin: path file

5. Buat Transaksi Penarikan:
   
   Tabel: trans_tabungan
   ├── id_transaksi: generate
   ├── id_pengajuan_tarik: id pengajuan
   ├── id_anggota
   ├── id_jns_akun
   ├── nominal
   ├── keterangan
   ├── jenis: 'penarikan'
   ├── via: 'transfer' atau 'cash'
   └── tgl_transaksi: now()

6. Redirect dengan success message
```

---

# SISTEM PINJAMAN

## A. PENGAJUAN PINJAMAN TRANSFER

### 📄 HALAMAN FORM
**File:** `resources/views/nasabah/pinjaman/pengajuan-transfer.blade.php`

**Route:** `nasabah.pinjaman.pengajuan-transfer`

### 🔍 FORM DATA YANG DIKIRIM

```php
// Method: POST
// Action: route('nasabah.pinjaman.submit-pengajuan-transfer')

Data:
├── nominal: integer (min: 100000)
├── durasi: integer (1-24 bulan)
├── pin: string (6 digit)
└── keterangan: string|nullable
```

**Fitur Tambahan:**
- Simulasi angsuran (AJAX): Hitung bunga & angsuran per bulan
- Get bunga dari master data berdasarkan durasi

### 🎯 CONTROLLER - PinjamanController (Nasabah)
**File:** `app/Http/Controllers/Nasabah/PinjamanController.php`

**Method:** `submitPengajuanTransfer(Request $request)` (Line 242-332)

```php
Alur:

1. Clean Nominal (hapus format rupiah)

2. Validasi:
   ├── nominal: required|numeric|min:100000
   ├── durasi: required|integer|min:1|max:24
   ├── pin: required|numeric|digits:6
   └── keterangan: nullable|string|max:500

3. Verifikasi PIN

4. Simpan ke Database:
   
   Tabel: tbl_pengajuan_pinjaman
   ├── id_anggota
   ├── tgl_pengajuan: now()
   ├── nominal
   ├── jenis: 'bulanan' (hanya bulanan)
   ├── durasi
   ├── jenis_pencairan: 'transfer'
   ├── status: '1' (Pending)
   └── keterangan

5. Redirect ke daftar pengajuan
```

### 💾 MODEL - PengajuanPinjaman
**File:** `app/Models/PengajuanPinjaman.php`

```php
Table: tbl_pengajuan_pinjaman

Fillable:
├── id_anggota
├── tgl_pengajuan
├── nominal
├── jenis ('bulanan' / 'mingguan') - Saat ini hanya bulanan
├── durasi (integer) - Dalam bulan
├── jenis_pencairan ('transfer' / 'cash')
├── status (string)
│   ├── '1' = Pending (belum direview)
│   ├── '2' = Ditolak
│   ├── '3' = Disetujui (belum dicairkan)
│   └── '4' = Terlaksana (sudah dicairkan)
└── keterangan

Relations:
├── nasabah() -> BelongsTo Nasabah
├── pinjaman() -> HasOne PinjamanH (dibuat setelah dicairkan)
├── janjiTemu() -> HasOne JanjiTemuPinjaman
└── buktiFoto() -> HasMany BuktiFotoPinjaman
```

---

## B. PENGAJUAN PINJAMAN TUNAI (JANJI TEMU)

### 📄 HALAMAN FORM
**File:** `resources/views/nasabah/pinjaman/janji-temu.blade.php`

**Route:** `nasabah.pinjaman.janji-temu`

### 🔍 FORM DATA

```php
Data:
├── nominal: integer (min: 100000)
├── durasi: integer (1-24)
├── pin: string (6 digit)
├── lokasi_temu: integer (FK jns_lokasi_perusahaan)
├── tanggal_janji_temu: date (after: today)
├── waktu_janji_temu: time (H:i)
└── keterangan: nullable
```

### 🎯 CONTROLLER

**Method:** `submitJanjiTemuPinjaman(Request $request)` (Line 402-511)

```php
Alur:

1. Validasi semua input + PIN

2. Simpan ke Database:
   
   a. Tabel: tbl_pengajuan_pinjaman
      ├── id_anggota
      ├── tgl_pengajuan: now()
      ├── nominal
      ├── jenis: 'bulanan'
      ├── durasi
      ├── jenis_pencairan: 'cash'
      ├── status: '1'
      └── keterangan
   
   b. Tabel: tbl_janji_temu_pinjaman
      ├── id_pengajuan
      ├── lokasi_temu
      ├── nominal
      ├── tanggal_janji_temu
      ├── waktu_janji_temu
      └── keterangan

3. Redirect
```

---

## C. ADMIN APPROVE & CAIRKAN PINJAMAN

### 🎯 STEP 1: APPROVE PENGAJUAN 

**Route:** `admin.pinjaman.detail-pengajuan`

**Controller Method:** `approvePengajuan(Request $request, $id)` (Line 123-155)

```php
Proses Approve (Status 1 -> 3):

1. Cek status = '1' (pending)

2. Get Bunga dari Master Data:
   └── MasterBungaPinjaman::getBungaByDurasi($durasi)

3. Get Denda dari Master Data:
   └── MasterDendaPinjaman::getDendaAktif()

4. Update Pengajuan:
   ├── status: '3' (Disetujui)
   └── bunga_persen: dari master data

⚠️ CATATAN:
- Belum membuat data pinjaman
- Hanya update status & simpan bunga_persen
- Akan dicairkan di step berikutnya
```

### 🎯 STEP 2: CAIRKAN PINJAMAN

**Controller Method:** `cairkanPinjaman(Request $request, $id)` (Line 161-244)

```php
Proses Pencairan (Status 3 -> 4):

Input:
├── tgl_cair: required|date
└── bukti_transfer: nullable|image|max:5120

1. Cek status = '3' (disetujui)

2. Cek apakah sudah punya pinjaman (avoid duplicate)

3. Hitung Bunga & Nominal:
   ├── nominal = pengajuan->nominal
   ├── bunga_persen = dari MasterBungaPinjaman
   ├── bunga_rp = (nominal × bunga_persen) / 100
   └── jumlah_pinjam = nominal (yang diterima nasabah)

4. Buat Pinjaman (BEGIN TRANSACTION):
   
   Tabel: tbl_pinjaman_h
   ├── id_anggota
   ├── id_pengajuan
   ├── jumlah_pinjam (decimal)
   ├── lama_pinjam (integer) = durasi
   ├── jenis: 'bulanan'
   ├── bunga (decimal) = bunga_persen / 100
   ├── bunga_rp (decimal)
   ├── denda_persen (dari MasterDendaPinjaman)
   ├── tgl_pinjam = tgl_cair
   ├── status: 'telaksana'
   └── lunas: 'belum'

5. Generate Jadwal Angsuran:
   └── Panggil method: generateJadwalAngsuran($pinjaman)

6. Upload bukti transfer (jika ada)

7. Update pengajuan:
   ├── status: '4' (Terlaksana)
   └── tgl_cair

8. COMMIT TRANSACTION

9. Redirect ke detail pinjaman
```

### 💾 MODEL - PinjamanH
**File:** `app/Models/PinjamanH.php`

```php
Table: tbl_pinjaman_h

Fillable:
├── id_anggota
├── id_pengajuan
├── jumlah_pinjam (decimal) - Jumlah yang diterima nasabah
├── lama_pinjam (integer) - Durasi dalam bulan
├── jenis ('bulanan' / 'mingguan')
├── bunga (decimal) - Bunga dalam decimal (misal 0.15 = 15%)
├── bunga_rp (decimal) - Total bunga dalam rupiah
├── denda_persen (decimal) - Persen denda per hari (misal 0.30 = 0.3%)
├── tgl_pinjam (datetime)
├── status ('pencairan' / 'telaksana')
├── lunas ('belum' / 'lunas')
└── foto_bukti_transfer, foto_serah_terima

Relations:
├── nasabah() -> BelongsTo Nasabah
├── pengajuan() -> BelongsTo PengajuanPinjaman
├── tempoBulanan() -> HasMany TempoPinjamanB
├── tempoMingguan() -> HasMany TempoPinjamanM
└── buktiFoto() -> HasMany BuktiFotoPinjaman
```

---

## D. SISTEM TEMPO & ANGSURAN

### 🎯 GENERATE JADWAL ANGSURAN

**Controller Method:** `generateJadwalAngsuran(PinjamanH $pinjaman)` (Line 383-416)

```php
Sistem Perhitungan (REVISI TERBARU):

Input:
├── jumlah_pinjam = nominal yang diterima nasabah
├── bunga_rp = total bunga
├── lama_pinjam = durasi (bulan)

Perhitungan:
├── pokok_per_bulan = jumlah_pinjam / lama_pinjam
├── bunga_per_bulan = bunga_rp / lama_pinjam
└── total_per_angsuran = pokok_per_bulan + bunga_per_bulan

Generate Loop (untuk setiap bulan):

Tabel: tempo_pinjaman_b (untuk bulanan)
├── pinjaman_id
├── anggota_id
├── no_urut (1, 2, 3, ...)
├── tgl_jatuh_tempo = tgl_pinjam + i bulan
├── jumlah_tagihan = total_per_angsuran (rounded)
├── jumlah_terbayar: 0
├── denda: 0
└── status_bayar: 'belum'

Contoh:
Pinjaman: Rp 3.000.000
Durasi: 3 bulan
Bunga: 15% = Rp 450.000

Pokok/bulan = 3.000.000 / 3 = Rp 1.000.000
Bunga/bulan = 450.000 / 3 = Rp 150.000
Total/bulan = Rp 1.150.000

Angsuran 1: Rp 1.150.000 (JT: tgl_pinjam + 1 bulan)
Angsuran 2: Rp 1.150.000 (JT: tgl_pinjam + 2 bulan)  
Angsuran 3: Rp 1.150.000 (JT: tgl_pinjam + 3 bulan)
```

### 💾 MODEL - TempoPinjamanB

```php
Table: tempo_pinjaman_b

Fillable:
├── pinjaman_id (FK to tbl_pinjaman_h)
├── anggota_id (FK to tbl_nasabah)
├── no_urut (integer) - Angsuran ke-berapa
├── tgl_jatuh_tempo (datetime)
├── jumlah_tagihan (decimal) - Pokok + bunga per bulan
├── jumlah_terbayar (decimal) - Total yang sudah dibayar
├── denda (decimal) - Denda keterlambatan
└── status_bayar (enum)
    ├── 'belum' - Belum dibayar
    ├── 'telat' - Sudah telat tapi belum lunas
    └── 'lunas' - Sudah lunas

Relations:
├── pinjaman() -> BelongsTo PinjamanH
└── nasabah() -> BelongsTo Nasabah
```

### 🔍 SISTEM DENDA

**Method:** `hitungDenda($angsuran, $pinjaman)` (Line 497-537 Admin)

```php
Aturan Denda (REVISI TERBARU):

1. Denda = 0.3% per hari dari POKOK per bulan (bukan total tagihan)
2. Denda mulai H+1 setelah jatuh tempo
3. Denda BERHENTI jika sudah ada pembayaran (walaupun Rp 1)

Perhitungan:
├── pokok_per_bulan = jumlah_pinjam / lama_pinjam
├── hari_telat = selisih hari dari (tgl_jatuh_tempo + 1 hari) sampai now()
└── denda = pokok_per_bulan × (denda_persen / 100) × hari_telat

Contoh:
Pinjaman: Rp 3.000.000, 3 bulan
Pokok/bulan: Rp 1.000.000
Denda persen: 0.3%

Telat 1 hari = 1.000.000 × 0.3% × 1 = Rp 3.000
Telat 5 hari = 1.000.000 × 0.3% × 5 = Rp 15.000

⚠️ IMPORTANT:
- Jika status_bayar = 'lunas': return denda yang tersimpan
- Jika jumlah_terbayar > 0: return denda yang tersimpan (tidak bertambah)
- Jika belum H+1: return 0
```

---

## E. PEMBAYARAN ANGSURAN

### 📄 HALAMAN NASABAH - Form Pembayaran

**Route:** `nasabah.pinjaman.pembayaran`

**File:** `resources/views/nasabah/pinjaman/pembayaran.blade.php`

### 🔍 FORM DATA

```php
// Ada 2 metode pembayaran:

1. Transfer:
   ├── pinjaman_id
   ├── tempo_id
   ├── jenis_tempo ('bulanan' / 'mingguan')
   ├── jumlah_bayar (decimal)
   ├── bukti_foto[] (array of images)
   └── keterangan

2. Janji Temu (Cash):
   ├── pinjaman_id
   ├── tempo_id
   ├── jenis_tempo
   ├── jumlah_bayar
   ├── lokasi_temu
   ├── tanggal_janji_temu
   ├── waktu_janji_temu
   └── keterangan
```

### 🎯 CONTROLLER - Submit Pembayaran (Nasabah)

Sama seperti tabungan, ada 2 jenis:

**1. Transfer:** Simpan ke `tbl_pengajuan_pembayaran_pinjaman` + `tbl_bukti_foto_pembayaran_pinjaman`

**2. Janji Temu:** Simpan ke `tbl_pengajuan_pembayaran_pinjaman` + `tbl_janji_temu_pembayaran_pinjaman`

### 🎯 ADMIN - Update Pembayaran Angsuran

**Route:** `admin.pinjaman.update-pembayaran-angsuran`

**Method:** `updatePembayaranAngsuran(Request $request, $id)` (Line 542-614)

```php
Alur Update Pembayaran:

Input:
├── jumlah_bayar (decimal)
└── jenis ('bulanan' / 'mingguan')

1. Ambil data angsuran berdasarkan jenis

2. Hitung denda (sebelum update):
   └── denda = hitungDenda($angsuran, $pinjaman)

3. Hitung total tagihan + denda:
   └── total = jumlah_tagihan + denda

4. Update jumlah terbayar:
   ├── jumlah_terbayar_baru = jumlah_terbayar_lama + jumlah_bayar
   └── Tentukan status_bayar:
       ├── Jika >= total: status = 'lunas', denda = 0, tgl_bayar = now()
       ├── Jika >= jumlah_tagihan tapi < total: status = 'telat'
       └── Jika < jumlah_tagihan DAN sudah lewat JT: status = 'telat'

5. Update angsuran:
   ├── jumlah_terbayar
   ├── denda
   ├── status_bayar
   └── tgl_bayar (jika lunas/ada pembayaran)

6. Cek apakah semua angsuran lunas:
   └── Jika YA: update pinjaman->lunas = 'lunas'

7. Return success
```

### 📊 CARA KERJA TABLE & RELASI

```
FLOW LENGKAP PEMBAYARAN ANGSURAN:

Nasabah Submit Pembayaran
    ↓
tbl_pengajuan_pembayaran_pinjaman (status='1' pending)
    ├── pinjaman_id
    ├── tempo_id
    ├── jenis_tempo
    ├── jumlah_bayar
    └── metode (transfer/cash)
    ↓
Admin Review
    ├── Approve (status='3')
    └── atau Reject (status='2')
    ↓
Admin Input Pembayaran ke Tempo
    ↓
tempo_pinjaman_b table updated:
    ├── jumlah_terbayar += jumlah_bayar
    ├── denda (jika ada keterlambatan)
    ├── status_bayar (belum/telat/lunas)
    └── tgl_bayar (jika lunas)
    ↓
Check All Angsuran
    ↓
Jika SEMUA lunas
    ↓
tbl_pinjaman_h->lunas = 'lunas'
```

---

## 📋 SUMMARY TABEL & FOTO

### TABUNGAN - Penyimpanan Foto:

```
1. Pengajuan Setor Transfer:
   └── tbl_bukti_foto_tabungan
       ├── id_pengajuan (FK)
       ├── file_photo (path ke storage/bukti_tabungan/)
       └── jenis: 'tabungan'
   
   ⚠️ Bisa multiple foto per pengajuan

2. Pengajuan Tarik Transfer (Approve):
   └── tbl_pengajuan_penarikan_tabungan
       └── foto_bukti_tf_admin (path ke storage/bukti_tf_admin/)
   
   ⚠️ Foto di-upload oleh admin saat approve
```

### PINJAMAN - Penyimpanan Foto:

```
1. Pengajuan Pinjaman (Pencairan):
   └── tbl_pinjaman_h
       ├── foto_bukti_transfer (bukti pencairan)
       └── foto_serah_terima
   
   ⚠️ File di-upload admin saat cairkan pinjaman

2. Pembayaran Angsuran Transfer:
   └── tbl_bukti_foto_pembayaran_pinjaman
       ├── id_pengajuan_pembayaran (FK)
       ├── foto_bukti (path ke storage)
       └── keterangan
   
   ⚠️ Bisa multiple foto per pengajuan pembayaran
```

---

## 🔄 STATUS CODES REFERENCE

### Pengajuan Tabungan (tbl_pengajuan_tabungan):
- `'1'` = Pending (menunggu review admin)
- `'2'` = Rejected/Ditolak
- `'3'` = Approved/Disetujui (transaksi sudah dibuat)

### Pengajuan Penarikan Tabungan:
- `'1'` = Pending
- `'2'` = Approved
- `'3'` = Rejected

### Pengajuan Pinjaman (tbl_pengajuan_pinjaman):
- `'1'` = Pending (belum direview)
- `'2'` = Ditolak
- `'3'` = Disetujui (belum dicairkan)
- `'4'` = Terlaksana (sudah dicairkan, pinjaman sudah dibuat)

### Tempo/Angsuran (status_bayar):
- `'belum'` = Belum dibayar & belum telat
- `'telat'` = Sudah telat tapi belum lunas
- `'lunas'` = Sudah lunas

### Pinjaman (lunas):
- `'belum'` = Masih ada angsuran yang belum lunas
- `'lunas'` = Semua angsuran sudah lunas

---

## ✅ KESIMPULAN & CATATAN PENTING

### TABUNGAN:
1. **2 Metode Setoran:** Transfer (dengan bukti foto) dan Tunai (janji temu)
2. **Nominal untuk Tunai:** Disimpan di `tbl_janji_temu_tabungan`, BUKAN di `tbl_pengajuan_tabungan`
3. **Saldo:** Dihitung real-time dari `trans_tabungan` + pengajuan approved yang belum jadi transaksi
4. **Transaksi:** Dibuat otomatis saat admin approve pengajuan
5. **Penarikan:** Ada validasi saldo, admin upload bukti TF jika metode transfer

### PINJAMAN:
1. **2 Tahap Approve:** Approve pengajuan (status 1→3) lalu Cairkan (status 3→4, buat pinjaman & tempo)
2. **Bunga:** Diambil dari `master_bunga_pinjaman` berdasarkan durasi
3. **Sistem Bunga:** Bunga dibagi merata ke setiap angsuran (bukan dipotong di awal)
4. **Denda:** 0.3%/hari dari POKOK per bulan, mulai H+1, berhenti saat ada pembayaran
5. **Jadwal Angsuran:** Di-generate otomatis saat pencairan
6. **Tempo:** Disimpan di `tempo_pinjaman_b` (bulanan) atau `tempo_pinjaman_m` (mingguan)
7. **Pembayaran:** Bisa cicilan, sistem track `jumlah_terbayar` vs `jumlah_tagihan`
8. **Lunas:** Pinjaman dianggap lunas jika SEMUA tempo status_bayar = 'lunas'

---

📄 **Dokumentasi ini dibuat berdasarkan scanning lengkap:**
- ✅ Form & Blade templates
- ✅ Controllers (Nasabah & Admin)
- ✅ Models & Relations
- ✅ Database tables & fillable
- ✅ Business logic & validations

🎯 **Update terakhir:** 3 Februari 2026
