# ANALISIS DATABASE & CONTROLLER - SISTEM PINJAMAN

> 📅 **Tanggal:** 4 Februari 2026  
> 🎯 **Fokus:** Struktur Database Pinjaman, Alur Data, Controller Methods, Logic Perhitungan
> 🔧 **Status:** Updated - Final V2 (Post Refactoring)

---

## 📊 DATABASE TABLES - SISTEM PINJAMAN

### 1️⃣ TABLE: `tbl_pengajuan_pinjaman`

**Fungsi:** Menyimpan pengajuan pinjaman dari nasabah (sebelum dicairkan)

| Kolom | Tipe | Null | Default | Digunakan | Keterangan |
|-------|------|------|---------|-----------|------------|
| `id` | string(30) | NO | - | ✅ | Primary key (AUTO-GENERATE: PJ-YYYYMMDD-XXXXXXXX) |
| `id_anggota` | bigint unsigned | NO | - | ✅ | FK ke tbl_nasabah |
| `tgl_pengajuan` | date | NO | - | ✅ | Tanggal pengajuan |
| `nominal` | decimal(15,2) | NO | - | ✅ | Nominal pinjaman yang diajukan |
| `jenis` | string(20) | YES | bulanan | ✅ | Jenis angsuran (saat ini hanya 'bulanan') |
| `durasi` | integer | NO | - | ✅ | Durasi pinjaman dalam bulan (1-24) |
| `jenis_pencairan` | string(20) | YES | transfer | ✅ | Metode pencairan: 'transfer' atau 'cash' |
| `status` | char(1) | NO | 1 | ✅ | 1=Pending, 2=Ditolak, 3=Disetujui, 4=Terlaksana |
| `keterangan` | text | YES | NULL | ✅ | Keterangan tambahan dari nasabah |
| `keterangan_admin` | text | YES | NULL | ✅ | Keterangan dari admin (jika ditolak) |
| `tgl_cair` | date | YES | NULL | ✅ | Tanggal pencairan (diisi saat dicairkan) |
| `bunga_persen` | decimal(5,2) | YES | NULL | ✅ | Bunga persen (diisi saat approve dari master) |
| `created_at` | timestamp | YES | NULL | ✅ | Auto timestamp |
| `updated_at` | timestamp | YES | NULL | ✅ | Auto timestamp |

**Format ID Auto-Generate:**
```
PJ-20260204-A1B2C3D4
│   │         └─ Random 8 karakter (hexadecimal uppercase)
│   └─ Tanggal (YYYYMMDD)
└─ Prefix (Pinjaman)
```

**Alur Status:**
1. `'1'` → **Pending** (baru diajukan oleh nasabah)
2. `'2'` → **Ditolak** (admin reject dengan keterangan)
3. `'3'` → **Disetujui** (admin approve, belum dicairkan, bunga_persen sudah diset)
4. `'4'` → **Terlaksana** (sudah dicairkan, pinjaman created di tbl_pinjaman_h)

**Model:** `PengajuanPinjaman.php`
- `public $incrementing = false`
- `protected $keyType = 'string'`
- Method `boot()` auto-generate ID saat creating

---

### 2️⃣ TABLE: `tbl_pinjaman_h`

**Fungsi:** Menyimpan data pinjaman yang sudah dicairkan (header pinjaman)

| Kolom | Tipe | Null | Default | Digunakan | Keterangan |
|-------|------|------|---------|-----------|------------|
| `id` | string(30) | NO | - | ✅ | Primary key (Format: DDMMYYYY-SEQQ-PINJAMAN) |
| `id_anggota` | bigint unsigned | NO | - | ✅ | FK ke tbl_nasabah |
| `id_pengajuan` | string(30) | YES | NULL | ✅ | FK ke tbl_pengajuan_pinjaman |
| `jumlah_pinjam` | decimal(15,2) | NO | - | ✅ | Nominal yang diterima nasabah |
| `lama_pinjam` | integer | NO | - | ✅ | Durasi dalam bulan |
| `jenis` | string(20) | YES | bulanan | ✅ | 'bulanan' atau 'mingguan' |
| `bunga` | decimal(5,2) | NO | - | ✅ | Bunga persen (ex: 2.5 untuk 2.5%) |
| `bunga_rp` | decimal(15,2) | NO | - | ✅ | Total bunga dalam rupiah |
| `denda_persen` | decimal(5,2) | YES | 0.30 | ✅ | Denda per hari (0.30 = 0.3%/hari dari pokok) |
| `ags_bulan` | decimal(15,2) | NO | - | ✅ | Angsuran per bulan (pokok + bunga) |
| `tgl_pinjam` | date | NO | - | ✅ | Tanggal pencairan |
| `lunas` | enum | YES | belum | ✅ | 'belum' atau 'lunas' |
| `created_at` | timestamp | YES | NULL | ✅ | Auto timestamp |
| `updated_at` | timestamp | YES | NULL | ✅ | Auto timestamp |

**Perhitungan saat Create:**
```php
$nominal = $pengajuan->nominal;  // Misal: 3.000.000
$bungaPersen = $masterBunga->bunga_persen;  // Misal: 2.5
$durasi = $pengajuan->durasi;  // Misal: 3 bulan

$bungaRp = ($nominal * $bungaPersen) / 100;  // 3.000.000 × 2.5% = 75.000
$jumlahPinjam = $nominal;  // 3.000.000 (yang diterima nasabah)

$pokokPerBulan = $jumlahPinjam / $durasi;  // 3.000.000 / 3 = 1.000.000
$bungaPerBulan = $bungaRp / $durasi;  // 75.000 / 3 = 25.000
$agsBulan = $pokokPerBulan + $bungaPerBulan;  // 1.000.000 + 25.000 = 1.025.000

// Total yang harus dibayar = 3.000.000 + 75.000 = 3.075.000
// Angsuran per bulan = 1.025.000 × 3 bulan = 3.075.000
```

**Model:** `PinjamanH.php`
- Relasi: `tempoBulanan()`, `tempoMingguan()`, `pengajuan()`, `nasabah()`

