# 📋 RANGKUMAN ALUR PEMINJAMAN TERBARU
**Tanggal Referensi: 23 Januari 2026**

---

## 📊 TABEL-TABEL UTAMA

### 1. **tbl_pengajuan_pinjaman** (Pengajuan Pinjaman)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| id_anggota | foreignId | FK ke tbl_nasabah |
| tgl_pengajuan | datetime | Tanggal pengajuan |
| nominal | decimal(15,2) | Nominal yang diajukan |
| jenis | enum | 'bulanan' atau 'mingguan' |
| durasi | integer | Durasi pinjaman (bulan/minggu) |
| jenis_pencairan | enum | 'transfer' atau 'cash' |
| status | enum | '1'=Pending, '2'=Ditolak, '3'=Disetujui, '4'=Terlaksana |
| keterangan | text | Keterangan pengajuan |
| timestamps | - | created_at, updated_at |

### 2. **tbl_pinjaman_h** (Data Pinjaman)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| id_anggota | foreignId | FK ke tbl_nasabah |
| id_pengajuan | foreignId | FK ke tbl_pengajuan_pinjaman |
| jumlah_pinjam | decimal(15,2) | Jumlah yang diterima nasabah (setelah potong bunga) |
| lama_pinjam | integer | Durasi pinjaman |
| jenis | enum | 'bulanan' atau 'mingguan' |
| bunga | decimal(5,4) | Bunga dalam decimal (misal 0.15 = 15%) |
| bunga_rp | decimal(15,2) | Bunga dalam rupiah |
| denda_persen | decimal(5,2) | Persentase denda per hari (misal 2%) |
| tgl_pinjam | datetime | Tanggal pinjaman |
| foto_bukti_transfer | string | Path foto bukti transfer (jika transfer) |
| foto_serah_terima | string | Path foto serah terima (jika cash) |
| status | enum | 'menunggu', 'pencairan', 'telaksana' |
| lunas | enum | 'belum' atau 'lunas' |
| timestamps | - | created_at, updated_at |

**Catatan Sistem Bunga:**
- **Nominal pengajuan** = Total yang harus dibayar kembali
- **jumlah_pinjam** = Nominal - bunga_rp (jumlah yang diterima nasabah)
- **Total tagihan** = Nominal = jumlah_pinjam + bunga_rp

### 3. **tempo_pinjaman_b** (Jadwal Angsuran Bulanan)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| pinjaman_id | foreignId | FK ke tbl_pinjaman_h |
| anggota_id | foreignId | FK ke tbl_nasabah |
| no_urut | integer | Nomor urut angsuran |
| tgl_jatuh_tempo | datetime | Tanggal jatuh tempo |
| jumlah_tagihan | decimal(15,2) | Jumlah yang harus dibayar |
| jumlah_terbayar | decimal(15,2) | Jumlah yang sudah dibayar |
| denda | decimal(15,2) | Denda jika telat |
| tgl_bayar | datetime | Tanggal pembayaran |
| status_bayar | enum | 'belum', 'lunas', 'telat' |
| timestamps | - | created_at, updated_at |

### 4. **tempo_pinjaman_m** (Jadwal Angsuran Mingguan)
Struktur sama dengan `tempo_pinjaman_b`, hanya berbeda periode.

### 5. **tbl_janji_temu_pinjaman** (Janji Temu Pencairan Cash)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| id_pengajuan | foreignId | FK ke tbl_pengajuan_pinjaman |
| lokasi_temu | foreignId | FK ke jns_lokasi_perusahaan |
| nominal | decimal(15,2) | Nominal pinjaman |
| tanggal_janji_temu | datetime | Tanggal janji temu |
| waktu_janji_temu | time | Waktu janji temu |
| keterangan | text | Keterangan |
| timestamps | - | created_at, updated_at |

### 6. **tbl_bukti_foto_pinjaman** (Bukti Foto Pencairan)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| id_pinjaman | foreignId | FK ke tbl_pinjaman_h |
| id_pengajuan | foreignId | FK ke tbl_pengajuan_pinjaman |
| file_photo | string | Path file foto |
| jenis | enum | 'bukti_transfer' atau 'serah_terima' |
| keterangan | text | Keterangan |
| timestamps | - | created_at, updated_at |

### 7. **tbl_pengajuan_pembayaran_pinjaman** (Pengajuan Pembayaran)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| id_anggota | foreignId | FK ke tbl_nasabah |
| pinjaman_id | foreignId | FK ke tbl_pinjaman_h |
| tempo_id | bigint | ID angsuran (bulanan/mingguan) |
| jenis_tempo | enum | 'bulanan' atau 'mingguan' |
| nominal | decimal(15,2) | Nominal pembayaran |
| rekening_tujuan | string | Rekening tujuan (jika transfer) |
| keterangan | text | Keterangan |
| status | enum | '1'=Pending, '2'=Ditolak, '3'=Disetujui, '4'=Terlaksana |
| tgl_pembayaran | datetime | Tanggal pembayaran |
| timestamps | - | created_at, updated_at |

