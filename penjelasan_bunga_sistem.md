# Laporan Analisis Perhitungan Bunga Koperasi Majakara

Berdasarkan hasil pemindaian (*scanning*) pada *source code* proyek Koperasi Majakara, sistem perhitungan bunga terbagi ke dalam 3 modul utama: **Pinjaman**, **Deposito**, dan **Gadai**. Bunga ini dibedakan menjadi **Bunga Keuntungan** (pemasukan bagi koperasi) dan **Bunga Keluar** (pengeluaran bagi koperasi).

Berikut adalah penjelasan detail mengenai perhitungan bunga pada masing-masing sistem:

---

## 1. Sistem Pinjaman (Bunga Keuntungan)
Sistem pinjaman memberikan keuntungan bagi koperasi melalui bunga yang dibebankan kepada nasabah yang meminjam dana.

- **Jenis Bunga:** Bunga Keuntungan (Pemasukan)
- **Komponen Variabel:**
  - `jumlah_pinjam`: Nominal uang yang disetujui untuk dicairkan ke nasabah.
  - `bunga_persen`: Persentase bunga yang berlaku berdasarkan durasi/tenor pinjaman (diambil dari tabel master).
  - `bunga_rp`: Nominal bunga dalam rupiah.

### Rumus Perhitungan:
```text
Bunga Rupiah (bunga_rp) = Jumlah Pinjaman x (Bunga Persen / 100)
Total Kewajiban Nasabah = Jumlah Pinjaman + Bunga Rupiah
```

### Mekanisme Pencairan & Pembayaran:
1. **Pencairan:** Nasabah akan menerima dana penuh sebesar **`jumlah_pinjam`** dari Petty Cash / Owner Wallet Koperasi (dipotong biaya transfer antar bank jika menggunakan bank non-BCA).
2. **Kewajiban / Pelunasan:** Nasabah harus mengembalikan total dana sebesar **`jumlah_pinjam + bunga_rp`**.
3. **Pencatatan:** Komponen `bunga_rp` akan menjadi margin profit atau penghasilan bunga bagi Koperasi Majakara.

---

## 2. Sistem Deposito (Bunga Keluar)
Koperasi memberikan imbal hasil atau bunga kepada nasabah yang menempatkan dananya dalam bentuk Deposito. Ini merupakan beban/pengeluaran bagi koperasi.

- **Jenis Bunga:** Bunga Keluar (Pengeluaran)
- **Komponen Variabel:**
  - `pokok`: Nominal uang yang didepositokan oleh nasabah.
  - `bunga`: Persentase bunga deposito per tahun (sesuai paket).
  - `tenor_hari`: Lama hari deposito berjalan (berdasarkan tanggal pencairan dikurangi tanggal mulai).
  - `pembagi`: Asumsi jumlah hari dalam setahun (standar akuntansi).

### Rumus Perhitungan (dijalankan via Scheduler `GenerateDepositoPeringatan`):
```text
Bunga Kotor   = Pokok x Bunga Tahunan x (Tenor Hari / Pembagi)
Pajak         = Bunga Kotor x 20% (Pajak penghasilan final)
Bunga Bersih  = Bunga Kotor - Pajak
Total Cair    = Pokok + Bunga Bersih
```

### Mekanisme Pencairan & Pembayaran:
1. **Pencairan Bunga:** Saat deposito jatuh tempo, Koperasi akan membayarkan kembali nilai uang **`Pokok`** ditambah dengan **`Bunga Bersih`**.
2. **Pencatatan:** Perhitungan bunga ini dicatat dan diakumulasi secara akrual. Koperasi menanggung beban bunga, namun memotong kewajiban pajak 20% atas bunga tersebut yang ditanggung nasabah.

---

## 3. Sistem Gadai (Bunga Keuntungan)
Sistem Gadai (pawn) pada sistem terbaru (Gadai Baru) tidak secara eksplisit menggunakan istilah "bunga_rp", melainkan menggunakan terminologi **Biaya Jasa** dan **Biaya Inap**, yang secara substansi berfungsi sama dengan bunga pinjaman karena merupakan instrumen keuntungan Koperasi.

- **Jenis Bunga:** Bunga Keuntungan (Pemasukan)
- **Komponen Variabel:**
  - `nominal_deal`: Uang pinjaman yang disepakati dari hasil taksiran barang (emas, elektronik, kendaraan).
  - `rate_jasa`: Persentase keuntungan (bunga) atas jasa gadai.
  - `biaya_inap`: Biaya penitipan/penyimpanan barang (bisa nominal tetap atau berdasarkan persentase).

### Rumus Perhitungan:
```text
Biaya Jasa = Nominal Deal x (Rate Jasa / 100)

Biaya Inap = 
   a. Jika memiliki nominal tetap: Nominal tetap (contoh untuk Kendaraan)
   b. Jika persentase: Nominal Deal x (Rate Inap Persen / 100)

Total Kewajiban Tebus = Nominal Deal + Biaya Jasa + Biaya Inap
```

### Mekanisme Pencairan & Pembayaran:
1. **Pencairan:** Saat gadai disetujui, Koperasi mentransfer atau menyerahkan uang tunai sebesar **`nominal_deal`** kepada nasabah.
2. **Tebus Barang:** Pada saat nasabah ingin mengambil kembali barangnya (Jatuh Tempo), nasabah harus membayar uang pinjaman **`nominal_deal`** ditambah dengan **`Biaya Jasa`** dan **`Biaya Inap`**.
3. **Pencatatan:** Akumulasi dari **`Biaya Jasa`** dan **`Biaya Inap`** menjadi pos pemasukan murni (profit) untuk Koperasi Majakara.

---

## Kesimpulan Transaksi (Aliran Dana Koperasi)
- **Uang Masuk / Pendapatan (Bunga Keuntungan):** Berasal dari akumulasi `bunga_rp` (Pinjaman) serta gabungan dari `Biaya Jasa` + `Biaya Inap` (Gadai).
- **Uang Keluar / Beban (Bunga Keluar):** Berasal dari kewajiban pembayaran `Bunga Bersih` (Deposito) kepada nasabah.
