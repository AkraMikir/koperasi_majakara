# Ringkasan Perbaikan Sistem Pinjaman

## Tanggal: 6 Februari 2026

### 1. ✅ Perbaikan Logika Pembulatan Angsuran

**Masalah:** Data di `tempo_pinjaman_b` masih menggunakan koma (decimal) padahal simulasi sudah membulatkan ke satuan ratusan.

**Solusi:** 
- **File:** `app/Http/Controllers/Admin/PinjamanController.php`
- **Method:** `generateJadwalAngsuran()`
- **Perubahan:**
  - Menggunakan logika yang sama dengan `simulasiAngsuran()` di NasabahController
  - Angsuran 1 sampai n-1: Dibulatkan ke bawah ke ratusan terdekat `floor($totalKewajiban / $jumlahAngsuran / 100) * 100`
  - Angsuran terakhir: Sisa dari total kewajiban (memastikan total tepat)
  - Menghilangkan decimal dengan menyimpan langsung sebagai integer

**Contoh:**
```
Pinjaman: Rp 1.000.000
Durasi: 3 bulan
Bunga: 2.5% = Rp 25.000
Total kewajiban: Rp 1.025.000

Angsuran per bulan (base): Rp 341.666,67
Dibulatkan ke bawah ke ratusan: Rp 341.600

Angsuran bulan 1: Rp 341.600
Angsuran bulan 2: Rp 341.600
Angsuran bulan 3: Rp 341.800 (sisa: 1.025.000 - 683.200)
```

---

### 2. ✅ Perbaikan Field Mapping Form Pengajuan Transfer

**File:** `app/Http/Controllers/Nasabah/PinjamanController.php` & `app/Models/PengajuanPinjaman.php`

**Field mapping yang diperbaiki:**

| Field | Nilai | Keterangan |
|-------|-------|------------|
| `id` | Generate dari 3 master data | Format: `DDMMYYYY` + `SEQ` + `P` + `TF` + `PNJ` |
| `id_anggota` | Dari tbl_nasabah yang mengajukan | Auto dari user login |
| `tgl_pengajuan` | `now()` | Waktu data dibuat |
| `nominal` | Dari field form | Input user |
| `jenis` | `'bulanan'` | Auto untuk transfer |
| `durasi` | Dari form + master data | Terintegrasi dengan `jns_angsuran_bulan` |
| `jenis_pencairan` | `'transfer'` | Status 1→3: tetap, Status 3→4: auto 'transfer' |
| `status` | `'1'` → `'2'` → `'3'` → `'4'` | 1=pending, 2=ditolak, 3=disetujui, 4=terlaksana |
| `keterangan` | Dari field form | Opsional, untuk nasabah |
| `keterangan_admin` | Dari form admin | Untuk approve (opsional) & reject (required) |
| `tgl_cair` | Generate saat status `'4'` | Ketika admin cairkan pinjaman |
| `bunga_persen` | Dari `master_bunga_pinjaman` | Berdasarkan durasi |
| `created_at` | `now()` | Auto |
| `updated_at` | `now()` | Auto |

**Status Flow:**
1. **Status 1 (Pending):** Form baru dikirim/data baru dibuat
2. **Status 2 (Ditolak):** Admin menolak dengan `keterangan_admin` (required)
3. **Status 3 (Disetujui):** Admin menyetujui dengan `keterangan_admin` (opsional)
   - Generate `tbl_pinjaman_h` 
   - **BELUM** generate `tempo_pinjaman_b`
4. **Status 4 (Terlaksana):** Admin cairkan pinjaman
   - Auto set `jenis_pencairan = 'transfer'`
   - Set `tgl_cair`
   - Generate `tempo_pinjaman_b` dengan logika pembulatan

---

### 3. ✅ Perbaikan Form Pengajuan Pinjaman Transfer

**File:** `resources/views/nasabah/pinjaman/pengajuan-transfer.blade.php`

