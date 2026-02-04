# Database Structure - Koperasi Majakara

This document outlines the complete database schema for the Koperasi Majakara system.

## Master Data Tables

### jns_fitur
Jenis fitur/layanan koperasi
- id BIGINT UNSIGNED PK AUTO_INCREMENT
- kode CHAR(1) UNIQUE NOT NULL
- nama VARCHAR(50) NOT NULL
- deskripsi TEXT NULL
- is_active TINYINT(1) DEFAULT 1
- created_at TIMESTAMP
- updated_at TIMESTAMP

**Data:**
- T: Tabungan
- P: Pinjaman
- G: Gadai
- D: Deposito

### jns_via
Jenis metode transaksi
- id BIGINT UNSIGNED PK AUTO_INCREMENT
- kode CHAR(2) UNIQUE NOT NULL  
- nama VARCHAR(50) NOT NULL
- deskripsi TEXT NULL
- is_active TINYINT(1) DEFAULT 1
- created_at TIMESTAMP
- updated_at TIMESTAMP

**Data:**
- TF: Transfer
- CS: Cash

### jns_transaksi
Jenis transaksi
- id BIGINT UNSIGNED PK AUTO_INCREMENT
- kode VARCHAR(5) UNIQUE NOT NULL
- nama VARCHAR(50) NOT NULL
- deskripsi TEXT NULL
- is_active TINYINT(1) DEFAULT 1
- created_at TIMESTAMP
- updated_at TIMESTAMP

**Data:**
- STR: Setoran
- PNR: Penarikan
- TRKT: Transaksi Tabungan
- PNJ: Pengajuan Pinjaman
- PMB: Pembayaran Pinjaman
- DPNJM: Data Pinjaman
- TPNJM: Tempo Pinjaman
- JNJT: Janji Temu

### jns_lokasi_perusahaan
Lokasi kantor koperasi
- id BIGINT UNSIGNED PK AUTO_INCREMENT
- nama_lokasi VARCHAR(100) NOT NULL
- alamat TEXT
- kota VARCHAR(50)
- kode_pos VARCHAR(10)
- telepon VARCHAR(20)
- email VARCHAR(100)
- latitude DECIMAL(10,8)
- longitude DECIMAL(11,8)
- jam_buka TIME
- jam_tutup TIME
- status_aktif BOOLEAN DEFAULT TRUE
- created_at TIMESTAMP
- updated_at TIMESTAMP

## User & Authentication Tables

### users
Tabel pengguna sistem
- id BIGINT UNSIGNED PK AUTO_INCREMENT
- nama VARCHAR(255) NOT NULL
- nik VARCHAR(16) UNIQUE NULL
- email VARCHAR(255) UNIQUE NOT NULL
- email_verified_at TIMESTAMP NULL
- password VARCHAR(255) NOT NULL
- role ENUM('nasabah','admin') NOT NULL
- pin VARCHAR(6) NULL
- remember_token VARCHAR(100) NULL
- created_at TIMESTAMP
- updated_at TIMESTAMP

### tbl_nasabah
Data lengkap nasabah
- id BIGINT UNSIGNED PK AUTO_INCREMENT
- user_id BIGINT UNSIGNED FK->users.id UNIQUE
- no_anggota VARCHAR(20) UNIQUE
- tempat_lahir VARCHAR(100)
- tanggal_lahir DATE
- jenis_kelamin ENUM('L','P')
- agama VARCHAR(20)
- status_perkawinan ENUM('BELUM_KAWIN','KAWIN','CERAI_HIDUP','CERAI_MATI')
- no_hp VARCHAR(15)
- created_at TIMESTAMP
- updated_at TIMESTAMP

### tbl_data_ktp
Data KTP nasabah
- id BIGINT UNSIGNED PK AUTO_INCREMENT
- nasabah_id BIGINT UNSIGNED FK->tbl_nasabah.id UNIQUE
- nik VARCHAR(16) UNIQUE
- nama_lengkap VARCHAR(255)
- alamat TEXT
- rt VARCHAR(3)
- rw VARCHAR(3)
- kelurahan VARCHAR(100)
- kecamatan VARCHAR(100)
- kota VARCHAR(100)
- provinsi VARCHAR(100)
- kode_pos VARCHAR(10)
- foto_ktp VARCHAR(255)
- created_at TIMESTAMP
- updated_at TIMESTAMP

