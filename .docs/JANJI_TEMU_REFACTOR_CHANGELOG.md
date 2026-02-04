# Pembaharuan Sistem Janji Temu - Changelog

## Tanggal: 2026-02-04

### Summary Perubahan Besar

Melakukan refactoring complete pada sistem janji temu untuk memisahkannya dari pengajuan tabungan dan membuat struktur yang lebih clean.

---

## 1. Perubahan Database Structure

### Janji Temu Tables (All)
**BEFORE:**
```sql
CREATE TABLE tbl_janji_temu_tabungan (
    id BIGINT UNSIGNED AUTO_INCREMENT,
    id_pengajuan VARCHAR(30) NULL,
    ...
);
```

**AFTER:**
```sql
CREATE TABLE tbl_janji_temu_tabungan (
    id VARCHAR(30) PRIMARY KEY,  -- Generated ID
    id_nasabah BIGINT UNSIGNED,
    ...
    keterangan_admin TEXT NULL,  -- NEW
    status ENUM('1','2','3') DEFAULT '1'  -- NEW
);
```

**Perubahan:**
- ✅ `id` changed to VARCHAR(30) PRIMARY KEY (generated)
- ✅ Removed `id_pengajuan` column
- ✅ Added `keterangan_admin` TEXT
- ✅ Added `status` ENUM for tracking (1=Menunggu, 2=Selesai, 3=Batal)

**Impacted Tables:**
- `tbl_janji_temu_tabungan`
- `tbl_janji_temu_pinjaman`
- `tbl_janji_temu_pembayaran_pinjaman`

---

## 2. Master Data Updates

### jns_fitur
```
T = Tabungan
P = Pinjaman
G = Gadai
D = Deposito
```

### jns_via
**BEFORE:** `T` (Transfer), `TN` (Tunai)
**AFTER:** `TF` (Transfer), `CS` (Cash)

### jns_transaksi (UPDATED)
```
STR    = Setoran
PNR    = Penarikan
TRKT   = Transaksi Tabungan
PNJ    = Pengajuan (Pinjaman)
PMB    = Pembayaran (Pinjaman)
DPNJM  = Data Pinjaman
TPNJM  = Tempo Pinjaman
JNJT   = Janji Temu  ← NEW!
```

**File:** `database/seeders/MasterDataSeeder.php`

---

## 3. ID Generation Format

All janji temu now use generated IDs:

**Format:** `DDMMYYYYNNNNTJTSTR`

**Example:** `04022026001TJTSTR`
- `04022026` = Date (04 Feb 2026)
- `001` = Sequence number
- `T` = Tabungan (fitur)
- `JT` = Janji Temu (type)
- `STR` = Setoran (transaction type)

**Generator:** Uses existing `IdGenerator` helper

---

## 4. Model Updates

### JanjiTemuTabungan.php

**Changes:**
```php
class JanjiTemuTabungan extends Model
{
    // NEW: Non-incrementing string ID
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',  // NEW
        'id_nasabah',
        'lokasi_temu',
        'nominal',
        'tanggal_janji_temu',
        'waktu_janji_temu',
        'keterangan',
        'keterangan_admin',  // NEW
        'status',  // NEW
    ];
    
    // REMOVED: pengajuan() relation
    // REMOVED: transTabungan() relation
}
```

**Similar changes made to:**
- `JanjiTemuPinjaman.php`
- `JanjiTemuPembayaranPinjaman.php`

---

## 5. Controller Updates

### Nasabah/TabunganController.php

**submitJanjiTemu() method:**
```php
// Generate ID
$id = IdGenerator::generate('tbl_janji_temu_tabungan', 'T', 'JT', 'STR');

JanjiTemuTabungan::create([
    'id' => $id,  // Generated
    'id_nasabah' => $idAnggota,
    'lokasi_temu' => $request->lokasi_temu,
    'nominal' => $request->nominal,
    'tanggal_janji_temu' => $tanggalJanjiTemu,
    'waktu_janji_temu' => $waktuJanjiTemu,
    'keterangan' => $request->keterangan,
    'status' => '1',  // Default: Menunggu
]);
```

**Other query fixes:**
- Line 42: Simplified query, direct `where('id_nasabah', $idAnggota)`
- Removed all references to `pengajuan` relation

### Admin/TabunganController.php

**janjiTemu() method:**
```php
$query = JanjiTemuTabungan::with(['nasabah.user', 'nasabah.dataKtp', 'lokasi'])
    ->latest('tanggal_janji_temu');
```

