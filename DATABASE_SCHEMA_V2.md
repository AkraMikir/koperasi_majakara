# Database Schema V2 (Refactored)

Dokumen ini berisi daftar perubahan tabel dan kolom utama yang harus dipatuhi oleh Controller dan View.

## 1. Transaksi Tabungan (`trans_tabungan`)
Tabel untuk mencatat mutasi saldo.

| Kolom Lama | Status | Kolom Baru / Pengganti | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` (Auto Inc) | **CHANGED** | `id` (CHAR 17) | Format: `SEQ..DATE..FITUR..` |
| `jenis` (enum) | **DELETED** | `id_jns_trans` | FK ke `jns_transaksi`. Gunakan kode 'STR', 'PNR'. |
| `via` (enum) | **DELETED** | `id_via` | FK ke `jns_via`. Gunakan kode 'TF', 'TN'. |
| `id_jns_akun` | **DELETED** | - | Tidak dipakai lagi. Identitas transaksi via `id_jns_trans`. |
| `tgl_transaksi` | KEEP | `tgl_transaksi` | Timestamp. |

**Cara Query Baru:**
```php
// Query Setoran
TransTabungan::whereHas('jnsTransaksi', fn($q) => $q->where('kode', 'STR'))->get();

// Query Via Transfer
TransTabungan::whereHas('jnsVia', fn($q) => $q->where('kode', 'TF'))->get();
```

## 2. Pinjaman Header (`tbl_pinjaman_h`)
Tabel pinjaman yang **sudah cair/aktif**.

| Kolom Lama | Status | Kolom Baru / Pengganti | Keterangan |
| :--- | :--- | :--- | :--- |
| `status` | **DELETED** | - | Jika data ada di tabel ini, artinya status sudah aktif/cair. Cek kelunasan pakai kolom `lunas`. |
| `saldo_lebih` | **DELETED** | - | Dihapus. Kelebihan bayar dicatat terpisah/manual. |
| `ags_bulan` | **DELETED** | - | Dihitung dari `jumlah_pinjam / lama_pinjam`. |
| `foto*` | **MOVED** | `tbl_bukti_foto` | Semua foto pindah ke tabel universal `tbl_bukti_foto`. |

**Cara Query Baru:**
```php
// Pinjaman Aktif (Belum Lunas)
// Hapus whereIn('status') karena status column sudah tidak ada!
PinjamanH::where('lunas', 'belum')->get(); 
```

## 3. Tempo Pinjaman (`tempo_pinjaman_b`)
Jadwal angsuran bulanan.

| Kolom Lama | Status | Kolom Baru / Pengganti | Keterangan |
| :--- | :--- | :--- | :--- |
| `anggota_id` | **DELETED** | Relasi via `pinjaman` | `tempo->pinjaman->id_anggota` |
| `pinjaman_id` | KEEP | `pinjaman_id` (CHAR) | FK ke `tbl_pinjaman_h` (String ID). |

**Cara Query Baru:**
```php
// Salah
TempoPinjamanB::where('anggota_id', $id)->get();

// Benar
TempoPinjamanB::whereHas('pinjaman', fn($q) => $q->where('id_anggota', $id))->get();
```

## 4. Pengajuan Tabungan (`tbl_pengajuan_tabungan`)

| Kolom Lama | Status | Kolom Baru / Pengganti | Keterangan |
| :--- | :--- | :--- | :--- |
| `foto_bukti_tf` | **MOVED** | `tbl_bukti_foto` | Relasi polymorphic `BuktiFoto`. |
| `status` | KEEP | `status` (CHAR 1) | 1=Pending, 2=Rejected, 3=Approved, 4=Completed. |

## 5. Master Data
Tabel-tabel master baru:
- `jns_fitur` (kode: T, P, D, G)
- `jns_via` (kode: TF=Transfer, TN=Tunai)
- `jns_transaksi` (kode: STR, PNR, BYR, CAIR)
- `master_bunga_pinjaman` (Restored)
- `master_denda_pinjaman` (Restored)

Tabel dihapus:
- `jns_akun` (Ganti logic di code)
- `jns_deposito` (Ganti logic)
- `suku_bunga` (Use `master_bunga_pinjaman`)