---

### 3️⃣ TABLE: `tempo_pinjaman_b`

**Fungsi:** Menyimpan jadwal angsuran BULANAN (schedule pembayaran)

| Kolom | Tipe | Null | Default | Digunakan | Keterangan |
|-------|------|------|---------|-----------|------------|
| `id` | string(30) | NO | - | ✅ | Primary key (Format: DDMMYYYY-SEQQ-PTTPNJM) |
| `pinjaman_id` | string(30) | NO | - | ✅ | FK ke tbl_pinjaman_h |
| `no_urut` | integer | NO | - | ✅ | Angsuran ke- (1, 2, 3, ..., n) |
| `tgl_jatuh_tempo` | date | NO | - | ✅ | Tanggal jatuh tempo pembayaran |
| `jumlah_tagihan` | decimal(15,2) | NO | - | ✅ | Pokok + bunga per periode |
| `jumlah_terbayar` | decimal(15,2) | YES | 0.00 | ✅ | Total yang sudah dibayar |
| `denda` | decimal(15,2) | YES | 0.00 | ✅ | Denda keterlambatan (tersimpan) |
| `tgl_bayar` | timestamp | YES | NULL | ✅ | Tanggal pembayaran terakhir |
| `status_bayar` | enum | YES | belum | ✅ | 'belum', 'lunas', 'telat' |
| `created_at` | timestamp | YES | NULL | ✅ | Auto timestamp |
| `updated_at` | timestamp | YES | NULL | ✅ | Auto timestamp |

**Generate saat Pencairan (Auto):**
```php
// Contoh: Pinjaman 3 juta, 3 bulan
$pokokPerBulan = 3000000 / 3 = 1000000
$bungaPerBulan = 75000 / 3 = 25000
$jumlahTagihan = 1025000

Loop untuk i = 1 sampai 3:
  - no_urut = 1, 2, 3
  - tgl_jatuh_tempo = tgl_pinjam + i bulan
  - jumlah_tagihan = 1.025.000
  - jumlah_terbayar = 0
  - status_bayar = 'belum'
```

**Status Bayar Logic:**
- `'belum'`: Belum ada pembayaran, belum lewat jatuh tempo
- `'telat'`: Belum lunas DAN sudah lewat jatuh tempo
- `'lunas'`: `jumlah_terbayar >= (jumlah_tagihan + denda)`

**Model:** `TempoPinjamanB.php`
- `public $incrementing = false`
- `protected $keyType = 'string'`
- Relasi: `pinjaman()` → BelongsTo PinjamanH

---

### 4️⃣ TABLE: `tempo_pinjaman_m`

**Fungsi:** Menyimpan jadwal angsuran MINGGUAN (untuk pinjaman mingguan jika diaktifkan)

**Struktur sama persis dengan `tempo_pinjaman_b`**

| Kolom | Tipe | Digunakan | Keterangan |
|-------|------|-----------|------------|
| Sama dengan tempo_pinjaman_b | - | ⏸️ | Saat ini belum digunakan (fokus bulanan) |

**Model:** `TempoPinjamanM.php`
- Struktur sama dengan TempoPinjamanB
- Ready untuk future feature pinjaman mingguan

---

### 5️⃣ TABLE: `tbl_pengajuan_pembayaran_pinjaman`

**Fungsi:** Menyimpan pengajuan pembayaran angsuran dari nasabah

| Kolom | Tipe | Null | Default | Digunakan | Keterangan |
|-------|------|------|---------|-----------|------------|
| `id` | string(30) | NO | - | ✅ | Primary key |
| `id_anggota` | bigint unsigned | NO | - | ✅ | FK ke tbl_nasabah |
| `pinjaman_id` | string(30) | NO | - | ✅ | FK ke tbl_pinjaman_h |
| `tempo_id` | string(30) | NO | - | ✅ | FK ke tempo_pinjaman_b/m (angsuran yang dibayar) |
| `jenis_tempo` | string(20) | YES | bulanan | ✅ | 'bulanan' atau 'mingguan' |
| `nominal` | decimal(15,2) | NO | - | ✅ | Jumlah pembayaran |
| `rekening_tujuan` | string(50) | YES | NULL | ✅ | Rekening bank tujuan (untuk transfer) |
| `keterangan` | text | YES | NULL | ✅ | Keterangan tambahan |
| `keterangan_admin` | text | YES | NULL | ✅ | Keterangan dari admin |
| `status` | char(1) | NO | 1 | ✅ | 1=Pending, 2=Ditolak, 3=Disetujui |
| `tgl_pembayaran` | timestamp | YES | NULL | ✅ | Tanggal pembayaran diproses |
| `created_at` | timestamp | YES | NULL | ✅ | Auto timestamp |
| `updated_at` | timestamp | YES | NULL | ✅ | Auto timestamp |

**Alur:**
1. Nasabah pilih angsuran mana yang mau dibayar
2. Submit dengan nominal + bukti foto (untuk transfer)
3. Admin review dan approve
4. Admin input pembayaran → update `tempo_pinjaman_b`

---

### 6️⃣ TABLE: `tbl_bukti_foto_pembayaran_pinjaman`

**Fungsi:** Menyimpan foto bukti pembayaran angsuran

| Kolom | Tipe | Null | Digunakan | Keterangan |
|-------|------|------|-----------|------------|
| `id` | bigint unsigned | NO | ✅ | Primary key |
| `id_pengajuan` | string(30) | NO | ✅ | FK ke tbl_pengajuan_pembayaran_pinjaman |
| `file_photo` | varchar(255) | NO | ✅ | Path file di storage |
| `jenis` | enum | NO | ✅ | 'bukti_transfer' atau 'serah_terima' |
| `keterangan` | text | YES | ✅ | Keterangan foto |
| `created_at` | timestamp | YES | ✅ | Auto timestamp |
| `updated_at` | timestamp | YES | ✅ | Auto timestamp |

**Upload Location:** `storage/app/public/bukti-pembayaran-pinjaman/`

---