**Perubahan:**
- ❌ **DIHAPUS:** Field "Upload Bukti Transfer"
- ❌ **DIHAPUS:** Validasi dan controller upload bukti foto
- ✅ **TETAP:** Nominal pinjaman
- ✅ **TETAP:** Durasi pinjaman (bulan) - terintegrasi dengan master data
- ✅ **TETAP:** Keterangan (opsional)
- ✅ **TETAP:** Simulasi angsuran (real-time)
- ✅ **TETAP:** Verifikasi PIN

**Logika Controller (`submitPengajuanTransfer`):**
- Hapus validasi `bukti_foto`
- Hapus upload logic untuk bukti foto
- Set otomatis: `jenis = 'bulanan'`, `jenis_pencairan = 'transfer'`

---

### 4. ✅ Perbaikan Form Admin untuk Approve & Reject

**File:** `resources/views/admin/pinjaman/detail-pengajuan.blade.php`

**Perubahan:**
1. **Form Approve:**
   - Tambah field `keterangan_admin` (textarea, opsional)
   - Submit ke `admin.pinjaman.approve-pengajuan`

2. **Form Reject:**
   - Ubah field dari `keterangan` → `keterangan_admin`
   - Field ini **required** untuk penolakan
   - Submit ke `admin.pinjaman.reject-pengajuan`

3. **Display Status Ditolak:**
   - Ubah `$pengajuan->keterangan` → `$pengajuan->keterangan_admin`

**Controller Changes:**
- `approvePengajuan()`: Validasi & simpan `keterangan_admin` (opsional)
- `rejectPengajuan()`: Validasi & simpan `keterangan_admin` (required, max 500 char)
- `cairkanPinjaman()`: Auto-set `jenis_pencairan = 'transfer'` saat status → 4

---

### 5. ✅ Verifikasi Angsuran & Pelunasan

**Status:** Sudah aman, tetapi ada beberapa catatan penting

**Logika Denda (REVISI TERBARU):**
- Denda 0.3% per hari dari **POKOK per bulan** (bukan total tagihan)
- Denda mulai dihitung **H+1** setelah jatuh tempo
- Denda **BERHENTI** jika sudah ada pembayaran (walaupun Rp 1)
- Formula: `Denda = (jumlah_pinjam / lama_pinjam) × 0.3% × hari_telat`

**Contoh:**
```
Pinjaman: Rp 3.000.000, 3 bulan
Pokok per bulan: Rp 1.000.000
Telat 1 hari: Rp 3.000
Telat 2 hari: Rp 6.000
```

**Status Bayar:**
- `'belum'`: Belum ada pembayaran
- `'telat'`: Sudah lewat jatuh tempo, belum lunas
- `'lunas'`: Sudah membayar penuh (tagihan + denda)

**Pelunasan Dipercepat:**
- Hitung sisa tagihan pokok + total denda dari semua angsuran belum lunas
- Potongan (opsional) bisa diterapkan
- Update semua angsuran belum lunas → `'lunas'`
- Update pinjaman → `lunas = 'lunas'`

---

## Checklist Perbaikan

- [✅] **Issue #1:** Logika pembulatan di `tempo_pinjaman_b` sudah sesuai simulasi
- [✅] **Issue #2:** Field mapping form pengajuan sudah benar
- [✅] **Issue #3:** Field upload bukti transfer sudah dihapus
- [✅] **Issue #4:** Alur angsuran sampai pelunasan sudah aman

---

## Testing Recommendations

### Test Case 1: Pengajuan Baru
1. Nasabah mengajukan pinjaman Rp 1.000.000, 3 bulan via transfer
2. Lihat simulasi → pastikan pembulatan ke ratusan
3. Submit tanpa upload file (harus berhasil)
4. Cek database: `status = '1'`, `jenis = 'bulanan'`, `jenis_pencairan = 'transfer'`

