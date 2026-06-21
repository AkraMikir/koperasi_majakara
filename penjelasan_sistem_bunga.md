# 📊 Penjelasan Sistem Dashboard Bunga — Koperasi Majakara

## Gambaran Besar Arsitektur

Dashboard bunga adalah fitur **read-only analitik** di panel admin. Tidak ada CRUD di sini — hanya agregasi data dari 3 modul transaksi utama yang ditampilkan sebagai KPI card dan grafik tren.

```
Routes → BungaController → (PinjamanH, DepositoH, GadaiActive) → Views (Blade)
```

**File Utama:**
- Controller: [BungaController.php](file:///d:/project/koperasi_majakara/app/Http/Controllers/Admin/BungaController.php)
- Views: [resources/views/admin/bunga/](file:///d:/project/koperasi_majakara/resources/views/admin/bunga/)
  - [index.blade.php](file:///d:/project/koperasi_majakara/resources/views/admin/bunga/index.blade.php) — Dashboard Utama
  - [pinjaman.blade.php](file:///d:/project/koperasi_majakara/resources/views/admin/bunga/pinjaman.blade.php)
  - [deposito.blade.php](file:///d:/project/koperasi_majakara/resources/views/admin/bunga/deposito.blade.php)
  - [gadai.blade.php](file:///d:/project/koperasi_majakara/resources/views/admin/bunga/gadai.blade.php)

---

## 4 Halaman Dashboard

### 1. Dashboard Utama `/admin/bunga` — Ringkasan Semua Bunga

Controller method: [`index()`](file:///d:/project/koperasi_majakara/app/Http/Controllers/Admin/BungaController.php#L18-L196)

Halaman ini menampilkan **2 layer data**: proyeksi portofolio + realisasi kas bulan ini.

#### Layer 1 — Proyeksi Portofolio (semua transaksi aktif)

| KPI Card | Sumber Tabel | Logika |
|----------|-------------|--------|
| **Total Bunga Pinjaman** | `tbl_pinjaman_h` | `SUM(bunga_rp)` dari pinjaman status `3` atau `4` yang belum lunas |
| **Total Biaya Gadai** | `tbl_gadai_active` | `SUM(biaya_jasa + biaya_inap)` status `active/extended/expired_grace/expired_final` |
| **Total Bunga Deposito** | `tbl_deposito_h` + `deposito_persiapan_cair` | Bunga Kotor − Pajak 20% (lihat detail bawah) |
| **Net Margin** | Kalkulasi | `(Pinjaman + Gadai) − Deposito` |

#### Layer 2 — Realisasi Kas Bulan Ini (kas yang benar-benar bergerak)

| KPI | Sumber | Logika |
|-----|--------|--------|
| **Realisasi Pinjaman** | `tbl_tempo_pinjaman_b` + `tbl_tempo_pinjaman_m` | Angsuran `status_bayar = 'lunas'` bulan ini → `bunga_rp / lama_pinjam` per cicilan |
| **Realisasi Gadai** | `GadaiPaymentLog` | Jika `tebus`: selisih `nominal − nominal_deal`. Jika lain: full `nominal` |
| **Realisasi Deposito** | `deposito_persiapan_cair` | `SUM(bunga_bersih)` yang `status = 'selesai'` bulan ini |
| **Net Realisasi** | Kalkulasi | `(Realisasi Pinjaman + Gadai) − Realisasi Deposito` |

---

### 2. Dashboard Bunga Pinjaman `/admin/bunga/pinjaman` — Bunga Masuk

Controller method: [`pinjaman()`](file:///d:/project/koperasi_majakara/app/Http/Controllers/Admin/BungaController.php#L201-L285)
Model utama: [PinjamanH.php](file:///d:/project/koperasi_majakara/app/Models/PinjamanH.php) → tabel `tbl_pinjaman_h`

**Jenis bunga: MASUK / KEUNTUNGAN KOPERASI**

#### Rumus Bunga Pinjaman:
```
bunga_rp = jumlah_pinjam × (bunga_persen / 100)
Total Kewajiban Nasabah = jumlah_pinjam + bunga_rp
```

> Bunga dihitung flat (tidak compounding). `bunga_rp` disimpan langsung di kolom saat pinjaman disetujui.

#### KPI Cards:

| Card | Logika |
|------|--------|
| **Total Bunga Masuk** | `SUM(bunga_rp)` — pinjaman status `3` & `4`, belum lunas |
| **Pinjaman Aktif** | `COUNT(*)` pinjaman dimana `lunas != 'lunas'` |
| **Rata-rata Bunga/Pinjaman** | `Total Bunga / Jumlah Aktif` |
| **Proyeksi Bulan Depan** | `Realisasi Bulan Ini × 1.05` (asumsi tumbuh 5%). Fallback: `Total Bunga × 0.05` jika belum ada realisasi. *(BUG-11 FIX: basis realisasi lebih akurat dari total portofolio)* |

#### Mekanisme Angsuran:
- **Bulanan** → tabel `tbl_tempo_pinjaman_b`, relasi `TempoPinjamanB`
- **Mingguan** → tabel `tbl_tempo_pinjaman_m`, relasi `TempoPinjamanM`
- Bunga per cicilan = `bunga_rp / lama_pinjam` (bulanan) atau `bunga_rp / (lama_pinjam × 4)` (mingguan)

---

### 3. Dashboard Bunga Deposito `/admin/bunga/deposito` — Bunga Keluar

Controller method: [`deposito()`](file:///d:/project/koperasi_majakara/app/Http/Controllers/Admin/BungaController.php#L287-L390)
Model utama: [DepositoH.php](file:///d:/project/koperasi_majakara/app/Models/DepositoH.php) → tabel `tbl_deposito_h`
Model bantu: [DepositoPersiapanCair.php](file:///d:/project/koperasi_majakara/app/Models/DepositoPersiapanCair.php) → tabel `deposito_persiapan_cair`

**Jenis bunga: KELUAR / BEBAN KOPERASI**

#### Rumus Bunga Deposito:
```
Bunga Kotor = nominal_awal × (bunga_tahunan / 100) × (tenor_hari / pembagi)
              dimana pembagi = 365 (normal) atau 366 (tahun kabisat)

Pajak       = Bunga Kotor × 20%   ← PPh Final atas bunga simpanan

Bunga Bersih = Bunga Kotor − Pajak

Total Cair  = nominal_awal + Bunga Bersih
```

#### Dua Sumber Data (dual-path):
```
Deposito Aktif
    ├── Ada di `deposito_persiapan_cair`?
    │       YES → ambil bunga_kotor, pajak, bunga_bersih dari sana (data akurat)
    │             → ditandai sumber_bunga = 'siap_cair'
    └── TIDAK → hitung in-memory (fallback kalkulasi real-time)
                → ditandai sumber_bunga = 'estimasi'
```

#### KPI Cards:

| Card | Logika |
|------|--------|
| **Total Bunga Keluar (Bersih)** | `SUM(bunga_kotor) − SUM(pajak)` dari deposito aktif |
| **Deposito Aktif** | `COUNT(*)` dimana `status = 'aktif'` |
| **Pajak Deposito (20%)** | `SUM(bunga_kotor) × 20%` |
| **Jatuh Tempo Bulan Ini** | COUNT deposito aktif dimana bulan `tgl_jatuh_tempo` = bulan sekarang |

---

### 4. Dashboard Bunga Gadai `/admin/bunga/gadai` — Biaya Jasa & Inap

Controller method: [`gadai()`](file:///d:/project/koperasi_majakara/app/Http/Controllers/Admin/BungaController.php#L395-L480)
Model utama: [GadaiActive.php](file:///d:/project/koperasi_majakara/app/Models/GadaiActive.php) → tabel `tbl_gadai_active`

**Jenis bunga: MASUK / KEUNTUNGAN KOPERASI**

#### Terminologi Gadai (bukan "bunga" tapi substansi sama):
```
Biaya Jasa  = nominal_deal × (rate_jasa / 100)
Biaya Inap  = (disimpan di kolom biaya_inap, atau rate_inap_persen × nominal_deal)

Total Tebus = nominal_deal + biaya_jasa + biaya_inap
```

#### KPI Cards:

| Card | Logika |
|------|--------|
| **Total Pendapatan Proyeksi** | `SUM(biaya_jasa + biaya_inap)` dari gadai aktif |
| **Biaya Jasa** | `SUM(biaya_jasa)` |
| **Biaya Inap** | `SUM(biaya_inap)` |
| **Gadai Aktif** | `COUNT(*)` status `active/extended/expired_grace/expired_final` |
| **Pendapatan Bulan Ini (Realisasi)** | Dari `GadaiPaymentLog`: tebus → `nominal − nominal_deal`; perpanjang → full `nominal` |

---

## Diagram Aliran Dana Koperasi

```
                    KOPERASI MAJAKARA
                    
    MASUK (Pendapatan Bunga)        KELUAR (Beban Bunga)
    ─────────────────────────       ─────────────────────
    
    Pinjaman                        Deposito
    bunga_rp = pokok × rate%        Bunga Bersih = pokok × rate_thn%
    Dibayar via angsuran            × (tenor/365) × 80%
    bulanan/mingguan                Dibayar saat jatuh tempo
    
    Gadai
    biaya_jasa + biaya_inap
    = nominal_deal × rate%
    Dibayar saat nasabah tebus
    
                    ↓ NET MARGIN ↓
    (Bunga Pinjaman + Biaya Gadai) - Bunga Deposito
```

---

## Status Kode Penting

| Modul | Status | Arti |
|-------|--------|------|
| Pinjaman (pengajuan) | `3` | Disetujui |
| Pinjaman (pengajuan) | `4` | Dicairkan |
| Pinjaman | `lunas` | Semua angsuran selesai |
| Deposito | `aktif` | Berjalan |
| Deposito | `selesai` | Sudah cair |
| Gadai | `active` | Berjalan normal |
| Gadai | `extended` | Diperpanjang |
| Gadai | `expired_grace` | Lewat jatuh tempo, masa tenggang |
| Gadai | `expired_final` | Lewat tenggang, siap lelang |

---

## Grafik Tren (Semua Dashboard)

Semua dashboard mengambil **6 bulan terakhir** menggunakan:
```php
$sixMonthsAgo = Carbon::now()->subMonths(5)->startOfMonth();
```
Data digroup per bulan (`Y-m`), lalu di-loop untuk mengisi array `labels[]` dan `data[]` yang dikirim ke Chart.js di view Blade.

---

## Catatan Teknis Penting

> [!NOTE]
> **Dual-path Deposito**: Jika `deposito_persiapan_cair` belum diisi (deposito masih aktif dan belum diproses admin), bunga dihitung **in-memory secara real-time** setiap kali halaman dibuka. Ini adalah estimasi akrual, bukan nilai final.

> [!NOTE]
> **Realisasi vs Proyeksi**: Dashboard utama menampilkan DUA angka berbeda — **proyeksi** (total potensi bunga dari portofolio aktif) dan **realisasi** (kas yang benar-benar bergerak bulan ini). Keduanya tidak selalu sama.

> [!NOTE]
> **Gadai Realisasi**: Pendapatan gadai baru "terealisasi" saat nasabah **tebus barang**. Sebelum tebus, biaya_jasa dan biaya_inap masih proyeksi.
