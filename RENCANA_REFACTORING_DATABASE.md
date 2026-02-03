# 🚀 RENCANA REFACTORING DATABASE - SISTEM TABUNGAN & PINJAMAN

> 📅 **Tanggal:** 3 Februari 2026  
> ⚠️ **WARNING:** Ini adalah refactoring BESAR yang akan mengubah struktur database fundamental  
> 🎯 **Tujuan:** Migrasi ke sistem ID Complex + Tabel Foto Universal

---

## 📋 CHECKLIST TAHAPAN

### ✅ TAHAP 1: PERSIAPAN & BACKUP
- [ ] Backup database lengkap
- [ ] Dokumentasi struktur lama
- [ ] Buat branch baru di git
- [ ] List semua controller & view yang terpengaruh

### ✅ TAHAP 2: DROP TABLES LAMA
**Tables yang akan DIHAPUS:**
1. `jns_akun`
2. `jns_deposito`
3. `suku_bunga`
4. `tbl_bukti_foto_pembayaran_pinjaman`
5. `tbl_bukti_foto_pinjaman`
6. `tbl_bukti_foto_tabungan`

### ✅ TAHAP 3: CREATE MASTER TABLES BARU
1. `jns_fitur` (T, P, D, G)
2. `jns_via` (T, C)
3. `jns_transaksi` (STR, PNR, PMB, PNJ, PCR, TRKT, dll)
4. `tbl_bukti_foto` (universal)

### ✅ TAHAP 4: MODIFY EXISTING TABLES
**Tables yang diubah:**
1. `tbl_pengajuan_tabungan` - ubah PK jadi VARCHAR, hapus kolom, tambah kolom
2. `trans_tabungan` - ubah PK, hapus kolom redundan
3. `tbl_pengajuan_penarikan_tabungan` - ubah PK
4. `tbl_pengajuan_pinjaman` - ubah PK, hapus kolom
5. `tbl_pinjaman_h` - ubah PK, hapus kolom
6. `tempo_pinjaman_b` - ubah PK, hapus kolom
7. `tbl_pengajuan_pembayaran_pinjaman` - ubah PK
8. **SEMUA** `tbl_janji_temu_*` - tambah kolom id_nasabah, keterangan

### ✅ TAHAP 5: CREATE VIEW
- `v_janji_temu_universal` - union semua janji temu

### ✅ TAHAP 6: UPDATE MODELS
- Update semua model yang terpengaruh
- Update fillable, casts, relationships

### ✅ TAHAP 7: CREATE HELPERS
- Helper untuk generate ID Complex
- Helper untuk relasi tbl_bukti_foto

### ✅ TAHAP 8: UPDATE CONTROLLERS
- Nasabah\TabunganController
- Admin\TabunganController
- Nasabah\PinjamanController
- Admin\PinjamanController

### ✅ TAHAP 9: UPDATE VIEWS
- Semua view tabungan (nasabah & admin)
- Semua view pinjaman (nasabah & admin)
- Buat view universal janji temu

### ✅ TAHAP 10: TESTING
- Test setiap flow end-to-end
- Test edge cases
- Validasi data integrity

---

## 🔍 DETAIL PERUBAHAN

### 1. FORMAT ID COMPLEX

**Pattern:** `{SEQ}{DDMMYYYY}{FITUR}{VIA}{TRANS}`

**Contoh:**
```
300120260001TTSTR   → Pengajuan Tabungan Transfer Setoran
                      30 (seq) 01202600 (tanggal) T (tabungan) T (transfer) STR (setoran)

300120260010PTPMB   → Pengajuan Pembayaran Pinjaman Transfer
                      30 (seq) 01202600 (tanggal) P (pinjaman) T (transfer) PMB (pembayaran)

30012026000...TTTRKT → Transaksi Tabungan Transfer
                      T (tabungan) T (transfer) TRKT (transaksi tabungan)
```

**Komponen:**
- `SEQ`: Sequence 2-3 digit (auto increment per hari)
- `DDMMYYYY`: Tanggal 8 digit
- `FITUR`: 1 char (T/P/D/G)
- `VIA`: 1 char (T/C)
- `TRANS`: 3-5 char (STR, PNR, PMB, TRKT, dll)

---

### 2. MASTER DATA BARU

