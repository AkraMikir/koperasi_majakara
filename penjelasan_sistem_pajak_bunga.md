# Dokumentasi Sistem Pembayaran Pajak Bunga (PPh)
### Koperasi Majakara — Implementasi Juni 2022

---

## Gambaran Umum

Sistem ini menambahkan fitur tracking dan pencatatan **PPh (Pajak Penghasilan)** atas pendapatan bunga koperasi kepada negara. Tiga jenis pajak yang dikelola:

| Modul | Tarif | Jenis |
|-------|-------|-------|
| Bunga Pinjaman | **15%** | PPh Final atas bunga pinjaman |
| Biaya Gadai | **15%** | PPh Final atas pendapatan jasa gadai |
| Bunga Deposito | **20%** | PPh Final atas bunga simpanan (sudah dipotong dari nasabah) |

---

## Backend

### 1. Migration
**File:** `database/migrations/2026_06_22_050700_create_pajak_bunga_payments_table.php`

Membuat tabel `pajak_bunga_payments` dengan struktur:

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | bigint PK | Auto increment |
| `jenis_pajak` | enum | `pph_pinjaman` / `pph_gadai` / `pph_deposito` |
| `periode_bulan` | tinyint | Bulan 1–12 |
| `periode_tahun` | smallint | Tahun (mis. 2026) |
| `jumlah_kotor` | decimal(15,2) | Basis perhitungan (realisasi bulan tsb) |
| `tarif_persen` | decimal(5,2) | Tarif pajak: 15.00 atau 20.00 |
| `jumlah_pajak` | decimal(15,2) | Hasil: kotor × tarif% |
| `jumlah_bersih` | decimal(15,2) | Hasil: kotor − pajak |
| `tanggal_bayar` | date | Kapan pajak disetor ke negara |
| `keterangan` | text | Catatan tambahan |
| `bukti_bayar` | varchar | Path file foto/PDF bukti setor |
| `status` | enum | `belum_bayar` / `sudah_bayar` |
| `dibuat_oleh` | FK → users | Admin yang mencatat |
| `timestamps` | — | created_at / updated_at |

---

### 2. Model
**File:** `app/Models/PajakBungaPayment.php`

Accessor yang tersedia:

| Accessor | Output Contoh |
|----------|---------------|
| `jenis_label` | "PPh Pinjaman", "PPh Gadai", "PPh Deposito" |
| `status_label` | "Belum Dibayar", "Sudah Dibayar" |
| `periode_label` | "Juni 2026" (format translasi) |

Relasi: `dibuatOleh()` → `belongsTo(User::class)`

---

### 3. Controller
**File:** `app/Http/Controllers/Admin/PajakBungaController.php`

| Method | Route | Fungsi |
|--------|-------|--------|
| `index()` | GET `/admin/bunga/pajak` | Daftar semua record + KPI summary |
| `create()` | GET `/admin/bunga/pajak/create` | Tampilkan form tambah |
| `store()` | POST `/admin/bunga/pajak` | Simpan record baru + upload file |
| `edit($id)` | GET `/admin/bunga/pajak/{id}/edit` | Form edit record |
| `update($id)` | PUT `/admin/bunga/pajak/{id}` | Update record + ganti file lama |
| `destroy($id)` | DELETE `/admin/bunga/pajak/{id}` | Hapus record + hapus file |
| `hitung()` | GET `/admin/bunga/pajak/hitung` | **AJAX** — hitung pajak dari data aktual |

#### Logika Hitung Otomatis (`hitung()`)

```
PPh Pinjaman → basis = realisasi angsuran lunas bulan tsb
               (TempoPinjamanB + TempoPinjamanM, status_bayar = 'lunas')
               tarif 15%

PPh Gadai    → basis = total payment GadaiPaymentLog bulan tsb
               tebus: nominal − nominal_deal | lain: full nominal
               tarif 15%

PPh Deposito → basis = SUM(bunga_kotor) dari deposito_persiapan_cair
               status = 'selesai', bulan tsb
               tarif 20%
```

---

### 4. Routes
**File:** `routes/web.php` — ditambahkan di dalam group `prefix('bunga')`

```
GET    /admin/bunga/pajak            → index
GET    /admin/bunga/pajak/create     → create
POST   /admin/bunga/pajak            → store
GET    /admin/bunga/pajak/hitung     → hitung (AJAX endpoint)
GET    /admin/bunga/pajak/{id}/edit  → edit
PUT    /admin/bunga/pajak/{id}       → update
DELETE /admin/bunga/pajak/{id}       → destroy
```