### tbl_data_rek
Data rekening nasabah
- id BIGINT UNSIGNED PK AUTO_INCREMENT
- nasabah_id BIGINT UNSIGNED FK->tbl_nasabah.id UNIQUE
- nama_bank VARCHAR(100)
- no_rekening VARCHAR(50)
- nama_pemilik_rekening VARCHAR(255)
- created_at TIMESTAMP
- updated_at TIMESTAMP

### tbl_pekerjaan
Data pekerjaan nasabah
- id BIGINT UNSIGNED PK AUTO_INCREMENT
- nasabah_id BIGINT UNSIGNED FK->tbl_nasabah.id UNIQUE
- jenis_pekerjaan VARCHAR(100)
- nama_perusahaan VARCHAR(255)
- alamat_perusahaan TEXT
- jabatan VARCHAR(100)
- lama_bekerja_tahun INT
- lama_bekerja_bulan INT
- penghasilan_per_bulan DECIMAL(15,2)
- created_at TIMESTAMP
- updated_at TIMESTAMP

### tbl_darurat
Data kontak darurat
- id BIGINT UNSIGNED PK AUTO_INCREMENT
- nasabah_id BIGINT UNSIGNED FK->tbl_nasabah.id UNIQUE
- nama_lengkap VARCHAR(255)
- hubungan VARCHAR(50)
- no_hp VARCHAR(15)
- alamat TEXT
- created_at TIMESTAMP
- updated_at TIMESTAMP

## Tabungan Tables

### tbl_pengajuan_tabungan
Pengajuan setoran tabungan
- id VARCHAR(30) PK
- id_anggota BIGINT UNSIGNED FK->tbl_nasabah.id
- nominal DECIMAL(15,2)
- foto_bukti_tf ENUM('transfer','tunai') DEFAULT 'transfer'
- keterangan TEXT NULL
- keterangan_admin TEXT NULL
- status CHAR(1) DEFAULT '1'
- created_at TIMESTAMP
- updated_at TIMESTAMP

**Status:**
- 1: Pending
- 2: Disetujui
- 3: Ditolak

### tbl_pengajuan_penarikan_tabungan
Pengajuan penarikan tabungan
- id VARCHAR(30) PK
- id_anggota BIGINT UNSIGNED FK->tbl_nasabah.id
- tgl_pengajuan DATETIME
- nominal DECIMAL(15,2)
- metode_transfer VARCHAR(50) NULL
- no_rekening VARCHAR(50) NULL
- nama_bank VARCHAR(100) NULL
- foto_bukti_tf_admin VARCHAR(255) NULL
- keterangan TEXT NULL
- keterangan_admin TEXT NULL
- status ENUM('1','2','3') DEFAULT '1'
- created_at TIMESTAMP
- updated_at TIMESTAMP

### trans_tabungan
Transaksi tabungan (setoran & penarikan)
- id VARCHAR(30) PK
- id_pengajuan_setor VARCHAR(30) NULL
- id_pengajuan_tarik VARCHAR(30) NULL
- id_anggota BIGINT UNSIGNED FK->tbl_nasabah.id
- id_jns_transaksi BIGINT UNSIGNED FK->jns_transaksi.id NULL
- id_jns_via BIGINT UNSIGNED FK->jns_via.id NULL
- nominal DECIMAL(15,2)
- keterangan TEXT NULL
- tgl_transaksi TIMESTAMP DEFAULT CURRENT_TIMESTAMP
- created_at TIMESTAMP
- updated_at TIMESTAMP

### tbl_janji_temu_tabungan
Janji temu untuk setoran tunai tabungan
- id VARCHAR(30) PK (Format: DDMMYYYYNNNNTCJNJT)
- id_nasabah BIGINT UNSIGNED FK->tbl_nasabah.id
- id_jns_fitur BIGINT UNSIGNED FK->jns_fitur.id (T)
- id_jns_via BIGINT UNSIGNED FK->jns_via.id (CS)
- id_jns_transaksi BIGINT UNSIGNED FK->jns_transaksi.id (JNJT)
- lokasi_temu BIGINT UNSIGNED FK->jns_lokasi_perusahaan.id
- nominal DECIMAL(15,2)
- tanggal_janji_temu DATETIME
- waktu_janji_temu TIME
- keterangan TEXT NULL
- keterangan_admin TEXT NULL
- status ENUM('1','2','3') DEFAULT '1'
- created_at TIMESTAMP
- updated_at TIMESTAMP

