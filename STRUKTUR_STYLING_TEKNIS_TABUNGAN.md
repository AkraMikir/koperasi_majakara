# STRUKTUR STYLING DAN TEKNIS SISTEM TABUNGAN

## 🎨 STRUKTUR STYLING KONSISTEN

### Color Palette
```css
Primary Gold: #674c1d
Secondary Gold: #8b6f2f  
Accent Gold: #d4af37
Success: #10b981 (green-600)
Warning: #f59e0b (yellow-600)
Danger: #dc2626 (red-600)
Info: #3b82f6 (blue-600)
```

### Border Radius Standards
- Cards: `rounded-2xl` (16px)
- Buttons: `rounded-xl` (12px)
- Inputs: `rounded-xl` (12px)
- Badges: `rounded-full`
- Small elements: `rounded-lg` (8px)

### Spacing Standards
- Section spacing: `space-y-6` (24px)
- Card padding: `p-6` (24px)
- Button padding: `px-4 py-3` (16px horizontal, 12px vertical)
- Input padding: `px-4 py-3`

### Typography
- Page title: `text-3xl font-bold text-gray-900 font-display`
- Section title: `text-lg font-bold text-primary font-display`
- Label: `text-sm font-semibold text-gray-700`
- Body text: `text-gray-900`
- Helper text: `text-xs text-gray-500`

### Shadow System
- Cards: `shadow-lg` atau `shadow-md`
- Buttons: `shadow-md`
- Modals: `shadow-2xl`

---

## 🏗️ STRUKTUR DATABASE

### Tabel Utama

#### 1. `tbl_pengajuan_tabungan`
```sql
id                  BIGINT PRIMARY KEY
id_anggota         BIGINT FK
nominal            DECIMAL(15,2)        -- REVISI: tambah field
keterangan         TEXT
status             ENUM('1','2','3')    -- 1=Pending, 2=Approved, 3=Rejected
foto_bukti_tf      VARCHAR(255)         -- 'transfer' atau 'tunai'
created_at         TIMESTAMP
updated_at         TIMESTAMP
```

#### 2. `tbl_pengajuan_penarikan_tabungan`
```sql
id                  BIGINT PRIMARY KEY
id_anggota         BIGINT FK
tgl_pengajuan      DATETIME
nominal            DECIMAL(15,2)
keterangan         TEXT
metode_transfer    VARCHAR(50)          -- REVISI: tambah (BCA, BNI, Mandiri, etc)
no_rekening        VARCHAR(50)          -- REVISI: tambah
nama_bank          VARCHAR(100)         -- REVISI: tambah
status             ENUM('1','2','3')
foto_bukti_tf_admin VARCHAR(255)        -- REVISI: tambah (foto TF admin ke nasabah)
created_at         TIMESTAMP
updated_at         TIMESTAMP
```

#### 3. `tbl_bukti_foto_tabungan`
```sql
id                  BIGINT PRIMARY KEY
id_pengajuan       BIGINT FK
file_photo         VARCHAR(255)
jenis              ENUM('tabungan','penarikan')
nominal            DECIMAL(15,2)        -- REVISI: hapus (pindah ke pengajuan)
keterangan         VARCHAR(255)         -- REVISI: hapus (pindah ke pengajuan)
created_at         TIMESTAMP
updated_at         TIMESTAMP
```

#### 4. `trans_tabungan`
```sql
id                      BIGINT PRIMARY KEY
id_transaksi           VARCHAR(50) UNIQUE   -- REVISI: format YYYYMMDD-SEQ-TAB
id_pengajuan_setor     BIGINT FK NULLABLE
id_pengajuan_tarik     BIGINT FK NULLABLE
id_anggota             BIGINT FK
id_jns_akun            BIGINT FK            -- REVISI: tambah
nominal                DECIMAL(15,2)
keterangan             TEXT
jenis                  ENUM('setoran','penarikan')
via                    ENUM('transfer','cash')
tgl_transaksi          TIMESTAMP
created_at             TIMESTAMP
updated_at             TIMESTAMP
```

#### 5. `tbl_janji_temu` (UNIVERSAL)
```sql
id                      BIGINT PRIMARY KEY
id_referensi           BIGINT               -- ID pengajuan terkait
jenis_transaksi        VARCHAR(50)          -- 'tabungan_setor', 'tabungan_tarik', 'pinjaman', etc
id_anggota             BIGINT FK
lokasi_temu            BIGINT FK
nominal                DECIMAL(15,2)
tanggal_janji_temu     DATETIME
waktu_janji_temu       TIMESTAMP
status_ketemu          ENUM('belum','sudah') -- REVISI: tambah untuk tracking
catatan                TEXT
created_at             TIMESTAMP
updated_at             TIMESTAMP
```