### 7️⃣ TABLE: `tbl_janji_temu_pinjaman`

**Fungsi:** Jadwal janji temu untuk pengajuan pinjaman CASH (pencairan tunai)

| Kolom | Tipe | Null | Digunakan | Keterangan |
|-------|------|------|-----------|------------|
| `id` | bigint unsigned | NO | ✅ | Primary key (auto increment) |
| `id_pengajuan` | string(30) | NO | ✅ | FK ke tbl_pengajuan_pinjaman |
| `id_nasabah` | bigint unsigned | NO | ✅ | FK ke tbl_nasabah |
| `lokasi_temu` | bigint unsigned | NO | ✅ | FK ke jns_lokasi_perusahaan |
| `nominal` | decimal(15,2) | NO | ✅ | Nominal pinjaman |
| `tanggal_janji_temu` | datetime | NO | ✅ | Tanggal janji temu |
| `waktu_janji_temu` | time | NO | ✅ | Waktu janji temu |
| `keterangan` | text | YES | ✅ | Keterangan |
| `created_at` | timestamp | YES | ✅ | Auto timestamp |
| `updated_at` | timestamp | YES | ✅ | Auto timestamp |

**Alur:**
- Hanya dibuat jika `jenis_pencairan = 'cash'`
- Admin dan nasabah bertemu di lokasi untuk pencairan tunai

---

### 8️⃣ TABLE: `tbl_janji_temu_pembayaran_pinjaman`

**Fungsi:** Jadwal janji temu untuk pembayaran angsuran CASH

**Struktur sama dengan `tbl_janji_temu_pinjaman`**

| Kolom | Digunakan | Keterangan |
|-------|-----------|------------|
| `id_pengajuan` | ✅ | FK ke tbl_pengajuan_pembayaran_pinjaman |
| (kolom lain sama) | ✅ | - |

---

### 9️⃣ TABLE: `master_bunga_pinjaman`

**Fungsi:** Master data bunga pinjaman berdasarkan durasi

| Kolom | Tipe | Digunakan | Keterangan |
|-------|------|-----------|------------|
| `id` | bigint unsigned | ✅ | Primary key |
| `durasi_min` | integer | ✅ | Durasi minimum (bulan) |
| `durasi_max` | integer | ✅ | Durasi maksimum (bulan) |
| `bunga_persen` | decimal(5,2) | ✅ | Bunga persen (ex: 2.5 untuk 2.5%) |
| `status_aktif` | boolean | ✅ | Status aktif |
| `created_at` | timestamp | ✅ | Auto timestamp |
| `updated_at` | timestamp | ✅ | Auto timestamp |

**Contoh Data:**
```
durasi_min=1, durasi_max=3, bunga_persen=2.5   → pinjaman 1-3 bulan = 2.5%
durasi_min=4, durasi_max=6, bunga_persen=3.0   → pinjaman 4-6 bulan = 3.0%
durasi_min=7, durasi_max=12, bunga_persen=3.5  → pinjaman 7-12 bulan = 3.5%
```

**Method Static:**
```php
MasterBungaPinjaman::getBungaByDurasi($durasi)
// Return: object bunga yang sesuai dengan durasi
```

---

### 🔟 TABLE: `master_denda_pinjaman`

**Fungsi:** Master data denda keterlambatan

| Kolom | Tipe | Digunakan | Keterangan |
|-------|------|-----------|------------|
| `id` | bigint unsigned | ✅ | Primary key |
| `denda_persen` | decimal(5,2) | ✅ | Denda per hari (ex: 0.30 untuk 0.3%/hari) |
| `status_aktif` | boolean | ✅ | Status aktif (hanya 1 yang aktif) |
| `created_at` | timestamp | ✅ | Auto timestamp |
| `updated_at` | timestamp | ✅ | Auto timestamp |

**Method Static:**
```php
MasterDendaPinjaman::getDendaAktif()
// Return: object denda yang aktif
```

---

## 🎮 CONTROLLER METHODS - PINJAMAN

### 📘 **PinjamanController (Nasabah)**
**Location:** `app/Http/Controllers/Nasabah/PinjamanController.php`

#### **Method: `index()`** - Dashboard Pinjaman
- **Trigger:** Akses halaman dashboard pinjaman nasabah
- **Query:**
  ```php
  SELECT * FROM tbl_pinjaman_h 
  WHERE id_anggota = $idAnggota AND lunas = 'belum'
  
  // Join dengan tempo untuk hitung statistik
  ```
- **Output:**
  - Total pinjaman aktif
  - Sisa pinjaman (tagihan - terbayar)
  - Angsuran terdekat (7 hari ke depan)
  - Total angsuran telat
  - List semua angsuran (10 terakhir)

---

#### **Method: `pengajuanTransfer()`** - Form Transfer
- **Trigger:** Nasabah akses form pengajuan pinjaman transfer
- **Data:**
  - Master bunga (untuk info simulasi)
- **View:** `nasabah.pinjaman.pengajuan-transfer`

---

#### **Method: `simulasiAngsuran()`** - AJAX Simulasi
- **Trigger:** User input nominal & durasi, get simulasi
- **Input:** `nominal`, `durasi`
- **Logic:**
  ```php
  $masterBunga = MasterBungaPinjaman::getBungaByDurasi($durasi);
  $bungaPersen = $masterBunga->bunga_persen;
  $bungaRp = ($nominal * $bungaPersen) / 100;
  
  $bungaPerBulan = $bungaRp / $durasi;
  $pokokPerBulan = $nominal / $durasi;
  $totalPerAngsuran = $pokokPerBulan + $bungaPerBulan;
  
  // Generate array simulasi untuk setiap bulan
  FOR i = 1 to durasi:
    simulasi[i] = {
      bulan: i,
      tanggal: tgl_mulai + i bulan,
      pokok: pokokPerBulan,
      bunga: bungaPerBulan,
      total: totalPerAngsuran
    }
  ```