**Status:**
- 1: Menunggu (belum temu)
- 2: Selesai (sudah konfirmasi)
- 3: Batal

## Pinjaman Tables

### tbl_pengajuan_pinjaman
Pengajuan pinjaman
- id VARCHAR(30) PK
- id_anggota BIGINT UNSIGNED FK->tbl_nasabah.id
- nominal DECIMAL(15,2)
- jangka_waktu INT
- jenis_angsuran ENUM('B','M') (B=Bulanan, M=Mingguan)
- tujuan_pinjaman TEXT
- keterangan TEXT NULL
- keterangan_admin TEXT NULL
- status ENUM('1','2','3') DEFAULT '1'
- created_at TIMESTAMP
- updated_at TIMESTAMP

### tbl_pinjaman_h
Header pinjaman yang disetujui
- id VARCHAR(30) PK
- id_pengajuan VARCHAR(30) FK->tbl_pengajuan_pinjaman.id
- id_anggota BIGINT UNSIGNED FK->tbl_nasabah.id
- nominal_pinjaman DECIMAL(15,2)
- bunga_persen DECIMAL(5,2)
- total_bunga DECIMAL(15,2)
- total_harus_dibayar DECIMAL(15,2)
- jangka_waktu INT
- jenis_angsuran ENUM('B','M')
- nominal_angsuran DECIMAL(15,2)
- tanggal_pinjaman DATE
- status_lunas BOOLEAN DEFAULT FALSE
- created_at TIMESTAMP
- updated_at TIMESTAMP

### tempo_pinjaman_b
Jadwal tempo pinjaman bulanan
- id BIGINT UNSIGNED PK AUTO_INCREMENT
- id_pinjaman VARCHAR(30) FK->tbl_pinjaman_h.id
- tempo_ke INT
- tanggal_jatuh_tempo DATE
- nominal_angsuran DECIMAL(15,2)
- nominal_terbayar DECIMAL(15,2) DEFAULT 0
- status_lunas BOOLEAN DEFAULT FALSE
- created_at TIMESTAMP
- updated_at TIMESTAMP

### tempo_pinjaman_m  
Jadwal tempo pinjaman mingguan
- id BIGINT UNSIGNED PK AUTO_INCREMENT
- id_pinjaman VARCHAR(30) FK->tbl_pinjaman_h.id
- tempo_ke INT
- tanggal_jatuh_tempo DATE
- nominal_angsuran DECIMAL(15,2)
- nominal_terbayar DECIMAL(15,2) DEFAULT 0
- status_lunas BOOLEAN DEFAULT FALSE
- created_at TIMESTAMP
- updated_at TIMESTAMP

### tbl_pengajuan_pembayaran_pinjaman
Pengajuan pembayaran angsuran
- id VARCHAR(30) PK
- id_pinjaman VARCHAR(30) FK->tbl_pinjaman_h.id
- id_anggota BIGINT UNSIGNED FK->tbl_nasabah.id
- nominal DECIMAL(15,2)
- metode_pembayaran ENUM('transfer','tunai')
- keterangan TEXT NULL
- keterangan_admin TEXT NULL
- status ENUM('1','2','3') DEFAULT '1'
- created_at TIMESTAMP
- updated_at TIMESTAMP

### tbl_janji_temu_pinjaman
Janji temu untuk pengajuan pinjaman
- id VARCHAR(30) PK (Format: DDMMYYYYNNNNTCJNJT)
- id_nasabah BIGINT UNSIGNED FK->tbl_nasabah.id
- id_jns_fitur BIGINT UNSIGNED FK->jns_fitur.id (P)
- id_jns_via BIGINT UNSIGNED FK->jns_via.id (CS)
- id_jns_transaksi BIGINT UNSIGNED FK->jns_transaksi.id (JNJT)
- lokasi_temu BIGINT UNSIGNED FK->jns_lokasi_perusahaan.id
- nominal DECIMAL(15,2)
- tanggal_janji_temu DATETIME
- waktu_janji_temu TIME
- keterangan TEXT NULL
- keterangan_admin TEXT NULL
- status ENUM('1','2','3') DEFAULT '1'
- created_at TIMESTAMP
- updated_at TIMESTAMP

