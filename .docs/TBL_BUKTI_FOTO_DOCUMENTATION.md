# tbl_bukti_foto - Universal Photo Evidence Table

## Overview
Table universal untuk menyimpan bukti foto dari semua fitur transaksi di sistem koperasi.

---

## Table Structure

```sql
CREATE TABLE tbl_bukti_foto (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    owner_id VARCHAR(30) NOT NULL,
    owner_fitur VARCHAR(10) NOT NULL,
    owner_trans VARCHAR(20) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    keterangan TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    INDEX idx_owner (owner_id, owner_fitur, owner_trans)
);
```

---

## Field Description

| Field | Type | Description | Example |
|-------|------|-------------|---------|
| `id` | BIGINT | Auto-increment primary key | 1, 2, 3... |
| `owner_id` | VARCHAR(30) | ID dari record pemilik foto | `04022026TCSJNJT` |
| `owner_fitur` | VARCHAR(10) | Kode fitur pemilik | `T`, `P`, `G`, `D` |
| `owner_trans` | VARCHAR(20) | Kode tipe transaksi | `JNJT`, `STR`, `PNR`, `PNJ` |
| `file_path` | VARCHAR(255) | Path file di storage | `bukti_tabungan/abc123.jpg` |
| `keterangan` | TEXT | Keterangan opsional | `Bukti transfer setoran` |
| `created_at` | TIMESTAMP | Waktu upload | |
| `updated_at` | TIMESTAMP | Waktu update terakhir | |

---

## Field Values

### owner_fitur
```
T = Tabungan
P = Pinjaman
G = Gadai
D = Deposito
```

### owner_trans (Examples)
```
Tabungan:
- STR   = Setoran
- PNR   = Penarikan
- TRKT  = Transaksi Tabungan
- JNJT  = Janji Temu

Pinjaman:
- PNJ   = Pengajuan
- PMB   = Pembayaran
- DPNJM = Data Pinjaman
- TPNJM = Tempo Pinjaman
```

---

## Usage Examples

### 1. Upload Bukti Janji Temu Tabungan

```php
use App\Models\BuktiFoto;

$janjiTemuId = '04022026TCSJNJT';

if ($request->hasFile('foto_penerimaan')) {
    foreach ($request->file('foto_penerimaan') as $file) {
        $path = $file->store('bukti_tabungan', 'public');
        
        BuktiFoto::create([
            'owner_id' => $janjiTemuId,
            'owner_fitur' => 'T',      // Tabungan
            'owner_trans' => 'JNJT',   // Janji Temu
            'file_path' => $path,
            'keterangan' => 'Bukti penerimaan janji temu',
        ]);
    }
}
```

### 2. Upload Bukti Pengajuan Pinjaman

```php
$pengajuanId = '04022026PCSPNJ001';

BuktiFoto::create([
    'owner_id' => $pengajuanId,
    'owner_fitur' => 'P',      // Pinjaman
    'owner_trans' => 'PNJ',    // Pengajuan
    'file_path' => 'bukti_pinjaman/slip.jpg',
    'keterangan' => 'Slip gaji pemohon',
]);
```

### 3. Retrieve Foto by Owner

```php
// Get all photos for a specific janji temu
$photos = BuktiFoto::where('owner_id', '04022026TCSJNJT')
                   ->where('owner_fitur', 'T')
                   ->where('owner_trans', 'JNJT')
                   ->get();

foreach ($photos as $photo) {
    $url = Storage::url($photo->file_path);
    echo "<img src='{$url}' alt='{$photo->keterangan}'>";
}
```

### 4. Count Photos

```php
$photoCount = BuktiFoto::where('owner_id', $janjiTemuId)
                       ->where('owner_fitur', 'T')
                       ->count();
```

---

## Model Definition

**File:** `app/Models/BuktiFoto.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuktiFoto extends Model
{
    use HasFactory;

    protected $table = 'tbl_bukti_foto';

    protected $fillable = [
        'owner_id',
        'owner_fitur',
        'owner_trans',
        'file_path',
        'keterangan',
    ];
}
```

---

## Benefits of Universal Table

✅ **Single Source of Truth**
- All photo evidence in one table
- Easy to query and manage

✅ **Flexible**
- Works for all features (tabungan, pinjaman, gadai, deposito)
- Extensible for future transaction types

✅ **Performance**
- Indexed columns for fast queries
- Composite index on (owner_id, owner_fitur, owner_trans)

✅ **Consistency**
- Same structure across all features
- Easy to maintain

---

## Migration Location

**File:** `database/migrations/2024_01_01_000003_create_tabungan_tables.php`

The table is created in the tabungan migration but is designed to be universal for all features.

---

## Storage Location

Photos are stored in Laravel's `storage/app/public/` directory:

```
storage/
└── app/
    └── public/
        ├── bukti_tabungan/    ← Tabungan photos
        ├── bukti_pinjaman/    ← Pinjaman photos
        ├── bukti_gadai/       ← Gadai photos
        └── bukti_deposito/    ← Deposito photos
```

To access publicly:
```php
$url = Storage::url($photo->file_path);
// Returns: /storage/bukti_tabungan/abc123.jpg
```

---

## Best Practices

1. **Always specify all 3 identifiers:**
   - `owner_id` - The record ID
   - `owner_fitur` - Feature code
   - `owner_trans` - Transaction type

2. **Use descriptive keterangan:**
   ```php
   'keterangan' => 'Bukti transfer setoran tanggal 04/02/2026'
   ```

3. **Validate file uploads:**
   ```php
   'foto.*' => 'image|max:5120'  // Max 5MB per file
   ```

4. **Delete old files when deleting records:**
   ```php
   $photos = BuktiFoto::where('owner_id', $id)->get();
   foreach ($photos as $photo) {
       Storage::delete($photo->file_path);
       $photo->delete();
   }
   ```

---

**Created:** 2026-02-04
**Version:** 1.0
**Status:** Active ✅