- **Output JSON:**
  ```json
  {
    "success": true,
    "data": {
      "nominal": 3000000,
      "durasi": 3,
      "bunga_persen": 2.5,
      "bunga_total": 75000,
      "total_yang_harus_dibayar": 3075000,
      "angsuran_per_bulan": 1025000,
      "simulasi": [...]
    }
  }
  ```

---

#### **Method: `submitPengajuanTransfer()`** - Submit Transfer
- **Trigger:** Form pengajuan transfer di-submit
- **Input:** `nominal`, `durasi`, `pin`, `keterangan`
- **Validasi:**
  1. Validate input
  2. Verify PIN user
- **Proses:**
  ```php
  PengajuanPinjaman::create([
    // 'id' → AUTO-GENERATE oleh model (PJ-YYYYMMDD-XXXXXXXX)
    'id_anggota' => $idAnggota,
    'tgl_pengajuan' => now(),
    'nominal' => $request->nominal,
    'jenis' => 'bulanan',
    'durasi' => (int)$request->durasi,
    'jenis_pencairan' => 'transfer',
    'status' => '1',  // Pending
    'keterangan' => $request->keterangan,
  ]);
  ```
- **Output:** Redirect ke `nasabah.pinjaman.pengajuan` dengan success message

---

#### **Method: `submitJanjiTemuPinjaman()`** - Submit Cash (Janji Temu)
- **Trigger:** Form janji temu pinjaman tunai di-submit
- **Input:** `nominal`, `durasi`, `lokasi_temu`, `tanggal_janji_temu`, `waktu_janji_temu`, `pin`
- **Proses:**
  ```php
  // 1. Create pengajuan
  $pengajuan = PengajuanPinjaman::create([
    'id_anggota' => $idAnggota,
    'tgl_pengajuan' => now(),
    'nominal' => $request->nominal,
    'jenis' => 'bulanan',
    'durasi' => (int)$request->durasi,
    'jenis_pencairan' => 'cash',
    'status' => '1',
    'keterangan' => $request->keterangan,
  ]);
  
  // 2. Create janji temu
  JanjiTemuPinjaman::create([
    'id_pengajuan' => $pengajuan->id,
    'id_nasabah' => $idAnggota,
    'lokasi_temu' => $request->lokasi_temu,
    'nominal' => $request->nominal,
    'tanggal_janji_temu' => $request->tanggal_janji_temu,
    'waktu_janji_temu' => $request->waktu_janji_temu,
    'keterangan' => $request->keterangan,
  ]);
  ```
- **Output:** Redirect dengan success

---

#### **Method: `pembayaran()`** - Form Pembayaran Angsuran
- **Trigger:** Nasabah akses halaman pembayaran pinjaman
- **Input (Query String):** `pinjaman_id`, `tempo_id`, `jenis`
- **Query:**
  ```php
  // 1. Get semua pinjaman aktif
  $pinjamanAktif = PinjamanH::where('id_anggota', $idAnggota)
    ->where('lunas', 'belum')
    ->get();
  
  // 2. Jika pinjaman_id dipilih, get angsuran yang belum lunas
  if ($pinjamanId) {
    $angsuranList = $pinjaman->tempoBulanan()
      ->where('status_bayar', '!=', 'lunas')
      ->orderBy('no_urut')
      ->get();
  }
  
  // 3. Jika tempo_id dipilih, get detail angsuran
  if ($tempoId) {
    $selectedAngsuran = TempoPinjamanB::where('id', $tempoId)
      ->whereHas('pinjaman', function($q) use ($idAnggota) {
        $q->where('id_anggota', $idAnggota);  // ✅ Verifikasi via relasi
      })
      ->first();
  }
  ```
- **Output:**
  - Dropdown pinjaman aktif
  - Dropdown angsuran (jika pinjaman dipilih)
  - Detail angsuran (jika angsuran dipilih)

**⚠️ PENTING:** Query menggunakan `whereHas('pinjaman')` karena tabel `tempo_pinjaman_b` **TIDAK** memiliki kolom `anggota_id`

---

#### **Method: `submitPembayaranTransfer()`** - Submit Pembayaran Transfer
- **Trigger:** Nasabah submit pembayaran via transfer
- **Input:** `pinjaman_id`, `tempo_id`, `jenis_tempo`, `nominal`, `rekening_tujuan`, `bukti_foto[]`, `pin`
- **Proses:**
  ```php
  // 1. Verify PIN
  // 2. Create pengajuan pembayaran
  $pengajuan = PengajuanPembayaranPinjaman::create([
    'id_anggota' => $idAnggota,
    'pinjaman_id' => $request->pinjaman_id,
    'tempo_id' => $request->tempo_id,
    'jenis_tempo' => $request->jenis_tempo,
    'nominal' => $request->nominal,
    'rekening_tujuan' => $request->rekening_tujuan,
    'status' => '1',  // Pending
    'keterangan' => $request->keterangan,
  ]);
  
  // 3. Upload bukti foto (multiple files)
  foreach ($request->file('bukti_foto') as $file) {
    BuktiFotoPembayaranPinjaman::create([
      'id_pengajuan' => $pengajuan->id,
      'file_photo' => $path,
      'jenis' => 'bukti_transfer',
    ]);
  }
  ```

---

### 📗 **PinjamanController (Admin)**
**Location:** `app/Http/Controllers/Admin/PinjamanController.php`

#### **Method: `index()`** - Dashboard Admin
- **Output:**
  - Total pengajuan pending
  - Total pinjaman aktif
  - Total pinjaman lunas
  - Total angsuran telat
  - Total pembayaran pending
  - Pengajuan terbaru (5)
  - Pinjaman aktif terbaru (5)
  - Angsuran jatuh tempo hari ini

---

