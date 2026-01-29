# 📋 RANGKUMAN ALUR PEMINJAMAN TERBARU (REVISI)
**Tanggal Update: 26 Januari 2026**

---

## 🆕 PERUBAHAN SISTEM TERBARU

### **Revisi Major:**
1. ✅ **Pinjaman HANYA BULANAN** (mingguan dihapus dari UI/controller, tabel database tetap ada)
2. ✅ **Durasi: 1-24 bulan** (sebelumnya max 12 bulan)
3. ✅ **Bunga TIDAK dipotong di awal** - Nasabah menerima nominal penuh
4. ✅ **Bunga otomatis dari Master Data** - Range 10%-24% berdasarkan durasi
5. ✅ **Denda 0.3% per hari** (bukan 2%)
6. ✅ **Denda mulai 1 hari setelah jatuh tempo**
7. ✅ **Denda berhenti jika sudah ada pembayaran**
8. ✅ **Simulasi tabel angsuran** saat nasabah mengajukan
9. ✅ **CRUD Pinjaman untuk admin** (untuk yang ketemu langsung)
10. ✅ **Master Data Bunga & Denda**

---

## 📊 TABEL-TABEL UTAMA

### 1. **master_bunga_pinjaman** (Master Data Bunga) - BARU!
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| durasi_min | integer | Durasi minimum (bulan) |
| durasi_max | integer | Durasi maksimum (bulan) |
| bunga_persen | decimal(5,2) | Persentase bunga |
| status_aktif | boolean | Status aktif/nonaktif |
| keterangan | text | Keterangan |
| timestamps | - | created_at, updated_at |

**Data Default:**
- 1-3 bulan: 10%
- 4-6 bulan: 12%
- 7-9 bulan: 14%
- 10-12 bulan: 16%
- 13-15 bulan: 18%
- 16-18 bulan: 20%
- 19-21 bulan: 22%
- 22-24 bulan: 24%

### 2. **master_denda_pinjaman** (Master Data Denda) - BARU!
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| denda_persen | decimal(5,2) | Persentase denda per hari |
| status_aktif | boolean | Status aktif/nonaktif |
| keterangan | text | Keterangan |
| timestamps | - | created_at, updated_at |

**Data Default:**
- Denda: 0.3% per hari

### 3. **tbl_pengajuan_pinjaman** (Pengajuan Pinjaman)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| id_anggota | foreignId | FK ke tbl_nasabah |
| tgl_pengajuan | datetime | Tanggal pengajuan |
| nominal | decimal(15,2) | Nominal yang diajukan |
| jenis | enum | **'bulanan'** (mingguan dihapus dari UI) |
| durasi | integer | Durasi pinjaman **(1-24 bulan)** |
| jenis_pencairan | enum | 'transfer' atau 'cash' |
| status | enum | '1'=Pending, '2'=Ditolak, '3'=Disetujui, '4'=Terlaksana |
| keterangan | text | Keterangan pengajuan |
| timestamps | - | created_at, updated_at |

### 4. **tbl_pinjaman_h** (Data Pinjaman)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| id_anggota | foreignId | FK ke tbl_nasabah |
| id_pengajuan | foreignId | FK ke tbl_pengajuan_pinjaman (nullable untuk CRUD admin) |
| jumlah_pinjam | decimal(15,2) | **Jumlah yang diterima = nominal penuh** |
| lama_pinjam | integer | Durasi pinjaman (1-24 bulan) |
| jenis | enum | **'bulanan'** |
| bunga | decimal(5,4) | Bunga dalam decimal (dari master data) |
| bunga_rp | decimal(15,2) | Bunga dalam rupiah |
| denda_persen | decimal(5,2) | **0.3%** per hari (dari master data) |
| tgl_pinjam | datetime | Tanggal pinjaman |
| foto_bukti_transfer | string | Path foto bukti transfer (jika transfer) |
| foto_serah_terima | string | Path foto serah terima (jika cash) |
| status | enum | 'menunggu', 'pencairan', 'telaksana' |
| lunas | enum | 'belum' atau 'lunas' |
| timestamps | - | created_at, updated_at |