### 8. **tbl_janji_temu_pembayaran_pinjaman** (Janji Temu Pembayaran Cash)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| id_pengajuan | foreignId | FK ke tbl_pengajuan_pembayaran_pinjaman |
| lokasi_temu | foreignId | FK ke jns_lokasi_perusahaan |
| nominal | decimal(15,2) | Nominal pembayaran |
| tanggal_janji_temu | datetime | Tanggal janji temu |
| waktu_janji_temu | time | Waktu janji temu |
| keterangan | text | Keterangan |
| timestamps | - | created_at, updated_at |

### 9. **tbl_bukti_foto_pembayaran_pinjaman** (Bukti Foto Pembayaran)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| id_pengajuan | foreignId | FK ke tbl_pengajuan_pembayaran_pinjaman |
| file_photo | string | Path file foto |
| jenis | enum | 'bukti_transfer' atau 'serah_terima' |
| keterangan | text | Keterangan |
| timestamps | - | created_at, updated_at |

---

## 🔄 ALUR PEMINJAMAN (DARI PENGAJUAN SAMPAI PEMBAYARAN)

### **TAHAP 1: PENGAJUAN PINJAMAN**

#### **A. Pengajuan via Transfer**
1. **Nasabah:**
   - Isi form: nominal, jenis (bulanan/mingguan), durasi
   - Pilih jenis pencairan = **transfer**
   - Verifikasi PIN (6 digit)
   - Submit pengajuan
   - **Status:** '1' (Pending)

2. **Admin:**
   - Lihat pengajuan di dashboard
   - Review data nasabah
   - Setujui dengan input:
     * Bunga (%)
     * Bunga (Rp)
     * Denda persen (% per hari)
   - Sistem otomatis:
     * Buat record di `tbl_pinjaman_h` dengan status 'pencairan'
     * Hitung: `jumlah_pinjam = nominal - bunga_rp`
     * Generate jadwal angsuran di `tempo_pinjaman_b` atau `tempo_pinjaman_m`
   - Admin upload bukti transfer
   - Update status pinjaman menjadi 'telaksana'
   - **Status pengajuan:** '4' (Terlaksana)

#### **B. Pengajuan via Cash (Janji Temu)**
1. **Nasabah:**
   - Isi form: nominal, jenis, durasi
   - Pilih jenis pencairan = **cash**
   - Isi form janji temu:
     * Pilih lokasi kantor
     * Tanggal janji temu
     * Waktu janji temu
   - Verifikasi PIN
   - Submit pengajuan
   - **Status:** '1' (Pending)
   - Data tersimpan di `tbl_pengajuan_pinjaman` dan `tbl_janji_temu_pinjaman`

2. **Admin:**
   - Lihat pengajuan + janji temu
   - Review data
   - Setujui dengan input bunga & denda
   - Sistem buat pinjaman + generate jadwal angsuran
   - Admin & nasabah bertemu sesuai jadwal
   - Admin terima pembayaran tunai
   - Admin upload foto serah terima
   - Update status pinjaman menjadi 'telaksana'
   - **Status pengajuan:** '4' (Terlaksana)

---

### **TAHAP 2: PEMBAYARAN ANGSURAN**

#### **A. Pembayaran via Transfer**
1. **Nasabah:**
   - Pilih pinjaman aktif
   - Pilih angsuran yang akan dibayar
   - Isi nominal pembayaran
   - Isi rekening tujuan
   - Upload bukti transfer (multiple files)
   - Verifikasi PIN
   - Submit pengajuan pembayaran
   - **Status:** '1' (Pending)
   - Data tersimpan di `tbl_pengajuan_pembayaran_pinjaman` dan `tbl_bukti_foto_pembayaran_pinjaman`

2. **Admin:**
   - Lihat pengajuan pembayaran
   - Verifikasi:
     * Nominal
     * Rekening tujuan
     * Bukti transfer
   - Approve → **Status:** '3' (Disetujui)
   - Verifikasi bukti transfer di bank
   - Konfirmasi pembayaran → **Status:** '4' (Terlaksana)
   - Sistem otomatis update:
     * Hitung denda (jika telat)
     * Update `jumlah_terbayar` di angsuran
     * Update `status_bayar` (belum/lunas/telat)
     * Update `tgl_bayar`
   - Jika semua angsuran lunas → update pinjaman `lunas = 'lunas'`

#### **B. Pembayaran via Cash (Janji Temu)**
1. **Nasabah:**
   - Pilih pinjaman aktif
   - Pilih angsuran yang akan dibayar
   - Pilih metode = Cash (Janji Temu)
   - Isi form janji temu:
     * Pilih lokasi kantor
     * Tanggal janji temu
     * Waktu janji temu
   - Verifikasi PIN
   - Submit pengajuan
   - **Status:** '1' (Pending)
   - Data tersimpan di `tbl_pengajuan_pembayaran_pinjaman` dan `tbl_janji_temu_pembayaran_pinjaman`

2. **Admin:**
   - Lihat pengajuan pembayaran + janji temu
   - Verifikasi data janji temu
   - Approve → **Status:** '3' (Disetujui)
   - Konfirmasi jadwal dengan nasabah
   - Admin & nasabah bertemu sesuai jadwal
   - Admin terima pembayaran tunai
   - Admin upload foto serah terima
   - **Status:** '4' (Terlaksana)
   - Sistem otomatis update angsuran (sama seperti transfer)

