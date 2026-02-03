# ANALISIS DATABASE & CONTROLLER - SISTEM TABUNGAN & PINJAMAN

> 📅 **Tanggal:** 3 Februari 2026  
> 🎯 **Fokus:** Struktur Database, Alur Data, Controller Methods

---

# 🏦 BAGIAN 1: SISTEM TABUNGAN

## 📊 DATABASE TABLES - TABUNGAN

### 1️⃣ TABLE: `tbl_pengajuan_tabungan`

**Fungsi:** Menyimpan pengajuan setoran tabungan dari nasabah

| Kolom | Tipe | Null | Default | Digunakan | Keterangan |
|-------|------|------|---------|-----------|------------|
| `id` | bigint unsigned | NO | auto | ✅ | Primary key |
| `id_anggota` | bigint unsigned | NO | - | ✅ | FK ke tbl_nasabah - ID nasabah yang mengajukan |
| `nominal` | decimal(15,2) | NO | 0.00 | ✅ | Nominal setoran (untuk transfer), 0 untuk tunai |
| `foto_bukti_tf` | varchar(255) | NO | - | ✅ | Indikator: 'transfer' atau 'tunai' |
| `keterangan` | text | YES | NULL | ✅ | Keterangan tambahan dari nasabah |
| `status` | enum('1','2','3') | NO | 1 | ✅ | 1=Pending, 2=Rejected, 3=Approved |
| `created_at` | timestamp | YES | NULL | ✅ | Timestamp pengajuan dibuat |
| `updated_at` | timestamp | YES | NULL | ✅ | Timestamp terakhir update |

**Alur Data:**
1. **Transfer:** `nominal` diisi, `foto_bukti_tf` = 'transfer'
2. **Tunai:** `nominal` = 0, `foto_bukti_tf` = 'tunai', nominal sebenarnya ada di `tbl_janji_temu_tabungan`

---

### 2️⃣ TABLE: `tbl_bukti_foto_tabungan`

**Fungsi:** Menyimpan file foto bukti transfer untuk pengajuan tabungan

| Kolom | Tipe | Null | Digunakan | Keterangan |
|-------|------|------|-----------|------------|
| `id` | bigint unsigned | NO | ✅ | Primary key |
| `id_pengajuan` | bigint unsigned | NO | ✅ | FK ke tbl_pengajuan_tabungan |
| `file_photo` | varchar(255) | NO | ✅ | Path file di storage/bukti_tabungan/ |
| `jenis` | enum('tabungan','penarikan') | NO | ✅ | Selalu 'tabungan' untuk setoran |
| `created_at` | timestamp | YES | ✅ | Timestamp upload |
| `updated_at` | timestamp | YES | ✅ | Timestamp update |

**Alur Data:**
- Bisa multiple rows per `id_pengajuan` (nasabah bisa upload beberapa bukti)
- Hanya untuk pengajuan TRANSFER

---

### 3️⃣ TABLE: `tbl_janji_temu_tabungan`

**Fungsi:** Menyimpan jadwal janji temu untuk setoran tunai

| Kolom | Tipe | Null | Digunakan | Keterangan |
|-------|------|------|-----------|------------|
| `id` | bigint unsigned | NO | ✅ | Primary key |
| `id_pengajuan` | bigint unsigned | NO | ✅ | FK ke tbl_pengajuan_tabungan |
| `lokasi_temu` | bigint unsigned | NO | ✅ | FK ke jns_lokasi_perusahaan |
| `nominal` | decimal(15,2) | NO | ✅ | **PENTING: Nominal untuk tunai disimpan DI SINI** |
| `tanggal_janji_temu` | datetime | NO | ✅ | Tanggal janji temu |
| `waktu_janji_temu` | timestamp | NO | ✅ | Waktu janji temu |
| `created_at` | timestamp | YES | ✅ | Auto timestamp |
| `updated_at` | timestamp | YES | ✅ | Auto timestamp |

**Alur Data:**
- Hanya ada 1 row per `id_pengajuan`
- Hanya untuk pengajuan TUNAI/CASH
- Admin membuat transaksi berdasarkan nominal di sini

---

### 4️⃣ TABLE: `trans_tabungan`

**Fungsi:** Menyimpan transaksi tabungan (setoran & penarikan) yang sudah final

