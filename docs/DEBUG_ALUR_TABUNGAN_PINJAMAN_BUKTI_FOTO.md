# Debug: Alur Tabungan, Pinjaman & Bukti Foto — Studi Kasus & Celah Error

Dokumen ini memetakan **celah error** dan **studi kasus bermasalah** untuk setiap alur di `ALUR_TABUNGAN_PINJAMAN_BUKTI_FOTO.md`, berdasarkan penelusuran kode (controller, migration, IdGenerator).

---

## Ringkasan Cepat

| Kategori | Jumlah | Prioritas |
|----------|--------|-----------|
| Bug / logic error | 4 | Tinggi |
| Kurang transaction (atomicity) | 5 | Tinggi |
| Validasi kurang / race | 6 | Sedang |
| Doc vs kode (konsistensi) | 2 | Rendah |
| Edge case / UX | 4 | Sedang |

---

## 1. Alur 1 — Pengajuan Setoran Tabungan Transfer

**Lokasi:** `Nasabah\TabunganController::submitSetoran`

### Celah error

1. **Operator precedence (BUG)**  
   - **Kode:** `if ($request->metode ?? 'transfer' === 'transfer')`  
   - **Masalah:** Di PHP, `===` lebih tinggi dari `??`, jadi dibaca: `$request->metode ?? ('transfer' === 'transfer')` → `$request->metode ?? true`.  
   - **Dampak:** Jika form kirim `metode=tunai`, kondisi tetap truthy (nilai `'tunai'`) dan blok setoran **transfer** tetap jalan. Bisa salah proses.  
   - **Perbaikan:** `if (($request->metode ?? 'transfer') === 'transfer')`

2. **Tanpa DB transaction**  
   - Create: `PengajuanTabungan` → lalu loop `BuktiFoto::create` + `store()`.  
   - Jika salah satu file gagal (store/DB) di tengah loop: pengajuan sudah tersimpan, sebagian bukti foto tersimpan, sebagian gagal → **data tidak konsisten**.  
   - **Perbaikan:** Bungkus dalam `DB::beginTransaction()` / `commit()` dan rollback + hapus file yang sudah di-store jika gagal.

3. **IdGenerator vs doc**  
   - Doc: ID pengajuan & bukti foto **T+TF+STR**.  
   - Kode: `IdGenerator::generate(..., 'T', 'T', 'STR')` → suffix **TSTR** (bukan TTFSTR).  
   - **Dampak:** Konsistensi penamaan/reporting; tidak mempengaruhi unikness (sequence per tabel+suffix).

---

## 2. Alur 2 — Approval Pengajuan Setoran Tabungan

**Lokasi:** `Admin\TabunganController::approveSetor`

### Celah error

1. **Tidak cek status pengajuan**  
   - Hanya cek: “belum ada transaksi” (`transTabungan->count() == 0`).  
   - **Masalah:** Jika status pengajuan pernah diubah kembali ke `1` (misal lewat edit), admin bisa “approve” lagi. Tidak buat transaksi duplikat (karena sudah ada), tapi status tetap di-update ke `2`.  
   - **Perbaikan:** Tambah guard: `if ($pengajuan->status !== '1') return ...`.

2. **Sudah pakai transaction**  
   - Ada `DB::beginTransaction()` / `commit()` / `rollBack()` — baik.

---

## 3. Alur 3 — Pengajuan Setoran Janji Temu Cash

**Lokasi:** `Nasabah\TabunganController::submitJanjiTemu`

### Celah error

1. **Validasi tanggal:** `after:today` → hari ini tidak boleh. Doc tidak menyebut; kalau bisnis mengizinkan “hari ini”, ganti jadi `after_or_equal:today`.

2. **Tidak ada transaction**  
   - Hanya satu insert `JanjiTemuTabungan::create`. Risiko kecil, tapi kalau nanti ada notifikasi/audit di sini, lebih aman pakai transaction.

---

## 4. Alur 4 — Approval Setoran Janji Temu Cash

**Lokasi:** `Admin\TabunganController::createTransFromJanjiTemu`

### Celah error

1. **Tanpa DB transaction**  
   - Urutan: (optional) simpan bukti foto → update janji temu status → (optional) update pengajuan tarik → insert `TransTabungan`.  
   - Jika `TransTabungan::create` gagal, janji temu dan (kalau penarikan) pengajuan tarik sudah di-update → **status tidak konsisten**.  
   - **Perbaikan:** Bungkus seluruh proses dalam satu transaction.

2. **Nominal dari input admin**  
   - Nominal diambil dari request (bisa diubah di form). Jika admin salah input, transaksi dan saldo salah.  
   - **Rekomendasi:** Untuk setoran/penarikan dari janji temu, nominal sebaiknya baca dari `$janjiTemu->nominal` (read-only di form), bukan dari input bebas.

---

## 5. Alur 5 — Pengajuan Penarikan Tabungan Transfer