**Catatan Sistem Bunga BARU:**
- **Nominal pengajuan** = Jumlah yang diterima nasabah
- **Bunga TIDAK dipotong di awal**
- **jumlah_pinjam** = Nominal (sama dengan yang diajukan)
- **Total tagihan** = Nominal + bunga_rp
- **Bunga dibagi ke setiap angsuran bulanan**

### 5. **tempo_pinjaman_b** (Jadwal Angsuran Bulanan)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| pinjaman_id | foreignId | FK ke tbl_pinjaman_h |
| anggota_id | foreignId | FK ke tbl_nasabah |
| no_urut | integer | Nomor urut angsuran |
| tgl_jatuh_tempo | datetime | Tanggal jatuh tempo |
| jumlah_tagihan | decimal(15,2) | **Pokok + bunga per bulan** |
| jumlah_terbayar | decimal(15,2) | Jumlah yang sudah dibayar |
| denda | decimal(15,2) | Denda jika telat |
| tgl_bayar | datetime | Tanggal pembayaran |
| status_bayar | enum | 'belum', 'lunas', 'telat' |
| timestamps | - | created_at, updated_at |

### 6. **tempo_pinjaman_m** (Jadwal Angsuran Mingguan)
**Catatan:** Tabel masih ada di database untuk kebutuhan masa depan, tapi **TIDAK digunakan** di sistem saat ini.

### 7-9. Tabel Janji Temu & Bukti Foto (Sama seperti sebelumnya)

---

## 🔄 ALUR PEMINJAMAN TERBARU

### **TAHAP 1: PENGAJUAN PINJAMAN**

#### **A. Pengajuan via Transfer**
1. **Nasabah:**
   - Isi form pengajuan:
     * **Nominal** (min Rp 100.000) - **Input TEXT** (display format Rupiah, data numeric di DB)
     * **Durasi** (1-24 bulan) - pilih dari dropdown
   - **Lihat Simulasi Tabel Angsuran** (FITUR BARU!)
     * Sistem otomatis tampilkan tabel simulasi
     * Kolom: Bulan, Tanggal, Pokok, Bunga, Total
     * Update real-time saat ubah nominal/durasi
   - Verifikasi PIN (6 digit)
   - Submit pengajuan
   - **Status:** '1' (Pending)
   - Data tersimpan di `tbl_pengajuan_pinjaman` dengan status = '1'

2. **Admin - Tahap Approval (Status 1 → 3):**
   - Lihat pengajuan di dashboard (status = '1')
   - Review data nasabah
   - Klik tombol **"Setujui"** → Muncul modal konfirmasi
     * Modal menampilkan info bunga dan denda dari master data
     * Tombol: Setujui / Batal
   - Jika klik **Setujui**:
     * Update `status = '3'` (Disetujui) di `tbl_pengajuan_pinjaman`
     * Update `bunga_persen` di pengajuan (dari master data sesuai durasi)
     * **BELUM** membuat pinjaman di `tbl_pinjaman_h`
     * **BELUM** generate jadwal angsuran
   - **Status pengajuan:** '3' (Disetujui, menunggu pencairan)

3. **Admin - Tahap Pencairan (Status 3 → 4):**
   - Lihat pengajuan yang sudah disetujui (status = '3')
   - Klik tombol **"Terlaksana/Cairkan"** → Muncul modal input tanggal cair
     * Field: Tanggal Cair (default = hari ini)
     * Upload bukti transfer/foto serah terima
     * Tombol: Cairkan / Batal
   - Jika klik **Cairkan**:
     * Update `status = '4'` (Terlaksana) di `tbl_pengajuan_pinjaman`
     * **BUAT** record di `tbl_pinjaman_h` dengan status 'telaksana':
       - Ambil bunga dari `master_bunga_pinjaman` berdasarkan durasi
       - Ambil denda dari `master_denda_pinjaman` yang aktif
       - Hitung: `bunga_rp = nominal × (bunga_persen / 100)`
       - **jumlah_pinjam = nominal** (TIDAK dikurangi bunga)
       - `tgl_pinjam` = tanggal cair dari input
     * **GENERATE** jadwal angsuran di `tempo_pinjaman_b`
   - **Status pengajuan:** '4' (Terlaksana)

