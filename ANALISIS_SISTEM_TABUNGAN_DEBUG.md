# Analisis Sistem Tabungan (Nasabah & Admin) – Untuk Debugging

Dokumen ini memetakan seluruh alur, file, dan potensi bug pada modul tabungan (nasabah + admin) untuk keperluan debugging.

---

## 1. Peta Routes

### Nasabah (`/nasabah/tabungan/...`)

| Method | URI | Controller Method | Keterangan |
|--------|-----|-------------------|------------|
| GET | `/tabungan` | `index` | Dashboard tabungan, saldo, riwayat transaksi & janji temu |
| GET | `/tabungan/nabung-sekarang` | `nabungSekarang` | Halaman pilihan setoran (transfer / janji temu) |
| GET | `/tabungan/pengajuan-transfer` | `pengajuanTransfer` | Form pengajuan setoran via transfer |
| POST | `/tabungan/pengajuan-transfer` | `submitSetoran` | Submit setoran transfer (PIN, nominal, bukti foto) |
| GET | `/tabungan/penarikan` | `penarikanTabungan` | Halaman penarikan (tunai / transfer) |
| POST | `/tabungan/penarikan` | `submitPenarikan` | Submit pengajuan penarikan |
| GET | `/tabungan/janji-temu` | `janjiTemu` | Form janji temu setoran tunai |
| POST | `/tabungan/janji-temu` | `submitJanjiTemu` | Submit janji temu |
| POST | `/tabungan/verify-pin` | `verifyPin` | Verifikasi PIN (AJAX) |
| GET | `/tabungan/status-pengajuan-setor` | `statusPengajuanSetor` | Daftar status pengajuan setor |
| GET | `/tabungan/status-janji-temu` | `statusJanjiTemu` | Daftar status janji temu |
| GET | `/tabungan/status-pengajuan-tarik` | `statusPengajuanTarik` | Daftar status pengajuan tarik |
| GET | `/tabungan/pengajuan-setor/{id}` | `detailPengajuanSetor` | Detail pengajuan setor |
| GET | `/tabungan/pengajuan-tarik/{id}` | `detailPengajuanTarik` | Detail pengajuan tarik |
| GET | `/tabungan/transaksi/{id}` | `detailTransaksi` | Detail transaksi |
| GET | `/tabungan/janji-temu/{id}` | `detailJanjiTemu` | Detail janji temu |

### Admin (`/admin/tabungan/...`)

| Method | URI | Controller Method | Keterangan |
|--------|-----|-------------------|------------|
| GET | `/tabungan` | `index` | Dashboard tabungan admin |
| GET | `/tabungan/pengajuan-setor` | `pengajuanSetor` | Daftar pengajuan setor (filter status, search) |
| GET | `/tabungan/pengajuan-setor/{id}` | `detailPengajuanSetor` | Detail + approve/reject/edit/delete setor |
| POST | `/tabungan/pengajuan-setor/{id}/approve` | `approveSetor` | Setujui setor (buat transaksi) |
| POST | `/tabungan/pengajuan-setor/{id}/reject` | `rejectSetor` | Tolak setor |
| POST | `/tabungan/pengajuan-setor/{id}/edit` | `editPengajuanSetor` | Edit nominal/keterangan + setujui |
| DELETE | `/tabungan/pengajuan-setor/{id}` | `deletePengajuanSetor` | Hapus pengajuan (hanya pending, belum ada transaksi) |
| GET | `/tabungan/pengajuan-tarik` | `pengajuanTarik` | Daftar pengajuan tarik |
| GET | `/tabungan/pengajuan-tarik/{id}` | `detailPengajuanTarik` | Detail + approve/reject tarik (upload bukti TF) |
| POST | `/tabungan/pengajuan-tarik/{id}/approve` | `approveTarik` | Setujui tarik (buat transaksi) |
| POST | `/tabungan/pengajuan-tarik/{id}/reject` | `rejectTarik` | Tolak tarik |
| GET | `/tabungan/transaksi` | `transaksi` | Daftar transaksi (filter, search) |
| GET | `/tabungan/transaksi/create` | `createTransaksi` | Form buat transaksi manual |
| POST | `/tabungan/transaksi` | `storeTransaksi` | Simpan transaksi manual |
| GET | `/tabungan/transaksi/{id}` | `detailTransaksi` | Detail transaksi |
| GET | `/tabungan/transaksi/{id}/edit` | `editTransaksi` | Form edit transaksi (hanya manual) |
| PUT | `/tabungan/transaksi/{id}` | `updateTransaksi` | Update transaksi manual |
| DELETE | `/tabungan/transaksi/{id}` | `destroyTransaksi` | Hapus transaksi manual |
| GET | `/tabungan/janji-temu/{id}` | `detailJanjiTemu` | Detail janji temu |
| POST | `/tabungan/janji-temu/{id}/create-trans` | `createTransFromJanjiTemu` | Buat transaksi dari janji temu |
| GET | `/tabungan/saldo-nasabah` | `saldoNasabah` | Daftar saldo per nasabah |