| Kolom | Tipe | Null | Default | Digunakan | Keterangan |
|-------|------|------|---------|-----------|------------|
| `id` | bigint unsigned | NO | auto | ✅ | Primary key |
| `id_transaksi` | varchar(50) | NO | - | ✅ | ID unik format: YYYYMMDD-SEQ-TAB |
| `id_pengajuan_setor` | bigint unsigned | YES | NULL | ✅ | FK ke tbl_pengajuan_tabungan (jika setoran) |
| `id_pengajuan_tarik` | bigint unsigned | YES | NULL | ✅ | FK ke tbl_pengajuan_penarikan_tabungan (jika penarikan) |
| `id_anggota` | bigint unsigned | NO | - | ✅ | FK ke tbl_nasabah |
| `id_jns_akun` | bigint unsigned | YES | NULL | ✅ | FK ke jns_akun (kode='TAB') |
| `nominal` | decimal(15,2) | NO | - | ✅ | Nominal transaksi |
| `keterangan` | text | YES | NULL | ✅ | Keterangan transaksi |
| `jenis` | enum('setoran','penarikan') | NO | - | ✅ | Jenis transaksi |
| `via` | enum('transfer','cash') | NO | - | ✅ | Metode transaksi |
| `tgl_transaksi` | timestamp | NO | CURRENT | ✅ | Tanggal transaksi |
| `created_at` | timestamp | YES | NULL | ✅ | Auto timestamp |
| `updated_at` | timestamp | YES | NULL | ✅ | Auto timestamp |

**Alur Data:**
1. Dibuat oleh admin saat approve pengajuan
2. Untuk setoran: `id_pengajuan_setor` terisi
3. Untuk penarikan: `id_pengajuan_tarik` terisi
4. Digunakan untuk hitung saldo nasabah

---

### 5️⃣ TABLE: `tbl_pengajuan_penarikan_tabungan`

**Fungsi:** Menyimpan pengajuan penarikan tabungan dari nasabah

| Kolom | Tipe | Null | Default | Digunakan | Keterangan |
|-------|------|------|---------|-----------|------------|
| `id` | bigint unsigned | NO | auto | ✅ | Primary key |
| `id_anggota` | bigint unsigned | NO | - | ✅ | FK ke tbl_nasabah |
| `tgl_pengajuan` | datetime | NO | - | ✅ | Tanggal pengajuan |
| `nominal` | decimal(15,2) | NO | - | ✅ | Nominal yang ingin ditarik |
| `metode_transfer` | varchar(50) | YES | NULL | ✅ | 'tunai' atau 'transfer' |
| `no_rekening` | varchar(50) | YES | NULL | ✅ | No rekening tujuan (jika transfer) |
| `nama_bank` | varchar(100) | YES | NULL | ✅ | Nama bank tujuan (jika transfer) |
| `foto_bukti_tf_admin` | varchar(255) | YES | NULL | ✅ | Bukti transfer dari admin (diupload saat approve) |
| `keterangan` | text | YES | NULL | ✅ | Keterangan tambahan |
| `status` | enum('1','2','3') | NO | 1 | ✅ | 1=Pending, 2=Approved, 3=Rejected |
| `created_at` | timestamp | YES | NULL | ✅ | Auto timestamp |
| `updated_at` | timestamp | YES | NULL | ✅ | Auto timestamp |

**Alur Data:**
1. Nasabah submit penarikan
2. Admin review dan upload bukti TF (jika metode=transfer)
3. Admin approve → otomatis buat transaksi di `trans_tabungan`

---

### 6️⃣ TABLE: `jns_akun`

**Fungsi:** Master data jenis akun untuk ID transaksi

| Kolom | Tipe | Digunakan | Keterangan |
|-------|------|-----------|------------|
| `id` | bigint unsigned | ✅ | Primary key |
| `kode_akun` | varchar(20) | ✅ | Kode: 'TAB' untuk tabungan |
| `nama_akun` | varchar(100) | ✅ | Nama akun |
| `deskripsi` | text | ✅ | Deskripsi |
| `prefix_id` | varchar(10) | ✅ | Prefix untuk ID transaksi (ex: 'TAB') |
| `is_active` | tinyint(1) | ✅ | Status aktif |

---

## 🎮 CONTROLLER METHODS - TABUNGAN

### 📘 `TabunganController` (Nasabah)
**Location:** `app/Http/Controllers/Nasabah/TabunganController.php`

#### **Method: `submitSetoran()`** - Line 150-239
- **Trigger:** Form pengajuan setoran TRANSFER di-submit
- **Input:** `nominal`, `bukti_foto[]`, `keterangan`, `pin`
- **Proses:**
  1. Validasi input & PIN
  2. **INSERT** `tbl_pengajuan_tabungan` → `nominal`, `foto_bukti_tf='transfer'`, `status='1'`
  3. **INSERT MULTIPLE** `tbl_bukti_foto_tabungan` → untuk setiap foto yang diupload