#### **A2. Jika Ditolak (Status 1 → 2):**
- Admin klik tombol **"Tolak"** → Modal input alasan penolakan
- Update `status = '2'` (Ditolak) di `tbl_pengajuan_pinjaman`
- Update `keterangan` dengan alasan penolakan
- **TIDAK** membuat pinjaman
- **TIDAK** generate jadwal angsuran

#### **B. Pengajuan via Cash (Janji Temu)**
1. **Nasabah:**
   - Isi form pengajuan:
     * Nominal (min Rp 100.000)
     * Durasi (1-24 bulan)
   - Pilih jenis pencairan = **cash**
   - Isi form janji temu:
     * Pilih lokasi kantor (dari master data lokasi)
     * Tanggal janji temu
     * Waktu janji temu
   - Verifikasi PIN
   - Submit pengajuan
   - **Status:** '1' (Pending)
   - Data tersimpan di `tbl_pengajuan_pinjaman` dan `tbl_janji_temu_pinjaman`

2. **Admin:**
   - Lihat pengajuan + janji temu
   - Review data
   - Approve (sistem otomatis ambil bunga & denda dari master data)
   - Sistem buat pinjaman + generate jadwal angsuran
   - Admin & nasabah bertemu sesuai jadwal
   - Admin terima uang tunai
   - Admin upload foto serah terima
   - Update status pinjaman menjadi 'telaksana'
   - **Status pengajuan:** '4' (Terlaksana)

#### **C. Input Pinjaman Manual (Ketemu Langsung)** - FITUR BARU!
1. **Admin:**
   - Klik "Tambah Pinjaman" di halaman Pinjaman Aktif
   - Isi form:
     * Pilih Nasabah
     * Nominal pinjaman
     * Durasi (1-24 bulan)
     * Tanggal pinjam
   - Submit
   - **Sistem Otomatis:**
     * Ambil bunga dari master data
     * Hitung bunga_rp
     * Buat pinjaman langsung dengan status 'telaksana'
     * Generate jadwal angsuran
   - **Tidak ada pengajuan** (langsung jadi pinjaman)

---

### **TAHAP 2: PEMBAYARAN ANGSURAN**

#### **A. Pembayaran via Transfer**
1. **Nasabah:**
   - Pilih pinjaman aktif
   - Pilih angsuran yang akan dibayar
   - **Lihat total tagihan:** Sisa pokok + Sisa bunga + Denda (jika telat)
   - **Isi nominal pembayaran** - BISA SEBAGIAN:
     * Minimal: Rp 1
     * Maksimal: Total tagihan + denda
     * **PENTING:** Jika denda sudah berjalan (H+1 dari jatuh tempo), nominal minimal harus cukup untuk bayar sebagian
   - Isi rekening tujuan
   - Upload bukti transfer (multiple files)
   - Verifikasi PIN (6 digit)
   - Submit pengajuan pembayaran
   - **Status:** '1' (Pending)

