# Migration Refactoring Plan

## Overview
Refactor migration files untuk membuat struktur database yang lebih clean dan konsisten, khususnya untuk sistem janji temu.

## Changes Summary

###  1. Janji Temu Structure Changes
**OLD:**
- Table: `tbl_janji_temu_tabungan`
- Kolom: `id_pengajuan` VARCHAR(30) NULL
- Problem: Inconsistent, kadang NULL kadang terisi

**NEW:**
- Table: `tbl_janji_temu_tabungan`
- Hapus kolom: `id_pengajuan`
- Tambah kolom:
  - `id` VARCHAR(30) PK (generated) 
  - `id_jns_fitur` BIGINT UNSIGNED FK
  - `id_jns_via` BIGINT UNSIGNED FK  
  - `id_jns_transaksi` BIGINT UNSIGNED FK
  - `keterangan_admin` TEXT NULL
  - `status` ENUM('1','2','3') DEFAULT '1'

### 2. Master Data Updates
**jns_transaksi** perlu tambah data:
- JNJT: Janji Temu

**jns_via** perlu update:
- TF: Transfer (sebelumnya T)
- CS: Cash (sebelumnya C)

### 3. ID Generation Format
All janji temu IDs:
```
DDMMYYYYNNNNTCJNJT
│       │    ││ └─ Trans (JNJT)
│       │    │└─ Via (CS/TF)
│       │    └─ Fitur (T/P/G/D)
│       └─ Sequence (0001)
└─ Date (04022026)
```

## Migration Steps

1. Backup existing migrations folder
2. Create fresh migrations:
   - `2024_01_01_000001_create_users_tables.php`
   - `2024_01_01_000002_create_master_data_tables.php`
   - `2024_01_01_000003_create_tabungan_tables.php`
   - `2024_01_01_000004_create_pinjaman_tables.php`
   - `2024_01_01_000005_create_janji_temu_tables.php`
   - `2024_01_01_000006_create_views.php`

3. Update seeders:
   - `JnsTransaksiSeeder` - tambah JNJT
   - `JnsViaSeeder` - update TF/CS instead T/C

4. Drop & recreate database
5. Run fresh migrations
6. Run seeders

## Breaking Changes

⚠️ **WARNING**: This is a breaking change!
- Existing janji temu data will be LOST
- ID format completely changes
- Need to update:
  - Controllers (ID generation)
  - Models (relationships)
  - Views/templates
  - IdGenerator helper

## Implementation

User requested to:
1. Delete all current migration files
2. Create fresh migrations based on current database structure
3. Apply the new janji temu structure

### Next Steps:
1. [ ] Create backup of current migrations
2. [ ] Delete current migrations folder
3. [ ] Create new migration files
4. [ ] Update seeders
5. [ ] Update IdGenerator helper
6. [ ] Update controllers
7. [ ] Test fresh install

