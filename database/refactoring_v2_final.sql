-- 1. DROP TABLES yang tidak digunakan / akan direcreate
DROP TABLE IF EXISTS `jns_akun`;
DROP TABLE IF EXISTS `jns_deposito`;
DROP TABLE IF EXISTS `suku_bunga`;
DROP TABLE IF EXISTS `tbl_bukti_foto_pembayaran_pinjaman`;
DROP TABLE IF EXISTS `tbl_bukti_foto_pinjaman`;
DROP TABLE IF EXISTS `tbl_bukti_foto_tabungan`;
DROP TABLE IF EXISTS `tempo_pinjaman_m`; -- Mingguan ditiadakan sementara

-- Drop tabel transaksi utama untuk recreate dengan struktur baru (Hati-hati: Data akan hilang)
-- Pastikan ini safe dilakukan di development
DROP TABLE IF EXISTS `trans_tabungan`;
DROP TABLE IF EXISTS `tbl_pengajuan_tabungan`;
DROP TABLE IF EXISTS `tbl_pengajuan_pembayaran_pinjaman`;
DROP TABLE IF EXISTS `tempo_pinjaman_b`;
DROP TABLE IF EXISTS `tbl_pinjaman_h`;
DROP TABLE IF EXISTS `tbl_pengajuan_pinjaman`;

-- 2. CREATE MASTER DATA TABLES
CREATE TABLE IF NOT EXISTS `jns_fitur` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `kode` CHAR(1) NOT NULL UNIQUE, -- T, P, D, G
    `nama` VARCHAR(50) NOT NULL,
    `deskripsi` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `jns_via` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `kode` CHAR(2) NOT NULL UNIQUE, -- TF (Transfer), TN (Tunai)
    `nama` VARCHAR(50) NOT NULL,
    `deskripsi` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `jns_transaksi` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `kode` VARCHAR(5) NOT NULL UNIQUE, -- STR, PNR, PMB, PNJ, PCR, TRKT
    `nama` VARCHAR(50) NOT NULL,
    `deskripsi` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 3. SEED MASTER DATA
INSERT IGNORE INTO `jns_fitur` (`kode`, `nama`) VALUES 
('T', 'Tabungan'),
('P', 'Pinjaman'),
('D', 'Deposito'),
('G', 'Gadai');

INSERT IGNORE INTO `jns_via` (`kode`, `nama`) VALUES 
('TF', 'Transfer'),
('TN', 'Tunai');

INSERT IGNORE INTO `jns_transaksi` (`kode`, `nama`) VALUES 
('STR', 'Setoran Tabungan'),
('PNR', 'Penarikan Tabungan'),
('TRKT', 'Transaksi Tabungan'),
('PNJ', 'Pengajuan Pinjaman'),
('CAIR', 'Pencairan Pinjaman'),
('PMB', 'Pembayaran Pinjaman'),
('TPNJM', 'Tempo Pinjaman Bulanan');

-- 4. CREATE TABLE FOTO UNIVERSAL
CREATE TABLE IF NOT EXISTS `tbl_bukti_foto` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `owner_id` VARCHAR(30) NOT NULL, -- Kunci relasi string complex
    `owner_fitur` CHAR(1) NOT NULL, -- T, P, G, D
    `owner_trans` VARCHAR(10) NOT NULL, -- STR, PNR, dll
    `file_path` VARCHAR(255) NOT NULL,
    `keterangan` VARCHAR(255) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_owner` (`owner_id`),
    INDEX `idx_owner_type` (`owner_fitur`, `owner_trans`)
);

-- 5. RE-CREATE TRANSACTION TABLES WITH NEW STRUCTURE

-- A. Pengajuan Tabungan
CREATE TABLE IF NOT EXISTS `tbl_pengajuan_tabungan` (
    `id` VARCHAR(30) PRIMARY KEY, -- Format: 300120260001TTSTR
    `id_anggota` BIGINT UNSIGNED NOT NULL,
    `nominal` DECIMAL(15,2) NOT NULL,
    `keterangan` TEXT NULL,
    `keterangan_admin` TEXT NULL,
    `status` CHAR(1) NOT NULL DEFAULT '1' COMMENT '1=Pending, 2=Ditolak, 3=Disetujui',
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_anggota_pt` (`id_anggota`)
);

