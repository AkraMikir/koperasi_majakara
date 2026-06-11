-- =============================================================================
-- MySQL Init Script — Koperasi Majakara
-- Dijalankan sekali saat MySQL container pertama kali dibuat
-- =============================================================================

-- Pastikan database ada dengan charset yang benar
CREATE DATABASE IF NOT EXISTS `koperasi_majakara`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

-- Gunakan database
USE `koperasi_majakara`;

-- ---- Konfigurasi MySQL untuk Laravel ----
SET GLOBAL sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
