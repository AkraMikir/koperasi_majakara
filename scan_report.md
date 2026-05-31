# Flow Data dan Struktur Database

Berdasarkan rute dan controller untuk Tabungan, Pinjaman, Deposito, dan Gadai:

## Controller: `App\Http\Controllers\Nasabah\GuideController`

**Models Imported:**
- `App\Models\MasterBungaPinjaman`
- `App\Models\MasterDendaPinjaman`
- `App\Models\JnsAngsuranBulan`

### Route: `nasabah/guide/tabungan-setoran` [GET|HEAD]
**Function:** `tabunganSetoran`

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function tabunganSetoran()
    {
        return view('nasabah.guide.tabungan-setoran');
    }
```
</details>

### Route: `nasabah/guide/tabungan-penarikan` [GET|HEAD]
**Function:** `tabunganPenarikan`

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function tabunganPenarikan()
    {
        return view('nasabah.guide.tabungan-penarikan');
    }
```
</details>

### Route: `nasabah/guide/pinjaman-pengajuan` [GET|HEAD]
**Function:** `pinjamanPengajuan`

**Queries Detected:**
- Model: MasterBungaPinjaman
- Model: MasterDendaPinjaman
- Model: JnsAngsuranBulan

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function pinjamanPengajuan()
    {
        $bungaPinjaman = MasterBungaPinjaman::where('status_aktif', true)->orderBy('durasi_min')->get();
        $dendaPinjaman = MasterDendaPinjaman::getDendaAktif();
        $durasiList = JnsAngsuranBulan::where('aktif', 'y')->orderBy('bulan')->get();
        if ($durasiList->isEmpty()) {
            $durasiList = collect(range(1, 24))->map(fn ($b) => (object)['bulan' => $b, 'ket' => (string) $b]);
        }
        return view('nasabah.guide.pinjaman-pengajuan', [
            'bungaPinjaman' => $bungaPinjaman,
            'dendaPinjaman' => $dendaPinjaman,
            'durasiList' => $durasiList,
        ]);
    }
```
</details>

### Route: `nasabah/guide/pinjaman-pembayaran` [GET|HEAD]
**Function:** `pinjamanPembayaran`

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function pinjamanPembayaran()
    {
        return view('nasabah.guide.pinjaman-pembayaran');
    }
```
</details>

## Controller: `App\Http\Controllers\Nasabah\TabunganController`

**Models Imported:**
- `App\Models\PengajuanTabungan`
- `App\Models\PengajuanPenarikanTabungan`
- `App\Models\JanjiTemuTabungan`
- `App\Models\BuktiFotoTabungan`
- `App\Models\JnsBank`
- `App\Models\JnsLokasiPerusahaan`
- `App\Models\Nasabah`
- `App\Models\TransTabungan`
- `App\Models\BuktiFoto`
- `App\Models\User`

### Route: `nasabah/tabungan` [GET|HEAD]
**Function:** `index`

**Queries Detected:**
- Model: TransTabungan
- Model: JanjiTemuTabungan
- Model: PengajuanTabungan
- Model: PengajuanPenarikanTabungan

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function index(Request $request)
    {
        $idAnggota = $this->getIdAnggota();
        
        // Calculate saldo from database
        $saldoData = $this->getSaldoNasabah($idAnggota, true);
        
        // Tabungan info from database
        $tabunganInfo = (object) [
            'saldo'      => $saldoData['saldo'],
            'saldo_hold' => $saldoData['hold'],
            'bunga'      => 3.5,
            'status'     => 'Aktif',
        ];

        // Optimized Transaksi selection - Unique paginator 'page_trans'
        $transTabungans = TransTabungan::select('id', 'id_jns_transaksi', 'nominal', 'tgl_transaksi', 'id_jns_via')
            ->where('id_anggota', $idAnggota)
            ->with(['jnsTransaksi', 'jnsVia'])
            ->latest('tgl_transaksi')
            ->paginate(10, ['*'], 'page_trans')
            ->withQueryString();

        // Optimized Janji Temu selection - Unique paginator 'page_jt'
        $janjiTemuTabungans = JanjiTemuTabungan::select('id', 'nominal', 'status', 'tanggal_janji_temu', 'waktu_janji_temu', 'jenis', 'lokasi_temu')
            ->where('id_nasabah', $idAnggota)
            ->with('lokasi')
            ->latest('tanggal_janji_temu')
            ->paginate(10, ['*'], 'page_jt')
            ->withQueryString();

        // Optimized Pengajuan Setor (Transfer) - Unique paginator 'page_setor'
        $pengajuanSetors = PengajuanTabungan::where('id_anggota', $idAnggota)
            ->latest()
            ->paginate(10, ['*'], 'page_setor')
            ->withQueryString();

        // Optimized Pengajuan Tarik (Transfer) - Unique paginator 'page_tarik'
        $pengajuanTariks = PengajuanPenarikanTabungan::where('id_anggota', $idAnggota)
            ->where('metode_transfer', 'transfer')
            ->latest()
            ->paginate(10, ['*'], 'page_tarik')
            ->withQueryString();

        // Handle AJAX Pagination
        if ($request->ajax()) {
            if ($request->section === 'trans') {
                return view('nasabah.tabungan.partials._table_trans', compact('transTabungans'))->render();
            }
            if ($request->section === 'jt') {
                return view('nasabah.tabungan.partials._table_jt', compact('janjiTemuTabungans'))->render();
            }
            if ($request->section === 'setor') {
                return view('nasabah.tabungan.partials._table_setor', compact('pengajuanSetors'))->render();
            }
            if ($request->section === 'tarik') {
                return view('nasabah.tabungan.partials._table_tarik', compact('pengajuanTariks'))->render();
            }
        }

        return view('nasabah.tabungan.index', [
            'user' => Auth::user(),
            'tabunganInfo' => $tabunganInfo,
            'transTabungans' => $transTabungans,
            'janjiTemuTabungans' => $janjiTemuTabungans,
            'pengajuanSetors' => $pengajuanSetors,
            'pengajuanTariks' => $pengajuanTariks,
        ]);
    }
```
</details>

### Route: `nasabah/tabungan/nabung-sekarang` [GET|HEAD]
**Function:** `nabungSekarang`

**Queries Detected:**
- Model: TransTabungan
- Model: JnsLokasiPerusahaan
- Model: JnsBank

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function nabungSekarang()
    {
        $idAnggota = $this->getIdAnggota();
        
        // Get riwayat setoran from database
        $riwayatTabungan = TransTabungan::where('id_anggota', $idAnggota)
            ->whereHas('jnsTransaksi', function($q) {
                $q->where('kode', 'STR');
            })
            ->with(['jnsTransaksi', 'jnsVia'])
            ->latest('tgl_transaksi')
            ->take(10)
            ->get();

        // Get lokasi untuk janji temu
        $lokasi = JnsLokasiPerusahaan::where('status_aktif', true)->get();

        // Get data bank aktif
        $banks = JnsBank::where('status', 'aktif')->get();

        return view('nasabah.tabungan.nabung-sekarang', [
            'user' => Auth::user(),
            'riwayatTabungan' => $riwayatTabungan,
            'lokasi' => $lokasi,
            'banks' => $banks,
        ]);
    }
```
</details>

### Route: `nasabah/tabungan/pengajuan-transfer` [GET|HEAD]
**Function:** `pengajuanTransfer`

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function pengajuanTransfer()
    {
        return redirect()->route('nasabah.tabungan.nabung-sekarang');
    }
```
</details>

### Route: `nasabah/tabungan/pengajuan-transfer` [POST]
**Function:** `submitSetoran`

**Queries Detected:**
- Model: PengajuanTabungan
- Model: IdGenerator
- Model: BuktiFoto
- Model: AdminNotification
- Model: ActivityLogService

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function submitSetoran(Request $request)
    {
        // Check authentication first
        if (Auth::user() === null) {
            return redirect()->route('login')
                ->with('error', 'Session Anda telah berakhir. Silakan login kembali.');
        }

        $request->validate([
            'pin' => 'required|numeric|digits:6',
            'nominal' => 'required|numeric|min:10000',
            'keterangan' => 'nullable|string|max:500',
            'bukti_foto.*' => 'required|image|max:5120',
        ]);

        // Verify PIN
        /** @var User $user */
        $user = Auth::user();
        
        // Check if user has PIN
        if (!$user->pin) {
            return redirect()->back()
                ->with('error', 'PIN belum diatur. Silakan atur PIN terlebih dahulu di profil Anda.')
                ->withInput($request->except('pin'));
        }

        // Convert both to integer for comparison (handles string/int mismatch)
        $userPin = (int) $user->pin;
        $inputPin = (int) $request->pin;

        if ($userPin !== $inputPin) {
            return redirect()->route('nasabah.tabungan.nabung-sekarang')
                ->with('error', 'PIN yang Anda masukkan salah!')
                ->withInput($request->except('pin'));
        }

        try {
            // Get nasabah ID from auth
            $idAnggota = $this->getIdAnggota();

            $isTransfer = ($request->metode ?? 'transfer') === 'transfer';

            if ($isTransfer) {
                // Validate bukti foto exists
                if (!$request->hasFile('bukti_foto') || count($request->file('bukti_foto')) == 0) {
                    return redirect()->route('nasabah.tabungan.nabung-sekarang')
                        ->with('error', 'Minimal upload 1 bukti transfer')
                        ->withInput($request->except('pin'));
                }

                // 🛡️ Server-side guard: cegah duplikasi dalam 30 detik
                $recentDuplicate = \App\Models\PengajuanTabungan::where('id_anggota', $idAnggota)
                    ->where('nominal', $request->nominal)
                    ->where('status', '1')
                    ->where('created_at', '>=', now()->subSeconds(30))
                    ->exists();

                if ($recentDuplicate) {
                    return redirect()->route('nasabah.tabungan.status-pengajuan-setor')
                        ->with('warning', 'Pengajuan setoran yang sama sudah dikirim. Silakan tunggu beberapa saat.');
                }

                // Generate ID: Tabungan (T), Transfer (T), Setoran (STR)
                $idPengajuan = IdGenerator::generate('tbl_pengajuan_tabungan', 'T', 'T', 'STR');

                // Create pengajuan tabungan with nominal
                $pengajuan = PengajuanTabungan::create([
                    'id' => $idPengajuan,
                    'id_anggota' => $idAnggota,
                    'nominal' => $request->nominal,
                    // 'foto_bukti_tf' => 'transfer', // REMOVED
                    'keterangan' => $request->keterangan,
                    'status' => '1', // Pending
                ]);

                // Handle multiple bukti foto (hanya file, no nominal/keterangan)
                // id wajib diisi: tbl_bukti_foto pakai id string (bukan auto-increment)
                if ($request->hasFile('bukti_foto')) {
                    foreach ($request->file('bukti_foto') as $file) {
                        $path = $file->store('bukti_tabungan', 'public');
                        $idBuktiFoto = IdGenerator::generate('tbl_bukti_foto', 'T', 'T', 'STR');
                        BuktiFoto::create([
                            'id' => $idBuktiFoto,
                            'owner_id' => $idPengajuan,
                            'owner_fitur' => 'T',
                            'owner_trans' => 'STR',
                            'file_path' => $path,
                            'keterangan' => 'Bukti Transfer'
                        ]);
                    }
                }

                // Notifikasi untuk admin
                \App\Models\AdminNotification::notify(
                    'tabungan_setor',
                    'Pengajuan setoran tabungan baru',
                    'Nasabah mengajukan setoran transfer Rp ' . number_format((float) ($pengajuan->nominal ?? 0), 0, ',', '.'),
                    route('admin.tabungan.detail-pengajuan-setor', $pengajuan->id),
                    $pengajuan->id,
                    'pengajuan_tabungan'
                );

                app(ActivityLogService::class)->logSubmitSetoran($pengajuan->id, $pengajuan->nominal, 'transfer');

                return redirect()->route('nasabah.tabungan.status-pengajuan-setor')
                    ->with('success', 'Pengajuan setoran via transfer berhasil dikirim!');
            }

            return redirect()->route('nasabah.tabungan.nabung-sekarang')
                ->with('error', 'Metode tidak valid')
                ->withInput($request->except('pin'));
                
        } catch (\Illuminate\Auth\AuthenticationException $e) {
            return redirect()->route('login')
                ->with('error', 'Session Anda telah berakhir. Silakan login kembali.');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return redirect()->route('nasabah.dashboard')
                ->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->route('nasabah.tabungan.nabung-sekarang')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput($request->except('pin'));
        }
    }
```
</details>

### Route: `nasabah/tabungan/penarikan` [GET|HEAD]
**Function:** `penarikanTabungan`

**Queries Detected:**
- Model: TransTabungan
- Model: JnsLokasiPerusahaan
- Model: Nasabah

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function penarikanTabungan()
    {
        $idAnggota = $this->getIdAnggota();
        
        // Calculate saldo from database
        $saldo = $this->getSaldoNasabah($idAnggota);
        
        // Tabungan info from database
        $tabunganInfo = (object) [
            'saldo' => $saldo,
            'bunga' => 3.5,
            'status' => 'Aktif',
        ];

        // Get riwayat penarikan from database
        $riwayatPenarikan = TransTabungan::where('id_anggota', $idAnggota)
            ->whereHas('jnsTransaksi', function($q) {
                $q->where('kode', 'PNR');
            })
            ->with(['jnsTransaksi', 'jnsVia'])
            ->latest('tgl_transaksi')
            ->take(10)
            ->get();

        // Get active office locations
        $lokasi = JnsLokasiPerusahaan::where('status_aktif', true)->get();

        // Data rekening nasabah untuk auto-fill form transfer (bank & no rekening)
        $rekeningNasabah = Nasabah::with('dataRek')->find($idAnggota)?->dataRek;

        return view('nasabah.tabungan.penarikan-tabungan', [
            'user' => Auth::user(),
            'tabunganInfo' => $tabunganInfo,
            'riwayatPenarikan' => $riwayatPenarikan,
            'lokasi' => $lokasi,
            'rekeningNasabah' => $rekeningNasabah,
        ]);
    }
```
</details>

### Route: `nasabah/tabungan/penarikan` [POST]
**Function:** `submitPenarikan`

**Queries Detected:**
- Model: PengajuanPenarikanTabungan
- Model: IdGenerator
- Model: AdminNotification
- Model: JanjiTemuTabungan
- Model: Carbon
- Model: ActivityLogService

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function submitPenarikan(Request $request)
    {
        $request->validate([
            'pin' => 'required|numeric|digits:6',
            'metode' => 'required|in:tunai,transfer',
            'nominal' => 'required|numeric|min:10000',
            'keterangan' => 'nullable|string|max:500',
            'nama_bank' => 'nullable|required_if:metode,transfer|string|max:100',
            'no_rekening' => 'nullable|required_if:metode,transfer|string|max:50',
            'lokasi_temu' => 'nullable|required_if:metode,tunai|exists:jns_lokasi_perusahaan,id',
            'tanggal_janji_temu' => 'nullable|required_if:metode,tunai|date|after_or_equal:today',
            'waktu_janji_temu' => 'nullable|required_if:metode,tunai',
        ]);

        // Verify PIN
        /** @var User $user */
        $user = Auth::user();
        if (!$user->pin || (int)$user->pin !== (int)$request->pin) {
            return redirect()->back()
                ->with('error', 'PIN yang Anda masukkan salah!')
                ->withInput($request->except('pin'));
        }

        $idAnggota = $this->getIdAnggota();

        // Check saldo
        $saldo = $this->getSaldoNasabah($idAnggota);
        if ($saldo < $request->nominal) {
            return redirect()->back()
                ->with('error', 'Saldo tidak mencukupi!')
                ->withInput($request->except('pin'));
        }

        // 🛡️ Server-side guard: cegah duplikasi dalam 30 detik
        $recentDuplicate = \App\Models\PengajuanPenarikanTabungan::where('id_anggota', $idAnggota)
            ->where('nominal', $request->nominal)
            ->where('status', '1') // pending
            ->where('created_at', '>=', now()->subSeconds(30))
            ->exists();

        if ($recentDuplicate) {
            return redirect()->route('nasabah.tabungan.status-pengajuan-tarik')
                ->with('info', 'Permintaan Anda sedang diproses. Silakan cek status penarikan Anda.');
        }

        try {
            DB::beginTransaction();

            $kodeVia = $request->metode === 'transfer' ? 'TF' : 'TN';
            $idPengajuan = IdGenerator::generate('tbl_pengajuan_penarikan_tabungan', 'T', $kodeVia, 'PNR');

            // 1. Create pengajuan penarikan record (always, for history consistency)
            PengajuanPenarikanTabungan::create([
                'id' => $idPengajuan,
                'id_anggota' => $idAnggota,
                'tgl_pengajuan' => now(),
                'nominal' => $request->nominal,
                'metode_transfer' => $request->metode,
                'nama_bank' => $request->metode === 'transfer' ? $request->nama_bank : null,
                'no_rekening' => $request->metode === 'transfer' ? $request->no_rekening : null,
                'lokasi_temu' => $request->metode === 'tunai' ? $request->lokasi_temu : null,
                'tanggal_janji_temu' => $request->metode === 'tunai' ? $request->tanggal_janji_temu : null,
                'waktu_janji_temu' => $request->metode === 'tunai' ? $request->waktu_janji_temu : null,
                'keterangan' => $request->keterangan,
                'status' => '1', // Pending
            ]);

            // Notifikasi admin hanya untuk penarikan via transfer (tunai diproses lewat Janji Temu)
            if ($request->metode === 'transfer') {
                \App\Models\AdminNotification::notify(
                    'tabungan_tarik',
                    'Pengajuan penarikan tabungan (transfer)',
                    'Nasabah mengajukan penarikan transfer Rp ' . number_format($request->nominal, 0, ',', '.'),
                    route('admin.tabungan.detail-pengajuan-tarik', $idPengajuan),
                    $idPengajuan,
                    'pengajuan_penarikan_tabungan'
                );
            }

            // 2. If Tunai, also create JanjiTemuTabungan for Universal System
            if ($request->metode === 'tunai') {
                $idJanjiTemu = IdGenerator::generate('tbl_janji_temu_tabungan', 'T', 'CS', 'JNJT');
                
                JanjiTemuTabungan::create([
                    'id' => $idJanjiTemu,
                    'id_nasabah' => $idAnggota,
                    'lokasi_temu' => $request->lokasi_temu,
                    'jenis' => 'penarikan',  // ✅ Set jenis as penarikan
                    'nominal' => $request->nominal,
                    'tanggal_janji_temu' => \Carbon\Carbon::parse($request->tanggal_janji_temu)->startOfDay(),
                    'waktu_janji_temu' => $request->waktu_janji_temu,
                    'keterangan' => $request->keterangan,
                    'status' => '1', // Menunggu
                ]);
            }

            DB::commit();

            app(ActivityLogService::class)->logSubmitPenarikan($idPengajuan, $request->nominal, $request->metode);

            $redirectRoute = $request->metode === 'tunai' 
                ? 'nasabah.tabungan.status-janji-temu' 
                : 'nasabah.tabungan.status-pengajuan-tarik';

            return redirect()->route($redirectRoute)
                ->with('success', 'Pengajuan penarikan berhasil dikirim!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput($request->except('pin'));
        }
    }
```
</details>

### Route: `nasabah/tabungan/janji-temu` [GET|HEAD]
**Function:** `janjiTemu`

**Queries Detected:**
- Model: JnsLokasiPerusahaan

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function janjiTemu(Request $request)
    {
        $lokasi = JnsLokasiPerusahaan::where('status_aktif', true)->get();
        
        return view('nasabah.tabungan.janji-temu', [
            'lokasi' => $lokasi,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
        ]);
    }
```
</details>

### Route: `nasabah/tabungan/janji-temu` [POST]
**Function:** `submitJanjiTemu`

**Queries Detected:**
- Model: Carbon
- Model: JanjiTemuTabungan
- Model: IdGenerator
- Model: ActivityLogService

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function submitJanjiTemu(Request $request)
    {
        // Check authentication first
        if (Auth::user() === null) {
            return redirect()->route('login')
                ->with('error', 'Session Anda telah berakhir. Silakan login kembali.');
        }

        try {
            $validated = $request->validate([
                'pin' => 'required|numeric|digits:6',
                'nominal' => 'required|numeric|min:10000',
                'lokasi_temu' => 'required|exists:jns_lokasi_perusahaan,id',
                'tanggal_janji_temu' => 'required|date|after:today',
                'waktu_janji_temu' => 'required|date_format:H:i',
                'keterangan' => 'nullable|string|max:500',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('nasabah.tabungan.janji-temu', [
                'nominal' => $request->nominal ?? '',
                'keterangan' => $request->keterangan ?? '',
            ])
                ->withErrors($e->validator)
                ->withInput($request->except('pin'));
        }

        // Verify PIN
        /** @var User $user */
        $user = Auth::user();
        
        // Check if user has PIN
        if (!$user->pin) {
            return redirect()->route('nasabah.tabungan.janji-temu', [
                'nominal' => $request->nominal ?? '',
                'keterangan' => $request->keterangan ?? '',
            ])
                ->with('error', 'PIN belum diatur. Silakan atur PIN terlebih dahulu di profil Anda.')
                ->withInput($request->except('pin'));
        }

        // Convert both to integer for comparison (handles string/int mismatch)
        $userPin = (int) $user->pin;
        $inputPin = (int) $request->pin;

        if ($userPin !== $inputPin) {
            return redirect()->route('nasabah.tabungan.janji-temu', [
                'nominal' => $request->nominal ?? '',
                'keterangan' => $request->keterangan ?? '',
            ])
                ->with('error', 'PIN yang Anda masukkan salah!')
                ->withInput($request->except('pin'));
        }

        try {
            // Get ID anggota after PIN verification
            $idAnggota = $this->getIdAnggota();

            // Duplicate submission prevention (last 10 seconds) - use whereDate
            $waktuJanjiTemu = \Carbon\Carbon::parse($request->waktu_janji_temu)->format('H:i:s');
            $alreadyExists = JanjiTemuTabungan::where('id_nasabah', $idAnggota)
                ->where('nominal', $request->nominal)
                ->whereDate('tanggal_janji_temu', $request->tanggal_janji_temu)
                ->where('waktu_janji_temu', $waktuJanjiTemu)
                ->where('created_at', '>=', now()->subSeconds(10))
                ->first();

            if ($alreadyExists) {
                return redirect()->route('nasabah.tabungan.status-janji-temu')
                    ->with('info', 'Permintaan Anda sedang diproses. Silakan cek daftar janji temu Anda.');
            }

            // Generate ID untuk janji temu
            // Format: DDMMYYYYNNNN + T + CS + JNJT (dengan sequence number untuk uniqueness)
            // Contoh: 04022026001TCSJNJT, 04022026002TCSJNJT
            $id = IdGenerator::generate('tbl_janji_temu_tabungan', 'T', 'CS', 'JNJT');
            
            // Parse dates - ensure only date part for tanggal_janji_temu
            $tanggalJanjiTemu = \Carbon\Carbon::parse($request->tanggal_janji_temu)->startOfDay();
            $waktuJanjiTemu = \Carbon\Carbon::parse($request->waktu_janji_temu)->format('H:i:s');
            
            // Create janji temu
            JanjiTemuTabungan::create([
                'id' => $id,                    // ✅ Generated ID
                'id_nasabah' => $idAnggota,
                'lokasi_temu' => $request->lokasi_temu,
                'nominal' => $request->nominal,
                'tanggal_janji_temu' => $tanggalJanjiTemu,
                'waktu_janji_temu' => $waktuJanjiTemu,
                'keterangan' => $request->keterangan,
                'status' => '1',                // ✅ Default: Menunggu
            ]);

            app(ActivityLogService::class)->logSubmitJanjiTemuTabungan($id, $request->nominal, 'setoran', $request->tanggal_janji_temu);

            // Redirect ke status janji temu
            return redirect()->route('nasabah.tabungan.status-janji-temu')
                ->with('success', 'Janji temu berhasil dibuat!');
                
        } catch (\Illuminate\Auth\AuthenticationException $e) {
            return redirect()->route('login')
                ->with('error', 'Session Anda telah berakhir. Silakan login kembali.');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return redirect()->route('nasabah.dashboard')
                ->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->route('nasabah.tabungan.janji-temu', [
                'nominal' => $request->nominal ?? '',
                'keterangan' => $request->keterangan ?? '',
            ])
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput($request->except('pin'));
        }
    }
```
</details>

### Route: `nasabah/tabungan/verify-pin` [POST]
**Function:** `verifyPin`

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function verifyPin(Request $request)
    {
        $request->validate([
            'pin' => 'required|numeric|digits:6',
        ]);

        /** @var User $user */
        $user = Auth::user();
        
        if (!$user->pin) {
            return response()->json([
                'success' => false,
                'message' => 'PIN belum diatur. Silakan atur PIN terlebih dahulu.'
            ], 400);
        }

        if ($user->pin != $request->pin) {
            return response()->json([
                'success' => false,
                'message' => 'PIN yang Anda masukkan salah.'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'PIN berhasil diverifikasi.'
        ]);
    }
```
</details>

### Route: `nasabah/tabungan/status-pengajuan-setor` [GET|HEAD]
**Function:** `statusPengajuanSetor`

**Queries Detected:**
- Model: PengajuanTabungan

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function statusPengajuanSetor(Request $request)
    {
        $idAnggota = $this->getIdAnggota();
        $status = $request->status;
        
        $query = PengajuanTabungan::where('id_anggota', $idAnggota)
            ->with(['buktiFoto'])
            ->latest();

        if ($status) {
            $query->where('status', $status);
        }
        
        $pengajuan = $query->paginate(10)->withQueryString();

        return view('nasabah.tabungan.status-pengajuan-setor', [
            'pengajuan' => $pengajuan,
            'currentStatus' => $status,
        ]);
    }
```
</details>

### Route: `nasabah/tabungan/status-janji-temu` [GET|HEAD]
**Function:** `statusJanjiTemu`

**Queries Detected:**
- Model: JanjiTemuTabungan

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function statusJanjiTemu(Request $request)
    {
        $idAnggota = $this->getIdAnggota();
        $status = $request->status;
        
        $query = JanjiTemuTabungan::where('id_nasabah', $idAnggota)
            ->with(['lokasi', 'transTabungan'])
            ->latest('tanggal_janji_temu');

        // Apply filtering logic based on status categories
        if ($status) {
            if ($status === 'akan_datang') {
                $query->where('status', '1')->where('tanggal_janji_temu', '>=', now()->toDateString());
            } elseif ($status === 'terlaksana') {
                $query->where('status', '2');
            } elseif ($status === 'dibatalkan') {
                $query->where('status', '3');
            } elseif ($status === 'terlewat') {
                $query->where('status', '1')->where('tanggal_janji_temu', '<', now()->toDateString());
            }
        }
        
        $janjiTemu = $query->paginate(10)->withQueryString();

        return view('nasabah.tabungan.status-janji-temu', [
            'janjiTemu' => $janjiTemu,
            'currentStatus' => $status,
        ]);
    }
```
</details>

### Route: `nasabah/tabungan/status-pengajuan-tarik` [GET|HEAD]
**Function:** `statusPengajuanTarik`

**Queries Detected:**
- Model: PengajuanPenarikanTabungan

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function statusPengajuanTarik()
    {
        $idAnggota = $this->getIdAnggota();
        
        $pengajuan = PengajuanPenarikanTabungan::where('id_anggota', $idAnggota)
            ->latest()
            ->paginate(10);

        return view('nasabah.tabungan.status-pengajuan-tarik', [
            'pengajuan' => $pengajuan,
        ]);
    }
```
</details>

### Route: `nasabah/tabungan/pengajuan-setor/{id}` [GET|HEAD]
**Function:** `detailPengajuanSetor`

**Queries Detected:**
- Model: PengajuanTabungan

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function detailPengajuanSetor($id)
    {
        $idAnggota = $this->getIdAnggota();
        
        $pengajuan = PengajuanTabungan::where('id_anggota', $idAnggota)
            ->with(['buktiFoto'])
            ->findOrFail($id);

        return view('nasabah.tabungan.detail-pengajuan-setor', [
            'pengajuan' => $pengajuan,
        ]);
    }
```
</details>

### Route: `nasabah/tabungan/pengajuan-tarik/{id}` [GET|HEAD]
**Function:** `detailPengajuanTarik`

**Queries Detected:**
- Model: PengajuanPenarikanTabungan

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function detailPengajuanTarik($id)
    {
        $idAnggota = $this->getIdAnggota();
        
        $pengajuan = PengajuanPenarikanTabungan::where('id_anggota', $idAnggota)
            ->findOrFail($id);

        return view('nasabah.tabungan.detail-pengajuan-tarik', [
            'pengajuan' => $pengajuan,
        ]);
    }
```
</details>

### Route: `nasabah/tabungan/transaksi/{id}` [GET|HEAD]
**Function:** `detailTransaksi`

**Queries Detected:**
- Model: TransTabungan

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function detailTransaksi($id)
    {
        $idAnggota = $this->getIdAnggota();
        
        $transaksi = TransTabungan::where('id_anggota', $idAnggota)
            ->with(['pengajuanSetor.buktiFoto', 'pengajuanTarik', 'jnsTransaksi', 'jnsVia', 'janjiTemuTabungan.buktiFoto'])
            ->findOrFail($id);

        return view('nasabah.tabungan.detail-transaksi', [
            'transaksi' => $transaksi,
        ]);
    }
```
</details>

### Route: `nasabah/tabungan/janji-temu/{id}` [GET|HEAD]
**Function:** `detailJanjiTemu`

**Queries Detected:**
- Model: JanjiTemuTabungan

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function detailJanjiTemu($id)
    {
        $idAnggota = $this->getIdAnggota();
        
        $janjiTemu = JanjiTemuTabungan::where('id_nasabah', $idAnggota)
            ->with(['lokasi', 'transTabungan'])
            ->findOrFail($id);

        $isPast = $janjiTemu->tanggal_janji_temu && $janjiTemu->tanggal_janji_temu->isPast();

        return view('nasabah.tabungan.detail-janji-temu', [
            'janjiTemu' => $janjiTemu,
            'isPast' => $isPast,
        ]);
    }
```
</details>

### Route: `nasabah/tabungan/janji-temu/{id}/cancel` [POST]
**Function:** `cancelJanjiTemu`

**Queries Detected:**
- Model: JanjiTemuTabungan

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function cancelJanjiTemu(Request $request, $id)
    {
        $idAnggota = $this->getIdAnggota();
        
        $request->validate([
            'pin' => 'required|numeric|digits:6',
        ]);

        /** @var User $user */
        $user = Auth::user();
        if ((int) $user->pin !== (int) $request->pin) {
            return redirect()->back()->with('error', 'PIN yang Anda masukkan salah!');
        }

        $janjiTemu = JanjiTemuTabungan::where('id_nasabah', $idAnggota)
            ->where('status', '1') // Only if pending
            ->findOrFail($id);

        $janjiTemu->update([
            'status' => '3', // Dibatalkan
        ]);

        return redirect()->back()->with('success', 'Janji temu berhasil dibatalkan.');
    }
```
</details>

### Route: `nasabah/tabungan/pengajuan-setor/{id}/cancel` [POST]
**Function:** `cancelPengajuanSetor`

**Queries Detected:**
- Model: PengajuanTabungan

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function cancelPengajuanSetor(Request $request, $id)
    {
        $idAnggota = $this->getIdAnggota();

        $request->validate([
            'pin' => 'required|numeric|digits:6',
        ]);

        /** @var User $user */
        $user = Auth::user();
        if ((int) $user->pin !== (int) $request->pin) {
            return redirect()->back()->with('error', 'PIN yang Anda masukkan salah!');
        }
        
        $pengajuan = PengajuanTabungan::where('id_anggota', $idAnggota)
            ->where('status', '1') // Only if pending
            ->findOrFail($id);

        $pengajuan->update([
            'status' => '3', // Dibatalkan/Ditolak
        ]);

        return redirect()->back()->with('success', 'Pengajuan setoran berhasil dibatalkan.');
    }
```
</details>

### Route: `nasabah/tabungan/pengajuan-tarik/{id}/cancel` [POST]
**Function:** `cancelPengajuanTarik`

**Queries Detected:**
- Model: PengajuanPenarikanTabungan

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function cancelPengajuanTarik(Request $request, $id)
    {
        $idAnggota = $this->getIdAnggota();

        $request->validate([
            'pin' => 'required|numeric|digits:6',
        ]);

        /** @var User $user */
        $user = Auth::user();
        if ((int) $user->pin !== (int) $request->pin) {
            return redirect()->back()->with('error', 'PIN yang Anda masukkan salah!');
        }
        
        $pengajuan = PengajuanPenarikanTabungan::where('id_anggota', $idAnggota)
            ->where('status', '1') // Only if pending
            ->findOrFail($id);

        $pengajuan->update([
            'status' => '3', // Dibatalkan/Ditolak
        ]);

        return redirect()->back()->with('success', 'Pengajuan penarikan berhasil dibatalkan.');
    }
```
</details>

## Controller: `App\Http\Controllers\Nasabah\StrukController`

**Models Imported:**
- `App\Models\TransTabungan`
- `App\Models\PinjamanH`
- `App\Models\TempoPinjamanB`
- `App\Models\TempoPinjamanM`
- `App\Models\PengajuanPembayaranPinjaman`

### Route: `nasabah/tabungan/transaksi/{id}/struk` [GET|HEAD]
**Function:** `transaksiTabungan`

**Queries Detected:**
- Model: TransTabungan
- Model: Pdf

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function transaksiTabungan(string $id)
    {
        $idAnggota = $this->getIdAnggota();
        $transaksi = TransTabungan::where('id_anggota', $idAnggota)
            ->with([
                'nasabah.user',
                'nasabah.dataKtp',
                'jnsTransaksi',
                'jnsVia',
                'pengajuanSetor.approvedBy',
                'pengajuanTarik'
            ])
            ->findOrFail($id);

        $logoPath = public_path('images/logo-koperasi-majakara.png');
        $hasLogo = is_file($logoPath);

        $pdf = Pdf::loadView('struk.tabungan', compact('transaksi', 'hasLogo', 'logoPath'));
        $pdf->setPaper([0, 0, 226.77, 841.89], 'portrait');
        $filename = 'Struk-Tabungan-' . $transaksi->id . '.pdf';
        return $pdf->download($filename);
    }
```
</details>

### Route: `nasabah/pinjaman/pembayaran/{id}/struk` [GET|HEAD]
**Function:** `pembayaranPinjaman`

**Queries Detected:**
- Model: PengajuanPembayaranPinjaman
- Model: TempoPinjamanB
- Model: TempoPinjamanM
- Model: Pdf

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function pembayaranPinjaman(string $id)
    {
        $idAnggota = $this->getIdAnggota();
        $pengajuan = PengajuanPembayaranPinjaman::where('id_anggota', $idAnggota)
            ->with([
                'nasabah.user',
                'nasabah.dataKtp',
                'pinjaman.pengajuan'
            ])
            ->findOrFail($id);

        $angsuran = null;
        if ($pengajuan->tempo_id && $pengajuan->jenis_tempo) {
            if ($pengajuan->jenis_tempo === 'bulanan') {
                $angsuran = TempoPinjamanB::with('pinjaman')->find($pengajuan->tempo_id);
            } else {
                $angsuran = TempoPinjamanM::with('pinjaman')->find($pengajuan->tempo_id);
            }
        }

        $pdf = Pdf::loadView('struk.pembayaran-pinjaman', compact('pengajuan', 'angsuran'));
        $pdf->setPaper([0, 0, 226.77, 841.89], 'portrait');
        $filename = 'Struk-Pembayaran-' . $pengajuan->id . '.pdf';
        return $pdf->download($filename);
    }
```
</details>

### Route: `nasabah/pinjaman/pinjaman-aktif/{id}/struk-pencairan` [GET|HEAD]
**Function:** `pencairanPinjaman`

**Queries Detected:**
- Model: PinjamanH
- Model: Pdf

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function pencairanPinjaman(string $id)
    {
        $idAnggota = $this->getIdAnggota();
        $pinjaman = PinjamanH::where('id_anggota', $idAnggota)
            ->with([
                'nasabah.user',
                'nasabah.dataKtp',
                'pengajuan'
            ])
            ->findOrFail($id);

        $pdf = Pdf::loadView('struk.pencairan-pinjaman', compact('pinjaman'));
        $pdf->setPaper([0, 0, 226.77, 841.89], 'portrait');
        $filename = 'Struk-Pencairan-' . $pinjaman->id . '.pdf';
        return $pdf->download($filename);
    }
```
</details>

### Route: `nasabah/pinjaman/angsuran/{id}/struk` [GET|HEAD]
**Function:** `angsuran`

**Queries Detected:**
- Model: TempoPinjamanB
- Model: TempoPinjamanM
- Model: Pdf

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function angsuran(Request $request, string $id)
    {
        $idAnggota = $this->getIdAnggota();
        $jenis = $request->get('jenis', 'bulanan');
        if ($jenis === 'bulanan') {
            $angsuran = TempoPinjamanB::with(['pinjaman.nasabah.user', 'pinjaman.pengajuan'])
                ->whereHas('pinjaman', fn ($q) => $q->where('id_anggota', $idAnggota))
                ->findOrFail($id);
        } else {
            $angsuran = TempoPinjamanM::with(['pinjaman.nasabah.user', 'pinjaman.pengajuan'])
                ->whereHas('pinjaman', fn ($q) => $q->where('id_anggota', $idAnggota))
                ->findOrFail($id);
        }

        $pdf = Pdf::loadView('struk.angsuran', compact('angsuran', 'jenis'));
        $pdf->setPaper([0, 0, 226.77, 841.89], 'portrait');
        $filename = 'Struk-Angsuran-' . $id . '.pdf';
        return $pdf->download($filename);
    }
```
</details>

## Controller: `App\Http\Controllers\Nasabah\PinjamanController`

**Models Imported:**
- `App\Models\PengajuanPinjaman`
- `App\Models\User`
- `App\Models\PinjamanH`
- `App\Models\TempoPinjamanB`
- `App\Models\TempoPinjamanM`
- `App\Models\JanjiTemuPinjaman`
- `App\Models\JnsLokasiPerusahaan`
- `App\Models\JnsBank`
- `App\Models\PengajuanPembayaranPinjaman`
- `App\Models\JanjiTemuPembayaranPinjaman`
- `App\Models\BuktiFoto`
- `App\Models\MasterBungaPinjaman`
- `App\Models\MasterDendaPinjaman`

### Route: `nasabah/pinjaman` [GET|HEAD]
**Function:** `index`

**Queries Detected:**
- Model: PinjamanH

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function index()
    {
        $idAnggota = $this->getIdAnggota();

        // BANK ACCESS GUARD dihapus dari index agar nasabah selalu bisa melihat riwayat pinjamannya

        // Get pinjaman aktif
        $pinjamanAktif = PinjamanH::where('id_anggota', $idAnggota)
            ->where('lunas', 'belum')
            ->with(['pengajuan', 'tempoBulanan', 'tempoMingguan'])
            ->latest()
            ->get();

        // Calculate total pinjaman aktif
        $totalPinjamanAktif = $pinjamanAktif->sum('jumlah_pinjam') ?? 0;

        // Calculate sisa pinjaman (total pinjaman - total terbayar)
        // Sistem bunga di awal: jumlah_pinjam sudah dikurangi bunga_rp
        // Total tagihan = nominal = jumlah_pinjam + bunga_rp
        $sisaPinjaman = 0;
        foreach ($pinjamanAktif as $pinjaman) {
            $totalTerbayar = 0;
            if ($pinjaman->jenis === 'bulanan') {
                $totalTerbayar = $pinjaman->tempoBulanan->sum('jumlah_terbayar') ?? 0;
            } else {
                $totalTerbayar = $pinjaman->tempoMingguan->sum('jumlah_terbayar') ?? 0;
            }
            // Total tagihan = nominal = jumlah_pinjam + bunga_rp (karena bunga sudah dipotong di awal)
            $totalTagihan = $pinjaman->jumlah_pinjam + $pinjaman->bunga_rp;
            $sisaPinjaman += max(0, $totalTagihan - $totalTerbayar);
        }

        // Get angsuran terdekat (jatuh tempo dalam 7 hari ke depan)
        $angsuranTerdekat = collect();
        foreach ($pinjamanAktif as $pinjaman) {
            if ($pinjaman->jenis === 'bulanan') {
                $tempo = $pinjaman->tempoBulanan()
                    ->where('status_bayar', 'belum')
                    ->whereBetween('tgl_jatuh_tempo', [now(), now()->addDays(7)])
                    ->orderBy('tgl_jatuh_tempo')
                    ->first();
            } else {
                $tempo = $pinjaman->tempoMingguan()
                    ->where('status_bayar', 'belum')
                    ->whereBetween('tgl_jatuh_tempo', [now(), now()->addDays(7)])
                    ->orderBy('tgl_jatuh_tempo')
                    ->first();
            }
            if ($tempo) {
                $tempo->pinjaman = $pinjaman;
                $angsuranTerdekat->push($tempo);
            }
        }
        $angsuranTerdekat = $angsuranTerdekat->sortBy('tgl_jatuh_tempo')->take(5);

        // Get total angsuran telat
        $totalAngsuranTelat = 0;
        foreach ($pinjamanAktif as $pinjaman) {
            $tempos = $pinjaman->jenis === 'bulanan' ? $pinjaman->tempoBulanan : $pinjaman->tempoMingguan;
            $telat = $tempos->filter(function($t) {
                return $t->status_bayar === 'telat' || ($t->status_bayar === 'belum' && $t->tgl_jatuh_tempo < now());
            })->count();
            $totalAngsuranTelat += $telat;
        }

        // Get all angsuran untuk tabel
        $semuaAngsuran = collect();
        foreach ($pinjamanAktif as $pinjaman) {
            if ($pinjaman->jenis === 'bulanan') {
                $tempos = $pinjaman->tempoBulanan()->orderBy('no_urut')->get();
            } else {
                $tempos = $pinjaman->tempoMingguan()->orderBy('no_urut')->get();
            }
            foreach ($tempos as $tempo) {
                $tempo->pinjaman = $pinjaman;
                $semuaAngsuran->push($tempo);
            }
        }
        $semuaAngsuran = $semuaAngsuran->sortBy('tgl_jatuh_tempo')->take(10);

        // Pinjaman Lunas (lunas = 'lunas') dengan total terbayar dari tempo
        $pinjamanLunas = PinjamanH::where('id_anggota', $idAnggota)
            ->where('lunas', 'lunas')
            ->with(['tempoBulanan', 'tempoMingguan'])
            ->latest()
            ->get()
            ->map(function ($p) {
                $terbayar = $p->jenis === 'bulanan'
                    ? $p->tempoBulanan->sum('jumlah_terbayar')
                    : $p->tempoMingguan->sum('jumlah_terbayar');
                $p->total_terbayar = $terbayar;
                return $p;
            });

        return view('nasabah.pinjaman.index', [
            'pinjamanAktif' => $pinjamanAktif,
            'pinjamanLunas' => $pinjamanLunas,
            'totalPinjamanAktif' => $totalPinjamanAktif,
            'sisaPinjaman' => $sisaPinjaman,
            'angsuranTerdekat' => $angsuranTerdekat,
            'totalAngsuranTelat' => $totalAngsuranTelat,
            'semuaAngsuran' => $semuaAngsuran,
        ]);
    }
```
</details>

### Route: `nasabah/pinjaman/pengajuan` [GET|HEAD]
**Function:** `pengajuanPinjaman`

**Queries Detected:**
- Model: BankAccessService
- Model: PengajuanPinjaman
- Model: MasterBungaPinjaman
- Model: JnsAngsuranBulan
- Model: JnsLokasiPerusahaan

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function pengajuanPinjaman(Request $request)
    {
        $idAnggota = $this->getIdAnggota();

        // ── BANK ACCESS GUARD ──────────────────────────────────────
        $access = app(BankAccessService::class)->checkPremiumAccess($idAnggota);
        if (!$access['allowed']) {
            return redirect()->route('nasabah.dashboard')
                ->with('error', $access['reason']);
        }
        // ──────────────────────────────────────────────────────────

        $riwayatPengajuan = PengajuanPinjaman::where('id_anggota', $idAnggota)
            ->with('pinjaman')
            ->latest()
            ->take(10)
            ->get();

        $masterBunga = MasterBungaPinjaman::where('status_aktif', true)->orderBy('durasi_min')->get();
        $durasiList = \App\Models\JnsAngsuranBulan::where('aktif', 'y')->orderBy('bulan')->get();
        if ($durasiList->isEmpty()) {
            $durasiList = collect(range(1, 24))->map(fn ($b) => (object)['bulan' => $b, 'ket' => (string)$b]);
        }
        $lokasi = JnsLokasiPerusahaan::where('status_aktif', true)->get();

        return view('nasabah.pinjaman.pengajuan-pinjaman', [
            'riwayatPengajuan' => $riwayatPengajuan,
            'masterBunga' => $masterBunga,
            'durasiList' => $durasiList,
            'lokasi' => $lokasi,
            'openMetode' => $request->get('metode'), // 'transfer' | 'tunai'
        ]);
    }
```
</details>

### Route: `nasabah/pinjaman/pengajuan-transfer` [GET|HEAD]
**Function:** `pengajuanTransfer`

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function pengajuanTransfer()
    {
        return redirect()->route('nasabah.pinjaman.pengajuan', ['metode' => 'transfer']);
    }
```
</details>

### Route: `nasabah/pinjaman/pengajuan-transfer` [POST]
**Function:** `submitPengajuanTransfer`

**Queries Detected:**
- Model: BankAccessService
- Model: MasterBungaPinjaman
- Model: IdGenerator
- Model: PengajuanPinjaman
- Model: AdminNotification
- Model: ActivityLogService

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function submitPengajuanTransfer(Request $request)
    {
        Log::info('Submit pengajuan request received', [
            'all_data' => $request->except('pin'),
            'has_pin' => $request->has('pin'),
        ]);

        // Clean nominal dari format rupiah
        $nominalRaw = $request->input('nominal_raw') ?? str_replace(['.', ',', ' '], '', $request->input('nominal'));
        $request->merge(['nominal' => $nominalRaw]);
        
        $rules = [
            'nominal' => 'required|numeric|min:100000',
            'durasi' => 'required|integer|min:1|max:24',
            'pin' => 'required|numeric|digits:6',
            'keterangan' => 'nullable|string|max:500',
        ];
        
        try {
            $validated = $request->validate($rules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', [
                'errors' => $e->errors(),
                'request' => $request->except('pin'),
            ]);
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput($request->except('pin'));
        }

        // Verify PIN
        /** @var User $user */
        $user = Auth::user();

        if (!$user->pin) {
            return redirect()->back()
                ->with('error', 'PIN belum diatur. Silakan atur PIN terlebih dahulu di profil Anda.')
                ->withInput($request->except('pin'));
        }

        // Convert both to integer for comparison
        $userPin = (int) $user->pin;
        $inputPin = (int) $request->pin;

        if ($userPin !== $inputPin) {
            return redirect()->back()
                ->with('error', 'PIN yang Anda masukkan salah!')
                ->withInput($request->except('pin'));
        }

        $idAnggota = $this->getIdAnggota();
        $jenisPencairan = 'transfer'; // Auto set to transfer for this form

        // ── BANK ACCESS GUARD (server-side double check) ───────────
        $access = app(BankAccessService::class)->checkPremiumAccess($idAnggota);
        if (!$access['allowed']) {
            return redirect()->route('nasabah.dashboard')
                ->with('error', $access['reason']);
        }
        // ──────────────────────────────────────────────────────────

        // Bunga dari master_bunga_pinjaman sesuai durasi
        $durasi = (int) $request->durasi;
        $bungaMaster = MasterBungaPinjaman::where('status_aktif', true)
            ->where('durasi_min', '<=', $durasi)
            ->where('durasi_max', '>=', $durasi)
            ->first();
        $bungaPersen = $bungaMaster ? (float) $bungaMaster->bunga_persen : 10.00;

        // ID dari 3 master: P (pinjaman), TF (transfer), PNJ (pengajuan)
        $idPengajuan = IdGenerator::generate('tbl_pengajuan_pinjaman', 'P', 'TF', 'PNJ');

        try {
            $pengajuan = PengajuanPinjaman::create([
                'id' => $idPengajuan,
                'id_anggota' => $idAnggota,
                'tgl_pengajuan' => now(),
                'nominal' => $request->nominal,
                'jenis' => 'bulanan', // Auto set to bulanan for transfer
                'durasi' => $durasi,
                'jenis_pencairan' => $jenisPencairan,
                'status' => '1', // Status 1 = pending
                'keterangan' => $request->keterangan,
                'bunga_persen' => $bungaPersen,
            ]);

            \App\Models\AdminNotification::notify(
                'pinjaman',
                'Pengajuan pinjaman baru',
                'Nasabah mengajukan pinjaman Rp ' . number_format((float) ($pengajuan->nominal ?? 0), 0, ',', '.') . ' (transfer)',
                route('admin.pinjaman.detail-pengajuan', $pengajuan->id),
                $pengajuan->id,
                'pengajuan_pinjaman'
            );

            app(ActivityLogService::class)->logSubmitPengajuanPinjaman($pengajuan->id, $pengajuan->nominal, 'transfer');

            return redirect()->route('nasabah.pinjaman.pengajuan')
                ->with('success', 'Pengajuan pinjaman berhasil dikirim!');
        } catch (\Exception $e) {
            Log::error('Error creating pengajuan pinjaman: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'id_anggota' => $idAnggota,
                'request' => $request->except('pin'),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput($request->except('pin'));
        }
    }
```
</details>

### Route: `nasabah/pinjaman/simulasi-angsuran` [POST]
**Function:** `simulasiAngsuran`

**Queries Detected:**
- Model: MasterBungaPinjaman

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function simulasiAngsuran(Request $request)
    {
        $request->validate([
            'nominal' => 'required|numeric|min:100000',
            'durasi' => 'required|integer|min:1|max:24',
        ]);

        $nominal = $request->nominal;
        $durasi = (int) $request->durasi;

        // Get bunga berdasarkan durasi
        $masterBunga = MasterBungaPinjaman::getBungaByDurasi($durasi);
        
        if (!$masterBunga) {
            return response()->json([
                'success' => false,
                'message' => 'Bunga untuk durasi ini belum diatur'
            ], 400);
        }

        $bungaPersen = (float) $masterBunga->bunga_persen;
        $bungaRp = ($nominal * $bungaPersen) / 100;
        $totalKewajiban = $nominal + $bungaRp;

        $angsuranRaw = $totalKewajiban / $durasi;

        // Bulatkan angsuran per bulan ke bawah ke kelipatan 1.000
        $angsuranBulanan = (int) floor($angsuranRaw / 1000) * 1000;
        if ($angsuranBulanan == 0 && $totalKewajiban > 0) {
            $angsuranBulanan = (int) floor($totalKewajiban / $durasi);
        }

        $simulasi = [];
        $tanggalMulai = now();
        $akumulasi = 0;
        for ($i = 1; $i <= $durasi; $i++) {
            $tanggalJatuhTempo = $tanggalMulai->copy()->addMonths($i);
            
            // Angsuran 1 sampai n-1 menggunakan angsuranBulanan
            // Angsuran terakhir (n) menggunakan sisa totalKewajiban
            $tagihan = ($i < $durasi) ? $angsuranBulanan : ($totalKewajiban - $akumulasi);
            $akumulasi += $tagihan;

            $simulasi[] = [
                'bulan' => $i,
                'tanggal' => $tanggalJatuhTempo->format('d/m/Y'),
                'pokok' => 0,
                'bunga' => 0,
                'total' => (int) round($tagihan, 0),
            ];
        }

        $displayAngsuran = $durasi > 1 ? $angsuranBulanan : (int) $totalKewajiban;

        return response()->json([
            'success' => true,
            'data' => [
                'nominal' => (float) $nominal,
                'durasi' => $durasi,
                'bunga_persen' => $bungaPersen,
                'bunga_total' => $bungaRp,
                'total_yang_harus_dibayar' => (int) round($totalKewajiban, 0),
                'angsuran_per_bulan' => $displayAngsuran,
                'simulasi' => $simulasi,
            ]
        ]);
    }
```
</details>

### Route: `nasabah/pinjaman/janji-temu` [GET|HEAD]
**Function:** `janjiTemuPinjaman`

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function janjiTemuPinjaman(Request $request)
    {
        return redirect()->route('nasabah.pinjaman.pengajuan', ['metode' => 'tunai']);
    }
```
</details>

### Route: `nasabah/pinjaman/janji-temu` [POST]
**Function:** `submitJanjiTemuPinjaman`

**Queries Detected:**
- Model: BankAccessService
- Model: JanjiTemuPinjaman
- Model: MasterBungaPinjaman
- Model: IdGenerator
- Model: PengajuanPinjaman
- Model: AdminNotification
- Model: ActivityLogService

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function submitJanjiTemuPinjaman(Request $request)
    {
        // Clean nominal dari format rupiah (sama seperti transfer)
        $nominalRaw = $request->input('nominal_raw') ?? str_replace(['.', ',', ' '], '', $request->input('nominal'));
        $request->merge(['nominal' => $nominalRaw]);

        Log::info('Submit janji temu pinjaman request received', [
            'all_data' => $request->except('pin'),
            'has_pin' => $request->has('pin'),
        ]);

        try {
            $validated = $request->validate([
                'nominal' => 'required|numeric|min:100000',
                'durasi' => 'required|integer|min:1|max:24',
                'pin' => 'required|numeric|digits:6',
                'lokasi_temu' => 'required|exists:jns_lokasi_perusahaan,id',
                'tanggal_janji_temu' => 'required|date|after:today',
                'waktu_janji_temu' => 'required|date_format:H:i',
                'keterangan' => 'nullable|string|max:500',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', [
                'errors' => $e->errors(),
                'request' => $request->except('pin'),
            ]);
            return redirect()->route('nasabah.pinjaman.janji-temu', [
                'nominal' => $request->nominal ?? '',
                'keterangan' => $request->keterangan ?? '',
            ])
                ->withErrors($e->errors())
                ->withInput($request->except('pin'));
        }

        // Verify PIN
        /** @var User $user */
        $user = Auth::user();
        
        if (!$user->pin) {
            return redirect()->route('nasabah.pinjaman.janji-temu', [
                'nominal' => $request->nominal ?? '',
                'keterangan' => $request->keterangan ?? '',
            ])
                ->with('error', 'PIN belum diatur. Silakan atur PIN terlebih dahulu di profil Anda.')
                ->withInput($request->except('pin'));
        }

        $userPin = (int) $user->pin;
        $inputPin = (int) $request->pin;

        if ($userPin !== $inputPin) {
            return redirect()->route('nasabah.pinjaman.janji-temu', [
                'nominal' => $request->nominal ?? '',
                'keterangan' => $request->keterangan ?? '',
            ])
                ->with('error', 'PIN yang Anda masukkan salah!')
                ->withInput($request->except('pin'));
        }

        $idAnggota = $this->getIdAnggota();

        // ── BANK ACCESS GUARD (server-side double check) ───────────
        $access = app(BankAccessService::class)->checkPremiumAccess($idAnggota);
        if (!$access['allowed']) {
            return redirect()->route('nasabah.dashboard')
                ->with('error', $access['reason']);
        }
        // ──────────────────────────────────────────────────────────

        // 🛡️ Guard duplikasi harian: satu nasabah max 1 janji temu pinjaman PENDING per tanggal
        $sudahAdaHariIni = \App\Models\JanjiTemuPinjaman::where('id_nasabah', $idAnggota)
            ->whereDate('tanggal_janji_temu', $request->tanggal_janji_temu)
            ->where('status', '1') // pending
            ->exists();

        if ($sudahAdaHariIni) {
            return redirect()->back()
                ->with('warning', 'Anda sudah memiliki janji temu pembayaran pinjaman yang sedang menunggu untuk tanggal yang sama.')
                ->withInput($request->except('pin'));
        }

        $durasi = (int) $request->durasi;

        // Bunga dari master_bunga_pinjaman sesuai durasi
        $bungaMaster = MasterBungaPinjaman::where('status_aktif', true)
            ->where('durasi_min', '<=', $durasi)
            ->where('durasi_max', '>=', $durasi)
            ->first();
        $bungaPersen = $bungaMaster ? (float) $bungaMaster->bunga_persen : 10.00;

        // ID dari 3 master: P (pinjaman), TN (tunai), PNJ (pengajuan)
        $idPengajuan = IdGenerator::generate('tbl_pengajuan_pinjaman', 'P', 'TN', 'PNJ');

        Log::info('Submitting janji temu pinjaman', [
            'user_id' => $user->id,
            'id_anggota' => $idAnggota,
            'nominal' => $request->nominal,
            'durasi' => $durasi,
        ]);

        try {
            // Create pengajuan (tunai) - hanya bulanan
            $pengajuan = PengajuanPinjaman::create([
                'id' => $idPengajuan,
                'id_anggota' => $idAnggota,
                'tgl_pengajuan' => now(),
                'nominal' => $request->nominal,
                'jenis' => 'bulanan',
                'durasi' => $durasi,
                'jenis_pencairan' => 'tunai',
                'status' => '1', // Pending
                'keterangan' => $request->keterangan,
                'bunga_persen' => $bungaPersen,
            ]);

            // Create janji temu pinjaman (muncul di halaman Janji Temu Universal, bukan Pengajuan Terbaru)
            $idJanjiTemu = IdGenerator::generate('tbl_janji_temu_pinjaman', 'P', 'TN', 'JNJT');
            JanjiTemuPinjaman::create([
                'id' => $idJanjiTemu,
                'id_pengajuan' => $pengajuan->id,
                'id_nasabah' => $idAnggota,
                'lokasi_temu' => $request->lokasi_temu,
                'nominal' => $request->nominal,
                'tanggal_janji_temu' => $request->tanggal_janji_temu,
                'waktu_janji_temu' => $request->waktu_janji_temu,
                'keterangan' => $request->keterangan,
                'status' => '1',
            ]);

            \App\Models\AdminNotification::notify(
                'janji_temu',
                'Janji temu pinjaman (tunai)',
                'Nasabah membuat janji temu pinjaman Rp ' . number_format((float) ($pengajuan->nominal ?? 0), 0, ',', '.'),
                route('admin.pinjaman.detail-pengajuan', $pengajuan->id),
                $idJanjiTemu,
                'janji_temu_pinjaman'
            );

            Log::info('Pengajuan tunai + janji temu pinjaman created', [
                'pengajuan_id' => $pengajuan->id,
                'janji_temu_id' => $idJanjiTemu,
                'id_anggota' => $pengajuan->id_anggota,
            ]);

            app(ActivityLogService::class)->logSubmitPengajuanPinjaman($pengajuan->id, $pengajuan->nominal, 'janji temu');

            return redirect()->route('nasabah.pinjaman.pengajuan')
                ->with('success', 'Pengajuan pinjaman berhasil dikirim!');
        } catch (\Exception $e) {
            Log::error('Error creating pengajuan pinjaman: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'id_anggota' => $idAnggota,
                'request' => $request->except('pin'),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('nasabah.pinjaman.janji-temu', [
                'nominal' => $request->nominal ?? '',
                'keterangan' => $request->keterangan ?? '',
            ])
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput($request->except('pin'));
        }
    }
```
</details>

### Route: `nasabah/pinjaman/verify-pin` [POST]
**Function:** `verifyPin`

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function verifyPin(Request $request)
    {
        try {
            $request->validate([
                'pin' => 'required|numeric|digits:6',
            ]);

            /** @var User|null $user */
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak terautentikasi.'
                ], 401);
            }
            
            if (!$user->pin) {
                return response()->json([
                    'success' => false,
                    'message' => 'PIN belum diatur. Silakan atur PIN terlebih dahulu.'
                ], 400);
            }

            $userPin = (int) $user->pin;
            $inputPin = (int) $request->pin;

            Log::info('Verifying PIN', [
                'user_id' => $user->id,
                'user_pin' => $userPin,
                'input_pin' => $inputPin,
                'match' => $userPin === $inputPin
            ]);

            if ($userPin !== $inputPin) {
                return response()->json([
                    'success' => false,
                    'message' => 'PIN yang Anda masukkan salah.'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'PIN berhasil diverifikasi.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error verifying PIN: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat verifikasi PIN.'
            ], 500);
        }
    }
```
</details>

### Route: `nasabah/pinjaman/status-pengajuan` [GET|HEAD]
**Function:** `statusPengajuan`

**Queries Detected:**
- Model: PengajuanPinjaman

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function statusPengajuan(Request $request)
    {
        $idAnggota = $this->getIdAnggota();

        $query = PengajuanPinjaman::where('id_anggota', $idAnggota)
            ->with('pinjaman')
            ->latest();

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            if ($request->status === 'pending') {
                $query->whereDoesntHave('pinjaman');
            } elseif ($request->status === 'approved') {
                $query->whereHas('pinjaman');
            }
        }

        // Filter by jenis
        if ($request->has('jenis') && $request->jenis !== '') {
            $query->where('jenis', $request->jenis);
        }

        $pengajuan = $query->paginate(15);

        return view('nasabah.pinjaman.status-pengajuan', [
            'pengajuan' => $pengajuan,
        ]);
    }
```
</details>

### Route: `nasabah/pinjaman/pengajuan/{id}` [GET|HEAD]
**Function:** `detailPengajuan`

**Queries Detected:**
- Model: PengajuanPinjaman

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function detailPengajuan($id)
    {
        $idAnggota = $this->getIdAnggota();

        $pengajuan = PengajuanPinjaman::where('id_anggota', $idAnggota)
            ->with(['pinjaman', 'nasabah.user'])
            ->findOrFail($id);

        return view('nasabah.pinjaman.detail-pengajuan', [
            'pengajuan' => $pengajuan,
        ]);
    }
```
</details>

### Route: `nasabah/pinjaman/pinjaman-aktif` [GET|HEAD]
**Function:** `pinjamanAktif`

**Queries Detected:**
- Model: PinjamanH

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function pinjamanAktif(Request $request)
    {
        $idAnggota = $this->getIdAnggota();

        $query = PinjamanH::where('id_anggota', $idAnggota)
            ->where('lunas', 'belum')
            ->with(['pengajuan', 'tempoBulanan', 'tempoMingguan'])
            ->latest();

        // Filter by jenis
        if ($request->has('jenis') && $request->jenis !== '') {
            $query->where('jenis', $request->jenis);
        }

        $pinjaman = $query->paginate(15);

        return view('nasabah.pinjaman.pinjaman-aktif', [
            'pinjaman' => $pinjaman,
        ]);
    }
```
</details>

### Route: `nasabah/pinjaman/pinjaman-aktif/{id}` [GET|HEAD]
**Function:** `detailPinjaman`

**Queries Detected:**
- Model: PinjamanH
- Model: BuktiFoto

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function detailPinjaman($id)
    {
        $idAnggota = $this->getIdAnggota();

        $pinjaman = PinjamanH::where('id_anggota', $idAnggota)
            ->with([
                'pengajuan',
                'nasabah.user',
                'tempoBulanan',
                'tempoMingguan',
                'buktiPelunasan'
            ])
            ->findOrFail($id);

        // Get angsuran berdasarkan jenis
        $angsuran = $pinjaman->jenis === 'bulanan'
            ? $pinjaman->tempoBulanan()->orderBy('no_urut')->get()
            : $pinjaman->tempoMingguan()->orderBy('no_urut')->get();

        // Hitung denda per angsuran (berjalan jika telat & belum bayar) dan total denda
        $totalDenda = 0;
        foreach ($angsuran as $item) {
            $item->setRelation('pinjaman', $pinjaman);
            $dendaItem = $item->hitungDenda();
            $item->denda_berjalan = $dendaItem;
            $totalDenda += $dendaItem;
        }

        // Total tagihan (pokok + bunga) dan total kewajiban (termasuk denda berjalan)
        $totalTagihan = $pinjaman->jumlah_pinjam + $pinjaman->bunga_rp;
        $totalKewajiban = $totalTagihan + $totalDenda;
        $totalTerbayar = $angsuran->sum('jumlah_terbayar') ?? 0;
        $sisaPinjaman = max(0, $totalKewajiban - $totalTerbayar);
        $progress = $totalKewajiban > 0 ? ($totalTerbayar / $totalKewajiban) * 100 : 0;
        $angsuranLunas = $angsuran->where('status_bayar', 'lunas')->count();
        $totalAngsuran = $angsuran->count();

        // Bukti foto pencairan (upload admin saat cairkan pinjaman)
        $buktiPencairan = collect();
        if ($pinjaman->id_pengajuan) {
            $buktiPencairan = BuktiFoto::where('owner_id', $pinjaman->id_pengajuan)
                ->where('owner_fitur', 'P')
                ->where('owner_trans', 'PNCR')
                ->orderBy('created_at')
                ->get();
        }

        return view('nasabah.pinjaman.detail-pinjaman', [
            'pinjaman' => $pinjaman,
            'angsuran' => $angsuran,
            'totalTagihan' => $totalTagihan,
            'totalDenda' => $totalDenda,
            'totalKewajiban' => $totalKewajiban,
            'totalTerbayar' => $totalTerbayar,
            'sisaPinjaman' => $sisaPinjaman,
            'progress' => $progress,
            'angsuranLunas' => $angsuranLunas,
            'totalAngsuran' => $totalAngsuran,
            'buktiPencairan' => $buktiPencairan,
        ]);
    }
```
</details>

### Route: `nasabah/pinjaman/angsuran` [GET|HEAD]
**Function:** `angsuran`

**Queries Detected:**
- Model: PinjamanH

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function angsuran(Request $request)
    {
        $idAnggota = $this->getIdAnggota();
        $jenis = $request->get('jenis', 'bulanan');

        // Query pinjaman milik nasabah, dengan tempo diurut dari jatuh tempo terdekat
        $query = PinjamanH::where('id_anggota', $idAnggota)
            ->where('lunas', 'belum')
            ->when($jenis === 'bulanan', function ($q) {
                $q->with(['tempoBulanan' => fn ($t) => $t->orderBy('tgl_jatuh_tempo', 'asc')]);
            })
            ->when($jenis === 'mingguan', function ($q) {
                $q->with(['tempoMingguan' => fn ($t) => $t->orderBy('tgl_jatuh_tempo', 'asc')]);
            })
            ->latest();

        if ($request->filled('status')) {
            if ($jenis === 'bulanan') {
                $query->whereHas('tempoBulanan', fn ($q) => $q->where('status_bayar', $request->status));
            } else {
                $query->whereHas('tempoMingguan', fn ($q) => $q->where('status_bayar', $request->status));
            }
        }
        if ($request->filled('tanggal_dari')) {
            if ($jenis === 'bulanan') {
                $query->whereHas('tempoBulanan', fn ($q) => $q->whereDate('tgl_jatuh_tempo', '>=', $request->tanggal_dari));
            } else {
                $query->whereHas('tempoMingguan', fn ($q) => $q->whereDate('tgl_jatuh_tempo', '>=', $request->tanggal_dari));
            }
        }
        if ($request->filled('tanggal_sampai')) {
            if ($jenis === 'bulanan') {
                $query->whereHas('tempoBulanan', fn ($q) => $q->whereDate('tgl_jatuh_tempo', '<=', $request->tanggal_sampai));
            } else {
                $query->whereHas('tempoMingguan', fn ($q) => $q->whereDate('tgl_jatuh_tempo', '<=', $request->tanggal_sampai));
            }
        }

        $pinjamanList = $query->paginate(10);

        // Hitung denda berjalan per angsuran (untuk tampilan di list)
        foreach ($pinjamanList as $pinjaman) {
            $tempos = $jenis === 'bulanan' ? $pinjaman->tempoBulanan : $pinjaman->tempoMingguan;
            foreach ($tempos as $t) {
                $t->setRelation('pinjaman', $pinjaman);
                $t->denda_berjalan = $t->hitungDenda();
            }
        }

        return view('nasabah.pinjaman.angsuran', [
            'pinjamanList' => $pinjamanList,
            'jenis' => $jenis,
        ]);
    }
```
</details>

### Route: `nasabah/pinjaman/angsuran/{id}` [GET|HEAD]
**Function:** `detailAngsuran`

**Queries Detected:**
- Model: TempoPinjamanB
- Model: TempoPinjamanM
- Model: PengajuanPembayaranPinjaman

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function detailAngsuran(Request $request, $id)
    {
        $idAnggota = $this->getIdAnggota();
        $jenis = $request->get('jenis', 'bulanan');

        if ($jenis === 'bulanan') {
            $angsuran = TempoPinjamanB::whereHas('pinjaman', function($q) use ($idAnggota) {
                    $q->where('id_anggota', $idAnggota);
                })
                ->with(['pinjaman.pengajuan', 'pinjaman.nasabah.user'])
                ->findOrFail($id);
        } else {
            $angsuran = TempoPinjamanM::whereHas('pinjaman', function($q) use ($idAnggota) {
                    $q->where('id_anggota', $idAnggota);
                })
                ->with(['pinjaman.pengajuan', 'pinjaman.nasabah.user'])
                ->findOrFail($id);
        }

        $sisaTagihan = max(0, $angsuran->jumlah_tagihan - ($angsuran->jumlah_terbayar ?? 0));
        $isTelat = $angsuran->tgl_jatuh_tempo < now() && $angsuran->status_bayar !== 'lunas';
        
        // Hitung denda
        $denda = $angsuran->hitungDenda();
        $totalTagihanPlusDenda = $angsuran->jumlah_tagihan + $denda;

        // Bukti transfer untuk angsuran ini (dari pengajuan pembayaran yang sudah terlaksana)
        $buktiTransferAngsuran = collect();
        if ($angsuran->status_bayar === 'lunas') {
            $pengajuanBayar = PengajuanPembayaranPinjaman::where('tempo_id', $id)
                ->where('jenis_tempo', $jenis)
                ->whereIn('status', ['3', '4'])
                ->with('buktiFoto')
                ->get();
            $buktiTransferAngsuran = $pengajuanBayar->pluck('buktiFoto')->flatten()->filter(fn($b) => $b && ($b->file_path ?? null));
        }

        return view('nasabah.pinjaman.detail-angsuran', [
            'angsuran' => $angsuran,
            'jenis' => $jenis,
            'sisaTagihan' => $sisaTagihan,
            'isTelat' => $isTelat,
            'denda' => $denda,
            'totalTagihanPlusDenda' => $totalTagihanPlusDenda,
            'buktiTransferAngsuran' => $buktiTransferAngsuran,
        ]);
    }
```
</details>

### Route: `nasabah/pinjaman/pembayaran` [GET|HEAD]
**Function:** `pembayaran`

**Queries Detected:**
- Model: PinjamanH
- Model: TempoPinjamanB
- Model: TempoPinjamanM
- Model: JnsLokasiPerusahaan
- Model: JnsBank

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function pembayaran(Request $request)
    {
        $idAnggota = $this->getIdAnggota();
        $pinjamanId = $request->get('pinjaman_id');
        $tempoId = $request->get('tempo_id');
        $jenis = $request->get('jenis', 'bulanan');

        // Get pinjaman aktif
        $pinjamanAktif = PinjamanH::where('id_anggota', $idAnggota)
            ->where('lunas', 'belum')
            ->with(['pengajuan'])
            ->get();

        $selectedPinjaman = null;
        $selectedAngsuran = null;
        $angsuranList = collect();

        if ($pinjamanId) {
            $selectedPinjaman = PinjamanH::where('id_anggota', $idAnggota)
                ->where('id', $pinjamanId)
                ->with(['pengajuan'])
                ->first();

            if ($selectedPinjaman) {
                // Get angsuran yang belum lunas
                if ($selectedPinjaman->jenis === 'bulanan') {
                    $angsuranList = $selectedPinjaman->tempoBulanan()
                        ->where('status_bayar', '!=', 'lunas')
                        ->orderBy('no_urut')
                        ->get();
                } else {
                    $angsuranList = $selectedPinjaman->tempoMingguan()
                        ->where('status_bayar', '!=', 'lunas')
                        ->orderBy('no_urut')
                        ->get();
                }

                // Tambahkan denda ke setiap angsuran dalam list untuk konsistensi tampilan dropdown
                foreach ($angsuranList as $angs) {
                    $angs->setRelation('pinjaman', $selectedPinjaman);
                    $angs->denda_kalkulasi = $angs->hitungDenda();
                    $angs->sisa_kalkulasi = $angs->sisa_tagihan;
                    $angs->total_kalkulasi = $angs->sisa_kalkulasi + $angs->denda_kalkulasi;
                }

                if ($tempoId) {
                    if ($selectedPinjaman->jenis === 'bulanan') {
                        $selectedAngsuran = TempoPinjamanB::where('id', $tempoId)
                            ->whereHas('pinjaman', function($q) use ($idAnggota) {
                                $q->where('id_anggota', $idAnggota);
                            })
                            ->first();
                    } else {
                        $selectedAngsuran = TempoPinjamanM::where('id', $tempoId)
                            ->whereHas('pinjaman', function($q) use ($idAnggota) {
                                $q->where('id_anggota', $idAnggota);
                            })
                            ->first();
                    }
                }
            }
        }

        // Hitung denda menggunakan hitungDenda() yang konsisten
        // agar view tidak perlu re-kalkulasi sendiri dengan rumus berbeda
        $dendaAngsuran = 0;
        $sisaTagihan = 0;
        $totalBayar = 0;
        $isTelat = false;
        $hariTelat = 0;

        if ($selectedAngsuran && $selectedPinjaman) {
            // Set relasi pinjaman agar hitungDenda() bisa mengakses data pinjaman
            $selectedAngsuran->setRelation('pinjaman', $selectedPinjaman);

            $sisaTagihan = $selectedAngsuran->sisa_tagihan;
            $isTelat = $selectedAngsuran->tgl_jatuh_tempo < now() && $selectedAngsuran->status_bayar !== 'lunas';

            // Gunakan hitungDenda() dari trait (Model)
            $dendaAngsuran = $selectedAngsuran->hitungDenda();

            // Use hitungHariTelat() from trait (Model) to avoid duplication
            $hariTelat = $selectedAngsuran->hitungHariTelat();

            $totalBayar = $sisaTagihan + $dendaAngsuran;
        }

        // Get lokasi untuk janji temu
        $lokasi = JnsLokasiPerusahaan::all();
        // Rekening perusahaan untuk dropdown transfer
        $rekeningPerusahaan = JnsBank::orderBy('bank')->orderBy('pemilik')->get();

        return view('nasabah.pinjaman.pembayaran', [
            'pinjamanAktif'   => $pinjamanAktif,
            'selectedPinjaman' => $selectedPinjaman,
            'selectedAngsuran' => $selectedAngsuran,
            'angsuranList'    => $angsuranList,
            'lokasi'          => $lokasi,
            'rekeningPerusahaan' => $rekeningPerusahaan,
            'jenis'           => $jenis,
            // Data denda yang dihitung oleh controller (konsisten dengan hitungDenda())
            'dendaAngsuran'   => $dendaAngsuran,
            'sisaTagihan'     => $sisaTagihan,
            'totalBayar'      => $totalBayar,
            'isTelat'         => $isTelat,
            'hariTelat'       => $hariTelat,
        ]);
    }
```
</details>

### Route: `nasabah/pinjaman/pembayaran/transfer` [POST]
**Function:** `submitPembayaranTransfer`

**Queries Detected:**
- Model: PinjamanH
- Model: PengajuanPembayaranPinjaman
- Model: IdGenerator
- Model: AdminNotification
- Model: BuktiFoto
- Model: ActivityLogService

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function submitPembayaranTransfer(Request $request)
    {
        $validated = $request->validate([
            'pinjaman_id' => 'required|exists:tbl_pinjaman_h,id',
            'tempo_id' => 'required|exists:tempo_pinjaman_b,id',
            'jenis_tempo' => 'required|in:bulanan,mingguan',
            'nominal' => 'required|numeric|min:1',
            'rekening_tujuan' => 'required|string|max:255',
            'pin' => 'required|numeric|digits:6',
            'bukti_foto.*' => 'nullable|image|max:5120',
            'keterangan' => 'nullable|string|max:500',
        ]);

        // Verify PIN
        /** @var User $user */
        $user = Auth::user();
        
        if (!$user->pin) {
            return redirect()->back()
                ->with('error', 'PIN belum diatur. Silakan atur PIN terlebih dahulu di profil Anda.')
                ->withInput($request->except('pin'));
        }

        $userPin = (int) $user->pin;
        $inputPin = (int) $request->pin;

        if ($userPin !== $inputPin) {
            return redirect()->back()
                ->with('error', 'PIN yang Anda masukkan salah!')
                ->withInput($request->except('pin'));
        }

        $idAnggota = $this->getIdAnggota();

        // Verify pinjaman belongs to nasabah
        $pinjaman = PinjamanH::where('id', $request->pinjaman_id)
            ->where('id_anggota', $idAnggota)
            ->firstOrFail();

        // 🛡️ Server-side guard: cegah duplikasi dalam 30 detik
        $recentDuplicate = \App\Models\PengajuanPembayaranPinjaman::where('id_anggota', $idAnggota)
            ->where('pinjaman_id', $request->pinjaman_id)
            ->where('tempo_id', $request->tempo_id)
            ->where('nominal', $request->nominal)
            ->where('status', '1')
            ->where('created_at', '>=', now()->subSeconds(30))
            ->exists();

        if ($recentDuplicate) {
            return redirect()->route('nasabah.pinjaman.pembayaran')
                ->with('warning', 'Pengajuan pembayaran yang sama sudah dikirim. Silakan tunggu beberapa saat.');
        }

        // ID dari 3 master: P (pinjaman), TF (transfer), PMB (pembayaran)
        $idPengajuanPembayaran = IdGenerator::generate('tbl_pengajuan_pembayaran_pinjaman', 'P', 'TF', 'PMB');

        try {
            // Create pengajuan pembayaran (tempo_id FK ke tempo_pinjaman_b)
            $pengajuan = PengajuanPembayaranPinjaman::create([
                'id' => $idPengajuanPembayaran,
                'id_anggota' => $idAnggota,
                'pinjaman_id' => $request->pinjaman_id,
                'tempo_id' => $request->tempo_id,
                'jenis_tempo' => $request->jenis_tempo,
                'nominal' => $request->nominal,
                'rekening_tujuan' => $request->rekening_tujuan,
                'keterangan' => $request->keterangan,
                'status' => '1', // Pending
            ]);

            \App\Models\AdminNotification::notify(
                'pinjaman_pembayaran',
                'Pengajuan pembayaran pinjaman baru',
                'Nasabah mengajukan pembayaran pinjaman Rp ' . number_format((float) ($pengajuan->nominal ?? 0), 0, ',', '.') . ' (transfer)',
                route('admin.pinjaman.detail-pembayaran', $pengajuan->id),
                $pengajuan->id,
                'pengajuan_pembayaran_pinjaman'
            );

            // Upload bukti foto
            if ($request->hasFile('bukti_foto')) {
                foreach ($request->file('bukti_foto') as $file) {
                    $path = $file->store('bukti-pembayaran-pinjaman', 'public');
                    
                    // Generate ID for bukti foto: P (pinjaman) + TF (transfer) + PMB (pembayaran)
                    $idBuktiFoto = IdGenerator::generate('tbl_bukti_foto', 'P', 'TF', 'PMB');
                    
                    BuktiFoto::create([
                        'id' => $idBuktiFoto,
                        'owner_id' => $pengajuan->id,
                        'owner_fitur' => 'P', // Pinjaman
                        'owner_trans' => 'PMB', // Pembayaran
                        'file_path' => $path,
                        'keterangan' => $request->keterangan,
                    ]);
                }
            }

            app(ActivityLogService::class)->logSubmitPembayaranPinjaman($pengajuan->id, $request->nominal, 'transfer');

            return redirect()->route('nasabah.pinjaman.status-pembayaran')
                ->with('success', 'Pengajuan pembayaran berhasil dikirim!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput($request->except('pin'));
        }
    }
```
</details>

### Route: `nasabah/pinjaman/pembayaran/janji-temu` [POST]
**Function:** `submitJanjiTemuPembayaran`

**Queries Detected:**
- Model: PinjamanH
- Model: PengajuanPembayaranPinjaman
- Model: IdGenerator
- Model: AdminNotification
- Model: JanjiTemuPembayaranPinjaman
- Model: ActivityLogService

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function submitJanjiTemuPembayaran(Request $request)
    {
        $validated = $request->validate([
            'pinjaman_id' => 'required|exists:tbl_pinjaman_h,id',
            'tempo_id' => 'required|exists:tempo_pinjaman_b,id',
            'jenis_tempo' => 'required|in:bulanan,mingguan',
            'nominal' => 'required|numeric|min:1',
            'lokasi_temu' => 'required|exists:jns_lokasi_perusahaan,id',
            'tanggal_janji_temu' => 'required|date|after:today',
            'waktu_janji_temu' => 'required|date_format:H:i',
            'pin' => 'required|numeric|digits:6',
            'keterangan' => 'nullable|string|max:500',
        ]);

        // Verify PIN
        /** @var User $user */
        $user = Auth::user();
        
        if (!$user->pin) {
            return redirect()->back()
                ->with('error', 'PIN belum diatur. Silakan atur PIN terlebih dahulu di profil Anda.')
                ->withInput($request->except('pin'));
        }

        $userPin = (int) $user->pin;
        $inputPin = (int) $request->pin;

        if ($userPin !== $inputPin) {
            return redirect()->back()
                ->with('error', 'PIN yang Anda masukkan salah!')
                ->withInput($request->except('pin'));
        }

        $idAnggota = $this->getIdAnggota();

        // Verify pinjaman belongs to nasabah
        $pinjaman = PinjamanH::where('id', $request->pinjaman_id)
            ->where('id_anggota', $idAnggota)
            ->firstOrFail();

        // 🛡️ Server-side guard: cegah duplikasi dalam 30 detik
        $recentDuplicate = \App\Models\PengajuanPembayaranPinjaman::where('id_anggota', $idAnggota)
            ->where('pinjaman_id', $request->pinjaman_id)
            ->where('tempo_id', $request->tempo_id)
            ->where('nominal', $request->nominal)
            ->where('status', '1')
            ->where('created_at', '>=', now()->subSeconds(30))
            ->exists();

        if ($recentDuplicate) {
            return redirect()->route('nasabah.pinjaman.pembayaran')
                ->with('warning', 'Pengajuan pembayaran yang sama sudah dikirim. Silakan tunggu beberapa saat.');
        }

        // ID dari 3 master: P (pinjaman), TN (tunai), PMB (pembayaran)
        $idPengajuanPembayaran = IdGenerator::generate('tbl_pengajuan_pembayaran_pinjaman', 'P', 'TN', 'PMB');

        try {
            // Create pengajuan pembayaran (tempo_id FK ke tempo_pinjaman_b)
            $pengajuan = PengajuanPembayaranPinjaman::create([
                'id' => $idPengajuanPembayaran,
                'id_anggota' => $idAnggota,
                'pinjaman_id' => $request->pinjaman_id,
                'tempo_id' => $request->tempo_id,
                'jenis_tempo' => $request->jenis_tempo,
                'nominal' => $request->nominal,
                'metode_pembayaran' => 'tunai',
                'keterangan' => $request->keterangan,
                'status' => '1', // Pending
            ]);

            \App\Models\AdminNotification::notify(
                'pinjaman_pembayaran',
                'Pengajuan pembayaran pinjaman baru (tunai)',
                'Nasabah mengajukan pembayaran pinjaman Rp ' . number_format((float) ($pengajuan->nominal ?? 0), 0, ',', '.') . ' via janji temu',
                route('admin.pinjaman.detail-pembayaran', $pengajuan->id),
                $pengajuan->id,
                'pengajuan_pembayaran_pinjaman'
            );

            // Create janji temu pembayaran (id seperti janji temu pinjaman: P-TN-JNJT)
            $idJanjiTemu = IdGenerator::generate('tbl_janji_temu_pembayaran_pinjaman', 'P', 'TN', 'JNJT');
            JanjiTemuPembayaranPinjaman::create([
                'id' => $idJanjiTemu,
                'id_pengajuan' => $pengajuan->id,
                'lokasi_temu' => $request->lokasi_temu,
                'nominal' => $request->nominal,
                'tanggal_janji_temu' => $request->tanggal_janji_temu,
                'waktu_janji_temu' => $request->waktu_janji_temu,
                'keterangan' => $request->keterangan,
                'status' => '1',
            ]);

            app(ActivityLogService::class)->logSubmitJanjiTemuPembayaran($idJanjiTemu, $request->nominal, $request->tanggal_janji_temu);

            return redirect()->route('nasabah.pinjaman.status-pembayaran')
                ->with('success', 'Pengajuan janji temu pembayaran berhasil dikirim!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput($request->except('pin'));
        }
    }
```
</details>

### Route: `nasabah/pinjaman/status-pembayaran` [GET|HEAD]
**Function:** `statusPembayaran`

**Queries Detected:**
- Model: PengajuanPembayaranPinjaman

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function statusPembayaran(Request $request)
    {
        $idAnggota = $this->getIdAnggota();

        $query = PengajuanPembayaranPinjaman::where('id_anggota', $idAnggota)
            ->with(['pinjaman.pengajuan', 'janjiTemu.lokasi', 'buktiFoto'])
            ->latest();

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $pengajuan = $query->paginate(15);

        return view('nasabah.pinjaman.status-pembayaran', [
            'pengajuan' => $pengajuan,
        ]);
    }
```
</details>

### Route: `nasabah/pinjaman/pembayaran/{id}` [GET|HEAD]
**Function:** `detailPembayaran`

**Queries Detected:**
- Model: PengajuanPembayaranPinjaman

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function detailPembayaran($id)
    {
        $idAnggota = $this->getIdAnggota();

        $pengajuan = PengajuanPembayaranPinjaman::where('id_anggota', $idAnggota)
            ->with(['pinjaman.pengajuan', 'janjiTemu.lokasi', 'buktiFoto'])
            ->findOrFail($id);

        return view('nasabah.pinjaman.detail-pembayaran', [
            'pengajuan' => $pengajuan,
        ]);
    }
```
</details>

## Controller: `App\Http\Controllers\Nasabah\DepositoController`

**Models Imported:**
- `App\Models\DepositoH`
- `App\Models\JnsTenorDeposito`
- `App\Models\PengajuanDeposito`
- `App\Models\PencairanDeposito`
- `App\Models\NasabahNotification`
- `App\Models\SukuBungaDeposito`
- `App\Models\PengajuanTabungan`
- `App\Models\PaketDeposito`
- `App\Models\JnsBank`
- `App\Models\KategoriDeposito`

### Route: `nasabah/deposito` [GET|HEAD]
**Function:** `index`

**Queries Detected:**
- Model: PaketDeposito
- Model: KategoriDeposito
- Model: DepositoH
- Model: PengajuanDeposito

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function index()
    {
        $nasabah = Auth::user()->nasabah;

        // BANK ACCESS GUARD dihapus dari index agar nasabah selalu bisa melihat riwayat deposito

        // Ambil semua paket deposito aktif
        $pakets = PaketDeposito::with('kategori')
            ->where('status', 'aktif')
            ->orderBy('tenor_bulan')
            ->orderBy('minimal_nominal')
            ->get();

        // Jenis deposito dinamis dari database
        $jenisDeposito = KategoriDeposito::where('status', 'aktif')->get();

        // Ambil deposito aktif nasabah
        $depositoAktif = [];
        $riwayatPengajuan = [];

        if ($nasabah) {
            $depositoAktif = DepositoH::where('id_nasabah', $nasabah->id)
                ->whereIn('status', ['aktif'])
                ->with('tenor')
                ->latest()
                ->get();

            $riwayatPengajuan = PengajuanDeposito::where('id_nasabah', $nasabah->id)
                ->with('tenor')
                ->latest()
                ->take(5)
                ->get();
        }

        return view('nasabah.deposito.index', compact(
            'pakets',
            'jenisDeposito',
            'depositoAktif',
            'riwayatPengajuan'
        ));
    }
```
</details>

### Route: `nasabah/deposito/riwayat` [GET|HEAD]
**Function:** `riwayat`

**Queries Detected:**
- Model: PengajuanDeposito

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function riwayat()
    {
        $nasabah = Auth::user()->nasabah;

        // BANK ACCESS GUARD dihapus dari riwayat agar nasabah selalu bisa melihat riwayat deposito

        $riwayat = PengajuanDeposito::where('id_nasabah', $nasabah->id)
            ->with(['tenor', 'deposito.pencairan'])
            ->latest()
            ->paginate(10);

        return view('nasabah.deposito.riwayat', compact('riwayat'));
    }
```
</details>

### Route: `nasabah/deposito/pengajuan` [GET|HEAD]
**Function:** `pengajuan`

**Queries Detected:**
- Model: BankAccessService
- Model: PaketDeposito
- Model: KategoriDeposito
- Model: JnsBank

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function pengajuan()
    {
        $nasabah = Auth::user()->nasabah;

        // ── BANK ACCESS GUARD ──────────────────────────────────────
        if ($nasabah) {
            $access = app(BankAccessService::class)->checkPremiumAccess($nasabah->id);
            if (!$access['allowed']) {
                return redirect()->route('nasabah.dashboard')
                    ->with('error', $access['reason']);
            }
        }
        // ──────────────────────────────────────────────────────────

        $pakets = PaketDeposito::with('kategori')
            ->where('status', 'aktif')
            ->orderBy('tenor_bulan')
            ->orderBy('minimal_nominal')
            ->get();

        // Jenis deposito dinamis
        $jenisDeposito = KategoriDeposito::where('status', 'aktif')->get();

        // Saldo tabungan nasabah dari history transaksi
        $saldoTabungan = $nasabah ? $this->getSaldoNasabah($nasabah->id) : 0;

        // Daftar Bank untuk Info Rekening
        $banks = JnsBank::where('status', 'aktif')->get();

        return view('nasabah.deposito.pengajuan', compact(
            'pakets',
            'jenisDeposito',
            'saldoTabungan',
            'banks'
        ));
    }
```
</details>

### Route: `nasabah/deposito/pengajuan` [POST]
**Function:** `submitPengajuan`

**Queries Detected:**
- Model: BankAccessService
- Model: PaketDeposito
- Model: JnsTenorDeposito
- Model: PengajuanDeposito
- Model: ActivityLogService

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function submitPengajuan(Request $request)
    {
        $request->validate([
            'nominal'        => 'required|numeric|min:1000000',
            'paket_id'       => 'required|exists:paket_depositos,id',
            'metode_setor'   => 'required|in:transfer,saldo_tabungan',
            'foto_bukti_tf'  => 'nullable|required_if:metode_setor,transfer|image|max:5120',
        ]);

        $nasabah = Auth::user()->nasabah;

        // ── BANK ACCESS GUARD (server-side double check) ───────────
        if ($nasabah) {
            $access = app(BankAccessService::class)->checkPremiumAccess($nasabah->id);
            if (!$access['allowed']) {
                return redirect()->route('nasabah.dashboard')
                    ->with('error', $access['reason']);
            }
        }
        // ──────────────────────────────────────────────────────────
        
        $paket = PaketDeposito::findOrFail($request->paket_id);
        if ($request->nominal < $paket->minimal_nominal) {
            return back()->with('error', 'Nominal pengajuan kurang dari batas minimal paket ini.')->withInput();
        }
        if ($paket->maksimal_nominal && $request->nominal > $paket->maksimal_nominal) {
            return back()->with('error', 'Nominal pengajuan melebihi batas maksimal paket ini.')->withInput();
        }

        if ($request->metode_setor === 'saldo_tabungan') {
            $saldo = $this->getSaldoNasabah($nasabah->id);
            if ($saldo < $request->nominal) {
                return back()->with('error', 'Saldo Tabungan tidak mencukupi untuk membuka Deposito.')->withInput();
            }
        }
        
        // Cari mapping tenor_id untuk backward compatibility
        $tenorDb = JnsTenorDeposito::where('tenor_bulan', $paket->tenor_bulan)->first();
        $tenorId = $tenorDb ? $tenorDb->id : 1; // Default fallback if not found

        $data = [
            'id_nasabah'     => $nasabah->id,
            'paket_id'       => $paket->id,
            'nominal'        => $request->nominal,
            'tenor_id'       => $tenorId, // Dipertahankan untuk compatibility Warning System
            'metode_setor'   => $request->metode_setor,
            'status'         => '1',
            'catatan'        => $request->catatan,
        ];

        if ($request->hasFile('foto_bukti_tf') && $request->metode_setor === 'transfer') {
            $data['foto_bukti_tf'] = $request->file('foto_bukti_tf')
                ->store('deposito/bukti-tf', 'public');
        }

        $pengajuan = PengajuanDeposito::create($data);

        app(\App\Services\ActivityLogService::class)->logSubmitPengajuanDeposito(
            $pengajuan->id,
            $request->nominal
        );

        return redirect()->route('nasabah.deposito.status-pengajuan', $pengajuan->id)
            ->with('success', 'Pengajuan deposito berhasil dikirim! Kami akan memproses pengajuan Anda.');
    }
```
</details>

### Route: `nasabah/deposito/pengajuan/{id}/status` [GET|HEAD]
**Function:** `statusPengajuan`

**Queries Detected:**
- Model: PengajuanDeposito

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function statusPengajuan($id)
    {
        $nasabah = Auth::user()->nasabah;

        $pengajuan = PengajuanDeposito::where('id_nasabah', $nasabah->id)
            ->with(['tenor'])
            ->findOrFail($id);

        return view('nasabah.deposito.status-pengajuan', compact('pengajuan'));
    }
```
</details>

### Route: `nasabah/deposito/aktif/{id}` [GET|HEAD]
**Function:** `detail`

**Queries Detected:**
- Model: BankAccessService
- Model: DepositoH

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function detail($id)
    {
        $nasabah = Auth::user()->nasabah;

        // ── BANK ACCESS GUARD ──────────────────────────────────────
        if ($nasabah) {
            $access = app(BankAccessService::class)->checkPremiumAccess($nasabah->id);
            if (!$access['allowed']) {
                return redirect()->route('nasabah.dashboard')
                    ->with('error', $access['reason']);
            }
        }
        // ──────────────────────────────────────────────────────────

        $deposito = DepositoH::where('id_nasabah', $nasabah->id)
            ->with(['tenor', 'bungaHarian', 'transDeposito', 'pencairan'])
            ->findOrFail($id);

        return view('nasabah.deposito.detail', compact('deposito'));
    }
```
</details>

### Route: `nasabah/deposito/aktif/{id}/cairkan` [POST]
**Function:** `ajukanCairkan`

**Queries Detected:**
- Model: DepositoH
- Model: PencairanDeposito

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function ajukanCairkan(Request $request, $id)
    {
        $request->validate([
            'jenis_pencairan' => 'required|in:rek_nasabah,saldo_tabungan',
        ]);

        $nasabah  = Auth::user()->nasabah;
        $deposito = DepositoH::where('id_nasabah', $nasabah->id)
            ->with('tenor')->findOrFail($id);

        // Hitung nominal akhir: pokok + bunga bersih (setelah pajak 20%)
        $tglJatuhTempo = $deposito->tgl_jatuh_tempo;
        $isLeap = $tglJatuhTempo ? (($tglJatuhTempo->year % 4 === 0 && $tglJatuhTempo->year % 100 !== 0) || ($tglJatuhTempo->year % 400 === 0)) : false;
        $pembagi = $isLeap ? 366 : 365;
        
        $bungaKotor  = $deposito->nominal_awal * $deposito->bunga * (($deposito->tenor->tenor_hari ?? 30) / $pembagi);
        $pajak       = $bungaKotor * 0.20;
        $nominalAkhir = $deposito->nominal_awal + $bungaKotor - $pajak;

        // Cek apakah sudah ada request yang pending
        $existing = PencairanDeposito::where('deposito_id', $deposito->id)
            ->where('status', 'pending')->first();

        if ($existing) {
            return back()->with('error', 'Permintaan pencairan sudah diajukan sebelumnya dan masih dalam proses.');
        }

        // Buat record pencairan
        PencairanDeposito::create([
            'deposito_id'     => $deposito->id,
            'id_nasabah'      => $nasabah->id,
            'jenis_pencairan' => $request->jenis_pencairan,
            'metode_pencairan'=> $request->jenis_pencairan, // compat
            'nominal_akhir'   => $nominalAkhir,
            'status'          => 'pending',
            'catatan'         => 'Pengajuan pencairan oleh nasabah via ' .
                ($request->jenis_pencairan === 'rek_nasabah' ? 'Transfer ke Rekening' : 'Saldo Tabungan'),
        ]);

        return back()->with('success', 'Permintaan pencairan berhasil diajukan. Admin kami akan segera memprosesnya.');
    }
```
</details>

### Route: `nasabah/deposito/aktif/{id}/cancel` [POST]
**Function:** `ajukanCancel`

**Queries Detected:**
- Model: DepositoH
- Model: PencairanDeposito

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function ajukanCancel(Request $request, $id)
    {
        $request->validate([
            'pin' => 'required|numeric',
            'jenis_pencairan' => 'required|in:saldo_tabungan,rek_nasabah',
        ]);

        $user = Auth::user();
        if ((int) $request->pin !== (int) $user->pin) {
            return back()->with('error', 'PIN yang Anda masukkan salah.');
        }

        $nasabah  = $user->nasabah;
        $deposito = DepositoH::where('id_nasabah', $nasabah->id)
            ->findOrFail($id);

        // Cek apakah sudah ada request yang pending
        $existing = PencairanDeposito::where('deposito_id', $deposito->id)
            ->where('status', 'pending')->first();

        if ($existing) {
            return back()->with('error', 'Permintaan pencairan/pembatalan sudah diajukan sebelumnya dan masih dalam proses.');
        }

        // Buat record pencairan dengan flag is_cancel = true
        PencairanDeposito::create([
            'deposito_id'     => $deposito->id,
            'id_nasabah'      => $nasabah->id,
            'jenis_pencairan' => $request->jenis_pencairan,
            'metode_pencairan'=> $request->jenis_pencairan, // compat
            'nominal_akhir'   => $deposito->nominal_awal, // hanya pokok kembali
            'status'          => 'pending',
            'is_cancel'       => true,
            'catatan'         => 'Pengajuan pembatalan deposito oleh nasabah via ' .
                ($request->jenis_pencairan === 'rek_nasabah' ? 'Transfer ke Rekening Bank' : 'Saldo Tabungan') . '.',
        ]);

        return back()->with('success', 'Permintaan pembatalan deposito berhasil diajukan. Admin kami akan segera memproses pengembalian dana.');
    }
```
</details>

## Controller: `App\Http\Controllers\NasabahGadaiBaruController`

**Models Imported:**
- `App\Models\GadaiMasterKategori`
- `App\Models\GadaiMasterItem`
- `App\Models\GadaiActive`
- `App\Models\JnsLokasiPerusahaan`

### Route: `nasabah/gadai_baru` [GET|HEAD]
**Function:** `index`

**Queries Detected:**
- Model: GadaiMasterKategori
- Model: GadaiActive
- Model: GadaiPengajuan

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function index()
    {
        $kategoriElektronik = GadaiMasterKategori::with('items')->where('kode_kategori', 'electronic')->first();
        $kategoriKendaraan = GadaiMasterKategori::with('items')->where('kode_kategori', 'vehicle')->first();
        $kategoriEmas = GadaiMasterKategori::with('items')->where('kode_kategori', 'gold')->first();

        $pengajuanLunas = collect();
        $pengajuanPerpanjang = collect();
        $gadaiSelesai = collect();

        $nasabah = Auth::user()->nasabah;
        
        // BANK ACCESS GUARD dihapus dari index agar nasabah selalu bisa melihat daftar gadainya

        if ($nasabah) {
            $nasabahId = $nasabah->id;
            
            $gadaiAktif = GadaiActive::with(['kategori', 'item', 'lokasi'])
                ->where('nasabah_id', $nasabahId)
                ->whereIn('status', ['active', 'grace_period'])
                ->orderBy('created_at', 'desc')
                ->get();

            $pengajuanLunas = \App\Models\GadaiPengajuan::with(['gadaiActive.item'])
                ->where('nasabah_id', $nasabahId)
                ->where('jenis_pengajuan', 'lunas')
                ->latest()
                ->take(5)
                ->get();

            $pengajuanPerpanjang = \App\Models\GadaiPengajuan::with(['gadaiActive.item'])
                ->where('nasabah_id', $nasabahId)
                ->whereIn('jenis_pengajuan', ['perpanjang', 'perpanjangan'])
                ->latest()
                ->take(5)
                ->get();

            $gadaiSelesai = GadaiActive::with(['kategori', 'item'])
                ->where('nasabah_id', $nasabahId)
                ->whereNotIn('status', ['active', 'grace_period'])
                ->orderBy('updated_at', 'desc')
                ->take(5)
                ->get();
        }

        return view('nasabah.gadai_baru.index', compact(
            'kategoriElektronik', 
            'kategoriKendaraan', 
            'kategoriEmas', 
            'gadaiAktif',
            'pengajuanLunas',
            'pengajuanPerpanjang',
            'gadaiSelesai'
        ));
    }
```
</details>

### Route: `nasabah/gadai_baru/riwayat` [GET|HEAD]
**Function:** `riwayat`

**Queries Detected:**
- Model: GadaiActive

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function riwayat()
    {
        $nasabah = Auth::user()->nasabah;
        
        // BANK ACCESS GUARD dihapus dari riwayat agar nasabah selalu bisa melihat riwayat gadainya
        
        $gadaiAktif = GadaiActive::with(['kategori', 'item', 'lokasi'])
            ->where('nasabah_id', $nasabah->id)
            ->whereIn('status', ['active', 'grace_period'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        $gadaiSelesai = GadaiActive::with(['kategori', 'item', 'lokasi'])
            ->where('nasabah_id', $nasabah->id)
            ->whereNotIn('status', ['active', 'grace_period'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('nasabah.gadai_baru.riwayat', compact('gadaiAktif', 'gadaiSelesai'));
    }
```
</details>

### Route: `nasabah/gadai_baru/aktif/{id}` [GET|HEAD]
**Function:** `showActiveDetail`

**Queries Detected:**
- Model: GadaiActive

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function showActiveDetail($id)
    {
        $nasabah = Auth::user()->nasabah;
        
        // BANK ACCESS GUARD dihapus dari showActiveDetail agar nasabah selalu bisa melihat detail gadai aktifnya

        $gadai = GadaiActive::with(['kategori', 'item', 'lokasi', 'files', 'paymentLogs', 'history'])
            ->where('nasabah_id', $nasabah->id)
            ->findOrFail($id);

        return view('nasabah.gadai_baru.aktif_detail', compact('gadai'));
    }
```
</details>

### Route: `nasabah/gadai_baru/{kategori}/{item}` [GET|HEAD]
**Function:** `show`

**Queries Detected:**
- Model: GadaiMasterKategori
- Model: GadaiMasterItem
- Model: JnsLokasiPerusahaan
- Model: BankAccessService

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function show($kategori_kode, $item_id)
    {
        $kategori = GadaiMasterKategori::where('kode_kategori', $kategori_kode)->firstOrFail();
        $item = GadaiMasterItem::where('id', $item_id)->where('kategori_id', $kategori->id)->firstOrFail();
        $lokasi = JnsLokasiPerusahaan::all();
        $nasabah = Auth::user()->nasabah;

        // ── BANK ACCESS GUARD ──────────────────────────────────────
        if ($nasabah) {
            $access = app(BankAccessService::class)->checkPremiumAccess($nasabah->id);
            if (!$access['allowed']) {
                return redirect()->route('nasabah.dashboard')
                    ->with('error', $access['reason']);
            }
        }
        // ──────────────────────────────────────────────────────────

        return view('nasabah.gadai_baru.show', compact('kategori', 'item', 'lokasi', 'nasabah'));
    }
```
</details>

### Route: `nasabah/gadai_baru/pengajuan/{id}/{jenis}` [GET|HEAD]
**Function:** `createPengajuan`

**Queries Detected:**
- Model: GadaiActive
- Model: BankAccessService
- Model: JnsLokasiPerusahaan
- Model: JnsBank

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function createPengajuan($id, $jenis)
    {
        $gadai = GadaiActive::with(['kategori', 'item', 'lokasi'])->findOrFail($id);
        
        // Safety check: ensure gadai belongs to current nasabah
        if ($gadai->nasabah_id !== Auth::user()->nasabah->id) {
            abort(403);
        }

        // ── BANK ACCESS GUARD ──────────────────────────────────────
        $access = app(BankAccessService::class)->checkPremiumAccess($gadai->nasabah_id);
        if (!$access['allowed']) {
            return redirect()->route('nasabah.dashboard')
                ->with('error', $access['reason']);
        }
        // ──────────────────────────────────────────────────────────

        // 🛡️ Guard Perpanjangan rules
        if (in_array($jenis, ['perpanjang', 'perpanjangan'])) {
            if (!in_array($gadai->status, ['active', 'grace_period'])) {
                return redirect()->route('nasabah.gadai_baru.index')->with('warning', 'Perpanjangan hanya dapat dilakukan untuk gadai yang aktif atau dalam masa tenggang.');
            }
            if ($gadai->jumlah_perpanjangan >= 3) {
                return redirect()->route('nasabah.gadai_baru.index')->with('warning', 'Maksimal perpanjangan adalah 3 kali.');
            }
        }

        // Calculate totals (re-using logic from dashboard)
        $totalTagihan = $gadai->nominal_deal + $gadai->biaya_jasa + $gadai->biaya_inap + ($gadai->extra_pinjaman_nominal ?? 0); // Denda hanya untuk perpanjang
        $totalPerpanjang = $gadai->biaya_jasa + $gadai->denda_aktif + $gadai->biaya_inap;
        
        $nominal = ($jenis == 'lunas') ? $totalTagihan : $totalPerpanjang;
        $lokasi = JnsLokasiPerusahaan::where('status_aktif', true)->get();
        $banks = \App\Models\JnsBank::where('status', 'aktif')->get();

        return view('nasabah.gadai_baru.pengajuan', compact('gadai', 'jenis', 'nominal', 'lokasi', 'banks'));
    }
```
</details>

### Route: `nasabah/gadai_baru/pengajuan/{id}/{jenis}` [POST]
**Function:** `storePengajuan`

**Queries Detected:**
- Model: GadaiActive
- Model: BankAccessService
- Model: GadaiPengajuan
- Model: ActivityLogService
- Model: GadaiFile

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function storePengajuan(Request $request, $id, $jenis)
    {
        $request->validate([
            'pin' => 'required|numeric|digits:6',
            'metode' => 'required|in:cash,transfer',
            'tgl_janji_temu' => 'required_if:metode,cash|nullable|date|after:now',
            'bukti_transfer.*' => 'required_if:metode,transfer|nullable|image|max:2048',
            'keterangan' => 'nullable|string'
        ]);

        $gadai = GadaiActive::findOrFail($id);
        
        // Safety check
        if ($gadai->nasabah_id !== Auth::user()->nasabah->id) {
            abort(403);
        }

        // ── BANK ACCESS GUARD ──────────────────────────────────────
        $access = app(BankAccessService::class)->checkPremiumAccess($gadai->nasabah_id);
        if (!$access['allowed']) {
            return redirect()->route('nasabah.dashboard')
                ->with('error', $access['reason']);
        }
        // ──────────────────────────────────────────────────────────

        // Verify PIN
        $user = Auth::user();
        if (!$user->pin || (int)$user->pin !== (int)$request->pin) {
            return redirect()->back()
                ->with('error', 'PIN yang Anda masukkan salah!')
                ->withInput($request->except('pin'));
        }

        // 🛡️ Guard Duplikasi: check if there's already a pending request for this gadai
        $pending = \App\Models\GadaiPengajuan::where('gadai_active_id', $id)
            ->where('status', 'pending')
            ->first();
            
        if ($pending) {
            return redirect()->route('nasabah.gadai_baru.index')->with('warning', 'Anda sudah memiliki pengajuan yang sedang menunggu verifikasi untuk gadai ini.');
        }

        // 🛡️ Guard Perpanjangan rules
        if (in_array($jenis, ['perpanjang', 'perpanjangan'])) {
            if (!in_array($gadai->status, ['active', 'grace_period'])) {
                return redirect()->route('nasabah.gadai_baru.index')->with('warning', 'Perpanjangan hanya dapat dilakukan untuk gadai yang aktif atau dalam masa tenggang.');
            }
            if ($gadai->jumlah_perpanjangan >= 3) {
                return redirect()->route('nasabah.gadai_baru.index')->with('warning', 'Maksimal perpanjangan adalah 3 kali.');
            }
        }

        // Calculate nominal again to be safe
        $totalTagihan = $gadai->nominal_deal + $gadai->biaya_jasa + $gadai->biaya_inap + ($gadai->extra_pinjaman_nominal ?? 0); // Denda hanya untuk perpanjang
        $totalPerpanjang = $gadai->biaya_jasa + $gadai->denda_aktif + $gadai->biaya_inap;
        $nominal = ($jenis == 'lunas') ? $totalTagihan : $totalPerpanjang;

        $pengajuan = new \App\Models\GadaiPengajuan();
        $pengajuan->nasabah_id = Auth::user()->nasabah->id;
        $pengajuan->gadai_active_id = $id;
        $pengajuan->jenis_pengajuan = $jenis;
        $pengajuan->metode = $request->metode;
        $pengajuan->nominal = $nominal;
        $pengajuan->keterangan = $request->keterangan;
        
        if ($request->metode == 'cash') {
            $pengajuan->tgl_janji_temu = $request->tgl_janji_temu;
        }

        $pengajuan->save();

        app(\App\Services\ActivityLogService::class)->logSubmitPengajuanGadai(
            $pengajuan->id,
            $nominal
        );

        // Handle Multiple Files
        if ($request->metode == 'transfer' && $request->hasFile('bukti_transfer')) {
            foreach ($request->file('bukti_transfer') as $index => $file) {
                $path = $file->store('bukti_transfer_gadai', 'public');
                
                // Save to tbl_gadai_files for multi-file support
                \App\Models\GadaiFile::create([
                    'gadai_active_id' => $gadai->id,
                    'pengajuan_id' => $pengajuan->id,
                    'path_file' => $path,
                    'tipe_foto' => 'lainnya' // We use 'lainnya' for payment proof
                ]);

                // Also save the FIRST one to pengajuan.bukti_transfer for backward compatibility/simplicity in list view
                if ($index === 0) {
                    $pengajuan->update(['bukti_transfer' => $path]);
                }
            }
        }

        return redirect()->route('nasabah.gadai_baru.status-pengajuan')->with('success', 'Pengajuan berhasil dikirim. Mohon tunggu verifikasi admin.');
    }
```
</details>

### Route: `nasabah/gadai_baru/status-pengajuan` [GET|HEAD]
**Function:** `statusPengajuan`

**Queries Detected:**
- Model: BankAccessService
- Model: GadaiPengajuan

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function statusPengajuan()
    {
        $nasabah = Auth::user()->nasabah;

        // ── BANK ACCESS GUARD ──────────────────────────────────────
        if ($nasabah) {
            $access = app(BankAccessService::class)->checkPremiumAccess($nasabah->id);
            if (!$access['allowed']) {
                return redirect()->route('nasabah.dashboard')
                    ->with('error', $access['reason']);
            }
        }
        // ──────────────────────────────────────────────────────────

        $pengajuan = \App\Models\GadaiPengajuan::with(['gadaiActive.item', 'gadaiActive.kategori', 'files'])
            ->where('nasabah_id', $nasabah->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('nasabah.gadai_baru.status_pengajuan', compact('pengajuan'));
    }
```
</details>

## Controller: `App\Http\Controllers\Admin\LaporanKeuanganController`

**Models Imported:**
- `App\Models\TransTabungan`
- `App\Models\PinjamanH`
- `App\Models\TempoPinjamanB`
- `App\Models\TempoPinjamanM`
- `App\Models\PengajuanTabungan`
- `App\Models\PengajuanPenarikanTabungan`
- `App\Models\PengajuanPinjaman`
- `App\Models\PengajuanPembayaranPinjaman`
- `App\Models\JnsTransaksi`

### Route: `admin/laporan/tabungan` [GET|HEAD]
**Function:** `tabungan`

**Queries Detected:**
- Model: TransTabungan

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function tabungan(Request $request)
    {
        $dari = $request->get('tgl_dari', now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->get('tgl_sampai', now()->format('Y-m-d'));

        $transaksi = TransTabungan::with(['nasabah.user', 'jnsTransaksi'])
            ->whereBetween(DB::raw('DATE(tgl_transaksi)'), [$dari, $sampai])
            ->orderBy('tgl_transaksi')
            ->get();

        $totalSetor = $transaksi->filter(fn($t) => $t->jnsTransaksi && $t->jnsTransaksi->kode === 'STR')->sum('nominal');
        $totalTarik = $transaksi->filter(fn($t) => $t->jnsTransaksi && $t->jnsTransaksi->kode === 'PNR')->sum('nominal');

        $data = [
            'tgl_dari' => $dari,
            'tgl_sampai' => $sampai,
            'transaksi' => $transaksi,
            'total_setor' => $totalSetor,
            'total_tarik' => $totalTarik,
            'net' => $totalSetor - $totalTarik,
        ];

        if ($request->get('export') === 'pdf') {
            return $this->exportPdf('admin.laporan.export.tabungan', $data, 'Laporan-Tabungan-' . $dari . '-sd-' . $sampai . '.pdf');
        }
        if ($request->get('export') === 'excel') {
            return $this->exportExcelTabungan($data);
        }

        return view('admin.laporan.tabungan', $data);
    }
```
</details>

### Route: `admin/laporan/saldo-tabungan` [GET|HEAD]
**Function:** `saldoTabungan`

**Queries Detected:**
- Model: TransTabungan

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function saldoTabungan(Request $request)
    {
        $tglCutoff = $request->get('tgl_cutoff', now()->format('Y-m-d'));

        $transaksi = TransTabungan::with(['nasabah.user', 'jnsTransaksi'])
            ->where(DB::raw('DATE(tgl_transaksi)'), '<=', $tglCutoff)
            ->orderBy('id_anggota')
            ->orderBy('tgl_transaksi')
            ->get();

        $perNasabah = $transaksi->groupBy('id_anggota')->map(function ($items) {
            $setor = $items->filter(fn($t) => $t->jnsTransaksi && $t->jnsTransaksi->kode === 'STR')->sum('nominal');
            $tarik = $items->filter(fn($t) => $t->jnsTransaksi && $t->jnsTransaksi->kode === 'PNR')->sum('nominal');
            return (object)[
                'nasabah' => $items->first()->nasabah,
                'saldo' => $setor - $tarik,
                'total_setor' => $setor,
                'total_tarik' => $tarik,
            ];
        })->filter(fn($r) => $r->saldo != 0 || $r->total_setor > 0);

        $data = [
            'tgl_cutoff' => $tglCutoff,
            'per_nasabah' => $perNasabah->values(),
            'total_saldo' => $perNasabah->sum('saldo'),
        ];

        if ($request->get('export') === 'pdf') {
            return $this->exportPdf('admin.laporan.export.saldo-tabungan', $data, 'Laporan-Saldo-Tabungan-' . $tglCutoff . '.pdf');
        }
        if ($request->get('export') === 'excel') {
            return $this->exportExcelSaldoTabungan($data);
        }

        return view('admin.laporan.saldo-tabungan', $data);
    }
```
</details>

### Route: `admin/laporan/pinjaman-aktif` [GET|HEAD]
**Function:** `pinjamanAktif`

**Queries Detected:**
- Model: PinjamanH

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function pinjamanAktif(Request $request)
    {
        $pinjaman = PinjamanH::with(['nasabah.user', 'tempoBulanan', 'tempoMingguan'])
            ->where('lunas', 'belum')
            ->orderBy('tgl_pinjam')
            ->get()
            ->map(function ($p) {
                $terbayarB = $p->tempoBulanan->sum('jumlah_terbayar');
                $terbayarM = $p->tempoMingguan->sum('jumlah_terbayar');
                $totalTerbayar = $terbayarB + $terbayarM;
                $sisaPokok = $p->jumlah_pinjam - $totalTerbayar;
                $totalTempo = $p->tempoBulanan->count() + $p->tempoMingguan->count();
                $sudahBayar = $p->tempoBulanan->where('status_bayar', '!=', 'belum')->count() + $p->tempoMingguan->where('status_bayar', '!=', 'belum')->count();
                return (object)[
                    'pinjaman' => $p,
                    'total_terbayar' => $totalTerbayar,
                    'sisa_pokok' => $sisaPokok,
                    'sisa_angsuran' => $totalTempo - $sudahBayar,
                ];
            });

        $data = [
            'rows' => $pinjaman,
            'total_outstanding' => $pinjaman->sum('sisa_pokok'),
        ];

        if ($request->get('export') === 'pdf') {
            return $this->exportPdf('admin.laporan.export.pinjaman-aktif', $data, 'Laporan-Pinjaman-Aktif.pdf');
        }
        if ($request->get('export') === 'excel') {
            return $this->exportExcelPinjamanAktif($data);
        }

        return view('admin.laporan.pinjaman-aktif', $data);
    }
```
</details>

### Route: `admin/laporan/angsuran-pinjaman` [GET|HEAD]
**Function:** `angsuranPinjaman`

**Queries Detected:**
- Model: TempoPinjamanB
- Model: TempoPinjamanM

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function angsuranPinjaman(Request $request)
    {
        $dari = $request->get('tgl_dari', now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->get('tgl_sampai', now()->format('Y-m-d'));

        $tempoB = TempoPinjamanB::with(['pinjaman.nasabah.user'])
            ->whereNotNull('tgl_bayar')
            ->whereBetween(DB::raw('DATE(tgl_bayar)'), [$dari, $sampai])
            ->orderBy('tgl_bayar')
            ->get();
        $tempoM = TempoPinjamanM::with(['pinjaman.nasabah.user'])
            ->whereNotNull('tgl_bayar')
            ->whereBetween(DB::raw('DATE(tgl_bayar)'), [$dari, $sampai])
            ->orderBy('tgl_bayar')
            ->get();

        $rows = $tempoB->map(fn($t) => (object)[
            'tempo' => $t,
            'tgl_bayar' => $t->tgl_bayar,
            'pinjaman' => $t->pinjaman,
            'pokok' => $t->jumlah_terbayar,
            'denda' => $t->denda ?? 0,
            'total' => $t->jumlah_terbayar + ($t->denda ?? 0),
        ])->concat($tempoM->map(fn($t) => (object)[
            'tempo' => $t,
            'tgl_bayar' => $t->tgl_bayar,
            'pinjaman' => $t->pinjaman,
            'pokok' => $t->jumlah_terbayar,
            'denda' => $t->denda ?? 0,
            'total' => $t->jumlah_terbayar + ($t->denda ?? 0),
        ]))->sortBy('tgl_bayar')->values();

        $data = [
            'tgl_dari' => $dari,
            'tgl_sampai' => $sampai,
            'rows' => $rows,
            'total_pokok' => $rows->sum('pokok'),
            'total_denda' => $rows->sum('denda'),
            'total_jumlah' => $rows->sum('total'),
        ];

        if ($request->get('export') === 'pdf') {
            return $this->exportPdf('admin.laporan.export.angsuran-pinjaman', $data, 'Laporan-Angsuran-' . $dari . '-sd-' . $sampai . '.pdf');
        }
        if ($request->get('export') === 'excel') {
            return $this->exportExcelAngsuran($data);
        }

        return view('admin.laporan.angsuran-pinjaman', $data);
    }
```
</details>

## Controller: `App\Http\Controllers\Admin\TabunganController`

**Models Imported:**
- `App\Models\PengajuanTabungan`
- `App\Models\PengajuanPenarikanTabungan`
- `App\Models\TransTabungan`
- `App\Models\JanjiTemuTabungan`
- `App\Models\Nasabah`
- `App\Models\BuktiFotoTabungan`
- `App\Models\BuktiFoto`
- `App\Models\BiayaTransfer`
- `App\Models\NasabahNotification`
- `App\Models\PettyCashTransaksiNasabah`
- `App\Models\PettyCashSaldo`
- `App\Models\User`
- `App\Models\PettyCashOwnerTransaksi`

### Route: `admin/tabungan` [GET|HEAD]
**Function:** `index`

**Queries Detected:**
- Model: PengajuanTabungan
- Model: PengajuanPenarikanTabungan
- Model: TransTabungan
- Model: JanjiTemuTabungan

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function index()
    {
        // Statistik tabungan (pengajuan tarik = hanya transfer; tunai lewat Janji Temu)
        $stats = [
            'total_pengajuan_setor' => PengajuanTabungan::where('status', '1')->count(),
            'total_pengajuan_tarik' => PengajuanPenarikanTabungan::where('status', '1')->where('metode_transfer', 'transfer')->count(),
            'total_transaksi_hari_ini' => TransTabungan::whereDate('created_at', today())->count(),
            'count_setoran_hari_ini' => TransTabungan::whereHas('jnsTransaksi', function($q) {
                    $q->where('kode', 'STR');
                })->whereDate('created_at', today())->count(),
            'total_setoran_hari_ini' => TransTabungan::whereHas('jnsTransaksi', function($q) {
                    $q->where('kode', 'STR');
                })->whereDate('created_at', today())->sum('nominal') ?? 0,
            'count_penarikan_hari_ini' => TransTabungan::whereHas('jnsTransaksi', function($q) {
                    $q->where('kode', 'PNR');
                })->whereDate('created_at', today())->count(),
            'total_penarikan_hari_ini' => TransTabungan::whereHas('jnsTransaksi', function($q) {
                    $q->where('kode', 'PNR');
                })->whereDate('created_at', today())->sum('nominal') ?? 0,
            'total_janji_temu_pending' => JanjiTemuTabungan::where('tanggal_janji_temu', '>=', now())->count(),
        ];

        // Pengajuan setoran terbaru (pending)
        $pengajuan_setor_terbaru = PengajuanTabungan::where('status', '1')
            ->with(['nasabah.user', 'buktiFoto'])
            ->latest()
            ->take(5)
            ->get();

        // Pengajuan penarikan terbaru (pending, hanya transfer)
        $pengajuan_tarik_terbaru = PengajuanPenarikanTabungan::where('status', '1')
            ->where('metode_transfer', 'transfer')
            ->with('nasabah.user')
            ->latest()
            ->take(5)
            ->get();

        // Transaksi terbaru
        $transaksi_terbaru = TransTabungan::with('nasabah.user')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.tabungan.index', compact(
            'stats',
            'pengajuan_setor_terbaru',
            'pengajuan_tarik_terbaru',
            'transaksi_terbaru'
        ));
    }
```
</details>

### Route: `admin/tabungan/pengajuan-setor` [GET|HEAD]
**Function:** `pengajuanSetor`

**Queries Detected:**
- Model: PengajuanTabungan

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function pengajuanSetor(Request $request)
    {
        $query = PengajuanTabungan::with(['nasabah.user', 'buktiFoto'])  // Removed janjiTemu
            ->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', '1');
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('nasabah.user', function($sq) use ($search) {
                    $sq->where('nama', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('id', 'like', "%{$search}%");
            });
        }

        $pengajuan = $query->paginate(15)->withQueryString();

        return view('admin.tabungan.pengajuan-setor', compact('pengajuan'));
    }
```
</details>

### Route: `admin/tabungan/pengajuan-setor/{id}` [GET|HEAD]
**Function:** `detailPengajuanSetor`

**Queries Detected:**
- Model: PengajuanTabungan

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function detailPengajuanSetor($id)
    {
        $pengajuan = PengajuanTabungan::with(['nasabah.user', 'nasabah.dataKtp', 'nasabah.dataRek', 'buktiFoto'])  // Removed janjiTemu
            ->findOrFail($id);

        return view('admin.tabungan.detail-pengajuan-setor', compact('pengajuan'));
    }
```
</details>

### Route: `admin/tabungan/pengajuan-tarik` [GET|HEAD]
**Function:** `pengajuanTarik`

**Queries Detected:**
- Model: PengajuanPenarikanTabungan

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function pengajuanTarik(Request $request)
    {
        // Hanya pengajuan penarikan via TRANSFER. Penarikan tunai diproses via Janji Temu.
        $query = PengajuanPenarikanTabungan::with('nasabah.user')
            ->where('metode_transfer', 'transfer')
            ->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', '1');
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('nasabah.user', function($sq) use ($search) {
                    $sq->where('nama', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('id', 'like', "%{$search}%");
            });
        }

        $pengajuan = $query->paginate(15)->withQueryString();

        return view('admin.tabungan.pengajuan-tarik', compact('pengajuan'));
    }
```
</details>

### Route: `admin/tabungan/pengajuan-tarik/{id}` [GET|HEAD]
**Function:** `detailPengajuanTarik`

**Queries Detected:**
- Model: PengajuanPenarikanTabungan
- Model: BiayaTransfer
- Model: PettyCashSaldo

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function detailPengajuanTarik($id)
    {
        $pengajuan = PengajuanPenarikanTabungan::with(['nasabah.user', 'nasabah.dataKtp'])
            ->findOrFail($id);

        // Penarikan tunai diproses via Janji Temu, bukan di halaman ini
        if ($pengajuan->metode_transfer !== 'transfer') {
            return redirect()->route('admin.janji-temu.index')
                ->with('info', 'Penarikan tunai diproses melalui menu Janji Temu. Silakan cek daftar janji temu untuk penarikan tunai.');
        }

        // Get saldo nasabah
        $saldo = $this->getSaldoNasabah($pengajuan->id_anggota);

        // Biaya transfer (ditanggung nasabah): untuk tampilan sisa & form
        $biayaTransferList = BiayaTransfer::where('is_active', true)->get();
        $biayaDefault = $biayaTransferList->where('bank_penerima', $pengajuan->nama_bank)->first()?->biaya_admin
            ?? $biayaTransferList->first()?->biaya_admin
            ?? 0;
        $biayaDefault = (float) $biayaDefault;
        
        // Get admin petty cash balance (Transfer)
        $adminSaldo = PettyCashSaldo::getSaldoTransfer(Auth::id());

        return view('admin.tabungan.detail-pengajuan-tarik', compact('pengajuan', 'saldo', 'biayaTransferList', 'biayaDefault', 'adminSaldo'));
    }
```
</details>

### Route: `admin/tabungan/transaksi` [GET|HEAD]
**Function:** `transaksi`

**Queries Detected:**
- Model: TransTabungan

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function transaksi(Request $request)
    {
        $query = TransTabungan::with(['nasabah.user', 'adminPengelola'])
            ->latest();

        // Filter Riwayat Saya / Petty Cash
        if ($request->filter == 'saya') {
            $query->where('admin_pengelola_id', Auth::id());
            $title = 'Riwayat Proses Saya';
        } elseif ($request->filter == 'petty') {
            $query->where('is_petty_cash', 1);
            $title = 'Transaksi Petty Cash';
        } else {
            $title = 'Semua Transaksi';
        }

        // Filter by jenis
        if ($request->filled('jenis')) {
            $kode = $request->jenis === 'setoran' ? 'STR' : ($request->jenis === 'penarikan' ? 'PNR' : null);
            if ($kode) {
                $query->whereHas('jnsTransaksi', function ($q) use ($kode) {
                    $q->where('kode', $kode);
                });
            }
        }

        // Filter by date
        $query->when($request->filled('tanggal_dari'), function($q) use ($request) {
            $q->whereDate('tgl_transaksi', '>=', $request->tanggal_dari);
        });

        $query->when($request->filled('tanggal_sampai'), function($q) use ($request) {
            $q->whereDate('tgl_transaksi', '<=', $request->tanggal_sampai);
        });

        // Search - Use where to group the search conditions
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('nasabah.user', function($sq) use ($search) {
                    $sq->where('nama', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('id', 'like', "%{$search}%");
            });
        }

        $transaksi = $query->paginate(20)->withQueryString();

        $myCount = TransTabungan::where('admin_pengelola_id', Auth::id())->count();
        $pettyCount = TransTabungan::where('is_petty_cash', 1)->count();

        return view('admin.tabungan.transaksi', compact('transaksi', 'title', 'myCount', 'pettyCount'));
    }
```
</details>

### Route: `admin/tabungan/transaksi/create` [GET|HEAD]
**Function:** `createTransaksi`

**Queries Detected:**
- Model: AdminPermissionService
- Model: Nasabah

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function createTransaksi()
    {
        // Authorization: Only Admin Utama can create manual transactions
        if (!app(\App\Services\AdminPermissionService::class)->canCrudTabunganTransaksi(Auth::user())) {
            abort(403, 'Anda tidak memiliki akses untuk fitur ini. Hanya Admin Utama yang dapat membuat transaksi manual.');
        }

        $nasabah = Nasabah::with('user')->get();

        return view('admin.tabungan.create-transaksi', compact('nasabah'));
    }
```
</details>

### Route: `admin/tabungan/transaksi` [POST]
**Function:** `storeTransaksi`

**Queries Detected:**
- Table: jns_via
- Table: jns_transaksi
- Model: AdminPermissionService
- Model: IdGenerator
- Model: TransTabungan
- Model: Nasabah
- Model: ActivityLogService

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function storeTransaksi(Request $request)
    {
        // Authorization: Only Admin Utama can create manual transactions
        if (!app(\App\Services\AdminPermissionService::class)->canCrudTabunganTransaksi(Auth::user())) {
            abort(403, 'Anda tidak memiliki akses untuk fitur ini. Hanya Admin Utama yang dapat membuat transaksi manual.');
        }

        $request->validate([
            'id_anggota' => 'required|exists:tbl_nasabah,id',
            'jenis' => 'required|in:setoran,penarikan',
            'nominal' => 'required|numeric|min:10000',
            'via' => 'required|in:transfer,cash',
            'keterangan' => 'nullable|string|max:500',
            'tgl_transaksi' => 'required|date',
            'foto_bukti' => 'nullable|image|max:5120',
        ]);

        // If penarikan, check saldo
        if ($request->jenis == 'penarikan') {
            $saldo = $this->getSaldoNasabah($request->id_anggota);
            if ($saldo < $request->nominal) {
                return redirect()->back()
                    ->with('error', 'Saldo nasabah tidak mencukupi')
                    ->withInput();
            }
        }

        // V2 Logic Mapping
        $kodeVia = ($request->via == 'transfer') ? 'TF' : 'TN';
        $kodeTrans = ($request->jenis == 'setoran') ? 'STR' : 'PNR';
        
        $idVia = DB::table('jns_via')->where('kode', $kodeVia)->value('id');
        $idTrans = DB::table('jns_transaksi')->where('kode', $kodeTrans)->value('id');
        
        // Generate ID using correct method
        $idTransaksi = IdGenerator::generate('trans_tabungan', 'T', $kodeVia, $kodeTrans);

        // Upload foto bukti if exists
        $fotoBukti = null;
        if ($request->hasFile('foto_bukti')) {
            $fotoBukti = $request->file('foto_bukti')->store('bukti_transaksi', 'public');
        }

        // Create transaksi
        $transaksi = TransTabungan::create([
            'id' => $idTransaksi,
            'id_anggota' => $request->id_anggota,
            'id_jns_via' => $idVia,
            'id_jns_transaksi' => $idTrans,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan . ($fotoBukti ? ' | Foto: ' . $fotoBukti : ''),
            'tgl_transaksi' => $request->tgl_transaksi,
        ]);

        $nasabahRecord = \App\Models\Nasabah::with('user')->find($request->id_anggota);
        app(ActivityLogService::class)->logCreateTransaksiManual($idTransaksi, $request->nominal, $nasabahRecord->user->nama ?? 'N/A', $request->jenis);

        return redirect()->route('admin.tabungan.transaksi')
            ->with('success', "Transaksi {$request->jenis} berhasil dibuat dengan ID: {$idTransaksi}");
    }
```
</details>

### Route: `admin/tabungan/transaksi/{id}/edit` [GET|HEAD]
**Function:** `editTransaksi`

**Queries Detected:**
- Model: AdminPermissionService
- Model: TransTabungan
- Model: Nasabah

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function editTransaksi($id)
    {
        // Authorization: Only Admin Utama can edit manual transactions
        if (!app(\App\Services\AdminPermissionService::class)->canCrudTabunganTransaksi(Auth::user())) {
            abort(403, 'Anda tidak memiliki akses untuk fitur ini. Hanya Admin Utama yang dapat mengedit transaksi manual.');
        }

        $transaksi = TransTabungan::with(['nasabah.user'])->findOrFail($id);

        // Only allow edit if created manually (no pengajuan)
        if ($transaksi->id_pengajuan_setor || $transaksi->id_pengajuan_tarik) {
            return redirect()->back()
                ->with('error', 'Transaksi dari pengajuan tidak dapat diedit');
        }

        $nasabah = Nasabah::with('user')->get();

        return view('admin.tabungan.edit-transaksi', compact('transaksi', 'nasabah'));
    }
```
</details>

### Route: `admin/tabungan/transaksi/{id}` [PUT]
**Function:** `updateTransaksi`

**Queries Detected:**
- Model: AdminPermissionService
- Model: TransTabungan
- Model: ActivityLogService

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function updateTransaksi(Request $request, $id)
    {
        // Authorization: Only Admin Utama can update manual transactions
        if (!app(\App\Services\AdminPermissionService::class)->canCrudTabunganTransaksi(Auth::user())) {
            abort(403, 'Anda tidak memiliki akses untuk fitur ini. Hanya Admin Utama yang dapat mengupdate transaksi manual.');
        }

        $transaksi = TransTabungan::findOrFail($id);

        // Only allow update if created manually
        if ($transaksi->id_pengajuan_setor || $transaksi->id_pengajuan_tarik) {
            return redirect()->back()
                ->with('error', 'Transaksi dari pengajuan tidak dapat diupdate');
        }

        $request->validate([
            'nominal' => 'required|numeric|min:10000',
            'keterangan' => 'nullable|string|max:500',
            'tgl_transaksi' => 'required|date',
        ]);

        // Jika transaksi ini penarikan, validasi saldo (edit nominal tidak boleh melebihi saldo tersedia)
        if ($transaksi->jenis === 'penarikan') {
            $saldo = $this->getSaldoNasabah($transaksi->id_anggota);
            $saldoWithoutThis = $saldo + $transaksi->nominal; // kembalikan nominal lama dulu
            
            if ($saldoWithoutThis < $request->nominal) {
                return redirect()->back()
                    ->with('error', 'Saldo nasabah tidak mencukupi untuk nominal ini')
                    ->withInput();
            }
        }

        $transaksi->update([
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
            'tgl_transaksi' => $request->tgl_transaksi,
        ]);

        app(ActivityLogService::class)->logEditTransaksiManual($id, $transaksi->nasabah->user->nama ?? 'N/A');

        return redirect()->route('admin.tabungan.detail-transaksi', $id)
            ->with('success', 'Transaksi berhasil diupdate');
    }
```
</details>

### Route: `admin/tabungan/transaksi/{id}` [DELETE]
**Function:** `destroyTransaksi`

**Queries Detected:**
- Model: AdminPermissionService
- Model: TransTabungan
- Model: ActivityLogService

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function destroyTransaksi($id)
    {
        // Authorization: Only Admin Utama can delete manual transactions
        if (!app(\App\Services\AdminPermissionService::class)->canCrudTabunganTransaksi(Auth::user())) {
            abort(403, 'Anda tidak memiliki akses untuk fitur ini. Hanya Admin Utama yang dapat menghapus transaksi manual.');
        }

        $transaksi = TransTabungan::with('nasabah.user')->findOrFail($id);

        // Only allow delete if created manually
        if ($transaksi->id_pengajuan_setor || $transaksi->id_pengajuan_tarik) {
            return redirect()->back()
                ->with('error', 'Transaksi dari pengajuan tidak dapat dihapus');
        }

        $nasabahNama = $transaksi->nasabah->user->nama ?? 'N/A';
        $transaksi->delete();

        app(ActivityLogService::class)->logDeleteTransaksiManual($id, $nasabahNama);

        return redirect()->route('admin.tabungan.transaksi')
            ->with('success', 'Transaksi berhasil dihapus');
    }
```
</details>

### Route: `admin/tabungan/pengajuan-setor/{id}/edit` [POST]
**Function:** `editPengajuanSetor`

**Queries Detected:**
- Table: jns_via
- Table: jns_transaksi
- Model: AdminPermissionService
- Model: PengajuanTabungan
- Model: IdGenerator
- Model: TransTabungan

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function editPengajuanSetor(Request $request, $id)
    {
        // Authorization: Only Admin Utama can edit pengajuan
        if (!app(\App\Services\AdminPermissionService::class)->canCrudTabunganTransaksi(Auth::user())) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk fitur ini. Hanya Admin Utama yang dapat mengedit pengajuan.'
            ], 403);
        }

        $request->validate([
            'nominal' => 'nullable|numeric|min:10000',
            'keterangan_admin' => 'nullable|string|max:500',
            'status' => 'required|in:1,2,3',
        ]);

        try {
            DB::beginTransaction();
            
            $pengajuan = PengajuanTabungan::with(['buktiFoto', 'transTabungan'])->findOrFail($id);  // Removed janjiTemu
            
            $updateData = [
                'status' => $request->status,
            ];

            // Update nominal jika diisi
            if ($request->has('nominal') && $request->nominal) {
                $updateData['nominal'] = $request->nominal;
            }

            // Update keterangan_admin jika diisi
            if ($request->has('keterangan_admin') && $request->keterangan_admin) {
                $updateData['keterangan_admin'] = $request->keterangan_admin;
            }

            $pengajuan->update($updateData);

            // Jika status approved (2) dan belum ada transaksi, buat transaksi
            if ($request->status == '2' && $pengajuan->transTabungan->count() == 0) {
                // Get nominal from pengajuan
                $nominal = $pengajuan->nominal ?? 0;

                // Validate nominal
                if ($nominal == 0 || $nominal < 10000) {
                    DB::rollBack();
                    return redirect()->back()
                        ->with('error', 'Nominal tidak valid. Minimal Rp 10.000');
                }

                // V2 Logic: Master Data Driven
                $kodeVia = 'TF';  // Pengajuan always Transfer
                $kodeTrans = 'STR';

                // Get IDs from Master Tables
                $idVia = DB::table('jns_via')->where('kode', $kodeVia)->value('id');
                $idTrans = DB::table('jns_transaksi')->where('kode', $kodeTrans)->value('id');

                // Generate Complex String ID using correct method
                $idTransaksi = IdGenerator::generate('trans_tabungan', 'T', $kodeVia, $kodeTrans);

                Log::info('Creating transaksi tabungan from editPengajuanSetor', [
                    'id' => $idTransaksi,
                    'pengajuan_id' => $pengajuan->id,
                    'nominal' => $nominal,
                    'id_via' => $idVia,
                    'id_trans' => $idTrans,
                ]);

                TransTabungan::create([
                    'id' => $idTransaksi,
                    'id_pengajuan_setor' => $pengajuan->id,
                    'id_anggota' => $pengajuan->id_anggota,
                    'id_jns_via' => $idVia,
                    'id_jns_transaksi' => $idTrans,
                    'nominal' => abs((float) $nominal), // setoran selalu positif
                    'keterangan' => $request->keterangan_admin ?? $pengajuan->keterangan ?? 'Setoran tabungan disetujui',
                    'tgl_transaksi' => now(),
                ]);

                Log::info('Transaksi tabungan created successfully from edit', ['id' => $idTransaksi]);
            }

            DB::commit();

            return redirect()->route('admin.tabungan.pengajuan-setor')
                ->with('success', 'Pengajuan setoran berhasil diupdate dan transaksi telah dibuat');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error edit pengajuan setor', [
                'pengajuan_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
```
</details>

### Route: `admin/tabungan/pengajuan-setor/{id}` [DELETE]
**Function:** `deletePengajuanSetor`

**Queries Detected:**
- Model: AdminPermissionService
- Model: PengajuanTabungan
- Model: Storage

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function deletePengajuanSetor($id)
    {
        // Authorization: Only Admin Utama can delete pengajuan
        if (!app(\App\Services\AdminPermissionService::class)->canCrudTabunganTransaksi(Auth::user())) {
            abort(403, 'Anda tidak memiliki akses untuk fitur ini. Hanya Admin Utama yang dapat menghapus pengajuan.');
        }

        $pengajuan = PengajuanTabungan::findOrFail($id);
        
        // Hanya bisa delete jika status masih pending dan belum ada transaksi
        if ($pengajuan->status != '1') {
            return redirect()->back()
                ->with('error', 'Hanya pengajuan dengan status pending yang bisa dihapus');
        }

        if ($pengajuan->transTabungan->count() > 0) {
            return redirect()->back()
                ->with('error', 'Pengajuan yang sudah memiliki transaksi tidak bisa dihapus');
        }

        // Delete bukti foto files (model BuktiFoto pakai file_path)
        foreach ($pengajuan->buktiFoto as $bukti) {
            if ($bukti->file_path && Storage::disk('public')->exists($bukti->file_path)) {
                Storage::disk('public')->delete($bukti->file_path);
            }
        }

        $pengajuan->delete();

        return redirect()->route('admin.tabungan.pengajuan-setor')
            ->with('success', 'Pengajuan setoran berhasil dihapus');
    }
```
</details>

### Route: `admin/tabungan/transaksi/{id}` [GET|HEAD]
**Function:** `detailTransaksi`

**Queries Detected:**
- Model: TransTabungan

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function detailTransaksi($id)
    {
        $transaksi = TransTabungan::with(['nasabah.user', 'nasabah.dataKtp', 'pengajuanSetor.buktiFoto', 'pengajuanTarik'])
            ->findOrFail($id);

        return view('admin.tabungan.detail-transaksi', compact('transaksi'));
    }
```
</details>

### Route: `admin/tabungan/saldo-nasabah` [GET|HEAD]
**Function:** `saldoNasabah`

**Queries Detected:**
- Model: Nasabah
- Model: TransTabungan

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function saldoNasabah(Request $request)
    {
        $query = Nasabah::with('user');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($sq) use ($search) {
                    $sq->where('nama', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('id', 'like', "%{$search}%");
            });
        }

        $nasabah = $query->paginate(20)->withQueryString();

        // Calculate saldo for each nasabah
        $nasabah->getCollection()->transform(function($item) {
            $saldoData = $this->getSaldoNasabah($item->id, true); // true = return detail
            $item->saldo = $saldoData['saldo'];
            $item->saldo_hold = $saldoData['hold'];
            $item->total_setoran = TransTabungan::where('id_anggota', $item->id)
                ->whereHas('jnsTransaksi', function($q) { $q->where('kode', 'STR'); })
                ->sum('nominal') ?? 0;
            $item->total_penarikan = TransTabungan::where('id_anggota', $item->id)
                ->whereHas('jnsTransaksi', function($q) { $q->where('kode', 'PNR'); })
                ->sum('nominal') ?? 0;
            return $item;
        });

        return view('admin.tabungan.saldo-nasabah', compact('nasabah'));
    }
```
</details>

### Route: `admin/tabungan/janji-temu/{id}` [GET|HEAD]
**Function:** `detailJanjiTemu`

**Queries Detected:**
- Model: JanjiTemuTabungan
- Model: PettyCashSaldo

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function detailJanjiTemu($id)
    {
        $janjiTemu = JanjiTemuTabungan::with([
            'nasabah.user', 'nasabah.dataKtp', 'nasabah.dataRek', 'lokasi', 'buktiFoto', 'transTabungan'
        ])->findOrFail($id);

        $adminSaldo = PettyCashSaldo::getSaldoCash(Auth::id());

        return view('admin.tabungan.detail-janji-temu', compact('janjiTemu', 'adminSaldo'));
    }
```
</details>

### Route: `admin/tabungan/pengajuan-setor/{id}/approve` [POST]
**Function:** `approveSetor`

**Queries Detected:**
- Table: jns_via
- Table: jns_transaksi
- Model: PengajuanTabungan
- Model: IdGenerator
- Model: PettyCashTransaksiNasabah
- Model: PettyCashConstants
- Model: TransTabungan
- Model: PettyCashSaldo
- Model: User
- Model: PettyCashOwnerTransaksi
- Model: ActivityLogService
- Model: NasabahNotification

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function approveSetor(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            
            $pengajuan = PengajuanTabungan::with(['buktiFoto', 'transTabungan'])->findOrFail($id);  // Removed janjiTemu
            
            // Cek apakah sudah diproses
            if ($pengajuan->status != '1') {
                DB::rollBack();
                return redirect()->route('admin.tabungan.pengajuan-setor')
                    ->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
            }

            // Get nominal from pengajuan
            $nominal = $pengajuan->nominal ?? 0;

            // Validate nominal
            if ($nominal == 0 || $nominal < 10000) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Nominal tidak valid. Minimal Rp 10.000');
            }

            // Create transaksi tabungan jika belum ada
            // Pastikan tidak ada duplikasi transaksi
            if ($pengajuan->transTabungan->count() == 0) {
                // V2 Logic: Master Data Driven
                $kodeVia = 'TF';  // Pengajuan always Transfer
                $kodeTrans = 'STR';

                // Get IDs from Master Tables
                $idVia = DB::table('jns_via')->where('kode', $kodeVia)->value('id');
                $idTrans = DB::table('jns_transaksi')->where('kode', $kodeTrans)->value('id');

                // Generate Complex String ID using correct method
                // Format: DDMMYYYYSEQFTVTRANS (e.g., 040220260001TTFSTR)
                $idTransaksi = IdGenerator::generate('trans_tabungan', 'T', $kodeVia, $kodeTrans);

                Log::info('Creating transaksi tabungan', [
                    'id' => $idTransaksi,
                    'pengajuan_id' => $pengajuan->id,
                    'nominal' => $nominal,
                    'id_via' => $idVia,
                    'id_trans' => $idTrans,
                ]);

            // 🛡️ Cek apakah sudah ada transaksi petty cash (hindari duplikasi)
            $existingPc = \App\Models\PettyCashTransaksiNasabah::where('ref_table', \App\Services\PettyCashConstants::REF_TABUNGAN_STR)
                ->where('ref_id', $pengajuan->id)
                ->first();

            if ($existingPc) {
                DB::rollBack();
                return redirect()->route('admin.tabungan.pengajuan-setor')
                    ->with('error', 'Transaksi setoran ini sudah tercatat di Petty Cash.');
            }

            $pettyId = ($request->metode_bayar === 'transfer_admin' || $request->metode_bayar === 'cash') ? 
                        IdGenerator::generate('petty_cash_transaksi_nasabah', 'P', 'CS', 'STR') : null;

            TransTabungan::create([
                'id'                 => $idTransaksi,
                'id_pengajuan_setor' => $pengajuan->id,
                'id_anggota'         => $pengajuan->id_anggota,
                'id_jns_via'         => $idVia,
                'id_jns_transaksi'   => $idTrans,
                'nominal'            => abs((float) $nominal),
                'keterangan'         => $pengajuan->keterangan ?? 'Setoran tabungan disetujui',
                'tgl_transaksi'      => now(),
                'admin_pengelola_id' => Auth::id(),
                'is_petty_cash'      => ($request->metode_bayar !== 'transfer_koperasi') ? 1 : 0,
                'petty_cash_ref'     => $pettyId,
                'metode_bayar'       => $request->metode_bayar ?? 'transfer_koperasi',
            ]);

            // 🔥 INTEGRASI PETTY CASH: Jika transfer ke Admin atau Cash
            if ($pettyId) {
                PettyCashTransaksiNasabah::create([
                    'id'               => $pettyId,
                    'admin_id'         => Auth::id(),
                    'nasabah_id'       => $pengajuan->id_anggota,
                    'id_jns_transaksi' => $idTrans,
                    'id_jns_via'       => ($request->metode_bayar === 'cash') ? 
                                          PettyCashConstants::VIA_CS : 
                                          PettyCashConstants::VIA_TF,
                    'id_jns_fitur'     => PettyCashConstants::FITUR_TABUNGAN, // Simpanan Umum
                    'nominal'          => $nominal,
                    'status'           => 'approved',
                    'keterangan'       => 'Otomatis dari Pengajuan #' . $pengajuan->id,
                    'ref_table'        => 'trans_tabungan',
                    'ref_id'           => $idTransaksi,
                    'tgl_transaksi'    => now(),
                ]);

                $pettyType = ($request->metode_bayar === 'cash') ? 'cash' : 'transfer';
                PettyCashSaldo::updateOrCreateSaldo(
                    Auth::id(), 
                    'admin', 
                    $nominal, 
                    $pettyId, 
                    'Setoran dari Pengajuan #' . $pengajuan->id,
                    'petty_cash_transaksi_nasabah',
                    $pettyType,
                    'tabungan' // 🔥 Fix: Masuk ke Saldo Tabungan (Clearing)
                );
            }

            Log::info('Transaksi tabungan created successfully', ['id' => $idTransaksi]);

            // 🔥 INTEGRASI OWNER LEDGER: Jika transfer langsung ke Koperasi (Rek Utama Owner)
            if ($request->metode_bayar === 'transfer_koperasi') {
                $owner = User::where('role', 'admin_utama')->first();
                if ($owner) {
                    PettyCashOwnerTransaksi::create([
                        'id'              => IdGenerator::generate('petty_cash_owner_transaksi', 'PCOW', 'OW', 'TR'),
                        'user_id'         => $owner->id,
                        'tipe'            => 'terima_setoran',
                        'sumber'          => \App\Services\PettyCashConstants::SUMBER_TABUNGAN,
                        'nominal_cash'    => 0,
                        'nominal_tf'      => $nominal,
                        'keterangan'      => "Setoran Tabungan Nasabah: " . ($pengajuan->nasabah->user->nama ?? '-') . " (#{$pengajuan->id})",
                        'bukti_foto_tf'   => $pengajuan->buktiFoto->first()?->file_path ?? null,
                        'ref_id'          => $pengajuan->id,
                        'ref_table'       => 'tbl_pengajuan_tabungan',
                    ]);

                    // Update Saldo Owner (Transfer)
                    PettyCashSaldo::buatMutasi(
                        $owner->id, 'owner', $nominal,
                        "Setoran Tabungan Nasabah (#{$pengajuan->id})",
                        $pengajuan->id, 'tbl_pengajuan_tabungan', 'transfer',
                        \App\Services\PettyCashConstants::SUMBER_TABUNGAN
                    );
                }
            }
        }

        // Update status to approved (status '2') + simpan keterangan_admin dan siapa yang approve
        $updateData = [
            'status' => '2',
            'approved_by_user_id' => Auth::id(),
            'metode_bayar' => $request->metode_bayar ?? 'transfer_koperasi',
        ];
        if ($request->filled('keterangan_admin')) {
            $updateData['keterangan_admin'] = $request->keterangan_admin;
        }
        $pengajuan->update($updateData);

            DB::commit();

            app(ActivityLogService::class)->logApproveSetoran($pengajuan->id, (float) $pengajuan->nominal, $pengajuan->nasabah->user->nama ?? 'N/A');

            NasabahNotification::notify(
                $pengajuan->id_anggota,
                'tabungan_setor',
                'Pengajuan setoran disetujui',
                'Setoran Anda sebesar Rp ' . number_format($pengajuan->nominal ?? 0, 0, ',', '.') . ' telah disetujui.',
                route('nasabah.tabungan.detail-pengajuan-setor', $pengajuan->id),
                (string) $pengajuan->id,
                'pengajuan_tabungan'
            );

            $transaksi = $pengajuan->transTabungan()->first();
            if ($transaksi) {
                return redirect()->route('admin.tabungan.detail-transaksi', $transaksi->id)
                    ->with('success', 'Pengajuan setoran berhasil disetujui dan transaksi telah dibuat. Silakan cetak struk di bawah.')
                    ->with('download_struk', true);
            }
            return redirect()->route('admin.tabungan.pengajuan-setor')
                ->with('success', 'Pengajuan setoran berhasil disetujui dan transaksi telah dibuat');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error approve setor', [
                'pengajuan_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
```
</details>

### Route: `admin/tabungan/pengajuan-setor/{id}/reject` [POST]
**Function:** `rejectSetor`

**Queries Detected:**
- Model: PengajuanTabungan
- Model: ActivityLogService
- Model: NasabahNotification

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function rejectSetor(Request $request, $id)
    {
        $request->validate([
            'keterangan_admin' => 'required|string'
        ]);

        $pengajuan = PengajuanTabungan::with('nasabah.user')->findOrFail($id);

        if ($pengajuan->status != '1') {
            return redirect()->route('admin.tabungan.pengajuan-setor')
                ->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $pengajuan->update([
            'status' => '3',
            'keterangan_admin' => $request->keterangan_admin
        ]);

        app(ActivityLogService::class)->logRejectSetoran($pengajuan->id, (float) $pengajuan->nominal, $pengajuan->nasabah->user->nama ?? 'N/A', $request->keterangan_admin);

        NasabahNotification::notify(
            $pengajuan->id_anggota,
            'tabungan_setor',
            'Pengajuan setoran ditolak',
            'Pengajuan setoran Anda ditolak. ' . ($request->keterangan_admin ?? ''),
            route('nasabah.tabungan.detail-pengajuan-setor', $pengajuan->id),
            (string) $pengajuan->id,
            'pengajuan_tabungan'
        );

        return redirect()->route('admin.tabungan.pengajuan-setor')
            ->with('success', 'Pengajuan setoran ditolak');
    }
```
</details>

### Route: `admin/tabungan/pengajuan-tarik/{id}/approve` [POST]
**Function:** `approveTarik`

**Queries Detected:**
- Table: jns_via
- Table: jns_transaksi
- Model: PengajuanPenarikanTabungan
- Model: BiayaTransfer
- Model: PettyCashTransaksiNasabah
- Model: PettyCashConstants
- Model: IdGenerator
- Model: PettyCashSaldo
- Model: TransTabungan
- Model: ActivityLogService
- Model: NasabahNotification

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function approveTarik(Request $request, $id)
    {
        $pengajuan = PengajuanPenarikanTabungan::findOrFail($id);

        if ($pengajuan->status != '1') {
            return redirect()->route('admin.tabungan.pengajuan-tarik')
                ->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        // Penarikan tunai diproses via Janji Temu, bukan di sini
        if ($pengajuan->metode_transfer !== 'transfer') {
            return redirect()->route('admin.janji-temu.index')
                ->with('info', 'Penarikan tunai diproses melalui menu Janji Temu.');
        }
        
        // Validate for transfer
        if ($pengajuan->metode_transfer == 'transfer') {
            $request->validate([
                'foto_bukti_tf_admin' => 'required|image|max:5120',
                'bank_pengirim' => 'required|string|max:50',
            ]);
        }
        
        // Biaya transfer (ditanggung nasabah)
        $biayaTransfer = 0;
        if ($pengajuan->metode_transfer == 'transfer') {
            $bt = BiayaTransfer::where('is_active', true)
                ->where('bank_pengirim', $request->bank_pengirim)
                ->where('bank_penerima', $pengajuan->nama_bank)
                ->first();
            $biayaTransfer = $bt ? (float) $bt->biaya_admin : 0;
        }

        // Check saldo: harus mencukupi nominal + biaya transfer
        $saldo = $this->getSaldoNasabah($pengajuan->id_anggota);
        $totalDipotong = $pengajuan->nominal + $biayaTransfer;

        if ($saldo < $totalDipotong) {
            return redirect()->back()
                ->with('error', 'Saldo nasabah tidak mencukupi (nominal + biaya transfer). Total yang dipotong: Rp ' . number_format($totalDipotong, 0, ',', '.'));
        }

        DB::beginTransaction();
        try {
            // Upload foto bukti TF admin (jika transfer)
            $fotoBuktiPath = null;
            if ($pengajuan->metode_transfer == 'transfer' && $request->hasFile('foto_bukti_tf_admin')) {
                $fotoBuktiPath = $request->file('foto_bukti_tf_admin')->store('bukti_tf_admin', 'public');
            }

            // Update pengajuan dengan foto dan biaya transfer
            $pengajuan->update([
                'status' => '2',
                'foto_bukti_tf_admin' => $fotoBuktiPath,
                'biaya_transfer' => $biayaTransfer,
            ]);

            // V2 Logic: Master Data Driven
            $kodeVia = ($pengajuan->metode_transfer == 'transfer') ? 'TF' : 'TN';
            $kodeTrans = 'PNR';

            // Get IDs
            $idVia = DB::table('jns_via')->where('kode', $kodeVia)->value('id');
            $idTrans = DB::table('jns_transaksi')->where('kode', $kodeTrans)->value('id');
            
            // 🛡️ Cek apakah sudah ada transaksi petty cash (hindari duplikasi)
            $existingPc = \App\Models\PettyCashTransaksiNasabah::where('ref_table', \App\Services\PettyCashConstants::REF_TABUNGAN_PNR)
                ->where('ref_id', $pengajuan->id)
                ->first();
            
            if ($existingPc) {
                throw new \Exception('Transaksi penarikan ini sudah tercatat di Petty Cash.');
            }

            // Generate ID using correct method
            $idTransaksi = IdGenerator::generate('trans_tabungan', 'T', $kodeVia, $kodeTrans);

            // 🔥 INTEGRASI PETTY CASH: Validasi dan Pemotongan Saldo Transfer Admin (MODAL AWAL)
            // Untuk penarikan via transfer, kita kurangi saldo transfer admin sumber MODAL AWAL
            if ($pengajuan->metode_transfer == 'transfer') {
                if (!PettyCashSaldo::validatePenarikan(Auth::id(), $pengajuan->nominal, 'transfer', 'other')) {
                    throw new \Exception('Saldo Transfer MODAL AWAL Anda tidak mencukupi untuk melakukan penarikan ini.');
                }

                PettyCashSaldo::updateSaldo(
                    Auth::id(), 
                    'transfer', 
                    -(float)$pengajuan->nominal, 
                    $pengajuan->id, 
                    'Penarikan Tabungan (Transfer): ' . ($pengajuan->nasabah->user->nama ?? 'Nasabah'),
                    'tbl_pengajuan_penarikan_tabungan',
                    'other' // 🔥 Tetap Other karena ini pengeluaran (Modal Awal)
                );
            }

            // Create transaksi penarikan: nominal = total yang didebet dari saldo nasabah (nominal + biaya transfer)
            TransTabungan::create([
                'id' => $idTransaksi,
                'id_pengajuan_tarik' => $pengajuan->id,
                'id_anggota' => $pengajuan->id_anggota,
                'id_jns_via' => $idVia,
                'id_jns_transaksi' => $idTrans,
                'nominal' => abs($totalDipotong), // Pastikan selalu positif
                'keterangan' => $pengajuan->keterangan ?? 'Penarikan tabungan transfer',
                'tgl_transaksi' => now(),
                'admin_pengelola_id' => Auth::id(),
                'is_petty_cash' => 1,
                'petty_cash_ref' => $pengajuan->id,
                'metode_bayar' => 'transfer_admin',
            ]);

            DB::commit();

            app(ActivityLogService::class)->logApproveTarik(
                $pengajuan->id,
                (float) $pengajuan->nominal,
                $pengajuan->nasabah->user->nama ?? 'N/A',
                (float) ($pengajuan->biaya_transfer ?? 0)
            );

            NasabahNotification::notify(
                $pengajuan->id_anggota,
                'tabungan_tarik',
                'Pengajuan penarikan disetujui',
                'Penarikan Anda sebesar Rp ' . number_format($pengajuan->nominal ?? 0, 0, ',', '.') . ' telah disetujui. Dana telah ditransfer ke rekening Anda.',
                route('nasabah.tabungan.detail-pengajuan-tarik', $pengajuan->id),
                (string) $pengajuan->id,
                'pengajuan_penarikan_tabungan'
            );

            return redirect()->route('admin.tabungan.pengajuan-tarik')
                ->with('success', 'Pengajuan penarikan berhasil disetujui, saldo petty cash telah diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error approve penarikan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memproses penarikan: ' . $e->getMessage());
        }
    }
```
</details>

### Route: `admin/tabungan/pengajuan-tarik/{id}/reject` [POST]
**Function:** `rejectTarik`

**Queries Detected:**
- Model: PengajuanPenarikanTabungan
- Model: ActivityLogService
- Model: NasabahNotification

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function rejectTarik(Request $request, $id)
    {
        $request->validate([
            'keterangan_admin' => 'required|string'
        ]);

        $pengajuan = PengajuanPenarikanTabungan::with('nasabah.user')->findOrFail($id);

        if ($pengajuan->status != '1') {
            return redirect()->route('admin.tabungan.pengajuan-tarik')
                ->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        // Penarikan tunai diproses via Janji Temu
        if ($pengajuan->metode_transfer !== 'transfer') {
            return redirect()->route('admin.janji-temu.index')
                ->with('info', 'Penarikan tunai diproses melalui menu Janji Temu.');
        }

        $pengajuan->update([
            'status' => '3',
            'keterangan_admin' => $request->keterangan_admin
        ]);

        app(ActivityLogService::class)->logRejectTarik($pengajuan->id, (float) $pengajuan->nominal, $pengajuan->nasabah->user->nama ?? 'N/A', $request->keterangan_admin);

        NasabahNotification::notify(
            $pengajuan->id_anggota,
            'tabungan_tarik',
            'Pengajuan penarikan ditolak',
            'Pengajuan penarikan Anda ditolak. ' . ($request->keterangan_admin ?? ''),
            route('nasabah.tabungan.detail-pengajuan-tarik', $pengajuan->id),
            (string) $pengajuan->id,
            'pengajuan_penarikan_tabungan'
        );

        return redirect()->route('admin.tabungan.pengajuan-tarik')
            ->with('success', 'Pengajuan penarikan ditolak');
    }
```
</details>

### Route: `admin/tabungan/janji-temu/{id}/create-trans` [POST]
**Function:** `createTransFromJanjiTemu`

**Queries Detected:**
- Table: jns_via
- Table: jns_transaksi
- Model: JanjiTemuTabungan
- Model: PettyCashSaldo
- Model: IdGenerator
- Model: BuktiFoto
- Model: PengajuanPenarikanTabungan
- Model: PettyCashTransaksiNasabah
- Model: PettyCashConstants
- Model: TransTabungan
- Model: ActivityLogService

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function createTransFromJanjiTemu(Request $request, $id)
    {
        $request->validate([
            'nominal'          => 'required|string',
            'keterangan_admin' => 'nullable|string|max:500',
            'foto_penerimaan.*'=> 'nullable|image|max:5120',  // Multiple files
        ]);

        // Parse nominal from formatted currency string (e.g., "Rp 10.000.000")
        $nominalStr = preg_replace('/[^0-9]/', '', $request->nominal);
        $nominal = (float) $nominalStr;

        if ($nominal < 10000) {
            return redirect()->back()
                ->with('error', 'Nominal minimal Rp 10.000')
                ->withInput();
        }

        $janjiTemu = JanjiTemuTabungan::with(['nasabah'])->findOrFail($id);

        // Check if already processed (status = 2)
        if ($janjiTemu->status == '2') {
            return redirect()->back()
                ->with('error', 'Janji temu ini sudah diproses sebelumnya');
        }

        $idAnggota    = $janjiTemu->id_nasabah;
        $isWithdrawal = isset($janjiTemu->jenis) && $janjiTemu->jenis === 'penarikan';

        // 🔥 Tugas 4: Validasi saldo SEBELUM ada perubahan data (fail-fast)
        if ($isWithdrawal) {
            // 1. Cek Saldo Admin (Petty Cash MODAL AWAL)
            $saldoCash = PettyCashSaldo::getSaldo(Auth::id(), 'admin', 'cash', 'other');
            if ($saldoCash < $nominal) {
                return redirect()->back()
                    ->with('error', sprintf(
                        'Saldo CASH MODAL AWAL Anda tidak mencukupi. Dibutuhkan: Rp %s | Tersedia: Rp %s',
                        number_format($nominal, 0, ',', '.'),
                        number_format($saldoCash, 0, ',', '.')
                    ))
                    ->withInput();
            }

            // 2. Cek Saldo Nasabah
            $saldoNasabah = $this->getSaldoNasabah($idAnggota);
            if ($saldoNasabah < $nominal) {
                return redirect()->back()
                    ->with('error', sprintf(
                        'Saldo NASABAH tidak mencukupi. Dibutuhkan: Rp %s | Tersedia: Rp %s',
                        number_format($nominal, 0, ',', '.'),
                        number_format($saldoNasabah, 0, ',', '.')
                    ))
                    ->withInput();
            }
        }

        try {
            DB::beginTransaction();

            // Handle foto penerimaan menggunakan tbl_bukti_foto universal
            // id wajib diisi: tbl_bukti_foto pakai id string (bukan auto-increment)
            if ($request->hasFile('foto_penerimaan')) {
                foreach ($request->file('foto_penerimaan') as $file) {
                    $fotoPenerimaan = $file->store('bukti_tabungan', 'public');
                    $idBuktiFoto = IdGenerator::generate('tbl_bukti_foto', 'T', 'CS', 'JNJT');
                    BuktiFoto::create([
                        'id'          => $idBuktiFoto,
                        'owner_id'    => $janjiTemu->id,
                        'owner_fitur' => 'T',    // Tabungan
                        'owner_trans' => 'JNJT', // Janji Temu
                        'file_path'   => $fotoPenerimaan,
                        'keterangan'  => 'Bukti penerimaan janji temu',
                    ]);
                }
            }

            // Update janji temu: status selesai + nominal disamakan dengan yang dipakai di transaksi
            // (agar semua halaman—admin detail, nasabah detail, list—tampil nominal yang sama)
            $janjiTemu->update([
                'status'           => '2',  // Selesai
                'nominal'          => $nominal,
                'keterangan_admin' => $request->keterangan_admin,
            ]);

            // Create transaksi tabungan
            $kodeVia  = 'CS'; // Cash (janji temu)
            $kodeTrans = $isWithdrawal ? 'PNR' : 'STR';

            $idVia       = DB::table('jns_via')->where('kode', $kodeVia)->value('id');
            $idTrans     = DB::table('jns_transaksi')->where('kode', $kodeTrans)->value('id');
            $idTransaksi = IdGenerator::generate('trans_tabungan', 'T', $kodeVia, $kodeTrans);

            // Find related pengajuan tarik if this is a withdrawal
            $idPengajuanTarik = null;
            if ($isWithdrawal) {
                $pengajuanTarik = PengajuanPenarikanTabungan::where('id_anggota', $idAnggota)
                    ->where('nominal', $nominal)
                    ->where('status', '1') // Pending
                    ->latest()
                    ->first();

                if ($pengajuanTarik) {
                    $idPengajuanTarik = $pengajuanTarik->id;
                    $pengajuanTarik->update(['status' => '2']); // Approve
                }
            }

            // 🔥 Tugas 4: Pemotongan Saldo Petty Cash Admin (CASH MODAL AWAL) — sudah tervalidasi di atas
            if ($isWithdrawal) {
                PettyCashSaldo::updateSaldo(
                    Auth::id(),
                    'cash',
                    -$nominal,
                    $janjiTemu->id,
                    'Penarikan Tunai: ' . ($janjiTemu->nasabah->user->nama ?? 'Nasabah'),
                    'tbl_janji_temu_tabungan',
                    'other'
                );
            }

            // 🛡️ Cek apakah sudah ada transaksi petty cash (hindari duplikasi)
            $existingPc = \App\Models\PettyCashTransaksiNasabah::where('ref_table', \App\Services\PettyCashConstants::REF_JANJI_TEMU)
                ->where('ref_id', $janjiTemu->id)
                ->first();

            if ($existingPc) {
                throw new \Exception('Transaksi janji temu ini sudah tercatat di Petty Cash.');
            }

            $pettyId = !$isWithdrawal
                ? IdGenerator::generate('petty_cash_transaksi_nasabah', 'P', 'CS', 'STR')
                : null;

            TransTabungan::create([
                'id'                     => $idTransaksi,
                'id_pengajuan_setor'     => null,
                'id_janji_temu_tabungan' => $janjiTemu->id,
                'id_pengajuan_tarik'     => $idPengajuanTarik,
                'id_anggota'             => $idAnggota,
                'id_jns_via'             => $idVia,
                'id_jns_transaksi'       => $idTrans,
                'nominal'                => (float) $nominal,
                'keterangan'             => ($isWithdrawal ? '[PENARIKAN TUNAI] ' : '[SETORAN TUNAI] ') . $janjiTemu->keterangan,
                'tgl_transaksi'          => now(),
                'admin_pengelola_id'     => Auth::id(),
                'is_petty_cash'          => 1,
                'petty_cash_ref'         => $pettyId ?: $janjiTemu->id,
                'metode_bayar'           => 'cash',
            ]);

            // 🔥 INTEGRASI PETTY CASH: Untuk Setoran Cash via Janji Temu
            if ($pettyId) {
                PettyCashTransaksiNasabah::create([
                    'id'               => $pettyId,
                    'admin_id'         => Auth::id(),
                    'nasabah_id'       => $idAnggota,
                    'id_jns_transaksi' => $idTrans,
                    'id_jns_via'       => $idVia,
                    'id_jns_fitur'     => PettyCashConstants::FITUR_TABUNGAN,
                    'nominal'          => $nominal,
                    'status'           => 'approved',
                    'keterangan'       => 'Otomatis dari Janji Temu #' . $janjiTemu->id,
                    'ref_table'        => 'trans_tabungan',
                    'ref_id'           => $idTransaksi,
                    'tgl_transaksi'    => now(),
                ]);

                PettyCashSaldo::updateOrCreateSaldo(
                    Auth::id(),
                    'admin',
                    $nominal,
                    $pettyId,
                    'Setoran dari Janji Temu #' . $janjiTemu->id,
                    'petty_cash_transaksi_nasabah',
                    'cash',
                    \App\Services\PettyCashConstants::SUMBER_TABUNGAN
                );
            }

            app(ActivityLogService::class)->logProsesJanjiTemuTabungan(
                $idTransaksi, $nominal,
                $janjiTemu->nasabah->user->nama ?? 'N/A',
                $isWithdrawal ? 'penarikan' : 'setoran'
            );

            DB::commit();

            return redirect()->route('admin.janji-temu.index')
                ->with('success', 'Transaksi tabungan berhasil dibuat dari janji temu!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('createTransFromJanjiTemu error', [
                'janji_temu_id' => $id,
                'error'         => $e->getMessage(),
                'trace'         => $e->getTraceAsString(),
            ]);
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
```
</details>

## Controller: `App\Http\Controllers\Admin\StrukController`

**Models Imported:**
- `App\Models\TransTabungan`
- `App\Models\PinjamanH`
- `App\Models\TempoPinjamanB`
- `App\Models\TempoPinjamanM`
- `App\Models\PengajuanPembayaranPinjaman`

### Route: `admin/tabungan/transaksi/{id}/struk` [GET|HEAD]
**Function:** `transaksiTabungan`

**Queries Detected:**
- Model: TransTabungan
- Model: Pdf

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function transaksiTabungan(string $id)
    {
        $transaksi = TransTabungan::with([
            'nasabah.user',
            'nasabah.dataKtp',
            'jnsTransaksi',
            'jnsVia',
            'pengajuanSetor.approvedBy',
            'pengajuanTarik'
        ])->findOrFail($id);

        $logoPath = public_path('images/logo-koperasi-majakara.png');
        $hasLogo = is_file($logoPath);

        $pdf = Pdf::loadView('struk.tabungan', compact('transaksi', 'hasLogo', 'logoPath'));
        $pdf->setPaper([0, 0, 226.77, 841.89], 'portrait'); // 60mm width (thermal-ish), height auto
        $filename = 'Struk-Tabungan-' . $transaksi->id . '.pdf';
        return $pdf->download($filename);
    }
```
</details>

### Route: `admin/tabungan/transaksi/{id}/print-struk` [GET|HEAD]
**Function:** `printTransaksiTabunganHtml`

**Queries Detected:**
- Model: TransTabungan

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function printTransaksiTabunganHtml(string $id)
    {
        $transaksi = TransTabungan::with([
            'nasabah.user',
            'nasabah.dataKtp',
            'jnsTransaksi',
            'jnsVia',
            'pengajuanSetor.approvedBy',
            'pengajuanTarik',
            'adminPengelola'
        ])->findOrFail($id);

        $logoPath = public_path('images/logo-koperasi-majakara.png');
        $hasLogo = is_file($logoPath);

        return view('struk.tabungan-html', compact('transaksi', 'hasLogo', 'logoPath'));
    }
```
</details>

### Route: `admin/pinjaman/pembayaran/{id}/struk` [GET|HEAD]
**Function:** `pembayaranPinjaman`

**Queries Detected:**
- Model: PengajuanPembayaranPinjaman
- Model: TempoPinjamanB
- Model: TempoPinjamanM
- Model: Pdf

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function pembayaranPinjaman(string $id)
    {
        $pengajuan = PengajuanPembayaranPinjaman::with([
            'nasabah.user',
            'nasabah.dataKtp',
            'pinjaman.pengajuan'
        ])->findOrFail($id);

        $angsuran = null;
        if ($pengajuan->tempo_id && $pengajuan->jenis_tempo) {
            if ($pengajuan->jenis_tempo === 'bulanan') {
                $angsuran = TempoPinjamanB::with('pinjaman')->find($pengajuan->tempo_id);
            } else {
                $angsuran = TempoPinjamanM::with('pinjaman')->find($pengajuan->tempo_id);
            }
        }

        $pdf = Pdf::loadView('struk.pembayaran-pinjaman', compact('pengajuan', 'angsuran'));
        $pdf->setPaper([0, 0, 226.77, 841.89], 'portrait');
        $filename = 'Struk-Pembayaran-' . $pengajuan->id . '.pdf';
        return $pdf->download($filename);
    }
```
</details>

### Route: `admin/pinjaman/pinjaman-aktif/{id}/struk-pencairan` [GET|HEAD]
**Function:** `pencairanPinjaman`

**Queries Detected:**
- Model: PinjamanH
- Model: Pdf

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function pencairanPinjaman(string $id)
    {
        $pinjaman = PinjamanH::with([
            'nasabah.user',
            'nasabah.dataKtp',
            'pengajuan'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('struk.pencairan-pinjaman', compact('pinjaman'));
        $pdf->setPaper([0, 0, 226.77, 841.89], 'portrait');
        $filename = 'Struk-Pencairan-' . $pinjaman->id . '.pdf';
        return $pdf->download($filename);
    }
```
</details>

### Route: `admin/pinjaman/angsuran/{id}/struk` [GET|HEAD]
**Function:** `angsuran`

**Queries Detected:**
- Model: TempoPinjamanB
- Model: TempoPinjamanM
- Model: Pdf

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function angsuran(Request $request, string $id)
    {
        $jenis = $request->get('jenis', 'bulanan');
        if ($jenis === 'bulanan') {
            $angsuran = TempoPinjamanB::with(['pinjaman.nasabah.user', 'pinjaman.pengajuan'])->findOrFail($id);
        } else {
            $angsuran = TempoPinjamanM::with(['pinjaman.nasabah.user', 'pinjaman.pengajuan'])->findOrFail($id);
        }

        $pdf = Pdf::loadView('struk.angsuran', compact('angsuran', 'jenis'));
        $pdf->setPaper([0, 0, 226.77, 841.89], 'portrait');
        $filename = 'Struk-Angsuran-' . $id . '.pdf';
        return $pdf->download($filename);
    }
```
</details>

## Controller: `App\Http\Controllers\Admin\PinjamanController`

**Models Imported:**
- `App\Models\PengajuanPinjaman`
- `App\Models\PinjamanH`
- `App\Models\TempoPinjamanB`
- `App\Models\TempoPinjamanM`
- `App\Models\Nasabah`
- `App\Models\PengajuanPembayaranPinjaman`
- `App\Models\JanjiTemuPembayaranPinjaman`
- `App\Models\PettyCashSaldo`
- `App\Models\PettyCashTransaksiNasabah`
- `App\Models\JanjiTemuPinjaman`
- `App\Models\BuktiFoto`
- `App\Models\JnsLokasiPerusahaan`
- `App\Models\MasterBungaPinjaman`
- `App\Models\MasterDendaPinjaman`
- `App\Models\NasabahNotification`
- `App\Models\User`
- `App\Models\PettyCashOwnerTransaksi`

### Route: `admin/pinjaman` [GET|HEAD]
**Function:** `index`

**Queries Detected:**
- Model: PengajuanPinjaman
- Model: PinjamanH
- Model: PengajuanPembayaranPinjaman

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function index()
    {
        // Statistik pinjaman
        $stats = [
            'total_pengajuan_pending' => PengajuanPinjaman::whereDoesntHave('pinjaman')->count(),
            'total_pinjaman_aktif' => PinjamanH::where('lunas', 'belum')->count(),
            'total_pinjaman_lunas' => PinjamanH::where('lunas', 'lunas')->count(),
            'total_pinjaman_hari_ini' => PinjamanH::whereDate('created_at', today())->count(),
            'total_nominal_pinjaman_aktif' => PinjamanH::where('lunas', 'belum')->sum('jumlah_pinjam') ?? 0,
            'total_angsuran_telat' => $this->getTotalAngsuranTelat(),
            'total_pembayaran_pending' => PengajuanPembayaranPinjaman::where('status', '1')->count(),
        ];

        // Pengajuan terbaru (pending)
        // Hanya pengajuan via transfer (tunai/janji temu muncul di halaman Janji Temu Universal)
        $pengajuan_terbaru = PengajuanPinjaman::whereDoesntHave('pinjaman')
            ->where('jenis_pencairan', 'transfer')
            ->with('nasabah.user')
            ->latest()
            ->take(5)
            ->get();

        // Pinjaman aktif terbaru
        $pinjaman_aktif_terbaru = PinjamanH::where('lunas', 'belum')
            // ->whereIn('status', ['pencairan', 'telaksana']) // Removed status check
            ->with('nasabah.user')
            ->latest()
            ->take(5)
            ->get();

        // Angsuran jatuh tempo hari ini
        $angsuran_jatuh_tempo = $this->getAngsuranJatuhTempo();

        // Pembayaran terbaru (pengajuan pembayaran yang baru ditambahkan nasabah)
        $pembayaran_terbaru = PengajuanPembayaranPinjaman::with(['nasabah.user', 'pinjaman'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.pinjaman.index', compact(
            'stats',
            'pengajuan_terbaru',
            'pinjaman_aktif_terbaru',
            'angsuran_jatuh_tempo',
            'pembayaran_terbaru'
        ));
    }
```
</details>

### Route: `admin/pinjaman/pengajuan` [GET|HEAD]
**Function:** `pengajuan`

**Queries Detected:**
- Model: PengajuanPinjaman

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function pengajuan(Request $request)
    {
        $query = PengajuanPinjaman::with('nasabah.user')
            ->latest();

        // Filter by status: kosong / Semua Status = tampilkan semua pengajuan
        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $query->whereDoesntHave('pinjaman');
            } elseif ($request->status === 'approved') {
                $query->whereHas('pinjaman');
            }
        }

        // Filter by jenis
        if ($request->has('jenis') && $request->jenis !== '') {
            $query->where('jenis', $request->jenis);
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->whereHas('nasabah.user', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $pengajuan = $query->paginate(15);

        return view('admin.pinjaman.pengajuan', compact('pengajuan'));
    }
```
</details>

### Route: `admin/pinjaman/pengajuan/{id}` [GET|HEAD]
**Function:** `detailPengajuan`

**Queries Detected:**
- Model: PengajuanPinjaman
- Model: BankAccessService
- Model: MasterBungaPinjaman
- Model: MasterDendaPinjaman
- Model: PettyCashSaldo
- Model: BiayaTransfer

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function detailPengajuan($id)
    {
        $pengajuan = PengajuanPinjaman::with(['nasabah.user', 'nasabah.dataKtp', 'nasabah.dataRek', 'nasabah.pekerjaan', 'pinjaman'])
            ->findOrFail($id);

        if ($pengajuan->nasabah) {
            $pengajuan->nasabah->saldo = app(\App\Services\BankAccessService::class)->getSaldoTabungan($pengajuan->id_anggota);
        }

        // Get bunga dari master data berdasarkan durasi
        $masterBunga = MasterBungaPinjaman::getBungaByDurasi($pengajuan->durasi);
        $masterDenda = MasterDendaPinjaman::getDendaAktif();

        // Get admin petty cash balances (Modal Awal only, for pencairan)
        $adminSaldoCash = PettyCashSaldo::getSaldo(Auth::id(), 'admin', 'cash', 'other');
        $adminSaldoTransfer = PettyCashSaldo::getSaldo(Auth::id(), 'admin', 'transfer', 'other');
        $adminSaldo = (object) [
            'cash' => $adminSaldoCash,
            'transfer' => $adminSaldoTransfer
        ];

        // Biaya transfer mapping
        $biayaTransfer = \App\Models\BiayaTransfer::where('is_active', true)->get();

        return view('admin.pinjaman.detail-pengajuan', compact('pengajuan', 'masterBunga', 'masterDenda', 'adminSaldo', 'biayaTransfer'));
    }
```
</details>

### Route: `admin/pinjaman/pinjaman-aktif` [GET|HEAD]
**Function:** `pinjamanAktif`

**Queries Detected:**
- Model: PinjamanH

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function pinjamanAktif(Request $request)
    {
        $query = PinjamanH::with('nasabah.user')
            ->where('lunas', 'belum')
            // ->whereIn('status', ['pencairan', 'telaksana']) // Removed status check
            ->latest();

        // Filter by jenis
        if ($request->has('jenis') && $request->jenis !== '') {
            $query->where('jenis', $request->jenis);
        }

        // Filter by status (removed because PinjamanH status column dropped)
        /* if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        } */

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->whereHas('nasabah.user', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $pinjaman = $query->paginate(15);

        return view('admin.pinjaman.pinjaman-aktif', compact('pinjaman'));
    }
```
</details>

### Route: `admin/pinjaman/pinjaman-aktif/create` [GET|HEAD]
**Function:** `createPinjaman`

**Queries Detected:**
- Model: AdminPermissionService
- Model: Nasabah
- Model: MasterBungaPinjaman

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function createPinjaman()
    {
        // Authorization: Only Admin Utama can create manual pinjaman
        if (!app(\App\Services\AdminPermissionService::class)->canCrudPinjamanAktif(Auth::user())) {
            abort(403, 'Anda tidak memiliki akses untuk fitur ini. Hanya Admin Utama yang dapat membuat pinjaman manual.');
        }

        $nasabah = Nasabah::with('user')->get();
        $masterBunga = MasterBungaPinjaman::where('status_aktif', true)->orderBy('durasi_min')->get();
        
        return view('admin.pinjaman.create-pinjaman', compact('nasabah', 'masterBunga'));
    }
```
</details>

### Route: `admin/pinjaman/pinjaman-aktif` [POST]
**Function:** `storePinjaman`

**Queries Detected:**
- Model: AdminPermissionService
- Model: MasterBungaPinjaman
- Model: MasterDendaPinjaman
- Model: PinjamanH
- Model: Nasabah
- Model: ActivityLogService

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function storePinjaman(Request $request)
    {
        // Authorization: Only Admin Utama can create manual pinjaman
        if (!app(\App\Services\AdminPermissionService::class)->canCrudPinjamanAktif(Auth::user())) {
            abort(403, 'Anda tidak memiliki akses untuk fitur ini. Hanya Admin Utama yang dapat membuat pinjaman manual.');
        }

        $request->validate([
            'id_anggota' => 'required|exists:tbl_nasabah,id',
            'nominal' => 'required|numeric|min:100000',
            'durasi' => 'required|integer|min:1|max:24',
            'tgl_pinjam' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            // Get bunga dari master data
            $masterBunga = MasterBungaPinjaman::getBungaByDurasi($request->durasi);
            if (!$masterBunga) {
                return redirect()->back()
                    ->with('error', 'Bunga untuk durasi ' . $request->durasi . ' bulan belum diatur di master data')
                    ->withInput();
            }

            // Get denda dari master data
            $masterDenda = MasterDendaPinjaman::getDendaAktif();
            if (!$masterDenda) {
                return redirect()->back()
                    ->with('error', 'Denda belum diatur di master data')
                    ->withInput();
            }

            $nominal = $request->nominal;
            $bungaPersen = $masterBunga->bunga_persen;
            $bungaRp = ($nominal * $bungaPersen) / 100;

            // Create pinjaman langsung (tanpa pengajuan)
            $pinjaman = PinjamanH::create([
                'id_anggota' => $request->id_anggota,
                'id_pengajuan' => null, // Tidak ada pengajuan
                'jumlah_pinjam' => $nominal,
                'lama_pinjam' => (int)$request->durasi,
                'jenis' => 'bulanan',
                'bunga' => $bungaPersen / 100,
                'bunga_rp' => $bungaRp,
                'denda_persen' => $masterDenda->denda_persen,
                'tgl_pinjam' => $request->tgl_pinjam,
                'status' => 'telaksana', // Langsung terlaksana karena ketemu langsung
                'lunas' => 'belum',
            ]);

            // Generate jadwal angsuran
            $this->generateJadwalAngsuran($pinjaman);

            DB::commit();

            $nasabahRecord = Nasabah::with('user')->find($request->id_anggota);
            app(ActivityLogService::class)->logCreatePinjamanManual($pinjaman->id, $pinjaman->jumlah_pinjam, $nasabahRecord->user->nama ?? 'N/A');

            return redirect()->route('admin.pinjaman.pinjaman-aktif')
                ->with('success', 'Pinjaman berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
```
</details>

### Route: `admin/pinjaman/pinjaman-aktif/{id}/edit` [GET|HEAD]
**Function:** `editPinjaman`

**Queries Detected:**
- Model: AdminPermissionService
- Model: PinjamanH
- Model: Nasabah
- Model: MasterBungaPinjaman

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function editPinjaman($id)
    {
        // Authorization: Only Admin Utama can edit manual pinjaman
        if (!app(\App\Services\AdminPermissionService::class)->canCrudPinjamanAktif(Auth::user())) {
            abort(403, 'Anda tidak memiliki akses untuk fitur ini. Hanya Admin Utama yang dapat mengedit pinjaman manual.');
        }

        $pinjaman = PinjamanH::with(['nasabah.user'])->findOrFail($id);
        $nasabah = Nasabah::with('user')->get();
        $masterBunga = MasterBungaPinjaman::where('status_aktif', true)->orderBy('durasi_min')->get();
        
        return view('admin.pinjaman.edit-pinjaman', compact('pinjaman', 'nasabah', 'masterBunga'));
    }
```
</details>

### Route: `admin/pinjaman/pinjaman-aktif/{id}` [PUT]
**Function:** `updatePinjaman`

**Queries Detected:**
- Model: AdminPermissionService
- Model: PinjamanH
- Model: TempoPinjamanB
- Model: MasterBungaPinjaman

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function updatePinjaman(Request $request, $id)
    {
        // Authorization: Only Admin Utama can update manual pinjaman
        if (!app(\App\Services\AdminPermissionService::class)->canCrudPinjamanAktif(Auth::user())) {
            abort(403, 'Anda tidak memiliki akses untuk fitur ini. Hanya Admin Utama yang dapat mengupdate pinjaman manual.');
        }

        $pinjaman = PinjamanH::findOrFail($id);

        // Cek apakah pinjaman sudah ada angsuran yang dibayar
        $hasPayment = TempoPinjamanB::where('pinjaman_id', $id)
            ->where('jumlah_terbayar', '>', 0)
            ->exists();

        if ($hasPayment) {
            return redirect()->back()
                ->with('error', 'Pinjaman tidak dapat diubah karena sudah ada pembayaran')
                ->withInput();
        }

        $request->validate([
            'id_anggota' => 'required|exists:tbl_nasabah,id',
            'nominal' => 'required|numeric|min:100000',
            'durasi' => 'required|integer|min:1|max:24',
            'tgl_pinjam' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            // Get bunga dari master data
            $masterBunga = MasterBungaPinjaman::getBungaByDurasi($request->durasi);
            if (!$masterBunga) {
                return redirect()->back()
                    ->with('error', 'Bunga untuk durasi ' . $request->durasi . ' bulan belum diatur di master data')
                    ->withInput();
            }

            $nominal = $request->nominal;
            $bungaPersen = $masterBunga->bunga_persen;
            $bungaRp = ($nominal * $bungaPersen) / 100;

            // Update pinjaman
            $pinjaman->update([
                'id_anggota' => $request->id_anggota,
                'jumlah_pinjam' => $nominal,
                'lama_pinjam' => (int)$request->durasi,
                'bunga' => $bungaPersen / 100,
                'bunga_rp' => $bungaRp,
                'tgl_pinjam' => $request->tgl_pinjam,
            ]);

            // Hapus angsuran lama dan buat baru
            TempoPinjamanB::where('pinjaman_id', $id)->delete();
            $this->generateJadwalAngsuran($pinjaman->fresh());

            DB::commit();

            return redirect()->route('admin.pinjaman.detail-pinjaman', $id)
                ->with('success', 'Pinjaman berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
```
</details>

### Route: `admin/pinjaman/pinjaman-aktif/{id}` [DELETE]
**Function:** `deletePinjaman`

**Queries Detected:**
- Model: AdminPermissionService
- Model: PinjamanH
- Model: TempoPinjamanB
- Model: ActivityLogService

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function deletePinjaman($id)
    {
        // Authorization: Only Admin Utama can delete manual pinjaman
        if (!app(\App\Services\AdminPermissionService::class)->canCrudPinjamanAktif(Auth::user())) {
            abort(403, 'Anda tidak memiliki akses untuk fitur ini. Hanya Admin Utama yang dapat menghapus pinjaman manual.');
        }

        $pinjaman = PinjamanH::findOrFail($id);

        // Cek apakah pinjaman sudah ada angsuran yang dibayar
        $hasPayment = TempoPinjamanB::where('pinjaman_id', $id)
            ->where('jumlah_terbayar', '>', 0)
            ->exists();

        if ($hasPayment) {
            return redirect()->back()
                ->with('error', 'Pinjaman tidak dapat dihapus karena sudah ada pembayaran');
        }

        try {
            DB::beginTransaction();

            // Hapus angsuran
            TempoPinjamanB::where('pinjaman_id', $id)->delete();
            
            // Hapus pinjaman
            $nasabahNama = $pinjaman->nasabah->user->nama ?? 'N/A';
            $pinjaman->delete();

            DB::commit();

            app(ActivityLogService::class)->logDeletePinjamanManual($id, $nasabahNama);

            return redirect()->route('admin.pinjaman.pinjaman-aktif')
                ->with('success', 'Pinjaman berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
```
</details>

### Route: `admin/pinjaman/pinjaman-lunas` [GET|HEAD]
**Function:** `pinjamanLunas`

**Queries Detected:**
- Model: PinjamanH

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function pinjamanLunas(Request $request)
    {
        $query = PinjamanH::with(['nasabah.user', 'tempoBulanan', 'tempoMingguan'])
            ->where('lunas', 'lunas')
            ->latest();

        if ($request->has('jenis') && $request->jenis !== '') {
            $query->where('jenis', $request->jenis);
        }

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->whereHas('nasabah.user', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $pinjaman = $query->paginate(15);

        return view('admin.pinjaman.pinjaman-lunas', compact('pinjaman'));
    }
```
</details>

### Route: `admin/pinjaman/pinjaman-aktif/{id}` [GET|HEAD]
**Function:** `detailPinjaman`

**Queries Detected:**
- Model: PinjamanH

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function detailPinjaman($id)
    {
        $pinjaman = PinjamanH::with([
            'nasabah.user',
            'nasabah.dataKtp',
            'nasabah.pekerjaan',
            'pengajuan',
            'tempoBulanan',
            'tempoMingguan',
            'buktiPelunasan'
        ])->findOrFail($id);

        // Get angsuran berdasarkan jenis
        $angsuran = $pinjaman->jenis === 'bulanan' 
            ? $pinjaman->tempoBulanan()->orderBy('no_urut')->get()
            : $pinjaman->tempoMingguan()->orderBy('no_urut')->get();

        return view('admin.pinjaman.detail-pinjaman', compact('pinjaman', 'angsuran'));
    }
```
</details>

### Route: `admin/pinjaman/angsuran` [GET|HEAD]
**Function:** `angsuran`

**Queries Detected:**
- Model: PinjamanH

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function angsuran(Request $request)
    {
        $jenis = $request->get('jenis', 'bulanan');

        $query = PinjamanH::with(['nasabah.user'])
            ->where('lunas', 'belum')
            ->when($jenis === 'bulanan', fn ($q) => $q->with(['tempoBulanan' => fn ($q) => $q->orderBy('no_urut')]))
            ->when($jenis === 'mingguan', fn ($q) => $q->with(['tempoMingguan' => fn ($q) => $q->orderBy('no_urut')]))
            ->latest();

        // Filter by status (pinjaman yang punya minimal satu angsuran dengan status ini)
        if ($request->filled('status')) {
            if ($jenis === 'bulanan') {
                $query->whereHas('tempoBulanan', fn ($q) => $q->where('status_bayar', $request->status));
            } else {
                $query->whereHas('tempoMingguan', fn ($q) => $q->where('status_bayar', $request->status));
            }
        }

        // Filter by date (jatuh tempo)
        if ($request->filled('tanggal_dari')) {
            if ($jenis === 'bulanan') {
                $query->whereHas('tempoBulanan', fn ($q) => $q->whereDate('tgl_jatuh_tempo', '>=', $request->tanggal_dari));
            } else {
                $query->whereHas('tempoMingguan', fn ($q) => $q->whereDate('tgl_jatuh_tempo', '>=', $request->tanggal_dari));
            }
        }
        if ($request->filled('tanggal_sampai')) {
            if ($jenis === 'bulanan') {
                $query->whereHas('tempoBulanan', fn ($q) => $q->whereDate('tgl_jatuh_tempo', '<=', $request->tanggal_sampai));
            } else {
                $query->whereHas('tempoMingguan', fn ($q) => $q->whereDate('tgl_jatuh_tempo', '<=', $request->tanggal_sampai));
            }
        }

        // Search by nasabah
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('nasabah.user', fn ($q) => $q->where('nama', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%"));
        }

        $pinjamanList = $query->paginate(10);

        return view('admin.pinjaman.angsuran', compact('pinjamanList', 'jenis'));
    }
```
</details>

### Route: `admin/pinjaman/angsuran/{id}` [GET|HEAD]
**Function:** `detailAngsuran`

**Queries Detected:**
- Model: TempoPinjamanB
- Model: TempoPinjamanM
- Model: PengajuanPembayaranPinjaman

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function detailAngsuran(Request $request, $id)
    {
        $jenis = $request->get('jenis', 'bulanan');
        
        if ($jenis === 'bulanan') {
            $angsuran = TempoPinjamanB::with(['pinjaman.nasabah.user', 'pinjaman'])
                ->findOrFail($id);
        } else {
            $angsuran = TempoPinjamanM::with(['pinjaman.nasabah.user', 'pinjaman'])
                ->findOrFail($id);
        }

        // Bukti transfer untuk angsuran ini (jika sudah lunas)
        $buktiTransferAngsuran = collect();
        if ($angsuran->status_bayar === 'lunas') {
            $pengajuanBayar = PengajuanPembayaranPinjaman::where('tempo_id', $id)
                ->where('jenis_tempo', $jenis)
                ->whereIn('status', ['3', '4'])
                ->with('buktiFoto')
                ->get();
            $buktiTransferAngsuran = $pengajuanBayar->pluck('buktiFoto')->flatten()->filter(fn($b) => $b && ($b->file_path ?? null));
        }

        // Denda yang dihitung (untuk tampilan angsuran telat, konsisten dengan nasabah)
        $dendaDisplay = $angsuran->hitungDenda();

        return view('admin.pinjaman.detail-angsuran', compact('angsuran', 'jenis', 'buktiTransferAngsuran', 'dendaDisplay'));
    }
```
</details>

### Route: `admin/pinjaman/pembayaran` [GET|HEAD]
**Function:** `pembayaran`

**Queries Detected:**
- Model: PengajuanPembayaranPinjaman

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function pembayaran(Request $request)
    {
        // Hanya tampilkan pengajuan pembayaran via transfer. Pembayaran tunai/janji temu dikelola dari Janji Temu Universal.
        $query = PengajuanPembayaranPinjaman::with(['nasabah.user', 'pinjaman.pengajuan', 'janjiTemu.lokasi'])
            ->where(function ($q) {
                $q->where('metode_pembayaran', 'transfer')->orWhereNull('metode_pembayaran');
            })
            ->latest();

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        } else {
            // Default show pending
            $query->where('status', '1');
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->whereHas('nasabah.user', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $pengajuan = $query->paginate(15);

        return view('admin.pinjaman.pembayaran', compact('pengajuan'));
    }
```
</details>

### Route: `admin/pinjaman/pembayaran/{id}` [GET|HEAD]
**Function:** `detailPembayaran`

**Queries Detected:**
- Model: PengajuanPembayaranPinjaman
- Model: TempoPinjamanB
- Model: TempoPinjamanM
- Model: JnsLokasiPerusahaan

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function detailPembayaran($id)
    {
        $pengajuan = PengajuanPembayaranPinjaman::with([
            'nasabah.user',
            'nasabah.dataKtp',
            'pinjaman.pengajuan',
            'janjiTemu.lokasi',
            'buktiFoto'
        ])->findOrFail($id);

        // Get angsuran yang terkait
        $angsuran = null;
        if ($pengajuan->tempo_id && $pengajuan->jenis_tempo) {
            if ($pengajuan->jenis_tempo === 'bulanan') {
                $angsuran = TempoPinjamanB::where('id', $pengajuan->tempo_id)->first();
            } else {
                $angsuran = TempoPinjamanM::where('id', $pengajuan->tempo_id)->first();
            }
        }

        // Get lokasi untuk janji temu (jika cash)
        $lokasi = JnsLokasiPerusahaan::all();

        return view('admin.pinjaman.detail-pembayaran', compact('pengajuan', 'angsuran', 'lokasi'));
    }
```
</details>

### Route: `admin/pinjaman/pengajuan/{id}/approve` [POST]
**Function:** `approvePengajuan`

**Queries Detected:**
- Model: PengajuanPinjaman
- Model: MasterBungaPinjaman
- Model: MasterDendaPinjaman
- Model: IdGenerator
- Model: PinjamanH
- Model: ActivityLogService
- Model: NasabahNotification

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function approvePengajuan(Request $request, $id)
    {
        $request->validate([
            'keterangan_admin' => 'nullable|string|max:500',
        ]);

        $pengajuan = PengajuanPinjaman::findOrFail($id);

        // Cek status harus '1' (pending)
        if ($pengajuan->status !== '1') {
            return redirect()->back()
                ->with('error', 'Pengajuan ini tidak bisa disetujui karena statusnya bukan pending');
        }

        // Cek apakah sudah punya pinjaman
        if ($pengajuan->pinjaman) {
            return redirect()->back()
                ->with('error', 'Pengajuan ini sudah memiliki data pinjaman');
        }

        // Get bunga dari master data berdasarkan durasi
        $masterBunga = MasterBungaPinjaman::getBungaByDurasi($pengajuan->durasi);
        if (!$masterBunga) {
            return redirect()->back()
                ->with('error', 'Bunga untuk durasi ' . $pengajuan->durasi . ' bulan belum diatur di master data');
        }

        // Get denda dari master data
        $masterDenda = MasterDendaPinjaman::getDendaAktif();
        if (!$masterDenda) {
            return redirect()->back()
                ->with('error', 'Denda belum diatur di master data');
        }

        try {
            DB::beginTransaction();

            // Hitung bunga asli (persentase dari nominal)
            $nominal = (float) $pengajuan->nominal;
            $durasi = (int) $pengajuan->durasi;
            $bungaPersen = $masterBunga->bunga_persen;
            
            $bungaRp = ($nominal * $bungaPersen) / 100;
            $totalKewajiban = $nominal + $bungaRp;
            
            $angsuranRaw = $totalKewajiban / $durasi;
            
            // Bulatkan angsuran per bulan ke bawah ke kelipatan 1.000
            $angsuranBulanan = (int) floor($angsuranRaw / 1000) * 1000;
            if ($angsuranBulanan == 0 && $totalKewajiban > 0) {
                $angsuranBulanan = (int) floor($totalKewajiban / $durasi);
            }
            
            $jumlahPinjam = $nominal;

            // Generate ID Pinjaman: P (Pinjaman) TF/TN (Transfer/Tunai) DPNJM (Detail Pinjaman Header)
            $kodeVia = $pengajuan->jenis_pencairan === 'transfer' ? 'TF' : 'TN';
            $idPinjaman = IdGenerator::generate('tbl_pinjaman_h', 'P', $kodeVia, 'DPNJM', now());

            // Create pinjaman header (BELUM ada jadwal angsuran)
            $pinjaman = PinjamanH::create([
                'id' => $idPinjaman,
                'id_anggota' => $pengajuan->id_anggota,
                'id_pengajuan' => $pengajuan->id,
                'jumlah_pinjam' => $jumlahPinjam,
                'lama_pinjam' => $durasi,
                'ags_bulan' => $angsuranBulanan,
                'jenis' => 'bulanan',
                'bunga' => $bungaPersen,
                'bunga_rp' => $bungaRp,
                'denda_persen' => $masterDenda->denda_persen,
                'tgl_pinjam' => now(), // Tanggal approval
                'lunas' => 'belum',
            ]);

            // Update status pengajuan menjadi '3' (Disetujui)
            $pengajuan->update([
                'status' => '3',
                'bunga_persen' => $masterBunga->bunga_persen,
                'keterangan_admin' => $request->keterangan_admin,
            ]);

            DB::commit();

            app(ActivityLogService::class)->logApprovePengajuanPinjaman($pengajuan->id, (float) $pengajuan->nominal, $pengajuan->nasabah->user->nama ?? 'N/A');

            NasabahNotification::notify(
                $pengajuan->id_anggota,
                'pinjaman',
                'Pengajuan pinjaman disetujui',
                'Pengajuan pinjaman Anda sebesar Rp ' . number_format((float) $pengajuan->nominal ?? 0, 0, ',', '.') . ' telah disetujui. Silakan menunggu proses pencairan.',
                route('nasabah.pinjaman.detail-pengajuan', $pengajuan->id),
                (string) $pengajuan->id,
                'pengajuan_pinjaman'
            );

            return redirect()->route('admin.pinjaman.detail-pengajuan', $id)
                ->with('success', 'Pengajuan berhasil disetujui dan data pinjaman dibuat. Silakan klik "Cairkan" untuk generate jadwal angsuran dan pencairan dana.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
```
</details>

### Route: `admin/pinjaman/pengajuan/{id}/reject` [POST]
**Function:** `rejectPengajuan`

**Queries Detected:**
- Model: PengajuanPinjaman
- Model: ActivityLogService
- Model: NasabahNotification

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function rejectPengajuan(Request $request, $id)
    {
        $request->validate([
            'keterangan_admin' => 'required|string|max:500'
        ]);

        $pengajuan = PengajuanPinjaman::findOrFail($id);
        
        // Cek status harus '1' (pending)
        if ($pengajuan->status !== '1') {
            return redirect()->back()
                ->with('error', 'Pengajuan ini tidak bisa ditolak karena statusnya bukan pending');
        }

        // Update status menjadi '2' (Ditolak)
        $pengajuan->update([
            'status' => '2',
            'keterangan_admin' => $request->keterangan_admin
        ]);

        app(ActivityLogService::class)->logRejectPengajuanPinjaman($pengajuan->id, (float) $pengajuan->nominal, $pengajuan->nasabah->user->nama ?? 'N/A', $request->keterangan_admin);

        NasabahNotification::notify(
            $pengajuan->id_anggota,
            'pinjaman',
            'Pengajuan pinjaman ditolak',
            'Pengajuan pinjaman Anda ditolak. ' . ($request->keterangan_admin ?? ''),
            route('nasabah.pinjaman.detail-pengajuan', $pengajuan->id),
            (string) $pengajuan->id,
            'pengajuan_pinjaman'
        );

        return redirect()->route('admin.pinjaman.pengajuan')
            ->with('success', 'Pengajuan pinjaman berhasil ditolak');
    }
```
</details>

### Route: `admin/pinjaman/pengajuan/{id}/cairkan` [POST]
**Function:** `cairkanPinjaman`

**Queries Detected:**
- Model: PengajuanPinjaman
- Model: TempoPinjamanB
- Model: PettyCashSaldo
- Model: PettyCashTransaksiNasabah
- Model: PettyCashConstants
- Model: IdGenerator
- Model: User
- Model: PettyCashOwnerTransaksi
- Model: BuktiFoto
- Model: BankAccessService
- Model: ActivityLogService
- Model: NasabahNotification

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function cairkanPinjaman(Request $request, $id)
    {
        $request->validate([
            'tgl_cair' => 'required|date',
            'metode_pencairan' => 'required|in:kas_utama,petty_cash,petty_tf',
            'bukti_transfer' => 'required|image|max:5120',
        ], [
            'bukti_transfer.required' => 'Bukti transaksi wajib diupload.',
        ]);

        $pengajuan = PengajuanPinjaman::with('pinjaman')->findOrFail($id);

        // Cek status harus '3' (disetujui)
        if ($pengajuan->status !== '3') {
            return redirect()->back()
                ->with('error', 'Pengajuan ini tidak bisa dicairkan karena statusnya bukan disetujui');
        }

        // Cek apakah sudah punya pinjaman
        if (!$pengajuan->pinjaman) {
            return redirect()->back()
                ->with('error', 'Data pinjaman belum dibuat. Silakan setujui pengajuan terlebih dahulu.');
        }

        $pinjaman = $pengajuan->pinjaman;

        // Cek apakah jadwal angsuran sudah dibuat
        $existingTempo = TempoPinjamanB::where('pinjaman_id', $pinjaman->id)->exists();
        if ($existingTempo) {
            return redirect()->back()
                ->with('error', 'Pinjaman ini sudah dicairkan sebelumnya (jadwal angsuran sudah ada)');
        }

        try {
            DB::beginTransaction();

            // Update tanggal pinjam di pinjaman header
            $metode = $request->metode_pencairan;
            $isPettyCash = in_array($metode, ['petty_cash', 'petty_tf']);
            
            if ($isPettyCash) {
                $tipe = $metode == 'petty_cash' ? 'cash' : 'transfer';
                $saldoTersedia = \App\Models\PettyCashSaldo::getSaldo(Auth::id(), 'admin', $tipe);
                // Get Saldo dari MODAL AWAL
                $saldoTersedia = \App\Models\PettyCashSaldo::getSaldo(Auth::id(), 'admin', $tipe, 'other');
                if ($saldoTersedia < (float) $pinjaman->jumlah_pinjam) {
                    throw new \Exception(sprintf(
                        'Saldo %s MODAL AWAL tidak mencukupi untuk pencairan. Dibutuhkan: Rp %s | Tersedia: Rp %s',
                        strtoupper($tipe),
                        number_format($pinjaman->jumlah_pinjam, 0, ',', '.'),
                        number_format($saldoTersedia, 0, ',', '.')
                    ));
                }

                // 🛡️ Cek apakah sudah ada transaksi petty cash (hindari duplikasi)
                $existingPc = \App\Models\PettyCashTransaksiNasabah::where('ref_table', PettyCashConstants::REF_PINJAMAN_H)
                    ->where('ref_id', $pinjaman->id)
                    ->first();
                
                if ($existingPc) {
                    throw new \Exception('Transaksi pencairan ini sudah tercatat di Petty Cash.');
                }
                
                // Create Transaction Record for Disbursement
                $pettyId = \App\Helpers\IdGenerator::generate('petty_cash_transaksi_nasabah', 'PC', 'AN', 'P', now());
                \App\Models\PettyCashTransaksiNasabah::create([
                    'id' => $pettyId,
                    'admin_id' => Auth::id(),
                    'nasabah_id' => $pengajuan->id_anggota,
                    'nominal' => $pinjaman->jumlah_pinjam,
                    'id_jns_transaksi' => PettyCashConstants::JNS_PNCR,
                    'id_jns_via' => ($tipe == 'cash' ? PettyCashConstants::VIA_CS : PettyCashConstants::VIA_TF),
                    'id_jns_fitur' => PettyCashConstants::FITUR_PINJAMAN,
                    'keterangan' => 'Pencairan Pinjaman #' . $pinjaman->id,
                    'ref_table' => PettyCashConstants::REF_PINJAMAN_H,
                    'ref_id' => $pinjaman->id,
                    'status' => 'approved',
                    'tgl_transaksi' => now(),
                ]);

                \App\Models\PettyCashSaldo::updateSaldo(Auth::id(), $tipe, -(float)$pinjaman->jumlah_pinjam, $pettyId, 'Pencairan Pinjaman', 'petty_cash_transaksi_nasabah', 'other');
            }

            $pinjaman->update([
                'tgl_pinjam' => $request->tgl_cair,
                'is_petty_cash' => $isPettyCash ? 1 : 0,
                'petty_cash_ref' => $isPettyCash ? $pinjaman->id : null,
                'metode_pencairan' => $metode,
            ]);

            // Generate jadwal angsuran
            $this->generateJadwalAngsuran($pinjaman);

            // Upload bukti transaksi dan simpan ke tbl_bukti_foto dengan kode PNCR
            $file = $request->file('bukti_transfer');
            $path = $file->store('bukti-pencairan-pinjaman', 'public');
            
            // 🔥 INTEGRASI OWNER LEDGER: Jika pencairan dari Kas Utama
            if ($metode === 'kas_utama') {
                $owner = User::where('role', 'admin_utama')->first();
                if ($owner) {
                    $ownerTransId = IdGenerator::generate('petty_cash_owner_transaksi', 'PCOW', 'OW', 'TR');
                    PettyCashOwnerTransaksi::create([
                        'id'              => $ownerTransId,
                        'user_id'         => $owner->id,
                        'tipe'            => 'keluar',
                        'sumber'          => PettyCashConstants::SUMBER_PINJAMAN,
                        'nominal_cash'    => 0,
                        'nominal_tf'      => (float) $pinjaman->jumlah_pinjam,
                        'keterangan'      => "Pencairan Pinjaman #{$pinjaman->id}: " . ($pengajuan->nasabah->user->nama ?? '-') . " (#{$pengajuan->id})",
                        'bukti_foto_tf'   => $path,
                        'ref_id'          => $pinjaman->id,
                        'ref_table'       => PettyCashConstants::REF_PINJAMAN_H,
                    ]);

                    // Owner memberikan pinjaman (saldo keluar)
                    PettyCashSaldo::buatMutasi(
                        $owner->id, 'owner', -(float)$pinjaman->jumlah_pinjam,
                        "Pencairan Pinjaman: " . ($pinjaman->nasabah->user->nama ?? '-') . " (#{$pinjaman->id})",
                        $pinjaman->id, 'tbl_pinjaman', 'transfer',
                        \App\Services\PettyCashConstants::SUMBER_PINJAMAN
                    );
                }
            }

            $idBuktiFoto = IdGenerator::generate('tbl_bukti_foto', 'P', 'TF', 'PNCR', $request->tgl_cair);
            BuktiFoto::create([
                'id' => $idBuktiFoto,
                'owner_id' => $pengajuan->id,
                'owner_fitur' => 'P',
                'owner_trans' => 'PNCR',
                'file_path' => $path,
                'keterangan' => 'Bukti transaksi pencairan pinjaman',
            ]);

            // Update status pengajuan menjadi '4' (Terlaksana/Tercair)
            $pengajuan->update([
                'status' => '4',
                'tgl_cair' => $request->tgl_cair,
            ]);

            // 🔥 INTEGRASI BIAYA TRANSFER ANTARBANK
            $biayaTransfer = 0;
            $bankPengirim = null;

            if ($metode !== 'petty_cash') {
                $bankService = app(\App\Services\BankAccessService::class);
                $namaBank = $bankService->getNamaBank($pengajuan->id_anggota);
                $bankPengirim = $request->input('bank_pengirim', 'BCA');
                
                if ($namaBank && !$bankService->isBcaUser($pengajuan->id_anggota)) {
                    $potong = $bankService->potongBiayaTransfer(
                        $pengajuan->id_anggota,
                        $namaBank,
                        'Pencairan Pinjaman #' . $pinjaman->id,
                        Auth::id(),
                        $bankPengirim
                    );
                    
                    if (!$potong['success']) {
                        throw new \Exception($potong['message']);
                    }
                    $biayaTransfer = $potong['biaya'] ?? 0;
                }
            }

            // Simpan info bank pengirim dan biaya transfer ke tabel pinjaman_h
            if ($bankPengirim || $biayaTransfer > 0) {
                $pinjaman->update([
                    'bank_pengirim' => $bankPengirim,
                    'biaya_transfer' => $biayaTransfer,
                ]);
            }

            DB::commit();

            app(ActivityLogService::class)->logCairkanPinjaman($pinjaman->id, $pinjaman->jumlah_pinjam, $pengajuan->nasabah->user->nama ?? 'N/A');

            NasabahNotification::notify(
                $pengajuan->id_anggota,
                'pinjaman',
                'Pinjaman telah dicairkan',
                'Pinjaman Anda sebesar Rp ' . number_format($pinjaman->jumlah_pinjam ?? 0, 0, ',', '.') . ' telah dicairkan. Dana telah ditransfer sesuai metode pencairan.',
                route('nasabah.pinjaman.detail-pinjaman', $pinjaman->id),
                (string) $pinjaman->id,
                'pinjaman'
            );

            return redirect()->route('admin.pinjaman.detail-pinjaman', $pinjaman->id)
                ->with('success', 'Pinjaman berhasil dicairkan dan jadwal angsuran telah dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
```
</details>

### Route: `admin/pinjaman/pembayaran/{id}/approve` [POST]
**Function:** `approvePembayaran`

**Queries Detected:**
- Model: PengajuanPembayaranPinjaman
- Model: TempoPinjamanB
- Model: TempoPinjamanM
- Model: PettyCashTransaksiNasabah
- Model: PettyCashConstants
- Model: PettyCashOwnerTransaksi
- Model: IdGenerator
- Model: PettyCashSaldo
- Model: User
- Model: ActivityLogService
- Model: NasabahNotification

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function approvePembayaran(Request $request, $id)
    {
        $request->validate([
            'metode_penerimaan' => 'required|in:rek_koperasi,rek_admin,cash_admin',
            'keterangan_admin' => 'nullable|string|max:500',
        ]);

        $pengajuan = PengajuanPembayaranPinjaman::with(['pinjaman'])->findOrFail($id);

        if ($pengajuan->status !== '1') {
            return redirect()->back()
                ->with('error', 'Status pengajuan tidak valid untuk disetujui');
        }

        try {
            DB::beginTransaction();

            // 🛡️ Re-check status DALAM transaction + lock untuk cegah race condition dobel-approve
            $pengajuan = PengajuanPembayaranPinjaman::with(['pinjaman'])
                ->lockForUpdate()
                ->findOrFail($id);

            if ($pengajuan->status !== '1') {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Pengajuan ini sudah diproses oleh admin lain. Silakan refresh halaman.');
            }

            // Get angsuran yang terkait (dengan lock agar tidak ada update paralel)
            $angsuran = null;
            if ($pengajuan->tempo_id && $pengajuan->jenis_tempo) {
                if ($pengajuan->jenis_tempo === 'bulanan') {
                    $angsuran = TempoPinjamanB::where('id', $pengajuan->tempo_id)
                        ->lockForUpdate()
                        ->first();
                } else {
                    $angsuran = TempoPinjamanM::where('id', $pengajuan->tempo_id)
                        ->lockForUpdate()
                        ->first();
                }
            }

            if (!$angsuran) {
                return redirect()->back()
                    ->with('error', 'Data angsuran tidak ditemukan');
            }

            $pinjaman = $pengajuan->pinjaman;
            if (!$pinjaman) {
                return redirect()->back()
                    ->with('error', 'Data pinjaman tidak ditemukan');
            }

            // 🛡️ Cek apakah sudah ada transaksi petty cash (hindari duplikasi)
            $existingPc = \App\Models\PettyCashTransaksiNasabah::where('ref_table', PettyCashConstants::REF_PINJAMAN_D)
                ->where('ref_id', $pengajuan->id)
                ->first();
            
            $existingOwner = \App\Models\PettyCashOwnerTransaksi::where('ref_table', PettyCashConstants::REF_PINJAMAN_D)
                ->where('ref_id', $pengajuan->id)
                ->first();

            if ($existingPc || $existingOwner) {
                 DB::rollBack();
                 return redirect()->back()->with('error', 'Transaksi ini sudah tercatat sebelumnya.');
            }

            // Update angsuran dengan pembayaran menggunakan method terpusat (Anti-Duplikasi)
            $angsuran->applyPayment($pengajuan->nominal);

            // Proses Penerimaan Petty Cash (Jika bukan Rek Koperasi Utama)
            $metode = $request->metode_penerimaan;
            if (in_array($metode, ['rek_admin', 'cash_admin'])) {
                $pettyId = \App\Helpers\IdGenerator::generate('petty_cash_transaksi_nasabah', 'PC', 'AN', 'P', now());
                $tipeVia = $metode == 'cash_admin' ? PettyCashConstants::VIA_CS : PettyCashConstants::VIA_TF; 
                
                \App\Models\PettyCashTransaksiNasabah::create([
                    'id' => $pettyId,
                    'admin_id' => Auth::id(),
                    'nasabah_id' => $pengajuan->id_anggota,
                    'nominal' => (float) $pengajuan->nominal,
                    'id_jns_transaksi' => PettyCashConstants::JNS_PMB,
                    'id_jns_via' => $tipeVia,
                    'id_jns_fitur' => PettyCashConstants::FITUR_PINJAMAN,
                    'keterangan' => 'Angsuran Pinjaman #' . $pinjaman->id,
                    'ref_table' => PettyCashConstants::REF_PINJAMAN_D,
                    'ref_id' => $pengajuan->id,
                    'status' => 'approved',
                    'tgl_transaksi' => now(),
                ]);
                
                $tipeSaldo = $metode == 'cash_admin' ? 'cash' : 'transfer';
                \App\Models\PettyCashSaldo::updateSaldo(Auth::id(), $tipeSaldo, (float) $pengajuan->nominal, $pettyId, 'Angsuran Masuk', 'petty_cash_transaksi_nasabah', 'pinjaman');
            } elseif ($metode === 'rek_koperasi') {
                // 🔥 INTEGRASI OWNER LEDGER: Pencatatan ke Rekening Koperasi Utama (Admin Utama)
                $owner = User::where('role', 'admin_utama')->first();
                if ($owner) {
                    $ownerTransId = IdGenerator::generate('petty_cash_owner_transaksi', 'PCOW', 'OW', 'TR');
                    PettyCashOwnerTransaksi::create([
                        'id'           => $ownerTransId,
                        'user_id'      => $owner->id,
                        'tipe'         => 'terima_setoran',
                        'sumber'       => PettyCashConstants::SUMBER_PINJAMAN,
                        'nominal_cash' => 0,
                        'nominal_tf'   => (float) $pengajuan->nominal,
                        'keterangan'   => "Angsuran Pinjaman #{$pinjaman->id}: " . ($pengajuan->nasabah->user->nama ?? '-') . " (#{$pengajuan->id})",
                        'ref_table'    => PettyCashConstants::REF_PINJAMAN_D,
                        'ref_id'       => $pengajuan->id,
                    ]);

                    // Owner menerima angsuran (saldo masuk)
                    PettyCashSaldo::buatMutasi(
                        $owner->id, 'owner', (float)$pengajuan->nominal,
                        "Penerimaan Angsuran: " . ($pengajuan->pinjaman->nasabah->user->nama ?? '-') . " (#{$pengajuan->id_pinjaman})",
                        $pengajuan->id, PettyCashConstants::REF_PINJAMAN_D, 'transfer',
                        \App\Services\PettyCashConstants::SUMBER_PINJAMAN
                    );
                }
            }

            // Update status pengajuan pembayaran menjadi disetujui
            $pengajuan->update([
                'status' => '3', // Disetujui
                'keterangan_admin' => $request->keterangan_admin,
                'tgl_pembayaran' => now(),
            ]);

            DB::commit();

            app(ActivityLogService::class)->logApprovePembayaranPinjaman($pengajuan->id, (float) $pengajuan->nominal, $pengajuan->nasabah->user->nama ?? 'N/A');

            NasabahNotification::notify(
                $pengajuan->id_anggota,
                'pinjaman_pembayaran',
                'Pembayaran angsuran disetujui',
                'Pembayaran angsuran Anda telah disetujui dan dicatat.',
                route('nasabah.pinjaman.detail-pembayaran', $pengajuan->id),
                (string) $pengajuan->id,
                'pengajuan_pembayaran_pinjaman'
            );

            return redirect()->route('admin.pinjaman.pembayaran')
                ->with('success', 'Pengajuan pembayaran berhasil disetujui dan angsuran diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
```
</details>

### Route: `admin/pinjaman/pembayaran/{id}/reject` [POST]
**Function:** `rejectPembayaran`

**Queries Detected:**
- Model: PengajuanPembayaranPinjaman
- Model: ActivityLogService
- Model: NasabahNotification

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function rejectPembayaran(Request $request, $id)
    {
        $request->validate([
            'keterangan_admin' => 'required|string|max:500',
        ]);

        $pengajuan = PengajuanPembayaranPinjaman::findOrFail($id);

        if ($pengajuan->status !== '1') {
            return redirect()->back()
                ->with('error', 'Status pengajuan tidak valid untuk ditolak');
        }

        $pengajuan->update([
            'status' => '2', // Ditolak
            'keterangan_admin' => $request->keterangan_admin,
        ]);

        app(ActivityLogService::class)->logRejectPembayaranPinjaman($pengajuan->id, (float) $pengajuan->nominal, $pengajuan->nasabah->user->nama ?? 'N/A', $request->keterangan_admin);

        NasabahNotification::notify(
            $pengajuan->id_anggota,
            'pinjaman_pembayaran',
            'Pembayaran angsuran ditolak',
            'Pengajuan pembayaran angsuran Anda ditolak. ' . ($request->keterangan_admin ?? ''),
            route('nasabah.pinjaman.detail-pembayaran', $pengajuan->id),
            (string) $pengajuan->id,
            'pengajuan_pembayaran_pinjaman'
        );

        return redirect()->route('admin.pinjaman.pembayaran')
            ->with('success', 'Pengajuan pembayaran ditolak');
    }
```
</details>

### Route: `admin/pinjaman/pembayaran/{id}/konfirmasi` [POST]
**Function:** `konfirmasiPembayaran`

**Queries Detected:**
- Model: PengajuanPembayaranPinjaman
- Model: TempoPinjamanB
- Model: TempoPinjamanM
- Model: IdGenerator
- Model: BuktiFoto
- Model: ActivityLogService

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function konfirmasiPembayaran(Request $request, $id)
    {
        $pengajuan = PengajuanPembayaranPinjaman::with(['pinjaman'])->findOrFail($id);

        if ($pengajuan->status !== '3') {
            return redirect()->back()
                ->with('error', 'Pembayaran harus disetujui terlebih dahulu');
        }

        try {
            DB::beginTransaction();

            // Get angsuran
            $angsuran = null;
            if ($pengajuan->jenis_tempo === 'bulanan') {
                $angsuran = TempoPinjamanB::where('id', $pengajuan->tempo_id)->first();
            } else {
                $angsuran = TempoPinjamanM::where('id', $pengajuan->tempo_id)->first();
            }

            if (!$angsuran) {
                return redirect()->back()
                    ->with('error', 'Data angsuran tidak ditemukan');
            }

            $pinjaman = $pengajuan->pinjaman;

            // Update angsuran dengan pembayaran menggunakan method terpusat (Anti-Duplikasi)
            $angsuran->applyPayment($pengajuan->nominal);

            // Jika transfer, upload bukti bisa dilakukan disini
            if ($request->hasFile('bukti_transfer')) {
                $file = $request->file('bukti_transfer');
                $path = $file->store('bukti-pembayaran-pinjaman', 'public');
                
                // Generate ID for bukti foto: P (pinjaman) + TF (transfer) + PMB (pembayaran)
                $idBuktiFoto = IdGenerator::generate('tbl_bukti_foto', 'P', 'TF', 'PMB');
                
                BuktiFoto::create([
                    'id' => $idBuktiFoto,
                    'owner_id' => $pengajuan->id,
                    'owner_fitur' => 'P', // Pinjaman
                    'owner_trans' => 'PMB', // Pembayaran
                    'file_path' => $path,
                    'keterangan' => $request->keterangan,
                ]);
            }

            // Update status pengajuan menjadi terlaksana
            $pengajuan->update([
                'status' => '4', // Terlaksana
                'tgl_pembayaran' => now(),
            ]);

            // Pelunasan pinjaman induk sudah dihandle oleh applyPayment() di dalam model
            // Jadi block check lunas manual disini bisa dihapus (Anti-Duplikasi)

            DB::commit();

            app(ActivityLogService::class)->logKonfirmasiPembayaranPinjaman($id, (float) $pengajuan->nominal, $pengajuan->nasabah->user->nama ?? 'N/A');

            return redirect()->route('admin.pinjaman.pembayaran')
                ->with('success', 'Pembayaran berhasil dikonfirmasi dan angsuran diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
```
</details>

### Route: `admin/pinjaman/pembayaran/{id}/upload-serah-terima` [POST]
**Function:** `uploadSerahTerima`

**Queries Detected:**
- Model: PengajuanPembayaranPinjaman
- Model: IdGenerator
- Model: BuktiFoto
- Model: TempoPinjamanB
- Model: TempoPinjamanM
- Model: PettyCashTransaksiNasabah
- Model: PettyCashConstants
- Model: PettyCashSaldo
- Model: ActivityLogService

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function uploadSerahTerima(Request $request, $id)
    {
        $request->validate([
            'foto_serah_terima' => 'required|image|max:5120',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $pengajuan = PengajuanPembayaranPinjaman::findOrFail($id);

        // Tunai/janji temu: boleh upload langsung (status 1) atau setelah setujui (status 3)
        $isTunai = ($pengajuan->metode_pembayaran ?? '') === 'tunai' || (!$pengajuan->rekening_tujuan && $pengajuan->janjiTemu);
        if (!in_array($pengajuan->status, ['1', '3'])) {
            return redirect()->back()
                ->with('error', 'Status pembayaran tidak valid untuk upload bukti.');
        }
        if ($pengajuan->status === '1' && !$isTunai) {
            return redirect()->back()
                ->with('error', 'Pembayaran transfer harus disetujui terlebih dahulu.');
        }

        try {
            DB::beginTransaction();

            // Upload foto serah terima
            $file = $request->file('foto_serah_terima');
            $path = $file->store('bukti-pembayaran-pinjaman', 'public');
            
            // Generate ID for bukti foto: P (pinjaman) + CS (cash) + PMB (pembayaran)
            $idBuktiFoto = IdGenerator::generate('tbl_bukti_foto', 'P', 'CS', 'PMB');
            
            BuktiFoto::create([
                'id' => $idBuktiFoto,
                'owner_id' => $pengajuan->id,
                'owner_fitur' => 'P', // Pinjaman
                'owner_trans' => 'PMB', // Pembayaran
                'file_path' => $path,
                'keterangan' => $request->keterangan,
            ]);

            // Get angsuran dan update
            $angsuran = null;
            if ($pengajuan->jenis_tempo === 'bulanan') {
                $angsuran = TempoPinjamanB::where('id', $pengajuan->tempo_id)->first();
            } else {
                $angsuran = TempoPinjamanM::where('id', $pengajuan->tempo_id)->first();
            }

            if ($angsuran) {
                // Update angsuran dengan pembayaran menggunakan method terpusat (Anti-Duplikasi)
                $angsuran->applyPayment($pengajuan->nominal);
            }

            // Update status pengajuan menjadi terlaksana
            $pengajuan->update([
                'status' => '4', // Terlaksana
                'tgl_pembayaran' => now(),
                'setoran_kantor_id' => null, // Prepare for petty cash
            ]);

            // Cek transaksi petty cash (hindari duplikasi jika entah kenapa panggil 2x)
            $existingPc = \App\Models\PettyCashTransaksiNasabah::where('ref_table', 'tbl_pengajuan_pembayaran_pinjaman')
                ->where('ref_id', $pengajuan->id)
                ->first();

            if (!$existingPc) {
                // Catat ke Petty Cash Admin (Cash Fisik)
                $pettyId = \App\Helpers\IdGenerator::generate('petty_cash_transaksi_nasabah', 'PC', 'AN', 'P', now());
                
                \App\Models\PettyCashTransaksiNasabah::create([
                    'id' => $pettyId,
                    'admin_id' => Auth::id(),
                    'nasabah_id' => $pengajuan->id_anggota,
                    'nominal' => (float) $pengajuan->nominal,
                    'id_jns_transaksi' => PettyCashConstants::JNS_PMB,
                    'id_jns_via' => PettyCashConstants::VIA_CS,
                    'id_jns_fitur' => PettyCashConstants::FITUR_PINJAMAN, // Pinjaman
                    'keterangan' => 'Angsuran Pinjaman (Janji Temu) #' . $pengajuan->pinjaman->id,
                    'ref_table' => PettyCashConstants::REF_PINJAMAN_D,
                    'ref_id' => $pengajuan->id,
                    'status' => 'approved',
                    'tgl_transaksi' => now(),
                ]);
                
                \App\Models\PettyCashSaldo::updateSaldo(Auth::id(), 'cash', (float) $pengajuan->nominal, $pettyId, 'Angsuran Masuk (JT)', 'petty_cash_transaksi_nasabah');
            }

            // Update janji temu pembayaran jika ada (status selesai, keterangan_admin)
            $janjiTemu = $pengajuan->janjiTemu;
            if ($janjiTemu) {
                $janjiTemu->update([
                    'status' => '2',
                    'keterangan_admin' => $request->keterangan,
                ]);
            }

            DB::commit();

            $pengajuan->load('nasabah.user');
            app(ActivityLogService::class)->logProsesJanjiTemuPembayaranPinjaman($pengajuan->id, (float) $pengajuan->nominal, $pengajuan->nasabah->user->nama ?? 'N/A');

            return redirect()->route('admin.pinjaman.pembayaran')
                ->with('success', 'Foto serah terima berhasil diupload dan pembayaran dikonfirmasi');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
```
</details>

### Route: `admin/pinjaman/pinjaman-aktif/{id}/pelunasan-dipercepat` [POST]
**Function:** `pelunasanDipercepat`

**Queries Detected:**
- Model: AdminPermissionService
- Model: PinjamanH
- Model: IdGenerator
- Model: BuktiFoto
- Model: ActivityLogService

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function pelunasanDipercepat(Request $request, $id)
    {
        // Authorization: Only Admin Utama can do pelunasan dipercepat
        if (!app(\App\Services\AdminPermissionService::class)->canPelunasanDipercepat(Auth::user())) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk fitur ini. Hanya Admin Utama yang dapat melakukan pelunasan dipercepat.'
            ], 403);
        }

        $request->validate([
            'potongan' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string|max:500',
            'bukti_foto' => 'required|image|max:10240',
        ]);

        $pinjaman = PinjamanH::with(['tempoBulanan', 'tempoMingguan'])
            ->findOrFail($id);

        // Cek apakah pinjaman sudah lunas
        if ($pinjaman->lunas === 'lunas') {
            return redirect()->back()
                ->with('error', 'Pinjaman ini sudah lunas');
        }

        // Hitung sisa tagihan
        // Sistem bunga di awal: jumlah_pinjam sudah dikurangi bunga_rp
        // Total tagihan = nominal = jumlah_pinjam + bunga_rp
        $totalTagihan = $pinjaman->jumlah_pinjam + $pinjaman->bunga_rp;
        $totalTerbayar = $pinjaman->jenis === 'bulanan' 
            ? $pinjaman->tempoBulanan->sum('jumlah_terbayar')
            : $pinjaman->tempoMingguan->sum('jumlah_terbayar');
        
        $sisaTagihanPokok = $totalTagihan - $totalTerbayar;

        // Hitung total denda dari semua angsuran yang belum lunas
        $totalDenda = 0;
        $angsuranBelumLunas = $pinjaman->jenis === 'bulanan' 
            ? $pinjaman->tempoBulanan()->where('status_bayar', '!=', 'lunas')->get()
            : $pinjaman->tempoMingguan()->where('status_bayar', '!=', 'lunas')->get();

        foreach ($angsuranBelumLunas as $a) {
            $denda = $a->hitungDenda();
            $totalDenda += $denda;
        }

        // Hitung potongan (opsional)
        $potongan = $request->potongan ?? 0;
        $jumlahBayar = $sisaTagihanPokok + $totalDenda - $potongan;

        // Update semua angsuran yang belum lunas
        foreach ($angsuranBelumLunas as $a) {
            $denda = $a->hitungDenda();
            $totalPerAngsuran = $a->jumlah_tagihan + $denda;
            $sisaHarusDibayar = $totalPerAngsuran - ($a->jumlah_terbayar ?? 0);
            
            // Gunakan method terpusat (Anti-Duplikasi)
            $a->applyPayment($sisaHarusDibayar);
        }

        // Upload Bukti Foto
        if ($request->hasFile('bukti_foto')) {
            $file = $request->file('bukti_foto');
            $path = $file->store('bukti_pelunasan_dipercepat', 'public');
            
            $idBuktiFoto = IdGenerator::generate('tbl_bukti_foto', 'P', 'CS', 'LUNAS');
            BuktiFoto::create([
                'id' => $idBuktiFoto,
                'owner_id' => $pinjaman->id,
                'owner_fitur' => 'P', // Pinjaman
                'owner_trans' => 'LUNAS', // Pelunasan Dipercepat
                'file_path' => $path,
            ]);
        }

        app(ActivityLogService::class)->logPelunasanDipercepat($pinjaman->id, $jumlahBayar, $pinjaman->nasabah->user->nama ?? 'N/A');

        return redirect()->route('admin.pinjaman.detail-pinjaman', $pinjaman->id)
            ->with('success', 'Pinjaman berhasil dilunasi dipercepat. Total pembayaran: Rp ' . number_format($jumlahBayar, 0, ',', '.'));
    }
```
</details>

### Route: `admin/pinjaman/janji-temu/proses-pinjaman/{id}` [POST]
**Function:** `prosesJanjiTemuPinjaman`

**Queries Detected:**
- Model: JanjiTemuPinjaman
- Model: PengajuanPinjaman
- Model: IdGenerator
- Model: BuktiFoto
- Model: MasterBungaPinjaman
- Model: MasterDendaPinjaman
- Model: PinjamanH
- Model: TempoPinjamanB

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function prosesJanjiTemuPinjaman(Request $request, $id)
    {
        $request->validate([
            'tgl_cair' => 'required|date',
            'keterangan_admin' => 'nullable|string|max:500',
            'bukti_transfer' => 'nullable|image|max:5120',
        ]);

        $janjiTemu = JanjiTemuPinjaman::findOrFail($id);

        if ($janjiTemu->status == '2') {
            return redirect()->back()->with('error', 'Janji temu ini sudah diproses sebelumnya.');
        }

        if (!$janjiTemu->id_pengajuan) {
            return redirect()->back()->with('error', 'Janji temu ini belum terhubung ke pengajuan pinjaman.');
        }

        $pengajuan = PengajuanPinjaman::with('pinjaman')->find($janjiTemu->id_pengajuan);
        if (!$pengajuan) {
            return redirect()->back()->with('error', 'Data pengajuan tidak ditemukan.');
        }

        try {
            DB::beginTransaction();

            // 1. Update janji temu (keterangan + status selesai)
            $janjiTemu->update([
                'status' => '2',
                'keterangan_admin' => $request->keterangan_admin,
            ]);

            // 2. Bukti foto: simpan dengan owner = janji temu (mirip tabungan)
            if ($request->hasFile('bukti_transfer')) {
                $file = $request->file('bukti_transfer');
                $path = $file->store('bukti-pencairan-pinjaman', 'public');
                $idBuktiFoto = IdGenerator::generate('tbl_bukti_foto', 'P', 'TN', 'PNCR', $request->tgl_cair);
                BuktiFoto::create([
                    'id' => $idBuktiFoto,
                    'owner_id' => $janjiTemu->id,
                    'owner_fitur' => 'P',
                    'owner_trans' => 'PNCR',
                    'file_path' => $path,
                    'keterangan' => 'Bukti pencairan pinjaman (janji temu tunai)',
                ]);
            }

            // 3. Jika pengajuan masih pending (1): setujui dulu (buat pinjaman header)
            if ($pengajuan->status === '1') {
                $masterBunga = MasterBungaPinjaman::getBungaByDurasi($pengajuan->durasi);
                $masterDenda = MasterDendaPinjaman::getDendaAktif();
                if (!$masterBunga || !$masterDenda) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Master bunga/denda belum diatur.');
                }
                // Hitung bunga asli (persentase dari nominal)
                $nominal = (float) $pengajuan->nominal;
                $durasi = (int) $pengajuan->durasi;
                $bungaPersen = $masterBunga->bunga_persen;
                
                $bungaRp = ($nominal * $bungaPersen) / 100;
                $totalKewajiban = $nominal + $bungaRp;
                
                $angsuranRaw = $totalKewajiban / $durasi;
                
                // Bulatkan angsuran per bulan ke bawah ke kelipatan 1.000
                $angsuranBulanan = (int) floor($angsuranRaw / 1000) * 1000;
                if ($angsuranBulanan == 0 && $totalKewajiban > 0) {
                    $angsuranBulanan = (int) floor($totalKewajiban / $durasi);
                }

                $kodeVia = 'TN';
                $idPinjaman = IdGenerator::generate('tbl_pinjaman_h', 'P', $kodeVia, 'DPNJM', now());
                PinjamanH::create([
                    'id' => $idPinjaman,
                    'id_anggota' => $pengajuan->id_anggota,
                    'id_pengajuan' => $pengajuan->id,
                    'jumlah_pinjam' => $nominal,
                    'lama_pinjam' => $durasi,
                    'ags_bulan' => $angsuranBulanan,
                    'jenis' => 'bulanan',
                    'bunga' => $bungaPersen,
                    'bunga_rp' => $bungaRp,
                    'denda_persen' => $masterDenda->denda_persen,
                    'tgl_pinjam' => $request->tgl_cair,
                    'lunas' => 'belum',
                ]);
                $pengajuan->update([
                    'status' => '3',
                    'bunga_persen' => $bungaPersen,
                    'keterangan_admin' => $request->keterangan_admin,
                ]);
                $pengajuan->load('pinjaman');
            }

            $pinjaman = $pengajuan->pinjaman;
            if (!$pinjaman) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Data pinjaman belum dibuat.');
            }

            // 4. Cairkan: generate jadwal angsuran, update status 4
            if (TempoPinjamanB::where('pinjaman_id', $pinjaman->id)->exists()) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Pinjaman ini sudah dicairkan sebelumnya.');
            }

            $pinjaman->update(['tgl_pinjam' => $request->tgl_cair]);
            $this->generateJadwalAngsuran($pinjaman);
            $pengajuan->update([
                'status' => '4',
                'tgl_cair' => $request->tgl_cair,
            ]);

            DB::commit();

            return redirect()->route('admin.pinjaman.janji-temu.detail-pinjaman', $janjiTemu->id)
                ->with('success', 'Janji temu selesai. Pinjaman telah disetujui dan dicairkan; jadwal angsuran telah dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
```
</details>

## Controller: `App\Http\Controllers\Admin\JanjiTemuController`

**Models Imported:**
- `App\Models\JanjiTemuUniversal`
- `App\Models\JanjiTemuPinjaman`

### Route: `admin/pinjaman/janji-temu/detail-pinjaman/{id}` [GET|HEAD]
**Function:** `detailPinjaman`

**Queries Detected:**
- Model: JanjiTemuPinjaman
- Model: MasterBungaPinjaman
- Model: MasterDendaPinjaman

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function detailPinjaman($id)
    {
        $janjiTemu = JanjiTemuPinjaman::with(['nasabah.user', 'nasabah.dataKtp', 'lokasi', 'pengajuan', 'buktiFoto'])
            ->findOrFail($id);

        $masterBunga = null;
        $masterDenda = null;
        if ($janjiTemu->id_pengajuan && $janjiTemu->pengajuan) {
            $masterBunga = \App\Models\MasterBungaPinjaman::getBungaByDurasi($janjiTemu->pengajuan->durasi);
            $masterDenda = \App\Models\MasterDendaPinjaman::getDendaAktif();
        }

        return view('admin.janji-temu.detail-pinjaman', compact('janjiTemu', 'masterBunga', 'masterDenda'));
    }
```
</details>

### Route: `admin/janji-temu/tabungan/{id}/cancel` [POST]
**Function:** `cancelTabungan`

**Queries Detected:**
- Model: JanjiTemuTabungan

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function cancelTabungan(Request $request, $id)
    {
        $request->validate([
            'keterangan_admin' => 'required|string|max:255',
        ]);

        $janjiTemu = \App\Models\JanjiTemuTabungan::findOrFail($id);
        $janjiTemu->update([
            'status' => '3',
            'keterangan_admin' => $request->keterangan_admin,
        ]);

        return redirect()->back()->with('success', 'Janji temu tabungan berhasil dibatalkan.');
    }
```
</details>

### Route: `admin/janji-temu/pinjaman/{id}/cancel` [POST]
**Function:** `cancelPinjaman`

**Queries Detected:**
- Model: JanjiTemuPinjaman

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function cancelPinjaman(Request $request, $id)
    {
        $request->validate([
            'keterangan_admin' => 'required|string|max:255',
        ]);

        $janjiTemu = JanjiTemuPinjaman::findOrFail($id);
        $janjiTemu->update([
            'status' => '3',
            'keterangan_admin' => $request->keterangan_admin,
        ]);

        return redirect()->back()->with('success', 'Janji temu pinjaman berhasil dibatalkan.');
    }
```
</details>

## Controller: `App\Http\Controllers\Admin\MasterItemGadaiController`

**Models Imported:**
- `App\Models\GadaiMasterItem`
- `App\Models\GadaiMasterKategori`

### Route: `admin/master-data/item-gadai` [GET|HEAD]
**Function:** `index`

**Queries Detected:**
- Model: GadaiMasterItem

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function index()
    {
        $data = GadaiMasterItem::with('kategori')->paginate(15);
        return view('admin.master-data.item-gadai.index', compact('data'));
    }
```
</details>

### Route: `admin/master-data/item-gadai/create` [GET|HEAD]
**Function:** `create`

**Queries Detected:**
- Model: GadaiMasterKategori
- Model: GadaiMasterInapKendaraan

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function create()
    {
        $this->checkCrudPermission();
        $kategoris = GadaiMasterKategori::all();
        $inapKendaraans = \App\Models\GadaiMasterInapKendaraan::all();
        return view('admin.master-data.item-gadai.create', compact('kategoris', 'inapKendaraans'));
    }
```
</details>

### Route: `admin/master-data/item-gadai` [POST]
**Function:** `store`

**Queries Detected:**
- Model: GadaiMasterItem

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function store(Request $request)
    {
        $this->checkCrudPermission();
        $request->validate([
            'kategori_id' => 'required|exists:tbl_gadai_master_kategori,id',
            'head_1' => 'required|string|max:255',
            'head_2' => 'nullable|string|max:255',
            'file_pic' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'nominal_real' => 'required|numeric|min:0',
            'bunga_low' => 'required|numeric|min:0',
            'nominal_low' => 'required|numeric|min:0',
            'bunga_high' => 'required|numeric|min:0',
            'nominal_high' => 'required|numeric|min:0',
            'nominal_inap' => 'nullable|numeric|min:0',
            'is_active' => 'required|boolean',
        ]);

        $data = $request->except('file_pic');
        
        if ($request->hasFile('file_pic')) {
            $path = $request->file('file_pic')->store('master-item', 'public');
            $data['file_pic'] = $path;
        }

        GadaiMasterItem::create($data);

        return redirect()->route('admin.master-data.item-gadai.index')
            ->with('success', 'Item Gadai berhasil ditambahkan');
    }
```
</details>

### Route: `admin/master-data/item-gadai/{id}/edit` [GET|HEAD]
**Function:** `edit`

**Queries Detected:**
- Model: GadaiMasterItem
- Model: GadaiMasterKategori
- Model: GadaiMasterInapKendaraan

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function edit($id)
    {
        $this->checkCrudPermission();
        $data = GadaiMasterItem::findOrFail($id);
        $kategoris = GadaiMasterKategori::all();
        $inapKendaraans = \App\Models\GadaiMasterInapKendaraan::all();
        return view('admin.master-data.item-gadai.edit', compact('data', 'kategoris', 'inapKendaraans'));
    }
```
</details>

### Route: `admin/master-data/item-gadai/{id}` [PUT]
**Function:** `update`

**Queries Detected:**
- Model: GadaiMasterItem
- Model: Storage

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function update(Request $request, $id)
    {
        $this->checkCrudPermission();
        $request->validate([
            'kategori_id' => 'required|exists:tbl_gadai_master_kategori,id',
            'head_1' => 'required|string|max:255',
            'head_2' => 'nullable|string|max:255',
            'file_pic' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'nominal_real' => 'required|numeric|min:0',
            'bunga_low' => 'required|numeric|min:0',
            'nominal_low' => 'required|numeric|min:0',
            'bunga_high' => 'required|numeric|min:0',
            'nominal_high' => 'required|numeric|min:0',
            'nominal_inap' => 'nullable|numeric|min:0',
            'is_active' => 'required|boolean',
        ]);

        $data = GadaiMasterItem::findOrFail($id);
        $updateData = $request->except('file_pic');

        if ($request->hasFile('file_pic')) {
            // Hapus foto lama jika ada
            if ($data->file_pic) {
                Storage::disk('public')->delete($data->file_pic);
            }
            $path = $request->file('file_pic')->store('master-item', 'public');
            $updateData['file_pic'] = $path;
        }

        $data->update($updateData);

        return redirect()->route('admin.master-data.item-gadai.index')
            ->with('success', 'Item Gadai berhasil diupdate');
    }
```
</details>

### Route: `admin/master-data/item-gadai/{id}` [DELETE]
**Function:** `destroy`

**Queries Detected:**
- Model: GadaiMasterItem

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function destroy($id)
    {
        $this->checkCrudPermission();
        $data = GadaiMasterItem::findOrFail($id);
        $data->delete();

        return redirect()->route('admin.master-data.item-gadai.index')
            ->with('success', 'Item Gadai berhasil dihapus');
    }
```
</details>

## Controller: `App\Http\Controllers\Admin\MasterDataController`

**Models Imported:**
- `App\Models\MasterBungaPinjaman`
- `App\Models\MasterDendaPinjaman`
- `App\Models\SukuBunga`
- `App\Models\JnsTenorDeposito`
- `App\Models\SukuBungaDeposito`
- `App\Models\MBarangGadai`
- `App\Models\JnsLokasiPerusahaan`
- `App\Models\AdminOperasional`
- `App\Models\JnsBank`
- `App\Models\LogoBank`
- `App\Models\User`

### Route: `admin/master-data/bunga-pinjaman` [GET|HEAD]
**Function:** `bungaPinjamanIndex`

**Queries Detected:**
- Model: MasterBungaPinjaman

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function bungaPinjamanIndex()
    {
        $data = MasterBungaPinjaman::orderBy('durasi_min')->paginate(15);
        return view('admin.master-data.bunga-pinjaman.index', compact('data'));
    }
```
</details>

### Route: `admin/master-data/bunga-pinjaman/create` [GET|HEAD]
**Function:** `bungaPinjamanCreate`

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function bungaPinjamanCreate()
    {
        $this->checkCrudPermission();
        return view('admin.master-data.bunga-pinjaman.create');
    }
```
</details>

### Route: `admin/master-data/bunga-pinjaman` [POST]
**Function:** `bungaPinjamanStore`

**Queries Detected:**
- Model: MasterBungaPinjaman

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function bungaPinjamanStore(Request $request)
    {
        $this->checkCrudPermission();
        $request->validate([
            'durasi_min' => 'required|integer|min:1',
            'durasi_max' => 'required|integer|min:1|gte:durasi_min',
            'bunga_persen' => 'required|numeric|min:0|max:100',
            'keterangan' => 'nullable|string|max:500',
        ]);

        MasterBungaPinjaman::create($request->all());

        return redirect()->route('admin.master-data.bunga-pinjaman.index')
            ->with('success', 'Data berhasil ditambahkan');
    }
```
</details>

### Route: `admin/master-data/bunga-pinjaman/{id}/edit` [GET|HEAD]
**Function:** `bungaPinjamanEdit`

**Queries Detected:**
- Model: MasterBungaPinjaman

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function bungaPinjamanEdit($id)
    {
        $this->checkCrudPermission();
        $data = MasterBungaPinjaman::findOrFail($id);
        return view('admin.master-data.bunga-pinjaman.edit', compact('data'));
    }
```
</details>

### Route: `admin/master-data/bunga-pinjaman/{id}` [PUT]
**Function:** `bungaPinjamanUpdate`

**Queries Detected:**
- Model: MasterBungaPinjaman

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function bungaPinjamanUpdate(Request $request, $id)
    {
        $this->checkCrudPermission();
        $request->validate([
            'durasi_min' => 'required|integer|min:1',
            'durasi_max' => 'required|integer|min:1|gte:durasi_min',
            'bunga_persen' => 'required|numeric|min:0|max:100',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $data = MasterBungaPinjaman::findOrFail($id);
        $data->update($request->all());

        return redirect()->route('admin.master-data.bunga-pinjaman.index')
            ->with('success', 'Data berhasil diupdate');
    }
```
</details>

### Route: `admin/master-data/bunga-pinjaman/{id}` [DELETE]
**Function:** `bungaPinjamanDestroy`

**Queries Detected:**
- Model: MasterBungaPinjaman

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function bungaPinjamanDestroy($id)
    {
        $data = MasterBungaPinjaman::findOrFail($id);
        $data->delete();

        return redirect()->route('admin.master-data.bunga-pinjaman.index')
            ->with('success', 'Data berhasil dihapus');
    }
```
</details>

### Route: `admin/master-data/bunga-pinjaman/{id}/toggle-status` [POST]
**Function:** `bungaPinjamanToggleStatus`

**Queries Detected:**
- Model: MasterBungaPinjaman

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function bungaPinjamanToggleStatus($id)
    {
        $this->checkCrudPermission();
        $data = MasterBungaPinjaman::findOrFail($id);
        $data->status_aktif = !$data->status_aktif;
        $data->save();

        return redirect()->back()->with('success', 'Status berhasil diubah');
    }
```
</details>

### Route: `admin/master-data/denda-pinjaman` [GET|HEAD]
**Function:** `dendaPinjamanIndex`

**Queries Detected:**
- Model: MasterDendaPinjaman

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function dendaPinjamanIndex()
    {
        $data = MasterDendaPinjaman::paginate(15);
        return view('admin.master-data.denda-pinjaman.index', compact('data'));
    }
```
</details>

### Route: `admin/master-data/denda-pinjaman/create` [GET|HEAD]
**Function:** `dendaPinjamanCreate`

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function dendaPinjamanCreate()
    {
        $this->checkCrudPermission();
        return view('admin.master-data.denda-pinjaman.create');
    }
```
</details>

### Route: `admin/master-data/denda-pinjaman` [POST]
**Function:** `dendaPinjamanStore`

**Queries Detected:**
- Model: MasterDendaPinjaman

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function dendaPinjamanStore(Request $request)
    {
        $this->checkCrudPermission();
        $request->validate([
            'denda_persen' => 'required|numeric|min:0|max:100',
            'keterangan' => 'nullable|string|max:500',
        ]);

        MasterDendaPinjaman::create($request->all());

        return redirect()->route('admin.master-data.denda-pinjaman.index')
            ->with('success', 'Data berhasil ditambahkan');
    }
```
</details>

### Route: `admin/master-data/denda-pinjaman/{id}/edit` [GET|HEAD]
**Function:** `dendaPinjamanEdit`

**Queries Detected:**
- Model: MasterDendaPinjaman

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function dendaPinjamanEdit($id)
    {
        $this->checkCrudPermission();
        $data = MasterDendaPinjaman::findOrFail($id);
        return view('admin.master-data.denda-pinjaman.edit', compact('data'));
    }
```
</details>

### Route: `admin/master-data/denda-pinjaman/{id}` [PUT]
**Function:** `dendaPinjamanUpdate`

**Queries Detected:**
- Model: MasterDendaPinjaman

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function dendaPinjamanUpdate(Request $request, $id)
    {
        $this->checkCrudPermission();
        $request->validate([
            'denda_persen' => 'required|numeric|min:0|max:100',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $data = MasterDendaPinjaman::findOrFail($id);
        $data->update($request->all());

        return redirect()->route('admin.master-data.denda-pinjaman.index')
            ->with('success', 'Data berhasil diupdate');
    }
```
</details>

### Route: `admin/master-data/denda-pinjaman/{id}` [DELETE]
**Function:** `dendaPinjamanDestroy`

**Queries Detected:**
- Model: MasterDendaPinjaman

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function dendaPinjamanDestroy($id)
    {
        $data = MasterDendaPinjaman::findOrFail($id);
        $data->delete();

        return redirect()->route('admin.master-data.denda-pinjaman.index')
            ->with('success', 'Data berhasil dihapus');
    }
```
</details>

### Route: `admin/master-data/denda-pinjaman/{id}/toggle-status` [POST]
**Function:** `dendaPinjamanToggleStatus`

**Queries Detected:**
- Model: MasterDendaPinjaman

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function dendaPinjamanToggleStatus($id)
    {
        $this->checkCrudPermission();
        $data = MasterDendaPinjaman::findOrFail($id);
        $data->status_aktif = !$data->status_aktif;
        $data->save();

        return redirect()->back()->with('success', 'Status berhasil diubah');
    }
```
</details>

### Route: `admin/master-data/suku-bunga-tabungan` [GET|HEAD]
**Function:** `sukuBungaTabunganIndex`

**Queries Detected:**
- Model: SukuBunga

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function sukuBungaTabunganIndex()
    {
        $data = SukuBunga::orderBy('jenis_bunga')->paginate(15);
        return view('admin.master-data.suku-bunga-tabungan.index', compact('data'));
    }
```
</details>

### Route: `admin/master-data/suku-bunga-tabungan/create` [GET|HEAD]
**Function:** `sukuBungaTabunganCreate`

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function sukuBungaTabunganCreate()
    {
        $this->checkCrudPermission();
        return view('admin.master-data.suku-bunga-tabungan.create');
    }
```
</details>

### Route: `admin/master-data/suku-bunga-tabungan` [POST]
**Function:** `sukuBungaTabunganStore`

**Queries Detected:**
- Model: SukuBunga

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function sukuBungaTabunganStore(Request $request)
    {
        $this->checkCrudPermission();
        $request->validate([
            'jenis_bunga' => 'required|string|max:255',
            'opsi_val' => 'required|numeric|min:0|max:100',
        ]);

        SukuBunga::create($request->only(['jenis_bunga', 'opsi_val']));

        return redirect()->route('admin.master-data.suku-bunga-tabungan.index')
            ->with('success', 'Data berhasil ditambahkan');
    }
```
</details>

### Route: `admin/master-data/suku-bunga-tabungan/{id}/edit` [GET|HEAD]
**Function:** `sukuBungaTabunganEdit`

**Queries Detected:**
- Model: SukuBunga

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function sukuBungaTabunganEdit($id)
    {
        $data = SukuBunga::findOrFail($id);
        return view('admin.master-data.suku-bunga-tabungan.edit', compact('data'));
    }
```
</details>

### Route: `admin/master-data/suku-bunga-tabungan/{id}` [PUT]
**Function:** `sukuBungaTabunganUpdate`

**Queries Detected:**
- Model: SukuBunga

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function sukuBungaTabunganUpdate(Request $request, $id)
    {
        $request->validate([
            'jenis_bunga' => 'required|string|max:255',
            'opsi_val' => 'required|numeric|min:0|max:100',
        ]);

        $data = SukuBunga::findOrFail($id);
        $data->update($request->only(['jenis_bunga', 'opsi_val']));

        return redirect()->route('admin.master-data.suku-bunga-tabungan.index')
            ->with('success', 'Data berhasil diupdate');
    }
```
</details>

### Route: `admin/master-data/suku-bunga-tabungan/{id}` [DELETE]
**Function:** `sukuBungaTabunganDestroy`

**Queries Detected:**
- Model: SukuBunga

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function sukuBungaTabunganDestroy($id)
    {
        $data = SukuBunga::findOrFail($id);
        $data->delete();

        return redirect()->route('admin.master-data.suku-bunga-tabungan.index')
            ->with('success', 'Data berhasil dihapus');
    }
```
</details>

### Route: `admin/master-data/tenor-deposito` [GET|HEAD]
**Function:** `tenorDepositoIndex`

**Queries Detected:**
- Model: JnsTenorDeposito

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function tenorDepositoIndex()
    {
        $data = JnsTenorDeposito::with('sukuBunga')->orderBy('tenor_hari')->paginate(15);
        return view('admin.master-data.tenor-deposito.index', compact('data'));
    }
```
</details>

### Route: `admin/master-data/tenor-deposito/create` [GET|HEAD]
**Function:** `tenorDepositoCreate`

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function tenorDepositoCreate()
    {
        return view('admin.master-data.tenor-deposito.create');
    }
```
</details>

### Route: `admin/master-data/tenor-deposito` [POST]
**Function:** `tenorDepositoStore`

**Queries Detected:**
- Model: JnsTenorDeposito

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function tenorDepositoStore(Request $request)
    {
        $request->validate([
            'tenor_hari' => 'required|integer|min:1',
            'tenor_bulan' => 'required|integer|min:1',
        ]);

        JnsTenorDeposito::create($request->all());

        return redirect()->route('admin.master-data.tenor-deposito.index')
            ->with('success', 'Data berhasil ditambahkan');
    }
```
</details>

### Route: `admin/master-data/tenor-deposito/{id}/edit` [GET|HEAD]
**Function:** `tenorDepositoEdit`

**Queries Detected:**
- Model: JnsTenorDeposito

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function tenorDepositoEdit($id)
    {
        $data = JnsTenorDeposito::findOrFail($id);
        return view('admin.master-data.tenor-deposito.edit', compact('data'));
    }
```
</details>

### Route: `admin/master-data/tenor-deposito/{id}` [PUT]
**Function:** `tenorDepositoUpdate`

**Queries Detected:**
- Model: JnsTenorDeposito

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function tenorDepositoUpdate(Request $request, $id)
    {
        $request->validate([
            'tenor_hari' => 'required|integer|min:1',
            'tenor_bulan' => 'required|integer|min:1',
        ]);

        $data = JnsTenorDeposito::findOrFail($id);
        $data->update($request->all());

        return redirect()->route('admin.master-data.tenor-deposito.index')
            ->with('success', 'Data berhasil diupdate');
    }
```
</details>

### Route: `admin/master-data/tenor-deposito/{id}` [DELETE]
**Function:** `tenorDepositoDestroy`

**Queries Detected:**
- Model: JnsTenorDeposito

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function tenorDepositoDestroy($id)
    {
        $data = JnsTenorDeposito::findOrFail($id);
        
        // Check if tenor has bunga deposito
        if ($data->sukuBunga()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus tenor yang masih memiliki data suku bunga');
        }

        $data->delete();

        return redirect()->route('admin.master-data.tenor-deposito.index')
            ->with('success', 'Data berhasil dihapus');
    }
```
</details>

### Route: `admin/master-data/tenor-deposito/{id}/toggle-status` [POST]
**Function:** `tenorDepositoToggleStatus`

**Queries Detected:**
- Model: JnsTenorDeposito

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function tenorDepositoToggleStatus($id)
    {
        $data = JnsTenorDeposito::findOrFail($id);
        $data->aktif = !$data->aktif;
        $data->save();

        return redirect()->back()->with('success', 'Status berhasil diubah');
    }
```
</details>

### Route: `admin/master-data/suku-bunga-deposito` [GET|HEAD]
**Function:** `sukuBungaDepositoIndex`

**Queries Detected:**
- Model: SukuBungaDeposito

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function sukuBungaDepositoIndex()
    {
        $data = SukuBungaDeposito::with('tenor')->orderBy('tenor_id')->paginate(15);
        return view('admin.master-data.suku-bunga-deposito.index', compact('data'));
    }
```
</details>

### Route: `admin/master-data/suku-bunga-deposito/create` [GET|HEAD]
**Function:** `sukuBungaDepositoCreate`

**Queries Detected:**
- Model: JnsTenorDeposito

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function sukuBungaDepositoCreate()
    {
        $tenors = JnsTenorDeposito::where('aktif', true)->orderBy('tenor_hari')->get();
        return view('admin.master-data.suku-bunga-deposito.create', compact('tenors'));
    }
```
</details>

### Route: `admin/master-data/suku-bunga-deposito` [POST]
**Function:** `sukuBungaDepositoStore`

**Queries Detected:**
- Model: SukuBungaDeposito

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function sukuBungaDepositoStore(Request $request)
    {
        $request->validate([
            'tenor_id' => 'required|exists:jns_tenor_deposito,id',
            'min_nominal' => 'required|numeric|min:0',
            'max_nominal' => 'required|numeric|min:0|gte:min_nominal',
            'bunga' => 'required|numeric|min:0|max:100',
        ]);

        SukuBungaDeposito::create($request->all());

        return redirect()->route('admin.master-data.suku-bunga-deposito.index')
            ->with('success', 'Data berhasil ditambahkan');
    }
```
</details>

### Route: `admin/master-data/suku-bunga-deposito/{id}/edit` [GET|HEAD]
**Function:** `sukuBungaDepositoEdit`

**Queries Detected:**
- Model: SukuBungaDeposito
- Model: JnsTenorDeposito

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function sukuBungaDepositoEdit($id)
    {
        $data = SukuBungaDeposito::findOrFail($id);
        $tenors = JnsTenorDeposito::where('aktif', true)->orderBy('tenor_hari')->get();
        return view('admin.master-data.suku-bunga-deposito.edit', compact('data', 'tenors'));
    }
```
</details>

### Route: `admin/master-data/suku-bunga-deposito/{id}` [PUT]
**Function:** `sukuBungaDepositoUpdate`

**Queries Detected:**
- Model: SukuBungaDeposito

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function sukuBungaDepositoUpdate(Request $request, $id)
    {
        $request->validate([
            'tenor_id' => 'required|exists:jns_tenor_deposito,id',
            'min_nominal' => 'required|numeric|min:0',
            'max_nominal' => 'required|numeric|min:0|gte:min_nominal',
            'bunga' => 'required|numeric|min:0|max:100',
        ]);

        $data = SukuBungaDeposito::findOrFail($id);
        $data->update($request->all());

        return redirect()->route('admin.master-data.suku-bunga-deposito.index')
            ->with('success', 'Data berhasil diupdate');
    }
```
</details>

### Route: `admin/master-data/suku-bunga-deposito/{id}` [DELETE]
**Function:** `sukuBungaDepositoDestroy`

**Queries Detected:**
- Model: SukuBungaDeposito

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function sukuBungaDepositoDestroy($id)
    {
        $data = SukuBungaDeposito::findOrFail($id);
        $data->delete();

        return redirect()->route('admin.master-data.suku-bunga-deposito.index')
            ->with('success', 'Data berhasil dihapus');
    }
```
</details>

### Route: `admin/master-data/suku-bunga-deposito/{id}/toggle-status` [POST]
**Function:** `sukuBungaDepositoToggleStatus`

**Queries Detected:**
- Model: SukuBungaDeposito

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function sukuBungaDepositoToggleStatus($id)
    {
        $data = SukuBungaDeposito::findOrFail($id);
        $data->status = !$data->status;
        $data->save();

        return redirect()->back()->with('success', 'Status berhasil diubah');
    }
```
</details>

### Route: `admin/master-data/barang-gadai` [GET|HEAD]
**Function:** `barangGadaiIndex`

**Queries Detected:**
- Model: MBarangGadai

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function barangGadaiIndex()
    {
        $data = MBarangGadai::paginate(15);
        return view('admin.master-data.barang-gadai.index', compact('data'));
    }
```
</details>

### Route: `admin/master-data/barang-gadai/create` [GET|HEAD]
**Function:** `barangGadaiCreate`

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function barangGadaiCreate()
    {
        return view('admin.master-data.barang-gadai.create');
    }
```
</details>

### Route: `admin/master-data/barang-gadai` [POST]
**Function:** `barangGadaiStore`

**Queries Detected:**
- Model: MBarangGadai

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function barangGadaiStore(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        MBarangGadai::create($request->all());

        return redirect()->route('admin.master-data.barang-gadai.index')
            ->with('success', 'Data berhasil ditambahkan');
    }
```
</details>

### Route: `admin/master-data/barang-gadai/{id}/edit` [GET|HEAD]
**Function:** `barangGadaiEdit`

**Queries Detected:**
- Model: MBarangGadai

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function barangGadaiEdit($id)
    {
        $data = MBarangGadai::findOrFail($id);
        return view('admin.master-data.barang-gadai.edit', compact('data'));
    }
```
</details>

### Route: `admin/master-data/barang-gadai/{id}` [PUT]
**Function:** `barangGadaiUpdate`

**Queries Detected:**
- Model: MBarangGadai

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function barangGadaiUpdate(Request $request, $id)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $data = MBarangGadai::findOrFail($id);
        $data->update($request->all());

        return redirect()->route('admin.master-data.barang-gadai.index')
            ->with('success', 'Data berhasil diupdate');
    }
```
</details>

### Route: `admin/master-data/barang-gadai/{id}` [DELETE]
**Function:** `barangGadaiDestroy`

**Queries Detected:**
- Model: MBarangGadai

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function barangGadaiDestroy($id)
    {
        $data = MBarangGadai::findOrFail($id);
        
        // Check if barang has item gadai
        if ($data->itemGadai()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus barang yang masih digunakan');
        }

        $data->delete();

        return redirect()->route('admin.master-data.barang-gadai.index')
            ->with('success', 'Data berhasil dihapus');
    }
```
</details>

### Route: `admin/master-data/jenis-deposito` [GET|HEAD]
**Function:** `jenisDepositoIndex`

_Method not found in controller._

### Route: `admin/master-data/jenis-deposito/create` [GET|HEAD]
**Function:** `jenisDepositoCreate`

_Method not found in controller._

### Route: `admin/master-data/jenis-deposito` [POST]
**Function:** `jenisDepositoStore`

_Method not found in controller._

### Route: `admin/master-data/jenis-deposito/{id}/edit` [GET|HEAD]
**Function:** `jenisDepositoEdit`

_Method not found in controller._

### Route: `admin/master-data/jenis-deposito/{id}` [PUT]
**Function:** `jenisDepositoUpdate`

_Method not found in controller._

### Route: `admin/master-data/jenis-deposito/{id}` [DELETE]
**Function:** `jenisDepositoDestroy`

_Method not found in controller._

### Route: `admin/master-data/jenis-deposito/{id}/toggle-status` [POST]
**Function:** `jenisDepositoToggleStatus`

_Method not found in controller._

### Route: `admin/master-data/gadai-debugger` [GET|HEAD]
**Function:** `gadaiDebuggerIndex`

**Queries Detected:**
- Model: GadaiActive

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function gadaiDebuggerIndex()
    {
        $this->checkCrudPermission();
        $gadaiList = \App\Models\GadaiActive::with('item')->get();
        return view('admin.master-data.gadai-debugger.index', compact('gadaiList'));
    }
```
</details>

### Route: `admin/master-data/gadai-debugger/maju-hari` [POST]
**Function:** `gadaiDebuggerMajuHari`

**Queries Detected:**
- Table: tbl_gadai_active
- Model: Artisan

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function gadaiDebuggerMajuHari(Request $request)
    {
        $this->checkCrudPermission();
        $request->validate([
            'days' => 'required|integer|min:1|max:365'
        ]);

        $days = (int) $request->days;

        // Kurangi tanggal jatuh tempo, tenggang, dan mulai di semua gadai_active (mensimulasikan waktu maju)
        DB::table('tbl_gadai_active')->update([
            'tgl_mulai' => DB::raw("DATE_SUB(tgl_mulai, INTERVAL $days DAY)"),
            'tgl_jatuh_tempo' => DB::raw("DATE_SUB(tgl_jatuh_tempo, INTERVAL $days DAY)"),
            'tgl_tenggang' => DB::raw("DATE_SUB(tgl_tenggang, INTERVAL $days DAY)"),
        ]);

        // Jalankan artisan command untuk cek status secara paksa
        \Illuminate\Support\Facades\Artisan::call('gadai:check-status');
        $output = \Illuminate\Support\Facades\Artisan::output();

        return redirect()->route('admin.master-data.gadai-debugger.index')
            ->with('success', "Berhasil mensimulasikan waktu maju $days hari dan mengecek status Gadai!")
            ->with('output', $output);
    }
```
</details>

### Route: `admin/master-data/pinjaman-debugger` [GET|HEAD]
**Function:** `pinjamanDebuggerIndex`

**Queries Detected:**
- Model: PinjamanH

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function pinjamanDebuggerIndex()
    {
        $this->checkCrudPermission();
        // Load some active pinjaman data to show
        $pinjamanList = \App\Models\PinjamanH::where('lunas', 'belum')
            ->with(['nasabah.user', 'tempoBulanan', 'tempoMingguan'])
            ->latest()
            ->take(10)
            ->get();
        return view('admin.master-data.pinjaman-debugger.index', compact('pinjamanList'));
    }
```
</details>

### Route: `admin/master-data/pinjaman-debugger/maju-hari` [POST]
**Function:** `pinjamanDebuggerMajuHari`

**Queries Detected:**
- Table: tempo_pinjaman_b
- Table: tempo_pinjaman_m
- Model: Artisan

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function pinjamanDebuggerMajuHari(Request $request)
    {
        $this->checkCrudPermission();
        $request->validate([
            'days' => 'required|integer|min:1|max:365'
        ]);

        $days = (int) $request->days;

        // Kurangi tanggal jatuh tempo di angsuran bulanan
        DB::table('tempo_pinjaman_b')->update([
            'tgl_jatuh_tempo' => DB::raw("DATE_SUB(tgl_jatuh_tempo, INTERVAL $days DAY)"),
        ]);

        // Kurangi tanggal jatuh tempo di angsuran mingguan
        DB::table('tempo_pinjaman_m')->update([
            'tgl_jatuh_tempo' => DB::raw("DATE_SUB(tgl_jatuh_tempo, INTERVAL $days DAY)"),
        ]);

        // Jalankan Cron Job untuk meng-update denda di database seketika
        Artisan::call('pinjaman:update-telat-status');
        $output = Artisan::output();

        return redirect()->route('admin.master-data.pinjaman-debugger.index')
            ->with('success', "Berhasil memundurkan tanggal jatuh tempo angsuran pinjaman sebanyak $days hari dan denda telah di-generate ke database!")
            ->with('output', $output);
    }
```
</details>

### Route: `admin/master-data/deposito-debugger` [GET|HEAD]
**Function:** `depositoDebuggerIndex`

**Queries Detected:**
- Model: DepositoH

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function depositoDebuggerIndex()
    {
        $this->checkCrudPermission();
        $depositoList = \App\Models\DepositoH::where('status', 'aktif')
            ->with(['nasabah.user'])
            ->latest()
            ->take(10)
            ->get();
        return view('admin.master-data.deposito-debugger.index', compact('depositoList'));
    }
```
</details>

### Route: `admin/master-data/deposito-debugger/maju-hari` [POST]
**Function:** `depositoDebuggerMajuHari`

**Queries Detected:**
- Table: tbl_deposito_h
- Model: Artisan

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function depositoDebuggerMajuHari(Request $request)
    {
        $this->checkCrudPermission();
        $request->validate([
            'days' => 'required|integer|min:1|max:365'
        ]);

        $days = (int) $request->days;

        // Kurangi tanggal mulai dan jatuh tempo di deposito aktif
        DB::table('tbl_deposito_h')->update([
            'tgl_mulai'       => DB::raw("DATE_SUB(tgl_mulai, INTERVAL $days DAY)"),
            'tgl_jatuh_tempo' => DB::raw("DATE_SUB(tgl_jatuh_tempo, INTERVAL $days DAY)"),
        ]);

        // Jalankan Cron Job deposito
        Artisan::call('deposito:generate-peringatan');
        $output = Artisan::output();

        return redirect()->route('admin.master-data.deposito-debugger.index')
            ->with('success', "Berhasil memundurkan tanggal deposito sebanyak $days hari dan mengecek jatuh tempo!")
            ->with('output', $output);
    }
```
</details>

## Controller: `App\Http\Controllers\Admin\KategoriDepositoController`

**Models Imported:**
- `App\Models\KategoriDeposito`

### Route: `admin/master-data/kategori-deposito` [GET|HEAD]
**Function:** `index`

**Queries Detected:**
- Model: KategoriDeposito

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function index()
    {
        $kategoris = KategoriDeposito::latest()->get();
        return view('admin.master-data.kategori-deposito.index', compact('kategoris'));
    }
```
</details>

### Route: `admin/master-data/kategori-deposito/create` [GET|HEAD]
**Function:** `create`

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function create()
    {
        return view('admin.master-data.kategori-deposito.create');
    }
```
</details>

### Route: `admin/master-data/kategori-deposito` [POST]
**Function:** `store`

**Queries Detected:**
- Model: KategoriDeposito

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'keterangan'    => 'nullable|string',
            'status'        => 'required|in:aktif,nonaktif',
        ]);

        // 🛡️ Server-side guard: cegah duplikasi (double submit) dalam 10 detik
        $recentDuplicate = KategoriDeposito::where('nama_kategori', $request->nama_kategori)
            ->where('created_at', '>=', now()->subSeconds(10))
            ->exists();

        if ($recentDuplicate) {
            return redirect()->route('admin.master-data.kategori-deposito.index')
                             ->with('warning', 'Kategori tersebut sudah ditambahkan barusan. Silakan tunggu beberapa saat untuk menambahkan data yang sama.');
        }

        KategoriDeposito::create($validated);
        return redirect()->route('admin.master-data.kategori-deposito.index')
                         ->with('success', 'Kategori Deposito berhasil ditambahkan.');
    }
```
</details>

### Route: `admin/master-data/kategori-deposito/{id}/edit` [GET|HEAD]
**Function:** `edit`

**Queries Detected:**
- Model: KategoriDeposito

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function edit($id)
    {
        $kategori = KategoriDeposito::findOrFail($id);
        return view('admin.master-data.kategori-deposito.edit', compact('kategori'));
    }
```
</details>

### Route: `admin/master-data/kategori-deposito/{id}` [PUT]
**Function:** `update`

**Queries Detected:**
- Model: KategoriDeposito

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function update(Request $request, $id)
    {
        $kategori = KategoriDeposito::findOrFail($id);
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'keterangan'    => 'nullable|string',
            'status'        => 'required|in:aktif,nonaktif',
        ]);

        $kategori->update($validated);
        return redirect()->route('admin.master-data.kategori-deposito.index')
                         ->with('success', 'Kategori Deposito berhasil diperbarui.');
    }
```
</details>

### Route: `admin/master-data/kategori-deposito/{id}` [DELETE]
**Function:** `destroy`

**Queries Detected:**
- Model: KategoriDeposito

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function destroy($id)
    {
        $kategori = KategoriDeposito::findOrFail($id);
        $kategori->update(['status' => 'nonaktif']);
        return redirect()->route('admin.master-data.kategori-deposito.index')
                         ->with('success', 'Kategori Deposito berhasil dinonaktifkan.');
    }
```
</details>

## Controller: `App\Http\Controllers\Admin\MasterKategoriGadaiController`

**Models Imported:**
- `App\Models\GadaiMasterKategori`
- `App\Models\GadaiMasterInapKendaraan`

### Route: `admin/master-data/kategori-gadai` [GET|HEAD]
**Function:** `index`

**Queries Detected:**
- Model: GadaiMasterKategori
- Model: GadaiMasterInapKendaraan

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function index()
    {
        $data = GadaiMasterKategori::all();
        $inapKendaraans = GadaiMasterInapKendaraan::orderBy('golongan')->get();
        return view('admin.master-data.kategori-gadai.index', compact('data', 'inapKendaraans'));
    }
```
</details>

### Route: `admin/master-data/kategori-gadai/{id}/edit` [GET|HEAD]
**Function:** `edit`

**Queries Detected:**
- Model: GadaiMasterKategori
- Model: GadaiMasterInapKendaraan

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function edit($id)
    {
        $this->checkCrudPermission();
        $data = GadaiMasterKategori::findOrFail($id);
        $inapKendaraans = GadaiMasterInapKendaraan::orderBy('golongan')->get();
        return view('admin.master-data.kategori-gadai.edit', compact('data', 'inapKendaraans'));
    }
```
</details>

### Route: `admin/master-data/kategori-gadai/{id}` [PUT]
**Function:** `update`

**Queries Detected:**
- Model: GadaiMasterKategori
- Model: GadaiMasterInapKendaraan

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function update(Request $request, $id)
    {
        $this->checkCrudPermission();
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'rate_jasa' => 'required|numeric|min:0',
            'rate_denda' => 'required|numeric|min:0',
            'rate_inap_persen' => 'required|numeric|min:0',
            'masa_gadai_hari' => 'required|integer|min:1',
            'masa_tenggang_hari' => 'required|integer|min:1',
            'max_extend_default' => 'required|integer|min:0',
        ]);

        $data = GadaiMasterKategori::findOrFail($id);
        $data->update($request->all());

        // Update Master Inap Kendaraan if category is vehicle and present in request
        if ($request->has('update_inap_kendaraan') && $data->kode_kategori === 'vehicle') {
            $inapData = $request->input('inap', []);
            foreach ($inapData as $inapId => $fields) {
                $inapRecord = GadaiMasterInapKendaraan::find($inapId);
                if ($inapRecord) {
                    $inapRecord->update([
                        'jenis_kendaraan' => $fields['jenis_kendaraan'],
                        'nominal_inap' => $fields['nominal_inap'],
                        'keterangan' => $fields['keterangan'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('admin.master-data.kategori-gadai.index')
            ->with('success', 'Kategori Gadai berhasil diupdate');
    }
```
</details>

## Controller: `App\Http\Controllers\Admin\PettyCashController`

**Models Imported:**
- `App\Models\PettyCashPenerimaan`
- `App\Models\PettyCashTransaksiNasabah`
- `App\Models\PettyCashSetoranKantor`
- `App\Models\PettyCashSaldo`
- `App\Models\PettyCashLog`
- `App\Models\TransTabungan`
- `App\Models\Nasabah`
- `App\Models\User`
- `App\Models\PettyCashOwnerTransaksi`
- `App\Models\BuktiFoto`

### Route: `admin/petty-cash/penerimaan/{id}/approve-deposito` [POST]
**Function:** `approvePenerimaanDeposito`

**Queries Detected:**
- Model: PettyCashPenerimaan
- Model: PettyCashSaldo
- Model: PencairanDeposito
- Model: DepositoPersiapanCair
- Model: PettyCashLog

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function approvePenerimaanDeposito(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $penerimaan = PettyCashPenerimaan::where('admin_id', Auth::id())
                ->where('status', 'pending')
                ->where('sumber', 'deposito')
                ->findOrFail($id);

            $penerimaan->update([
                'status'           => 'approved',
                'keterangan_admin' => $request->keterangan_admin,
            ]);

            // Tambah saldo Admin (tunai yang diterima dari Owner)
            if ($penerimaan->nominal_cash > 0) {
                PettyCashSaldo::buatMutasi(
                    Auth::id(), 'admin', (float) $penerimaan->nominal_cash,
                    "Terima Dana Deposito dari Owner untuk Pencairan Tunai",
                    $penerimaan->id, 'petty_cash_penerimaan', 'cash'
                );
            }

            // Jika ada ref_id, update status penyiapan/pencairan
            if ($penerimaan->ref_id) {
                // Link ke PencairanDeposito
                \App\Models\PencairanDeposito::where('id', $penerimaan->ref_id)
                    ->where('status', 'pending')
                    ->update(['status' => 'diproses']);

                // Link ke DepositoPersiapanCair
                \App\Models\DepositoPersiapanCair::where('id', $penerimaan->ref_id)
                    ->where('status', 'tentatif')
                    ->update(['status' => 'diproses']);
            }

            PettyCashLog::catat(Auth::id(), 'approve_penerimaan_deposito', (float) $penerimaan->nominal_cash, [
                'penerimaan_id' => $id,
                'ref_id'        => $penerimaan->ref_id,
            ], $id, 'petty_cash_penerimaan');

            DB::commit();

            return back()->with('success', 'Dana deposito diterima. Silakan serahkan tunai ke nasabah.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('approvePenerimaanDeposito error', ['id' => $id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
```
</details>

### Route: `admin/deposito/pencairan-petty-cash/{id}/serahkan` [POST]
**Function:** `pencairanDepositoCash`

**Queries Detected:**
- Table: jns_fitur
- Table: jns_via
- Table: jns_transaksi
- Model: PencairanDeposito
- Model: PettyCashSaldo
- Model: IdGenerator
- Model: PettyCashTransaksiNasabah
- Model: TransDeposito
- Model: DepositoPersiapanCair
- Model: PettyCashLog
- Model: NasabahNotification

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function pencairanDepositoCash(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $pencairan = \App\Models\PencairanDeposito::with(['deposito', 'nasabah.user'])
                ->where('jenis_pencairan', 'petty_cash_operator')
                ->where('status', 'diproses')
                ->findOrFail($id);

            $nominal = (float) $pencairan->nominal_akhir;

            // Validasi saldo Admin mencukupi (MODAL AWAL)
            if (!PettyCashSaldo::validatePenarikan(Auth::id(), $nominal, 'cash', 'other')) {
                $saldoAdmin = PettyCashSaldo::getSaldo(Auth::id(), 'admin', 'cash', 'other');
                return back()->with('error',
                    'Saldo CASH MODAL AWAL Anda tidak mencukupi. Saldo: Rp ' . number_format($saldoAdmin, 0, ',', '.') .
                    ', Dibutuhkan: Rp ' . number_format($nominal, 0, ',', '.')
                );
            }

            // Ambil id fitur deposito
            $idJnsFitur = DB::table('jns_fitur')->where('kode', 'DP')->value('id')
                ?? DB::table('jns_fitur')->first()?->id;
            $idJnsVia   = DB::table('jns_via')->where('kode', 'CS')->value('id');
            $idJnsTrans = DB::table('jns_transaksi')->where('kode', 'PCR')->value('id')
                ?? DB::table('jns_transaksi')->where('kode', 'PNR')->value('id');

            $pctnId = \App\Helpers\IdGenerator::generate('petty_cash_transaksi_nasabah', 'T', 'CS', 'PCR');

            // Catat di PettyCashTransaksiNasabah (KELUAR dari Admin ke nasabah)
            PettyCashTransaksiNasabah::create([
                'id'               => $pctnId,
                'admin_id'         => Auth::id(),
                'nasabah_id'       => $pencairan->id_nasabah,
                'id_jns_transaksi' => $idJnsTrans,
                'id_jns_via'       => $idJnsVia,
                'id_jns_fitur'     => $idJnsFitur,
                'nominal'          => $nominal,
                'status'           => 'approved',
                'keterangan'       => 'Pencairan Deposito ' . $pencairan->deposito->nomor_deposito . ' - Tunai',
                'ref_table'        => 'tbl_pencairan_deposito',
                'ref_id'           => $pencairan->id,
                'tgl_transaksi'    => now(),
            ]);

            // Kurangi saldo Admin (tunai keluar ke nasabah) - Gunakan MODAL AWAL
            PettyCashSaldo::buatMutasi(
                Auth::id(), 'admin', -$nominal,
                'Pencairan Deposito ' . $pencairan->deposito->nomor_deposito . ' - Tunai ke Nasabah',
                $pctnId, 'petty_cash_transaksi_nasabah', 'cash', 'other'
            );

            // Catat di TransDeposito
            \App\Models\TransDeposito::create([
                'deposito_id'   => $pencairan->deposito_id,
                'jenis'         => 'pencairan',
                'nominal'       => $nominal,
                'keterangan'    => 'Pencairan via Petty Cash (Tunai) - ' . ($request->catatan ?? ''),
                'tgl_transaksi' => now(),
            ]);

            // Update PencairanDeposito → selesai
            $pencairan->update([
                'catatan'     => $request->catatan,
                'status'      => 'selesai',
                'approved_by' => Auth::id(),
            ]);

            // Update DepositoH → dicairkan
            $pencairan->deposito->update(['status' => 'dicairkan']);

            // Sinkronisasi deposito_persiapan_cair
            \App\Models\DepositoPersiapanCair::where('deposito_id', $pencairan->deposito_id)
                ->whereIn('status', ['tentatif', 'diproses'])
                ->update(['status' => 'selesai', 'pencairan_id' => $pencairan->id]);

            PettyCashLog::catat(Auth::id(), 'pencairan_deposito_cash', $nominal, [
                'pencairan_id' => $pencairan->id,
                'nasabah_id'   => $pencairan->id_nasabah,
            ], (string) $pencairan->id, 'tbl_pencairan_deposito');

            DB::commit();

            // Notifikasi nasabah
            \App\Models\NasabahNotification::notify(
                $pencairan->id_nasabah, 'deposito',
                'Deposito Anda Telah Dicairkan',
                'Deposito No. ' . $pencairan->deposito->nomor_deposito . ' senilai Rp ' .
                    number_format($nominal, 0, ',', '.') . ' telah diserahkan secara tunai.',
                route('nasabah.deposito.detail', $pencairan->deposito_id),
                (string) $pencairan->deposito_id, 'pencairan_deposito'
            );

            return redirect()->route('admin.deposito.pencairan-tf.index')
                ->with('success', 'Pencairan tunai deposito berhasil. Saldo Petty Cash Admin berkurang Rp ' . number_format($nominal, 0, ',', '.'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('pencairanDepositoCash error', ['id' => $id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
```
</details>

## Controller: `App\Http\Controllers\Admin\DepositoController`

**Models Imported:**
- `App\Models\PengajuanDeposito`
- `App\Models\DepositoH`
- `App\Models\DepositoPersiapanCair`
- `App\Models\JnsTenorDeposito`
- `App\Models\SukuBungaDeposito`
- `App\Models\PaketDeposito`
- `App\Models\Nasabah`
- `App\Models\NasabahNotification`
- `App\Models\TransTabungan`
- `App\Models\TransDeposito`
- `App\Models\PencairanDeposito`
- `App\Models\PettyCashPenerimaan`
- `App\Models\PettyCashOwnerTransaksi`
- `App\Models\PettyCashSaldo`
- `App\Models\User`
- `App\Models\PettyCashTransaksiNasabah`

### Route: `admin/deposito` [GET|HEAD]
**Function:** `index`

**Queries Detected:**
- Model: PengajuanDeposito
- Model: DepositoH
- Model: TransDeposito
- Model: PencairanDeposito

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function index()
    {
        $stats = [
            'pengajuan_pending'      => PengajuanDeposito::where('status', '1')->count(),
            'jatuh_tempo_bulan_ini'  => DepositoH::where('status', 'aktif')
                                        ->whereMonth('tgl_jatuh_tempo', now()->month)
                                        ->whereYear('tgl_jatuh_tempo', now()->year)->count(),
            'bunga_dibayar_bulan_ini'=> TransDeposito::where('jenis', 'pencairan_bunga')
                                        ->whereMonth('tgl_transaksi', now()->month)
                                        ->whereYear('tgl_transaksi', now()->year)->sum('nominal'),
            'total_deposito_aktif'   => DepositoH::where('status', 'aktif')->count(),
            'total_nominal_aktif'    => DepositoH::where('status', 'aktif')->sum('nominal_awal'),
            'pending_transfer'       => PengajuanDeposito::where('status', '1')->where('metode_setor', 'transfer')->count(),
            'pending_tabungan'       => PengajuanDeposito::where('status', '1')->where('metode_setor', 'saldo_tabungan')->count(),
            // Pencairan stats
            'pencairan_tf_pending'   => PencairanDeposito::where('jenis_pencairan', 'rek_nasabah')->where('status', 'pending')->count(),
            'pencairan_tab_pending'  => PencairanDeposito::where('jenis_pencairan', 'saldo_tabungan')->where('status', 'pending')->count(),
        ];

        $pengajuan_terbaru = PengajuanDeposito::with(['nasabah.user', 'tenor'])
            ->where('status', '1')->latest()->take(5)->get();

        $deposito_terbaru = DepositoH::with(['nasabah.user', 'tenor'])
            ->where('status', 'aktif')->latest()->take(5)->get();

        // Deposito jatuh tempo (pending pencairan)
        $jatuh_tempo = DepositoH::with(['nasabah.user', 'tenor'])
            ->where('status', 'aktif')
            ->where('tgl_jatuh_tempo', '<=', now())
            ->latest('tgl_jatuh_tempo')
            ->take(5)
            ->get();

        // Trend data for chart (6 months)
        $trend_data = [];
        $trend_labels = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $trend_labels[] = $date->translatedFormat('M Y');
            $trend_data[] = DepositoH::where('status', 'aktif')
                                     ->whereMonth('tgl_mulai', $date->month)
                                     ->whereYear('tgl_mulai', $date->year)
                                     ->sum('nominal_awal');
        }

        return view('admin.deposito.index', compact('stats', 'pengajuan_terbaru', 'deposito_terbaru', 'jatuh_tempo', 'trend_labels', 'trend_data'));
    }
```
</details>

### Route: `admin/deposito/pengajuan` [GET|HEAD]
**Function:** `pengajuanList`

**Queries Detected:**
- Model: PengajuanDeposito

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function pengajuanList(Request $request)
    {
        $query = PengajuanDeposito::with(['nasabah.user', 'tenor'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', '1');
        }

        if ($request->filled('metode')) {
            $query->where('metode_setor', $request->metode);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('nasabah.user', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $pengajuan = $query->paginate(15)->withQueryString();

        return view('admin.deposito.pengajuan-list', compact('pengajuan'));
    }
```
</details>

### Route: `admin/deposito/pengajuan/{id}` [GET|HEAD]
**Function:** `detailPengajuan`

**Queries Detected:**
- Model: PengajuanDeposito

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function detailPengajuan($id)
    {
        $pengajuan = PengajuanDeposito::with(['nasabah.user', 'nasabah.dataKtp', 'nasabah.dataRek', 'tenor'])
            ->findOrFail($id);

        return view('admin.deposito.detail-pengajuan', compact('pengajuan'));
    }
```
</details>

### Route: `admin/deposito/pengajuan/{id}/approve` [POST]
**Function:** `approve`

**Queries Detected:**
- Table: jns_via
- Table: jns_transaksi
- Model: PengajuanDeposito
- Model: SukuBungaDeposito
- Model: IdGenerator
- Model: TransTabungan
- Model: PettyCashTransaksiNasabah
- Model: PettyCashConstants
- Model: PettyCashSaldo
- Model: User
- Model: PettyCashOwnerTransaksi
- Model: DepositoH
- Model: TransDeposito
- Model: ActivityLogService
- Model: NasabahNotification

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function approve(Request $request, $id)
    {
        $this->checkDepositoPermission();

        try {
            DB::beginTransaction();

            $pengajuan = PengajuanDeposito::with(['nasabah', 'tenor'])->findOrFail($id);

            if ($pengajuan->status !== '1') {
                return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
            }

            $tenor  = $pengajuan->tenor;
            $nominal = (float) $pengajuan->nominal;

            // Cari suku bunga
            if ($pengajuan->paket_id && $pengajuan->paket) {
                // Gunakan suku bunga dari paket (PaketDeposito suku_bunga is in percentage, e.g. 5.25 for 5.25%)
                $bunga = (float) $pengajuan->paket->suku_bunga / 100;
            } else {
                // Fallback sistem lama
                $sukuBunga = SukuBungaDeposito::where('tenor_id', $tenor->id)
                    ->where('status', 'aktif')
                    ->where(fn($q) => $q->whereNull('min_nominal')->orWhere('min_nominal', '<=', $nominal))
                    ->where(fn($q) => $q->whereNull('max_nominal')->orWhere('max_nominal', '>=', $nominal))
                    ->orderBy('min_nominal')->first();

                $bungaFallback = [1 => 0.0375, 3 => 0.0450, 6 => 0.0525, 12 => 0.0600];
                $bunga = $sukuBunga ? (float) $sukuBunga->bunga : ($bungaFallback[$tenor->tenor_bulan] ?? 0.05);
            }

            if ($pengajuan->metode_setor === 'saldo_tabungan') {
                $nasabah = $pengajuan->nasabah;
                $saldo   = $this->getSaldoNasabah($nasabah->id);
                if ($saldo < $nominal) {
                    DB::rollBack();
                    return back()->with('error', 'Saldo tabungan nasabah tidak mencukupi (Rp ' . number_format($saldo, 0, ',', '.') . ').');
                }

                $idVia   = DB::table('jns_via')->where('kode', 'TF')->value('id');
                $idTrans = DB::table('jns_transaksi')->where('kode', 'PNR')->value('id');
                $idTransaksi = IdGenerator::generate('trans_tabungan', 'T', 'TF', 'PNR');

                TransTabungan::create([
                    'id'                 => $idTransaksi,
                    'id_anggota'         => $nasabah->id,
                    'id_jns_via'         => $idVia,
                    'id_jns_transaksi'   => $idTrans,
                    'nominal'            => $nominal,
                    'keterangan'         => 'Penempatan Deposito #' . $pengajuan->id,
                    'tgl_transaksi'      => now(),
                    'admin_pengelola_id' => auth()->id(),
                ]);
            }

            // 🔥 INTEGRASI PETTY CASH / OWNER LEDGER (Hanya untuk Transfer Bank)
            if ($pengajuan->metode_setor === 'transfer') {
                $metodeBayar = $request->metode_bayar ?? 'transfer_koperasi';
                $pettyId = null;

                if ($metodeBayar === 'transfer_admin' || $metodeBayar === 'cash') {
                    // Simpan ke Petty Cash Admin
                    $pettyId = IdGenerator::generate('petty_cash_transaksi_nasabah', 'P', 'CS', 'STR');
                    $idTransStr = DB::table('jns_transaksi')->where('kode', 'STR')->value('id');

                    PettyCashTransaksiNasabah::create([
                        'id'               => $pettyId,
                        'admin_id'         => auth()->id(),
                        'nasabah_id'       => $pengajuan->id_nasabah,
                        'id_jns_transaksi' => $idTransStr,
                        'id_jns_via'       => ($metodeBayar === 'cash') ? PettyCashConstants::VIA_CS : PettyCashConstants::VIA_TF,
                        'id_jns_fitur'     => PettyCashConstants::FITUR_DEPOSITO,
                        'nominal'          => $nominal,
                        'status'           => 'approved',
                        'keterangan'       => 'Otomatis dari Pengajuan Deposito #' . $pengajuan->id,
                        'ref_table'        => PettyCashConstants::REF_DEPOSITO_P,
                        'ref_id'           => (string)$pengajuan->id,
                        'tgl_transaksi'    => now(),
                    ]);

                    $pettyType = ($metodeBayar === 'cash') ? 'cash' : 'transfer';
                    PettyCashSaldo::updateOrCreateSaldo(
                        auth()->id(),
                        'admin',
                        $nominal,
                        $pettyId,
                        'Setoran Deposito dari Pengajuan #' . $pengajuan->id,
                        'petty_cash_transaksi_nasabah',
                        $pettyType,
                        'deposito'
                    );
                } else {
                    // Simpan ke Koperasi Utama (Owner Wallet)
                    $owner = User::where('role', 'admin_utama')->first();
                    if ($owner) {
                        PettyCashOwnerTransaksi::create([
                            'id'              => IdGenerator::generate('petty_cash_owner_transaksi', 'PCOW', 'OW', 'TR'),
                            'user_id'         => $owner->id,
                            'tipe'            => 'terima_setoran',
                            'sumber'          => PettyCashConstants::SUMBER_DEPOSITO,
                            'nominal_cash'    => 0,
                            'nominal_tf'      => $nominal,
                            'keterangan'      => "Setoran Deposito Nasabah: " . ($pengajuan->nasabah->user->nama ?? '-') . " (#{$pengajuan->id})",
                            'bukti_foto_tf'   => $pengajuan->foto_bukti_tf,
                            'ref_id'          => (string)$pengajuan->id,
                            'ref_table'       => PettyCashConstants::REF_DEPOSITO_P,
                        ]);

                        PettyCashSaldo::buatMutasi(
                            $owner->id, 'owner', $nominal,
                            "Setoran Deposito (#{$pengajuan->id})",
                            $pengajuan->id, 'tbl_pengajuan_deposito', 'transfer',
                            \App\Services\PettyCashConstants::SUMBER_DEPOSITO
                        );
                    }
                }
            }

            $tglMulai      = now();
            $tglJatuhTempo = now()->addDays($tenor->tenor_hari);

            $nomorDeposito = 'DP' . now()->format('ymd') . str_pad(
                DepositoH::whereDate('created_at', today())->count() + 1,
                4, '0', STR_PAD_LEFT
            );

            $deposito = DepositoH::create([
                'id_pengajuan'    => $pengajuan->id,
                'id_nasabah'      => $pengajuan->id_nasabah,
                'paket_id'        => $pengajuan->paket_id,
                'nomor_deposito'  => $nomorDeposito,
                'nominal_awal'    => $nominal,
                'tenor_id'        => $pengajuan->tenor_id,
                'bunga'           => $bunga,
                'tgl_mulai'       => $tglMulai,
                'tgl_jatuh_tempo' => $tglJatuhTempo,
                'metode_pencairan'=> 'pencairan_ke_rekening',
                'status'          => 'aktif',
            ]);

            TransDeposito::create([
                'deposito_id'   => $deposito->id,
                'jenis'         => 'setor_awal',
                'nominal'       => $nominal,
                'keterangan'    => 'Setoran awal deposito - ' . ucfirst(str_replace('_', ' ', $pengajuan->metode_setor)),
                'tgl_transaksi' => now(),
            ]);

            $pengajuan->update([
                'status'        => '2',
                'catatan_admin' => $request->catatan_admin ?? 'Pengajuan disetujui',
                'approved_by'   => auth()->id(),
            ]);

            DB::commit();

            app(\App\Services\ActivityLogService::class)->logApprovePengajuanDeposito((string)$pengajuan->id, (float)$nominal, $pengajuan->nasabah->user->nama ?? 'N/A');

            NasabahNotification::notify(
                $pengajuan->id_nasabah, 'deposito',
                'Pengajuan Deposito Disetujui',
                'Deposito Anda sebesar Rp ' . number_format($nominal, 0, ',', '.') . ' (' . $tenor->tenor_bulan . ' bulan) telah aktif! No. ' . $nomorDeposito,
                route('nasabah.deposito.detail', $deposito->id),
                (string) $pengajuan->id, 'pengajuan_deposito'
            );

            return redirect()->route('admin.deposito.pengajuan-list')
                ->with('success', 'Pengajuan deposito berhasil disetujui. Nomor Deposito: ' . $nomorDeposito);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Admin approve deposito error', ['id' => $id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
```
</details>

### Route: `admin/deposito/pengajuan/{id}/reject` [POST]
**Function:** `reject`

**Queries Detected:**
- Model: PengajuanDeposito
- Model: ActivityLogService
- Model: NasabahNotification

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function reject(Request $request, $id)
    {
        $this->checkDepositoPermission();

        $request->validate(['catatan_admin' => 'required|string|max:500']);

        $pengajuan = PengajuanDeposito::with('nasabah.user', 'tenor')->findOrFail($id);

        if ($pengajuan->status !== '1') {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $pengajuan->update(['status' => '3', 'catatan_admin' => $request->catatan_admin]);

        app(\App\Services\ActivityLogService::class)->logRejectPengajuanDeposito((string)$pengajuan->id, (float)$pengajuan->nominal, $pengajuan->nasabah->user->nama ?? 'N/A', $request->catatan_admin);

        NasabahNotification::notify(
            $pengajuan->id_nasabah, 'deposito',
            'Pengajuan Deposito Ditolak',
            'Pengajuan deposito Anda ditolak. ' . $request->catatan_admin,
            route('nasabah.deposito.status-pengajuan', $pengajuan->id),
            (string) $pengajuan->id, 'pengajuan_deposito'
        );

        return redirect()->route('admin.deposito.pengajuan-list')
            ->with('success', 'Pengajuan deposito telah ditolak.');
    }
```
</details>

### Route: `admin/deposito/list` [GET|HEAD]
**Function:** `depositoList`

**Queries Detected:**
- Model: DepositoH

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function depositoList(Request $request)
    {
        $query = DepositoH::with(['nasabah.user', 'tenor'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_deposito', 'like', "%{$search}%")
                  ->orWhereHas('nasabah.user', fn($q2) => $q2->where('nama', 'like', "%{$search}%"));
            });
        }

        $depositos = $query->paginate(15)->withQueryString();

        return view('admin.deposito.deposito-list', compact('depositos'));
    }
```
</details>

### Route: `admin/deposito/export-pdf` [GET|HEAD]
**Function:** `exportPdf`

**Queries Detected:**
- Model: DepositoH
- Model: Pdf

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function exportPdf(Request $request)
    {
        $this->checkDepositoPermission();

        $query = DepositoH::with(['nasabah.user', 'tenor'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $depositos = $query->get();

        $pdf = Pdf::loadView('admin.deposito.export-pdf', compact('depositos'));
        return $pdf->download('laporan-deposito-' . now()->format('Ymd-Hi') . '.pdf');
    }
```
</details>

### Route: `admin/deposito/list/{id}` [GET|HEAD]
**Function:** `depositoDetail`

**Queries Detected:**
- Model: DepositoH
- Model: User

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function depositoDetail($id)
    {
        $deposito = DepositoH::with(['nasabah.user', 'tenor', 'transDeposito', 'pencairan', 'persiapanCair'])
            ->findOrFail($id);

        $admins = [];
        if (auth()->user()->role === 'admin_utama') {
            $admins = \App\Models\User::where('role', 'admin_operasional')->get();
        }

        return view('admin.deposito.deposito-detail', compact('deposito', 'admins'));
    }
```
</details>

### Route: `admin/deposito/paket` [GET|HEAD]
**Function:** `paketIndex`

**Queries Detected:**
- Model: PaketDeposito

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function paketIndex()
    {
        if (auth()->user()->role !== 'admin_utama') abort(403);
        $paket = PaketDeposito::orderBy('tenor_bulan')->orderBy('minimal_nominal')->get();
        return view('admin.deposito.paket.index', compact('paket'));
    }
```
</details>

### Route: `admin/deposito/paket/create` [GET|HEAD]
**Function:** `paketCreate`

**Queries Detected:**
- Model: KategoriDeposito
- Model: JnsTenorDeposito

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function paketCreate()
    {
        if (auth()->user()->role !== 'admin_utama') abort(403);
        $kategoris = \App\Models\KategoriDeposito::where('status', 'aktif')->get();
        $tenors = \App\Models\JnsTenorDeposito::where('aktif', 'y')->orderBy('tenor_bulan')->get();
        return view('admin.deposito.paket.create', compact('kategoris', 'tenors'));
    }
```
</details>

### Route: `admin/deposito/paket` [POST]
**Function:** `paketStore`

**Queries Detected:**
- Model: PaketDeposito

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function paketStore(Request $request)
    {
        if (auth()->user()->role !== 'admin_utama') abort(403);
        $validated = $request->validate([
            'nama_paket'       => 'required|string|max:100',
            'tenor_bulan'      => 'required|integer|min:1',
            'suku_bunga'       => 'required|numeric|min:0',
            'minimal_nominal'  => 'required|numeric|min:0',
            'maksimal_nominal' => 'nullable|numeric|gte:minimal_nominal',
            'status'           => 'required|in:aktif,nonaktif',
            'kategori_id'      => 'nullable|exists:kategori_depositos,id',
            'keterangan'       => 'nullable|string'
        ]);

        PaketDeposito::create($validated);
        return redirect()->route('admin.deposito.paket.index')->with('success', 'Paket Deposito berhasil ditambahkan.');
    }
```
</details>

### Route: `admin/deposito/paket/{id}/edit` [GET|HEAD]
**Function:** `paketEdit`

**Queries Detected:**
- Model: PaketDeposito
- Model: KategoriDeposito
- Model: JnsTenorDeposito

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function paketEdit($id)
    {
        if (auth()->user()->role !== 'admin_utama') abort(403);
        $paket = PaketDeposito::findOrFail($id);
        $kategoris = \App\Models\KategoriDeposito::where('status', 'aktif')->get();
        $tenors = \App\Models\JnsTenorDeposito::where('aktif', 'y')->orderBy('tenor_bulan')->get();
        return view('admin.deposito.paket.edit', compact('paket', 'kategoris', 'tenors'));
    }
```
</details>

### Route: `admin/deposito/paket/{id}` [PUT]
**Function:** `paketUpdate`

**Queries Detected:**
- Model: PaketDeposito

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function paketUpdate(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin_utama') abort(403);
        $paket = PaketDeposito::findOrFail($id);
        
        $validated = $request->validate([
            'nama_paket'       => 'required|string|max:100',
            'tenor_bulan'      => 'required|integer|min:1',
            'suku_bunga'       => 'required|numeric|min:0',
            'minimal_nominal'  => 'required|numeric|min:0',
            'maksimal_nominal' => 'nullable|numeric|gte:minimal_nominal',
            'status'           => 'required|in:aktif,nonaktif',
            'kategori_id'      => 'nullable|exists:kategori_depositos,id',
            'keterangan'       => 'nullable|string'
        ]);

        $paket->update($validated);
        return redirect()->route('admin.deposito.paket.index')->with('success', 'Paket Deposito berhasil diperbarui.');
    }
```
</details>

### Route: `admin/deposito/paket/{id}` [DELETE]
**Function:** `paketDestroy`

**Queries Detected:**
- Model: PaketDeposito

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function paketDestroy($id)
    {
        if (auth()->user()->role !== 'admin_utama') abort(403);
        $paket = PaketDeposito::findOrFail($id);
        $paket->update(['status' => 'nonaktif']);
        return redirect()->route('admin.deposito.paket.index')->with('success', 'Paket Deposito berhasil dinonaktifkan (soft delete).');
    }
```
</details>

### Route: `admin/deposito/pencairan-tf` [GET|HEAD]
**Function:** `pencairanTfIndex`

**Queries Detected:**
- Model: PencairanDeposito

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function pencairanTfIndex(Request $request)
    {
        $this->checkDepositoPermission();

        $query = PencairanDeposito::with(['deposito.tenor', 'nasabah.user', 'nasabah.dataRek'])
            ->where('jenis_pencairan', 'rek_nasabah')
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->whereHas('nasabah.user', fn($q2) => $q2->where('nama', 'like', "%$s%"))
                  ->orWhereHas('deposito', fn($q2) => $q2->where('nomor_deposito', 'like', "%$s%"));
            });
        }

        $pencairans = $query->paginate(15)->withQueryString();
        $pendingCount = PencairanDeposito::where('jenis_pencairan', 'rek_nasabah')->where('status', 'pending')->count();

        return view('admin.deposito.pencairan-tf', compact('pencairans', 'pendingCount'));
    }
```
</details>

### Route: `admin/deposito/pencairan-tf/{id}/proses` [GET|HEAD]
**Function:** `pencairanTfFormShow`

**Queries Detected:**
- Model: PencairanDeposito
- Model: User
- Model: BiayaTransfer
- Model: BankAccessService
- Model: PettyCashSaldo

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function pencairanTfFormShow($id)
    {
        $this->checkDepositoPermission();

        $pencairan = PencairanDeposito::with(['deposito.tenor', 'nasabah.user', 'nasabah.dataRek'])
            ->where('jenis_pencairan', 'rek_nasabah')
            ->findOrFail($id);

        if ($pencairan->isSelesai()) {
            return redirect()->route('admin.deposito.pencairan-tf.index')
                ->with('error', 'Pencairan ini sudah selesai diproses.');
        }

        $admins = User::where('role', 'admin_operasional')->get();
        $biayaTransfer = \App\Models\BiayaTransfer::where('is_active', true)->get();
        $saldoTabunganNasabah = app(\App\Services\BankAccessService::class)->getSaldoTabungan($pencairan->id_nasabah);
        $adminSaldoTransfer = PettyCashSaldo::getSaldo(Auth::id(), 'admin', 'transfer', 'other');

        return view('admin.deposito.pencairan-tf-form', compact('pencairan', 'admins', 'biayaTransfer', 'saldoTabunganNasabah', 'adminSaldoTransfer'));
    }
```
</details>

### Route: `admin/deposito/pencairan-tf/{id}/proses` [POST]
**Function:** `pencairanTfProses`

**Queries Detected:**
- Model: PencairanDeposito
- Model: PettyCashSaldo
- Model: IdGenerator
- Model: PettyCashPenerimaan
- Model: PettyCashConstants
- Model: PettyCashOwnerTransaksi
- Model: DepositoPersiapanCair

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function pencairanTfProses(Request $request, $id)
    {
        $this->checkDepositoPermission();

        $request->validate([
            'admin_id'      => 'required|exists:users,id',
            'nominal_akhir' => 'required|numeric|min:1',
            'catatan'       => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $pencairan = PencairanDeposito::with(['deposito', 'nasabah'])->findOrFail($id);

            if (!$pencairan->isTf()) {
                return back()->with('error', 'Jenis pencairan ini bukan Transfer Rekening.');
            }
            if (!$pencairan->isPending()) {
                return back()->with('error', 'Pencairan sudah diproses sebelumnya.');
            }

            $nominal = (float) $request->nominal_akhir;
            $ownerId = Auth::id();
            $adminId = $request->admin_id;
            $nomorDep = $pencairan->deposito->nomor_deposito;

            // Validasi saldo Owner (transfer) mencukupi
            $saldoTfOwner = PettyCashSaldo::getSaldo($ownerId, 'owner', 'transfer');
            if ($saldoTfOwner < $nominal) {
                return back()->with('error',
                    'Saldo Transfer Owner tidak mencukupi. Tersedia: Rp ' . number_format($saldoTfOwner, 0, ',', '.') .
                    ', Dibutuhkan: Rp ' . number_format($nominal, 0, ',', '.')
                );
            }

            $penerimaanId = IdGenerator::generate('petty_cash_penerimaan', 'PCP', 'OW', 'KRM');

            // 1. Buat PettyCashPenerimaan (Owner -> Admin, sumber=deposito, tipe=transfer untuk dikirim ke nasabah)
            PettyCashPenerimaan::create([
                'id'          => $penerimaanId,
                'owner_id'    => $ownerId,
                'admin_id'    => $adminId,
                'sumber'      => 'deposito',
                'nominal_tf'  => $nominal,
                'nominal_cash'=> 0,
                'keterangan'  => 'Dana Pencairan Deposito ' . $nomorDep . ' (TF Rekening) untuk dikirim ke nasabah',
                'status'      => 'pending',
                'ref_id'      => (string) $pencairan->id,
            ]);

            // Transfer Koperasi: Mutasi Saldo Owner (TF - DEPOSITO) berkurang
            PettyCashSaldo::buatMutasi(
                $ownerId, 'owner', -$nominal,
                "Pencairan Deposito (Transfer) #{$pencairan->id}",
                $pencairan->id, 'tbl_pencairan_deposito', 'transfer',
                \App\Services\PettyCashConstants::SUMBER_DEPOSITO
            );

            // 3. Catat di PettyCashOwnerTransaksi
            PettyCashOwnerTransaksi::create([
                'id'           => IdGenerator::generate('petty_cash_owner_transaksi', 'PCOW', 'OW', 'TR'),
                'user_id'      => $ownerId,
                'tipe'         => 'kirim_admin_hold',
                'sumber'       => 'deposito',
                'nominal_tf'   => $nominal,
                'nominal_cash' => 0,
                'keterangan'   => 'HOLD: Kirim dana TF ke Admin untuk Pencairan Deposito ' . $nomorDep,
                'ref_id'       => $penerimaanId,
                'ref_table'    => 'petty_cash_penerimaan',
            ]);

            // 4. Update status pencairan record
            $pencairan->update([
                'nominal_akhir' => $nominal,
                'catatan'       => $request->catatan,
                'status'        => 'diproses', // Menunggu Admin terima dana
            ]);

            // 5. Sinkronisasi deposito_persiapan_cair
            DepositoPersiapanCair::where('deposito_id', $pencairan->deposito_id)
                ->whereIn('status', ['tentatif', 'diproses'])
                ->update(['status' => 'diproses', 'pencairan_id' => $pencairan->id]);

            DB::commit();

            return redirect()->route('admin.deposito.pencairan-tf.index')
                ->with('success', 'Dana pencairan telah dikirim ke Admin. Pencairan akan selesai setelah Admin mengkonfirmasi transfer ke nasabah.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Inisiasi pencairan TF error', ['id' => $id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
```
</details>

### Route: `admin/deposito/pencairan-tf/{id}/finish` [POST]
**Function:** `selesaikanPencairanTf`

**Queries Detected:**
- Model: PencairanDeposito
- Model: PettyCashSaldo
- Model: TransDeposito
- Model: BankAccessService
- Model: DepositoPersiapanCair
- Model: ActivityLogService

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function selesaikanPencairanTf(Request $request, $id)
    {
        $this->checkDepositoPermission();

        $request->validate([
            'foto_bukti_tf' => 'required|image|max:5120',
            'nominal_akhir' => 'required|numeric|min:1',
            'catatan'       => 'nullable|string|max:500',
            'bank_pengirim' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $pencairan = PencairanDeposito::with(['deposito', 'nasabah'])->findOrFail($id);

            if (!in_array($pencairan->status, ['pending', 'diproses'])) {
                return back()->with('error', 'Pencairan tidak dalam status valid untuk diselesaikan.');
            }

            // Direct Approval check
            $isDirect = ($pencairan->status === 'pending');
            if ($isDirect && auth()->user()->role !== 'admin_operasional') {
                return back()->with('error', 'Hanya Admin Operasional yang dapat melakukan persetujuan langsung.');
            }

            $nominal = (float) $request->nominal_akhir;
            $adminId = auth()->id();

            // Cek saldo Admin (transfer) mencukupi dari MODAL AWAL (Rule #1)
            $saldoTfAdmin = PettyCashSaldo::getSaldo($adminId, 'admin', 'transfer', 'other');
            if ($saldoTfAdmin < $nominal) {
                return back()->with('error', 'Saldo Transfer MODAL AWAL Anda tidak mencukupi untuk melakukan transfer ini.');
            }

            // 1. Upload foto bukti TF
            $fotoPath = $request->file('foto_bukti_tf')->store('deposito/bukti-tf-pencairan', 'public');

            // 2. Potong saldo Petty Cash Admin (MODAL AWAL) - Rule #1
            // Ini MENGURANGI saldo Admin. Reimbursement dilakukan terpisah oleh Owner (Rule #3).
            PettyCashSaldo::buatMutasi(
                $adminId, 'admin', -$nominal,
                'Pencairan Deposito ' . $pencairan->deposito->nomor_deposito . ' (TF)',
                (string) $pencairan->id, 'tbl_pencairan_deposito', 'transfer', 'other'
            );

            // 3. Record di trans_deposito
            TransDeposito::create([
                'deposito_id'   => $pencairan->deposito_id,
                'jenis'         => 'pencairan',
                'nominal'       => $nominal,
                'keterangan'    => 'Pencairan TF ke Nasabah',
                'tgl_transaksi' => now(),
            ]);

            // 🔥 INTEGRASI BIAYA TRANSFER ANTARBANK
            $bankService = app(\App\Services\BankAccessService::class);
            $namaBank = $bankService->getNamaBank($pencairan->deposito->id_nasabah);
            $bankPengirim = $request->input('bank_pengirim', 'BCA');
            $biayaTransfer = 0;
            
            if ($namaBank && !$bankService->isBcaUser($pencairan->deposito->id_nasabah)) {
                $potong = $bankService->potongBiayaTransfer(
                    $pencairan->deposito->id_nasabah,
                    $namaBank,
                    'Pencairan Deposito ' . $pencairan->deposito->nomor_deposito . ' (TF)',
                    $adminId,
                    $bankPengirim
                );
                
                if (!$potong['success']) {
                    throw new \Exception($potong['message']);
                }
                $biayaTransfer = $potong['biaya'] ?? 0;
            }

            // 4. Update status pencairan
            $pencairan->update([
                'nominal_akhir' => $nominal,
                'foto_bukti_tf' => $fotoPath,
                'status'        => 'selesai',
                'approved_by'   => $adminId,
                'bank_pengirim' => $bankPengirim,
                'biaya_transfer' => $biayaTransfer,
            ]);

            // 5. Update status deposito → dicairkan atau ditutup jika is_cancel
            $statusDep = $pencairan->is_cancel ? 'ditutup' : 'dicairkan';
            $pencairan->deposito->update(['status' => $statusDep]);

            // 6. Sinkronisasi persiapan cair
            DepositoPersiapanCair::where('deposito_id', $pencairan->deposito_id)
                ->update(['status' => 'selesai']);

            DB::commit();

            app(\App\Services\ActivityLogService::class)->logPencairanDeposito((string)$pencairan->deposito_id, (float)$nominal, $pencairan->nasabah->user->nama ?? 'N/A');

            return redirect()->route('admin.deposito.pencairan-tf.index')
                ->with('success', 'Pencairan deposito berhasil diselesaikan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Selesaikan pencairan TF error', ['id' => $id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
```
</details>

### Route: `admin/deposito/pencairan-tabungan` [GET|HEAD]
**Function:** `pencairanTabunganIndex`

**Queries Detected:**
- Model: PencairanDeposito
- Model: User
- Model: PettyCashSaldo

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function pencairanTabunganIndex(Request $request)
    {
        $this->checkDepositoPermission();

        $query = PencairanDeposito::with(['deposito.tenor', 'nasabah.user'])
            ->where('jenis_pencairan', 'saldo_tabungan')
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->whereHas('nasabah.user', fn($q2) => $q2->where('nama', 'like', "%$s%"))
                  ->orWhereHas('deposito', fn($q2) => $q2->where('nomor_deposito', 'like', "%$s%"));
            });
        }

        $pencairans = $query->paginate(15)->withQueryString();
        $pendingCount = PencairanDeposito::where('jenis_pencairan', 'saldo_tabungan')->where('status', 'pending')->count();
        $admins = User::where('role', 'admin_operasional')->get();
        $adminSaldoTransfer = PettyCashSaldo::getSaldo(Auth::id(), 'admin', 'transfer', 'other');

        return view('admin.deposito.pencairan-tabungan', compact('pencairans', 'pendingCount', 'admins', 'adminSaldoTransfer'));
    }
```
</details>

### Route: `admin/deposito/pencairan-tabungan/{id}/proses` [POST]
**Function:** `pencairanTabunganProses`

**Queries Detected:**
- Model: PencairanDeposito
- Model: PettyCashSaldo
- Model: IdGenerator
- Model: PettyCashPenerimaan
- Model: PettyCashOwnerTransaksi

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function pencairanTabunganProses(Request $request, $id)
    {
        $this->checkDepositoPermission();

        $request->validate([
            'admin_id' => 'required|exists:users,id',
            'catatan'  => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $pencairan = PencairanDeposito::with(['deposito', 'nasabah'])->findOrFail($id);

            if (!$pencairan->isTabungan()) {
                return back()->with('error', 'Jenis pencairan ini bukan Saldo Tabungan.');
            }
            if (!$pencairan->isPending()) {
                return back()->with('error', 'Pencairan sudah diproses sebelumnya.');
            }

            $nominal  = (float) $pencairan->nominal_akhir;
            $ownerId  = Auth::id();
            $adminId  = $request->admin_id;
            $nomorDep = $pencairan->deposito->nomor_deposito;

            // Validasi saldo Owner (transfer/internal) mencukupi
            $saldoTfOwner = PettyCashSaldo::getSaldo($ownerId, 'owner', 'transfer');
            if ($saldoTfOwner < $nominal) {
                return back()->with('error', 'Saldo Transfer/Internal Owner tidak mencukupi untuk membiayai pencairan tabungan ini.');
            }

            $penerimaanId = IdGenerator::generate('petty_cash_penerimaan', 'PCP', 'OW', 'KRM');

            // 1. Buat PettyCashPenerimaan (Owner -> Admin, sumber=deposito, tipe=transfer untuk virtual)
            PettyCashPenerimaan::create([
                'id'          => $penerimaanId,
                'owner_id'    => $ownerId,
                'admin_id'    => $adminId,
                'sumber'      => 'deposito',
                'nominal_tf'  => $nominal,
                'nominal_cash'=> 0,
                'keterangan'  => 'Dana Pencairan Deposito ' . $nomorDep . ' (ke Tabungan) untuk dikelola Admin',
                'status'      => 'pending',
                'ref_id'      => (string) $pencairan->id,
            ]);

            // 2. Hold saldo Owner
            PettyCashSaldo::buatMutasi(
                $ownerId, 'owner', -$nominal,
                'HOLD: Dana Pencairan Deposito ' . $nomorDep . ' (Tabungan) ke Admin',
                $penerimaanId, 'petty_cash_penerimaan', 'transfer'
            );

            // 3. Catat di PettyCashOwnerTransaksi
            PettyCashOwnerTransaksi::create([
                'id'           => IdGenerator::generate('petty_cash_owner_transaksi', 'PCOW', 'OW', 'TR'),
                'user_id'      => $ownerId,
                'tipe'         => 'kirim_admin_hold',
                'sumber'       => 'deposito',
                'nominal_tf'   => $nominal,
                'nominal_cash' => 0,
                'keterangan'   => 'HOLD: Kirim dana virtual ke Admin untuk Pencairan Deposito ' . $nomorDep . ' ke Tabungan',
                'ref_id'       => $penerimaanId,
                'ref_table'    => 'petty_cash_penerimaan',
            ]);

            // 4. Update status pencairan
            $pencairan->update([
                'catatan' => $request->catatan,
                'status'  => 'diproses',
            ]);

            DB::commit();

            return redirect()->route('admin.deposito.pencairan-tabungan.index')
                ->with('success', 'Pencairan sedang diproses. Menunggu Admin menerima dana dan menambahkannya ke tabungan nasabah.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Inisiasi pencairan tabungan error', ['id' => $id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
```
</details>

### Route: `admin/deposito/pencairan-tabungan/{id}/finish` [POST]
**Function:** `selesaikanPencairanTabungan`

**Queries Detected:**
- Table: jns_via
- Table: jns_transaksi
- Model: PencairanDeposito
- Model: PettyCashSaldo
- Model: IdGenerator
- Model: TransTabungan
- Model: TransDeposito
- Model: DepositoPersiapanCair
- Model: ActivityLogService
- Model: NasabahNotification

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function selesaikanPencairanTabungan(Request $request, $id)
    {
        $this->checkDepositoPermission();

        $request->validate([
            'nominal_akhir' => 'required|numeric|min:1',
            'foto_bukti_tf' => 'nullable|image|max:5120',
        ]);

        try {
            DB::beginTransaction();

            $pencairan = PencairanDeposito::with(['deposito.tenor', 'nasabah'])->findOrFail($id);

            if (!in_array($pencairan->status, ['pending', 'diproses'])) {
                return back()->with('error', 'Pencairan tidak dalam status valid untuk diselesaikan.');
            }

            $isDirect = ($pencairan->status === 'pending');
            if ($isDirect && auth()->user()->role !== 'admin_operasional') {
                return back()->with('error', 'Hanya Admin Operasional yang dapat melakukan persetujuan langsung.');
            }

            $nominal = (float) $request->nominal_akhir;
            $adminId = auth()->id();

            // Cek saldo Admin (transfer) mencukupi dari MODAL AWAL (Rule #1)
            $saldoTfAdmin = PettyCashSaldo::getSaldo($adminId, 'admin', 'transfer', 'other');
            if ($saldoTfAdmin < $nominal) {
                return back()->with('error', 'Saldo Transfer MODAL AWAL Anda tidak mencukupi.');
            }

            // 0. Handle Foto Bukti
            $fotoPath = $pencairan->foto_bukti_tf;
            if ($request->hasFile('foto_bukti_tf')) {
                $fotoPath = $request->file('foto_bukti_tf')->store('deposito/bukti-tabungan', 'public');
            }

            // 1. Buat TransTabungan (STR)
            $idVia   = DB::table('jns_via')->where('kode', 'TF')->value('id');
            $idTrans = DB::table('jns_transaksi')->where('kode', 'STR')->value('id');
            $idTransaksi = IdGenerator::generate('trans_tabungan', 'T', 'TF', 'STR');

            TransTabungan::create([
                'id'                 => $idTransaksi,
                'id_anggota'         => $pencairan->id_nasabah,
                'id_jns_via'         => $idVia,
                'id_jns_transaksi'   => $idTrans,
                'nominal'            => $nominal,
                'keterangan'         => 'Pencairan Deposito ' . $pencairan->deposito->nomor_deposito . ' ke Tabungan',
                'tgl_transaksi'      => now(),
                'admin_pengelola_id' => $adminId,
            ]);

            // 2. Potong saldo Admin (MODAL AWAL) - Rule #1
            // Ini MENGURANGI saldo. TopUp dilakukan terpisah oleh Owner (Rule #3).
            PettyCashSaldo::buatMutasi(
                $adminId, 'admin', -$nominal,
                'Pencairan Deposito ' . $pencairan->deposito->nomor_deposito . ' ke Tabungan',
                (string) $pencairan->id, 'tbl_pencairan_deposito', 'transfer', 'other'
            );

            // 3. Record di trans_deposito
            TransDeposito::create([
                'deposito_id'   => $pencairan->deposito_id,
                'jenis'         => 'pencairan',
                'nominal'       => $nominal,
                'keterangan'    => 'Pencairan ke Saldo Tabungan',
                'tgl_transaksi' => now(),
            ]);

            // 4. Update status pencairan
            $pencairan->update([
                'nominal_akhir' => $nominal,
                'foto_bukti_tf' => $fotoPath,
                'status'        => 'selesai',
                'approved_by'   => $adminId,
            ]);

            // 5. Update status deposito → dicairkan atau ditutup jika is_cancel
            $statusDep = $pencairan->is_cancel ? 'ditutup' : 'dicairkan';
            $pencairan->deposito->update(['status' => $statusDep]);

            // 6. Sinkronisasi persiapan cair
            DepositoPersiapanCair::where('deposito_id', $pencairan->deposito_id)
                ->update(['status' => 'selesai']);

            DB::commit();

            app(\App\Services\ActivityLogService::class)->logPencairanDeposito((string)$pencairan->deposito_id, (float)$nominal, $pencairan->nasabah->user->nama ?? 'N/A');

            // Notifikasi nasabah
            NasabahNotification::notify(
                $pencairan->id_nasabah, 'deposito',
                'Deposito Dicairkan ke Tabungan',
                'Deposito No. ' . $pencairan->deposito->nomor_deposito . ' senilai Rp ' .
                    number_format($nominal, 0, ',', '.') . ' telah ditambahkan ke saldo tabungan Anda.',
                route('nasabah.deposito.detail', $pencairan->deposito_id),
                (string) $pencairan->deposito_id, 'pencairan_deposito'
            );

            return redirect()->route('admin.deposito.pencairan-tabungan.index')
                ->with('success', 'Pencairan ke tabungan berhasil.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Finalisasi pencairan tabungan error', ['id' => $id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
```
</details>

### Route: `admin/deposito/pencairan-petty-cash/{id}/proses` [POST]
**Function:** `pencairanPettyCashProses`

**Queries Detected:**
- Model: PencairanDeposito
- Model: PettyCashSaldo
- Model: IdGenerator
- Model: PettyCashPenerimaan
- Model: PettyCashOwnerTransaksi
- Model: DepositoPersiapanCair
- Model: NasabahNotification

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function pencairanPettyCashProses(Request $request, $id)
    {
        $this->checkDepositoPermission();

        $request->validate([
            'admin_id' => 'required|exists:users,id',
            'catatan'  => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $pencairan = PencairanDeposito::with(['deposito', 'nasabah'])->findOrFail($id);

            if (!$pencairan->isPettyCash()) {
                return back()->with('error', 'Jenis pencairan ini bukan Petty Cash Operator.');
            }
            if (!$pencairan->isPending()) {
                return back()->with('error', 'Pencairan ini sudah diproses sebelumnya.');
            }

            $nominal  = (float) $pencairan->nominal_akhir;
            $ownerId  = Auth::id();
            $adminId  = $request->admin_id;
            $nomorDep = $pencairan->deposito->nomor_deposito;

            // Validasi saldo Owner (cash) mencukupi
            $saldoCashOwner = PettyCashSaldo::getSaldo($ownerId, 'owner', 'cash');
            if ($saldoCashOwner < $nominal) {
                return back()->with('error',
                    'Saldo Cash Owner tidak mencukupi. Tersedia: Rp ' . number_format($saldoCashOwner, 0, ',', '.') .
                    ', Dibutuhkan: Rp ' . number_format($nominal, 0, ',', '.')
                );
            }

            $penerimaanId = IdGenerator::generate('petty_cash_penerimaan', 'PCP', 'OW', 'KRM');

            // Buat PettyCashPenerimaan (Owner → Admin, sumber=deposito)
            PettyCashPenerimaan::create([
                'id'          => $penerimaanId,
                'owner_id'    => $ownerId,
                'admin_id'    => $adminId,
                'sumber'      => 'deposito',
                'nominal_cash'=> $nominal,
                'nominal_tf'  => 0,
                'keterangan'  => 'Dana Pencairan Deposito ' . $nomorDep . ' untuk diserahkan ke nasabah',
                'status'      => 'pending',
                'ref_id'      => (string) $pencairan->id,  // link ke pencairan
            ]);

            // Hold saldo Owner (cash) — akan dikembalikan jika Admin reject
            PettyCashSaldo::buatMutasi(
                $ownerId, 'owner', -$nominal,
                'HOLD: Dana Pencairan Deposito ' . $nomorDep . ' ke Admin (Petty Cash)',
                $penerimaanId, 'petty_cash_penerimaan', 'cash'
            );

            // Catat di PettyCashOwnerTransaksi untuk audit trail / vw_saldo_owner_detail
            PettyCashOwnerTransaksi::create([
                'id'           => IdGenerator::generate('petty_cash_owner_transaksi', 'PCOW', 'OW', 'TR'),
                'user_id'      => $ownerId,
                'tipe'         => 'kirim_admin_hold',
                'sumber'       => 'deposito',
                'nominal_cash' => $nominal,
                'nominal_tf'   => 0,
                'keterangan'   => 'HOLD: Kirim dana Petty Cash ke Admin untuk Pencairan Deposito ' . $nomorDep,
                'ref_id'       => $penerimaanId,
                'ref_table'    => 'petty_cash_penerimaan',
            ]);

            // Update status pencairan → diproses (menunggu Admin konfirmasi)
            $pencairan->update(['status' => 'diproses']);

            // Sinkronisasi deposito_persiapan_cair
            DepositoPersiapanCair::where('deposito_id', $pencairan->deposito_id)
                ->where('status', 'tentatif')
                ->update([
                    'status'      => 'diproses',
                    'metode_cair' => 'petty_cash_operator',
                    'pencairan_id'=> $pencairan->id,
                ]);

            DB::commit();

            // Notifikasi nasabah bahwa pencairan sedang diproses
            NasabahNotification::notify(
                $pencairan->id_nasabah, 'deposito',
                'Pencairan Deposito Sedang Diproses',
                'Pencairan Deposito No. ' . $nomorDep . ' sedang diproses. Silakan datang ke kantor untuk pengambilan tunai.',
                route('nasabah.deposito.detail', $pencairan->deposito_id),
                (string) $pencairan->deposito_id, 'pencairan_deposito'
            );

            return redirect()->route('admin.deposito.pencairan-tf.index')
                ->with('success', 'Dana deposito Rp ' . number_format($nominal, 0, ',', '.') . ' telah dikirim ke Admin. Menunggu konfirmasi penerimaan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('pencairanPettyCashProses error', ['id' => $id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
```
</details>

### Route: `admin/deposito/peringatan` [GET|HEAD]
**Function:** `peringatanIndex`

**Queries Detected:**
- Model: DepositoPersiapanCair

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function peringatanIndex(Request $request)
    {
        $this->checkDepositoPermission();

        $query = DepositoPersiapanCair::with(['deposito.nasabah.user', 'deposito.tenor', 'nasabah.user'])
            ->whereIn('status', ['tentatif', 'diproses'])
            ->orderBy('tgl_target_cair');

        if ($request->filled('metode_cair')) {
            $query->where('metode_cair', $request->metode_cair);
        }

        if ($request->filled('tanggal_dari')) {
            $query->where('tgl_target_cair', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->where('tgl_target_cair', '<=', $request->tanggal_sampai);
        }

        $persiapan = $query->paginate(15)->withQueryString();

        // Agregasi per hari untuk summary Owner
        $summary = DepositoPersiapanCair::whereIn('status', ['tentatif', 'diproses'])
            ->selectRaw('tgl_target_cair, metode_cair, COUNT(*) as jumlah, SUM(total_dibayar) as total_dana')
            ->groupBy('tgl_target_cair', 'metode_cair')
            ->orderBy('tgl_target_cair')
            ->get()
            ->groupBy('tgl_target_cair');

        // Stats card
        $stats = [
            'total_persiapan'    => DepositoPersiapanCair::whereIn('status', ['tentatif', 'diproses'])->count(),
            'total_dana'         => DepositoPersiapanCair::whereIn('status', ['tentatif', 'diproses'])->sum('total_dibayar'),
            'butuh_transfer'     => DepositoPersiapanCair::whereIn('status', ['tentatif', 'diproses'])
                                        ->where('metode_cair', 'rek_nasabah')->sum('total_dibayar'),
            'butuh_petty_cash'   => DepositoPersiapanCair::whereIn('status', ['tentatif', 'diproses'])
                                        ->where('metode_cair', 'petty_cash_operator')->sum('total_dibayar'),
            'ke_tabungan'        => DepositoPersiapanCair::whereIn('status', ['tentatif', 'diproses'])
                                        ->where('metode_cair', 'saldo_tabungan')->sum('total_dibayar'),
            'jatuh_tempo_hari_ini' => DepositoPersiapanCair::whereIn('status', ['tentatif', 'diproses'])
                                        ->where('tgl_target_cair', today())->count(),
        ];

        return view('admin.deposito.peringatan-index', compact('persiapan', 'summary', 'stats'));
    }
```
</details>

### Route: `admin/deposito/peringatan/{id}/update` [POST]
**Function:** `updatePersiapanCair`

**Queries Detected:**
- Model: DepositoPersiapanCair

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function updatePersiapanCair(Request $request, $id)
    {
        $this->checkDepositoPermission();
        $item = DepositoPersiapanCair::findOrFail($id);

        $request->validate([
            'metode_cair' => 'required|in:rek_nasabah,saldo_tabungan,petty_cash_operator',
            'catatan'     => 'nullable|string|max:500',
        ]);

        $item->update([
            'metode_cair' => $request->metode_cair,
            'catatan'     => $request->catatan,
        ]);

        return back()->with('success', 'Rencana pencairan berhasil diperbarui.');
    }
```
</details>

### Route: `admin/deposito/peringatan/{id}/send-dana` [POST]
**Function:** `sendDanaPersiapan`

**Queries Detected:**
- Model: DepositoPersiapanCair
- Model: PettyCashSaldo
- Model: IdGenerator
- Model: PettyCashPenerimaan
- Model: PettyCashConstants
- Model: PettyCashOwnerTransaksi

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function sendDanaPersiapan(Request $request, $id)
    {
        $this->checkDepositoPermission();
        $item = DepositoPersiapanCair::with(['deposito', 'nasabah'])->findOrFail($id);

        if ($item->status !== 'tentatif') {
            return back()->with('error', 'Persiapan dana ini sudah diproses.');
        }

        if ($item->metode_cair !== 'petty_cash_operator') {
            return back()->with('error', 'Pengiriman dana hanya tersedia untuk metode Petty Cash.');
        }

        $request->validate([
            'admin_id' => 'required|exists:users,id',
        ]);

        try {
            DB::beginTransaction();

            $nominal  = (float) $item->total_dibayar;
            $ownerId  = Auth::id();
            $adminId  = $request->admin_id;
            $nomorDep = $item->deposito->nomor_deposito;

            // Validasi saldo Owner (cash)
            $saldoCashOwner = PettyCashSaldo::getSaldo($ownerId, 'owner', 'cash');
            if ($saldoCashOwner < $nominal) {
                return back()->with('error', 'Saldo Cash Owner tidak mencukupi. Tersedia: Rp ' . number_format($saldoCashOwner, 0, ',', '.'));
            }

            $penerimaanId = IdGenerator::generate('petty_cash_penerimaan', 'PCP', 'OW', 'KRM');

            // 1. Buat PettyCashPenerimaan
            PettyCashPenerimaan::create([
                'id'          => $penerimaanId,
                'owner_id'    => $ownerId,
                'admin_id'    => $adminId,
                'sumber'      => 'deposito',
                'nominal_cash'=> $nominal,
                'nominal_tf'  => 0,
                'keterangan'  => 'Persiapan Dana Deposito ' . $nomorDep . ' (Jatuh Tempo: ' . $item->tgl_target_cair->format('d/m/Y') . ')',
                'status'      => 'pending',
                'ref_id'      => (string) $item->id, // link ke persiapan cair
            ]);

            // Tunai Koperasi (CASH Owner - DEPOSITO) berkurang
            PettyCashSaldo::buatMutasi(
                $ownerId, 'owner', -$nominal,
                "Pencairan Deposito (Tunai Koperasi) #{$item->id}",
                $item->id, 'tbl_pencairan_deposito', 'cash',
                \App\Services\PettyCashConstants::SUMBER_DEPOSITO
            );

            // 3. Catat di Owner Wallet Detail
            PettyCashOwnerTransaksi::create([
                'id'           => IdGenerator::generate('petty_cash_owner_transaksi', 'PCOW', 'OW', 'TR'),
                'user_id'      => $ownerId,
                'tipe'         => 'kirim_admin_hold',
                'sumber'       => 'deposito',
                'nominal_cash' => $nominal,
                'nominal_tf'   => 0,
                'keterangan'   => 'HOLD: Kirim dana Petty Cash ke Admin untuk Persiapan Deposito ' . $nomorDep,
                'ref_id'       => $penerimaanId,
                'ref_table'    => 'petty_cash_penerimaan',
            ]);

            // 4. Update status persiapan → diproses
            $item->update(['status' => 'diproses']);

            DB::commit();
            return back()->with('success', 'Dana persiapan berhasil dikirim ke Admin. Menunggu konfirmasi Admin.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengirim dana: ' . $e->getMessage());
        }
    }
```
</details>

## Controller: `App\Http\Controllers\AdminGadaiBaruController`

**Models Imported:**
- `App\Models\GadaiActive`
- `App\Models\GadaiMasterKategori`
- `App\Models\GadaiMasterItem`
- `App\Models\JnsLokasiPerusahaan`
- `App\Models\Nasabah`
- `App\Models\GadaiHistory`
- `App\Models\GadaiFile`
- `App\Models\GadaiSlotLog`
- `App\Models\PettyCashSaldo`

### Route: `admin/gadai_baru` [GET|HEAD]
**Function:** `index`

**Queries Detected:**
- Model: GadaiActive
- Model: GadaiMasterKategori
- Model: JnsLokasiPerusahaan

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function index(Request $request)
    {
        $query = GadaiActive::with(['nasabah.user', 'kategori', 'item', 'lokasi']);

        if ($request->filled('kategori')) {
            $query->whereHas('kategori', function($q) use ($request) {
                $q->where('kode_kategori', $request->kategori);
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('cabang')) {
            $query->where('lokasi_id', $request->cabang);
        }

        $gadiList = $query->orderBy('created_at', 'desc')->get();
        $kategoriList = GadaiMasterKategori::all();
        $lokasiList = JnsLokasiPerusahaan::all();

        return view('admin.gadai_baru.index', compact('gadiList', 'kategoriList', 'lokasiList'));
    }
```
</details>

### Route: `admin/gadai_baru/storage` [GET|HEAD]
**Function:** `storage`

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function storage(Request $request)
    {
        $kategori = $request->get('kategori', 'electronic');
        
        $table = '';
        switch ($kategori) {
            case 'electronic': $table = 'tbl_gadai_grid_electronic'; break;
            case 'vehicle': $table = 'tbl_gadai_grid_vehicle'; break;
            case 'gold': $table = 'tbl_gadai_grid_gold'; break;
            default: $table = 'tbl_gadai_grid_electronic'; break;
        }

        $grid = DB::table($table)
            ->leftJoin('tbl_gadai_active', "$table.active_gadai_id", '=', 'tbl_gadai_active.id')
            ->leftJoin('tbl_gadai_master_item', 'tbl_gadai_active.item_id', '=', 'tbl_gadai_master_item.id')
            ->leftJoin('tbl_nasabah', 'tbl_gadai_active.nasabah_id', '=', 'tbl_nasabah.id')
            ->leftJoin('users', 'tbl_nasabah.user_id', '=', 'users.id')
            ->select(
                "$table.*", 
                'users.nama as nasabah_nama', 
                'tbl_gadai_master_item.head_1 as item_nama', 
                'tbl_gadai_active.status as gadai_status', 
                'tbl_gadai_active.id as active_gadai_id'
            )
            ->orderBy('baris', 'desc')
            ->orderBy('kolom', 'asc')
            ->get();
            
        $groupedGrid = $grid->groupBy('baris');

        return view('admin.gadai_baru.storage', compact('groupedGrid', 'kategori'));
    }
```
</details>

### Route: `admin/gadai_baru/storage/empty-auction` [POST]
**Function:** `emptyAuction`

**Queries Detected:**
- Model: GadaiActive
- Model: GadaiSlotLog
- Model: GadaiHistory
- Model: GadaiFile

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function emptyAuction(Request $request)
    {
        $request->validate([
            'gadai_id' => 'required|exists:tbl_gadai_active,id',
            'catatan' => 'required|string|min:5',
            'foto_bukti' => 'required|array|min:1',
            'foto_bukti.*' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'catatan.required' => 'Catatan alasan/detail pengambilan barang wajib diisi.',
            'catatan.min' => 'Catatan minimal 5 karakter.',
            'foto_bukti.required' => 'Wajib melampirkan minimal 1 foto bukti pengambilan.',
            'foto_bukti.min' => 'Wajib melampirkan minimal 1 foto bukti pengambilan.',
            'foto_bukti.*.image' => 'File bukti harus berupa foto/gambar.',
            'foto_bukti.*.max' => 'Ukuran foto maksimal adalah 2MB.'
        ]);

        $gadai = GadaiActive::findOrFail($request->gadai_id);

        if ($gadai->status !== 'expired_final') {
            return back()->with('error', 'Barang gadai ini belum berstatus hangus, tidak bisa dikosongkan untuk lelang.');
        }

        DB::beginTransaction();
        try {
            // 1. Update Grid Slot (Set occupied to false, active_gadai_id to null)
            $table = '';
            switch ($gadai->slot_table) {
                case 'electronic': $table = 'tbl_gadai_grid_electronic'; break;
                case 'vehicle': $table = 'tbl_gadai_grid_vehicle'; break;
                case 'gold': $table = 'tbl_gadai_grid_gold'; break;
                default: throw new \Exception("Kategori slot tidak valid.");
            }

            DB::table($table)->where('kode_slot', $gadai->slot_kode)->update([
                'is_occupied' => false,
                'active_gadai_id' => null
            ]);

            // 2. Create record for GadaiSlotLog (empty)
            GadaiSlotLog::create([
                'slot_kode' => $gadai->slot_kode,
                'kategori' => $gadai->slot_table,
                'aksi' => 'empty',
                'gadai_active_id' => $gadai->id
            ]);

            // 3. Update Gadai Active status to 'auctioned'
            $gadai->update([
                'status' => 'auctioned'
            ]);

            // 4. Create History
            GadaiHistory::create([
                'gadai_active_id' => $gadai->id,
                'aksi' => 'auction',
                'catatan' => 'Barang diambil dari penyimpanan untuk proses lelang. Catatan: ' . $request->catatan
            ]);

            // 5. Save proof photos to tbl_gadai_files
            if ($request->hasFile('foto_bukti')) {
                foreach ($request->file('foto_bukti') as $file) {
                    $path = $file->store('gadai_files', 'public');
                    GadaiFile::create([
                        'gadai_active_id' => $gadai->id,
                        'path_file' => $path,
                        'tipe_foto' => 'lainnya'
                    ]);
                }
            }

            DB::commit();
            return back()->with('success', 'Barang pada slot ' . $gadai->slot_kode . ' berhasil diambil untuk dilelang dan kapasitas slot telah dikosongkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
```
</details>

### Route: `admin/gadai_baru/create` [GET|HEAD]
**Function:** `create`

**Queries Detected:**
- Table: tbl_gadai_grid_electronic
- Table: tbl_gadai_grid_vehicle
- Table: tbl_gadai_grid_gold
- Model: Nasabah
- Model: BankAccessService
- Model: GadaiMasterKategori
- Model: GadaiMasterItem
- Model: JnsLokasiPerusahaan
- Model: PettyCashSaldo
- Model: BiayaTransfer

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function create()
    {
        $nasabahs = Nasabah::with(['user', 'dataRek'])->get();
        $bankService = app(\App\Services\BankAccessService::class);
        $nasabahs->each(function ($n) use ($bankService) {
            $n->saldo_tabungan = $bankService->getSaldoTabungan($n->id);
        });

        $kategoriList = GadaiMasterKategori::all();
        $itemList = GadaiMasterItem::all();
        $lokasiList = JnsLokasiPerusahaan::all();

        $availableSlots = [
            'electronic' => DB::table('tbl_gadai_grid_electronic')->where('is_occupied', false)->orderBy('baris')->orderBy('kolom')->get(),
            'vehicle' => DB::table('tbl_gadai_grid_vehicle')->where('is_occupied', false)->orderBy('baris')->orderBy('kolom')->get(),
            'gold' => DB::table('tbl_gadai_grid_gold')->where('is_occupied', false)->orderBy('baris')->orderBy('kolom')->get(),
        ];

        $adminId = Auth::id();
        $adminSaldoCash = PettyCashSaldo::getSaldo($adminId, 'admin', 'cash', 'other');
        $adminSaldoTransfer = PettyCashSaldo::getSaldo($adminId, 'admin', 'transfer', 'other');
        $biayaTransfer = \App\Models\BiayaTransfer::where('is_active', true)->get();

        return view('admin.gadai_baru.create', compact(
            'nasabahs', 
            'kategoriList', 
            'itemList', 
            'lokasiList', 
            'availableSlots', 
            'adminSaldoCash', 
            'adminSaldoTransfer', 
            'biayaTransfer'
        ));
    }
```
</details>

### Route: `admin/gadai_baru/store` [POST]
**Function:** `store`

**Queries Detected:**
- Model: GadaiMasterItem
- Model: GadaiMasterKategori
- Model: PettyCashSaldo
- Model: GadaiActive
- Model: GadaiSlotLog
- Model: GadaiHistory
- Model: GadaiFile
- Model: BankAccessService

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function store(Request $request)
    {
        $request->validate([
            'nasabah_id' => 'required|exists:tbl_nasabah,id',
            'kategori_id' => 'required|exists:tbl_gadai_master_kategori,id',
            'item_id' => 'required|exists:tbl_gadai_master_item,id',
            'lokasi_id' => 'required|exists:jns_lokasi_perusahaan,id',
            'slot_kode' => 'required|string',
            'nominal_deal' => 'required|numeric|min:1',
            'metode_pencairan' => 'required|in:cash,transfer',
            'foto_bukti.*' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $item = GadaiMasterItem::findOrFail($request->item_id);
        if ($request->nominal_deal > $item->nominal_high) {
            return back()->with('error', 'Nominal deal tidak boleh melebihi taksiran maksimal (Rp ' . number_format($item->nominal_high, 0, ',', '.') . ')');
        }

        $kategori = GadaiMasterKategori::findOrFail($request->kategori_id);
        
        // Calculate Fees
        $rateJasa = $kategori->rate_jasa;
        $biayaJasa = ($request->nominal_deal * $rateJasa) / 100;

        // Calculate Biaya Inap upfront (flat for vehicles, percentage for gold/electronics)
        $biayaInap = 0;
        if ($item->nominal_inap > 0) {
            $biayaInap = $item->nominal_inap;
        } else {
            $biayaInap = ($request->nominal_deal * $kategori->rate_inap_persen) / 100;
        }
        
        // Petty Cash Check & Mutasi (Uang keluar dari Admin ke Nasabah dari Modal Awal)
        $adminId = Auth::id();
        $metode = $request->metode_pencairan;
        if (!PettyCashSaldo::validatePenarikan($adminId, $request->nominal_deal, $metode, 'other')) {
            return back()->with('error', 'Saldo Petty Cash (' . strtoupper($metode) . ') pada Modal Awal tidak mencukupi untuk pencairan gadai.');
        }

        DB::beginTransaction();
        try {
            // Allocate Slot
            $slotData = $this->allocateSlot($kategori->kode_kategori, $request->slot_kode);

            $tglMulai = now();
            $tglJatuhTempo = now()->addDays($kategori->masa_gadai_hari)->endOfDay();
            $tglTenggang = $tglJatuhTempo->copy()->addDays($kategori->masa_tenggang_hari)->endOfDay();

            $gadai = GadaiActive::create([
                'nasabah_id' => $request->nasabah_id,
                'kategori_id' => $kategori->id,
                'item_id' => $item->id,
                'lokasi_id' => $request->lokasi_id,
                'slot_kode' => $slotData->kode_slot,
                'slot_table' => $kategori->kode_kategori,
                'nominal_deal' => $request->nominal_deal,
                'biaya_jasa' => $biayaJasa,
                'denda_aktif' => 0,
                'biaya_inap' => $biayaInap,
                'tgl_mulai' => $tglMulai,
                'tgl_jatuh_tempo' => $tglJatuhTempo,
                'tgl_tenggang' => $tglTenggang,
                'status' => 'active',
                'admin_id' => $adminId
            ]);

            // Update Slot with active ID
            $gridTable = $this->getGridTableName($kategori->kode_kategori);
            DB::table($gridTable)->where('id', $slotData->id)->update(['active_gadai_id' => $gadai->id]);

            // Log Slot
            GadaiSlotLog::create([
                'slot_kode' => $slotData->kode_slot,
                'kategori' => $kategori->kode_kategori,
                'aksi' => 'fill',
                'gadai_active_id' => $gadai->id
            ]);

            // History
            GadaiHistory::create([
                'gadai_active_id' => $gadai->id,
                'aksi' => 'create',
                'catatan' => 'Gadai baru dibuat. Slot: ' . $slotData->kode_slot
            ]);

            // Upload Files
            if ($request->hasFile('foto_bukti')) {
                foreach ($request->file('foto_bukti') as $file) {
                    $path = $file->store('gadai_files', 'public');
                    GadaiFile::create([
                        'gadai_active_id' => $gadai->id,
                        'path_file' => $path,
                        'tipe_foto' => 'barang'
                    ]);
                }
            }

            // Petty Cash Withdrawal (Dari Modal Awal)
            PettyCashSaldo::buatMutasi(
                $adminId, 
                'admin', 
                -$request->nominal_deal, 
                'Pencairan Gadai Baru ' . $gadai->slot_kode, 
                $gadai->id, 
                'tbl_gadai_active', 
                $metode, 
                'other'
            );

            // 🔥 INTEGRASI BIAYA TRANSFER ANTARBANK
            if ($metode === 'transfer') {
                $bankService = app(\App\Services\BankAccessService::class);
                $namaBank = $bankService->getNamaBank($request->nasabah_id);
                
                if ($namaBank && !$bankService->isBcaUser($request->nasabah_id)) {
                    $potong = $bankService->potongBiayaTransfer(
                        $request->nasabah_id,
                        $namaBank,
                        'Pencairan Gadai Baru ' . $gadai->slot_kode,
                        $adminId
                    );
                    
                    if (!$potong['success']) {
                        throw new \Exception($potong['message']);
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.gadai_baru.detail', $gadai->id)->with('success', 'Gadai berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
```
</details>

### Route: `admin/gadai_baru/{id}` [GET|HEAD]
**Function:** `detail`

**Queries Detected:**
- Model: GadaiActive

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function detail($id)
    {
        $gadai = GadaiActive::with(['nasabah.user', 'kategori', 'item', 'lokasi', 'files', 'history', 'paymentLogs'])->findOrFail($id);
        return view('admin.gadai_baru.detail', compact('gadai'));
    }
```
</details>

## Controller: `App\Http\Controllers\GadaiBaruActionController`

**Models Imported:**
- `App\Models\GadaiActive`
- `App\Models\GadaiHistory`
- `App\Models\GadaiPaymentLog`
- `App\Models\GadaiSlotLog`
- `App\Models\PettyCashSaldo`

### Route: `admin/gadai_baru/{id}/perpanjang` [POST]
**Function:** `perpanjang`

_Method not found in controller._

### Route: `admin/gadai_baru/{id}/lunas` [POST]
**Function:** `lunas`

_Method not found in controller._

### Route: `admin/gadai_baru/{id}/lelang` [POST]
**Function:** `lelang`

_Method not found in controller._

## Controller: `App\Http\Controllers\Admin\AdminPengajuanGadaiController`

**Models Imported:**
- `App\Models\GadaiActive`
- `App\Models\GadaiHistory`
- `App\Models\GadaiPaymentLog`
- `App\Models\GadaiSlotLog`
- `App\Models\GadaiPengajuan`
- `App\Models\PettyCashSaldo`

### Route: `admin/gadai_baru/pengajuan/list` [GET|HEAD]
**Function:** `index`

**Queries Detected:**
- Model: GadaiPengajuan

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function index()
    {
        $pengajuan = GadaiPengajuan::with(['nasabah.user', 'gadaiActive.item', 'gadaiActive.kategori', 'files'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();
            
        return view('admin.gadai_baru.pengajuan_index', compact('pengajuan'));
    }
```
</details>

### Route: `admin/gadai_baru/pengajuan/{id}/approve` [POST]
**Function:** `approve`

**Queries Detected:**
- Model: GadaiPengajuan
- Model: User
- Model: IdGenerator
- Model: PettyCashOwnerTransaksi
- Model: PettyCashConstants
- Model: PettyCashSaldo
- Model: GadaiHistory
- Model: GadaiSlotLog
- Model: GadaiPaymentLog
- Model: PettyCashTransaksiNasabah
- Model: GadaiFile
- Model: ActivityLogService

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function approve(Request $request, $id)
    {
        $pengajuan = GadaiPengajuan::with('gadaiActive.kategori')->findOrFail($id);
        
        if ($pengajuan->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $gadai = $pengajuan->gadaiActive;

        DB::beginTransaction();
        try {
            $adminId = Auth::id();

            // 1. Process Petty Cash / Owner Mutation
            if ($pengajuan->metode === 'transfer') {
                $owner = \App\Models\User::where('role', 'admin_utama')->first();
                if ($owner) {
                    $ownerTransId = \App\Helpers\IdGenerator::generate('petty_cash_owner_transaksi', 'PCOW', 'OW', 'TR');
                    \App\Models\PettyCashOwnerTransaksi::create([
                        'id'           => $ownerTransId,
                        'user_id'      => $owner->id,
                        'tipe'         => 'terima_setoran',
                        'sumber'       => \App\Services\PettyCashConstants::SUMBER_GADAI,
                        'nominal_cash' => 0,
                        'nominal_tf'   => (float) $pengajuan->nominal,
                        'keterangan'   => ucfirst($pengajuan->jenis_pengajuan) . " Gadai: " . ($pengajuan->nasabah->user->nama ?? '-') . " (#{$pengajuan->id})",
                        'ref_table'    => \App\Services\PettyCashConstants::REF_GADAI_P,
                        'ref_id'       => $pengajuan->id,
                    ]);

                    \App\Models\PettyCashSaldo::buatMutasi(
                        $owner->id, 
                        'owner', 
                        (float)$pengajuan->nominal,
                        ucfirst($pengajuan->jenis_pengajuan) . " Gadai (#{$pengajuan->id})",
                        $pengajuan->id, 
                        \App\Services\PettyCashConstants::REF_GADAI_P, 
                        'transfer', 
                        'gadai'
                    );
                }
            } else {
                PettyCashSaldo::buatMutasi(
                    $adminId,
                    'admin',
                    $pengajuan->nominal,
                    ucfirst($pengajuan->jenis_pengajuan) . ' Gadai ' . $gadai->slot_kode . ' (via ' . $pengajuan->metode . ')',
                    $gadai->id,
                    'tbl_gadai_active',
                    $pengajuan->metode,
                    'gadai'
                );
            }

            // 2. Process Gadai Logic based on type
            if ($pengajuan->jenis_pengajuan == 'perpanjang' || $pengajuan->jenis_pengajuan == 'perpanjangan') {
                if (!in_array($gadai->status, ['active', 'grace_period'])) {
                    throw new \Exception('Perpanjangan hanya dapat dilakukan untuk gadai aktif atau dalam masa tenggang.');
                }
                if ($gadai->jumlah_perpanjangan >= 3) {
                    throw new \Exception('Maksimal perpanjangan adalah 3 kali.');
                }
                $newJatuhTempo = $gadai->tgl_jatuh_tempo->copy()->addDays($gadai->kategori->masa_gadai_hari)->endOfDay();
                $newTenggang = $newJatuhTempo->copy()->addDays($gadai->kategori->masa_tenggang_hari)->endOfDay();

                // Recalculate interest and inap for the next period
                $rateJasa = $gadai->kategori->rate_jasa;
                $newBiayaJasa = ($gadai->nominal_deal * $rateJasa) / 100;

                $newBiayaInap = 0;
                if ($gadai->item->nominal_inap > 0) {
                    $newBiayaInap = $gadai->item->nominal_inap;
                } else {
                    $newBiayaInap = ($gadai->nominal_deal * $gadai->kategori->rate_inap_persen) / 100;
                }

                $gadai->update([
                    'tgl_jatuh_tempo' => $newJatuhTempo,
                    'tgl_tenggang' => $newTenggang,
                    'jumlah_perpanjangan' => $gadai->jumlah_perpanjangan + 1,
                    'status' => 'active',
                    'biaya_jasa' => $newBiayaJasa, // Interest for the new period
                    'denda_aktif' => 0,
                    'biaya_inap' => $newBiayaInap
                ]);

                GadaiHistory::create([
                    'gadai_active_id' => $gadai->id,
                    'aksi' => 'extend',
                    'catatan' => 'Perpanjangan ke-' . $gadai->jumlah_perpanjangan . ' (Approved from Pengajuan #' . $pengajuan->id . ')'
                ]);
            } else if ($pengajuan->jenis_pengajuan == 'lunas') {
                $gadaiUpdateData = ['status' => 'lunas'];
                
                // Handle Extra Pinjaman for LUNAS
                if ($request->filled('extra_pinjaman_nominal') && $request->extra_pinjaman_nominal > 0) {
                    if (!$request->filled('extra_pinjaman_reason')) {
                        throw new \Exception('Alasan extra pinjaman harus diisi jika nominal extra lebih dari 0.');
                    }
                    $gadaiUpdateData['extra_pinjaman_nominal'] = $request->extra_pinjaman_nominal;
                    $gadaiUpdateData['extra_pinjaman_reason'] = $request->extra_pinjaman_reason;
                    $gadaiUpdateData['extra_pinjaman_admin_id'] = $adminId;
                    $gadaiUpdateData['extra_pinjaman_set_at'] = now();
                    
                    // Create mutation for the extra pinjaman (cash only)
                    PettyCashSaldo::buatMutasi(
                        $adminId,
                        'admin',
                        $request->extra_pinjaman_nominal,
                        'Extra Pinjaman/Denda Gadai ' . $gadai->slot_kode . ' (' . $request->extra_pinjaman_reason . ')',
                        $gadai->id,
                        'tbl_gadai_active',
                        'cash',
                        'gadai'
                    );
                }
                
                $gadai->update($gadaiUpdateData);

                // Free the slot
                $gridTable = $this->getGridTableName($gadai->slot_table);
                DB::table($gridTable)
                    ->where('kode_slot', $gadai->slot_kode)
                    ->update(['is_occupied' => false, 'active_gadai_id' => null]);

                GadaiSlotLog::create([
                    'slot_kode' => $gadai->slot_kode,
                    'kategori' => $gadai->slot_table,
                    'aksi' => 'empty',
                    'gadai_active_id' => $gadai->id
                ]);

                GadaiHistory::create([
                    'gadai_active_id' => $gadai->id,
                    'aksi' => 'lunas',
                    'catatan' => 'Gadai telah dilunasi (Approved from Pengajuan #' . $pengajuan->id . ')'
                ]);
            }

            // 3. Log Payment
            GadaiPaymentLog::create([
                'gadai_active_id' => $gadai->id,
                'jenis_pembayaran' => ($pengajuan->jenis_pengajuan == 'lunas') ? 'tebus' : 'perpanjangan',
                'nominal' => $pengajuan->nominal,
                'metode' => $pengajuan->metode
            ]);

            // 4. Update Pengajuan Status
            $pengajuan->update([
                'status' => 'approved',
                'admin_id' => $adminId,
                'admin_keterangan' => $request->admin_keterangan,
                'processed_at' => now()
            ]);

            // 5. Create record for Setoran Kantor queue (ONLY for cash payment, because transfer goes directly to owner)
            if ($pengajuan->metode === 'cash') {
                \App\Models\PettyCashTransaksiNasabah::create([
                    'id' => \App\Helpers\IdGenerator::generate('petty_cash_transaksi_nasabah', 'PCTN', 'AD', 'TR'),
                    'admin_id' => $adminId,
                    'nasabah_id' => $pengajuan->nasabah_id,
                    'id_jns_transaksi' => \App\Services\PettyCashConstants::JNS_PMB, // Pembayaran
                    'id_jns_via' => \App\Services\PettyCashConstants::VIA_CS,
                    'id_jns_fitur' => \App\Services\PettyCashConstants::FITUR_GADAI,
                    'nominal' => $pengajuan->nominal,
                    'status' => 'approved',
                    'keterangan' => ucfirst($pengajuan->jenis_pengajuan) . ' Gadai ' . $gadai->slot_kode,
                    'ref_table' => \App\Services\PettyCashConstants::REF_GADAI_P,
                    'ref_id' => $pengajuan->id,
                    'tgl_transaksi' => now()
                ]);
            }

            // 6. Handle Admin Proof Photos
            if ($request->hasFile('admin_bukti_foto')) {
                foreach ($request->file('admin_bukti_foto') as $file) {
                    $path = $file->store('admin_bukti_gadai', 'public');
                    \App\Models\GadaiFile::create([
                        'gadai_active_id' => $gadai->id,
                        'pengajuan_id' => $pengajuan->id,
                        'path_file' => $path,
                        'tipe_foto' => 'penyerahan' // We use 'penyerahan' for admin proof
                    ]);
                }
            }

            DB::commit();

            app(\App\Services\ActivityLogService::class)->logApprovePengajuanGadai((string)$pengajuan->id, (float)$pengajuan->nominal, $pengajuan->nasabah->user->nama ?? 'N/A');

            if ($pengajuan->jenis_pengajuan == 'lunas') {
                app(\App\Services\ActivityLogService::class)->logPelunasanGadai((string)$gadai->id, (float)$pengajuan->nominal, $pengajuan->nasabah->user->nama ?? 'N/A');
            }

            return back()->with('success', 'Pengajuan ' . $pengajuan->jenis_pengajuan . ' berhasil disetujui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
```
</details>

### Route: `admin/gadai_baru/pengajuan/{id}/reject` [POST]
**Function:** `reject`

**Queries Detected:**
- Model: GadaiPengajuan
- Model: ActivityLogService

<details><summary><b>Lihat Kode Function</b></summary>

```php
    public function reject(Request $request, $id)
    {
        $pengajuan = GadaiPengajuan::with('nasabah.user')->findOrFail($id);
        
        if ($pengajuan->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $pengajuan->update([
            'status' => 'rejected',
            'admin_id' => Auth::id(),
            'processed_at' => now(),
            'keterangan' => $request->keterangan ?? 'Ditolak oleh admin'
        ]);

        app(\App\Services\ActivityLogService::class)->logRejectPengajuanGadai((string)$pengajuan->id, (float)$pengajuan->nominal, $pengajuan->nasabah->user->nama ?? 'N/A', $request->keterangan ?? 'Ditolak oleh admin');

        return back()->with('success', 'Pengajuan berhasil ditolak.');
    }
```
</details>