---

## ⚙️ SISTEM & LOGIKA

### **1. Sistem Bunga di Awal**
- **Nominal pengajuan** = Total yang harus dibayar kembali
- **Bunga dipotong di awal**
- **jumlah_pinjam** = Nominal - bunga_rp (yang diterima nasabah)
- **Total tagihan** = Nominal = jumlah_pinjam + bunga_rp
- **Contoh:**
  - Nominal: Rp 10.000.000
  - Bunga: Rp 1.500.000
  - jumlah_pinjam: Rp 8.500.000 (yang diterima)
  - Total tagihan: Rp 10.000.000 (yang harus dibayar)

### **2. Generate Jadwal Angsuran**
- Dibuat otomatis saat admin approve pengajuan
- **Bulanan:** Jatuh tempo setiap bulan (tgl_pinjam + 1 bulan, +2 bulan, dst)
- **Mingguan:** Jatuh tempo setiap minggu (tgl_pinjam + 1 minggu, +2 minggu, dst)
- **Jumlah per angsuran:** Total tagihan / jumlah angsuran
- **Contoh:** Pinjaman 12 bulan, total tagihan Rp 10.000.000
  - Per angsuran: Rp 10.000.000 / 12 = Rp 833.333,33

### **3. Perhitungan Denda**
- **Denda dihitung per hari telat**
- **Rumus:** `denda = sisa_tagihan × (denda_persen / 100) × hari_telat`
- **Maksimal denda:** 50% dari jumlah tagihan
- **Contoh:**
  - Sisa tagihan: Rp 833.333
  - Denda persen: 2% per hari
  - Hari telat: 5 hari
  - Denda: Rp 833.333 × (2/100) × 5 = Rp 83.333,3

### **4. Status Angsuran**
- **belum:** Belum dibayar dan belum jatuh tempo
- **telat:** Sudah jatuh tempo tapi belum lunas
- **lunas:** Sudah dibayar penuh (termasuk denda jika ada)

### **5. Status Pinjaman**
- **menunggu:** Menunggu approval
- **pencairan:** Sudah disetujui, menunggu pencairan
- **telaksana:** Sudah dicairkan
- **lunas:** Semua angsuran sudah lunas

### **6. Pelunasan Dipercepat**
- Admin bisa lakukan pelunasan dipercepat
- Hitung sisa tagihan pokok + total denda
- Opsional: potongan (diskon)
- Update semua angsuran menjadi lunas
- Update pinjaman menjadi lunas

---

## 📅 CONTOH ALUR DENGAN TANGGAL (23/01/2026)

### **Skenario: Pinjaman Bulanan 12 Bulan, Rp 10.000.000**

1. **23/01/2026** - Nasabah ajukan pinjaman (transfer)
2. **23/01/2026** - Admin approve, set bunga 15% (Rp 1.500.000)
   - jumlah_pinjam: Rp 8.500.000
   - Generate 12 angsuran @ Rp 833.333,33
   - Jatuh tempo: 23/02, 23/03, ..., 23/12/2026
3. **23/01/2026** - Admin upload bukti transfer
4. **23/01/2026** - Status pinjaman: 'telaksana'
5. **23/02/2026** - Angsuran ke-1 jatuh tempo
6. **25/02/2026** - Nasabah bayar angsuran ke-1 (telat 2 hari)
   - Denda: Rp 833.333 × 2% × 2 = Rp 33.333
   - Total bayar: Rp 833.333 + Rp 33.333 = Rp 866.666
7. **23/03/2026** - Angsuran ke-2 jatuh tempo
8. **23/03/2026** - Nasabah bayar tepat waktu
9. ... (sampai semua lunas)
10. **23/12/2026** - Angsuran ke-12 lunas → Pinjaman lunas

---

## ✅ FITUR YANG SUDAH ADA

- ✅ Pengajuan pinjaman (transfer & cash)
- ✅ Verifikasi PIN untuk pengajuan
- ✅ Approval workflow
- ✅ Generate jadwal angsuran otomatis
- ✅ Pencairan via transfer (dengan bukti)
- ✅ Pencairan via cash (dengan janji temu & foto serah terima)
- ✅ Pembayaran angsuran via transfer
- ✅ Pembayaran angsuran via cash (janji temu)
- ✅ Perhitungan denda otomatis
- ✅ Tracking status angsuran
- ✅ Pelunasan dipercepat
- ✅ Dashboard pinjaman (admin & nasabah)

---

## 📝 CATATAN PENTING

1. **Bunga di awal:** Nasabah menerima jumlah setelah potong bunga
2. **Total tagihan:** Sama dengan nominal pengajuan (bukan nominal + bunga)
3. **Denda:** Dihitung otomatis saat pembayaran jika telat
4. **Status angsuran:** Update otomatis saat pembayaran dikonfirmasi
5. **Pinjaman lunas:** Update otomatis jika semua angsuran lunas

