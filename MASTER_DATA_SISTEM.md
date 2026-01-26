# 📊 DOKUMENTASI SISTEM MASTER DATA KOPERASI MAJAKARA

**Tanggal Update: 26 Januari 2026**

---

## 📋 OVERVIEW

Sistem Master Data adalah fitur untuk mengelola semua data referensi yang digunakan oleh modul-modul utama (Pinjaman, Tabungan, Deposito, dan Gadai) di Koperasi Majakara.

### **Akses Menu:**
- **URL:** `/admin/master-data`
- **Posisi:** Sidebar Admin (di bawah menu "Pengajuan")
- **Icon:** Database dengan 3 layer

---

## 🎯 MODUL MASTER DATA

### **1. MASTER DATA PINJAMAN**

#### A. Master Bunga Pinjaman
**Tabel:** `master_bunga_pinjaman`

| Field | Tipe | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| durasi_min | integer | Durasi minimum (bulan) |
| durasi_max | integer | Durasi maksimum (bulan) |
| bunga_persen | decimal(5,2) | Persentase bunga |
| status_aktif | boolean | Status aktif/nonaktif |
| keterangan | text | Keterangan |

**Data Default (Seeded):**
- 1-3 bulan: 10%
- 4-6 bulan: 12%
- 7-9 bulan: 14%
- 10-12 bulan: 16%
- 13-15 bulan: 18%
- 16-18 bulan: 20%
- 19-21 bulan: 22%
- 22-24 bulan: 24%

**Fitur:**
- ✅ CRUD lengkap
- ✅ Toggle status aktif/nonaktif
- ✅ Validasi range durasi (durasi_max >= durasi_min)
- ✅ Auto-select bunga berdasarkan durasi saat pengajuan

**Route:**
- Index: `GET /admin/master-data/bunga-pinjaman`
- Create: `GET /admin/master-data/bunga-pinjaman/create`
- Store: `POST /admin/master-data/bunga-pinjaman`
- Edit: `GET /admin/master-data/bunga-pinjaman/{id}/edit`
- Update: `PUT /admin/master-data/bunga-pinjaman/{id}`
- Delete: `DELETE /admin/master-data/bunga-pinjaman/{id}`
- Toggle Status: `POST /admin/master-data/bunga-pinjaman/{id}/toggle-status`

---

#### B. Master Denda Pinjaman
**Tabel:** `master_denda_pinjaman`

| Field | Tipe | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| denda_persen | decimal(5,2) | Persentase denda per hari |
| status_aktif | boolean | Status aktif/nonaktif |
| keterangan | text | Keterangan |

**Data Default:**
- Denda: 0.3% per hari

**Aturan Denda:**
1. Denda mulai dihitung 1 hari setelah tanggal jatuh tempo
2. Denda berhenti jika sudah ada pembayaran (walaupun sedikit)
3. Rumus: `jumlah_tagihan × (denda_persen / 100) × hari_telat`

**Fitur:**
- ✅ CRUD lengkap
- ✅ Toggle status aktif/nonaktif
- ✅ Hanya 1 data yang aktif pada satu waktu

**Route:**
- Index: `GET /admin/master-data/denda-pinjaman`
- Create: `GET /admin/master-data/denda-pinjaman/create`
- Store: `POST /admin/master-data/denda-pinjaman`
- Edit: `GET /admin/master-data/denda-pinjaman/{id}/edit`
- Update: `PUT /admin/master-data/denda-pinjaman/{id}`
- Delete: `DELETE /admin/master-data/denda-pinjaman/{id}`
- Toggle Status: `POST /admin/master-data/denda-pinjaman/{id}/toggle-status`

---

### **2. MASTER DATA TABUNGAN**

#### Suku Bunga Tabungan
**Tabel:** `suku_bunga`

| Field | Tipe | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| jenis_bunga | string | Nama jenis bunga |
| opsi_val | decimal(5,4) | Nilai persentase |

**Fitur:**
- ✅ CRUD lengkap
- ✅ Digunakan untuk perhitungan bunga tabungan nasabah

**Route:**
- Index: `GET /admin/master-data/suku-bunga-tabungan`
- Create: `GET /admin/master-data/suku-bunga-tabungan/create`
- Store: `POST /admin/master-data/suku-bunga-tabungan`
- Edit: `GET /admin/master-data/suku-bunga-tabungan/{id}/edit`
- Update: `PUT /admin/master-data/suku-bunga-tabungan/{id}`
- Delete: `DELETE /admin/master-data/suku-bunga-tabungan/{id}`

