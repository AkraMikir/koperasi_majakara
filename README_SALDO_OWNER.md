# Panduan Saldo Owner (Admin Utama) - Koperasi Majakara

Dokumen ini menjelaskan struktur, sumber data, dan alur pengelolaan dana pada level **Owner (Admin Utama)** dalam sistem Koperasi Majakara.

---

## 1. Konsep Saldo Terpusat (Centralized Balance)
Saldo Owner adalah representasi dari seluruh aset likuid koperasi yang berada di bawah pengawasan langsung Owner. Saldo ini dibagi secara ketat berdasarkan:
1.  **Sumber (Source)**: Memisahkan dana berdasarkan asalnya (Tabungan, Pinjaman, Petty Cash, atau Modal Awal).
2.  **Tipe (Type)**: Memisahkan antara uang fisik (**Cash**) dan uang digital (**Transfer/Bank**).

Pemisahan ini bertujuan untuk memastikan transparansi dan mencegah pencampuran dana antar produk.

---

## 2. Sumber Dana Masuk (Incoming Sources)

Dana Owner bertambah melalui beberapa jalur utama:

### A. Setoran Kantor (Admin Settlement)
*   Jalur utama uang tunai masuk ke kantor.
*   Berasal dari **Admin Operasional** yang menyetorkan hasil kolektif harian (Setoran Tabungan, Bayar Angsuran).
*   **Status**: Saldo bertambah setelah Owner melakukan **Verifikasi (Approve)**.

### B. Transaksi Digital Langsung
*   Setoran tabungan atau pembayaran angsuran nasabah yang dilakukan melalui **Transfer Bank** langsung ke rekening utama Owner.
*   Sistem mencatat mutasi ini sebagai penambahan saldo tipe `Transfer`.

### C. Input Manual (Add Capital)
*   Owner dapat memasukkan dana secara manual ke sistem (Misal: Suntikan modal pribadi atau pendapatan lain).
*   Dicatat melalui fitur **"Transaksi Owner"** dengan tipe `Masuk` dan sumber `Other`.

---

## 3. Alur Dana Keluar (Outgoing Flow / CRUD Keluar)

Owner memiliki kontrol penuh atas pengeluaran dana melalui mekanisme berikut:

### A. Kirim Dana ke Admin (Capital Injection)
*   Digunakan untuk memberikan modal kerja atau pengisian ulang (*refill*) kas di tangan Admin Operasional.
*   **Proses**: Owner memilih sumber dana (misal: Tabungan) -> Pilih tipe (Cash/Transfer) -> Kirim.
*   **Mekanisme Hold**: Saldo Owner akan langsung berkurang dengan status `Hold`. Jika Admin menolak, saldo akan di-*refund* otomatis ke Owner.

### B. Penarikan Owner (Withdrawal)
*   Digunakan untuk keperluan di luar operasional langsung (Misal: Pengambilan profit atau biaya overhead kantor).
*   Tercatat di tabel `owner_withdrawals`.
*   Wajib mencantumkan sumber dana dan bukti foto/keterangan.

### C. Pengeluaran Manual (Expense)
*   Pencatatan biaya-biaya kecil atau tak terduga.
*   Dicatat melalui fitur **"Transaksi Owner"** dengan tipe `Keluar`.

---

## 4. Validasi & Governance (Aturan Main)

Sistem menerapkan aturan ketat dalam setiap transaksi keluar:
1.  **Kecukupan Saldo per Sumber**: Owner tidak bisa mengirim dana dari sumber `Tabungan` jika saldo di sumber tersebut tidak mencukupi, meskipun total saldo keseluruhan mencukupi.
2.  **Validasi Tipe**: Saldo `Cash` tidak bisa digunakan untuk transaksi `Transfer`, begitu pula sebaliknya.
3.  **Audit Trail**: Setiap pergerakan dana (In/Out) menghasilkan ID transaksi unik (PCOW) dan tercatat dalam `PettyCashLog` untuk kebutuhan audit di masa depan.
4.  **Real-time View**: Owner dapat melihat *Running Balance* (Saldo Berjalan) melalui SQL View `vw_saldo_owner_detail` yang menghitung mutasi secara otomatis.

---

## 5. Detail Teknis (Metadata)

*   **Model Utama**:
    *   `PettyCashOwnerTransaksi`: Mencatat setiap mutasi manual In/Out oleh Owner.
    *   `OwnerWithdrawal`: Mencatat penarikan dana resmi oleh Owner.
    *   `PettyCashSaldo`: Tabel utama yang menyimpan angka saldo terakhir per user, per role, dan per tipe.
*   **Database View**:
    *   `vw_saldo_owner_detail`: View khusus yang merangkum saldo per sumber secara real-time.

---
*Dokumentasi ini dibuat sebagai referensi pengelolaan aset tingkat tinggi Koperasi Majakara.*
