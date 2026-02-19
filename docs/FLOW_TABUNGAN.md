# Flow Tabungan: Pengajuan Transfer, Janji Temu, Penarikan Transfer & Janji Temu

Dokumen ini menjelaskan alur (flow) fitur tabungan dari sisi **Nasabah** dan **Admin**, untuk **setoran** (transfer & janji temu) serta **penarikan** (transfer & janji temu).

---

## 1. SETORAN (Menabung)

### 1.1 Setoran via Transfer

| Langkah | Pihak | Aksi | Keterangan |
|--------|--------|------|------------|
| 1 | Nasabah | Buka **Tabungan → Nabung Sekarang** | Halaman form setoran |
| 2 | Nasabah | Pilih **Transfer**, isi nominal, upload **bukti transfer**, masukkan **PIN** | Validasi: nominal min Rp 10.000, minimal 1 bukti foto |
| 3 | Nasabah | Submit form | `POST nasabah/tabungan/pengajuan-transfer` → `submitSetoran()` |
| 4 | Sistem | Buat **PengajuanTabungan** (status `1` = Pending), simpan bukti di **tbl_bukti_foto** | ID format: T + T + STR (contoh: 190220260001TTFSTR) |
| 5 | Sistem | Notifikasi admin, redirect ke **Status Pengajuan Setor** | Nasabah bisa cek status di sini |
| 6 | Admin | Buka **Tabungan → Pengajuan Setoran** | Daftar pengajuan pending |
| 7 | Admin | Buka detail pengajuan, lalu **Setujui** (atau Tolak) | `POST admin/tabungan/pengajuan-setor/{id}/approve` → `approveSetor()` |
| 8 | Sistem | Buat **TransTabungan** (jenis STR, via TF), update pengajuan status → `2` (Disetujui) | Saldo nasabah bertambah |
| 9 | Nasabah | Cek **Status Pengajuan Setor** / **Riwayat transaksi** | Transaksi setoran tampil, saldo terupdate |

**Ringkas:**  
Nasabah isi form transfer + bukti → PengajuanTabungan (pending) → Admin approve → TransTabungan (STR) → saldo naik.

---

### 1.2 Setoran via Janji Temu (Tunai)

| Langkah | Pihak | Aksi | Keterangan |
|--------|--------|------|------------|
| 1 | Nasabah | Buka **Nabung Sekarang**, pilih **Tunai** | Redirect ke halaman **Janji Temu** dengan nominal & keterangan terisi |
| 2 | Nasabah | Isi **lokasi**, **tanggal**, **waktu** janji temu, **PIN** | Validasi: tanggal setelah hari ini, nominal min Rp 10.000 |
| 3 | Nasabah | Submit | `POST nasabah/tabungan/janji-temu` → `submitJanjiTemu()` |
| 4 | Sistem | Buat **JanjiTemuTabungan** (jenis setoran implisit, status `1` = Menunggu) | Tidak ada record di PengajuanTabungan; hanya di tbl_janji_temu_tabungan |
| 5 | Nasabah | Redirect ke **Status Janji Temu** | Bisa lihat daftar janji temu |
| 6 | Admin | Buka **Janji Temu** (menu universal: Tabungan + Pinjaman + Pembayaran) | List dari view `v_janji_temu_universal` |
| 7 | Admin | Buka **detail janji temu** (tabungan), isi nominal (bisa diedit), upload bukti penerimaan, **Buat Transaksi** | `POST admin/tabungan/janji-temu/{id}/create-trans` → `createTransFromJanjiTemu()` |
| 8 | Sistem | Update JanjiTemuTabungan status → `2` (Selesai), buat **TransTabungan** (STR, via CS), simpan bukti di tbl_bukti_foto | Saldo nasabah bertambah |
| 9 | Nasabah | Cek **Status Janji Temu** / **Riwayat transaksi** | Janji temu “Selesai”, transaksi setoran tunai tampil |

**Ringkas:**  
Nasabah buat janji temu setoran tunai → JanjiTemuTabungan (menunggu) → Admin di halaman Janji Temu buat transaksi setoran → TransTabungan (STR, via CS) → saldo naik.

---

## 2. PENARIKAN (Tarik Tabungan)

### 2.1 Penarikan via Transfer