- **Output:** Redirect ke status pengajuan

#### **Method: `submitJanjiTemu()`** - Line 258-350
- **Trigger:** Form janji temu TUNAI di-submit
- **Input:** `nominal`, `lokasi_temu`, `tanggal_janji_temu`, `waktu_janji_temu`, `pin`
- **Proses:**
  1. Validasi input & PIN
  2. **INSERT** `tbl_pengajuan_tabungan` → `foto_bukti_tf='tunai'`, `nominal=0`, `status='1'`
  3. **INSERT** `tbl_janji_temu_tabungan` → `nominal`, `lokasi_temu`, `tanggal/waktu`
- **Output:** Redirect ke status pengajuan

#### **Method: `submitPenarikan()`** - Line 355-389
- **Trigger:** Form pengajuan penarikan di-submit
- **Input:** `metode`, `nominal`, `nama_bank`, `no_rekening`, `keterangan`
- **Proses:**
  1. Validasi saldo (harus cukup)
  2. **INSERT** `tbl_pengajuan_penarikan_tabungan` → semua data, `status='1'`
- **Output:** Redirect ke status pengajuan penarikan

#### **Method: `getSaldoNasabah()`** - Line 492-523 (Private)
- **Fungsi:** Hitung saldo nasabah real-time
- **Perhitungan:**
  ```
  Total Setoran = SUM(trans_tabungan WHERE jenis='setoran')
  Total Penarikan = SUM(trans_tabungan WHERE jenis='penarikan')
  Pengajuan Approved = SUM pengajuan status='2' yang belum ada di trans_tabungan
  Saldo = (Total Setoran + Pengajuan Approved) - Total Penarikan
  ```

---

### 📗 `TabunganController` (Admin)
**Location:** `app/Http/Controllers/Admin/TabunganController.php`

#### **Method: `approveSetor()`** - Line 104-147
- **Trigger:** Admin klik approve pada pengajuan setoran
- **Input:** `id` pengajuan
- **Proses:**
  1. **SELECT** pengajuan with relations
  2. Tentukan nominal (dari pengajuan atau janji temu)
  3. **UPDATE** `tbl_pengajuan_tabungan` → `status='3'`
  4. **INSERT** `trans_tabungan` (jika belum ada) →
     - `id_transaksi`: generate YYYYMMDD-SEQ-TAB
     - `id_pengajuan_setor`: id pengajuan
     - `jenis='setoran'`
     - `via`: 'cash' (jika janji temu) atau 'transfer'
- **Output:** Redirect dengan success message
- **⚠️ PENTING:** Cek dulu apakah transaksi sudah ada untuk avoid duplikasi

#### **Method: `approveTarik()`** - Line 215-268
- **Trigger:** Admin approve pengajuan penarikan
- **Input:** `id`, `foto_bukti_tf_admin` (jika transfer), `bank_pengirim`
- **Proses:**
  1. Validasi saldo nasabah
  2. Upload foto bukti TF admin (jika metode=transfer)
  3. **UPDATE** `tbl_pengajuan_penarikan_tabungan` → `status='2'`, `foto_bukti_tf_admin`
  4. **INSERT** `trans_tabungan` →
     - `id_pengajuan_tarik`: id pengajuan
     - `jenis='penarikan'`
     - `via`: sesuai metode
- **Output:** Redirect dengan success

#### **Method: `createTransFromJanjiTemu()`** - Line 372-432
- **Trigger:** Admin input transaksi dari halaman detail janji temu
- **Input:** `nominal`, `keterangan`, `foto_penerimaan`, `tgl_transaksi`
- **Proses:**
  1. Validasi nominal >= 10000
  2. Cek transaksi belum pernah dibuat
  3. Upload foto penerimaan (optional)
  4. **INSERT** `tbl_bukti_foto_tabungan` (jika ada foto)
  5. **UPDATE** `tbl_pengajuan_tabungan` → `status='2'`
  6. **INSERT** `trans_tabungan` → `jenis='setoran'`, `via='cash'`
- **Output:** Redirect ke detail janji temu

---

# 💰 BAGIAN 2: SISTEM PINJAMAN

## 📊 DATABASE TABLES - PINJAMAN

### 1️⃣ TABLE: `tbl_pengajuan_pinjaman`

**Fungsi:** Menyimpan pengajuan pinjaman dari nasabah (sebelum dicairkan)

