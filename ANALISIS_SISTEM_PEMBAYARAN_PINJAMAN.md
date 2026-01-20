# ANALISIS SISTEM PEMBAYARAN PINJAMAN

## 📋 RINGKASAN

Sistem pembayaran pinjaman memungkinkan nasabah untuk melakukan pembayaran angsuran pinjaman melalui 2 metode:
1. **Via Transfer** - Nasabah upload bukti transfer, admin verifikasi dan konfirmasi
2. **Via Janji Temu (Cash)** - Nasabah buat janji temu, admin upload foto serah terima setelah pembayaran tunai

---

## 🗄️ DATABASE SCHEMA

### 1. `tbl_pengajuan_pembayaran_pinjaman`
**Tujuan**: Menyimpan pengajuan pembayaran pinjaman (transfer & cash)

| Field | Type | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| id_anggota | foreignId | FK ke tbl_nasabah |
| pinjaman_id | foreignId | FK ke tbl_pinjaman_h |
| tempo_id | bigint | ID angsuran (bulanan/mingguan) |
| jenis_tempo | enum | 'bulanan' atau 'mingguan' |
| nominal | decimal(15,2) | Nominal pembayaran |
| rekening_tujuan | string | Rekening tujuan (jika transfer) |
| keterangan | text | Keterangan |
| status | enum('1','2','3','4') | 1=Pending, 2=Ditolak, 3=Disetujui, 4=Terlaksana |
| tgl_pembayaran | datetime | Tanggal pembayaran (saat terlaksana) |
| timestamps | - | created_at, updated_at |

### 2. `tbl_janji_temu_pembayaran_pinjaman`
**Tujuan**: Menyimpan janji temu untuk pembayaran cash

| Field | Type | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| id_pengajuan | foreignId | FK ke tbl_pengajuan_pembayaran_pinjaman |
| lokasi_temu | foreignId | FK ke jns_lokasi_perusahaan |
| nominal | decimal(15,2) | Nominal pembayaran |
| tanggal_janji_temu | datetime | Tanggal janji temu |
| waktu_janji_temu | time | Waktu janji temu |
| keterangan | text | Keterangan |
| timestamps | - | created_at, updated_at |

### 3. `tbl_bukti_foto_pembayaran_pinjaman`
**Tujuan**: Menyimpan bukti foto pembayaran (transfer & serah terima)

| Field | Type | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| id_pengajuan | foreignId | FK ke tbl_pengajuan_pembayaran_pinjaman |
| file_photo | string | Path file foto |
| jenis | enum | 'bukti_transfer' atau 'serah_terima' |
| keterangan | text | Keterangan |
| timestamps | - | created_at, updated_at |

---

## 🔄 ALUR SISTEM

### 1. Pembayaran via Transfer

```
A. NASABAH:
1. Buka halaman Pembayaran Pinjaman
2. Pilih pinjaman aktif
3. Pilih angsuran yang akan dibayar
4. Pilih metode = Transfer
5. Isi rekening tujuan
6. Upload bukti transfer (multiple files)
7. Verifikasi PIN → Submit pengajuan

B. ADMIN:
1. Lihat pengajuan pembayaran (status: Pending)
2. Verifikasi data pembayaran:
   - Nominal
   - Rekening tujuan
   - Bukti transfer
3. Approve → Status menjadi 'Disetujui'
4. Verifikasi bukti transfer di bank
5. Konfirmasi pembayaran → Status menjadi 'Terlaksana'
6. Sistem auto-update angsuran (jumlah_terbayar, status_bayar)
7. Sistem cek apakah semua angsuran sudah lunas → update pinjaman
```

### 2. Pembayaran via Janji Temu (Cash)

```
A. NASABAH:
1. Buka halaman Pembayaran Pinjaman
2. Pilih pinjaman aktif
3. Pilih angsuran yang akan dibayar
4. Pilih metode = Cash (Janji Temu)
5. Isi form janji temu:
   - Pilih lokasi kantor
   - Tanggal janji temu
   - Waktu janji temu
6. Verifikasi PIN → Submit pengajuan

B. ADMIN:
1. Lihat pengajuan pembayaran (status: Pending)
2. Verifikasi data janji temu
3. Approve → Status menjadi 'Disetujui'
4. Konfirmasi jadwal dengan nasabah
5. Admin & nasabah bertemu sesuai jadwal
6. Admin terima pembayaran tunai
7. Admin upload foto serah terima → Status menjadi 'Terlaksana'
8. Sistem auto-update angsuran (jumlah_terbayar, status_bayar)
9. Sistem cek apakah semua angsuran sudah lunas → update pinjaman
```