---

### **3. MASTER DATA DEPOSITO**

#### A. Master Tenor Deposito
**Tabel:** `jns_tenor_deposito`

| Field | Tipe | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| tenor_hari | integer | Tenor dalam hari |
| tenor_bulan | integer | Tenor dalam bulan |
| aktif | boolean | Status aktif/nonaktif |

**Fitur:**
- ✅ CRUD lengkap
- ✅ Toggle status aktif/nonaktif
- ✅ Relasi ke suku bunga deposito
- ✅ Validasi: tidak bisa dihapus jika masih ada suku bunga terkait

**Route:**
- Index: `GET /admin/master-data/tenor-deposito`
- Create: `GET /admin/master-data/tenor-deposito/create`
- Store: `POST /admin/master-data/tenor-deposito`
- Edit: `GET /admin/master-data/tenor-deposito/{id}/edit`
- Update: `PUT /admin/master-data/tenor-deposito/{id}`
- Delete: `DELETE /admin/master-data/tenor-deposito/{id}`
- Toggle Status: `POST /admin/master-data/tenor-deposito/{id}/toggle-status`

---

#### B. Suku Bunga Deposito
**Tabel:** `suku_bunga_deposito`

| Field | Tipe | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| tenor_id | foreignId | FK ke jns_tenor_deposito |
| min_nominal | decimal(15,2) | Nominal minimum |
| max_nominal | decimal(15,2) | Nominal maksimum |
| bunga | decimal(5,4) | Persentase bunga |
| status | boolean | Status aktif/nonaktif |

**Fitur:**
- ✅ CRUD lengkap
- ✅ Bunga berdasarkan tenor dan range nominal
- ✅ Toggle status aktif/nonaktif
- ✅ Dropdown tenor yang aktif saja

**Route:**
- Index: `GET /admin/master-data/suku-bunga-deposito`
- Create: `GET /admin/master-data/suku-bunga-deposito/create`
- Store: `POST /admin/master-data/suku-bunga-deposito`
- Edit: `GET /admin/master-data/suku-bunga-deposito/{id}/edit`
- Update: `PUT /admin/master-data/suku-bunga-deposito/{id}`
- Delete: `DELETE /admin/master-data/suku-bunga-deposito/{id}`
- Toggle Status: `POST /admin/master-data/suku-bunga-deposito/{id}/toggle-status`

---

### **4. MASTER DATA GADAI**

#### Master Barang Gadai
**Tabel:** `m_barang_gadai`

| Field | Tipe | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| nama_barang | string | Nama jenis barang |
| deskripsi | text | Deskripsi barang |

**Fitur:**
- ✅ CRUD lengkap
- ✅ Validasi: tidak bisa dihapus jika masih digunakan di item gadai
- ✅ Deskripsi lengkap untuk setiap jenis barang

**Route:**
- Index: `GET /admin/master-data/barang-gadai`
- Create: `GET /admin/master-data/barang-gadai/create`
- Store: `POST /admin/master-data/barang-gadai`
- Edit: `GET /admin/master-data/barang-gadai/{id}/edit`
- Update: `PUT /admin/master-data/barang-gadai/{id}`
- Delete: `DELETE /admin/master-data/barang-gadai/{id}`

---

## 🎨 DESIGN PATTERN

### **Color Scheme (Tema Koperasi):**
- **Primary:** `#674c1d` (Coklat Gelap)
- **Primary Light:** `#8b6f2f` (Coklat Terang)
- **Primary Dark:** `#4a3514` (Coklat Sangat Gelap)
- **Accent:** `#d4af37` (Emas)

### **Card Stats:**
- Background: `bg-white`
- Border: `border border-gray-100`
- Shadow: `shadow-md hover:shadow-lg`
- Icon Container: `w-14 h-14 bg-gradient-to-br from-[#674c1d]/20 to-[#674c1d]/10 rounded-xl`

### **Buttons:**
- Primary: `bg-gradient-to-r from-[#674c1d] to-[#8b6f2f]`
- Hover: `hover:from-[#4a3514] hover:to-[#674c1d]`

### **Form Inputs:**
- Focus Border: `focus:border-[#674c1d]`
- Focus Ring: `focus:ring-2 focus:ring-[#674c1d]/20`

