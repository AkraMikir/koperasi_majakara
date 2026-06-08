# Rangkuman Logika dan Sumber Data Dashboard Bunga Koperasi Majakara

Dokumen ini menjelaskan sumber data dan logika perhitungan untuk setiap KPI card dan metrik yang ditampilkan pada 4 halaman Dashboard Analisis Bunga, setelah dihubungkan dengan data riil dari database.

---

## 1. Dashboard Utama (Ringkasan Bunga)
Halaman ini memberikan gambaran keseluruhan mengenai arus kas yang bersumber dari bunga (Keuntungan vs Beban).

### KPI Cards:
*   **Total Pemasukan Bunga**
    *   **Sumber Data**: Penggabungan dari `tbl_pinjaman_h` dan `tbl_gadai_h`.
    *   **Logika**: Penjumlahan dari **Total Bunga Pinjaman** (hanya pinjaman aktif/lunas, status 3 & 4) ditambah **Total Pendapatan Gadai** (Biaya Jasa + Biaya Inap dari gadai berstatus 2, 3, & 4).
*   **Total Pengeluaran Bunga**
    *   **Sumber Data**: `tbl_deposito_h`.
    *   **Logika**: Total dari **Bunga Bersih** yang harus dibayarkan kepada deposan (setelah dipotong Pajak Final 20%) untuk semua deposito aktif (status 1).
*   **Net Cashflow Bunga**
    *   **Sumber Data**: Hasil perhitungan KPI di atas.
    *   **Logika**: Total Pemasukan Bunga dikurangi Total Pengeluaran Bunga. Indikator ini menunjukkan profitabilitas bersih koperasi dari perputaran bunga.

---

## 2. Dashboard Bunga Pinjaman
Halaman ini fokus pada analisis pendapatan koperasi yang berasal dari pembiayaan/pinjaman.

### KPI Cards:
*   **Total Bunga Masuk**
    *   **Sumber Data**: Kolom `bunga_rp` di `tbl_pinjaman_h`.
    *   **Logika**: Penjumlahan nominal bunga dalam rupiah dari semua pinjaman yang berstatus disetujui/sedang berjalan (status 3) atau lunas (status 4).
*   **Total Pinjaman Aktif**
    *   **Sumber Data**: `tbl_pinjaman_h`.
    *   **Logika**: Perhitungan jumlah record (count) pinjaman yang berstatus 3 atau 4.
*   **Rata-rata Bunga per Pinjaman**
    *   **Sumber Data**: Kalkulasi dari metrik sebelumnya.
    *   **Logika**: `Total Bunga Masuk` dibagi `Total Pinjaman Aktif`. Memberikan *insight* mengenai rata-rata keuntungan per debitur.
*   **Proyeksi Bunga Bulan Ini**
    *   **Sumber Data**: `tbl_pinjaman_h`.
    *   **Logika**: Total `bunga_rp` dari pinjaman-pinjaman yang disetujui/mulai berjalan pada bulan dan tahun berjalan (berdasarkan kolom `tgl_disetujui`).

---

## 3. Dashboard Bunga Deposito
Halaman ini memantau beban/kewajiban bunga yang harus dibayarkan koperasi kepada penyimpan dana (deposan), beserta aspek perpajakannya.

### KPI Cards:
*   **Total Bunga Kotor**
    *   **Sumber Data**: `tbl_deposito_h`.
    *   **Logika**: Total akumulasi bunga sebelum pajak untuk semua deposito aktif. Dihitung berdasarkan rumus: `Nominal x (Suku Bunga / 100) / 12 x Lama Tenor (dalam bulan)`. Lama tenor didapat dari selisih `tgl_mulai` dan `tgl_jatuh_tempo`.
*   **Pajak Bunga (20%)**
    *   **Sumber Data**: Kalkulasi otomatis dari Total Bunga Kotor.
    *   **Logika**: Sesuai PPh Final atas bunga simpanan koperasi, dihitung flat sebesar 20% dari Total Bunga Kotor.
*   **Total Bunga Bersih (Kewajiban)**
    *   **Sumber Data**: Kalkulasi.
    *   **Logika**: `Total Bunga Kotor` dikurangi `Pajak Bunga (20%)`. Angka inilah yang merepresentasikan beban uang *cash* yang sebenarnya keluar dari kas koperasi.
*   **Deposito Aktif**
    *   **Sumber Data**: `tbl_deposito_h`.
    *   **Logika**: Menghitung jumlah record deposito yang berstatus aktif (status 1).

---

## 4. Dashboard Bunga Gadai
Halaman ini menganalisis pendapatan yang berasal dari unit bisnis Gadai, yang terbagi menjadi komponen Jasa dan Inap.

### KPI Cards:
*   **Total Pendapatan Gadai**
    *   **Sumber Data**: Gabungan dari Jasa dan Inap pada `tbl_gadai_h` (status 2, 3, 4).
    *   **Logika**: Penjumlahan dari **Biaya Jasa** dan **Biaya Inap**.
*   **Biaya Jasa (Gadai)**
    *   **Sumber Data**: Kolom `bunga_rp` di `tbl_gadai_h`.
    *   **Logika**: Total penjumlahan nominal biaya jasa dari transaksi gadai yang valid.
*   **Biaya Inap**
    *   **Sumber Data**: Simulasi dari `tbl_gadai_h`.
    *   **Logika**: Saat ini dihitung secara dinamis/simulasi sebesar **10% dari nominal Biaya Jasa (`bunga_rp`)**. (Catatan: Jika koperasi sudah memiliki struktur kolom `biaya_inap` di tabel, logika ini dapat langsung disesuaikan).
*   **Total Barang Aktif**
    *   **Sumber Data**: `tbl_gadai_h`.
    *   **Logika**: Menghitung jumlah barang yang sedang digadaikan berdasarkan transaksi gadai yang aktif.

---
*Catatan Tambahan: Semua visualisasi grafik (tren bulanan dan komposisi) ditarik menggunakan agregasi per-bulan dari model-model terkait menggunakan fungsi tanggal (Carbon).*