---

## 📁 FILE YANG DIBUAT/DIUPDATE

### Migrations:
1. ✅ `2026_01_20_155604_create_pembayaran_pinjaman_tables.php`
   - Tabel pengajuan pembayaran pinjaman
   - Tabel janji temu pembayaran pinjaman
   - Tabel bukti foto pembayaran pinjaman

### Models:
1. ✅ `PengajuanPembayaranPinjaman.php`
2. ✅ `JanjiTemuPembayaranPinjaman.php`
3. ✅ `BuktiFotoPembayaranPinjaman.php`

### Controllers:
1. ✅ `Nasabah\PinjamanController.php`
   - Method: `pembayaran()` - Form pembayaran
   - Method: `submitPembayaranTransfer()` - Submit pembayaran via transfer
   - Method: `submitJanjiTemuPembayaran()` - Submit janji temu cash
   - Method: `statusPembayaran()` - Status pengajuan pembayaran
   - Method: `detailPembayaran()` - Detail pengajuan pembayaran

2. ✅ `Admin\PinjamanController.php`
   - Method: `pembayaran()` - List pengajuan pembayaran
   - Method: `detailPembayaran()` - Detail pengajuan pembayaran
   - Method: `approvePembayaran()` - Approve pengajuan pembayaran
   - Method: `rejectPembayaran()` - Reject pengajuan pembayaran
   - Method: `konfirmasiPembayaran()` - Konfirmasi pembayaran transfer
   - Method: `uploadSerahTerima()` - Upload foto serah terima (cash)

### Views:
1. ✅ `nasabah/pinjaman/pembayaran.blade.php` - Form pembayaran
2. ✅ `nasabah/pinjaman/status-pembayaran.blade.php` - Status pembayaran
3. ✅ `nasabah/pinjaman/detail-pembayaran.blade.php` - Detail pembayaran
4. ✅ `admin/pinjaman/pembayaran.blade.php` - List pengajuan pembayaran
5. ✅ `admin/pinjaman/detail-pembayaran.blade.php` - Detail & aksi pembayaran

### Routes:
1. ✅ Routes nasabah:
   - GET `/nasabah/pinjaman/pembayaran` - Form pembayaran
   - POST `/nasabah/pinjaman/pembayaran/transfer` - Submit transfer
   - POST `/nasabah/pinjaman/pembayaran/janji-temu` - Submit janji temu
   - GET `/nasabah/pinjaman/status-pembayaran` - Status pembayaran
   - GET `/nasabah/pinjaman/pembayaran/{id}` - Detail pembayaran

2. ✅ Routes admin:
   - GET `/admin/pinjaman/pembayaran` - List pembayaran
   - GET `/admin/pinjaman/pembayaran/{id}` - Detail pembayaran
   - POST `/admin/pinjaman/pembayaran/{id}/approve` - Approve
   - POST `/admin/pinjaman/pembayaran/{id}/reject` - Reject
   - POST `/admin/pinjaman/pembayaran/{id}/konfirmasi` - Konfirmasi transfer
   - POST `/admin/pinjaman/pembayaran/{id}/upload-serah-terima` - Upload foto cash

---

## ✅ FITUR YANG SUDAH DIIMPLEMENTASI

### Nasabah Side:
1. ✅ Form pembayaran dengan pilihan pinjaman & angsuran
2. ✅ Form pembayaran via transfer (upload bukti, rekening tujuan)
3. ✅ Form janji temu pembayaran cash (lokasi, tanggal, waktu)
4. ✅ Verifikasi PIN sebelum submit (AJAX)
5. ✅ Status pengajuan pembayaran (list & detail)
6. ✅ Tampilkan bukti foto transfer
7. ✅ Tampilkan informasi janji temu

### Admin Side:
1. ✅ List pengajuan pembayaran dengan filter status
2. ✅ Detail pengajuan pembayaran
3. ✅ Approve/reject pengajuan pembayaran
4. ✅ Konfirmasi pembayaran transfer (upload bukti tambahan)
5. ✅ Upload foto serah terima untuk cash
6. ✅ Auto-update angsuran setelah konfirmasi
7. ✅ Auto-update status pinjaman jika semua angsuran lunas

---

## 🔧 LOGIC PENTING