| Kolom | Tipe | Default | Digunakan | Keterangan |
|-------|------|---------|-----------|------------|
| `id` | bigint unsigned | auto | ✅ | Primary key |
| `id_anggota` | bigint unsigned | - | ✅ | FK ke tbl_nasabah |
| `tgl_pengajuan` | datetime | - | ✅ | Tanggal pengajuan |
| `nominal` | decimal(15,2) | - | ✅ | Nominal pinjaman yang diajukan |
| `jenis` | enum('bulanan','mingguan') | - | ✅ | Jenis angsuran (saat ini hanya 'bulanan') |
| `durasi` | int | - | ✅ | Durasi pinjaman dalam bulan (1-24) |
| `jenis_pencairan` | enum('transfer','cash') | transfer | ✅ | Metode pencairan |
| `status` | enum('1','2','3','4') | 1 | ✅ | 1=Pending, 2=Ditolak, 3=Disetujui, 4=Terlaksana |
| `keterangan` | text | NULL | ✅ | Keterangan tambahan |
| `tgl_cair` | datetime | NULL | ✅ | Tanggal pencairan (diisi saat dicairkan) |
| `bunga_persen` | decimal(5,2) | NULL | ✅ | Bunga persen (diisi saat approve) |

**Alur Status:**
1. `'1'` → Pending (baru diajukan)
2. `'2'` → Ditolak
3. `'3'` → Disetujui (admin approve, belum dicairkan)
4. `'4'` → Terlaksana (sudah dicairkan, pinjaman dibuat)

---

### 2️⃣ TABLE: `tbl_pinjaman_h`

**Fungsi:** Menyimpan data pinjaman yang sudah dicairkan (header pinjaman)

| Kolom | Tipe | Default | Digunakan | Keterangan |
|-------|------|---------|-----------|------------|
| `id` | bigint unsigned | auto | ✅ | Primary key |
| `id_anggota` | bigint unsigned | - | ✅ | FK ke tbl_nasabah |
| `id_pengajuan` | bigint unsigned | - | ✅ | FK ke tbl_pengajuan_pinjaman |
| `jumlah_pinjam` | decimal(15,2) | - | ✅ | Nominal yang diterima nasabah |
| `lama_pinjam` | int | - | ✅ | Durasi dalam bulan |
| `jenis` | enum('bulanan','mingguan') | - | ✅ | Jenis angsuran |
| `bunga` | decimal(10,4) | - | ✅ | Bunga dalam decimal (0.15 = 15%) |
| `bunga_rp` | decimal(15,2) | - | ✅ | Total bunga dalam rupiah |
| `denda_persen` | decimal(5,2) | - | ✅ | Persen denda per hari (0.30 = 0.3%/hari) |
| `ags_bulan` | int | - | ❌ | TIDAK DIGUNAKAN |
| `ags_minggu` | int | - | ❌ | TIDAK DIGUNAKAN |
| `tgl_pinjam` | datetime | - | ✅ | Tanggal pencairan |
| `saldo_lebih` | decimal(15,2) | - | ❌ | TIDAK DIGUNAKAN |
| `foto_bukti_transfer` | varchar(255) | - | ✅ | Foto bukti transfer pencairan |
| `foto_serah_terima` | varchar(255) | - | ✅ | Foto serah terima (jika cash) |
| `status` | enum('pencairan','telaksana') | - | ✅ | Selalu 'telaksana' saat ini |
| `lunas` | enum('belum','lunas') | - | ✅ | Status pelunasan |

**Alur Data:**
- Dibuat saat admin cairkan pinjaman (status pengajuan 3→4)
- Tempo/angsuran di-generate otomatis saat dibuat

---

### 3️⃣ TABLE: `tempo_pinjaman_b`

**Fungsi:** Menyimpan jadwal angsuran BULANAN

| Kolom | Tipe | Default | Digunakan | Keterangan |
|-------|------|---------|-----------|------------|
| `id` | bigint unsigned | auto | ✅ | Primary key |
| `pinjaman_id` | bigint unsigned | - | ✅ | FK ke tbl_pinjaman_h |
| `anggota_id` | bigint unsigned | - | ✅ | FK ke tbl_nasabah (untuk query cepat) |
| `no_urut` | int | - | ✅ | Angsuran ke- (1, 2, 3, ...) |
| `tgl_jatuh_tempo` | datetime | - | ✅ | Tanggal jatuh tempo |
| `jumlah_tagihan` | decimal(15,2) | - | ✅ | Pokok + bunga per bulan |
| `jumlah_terbayar` | decimal(15,2) | 0.00 | ✅ | Total yang sudah dibayar |
| `denda` | decimal(15,2) | 0.00 | ✅ | Denda keterlambatan |
| `tgl_bayar` | datetime | NULL | ✅ | Tanggal pembayaran (jika sudah bayar) |
| `status_bayar` | enum('belum','lunas','telat') | belum | ✅ | Status pembayaran |