---

## Frontend

### 1. Modifikasi Banner — Halaman Bunga Pinjaman
**File:** `resources/views/admin/bunga/pinjaman.blade.php`

Ditambahkan di kolom **Pendapatan Bunga Bulan Ini** pada summary banner:

#### Badge Breakdown PPh Pinjaman
```
+-------------------------+-------------------------+
|  85% Bersih             |  15% PPh                |
|  Rp X.XXX.XXX           |  Rp X.XXX.XXX           |
|  (bg putih transparan)  |  (bg amber/kuning)      |
+-------------------------+-------------------------+
```
- **85% Bersih** = `bungaBulanIni × 0.85` — keuntungan koperasi setelah pajak
- **15% PPh** = `bungaBulanIni × 0.15` — kewajiban disetor ke negara
- Ditambahkan link **"Kelola Pembayaran Pajak"** di kolom ketiga

---

### 2. Modifikasi Banner — Halaman Biaya Gadai
**File:** `resources/views/admin/bunga/gadai.blade.php`

Kolom tengah diubah menjadi **Realisasi Bulan Ini (Gabungan)** = tebus + perpanjang:

#### Badge Breakdown PPh Gadai
```
+-------------------------+-------------------------+
|  85% Bersih             |  15% PPh                |
|  Rp X.XXX.XXX           |  Rp X.XXX.XXX           |
|  (bg putih transparan)  |  (bg orange)            |
+-------------------------+-------------------------+
```
- **85% Bersih** = `(realisasiBungaMurni + realisasiAdminInap) × 0.85`
- **15% PPh** = `(realisasiBungaMurni + realisasiAdminInap) × 0.15`

---

### 3. Modifikasi Banner — Halaman Bunga Deposito
**File:** `resources/views/admin/bunga/deposito.blade.php`

Kolom pertama diubah menjadi **Total Kewajiban Deposito** (kotor = bersih + pajak):

#### Badge Breakdown PPh Deposito
```
+-------------------------+-------------------------+
|  80% ke Nasabah         |  20% PPh                |
|  Rp X.XXX.XXX           |  Rp X.XXX.XXX           |
|  (bg putih transparan)  |  (bg rose/merah)        |
+-------------------------+-------------------------+
```
- **80% ke Nasabah** = `totalBersih` — bunga yang diterima nasabah
- **20% PPh** = `totalPajak` — beban yang harus disetor koperasi ke negara

---

### 4. Halaman Baru — Pembayaran Pajak (Index)
**File:** `resources/views/admin/bunga/pajak/index.blade.php`
**URL:** `/admin/bunga/pajak`

#### Summary Banner (coklat gradient)
Menampilkan kewajiban PPh bulan berjalan dalam 3 kolom:
- **PPh Pinjaman (15%)** — total dari record + referensi realisasi aktual
- **PPh Gadai (15%)** — idem
- **PPh Deposito (20%)** — idem

#### KPI Cards (4 card)

| Card | Isi | Warna |
|------|-----|-------|
| **Total PPh Bulan Ini** | Sum semua jenis pajak bulan ini dari record yang dicatat | Coklat (brand) |
| **Sudah Dibayar** | Total jumlah_pajak status `sudah_bayar` semua periode | Hijau |
| **Belum Dibayar** | Total jumlah_pajak status `belum_bayar` semua periode | Merah |
| **Total Catatan** | COUNT semua record dari semua periode | Biru |

#### Tabel CRUD

| Kolom | Keterangan |
|-------|------------|
| Periode | Nama bulan + tahun (mis. "Juni 2026") |
| Jenis | Badge berwarna: hijau=pinjaman, amber=gadai, merah=deposito |
| Kotor (Basis) | Jumlah realisasi yang jadi basis hitung |
| Tarif | Badge % (15% atau 20%) |
| Jumlah Pajak | Angka merah — nilai yang harus disetor |
| Bersih | Sisa setelah pajak |
| Status | Badge Lunas (hijau) atau Belum (merah) |
| Bukti | Icon mata — buka file bukti di tab baru |
| Aksi | Icon edit (amber) + icon hapus (merah) |

---

### 5. Halaman Baru — Catat Pembayaran (Create)
**File:** `resources/views/admin/bunga/pajak/create.blade.php`

#### Form Fields