#### **Method: `approvePengajuan()`** - Approve Pengajuan (Status 1→3)
- **Trigger:** Admin klik approve pada pengajuan
- **Input:** `id` pengajuan
- **Proses:**
  ```php
  // 1. Cek status harus '1' (pending)
  if ($pengajuan->status !== '1') {
    return error;
  }
  
  // 2. Get bunga dari master data
  $masterBunga = MasterBungaPinjaman::getBungaByDurasi($pengajuan->durasi);
  if (!$masterBunga) {
    return error('Bunga belum diatur');
  }
  
  // 3. Get denda dari master (untuk validasi)
  $masterDenda = MasterDendaPinjaman::getDendaAktif();
  if (!$masterDenda) {
    return error('Denda belum diatur');
  }
  
  // 4. Update status menjadi '3' (Disetujui)
  $pengajuan->update([
    'status' => '3',
    'bunga_persen' => $masterBunga->bunga_persen,
  ]);
  ```
- **Output:** Redirect ke detail pengajuan dengan message "Silakan klik Cairkan untuk melanjutkan"

**⚠️ PENTING:** Belum membuat pinjaman, hanya update status approval!

---

#### **Method: `cairkanPinjaman()`** - Cairkan Pinjaman (Status 3→4)
- **Trigger:** Admin klik cairkan pada pengajuan yang sudah disetujui
- **Input:** `id`, `tgl_cair`, `bukti_transfer` (optional)
- **Validasi:**
  1. Status harus '3' (disetujui)
  2. Belum punya pinjaman
  3. Master bunga & denda ada
- **Proses (TRANSACTION):**
  ```php
  DB::beginTransaction();
  
  // 1. Hitung bunga
  $nominal = $pengajuan->nominal;
  $bungaPersen = $masterBunga->bunga_persen;
  $bungaRp = ($nominal * $bungaPersen) / 100;
  $jumlahPinjam = $nominal;
  
  // 2. Generate ID Pinjaman
  $idPinjaman = IdGenerator::generate(
    'tbl_pinjaman_h', 
    'P',      // Prefix 1
    'T',      // Prefix 2 
    'DPNJM',  // Suffix
    $request->tgl_cair
  );
  // Format hasil: 04022026-0001-PTDPNJM
  
  // 3. Create pinjaman
  $pinjaman = PinjamanH::create([
    'id' => $idPinjaman,
    'id_anggota' => $pengajuan->id_anggota,
    'id_pengajuan' => $pengajuan->id,
    'jumlah_pinjam' => $jumlahPinjam,
    'lama_pinjam' => (int)$pengajuan->durasi,
    'ags_bulan' => ($jumlahPinjam + $bungaRp) / $pengajuan->durasi,
    'jenis' => 'bulanan',
    'bunga' => $bungaPersen,
    'bunga_rp' => $bungaRp,
    'denda_persen' => $masterDenda->denda_persen,
    'tgl_pinjam' => $request->tgl_cair,
    'lunas' => 'belum',
  ]);
  
  // 4. Generate jadwal angsuran
  $this->generateJadwalAngsuran($pinjaman);
  
  // 5. Upload bukti transfer (optional)
  if ($request->hasFile('bukti_transfer')) {
    // Store file
  }
  
  // 6. Update status pengajuan menjadi '4' (Terlaksana)
  $pengajuan->update([
    'status' => '4',
    'tgl_cair' => $request->tgl_cair,
  ]);
  
  DB::commit();
  ```
- **Output:** Redirect ke `admin.pinjaman.detail-pinjaman` dengan success

---

#### **Method: `generateJadwalAngsuran()` (PRIVATE)** - Generate Tempo
- **Trigger:** Dipanggil otomatis saat `cairkanPinjaman()`
- **Input:** `PinjamanH $pinjaman`
- **Logic:**
  ```php
  $jumlahAngsuran = $pinjaman->lama_pinjam;  // ex: 3
  $jumlahPinjam = $pinjaman->jumlah_pinjam;  // ex: 3.000.000
  $bungaRp = $pinjaman->bunga_rp;            // ex: 75.000
  
  // Hitung per bulan
  $pokokPerBulan = $jumlahPinjam / $jumlahAngsuran;  // 1.000.000
  $bungaPerBulan = $bungaRp / $jumlahAngsuran;       // 25.000
  $totalPerAngsuran = $pokokPerBulan + $bungaPerBulan;  // 1.025.000
  
  $tanggalMulai = Carbon::parse($pinjaman->tgl_pinjam);
  
  // Generate Base ID untuk tempo pertama
  $baseId = IdGenerator::generate(
    'tempo_pinjaman_b',
    'P', 'T', 'TPNJM',
    $tanggalMulai
  );
  // Format: 04022026-0001-PTTPNJM
  
  // Extract untuk increment manual
  $datePrefix = substr($baseId, 0, 8);      // 04022026
  $seqStart = (int)substr($baseId, 8, 4);   // 0001
  $suffix = substr($baseId, 12);             // PTTPNJM
  
  // Loop create tempo
  for ($i = 1; $i <= $jumlahAngsuran; $i++) {
    $tanggalJatuhTempo = $tanggalMulai->copy()->addMonths($i);
    
    // Increment sequence manual
    $currentSeq = $seqStart + ($i - 1);
    $seqStr = str_pad($currentSeq, 4, '0', STR_PAD_LEFT);
    $currentId = $datePrefix . $seqStr . $suffix;
    
    TempoPinjamanB::create([
      'id' => $currentId,  // 04022026-0001-PTTPNJM, 04022026-0002-PTTPNJM, ...
      'pinjaman_id' => $pinjaman->id,
      'no_urut' => $i,
      'tgl_jatuh_tempo' => $tanggalJatuhTempo,
      'jumlah_tagihan' => round($totalPerAngsuran, 2),
      'jumlah_terbayar' => 0,
      'denda' => 0,
      'status_bayar' => 'belum',
    ]);
  }
  ```

**Hasil:**
```
Angsuran 1: 04022026-0001-PTTPNJM | Jatuh tempo: 04-03-2026 | Tagihan: 1.025.000
Angsuran 2: 04022026-0002-PTTPNJM | Jatuh tempo: 04-04-2026 | Tagihan: 1.025.000
Angsuran 3: 04022026-0003-PTTPNJM | Jatuh tempo: 04-05-2026 | Tagihan: 1.025.000
```

---