**Rumus Perhitungan:**
```
pokok_per_bulan = jumlah_pinjam / lama_pinjam
bunga_per_bulan = bunga_rp / lama_pinjam
jumlah_tagihan = pokok_per_bulan + bunga_per_bulan
```

**Rumus Denda:**
```
pokok_per_bulan = jumlah_pinjam / lama_pinjam
hari_telat = (now - tgl_jatuh_tempo) - 1 hari (mulai H+1)
denda = pokok_per_bulan × (denda_persen / 100) × hari_telat

CATATAN: Denda BERHENTI jika sudah ada pembayaran (walaupun Rp 1)
```

---

### 4️⃣ TABLE: `tbl_pengajuan_pembayaran_pinjaman`

**Fungsi:** Menyimpan pengajuan pembayaran angsuran dari nasabah

| Kolom | Tipe | Default | Digunakan | Keterangan |
|-------|------|---------|-----------|------------|
| `id` | bigint unsigned | auto | ✅ | Primary key |
| `id_anggota` | bigint unsigned | - | ✅ | FK ke tbl_nasabah |
| `pinjaman_id` | bigint unsigned | - | ✅ | FK ke tbl_pinjaman_h |
| `tempo_id` | bigint unsigned | NULL | ✅ | FK ke tempo_pinjaman_b/m (angsuran mana yang dibayar) |
| `jenis_tempo` | enum('bulanan','mingguan') | NULL | ✅ | Jenis tempo |
| `nominal` | decimal(15,2) | - | ✅ | Jumlah pembayaran |
| `rekening_tujuan` | varchar(255) | NULL | ✅ | Rekening tujuan transfer |
| `keterangan` | text | NULL | ✅ | Keterangan tambahan |
| `status` | enum('1','2','3','4') | 1 | ✅ | 1=Pending, 2=Ditolak, 3=Disetujui, 4=??? |
| `tgl_pembayaran` | datetime | NULL | ✅ | Tanggal pembayaran |

**Alur:** Nasabah submit → Admin approve → Admin input ke tempo

---

### 5️⃣ TABLE: `tbl_bukti_foto_pembayaran_pinjaman`

**Fungsi:** Menyimpan foto bukti pembayaran angsuran

| Kolom | Tipe | Digunakan | Keterangan |
|-------|------|-----------|------------|
| `id` | bigint unsigned | ✅ | Primary key |
| `id_pengajuan` | bigint unsigned | ✅ | FK ke tbl_pengajuan_pembayaran_pinjaman |
| `file_photo` | varchar(255) | ✅ | Path file foto |
| `jenis` | enum('bukti_transfer','serah_terima') | ✅ | Jenis foto |
| `keterangan` | text | ✅ | Keterangan foto |

---

### 6️⃣ TABLE: `tbl_janji_temu_pinjaman`

**Fungsi:** Jadwal janji temu untuk pengajuan pinjaman cash

| Kolom | Tipe | Digunakan | Keterangan |
|-------|------|-----------|------------|
| `id` | bigint unsigned | ✅ | Primary key |
| `id_pengajuan` | bigint unsigned | ✅ | FK ke tbl_pengajuan_pinjaman |
| `lokasi_temu` | bigint unsigned | ✅ | FK ke jns_lokasi_perusahaan |
| `nominal` | decimal(15,2) | ✅ | Nominal pinjaman |
| `tanggal_janji_temu` | datetime | ✅ | Tanggal janji temu |
| `waktu_janji_temu` | time | ✅ | Waktu janji temu |
| `keterangan` | text | ✅ | Keterangan |

---

### 7️⃣ TABLE: `tbl_janji_temu_pembayaran_pinjaman`

**Fungsi:** Jadwal janji temu untuk pembayaran angsuran cash