### Auto-Update Angsuran:
Saat admin konfirmasi pembayaran:
1. Hitung denda jika telat
2. Update `jumlah_terbayar` = jumlah_terbayar + nominal pembayaran
3. Update `denda` = hitung denda baru
4. Update `status_bayar`:
   - 'lunas' jika jumlah_terbayar >= tagihan + denda
   - 'telat' jika sudah lewat jatuh tempo & belum lunas
   - 'belum' jika belum lunas & belum telat
5. Update `tgl_bayar` = now() jika sudah ada pembayaran
6. Cek apakah semua angsuran sudah lunas → update pinjaman menjadi 'lunas'

### Perhitungan Denda:
```php
$hariTelat = now()->diffInDays($angsuran->tgl_jatuh_tempo, false);
$dendaPersen = $pinjaman->denda_persen ?? 0.02; // 2% per hari
$sisaTagihan = max(0, $angsuran->jumlah_tagihan - $angsuran->jumlah_terbayar);
$denda = $sisaTagihan * ($dendaPersen / 100) * $hariTelat;
$dendaMax = $angsuran->jumlah_tagihan * 0.5; // Max 50%
$denda = min($denda, $dendaMax);
```

---

## 🔐 SECURITY & VALIDASI

1. ✅ PIN verification sebelum submit (nasabah)
2. ✅ Validasi pinjaman belongs to nasabah
3. ✅ Validasi angsuran belongs to pinjaman
4. ✅ File upload validation (image, max 5MB)
5. ✅ Status validation (tidak bisa approve jika sudah terlaksana)
6. ✅ Database transaction untuk operasi critical

---

## 📌 STATUS PEMBAYARAN

- **'1' (Pending)**: Menunggu verifikasi admin
- **'2' (Ditolak)**: Ditolak oleh admin (dengan keterangan)
- **'3' (Disetujui)**: Disetujui, menunggu konfirmasi pembayaran
- **'4' (Terlaksana)**: Pembayaran sudah diterima & angsuran di-update

---

## 🎨 UI/UX FEATURES

1. ✅ Tabs untuk pilihan metode (Transfer / Cash)
2. ✅ Conditional form (janji temu muncul jika pilih cash)
3. ✅ Kalkulator denda otomatis di form
4. ✅ Preview bukti foto sebelum upload
5. ✅ Modal verifikasi PIN
6. ✅ Status badge dengan warna berbeda
7. ✅ Filter & search di list admin
8. ✅ Responsive design

---

## 📝 LANGKAH SETUP

1. **Jalankan Migration:**
   ```bash
   php artisan migrate
   ```

2. **Pastikan Storage Link:**
   ```bash
   php artisan storage:link
   ```

3. **Test Fitur:**
   - Login sebagai nasabah → Buat pengajuan pembayaran
   - Login sebagai admin → Verifikasi pembayaran
   - Test flow lengkap dari pengajuan hingga terlaksana

---

## ⚠️ CATATAN PENTING

1. **Field `tempo_id`**: Harus disimpan untuk tracking angsuran mana yang dibayar
2. **Field `jenis_tempo`**: Diperlukan untuk query angsuran yang benar (bulanan/mingguan)
3. **Perhitungan denda**: Otomatis dihitung saat konfirmasi pembayaran
4. **Multiple bukti foto**: Nasabah bisa upload multiple bukti transfer
5. **Foto serah terima**: Hanya untuk pembayaran cash, upload oleh admin

---

## 🔄 RELASI DATABASE

```
PengajuanPembayaranPinjaman
├── nasabah (belongsTo)
├── pinjaman (belongsTo)
├── janjiTemu (hasOne) - jika cash
└── buktiFoto (hasMany) - bukti transfer atau serah terima

JanjiTemuPembayaranPinjaman
├── pengajuan (belongsTo)
└── lokasi (belongsTo)

BuktiFotoPembayaranPinjaman
└── pengajuan (belongsTo)
```

---

## ✅ CHECKLIST IMPLEMENTASI

- [x] Migration database
- [x] Models dengan relationships
- [x] Controller nasabah (pembayaran)
- [x] Controller admin (verifikasi)
- [x] Views nasabah (form, status, detail)
- [x] Views admin (list, detail, aksi)
- [x] Routes (nasabah & admin)
- [x] Verifikasi PIN
- [x] Upload bukti transfer
- [x] Janji temu cash
- [x] Upload foto serah terima
- [x] Auto-update angsuran
- [x] Auto-update status pinjaman
- [x] Link menu di dashboard

---

## 🚀 SELESAI!

Sistem pembayaran pinjaman sudah lengkap dan siap digunakan. Semua fitur sudah diimplementasikan sesuai requirement.