#### 6. `jns_akun` (MASTER DATA)
```sql
id                  BIGINT PRIMARY KEY
kode_akun          VARCHAR(20) UNIQUE    -- TAB, PNJ, DEP, GDI
nama_akun          VARCHAR(100)          -- Tabungan, Pinjaman, Deposito, Gadai
deskripsi          TEXT
prefix_id          VARCHAR(10)           -- TAB, PNJ, DEP, GDI (untuk ID transaksi)
is_active          BOOLEAN DEFAULT TRUE
created_at         TIMESTAMP
updated_at         TIMESTAMP
```

#### 7. `biaya_transfer` (MASTER DATA)
```sql
id                  BIGINT PRIMARY KEY
bank_pengirim      VARCHAR(50)           -- BCA
bank_penerima      VARCHAR(50)           -- BNI, Mandiri, etc
biaya_admin        DECIMAL(10,2)         -- 6500
keterangan         TEXT
is_active          BOOLEAN DEFAULT TRUE
created_at         TIMESTAMP
updated_at         TIMESTAMP
```

---

## 🔄 ALUR SISTEM (REVISED)

### A. PENGAJUAN SETORAN (TRANSFER)

#### Nasabah Side:
1. Input nominal setoran (1x)
2. Input keterangan umum (1x)
3. Upload multiple bukti foto (hanya file, no nominal/keterangan per file)
4. Verifikasi PIN
5. Submit

#### Admin Side:
1. Lihat pengajuan
2. Preview bukti foto (popup/lightbox)
3. Edit nominal dan keterangan jika perlu
4. Approve atau Reject
5. Jika approve: Auto create transaksi dengan ID kompleks

### B. PENGAJUAN PENARIKAN

#### Nasabah Side:
1. Input nominal penarikan
2. Pilih metode (Transfer/Tunai)
3. Jika transfer: Input no rekening dan nama bank
4. Input keterangan
5. Submit

#### Admin Side:
1. Lihat pengajuan
2. Cek saldo nasabah
3. Jika transfer: Pilih bank tujuan untuk lihat biaya admin
4. Upload foto bukti TF admin ke nasabah
5. Edit nominal/keterangan jika perlu
6. Approve atau Reject
7. Jika approve: Auto create transaksi

### C. JANJI TEMU (UNIVERSAL)

#### Nasabah Side:
1. Pilih jenis transaksi (Tabungan Setor/Tarik, Pinjaman, dll)
2. Input nominal
3. Pilih lokasi
4. Pilih tanggal dan waktu
5. Input catatan
6. Submit

#### Admin Side:
1. Lihat daftar janji temu (semua jenis)
2. Lihat detail: Nama, No HP, Tanggal, Lokasi, Nominal, Jenis Transaksi
3. Contact nasabah via telepon jika perlu
4. Setelah ketemu: Input transaksi manual di menu Trans Tabungan
5. Upload foto penerimaan/penyerahan
6. Update status janji temu menjadi "sudah"

### D. TRANS TABUNGAN (CRUD)

#### Admin Features:
1. **Create**: Form manual input transaksi
   - ID Anggota
   - Jenis Akun (dari master)
   - Jenis Transaksi (Setoran/Penarikan)
   - Nominal
   - Via (Transfer/Cash)
   - Keterangan
   - Upload foto bukti
   - Tanggal transaksi
   
2. **Read**: List semua transaksi
   - Filter: Jenis Akun, Jenis Transaksi, Tanggal, Nasabah
   - Search: ID Transaksi, Nama Nasabah
   - Pagination
   
3. **Update**: Edit transaksi
   - Hanya bisa edit jika dibuat manual (tidak dari pengajuan)
   - Edit: Nominal, Keterangan, Tanggal
   
4. **Delete**: Hapus transaksi
   - Hanya bisa delete jika dibuat manual
   - Confirm dialog dengan warning

---

## 📋 KOMPONEN BLADE YANG PERLU DIBUAT ULANG

### 1. Nasabah - Pengajuan Transfer
**File**: `resources/views/nasabah/tabungan/pengajuan-transfer.blade.php`

