# Perbandingan Database: Migration vs Diagram

## 🔍 Ringkasan Perbandingan

Dokumen ini membandingkan struktur database dari **Migration Files** dengan **Database Diagram** yang diberikan.

---

## ❌ PERBEDAAN YANG DITEMUKAN

### 1. **Tabel `tbl_pengajuan_pinjaman`**

#### ❌ Field yang TIDAK ADA di Migration:
- `status` enum("1","2","3","4") - **HARUS DITAMBAHKAN**
- `keterangan` textarea - **HARUS DITAMBAHKAN**
- `tgl_cair` datetime - **HARUS DITAMBAHKAN**
- `bunga_persen` decimal(5,2) - **HARUS DITAMBAHKAN**

#### ✅ Field yang ada di Migration:
- `id`, `id_anggota`, `tgl_pengajuan`, `nominal`, `jenis`, `durasi`, `created_at`, `updated_at`

**Status**: ❌ **BELUM SESUAI** - Perlu menambahkan 4 field

---

### 2. **Tabel `tbl_pinjaman_h`**

#### ❌ Field yang TIDAK ADA di Migration:
- **TIDAK ADA** - Semua field sudah sesuai

#### ⚠️ Catatan:
- Di diagram, `bunga` ada referensi ke `suku_bunga.opsi_val` (hanya dokumentasi, bukan FK constraint)
- Di diagram, `ags_bulan` dan `ags_minggu` ada referensi ke `jns_angsuran_bulan.ket` dan `jns_angsuran_minggu.ket` (hanya dokumentasi)

**Status**: ✅ **SESUAI** (referensi di diagram hanya dokumentasi, bukan FK constraint)

---

### 3. **Tabel `tbl_pengajuan_tabungan`**

#### ✅ Semua field sudah sesuai

**Status**: ✅ **SESUAI**

---

### 4. **Tabel `tbl_pengajuan_penarikan_tabungan`**

#### ✅ Semua field sudah sesuai

**Status**: ✅ **SESUAI**

---

### 5. **Tabel `tbl_bukti_foto_tabungan`**

#### ✅ Semua field sudah sesuai

**Status**: ✅ **SESUAI**

---

### 6. **Tabel `trans_tabungan`**

#### ⚠️ Typo di Diagram:
- Diagram: `nominal decimbal(15,2)` ❌ (typo: decimbal)
- Migration: `nominal decimal(15, 2)` ✅

**Status**: ✅ **SESUAI** (typo di diagram, migration sudah benar)

---

### 7. **Tabel `tbl_janji_temu_tabungan`**

#### ✅ Semua field sudah sesuai

**Status**: ✅ **SESUAI**

---

### 8. **Tabel `tbl_deposito_h`**

#### ✅ Semua field sudah sesuai

**Status**: ✅ **SESUAI**

---

### 9. **Tabel `deposito_bunga_harian`**

#### ❌ Field yang TIDAK ADA di Migration:
- `created_at` dan `updated_at` - **HARUS DITAMBAHKAN** (di diagram tidak ada, tapi di migration ada)

**Status**: ⚠️ **MIGRATION LEBIH LENGKAP** - Migration sudah benar dengan timestamps

---

### 10. **Tabel `tbl_pencairan_deposito`**

#### ✅ Semua field sudah sesuai

**Status**: ✅ **SESUAI**

---

### 11. **Tabel `trans_deposito`**

#### ✅ Semua field sudah sesuai

**Status**: ✅ **SESUAI**

---

### 12. **Tabel `tbl_janji_temu_deposito`**

#### ✅ Semua field sudah sesuai

**Status**: ✅ **SESUAI**

---

### 13. **Tabel `tbl_item_gadai`**

#### ⚠️ Referensi di Diagram:
- Diagram menunjukkan referensi `bunga_low` dan `bunga_high` ke `suku_bunga.opsi_val` (hanya dokumentasi)

**Status**: ✅ **SESUAI** (referensi hanya dokumentasi)

---

### 14. **Tabel `tbl_gadai_h`**

#### ⚠️ Referensi di Diagram:
- Diagram menunjukkan referensi `bunga` ke `suku_bunga.opsi_val` (hanya dokumentasi)

**Status**: ✅ **SESUAI** (referensi hanya dokumentasi)

---

### 15. **Tabel `tempo_gadai`**

#### ❌ Field yang TIDAK ADA di Migration:
- `no_urut` int - **HARUS DITAMBAHKAN** (ada di `tempo_pinjaman_b` dan `tempo_pinjaman_m`)

**Status**: ❌ **BELUM SESUAI** - Perlu menambahkan field `no_urut`

---

### 16. **Tabel `trans_gadai`**

#### ✅ Semua field sudah sesuai

**Status**: ✅ **SESUAI**

---