-- B. Transaksi Tabungan (Mutasi Saldo)
CREATE TABLE IF NOT EXISTS `trans_tabungan` (
    `id` VARCHAR(30) PRIMARY KEY, -- Format: 30012026000...TTTRKT
    `id_pengajuan_setor` VARCHAR(30) NULL,
    `id_pengajuan_tarik` VARCHAR(30) NULL,
    `id_anggota` BIGINT UNSIGNED NOT NULL,
    `nominal` DECIMAL(15,2) NOT NULL,
    `keterangan` TEXT NULL,
    `tgl_transaksi` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Kolom relasi logic ke Master (Optional, bisa join lewat kode string ID, tapi FK lebih aman untuk integritas)
    `id_jns_trans` BIGINT UNSIGNED NULL, -- Referensi ke jns_transaksi.id
    `id_via` BIGINT UNSIGNED NULL, -- Referensi ke jns_via.id
    
    INDEX `idx_anggota_tt` (`id_anggota`),
    FOREIGN KEY (`id_jns_trans`) REFERENCES `jns_transaksi`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`id_via`) REFERENCES `jns_via`(`id`) ON DELETE SET NULL
);

-- C. Pengajuan Pinjaman
CREATE TABLE IF NOT EXISTS `tbl_pengajuan_pinjaman` (
    `id` VARCHAR(30) PRIMARY KEY, -- Format: 300120260001PTPNJ
    `id_anggota` BIGINT UNSIGNED NOT NULL,
    `tgl_pengajuan` DATE NOT NULL,
    `nominal` DECIMAL(15,2) NOT NULL,
    `jenis` VARCHAR(20) DEFAULT 'bulanan',
    `durasi` INT NOT NULL,
    `status` CHAR(1) NOT NULL DEFAULT '1' COMMENT '1=Pending, 2=Ditolak, 3=Disetujui, 4=Cair',
    `keterangan` TEXT NULL,
    `keterangan_admin` TEXT NULL,
    `tgl_cair` DATE NULL,
    `bunga_persen` DECIMAL(5,2) NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_anggota_pp` (`id_anggota`)
);

-- D. Pinjaman Header (Pinjaman Aktif)
CREATE TABLE IF NOT EXISTS `tbl_pinjaman_h` (
    `id` VARCHAR(30) PRIMARY KEY, -- Format: 30012026000...PTDPNJM
    `id_anggota` BIGINT UNSIGNED NOT NULL,
    `id_pengajuan` VARCHAR(30) NULL,
    `jumlah_pinjam` DECIMAL(15,2) NOT NULL,
    `lama_pinjam` INT NOT NULL,
    `jenis` VARCHAR(20) DEFAULT 'bulanan',
    `bunga` DECIMAL(5,2) NOT NULL,
    `bunga_rp` DECIMAL(15,2) NOT NULL,
    `denda_persen` DECIMAL(5,2) DEFAULT 0.3,
    `ags_bulan` DECIMAL(15,2) NOT NULL, -- Pokok per bulan
    `tgl_pinjam` DATE NOT NULL,
    `lunas` ENUM('belum', 'lunas') DEFAULT 'belum',
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_anggota_ph` (`id_anggota`)
);