**Changes**:
- Nominal input (1x) di atas
- Keterangan input (1x) di bawah
- Upload file hanya file saja (no nominal, no keterangan per file)
- Preview thumbnail setelah upload
- Multiple upload dengan button "Tambah Bukti"

### 2. Admin - Detail Pengajuan Setor
**File**: `resources/views/admin/tabungan/detail-pengajuan-setor.blade.php`

**Changes**:
- Bukti foto dengan lightbox/popup preview (click to zoom)
- Form edit dengan field nominal dan keterangan
- Button "Edit & Setujui" (edit langsung tanpa modal terpisah)

### 3. Admin - Detail Pengajuan Tarik
**File**: `resources/views/admin/tabungan/detail-pengajuan-tarik.blade.php`

**Changes**:
- Dropdown pilih bank tujuan (dari master biaya_transfer)
- Show biaya admin secara realtime
- Upload foto bukti TF admin
- Form edit nominal dan keterangan

### 4. Admin - Trans Tabungan CRUD
**File**: `resources/views/admin/tabungan/transaksi-crud.blade.php` (NEW)

**Features**:
- Button "Buat Transaksi Baru"
- Modal/Form untuk input transaksi manual
- List transaksi dengan action Edit/Delete (hanya manual)
- Filter dan search

### 5. Admin - Janji Temu Universal
**File**: `resources/views/admin/janji-temu/index.blade.php` (NEW)

**Features**:
- List semua janji temu (semua jenis transaksi)
- Filter by jenis transaksi, tanggal, status
- Show: Nama, No HP, Jenis Transaksi, Tanggal, Lokasi, Status
- Detail dengan button "Hubungi Nasabah" (no HP clickable)
- Button "Transaksi Sudah Dilakukan" → update status

---

## 🆔 FORMAT ID TRANSAKSI KOMPLEKS

### Format: `YYYYMMDD-SEQ-TYPE`

**Contoh**:
- `20260128-001-TAB` → Transaksi pertama hari ini untuk Tabungan
- `20260128-002-PNJ` → Transaksi kedua hari ini untuk Pinjaman
- `20260128-003-TAB` → Transaksi ketiga hari ini untuk Tabungan
- `20260128-015-DEP` → Transaksi ke-15 hari ini untuk Deposito

**Implementasi**:
```php
public static function generateIdTransaksi($jnsAkunPrefix)
{
    $date = now()->format('Ymd'); // 20260128
    
    // Hitung transaksi hari ini
    $count = TransTabungan::whereDate('created_at', now())
        ->count() + 1;
    
    $seq = str_pad($count, 3, '0', STR_PAD_LEFT); // 001
    
    return "{$date}-{$seq}-{$jnsAkunPrefix}"; // 20260128-001-TAB
}
```

---

## 🎯 LIGHTBOX/PREVIEW IMPLEMENTATION

### Using GLightbox (Recommended)

**Include in layout**:
```html
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">
<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
```

**Usage**:
```html
<a href="{{ Storage::url($bukti->file_photo) }}" 
   class="glightbox" 
   data-gallery="bukti-transfer">
    <img src="{{ Storage::url($bukti->file_photo) }}" 
         alt="Bukti Transfer" 
         class="w-full h-48 object-cover rounded-lg hover:opacity-80 transition-opacity cursor-pointer">
</a>

<script>
    const lightbox = GLightbox({
        selector: '.glightbox'
    });
</script>
```

---

## 📝 MIGRATION FILES YANG PERLU DIBUAT

### 1. Update tbl_pengajuan_tabungan
```php
Schema::table('tbl_pengajuan_tabungan', function (Blueprint $table) {
    $table->decimal('nominal', 15, 2)->after('id_anggota');
});
```

### 2. Update tbl_pengajuan_penarikan_tabungan
```php
Schema::table('tbl_pengajuan_penarikan_tabungan', function (Blueprint $table) {
    $table->string('metode_transfer', 50)->nullable()->after('nominal');
    $table->string('no_rekening', 50)->nullable()->after('metode_transfer');
    $table->string('nama_bank', 100)->nullable()->after('no_rekening');
    $table->string('foto_bukti_tf_admin')->nullable()->after('nama_bank');
});
```

### 3. Update tbl_bukti_foto_tabungan
```php
Schema::table('tbl_bukti_foto_tabungan', function (Blueprint $table) {
    $table->dropColumn(['nominal', 'keterangan']);
});
```