**detailJanjiTemu() method:**
```php
$janjiTemu = JanjiTemuTabungan::with([
    'nasabah.user', 'nasabah.dataKtp', 'nasabah.dataRek', 'lokasi',
])->findOrFail($id);
```

**createTransFromJanjiTemu() method - COMPLETE REWRITE:**
```php
public function createTransFromJanjiTemu(Request $request, $id)
{
    $janjiTemu = JanjiTemuTabungan::with(['nasabah'])->findOrFail($id);

    // Check if already processed (status = 2)
    if ($janjiTemu->status == '2') {
        return redirect()->back()->with('error', 'Sudah diproses');
    }

    $idAnggota = $janjiTemu->id_nasabah;
    $nominal = $request->nominal ?? $janjiTemu->nominal;

    // Handle multiple foto penerimaan
    if ($request->hasFile('foto_penerimaan')) {
        foreach ($request->file('foto_penerimaan') as $file) {
            $fotoPenerimaan = $file->store('bukti_tabungan', 'public');
            BuktiFoto::create([
                'owner_id' => $janjiTemu->id,
                'owner_fitur' => 'T',  // Tabungan
                'owner_trans' => 'JNJT',  // Janji Temu
                'file_path' => $fotoPenerimaan,
                'keterangan' => 'Bukti penerimaan janji temu',
            ]);
        }
    }

    // Update janji temu status
    $janjiTemu->update([
        'status' => '2',  // Selesai
        'keterangan_admin' => $request->keterangan_admin,
    ]);

    // Create transaksi tabungan
    $kodeVia = 'CS';  // Cash
    $kodeTrans = 'STR';  // Setoran
    $idVia = DB::table('jns_via')->where('kode', $kodeVia)->value('id');
    $idTrans = DB::table('jns_transaksi')->where('kode', $kodeTrans)->value('id');
    $idTransaksi = IdGenerator::generate('trans_tabungan', 'T', $kodeVia, $kodeTrans);

    TransTabungan::create([
        'id' => $idTransaksi,
        'id_pengajuan_setor' => null,  // Tidak ada pengajuan
        'id_pengajuan_tarik' => null,
        'id_anggota' => $idAnggota,
        'id_jns_via' => $idVia,
        'id_jns_transaksi' => $idTrans,
        'nominal' => $nominal,
        'keterangan' => 'Setoran tunai via janji temu #' . $janjiTemu->id,
        'tgl_transaksi' => now(),
    ]);

    return redirect()->route('admin.tabungan.janji-temu')
        ->with('success', 'Transaksi berhasil dibuat!');
}
```

**Key Changes:**
- ✅ No longer references `pengajuan`
- ✅ Uses `BuktiFoto` universal table for photos
- ✅ Updates janji temu `status` to '2' (Selesai)
- ✅ Creates `trans_tabungan` with null pengajuan IDs
- ✅ Uses janji temu `keterangan` (nasabah) for transaction
- ✅ Stores admin notes in `keterangan_admin`

---

## 6. View Updates

### admin/tabungan/detail-janji-temu.blade.php

**Major Changes:**

1. **Removed Pengajuan References:**
   ```blade
   // BEFORE
   $nasabah = $janjiTemu->pengajuan?->nasabah ?? $janjiTemu->nasabah;
   
   // AFTER
   $nasabah = $janjiTemu->nasabah;
   ```

2. **Status Display:**
   ```blade
   @php
       $statusConfig = [
           '1' => ['label' => 'Menunggu', 'color' => 'bg-yellow-100 text-yellow-700'],
           '2' => ['label' => 'Selesai', 'color' => 'bg-green-100 text-green-700'],
           '3' => ['label' => 'Batal', 'color' => 'bg-red-100 text-red-700'],
       ];
       $status = $statusConfig[$janjiTemu->status] ?? $statusConfig['1'];
   @endphp
   ```

3. **Form: Multiple Photo Upload dengan Button Add:**
   ```blade
   <div id="foto-container" class="space-y-2">
       <div class="foto-upload-item flex gap-2">
           <input type="file" name="foto_penerimaan[]" accept="image/*" class="flex-1 ...">
           <button type="button" onclick="removeFotoInput(this)" class="hidden ...">×</button>
       </div>
   </div>
   <button type="button" onclick="addFotoInput()" class="...">+ Tambah Foto</button>
   ```

4. **JavaScript Functions:**
   ```js
   function addFotoInput() {
       // Dynamically add new file input
   }
   
   function removeFotoInput(button) {
       // Remove file input (min 1 must remain)
   }
   ```

5. **Form Fields:**
   - Nominal (currency formatted)
   - Foto Penerimaan[] (multiple with add button)
   - Keterangan Admin (textarea) ← NEW

