-- =====================================================
-- REFACTORING DATABASE KOPERASI MAJAKARA
-- Tanggal: 3 Februari 2026
-- Tujuan: Migrasi ke sistem Complex ID + Bukti Foto Universal
-- =====================================================

-- DISABLE Foreign Key Checks untuk avoid constraint errors
SET FOREIGN_KEY_CHECKS=0;

-- =====================================================
-- TAHAP 1: DROP OLD TABLES
-- =====================================================

DROP TABLE IF EXISTS `tbl_bukti_foto_pembayaran_pinjaman`;
DROP TABLE IF EXISTS `tbl_bukti_foto_pinjaman`;
DROP TABLE IF EXISTS `tbl_bukti_foto_tabungan`;
DROP TABLE IF EXISTS `jns_akun`;
DROP TABLE IF EXISTS `jns_deposito`;
DROP TABLE IF EXISTS `suku_bunga`;

-- =====================================================
-- TAHAP 2: CREATE MASTER TABLES BARU
-- =====================================================

-- Drop if exists first
DROP TABLE IF EXISTS `jns_fitur`;
DROP TABLE IF EXISTS `jns_via`;
DROP TABLE IF EXISTS `jns_transaksi`;
DROP TABLE IF EXISTS `tbl_bukti_foto`;