**Lokasi:** `Nasabah\TabunganController::submitPenarikan`

### Celah error

1. **Race condition saldo**  
   - Saldo dihitung sebelum `DB::beginTransaction()`. Jika dua request penarikan bersamaan dan saldo pas-pasan, keduanya bisa lolos validasi saldo.  
   - **Perbaikan:** Cek saldo **di dalam** transaction, atau gunakan lock (e.g. `lockForUpdate()` pada row nasabah/aggregate saldo).

2. **Sudah pakai transaction**  
   - Create pengajuan + optional janji temu dalam satu transaction — baik.

---

## 6. Alur 6 — Approval Pengajuan Penarikan Transfer

**Lokasi:** `Admin\TabunganController::approveTarik`

### Celah error

1. **Tidak pakai DB transaction**  
   - Urutan: update pengajuan (status + foto) → create `TransTabungan`.  
   - Jika create transaksi gagal, pengajuan sudah “approved” (status 2) tapi tidak ada transaksi penarikan → **saldo tidak turun, data tidak konsisten**.  
   - **Perbaikan:** Bungkus update pengajuan + create transaksi dalam satu transaction.

2. **Tidak cek status pengajuan**  
   - Bisa approve lagi untuk pengajuan yang sudah status 2 → duplikat transaksi penarikan (dan saldo salah).  
   - **Perbaikan:** Guard `if ($pengajuan->status !== '1') return ...`.

3. **Race: saldo dihitung sebelum approve**  
   - Saldo dihitung sekali; sebelum create transaksi, nasabah bisa dapat setoran/penarikan lain. Untuk edge saldo pas-pasan, bisa double penarikan.  
   - **Rekomendasi:** Cek saldo lagi di dalam transaction (atau lock).

---

## 7–8. Alur 7 & 8 — Penarikan Tunai & Approval via Janji Temu

**Lokasi:** `submitPenarikan` (tunai) + `Admin\TabunganController::createTransFromJanjiTemu` (jenis penarikan)

### Celah error

1. **Matching pengajuan penarikan (Alur 8)**  
   - Pencocokan: `id_anggota` + `nominal` + `status = 1`, lalu `latest()`.  
   - **Masalah:** Jika satu nasabah punya dua pengajuan penarikan tunai pending dengan nominal sama, hanya satu yang di-match (yang terakhir by time). Yang lain tetap pending tanpa link ke transaksi.  
   - **Rekomendasi:** Doc bilang “linking saat approval by nominal dan status pending” — tambah aturan bisnis: misalnya satu janji temu = satu pengajuan penarikan, atau simpan `id_janji_temu` di pengajuan penarikan tunai agar match eksak.

2. **Nominal float**  
   - Match pakai `where('nominal', $nominal)`. Jika nominal dari form (parsed dari “Rp 10.000.000”) dan dari DB sedikit beda (float), bisa tidak ketemu.  
   - **Perbaikan:** Bandingkan dengan tolerance (atau simpan nominal integer sen/cents) atau round konsisten.

---

## 9. Alur 9 — Pengajuan Pinjaman Transfer

**Lokasi:** `Nasabah\PinjamanController::submitPengajuanTransfer`

### Celah error

1. **Tidak ada transaction**  
   - Satu insert pengajuan + notifikasi. Risiko kecil; untuk konsistensi bisa pakai transaction.

---

## 10–11. Alur 10 & 11 — Approval & Cairkan Pinjaman Transfer

**Lokasi:** `Admin\PinjamanController::approvePengajuan`, `cairkanPinjaman`

### Celah error

1. **approvePengajuan**  
   - Sudah cek status `1`, sudah cek belum punya pinjaman, pakai transaction — baik.

2. **cairkanPinjaman**  
   - Sudah cek status `3`, sudah cek ada pinjaman & belum ada tempo, pakai transaction — baik.

---

## 12–13. Alur 12 & 13 — Pinjaman Janji Temu Tunai

**Lokasi:** `submitJanjiTemuPinjaman`, `Admin\PinjamanController::prosesJanjiTemuPinjaman`

### Celah error

1. **prosesJanjiTemuPinjaman**  
   - Sudah cek status janji temu, id_pengajuan, pakai transaction — baik.

---

## 14. Alur 14 — Pengajuan Pembayaran Pinjaman Transfer

**Lokasi:** `Nasabah\PinjamanController::submitPembayaranTransfer`

### Celah error

1. **Tidak pakai transaction**  
   - Create pengajuan → (optional) loop bukti foto (store + BuktiFoto::create).  
   - Jika salah satu bukti gagal di tengah: pengajuan sudah ada, sebagian file dan baris bukti ada → **inkonsisten**.  
   - **Perbaikan:** Satu transaction; bila gagal, rollback dan hapus file yang sudah di-store.