6. **Keterangan Display:**
   ```blade
   @if($janjiTemu->keterangan_admin)
   <div class="mt-4 p-3 bg-white rounded-lg">
       <p class="text-xs text-gray-600 mb-1">Keterangan Admin:</p>
       <p class="text-sm text-gray-900">{{ $janjiTemu->keterangan_admin }}</p>
   </div>
   @endif
   ```

---

## 7. Migration Files (Clean Structure)

**Backup:** All old migrations → `database/migrations_backup_20260204_213403/`

**New Clean Migrations:**
```
2024_01_01_000000_create_core_tables.php
2024_01_01_000001_create_nasabah_tables.php
2024_01_01_000002_create_master_tables.php
2024_01_01_000003_create_tabungan_tables.php  ← UPDATED
2024_01_01_000004_create_pinjaman_tables.php  ← UPDATED
2024_01_01_000005_create_gadai_deposito_tables.php
2024_01_01_000006_create_views.php  ← UPDATED
+ Additional migrations (OTP, tempo_pinjaman_m, etc)
```

**Updated View:** `v_janji_temu_universal`
```sql
CREATE OR REPLACE VIEW v_janji_temu_universal AS 
SELECT 
    UUID() AS id_view,
    'Tabungan' AS fitur,
    jt.id AS id_asli,  -- Now using generated ID
    jt.id_nasabah AS id_anggota,
    u.nama AS nama_anggota,
    jt.tanggal_janji_temu,
    jt.waktu_janji_temu,
    jt.nominal,
    jl.nama_lokasi AS lokasi,
    jt.keterangan,
    jt.keterangan_admin,  -- NEW
    jt.status,  -- NEW
    jt.created_at
FROM tbl_janji_temu_tabungan jt
JOIN tbl_nasabah n ON jt.id_nasabah = n.id  -- Direct join
JOIN users u ON n.user_id = u.id
JOIN jns_lokasi_perusahaan jl ON jt.lokasi_temu = jl.id
...
```

---

## 8. Testing Checklist

### Nasabah Flow:
- [ ] Buat janji temu baru → verify ID generated correctly
- [ ] Check status janji temu page → verify data muncul
- [ ] Verify tidak muncul di pengajuan setor page

### Admin Flow:
- [ ] Access "Janji Temu Universal" → verify data muncul
- [ ] Click detail janji temu → verify no errors
- [ ] Test form buat transaksi:
  - [ ] Add multiple photos
  - [ ] Fill keterangan admin
  - [ ] Submit form
- [ ] Verify transaksi created in `trans_tabungan`
- [ ] Verify janji temu status changed to '2'
- [ ] Verify keterangan_admin stored

---

## 9. Command History

```bash
# Backup migrations
mkdir database\migrations_backup_$(date)
move database\migrations\* backup/

# Fresh migration
php artisan migrate:fresh

# Seed master data
php artisan db:seed --class=MasterDataSeeder
```

---

## 10. Breaking Changes

⚠️ **WARNING: This is a breaking change!**

**Data Loss:**
- All existing janji temu data will be lost with fresh migration
- ID format completely changes

**Code Updates Required:**
- Any code referencing `janjiTemu->pengajuan` must be updated
- Any code referencing `transTabungan()` relation must be updated
- ID generation logic changed

---

## 11. Files Modified

**Controllers:**
- `app/Http/Controllers/Nasabah/TabunganController.php`
- `app/Http/Controllers/Admin/TabunganController.php`

**Models:**
- `app/Models/JanjiTemuTabungan.php`

**Views:**
 - `resources/views/admin/tabungan/detail-janji-temu.blade.php`

**Migrations:**
- `database/migrations/2024_01_01_000003_create_tabungan_tables.php`
- `database/migrations/2024_01_01_000004_create_pinjaman_tables.php`
- `database/migrations/2024_01_01_000006_create_views.php`

**Seeders:**
- `database/seeders/MasterDataSeeder.php`

**Documentation:**
- `.docs/DATABASE_STRUCTURE.md` (NEW)
- `.docs/MIGRATION_REFACTOR_PLAN.md` (NEW)

---

## 12. Next Steps

1. ✅ Test janji temu creation flow
2. ✅ Test admin buat transaksi flow
3. ⏳ Update models untuk janji temu pinjaman (same pattern)
4. ⏳ Update controllers untuk janji temu pinjaman
5. ⏳ Create similar workflow for other fitur (gadai, deposito)

---

**Last Updated:** 2026-02-04 22:20 WIB
