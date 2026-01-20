# ANALISIS SISTEM PINJAMAN - UPDATE FITUR BARU

## 📋 RINGKASAN SISTEM SAAT INI

### Status Sistem Pinjaman Sebelum Update:
- ✅ Pengajuan pinjaman dasar (nominal, jenis, durasi)
- ✅ Approval & pembuatan pinjaman
- ✅ Generate jadwal angsuran otomatis
- ✅ Tracking pembayaran angsuran
- ✅ Status angsuran (lunas/belum/telat)
- ✅ Dashboard pinjaman (admin & nasabah)
- ✅ Perhitungan denda otomatis (sudah ditambahkan)
- ✅ Pelunasan dipercepat (sudah ditambahkan)

### Yang Belum Ada (Perlu Ditambahkan):
- 🔴 **Verifikasi PIN** untuk pengajuan pinjaman
- 🔴 **Jenis Pencairan** (transfer/tunai)
- 🔴 **Upload Bukti Transfer** oleh admin
- 🔴 **Janji Temu** untuk pencairan tunai
- 🔴 **Foto Serah Terima** untuk pencairan tunai
- 🔴 **Generate tempo** setelah upload bukti/pencairan (bukan saat approve)

---

## 🎯 FITUR BARU YANG AKAN DITAMBAHKAN

### 1. Pengajuan Pinjaman via Transfer (Dengan Verifikasi PIN)

**Alur:**
1. Nasabah isi form pengajuan → pilih jenis pencairan = **transfer**
2. Sistem munculkan popup **verifikasi PIN**
3. Jika PIN benar → simpan pengajuan dengan status '1' (menunggu)
4. Admin verifikasi & approve → status menjadi '3' (disetujui)
5. Admin **upload bukti transfer** → status menjadi '4' (terlaksana)
6. Sistem **generate jadwal angsuran** saat status menjadi '4'

### 2. Pengajuan Pinjaman via Tunai (Dengan Verifikasi PIN)

**Alur:**
1. Nasabah isi form pengajuan → pilih jenis pencairan = **cash**
2. Nasabah isi form **janji temu** (lokasi, tanggal, waktu)
3. Sistem munculkan popup **verifikasi PIN**
4. Jika PIN benar → simpan pengajuan + janji temu dengan status '1' (menunggu)
5. Admin verifikasi & approve → status menjadi '3' (disetujui)
6. Admin lakukan **pencairan tunai** → upload foto serah terima
7. Sistem update status menjadi '4' (terlaksana)
8. Sistem **generate jadwal angsuran** saat status menjadi '4'

---

## 📊 DATABASE CHANGES

### Migration 1: `add_jenis_pencairan_to_pengajuan_pinjaman`
```sql
ALTER TABLE tbl_pengajuan_pinjaman
ADD COLUMN jenis_pencairan ENUM('transfer', 'cash') DEFAULT 'transfer';

ALTER TABLE tbl_pinjaman_h
ADD COLUMN foto_bukti_transfer VARCHAR(255) NULL,
ADD COLUMN foto_serah_terima VARCHAR(255) NULL;
```

### Migration 2: `create_janji_temu_and_bukti_foto_pinjaman_tables`
```sql
CREATE TABLE tbl_janji_temu_pinjaman (
    id BIGINT PRIMARY KEY,
    id_pengajuan BIGINT FK,
    lokasi_temu BIGINT FK,
    nominal DECIMAL(15,2),
    tanggal_janji_temu DATETIME,
    waktu_janji_temu TIME,
    keterangan TEXT,
    timestamps
);

CREATE TABLE tbl_bukti_foto_pinjaman (
    id BIGINT PRIMARY KEY,
    id_pinjaman BIGINT FK NULL,
    id_pengajuan BIGINT FK NULL,
    file_photo VARCHAR(255),
    jenis ENUM('bukti_transfer', 'serah_terima'),
    keterangan TEXT,
    timestamps
);
```

---

## 🔄 PERUBAHAN ALUR APPROVAL

### Sebelum:
1. Admin approve → langsung buat pinjaman + generate tempo

### Sesudah:
1. Admin approve → buat pinjaman (status: 'pencairan'), **BELUM generate tempo**
2. Admin upload bukti transfer (transfer) atau foto serah terima (cash)
3. Sistem update status pengajuan menjadi '4' (terlaksana)
4. Sistem update status pinjaman menjadi 'telaksana'
5. **Sistem generate jadwal angsuran** saat status menjadi '4'

---

## ✅ IMPLEMENTASI YANG SUDAH DILAKUKAN

1. ✅ Migration untuk jenis_pencairan
2. ✅ Migration untuk janji_temu_pinjaman dan bukti_foto_pinjaman
3. ✅ Model JanjiTemuPinjaman dan BuktiFotoPinjaman
4. ✅ Update model PengajuanPinjaman (relationship)
5. ✅ Update model PinjamanH (field foto)
6. ✅ Update Nasabah Controller (verifikasi PIN + jenis pencairan)

---

## 📝 YANG MASIH PERLU DILAKUKAN

1. ⏳ Update view form pengajuan nasabah (tambah jenis pencairan + janji temu)
2. ⏳ Update Admin Controller (upload bukti transfer + pencairan tunai)
3. ⏳ Update view admin detail pengajuan (upload bukti + form pencairan)
4. ⏳ Update approval flow (generate tempo setelah pencairan)
5. ⏳ Update view status pengajuan nasabah (tampilkan jenis pencairan)

---

## 🎨 VIEW CHANGES

### Nasabah - Form Pengajuan:
- Tambah radio button: Transfer / Cash
- Conditional form janji temu (muncul jika pilih Cash)
- Modal verifikasi PIN sebelum submit
- AJAX verify PIN sebelum submit form

### Admin - Detail Pengajuan:
- Tampilkan jenis pencairan
- Jika transfer: Upload bukti transfer button
- Jika cash: Form pencairan tunai + upload foto serah terima
- Generate tempo hanya setelah upload bukti/pencairan

---

## 🔐 SECURITY

- PIN verification sebelum submit pengajuan
- PIN di-hash di database (sudah ada di User model)
- Validasi jenis pencairan required
- File upload validation (image, max size)

---

## 📌 NOTES

- Status pengajuan: '1'=Pending, '2'=Ditolak, '3'=Disetujui, '4'=Terlaksana
- Status pinjaman: 'menunggu', 'pencairan', 'telaksana'
- Generate tempo hanya dilakukan saat status pengajuan menjadi '4' (terlaksana)
- Foto bukti transfer disimpan di storage dan path disimpan di database