#### **Method: `updatePembayaranAngsuran()`** - Update Pembayaran
- **Trigger:** Admin input pembayaran angsuran
- **Input:** `id` (tempo), `jumlah_bayar`, `jenis`
- **Logic:**
  ```php
  // 1. Get angsuran dan pinjaman
  $angsuran = TempoPinjamanB::findOrFail($id);
  $pinjaman = $angsuran->pinjaman;
  
  // 2. Hitung denda sebelum pembayaran
  $denda = $this->hitungDenda($angsuran, $pinjaman);
  
  // 3. Total yang harus dibayar
  $totalTagihanPlusDenda = $angsuran->jumlah_tagihan + $denda;
  
  // 4. Hitung jumlah terbayar setelah pembayaran baru
  $jumlahTerbayarSebelumnya = $angsuran->jumlah_terbayar ?? 0;
  $jumlahTerbayar = $jumlahTerbayarSebelumnya + $request->jumlah_bayar;
  
  // 5. Tentukan status bayar
  $statusBayar = 'belum';
  $tglBayar = null;
  
  if ($jumlahTerbayar >= $totalTagihanPlusDenda) {
    // Lunas penuh
    $statusBayar = 'lunas';
    $jumlahTerbayar = $totalTagihanPlusDenda;  // Cap di max
    $denda = 0;  // Reset denda
    $tglBayar = now();
  } elseif ($jumlahTerbayar >= $angsuran->jumlah_tagihan) {
    // Sudah bayar pokok, tapi masih ada denda
    $statusBayar = $angsuran->tgl_jatuh_tempo < now() ? 'telat' : 'belum';
    $tglBayar = now();
  } elseif ($angsuran->tgl_jatuh_tempo < now() && $jumlahTerbayar < $angsuran->jumlah_tagihan) {
    // Sudah telat dan belum lunas
    $statusBayar = 'telat';
  }
  
  // 6. Update angsuran
  $angsuran->update([
    'jumlah_terbayar' => $jumlahTerbayar,
    'denda' => $denda,
    'status_bayar' => $statusBayar,
    'tgl_bayar' => $tglBayar,
  ]);
  
  // 7. Check apakah semua angsuran sudah lunas
  $allAngsuran = $pinjaman->tempoBulanan;
  $allLunas = $allAngsuran->every(function($item) {
    return $item->status_bayar === 'lunas';
  });
  
  if ($allLunas) {
    $pinjaman->update(['lunas' => 'lunas']);
  }
  ```

---

#### **Method: `hitungDenda()` (PRIVATE)** - Hitung Denda
- **Trigger:** Dipanggil saat update pembayaran atau view detail
- **Input:** `$angsuran`, `$pinjaman`
- **Rules PENTING:**
  1. Jika `status_bayar='lunas'`: return denda tersimpan
  2. Jika `jumlah_terbayar > 0`: return denda tersimpan (**DENDA BERHENTI**)
  3. Jika belum H+1 setelah jatuh tempo: return 0
  4. Hitung real-time jika belum bayar & sudah telat

- **Logic:**
  ```php
  // 1. Jika sudah lunas
  if ($angsuran->status_bayar === 'lunas') {
    return $angsuran->denda ?? 0;
  }
  
  // 2. Jika sudah ada pembayaran (walau Rp 1), DENDA BERHENTI
  if ($angsuran->jumlah_terbayar > 0) {
    return $angsuran->denda ?? 0;
  }
  
  // 3. Hitung hari telat mulai H+1
  $tanggalMulaiDenda = $angsuran->tgl_jatuh_tempo->copy()->addDay();
  
  if (now() < $tanggalMulaiDenda) {
    return 0;  // Belum H+1
  }
  
  $hariTelat = now()->diffInDays($tanggalMulaiDenda);
  
  if ($hariTelat < 0) {
    return 0;
  }
  
  // 4. Get denda persen dari pinjaman
  $dendaPersen = $pinjaman->denda_persen ?? 0.30;  // Default 0.3%/hari
  
  // 5. PENTING: Denda dihitung dari POKOK per bulan, BUKAN total tagihan
  $pokokPerBulan = $pinjaman->jumlah_pinjam / $pinjaman->lama_pinjam;
  
  // 6. Formula denda
  $denda = $pokokPerBulan * ($dendaPersen / 100) * $hariTelat;
  
  return round($denda, 2);
  ```

**Contoh Perhitungan:**
```
Pinjaman: 3.000.000, 3 bulan
Pokok per bulan = 3.000.000 / 3 = 1.000.000
Denda persen = 0.30% per hari

Jatuh tempo: 04-03-2026
Hari ini: 06-03-2026 (2 hari setelah jatuh tempo)
Hari telat = 2 - 1 = 1 hari (mulai H+1)

Denda = 1.000.000 × (0.30 / 100) × 1
      = 1.000.000 × 0.003 × 1
      = 3.000

Jika nasabah bayar Rp 1 saja di tanggal 06-03-2026:
  - Denda tersimpan = 3.000
  - Denda TIDAK bertambah lagi walau lewat tanggal 07, 08, dst
  - Denda tetap 3.000 sampai lunas
```

---

## 📊 FLOW DIAGRAM - ALUR DATA LENGKAP

### 💰 PINJAMAN - Pengajuan sampai Pencairan (TRANSFER)