#### `jns_fitur`
| id | kode | nama | deskripsi |
|----|------|------|-----------|
| 1 | T | Tabungan | Fitur tabungan |
| 2 | P | Pinjaman | Fitur pinjaman |
| 3 | D | Deposito | Fitur deposito |
| 4 | G | Gadai | Fitur gadai |

#### `jns_via`
| id | kode | nama | deskripsi |
|----|------|------|-----------|
| 1 | T | Transfer | Via transfer bank |
| 2 | C | Cash | Via tunai/cash |

#### `jns_transaksi`
| id | kode | nama | deskripsi |
|----|------|------|-----------|
| 1 | STR | Setoran | Setoran tabungan |
| 2 | PNR | Penarikan | Penarikan tabungan |
| 3 | TRKT | Transaksi Tabungan | Transaksi tabungan final |
| 4 | PNJ | Pengajuan Pinjaman | Pengajuan pinjaman |
| 5 | PMB | Pembayaran | Pembayaran angsuran |
| 6 | DPNJM | Data Pinjaman | Header pinjaman |
| 7 | TPNJM | Tempo Pinjaman | Jadwal angsuran |

---

### 3. TABEL FOTO UNIVERSAL

#### `tbl_bukti_foto`
```sql
CREATE TABLE tbl_bukti_foto (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    owner_id VARCHAR(30) NOT NULL,           -- ID dari table manapun
    owner_fitur CHAR(1) NOT NULL,            -- T, P, D, G
    owner_trans VARCHAR(10) NOT NULL,        -- STR, PNR, PMB, dll
    file_path VARCHAR(255) NOT NULL,         -- Path file
    keterangan VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_owner (owner_id),
    INDEX idx_owner_type (owner_fitur, owner_trans)
);
```

**Cara Pakai:**
```php
// Contoh: Simpan foto untuk pengajuan tabungan
$pengajuanId = '300120260001TTSTR';
BuktiFoto::create([
    'owner_id' => $pengajuanId,
    'owner_fitur' => 'T',
    'owner_trans' => 'STR',
    'file_path' => 'bukti/foto123.jpg',
    'keterangan' => 'Bukti transfer BCA'
]);

// Query foto
$fotos = BuktiFoto::where('owner_id', $pengajuanId)->get();
```

---

### 4. PERUBAHAN TABLE DETAIL

#### `tbl_pengajuan_tabungan`
**BEFORE:**
```
id (bigint auto_increment)
id_anggota
nominal
foto_bukti_tf (varchar - 'transfer'/'tunai')
keterangan
status
```

**AFTER:**
```
id (VARCHAR(30) PRIMARY - format: 300120260001TTSTR)
id_anggota
nominal
keterangan
keterangan_admin (TEXT - BARU)
status (1,2,3)
created_at
updated_at

HAPUS: foto_bukti_tf (diganti dengan tbl_bukti_foto)
```

#### `trans_tabungan`
**BEFORE:**
```
id (bigint)
id_transaksi (varchar - YYYYMMDD-SEQ-TAB)
id_pengajuan_setor
id_pengajuan_tarik
id_anggota
id_jns_akun (FK ke jns_akun)
nominal
keterangan
jenis (enum: setoran/penarikan)
via (enum: transfer/cash)
tgl_transaksi
```

**AFTER:**
```
id (VARCHAR(30) - format: 30012026000...TTTRKT)
id_pengajuan_setor (VARCHAR(30))
id_pengajuan_tarik (VARCHAR(30))
id_anggota
nominal
keterangan
id_jns_fitur (FK ke jns_fitur - BARU)
id_jns_via (FK ke jns_via - BARU)
id_jns_transaksi (FK ke jns_transaksi - BARU)
tgl_transaksi
created_at
updated_at

HAPUS: id_transaksi (sudah ada di id)
HAPUS: id_jns_akun (diganti jns_fitur)
HAPUS: jenis (redundant, ada di id_jns_transaksi)
HAPUS: via (redundant, ada di id_jns_via)
```

#### `tbl_pengajuan_pinjaman`
**BEFORE:**
```
id (bigint)
id_anggota
tgl_pengajuan
nominal
jenis (bulanan/mingguan)
durasi
jenis_pencairan (transfer/cash)
status (1,2,3,4)
keterangan
tgl_cair
bunga_persen
```