2. **Admin:**
   - Lihat pengajuan pembayaran
   - Verifikasi bukti transfer
   - Approve → **Status:** '3' (Disetujui)
   - Konfirmasi pembayaran → **Status:** '4' (Terlaksana)
   - **Sistem otomatis:**
     * Hitung denda HANYA jika belum ada pembayaran sama sekali
     * **Denda dihitung:** 0.3% × **POKOK per bulan** (bukan total tagihan) × hari telat
     * **Denda berhenti:** Segera setelah ada pembayaran (walaupun sebagian)
     * Update `jumlah_terbayar` di angsuran (akumulatif)
     * Simpan `denda` di angsuran (fixed, tidak bertambah lagi)
     * Update `status_bayar`:
       - 'lunas' jika jumlah_terbayar >= (jumlah_tagihan + denda)
       - 'telat' jika masih kurang dan sudah lewat jatuh tempo
       - 'belum' jika belum jatuh tempo
     * Update `tgl_bayar` (tanggal pembayaran pertama kali)
     * Jika semua angsuran lunas → update `pinjaman.lunas = 'lunas'`

#### **B. Pembayaran via Cash (Janji Temu)**
(Sama seperti transfer, bedanya admin upload foto serah terima)

---

## ⚙️ SISTEM & LOGIKA TERBARU

### **1. Sistem Bunga BARU (Tidak Dipotong Di Awal)**
- **Nominal pengajuan** = Jumlah yang diterima nasabah
- **Bunga TIDAK dipotong di awal**
- **jumlah_pinjam** = Nominal (sama dengan yang diajukan)
- **Total tagihan** = Nominal + bunga_rp
- **Bunga dibagi ke setiap angsuran bulanan**

**Contoh:**
```
Nominal Pengajuan: Rp 10.000.000
Durasi: 12 bulan
Bunga (dari master data): 16% untuk 10-12 bulan

Perhitungan:
- Bunga_rp = Rp 10.000.000 × 16% = Rp 1.600.000
- Jumlah diterima (jumlah_pinjam) = Rp 10.000.000 (PENUH!)
- Total yang harus dibayar = Rp 10.000.000 + Rp 1.600.000 = Rp 11.600.000
- Pokok per bulan = Rp 10.000.000 / 12 = Rp 833.333,33
- Bunga per bulan = Rp 1.600.000 / 12 = Rp 133.333,33
- Total per angsuran = Rp 833.333,33 + Rp 133.333,33 = Rp 966.666,67
```

### **2. Master Data Bunga (Otomatis)**
Bunga otomatis dipilih dari master data berdasarkan durasi:

| Durasi | Bunga |
|--------|-------|
| 1-3 bulan | 10% |
| 4-6 bulan | 12% |
| 7-9 bulan | 14% |
| 10-12 bulan | 16% |
| 13-15 bulan | 18% |
| 16-18 bulan | 20% |
| 19-21 bulan | 22% |
| 22-24 bulan | 24% |

**Sistem:**
- Admin tidak perlu input bunga manual
- Saat approve, sistem otomatis ambil dari `master_bunga_pinjaman`
- Jika tidak ada master data untuk durasi tersebut, approve gagal

### **3. Generate Jadwal Angsuran BARU**
- Dibuat otomatis saat admin approve pengajuan
- **Hanya Bulanan** (mingguan tidak digunakan)
- Jatuh tempo: tgl_pinjam + 1 bulan, +2 bulan, dst
- **Perhitungan per angsuran:**
  ```
  Pokok per bulan = jumlah_pinjam / durasi
  Bunga per bulan = bunga_rp / durasi
  Total per angsuran = Pokok per bulan + Bunga per bulan
  ```

**Contoh Generate:**
```
Pinjaman: Rp 10.000.000
Durasi: 12 bulan
Bunga: 16% (Rp 1.600.000)

Angsuran #1: 
- Tanggal: 26/02/2026
- Pokok: Rp 833.333,33
- Bunga: Rp 133.333,33
- Total: Rp 966.666,67

Angsuran #2:
- Tanggal: 26/03/2026
- Pokok: Rp 833.333,33
- Bunga: Rp 133.333,33
- Total: Rp 966.666,67

... (dst sampai bulan ke-12)
```

