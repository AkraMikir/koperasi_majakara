# Laporan Teks Bahasa Inggris - Koperasi Majakara

> **Tanggal Scan**: 23 Mei 2026 11.41
> **Total File Blade**: 213
> **File dengan Teks Inggris**: 212
> **Total Baris Terdeteksi**: 4883

---

## ðŸ„ `admin\activity-log\admin-operasional.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 79 | `Total` | UI | <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1">Total</p> |
| 87 | `` | UI | <form method="GET" action="{{ route('admin.activity-log.admin-operasional') }}" class="flex flex-wrap gap-3"> |
| 93 | `` | UI | <input type="text" name="search" value="{{ request('search') }}" |
| 98 | `` | UI | <select name="role" class="px-3 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:border-[#674c1d] outline-none bg-white min-w-40"> |
| 102 | `` | UI | </select> |
| 103 | `` | UI | <select name="module" class="px-3 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:border-[#674c1d] outline-none bg-white min-w-36"> |
| 109 | `` | UI | </select> |
| 110 | `` | UI | <input type="date" name="date_from" value="{{ request('date_from') }}" |
| 112 | `` | UI | <input type="date" name="date_to" value="{{ request('date_to') }}" |
| 114 | `` | UI | <button type="submit" |
| 116 | `Filter` | UI | Filter |
| 118 | `` | UI | @if(request()->hasAny(['search', 'role', 'module', 'action', 'date_from', 'date_to'])) |
| 121 | `Reset` | UI | Reset |
| 132 | `` | UI | <p class="text-sm text-gray-500 mt-0.5">{{ $logs->total() }} aktivitas ditemukan</p> |
| 145 | `` | PHP | $actionLabel = str_replace('_', ' ', $log->action); |
| 172 | `` | UI | <p class="text-sm text-gray-700">{{ $log->description }}</p> |
| 207 | `` | UI | <button type="button" @click="open = !open" |
| 212 | `` | UI | <span x-text="open ? 'Sembunyikan detail' : 'Lihat detail'"></span> |
| 214 | `` | UI | <div x-show="open" x-transition class="mt-2 p-3 bg-gray-50 rounded-xl border border-gray-200 text-xs"> |

## ðŸ„ `admin\activity-log\nasabah.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 71 | `Total` | UI | <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1">Total</p> |
| 79 | `` | UI | <form method="GET" action="{{ route('admin.activity-log.nasabah') }}" class="flex flex-wrap gap-3"> |
| 85 | `` | UI | <input type="text" name="search" value="{{ request('search') }}" |
| 90 | `` | UI | <select name="module" class="px-3 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:border-[#674c1d] outline-none bg-white min-w-36"> |
| 96 | `` | UI | </select> |
| 97 | `` | UI | <input type="date" name="date_from" value="{{ request('date_from') }}" |
| 100 | `` | UI | <input type="date" name="date_to" value="{{ request('date_to') }}" |
| 103 | `` | UI | <button type="submit" |
| 105 | `Filter` | UI | Filter |
| 107 | `` | UI | @if(request()->hasAny(['search', 'module', 'action', 'date_from', 'date_to'])) |
| 110 | `Reset` | UI | Reset |
| 121 | `` | UI | <p class="text-sm text-gray-500 mt-0.5">{{ $logs->total() }} aktivitas ditemukan</p> |
| 132 | `` | PHP | $actionLabel = str_replace('_', ' ', $log->action); |
| 156 | `` | UI | <p class="text-sm text-gray-700">{{ $log->description }}</p> |
| 171 | `` | UI | <button type="button" @click="open = !open" |
| 176 | `` | UI | <span x-text="open ? 'Sembunyikan detail' : 'Lihat detail'"></span> |
| 178 | `` | UI | <div x-show="open" x-transition class="mt-2 p-3 bg-gray-50 rounded-xl border border-gray-200 text-xs"> |

## ðŸ„ `admin\dashboard.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Dashboard` | UI | @section('title', 'Dashboard') |
| 10 | `Dashboard` | UI | <h1 class="text-2xl md:text-3xl font-bold text-gray-900 font-display">Dashboard Admin</h1> |
| 32 | `Total` | UI | <!-- Total Nasabah --> |
| 37 | `Total` | UI | <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Total Anggota</p> |
| 50 | `Total` | UI | <!-- Total Aset --> |
| 55 | `Total` | UI | <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Total Aset Masuk</p> |
| 63 | `Total` | UI | <span class="text-[10px] text-gray-400 font-medium truncate">Total Tabungan & Deposito</span> |
| 67 | `Total` | UI | <!-- Total Penyaluran --> |
| 80 | `Total` | UI | <span class="text-[10px] text-gray-400 font-medium truncate">Total Pinjaman & Gadai Aktif</span> |
| 135 | `` | UI | @if($aktivitas['type'] === 'tabungan') bg-blue-50 text-blue-500 |
| 136 | `` | UI | @elseif($aktivitas['type'] === 'pinjaman') bg-yellow-50 text-yellow-500 |
| 137 | `` | UI | @elseif($aktivitas['type'] === 'deposito') bg-green-50 text-green-500 |
| 140 | `` | UI | @if($aktivitas['type'] === 'tabungan') |
| 142 | `` | UI | @elseif($aktivitas['type'] === 'pinjaman') |
| 163 | `Pending` | UI | <!-- Pengajuan Pending --> |
| 202 | `` | UI | {{ $pengajuan['label'] ?? $pengajuan['type'] }} |
| 213 | `` | UI | <form action="{{ $pengajuan['route_approve'] ?? '#' }}" method="POST" class="inline"> |
| 215 | `` | UI | <button type="button" onclick="confirmApprove(this)" class="p-2 bg-green-50 text-green-600 hover:bg-green-500 hover:text-white rounded-lg transition-colors border border-green-100 hover:border-green-600 shadow-sm" title="Setujui"> |
| 219 | `` | UI | <form action="{{ $pengajuan['route_reject'] ?? '#' }}" method="POST" class="inline"> |
| 221 | `` | UI | <button type="button" onclick="confirmReject(this)" class="p-2 bg-red-50 text-red-600 hover:bg-red-500 hover:text-white rounded-lg transition-colors border border-red-100 hover:border-red-600 shadow-sm" title="Tolak"> |
| 225 | `Detail` | UI | <a href="{{ $pengajuan['route_index'] ?? '#' }}" class="p-2 bg-gray-50 text-gray-500 hover:bg-gray-200 hover:text-gray-700 rounded-lg transition-colors border border-gray-200 shadow-sm" title="Detail"> |
| 285 | `` | UI | type: 'line', |
| 394 | `` | UI | btn.closest('form').submit(); |
| 403 | `` | UI | icon: 'warning', |
| 411 | `` | UI | btn.closest('form').submit(); |

## ðŸ„ `admin\deposito\deposito-detail.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Detail` | UI | @section('title', 'Detail Deposito') |
| 22 | `` | UI | @if($deposito->status === 'aktif') |
| 24 | `` | UI | @elseif($deposito->status === 'dicairkan') |
| 27 | `` | UI | <span class="bg-gray-100 text-gray-500 px-3 py-1 rounded-full text-xs font-bold">{{ ucfirst($deposito->status) }}</span> |
| 35 | `` | UI | <p class="text-xs text-gray-500">{{ $deposito->nasabah->user->email ?? '-' }}</p> |
| 57 | `` | UI | @if($deposito->status === 'aktif' && $deposito->tgl_jatuh_tempo) |
| 79 | `` | UI | @if($deposito->tgl_mulai && $deposito->tgl_jatuh_tempo && $deposito->status === 'aktif') |
| 115 | `Total` | UI | <p class="text-xs text-gray-500 mb-1">Total Pencairan</p> |
| 123 | `` | PHP | $persiapan = $deposito->persiapanCair->whereIn('status', ['tentatif', 'diproses'])->first(); |
| 132 | `` | UI | {{ $persiapan->status === 'tentatif' ? 'bg-yellow-100 text-yellow-700' : 'bg-blue-100 text-blue-700' }}"> |
| 133 | `` | UI | {{ $persiapan->status }} |
| 139 | `Total` | UI | <p class="text-[10px] text-gray-500 uppercase font-bold">Total Disiapkan</p> |
| 149 | `` | UI | @if($persiapan->status === 'tentatif') |
| 152 | `` | UI | <form action="{{ route('admin.deposito.peringatan.update', $persiapan->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-3 items-end"> |
| 156 | `` | UI | <select name="metode_cair" class="w-full border-gray-200 rounded-lg text-xs py-2"> |
| 158 | `Transfer` | UI | <option value="rek_nasabah" {{ $persiapan->metode_cair === 'rek_nasabah' ? 'selected' : '' }}>Transfer Bank</option> |
| 160 | `` | UI | </select> |
| 164 | `` | UI | <input type="text" name="catatan" value="{{ $persiapan->catatan }}" placeholder="Internal note..." class="w-full border-gray-200 rounded-lg text-xs py-2"> |
| 166 | `` | UI | <button type="submit" class="bg-[#674c1d] text-white py-2 rounded-lg text-xs font-bold hover:bg-[#4a3514] transition"> |
| 167 | `Update` | UI | Update Rencana |
| 175 | `` | UI | <form action="{{ route('admin.deposito.peringatan.send-dana', $persiapan->id) }}" method="POST" class="flex gap-2"> |
| 177 | `` | UI | <select name="admin_id" required class="flex-1 border-gray-200 rounded-lg text-xs py-2"> |
| 182 | `` | UI | </select> |
| 183 | `` | UI | <button type="submit" onclick="return confirm('Kirim Rp {{ number_format($persiapan->total_dibayar, 0, ',', '.') }} ke Admin sekarang?')" |
| 250 | `Transfer` | UI | <p class="text-xs text-gray-500 mb-2">Bukti Transfer Setor Awal</p> |
| 262 | `Transfer` | UI | <p class="text-xs text-gray-500 mb-2">Bukti Transfer Pencairan</p> |

## ðŸ„ `admin\deposito\deposito-list.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 21 | `` | UI | <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / no. deposito..." class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#674c1d] w-64"> |
| 22 | `` | UI | <select name="status" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#674c1d]"> |
| 23 | `Status` | UI | <option value="">Semua Status</option> |
| 24 | `` | UI | <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option> |
| 25 | `` | UI | <option value="dicairkan" {{ request('status') === 'dicairkan' ? 'selected' : '' }}>Dicairkan</option> |
| 26 | `` | UI | <option value="ditutup" {{ request('status') === 'ditutup' ? 'selected' : '' }}>Ditutup</option> |
| 27 | `` | UI | </select> |
| 28 | `Filter` | UI | <button type="submit" class="bg-[#674c1d] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-[#8b6f2f]">Filter</button> |
| 31 | `` | UI | <a href="{{ route('admin.deposito.export-pdf', request()->all()) }}" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-700 flex items-center gap-2"> |
| 48 | `Status` | UI | <th class="text-left px-4 py-3 font-semibold">Status</th> |
| 64 | `` | UI | @if($d->status === 'aktif') |
| 66 | `` | UI | @elseif($d->status === 'dicairkan') |
| 73 | `Detail` | UI | <a href="{{ route('admin.deposito.deposito-detail', $d->id) }}" class="text-[#674c1d] hover:underline text-xs font-semibold">Detail →</a> |

## ðŸ„ `admin\deposito\detail-pengajuan.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Detail` | UI | @section('title', 'Detail Pengajuan Deposito') |
| 15 | `` | UI | @if(session('success')) |
| 16 | `` | UI | <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">{{ session('success') }}</div> |
| 18 | `` | UI | @if(session('error')) |
| 19 | `` | UI | <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">{{ session('error') }}</div> |
| 24 | `Detail` | UI | <h1 class="text-lg font-bold text-gray-800">Detail Pengajuan Deposito</h1> |
| 25 | `` | UI | @if($pengajuan->status === '1') |
| 26 | `Pending` | UI | <span class="bg-amber-50 text-amber-600 px-3 py-1 rounded-full text-xs font-bold">Pending</span> |
| 27 | `` | UI | @elseif($pengajuan->status === '2') |
| 38 | `` | UI | <p class="text-xs text-gray-500">{{ $pengajuan->nasabah->user->email ?? '-' }}</p> |
| 54 | `` | UI | @if($pengajuan->metode_setor === 'transfer') |
| 55 | `Transfer` | UI | <span class="bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full text-xs font-bold">Transfer Bank</span> |
| 66 | `` | UI | @if($pengajuan->metode_setor === 'transfer' && $pengajuan->foto_bukti_tf) |
| 68 | `Transfer` | UI | <p class="text-xs text-gray-400 mb-2">Bukti Transfer</p> |
| 69 | `Transfer` | UI | <img src="{{ Storage::url($pengajuan->foto_bukti_tf) }}" alt="Bukti Transfer" class="max-h-64 rounded-lg border border-gray-200 object-contain"> |
| 71 | `` | UI | @elseif($pengajuan->metode_setor === 'transfer' && !$pengajuan->foto_bukti_tf) |
| 73 | `` | UI | ⚠ Nasabah belum mengupload bukti transfer. |
| 85 | `` | UI | @if($pengajuan->status === '1') |
| 88 | `` | UI | <form action="{{ route('admin.deposito.approve', $pengajuan->id) }}" method="POST"> |
| 93 | `` | UI | <textarea name="catatan_admin" rows="2" placeholder="Catatan untuk nasabah..." class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-green-500 resize-none mb-3"></textarea> |
| 95 | `` | UI | @if($pengajuan->metode_setor === 'transfer') |
| 97 | `` | UI | <select name="metode_bayar" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-green-500 mb-3" required> |
| 101 | `` | UI | </select> |
| 104 | `` | UI | <button type="submit" onclick="return confirm('Yakin ingin menyetujui pengajuan ini?')" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-xl text-sm transition"> |
| 110 | `` | UI | <form action="{{ route('admin.deposito.reject', $pengajuan->id) }}" method="POST"> |
| 115 | `` | UI | <textarea name="catatan_admin" rows="2" placeholder="Jelaskan alasan penolakan..." class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-red-400 resize-none" required></textarea> |
| 117 | `` | UI | <button type="submit" onclick="return confirm('Yakin ingin menolak pengajuan ini?')" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-3 rounded-xl text-sm transition"> |

## ðŸ„ `admin\deposito\export-pdf.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 28 | `Status` | UI | <th>Status</th> |
| 41 | `` | UI | <td>{{ ucfirst($d->status) }}</td> |

## ðŸ„ `admin\deposito\index.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Dashboard` | UI | @section('title', 'Dashboard Deposito') |
| 10 | `Dashboard` | UI | <h1 class="text-2xl font-bold text-gray-900">Dashboard Deposito</h1> |
| 22 | `Pending` | UI | <p class="text-xs text-gray-500 mb-1">Pengajuan Pending</p> |
| 24 | `` | UI | <p class="text-xs text-gray-400 mt-1">{{ $stats['pending_transfer'] }} transfer · {{ $stats['pending_tabungan'] }} tabungan</p> |
| 55 | `Pending` | UI | <h2 class="font-bold text-gray-800 text-sm">Pengajuan Pending</h2> |
| 64 | `` | UI | <a href="{{ route('admin.deposito.detail-pengajuan', $p->id) }}" class="flex-1 min-w-0 group"> |
| 69 | `` | UI | <span class="text-[10px] px-2 py-0.5 rounded-full {{ $p->metode_setor === 'transfer' ? 'bg-blue-50 text-blue-600' : 'bg-green-50 text-green-700' }}"> |
| 70 | `Transfer` | UI | {{ $p->metode_setor === 'transfer' ? 'Transfer' : 'Tabungan' }} |
| 73 | `` | UI | <form action="{{ route('admin.deposito.approve', $p->id) }}" method="POST" class="inline" onsubmit="return confirm('Setujui pengajuan ini?')"> |
| 75 | `` | UI | <button type="submit" class="p-1 rounded-md text-green-600 hover:bg-green-100" title="Setujui"> |
| 79 | `` | UI | <form action="{{ route('admin.deposito.reject', $p->id) }}" method="POST" class="inline" onsubmit="return confirm('Tolak pengajuan ini?')"> |
| 81 | `Dashboard` | UI | <input type="hidden" name="catatan_admin" value="Ditolak via Dashboard"> |
| 82 | `` | UI | <button type="submit" class="p-1 rounded-md text-red-600 hover:bg-red-100" title="Tolak"> |
| 90 | `` | UI | <div class="px-5 py-8 text-center text-gray-400 text-sm">Tidak ada pengajuan pending</div> |
| 103 | `` | UI | <a href="{{ route('admin.deposito.deposito-detail', $d->id) }}" class="flex items-center gap-3 px-5 py-3 hover:bg-[#674c1d]/5 transition"> |
| 131 | `` | UI | type: 'line', |

## ðŸ„ `admin\deposito\paket\create.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 22 | `` | UI | <form action="{{ route('admin.deposito.paket.store') }}" method="POST" class="p-6 md:p-8 space-y-6"> |
| 29 | `` | UI | <input type="text" name="nama_paket" id="nama_paket" required |
| 33 | `` | UI | @error('nama_paket') |
| 42 | `` | UI | <select name="tenor_bulan" id="tenor_bulan" required |
| 48 | `` | UI | </select> |
| 53 | `` | UI | @error('tenor_bulan') |
| 62 | `` | UI | <input type="number" step="0.01" min="0" name="suku_bunga" id="suku_bunga" required |
| 68 | `` | UI | @error('suku_bunga') |
| 78 | `` | UI | <input type="number" name="minimal_nominal" id="minimal_nominal" required min="0" |
| 83 | `` | UI | @error('minimal_nominal') |
| 93 | `` | UI | <input type="number" name="maksimal_nominal" id="maksimal_nominal" min="0" |
| 98 | `` | UI | @error('maksimal_nominal') |
| 103 | `Status` | UI | <!-- Status --> |
| 105 | `Status` | UI | <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label> |
| 107 | `` | UI | <select name="status" id="status" required |
| 109 | `` | UI | <option value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option> |
| 110 | `` | UI | <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option> |
| 111 | `` | UI | </select> |
| 116 | `` | UI | @error('status') |
| 125 | `` | UI | <select name="kategori_id" id="kategori_id" |
| 131 | `` | UI | </select> |
| 136 | `` | UI | @error('kategori_id') |
| 144 | `` | UI | <textarea name="keterangan" id="keterangan" rows="3" |
| 147 | `` | UI | @error('keterangan') |
| 157 | `` | UI | <button type="submit" class="px-5 py-2.5 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white font-medium rounded-xl hover:shadow-lg hover:shadow-[#674c1d]/20 transition-all duration-300"> |

## ðŸ„ `admin\deposito\paket\edit.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Edit` | UI | @section('title', 'Edit Paket Deposito') |
| 15 | `Edit` | UI | <h1 class="text-2xl font-bold text-gray-900 font-display">Edit Paket Deposito</h1> |
| 22 | `` | UI | <form action="{{ route('admin.deposito.paket.update', $paket->id) }}" method="POST" class="p-6 md:p-8 space-y-6"> |
| 30 | `` | UI | <input type="text" name="nama_paket" id="nama_paket" required |
| 34 | `` | UI | @error('nama_paket') |
| 43 | `` | UI | <select name="tenor_bulan" id="tenor_bulan" required |
| 49 | `` | UI | </select> |
| 54 | `` | UI | @error('tenor_bulan') |
| 63 | `` | UI | <input type="number" step="0.01" min="0" name="suku_bunga" id="suku_bunga" required |
| 69 | `` | UI | @error('suku_bunga') |
| 79 | `` | UI | <input type="number" name="minimal_nominal" id="minimal_nominal" required min="0" |
| 84 | `` | UI | @error('minimal_nominal') |
| 94 | `` | UI | <input type="number" name="maksimal_nominal" id="maksimal_nominal" min="0" |
| 99 | `` | UI | @error('maksimal_nominal') |
| 104 | `Status` | UI | <!-- Status --> |
| 106 | `Status` | UI | <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label> |
| 108 | `` | UI | <select name="status" id="status" required |
| 110 | `` | UI | <option value="aktif" {{ old('status', $paket->status) == 'aktif' ? 'selected' : '' }}>Aktif</option> |
| 111 | `` | UI | <option value="nonaktif" {{ old('status', $paket->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option> |
| 112 | `` | UI | </select> |
| 117 | `` | UI | @error('status') |
| 126 | `` | UI | <select name="kategori_id" id="kategori_id" |
| 132 | `` | UI | </select> |
| 137 | `` | UI | @error('kategori_id') |
| 145 | `` | UI | <textarea name="keterangan" id="keterangan" rows="3" |
| 148 | `` | UI | @error('keterangan') |
| 158 | `` | UI | <button type="submit" class="px-5 py-2.5 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white font-medium rounded-xl hover:shadow-lg hover:shadow-[#674c1d]/20 transition-all duration-300"> |

## ðŸ„ `admin\deposito\paket\index.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 14 | `` | UI | <a href="{{ route('admin.deposito.paket.create') }}" |
| 15 | `` | UI | class="inline-flex items-center px-4 py-2.5 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white font-medium rounded-xl hover:shadow-lg hover:shadow-[#674c1d]/20 transition-all duration-300"> |
| 24 | `Success` | UI | <!-- Alert Success --> |
| 25 | `` | UI | @if(session('success')) |
| 30 | `` | UI | {{ session('success') }} |
| 44 | `Status` | UI | <th class="px-6 py-4">Status</th> |
| 66 | `` | UI | @if($item->status == 'aktif') |
| 78 | `Edit` | UI | <a href="{{ route('admin.deposito.paket.edit', $item->id) }}" class="text-[#8b6f2f] hover:text-[#674c1d] transition-colors" title="Edit"> |
| 83 | `` | UI | @if($item->status == 'aktif') |
| 84 | `` | UI | <form action="{{ route('admin.deposito.paket.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan paket ini?');" class="inline-block"> |
| 86 | `` | UI | @method('DELETE') |
| 87 | `` | UI | <button type="submit" class="text-red-500 hover:text-red-700 transition-colors" title="Nonaktifkan"> |

## ðŸ„ `admin\deposito\pencairan-tabungan.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 13 | `Dashboard` | UI | <a href="{{ route('admin.deposito.index') }}" class="text-sm text-gray-500 hover:text-[#674c1d]">← Dashboard Deposito</a> |
| 17 | `` | UI | @if(session('success')) |
| 18 | `` | UI | <div class="mb-4 bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 text-sm">{{ session('success') }}</div> |
| 20 | `` | UI | @if(session('error')) |
| 21 | `` | UI | <div class="mb-4 bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 text-sm">{{ session('error') }}</div> |
| 31 | `` | UI | <p class="text-2xl font-black text-gray-700">{{ $pencairans->total() }}</p> |
| 32 | `Total` | UI | <p class="text-xs text-gray-500 font-semibold mt-1">Total Request</p> |
| 35 | `` | UI | <p class="text-2xl font-black text-green-700">{{ $pencairans->where('status','selesai')->count() }}</p> |
| 42 | `` | UI | <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / no. deposito…" |
| 44 | `` | UI | <select name="status" class="border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none"> |
| 45 | `Status` | UI | <option value="">Semua Status</option> |
| 46 | `Pending` | UI | <option value="pending" @selected(request('status')=='pending')>Pending</option> |
| 47 | `` | UI | <option value="selesai" @selected(request('status')=='selesai')>Selesai</option> |
| 48 | `` | UI | </select> |
| 49 | `Filter` | UI | <button type="submit" class="bg-[#674c1d] text-white px-5 py-2 rounded-xl text-sm font-semibold hover:bg-[#8b6f2f] transition">Filter</button> |
| 61 | `Status` | UI | <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Status</th> |
| 76 | `` | UI | <p class="text-xs text-gray-400">{{ $p->nasabah?->user?->email ?? '-' }}</p> |
| 90 | `` | UI | @if($p->status === 'pending') |
| 91 | `Pending` | UI | <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold">Pending</span> |
| 92 | `` | UI | @elseif($p->status === 'diproses') |
| 94 | `` | UI | @elseif($p->status === 'selesai') |
| 97 | `` | UI | <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-bold">{{ ucfirst($p->status) }}</span> |
| 101 | `` | UI | @if($p->status === 'pending') |
| 104 | `` | UI | <button type="button" |
| 105 | `` | UI | onclick="document.getElementById('modal-finish-{{ $p->id }}').classList.remove('hidden')" |
| 110 | `` | UI | @elseif($p->status === 'diproses') |
| 112 | `` | UI | <a href="{{ route('admin.deposito.deposito-detail', $p->deposito_id) }}" |
| 113 | `Detail` | UI | class="text-xs text-[#674c1d] hover:underline">Lihat Detail</a> |
| 119 | `` | UI | @if($p->status === 'pending' \|\| $p->status === 'diproses') |
| 124 | `` | UI | @if($p->status === 'pending') |
| 130 | `` | UI | <form method="POST" action="{{ route('admin.deposito.pencairan-tabungan.finish', $p->id) }}" enctype="multipart/form-data"> |
| 136 | `` | UI | <input type="number" name="nominal_akhir" value="{{ round($p->nominal_akhir) }}" |
| 142 | `Upload` | UI | <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Upload Bukti</label> |
| 143 | `` | UI | <input type="file" name="foto_bukti_tf" accept="image/*" |
| 148 | `` | UI | <button type="submit" class="flex-1 bg-green-600 text-white py-3 rounded-xl text-sm font-bold hover:bg-green-700 transition shadow-lg"> |
| 151 | `` | UI | <button type="button" onclick="document.getElementById('modal-finish-{{ $p->id }}').classList.add('hidden')" |

## ðŸ„ `admin\deposito\pencairan-tf.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Transfer` | UI | @section('title', 'Pencairan Deposito via Transfer') |
| 10 | `Transfer` | UI | <h1 class="text-xl font-bold text-gray-800">Pencairan Deposito – Transfer (TF)</h1> |
| 13 | `Dashboard` | UI | <a href="{{ route('admin.deposito.index') }}" class="text-sm text-gray-500 hover:text-[#674c1d]">← Dashboard Deposito</a> |
| 17 | `` | UI | @if(session('success')) |
| 18 | `` | UI | <div class="mb-4 bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 text-sm">{{ session('success') }}</div> |
| 20 | `` | UI | @if(session('error')) |
| 21 | `` | UI | <div class="mb-4 bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 text-sm">{{ session('error') }}</div> |
| 31 | `` | UI | <p class="text-2xl font-black text-gray-700">{{ $pencairans->total() }}</p> |
| 32 | `Total` | UI | <p class="text-xs text-gray-500 font-semibold mt-1">Total Request</p> |
| 35 | `` | UI | <p class="text-2xl font-black text-green-700">{{ $pencairans->where('status','selesai')->count() }}</p> |
| 42 | `` | UI | <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / no. deposito…" |
| 44 | `` | UI | <select name="status" class="border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none"> |
| 45 | `Status` | UI | <option value="">Semua Status</option> |
| 46 | `Pending` | UI | <option value="pending" @selected(request('status')=='pending')>Pending</option> |
| 47 | `` | UI | <option value="selesai" @selected(request('status')=='selesai')>Selesai</option> |
| 48 | `` | UI | <option value="ditolak" @selected(request('status')=='ditolak')>Ditolak</option> |
| 49 | `` | UI | </select> |
| 50 | `Filter` | UI | <button type="submit" class="bg-[#674c1d] text-white px-5 py-2 rounded-xl text-sm font-semibold hover:bg-[#8b6f2f] transition">Filter</button> |
| 63 | `Status` | UI | <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Status</th> |
| 72 | `` | UI | <p class="text-xs text-gray-400">{{ $p->nasabah?->user?->email ?? '-' }}</p> |
| 85 | `` | UI | @if($p->status === 'pending') |
| 86 | `Pending` | UI | <span class="px-2 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold">Pending</span> |
| 87 | `` | UI | @elseif($p->status === 'diproses') |
| 89 | `` | UI | @elseif($p->status === 'selesai') |
| 92 | `` | UI | <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">{{ ucfirst($p->status) }}</span> |
| 96 | `` | UI | @if($p->status === 'pending') |
| 101 | `` | UI | @elseif($p->status === 'diproses') |
| 107 | `` | UI | <a href="{{ route('admin.deposito.deposito-detail', $p->deposito_id) }}" |
| 108 | `Detail` | UI | class="text-xs text-[#674c1d] hover:underline">Lihat Detail</a> |

## ðŸ„ `admin\deposito\pencairan-tf-form.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 28 | `Transfer` | UI | <p class="text-white/60 text-[10px] font-bold uppercase tracking-widest mb-4">Tujuan Transfer (Nasabah)</p> |
| 44 | `` | UI | @if(session('error')) |
| 45 | `` | UI | <div class="mb-4 bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 text-sm">{{ session('error') }}</div> |
| 55 | `` | UI | <form method="POST" action="{{ route('admin.deposito.pencairan-tf.proses', $pencairan->id) }}"> |
| 59 | `` | UI | <select name="admin_id" required class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#674c1d]/20 outline-none"> |
| 64 | `` | UI | </select> |
| 67 | `Transfer` | UI | <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nominal Transfer</label> |
| 70 | `` | UI | <input type="number" name="nominal_akhir" value="{{ round($estimasiCair) }}" |
| 74 | `` | UI | <button type="submit" class="w-full bg-[#674c1d] text-white py-4 rounded-xl font-bold hover:bg-[#8b6f2f] transition shadow-lg"> |
| 80 | `` | UI | <form method="POST" action="{{ route('admin.deposito.pencairan-tf.finish', $pencairan->id) }}" enctype="multipart/form-data"> |
| 83 | `Transfer` | UI | <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nominal Transfer</label> |
| 86 | `` | UI | <input type="number" name="nominal_akhir" value="{{ $isManual ? round($pencairan->nominal_akhir) : round($estimasiCair) }}" |
| 92 | `Upload` | UI | <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Upload Bukti Foto</label> |
| 94 | `` | UI | <input type="file" name="foto_bukti_tf" accept="image/*" required |
| 98 | `Upload` | UI | <p class="text-[10px] text-gray-500 font-bold uppercase tracking-tighter">Klik untuk Upload Bukti</p> |
| 103 | `` | UI | <button type="submit" class="w-full bg-[#674c1d] text-white py-4 rounded-xl font-bold hover:bg-[#8b6f2f] transition shadow-lg"> |

## ðŸ„ `admin\deposito\pengajuan-list.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 21 | `` | UI | <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama nasabah..." class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#674c1d] w-64"> |
| 22 | `` | UI | <select name="status" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#674c1d]"> |
| 23 | `Pending` | UI | <option value="1" {{ request('status','1') === '1' ? 'selected' : '' }}>Pending</option> |
| 24 | `` | UI | <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>Disetujui</option> |
| 25 | `` | UI | <option value="3" {{ request('status') === '3' ? 'selected' : '' }}>Ditolak</option> |
| 26 | `` | UI | <option value="" {{ request('status') === '' ? 'selected' : '' }}>Semua</option> |
| 27 | `` | UI | </select> |
| 28 | `` | UI | <select name="metode" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#674c1d]"> |
| 30 | `Transfer` | UI | <option value="transfer" {{ request('metode') === 'transfer' ? 'selected' : '' }}>Transfer Bank</option> |
| 32 | `` | UI | </select> |
| 33 | `Filter` | UI | <button type="submit" class="bg-[#674c1d] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-[#8b6f2f]">Filter</button> |
| 36 | `` | UI | @if(session('success')) |
| 37 | `` | UI | <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">{{ session('success') }}</div> |
| 39 | `` | UI | @if(session('error')) |
| 40 | `` | UI | <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">{{ session('error') }}</div> |
| 52 | `Status` | UI | <th class="text-left px-4 py-3 font-semibold">Status</th> |
| 61 | `` | UI | <p class="text-xs text-gray-400">{{ $p->nasabah->user->email ?? '-' }}</p> |
| 66 | `` | UI | @if($p->metode_setor === 'transfer') |
| 67 | `Transfer` | UI | <span class="bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full text-xs font-semibold">Transfer Bank</span> |
| 74 | `` | UI | @if($p->status === '1') |
| 75 | `Pending` | UI | <span class="bg-amber-50 text-amber-600 px-2 py-0.5 rounded-full text-xs font-semibold">Pending</span> |
| 76 | `` | UI | @elseif($p->status === '2') |
| 83 | `Detail` | UI | <a href="{{ route('admin.deposito.detail-pengajuan', $p->id) }}" class="text-[#674c1d] hover:underline text-xs font-semibold">Detail →</a> |

## ðŸ„ `admin\deposito\peringatan-index.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 30 | `Total` | UI | <p class="text-xs text-gray-500 mb-1">Total Persiapan</p> |
| 35 | `Total` | UI | <p class="text-xs text-gray-500 mb-1">Total Dana Dibutuhkan</p> |
| 40 | `Transfer` | UI | <p class="text-xs text-orange-600 mb-1">Butuh Transfer Bank</p> |
| 68 | `Transfer` | UI | <th class="text-right px-3 py-2">Via Transfer</th> |
| 70 | `Total` | UI | <th class="text-right px-3 py-2 rounded-r">Total Dana</th> |
| 102 | `` | UI | <form method="GET" action="{{ route('admin.deposito.peringatan.index') }}" class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 mb-6"> |
| 106 | `` | UI | <select name="metode_cair" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"> |
| 111 | `` | UI | </select> |
| 115 | `` | UI | <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"> |
| 119 | `` | UI | <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"> |
| 122 | `Filter` | UI | <button type="submit" class="flex-1 bg-[#674c1d] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-[#8b6f2f] transition">Filter</button> |
| 123 | `Reset` | UI | <a href="{{ route('admin.deposito.peringatan.index') }}" class="px-4 py-2 border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition">Reset</a> |
| 132 | `` | UI | <span class="text-xs text-gray-400">{{ $persiapan->total() }} deposito</span> |
| 150 | `Total` | UI | <th class="text-right px-4 py-3">Total Dibayar</th> |
| 152 | `Status` | UI | <th class="text-center px-4 py-3">Status</th> |
| 171 | `` | UI | <a href="{{ route('admin.deposito.deposito-detail', $item->deposito_id) }}" class="font-mono text-xs text-[#674c1d] hover:underline"> |
| 186 | `Transfer` | UI | <span class="text-xs bg-orange-100 text-orange-700 px-2 py-1 rounded-full">Transfer Bank</span> |
| 192 | `` | UI | @if($item->status === 'tentatif') |
| 194 | `` | UI | @elseif($item->status === 'diproses') |
| 214 | `` | UI | <a href="{{ route('admin.deposito.deposito-detail', $item->deposito_id) }}" |
| 216 | `Detail` | UI | Detail |

## ðŸ„ `admin\gadai\index.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 22 | `` | UI | <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center justify-center px-6 py-3 bg-linear-to-r from-[#674c1d] to-[#8d6b2c] text-white rounded-xl hover:from-[#573f17] hover:to-[#765d29] transition-all font-semibold shadow-md"> |
| 26 | `Dashboard` | UI | Kembali ke Dashboard |

## ðŸ„ `admin\gadai_baru\create.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 24 | `` | UI | @if(session('error')) |
| 32 | `` | UI | <p class="text-sm text-red-700 mt-1">{{ session('error') }}</p> |
| 39 | `` | UI | <form action="{{ route('admin.gadai_baru.store') }}" method="POST" enctype="multipart/form-data" |
| 60 | `` | UI | <select name="nasabah_id" |
| 68 | `` | UI | </select> |
| 74 | `` | UI | <select name="lokasi_id" |
| 81 | `` | UI | </select> |
| 95 | `Detail` | UI | <h3 class="text-lg font-bold text-gray-800">Detail Barang Gadai</h3> |
| 102 | `` | UI | <select name="kategori_id" id="kategori_id" |
| 109 | `` | UI | </select> |
| 115 | `` | UI | <select name="item_id" id="item_id" |
| 124 | `` | UI | </select> |
| 136 | `` | UI | <input type="number" name="nominal_deal" id="nominal_deal" |
| 146 | `` | UI | Melebihi batas maksimal taksiran! |
| 153 | `` | UI | <select name="metode_pencairan" |
| 157 | `Transfer` | UI | <option value="transfer">Transfer Bank</option> |
| 158 | `` | UI | </select> |
| 164 | `` | UI | <select name="slot_kode" id="slot_kode" |
| 168 | `` | UI | </select> |
| 188 | `Upload` | UI | <label class="block text-sm font-bold text-gray-700">Upload Foto Bukti <span |
| 190 | `` | UI | <button type="button" id="add_file_btn" |
| 202 | `` | UI | <input type="file" name="foto_bukti[]" class="block w-full text-sm text-gray-500 |
| 209 | `` | UI | <button type="button" |
| 235 | `Submit` | UI | <h4 class="font-bold text-gray-900">Perhatian Sebelum Submit</h4> |
| 247 | `` | UI | <button type="submit" id="btnSubmit" |
| 276 | `` | UI | katSelect.addEventListener('change', function () { |
| 283 | `` | UI | tInfoBox.classList.add('hidden'); |
| 287 | `` | UI | const filtered = allItems.filter(item => item.kategori_id == val); |
| 307 | `` | UI | itemSelect.addEventListener('change', function () { |
| 309 | `` | UI | tInfoBox.classList.add('hidden'); |
| 317 | `` | UI | tInfoBox.classList.remove('hidden'); |
| 332 | `` | UI | errorNominal.classList.remove('hidden'); |
| 333 | `` | UI | nominalInput.classList.add('border-red-500', 'focus:ring-red-500', 'focus:border-red-500'); |
| 335 | `` | UI | btnSubmit.classList.add('opacity-50', 'cursor-not-allowed'); |
| 337 | `` | UI | errorNominal.classList.add('hidden'); |
| 338 | `` | UI | nominalInput.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500'); |
| 340 | `` | UI | btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed'); |
| 352 | `` | UI | <input type="file" name="foto_bukti[]" class="block w-full text-sm text-gray-500 |
| 359 | `` | UI | <button type="button" class="w-11 h-11 flex items-center justify-center bg-red-50 text-red-500 rounded-xl border border-red-100 hover:bg-red-100 transition-colors remove-file-btn"> |
| 366 | `` | UI | row.querySelector('.remove-file-btn').addEventListener('click', function () { |
| 367 | `` | UI | row.remove(); |

## ðŸ„ `admin\gadai_baru\detail.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Detail` | UI | @section('title', 'Detail Gadai Fisik') |
| 16 | `Detail` | UI | Detail Gadai |
| 24 | `Status` | UI | <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Status:</span> |
| 25 | `` | UI | @if($gadai->status == 'active') |
| 26 | `` | UI | <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-black rounded-lg">ACTIVE</span> |
| 27 | `` | UI | @elseif($gadai->status == 'grace_period') |
| 28 | `` | UI | <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-black rounded-lg animate-pulse">GRACE PERIOD</span> |
| 29 | `` | UI | @elseif($gadai->status == 'lunas') |
| 31 | `` | UI | @elseif($gadai->status == 'expired_final') |
| 34 | `` | UI | <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-black rounded-lg">{{ strtoupper($gadai->status) }}</span> |
| 47 | `` | UI | @if(session('success')) |
| 52 | `` | UI | <p class="text-sm text-emerald-700 mt-1">{{ session('success') }}</p> |
| 56 | `` | UI | @if(session('error')) |
| 61 | `` | UI | <p class="text-sm text-red-700 mt-1">{{ session('error') }}</p> |
| 108 | `Status` | UI | <p class="text-xs text-gray-500 font-medium mb-1">Status Barang</p> |
| 109 | `` | UI | @if($gadai->status == 'active') |
| 111 | `` | UI | @elseif($gadai->status == 'grace_period') |
| 113 | `` | UI | @elseif($gadai->status == 'lunas') |
| 115 | `` | UI | @elseif($gadai->status == 'auctioned') |
| 117 | `` | UI | @elseif($gadai->status == 'expired_final') |
| 176 | `Total` | UI | <p class="text-xs text-emerald-600 font-bold uppercase tracking-wider mb-1">Total Tebus Sekarang</p> |
| 221 | `` | UI | <img src="{{ asset('storage/' . $file->path_file) }}" alt="Foto" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"> |
| 283 | `` | UI | @if($hist->aksi === 'create') |

## ðŸ„ `admin\gadai_baru\index.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 25 | `` | UI | <a href="{{ route('admin.gadai_baru.create') }}" |
| 35 | `` | PHP | $statAktif      = $gadiList->where('status', 'active')->count(); |
| 36 | `` | PHP | $statTenggang   = $gadiList->where('status', 'grace_period')->count(); |
| 37 | `` | PHP | $statHangus     = $gadiList->where('status', 'expired_final')->count(); |
| 38 | `` | PHP | $statLunas      = $gadiList->where('status', 'lunas')->count(); |
| 84 | `Filter` | UI | <h3 class="font-bold text-gray-800 text-sm">Filter Pencarian</h3> |
| 87 | `` | UI | <form action="{{ route('admin.gadai_baru.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4"> |
| 90 | `` | UI | <select name="kategori" class="w-full border-gray-200 rounded-xl bg-gray-50 focus:ring-[#674c1d] focus:border-[#674c1d] text-sm transition-colors"> |
| 95 | `` | UI | </select> |
| 99 | `` | UI | <select name="cabang" class="w-full border-gray-200 rounded-xl bg-gray-50 focus:ring-[#674c1d] focus:border-[#674c1d] text-sm transition-colors"> |
| 104 | `` | UI | </select> |
| 107 | `Status` | UI | <label class="block text-xs font-semibold text-gray-600 mb-1.5">Status</label> |
| 108 | `` | UI | <select name="status" class="w-full border-gray-200 rounded-xl bg-gray-50 focus:ring-[#674c1d] focus:border-[#674c1d] text-sm transition-colors"> |
| 109 | `Status` | UI | <option value="">Semua Status</option> |
| 110 | `` | UI | <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option> |
| 111 | `` | UI | <option value="grace_period" {{ request('status') == 'grace_period' ? 'selected' : '' }}>Tenggang</option> |
| 112 | `` | UI | <option value="expired_final" {{ request('status') == 'expired_final' ? 'selected' : '' }}>Hangus</option> |
| 113 | `` | UI | <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option> |
| 114 | `` | UI | </select> |
| 117 | `` | UI | <button type="submit" class="flex-1 flex justify-center items-center gap-2 px-4 py-2.5 bg-gray-800 hover:bg-gray-900 text-white font-medium rounded-xl transition-colors shadow-sm text-sm"> |
| 121 | `` | UI | @if(request()->hasAny(['kategori','cabang','status'])) |
| 122 | `Filter` | UI | <a href="{{ route('admin.gadai_baru.index') }}" class="px-3 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl transition-colors text-sm font-medium" title="Reset Filter">✕</a> |
| 149 | `Status` | UI | <th class="px-6 py-3.5 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Status</th> |
| 157 | `` | PHP | $today = now()->startOfDay(); |
| 158 | `` | UI | if ($gadai->status == 'grace_period') { |
| 160 | `` | PHP | $sisaHari = (int) $today->diffInDays(\Carbon\Carbon::parse($gadai->tgl_tenggang)->startOfDay(), false); |
| 161 | `` | UI | } elseif ($gadai->status == 'expired_final') { |
| 164 | `` | UI | } elseif ($gadai->status == 'lunas') { |
| 168 | `` | PHP | $sisaHari = (int) $today->diffInDays(\Carbon\Carbon::parse($gadai->tgl_jatuh_tempo)->startOfDay(), false); |
| 208 | `` | UI | @if($gadai->status == 'grace_period') |
| 220 | `` | UI | @elseif(in_array($gadai->status, ['expired_final', 'auctioned'])) |
| 222 | `` | UI | @elseif($gadai->status == 'lunas') |
| 239 | `` | UI | @if($gadai->status == 'active') |
| 243 | `` | UI | @elseif($gadai->status == 'grace_period') |
| 247 | `` | UI | @elseif($gadai->status == 'lunas') |
| 251 | `` | UI | @elseif($gadai->status == 'auctioned') |
| 255 | `` | UI | @elseif($gadai->status == 'expired_final') |
| 262 | `` | UI | <a href="{{ route('admin.gadai_baru.detail', $gadai->id) }}" |
| 277 | `` | UI | <p class="text-gray-300 text-xs mt-1">Coba ubah filter pencarian di atas</p> |

## ðŸ„ `admin\gadai_baru\pengajuan_index.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 58 | `` | UI | @if($item->metode == 'transfer') |
| 72 | `Transfer` | UI | <button onclick="showPhotoPreview('{{ asset('storage/'.$file->path_file) }}', 'Bukti Transfer {{ $item->nasabah->user->nama }}')" class="w-7 h-7 rounded-lg overflow-hidden border border-gray-200 hover:border-[#674c1d] transition-all shadow-xs scale-95 hover:scale-105"> |
| 77 | `Transfer` | UI | <button onclick="showPhotoPreview('{{ asset('storage/'.$item->bukti_transfer) }}', 'Bukti Transfer {{ $item->nasabah->user->nama }}')" class="w-7 h-7 rounded-lg overflow-hidden border border-gray-200 hover:border-[#674c1d] transition-all shadow-xs scale-95 hover:scale-105"> |
| 87 | `` | UI | <button type="button" onclick="openDetailsModal({{ $item->id }})" |
| 88 | `Detail` | UI | class="w-9 h-9 flex items-center justify-center bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-sm hover:shadow-blue-100 active:scale-90" title="Lihat Detail"> |
| 91 | `` | UI | <button type="button" onclick="openApproveModal({{ $item->id }}, '{{ $item->nasabah->user->nama }}', '{{ strtoupper($item->jenis_pengajuan) }}')" |
| 92 | `` | UI | class="w-9 h-9 flex items-center justify-center bg-green-50 text-green-600 rounded-xl hover:bg-green-600 hover:text-white transition-all shadow-sm hover:shadow-green-100 active:scale-90" title="Setujui"> |
| 96 | `` | UI | class="w-9 h-9 flex items-center justify-center bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm hover:shadow-red-100 active:scale-90" title="Tolak"> |
| 123 | `Detail` | UI | <!-- Modal Detail Pengajuan (Tailwind) --> |
| 124 | `` | UI | <div id="details-modal" class="fixed inset-0 bg-black/60 backdrop-blur-md z-[110] hidden items-center justify-center p-4"> |
| 125 | `` | UI | <div class="bg-white rounded-[2.5rem] shadow-2xl max-w-2xl w-full overflow-hidden animate-in fade-in zoom-in duration-300"> |
| 133 | `Detail` | UI | <h3 class="text-xl font-black text-gray-900 tracking-tight">Detail Rincian Pengajuan</h3> |
| 147 | `` | UI | <p class="font-bold text-gray-800" id="detail-nasabah-name">-</p> |
| 148 | `` | UI | <p class="text-xs text-gray-500 font-medium mt-1" id="detail-barang-name">-</p> |
| 149 | `` | UI | <span class="inline-block mt-2 text-[10px] font-mono bg-amber-100 text-amber-800 px-2 py-0.5 rounded font-black" id="detail-slot-code">-</span> |
| 156 | `` | UI | <span class="font-bold text-gray-800 uppercase text-xs" id="detail-jenis">-</span> |
| 160 | `` | UI | <span class="font-bold text-gray-800 uppercase text-xs" id="detail-metode">-</span> |
| 162 | `` | UI | <div class="flex justify-between py-1 border-b border-gray-200/50" id="detail-janji-temu-row"> |
| 164 | `` | UI | <span class="font-bold text-gray-800 text-xs" id="detail-janji-temu">-</span> |
| 168 | `` | UI | <span class="font-black text-emerald-600 text-base" id="detail-nominal">-</span> |
| 177 | `` | UI | <p class="text-xs text-gray-700 italic leading-relaxed" id="detail-keterangan">-</p> |
| 180 | `` | UI | <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100" id="detail-foto-section"> |
| 181 | `Transfer` | UI | <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Foto Lampiran / Bukti Transfer</h4> |
| 182 | `` | UI | <div class="grid grid-cols-3 gap-2" id="detail-foto-grid"> |
| 190 | `` | UI | <button type="button" onclick="closeDetailsModal()" |
| 191 | `` | UI | class="flex-1 px-6 py-3.5 bg-gray-100 text-gray-500 font-bold rounded-2xl hover:bg-gray-200 transition-all active:scale-95 text-center text-sm"> |
| 195 | `` | UI | <button type="button" id="detail-reject-btn" |
| 196 | `` | UI | class="flex-1 px-6 py-3.5 bg-red-50 text-red-600 font-bold rounded-2xl hover:bg-red-600 hover:text-white transition-all active:scale-95 text-center text-sm"> |
| 199 | `` | UI | <button type="button" id="detail-approve-btn" |
| 200 | `` | UI | class="flex-1 px-6 py-3.5 bg-green-600 text-white font-bold rounded-2xl hover:bg-green-700 transition-all shadow-xl shadow-green-200 active:scale-95 text-center text-sm"> |
| 209 | `Approve` | UI | <!-- Modal Approve (Tailwind) --> |
| 210 | `` | UI | <div id="approve-modal" class="fixed inset-0 bg-black/60 backdrop-blur-md z-[110] hidden items-center justify-center p-4"> |
| 211 | `` | UI | <div class="bg-white rounded-[2.5rem] shadow-2xl max-w-lg w-full overflow-hidden animate-in fade-in zoom-in duration-300"> |
| 219 | `` | UI | <p class="text-xs text-gray-500">Nasabah: <span id="approve-nasabah-name" class="font-bold text-green-600 bg-green-50 px-1.5 rounded"></span> \| <span id="approve-jenis" class="font-bold"></span></p> |
| 223 | `` | UI | <form id="formApprove" action="" method="POST" enctype="multipart/form-data" class="space-y-5" onsubmit="this.querySelector('button[type=submit]').disabled=true; this.querySelector('button[type=submit]').innerHTML='Mengolah...';"> |
| 226 | `Upload` | UI | <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Upload Bukti Administrasi (Opsional)</label> |
| 228 | `Add` | UI | <!-- Add Button --> |
| 229 | `` | UI | <button type="button" onclick="addAdminBuktiField()" class="aspect-square rounded-xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center text-gray-400 hover:border-green-500 hover:text-green-500 transition-all"> |
| 239 | `` | UI | <textarea name="admin_keterangan" rows="3" |
| 250 | `` | UI | <button type="button" onclick="closeApproveModal()" |
| 251 | `` | UI | class="flex-1 px-6 py-4 bg-gray-100 text-gray-500 font-bold rounded-2xl hover:bg-gray-200 transition-all active:scale-95"> |
| 254 | `` | UI | <button type="submit" |
| 255 | `` | UI | class="flex-2 px-6 py-4 bg-green-600 text-white font-bold rounded-2xl hover:bg-green-700 transition-all shadow-xl shadow-green-200 active:scale-95"> |
| 263 | `` | UI | <div id="reject-modal" class="fixed inset-0 bg-black/60 backdrop-blur-md z-[110] hidden items-center justify-center p-4"> |
| 264 | `` | UI | <div class="bg-white rounded-[2.5rem] shadow-2xl max-w-md w-full overflow-hidden animate-in fade-in zoom-in duration-300"> |
| 272 | `` | UI | <p class="text-xs text-gray-500">Nasabah: <span id="reject-nasabah-name" class="font-bold text-red-600 bg-red-50 px-1.5 rounded"></span></p> |
| 276 | `` | UI | <form id="formReject" action="" method="POST" class="space-y-5" onsubmit="this.querySelector('button[type=submit]').disabled=true; this.querySelector('button[type=submit]').innerHTML='Mengolah...';"> |
| 280 | `` | UI | <textarea name="keterangan" rows="5" required |
| 286 | `` | UI | <button type="button" onclick="closeRejectModal()" |
| 287 | `` | UI | class="flex-1 px-6 py-4 bg-gray-100 text-gray-500 font-bold rounded-2xl hover:bg-gray-200 transition-all active:scale-95"> |
| 290 | `` | UI | <button type="submit" |
| 291 | `` | UI | class="flex-2 px-6 py-4 bg-red-600 text-white font-bold rounded-2xl hover:bg-red-700 transition-all shadow-xl shadow-red-200 active:scale-95"> |
| 331 | `` | UI | document.getElementById('detail-nasabah-name').textContent = data.nasabahName; |
| 332 | `` | UI | document.getElementById('detail-barang-name').textContent = data.barangName; |
| 333 | `` | UI | document.getElementById('detail-slot-code').textContent = data.slotCode; |
| 334 | `` | UI | document.getElementById('detail-jenis').textContent = data.jenis; |
| 335 | `` | UI | document.getElementById('detail-metode').textContent = data.metode; |
| 337 | `` | UI | const janjiTemuRow = document.getElementById('detail-janji-temu-row'); |
| 339 | `` | UI | janjiTemuRow.classList.remove('hidden'); |
| 340 | `` | UI | document.getElementById('detail-janji-temu').textContent = data.janjiTemu; |
| 342 | `` | UI | janjiTemuRow.classList.add('hidden'); |
| 345 | `` | UI | document.getElementById('detail-nominal').textContent = data.nominal; |
| 346 | `` | UI | document.getElementById('detail-keterangan').textContent = data.keterangan; |
| 349 | `` | UI | const grid = document.getElementById('detail-foto-grid'); |
| 350 | `` | UI | const photoSection = document.getElementById('detail-foto-section'); |
| 354 | `` | UI | photoSection.classList.remove('hidden'); |
| 357 | `` | UI | <button type="button" onclick="showPhotoPreview('${photoUrl}', 'Lampiran Foto ${data.nasabahName}')" class="aspect-square rounded-xl overflow-hidden border border-gray-200 hover:border-[#674c1d] transition-all shadow-xs scale-95 hover:scale-105"> |
| 363 | `` | UI | photoSection.classList.add('hidden'); |
| 367 | `` | UI | document.getElementById('detail-approve-btn').onclick = function() { |
| 371 | `` | UI | document.getElementById('detail-reject-btn').onclick = function() { |
| 376 | `` | UI | const modal = document.getElementById('details-modal'); |
| 377 | `` | UI | modal.classList.remove('hidden'); |
| 378 | `` | UI | modal.classList.add('flex'); |
| 383 | `` | UI | const modal = document.getElementById('details-modal'); |
| 384 | `` | UI | modal.classList.add('hidden'); |
| 385 | `` | UI | modal.classList.remove('flex'); |
| 390 | `` | UI | document.getElementById('details-modal').addEventListener('click', function(e) { |
| 399 | `` | UI | div.className = 'relative aspect-square rounded-xl bg-gray-50 border-2 border-gray-100 overflow-hidden group animate-in zoom-in duration-200'; |
| 401 | `` | UI | <input type="file" name="admin_bukti_foto[]" class="absolute inset-0 opacity-0 z-20 cursor-pointer" onchange="previewAdminFile(this)" required> |
| 406 | `` | UI | <button type="button" onclick="this.closest('.relative').remove()" class="absolute top-1 right-1 z-40 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 shadow-sm"> |
| 422 | `` | UI | img.classList.remove('hidden'); |
| 423 | `` | UI | icon.classList.add('hidden'); |
| 429 | `` | UI | function openApproveModal(id, name, jenis) { |
| 430 | `` | UI | const modal = document.getElementById('approve-modal'); |
| 432 | `` | UI | const nameDisplay = document.getElementById('approve-nasabah-name'); |
| 433 | `` | UI | const jenisDisplay = document.getElementById('approve-jenis'); |
| 435 | `` | UI | form.action = "{{ route('admin.gadai_baru.pengajuan.approve', ':id') }}".replace(':id', id); |
| 436 | `` | UI | nameDisplay.textContent = name; |
| 439 | `` | UI | modal.classList.remove('hidden'); |
| 440 | `` | UI | modal.classList.add('flex'); |
| 445 | `` | UI | const modal = document.getElementById('approve-modal'); |
| 446 | `` | UI | modal.classList.add('hidden'); |
| 447 | `` | UI | modal.classList.remove('flex'); |
| 451 | `` | UI | function openRejectModal(id, name) { |
| 452 | `` | UI | const modal = document.getElementById('reject-modal'); |
| 454 | `` | UI | const nameDisplay = document.getElementById('reject-nasabah-name'); |
| 456 | `` | UI | form.action = "{{ route('admin.gadai_baru.pengajuan.reject', ':id') }}".replace(':id', id); |
| 457 | `` | UI | nameDisplay.textContent = name; |
| 459 | `` | UI | modal.classList.remove('hidden'); |
| 460 | `` | UI | modal.classList.add('flex'); |
| 465 | `` | UI | const modal = document.getElementById('reject-modal'); |
| 466 | `` | UI | modal.classList.add('hidden'); |
| 467 | `` | UI | modal.classList.remove('flex'); |
| 472 | `` | UI | document.getElementById('reject-modal').addEventListener('click', function(e) { |

## ðŸ„ `admin\gadai_baru\storage.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 20 | `` | UI | <form action="{{ route('admin.gadai_baru.storage') }}" method="GET"> |
| 21 | `` | UI | <select name="kategori" class="border-gray-200 rounded-xl focus:ring-[#674c1d] focus:border-[#674c1d] text-sm font-medium bg-white shadow-sm" onchange="this.form.submit()"> |
| 25 | `` | UI | </select> |
| 31 | `` | UI | @if(session('success')) |
| 35 | `` | UI | <p class="text-sm font-semibold text-emerald-800">{{ session('success') }}</p> |
| 37 | `` | UI | <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 font-bold text-xl leading-none">&times;</button> |
| 40 | `` | UI | @if(session('error')) |
| 44 | `` | UI | <p class="text-sm font-semibold text-red-800">{{ session('error') }}</p> |
| 46 | `` | UI | <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 font-bold text-xl leading-none">&times;</button> |
| 52 | `` | UI | @foreach ($errors->all() as $error)<li>• {{ $error }}</li>@endforeach |
| 69 | `Total` | UI | <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Total Slot</p> |
| 93 | `` | UI | <div class="h-3 rounded-full bg-gradient-to-r from-[#674c1d] to-[#d4af37] transition-all duration-700" style="width: {{ $fillPct }}%"></div> |
| 144 | `` | UI | class="w-full py-2 bg-amber-600 hover:bg-amber-700 active:scale-95 text-white text-[10px] font-black rounded-lg transition-all shadow-sm uppercase tracking-wider"> |
| 185 | `` | UI | <div class="relative bg-white rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden z-10 animate-in fade-in zoom-in duration-300"> |
| 186 | `` | UI | <form action="{{ route('admin.gadai_baru.storage.empty-auction') }}" method="POST" enctype="multipart/form-data"> |
| 188 | `` | UI | <input type="hidden" name="gadai_id" id="modal_gadai_id"> |
| 199 | `` | UI | <button type="button" onclick="closeEmptyAuctionModal()" class="w-8 h-8 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center text-white transition-colors"> |
| 210 | `` | UI | <p class="text-xs text-amber-700 font-medium leading-relaxed">Barang pada slot ini berstatus <strong class="underline">hangus</strong>. Konfirmasi ini akan mengosongkan slot dan mengubah status barang menjadi <strong>Sudah Dilelang (Auctioned)</strong>.</p> |
| 232 | `Upload` | UI | <label class="block text-xs font-black text-gray-700 uppercase tracking-wider mb-2">Upload Foto Bukti Pengambilan <span class="text-red-500">*</span></label> |
| 234 | `` | UI | <input type="file" name="foto_bukti[]" multiple required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewImages(event)"> |
| 247 | `` | UI | <textarea name="catatan" rows="3" required |
| 254 | `` | UI | <button type="button" onclick="closeEmptyAuctionModal()" |
| 258 | `` | UI | <button type="submit" |
| 259 | `` | UI | class="px-6 py-2.5 bg-amber-600 hover:bg-amber-700 active:scale-95 text-white text-sm font-black rounded-xl transition-all shadow-md shadow-amber-600/20 uppercase tracking-wide" |
| 276 | `` | UI | modal.classList.remove('hidden'); |
| 280 | `` | UI | document.getElementById('emptyAuctionModal').classList.add('hidden'); |

## ðŸ„ `admin\janji-temu\detail.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Detail` | UI | @section('title', 'Detail Janji Temu') |
| 10 | `Detail` | UI | <h1 class="text-3xl font-bold text-gray-900 font-display">Detail Janji Temu</h1> |
| 35 | `Email` | UI | <p class="text-sm text-gray-600">Email</p> |
| 36 | `` | UI | <p class="font-semibold text-gray-900">{{ $nasabah->user->email ?? 'N/A' }}</p> |
| 87 | `` | UI | <iframe src="https://www.google.com/maps/embed?pb=!4v1771057242792!6m8!1m7!1sTDnmeXtVvimBtQeXmqSSCQ!2m2!1d-6.267415399913648!2d106.9806162945405!3f247.41483905689947!4f-35.52001210835799!5f0.7820865974627469" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Peta Lokasi Janji Temu"></iframe> |
| 98 | `Actions` | UI | <!-- Sidebar Actions (sticky) --> |
| 101 | `Status` | UI | <h3 class="text-lg font-bold text-primary font-display mb-4">Status & Aksi</h3> |
| 107 | `Update` | UI | <p class="text-sm text-gray-600">Terakhir Update</p> |
| 111 | `` | UI | @if($janjiTemu->status == '1') |
| 122 | `` | UI | <div x-show="openCancel" |
| 123 | `` | UI | x-transition:enter="transition ease-out duration-300" |
| 126 | `` | UI | x-transition:leave="transition ease-in duration-200" |
| 135 | `` | UI | <form action="{{ route('admin.janji-temu.cancel-tabungan', $janjiTemu->id) }}" method="POST"> |
| 139 | `` | UI | <textarea name="keterangan_admin" required rows="3" |
| 144 | `` | UI | <button type="button" @click="openCancel = false" class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium transition-colors"> |
| 147 | `` | UI | <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition-colors"> |
| 159 | `Create` | UI | <!-- Form Create Transaksi --> |
| 164 | `` | UI | <form method="POST" action="{{ route('admin.tabungan.create-trans-from-janji-temu', $janjiTemu->id) }}" enctype="multipart/form-data"> |
| 175 | `` | UI | <input type="text" name="nominal" id="nominal" value="{{ number_format($janjiTemu->nominal, 0, ',', '.') }}" |
| 182 | `Edit` | UI | <p class="text-xs text-gray-500 mt-1">Default dari janji temu: Rp {{ number_format($janjiTemu->nominal, 0, ',', '.') }}. Edit jika nominal {{ $isSetoran ? 'diterima' : 'diserahkan' }} berbeda.</p> |
| 188 | `` | UI | <div class="foto-upload-item"> |
| 190 | `` | UI | <input type="file" name="foto_penerimaan[]" accept="image/*" |
| 197 | `` | UI | <button type="button" onclick="addFotoInput()" id="btn-add-foto" |
| 209 | `` | UI | <textarea name="keterangan_admin" rows="3" |
| 215 | `` | UI | <button type="submit" class="w-full px-4 py-3 bg-linear-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all font-medium shadow-md"> |
| 255 | `` | UI | const currentCount = container.querySelectorAll('.foto-upload-item').length; |
| 263 | `` | UI | newItem.className = 'foto-upload-item flex gap-2 items-center'; |
| 266 | `` | UI | <input type="file" name="foto_penerimaan[]" accept="image/*" |
| 271 | `` | UI | <button type="button" onclick="removeFotoInput(this)" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors shrink-0"> |
| 281 | `` | UI | document.getElementById('btn-add-foto').style.display = 'none'; |
| 286 | `` | UI | const item = button.closest('.foto-upload-item'); |
| 290 | `` | UI | item.remove(); |
| 292 | `` | UI | document.getElementById('btn-add-foto').style.display = 'flex'; |
| 299 | `` | UI | document.querySelector('form').addEventListener('submit', function(e) { |
| 305 | `` | UI | hiddenInput.type = 'hidden'; |
| 306 | `` | UI | hiddenInput.name = 'nominal'; |
| 308 | `` | UI | nominalInput.name = 'nominal_formatted'; |

## ðŸ„ `admin\janji-temu\detail-pinjaman.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Detail` | UI | @section('title', 'Detail Janji Temu Pinjaman') |
| 41 | `Email` | UI | <p class="text-sm text-gray-500 mb-1">Email</p> |
| 42 | `` | UI | <p class="font-medium text-gray-900">{{ $janjiTemu->nasabah->user->email ?? '-' }}</p> |
| 54 | `Detail` | UI | <!-- Detail Janji Temu (data dari tbl_janji_temu_pinjaman) --> |
| 56 | `Detail` | UI | <h2 class="text-xl font-bold text-gray-900 font-display mb-6">Detail Janji Temu</h2> |
| 128 | `` | UI | @if($janjiTemu->status == '2') |
| 151 | `` | UI | @if($janjiTemu->status == '1') |
| 164 | `` | UI | <form action="{{ route('admin.pinjaman.janji-temu.proses-pinjaman', ['id' => $janjiTemu->id]) }}" |
| 172 | `` | UI | <input type="text" value="{{ number_format($janjiTemu->nominal ?? 0, 0, ',', '.') }}" |
| 179 | `` | UI | <input type="date" name="tgl_cair" value="{{ date('Y-m-d') }}" required |
| 184 | `` | UI | <input type="file" name="bukti_transfer" accept="image/*" required |
| 186 | `` | UI | <p class="text-xs text-gray-500 mt-1">Bisa upload foto uang / kwitansi / bukti penerimaan |
| 191 | `` | UI | <textarea name="keterangan_admin" rows="3" |
| 195 | `` | UI | <button type="submit" |
| 196 | `` | UI | onclick="return confirm('Apakah Anda yakin uang sudah diterima? Pinjaman akan disetujui dan dicairkan (jadwal angsuran dibuat).')" |
| 210 | `Status` | UI | <h3 class="font-bold text-lg mb-2">Status Saat Ini</h3> |
| 216 | `` | UI | @if($janjiTemu->status == '1') |
| 229 | `` | UI | <div x-show="openCancel" |
| 238 | `` | UI | <form action="{{ route('admin.janji-temu.cancel-pinjaman', $janjiTemu->id) }}" |
| 244 | `` | UI | <textarea name="keterangan_admin" required rows="3" |
| 249 | `` | UI | <button type="button" @click="openCancel = false" |
| 251 | `` | UI | <button type="submit" |

## ðŸ„ `admin\janji-temu\index.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 15 | `Total` | UI | Total: {{ $janjiTemu->total() }} Jadwal |
| 20 | `Filter` | UI | <!-- Filter Section --> |
| 22 | `` | UI | <form action="{{ route('admin.janji-temu.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4"> |
| 24 | `` | UI | <input type="text" name="search" value="{{ request('search') }}" |
| 33 | `` | UI | <select name="fitur" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#674c1d] focus:border-transparent transition-all outline-none"> |
| 38 | `` | UI | </select> |
| 42 | `` | UI | <select name="status" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#674c1d] focus:border-transparent transition-all outline-none"> |
| 43 | `Status` | UI | <option value="">Semua Status</option> |
| 44 | `` | UI | <option value="akan-datang" {{ request('status') == 'akan-datang' ? 'selected' : '' }}>Akan Datang</option> |
| 45 | `` | UI | <option value="terlewat" {{ request('status') == 'terlewat' ? 'selected' : '' }}>Terlewat</option> |
| 46 | `` | UI | <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Terlaksana</option> |
| 47 | `` | UI | <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>Dibatalkan</option> |
| 48 | `` | UI | </select> |
| 52 | `` | UI | <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" |
| 57 | `` | UI | <button type="submit" class="px-6 py-2.5 bg-[#674c1d] hover:bg-[#543d16] text-white font-medium rounded-xl transition-all shadow-lg shadow-[#674c1d]/20 flex items-center justify-center gap-2"> |
| 61 | `Filter` | UI | Filter |
| 77 | `` | UI | <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">STATUS</th> |
| 131 | `Detail` | UI | <a href="{{ route('admin.tabungan.detail-janji-temu', $item->id_asli) }}" class="text-[#674c1d] hover:text-[#543d16]">Detail →</a> |
| 133 | `Detail` | UI | <a href="{{ route('admin.pinjaman.detail-pengajuan', $item->id_pengajuan ?? $item->id_asli) }}" class="text-[#674c1d] hover:text-[#543d16]">Detail →</a> |
| 135 | `Detail` | UI | <a href="{{ route('admin.pinjaman.detail-pembayaran', $item->id_pengajuan ?? $item->id_asli) }}" class="text-[#674c1d] hover:text-[#543d16]">Detail →</a> |

## ðŸ„ `admin\laporan\angsuran-pinjaman.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 13 | `` | UI | <form method="GET" action="{{ route('admin.laporan.angsuran-pinjaman') }}" class="flex flex-wrap items-end gap-4"> |
| 16 | `` | UI | <input type="date" name="tgl_dari" value="{{ $tgl_dari ?? now()->startOfMonth()->format('Y-m-d') }}" class="px-4 py-2 border border-gray-300 rounded-lg"> |
| 20 | `` | UI | <input type="date" name="tgl_sampai" value="{{ $tgl_sampai ?? now()->format('Y-m-d') }}" class="px-4 py-2 border border-gray-300 rounded-lg"> |
| 22 | `` | UI | <button type="submit" class="px-4 py-2 bg-[#674c1d] text-white rounded-lg font-medium">Tampilkan</button> |
| 29 | `Export` | UI | <a href="{{ route('admin.laporan.angsuran-pinjaman', ['tgl_dari' => $tgl_dari, 'tgl_sampai' => $tgl_sampai, 'export' => 'pdf']) }}" class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-sm font-medium">Export PDF</a> |
| 30 | `Export` | UI | <a href="{{ route('admin.laporan.angsuran-pinjaman', ['tgl_dari' => $tgl_dari, 'tgl_sampai' => $tgl_sampai, 'export' => 'excel']) }}" class="px-3 py-1.5 bg-green-100 text-green-700 rounded-lg text-sm font-medium">Export Excel</a> |
| 43 | `Total` | UI | <th class="px-6 py-3 text-right text-xs font-bold text-[#674c1d] uppercase">Total</th> |
| 55 | `` | UI | <td class="px-6 py-3 text-sm text-right">Rp {{ number_format($r->total ?? 0, 0, ',', '.') }}</td> |
| 64 | `` | UI | <td colspan="4" class="px-6 py-3">TOTAL</td> |

## ðŸ„ `admin\laporan\export\angsuran-pinjaman.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 27 | `Total` | UI | <th class="text-right">Total</th> |
| 39 | `` | UI | <td class="text-right">Rp {{ number_format($r->total ?? 0, 0, ',', '.') }}</td> |
| 46 | `` | UI | <td colspan="4"><strong>TOTAL</strong></td> |

## ðŸ„ `admin\laporan\export\jatuh-tempo.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 28 | `Status` | UI | <th>Status</th> |
| 48 | `` | UI | <td colspan="4"><strong>TOTAL</strong></td> |

## ðŸ„ `admin\laporan\export\pengajuan.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 19 | `Total` | UI | <tr><th>Jenis</th><th>Jumlah</th><th class="text-right">Total Nominal</th></tr> |

## ðŸ„ `admin\laporan\export\rekapitulasi.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 21 | `Total` | UI | <tr><td>Total Setoran</td><td class="text-right">Rp {{ number_format($setoran_tabungan ?? 0, 0, ',', '.') }}</td></tr> |
| 22 | `Total` | UI | <tr><td>Total Penarikan</td><td class="text-right">Rp {{ number_format($penarikan_tabungan ?? 0, 0, ',', '.') }}</td></tr> |

## ðŸ„ `admin\laporan\export\saldo-tabungan.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 17 | `Total` | UI | <p>Tanggal: {{ $tgl_cutoff }} \| Total: Rp {{ number_format($total_saldo ?? 0, 0, ',', '.') }}</p> |

## ðŸ„ `admin\laporan\export\tabungan.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 41 | `Total` | UI | <tr><td colspan="4">Total Setor</td><td class="text-right">Rp {{ number_format($total_setor ?? 0, 0, ',', '.') }}</td></tr> |
| 42 | `Total` | UI | <tr><td colspan="4">Total Tarik</td><td class="text-right">Rp {{ number_format($total_tarik ?? 0, 0, ',', '.') }}</td></tr> |

## ðŸ„ `admin\laporan\index.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 9 | `` | UI | <p class="text-gray-600 mt-1">Pilih jenis laporan untuk melihat data dan export PDF/Excel</p> |

## ðŸ„ `admin\laporan\jatuh-tempo.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 13 | `` | UI | <form method="GET" action="{{ route('admin.laporan.jatuh-tempo') }}" class="flex flex-wrap items-end gap-4"> |
| 16 | `` | UI | <input type="month" name="bulan" value="{{ $bulan ?? now()->format('Y-m') }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d]"> |
| 18 | `` | UI | <button type="submit" class="px-4 py-2 bg-[#674c1d] text-white rounded-lg hover:bg-[#4a3514] font-medium">Tampilkan</button> |
| 25 | `Export` | UI | <a href="{{ route('admin.laporan.jatuh-tempo', ['bulan' => $bulan, 'export' => 'pdf']) }}" class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-sm font-medium">Export PDF</a> |
| 26 | `Export` | UI | <a href="{{ route('admin.laporan.jatuh-tempo', ['bulan' => $bulan, 'export' => 'excel']) }}" class="px-3 py-1.5 bg-green-100 text-green-700 rounded-lg text-sm font-medium">Export Excel</a> |
| 40 | `Status` | UI | <th class="px-6 py-3 text-center text-xs font-bold text-[#674c1d] uppercase">Status</th> |
| 62 | `` | UI | <td colspan="4" class="px-6 py-3">TOTAL</td> |

## ðŸ„ `admin\laporan\pengajuan.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 16 | `` | UI | <form method="GET" action="{{ route('admin.laporan.pengajuan') }}" class="flex flex-wrap items-end gap-4"> |
| 19 | `` | UI | <input type="date" name="tgl_dari" value="{{ $tgl_dari ?? now()->startOfMonth()->format('Y-m-d') }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d]"> |
| 23 | `` | UI | <input type="date" name="tgl_sampai" value="{{ $tgl_sampai ?? now()->format('Y-m-d') }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d]"> |
| 26 | `Status` | UI | <label class="block text-sm font-medium text-gray-700 mb-1">Status</label> |
| 27 | `` | UI | <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d]"> |
| 29 | `Pending` | UI | <option value="1" {{ ($status ?? '') === '1' ? 'selected' : '' }}>Pending</option> |
| 30 | `` | UI | <option value="2" {{ ($status ?? '') === '2' ? 'selected' : '' }}>Disetujui</option> |
| 31 | `` | UI | <option value="3" {{ ($status ?? '') === '3' ? 'selected' : '' }}>Ditolak</option> |
| 32 | `` | UI | </select> |
| 34 | `` | UI | <button type="submit" class="px-4 py-2 bg-[#674c1d] text-white rounded-lg hover:bg-[#4a3514] font-medium">Tampilkan</button> |
| 42 | `Export` | UI | <a href="{{ route('admin.laporan.pengajuan', ['tgl_dari' => $tgl_dari, 'tgl_sampai' => $tgl_sampai, 'status' => $status, 'export' => 'pdf']) }}" class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-sm font-medium hover:bg-red-200">Export PDF</a> |
| 43 | `Export` | UI | <a href="{{ route('admin.laporan.pengajuan', ['tgl_dari' => $tgl_dari, 'tgl_sampai' => $tgl_sampai, 'status' => $status, 'export' => 'excel']) }}" class="px-3 py-1.5 bg-green-100 text-green-700 rounded-lg text-sm font-medium hover:bg-green-200">Export Excel</a> |

## ðŸ„ `admin\laporan\pinjaman-aktif.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 17 | `Total` | UI | <h2 class="font-semibold text-gray-900">Total Outstanding: Rp {{ number_format($total_outstanding ?? 0, 0, ',', '.') }}</h2> |
| 19 | `Export` | UI | <a href="{{ route('admin.laporan.pinjaman-aktif', ['export' => 'pdf']) }}" class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-sm font-medium hover:bg-red-200">Export PDF</a> |
| 20 | `Export` | UI | <a href="{{ route('admin.laporan.pinjaman-aktif', ['export' => 'excel']) }}" class="px-3 py-1.5 bg-green-100 text-green-700 rounded-lg text-sm font-medium hover:bg-green-200">Export Excel</a> |

## ðŸ„ `admin\laporan\rekapitulasi.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 16 | `` | UI | <form method="GET" action="{{ route('admin.laporan.rekapitulasi') }}" class="flex flex-wrap items-end gap-4"> |
| 19 | `` | UI | <select name="tipe" id="tipeRekap" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d]"> |
| 22 | `` | UI | </select> |
| 26 | `` | UI | <input type="date" name="tgl" value="{{ $tgl ?? now()->format('Y-m-d') }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d]"> |
| 30 | `` | UI | <input type="month" name="bulan" value="{{ $bulan ?? now()->format('Y-m') }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d]"> |
| 32 | `` | UI | <button type="submit" class="px-4 py-2 bg-[#674c1d] text-white rounded-lg hover:bg-[#4a3514] font-medium">Tampilkan</button> |
| 40 | `Export` | UI | <a href="{{ route('admin.laporan.rekapitulasi', array_merge(request()->query(), ['export' => 'pdf'])) }}" class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-sm font-medium hover:bg-red-200">Export PDF</a> |
| 41 | `Export` | UI | <a href="{{ route('admin.laporan.rekapitulasi', array_merge(request()->query(), ['export' => 'excel'])) }}" class="px-3 py-1.5 bg-green-100 text-green-700 rounded-lg text-sm font-medium hover:bg-green-200">Export Excel</a> |
| 49 | `Total` | UI | <tr><td class="py-2 text-gray-600">Total Setoran</td><td class="py-2 text-right font-semibold">Rp {{ number_format($setoran_tabungan ?? 0, 0, ',', '.') }}</td></tr> |
| 50 | `Total` | UI | <tr><td class="py-2 text-gray-600">Total Penarikan</td><td class="py-2 text-right font-semibold">Rp {{ number_format($penarikan_tabungan ?? 0, 0, ',', '.') }}</td></tr> |
| 67 | `` | UI | document.getElementById('tipeRekap').addEventListener('change', function() { |

## ðŸ„ `admin\laporan\saldo-tabungan.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 16 | `` | UI | <form method="GET" action="{{ route('admin.laporan.saldo-tabungan') }}" class="flex flex-wrap items-end gap-4"> |
| 19 | `` | UI | <input type="date" name="tgl_cutoff" value="{{ $tgl_cutoff ?? now()->format('Y-m-d') }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d]"> |
| 21 | `` | UI | <button type="submit" class="px-4 py-2 bg-[#674c1d] text-white rounded-lg hover:bg-[#4a3514] font-medium">Tampilkan</button> |
| 27 | `Total` | UI | <h2 class="font-semibold text-gray-900">Per {{ $tgl_cutoff }} · Total Saldo: Rp {{ number_format($total_saldo ?? 0, 0, ',', '.') }}</h2> |
| 29 | `Export` | UI | <a href="{{ route('admin.laporan.saldo-tabungan', array_merge(request()->query(), ['export' => 'pdf'])) }}" class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-sm font-medium hover:bg-red-200">Export PDF</a> |
| 30 | `Export` | UI | <a href="{{ route('admin.laporan.saldo-tabungan', array_merge(request()->query(), ['export' => 'excel'])) }}" class="px-3 py-1.5 bg-green-100 text-green-700 rounded-lg text-sm font-medium hover:bg-green-200">Export Excel</a> |
| 39 | `Total` | UI | <th class="px-6 py-3 text-right text-xs font-bold text-[#674c1d] uppercase">Total Setor</th> |
| 40 | `Total` | UI | <th class="px-6 py-3 text-right text-xs font-bold text-[#674c1d] uppercase">Total Tarik</th> |

## ðŸ„ `admin\laporan\tabungan.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 13 | `` | UI | <form method="GET" action="{{ route('admin.laporan.tabungan') }}" class="flex flex-wrap items-end gap-4"> |
| 16 | `` | UI | <input type="date" name="tgl_dari" value="{{ $tgl_dari ?? now()->startOfMonth()->format('Y-m-d') }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d]"> |
| 20 | `` | UI | <input type="date" name="tgl_sampai" value="{{ $tgl_sampai ?? now()->format('Y-m-d') }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d]"> |
| 22 | `` | UI | <button type="submit" class="px-4 py-2 bg-[#674c1d] text-white rounded-lg hover:bg-[#4a3514] font-medium">Tampilkan</button> |
| 29 | `Export` | UI | <a href="{{ route('admin.laporan.tabungan', ['tgl_dari' => $tgl_dari, 'tgl_sampai' => $tgl_sampai, 'export' => 'pdf']) }}" class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-sm font-medium">Export PDF</a> |
| 30 | `Export` | UI | <a href="{{ route('admin.laporan.tabungan', ['tgl_dari' => $tgl_dari, 'tgl_sampai' => $tgl_sampai, 'export' => 'excel']) }}" class="px-3 py-1.5 bg-green-100 text-green-700 rounded-lg text-sm font-medium">Export Excel</a> |
| 60 | `Total` | UI | <td colspan="3" class="px-6 py-3">Total Setor</td> |
| 65 | `Total` | UI | <td colspan="3" class="px-6 py-3">Total Tarik</td> |

## ðŸ„ `admin\master-data\admin-operasional\create.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 39 | `` | UI | <form action="{{ route('admin.master-data.admin-operasional.store') }}" method="POST" class="p-6 space-y-5"> |
| 47 | `` | UI | <input type="text" id="nama" name="nama" value="{{ old('nama') }}" |
| 51 | `` | UI | @error('nama') |
| 61 | `Email` | UI | <!-- Email --> |
| 63 | `` | UI | <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5"> |
| 64 | `Email` | UI | Email <span class="text-red-500">*</span> |
| 66 | `` | UI | <input type="email" id="email" name="email" value="{{ old('email') }}" |
| 69 | `` | UI | {{ $errors->has('email') ? 'border-red-400 focus:border-red-500 bg-red-50' : 'border-gray-200 focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20' }}"> |
| 70 | `` | UI | @error('email') |
| 85 | `` | UI | <input type="text" id="nomor_hp" name="nomor_hp" value="{{ old('nomor_hp') }}" |
| 89 | `` | UI | @error('nomor_hp') |
| 104 | `Password` | UI | <!-- Password --> |
| 106 | `` | UI | <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5"> |
| 107 | `Password` | UI | Password <span class="text-red-500">*</span> |
| 110 | `` | UI | <input type="password" id="password" name="password" |
| 113 | `` | UI | {{ $errors->has('password') ? 'border-red-400 focus:border-red-500 bg-red-50' : 'border-gray-200 focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20' }}"> |
| 114 | `` | UI | <button type="button" onclick="togglePassword('password', 'eye-password')" |
| 116 | `` | UI | <svg id="eye-password" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"> |
| 122 | `` | UI | @error('password') |
| 132 | `Password` | UI | <!-- Konfirmasi Password --> |
| 135 | `Password` | UI | Konfirmasi Password <span class="text-red-500">*</span> |
| 138 | `` | UI | <input type="password" id="password_confirmation" name="password_confirmation" |
| 139 | `` | UI | placeholder="Ulangi password" |
| 141 | `` | UI | <button type="button" onclick="togglePassword('password_confirmation', 'eye-confirm')" |
| 143 | `` | UI | <svg id="eye-confirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"> |
| 163 | `` | UI | <li>Tidak dapat mengelola akun nasabah (approve/reset PIN)</li> |
| 169 | `Actions` | UI | <!-- Form Actions --> |
| 175 | `` | UI | <button type="submit" |
| 193 | `` | UI | if (input.type === 'password') { |
| 194 | `` | UI | input.type = 'text'; |
| 197 | `` | UI | input.type = 'password'; |

## ðŸ„ `admin\master-data\admin-operasional\edit.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Edit` | UI | @section('title', 'Edit Admin Operasional') |
| 15 | `Edit` | UI | <h1 class="text-3xl font-bold text-gray-900 font-display">Edit Admin Operasional</h1> |
| 21 | `Profile` | UI | <!-- Profile Preview Card --> |
| 29 | `` | UI | <p class="text-sm text-gray-500">{{ $adminOp->user->email }}</p> |
| 30 | `` | UI | <span class="inline-flex items-center mt-1 px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $adminOp->status === 'aktif' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}"> |
| 31 | `` | UI | {{ $adminOp->status === 'aktif' ? 'Aktif' : 'Nonaktif' }} |
| 42 | `Edit` | UI | <!-- Edit Form Card --> |
| 53 | `Edit` | UI | <h2 class="text-white font-bold text-lg">Edit Informasi Akun</h2> |
| 60 | `` | UI | <form action="{{ route('admin.master-data.admin-operasional.update', $adminOp->id) }}" method="POST" class="p-6 space-y-5"> |
| 69 | `` | UI | <input type="text" id="nama" name="nama" value="{{ old('nama', $adminOp->user->nama) }}" |
| 73 | `` | UI | @error('nama') |
| 83 | `Email` | UI | <!-- Email --> |
| 85 | `` | UI | <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5"> |
| 86 | `Email` | UI | Email <span class="text-red-500">*</span> |
| 88 | `` | UI | <input type="email" id="email" name="email" value="{{ old('email', $adminOp->user->email) }}" |
| 91 | `` | UI | {{ $errors->has('email') ? 'border-red-400 focus:border-red-500 bg-red-50' : 'border-gray-200 focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20' }}"> |
| 92 | `` | UI | @error('email') |
| 107 | `` | UI | <input type="text" id="nomor_hp" name="nomor_hp" value="{{ old('nomor_hp', $adminOp->user->nomor_hp) }}" |
| 111 | `` | UI | @error('nomor_hp') |
| 124 | `Password` | UI | <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Ganti Password</p> |
| 129 | `Password` | UI | <!-- Password Baru --> |
| 131 | `` | UI | <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5"> |
| 132 | `Password` | UI | Password Baru |
| 135 | `` | UI | <input type="password" id="password" name="password" |
| 138 | `` | UI | {{ $errors->has('password') ? 'border-red-400 focus:border-red-500 bg-red-50' : 'border-gray-200 focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20' }}"> |
| 139 | `` | UI | <button type="button" onclick="togglePassword('password', 'eye-password')" |
| 141 | `` | UI | <svg id="eye-password" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"> |
| 147 | `` | UI | @error('password') |
| 157 | `Password` | UI | <!-- Konfirmasi Password --> |
| 160 | `Password` | UI | Konfirmasi Password Baru |
| 163 | `` | UI | <input type="password" id="password_confirmation" name="password_confirmation" |
| 164 | `` | UI | placeholder="Ulangi password baru" |
| 166 | `` | UI | <button type="button" onclick="togglePassword('password_confirmation', 'eye-confirm')" |
| 168 | `` | UI | <svg id="eye-confirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"> |
| 176 | `Actions` | UI | <!-- Form Actions --> |
| 178 | `Delete` | UI | <!-- Delete action — hanya Admin Utama --> |
| 180 | `` | UI | <form action="{{ route('admin.master-data.admin-operasional.destroy', $adminOp->id) }}" method="POST" |
| 181 | `` | UI | onsubmit="return confirm('Yakin ingin menghapus akun ini? Aksi ini tidak dapat dibatalkan.')"> |
| 183 | `` | UI | @method('DELETE') |
| 184 | `` | UI | <button type="submit" |
| 201 | `` | UI | <button type="submit" |
| 220 | `` | UI | if (input.type === 'password') { |
| 221 | `` | UI | input.type = 'text'; |
| 224 | `` | UI | input.type = 'password'; |

## ðŸ„ `admin\master-data\admin-operasional\index.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 20 | `` | UI | <a href="{{ route('admin.master-data.admin-operasional.create') }}" |
| 30 | `` | UI | @if(session('success')) |
| 35 | `` | UI | <p class="text-green-700 font-semibold">{{ session('success') }}</p> |
| 39 | `` | UI | @if(session('error')) |
| 44 | `` | UI | <p class="text-red-700 font-semibold">{{ session('error') }}</p> |
| 58 | `` | UI | <p class="text-2xl font-bold text-[#674c1d]">{{ $adminList->total() }}</p> |
| 59 | `Total` | UI | <p class="text-sm text-gray-500">Total Admin</p> |
| 71 | `` | UI | <p class="text-2xl font-bold text-green-600">{{ \App\Models\AdminOperasional::where('status', 'aktif')->count() }}</p> |
| 84 | `` | UI | <p class="text-2xl font-bold text-gray-500">{{ \App\Models\AdminOperasional::where('status', 'nonaktif')->count() }}</p> |
| 91 | `Search` | UI | <!-- Search & Filter --> |
| 93 | `` | UI | <form method="GET" action="{{ route('admin.master-data.admin-operasional.index') }}" class="flex flex-wrap gap-3"> |
| 99 | `` | UI | <input type="text" name="search" value="{{ request('search') }}" |
| 100 | `` | UI | placeholder="Cari nama, email, atau nomor HP..." |
| 104 | `` | UI | <select name="status" |
| 106 | `Status` | UI | <option value="">Semua Status</option> |
| 107 | `` | UI | <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option> |
| 108 | `` | UI | <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option> |
| 109 | `` | UI | </select> |
| 110 | `` | UI | <button type="submit" |
| 114 | `` | UI | @if(request('search') \|\| request('status')) |
| 117 | `Reset` | UI | Reset |
| 128 | `` | UI | <p class="text-sm text-gray-500 mt-0.5">{{ $adminList->total() }} akun terdaftar</p> |
| 139 | `Email` | UI | <th class="px-6 py-4 text-left text-sm font-semibold">Email</th> |
| 141 | `Status` | UI | <th class="px-6 py-4 text-center text-sm font-semibold">Status</th> |
| 170 | `` | UI | <p class="text-sm text-gray-700">{{ $admin->user->email ?? 'N/A' }}</p> |
| 176 | `` | UI | <form action="{{ route('admin.master-data.admin-operasional.toggle-status', $admin->id) }}" method="POST" class="inline"> |
| 178 | `` | UI | <button type="submit" |
| 179 | `` | UI | onclick="return confirm('Ubah status akun ini?')" |
| 180 | `` | UI | class="px-3 py-1.5 rounded-full text-xs font-semibold transition-all hover:opacity-80 {{ $admin->status === 'aktif' ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-gray-100 text-gray-500 border border-gray-200' }}"> |
| 181 | `` | UI | {{ $admin->status === 'aktif' ? 'Aktif' : 'Nonaktif' }} |
| 192 | `` | UI | <a href="{{ route('admin.master-data.admin-operasional.edit', $admin->id) }}" |
| 197 | `Edit` | UI | Edit |
| 199 | `` | UI | <form action="{{ route('admin.master-data.admin-operasional.destroy', $admin->id) }}" method="POST" class="inline" |
| 200 | `` | UI | onsubmit="return confirm('Yakin ingin menghapus akun Admin Operasional ini? Aksi ini tidak dapat dibatalkan.')"> |
| 202 | `` | UI | @method('DELETE') |
| 203 | `` | UI | <button type="submit" |
| 237 | `` | UI | <a href="{{ route('admin.master-data.admin-operasional.create') }}" |

## ðŸ„ `admin\master-data\barang-gadai\create.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 19 | `` | UI | <form action="{{ route('admin.master-data.barang-gadai.store') }}" method="POST" class="space-y-6"> |
| 24 | `` | UI | <input type="text" name="nama_barang" value="{{ old('nama_barang') }}" required |
| 27 | `` | UI | @error('nama_barang') |
| 34 | `` | UI | <textarea name="deskripsi" rows="4" |
| 37 | `` | UI | @error('deskripsi') |
| 43 | `` | UI | <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#8b6f2f] to-[#d4af37] text-white rounded-xl hover:from-[#674c1d] hover:to-[#8b6f2f] transition-all font-medium shadow-md"> |

## ðŸ„ `admin\master-data\barang-gadai\edit.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Edit` | UI | @section('title', 'Edit Master Barang Gadai') |
| 13 | `Edit` | UI | <span class="text-gray-900 font-medium">Edit</span> |
| 15 | `Edit` | UI | <h1 class="text-3xl font-bold text-gray-900 font-display">Edit Master Barang Gadai</h1> |
| 19 | `` | UI | <form action="{{ route('admin.master-data.barang-gadai.update', $data->id) }}" method="POST" class="space-y-6"> |
| 25 | `` | UI | <input type="text" name="nama_barang" value="{{ old('nama_barang', $data->nama_barang) }}" required |
| 31 | `` | UI | <textarea name="deskripsi" rows="4" |
| 36 | `` | UI | <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#8b6f2f] to-[#d4af37] text-white rounded-xl hover:from-[#674c1d] hover:to-[#8b6f2f] transition-all font-medium shadow-md"> |
| 37 | `Update` | UI | Update Data |

## ðŸ„ `admin\master-data\barang-gadai\index.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 18 | `` | UI | <a href="{{ route('admin.master-data.barang-gadai.create') }}" class="px-4 py-2 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-md text-sm font-medium"> |
| 24 | `` | UI | @if(session('success')) |
| 26 | `` | UI | <p class="text-green-700 font-medium">{{ session('success') }}</p> |
| 30 | `` | UI | @if(session('error')) |
| 32 | `` | UI | <p class="text-red-700 font-medium">{{ session('error') }}</p> |
| 58 | `` | UI | <a href="{{ route('admin.master-data.barang-gadai.edit', $item->id) }}" class="p-2 text-[#674c1d] hover:bg-[#674c1d]/10 rounded-lg"> |
| 63 | `` | UI | <form action="{{ route('admin.master-data.barang-gadai.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?')"> |
| 65 | `` | UI | @method('DELETE') |
| 66 | `` | UI | <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg"> |
| 73 | `View` | UI | <span class="text-xs text-gray-400">View Only</span> |

## ðŸ„ `admin\master-data\biaya-transfer\create.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Transfer` | UI | @section('title', 'Tambah Biaya Transfer') |
| 12 | `Transfer` | UI | <a href="{{ route('admin.master-data.biaya-transfer.index') }}" class="hover:text-gray-900">Biaya Transfer</a> |
| 16 | `Transfer` | UI | <h1 class="text-3xl font-bold text-gray-900 font-display">Tambah Biaya Transfer</h1> |
| 18 | `` | UI | <a href="{{ route('admin.master-data.biaya-transfer.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium"> |
| 24 | `` | UI | <form method="POST" action="{{ route('admin.master-data.biaya-transfer.store') }}" class="space-y-6"> |
| 30 | `` | UI | <select name="bank_pengirim" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none"> |
| 38 | `` | UI | </select> |
| 39 | `` | UI | @error('bank_pengirim')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror |
| 44 | `` | UI | <select name="bank_penerima" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none"> |
| 53 | `` | UI | </select> |
| 54 | `` | UI | @error('bank_penerima')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror |
| 61 | `` | UI | <input type="text" name="biaya_admin" id="biaya_admin" value="{{ old('biaya_admin', 0) }}" required |
| 65 | `` | UI | @error('biaya_admin')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror |
| 71 | `` | UI | <textarea name="keterangan" rows="3" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none resize-none" placeholder="Keterangan tambahan...">{{ old('keterangan') }}</textarea> |
| 76 | `` | UI | <a href="{{ route('admin.master-data.biaya-transfer.index') }}" class="flex-1 px-4 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-colors text-center"> |
| 79 | `` | UI | <button type="submit" class="flex-1 px-4 py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-semibold hover:from-[#4a3514] hover:to-[#674c1d] transition-all"> |
| 98 | `` | UI | document.querySelector('form').addEventListener('submit', function(e) { |

## ðŸ„ `admin\master-data\biaya-transfer\edit.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Edit` | UI | @section('title', 'Edit Biaya Transfer') |
| 12 | `Transfer` | UI | <a href="{{ route('admin.master-data.biaya-transfer.index') }}" class="hover:text-gray-900">Biaya Transfer</a> |
| 14 | `Edit` | UI | <span class="text-gray-900 font-medium">Edit</span> |
| 16 | `Edit` | UI | <h1 class="text-3xl font-bold text-gray-900 font-display">Edit Biaya Transfer</h1> |
| 18 | `` | UI | <a href="{{ route('admin.master-data.biaya-transfer.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium"> |
| 24 | `` | UI | <form method="POST" action="{{ route('admin.master-data.biaya-transfer.update', $data->id) }}" class="space-y-6"> |
| 31 | `` | UI | <select name="bank_pengirim" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none"> |
| 39 | `` | UI | </select> |
| 40 | `` | UI | @error('bank_pengirim')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror |
| 45 | `` | UI | <select name="bank_penerima" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none"> |
| 54 | `` | UI | </select> |
| 55 | `` | UI | @error('bank_penerima')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror |
| 62 | `` | UI | <input type="text" name="biaya_admin" id="biaya_admin" value="{{ number_format($data->biaya_admin, 0, '.', '') }}" required |
| 66 | `` | UI | @error('biaya_admin')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror |
| 71 | `` | UI | <textarea name="keterangan" rows="3" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none resize-none">{{ old('keterangan', $data->keterangan) }}</textarea> |
| 76 | `` | UI | <a href="{{ route('admin.master-data.biaya-transfer.index') }}" class="flex-1 px-4 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-colors text-center"> |
| 79 | `` | UI | <button type="submit" class="flex-1 px-4 py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-semibold hover:from-[#4a3514] hover:to-[#674c1d] transition-all"> |
| 80 | `Update` | UI | Update Data |
| 98 | `` | UI | document.querySelector('form').addEventListener('submit', function(e) { |

## ðŸ„ `admin\master-data\biaya-transfer\index.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Transfer` | UI | @section('title', 'Master Biaya Transfer') |
| 12 | `Transfer` | UI | <span class="text-gray-900 font-medium">Biaya Transfer</span> |
| 14 | `Transfer` | UI | <h1 class="text-3xl font-bold text-gray-900 font-display">Master Biaya Transfer</h1> |
| 15 | `` | UI | <p class="text-gray-600 mt-1">Kelola biaya admin transfer antar bank</p> |
| 18 | `` | UI | <a href="{{ route('admin.master-data.biaya-transfer.create') }}" class="px-4 py-2 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-md text-sm font-medium"> |
| 24 | `` | UI | @if(session('success')) |
| 26 | `` | UI | <p class="text-green-700 font-medium">{{ session('success') }}</p> |
| 40 | `Status` | UI | <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase">Status</th> |
| 62 | `` | UI | <form action="{{ route('admin.master-data.biaya-transfer.toggle-status', $item->id) }}" method="POST"> |
| 64 | `` | UI | <button type="submit" class="px-3 py-1 {{ $item->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }} rounded-full text-xs font-semibold"> |
| 77 | `` | UI | <a href="{{ route('admin.master-data.biaya-transfer.edit', $item->id) }}" class="p-2 text-[#674c1d] hover:bg-[#674c1d]/10 rounded-lg transition-colors"> |
| 82 | `` | UI | <form action="{{ route('admin.master-data.biaya-transfer.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')"> |
| 84 | `` | UI | @method('DELETE') |
| 85 | `` | UI | <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"> |
| 92 | `View` | UI | <span class="text-xs text-gray-400">View Only</span> |

## ðŸ„ `admin\master-data\bunga-pinjaman\create.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 23 | `` | UI | <form action="{{ route('admin.master-data.bunga-pinjaman.store') }}" method="POST" class="space-y-6"> |
| 30 | `` | UI | <input type="number" name="durasi_min" value="{{ old('durasi_min') }}" required min="1" |
| 32 | `` | UI | @error('durasi_min') |
| 40 | `` | UI | <input type="number" name="durasi_max" value="{{ old('durasi_max') }}" required min="1" |
| 42 | `` | UI | @error('durasi_max') |
| 52 | `` | UI | <input type="number" name="bunga_persen" value="{{ old('bunga_persen') }}" required min="0" max="100" step="0.01" |
| 57 | `` | UI | @error('bunga_persen') |
| 62 | `Status` | UI | <!-- Status Aktif --> |
| 65 | `` | UI | <input type="hidden" name="status_aktif" value="0"> |
| 66 | `` | UI | <input type="checkbox" name="status_aktif" value="1" {{ old('status_aktif', true) ? 'checked' : '' }} |
| 68 | `Status` | UI | <span class="text-sm font-medium text-gray-700">Status Aktif</span> |
| 75 | `` | UI | <textarea name="keterangan" rows="3" |
| 77 | `` | UI | @error('keterangan') |
| 84 | `` | UI | <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl hover:from-[#4a3514] hover:to-[#674c1d] transition-all font-medium shadow-md"> |

## ðŸ„ `admin\master-data\bunga-pinjaman\edit.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Edit` | UI | @section('title', 'Edit Master Bunga Pinjaman') |
| 15 | `Edit` | UI | <span class="text-gray-900 font-medium">Edit Data</span> |
| 17 | `Edit` | UI | <h1 class="text-3xl font-bold text-gray-900 font-display">Edit Master Bunga Pinjaman</h1> |
| 23 | `` | UI | <form action="{{ route('admin.master-data.bunga-pinjaman.update', $data->id) }}" method="POST" class="space-y-6"> |
| 31 | `` | UI | <input type="number" name="durasi_min" value="{{ old('durasi_min', $data->durasi_min) }}" required min="1" |
| 33 | `` | UI | @error('durasi_min') |
| 41 | `` | UI | <input type="number" name="durasi_max" value="{{ old('durasi_max', $data->durasi_max) }}" required min="1" |
| 43 | `` | UI | @error('durasi_max') |
| 53 | `` | UI | <input type="number" name="bunga_persen" value="{{ old('bunga_persen', $data->bunga_persen) }}" required min="0" max="100" step="0.01" |
| 57 | `` | UI | @error('bunga_persen') |
| 62 | `Status` | UI | <!-- Status Aktif --> |
| 65 | `` | UI | <input type="hidden" name="status_aktif" value="0"> |
| 66 | `` | UI | <input type="checkbox" name="status_aktif" value="1" {{ old('status_aktif', $data->status_aktif) ? 'checked' : '' }} |
| 68 | `Status` | UI | <span class="text-sm font-medium text-gray-700">Status Aktif</span> |
| 75 | `` | UI | <textarea name="keterangan" rows="3" |
| 77 | `` | UI | @error('keterangan') |
| 84 | `` | UI | <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl hover:from-[#4a3514] hover:to-[#674c1d] transition-all font-medium shadow-md"> |
| 85 | `Update` | UI | Update Data |

## ðŸ„ `admin\master-data\bunga-pinjaman\index.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 19 | `` | UI | <a href="{{ route('admin.master-data.bunga-pinjaman.create') }}" class="px-4 py-2 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-md text-sm font-medium"> |
| 26 | `` | UI | @if(session('success')) |
| 32 | `` | UI | <p class="text-green-700 font-medium">{{ session('success') }}</p> |
| 37 | `` | UI | @if(session('error')) |
| 43 | `` | UI | <p class="text-red-700 font-medium">{{ session('error') }}</p> |
| 57 | `Status` | UI | <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th> |
| 77 | `` | UI | <form action="{{ route('admin.master-data.bunga-pinjaman.toggle-status', $item->id) }}" method="POST" class="inline"> |
| 79 | `` | UI | <button type="submit" class="px-3 py-1 rounded-full text-xs font-medium {{ $item->status_aktif ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }} hover:opacity-80 transition-all"> |
| 95 | `` | UI | <a href="{{ route('admin.master-data.bunga-pinjaman.edit', $item->id) }}" class="p-2 text-[#674c1d] hover:bg-[#674c1d]/10 rounded-lg transition-all"> |
| 100 | `` | UI | <form action="{{ route('admin.master-data.bunga-pinjaman.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')"> |
| 102 | `` | UI | @method('DELETE') |
| 103 | `` | UI | <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-all"> |
| 110 | `View` | UI | <span class="text-xs text-gray-400">View Only</span> |

## ðŸ„ `admin\master-data\denda-pinjaman\create.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 21 | `` | UI | <form action="{{ route('admin.master-data.denda-pinjaman.store') }}" method="POST" class="space-y-6"> |
| 27 | `` | UI | <input type="number" name="denda_persen" value="{{ old('denda_persen', 0.30) }}" required min="0" max="100" step="0.01" |
| 32 | `` | UI | @error('denda_persen') |
| 39 | `` | UI | <input type="hidden" name="status_aktif" value="0"> |
| 40 | `` | UI | <input type="checkbox" name="status_aktif" value="1" {{ old('status_aktif', true) ? 'checked' : '' }} |
| 42 | `Status` | UI | <span class="text-sm font-medium text-gray-700">Status Aktif</span> |
| 48 | `` | UI | <textarea name="keterangan" rows="3" |
| 53 | `` | UI | <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#8b6f2f] to-[#d4af37] text-white rounded-xl hover:from-[#674c1d] hover:to-[#8b6f2f] transition-all font-medium shadow-md"> |

## ðŸ„ `admin\master-data\denda-pinjaman\edit.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Edit` | UI | @section('title', 'Edit Master Denda Pinjaman') |
| 14 | `Edit` | UI | <span class="text-gray-900 font-medium">Edit Data</span> |
| 16 | `Edit` | UI | <h1 class="text-3xl font-bold text-gray-900 font-display">Edit Master Denda Pinjaman</h1> |
| 21 | `` | UI | <form action="{{ route('admin.master-data.denda-pinjaman.update', $data->id) }}" method="POST" class="space-y-6"> |
| 28 | `` | UI | <input type="number" name="denda_persen" value="{{ old('denda_persen', $data->denda_persen) }}" required min="0" max="100" step="0.01" |
| 37 | `` | UI | <input type="hidden" name="status_aktif" value="0"> |
| 38 | `` | UI | <input type="checkbox" name="status_aktif" value="1" {{ old('status_aktif', $data->status_aktif) ? 'checked' : '' }} |
| 40 | `Status` | UI | <span class="text-sm font-medium text-gray-700">Status Aktif</span> |
| 46 | `` | UI | <textarea name="keterangan" rows="3" |
| 51 | `` | UI | <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#8b6f2f] to-[#d4af37] text-white rounded-xl hover:from-[#674c1d] hover:to-[#8b6f2f] transition-all font-medium shadow-md"> |
| 52 | `Update` | UI | Update Data |

## ðŸ„ `admin\master-data\denda-pinjaman\index.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 18 | `` | UI | <a href="{{ route('admin.master-data.denda-pinjaman.create') }}" class="px-4 py-2 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-md text-sm font-medium"> |
| 24 | `` | UI | @if(session('success')) |
| 30 | `` | UI | <p class="text-green-700 font-medium">{{ session('success') }}</p> |
| 35 | `` | UI | @if(session('error')) |
| 41 | `` | UI | <p class="text-red-700 font-medium">{{ session('error') }}</p> |
| 53 | `Status` | UI | <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Status</th> |
| 67 | `` | UI | <form action="{{ route('admin.master-data.denda-pinjaman.toggle-status', $item->id) }}" method="POST" class="inline"> |
| 69 | `` | UI | <button type="submit" class="px-3 py-1 rounded-full text-xs font-medium {{ $item->status_aktif ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }} hover:opacity-80"> |
| 83 | `` | UI | <a href="{{ route('admin.master-data.denda-pinjaman.edit', $item->id) }}" class="p-2 text-[#674c1d] hover:bg-[#674c1d]/10 rounded-lg"> |
| 88 | `` | UI | <form action="{{ route('admin.master-data.denda-pinjaman.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')"> |
| 90 | `` | UI | @method('DELETE') |
| 91 | `` | UI | <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg"> |
| 98 | `View` | UI | <span class="text-xs text-gray-400">View Only</span> |

## ðŸ„ `admin\master-data\gadai-debugger\index.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 11 | `` | UI | <p class="text-gray-500 mt-1">Simulasi perjalanan waktu (Time Travel) untuk menguji status dan denda Gadai.</p> |
| 19 | `` | UI | @if(session('success')) |
| 28 | `` | UI | <p class="text-sm font-medium text-green-800">{{ session('success') }}</p> |
| 44 | `` | UI | <p class="text-xs text-gray-500">Uji perubahan status gadai</p> |
| 48 | `` | UI | <form action="{{ route('admin.master-data.gadai-debugger.maju-hari') }}" method="POST"> |
| 53 | `` | UI | <input type="number" name="days" min="1" max="365" value="1" class="w-24 px-4 py-2 border border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block text-center font-bold text-lg" required> |
| 56 | `` | UI | <p class="text-xs text-gray-400 mt-2">Ini akan secara permanen mengubah tanggal jatuh tempo di database menjadi lebih lampau dan memicu proses cek status.</p> |
| 58 | `` | UI | <button type="submit" onclick="return confirm('Yakin ingin memajukan waktu sistem (Gadai)? Ini akan memicu denda jika ada yang lewat batas.')" class="w-full flex justify-center items-center gap-2 py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"> |
| 74 | `Status` | UI | <!-- Tabel Status Saat Ini --> |
| 88 | `Status` | UI | <th scope="col" class="px-6 py-3 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Status</th> |
| 113 | `` | UI | @if($gadai->status == 'active') |
| 114 | `` | UI | <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-green-100 text-green-800">ACTIVE</span> |
| 115 | `` | UI | @elseif($gadai->status == 'grace_period') |
| 116 | `` | UI | <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-red-100 text-red-800">GRACE PERIOD</span> |
| 118 | `` | UI | <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-gray-100 text-gray-800">{{ strtoupper($gadai->status) }}</span> |

## ðŸ„ `admin\master-data\inap-kendaraan\create.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 29 | `` | UI | @foreach($errors->all() as $error) |
| 30 | `` | UI | <li>{{ $error }}</li> |
| 37 | `` | UI | <form action="{{ route('admin.master-data.inap-kendaraan.store') }}" method="POST" class="p-8 space-y-6"> |
| 43 | `` | UI | <input type="text" name="golongan" id="golongan" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#674c1d] focus:border-transparent transition-all font-bold text-gray-700 uppercase" value="{{ old('golongan') }}" required placeholder="Contoh: G, H, I"> |
| 49 | `` | UI | <input type="text" name="jenis_kendaraan" id="jenis_kendaraan" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#674c1d] focus:border-transparent transition-all font-bold text-gray-700" value="{{ old('jenis_kendaraan') }}" required placeholder="Contoh: motor matic, mobil sedan"> |
| 58 | `` | UI | <input type="number" name="nominal_inap" id="nominal_inap" class="w-full pl-10 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#674c1d] focus:border-transparent transition-all font-bold text-gray-700" value="{{ old('nominal_inap') }}" min="0" required placeholder="Contoh: 50000"> |
| 64 | `` | UI | <textarea name="keterangan" id="keterangan" rows="4" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#674c1d] focus:border-transparent transition-all font-bold text-gray-700" placeholder="Contoh: motor sport 250cc, mobil premium SUV">{{ old('keterangan') }}</textarea> |
| 69 | `Reset` | UI | <button type="reset" class="px-6 py-3 text-gray-500 font-bold hover:text-gray-700 transition-all text-sm">Reset</button> |
| 70 | `` | UI | <button type="submit" class="px-8 py-3 bg-[#674c1d] text-white font-black rounded-2xl hover:bg-[#8b6f2f] transition-all shadow-lg shadow-amber-900/20 text-sm"> |

## ðŸ„ `admin\master-data\inap-kendaraan\edit.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Edit` | UI | @section('title', 'Edit Golongan Inap Kendaraan') |
| 15 | `Edit` | UI | <span class="text-gray-900 font-medium">Edit</span> |
| 17 | `Edit` | UI | <h2 class="text-3xl font-black text-gray-900 tracking-tight font-display">Edit Golongan Inap</h2> |
| 29 | `` | UI | @foreach($errors->all() as $error) |
| 30 | `` | UI | <li>{{ $error }}</li> |
| 37 | `` | UI | <form action="{{ route('admin.master-data.inap-kendaraan.update', $data->id) }}" method="POST" class="p-8 space-y-6"> |
| 44 | `` | UI | <input type="text" name="golongan" id="golongan" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#674c1d] focus:border-transparent transition-all font-bold text-gray-700 uppercase" value="{{ old('golongan', $data->golongan) }}" required placeholder="Contoh: G, H, I"> |
| 50 | `` | UI | <input type="text" name="jenis_kendaraan" id="jenis_kendaraan" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#674c1d] focus:border-transparent transition-all font-bold text-gray-700" value="{{ old('jenis_kendaraan', $data->jenis_kendaraan) }}" required placeholder="Contoh: motor matic, mobil sedan"> |
| 59 | `` | UI | <input type="number" name="nominal_inap" id="nominal_inap" class="w-full pl-10 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#674c1d] focus:border-transparent transition-all font-bold text-gray-700" value="{{ old('nominal_inap', (int)$data->nominal_inap) }}" min="0" required placeholder="Contoh: 50000"> |
| 65 | `` | UI | <textarea name="keterangan" id="keterangan" rows="4" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#674c1d] focus:border-transparent transition-all font-bold text-gray-700" placeholder="Contoh: motor sport 250cc, mobil premium SUV">{{ old('keterangan', $data->keterangan) }}</textarea> |
| 71 | `` | UI | <button type="submit" class="px-8 py-3 bg-[#674c1d] text-white font-black rounded-2xl hover:bg-[#8b6f2f] transition-all shadow-lg shadow-amber-900/20 text-sm"> |

## ðŸ„ `admin\master-data\inap-kendaraan\index.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 19 | `` | UI | <a href="{{ route('admin.master-data.inap-kendaraan.create') }}" class="px-4 py-2.5 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-md text-sm font-bold flex items-center gap-2"> |
| 27 | `` | UI | @if(session('success')) |
| 32 | `` | UI | <span class="text-sm font-bold">{{ session('success') }}</span> |
| 69 | `Edit` | UI | <a href="{{ route('admin.master-data.inap-kendaraan.edit', $item->id) }}" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors" title="Edit"> |
| 72 | `` | UI | <form action="{{ route('admin.master-data.inap-kendaraan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')"> |
| 74 | `` | UI | @method('DELETE') |
| 75 | `` | UI | <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors" title="Hapus"> |

## ðŸ„ `admin\master-data\index.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 29 | `` | UI | class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-[#674c1d]/30 transition-all duration-200"> |
| 47 | `` | UI | class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-[#674c1d]/30 transition-all duration-200"> |
| 65 | `` | UI | class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-[#674c1d]/30 transition-all duration-200"> |
| 83 | `` | UI | class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-[#674c1d]/30 transition-all duration-200"> |
| 101 | `` | UI | class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-[#674c1d]/30 transition-all duration-200"> |
| 119 | `` | UI | class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-[#674c1d]/30 transition-all duration-200"> |
| 141 | `` | UI | class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-[#674c1d]/30 transition-all duration-200"> |
| 159 | `` | UI | class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-[#674c1d]/30 transition-all duration-200"> |
| 176 | `` | UI | <a href="{{ route('admin.master-data.biaya-transfer.index') }}" |
| 177 | `` | UI | class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-[#674c1d]/30 transition-all duration-200"> |
| 189 | `Transfer` | UI | <p class="text-sm font-medium text-gray-700">Biaya Transfer</p> |
| 195 | `` | UI | class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-[#674c1d]/30 transition-all duration-200"> |
| 214 | `` | UI | class="group bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] rounded-2xl border border-[#674c1d] shadow-sm p-5 hover:shadow-lg transition-all duration-200"> |
| 463 | `` | UI | <a href="{{ route('admin.master-data.biaya-transfer.index') }}" |
| 472 | `Transfer` | UI | <p class="text-sm font-semibold text-gray-800">Biaya Transfer</p> |

## ðŸ„ `admin\master-data\item-gadai\create.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 26 | `` | UI | @foreach($errors->all() as $error) |
| 27 | `` | UI | <li>{{ $error }}</li> |
| 34 | `` | UI | <form action="{{ route('admin.master-data.item-gadai.store') }}" method="POST" enctype="multipart/form-data" |
| 43 | `` | UI | <select name="kategori_id" id="kategori_id" |
| 52 | `` | UI | </select> |
| 87 | `` | UI | <input type="text" name="head_1" id="head_1" |
| 96 | `` | UI | <input type="text" name="head_2" id="head_2" |
| 105 | `` | UI | <input type="number" name="nominal_real" id="nominal_real" |
| 112 | `Status` | UI | class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Status <span |
| 114 | `` | UI | <select name="is_active" id="is_active" |
| 119 | `` | UI | </select> |
| 131 | `` | UI | <input type="number" name="nominal_low" id="nominal_low" |
| 139 | `` | UI | <input type="number" name="bunga_low" id="bunga_low" step="0.01" |
| 148 | `` | UI | <input type="number" name="nominal_high" id="nominal_high" |
| 156 | `` | UI | <input type="number" name="bunga_high" id="bunga_high" step="0.01" |
| 166 | `` | UI | <select id="inap_preset" |
| 175 | `` | UI | </select> |
| 186 | `` | UI | <input type="number" name="nominal_inap" id="nominal_inap" |
| 199 | `` | UI | <input type="file" name="file_pic" id="file_pic" |
| 207 | `` | UI | <button type="reset" |
| 208 | `Reset` | UI | class="px-6 py-3 text-gray-500 font-bold hover:text-gray-700 transition-all">Reset</button> |
| 209 | `` | UI | <button type="submit" |
| 289 | `` | UI | inapPresetContainer.classList.remove('hidden'); |
| 291 | `` | UI | inapPresetContainer.classList.add('hidden'); |
| 295 | `` | UI | kategoriSelect.addEventListener('change', function () { |
| 300 | `` | UI | inapPresetSelect.addEventListener('change', function () { |
| 307 | `` | UI | inapPresetDesc.classList.remove('hidden'); |
| 309 | `` | UI | inapPresetDesc.classList.add('hidden'); |
| 312 | `` | UI | inapPresetDesc.classList.add('hidden'); |

## ðŸ„ `admin\master-data\item-gadai\edit.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Edit` | UI | @section('title', 'Edit Master Item Gadai') |
| 10 | `Edit` | UI | <h2 class="text-3xl font-black text-gray-900 tracking-tight font-display">Edit Item Gadai</h2> |
| 27 | `` | UI | @foreach($errors->all() as $error) |
| 28 | `` | UI | <li>{{ $error }}</li> |
| 35 | `` | UI | <form action="{{ route('admin.master-data.item-gadai.update', $data->id) }}" method="POST" |
| 45 | `` | UI | <select name="kategori_id" id="kategori_id" |
| 54 | `` | UI | </select> |
| 89 | `` | UI | <input type="text" name="head_1" id="head_1" |
| 98 | `` | UI | <input type="text" name="head_2" id="head_2" |
| 107 | `` | UI | <input type="number" name="nominal_real" id="nominal_real" |
| 114 | `Status` | UI | class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Status <span |
| 116 | `` | UI | <select name="is_active" id="is_active" |
| 122 | `` | UI | </select> |
| 134 | `` | UI | <input type="number" name="nominal_low" id="nominal_low" |
| 142 | `` | UI | <input type="number" name="bunga_low" id="bunga_low" step="0.01" |
| 152 | `` | UI | <input type="number" name="nominal_high" id="nominal_high" |
| 160 | `` | UI | <input type="number" name="bunga_high" id="bunga_high" step="0.01" |
| 171 | `` | UI | <select id="inap_preset" |
| 181 | `` | UI | </select> |
| 194 | `` | UI | <input type="number" name="nominal_inap" id="nominal_inap" |
| 218 | `` | UI | <input type="file" name="file_pic" id="file_pic" |
| 224 | `` | UI | <button type="submit" |
| 226 | `Update` | UI | Update Perubahan |
| 304 | `` | UI | inapPresetContainer.classList.remove('hidden'); |
| 306 | `` | UI | inapPresetContainer.classList.add('hidden'); |
| 310 | `` | UI | kategoriSelect.addEventListener('change', function () { |
| 315 | `` | UI | inapPresetSelect.addEventListener('change', function () { |
| 322 | `` | UI | inapPresetDesc.classList.remove('hidden'); |
| 324 | `` | UI | inapPresetDesc.classList.add('hidden'); |
| 327 | `` | UI | inapPresetDesc.classList.add('hidden'); |
| 344 | `` | UI | inapPresetDesc.classList.remove('hidden'); |

## ðŸ„ `admin\master-data\item-gadai\index.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 19 | `` | UI | <a href="{{ route('admin.master-data.item-gadai.create') }}" class="px-5 py-2.5 bg-[#674c1d] text-white font-bold rounded-xl hover:bg-[#8b6f2f] transition-all flex items-center gap-2 shadow-lg shadow-amber-900/20"> |
| 27 | `` | UI | @if(session('success')) |
| 30 | `` | UI | <span class="text-sm font-bold">{{ session('success') }}</span> |
| 41 | `Detail` | UI | <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Detail Item</th> |
| 44 | `Status` | UI | <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th> |
| 56 | `` | UI | <div class="w-12 h-12 rounded-xl overflow-hidden border border-gray-100 shadow-sm group-hover:scale-110 transition-transform duration-300"> |
| 99 | `Edit` | UI | <a href="{{ route('admin.master-data.item-gadai.edit', $item->id) }}" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors" title="Edit"> |
| 102 | `` | UI | <form action="{{ route('admin.master-data.item-gadai.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')"> |
| 104 | `` | UI | @method('DELETE') |
| 105 | `` | UI | <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors" title="Hapus"> |

## ðŸ„ `admin\master-data\jenis-deposito\create.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 19 | `` | UI | <form action="{{ route('admin.master-data.jenis-deposito.store') }}" method="POST" class="space-y-6"> |
| 24 | `` | UI | <input type="text" name="nama_jenis" value="{{ old('nama_jenis') }}" required |
| 27 | `` | UI | @error('nama_jenis') |
| 34 | `` | UI | <textarea name="deskripsi" rows="4" |
| 41 | `` | UI | <input type="hidden" name="status_aktif" value="0"> |
| 42 | `` | UI | <input type="checkbox" name="status_aktif" value="1" {{ old('status_aktif', true) ? 'checked' : '' }} |
| 44 | `Status` | UI | <span class="text-sm font-medium text-gray-700">Status Aktif</span> |
| 49 | `` | UI | <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#d4af37] to-[#8b6f2f] text-white rounded-xl hover:from-[#8b6f2f] hover:to-[#674c1d] transition-all font-medium shadow-md"> |

## ðŸ„ `admin\master-data\jenis-deposito\edit.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Edit` | UI | @section('title', 'Edit Jenis Deposito') |
| 13 | `Edit` | UI | <span class="text-gray-900 font-medium">Edit</span> |
| 15 | `Edit` | UI | <h1 class="text-3xl font-bold text-gray-900 font-display">Edit Jenis Deposito</h1> |
| 19 | `` | UI | <form action="{{ route('admin.master-data.jenis-deposito.update', $data->id) }}" method="POST" class="space-y-6"> |
| 25 | `` | UI | <input type="text" name="nama_jenis" value="{{ old('nama_jenis', $data->nama_jenis) }}" required |
| 31 | `` | UI | <textarea name="deskripsi" rows="4" |
| 37 | `` | UI | <input type="hidden" name="status_aktif" value="0"> |
| 38 | `` | UI | <input type="checkbox" name="status_aktif" value="1" {{ old('status_aktif', $data->status_aktif) ? 'checked' : '' }} |
| 40 | `Status` | UI | <span class="text-sm font-medium text-gray-700">Status Aktif</span> |
| 45 | `` | UI | <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#d4af37] to-[#8b6f2f] text-white rounded-xl hover:from-[#8b6f2f] hover:to-[#674c1d] transition-all font-medium shadow-md"> |
| 46 | `Update` | UI | Update Data |

## ðŸ„ `admin\master-data\jenis-deposito\index.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 18 | `` | UI | <a href="{{ route('admin.master-data.jenis-deposito.create') }}" class="px-4 py-2 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-md text-sm font-medium"> |
| 24 | `` | UI | @if(session('success')) |
| 26 | `` | UI | <p class="text-green-700 font-medium">{{ session('success') }}</p> |
| 30 | `` | UI | @if(session('error')) |
| 32 | `` | UI | <p class="text-red-700 font-medium">{{ session('error') }}</p> |
| 44 | `Status` | UI | <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Status</th> |
| 56 | `` | UI | <form action="{{ route('admin.master-data.jenis-deposito.toggle-status', $item->id) }}" method="POST" class="inline"> |
| 58 | `` | UI | <button type="submit" class="px-3 py-1 rounded-full text-xs font-medium {{ $item->status_aktif ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}"> |
| 71 | `` | UI | <a href="{{ route('admin.master-data.jenis-deposito.edit', $item->id) }}" class="p-2 text-[#674c1d] hover:bg-[#674c1d]/10 rounded-lg"> |
| 76 | `` | UI | <form action="{{ route('admin.master-data.jenis-deposito.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?')"> |
| 78 | `` | UI | @method('DELETE') |
| 79 | `` | UI | <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg"> |
| 86 | `View` | UI | <span class="text-xs text-gray-400">View Only</span> |

## ðŸ„ `admin\master-data\kategori-deposito\create.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 22 | `` | UI | <form action="{{ route('admin.master-data.kategori-deposito.store') }}" method="POST" class="p-6 md:p-8 space-y-6"> |
| 29 | `` | UI | <input type="text" name="nama_kategori" id="nama_kategori" required |
| 33 | `` | UI | @error('nama_kategori') |
| 38 | `Status` | UI | <!-- Status --> |
| 40 | `Status` | UI | <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label> |
| 42 | `` | UI | <select name="status" id="status" required |
| 44 | `` | UI | <option value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option> |
| 45 | `` | UI | <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option> |
| 46 | `` | UI | </select> |
| 51 | `` | UI | @error('status') |
| 59 | `` | UI | <textarea name="keterangan" id="keterangan" rows="3" |
| 62 | `` | UI | @error('keterangan') |
| 72 | `` | UI | <button type="submit" class="px-5 py-2.5 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white font-medium rounded-xl hover:shadow-lg hover:shadow-[#674c1d]/20 transition-all duration-300"> |

## ðŸ„ `admin\master-data\kategori-deposito\edit.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Edit` | UI | @section('title', 'Edit Kategori Deposito') |
| 15 | `Edit` | UI | <h1 class="text-2xl font-bold text-gray-900 font-display">Edit Kategori Deposito</h1> |
| 22 | `` | UI | <form action="{{ route('admin.master-data.kategori-deposito.update', $kategori->id) }}" method="POST" class="p-6 md:p-8 space-y-6"> |
| 30 | `` | UI | <input type="text" name="nama_kategori" id="nama_kategori" required |
| 33 | `` | UI | @error('nama_kategori') |
| 38 | `Status` | UI | <!-- Status --> |
| 40 | `Status` | UI | <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label> |
| 42 | `` | UI | <select name="status" id="status" required |
| 44 | `` | UI | <option value="aktif" {{ old('status', $kategori->status) == 'aktif' ? 'selected' : '' }}>Aktif</option> |
| 45 | `` | UI | <option value="nonaktif" {{ old('status', $kategori->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option> |
| 46 | `` | UI | </select> |
| 51 | `` | UI | @error('status') |
| 59 | `` | UI | <textarea name="keterangan" id="keterangan" rows="3" |
| 61 | `` | UI | @error('keterangan') |
| 71 | `` | UI | <button type="submit" class="px-5 py-2.5 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white font-medium rounded-xl hover:shadow-lg hover:shadow-[#674c1d]/20 transition-all duration-300"> |

## ðŸ„ `admin\master-data\kategori-deposito\index.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 14 | `` | UI | <a href="{{ route('admin.master-data.kategori-deposito.create') }}" |
| 15 | `` | UI | class="inline-flex items-center px-4 py-2.5 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white font-medium rounded-xl hover:shadow-lg hover:shadow-[#674c1d]/20 transition-all duration-300"> |
| 24 | `Success` | UI | <!-- Alert Success --> |
| 25 | `` | UI | @if(session('success')) |
| 30 | `` | UI | {{ session('success') }} |
| 43 | `Status` | UI | <th class="px-6 py-4">Status</th> |
| 56 | `` | UI | @if($k->status == 'aktif') |
| 68 | `Edit` | UI | <a href="{{ route('admin.master-data.kategori-deposito.edit', $k->id) }}" class="text-[#8b6f2f] hover:text-[#674c1d] transition-colors" title="Edit"> |
| 73 | `` | UI | @if($k->status == 'aktif') |
| 74 | `` | UI | <form action="{{ route('admin.master-data.kategori-deposito.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan kategori ini?');" class="inline-block"> |
| 76 | `` | UI | @method('DELETE') |
| 77 | `` | UI | <button type="submit" class="text-red-500 hover:text-red-700 transition-colors" title="Nonaktifkan"> |

## ðŸ„ `admin\master-data\kategori-gadai\edit.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Edit` | UI | @section('title', 'Edit Aturan Kategori') |
| 12 | `Edit` | UI | <h1 class="text-2xl font-bold text-gray-900">Edit Aturan: {{ $data->nama_kategori }}</h1> |
| 17 | `` | UI | <form action="{{ route('admin.master-data.kategori-gadai.update', $data->id) }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden"> |
| 26 | `` | UI | <input type="text" name="nama_kategori" value="{{ old('nama_kategori', $data->nama_kategori) }}" class="w-full border-gray-200 rounded-xl bg-gray-50 focus:ring-[#674c1d] focus:border-[#674c1d]" required> |
| 30 | `` | UI | <input type="text" value="{{ $data->kode_kategori }}" class="w-full border-gray-200 rounded-xl bg-gray-100 text-gray-500" readonly> |
| 40 | `` | UI | <input type="number" step="0.01" name="rate_jasa" value="{{ old('rate_jasa', $data->rate_jasa) }}" class="w-full pr-10 border-blue-200 rounded-xl bg-blue-50/30 focus:ring-blue-500 focus:border-blue-500 font-bold" required> |
| 47 | `` | UI | <input type="number" step="0.01" name="rate_denda" value="{{ old('rate_denda', $data->rate_denda) }}" class="w-full pr-10 border-red-200 rounded-xl bg-red-50/30 focus:ring-red-500 focus:border-red-500 font-bold" required> |
| 54 | `` | UI | <input type="number" step="0.01" name="rate_inap_persen" value="{{ old('rate_inap_persen', $data->rate_inap_persen) }}" class="w-full pr-10 border-amber-200 rounded-xl bg-amber-50/30 focus:ring-amber-500 focus:border-amber-500 font-bold" required> |
| 67 | `` | UI | <input type="number" name="masa_gadai_hari" value="{{ old('masa_gadai_hari', $data->masa_gadai_hari) }}" class="w-full border-gray-200 rounded-xl bg-gray-50 focus:ring-[#674c1d] focus:border-[#674c1d]" required> |
| 71 | `` | UI | <input type="number" name="masa_tenggang_hari" value="{{ old('masa_tenggang_hari', $data->masa_tenggang_hari) }}" class="w-full border-gray-200 rounded-xl bg-gray-50 focus:ring-[#674c1d] focus:border-[#674c1d]" required> |
| 75 | `` | UI | <input type="number" name="max_extend_default" value="{{ old('max_extend_default', $data->max_extend_default) }}" class="w-full border-gray-200 rounded-xl bg-gray-50 focus:ring-[#674c1d] focus:border-[#674c1d]" required> |
| 86 | `` | UI | <input type="hidden" name="update_inap_kendaraan" value="1"> |
| 102 | `` | UI | <input type="hidden" name="inap[{{ $inap->id }}][id]" value="{{ $inap->id }}"> |
| 105 | `` | UI | <input type="text" name="inap[{{ $inap->id }}][jenis_kendaraan]" value="{{ $inap->jenis_kendaraan }}" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white font-bold focus:ring-[#674c1d] focus:border-transparent transition-all text-gray-700" required> |
| 110 | `` | UI | <input type="number" name="inap[{{ $inap->id }}][nominal_inap]" value="{{ (int)$inap->nominal_inap }}" class="w-full pl-8 pr-3 py-2 border border-gray-200 rounded-lg text-sm bg-white font-black focus:ring-[#674c1d] focus:border-transparent transition-all text-emerald-600" min="0" required> |
| 114 | `` | UI | <input type="text" name="inap[{{ $inap->id }}][keterangan]" value="{{ $inap->keterangan }}" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white focus:ring-[#674c1d] focus:border-transparent transition-all text-gray-600"> |
| 127 | `` | UI | <button type="submit" class="px-6 py-2.5 bg-[#674c1d] text-white font-bold rounded-xl shadow-lg shadow-[#674c1d]/20 hover:shadow-xl hover:-translate-y-0.5 transition-all"> |

## ðŸ„ `admin\master-data\kategori-gadai\index.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 14 | `` | UI | @if(session('success')) |
| 17 | `` | UI | <p class="text-sm">{{ session('success') }}</p> |
| 77 | `` | UI | <a href="{{ route('admin.master-data.kategori-gadai.edit', $kat->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-white border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium"> |
| 79 | `Edit` | UI | Edit Aturan |
| 99 | `` | UI | <li><strong>Biaya Inap:</strong> Untuk kategori <strong>Emas/Elektronik</strong> biasanya menggunakan persentase. Untuk <strong>Kendaraan</strong>, biaya inap biasanya diatur per-item barang (Motor/Mobil) di menu <a href="{{ route('admin.master-data.item-gadai.index') }}" class="font-bold underline">Item Gadai</a>.</li> |

## ðŸ„ `admin\master-data\lokasi-perusahaan\create.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 19 | `` | UI | <form action="{{ route('admin.master-data.lokasi-perusahaan.store') }}" method="POST" class="space-y-6"> |
| 24 | `` | UI | <input type="text" name="nama_lokasi" value="{{ old('nama_lokasi') }}" required |
| 27 | `` | UI | @error('nama_lokasi') |
| 34 | `` | UI | <textarea name="alamat_lengkap" rows="3" required |
| 42 | `` | UI | <input type="text" name="kota" value="{{ old('kota') }}" required |
| 48 | `` | UI | <input type="text" name="provinsi" value="{{ old('provinsi') }}" required |
| 55 | `` | UI | <select name="tipe_lokasi" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none"> |
| 60 | `` | UI | </select> |
| 65 | `` | UI | <input type="hidden" name="status_aktif" value="0"> |
| 66 | `` | UI | <input type="checkbox" name="status_aktif" value="1" {{ old('status_aktif', true) ? 'checked' : '' }} |
| 68 | `Status` | UI | <span class="text-sm font-medium text-gray-700">Status Aktif</span> |
| 73 | `` | UI | <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl hover:from-[#4a3514] hover:to-[#674c1d] transition-all font-medium shadow-md"> |

## ðŸ„ `admin\master-data\lokasi-perusahaan\edit.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Edit` | UI | @section('title', 'Edit Lokasi Perusahaan') |
| 13 | `Edit` | UI | <span class="text-gray-900 font-medium">Edit</span> |
| 15 | `Edit` | UI | <h1 class="text-3xl font-bold text-gray-900 font-display">Edit Lokasi Perusahaan</h1> |
| 19 | `` | UI | <form action="{{ route('admin.master-data.lokasi-perusahaan.update', $data->id) }}" method="POST" class="space-y-6"> |
| 25 | `` | UI | <input type="text" name="nama_lokasi" value="{{ old('nama_lokasi', $data->nama_lokasi) }}" required |
| 31 | `` | UI | <textarea name="alamat_lengkap" rows="3" required |
| 38 | `` | UI | <input type="text" name="kota" value="{{ old('kota', $data->kota) }}" required |
| 44 | `` | UI | <input type="text" name="provinsi" value="{{ old('provinsi', $data->provinsi) }}" required |
| 51 | `` | UI | <select name="tipe_lokasi" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none"> |
| 56 | `` | UI | </select> |
| 61 | `` | UI | <input type="hidden" name="status_aktif" value="0"> |
| 62 | `` | UI | <input type="checkbox" name="status_aktif" value="1" {{ old('status_aktif', $data->status_aktif) ? 'checked' : '' }} |
| 64 | `Status` | UI | <span class="text-sm font-medium text-gray-700">Status Aktif</span> |
| 69 | `` | UI | <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl hover:from-[#4a3514] hover:to-[#674c1d] transition-all font-medium shadow-md"> |
| 70 | `Update` | UI | Update Data |

## ðŸ„ `admin\master-data\lokasi-perusahaan\index.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 18 | `` | UI | <a href="{{ route('admin.master-data.lokasi-perusahaan.create') }}" class="px-4 py-2 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-md text-sm font-medium"> |
| 24 | `` | UI | @if(session('success')) |
| 26 | `` | UI | <p class="text-green-700 font-medium">{{ session('success') }}</p> |
| 40 | `Status` | UI | <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Status</th> |
| 54 | `` | UI | <form action="{{ route('admin.master-data.lokasi-perusahaan.toggle-status', $item->id) }}" method="POST" class="inline"> |
| 56 | `` | UI | <button type="submit" class="px-3 py-1 rounded-full text-xs font-medium {{ $item->status_aktif ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}"> |
| 69 | `` | UI | <a href="{{ route('admin.master-data.lokasi-perusahaan.edit', $item->id) }}" class="p-2 text-[#674c1d] hover:bg-[#674c1d]/10 rounded-lg"> |
| 74 | `` | UI | <form action="{{ route('admin.master-data.lokasi-perusahaan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?')"> |
| 76 | `` | UI | @method('DELETE') |
| 77 | `` | UI | <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg"> |
| 84 | `View` | UI | <span class="text-xs text-gray-400">View Only</span> |

## ðŸ„ `admin\master-data\rekening-perusahaan\create.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 19 | `` | UI | <form action="{{ route('admin.master-data.rekening-perusahaan.store') }}" method="POST" class="space-y-6"> |
| 24 | `` | UI | <input type="text" name="pemilik" value="{{ old('pemilik') }}" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 outline-none" placeholder="Contoh: Admin, Bang Farhan"> |
| 25 | `` | UI | @error('pemilik')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror |
| 29 | `` | UI | <input type="text" name="nama" value="{{ old('nama') }}" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 outline-none" placeholder="Contoh: Operasional BCA"> |
| 30 | `` | UI | @error('nama')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror |
| 38 | `` | UI | <input type="text" name="bank" id="bank-input" value="{{ old('bank') }}" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 outline-none" placeholder="Contoh: BCA, Mandiri, BNI"> |
| 44 | `` | UI | @error('bank')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror |
| 49 | `` | UI | <input type="text" name="no_rek" value="{{ old('no_rek') }}" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 outline-none" placeholder="Nomor rekening"> |
| 50 | `` | UI | @error('no_rek')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror |
| 57 | `` | UI | <input type="text" name="cabang" value="{{ old('cabang') }}" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 outline-none" placeholder="Contoh: KCU Malang"> |
| 58 | `` | UI | @error('cabang')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror |
| 61 | `Transfer` | UI | <label class="block text-sm font-semibold text-gray-700 mb-2">Kode Bank (Transfer)</label> |
| 62 | `` | UI | <input type="text" name="kode_bank" value="{{ old('kode_bank') }}" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 outline-none" placeholder="Contoh: 014"> |
| 63 | `` | UI | @error('kode_bank')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror |
| 69 | `Status` | UI | <label class="block text-sm font-semibold text-gray-700 mb-2">Status Rekening *</label> |
| 70 | `` | UI | <select name="status" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 outline-none"> |
| 71 | `` | UI | <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option> |
| 72 | `` | UI | <option value="non-aktif" {{ old('status') == 'non-aktif' ? 'selected' : '' }}>Non-Aktif</option> |
| 73 | `` | UI | </select> |
| 74 | `` | UI | @error('status')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror |
| 90 | `` | UI | imgPreview.classList.remove('hidden'); |
| 91 | `` | UI | initialPreview.classList.add('hidden'); |
| 93 | `` | UI | imgPreview.classList.add('hidden'); |
| 94 | `` | UI | initialPreview.classList.remove('hidden'); |
| 100 | `` | UI | <button type="submit" class="px-6 py-3 bg-gradient-to-r from-[#8b6f2f] to-[#d4af37] text-white rounded-xl hover:from-[#674c1d] hover:to-[#8b6f2f] transition-all font-medium shadow-md">Simpan Data</button> |

## ðŸ„ `admin\master-data\rekening-perusahaan\edit.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Edit` | UI | @section('title', 'Edit Rekening Perusahaan') |
| 13 | `Edit` | UI | <span class="text-gray-900 font-medium">Edit</span> |
| 15 | `Edit` | UI | <h1 class="text-3xl font-bold text-gray-900 font-display">Edit Rekening Perusahaan</h1> |
| 19 | `` | UI | <form action="{{ route('admin.master-data.rekening-perusahaan.update', $data->id) }}" method="POST" class="space-y-6"> |
| 26 | `` | UI | <input type="text" name="pemilik" value="{{ old('pemilik', $data->pemilik) }}" required |
| 28 | `` | UI | @error('pemilik')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror |
| 32 | `` | UI | <input type="text" name="nama" value="{{ old('nama', $data->nama) }}" required |
| 34 | `` | UI | @error('nama')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror |
| 42 | `` | UI | <input type="text" name="bank" id="bank-input" value="{{ old('bank', $data->bank) }}" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 outline-none" placeholder="Contoh: BCA, Mandiri, BNI"> |
| 48 | `` | UI | @error('bank')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror |
| 53 | `` | UI | <input type="text" name="no_rek" value="{{ old('no_rek', $data->no_rek) }}" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 outline-none" placeholder="Nomor rekening"> |
| 54 | `` | UI | @error('no_rek')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror |
| 61 | `` | UI | <input type="text" name="cabang" value="{{ old('cabang', $data->cabang) }}" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 outline-none" placeholder="Contoh: KCU Malang"> |
| 62 | `` | UI | @error('cabang')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror |
| 65 | `Transfer` | UI | <label class="block text-sm font-semibold text-gray-700 mb-2">Kode Bank (Transfer)</label> |
| 66 | `` | UI | <input type="text" name="kode_bank" value="{{ old('kode_bank', $data->kode_bank) }}" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 outline-none" placeholder="Contoh: 014"> |
| 67 | `` | UI | @error('kode_bank')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror |
| 73 | `Status` | UI | <label class="block text-sm font-semibold text-gray-700 mb-2">Status Rekening *</label> |
| 74 | `` | UI | <select name="status" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 outline-none"> |
| 75 | `` | UI | <option value="aktif" {{ old('status', $data->status) == 'aktif' ? 'selected' : '' }}>Aktif</option> |
| 76 | `` | UI | <option value="non-aktif" {{ old('status', $data->status) == 'non-aktif' ? 'selected' : '' }}>Non-Aktif</option> |
| 77 | `` | UI | </select> |
| 78 | `` | UI | @error('status')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror |
| 94 | `` | UI | imgPreview.classList.remove('hidden'); |
| 95 | `` | UI | initialPreview.classList.add('hidden'); |
| 97 | `` | UI | imgPreview.classList.add('hidden'); |
| 98 | `` | UI | initialPreview.classList.remove('hidden'); |
| 112 | `` | UI | <button type="submit" class="px-6 py-3 bg-gradient-to-r from-[#8b6f2f] to-[#d4af37] text-white rounded-xl hover:from-[#674c1d] hover:to-[#8b6f2f] transition-all font-medium shadow-md"> |
| 113 | `Update` | UI | Update Data |

## ðŸ„ `admin\master-data\rekening-perusahaan\index.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 16 | `` | UI | <a href="{{ route('admin.master-data.rekening-perusahaan.create') }}" class="px-4 py-2 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg shadow-md text-sm font-medium">+ Tambah Data</a> |
| 19 | `` | UI | @if(session('success')) |
| 20 | `` | UI | <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg"><p class="text-green-700 font-medium">{{ session('success') }}</p></div> |
| 32 | `Status` | UI | <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase">Status</th> |
| 58 | `` | UI | @if(($item->status ?? 'aktif') == 'aktif') |
| 67 | `Edit` | UI | <a href="{{ route('admin.master-data.rekening-perusahaan.edit', $item->id) }}" class="p-2 text-[#674c1d] hover:bg-[#674c1d]/10 rounded-lg">Edit</a> |
| 68 | `` | UI | <form action="{{ route('admin.master-data.rekening-perusahaan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?')"> |
| 70 | `` | UI | @method('DELETE') |
| 71 | `` | UI | <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg">Hapus</button> |
| 74 | `View` | UI | <span class="text-xs text-gray-400">View Only</span> |

## ðŸ„ `admin\master-data\slot-storage\index.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 21 | `` | UI | @if(session('success')) |
| 24 | `` | UI | <span>{{ session('success') }}</span> |
| 28 | `` | UI | @if(session('error')) |
| 31 | `` | UI | <span>{{ session('error') }}</span> |
| 39 | `` | UI | @foreach ($errors->all() as $error) |
| 40 | `` | UI | <li>{{ $error }}</li> |
| 50 | `` | UI | <form action="{{ route('admin.master-data.slot-storage.index') }}" method="GET" class="space-y-4"> |
| 51 | `` | UI | <select name="kategori" class="w-full border-gray-200 rounded-xl focus:ring-[#674c1d] focus:border-[#674c1d]" onchange="this.form.submit()"> |
| 55 | `` | UI | </select> |
| 81 | `` | UI | <form action="{{ route('admin.master-data.slot-storage.store') }}" method="POST" class="space-y-5"> |
| 83 | `` | UI | <input type="hidden" name="kategori" value="{{ $selectedKategori }}"> |
| 88 | `` | UI | <select name="jenis" class="w-full border-gray-200 rounded-xl focus:ring-[#674c1d] focus:border-[#674c1d]" required> |
| 91 | `` | UI | </select> |
| 95 | `` | UI | <input type="number" name="jumlah" min="1" max="10" value="1" class="w-full border-gray-200 rounded-xl focus:ring-[#674c1d] focus:border-[#674c1d]" required> |
| 100 | `` | UI | <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white font-medium rounded-xl hover:shadow-lg transition-all"> |
| 112 | `` | UI | <form action="{{ route('admin.master-data.slot-storage.reduce') }}" method="POST" class="space-y-5"> |
| 114 | `` | UI | <input type="hidden" name="kategori" value="{{ $selectedKategori }}"> |
| 119 | `` | UI | <select name="jenis" class="w-full border-gray-200 rounded-xl focus:ring-red-500 focus:border-red-500" required> |
| 122 | `` | UI | </select> |
| 126 | `` | UI | <input type="number" name="jumlah" min="1" max="10" value="1" class="w-full border-gray-200 rounded-xl focus:ring-red-500 focus:border-red-500" required> |
| 131 | `` | UI | <button type="submit" class="px-5 py-2.5 bg-red-600 text-white font-medium rounded-xl hover:bg-red-700 hover:shadow-lg transition-all"> |

## ðŸ„ `admin\master-data\suku-bunga-deposito\create.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 19 | `` | UI | <form action="{{ route('admin.master-data.suku-bunga-deposito.store') }}" method="POST" class="space-y-6"> |
| 24 | `` | UI | <select name="tenor_id" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#d4af37] focus:ring-2 focus:ring-[#d4af37]/20 outline-none"> |
| 31 | `` | UI | </select> |
| 39 | `` | UI | <input type="number" name="min_nominal" value="{{ old('min_nominal') }}" required min="0" |
| 48 | `` | UI | <input type="number" name="max_nominal" value="{{ old('max_nominal') }}" required min="0" |
| 57 | `` | UI | <input type="number" name="bunga" value="{{ old('bunga') }}" required min="0" max="100" step="0.0001" |
| 65 | `` | UI | <input type="hidden" name="status" value="0"> |
| 66 | `` | UI | <input type="checkbox" name="status" value="1" {{ old('status', true) ? 'checked' : '' }} |
| 68 | `Status` | UI | <span class="text-sm font-medium text-gray-700">Status Aktif</span> |
| 73 | `` | UI | <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#d4af37] to-[#8b6f2f] text-white rounded-xl hover:from-[#8b6f2f] hover:to-[#674c1d] transition-all font-medium shadow-md"> |

## ðŸ„ `admin\master-data\suku-bunga-deposito\edit.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Edit` | UI | @section('title', 'Edit Suku Bunga Deposito') |
| 13 | `Edit` | UI | <span class="text-gray-900 font-medium">Edit</span> |
| 15 | `Edit` | UI | <h1 class="text-3xl font-bold text-gray-900 font-display">Edit Suku Bunga Deposito</h1> |
| 19 | `` | UI | <form action="{{ route('admin.master-data.suku-bunga-deposito.update', $data->id) }}" method="POST" class="space-y-6"> |
| 25 | `` | UI | <select name="tenor_id" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#d4af37] focus:ring-2 focus:ring-[#d4af37]/20 outline-none"> |
| 32 | `` | UI | </select> |
| 40 | `` | UI | <input type="number" name="min_nominal" value="{{ old('min_nominal', $data->min_nominal) }}" required min="0" |
| 49 | `` | UI | <input type="number" name="max_nominal" value="{{ old('max_nominal', $data->max_nominal) }}" required min="0" |
| 58 | `` | UI | <input type="number" name="bunga" value="{{ old('bunga', $data->bunga) }}" required min="0" max="100" step="0.0001" |
| 66 | `` | UI | <input type="hidden" name="status" value="0"> |
| 67 | `` | UI | <input type="checkbox" name="status" value="1" {{ old('status', $data->status) ? 'checked' : '' }} |
| 69 | `Status` | UI | <span class="text-sm font-medium text-gray-700">Status Aktif</span> |
| 74 | `` | UI | <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#d4af37] to-[#8b6f2f] text-white rounded-xl hover:from-[#8b6f2f] hover:to-[#674c1d] transition-all font-medium shadow-md"> |
| 75 | `Update` | UI | Update Data |

## ðŸ„ `admin\master-data\suku-bunga-deposito\index.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 18 | `` | UI | <a href="{{ route('admin.master-data.suku-bunga-deposito.create') }}" class="px-4 py-2 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-md text-sm font-medium"> |
| 24 | `` | UI | @if(session('success')) |
| 26 | `` | UI | <p class="text-green-700 font-medium">{{ session('success') }}</p> |
| 39 | `Status` | UI | <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Status</th> |
| 56 | `` | UI | <form action="{{ route('admin.master-data.suku-bunga-deposito.toggle-status', $item->id) }}" method="POST" class="inline"> |
| 58 | `` | UI | <button type="submit" class="px-3 py-1 rounded-full text-xs font-medium {{ $item->status ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}"> |
| 59 | `` | UI | {{ $item->status ? 'Aktif' : 'Nonaktif' }} |
| 63 | `` | UI | <span class="px-3 py-1 rounded-full text-xs font-medium {{ $item->status ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}"> |
| 64 | `` | UI | {{ $item->status ? 'Aktif' : 'Nonaktif' }} |
| 71 | `` | UI | <a href="{{ route('admin.master-data.suku-bunga-deposito.edit', $item->id) }}" class="p-2 text-[#674c1d] hover:bg-[#674c1d]/10 rounded-lg"> |
| 76 | `` | UI | <form action="{{ route('admin.master-data.suku-bunga-deposito.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?')"> |
| 78 | `` | UI | @method('DELETE') |
| 79 | `` | UI | <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg"> |
| 86 | `View` | UI | <span class="text-xs text-gray-400">View Only</span> |

## ðŸ„ `admin\master-data\suku-bunga-tabungan\create.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 19 | `` | UI | <form action="{{ route('admin.master-data.suku-bunga-tabungan.store') }}" method="POST" class="space-y-6"> |
| 23 | `` | UI | <input type="text" name="jenis_bunga" value="{{ old('jenis_bunga') }}" required |
| 26 | `` | UI | @error('jenis_bunga') |
| 34 | `` | UI | <input type="number" name="opsi_val" value="{{ old('opsi_val') }}" required min="0" max="100" step="0.0001" |
| 42 | `` | UI | <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#4a3514] to-[#674c1d] text-white rounded-xl hover:from-[#674c1d] hover:to-[#8b6f2f] transition-all font-medium shadow-md"> |

## ðŸ„ `admin\master-data\suku-bunga-tabungan\edit.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Edit` | UI | @section('title', 'Edit Suku Bunga Tabungan') |
| 13 | `Edit` | UI | <span class="text-gray-900 font-medium">Edit</span> |
| 15 | `Edit` | UI | <h1 class="text-3xl font-bold text-gray-900 font-display">Edit Suku Bunga Tabungan</h1> |
| 19 | `` | UI | <form action="{{ route('admin.master-data.suku-bunga-tabungan.update', $data->id) }}" method="POST" class="space-y-6"> |
| 25 | `` | UI | <input type="text" name="jenis_bunga" value="{{ old('jenis_bunga', $data->jenis_bunga) }}" required |
| 32 | `` | UI | <input type="number" name="opsi_val" value="{{ old('opsi_val', $data->opsi_val) }}" required min="0" max="100" step="0.0001" |
| 39 | `` | UI | <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#4a3514] to-[#674c1d] text-white rounded-xl hover:from-[#674c1d] hover:to-[#8b6f2f] transition-all font-medium shadow-md"> |
| 40 | `Update` | UI | Update Data |

## ðŸ„ `admin\master-data\suku-bunga-tabungan\index.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 18 | `` | UI | <a href="{{ route('admin.master-data.suku-bunga-tabungan.create') }}" class="px-4 py-2 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-md text-sm font-medium"> |
| 24 | `` | UI | @if(session('success')) |
| 26 | `` | UI | <p class="text-green-700 font-medium">{{ session('success') }}</p> |
| 50 | `` | UI | <a href="{{ route('admin.master-data.suku-bunga-tabungan.edit', $item->id) }}" class="p-2 text-[#674c1d] hover:bg-[#674c1d]/10 rounded-lg"> |
| 55 | `` | UI | <form action="{{ route('admin.master-data.suku-bunga-tabungan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?')"> |
| 57 | `` | UI | @method('DELETE') |
| 58 | `` | UI | <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg"> |
| 65 | `View` | UI | <span class="text-xs text-gray-400">View Only</span> |

## ðŸ„ `admin\master-data\tenor-deposito\create.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 19 | `` | UI | <form action="{{ route('admin.master-data.tenor-deposito.store') }}" method="POST" class="space-y-6"> |
| 24 | `` | UI | <input type="number" name="tenor_hari" value="{{ old('tenor_hari') }}" required min="1" |
| 31 | `` | UI | <input type="number" name="tenor_bulan" value="{{ old('tenor_bulan') }}" required min="1" |
| 39 | `` | UI | <input type="hidden" name="aktif" value="0"> |
| 40 | `` | UI | <input type="checkbox" name="aktif" value="1" {{ old('aktif', true) ? 'checked' : '' }} |
| 42 | `Status` | UI | <span class="text-sm font-medium text-gray-700">Status Aktif</span> |
| 47 | `` | UI | <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#d4af37] to-[#8b6f2f] text-white rounded-xl hover:from-[#8b6f2f] hover:to-[#674c1d] transition-all font-medium shadow-md"> |

## ðŸ„ `admin\master-data\tenor-deposito\edit.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Edit` | UI | @section('title', 'Edit Tenor Deposito') |
| 13 | `Edit` | UI | <span class="text-gray-900 font-medium">Edit</span> |
| 15 | `Edit` | UI | <h1 class="text-3xl font-bold text-gray-900 font-display">Edit Tenor Deposito</h1> |
| 19 | `` | UI | <form action="{{ route('admin.master-data.tenor-deposito.update', $data->id) }}" method="POST" class="space-y-6"> |
| 26 | `` | UI | <input type="number" name="tenor_hari" value="{{ old('tenor_hari', $data->tenor_hari) }}" required min="1" |
| 32 | `` | UI | <input type="number" name="tenor_bulan" value="{{ old('tenor_bulan', $data->tenor_bulan) }}" required min="1" |
| 39 | `` | UI | <input type="hidden" name="aktif" value="0"> |
| 40 | `` | UI | <input type="checkbox" name="aktif" value="1" {{ old('aktif', $data->aktif) ? 'checked' : '' }} |
| 42 | `Status` | UI | <span class="text-sm font-medium text-gray-700">Status Aktif</span> |
| 47 | `` | UI | <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#d4af37] to-[#8b6f2f] text-white rounded-xl hover:from-[#8b6f2f] hover:to-[#674c1d] transition-all font-medium shadow-md"> |
| 48 | `Update` | UI | Update Data |

## ðŸ„ `admin\master-data\tenor-deposito\index.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 18 | `` | UI | <a href="{{ route('admin.master-data.tenor-deposito.create') }}" class="px-4 py-2 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-md text-sm font-medium"> |
| 24 | `` | UI | @if(session('success')) |
| 26 | `` | UI | <p class="text-green-700 font-medium">{{ session('success') }}</p> |
| 30 | `` | UI | @if(session('error')) |
| 32 | `` | UI | <p class="text-red-700 font-medium">{{ session('error') }}</p> |
| 44 | `Status` | UI | <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Status</th> |
| 57 | `` | UI | <form action="{{ route('admin.master-data.tenor-deposito.toggle-status', $item->id) }}" method="POST" class="inline"> |
| 59 | `` | UI | <button type="submit" class="px-3 py-1 rounded-full text-xs font-medium {{ $item->aktif ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }} hover:opacity-80"> |
| 73 | `` | UI | <a href="{{ route('admin.master-data.tenor-deposito.edit', $item->id) }}" class="p-2 text-[#674c1d] hover:bg-[#674c1d]/10 rounded-lg"> |
| 78 | `` | UI | <form action="{{ route('admin.master-data.tenor-deposito.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?')"> |
| 80 | `` | UI | @method('DELETE') |
| 81 | `` | UI | <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg"> |
| 88 | `View` | UI | <span class="text-xs text-gray-400">View Only</span> |

## ðŸ„ `admin\nasabah\detail.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Detail` | UI | @section('title', 'Detail Nasabah - ' . $nasabah->user->nama) |
| 16 | `Detail` | UI | <h1 class="text-3xl font-bold text-gray-900 font-display">Detail Nasabah</h1> |
| 20 | `` | UI | <a href="{{ route('admin.nasabah.pending-changes') }}" class="px-5 py-3 bg-yellow-500 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all flex items-center gap-2"> |
| 24 | `Pending` | UI | {{ $pendingChanges->count() }} Pengajuan Pending |
| 29 | `Profile` | UI | <!-- Profile Header --> |
| 46 | `` | UI | {{ $nasabah->user->email }} |
| 85 | `Email` | UI | <span class="text-sm text-gray-600 font-semibold">Email</span> |
| 86 | `` | UI | <span class="text-sm text-gray-900">{{ $nasabah->user->email ?? '-' }}</span> |
| 258 | `Email` | UI | <span class="text-sm text-gray-600 font-semibold">Email</span> |
| 259 | `` | UI | <span class="text-sm text-gray-900">{{ $nasabah->darurat->email ?? '-' }}</span> |
| 279 | `Reset` | UI | <!-- Reset PIN Nasabah - Only for Admin Utama --> |
| 289 | `Reset` | UI | <h2 class="text-xl font-bold text-gray-900 font-display">Reset PIN Nasabah</h2> |
| 302 | `` | UI | <li>Pastikan nasabah benar-benar meminta reset PIN</li> |
| 311 | `` | UI | <form id="form-reset-pin" action="{{ route('admin.nasabah.reset-pin', $nasabah->id) }}" method="POST" class="space-y-4"> |
| 317 | `` | UI | <input type="text" name="pin_baru" id="pin_baru" maxlength="6" required |
| 321 | `` | UI | <button type="button" onclick="generateRandomPinAdmin()" class="px-6 py-3 bg-linear-to-r from-blue-600 to-blue-700 text-white rounded-xl font-semibold hover:from-blue-700 hover:to-blue-800 transition-all shadow-md flex items-center gap-2"> |
| 339 | `Reset` | UI | <li>Klik "Reset PIN" untuk menyimpan PIN baru</li> |
| 349 | `` | UI | <button type="submit" onclick="return confirm('Yakin reset PIN nasabah ini? PIN lama akan diganti dengan PIN baru.')" |
| 354 | `Reset` | UI | Reset PIN Nasabah |
| 368 | `Pending` | UI | <!-- Pending Changes Alert (jika ada) --> |
| 378 | `Pending` | UI | <h3 class="text-lg font-bold text-yellow-900 mb-2">Pengajuan Perubahan Data Pending</h3> |
| 380 | `` | UI | <a href="{{ route('admin.nasabah.pending-changes') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-yellow-500 text-white rounded-xl font-semibold hover:bg-yellow-600 transition-all shadow-md"> |
| 384 | `Pending` | UI | Lihat Semua Pengajuan Pending |
| 417 | `` | UI | setTimeout(() => toast.remove(), 3000); |
| 422 | `` | UI | } catch (error) { |

## ðŸ„ `admin\nasabah\index.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 13 | `` | UI | <a href="{{ route('admin.nasabah.pending-changes') }}" class="px-5 py-3 bg-linear-to-r from-yellow-500 to-yellow-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all flex items-center gap-2"> |
| 17 | `Pending` | UI | Pengajuan Pending |
| 25 | `` | UI | @if(session('success')) |
| 31 | `` | UI | <p class="text-green-700 font-semibold">{{ session('success') }}</p> |
| 36 | `` | UI | @if(session('error')) |
| 42 | `` | UI | <p class="text-red-700 font-semibold">{{ session('error') }}</p> |
| 47 | `Search` | UI | <!-- Search & Filter --> |
| 49 | `` | UI | <form method="GET" action="{{ route('admin.nasabah.index') }}" class="flex gap-4"> |
| 55 | `` | UI | <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan nama, email, atau nomor HP..." |
| 59 | `` | UI | <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-semibold hover:shadow-lg transition-all"> |
| 62 | `` | UI | @if(request('search')) |
| 64 | `Reset` | UI | Reset |
| 73 | `` | UI | <h2 class="text-xl font-bold text-gray-900 font-display">Daftar Nasabah ({{ $nasabahList->total() }})</h2> |
| 84 | `Email` | UI | <th class="px-6 py-4 text-left text-sm font-semibold">Email</th> |
| 87 | `Status` | UI | <th class="px-6 py-4 text-left text-sm font-semibold">Status</th> |
| 108 | `` | UI | <td class="px-6 py-4 text-sm text-gray-700">{{ $nasabah->user->email ?? 'N/A' }}</td> |
| 115 | `` | UI | <a href="{{ route('admin.nasabah.show', $nasabah->id) }}" class="inline-flex items-center gap-1 px-4 py-2 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg font-semibold hover:shadow-lg transition-all text-sm"> |
| 120 | `Detail` | UI | Detail |

## ðŸ„ `admin\nasabah\pending-changes.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 24 | `` | UI | @if(session('success')) |
| 30 | `` | UI | <p class="text-green-800 font-medium">{{ session('success') }}</p> |
| 35 | `` | UI | @if(session('error')) |
| 41 | `` | UI | <p class="text-red-800 font-medium">{{ session('error') }}</p> |
| 56 | `Pending` | UI | <h2 class="text-lg font-bold text-gray-800">Daftar Pengajuan Pending</h2> |
| 57 | `Total` | UI | <p class="text-sm text-gray-600">Total: {{ $pendingRequests->total() }} pengajuan</p> |
| 83 | `` | UI | {{ $request->nasabah->user->email }} |
| 105 | `Action` | UI | <!-- Action Button --> |
| 106 | `` | UI | <button type="button" onclick="openDetailModal('modal{{ $request->id }}')" |
| 133 | `Detail` | UI | <!-- Modal Detail (Modern Design) --> |
| 146 | `Detail` | UI | <h3 class="text-xl font-bold text-white">Detail Perubahan Data</h3> |
| 176 | `` | UI | <span class="text-gray-700">{{ $request->nasabah->user->email }}</span> |
| 250 | `Actions` | UI | <!-- Footer Actions --> |
| 252 | `` | UI | <button type="button" onclick="closeDetailModal('modal{{ $request->id }}')" |
| 257 | `` | UI | <form action="{{ route('admin.nasabah.reject-change', $request->id) }}" method="POST" class="inline"> |
| 259 | `` | UI | <input type="hidden" name="catatan_admin" id="reject_catatan_{{ $request->id }}"> |
| 260 | `` | UI | <button type="submit" onclick="return confirmReject({{ $request->id }})" |
| 268 | `` | UI | <form action="{{ route('admin.nasabah.approve-change', $request->id) }}" method="POST" class="inline"> |
| 270 | `` | UI | <input type="hidden" name="catatan_admin" id="approve_catatan_{{ $request->id }}"> |
| 271 | `` | UI | <button type="submit" onclick="return confirmApprove({{ $request->id }})" |
| 298 | `Pending` | UI | <h3 class="text-xl font-bold text-gray-800 mb-2">Tidak Ada Pengajuan Pending</h3> |
| 315 | `` | UI | document.getElementById(modalId).classList.remove('hidden'); |
| 320 | `` | UI | document.getElementById(modalId).classList.add('hidden'); |
| 328 | `` | UI | return confirm('Apakah Anda yakin ingin MENYETUJUI perubahan data ini?\n\nData akan langsung diupdate di database.'); |
| 344 | `` | UI | return confirm('Apakah Anda yakin ingin MENOLAK perubahan data ini?'); |

## ðŸ„ `admin\notifications\index.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 15 | `` | UI | <form method="POST" action="{{ route('admin.notifications.mark-all-read') }}" class="inline"> |
| 17 | `` | UI | <button type="submit" class="px-4 py-2 bg-[#674c1d] text-white rounded-lg hover:bg-[#4a3514] transition-colors text-sm font-medium"> |
| 22 | `` | UI | <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium"> |
| 23 | `Dashboard` | UI | ← Dashboard |
| 28 | `` | UI | @if(session('success')) |
| 30 | `` | UI | {{ session('success') }} |
| 34 | `Filter` | UI | <!-- Filter --> |
| 36 | `` | UI | <form method="GET" action="{{ route('admin.notifications.index') }}" class="flex flex-wrap items-center gap-3"> |
| 38 | `` | UI | <input type="checkbox" name="unread_only" value="1" {{ request('unread_only') ? 'checked' : '' }} class="rounded border-gray-300 text-[#674c1d] focus:ring-[#674c1d]"> |
| 41 | `Filter` | UI | <button type="submit" class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-medium">Filter</button> |
| 49 | `` | UI | <form method="POST" action="{{ route('admin.notifications.mark-read', $notif->id) }}" class="block"> |
| 51 | `` | UI | <input type="hidden" name="redirect" value="{{ $notif->link ?: url()->current() }}"> |
| 52 | `` | UI | <button type="submit" class="w-full text-left px-6 py-4 hover:bg-gray-50/50 transition-colors flex items-start gap-4"> |
| 54 | `` | UI | @if($notif->type === 'tabungan_setor') bg-green-100 text-green-700 |
| 55 | `` | UI | @elseif($notif->type === 'tabungan_tarik') bg-amber-100 text-amber-700 |
| 56 | `` | UI | @elseif(str_starts_with($notif->type, 'pinjaman')) bg-blue-100 text-blue-700 |
| 57 | `` | UI | @elseif($notif->type === 'janji_temu') bg-purple-100 text-purple-700 |
| 59 | `` | UI | @if($notif->type === 'tabungan_setor') |
| 61 | `` | UI | @elseif($notif->type === 'tabungan_tarik') |
| 63 | `` | UI | @elseif(str_starts_with($notif->type, 'pinjaman')) |

## ðŸ„ `admin\pengajuan\index.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 18 | `` | UI | <a href="{{ route('admin.tabungan.pengajuan-setor') }}" class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-all duration-300"> |
| 31 | `` | UI | <a href="{{ route('admin.tabungan.pengajuan-tarik') }}" class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-all duration-300"> |
| 44 | `` | UI | <a href="{{ route('admin.pinjaman.pengajuan') }}" class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-all duration-300"> |
| 57 | `` | UI | <a href="{{ route('admin.janji-temu.index') }}" class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-all duration-300"> |
| 81 | `` | UI | Halaman ini menampilkan semua jenis pengajuan dari nasabah. Gunakan menu di atas untuk mengakses pengajuan spesifik: |

## ðŸ„ `admin\petty-cash\admin-dashboard.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Dashboard` | UI | @section('title', 'Dashboard Operasional Petty Cash') |
| 10 | `Dashboard` | UI | <h1 class="text-3xl font-bold text-gray-900 font-display">Dashboard Petty Cash</h1> |
| 15 | `` | UI | @if(session('success')) |
| 20 | `` | UI | {{ session('success') }} |
| 36 | `Total` | UI | <p class="text-white/70 text-xs font-bold uppercase tracking-widest mb-1 font-display">Total Sisa Saldo (Modal + Clearing)</p> |
| 48 | `Total` | UI | <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">Total Cash Fisik</p> |
| 61 | `Total` | UI | <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">Total Bank Transfer</p> |
| 91 | `` | PHP | $total = $cash + $tf; |
| 101 | `` | UI | <p class="text-2xl font-black text-{{ $color }}-900 font-display">Rp {{ number_format($total, 0, ',', '.') }}</p> |

## ðŸ„ `admin\petty-cash\dashboard.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Dashboard` | UI | @section('title', 'Dashboard Petty Cash') |
| 10 | `Dashboard` | UI | <h1 class="text-3xl font-bold text-gray-900 font-display">Dashboard Petty Cash</h1> |
| 14 | `` | UI | <a href="{{ route('admin.petty-cash.penerimaan.create') }}" |
| 28 | `` | UI | @if(session('success')) |
| 33 | `` | UI | {{ session('success') }} |
| 97 | `Pending` | UI | <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold">{{ $totalPending }} Pending</span> |
| 221 | `` | UI | <a href="{{ route('admin.petty-cash.setoran-approval.detail', $s->id) }}" |

## ðŸ„ `admin\petty-cash\laporan.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 12 | `` | UI | <a href="{{ route('admin.petty-cash.dashboard') }}" |
| 14 | `Dashboard` | UI | ← Dashboard |
| 61 | `Total` | UI | <p class="text-xs font-bold text-green-700 uppercase">Total Pengiriman</p> |
| 72 | `Transfer` | UI | <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Transfer (Bank)</p> |
| 87 | `Total` | UI | <p class="text-xs font-bold text-blue-700 uppercase">Total Setoran</p> |
| 98 | `Transfer` | UI | <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Transfer (Bank)</p> |
| 116 | `Total` | UI | (Manual In + Total Setoran) - (Manual Out + Total Kiriman) = |
| 122 | `Total` | UI | <p class="text-[10px] font-bold text-indigo-300 uppercase tracking-widest mb-1">Total Saldo Dompet Utama</p> |
| 138 | `` | UI | <select name="admin_id" class="border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#674c1d]"> |
| 145 | `` | UI | </select> |
| 149 | `` | UI | <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" |
| 154 | `` | UI | <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" |
| 158 | `Status` | UI | <label class="block text-xs font-semibold text-gray-600 mb-1.5">Status</label> |
| 159 | `` | UI | <select name="status" class="border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#674c1d]"> |
| 160 | `Status` | UI | <option value="">Semua Status</option> |
| 161 | `Pending` | UI | <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option> |
| 162 | `` | UI | <option value="approved_owner" {{ request('status') === 'approved_owner' ? 'selected' : '' }}>Disetujui</option> |
| 163 | `` | UI | <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option> |
| 164 | `` | UI | </select> |
| 166 | `` | UI | <button type="submit" |
| 168 | `Filter` | UI | Filter |
| 170 | `` | UI | @if(request()->hasAny(['admin_id', 'tanggal_dari', 'tanggal_sampai', 'status'])) |
| 173 | `Reset` | UI | Reset |
| 187 | `Total` | UI | <th class="px-5 py-4 text-right text-xs font-bold text-[#674c1d] uppercase">Total</th> |
| 190 | `Status` | UI | <th class="px-5 py-4 text-center text-xs font-bold text-[#674c1d] uppercase">Status</th> |
| 191 | `Detail` | UI | <th class="px-5 py-4 text-center text-xs font-bold text-[#674c1d] uppercase">Detail</th> |
| 209 | `` | UI | @if($item->status === 'pending') |
| 210 | `Pending` | UI | <span class="px-2.5 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">Pending</span> |
| 211 | `` | UI | @elseif($item->status === 'approved_owner') |
| 218 | `` | UI | <button onclick="document.getElementById('detail-lap-{{ $item->id }}').classList.toggle('hidden')" |
| 220 | `Detail` | UI | Detail |
| 225 | `` | UI | <tr id="detail-lap-{{ $item->id }}" class="hidden bg-gray-50"> |

## ðŸ„ `admin\petty-cash\owner-dashboard.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Dashboard` | UI | @section('title', 'Dashboard Petty Cash') |
| 10 | `Dashboard` | UI | <h1 class="text-3xl font-bold text-gray-900 font-display">Dashboard Petty Cash (Owner)</h1> |
| 11 | `View` | UI | <p class="text-gray-600 mt-1">Helicopter View: Monitoring aliran dana operasional koperasi</p> |
| 20 | `` | UI | <a href="{{ route('admin.petty-cash.penerimaan.create') }}" |
| 34 | `` | UI | @if(session('success')) |
| 39 | `` | UI | {{ session('success') }} |
| 55 | `Total` | UI | <p class="text-white/80 text-xs mb-1">Total Saldo (Cash + TF)</p> |
| 73 | `Pending` | UI | <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold">{{ $pendingSetoran }} Pending</span> |
| 132 | `` | UI | <a href="{{ route('admin.petty-cash.penerimaan.create', ['admin_id' => $admin->id]) }}" |
| 203 | `` | UI | <a href="{{ route('admin.petty-cash.setoran-approval.detail', $s->id) }}" |

## ðŸ„ `admin\petty-cash\owner-wallet.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 45 | `` | UI | @if(session('success')) |
| 50 | `` | UI | {{ session('success') }} |
| 57 | `` | UI | @foreach($errors->all() as $error) |
| 58 | `` | UI | <li>{{ $error }}</li> |
| 69 | `Total` | UI | <p class="text-white/70 text-sm font-medium mb-1">Total Saldo Wallet Utama</p> |
| 129 | `` | PHP | $total = $det->total_cash + $det->total_tf; |
| 140 | `` | UI | <p class="text-xl font-black text-{{ $color }}-900 font-display">Rp {{ number_format($total, 0, ',', '.') }}</p> |
| 162 | `` | UI | <form action="{{ route('admin.petty-cash.owner-wallet.index') }}" method="GET" class="flex flex-wrap items-center gap-3 text-sm"> |
| 163 | `` | UI | <select name="sumber" class="rounded-lg border-gray-300 focus:ring-[#674c1d] focus:border-[#674c1d]"> |
| 171 | `` | UI | </select> |
| 172 | `` | UI | <select name="tipe" class="rounded-lg border-gray-300 focus:ring-[#674c1d] focus:border-[#674c1d]"> |
| 178 | `` | UI | </select> |
| 179 | `` | UI | <input type="date" name="tgl_dari" value="{{ request('tgl_dari') }}" class="rounded-lg border-gray-300 focus:ring-[#674c1d] focus:border-[#674c1d]"> |
| 180 | `` | UI | <button type="submit" class="p-2 bg-[#674c1d] text-white hover:bg-[#4a3514] rounded-lg transition-colors"> |
| 195 | `Balance` | UI | <th class="px-6 py-4 text-right font-bold text-gray-700 uppercase tracking-wider bg-gray-100/50">Running Balance</th> |
| 272 | `Transfer` | UI | <button onclick="viewImage('{{ asset('storage/' . $t->bukti_foto_tf) }}', 'Bukti Transfer')" |
| 286 | `` | UI | <form action="{{ route('admin.petty-cash.owner-wallet.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini? Saldo akan disesuaikan otomatis.')"> |
| 288 | `` | UI | @method('DELETE') |
| 289 | `` | UI | <button type="submit" class="p-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors" title="Hapus Transaksi"> |
| 346 | `` | UI | document.getElementById(id).classList.remove('hidden'); |
| 347 | `` | UI | document.getElementById(id).classList.add('flex'); |
| 351 | `` | UI | document.getElementById(id).classList.add('hidden'); |
| 352 | `` | UI | document.getElementById(id).classList.remove('flex'); |
| 372 | `` | UI | document.addEventListener('submit', function(e) { |
| 374 | `` | UI | const submitBtn = form.querySelector('button[type="submit"]'); |

## ðŸ„ `admin\petty-cash\partials\_owner_wallet_modals.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `` | UI | <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden animate-in fade-in zoom-in duration-200"> |
| 10 | `` | UI | <form action="{{ route('admin.petty-cash.owner-wallet.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4"> |
| 12 | `` | UI | <input type="hidden" name="tipe" value="masuk"> |
| 19 | `` | UI | <input type="number" name="nominal_cash" placeholder="0" |
| 24 | `Transfer` | UI | <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nominal Transfer</label> |
| 27 | `` | UI | <input type="number" name="nominal_tf" placeholder="0" |
| 35 | `` | UI | <textarea name="keterangan" rows="3" required placeholder="Contoh: Setoran Modal Awal Owner" |
| 41 | `Upload` | UI | <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Upload Bukti Cash</label> |
| 42 | `` | UI | <input type="file" name="bukti_foto_cash" accept="image/*" |
| 46 | `Upload` | UI | <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Upload Bukti Transfer</label> |
| 47 | `` | UI | <input type="file" name="bukti_foto_tf" accept="image/*" |
| 53 | `` | UI | <button type="button" onclick="closeModal('modalMasuk')" class="px-4 py-2 text-gray-600 font-semibold text-sm hover:text-gray-800">Batal</button> |
| 54 | `` | UI | <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-xl font-bold text-sm hover:bg-green-700 shadow-md">Simpan Modal</button> |
| 70 | `` | UI | <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden animate-in fade-in zoom-in duration-200"> |
| 77 | `` | UI | <form action="{{ route('admin.petty-cash.owner-wallet.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4" id="formDanaKeluar"> |
| 79 | `` | UI | <input type="hidden" name="tipe" value="keluar"> |
| 80 | `` | UI | <input type="hidden" id="max_other_cash" value="{{ $otherBal->total_cash }}"> |
| 81 | `` | UI | <input type="hidden" id="max_other_tf" value="{{ $otherBal->total_tf }}"> |
| 88 | `` | UI | <input type="number" name="nominal_cash" id="keluar_nominal_cash" placeholder="0" |
| 93 | `Transfer` | UI | <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nominal Transfer</label> |
| 96 | `` | UI | <input type="number" name="nominal_tf" id="keluar_nominal_tf" placeholder="0" |
| 104 | `` | UI | <textarea name="keterangan" rows="3" required placeholder="Contoh: Pembelian Inventaris Kantor / Pengeluaran Tak Terduga" |
| 110 | `Upload` | UI | <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Upload Bukti Cash</label> |
| 111 | `` | UI | <input type="file" name="bukti_foto_cash" accept="image/*" |
| 115 | `Upload` | UI | <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Upload Bukti Transfer</label> |
| 116 | `` | UI | <input type="file" name="bukti_foto_tf" accept="image/*" |
| 122 | `` | UI | <button type="button" onclick="closeModal('modalKeluar')" class="px-4 py-2 text-gray-600 font-semibold text-sm hover:text-gray-800">Batal</button> |
| 123 | `` | UI | <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-xl font-bold text-sm hover:bg-red-700 shadow-md">Simpan Pengeluaran</button> |
| 129 | `Withdrawal` | UI | <!-- Modal Tarik Saldo (Withdrawal) --> |
| 131 | `` | UI | <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden animate-in fade-in zoom-in duration-200"> |
| 138 | `` | UI | <form action="{{ route('admin.petty-cash.owner-wallet.withdraw') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4" id="formWithdraw"> |
| 143 | `` | UI | <select name="sumber" id="withdraw_sumber" required class="w-full px-4 py-2 bg-gray-50 border-gray-200 rounded-xl focus:ring-purple-500 focus:border-purple-500 transition-all font-semibold"> |
| 157 | `` | PHP | $total = $det->total_cash + $det->total_tf; |
| 159 | `` | UI | <option value="{{ $key }}">{{ $label }} (Rp {{ number_format($total, 0, ',', '.') }})</option> |
| 161 | `` | UI | </select> |
| 169 | `` | UI | <input type="number" name="nominal_cash" id="withdraw_nominal_cash" placeholder="0" |
| 174 | `Transfer` | UI | <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nominal Transfer</label> |
| 177 | `` | UI | <input type="number" name="nominal_tf" id="withdraw_nominal_tf" placeholder="0" |
| 185 | `` | UI | <textarea name="keterangan" rows="3" required placeholder="Contoh: Penarikan Keperluan Pribadi / Deviden" |
| 190 | `Upload` | UI | <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Upload Bukti (Opsional)</label> |
| 191 | `` | UI | <input type="file" name="bukti_foto" accept="image/*" |
| 196 | `` | UI | <button type="button" onclick="closeModal('modalWithdraw')" class="px-4 py-2 text-gray-600 font-semibold text-sm hover:text-gray-800">Batal</button> |
| 197 | `` | UI | <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-xl font-bold text-sm hover:bg-purple-700 shadow-md">Proses Tarik Saldo</button> |
| 203 | `Transfer` | UI | <!-- Modal Pindah Dana (Internal Transfer) --> |
| 205 | `` | UI | <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden animate-in fade-in zoom-in duration-200"> |
| 212 | `` | UI | <form action="{{ route('admin.petty-cash.owner-wallet.internal-transfer') }}" method="POST" class="p-6 space-y-4" id="formInternalTransfer"> |
| 224 | `` | UI | <select name="sumber_asal" id="it_sumber_asal" required class="w-full px-4 py-2 bg-gray-50 border-gray-200 rounded-xl focus:ring-amber-500 focus:border-amber-500 transition-all font-semibold"> |
| 228 | `` | PHP | $total = $det->total_cash + $det->total_tf; |
| 231 | `` | UI | <option value="{{ $key }}">{{ $label }} (Rp {{ number_format($total, 0, ',', '.') }})</option> |
| 233 | `` | UI | </select> |
| 241 | `` | UI | <input type="number" name="nominal_cash" id="it_nominal_cash" placeholder="0" |
| 246 | `Transfer` | UI | <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nominal Transfer</label> |
| 249 | `` | UI | <input type="number" name="nominal_tf" id="it_nominal_tf" placeholder="0" |
| 257 | `` | UI | <textarea name="keterangan" rows="2" placeholder="Contoh: TopUp Modal Operasional" |
| 262 | `` | UI | <button type="button" onclick="closeModal('modalTransferInternal')" class="px-4 py-2 text-gray-600 font-semibold text-sm hover:text-gray-800">Batal</button> |
| 263 | `` | UI | <button type="submit" class="px-6 py-2 bg-amber-600 text-white rounded-xl font-bold text-sm hover:bg-amber-700 shadow-md">Pindahkan Dana</button> |
| 277 | `` | UI | const submitBtn = form.querySelector('button[type="submit"]'); |
| 283 | `Invalid` | UI | const isInvalid = (valCash > maxCash) \|\| (valTf > maxTf); |
| 285 | `Invalid` | UI | submitBtn.disabled = isInvalid; |
| 286 | `Invalid` | UI | if (isInvalid) { |
| 287 | `` | UI | submitBtn.classList.add('opacity-50', 'cursor-not-allowed'); |
| 290 | `` | UI | submitBtn.classList.remove('opacity-50', 'cursor-not-allowed'); |
| 308 | `` | UI | const submitBtnWD  = formWD.querySelector('button[type="submit"]'); |
| 320 | `Invalid` | UI | const isInvalid = (valCash > parseFloat(bal.total_cash)) \|\| (valTf > parseFloat(bal.total_tf)); |
| 322 | `Invalid` | UI | submitBtnWD.disabled = isInvalid; |
| 323 | `Invalid` | UI | if (isInvalid) { |
| 324 | `` | UI | submitBtnWD.classList.add('opacity-50', 'cursor-not-allowed'); |
| 327 | `` | UI | submitBtnWD.classList.remove('opacity-50', 'cursor-not-allowed'); |
| 332 | `` | UI | selectSumber.addEventListener('change', validateWD); |
| 345 | `` | UI | const submitBtnIT = formIT.querySelector('button[type="submit"]'); |
| 356 | `Invalid` | UI | const isInvalid = (valCash > parseFloat(bal.total_cash)) \|\| (valTf > parseFloat(bal.total_tf)); |
| 358 | `Invalid` | UI | submitBtnIT.disabled = isInvalid; |
| 359 | `Invalid` | UI | if (isInvalid) { |
| 360 | `` | UI | submitBtnIT.classList.add('opacity-50', 'cursor-not-allowed'); |
| 362 | `` | UI | submitBtnIT.classList.remove('opacity-50', 'cursor-not-allowed'); |
| 366 | `` | UI | selectAsal.addEventListener('change', validateIT); |

## ðŸ„ `admin\petty-cash\partials\_penerimaan_table.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 16 | `` | UI | @if($item->keterangan_admin && $item->status !== 'pending') |
| 25 | `` | PHP | @php $total = (float)$item->nominal_tf + (float)$item->nominal_cash; @endphp |
| 26 | `` | UI | <p class="font-bold text-gray-900 text-base">Rp {{ number_format($total, 0, ',', '.') }}</p> |
| 32 | `Transfer` | UI | <span class="w-7 h-7 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full border border-blue-200" title="Transfer"> |
| 49 | `` | UI | @if($item->status === 'pending') |
| 50 | `Pending` | UI | <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-[11px] font-bold uppercase tracking-wider">Pending</span> |
| 51 | `` | UI | @elseif($item->status === 'approved') |

## ðŸ„ `admin\petty-cash\penerimaan.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 22 | `` | UI | @if(session('success')) |
| 23 | `` | UI | <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl">{{ session('success') }}</div> |
| 25 | `` | UI | @if(session('error')) |
| 26 | `` | UI | <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl">{{ session('error') }}</div> |
| 41 | `Transfer` | UI | <th class="px-6 py-4 text-right text-xs font-bold text-[#674c1d] uppercase">Transfer</th> |
| 43 | `Total` | UI | <th class="px-6 py-4 text-right text-xs font-bold text-[#674c1d] uppercase">Total</th> |
| 44 | `Status` | UI | <th class="px-6 py-4 text-center text-xs font-bold text-[#674c1d] uppercase">Status</th> |
| 59 | `` | UI | @if($item->keterangan_admin && $item->status !== 'pending') |
| 69 | `` | UI | @if($item->status === 'pending') |
| 70 | `Pending` | UI | <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">Pending</span> |
| 71 | `` | UI | @elseif($item->status === 'approved') |
| 78 | `` | UI | @if($item->status === 'pending') |
| 90 | `` | UI | <button type="button" |
| 91 | `` | UI | onclick="document.getElementById('approve-modal-{{ $item->id }}').classList.remove('hidden')" |
| 96 | `` | UI | <button type="button" |
| 97 | `` | UI | onclick="document.getElementById('reject-modal-{{ $item->id }}').classList.remove('hidden')" |
| 109 | `` | UI | @if($item->status === 'pending') |
| 110 | `` | UI | <div id="approve-modal-{{ $item->id }}" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50"> |
| 113 | `Total` | UI | <p class="text-sm text-gray-600 mb-4">Total: Rp {{ number_format($item->nominal_tf + $item->nominal_cash, 0, ',', '.') }}</p> |
| 114 | `` | UI | <form action="{{ route('admin.petty-cash.penerimaan.approve', $item->id) }}" method="POST"> |
| 116 | `` | UI | <textarea name="keterangan_admin" rows="3" |
| 120 | `` | UI | <button type="button" |
| 121 | `` | UI | onclick="document.getElementById('approve-modal-{{ $item->id }}').classList.add('hidden')" |
| 125 | `` | UI | <button type="submit" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-bold hover:bg-green-700"> |
| 135 | `` | UI | @if($item->status === 'pending') |
| 136 | `` | UI | <div id="reject-modal-{{ $item->id }}" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50"> |
| 139 | `` | UI | <form action="{{ route('admin.petty-cash.penerimaan.reject', $item->id) }}" method="POST"> |
| 141 | `` | UI | <textarea name="keterangan_admin" rows="3" required |
| 145 | `` | UI | <button type="button" |
| 146 | `` | UI | onclick="document.getElementById('reject-modal-{{ $item->id }}').classList.add('hidden')" |
| 150 | `` | UI | <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-bold hover:bg-red-700"> |

## ðŸ„ `admin\petty-cash\penerimaan-owner.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 14 | `` | UI | <a href="{{ route('admin.petty-cash.dashboard') }}" |
| 16 | `Dashboard` | UI | ← Dashboard |
| 33 | `Total` | UI | <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Kirim</p> |
| 34 | `` | UI | <p class="text-2xl font-bold text-gray-900" id="stat-total">{{ $stats['total'] }}</p> |
| 42 | `Pending` | UI | <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Pending</p> |
| 43 | `` | UI | <p class="text-2xl font-bold text-gray-900" id="stat-pending">{{ $stats['pending'] }}</p> |
| 51 | `Approved` | UI | <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Approved</p> |
| 52 | `` | UI | <p class="text-2xl font-bold text-gray-900" id="stat-approved">{{ $stats['approved'] }}</p> |
| 60 | `Rejected` | UI | <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Rejected</p> |
| 61 | `` | UI | <p class="text-2xl font-bold text-gray-900" id="stat-rejected">{{ $stats['rejected'] }}</p> |
| 69 | `Total` | UI | <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Nominal</p> |
| 77 | `` | UI | <form id="filter-form" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end"> |
| 80 | `` | UI | <select name="admin_id" class="w-full border-gray-200 rounded-xl text-sm focus:ring-[#674c1d]"> |
| 85 | `` | UI | </select> |
| 88 | `Status` | UI | <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Status</label> |
| 89 | `` | UI | <select name="status" class="w-full border-gray-200 rounded-xl text-sm focus:ring-[#674c1d]"> |
| 90 | `Status` | UI | <option value="">Semua Status</option> |
| 91 | `Pending` | UI | <option value="pending">Pending</option> |
| 92 | `Approved` | UI | <option value="approved">Approved (ACC)</option> |
| 93 | `Rejected` | UI | <option value="rejected">Rejected</option> |
| 94 | `` | UI | </select> |
| 99 | `` | UI | <input type="date" name="tgl_dari" class="w-full border-gray-200 rounded-xl text-xs focus:ring-[#674c1d]"> |
| 100 | `` | UI | <input type="date" name="tgl_sampai" class="w-full border-gray-200 rounded-xl text-xs focus:ring-[#674c1d]"> |
| 104 | `` | UI | <button type="button" onclick="resetFilters()" class="w-full py-2.5 text-sm font-bold text-gray-600 bg-gray-50 hover:bg-gray-100 rounded-xl transition-colors border border-gray-200"> |
| 106 | `Filter` | UI | Reset Filter |
| 115 | `` | UI | <div id="loading-overlay" class="absolute inset-0 bg-white/60 backdrop-blur-[1px] z-10 hidden flex items-center justify-center transition-all"> |
| 129 | `Status` | UI | <th class="px-6 py-4 text-center text-xs font-bold text-[#674c1d] uppercase">Status</th> |
| 173 | `` | UI | <form action="{{ route('admin.petty-cash.penerimaan.store') }}" method="POST" enctype="multipart/form-data" class="px-10 py-10 space-y-8"> |
| 185 | `` | UI | <input type="text" readonly |
| 188 | `` | UI | <input type="hidden" name="sumber" id="select_sumber" value="other" |
| 200 | `` | UI | <select name="admin_id" required class="block w-full pl-12 pr-4 py-4 text-base font-semibold border-gray-300 rounded-[1.25rem] focus:ring-4 focus:ring-[#674c1d]/10 focus:border-[#674c1d] transition-all bg-gray-50/50"> |
| 204 | `` | UI | {{ $admin->nama }} ({{ $admin->email }}) |
| 207 | `` | UI | </select> |
| 214 | `Transfer` | UI | <span>Saldo Transfer (Bank)</span> |
| 219 | `` | UI | <input type="number" name="nominal_tf" id="input_tf" oninput="calculateTotal()" |
| 232 | `` | UI | <input type="number" name="nominal_cash" id="input_cash" oninput="calculateTotal()" |
| 242 | `Total` | UI | <p class="text-[0.6rem] font-black text-[#674c1d] uppercase tracking-[0.2em]">Total yang Dikirimkan</p> |
| 252 | `Upload` | UI | <label class="block text-[0.65rem] font-black text-gray-500 uppercase tracking-widest mb-2">Upload Bukti Transfer</label> |
| 254 | `` | UI | <i class="fas fa-cloud-upload-alt text-gray-400 group-hover:text-[#674c1d] mb-1"></i> |
| 256 | `` | UI | <input type="file" name="bukti_tf" accept="image/*" class="hidden"> |
| 260 | `Upload` | UI | <label class="block text-[0.65rem] font-black text-gray-500 uppercase tracking-widest mb-2">Upload Foto Cash</label> |
| 264 | `` | UI | <input type="file" name="foto_cash" accept="image/*" class="hidden"> |
| 272 | `` | UI | <textarea name="keterangan" rows="2" placeholder="Tambahkan catatan jika diperlukan..." |
| 277 | `` | UI | <button type="submit" class="flex-[3] px-8 py-4.5 bg-[#674c1d] text-white rounded-[1.25rem] text-sm font-black shadow-2xl shadow-[#674c1d]/30 hover:bg-[#4a3514] transition-all transform hover:scale-[1.02] active:scale-[0.98]"> |
| 281 | `` | UI | <button type="button" onclick="closeKirimModal()" class="flex-[1] px-4 py-4.5 bg-gray-100 text-gray-700 rounded-[1.25rem] text-xs font-bold hover:bg-gray-200 transition-all uppercase tracking-widest"> |
| 292 | `` | UI | const filterForm = document.getElementById('filter-form'); |
| 296 | `` | UI | const loadingOverlay = document.getElementById('loading-overlay'); |
| 299 | `` | UI | filterForm.querySelectorAll('select, input').forEach(input => { |
| 300 | `` | UI | input.addEventListener('change', fetchFilteredData); |
| 307 | `` | UI | const url = `{{ route('admin.petty-cash.penerimaan.create') }}?${params}`; |
| 320 | `Error` | UI | .catch(error => console.error('Error:', error)) |
| 325 | `` | UI | document.getElementById('stat-total').innerText = stats.total; |
| 326 | `` | UI | document.getElementById('stat-pending').innerText = stats.pending; |
| 327 | `` | UI | document.getElementById('stat-approved').innerText = stats.approved; |
| 328 | `` | UI | document.getElementById('stat-rejected').innerText = stats.rejected; |
| 356 | `` | UI | filterForm.reset(); |
| 360 | `` | UI | function showLoading(show) { |
| 361 | `` | UI | if (show) { |
| 362 | `` | UI | loadingOverlay.classList.remove('hidden'); |
| 363 | `` | UI | loadingOverlay.classList.add('flex'); |
| 365 | `` | UI | loadingOverlay.classList.add('hidden'); |
| 366 | `` | UI | loadingOverlay.classList.remove('flex'); |
| 373 | `` | UI | modal.classList.remove('hidden'); |
| 380 | `` | UI | modal.classList.add('hidden'); |
| 387 | `` | UI | const total = tf + cash; |
| 388 | `` | UI | document.getElementById('display_total').innerText = 'Rp ' + total.toLocaleString('id-ID'); |

## ðŸ„ `admin\petty-cash\setoran-approval.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 16 | `Pending` | UI | @foreach(['pending' => 'Pending', 'approved_owner' => 'Disetujui', 'rejected' => 'Ditolak'] as $val => $label) |
| 17 | `` | UI | <a href="{{ request()->fullUrlWithQuery(['status' => $val]) }}" |
| 19 | `` | UI | {{ request('status', 'pending') === $val |
| 28 | `` | UI | @if(session('success')) |
| 31 | `` | UI | <span class="text-sm font-bold">{{ session('success') }}</span> |
| 34 | `` | UI | @if(session('error')) |
| 37 | `` | UI | <span class="text-sm font-bold">{{ session('error') }}</span> |
| 43 | `` | UI | <div class="group bg-white rounded-[2.5rem] shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden hover:shadow-2xl hover:shadow-gray-300/50 transition-all duration-500 |
| 44 | `` | UI | {{ $s->status === 'pending' ? 'ring-2 ring-amber-400 ring-offset-4' : '' }}"> |
| 56 | `` | UI | @if($s->status === 'pending') |
| 65 | `` | UI | @if($s->status === 'pending') |
| 68 | `Pending` | UI | Pending |
| 70 | `` | UI | @elseif($s->status === 'approved_owner') |
| 74 | `Approved` | UI | Approved |
| 83 | `Rejected` | UI | Rejected |
| 91 | `Total` | UI | <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Setor</p> |
| 117 | `` | UI | @if($s->keterangan_admin \|\| ($s->keterangan_owner && $s->status !== 'pending')) |
| 125 | `` | UI | @if($s->keterangan_owner && $s->status !== 'pending') |
| 137 | `` | UI | <button onclick="document.getElementById('detail-{{ $s->id }}').classList.toggle('hidden')" |
| 142 | `Detail` | UI | Lihat Detail {{ count($s->data_potongan) }} Nasabah |
| 144 | `` | UI | <div id="detail-{{ $s->id }}" class="hidden mt-3 overflow-x-auto"> |
| 178 | `` | UI | @if($s->status === 'pending') |
| 179 | `Pending` | UI | <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-[10px] font-bold uppercase tracking-wider">Pending</span> |
| 180 | `` | UI | @elseif($s->status === 'approved_owner') |
| 181 | `Approved` | UI | <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-[10px] font-bold uppercase tracking-wider">Approved</span> |
| 183 | `Rejected` | UI | <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-[10px] font-bold uppercase tracking-wider">Rejected</span> |
| 196 | `` | UI | @if($s->status === 'pending') |
| 198 | `` | UI | <button type="button" |
| 199 | `` | UI | onclick="document.getElementById('approve-modal-{{ $s->id }}').classList.remove('hidden')" |
| 202 | `` | UI | APPROVE |
| 206 | `` | UI | <button type="button" |
| 207 | `` | UI | onclick="document.getElementById('reject-modal-{{ $s->id }}').classList.remove('hidden')" |
| 215 | `` | UI | @if($s->status !== 'pending') |
| 216 | `` | UI | <a href="{{ route('admin.petty-cash.setoran-approval.detail', $s->id) }}" |
| 219 | `Detail` | UI | Detail Slip |
| 228 | `` | UI | @if($s->status === 'pending') |
| 229 | `` | UI | <div id="approve-modal-{{ $s->id }}" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50"> |
| 243 | `Total` | UI | <span class="text-[10px] font-bold text-gray-400 uppercase">Total Setoran</span> |
| 252 | `` | UI | <form action="{{ route('admin.petty-cash.setoran-approval.approve', $s->id) }}" method="POST"> |
| 255 | `` | UI | <textarea name="keterangan_owner" rows="3" |
| 260 | `` | UI | <button type="button" |
| 261 | `` | UI | onclick="document.getElementById('approve-modal-{{ $s->id }}').classList.add('hidden')" |
| 263 | `` | UI | <button type="submit" class="flex-[2] px-6 py-3.5 bg-[#674c1d] text-white rounded-2xl text-sm font-black shadow-xl shadow-[#674c1d]/20 hover:bg-[#4a3514] transition-all">SETUJUI</button> |
| 271 | `` | UI | @if($s->status === 'pending') |
| 272 | `` | UI | <div id="reject-modal-{{ $s->id }}" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50"> |
| 284 | `` | UI | <form action="{{ route('admin.petty-cash.setoran-approval.reject', $s->id) }}" method="POST"> |
| 287 | `` | UI | <textarea name="keterangan_owner" rows="3" required |
| 292 | `` | UI | <button type="button" |
| 293 | `` | UI | onclick="document.getElementById('reject-modal-{{ $s->id }}').classList.add('hidden')" |
| 295 | `` | UI | <button type="submit" class="flex-[2] px-6 py-3.5 bg-red-600 text-white rounded-2xl text-sm font-black shadow-xl shadow-red-200 hover:bg-red-700 transition-all">TOLAK</button> |

## ðŸ„ `admin\petty-cash\setoran-approval-detail.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Detail` | UI | @section('title', 'Detail Setoran Kantor') |
| 14 | `Detail` | UI | <h1 class="text-3xl font-bold text-gray-900 font-display">Detail Setoran</h1> |
| 19 | `` | UI | @if(session('success')) |
| 20 | `` | UI | <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl">{{ session('success') }}</div> |
| 31 | `Total` | UI | <p class="text-xs text-gray-500 uppercase font-medium">Total Setoran</p> |
| 39 | `Status` | UI | <p class="text-xs text-gray-500 uppercase font-medium">Status</p> |
| 41 | `` | UI | @if($setoran->status === 'pending') |
| 42 | `Pending` | UI | <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm font-semibold">Pending</span> |
| 43 | `` | UI | @elseif($setoran->status === 'approved_owner') |
| 126 | `` | UI | <td colspan="2" class="px-5 py-3 font-bold text-gray-900 text-sm">TOTAL</td> |
| 136 | `` | UI | @if($setoran->status === 'pending') |
| 138 | `` | UI | <form action="{{ route('admin.petty-cash.setoran-approval.approve', $setoran->id) }}" method="POST" class="flex-1"> |
| 140 | `` | UI | <button type="submit" |
| 141 | `` | UI | onclick="return confirm(`Setujui setoran Rp {{ number_format((float) $setoran->total_setor, 0, ',', '.') }}?`)" |
| 146 | `` | UI | APPROVE Setoran |
| 150 | `` | UI | <button type="button" onclick="document.getElementById('reject-modal').classList.remove('hidden')" |
| 157 | `` | UI | <div id="reject-modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50"> |
| 160 | `` | UI | <form action="{{ route('admin.petty-cash.setoran-approval.reject', $setoran->id) }}" method="POST"> |
| 162 | `` | UI | <textarea name="keterangan_owner" rows="3" required |
| 166 | `` | UI | <button type="button" onclick="document.getElementById('reject-modal').classList.add('hidden')" |
| 168 | `` | UI | <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-bold">Tolak</button> |

## ðŸ„ `admin\petty-cash\setoran-kantor.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 25 | `` | UI | @if(session('success')) |
| 26 | `` | UI | <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl">{{ session('success') }}</div> |
| 28 | `` | UI | @if(session('error')) |
| 29 | `` | UI | <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl">{{ session('error') }}</div> |
| 38 | `Total` | UI | <p class="text-xs text-green-700 font-medium uppercase">Total Cash Hari Ini</p> |
| 43 | `Total` | UI | <p class="text-xs text-blue-700 font-medium uppercase">Total Transfer</p> |
| 45 | `` | UI | <p class="text-xs text-blue-600 mt-1">Via transfer bank</p> |
| 51 | `Pending` | UI | <button type="button" onclick="switchTab('transaksi')" id="tab-transaksi" class="px-4 py-2 font-semibold text-[#674c1d] border-b-2 border-[#674c1d]">Dari Transaksi Pending</button> |
| 52 | `` | UI | <button type="button" onclick="switchTab('manual')" id="tab-manual" class="px-4 py-2 font-semibold text-gray-500 border-b-2 border-transparent hover:text-[#674c1d]">Setor Manual</button> |
| 55 | `` | UI | <form action="{{ route('admin.petty-cash.setoran-kantor.store') }}" method="POST" enctype="multipart/form-data" id="form-setor"> |
| 57 | `` | UI | <input type="hidden" name="tipe_setoran" id="tipe_setoran" value="transaksi"> |
| 68 | `Total` | UI | <span class="font-bold text-[#674c1d]" id="total-checked">Total: Rp {{ number_format($transaksiPending->sum('nominal'), 0, ',', '.') }}</span> |
| 80 | `` | UI | <th class="px-4 py-3 text-center"><input type="checkbox" id="check-all" checked class="rounded text-[#674c1d]"></th> |
| 90 | `` | UI | <input type="checkbox" name="transaksi_ids[]" value="{{ $t->id }}" data-nominal="{{ $t->nominal }}" checked class="transaksi-check rounded text-[#674c1d]"> |
| 117 | `` | UI | <input type="number" name="manual_cash" value="0" class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#674c1d]" min="0"> |
| 120 | `Transfer` | UI | <label class="block text-sm font-semibold text-gray-700 mb-2">Setor Transfer (Rp)</label> |
| 121 | `` | UI | <input type="number" name="manual_tf" value="0" class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#674c1d]" min="0"> |
| 134 | `` | UI | <input type="file" name="foto_setoran" accept="image/*" class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#674c1d]"/> |
| 140 | `` | UI | <input type="checkbox" name="sudah_setor_fisik" value="1" class="mt-0.5 text-[#674c1d] rounded" required> |
| 151 | `` | UI | <textarea name="keterangan_admin" rows="2" class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#674c1d]"></textarea> |
| 154 | `` | UI | <button type="submit" class="w-full py-3 bg-[#674c1d] text-white rounded-xl font-bold text-sm hover:bg-[#4a3514] transition-colors"> |
| 173 | `` | UI | @if($s->status === 'pending') |
| 175 | `` | UI | @elseif($s->status === 'approved_owner') |
| 209 | `` | UI | modeTransaksi.classList.remove('hidden'); |
| 210 | `` | UI | modeManual.classList.add('hidden'); |
| 214 | `` | UI | modeManual.classList.remove('hidden'); |
| 215 | `` | UI | modeTransaksi.classList.add('hidden'); |
| 222 | `` | UI | const totalChecked = document.getElementById('total-checked'); |
| 226 | `` | UI | let total = 0; |
| 230 | `` | UI | total += parseFloat(check.dataset.nominal) \|\| 0; |
| 236 | `Total` | UI | totalChecked.textContent = 'Total: Rp ' + new Intl.NumberFormat('id-ID').format(total); |
| 247 | `` | UI | checkAll.addEventListener('change', function() { |
| 256 | `` | UI | check.addEventListener('change', calculateTotal); |
| 261 | `` | UI | formSetor.addEventListener('submit', function(e) { |
| 262 | `` | UI | const fileInput = this.querySelector('input[name="foto_setoran"]'); |

## ðŸ„ `admin\pinjaman\angsuran.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 20 | `Filter` | UI | <!-- Filter Section --> |
| 22 | `` | UI | <form method="GET" action="{{ route('admin.pinjaman.angsuran') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4"> |
| 25 | `` | UI | <select name="jenis" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none"> |
| 28 | `` | UI | </select> |
| 31 | `Status` | UI | <label class="block text-xs font-medium text-gray-600 mb-1">Status</label> |
| 32 | `` | UI | <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none"> |
| 33 | `Status` | UI | <option value="">Semua Status</option> |
| 34 | `` | UI | <option value="belum" {{ request('status') == 'belum' ? 'selected' : '' }}>Belum Lunas</option> |
| 35 | `` | UI | <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option> |
| 36 | `` | UI | <option value="telat" {{ request('status') == 'telat' ? 'selected' : '' }}>Telat</option> |
| 37 | `` | UI | </select> |
| 41 | `` | UI | <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama atau email..." |
| 47 | `` | UI | <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" |
| 49 | `` | UI | <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" |
| 54 | `` | UI | <button type="submit" class="px-6 py-2 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-md font-medium"> |
| 55 | `Filter` | UI | Filter |
| 61 | `Detail` | UI | <!-- Table: per pinjaman (No, Id, Nasabah, Detail Angsuran, Status Pembayaran, Aksi) --> |
| 70 | `Detail` | UI | <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase min-w-[280px]">Detail Angsuran</th> |
| 71 | `Status` | UI | <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase min-w-[200px]">Status Pembayaran</th> |
| 88 | `` | UI | <p class="text-xs text-gray-500">{{ $pinjaman->nasabah->user->email ?? '-' }}</p> |
| 119 | `Status` | UI | <th class="px-3 py-2 text-center font-semibold text-[#674c1d]">Status</th> |
| 147 | `` | UI | <a href="{{ route('admin.pinjaman.detail-angsuran', $t->id) }}?jenis={{ $jenis }}" |
| 149 | `Detail` | UI | Detail #{{ $t->no_urut }} |

## ðŸ„ `admin\pinjaman\create-pinjaman.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 24 | `` | UI | <form action="{{ route('admin.pinjaman.store-pinjaman') }}" method="POST" class="space-y-6"> |
| 30 | `` | UI | <select name="id_anggota" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none"> |
| 34 | `` | UI | {{ $item->user->nama ?? 'N/A' }} - {{ $item->user->email ?? 'N/A' }} |
| 37 | `` | UI | </select> |
| 38 | `` | UI | @error('id_anggota') |
| 48 | `` | UI | <input type="number" name="nominal" id="nominal" value="{{ old('nominal') }}" required min="100000" step="10000" |
| 52 | `` | UI | @error('nominal') |
| 60 | `` | UI | <select name="durasi" id="durasi" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none"> |
| 65 | `` | UI | </select> |
| 66 | `` | UI | @error('durasi') |
| 74 | `` | UI | <input type="date" name="tgl_pinjam" value="{{ old('tgl_pinjam', date('Y-m-d')) }}" required |
| 76 | `` | UI | @error('tgl_pinjam') |
| 94 | `Total` | UI | <span class="text-gray-600">Total yang harus dibayar:</span> |
| 102 | `` | UI | <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl hover:from-[#4a3514] hover:to-[#674c1d] transition-all font-medium shadow-md"> |
| 135 | `` | UI | const total = nominal + bungaRp; |
| 139 | `` | UI | document.getElementById('infoTotal').textContent = 'Rp ' + total.toLocaleString('id-ID'); |
| 141 | `` | UI | infoSection.classList.remove('hidden'); |
| 143 | `` | UI | infoSection.classList.add('hidden'); |
| 148 | `` | UI | durasiSelect.addEventListener('change', updateInfo); |

## ðŸ„ `admin\pinjaman\detail-angsuran.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Detail` | UI | @section('title', 'Detail Angsuran') |
| 10 | `Detail` | UI | <h1 class="text-3xl font-bold text-gray-900 font-display">Detail Angsuran</h1> |
| 16 | `Download` | UI | Download PDF Struk |
| 36 | `Email` | UI | <p class="text-sm text-gray-600">Email</p> |
| 37 | `` | UI | <p class="font-semibold text-gray-900">{{ $angsuran->pinjaman->nasabah->user->email ?? 'N/A' }}</p> |
| 114 | `Total` | UI | <p class="text-sm text-gray-600">Total Tagihan + Denda</p> |
| 131 | `` | PHP | $status = $statusConfig[$angsuran->status_bayar] ?? $statusConfig['belum']; |
| 133 | `` | UI | <span class="inline-block mt-2 px-4 py-2 {{ $status['bg'] }} {{ $status['text'] }} rounded-full text-sm font-semibold"> |
| 134 | `` | UI | {{ $status['label'] }} |
| 140 | `Transfer` | UI | <!-- Bukti Transfer (jika angsuran sudah dibayar) --> |
| 143 | `Transfer` | UI | <h2 class="text-lg font-bold text-primary font-display mb-4 pb-4 border-b border-gray-200">Bukti Transfer</h2> |
| 151 | `` | PHP | $fileName = $filePath ? basename($filePath) : 'bukti-transfer'; |
| 156 | `` | UI | <img src="{{ $imageUrl }}" alt="Bukti Transfer" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200"> |

## ðŸ„ `admin\pinjaman\detail-pembayaran.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Detail` | UI | @section('title', 'Detail Pembayaran Pinjaman') |
| 10 | `Detail` | UI | <h1 class="text-3xl font-bold text-gray-900 font-display">Detail Pembayaran Pinjaman</h1> |
| 16 | `Download` | UI | Download PDF Struk |
| 34 | `` | UI | @foreach($errors->all() as $error) |
| 35 | `` | UI | <li>{{ $error }}</li> |
| 41 | `` | UI | @if(session('error')) |
| 45 | `` | UI | {{ session('error') }} |
| 58 | `Email` | UI | <p class="text-sm text-gray-600">Email</p> |
| 59 | `` | UI | <p class="font-semibold text-gray-900">{{ $pengajuan->nasabah->user->email ?? 'N/A' }}</p> |
| 95 | `` | UI | <p class="text-xs text-gray-500 mt-1">Wajib upload bukti foto pertemuan setelah menyetujui.</p> |
| 98 | `Transfer` | UI | Transfer |
| 111 | `Pending` | UI | '1' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Pending'], |
| 116 | `` | PHP | $status = $statusConfig[$pengajuan->status] ?? $statusConfig['1']; |
| 118 | `` | UI | <span class="inline-block mt-2 px-4 py-2 {{ $status['bg'] }} {{ $status['text'] }} rounded-full text-sm font-semibold"> |
| 119 | `` | UI | {{ $status['label'] }} |
| 178 | `Total` | UI | <span class="font-semibold text-gray-900">Total Tagihan + Denda:</span> |
| 185 | `Transfer` | UI | <!-- Bukti Foto (Transfer / Serah Terima) --> |
| 248 | `Actions` | UI | <!-- Sidebar Actions (sticky) --> |
| 251 | `` | UI | @if($pengajuan->status === '1' && $isTunaiPembayaran) |
| 252 | `` | UI | <!-- Tunai/Janji Temu: Langsung upload bukti foto (tanpa setujui dulu) --> |
| 255 | `Upload` | UI | <p class="text-sm text-gray-600 mb-4">Upload bukti foto bahwa admin dan nasabah telah bertemu serta pembayaran tunai diterima. Setelah upload, pembayaran akan otomatis dikonfirmasi.</p> |
| 256 | `` | UI | <form method="POST" action="{{ route('admin.pinjaman.upload-serah-terima', $pengajuan->id) }}" enctype="multipart/form-data"> |
| 260 | `` | UI | <input type="file" name="foto_serah_terima" accept="image/*" required |
| 262 | `` | UI | <p class="text-xs text-gray-500 mt-1">Wajib upload. Format: JPG, PNG (Max 5MB)</p> |
| 266 | `` | UI | <textarea name="keterangan" rows="3" |
| 270 | `` | UI | <button type="submit" onclick="return confirm('Upload foto akan mengkonfirmasi pembayaran dan memperbarui angsuran. Lanjutkan?')" |
| 272 | `Upload` | UI | ✓ Upload Bukti & Konfirmasi Pembayaran |
| 276 | `` | UI | <button type="button" onclick="showRejectModal()" class="w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors"> |
| 284 | `` | UI | <form method="POST" action="{{ route('admin.pinjaman.reject-pembayaran', $pengajuan->id) }}"> |
| 288 | `` | UI | <textarea name="keterangan_admin" rows="4" required |
| 293 | `` | UI | <button type="button" onclick="hideRejectModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Batal</button> |
| 294 | `` | UI | <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">Tolak</button> |
| 299 | `` | UI | @elseif($pengajuan->status === '1') |
| 300 | `Approve` | UI | <!-- Transfer: Approve/Reject dulu --> |
| 303 | `` | UI | <form method="POST" action="{{ route('admin.pinjaman.approve-pembayaran', $pengajuan->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui pembayaran ini?')" class="mb-3"> |
| 307 | `` | UI | <select name="metode_penerimaan" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none"> |
| 309 | `Transfer` | UI | <option value="rek_admin">Rekening Admin (Petty Cash Transfer)</option> |
| 311 | `` | UI | </select> |
| 315 | `` | UI | <textarea name="keterangan_admin" rows="3" |
| 319 | `` | UI | <button type="submit" class="w-full px-4 py-3 bg-linear-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all font-medium shadow-md"> |
| 330 | `` | UI | <form method="POST" action="{{ route('admin.pinjaman.reject-pembayaran', $pengajuan->id) }}"> |
| 334 | `` | UI | <textarea name="keterangan_admin" rows="4" required |
| 339 | `` | UI | <button type="button" onclick="hideRejectModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Batal</button> |
| 340 | `` | UI | <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">Tolak</button> |
| 347 | `` | UI | @if($pengajuan->status === '3' && $pengajuan->rekening_tujuan) |
| 348 | `Transfer` | UI | <!-- Konfirmasi Pembayaran Transfer --> |
| 351 | `` | UI | <form method="POST" action="{{ route('admin.pinjaman.konfirmasi-pembayaran', $pengajuan->id) }}" enctype="multipart/form-data"> |
| 354 | `Upload` | UI | <label class="block text-sm font-medium text-gray-700 mb-2">Upload Bukti Transfer (Opsional)</label> |
| 355 | `` | UI | <input type="file" name="bukti_transfer" accept="image/*" |
| 360 | `` | UI | <textarea name="keterangan" rows="3" |
| 364 | `` | UI | <button type="submit" onclick="return confirm('Konfirmasi pembayaran akan memperbarui angsuran. Lanjutkan?')" |
| 372 | `` | UI | @if($pengajuan->status === '3' && $isTunaiPembayaran) |
| 373 | `` | UI | <!-- Tunai sudah disetujui tapi belum upload: tampilkan form upload (fallback) --> |
| 376 | `Upload` | UI | <p class="text-sm text-gray-600 mb-4">Upload bukti foto bahwa admin dan nasabah telah bertemu serta pembayaran tunai diterima.</p> |
| 377 | `` | UI | <form method="POST" action="{{ route('admin.pinjaman.upload-serah-terima', $pengajuan->id) }}" enctype="multipart/form-data"> |
| 381 | `` | UI | <input type="file" name="foto_serah_terima" accept="image/*" required |
| 383 | `` | UI | <p class="text-xs text-gray-500 mt-1">Wajib upload. Format: JPG, PNG (Max 5MB)</p> |
| 387 | `` | UI | <textarea name="keterangan" rows="3" |
| 391 | `` | UI | <button type="submit" onclick="return confirm('Upload foto akan mengkonfirmasi pembayaran dan memperbarui angsuran. Lanjutkan?')" |
| 393 | `Upload` | UI | ✓ Upload Bukti & Konfirmasi Pembayaran |
| 399 | `` | UI | @if($pengajuan->status === '4') |
| 418 | `` | UI | document.getElementById('rejectModal').classList.remove('hidden'); |
| 422 | `` | UI | document.getElementById('rejectModal').classList.add('hidden'); |

## ðŸ„ `admin\pinjaman\detail-pengajuan.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Detail` | UI | @section('title', 'Detail Pengajuan Pinjaman') |
| 10 | `Detail` | UI | <h1 class="text-3xl font-bold text-gray-900 font-display">Detail Pengajuan Pinjaman</h1> |
| 34 | `Email` | UI | <p class="text-sm text-gray-600">Email</p> |
| 35 | `` | UI | <p class="font-semibold text-gray-900">{{ $pengajuan->nasabah->user->email ?? 'N/A' }}</p> |
| 41 | `` | UI | @if(($pengajuan->jenis_pencairan ?? '') === 'transfer' && $pengajuan->nasabah->dataRek) |
| 114 | `` | UI | class="inline-block mt-2 px-4 py-2 {{ ($pengajuan->jenis_pencairan ?? 'transfer') === 'transfer' ? 'bg-indigo-100 text-indigo-700' : 'bg-amber-100 text-amber-700' }} rounded-full text-sm font-semibold"> |
| 115 | `Transfer` | UI | {{ ($pengajuan->jenis_pencairan ?? 'transfer') === 'transfer' ? 'Transfer' : 'Tunai (Janji Temu)' }} |
| 119 | `Status` | UI | <p class="text-sm text-gray-600">Status</p> |
| 120 | `` | UI | @if($pengajuan->status === '1') |
| 123 | `Pending` | UI | Pending |
| 125 | `` | UI | @elseif($pengajuan->status === '2') |
| 130 | `` | UI | @elseif($pengajuan->status === '3') |
| 135 | `` | UI | @elseif($pengajuan->status === '4') |
| 146 | `Actions` | UI | <!-- Sidebar Actions (sticky) --> |
| 148 | `Action` | UI | <!-- Action Buttons --> |
| 152 | `` | UI | @if($pengajuan->status === '1') |
| 153 | `Approve` | UI | <!-- Status Pending - Show Approve/Reject --> |
| 162 | `Total` | UI | <strong>Bunga Total:</strong> Rp |
| 170 | `Total` | UI | <strong>Total Tagihan:</strong> Rp |
| 186 | `` | UI | <form method="POST" action="{{ route('admin.pinjaman.approve-pengajuan', $pengajuan->id) }}" |
| 187 | `` | UI | onsubmit="return confirm('Setujui pengajuan ini? Status akan berubah menjadi DISETUJUI. Anda masih perlu CAIRKAN dana setelah ini.')"> |
| 194 | `` | UI | <button type="submit" disabled |
| 201 | `` | UI | <textarea name="keterangan_admin" rows="3" |
| 205 | `` | UI | <button type="submit" |
| 217 | `` | UI | @elseif($pengajuan->status === '3') |
| 218 | `Show` | UI | <!-- Status Disetujui - Show Cairkan --> |
| 230 | `Total` | UI | <strong>Total Bunga:</strong> Rp |
| 244 | `` | UI | @elseif($pengajuan->status === '2') |
| 245 | `Status` | UI | <!-- Status Ditolak --> |
| 255 | `` | UI | @elseif($pengajuan->status === '4') |
| 256 | `Status` | UI | <!-- Status Terlaksana --> |
| 266 | `` | UI | <a href="{{ route('admin.pinjaman.detail-pinjaman', $pengajuan->pinjaman->id) }}" |
| 268 | `Detail` | UI | Lihat Detail Pinjaman → |
| 275 | `Reject` | UI | <!-- Reject Modal --> |
| 280 | `` | UI | <form method="POST" action="{{ route('admin.pinjaman.reject-pengajuan', $pengajuan->id) }}"> |
| 284 | `` | UI | <textarea name="keterangan_admin" rows="4" required |
| 289 | `` | UI | <button type="button" onclick="hideRejectModal()" |
| 293 | `` | UI | <button type="submit" |
| 317 | `` | UI | action="{{ route('admin.pinjaman.cairkan-pinjaman', $pengajuan->id) }}" |
| 322 | `` | UI | <input type="date" name="tgl_cair" required value="{{ date('Y-m-d') }}" |
| 330 | `Transfer` | UI | <!-- Petty Cash Info Card (Only Transfer) --> |
| 336 | `Transfer` | UI | Saldo Petty Cash (Transfer)</p> |
| 338 | `` | UI | {{ number_format($adminSaldo->transfer, 0, ',', '.') }} |
| 343 | `` | UI | class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $adminSaldo->transfer >= $pengajuan->nominal ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}"> |
| 345 | `` | UI | class="w-2 h-2 rounded-full {{ $adminSaldo->transfer >= $pengajuan->nominal ? 'bg-green-500' : 'bg-red-500' }} mr-1.5"></span> |
| 346 | `` | UI | {{ $adminSaldo->transfer >= $pengajuan->nominal ? 'Saldo Cukup' : 'Saldo Kurang' }} |
| 356 | `Transfer` | UI | <span class="text-sm font-bold text-gray-900">Petty Cash (Transfer)</span> |
| 358 | `` | UI | <input type="hidden" name="metode_pencairan" value="petty_tf"> |
| 364 | `` | UI | <input type="file" name="bukti_transfer" accept="image/*" required |
| 367 | `` | UI | <p class="text-xs text-gray-500 mt-1 italic">Wajib upload bukti pencairan (JPG, PNG, Max |
| 384 | `` | UI | <button type="button" onclick="hideCairkanModal()" |
| 388 | `` | UI | <button id="btnSubmitCairkan" type="submit" |
| 402 | `` | UI | const saldoTransfer = {{ $adminSaldo->transfer }}; |
| 405 | `` | UI | document.getElementById('rejectModal').classList.remove('hidden'); |
| 409 | `` | UI | document.getElementById('rejectModal').classList.add('hidden'); |
| 413 | `` | UI | document.getElementById('cairkanModal').classList.remove('hidden'); |
| 418 | `` | UI | document.getElementById('cairkanModal').classList.add('hidden'); |
| 422 | `` | UI | const warning = document.getElementById('warningBalance'); |
| 428 | `` | UI | warning.classList.remove('hidden'); |
| 432 | `` | UI | warning.classList.add('hidden'); |

## ðŸ„ `admin\pinjaman\detail-pinjaman.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Detail` | UI | @section('title', 'Detail Pinjaman') |
| 10 | `Detail` | UI | <h1 class="text-3xl font-bold text-gray-900 font-display">Detail Pinjaman</h1> |
| 16 | `Download` | UI | Download PDF Struk |
| 38 | `Email` | UI | <p class="text-sm text-gray-600">Email</p> |
| 39 | `` | UI | <p class="font-semibold text-gray-900">{{ $pinjaman->nasabah->user->email ?? 'N/A' }}</p> |
| 89 | `` | UI | class="inline-block mt-2 px-4 py-2 {{ $pinjaman->status === 'telaksana' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }} rounded-full text-sm font-semibold"> |
| 90 | `` | UI | {{ ucfirst($pinjaman->status) }} |
| 117 | `Status` | UI | <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Status</th> |
| 141 | `` | PHP | $status = $statusConfig[$item->status_bayar] ?? $statusConfig['belum']; |
| 144 | `` | UI | class="px-3 py-1 {{ $status['bg'] }} {{ $status['text'] }} rounded-full text-xs font-semibold"> |
| 176 | `Total` | UI | <p class="text-sm text-gray-600">Total Tagihan</p> |
| 181 | `Total` | UI | <p class="text-sm text-gray-600">Total Terbayar</p> |
| 211 | `` | UI | <img src="{{ $imageUrl }}" alt="Bukti Pelunasan" class="w-full h-auto hover:scale-105 transition-transform duration-200"> |
| 213 | `` | UI | <a href="{{ $imageUrl }}" download class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium"> |
| 237 | `` | UI | <form method="POST" action="{{ route('admin.pinjaman.pelunasan-dipercepat', $pinjaman->id) }}" |
| 238 | `` | UI | onsubmit="return confirm('Apakah Anda yakin ingin melakukan pelunasan dipercepat?')" |
| 272 | `Total` | UI | <span class="text-sm">Total Denda:</span> |
| 278 | `Total` | UI | <span class="font-semibold">Total Pembayaran:</span> |
| 286 | `` | UI | <input type="file" name="bukti_foto" accept="image/*" required |
| 293 | `` | UI | <input type="number" name="potongan" step="0.01" min="0" max="{{ $totalBayar }}" |
| 300 | `` | UI | <textarea name="keterangan" rows="3" |
| 306 | `` | UI | <button type="button" onclick="hidePelunasanModal()" |
| 310 | `` | UI | <button type="submit" |
| 328 | `` | UI | document.getElementById('pelunasanModal').classList.remove('hidden'); |
| 332 | `` | UI | document.getElementById('pelunasanModal').classList.add('hidden'); |

## ðŸ„ `admin\pinjaman\edit-pinjaman.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Edit` | UI | @section('title', 'Edit Pinjaman') |
| 15 | `Edit` | UI | <span class="text-gray-900 font-medium">Edit Pinjaman</span> |
| 17 | `Edit` | UI | <h1 class="text-3xl font-bold text-gray-900 font-display">Edit Pinjaman</h1> |
| 24 | `` | UI | <form action="{{ route('admin.pinjaman.update-pinjaman', $pinjaman->id) }}" method="POST" class="space-y-6"> |
| 31 | `` | UI | <select name="id_anggota" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none"> |
| 35 | `` | UI | {{ $item->user->nama ?? 'N/A' }} - {{ $item->user->email ?? 'N/A' }} |
| 38 | `` | UI | </select> |
| 46 | `` | UI | <input type="number" name="nominal" value="{{ old('nominal', $pinjaman->jumlah_pinjam) }}" required min="100000" step="10000" |
| 54 | `` | UI | <select name="durasi" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none"> |
| 59 | `` | UI | </select> |
| 65 | `` | UI | <input type="date" name="tgl_pinjam" value="{{ old('tgl_pinjam', $pinjaman->tgl_pinjam->format('Y-m-d')) }}" required |
| 71 | `` | UI | <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl hover:from-[#4a3514] hover:to-[#674c1d] transition-all font-medium shadow-md"> |
| 72 | `Update` | UI | Update Pinjaman |
| 74 | `` | UI | <a href="{{ route('admin.pinjaman.detail-pinjaman', $pinjaman->id) }}" class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all font-medium"> |

## ðŸ„ `admin\pinjaman\index.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Dashboard` | UI | @section('title', 'Dashboard Pinjaman') |
| 10 | `Dashboard` | UI | <h1 class="text-3xl font-bold text-gray-900 font-display">Dashboard Pinjaman</h1> |
| 27 | `Pending` | UI | <!-- Pengajuan Pending --> |
| 30 | `Pending` | UI | <h3 class="text-sm font-medium text-gray-500 group-hover:text-gray-700 transition-colors">Pengajuan Pending</h3> |
| 59 | `Total` | UI | <!-- Total Nominal Pinjaman --> |
| 62 | `Total` | UI | <h3 class="text-sm font-medium text-gray-500">Total Outstanding</h3> |
| 76 | `` | UI | <div class="bg-white rounded-2xl shadow-sm p-5 border-2 {{ ($stats['total_angsuran_telat'] ?? 0) > 0 ? 'border-red-500 bg-red-50/30' : 'border-gray-100' }} hover:shadow-md transition-all cursor-pointer group" onclick="window.location.href='{{ route('admin.pinjaman.angsuran') }}?status=telat'"> |
| 135 | `Actions` | UI | <!-- Inline Actions --> |
| 136 | `` | UI | <form action="{{ route('admin.pinjaman.approve-pengajuan', $pengajuan->id) }}" method="POST" class="inline"> |
| 138 | `` | UI | <button type="submit" class="w-8 h-8 bg-green-50 text-green-600 hover:bg-green-600 hover:text-white rounded-lg flex items-center justify-center transition-colors" title="Setujui"> |
| 142 | `` | UI | <form action="{{ route('admin.pinjaman.reject-pengajuan', $pengajuan->id) }}" method="POST" class="inline"> |
| 144 | `` | UI | <button type="submit" class="w-8 h-8 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg flex items-center justify-center transition-colors" title="Tolak"> |
| 148 | `Detail` | UI | <a href="{{ route('admin.pinjaman.detail-pengajuan', $pengajuan->id) }}" class="w-8 h-8 border border-gray-200 text-gray-600 hover:border-[#674c1d] hover:text-[#674c1d] rounded-lg flex items-center justify-center transition-colors" title="Lihat Detail"> |
| 181 | `` | UI | <li class="p-4 hover:bg-[#674c1d]/5 transition-colors cursor-pointer" onclick="window.location.href='{{ route('admin.pinjaman.detail-pembayaran', $bayar->id) }}'"> |
| 193 | `Pending` | PHP | $statusLabels = ['1' => 'Pending', '2' => 'Ditolak', '3' => 'Disetujui', '4' => 'Terlaksana']; |
| 195 | `` | PHP | $s = $bayar->status ?? '1'; |
| 197 | `Pending` | UI | <span class="px-2.5 py-1 {{ $statusClass[$s] ?? $statusClass['1'] }} border rounded-full text-[10px] font-bold uppercase">{{ $statusLabels[$s] ?? 'Pending' }}</span> |
| 225 | `` | UI | <div class="flex items-center justify-between p-3 bg-white rounded-xl shadow-sm border border-orange-100 hover:border-orange-300 transition-colors cursor-pointer" onclick="window.location.href='{{ route('admin.pinjaman.detail-angsuran', $item->id) }}'"> |
| 247 | `` | UI | <a href="{{ route('admin.pinjaman.angsuran') }}?status=telat" class="text-[10px] text-red-600 font-bold uppercase hover:underline">Lihat Semua</a> |
| 251 | `` | UI | <!-- We use placeholder visual if actual late items aren't passed to view directly, just tell admin to check --> |
| 257 | `` | UI | <a href="{{ route('admin.pinjaman.angsuran') }}?status=telat" class="inline-flex items-center justify-center px-3 py-1.5 bg-red-600 text-white text-xs font-bold rounded-lg hover:bg-red-700 transition-colors shadow-sm"> |
| 281 | `` | UI | <div class="flex items-center justify-between p-2 hover:bg-[#674c1d]/5 rounded-lg transition-colors cursor-pointer" onclick="window.location.href='{{ route('admin.pinjaman.detail-pinjaman', $pinjaman->id) }}'"> |

## ðŸ„ `admin\pinjaman\pembayaran.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 7 | `` | UI | @if(session('success')) |
| 14 | `` | UI | <p class="text-green-800 font-medium">{{ session('success') }}</p> |
| 17 | `` | UI | @if(session('error')) |
| 24 | `` | UI | <p class="text-red-800 font-medium">{{ session('error') }}</p> |
| 41 | `Filter` | UI | <!-- Filter Section --> |
| 43 | `` | UI | <form method="GET" action="{{ route('admin.pinjaman.pembayaran') }}" class="flex flex-col md:flex-row gap-4"> |
| 45 | `` | UI | <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama nasabah atau email..." |
| 49 | `` | UI | <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none"> |
| 50 | `Status` | UI | <option value="">Semua Status</option> |
| 51 | `Pending` | UI | <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Pending</option> |
| 52 | `` | UI | <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Ditolak</option> |
| 53 | `` | UI | <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>Disetujui</option> |
| 54 | `` | UI | <option value="4" {{ request('status') == '4' ? 'selected' : '' }}>Terlaksana</option> |
| 55 | `` | UI | </select> |
| 57 | `` | UI | <button type="submit" class="px-6 py-2 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-md font-medium"> |
| 58 | `Filter` | UI | Filter |
| 74 | `Status` | UI | <th class="px-5 py-4 text-center text-xs font-bold text-[#674c1d] uppercase tracking-wide w-28">Status</th> |
| 87 | `` | UI | <p class="text-sm text-gray-500 truncate max-w-[200px]">{{ $item->nasabah->user->email ?? '-' }}</p> |
| 102 | `Pending` | UI | '1' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'label' => 'Pending'], |
| 107 | `` | PHP | $status = $statusConfig[$item->status] ?? $statusConfig['1']; |
| 109 | `` | UI | <span class="inline-block px-3 py-1.5 {{ $status['bg'] }} {{ $status['text'] }} rounded-lg text-xs font-semibold border border-current/10"> |
| 110 | `` | UI | {{ $status['label'] }} |
| 114 | `` | UI | <a href="{{ route('admin.pinjaman.detail-pembayaran', $item->id) }}" |
| 116 | `Detail` | UI | Detail |

## ðŸ„ `admin\pinjaman\pengajuan.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 20 | `Filter` | UI | <!-- Filter Section --> |
| 22 | `` | UI | <form method="GET" action="{{ route('admin.pinjaman.pengajuan') }}" class="flex flex-col md:flex-row gap-4"> |
| 24 | `` | UI | <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama nasabah atau email..." |
| 28 | `` | UI | <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none"> |
| 29 | `Status` | UI | <option value="">Semua Status</option> |
| 30 | `Pending` | UI | <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option> |
| 31 | `` | UI | <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option> |
| 32 | `` | UI | </select> |
| 35 | `` | UI | <select name="jenis" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none"> |
| 39 | `` | UI | </select> |
| 41 | `` | UI | <button type="submit" class="px-6 py-2 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-md font-medium"> |
| 42 | `Filter` | UI | Filter |
| 59 | `Status` | UI | <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Status</th> |
| 70 | `` | UI | <p class="text-sm text-gray-500">{{ $item->nasabah->user->email ?? '-' }}</p> |
| 86 | `Pending` | UI | '1' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Pending'], |
| 91 | `` | PHP | $s = $statusPengajuan[$item->status ?? '1'] ?? $statusPengajuan['1']; |
| 96 | `` | UI | <a href="{{ route('admin.pinjaman.detail-pengajuan', $item->id) }}" |
| 98 | `Detail` | UI | Detail |

## ðŸ„ `admin\pinjaman\pinjaman-aktif.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 15 | `` | UI | <a href="{{ route('admin.pinjaman.create-pinjaman') }}" class="px-4 py-2 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-md text-sm font-medium"> |
| 25 | `Filter` | UI | <!-- Filter Section --> |
| 27 | `` | UI | <form method="GET" action="{{ route('admin.pinjaman.pinjaman-aktif') }}" class="flex flex-col md:flex-row gap-4"> |
| 29 | `` | UI | <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama nasabah atau email..." |
| 33 | `` | UI | <select name="jenis" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none"> |
| 37 | `` | UI | </select> |
| 40 | `` | UI | <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-md font-medium"> |
| 41 | `Filter` | UI | Filter |
| 69 | `` | UI | <p class="text-sm text-gray-500">{{ $item->nasabah->user->email ?? '-' }}</p> |
| 91 | `` | UI | <a href="{{ route('admin.pinjaman.detail-pinjaman', $item->id) }}" |
| 93 | `Detail` | UI | Detail |

## ðŸ„ `admin\pinjaman\pinjaman-lunas.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 18 | `Filter` | UI | <!-- Filter Section --> |
| 20 | `` | UI | <form method="GET" action="{{ route('admin.pinjaman.pinjaman-lunas') }}" class="flex flex-col md:flex-row gap-4"> |
| 22 | `` | UI | <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama nasabah atau email..." |
| 26 | `` | UI | <select name="jenis" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none"> |
| 30 | `` | UI | </select> |
| 32 | `` | UI | <button type="submit" class="px-6 py-2 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-md font-medium"> |
| 33 | `Filter` | UI | Filter |
| 61 | `` | UI | <p class="text-sm text-gray-500">{{ $item->nasabah->user->email ?? '-' }}</p> |
| 83 | `` | UI | <a href="{{ route('admin.pinjaman.detail-pinjaman', $item->id) }}" |
| 85 | `Detail` | UI | Detail |

## ðŸ„ `admin\tabungan\create-transaksi.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 28 | `` | UI | <form method="POST" action="{{ route('admin.tabungan.store-transaksi') }}" enctype="multipart/form-data" id="transaksi-form"> |
| 41 | `` | UI | <select name="id_anggota" id="id_anggota" required |
| 46 | `` | UI | <option value="{{ $n->id }}" data-saldo="{{ $n->id }}">{{ $n->user->nama ?? 'N/A' }} — {{ $n->user->email ?? '' }}</option> |
| 48 | `` | UI | </select> |
| 63 | `` | UI | <select name="jenis" id="jenis" required |
| 68 | `` | UI | </select> |
| 72 | `` | UI | <select name="via" required |
| 74 | `Transfer` | UI | <option value="transfer">Transfer</option> |
| 76 | `` | UI | </select> |
| 94 | `` | UI | <input type="text" name="nominal" id="nominal" placeholder="0" required |
| 102 | `` | UI | <div id="saldo-warning" class="hidden mt-3 p-4 bg-red-50 border border-red-200 rounded-xl flex items-center gap-3"> |
| 109 | `` | UI | <input type="datetime-local" name="tgl_transaksi" id="tgl_transaksi" value="{{ now()->format('Y-m-d\TH:i') }}" required |
| 126 | `` | UI | <textarea name="keterangan" id="keterangan" rows="3" |
| 131 | `Transfer` | UI | <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Transfer <span class="text-gray-400 font-normal">(opsional)</span></label> |
| 133 | `` | UI | <input type="file" name="foto_bukti[]" accept="image/jpeg,image/png,image/jpg" multiple |
| 150 | `` | UI | <button type="submit" id="submit-btn" class="flex-1 sm:flex-initial inline-flex justify-center items-center gap-2 px-8 py-3 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-semibold hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-lg shadow-[#674c1d]/20"> |
| 174 | `` | UI | preview.classList.remove('hidden'); |
| 190 | `` | UI | preview.classList.add('hidden'); |
| 194 | `` | UI | document.querySelector('input[name="foto_bukti[]"]').addEventListener('change', function() { |
| 205 | `` | UI | document.getElementById('transaksi-form').addEventListener('submit', function(e) { |

## ðŸ„ `admin\tabungan\detail-janji-temu.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Detail` | UI | @section('title', 'Detail Janji Temu') |
| 7 | `Back` | UI | <!-- Back Button --> |
| 43 | `Email` | UI | <p class="text-sm text-gray-500 mb-1">Email</p> |
| 44 | `` | UI | <p class="font-medium text-gray-900">{{ $janjiTemu->nasabah->user->email }}</p> |
| 55 | `Detail` | UI | <!-- Janji Temu Detail Card --> |
| 57 | `Detail` | UI | <h2 class="text-xl font-bold text-gray-900 font-display mb-6">Detail Janji Temu</h2> |
| 102 | `` | UI | <iframe src="https://www.google.com/maps/embed?pb=!4v1771057242792!6m8!1m7!1sTDnmeXtVvimBtQeXmqSSCQ!2m2!1d-6.267415399913648!2d106.9806162945405!3f247.41483905689947!4f-35.52001210835799!5f0.7820865974627469" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Peta Lokasi Janji Temu"></iframe> |
| 137 | `` | UI | @if($janjiTemu->status == '2') |
| 154 | `Actions` | UI | <!-- Sidebar Actions (sticky) --> |
| 156 | `` | UI | @if($janjiTemu->status == '1') |
| 159 | `` | UI | @if(session('error')) |
| 165 | `` | UI | <p class="text-sm font-bold">{{ session('error') }}</p> |
| 166 | `` | UI | @if(str_contains(session('error'), 'Saldo Petty Cash')) |
| 173 | `` | UI | @if($janjiTemu->status == '1') |
| 194 | `` | UI | <form action="{{ route('admin.tabungan.create-trans-from-janji-temu', $janjiTemu->id) }}" method="POST" enctype="multipart/form-data"> |
| 202 | `` | UI | <input type="text" name="nominal" id="nominal" value="{{ number_format($janjiTemu->nominal, 0, ',', '.') }}" |
| 209 | `Edit` | UI | <p class="text-xs text-gray-500 mt-1">Default: Rp {{ number_format($janjiTemu->nominal, 0, ',', '.') }}. Edit jika nominal berbeda.</p> |
| 214 | `` | UI | <input type="file" name="foto_penerimaan[]" multiple |
| 216 | `` | UI | <p class="text-xs text-gray-500 mt-1">Bisa upload foto uang / kwitansi / bukti {{ ($janjiTemu->jenis ?? 'setoran') == 'setoran' ? 'penerimaan' : 'penyerahan' }}</p> |
| 221 | `` | UI | <textarea name="keterangan_admin" rows="3" |
| 239 | `` | UI | <button type="submit" data-confirm-message="{{ $confirmMsg }}" onclick="return confirm(this.dataset.confirmMessage)" |
| 252 | `Status` | UI | <h3 class="font-bold text-lg mb-2">Status Saat Ini</h3> |
| 253 | `` | UI | @if($janjiTemu->status == '1') |
| 273 | `` | UI | <div x-show="openCancel" |
| 280 | `` | UI | <form action="{{ route('admin.janji-temu.cancel-tabungan', $janjiTemu->id) }}" method="POST"> |
| 284 | `` | UI | <textarea name="keterangan_admin" required rows="3" |
| 289 | `` | UI | <button type="button" @click="openCancel = false" |
| 291 | `` | UI | <button type="submit" |
| 298 | `` | UI | @elseif($janjiTemu->status == '2') |
| 343 | `` | UI | document.getElementById('photoPreviewModal').classList.remove('hidden'); |
| 344 | `` | UI | document.getElementById('photoPreviewModal').classList.add('flex'); |
| 349 | `` | UI | document.getElementById('photoPreviewModal').classList.add('hidden'); |
| 350 | `` | UI | document.getElementById('photoPreviewModal').classList.remove('flex'); |
| 369 | `` | UI | var formProses = document.querySelector('form[action*="create-trans-from-janji-temu"]'); |
| 371 | `` | UI | formProses.addEventListener('submit', function(e) { |
| 376 | `` | UI | hidden.type = 'hidden'; |
| 377 | `` | UI | hidden.name = 'nominal'; |
| 379 | `` | UI | nominalInput.removeAttribute('name'); |

## ðŸ„ `admin\tabungan\detail-pengajuan-setor.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Detail` | UI | @section('title', 'Detail Pengajuan Setor') |
| 11 | `Detail` | UI | <h1 class="text-3xl font-bold text-gray-900 font-display">Detail Pengajuan Setor</h1> |
| 35 | `Email` | UI | <p class="text-sm text-gray-600">Email</p> |
| 36 | `` | UI | <p class="font-semibold text-gray-900">{{ $pengajuan->nasabah->user->email ?? 'N/A' }}</p> |
| 75 | `` | UI | @if($pengajuan->status == '1') |
| 77 | `` | UI | <button onclick="toggleEditNominal()" id="btn-edit-nominal" |
| 84 | `Edit` | UI | Edit |
| 93 | `` | UI | @if($pengajuan->status == '1') |
| 95 | `` | UI | <div id="nominal-edit" class="hidden"> |
| 96 | `` | UI | <input type="text" id="input-nominal" |
| 112 | `Status` | UI | <p class="text-sm text-gray-600">Status</p> |
| 115 | `Pending` | UI | '1' => ['label' => 'Pending', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'], |
| 119 | `` | PHP | $status = $statusConfig[$pengajuan->status] ?? $statusConfig['1']; |
| 122 | `` | UI | class="inline-block mt-2 px-4 py-2 {{ $status['bg'] }} {{ $status['text'] }} rounded-full text-sm font-semibold"> |
| 123 | `` | UI | {{ $status['label'] }} |
| 127 | `Approve` | UI | <!-- Approve Button --> |
| 128 | `` | UI | @if($pengajuan->status == '1') |
| 139 | `` | UI | ditampilkan (jika sudah diedit, akan menggunakan nilai edit)</p> |
| 145 | `Approve` | UI | <!-- Approve Modal with Keterangan Admin --> |
| 146 | `` | UI | @if($pengajuan->status == '1') |
| 153 | `` | UI | <input type="hidden" name="nominal" id="modal-nominal"> |
| 154 | `` | UI | <input type="hidden" name="status" value="2"> |
| 165 | `` | UI | <select name="metode_bayar" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none" required> |
| 166 | `Transfer` | UI | <option value="transfer_koperasi">Transfer Rekening Koperasi (Utama)</option> |
| 167 | `Transfer` | UI | <option value="transfer_admin">Transfer ke Rekening Admin (Petty Cash)</option> |
| 169 | `` | UI | </select> |
| 177 | `` | UI | <textarea name="keterangan_admin" rows="3" |
| 184 | `` | UI | <button type="button" onclick="hideApproveModal()" |
| 188 | `` | UI | <button type="submit" |
| 198 | `Transfer` | UI | <!-- Bukti Foto Transfer --> |
| 202 | `Transfer` | UI | Transfer</h2> |
| 208 | `Transfer` | UI | data-preview-label="Bukti Transfer #{{ $index + 1 }}" |
| 212 | `Transfer` | UI | <img src="{{ asset('storage/' . $bukti->file_path) }}" alt="Bukti Transfer" |
| 213 | `` | UI | class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" |
| 247 | `Transfer` | UI | Bukti Transfer {{ $index + 1 }} |
| 259 | `Actions` | UI | <!-- Sidebar Actions (sticky) --> |
| 261 | `` | UI | @if($pengajuan->status == '1') |
| 276 | `` | UI | <form method="POST" action="{{ route('admin.tabungan.delete-pengajuan-setor', $pengajuan->id) }}" |
| 277 | `` | UI | onsubmit="return confirm('Yakin hapus? Tidak dapat dibatalkan!')"> |
| 279 | `` | UI | @method('DELETE') |
| 280 | `` | UI | <button type="submit" |
| 294 | `Reject` | UI | <!-- Reject Modal --> |
| 299 | `` | UI | <form method="POST" action="{{ route('admin.tabungan.reject-setor', $pengajuan->id) }}"> |
| 305 | `` | UI | <textarea name="keterangan_admin" rows="4" required |
| 311 | `` | UI | <button type="button" onclick="hideRejectModal()" |
| 315 | `` | UI | <button type="submit" |
| 335 | `` | UI | document.getElementById('nominal-edit').classList.toggle('hidden'); |
| 377 | `` | UI | form.action = '{{ route("admin.tabungan.edit-pengajuan-setor", $pengajuan->id) }}'; |
| 380 | `` | UI | form.action = '{{ route("admin.tabungan.approve-setor", $pengajuan->id) }}'; |
| 384 | `` | UI | document.getElementById('approveModal').classList.remove('hidden'); |
| 388 | `` | UI | document.getElementById('approveModal').classList.add('hidden'); |
| 392 | `` | UI | document.getElementById('rejectModal').classList.remove('hidden'); |
| 396 | `` | UI | document.getElementById('rejectModal').classList.add('hidden'); |

## ðŸ„ `admin\tabungan\detail-pengajuan-tarik.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Detail` | UI | @section('title', 'Detail Pengajuan Penarikan') |
| 10 | `Detail` | UI | <h1 class="text-3xl font-bold text-gray-900 font-display">Detail Pengajuan Penarikan</h1> |
| 32 | `Email` | UI | <p class="text-sm text-gray-600">Email</p> |
| 33 | `` | UI | <p class="font-semibold text-gray-900">{{ $pengajuan->nasabah->user->email ?? 'N/A' }}</p> |
| 50 | `` | UI | @if($pengajuan->status == '1') |
| 58 | `` | UI | @if(session('error')) |
| 64 | `` | UI | <p class="text-sm font-bold text-red-800">{{ session('error') }}</p> |
| 65 | `` | UI | @if(str_contains(session('error'), 'Petty Cash')) |
| 66 | `` | UI | <p class="text-xs text-red-600 mt-1">Saldo Anda saat ini tidak mencukupi untuk melakukan transfer penarikan nasabah ini.</p> |
| 81 | `` | UI | @if($pengajuan->metode_transfer == 'transfer' && isset($biayaDefault)) |
| 83 | `Transfer` | UI | <p class="text-sm text-gray-600">Biaya Transfer (ditanggung nasabah)</p> |
| 89 | `` | UI | <span class="inline-block mt-1 px-3 py-1 {{ $pengajuan->metode_transfer == 'transfer' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }} rounded-full text-sm font-semibold"> |
| 93 | `` | UI | @if($pengajuan->metode_transfer == 'transfer') |
| 119 | `` | PHP | $totalDipotong = $pengajuan->nominal + ($pengajuan->metode_transfer == 'transfer' ? (float)($biayaDefault ?? 0) : 0); |
| 123 | `Total` | UI | <p class="text-xs text-gray-500 mt-1">Total dipotong (nominal + biaya transfer): Rp {{ number_format($totalDipotong, 0, ',', '.') }}. Kekurangan: Rp {{ number_format($totalDipotong - $saldo, 0, ',', '.') }}</p> |
| 126 | `` | UI | <p class="text-xs text-gray-500 mt-1">Sisa setelah penarikan (setelah dikurangi nominal + biaya transfer): Rp {{ number_format($saldo - $totalDipotong, 0, ',', '.') }}</p> |
| 130 | `Status` | UI | <p class="text-sm text-gray-600">Status</p> |
| 133 | `Pending` | UI | '1' => ['label' => 'Pending', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'], |
| 137 | `` | PHP | $status = $statusConfig[$pengajuan->status] ?? $statusConfig['1']; |
| 139 | `` | UI | <span class="inline-block mt-2 px-4 py-2 {{ $status['bg'] }} {{ $status['text'] }} rounded-full text-sm font-semibold"> |
| 140 | `` | UI | {{ $status['label'] }} |
| 150 | `` | UI | @if($pengajuan->status == '2' && $pengajuan->foto_bukti_tf_admin) |
| 152 | `Transfer` | UI | <p class="text-sm text-gray-600 mb-2">Bukti Transfer (Admin)</p> |
| 154 | `Transfer` | UI | <img src="{{ asset('storage/' . $pengajuan->foto_bukti_tf_admin) }}" alt="Bukti Transfer" class="w-full h-auto cursor-pointer" onclick="window.open(this.src)"> |
| 169 | `Actions` | UI | <!-- Sidebar Actions (sticky) --> |
| 171 | `` | UI | @if($pengajuan->status == '1') |
| 172 | `Approve` | UI | <!-- Approve Form with Bank Selection --> |
| 175 | `` | UI | <form method="POST" action="{{ route('admin.tabungan.approve-tarik', $pengajuan->id) }}" enctype="multipart/form-data" class="space-y-4" id="approve-form"> |
| 178 | `` | UI | @if($pengajuan->metode_transfer == 'transfer') |
| 182 | `` | UI | <select name="bank_pengirim" id="bank_pengirim" required |
| 190 | `` | UI | </select> |
| 196 | `Transfer` | UI | <p class="text-sm font-semibold text-gray-700">Biaya Transfer (ditanggung nasabah):</p> |
| 200 | `Total` | UI | <p class="text-sm font-semibold text-gray-700">Total dikurangi dari saldo:</p> |
| 201 | `` | UI | <p class="font-bold text-gray-900" id="total-dipotong-display">Rp {{ number_format($pengajuan->nominal, 0, ',', '.') }}</p> |
| 204 | `Total` | UI | <p class="text-sm font-semibold text-gray-700">Total Diterima Nasabah:</p> |
| 205 | `` | UI | <p class="font-bold text-green-600" id="total-display">Rp {{ number_format($pengajuan->nominal, 0, ',', '.') }}</p> |
| 210 | `Upload` | UI | <!-- Upload Bukti TF Admin --> |
| 212 | `Upload` | UI | <label class="block text-sm font-medium text-gray-700 mb-2">Upload Bukti Transfer *</label> |
| 213 | `` | UI | <input type="file" name="foto_bukti_tf_admin" accept="image/jpeg,image/png,image/jpg" required |
| 225 | `` | PHP | $isAdminSaldoCukup = ($pengajuan->metode_transfer == 'transfer') ? ($adminSaldo >= $pengajuan->nominal) : true; |
| 229 | `` | UI | <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all font-medium shadow-md"> |
| 244 | `Reject` | UI | <!-- Reject Button --> |
| 252 | `Reject` | UI | <!-- Reject Modal --> |
| 256 | `` | UI | <form method="POST" action="{{ route('admin.tabungan.reject-tarik', $pengajuan->id) }}"> |
| 260 | `` | UI | <textarea name="keterangan_admin" rows="4" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none" placeholder="Masukkan alasan penolakan..."></textarea> |
| 263 | `` | UI | <button type="button" onclick="hideRejectModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"> |
| 266 | `` | UI | <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"> |
| 308 | `` | UI | biayaSection.classList.remove('hidden'); |
| 311 | `` | UI | document.getElementById('total-display').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(nominal); |
| 313 | `` | UI | const totalDipotongEl = document.getElementById('total-dipotong-display'); |
| 326 | `` | UI | preview.classList.remove('hidden'); |
| 333 | `` | UI | document.getElementById('rejectModal').classList.remove('hidden'); |
| 337 | `` | UI | document.getElementById('rejectModal').classList.add('hidden'); |

## ðŸ„ `admin\tabungan\detail-transaksi.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Detail` | UI | @section('title', 'Detail Transaksi Tabungan') |
| 10 | `Detail` | UI | <h1 class="text-3xl font-bold text-gray-900 font-display">Detail Transaksi Tabungan</h1> |
| 16 | `` | UI | <a href="{{ route('admin.tabungan.edit-transaksi', $transaksi->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium"> |
| 17 | `Edit` | UI | Edit Transaksi |
| 19 | `` | UI | <form method="POST" action="{{ route('admin.tabungan.destroy-transaksi', $transaksi->id) }}" class="inline" onsubmit="return confirm('Yakin hapus transaksi ini?')"> |
| 21 | `` | UI | @method('DELETE') |
| 22 | `` | UI | <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium"> |
| 28 | `` | UI | <a href="{{ route('admin.tabungan.print-struk-transaksi', $transaksi->id) }}" target="_blank" class="px-4 py-2 bg-[#674c1d] text-white rounded-lg hover:bg-[#8b6f2f] transition-colors text-sm font-medium inline-flex items-center gap-2"> |
| 40 | `` | UI | <p class="text-green-800 font-medium">{{ session('success') }}</p> |
| 41 | `` | UI | <a href="{{ route('admin.tabungan.print-struk-transaksi', $transaksi->id) }}" target="_blank" class="shrink-0 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium inline-flex items-center gap-2"> |
| 46 | `` | UI | @elseif(session('success')) |
| 48 | `` | UI | <p class="text-green-800 font-medium">{{ session('success') }}</p> |
| 64 | `Email` | UI | <p class="text-sm text-gray-600">Email</p> |
| 65 | `` | UI | <p class="font-semibold text-gray-900">{{ $transaksi->nasabah->user->email ?? 'N/A' }}</p> |
| 112 | `Transfer` | UI | <h2 class="text-lg font-bold text-primary font-display mb-4 pb-4 border-b border-gray-200">Bukti Foto Transfer</h2> |
| 123 | `Transfer` | UI | <p class="text-xs text-gray-600">{{ $bukti->keterangan ?? 'Bukti Transfer' }}</p> |
| 131 | `Transfer` | UI | <h2 class="text-lg font-bold text-primary font-display mb-4 pb-4 border-b border-gray-200">Bukti Transfer Admin</h2> |
| 133 | `Transfer` | UI | <img src="{{ asset('storage/' . $transaksi->pengajuanTarik->foto_bukti_tf_admin) }}" alt="Bukti Transfer" class="w-full h-auto cursor-pointer" onclick="window.open(this.src)"> |
| 135 | `` | UI | Bukti transfer di-upload saat persetujuan |

## ðŸ„ `admin\tabungan\edit-transaksi.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Edit` | UI | @section('title', 'Edit Transaksi') |
| 10 | `Edit` | UI | <h1 class="text-3xl font-bold text-gray-900 font-display">Edit Transaksi</h1> |
| 14 | `` | UI | <a href="{{ route('admin.tabungan.detail-transaksi', $transaksi->id) }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium"> |
| 35 | `` | UI | <form method="POST" action="{{ route('admin.tabungan.update-transaksi', $transaksi->id) }}" enctype="multipart/form-data" class="space-y-6" id="edit-form"> |
| 44 | `` | UI | <p class="text-sm text-gray-600">{{ $transaksi->nasabah->user->email ?? '' }}</p> |
| 67 | `` | UI | <input type="text" name="nominal" id="nominal" |
| 78 | `` | UI | <input type="datetime-local" name="tgl_transaksi" |
| 86 | `` | UI | <textarea name="keterangan" rows="3" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[[#674c1d]] focus:ring-2 focus:ring-[[#674c1d]]/20 outline-none resize-none" placeholder="Tambahkan keterangan...">{{ $transaksi->keterangan }}</textarea> |
| 99 | `Transfer` | UI | <img src="{{ Storage::url($foto->file_path) }}" alt="Bukti Transfer" |
| 111 | `Upload` | UI | <!-- Upload Bukti Foto Baru (Multiple) --> |
| 113 | `Upload` | UI | <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Bukti Baru (Opsional)</label> |
| 114 | `` | UI | <input type="file" name="foto_bukti[]" accept="image/jpeg,image/png,image/jpg" multiple |
| 117 | `` | UI | <p class="text-xs text-gray-500 mt-1">Bisa upload lebih dari 1 foto. Foto baru akan ditambahkan ke bukti yang sudah ada.</p> |
| 122 | `Submit` | UI | <!-- Submit --> |
| 124 | `` | UI | <a href="{{ route('admin.tabungan.detail-transaksi', $transaksi->id) }}" class="flex-1 px-4 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-colors text-center"> |
| 127 | `` | UI | <button type="submit" class="flex-1 px-4 py-3 bg-gradient-to-r from-[[#674c1d]] to-[#8b6f2f] text-white rounded-xl font-semibold hover:from-[[#4a3514]] hover:to-[[#674c1d]] transition-all"> |
| 128 | `Update` | UI | Update Transaksi |
| 146 | `` | UI | preview.innerHTML = ''; // Clear previous previews |
| 149 | `` | UI | preview.classList.remove('hidden'); |
| 168 | `` | UI | preview.classList.add('hidden'); |
| 173 | `` | UI | document.getElementById('edit-form').addEventListener('submit', function(e) { |

## ðŸ„ `admin\tabungan\index.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Dashboard` | UI | @section('title', 'Dashboard Tabungan') |
| 11 | `Dashboard` | UI | <h1 class="text-2xl font-bold text-gray-900 font-display">Dashboard Tabungan</h1> |
| 34 | `` | UI | class="group bg-white rounded-xl border border-gray-100 shadow-sm p-5 hover:border-[#674c1d]/30 hover:shadow-md transition-all duration-200"> |
| 55 | `` | UI | class="group bg-white rounded-xl border border-gray-100 shadow-sm p-5 hover:border-[#8b6f2f]/30 hover:shadow-md transition-all duration-200"> |
| 137 | `Pending` | UI | <span class="px-2.5 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold">Pending</span> |
| 138 | `` | UI | <a href="{{ route('admin.tabungan.detail-pengajuan-setor', $pengajuan->id) }}" |
| 189 | `Pending` | UI | <span class="px-2.5 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold">Pending</span> |
| 190 | `` | UI | <a href="{{ route('admin.tabungan.detail-pengajuan-tarik', $pengajuan->id) }}" |

## ðŸ„ `admin\tabungan\pengajuan-setor.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 20 | `Filter` | UI | <!-- Filter Section --> |
| 22 | `Filter` | UI | <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Filter</p> |
| 23 | `` | UI | <form method="GET" action="{{ route('admin.tabungan.pengajuan-setor') }}" class="flex flex-col md:flex-row gap-4 items-stretch md:items-center"> |
| 25 | `` | UI | <label for="filter-search" class="block text-sm font-medium text-gray-600 mb-1.5">Cari nasabah</label> |
| 26 | `` | UI | <input id="filter-search" type="text" name="search" value="{{ request('search') }}" placeholder="Nama atau email nasabah..." |
| 30 | `` | UI | <label for="filter-status" class="block text-sm font-medium text-gray-600 mb-1.5">Status</label> |
| 31 | `` | UI | <select id="filter-status" name="status" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-[#674c1d]/30 focus:border-[#674c1d] outline-none transition-colors bg-white"> |
| 32 | `Status` | UI | <option value="">Semua Status</option> |
| 33 | `Pending` | UI | <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Pending</option> |
| 34 | `` | UI | <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Disetujui</option> |
| 35 | `` | UI | <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>Ditolak</option> |
| 36 | `` | UI | </select> |
| 39 | `` | UI | <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-md font-semibold text-sm"> |
| 44 | `Reset` | UI | Reset |
| 61 | `Status` | UI | <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase tracking-wider">Status</th> |
| 76 | `` | UI | <p class="text-sm text-gray-500">{{ $item->nasabah->user->email ?? '-' }}</p> |
| 95 | `Pending` | UI | '1' => ['label' => 'Pending', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'], |
| 99 | `` | PHP | $status = $statusConfig[$item->status] ?? $statusConfig['1']; |
| 101 | `` | UI | <span class="px-3 py-1 {{ $status['bg'] }} {{ $status['text'] }} rounded-full text-xs font-semibold"> |
| 102 | `` | UI | {{ $status['label'] }} |
| 106 | `` | UI | <a href="{{ route('admin.tabungan.detail-pengajuan-setor', $item->id) }}" |

## ðŸ„ `admin\tabungan\pengajuan-tarik.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 11 | `Transfer` | UI | <p class="text-gray-500 mt-1">Kelola pengajuan penarikan tabungan via <strong>Transfer</strong> dari nasabah</p> |
| 19 | `Transfer` | UI | <!-- Info: Hanya Transfer (tema selaras, tidak biru mencolok) --> |
| 27 | `Transfer` | UI | <p class="text-sm font-semibold text-gray-800">Hanya Penarikan via Transfer</p> |
| 28 | `` | UI | <p class="text-sm text-gray-600 mt-0.5">Daftar ini menampilkan pengajuan penarikan dengan metode <strong>Transfer</strong>. Penarikan <strong>Tunai</strong> diproses melalui menu <a href="{{ route('admin.janji-temu.index') }}" class="text-[#674c1d] font-medium hover:underline">Janji Temu</a>.</p> |
| 32 | `Filter` | UI | <!-- Filter Section --> |
| 34 | `Filter` | UI | <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Filter</p> |
| 35 | `` | UI | <form method="GET" action="{{ route('admin.tabungan.pengajuan-tarik') }}" class="flex flex-col md:flex-row gap-4 items-stretch md:items-center"> |
| 37 | `` | UI | <label for="search-tarik" class="block text-sm font-medium text-gray-600 mb-1.5">Cari nasabah</label> |
| 38 | `` | UI | <input id="search-tarik" type="text" name="search" value="{{ request('search') }}" placeholder="Nama atau email nasabah..." |
| 42 | `Status` | UI | <label for="status-tarik" class="block text-sm font-medium text-gray-600 mb-1.5">Status</label> |
| 43 | `` | UI | <select id="status-tarik" name="status" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-[#674c1d]/30 focus:border-[#674c1d] outline-none transition-colors bg-white"> |
| 44 | `Status` | UI | <option value="">Semua Status</option> |
| 45 | `Pending` | UI | <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Pending</option> |
| 46 | `` | UI | <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Disetujui</option> |
| 47 | `` | UI | <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>Ditolak</option> |
| 48 | `` | UI | </select> |
| 51 | `` | UI | <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-md font-semibold text-sm"> |
| 56 | `Reset` | UI | Reset |
| 72 | `Status` | UI | <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th> |
| 83 | `` | UI | <p class="text-sm text-gray-500">{{ $item->nasabah->user->email ?? '-' }}</p> |
| 93 | `Pending` | UI | '1' => ['label' => 'Pending', 'bg' => 'bg-amber-100', 'text' => 'text-amber-800'], |
| 97 | `` | PHP | $status = $statusConfig[$item->status] ?? $statusConfig['1']; |
| 99 | `` | UI | <span class="px-3 py-1 {{ $status['bg'] }} {{ $status['text'] }} rounded-lg text-xs font-semibold"> |
| 100 | `` | UI | {{ $status['label'] }} |
| 104 | `` | UI | <a href="{{ route('admin.tabungan.detail-pengajuan-tarik', $item->id) }}" |

## ðŸ„ `admin\tabungan\saldo-nasabah.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 19 | `Filter` | UI | <!-- Filter Section --> |
| 22 | `` | UI | <form method="GET" action="{{ route('admin.tabungan.saldo-nasabah') }}" class="flex flex-col sm:flex-row gap-4"> |
| 24 | `` | UI | <label for="search-saldo" class="block text-sm font-medium text-gray-600 mb-1.5">Nama atau email nasabah</label> |
| 25 | `` | UI | <input id="search-saldo" type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." |
| 29 | `` | UI | <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-md font-semibold text-sm"> |
| 34 | `Reset` | UI | Reset |
| 47 | `Total` | UI | <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Setoran</th> |
| 48 | `Total` | UI | <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Penarikan</th> |
| 60 | `` | UI | <p class="text-sm text-gray-500">{{ $item->user->email ?? '-' }}</p> |
| 80 | `` | UI | <a href="{{ route('admin.tabungan.transaksi') }}?search={{ urlencode($item->user->nama ?? '') }}" |
| 97 | `` | UI | <p class="text-sm text-gray-400">Gunakan filter di atas untuk mencari</p> |

## ðŸ„ `admin\tabungan\transaksi.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 15 | `` | UI | <a href="{{ route('admin.tabungan.create-transaksi') }}" class="px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all text-sm font-medium shadow-md"> |
| 25 | `Filter` | UI | <!-- Filter Section --> |
| 27 | `` | UI | <form method="GET" action="{{ route('admin.tabungan.transaksi') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4"> |
| 29 | `` | UI | <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama nasabah..." |
| 33 | `` | UI | <select name="jenis" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none"> |
| 37 | `` | UI | </select> |
| 40 | `` | UI | <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" |
| 44 | `` | UI | <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" |
| 48 | `` | UI | <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[[#4a3514]] hover:to-[#674c1d] transition-all shadow-md font-medium"> |
| 49 | `Filter` | UI | Filter Waktu/Cari |
| 55 | `Filter` | UI | <!-- Filter Tab Switch (Riwayat / Petty Cash) --> |
| 58 | `` | UI | class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ !request('filter') ? 'bg-[#674c1d] text-white shadow-md' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}"> |
| 61 | `` | UI | <a href="{{ route('admin.tabungan.transaksi', ['filter' => 'saya']) }}" |
| 62 | `` | UI | class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all flex items-center gap-2 {{ request('filter') == 'saya' ? 'bg-blue-600 text-white shadow-md' : 'bg-blue-50 text-blue-700 hover:bg-blue-100' }}"> |
| 65 | `` | UI | <span class="px-2 py-0.5 rounded-full text-xs {{ request('filter') == 'saya' ? 'bg-white/20 text-white' : 'bg-blue-200 text-blue-800' }}">{{ $myCount ?? 0 }}</span> |
| 67 | `` | UI | <a href="{{ route('admin.tabungan.transaksi', ['filter' => 'petty']) }}" |
| 68 | `` | UI | class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all flex items-center gap-2 {{ request('filter') == 'petty' ? 'bg-amber-500 text-white shadow-md' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}"> |
| 71 | `` | UI | <span class="px-2 py-0.5 rounded-full text-xs {{ request('filter') == 'petty' ? 'bg-white/20 text-white' : 'bg-amber-200 text-amber-800' }}">{{ $pettyCount ?? 0 }}</span> |
| 100 | `` | UI | <p class="text-xs text-gray-500">{{ $item->nasabah->user->email ?? '-' }}</p> |
| 131 | `` | UI | <a href="{{ route('admin.tabungan.detail-transaksi', $item->id) }}" |
| 133 | `Detail` | UI | Detail |
| 137 | `` | UI | <form method="POST" action="{{ route('admin.tabungan.destroy-transaksi', $item->id) }}" class="inline" onsubmit="return confirm('Yakin hapus transaksi ini?')"> |
| 139 | `` | UI | @method('DELETE') |
| 140 | `` | UI | <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-all text-xs font-medium border border-red-100"> |

## ðŸ„ `auth\login.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 5 | `` | UI | <meta name="viewport" content="width=device-width, initial-scale=1"> |
| 6 | `` | UI | <meta name="csrf-token" content="{{ csrf_token() }}"> |
| 7 | `Login` | UI | <title>Login - Koperasi Majakara</title> |
| 104 | `Success` | UI | <!-- Success Message --> |
| 105 | `` | UI | @if(session('success')) |
| 111 | `` | UI | <p class="text-green-800 font-medium">{{ session('success') }}</p> |
| 116 | `Error` | UI | <!-- General Error Message --> |
| 117 | `` | UI | @if(session('error')) |
| 123 | `` | UI | <p class="text-red-800 font-medium">{{ session('error') }}</p> |
| 128 | `Login` | UI | <!-- Login Form --> |
| 129 | `` | UI | <form method="POST" action="{{ route('login.submit') }}" id="loginForm" class="space-y-6"> |
| 132 | `Email` | UI | <!-- Email Field --> |
| 134 | `` | UI | <label for="email" class="block text-sm font-semibold text-gray-700 mb-2"> |
| 135 | `Email` | UI | Email <span class="text-red-500">*</span> |
| 143 | `` | UI | <input type="email" name="email" id="email" value="{{ old('email') }}" required |
| 144 | `` | UI | class="w-full pl-11 pr-4 py-3 border @error('email') border-red-300 @else border-gray-300 @enderror rounded-xl focus:ring-2 focus:ring-[#8b6f2f] focus:border-[#8b6f2f] transition-all outline-none bg-white/50" |
| 145 | `` | UI | placeholder="nama@email.com"> |
| 147 | `` | UI | @error('email') |
| 159 | `Password` | UI | <!-- Password Field --> |
| 161 | `` | UI | <label for="password" class="block text-sm font-semibold text-gray-700 mb-2"> |
| 162 | `Password` | UI | Password <span class="text-red-500">*</span> |
| 170 | `` | UI | <input type="password" name="password" id="password" required |
| 171 | `` | UI | class="w-full pl-11 pr-12 py-3 border @error('password') border-red-300 @else border-gray-300 @enderror rounded-xl focus:ring-2 focus:ring-[#8b6f2f] focus:border-[#8b6f2f] transition-all outline-none bg-white/50" |
| 172 | `` | UI | placeholder="Masukkan password"> |
| 173 | `` | UI | <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600"> |
| 180 | `` | UI | @error('password') |
| 192 | `Password` | UI | <!-- Remember Me & Forgot Password --> |
| 195 | `` | UI | <input type="checkbox" name="remember" id="remember" |
| 199 | `` | UI | <a href="#" class="text-sm text-[#674c1d] hover:text-[#8b6f2f] font-medium transition-colors">Lupa password?</a> |
| 202 | `Submit` | UI | <!-- Submit Button --> |
| 203 | `` | UI | <button type="submit" id="loginButton" |
| 204 | `` | UI | class="w-full px-8 py-3.5 bg-linear-to-r from-[#674c1d] via-[#8b6f2f] to-[#674c1d] bg-size-200 bg-pos-0 hover:bg-pos-100 text-white rounded-xl transition-all duration-500 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"> |
| 221 | `Register` | UI | <!-- Register Link --> |
| 231 | `` | UI | <a href="{{ route('register') }}" class="mt-4 inline-flex items-center gap-2 text-[#674c1d] font-semibold hover:text-[#8b6f2f] transition-colors"> |
| 300 | `All rights reserved` | UI | <p>© 2026 Koperasi Majakara. All rights reserved.</p> |
| 318 | `` | UI | <form id="pinForm" method="POST" action="{{ route('login.verify-pin') }}"> |
| 323 | `` | UI | <input type="password" name="pin" id="pin" maxlength="6" required autofocus |
| 337 | `` | UI | <button type="button" onclick="closePinModal()" |
| 341 | `` | UI | <button type="submit" id="verifyPinButton" |
| 361 | `` | UI | const passwordInput = document.getElementById('password'); |
| 364 | `` | UI | if (passwordInput.type === 'password') { |
| 365 | `` | UI | passwordInput.type = 'text'; |
| 368 | `` | UI | passwordInput.type = 'password'; |
| 374 | `` | UI | document.getElementById('loginForm').addEventListener('submit', function(e) { |
| 385 | `` | UI | loginButtonText.classList.add('hidden'); |
| 386 | `` | UI | loginButtonLoading.classList.remove('hidden'); |
| 389 | `` | UI | fetch(form.action, { |
| 399 | `` | UI | const contentType = response.headers.get("content-type"); |
| 411 | `` | UI | if (data.success) { |
| 418 | `Login` | UI | alert(data.message \|\| 'Login gagal'); |
| 420 | `` | UI | loginButtonText.classList.remove('hidden'); |
| 421 | `` | UI | loginButtonLoading.classList.add('hidden'); |
| 424 | `` | UI | .catch(error => { |
| 425 | `Error` | UI | console.error('Error:', error); |
| 426 | `` | UI | form.submit(); |
| 431 | `` | UI | document.getElementById('pinForm').addEventListener('submit', function(e) { |
| 442 | `` | UI | pinError.classList.add('hidden'); |
| 447 | `` | UI | verifyButtonText.classList.add('hidden'); |
| 448 | `` | UI | verifyButtonLoading.classList.remove('hidden'); |
| 451 | `` | UI | fetch(form.action, { |
| 461 | `` | UI | if (data.success) { |
| 465 | `` | UI | pinError.classList.remove('hidden'); |
| 467 | `` | UI | verifyButtonText.classList.remove('hidden'); |
| 468 | `` | UI | verifyButtonLoading.classList.add('hidden'); |
| 473 | `` | UI | .catch(error => { |
| 474 | `Error` | UI | console.error('Error:', error); |
| 476 | `` | UI | pinError.classList.remove('hidden'); |
| 478 | `` | UI | verifyButtonText.classList.remove('hidden'); |
| 479 | `` | UI | verifyButtonLoading.classList.add('hidden'); |
| 486 | `` | UI | modal.classList.remove('hidden'); |
| 494 | `` | UI | loginButtonText.classList.remove('hidden'); |
| 495 | `` | UI | loginButtonLoading.classList.add('hidden'); |
| 501 | `` | UI | modal.classList.add('hidden'); |
| 503 | `` | UI | document.getElementById('pinError').classList.add('hidden'); |
| 506 | `` | UI | fetch('{{ route("logout") }}', { |
| 520 | `` | UI | document.getElementById('pinForm').dispatchEvent(new Event('submit')); |

## ðŸ„ `auth\register.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 6 | `` | UI | <meta name="viewport" content="width=device-width, initial-scale=1"> |
| 118 | `` | UI | .progress-step.active { |
| 148 | `Detail` | UI | 2 => 'Detail Nasabah', |
| 165 | `` | UI | <div class="flex flex-col items-center progress-step {{ $subStep == $i ? 'active' : '' }}"> |
| 168 | `` | UI | <div class="flex items-center justify-center w-12 h-12 rounded-full {{ $subStep > $i ? 'bg-linear-to-br from-green-500 to-green-600' : ($subStep == $i ? 'bg-linear-to-br from-[#674c1d] to-[#8b6f2f]' : 'bg-gray-200') }} text-white transition-all duration-300 shadow-md {{ $subStep == $i ? 'ring-4 ring-[#d4af37]/30' : '' }}"> |
| 170 | `` | UI | <!-- Checkmark for completed --> |
| 196 | `` | UI | <div class="absolute inset-0 {{ $subStep > $i ? 'bg-linear-to-r from-green-500 to-green-600' : ($subStep == $i ? 'bg-linear-to-r from-[#674c1d] to-[#8b6f2f] w-1/2' : 'w-0') }} transition-all duration-500"></div> |
| 261 | `Success` | UI | <!-- Success Message --> |
| 262 | `` | UI | @if(session('success')) |
| 268 | `` | UI | <p class="text-green-800 font-medium text-sm">{{ session('success') }}</p> |
| 273 | `Error` | UI | <!-- Error Message --> |
| 274 | `` | UI | @if(session('error')) |
| 281 | `Error` | UI | <p class="text-red-800 font-semibold text-sm">Error</p> |
| 282 | `` | UI | <p class="text-red-700 text-sm">{{ session('error') }}</p> |
| 298 | `` | UI | @foreach($errors->all() as $error) |
| 301 | `` | UI | <span>{{ $error }}</span> |
| 310 | `` | UI | <form method="POST" action="{{ route('register.submit') }}" enctype="multipart/form-data" |
| 313 | `` | UI | <input type="hidden" name="step" value="{{ $step }}"> |
| 315 | `` | UI | <input type="hidden" name="substep" value="{{ $subStep }}"> |
| 318 | `` | UI | <input type="hidden" name="nama" value="{{ old('nama', $formData['nama'] ?? '') }}"> |
| 319 | `` | UI | <input type="hidden" name="email" value="{{ old('email', $formData['email'] ?? '') }}"> |
| 320 | `` | UI | <input type="hidden" name="nomor_hp" value="{{ old('nomor_hp', $formData['nomor_hp'] ?? '') }}"> |
| 334 | `` | UI | <input type="text" name="nama" id="nama" |
| 338 | `` | UI | @error('nama') |
| 345 | `` | UI | <label for="email" |
| 346 | `Email` | UI | class="block text-sm font-medium text-gray-700 mb-2">Email</label> |
| 347 | `` | UI | <input type="email" name="email" id="email" |
| 348 | `` | UI | value="{{ old('email', $formData['email'] ?? '') }}" |
| 350 | `` | UI | placeholder="nama@email.com"> |
| 351 | `` | UI | @error('email') |
| 359 | `` | UI | <input type="text" name="nomor_hp" id="nomor_hp" |
| 363 | `` | UI | @error('nomor_hp') |
| 371 | `` | UI | <label for="password" |
| 372 | `Password` | UI | class="block text-sm font-medium text-gray-700 mb-2">Password</label> |
| 374 | `` | UI | <input type="password" name="password" id="password" |
| 376 | `` | UI | placeholder="Minimal 8 karakter" value="{{ old('password') }}"> |
| 377 | `` | UI | <button type="button" onclick="togglePassword('password', 'passwordToggle')" |
| 389 | `` | UI | @error('password') |
| 396 | `Password` | UI | class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label> |
| 398 | `` | UI | <input type="password" name="password_confirmation" id="password_confirmation" |
| 400 | `` | UI | placeholder="Ulangi password" value="{{ old('password_confirmation') }}"> |
| 401 | `` | UI | <button type="button" |
| 414 | `` | UI | @error('password_confirmation') |
| 428 | `` | UI | <input type="file" name="foto" id="foto" accept="image/*" class="hidden" |
| 431 | `` | UI | <div id="fotoPreview" class="mt-2 {{ !empty($formData['foto']) && $formData['foto'] !== 'default-profile.jpg' ? '' : 'hidden' }}"> |
| 433 | `` | UI | src="{{ !empty($formData['foto']) && $formData['foto'] !== 'default-profile.jpg' ? asset('storage/' . $formData['foto']) : '' }}" |
| 441 | `Detail` | UI | <!-- Sub-step 2: Detail Nasabah --> |
| 443 | `Detail` | UI | <h3 class="text-xl font-bold text-[#674c1d] mb-4">Detail Nasabah</h3> |
| 447 | `` | UI | <input type="text" name="no_kk" id="no_kk" |
| 457 | `` | UI | <input type="text" name="tempat_lahir" id="tempat_lahir" |
| 466 | `` | UI | <input type="date" name="tanggal_lahir" id="tanggal_lahir" |
| 475 | `` | UI | <select name="jenis_kelamin" id="jenis_kelamin" |
| 484 | `` | UI | </select> |
| 490 | `` | UI | <textarea name="alamat" id="alamat" rows="3" |
| 502 | `Upload` | UI | <span class="text-sm text-gray-600">Upload Foto KTP</span> |
| 504 | `` | UI | <input type="file" name="foto_ktp" id="foto_ktp" accept="image/*" class="hidden" |
| 521 | `Upload` | UI | <span class="text-sm text-gray-600">Upload Foto KK</span> |
| 523 | `` | UI | <input type="file" name="foto_kk" id="foto_kk" accept="image/*" class="hidden" |
| 544 | `` | UI | <input type="text" name="pekerjaan" id="pekerjaan" |
| 554 | `` | UI | <select name="penghasilan" id="penghasilan" |
| 578 | `` | UI | </select> |
| 584 | `` | UI | <input type="text" name="nama_perusahaan" id="nama_perusahaan" |
| 600 | `` | UI | <input type="text" name="no_rekening" id="no_rekening" |
| 612 | `` | UI | <input type="text" name="nama_pemilik_rekening" id="nama_pemilik_rekening" |
| 621 | `` | UI | <select name="jenis_atm" id="jenis_atm" |
| 636 | `` | UI | </select> |
| 646 | `Notes` | UI | <!-- Notes untuk OCR KTP --> |
| 659 | `Upload` | UI | <label class="block text-sm font-medium text-gray-700 mb-2">Ambil/Upload Foto |
| 662 | `Upload` | UI | <!-- Camera/Upload Options --> |
| 664 | `` | UI | <button type="button" onclick="openCamera()" |
| 678 | `Upload` | UI | Upload dari File |
| 682 | `` | UI | <!-- Hidden file input for upload --> |
| 683 | `` | UI | <input type="file" name="file_ktp_upload" id="file_ktp_upload" accept="image/*" |
| 687 | `` | UI | <input type="file" id="file_ktp_camera" accept="image/*" capture="environment" |
| 696 | `` | UI | <button type="button" onclick="closeCamera()" |
| 716 | `` | UI | <button type="button" onclick="capturePhoto()" id="btnCapture" |
| 720 | `` | UI | <button type="button" onclick="retakePhoto()" id="btnRetake" |
| 724 | `` | UI | <button type="button" onclick="usePhoto()" id="btnUsePhoto" |
| 739 | `` | UI | <button type="button" onclick="removeKtpPhoto()" |
| 752 | `` | UI | <button type="button" onclick="processOcr()" id="btnOcr" disabled |
| 762 | `` | UI | <input type="hidden" name="file_ktp" id="file_ktp" |
| 769 | `` | UI | <input type="text" name="nik" id="nik" |
| 779 | `` | UI | <input type="text" name="nama_lengkap_ktp" id="nama_lengkap_ktp" |
| 790 | `` | UI | <input type="text" name="tempat_lahir_ktp" id="tempat_lahir_ktp" |
| 799 | `` | UI | <input type="date" name="tanggal_lahir_ktp" id="tanggal_lahir_ktp" |
| 808 | `` | UI | <select name="jenis_kelamin_ktp" id="jenis_kelamin_ktp" |
| 817 | `` | UI | </select> |
| 823 | `` | UI | <input type="text" name="rt_rw" id="rt_rw" |
| 831 | `` | UI | <input type="text" name="kel_desa" id="kel_desa" |
| 839 | `` | UI | <input type="text" name="kecamatan" id="kecamatan" |
| 846 | `` | UI | <input type="hidden" name="alamat_ktp" id="alamat_ktp" value=""> |
| 859 | `` | UI | <input type="text" name="darurat_nama_lengkap" id="darurat_nama_lengkap" |
| 868 | `` | UI | <select name="hubungan_peminjam" id="hubungan_peminjam" |
| 886 | `` | UI | </select> |
| 894 | `` | UI | <input type="text" name="darurat_no_telepon" id="darurat_no_telepon" |
| 903 | `Email` | UI | class="block text-sm font-medium text-gray-700 mb-2">Email</label> |
| 904 | `` | UI | <input type="email" name="darurat_email" id="darurat_email" |
| 907 | `` | UI | placeholder="email@example.com"> |
| 914 | `` | UI | <textarea name="darurat_alamat" id="darurat_alamat" rows="3" |
| 923 | `` | UI | <input type="text" name="darurat_pekerjaan" id="darurat_pekerjaan" |
| 932 | `` | UI | <input type="text" name="darurat_no_ktp" id="darurat_no_ktp" |
| 946 | `Upload` | UI | <span class="text-sm text-gray-600">Upload Foto KTP</span> |
| 948 | `` | UI | <input type="file" name="darurat_foto_ktp" id="darurat_foto_ktp" accept="image/*" |
| 965 | `` | UI | @if(session('error') && !session('success')) |
| 971 | `Error` | UI | <strong class="font-semibold">Error:</strong> |
| 972 | `` | UI | <p class="mt-0.5">{{ session('error') }}</p> |
| 978 | `` | UI | @if(session('success')) |
| 983 | `` | UI | <p>{{ session('success') }}</p> |
| 1009 | `` | UI | {{ session('register_phone') ?? $phone ?? 'Nomor HP tidak tersimpan — kembali ke Langkah 1 (Data Diri) dan isi nomor HP' }} |
| 1036 | `` | UI | <input type="hidden" name="send_otp" id="send_otp_input" value="0"> |
| 1039 | `` | UI | <button type="button" name="send_otp_btn" value="1" id="btnSendOtp" |
| 1041 | `` | UI | class="w-full px-6 py-4 bg-linear-to-r from-green-600 to-green-700 text-white rounded-xl hover:from-green-700 hover:to-green-800 transition-all font-bold text-lg flex items-center justify-center gap-3 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 active:translate-y-0"> |
| 1054 | `` | UI | <a href="{{ route('register', ['step' => 1, 'substep' => 1]) }}" |
| 1080 | `` | UI | Nomor WhatsApp: <strong class="font-semibold">{{ session('register_phone') ?? $phone ?? 'Nomor HP tidak tersimpan — kembali ke Langkah 1 (Data Diri) dan isi nomor HP' }}</strong> |
| 1099 | `` | UI | <input type="hidden" name="otp_code" id="otp_code_hidden" required> |
| 1103 | `` | UI | <input type="text" maxlength="1" |
| 1106 | `` | UI | <input type="text" maxlength="1" |
| 1109 | `` | UI | <input type="text" maxlength="1" |
| 1113 | `` | UI | <input type="text" maxlength="1" |
| 1116 | `` | UI | <input type="text" maxlength="1" |
| 1119 | `` | UI | <input type="text" maxlength="1" |
| 1124 | `` | UI | @error('otp_code') |
| 1144 | `` | UI | <button type="button" disabled |
| 1154 | `` | UI | <button type="submit" name="send_otp" value="1" |
| 1185 | `Create` | UI | <!-- Step 3: Create PIN --> |
| 1187 | `` | UI | @if(session('success')) |
| 1189 | `` | UI | {{ session('success') }} |
| 1193 | `` | UI | @if(session('error')) |
| 1195 | `` | UI | {{ session('error') }} |
| 1209 | `` | UI | <input type="password" name="pin" id="pin" maxlength="6" required |
| 1212 | `` | UI | @error('pin') |
| 1220 | `` | UI | <input type="password" name="pin_confirmation" id="pin_confirmation" maxlength="6" required |
| 1234 | `Navigation` | UI | <!-- Navigation Buttons --> |
| 1238 | `` | UI | <a href="{{ route('register', ['step' => 1, 'substep' => $subStep - 1]) }}" |
| 1243 | `` | UI | <a href="{{ route('login') }}" |
| 1245 | `Login` | UI | Kembali ke Login |
| 1249 | `` | UI | <a href="{{ route('register', ['step' => $step - 1]) }}" |
| 1254 | `` | UI | <a href="{{ route('login') }}" |
| 1256 | `Login` | UI | Kembali ke Login |
| 1261 | `` | UI | <button type="submit" |
| 1265 | `` | UI | <button type="submit" |
| 1270 | `` | UI | <button type="submit" |
| 1374 | `Login` | UI | <!-- Login Link --> |
| 1384 | `` | UI | <a href="{{ route('login') }}" class="mt-4 inline-flex items-center gap-2 text-[#674c1d] font-semibold hover:text-[#8b6f2f] transition-colors"> |
| 1406 | `` | UI | console.error('Invalid step:', step); |
| 1411 | `` | UI | let url = '{{ route("register") }}?step=' + step; |
| 1429 | `Upload` | UI | 'Browser Anda tidak mendukung akses kamera. Silakan gunakan opsi Upload dari File atau gunakan browser modern seperti Chrome, Firefox, atau Safari.' |
| 1441 | `` | UI | modal.classList.remove('hidden'); |
| 1442 | `` | UI | preview.classList.add('hidden'); |
| 1443 | `` | UI | btnRetake.classList.add('hidden'); |
| 1444 | `` | UI | btnUsePhoto.classList.add('hidden'); |
| 1445 | `` | UI | btnCapture.classList.remove('hidden'); |
| 1450 | `` | UI | facingMode: 'environment', // Use back camera on mobile |
| 1462 | `` | UI | video.classList.remove('hidden'); |
| 1465 | `Error` | UI | console.error('Error accessing camera:', err); |
| 1467 | `` | UI | if (err.name === 'NotAllowedError') { |
| 1469 | `` | UI | } else if (err.name === 'NotFoundError') { |
| 1472 | `Upload` | UI | errorMsg += 'Silakan gunakan opsi Upload dari File.'; |
| 1495 | `` | UI | video.classList.add('hidden'); |
| 1496 | `` | UI | preview.classList.add('hidden'); |
| 1497 | `` | UI | btnRetake.classList.add('hidden'); |
| 1498 | `` | UI | btnUsePhoto.classList.add('hidden'); |
| 1499 | `` | UI | btnCapture.classList.remove('hidden'); |
| 1500 | `` | UI | modal.classList.add('hidden'); |
| 1524 | `` | UI | preview.classList.remove('hidden'); |
| 1525 | `` | UI | btnCapture.classList.add('hidden'); |
| 1526 | `` | UI | btnRetake.classList.remove('hidden'); |
| 1527 | `` | UI | btnUsePhoto.classList.remove('hidden'); |
| 1532 | `` | UI | video.classList.add('hidden'); |
| 1545 | `` | UI | preview.classList.add('hidden'); |
| 1546 | `` | UI | btnRetake.classList.add('hidden'); |
| 1547 | `` | UI | btnUsePhoto.classList.add('hidden'); |
| 1548 | `` | UI | btnCapture.classList.remove('hidden'); |
| 1567 | `` | UI | video.classList.remove('hidden'); |
| 1570 | `Error` | UI | console.error('Error accessing camera:', err); |
| 1585 | `Date` | UI | const file = new File([capturedPhotoBlob], 'ktp-camera-' + Date.now() + '.jpg', { |
| 1586 | `` | UI | type: 'image/jpeg' |
| 1591 | `` | UI | dataTransfer.items.add(file); |
| 1608 | `` | UI | preview.classList.remove('hidden'); |
| 1618 | `` | UI | } catch (error) { |
| 1619 | `Error` | UI | console.error('Error using photo:', error); |
| 1632 | `` | UI | preview.classList.add('hidden'); |
| 1658 | `` | UI | preview.classList.remove('hidden'); |
| 1681 | `` | UI | alert('Silakan ambil atau upload foto KTP terlebih dahulu'); |
| 1695 | `` | UI | ocrText.classList.add('hidden'); |
| 1696 | `` | UI | ocrLoading.classList.remove('hidden'); |
| 1698 | `` | UI | fetch('{{ route("register.ocr") }}', { |
| 1704 | `` | UI | ocrText.classList.remove('hidden'); |
| 1705 | `` | UI | ocrLoading.classList.add('hidden'); |
| 1708 | `` | UI | if (data.success) { |
| 1732 | `` | UI | ocrResult.classList.remove('hidden'); |
| 1736 | `` | UI | (data.message \|\| 'Unknown error') + '. Silakan isi manual.</div>'; |
| 1737 | `` | UI | ocrResult.classList.remove('hidden'); |
| 1741 | `` | UI | .catch(error => { |
| 1742 | `` | UI | ocrText.classList.remove('hidden'); |
| 1743 | `` | UI | ocrLoading.classList.add('hidden'); |
| 1746 | `Error` | UI | '<div class="p-4 bg-red-50 border border-red-200 rounded-lg text-red-800 text-sm">Error: ' + |
| 1747 | `` | UI | error.message + '</div>'; |
| 1748 | `` | UI | ocrResult.classList.remove('hidden'); |
| 1764 | `` | UI | iconSend.classList.add('hidden'); |
| 1765 | `` | UI | iconLoading.classList.remove('hidden'); |
| 1772 | `` | UI | if (form) form.submit(); |
| 1856 | `` | UI | form.submit(); |
| 1900 | `` | UI | expiryElement.classList.add('text-red-600'); |
| 1901 | `` | UI | expiryElement.classList.remove('text-yellow-900'); |
| 1915 | `` | UI | expiryElement.classList.add('text-red-600', 'font-bold'); |
| 1990 | `` | UI | preview.classList.remove('hidden'); |
| 2000 | `` | UI | @if(!empty($formData['foto']) && $formData['foto'] !== 'default-profile.jpg') |
| 2005 | `` | UI | fotoPreview.classList.remove('hidden'); |
| 2015 | `` | UI | fotoKtpPreview.classList.remove('hidden'); |
| 2025 | `` | UI | fotoKkPreview.classList.remove('hidden'); |
| 2035 | `` | UI | daruratKtpPreview.classList.remove('hidden'); |
| 2046 | `` | UI | const input = document.querySelector(`[name="${key}"]`); |
| 2047 | `` | UI | if (input && input.type !== 'file') { |
| 2058 | `` | UI | if (input.type === "password") { |
| 2059 | `` | UI | input.type = "text"; |
| 2065 | `` | UI | input.type = "password"; |

## ðŸ„ `components\admin\header.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 7 | `Menu` | UI | <!-- Left Side: Menu Toggle & Breadcrumb --> |
| 9 | `Menu` | UI | <!-- Mobile Menu Button --> |
| 22 | `Dashboard` | UI | <span class="text-primary font-medium">@yield('title', 'Dashboard')</span> |
| 26 | `Profile` | UI | <!-- Right Side: Search, Notifications, Profile --> |
| 28 | `Search` | UI | <!-- Search --> |
| 30 | `` | UI | <input type="text" placeholder="Cari..." |
| 54 | `` | UI | <div x-show="open" x-cloak |
| 56 | `` | UI | x-transition:enter="transition ease-out duration-150" |
| 59 | `` | UI | x-transition:leave="transition ease-in duration-100" |
| 64 | `` | UI | <form method="POST" action="{{ route('admin.notifications.mark-all-read') }}" class="inline"> |
| 66 | `` | UI | <button type="submit" class="text-xs text-[#674c1d] hover:underline">Tandai semua dibaca</button> |
| 73 | `` | UI | <form method="POST" action="{{ route('admin.notifications.mark-read', $notif->id) }}" class="block border-b border-gray-50 last:border-0 {{ $notif->read_at ? '' : 'bg-[#674c1d]/5' }}"> |
| 75 | `` | UI | <input type="hidden" name="redirect" value="{{ $targetUrl }}"> |
| 76 | `` | UI | <button type="submit" class="w-full text-left px-4 py-3 hover:bg-gray-50 transition-colors"> |
| 94 | `Date` | UI | <!-- Date & Time --> |
| 100 | `Profile` | UI | <!-- Profile Dropdown --> |
| 114 | `` | UI | @elseif($roleBadge === 'warning') bg-yellow-100 text-yellow-800 |
| 125 | `Menu` | UI | <!-- Dropdown Menu --> |
| 126 | `` | UI | <div x-show="open" |
| 129 | `` | UI | x-transition:enter="transition ease-out duration-100" |
| 132 | `` | UI | x-transition:leave="transition ease-in duration-75" |
| 139 | `` | UI | <form method="POST" action="{{ route('logout') }}"> |
| 141 | `` | UI | <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100"> |

## ðŸ„ `components\admin\sidebar.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 15 | `` | PHP | $pendingTf  = \App\Models\PencairanDeposito::where('jenis_pencairan','rek_nasabah')->where('status','pending')->count(); |
| 16 | `` | PHP | $pendingTab = \App\Models\PencairanDeposito::where('jenis_pencairan','saldo_tabungan')->where('status','pending')->count(); |
| 24 | `` | UI | class="fixed lg:static inset-y-0 left-0 z-50 w-64 bg-white shadow-xl transform transition-transform duration-300 ease-in-out lg:translate-x-0 -translate-x-full"> |
| 40 | `Menu` | UI | <!-- Navigation Menu --> |
| 42 | `Dashboard` | UI | <!-- Dashboard --> |
| 43 | `` | UI | <a href="{{ route('admin.dashboard') }}" |
| 44 | `` | UI | class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ $isActive('admin.dashboard') }}"> |
| 52 | `Dashboard` | UI | <span class="font-medium">Dashboard</span> |
| 57 | `` | UI | <button type="button" @click="open = !open" |
| 58 | `` | UI | class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 {{ $isTabunganActive ? 'bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' }}"> |
| 69 | `` | UI | <svg class="w-5 h-5 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" |
| 74 | `` | UI | <div x-show="open" x-transition:enter="transition ease-out duration-200" |
| 80 | `Dashboard` | UI | Dashboard |
| 91 | `` | UI | class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs('admin.tabungan.transaksi') && !request('filter') ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}"> |
| 94 | `` | UI | <a href="{{ route('admin.tabungan.transaksi', ['filter' => 'saya']) }}" |
| 95 | `` | UI | class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs('admin.tabungan.transaksi') && request('filter') == 'saya' ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}"> |
| 107 | `` | UI | <button type="button" @click="open = !open" |
| 108 | `` | UI | class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 {{ $isPinjamanActive ? 'bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' }}"> |
| 118 | `` | UI | <svg class="w-5 h-5 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" |
| 123 | `` | UI | <div x-show="open" x-transition:enter="transition ease-out duration-200" |
| 129 | `Dashboard` | UI | Dashboard |
| 132 | `` | UI | class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ in_array($currentRoute, ['admin.pinjaman.pengajuan']) \|\| str_contains($currentRoute, 'admin.pinjaman.detail-pengajuan') ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}"> |
| 156 | `` | UI | <button type="button" @click="open = !open" |
| 157 | `` | UI | class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 {{ $isDepositoActive ? 'bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' }}"> |
| 168 | `` | UI | <svg class="w-5 h-5 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" |
| 173 | `` | UI | <div x-show="open" x-transition:enter="transition ease-out duration-200" |
| 179 | `Dashboard` | UI | Dashboard |
| 184 | `` | PHP | @php $pendingDeposito = \App\Models\PengajuanDeposito::where('status','1')->count(); @endphp |
| 220 | `` | PHP | $peringatanCount = \App\Models\DepositoPersiapanCair::whereIn('status', ['tentatif', 'diproses'])->count(); |
| 232 | `` | UI | <button type="button" @click="open = !open" |
| 233 | `` | UI | class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 {{ $isGadaiActive ? 'bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' }}"> |
| 244 | `` | UI | <svg class="w-5 h-5 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" |
| 249 | `` | UI | <div x-show="open" x-transition:enter="transition ease-out duration-200" |
| 255 | `Dashboard` | UI | Dashboard Gadai |
| 257 | `` | UI | <a href="{{ route('admin.gadai_baru.create') }}" |
| 258 | `` | UI | class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-colors {{ $currentRoute === 'admin.gadai_baru.create' ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}"> |
| 268 | `` | PHP | @php $pendingGadai = \App\Models\GadaiPengajuan::where('status','pending')->count(); @endphp |
| 278 | `` | UI | class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ $isActive('admin.janji-temu') }}"> |
| 296 | `` | UI | <button type="button" @click="open = !open" |
| 297 | `` | UI | class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 {{ $isPettyCashActive ? 'bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' }}"> |
| 320 | `` | UI | <svg class="w-5 h-5 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"> |
| 326 | `` | UI | <div x-show="open" |
| 327 | `` | UI | x-transition:enter="transition ease-out duration-200" |
| 334 | `` | UI | <a href="{{ route('admin.petty-cash.dashboard') }}" |
| 336 | `` | UI | {{ $currentRoute === 'admin.petty-cash.dashboard' ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}"> |
| 340 | `Dashboard` | UI | Dashboard |
| 342 | `` | UI | <a href="{{ route('admin.petty-cash.penerimaan.create') }}" |
| 375 | `` | UI | <a href="{{ route('admin.petty-cash.dashboard') }}" |
| 377 | `` | UI | {{ $currentRoute === 'admin.petty-cash.dashboard' ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}"> |
| 381 | `Dashboard` | UI | Dashboard |
| 412 | `` | UI | <button type="button" @click="open = !open" |
| 413 | `` | UI | class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 {{ $isLaporanActive ? 'bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' }}"> |
| 424 | `` | UI | <svg class="w-5 h-5 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" |
| 429 | `` | UI | <div x-show="open" x-transition:enter="transition ease-out duration-200" |
| 473 | `` | UI | class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ $isActive('admin.nasabah.index') }}"> |
| 488 | `` | UI | <a href="{{ route('admin.nasabah.pending-changes') }}" |
| 489 | `` | UI | class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ $isActive('admin.nasabah.pending-changes') }}"> |
| 515 | `` | UI | class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ $isActive('admin.master-data') }}"> |
| 529 | `` | UI | <button type="button" @click="open = !open" |
| 530 | `` | UI | class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 {{ $isActivityLogActive ? 'bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' }}"> |
| 541 | `` | UI | <svg class="w-5 h-5 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" |
| 546 | `` | UI | <div x-show="open" x-transition:enter="transition ease-out duration-200" |
| 563 | `Profile` | UI | <!-- User Profile Section --> |

## ðŸ„ `components\nasabah\bottom-navbar.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 15 | `` | UI | if ($route === 'dashboard') return $currentRoute === 'nasabah.dashboard'; |
| 21 | `Dashboard` | UI | ['key' => 'dashboard', 'route' => 'nasabah.dashboard', 'label' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'], |
| 27 | `Settings` | UI | ['key' => 'setting', 'route' => 'nasabah.setting.index', 'label' => 'Settings', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'], |
| 29 | `` | PHP | $arcItems = array_values(array_filter($allNavItems, fn($i) => !in_array($i['key'], ['dashboard', 'setting']))); |
| 40 | `` | UI | <nav id="bottomNavbar" class="fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-sm border-t border-gray-200/80 shadow-[0_-4px_20px_rgba(103,76,29,0.08)] z-50 transition-transform duration-300"> |
| 47 | `` | PHP | @php $active = $navActive($item['key']); @endphp |
| 49 | `` | UI | class="nav-item group relative z-10 flex flex-col items-center justify-end min-w-[48px] px-1 py-2 rounded-2xl transition-colors duration-200 {{ $active ? 'text-[#8b6f2f]' : 'text-gray-500 hover:text-gray-700' }}" |
| 51 | `` | UI | @if($active) data-nav-active="1" @endif> |
| 52 | `` | UI | <span class="flex items-center justify-center w-12 h-10 rounded-2xl transition-all duration-300 ease-out {{ $active ? 'scale-110 -translate-y-1.5' : 'group-hover:scale-105 group-hover:-translate-y-0.5' }}"> |
| 53 | `` | UI | <svg class="w-6 h-6 transition-transform duration-300 {{ $active ? 'scale-105' : '' }}" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="{{ $item['icon'] }}"></path></svg> |
| 55 | `` | UI | <span class="text-xs font-medium mt-1.5 block transition-colors duration-200 {{ $active ? 'text-[#8b6f2f] font-semibold' : '' }}">{{ $item['label'] }}</span> |
| 62 | `` | UI | <a href="{{ route('nasabah.dashboard') }}" class="flex flex-col items-center gap-0.5 text-gray-500 hover:text-[#8b6f2f] transition-colors {{ $currentRoute === 'nasabah.dashboard' ? 'text-[#8b6f2f]' : '' }}"> |
| 64 | `Dashboard` | UI | <span class="text-xs font-medium">Dashboard</span> |
| 66 | `Menu` | UI | <button type="button" id="burgerMenuBtn" class="flex flex-col items-center gap-0.5 text-[#8b6f2f] focus:outline-none focus:ring-2 focus:ring-[#8b6f2f]/30 rounded-2xl px-2 py-1 transition-transform active:scale-95" aria-expanded="false" aria-label="Menu layanan"> |
| 71 | `Menu` | UI | <span class="text-xs font-medium mt-1">Menu</span> |
| 75 | `Settings` | UI | <span class="text-xs font-medium">Settings</span> |
| 82 | `` | UI | <div id="burgerArcBackdrop" class="md:hidden fixed inset-0 z-40 bg-black/15 opacity-0 pointer-events-none transition-opacity duration-300" aria-hidden="true"></div> |
| 87 | `` | UI | class="arc-item absolute left-1/2 top-full flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-br from-[#a67c52]/90 to-[#8b6f2f]/95 text-white border-2 border-[#674c1d]/30 shadow-[0_4px_16px_rgba(139,111,47,0.35)] hover:scale-110 hover:shadow-[0_8px_24px_rgba(103,76,29,0.45)] hover:border-[#d4af37]/60 hover:from-[#8b6f2f] hover:to-[#674c1d] hover:ring-2 hover:ring-[#d4af37]/40 active:scale-95 transition-all duration-200 ease-out" |
| 124 | `` | UI | burgerArcMenu.classList.add('open'); |
| 126 | `` | UI | burgerArcBackdrop.classList.remove('pointer-events-none', 'opacity-0'); |
| 127 | `` | UI | burgerArcBackdrop.classList.add('opacity-100'); |
| 128 | `` | UI | if (burgerIconOpen) burgerIconOpen.classList.add('hidden'); |
| 129 | `` | UI | if (burgerIconClose) burgerIconClose.classList.remove('hidden'); |
| 135 | `` | UI | burgerArcMenu.classList.remove('open'); |
| 137 | `` | UI | burgerArcBackdrop.classList.add('pointer-events-none', 'opacity-0'); |
| 138 | `` | UI | burgerArcBackdrop.classList.remove('opacity-100'); |
| 139 | `` | UI | if (burgerIconOpen) burgerIconOpen.classList.remove('hidden'); |
| 140 | `` | UI | if (burgerIconClose) burgerIconClose.classList.add('hidden'); |
| 181 | `` | UI | var activeLink = navInner.querySelector('[data-nav-active="1"]'); |
| 212 | `` | UI | if (document.readyState === 'loading') { |

## ðŸ„ `components\nasabah\bottom-navbar-icon.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 6 | `` | UI | @case('dashboard') |

## ðŸ„ `components\nasabah\data-akun.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 8 | `` | UI | 'email' => 'ahmad.rizki@example.com', |
| 22 | `Profile` | UI | <!-- Profile Section (Left) --> |
| 27 | `` | UI | @if($user->foto && $user->foto !== 'default-profile.jpg') |
| 34 | `Status` | UI | <!-- Nama dan Status --> |
| 43 | `Email` | UI | <!-- Email --> |
| 45 | `` | UI | class="bg-gray-50 rounded-xl p-4 border border-gray-100 hover:shadow-md transition-all duration-200"> |
| 56 | `Email` | UI | <p class="text-xs text-gray-500 mb-0.5">Email</p> |
| 57 | `` | UI | <p class="text-sm font-semibold text-gray-900 truncate">{{ $user->email }}</p> |
| 64 | `` | UI | class="bg-gray-50 rounded-xl p-4 border border-gray-100 hover:shadow-md transition-all duration-200"> |
| 83 | `` | UI | class="bg-gray-50 rounded-xl p-4 border border-gray-100 hover:shadow-md transition-all duration-200"> |
| 104 | `` | UI | class="bg-gray-50 rounded-xl p-4 border border-gray-100 hover:shadow-md transition-all duration-200"> |

## ðŸ„ `components\nasabah\header.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 15 | `` | UI | <img src="{{ asset('images/logo/logo_coklat.png') }}" alt="Logo" class="h-16 w-auto object-contain" style="mix-blend-mode: multiply; filter: brightness(1.1) contrast(1.2);"> |
| 19 | `Welcome` | UI | <!-- Welcome Text --> |
| 49 | `` | UI | <div class="absolute right-0 mt-1 w-80 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-[100] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform group-hover:translate-y-0 -translate-y-1"> |
| 53 | `` | UI | <form method="POST" action="{{ route('nasabah.notifications.mark-all-read') }}" class="inline"> |
| 55 | `` | UI | <button type="submit" class="text-xs text-[#674c1d] hover:underline">Tandai semua dibaca</button> |
| 62 | `` | UI | <form method="POST" action="{{ route('nasabah.notifications.mark-read', $notif->id) }}" class="block border-b border-gray-50 last:border-0 {{ $notif->read_at ? '' : 'bg-[#674c1d]/5' }}"> |
| 64 | `` | UI | <input type="hidden" name="redirect" value="{{ $targetUrl }}"> |
| 65 | `` | UI | <button type="submit" class="w-full text-left px-4 py-3 hover:bg-gray-100 transition-colors rounded-none"> |
| 83 | `Profile` | UI | <!-- Profile Dropdown --> |
| 91 | `Menu` | UI | <!-- Dropdown Menu --> |
| 92 | `` | UI | <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 transform group-hover:translate-y-0 -translate-y-2"> |
| 94 | `Profile` | UI | <!-- Profile Link --> |
| 95 | `` | UI | <a href="{{ route('nasabah.profile') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-[#674c1d] hover:text-white transition-colors"> |
| 100 | `Profile` | UI | <span>Profile</span> |
| 107 | `Logout` | UI | <!-- Logout Button --> |
| 108 | `` | UI | <form action="{{ route('logout') }}" method="POST" class="block"> |
| 110 | `` | UI | <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors"> |
| 115 | `Logout` | UI | <span>Logout</span> |
| 123 | `Date` | UI | <!-- Date and Time --> |

## ðŸ„ `components\nasabah\info-cards.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 11 | `` | UI | <div class="bg-white rounded-2xl shadow-md p-5 hover:shadow-lg transition-all duration-300 border border-gray-100"> |
| 26 | `` | UI | <div class="bg-white rounded-2xl shadow-md p-5 hover:shadow-lg transition-all duration-300 border border-gray-100"> |
| 41 | `` | UI | <div class="bg-white rounded-2xl shadow-md p-5 hover:shadow-lg transition-all duration-300 border border-gray-100"> |
| 56 | `` | UI | <div class="bg-white rounded-2xl shadow-md p-5 hover:shadow-lg transition-all duration-300 border border-gray-100"> |

## ðŸ„ `components\nasabah\table-section.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 13 | `` | UI | <div class="bg-white rounded-2xl shadow-md p-5 hover:shadow-lg transition-all duration-300 border border-gray-100"> |
| 32 | `Status` | UI | <span class="text-xs text-gray-600">Status</span> |
| 36 | `Detail` | UI | <a href="#" class="block text-center text-xs text-primary hover:text-[#4a3514] transition-colors font-medium">Lihat Detail →</a> |
| 40 | `` | UI | <div class="bg-white rounded-2xl shadow-md p-5 hover:shadow-lg transition-all duration-300 border border-gray-100"> |
| 51 | `Total` | UI | <span class="text-xs text-gray-600">Total Pinjaman</span> |
| 59 | `Status` | UI | <span class="text-xs text-gray-600">Status</span> |
| 67 | `` | UI | <div class="bg-white rounded-2xl shadow-md p-5 hover:shadow-lg transition-all duration-300 border border-gray-100"> |
| 90 | `Detail` | UI | <a href="#" class="block text-center text-xs text-primary hover:text-[#4a3514] transition-colors font-medium">Lihat Detail →</a> |
| 95 | `` | UI | <div class="bg-white rounded-2xl shadow-md p-5 hover:shadow-lg transition-all duration-300 border border-gray-100"> |
| 106 | `Total` | UI | <span class="text-xs text-gray-600">Total Gadai</span> |
| 114 | `Status` | UI | <span class="text-xs text-gray-600">Status</span> |

## ðŸ„ `components\nasabah\tabungan\filter-tabungan.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 2 | `` | UI | 'action' => '', |
| 11 | `` | UI | <form action="{{ $action }}" method="GET" class="flex flex-col sm:flex-row gap-3 items-end w-full"> |
| 12 | `Filter` | UI | <!-- Filter Inputs --> |
| 21 | `` | UI | <input type="date" |
| 22 | `` | UI | name="{{ $nameTanggal }}" |
| 35 | `` | UI | <input type="number" |
| 36 | `` | UI | name="{{ $nameJumlah }}" |
| 49 | `` | UI | <input type="text" |
| 50 | `` | UI | name="{{ $nameId }}" |
| 57 | `Action` | UI | <!-- Action Buttons --> |
| 59 | `` | UI | <button type="submit" class="w-12 h-12 bg-linear-to-br from-[#674c1d] to-[#4a3514] text-white rounded-xl hover:from-[#4a3514] hover:to-[#674c1d] transition-all flex items-center justify-center shadow-md hover:shadow-lg transform hover:scale-105"> |
| 64 | `` | UI | <a href="{{ $action }}" class="w-12 h-12 bg-linear-to-br from-[#8b6f2f] to-[#d4af37] text-white rounded-xl hover:from-[#d4af37] hover:to-[#8b6f2f] transition-all flex items-center justify-center shadow-md hover:shadow-lg transform hover:scale-105"> |

## ðŸ„ `components\nasabah\tabungan\table-riwayat.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 27 | `` | UI | @elseif($key === 'status') |
| 71 | `` | PHP | $date = \Carbon\Carbon::parse($row->{$key}); |
| 72 | `` | UI | echo $date->format('d M Y'); |
| 107 | `` | UI | @elseif($key === 'status' && isset($row->{$key})) |
| 110 | `` | UI | 'menunggu', 'pending' => ['bg' => 'bg-[#d4af37]/20', 'text' => 'text-[#8b6f2f]', 'border' => 'border-[#d4af37]'], |
| 111 | `` | UI | 'selesai', 'completed', 'aktif' => ['bg' => 'bg-[#674c1d]/20', 'text' => 'text-[#674c1d]', 'border' => 'border-[#674c1d]'], |

## ðŸ„ `components\photo-preview-modal.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 4 | `Close` | UI | <!-- Close Button --> |
| 33 | `` | UI | document.getElementById('preview-info').classList.remove('hidden'); |
| 35 | `` | UI | document.getElementById('preview-info').classList.add('hidden'); |
| 38 | `` | UI | modal.classList.remove('hidden'); |
| 39 | `` | UI | modal.classList.add('flex'); |
| 49 | `` | UI | modal.classList.add('hidden'); |
| 50 | `` | UI | modal.classList.remove('flex'); |

## ðŸ„ `landing\faq.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 5 | `` | UI | <meta name="viewport" content="width=device-width, initial-scale=1"> |
| 44 | `` | UI | .faq-item.active .faq-content { |
| 49 | `` | UI | .faq-item.active { |
| 54 | `` | UI | .category-btn.active { |
| 67 | `` | UI | <a href="{{ route('welcome') }}"> |
| 68 | `` | UI | <img src="{{ asset('images/logo/logo_coklat.png') }}" alt="Logo Kospin Majakara" class="h-16 w-auto object-contain" style="mix-blend-mode: multiply; filter: brightness(1.1) contrast(1.2);"> |
| 70 | `` | UI | <a href="{{ route('welcome') }}" class="text-xl font-bold text-primary">Kospin Majakara</a> |
| 74 | `` | UI | <a href="{{ route('welcome') }}#beranda" class="text-gray-700 hover:text-primary transition">Beranda</a> |
| 82 | `` | UI | @if (Route::has('login')) |
| 86 | `` | PHP | $dashboardUrl = $user->role === 'nasabah' ? route('nasabah.dashboard') : '/admin/dashboard'; |
| 92 | `Dashboard` | UI | <span>Dashboard</span> |
| 95 | `Login` | UI | <a href="{{ route('login') }}" class="px-5 py-2.5 text-primary border border-primary rounded-lg hover:bg-primary hover:text-white transition font-medium">Login</a> |
| 96 | `` | UI | @if (Route::has('register')) |
| 97 | `Register` | UI | <a href="{{ route('register') }}" class="px-6 py-2.5 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[#4a3514] hover:to-[#674c1d] transition shadow-md font-medium">Register</a> |
| 103 | `` | UI | <button id="mobile-menu-btn" class="md:hidden p-2 text-gray-700"> |
| 111 | `Menu` | UI | <!-- Mobile Menu --> |
| 112 | `` | UI | <div id="mobile-menu" class="hidden md:hidden bg-white border-t"> |
| 114 | `` | UI | <a href="{{ route('welcome') }}#beranda" class="block text-gray-700 hover:text-primary">Beranda</a> |
| 126 | `` | UI | <div class="absolute top-20 left-10 w-96 h-96 bg-primary rounded-full filter blur-3xl"></div> |
| 127 | `` | UI | <div class="absolute bottom-10 right-10 w-96 h-96 bg-accent rounded-full filter blur-3xl"></div> |
| 144 | `Search` | UI | <!-- Search Box --> |
| 147 | `` | UI | <input type="text" id="searchFaq" placeholder="Cari pertanyaan..." |
| 155 | `Filter` | UI | <!-- Category Filter --> |
| 157 | `` | UI | <button onclick="filterCategory('all')" class="category-btn active px-6 py-3 bg-white border-2 border-gray-200 rounded-xl font-semibold text-gray-700 hover:border-[#674c1d] transition-all" data-category="all"> |
| 201 | `Register` | UI | <li class="pl-2">Klik tombol "Register" di halaman utama</li> |
| 202 | `` | UI | <li class="pl-2">Isi formulir dengan data lengkap (nama, email, nomor HP, password)</li> |
| 203 | `Upload` | UI | <li class="pl-2">Upload foto KTP - sistem akan otomatis membaca data KTP (OCR)</li> |
| 240 | `` | UI | <li class="pl-2">Transparan dan dapat dilihat di dashboard</li> |
| 244 | `` | UI | Untuk informasi detail suku bunga terkini, silakan login ke dashboard atau hubungi customer service kami. |
| 354 | `` | UI | <p class="text-gray-600 mb-4">Setelah verifikasi selesai, Anda dapat melakukan transaksi melalui dashboard:</p> |
| 364 | `` | UI | <p class="text-sm text-gray-600">Via transfer (upload bukti) atau tunai (janji temu)</p> |
| 375 | `` | UI | <p class="text-sm text-gray-600">Isi form, lihat simulasi, submit dengan PIN</p> |
| 391 | `` | UI | ✅ Semua transaksi dapat dilacak status-nya secara real-time di dashboard! |
| 535 | `` | UI | <li class="pl-2">Ajukan dengan nominal yang diinginkan + upload foto barang</li> |
| 536 | `` | UI | <li class="pl-2">Admin menilai barang dan approve</li> |
| 584 | `` | UI | 💡 Jadwal angsuran dapat dilihat di dashboard dan Anda akan mendapat notifikasi sebelum jatuh tempo. |
| 590 | `Customer` | UI | <!-- FAQ 10: Kontak Customer Service --> |
| 599 | `` | UI | <span class="font-bold text-lg text-primary pr-4">Bagaimana cara menghubungi customer service?</span> |
| 630 | `Email` | UI | <p class="font-semibold text-gray-900">Email</p> |
| 677 | `` | UI | Tim customer service kami siap membantu menjawab pertanyaan Anda |
| 690 | `Email` | UI | <span>Kirim Email</span> |
| 723 | `` | UI | <li><a href="{{ route('welcome') }}" class="hover:text-white transition">Beranda</a></li> |
| 729 | `Email` | UI | <li>Email: info@majakara.com</li> |
| 736 | `All rights reserved` | UI | <p class="text-white/80 text-sm">Copyright © 2026 Koperasi Majakara. All rights reserved.</p> |
| 742 | `` | UI | document.getElementById('mobile-menu-btn').addEventListener('click', function() { |
| 743 | `` | UI | document.getElementById('mobile-menu').classList.toggle('hidden'); |
| 750 | `` | UI | const isActive = faqItem.classList.contains('active'); |
| 754 | `` | UI | item.classList.remove('active'); |
| 755 | `` | UI | item.querySelectorAll('svg:last-child').forEach(svg => svg.classList.remove('rotate-180')); |
| 760 | `` | UI | faqItem.classList.add('active'); |
| 761 | `` | UI | icon.classList.add('rotate-180'); |
| 772 | `` | UI | btn.classList.remove('active'); |
| 774 | `` | UI | btn.classList.add('active'); |
| 785 | `` | UI | faq.classList.remove('active'); |
| 786 | `` | UI | faq.querySelectorAll('svg:last-child').forEach(svg => svg.classList.remove('rotate-180')); |

## ðŸ„ `landing\keuntungan.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 6 | `` | UI | <meta name="viewport" content="width=device-width, initial-scale=1"> |
| 62 | `` | UI | <a href="{{ route('welcome') }}"> |
| 65 | `` | UI | style="mix-blend-mode: multiply; filter: brightness(1.1) contrast(1.2);"> |
| 67 | `` | UI | <a href="{{ route('welcome') }}" class="text-xl font-bold text-primary">Kospin Majakara</a> |
| 71 | `` | UI | <a href="{{ route('welcome') }}#beranda" |
| 83 | `` | UI | @if (Route::has('login')) |
| 87 | `` | PHP | $dashboardUrl = $user->role === 'nasabah' ? route('nasabah.dashboard') : '/admin/dashboard'; |
| 97 | `Dashboard` | UI | <span>Dashboard</span> |
| 100 | `` | UI | <a href="{{ route('login') }}" |
| 101 | `Login` | UI | class="px-5 py-2.5 text-primary border border-primary rounded-lg hover:bg-primary hover:text-white transition font-medium">Login</a> |
| 102 | `` | UI | @if (Route::has('register')) |
| 103 | `` | UI | <a href="{{ route('register') }}" |
| 104 | `Register` | UI | class="px-6 py-2.5 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[#4a3514] hover:to-[#674c1d] transition shadow-md font-medium">Register</a> |
| 110 | `` | UI | <button id="mobile-menu-btn" class="md:hidden p-2 text-gray-700"> |
| 119 | `Menu` | UI | <!-- Mobile Menu --> |
| 120 | `` | UI | <div id="mobile-menu" class="hidden md:hidden bg-white border-t"> |
| 122 | `` | UI | <a href="{{ route('welcome') }}#beranda" class="block text-gray-700 hover:text-primary">Beranda</a> |
| 136 | `` | UI | <div class="absolute top-20 left-10 w-96 h-96 bg-primary rounded-full filter blur-3xl"></div> |
| 137 | `` | UI | <div class="absolute bottom-10 right-10 w-96 h-96 bg-accent rounded-full filter blur-3xl"></div> |
| 317 | `` | UI | <p class="text-gray-600">Ajukan dari mana saja, kapan saja melalui dashboard</p> |
| 359 | `` | UI | <p class="text-gray-600">Update status langsung ke dashboard Anda</p> |
| 407 | `` | UI | <p class="text-gray-700 font-medium">Tracking status real-time di dashboard</p> |
| 415 | `Upload` | UI | <p class="text-gray-700 font-medium">Upload dokumen digital, tidak perlu fotokopi</p> |
| 575 | `Dashboard` | UI | <h4 class="font-semibold text-gray-900">Dashboard Interaktif</h4> |
| 590 | `` | UI | <p class="text-gray-600 text-sm">Pengingat jatuh tempo & update status</p> |
| 598 | `Dashboard` | UI | <h3 class="text-2xl font-bold text-primary mb-8 text-center">Fitur Dashboard</h3> |
| 712 | `Customer` | UI | <h3 class="text-xl font-bold text-gray-900 mb-3">Customer Service Responsif</h3> |
| 793 | `` | UI | <a href="{{ route('register') }}" |
| 797 | `` | UI | <a href="{{ route('login') }}" |
| 805 | `` | PHP | $dashboardUrl = $user->role === 'nasabah' ? route('nasabah.dashboard') : '/admin/dashboard'; |
| 809 | `Dashboard` | UI | Akses Dashboard Saya |
| 848 | `` | UI | <li><a href="{{ route('welcome') }}" class="hover:text-white transition">Tentang Kami</a></li> |
| 854 | `Email` | UI | <li>Email: info@majakara.com</li> |
| 861 | `All rights reserved` | UI | <p class="text-white/80 text-sm">Copyright © 2026 Koperasi Majakara. All rights reserved.</p> |
| 867 | `` | UI | document.getElementById('mobile-menu-btn').addEventListener('click', function() { |
| 868 | `` | UI | document.getElementById('mobile-menu').classList.toggle('hidden'); |

## ðŸ„ `landing\layanan.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 5 | `` | UI | <meta name="viewport" content="width=device-width, initial-scale=1"> |
| 84 | `` | UI | <a href="{{ route('welcome') }}"> |
| 85 | `` | UI | <img src="{{ asset('images/logo/logo_coklat.png') }}" alt="Logo Kospin Majakara" class="h-16 w-auto object-contain" style="mix-blend-mode: multiply; filter: brightness(1.1) contrast(1.2);"> |
| 87 | `` | UI | <a href="{{ route('welcome') }}" class="text-xl font-bold text-primary">Kospin Majakara</a> |
| 90 | `Navigation` | UI | <!-- Desktop Navigation --> |
| 92 | `` | UI | <a href="{{ route('welcome') }}#beranda" class="text-gray-700 hover:text-primary transition">Beranda</a> |
| 101 | `` | UI | @if (Route::has('login')) |
| 105 | `` | PHP | $dashboardUrl = $user->role === 'nasabah' ? route('nasabah.dashboard') : '/admin/dashboard'; |
| 111 | `Dashboard` | UI | <span>Dashboard</span> |
| 114 | `` | UI | <a href="{{ route('login') }}" class="px-5 py-2.5 text-primary border border-primary rounded-lg hover:bg-primary hover:text-white transition font-medium"> |
| 115 | `Login` | UI | Login |
| 117 | `` | UI | @if (Route::has('register')) |
| 118 | `` | UI | <a href="{{ route('register') }}" class="px-6 py-2.5 bg-primary text-white rounded-lg hover:bg-primary-dark transition shadow-md font-medium"> |
| 119 | `Register` | UI | Register |
| 126 | `Menu` | UI | <!-- Mobile Menu Button --> |
| 127 | `` | UI | <button id="mobile-menu-btn" class="md:hidden p-2 text-gray-700"> |
| 135 | `Menu` | UI | <!-- Mobile Menu --> |
| 136 | `` | UI | <div id="mobile-menu" class="hidden md:hidden bg-white border-t"> |
| 138 | `` | UI | <a href="{{ route('welcome') }}#beranda" class="block text-gray-700 hover:text-primary">Beranda</a> |
| 144 | `` | UI | @if (Route::has('login')) |
| 148 | `` | PHP | $dashboardUrl = $user->role === 'nasabah' ? route('nasabah.dashboard') : '/admin/dashboard'; |
| 154 | `Dashboard` | UI | <span>Dashboard</span> |
| 157 | `` | UI | <a href="{{ route('login') }}" class="block px-4 py-2.5 text-center text-primary border border-primary rounded-lg hover:bg-primary hover:text-white transition font-medium"> |
| 158 | `Login` | UI | Login |
| 160 | `` | UI | @if (Route::has('register')) |
| 161 | `` | UI | <a href="{{ route('register') }}" class="block px-4 py-2.5 text-center bg-primary text-white rounded-lg hover:bg-primary-dark transition font-medium shadow-md"> |
| 162 | `Register` | UI | Register |
| 176 | `` | UI | <div class="absolute top-20 right-20 w-96 h-96 bg-primary rounded-full filter blur-3xl"></div> |
| 177 | `` | UI | <div class="absolute bottom-20 left-20 w-96 h-96 bg-accent rounded-full filter blur-3xl"></div> |
| 275 | `` | UI | <p class="text-gray-600 text-sm">Mudah setor dan tarik kapan saja melalui transfer atau janji temu</p> |
| 299 | `` | UI | <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-4 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl hover:shadow-xl transition-all font-semibold group"> |
| 308 | `Process` | UI | <!-- Tabungan Process --> |
| 324 | `` | UI | <p class="text-gray-600 text-sm">Pilih metode setoran: transfer atau tunai dengan janji temu</p> |
| 330 | `Approve` | UI | <h4 class="font-bold text-gray-900 mb-1">Admin Approve</h4> |
| 352 | `Process` | UI | <!-- Pinjaman Process --> |
| 374 | `Approve` | UI | <h4 class="font-bold text-gray-900 mb-1">Review & Approve</h4> |
| 405 | `` | UI | <div class="absolute top-0 left-0 w-32 h-32 bg-[#d4af37]/20 rounded-full filter blur-2xl"></div> |
| 444 | `` | UI | <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-4 bg-[#8b6f2f] text-white rounded-xl hover:bg-[#674c1d] transition-all font-semibold"> |
| 517 | `` | UI | <a href="{{ route('register') }}" class="mt-8 inline-flex items-center px-8 py-4 bg-linear-to-r from-[#d4af37] to-[#8b6f2f] text-white rounded-xl hover:shadow-xl transition-all font-semibold group"> |
| 621 | `` | UI | <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-4 bg-linear-to-r from-[#8b6f2f] to-[#d4af37] text-white rounded-xl hover:shadow-xl transition-all font-semibold"> |
| 645 | `Upload` | UI | <h4 class="font-bold text-gray-900 mb-1">Ajukan & Upload Foto</h4> |
| 646 | `` | UI | <p class="text-gray-600 text-sm">Isi form dengan detail barang dan nominal yang diinginkan</p> |
| 653 | `` | UI | <p class="text-gray-600 text-sm">Admin menilai barang dan approve pengajuan</p> |
| 810 | `` | UI | <a href="{{ route('register') }}" class="px-8 py-4 bg-white text-primary rounded-xl font-semibold hover:bg-gray-100 transition shadow-lg hover:shadow-2xl"> |
| 813 | `` | UI | <a href="{{ route('login') }}" class="px-8 py-4 border-2 border-white text-white rounded-xl font-semibold hover:bg-white/10 transition"> |
| 820 | `` | PHP | $dashboardUrl = $user->role === 'nasabah' ? route('nasabah.dashboard') : '/admin/dashboard'; |
| 823 | `Dashboard` | UI | Masuk ke Dashboard |
| 833 | `Description` | UI | <!-- Logo & Description --> |
| 836 | `` | UI | <img src="{{ asset('images/logo/logo_putih.png') }}" alt="Logo Koperasi Majakara" class="h-16 w-auto object-contain" style="mix-blend-mode: multiply; filter: brightness(1.1) contrast(1.2);"> |
| 862 | `` | UI | <li><a href="{{ route('welcome') }}" class="hover:text-white transition">Tentang Kami</a></li> |
| 870 | `Email` | UI | <li>Email: info@majakara.com</li> |
| 880 | `All rights reserved` | UI | Copyright © 2026 Koperasi Majakara. All rights reserved. |
| 888 | `` | UI | document.getElementById('mobile-menu-btn').addEventListener('click', function() { |
| 889 | `` | UI | const menu = document.getElementById('mobile-menu'); |
| 890 | `` | UI | menu.classList.toggle('hidden'); |

## ðŸ„ `landing\testimoni.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 5 | `` | UI | <meta name="viewport" content="width=device-width, initial-scale=1"> |
| 32 | `` | UI | backdrop-filter: blur(10px); |
| 61 | `` | UI | <a href="{{ route('welcome') }}"> |
| 62 | `` | UI | <img src="{{ asset('images/logo/logo_coklat.png') }}" alt="Logo Kospin Majakara" class="h-16 w-auto object-contain" style="mix-blend-mode: multiply; filter: brightness(1.1) contrast(1.2);"> |
| 64 | `` | UI | <a href="{{ route('welcome') }}" class="text-xl font-bold text-primary">Kospin Majakara</a> |
| 68 | `` | UI | <a href="{{ route('welcome') }}#beranda" class="text-gray-700 hover:text-primary transition">Beranda</a> |
| 76 | `` | UI | @if (Route::has('login')) |
| 80 | `` | PHP | $dashboardUrl = $user->role === 'nasabah' ? route('nasabah.dashboard') : '/admin/dashboard'; |
| 86 | `Dashboard` | UI | <span>Dashboard</span> |
| 89 | `Login` | UI | <a href="{{ route('login') }}" class="px-5 py-2.5 text-primary border border-primary rounded-lg hover:bg-primary hover:text-white transition font-medium">Login</a> |
| 90 | `` | UI | @if (Route::has('register')) |
| 91 | `Register` | UI | <a href="{{ route('register') }}" class="px-6 py-2.5 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[#4a3514] hover:to-[#674c1d] transition shadow-md font-medium">Register</a> |
| 97 | `` | UI | <button id="mobile-menu-btn" class="md:hidden p-2 text-gray-700"> |
| 105 | `Menu` | UI | <!-- Mobile Menu --> |
| 106 | `` | UI | <div id="mobile-menu" class="hidden md:hidden bg-white border-t"> |
| 108 | `` | UI | <a href="{{ route('welcome') }}#beranda" class="block text-gray-700 hover:text-primary">Beranda</a> |
| 120 | `` | UI | <div class="absolute top-10 right-10 w-96 h-96 bg-primary rounded-full filter blur-3xl"></div> |
| 121 | `` | UI | <div class="absolute bottom-10 left-10 w-96 h-96 bg-accent rounded-full filter blur-3xl"></div> |
| 455 | `Dashboard` | UI | "Dashboard yang informatif dengan simulasi angsuran membuat saya mudah merencanakan keuangan. Feature tabel simulasi pinjaman sangat membantu dalam pengambilan keputusan." |
| 513 | `` | UI | <a href="{{ route('register') }}" class="px-10 py-5 bg-white text-primary rounded-xl font-bold hover:bg-gray-100 transition shadow-2xl text-lg"> |
| 516 | `` | UI | <a href="{{ route('login') }}" class="px-10 py-5 border-2 border-white text-white rounded-xl font-bold hover:bg-white/10 transition text-lg"> |
| 517 | `Login` | UI | Login Sekarang |
| 523 | `` | PHP | $dashboardUrl = $user->role === 'nasabah' ? route('nasabah.dashboard') : '/admin/dashboard'; |
| 526 | `Dashboard` | UI | Masuk ke Dashboard |
| 558 | `` | UI | <li><a href="{{ route('welcome') }}" class="hover:text-white transition">Beranda</a></li> |
| 564 | `Email` | UI | <li>Email: info@majakara.com</li> |
| 571 | `All rights reserved` | UI | <p class="text-white/80 text-sm">Copyright © 2026 Koperasi Majakara. All rights reserved.</p> |
| 577 | `` | UI | document.getElementById('mobile-menu-btn').addEventListener('click', function() { |
| 578 | `` | UI | document.getElementById('mobile-menu').classList.toggle('hidden'); |

## ðŸ„ `layouts\admin.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 5 | `` | UI | <meta name="viewport" content="width=device-width, initial-scale=1"> |
| 6 | `Dashboard` | UI | <title>@yield('title', 'Dashboard') - Admin Koperasi Majakara</title> |
| 78 | `Date` | UI | const now = new Date(); |
| 79 | `` | UI | const options = { day: 'numeric', month: 'long', year: 'numeric', timeZone: 'Asia/Jakarta' }; |
| 103 | `` | UI | sidebar.classList.remove('-translate-x-full'); |
| 104 | `` | UI | overlay.classList.remove('hidden'); |
| 106 | `` | UI | sidebar.classList.add('-translate-x-full'); |
| 107 | `` | UI | overlay.classList.add('hidden'); |
| 118 | `` | UI | if (sidebar) sidebar.classList.add('-translate-x-full'); |
| 119 | `` | UI | if (overlay) overlay.classList.add('hidden'); |

## ðŸ„ `layouts\nasabah.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 5 | `` | UI | <meta name="viewport" content="width=device-width, initial-scale=1"> |
| 6 | `Dashboard` | UI | <title>@yield('title', 'Dashboard') - Koperasi Majakara</title> |
| 68 | `Date` | UI | const now = new Date(); |
| 70 | `` | UI | day: 'numeric', |
| 71 | `` | UI | month: 'long', |
| 72 | `` | UI | year: 'numeric', |

## ðŸ„ `nasabah\dashboard.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Dashboard` | UI | @section('title', 'Dashboard') |
| 7 | `Member` | UI | <!-- Personalized Greeting / Premium Member Card --> |
| 46 | `Total` | UI | <!-- Balance / Card chip (Total Saldo) --> |
| 47 | `` | UI | <div class="bg-white/10 backdrop-blur-xl rounded-[1.5rem] p-5 border border-white/20 min-w-[240px] flex-1 sm:flex-none shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)] relative overflow-hidden group-hover:bg-white/15 transition-all duration-500"> |
| 51 | `Total` | UI | <p class="text-[10px] md:text-xs text-white/80 uppercase tracking-[0.15em] font-bold">Total Saldo Aktif</p> |
| 53 | `` | UI | <svg x-show="!showBalance" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg> |
| 54 | `` | UI | <svg x-show="showBalance" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg> |
| 61 | `` | UI | <p x-show="showBalance" class="text-2xl md:text-3xl font-black font-display tracking-tight text-white drop-shadow-sm" style="display: none;"> |
| 64 | `` | UI | <p x-show="!showBalance" class="text-2xl md:text-3xl font-black font-display tracking-[0.2em] text-white/90 drop-shadow-sm flex items-center gap-1"> |
| 75 | `Total` | UI | <!-- Card 2: Total Pinjaman & Gadai (Kewajiban) --> |
| 76 | `` | UI | <div class="bg-white/10 backdrop-blur-xl rounded-[1.5rem] p-5 border border-white/20 min-w-[240px] flex-1 sm:flex-none shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)] relative overflow-hidden group-hover:bg-white/15 transition-all duration-500"> |
| 78 | `Total` | UI | <p class="text-[10px] md:text-xs text-white/80 uppercase tracking-[0.15em] font-bold">Total Kewajiban</p> |
| 80 | `` | UI | <svg x-show="!showBalance" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg> |
| 81 | `` | UI | <svg x-show="showBalance" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg> |
| 88 | `` | UI | <p x-show="showBalance" class="text-2xl md:text-3xl font-black font-display tracking-tight text-white drop-shadow-sm" style="display: none;"> |
| 91 | `` | UI | <p x-show="!showBalance" class="text-2xl md:text-3xl font-black font-display tracking-[0.2em] text-white/90 drop-shadow-sm flex items-center gap-1"> |
| 119 | `` | UI | ['route' => 'nasabah.pengajuan-pending', 'label' => 'Status', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'], |
| 126 | `` | UI | <div class="w-14 h-14 md:w-16 md:h-16 rounded-[1.25rem] bg-white border border-gray-100 shadow-[0_4px_10px_-2px_rgba(0,0,0,0.05)] flex items-center justify-center text-majakara-brown group-hover:bg-gradient-to-br group-hover:from-majakara-brown group-hover:to-majakara-dark-gold group-hover:text-white group-hover:border-transparent group-hover:shadow-[0_10px_20px_-5px_rgba(103,76,29,0.3)] transition-all duration-300 transform group-hover:-translate-y-1 relative overflow-hidden"> |
| 127 | `` | UI | <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out"></div> |
| 138 | `Dashboard` | UI | <!-- Main Split Dashboard Content --> |
| 162 | `Total` | UI | <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-0.5">Total</p> |
| 195 | `` | UI | <span class="block text-sm font-black text-gray-900 font-display" x-show="showBalance" style="display: none;">Rp {{ number_format($item['value'], 0, ',', '.') }}</span> |
| 196 | `` | UI | <span class="block text-sm font-black text-gray-900 font-display tracking-widest" x-show="!showBalance">••••••</span> |
| 204 | `View` | UI | <!-- Recent Transactions (List View) --> |
| 222 | `` | UI | <a href="{{ route('nasabah.tabungan.detail-transaksi', $transaksi->id) }}" class="flex items-center justify-between group p-2 -mx-2 rounded-xl hover:bg-gray-50 transition-colors"> |
| 224 | `` | UI | <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 border border-gray-100 {{ $isSetoran ? 'bg-green-50/50 text-green-500' : 'bg-red-50/50 text-red-500' }} group-hover:scale-110 transition-transform duration-300"> |
| 237 | `` | UI | <span class="block text-sm font-black font-display {{ $isSetoran ? 'text-green-600' : 'text-red-600' }}" x-show="showBalance" style="display: none;"> |
| 240 | `` | UI | <span class="block text-sm font-black font-display text-gray-400 tracking-widest" x-show="!showBalance">••••••</span> |
| 271 | `` | UI | type: 'doughnut', |

## ðŸ„ `nasabah\deposito\detail.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Detail` | UI | @section('title', 'Detail Deposito') |
| 29 | `Detail` | UI | <h1 class="text-white font-bold text-lg">Detail Deposito</h1> |
| 39 | `` | UI | [$statusLabel, $statusClass] = $statusMap[$deposito->status] ?? ['Aktif', 'bg-green-400/30 text-green-200']; |
| 84 | `` | UI | <div class="h-3 rounded-full bg-gradient-to-r from-[#674c1d] via-[#8b6f2f] to-[#d4af37] transition-all duration-700" |
| 118 | `Total` | UI | <span class="text-sm font-bold text-gray-700">Estimasi Total Pencairan</span> |
| 138 | `Status` | UI | ['label' => 'Status', 'value' => ucfirst($deposito->status)], |
| 270 | `` | UI | @if(session('success')) |
| 273 | `` | UI | ✓ {{ session('success') }} |
| 277 | `` | UI | @if(session('error')) |
| 280 | `` | UI | ✗ {{ session('error') }} |
| 286 | `` | UI | @if($deposito->status === 'aktif' && $deposito->tgl_jatuh_tempo) |
| 289 | `` | PHP | $requestPending  = $deposito->pencairan?->status === 'pending'; |
| 290 | `` | PHP | $requestSelesai  = $deposito->pencairan?->status === 'selesai'; |
| 319 | `Transfer` | UI | Metode: <strong>{{ $deposito->pencairan->jenis_pencairan === 'rek_nasabah' ? 'Transfer ke Rekening' : 'Saldo Tabungan' }}</strong> |
| 336 | `` | UI | <form method="POST" action="{{ route('nasabah.deposito.ajukan-cairkan', $deposito->id) }}"> |
| 340 | `` | UI | <input type="radio" name="jenis_pencairan" value="rek_nasabah" class="accent-[#674c1d]" required> |
| 342 | `Transfer` | UI | <p class="text-sm font-semibold text-gray-800">Transfer ke Rekening</p> |
| 347 | `` | UI | <input type="radio" name="jenis_pencairan" value="saldo_tabungan" class="accent-[#674c1d]"> |
| 358 | `` | UI | <button type="submit" |

## ðŸ„ `nasabah\deposito\index.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 39 | `` | UI | .rate-badge { |
| 89 | `` | PHP | $total = $dep->tgl_mulai->diffInDays($dep->tgl_jatuh_tempo); |
| 91 | `` | PHP | $persen = $total > 0 ? min(100, round(($lewat / $total) * 100)) : 0; |
| 100 | `` | UI | <a href="{{ route('nasabah.deposito.detail', $dep->id) }}" class="block bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-4 hover:bg-white/15 transition"> |
| 168 | `` | UI | <a href="{{ route('nasabah.deposito.pengajuan') }}" class="inline-flex items-center gap-2 bg-white text-[#674c1d] font-bold px-8 py-3.5 rounded-full shadow-xl hover:shadow-2xl hover:scale-105 active:scale-95 transition-all text-sm mb-6"> |
| 206 | `` | UI | <input type="number" id="sim_nominal" placeholder="1.000.000" min="1000000" step="500000" |
| 219 | `` | UI | <button type="button" onclick="selectTenor({{ $t->tenor_bulan }}, {{ $t->suku_bunga }}, this)" |
| 220 | `` | UI | data-bulan="{{ $t->tenor_bulan }}" data-rate="{{ $t->suku_bunga }}" |
| 227 | `` | UI | <button type="button" onclick="selectTenor(1, 6.0, this)" class="tenor-sim-btn py-2 rounded-xl border-2 border-gray-200 text-xs font-bold text-gray-600">1bln</button> |
| 228 | `` | UI | <button type="button" onclick="selectTenor(3, 7.5, this)" class="tenor-sim-btn py-2 rounded-xl border-2 border-gray-200 text-xs font-bold text-gray-600">3bln</button> |
| 229 | `` | UI | <button type="button" onclick="selectTenor(6, 9.0, this)" class="tenor-sim-btn py-2 rounded-xl border-2 border-gray-200 text-xs font-bold text-gray-600">6bln</button> |
| 230 | `` | UI | <button type="button" onclick="selectTenor(12, 12.0, this)" class="tenor-sim-btn py-2 rounded-xl border-2 border-gray-200 text-xs font-bold text-gray-600">12bln</button> |
| 243 | `Total` | UI | <p class="text-xs text-gray-500 mb-1">Total Pencairan</p> |
| 274 | `` | UI | ['bulan' => 1, 'rate' => 6.0, 'label' => '1 Bulan'], |
| 275 | `` | UI | ['bulan' => 3, 'rate' => 7.5, 'label' => '3 Bulan'], |
| 276 | `` | UI | ['bulan' => 6, 'rate' => 9.0, 'label' => '6 Bulan'], |
| 277 | `` | UI | ['bulan' => 12, 'rate' => 12.0, 'label' => '12 Bulan'], |
| 298 | `` | UI | <span class="text-[#674c1d] font-black text-xl">{{ number_format($td['rate'], 2) }}%</span> |
| 306 | `` | PHP | $hasCategory = $pakets->filter(function($p) { return $p->kategori != null; })->isNotEmpty(); |
| 310 | `` | PHP | $featuredPakets = $pakets->filter(function($p) use ($hasCategory, $highestRateId) { |
| 314 | `` | PHP | $regularPakets = $pakets->filter(function($p) use ($hasCategory, $highestRateId) { |
| 321 | `` | UI | <div class="flex overflow-x-auto gap-4 pb-4 snap-x snap-mandatory hide-scrollbar -mx-4 px-4"> |
| 329 | `` | UI | <span class="rate-badge text-xs px-2.5 py-1 rounded-full text-[#674c1d] bg-[#d4af37]">⭐ {{ $p->kategori->nama_kategori }}</span> |
| 332 | `` | UI | <span class="rate-badge text-xs px-2.5 py-1 rounded-full text-[#674c1d] bg-[#d4af37]">🔥 Bunga Tertinggi</span> |
| 355 | `` | UI | class="shimmer-gold w-full block text-center font-bold text-[#3a2800] py-3 rounded-xl text-sm transition-all active:scale-95"> |
| 411 | `` | PHP | $st = $statusMap[$pjn->status] ?? $statusMap['1']; |
| 413 | `` | UI | <a href="{{ route('nasabah.deposito.status-pengajuan', $pjn->id) }}" class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0 hover:bg-[#674c1d]/5 -mx-4 px-4 transition-colors"> |
| 488 | `Total` | UI | ['label' => 'Total Cair', 'vals' => ['Rp 10.038.137', 'Rp 10.147.946', 'Rp 10.355.069', 'Rp 10.960.000'], 'class' => 'font-black text-[#674c1d]'], |
| 516 | `` | UI | function selectTenor(bulan, rate, el) { |
| 518 | `` | UI | selectedRate = rate; |
| 521 | `` | UI | b.classList.remove('border-[#674c1d]', 'bg-[#674c1d]', 'text-white'); |
| 522 | `` | UI | b.classList.add('border-gray-200', 'text-gray-600'); |
| 525 | `` | UI | el.classList.remove('border-gray-200', 'text-gray-600'); |
| 526 | `` | UI | el.classList.add('border-[#674c1d]', 'bg-[#674c1d]', 'text-white'); |
| 533 | `` | UI | document.getElementById('sim_result').classList.add('hidden'); |
| 540 | `` | UI | const total = nominal + bungaBersih; |
| 544 | `` | UI | document.getElementById('sim_total').textContent = fmt(total); |
| 547 | `` | UI | document.getElementById('sim_result').classList.remove('hidden'); |

## ðŸ„ `nasabah\deposito\pengajuan.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 8 | `` | UI | .step-active { background: linear-gradient(135deg, #674c1d, #d4af37); } |
| 10 | `` | UI | .step-inactive { background: #e5e7eb; } |
| 13 | `` | UI | .tenor-option.selected .rate-text { color: #674c1d; } |
| 18 | `` | UI | input[type=file]::file-selector-button { |
| 50 | `` | UI | <div id="step-dot-1" class="step-indicator step-active w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold shadow-md">1</div> |
| 54 | `` | UI | <div id="line-1" class="absolute inset-0 rounded-full bg-gradient-to-r from-[#674c1d] to-[#d4af37] transition-all duration-500" style="width:0%"></div> |
| 58 | `` | UI | <div id="step-dot-2" class="step-indicator step-inactive w-8 h-8 rounded-full flex items-center justify-center text-gray-400 text-xs font-bold">2</div> |
| 62 | `` | UI | <div id="line-2" class="absolute inset-0 rounded-full bg-gradient-to-r from-[#674c1d] to-[#d4af37] transition-all duration-500" style="width:0%"></div> |
| 66 | `` | UI | <div id="step-dot-3" class="step-indicator step-inactive w-8 h-8 rounded-full flex items-center justify-center text-gray-400 text-xs font-bold">3</div> |
| 73 | `` | UI | <form id="form-pengajuan" method="POST" action="{{ route('nasabah.deposito.submit-pengajuan') }}" enctype="multipart/form-data"> |
| 86 | `` | UI | <input type="hidden" name="paket_id" id="selected_paket_id" required> |
| 89 | `` | UI | <div class="paket-option border-2 border-gray-200 rounded-xl p-4 hover:border-[#d4af37] cursor-pointer transition-all duration-200" |
| 112 | `` | UI | <span class="rate-text text-xl font-black text-gray-700 transition-colors">{{ number_format($p->suku_bunga, 2) }}%</span> |
| 126 | `` | UI | <button type="button" onclick="goToStep(2)" |
| 127 | `` | UI | class="w-full bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white font-bold py-4 rounded-xl text-sm shadow-md active:scale-95 transition-all"> |
| 135 | `` | UI | <button type="button" onclick="goToStep(1)" id="paket-summary" class="w-full text-left bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] rounded-2xl p-4 mb-4 text-white hover:shadow-md transition-all active:scale-[0.98] border border-[#d4af37]/30"> |
| 146 | `` | UI | <p class="text-[#f0d060] font-black text-xl leading-none" id="summary-rate">-</p> |
| 156 | `` | UI | <input type="number" name="nominal" id="nominal_input" placeholder="10.000.000" min="1000000" step="500000" |
| 164 | `` | UI | <button type="button" onclick="setNominal({{ $amt }})" |
| 175 | `` | UI | <button type="button" onclick="document.getElementById('modal-rumus').classList.remove('hidden')" class="text-[10px] text-[#674c1d] font-bold underline">Lihat Rumus</button> |
| 183 | `Total` | UI | <p class="text-xs text-gray-400 mb-1">Total Cair</p> |
| 184 | `` | UI | <p id="est-total" class="font-bold text-green-700">Rp 0</p> |
| 196 | `` | UI | <div class="bg-white rounded-2xl w-full max-w-sm overflow-hidden shadow-2xl animate-in zoom-in duration-200"> |
| 210 | `Total` | UI | <p class="text-[10px] text-gray-500 uppercase font-bold mb-1">Total Cair</p> |
| 214 | `` | UI | <button type="button" onclick="document.getElementById('modal-rumus').classList.add('hidden')" |
| 225 | `` | UI | <input type="radio" name="metode_setor" value="transfer" class="mt-0.5 accent-[#674c1d]" onchange="toggleBuktiTf(this)"> |
| 229 | `Transfer` | UI | <p class="font-bold text-gray-800 text-sm">Transfer Bank</p> |
| 231 | `` | UI | <p class="text-xs text-gray-500">Setorkan dana ke rekening Koperasi Majakara, lalu upload bukti transfer</p> |
| 236 | `` | UI | <input type="radio" name="metode_setor" value="saldo_tabungan" class="mt-0.5 accent-[#674c1d]" onchange="toggleBuktiTf(this)"> |
| 276 | `Upload` | UI | <label class="text-xs font-semibold text-gray-700 block mb-2">Upload Bukti Transfer *</label> |
| 277 | `` | UI | <input type="file" name="foto_bukti_tf" accept="image/*" class="w-full text-sm text-gray-600 border border-gray-200 rounded-xl p-2"> |
| 283 | `` | UI | <textarea name="catatan" rows="2" placeholder="Tambahkan catatan jika perlu..." class="w-full p-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#674c1d] resize-none"></textarea> |
| 288 | `` | UI | <button type="button" onclick="goToStep(1)" class="flex-1 border-2 border-[#674c1d] text-[#674c1d] font-bold py-4 rounded-xl text-sm active:scale-95 transition-all">← Kembali</button> |
| 289 | `` | UI | <button type="button" onclick="goToStep(3)" class="flex-[2] bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white font-bold py-4 rounded-xl text-sm shadow-md active:scale-95 transition-all">Lanjut →</button> |
| 302 | `Detail` | UI | <h2 class="font-bold text-[#674c1d] text-sm">Konfirmasi Detail Deposito</h2> |
| 312 | `` | UI | <span class="font-bold text-[#674c1d] text-sm" id="conf-rate">-</span> |
| 327 | `Total` | UI | <span class="text-sm font-bold text-gray-700">Estimasi Total Cair</span> |
| 328 | `` | UI | <span class="font-black text-[#674c1d] text-base" id="conf-total">-</span> |
| 340 | `` | UI | <button type="button" onclick="goToStep(2)" class="flex-1 border-2 border-[#674c1d] text-[#674c1d] font-bold py-4 rounded-xl text-sm active:scale-95 transition-all">← Kembali</button> |
| 341 | `` | UI | <button type="submit" class="flex-[2] bg-gradient-to-r from-[#674c1d] to-[#d4af37] text-white font-bold py-4 rounded-xl text-sm shadow-lg active:scale-95 transition-all"> |
| 358 | `` | UI | function selectPaketOption(id, bulan, rate, minNominal, el) { |
| 361 | `` | UI | selectedTenorRate = rate; |
| 366 | `` | UI | document.querySelectorAll('.paket-option').forEach(e => e.classList.remove('selected', 'border-[#674c1d]', 'bg-gradient-to-r', 'from-[#fffbf0]', 'to-[#fef9e7]')); |
| 367 | `` | UI | el.classList.add('selected', 'border-[#674c1d]', 'bg-gradient-to-r', 'from-[#fffbf0]', 'to-[#fef9e7]'); |
| 409 | `` | UI | const metode = document.querySelector('input[name="metode_setor"]:checked'); |
| 425 | `` | UI | el.classList.add('hidden'); |
| 426 | `` | UI | el.classList.remove('slide-in'); |
| 428 | `` | UI | document.getElementById('step' + step).classList.remove('hidden'); |
| 429 | `` | UI | document.getElementById('step' + step).classList.add('slide-in'); |
| 439 | `` | UI | else if (i === step) { dot.className = 'step-indicator step-active w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold shadow-md'; dot.textContent = i; } |
| 440 | `` | UI | else { dot.className = 'step-indicator step-inactive w-8 h-8 rounded-full flex items-center justify-center text-gray-400 text-xs font-bold'; dot.textContent = i; } |
| 447 | `` | UI | document.getElementById('summary-rate').textContent = selectedTenorRate.toFixed(2) + '% p.a.'; |
| 452 | `` | UI | if (el.value === 'transfer') { c.classList.remove('hidden'); } |
| 453 | `` | UI | else { c.classList.add('hidden'); } |
| 464 | `` | UI | if (nominal < currentMinNominal \|\| !selectedTenorBulan) { cont.classList.add('hidden'); return; } |
| 465 | `` | UI | cont.classList.remove('hidden'); |
| 468 | `Date` | UI | const year = new Date().getFullYear(); |
| 469 | `` | UI | const isLeap = (year % 4 === 0 && year % 100 !== 0) \|\| (year % 400 === 0); |
| 476 | `` | UI | const total = nominal + bersih; |
| 480 | `` | UI | document.getElementById('est-total').textContent = fmt(total); |
| 485 | `Date` | UI | const year = new Date().getFullYear(); |
| 486 | `` | UI | const isLeap = (year % 4 === 0 && year % 100 !== 0) \|\| (year % 400 === 0); |
| 493 | `` | UI | const total = nominal + bersih; |
| 497 | `` | UI | document.getElementById('conf-rate').textContent = selectedTenorRate.toFixed(2) + '% p.a.'; |
| 499 | `Transfer` | UI | document.getElementById('conf-metode').textContent = metode === 'transfer' ? 'Transfer Bank' : 'Saldo Tabungan'; |
| 501 | `` | UI | document.getElementById('conf-total').textContent = fmt(total); |
| 505 | `` | UI | const urlParams = new URLSearchParams(window.location.search); |

## ðŸ„ `nasabah\deposito\riwayat.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 46 | `` | UI | if ($item->status == '1') { |
| 50 | `` | PHP | $linkTujuan = route('nasabah.deposito.status-pengajuan', $item->id); |
| 51 | `` | UI | } elseif ($item->status == '3') { |
| 55 | `` | PHP | $linkTujuan = route('nasabah.deposito.status-pengajuan', $item->id); // to see rejection note |
| 56 | `` | UI | } elseif ($item->status == '2') { |
| 58 | `` | UI | if ($depo->status === 'aktif') { |
| 62 | `` | PHP | $linkTujuan = route('nasabah.deposito.detail', $depo->id); |
| 63 | `` | UI | } elseif ($depo->status === 'dicairkan') { |
| 67 | `` | PHP | $linkTujuan = route('nasabah.deposito.detail', $depo->id); |
| 68 | `` | UI | } elseif ($depo->status === 'ditutup') { |
| 72 | `` | PHP | $linkTujuan = route('nasabah.deposito.detail', $depo->id); |
| 74 | `` | PHP | $statusLabel = ucfirst($depo->status); |
| 77 | `` | PHP | $linkTujuan = route('nasabah.deposito.detail', $depo->id); |
| 84 | `` | PHP | $linkTujuan = route('nasabah.deposito.status-pengajuan', $item->id); |
| 110 | `` | UI | @if($depo && $depo->status === 'aktif' && $depo->tgl_jatuh_tempo) |

## ðŸ„ `nasabah\deposito\status-pengajuan.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Status` | UI | @section('title', 'Status Pengajuan Deposito') |
| 17 | `Status` | UI | <h1 class="text-white font-bold text-lg">Status Pengajuan</h1> |
| 25 | `` | PHP | $status = $pengajuan->status; |
| 31 | `` | UI | 'steps'    => ['done' => 1, 'active' => 2], |
| 40 | `` | UI | 'steps'    => ['done' => 3, 'active' => 0], |
| 49 | `` | UI | 'steps'    => ['done' => 1, 'active' => 0], |
| 55 | `` | PHP | $cfg = $statusConfig[$status] ?? $statusConfig['1']; |
| 77 | `` | UI | ['label' => 'Dalam Review Admin', 'desc' => $status === '1' ? 'Menunggu persetujuan...' : ($status === '3' ? 'Ditolak' : 'Selesai ditinjau'), 'done' => in_array($status, ['2', '3']), 'active' => $status === '1'], |
| 78 | `` | UI | ['label' => 'Deposito Aktif', 'desc' => $status === '2' ? 'Nomor deposito diterbitkan' : 'Menunggu persetujuan', 'done' => $status === '2', 'active' => false, 'skip' => $status === '3'], |
| 87 | `` | UI | {{ $step['done'] ? 'bg-green-500' : (($step['active'] ?? false) ? 'bg-gradient-to-br from-[#674c1d] to-[#d4af37]' : 'bg-gray-200') }} |
| 93 | `` | UI | @elseif($step['active'] ?? false) |
| 100 | `` | UI | <p class="font-semibold text-sm {{ $step['done'] ? 'text-gray-800' : (($step['active'] ?? false) ? 'text-[#674c1d]' : 'text-gray-400') }}"> |
| 103 | `` | UI | <p class="text-xs {{ $step['done'] \|\| ($step['active'] ?? false) ? 'text-gray-500' : 'text-gray-300' }} mt-0.5"> |
| 140 | `Transfer` | UI | <p class="text-xs font-semibold text-gray-500 mb-2">Bukti Transfer</p> |
| 141 | `Transfer` | UI | <img src="{{ asset('storage/' . $pengajuan->foto_bukti_tf) }}" alt="Bukti Transfer" class="w-full rounded-xl border border-gray-200 object-cover max-h-48"> |
| 146 | `` | UI | @if($pengajuan->status === '3' && $pengajuan->catatan) |
| 157 | `` | UI | @if($pengajuan->status === '3') |
| 158 | `` | UI | <a href="{{ route('nasabah.deposito.pengajuan') }}" class="flex items-center justify-center gap-2 w-full bg-gradient-to-r from-[#674c1d] to-[#d4af37] text-white font-bold py-4 rounded-xl text-sm shadow-md active:scale-95 transition-all"> |
| 165 | `` | UI | <a href="{{ route('nasabah.deposito.index') }}" class="flex items-center justify-center gap-2 w-full border-2 border-[#674c1d] text-[#674c1d] font-bold py-4 rounded-xl text-sm active:scale-95 transition-all"> |

## ðŸ„ `nasabah\gadai_baru\aktif_detail.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Detail` | UI | @section('title', 'Detail Gadai Saya') |
| 7 | `` | PHP | $isTenggang = $gadai->status == 'grace_period'; |
| 8 | `` | PHP | $isLunas = $gadai->status == 'lunas'; |
| 9 | `` | PHP | $isHangus = $gadai->status == 'expired_final'; |
| 12 | `` | PHP | $today = now()->startOfDay(); |
| 16 | `` | PHP | $sisaHari = $isTenggang ? $today->diffInDays($tenggang, false) : $today->diffInDays($jatuhTempo, false); |
| 18 | `` | PHP | $elapsedDays = min($totalDays, $mulai->diffInDays($today)); |
| 41 | `Status` | UI | <p class="text-white/70 text-[10px] font-black uppercase tracking-widest">Status Transaksi</p> |
| 77 | `` | UI | @if(in_array($gadai->status, ['active', 'grace_period'])) |
| 79 | `` | UI | <a href="{{ route('nasabah.gadai_baru.create-pengajuan', ['id' => $gadai->id, 'jenis' => 'lunas']) }}" |
| 80 | `` | UI | class="flex-1 flex items-center justify-center gap-2 py-3.5 bg-white text-emerald-700 font-black rounded-2xl text-xs uppercase tracking-widest shadow-xl active:scale-95 transition-all hover:bg-emerald-50"> |
| 85 | `` | UI | <a href="{{ route('nasabah.gadai_baru.create-pengajuan', ['id' => $gadai->id, 'jenis' => 'perpanjang']) }}" |
| 86 | `` | UI | class="flex-1 flex items-center justify-center gap-2 py-3.5 bg-white/20 hover:bg-white/30 text-white font-black rounded-2xl text-xs uppercase tracking-widest border border-white/30 active:scale-95 transition-all backdrop-blur-sm"> |
| 96 | `` | UI | @foreach(['success'=>'green','warning'=>'amber','error'=>'red'] as $t => $c) |
| 132 | `Total` | UI | <p class="text-[9px] text-emerald-600 font-black uppercase tracking-widest mb-1">Total Tebus / Lunas</p> |
| 137 | `Total` | UI | <p class="text-[9px] text-amber-600 font-black uppercase tracking-widest mb-1">Total Perpanjang Saja</p> |
| 170 | `` | UI | <img src="{{ asset('storage/' . $file->path_file) }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"> |
| 246 | `` | UI | @if($hist->aksi === 'create') |

## ðŸ„ `nasabah\gadai_baru\index.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 29 | `` | UI | @foreach(['success' => 'green', 'warning' => 'amber', 'error' => 'red'] as $type => $color) |
| 30 | `` | UI | @if(session($type)) |
| 32 | `` | UI | <div class="bg-{{ $color }}-50 border border-{{ $color }}-200 rounded-2xl p-4 text-{{ $color }}-700 text-sm font-bold shadow-sm">{{ session($type) }}</div> |
| 60 | `` | PHP | $isTenggang = $gadai->status == 'grace_period'; |
| 61 | `` | PHP | $today = now()->startOfDay(); |
| 64 | `` | PHP | $sisaHari   = (int) ($isTenggang ? $today->diffInDays($tenggang, false) : $today->diffInDays($jatuhTempo, false)); |
| 70 | `` | PHP | $elapsedDays = min($totalDays, (int) $mulai->diffInDays($today)); |
| 83 | `` | UI | <div class="bg-white rounded-3xl shadow-md border {{ $isTenggang ? 'border-red-200' : 'border-gray-100' }} relative overflow-hidden group hover:shadow-xl transition-all duration-300"> |
| 104 | `` | UI | <a href="{{ route('nasabah.gadai_baru.aktif-detail', $gadai->id) }}" class="group/t flex items-center gap-1.5"> |
| 137 | `` | UI | <div class="h-2.5 rounded-full bg-gradient-to-r {{ $progressColor }} transition-all duration-700" style="width: {{ min(100, $progressPct) }}%"></div> |
| 143 | `` | UI | <a href="{{ route('nasabah.gadai_baru.create-pengajuan', ['id' => $gadai->id, 'jenis' => 'lunas']) }}" |
| 144 | `` | UI | class="flex items-center justify-center gap-2 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-black rounded-2xl text-xs uppercase tracking-widest shadow-md shadow-emerald-600/20 active:scale-95 transition-all"> |
| 149 | `` | UI | <a href="{{ route('nasabah.gadai_baru.create-pengajuan', ['id' => $gadai->id, 'jenis' => 'perpanjang']) }}" |
| 150 | `` | UI | class="flex items-center justify-center gap-2 py-3.5 bg-amber-500 hover:bg-amber-600 text-white font-black rounded-2xl text-xs uppercase tracking-widest shadow-md shadow-amber-500/20 active:scale-95 transition-all"> |
| 172 | `` | UI | <a href="{{ route('nasabah.gadai_baru.status-pengajuan') }}" class="text-[10px] font-black text-[#674c1d] uppercase tracking-widest hover:underline">Lihat Semua</a> |
| 193 | `` | UI | <span class="text-[9px] font-black uppercase {{ $pengajuan->status == 'approved' ? 'text-emerald-600' : ($pengajuan->status == 'pending' ? 'text-amber-600' : 'text-red-600') }}">{{ $pengajuan->status }}</span> |
| 215 | `` | UI | <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $selesai->status }}</p> |
| 259 | `` | UI | <a href="{{ route('nasabah.gadai_baru.show', ['kategori' => $cfg['var']->kode_kategori, 'item' => $item->id]) }}" |
| 264 | `` | UI | <img src="{{ asset('storage/' . $item->file_pic) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"> |

## ðŸ„ `nasabah\gadai_baru\pengajuan.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 22 | `Total` | UI | <p class="text-xs text-amber-600 font-medium uppercase tracking-wider">Total Pembayaran</p> |
| 63 | `` | UI | @if(session('error')) |
| 66 | `` | UI | <p class="text-red-700 text-sm">{{ session('error') }}</p> |
| 81 | `` | UI | @foreach($errors->all() as $error) |
| 82 | `` | UI | <li>{{ $error }}</li> |
| 91 | `` | UI | <form action="{{ route('nasabah.gadai_baru.store-pengajuan', ['id' => $gadai->id, 'jenis' => $jenis]) }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="form-pengajuan"> |
| 93 | `` | UI | <input type="hidden" name="pin" id="pin-hidden-input"> |
| 100 | `` | UI | <input type="radio" name="metode" value="cash" class="sr-only peer" checked onclick="toggleMetode('cash')"> |
| 106 | `` | UI | <label class="relative flex flex-col items-center justify-center p-4 border-2 rounded-2xl cursor-pointer transition-all hover:bg-gray-50 peer-checked:border-[#674c1d] peer-checked:bg-amber-50" id="label-transfer"> |
| 107 | `` | UI | <input type="radio" name="metode" value="transfer" class="sr-only peer" onclick="toggleMetode('transfer')"> |
| 109 | `Transfer` | UI | <span class="text-sm font-bold text-gray-700">Transfer Bank</span> |
| 110 | `Upload` | UI | <span class="text-[10px] text-gray-500 text-center mt-1">Upload Bukti Transfer</span> |
| 119 | `` | UI | <input type="datetime-local" name="tgl_janji_temu" class="w-full border-gray-300 rounded-xl focus:ring-[#674c1d] focus:border-[#674c1d]" min="{{ date('Y-m-d\TH:i') }}"> |
| 124 | `Transfer` | UI | <!-- Input Transfer (Hidden by default) --> |
| 125 | `` | UI | <div id="section-transfer" class="hidden space-y-4"> |
| 139 | `` | UI | <button type="button" onclick="copyToClipboard('{{ $bank->no_rek }}')" class="absolute top-4 right-4 text-blue-400 hover:text-blue-600 transition-colors" title="Salin No. Rekening"> |
| 146 | `Upload` | UI | <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Bukti Transfer *</label> |
| 152 | `` | UI | <p class="text-xs text-gray-600 font-semibold">Klik untuk tambah bukti transfer</p> |
| 156 | `` | UI | <p class="text-[10px] text-gray-500 mt-2">Minimal upload 1 bukti transfer. Anda bisa upload beberapa bukti jika diperlukan.</p> |
| 162 | `` | UI | <textarea name="keterangan" rows="2" class="w-full border-gray-300 rounded-xl focus:ring-[#674c1d] focus:border-[#674c1d]" placeholder="Tambahkan pesan jika perlu..."></textarea> |
| 166 | `` | UI | <button type="button" onclick="showPinModal()" id="btnSubmit" class="w-full bg-[#674c1d] hover:bg-[#543e18] text-white font-bold py-4 rounded-2xl transition-all shadow-lg shadow-amber-200 flex items-center justify-center gap-2"> |
| 180 | `` | UI | const sectionTransfer = document.getElementById('section-transfer'); |
| 182 | `` | UI | const labelTransfer = document.getElementById('label-transfer'); |
| 185 | `` | UI | sectionCash.classList.remove('hidden'); |
| 186 | `` | UI | sectionTransfer.classList.add('hidden'); |
| 187 | `` | UI | labelCash.classList.add('border-[#674c1d]', 'bg-amber-50'); |
| 188 | `` | UI | labelTransfer.classList.remove('border-[#674c1d]', 'bg-amber-50'); |
| 190 | `` | UI | sectionCash.classList.add('hidden'); |
| 191 | `` | UI | sectionTransfer.classList.remove('hidden'); |
| 192 | `` | UI | labelTransfer.classList.add('border-[#674c1d]', 'bg-amber-50'); |
| 193 | `` | UI | labelCash.classList.remove('border-[#674c1d]', 'bg-amber-50'); |
| 205 | `` | UI | div.className = 'border border-gray-200 rounded-xl p-3 bg-gray-50 relative animate-in fade-in slide-in-from-top-2 duration-300'; |
| 209 | `` | UI | <button type="button" onclick="this.closest('.border').remove();" class="text-red-500 hover:text-red-700"> |
| 213 | `` | UI | <input type="file" name="bukti_transfer[]" required accept="image/*" class="w-full text-xs text-gray-500 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-[#674c1d] file:text-white"> |
| 232 | `` | UI | const metode = document.querySelector('input[name="metode"]:checked').value; |
| 233 | `` | UI | if (metode === 'transfer') { |
| 234 | `` | UI | const fileInputs = form.querySelectorAll('input[type="file"]'); |
| 240 | `` | UI | alert('Ukuran file ' + input.files[0].name + ' terlalu besar. Maksimal 2MB.'); |
| 246 | `` | UI | alert('Silakan tambahkan minimal 1 bukti transfer.'); |
| 251 | `` | UI | document.getElementById('pin-modal').classList.remove('hidden'); |
| 252 | `` | UI | document.getElementById('pin-modal').classList.add('flex'); |
| 254 | `` | UI | document.getElementById('pin-error').classList.add('hidden'); |
| 259 | `` | UI | document.getElementById('pin-modal').classList.add('hidden'); |
| 260 | `` | UI | document.getElementById('pin-modal').classList.remove('flex'); |
| 279 | `` | UI | document.getElementById('form-pengajuan').submit(); |
| 283 | `` | UI | const errorDiv = document.getElementById('pin-error'); |
| 285 | `` | UI | errorDiv.classList.remove('hidden'); |
| 299 | `` | UI | <button type="button" onclick="closePinModal()" class="text-gray-400 hover:text-gray-600"> |
| 306 | `` | UI | <div id="pin-error" class="hidden mb-4 p-3 bg-red-50 border border-red-200 rounded-lg"> |
| 312 | `` | UI | <input type="password" id="pin-input" maxlength="6" pattern="[0-9]*" inputmode="numeric" |
| 318 | `` | UI | <button type="button" onclick="closePinModal()" |
| 322 | `` | UI | <button type="button" onclick="verifyAndSubmit()" |

## ðŸ„ `nasabah\gadai_baru\riwayat.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 7 | `Active` | UI | <!-- Active Gadai Section --> |
| 32 | `` | PHP | $isTenggang = $gadai->status == 'grace_period'; |
| 34 | `` | UI | <div class="bg-white rounded-3xl p-6 shadow-xl border-2 {{ $isTenggang ? 'border-red-500 bg-red-50/30' : 'border-gray-50' }} transition-all hover:scale-[1.02] duration-300 relative overflow-hidden group"> |
| 36 | `` | UI | <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:rotate-12 transition-transform duration-500"> |
| 47 | `` | UI | <a href="{{ route('nasabah.gadai_baru.aktif-detail', $gadai->id) }}" class="group/title flex items-center gap-1.5"> |
| 61 | `` | UI | <a href="{{ route('nasabah.gadai_baru.aktif-detail', $gadai->id) }}" class="text-[9px] font-black text-[#674c1d] uppercase tracking-widest hover:underline flex items-center gap-1"> |
| 99 | `` | UI | <a href="{{ route('nasabah.gadai_baru.aktif-detail', $gadai->id) }}" class="flex-1 text-center bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white font-black py-3 px-4 rounded-2xl transition-all shadow-xl shadow-amber-100 active:scale-95 text-xs">Lihat Rincian &amp; Kelola</a> |
| 100 | `` | UI | <a href="https://wa.me/628139552626?text=Halo%20Admin,%20saya%20ingin%20info%20gadai%20{{$gadai->slot_kode}}" target="_blank" class="w-12 h-12 flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-2xl transition-all active:scale-95"> |
| 110 | `Completed` | UI | <!-- Completed Gadai Section --> |
| 127 | `Status` | UI | <th class="px-6 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th> |
| 146 | `` | PHP | $color = match($selesai->status) { |
| 152 | `` | PHP | $label = match($selesai->status) { |
| 156 | `` | UI | default => strtoupper($selesai->status) |

## ðŸ„ `nasabah\gadai_baru\show.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 58 | `` | UI | <input type="text" id="nama" |
| 65 | `` | UI | <input type="text" id="no_hp" |
| 73 | `` | UI | <select id="cabang" |
| 79 | `` | UI | </select> |
| 84 | `` | UI | <input type="datetime-local" id="waktu_kedatangan" |
| 89 | `` | UI | <button type="button" onclick="submitWA()" |
| 115 | `Date` | UI | const dateObj = new Date(waktu); |
| 123 | `Detail` | UI | `*Detail Pengajuan:*%0A` + |

## ðŸ„ `nasabah\gadai_baru\status_pengajuan.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Status` | UI | @section('title', 'Status Pengajuan Gadai') |
| 8 | `Status` | UI | <h2 class="text-2xl font-bold text-gray-800 mb-6">Status Pengajuan Anda</h2> |
| 16 | `Dashboard` | UI | <a href="{{ route('nasabah.gadai_baru.index') }}" class="inline-block mt-6 text-[#674c1d] font-bold hover:underline">Kembali ke Dashboard</a> |
| 22 | `` | UI | <div class="flex items-center justify-between p-4 {{ $item->status == 'pending' ? 'bg-amber-50' : ($item->status == 'approved' ? 'bg-emerald-50' : 'bg-red-50') }}"> |
| 24 | `` | UI | <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $item->status == 'pending' ? 'bg-amber-100 text-amber-600' : ($item->status == 'approved' ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600') }}"> |
| 37 | `` | UI | <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $item->status == 'pending' ? 'bg-amber-200 text-amber-800' : ($item->status == 'approved' ? 'bg-emerald-200 text-emerald-800' : 'bg-red-200 text-red-800') }}"> |
| 38 | `` | UI | {{ $item->status }} |
| 63 | `Transfer` | UI | <p class="text-[10px] text-gray-400 font-medium mb-1.5">Bukti Transfer</p> |
| 67 | `Transfer` | UI | <button onclick="showPhotoPreview('{{ asset('storage/'.$file->path_file) }}', 'Bukti Transfer')" class="w-14 h-14 rounded-xl overflow-hidden border border-gray-200 hover:border-[#674c1d] transition-all shadow-xs scale-95 hover:scale-105"> |
| 72 | `Transfer` | UI | <button onclick="showPhotoPreview('{{ asset('storage/'.$item->bukti_transfer) }}', 'Bukti Transfer')" class="w-14 h-14 rounded-xl overflow-hidden border border-gray-200 hover:border-[#674c1d] transition-all shadow-xs scale-95 hover:scale-105"> |

## ðŸ„ `nasabah\guide.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 40 | `` | UI | <p class="text-gray-600 text-sm">Setor dan tarik dana dengan transfer atau tunai (janji temu)</p> |
| 46 | `` | UI | <details class="group rounded-2xl border border-gray-100 bg-gray-50/50 overflow-hidden" open> |
| 56 | `Transfer` | UI | <p class="text-sm text-gray-600">Transfer atau tunai via janji temu</p> |
| 66 | `` | UI | Isi nominal & upload bukti |
| 82 | `` | UI | <span><strong>Transfer:</strong> Isi nominal, upload bukti transfer, lalu cek <strong>Status Pengajuan Setor</strong>. Admin akan memverifikasi bukti dan menyetujui; saldo Anda bertambah.</span> |
| 86 | `Status` | UI | <span><strong>Tunai (Janji Temu):</strong> Pilih lokasi, tanggal & waktu temu. Datang ke lokasi, admin akan memproses dan saldo bertambah. Pantau di <strong>Status Janji Temu</strong>.</span> |
| 98 | `` | UI | <h4 class="font-semibold text-amber-900 mb-1">Perlu Anda tahu (setoran transfer)</h4> |
| 100 | `` | UI | <li>• <strong>Nominal bisa diubah admin.</strong> Jika nominal yang Anda isi berbeda dengan bukti transfer, admin akan mengikuti <strong>nominal di bukti transfer</strong>. Jadi jangan kaget jika saldo yang masuk sesuai bukti TF, bukan nominal yang Anda tulis.</li> |
| 101 | `` | UI | <li>• <strong>Admin bisa menolak</strong> pengajuan jika bukti transfer tidak valid (data atau nominal tidak sesuai). Jika ditolak, pengajuan tidak diproses; Anda bisa mengajukan ulang dengan bukti yang benar.</li> |
| 112 | `` | UI | Panduan detail & cara akses |
| 117 | `` | UI | </details> |
| 120 | `` | UI | <details class="group rounded-2xl border border-gray-100 bg-gray-50/50 overflow-hidden"> |
| 130 | `Transfer` | UI | <p class="text-sm text-gray-600">Transfer ke rekening atau tunai di lokasi</p> |
| 144 | `` | UI | Admin setujui & transfer |
| 155 | `Transfer` | UI | <span><strong>Transfer:</strong> Isi nominal & rekening tujuan. Setelah admin menyetujui, dana akan ditransfer ke rekening Anda. <strong>Biaya transfer ditanggung nasabah</strong> (dipotong dari saldo).</span> |
| 159 | `Status` | UI | <span><strong>Tunai (Janji Temu):</strong> Pilih lokasi & jadwal. Datang ke lokasi untuk terima tunai. Pantau di <strong>Status Janji Temu</strong>.</span> |
| 171 | `` | UI | <h4 class="font-semibold text-blue-900 mb-1">Biaya transfer (penarikan)</h4> |
| 172 | `Detail` | UI | <p class="text-sm text-blue-900/90">Ketika admin menyetujui penarikan transfer, <strong>biaya transfer akan dihitung</strong> (sesuai bank pengirim koperasi dan bank tujuan Anda). Biaya ini <strong>dipotong dari saldo Anda</strong>. Total yang didebet = nominal penarikan + biaya transfer. Nominal yang Anda terima di rekening = nominal penarikan saja. Rincian biaya bisa Anda lihat di <strong>Detail Pengajuan Tarik</strong> setelah status Disetujui.</p> |
| 182 | `` | UI | Panduan detail & cara akses |
| 187 | `` | UI | </details> |
| 190 | `Dashboard` | UI | <a href="{{ route('nasabah.tabungan.index') }}" class="text-sm font-medium text-[#8b6f2f] hover:underline">Dashboard Tabungan</a> |
| 191 | `Status` | UI | <a href="{{ route('nasabah.tabungan.status-pengajuan-setor') }}" class="text-sm font-medium text-[#8b6f2f] hover:underline">Status Pengajuan Setor</a> |
| 192 | `Status` | UI | <a href="{{ route('nasabah.tabungan.status-janji-temu') }}" class="text-sm font-medium text-[#8b6f2f] hover:underline">Status Janji Temu</a> |
| 193 | `Status` | UI | <a href="{{ route('nasabah.tabungan.status-pengajuan-tarik') }}" class="text-sm font-medium text-[#8b6f2f] hover:underline">Status Pengajuan Tarik</a> |
| 209 | `` | UI | <p class="text-gray-600 text-sm">Ajukan pinjaman, cairkan via transfer/janji temu, bayar angsuran</p> |
| 215 | `` | UI | <details class="group rounded-2xl border border-gray-100 bg-amber-50/30 overflow-hidden" open> |
| 225 | `` | UI | <p class="text-sm text-gray-600">Ajukan nominal, pilih transfer atau janji temu</p> |
| 238 | `Transfer` | UI | <span>Pilih <strong>Transfer</strong> atau <strong>Janji Temu</strong>. Jika janji temu, tentukan lokasi & jadwal. Setelah admin menyetujui dan mencairkan, dana masuk sesuai pilihan Anda.</span> |
| 246 | `` | UI | </details> |
| 249 | `` | UI | <details class="group rounded-2xl border border-gray-100 bg-amber-50/30 overflow-hidden"> |
| 259 | `` | UI | <p class="text-sm text-gray-600">Bayar via transfer atau janji temu</p> |
| 268 | `Upload` | UI | <span><strong>Transfer:</strong> Upload bukti transfer pembayaran. Admin verifikasi lalu angsuran tercatat.</span> |
| 272 | `` | UI | <span><strong>Janji Temu:</strong> Buat janji bayar tunai di lokasi. Setelah proses, status pembayaran diperbarui.</span> |
| 281 | `` | UI | Panduan detail & cara akses |
| 286 | `` | UI | </details> |
| 289 | `` | UI | <details class="group rounded-2xl border border-amber-200 bg-amber-50/50 overflow-hidden"> |
| 312 | `` | UI | <p>Pinjaman dikenakan bunga sesuai ketentuan koperasi. Besar bunga mempengaruhi total angsuran bulanan Anda. Gunakan <strong>Simulasi Angsuran</strong> saat pengajuan untuk melihat perkiraan.</p> |
| 321 | `` | UI | <p>Jika angsuran dibayar <strong>terlambat</strong> dari jadwal, akan dikenakan <strong>denda</strong> sesuai kebijakan koperasi. Besar denda dapat dilihat di detail angsuran. Usahakan bayar tepat waktu agar tidak terkena denda.</p> |
| 345 | `` | UI | </details> |
| 348 | `Dashboard` | UI | <a href="{{ route('nasabah.pinjaman.index') }}" class="text-sm font-medium text-amber-700 hover:underline">Dashboard Pinjaman</a> |
| 349 | `Status` | UI | <a href="{{ route('nasabah.pinjaman.status-pengajuan') }}" class="text-sm font-medium text-amber-700 hover:underline">Status Pengajuan</a> |
| 351 | `Status` | UI | <a href="{{ route('nasabah.pinjaman.status-pembayaran') }}" class="text-sm font-medium text-amber-700 hover:underline">Status Pembayaran</a> |
| 385 | `` | UI | <li>• Semua pengajuan dan janji temu bisa Anda pantau di menu <strong>Status</strong> masing-masing fitur.</li> |
| 387 | `` | UI | <li>• Untuk penarikan transfer, biaya transfer admin akan dipotong dari saldo Anda; nominal yang diterima ke rekening sesuai yang Anda ajukan.</li> |

## ðŸ„ `nasabah\guide\pinjaman-pembayaran.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 40 | `Menu` | UI | <p class="text-sm text-gray-600 mt-0.5">Di mobile: buka Menu lalu pilih Pinjaman.</p> |
| 46 | `Dashboard` | UI | <p class="font-semibold text-gray-900">Dari <strong>Dashboard Pinjaman</strong>, buka <strong>Angsuran</strong> atau <strong>Pembayaran</strong></p> |
| 47 | `` | UI | <p class="text-sm text-gray-600 mt-0.5">Angsuran: daftar angsuran per pinjaman. Pembayaran: halaman untuk bayar angsuran (upload bukti transfer atau buat janji temu bayar tunai).</p> |
| 54 | `` | UI | <p class="text-sm text-gray-600 mt-0.5">Pilih pinjaman & angsuran yang akan dibayar, pilih metode (Transfer atau Janji Temu), upload bukti atau jadwalkan janji. Pantau di <strong>Status Pembayaran</strong>.</p> |
| 69 | `` | UI | loading="lazy" |
| 84 | `` | UI | <li>• Pantau <a href="{{ route('nasabah.pinjaman.status-pembayaran') }}" class="underline font-medium">Status Pembayaran</a> setelah submit.</li> |

## ðŸ„ `nasabah\guide\pinjaman-pengajuan.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 41 | `Menu` | UI | <p class="text-sm text-gray-600 mt-0.5">Icon berbentuk dokumen/uang. Di mobile: buka Menu lalu pilih Pinjaman.</p> |
| 47 | `Dashboard` | UI | <p class="font-semibold text-gray-900">Anda masuk ke <strong>Dashboard Pinjaman</strong></p> |
| 55 | `` | UI | <p class="text-sm text-gray-600 mt-0.5">Halaman pengajuan: isi nominal, pilih durasi (tenor), pilih metode pencairan (Transfer atau Janji Temu). Anda bisa gunakan <strong>Simulasi Angsuran</strong> di halaman itu untuk melihat estimasi sebelum submit.</p> |
| 121 | `` | UI | <input type="text" id="guideSimulasiNominal" placeholder="Contoh: 5000000" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500" value="5000000"> |
| 125 | `` | UI | <select id="guideSimulasiDurasi" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500"> |
| 129 | `` | UI | </select> |
| 132 | `` | UI | <button type="button" id="guideSimulasiBtn" class="px-5 py-2.5 bg-amber-600 text-white rounded-xl font-semibold hover:bg-amber-700 transition-colors"> |
| 142 | `Total` | UI | <p class="text-gray-600">Total kewajiban</p> |
| 149 | `Total` | UI | <thead><tr><th class="text-left py-1">Bulan</th><th class="text-left py-1">Jatuh tempo</th><th class="text-right py-1">Total</th></tr></thead> |
| 167 | `` | UI | loading="lazy" |
| 198 | `` | UI | errorP.classList.add('hidden'); |
| 199 | `` | UI | resultDiv.classList.add('hidden'); |
| 202 | `` | UI | errorP.classList.remove('hidden'); |
| 220 | `` | UI | if (data.success && data.data) { |
| 229 | `` | UI | tr.innerHTML = '<td class="py-2 text-gray-900">' + item.bulan + '</td><td class="py-2 text-gray-600">' + item.tanggal + '</td><td class="py-2 text-right font-semibold text-amber-700">' + formatRp(item.total) + '</td>'; |
| 232 | `` | UI | resultDiv.classList.remove('hidden'); |
| 235 | `` | UI | errorP.classList.remove('hidden'); |
| 241 | `` | UI | errorP.textContent = 'Network error. Coba lagi.'; |
| 242 | `` | UI | errorP.classList.remove('hidden'); |

## ðŸ„ `nasabah\guide\tabungan-penarikan.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 40 | `Menu` | UI | <p class="text-sm text-gray-600 mt-0.5">Di mobile: buka Menu (burger) lalu pilih Tabungan.</p> |
| 46 | `Dashboard` | UI | <p class="font-semibold text-gray-900">Di <strong>Dashboard Tabungan</strong>, klik <strong>Penarikan</strong></p> |
| 47 | `Transfer` | UI | <p class="text-sm text-gray-600 mt-0.5">Anda masuk ke halaman Penarikan: pilih Transfer (ke rekening) atau Tunai (Janji Temu), isi nominal dan data rekening (jika transfer).</p> |
| 54 | `Detail` | UI | <p class="text-sm text-gray-600 mt-0.5">Untuk transfer: biaya transfer akan dipotong dari saldo. Rincian biaya bisa dilihat di <strong>Detail Pengajuan Tarik</strong> setelah disetujui.</p> |
| 69 | `` | UI | loading="lazy" |
| 80 | `` | UI | <h3 class="font-bold text-blue-900 mb-2">Biaya transfer</h3> |
| 81 | `Detail` | UI | <p class="text-sm text-blue-900/90">Ketika admin menyetujui penarikan via transfer, biaya transfer dihitung dan dipotong dari saldo. Total didebet = nominal penarikan + biaya transfer. Yang Anda terima ke rekening = nominal penarikan saja. Lihat rincian di <a href="{{ route('nasabah.tabungan.status-pengajuan-tarik') }}" class="underline font-medium">Status Pengajuan Tarik</a> → Detail setelah Disetujui.</p> |

## ðŸ„ `nasabah\guide\tabungan-setoran.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 45 | `Menu` | UI | <p class="text-sm text-gray-600 mt-0.5">Icon berbentuk koin/uang. Di mobile: buka <strong>Menu</strong> (ikon burger) lalu pilih <strong>Tabungan</strong>.</p> |
| 51 | `Dashboard` | UI | <p class="font-semibold text-gray-900">Anda masuk ke <strong>Dashboard Tabungan</strong></p> |
| 59 | `` | UI | <p class="text-sm text-gray-600 mt-0.5">Anda akan masuk ke halaman <strong>Nabung Sekarang</strong>: pilih setoran via Transfer atau Tunai (Janji Temu), isi nominal, upload bukti (untuk transfer), lalu submit.</p> |
| 75 | `` | UI | loading="lazy" |
| 89 | `` | UI | <li>• Admin dapat mengubah nominal setoran jika berbeda dengan bukti transfer (mengikuti bukti TF).</li> |
| 90 | `` | UI | <li>• Admin dapat menolak pengajuan jika bukti transfer tidak valid.</li> |
| 91 | `Status` | UI | <li>• Pantau status di <a href="{{ route('nasabah.tabungan.status-pengajuan-setor') }}" class="underline font-medium">Status Pengajuan Setor</a>.</li> |

## ðŸ„ `nasabah\notifications\index.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 16 | `` | UI | <form method="POST" action="{{ route('nasabah.notifications.mark-all-read') }}" class="inline"> |
| 18 | `` | UI | <button type="submit" class="px-4 py-2 bg-[#674c1d] text-white rounded-xl hover:bg-[#4a3514] transition-colors text-sm font-medium"> |
| 23 | `` | UI | <a href="{{ route('nasabah.dashboard') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium"> |
| 24 | `Dashboard` | UI | ← Dashboard |
| 29 | `` | UI | @if(session('success')) |
| 31 | `` | UI | {{ session('success') }} |
| 35 | `Filter` | UI | <!-- Filter --> |
| 37 | `` | UI | <form method="GET" action="{{ route('nasabah.notifications.index') }}" class="flex flex-wrap items-center gap-3"> |
| 39 | `` | UI | <input type="checkbox" name="unread_only" value="1" {{ request('unread_only') ? 'checked' : '' }} class="rounded border-gray-300 text-[#674c1d] focus:ring-[#674c1d]"> |
| 42 | `Filter` | UI | <button type="submit" class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-medium">Filter</button> |
| 50 | `` | UI | <form method="POST" action="{{ route('nasabah.notifications.mark-read', $notif->id) }}" class="block"> |
| 52 | `` | UI | <input type="hidden" name="redirect" value="{{ $notif->link ?: url()->current() }}"> |
| 53 | `` | UI | <button type="submit" class="w-full text-left px-6 py-4 hover:bg-gray-100 transition-colors flex items-start gap-4"> |
| 55 | `` | UI | @if($notif->type === 'tabungan_setor') bg-green-100 text-green-700 |
| 56 | `` | UI | @elseif($notif->type === 'tabungan_tarik') bg-amber-100 text-amber-700 |
| 57 | `` | UI | @elseif(str_starts_with($notif->type, 'pinjaman')) bg-blue-100 text-blue-700 |
| 58 | `` | UI | @elseif($notif->type === 'janji_temu') bg-purple-100 text-purple-700 |
| 59 | `` | UI | @elseif($notif->type === 'profil') bg-indigo-100 text-indigo-700 |
| 61 | `` | UI | @if($notif->type === 'tabungan_setor') |
| 63 | `` | UI | @elseif($notif->type === 'tabungan_tarik') |
| 65 | `` | UI | @elseif(str_starts_with($notif->type, 'pinjaman')) |
| 67 | `` | UI | @elseif($notif->type === 'janji_temu') |

## ðŸ„ `nasabah\pengajuan-pending.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Pending` | UI | @section('title', 'Pengajuan Pending') |
| 18 | `Pending` | UI | <h1 class="text-2xl font-bold text-white font-display mb-1">Semua Pengajuan Pending</h1> |
| 22 | `` | UI | <a href="{{ route('nasabah.dashboard') }}" class="px-4 py-2 bg-white/20 backdrop-blur-sm rounded-xl text-white hover:bg-white/30 transition-all border border-white/30"> |
| 31 | `Filter` | UI | <!-- Filter Section --> |
| 34 | `` | UI | <form method="GET" action="{{ route('nasabah.pengajuan-pending') }}" class="flex flex-col md:flex-row gap-4"> |
| 36 | `Filter` | UI | <label class="block text-sm font-semibold text-gray-700 mb-2">Filter Jenis</label> |
| 37 | `` | UI | <select name="type" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none"> |
| 39 | `` | UI | <option value="tabungan_setor" {{ request('type') == 'tabungan_setor' ? 'selected' : '' }}>Setoran Tabungan</option> |
| 40 | `` | UI | <option value="tabungan_tarik" {{ request('type') == 'tabungan_tarik' ? 'selected' : '' }}>Penarikan Tabungan</option> |
| 41 | `` | UI | <option value="pinjaman" {{ request('type') == 'pinjaman' ? 'selected' : '' }}>Pinjaman</option> |
| 42 | `` | UI | <option value="deposito" {{ request('type') == 'deposito' ? 'selected' : '' }}>Deposito</option> |
| 43 | `` | UI | <option value="gadai" {{ request('type') == 'gadai' ? 'selected' : '' }}>Gadai</option> |
| 44 | `` | UI | </select> |
| 47 | `Filter` | UI | <label class="block text-sm font-semibold text-gray-700 mb-2">Filter Status</label> |
| 48 | `` | UI | <select name="status" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none"> |
| 49 | `Status` | UI | <option value="">Semua Status</option> |
| 50 | `` | UI | <option value="Menunggu Persetujuan" {{ request('status') == 'Menunggu Persetujuan' ? 'selected' : '' }}>Menunggu Persetujuan</option> |
| 51 | `` | UI | </select> |
| 54 | `` | UI | <button type="submit" class="w-full md:w-auto px-6 py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-semibold hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-lg hover:shadow-xl"> |
| 55 | `Filter` | UI | Filter |
| 58 | `` | UI | @if(request('type') \|\| request('status')) |
| 60 | `` | UI | <a href="{{ route('nasabah.pengajuan-pending') }}" class="w-full md:w-auto px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300 transition-all"> |
| 61 | `Reset` | UI | Reset |
| 81 | `Status` | UI | <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase tracking-wider">Status</th> |
| 94 | `` | UI | @if($item['type'] == 'tabungan_setor') |
| 98 | `` | UI | @elseif($item['type'] == 'tabungan_tarik') |
| 102 | `` | UI | @elseif($item['type'] == 'pinjaman') |
| 106 | `` | UI | @elseif($item['type'] == 'deposito') |
| 110 | `` | UI | @elseif($item['type'] == 'gadai') |
| 134 | `` | UI | {{ $item['status'] }} |
| 140 | `Detail` | UI | Detail |
| 146 | `` | UI | <span class="text-gray-400">Coming Soon</span> |
| 159 | `Pending` | UI | <p class="text-xl font-semibold text-gray-700 mb-2">Tidak Ada Pengajuan Pending</p> |
| 161 | `` | UI | <a href="{{ route('nasabah.dashboard') }}" class="inline-flex items-center px-6 py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-semibold hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-lg hover:shadow-xl"> |
| 162 | `Dashboard` | UI | Kembali ke Dashboard |

## ðŸ„ `nasabah\pinjaman\angsuran.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 7 | `Back` | UI | <!-- Back Button --> |
| 28 | `Filter` | UI | <!-- Filter Section --> |
| 35 | `Filter` | UI | <p class="text-xs font-semibold text-gray-700">Filter Angsuran</p> |
| 37 | `` | UI | <form method="GET" action="{{ route('nasabah.pinjaman.angsuran') }}" class="space-y-4"> |
| 41 | `` | UI | <select name="jenis" class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20"> |
| 44 | `` | UI | </select> |
| 47 | `Status` | UI | <label class="block text-xs font-semibold text-gray-700 mb-2">Status</label> |
| 48 | `` | UI | <select name="status" class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20"> |
| 49 | `Status` | UI | <option value="">Semua Status</option> |
| 50 | `` | UI | <option value="belum" {{ request('status') === 'belum' ? 'selected' : '' }}>Belum</option> |
| 51 | `` | UI | <option value="lunas" {{ request('status') === 'lunas' ? 'selected' : '' }}>Lunas</option> |
| 52 | `` | UI | <option value="telat" {{ request('status') === 'telat' ? 'selected' : '' }}>Telat</option> |
| 53 | `` | UI | </select> |
| 57 | `` | UI | <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" |
| 62 | `` | UI | <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" |
| 67 | `` | UI | <button type="submit" class="w-full md:w-auto bg-linear-to-r from-[#8b6f2f] to-[#a0824d] text-white font-semibold px-6 py-2 rounded-xl hover:shadow-lg transition-all"> |
| 68 | `Filter` | UI | Filter |
| 75 | `Detail` | UI | <!-- Table: dikelompokkan per pinjaman (No, Pinjaman, Detail Angsuran) --> |
| 84 | `Detail` | UI | <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase min-w-[280px]">Detail Angsuran (urut bulan terdekat)</th> |
| 110 | `Total` | UI | <th class="px-3 py-2 text-right font-semibold text-[#8b6f2f]">Total Harus Bayar</th> |
| 111 | `Status` | UI | <th class="px-3 py-2 text-left font-semibold text-[#8b6f2f]">Status</th> |
| 126 | `` | UI | onclick="window.location.href='{{ route('nasabah.pinjaman.detail-angsuran', ['id' => $t->id, 'jenis' => $jenis]) }}'"> |

## ðŸ„ `nasabah\pinjaman\detail-angsuran.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Detail` | UI | @section('title', 'Detail Angsuran') |
| 7 | `Back` | UI | <!-- Back Button & Struk --> |
| 12 | `Download` | UI | Download PDF Struk |
| 29 | `Detail` | UI | <h1 class="text-3xl font-bold text-white mb-2 font-display">Detail Angsuran</h1> |
| 81 | `Total` | UI | <p class="text-sm text-gray-500 mb-1">Total Tagihan + Denda</p> |
| 92 | `Status` | UI | <p class="text-sm text-gray-500 mb-1">Status Pembayaran</p> |
| 135 | `` | UI | <a href="{{ route('nasabah.pinjaman.detail-pinjaman', $angsuran->pinjaman->id) }}" |
| 137 | `Detail` | UI | Lihat Detail Pinjaman |
| 147 | `Transfer` | UI | <!-- Bukti Transfer (jika angsuran sudah dibayar) --> |
| 151 | `Transfer` | UI | <h2 class="text-lg font-bold text-[#8b6f2f] mb-4 font-display">Bukti Transfer</h2> |
| 159 | `` | PHP | $fileName = $filePath ? basename($filePath) : 'bukti-transfer'; |
| 164 | `` | UI | <img src="{{ $imageUrl }}" alt="Bukti Transfer" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200"> |
| 168 | `` | UI | <a href="{{ $imageUrl }}" download="{{ $fileName }}" class="shrink-0 inline-flex items-center gap-1 px-3 py-1.5 bg-[#8b6f2f] text-white text-xs font-semibold rounded-lg hover:bg-[#674c1d] transition-colors"> |
| 183 | `Back` | UI | <!-- Back Button --> |

## ðŸ„ `nasabah\pinjaman\detail-pembayaran.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Detail` | UI | @section('title', 'Detail Pembayaran') |
| 7 | `Back` | UI | <!-- Back Button & Struk --> |
| 12 | `Download` | UI | Download PDF Struk |
| 14 | `` | UI | <a href="{{ route('nasabah.pinjaman.status-pembayaran') }}" |
| 29 | `Detail` | UI | <h1 class="text-3xl font-bold text-white mb-2 font-display">Detail Pembayaran</h1> |
| 34 | `Pending` | UI | '1' => ['bg' => 'bg-yellow-500', 'label' => 'Pending'], |
| 39 | `` | PHP | $status = $statusConfig[$pengajuan->status] ?? $statusConfig['1']; |
| 41 | `` | UI | <span class="px-4 py-2 {{ $status['bg'] }} text-white rounded-full text-sm font-semibold"> |
| 42 | `` | UI | {{ $status['label'] }} |
| 71 | `Transfer` | UI | Transfer |
| 102 | `` | UI | <p class="text-gray-900 {{ in_array($pengajuan->status, ['2']) ? 'text-red-700 font-medium' : 'text-green-700' }}">{{ $pengajuan->keterangan_admin }}</p> |
| 108 | `` | UI | <!-- Bukti Foto (transfer / serah terima dari admin) --> |
| 112 | `Transfer` | UI | <h2 class="text-lg font-bold text-[#8b6f2f] mb-4 font-display">Bukti Transfer</h2> |
| 120 | `` | PHP | $fileName = $filePath ? basename($filePath) : 'bukti-transfer'; |
| 125 | `` | UI | <img src="{{ $imageUrl }}" alt="Bukti Transfer" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200"> |
| 129 | `` | UI | <a href="{{ $imageUrl }}" download="{{ $fileName }}" class="shrink-0 inline-flex items-center gap-1 px-3 py-1.5 bg-[#8b6f2f] text-white text-xs font-semibold rounded-lg hover:bg-[#674c1d] transition-colors"> |
| 172 | `Back` | UI | <!-- Back Button --> |
| 174 | `` | UI | <a href="{{ route('nasabah.pinjaman.status-pembayaran') }}" |
| 179 | `Status` | UI | Kembali ke Status Pembayaran |

## ðŸ„ `nasabah\pinjaman\detail-pengajuan.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Detail` | UI | @section('title', 'Detail Pengajuan Pinjaman') |
| 7 | `Back` | UI | <!-- Back Button --> |
| 9 | `` | UI | <a href="{{ route('nasabah.pinjaman.status-pengajuan') }}" |
| 24 | `Detail` | UI | <h1 class="text-3xl font-bold text-white mb-2 font-display">Detail Pengajuan</h1> |
| 28 | `` | UI | if ($pengajuan->status == '3' \|\| $pengajuan->status == '4' \|\| $pengajuan->pinjaman) { |
| 31 | `` | UI | } elseif ($pengajuan->status == '2') { |
| 36 | `Pending` | PHP | $badgeText = 'Pending'; |
| 81 | `Status` | UI | <!-- Status Info --> |
| 82 | `Status` | UI | <!-- Status Info --> |
| 83 | `` | UI | @if($pengajuan->status == '3' \|\| $pengajuan->status == '4' \|\| $pengajuan->pinjaman) |
| 100 | `` | UI | <a href="{{ route('nasabah.pinjaman.detail-pinjaman', ['id' => $pengajuan->pinjaman->id]) }}" |
| 102 | `Detail` | UI | Lihat Detail Pinjaman |
| 107 | `` | UI | @elseif($pengajuan->status == '2') |
| 139 | `Back` | UI | <!-- Back Button --> |
| 141 | `` | UI | <a href="{{ route('nasabah.pinjaman.status-pengajuan') }}" |
| 146 | `Status` | UI | Kembali ke Status Pengajuan |

## ðŸ„ `nasabah\pinjaman\detail-pinjaman.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Detail` | UI | @section('title', 'Detail Pinjaman') |
| 7 | `Back` | UI | <!-- Back Button & Struk --> |
| 12 | `Download` | UI | Download PDF Struk |
| 29 | `Detail` | UI | <h1 class="text-3xl font-bold text-white mb-2 font-display">Detail Pinjaman</h1> |
| 32 | `` | UI | <span class="px-4 py-2 {{ $pinjaman->status === 'telaksana' ? 'bg-green-500' : 'bg-yellow-500' }} text-white rounded-full text-sm font-semibold"> |
| 33 | `` | UI | {{ ucfirst($pinjaman->status) }} |
| 44 | `Total` | UI | <p class="text-sm text-gray-500 mb-1">Total Pinjaman</p> |
| 48 | `Total` | UI | <p class="text-sm text-gray-500 mb-1">Total Tagihan</p> |
| 52 | `Total` | UI | <p class="text-sm font-semibold text-gray-900 mt-0.5">Total kewajiban: Rp {{ number_format($totalKewajiban ?? $totalTagihan, 0, ',', '.') }}</p> |
| 56 | `Total` | UI | <p class="text-sm text-gray-500 mb-1">Total Terbayar</p> |
| 75 | `` | UI | <div class="bg-linear-to-r from-[#8b6f2f] to-[#d4af37] h-4 rounded-full transition-all duration-500" style="width: {{ number_format($progress, 2) }}%"></div> |
| 113 | `Status` | UI | <p class="text-sm text-gray-500 mb-1">Status</p> |
| 127 | `` | UI | <p class="text-sm text-gray-500 mb-4">Bukti transfer pencairan dana pinjaman dari admin. Klik untuk memperbesar atau unduh.</p> |
| 139 | `` | UI | <img src="{{ $imageUrl }}" alt="Bukti Pencairan" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200"> |
| 143 | `` | UI | <a href="{{ $imageUrl }}" download="{{ $fileName }}" class="shrink-0 inline-flex items-center gap-1 px-3 py-1.5 bg-[#8b6f2f] text-white text-xs font-semibold rounded-lg hover:bg-[#674c1d] transition-colors"> |
| 173 | `` | UI | <img src="{{ $imageUrl }}" alt="Bukti Pelunasan" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200"> |
| 177 | `` | UI | <a href="{{ $imageUrl }}" download="{{ $fileName }}" class="shrink-0 inline-flex items-center gap-1 px-3 py-1.5 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition-colors"> |
| 205 | `Total` | UI | <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase">Total Harus Bayar</th> |
| 206 | `Status` | UI | <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase">Status</th> |
| 217 | `` | UI | <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors cursor-pointer" onclick="window.location.href='{{ route('nasabah.pinjaman.detail-angsuran', ['id' => $item->id, 'jenis' => $pinjaman->jenis]) }}'"> |
| 275 | `Action` | UI | <!-- Action Buttons --> |
| 291 | `Back` | UI | <!-- Back Button --> |

## ðŸ„ `nasabah\pinjaman\index.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Dashboard` | UI | @section('title', 'Dashboard Pinjaman') |
| 27 | `Total` | UI | <p class="text-white/80 text-sm mb-1">Total Pinjaman Aktif</p> |
| 40 | `Menu` | UI | <!-- Quick Menu --> |
| 62 | `` | UI | <a href="{{ route('nasabah.pinjaman.status-pengajuan') }}" class="group bg-white/10 hover:bg-white/20 backdrop-blur-sm rounded-xl p-3 transition-all border border-white/20 flex items-center gap-3"> |
| 69 | `Status` | UI | <p class="text-white text-xs font-medium leading-tight">Status<br>Pengajuan</p> |
| 72 | `` | UI | <a href="{{ route('nasabah.pinjaman.status-pembayaran') }}" class="group bg-white/10 hover:bg-white/20 backdrop-blur-sm rounded-xl p-3 transition-all border border-white/20 flex items-center gap-3"> |
| 115 | `` | PHP | $urlDetail = route('nasabah.pinjaman.detail-angsuran', ['id' => $angsuran->id, 'jenis' => $jenisAngsuran]); |
| 152 | `` | UI | <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md hover:border-[#8b6f2f]/30 transition-all cursor-pointer group" onclick="window.location.href='{{ route('nasabah.pinjaman.detail-pinjaman', $pinjaman->id) }}'"> |
| 178 | `` | UI | <div class="bg-gradient-to-r from-[#a0824d] to-[#d4af37] h-2 rounded-full transition-all duration-500" style="width: {{ $persentase }}%"></div> |
| 191 | `` | UI | <p class="text-gray-500 text-sm mb-6 max-w-md">Anda belum memiliki pinjaman yang sedang berjalan. Ajukan pinjaman sekarang untuk memenuhi kebutuhan finansial Anda.</p> |
| 213 | `Total` | UI | <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Total Terbayar</th> |
| 214 | `Status` | UI | <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Status</th> |
| 219 | `` | UI | <tr class="border-b border-gray-50 hover:bg-[#8b6f2f]/5 transition-colors cursor-pointer" onclick="window.location.href='{{ route('nasabah.pinjaman.detail-pinjaman', $pinjaman->id) }}'"> |

## ðŸ„ `nasabah\pinjaman\janji-temu.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 8 | `` | UI | @if(session('success')) |
| 15 | `` | UI | <p class="font-semibold">{{ session('success') }}</p> |
| 21 | `` | UI | @if(session('error')) |
| 28 | `` | UI | <p class="font-semibold">{{ session('error') }}</p> |
| 44 | `` | UI | @foreach($errors->all() as $error) |
| 45 | `` | UI | <li>{{ $error }}</li> |
| 76 | `` | UI | <form method="POST" action="{{ route('nasabah.pinjaman.submit-janji-temu') }}" class="space-y-6" id="form-janji-temu"> |
| 78 | `` | UI | <input type="hidden" name="jenis_pencairan" value="cash"> |
| 85 | `` | UI | <input type="text" name="nominal" id="nominal" value="{{ old('nominal', request('nominal')) }}" placeholder="Masukkan nominal pinjaman" required |
| 87 | `` | UI | <input type="hidden" name="nominal_raw" id="nominal_raw" value="{{ old('nominal_raw') }}"> |
| 90 | `` | UI | @error('nominal') |
| 98 | `` | UI | <select name="durasi" id="durasi" required |
| 104 | `` | UI | </select> |
| 107 | `` | UI | @error('durasi') |
| 115 | `` | UI | <select name="lokasi_temu" id="lokasi_temu" required |
| 121 | `` | UI | </select> |
| 133 | `` | UI | @error('lokasi_temu') |
| 141 | `` | UI | <input type="date" name="tanggal_janji_temu" id="tanggal_janji_temu" value="{{ old('tanggal_janji_temu') }}" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" |
| 144 | `` | UI | @error('tanggal_janji_temu') |
| 152 | `` | UI | <input type="time" name="waktu_janji_temu" id="waktu_janji_temu" value="{{ old('waktu_janji_temu') }}" required |
| 155 | `` | UI | @error('waktu_janji_temu') |
| 163 | `` | UI | <textarea name="keterangan" rows="3" placeholder="Tambahkan keterangan jika diperlukan..." |
| 184 | `Total` | UI | <span class="font-semibold text-[#8b6f2f]">Total yang Harus Dibayar:</span> |
| 195 | `` | UI | <input type="hidden" name="pin" id="pinInput"> |
| 197 | `Submit` | UI | <!-- Submit Button --> |
| 199 | `` | UI | <button type="button" onclick="showPinModal()" class="w-full py-4 bg-linear-to-r from-[#8b6f2f] to-[#a0824d] text-white rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.02]"> |
| 233 | `` | UI | <div id="pin-error" class="hidden mb-4 p-3 bg-red-50 border border-red-200 rounded-lg"> |
| 239 | `` | UI | <input type="password" id="pin-input" maxlength="6" pattern="[0-9]*" inputmode="numeric" |
| 314 | `` | UI | durasiSelect.addEventListener('change', updateEstimasi); |
| 326 | `` | UI | document.getElementById('pin-modal').classList.remove('hidden'); |
| 327 | `` | UI | document.getElementById('pin-modal').classList.add('flex'); |
| 332 | `` | UI | document.getElementById('pin-modal').classList.add('hidden'); |
| 333 | `` | UI | document.getElementById('pin-modal').classList.remove('flex'); |
| 335 | `` | UI | document.getElementById('pin-error').classList.add('hidden'); |
| 349 | `` | UI | pinInput.type = 'hidden'; |
| 350 | `` | UI | pinInput.name = 'pin'; |
| 355 | `` | UI | form.submit(); |
| 359 | `` | UI | const errorDiv = document.getElementById('pin-error'); |
| 361 | `` | UI | errorDiv.classList.remove('hidden'); |

## ðŸ„ `nasabah\pinjaman\pembayaran.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 7 | `Back` | UI | <!-- Back Button --> |
| 29 | `` | UI | @if(session('error')) |
| 32 | `` | UI | <p class="text-red-700 text-sm">{{ session('error') }}</p> |
| 37 | `` | UI | @if(session('success')) |
| 40 | `` | UI | <p class="text-green-700 text-sm">{{ session('success') }}</p> |
| 55 | `` | UI | @foreach($errors->all() as $error) |
| 56 | `` | UI | <li>{{ $error }}</li> |
| 70 | `` | UI | <form action="{{ route('nasabah.pinjaman.pembayaran') }}" method="GET" class="mb-6"> |
| 74 | `` | UI | <select name="pinjaman_id" id="pinjaman_id" |
| 76 | `` | UI | onchange="this.form.submit()" required> |
| 85 | `` | UI | </select> |
| 91 | `` | UI | <form action="{{ route('nasabah.pinjaman.pembayaran') }}" method="GET" class="mb-6"> |
| 92 | `` | UI | <input type="hidden" name="pinjaman_id" value="{{ $selectedPinjaman->id }}"> |
| 95 | `` | UI | <select name="tempo_id" id="tempo_id" |
| 97 | `` | UI | onchange="this.form.submit()" required> |
| 109 | `` | UI | </select> |
| 147 | `Total` | UI | <span class="font-semibold text-[#8b6f2f]">Total yang harus dibayar:</span> |
| 155 | `Transfer` | UI | <!-- Tabs: Transfer / Janji Temu (Cash) --> |
| 158 | `` | UI | <button type="button" onclick="showTransferForm()" id="tab-transfer" aria-selected="true" |
| 159 | `` | UI | class="tab-payment flex-1 px-4 py-3.5 font-semibold border-b-2 border-[#8b6f2f] text-[#8b6f2f] transition-all duration-200 rounded-t-lg hover:bg-amber-50/50"> |
| 160 | `Transfer` | UI | Transfer |
| 162 | `` | UI | <button type="button" onclick="showCashForm()" id="tab-cash" aria-selected="false" |
| 163 | `` | UI | class="tab-payment flex-1 px-4 py-3.5 font-semibold border-b-2 border-transparent text-gray-500 hover:text-gray-700 transition-all duration-200 rounded-t-lg hover:bg-amber-50/50"> |
| 169 | `Transfer` | UI | <!-- Form Transfer --> |
| 170 | `` | UI | <div id="form-transfer-section"> |
| 171 | `` | UI | <form action="{{ route('nasabah.pinjaman.submit-pembayaran-transfer') }}" method="POST" |
| 172 | `` | UI | enctype="multipart/form-data" id="form-transfer"> |
| 174 | `` | UI | <input type="hidden" name="pinjaman_id" value="{{ $selectedPinjaman->id }}"> |
| 175 | `` | UI | <input type="hidden" name="tempo_id" value="{{ $selectedAngsuran->id }}"> |
| 176 | `` | UI | <input type="hidden" name="jenis_tempo" value="{{ $selectedPinjaman->jenis }}"> |
| 177 | `` | UI | <input type="hidden" name="pin" id="pin-transfer"> |
| 184 | `` | UI | <input type="text" name="nominal_display" id="nominal-transfer" required |
| 187 | `` | UI | <input type="hidden" name="nominal" id="nominal-transfer-raw"> |
| 197 | `` | UI | <select name="rekening_tujuan" required |
| 199 | `Transfer` | UI | <option value="">-- Pilih Rekening Tujuan Transfer --</option> |
| 205 | `` | UI | </select> |
| 208 | `Upload` | UI | <!-- Upload Bukti Transfer --> |
| 210 | `Upload` | UI | <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Bukti Transfer <span class="text-red-500">*</span></label> |
| 219 | `` | UI | <input type="file" name="bukti_foto[]" accept="image/*" required |
| 228 | `` | UI | <p class="text-sm text-gray-600">Klik untuk tambah bukti transfer</p> |
| 236 | `` | UI | <textarea name="keterangan" rows="3" |
| 241 | `` | UI | <button type="button" onclick="showPinModal('transfer')" |
| 250 | `` | UI | <form action="{{ route('nasabah.pinjaman.submit-janji-temu-pembayaran') }}" method="POST" |
| 253 | `` | UI | <input type="hidden" name="pinjaman_id" value="{{ $selectedPinjaman->id }}"> |
| 254 | `` | UI | <input type="hidden" name="tempo_id" value="{{ $selectedAngsuran->id }}"> |
| 255 | `` | UI | <input type="hidden" name="jenis_tempo" value="{{ $selectedPinjaman->jenis }}"> |
| 256 | `` | UI | <input type="hidden" name="pin" id="pin-cash"> |
| 263 | `` | UI | <input type="text" name="nominal_display" id="nominal-cash" required |
| 266 | `` | UI | <input type="hidden" name="nominal" id="nominal-cash-raw"> |
| 276 | `` | UI | <select name="lokasi_temu" required |
| 283 | `` | UI | </select> |
| 289 | `` | UI | <input type="date" name="tanggal_janji_temu" required |
| 290 | `` | UI | min="{{ date('Y-m-d', strtotime('+1 day')) }}" |
| 297 | `` | UI | <input type="time" name="waktu_janji_temu" required |
| 304 | `` | UI | <textarea name="keterangan" rows="3" |
| 309 | `` | UI | <button type="button" onclick="showPinModal('cash')" |
| 334 | `` | UI | <div id="pin-error" class="hidden mb-4 p-3 bg-red-50 border border-red-200 rounded-lg"> |
| 340 | `` | UI | <input type="password" id="pin-input" maxlength="6" pattern="[0-9]*" inputmode="numeric" |
| 361 | `` | UI | let currentFormType = 'transfer'; |
| 366 | `` | UI | const nominalTransfer = document.getElementById('nominal-transfer'); |
| 367 | `` | UI | const nominalTransferRaw = document.getElementById('nominal-transfer-raw'); |
| 416 | `` | UI | const tabs = document.querySelectorAll('.tab-payment'); |
| 421 | `` | UI | tab.classList.remove('border-transparent', 'text-gray-500'); |
| 422 | `` | UI | tab.classList.add('border-[#8b6f2f]', 'text-[#8b6f2f]'); |
| 424 | `` | UI | tab.classList.remove('border-[#8b6f2f]', 'text-[#8b6f2f]'); |
| 425 | `` | UI | tab.classList.add('border-transparent', 'text-gray-500'); |
| 431 | `` | UI | document.getElementById('form-transfer-section').classList.remove('hidden'); |
| 432 | `` | UI | document.getElementById('form-cash-section').classList.add('hidden'); |
| 433 | `` | UI | setActiveTab('tab-transfer'); |
| 434 | `` | UI | currentFormType = 'transfer'; |
| 438 | `` | UI | document.getElementById('form-transfer-section').classList.add('hidden'); |
| 439 | `` | UI | document.getElementById('form-cash-section').classList.remove('hidden'); |
| 457 | `` | UI | <input type="file" name="bukti_foto[]" accept="image/*" required |
| 461 | `` | UI | <button type="button" onclick="this.closest('.relative').remove();" class="shrink-0 w-8 h-8 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg flex items-center justify-center transition-colors"> |
| 473 | `` | UI | const form = formType === 'transfer' ? document.getElementById('form-transfer') : document.getElementById( |
| 481 | `` | UI | if (formType === 'transfer') { |
| 482 | `` | UI | const fileInputs = form.querySelectorAll('input[type="file"]'); |
| 486 | `` | UI | alert('Ukuran file ' + input.files[0].name + ' terlalu besar. Maksimal 5MB.'); |
| 494 | `` | UI | const nominalRaw = formType === 'transfer' ? |
| 495 | `` | UI | document.getElementById('nominal-transfer-raw').value : |
| 511 | `` | UI | document.getElementById('pin-modal').classList.remove('hidden'); |
| 512 | `` | UI | document.getElementById('pin-modal').classList.add('flex'); |
| 514 | `` | UI | document.getElementById('pin-error').classList.add('hidden'); |
| 519 | `` | UI | document.getElementById('pin-modal').classList.add('hidden'); |
| 520 | `` | UI | document.getElementById('pin-modal').classList.remove('flex'); |
| 532 | `` | UI | if (currentFormType === 'transfer') { |
| 533 | `` | UI | document.getElementById('pin-transfer').value = pin; |
| 535 | `` | UI | document.getElementById('form-transfer').submit(); |
| 539 | `` | UI | document.getElementById('form-cash').submit(); |
| 544 | `` | UI | const errorDiv = document.getElementById('pin-error'); |
| 546 | `` | UI | errorDiv.classList.remove('hidden'); |

## ðŸ„ `nasabah\pinjaman\pengajuan-pinjaman.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 7 | `Back` | UI | <!-- Back Button --> |
| 19 | `` | UI | @if(session('success')) |
| 26 | `` | UI | <p class="font-semibold">{{ session('success') }}</p> |
| 31 | `` | UI | @if(session('error')) |
| 38 | `` | UI | <p class="font-semibold">{{ session('error') }}</p> |
| 53 | `` | UI | @foreach($errors->all() as $error) |
| 54 | `` | UI | <li>{{ $error }}</li> |
| 86 | `Transfer` | UI | <!-- Metode Transfer --> |
| 87 | `` | UI | <button type="button" onclick="selectMethod('transfer')" id="btn-transfer" class="group p-6 bg-linear-to-br from-gray-50 to-gray-100 rounded-2xl border-2 border-gray-200 hover:border-[#8b6f2f] transition-all text-left"> |
| 95 | `Transfer` | UI | <h3 class="text-lg font-bold text-gray-900 mb-1">Transfer</h3> |
| 103 | `` | UI | <span>Pencairan via transfer bank</span> |
| 108 | `` | UI | <button type="button" onclick="selectMethod('tunai')" id="btn-tunai" class="group p-6 bg-linear-to-br from-gray-50 to-gray-100 rounded-2xl border-2 border-gray-200 hover:border-[#674c1d] transition-all text-left"> |
| 132 | `Transfer` | UI | <!-- Form Transfer (muncul di bawah, inline) --> |
| 133 | `` | UI | <div id="form-transfer-section" class="mx-4 mb-6 hidden"> |
| 135 | `Transfer` | UI | <h2 class="text-lg font-bold text-gray-900 font-display mb-6">Formulir Pengajuan Pinjaman Transfer</h2> |
| 137 | `` | UI | <form id="form-transfer" method="POST" action="{{ route('nasabah.pinjaman.submit-pengajuan-transfer') }}" class="space-y-6"> |
| 139 | `` | UI | <input type="hidden" name="jenis_pencairan" value="transfer"> |
| 145 | `` | UI | <input type="text" name="nominal" id="nominal-transfer" placeholder="0" required |
| 148 | `` | UI | <input type="hidden" name="nominal_raw" id="nominal_raw_transfer" value="{{ old('nominal_raw') }}"> |
| 151 | `` | UI | @error('nominal')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror |
| 156 | `` | UI | <select name="durasi" id="durasi-transfer" required |
| 162 | `` | UI | </select> |
| 164 | `` | UI | @error('durasi')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror |
| 170 | `` | UI | <textarea name="keterangan" rows="3" placeholder="Tambahkan keterangan jika diperlukan..." |
| 188 | `Total` | UI | <span class="text-sm text-gray-600">Total yang Harus Dibayar:</span> |
| 208 | `Total` | UI | <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Total</th> |
| 218 | `` | UI | <button type="button" onclick="showPinModalTransfer()" class="w-full py-4 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.02]"> |
| 231 | `` | UI | <form id="form-tunai" method="POST" action="{{ route('nasabah.pinjaman.submit-janji-temu') }}" class="space-y-6"> |
| 233 | `` | UI | <input type="hidden" name="jenis_pencairan" value="tunai"> |
| 239 | `` | UI | <input type="text" name="nominal" id="nominal-tunai" placeholder="0" required |
| 242 | `` | UI | <input type="hidden" name="nominal_raw" id="nominal_raw_tunai" value="{{ old('nominal_raw') }}"> |
| 245 | `` | UI | @error('nominal')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror |
| 250 | `` | UI | <select name="durasi" id="durasi-tunai" required |
| 257 | `` | UI | </select> |
| 258 | `` | UI | @error('durasi')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror |
| 263 | `` | UI | <select name="lokasi_temu" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none"> |
| 268 | `` | UI | </select> |
| 269 | `` | UI | @error('lokasi_temu')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror |
| 274 | `` | UI | <input type="date" name="tanggal_janji_temu" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" |
| 277 | `` | UI | @error('tanggal_janji_temu')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror |
| 282 | `` | UI | <input type="time" name="waktu_janji_temu" required |
| 285 | `` | UI | @error('waktu_janji_temu')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror |
| 290 | `` | UI | <textarea name="keterangan" rows="3" placeholder="Tambahkan keterangan jika diperlukan..." |
| 294 | `` | UI | <!-- Estimasi & Simulasi (sama seperti form transfer) --> |
| 307 | `Total` | UI | <span class="text-sm text-gray-600">Total yang Harus Dibayar:</span> |
| 327 | `Total` | UI | <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Total</th> |
| 337 | `` | UI | <button type="button" onclick="showPinModalTunai()" class="w-full py-4 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.02]"> |
| 357 | `Status` | UI | <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase">Status</th> |
| 362 | `` | UI | <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors cursor-pointer" role="button" tabindex="0" data-href="{{ route('nasabah.pinjaman.detail-pengajuan', $pengajuan->id) }}" onclick="window.location.href=this.dataset.href"> |
| 375 | `Pending` | PHP | $statusLabel = 'Pending'; |
| 376 | `` | UI | if (($pengajuan->status ?? '1') == '2') $statusLabel = 'Ditolak'; |
| 377 | `` | UI | elseif (($pengajuan->status ?? '1') == '3') $statusLabel = 'Disetujui'; |
| 378 | `` | UI | elseif (($pengajuan->status ?? '1') == '4') $statusLabel = 'Terlaksana'; |
| 381 | `` | UI | @if(($pengajuan->status ?? '1') == '4') bg-green-100 text-green-700 |
| 382 | `` | UI | @elseif(($pengajuan->status ?? '1') == '2') bg-red-100 text-red-700 |
| 383 | `` | UI | @elseif(($pengajuan->status ?? '1') == '3') bg-blue-100 text-blue-700 |
| 399 | `Transfer` | UI | <!-- PIN Modal Transfer --> |
| 400 | `` | UI | <div id="pin-modal-transfer" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4"> |
| 414 | `` | UI | <button type="button" onclick="closePinModalTransfer()" class="text-gray-400 hover:text-gray-600"> |
| 422 | `` | UI | <input type="password" id="pin-input-transfer" maxlength="6" placeholder="••••••" |
| 425 | `` | UI | <p id="pin-error-transfer" class="hidden text-sm text-red-600 mt-2"></p> |
| 427 | `` | UI | <button type="button" onclick="submitFormTransfer()" class="w-full py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-bold hover:shadow-lg transition-all">Konfirmasi</button> |
| 446 | `` | UI | <button type="button" onclick="closePinModalTunai()" class="text-gray-400 hover:text-gray-600"> |
| 454 | `` | UI | <input type="password" id="pin-input-tunai" maxlength="6" placeholder="••••••" |
| 457 | `` | UI | <p id="pin-error-tunai" class="hidden text-sm text-red-600 mt-2"></p> |
| 459 | `` | UI | <button type="button" onclick="submitFormTunai()" class="w-full py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-bold hover:shadow-lg transition-all">Konfirmasi</button> |
| 469 | `` | UI | btn.classList.remove('border-[#674c1d]', 'border-[#8b6f2f]', 'bg-linear-to-br', 'from-[#674c1d]/10', 'to-[#8b6f2f]/10'); |
| 470 | `` | UI | btn.classList.add('border-gray-200'); |
| 472 | `` | UI | document.getElementById('form-transfer-section').classList.add('hidden'); |
| 473 | `` | UI | document.getElementById('form-tunai-section').classList.add('hidden'); |
| 475 | `` | UI | if (method === 'transfer') { |
| 476 | `` | UI | document.getElementById('btn-transfer').classList.add('border-[#8b6f2f]', 'bg-linear-to-br', 'from-[#8b6f2f]/10', 'to-[#d4af37]/10'); |
| 477 | `` | UI | document.getElementById('form-transfer-section').classList.remove('hidden'); |
| 480 | `` | UI | document.getElementById('btn-tunai').classList.add('border-[#674c1d]', 'bg-linear-to-br', 'from-[#674c1d]/10', 'to-[#8b6f2f]/10'); |
| 481 | `` | UI | document.getElementById('form-tunai-section').classList.remove('hidden'); |
| 485 | `` | UI | const el = method === 'transfer' ? document.getElementById('form-transfer-section') : document.getElementById('form-tunai-section'); |
| 510 | `` | UI | const durasi = parseInt(document.getElementById('durasi-transfer').value) \|\| 0; |
| 523 | `Type` | UI | headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }, |
| 528 | `` | UI | if (!data.success) return; |
| 539 | `` | UI | tr.innerHTML = '<td class="px-4 py-3 text-sm text-gray-900">' + item.bulan + '</td><td class="px-4 py-3 text-sm text-gray-700">' + item.tanggal + '</td><td class="px-4 py-3 text-sm font-semibold text-[#8b6f2f] text-right">Rp ' + item.total.toLocaleString('id-ID') + '</td>'; |
| 564 | `Type` | UI | headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }, |
| 569 | `` | UI | if (!data.success) return; |
| 580 | `` | UI | tr.innerHTML = '<td class="px-4 py-3 text-sm text-gray-900">' + item.bulan + '</td><td class="px-4 py-3 text-sm text-gray-700">' + item.tanggal + '</td><td class="px-4 py-3 text-sm font-semibold text-[#8b6f2f] text-right">Rp ' + item.total.toLocaleString('id-ID') + '</td>'; |
| 590 | `` | UI | const form = document.getElementById('form-transfer'); |
| 592 | `` | UI | document.getElementById('pin-modal-transfer').classList.remove('hidden'); |
| 593 | `` | UI | document.getElementById('pin-modal-transfer').classList.add('flex'); |
| 594 | `` | UI | document.getElementById('pin-input-transfer').focus(); |
| 598 | `` | UI | document.getElementById('pin-modal-transfer').classList.add('hidden'); |
| 599 | `` | UI | document.getElementById('pin-modal-transfer').classList.remove('flex'); |
| 600 | `` | UI | document.getElementById('pin-input-transfer').value = ''; |
| 601 | `` | UI | document.getElementById('pin-error-transfer').classList.add('hidden'); |
| 605 | `` | UI | const pin = document.getElementById('pin-input-transfer').value; |
| 607 | `` | UI | document.getElementById('pin-error-transfer').textContent = 'PIN harus 6 digit'; |
| 608 | `` | UI | document.getElementById('pin-error-transfer').classList.remove('hidden'); |
| 611 | `` | UI | fetch('{{ route("nasabah.pinjaman.verify-pin") }}', { |
| 613 | `Type` | UI | headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, |
| 618 | `` | UI | if (data.success) { |
| 619 | `` | UI | const nominalInput = document.getElementById('nominal-transfer'); |
| 624 | `` | UI | pinInput.type = 'hidden'; |
| 625 | `` | UI | pinInput.name = 'pin'; |
| 627 | `` | UI | document.getElementById('form-transfer').appendChild(pinInput); |
| 628 | `` | UI | document.getElementById('form-transfer').submit(); |
| 630 | `` | UI | document.getElementById('pin-error-transfer').textContent = data.message \|\| 'PIN salah'; |
| 631 | `` | UI | document.getElementById('pin-error-transfer').classList.remove('hidden'); |
| 635 | `` | UI | document.getElementById('pin-error-transfer').textContent = 'Terjadi kesalahan, coba lagi'; |
| 636 | `` | UI | document.getElementById('pin-error-transfer').classList.remove('hidden'); |
| 643 | `` | UI | document.getElementById('pin-modal-tunai').classList.remove('hidden'); |
| 644 | `` | UI | document.getElementById('pin-modal-tunai').classList.add('flex'); |
| 649 | `` | UI | document.getElementById('pin-modal-tunai').classList.add('hidden'); |
| 650 | `` | UI | document.getElementById('pin-modal-tunai').classList.remove('flex'); |
| 652 | `` | UI | document.getElementById('pin-error-tunai').classList.add('hidden'); |
| 658 | `` | UI | document.getElementById('pin-error-tunai').textContent = 'PIN harus 6 digit'; |
| 659 | `` | UI | document.getElementById('pin-error-tunai').classList.remove('hidden'); |
| 662 | `` | UI | fetch('{{ route("nasabah.pinjaman.verify-pin") }}', { |
| 664 | `Type` | UI | headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, |
| 669 | `` | UI | if (data.success) { |
| 675 | `` | UI | pinInput.type = 'hidden'; |
| 676 | `` | UI | pinInput.name = 'pin'; |
| 679 | `` | UI | document.getElementById('form-tunai').submit(); |
| 681 | `` | UI | document.getElementById('pin-error-tunai').textContent = data.message \|\| 'PIN salah'; |
| 682 | `` | UI | document.getElementById('pin-error-tunai').classList.remove('hidden'); |
| 686 | `` | UI | document.getElementById('pin-error-tunai').textContent = 'Terjadi kesalahan, coba lagi'; |
| 687 | `` | UI | document.getElementById('pin-error-tunai').classList.remove('hidden'); |
| 691 | `` | UI | document.getElementById('durasi-transfer').addEventListener('change', updateEstimasiTransfer); |
| 692 | `` | UI | document.getElementById('durasi-tunai').addEventListener('change', updateEstimasiTunai); |
| 695 | `` | UI | if (openMetode === 'transfer') selectMethod('transfer'); |

## ðŸ„ `nasabah\pinjaman\pengajuan-transfer.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Transfer` | UI | @section('title', 'Pengajuan Pinjaman Transfer') |
| 8 | `` | UI | @if(session('success')) |
| 15 | `` | UI | <p class="font-semibold">{{ session('success') }}</p> |
| 21 | `` | UI | @if(session('error')) |
| 28 | `` | UI | <p class="font-semibold">{{ session('error') }}</p> |
| 44 | `` | UI | @foreach($errors->all() as $error) |
| 45 | `` | UI | <li>{{ $error }}</li> |
| 64 | `Transfer` | UI | <h1 class="text-2xl font-bold text-white font-display mb-1">Pengajuan Pinjaman Transfer</h1> |
| 65 | `` | UI | <p class="text-white/90 text-sm">Isi form untuk pengajuan pinjaman via transfer</p> |
| 76 | `` | UI | <form action="{{ route('nasabah.pinjaman.submit-pengajuan-transfer') }}" method="POST" id="formPengajuan"> |
| 78 | `` | UI | <input type="hidden" name="jenis_pencairan" value="transfer"> |
| 85 | `` | UI | <input type="text" name="nominal" id="nominal" |
| 88 | `` | UI | <input type="hidden" name="nominal_raw" id="nominal_raw" value="{{ old('nominal_raw') }}"> |
| 91 | `` | UI | @error('nominal') |
| 99 | `` | UI | <select name="durasi" id="durasi" |
| 105 | `` | UI | </select> |
| 107 | `` | UI | @error('durasi') |
| 115 | `` | UI | <textarea name="keterangan" id="keterangan" rows="3" |
| 137 | `Total` | UI | <span class="font-semibold text-[#8b6f2f]">Total yang Harus Dibayar:</span> |
| 161 | `Total` | UI | <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Total</th> |
| 173 | `` | UI | <input type="hidden" name="pin" id="pinInput"> |
| 175 | `Submit` | UI | <!-- Submit Button --> |
| 176 | `` | UI | <button type="button" id="btnSubmitPengajuan" |
| 205 | `` | UI | <input type="password" name="pin" id="pin" maxlength="6" required autofocus |
| 212 | `` | UI | <button type="button" onclick="closePinModal()" |
| 216 | `` | UI | <button type="submit" id="verifyPinButton" |
| 291 | `Type` | UI | 'Content-Type': 'application/json', |
| 302 | `` | UI | if (data.success) { |
| 320 | `` | UI | <td class="px-4 py-3 text-sm font-semibold text-[#8b6f2f] text-right">Rp ${item.total.toLocaleString('id-ID')}</td> |
| 330 | `` | UI | .catch(error => { |
| 331 | `Error` | UI | console.error('Error:', error); |
| 336 | `` | UI | durasiSelect.addEventListener('change', updateEstimasi); |
| 346 | `` | UI | pinModal.classList.remove('hidden'); |
| 347 | `` | UI | pinModal.classList.add('flex'); |
| 351 | `` | UI | pinForm.addEventListener('submit', function(e) { |
| 358 | `` | UI | pinError.classList.remove('hidden'); |
| 362 | `` | UI | verifyPinButtonText.classList.add('hidden'); |
| 363 | `` | UI | verifyPinButtonLoading.classList.remove('hidden'); |
| 365 | `` | UI | pinError.classList.add('hidden'); |
| 367 | `` | UI | fetch('{{ route("nasabah.pinjaman.verify-pin") }}', { |
| 370 | `Type` | UI | 'Content-Type': 'application/json', |
| 379 | `Error` | UI | throw new Error(err.message \|\| 'Network response was not ok'); |
| 385 | `` | UI | if (data.success) { |
| 389 | `` | UI | formPengajuan.submit(); |
| 393 | `` | UI | pinError.classList.remove('hidden'); |
| 394 | `` | UI | verifyPinButtonText.classList.remove('hidden'); |
| 395 | `` | UI | verifyPinButtonLoading.classList.add('hidden'); |
| 401 | `` | UI | .catch(error => { |
| 402 | `Error` | UI | console.error('Error verifying PIN:', error); |
| 403 | `` | UI | pinError.textContent = error.message \|\| 'Terjadi kesalahan. Silakan coba lagi.'; |
| 404 | `` | UI | pinError.classList.remove('hidden'); |
| 405 | `` | UI | verifyPinButtonText.classList.remove('hidden'); |
| 406 | `` | UI | verifyPinButtonLoading.classList.add('hidden'); |
| 412 | `` | UI | pinModal.classList.add('hidden'); |
| 413 | `` | UI | pinModal.classList.remove('flex'); |
| 415 | `` | UI | pinError.classList.add('hidden'); |
| 416 | `` | UI | verifyPinButtonText.classList.remove('hidden'); |
| 417 | `` | UI | verifyPinButtonLoading.classList.add('hidden'); |

## ðŸ„ `nasabah\pinjaman\pinjaman-aktif.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 7 | `Back` | UI | <!-- Back Button --> |
| 28 | `Filter` | UI | <!-- Filter Section --> |
| 35 | `Filter` | UI | <p class="text-xs font-semibold text-gray-700">Filter Pinjaman</p> |
| 38 | `` | UI | <form method="GET" action="{{ route('nasabah.pinjaman.pinjaman-aktif') }}" class="flex gap-4"> |
| 41 | `` | UI | <select name="jenis" class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20"> |
| 45 | `` | UI | </select> |
| 64 | `Status` | UI | <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase">Status</th> |
| 81 | `` | UI | <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors cursor-pointer" onclick="window.location.href='{{ route('nasabah.pinjaman.detail-pinjaman', $item->id) }}'"> |

## ðŸ„ `nasabah\pinjaman\status-pembayaran.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Status` | UI | @section('title', 'Status Pembayaran Pinjaman') |
| 7 | `Back` | UI | <!-- Back Button --> |
| 22 | `Status` | UI | <h1 class="text-3xl font-bold text-white mb-2 font-display">Status Pembayaran Pinjaman</h1> |
| 23 | `` | UI | <p class="text-white/90 text-sm">Lihat status pengajuan pembayaran Anda</p> |
| 28 | `` | UI | @if(session('success')) |
| 31 | `` | UI | <p class="text-green-700 text-sm">{{ session('success') }}</p> |
| 36 | `Filter` | UI | <!-- Filter --> |
| 39 | `` | UI | <form method="GET" action="{{ route('nasabah.pinjaman.status-pembayaran') }}" class="flex gap-4"> |
| 40 | `` | UI | <select name="status" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg"> |
| 41 | `Status` | UI | <option value="">Semua Status</option> |
| 42 | `Pending` | UI | <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Pending</option> |
| 43 | `` | UI | <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Ditolak</option> |
| 44 | `` | UI | <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>Disetujui</option> |
| 45 | `` | UI | <option value="4" {{ request('status') == '4' ? 'selected' : '' }}>Terlaksana</option> |
| 46 | `` | UI | </select> |
| 47 | `` | UI | <button type="submit" class="px-6 py-2 bg-[#8b6f2f] text-white rounded-lg hover:bg-[#a0824d] transition-colors"> |
| 48 | `Filter` | UI | Filter |
| 67 | `Status` | UI | <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase">Status</th> |
| 73 | `` | UI | onclick="window.location.href='{{ route('nasabah.pinjaman.detail-pembayaran', $item->id) }}'"> |
| 86 | `Transfer` | UI | Transfer |
| 97 | `Pending` | UI | '1' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Pending'], |
| 102 | `` | PHP | $status = $statusConfig[$item->status] ?? $statusConfig['1']; |
| 104 | `` | UI | <span class="px-3 py-1 {{ $status['bg'] }} {{ $status['text'] }} rounded-full text-xs font-semibold"> |
| 105 | `` | UI | {{ $status['label'] }} |

## ðŸ„ `nasabah\pinjaman\status-pengajuan.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Status` | UI | @section('title', 'Status Pengajuan Pinjaman') |
| 7 | `Back` | UI | <!-- Back Button --> |
| 22 | `Status` | UI | <h1 class="text-3xl font-bold text-white mb-2 font-display">Status Pengajuan Pinjaman</h1> |
| 23 | `` | UI | <p class="text-white/90 text-sm">Lihat status pengajuan pinjaman Anda</p> |
| 28 | `Filter` | UI | <!-- Filter Section --> |
| 35 | `Filter` | UI | <p class="text-xs font-semibold text-gray-700">Filter Pengajuan</p> |
| 38 | `` | UI | <form method="GET" action="{{ route('nasabah.pinjaman.status-pengajuan') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4"> |
| 40 | `Status` | UI | <label class="block text-xs font-semibold text-gray-700 mb-2">Status</label> |
| 41 | `` | UI | <select name="status" class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20"> |
| 42 | `Status` | UI | <option value="">Semua Status</option> |
| 43 | `Pending` | UI | <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option> |
| 44 | `` | UI | <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option> |
| 45 | `` | UI | </select> |
| 49 | `` | UI | <select name="jenis" class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20"> |
| 53 | `` | UI | </select> |
| 56 | `` | UI | <button type="submit" class="w-full bg-linear-to-r from-[#8b6f2f] to-[#a0824d] text-white font-semibold py-2 rounded-xl hover:shadow-lg transition-all"> |
| 57 | `Filter` | UI | Filter |
| 75 | `Status` | UI | <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase">Status</th> |
| 80 | `` | UI | <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors cursor-pointer" role="button" tabindex="0" data-href="{{ route('nasabah.pinjaman.detail-pengajuan', $item->id) }}" onclick="window.location.href=this.dataset.href"> |
| 97 | `Pending` | UI | {{ $item->pinjaman ? 'Disetujui' : 'Pending' }} |

## ðŸ„ `nasabah\profile.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Profile` | UI | @section('title', 'Profile') |
| 7 | `Profile` | UI | <!-- Profile Header --> |
| 31 | `` | UI | <p class="text-white/90 text-sm mb-4">{{ $nasabah->user->email ?? 'N/A' }}</p> |
| 33 | `Status` | UI | <!-- Status Badge --> |
| 61 | `Profile` | UI | <!-- Edit Button for User Profile --> |
| 66 | `Edit` | UI | Edit Profil |
| 73 | `Pending` | UI | <!-- Pending Requests Notification --> |
| 95 | `` | UI | <form action="{{ route('nasabah.profile.cancel-request', $request->id) }}" method="POST" class="inline" |
| 96 | `` | UI | onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan ini?')"> |
| 98 | `` | UI | @method('DELETE') |
| 99 | `` | UI | <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium transition-colors"> |
| 128 | `Edit` | UI | Edit |
| 132 | `Note` | UI | <!-- Info Note --> |
| 139 | `Edit` | UI | <strong>Catatan:</strong> Untuk mengedit <strong>Nama</strong> dan <strong>Email</strong> yang tampil di header profil, gunakan tombol <strong>"Edit Profil"</strong> di bagian atas. Tombol "Edit" di sini untuk mengedit data pribadi lengkap (KTP, tempat lahir, alamat, dll). |
| 150 | `Email` | UI | <p class="text-sm text-gray-600 mb-1">Email</p> |
| 151 | `` | UI | <p class="font-semibold text-gray-900">{{ $nasabah->user->email ?? 'N/A' }}</p> |
| 209 | `Edit` | UI | Edit |
| 248 | `Edit` | UI | Edit |
| 297 | `Email` | UI | <p class="text-sm text-gray-600 mb-1">Email</p> |
| 298 | `` | UI | <p class="font-semibold text-gray-900">{{ $nasabah->darurat->email ?? 'N/A' }}</p> |
| 335 | `Total` | UI | <p class="text-sm text-blue-700 mb-1">Total Setoran</p> |
| 339 | `Total` | UI | <p class="text-sm text-red-700 mb-1">Total Penarikan</p> |
| 358 | `` | UI | <p class="text-white/90 text-sm">Kelola password dan PIN transaksi</p> |
| 373 | `Edit` | UI | <!-- Modal Edit Data Profil --> |
| 384 | `Edit` | UI | <h3 class="text-xl font-bold text-white" id="editModalTitle">Edit Data</h3> |
| 395 | `` | UI | <input type="hidden" id="edit_jenis_data" name="jenis_data" value=""> |
| 412 | `` | UI | <button type="button" onclick="closeEditModal()" |
| 416 | `` | UI | <button type="button" onclick="submitEditForm()" |
| 425 | `Profile` | UI | <!-- Modal PIN Verification untuk Edit Profile --> |
| 438 | `` | UI | <form action="{{ route('nasabah.profile.update-request') }}" method="POST" id="pinVerificationForm"> |
| 440 | `` | UI | <input type="hidden" id="pin_jenis_data" name="jenis_data" value=""> |
| 448 | `` | UI | <input type="password" name="pin" id="pin_verification" maxlength="6" |
| 456 | `` | UI | <button type="button" onclick="closePinVerificationModal()" |
| 460 | `` | UI | <button type="submit" |
| 471 | `` | UI | <!-- ==================== EDIT PROFILE FUNCTIONS ====================--> |
| 476 | `Edit` | UI | title: 'Edit Data Akun User', |
| 478 | `Name` | UI | { name: 'nama', label: 'Nama (Display Name)', type: 'text', value: '{{ $nasabah->user->nama ?? "" }}' }, |
| 479 | `` | UI | { name: 'email', label: 'Email', type: 'email', value: '{{ $nasabah->user->email ?? "" }}' }, |
| 480 | `` | UI | { name: 'nomor_hp', label: 'Nomor HP', type: 'text', value: '{{ $nasabah->user->nomor_hp ?? "" }}' } |
| 484 | `Edit` | UI | title: 'Edit Data Pribadi', |
| 486 | `` | UI | { name: 'nama', label: 'Nama Lengkap', type: 'text', value: '{{ $nasabah->dataKtp->nama_lengkap ?? $nasabah->user->nama ?? "" }}' }, |
| 487 | `` | UI | { name: 'email', label: 'Email', type: 'email', value: '{{ $nasabah->user->email ?? "" }}' }, |
| 488 | `` | UI | { name: 'nomor_hp', label: 'Nomor HP', type: 'text', value: '{{ $nasabah->user->nomor_hp ?? "" }}' }, |
| 489 | `` | UI | { name: 'no_kk', label: 'No. KK', type: 'text', value: '{{ $nasabah->no_kk ?? "" }}' }, |
| 490 | `` | UI | { name: 'tempat_lahir', label: 'Tempat Lahir', type: 'text', value: '{{ $nasabah->tempat_lahir ?? ($nasabah->dataKtp->tempat_lahir ?? "") }}' }, |
| 491 | `` | UI | { name: 'tanggal_lahir', label: 'Tanggal Lahir', type: 'date', value: '{{ $nasabah->tanggal_lahir ? $nasabah->tanggal_lahir->format("Y-m-d") : ($nasabah->dataKtp && $nasabah->dataKtp->tanggal_lahir ? $nasabah->dataKtp->tanggal_lahir->format("Y-m-d") : "") }}' }, |
| 492 | `` | UI | { name: 'jenis_kelamin', label: 'Jenis Kelamin', type: 'select', value: '{{ $nasabah->jenis_kelamin ?? "" }}', options: [{value: 'L', label: 'Laki-laki'}, {value: 'P', label: 'Perempuan'}] }, |
| 493 | `` | UI | { name: 'alamat', label: 'Alamat', type: 'textarea', value: '{{ $nasabah->alamat ?? ($nasabah->dataKtp->alamat ?? "") }}' } |
| 497 | `Edit` | UI | title: 'Edit Data Pekerjaan', |
| 499 | `` | UI | { name: 'pekerjaan', label: 'Pekerjaan', type: 'text', value: '{{ $nasabah->pekerjaan->pekerjaan ?? "" }}' }, |
| 500 | `` | UI | { name: 'nama_perusahaan', label: 'Nama Perusahaan', type: 'text', value: '{{ $nasabah->pekerjaan->nama_perusahaan ?? "" }}' }, |
| 501 | `` | UI | { name: 'penghasilan', label: 'Penghasilan', type: 'text', value: '{{ $nasabah->pekerjaan->penghasilan ?? "" }}' } |
| 505 | `Edit` | UI | title: 'Edit Data Rekening Bank', |
| 507 | `` | UI | { name: 'nama_bank', label: 'Nama Bank', type: 'text', value: '{{ $nasabah->dataRek->nama_bank ?? "" }}' }, |
| 508 | `` | UI | { name: 'no_rekening', label: 'No. Rekening', type: 'text', value: '{{ $nasabah->dataRek->no_rekening ?? "" }}' }, |
| 509 | `` | UI | { name: 'nama_pemilik_rekening', label: 'Nama Pemilik Rekening', type: 'text', value: '{{ $nasabah->dataRek->nama_pemilik_rekening ?? "" }}' } |
| 537 | `` | UI | if (field.type === 'textarea') { |
| 538 | `` | UI | html += `<textarea name="${field.name}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#8b6f2f] focus:border-[#8b6f2f]" rows="3">${field.value}</textarea>`; |
| 539 | `` | UI | } else if (field.type === 'select') { |
| 540 | `` | UI | html += `<select name="${field.name}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#8b6f2f] focus:border-[#8b6f2f]">`; |
| 546 | `` | UI | html += '</select>'; |
| 548 | `` | UI | html += `<input type="${field.type}" name="${field.name}" value="${field.value}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#8b6f2f] focus:border-[#8b6f2f]">`; |
| 556 | `` | UI | modal.classList.remove('hidden'); |
| 563 | `` | UI | modal.classList.add('hidden'); |
| 583 | `` | UI | html += `<input type="hidden" name="${key}" value="${value}">`; |
| 596 | `` | UI | modal.classList.remove('hidden'); |
| 604 | `` | UI | modal.classList.add('hidden'); |

## ðŸ„ `nasabah\setting\index.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 18 | `` | UI | <p class="text-white/90 text-sm">Kelola password dan PIN Anda</p> |
| 25 | `` | UI | @if(session('success')) |
| 33 | `` | UI | <p class="text-green-700 font-semibold">{{ session('success') }}</p> |
| 55 | `` | UI | @if(session('error')) |
| 63 | `` | UI | <p class="text-red-700 font-semibold">{{ session('error') }}</p> |
| 70 | `Navigation` | UI | <!-- Tab Navigation --> |
| 74 | `` | UI | <button onclick="switchTab('password')" id="tab-password" class="flex-1 py-3 px-4 rounded-xl font-semibold transition-all bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white shadow-md"> |
| 78 | `Password` | UI | Password |
| 90 | `Password` | UI | <!-- Password Tab Content --> |
| 91 | `` | UI | <div id="content-password" class="mx-4 space-y-6"> |
| 92 | `Change` | UI | <!-- Method 1: Change Password (Ingat Password Lama) --> |
| 101 | `Password` | UI | <h2 class="text-lg font-bold text-gray-900 font-display">Ubah Password</h2> |
| 102 | `` | UI | <p class="text-sm text-gray-600">Jika Anda masih ingat password lama</p> |
| 106 | `` | UI | <form method="POST" action="{{ route('nasabah.setting.change-password') }}" class="space-y-4"> |
| 110 | `Password` | UI | <label class="block text-sm font-semibold text-gray-700 mb-2">Password Lama *</label> |
| 112 | `` | UI | <input type="password" name="password_lama" id="password_lama" required |
| 114 | `` | UI | <button type="button" onclick="togglePassword('password_lama')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700"> |
| 124 | `Password` | UI | <label class="block text-sm font-semibold text-gray-700 mb-2">Password Baru *</label> |
| 126 | `` | UI | <input type="password" name="password_baru" id="password_baru" required minlength="8" |
| 128 | `` | UI | <button type="button" onclick="togglePassword('password_baru')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700"> |
| 139 | `Password` | UI | <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password Baru *</label> |
| 141 | `` | UI | <input type="password" name="password_baru_confirmation" id="password_baru_confirmation" required minlength="8" |
| 143 | `` | UI | <button type="button" onclick="togglePassword('password_baru_confirmation')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700"> |
| 152 | `` | UI | <button type="submit" class="w-full py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-bold shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.01]"> |
| 153 | `Password` | UI | Ubah Password |
| 158 | `Reset` | UI | <!-- Method 2: Reset Password dengan OTP (Lupa Password) --> |
| 167 | `Reset` | UI | <h2 class="text-lg font-bold text-gray-900 font-display">Reset Password (Lupa Password)</h2> |
| 168 | `` | UI | <p class="text-sm text-gray-600">Gunakan OTP WhatsApp untuk reset password</p> |
| 189 | `` | UI | <button type="button" onclick="sendOtpPasswordReset()" id="btn-send-otp-pwd" class="w-full py-3 bg-linear-to-r from-red-600 to-red-700 text-white rounded-xl font-bold shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.01]"> |
| 198 | `` | UI | <form id="form-reset-password" class="hidden space-y-4 mt-4"> |
| 201 | `` | UI | <input type="text" id="otp_code_pwd" maxlength="6" placeholder="••••••" required |
| 208 | `Password` | UI | <label class="block text-sm font-semibold text-gray-700 mb-2">Password Baru *</label> |
| 210 | `` | UI | <input type="password" id="new_password_otp" required minlength="8" |
| 212 | `` | UI | <button type="button" onclick="togglePassword('new_password_otp')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700"> |
| 223 | `Password` | UI | <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password Baru *</label> |
| 225 | `` | UI | <input type="password" id="new_password_otp_confirmation" required minlength="8" |
| 227 | `` | UI | <button type="button" onclick="togglePassword('new_password_otp_confirmation')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700"> |
| 237 | `` | UI | <button type="button" onclick="cancelResetPassword()" class="flex-1 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-all"> |
| 240 | `` | UI | <button type="button" onclick="submitResetPassword()" class="flex-1 py-3 bg-linear-to-r from-red-600 to-red-700 text-white rounded-xl font-bold shadow-lg hover:shadow-xl transition-all"> |
| 241 | `Reset` | UI | Reset Password |
| 250 | `Change` | UI | <!-- Change PIN (Ingat PIN Lama) --> |
| 264 | `` | UI | <form method="POST" action="{{ route('nasabah.setting.change-pin') }}" class="space-y-4"> |
| 269 | `` | UI | <input type="password" name="pin_lama" id="pin_lama" maxlength="6" required |
| 277 | `` | UI | <input type="password" name="pin_baru" id="pin_baru" maxlength="6" required |
| 285 | `` | UI | <input type="password" name="pin_baru_confirmation" id="pin_baru_confirmation" maxlength="6" required |
| 290 | `` | UI | <button type="submit" class="w-full py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-bold shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.01]"> |
| 308 | `` | UI | <a href="https://wa.me/628139552626?text=Halo%20Admin%20Koperasi%2C%0A%0ASaya%20lupa%20PIN%20transaksi%20saya.%0A%0ANama%3A%20{{ urlencode($user->nama) }}%0AEmail%3A%20{{ urlencode($user->email) }}%0ANo%20HP%3A%20{{ urlencode($user->nomor_hp) }}%0A%0AMohon%20bantuannya%20untuk%20reset%20PIN.%0ATerima%20kasih." |
| 327 | `` | UI | const tabs = ['password', 'pin']; |
| 334 | `` | UI | content.classList.remove('hidden'); |
| 337 | `` | UI | content.classList.add('hidden'); |
| 345 | `` | UI | field.type = field.type === 'password' ? 'text' : 'password'; |
| 362 | `` | UI | const response = await fetch('{{ route("nasabah.setting.send-otp-password-reset") }}', { |
| 365 | `Type` | UI | 'Content-Type': 'application/json', |
| 372 | `` | UI | if (data.success) { |
| 374 | `` | UI | document.getElementById('step-send-otp-pwd').classList.add('hidden'); |
| 375 | `` | UI | document.getElementById('form-reset-password').classList.remove('hidden'); |
| 380 | `` | UI | showAlert('success', data.message); |
| 382 | `` | UI | showAlert('error', data.message); |
| 386 | `` | UI | } catch (error) { |
| 387 | `` | UI | showAlert('error', 'Terjadi kesalahan. Silakan coba lagi.'); |
| 399 | `` | UI | showAlert('error', 'Kode OTP harus 6 digit'); |
| 404 | `` | UI | showAlert('error', 'Password baru minimal 8 karakter'); |
| 409 | `` | UI | showAlert('error', 'Konfirmasi password tidak cocok'); |
| 414 | `` | UI | const response = await fetch('{{ route("nasabah.setting.verify-otp-reset-password") }}', { |
| 417 | `Type` | UI | 'Content-Type': 'application/json', |
| 429 | `` | UI | if (data.success) { |
| 430 | `` | UI | showAlert('success', data.message); |
| 433 | `` | UI | document.getElementById('form-reset-password').classList.add('hidden'); |
| 434 | `` | UI | document.getElementById('step-send-otp-pwd').classList.remove('hidden'); |
| 435 | `` | UI | document.getElementById('form-reset-password').reset(); |
| 439 | `` | UI | showAlert('error', data.message); |
| 441 | `` | UI | } catch (error) { |
| 442 | `` | UI | showAlert('error', 'Terjadi kesalahan. Silakan coba lagi.'); |
| 447 | `` | UI | document.getElementById('form-reset-password').classList.add('hidden'); |
| 448 | `` | UI | document.getElementById('step-send-otp-pwd').classList.remove('hidden'); |
| 449 | `` | UI | document.getElementById('form-reset-password').reset(); |
| 455 | `` | UI | element.classList.remove('hidden'); |
| 463 | `` | UI | element.classList.add('hidden'); |
| 470 | `` | UI | function showAlert(type, message) { |
| 472 | `` | UI | alertContainer.className = `mx-4 mb-6 ${type === 'success' ? 'bg-green-50 border-green-500' : 'bg-red-50 border-red-500'} border-l-4 rounded-r-xl p-4 shadow-md`; |
| 475 | `` | UI | <svg class="w-6 h-6 ${type === 'success' ? 'text-green-600' : 'text-red-600'} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"> |
| 476 | `` | UI | ${type === 'success' ? |
| 482 | `` | UI | <p class="${type === 'success' ? 'text-green-700' : 'text-red-700'} font-semibold">${message}</p> |
| 493 | `` | UI | alertContainer.remove(); |

## ðŸ„ `nasabah\tabungan\detail-janji-temu.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Detail` | UI | @section('title', 'Detail Janji Temu') |
| 18 | `Detail` | UI | <h1 class="text-2xl font-bold text-white font-display mb-1">Detail Janji Temu</h1> |
| 22 | `` | UI | <a href="{{ route('nasabah.tabungan.status-janji-temu') }}" class="px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-xl hover:bg-white/30 transition-colors"> |
| 71 | `Status` | UI | <!-- Status Janji Temu (setoran tunai diproses terpisah dari pengajuan transfer) --> |
| 73 | `Status` | UI | <h2 class="text-lg font-bold text-[#674c1d] font-display mb-4">Status</h2> |
| 84 | `` | UI | if ($janjiTemu->status == '2') { |
| 87 | `` | UI | } elseif ($janjiTemu->status == '3') { |
| 118 | `` | UI | <iframe src="https://www.google.com/maps/embed?pb=!4v1771057242792!6m8!1m7!1sTDnmeXtVvimBtQeXmqSSCQ!2m2!1d-6.267415399913648!2d106.9806162945405!3f247.41483905689947!4f-35.52001210835799!5f0.7820865974627469" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Peta Lokasi Janji Temu"></iframe> |

## ðŸ„ `nasabah\tabungan\detail-pengajuan-setor.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Detail` | UI | @section('title', 'Detail Pengajuan Setoran') |
| 18 | `Detail` | UI | <h1 class="text-2xl font-bold text-white font-display mb-1">Detail Pengajuan Setoran</h1> |
| 22 | `` | UI | <a href="{{ route('nasabah.tabungan.status-pengajuan-setor') }}" class="px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-xl hover:bg-white/30 transition-colors"> |
| 30 | `Status` | UI | <!-- Status Card --> |
| 34 | `Status` | UI | <p class="text-sm text-gray-600 mb-1">Status Pengajuan</p> |
| 41 | `` | PHP | $status = $statusConfig[$pengajuan->status] ?? $statusConfig['1']; |
| 43 | `` | UI | <span class="inline-block mt-2 px-4 py-2 {{ $status['bg'] }} {{ $status['text'] }} rounded-full text-sm font-semibold"> |
| 44 | `` | UI | {{ $status['label'] }} |
| 60 | `Total` | UI | <p class="text-sm text-gray-600 mb-2">Total Nominal</p> |
| 76 | `Transfer` | UI | <h2 class="text-lg font-bold text-[#674c1d] font-display mb-4">Bukti Transfer</h2> |
| 80 | `Transfer` | UI | onclick="showPhotoPreview('{{ asset('storage/' . $bukti->file_path) }}', 'Bukti Transfer #{{ $loop->iteration }}')"> |
| 81 | `Transfer` | UI | <img src="{{ asset('storage/' . $bukti->file_path) }}" alt="Bukti Transfer" class="w-full h-48 object-cover"> |
| 96 | `Note` | UI | <!-- Note: Janji temu removed - sekarang independent, hanya untuk setoran tunai --> |

## ðŸ„ `nasabah\tabungan\detail-pengajuan-tarik.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Detail` | UI | @section('title', 'Detail Pengajuan Penarikan') |
| 18 | `Detail` | UI | <h1 class="text-2xl font-bold text-white font-display mb-1">Detail Pengajuan Penarikan</h1> |
| 22 | `` | UI | <a href="{{ route('nasabah.tabungan.status-pengajuan-tarik') }}" class="px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-xl hover:bg-white/30 transition-colors"> |
| 30 | `Status` | UI | <!-- Status Card --> |
| 34 | `Status` | UI | <p class="text-sm text-gray-600 mb-1">Status Pengajuan</p> |
| 41 | `` | PHP | $status = $statusConfig[$pengajuan->status] ?? $statusConfig['1']; |
| 43 | `` | UI | <span class="inline-block mt-2 px-4 py-2 {{ $status['bg'] }} {{ $status['text'] }} rounded-full text-sm font-semibold"> |
| 44 | `` | UI | {{ $status['label'] }} |
| 64 | `` | UI | @if($pengajuan->status == '2' && $pengajuan->metode_transfer === 'transfer' && isset($pengajuan->biaya_transfer) && (float)$pengajuan->biaya_transfer > 0) |
| 66 | `` | UI | <p class="text-sm text-gray-600 mb-1">Biaya transfer admin (ditanggung nasabah)</p> |
| 68 | `Total` | UI | <p class="text-xs text-gray-500 mt-1">Total yang didebet dari saldo: Rp {{ number_format($pengajuan->nominal + $pengajuan->biaya_transfer, 0, ',', '.') }}</p> |
| 77 | `` | UI | @if($pengajuan->metode_transfer === 'transfer') |
| 108 | `` | UI | @if($pengajuan->status == '2' && $pengajuan->foto_bukti_tf_admin) |
| 110 | `Transfer` | UI | <h3 class="text-md font-bold text-[#674c1d] mb-4">Bukti Transfer dari Admin</h3> |
| 112 | `Transfer` | UI | onclick="showPhotoPreview('{{ asset('storage/' . $pengajuan->foto_bukti_tf_admin) }}', 'Bukti Transfer Admin')"> |
| 113 | `Transfer` | UI | <img src="{{ asset('storage/' . $pengajuan->foto_bukti_tf_admin) }}" alt="Bukti Transfer Admin" class="w-full h-auto"> |
| 155 | `` | UI | document.getElementById('photoPreviewModal').classList.remove('hidden'); |
| 156 | `` | UI | document.getElementById('photoPreviewModal').classList.add('flex'); |
| 161 | `` | UI | document.getElementById('photoPreviewModal').classList.add('hidden'); |
| 162 | `` | UI | document.getElementById('photoPreviewModal').classList.remove('flex'); |

## ðŸ„ `nasabah\tabungan\detail-transaksi.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Detail` | UI | @section('title', 'Detail Transaksi') |
| 18 | `Detail` | UI | <h1 class="text-2xl font-bold text-white font-display mb-1">Detail Transaksi</h1> |
| 25 | `Download` | UI | Download PDF Struk |
| 70 | `Transfer` | UI | <p class="text-sm font-semibold text-[#674c1d] mb-3">Rincian penarikan (Transfer)</p> |
| 77 | `` | UI | <span class="text-gray-600">Biaya transfer admin (ditanggung nasabah)</span> |
| 81 | `Total` | UI | <span class="text-gray-700 font-medium">Total didebet dari saldo</span> |
| 100 | `Transfer` | UI | <h2 class="text-lg font-bold text-[#674c1d] font-display mb-4">Bukti Transfer</h2> |
| 104 | `Transfer` | UI | onclick="showPhotoPreview('{{ asset('storage/' . $bukti->file_path) }}', 'Bukti Transfer #{{ $loop->iteration }}')"> |
| 105 | `Transfer` | UI | <img src="{{ asset('storage/' . $bukti->file_path) }}" alt="Bukti Transfer" class="w-full h-48 object-cover"> |
| 137 | `` | UI | <p class="text-sm text-gray-600 mb-4">Bukti transaksi yang di-upload oleh admin</p> |
| 145 | `Upload` | UI | <p class="text-xs text-gray-500 mb-2">Upload: {{ $bukti->created_at->format('d M Y, H:i') }}</p> |
| 163 | `Upload` | UI | <p class="text-xs text-gray-500 mb-2">Upload: {{ $bukti->created_at->format('d M Y, H:i') }}</p> |
| 177 | `` | UI | <!-- Proof from withdrawal request --> |
| 185 | `Transfer` | UI | <h2 class="text-lg font-bold text-[#674c1d] font-display">Bukti Transfer Admin</h2> |
| 187 | `` | UI | <p class="text-sm text-gray-600 mb-4">Bukti transfer yang dikirim oleh admin koperasi</p> |
| 189 | `Transfer` | UI | onclick="showPhotoPreview('{{ asset('storage/' . $transaksi->pengajuanTarik->foto_bukti_tf_admin) }}', 'Bukti Transfer Admin')"> |
| 190 | `Transfer` | UI | <img src="{{ asset('storage/' . $transaksi->pengajuanTarik->foto_bukti_tf_admin) }}" alt="Bukti Transfer" class="w-full h-auto"> |
| 206 | `Status` | UI | <p class="text-xs text-gray-500 mt-1">Status: |
| 213 | `` | PHP | $status = $statusConfig[$transaksi->pengajuanSetor->status] ?? $statusConfig['1']; |
| 215 | `` | UI | <span class="{{ $status['color'] }} font-semibold">{{ $status['label'] }}</span> |
| 223 | `Status` | UI | <p class="text-xs text-gray-500 mt-1">Status: |
| 230 | `` | PHP | $status = $statusConfig[$transaksi->pengajuanTarik->status] ?? $statusConfig['1']; |
| 232 | `` | UI | <span class="{{ $status['color'] }} font-semibold">{{ $status['label'] }}</span> |
| 265 | `` | UI | document.getElementById('photoPreviewModal').classList.remove('hidden'); |
| 266 | `` | UI | document.getElementById('photoPreviewModal').classList.add('flex'); |
| 271 | `` | UI | document.getElementById('photoPreviewModal').classList.add('hidden'); |
| 272 | `` | UI | document.getElementById('photoPreviewModal').classList.remove('flex'); |

## ðŸ„ `nasabah\tabungan\form-setoran.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 16 | `Transfer` | UI | <!-- Metode Transfer --> |
| 17 | `` | UI | <button onclick="selectMethod('transfer')" id="btn-transfer" class="group p-6 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl border-2 border-gray-200 hover:border-[#8b6f2f] transition-all text-left"> |
| 25 | `Transfer` | UI | <h3 class="text-lg font-bold text-gray-900 mb-1">Transfer</h3> |
| 26 | `Transfer` | UI | <p class="text-sm text-gray-600">Transfer via bank/mobile banking</p> |
| 33 | `Upload` | UI | <span>Upload bukti transfer</span> |
| 62 | `Transfer` | UI | <!-- Form Transfer --> |
| 63 | `` | UI | <div id="form-transfer-section" class="mb-6 hidden"> |
| 65 | `Transfer` | UI | <h2 class="text-lg font-bold text-gray-900 font-display mb-6">Formulir Setoran Transfer</h2> |
| 67 | `` | UI | <form id="form-transfer" method="POST" action="{{ route('nasabah.tabungan.submit-setoran') }}" enctype="multipart/form-data" class="space-y-6"> |
| 69 | `` | UI | <input type="hidden" name="metode" value="transfer"> |
| 76 | `` | UI | <input type="text" name="nominal" id="nominal-transfer" placeholder="0" required |
| 83 | `Upload` | UI | <!-- Upload Bukti Transfer --> |
| 85 | `Upload` | UI | <label class="block text-sm font-semibold text-gray-700 mb-3">Upload Bukti Transfer *</label> |
| 87 | `` | UI | <button type="button" onclick="addBuktiField()" |
| 92 | `Transfer` | UI | Tambah Bukti Transfer |
| 94 | `Upload` | UI | <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG, JPEG (Max 5MB per file). Upload minimal 1 bukti transfer.</p> |
| 100 | `` | UI | <textarea name="keterangan" rows="3" placeholder="Tambahkan keterangan jika diperlukan..." |
| 104 | `Submit` | UI | <!-- Submit Button --> |
| 106 | `` | UI | <button type="button" onclick="showPinModalTransfer()" class="w-full py-4 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.02]"> |
| 119 | `` | UI | <form id="form-tunai" method="POST" action="{{ route('nasabah.tabungan.submit-janji-temu') }}" class="space-y-6"> |
| 127 | `` | UI | <input type="text" name="nominal" id="nominal-tunai" placeholder="0" required |
| 137 | `` | UI | <select name="lokasi_temu" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none"> |
| 142 | `` | UI | </select> |
| 159 | `` | UI | <input type="date" name="tanggal_janji_temu" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" |
| 167 | `` | UI | <input type="time" name="waktu_janji_temu" required |
| 175 | `` | UI | <textarea name="keterangan" rows="3" placeholder="Tambahkan keterangan jika diperlukan..." |
| 179 | `Submit` | UI | <!-- Submit Button --> |
| 181 | `` | UI | <button type="button" onclick="showPinModalTunai()" class="w-full py-4 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.02]"> |
| 205 | `` | UI | <a href="{{ route('nasabah.tabungan.detail-transaksi', $riwayat->id) }}" class="block p-4 bg-gray-50 rounded-xl border border-gray-200 hover:border-[#674c1d]/30 hover:shadow-md transition-all"> |

## ðŸ„ `nasabah\tabungan\index.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Dashboard` | UI | @section('title', 'Dashboard Tabungan') |
| 29 | `` | UI | .tab-btn.active { |
| 34 | `` | UI | .tab-btn.active::after { |
| 47 | `` | UI | .tab-pane.active { |
| 111 | `Status` | UI | <p class="text-white/60 text-xs mb-5">Status: <span class="text-white/90 font-semibold">{{ $tabunganInfo->status ?? 'Aktif' }}</span></p> |
| 116 | `` | UI | class="flex items-center gap-3 bg-white/15 backdrop-blur-sm hover:bg-white/25 border border-white/25 rounded-2xl px-4 py-3 transition-all active:scale-95"> |
| 128 | `` | UI | class="flex items-center gap-3 bg-white/15 backdrop-blur-sm hover:bg-white/25 border border-white/25 rounded-2xl px-4 py-3 transition-all active:scale-95"> |
| 151 | `` | UI | <button type="button" class="tab-btn active" onclick="switchTab('trans', this)" id="tab-btn-trans"> |
| 159 | `` | UI | <button type="button" class="tab-btn" onclick="switchTab('setor', this)" id="tab-btn-setor"> |
| 164 | `` | UI | @if($pengajuanSetors->total() > 0) |
| 165 | `` | UI | <span class="tab-badge">{{ $pengajuanSetors->total() }}</span> |
| 170 | `` | UI | <button type="button" class="tab-btn" onclick="switchTab('tarik', this)" id="tab-btn-tarik"> |
| 175 | `` | UI | @if($pengajuanTariks->total() > 0) |
| 176 | `` | UI | <span class="tab-badge">{{ $pengajuanTariks->total() }}</span> |
| 181 | `` | UI | <button type="button" class="tab-btn" onclick="switchTab('jt', this)" id="tab-btn-jt"> |
| 186 | `` | UI | @if($janjiTemuTabungans->total() > 0) |
| 187 | `` | UI | <span class="tab-badge">{{ $janjiTemuTabungans->total() }}</span> |
| 196 | `` | UI | <div id="pane-trans" class="tab-pane active" data-container="trans-container"> |
| 221 | `Status` | UI | <p class="text-sm font-bold text-[#674c1d] font-display">Status Pengajuan Setoran</p> |
| 222 | `` | UI | <p class="text-xs text-gray-400 mt-0.5">Riwayat transfer & setoran yang diajukan</p> |
| 224 | `` | UI | <a href="{{ route('nasabah.tabungan.status-pengajuan-setor') }}" |
| 243 | `Status` | UI | <p class="text-sm font-bold text-[#674c1d] font-display">Status Pengajuan Penarikan</p> |
| 246 | `` | UI | <a href="{{ route('nasabah.tabungan.status-pengajuan-tarik') }}" |
| 294 | `` | UI | document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active')); |
| 295 | `` | UI | document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active')); |
| 298 | `` | UI | document.getElementById('pane-' + tabKey)?.classList.add('active'); |
| 299 | `` | UI | btnEl.classList.add('active'); |
| 330 | `Error` | UI | .then(r => { if (!r.ok) throw new Error(); return r.text(); }) |

## ðŸ„ `nasabah\tabungan\janji-temu.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 27 | `` | UI | @if(session('error')) |
| 33 | `` | UI | <p class="text-red-700 text-sm">{{ session('error') }}</p> |
| 47 | `` | UI | @foreach($errors->all() as $error) |
| 48 | `` | UI | <li>{{ $error }}</li> |
| 56 | `` | UI | @if(session('success')) |
| 62 | `` | UI | <p class="text-green-700 text-sm">{{ session('success') }}</p> |
| 67 | `` | UI | <form method="POST" action="{{ route('nasabah.tabungan.submit-janji-temu') }}" class="space-y-6" id="form-janji-temu"> |
| 75 | `` | UI | <input type="text" name="nominal" id="nominal" value="{{ old('nominal', request('nominal')) }}" placeholder="0" required |
| 80 | `` | UI | @error('nominal') |
| 88 | `` | UI | <select name="lokasi_temu" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none"> |
| 93 | `` | UI | </select> |
| 110 | `` | UI | <input type="date" name="tanggal_janji_temu" value="{{ old('tanggal_janji_temu') }}" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" |
| 118 | `` | UI | <input type="time" name="waktu_janji_temu" value="{{ old('waktu_janji_temu') }}" required |
| 126 | `` | UI | <textarea name="keterangan" rows="3" placeholder="Tambahkan keterangan jika diperlukan..." |
| 130 | `Submit` | UI | <!-- Submit Button --> |
| 132 | `` | UI | <button type="button" onclick="showPinModal()" class="w-full py-4 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.02]"> |
| 166 | `` | UI | <div id="pin-error" class="hidden mb-4 p-3 bg-red-50 border border-red-200 rounded-lg"> |
| 172 | `` | UI | <input type="password" id="pin-input" maxlength="6" pattern="[0-9]*" inputmode="numeric" |
| 182 | `` | UI | <button onclick="verifyAndSubmit()" id="btn-verify-submit" class="flex-1 px-4 py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-semibold hover:from-[#4a3514] hover:to-[#674c1d] transition-all"> |
| 209 | `` | UI | const nominalInput = form.querySelector('input[name="nominal"]'); |
| 223 | `` | UI | document.getElementById('pin-modal').classList.remove('hidden'); |
| 224 | `` | UI | document.getElementById('pin-modal').classList.add('flex'); |
| 229 | `` | UI | document.getElementById('pin-modal').classList.add('hidden'); |
| 230 | `` | UI | document.getElementById('pin-modal').classList.remove('flex'); |
| 232 | `` | UI | document.getElementById('pin-error').classList.add('hidden'); |
| 250 | `` | UI | const nominalInput = form.querySelector('input[name="nominal"]'); |
| 252 | `` | UI | nominalInput.value = nominalRaw; // Set as raw number for server processing |
| 256 | `` | UI | pinInputHidden.type = 'hidden'; |
| 257 | `` | UI | pinInputHidden.name = 'pin'; |
| 262 | `` | UI | const submitBtn = document.getElementById('btn-verify-submit'); |
| 271 | `` | UI | form.submit(); |
| 275 | `` | UI | const errorDiv = document.getElementById('pin-error'); |
| 277 | `` | UI | errorDiv.classList.remove('hidden'); |

## ðŸ„ `nasabah\tabungan\modals-pin.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 1 | `Transfer` | UI | <!-- PIN Modal Transfer --> |
| 2 | `` | UI | <div id="pin-modal-transfer" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-all"> |
| 24 | `` | UI | <input type="password" id="pin-input-transfer" maxlength="6" placeholder="••••••" |
| 27 | `` | UI | <p id="pin-error-transfer" class="hidden text-sm text-red-600 mt-4 text-center font-medium bg-red-50 py-2 rounded-lg italic">PIN salah, silakan coba lagi</p> |
| 30 | `` | UI | <button onclick="submitFormTransfer()" class="w-full py-4 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-2xl font-bold text-lg shadow-xl shadow-[#674c1d]/20 hover:shadow-2xl hover:scale-[1.01] active:scale-95 transition-all"> |
| 59 | `` | UI | <input type="password" id="pin-input-tunai" maxlength="6" placeholder="••••••" |
| 62 | `` | UI | <p id="pin-error-tunai" class="hidden text-sm text-red-600 mt-4 text-center font-medium bg-red-50 py-2 rounded-lg italic">PIN salah, silakan coba lagi</p> |
| 65 | `` | UI | <button onclick="submitFormTunai()" class="w-full py-4 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-2xl font-bold text-lg shadow-xl shadow-[#674c1d]/20 hover:shadow-2xl hover:scale-[1.01] active:scale-95 transition-all"> |

## ðŸ„ `nasabah\tabungan\nabung-sekarang.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 7 | `Back` | UI | <!-- Back Button --> |
| 18 | `` | UI | @if(session('error')) |
| 29 | `` | UI | <p class="text-gray-700 text-sm">{{ session('error') }}</p> |
| 47 | `` | UI | @foreach($errors->all() as $error) |
| 48 | `` | UI | <li>{{ $error }}</li> |
| 75 | `` | UI | <button onclick="switchGuide('transfer')" id="tab-guide-transfer" class="px-6 py-2.5 rounded-xl font-bold text-sm transition-all bg-white shadow-sm text-[#674c1d]"> |
| 76 | `Transfer` | UI | Metode Transfer |
| 85 | `` | UI | <div id="guide-transfer-content" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-10 transition-all duration-300"> |
| 92 | `Transfer` | UI | <h3 class="font-bold text-gray-900 mb-2">1. Transfer Uang</h3> |
| 93 | `Transfer` | UI | <p class="text-sm text-gray-600">Transfer ke rekening resmi koperasi via Mobile Banking.</p> |
| 104 | `` | UI | <p class="text-sm text-gray-600">Screenshot atau foto bukti transfer Anda.</p> |
| 114 | `` | UI | <p class="text-sm text-gray-600">Input nominal & upload bukti, lalu masukkan PIN Anda.</p> |
| 119 | `` | UI | <div id="guide-tunai-content" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-10 hidden transition-all duration-300"> |
| 152 | `` | UI | <div id="guide-transfer-info" class="space-y-6 mb-10 transition-all duration-300"> |
| 154 | `` | UI | <div class="bg-gradient-to-br from-[#674c1d]/5 to-[#8b6f2f]/10 p-6 md:p-8 rounded-3xl border border-[#674c1d]/10 transition-all duration-300"> |
| 160 | `Transfer` | UI | Rekening Transfer {{ $bank->bank }} |
| 183 | `` | UI | <p class="text-xs text-gray-500 mt-1">Pastikan nama sesuai saat konfirmasi transfer</p> |
| 203 | `` | UI | <p class="text-amber-800 font-medium italic">Belum ada data rekening transfer yang tersedia saat ini.</p> |
| 209 | `` | UI | <div id="guide-tunai-info" class="hidden bg-gradient-to-br from-gray-50 to-gray-100 p-6 md:p-8 rounded-3xl border border-gray-200 mb-10 transition-all duration-300"> |
| 237 | `` | UI | class="group relative inline-flex items-center gap-3 px-10 py-4 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-2xl font-bold text-lg shadow-xl hover:shadow-2xl hover:scale-[1.02] transform transition-all active:scale-95"> |
| 261 | `` | UI | const transTab = document.getElementById('tab-guide-transfer'); |
| 263 | `` | UI | const transContent = document.getElementById('guide-transfer-content'); |
| 265 | `` | UI | const transInfo = document.getElementById('guide-transfer-info'); |
| 268 | `` | UI | if (method === 'transfer') { |
| 270 | `` | UI | transTab.classList.add('bg-white', 'shadow-sm', 'text-[#674c1d]'); |
| 271 | `` | UI | transTab.classList.remove('text-gray-500'); |
| 272 | `` | UI | tunaiTab.classList.remove('bg-white', 'shadow-sm', 'text-[#674c1d]'); |
| 273 | `` | UI | tunaiTab.classList.add('text-gray-500'); |
| 276 | `` | UI | transContent.classList.remove('hidden'); |
| 277 | `` | UI | tunaiContent.classList.add('hidden'); |
| 278 | `` | UI | transInfo.classList.remove('hidden'); |
| 279 | `` | UI | tunaiInfo.classList.add('hidden'); |
| 282 | `` | UI | tunaiTab.classList.add('bg-white', 'shadow-sm', 'text-[#674c1d]'); |
| 283 | `` | UI | tunaiTab.classList.remove('text-gray-500'); |
| 284 | `` | UI | transTab.classList.remove('bg-white', 'shadow-sm', 'text-[#674c1d]'); |
| 285 | `` | UI | transTab.classList.add('text-gray-500'); |
| 288 | `` | UI | tunaiContent.classList.remove('hidden'); |
| 289 | `` | UI | transContent.classList.add('hidden'); |
| 290 | `` | UI | tunaiInfo.classList.remove('hidden'); |
| 291 | `` | UI | transInfo.classList.add('hidden'); |
| 299 | `` | UI | guide.classList.add('opacity-0', '-translate-y-4'); |
| 301 | `` | UI | guide.classList.add('hidden'); |
| 302 | `` | UI | form.classList.remove('hidden'); |
| 303 | `` | UI | form.classList.add('opacity-0', 'translate-y-4'); |
| 305 | `` | UI | form.classList.remove('opacity-0', 'translate-y-4'); |
| 306 | `` | UI | form.classList.add('opacity-100', 'translate-y-0'); |
| 324 | `` | UI | btn.classList.remove('border-[#674c1d]', 'border-[#8b6f2f]', 'bg-gradient-to-br', 'from-[#674c1d]/10', 'to-[#8b6f2f]/10'); |
| 325 | `` | UI | btn.classList.add('border-gray-200'); |
| 328 | `` | UI | document.getElementById('form-transfer-section').classList.add('hidden'); |
| 329 | `` | UI | document.getElementById('form-tunai-section').classList.add('hidden'); |
| 331 | `` | UI | if (method === 'transfer') { |
| 332 | `` | UI | document.getElementById('btn-transfer').classList.add('border-[#8b6f2f]', 'bg-gradient-to-br', 'from-[#8b6f2f]/10', 'to-[#d4af37]/10'); |
| 333 | `` | UI | document.getElementById('form-transfer-section').classList.remove('hidden'); |
| 336 | `` | UI | document.getElementById('btn-tunai').classList.add('border-[#674c1d]', 'bg-gradient-to-br', 'from-[#674c1d]/10', 'to-[#8b6f2f]/10'); |
| 337 | `` | UI | document.getElementById('form-tunai-section').classList.remove('hidden'); |
| 354 | `` | UI | <input type="file" name="bukti_foto[]" accept="image/*" required |
| 358 | `` | UI | <button type="button" onclick="this.parentElement.parentElement.remove(); buktiCount--;" |
| 377 | `` | UI | const response = await fetch('{{ route("nasabah.tabungan.verify-pin") }}', { |
| 379 | `Type` | UI | headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, |
| 387 | `` | UI | const pinInput = document.getElementById('pin-input-transfer'); |
| 388 | `` | UI | const errorMsg = document.getElementById('pin-error-transfer'); |
| 389 | `` | UI | const submitBtn = document.querySelector('#pin-modal-transfer button[onclick="submitFormTransfer()"]'); |
| 400 | `` | UI | if (data.success) { |
| 401 | `` | UI | const nominalInput = document.getElementById('nominal-transfer'); |
| 404 | `` | UI | hiddenPin.type = 'hidden'; hiddenPin.name = 'pin'; hiddenPin.value = pin; |
| 405 | `` | UI | document.getElementById('form-transfer').appendChild(hiddenPin); |
| 406 | `` | UI | document.getElementById('form-transfer').submit(); |
| 409 | `` | UI | errorMsg.classList.remove('hidden'); |
| 421 | `` | UI | const errorMsg = document.getElementById('pin-error-tunai'); |
| 433 | `` | UI | if (data.success) { |
| 437 | `` | UI | hiddenPin.type = 'hidden'; hiddenPin.name = 'pin'; hiddenPin.value = pin; |
| 439 | `` | UI | document.getElementById('form-tunai').submit(); |
| 442 | `` | UI | errorMsg.classList.remove('hidden'); |
| 453 | `` | UI | const form = document.getElementById('form-transfer'); |
| 455 | `` | UI | const fileInputs = form.querySelectorAll('input[type="file"]'); |
| 459 | `` | UI | alert('Ukuran file ' + input.files[0].name + ' terlalu besar. Maksimal 5MB.'); |
| 464 | `` | UI | document.getElementById('pin-modal-transfer').classList.remove('hidden'); |
| 465 | `` | UI | document.getElementById('pin-modal-transfer').classList.add('flex'); |
| 466 | `` | UI | document.getElementById('pin-input-transfer').focus(); |
| 471 | `` | UI | function closePinModalTransfer() { document.getElementById('pin-modal-transfer').classList.add('hidden'); document.getElementById('pin-modal-transfer').classList.remove('flex'); } |
| 472 | `` | UI | function showPinModalTunai() { if (document.getElementById('form-tunai').checkValidity()) { document.getElementById('pin-modal-tunai').classList.remove('hidden'); document.getElementById('pin-modal-tunai').classList.add('flex'); document.getElementById('pin-input-tunai').focus(); } else document.getElementById('form-tunai').reportValidity(); } |
| 473 | `` | UI | function closePinModalTunai() { document.getElementById('pin-modal-tunai').classList.add('hidden'); document.getElementById('pin-modal-tunai').classList.remove('flex'); } |
| 477 | `` | UI | card.addEventListener('mouseenter', () => card.classList.add('-translate-y-2')); |
| 478 | `` | UI | card.addEventListener('mouseleave', () => card.classList.remove('-translate-y-2')); |

## ðŸ„ `nasabah\tabungan\partials\_table_jt.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 7 | `Status` | UI | <th class="px-5 py-3 text-left text-xs font-bold text-[#674c1d] uppercase tracking-wide">Status</th> |
| 29 | `` | UI | <a href="{{ route('nasabah.tabungan.detail-janji-temu', $jt->id) }}" |
| 31 | `Detail` | UI | Detail |

## ðŸ„ `nasabah\tabungan\partials\_table_setor.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 7 | `Status` | UI | <th class="px-5 py-3 text-left text-xs font-bold text-[#674c1d] uppercase tracking-wide">Status</th> |
| 19 | `` | PHP | $status = $statusConfig[$item->status] ?? $statusConfig['1']; |
| 30 | `` | UI | <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $status['bg'] }} {{ $status['text'] }}"> |
| 31 | `` | UI | <span class="w-1 h-1 rounded-full {{ $status['dot'] }}"></span> |
| 32 | `` | UI | {{ $status['label'] }} |
| 36 | `` | UI | <a href="{{ route('nasabah.tabungan.detail-pengajuan-setor', $item->id) }}" |
| 38 | `Detail` | UI | Detail |

## ðŸ„ `nasabah\tabungan\partials\_table_tarik.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 7 | `Status` | UI | <th class="px-5 py-3 text-left text-xs font-bold text-[#674c1d] uppercase tracking-wide">Status</th> |
| 19 | `` | PHP | $status = $statusConfig[$item->status] ?? $statusConfig['1']; |
| 30 | `` | UI | <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $status['bg'] }} {{ $status['text'] }}"> |
| 31 | `` | UI | <span class="w-1 h-1 rounded-full {{ $status['dot'] }}"></span> |
| 32 | `` | UI | {{ $status['label'] }} |
| 36 | `` | UI | <a href="{{ route('nasabah.tabungan.detail-pengajuan-tarik', $item->id) }}" |
| 38 | `Detail` | UI | Detail |

## ðŸ„ `nasabah\tabungan\partials\_table_trans.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 31 | `` | UI | <a href="{{ route('nasabah.tabungan.detail-transaksi', $trans->id) }}" |
| 33 | `Detail` | UI | Detail |

## ðŸ„ `nasabah\tabungan\penarikan-tabungan.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 54 | `Transfer` | UI | <!-- Metode Transfer --> |
| 55 | `` | UI | <button onclick="selectMethod('transfer')" id="btn-transfer" class="group p-6 bg-linear-to-br from-gray-50 to-gray-100 rounded-2xl border-2 border-gray-200 hover:border-[#674c1d] transition-all text-left"> |
| 63 | `Transfer` | UI | <h3 class="text-lg font-bold text-gray-900 mb-1">Transfer</h3> |
| 64 | `Transfer` | UI | <p class="text-sm text-gray-600">Transfer ke rekening Anda</p> |
| 91 | `` | UI | @foreach($errors->all() as $error) |
| 92 | `` | UI | <li>{{ $error }}</li> |
| 99 | `` | UI | <form id="form-penarikan" method="POST" action="{{ route('nasabah.tabungan.submit-penarikan') }}" class="space-y-6"> |
| 101 | `` | UI | <input type="hidden" name="metode" id="metode-input" value="{{ old('metode') }}"> |
| 108 | `` | UI | <input type="text" name="nominal" id="nominal" value="{{ old('nominal', request('nominal')) }}" placeholder="0" required |
| 112 | `` | UI | <div id="saldo-warning" class="hidden mt-2 p-3 bg-red-50 border border-red-200 rounded-lg"> |
| 120 | `` | UI | @if(session('error')) |
| 122 | `` | UI | <p class="text-sm text-red-600 font-medium">{{ session('error') }}</p> |
| 128 | `Details` | UI | <!-- Tunai Details (for Tunai) --> |
| 141 | `` | UI | <select name="lokasi_temu" id="lokasi_temu" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none"> |
| 146 | `` | UI | </select> |
| 152 | `` | UI | <input type="date" name="tanggal_janji_temu" id="tanggal_janji_temu" min="{{ date('Y-m-d') }}" value="{{ old('tanggal_janji_temu') }}" |
| 157 | `` | UI | <input type="time" name="waktu_janji_temu" id="waktu_janji_temu" value="{{ old('waktu_janji_temu') }}" |
| 164 | `Details` | UI | <!-- Bank Details (for Transfer) - auto-fill dari data rekening nasabah --> |
| 172 | `` | UI | <select name="nama_bank" id="nama_bank" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none"> |
| 177 | `` | UI | </select> |
| 185 | `` | UI | <input type="text" name="no_rekening" id="no_rekening" placeholder="Masukkan nomor rekening tujuan" value="{{ $defaultNoRek }}" |
| 200 | `` | UI | <textarea name="keterangan" rows="3" placeholder="Tambahkan keterangan jika diperlukan..." |
| 204 | `Submit` | UI | <!-- Submit Button --> |
| 206 | `` | UI | <button type="button" onclick="showPinModal()" id="submit-btn" class="w-full py-4 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed"> |
| 231 | `` | UI | <a href="{{ route('nasabah.tabungan.detail-transaksi', $riwayat->id) }}" class="block p-4 bg-gray-50 rounded-xl border border-gray-200 hover:border-red-300 hover:shadow-md transition-all"> |
| 288 | `` | UI | <div id="pin-error" class="hidden mb-4 p-3 bg-red-50 border border-red-200 rounded-lg"> |
| 294 | `` | UI | <input type="password" id="pin-input" maxlength="6" pattern="[0-9]*" inputmode="numeric" |
| 304 | `` | UI | <button onclick="verifyAndSubmit()" id="btn-verify-submit" class="flex-1 px-4 py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-semibold hover:from-[#4a3514] hover:to-[#674c1d] transition-all"> |
| 329 | `` | UI | document.getElementById('form-section').classList.remove('hidden'); |
| 333 | `` | UI | const btnTransfer = document.getElementById('btn-transfer'); |
| 336 | `` | UI | btnTunai.classList.remove('border-[#8b6f2f]', 'bg-linear-to-br', 'from-[#8b6f2f]/10', 'to-[#d4af37]/10'); |
| 337 | `` | UI | btnTransfer.classList.remove('border-[#674c1d]', 'bg-linear-to-br', 'from-[#674c1d]/10', 'to-[#4a3514]/10'); |
| 340 | `` | UI | btnTunai.classList.add('border-[#8b6f2f]', 'bg-linear-to-br', 'from-[#8b6f2f]/10', 'to-[#d4af37]/10'); |
| 343 | `` | UI | document.getElementById('tunai-section').classList.remove('hidden'); |
| 349 | `` | UI | document.getElementById('bank-section').classList.add('hidden'); |
| 353 | `` | UI | btnTransfer.classList.add('border-[#674c1d]', 'bg-linear-to-br', 'from-[#674c1d]/10', 'to-[#4a3514]/10'); |
| 356 | `` | UI | document.getElementById('tunai-section').classList.add('hidden'); |
| 362 | `` | UI | document.getElementById('bank-section').classList.remove('hidden'); |
| 384 | `` | UI | const warning = document.getElementById('saldo-warning'); |
| 385 | `` | UI | const submitBtn = document.getElementById('submit-btn'); |
| 388 | `` | UI | warning.classList.remove('hidden'); |
| 391 | `` | UI | warning.classList.add('hidden'); |
| 403 | `` | UI | console.warn('Form invalid according to checkValidity()'); |
| 437 | `` | UI | document.getElementById('pin-modal').classList.remove('hidden'); |
| 438 | `` | UI | document.getElementById('pin-modal').classList.add('flex'); |
| 444 | `` | UI | document.getElementById('pin-modal').classList.add('hidden'); |
| 445 | `` | UI | document.getElementById('pin-modal').classList.remove('flex'); |
| 447 | `` | UI | document.getElementById('pin-error').classList.add('hidden'); |
| 454 | `` | UI | console.log('=== VERIFY AND SUBMIT CALLED ==='); |
| 458 | `` | UI | return; // Don't show error yet if called from oninput |
| 469 | `` | UI | let pinInputHidden = form.querySelector('input[name="pin"]'); |
| 472 | `` | UI | pinInputHidden.type = 'hidden'; |
| 473 | `` | UI | pinInputHidden.name = 'pin'; |
| 481 | `` | UI | const verifyBtn = document.getElementById('btn-verify-submit'); |
| 490 | `` | UI | form.submit(); |
| 494 | `` | UI | const errorDiv = document.getElementById('pin-error'); |
| 496 | `` | UI | errorDiv.classList.remove('hidden'); |

## ðŸ„ `nasabah\tabungan\pengajuan-transfer.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Transfer` | UI | @section('title', 'Pengajuan Setoran Transfer') |
| 17 | `Transfer` | UI | <h1 class="text-2xl font-bold text-white font-display mb-1">Pengajuan Setoran Transfer</h1> |
| 18 | `Upload` | UI | <p class="text-white/90 text-sm">Upload bukti transfer untuk pengajuan setoran</p> |
| 27 | `` | UI | @if(session('error')) |
| 29 | `` | UI | <p class="text-red-700 text-sm">{{ session('error') }}</p> |
| 42 | `` | UI | @foreach($errors->all() as $error) |
| 43 | `` | UI | <li>{{ $error }}</li> |
| 51 | `` | UI | <form id="form-transfer" method="POST" action="{{ route('nasabah.tabungan.submit-setoran') }}" enctype="multipart/form-data" class="space-y-6"> |
| 53 | `` | UI | <input type="hidden" name="metode" value="transfer"> |
| 60 | `` | UI | <input type="text" name="nominal" id="nominal" placeholder="0" required |
| 68 | `Upload` | UI | <!-- Upload Bukti Transfer --> |
| 70 | `Upload` | UI | <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Bukti Transfer *</label> |
| 76 | `` | UI | <p class="text-sm text-gray-600 font-semibold">Klik untuk tambah bukti transfer</p> |
| 80 | `` | UI | <p class="text-xs text-gray-500 mt-2">Minimal upload 1 bukti transfer. Anda bisa upload beberapa bukti jika melakukan transfer bertahap.</p> |
| 86 | `` | UI | <textarea name="keterangan" rows="3" placeholder="Tambahkan keterangan jika diperlukan..." |
| 90 | `Submit` | UI | <!-- Submit Button --> |
| 92 | `` | UI | <button type="button" onclick="showPinModal()" class="w-full py-4 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.02]"> |
| 126 | `` | UI | <div id="pin-error" class="hidden mb-4 p-3 bg-red-50 border border-red-200 rounded-lg"> |
| 132 | `` | UI | <input type="password" id="pin-input" maxlength="6" pattern="[0-9]*" inputmode="numeric" |
| 160 | `Transfer` | UI | <label class="text-sm font-semibold text-gray-700">Bukti Transfer ${buktiCount}</label> |
| 161 | `` | UI | <button type="button" onclick="this.closest('.border-2').remove(); buktiCount--;" |
| 168 | `` | UI | <input type="file" name="bukti_foto[]" accept="image/jpeg,image/png,image/jpg" required |
| 185 | `` | UI | preview.classList.remove('hidden'); |
| 207 | `` | UI | alert('Minimal upload 1 bukti transfer'); |
| 212 | `` | UI | document.getElementById('pin-modal').classList.remove('hidden'); |
| 213 | `` | UI | document.getElementById('pin-modal').classList.add('flex'); |
| 218 | `` | UI | document.getElementById('pin-modal').classList.add('hidden'); |
| 219 | `` | UI | document.getElementById('pin-modal').classList.remove('flex'); |
| 221 | `` | UI | document.getElementById('pin-error').classList.add('hidden'); |
| 233 | `` | UI | const form = document.getElementById('form-transfer'); |
| 235 | `` | UI | pinInput.type = 'hidden'; |
| 236 | `` | UI | pinInput.name = 'pin'; |
| 248 | `` | UI | form.submit(); |
| 252 | `` | UI | const errorDiv = document.getElementById('pin-error'); |
| 254 | `` | UI | errorDiv.classList.remove('hidden'); |

## ðŸ„ `nasabah\tabungan\status-janji-temu.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Status` | UI | @section('title', 'Status Janji Temu') |
| 18 | `Status` | UI | <h1 class="text-2xl font-bold text-white font-display mb-1">Status Janji Temu</h1> |
| 33 | `` | UI | @if(session('success')) |
| 45 | `` | UI | <p class="text-gray-700 text-sm leading-relaxed">{{ session('success') }}</p> |
| 54 | `` | UI | @if(session('error')) |
| 65 | `` | UI | <p class="text-gray-700 text-sm">{{ session('error') }}</p> |
| 84 | `` | UI | <a href="{{ route('nasabah.tabungan.status-janji-temu', ['status' => $val]) }}" |
| 110 | `` | UI | if ($item->status == '2') { |
| 115 | `` | UI | } elseif ($item->status == '3') { |
| 132 | `` | UI | <div class="group bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 border-l-4 {{ $borderClass }}"> |
| 191 | `` | UI | <a href="{{ route('nasabah.tabungan.detail-janji-temu', $item->id) }}" |
| 192 | `` | UI | class="w-full sm:flex-1 py-3.5 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-2xl font-bold text-sm hover:translate-y-[-2px] hover:shadow-lg transition-all duration-300 flex items-center justify-center gap-2"> |
| 193 | `Detail` | UI | <span>Lihat Detail Janji Temu</span> |
| 198 | `` | UI | @if(!$isPast && $item->status == '1') |
| 199 | `` | UI | <button type="button" |
| 200 | `` | UI | onclick="openCancelModal('{{ route('nasabah.tabungan.cancel-janji-temu', $item->id) }}', 'Janji Temu #{{ $item->id }}')" |
| 229 | `` | UI | <a href="{{ route('nasabah.tabungan.status-janji-temu') }}" |
| 234 | `Status` | UI | <span>Lihat Semua Status</span> |
| 250 | `` | UI | <div id="cancel-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4"> |
| 251 | `` | UI | <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden transform transition-all scale-95 opacity-0 duration-300" id="cancel-modal-content"> |
| 260 | `` | UI | <p class="text-gray-500 text-center text-sm mb-8" id="cancel-modal-description">Apakah Anda yakin ingin membatalkan item ini?</p> |
| 262 | `` | UI | <form id="cancel-form" method="POST" class="space-y-5"> |
| 266 | `` | UI | <input type="text" id="confirm-text" placeholder="SETUJU" |
| 272 | `` | UI | <input type="password" name="pin" id="pin-input" maxlength="6" placeholder="••••••" |
| 277 | `` | UI | <button type="button" onclick="closeCancelModal()" class="flex-1 py-4 bg-gray-100 text-gray-600 rounded-2xl font-bold hover:bg-gray-200 transition-all"> |
| 280 | `` | UI | <button type="submit" id="submit-btn" disabled class="flex-1 py-4 bg-rose-500 text-white rounded-2xl font-bold shadow-lg shadow-rose-200 opacity-50 cursor-not-allowed transition-all hover:bg-rose-600"> |
| 291 | `` | UI | const modal = document.getElementById('cancel-modal'); |
| 292 | `` | UI | const modalContent = document.getElementById('cancel-modal-content'); |
| 293 | `` | UI | const confirmInput = document.getElementById('confirm-text'); |
| 295 | `` | UI | const submitBtn = document.getElementById('submit-btn'); |
| 296 | `` | UI | const cancelForm = document.getElementById('cancel-form'); |
| 298 | `` | UI | function openCancelModal(action, title) { |
| 299 | `` | UI | cancelForm.action = action; |
| 300 | `` | UI | document.getElementById('cancel-modal-description').textContent = `Anda akan membatalkan: ${title}. Tindakan ini tidak dapat dibatalkan.`; |
| 302 | `` | UI | modal.classList.remove('hidden'); |
| 303 | `` | UI | modal.classList.add('flex'); |
| 307 | `` | UI | modalContent.classList.remove('scale-95', 'opacity-0'); |
| 308 | `` | UI | modalContent.classList.add('scale-100', 'opacity-100'); |
| 319 | `` | UI | modalContent.classList.remove('scale-100', 'opacity-100'); |
| 320 | `` | UI | modalContent.classList.add('scale-95', 'opacity-0'); |
| 323 | `` | UI | modal.classList.add('hidden'); |
| 324 | `` | UI | modal.classList.remove('flex'); |
| 330 | `` | UI | const isPinValid = pinInput.value.length === 6; |
| 332 | `` | UI | if (isConfirmMatch && isPinValid) { |
| 334 | `` | UI | submitBtn.classList.remove('opacity-50', 'cursor-not-allowed'); |
| 337 | `` | UI | submitBtn.classList.add('opacity-50', 'cursor-not-allowed'); |
| 347 | `` | UI | cancelForm.addEventListener('submit', function(e) { |

## ðŸ„ `nasabah\tabungan\status-pengajuan-setor.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Status` | UI | @section('title', 'Status Pengajuan Setoran') |
| 18 | `Status` | UI | <h1 class="text-2xl font-bold text-white font-display mb-1">Status Pengajuan Setoran</h1> |
| 19 | `` | UI | <p class="text-white/90 text-sm">Lihat status pengajuan setoran Anda</p> |
| 33 | `` | UI | <!-- Notifikasi sukses / error setelah submit --> |
| 34 | `` | UI | @if(session('success')) |
| 46 | `` | UI | <p class="text-gray-700 text-sm leading-relaxed">{{ session('success') }}</p> |
| 49 | `` | UI | Anda bisa cek status pengajuan di bawah. Admin akan memproses segera. |
| 55 | `` | UI | @if(session('error')) |
| 66 | `` | UI | <p class="text-gray-700 text-sm">{{ session('error') }}</p> |
| 84 | `` | UI | <a href="{{ route('nasabah.tabungan.status-pengajuan-setor', ['status' => $val]) }}" |
| 103 | `` | UI | if ($item->status == '2') { |
| 108 | `` | UI | } elseif ($item->status == '3') { |
| 120 | `` | UI | <div class="group bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 border-l-4 {{ $borderClass }}"> |
| 133 | `Transfer` | UI | <span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded-md text-[10px] font-bold uppercase tracking-tighter">Via Transfer</span> |
| 152 | `Total` | UI | <p class="text-xs font-bold text-gray-400 uppercase">Total Nominal</p> |
| 165 | `` | UI | <div class="mb-6 p-4 {{ $item->status == '2' ? 'bg-emerald-50 border border-emerald-100' : 'bg-rose-50 border border-rose-100' }} rounded-2xl"> |
| 167 | `` | UI | <div class="p-1.5 {{ $item->status == '2' ? 'bg-emerald-100' : 'bg-rose-100' }} rounded-lg shrink-0"> |
| 168 | `` | UI | <svg class="w-4 h-4 {{ $item->status == '2' ? 'text-emerald-600' : 'text-rose-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"> |
| 173 | `` | UI | <p class="text-xs font-extrabold {{ $item->status == '2' ? 'text-emerald-700' : 'text-rose-700' }} uppercase tracking-wider mb-1">Catatan Admin</p> |
| 174 | `` | UI | <p class="text-sm {{ $item->status == '2' ? 'text-emerald-900' : 'text-rose-900' }} font-bold">{{ $item->keterangan_admin }}</p> |
| 181 | `` | UI | <a href="{{ route('nasabah.tabungan.detail-pengajuan-setor', $item->id) }}" |
| 182 | `` | UI | class="flex-1 py-3.5 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-2xl font-bold text-sm hover:translate-y-[-2px] hover:shadow-lg transition-all duration-300 flex items-center justify-center gap-2"> |
| 183 | `Detail` | UI | <span>Lihat Detail Pengajuan</span> |
| 188 | `` | UI | @if($item->status == '1') |
| 189 | `` | UI | <button type="button" |
| 190 | `` | UI | onclick="openCancelModal('{{ route('nasabah.tabungan.cancel-pengajuan-setor', $item->id) }}', 'Pengajuan Setoran #{{ $item->id }}')" |
| 219 | `` | UI | <a href="{{ route('nasabah.tabungan.status-pengajuan-setor') }}" |
| 224 | `Status` | UI | <span>Lihat Semua Status</span> |
| 240 | `` | UI | <div id="cancel-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4"> |
| 241 | `` | UI | <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden transform transition-all scale-95 opacity-0 duration-300" id="cancel-modal-content"> |
| 250 | `` | UI | <p class="text-gray-500 text-center text-sm mb-8" id="cancel-modal-description">Apakah Anda yakin ingin membatalkan item ini?</p> |
| 252 | `` | UI | <form id="cancel-form" method="POST" class="space-y-5"> |
| 256 | `` | UI | <input type="text" id="confirm-text" placeholder="SETUJU" |
| 262 | `` | UI | <input type="password" name="pin" id="pin-input" maxlength="6" placeholder="••••••" |
| 267 | `` | UI | <button type="button" onclick="closeCancelModal()" class="flex-1 py-4 bg-gray-100 text-gray-600 rounded-2xl font-bold hover:bg-gray-200 transition-all"> |
| 270 | `` | UI | <button type="submit" id="submit-btn" disabled class="flex-1 py-4 bg-rose-500 text-white rounded-2xl font-bold shadow-lg shadow-rose-200 opacity-50 cursor-not-allowed transition-all hover:bg-rose-600"> |
| 281 | `` | UI | const modal = document.getElementById('cancel-modal'); |
| 282 | `` | UI | const modalContent = document.getElementById('cancel-modal-content'); |
| 283 | `` | UI | const confirmInput = document.getElementById('confirm-text'); |
| 285 | `` | UI | const submitBtn = document.getElementById('submit-btn'); |
| 286 | `` | UI | const cancelForm = document.getElementById('cancel-form'); |
| 288 | `` | UI | function openCancelModal(action, title) { |
| 289 | `` | UI | cancelForm.action = action; |
| 290 | `` | UI | document.getElementById('cancel-modal-description').textContent = `Anda akan membatalkan: ${title}. Tindakan ini tidak dapat dibatalkan.`; |
| 292 | `` | UI | modal.classList.remove('hidden'); |
| 293 | `` | UI | modal.classList.add('flex'); |
| 297 | `` | UI | modalContent.classList.remove('scale-95', 'opacity-0'); |
| 298 | `` | UI | modalContent.classList.add('scale-100', 'opacity-100'); |
| 309 | `` | UI | modalContent.classList.remove('scale-100', 'opacity-100'); |
| 310 | `` | UI | modalContent.classList.add('scale-95', 'opacity-0'); |
| 313 | `` | UI | modal.classList.add('hidden'); |
| 314 | `` | UI | modal.classList.remove('flex'); |
| 320 | `` | UI | const isPinValid = pinInput.value.length === 6; |
| 322 | `` | UI | if (isConfirmMatch && isPinValid) { |
| 324 | `` | UI | submitBtn.classList.remove('opacity-50', 'cursor-not-allowed'); |
| 327 | `` | UI | submitBtn.classList.add('opacity-50', 'cursor-not-allowed'); |
| 337 | `` | UI | cancelForm.addEventListener('submit', function(e) { |

## ðŸ„ `nasabah\tabungan\status-pengajuan-tarik.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 3 | `Status` | UI | @section('title', 'Status Pengajuan Penarikan') |
| 18 | `Status` | UI | <h1 class="text-2xl font-bold text-white font-display mb-1">Status Penarikan</h1> |
| 19 | `` | UI | <p class="text-white/90 text-sm">Lihat status pengajuan penarikan Anda</p> |
| 33 | `` | UI | <!-- Notifikasi sukses / error setelah submit --> |
| 34 | `` | UI | @if(session('success')) |
| 46 | `` | UI | <p class="text-gray-700 text-sm leading-relaxed">{{ session('success') }}</p> |
| 49 | `` | UI | Anda bisa cek status pengajuan di bawah. Admin akan memproses segera. |
| 55 | `` | UI | @if(session('error')) |
| 66 | `` | UI | <p class="text-gray-700 text-sm">{{ session('error') }}</p> |
| 84 | `` | UI | <a href="{{ route('nasabah.tabungan.status-pengajuan-tarik', ['status' => $val]) }}" |
| 103 | `` | UI | if ($item->status == '2') { |
| 108 | `` | UI | } elseif ($item->status == '3') { |
| 120 | `` | UI | <div class="group bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 border-l-4 {{ $borderClass }}"> |
| 133 | `` | UI | @if($item->metode_transfer == 'transfer') |
| 134 | `Transfer` | UI | <span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded-md text-[10px] font-bold uppercase tracking-tighter">Via Transfer</span> |
| 156 | `Total` | UI | <p class="text-xs font-bold text-gray-400 uppercase">Total Nominal</p> |
| 160 | `` | UI | @if($item->metode_transfer == 'transfer') |
| 192 | `` | UI | <div class="mb-6 p-4 {{ $item->status == '2' ? 'bg-emerald-50 border border-emerald-100' : 'bg-rose-50 border border-rose-100' }} rounded-2xl"> |
| 194 | `` | UI | <div class="p-1.5 {{ $item->status == '2' ? 'bg-emerald-100' : 'bg-rose-100' }} rounded-lg shrink-0"> |
| 195 | `` | UI | <svg class="w-4 h-4 {{ $item->status == '2' ? 'text-emerald-600' : 'text-rose-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"> |
| 200 | `` | UI | <p class="text-xs font-extrabold {{ $item->status == '2' ? 'text-emerald-700' : 'text-rose-700' }} uppercase tracking-wider mb-1">Catatan Admin</p> |
| 201 | `` | UI | <p class="text-sm {{ $item->status == '2' ? 'text-emerald-900' : 'text-rose-900' }} font-bold">{{ $item->keterangan_admin }}</p> |
| 208 | `` | UI | <a href="{{ route('nasabah.tabungan.detail-pengajuan-tarik', $item->id) }}" |
| 209 | `` | UI | class="flex-1 py-3.5 bg-linear-to-r from-[#8b6f2f] to-[#d4af37] text-white rounded-2xl font-bold text-sm hover:translate-y-[-2px] hover:shadow-lg transition-all duration-300 flex items-center justify-center gap-2"> |
| 210 | `Detail` | UI | <span>Lihat Detail Pengajuan</span> |
| 215 | `` | UI | @if($item->status == '1') |
| 216 | `` | UI | <button type="button" |
| 217 | `` | UI | onclick="openCancelModal('{{ route('nasabah.tabungan.cancel-pengajuan-tarik', $item->id) }}', 'Pengajuan Penarikan #{{ $item->id }}')" |
| 246 | `` | UI | <a href="{{ route('nasabah.tabungan.status-pengajuan-tarik') }}" |
| 251 | `Status` | UI | <span>Lihat Semua Status</span> |
| 267 | `` | UI | <div id="cancel-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4"> |
| 268 | `` | UI | <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden transform transition-all scale-95 opacity-0 duration-300" id="cancel-modal-content"> |
| 277 | `` | UI | <p class="text-gray-500 text-center text-sm mb-8" id="cancel-modal-description">Apakah Anda yakin ingin membatalkan item ini?</p> |
| 279 | `` | UI | <form id="cancel-form" method="POST" class="space-y-5"> |
| 283 | `` | UI | <input type="text" id="confirm-text" placeholder="SETUJU" |
| 289 | `` | UI | <input type="password" name="pin" id="pin-input" maxlength="6" placeholder="••••••" |
| 294 | `` | UI | <button type="button" onclick="closeCancelModal()" class="flex-1 py-4 bg-gray-100 text-gray-600 rounded-2xl font-bold hover:bg-gray-200 transition-all"> |
| 297 | `` | UI | <button type="submit" id="submit-btn" disabled class="flex-1 py-4 bg-rose-500 text-white rounded-2xl font-bold shadow-lg shadow-rose-200 opacity-50 cursor-not-allowed transition-all hover:bg-rose-600"> |
| 308 | `` | UI | const modal = document.getElementById('cancel-modal'); |
| 309 | `` | UI | const modalContent = document.getElementById('cancel-modal-content'); |
| 310 | `` | UI | const confirmInput = document.getElementById('confirm-text'); |
| 312 | `` | UI | const submitBtn = document.getElementById('submit-btn'); |
| 313 | `` | UI | const cancelForm = document.getElementById('cancel-form'); |
| 315 | `` | UI | function openCancelModal(action, title) { |
| 316 | `` | UI | cancelForm.action = action; |
| 317 | `` | UI | document.getElementById('cancel-modal-description').textContent = `Anda akan membatalkan: ${title}. Tindakan ini tidak dapat dibatalkan.`; |
| 319 | `` | UI | modal.classList.remove('hidden'); |
| 320 | `` | UI | modal.classList.add('flex'); |
| 324 | `` | UI | modalContent.classList.remove('scale-95', 'opacity-0'); |
| 325 | `` | UI | modalContent.classList.add('scale-100', 'opacity-100'); |
| 336 | `` | UI | modalContent.classList.remove('scale-100', 'opacity-100'); |
| 337 | `` | UI | modalContent.classList.add('scale-95', 'opacity-0'); |
| 340 | `` | UI | modal.classList.add('hidden'); |
| 341 | `` | UI | modal.classList.remove('flex'); |
| 347 | `` | UI | const isPinValid = pinInput.value.length === 6; |
| 349 | `` | UI | if (isConfirmMatch && isPinValid) { |
| 351 | `` | UI | submitBtn.classList.remove('opacity-50', 'cursor-not-allowed'); |
| 354 | `` | UI | submitBtn.classList.add('opacity-50', 'cursor-not-allowed'); |
| 364 | `` | UI | cancelForm.addEventListener('submit', function(e) { |

## ðŸ„ `struk\angsuran.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 16 | `` | UI | <div class="center bold mb">{{ config('app.name', 'Koperasi Majakara') }}</div> |
| 28 | `Status` | UI | <div class="mt">Status : Lunas angsuran ke-{{ $angsuran->no_urut ?? '-' }}</div> |
| 31 | `` | UI | <div class="center">Dicetak dari {{ config('app.name', 'Koperasi Majakara') }}</div> |

## ðŸ„ `struk\pembayaran-pinjaman.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 16 | `` | UI | <div class="center bold mb">{{ config('app.name', 'Koperasi Majakara') }}</div> |
| 28 | `Status` | UI | <div class="mt">Status : Lunas angsuran{{ $angsuran ? ' ke-' . $angsuran->no_urut : '' }}</div> |
| 31 | `` | UI | <div class="center">Dicetak dari {{ config('app.name', 'Koperasi Majakara') }}</div> |

## ðŸ„ `struk\pencairan-pinjaman.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 16 | `` | UI | <div class="center bold mb">{{ config('app.name', 'Koperasi Majakara') }}</div> |
| 31 | `` | UI | <div class="center">Dicetak dari {{ config('app.name', 'Koperasi Majakara') }}</div> |

## ðŸ„ `struk\tabungan.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 48 | `Transfer` | UI | <div class="row"><span class="label">Biaya Transfer</span><span class="value">Rp {{ number_format($transaksi->pengajuanTarik->biaya_transfer ?? 0, 0, ',', '.') }}</span></div> |
| 49 | `Total` | UI | <div class="nominal">Total Didebet : Rp {{ number_format($transaksi->nominal ?? 0, 0, ',', '.') }}</div> |

## ðŸ„ `struk\tabungan-html.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 5 | `` | UI | <meta name="viewport" content="width=device-width, initial-scale=1.0"> |
| 24 | `` | UI | .receipt-container { |
| 57 | `` | UI | filter: grayscale(100%) contrast(1000%); /* Bantu hitam-putih untuk printer */ |
| 91 | `` | UI | .sign-name { |
| 98 | `` | UI | @media print { |
| 102 | `` | UI | .receipt-container { |
| 116 | `` | UI | <div class="receipt-container"> |
| 126 | `Details` | UI | <!-- Transaction Details --> |
| 154 | `Details` | UI | <!-- Nominal Details --> |
| 163 | `Transfer` | UI | <span class="label">Biaya Transfer</span> |
| 171 | `Total` | UI | <span class="label">Total {{ $transaksi->jenis === 'setoran' ? 'Setoran' : 'Didebet' }}</span> |
| 192 | `Note` | UI | <!-- Approval & Note --> |
| 208 | `` | UI | <p class="sign-name">{{ $petugasName }}</p> |
| 213 | `` | UI | <p class="sign-name">{{ $transaksi->nasabah->user->nama ?? 'Nasabah' }}</p> |
| 223 | `` | UI | window.print(); |
| 229 | `` | UI | window.close(); |

## ðŸ„ `vendor\pagination\bootstrap-4.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 6 | `` | UI | <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')"> |
| 11 | `` | UI | <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a> |
| 26 | `` | UI | <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li> |
| 37 | `` | UI | <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a> |
| 40 | `` | UI | <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')"> |

## ðŸ„ `vendor\pagination\bootstrap-5.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 8 | `` | UI | <span class="page-link">@lang('pagination.previous')</span> |
| 12 | `` | UI | <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">@lang('pagination.previous')</a> |
| 19 | `` | UI | <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">@lang('pagination.next')</a> |
| 23 | `` | UI | <span class="page-link">@lang('pagination.next')</span> |
| 37 | `` | UI | <span class="fw-semibold">{{ $paginator->total() }}</span> |
| 46 | `` | UI | <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')"> |
| 51 | `` | UI | <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a> |
| 66 | `` | UI | <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li> |
| 77 | `` | UI | <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a> |
| 80 | `` | UI | <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')"> |

## ðŸ„ `vendor\pagination\default.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 6 | `` | UI | <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.previous')"> |
| 11 | `` | UI | <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a> |
| 26 | `` | UI | <li class="active" aria-current="page"><span>{{ $page }}</span></li> |
| 37 | `` | UI | <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a> |
| 40 | `` | UI | <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.next')"> |

## ðŸ„ `vendor\pagination\semantic-ui.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 2 | `` | UI | <div class="ui pagination menu" role="navigation"> |
| 5 | `` | UI | <a class="icon item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')"> <i class="left chevron icon"></i> </a> |
| 7 | `` | UI | <a class="icon item" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')"> <i class="left chevron icon"></i> </a> |
| 21 | `` | UI | <a class="item active" href="{{ $url }}" aria-current="page">{{ $page }}</a> |
| 31 | `` | UI | <a class="icon item" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')"> <i class="right chevron icon"></i> </a> |
| 33 | `` | UI | <a class="icon item disabled" aria-disabled="true" aria-label="@lang('pagination.next')"> <i class="right chevron icon"></i> </a> |

## ðŸ„ `vendor\pagination\simple-bootstrap-4.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 7 | `` | UI | <span class="page-link">@lang('pagination.previous')</span> |
| 11 | `` | UI | <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">@lang('pagination.previous')</a> |
| 18 | `` | UI | <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">@lang('pagination.next')</a> |
| 22 | `` | UI | <span class="page-link">@lang('pagination.next')</span> |

## ðŸ„ `vendor\pagination\simple-bootstrap-5.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 2 | `Navigation` | UI | <nav role="navigation" aria-label="{!! __('Pagination Navigation') !!}"> |
| 7 | `` | UI | <span class="page-link">{!! __('pagination.previous') !!}</span> |
| 12 | `` | UI | {!! __('pagination.previous') !!} |
| 20 | `` | UI | <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">{!! __('pagination.next') !!}</a> |
| 24 | `` | UI | <span class="page-link">{!! __('pagination.next') !!}</span> |

## ðŸ„ `vendor\pagination\simple-default.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 6 | `` | UI | <li class="disabled" aria-disabled="true"><span>@lang('pagination.previous')</span></li> |
| 8 | `` | UI | <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">@lang('pagination.previous')</a></li> |
| 13 | `` | UI | <li><a href="{{ $paginator->nextPageUrl() }}" rel="next">@lang('pagination.next')</a></li> |
| 15 | `` | UI | <li class="disabled" aria-disabled="true"><span>@lang('pagination.next')</span></li> |

## ðŸ„ `vendor\pagination\simple-tailwind.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 2 | `Navigation` | UI | <nav role="navigation" aria-label="{!! __('Pagination Navigation') !!}" class="flex justify-between"> |
| 6 | `` | UI | {!! __('pagination.previous') !!} |
| 9 | `` | UI | <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:focus:border-blue-700 dark:active:bg-gray-700 dark:active:text-gray-300"> |
| 10 | `` | UI | {!! __('pagination.previous') !!} |
| 16 | `` | UI | <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:focus:border-blue-700 dark:active:bg-gray-700 dark:active:text-gray-300"> |
| 17 | `` | UI | {!! __('pagination.next') !!} |
| 21 | `` | UI | {!! __('pagination.next') !!} |

## ðŸ„ `vendor\pagination\tailwind.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 2 | `Navigation` | UI | <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between"> |
| 6 | `` | UI | {!! __('pagination.previous') !!} |
| 9 | `` | UI | <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-[#674c1d] bg-white border border-gray-200 leading-5 rounded-xl hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#674c1d]/20 transition ease-in-out duration-150"> |
| 10 | `` | UI | {!! __('pagination.previous') !!} |
| 15 | `` | UI | <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-[#674c1d] bg-white border border-gray-200 leading-5 rounded-xl hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#674c1d]/20 transition ease-in-out duration-150"> |
| 16 | `` | UI | {!! __('pagination.next') !!} |
| 20 | `` | UI | {!! __('pagination.next') !!} |
| 37 | `` | UI | <span class="font-bold text-[#674c1d]">{{ $paginator->total() }}</span> |
| 46 | `` | UI | <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}"> |
| 54 | `` | UI | <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 bg-white leading-5 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-2 focus:ring-[#674c1d]/20 transition ease-in-out duration-150" aria-label="{{ __('pagination.previous') }}"> |
| 78 | `` | UI | <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-600 bg-white border-l border-gray-100 leading-5 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-2 focus:ring-[#674c1d]/20 transition ease-in-out duration-150" aria-label="{{ __('Go to page :page', ['page' => $page]) }}"> |
| 88 | `` | UI | <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-3 py-2 -ml-px text-sm font-medium text-gray-600 bg-white border-l border-gray-100 leading-5 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-2 focus:ring-[#674c1d]/20 transition ease-in-out duration-150" aria-label="{{ __('pagination.next') }}"> |
| 94 | `` | UI | <span aria-disabled="true" aria-label="{{ __('pagination.next') }}"> |

## ðŸ„ `welcome.blade.php`

| Baris | Kata Terdeteksi | Konteks | Isi Baris |
|-------|-----------------|---------|-----------|
| 6 | `` | UI | <meta name="viewport" content="width=device-width, initial-scale=1"> |
| 19 | `` | UI | @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap'); |
| 92 | `` | UI | .faq-item.active .faq-content { |
| 98 | `` | UI | backdrop-filter: blur(10px); |
| 188 | `` | UI | style="mix-blend-mode: multiply; filter: brightness(1.1) contrast(1.2);"> |
| 192 | `Navigation` | UI | <!-- Desktop Navigation --> |
| 206 | `` | UI | @if (Route::has('login')) |
| 210 | `` | PHP | $dashboardUrl = $user->role === 'nasabah' ? route('nasabah.dashboard') : '/admin/dashboard'; |
| 220 | `Dashboard` | UI | <span>Dashboard</span> |
| 223 | `` | UI | <a href="{{ route('login') }}" |
| 225 | `Login` | UI | Login |
| 227 | `` | UI | @if (Route::has('register')) |
| 228 | `` | UI | <a href="{{ route('register') }}" |
| 230 | `Register` | UI | Register |
| 237 | `Login` | UI | Login |
| 241 | `Register` | UI | Register |
| 246 | `Menu` | UI | <!-- Mobile Menu Button --> |
| 247 | `` | UI | <button id="mobile-menu-btn" class="md:hidden p-2 text-gray-700"> |
| 256 | `Menu` | UI | <!-- Mobile Menu --> |
| 257 | `` | UI | <div id="mobile-menu" class="hidden md:hidden bg-white border-t"> |
| 266 | `` | UI | @if (Route::has('login')) |
| 270 | `` | PHP | $dashboardUrl = $user->role === 'nasabah' ? route('nasabah.dashboard') : '/admin/dashboard'; |
| 279 | `Dashboard` | UI | <span>Dashboard</span> |
| 282 | `` | UI | <a href="{{ route('login') }}" |
| 284 | `Login` | UI | Login |
| 286 | `` | UI | @if (Route::has('register')) |
| 287 | `` | UI | <a href="{{ route('register') }}" |
| 289 | `Register` | UI | Register |
| 296 | `Login` | UI | Login |
| 300 | `Register` | UI | Register |
| 316 | `` | UI | <div class="absolute top-20 right-10 w-72 h-72 bg-linear-to-br from-[#674c1d]/20 to-[#8b6f2f]/20 rounded-full filter blur-3xl animate-pulse"></div> |
| 317 | `` | UI | <div class="absolute bottom-20 left-10 w-96 h-96 bg-linear-to-tr from-[#d4af37]/20 to-[#8b6f2f]/20 rounded-full filter blur-3xl animate-pulse" style="animation-delay: 1s;"></div> |
| 318 | `` | UI | <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-linear-to-r from-[#8b6f2f]/10 to-[#d4af37]/10 rounded-full filter blur-3xl animate-pulse" style="animation-delay: 2s;"></div> |
| 374 | `` | PHP | $dashboardUrl = $user->role === 'nasabah' ? route('nasabah.dashboard') : '/admin/dashboard'; |
| 380 | `Dashboard` | UI | <span>Masuk Dashboard</span> |
| 383 | `` | UI | <a href="{{ route('register') }}" class="group px-8 py-4 bg-linear-to-r from-[#674c1d] via-[#8b6f2f] to-[#d4af37] text-white rounded-xl font-bold shadow-2xl hover:shadow-3xl transition-all hover:scale-105 flex items-center justify-center space-x-2"> |
| 390 | `` | UI | <span>Jelajahi Layanan</span> |
| 404 | `` | UI | <div class="bg-white rounded-3xl p-6 md:p-8 shadow-2xl border border-gray-100 mb-4 md:mb-6 hover:shadow-3xl transition-all duration-500"> |
| 511 | `` | UI | Kami menyediakan berbagai layanan keuangan yang lengkap untuk memenuhi kebutuhan Anda |
| 712 | `` | UI | style="mix-blend-mode: multiply; filter: brightness(1.1) contrast(1.2);"> |
| 757 | `` | UI | style="mix-blend-mode: multiply; filter: brightness(1.1) contrast(1.2);"> |
| 894 | `` | UI | hubungi customer service kami untuk informasi detail mengenai suku bunga terkini. |
| 957 | `Login` | UI | Setelah menjadi anggota, Anda akan mendapatkan akses ke aplikasi mobile dan website. Login |
| 958 | `` | UI | menggunakan nomor anggota dan password yang diberikan. Semua transaksi dapat dilakukan |
| 971 | `Description` | UI | <!-- Logo & Description --> |
| 976 | `` | UI | style="mix-blend-mode: multiply; filter: brightness(1.1) contrast(1.2);"> |
| 1000 | `Update` | UI | <li><a href="#" class="hover:text-white transition">Berita & Update</a></li> |
| 1010 | `Customer` | UI | <li><a href="#" class="hover:text-white transition">Customer Service</a></li> |
| 1011 | `Email` | UI | <li><a href="#" class="hover:text-white transition">Email Support</a></li> |
| 1020 | `All rights reserved` | UI | Copyright © 2025 Koperasi Majakara. All rights reserved. |
| 1065 | `` | UI | document.getElementById('mobile-menu-btn').addEventListener('click', function() { |
| 1066 | `` | UI | const menu = document.getElementById('mobile-menu'); |
| 1067 | `` | UI | menu.classList.toggle('hidden'); |
| 1074 | `` | UI | const isActive = faqItem.classList.contains('active'); |
| 1078 | `` | UI | item.classList.remove('active'); |
| 1079 | `` | UI | item.querySelector('svg').classList.remove('rotate-180'); |
| 1084 | `` | UI | faqItem.classList.add('active'); |
| 1085 | `` | UI | icon.classList.add('rotate-180'); |
| 1100 | `` | UI | document.getElementById('mobile-menu').classList.add('hidden'); |
| 1114 | `` | UI | entry.target.classList.add('visible'); |

---

## Ringkasan per Folder

- **admin/**: 126 file
- **auth/**: 2 file
- **components/**: 11 file
- **landing/**: 4 file
- **layouts/**: 2 file
- **nasabah/**: 52 file
- **struk/**: 5 file
- **vendor/**: 9 file
- **welcome.blade.php/**: 1 file