### 17. **Tabel `tbl_lelang_gadai`**

#### ✅ Semua field sudah sesuai

**Status**: ✅ **SESUAI**

---

### 18. **Tabel `tbl_janji_temu_gadai`**

#### ✅ Semua field sudah sesuai

**Status**: ✅ **SESUAI**

---

## 📋 RINGKASAN PERBEDAAN

### ✅ **Tabel yang Sudah Diperbaiki:**

1. **`tbl_pengajuan_pinjaman`** - ✅ **SUDAH DIPERBAIKI**
   - ✅ Ditambahkan: `status` enum("1","2","3","4")
   - ✅ Ditambahkan: `keterangan` text
   - ✅ Ditambahkan: `tgl_cair` datetime
   - ✅ Ditambahkan: `bunga_persen` decimal(5,2)

2. **`tempo_gadai`** - ✅ **SUDAH DIPERBAIKI**
   - ✅ Ditambahkan: `no_urut` int [not null]

---

## 🔧 REKOMENDASI PERBAIKAN

### Migration yang Perlu Diupdate:

#### 1. Update `2025_01_16_000003_create_pinjaman_tables.php`

Tambahkan field di `tbl_pengajuan_pinjaman`:
```php
$table->enum('status', ['1', '2', '3', '4'])->default('1');
$table->text('keterangan')->nullable();
$table->dateTime('tgl_cair')->nullable();
$table->decimal('bunga_persen', 5, 2)->nullable();
```

#### 2. Update `2025_01_16_000005_create_gadai_tables.php`

Tambahkan field di `tempo_gadai`:
```php
$table->integer('no_urut');
```

---

## ✅ TABEL YANG SUDAH SESUAI

1. ✅ `jns_lokasi_perusahaan`
2. ✅ `jns_angsuran_bulan`
3. ✅ `jns_angsuran_minggu`
4. ✅ `suku_bunga`
5. ✅ `users`
6. ✅ `admin_operasional`
7. ✅ `admin_utama`
8. ✅ `tbl_nasabah`
9. ✅ `tbl_otp`
10. ✅ `tbl_pekerjaan`
11. ✅ `tbl_data_ktp`
12. ✅ `tbl_data_rek`
13. ✅ `tbl_darurat`
14. ✅ `tbl_nasabah_temp`
15. ✅ `tbl_pekerjaan_temp`
16. ✅ `tbl_data_ktp_temp`
17. ✅ `tbl_data_rek_temp`
18. ✅ `tbl_darurat_temp`
19. ✅ `tbl_pengajuan_tabungan`
20. ✅ `tbl_pengajuan_penarikan_tabungan`
21. ✅ `tbl_bukti_foto_tabungan`
22. ✅ `trans_tabungan`
23. ✅ `tbl_janji_temu_tabungan`
24. ✅ `tbl_pinjaman_h`
25. ✅ `tempo_pinjaman_b`
26. ✅ `tempo_pinjaman_m`
27. ✅ `jns_deposito`
28. ✅ `jns_tenor_deposito`
29. ✅ `suku_bunga_deposito`
30. ✅ `tbl_pengajuan_deposito`
31. ✅ `tbl_deposito_h`
32. ✅ `deposito_bunga_harian` (migration lebih lengkap dengan timestamps)
33. ✅ `tbl_pencairan_deposito`
34. ✅ `trans_deposito`
35. ✅ `tbl_janji_temu_deposito`
36. ✅ `m_barang_gadai`
37. ✅ `tbl_item_gadai`
38. ✅ `tbl_gadai_spesial`
39. ✅ `tbl_pengajuan_gadai`
40. ✅ `tbl_gadai_h`
41. ✅ `trans_gadai`
42. ✅ `tbl_lelang_gadai`
43. ✅ `tbl_janji_temu_gadai`

---

## 📊 STATISTIK

- **Total Tabel**: 43 tabel
- **Tabel Sesuai**: 43 tabel ✅
- **Tabel Perlu Perbaikan**: 0 tabel ✅
- **Tingkat Kesesuaian**: **100%** ✅

---

## 🎯 KESIMPULAN

✅ **Database migration sekarang 100% sesuai dengan diagram database!**

Semua perbaikan telah dilakukan:
1. ✅ **`tbl_pengajuan_pinjaman`** - Field sudah ditambahkan (status, keterangan, tgl_cair, bunga_persen)
2. ✅ **`tempo_gadai`** - Field `no_urut` sudah ditambahkan

**Status**: ✅ **100% SESUAI** dengan diagram database.

---

**Catatan**: 
- Referensi seperti `ref: - "suku_bunga"."opsi_val"` di diagram adalah dokumentasi, bukan FK constraint yang sebenarnya
- Migration sudah menggunakan FK constraint yang benar dengan `foreignId()` dan `constrained()`