### **4. Perhitungan Denda BARU (REVISI PENTING!)**
- **Denda:** 0.3% per hari (dari master data)
- **Mulai dihitung:** 1 hari SETELAH tanggal jatuh tempo (H+1)
- **Dihitung dari:** **POKOK ANGSURAN PER BULAN** (bukan total tagihan!)
- **Rumus:** `denda = (nominal_pinjaman / durasi) × (0.3 / 100) × hari_telat`
- **Denda berhenti:** Segera setelah ada pembayaran (walaupun sebagian kecil)
- **Tidak ada maksimal denda**

**Contoh Kasus Lengkap:**
```
PINJAMAN:
- Nominal: Rp 3.000.000
- Durasi: 3 bulan
- Bunga: 10% dari total pinjaman = Rp 300.000
- Yang diterima nasabah: Rp 3.000.000 (PENUH!)
- Total yang harus dibayar: Rp 3.300.000

ANGSURAN PER BULAN:
- Pokok per bulan: Rp 3.000.000 / 3 = Rp 1.000.000
- Bunga per bulan: Rp 300.000 / 3 = Rp 100.000
- Total per bulan: Rp 1.100.000

DENDA KETERLAMBATAN:
- Denda per hari: 0.3% × POKOK per bulan
- Denda per hari: 0.3% × Rp 1.000.000 = Rp 3.000/hari

Skenario 1 - TELAT 1 HARI:
- Jatuh tempo: 26/02/2026
- Mulai denda: 27/02/2026 (H+1)
- Bayar tanggal: 27/02/2026 (telat 1 hari)
- Denda = Rp 3.000 × 1 = Rp 3.000
- Total bayar = Rp 1.100.000 + Rp 3.000 = Rp 1.103.000

Skenario 2 - TELAT 2 HARI:
- Jatuh tempo: 26/02/2026
- Mulai denda: 27/02/2026 (H+1)
- Bayar tanggal: 28/02/2026 (telat 2 hari)
- Denda = Rp 3.000 × 2 = Rp 6.000
- Total bayar = Rp 1.100.000 + Rp 6.000 = Rp 1.106.000

Skenario 3 - BAYAR SEBAGIAN (TELAT 3 HARI):
- Jatuh tempo: 26/02/2026
- Telat 3 hari (27, 28, 01/03)
- Denda sampai hari ke-3 = Rp 3.000 × 3 = Rp 9.000
- Total tagihan = Rp 1.100.000 + Rp 9.000 = Rp 1.109.000
- Bayar: Rp 500.000 (sebagian)
- Denda = Rp 9.000 (FIXED, tidak bertambah lagi!)
- Sisa tagihan = Rp 1.109.000 - Rp 500.000 = Rp 609.000
- Jika bayar lagi nanti, denda tetap Rp 9.000 (tidak bertambah)

Skenario 4 - BAYAR LUNAS (TELAT 5 HARI):
- Jatuh tempo: 26/02/2026
- Bayar tanggal: 03/03/2026 (telat 5 hari: 27, 28, 01, 02, 03)
- Denda = Rp 3.000 × 5 = Rp 15.000
- Total bayar = Rp 1.100.000 + Rp 15.000 = Rp 1.115.000
- Status: LUNAS
```

**PENTING - Kapan Denda Berhenti:**
1. ✅ **Denda berhenti** jika sudah ada pembayaran (walaupun Rp 1)
2. ✅ **Denda tersimpan** di kolom `denda` di tabel tempo
3. ❌ **Denda TIDAK bertambah lagi** setelah ada pembayaran pertama
4. ✅ Nasabah bisa bayar sisa tagihan kapan saja tanpa denda tambahan

### **5. Simulasi Angsuran (Fitur Baru)**
Saat nasabah mengisi form pengajuan:
1. Input nominal dan pilih durasi
2. Sistem otomatis fetch bunga dari master data via AJAX
3. Tampilkan tabel simulasi dengan kolom:
   - **Bulan** (1, 2, 3, ...)
   - **Tanggal** (jatuh tempo)
   - **Pokok** (nominal / durasi)
   - **Bunga** (bunga_rp / durasi)
   - **Total** (pokok + bunga)

