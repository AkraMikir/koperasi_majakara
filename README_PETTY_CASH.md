# Panduan Fitur Petty Cash - Koperasi Majakara

Dokumen ini menjelaskan alur kerja, integrasi, dan peran pengguna dalam sistem **Petty Cash (Kas Kecil)** di project Koperasi Majakara, khususnya hubungannya dengan produk **Tabungan** dan **Pinjaman**.

---

## 1. Ikhtisar (Overview)
Petty Cash adalah sistem pengelolaan dana operasional harian yang dipegang oleh **Admin Operasional**. Sistem ini mencatat setiap aliran uang fisik (Cash) maupun saldo digital (Transfer) yang masuk dan keluar melalui meja Admin sebelum akhirnya disetorkan ke **Owner (Kantor Pusat)**.

Fitur ini memastikan adanya rekonsiliasi yang akurat antara saldo di sistem dengan uang fisik yang ada di tangan Admin.

---

## 2. Peran & Tanggung Jawab (Roles)

### A. Owner (Admin Utama)
*   **Penyedia Modal**: Mengirimkan dana awal operasional ke Admin melalui fitur **"Kirim Dana"**.
*   **Verifikator**: Meninjau dan menyetujui setoran harian dari Admin melalui fitur **"Verifikasi Setoran"**.
*   **Monitoring**: Memantau saldo real-time di tangan tiap-tiap Admin (Cash & Transfer).

### B. Admin Operasional
*   **Kasir Harian**: Menerima setoran tabungan/angsuran dan memberikan uang pencairan pinjaman/penarikan tabungan kepada nasabah.
*   **Pengelola Saldo**: Bertanggung jawab atas keseimbangan antara saldo sistem dengan fisik.
*   **Penyetor**: Melakukan tutup buku harian dan menyetorkan uang kolektif ke Owner.

---

## 3. Integrasi Produk (Tabungan & Pinjaman)

Petty Cash bertindak sebagai "jembatan" untuk semua transaksi nasabah yang dilakukan melalui Admin.

### A. Integrasi Tabungan (Savings)
*   **Setoran Tunai (Cash In)**: 
    *   Nasabah menyerahkan uang tunai ke Admin.
    *   Admin menginput transaksi setoran.
    *   **Efek**: Saldo Petty Cash Admin bertambah, Saldo Tabungan Nasabah bertambah.
*   **Penarikan Tunai (Cash Out)**:
    *   Nasabah meminta penarikan dana tunai.
    *   Admin memberikan uang tunai kepada nasabah.
    *   **Efek**: Saldo Petty Cash Admin berkurang, Saldo Tabungan Nasabah berkurang.

### B. Integrasi Pinjaman (Loans)
*   **Bayar Angsuran (Cash In)**:
    *   Nasabah membayar cicilan secara tunai/transfer via Admin.
    *   **Efek**: Saldo Petty Cash Admin bertambah, Tagihan Pinjaman Nasabah berkurang.
*   **Pencairan Pinjaman (Cash Out)**:
    *   Pinjaman nasabah disetujui untuk cair via Petty Cash.
    *   Admin menyerahkan dana pinjaman kepada nasabah.
    *   **Efek**: Saldo Petty Cash Admin berkurang, Status Pinjaman Nasabah aktif/cair.

---

## 4. Alur Kerja Utama (Workflow)

### Tahap 1: Pengisian Saldo (Capital Injection)
1.  **Owner** menggunakan fitur **Kirim Dana** untuk mengirim modal awal ke Admin.
2.  Saldo Owner dipotong (status: *Hold*).
3.  **Admin** melakukan konfirmasi (**Approve**) saat dana fisik diterima.
4.  Saldo Petty Cash Admin bertambah.

### Tahap 2: Transaksi Harian (Daily Transactions)
1.  Admin melayani nasabah (Tabungan/Pinjaman).
2.  Setiap transaksi tercatat di tabel `petty_cash_transaksi_nasabah`.
3.  Saldo Admin bergerak secara otomatis (Bertambah jika nasabah setor, Berkurang jika nasabah tarik/cair).

### Tahap 3: Setoran Kantor (Settlement/Closing)
1.  Di akhir hari atau periode tertentu, Admin melakukan **Setoran ke Kantor**.
2.  Admin memilih transaksi nasabah mana saja yang akan disetorkan uang fisiknya.
3.  Admin mengunggah bukti setoran dan menyerahkan uang ke Owner.
4.  **Owner** memverifikasi setoran. Jika disetujui, dana masuk kembali ke saldo utama Owner.

---

## 5. Fitur Unggulan

*   **Pemisahan Cash & Transfer**: Sistem memisahkan catatan uang fisik (Cash) dan uang digital (Transfer) agar tidak tercampur saat audit.
*   **Hold & Refund**: Saldo yang dikirim Owner ke Admin akan di-*hold* dan dapat di-*refund* otomatis jika Admin menolak kiriman tersebut.
*   **Sistem Notifikasi**: Owner mendapatkan peringatan jika saldo di tangan Admin sudah di bawah limit operasional.
*   **Riwayat Mutasi Detail**: Setiap pergerakan uang tercatat dengan referensi ID transaksi nasabah yang jelas (Audit Trail).

---

## 6. Detail Teknis (Metadata)

*   **Model Utama**:
    *   `PettyCashSaldo`: Pusat data saldo saat ini (Role: Admin/Owner, Tipe: Cash/Transfer).
    *   `PettyCashTransaksiNasabah`: Jembatan transaksi antara produk (Tabungan/Pinjaman) dengan Petty Cash.
    *   `PettyCashPenerimaan`: Log pengiriman modal dari Owner ke Admin.
    *   `PettyCashSetoranKantor`: Log setoran balik dari Admin ke Owner.
*   **Keamanan**:
    *   Garda *Double Create*: Sistem mencegah pembuatan data ganda pada transaksi yang sama.
    *   Verifikasi Bertingkat: Setiap perpindahan dana antar peran wajib melalui proses persetujuan (Approve).

---
*Dokumentasi ini dibuat untuk referensi operasional harian Koperasi Majakara.*
