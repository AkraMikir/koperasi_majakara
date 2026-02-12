# ESTIMASI BIAYA & PROPOSAL PROJECT (RAB)
# SISTEM INFORMASI KOPERASI MAJAKARA (SI-MAJAKARA)

**Tanggal**: 04 Februari 2026  
**Dibuat Oleh**: Senior System Architect (AI Assistant)  
**Versi**: 1.0

---

## 1. RINGKASAN EKSEKUTIF

Sistem Informasi Koperasi Majakara adalah solusi perbankan digital komprehensif yang dirancang untuk mendigitalkan seluruh operasional koperasi, mulai dari pendaftaran anggota, simpan pinjam, hingga manajemen gadai dan deposito. Sistem ini dibangun dengan teknologi web modern (Laravel 12) yang menjamin keamanan data, kecepatan akses, dan kemudahan pengembangan di masa depan.

Dokumen ini berisi rincian fitur, estimasi waktu pengerjaan, dan rencana anggaran biaya (RAB) untuk penyelesaian proyek hingga tahap **Production Ready (100%)**.

---

## 2. RUANG LINGKUP PEKERJAAN (SCOPE OF WORK)

Proyek ini mencakup pengembangan modul-modul berikut:

### A. Modul Core & Keamanan
1.  **Sistem Autentikasi Bertingkat**: Login/Register dengan verifikasi OTP (WhatsApp/Email) dan PIN Transaksi.
2.  **Role Management**: Akses kontrol bertingkat untuk Nasabah, Admin Operasional, dan Super Admin.
3.  **Security Layer**: Proteksi XSS, CSRF, Rate Limiting, dan Enkripsi Data Sensitif.
4.  **Audit Trail**: Pencatatan log aktivitas sistem untuk keamanan.

### B. Modul Nasabah (Front-Office)
1.  **Registrasi Digital & OCR**:
    *   Form pendaftaran multi-step.
    *   **Teknologi OCR (Optical Character Recognition)** untuk scan otomatis KTP/KK.
    *   Upload dokumen bukti diri.
2.  **Dashboard Nasabah**: Ringkasan saldo, tanggungan pinjaman, dan aktivitas terkini.
3.  **Sistem Tabungan**:
    *   Cek Saldo & Mutasi Rekening.
    *   Pengajuan Setoran (Transfer Bank & Janji Temu Tunai).
    *   Pengajuan Penarikan Saldo.
4.  **Sistem Pinjaman**:
    *   Pengajuan Pinjaman (Mingguan/Bulanan).
    *   Simulasi Angsuran.
    *   Pembayaran Angsuran (Transfer & Tunai).
    *   Monitoring Status Pinjaman & Denda.
5.  **Fitur Janji Temu**: Sistem booking jadwal untuk transaksi tunai di kantor (Teller System).
6.  **Pengaturan Akun**: Ganti Password, Reset PIN, Update Profil.

### C. Modul Admin (Back-Office)
1.  **Dashboard Eksekutif**: Statistik real-time (Total Aset, Laba Rugi, NPL, Jumlah Anggota).
2.  **Manajemen Anggota**:
    *   Verifikasi/Approval pendaftaran anggota baru.
    *   Manajemen data anggota & reset akses.
3.  **Approval Workflow**:
    *   Persetujuan/Penolakan Pengajuan Pinjaman.
    *   Persetujuan/Penolakan Transaksi Tabungan.
    *   Pencairan Dana.
4.  **Manajemen Master Data**: Pengaturan Suku Bunga, Denda, Tenor, Wilayah, dll.
5.  **Laporan Keuangan**: Laporan Harian, Bulanan, Neraca, dan Arus Kas.

### D. Modul Pengembangan Lanjutan (Future Phase)
1.  **Sistem Deposito Berjangka**: Pembukaan, perhitungan bunga otomatis, dan pencairan.
2.  **Sistem Gadai Digital**: Penaksiran barang, pencairan dana, dan pelelangan.

---

## 3. ESTIMASI BIAYA (RAB)

Estimasi biaya berikut dihitung berdasarkan kompleksitas fitur, teknologi yang digunakan, dan standar profesional pengembangan perangkat lunak enterprise.