**Route:** `POST /nasabah/pinjaman/simulasi-angsuran`

### **6. Status Angsuran**
- **belum:** Belum dibayar dan belum jatuh tempo
- **telat:** Sudah jatuh tempo tapi belum lunas
- **lunas:** Sudah dibayar penuh (termasuk denda jika ada)

### **7. Status Pinjaman**
- **menunggu:** Menunggu approval (tidak digunakan untuk CRUD admin)
- **pencairan:** Sudah disetujui, menunggu pencairan
- **telaksana:** Sudah dicairkan
- **lunas:** Semua angsuran sudah lunas

### **8. Pelunasan Dipercepat**
- Admin bisa lakukan pelunasan dipercepat
- Hitung sisa tagihan pokok + total denda
- Opsional: potongan (diskon)
- Update semua angsuran menjadi lunas
- Update pinjaman menjadi lunas

---

## 📅 CONTOH ALUR DENGAN TANGGAL BARU (26/01/2026)

### **Skenario: Pinjaman Bulanan 12 Bulan, Rp 10.000.000**

1. **26/01/2026** - Nasabah ajukan pinjaman (transfer)
   - Nominal: Rp 10.000.000
   - Durasi: 12 bulan
   - **Lihat simulasi:** Sistem tampilkan tabel 12 angsuran

2. **26/01/2026** - Admin approve
   - Sistem otomatis ambil bunga 16% dari master data
   - Bunga_rp: Rp 1.600.000
   - **jumlah_pinjam: Rp 10.000.000** (PENUH, tidak dikurangi!)
   - Generate 12 angsuran @ Rp 966.666,67
   - Jatuh tempo: 26/02, 26/03, ..., 26/01/2027

3. **26/01/2026** - Admin upload bukti transfer
   - **Nasabah terima: Rp 10.000.000** (sesuai nominal)

4. **26/01/2026** - Status pinjaman: 'telaksana'

5. **26/02/2026** - Angsuran #1 jatuh tempo
   - Tagihan: Rp 966.666,67

6. **28/02/2026** - Nasabah bayar angsuran #1 (telat 1 hari)
   - Denda mulai: 27/02 (1 hari setelah jatuh tempo)
   - Hari telat: 1 hari
   - Denda: Rp 966.666,67 × 0.3% × 1 = Rp 2.900
   - Total bayar: Rp 966.666,67 + Rp 2.900 = Rp 969.566,67

7. **26/03/2026** - Angsuran #2 jatuh tempo

8. **26/03/2026** - Nasabah bayar tepat waktu
   - Bayar: Rp 966.666,67 (tidak ada denda)

9. ... (sampai semua lunas)

10. **26/01/2027** - Angsuran ke-12 lunas → Pinjaman lunas
    - Total terbayar: Rp 10.000.000 (pokok) + Rp 1.600.000 (bunga) + denda (jika ada)

---

## ✅ FITUR YANG SUDAH ADA

### **Nasabah:**
- ✅ Pengajuan pinjaman (transfer & cash/janji temu)
- ✅ **Simulasi tabel angsuran real-time** (BARU!)
- ✅ Verifikasi PIN untuk pengajuan
- ✅ Lihat status pengajuan
- ✅ Lihat pinjaman aktif
- ✅ Lihat jadwal angsuran
- ✅ Pembayaran angsuran (transfer & cash)
- ✅ Dashboard pinjaman dengan statistik

### **Admin:**
- ✅ Dashboard pinjaman dengan stats
- ✅ Approval pengajuan (bunga otomatis dari master data)
- ✅ **CRUD Pinjaman** (untuk yang ketemu langsung) - BARU!
  - Create: Tambah pinjaman manual
  - Read: Lihat detail
  - Update: Edit pinjaman (jika belum ada pembayaran)
  - Delete: Hapus pinjaman (jika belum ada pembayaran)