```
[NASABAH] Submit Form Pengajuan Transfer
  - nominal: 3.000.000
  - durasi: 3 bulan
  - pin: ******
    ↓
[SYSTEM] Verify PIN
    ↓
INSERT tbl_pengajuan_pinjaman
  - id: AUTO (PJ-20260204-A1B2C3D4)
  - id_anggota: 1
  - nominal: 3.000.000
  - jenis: 'bulanan'
  - durasi: 3
  - jenis_pencairan: 'transfer'
  - status: '1' (Pending)
  - tgl_pengajuan: 2026-02-04
    ↓
[ADMIN] Review Pengajuan
    ↓
[ADMIN] Click "Approve"
    ↓
[SYSTEM] Get Bunga dari master_bunga_pinjaman
  - WHERE durasi_min <= 3 AND durasi_max >= 3
  - Result: bunga_persen = 2.5
    ↓
UPDATE tbl_pengajuan_pinjaman
  - status: '3' (Disetujui)
  - bunga_persen: 2.5
    ↓
[ADMIN] Click "Cairkan"
  - Input tgl_cair: 2026-02-04
  - Upload bukti_transfer (optional)
    ↓
BEGIN TRANSACTION
    ↓
[SYSTEM] Hitung Bunga
  - bunga_rp = 3.000.000 × (2.5 / 100) = 75.000
  - jumlah_pinjam = 3.000.000
  - ags_bulan = (3.000.000 + 75.000) / 3 = 1.025.000
    ↓
[SYSTEM] Generate ID Pinjaman
  - idPinjaman = "04022026-0001-PTDPNJM"
    ↓
INSERT tbl_pinjaman_h
  - id: "04022026-0001-PTDPNJM"
  - id_anggota: 1
  - id_pengajuan: "PJ-20260204-A1B2C3D4"
  - jumlah_pinjam: 3.000.000
  - lama_pinjam: 3
  - jenis: 'bulanan'
  - bunga: 2.5
  - bunga_rp: 75.000
  - denda_persen: 0.30
  - ags_bulan: 1.025.000
  - tgl_pinjam: 2026-02-04
  - lunas: 'belum'
    ↓
[SYSTEM] Generate Jadwal Angsuran
  - LOOP i = 1 to 3:
      ↓
    INSERT tempo_pinjaman_b (Angsuran 1)
      - id: "04022026-0001-PTTPNJM"
      - pinjaman_id: "04022026-0001-PTDPNJM"
      - no_urut: 1
      - tgl_jatuh_tempo: 2026-03-04
      - jumlah_tagihan: 1.025.000
      - jumlah_terbayar: 0
      - denda: 0
      - status_bayar: 'belum'
      ↓
    INSERT tempo_pinjaman_b (Angsuran 2)
      - id: "04022026-0002-PTTPNJM"
      - no_urut: 2
      - tgl_jatuh_tempo: 2026-04-04
      - jumlah_tagihan: 1.025.000
      - ...
      ↓
    INSERT tempo_pinjaman_b (Angsuran 3)
      - id: "04022026-0003-PTTPNJM"
      - no_urut: 3
      - tgl_jatuh_tempo: 2026-05-04
      - jumlah_tagihan: 1.025.000
      - ...
    ↓
UPDATE tbl_pengajuan_pinjaman
  - status: '4' (Terlaksana)
  - tgl_cair: 2026-02-04
    ↓
COMMIT TRANSACTION
    ↓
[RESULT] Pinjaman Aktif & Siap Dibayar
  - Total yang harus dibayar: 3.075.000
  - Terbagi 3 angsuran @ 1.025.000
```

---

### 💰 PINJAMAN - Pembayaran Angsuran (TRANSFER)

```
[NASABAH] Akses Halaman Pembayaran
    ↓
[NASABAH] Pilih Pinjaman: "04022026-0001-PTDPNJM"
    ↓
[SYSTEM] Load Angsuran yang Belum Lunas
  SELECT * FROM tempo_pinjaman_b
  WHERE pinjaman_id = "04022026-0001-PTDPNJM"
  AND status_bayar != 'lunas'
  ORDER BY no_urut
    ↓
[NASABAH] Pilih Angsuran: Angsuran 1 (04022026-0001-PTTPNJM)
    ↓
[SYSTEM] Verifikasi Kepemilikan
  SELECT * FROM tempo_pinjaman_b
  WHERE id = "04022026-0001-PTTPNJM"
  AND EXISTS (
    SELECT 1 FROM tbl_pinjaman_h
    WHERE id = pinjaman_id
    AND id_anggota = 1
  )
    ↓
[SYSTEM] Tampilkan Detail Angsuran
  - Tagihan Pokok + Bunga: 1.025.000
  - Denda (jika telat): calculated real-time
  - Total: 1.025.000 + denda
    ↓
[NASABAH] Submit Pembayaran
  - nominal: 1.025.000
  - Upload bukti transfer
  - Input PIN
    ↓
[SYSTEM] Verify PIN
    ↓
INSERT tbl_pengajuan_pembayaran_pinjaman
  - id_anggota: 1
  - pinjaman_id: "04022026-0001-PTDPNJM"
  - tempo_id: "04022026-0001-PTTPNJM"
  - jenis_tempo: 'bulanan'
  - nominal: 1.025.000
  - rekening_tujuan: "1234567890"
  - status: '1' (Pending)
    ↓
INSERT tbl_bukti_foto_pembayaran_pinjaman (untuk setiap foto)
  - id_pengajuan: (ID pengajuan payment)
  - file_photo: "storage/path/to/bukti.jpg"
  - jenis: 'bukti_transfer'
    ↓
[ADMIN] Review Pengajuan Pembayaran
    ↓
[ADMIN] Approve Pengajuan
    ↓
UPDATE tbl_pengajuan_pembayaran_pinjaman
  - status: '3' (Disetujui)
  - tgl_pembayaran: now()
    ↓
[ADMIN] Input Pembayaran ke Tempo
    ↓
[SYSTEM] Get Angsuran
  SELECT * FROM tempo_pinjaman_b
  WHERE id = "04022026-0001-PTTPNJM"
    ↓
[SYSTEM] Hitung Denda (jika telat)
  - Hari ini: 2026-03-06
  - Jatuh tempo: 2026-03-04
  - Mulai denda: 2026-03-05 (H+1)
  - Hari telat: 1 hari
  - Pokok per bulan: 3.000.000 / 3 = 1.000.000
  - Denda: 1.000.000 × 0.003 × 1 = 3.000
    ↓
[SYSTEM] Calculate Total
  - jumlah_tagihan: 1.025.000
  - denda: 3.000
  - total_tagihan: 1.028.000
  - jumlah_terbayar_lama: 0
  - jumlah_bayar_baru: 1.025.000
  - jumlah_terbayar_total: 1.025.000
    ↓
[SYSTEM] Tentukan Status
  - IF 1.025.000 >= 1.028.000? NO
  - IF tgl_jatuh_tempo < now()? YES
  - MAKA status_bayar = 'telat' (sudah bayar sebagian, tapi masih kurang)
    ↓
UPDATE tempo_pinjaman_b
  - jumlah_terbayar: 1.025.000
  - denda: 3.000 (tersimpan, tidak bertambah lagi)
  - status_bayar: 'telat'
  - tgl_bayar: 2026-03-06
    ↓
[NASABAH] Bayar Sisa (Denda 3.000)
    ↓
[ADMIN] Input Pembayaran Tambahan: 3.000
    ↓
[SYSTEM] Calculate Again
  - jumlah_terbayar_lama: 1.025.000
  - jumlah_bayar_baru: 3.000
  - jumlah_terbayar_total: 1.028.000
  - IF 1.028.000 >= 1.028.000? YES → LUNAS
    ↓
UPDATE tempo_pinjaman_b
  - jumlah_terbayar: 1.028.000
  - denda: 0 (reset karena lunas)
  - status_bayar: 'lunas'
    ↓
[SYSTEM] Check Semua Angsuran
  SELECT COUNT(*) FROM tempo_pinjaman_b
  WHERE pinjaman_id = "04022026-0001-PTDPNJM"
  AND status_bayar != 'lunas'
    ↓
  IF count = 0 (semua lunas):
    UPDATE tbl_pinjaman_h
    SET lunas = 'lunas'
    WHERE id = "04022026-0001-PTDPNJM"
```

