# Rangkuman Card Dashboard Bunga (Koperasi Majakara)

Dokumen ini menjelaskan definisi dan rumus perhitungan dari setiap Kartu Indikator Kinerja Utama (KPI) yang ditampilkan pada Dashboard Bunga Koperasi Majakara (termasuk modul Utama, Pinjaman, Deposito, dan Gadai).

## 1. Dashboard Utama Bunga (`/admin/bunga`)

### 1.1 Total Bunga Pinjaman (Pemasukan)
- **Definisi:** Total akumulasi pendapatan bunga yang dihasilkan dari seluruh transaksi pinjaman.
- **Rumus/Logika:** Total `bunga_rp` dari seluruh data pinjaman (`PinjamanH`) yang memiliki status pengajuan disetujui atau sudah dicairkan (status `3` atau `4`).

### 1.2 Total Biaya Gadai (Pemasukan)
- **Definisi:** Total akumulasi pendapatan dari layanan gadai, mencakup biaya jasa gadai dan biaya penitipan (inap) barang.
- **Rumus/Logika:** Total `biaya_jasa` + `biaya_inap` dari seluruh data gadai (`GadaiActive`) yang bersatus `aktif` atau `perpanjang`.

### 1.3 Total Bunga Deposito (Pengeluaran Bersih)
- **Definisi:** Total estimasi kewajiban bunga yang harus dibayarkan koperasi kepada nasabah deposito (nilai bersih setelah dipotong pajak 20%).
- **Rumus/Logika:** 
  1. Hitung Bunga Kotor per Deposito = `nominal_awal * (bunga / 100) * (tenor_hari / 365)`.
  2. Hitung Pajak per Deposito = `Bunga Kotor * 20%`.
  3. Total Bersih = Total Keseluruhan Bunga Kotor - Total Keseluruhan Pajak. (Diambil dari seluruh data deposito bersatus `aktif` atau `selesai`).

### 1.4 Net Margin (Laba Bersih Bunga)
- **Definisi:** Keuntungan bersih yang didapatkan koperasi dari selisih antara total pemasukan bunga dengan pengeluaran kewajiban bunga.
- **Rumus/Logika:** `(Total Bunga Pinjaman + Total Biaya Gadai) - Total Bunga Deposito`.

---

## 2. Dashboard Bunga Pinjaman (`/admin/bunga/pinjaman`)

### 2.1 Total Bunga Masuk
- **Definisi:** Sama dengan Total Bunga Pinjaman pada Dashboard Utama.

### 2.2 Pinjaman Aktif
- **Definisi:** Jumlah kontrak pinjaman yang saat ini masih berjalan dan belum lunas.
- **Rumus/Logika:** Hitung (count) seluruh data pinjaman yang kolom `lunas` tidak sama dengan `'lunas'`.

### 2.3 Rata-rata Bunga/Pinjaman
- **Definisi:** Rata-rata nilai bunga yang didapatkan dari satu kontrak pinjaman aktif.
- **Rumus/Logika:** `Total Bunga Masuk / Jumlah Pinjaman Aktif`. (Jika tidak ada pinjaman aktif, nilainya 0).

### 2.4 Proyeksi Bulan Depan
- **Definisi:** Estimasi pendapatan bunga pinjaman pada bulan berikutnya berdasarkan tren pertumbuhan yang ditetapkan oleh sistem.
- **Rumus/Logika:** `Total Bunga dari Pinjaman Aktif * 1.05` (Menggunakan asumsi rasio pertumbuhan tetap sebesar 5% dari portofolio aktif).

---

## 3. Dashboard Bunga Deposito (`/admin/bunga/deposito`)

### 3.1 Total Bunga Keluar (Bunga Bersih)
- **Definisi:** Estimasi kewajiban koperasi untuk membayar bunga kepada nasabah deposito aktif, yang sudah dipotong pajak.
- **Rumus/Logika:** Diambil khusus dari data deposito yang statusnya `'aktif'`. Total = `Sum(Bunga Kotor) - Sum(Pajak 20%)`.

### 3.2 Deposito Aktif
- **Definisi:** Jumlah kontrak deposito nasabah yang saat ini masih berjalan di koperasi.
- **Rumus/Logika:** Hitung (count) data `DepositoH` dengan status `'aktif'`.

### 3.3 Pajak Deposito (20%)
- **Definisi:** Total pajak final sebesar 20% yang dikenakan terhadap bunga deposito nasabah. Nilai ini biasanya disetorkan oleh koperasi ke kas negara sebagai PPh Final.
- **Rumus/Logika:** `Total Bunga Kotor Deposito Aktif * 20%`.

### 3.4 Jatuh Tempo Bulan Ini
- **Definisi:** Jumlah kontrak deposito yang akan berakhir / jatuh tempo pada bulan dan tahun berjalan.
- **Rumus/Logika:** Hitung deposito aktif dimana `Bulan(tgl_jatuh_tempo) == Bulan Sekarang` dan `Tahun(tgl_jatuh_tempo) == Tahun Sekarang`.

---

## 4. Dashboard Biaya Gadai (`/admin/bunga/gadai`)

### 4.1 Total Pendapatan Gadai
- **Definisi:** Sama dengan Total Biaya Gadai pada Dashboard Utama, tapi hanya dihitung khusus untuk gadai bersatus aktif atau diperpanjang saat ini.
- **Rumus/Logika:** `Total Biaya Jasa + Total Biaya Inap` dari `GadaiActive` dengan status `aktif` atau `perpanjang`.

### 4.2 Biaya Jasa
- **Definisi:** Total pendapatan yang berasal murni dari tarif / biaya jasa (rate pinjaman) gadai.
- **Rumus/Logika:** Akumulasi nilai kolom `biaya_jasa` pada seluruh kontrak gadai berjalan.

### 4.3 Biaya Inap
- **Definisi:** Total pendapatan yang ditarik dari biaya asuransi atau perawatan/penyimpanan (inap) barang gadai milik nasabah.
- **Rumus/Logika:** Akumulasi nilai kolom `biaya_inap` pada seluruh kontrak gadai berjalan.

### 4.4 Gadai Aktif
- **Definisi:** Jumlah transaksi gadai yang saat ini masih ditahan oleh koperasi (status berjalan).
- **Rumus/Logika:** Hitung (count) seluruh data gadai (`GadaiActive`) bersatus `aktif` atau `perpanjang`.

---
*Catatan: Parameter waktu pencarian (seperti tren 6 bulan terakhir) dibuat dinamis menggunakan interval `Carbon::now()->subMonths(...)` dengan acuan tanggal pencatatan seperti `tgl_pinjam` atau `tgl_mulai`.*