2. **Validasi tempo_id vs pinjaman_id**  
   - Validasi: `tempo_id` ada di `tempo_pinjaman_b`, `pinjaman_id` ada di `tbl_pinjaman_h`.  
   - **Masalah:** Tidak dicek bahwa `tempo_id` itu angsuran **dari** `pinjaman_id` tersebut. User bisa kirim tempo dari pinjaman lain.  
   - **Perbaikan:**  
     `TempoPinjamanB::where('id', $request->tempo_id)->where('pinjaman_id', $request->pinjaman_id)->firstOrFail();`

---

## 15–16. Alur 15 & 16 — Approval & Konfirmasi Pembayaran Transfer

**Lokasi:** `approvePembayaran`, `konfirmasiPembayaran`

### Celah error

1. **approvePembayaran**  
   - Cek status, tempo, pinjaman, transaction — baik.

2. **konfirmasiPembayaran**  
   - Cek status harus `3`; update angsuran + optional bukti + status 4; transaction — baik.

---

## 17. Alur 17 — Pengajuan Pembayaran Tunai Janji Temu

**Lokasi:** `Nasabah\PinjamanController::submitJanjiTemuPembayaran`

### Celah error

1. **Validasi tempo_id vs pinjaman_id**  
   - Sama seperti Alur 14: perlu pastikan `tempo_id` milik `pinjaman_id` yang diajukan.  
   - **Perbaikan:** Validasi eksplisit `TempoPinjamanB::where('id', $request->tempo_id)->where('pinjaman_id', $request->pinjaman_id)->firstOrFail();`

2. **Tanggal janji temu**  
   - `after:today` → hari ini tidak valid. Sesuaikan dengan aturan bisnis (boleh/tidak boleh hari ini).

---

## 18. Alur 18 — Konfirmasi Pembayaran Tunai Upload Bukti

**Lokasi:** `Admin\PinjamanController::uploadSerahTerima`

### Celah error

1. **Deteksi “tunai”**  
   - `$isTunai = ($pengajuan->metode_pembayaran ?? '') === 'tunai' || (!$pengajuan->rekening_tujuan && $pengajuan->janjiTemu)`.  
   - Jika `metode_pembayaran` null tapi ada `janjiTemu`, dianggap tunai.  
   - **Risiko:** Pastikan semua pengajuan janji temu pembayaran selalu punya `metode_pembayaran = 'tunai'` agar konsisten.

2. **Sudah pakai transaction**  
   - Upload bukti → update angsuran → status 4 → update janji temu; dalam transaction — baik.

---

## Tabel Bukti Foto (tbl_bukti_foto)

1. **Panjang ID**  
   - Sudah diperbaiki: `id` dan `owner_id` VARCHAR(50) di migration create table (sesuai format IdGenerator).

2. **Orphan file**  
   - Jika pengajuan/janji temu dihapus, file di storage tidak otomatis terhapus.  
   - **Rekomendasi:** Saat hapus pengajuan/janji temu, hapus juga baris `tbl_bukti_foto` dan file di disk (seperti yang sudah dilakukan di `deletePengajuanSetor`).

---

## IdGenerator

1. **Pattern LIKE**  
   - `$pattern = $dateStr . '____' . $suffix` (4 underscore).  
   - Jika suatu saat ID punya format lain (misal 5 digit seq), pattern bisa tidak match.  
   - Saat ini format 4 digit konsisten — cukup didokumentasikan.

2. **Concurrent request**  
   - Dua request bersamaan bisa baca `max(id)` yang sama → duplicate sequence.  
   - **Rekomendasi:** Untuk lingkungan high concurrency, pertimbangkan lock (e.g. `SELECT ... FOR UPDATE`) atau sequence table terpusat.

---

## Checklist Perbaikan Prioritas

- [ ] **Tinggi:** Perbaiki operator precedence di `submitSetoran`: `($request->metode ?? 'transfer') === 'transfer'`.
- [ ] **Tinggi:** Tambah `DB::transaction` di: `submitSetoran`, `createTransFromJanjiTemu`, `approveTarik`, `submitPembayaranTransfer`.
- [ ] **Tinggi:** Guard status di `approveSetor` dan `approveTarik`: hanya proses jika status `1`.
- [ ] **Sedang:** Validasi `tempo_id` milik `pinjaman_id` di `submitPembayaranTransfer` dan `submitJanjiTemuPembayaran`.
- [ ] **Sedang:** Matching penarikan tunai (Alur 8): pastikan satu janji temu match satu pengajuan (atau dokumentasi + handling nominal float).
- [ ] **Rendah:** Selaraskan doc vs kode untuk IdGenerator setoran (T+TF+STR vs T+T+STR); atau ubah kode ke TF jika ingin seragam dengan master.

Setelah perbaikan, jalankan lagi skenario utama (setor transfer, approve setor, penarikan transfer/tunai, approval, pinjaman, pembayaran) dan cek konsistensi DB serta file bukti.