-- E. Tempo Pinjaman Bulanan (Jadwal Angsuran)
CREATE TABLE IF NOT EXISTS `tempo_pinjaman_b` (
    `id` VARCHAR(30) PRIMARY KEY, -- Format: 30012026000...PTTPNJM
    `pinjaman_id` VARCHAR(30) NOT NULL,
    `no_urut` INT NOT NULL,
    `tgl_jatuh_tempo` DATE NOT NULL,
    `jumlah_tagihan` DECIMAL(15,2) NOT NULL,
    `jumlah_terbayar` DECIMAL(15,2) DEFAULT 0,
    `denda` DECIMAL(15,2) DEFAULT 0,
    `tgl_bayar` TIMESTAMP NULL,
    `status_bayar` ENUM('belum', 'lunas', 'telat') DEFAULT 'belum',
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_pinjaman_b` (`pinjaman_id`),
    FOREIGN KEY (`pinjaman_id`) REFERENCES `tbl_pinjaman_h`(`id`) ON DELETE CASCADE
);

-- F. Pengajuan Pembayaran Pinjaman
CREATE TABLE IF NOT EXISTS `tbl_pengajuan_pembayaran_pinjaman` (
    `id` VARCHAR(30) PRIMARY KEY, -- Format: 30012026000...PTPMB
    `id_anggota` BIGINT UNSIGNED NOT NULL,
    `pinjaman_id` VARCHAR(30) NOT NULL,
    `tempo_id` VARCHAR(30) NOT NULL,
    `jenis_tempo` VARCHAR(20) DEFAULT 'bulanan',
    `nominal` DECIMAL(15,2) NOT NULL,
    `rekening_tujuan` VARCHAR(50) NULL,
    `keterangan` TEXT NULL,
    `keterangan_admin` TEXT NULL,
    `status` CHAR(1) NOT NULL DEFAULT '1' COMMENT '1=Pending, 2=Ditolak, 3=Disetujui',
    `tgl_pembayaran` TIMESTAMP NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`pinjaman_id`) REFERENCES `tbl_pinjaman_h`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`tempo_id`) REFERENCES `tempo_pinjaman_b`(`id`) ON DELETE CASCADE
);

-- 6. CREATE VIEW JANJI TEMU UNIVERSAL
-- Menggabungkan janji tamu dari berbagai tabel kalau ada, tapi karena table janji temu mungkin masih terpisah,
-- Mari kita buat satu view dari tabel janji temu yang ada.
-- Jika user minta menyatukan semua janji temu, idealnya tabelnya pun disatukan, tapi via view juga bisa.
-- Asumsi tabel janji temu masih eksis: tbl_janji_temu_tabungan, tbl_janji_temu_pinjaman.

/*
CREATE OR REPLACE VIEW `v_janji_temu_universal` AS
SELECT 
    UUID() as id_view, -- Dummy ID
    'Tabungan' as fitur,
    jtt.id as id_asli,
    pt.id_anggota,
    u.nama as nama_anggota,
    jtt.tanggal_janji_temu,
    jtt.waktu_janji_temu,
    jtt.nominal,
    jl.nama_lokasi as lokasi,
    pt.keterangan,
    jtt.created_at
FROM `tbl_janji_temu_tabungan` jtt
JOIN `tbl_pengajuan_tabungan` pt ON jtt.id_pengajuan = pt.id
JOIN `tbl_nasabah` n ON pt.id_anggota = n.id
JOIN `users` u ON n.user_id = u.id
JOIN `jns_lokasi_perusahaan` jl ON jtt.lokasi_temu = jl.id

UNION ALL

SELECT 
    UUID() as id_view,
    'Pinjaman' as fitur,
    jtp.id as id_asli,
    pp.id_anggota,
    u.nama as nama_anggota,
    jtp.tanggal_janji_temu,
    jtp.waktu_janji_temu,
    jtp.nominal,
    jl.nama_lokasi as lokasi,
    jtp.keterangan,
    jtp.created_at
FROM `tbl_janji_temu_pinjaman` jtp
JOIN `tbl_pengajuan_pinjaman` pp ON jtp.id_pengajuan = pp.id
JOIN `tbl_nasabah` n ON pp.id_anggota = n.id
JOIN `users` u ON n.user_id = u.id
JOIN `jns_lokasi_perusahaan` jl ON jtp.lokasi_temu = jl.id;
*/