---

## 📋 RINGKASAN PENTING

### ✅ **Format ID yang Digunakan:**

1. **Pengajuan Pinjaman:**
   - Format: `PJ-YYYYMMDD-XXXXXXXX`
   - Contoh: `PJ-20260204-A1B2C3D4`
   - Generate: Auto oleh Model `boot()` method

2. **Pinjaman Header:**
   - Format: `DDMMYYYY-SEQQ-PTDPNJM`
   - Contoh: `04022026-0001-PTDPNJM`
   - Generate: Manual via `IdGenerator::generate()`

3. **Tempo Pinjaman:**
   - Format: `DDMMYYYY-SEQQ-PTTPNJM`
   - Contoh: `04022026-0001-PTTPNJM`, `04022026-0002-PTTPNJM`
   - Generate: Manual via `IdGenerator::generate()` + increment

---

### 🔑 **Key Points:**

1. **Auto-Generate ID Pengajuan:**
   - Model `PengajuanPinjaman` punya method `boot()`
   - Auto-generate saat `creating` event
   - Format: `PJ-[TANGGAL]-[RANDOM8CHAR]`

2. **Status Flow Pengajuan:**
   ```
   1 (Pending) → 3 (Disetujui) → 4 (Terlaksana)
                ↘ 2 (Ditolak)
   ```

3. **Bunga Sistem:**
   - Tidak dipotong di awal
   - Dibagi merata ke setiap angsuran
   - Total dibayar = Pokok + Bunga

4. **Denda Keterlambatan:**
   - **0.3% per hari** dari **POKOK per bulan** (bukan total tagihan)
   - Mulai dihitung **H+1** setelah jatuh tempo
   - **BERHENTI** jika sudah ada pembayaran (walau Rp 1)
   - Tersimpan di kolom `denda` saat ada pembayaran

5. **Relasi Tempo:**
   - Tabel `tempo_pinjaman_b` **TIDAK** punya kolom `anggota_id`
   - Verifikasi kepemilikan via `whereHas('pinjaman')`
   - Model punya relasi `pinjaman()` → BelongsTo

6. **Transaction Safety:**
   - Pencairan pinjaman pakai `DB::transaction()`
   - Ensure atomicity: create pinjaman + generate tempo + update pengajuan

---

### ❗ **Common Pitfalls:**

1. ❌ **Jangan query tempo pakai `anggota_id`** → kolom tidak ada!
   - ✅ Pakai: `whereHas('pinjaman', fn($q) => $q->where('id_anggota', $id))`

2. ❌ **Jangan hitung denda dari total tagihan**
   - ✅ Hitung dari: `jumlah_pinjam / lama_pinjam` (pokok per bulan)

3. ❌ **Jangan lupakan auto-generate ID pada model**
   - ✅ Model `PengajuanPinjaman` punya `boot()` method

4. ❌ **Jangan lupakan relasi `tempoBulanan()` dan `tempoMingguan()`**
   - ✅ Model `PinjamanH` harus punya kedua relasi ini

---

### 📊 **Ringkasan Perhitungan:**

**Contoh Kasus: Pinjaman 3 juta, 3 bulan, bunga 2.5%**

| Item | Perhitungan | Hasil |
|------|-------------|-------|
| Nominal Pinjaman | Input | 3.000.000 |
| Bunga Persen | Dari Master | 2.5% |
| Bunga Rupiah | 3.000.000 × 2.5% | 75.000 |
| Total Harus Dibayar | 3.000.000 + 75.000 | 3.075.000 |
| Pokok per Bulan | 3.000.000 / 3 | 1.000.000 |
| Bunga per Bulan | 75.000 / 3 | 25.000 |
| **Angsuran per Bulan** | 1.000.000 + 25.000 | **1.025.000** |

**Jika Telat 1 Hari:**

| Item | Perhitungan | Hasil |
|------|-------------|-------|
| Pokok per Bulan | 3.000.000 / 3 | 1.000.000 |
| Denda Persen | Dari Master | 0.3% per hari |
| Hari Telat | Sejak H+1 | 1 hari |
| **Denda** | 1.000.000 × 0.3% × 1 | **3.000** |
| Total Harus Bayar | 1.025.000 + 3.000 | **1.028.000** |

---

📅 **Dokumentasi dibuat:** 4 Februari 2026  
🔍 **Berdasarkan:** Database Schema + Source Code V2 (Post Refactoring)  
✅ **Status:** Final - Tested & Working