| NO | ITEM PEKERJAAN | DESKRIPSI DETAIL | ESTIMASI HARGA (IDR) |
|:--:|:---|:---|---:|
| **I** | **ANALISIS & DESAIN SISTEM** | | **Rp 7.500.000** |
| 1 | Requirement Gathering | Analisis kebutuhan bisnis dan alur kerja koperasi. | |
| 2 | System Architecture | Desain database, ERD, dan arsitektur server. | |
| 3 | UI/UX Design | Desain antarmuka (wireframe & mockup) yang user-friendly. | |
| | | | |
| **II** | **PENGEMBANGAN BACKEND & API** | | **Rp 25.000.000** |
| 1 | Core Framework Setup | Laravel 12 Setup, Database Migration, Seeding. | |
| 2 | Authentication System | JWT/Session Auth, OTP WhatsApp (Integration), PIN Security. | |
| 3 | Logic Bisnis Tabungan | Perhitungan saldo, mutasi, validasi transaksi. | |
| 4 | Logic Bisnis Pinjaman | Algoritma bunga, denda otomatis, jadwal angsuran. | |
| 5 | Logic Bisnis Deposito & Gadai | Perhitungan bunga berjangka & taksiran gadai. | |
| 6 | Notification Service | Integrasi WhatsApp API (Fonnte) & Email Gateway. | |
| | | | |
| **III** | **PENGEMBANGAN FRONTEND (WEB APP)** | | **Rp 18.500.000** |
| 1 | Member Area (Nasabah) | Implementasi desain responsif mobile-first untuk nasabah. | |
| 2 | Admin Dashboard | Panel admin dengan chart statistik dan manajemen data table. | |
| 3 | OCR Integration | Fitur scan KTP otomatis pada form registrasi. | |
| 4 | Landing Page | Halaman profil perusahaan yang modern dan informatif. | |
| | | | |
| **IV** | **INFRASTRUKTUR & DEPLOYMENT** | | **Rp 5.000.000** |
| 1 | Server Setup | Konfigurasi VPS (Linux/Ubuntu), Nginx, PHP, MySQL. | |
| 2 | Security Hardening | SSL Setup, Firewall, DDoS Protection basic. | |
| 3 | CI/CD Pipeline | Otomatisasi deployment (Git). | |
| | | | |
| **V** | **TESTING & TRAINING** | | **Rp 4.000.000** |
| 1 | Quality Assurance (QA) | Functional Testing, Security Testing, load testing. | |
| 2 | User Training | Pelatihan untuk Admin dan Staff Koperasi. | |
| 3 | User Manual | Dokumentasi penggunaan sistem (PDF/Web). | |

---

### **TOTAL ESTIMASI BIAYA: Rp 60.000.000,-**
*(Enam Puluh Juta Rupiah)*

---

## 4. OPSI PAKET PENGEMBANGAN

Jika budget terbatas, pengembangan dapat dibagi menjadi beberapa tahap (Phasing):

### **PAKET A: MVP (Minimum Viable Product) - Rp 35.000.000**
*   **Fokus**: Simpan Pinjam Dasar.
*   **Fitur**:
    *   Login/Register (Tanpa OCR).
    *   Simpanan (Setor/Tarik).
    *   Pinjaman standar (Tanpa Denda Otomatis).
    *   Admin Panel Dasar.
    *   *Tidak termasuk*: Deposito, Gadai, Notifikasi WA, Laporan Kompleks.

### **PAKET B: PRO (Standard) - Rp 48.000.000**
*   **Fokus**: Operasional Penuh.
*   **Fitur**:
    *   Semua fitur Paket A.
    *   Sistem Denda & Perhitungan Bunga Kompleks.
    *   Notifikasi WhatsApp.
    *   Laporan Keuangan Standar.
    *   OCR Integration.

### **PAKET C: ENTERPRISE (Full Suite) - Rp 60.000.000**
*   **Fokus**: Ekosistem Lengkap.
*   **Fitur**:
    *   Semua fitur Paket B.
    *   Sistem Deposito & Gadai.
    *   Dashboard Analitik Lanjutan.
    *   Prioritas Support & Warranty 6 Bulan.

---

## 5. ESTIMASI WAKTU (TIMELINE)

Total waktu pengerjaan diperkirakan **3 - 4 Bulan** dengan rincian:

1.  **Minggu 1-2**: Analisis & Desain.
2.  **Minggu 3-8**: Development Fase 1 (Core, Tabungan, Pinjaman).
3.  **Minggu 9-12**: Development Fase 2 (Admin, Laporan, Integrasi WA/OCR).
4.  **Minggu 13-14**: Testing & Revisi.
5.  **Minggu 15**: Deployment & Training.

---

## 6. LAYANAN MAINTENANCE (OPSIONAL)

Setelah masa garansi (biasanya 3 bulan) berakhir, ditawarkan paket maintenance:

*   **Basic Support (Rp 1.500.000/bulan)**: Monitoring server, backup rutin, bug fix ringan.
*   **Premium Support (Rp 3.500.000/bulan)**: Prioritas response 24/7, minor feature update, audit security bulanan.

---

**Catatan**:
*   Harga belum termasuk biaya sewa Server (VPS) dan Domain per tahun.
*   Harga belum termasuk biaya langganan API WhatsApp (Fonnte/Vendor lain).
*   Harga dapat dinegosiasikan sesuai penyesuaian fitur.
