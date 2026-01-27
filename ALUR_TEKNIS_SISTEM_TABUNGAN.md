# 🔄 ALUR TEKNIS SISTEM TABUNGAN KOPERASI MAJAKARA

## 📋 DAFTAR ISI
1. [Overview Alur](#overview-alur)
2. [Alur Pengajuan Setoran Transfer](#alur-pengajuan-setoran-transfer)
3. [Alur Pengajuan Setoran Tunai (Janji Temu)](#alur-pengajuan-setoran-tunai-janji-temu)
4. [Alur Pengajuan Penarikan](#alur-pengajuan-penarikan)
5. [Alur Approval Admin](#alur-approval-admin)
6. [Database Flow](#database-flow)
7. [Error Handling](#error-handling)
8. [Validation Rules](#validation-rules)

---

## 🎯 OVERVIEW ALUR

### Entities Terlibat
```
1. NASABAH
   - Mengajukan setoran (transfer/tunai)
   - Mengajukan penarikan
   - Melihat status pengajuan
   - Melihat riwayat transaksi

2. ADMIN
   - Menerima & verifikasi pengajuan
   - Approve/reject pengajuan
   - Membuat transaksi dari janji temu
   - Mengelola saldo nasabah

3. SISTEM
   - Validasi input
   - Perhitungan saldo
   - Create transaksi otomatis
   - Notifikasi (future)
```

### Status Flow
```
PENGAJUAN:
Pending ('1') → Approved ('2') OR Rejected ('3')

TRANSAKSI:
Dibuat otomatis saat approve (kecuali janji temu)
```

---

## 📤 ALUR PENGAJUAN SETORAN TRANSFER

### Flow Diagram
```
NASABAH                          SISTEM                          DATABASE
   |                                |                                |
   |---> Access nabung-sekarang     |                                |
   |                                |                                |
   |---> Pilih metode "Transfer"    |                                |
   |                                |                                |
   |---> Input nominal              |                                |
   |---> Upload bukti transfer(s)   |                                |
   |---> Input keterangan           |                                |
   |                                |                                |
   |---> Click "Ajukan Setoran"     |                                |
   |                                |---> Show PIN Modal             |
   |                                |                                |
   |---> Input PIN (6 digit)        |                                |
   |                                |                                |
   |---> Click "Verifikasi"         |                                |
   |                                |---> Validate PIN               |
   |                                |---> Validate form              |
   |                                |     - Nominal >= 10000         |
   |                                |     - Min 1 bukti foto         |
   |                                |     - File size <= 5MB         |
   |                                |                                |
   |                                |---> Store uploaded files       |---> storage/bukti_tabungan/
   |                                |                                |
   |                                |---> Create Pengajuan           |---> tbl_pengajuan_tabungan
   |                                |     - id_anggota               |     (status: '1' = Pending)
   |                                |     - foto_bukti_tf: 'transfer'|
   |                                |     - keterangan               |
   |                                |     - status: '1'              |
   |                                |                                |
   |                                |---> Create BuktiFoto(s)        |---> tbl_bukti_foto_tabungan
   |                                |     - id_pengajuan             |     (multiple records)
   |                                |     - file_photo: path         |
   |                                |     - nominal: per foto        |
   |                                |     - keterangan: per foto     |
   |                                |                                |
   |<--- Redirect ke status         |                                |
   |     pengajuan setor            |                                |
   |<--- Success message            |                                |
   |                                |                                |
```

### Controller Method: `submitSetoran()`
```php
Location: app/Http/Controllers/Nasabah/TabunganController.php

public function submitSetoran(Request $request)
{
    // 1. Validate request
    $request->validate([
        'pin' => 'required|numeric|digits:6',
        'nominal' => 'required|numeric|min:10000',
        'keterangan' => 'nullable|string|max:500',
        'bukti_foto.*' => 'required|image|max:5120',  // 5MB
        'nominal_foto.*' => 'required|string',
        'keterangan_foto.*' => 'nullable|string|max:255',
    ]);
    
    // 2. Verify PIN
    $user = auth()->user();
    if (!$user->pin || $user->pin != $request->pin) {
        return redirect()->back()
            ->with('error', 'PIN salah!')
            ->withInput();
    }
    
    // 3. Get ID Anggota
    $idAnggota = auth()->user()->nasabah->id;
    
    // 4. Create Pengajuan Tabungan
    $pengajuan = PengajuanTabungan::create([
        'id_anggota' => $idAnggota,
        'foto_bukti_tf' => 'transfer',  // Indicator
        'keterangan' => $request->keterangan,
        'status' => '1',  // Pending
    ]);
    
    // 5. Handle multiple bukti foto
    if ($request->hasFile('bukti_foto')) {
        foreach ($request->file('bukti_foto') as $index => $file) {
            // Store file
            $path = $file->store('bukti_tabungan', 'public');
            
            // Parse nominal from formatted currency
            $nominalStr = $request->nominal_foto[$index] ?? '0';
            $nominal = (float) str_replace(['.', ','], '', $nominalStr);
            
            // Create BuktiFotoTabungan
            BuktiFotoTabungan::create([
                'id_pengajuan' => $pengajuan->id,
                'file_photo' => $path,
                'jenis' => 'tabungan',
                'nominal' => $nominal > 0 ? $nominal : $request->nominal,
                'keterangan' => $request->keterangan_foto[$index] ?? 'Bukti transfer',
            ]);
        }
    }
    
    // 6. Redirect dengan success message
    return redirect()->route('nasabah.tabungan.status-pengajuan-setor')
        ->with('success', 'Pengajuan setoran berhasil dikirim!');
}
```

### View: `pengajuan-transfer.blade.php`
```blade
Location: resources/views/nasabah/tabungan/pengajuan-transfer.blade.php

Features:
- Form dengan multiple file upload
- Currency input dengan formatting
- PIN modal untuk verifikasi
- Preview image sebelum upload
- Dynamic add/remove bukti foto fields
```

---

## 🤝 ALUR PENGAJUAN SETORAN TUNAI (JANJI TEMU)

### Flow Diagram
```
NASABAH                          SISTEM                          DATABASE
   |                                |                                |
   |---> Access nabung-sekarang     |                                |
   |                                |                                |
   |---> Pilih metode "Tunai"       |                                |
   |                                |                                |
   |---> Input nominal              |                                |
   |---> Input keterangan           |                                |
   |                                |                                |
   |---> Click "Lanjut"             |                                |
   |                                |---> Redirect ke janji-temu     |
   |                                |     dengan data nominal        |
   |                                |                                |
   |---> Pilih lokasi temu          |                                |
   |---> Pilih tanggal (> today)    |                                |
   |---> Pilih waktu                |                                |
   |                                |                                |
   |---> Click "Ajukan"             |                                |
   |                                |---> Show PIN Modal             |
   |                                |                                |
   |---> Input PIN                  |                                |
   |                                |                                |
   |---> Click "Verifikasi"         |                                |
   |                                |---> Validate PIN               |
   |                                |---> Validate form              |
   |                                |     - Nominal >= 10000         |
   |                                |     - Tanggal > today          |
   |                                |     - Lokasi exists            |
   |                                |                                |
   |                                |---> Create Pengajuan           |---> tbl_pengajuan_tabungan
   |                                |     - id_anggota               |     (status: '1' = Pending)
   |                                |     - foto_bukti_tf: 'tunai'  |
   |                                |     - keterangan               |
   |                                |     - status: '1'              |
   |                                |                                |
   |                                |---> Create JanjiTemu           |---> tbl_janji_temu_tabungan
   |                                |     - id_pengajuan             |
   |                                |     - lokasi_temu              |
   |                                |     - nominal                  |
   |                                |     - tanggal_janji_temu       |
   |                                |     - waktu_janji_temu         |
   |                                |                                |
   |<--- Redirect ke status         |                                |
   |     pengajuan setor            |                                |
   |<--- Success message            |                                |
   |                                |                                |
```

### Controller Method: `submitJanjiTemu()`
```php
Location: app/Http/Controllers/Nasabah/TabunganController.php

public function submitJanjiTemu(Request $request)
{
    // 1. Validate request
    $validated = $request->validate([
        'pin' => 'required|numeric|digits:6',
        'nominal' => 'required|numeric|min:10000',
        'lokasi_temu' => 'required|exists:jns_lokasi_perusahaan,id',
        'tanggal_janji_temu' => 'required|date|after:today',
        'waktu_janji_temu' => 'required|date_format:H:i',
        'keterangan' => 'nullable|string|max:500',
    ]);
    
    // 2. Verify PIN
    $user = auth()->user();
    if (!$user->pin || $user->pin != $request->pin) {
        return redirect()->back()
            ->with('error', 'PIN salah!')
            ->withInput();
    }
    
    // 3. Get ID Anggota
    $idAnggota = auth()->user()->nasabah->id;
    
    // 4. Create Pengajuan Tabungan
    $pengajuan = PengajuanTabungan::create([
        'id_anggota' => $idAnggota,
        'foto_bukti_tf' => 'tunai',  // Indicator
        'keterangan' => $request->keterangan,
        'status' => '1',  // Pending
    ]);
    
    // 5. Create Janji Temu
    $tanggalWaktu = \Carbon\Carbon::parse(
        $request->tanggal_janji_temu . ' ' . $request->waktu_janji_temu
    );
    
    JanjiTemuTabungan::create([
        'id_pengajuan' => $pengajuan->id,
        'lokasi_temu' => $request->lokasi_temu,
        'nominal' => $request->nominal,
        'tanggal_janji_temu' => $tanggalWaktu,
        'waktu_janji_temu' => $tanggalWaktu,
    ]);
    
    // 6. Redirect dengan success message
    return redirect()->route('nasabah.tabungan.status-pengajuan-setor')
        ->with('success', 'Janji temu berhasil dibuat!');
}
```

---

## 📥 ALUR PENGAJUAN PENARIKAN

### Flow Diagram
```
NASABAH                          SISTEM                          DATABASE
   |                                |                                |
   |---> Access penarikan           |                                |
   |                                |---> Get saldo nasabah          |
   |                                |                                |
   |<--- Display saldo              |                                |
   |                                |                                |
   |---> Pilih metode               |                                |
   |     - Transfer: input no rek   |                                |
   |     - Tunai                    |                                |
   |                                |                                |
   |---> Input nominal              |                                |
   |---> Input keterangan           |                                |
   |                                |                                |
   |---> Click "Ajukan Penarikan"   |                                |
   |                                |---> Validate nominal           |
   |                                |     - Check saldo >= nominal   |
   |                                |                                |
   |                                |---> If insufficient:           |
   |<--- Error: Saldo tidak cukup   |<--- Return error               |
   |                                |                                |
   |                                |---> If sufficient:             |
   |                                |---> Create Pengajuan           |---> tbl_pengajuan_penarikan_tabungan
   |                                |     - id_anggota               |     (status: '1' = Pending)
   |                                |     - tgl_pengajuan: now()     |
   |                                |     - nominal                  |
   |                                |     - keterangan (+ no rek)    |
   |                                |     - status: '1'              |
   |                                |                                |
   |<--- Redirect ke status         |                                |
   |     pengajuan tarik            |                                |
   |<--- Success message            |                                |
   |                                |                                |
```

### Controller Method: `submitPenarikan()`
```php
Location: app/Http/Controllers/Nasabah/TabunganController.php

public function submitPenarikan(Request $request)
{
    // 1. Validate request
    $request->validate([
        'metode' => 'required|in:tunai,transfer',
        'nominal' => 'required|numeric|min:10000',
        'keterangan' => 'nullable|string|max:500',
        'no_rekening' => 'required_if:metode,transfer|string|max:50',
    ]);
    
    // 2. Get ID Anggota
    $idAnggota = auth()->user()->nasabah->id;
    
    // 3. Check saldo
    $saldo = $this->getSaldoNasabah($idAnggota);
    if ($saldo < $request->nominal) {
        return redirect()->back()
            ->with('error', 'Saldo tidak mencukupi!')
            ->withInput();
    }
    
    // 4. Prepare keterangan (include no rekening if transfer)
    $keterangan = $request->keterangan;
    if ($request->metode === 'transfer') {
        $keterangan .= ' | Rekening: ' . $request->no_rekening;
    }
    
    // 5. Create Pengajuan Penarikan
    PengajuanPenarikanTabungan::create([
        'id_anggota' => $idAnggota,
        'tgl_pengajuan' => now(),
        'nominal' => $request->nominal,
        'keterangan' => $keterangan,
        'status' => '1',  // Pending
    ]);
    
    // 6. Redirect dengan success message
    return redirect()->route('nasabah.tabungan.status-pengajuan-tarik')
        ->with('success', 'Pengajuan penarikan berhasil dikirim!');
}
```

---

## ✅ ALUR APPROVAL ADMIN

### A. Approve Setoran Transfer

```
ADMIN                            SISTEM                          DATABASE
   |                                |                                |
   |---> Access pengajuan-setor     |                                |
   |                                |---> Get pending pengajuan      |
   |                                |                                |
   |<--- Display list               |                                |
   |                                |                                |
   |---> Click detail pengajuan     |                                |
   |                                |---> Get pengajuan dengan       |
   |                                |     - buktiFoto (eager load)   |
   |                                |     - nasabah data             |
   |                                |                                |
   |<--- Display detail + bukti     |                                |
   |                                |                                |
   |---> Click "Setujui"            |                                |
   |                                |---> Validate nominal           |
   |                                |     - Sum bukti foto > 0       |
   |                                |                                |
   |                                |---> Update status              |---> tbl_pengajuan_tabungan
   |                                |     status = '2' (Approved)    |     (update)
   |                                |                                |
   |                                |---> Create Transaksi           |---> trans_tabungan
   |                                |     - id_pengajuan_setor       |     (insert)
   |                                |     - id_anggota               |
   |                                |     - nominal: sum bukti foto  |
   |                                |     - jenis: 'setoran'         |
   |                                |     - via: 'transfer'          |
   |                                |     - tgl_transaksi: now()     |
   |                                |                                |
   |                                |---> Saldo nasabah bertambah    |
   |                                |                                |
   |<--- Redirect ke pengajuan-setor|                                |
   |<--- Success message            |                                |
   |                                |                                |
```

### Controller Method: `approveSetor()`
```php
Location: app/Http/Controllers/Admin/TabunganController.php

public function approveSetor(Request $request, $id)
{
    // 1. Get pengajuan dengan bukti foto
    $pengajuan = PengajuanTabungan::with(['buktiFoto', 'janjiTemu', 'transTabungan'])
        ->findOrFail($id);
    
    // 2. Update status to approved
    $pengajuan->update(['status' => '2']);
    
    // 3. Get nominal dari bukti foto atau janji temu
    $nominal = 0;
    if ($pengajuan->buktiFoto && $pengajuan->buktiFoto->count() > 0) {
        $nominal = $pengajuan->buktiFoto->sum('nominal');
    } elseif ($pengajuan->janjiTemu) {
        $nominal = $pengajuan->janjiTemu->nominal ?? 0;
    }
    
    // 4. Validate nominal
    if ($nominal <= 0) {
        return redirect()->back()
            ->with('error', 'Nominal tidak valid!');
    }
    
    // 5. Check duplicate transaksi
    if ($pengajuan->transTabungan->count() > 0) {
        return redirect()->back()
            ->with('error', 'Transaksi sudah pernah dibuat!');
    }
    
    // 6. Create transaksi tabungan
    TransTabungan::create([
        'id_pengajuan_setor' => $pengajuan->id,
        'id_anggota' => $pengajuan->id_anggota,
        'nominal' => $nominal,
        'keterangan' => $pengajuan->keterangan ?? 'Setoran tabungan disetujui',
        'jenis' => 'setoran',
        'via' => $pengajuan->janjiTemu ? 'cash' : 'transfer',
        'tgl_transaksi' => now(),
    ]);
    
    // 7. Redirect dengan success
    return redirect()->route('admin.tabungan.pengajuan-setor')
        ->with('success', 'Pengajuan setoran berhasil disetujui!');
}
```

### B. Approve Penarikan

```
ADMIN                            SISTEM                          DATABASE
   |                                |                                |
   |---> Access pengajuan-tarik     |                                |
   |                                |---> Get pending pengajuan      |
   |                                |                                |
   |<--- Display list               |                                |
   |                                |                                |
   |---> Click detail pengajuan     |                                |
   |                                |---> Get pengajuan              |
   |                                |---> Calculate saldo nasabah    |
   |                                |                                |
   |<--- Display detail + saldo     |                                |
   |                                |                                |
   |---> Verify saldo mencukupi     |                                |
   |                                |                                |
   |---> Click "Setujui"            |                                |
   |                                |---> Validate saldo             |
   |                                |     - Check saldo >= nominal   |
   |                                |                                |
   |                                |---> If insufficient:           |
   |<--- Error: Saldo tidak cukup   |<--- Return error               |
   |                                |                                |
   |                                |---> If sufficient:             |
   |                                |---> Update status              |---> tbl_pengajuan_penarikan_tabungan
   |                                |     status = '2' (Approved)    |     (update)
   |                                |                                |
   |                                |---> Create Transaksi           |---> trans_tabungan
   |                                |     - id_pengajuan_tarik       |     (insert)
   |                                |     - id_anggota               |
   |                                |     - nominal                  |
   |                                |     - jenis: 'penarikan'       |
   |                                |     - via: 'transfer'          |
   |                                |     - tgl_transaksi: now()     |
   |                                |                                |
   |                                |---> Saldo nasabah berkurang    |
   |                                |                                |
   |<--- Redirect ke pengajuan-tarik|                                |
   |<--- Success message            |                                |
   |                                |                                |
```

### Controller Method: `approveTarik()`
```php
Location: app/Http/Controllers/Admin/TabunganController.php

public function approveTarik(Request $request, $id)
{
    // 1. Get pengajuan
    $pengajuan = PengajuanPenarikanTabungan::findOrFail($id);
    
    // 2. Check saldo nasabah
    $saldo = $this->getSaldoNasabah($pengajuan->id_anggota);
    
    if ($saldo < $pengajuan->nominal) {
        return redirect()->back()
            ->with('error', 'Saldo nasabah tidak mencukupi!');
    }
    
    // 3. Update status to approved
    $pengajuan->update(['status' => '2']);
    
    // 4. Create transaksi penarikan
    TransTabungan::create([
        'id_pengajuan_tarik' => $pengajuan->id,
        'id_anggota' => $pengajuan->id_anggota,
        'nominal' => $pengajuan->nominal,
        'keterangan' => $pengajuan->keterangan,
        'jenis' => 'penarikan',
        'via' => 'transfer',  // Default atau dari request
        'tgl_transaksi' => now(),
    ]);
    
    // 5. Redirect dengan success
    return redirect()->route('admin.tabungan.pengajuan-tarik')
        ->with('success', 'Pengajuan penarikan berhasil disetujui!');
}
```

### C. Create Transaksi dari Janji Temu

```
ADMIN                            SISTEM                          DATABASE
   |                                |                                |
   |---> Access janji-temu          |                                |
   |                                |---> Get list janji temu        |
   |                                |                                |
   |<--- Display list               |                                |
   |                                |                                |
   |---> Click detail janji temu    |                                |
   |                                |---> Get janji temu dengan      |
   |                                |     - pengajuan                |
   |                                |     - transTabungan (check)    |
   |                                |                                |
   |<--- Display detail             |                                |
   |                                |                                |
   |---> Verify transaksi belum ada |                                |
   |                                |                                |
   |---> PADA WAKTU BERTEMU:        |                                |
   |     - Terima uang tunai        |                                |
   |     - Input nominal aktual     |                                |
   |     - Upload foto penerimaan   |                                |
   |     - Input keterangan         |                                |
   |     - Pilih tgl transaksi      |                                |
   |                                |                                |
   |---> Click "Buat Transaksi"     |                                |
   |                                |---> Validate form              |
   |                                |     - Nominal >= 10000         |
   |                                |     - Check duplicate          |
   |                                |                                |
   |                                |---> If foto uploaded:          |
   |                                |---> Store foto                 |---> storage/bukti_tabungan/
   |                                |---> Create BuktiFoto           |---> tbl_bukti_foto_tabungan
   |                                |                                |
   |                                |---> Update pengajuan           |---> tbl_pengajuan_tabungan
   |                                |     status = '2' (Approved)    |     (update)
   |                                |                                |
   |                                |---> Create Transaksi           |---> trans_tabungan
   |                                |     - id_pengajuan_setor       |     (insert)
   |                                |     - id_anggota               |
   |                                |     - nominal (aktual)         |
   |                                |     - jenis: 'setoran'         |
   |                                |     - via: 'cash'              |
   |                                |     - tgl_transaksi: input     |
   |                                |                                |
   |<--- Redirect ke detail         |                                |
   |<--- Success message            |                                |
   |                                |                                |
```

### Controller Method: `createTransFromJanjiTemu()`
```php
Location: app/Http/Controllers/Admin/TabunganController.php

public function createTransFromJanjiTemu(Request $request, $id)
{
    // 1. Validate request
    $request->validate([
        'nominal' => 'required|string',
        'keterangan' => 'nullable|string|max:500',
        'foto_penerimaan' => 'nullable|image|max:5120',
        'tgl_transaksi' => 'required|date',
    ]);
    
    // 2. Parse nominal from formatted currency
    $nominal = (float) str_replace(['.', ','], '', $request->nominal);
    
    if ($nominal < 10000) {
        return redirect()->back()
            ->with('error', 'Nominal minimal Rp 10.000');
    }
    
    // 3. Get janji temu dengan pengajuan
    $janjiTemu = JanjiTemuTabungan::with('pengajuan')->findOrFail($id);
    $pengajuan = $janjiTemu->pengajuan;
    
    // 4. Check if transaksi already exists
    if ($pengajuan->transTabungan->count() > 0) {
        return redirect()->back()
            ->with('error', 'Transaksi sudah pernah dibuat!');
    }
    
    // 5. Handle foto penerimaan (optional)
    if ($request->hasFile('foto_penerimaan')) {
        $fotoPenerimaan = $request->file('foto_penerimaan')
            ->store('bukti_tabungan', 'public');
        
        // Save to bukti foto tabungan
        BuktiFotoTabungan::create([
            'id_pengajuan' => $pengajuan->id,
            'file_photo' => $fotoPenerimaan,
            'jenis' => 'tabungan',
            'nominal' => $nominal,
            'keterangan' => $request->keterangan ?? 'Bukti penerimaan dari janji temu',
        ]);
    }
    
    // 6. Update status pengajuan
    if ($pengajuan->status == '1') {
        $pengajuan->update(['status' => '2']);
    }
    
    // 7. Create transaksi tabungan
    TransTabungan::create([
        'id_pengajuan_setor' => $pengajuan->id,
        'id_anggota' => $pengajuan->id_anggota,
        'nominal' => $nominal,
        'keterangan' => $request->keterangan ?? 'Setoran tunai dari janji temu',
        'jenis' => 'setoran',
        'via' => 'cash',
        'tgl_transaksi' => $request->tgl_transaksi,
    ]);
    
    // 8. Redirect dengan success
    return redirect()->route('admin.tabungan.detail-janji-temu', $id)
        ->with('success', 'Transaksi berhasil dibuat!');
}
```

---

## 💾 DATABASE FLOW

### Relationship Diagram
```
tbl_nasabah
     |
     |--- tbl_pengajuan_tabungan
     |         |
     |         |--- tbl_bukti_foto_tabungan (1:many)
     |         |
     |         |--- tbl_janji_temu_tabungan (1:1)
     |         |         |
     |         |         |--- jns_lokasi_perusahaan (FK)
     |         |
     |         |--- trans_tabungan (1:many via id_pengajuan_setor)
     |
     |--- tbl_pengajuan_penarikan_tabungan
     |         |
     |         |--- trans_tabungan (1:many via id_pengajuan_tarik)
     |
     |--- trans_tabungan (direct 1:many via id_anggota)
```

### Saldo Calculation Logic
```php
private function getSaldoNasabah($idAnggota)
{
    // 1. Hitung total setoran dari trans_tabungan
    $totalSetoran = TransTabungan::where('id_anggota', $idAnggota)
        ->where('jenis', 'setoran')
        ->sum('nominal') ?? 0;
    
    // 2. Hitung total penarikan dari trans_tabungan
    $totalPenarikan = TransTabungan::where('id_anggota', $idAnggota)
        ->where('jenis', 'penarikan')
        ->sum('nominal') ?? 0;
    
    // 3. Tambahkan setoran dari pengajuan approved yang belum ada transaksi
    // (Edge case: admin approve tapi belum create transaksi)
    $pengajuanApproved = PengajuanTabungan::where('id_anggota', $idAnggota)
        ->where('status', '2')  // Approved
        ->whereDoesntHave('transTabungan')
        ->with('buktiFoto', 'janjiTemu')
        ->get();
    
    foreach ($pengajuanApproved as $pengajuan) {
        $nominal = 0;
        if ($pengajuan->buktiFoto && $pengajuan->buktiFoto->count() > 0) {
            $nominal = $pengajuan->buktiFoto->sum('nominal');
        } elseif ($pengajuan->janjiTemu) {
            $nominal = $pengajuan->janjiTemu->nominal ?? 0;
        }
        $totalSetoran += $nominal;
    }
    
    // 4. Return saldo (tidak boleh negatif)
    return max(0, $totalSetoran - $totalPenarikan);
}
```

---

## ⚠️ ERROR HANDLING

### Common Errors & Solutions

#### 1. PIN Salah
```php
Error: PIN yang Anda masukkan salah
Solution:
- Verify PIN di database matches input
- Convert both to integer for comparison
- Check if user has PIN (not null)
```

#### 2. Saldo Tidak Cukup
```php
Error: Saldo tidak mencukupi untuk penarikan
Solution:
- Calculate saldo sebelum submit
- Display saldo realtime di form
- Validate di controller before create pengajuan
```

#### 3. File Upload Failed
```php
Error: Gagal mengupload bukti transfer
Solution:
- Check file size <= 5MB
- Check file type (image only)
- Ensure storage/app/public/bukti_tabungan exists
- Run: php artisan storage:link
- Check folder permissions (775)
```

#### 4. Duplicate Transaction
```php
Error: Transaksi sudah pernah dibuat
Solution:
- Check transTabungan relationship before create
- Validate pengajuan->transTabungan->count() == 0
```

#### 5. Session Expired
```php
Error: Session Anda telah berakhir
Solution:
- Catch AuthenticationException
- Redirect to login
- Preserve intended URL
```

### Error Handling Pattern
```php
try {
    // Get ID anggota
    $idAnggota = $this->getIdAnggota();
    
    // Business logic...
    
} catch (\Illuminate\Auth\AuthenticationException $e) {
    return redirect()->route('login')
        ->with('error', 'Session berakhir. Silakan login kembali.');
        
} catch (\Illuminate\Auth\Access\AuthorizationException $e) {
    return redirect()->route('nasabah.dashboard')
        ->with('error', $e->getMessage());
        
} catch (\Exception $e) {
    Log::error('Error in TabunganController: ' . $e->getMessage());
    return redirect()->back()
        ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
        ->withInput($request->except('pin'));
}
```

---

## ✅ VALIDATION RULES

### Pengajuan Setoran Transfer
```php
[
    'pin' => 'required|numeric|digits:6',
    'nominal' => 'required|numeric|min:10000',
    'keterangan' => 'nullable|string|max:500',
    'bukti_foto.*' => 'required|image|max:5120',  // 5MB per file
    'nominal_foto.*' => 'required|string',
    'keterangan_foto.*' => 'nullable|string|max:255',
]
```

### Janji Temu
```php
[
    'pin' => 'required|numeric|digits:6',
    'nominal' => 'required|numeric|min:10000',
    'lokasi_temu' => 'required|exists:jns_lokasi_perusahaan,id',
    'tanggal_janji_temu' => 'required|date|after:today',
    'waktu_janji_temu' => 'required|date_format:H:i',
    'keterangan' => 'nullable|string|max:500',
]
```

### Pengajuan Penarikan
```php
[
    'metode' => 'required|in:tunai,transfer',
    'nominal' => 'required|numeric|min:10000',
    'keterangan' => 'nullable|string|max:500',
    'no_rekening' => 'required_if:metode,transfer|string|max:50',
]
```

### Create Transaksi dari Janji Temu
```php
[
    'nominal' => 'required|string',  // Will be parsed to numeric
    'keterangan' => 'nullable|string|max:500',
    'foto_penerimaan' => 'nullable|image|max:5120',
    'tgl_transaksi' => 'required|date',
]
```

---

## 🔄 STATE MANAGEMENT

### Pengajuan States
```
'1' = PENDING
- Just created
- Waiting admin verification
- Can be edited/deleted (admin only)

'2' = APPROVED
- Admin approved
- Transaction created (or will be created for janji temu)
- Cannot be edited
- Cannot be deleted

'3' = REJECTED
- Admin rejected
- Will not be processed
- Cannot be edited
- Can be deleted (admin only)
```

### Transaction Flow
```
NO TRANSACTION → PENDING
                   ↓
          (Admin Approve)
                   ↓
      APPROVED + TRANSACTION CREATED
                   ↓
          SALDO UPDATED
```

---

**END OF DOCUMENT**

*Last Updated: {{ now()->format('d F Y') }}*
*Version: 1.0*