| Kolom | Tipe | Digunakan | Keterangan |
|-------|------|-----------|------------|
| `id` | bigint unsigned | ✅ | Primary key |
| `id_pengajuan` | bigint unsigned | ✅ | FK ke tbl_pengajuan_pembayaran_pinjaman |
| `lokasi_temu` | bigint unsigned | ✅ | FK ke jns_lokasi_perusahaan |
| `nominal` | decimal(15,2) | ✅ | Nominal pembayaran |
| `tanggal_janji_temu` | datetime | ✅ | Tanggal |
| `waktu_janji_temu` | time | ✅ | Waktu |
| `keterangan` | text | ✅ | Keterangan |

---

## 🎮 CONTROLLER METHODS - PINJAMAN

### 📘 `PinjamanController` (Nasabah)
**Location:** `app/Http/Controllers/Nasabah/PinjamanController.php`

#### **Method: `submitPengajuanTransfer()`** - Line 242-332
- **Trigger:** Form pengajuan pinjaman TRANSFER
- **Input:** `nominal`, `durasi`, `pin`, `keterangan`
- **Proses:**
  1. Validasi & verifikasi PIN
  2. **INSERT** `tbl_pengajuan_pinjaman` →
     - `nominal`, `durasi`
     - `jenis='bulanan'`
     - `jenis_pencairan='transfer'`
     - `status='1'`
- **Output:** Redirect ke daftar pengajuan

#### **Method: `submitJanjiTemuPinjaman()`** - Line 402-511
- **Trigger:** Form pengajuan pinjaman TUNAI
- **Input:** `nominal`, `durasi`, `lokasi_temu`, `tanggal_janji_temu`, `waktu_janji_temu`, `pin`
- **Proses:**
  1. Validasi & PIN
  2. **INSERT** `tbl_pengajuan_pinjaman` → `jenis_pencairan='cash'`, `status='1'`
  3. **INSERT** `tbl_janji_temu_pinjaman` → semua data janji temu
- **Output:** Redirect

---

### 📗 `PinjamanController` (Admin)
**Location:** `app/Http/Controllers/Admin/PinjamanController.php`

#### **Method: `approvePengajuan()`** - Line 123-155
- **Trigger:** Admin approve pengajuan (Status 1→3)
- **Input:** `id` pengajuan
- **Proses:**
  1. Get bunga dari `master_bunga_pinjaman` by durasi
  2. Get denda dari `master_denda_pinjaman`
  3. **UPDATE** `tbl_pengajuan_pinjaman` →
     - `status='3'`
     - `bunga_persen`: dari master
- **Output:** Redirect ke detail (belum dicairkan)
- **⚠️ CATATAN:** Belum buat pinjaman, hanya update status

#### **Method: `cairkanPinjaman()`** - Line 161-244
- **Trigger:** Admin klik cairkan (Status 3→4)
- **Input:** `tgl_cair`, `bukti_transfer` (optional)
- **Proses (TRANSACTION):**
  1. Hitung bunga:
     ```
     bunga_rp = (nominal × bunga_persen) / 100
     jumlah_pinjam = nominal
     ```
  2. **INSERT** `tbl_pinjaman_h` →
     - `jumlah_pinjam`, `lama_pinjam`, `bunga`, `bunga_rp`
     - `denda_persen`: dari master
     - `status='telaksana'`, `lunas='belum'`
  3. **CALL** `generateJadwalAngsuran()` → INSERT ke `tempo_pinjaman_b`
  4. Upload foto bukti
  5. **UPDATE** `tbl_pengajuan_pinjaman` → `status='4'`, `tgl_cair`
- **Output:** Redirect ke detail pinjaman

#### **Method: `generateJadwalAngsuran()`** - Line 383-416 (Private)
- **Fungsi:** Generate jadwal angsuran
- **Proses:**
  ```php
  FOR i = 1 to lama_pinjam:
    tgl_jatuh_tempo = tgl_pinjam + i bulan
    pokok_per_bulan = jumlah_pinjam / lama_pinjam
    bunga_per_bulan = bunga_rp / lama_pinjam
    jumlah_tagihan = pokok_per_bulan + bunga_per_bulan
    
    INSERT tempo_pinjaman_b {
      pinjaman_id, anggota_id, no_urut: i,
      tgl_jatuh_tempo, jumlah_tagihan,
      jumlah_terbayar: 0, denda: 0, status_bayar: 'belum'
    }
  ```