**AFTER:**
```
id (VARCHAR(30) - format: 300120260001PTPNJ)
id_anggota
tgl_pengajuan
nominal
jenis (bulanan - auto)
durasi
status (1,2,3,4)
keterangan
keterangan_admin (TEXT - BARU)
tgl_cair
bunga_persen
created_at
updated_at

HAPUS: jenis_pencairan (sudah ada di ID)
```

#### `tbl_pinjaman_h`
**BEFORE:**
```
id (bigint)
id_anggota
id_pengajuan
jumlah_pinjam
lama_pinjam
jenis
bunga
bunga_rp
denda_persen
ags_bulan (TIDAK DIPAKAI)
ags_minggu (TIDAK DIPAKAI)
tgl_pinjam
saldo_lebih (TIDAK DIPAKAI)
foto_bukti_transfer
foto_serah_terima
status (pencairan/telaksana)
lunas (belum/lunas)
```

**AFTER:**
```
id (VARCHAR(30) - format: 30012026000...PTDPNJM)
id_anggota
id_pengajuan (VARCHAR(30))
jumlah_pinjam
lama_pinjam
jenis (bulanan)
bunga
bunga_rp
denda_persen
tgl_pinjam
lunas (belum/lunas)
created_at
updated_at

HAPUS: ags_bulan
HAPUS: ags_minggu
HAPUS: saldo_lebih
HAPUS: foto_bukti_transfer (pindah ke tbl_bukti_foto)
HAPUS: foto_serah_terima (pindah ke tbl_bukti_foto)
HAPUS: status (selalu telaksana)
```

#### `tempo_pinjaman_b`
**BEFORE:**
```
id (bigint)
pinjaman_id (bigint)
anggota_id (bigint)
no_urut
tgl_jatuh_tempo
jumlah_tagihan
jumlah_terbayar
denda
tgl_bayar
status_bayar
```

**AFTER:**
```
id (VARCHAR(30) - format: 30012026000...PTTPNJM)
pinjaman_id (VARCHAR(30))
no_urut
tgl_jatuh_tempo
jumlah_tagihan
jumlah_terbayar
denda
tgl_bayar
status_bayar
created_at
updated_at

HAPUS: anggota_id (bisa diambil dari pinjaman_id)
```

#### `tbl_pengajuan_pembayaran_pinjaman`
**BEFORE:**
```
id (bigint)
id_anggota
pinjaman_id (bigint)
tempo_id (bigint)
jenis_tempo
nominal
rekening_tujuan
keterangan
status
tgl_pembayaran
```

**AFTER:**
```
id (VARCHAR(30) - format: 300120260001PTPMB)
id_anggota
pinjaman_id (VARCHAR(30))
tempo_id (VARCHAR(30) - FK ke tempo_pinjaman_b)
jenis_tempo
nominal
rekening_tujuan
keterangan
keterangan_admin (TEXT - BARU)
status (1,2,3)
tgl_pembayaran
created_at
updated_at
```

---

### 5. JANJI TEMU TABLES

**Semua table janji temu ditambah:**
- `id_nasabah` (bigint unsigned - FK ke tbl_nasabah)
- `keterangan` (text - jika belum ada)

**Tables:**
1. `tbl_janji_temu_tabungan`
2. `tbl_janji_temu_pinjaman`
3. `tbl_janji_temu_pembayaran_pinjaman`
4. `tbl_janji_temu_deposito`
5. `tbl_janji_temu_gadai`

---

### 6. VIEW UNIVERSAL JANJI TEMU

