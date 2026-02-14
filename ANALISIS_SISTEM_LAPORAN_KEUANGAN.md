# Analisis Sistem Laporan Keuangan – Admin Koperasi Majakara

Dokumen ini menganalisis **kebutuhan dan rancangan sistem laporan keuangan** pada modul admin Koperasi Majakara, termasuk jenis laporan yang disarankan, sumber data, dan prioritas pengembangan.

---

## Daftar Isi

1. [Konteks & Tujuan](#1-konteks--tujuan)
2. [Sumber Data yang Tersedia](#2-sumber-data-yang-tersedia)
3. [Gap: Laporan vs Data](#3-gap-laporan-vs-data)
4. [Rekomendasi Jenis Laporan](#4-rekomendasi-jenis-laporan)
5. [Spesifikasi per Laporan](#5-spesifikasi-per-laporan)
6. [Arsitektur & Teknologi](#6-arsitektur--teknologi)
7. [Prioritas & Fase Pengembangan](#7-prioritas--fase-pengembangan)
8. [Referensi](#8-referensi)

---

## 1. Konteks & Tujuan

### 1.1 Konteks Proyek

- **Sistem**: SI Koperasi Majakara (Laravel), dengan modul **Tabungan**, **Pinjaman**, **Deposito**, **Gadai**, **Janji Temu**, **Nasabah**.
- **RAB/Scope**: Laporan Keuangan disebutkan sebagai “Laporan Harian, Bulanan, Neraca, dan Arus Kas”.
- **Pihak pemakai**: Admin/Karyawan koperasi untuk keputusan operasional, pengawas, dan kebutuhan compliance.

### 1.2 Tujuan Sistem Laporan

- Memberikan **ringkasan keuangan** per produk (tabungan, pinjaman) dan per periode (harian/bulanan/tahunan).
- Mendukung **monitoring** transaksi, saldo, dan pinjaman outstanding.
- Menyediakan **dokumen cetak/export** (PDF/Excel) untuk arsip dan pelaporan.
- Memungkinkan **perluasan ke Neraca/Arus Kas/SHU** jika nanti ada modul kas/akun/akuntansi.

---

## 2. Sumber Data yang Tersedia

Berdasarkan migration dan model yang ada di proyek:

| Domain       | Tabel / Konsep | Keterangan |
|-------------|----------------|------------|
| **Tabungan** | `tbl_pengajuan_tabungan` | Pengajuan setoran (status, nominal, tgl) |
|             | `tbl_pengajuan_penarikan_tabungan` | Pengajuan penarikan (transfer/tunai, nominal, status) |
|             | `trans_tabungan` | Transaksi riil: setor/penarik, nominal, `tgl_transaksi`, `id_anggota`, `id_jns_transaksi` |
|             | `tbl_janji_temu_tabungan` | Janji temu setor/penarikan tunai |
| **Pinjaman** | `tbl_pengajuan_pinjaman` | Pengajuan (nominal, durasi, status, tgl_cair) |
|             | `tbl_pinjaman_h` | Pinjaman aktif: `jumlah_pinjam`, `bunga_rp`, `ags_bulan`, `tgl_pinjam`, `lunas` |
|             | `tempo_pinjaman_b` / `tempo_pinjaman_m` | Jadwal angsuran: `tgl_jatuh_tempo`, `jumlah_tagihan`, `jumlah_terbayar`, `status_bayar` |
|             | `tbl_pengajuan_pembayaran_pinjaman` | Pengajuan pembayaran angsuran (transfer/tunai) |
| **Nasabah** | `tbl_nasabah`, `users` | Data anggota untuk filter dan label |
| **Master**  | `jns_transaksi`, `jns_via` | Jenis transaksi & channel (transfer/tunai) |
| **Lain**    | Deposito, Gadai | Bisa dipakai untuk laporan terpisah jika sudah dipakai operasional |

**Yang belum ada di codebase saat ini:**

- Tabel **kas** (nama_kas_tbl, mutasi kas per rekening).
- Tabel **akun/coa** (jns_akun) dan **jurnal/transaksi akuntansi** (debet/kredit per akun).
- View agregasi khusus laporan (v_transaksi, v_shu, v_rekap_simpanan, dll.) seperti di dokumentasi sistem laporan koperasi lain.

Implikasi: laporan **Neraca**, **Arus Kas**, dan **SHU** dalam bentuk standar akuntansi membutuhkan penambahan modul kas/akun atau setidaknya view/jurnal yang konsisten dengan double-entry. Laporan **produk (tabungan & pinjaman)** dan **rekapitulasi transaksi** dapat dibangun hanya dari data yang sudah ada.

---

## 3. Gap: Laporan vs Data

| Kebutuhan (RAB/Umum) | Data Tersedia | Kesimpulan |
|----------------------|---------------|------------|
| Laporan Harian/Bulanan | ✅ `trans_tabungan.tgl_transaksi`, tempo & pembayaran pinjaman | Bisa dibuat dari transaksi & pinjaman |
| Rekapitulasi Tabungan | ✅ `trans_tabungan`, pengajuan setor/tarik | Bisa: mutasi, saldo per nasabah, rekap per periode |
| Rekapitulasi Pinjaman | ✅ `tbl_pinjaman_h`, tempo, pengajuan pembayaran | Bisa: outstanding, angsuran masuk, jatuh tempo |
| Neraca | ❌ Tidak ada chart of account & jurnal | Perlu modul akun/jurnal atau definisi “neraca sederhana” dari saldo produk |
| Arus Kas | ❌ Tidak ada buku kas terpusat | Bisa “arus kas operasional” dari transaksi tabungan + pinjaman (setoran masuk, penarikan keluar, pencairan pinjaman keluar, angsuran masuk) |
| SHU | ❌ Tidak ada alokasi pendapatan/beban per akun | Bisa “kontribusi margin” dari bunga pinjaman vs data simpanan; SHU lengkap butuh akun |

Dokumen **DOKUMENTASI_SISTEM_LAPORAN_KOPERASI.md** di repo ini memuat rumus dan contoh laporan untuk sistem yang sudah punya **jns_akun**, **v_transaksi**, **v_shu**, dll. Rumus tersebut dapat dijadikan referensi saat nanti menambah modul akuntansi; untuk fase pertama, fokus pada laporan yang hanya memakai tabel tabungan dan pinjaman yang ada.

---

## 4. Rekomendasi Jenis Laporan

Disarankan membagi menjadi **dua fase**: laporan dari data operasional yang ada (Fase 1), dan laporan akuntansi/keuangan formal (Fase 2) setelah ada sumber data kas/akun.

### 4.1 Fase 1 – Laporan dari Data Existing (Disarankan Dibangun Dulu)

| No | Nama Laporan | Deskripsi Singkat | Sumber Data Utama | Filter Utama |
|----|----------------|-------------------|-------------------|--------------|
| 1 | **Laporan Rekapitulasi Harian/Bulanan** | Ringkasan transaksi tabungan & pinjaman per hari/bulan (total setoran, penarikan, pencairan pinjaman, angsuran masuk) | `trans_tabungan`, `tbl_pinjaman_h`, tempo, pengajuan cair/pembayaran | Tanggal / Bulan |
| 2 | **Laporan Tabungan** | Mutasi transaksi tabungan per periode (setor vs tarik), optional: saldo per nasabah / per jenis transaksi | `trans_tabungan`, `jns_transaksi` | Periode (tgl_dari–tgl_samp atau bulan) |
| 3 | **Laporan Saldo Tabungan** | Saldo tabungan per nasabah pada suatu tanggal (agregasi trans_tabungan sampai tgl tersebut) | `trans_tabungan` | Tanggal cutoff |
| 4 | **Laporan Pinjaman Aktif (Outstanding)** | Daftar pinjaman belum lunas, sisa pokok, sisa angsuran, keterlambatan | `tbl_pinjaman_h`, `tempo_pinjaman_*` | Status, optional periode |
| 5 | **Laporan Angsuran Pinjaman** | Realisasi pembayaran angsuran per periode (pokok, bunga, denda jika ada) | `tempo_pinjaman_*`, `tbl_pengajuan_pembayaran_pinjaman` | tgl_dari – tgl_samp |
| 6 | **Laporan Jatuh Tempo** | Angsuran yang jatuh tempo dalam periode (bulan berjalan atau range) | `tempo_pinjaman_*`, `tbl_pinjaman_h` | Periode (bulan/tgl) |
| 7 | **Laporan Pengajuan (Setor/Tarik/Pinjaman/Pembayaran)** | Ringkasan pengajuan per status (pending/disetujui/ditolak) per periode | `tbl_pengajuan_tabungan`, `tbl_pengajuan_penarikan_tabungan`, `tbl_pengajuan_pinjaman`, `tbl_pengajuan_pembayaran_pinjaman` | Periode, status |

Ini mencakup “Laporan Harian/Bulanan” dari RAB dan memberi fondasi untuk monitoring tanpa modul akuntansi.


### 4.2 Fase 2 – Setelah Ada Modul Kas/Akun (Opsional / Nanti)

| No | Nama Laporan | Keterangan |
|----|----------------|------------|
| 8 | **Neraca** | Butuh: daftar akun (aktiva/pasiva/modal) dan saldo per akun dari jurnal/buku besar. |
| 9 | **Laporan Arus Kas** | Butuh: buku kas atau jurnal kas; atau “arus kas operasional” dari agregasi setoran/penarikan/pencairan/angsuran. |
| 10 | **Laporan SHU** | Butuh: pendapatan dan beban per akun (atau minimal definisi pendapatan bunga vs beban bunga simpanan). |

Rumus dan alur untuk Neraca, Buku Besar, SHU, dll. dapat mengacu ke **DOKUMENTASI_SISTEM_LAPORAN_KOPERASI.md**.

---

## 5. Spesifikasi per Laporan

### 5.1 Laporan Rekapitulasi Harian/Bulanan

- **Tujuan**: Satu halaman ringkasan untuk manajemen (hari atau bulan).
- **Konten**:
  - **Tabungan**: Total setoran (periode), total penarikan (periode), selisih (net setoran).
  - **Pinjaman**: Total pencairan (periode), total angsuran masuk (periode), outstanding (dari pinjaman belum lunas).
- **Sumber**: Agregasi `trans_tabungan` (group by tgl/bulan), `tbl_pinjaman_h` (tgl_cair, lunas), tempo + pengajuan pembayaran untuk realisasi angsuran.
- **Filter**: Tanggal (hari) atau Bulan (Y-m).
- **Export**: PDF, optional Excel.

### 5.2 Laporan Tabungan (Mutasi Transaksi)

- **Tujuan**: Daftar transaksi setor/penarikan per periode.
- **Konten**: Tanggal, nasabah, jenis transaksi (setor/penarik), nominal, keterangan; subtotal setor, subtotal tarik, saldo bergerak (running balance) jika diinginkan.
- **Sumber**: `trans_tabungan` join nasabah, `jns_transaksi`.
- **Filter**: `tgl_dari`, `tgl_samp` (atau pilih bulan).
- **Export**: PDF, Excel.

### 5.3 Laporan Saldo Tabungan

- **Tujuan**: Saldo per nasabah pada suatu tanggal (untuk rekonsiliasi atau informasi).
- **Rumus**: Per nasabah, jumlah semua `nominal` transaksi setor (mis. id_jns_transaksi = setor) − jumlah `nominal` transaksi penarikan sampai tanggal cutoff. Definisi “setor” vs “tarik” mengikuti master `jns_transaksi` atau flag di aplikasi.
- **Filter**: Tanggal cutoff.
- **Export**: PDF, Excel.

### 5.4 Laporan Pinjaman Aktif (Outstanding)

- **Tujuan**: Daftar pinjaman yang belum lunas.
- **Konten**: Id pinjaman, nasabah, tgl pinjam, nominal pinjaman, total terbayar, sisa pokok, sisa angsuran (berapa kali), status (tepat waktu/telat).
- **Sumber**: `tbl_pinjaman_h` (lunas = belum), agregasi dari `tempo_pinjaman_*` (jumlah_terbayar, status_bayar).
- **Filter**: Opsional periode (tgl pinjam) atau status.
- **Export**: PDF, Excel.

### 5.5 Laporan Angsuran Pinjaman

- **Tujuan**: Realisasi pembayaran angsuran dalam periode.
- **Konten**: Tanggal bayar, pinjaman, nasabah, angsuran ke-, pokok, bunga, denda (jika ada), total; total per halaman/periode.
- **Sumber**: `tempo_pinjaman_b`/`tempo_pinjaman_m` (yang sudah punya tgl_bayar/jumlah_terbayar), bisa dikaitkan ke `tbl_pengajuan_pembayaran_pinjaman` untuk tgl konfirmasi.
- **Filter**: `tgl_dari`, `tgl_samp`.
- **Export**: PDF, Excel.

### 5.6 Laporan Jatuh Tempo

- **Tujuan**: Angsuran yang jatuh tempo di periode tertentu (untuk penagihan).
- **Konten**: Pinjaman, nasabah, tanggal jatuh tempo, nominal tagihan, status (sudah bayar/belum/telat).
- **Sumber**: `tempo_pinjaman_*` where `tgl_jatuh_tempo` dalam range bulan/tgl.
- **Filter**: Bulan (Y-m) atau tgl_dari–tgl_samp.
- **Export**: PDF, Excel.

### 5.7 Laporan Pengajuan

- **Tujuan**: Ringkasan pengajuan (setor tabungan, tarik tabungan, pinjaman baru, pembayaran pinjaman) per status.
- **Konten**: Jumlah per tipe (setor/tarik/pinjaman/pembayaran) dan per status (pending/disetujui/ditolak); optional list singkat.
- **Sumber**: Keempat tabel pengajuan.
- **Filter**: Periode, status.
- **Export**: PDF, Excel (opsional).

---

## 6. Arsitektur & Teknologi

### 6.1 Struktur Direktori (Saran)

```
app/Http/Controllers/Admin/
  Laporan/
    LaporanKeuanganController.php   → index, rekapitulasi, tabungan, saldo-tabungan, ...
    atau per laporan:
    RekapitulasiController.php
    LaporanTabunganController.php
    LaporanPinjamanController.php
    ...
routes/web.php
  Route::prefix('laporan')->name('laporan.')->group(...)
resources/views/admin/laporan/
  index.blade.php           → Dashboard / daftar semua laporan
  rekapitulasi.blade.php
  tabungan.blade.php
  saldo-tabungan.blade.php
  pinjaman-aktif.blade.php
  angsuran-pinjaman.blade.php
  jatuh-tempo.blade.php
  pengajuan.blade.php
```

### 6.2 Naming Route (Saran)

- `admin.laporan.index` → halaman indeks laporan
- `admin.laporan.rekapitulasi`
- `admin.laporan.tabungan`
- `admin.laporan.saldo-tabungan`
- `admin.laporan.pinjaman-aktif`
- `admin.laporan.angsuran-pinjaman`
- `admin.laporan.jatuh-tempo`
- `admin.laporan.pengajuan`

Setiap laporan: GET untuk form filter + tampilan, GET/POST untuk export (mis. `?export=pdf` atau route terpisah `admin.laporan.tabungan.export`).

### 6.3 Export

- **PDF**: DomPDF (`barryvdh/laravel-dompdf`) atau alternatif yang sudah dipakai di proyek.
- **Excel**: Maatwebsite Excel atau PhpSpreadsheet untuk export tabel besar.
- Konsisten gunakan **filter periode** (tgl_dari, tgl_samp atau bulan) sesuai spesifikasi tiap laporan.

### 6.4 Sidebar Admin

- Tambah menu **Laporan** (atau **Laporan Keuangan**) di sidebar admin dengan submenu sesuai laporan Fase 1, atau satu halaman indeks yang mengarahkan ke tiap laporan.

---

## 7. Prioritas & Fase Pengembangan

### Fase 1 (Disarankan pertama)

1. **Laporan Rekapitulasi Harian/Bulanan** – nilai tinggi untuk manajemen harian.
2. **Laporan Tabungan (mutasi)** dan **Laporan Saldo Tabungan** – langsung dari `trans_tabungan`.
3. **Laporan Pinjaman Aktif** dan **Laporan Angsuran Pinjaman** – dari `tbl_pinjaman_h` dan tempo.
4. **Laporan Jatuh Tempo** – untuk operasional penagihan.
5. **Laporan Pengajuan** – untuk monitoring antrian persetujuan.

Urutan implementasi bisa disesuaikan: misal dimulai dari Rekapitulasi + Tabungan, lalu Pinjaman.

### Fase 2 (Setelah ada kas/akun atau kebutuhan formal)

- Neraca, Arus Kas, SHU – mengacu ke **DOKUMENTASI_SISTEM_LAPORAN_KOPERASI.md** dan penambahan modul akuntansi.

---

## 8. Referensi

- **DOKUMENTASI_SISTEM_LAPORAN_KOPERASI.md** – rumus dan contoh laporan keuangan/akuntansi (SHU, Neraca Saldo, Buku Besar, Kas Simpanan, Kas Pinjaman, Angsuran, Jatuh Tempo, dll.).
- **RAB_PROYEK_KOPERASI_MAJAKARA.md** – scope “Laporan Keuangan: Laporan Harian, Bulanan, Neraca, dan Arus Kas”.
- Migration: `2024_01_01_000003_create_tabungan_tables.php`, `2024_01_01_000004_create_pinjaman_tables.php`, `2024_01_01_000002_create_master_tables.php`.

---

*Dokumen analisis ini dipakai sebagai dasar pengembangan modul Laporan Keuangan admin Koperasi Majakara. Implementasi teknis (controller, query, view) mengikuti spesifikasi di atas dan disesuaikan dengan konvensi kode proyek.*