#### **Method: `updatePembayaranAngsuran()`** - Line 542-614
- **Trigger:** Admin input pembayaran dari pengajuan pembayaran
- **Input:** `jumlah_bayar`, `jenis` (bulanan/mingguan)
- **Proses:**
  1. Get angsuran & pinjaman
  2. Hitung denda (dengan method `hitungDenda()`)
  3. `total_tagihan = jumlah_tagihan + denda`
  4. `jumlah_terbayar_baru = jumlah_terbayar_lama + jumlah_bayar`
  5. Tentukan status:
     - Jika >= total_tagihan: `status='lunas'`, `denda=0`
     - Jika >= jumlah_tagihan tapi < total: `status='telat'`
     - Jika telat dan belum lunas: `status='telat'`
  6. **UPDATE** `tempo_pinjaman_b` → `jumlah_terbayar`, `denda`, `status_bayar`, `tgl_bayar`
  7. Cek semua angsuran, jika semua lunas:
     - **UPDATE** `tbl_pinjaman_h` → `lunas='lunas'`
- **Output:** Success message

#### **Method: `hitungDenda()`** - Line 497-537 (Private)
- **Fungsi:** Hitung denda keterlambatan
- **Rules:**
  1. Jika `status_bayar='lunas'`: return denda tersimpan
  2. Jika `jumlah_terbayar > 0`: return denda tersimpan (denda BERHENTI)
  3. Jika belum H+1 setelah jatuh tempo: return 0
  4. Hitung:
     ```
     hari_telat = now - (tgl_jatuh_tempo + 1 hari)
     pokok_per_bulan = jumlah_pinjam / lama_pinjam
     denda = pokok_per_bulan × (denda_persen / 100) × hari_telat
     ```

---

## 📊 FLOW DIAGRAM - ALUR DATA

### 🏦 TABUNGAN - Setoran Transfer
```
Nasabah Submit Form
    ↓
INSERT tbl_pengajuan_tabungan (status='1', nominal=X, foto_bukti_tf='transfer')
    ↓
INSERT tbl_bukti_foto_tabungan (multiple rows untuk setiap foto)
    ↓
Admin Review
    ↓
UPDATE tbl_pengajuan_tabungan (status='3')
    ↓
INSERT trans_tabungan (jenis='setoran', via='transfer', id_pengajuan_setor=X)
    ↓
Saldo Nasabah Bertambah (dihitung dari trans_tabungan)
```

### 🏦 TABUNGAN - Setoran Tunai (Janji Temu)
```
Nasabah Submit Form Janji Temu
    ↓
INSERT tbl_pengajuan_tabungan (status='1', nominal=0, foto_bukti_tf='tunai')
    ↓
INSERT tbl_janji_temu_tabungan (nominal=X, tanggal=Y, lokasi=Z)
    ↓
Admin Terima Setoran Tunai di Lokasi
    ↓
Admin Input Transaksi dari Halaman Janji Temu
    ↓
UPDATE tbl_pengajuan_tabungan (status='2')
    ↓
INSERT trans_tabungan (jenis='setoran', via='cash', nominal dari janji_temu)
    ↓
Saldo Bertambah
```

### 🏦 TABUNGAN - Penarikan
```
Nasabah Submit Penarikan
    ↓
Validasi Saldo (dari getSaldoNasabah())
    ↓
INSERT tbl_pengajuan_penarikan_tabungan (status='1', metode=X)
    ↓
Admin Review & Upload Bukti TF (jika metode=transfer)
    ↓
UPDATE tbl_pengajuan_penarikan_tabungan (status='2', foto_bukti_tf_admin=Y)
    ↓
INSERT trans_tabungan (jenis='penarikan', via=metode, id_pengajuan_tarik=X)
    ↓
Saldo Berkurang
```

### 💰 PINJAMAN - Pengajuan sampai Pencairan
```
Nasabah Submit Pengajuan
    ↓
INSERT tbl_pengajuan_pinjaman (status='1', nominal=X, durasi=Y)
    ↓ (jika cash)
INSERT tbl_janji_temu_pinjaman
    ↓
Admin Approve Pengajuan
    ↓
Get Bunga dari master_bunga_pinjaman by durasi
Get Denda dari master_denda_pinjaman
    ↓
UPDATE tbl_pengajuan_pinjaman (status='3', bunga_persen=Z)
    ↓
Admin Cairkan Pinjaman
    ↓
BEGIN TRANSACTION
    ↓
Hitung: bunga_rp = (nominal × bunga_persen) / 100
    ↓
INSERT tbl_pinjaman_h (jumlah_pinjam=nominal, bunga_rp=X, status='telaksana', lunas='belum')
    ↓
Generate Jadwal Angsuran (Loop):
  FOR i=1 to durasi:
    tgl_jatuh_tempo = tgl_cair + i bulan
    pokok_per_bulan = nominal / durasi
    bunga_per_bulan = bunga_rp / durasi
    jumlah_tagihan = pokok + bunga
    INSERT tempo_pinjaman_b (no_urut=i, jumlah_tagihan=X, status_bayar='belum')
    ↓
UPDATE tbl_pengajuan_pinjaman (status='4', tgl_cair=now)
    ↓
COMMIT
    ↓
Pinjaman Aktif (bisa dibayar angsurannya)
```