### tbl_janji_temu_pembayaran_pinjaman
Janji temu untuk pembayaran angsuran pinjaman
- id VARCHAR(30) PK (Format: DDMMYYYYNNNNPCJNJT)
- id_pinjaman VARCHAR(30) FK->tbl_pinjaman_h.id
- id_nasabah BIGINT UNSIGNED FK->tbl_nasabah.id
- id_jns_fitur BIGINT UNSIGNED FK->jns_fitur.id (P)
- id_jns_via BIGINT UNSIGNED FK->jns_via.id (CS)
- id_jns_transaksi BIGINT UNSIGNED FK->jns_transaksi.id (JNJT)
- lokasi_temu BIGINT UNSIGNED FK->jns_lokasi_perusahaan.id
- nominal DECIMAL(15,2)
- tanggal_janji_temu DATETIME
- waktu_janji_temu TIME
- keterangan TEXT NULL
- keterangan_admin TEXT NULL
- status ENUM('1','2','3') DEFAULT '1'
- created_at TIMESTAMP
- updated_at TIMESTAMP

## Universal Tables

### tbl_bukti_foto
Universal table untuk semua bukti foto
- id BIGINT UNSIGNED PK AUTO_INCREMENT
- owner_id VARCHAR(30) INDEX
- owner_fitur CHAR(1) INDEX (T/P/G/D)
- owner_trans VARCHAR(10) (STR/PNR/PMB/JNJT etc)
- file_path VARCHAR(255)
- keterangan VARCHAR(255) NULL
- created_at TIMESTAMP
- updated_at TIMESTAMP

**Usage:**
- Bukti transfer setoran tabungan
- Bukti pembayaran pinjaman
- Foto penerimaan saat janji temu
- Foto barang gadai

## Views

### v_janji_temu_universal
View gabungan semua janji temu
```sql
SELECT
    UUID() AS id_view,
    'Tabungan' AS fitur,
    jt.id AS id_asli,
    jt.id_nasabah AS id_anggota,
    u.nama AS nama_anggota,
    jt.tanggal_janji_temu,
    jt.waktu_janji_temu,
    jt.nominal,
    jl.nama_lokasi AS lokasi,
    jt.keterangan,
    jt.status,
    jt.created_at
FROM tbl_janji_temu_tabungan jt
JOIN tbl_nasabah n ON jt.id_nasabah = n.id
JOIN users u ON n.user_id = u.id
JOIN jns_lokasi_perusahaan jl ON jt.lokasi_temu = jl.id

UNION ALL

SELECT
    UUID() AS id_view,
    'Pinjaman' AS fitur,
    jt.id AS id_asli,
    jt.id_nasabah AS id_anggota,
    u.nama AS nama_anggota,
    jt.tanggal_janji_temu,
    jt.waktu_janji_temu,
    jt.nominal,
    jl.nama_lokasi AS lokasi,
    jt.keterangan,
    jt.status,
    jt.created_at
FROM tbl_janji_temu_pinjaman jt
JOIN tbl_nasabah n ON jt.id_nasabah = n.id
JOIN users u ON n.user_id = u.id
JOIN jns_lokasi_perusahaan jl ON jt.lokasi_temu = jl.id

UNION ALL

SELECT
    UUID() AS id_view,
    'Pembayaran Pinjaman' AS fitur,
    jt.id AS id_asli,
    jt.id_nasabah AS id_anggota,
    u.nama AS nama_anggota,
    jt.tanggal_janji_temu,
    jt.waktu_janji_temu,
    jt.nominal,
    jl.nama_lokasi AS lokasi,
    jt.keterangan,
    jt.status,
    jt.created_at
FROM tbl_janji_temu_pembayaran_pinjaman jt
JOIN tbl_nasabah n ON jt.id_nasabah = n.id
JOIN users u ON n.user_id = u.id
JOIN jns_lokasi_perusahaan jl ON jt.lokasi_temu = jl.id
```

## ID Generation Format

All transaction IDs follow this pattern:
**DDMMYYYYNNNN[FITUR][VIA][TRANS]**

Examples:
- `04022026001TCSJNJT` - Tabungan Cash Janji Temu
- `04022026001TTFSTR` - Tabungan Transfer Setoran
- `04022026001PCSJNJT` - Pinjaman Cash Janji Temu
- `04022026001PCSPमB` - Pinjaman Cash Pembayaran

Where:
- DDMMYYYY: Date (04022026)
- NNNN: Sequence number (0001)
- FITUR: T/P/G/D (Tabungan/Pinjaman/Gadai/Deposito)
- VIA: TF/CS (Transfer/Cash)
- TRANS: STR/PNR/JNJT/PMB etc