-- Table: jns_fitur
CREATE TABLE `jns_fitur` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode` char(1) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `deskripsi` text,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `jns_fitur_kode_unique` (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed jns_fitur
INSERT INTO `jns_fitur` (`kode`, `nama`, `deskripsi`, `is_active`, `created_at`, `updated_at`) VALUES
('T', 'Tabungan', 'Fitur tabungan', 1, NOW(), NOW()),
('P', 'Pinjaman', 'Fitur pinjaman', 1, NOW(), NOW()),
('D', 'Deposito', 'Fitur deposito', 1, NOW(), NOW()),
('G', 'Gadai', 'Fitur gadai', 1, NOW(), NOW());

-- Table: jns_via
CREATE TABLE `jns_via` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode` char(1) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `deskripsi` text,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `jns_via_kode_unique` (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed jns_via
INSERT INTO `jns_via` (`kode`, `nama`, `deskripsi`, `is_active`, `created_at`, `updated_at`) VALUES
('T', 'Transfer', 'Via transfer bank', 1, NOW(), NOW()),
('C', 'Cash', 'Via tunai/cash', 1, NOW(), NOW());

-- Table: jns_transaksi
CREATE TABLE `jns_transaksi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(5) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `deskripsi` text,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `jns_transaksi_kode_unique` (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed jns_transaksi
INSERT INTO `jns_transaksi` (`kode`, `nama`, `deskripsi`, `is_active`, `created_at`, `updated_at`) VALUES
('STR', 'Setoran', 'Setoran tabungan', 1, NOW(), NOW()),
('PNR', 'Penarikan', 'Penarikan tabungan', 1, NOW(), NOW()),
('TRKT', 'Transaksi Tabungan', 'Transaksi tabungan final', 1, NOW(), NOW()),
('PNJ', 'Pengajuan Pinjaman', 'Pengajuan pinjaman', 1, NOW(), NOW()),
('PMB', 'Pembayaran', 'Pembayaran angsuran', 1, NOW(), NOW()),
('DPNJM', 'Data Pinjaman', 'Header pinjaman', 1, NOW(), NOW()),
('TPNJM', 'Tempo Pinjaman', 'Jadwal angsuran', 1, NOW(), NOW());

DROP TABLE IF EXISTS `master_bunga_pinjaman`;
-- Table: master_bunga_pinjaman
CREATE TABLE `master_bunga_pinjaman` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `durasi_min` int NOT NULL,
  `durasi_max` int NOT NULL,
  `bunga_persen` decimal(5,2) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `master_denda_pinjaman`;
-- Table: master_denda_pinjaman
CREATE TABLE `master_denda_pinjaman` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `denda_persen` decimal(5,2) NOT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT 1,
  `keterangan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TAHAP 3: CREATE TBL_BUKTI_FOTO UNIVERSAL
-- =====================================================

CREATE TABLE `tbl_bukti_foto` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` varchar(30) NOT NULL COMMENT 'Complex ID dari table manapun',
  `owner_fitur` char(1) NOT NULL COMMENT 'T, P, D, G',
  `owner_trans` varchar(10) NOT NULL COMMENT 'STR, PNR, PMB, dll',
  `file_path` varchar(255) NOT NULL COMMENT 'Path ke storage',
  `keterangan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_owner` (`owner_id`),
  KEY `idx_owner_type` (`owner_fitur`,`owner_trans`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TAHAP 4: DROP & RECREATE TABUNGAN TABLES
-- =====================================================

DROP TABLE IF EXISTS `trans_tabungan`;
DROP TABLE IF EXISTS `tbl_pengajuan_penarikan_tabungan`;
DROP TABLE IF EXISTS `tbl_janji_temu_tabungan`;
DROP TABLE IF EXISTS `tbl_pengajuan_tabungan`;

-- Table: tbl_pengajuan_tabungan
CREATE TABLE `tbl_pengajuan_tabungan` (
  `id` varchar(30) NOT NULL COMMENT 'Complex ID: 300120260001TTSTR',
  `id_anggota` bigint unsigned NOT NULL,
  `nominal` decimal(15,2) NOT NULL DEFAULT 0.00,
  `keterangan` text,
  `keterangan_admin` text COMMENT 'Keterangan dari admin',
  `status` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '1=Pending, 2=Rejected, 3=Approved',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tbl_pengajuan_tabungan_id_anggota_index` (`id_anggota`),
  KEY `tbl_pengajuan_tabungan_status_index` (`status`),
  CONSTRAINT `tbl_pengajuan_tabungan_id_anggota_foreign` FOREIGN KEY (`id_anggota`) REFERENCES `tbl_nasabah` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: tbl_pengajuan_penarikan_tabungan
CREATE TABLE `tbl_pengajuan_penarikan_tabungan` (
  `id` varchar(30) NOT NULL COMMENT 'Complex ID: 300120260001TTPNR',
  `id_anggota` bigint unsigned NOT NULL,
  `tgl_pengajuan` datetime NOT NULL,
  `nominal` decimal(15,2) NOT NULL,
  `metode_transfer` varchar(50) DEFAULT NULL,
  `no_rekening` varchar(50) DEFAULT NULL,
  `nama_bank` varchar(100) DEFAULT NULL,
  `foto_bukti_tf_admin` varchar(255) DEFAULT NULL,
  `keterangan` text,
  `keterangan_admin` text,
  `status` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '1=Pending, 2=Approved, 3=Rejected',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tbl_pengajuan_penarikan_tabungan_id_anggota_index` (`id_anggota`),
  KEY `tbl_pengajuan_penarikan_tabungan_status_index` (`status`),
  CONSTRAINT `tbl_pengajuan_penarikan_tabungan_id_anggota_foreign` FOREIGN KEY (`id_anggota`) REFERENCES `tbl_nasabah` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: trans_tabungan
CREATE TABLE `trans_tabungan` (
  `id` varchar(30) NOT NULL COMMENT 'Complex ID: 30012026000...TTTRKT',
  `id_pengajuan_setor` varchar(30) DEFAULT NULL,
  `id_pengajuan_tarik` varchar(30) DEFAULT NULL,
  `id_anggota` bigint unsigned NOT NULL,
  `id_jns_fitur` bigint unsigned DEFAULT NULL,
  `id_jns_via` bigint unsigned DEFAULT NULL,
  `id_jns_transaksi` bigint unsigned DEFAULT NULL,
  `nominal` decimal(15,2) NOT NULL,
  `keterangan` text,
  `tgl_transaksi` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trans_tabungan_id_anggota_index` (`id_anggota`),
  KEY `trans_tabungan_id_pengajuan_setor_index` (`id_pengajuan_setor`),
  KEY `trans_tabungan_id_pengajuan_tarik_index` (`id_pengajuan_tarik`),
  CONSTRAINT `trans_tabungan_id_anggota_foreign` FOREIGN KEY (`id_anggota`) REFERENCES `tbl_nasabah` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trans_tabungan_id_jns_fitur_foreign` FOREIGN KEY (`id_jns_fitur`) REFERENCES `jns_fitur` (`id`) ON DELETE SET NULL,
  CONSTRAINT `trans_tabungan_id_jns_via_foreign` FOREIGN KEY (`id_jns_via`) REFERENCES `jns_via` (`id`) ON DELETE SET NULL,
  CONSTRAINT `trans_tabungan_id_jns_transaksi_foreign` FOREIGN KEY (`id_jns_transaksi`) REFERENCES `jns_transaksi` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Recreate tbl_janji_temu_tabungan with id_nasabah
CREATE TABLE `tbl_janji_temu_tabungan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_pengajuan` varchar(30) NOT NULL,
  `id_nasabah` bigint unsigned NOT NULL,
  `lokasi_temu` bigint unsigned NOT NULL,
  `nominal` decimal(15,2) NOT NULL,
  `tanggal_janji_temu` datetime NOT NULL,
  `waktu_janji_temu` timestamp NOT NULL,
  `keterangan` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tbl_janji_temu_tabungan_id_nasabah_index` (`id_nasabah`),
  KEY `tbl_janji_temu_tabungan_lokasi_temu_foreign` (`lokasi_temu`),
  CONSTRAINT `tbl_janji_temu_tabungan_id_nasabah_foreign` FOREIGN KEY (`id_nasabah`) REFERENCES `tbl_nasabah` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tbl_janji_temu_tabungan_lokasi_temu_foreign` FOREIGN KEY (`lokasi_temu`) REFERENCES `jns_lokasi_perusahaan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TAHAP 5: DROP & RECREATE PINJAMAN TABLES
-- =====================================================

DROP TABLE IF EXISTS `tbl_pengajuan_pembayaran_pinjaman`;
DROP TABLE IF EXISTS `tempo_pinjaman_b`;
DROP TABLE IF EXISTS `tbl_pinjaman_h`;
DROP TABLE IF EXISTS `tbl_janji_temu_pembayaran_pinjaman`;
DROP TABLE IF EXISTS `tbl_janji_temu_pinjaman`;
DROP TABLE IF EXISTS `tbl_pengajuan_pinjaman`;

-- Table: tbl_pengajuan_pinjaman
CREATE TABLE `tbl_pengajuan_pinjaman` (
  `id` varchar(30) NOT NULL COMMENT 'Complex ID: 300120260001PTPNJ',
  `id_anggota` bigint unsigned NOT NULL,
  `tgl_pengajuan` datetime NOT NULL,
  `nominal` decimal(15,2) NOT NULL,
  `jenis` enum('bulanan','mingguan') NOT NULL DEFAULT 'bulanan',
  `durasi` int NOT NULL COMMENT 'Durasi dalam bulan (1-24)',
  `status` enum('1','2','3','4') NOT NULL DEFAULT '1' COMMENT '1=Pending, 2=Ditolak, 3=Disetujui, 4=Terlaksana',
  `keterangan` text,
  `keterangan_admin` text,
  `tgl_cair` datetime DEFAULT NULL,
  `bunga_persen` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tbl_pengajuan_pinjaman_id_anggota_index` (`id_anggota`),
  KEY `tbl_pengajuan_pinjaman_status_index` (`status`),
  CONSTRAINT `tbl_pengajuan_pinjaman_id_anggota_foreign` FOREIGN KEY (`id_anggota`) REFERENCES `tbl_nasabah` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: tbl_pinjaman_h
CREATE TABLE `tbl_pinjaman_h` (
  `id` varchar(30) NOT NULL COMMENT 'Complex ID: 30012026000...PTDPNJM',
  `id_anggota` bigint unsigned NOT NULL,
  `id_pengajuan` varchar(30) DEFAULT NULL,
  `jumlah_pinjam` decimal(15,2) NOT NULL,
  `lama_pinjam` int NOT NULL,
  `jenis` enum('bulanan','mingguan') NOT NULL DEFAULT 'bulanan',
  `bunga` decimal(10,4) NOT NULL,
  `bunga_rp` decimal(15,2) NOT NULL,
  `denda_persen` decimal(5,2) NOT NULL,
  `tgl_pinjam` datetime NOT NULL,
  `lunas` enum('belum','lunas') NOT NULL DEFAULT 'belum',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tbl_pinjaman_h_id_anggota_index` (`id_anggota`),
  KEY `tbl_pinjaman_h_id_pengajuan_index` (`id_pengajuan`),
  KEY `tbl_pinjaman_h_lunas_index` (`lunas`),
  CONSTRAINT `tbl_pinjaman_h_id_anggota_foreign` FOREIGN KEY (`id_anggota`) REFERENCES `tbl_nasabah` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: tempo_pinjaman_b
CREATE TABLE `tempo_pinjaman_b` (
  `id` varchar(30) NOT NULL COMMENT 'Complex ID: 30012026000...PTTPNJM',
  `pinjaman_id` varchar(30) NOT NULL,
  `no_urut` int NOT NULL COMMENT 'Angsuran ke-berapa',
  `tgl_jatuh_tempo` datetime NOT NULL,
  `jumlah_tagihan` decimal(15,2) NOT NULL,
  `jumlah_terbayar` decimal(15,2) NOT NULL DEFAULT 0.00,
  `denda` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tgl_bayar` datetime DEFAULT NULL,
  `status_bayar` enum('belum','lunas','telat') NOT NULL DEFAULT 'belum',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tempo_pinjaman_b_pinjaman_id_index` (`pinjaman_id`),
  KEY `tempo_pinjaman_b_status_bayar_index` (`status_bayar`),
  KEY `tempo_pinjaman_b_tgl_jatuh_tempo_index` (`tgl_jatuh_tempo`),
  CONSTRAINT `tempo_pinjaman_b_pinjaman_id_foreign` FOREIGN KEY (`pinjaman_id`) REFERENCES `tbl_pinjaman_h` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: tbl_pengajuan_pembayaran_pinjaman
CREATE TABLE `tbl_pengajuan_pembayaran_pinjaman` (
  `id` varchar(30) NOT NULL COMMENT 'Complex ID: 300120260001PTPMB',
  `id_anggota` bigint unsigned NOT NULL,
  `pinjaman_id` varchar(30) NOT NULL,
  `tempo_id` varchar(30) NOT NULL,
  `jenis_tempo` enum('bulanan','mingguan') NOT NULL DEFAULT 'bulanan',
  `nominal` decimal(15,2) NOT NULL,
  `rekening_tujuan` varchar(255) DEFAULT NULL,
  `keterangan` text,
  `keterangan_admin` text,
  `status` enum('1','2','3') NOT NULL DEFAULT '1',
  `tgl_pembayaran` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tbl_pengajuan_pembayaran_pinjaman_id_anggota_index` (`id_anggota`),
  KEY `tbl_pengajuan_pembayaran_pinjaman_pinjaman_id_index` (`pinjaman_id`),
  KEY `tbl_pengajuan_pembayaran_pinjaman_tempo_id_index` (`tempo_id`),
  KEY `tbl_pengajuan_pembayaran_pinjaman_status_index` (`status`),
  CONSTRAINT `tbl_pengajuan_pembayaran_pinjaman_id_anggota_foreign` FOREIGN KEY (`id_anggota`) REFERENCES `tbl_nasabah` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tbl_pengajuan_pembayaran_pinjaman_pinjaman_id_foreign` FOREIGN KEY (`pinjaman_id`) REFERENCES `tbl_pinjaman_h` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tbl_pengajuan_pembayaran_pinjaman_tempo_id_foreign` FOREIGN KEY (`tempo_id`) REFERENCES `tempo_pinjaman_b` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Recreate tbl_janji_temu_pinjaman with id_nasabah
CREATE TABLE `tbl_janji_temu_pinjaman` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_pengajuan` varchar(30) NOT NULL,
  `id_nasabah` bigint unsigned NOT NULL,
  `lokasi_temu` bigint unsigned NOT NULL,
  `nominal` decimal(15,2) NOT NULL,
  `tanggal_janji_temu` datetime NOT NULL,
  `waktu_janji_temu` time NOT NULL,
  `keterangan` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tbl_janji_temu_pinjaman_id_nasabah_index` (`id_nasabah`),
  KEY `tbl_janji_temu_pinjaman_lokasi_temu_foreign` (`lokasi_temu`),
  CONSTRAINT `tbl_janji_temu_pinjaman_id_nasabah_foreign` FOREIGN KEY (`id_nasabah`) REFERENCES `tbl_nasabah` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tbl_janji_temu_pinjaman_lokasi_temu_foreign` FOREIGN KEY (`lokasi_temu`) REFERENCES `jns_lokasi_perusahaan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Recreate tbl_janji_temu_pembayaran_pinjaman with id_nasabah
CREATE TABLE `tbl_janji_temu_pembayaran_pinjaman` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_pengajuan` varchar(30) NOT NULL,
  `id_nasabah` bigint unsigned NOT NULL,
  `lokasi_temu` bigint unsigned NOT NULL,
  `nominal` decimal(15,2) NOT NULL,
  `tanggal_janji_temu` datetime NOT NULL,
  `waktu_janji_temu` time NOT NULL,
  `keterangan` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tbl_janji_temu_pembayaran_pinjaman_id_nasabah_index` (`id_nasabah`),
  KEY `tbl_janji_temu_pembayaran_pinjaman_lokasi_temu_foreign` (`lokasi_temu`),
  CONSTRAINT `tbl_janji_temu_pembayaran_pinjaman_id_nasabah_foreign` FOREIGN KEY (`id_nasabah`) REFERENCES `tbl_nasabah` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tbl_janji_temu_pembayaran_pinjaman_lokasi_temu_foreign` FOREIGN KEY (`lokasi_temu`) REFERENCES `jns_lokasi_perusahaan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TAHAP 6: UPDATE JANJI TEMU DEPOSITO & GADAI (if exists)
-- =====================================================

-- NOTE: Skip this section if tables don't exist or already have id_nasabah column
-- You can manually run these ALTERs if needed:

-- Add id_nasabah to deposito janji temu if table exists
-- ALTER TABLE `tbl_janji_temu_deposito` 
-- ADD COLUMN IF NOT EXISTS `id_nasabah` bigint unsigned AFTER `deposito_id`,
-- ADD KEY IF NOT EXISTS `tbl_janji_temu_deposito_id_nasabah_index` (`id_nasabah`),
-- ADD CONSTRAINT IF NOT EXISTS `tbl_janji_temu_deposito_id_nasabah_foreign` FOREIGN KEY (`id_nasabah`) REFERENCES `tbl_nasabah` (`id`) ON DELETE CASCADE;

-- Rename catatan to keterangan
-- ALTER TABLE `tbl_janji_temu_deposito` CHANGE COLUMN `catatan` `keterangan` text;

-- Add id_nasabah to gadai janji temu if table exists
-- ALTER TABLE `tbl_janji_temu_gadai` 
-- ADD COLUMN IF NOT EXISTS `id_nasabah` bigint unsigned AFTER `gadai_id`,
-- ADD KEY IF NOT EXISTS `tbl_janji_temu_gadai_id_nasabah_index` (`id_nasabah`),
-- ADD CONSTRAINT IF NOT EXISTS `tbl_janji_temu_gadai_id_nasabah_foreign` FOREIGN KEY (`id_nasabah`) REFERENCES `tbl_nasabah` (`id`) ON DELETE CASCADE;

-- Rename catatan to keterangan
-- ALTER TABLE `tbl_janji_temu_gadai` CHANGE COLUMN `catatan` `keterangan` text;

-- =====================================================
-- TAHAP 7: CREATE VIEW JANJI TEMU UNIVERSAL
-- =====================================================

DROP VIEW IF EXISTS `v_janji_temu_universal`;

CREATE VIEW `v_janji_temu_universal` AS
SELECT 
    'TABUNGAN' as fitur,
    jt.id,
    jt.id_pengajuan as referensi_id,
    jt.id_nasabah,
    u.nama as nama_lengkap,
    u.email,
    u.nomor_hp,
    jt.lokasi_temu,
    l.nama_lokasi,
    jt.nominal,
    jt.tanggal_janji_temu,
    jt.waktu_janji_temu,
    COALESCE(jt.keterangan, '') as keterangan,
    jt.created_at,
    jt.updated_at
FROM tbl_janji_temu_tabungan jt
INNER JOIN tbl_nasabah n ON jt.id_nasabah = n.id
INNER JOIN users u ON n.user_id = u.id
INNER JOIN jns_lokasi_perusahaan l ON jt.lokasi_temu = l.id

UNION ALL

SELECT 
    'PINJAMAN' as fitur,
    jt.id,
    jt.id_pengajuan as referensi_id,
    jt.id_nasabah,
    u.nama as nama_lengkap,
    u.email,
    u.nomor_hp,
    jt.lokasi_temu,
    l.nama_lokasi,
    jt.nominal,
    jt.tanggal_janji_temu,
    CAST(jt.waktu_janji_temu AS DATETIME) as waktu_janji_temu,
    COALESCE(jt.keterangan, '') as keterangan,
    jt.created_at,
    jt.updated_at
FROM tbl_janji_temu_pinjaman jt
INNER JOIN tbl_nasabah n ON jt.id_nasabah = n.id
INNER JOIN users u ON n.user_id = u.id
INNER JOIN jns_lokasi_perusahaan l ON jt.lokasi_temu = l.id

UNION ALL

SELECT 
    'PEMBAYARAN PINJAMAN' as fitur,
    jt.id,
    jt.id_pengajuan as referensi_id,
    jt.id_nasabah,
    u.nama as nama_lengkap,
    u.email,
    u.nomor_hp,
    jt.lokasi_temu,
    l.nama_lokasi,
    jt.nominal,
    jt.tanggal_janji_temu,
    CAST(jt.waktu_janji_temu AS DATETIME) as waktu_janji_temu,
    COALESCE(jt.keterangan, '') as keterangan,
    jt.created_at,
    jt.updated_at
FROM tbl_janji_temu_pembayaran_pinjaman jt
INNER JOIN tbl_nasabah n ON jt.id_nasabah = n.id
INNER JOIN users u ON n.user_id = u.id
INNER JOIN jns_lokasi_perusahaan l ON jt.lokasi_temu = l.id

ORDER BY tanggal_janji_temu DESC, created_at DESC;

-- =====================================================
-- TAHAP 8: RE-ENABLE Foreign Key Checks
-- =====================================================

SET FOREIGN_KEY_CHECKS=1;

-- =====================================================
-- SELESAI!
-- =====================================================
-- Tables yang sudah diubah:
-- ✅ jns_fitur, jns_via, jns_transaksi (BARU)
-- ✅ tbl_bukti_foto (UNIVERSAL - BARU)
-- ✅ tbl_pengajuan_tabungan (Complex ID)
-- ✅ tbl_pengajuan_penarikan_tabungan (Complex ID)
-- ✅ trans_tabungan (Complex ID, FK baru)
-- ✅ tbl_janji_temu_tabungan (id_nasabah added)
-- ✅ tbl_pengajuan_pinjaman (Complex ID)
-- ✅ tbl_pinjaman_h (Complex ID, columns removed)
-- ✅ tempo_pinjaman_b (Complex ID, anggota_id removed)
-- ✅ tbl_pengajuan_pembayaran_pinjaman (Complex ID, FK fixed)
-- ✅ tbl_janji_temu_pinjaman (id_nasabah added)
-- ✅ tbl_janji_temu_pembayaran_pinjaman (id_nasabah added)
-- ✅ v_janji_temu_universal (VIEW CREATED)
-- =====================================================