### 4. Update trans_tabungan
```php
Schema::table('trans_tabungan', function (Blueprint $table) {
    $table->string('id_transaksi', 50)->unique()->after('id');
    $table->foreignId('id_jns_akun')->nullable()->constrained('jns_akun')->after('id_anggota');
});
```

### 5. Create jns_akun
```php
Schema::create('jns_akun', function (Blueprint $table) {
    $table->id();
    $table->string('kode_akun', 20)->unique();
    $table->string('nama_akun', 100);
    $table->text('deskripsi')->nullable();
    $table->string('prefix_id', 10);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### 6. Create biaya_transfer
```php
Schema::create('biaya_transfer', function (Blueprint $table) {
    $table->id();
    $table->string('bank_pengirim', 50);
    $table->string('bank_penerima', 50);
    $table->decimal('biaya_admin', 10, 2);
    $table->text('keterangan')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### 7. Refactor janji temu (universal)
```php
Schema::dropIfExists('tbl_janji_temu_tabungan');
Schema::dropIfExists('tbl_janji_temu_pinjaman');
// ... drop semua janji temu lainnya

Schema::create('tbl_janji_temu', function (Blueprint $table) {
    $table->id();
    $table->bigInteger('id_referensi'); // ID pengajuan terkait
    $table->string('jenis_transaksi', 50); // tabungan_setor, pinjaman, dll
    $table->foreignId('id_anggota')->constrained('tbl_nasabah');
    $table->foreignId('lokasi_temu')->constrained('jns_lokasi_perusahaan');
    $table->decimal('nominal', 15, 2);
    $table->dateTime('tanggal_janji_temu');
    $table->timestamp('waktu_janji_temu');
    $table->enum('status_ketemu', ['belum', 'sudah'])->default('belum');
    $table->text('catatan')->nullable();
    $table->timestamps();
});
```

---

## 🔧 CONTROLLER METHODS YANG PERLU DIUPDATE

### TabunganController (Nasabah)

#### submitSetoran()
```php
- Remove: nominal_foto[], keterangan_foto[] dari bukti foto
- Add: nominal di pengajuan (1x)
- Validate: minimal 1 file upload
- Save: nominal ke tbl_pengajuan_tabungan
- Save: hanya file_photo ke tbl_bukti_foto_tabungan
```

#### submitPenarikan()
```php
- Add: metode_transfer, no_rekening, nama_bank
- Validate: required_if metode = transfer
```

### TabunganController (Admin)

#### detailPengajuanSetor()
```php
- Add: form edit nominal dan keterangan
- Add: GLightbox untuk preview foto
```

#### approveSetor()
```php
- Update: ambil nominal dari pengajuan (bukan dari bukti foto)
- Generate: ID transaksi kompleks
- Link: ke jns_akun
```

#### detailPengajuanTarik()
```php
- Add: dropdown bank tujuan
- Add: calculate biaya admin
- Add: upload foto TF admin
```

#### approveTarik()
```php
- Validate: foto TF admin wajib
- Save: foto ke database
- Generate: ID transaksi kompleks
- Kurangi: biaya admin dari nominal (optional)
```

#### NEW: crudTransaksi()
```php
- index(): List transaksi dengan filter
- create(): Form input transaksi manual
- store(): Save transaksi manual (generate ID)
- edit(): Form edit transaksi manual
- update(): Update transaksi (hanya manual)
- destroy(): Delete transaksi (hanya manual)
```

---

## 📊 SEEDER DATA

### JnsAkunSeeder
```php
[
    ['kode_akun' => 'TAB', 'nama_akun' => 'Tabungan', 'prefix_id' => 'TAB'],
    ['kode_akun' => 'PNJ', 'nama_akun' => 'Pinjaman', 'prefix_id' => 'PNJ'],
    ['kode_akun' => 'DEP', 'nama_akun' => 'Deposito', 'prefix_id' => 'DEP'],
    ['kode_akun' => 'GDI', 'nama_akun' => 'Gadai', 'prefix_id' => 'GDI'],
]
```

### BiayaTransferSeeder
```php
[
    ['bank_pengirim' => 'BCA', 'bank_penerima' => 'BCA', 'biaya_admin' => 0],
    ['bank_pengirim' => 'BCA', 'bank_penerima' => 'BNI', 'biaya_admin' => 6500],
    ['bank_pengirim' => 'BCA', 'bank_penerima' => 'Mandiri', 'biaya_admin' => 6500],
    ['bank_pengirim' => 'BCA', 'bank_penerima' => 'BRI', 'biaya_admin' => 6500],
    // ... dst
]
```

---

## ✅ CHECKLIST IMPLEMENTASI

### Phase 1: Database
- [ ] Migration: Add nominal ke tbl_pengajuan_tabungan
- [ ] Migration: Add metode_transfer, bank, foto_admin ke tbl_pengajuan_penarikan
- [ ] Migration: Remove nominal, keterangan dari tbl_bukti_foto_tabungan
- [ ] Migration: Add id_transaksi ke trans_tabungan
- [ ] Migration: Create jns_akun
- [ ] Migration: Create biaya_transfer
- [ ] Migration: Create tbl_janji_temu (universal)
- [ ] Seeder: JnsAkunSeeder
- [ ] Seeder: BiayaTransferSeeder

### Phase 2: Models
- [ ] Update: PengajuanTabungan (add nominal field)
- [ ] Update: PengajuanPenarikanTabungan (add bank fields)
- [ ] Update: BuktiFotoTabungan (remove nominal, keterangan)
- [ ] Update: TransTabungan (add id_transaksi, id_jns_akun)
- [ ] Create: JnsAkun model
- [ ] Create: BiayaTransfer model
- [ ] Create: JanjiTemu model (universal)
- [ ] Add: generateIdTransaksi() method

### Phase 3: Controllers - Nasabah
- [ ] Update: submitSetoran() - remove per-file nominal/keterangan
- [ ] Update: submitPenarikan() - add bank fields
- [ ] Update: janjiTemu() - universal form

### Phase 4: Controllers - Admin
- [ ] Update: approveSetor() - with edit nominal feature
- [ ] Update: approveTarik() - with bank selection & foto upload
- [ ] Create: CRUD trans_tabungan (index, create, store, edit, update, destroy)
- [ ] Update: janjiTemu() - universal list & detail

### Phase 5: Views - Nasabah
- [ ] Rebuild: pengajuan-transfer.blade.php (1x nominal, 1x keterangan)
- [ ] Update: penarikan-tabungan.blade.php (add bank fields)
- [ ] Update: janji-temu.blade.php (universal form)

### Phase 6: Views - Admin
- [ ] Rebuild: detail-pengajuan-setor.blade.php (lightbox + edit form)
- [ ] Rebuild: detail-pengajuan-tarik.blade.php (bank dropdown + foto upload)
- [ ] Create: transaksi-crud.blade.php (CRUD interface)
- [ ] Create: admin/janji-temu/index.blade.php (universal list)
- [ ] Create: admin/janji-temu/detail.blade.php (with no HP)

### Phase 7: Routes
- [ ] Add: routes for CRUD trans_tabungan
- [ ] Add: routes for universal janji temu
- [ ] Add: routes for master jns_akun CRUD
- [ ] Add: routes for master biaya_transfer CRUD

### Phase 8: Master Data CRUD
- [ ] Create: JnsAkunController (index, create, store, edit, update, destroy)
- [ ] Create: BiayaTransferController (index, create, store, edit, update, destroy)
- [ ] Views: admin/master-data/jns-akun/
- [ ] Views: admin/master-data/biaya-transfer/

### Phase 9: Testing & Bug Fixes
- [ ] Test: Upload bukti foto (multiple)
- [ ] Test: Lightbox preview
- [ ] Test: Edit nominal saat approve
- [ ] Test: Bank selection & biaya admin
- [ ] Test: Foto upload admin
- [ ] Test: ID transaksi generation
- [ ] Test: CRUD trans_tabungan
- [ ] Test: Universal janji temu
- [ ] Fix: Linter errors
- [ ] Fix: Styling consistency

---

## 🚨 CATATAN PENTING

1. **Backup Database** sebelum run migration (ada drop column)
2. **Storage Link** harus sudah di-setup: `php artisan storage:link`
3. **GLightbox** perlu di-include di layout admin & nasabah
4. **ID Transaksi** harus unique, implement locking jika concurrent
5. **File Upload** max size di config (php.ini & Laravel)
6. **Biaya Transfer** bisa di-customize per koperasi
7. **Janji Temu Universal** akan merubah struktur existing
8. **Testing** harus menyeluruh karena banyak perubahan

---

**Dokumen ini adalah blueprint untuk implementasi revisi sistem tabungan.**
