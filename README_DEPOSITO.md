# Panduan Fitur Deposito - Koperasi Majakara

Dokumen ini menjelaskan alur kerja, fitur, dan peran pengguna dalam sistem Deposito di project Koperasi Majakara.

---

## 1. Ikhtisar (Overview)
Fitur Deposito memungkinkan Nasabah untuk menyimpan dana dalam jangka waktu tertentu (tenor) dengan imbal hasil berupa bunga yang kompetitif. Sistem ini terintegrasi dengan saldo tabungan nasabah dan sistem notifikasi real-time.

---

## 2. Peran & Akses (Roles)

### A. Nasabah
*   **Akses**: Menu "Deposito" di Dashboard Nasabah.
*   **Kemampuan**:
    *   Simulasi deposito berdasarkan tenor dan nominal.
    *   Mengajukan pembukaan deposito baru (via Transfer atau Potong Saldo Tabungan).
    *   Melihat daftar deposito aktif dan riwayat pengajuan.
    *   Melihat detail perkembangan bunga harian (jika diaktifkan).
    *   Mengajukan pencairan deposito yang telah jatuh tempo.

### B. Admin Operasional
*   **Akses**: Menu "Deposito" di Admin Panel.
*   **Kemampuan**:
    *   Verifikasi pengajuan deposito (Cek bukti transfer atau kecukupan saldo tabungan).
    *   Menyetujui (Approve) atau Menolak (Reject) pengajuan.
    *   Memproses pencairan dana nasabah (Transfer manual ke rekening atau otomatis ke saldo tabungan).
    *   Monitoring deposito yang akan segera jatuh tempo.

### C. Owner (Admin Utama)
*   **Akses**: Akses penuh ke Admin Panel.
*   **Kemampuan**:
    *   Memiliki kemampuan yang sama dengan Admin Operasional.
    *   Melihat laporan keuangan terkait total kewajiban deposito.
    *   Manajemen Master Data (Tenor, Suku Bunga, dan kebijakan pajak).

---

## 3. Alur Kerja Utama (Workflow)

### Tahap 1: Penempatan (Placement)
1.  **Nasabah** memilih tenor (1, 3, 6, atau 12 bulan) dan memasukkan nominal (Min. Rp 1.000.000).
2.  **Nasabah** memilih metode setoran:
    *   **Transfer**: Nasabah mengunggah foto bukti transfer bank.
    *   **Saldo Tabungan**: Sistem akan mengunci/memotong saldo tabungan nasabah saat pengajuan disetujui.
3.  **Sistem** mencatat sebagai `PengajuanDeposito` dengan status `Pending`.

### Tahap 2: Verifikasi & Aktivasi
1.  **Admin** meninjau pengajuan. Jika metode **Saldo Tabungan**, admin memverifikasi kecukupan saldo riil.
2.  **Admin** menyetujui pengajuan:
    *   Sistem menghasilkan Nomor Deposito (Contoh: `DP2604260001`).
    *   Jika via tabungan, saldo tabungan nasabah otomatis didebet.
    *   Status berubah menjadi `Aktif`.
    *   Nasabah menerima notifikasi keberhasilan.

### Tahap 3: Masa Aktif & Bunga
1.  Deposito berjalan sesuai tenor yang dipilih.
2.  Sistem menghitung estimasi bunga berdasarkan persentase per tahun (p.a).
3.  **Pajak**: Setiap perolehan bunga dikenakan pajak otomatis sebesar **20%** sesuai kebijakan (terprogram di controller).

### Tahap 4: Jatuh Tempo & Pencairan
1.  Saat mencapai `tgl_jatuh_tempo`, deposito siap dicairkan.
2.  **Nasabah** mengajukan pencairan melalui aplikasi, memilih metode:
    *   **Pencairan ke Rekening**: Admin akan melakukan transfer manual dan mengunggah bukti TF.
    *   **Pencairan ke Saldo Tabungan**: Sistem akan otomatis memindahkan Pokok + Bunga Bersih ke saldo tabungan nasabah saat admin menyetujui.
3.  **Admin** memproses pencairan dan status deposito berubah menjadi `Dicairkan`.

---

## 4. Fitur Unggulan

*   **Integrasi Saldo Tabungan**: Memudahkan nasabah memindahkan dana antar produk koperasi tanpa perlu transfer bank manual.
*   **Kalkulasi Pajak Otomatis**: Transparansi bagi nasabah mengenai nominal bersih yang akan diterima (setelah potongan pajak 20%).
*   **Suku Bunga Dinamis**: Admin dapat mengatur suku bunga yang berbeda untuk tiap rentang nominal dan tenor melalui database.
*   **Notifikasi Real-time**: Nasabah mendapatkan pemberitahuan via sistem saat pengajuan diterima, diaktifkan, maupun dicairkan.
*   **Manajemen Dokumen**: Penyimpanan bukti transfer yang rapi untuk audit internal.