---

## 2. Model & Relasi

### PengajuanTabungan (`tbl_pengajuan_tabungan`)
- **Fillable:** id, id_anggota, nominal, keterangan, keterangan_admin, status  
- **Relasi:** `nasabah`, `buktiFoto` (BuktiFoto, owner_id = id), `transTabungan`  
- **Catatan:** Tidak ada kolom `foto_bukti_tf`. Relasi `janjiTemu` sudah dihapus (janji temu terpisah).

### PengajuanPenarikanTabungan (`tbl_pengajuan_penarikan_tabungan`)
- **Fillable:** id, id_anggota, tgl_pengajuan, nominal, metode_transfer, no_rekening, nama_bank, foto_bukti_tf_admin, lokasi_temu, tanggal_janji_temu, waktu_janji_temu, keterangan, keterangan_admin, status  
- **Relasi:** `nasabah`, `lokasi`, `transTabungan`

### TransTabungan (`trans_tabungan`)
- **Fillable:** id, id_pengajuan_setor, id_janji_temu_tabungan, id_pengajuan_tarik, id_anggota, id_jns_via, id_jns_transaksi, nominal, keterangan, tgl_transaksi  
- **Relasi:** nasabah, pengajuanSetor, pengajuanTarik, janjiTemuTabungan, jnsVia, jnsTransaksi, buktiFoto (untuk transaksi manual / janji temu)  
- **Accessor:** `jenis` (setoran/penarikan dari jns_transaksi.kode), `via` (dari jns_via)

### JanjiTemuTabungan (`tbl_janji_temu_tabungan`)
- **Relasi:** nasabah, lokasi, buktiFoto (BuktiFoto, owner_id = id)  
- **Jenis:** setoran | penarikan (default setoran)

### BuktiFoto (`tbl_bukti_foto`)
- **Kolom:** owner_id, owner_fitur, owner_trans, file_path, keterangan  
- **Tidak ada kolom `nominal`** (sudah dipindah ke pengajuan).

---

## 3. Alur Data Penting

### Setoran via Transfer (Nasabah → Admin)
1. Nasabah: Form pengajuan transfer → nominal 1x, keterangan 1x, upload banyak file (tanpa nominal per file).  
2. Controller: `submitSetoran()` → buat `PengajuanTabungan` (nominal dari request), simpan file ke `BuktiFoto` (owner_id = id pengajuan, owner_fitur = 'T', owner_trans = 'STR').  
3. Admin: Detail pengajuan → tampilkan nominal dari `$pengajuan->nominal`, bukti dari `$pengajuan->buktiFoto`.  
4. Admin: Approve / Edit & Setujui → buat record di `trans_tabungan` (ID dari IdGenerator), status pengajuan = 2.