```sql
CREATE VIEW v_janji_temu_universal AS
SELECT 
    'TABUNGAN' as fitur,
    jt.id,
    jt.id_pengajuan as referensi_id,
    jt.id_nasabah,
    n.nama_lengkap,
    u.email,
    u.no_hp,
    jt.lokasi_temu,
    l.nama_lokasi,
    jt.nominal,
    jt.tanggal_janji_temu,
    jt.waktu_janji_temu,
    COALESCE(jt.keterangan, '') as keterangan,
    jt.created_at,
    jt.updated_at
FROM tbl_janji_temu_tabungan jt
JOIN tbl_pengajuan_tabungan p ON jt.id_pengajuan = p.id
JOIN tbl_nasabah n ON jt.id_nasabah = n.id
JOIN users u ON n.id_user = u.id
JOIN jns_lokasi_perusahaan l ON jt.lokasi_temu = l.id

UNION ALL

SELECT 
    'PINJAMAN',
    jt.id,
    jt.id_pengajuan,
    jt.id_nasabah,
    n.nama_lengkap,
    u.email,
    u.no_hp,
    jt.lokasi_temu,
    l.nama_lokasi,
    jt.nominal,
    jt.tanggal_janji_temu,
    jt.waktu_janji_temu,
    jt.keterangan,
    jt.created_at,
    jt.updated_at
FROM tbl_janji_temu_pinjaman jt
JOIN tbl_pengajuan_pinjaman p ON jt.id_pengajuan = p.id
JOIN tbl_nasabah n ON jt.id_nasabah = n.id
JOIN users u ON n.id_user = u.id
JOIN jns_lokasi_perusahaan l ON jt.lokasi_temu = l.id

UNION ALL

SELECT 
    'PEMBAYARAN PINJAMAN',
    jt.id,
    jt.id_pengajuan,
    jt.id_nasabah,
    n.nama_lengkap,
    u.email,
    u.no_hp,
    jt.lokasi_temu,
    l.nama_lokasi,
    jt.nominal,
    jt.tanggal_janji_temu,
    jt.waktu_janji_temu,
    jt.keterangan,
    jt.created_at,
    jt.updated_at
FROM tbl_janji_temu_pembayaran_pinjaman jt
JOIN tbl_pengajuan_pembayaran_pinjaman p ON jt.id_pengajuan = p.id
JOIN tbl_nasabah n ON jt.id_nasabah = n.id
JOIN users u ON n.id_user = u.id
JOIN jns_lokasi_perusahaan l ON jt.lokasi_temu = l.id

ORDER BY tanggal_janji_temu DESC, created_at DESC;
```

---

## 🛠️ HELPER FUNCTIONS

### Generate ID Complex

```php
// app/Helpers/IdGenerator.php
class IdGenerator 
{
    public static function generate($fitur, $via, $trans)
    {
        // Ambil sequence hari ini
        $date = now()->format('dmY');
        $seq = self::getNextSequence($fitur, $trans, $date);
        
        // Format: {SEQ}{DDMMYYYY}{FITUR}{VIA}{TRANS}
        return str_pad($seq, 2, '0', STR_PAD_LEFT) . $date . $fitur . $via . $trans;
    }
    
    private static function getNextSequence($fitur, $trans, $date)
    {
        // Logic untuk auto increment sequence per hari
        // ... implementasi ...
    }
}
```

---

## ⚠️ RESIKO & MITIGASI

### RESIKO TINGGI:
1. **Data Loss** - Migrasi PK dari bigint ke varchar
   - **Mitigasi:** BACKUP LENGKAP, test di staging dulu

2. **Breaking Changes** - Semua FK berubah
   - **Mitigasi:** Update semua relations di model

3. **Performance** - VARCHAR PK lebih lambat dari bigint
   - **Mitigasi:** Proper indexing, monitor performance

4. **ID Generation** - Collision di concurrent request
   - **Mitigasi:** Use database transaction + lock

### RESIKO RENDAH:
1. Views crash - query lama error
2. Upload foto fail - path handling berubah
3. Controller error - missing columns

---

## 📝 CATATAN PENTING

1. **WAJIB BACKUP** database sebelum mulai
2. **TESTING MENYELURUH** di setiap tahap
3. **MIGRATIONS** harus reversible (ada rollback)
4. **SEQUENTIAL** - jangan skip tahap
5. **DOKUMENTASI** setiap perubahan

---

## 🎯 REKOMENDASI

**OPSI 1: Migrasi Bertahap (RECOMMENDED)**
- Tahap 1: Create table baru, keep table lama
- Tahap 2: Dual write (tulis ke keduanya)
- Tahap 3: Migrate data lama ke baru
- Tahap 4: Switch read ke table baru
- Tahap 5: Drop table lama

**OPSI 2: Migrasi Langsung (RISKY)**
- Backup → Drop → Create → Migrate → Test
- Lebih cepat tapi risiko tinggi

**PILIHAN:** OPSI 1 lebih aman untuk production

---

📅 **Dokumen dibuat:** 3 Februari 2026  
🔧 **Status:** READY FOR REVIEW  
⏭️ **Next:** Tunggu approval user sebelum eksekusi
