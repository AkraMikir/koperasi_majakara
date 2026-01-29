# 📊 RANGKUMAN LENGKAP SISTEM TABUNGAN KOPERASI MAJAKARA

## 📑 DAFTAR ISI
1. [Overview Sistem](#overview-sistem)
2. [Alur Sistem Lengkap](#alur-sistem-lengkap)
3. [Fitur Sisi Nasabah](#fitur-sisi-nasabah)
4. [Fitur Sisi Admin](#fitur-sisi-admin)
5. [Struktur Database](#struktur-database)
6. [Status & Kode](#status--kode)
7. [Keamanan & Validasi](#keamanan--validasi)
8. [Catatan Penting](#catatan-penting)

---

## 🎯 OVERVIEW SISTEM

Sistem Tabungan Koperasi Majakara adalah modul untuk mengelola setoran dan penarikan tabungan anggota koperasi. Sistem ini dirancang dengan dua perspektif utama: **Nasabah** dan **Admin**.

### Konsep Dasar:
- **Setoran** dapat dilakukan via **Transfer Bank** atau **Tunai** (dengan janji temu)
- **Penarikan** dapat dilakukan via **Transfer Bank** atau **Tunai**
- Semua pengajuan harus **disetujui Admin** sebelum menjadi transaksi resmi
- **Saldo** dihitung otomatis dari transaksi setoran dan penarikan
- Sistem menggunakan **PIN 6 digit** untuk verifikasi transaksi nasabah

### Teknologi:
- **Framework**: Laravel 10+
- **Database**: MySQL
- **Storage**: Laravel Storage (public disk)
- **Authentication**: Laravel Auth
- **Frontend**: Blade Templates + Bootstrap

---

## 🔄 ALUR SISTEM LENGKAP

### A. ALUR SETORAN TABUNGAN

#### 1. Setoran via Transfer Bank
```
NASABAH:
1. Akses halaman "Nabung Sekarang"
2. Pilih metode "Transfer Bank"
3. Input nominal setoran (min Rp 10.000)
4. Upload bukti transfer (bisa multiple file)
5. Input nominal per bukti foto
6. Input keterangan (opsional)
7. Verifikasi PIN 6 digit
8. Submit pengajuan

SISTEM:
1. Validasi input dan PIN
2. Simpan pengajuan ke tbl_pengajuan_tabungan (status: pending)
3. Simpan bukti foto ke tbl_bukti_foto_tabungan
4. Redirect ke halaman status pengajuan
5. Notifikasi berhasil

ADMIN:
1. Menerima notifikasi pengajuan baru
2. Buka menu "Pengajuan Setor"
3. Lihat detail pengajuan + bukti transfer
4. Verifikasi keaslian bukti transfer
5. Klik "Setujui" atau "Tolak"
   - Jika setujui: Status = Approved, auto create transaksi
   - Jika tolak: Input alasan penolakan

HASIL:
- Status pengajuan: Approved/Rejected
- Jika approved: Transaksi tercatat, saldo nasabah bertambah
- Nasabah dapat melihat status di halaman "Status Pengajuan"
```

#### 2. Setoran via Tunai (Janji Temu)
```
NASABAH:
1. Akses halaman "Nabung Sekarang"
2. Pilih metode "Setor Tunai"
3. Input nominal setoran
4. Input keterangan (opsional)
5. Redirect ke halaman "Janji Temu"
6. Pilih lokasi temu (kantor koperasi)
7. Pilih tanggal temu (harus > hari ini)
8. Pilih waktu temu
9. Verifikasi PIN 6 digit
10. Submit janji temu

SISTEM:
1. Validasi input dan PIN
2. Simpan pengajuan ke tbl_pengajuan_tabungan (status: pending)
3. Simpan janji temu ke tbl_janji_temu_tabungan
4. Redirect ke status pengajuan
5. Notifikasi berhasil

ADMIN:
1. Menerima notifikasi janji temu baru
2. Buka menu "Janji Temu"
3. Lihat jadwal janji temu
4. Pada waktu yang ditentukan:
   - Terima setoran tunai dari nasabah
   - Upload foto penerimaan (opsional)
   - Input nominal yang diterima
   - Input keterangan
   - Pilih tanggal transaksi
   - Klik "Buat Transaksi"

SISTEM:
1. Update status pengajuan = Approved
2. Create transaksi di trans_tabungan (via: cash)
3. Saldo nasabah bertambah

HASIL:
- Transaksi tercatat dengan via = cash
- Nasabah dapat melihat di riwayat transaksi
```

### B. ALUR PENARIKAN TABUNGAN

```
NASABAH:
1. Akses halaman "Penarikan Tabungan"
2. Cek saldo yang tersedia
3. Pilih metode penarikan:
   - Transfer: Input nomor rekening tujuan
   - Tunai: (untuk diambil di kantor)
4. Input nominal penarikan
5. Input keterangan (opsional)
6. Klik "Ajukan Penarikan"

SISTEM:
1. Validasi nominal vs saldo
   - Jika saldo tidak cukup: Tampilkan error
   - Jika cukup: Lanjut proses
2. Simpan pengajuan ke tbl_pengajuan_penarikan_tabungan (status: pending)
3. Redirect ke status pengajuan
4. Notifikasi berhasil

ADMIN:
1. Menerima notifikasi pengajuan penarikan
2. Buka menu "Pengajuan Tarik"
3. Lihat detail pengajuan
4. Cek saldo nasabah (ditampilkan dengan breakdown)
5. Validasi saldo mencukupi
6. Klik "Setujui" atau "Tolak"
   - Jika setujui: 
     * Cek saldo sekali lagi
     * Auto create transaksi penarikan
     * Update status = Approved
   - Jika tolak: Input alasan penolakan

SISTEM:
1. Create transaksi di trans_tabungan (jenis: penarikan)
2. Saldo nasabah berkurang
3. Update status pengajuan

HASIL:
- Transaksi penarikan tercatat
- Admin melakukan transfer (jika via transfer)
- Atau nasabah datang mengambil uang (jika tunai)
- Nasabah dapat melihat di riwayat transaksi
```

---

## 👤 FITUR SISI NASABAH

### 1. Dashboard Tabungan (`/nasabah/tabungan`)
**Halaman**: `index.blade.php`

**Fitur:**
- ✅ Menampilkan **Saldo Tabungan** (realtime dari database)
- ✅ Menampilkan **Suku Bunga Tabungan** (3.5% - placeholder)
- ✅ Menampilkan **Status Tabungan** (Aktif/Tidak Aktif)
- ✅ Card Actions:
  - Nabung Sekarang
  - Penarikan Tabungan
  - Status Pengajuan Setor
  - Status Pengajuan Tarik
- ✅ **Riwayat Transaksi** (10 transaksi terbaru)
  - Tanggal transaksi
  - Jenis (Setoran/Penarikan)
  - Nominal
  - Via (Transfer/Cash)
  - Keterangan
- ✅ **Riwayat Janji Temu** (10 terbaru)
  - Tanggal & waktu janji temu
  - Lokasi
  - Nominal
  - Status

**Perhitungan Saldo:**
```php
Saldo = (Total Setoran) - (Total Penarikan)
- Total Setoran: Sum dari trans_tabungan (jenis: setoran)
- Total Penarikan: Sum dari trans_tabungan (jenis: penarikan)
- Tambahan: Pengajuan approved yang belum ada transaksi
```

---

### 2. Nabung Sekarang (`/nasabah/tabungan/nabung-sekarang`)
**Halaman**: `nabung-sekarang.blade.php`

**Fitur:**
- ✅ Pilihan Metode:
  - **Transfer Bank**: Upload bukti transfer
  - **Setor Tunai**: Buat janji temu
- ✅ Form Transfer Bank:
  - Input nominal (min Rp 10.000)
  - Upload bukti foto (multiple file, max 5MB per file)
  - Input nominal per bukti foto
  - Input keterangan per bukti foto
  - Input keterangan umum (opsional)
  - Verifikasi PIN (modal popup)
- ✅ Form Setor Tunai:
  - Input nominal
  - Input keterangan
  - Redirect ke halaman janji temu
- ✅ **Riwayat Setoran** (10 terbaru)
  - Tanggal
  - Nominal
  - Via
  - Keterangan

**Validasi:**
- Nominal minimum: Rp 10.000
- Format file: Image (jpg, png, jpeg)
- Ukuran file: Max 5MB per file
- PIN: 6 digit numeric
- Multiple upload: Minimal 1 file untuk transfer

**Route POST**: `/nasabah/tabungan/pengajuan-transfer`

---

### 3. Janji Temu Setoran Tunai (`/nasabah/tabungan/janji-temu`)
**Halaman**: `janji-temu.blade.php`

**Fitur:**
- ✅ Form Janji Temu:
  - Nominal (readonly dari form sebelumnya)
  - Pilih lokasi temu (dropdown dari jns_lokasi_perusahaan)
  - Pilih tanggal (datepicker, harus > hari ini)
  - Pilih waktu (timepicker)
  - Keterangan (readonly dari form sebelumnya)
  - Verifikasi PIN
- ✅ Informasi Lokasi:
  - Nama lokasi
  - Alamat lengkap
  - Jam operasional

**Validasi:**
- Lokasi: Harus dipilih (exists di database)
- Tanggal: Harus lebih dari hari ini
- Waktu: Format HH:MM
- PIN: 6 digit numeric

**Route POST**: `/nasabah/tabungan/janji-temu`

---

### 4. Penarikan Tabungan (`/nasabah/tabungan/penarikan`)
**Halaman**: `penarikan-tabungan.blade.php`

**Fitur:**
- ✅ Informasi Saldo:
  - Saldo tersedia (realtime)
  - Suku bunga
  - Status tabungan
- ✅ Form Penarikan:
  - Pilih metode (Transfer/Tunai)
  - Input nominal (min Rp 10.000)
  - Input nomor rekening (jika transfer)
  - Input keterangan (opsional)
- ✅ **Riwayat Penarikan** (10 terbaru)
  - Tanggal
  - Nominal
  - Via
  - Keterangan

**Validasi:**
- Nominal minimum: Rp 10.000
- Nominal tidak boleh > saldo
- Nomor rekening: Required jika metode transfer
- Keterangan: Max 500 karakter

**Route POST**: `/nasabah/tabungan/penarikan`

---

### 5. Status Pengajuan Setoran (`/nasabah/tabungan/status-pengajuan-setor`)
**Halaman**: `status-pengajuan-setor.blade.php`

**Fitur:**
- ✅ Tabel Pengajuan:
  - Tanggal pengajuan
  - Jenis (Transfer/Tunai)
  - Status (Pending/Approved/Rejected)
  - Nominal (total dari bukti foto atau janji temu)
  - Keterangan
  - Aksi: Lihat Detail
- ✅ Filter & Search (jika ada)
- ✅ Pagination (10 per halaman)
- ✅ Badge Status:
  - Pending: Warning (kuning)
  - Approved: Success (hijau)
  - Rejected: Danger (merah)

**Detail yang Ditampilkan:**
- Data pengajuan lengkap
- Bukti foto transfer (jika ada)
- Informasi janji temu (jika ada)
- Alasan penolakan (jika ditolak)

---

### 6. Status Pengajuan Penarikan (`/nasabah/tabungan/status-pengajuan-tarik`)
**Halaman**: `status-pengajuan-tarik.blade.php`

**Fitur:**
- ✅ Tabel Pengajuan:
  - Tanggal pengajuan
  - Nominal
  - Status (Pending/Approved/Rejected)
  - Keterangan (termasuk nomor rekening jika transfer)
  - Aksi: Lihat Detail
- ✅ Pagination (10 per halaman)
- ✅ Badge Status (sama seperti setoran)

---

### 7. Detail Pengajuan Setoran (`/nasabah/tabungan/pengajuan-setor/{id}`)
**Halaman**: `detail-pengajuan-setor.blade.php`

**Fitur:**
- ✅ Informasi Lengkap:
  - ID Pengajuan
  - Tanggal pengajuan
  - Status
  - Jenis (Transfer/Tunai)
- ✅ Bukti Transfer (jika ada):
  - Gallery bukti foto
  - Nominal per bukti
  - Keterangan per bukti
  - Total nominal
- ✅ Janji Temu (jika ada):
  - Lokasi
  - Tanggal & waktu
  - Nominal
- ✅ Keterangan pengajuan
- ✅ Alasan penolakan (jika ditolak)
- ✅ Timeline status

---

### 8. Detail Pengajuan Penarikan (`/nasabah/tabungan/pengajuan-tarik/{id}`)
**Halaman**: `detail-pengajuan-tarik.blade.php`

**Fitur:**
- ✅ Informasi Lengkap:
  - ID Pengajuan
  - Tanggal pengajuan
  - Nominal
  - Status
  - Metode (Transfer/Tunai)
  - Nomor rekening (jika transfer)
- ✅ Keterangan
- ✅ Alasan penolakan (jika ditolak)
- ✅ Timeline status

---

### 9. Detail Transaksi (`/nasabah/tabungan/transaksi/{id}`)
**Halaman**: `detail-transaksi.blade.php`

**Fitur:**
- ✅ Informasi Transaksi:
  - ID Transaksi
  - Tanggal transaksi
  - Jenis (Setoran/Penarikan)
  - Nominal
  - Via (Transfer/Cash)
  - Keterangan
- ✅ Link ke Pengajuan Terkait:
  - Jika dari pengajuan setor: Link ke detail pengajuan setor
  - Jika dari pengajuan tarik: Link ke detail pengajuan tarik
- ✅ Bukti Foto (jika ada)
- ✅ Informasi Saldo:
  - Saldo sebelum transaksi
  - Saldo setelah transaksi (estimasi)

---

### 10. Detail Janji Temu (`/nasabah/tabungan/janji-temu/{id}`)
**Halaman**: `detail-janji-temu.blade.php`

**Fitur:**
- ✅ Informasi Janji Temu:
  - ID Janji Temu
  - Lokasi temu (nama + alamat)
  - Tanggal & waktu
  - Nominal
- ✅ Status Pengajuan:
  - Pending: Menunggu kedatangan
  - Approved: Sudah terlaksana
- ✅ Informasi Transaksi (jika sudah dibuat):
  - ID Transaksi
  - Tanggal transaksi
  - Nominal yang diterima
  - Link ke detail transaksi
- ✅ Petunjuk Arah (jika ada)

---

## 👔 FITUR SISI ADMIN

### 1. Dashboard Tabungan Admin (`/admin/tabungan`)
**Halaman**: `index.blade.php`

**Fitur:**
- ✅ **Statistik Real-time**:
  - Total Pengajuan Setor (Pending)
  - Total Pengajuan Tarik (Pending)
  - Total Transaksi Hari Ini
  - Total Setoran Hari Ini (Rupiah)
  - Total Penarikan Hari Ini (Rupiah)
  - Total Janji Temu Pending
- ✅ **Pengajuan Setor Terbaru** (5 terbaru, status pending)
  - Nama nasabah
  - Tanggal pengajuan
  - Jenis (Transfer/Tunai)
  - Nominal (estimasi)
  - Quick Action: Lihat Detail
- ✅ **Pengajuan Tarik Terbaru** (5 terbaru, status pending)
  - Nama nasabah
  - Tanggal pengajuan
  - Nominal
  - Quick Action: Lihat Detail
- ✅ **Transaksi Terbaru** (10 terbaru)
  - Nama nasabah
  - Tanggal
  - Jenis
  - Nominal
  - Via

---

### 2. Pengajuan Setoran (`/admin/tabungan/pengajuan-setor`)
**Halaman**: `pengajuan-setor.blade.php`

**Fitur:**
- ✅ **Filter & Search**:
  - Filter by status (All/Pending/Approved/Rejected)
  - Search by nama nasabah atau email
  - Default: Tampilkan pending saja
- ✅ **Tabel Pengajuan**:
  - No urut
  - Nama nasabah + email
  - Tanggal pengajuan
  - Jenis (Transfer/Tunai - dengan badge)
  - Nominal (total dari bukti foto/janji temu)
  - Status (badge berwarna)
  - Aksi: Lihat Detail
- ✅ Pagination (15 per halaman)
- ✅ Sorting by tanggal (terbaru di atas)

**Badge:**
- Transfer: Info (biru)
- Tunai: Success (hijau)
- Pending: Warning (kuning)
- Approved: Success (hijau)
- Rejected: Danger (merah)

---

### 3. Detail Pengajuan Setoran (`/admin/tabungan/pengajuan-setor/{id}`)
**Halaman**: `detail-pengajuan-setor.blade.php`

**Fitur:**
- ✅ **Data Nasabah Lengkap**:
  - Nama lengkap
  - Email
  - No. KTP
  - Alamat
  - No. Telepon
  - Data rekening
- ✅ **Data Pengajuan**:
  - ID Pengajuan
  - Tanggal pengajuan
  - Jenis (Transfer/Tunai)
  - Status
  - Keterangan
- ✅ **Bukti Transfer** (jika transfer):
  - Gallery bukti foto (lightbox)
  - Nominal per bukti
  - Keterangan per bukti
  - Total nominal
  - Button: Download semua bukti
- ✅ **Janji Temu** (jika tunai):
  - Lokasi temu
  - Tanggal & waktu
  - Nominal
  - Status transaksi (sudah dibuat atau belum)
- ✅ **Aksi Admin**:
  - Button "Setujui" (jika pending)
  - Button "Tolak" (jika pending)
  - Button "Edit" (modal edit keterangan & status)
  - Button "Hapus" (hanya jika pending & belum ada transaksi)
- ✅ **Riwayat Transaksi Terkait** (jika ada)
- ✅ **Modal Reject**: Form input alasan penolakan
- ✅ **Modal Edit**: Form edit keterangan dan status

**Validasi:**
- Approve: Cek nominal > 0
- Reject: Alasan wajib diisi
- Delete: Hanya jika pending & belum ada transaksi

---

### 4. Pengajuan Penarikan (`/admin/tabungan/pengajuan-tarik`)
**Halaman**: `pengajuan-tarik.blade.php`

**Fitur:**
- ✅ **Filter & Search**:
  - Filter by status
  - Search by nama/email
  - Default: Pending
- ✅ **Tabel Pengajuan**:
  - Nama nasabah
  - Tanggal pengajuan
  - Nominal
  - Metode (Transfer/Tunai)
  - Status
  - Aksi: Lihat Detail
- ✅ Pagination (15 per halaman)

---

### 5. Detail Pengajuan Penarikan (`/admin/tabungan/pengajuan-tarik/{id}`)
**Halaman**: `detail-pengajuan-tarik.blade.php`

**Fitur:**
- ✅ **Data Nasabah Lengkap** (sama seperti setoran)
- ✅ **Data Pengajuan**:
  - ID Pengajuan
  - Tanggal pengajuan
  - Nominal
  - Metode (Transfer/Tunai)
  - Nomor rekening tujuan (jika transfer)
  - Status
  - Keterangan
- ✅ **Informasi Saldo Nasabah**:
  - Saldo saat ini
  - Total setoran (all time)
  - Total penarikan (all time)
  - Breakdown saldo (table)
  - **Validasi**: Warning jika saldo tidak cukup
- ✅ **Aksi Admin**:
  - Button "Setujui" (jika pending & saldo cukup)
  - Button "Tolak" (jika pending)
  - **Disabled** jika saldo tidak cukup
- ✅ **Modal Reject**: Form input alasan
- ✅ **Konfirmasi Approve**: Confirm dialog sebelum approve

**Validasi:**
- Approve: Cek saldo nasabah >= nominal penarikan
- Jika tidak cukup: Button approve disabled + warning message

---

### 6. Transaksi Tabungan (`/admin/tabungan/transaksi`)
**Halaman**: `transaksi.blade.php`

**Fitur:**
- ✅ **Filter & Search**:
  - Filter by jenis (All/Setoran/Penarikan)
  - Filter by tanggal (dari - sampai)
  - Search by nama nasabah
- ✅ **Tabel Transaksi**:
  - ID Transaksi
  - Nama nasabah
  - Tanggal transaksi
  - Jenis (badge berwarna)
  - Nominal
  - Via (Transfer/Cash)
  - Keterangan
  - Aksi: Lihat Detail
- ✅ Pagination (20 per halaman)
- ✅ Sorting by tanggal (terbaru di atas)
- ✅ **Summary Box**:
  - Total Transaksi (filtered)
  - Total Setoran (filtered)
  - Total Penarikan (filtered)

---

### 7. Detail Transaksi (`/admin/tabungan/transaksi/{id}`)
**Halaman**: `detail-transaksi.blade.php`

**Fitur:**
- ✅ **Data Nasabah** (sama seperti sebelumnya)
- ✅ **Data Transaksi**:
  - ID Transaksi
  - Tanggal transaksi
  - Jenis
  - Nominal
  - Via
  - Keterangan
- ✅ **Link ke Pengajuan**:
  - Jika dari pengajuan setor: Link + info pengajuan
  - Jika dari pengajuan tarik: Link + info pengajuan
- ✅ **Bukti Foto** (jika ada)
- ✅ **Informasi Saldo**:
  - Saldo sebelum transaksi (estimasi)
  - Saldo setelah transaksi (estimasi)
  - Saldo saat ini (realtime)
- ✅ **Print/Export**: Button cetak bukti transaksi

---

### 8. Janji Temu (`/admin/tabungan/janji-temu`)
**Halaman**: `janji-temu.blade.php`

**Fitur:**
- ✅ **Filter**:
  - Filter by tanggal (dari - sampai)
  - Filter by lokasi
  - Filter by status transaksi (Belum/Sudah dibuat)
- ✅ **Tabel Janji Temu**:
  - Nama nasabah
  - Tanggal & waktu janji temu
  - Lokasi
  - Nominal
  - Status transaksi
  - Aksi: Lihat Detail
- ✅ **Calendar View** (opsional, jika diimplementasikan)
- ✅ Pagination (15 per halaman)
- ✅ Highlight: Janji temu hari ini

---

### 9. Detail Janji Temu (`/admin/tabungan/janji-temu/{id}`)
**Halaman**: `detail-janji-temu.blade.php`

**Fitur:**
- ✅ **Data Nasabah Lengkap**
- ✅ **Data Janji Temu**:
  - ID Janji Temu
  - Lokasi (nama + alamat lengkap)
  - Tanggal & waktu
  - Nominal yang dijanjikan
- ✅ **Data Pengajuan Terkait**:
  - Status pengajuan
  - Keterangan
- ✅ **Status Transaksi**:
  - Belum dibuat: Tampilkan form create transaksi
  - Sudah dibuat: Tampilkan info transaksi + link
- ✅ **Form Create Transaksi** (jika belum dibuat):
  - Input nominal (bisa berbeda dari nominal janji temu)
  - Upload foto penerimaan (opsional)
  - Input keterangan
  - Pilih tanggal transaksi
  - Button "Buat Transaksi"
- ✅ **Validasi**:
  - Nominal minimum: Rp 10.000
  - Cek duplikasi transaksi
- ✅ **Auto Actions**:
  - Setelah create transaksi: Update status pengajuan = Approved
  - Simpan foto ke tbl_bukti_foto_tabungan (jika ada)

**Alur Create Transaksi:**
1. Admin mengisi form
2. System validasi
3. Upload foto (jika ada)
4. Update status pengajuan
5. Create transaksi di trans_tabungan
6. Redirect ke detail dengan success message

---

### 10. Saldo Nasabah (`/admin/tabungan/saldo-nasabah`)
**Halaman**: `saldo-nasabah.blade.php`

**Fitur:**
- ✅ **Search**: Cari by nama/email nasabah
- ✅ **Tabel Saldo**:
  - Nama nasabah
  - Email
  - Saldo saat ini (realtime)
  - Total setoran (all time)
  - Total penarikan (all time)
  - Jumlah transaksi
  - Aksi: Lihat Detail Nasabah
- ✅ Pagination (20 per halaman)
- ✅ Sorting by saldo (terbesar di atas)
- ✅ **Summary Box**:
  - Total saldo semua nasabah
  - Total setoran (all time)
  - Total penarikan (all time)
- ✅ **Export**: Button export to Excel/PDF

**Detail Nasabah** (jika diklik):
- Informasi lengkap nasabah
- Riwayat transaksi lengkap
- Pengajuan pending (jika ada)
- Grafik setoran/penarikan (opsional)

---

## 🗄️ STRUKTUR DATABASE

### 1. `tbl_pengajuan_tabungan`
Menyimpan pengajuan setoran tabungan.

| Field | Type | Constraint | Keterangan |
|-------|------|------------|------------|
| `id` | BIGINT | PRIMARY KEY, AUTO_INCREMENT | ID unik pengajuan |
| `id_anggota` | BIGINT | FOREIGN KEY → tbl_nasabah.id, CASCADE | ID nasabah yang mengajukan |
| `foto_bukti_tf` | VARCHAR(255) | NOT NULL | Menyimpan 'transfer' atau 'tunai' |
| `keterangan` | TEXT | NULLABLE | Keterangan pengajuan |
| `status` | ENUM('1','2','3') | DEFAULT '1' | 1=Pending, 2=Approved, 3=Rejected |
| `created_at` | TIMESTAMP | - | Tanggal dibuat |
| `updated_at` | TIMESTAMP | - | Tanggal update |

**Catatan Penting:**
- Field `foto_bukti_tf` **BUKAN** path file, tapi indikator jenis ('transfer'/'tunai')
- File bukti disimpan di tabel `tbl_bukti_foto_tabungan`

---

### 2. `tbl_pengajuan_penarikan_tabungan`
Menyimpan pengajuan penarikan tabungan.

| Field | Type | Constraint | Keterangan |
|-------|------|------------|------------|
| `id` | BIGINT | PRIMARY KEY, AUTO_INCREMENT | ID unik pengajuan |
| `id_anggota` | BIGINT | FOREIGN KEY → tbl_nasabah.id, CASCADE | ID nasabah |
| `tgl_pengajuan` | DATETIME | NOT NULL | Tanggal pengajuan |
| `nominal` | DECIMAL(15,2) | NOT NULL | Nominal penarikan |
| `keterangan` | TEXT | NULLABLE | Keterangan (termasuk no. rek) |
| `status` | ENUM('1','2','3') | DEFAULT '1' | 1=Pending, 2=Approved, 3=Rejected |
| `created_at` | TIMESTAMP | - | Tanggal dibuat |
| `updated_at` | TIMESTAMP | - | Tanggal update |

**Catatan:**
- Field `keterangan` menyimpan info tambahan termasuk nomor rekening jika transfer

---

### 3. `tbl_bukti_foto_tabungan`
Menyimpan bukti foto transfer untuk setoran.

| Field | Type | Constraint | Keterangan |
|-------|------|------------|------------|
| `id` | BIGINT | PRIMARY KEY, AUTO_INCREMENT | ID unik bukti foto |
| `id_pengajuan` | BIGINT | FOREIGN KEY → tbl_pengajuan_tabungan.id, CASCADE | ID pengajuan terkait |
| `file_photo` | VARCHAR(255) | NOT NULL | Path file di storage |
| `jenis` | ENUM('tabungan','penarikan') | NOT NULL | Jenis bukti |
| `nominal` | DECIMAL(15,2) | NOT NULL | Nominal pada bukti |
| `keterangan` | VARCHAR(255) | NOT NULL | Keterangan bukti |
| `created_at` | TIMESTAMP | - | Tanggal upload |
| `updated_at` | TIMESTAMP | - | Tanggal update |

**Catatan:**
- Satu pengajuan bisa punya **multiple bukti foto**
- File disimpan di `storage/app/public/bukti_tabungan/`

---

### 4. `trans_tabungan`
Menyimpan transaksi tabungan yang sudah disetujui.

| Field | Type | Constraint | Keterangan |
|-------|------|------------|------------|
| `id` | BIGINT | PRIMARY KEY, AUTO_INCREMENT | ID unik transaksi |
| `id_pengajuan_setor` | BIGINT | FOREIGN KEY → tbl_pengajuan_tabungan.id, SET NULL, NULLABLE | Link ke pengajuan setor |
| `id_pengajuan_tarik` | BIGINT | FOREIGN KEY → tbl_pengajuan_penarikan_tabungan.id, SET NULL, NULLABLE | Link ke pengajuan tarik |
| `id_anggota` | BIGINT | FOREIGN KEY → tbl_nasabah.id, CASCADE | ID nasabah |
| `nominal` | DECIMAL(15,2) | NOT NULL | Nominal transaksi |
| `keterangan` | TEXT | NULLABLE | Keterangan transaksi |
| `jenis` | ENUM('setoran','penarikan') | NOT NULL | Jenis transaksi |
| `via` | ENUM('transfer','cash') | NOT NULL | Metode transaksi |
| `tgl_transaksi` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Tanggal transaksi |
| `created_at` | TIMESTAMP | - | Tanggal record dibuat |
| `updated_at` | TIMESTAMP | - | Tanggal update |

**Catatan:**
- Tabel ini adalah **sumber tunggal** untuk menghitung saldo
- `id_pengajuan_setor` dan `id_pengajuan_tarik` bisa NULL (transaksi manual)
- Satu pengajuan bisa punya satu atau lebih transaksi (jarang terjadi)

---

### 5. `tbl_janji_temu_tabungan`
Menyimpan janji temu untuk setoran tunai.

| Field | Type | Constraint | Keterangan |
|-------|------|------------|------------|
| `id` | BIGINT | PRIMARY KEY, AUTO_INCREMENT | ID unik janji temu |
| `id_pengajuan` | BIGINT | FOREIGN KEY → tbl_pengajuan_tabungan.id, CASCADE | Link ke pengajuan |
| `lokasi_temu` | BIGINT | FOREIGN KEY → jns_lokasi_perusahaan.id, CASCADE | Lokasi kantor koperasi |
| `nominal` | DECIMAL(15,2) | NOT NULL | Nominal yang akan disetor |
| `tanggal_janji_temu` | DATETIME | NOT NULL | Tanggal janji temu |
| `waktu_janji_temu` | TIMESTAMP | NOT NULL | Waktu janji temu |
| `created_at` | TIMESTAMP | - | Tanggal dibuat |
| `updated_at` | TIMESTAMP | - | Tanggal update |

**Catatan:**
- Satu pengajuan hanya punya **satu janji temu**
- Nominal bisa berbeda dengan nominal transaksi aktual

---

## 🏷️ STATUS & KODE

### Status Pengajuan (Enum)
```
'1' = PENDING
- Pengajuan baru dibuat
- Menunggu verifikasi admin
- Badge: Warning (Kuning)

'2' = APPROVED
- Pengajuan disetujui admin
- Transaksi sudah dibuat (atau akan dibuat)
- Badge: Success (Hijau)

'3' = REJECTED
- Pengajuan ditolak admin
- Tidak akan diproses
- Badge: Danger (Merah)
```

### Jenis Transaksi (Enum)
```
'setoran' = SETORAN TABUNGAN
- Menambah saldo
- Nominal positif

'penarikan' = PENARIKAN TABUNGAN
- Mengurangi saldo
- Nominal positif (akan dikurangi dari saldo)
```

### Via Transaksi (Enum)
```
'transfer' = TRANSFER BANK
- Melalui transfer bank
- Perlu bukti transfer

'cash' = TUNAI
- Langsung di kantor
- Via janji temu
```

### Jenis Bukti Foto (Enum)
```
'tabungan' = BUKTI SETORAN
- Untuk pengajuan setoran
- Upload oleh nasabah atau admin

'penarikan' = BUKTI PENARIKAN
- Untuk pengajuan penarikan (jarang digunakan)
```

---

## 🔒 KEAMANAN & VALIDASI

### A. Authentication & Authorization

#### Nasabah Side:
```php
// Middleware: auth
Route::prefix('nasabah')->middleware('auth')->name('nasabah.')->group(...)

// Get ID Anggota:
$idAnggota = auth()->user()->nasabah->id;

// Security Check:
- Setiap query harus filter by id_anggota
- Nasabah hanya bisa akses data milik sendiri
- Verifikasi ownership di controller
```

#### Admin Side:
```php
// Middleware: auth + admin role (jika ada)
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(...)

// Admin bisa akses semua data nasabah
- Tidak perlu filter by id_anggota
- Logging semua aksi admin (recommended)
```

---

### B. Validasi Input

#### 1. Pengajuan Setoran (Transfer)
```php
Validasi:
- nominal: required|numeric|min:10000
- bukti_foto.*: required|image|max:5120 (5MB)
- nominal_foto.*: required|string (parsed to numeric)
- keterangan_foto.*: nullable|string|max:255
- keterangan: nullable|string|max:500
- pin: required|numeric|digits:6

Security:
- Validasi format file (hanya image)
- Validasi ukuran file (max 5MB)
- Sanitize input keterangan
- Verify PIN sebelum submit
```

#### 2. Janji Temu (Tunai)
```php
Validasi:
- nominal: required|numeric|min:10000
- lokasi_temu: required|exists:jns_lokasi_perusahaan,id
- tanggal_janji_temu: required|date|after:today
- waktu_janji_temu: required|date_format:H:i
- keterangan: nullable|string|max:500
- pin: required|numeric|digits:6

Security:
- Tanggal harus > hari ini
- Lokasi harus ada di database
- Verify PIN
```

#### 3. Pengajuan Penarikan
```php
Validasi:
- metode: required|in:tunai,transfer
- nominal: required|numeric|min:10000
- keterangan: nullable|string|max:500
- no_rekening: required_if:metode,transfer|string|max:50

Business Logic:
- Cek saldo: nominal <= saldo
- Jika tidak cukup: Return error

Security:
- No rekening required jika transfer
- Validasi format rekening (opsional)
```

#### 4. Admin Approve/Reject
```php
Approve Setoran:
- Cek nominal > 0 dari bukti foto atau janji temu
- Cek duplikasi transaksi
- Create transaksi otomatis

Approve Penarikan:
- Cek saldo nasabah >= nominal
- Jika tidak cukup: Return error
- Create transaksi otomatis

Reject:
- keterangan: required|string (alasan penolakan)
```

---

### C. PIN Verification

#### Sistem PIN:
```php
// PIN disimpan di tbl_users
- Format: 6 digit numeric
- Disimpan sebagai plain text (pertimbangkan hashing)
- Digunakan untuk verifikasi transaksi

// Verifikasi PIN:
Route: POST /nasabah/tabungan/verify-pin
Request: { pin: '123456' }
Response: { success: true/false, message: '...' }

// Flow:
1. User input PIN di modal
2. AJAX request ke verify-pin
3. Validasi PIN
4. Return success/failed
5. Jika success: Submit form utama
6. Jika failed: Tampilkan error message
```

**Rekomendasi Keamanan:**
- Hash PIN di database (bcrypt)
- Implement rate limiting (max 3 attempts)
- Lock account setelah 5 failed attempts
- Two-factor authentication (opsional)

---

### D. File Upload Security

```php
// Storage:
- Disk: public
- Path: storage/app/public/bukti_tabungan/

// Validasi:
- Allowed types: jpg, jpeg, png, gif
- Max size: 5MB per file
- Validate MIME type
- Sanitize filename
- Generate unique filename

// Access Control:
- Public access via storage/app/public/
- Atau implement private storage + authorized access
- Check ownership sebelum display
```

---

### E. Business Logic Validation

#### Perhitungan Saldo:
```php
private function getSaldoNasabah($idAnggota)
{
    // 1. Total setoran dari trans_tabungan
    $totalSetoran = TransTabungan::where('id_anggota', $idAnggota)
        ->where('jenis', 'setoran')
        ->sum('nominal') ?? 0;

    // 2. Total penarikan dari trans_tabungan
    $totalPenarikan = TransTabungan::where('id_anggota', $idAnggota)
        ->where('jenis', 'penarikan')
        ->sum('nominal') ?? 0;

    // 3. Pengajuan approved yang belum ada transaksi
    $pengajuanApproved = PengajuanTabungan::where('id_anggota', $idAnggota)
        ->where('status', '2') // Approved
        ->whereDoesntHave('transTabungan')
        ->with('buktiFoto', 'janjiTemu')
        ->get();

    foreach ($pengajuanApproved as $pengajuan) {
        $nominal = 0;
        if ($pengajuan->buktiFoto && $pengajuan->buktiFoto->count() > 0) {
            $nominal = $pengajuan->buktiFoto->sum('nominal');
        } elseif ($pengajuan->janjiTemu) {
            $nominal = $pengajuan->janjiTemu->nominal ?? 0;
        }
        $totalSetoran += $nominal;
    }

    // 4. Return saldo (min 0)
    return max(0, $totalSetoran - $totalPenarikan);
}
```

**Catatan:**
- Saldo tidak bisa negatif
- Pengajuan approved tapi belum ada transaksi tetap dihitung
- Ini untuk handle edge case admin approve via web tapi transaksi dibuat manual

---

#### Validasi Penarikan:
```php
// Before approve penarikan:
$saldo = $this->getSaldoNasabah($pengajuan->id_anggota);

if ($saldo < $pengajuan->nominal) {
    return redirect()->back()
        ->with('error', 'Saldo nasabah tidak mencukupi');
}

// OK, lanjut approve
```

---

#### Prevent Duplicate Transaction:
```php
// Sebelum create transaksi dari pengajuan:
if ($pengajuan->transTabungan->count() > 0) {
    return redirect()->back()
        ->with('error', 'Transaksi untuk pengajuan ini sudah pernah dibuat');
}

// OK, create transaksi baru
```

---

#### Auto Approve Flow:
```php
// Approve Setoran:
1. Update status pengajuan = '2' (Approved)
2. Hitung nominal dari bukti foto atau janji temu
3. Create transaksi di trans_tabungan (jika nominal > 0)
4. Link transaksi ke pengajuan (id_pengajuan_setor)

// Approve Penarikan:
1. Validasi saldo >= nominal
2. Update status pengajuan = '2' (Approved)
3. Create transaksi di trans_tabungan (jenis: penarikan)
4. Link transaksi ke pengajuan (id_pengajuan_tarik)
```

---

## 📝 CATATAN PENTING

### 1. Relationship Models

#### PengajuanTabungan:
```php
- belongsTo: Nasabah (id_anggota)
- hasMany: BuktiFotoTabungan (id_pengajuan)
- hasOne: JanjiTemuTabungan (id_pengajuan)
- hasMany: TransTabungan (id_pengajuan_setor)
```

#### PengajuanPenarikanTabungan:
```php
- belongsTo: Nasabah (id_anggota)
- hasMany: TransTabungan (id_pengajuan_tarik)
```

#### TransTabungan:
```php
- belongsTo: Nasabah (id_anggota)
- belongsTo: PengajuanTabungan (id_pengajuan_setor) - nullable
- belongsTo: PengajuanPenarikanTabungan (id_pengajuan_tarik) - nullable
```

#### BuktiFotoTabungan:
```php
- belongsTo: PengajuanTabungan (id_pengajuan)
```

#### JanjiTemuTabungan:
```php
- belongsTo: PengajuanTabungan (id_pengajuan)
- belongsTo: JnsLokasiPerusahaan (lokasi_temu)
```

---

### 2. Eager Loading (Optimization)

```php
// Nasabah - Status Pengajuan:
PengajuanTabungan::where('id_anggota', $idAnggota)
    ->with(['buktiFoto', 'janjiTemu.lokasi', 'transTabungan'])
    ->latest()
    ->paginate(10);

// Admin - Pengajuan Setor:
PengajuanTabungan::with([
        'nasabah.user',
        'nasabah.dataKtp',
        'nasabah.dataRek',
        'buktiFoto',
        'janjiTemu.lokasi',
        'transTabungan'
    ])
    ->where('status', '1')
    ->latest()
    ->paginate(15);

// Admin - Transaksi:
TransTabungan::with([
        'nasabah.user',
        'pengajuanSetor.buktiFoto',
        'pengajuanTarik'
    ])
    ->latest('tgl_transaksi')
    ->paginate(20);
```

---

### 3. Route Naming Convention

```
Nasabah:
- nasabah.tabungan.index
- nasabah.tabungan.nabung-sekarang
- nasabah.tabungan.pengajuan-transfer (POST)
- nasabah.tabungan.penarikan
- nasabah.tabungan.submit-penarikan (POST)
- nasabah.tabungan.janji-temu
- nasabah.tabungan.submit-janji-temu (POST)
- nasabah.tabungan.verify-pin (POST)
- nasabah.tabungan.status-pengajuan-setor
- nasabah.tabungan.status-pengajuan-tarik
- nasabah.tabungan.detail-pengajuan-setor
- nasabah.tabungan.detail-pengajuan-tarik
- nasabah.tabungan.detail-transaksi
- nasabah.tabungan.detail-janji-temu

Admin:
- admin.tabungan.index
- admin.tabungan.pengajuan-setor
- admin.tabungan.detail-pengajuan-setor
- admin.tabungan.approve-setor (POST)
- admin.tabungan.reject-setor (POST)
- admin.tabungan.edit-pengajuan-setor (POST)
- admin.tabungan.delete-pengajuan-setor (DELETE)
- admin.tabungan.pengajuan-tarik
- admin.tabungan.detail-pengajuan-tarik
- admin.tabungan.approve-tarik (POST)
- admin.tabungan.reject-tarik (POST)
- admin.tabungan.transaksi
- admin.tabungan.detail-transaksi
- admin.tabungan.janji-temu
- admin.tabungan.detail-janji-temu
- admin.tabungan.create-trans-from-janji-temu (POST)
- admin.tabungan.saldo-nasabah
```

---

### 4. Session Flash Messages

```php
// Success:
->with('success', 'Pengajuan setoran berhasil dikirim!')

// Error:
->with('error', 'Saldo tidak mencukupi!')

// Warning:
->with('warning', 'Data belum lengkap')

// Info:
->with('info', 'Pengajuan sedang diproses')

// Display di Blade:
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
```

---

### 5. Currency Formatting

```javascript
// Format Input (JavaScript):
function formatRupiah(angka) {
    return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Parse ke Numeric (PHP):
$nominal = (float) str_replace(['.', ',', 'Rp', ' '], '', $request->nominal);

// Display (Blade):
Rp {{ number_format($transaksi->nominal, 0, ',', '.') }}
```

---

### 6. Timestamp Formatting

```php
// Carbon (in Controller):
$transaksi->tgl_transaksi->format('d M Y H:i')

// Blade:
{{ $transaksi->created_at->format('d F Y, H:i') }}
{{ $transaksi->created_at->diffForHumans() }} // "2 jam yang lalu"
```

---

### 7. Status Badge HTML

```html
<!-- Status Pengajuan -->
@if($pengajuan->status == '1')
    <span class="badge bg-warning">Pending</span>
@elseif($pengajuan->status == '2')
    <span class="badge bg-success">Approved</span>
@else
    <span class="badge bg-danger">Rejected</span>
@endif

<!-- Jenis Transaksi -->
@if($transaksi->jenis == 'setoran')
    <span class="badge bg-success">Setoran</span>
@else
    <span class="badge bg-danger">Penarikan</span>
@endif

<!-- Via -->
@if($transaksi->via == 'transfer')
    <span class="badge bg-info">Transfer</span>
@else
    <span class="badge bg-success">Cash</span>
@endif
```

---

### 8. Image Display (Bukti Foto)

```html
<!-- Gallery with Lightbox -->
<div class="row">
    @foreach($pengajuan->buktiFoto as $bukti)
    <div class="col-md-4 mb-3">
        <a href="{{ Storage::url($bukti->file_photo) }}" data-lightbox="bukti-transfer">
            <img src="{{ Storage::url($bukti->file_photo) }}" 
                 class="img-thumbnail" 
                 alt="Bukti Transfer">
        </a>
        <p class="mt-2">
            <strong>Nominal:</strong> Rp {{ number_format($bukti->nominal, 0, ',', '.') }}<br>
            <strong>Keterangan:</strong> {{ $bukti->keterangan }}
        </p>
    </div>
    @endforeach
</div>
```

---

### 9. Pagination Custom

```php
// Controller:
$pengajuan = PengajuanTabungan::where('id_anggota', $idAnggota)
    ->latest()
    ->paginate(10);

// Blade:
{{ $pengajuan->links() }}

// With query string:
{{ $pengajuan->appends(request()->query())->links() }}
```

---

### 10. Error Handling Best Practices

```php
try {
    // Get ID anggota
    $idAnggota = $this->getIdAnggota();
    
    // Business logic...
    
} catch (\Illuminate\Auth\AuthenticationException $e) {
    return redirect()->route('login')
        ->with('error', 'Session Anda telah berakhir. Silakan login kembali.');
        
} catch (\Illuminate\Auth\Access\AuthorizationException $e) {
    return redirect()->route('nasabah.dashboard')
        ->with('error', $e->getMessage());
        
} catch (\Exception $e) {
    Log::error('Error in TabunganController: ' . $e->getMessage());
    return redirect()->back()
        ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
        ->withInput($request->except('pin'));
}
```

---

## ✅ CHECKLIST IMPLEMENTASI

### Prioritas KRITIS (Harus Segera):
- [x] Implementasi authentication middleware
- [x] Ganti hardcoded `$idAnggota` dengan `auth()->user()->nasabah->id`
- [x] Perbaiki perhitungan saldo di semua method
- [x] Implementasi PIN verification
- [x] Validasi ownership di detail methods

### Prioritas TINGGI:
- [x] Auto create transaksi saat approve
- [x] Validasi saldo sebelum approve penarikan
- [x] Prevent duplicate transaction
- [x] Multiple file upload untuk bukti transfer
- [x] Form create transaksi dari janji temu

### Prioritas SEDANG:
- [x] Filter & search di list pages
- [x] Pagination
- [x] Eager loading optimization
- [x] Session flash messages
- [ ] Export to Excel/PDF (saldo nasabah)
- [ ] Logging admin actions

### Prioritas RENDAH:
- [ ] Calendar view untuk janji temu
- [ ] Grafik statistik (dashboard)
- [ ] Email notification (approve/reject)
- [ ] WhatsApp notification (opsional)
- [ ] Print bukti transaksi
- [ ] Audit trail

---

## 🎓 PENJELASAN TEKNIS

### Mengapa Field `foto_bukti_tf` Tidak Menyimpan Path File?

**Design Decision:**
- Satu pengajuan bisa punya **multiple bukti foto** (multiple upload)
- Jika disimpan di satu field: Sulit di-query, sulit validasi
- **Solusi**: Pisahkan ke tabel terpisah (`tbl_bukti_foto_tabungan`)
- Field `foto_bukti_tf` hanya sebagai **flag/indicator** jenis pengajuan

**Alternatif yang Dipertimbangkan:**
1. JSON array di satu field → Sulit di-query
2. Comma-separated string → Sulit validasi
3. **Tabel terpisah (dipilih)** → Clean, scalable, easy to query

---

### Mengapa Saldo Tidak Disimpan di Tabel?

**Design Decision:**
- Saldo adalah **derived value** (nilai turunan)
- Hitung dari transaksi: `SUM(setoran) - SUM(penarikan)`
- **Keuntungan**: Always accurate, no sync issue
- **Trade-off**: Sedikit slower (tapi bisa di-cache)

**Jika Disimpan di Tabel:**
- **Risiko**: Saldo tidak sinkron jika ada bug
- **Kompleksitas**: Harus update saldo setiap transaksi
- **Data Inconsistency**: Jika ada transaksi yang dihapus/diubah

**Solusi Saat Ini:**
```php
// Method private di controller
private function getSaldoNasabah($idAnggota)
{
    // Hitung realtime dari trans_tabungan
    // Tambah pengajuan approved yang belum ada transaksi
    // Return saldo
}

// Bisa di-cache untuk performance:
Cache::remember("saldo_nasabah_{$idAnggota}", 300, function() {
    return $this->getSaldoNasabah($idAnggota);
});
```

---

### Mengapa Ada Pengajuan Approved Tanpa Transaksi?

**Edge Case:**
- Admin approve via web (status = '2')
- Tapi belum klik "Buat Transaksi" (untuk janji temu)
- **Solusi**: Method `getSaldoNasabah()` tetap hitung pengajuan approved

**Contoh:**
```php
// Pengajuan approved tapi belum ada transaksi
$pengajuanApproved = PengajuanTabungan::where('id_anggota', $idAnggota)
    ->where('status', '2') // Approved
    ->whereDoesntHave('transTabungan') // Belum ada transaksi
    ->with('buktiFoto', 'janjiTemu')
    ->get();

// Hitung nominal dari bukti foto atau janji temu
foreach ($pengajuanApproved as $pengajuan) {
    $nominal = 0;
    if ($pengajuan->buktiFoto->count() > 0) {
        $nominal = $pengajuan->buktiFoto->sum('nominal');
    } elseif ($pengajuan->janjiTemu) {
        $nominal = $pengajuan->janjiTemu->nominal ?? 0;
    }
    $totalSetoran += $nominal;
}
```

---

### Flow Approve Otomatis vs Manual

**Approve Transfer (Otomatis):**
```
Admin klik "Setujui" 
→ Update status = '2'
→ Hitung nominal dari bukti foto
→ Auto create transaksi di trans_tabungan
→ Done
```

**Approve Tunai (Manual):**
```
Nasabah buat janji temu
→ Admin lihat di menu "Janji Temu"
→ Pada waktu bertemu: Admin terima uang
→ Admin buka detail janji temu
→ Isi form "Buat Transaksi" (nominal, foto, keterangan, tanggal)
→ Klik "Buat Transaksi"
→ Update status pengajuan = '2'
→ Create transaksi di trans_tabungan
→ Done
```

**Mengapa Berbeda?**
- Transfer: Bukti sudah ada, nominal sudah jelas
- Tunai: Nominal bisa berbeda, perlu konfirmasi fisik

---

## 📞 TROUBLESHOOTING

### Problem 1: Saldo Tidak Sesuai
**Gejala**: Saldo yang ditampilkan tidak sesuai transaksi

**Penyebab:**
- Ada pengajuan approved tanpa transaksi
- Ada transaksi duplicate
- Kesalahan perhitungan di `getSaldoNasabah()`

**Solusi:**
1. Cek method `getSaldoNasabah()` di controller
2. Cek query: `trans_tabungan` harus filter by `id_anggota`
3. Cek pengajuan approved: Harus sum dari `buktiFoto` atau `janjiTemu`
4. Debug dengan `dd()` atau `Log::info()`

---

### Problem 2: PIN Tidak Berfungsi
**Gejala**: Verifikasi PIN selalu gagal

**Penyebab:**
- User belum set PIN
- PIN salah format (string vs int)
- Route verify-pin tidak ada

**Solusi:**
```php
// Controller:
$userPin = (int) $user->pin;
$inputPin = (int) $request->pin;

if ($userPin !== $inputPin) {
    // Error
}

// Cek user punya PIN:
if (!$user->pin) {
    return response()->json([
        'success' => false,
        'message' => 'PIN belum diatur'
    ], 400);
}
```

---

### Problem 3: Upload Bukti Foto Gagal
**Gejala**: Error saat upload file

**Penyebab:**
- File terlalu besar
- Format tidak didukung
- Storage belum di-link
- Permission folder salah

**Solusi:**
```bash
# Link storage:
php artisan storage:link

# Check permission:
chmod -R 775 storage/app/public

# Check php.ini:
upload_max_filesize = 10M
post_max_size = 10M
```

---

### Problem 4: Transaksi Duplicate
**Gejala**: Satu pengajuan punya multiple transaksi

**Penyebab:**
- Admin klik "Setujui" multiple kali
- Tidak ada validasi duplicate

**Solusi:**
```php
// Before create transaksi:
if ($pengajuan->transTabungan->count() > 0) {
    return redirect()->back()
        ->with('error', 'Transaksi sudah pernah dibuat');
}
```

---

### Problem 5: Pengajuan Tidak Muncul
**Gejala**: Pengajuan yang dibuat tidak muncul di list

**Penyebab:**
- Filter status default (hanya pending)
- Hardcoded `id_anggota`
- Query error

**Solusi:**
1. Cek filter status di controller
2. Pastikan `id_anggota` dari auth user
3. Cek relasi nasabah di user model
4. Debug query: `DB::enableQueryLog()`

---

## 🚀 OPTIMISASI PERFORMANCE

### 1. Database Indexing
```sql
-- Index untuk query filtering
ALTER TABLE tbl_pengajuan_tabungan ADD INDEX idx_anggota_status (id_anggota, status);
ALTER TABLE tbl_pengajuan_penarikan_tabungan ADD INDEX idx_anggota_status (id_anggota, status);
ALTER TABLE trans_tabungan ADD INDEX idx_anggota_jenis (id_anggota, jenis);
ALTER TABLE trans_tabungan ADD INDEX idx_tanggal (tgl_transaksi);

-- Index untuk foreign keys (auto di Laravel)
-- Tapi pastikan sudah ada
```

### 2. Eager Loading
```php
// Bad: N+1 Query Problem
$pengajuan = PengajuanTabungan::all();
foreach ($pengajuan as $p) {
    echo $p->nasabah->user->nama; // Query each iteration
}

// Good: Eager Loading
$pengajuan = PengajuanTabungan::with('nasabah.user')->get();
foreach ($pengajuan as $p) {
    echo $p->nasabah->user->nama; // No additional query
}
```

### 3. Caching Saldo
```php
// Cache saldo untuk 5 menit
$saldo = Cache::remember("saldo_{$idAnggota}", 300, function() use ($idAnggota) {
    return $this->getSaldoNasabah($idAnggota);
});

// Clear cache saat ada transaksi baru
Cache::forget("saldo_{$idAnggota}");
```

### 4. Pagination
```php
// Selalu gunakan pagination untuk list
$pengajuan = PengajuanTabungan::paginate(15);

// Jangan: ->get() untuk data banyak
```

### 5. Select Only Needed Columns
```php
// Bad: Select all columns
$nasabah = Nasabah::all();

// Good: Select only needed
$nasabah = Nasabah::select('id', 'nama', 'email')->get();
```

---

## 📚 DOKUMENTASI API (Jika Ada)

### Endpoint: Verify PIN
```
POST /nasabah/tabungan/verify-pin

Request:
{
    "pin": "123456"
}

Response Success:
{
    "success": true,
    "message": "PIN berhasil diverifikasi."
}

Response Error:
{
    "success": false,
    "message": "PIN yang Anda masukkan salah."
}

Status Codes:
- 200: Success
- 400: Bad Request (PIN salah atau belum diatur)
- 401: Unauthenticated
```

---

## 🎯 KESIMPULAN

### Status Sistem: **LENGKAP & BERFUNGSI**

**Yang Sudah Baik:**
- ✅ Flow sistem lengkap (Setoran & Penarikan)
- ✅ Support multiple metode (Transfer & Tunai)
- ✅ Multiple file upload
- ✅ PIN verification
- ✅ Auto approve & create transaksi
- ✅ Validasi saldo realtime
- ✅ Filter & search
- ✅ Pagination
- ✅ Security (authentication & authorization)
- ✅ Business logic validation
- ✅ Relationship models lengkap

**Fitur Tambahan yang Bisa Diimplementasikan:**
- 📧 Email notification
- 📱 WhatsApp notification  
- 📊 Export to Excel/PDF
- 📈 Dashboard analytics
- 📅 Calendar view (janji temu)
- 🖨️ Print bukti transaksi
- 📝 Audit trail
- 🔔 Push notification

**Rekomendasi Prioritas Berikutnya:**
1. Email notification (approve/reject)
2. Export report (saldo, transaksi)
3. Dashboard analytics
4. Audit trail (logging admin actions)
5. Enhanced security (hash PIN, rate limiting)

---

**Dokumen ini dibuat untuk memberikan pemahaman lengkap tentang Sistem Tabungan Koperasi Majakara.**

**Last Updated**: {{ date('d F Y') }}
**Version**: 1.0
**Author**: Development Team

---

## 📞 KONTAK & SUPPORT

Jika ada pertanyaan atau issue terkait sistem tabungan, silakan hubungi:
- **Developer**: [Your Contact]
- **Project Manager**: [PM Contact]
- **Documentation**: Lihat file ini dan ANALISIS_SISTEM_TABUNGAN_NASABAH.md

---

**END OF DOCUMENT**