---

## 5. Detail Teknis (Metadata)

*   **Model Utama**:
    *   `PengajuanDeposito`: Menampung data awal sebelum aktif.
    *   `DepositoH`: Header data deposito yang sedang aktif. Kini memiliki kolom `status_peringatan` (`tidak_perlu` / `tentatif` / `need_prepare`) dan `tgl_peringatan`.
    *   `TransDeposito`: Log setiap transaksi (setor awal, penambahan bunga, pencairan).
    *   `PencairanDeposito`: Menampung data pengajuan penarikan dana. `jenis_pencairan` memiliki 3 nilai: `rek_nasabah`, `saldo_tabungan`, `petty_cash_operator`.
    *   `DepositoPersiapanCair`: **[BARU]** Tabel to-do list dana yang harus disiapkan Owner sebelum jatuh tempo.
*   **Tabel Pendukung**:
    *   `jns_tenor_deposito`: Menyimpan pilihan durasi (bulan/hari).
    *   `suku_bunga_deposito`: Matriks bunga berdasarkan tenor dan nominal.
    *   `paket_deposito`: Menyimpan kombinasi tenor, suku bunga, dan minimal nominal untuk paket deposito yang dapat dipilih nasabah.

---

## 6. Manajemen Paket Deposito (Owner)

### A. CRUD Paket Deposito
* **Akses**: Menu "Paket Deposito" di bawah dropdown "Deposito" pada Admin Panel. Hanya bisa diakses oleh **Admin Utama (Owner)**.
* **Kemampuan**:
  * Buat / Edit / Hapus paket deposito (menentukan nama paket, tenor, suku bunga, minimal/maksimal nominal).
  * Mengaktifkan atau menonaktifkan paket untuk nasabah.
  * *Rencana ke depan*: Nasabah hanya bisa memilih paket yang memiliki `status='aktif'`.

### B. Tabel Master Data
* `paket_depositos`: Tabel yang menyimpan kombinasi tenor, suku bunga, batas nominal, dan status ketersediaan.
* Rute dilindungi middleware `admin.utama` untuk memastikan keamanan akses.

---

## 6. Sistem Peringatan Jatuh Tempo (Warning System)

### Cara Kerja
Setiap hari pukul **07:00**, sistem menjalankan command `deposito:generate-peringatan --days=7` yang:
1. Mencari semua `DepositoH` dengan `status=aktif` dan `tgl_jatuh_tempo` dalam 7 hari ke depan.
2. Menghitung `pokok`, `bunga_kotor`, `pajak (20%)`, `bunga_bersih`, dan `total_dibayar` secara pre-computed.
3. Menyimpan hasilnya ke tabel `deposito_persiapan_cair` dengan `status=tentatif`.
4. Meng-update `DepositoH.status_peringatan = 'need_prepare'`.

### Dashboard Peringatan
Owner/Admin dapat mengakses `/admin/deposito/peringatan` untuk:
- Melihat **ringkasan dana per tanggal jatuh tempo** (berapa yang butuh transfer, petty cash, atau otomatis ke tabungan).
- Melihat daftar detail deposito yang perlu disiapkan.
- Mendapat **alert merah** jika ada deposito yang jatuh tempo hari ini.

---

## 7. Metode Pencairan (3 Jalur)

| Metode | Alur Dana | Efek Saldo Owner |
|--------|-----------|-----------------|
| `saldo_tabungan` | Otomatis digital ke tabungan nasabah | Tidak berubah |
| `rek_nasabah` | Owner transfer bank → Nasabah | `PettyCashOwnerTransaksi` (keluar) + `PettyCashSaldo` Owner berkurang |
| `petty_cash_operator` | Owner kirim tunai ke Admin → Admin serahkan ke nasabah | Saldo Owner di-hold via `PettyCashPenerimaan`, Admin `PettyCashSaldo` bertambah lalu berkurang saat serahkan |

### Integrasi Petty Cash & Saldo Owner
Setiap pencairan yang melibatkan dana fisik/transfer **wajib** mencatatkan mutasi di:
- `PettyCashOwnerTransaksi` (audit trail Owner)
- `PettyCashSaldo` (running balance Owner/Admin)
- `vw_saldo_owner_detail` (otomatis reflect via view SQL)

---
*Dokumentasi ini dibuat untuk referensi tim pengembang dan operasional Koperasi Majakara.*
