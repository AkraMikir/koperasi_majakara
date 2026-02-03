-- Disable FK checks
SET FOREIGN_KEY_CHECKS=0;

-- Fix Collation for Core Tables
ALTER TABLE `tbl_pengajuan_tabungan` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `trans_tabungan` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `tbl_pinjaman_h` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `tempo_pinjaman_b` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `tbl_janji_temu_tabungan` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `tbl_janji_temu_pinjaman` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `tbl_janji_temu_pembayaran_pinjaman` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Fix Collation for Related Tables involved in View
ALTER TABLE `tbl_pengajuan_pinjaman` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `tbl_nasabah` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `users` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `jns_lokasi_perusahaan` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Fix ID Columns in Janji Temu to be compatible with String ID (just in case)
ALTER TABLE `tbl_janji_temu_tabungan` MODIFY COLUMN `id_pengajuan` VARCHAR(30) NOT NULL;
ALTER TABLE `tbl_janji_temu_pinjaman` MODIFY COLUMN `id_pengajuan` VARCHAR(30) NOT NULL;
ALTER TABLE `tbl_janji_temu_pembayaran_pinjaman` MODIFY COLUMN `id_pengajuan` VARCHAR(30) NOT NULL;

-- Create View Janji Temu Universal
CREATE OR REPLACE VIEW `v_janji_temu_universal` AS
SELECT 
    UUID() as id_view,
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

SET FOREIGN_KEY_CHECKS=1;
