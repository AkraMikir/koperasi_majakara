# Tabel Fitur Terbaru (V2 Refactoring)

Dokumen ini menjelaskan struktur database terbaru yang menggunakan ID Complex (String) dan Master Data terpusat.

## 1. Master Data
Digunakan untuk standarisasi kode dalam ID dan Logic.

### A. Jenis Fitur (`jns_fitur`)
| ID | Kode | Nama | Keterangan |
| :--- | :--- | :--- | :--- |
| 1 | `T` | Tabungan | Fitur Simpanan/Tabungan |
| 2 | `P` | Pinjaman | Fitur Pinjaman/Kredit |
| 3 | `D` | Deposito | - |
| 4 | `G` | Gadai | - |

### B. Jenis Via (`jns_via`)
| ID | Kode | Nama | Keterangan |
| :--- | :--- | :--- | :--- |
| 1 | `T` | Transfer | Via Bank Transfer |
| 2 | `C` | Cash | Via Tunai / Janji Temu |

### C. Jenis Transaksi (`jns_transaksi`)
| ID | Kode | Nama | Keterangan |
| :--- | :--- | :--- | :--- |
| 1 | `STR` | Setoran | Setoran Tabungan |
| 2 | `PNR` | Penarikan | Penarikan Tabungan |
| 3 | `TRKT` | Transaksi Tabungan| General transaksi tabungan |
| 4 | `PNJ` | Pengajuan Pinjaman| - |
| 5 | `CAIR`| Pencairan Pinjaman| - |
| 6 | `PMB` | Pembayaran Pinjaman| Bayar angsuran |
| 7 | `DPNJM`| Detail Pinjaman Header| Kode untuk ID Pinjaman Header |
| 8 | `TPNJM`| Tempo Pinjaman | Kode untuk ID Tempo / Jadwal |

---

## 2. Struktur ID (String Complex)
Format: `DDMMYYYY` + `SEQ(4)` + `FITUR` + `VIA` + `TRANS`
Contoh: `300120260001TTSTR`

- `30012026`: Tanggal 30 Jan 2026
- `0001`: Sequence ke-1 hari itu
- `T`: Fitur Tabungan
- `T`: Via Transfer
- `STR`: Transaksi Setoran

---

## 3. Tabel Transaksi Utama

### A. Pengajuan Tabungan (`tbl_pengajuan_tabungan`)
| Kolom | Tipe | Keterangan |
| :--- | :--- | :--- |
| `id` | VARCHAR(30) | **PK**. ID Complex. Contoh: `...TTSTR` |
| `id_anggota` | BIGINT | FK ke `tbl_nasabah` |
| `nominal` | DECIMAL | Jumlah uang |
| `status` | CHAR(1) | 1=Pending, 2=Rejected, 3=Approved |
| `keterangan` | TEXT | - |
| `foto_bukti_tf` | **DELETED** | Pindah ke `tbl_bukti_foto` |

### B. Transaksi Tabungan (`trans_tabungan`)
| Kolom | Tipe | Keterangan |
| :--- | :--- | :--- |
| `id` | VARCHAR(30) | **PK**. ID Complex. Contoh: `...TTTRKT` |
| `id_jns_trans` | BIGINT | FK ke `jns_transaksi` (STR/PNR) |
| `id_via` | BIGINT | FK ke `jns_via` (T/C) |
| `nominal` | DECIMAL | - |

### C. Pinjaman Header (`tbl_pinjaman_h`)
| Kolom | Tipe | Keterangan |
| :--- | :--- | :--- |
| `id` | VARCHAR(30) | **PK**. ID Complex. Contoh: `...PTDPNJM` |
| `lunas` | ENUM | 'belum', 'lunas'. (Status aktif cek ini) |
| `status` | **DELETED** | - |
| `ags_bulan` | DECIMAL | **NEW**. Pokok angsuran per bulan. |

### D. Tempo Pinjaman (`tempo_pinjaman_b`)
| Kolom | Tipe | Keterangan |
| :--- | :--- | :--- |
| `id` | VARCHAR(30) | **PK**. ID Complex. Contoh: `...PTTPNJM` |
| `pinjaman_id` | VARCHAR(30)| FK ke `tbl_pinjaman_h` |
| `anggota_id` | **DELETED** | Gunakan relasi `pinjaman->id_anggota` |

---

## 4. Tabel Universal Foto (`tbl_bukti_foto`)
Satu tabel untuk menyimpan semua foto bukti dari fitur apapun.

| Kolom | Keterangan |
| :--- | :--- |
| `owner_id` | ID dari `pengajuan_tabungan`, `pinjaman`, dll |
| `owner_fitur` | Kode Fitur (T, P, D, G) |
| `owner_trans` | Kode Transaksi (STR, PNJ, dll) |
| `file_path` | Lokasi file |

**Cara Simpan:**
Saat upload foto untuk Pengajuan Tabungan (ID: `...TTSTR`):
- `owner_id`: `...TTSTR`
- `owner_fitur`: `T`
- `owner_trans`: `STR`

---

## 5. View Janji Temu Universal (`v_janji_temu_universal`)
View gabungan dari `tbl_janji_temu_tabungan` dan `tbl_janji_temu_pinjaman` untuk mempermudah Admin melihat jadwal temu.

Columns: `id_view`, `fitur`, `nama_anggota`, `tanggal`, `waktu`, `nominal`, `lokasi`, `keterangan`.