| Field | Tipe | Keterangan |
|-------|------|------------|
| Jenis Pajak | Dropdown | PPh Pinjaman / PPh Gadai / PPh Deposito |
| Bulan & Tahun | Dropdown x2 | Pilih periode |
| Tombol "Hitung Otomatis" | Button AJAX | Isi otomatis nilai dari data realisasi DB |
| Jumlah Kotor | Number | Editable, basis perhitungan |
| Tarif PPh % | Number readonly | Auto-isi sesuai jenis (15 atau 20) |
| Jumlah Pajak | Number readonly | Auto-hitung: kotor × tarif% |
| Jumlah Bersih | Number readonly | Auto-hitung: kotor − pajak |
| Tanggal Bayar | Date | Kapan disetor (opsional) |
| Status | Dropdown | Belum / Sudah Dibayar |
| Keterangan | Textarea | Catatan bebas |
| Bukti Bayar | File upload | JPG / PNG / PDF, max 5MB |

> [!NOTE]
> Saat klik **"Hitung Otomatis"**, sistem fetch `GET /admin/bunga/pajak/hitung?jenis=...&bulan=...&tahun=...` dan auto-isi seluruh field nilai tanpa reload halaman.

---

### 6. Halaman Baru — Edit Pembayaran
**File:** `resources/views/admin/bunga/pajak/edit.blade.php`

Sama dengan Create, dengan tambahan:
- Pre-fill semua field dari data record existing
- Tampil preview file bukti lama dengan link "Lihat file saat ini"
- Upload file bersifat opsional (kosong = pertahankan file lama)
- Tombol **"Hitung Ulang"** untuk refresh nilai dari data aktual bulan terpilih

---

### 7. Sidebar
**File:** `resources/views/components/admin/sidebar.blade.php`

Ditambahkan menu **"Pembayaran Pajak"** di bawah "Biaya Gadai":

```
Bunga
  ├─ Ringkasan Bunga
  ├─ Bunga Pinjaman
  ├─ Bunga Deposito
  ├─ Biaya Gadai
  └─ Pembayaran Pajak   (BARU)
```

- Active state: highlight coklat `bg-[#674c1d]/10` saat di halaman pajak
- Auto-expand grup Bunga: dicakup oleh `str_starts_with($currentRoute, 'admin.bunga')`

---

## Alur Penggunaan

```
1. Admin buka halaman Bunga Pinjaman / Gadai / Deposito
   └─ Lihat breakdown 85%/15% atau 80%/20% di banner
   └─ Klik link "Kelola Pembayaran Pajak"

2. Di halaman Pembayaran Pajak (index)
   └─ Lihat KPI: Total PPh bulan ini, Sudah Bayar, Belum Bayar
   └─ Klik "+ Catat Pembayaran"

3. Di form Create
   └─ Pilih Jenis Pajak + Periode
   └─ Klik "Hitung Otomatis" — nilai terisi dari realisasi DB
   └─ Isi Tanggal Bayar + upload Bukti
   └─ Set Status "Sudah Dibayar" jika sudah disetor
   └─ Simpan

4. Record muncul di tabel index dengan status dan bukti
5. Edit kapan saja untuk update status atau ganti bukti
```

---

## File yang Dibuat / Dimodifikasi

### Dibuat (NEW)

| File | Keterangan |
|------|------------|
| `database/migrations/2026_06_22_050700_create_pajak_bunga_payments_table.php` | Migration tabel |
| `app/Models/PajakBungaPayment.php` | Model Eloquent |
| `app/Http/Controllers/Admin/PajakBungaController.php` | Controller CRUD + AJAX |
| `resources/views/admin/bunga/pajak/index.blade.php` | Halaman daftar |
| `resources/views/admin/bunga/pajak/create.blade.php` | Form tambah |
| `resources/views/admin/bunga/pajak/edit.blade.php` | Form edit |

### Dimodifikasi (MODIFY)

| File | Perubahan |
|------|-----------|
| `routes/web.php` | Tambah 7 routes pajak |
| `resources/views/admin/bunga/pinjaman.blade.php` | Badge 85%/15% di banner |
| `resources/views/admin/bunga/gadai.blade.php` | Badge 85%/15% di banner |
| `resources/views/admin/bunga/deposito.blade.php` | Badge 80%/20% di banner |
| `resources/views/components/admin/sidebar.blade.php` | Item menu Pembayaran Pajak |
| `penjelasan_sistem_bunga.md` | Update proyeksi BUG-11 |