| Langkah | Pihak | Aksi | Keterangan |
|--------|--------|------|------------|
| 1 | Nasabah | Buka **Tabungan → Penarikan** | Form penarikan; Nama Bank & No Rekening bisa auto-fill dari data rekening nasabah |
| 2 | Nasabah | Pilih **Transfer**, isi nominal, bank tujuan, no rekening, **PIN** | Validasi: saldo ≥ nominal (nanti di sistem juga nominal + biaya transfer ≤ saldo) |
| 3 | Nasabah | Submit | `POST nasabah/tabungan/penarikan` → `submitPenarikan()` |
| 4 | Sistem | Buat **PengajuanPenarikanTabungan** (metode_transfer = `transfer`, status `1` = Pending) | Satu record saja; tidak membuat Janji Temu |
| 5 | Sistem | Notifikasi admin, redirect ke **Status Pengajuan Tarik** | Nasabah pantau status di sini |
| 6 | Admin | Buka **Tabungan → Pengajuan Penarikan** | Hanya pengajuan **transfer** yang di sini; tunai lewat Janji Temu |
| 7 | Admin | Buka detail, pilih **Bank Pengirim (Koperasi)** | Biaya transfer dihitung dari master **BiayaTransfer** (bank pengirim + bank penerima) |
| 8 | Admin | Upload **bukti transfer**, **Setujui** | `POST admin/tabungan/pengajuan-tarik/{id}/approve` → `approveTarik()` |
| 9 | Sistem | Hitung **biaya transfer** (ditanggung nasabah); update pengajuan (status `2`, simpan `biaya_transfer`); buat **TransTabungan** dengan nominal = **nominal penarikan + biaya transfer** | Saldo nasabah berkurang (nominal + biaya); nasabah terima ke rekening = nominal saja |
| 10 | Nasabah | Cek **Detail Pengajuan Tarik** / **Detail Transaksi** | Bisa lihat biaya transfer admin & total didebet |

**Ringkas:**  
Nasabah ajukan penarikan transfer → PengajuanPenarikanTabungan (pending) → Admin pilih bank, upload bukti, approve → TransTabungan (PNR) = nominal + biaya → saldo turun, biaya tampil di detail.

---

### 2.2 Penarikan via Janji Temu (Tunai)

| Langkah | Pihak | Aksi | Keterangan |
|--------|--------|------|------------|
| 1 | Nasabah | Buka **Penarikan**, pilih **Tunai** | Form tunai: lokasi, tanggal, waktu janji temu |
| 2 | Nasabah | Isi nominal, lokasi, tanggal, waktu, **PIN**, submit | Validasi: saldo ≥ nominal |
| 3 | Sistem | Buat **PengajuanPenarikanTabungan** (metode_transfer = `tunai`, status `1`) **dan** **JanjiTemuTabungan** (jenis = `penarikan`, status `1`) | Satu pengajuan tarik + satu janji temu; penarikan tunai tidak di-approve di halaman Pengajuan Penarikan |
| 4 | Nasabah | Redirect ke **Status Janji Temu** | Pantau janji temu penarikan tunai |
| 5 | Admin | Buka **Janji Temu**, filter/lihat janji temu **Tabungan** jenis **penarikan** | Detail janji temu menampilkan jenis penarikan |
| 6 | Admin | Di detail janji temu, **Buat Transaksi** (nominal, bukti penerimaan, keterangan admin) | `createTransFromJanjiTemu()` dengan `jenis === 'penarikan'` |
| 7 | Sistem | Buat **TransTabungan** (PNR, via CS); link **id_pengajuan_tarik**; update **PengajuanPenarikanTabungan** status → `2`; update **JanjiTemuTabungan** status → `2` | Saldo nasabah berkurang; nasabah terima tunai di lokasi sesuai janji |
| 8 | Nasabah | Cek **Status Janji Temu** / **Detail transaksi** | Janji temu selesai, transaksi penarikan tunai tampil |

**Ringkas:**  
Nasabah ajukan penarikan tunai → PengajuanPenarikanTabungan + JanjiTemuTabungan (penarikan) → Admin di **Janji Temu** buat transaksi penarikan → TransTabungan (PNR, via CS), pengajuan tarik & janji temu diselesaikan.

---

## 3. Ringkasan Perbedaan Jalur

| Fitur | Data yang dibuat (Nasabah) | Diproses Admin di | Transaksi akhir |
|-------|----------------------------|--------------------|-----------------|
| **Setoran Transfer** | PengajuanTabungan + BuktiFoto | Tabungan → Pengajuan Setoran → Approve | TransTabungan STR (via TF) |
| **Setoran Janji Temu** | JanjiTemuTabungan (setoran) | Janji Temu → Detail → Buat Transaksi | TransTabungan STR (via CS) |
| **Penarikan Transfer** | PengajuanPenarikanTabungan | Tabungan → Pengajuan Penarikan → Approve + upload bukti | TransTabungan PNR (via TF), nominal = penarikan + biaya transfer |
| **Penarikan Janji Temu** | PengajuanPenarikanTabungan + JanjiTemuTabungan (penarikan) | Janji Temu → Detail → Buat Transaksi | TransTabungan PNR (via CS), pengajuan tarik di-update jadi disetujui |

---

## 4. Status & Tabel Penting

- **PengajuanTabungan:** status `1`=Pending, `2`=Disetujui, `3`=Ditolak. Hanya untuk **setoran transfer**.
- **PengajuanPenarikanTabungan:** status sama; untuk **semua penarikan** (transfer dan tunai); kolom `biaya_transfer` diisi saat approve **penarikan transfer**.
- **JanjiTemuTabungan:** status `1`=Menunggu, `2`=Selesai, `3`=Batal; kolom **jenis**: setoran (implisit) atau `penarikan`.
- **TransTabungan:** selalu ada setelah setoran/penarikan diproses; `id_pengajuan_setor`, `id_pengajuan_tarik`, atau `id_janji_temu_tabungan` mengaitkan ke sumbernya.

Setelah dibaca, flow ini bisa dipakai untuk onboarding tim atau dokumentasi teknis.