### Setoran via Janji Temu (Tunai)
1. Nasabah: Form janji temu → nominal, lokasi, tanggal, waktu.  
2. Controller: `submitJanjiTemu()` → buat `JanjiTemuTabungan` (tidak buat PengajuanTabungan).  
3. Admin: Menu janji temu (bukan dari menu pengajuan setor) → detail janji temu → form "Buat Transaksi" → upload foto penerimaan → simpan ke `BuktiFoto` (owner_id = id janji temu, owner_trans = 'JNJT'), buat `TransTabungan` dengan id_janji_temu_tabungan.

### Penarikan
1. Nasabah: Form penarikan → metode (tunai/transfer), nominal, jika transfer: bank + no rekening.  
2. Controller: `submitPenarikan()` → buat `PengajuanPenarikanTabungan`. Jika tunai, juga buat `JanjiTemuTabungan` (jenis = penarikan).  
3. Admin: Detail pengajuan tarik → jika transfer: upload bukti TF admin, pilih bank pengirim → approve → buat `TransTabungan`.  
4. Admin: Jika tunai: proses lewat detail janji temu → create trans dari janji temu (penarikan).

### Saldo
- Dihitung di controller: `getSaldoNasabah($idAnggota)` = total setoran (trans_tabungan STR) − total penarikan (trans_tabungan PNR).  
- Plus: pengajuan setor yang status = 2 (approved) tetapi belum punya transaksi (backward compatibility) → tambahkan nominal dari `pengajuan->nominal` (bukan dari bukti foto).

---

## 4. Bug & Inkonsistensi yang Ditemukan

### 4.1 DashboardController (Nasabah) – `pengajuanPending()`
- **Masalah 1:** `->with(['buktiFoto', 'janjiTemu'])` — relasi `janjiTemu` tidak ada di model `PengajuanTabungan` → error saat eager load.  
- **Masalah 2:** `$t->buktiFoto->sum('nominal')` — model `BuktiFoto` tidak punya kolom `nominal` → selalu 0 atau error.  
- **Masalah 3:** `$t->foto_bukti_tf` — kolom tidak ada di `tbl_pengajuan_tabungan` → undefined.  
- **Perbaikan:** Gunakan hanya `$t->nominal` untuk pengajuan setor. Hapus eager load `janjiTemu`. Tampilkan metode tetap "Transfer" (karena pengajuan setor saat ini hanya transfer).

### 4.2 Admin – `detail-pengajuan-setor.blade.php`
- **Masalah 1:** `$pengajuan->janjiTemu->nominal` dan `$pengajuan->janjiTemu` dipakai untuk nominal display dan section "Janji Temu". Relasi `janjiTemu` sudah dihapus dari model → error.  
- **Perbaikan:** Tampilkan hanya `$pengajuan->nominal`. Hapus atau sembunyikan blok "Janji Temu" untuk pengajuan setor (karena setoran transfer tidak punya janji temu di sini).

### 4.3 Admin – TabunganController `getSaldoNasabah()`
- **Masalah:** Ada baris redundan dan misleading: setelah `$nominal = $pengajuan->nominal ?? 0` ada lagi `$nominal = $pengajuan->nominal;` dan komentar "Jika nominal masih 0, coba ambil dari janji temu" tetapi tidak ada fallback ke janji temu.  
- **Perbaikan:** Gunakan hanya `$nominal = $pengajuan->nominal ?? 0` dan hapus duplikat.

### 4.4 Admin – TabunganController `updateTransaksi()`
- **Masalah:** Validasi saldo memakai `$request->jenis == 'penarikan'`, tetapi form edit tidak mengirim field `jenis` (hanya ditampilkan readonly).  
- **Perbaikan:** Gunakan `$transaksi->jenis == 'penarikan'` (dari record yang sedang diedit).

### 4.5 Edit Transaksi – View
- **Cek:** Form edit tidak kirim `jenis`; controller harus pakai `$transaksi->jenis`. Sudah dicatat di 4.4.

---

## 5. Checklist Debugging per Fitur

