# Analisis Error Subquery Returns More Than 1 Row

## Deskripsi Error
**Pesan Error:** `SQLSTATE[21000]: Cardinality violation: 1242 Subquery returns more than 1 row`

**Lokasi:** `App\Http\Controllers\Nasabah\DashboardController::getNotifikasiPenting`

## Penyebab
Query SQL yang digunakan dalam method `getNotifikasiPenting` mencoba membandingkan kolom `pinjaman_id` dengan sebuah subquery menggunakan operator `=`.

```sql
... WHERE `pinjaman_id` = (SELECT `id` FROM `tbl_pinjaman_h` WHERE `id_anggota` = 1) ...
```

Masalahnya adalah subquery `(SELECT id FROM tbl_pinjaman_h WHERE id_anggota = 1)` dapat (dan seharusnya) mengembalikan **banyak baris** jika seorang anggota memiliki lebih dari satu pinjaman. Operator `=` hanya bisa digunakan jika subquery dipastikan mengembalikan tepat satu baris.

Ketika anggota memiliki 2 atau lebih pinjaman aktif, database memberikan error karena tidak tahu ID mana yang harus dicocokkan.

## Solusi
Ubah logika query untuk menggunakan relationship Eloquent `whereHas` atau operator `IN` dalam SQL.

**Salah (Kode Saat Ini):**
```php
$angsuranTelatB = TempoPinjamanB::where('pinjaman_id', function($q) use ($idAnggota) {
    $q->select('id')->from('tbl_pinjaman_h')->where('id_anggota', $idAnggota);
})
// ...
```

**Benar (Perbaikan):**
Gunakan `whereHas` untuk memanfaatkan relasi yang sudah didefinisikan di model `TempoPinjamanB`.

```php
$angsuranTelatB = TempoPinjamanB::whereHas('pinjaman', function($q) use ($idAnggota) {
    $q->where('id_anggota', $idAnggota);
})
// ...
```
Atau jika ingin tetap manual tanpa relasi model:
```php
$angsuranTelatB = TempoPinjamanB::whereIn('pinjaman_id', function($q) use ($idAnggota) {
    $q->select('id')->from('tbl_pinjaman_h')->where('id_anggota', $idAnggota);
})
// ...
```

Solusi ini akan menangani kasus dimana anggota memiliki banyak pinjaman dengan benar. Tidak ada perubahan struktur database yang diperlukan.
