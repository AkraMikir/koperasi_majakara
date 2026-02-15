# Dokumentasi Sistem Laporan Koperasi Karyawan

Dokumentasi ini menjelaskan **sistem laporan** pada Sistem Informasi Koperasi Karyawan, termasuk **sistem perhitungan** tiap jenis laporan, agar dapat diterapkan ulang di proyek lain.

---

## Daftar Isi

1. [Ringkasan Sistem Laporan](#1-ringkasan-sistem-laporan)
2. [Arsitektur & Sumber Data](#2-arsitektur--sumber-data)
3. [Laporan Keuangan & Akuntansi](#3-laporan-keuangan--akuntansi)
4. [Laporan Simpanan](#4-laporan-simpanan)
5. [Laporan Pinjaman](#5-laporan-pinjaman)
6. [Laporan Kas & Transaksi](#6-laporan-kas--transaksi)
7. [Laporan Anggota & Operasional](#7-laporan-anggota--operasional)
8. [Lampiran: View & Tabel Database](#8-lampiran-view--tabel-database)

---

## 1. Ringkasan Sistem Laporan

### 1.1 Jenis Laporan (17+ laporan)

| No | Nama Laporan | Route | Filter | Export |
|----|--------------|-------|--------|--------|
| 1 | Data Anggota | `laporan/data-anggota` | Search | PDF, Excel |
| 2 | Kas Simpanan | `laporan/kas-simpanan` | Periode (Y-m) | PDF |
| 3 | Kas Pinjaman | `laporan/kas-pinjaman` | tgl_dari, tgl_samp | PDF |
| 4 | Kas Anggota | `laporan/kas-anggota` | Search | PDF, Excel (detail/tagihan/simpanan) |
| 5 | Angsuran Pinjaman | `laporan/angsuran-pinjaman` | tgl_dari, tgl_samp | PDF |
| 6 | Jatuh Tempo | `laporan/jatuh-tempo` | Periode, Search | PDF |
| 7 | Kredit Macet | `laporan/kredit-macet` | Periode | PDF |
| 8 | Target Realisasi | `laporan/target-realisasi` | tgl_dari, tgl_samp | PDF |
| 9 | Pengeluaran Pinjaman | `laporan/pengeluaran-pinjaman` | tgl_dari, tgl_samp | PDF |
| 10 | Rekapitulasi | `laporan/rekapitulasi` | Periode (Y-m) | PDF |
| 11 | SHU | `laporan/shu` | tgl_dari, tgl_samp | PDF |
| 12 | Neraca Saldo | `laporan/neraca-saldo` | tgl_dari, tgl_samp | PDF |
| 13 | Buku Besar | `laporan/buku-besar` | Periode (Y-m) | PDF |
| 14 | Transaksi Kas | `laporan/transaksi-kas` | tgl_dari, tgl_samp | PDF |
| 15 | Saldo Kas | `laporan/saldo-kas` | Periode (Y-m) | PDF |
| 16 | Angkutan Karyawan | `laporan/angkutan-karyawan` | - | PDF |
| 17 | Toserda | `laporan/toserda` | - | PDF |

### 1.2 Teknologi Export

- **PDF**: DomPDF (`Barryvdh\DomPDF`)
- **Excel**: Maatwebsite/Excel atau PhpSpreadsheet

---

## 2. Arsitektur & Sumber Data

### 2.1 Tabel Utama

- **Anggota**: `tbl_anggota` / model `data_anggota`
- **Simpanan**: `tbl_trans_sp` (transaksi simpanan), `jns_simpan` (jenis simpanan)
- **Pinjaman**: `tbl_pinjaman_h` (header), `tbl_pinjaman_d` (detail angsuran), `tempo_pinjaman` (jadwal tempo)
- **Kas**: `nama_kas_tbl`, `tbl_trans_kas`, `jns_akun`
- **View agregasi**: `v_transaksi`, `v_hitung_pinjaman`, `v_rekap`, `v_rekap_simpanan`, `v_rekap_det_angsuran`, `v_shu`, `v_pengeluaran_pinjaman`, `v_hitung_pinjaman_3`

### 2.2 Konvensi Debet/Kredit

- **Simpanan**: `dk = 'D'` = setoran (debet), `dk = 'K'` = penarikan (kredit).
- **Kas**: masuk ke kas = debet (`untuk_kas_id`), keluar dari kas = kredit (`dari_kas_id`).
- **Neraca**: Aktiva → saldo positif di debet; Pasiva → saldo positif di kredit.

---

## 3. Laporan Keuangan & Akuntansi

### 3.1 Laporan SHU (Sisa Hasil Usaha)

**Controller**: `LaporanShuController`  
**View data**: `v_hitung_pinjaman`, `v_shu`

#### Parameter
- `tgl_dari`, `tgl_samp` (default: 01-01 s/d 31-12 tahun berjalan)

#### Rumus Perhitungan

1. **Laba pinjaman**
   - Sumber: `v_hitung_pinjaman` (agregasi per pinjaman: `total_pinjaman`, `total_angsuran`).
   - **Laba Pinjaman** = `SUM(total_angsuran) - SUM(total_pinjaman)`  
     (total angsuran yang masuk dikurangi total pokok pinjaman yang dikeluarkan dalam periode).

2. **Total simpanan (periode)**
   - Sumber: `v_shu`, `jns_trans = 155`, `dk = 'D'`.
   - **Total Simpanan** = `SUM(jumlah_bayar)` untuk transaksi simpanan (debet) dalam rentang tanggal.

3. **Total pendapatan anggota (periode)**
   - Sumber: `v_shu`, `jns_trans = 155`, `dk = 'K'`.
   - **Total Pendapatan Anggota** = `SUM(jumlah_bayar)` untuk pendapatan (kredit) dalam rentang tanggal.

4. **SHU sebelum pajak**
   - Dalam implementasi saat ini dapat memakai nilai dari sumber lain atau dummy; idealnya:
   - **SHU Sebelum Pajak** = Laba Pinjaman + komponen pendapatan lain − biaya (dari pembukuan).

5. **Pajak PPh (5%)**
   - **Pajak PPh** = SHU Sebelum Pajak × 5%.

6. **SHU setelah pajak**
   - **SHU Setelah Pajak** = SHU Sebelum Pajak − Pajak PPh.

7. **Alokasi SHU (persentase dari SHU setelah pajak)**
   - Dana Cadangan = 40%
   - Jasa Anggota = 40%
   - Dana Pengurus = 5%
   - Dana Karyawan = 5%
   - Dana Pendidikan = 5%
   - Dana Sosial = 5%

8. **Pembagian Jasa Anggota**
   - Jasa Usaha = 70% × Jasa Anggota  
   - Jasa Modal = 30% × Jasa Anggota  

**Total Pendapatan** = Laba Pinjaman + Total Simpanan + Total Pendapatan Anggota  
**Total Biaya** = jumlah semua dana (cadangan, pengurus, karyawan, pendidikan, sosial)

---

### 3.2 Laporan Neraca Saldo

**Controller**: `LaporanNeracaSaldoController`  
**Sumber**: `jns_akun`, `nama_kas_tbl`, `v_transaksi`

#### Parameter
- `tgl_dari`, `tgl_samp`

#### Rumus Perhitungan

1. **Pengelompokan akun**  
   Akun dikelompokkan berdasarkan karakter pertama `kd_aktiva`:
   - A = Aktiva Lancar  
   - B = Aktiva Lainnya  
   - C = Aktiva Tetap Berwujud  
   - D = Aktiva Tetap Tidak Berwujud  
   - E = Aktiva Lain-lain  
   - F = Utang  
   - G = Utang Jangka Pendek  
   - H = Utang Jangka Panjang  
   - I = Modal  
   - J = Pendapatan  
   - K = Beban  

2. **Saldo per akun (dari `v_transaksi`)**
   - Filter: `transaksi = id akun`, `tgl` antara `tgl_dari` dan `tgl_samp`.
   - **Total Debet** = `SUM(debet)`  
   - **Total Kredit** = `SUM(kredit)`  
   - **Saldo** = Total Debet − Total Kredit  

3. **Tampilan kolom**
   - Untuk setiap akun: tampilkan Total Debet dan Total Kredit (saldo tidak perlu dipecah lagi di neraca saldo; cukup agregat debet/kredit periode).

4. **Validasi**
   - **Total Debet seluruh akun** harus sama dengan **Total Kredit** (neraca seimbang).

---

### 3.3 Laporan Buku Besar

**Controller**: `LaporanBukuBesarController`  
**Sumber**: `nama_kas_tbl`, `v_transaksi`

#### Parameter
- `periode` (format `Y-m`)

#### Rumus Perhitungan

1. **Saldo awal per kas**
   - Transaksi di `v_transaksi` dengan `tgl` **sebelum** bulan periode (tahun lebih kecil, atau tahun sama tetapi bulan lebih kecil).
   - **Saldo Awal** = `SUM(debet untuk kas ini) - SUM(kredit untuk kas ini)`  
     (untuk kas = `untuk_kas = kas_id`, keluar = `dari_kas = kas_id`).

2. **Transaksi periode**
   - Ambil dari `v_transaksi` untuk tahun dan bulan periode, dimana `dari_kas = kas_id` atau `untuk_kas = kas_id`.
   - Setiap baris: jika `untuk_kas = kas_id` → debet; jika `dari_kas = kas_id` → kredit.

3. **Saldo berjalan (running balance)**
   - **Saldo** = Saldo Awal + (Debet − Kredit) kumulatif per baris.

4. **Total dan saldo akhir**
   - **Total Debet** = jumlah semua debet periode untuk kas tersebut.  
   - **Total Kredit** = jumlah semua kredit periode untuk kas tersebut.  
   - **Saldo Akhir** = Saldo Awal + Total Debet − Total Kredit.

---

## 4. Laporan Simpanan

### 4.1 Laporan Kas Simpanan

**Controller**: `LaporanKasSimpananController`  
**Sumber**: `v_rekap_simpanan`, `jns_simpan` (id 31, 32, 40, 41, 51, 52)

#### Parameter
- `periode` (Y-m)

#### Rumus Perhitungan

1. **Data per jenis simpanan**
   - Filter `v_rekap_simpanan`: `jenis_id`, `tahun = tahun periode`, `bulan = bulan periode`.
   - Setiap baris: `Debet` = setoran, `Kredit` = penarikan (nama kolom di view: `Debet`, `Kredit`).

2. **Summary per jenis**
   - **Total Debet** = jumlah semua debet per jenis.  
   - **Total Kredit** = jumlah semua kredit per jenis.  
   - **Saldo** = Total Debet − Total Kredit.  
   - **Jumlah Transaksi** = banyak baris transaksi.

3. **Summary global**
   - **Total Simpanan** = sum semua Total Debet.  
   - **Total Penarikan** = sum semua Total Kredit.  
   - **Saldo Bersih** = Total Debet − Total Kredit.  
   - **Total Anggota** = count distinct nama (atau no_ktp) yang muncul di transaksi.

---

### 4.2 Laporan Kas Anggota (Saldo Simpanan & Tagihan per Anggota)

**Controller**: `LaporanKasAnggotaController`  
**Sumber**: `tbl_trans_sp`, `v_hitung_pinjaman`, `tbl_pinjaman_h`

#### Perhitungan Saldo Simpanan per Anggota (`hitungSaldoSimpanan(no_ktp)`)

Menggunakan `tbl_trans_sp`, filter `no_ktp`:

- **Simpanan Wajib** (jenis_id = 41):  
  `SUM(jumlah WHERE dk='D') - SUM(jumlah WHERE dk='K')`
- **Simpanan Sukarela** (32): idem
- **Simpanan Khusus 2** (52): idem
- **Simpanan Pokok** (40): idem
- **Simpanan Khusus 1** (51): idem
- **Tabungan Perumahan** (156): idem

**Total Saldo Anggota** = jumlah semua saldo jenis di atas.

#### Perhitungan Tagihan Kredit (`hitungTagihanKredit(no_ktp)`)

Menggunakan `v_hitung_pinjaman`, filter `no_ktp`, `status = '1'`:

- **Pinjaman Biasa** (jenis_pinjaman = 1): `SUM(jumlah)`, `SUM(sisa_pokok)` untuk yang lunas = 'Belum'.
- **Pinjaman Barang** (jenis_pinjaman = 3): idem.

---

## 5. Laporan Pinjaman

### 5.1 View Pinjaman: `v_hitung_pinjaman`

- **total_bayar** = `SUM(tbl_pinjaman_d.jumlah_bayar)` per pinjaman.  
- **sisa_pokok** = `jumlah - total_bayar`.  
- **tagihan** = total kewajiban (di view saat ini = `p.jumlah`; bisa dikembangkan jadi jumlah + bunga + biaya).

### 5.2 Laporan Kas Pinjaman

**Controller**: `LaporanKasPinjamanController`  
**Sumber**: `tbl_pinjaman_h` + `v_hitung_pinjaman`

#### Parameter
- `tgl_dari`, `tgl_samp` (tanggal pinjam)

#### Rumus Per Hitam

- **Pokok pinjaman** = `jumlah`.  
- **Jumlah bayar** = `total_bayar` dari view.  
- **Sisa angsuran** = `lama_angsuran - COUNT(tbl_pinjaman_d)` untuk pinjam_id.  
- **Sisa tagihan** = `tagihan - total_bayar`.  
- **Angsuran per bulan** = `jumlah_angsuran + bunga_rp` (dari header).

#### Summary

- **Total pinjaman** = sum `jumlah`.  
- **Total bayar** = sum `total_bayar`.  
- **Total sisa** = sum sisa tagihan.  
- **Completion rate** = (jumlah pinjaman lunas / jumlah pinjaman) × 100%.

---

### 5.3 Laporan Angsuran Pinjaman

**Controller**: `LaporanAngsuranPinjamanController`  
**Sumber**: `v_rekap_det_angsuran`

#### Parameter
- `tgl_dari`, `tgl_samp` (tanggal bayar)

#### Rumus Per Baris (per pembayaran)

- **Pokok** = `jumlah_bayar`.  
- **Bunga** = `bunga`.  
- **Denda** = `denda_rp`.  
- **Biaya adm** = `biaya_adm`.  
- **Jumlah angsuran** = Pokok + Bunga + Denda + Biaya adm.  
- **Saldo pinjaman (sebelum)** = `jumlah - sisa_pokok`.  
- **Saldo akhir** = `sisa_pokok`.  
- **Persentase pelunasan** = `(jumlah - sisa_pokok) / jumlah × 100`.

Status: Lunas (sisa_pokok ≤ 0), Terlambat (denda_rp > 0 atau terlambat > 0), Tepat Waktu, Belum Bayar.

#### Total & Summary

- Total pokok, total bunga, total denda, total biaya adm, total jumlah angsuran = sum per kolom.  
- Rata-rata angsuran = total jumlah angsuran / jumlah baris.  
- Persentase tepat waktu / terlambat = hitung dari status.

---

### 5.4 Laporan Jatuh Tempo

**Controller**: `LaporanJatuhTempoController`  
**Sumber**: `tempo_pinjaman`, `tbl_pinjaman_h`, `tbl_pinjaman_d`, `tbl_anggota`

#### Parameter
- `periode` (Y-m), `search` (opsional)

#### Rumus Perhitungan

1. **Filter**
   - `tempo_pinjaman.tempo`: tahun dan bulan = periode.  
   - `tbl_pinjaman_h.lunas` = 'Belum'.

2. **Tagihan per tempo**
   - **Angsuran pokok** = `jumlah / lama_angsuran`.  
   - **Angsuran bunga** = `bunga_rp / lama_angsuran`.  
   - **Tagihan** = Angsuran pokok + Angsuran bunga + `biaya_adm`.

3. **Total bayar**
   - **Total bayar** = `SUM(tbl_pinjaman_d.jumlah_bayar)` per pinjam_id.

4. **Sisa**
   - **Sisa tagihan** = Tagihan − Total bayar.

Total Tagihan, Total Dibayar, Total Sisa = sum untuk semua baris yang masuk filter.

---

### 5.5 Laporan Kredit Macet

**Controller**: `LaporanKreditMacetController`  
**Sumber**: `v_hitung_pinjaman_3`, `tbl_pinjaman_d`, `tbl_anggota`

#### Parameter
- `periode` (Y-m)

#### Rumus Perhitungan

1. **Kriteria macet**
   - Pinjaman dengan `lunas = 'Belum'`.  
   - Bulan tempo (`v.tempo`) **lebih kecil** dari bulan berjalan (sudah lewat jatuh tempo).

2. **Per pinjaman**
   - **Total tagihan** = `tagihan + denda_rp`.  
   - **Total bayar** = `SUM(tbl_pinjaman_d.jumlah_bayar)`.  
   - **Sisa tagihan** = Total tagihan − Total bayar.  
   - **Hari keterlambatan** = selisih hari dari `tempo` ke hari ini (nilai negatif = sudah lewat).

---

### 5.6 Laporan Target Realisasi

**Controller**: `LaporanTargetRealisasiController`  
**Sumber**: `v_hitung_pinjaman`, `tbl_pinjaman_h`, `tbl_pinjaman_d`

#### Parameter
- `tgl_dari`, `tgl_samp`

#### Rumus Perhitungan

1. **Target**
   - **Pokok angsuran** = `jumlah_angsuran` (per bulan).  
   - **Pokok bunga** = `bunga_rp`.  
   - **Biaya adm** = `biaya_adm`.  
   - **Target angsuran bulanan** = Pokok angsuran + Pokok bunga + Biaya adm.  
   - **Total target** = Target angsuran bulanan × `lama_angsuran`.

2. **Realisasi**
   - **Realisasi pembayaran** = `total_bayar` + `SUM(bunga)` + `SUM(denda_rp)` dari `tbl_pinjaman_d`.

3. **Indikator**
   - **Persentase realisasi** = (Realisasi pembayaran / Total target) × 100.  
   - **Sisa tagihan** = `tagihan - total_bayar`.  
   - **Gap** = Target angsuran bulanan − (Realisasi / bulan sudah angsur).

4. **Status**
   - Lunas, Berjalan, Jatuh Tempo, Belum Mulai (berdasarkan lunas, sisa tagihan, lama angsuran).

---

### 5.7 Laporan Pengeluaran Pinjaman

**Controller**: `LaporanPengeluaranPinjamanController`  
**Sumber**: `v_pengeluaran_pinjaman`, `tbl_pinjaman_h`, `tbl_pinjaman_d`

#### Parameter
- `tgl_dari`, `tgl_samp` (tanggal pinjam)

#### Rumus Perhitungan

- **Tagihan total** = `jumlah + bunga_rp + biaya_adm`.  
- **Jumlah bayar** = sum `jumlah_bayar` dari detail.  
- **Sisa tagihan** = Tagihan total − Jumlah bayar.  
- **Persentase bayar** = (Jumlah bayar / Tagihan total) × 100.  
Status pinjaman: sama seperti Target Realisasi (Lunas/Berjalan/Jatuh Tempo/Belum Mulai).

---

### 5.8 Laporan Rekapitulasi

**Controller**: `LaporanRekapitulasiController`  
**Sumber**: `v_rekap`

#### Parameter
- `periode` (Y-m)

#### Rumus Perhitungan

- Data per hari: `v_rekap` filter tahun dan bulan dari `tgl_bayar`.  
- **Persentase koleksi** = (tagihan_masuk / tagihan_hari_ini) × 100 (jika tagihan_hari_ini > 0).  
- **Status hari**:  
  - Sempurna (tagihan_bermasalah = 0),  
  - Sangat Baik (≥ 90%), Baik (≥ 75%), Cukup (≥ 50%), Perlu Perhatian (< 50%).

Kolom yang ditampilkan: tanggal, jml_tagihan, target_pokok, target_bunga, tagihan_masuk, realisasi_pokok, realisasi_bunga, tagihan_bermasalah, tidak_bayar_pokok/bunga, persentase_koleksi, status.

---

## 6. Laporan Kas & Transaksi

### 6.1 Laporan Transaksi Kas

**Controller**: `LaporanTransaksiKasController`  
**Sumber**: `v_transaksi`

#### Parameter
- `tgl_dari`, `tgl_samp`

#### Rumus Perhitungan

1. **Saldo sebelumnya**
   - **Saldo sebelum tgl_dari** = `SUM(debet) - SUM(kredit)` dari `v_transaksi` untuk semua transaksi dengan `DATE(tgl) < tgl_dari`.

2. **Running balance**
   - Urut berdasarkan `tgl`, `id`.  
   - Setiap baris: **Saldo** = Saldo sebelumnya + (Debet − Kredit) kumulatif.  
   - Saldo akhir = saldo setelah transaksi terakhir.

---

### 6.2 Laporan Saldo Kas

**Controller**: `LaporanSaldoKasController`  
**Sumber**: `v_transaksi`, `nama_kas_tbl`

#### Parameter
- `periode` (Y-m)

#### Rumus Perhitungan

1. **Saldo periode sebelumnya**
   - **Saldo sebelum** = `SUM(debet) - SUM(kredit)` dari `v_transaksi` untuk semua transaksi sebelum bulan periode (tahun < atau (tahun = dan bulan <)).

2. **Per kas (contoh: Kas Tunai, Kas Besar, Bank BCA, Bank BNI)**
   - **Debet** = `SUM(debet)` dimana `untuk_kas = kas_id`, tahun dan bulan = periode.  
   - **Kredit** = `SUM(kredit)` dimana `dari_kas = kas_id`, tahun dan bulan = periode.  
   - **Saldo** = Debet − Kredit.  
   - Status: Surplus (saldo ≥ 0), Defisit (saldo < 0).

3. **Total**
   - Total saldo = sum saldo semua kas yang dipilih.

---

## 7. Laporan Anggota & Operasional

### 7.1 Laporan Data Anggota

**Controller**: `LaporanDataAnggotaController`  
**Sumber**: `tbl_anggota` / `data_anggota`

- Filter: search (nama, no_ktp, id).  
- Statistik: total anggota, aktif (aktif = 'Y'), nonaktif (aktif = 'N').  
- Tidak ada rumus keuangan; tampilan data master.

### 7.2 Laporan Angkutan Karyawan & Toserda

- **Angkutan Karyawan**: data operasional angkutan.  
- **Toserda**: laporan unit usaha toko serba ada (penjualan, pembelian, biaya usaha, dll).  

Detail perhitungan mengikuti modul masing-masing (billing, transaksi toserda).

---

## 8. Lampiran: View & Tabel Database

### 8.1 View yang Digunakan

| View | Kegunaan |
|------|----------|
| `v_transaksi` | Transaksi kas terpadu (debet/kredit, dari_kas, untuk_kas, transaksi/akun) |
| `v_hitung_pinjaman` | Agregasi pinjaman: total_bayar, sisa_pokok, tagihan per pinjaman |
| `v_hitung_pinjaman_3` | Pinjaman + tempo + denda (untuk kredit macet) |
| `v_rekap` | Rekap harian: tagihan, realisasi, target (untuk rekapitulasi) |
| `v_rekap_simpanan` | Transaksi simpanan per jenis (Debet/Kredit) |
| `v_rekap_det_angsuran` | Detail angsuran per pembayaran (pokok, bunga, denda, sisa_pokok) |
| `v_shu` | Data untuk SHU (simpanan/pendapatan per jns_trans, dk) |
| `v_pengeluaran_pinjaman` | Data pengeluaran pinjaman (header + anggota) |

### 8.2 Tabel Utama

- **Anggota**: `tbl_anggota`  
- **Simpanan**: `tbl_trans_sp`, `jns_simpan`  
- **Pinjaman**: `tbl_pinjaman_h`, `tbl_pinjaman_d`, `tempo_pinjaman`  
- **Kas**: `nama_kas_tbl`, `tbl_trans_kas`, `jns_akun`  

### 8.3 Penerapan di Proyek Lain

1. **Skema database**: Buat tabel dan view yang sama (atau subset) sesuai kebutuhan.  
2. **Rumus**: Gunakan rumus di dokumen ini per jenis laporan; sesuaikan nama kolom jika skema beda.  
3. **Controller**: Pola controller = filter parameter → ambil data (view/tabel) → hitung agregat & turunan → kirim ke view/export.  
4. **Export**: Reuse pola PDF/Excel (DomPDF, PhpSpreadsheet/Maatwebsite).  
5. **Periode**: Konsisten pakai `tgl_dari`/`tgl_samp` atau `periode` (Y-m) sesuai jenis laporan.

---

---

## 9. Ringkasan Rumus Perhitungan (Quick Reference)

Rumus di bawah ini bisa langsung dipakai saat implementasi di proyek lain.

### Pinjaman

| Konsep | Rumus |
|--------|--------|
| Total bayar per pinjaman | `SUM(tbl_pinjaman_d.jumlah_bayar)` per pinjam_id |
| Sisa pokok | `jumlah - total_bayar` |
| Tagihan per angsuran (bulanan) | `(jumlah / lama_angsuran) + (bunga_rp / lama_angsuran) + biaya_adm` |
| Total tagihan pinjaman | `jumlah + bunga_rp + biaya_adm` |
| Sisa tagihan | Total tagihan − Total bayar |
| Persentase pelunasan | `(total_bayar / total_tagihan) × 100` |

### Simpanan

| Konsep | Rumus |
|--------|--------|
| Saldo per jenis (per no_ktp) | `SUM(jumlah WHERE dk='D') - SUM(jumlah WHERE dk='K')` filter jenis_id & no_ktp |
| Total simpanan periode | Sum semua debet (setoran) |
| Total penarikan periode | Sum semua kredit (penarikan) |
| Saldo bersih | Total simpanan − Total penarikan |

### Kas & Neraca

| Konsep | Rumus |
|--------|--------|
| Saldo kas periode | `SUM(debet WHERE untuk_kas=id) - SUM(kredit WHERE dari_kas=id)` |
| Saldo sebelumnya | Sum (debet − kredit) semua transaksi sebelum tgl_dari |
| Running balance | Saldo sebelumnya + (Debet − Kredit) kumulatif per baris |
| Neraca seimbang | Total Debet semua akun = Total Kredit semua akun |

### SHU

| Konsep | Rumus |
|--------|--------|
| Laba pinjaman | Total angsuran diterima − Total pokok pinjaman dikeluarkan |
| Pajak PPh 5% | SHU sebelum pajak × 0,05 |
| SHU setelah pajak | SHU sebelum pajak − Pajak PPh |
| Dana cadangan 40% | SHU setelah pajak × 0,40 |
| Jasa anggota 40% | SHU setelah pajak × 0,40 |
| Jasa usaha (70% jasa anggota) | Jasa anggota × 0,70 |
| Jasa modal (30% jasa anggota) | Jasa anggota × 0,30 |

### Koleksi & Rekapitulasi

| Konsep | Rumus |
|--------|--------|
| Persentase koleksi harian | `(tagihan_masuk / tagihan_hari_ini) × 100` |
| Persentase realisasi pinjaman | `(realisasi_pembayaran / total_target) × 100` |
| Completion rate | `(pinjaman_lunas / total_pinjaman) × 100` |

---

*Dokumentasi ini dibuat dari kode sumber proyek Kopkar App (Laravel). Untuk implementasi detail view SQL, lihat folder `database/migrations` yang mendefinisikan view.*