- ✅ Generate jadwal angsuran otomatis
- ✅ Pencairan via transfer/cash
- ✅ Approval pembayaran angsuran
- ✅ Konfirmasi pembayaran
- ✅ Pelunasan dipercepat
- ✅ **Master Data Bunga & Denda** (CRUD lengkap) - BARU!

---

## 🔄 ALUR STATUS PENGAJUAN PINJAMAN (REVISI BARU!)

### **Status Field di `tbl_pengajuan_pinjaman`:**
- **'1' = Pending** - Pengajuan baru, menunggu review admin
- **'2' = Ditolak** - Pengajuan tidak dilanjutkan
- **'3' = Disetujui** - Pengajuan sudah sesuai, menunggu pencairan
- **'4' = Terlaksana** - Dana sudah dicairkan, pinjaman aktif

### **Alur Lengkap:**
```
NASABAH SUBMIT → Status '1' (Pending)
         ↓
ADMIN REVIEW → Pilih: Setujui atau Tolak
         ↓                    ↓
   Status '3'            Status '2'
  (Disetujui)            (Ditolak)
         ↓                    ↓
ADMIN CAIRKAN              SELESAI
         ↓
   Status '4'
 (Terlaksana)
         ↓
BUAT PINJAMAN + GENERATE TEMPO-TEMPO
```

### **Detail Per Status:**

#### **Status '1' - Pending:**
- Pengajuan baru masuk
- Data tersimpan di `tbl_pengajuan_pinjaman`
- Belum ada pinjaman di `tbl_pinjaman_h`
- Admin bisa lihat di menu "Pengajuan Pinjaman"

#### **Status '2' - Ditolak:**
- Admin klik tombol "Tolak"
- Wajib isi alasan penolakan di `keterangan`
- Tidak membuat pinjaman
- Tidak generate tempo-tempo
- Nasabah bisa lihat status "Ditolak" dengan alasan

#### **Status '3' - Disetujui:**
- Admin klik tombol "Setujui" → Modal konfirmasi
- Update kolom `bunga_persen` dari master data
- **BELUM** membuat pinjaman
- **BELUM** generate tempo-tempo
- Menunggu admin cairkan dana

#### **Status '4' - Terlaksana:**
- Admin klik tombol "Terlaksana/Cairkan" pada pengajuan status '3'
- Modal input:
  * Tanggal cair (default = hari ini)
  * Upload bukti transfer/foto serah terima
- **BUAT** pinjaman di `tbl_pinjaman_h`:
  * `id_pengajuan` = ID pengajuan
  * `jumlah_pinjam` = nominal
  * `tgl_pinjam` = tanggal cair dari input
  * `bunga` dan `bunga_rp` dari master data
  * `denda_persen` dari master data
  * `status` = 'telaksana'
  * `lunas` = 'belum'
- **GENERATE** jadwal angsuran di `tempo_pinjaman_b`
- Pinjaman aktif, nasabah bisa bayar angsuran

### **Tombol di Halaman Admin:**

**Untuk Status '1' (Pending):**
- 🟢 Tombol "Setujui" (hijau) → Status jadi '3'
- 🔴 Tombol "Tolak" (merah) → Status jadi '2'

**Untuk Status '3' (Disetujui):**
- 🟡 Tombol "Terlaksana/Cairkan" (kuning/gold) → Status jadi '4', buat pinjaman

**Untuk Status '2' atau '4':**
- Tidak ada tombol aksi (sudah selesai)

## 📝 CATATAN PENTING

1. **Bunga di awal:** Nasabah menerima jumlah setelah potong bunga
2. **Total tagihan:** Sama dengan nominal pengajuan (bukan nominal + bunga)
3. **Denda:** Dihitung otomatis saat pembayaran jika telat
4. **Status angsuran:** Update otomatis saat pembayaran dikonfirmasi
5. **Pinjaman lunas:** Update otomatis jika semua angsuran lunas

