# Analisis Database & Flow Proyek Koperasi Majakara

## 📋 Daftar Isi
1. [Overview Sistem](#overview-sistem)
2. [Struktur Database](#struktur-database)
3. [Entity Relationship](#entity-relationship)
4. [Flow Proyek](#flow-proyek)
5. [Modul Utama](#modul-utama)

---

## 🎯 Overview Sistem

Sistem ini adalah **Sistem Manajemen Koperasi** yang mengelola berbagai produk keuangan:
- **Tabungan** (Savings)
- **Pinjaman** (Loans)
- **Deposito** (Time Deposits)
- **Gadai** (Pawn Services)

Sistem memiliki 3 jenis user:
1. **Nasabah** - Pelanggan koperasi
2. **Admin Operasional** - Admin yang menangani operasional harian
3. **Admin Utama** - Admin dengan akses penuh

---

## 🗄️ Struktur Database

### 1. **Tabel User & Authentication**

#### `users`
- **Primary Key**: `id`
- **Fields**:
  - `nama`, `email` (unique), `password`, `pin` (nullable)
  - `nomor_hp`, `foto`, `role` (enum: nasabah, admin_operasional, admin_utama)
  - `email_verified_at`, `remember_token`
- **Purpose**: Tabel utama untuk semua user (nasabah & admin)

#### `tbl_otp`
- **Foreign Key**: `user_id` → `users`
- **Fields**: `otp_code`, `expired_at`, `is_verified`
- **Purpose**: Verifikasi OTP untuk registrasi/login

#### `admin_operasional` & `admin_utama`
- **Foreign Key**: `user_id` → `users`
- **Purpose**: Tabel relasi untuk admin (one-to-one dengan users)

---

### 2. **Tabel Nasabah**

#### `tbl_nasabah` (Data Final Nasabah)
- **Foreign Key**: `user_id` → `users`
- **Fields**:
  - `no_kk` (unique, 16 char) - Nomor Kartu Keluarga
  - `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin` (L/P)
  - `alamat`, `foto_ktp`, `foto_kk`
- **Purpose**: Data nasabah yang sudah terverifikasi

#### `tbl_nasabah_temp` (Data Sementara)
- **Structure**: Sama dengan `tbl_nasabah`
- **Purpose**: Menyimpan data registrasi sementara sebelum verifikasi admin

#### Tabel Detail Nasabah:
- **`tbl_pekerjaan`** - Data pekerjaan nasabah
- **`tbl_data_ktp`** - Detail KTP (NIK, nama lengkap, alamat, file KTP)
- **`tbl_data_rek`** - Data rekening bank nasabah
- **`tbl_darurat`** - Kontak darurat nasabah

#### Tabel Temp Detail:
- **`tbl_pekerjaan_temp`**
- **`tbl_data_ktp_temp`**
- **`tbl_data_rek_temp`**
- **`tbl_darurat_temp`**

**Flow**: Data masuk ke tabel `*_temp` → Admin verifikasi → Pindah ke tabel final

---

### 3. **Tabel Master Data**

#### `jns_lokasi_perusahaan`
- **Fields**: `nama_lokasi`, `alamat_lengkap`, `kota`, `provinsi`, `tipe_lokasi`, `status_aktif`
- **Purpose**: Master data lokasi kantor/koperasi untuk janji temu

#### `jns_angsuran_bulan` & `jns_angsuran_minggu`
- **Fields**: `ket` (char 1, unique), `aktif` (y/n)
- **Purpose**: Master data jenis angsuran (untuk pinjaman)

#### `suku_bunga`
- **Fields**: `jenis_bunga`, `opsi_val` (decimal 5,4)
- **Purpose**: Master data suku bunga untuk pinjaman

---

### 4. **Modul Tabungan**

#### `tbl_pengajuan_tabungan`
- **Foreign Key**: `id_anggota` → `tbl_nasabah`
- **Fields**:
  - `foto_bukti_tf` - Bukti transfer setoran
  - `keterangan`, `status` (1=pending, 2=approved, 3=rejected)
- **Purpose**: Pengajuan setoran tabungan

#### `tbl_pengajuan_penarikan_tabungan`
- **Foreign Key**: `id_anggota` → `tbl_nasabah`
- **Fields**: `tgl_pengajuan`, `nominal`, `keterangan`, `status`
- **Purpose**: Pengajuan penarikan tabungan

#### `tbl_bukti_foto_tabungan`
- **Foreign Key**: `id_pengajuan` → `tbl_pengajuan_tabungan`
- **Fields**: `file_photo`, `jenis` (tabungan/penarikan), `nominal`, `keterangan`
- **Purpose**: Bukti foto untuk transaksi tabungan

#### `trans_tabungan`
- **Foreign Keys**: 
  - `id_pengajuan_setor` → `tbl_pengajuan_tabungan` (nullable)
  - `id_pengajuan_tarik` → `tbl_pengajuan_penarikan_tabungan` (nullable)
  - `id_anggota` → `tbl_nasabah`
- **Fields**: `nominal`, `keterangan`, `jenis` (setoran/penarikan), `via` (transfer/cash), `tgl_transaksi`
- **Purpose**: Riwayat transaksi tabungan

#### `tbl_janji_temu_tabungan`
- **Foreign Keys**: 
  - `id_pengajuan` → `tbl_pengajuan_tabungan`
  - `lokasi_temu` → `jns_lokasi_perusahaan`
- **Fields**: `nominal`, `tanggal_janji_temu`, `waktu_janji_temu`
- **Purpose**: Janji temu untuk setoran tabungan

---

### 5. **Modul Pinjaman**

#### `tbl_pengajuan_pinjaman`
- **Foreign Key**: `id_anggota` → `tbl_nasabah`
- **Fields**: 
  - `tgl_pengajuan`, `nominal`
  - `jenis` (bulanan/mingguan), `durasi` (char 1)
- **Purpose**: Pengajuan pinjaman

#### `tbl_pinjaman_h` (Header Pinjaman)
- **Foreign Keys**: 
  - `id_anggota` → `tbl_nasabah`
  - `id_pengajuan` → `tbl_pengajuan_pinjaman` (nullable)
- **Fields**:
  - `jumlah_pinjam`, `lama_pinjam`, `jenis` (bulanan/mingguan)
  - `bunga`, `bunga_rp`, `denda_persen`
  - `ags_bulan`, `ags_minggu` (char 1) - Kode angsuran
  - `tgl_pinjam`, `saldo_lebih`
  - `status` (menunggu/pencairan/telaksana)
  - `lunas` (belum/lunas)
- **Purpose**: Data pinjaman yang aktif

#### `tempo_pinjaman_b` (Tempo Bulanan)
- **Foreign Keys**: 
  - `pinjaman_id` → `tbl_pinjaman_h`
  - `anggota_id` → `tbl_nasabah`
- **Fields**: 
  - `no_urut`, `tgl_jatuh_tempo`
  - `jumlah_tagihan`, `jumlah_terbayar`
  - `status_bayar` (belum/lunas/telat)
- **Purpose**: Jadwal angsuran bulanan

#### `tempo_pinjaman_m` (Tempo Mingguan)
- **Structure**: Sama dengan `tempo_pinjaman_b`
- **Purpose**: Jadwal angsuran mingguan

---

### 6. **Modul Deposito**

#### `jns_deposito`
- **Fields**: `nama_jenis`, `deskripsi`, `status_aktif`
- **Purpose**: Master jenis deposito

#### `jns_tenor_deposito`
- **Fields**: `tenor_hari`, `tenor_bulan`, `aktif`
- **Purpose**: Master tenor/jangka waktu deposito

#### `suku_bunga_deposito`
- **Foreign Key**: `tenor_id` → `jns_tenor_deposito`
- **Fields**: `min_nominal`, `max_nominal`, `bunga`, `status`
- **Purpose**: Suku bunga berdasarkan tenor dan nominal

#### `tbl_pengajuan_deposito`
- **Foreign Keys**: 
  - `id_nasabah` → `tbl_nasabah`
  - `tenor_id` → `jns_tenor_deposito`
  - `jenis_deposito` → `jns_deposito`
- **Fields**: 
  - `nominal`, `metode_setor` (transfer/saldo_tabungan)
  - `foto_bukti_tf`, `status`, `catatan`
- **Purpose**: Pengajuan pembukaan deposito

#### `tbl_deposito_h` (Header Deposito)
- **Foreign Keys**: 
  - `id_pengajuan` → `tbl_pengajuan_deposito` (nullable)
  - `id_nasabah` → `tbl_nasabah`
  - `tenor_id` → `jns_tenor_deposito`
- **Fields**: 
  - `nomor_deposito` (unique, 16 char)
  - `nominal_awal`, `bunga`
  - `tgl_mulai`, `tgl_jatuh_tempo`
  - `metode_pencairan`, `status` (aktif/dicairkan/ditutup/gagal)
- **Purpose**: Data deposito aktif

#### `deposito_bunga_harian`
- **Foreign Key**: `deposito_id` → `tbl_deposito_h`
- **Fields**: `tanggal`, `bunga_harian`, `saldo_akhir`
- **Purpose**: Perhitungan bunga harian deposito

#### `tbl_pencairan_deposito`
- **Foreign Keys**: 
  - `deposito_id` → `tbl_deposito_h`
  - `id_nasabah` → `tbl_nasabah`
- **Fields**: 
  - `nominal_akhir`, `metode_pencairan` (rek_nasabah/saldo_tabungan)
  - `status` (pending/diproses/selesai/ditolak), `catatan`
- **Purpose**: Pengajuan pencairan deposito

#### `trans_deposito`
- **Foreign Key**: `deposito_id` → `tbl_deposito_h`
- **Fields**: `jenis` (setor_awal/bunga/pencairan), `nominal`, `keterangan`, `tgl_transaksi`
- **Purpose**: Riwayat transaksi deposito

#### `tbl_janji_temu_deposito`
- **Foreign Keys**: 
  - `deposito_id` → `tbl_deposito_h`
  - `lokasi_temu` → `jns_lokasi_perusahaan`
- **Fields**: `tanggal_janji_temu`, `waktu_janji_temu`, `catatan`
- **Purpose**: Janji temu untuk deposito

---

### 7. **Modul Gadai**

#### `m_barang_gadai`
- **Fields**: `nama_barang`, `deskripsi`
- **Purpose**: Master data jenis barang yang bisa digadaikan

#### `tbl_item_gadai`
- **Foreign Keys**: 
  - `id_nasabah` → `tbl_nasabah`
  - `id_master_barang` → `m_barang_gadai`
- **Fields**: 
  - `tgl_buat`, `head_1`, `head_2` (deskripsi barang)
  - `nominal_real` - Nilai taksiran sebenarnya
  - `bunga_low`, `nominal_low` - Estimasi rendah
  - `bunga_high`, `nominal_high` - Estimasi tinggi
  - `file_pic` - Foto barang
- **Purpose**: Item barang yang akan digadaikan

#### `tbl_gadai_spesial`
- **Fields**: 
  - `nama` (32 char)
  - `tmpl_250_ribu`, `tmpl_500_ribu`, `tmpl_1_juta`, `tmpl_2_juta`, `tmpl_3_juta`, `tmpl_4_juta`, `tmpl_lebih_dari_5_juta` (y/n)
- **Purpose**: Template khusus untuk kategori nominal gadai

#### `tbl_pengajuan_gadai`
- **Foreign Keys**: 
  - `id_nasabah` → `tbl_nasabah`
  - `id_item_gadai` → `tbl_item_gadai`
- **Fields**: 
  - `nominal_diajukan`, `metode` (datang_langsung/pickup)
  - `foto_bukti_barang`, `catatan`, `status`
- **Purpose**: Pengajuan gadai

#### `tbl_gadai_h` (Header Gadai)
- **Foreign Keys**: 
  - `id_pengajuan` → `tbl_pengajuan_gadai` (nullable)
  - `id_nasabah` → `tbl_nasabah`
  - `id_item_gadai` → `tbl_item_gadai`
- **Fields**: 
  - `nomor_gadai` (unique, 16 char)
  - `jumlah_pinjaman`, `bunga`, `bunga_rp`
  - `tgl_mulai`, `tgl_jatuh_tempo`
  - `status` (aktif/dilelang/lunas/gagal)
  - `metode_pencairan` (transfer/cash)
- **Purpose**: Data gadai aktif

#### `tempo_gadai`
- **Foreign Keys**: 
  - `gadai_id` → `tbl_gadai_h`
  - `nasabah_id` → `tbl_nasabah`
- **Fields**: 
  - `tgl_jatuh_tempo`, `jumlah_tagihan`, `jumlah_terbayar`
  - `status_bayar` (belum/lunas/telat)
- **Purpose**: Jadwal pembayaran gadai

#### `trans_gadai`
- **Foreign Keys**: 
  - `gadai_id` → `tbl_gadai_h`
  - `nasabah_id` → `tbl_nasabah`
- **Fields**: 
  - `jenis` (bunga/pelunasan/pelunasan_akhir/denda/lelang)
  - `nominal`, `keterangan`, `tgl_transaksi`
- **Purpose**: Riwayat transaksi gadai

#### `tbl_lelang_gadai`
- **Foreign Keys**: 
  - `gadai_id` → `tbl_gadai_h`
  - `id_item_gadai` → `tbl_item_gadai`
- **Fields**: 
  - `harga_laku`, `selisih_ke_nasabah`
  - `status` (pending/terjual/ditutup), `catatan`
- **Purpose**: Data lelang untuk gadai yang tidak ditebus

#### `tbl_janji_temu_gadai`
- **Foreign Keys**: 
  - `gadai_id` → `tbl_gadai_h`
  - `lokasi_temu` → `jns_lokasi_perusahaan`
- **Fields**: `tanggal_janji_temu`, `waktu_janji_temu`, `catatan`
- **Purpose**: Janji temu untuk gadai

---

## 🔗 Entity Relationship

### Hubungan Utama:

```
users (1) ──< (1) admin_operasional
users (1) ──< (1) admin_utama
users (1) ──< (1) tbl_nasabah
users (1) ──< (1) tbl_nasabah_temp
users (1) ──< (*) tbl_otp

tbl_nasabah (1) ──< (1) tbl_data_ktp
tbl_nasabah (1) ──< (*) tbl_pekerjaan
tbl_nasabah (1) ──< (*) tbl_data_rek
tbl_nasabah (1) ──< (*) tbl_darurat

tbl_nasabah (1) ──< (*) tbl_pengajuan_tabungan
tbl_nasabah (1) ──< (*) tbl_pengajuan_penarikan_tabungan
tbl_nasabah (1) ──< (*) trans_tabungan
tbl_nasabah (1) ──< (*) tbl_pengajuan_pinjaman
tbl_nasabah (1) ──< (*) tbl_pinjaman_h
tbl_nasabah (1) ──< (*) tbl_pengajuan_deposito
tbl_nasabah (1) ──< (*) tbl_deposito_h
tbl_nasabah (1) ──< (*) tbl_item_gadai
tbl_nasabah (1) ──< (*) tbl_pengajuan_gadai
tbl_nasabah (1) ──< (*) tbl_gadai_h

tbl_pengajuan_tabungan (1) ──< (*) tbl_bukti_foto_tabungan
tbl_pengajuan_tabungan (1) ──< (1) tbl_janji_temu_tabungan
tbl_pengajuan_tabungan (1) ──< (*) trans_tabungan

tbl_pinjaman_h (1) ──< (*) tempo_pinjaman_b
tbl_pinjaman_h (1) ──< (*) tempo_pinjaman_m

tbl_deposito_h (1) ──< (*) deposito_bunga_harian
tbl_deposito_h (1) ──< (*) trans_deposito
tbl_deposito_h (1) ──< (1) tbl_pencairan_deposito
tbl_deposito_h (1) ──< (*) tbl_janji_temu_deposito

tbl_gadai_h (1) ──< (*) tempo_gadai
tbl_gadai_h (1) ──< (*) trans_gadai
tbl_gadai_h (1) ──< (1) tbl_lelang_gadai
tbl_gadai_h (1) ──< (*) tbl_janji_temu_gadai

jns_tenor_deposito (1) ──< (*) suku_bunga_deposito
jns_tenor_deposito (1) ──< (*) tbl_deposito_h
jns_deposito (1) ──< (*) tbl_pengajuan_deposito
jns_lokasi_perusahaan (1) ──< (*) tbl_janji_temu_tabungan
jns_lokasi_perusahaan (1) ──< (*) tbl_janji_temu_deposito
jns_lokasi_perusahaan (1) ──< (*) tbl_janji_temu_gadai

m_barang_gadai (1) ──< (*) tbl_item_gadai
```

---

## 🔄 Flow Proyek

### 1. **Flow Registrasi Nasabah**

```
1. User mengisi form registrasi (6 step):
   Step 1: Data dasar (nama, email, password, nomor HP, foto)
   Step 2: Data nasabah (no_kk, tempat/tanggal lahir, jenis kelamin, alamat, foto KTP/KK)
   Step 3: Data pekerjaan (pekerjaan, penghasilan, nama perusahaan, nama bank)
   Step 4: Data rekening (no_rekening, nama pemilik, jenis ATM)
   Step 5: Data KTP (NIK, nama lengkap, alamat, file KTP)
   Step 6: Data darurat (nama, hubungan, no telepon, alamat, pekerjaan, email, no KTP, foto KTP)

2. Data disimpan di tabel *_temp (temporary)

3. Admin verifikasi data dari tabel temp

4. Jika approved → Data dipindah ke tabel final (tbl_nasabah, tbl_pekerjaan, dll)

5. User bisa login dan menggunakan sistem
```

### 2. **Flow Tabungan**

#### Setoran Tabungan:
```
1. Nasabah mengajukan setoran → tbl_pengajuan_tabungan
   - Upload foto bukti transfer
   - Status: '1' (pending)

2. Nasabah bisa buat janji temu → tbl_janji_temu_tabungan
   - Pilih lokasi
   - Set tanggal & waktu

3. Admin review pengajuan:
   - Approve (status: '2') → Buat trans_tabungan (jenis: setoran)
   - Reject (status: '3')

4. Transaksi tercatat di trans_tabungan
```

#### Penarikan Tabungan:
```
1. Nasabah mengajukan penarikan → tbl_pengajuan_penarikan_tabungan
   - Input nominal
   - Status: '1' (pending)

2. Admin review:
   - Approve (status: '2') → Buat trans_tabungan (jenis: penarikan)
   - Reject (status: '3')
```

### 3. **Flow Pinjaman**

```
1. Nasabah mengajukan pinjaman → tbl_pengajuan_pinjaman
   - Input nominal, jenis (bulanan/mingguan), durasi

2. Admin review & approve:
   - Buat tbl_pinjaman_h dengan status 'pencairan'
   - Generate jadwal angsuran:
     * Jika bulanan → tempo_pinjaman_b
     * Jika mingguan → tempo_pinjaman_m

3. Pencairan:
   - Status pinjaman: 'telaksana'
   - Transfer ke nasabah

4. Pembayaran Angsuran:
   - Nasabah bayar sesuai jadwal tempo
   - Update jumlah_terbayar di tempo_pinjaman_b/m
   - Status: 'lunas' jika sudah bayar, 'telat' jika lewat jatuh tempo

5. Pelunasan:
   - Jika semua angsuran lunas → status 'lunas' di tbl_pinjaman_h
```

### 4. **Flow Deposito**

```
1. Nasabah mengajukan deposito → tbl_pengajuan_deposito
   - Pilih jenis deposito, tenor, nominal
   - Pilih metode setor (transfer/saldo_tabungan)
   - Upload bukti transfer (jika transfer)
   - Status: '1' (pending)

2. Admin review & approve:
   - Buat tbl_deposito_h dengan:
     * nomor_deposito (unique)
     * tgl_mulai, tgl_jatuh_tempo
     * bunga dari suku_bunga_deposito
     * status: 'aktif'
   - Buat trans_deposito (jenis: setor_awal)

3. Perhitungan Bunga Harian:
   - Sistem hitung bunga harian → deposito_bunga_harian
   - Update saldo_akhir setiap hari

4. Pencairan Deposito:
   - Nasabah ajukan pencairan → tbl_pencairan_deposito
   - Admin proses:
     * Hitung nominal_akhir (nominal_awal + total bunga)
     * Buat trans_deposito (jenis: pencairan)
     * Update status: 'dicairkan'
```

### 5. **Flow Gadai**

```
1. Nasabah buat item gadai → tbl_item_gadai
   - Pilih jenis barang (m_barang_gadai)
   - Input deskripsi, nominal real
   - Sistem hitung estimasi (low & high)
   - Upload foto barang

2. Nasabah ajukan gadai → tbl_pengajuan_gadai
   - Pilih item_gadai
   - Input nominal_diajukan
   - Pilih metode (datang_langsung/pickup)
   - Status: '1' (pending)

3. Admin review & approve:
   - Buat tbl_gadai_h dengan:
     * nomor_gadai (unique)
     * jumlah_pinjaman, bunga, bunga_rp
     * tgl_mulai, tgl_jatuh_tempo
     * status: 'aktif'
   - Buat tempo_gadai (jadwal pembayaran)
   - Buat trans_gadai (jenis: setor_awal)
   - Pencairan ke nasabah (transfer/cash)

4. Pembayaran Gadai:
   - Nasabah bayar bunga/pelunasan
   - Update tempo_gadai (jumlah_terbayar)
   - Buat trans_gadai (jenis: bunga/pelunasan)

5. Jika tidak ditebus (lewat jatuh tempo):
   - Status gadai: 'dilelang'
   - Buat tbl_lelang_gadai
   - Jika terjual → Hitung selisih_ke_nasabah
   - Buat trans_gadai (jenis: lelang)
```

---

## 📦 Modul Utama

### 1. **Authentication & Authorization**
- Multi-role system (nasabah, admin_operasional, admin_utama)
- OTP verification
- Session management

### 2. **Nasabah Management**
- Registrasi multi-step dengan data temp
- Verifikasi admin
- Profile management

### 3. **Tabungan Module**
- Pengajuan setoran & penarikan
- Approval workflow
- Transaksi history
- Janji temu

### 4. **Pinjaman Module**
- Pengajuan pinjaman
- Angsuran bulanan/mingguan
- Tracking pembayaran
- Denda & pelunasan

### 5. **Deposito Module**
- Pengajuan deposito
- Perhitungan bunga harian
- Pencairan deposito
- Multiple tenor & suku bunga

### 6. **Gadai Module**
- Item management
- Pengajuan gadai
- Pembayaran bunga
- Lelang barang

### 7. **Master Data**
- Lokasi perusahaan
- Jenis angsuran
- Suku bunga
- Jenis deposito & tenor
- Master barang gadai

---

## 🔑 Key Features

1. **Two-Phase Registration**: Data temp → Verifikasi → Data final
2. **Approval Workflow**: Semua pengajuan perlu approval admin
3. **Janji Temu**: Sistem untuk scheduling pertemuan
4. **Multi-Product**: Tabungan, Pinjaman, Deposito, Gadai
5. **Flexible Payment**: Bulanan & Mingguan untuk pinjaman
6. **Daily Interest Calculation**: Untuk deposito
7. **Auction System**: Untuk gadai yang tidak ditebus
8. **Transaction History**: Semua transaksi tercatat

---

## 📊 Status Enums

### Tabungan:
- `status`: '1' = pending, '2' = approved, '3' = rejected

### Pinjaman:
- `status`: 'menunggu', 'pencairan', 'telaksana'
- `lunas`: 'belum', 'lunas'
- `status_bayar`: 'belum', 'lunas', 'telat'

### Deposito:
- `status`: 'aktif', 'dicairkan', 'ditutup', 'gagal'
- `status` (pencairan): 'pending', 'diproses', 'selesai', 'ditolak'

### Gadai:
- `status`: 'aktif', 'dilelang', 'lunas', 'gagal'
- `status` (lelang): 'pending', 'terjual', 'ditutup'
- `status_bayar`: 'belum', 'lunas', 'telat'

---

## 💡 Catatan Penting

1. **Tabel Temp**: Digunakan untuk registrasi, data akan dipindah setelah verifikasi
2. **Foreign Key Constraints**: Banyak menggunakan `onDelete('cascade')` untuk data integrity
3. **Unique Constraints**: 
   - `no_kk`, `nik`, `no_rekening` di nasabah
   - `nomor_deposito`, `nomor_gadai` untuk produk
4. **Decimal Precision**: 
   - Nominal: `decimal(15, 2)` - untuk uang
   - Bunga: `decimal(5, 4)` - untuk persentase
5. **Date/Time**: Menggunakan `dateTime` untuk tracking waktu transaksi

---

**Dokumen ini dibuat untuk membantu memahami struktur database dan flow sistem Koperasi Majakara.**