### 💰 PINJAMAN - Pembayaran Angsuran
```
Nasabah Submit Pembayaran
    ↓
INSERT tbl_pengajuan_pembayaran_pinjaman (status='1', tempo_id=X, nominal=Y)
    ↓ (jika transfer)
INSERT tbl_bukti_foto_pembayaran_pinjaman
    ↓ (jika cash)
INSERT tbl_janji_temu_pembayaran_pinjaman
    ↓
Admin Approve
    ↓
UPDATE tbl_pengajuan_pembayaran_pinjaman (status='3')
    ↓
Admin Input Pembayaran ke Tempo
    ↓
GET tempo (SELECT dari tempo_pinjaman_b WHERE id=tempo_id)
Hitung Denda (jika telat):
  - Jika jumlah_terbayar > 0: denda tidak bertambah
  - Jika belum bayar & telat: denda = pokok_per_bulan × 0.3% × hari_telat
    ↓
total_tagihan = jumlah_tagihan + denda
jumlah_terbayar_baru = jumlah_terbayar_lama + nominal_bayar
    ↓
Tentukan Status:
  IF jumlah_terbayar_baru >= total_tagihan:
    status_bayar = 'lunas', denda = 0
  ELSE IF sudah lewat jatuh tempo:
    status_bayar = 'telat'
  ELSE:
    status_bayar = 'belum'
    ↓
UPDATE tempo_pinjaman_b (jumlah_terbayar=X, denda=Y, status_bayar=Z, tgl_bayar=now)
    ↓
Check SEMUA Angsuran di Pinjaman ini
    ↓
IF SEMUA tempo status_bayar='lunas':
  UPDATE tbl_pinjaman_h (lunas='lunas')
    ↓
Selesai
```

---

## 📋 KESIMPULAN

### ✅ Table yang AKTIF Digunakan - TABUNGAN:
1. `tbl_pengajuan_tabungan` - Pengajuan setoran
2. `tbl_bukti_foto_tabungan` - Bukti transfer setoran
3. `tbl_janji_temu_tabungan` - Janji temu setoran tunai
4. `trans_tabungan` - Transaksi final (setoran & penarikan)
5. `tbl_pengajuan_penarikan_tabungan` - Pengajuan penarikan
6. `jns_akun` - Master jenis akun

### ✅ Table yang AKTIF Digunakan - PINJAMAN:
1. `tbl_pengajuan_pinjaman` - Pengajuan pinjaman
2. `tbl_pinjaman_h` - Data pinjaman yang sudah dicairkan
3. `tempo_pinjaman_b` - Jadwal angsuran bulanan
4. `tbl_pengajuan_pembayaran_pinjaman` - Pengajuan pembayaran angsuran
5. `tbl_bukti_foto_pembayaran_pinjaman` - Bukti pembayaran
6. `tbl_janji_temu_pinjaman` - Janji temu pengajuan pinjaman
7. `tbl_janji_temu_pembayaran_pinjaman` - Janji temu pembayaran
8. `master_bunga_pinjaman` - Master bunga
9. `master_denda_pinjaman` - Master denda

### ❌ Kolom yang TIDAK Digunakan:
**tbl_pinjaman_h:**
- `ags_bulan` - Tidak digunakan
- `ags_minggu` - Tidak digunakan  
- `saldo_lebih` - Tidak digunakan

### 🔑 Key Points:
1. **Nominal Tunai Tabungan:** Disimpan di `tbl_janji_temu_tabungan`, BUKAN di `tbl_pengajuan_tabungan`
2. **ID Transaksi:** Format YYYYMMDD-SEQ-PREFIX (ex: 20260203-001-TAB)
3. **Saldo:** Dihitung real-time dari `trans_tabungan` + pengajuan approved
4. **Bunga Pinjaman:** Dibagi merata ke setiap angsuran (BUKAN dipotong di awal)
5. **Denda:** 0.3%/hari dari POKOK per bulan, mulai H+1, BERHENTI saat ada pembayaran

---

📅 **Dokumentasi dibuat:** 3 Februari 2026  
🔍 **Berdasarkan:** Scanning database + source code lengkap