### Nasabah – Pengajuan Setoran Transfer
- [ ] Form: nominal 1x, keterangan 1x, multiple file upload.  
- [ ] PIN wajib dan valid.  
- [ ] Setelah submit: record di `tbl_pengajuan_tabungan` (nominal terisi), file di `tbl_bukti_foto` (owner_id = id pengajuan).  
- [ ] Redirect ke status pengajuan setor.

### Nasabah – Pengajuan Penarikan
- [ ] Validasi saldo sebelum submit.  
- [ ] Jika transfer: nama_bank, no_rekening wajib.  
- [ ] Jika tunai: lokasi, tanggal, waktu janji temu wajib; record di pengajuan penarikan + janji temu.

### Nasabah – Dashboard / Pengajuan Pending
- [ ] List pengajuan setor tidak error; nominal dari `$t->nominal`, bukan dari bukti foto.  
- [ ] Tidak memuat relasi `janjiTemu` pada PengajuanTabungan.

### Admin – Detail Pengajuan Setor
- [ ] Nominal tampil dari `$pengajuan->nominal`.  
- [ ] Tidak ada akses ke `$pengajuan->janjiTemu`.  
- [ ] Approve / Edit & Setujui membuat transaksi dengan nominal yang benar.

### Admin – Approve Penarikan (Transfer)
- [ ] Validasi foto_bukti_tf_admin dan bank_pengirim.  
- [ ] Cek saldo sebelum approve.  
- [ ] Transaksi terbuat dengan id_pengajuan_tarik.

### Admin – Transaksi Manual
- [ ] Create: validasi saldo untuk penarikan.  
- [ ] Edit/Delete: hanya untuk transaksi tanpa id_pengajuan_setor dan id_pengajuan_tarik.  
- [ ] Update: cek saldo menggunakan `$transaksi->jenis`, bukan `$request->jenis`.

### Saldo
- [ ] `getSaldoNasabah()` konsisten di Admin dan Nasabah.  
- [ ] Pengajuan approved tanpa transaksi: nominal dihitung dari `pengajuan->nominal`.

---

## 6. File Penting untuk Debug

| Area | File |
|------|------|
| Controller Nasabah | `app/Http/Controllers/Nasabah/TabunganController.php` |
| Controller Admin | `app/Http/Controllers/Admin/TabunganController.php` |
| Dashboard Nasabah (pengajuan pending) | `app/Http/Controllers/Nasabah/DashboardController.php` → `pengajuanPending()` |
| Model | `PengajuanTabungan`, `PengajuanPenarikanTabungan`, `TransTabungan`, `JanjiTemuTabungan`, `BuktiFoto` |
| View Admin Setor | `resources/views/admin/tabungan/detail-pengajuan-setor.blade.php` |
| View Admin Tarik | `resources/views/admin/tabungan/detail-pengajuan-tarik.blade.php` |
| View Nasabah Setor | `resources/views/nasabah/tabungan/pengajuan-transfer.blade.php` |
| View Nasabah Tarik | `resources/views/nasabah/tabungan/penarikan-tabungan.blade.php` |
| Routes | `routes/web.php` (group nasabah & admin tabungan) |

---

## 7. Ringkasan Perbaikan yang Disarankan

1. **DashboardController (Nasabah):** Hapus `janjiTemu` dari with(), gunakan `$t->nominal` untuk pengajuan setor, tampilkan metode "Transfer" tanpa pakai `foto_bukti_tf`.  
2. **detail-pengajuan-setor (Admin):** Hapus semua referensi `$pengajuan->janjiTemu`; nominal hanya dari `$pengajuan->nominal`; optional: sembunyikan/remove blok "Janji Temu" untuk halaman ini.  
3. **TabunganController (Admin):** Di `getSaldoNasabah()` hapus baris duplikat dan salah komentar. Di `updateTransaksi()` gunakan `$transaksi->jenis` untuk cek penarikan.

Setelah perbaikan di atas, jalankan lagi skenario: pengajuan setor, approve setor, pengajuan tarik, approve tarik, transaksi manual CRUD, dan cek saldo di kedua role.