---

## 🔄 INTEGRASI DENGAN MODUL LAIN

### **Pinjaman:**
- Bunga otomatis dipilih berdasarkan durasi saat approve pengajuan
- Denda otomatis diambil dari master denda aktif
- Simulasi angsuran menggunakan master bunga

### **Tabungan:**
- Suku bunga digunakan untuk perhitungan bunga tabungan

### **Deposito:**
- Tenor dan suku bunga digunakan saat pengajuan deposito
- Bunga dihitung berdasarkan tenor dan nominal

### **Gadai:**
- Jenis barang digunakan saat pengajuan gadai

---

## 📝 CARA PENGGUNAAN

### **Setup Awal:**
```bash
# 1. Jalankan migration
php artisan migrate

# 2. Seed master data bunga dan denda pinjaman
php artisan db:seed --class=MasterBungaDendaPinjamanSeeder
```

### **Akses Menu:**
1. Login sebagai Admin
2. Klik menu "Master Data" di sidebar
3. Pilih modul yang ingin dikelola
4. Gunakan tombol "+ Tambah Data" untuk menambahkan data baru

### **Best Practices:**
1. **Jangan hapus data yang sedang digunakan** - sistem akan memberi warning
2. **Gunakan toggle status** untuk menonaktifkan data sementara
3. **Update bunga/denda** melalui master data, bukan hardcode
4. **Backup data** sebelum melakukan perubahan besar

---

## ✅ CHECKLIST FITUR

### **CRUD Lengkap:**
- ✅ Create (Tambah Data)
- ✅ Read (Lihat Daftar & Detail)
- ✅ Update (Edit Data)
- ✅ Delete (Hapus Data)
- ✅ Toggle Status (untuk modul tertentu)

### **Validasi:**
- ✅ Required fields
- ✅ Range validation (min/max)
- ✅ Foreign key validation
- ✅ Dependency check sebelum delete

### **UI/UX:**
- ✅ Design konsisten dengan tema koperasi
- ✅ Responsive untuk mobile & desktop
- ✅ Alert messages (success/error)
- ✅ Breadcrumb navigation
- ✅ Pagination untuk data banyak
- ✅ Empty state yang informatif

---

## 🔧 TROUBLESHOOTING

### **Problem: Bunga tidak muncul saat pengajuan**
**Solusi:** Pastikan ada master bunga yang aktif untuk range durasi tersebut

### **Problem: Tidak bisa hapus data**
**Solusi:** Cek apakah data masih digunakan di transaksi/relasi lain

### **Problem: Menu Master Data tidak muncul**
**Solusi:** Clear cache: `php artisan route:clear && php artisan view:clear`

---

## 📚 FILE STRUKTUR

```
app/
├── Http/Controllers/Admin/
│   └── MasterDataController.php (Main controller)
├── Models/
│   ├── MasterBungaPinjaman.php
│   ├── MasterDendaPinjaman.php
│   ├── SukuBunga.php
│   ├── JnsTenorDeposito.php
│   ├── SukuBungaDeposito.php
│   └── MBarangGadai.php

database/
├── migrations/
│   ├── 2026_01_26_000001_create_master_bunga_pinjaman_table.php
│   └── 2026_01_26_000002_create_master_denda_pinjaman_table.php
└── seeders/
    └── MasterBungaDendaPinjamanSeeder.php

resources/views/admin/master-data/
├── index.blade.php (Dashboard)
├── bunga-pinjaman/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
├── denda-pinjaman/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
├── suku-bunga-tabungan/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
├── tenor-deposito/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
├── suku-bunga-deposito/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
└── barang-gadai/
    ├── index.blade.php
    ├── create.blade.php
    └── edit.blade.php

routes/
└── web.php (Updated dengan master-data routes)
```

---

## 🚀 UPDATE LOG

### **v1.0 - 26 Januari 2026**
- ✅ Implementasi sistem master data lengkap
- ✅ CRUD untuk semua modul (Pinjaman, Tabungan, Deposito, Gadai)
- ✅ Integrasi dengan sistem pengajuan
- ✅ Design konsisten dengan tema koperasi
- ✅ Seeder data awal
- ✅ Dokumentasi lengkap

---

## 📞 SUPPORT

Jika ada pertanyaan atau masalah terkait Master Data, hubungi tim developer.