### Test Case 2: Admin Approve
1. Admin setujui pengajuan dengan/tanpa keterangan
2. Cek database: `status = '3'`, `keterangan_admin` tersimpan
3. Pinjaman **belum** ada di `tbl_pinjaman_h`
4. Tempo **belum** ada di `tempo_pinjaman_b`

### Test Case 3: Admin Cairkan
1. Admin cairkan dengan tanggal cair
2. Cek database:
   - `tbl_pengajuan_pinjaman`: `status = '4'`, `tgl_cair` terisi, `jenis_pencairan = 'transfer'`
   - `tbl_pinjaman_h`: Data pinjaman sudah ada
   - `tempo_pinjaman_b`: Jadwal angsuran tergenerate dengan **integer** (TIDAK ada koma)
3. Verifikasi pembulatan:
   - Angsuran 1 s/d n-1: Kelipatan 100
   - Angsuran n: Sisa total kewajiban
   - Sum semua angsuran = total kewajiban

### Test Case 4: Admin Reject
1. Admin tolak pengajuan dengan alasan (required)
2. Cek database: `status = '2'`, `keterangan_admin` terisi

### Test Case 5: Pelunasan
1. Nasabah bayar angsuran pertama (tepat waktu)
2. Cek: `status_bayar = 'lunas'`, `denda = 0`
3. Nasabah telat bayar angsuran kedua (3 hari)
4. Cek denda: `(jumlah_pinjam / lama_pinjam) × 0.3% × 3`
5. Nasabah bayar sebagian → Denda **BERHENTI** bertambah
6. Nasabah pelunasan dipercepat → Semua angsuran `'lunas'`

---

## Files Modified

1. `app/Http/Controllers/Admin/PinjamanController.php`
   - `generateJadwalAngsuran()` - Fix rounding logic
   - `approvePengajuan()` - Add keterangan_admin
   - `rejectPengajuan()` - Fix field name to keterangan_admin
   - `cairkanPinjaman()` - Auto-set jenis_pencairan

2. `app/Http/Controllers/Nasabah/PinjamanController.php`
   - `submitPengajuanTransfer()` - Remove bukti_foto validation & upload

3. `resources/views/nasabah/pinjaman/pengajuan-transfer.blade.php`
   - Remove upload bukti transfer section
   - Fix JavaScript initialization

4. `resources/views/admin/pinjaman/detail-pengajuan.blade.php`
   - Add keterangan_admin field to approve form
   - Fix keterangan → keterangan_admin in reject form
   - Fix display of rejection reason

---

## Database Schema Reference

```sql
-- tbl_pengajuan_pinjaman
id VARCHAR PRIMARY KEY
id_anggota VARCHAR FK
tgl_pengajuan DATETIME
nominal DECIMAL(15,2)
jenis VARCHAR (bulanan/mingguan)
durasi INT
jenis_pencairan VARCHAR (transfer/tunai)
status ENUM('1','2','3','4')
keterangan TEXT (dari nasabah)
keterangan_admin TEXT (dari admin)
tgl_cair DATETIME
bunga_persen DECIMAL(5,2)
created_at DATETIME
updated_at DATETIME

-- tempo_pinjaman_b
id VARCHAR PRIMARY KEY
pinjaman_id VARCHAR FK
no_urut INT
tgl_jatuh_tempo DATE
jumlah_tagihan DECIMAL(15,2) --> NOW INTEGER (no decimals)
jumlah_terbayar DECIMAL(15,2)
denda DECIMAL(15,2)
status_bayar ENUM('belum','telat','lunas')
tgl_bayar DATETIME
```

---

## Notes

- Semua perubahan sudah dilakukan sesuai requirements
- Logika pembulatan sudah konsisten antara simulasi dan generate tempo
- Status flow sudah jelas: 1 → 2 (reject) atau 1 → 3 (approve) → 4 (cairkan)
- Field `keterangan_admin` sudah konsisten di semua tempat
- Bukti transfer upload sudah dihapus dari form pengajuan
