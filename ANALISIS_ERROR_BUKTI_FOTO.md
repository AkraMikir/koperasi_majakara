# Analisis Error Upload Bukti Transfer

## Deskripsi Error
**Pesan Error:** 
`SQLSTATE[HY000]: General error: 1364 Field 'id' doesn't have a default value (Connection: mysql, SQL: insert into tbl_bukti_foto ...)`

**Konteks:** 
Terjadi saat nasabah mencoba melakukan "Pengajuan Setoran Transfer" dan mengupload bukti transfer.

## Penyebab Masalah
1.  **Struktur Database (`tbl_bukti_foto`)**:
    -   Kolom `id` didefinisikan sebagai `string(30)` dan merupakan Primary Key.
    -   Kolom ini **tidak auto-increment**.
    -   Kolom ini **tidak memiliki default value** (NOT NULL).
2.  **Kode Aplikasi (Model/Controller)**:
    -   Saat menyimpan data ke tabel `tbl_bukti_foto`, aplikasi tidak menyertakan nilai untuk kolom `id`.
    -   Database menolak insert karena kolom wajib `id` kosong.

## Solusi Perbaikan

### Opsi 1: Generate ID Otomatis di Model (Reccomended)
Menambahkan logic pada model `App\Models\BuktiFoto` untuk secara otomatis mengisi kolom `id` saat record baru dibuat. Ini memastikan konsistensi di seluruh aplikasi setiap kali model ini digunakan.

**File:** `app/Models/BuktiFoto.php`

```php
protected static function booted()
{
    static::creating(function ($model) {
        if (empty($model->id)) {
            $model->id = (string) \Illuminate\Support\Str::uuid()->toString();
            // Atau gunakan format custom jika diperlukan panjang 30 char
            // $model->id = \Illuminate\Support\Str::random(30);
        }
    });
}
```

### Opsi 2: Set ID Manual di Controller
Mengupdate kode di controller yang menangani upload bukti transfer untuk men-generate ID secara manual sebelum menyimpan.

**Contoh:**
```php
$buktiFoto = new BuktiFoto();
$buktiFoto->id = Str::random(30); // Tambahkan baris ini
// ... set property lain
$buktiFoto->save();
```

## Rekomendasi
Disarankan menggunakan **Opsi 1** karena lebih robust dan mencegah error serupa di fitur lain yang menggunakan tabel `tbl_bukti_foto`.
