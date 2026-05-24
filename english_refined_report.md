# Laporan Refined: Teks Bahasa Inggris yang Wajib Diterjemahkan

> **Tanggal Scan**: 23 Mei 2026 11.51
> **Total File Blade**: 213
> **File yang Perlu Diterjemahkan**: 171
> **Total Teks yang Perlu Diterjemahkan**: 974

---

## ðŸ„ `welcome.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 19 | `` | **Impor** | @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap'); |
| 92 | `` | **AKTIF** | .faq-item.active .faq-content { |
| 1020 | `All rights reserved` | **Hak cipta dilindungi** | Copyright © 2025 Koperasi Majakara. All rights reserved. |
| 1074 | `` | **AKTIF** | const isActive = faqItem.classList.contains('active'); |
| 1078 | `` | **AKTIF** | item.classList.remove('active'); |
| 1083 | `` | **AKTIF** | if (!isActive) { |
| 1084 | `` | **AKTIF** | faqItem.classList.add('active'); |

## ðŸ„ `admin\dashboard.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 213 | `` | **Setujui** | <form action="{{ $pengajuan['route_approve'] ?? '#' }}" method="POST" class="inline"> |
| 215 | `Approve` | **Setujui** | <button type="button" onclick="confirmApprove(this)" class="p-2 bg-green-50 text-green-600 hover:bg-green-500 hover:text-white rounded-lg transition-colors border border-green-100 hover:border-green-600 shadow-sm" title="Setujui"> |
| 219 | `` | **Tolak** | <form action="{{ $pengajuan['route_reject'] ?? '#' }}" method="POST" class="inline"> |
| 221 | `Reject` | **Tolak** | <button type="button" onclick="confirmReject(this)" class="p-2 bg-red-50 text-red-600 hover:bg-red-500 hover:text-white rounded-lg transition-colors border border-red-100 hover:border-red-600 shadow-sm" title="Tolak"> |
| 382 | `Approve` | **Setujui** | function confirmApprove(btn) { |
| 394 | `` | **Kirim** | btn.closest('form').submit(); |
| 399 | `Reject` | **Tolak** | function confirmReject(btn) { |
| 411 | `` | **Kirim** | btn.closest('form').submit(); |

## ðŸ„ `admin\activity-log\admin-operasional.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 114 | `` | **Kirim** | <button type="submit" |

## ðŸ„ `admin\activity-log\nasabah.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 103 | `` | **Kirim** | <button type="submit" |

## ðŸ„ `admin\deposito\deposito-detail.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 164 | `Internal note` | **Catatan internal** | <input type="text" name="catatan" value="{{ $persiapan->catatan }}" placeholder="Internal note..." class="w-full border-gray-200 rounded-lg text-xs py-2"> |
| 166 | `` | **Kirim** | <button type="submit" class="bg-[#674c1d] text-white py-2 rounded-lg text-xs font-bold hover:bg-[#4a3514] transition"> |
| 183 | `` | **Kirim** | <button type="submit" onclick="return confirm('Kirim Rp {{ number_format($persiapan->total_dibayar, 0, ',', '.') }} ke Admin sekarang?')" |

## ðŸ„ `admin\deposito\deposito-list.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 28 | `` | **Kirim** | <button type="submit" class="bg-[#674c1d] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-[#8b6f2f]">Filter</button> |
| 31 | `` | **Ekspor** | <a href="{{ route('admin.deposito.export-pdf', request()->all()) }}" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-700 flex items-center gap-2"> |

## ðŸ„ `admin\deposito\detail-pengajuan.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 73 | `` | **Unggah** | ⚠ Nasabah belum mengupload bukti transfer. |
| 87 | `Approve` | **Setujui** | {{-- Approve --}} |
| 88 | `` | **Setujui** | <form action="{{ route('admin.deposito.approve', $pengajuan->id) }}" method="POST"> |
| 104 | `` | **Kirim** | <button type="submit" onclick="return confirm('Yakin ingin menyetujui pengajuan ini?')" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-xl text-sm transition"> |
| 109 | `Reject` | **Tolak** | {{-- Reject --}} |
| 110 | `` | **Tolak** | <form action="{{ route('admin.deposito.reject', $pengajuan->id) }}" method="POST"> |
| 117 | `` | **Kirim** | <button type="submit" onclick="return confirm('Yakin ingin menolak pengajuan ini?')" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-3 rounded-xl text-sm transition"> |

## ðŸ„ `admin\deposito\index.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 73 | `` | **Kirim** | <form action="{{ route('admin.deposito.approve', $p->id) }}" method="POST" class="inline" onsubmit="return confirm('Setujui pengajuan ini?')"> |
| 75 | `` | **Kirim** | <button type="submit" class="p-1 rounded-md text-green-600 hover:bg-green-100" title="Setujui"> |
| 79 | `` | **Kirim** | <form action="{{ route('admin.deposito.reject', $p->id) }}" method="POST" class="inline" onsubmit="return confirm('Tolak pengajuan ini?')"> |
| 81 | `via Dashboard` | **via Dasbor** | <input type="hidden" name="catatan_admin" value="Ditolak via Dashboard"> |
| 82 | `` | **Kirim** | <button type="submit" class="p-1 rounded-md text-red-600 hover:bg-red-100" title="Tolak"> |

## ðŸ„ `admin\deposito\pencairan-tabungan.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 32 | `Total Request` | **Total Permintaan** | <p class="text-xs text-gray-500 font-semibold mt-1">Total Request</p> |
| 49 | `` | **Kirim** | <button type="submit" class="bg-[#674c1d] text-white px-5 py-2 rounded-xl text-sm font-semibold hover:bg-[#8b6f2f] transition">Filter</button> |
| 142 | `Upload` | **Unggah** | <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Upload Bukti</label> |
| 148 | `` | **Kirim** | <button type="submit" class="flex-1 bg-green-600 text-white py-3 rounded-xl text-sm font-bold hover:bg-green-700 transition shadow-lg"> |

## ðŸ„ `admin\deposito\pencairan-tf-form.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 74 | `` | **Kirim** | <button type="submit" class="w-full bg-[#674c1d] text-white py-4 rounded-xl font-bold hover:bg-[#8b6f2f] transition shadow-lg"> |
| 92 | `Upload` | **Unggah** | <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Upload Bukti Foto</label> |
| 98 | `Upload` | **Unggah** | <p class="text-[10px] text-gray-500 font-bold uppercase tracking-tighter">Klik untuk Upload Bukti</p> |
| 103 | `` | **Kirim** | <button type="submit" class="w-full bg-[#674c1d] text-white py-4 rounded-xl font-bold hover:bg-[#8b6f2f] transition shadow-lg"> |

## ðŸ„ `admin\deposito\pencairan-tf.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 32 | `Total Request` | **Total Permintaan** | <p class="text-xs text-gray-500 font-semibold mt-1">Total Request</p> |
| 50 | `` | **Kirim** | <button type="submit" class="bg-[#674c1d] text-white px-5 py-2 rounded-xl text-sm font-semibold hover:bg-[#8b6f2f] transition">Filter</button> |

## ðŸ„ `admin\deposito\pengajuan-list.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 33 | `` | **Kirim** | <button type="submit" class="bg-[#674c1d] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-[#8b6f2f]">Filter</button> |

## ðŸ„ `admin\deposito\peringatan-index.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 122 | `` | **Kirim** | <button type="submit" class="flex-1 bg-[#674c1d] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-[#8b6f2f] transition">Filter</button> |

## ðŸ„ `admin\deposito\paket\create.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 157 | `` | **Kirim** | <button type="submit" class="px-5 py-2.5 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white font-medium rounded-xl hover:shadow-lg hover:shadow-[#674c1d]/20 transition-all duration-300"> |

## ðŸ„ `admin\deposito\paket\edit.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 158 | `` | **Kirim** | <button type="submit" class="px-5 py-2.5 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white font-medium rounded-xl hover:shadow-lg hover:shadow-[#674c1d]/20 transition-all duration-300"> |

## ðŸ„ `admin\deposito\paket\index.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 84 | `` | **Kirim** | <form action="{{ route('admin.deposito.paket.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan paket ini?');" class="inline-block"> |
| 87 | `` | **Kirim** | <button type="submit" class="text-red-500 hover:text-red-700 transition-colors" title="Nonaktifkan"> |

## ðŸ„ `admin\gadai\index.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 26 | `Kembali ke Dashboard` | **Kembali ke Dasbor** | Kembali ke Dashboard |

## ðŸ„ `admin\gadai_baru\create.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 41 | `Submit` | **Kirim** | onsubmit="document.getElementById('btnSubmit').disabled=true; document.getElementById('btnSubmit').innerHTML='Memproses...';"> |
| 173 | `Upload` | **Unggah** | {{-- Bagian 3: Upload Bukti --}} |
| 188 | `Upload` | **Unggah** | <label class="block text-sm font-bold text-gray-700">Upload Foto Bukti <span |
| 200 | `` | **Unggah** | <div id="file_upload_container" class="space-y-3"> |
| 235 | `Submit` | **Kirim** | <h4 class="font-bold text-gray-900">Perhatian Sebelum Submit</h4> |
| 245 | `Submit` | **Kirim** | {{-- Submit Button --}} |
| 247 | `Submit` | **Kirim** | <button type="submit" id="btnSubmit" |
| 267 | `Submit` | **Kirim** | const btnSubmit = document.getElementById('btnSubmit'); |
| 334 | `Submit` | **Kirim** | btnSubmit.disabled = true; |
| 335 | `Submit` | **Kirim** | btnSubmit.classList.add('opacity-50', 'cursor-not-allowed'); |
| 339 | `Submit` | **Kirim** | btnSubmit.disabled = false; |
| 340 | `Submit` | **Kirim** | btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed'); |
| 346 | `` | **Unggah** | const container = document.getElementById('file_upload_container'); |

## ðŸ„ `admin\gadai_baru\detail.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 25 | `` | **AKTIF** | @if($gadai->status == 'active') |
| 26 | `ACTIVE` | **AKTIF** | <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-black rounded-lg">ACTIVE</span> |
| 28 | `GRACE PERIOD` | **MASA TENGGANG** | <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-black rounded-lg animate-pulse">GRACE PERIOD</span> |
| 109 | `` | **AKTIF** | @if($gadai->status == 'active') |
| 115 | `` | **Dilelang** | @elseif($gadai->status == 'auctioned') |

## ðŸ„ `admin\gadai_baru\index.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 35 | `` | **AKTIF** | $statAktif      = $gadiList->where('status', 'active')->count(); |
| 110 | `` | **AKTIF** | <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option> |
| 117 | `` | **Kirim** | <button type="submit" class="flex-1 flex justify-center items-center gap-2 px-4 py-2.5 bg-gray-800 hover:bg-gray-900 text-white font-medium rounded-xl transition-colors shadow-sm text-sm"> |
| 220 | `` | **Dilelang** | @elseif(in_array($gadai->status, ['expired_final', 'auctioned'])) |
| 239 | `` | **AKTIF** | @if($gadai->status == 'active') |
| 251 | `` | **Dilelang** | @elseif($gadai->status == 'auctioned') |

## ðŸ„ `admin\gadai_baru\pengajuan_index.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 42 | `` | **AKTIF** | <div class="text-[11px] text-gray-500">{{ $item->gadaiActive->item->nama_item }} • <span class="font-mono bg-gray-100 px-1 rounded">{{ $item->gadaiActive->slot_kode }}/{{ $item->gadaiActive->slot_table }}</span></div> |
| 88 | `` | **AKTIF** | class="w-9 h-9 flex items-center justify-center bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-sm hover:shadow-blue-100 active:scale-90" title="Lihat Detail"> |
| 91 | `Approve` | **Setujui** | <button type="button" onclick="openApproveModal({{ $item->id }}, '{{ $item->nasabah->user->nama }}', '{{ strtoupper($item->jenis_pengajuan) }}')" |
| 92 | `` | **AKTIF** | class="w-9 h-9 flex items-center justify-center bg-green-50 text-green-600 rounded-xl hover:bg-green-600 hover:text-white transition-all shadow-sm hover:shadow-green-100 active:scale-90" title="Setujui"> |
| 95 | `Reject` | **Tolak** | <button onclick="openRejectModal({{ $item->id }}, '{{ $item->nasabah->user->nama }}')" |
| 96 | `` | **AKTIF** | class="w-9 h-9 flex items-center justify-center bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm hover:shadow-red-100 active:scale-90" title="Tolak"> |
| 191 | `` | **AKTIF** | class="flex-1 px-6 py-3.5 bg-gray-100 text-gray-500 font-bold rounded-2xl hover:bg-gray-200 transition-all active:scale-95 text-center text-sm"> |
| 195 | `` | **Tolak** | <button type="button" id="detail-reject-btn" |
| 196 | `` | **AKTIF** | class="flex-1 px-6 py-3.5 bg-red-50 text-red-600 font-bold rounded-2xl hover:bg-red-600 hover:text-white transition-all active:scale-95 text-center text-sm"> |
| 199 | `` | **Setujui** | <button type="button" id="detail-approve-btn" |
| 200 | `` | **AKTIF** | class="flex-1 px-6 py-3.5 bg-green-600 text-white font-bold rounded-2xl hover:bg-green-700 transition-all shadow-xl shadow-green-200 active:scale-95 text-center text-sm"> |
| 209 | `Approve` | **Setujui** | <!-- Modal Approve (Tailwind) --> |
| 210 | `` | **Setujui** | <div id="approve-modal" class="fixed inset-0 bg-black/60 backdrop-blur-md z-[110] hidden items-center justify-center p-4"> |
| 219 | `` | **Setujui** | <p class="text-xs text-gray-500">Nasabah: <span id="approve-nasabah-name" class="font-bold text-green-600 bg-green-50 px-1.5 rounded"></span> \| <span id="approve-jenis" class="font-bold"></span></p> |
| 223 | `` | **Kirim** | <form id="formApprove" action="" method="POST" enctype="multipart/form-data" class="space-y-5" onsubmit="this.querySelector('button[type=submit]').disabled=true; this.querySelector('button[type=submit]').innerHTML='Mengolah...';"> |
| 226 | `Upload` | **Unggah** | <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Upload Bukti Administrasi (Opsional)</label> |
| 250 | `Approve` | **Setujui** | <button type="button" onclick="closeApproveModal()" |
| 251 | `` | **AKTIF** | class="flex-1 px-6 py-4 bg-gray-100 text-gray-500 font-bold rounded-2xl hover:bg-gray-200 transition-all active:scale-95"> |
| 254 | `` | **Kirim** | <button type="submit" |
| 255 | `` | **AKTIF** | class="flex-2 px-6 py-4 bg-green-600 text-white font-bold rounded-2xl hover:bg-green-700 transition-all shadow-xl shadow-green-200 active:scale-95"> |
| 263 | `` | **Tolak** | <div id="reject-modal" class="fixed inset-0 bg-black/60 backdrop-blur-md z-[110] hidden items-center justify-center p-4"> |
| 272 | `` | **Tolak** | <p class="text-xs text-gray-500">Nasabah: <span id="reject-nasabah-name" class="font-bold text-red-600 bg-red-50 px-1.5 rounded"></span></p> |
| 276 | `` | **Kirim** | <form id="formReject" action="" method="POST" class="space-y-5" onsubmit="this.querySelector('button[type=submit]').disabled=true; this.querySelector('button[type=submit]').innerHTML='Mengolah...';"> |
| 286 | `Reject` | **Tolak** | <button type="button" onclick="closeRejectModal()" |
| 287 | `` | **AKTIF** | class="flex-1 px-6 py-4 bg-gray-100 text-gray-500 font-bold rounded-2xl hover:bg-gray-200 transition-all active:scale-95"> |
| 290 | `` | **Kirim** | <button type="submit" |
| 291 | `` | **AKTIF** | class="flex-2 px-6 py-4 bg-red-600 text-white font-bold rounded-2xl hover:bg-red-700 transition-all shadow-xl shadow-red-200 active:scale-95"> |
| 307 | `` | **AKTIF** | barangName: "{{ addslashes($item->gadaiActive->item->nama_item) }}", |
| 308 | `` | **AKTIF** | slotCode: "{{ $item->gadaiActive->slot_code }} / {{ $item->gadaiActive->slot_table }}", |
| 367 | `` | **Setujui** | document.getElementById('detail-approve-btn').onclick = function() { |
| 369 | `Approve` | **Setujui** | openApproveModal(data.id, data.nasabahName, data.jenis); |
| 371 | `` | **Tolak** | document.getElementById('detail-reject-btn').onclick = function() { |
| 373 | `Reject` | **Tolak** | openRejectModal(data.id, data.nasabahName); |
| 429 | `Approve` | **Setujui** | function openApproveModal(id, name, jenis) { |
| 430 | `` | **Setujui** | const modal = document.getElementById('approve-modal'); |
| 431 | `Approve` | **Setujui** | const form = document.getElementById('formApprove'); |
| 432 | `` | **Setujui** | const nameDisplay = document.getElementById('approve-nasabah-name'); |
| 433 | `` | **Setujui** | const jenisDisplay = document.getElementById('approve-jenis'); |
| 435 | `` | **Setujui** | form.action = "{{ route('admin.gadai_baru.pengajuan.approve', ':id') }}".replace(':id', id); |
| 444 | `Approve` | **Setujui** | function closeApproveModal() { |
| 445 | `` | **Setujui** | const modal = document.getElementById('approve-modal'); |
| 451 | `Reject` | **Tolak** | function openRejectModal(id, name) { |
| 452 | `` | **Tolak** | const modal = document.getElementById('reject-modal'); |
| 453 | `Reject` | **Tolak** | const form = document.getElementById('formReject'); |
| 454 | `` | **Tolak** | const nameDisplay = document.getElementById('reject-nasabah-name'); |
| 456 | `` | **Tolak** | form.action = "{{ route('admin.gadai_baru.pengajuan.reject', ':id') }}".replace(':id', id); |
| 464 | `Reject` | **Tolak** | function closeRejectModal() { |
| 465 | `` | **Tolak** | const modal = document.getElementById('reject-modal'); |
| 472 | `` | **Tolak** | document.getElementById('reject-modal').addEventListener('click', function(e) { |
| 473 | `Reject` | **Tolak** | if (e.target === this) closeRejectModal(); |
| 478 | `Reject` | **Tolak** | if (e.key === 'Escape') closeRejectModal(); |

## ðŸ„ `admin\gadai_baru\storage.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 21 | `` | **Kirim** | <select name="kategori" class="border-gray-200 rounded-xl focus:ring-[#674c1d] focus:border-[#674c1d] text-sm font-medium bg-white shadow-sm" onchange="this.form.submit()"> |
| 143 | `` | **AKTIF** | <button onclick="openEmptyAuctionModal({{ $slot->active_gadai_id }}, '{{ $slot->kode_slot }}', '{{ addslashes($slot->nasabah_nama) }}', '{{ addslashes($slot->item_nama) }}')" |
| 144 | `` | **AKTIF** | class="w-full py-2 bg-amber-600 hover:bg-amber-700 active:scale-95 text-white text-[10px] font-black rounded-lg transition-all shadow-sm uppercase tracking-wider"> |
| 210 | `Auctioned` | **Dilelang** | <p class="text-xs text-amber-700 font-medium leading-relaxed">Barang pada slot ini berstatus <strong class="underline">hangus</strong>. Konfirmasi ini akan mengosongkan slot dan mengubah status barang menjadi <strong>Sudah Dilelang (Auctioned)</strong>.</p> |
| 230 | `Upload` | **Unggah** | {{-- Upload Foto --}} |
| 232 | `Upload` | **Unggah** | <label class="block text-xs font-black text-gray-700 uppercase tracking-wider mb-2">Upload Foto Bukti Pengambilan <span class="text-red-500">*</span></label> |
| 258 | `` | **Kirim** | <button type="submit" |
| 259 | `` | **AKTIF** | class="px-6 py-2.5 bg-amber-600 hover:bg-amber-700 active:scale-95 text-white text-sm font-black rounded-xl transition-all shadow-md shadow-amber-600/20 uppercase tracking-wide" |
| 260 | `` | **Kirim** | onsubmit="this.disabled=true;this.textContent='Memproses...'"> |

## ðŸ„ `admin\janji-temu\detail-pinjaman.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 186 | `` | **Unggah** | <p class="text-xs text-gray-500 mt-1">Bisa upload foto uang / kwitansi / bukti penerimaan |
| 195 | `` | **Kirim** | <button type="submit" |
| 251 | `` | **Kirim** | <button type="submit" |

## ðŸ„ `admin\janji-temu\detail.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 87 | `` | **Memuat** | <iframe src="https://www.google.com/maps/embed?pb=!4v1771057242792!6m8!1m7!1sTDnmeXtVvimBtQeXmqSSCQ!2m2!1d-6.267415399913648!2d106.9806162945405!3f247.41483905689947!4f-35.52001210835799!5f0.7820865974627469" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Peta Lokasi Janji Temu"></iframe> |
| 98 | `Actions` | **Aksi** | <!-- Sidebar Actions (sticky) --> |
| 107 | `Terakhir Update` | **Terakhir Diperbarui** | <p class="text-sm text-gray-600">Terakhir Update</p> |
| 147 | `` | **Kirim** | <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition-colors"> |
| 188 | `` | **Unggah** | <div class="foto-upload-item"> |
| 215 | `` | **Kirim** | <button type="submit" class="w-full px-4 py-3 bg-linear-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all font-medium shadow-md"> |
| 255 | `` | **Unggah** | const currentCount = container.querySelectorAll('.foto-upload-item').length; |
| 263 | `` | **Unggah** | newItem.className = 'foto-upload-item flex gap-2 items-center'; |
| 286 | `` | **Unggah** | const item = button.closest('.foto-upload-item'); |
| 299 | `` | **Kirim** | document.querySelector('form').addEventListener('submit', function(e) { |

## ðŸ„ `admin\janji-temu\index.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 57 | `` | **Kirim** | <button type="submit" class="px-6 py-2.5 bg-[#674c1d] hover:bg-[#543d16] text-white font-medium rounded-xl transition-all shadow-lg shadow-[#674c1d]/20 flex items-center justify-center gap-2"> |

## ðŸ„ `admin\laporan\angsuran-pinjaman.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 22 | `` | **Kirim** | <button type="submit" class="px-4 py-2 bg-[#674c1d] text-white rounded-lg font-medium">Tampilkan</button> |
| 29 | `Export` | **Ekspor** | <a href="{{ route('admin.laporan.angsuran-pinjaman', ['tgl_dari' => $tgl_dari, 'tgl_sampai' => $tgl_sampai, 'export' => 'pdf']) }}" class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-sm font-medium">Export PDF</a> |
| 30 | `Export` | **Ekspor** | <a href="{{ route('admin.laporan.angsuran-pinjaman', ['tgl_dari' => $tgl_dari, 'tgl_sampai' => $tgl_sampai, 'export' => 'excel']) }}" class="px-3 py-1.5 bg-green-100 text-green-700 rounded-lg text-sm font-medium">Export Excel</a> |

## ðŸ„ `admin\laporan\index.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 9 | `` | **Ekspor** | <p class="text-gray-600 mt-1">Pilih jenis laporan untuk melihat data dan export PDF/Excel</p> |

## ðŸ„ `admin\laporan\jatuh-tempo.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 18 | `` | **Kirim** | <button type="submit" class="px-4 py-2 bg-[#674c1d] text-white rounded-lg hover:bg-[#4a3514] font-medium">Tampilkan</button> |
| 25 | `Export` | **Ekspor** | <a href="{{ route('admin.laporan.jatuh-tempo', ['bulan' => $bulan, 'export' => 'pdf']) }}" class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-sm font-medium">Export PDF</a> |
| 26 | `Export` | **Ekspor** | <a href="{{ route('admin.laporan.jatuh-tempo', ['bulan' => $bulan, 'export' => 'excel']) }}" class="px-3 py-1.5 bg-green-100 text-green-700 rounded-lg text-sm font-medium">Export Excel</a> |

## ðŸ„ `admin\laporan\pengajuan.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 34 | `` | **Kirim** | <button type="submit" class="px-4 py-2 bg-[#674c1d] text-white rounded-lg hover:bg-[#4a3514] font-medium">Tampilkan</button> |
| 42 | `Export` | **Ekspor** | <a href="{{ route('admin.laporan.pengajuan', ['tgl_dari' => $tgl_dari, 'tgl_sampai' => $tgl_sampai, 'status' => $status, 'export' => 'pdf']) }}" class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-sm font-medium hover:bg-red-200">Export PDF</a> |
| 43 | `Export` | **Ekspor** | <a href="{{ route('admin.laporan.pengajuan', ['tgl_dari' => $tgl_dari, 'tgl_sampai' => $tgl_sampai, 'status' => $status, 'export' => 'excel']) }}" class="px-3 py-1.5 bg-green-100 text-green-700 rounded-lg text-sm font-medium hover:bg-green-200">Export Excel</a> |

## ðŸ„ `admin\laporan\pinjaman-aktif.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 19 | `Export` | **Ekspor** | <a href="{{ route('admin.laporan.pinjaman-aktif', ['export' => 'pdf']) }}" class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-sm font-medium hover:bg-red-200">Export PDF</a> |
| 20 | `Export` | **Ekspor** | <a href="{{ route('admin.laporan.pinjaman-aktif', ['export' => 'excel']) }}" class="px-3 py-1.5 bg-green-100 text-green-700 rounded-lg text-sm font-medium hover:bg-green-200">Export Excel</a> |

## ðŸ„ `admin\laporan\rekapitulasi.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 32 | `` | **Kirim** | <button type="submit" class="px-4 py-2 bg-[#674c1d] text-white rounded-lg hover:bg-[#4a3514] font-medium">Tampilkan</button> |
| 40 | `Export` | **Ekspor** | <a href="{{ route('admin.laporan.rekapitulasi', array_merge(request()->query(), ['export' => 'pdf'])) }}" class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-sm font-medium hover:bg-red-200">Export PDF</a> |
| 41 | `Export` | **Ekspor** | <a href="{{ route('admin.laporan.rekapitulasi', array_merge(request()->query(), ['export' => 'excel'])) }}" class="px-3 py-1.5 bg-green-100 text-green-700 rounded-lg text-sm font-medium hover:bg-green-200">Export Excel</a> |

## ðŸ„ `admin\laporan\saldo-tabungan.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 21 | `` | **Kirim** | <button type="submit" class="px-4 py-2 bg-[#674c1d] text-white rounded-lg hover:bg-[#4a3514] font-medium">Tampilkan</button> |
| 29 | `Export` | **Ekspor** | <a href="{{ route('admin.laporan.saldo-tabungan', array_merge(request()->query(), ['export' => 'pdf'])) }}" class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-sm font-medium hover:bg-red-200">Export PDF</a> |
| 30 | `Export` | **Ekspor** | <a href="{{ route('admin.laporan.saldo-tabungan', array_merge(request()->query(), ['export' => 'excel'])) }}" class="px-3 py-1.5 bg-green-100 text-green-700 rounded-lg text-sm font-medium hover:bg-green-200">Export Excel</a> |

## ðŸ„ `admin\laporan\tabungan.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 22 | `` | **Kirim** | <button type="submit" class="px-4 py-2 bg-[#674c1d] text-white rounded-lg hover:bg-[#4a3514] font-medium">Tampilkan</button> |
| 29 | `Export` | **Ekspor** | <a href="{{ route('admin.laporan.tabungan', ['tgl_dari' => $tgl_dari, 'tgl_sampai' => $tgl_sampai, 'export' => 'pdf']) }}" class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-sm font-medium">Export PDF</a> |
| 30 | `Export` | **Ekspor** | <a href="{{ route('admin.laporan.tabungan', ['tgl_dari' => $tgl_dari, 'tgl_sampai' => $tgl_sampai, 'export' => 'excel']) }}" class="px-3 py-1.5 bg-green-100 text-green-700 rounded-lg text-sm font-medium">Export Excel</a> |

## ðŸ„ `admin\master-data\admin-operasional\create.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 163 | `` | **Setujui** | <li>Tidak dapat mengelola akun nasabah (approve/reset PIN)</li> |
| 169 | `Actions` | **Aksi** | <!-- Form Actions --> |
| 175 | `` | **Kirim** | <button type="submit" |

## ðŸ„ `admin\master-data\admin-operasional\edit.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 176 | `Actions` | **Aksi** | <!-- Form Actions --> |
| 181 | `` | **Kirim** | onsubmit="return confirm('Yakin ingin menghapus akun ini? Aksi ini tidak dapat dibatalkan.')"> |
| 184 | `` | **Kirim** | <button type="submit" |
| 201 | `` | **Kirim** | <button type="submit" |

## ðŸ„ `admin\master-data\admin-operasional\index.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 110 | `` | **Kirim** | <button type="submit" |
| 178 | `` | **Kirim** | <button type="submit" |
| 200 | `` | **Kirim** | onsubmit="return confirm('Yakin ingin menghapus akun Admin Operasional ini? Aksi ini tidak dapat dibatalkan.')"> |
| 203 | `` | **Kirim** | <button type="submit" |

## ðŸ„ `admin\master-data\barang-gadai\create.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 43 | `` | **Kirim** | <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#8b6f2f] to-[#d4af37] text-white rounded-xl hover:from-[#674c1d] hover:to-[#8b6f2f] transition-all font-medium shadow-md"> |

## ðŸ„ `admin\master-data\barang-gadai\edit.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 36 | `` | **Kirim** | <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#8b6f2f] to-[#d4af37] text-white rounded-xl hover:from-[#674c1d] hover:to-[#8b6f2f] transition-all font-medium shadow-md"> |

## ðŸ„ `admin\master-data\barang-gadai\index.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 63 | `` | **Kirim** | <form action="{{ route('admin.master-data.barang-gadai.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?')"> |
| 66 | `` | **Kirim** | <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg"> |

## ðŸ„ `admin\master-data\biaya-transfer\create.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 79 | `` | **Kirim** | <button type="submit" class="flex-1 px-4 py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-semibold hover:from-[#4a3514] hover:to-[#674c1d] transition-all"> |
| 98 | `` | **Kirim** | document.querySelector('form').addEventListener('submit', function(e) { |

## ðŸ„ `admin\master-data\biaya-transfer\edit.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 79 | `` | **Kirim** | <button type="submit" class="flex-1 px-4 py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-semibold hover:from-[#4a3514] hover:to-[#674c1d] transition-all"> |
| 98 | `` | **Kirim** | document.querySelector('form').addEventListener('submit', function(e) { |

## ðŸ„ `admin\master-data\biaya-transfer\index.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 64 | `` | **AKTIF** | <button type="submit" class="px-3 py-1 {{ $item->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }} rounded-full text-xs font-semibold"> |
| 65 | `` | **AKTIF** | {{ $item->is_active ? 'Aktif' : 'Tidak Aktif' }} |
| 69 | `` | **AKTIF** | <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $item->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}"> |
| 70 | `` | **AKTIF** | {{ $item->is_active ? 'Aktif' : 'Tidak Aktif' }} |
| 82 | `` | **Kirim** | <form action="{{ route('admin.master-data.biaya-transfer.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')"> |
| 85 | `` | **Kirim** | <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"> |

## ðŸ„ `admin\master-data\bunga-pinjaman\create.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 84 | `` | **Kirim** | <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl hover:from-[#4a3514] hover:to-[#674c1d] transition-all font-medium shadow-md"> |

## ðŸ„ `admin\master-data\bunga-pinjaman\edit.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 84 | `` | **Kirim** | <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl hover:from-[#4a3514] hover:to-[#674c1d] transition-all font-medium shadow-md"> |

## ðŸ„ `admin\master-data\bunga-pinjaman\index.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 79 | `` | **Kirim** | <button type="submit" class="px-3 py-1 rounded-full text-xs font-medium {{ $item->status_aktif ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }} hover:opacity-80 transition-all"> |
| 100 | `` | **Kirim** | <form action="{{ route('admin.master-data.bunga-pinjaman.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')"> |
| 103 | `` | **Kirim** | <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-all"> |

## ðŸ„ `admin\master-data\denda-pinjaman\create.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 53 | `` | **Kirim** | <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#8b6f2f] to-[#d4af37] text-white rounded-xl hover:from-[#674c1d] hover:to-[#8b6f2f] transition-all font-medium shadow-md"> |

## ðŸ„ `admin\master-data\denda-pinjaman\edit.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 51 | `` | **Kirim** | <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#8b6f2f] to-[#d4af37] text-white rounded-xl hover:from-[#674c1d] hover:to-[#8b6f2f] transition-all font-medium shadow-md"> |

## ðŸ„ `admin\master-data\denda-pinjaman\index.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 69 | `` | **Kirim** | <button type="submit" class="px-3 py-1 rounded-full text-xs font-medium {{ $item->status_aktif ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }} hover:opacity-80"> |
| 88 | `` | **Kirim** | <form action="{{ route('admin.master-data.denda-pinjaman.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')"> |
| 91 | `` | **Kirim** | <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg"> |

## ðŸ„ `admin\master-data\gadai-debugger\index.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 58 | `` | **Kirim** | <button type="submit" onclick="return confirm('Yakin ingin memajukan waktu sistem (Gadai)? Ini akan memicu denda jika ada yang lewat batas.')" class="w-full flex justify-center items-center gap-2 py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"> |
| 113 | `` | **AKTIF** | @if($gadai->status == 'active') |
| 114 | `ACTIVE` | **AKTIF** | <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-green-100 text-green-800">ACTIVE</span> |
| 116 | `GRACE PERIOD` | **MASA TENGGANG** | <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-red-100 text-red-800">GRACE PERIOD</span> |

## ðŸ„ `admin\master-data\inap-kendaraan\create.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 70 | `` | **Kirim** | <button type="submit" class="px-8 py-3 bg-[#674c1d] text-white font-black rounded-2xl hover:bg-[#8b6f2f] transition-all shadow-lg shadow-amber-900/20 text-sm"> |

## ðŸ„ `admin\master-data\inap-kendaraan\edit.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 71 | `` | **Kirim** | <button type="submit" class="px-8 py-3 bg-[#674c1d] text-white font-black rounded-2xl hover:bg-[#8b6f2f] transition-all shadow-lg shadow-amber-900/20 text-sm"> |

## ðŸ„ `admin\master-data\inap-kendaraan\index.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 72 | `` | **Kirim** | <form action="{{ route('admin.master-data.inap-kendaraan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')"> |
| 75 | `` | **Kirim** | <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors" title="Hapus"> |

## ðŸ„ `admin\master-data\item-gadai\create.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 111 | `` | **AKTIF** | <label for="is_active" |
| 114 | `` | **AKTIF** | <select name="is_active" id="is_active" |
| 117 | `` | **AKTIF** | <option value="1" {{ old('is_active', '1') === '1' ? 'selected' : '' }}>Aktif</option> |
| 118 | `` | **AKTIF** | <option value="0" {{ old('is_active') === '0' ? 'selected' : '' }}>Non-Aktif</option> |
| 209 | `` | **Kirim** | <button type="submit" |

## ðŸ„ `admin\master-data\item-gadai\edit.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 113 | `` | **AKTIF** | <label for="is_active" |
| 116 | `` | **AKTIF** | <select name="is_active" id="is_active" |
| 119 | `` | **AKTIF** | <option value="1" {{ (old('is_active', $data->is_active)) == 1 ? 'selected' : '' }}>Aktif</option> |
| 120 | `` | **AKTIF** | <option value="0" {{ (old('is_active', $data->is_active)) == 0 ? 'selected' : '' }}>Non-Aktif |
| 224 | `` | **Kirim** | <button type="submit" |

## ðŸ„ `admin\master-data\item-gadai\index.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 89 | `` | **AKTIF** | @if($item->is_active) |
| 102 | `` | **Kirim** | <form action="{{ route('admin.master-data.item-gadai.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')"> |
| 105 | `` | **Kirim** | <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors" title="Hapus"> |

## ðŸ„ `admin\master-data\jenis-deposito\create.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 49 | `` | **Kirim** | <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#d4af37] to-[#8b6f2f] text-white rounded-xl hover:from-[#8b6f2f] hover:to-[#674c1d] transition-all font-medium shadow-md"> |

## ðŸ„ `admin\master-data\jenis-deposito\edit.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 45 | `` | **Kirim** | <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#d4af37] to-[#8b6f2f] text-white rounded-xl hover:from-[#8b6f2f] hover:to-[#674c1d] transition-all font-medium shadow-md"> |

## ðŸ„ `admin\master-data\jenis-deposito\index.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 58 | `` | **Kirim** | <button type="submit" class="px-3 py-1 rounded-full text-xs font-medium {{ $item->status_aktif ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}"> |
| 76 | `` | **Kirim** | <form action="{{ route('admin.master-data.jenis-deposito.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?')"> |
| 79 | `` | **Kirim** | <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg"> |

## ðŸ„ `admin\master-data\kategori-deposito\create.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 72 | `` | **Kirim** | <button type="submit" class="px-5 py-2.5 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white font-medium rounded-xl hover:shadow-lg hover:shadow-[#674c1d]/20 transition-all duration-300"> |

## ðŸ„ `admin\master-data\kategori-deposito\edit.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 71 | `` | **Kirim** | <button type="submit" class="px-5 py-2.5 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white font-medium rounded-xl hover:shadow-lg hover:shadow-[#674c1d]/20 transition-all duration-300"> |

## ðŸ„ `admin\master-data\kategori-deposito\index.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 74 | `` | **Kirim** | <form action="{{ route('admin.master-data.kategori-deposito.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan kategori ini?');" class="inline-block"> |
| 77 | `` | **Kirim** | <button type="submit" class="text-red-500 hover:text-red-700 transition-colors" title="Nonaktifkan"> |

## ðŸ„ `admin\master-data\kategori-gadai\edit.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 127 | `` | **Kirim** | <button type="submit" class="px-6 py-2.5 bg-[#674c1d] text-white font-bold rounded-xl shadow-lg shadow-[#674c1d]/20 hover:shadow-xl hover:-translate-y-0.5 transition-all"> |

## ðŸ„ `admin\master-data\lokasi-perusahaan\create.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 73 | `` | **Kirim** | <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl hover:from-[#4a3514] hover:to-[#674c1d] transition-all font-medium shadow-md"> |

## ðŸ„ `admin\master-data\lokasi-perusahaan\edit.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 69 | `` | **Kirim** | <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl hover:from-[#4a3514] hover:to-[#674c1d] transition-all font-medium shadow-md"> |

## ðŸ„ `admin\master-data\lokasi-perusahaan\index.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 56 | `` | **Kirim** | <button type="submit" class="px-3 py-1 rounded-full text-xs font-medium {{ $item->status_aktif ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}"> |
| 74 | `` | **Kirim** | <form action="{{ route('admin.master-data.lokasi-perusahaan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?')"> |
| 77 | `` | **Kirim** | <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg"> |

## ðŸ„ `admin\master-data\rekening-perusahaan\create.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 100 | `` | **Kirim** | <button type="submit" class="px-6 py-3 bg-gradient-to-r from-[#8b6f2f] to-[#d4af37] text-white rounded-xl hover:from-[#674c1d] hover:to-[#8b6f2f] transition-all font-medium shadow-md">Simpan Data</button> |

## ðŸ„ `admin\master-data\rekening-perusahaan\edit.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 112 | `` | **Kirim** | <button type="submit" class="px-6 py-3 bg-gradient-to-r from-[#8b6f2f] to-[#d4af37] text-white rounded-xl hover:from-[#674c1d] hover:to-[#8b6f2f] transition-all font-medium shadow-md"> |

## ðŸ„ `admin\master-data\rekening-perusahaan\index.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 68 | `` | **Kirim** | <form action="{{ route('admin.master-data.rekening-perusahaan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?')"> |
| 71 | `` | **Kirim** | <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg">Hapus</button> |

## ðŸ„ `admin\master-data\slot-storage\index.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 51 | `` | **Kirim** | <select name="kategori" class="w-full border-gray-200 rounded-xl focus:ring-[#674c1d] focus:border-[#674c1d]" onchange="this.form.submit()"> |
| 100 | `` | **Kirim** | <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white font-medium rounded-xl hover:shadow-lg transition-all"> |
| 131 | `` | **Kirim** | <button type="submit" class="px-5 py-2.5 bg-red-600 text-white font-medium rounded-xl hover:bg-red-700 hover:shadow-lg transition-all"> |

## ðŸ„ `admin\master-data\suku-bunga-deposito\create.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 73 | `` | **Kirim** | <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#d4af37] to-[#8b6f2f] text-white rounded-xl hover:from-[#8b6f2f] hover:to-[#674c1d] transition-all font-medium shadow-md"> |

## ðŸ„ `admin\master-data\suku-bunga-deposito\edit.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 74 | `` | **Kirim** | <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#d4af37] to-[#8b6f2f] text-white rounded-xl hover:from-[#8b6f2f] hover:to-[#674c1d] transition-all font-medium shadow-md"> |

## ðŸ„ `admin\master-data\suku-bunga-deposito\index.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 58 | `` | **Kirim** | <button type="submit" class="px-3 py-1 rounded-full text-xs font-medium {{ $item->status ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}"> |
| 76 | `` | **Kirim** | <form action="{{ route('admin.master-data.suku-bunga-deposito.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?')"> |
| 79 | `` | **Kirim** | <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg"> |

## ðŸ„ `admin\master-data\suku-bunga-tabungan\create.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 42 | `` | **Kirim** | <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#4a3514] to-[#674c1d] text-white rounded-xl hover:from-[#674c1d] hover:to-[#8b6f2f] transition-all font-medium shadow-md"> |

## ðŸ„ `admin\master-data\suku-bunga-tabungan\edit.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 39 | `` | **Kirim** | <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#4a3514] to-[#674c1d] text-white rounded-xl hover:from-[#674c1d] hover:to-[#8b6f2f] transition-all font-medium shadow-md"> |

## ðŸ„ `admin\master-data\suku-bunga-tabungan\index.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 55 | `` | **Kirim** | <form action="{{ route('admin.master-data.suku-bunga-tabungan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?')"> |
| 58 | `` | **Kirim** | <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg"> |

## ðŸ„ `admin\master-data\tenor-deposito\create.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 47 | `` | **Kirim** | <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#d4af37] to-[#8b6f2f] text-white rounded-xl hover:from-[#8b6f2f] hover:to-[#674c1d] transition-all font-medium shadow-md"> |

## ðŸ„ `admin\master-data\tenor-deposito\edit.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 47 | `` | **Kirim** | <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#d4af37] to-[#8b6f2f] text-white rounded-xl hover:from-[#8b6f2f] hover:to-[#674c1d] transition-all font-medium shadow-md"> |

## ðŸ„ `admin\master-data\tenor-deposito\index.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 59 | `` | **Kirim** | <button type="submit" class="px-3 py-1 rounded-full text-xs font-medium {{ $item->aktif ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }} hover:opacity-80"> |
| 78 | `` | **Kirim** | <form action="{{ route('admin.master-data.tenor-deposito.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?')"> |
| 81 | `` | **Kirim** | <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg"> |

## ðŸ„ `admin\nasabah\detail.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 349 | `` | **Kirim** | <button type="submit" onclick="return confirm('Yakin reset PIN nasabah ini? PIN lama akan diganti dengan PIN baru.')" |

## ðŸ„ `admin\nasabah\index.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 59 | `` | **Kirim** | <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-semibold hover:shadow-lg transition-all"> |

## ðŸ„ `admin\nasabah\pending-changes.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 250 | `Actions` | **Aksi** | <!-- Footer Actions --> |
| 257 | `` | **Tolak** | <form action="{{ route('admin.nasabah.reject-change', $request->id) }}" method="POST" class="inline"> |
| 259 | `` | **Tolak** | <input type="hidden" name="catatan_admin" id="reject_catatan_{{ $request->id }}"> |
| 260 | `` | **Kirim** | <button type="submit" onclick="return confirmReject({{ $request->id }})" |
| 268 | `` | **Setujui** | <form action="{{ route('admin.nasabah.approve-change', $request->id) }}" method="POST" class="inline"> |
| 270 | `` | **Setujui** | <input type="hidden" name="catatan_admin" id="approve_catatan_{{ $request->id }}"> |
| 271 | `` | **Kirim** | <button type="submit" onclick="return confirmApprove({{ $request->id }})" |
| 325 | `Approve` | **Setujui** | function confirmApprove(requestId) { |
| 327 | `` | **Setujui** | document.getElementById('approve_catatan_' + requestId).value = catatan; |
| 332 | `Reject` | **Tolak** | function confirmReject(requestId) { |
| 338 | `` | **Tolak** | document.getElementById('reject_catatan_' + requestId).value = userCatatan; |
| 341 | `` | **Tolak** | document.getElementById('reject_catatan_' + requestId).value = catatan; |

## ðŸ„ `admin\notifications\index.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 17 | `` | **Kirim** | <button type="submit" class="px-4 py-2 bg-[#674c1d] text-white rounded-lg hover:bg-[#4a3514] transition-colors text-sm font-medium"> |
| 41 | `` | **Kirim** | <button type="submit" class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-medium">Filter</button> |
| 52 | `` | **Kirim** | <button type="submit" class="w-full text-left px-6 py-4 hover:bg-gray-50/50 transition-colors flex items-start gap-4"> |

## ðŸ„ `admin\petty-cash\admin-dashboard.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 185 | `Actions` | **Aksi** | {{-- Quick Actions --}} |

## ðŸ„ `admin\petty-cash\laporan.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 162 | `` | **Setujui** | <option value="approved_owner" {{ request('status') === 'approved_owner' ? 'selected' : '' }}>Disetujui</option> |
| 163 | `` | **Tolak** | <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option> |
| 166 | `` | **Kirim** | <button type="submit" |
| 211 | `` | **Setujui** | @elseif($item->status === 'approved_owner') |

## ðŸ„ `admin\petty-cash\owner-wallet.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 157 | `` | **Aksi** | {{-- Filter & Recent Transactions --}} |
| 180 | `` | **Kirim** | <button type="submit" class="p-2 bg-[#674c1d] text-white hover:bg-[#4a3514] rounded-lg transition-colors"> |
| 286 | `` | **Kirim** | <form action="{{ route('admin.petty-cash.owner-wallet.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini? Saldo akan disesuaikan otomatis.')"> |
| 289 | `` | **Kirim** | <button type="submit" class="p-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors" title="Hapus Transaksi"> |
| 372 | `` | **Kirim** | document.addEventListener('submit', function(e) { |
| 374 | `` | **Kirim** | const submitBtn = form.querySelector('button[type="submit"]'); |
| 375 | `` | **Kirim** | if (submitBtn) { |
| 376 | `` | **Kirim** | submitBtn.disabled = true; |
| 377 | `` | **Kirim** | submitBtn.innerHTML = ` |

## ðŸ„ `admin\petty-cash\penerimaan-owner.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 51 | `Approve` | **Setujui** | <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Approved</p> |
| 52 | `` | **Setujui** | <p class="text-2xl font-bold text-gray-900" id="stat-approved">{{ $stats['approved'] }}</p> |
| 60 | `Reject` | **Tolak** | <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Rejected</p> |
| 61 | `` | **Tolak** | <p class="text-2xl font-bold text-gray-900" id="stat-rejected">{{ $stats['rejected'] }}</p> |
| 92 | `Approve` | **Setujui** | <option value="approved">Approved (ACC)</option> |
| 93 | `Reject` | **Tolak** | <option value="rejected">Rejected</option> |
| 114 | `Loading` | **Memuat** | {{-- Loading Overlay --}} |
| 115 | `` | **Memuat** | <div id="loading-overlay" class="absolute inset-0 bg-white/60 backdrop-blur-[1px] z-10 hidden flex items-center justify-center transition-all"> |
| 252 | `Upload` | **Unggah** | <label class="block text-[0.65rem] font-black text-gray-500 uppercase tracking-widest mb-2">Upload Bukti Transfer</label> |
| 254 | `` | **Unggah** | <i class="fas fa-cloud-upload-alt text-gray-400 group-hover:text-[#674c1d] mb-1"></i> |
| 260 | `Upload` | **Unggah** | <label class="block text-[0.65rem] font-black text-gray-500 uppercase tracking-widest mb-2">Upload Foto Cash</label> |
| 277 | `` | **AKTIF** | <button type="submit" class="flex-[3] px-8 py-4.5 bg-[#674c1d] text-white rounded-[1.25rem] text-sm font-black shadow-2xl shadow-[#674c1d]/30 hover:bg-[#4a3514] transition-all transform hover:scale-[1.02] active:scale-[0.98]"> |
| 296 | `` | **Memuat** | const loadingOverlay = document.getElementById('loading-overlay'); |
| 304 | `Loading` | **Memuat** | showLoading(true); |
| 321 | `Loading` | **Memuat** | .finally(() => showLoading(false)); |
| 327 | `` | **Setujui** | document.getElementById('stat-approved').innerText = stats.approved; |
| 328 | `` | **Tolak** | document.getElementById('stat-rejected').innerText = stats.rejected; |
| 336 | `Loading` | **Memuat** | showLoading(true); |
| 350 | `Loading` | **Memuat** | .finally(() => showLoading(false)); |
| 360 | `Loading` | **Memuat** | function showLoading(show) { |
| 362 | `` | **Memuat** | loadingOverlay.classList.remove('hidden'); |
| 363 | `` | **Memuat** | loadingOverlay.classList.add('flex'); |
| 365 | `` | **Memuat** | loadingOverlay.classList.add('hidden'); |
| 366 | `` | **Memuat** | loadingOverlay.classList.remove('flex'); |

## ðŸ„ `admin\petty-cash\penerimaan.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 71 | `` | **Setujui** | @elseif($item->status === 'approved') |
| 91 | `` | **Setujui** | onclick="document.getElementById('approve-modal-{{ $item->id }}').classList.remove('hidden')" |
| 95 | `Reject` | **Tolak** | {{-- Reject --}} |
| 97 | `` | **Tolak** | onclick="document.getElementById('reject-modal-{{ $item->id }}').classList.remove('hidden')" |
| 110 | `` | **Setujui** | <div id="approve-modal-{{ $item->id }}" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50"> |
| 114 | `` | **Setujui** | <form action="{{ route('admin.petty-cash.penerimaan.approve', $item->id) }}" method="POST"> |
| 121 | `` | **Setujui** | onclick="document.getElementById('approve-modal-{{ $item->id }}').classList.add('hidden')" |
| 125 | `` | **Kirim** | <button type="submit" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-bold hover:bg-green-700"> |
| 134 | `Reject` | **Tolak** | {{-- Reject Modal --}} |
| 136 | `` | **Tolak** | <div id="reject-modal-{{ $item->id }}" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50"> |
| 139 | `` | **Tolak** | <form action="{{ route('admin.petty-cash.penerimaan.reject', $item->id) }}" method="POST"> |
| 146 | `` | **Tolak** | onclick="document.getElementById('reject-modal-{{ $item->id }}').classList.add('hidden')" |
| 150 | `` | **Kirim** | <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-bold hover:bg-red-700"> |

## ðŸ„ `admin\petty-cash\setoran-approval-detail.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 43 | `` | **Setujui** | @elseif($setoran->status === 'approved_owner') |
| 138 | `` | **Setujui** | <form action="{{ route('admin.petty-cash.setoran-approval.approve', $setoran->id) }}" method="POST" class="flex-1"> |
| 140 | `` | **Kirim** | <button type="submit" |
| 146 | `` | **Setujui** | APPROVE Setoran |
| 150 | `` | **Tolak** | <button type="button" onclick="document.getElementById('reject-modal').classList.remove('hidden')" |
| 156 | `Reject` | **Tolak** | {{-- Reject Modal --}} |
| 157 | `` | **Tolak** | <div id="reject-modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50"> |
| 160 | `` | **Tolak** | <form action="{{ route('admin.petty-cash.setoran-approval.reject', $setoran->id) }}" method="POST"> |
| 166 | `` | **Tolak** | <button type="button" onclick="document.getElementById('reject-modal').classList.add('hidden')" |
| 168 | `` | **Kirim** | <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-bold">Tolak</button> |

## ðŸ„ `admin\petty-cash\setoran-approval.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 16 | `` | **Setujui** | @foreach(['pending' => 'Pending', 'approved_owner' => 'Disetujui', 'rejected' => 'Ditolak'] as $val => $label) |
| 70 | `` | **Setujui** | @elseif($s->status === 'approved_owner') |
| 74 | `Approve` | **Setujui** | Approved |
| 83 | `Reject` | **Tolak** | Rejected |
| 180 | `` | **Setujui** | @elseif($s->status === 'approved_owner') |
| 181 | `Approve` | **Setujui** | <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-[10px] font-bold uppercase tracking-wider">Approved</span> |
| 183 | `Reject` | **Tolak** | <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-[10px] font-bold uppercase tracking-wider">Rejected</span> |
| 197 | `` | **Setujui** | {{-- APPROVE Modal Trigger --}} |
| 199 | `` | **Setujui** | onclick="document.getElementById('approve-modal-{{ $s->id }}').classList.remove('hidden')" |
| 202 | `` | **Setujui** | APPROVE |
| 205 | `Reject` | **Tolak** | {{-- Reject Modal Trigger --}} |
| 207 | `` | **Tolak** | onclick="document.getElementById('reject-modal-{{ $s->id }}').classList.remove('hidden')" |
| 214 | `Print` | **Cetak** | {{-- Print/Detail Link for History --}} |
| 227 | `Approve` | **Setujui** | {{-- Approve Modal --}} |
| 229 | `` | **Setujui** | <div id="approve-modal-{{ $s->id }}" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50"> |
| 252 | `` | **Setujui** | <form action="{{ route('admin.petty-cash.setoran-approval.approve', $s->id) }}" method="POST"> |
| 261 | `` | **Setujui** | onclick="document.getElementById('approve-modal-{{ $s->id }}').classList.add('hidden')" |
| 263 | `` | **Kirim** | <button type="submit" class="flex-[2] px-6 py-3.5 bg-[#674c1d] text-white rounded-2xl text-sm font-black shadow-xl shadow-[#674c1d]/20 hover:bg-[#4a3514] transition-all">SETUJUI</button> |
| 270 | `Reject` | **Tolak** | {{-- Reject Modal --}} |
| 272 | `` | **Tolak** | <div id="reject-modal-{{ $s->id }}" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50"> |
| 284 | `` | **Tolak** | <form action="{{ route('admin.petty-cash.setoran-approval.reject', $s->id) }}" method="POST"> |
| 293 | `` | **Tolak** | onclick="document.getElementById('reject-modal-{{ $s->id }}').classList.add('hidden')" |
| 295 | `` | **Kirim** | <button type="submit" class="flex-[2] px-6 py-3.5 bg-red-600 text-white rounded-2xl text-sm font-black shadow-xl shadow-red-200 hover:bg-red-700 transition-all">TOLAK</button> |

## ðŸ„ `admin\petty-cash\setoran-kantor.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 131 | `Upload` | **Unggah** | {{-- Upload Foto --}} |
| 154 | `` | **Kirim** | <button type="submit" class="w-full py-3 bg-[#674c1d] text-white rounded-xl font-bold text-sm hover:bg-[#4a3514] transition-colors"> |
| 175 | `` | **Setujui** | @elseif($s->status === 'approved_owner') |
| 261 | `` | **Kirim** | formSetor.addEventListener('submit', function(e) { |

## ðŸ„ `admin\petty-cash\partials\_owner_wallet_modals.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 41 | `Upload` | **Unggah** | <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Upload Bukti Cash</label> |
| 46 | `Upload` | **Unggah** | <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Upload Bukti Transfer</label> |
| 54 | `` | **Kirim** | <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-xl font-bold text-sm hover:bg-green-700 shadow-md">Simpan Modal</button> |
| 110 | `Upload` | **Unggah** | <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Upload Bukti Cash</label> |
| 115 | `Upload` | **Unggah** | <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Upload Bukti Transfer</label> |
| 123 | `` | **Kirim** | <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-xl font-bold text-sm hover:bg-red-700 shadow-md">Simpan Pengeluaran</button> |
| 190 | `Upload` | **Unggah** | <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Upload Bukti (Opsional)</label> |
| 197 | `` | **Kirim** | <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-xl font-bold text-sm hover:bg-purple-700 shadow-md">Proses Tarik Saldo</button> |
| 263 | `` | **Kirim** | <button type="submit" class="px-6 py-2 bg-amber-600 text-white rounded-xl font-bold text-sm hover:bg-amber-700 shadow-md">Pindahkan Dana</button> |
| 277 | `` | **Kirim** | const submitBtn = form.querySelector('button[type="submit"]'); |
| 285 | `` | **Kirim** | submitBtn.disabled = isInvalid; |
| 287 | `` | **Kirim** | submitBtn.classList.add('opacity-50', 'cursor-not-allowed'); |
| 288 | `` | **Kirim** | submitBtn.title = "Saldo Modal Awal tidak mencukupi"; |
| 290 | `` | **Kirim** | submitBtn.classList.remove('opacity-50', 'cursor-not-allowed'); |
| 291 | `` | **Kirim** | submitBtn.title = ""; |
| 308 | `` | **Kirim** | const submitBtnWD  = formWD.querySelector('button[type="submit"]'); |
| 322 | `` | **Kirim** | submitBtnWD.disabled = isInvalid; |
| 324 | `` | **Kirim** | submitBtnWD.classList.add('opacity-50', 'cursor-not-allowed'); |
| 325 | `` | **Kirim** | submitBtnWD.title = "Saldo pada sumber terpilih tidak mencukupi"; |
| 327 | `` | **Kirim** | submitBtnWD.classList.remove('opacity-50', 'cursor-not-allowed'); |
| 328 | `` | **Kirim** | submitBtnWD.title = ""; |
| 345 | `` | **Kirim** | const submitBtnIT = formIT.querySelector('button[type="submit"]'); |
| 358 | `` | **Kirim** | submitBtnIT.disabled = isInvalid; |
| 360 | `` | **Kirim** | submitBtnIT.classList.add('opacity-50', 'cursor-not-allowed'); |
| 362 | `` | **Kirim** | submitBtnIT.classList.remove('opacity-50', 'cursor-not-allowed'); |

## ðŸ„ `admin\petty-cash\partials\_penerimaan_table.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 51 | `` | **Setujui** | @elseif($item->status === 'approved') |

## ðŸ„ `admin\pinjaman\angsuran.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 54 | `` | **Kirim** | <button type="submit" class="px-6 py-2 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-md font-medium"> |

## ðŸ„ `admin\pinjaman\create-pinjaman.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 102 | `` | **Kirim** | <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl hover:from-[#4a3514] hover:to-[#674c1d] transition-all font-medium shadow-md"> |

## ðŸ„ `admin\pinjaman\detail-pembayaran.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 95 | `` | **Unggah** | <p class="text-xs text-gray-500 mt-1">Wajib upload bukti foto pertemuan setelah menyetujui.</p> |
| 248 | `Actions` | **Aksi** | <!-- Sidebar Actions (sticky) --> |
| 252 | `` | **Unggah** | <!-- Tunai/Janji Temu: Langsung upload bukti foto (tanpa setujui dulu) --> |
| 255 | `Upload` | **Unggah** | <p class="text-sm text-gray-600 mb-4">Upload bukti foto bahwa admin dan nasabah telah bertemu serta pembayaran tunai diterima. Setelah upload, pembayaran akan otomatis dikonfirmasi.</p> |
| 256 | `` | **Unggah** | <form method="POST" action="{{ route('admin.pinjaman.upload-serah-terima', $pengajuan->id) }}" enctype="multipart/form-data"> |
| 262 | `` | **Unggah** | <p class="text-xs text-gray-500 mt-1">Wajib upload. Format: JPG, PNG (Max 5MB)</p> |
| 270 | `` | **Kirim** | <button type="submit" onclick="return confirm('Upload foto akan mengkonfirmasi pembayaran dan memperbarui angsuran. Lanjutkan?')" |
| 272 | `Upload` | **Unggah** | ✓ Upload Bukti & Konfirmasi Pembayaran |
| 276 | `Reject` | **Tolak** | <button type="button" onclick="showRejectModal()" class="w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors"> |
| 281 | `` | **Tolak** | <div id="rejectModal" class="hidden fixed inset-0 bg-gray-600/50 backdrop-blur-sm z-50 flex items-center justify-center"> |
| 284 | `` | **Tolak** | <form method="POST" action="{{ route('admin.pinjaman.reject-pembayaran', $pengajuan->id) }}"> |
| 293 | `Reject` | **Tolak** | <button type="button" onclick="hideRejectModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Batal</button> |
| 294 | `` | **Kirim** | <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">Tolak</button> |
| 300 | `Approve` | **Setujui** | <!-- Transfer: Approve/Reject dulu --> |
| 303 | `` | **Kirim** | <form method="POST" action="{{ route('admin.pinjaman.approve-pembayaran', $pengajuan->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui pembayaran ini?')" class="mb-3"> |
| 319 | `` | **Kirim** | <button type="submit" class="w-full px-4 py-3 bg-linear-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all font-medium shadow-md"> |
| 323 | `Reject` | **Tolak** | <button onclick="showRejectModal()" class="w-full px-4 py-3 bg-linear-to-r from-red-600 to-red-700 text-white rounded-lg hover:from-red-700 hover:to-red-800 transition-all font-medium shadow-md"> |
| 327 | `` | **Tolak** | <div id="rejectModal" class="hidden fixed inset-0 bg-gray-600/50 backdrop-blur-sm z-50 flex items-center justify-center"> |
| 330 | `` | **Tolak** | <form method="POST" action="{{ route('admin.pinjaman.reject-pembayaran', $pengajuan->id) }}"> |
| 339 | `Reject` | **Tolak** | <button type="button" onclick="hideRejectModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Batal</button> |
| 340 | `` | **Kirim** | <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">Tolak</button> |
| 354 | `Upload` | **Unggah** | <label class="block text-sm font-medium text-gray-700 mb-2">Upload Bukti Transfer (Opsional)</label> |
| 364 | `` | **Kirim** | <button type="submit" onclick="return confirm('Konfirmasi pembayaran akan memperbarui angsuran. Lanjutkan?')" |
| 373 | `` | **Unggah** | <!-- Tunai sudah disetujui tapi belum upload: tampilkan form upload (fallback) --> |
| 376 | `Upload` | **Unggah** | <p class="text-sm text-gray-600 mb-4">Upload bukti foto bahwa admin dan nasabah telah bertemu serta pembayaran tunai diterima.</p> |
| 377 | `` | **Unggah** | <form method="POST" action="{{ route('admin.pinjaman.upload-serah-terima', $pengajuan->id) }}" enctype="multipart/form-data"> |
| 383 | `` | **Unggah** | <p class="text-xs text-gray-500 mt-1">Wajib upload. Format: JPG, PNG (Max 5MB)</p> |
| 391 | `` | **Kirim** | <button type="submit" onclick="return confirm('Upload foto akan mengkonfirmasi pembayaran dan memperbarui angsuran. Lanjutkan?')" |
| 393 | `Upload` | **Unggah** | ✓ Upload Bukti & Konfirmasi Pembayaran |
| 417 | `Reject` | **Tolak** | function showRejectModal() { |
| 418 | `` | **Tolak** | document.getElementById('rejectModal').classList.remove('hidden'); |
| 421 | `Reject` | **Tolak** | function hideRejectModal() { |
| 422 | `` | **Tolak** | document.getElementById('rejectModal').classList.add('hidden'); |

## ðŸ„ `admin\pinjaman\detail-pengajuan.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 146 | `Actions` | **Aksi** | <!-- Sidebar Actions (sticky) --> |
| 153 | `Approve` | **Setujui** | <!-- Status Pending - Show Approve/Reject --> |
| 186 | `` | **Setujui** | <form method="POST" action="{{ route('admin.pinjaman.approve-pengajuan', $pengajuan->id) }}" |
| 187 | `` | **Kirim** | onsubmit="return confirm('Setujui pengajuan ini? Status akan berubah menjadi DISETUJUI. Anda masih perlu CAIRKAN dana setelah ini.')"> |
| 194 | `` | **Kirim** | <button type="submit" disabled |
| 205 | `` | **Kirim** | <button type="submit" |
| 212 | `Reject` | **Tolak** | <button onclick="showRejectModal()" |
| 275 | `Reject` | **Tolak** | <!-- Reject Modal --> |
| 276 | `` | **Tolak** | <div id="rejectModal" |
| 280 | `` | **Tolak** | <form method="POST" action="{{ route('admin.pinjaman.reject-pengajuan', $pengajuan->id) }}"> |
| 289 | `Reject` | **Tolak** | <button type="button" onclick="hideRejectModal()" |
| 293 | `` | **Kirim** | <button type="submit" |
| 367 | `` | **Unggah** | <p class="text-xs text-gray-500 mt-1 italic">Wajib upload bukti pencairan (JPG, PNG, Max |
| 388 | `Submit` | **Kirim** | <button id="btnSubmitCairkan" type="submit" |
| 404 | `Reject` | **Tolak** | function showRejectModal() { |
| 405 | `` | **Tolak** | document.getElementById('rejectModal').classList.remove('hidden'); |
| 408 | `Reject` | **Tolak** | function hideRejectModal() { |
| 409 | `` | **Tolak** | document.getElementById('rejectModal').classList.add('hidden'); |
| 423 | `Submit` | **Kirim** | const btn = document.getElementById('btnSubmitCairkan'); |
| 441 | `Reject` | **Tolak** | hideRejectModal(); |
| 446 | `` | **Tolak** | document.getElementById('rejectModal')?.addEventListener('click', function (e) { |
| 447 | `Reject` | **Tolak** | if (e.target === this) hideRejectModal(); |

## ðŸ„ `admin\pinjaman\detail-pinjaman.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 238 | `` | **Kirim** | onsubmit="return confirm('Apakah Anda yakin ingin melakukan pelunasan dipercepat?')" |
| 310 | `` | **Kirim** | <button type="submit" |

## ðŸ„ `admin\pinjaman\edit-pinjaman.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 71 | `` | **Kirim** | <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl hover:from-[#4a3514] hover:to-[#674c1d] transition-all font-medium shadow-md"> |

## ðŸ„ `admin\pinjaman\index.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 135 | `Actions` | **Aksi** | <!-- Inline Actions --> |
| 136 | `` | **Setujui** | <form action="{{ route('admin.pinjaman.approve-pengajuan', $pengajuan->id) }}" method="POST" class="inline"> |
| 138 | `` | **Kirim** | <button type="submit" class="w-8 h-8 bg-green-50 text-green-600 hover:bg-green-600 hover:text-white rounded-lg flex items-center justify-center transition-colors" title="Setujui"> |
| 142 | `` | **Tolak** | <form action="{{ route('admin.pinjaman.reject-pengajuan', $pengajuan->id) }}" method="POST" class="inline"> |
| 144 | `` | **Kirim** | <button type="submit" class="w-8 h-8 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg flex items-center justify-center transition-colors" title="Tolak"> |

## ðŸ„ `admin\pinjaman\pembayaran.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 57 | `` | **Kirim** | <button type="submit" class="px-6 py-2 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-md font-medium"> |

## ðŸ„ `admin\pinjaman\pengajuan.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 31 | `` | **Setujui** | <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option> |
| 41 | `` | **Kirim** | <button type="submit" class="px-6 py-2 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-md font-medium"> |

## ðŸ„ `admin\pinjaman\pinjaman-aktif.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 40 | `` | **Kirim** | <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-md font-medium"> |

## ðŸ„ `admin\pinjaman\pinjaman-lunas.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 32 | `` | **Kirim** | <button type="submit" class="px-6 py-2 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-md font-medium"> |

## ðŸ„ `admin\tabungan\create-transaksi.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 145 | `Submit` | **Kirim** | {{-- Submit --}} |
| 150 | `` | **Kirim** | <button type="submit" id="submit-btn" class="flex-1 sm:flex-initial inline-flex justify-center items-center gap-2 px-8 py-3 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-semibold hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-lg shadow-[#674c1d]/20"> |
| 205 | `` | **Kirim** | document.getElementById('transaksi-form').addEventListener('submit', function(e) { |

## ðŸ„ `admin\tabungan\detail-janji-temu.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 102 | `` | **Memuat** | <iframe src="https://www.google.com/maps/embed?pb=!4v1771057242792!6m8!1m7!1sTDnmeXtVvimBtQeXmqSSCQ!2m2!1d-6.267415399913648!2d106.9806162945405!3f247.41483905689947!4f-35.52001210835799!5f0.7820865974627469" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Peta Lokasi Janji Temu"></iframe> |
| 154 | `Actions` | **Aksi** | <!-- Sidebar Actions (sticky) --> |
| 216 | `` | **Unggah** | <p class="text-xs text-gray-500 mt-1">Bisa upload foto uang / kwitansi / bukti {{ ($janjiTemu->jenis ?? 'setoran') == 'setoran' ? 'penerimaan' : 'penyerahan' }}</p> |
| 239 | `` | **Kirim** | <button type="submit" data-confirm-message="{{ $confirmMsg }}" onclick="return confirm(this.dataset.confirmMessage)" |
| 291 | `` | **Kirim** | <button type="submit" |
| 371 | `` | **Kirim** | formProses.addEventListener('submit', function(e) { |

## ðŸ„ `admin\tabungan\detail-pengajuan-setor.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 127 | `Approve` | **Setujui** | <!-- Approve Button --> |
| 130 | `Approve` | **Setujui** | <button onclick="showApproveModal()" |
| 145 | `Approve` | **Setujui** | <!-- Approve Modal with Keterangan Admin --> |
| 147 | `` | **Setujui** | <div id="approveModal" |
| 151 | `` | **Setujui** | <form id="approveForm" method="POST"> |
| 184 | `Approve` | **Setujui** | <button type="button" onclick="hideApproveModal()" |
| 188 | `` | **Kirim** | <button type="submit" |
| 259 | `Actions` | **Aksi** | <!-- Sidebar Actions (sticky) --> |
| 266 | `Reject` | **Tolak** | <button onclick="showRejectModal()" |
| 277 | `` | **Kirim** | onsubmit="return confirm('Yakin hapus? Tidak dapat dibatalkan!')"> |
| 280 | `` | **Kirim** | <button type="submit" |
| 294 | `Reject` | **Tolak** | <!-- Reject Modal --> |
| 295 | `` | **Tolak** | <div id="rejectModal" |
| 299 | `` | **Tolak** | <form method="POST" action="{{ route('admin.tabungan.reject-setor', $pengajuan->id) }}"> |
| 311 | `Reject` | **Tolak** | <button type="button" onclick="hideRejectModal()" |
| 315 | `` | **Kirim** | <button type="submit" |
| 349 | `Approve` | **Setujui** | function showApproveModal() { |
| 373 | `` | **Setujui** | const form = document.getElementById('approveForm'); |
| 380 | `` | **Setujui** | form.action = '{{ route("admin.tabungan.approve-setor", $pengajuan->id) }}'; |
| 384 | `` | **Setujui** | document.getElementById('approveModal').classList.remove('hidden'); |
| 387 | `Approve` | **Setujui** | function hideApproveModal() { |
| 388 | `` | **Setujui** | document.getElementById('approveModal').classList.add('hidden'); |
| 391 | `Reject` | **Tolak** | function showRejectModal() { |
| 392 | `` | **Tolak** | document.getElementById('rejectModal').classList.remove('hidden'); |
| 395 | `Reject` | **Tolak** | function hideRejectModal() { |
| 396 | `` | **Tolak** | document.getElementById('rejectModal').classList.add('hidden'); |

## ðŸ„ `admin\tabungan\detail-pengajuan-tarik.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 169 | `Actions` | **Aksi** | <!-- Sidebar Actions (sticky) --> |
| 172 | `Approve` | **Setujui** | <!-- Approve Form with Bank Selection --> |
| 175 | `` | **Setujui** | <form method="POST" action="{{ route('admin.tabungan.approve-tarik', $pengajuan->id) }}" enctype="multipart/form-data" class="space-y-4" id="approve-form"> |
| 210 | `Upload` | **Unggah** | <!-- Upload Bukti TF Admin --> |
| 212 | `Upload` | **Unggah** | <label class="block text-sm font-medium text-gray-700 mb-2">Upload Bukti Transfer *</label> |
| 229 | `` | **Kirim** | <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all font-medium shadow-md"> |
| 244 | `Reject` | **Tolak** | <!-- Reject Button --> |
| 247 | `Reject` | **Tolak** | <button onclick="showRejectModal()" class="w-full px-4 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg hover:from-red-700 hover:to-red-800 transition-all font-medium shadow-md"> |
| 252 | `Reject` | **Tolak** | <!-- Reject Modal --> |
| 253 | `` | **Tolak** | <div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"> |
| 256 | `` | **Tolak** | <form method="POST" action="{{ route('admin.tabungan.reject-tarik', $pengajuan->id) }}"> |
| 263 | `Reject` | **Tolak** | <button type="button" onclick="hideRejectModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"> |
| 266 | `` | **Kirim** | <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"> |
| 332 | `Reject` | **Tolak** | function showRejectModal() { |
| 333 | `` | **Tolak** | document.getElementById('rejectModal').classList.remove('hidden'); |
| 336 | `Reject` | **Tolak** | function hideRejectModal() { |
| 337 | `` | **Tolak** | document.getElementById('rejectModal').classList.add('hidden'); |

## ðŸ„ `admin\tabungan\detail-transaksi.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 19 | `` | **Kirim** | <form method="POST" action="{{ route('admin.tabungan.destroy-transaksi', $transaksi->id) }}" class="inline" onsubmit="return confirm('Yakin hapus transaksi ini?')"> |
| 22 | `` | **Kirim** | <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium"> |
| 28 | `` | **Cetak** | <a href="{{ route('admin.tabungan.print-struk-transaksi', $transaksi->id) }}" target="_blank" class="px-4 py-2 bg-[#674c1d] text-white rounded-lg hover:bg-[#8b6f2f] transition-colors text-sm font-medium inline-flex items-center gap-2"> |
| 41 | `` | **Cetak** | <a href="{{ route('admin.tabungan.print-struk-transaksi', $transaksi->id) }}" target="_blank" class="shrink-0 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium inline-flex items-center gap-2"> |
| 135 | `` | **Unggah** | Bukti transfer di-upload saat persetujuan |

## ðŸ„ `admin\tabungan\edit-transaksi.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 111 | `Upload` | **Unggah** | <!-- Upload Bukti Foto Baru (Multiple) --> |
| 113 | `Upload` | **Unggah** | <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Bukti Baru (Opsional)</label> |
| 117 | `` | **Unggah** | <p class="text-xs text-gray-500 mt-1">Bisa upload lebih dari 1 foto. Foto baru akan ditambahkan ke bukti yang sudah ada.</p> |
| 122 | `Submit` | **Kirim** | <!-- Submit --> |
| 127 | `` | **Kirim** | <button type="submit" class="flex-1 px-4 py-3 bg-gradient-to-r from-[[#674c1d]] to-[#8b6f2f] text-white rounded-xl font-semibold hover:from-[[#4a3514]] hover:to-[[#674c1d]] transition-all"> |
| 173 | `` | **Kirim** | document.getElementById('edit-form').addEventListener('submit', function(e) { |

## ðŸ„ `admin\tabungan\pengajuan-setor.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 39 | `` | **Kirim** | <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-md font-semibold text-sm"> |

## ðŸ„ `admin\tabungan\pengajuan-tarik.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 51 | `` | **Kirim** | <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-md font-semibold text-sm"> |

## ðŸ„ `admin\tabungan\saldo-nasabah.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 29 | `` | **Kirim** | <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-md font-semibold text-sm"> |

## ðŸ„ `admin\tabungan\transaksi.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 48 | `` | **Kirim** | <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[[#4a3514]] hover:to-[#674c1d] transition-all shadow-md font-medium"> |
| 137 | `` | **Kirim** | <form method="POST" action="{{ route('admin.tabungan.destroy-transaksi', $item->id) }}" class="inline" onsubmit="return confirm('Yakin hapus transaksi ini?')"> |
| 140 | `` | **Kirim** | <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-all text-xs font-medium border border-red-100"> |

## ðŸ„ `auth\login.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 129 | `` | **Kirim** | <form method="POST" action="{{ route('login.submit') }}" id="loginForm" class="space-y-6"> |
| 192 | `` | **Lupa kata sandi** | <!-- Remember Me & Forgot Password --> |
| 202 | `Submit` | **Kirim** | <!-- Submit Button --> |
| 203 | `` | **Kirim** | <button type="submit" id="loginButton" |
| 211 | `Loading` | **Memuat** | <span id="loginButtonLoading" class="hidden flex items-center justify-center gap-2"> |
| 300 | `All rights reserved` | **Hak cipta dilindungi** | <p>© 2026 Koperasi Majakara. All rights reserved.</p> |
| 341 | `` | **Kirim** | <button type="submit" id="verifyPinButton" |
| 344 | `Loading` | **Memuat** | <span id="verifyPinButtonLoading" class="hidden flex items-center justify-center gap-2"> |
| 374 | `` | **Kirim** | document.getElementById('loginForm').addEventListener('submit', function(e) { |
| 381 | `Loading` | **Memuat** | const loginButtonLoading = document.getElementById('loginButtonLoading'); |
| 386 | `Loading` | **Memuat** | loginButtonLoading.classList.remove('hidden'); |
| 421 | `Loading` | **Memuat** | loginButtonLoading.classList.add('hidden'); |
| 426 | `` | **Kirim** | form.submit(); |
| 431 | `` | **Kirim** | document.getElementById('pinForm').addEventListener('submit', function(e) { |
| 438 | `Loading` | **Memuat** | const verifyButtonLoading = document.getElementById('verifyPinButtonLoading'); |
| 448 | `Loading` | **Memuat** | verifyButtonLoading.classList.remove('hidden'); |
| 468 | `Loading` | **Memuat** | verifyButtonLoading.classList.add('hidden'); |
| 479 | `Loading` | **Memuat** | verifyButtonLoading.classList.add('hidden'); |
| 492 | `Loading` | **Memuat** | const loginButtonLoading = document.getElementById('loginButtonLoading'); |
| 495 | `Loading` | **Memuat** | loginButtonLoading.classList.add('hidden'); |
| 520 | `` | **Kirim** | document.getElementById('pinForm').dispatchEvent(new Event('submit')); |

## ðŸ„ `auth\register.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 118 | `` | **AKTIF** | .progress-step.active { |
| 165 | `` | **AKTIF** | <div class="flex flex-col items-center progress-step {{ $subStep == $i ? 'active' : '' }}"> |
| 310 | `` | **Kirim** | <form method="POST" action="{{ route('register.submit') }}" enctype="multipart/form-data" |
| 316 | `` | **Kirim** | {{-- Bawa data kritis Langkah 1 (Data Diri) di setiap submit substep 2–6 agar nomor HP tidak hilang --}} |
| 502 | `Upload` | **Unggah** | <span class="text-sm text-gray-600">Upload Foto KTP</span> |
| 521 | `Upload` | **Unggah** | <span class="text-sm text-gray-600">Upload Foto KK</span> |
| 659 | `Upload` | **Unggah** | <label class="block text-sm font-medium text-gray-700 mb-2">Ambil/Upload Foto |
| 662 | `Upload` | **Unggah** | <!-- Camera/Upload Options --> |
| 676 | `` | **Unggah** | <label for="file_ktp_upload" |
| 678 | `Upload` | **Unggah** | Upload dari File |
| 682 | `` | **Unggah** | <!-- Hidden file input for upload --> |
| 683 | `` | **Unggah** | <input type="file" name="file_ktp_upload" id="file_ktp_upload" accept="image/*" |
| 684 | `Upload` | **Unggah** | class="hidden" onchange="handleKtpUpload(this)"> |
| 688 | `Upload` | **Unggah** | class="hidden" onchange="handleKtpUpload(this)"> |
| 755 | `Loading` | **Memuat** | <span id="ocrLoading" class="hidden">Memproses...</span> |
| 946 | `Upload` | **Unggah** | <span class="text-sm text-gray-600">Upload Foto KTP</span> |
| 1037 | `Loading` | **Memuat** | {{-- Button Kirim OTP with Loading State --}} |
| 1040 | `Loading` | **Memuat** | onclick="setSendOtpAndLoading(this); return false;" |
| 1041 | `` | **AKTIF** | class="w-full px-6 py-4 bg-linear-to-r from-green-600 to-green-700 text-white rounded-xl hover:from-green-700 hover:to-green-800 transition-all font-bold text-lg flex items-center justify-center gap-3 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 active:translate-y-0"> |
| 1045 | `Loading` | **Memuat** | <svg class="w-6 h-6 animate-spin hidden" id="iconLoading" fill="none" stroke="currentColor" viewBox="0 0 24 24"> |
| 1154 | `` | **Kirim** | <button type="submit" name="send_otp" value="1" |
| 1261 | `` | **Kirim** | <button type="submit" |
| 1265 | `` | **Kirim** | <button type="submit" |
| 1270 | `` | **Kirim** | <button type="submit" |
| 1429 | `Upload` | **Unggah** | 'Browser Anda tidak mendukung akses kamera. Silakan gunakan opsi Upload dari File atau gunakan browser modern seperti Chrome, Firefox, atau Safari.' |
| 1472 | `Upload` | **Unggah** | errorMsg += 'Silakan gunakan opsi Upload dari File.'; |
| 1594 | `` | **Unggah** | const fileInput = document.getElementById('file_ktp_upload'); |
| 1599 | `Upload` | **Unggah** | handleKtpUpload(fileInput); |
| 1627 | `` | **Unggah** | const fileInput = document.getElementById('file_ktp_upload'); |
| 1641 | `Upload` | **Unggah** | function handleKtpUpload(input) { |
| 1671 | `` | **Unggah** | const fileInput = document.getElementById('file_ktp_upload'); |
| 1681 | `` | **Unggah** | alert('Silakan ambil atau upload foto KTP terlebih dahulu'); |
| 1691 | `Loading` | **Memuat** | const ocrLoading = document.getElementById('ocrLoading'); |
| 1696 | `Loading` | **Memuat** | ocrLoading.classList.remove('hidden'); |
| 1705 | `Loading` | **Memuat** | ocrLoading.classList.add('hidden'); |
| 1723 | `` | **Unggah** | const fileInput = document.getElementById('file_ktp_upload'); |
| 1743 | `Loading` | **Memuat** | ocrLoading.classList.add('hidden'); |
| 1753 | `Loading` | **Memuat** | function setSendOtpAndLoading(button) { |
| 1761 | `Loading` | **Memuat** | const iconLoading = document.getElementById('iconLoading'); |
| 1763 | `Loading` | **Memuat** | if (iconSend && iconLoading && textSendOtp) { |
| 1765 | `Loading` | **Memuat** | iconLoading.classList.remove('hidden'); |
| 1772 | `` | **Kirim** | if (form) form.submit(); |
| 1802 | `Submit` | **Kirim** | checkAutoSubmit(); |
| 1830 | `Submit` | **Kirim** | checkAutoSubmit(); |
| 1843 | `Submit` | **Kirim** | function checkAutoSubmit() { |
| 1856 | `` | **Kirim** | form.submit(); |

## ðŸ„ `components\admin\header.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 66 | `` | **Kirim** | <button type="submit" class="text-xs text-[#674c1d] hover:underline">Tandai semua dibaca</button> |
| 76 | `` | **Kirim** | <button type="submit" class="w-full text-left px-4 py-3 hover:bg-gray-50 transition-colors"> |
| 141 | `` | **Kirim** | <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100"> |
| 149 | `` | **Impor** | [x-cloak] { display: none !important; } |

## ðŸ„ `components\admin\sidebar.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 3 | `` | **AKTIF** | $isActive = function($route) use ($currentRoute) { |
| 7 | `` | **AKTIF** | $isPinjamanActive = str_starts_with($currentRoute, 'admin.pinjaman'); |
| 8 | `` | **AKTIF** | $isTabunganActive = str_starts_with($currentRoute, 'admin.tabungan'); |
| 9 | `` | **AKTIF** | $isLaporanActive = str_starts_with($currentRoute, 'admin.laporan'); |
| 10 | `` | **AKTIF** | $isActivityLogActive = str_starts_with($currentRoute, 'admin.activity-log'); |
| 11 | `` | **AKTIF** | $isPettyCashActive = str_starts_with($currentRoute, 'admin.petty-cash'); |
| 12 | `` | **AKTIF** | $isDepositoActive = str_starts_with($currentRoute, 'admin.deposito'); |
| 44 | `` | **AKTIF** | class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ $isActive('admin.dashboard') }}"> |
| 56 | `` | **AKTIF** | <div x-data="{ open: {{ $isTabunganActive ? 'true' : 'false' }} }" class="space-y-1"> |
| 58 | `` | **AKTIF** | class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 {{ $isTabunganActive ? 'bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' }}"> |
| 106 | `` | **AKTIF** | <div x-data="{ open: {{ $isPinjamanActive ? 'true' : 'false' }} }" class="space-y-1"> |
| 108 | `` | **AKTIF** | class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 {{ $isPinjamanActive ? 'bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' }}"> |
| 155 | `` | **AKTIF** | <div x-data="{ open: {{ $isDepositoActive ? 'true' : 'false' }} }" class="space-y-1"> |
| 157 | `` | **AKTIF** | class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 {{ $isDepositoActive ? 'bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' }}"> |
| 230 | `` | **AKTIF** | @php $isGadaiActive = str_starts_with($currentRoute, 'admin.gadai_baru'); @endphp |
| 231 | `` | **AKTIF** | <div x-data="{ open: {{ $isGadaiActive ? 'true' : 'false' }} }" class="space-y-1"> |
| 233 | `` | **AKTIF** | class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 {{ $isGadaiActive ? 'bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' }}"> |
| 278 | `` | **AKTIF** | class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ $isActive('admin.janji-temu') }}"> |
| 295 | `` | **AKTIF** | <div x-data="{ open: {{ $isPettyCashActive ? 'true' : 'false' }} }" class="space-y-1"> |
| 297 | `` | **AKTIF** | class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 {{ $isPettyCashActive ? 'bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' }}"> |
| 316 | `` | **AKTIF** | <span class="px-2 py-0.5 bg-amber-100 text-amber-800 text-xs font-bold rounded-full {{ $isPettyCashActive ? 'bg-white/20 text-white' : '' }}"> |
| 411 | `` | **AKTIF** | <div x-data="{ open: {{ $isLaporanActive ? 'true' : 'false' }} }" class="space-y-1"> |
| 413 | `` | **AKTIF** | class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 {{ $isLaporanActive ? 'bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' }}"> |
| 473 | `` | **AKTIF** | class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ $isActive('admin.nasabah.index') }}"> |
| 489 | `` | **AKTIF** | class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ $isActive('admin.nasabah.pending-changes') }}"> |
| 515 | `` | **AKTIF** | class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ $isActive('admin.master-data') }}"> |
| 528 | `` | **AKTIF** | <div x-data="{ open: {{ $isActivityLogActive ? 'true' : 'false' }} }" class="space-y-1"> |
| 530 | `` | **AKTIF** | class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 {{ $isActivityLogActive ? 'bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' }}"> |

## ðŸ„ `components\nasabah\bottom-navbar.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 3 | `` | **AKTIF** | $isActive = function($route) use ($currentRoute) { |
| 14 | `` | **AKTIF** | $navActive = function($route) use ($currentRoute, $isActive) { |
| 17 | `` | **AKTIF** | return $isActive($route); |
| 47 | `` | **AKTIF** | @php $active = $navActive($item['key']); @endphp |
| 49 | `` | **AKTIF** | class="nav-item group relative z-10 flex flex-col items-center justify-end min-w-[48px] px-1 py-2 rounded-2xl transition-colors duration-200 {{ $active ? 'text-[#8b6f2f]' : 'text-gray-500 hover:text-gray-700' }}" |
| 51 | `` | **AKTIF** | @if($active) data-nav-active="1" @endif> |
| 52 | `` | **AKTIF** | <span class="flex items-center justify-center w-12 h-10 rounded-2xl transition-all duration-300 ease-out {{ $active ? 'scale-110 -translate-y-1.5' : 'group-hover:scale-105 group-hover:-translate-y-0.5' }}"> |
| 53 | `` | **AKTIF** | <svg class="w-6 h-6 transition-transform duration-300 {{ $active ? 'scale-105' : '' }}" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="{{ $item['icon'] }}"></path></svg> |
| 55 | `` | **AKTIF** | <span class="text-xs font-medium mt-1.5 block transition-colors duration-200 {{ $active ? 'text-[#8b6f2f] font-semibold' : '' }}">{{ $item['label'] }}</span> |
| 66 | `` | **AKTIF** | <button type="button" id="burgerMenuBtn" class="flex flex-col items-center gap-0.5 text-[#8b6f2f] focus:outline-none focus:ring-2 focus:ring-[#8b6f2f]/30 rounded-2xl px-2 py-1 transition-transform active:scale-95" aria-expanded="false" aria-label="Menu layanan"> |
| 73 | `` | **AKTIF** | <a href="{{ route('nasabah.setting.index') }}" class="flex flex-col items-center gap-0.5 text-gray-500 hover:text-[#8b6f2f] transition-colors {{ $isActive('setting') ? 'text-[#8b6f2f]' : '' }}"> |
| 87 | `` | **AKTIF** | class="arc-item absolute left-1/2 top-full flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-br from-[#a67c52]/90 to-[#8b6f2f]/95 text-white border-2 border-[#674c1d]/30 shadow-[0_4px_16px_rgba(139,111,47,0.35)] hover:scale-110 hover:shadow-[0_8px_24px_rgba(103,76,29,0.45)] hover:border-[#d4af37]/60 hover:from-[#8b6f2f] hover:to-[#674c1d] hover:ring-2 hover:ring-[#d4af37]/40 active:scale-95 transition-all duration-200 ease-out" |
| 181 | `` | **AKTIF** | var activeLink = navInner.querySelector('[data-nav-active="1"]'); |
| 182 | `` | **AKTIF** | var currentKey = activeLink ? activeLink.getAttribute('data-nav-key') : null; |
| 195 | `` | **AKTIF** | if (activeLink) positionSlider(activeLink); |
| 199 | `` | **AKTIF** | if (activeLink) positionSlider(activeLink); |
| 203 | `` | **AKTIF** | if (activeLink) positionSlider(activeLink); |
| 212 | `` | **Memuat** | if (document.readyState === 'loading') { |

## ðŸ„ `components\nasabah\header.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 55 | `` | **Kirim** | <button type="submit" class="text-xs text-[#674c1d] hover:underline">Tandai semua dibaca</button> |
| 65 | `` | **Kirim** | <button type="submit" class="w-full text-left px-4 py-3 hover:bg-gray-100 transition-colors rounded-none"> |
| 110 | `` | **Kirim** | <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors"> |

## ðŸ„ `components\nasabah\tabungan\filter-tabungan.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 59 | `` | **Kirim** | <button type="submit" class="w-12 h-12 bg-linear-to-br from-[#674c1d] to-[#4a3514] text-white rounded-xl hover:from-[#4a3514] hover:to-[#674c1d] transition-all flex items-center justify-center shadow-md hover:shadow-lg transform hover:scale-105"> |

## ðŸ„ `landing\faq.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 44 | `` | **AKTIF** | .faq-item.active .faq-content { |
| 49 | `` | **AKTIF** | .faq-item.active { |
| 54 | `` | **AKTIF** | .category-btn.active { |
| 157 | `` | **AKTIF** | <button onclick="filterCategory('all')" class="category-btn active px-6 py-3 bg-white border-2 border-gray-200 rounded-xl font-semibold text-gray-700 hover:border-[#674c1d] transition-all" data-category="all"> |
| 203 | `Upload` | **Unggah** | <li class="pl-2">Upload foto KTP - sistem akan otomatis membaca data KTP (OCR)</li> |
| 364 | `` | **Unggah** | <p class="text-sm text-gray-600">Via transfer (upload bukti) atau tunai (janji temu)</p> |
| 375 | `` | **Kirim** | <p class="text-sm text-gray-600">Isi form, lihat simulasi, submit dengan PIN</p> |
| 535 | `` | **Unggah** | <li class="pl-2">Ajukan dengan nominal yang diinginkan + upload foto barang</li> |
| 536 | `` | **Setujui** | <li class="pl-2">Admin menilai barang dan approve</li> |
| 736 | `All rights reserved` | **Hak cipta dilindungi** | <p class="text-white/80 text-sm">Copyright © 2026 Koperasi Majakara. All rights reserved.</p> |
| 750 | `` | **AKTIF** | const isActive = faqItem.classList.contains('active'); |
| 754 | `` | **AKTIF** | item.classList.remove('active'); |
| 759 | `` | **AKTIF** | if (!isActive) { |
| 760 | `` | **AKTIF** | faqItem.classList.add('active'); |
| 772 | `` | **AKTIF** | btn.classList.remove('active'); |
| 774 | `` | **AKTIF** | btn.classList.add('active'); |
| 785 | `` | **AKTIF** | faq.classList.remove('active'); |

## ðŸ„ `landing\keuntungan.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 415 | `Upload` | **Unggah** | <p class="text-gray-700 font-medium">Upload dokumen digital, tidak perlu fotokopi</p> |
| 861 | `All rights reserved` | **Hak cipta dilindungi** | <p class="text-white/80 text-sm">Copyright © 2026 Koperasi Majakara. All rights reserved.</p> |

## ðŸ„ `landing\layanan.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 330 | `Approve` | **Setujui** | <h4 class="font-bold text-gray-900 mb-1">Admin Approve</h4> |
| 374 | `Approve` | **Setujui** | <h4 class="font-bold text-gray-900 mb-1">Review & Approve</h4> |
| 645 | `Upload` | **Unggah** | <h4 class="font-bold text-gray-900 mb-1">Ajukan & Upload Foto</h4> |
| 653 | `` | **Setujui** | <p class="text-gray-600 text-sm">Admin menilai barang dan approve pengajuan</p> |
| 880 | `All rights reserved` | **Hak cipta dilindungi** | Copyright © 2026 Koperasi Majakara. All rights reserved. |

## ðŸ„ `landing\testimoni.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 571 | `All rights reserved` | **Hak cipta dilindungi** | <p class="text-white/80 text-sm">Copyright © 2026 Koperasi Majakara. All rights reserved.</p> |

## ðŸ„ `nasabah\dashboard.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 14 | `ago` | **yang lalu** | <!-- Diagonal lines pattern --> |
| 204 | `` | **Aksi** | <!-- Recent Transactions (List View) --> |

## ðŸ„ `nasabah\guide.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 66 | `` | **Unggah** | Isi nominal & upload bukti |
| 82 | `` | **Unggah** | <span><strong>Transfer:</strong> Isi nominal, upload bukti transfer, lalu cek <strong>Status Pengajuan Setor</strong>. Admin akan memverifikasi bukti dan menyetujui; saldo Anda bertambah.</span> |
| 268 | `Upload` | **Unggah** | <span><strong>Transfer:</strong> Upload bukti transfer pembayaran. Admin verifikasi lalu angsuran tercatat.</span> |
| 356 | `` | **Segera hadir** | {{-- Gadai & Deposito (Coming Soon) --}} |

## ðŸ„ `nasabah\pengajuan-pending.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 54 | `` | **Kirim** | <button type="submit" class="w-full md:w-auto px-6 py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-semibold hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-lg hover:shadow-xl"> |
| 146 | `` | **Segera hadir** | <span class="text-gray-400">Coming Soon</span> |
| 162 | `Kembali ke Dashboard` | **Kembali ke Dasbor** | Kembali ke Dashboard |

## ðŸ„ `nasabah\profile.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 96 | `` | **Kirim** | onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan ini?')"> |
| 99 | `` | **Kirim** | <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium transition-colors"> |
| 416 | `` | **Kirim** | <button type="button" onclick="submitEditForm()" |
| 460 | `` | **Kirim** | <button type="submit" |
| 568 | `` | **Kirim** | function submitEditForm() { |

## ðŸ„ `nasabah\deposito\detail.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 358 | `` | **Kirim** | <button type="submit" |

## ðŸ„ `nasabah\deposito\index.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 67 | `` | **AKTIF** | {{-- ===== TOP SECTION: Active Deposito OR Hero Banner ===== --}} |
| 168 | `` | **AKTIF** | <a href="{{ route('nasabah.deposito.pengajuan') }}" class="inline-flex items-center gap-2 bg-white text-[#674c1d] font-bold px-8 py-3.5 rounded-full shadow-xl hover:shadow-2xl hover:scale-105 active:scale-95 transition-all text-sm mb-6"> |
| 355 | `` | **AKTIF** | class="shimmer-gold w-full block text-center font-bold text-[#3a2800] py-3 rounded-xl text-sm transition-all active:scale-95"> |

## ðŸ„ `nasabah\deposito\pengajuan.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 8 | `` | **AKTIF** | .step-active { background: linear-gradient(135deg, #674c1d, #d4af37); } |
| 10 | `` | **AKTIF** | .step-inactive { background: #e5e7eb; } |
| 50 | `` | **AKTIF** | <div id="step-dot-1" class="step-indicator step-active w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold shadow-md">1</div> |
| 58 | `` | **AKTIF** | <div id="step-dot-2" class="step-indicator step-inactive w-8 h-8 rounded-full flex items-center justify-center text-gray-400 text-xs font-bold">2</div> |
| 66 | `` | **AKTIF** | <div id="step-dot-3" class="step-indicator step-inactive w-8 h-8 rounded-full flex items-center justify-center text-gray-400 text-xs font-bold">3</div> |
| 73 | `` | **Kirim** | <form id="form-pengajuan" method="POST" action="{{ route('nasabah.deposito.submit-pengajuan') }}" enctype="multipart/form-data"> |
| 127 | `` | **AKTIF** | class="w-full bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white font-bold py-4 rounded-xl text-sm shadow-md active:scale-95 transition-all"> |
| 135 | `` | **AKTIF** | <button type="button" onclick="goToStep(1)" id="paket-summary" class="w-full text-left bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] rounded-2xl p-4 mb-4 text-white hover:shadow-md transition-all active:scale-[0.98] border border-[#d4af37]/30"> |
| 231 | `` | **Unggah** | <p class="text-xs text-gray-500">Setorkan dana ke rekening Koperasi Majakara, lalu upload bukti transfer</p> |
| 248 | `Upload` | **Unggah** | {{-- Upload bukti (conditional) --}} |
| 276 | `Upload` | **Unggah** | <label class="text-xs font-semibold text-gray-700 block mb-2">Upload Bukti Transfer *</label> |
| 288 | `` | **AKTIF** | <button type="button" onclick="goToStep(1)" class="flex-1 border-2 border-[#674c1d] text-[#674c1d] font-bold py-4 rounded-xl text-sm active:scale-95 transition-all">← Kembali</button> |
| 289 | `` | **AKTIF** | <button type="button" onclick="goToStep(3)" class="flex-[2] bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white font-bold py-4 rounded-xl text-sm shadow-md active:scale-95 transition-all">Lanjut →</button> |
| 340 | `` | **AKTIF** | <button type="button" onclick="goToStep(2)" class="flex-1 border-2 border-[#674c1d] text-[#674c1d] font-bold py-4 rounded-xl text-sm active:scale-95 transition-all">← Kembali</button> |
| 341 | `` | **AKTIF** | <button type="submit" class="flex-[2] bg-gradient-to-r from-[#674c1d] to-[#d4af37] text-white font-bold py-4 rounded-xl text-sm shadow-lg active:scale-95 transition-all"> |
| 439 | `` | **AKTIF** | else if (i === step) { dot.className = 'step-indicator step-active w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold shadow-md'; dot.textContent = i; } |
| 440 | `` | **AKTIF** | else { dot.className = 'step-indicator step-inactive w-8 h-8 rounded-full flex items-center justify-center text-gray-400 text-xs font-bold'; dot.textContent = i; } |

## ðŸ„ `nasabah\deposito\riwayat.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 55 | `` | **Tolak** | $linkTujuan = route('nasabah.deposito.status-pengajuan', $item->id); // to see rejection note |

## ðŸ„ `nasabah\deposito\status-pengajuan.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 31 | `` | **AKTIF** | 'steps'    => ['done' => 1, 'active' => 2], |
| 40 | `` | **AKTIF** | 'steps'    => ['done' => 3, 'active' => 0], |
| 49 | `` | **AKTIF** | 'steps'    => ['done' => 1, 'active' => 0], |
| 77 | `` | **AKTIF** | ['label' => 'Dalam Review Admin', 'desc' => $status === '1' ? 'Menunggu persetujuan...' : ($status === '3' ? 'Ditolak' : 'Selesai ditinjau'), 'done' => in_array($status, ['2', '3']), 'active' => $status === '1'], |
| 78 | `` | **AKTIF** | ['label' => 'Deposito Aktif', 'desc' => $status === '2' ? 'Nomor deposito diterbitkan' : 'Menunggu persetujuan', 'done' => $status === '2', 'active' => false, 'skip' => $status === '3'], |
| 87 | `` | **AKTIF** | {{ $step['done'] ? 'bg-green-500' : (($step['active'] ?? false) ? 'bg-gradient-to-br from-[#674c1d] to-[#d4af37]' : 'bg-gray-200') }} |
| 93 | `` | **AKTIF** | @elseif($step['active'] ?? false) |
| 100 | `` | **AKTIF** | <p class="font-semibold text-sm {{ $step['done'] ? 'text-gray-800' : (($step['active'] ?? false) ? 'text-[#674c1d]' : 'text-gray-400') }}"> |
| 103 | `` | **AKTIF** | <p class="text-xs {{ $step['done'] \|\| ($step['active'] ?? false) ? 'text-gray-500' : 'text-gray-300' }} mt-0.5"> |
| 158 | `` | **AKTIF** | <a href="{{ route('nasabah.deposito.pengajuan') }}" class="flex items-center justify-center gap-2 w-full bg-gradient-to-r from-[#674c1d] to-[#d4af37] text-white font-bold py-4 rounded-xl text-sm shadow-md active:scale-95 transition-all"> |
| 165 | `` | **AKTIF** | <a href="{{ route('nasabah.deposito.index') }}" class="flex items-center justify-center gap-2 w-full border-2 border-[#674c1d] text-[#674c1d] font-bold py-4 rounded-xl text-sm active:scale-95 transition-all"> |

## ðŸ„ `nasabah\gadai_baru\aktif_detail.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 76 | `` | **AKTIF** | {{-- CTA inside hero (active & tenggang) --}} |
| 77 | `` | **AKTIF** | @if(in_array($gadai->status, ['active', 'grace_period'])) |
| 80 | `` | **AKTIF** | class="flex-1 flex items-center justify-center gap-2 py-3.5 bg-white text-emerald-700 font-black rounded-2xl text-xs uppercase tracking-widest shadow-xl active:scale-95 transition-all hover:bg-emerald-50"> |
| 86 | `` | **AKTIF** | class="flex-1 flex items-center justify-center gap-2 py-3.5 bg-white/20 hover:bg-white/30 text-white font-black rounded-2xl text-xs uppercase tracking-widest border border-white/30 active:scale-95 transition-all backdrop-blur-sm"> |

## ðŸ„ `nasabah\gadai_baru\index.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 144 | `` | **AKTIF** | class="flex items-center justify-center gap-2 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-black rounded-2xl text-xs uppercase tracking-widest shadow-md shadow-emerald-600/20 active:scale-95 transition-all"> |
| 150 | `` | **AKTIF** | class="flex items-center justify-center gap-2 py-3.5 bg-amber-500 hover:bg-amber-600 text-white font-black rounded-2xl text-xs uppercase tracking-widest shadow-md shadow-amber-500/20 active:scale-95 transition-all"> |
| 187 | `` | **AKTIF** | <p class="text-xs font-bold text-gray-900 leading-tight">{{ $pengajuan->gadaiActive->item->nama_item ?? 'Gadai Item' }}</p> |
| 193 | `` | **Setujui** | <span class="text-[9px] font-black uppercase {{ $pengajuan->status == 'approved' ? 'text-emerald-600' : ($pengajuan->status == 'pending' ? 'text-amber-600' : 'text-red-600') }}">{{ $pengajuan->status }}</span> |
| 256 | `` | **AKTIF** | <span class="text-[9px] text-gray-400 font-bold">• {{ $cfg['var']->items->where('is_active', true)->count() }} item</span> |
| 258 | `` | **AKTIF** | @foreach($cfg['var']->items->where('is_active', true) as $item) |

## ðŸ„ `nasabah\gadai_baru\pengajuan.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 110 | `Upload` | **Unggah** | <span class="text-[10px] text-gray-500 text-center mt-1">Upload Bukti Transfer</span> |
| 146 | `Upload` | **Unggah** | <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Bukti Transfer *</label> |
| 156 | `` | **Unggah** | <p class="text-[10px] text-gray-500 mt-2">Minimal upload 1 bukti transfer. Anda bisa upload beberapa bukti jika diperlukan.</p> |
| 166 | `Submit` | **Kirim** | <button type="button" onclick="showPinModal()" id="btnSubmit" class="w-full bg-[#674c1d] hover:bg-[#543e18] text-white font-bold py-4 rounded-2xl transition-all shadow-lg shadow-amber-200 flex items-center justify-center gap-2"> |
| 263 | `Submit` | **Kirim** | function verifyAndSubmit() { |
| 275 | `Submit` | **Kirim** | const btnSubmit = document.getElementById('btnSubmit'); |
| 276 | `Submit` | **Kirim** | btnSubmit.disabled = true; |
| 277 | `Submit` | **Kirim** | btnSubmit.innerHTML = 'Mengolah...'; |
| 279 | `` | **Kirim** | document.getElementById('form-pengajuan').submit(); |
| 322 | `Submit` | **Kirim** | <button type="button" onclick="verifyAndSubmit()" |

## ðŸ„ `nasabah\gadai_baru\riwayat.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 7 | `` | **AKTIF** | <!-- Active Gadai Section --> |
| 99 | `` | **AKTIF** | <a href="{{ route('nasabah.gadai_baru.aktif-detail', $gadai->id) }}" class="flex-1 text-center bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white font-black py-3 px-4 rounded-2xl transition-all shadow-xl shadow-amber-100 active:scale-95 text-xs">Lihat Rincian &amp; Kelola</a> |
| 100 | `` | **AKTIF** | <a href="https://wa.me/628139552626?text=Halo%20Admin,%20saya%20ingin%20info%20gadai%20{{$gadai->slot_kode}}" target="_blank" class="w-12 h-12 flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-2xl transition-all active:scale-95"> |
| 148 | `` | **Dilelang** | 'auctioned' => 'bg-amber-100 text-amber-700', |
| 154 | `` | **Dilelang** | 'auctioned' => 'LELANG', |

## ðŸ„ `nasabah\gadai_baru\show.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 89 | `` | **Kirim** | <button type="button" onclick="submitWA()" |
| 104 | `` | **Kirim** | function submitWA() { |

## ðŸ„ `nasabah\gadai_baru\status_pengajuan.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 16 | `Kembali ke Dashboard` | **Kembali ke Dasbor** | <a href="{{ route('nasabah.gadai_baru.index') }}" class="inline-block mt-6 text-[#674c1d] font-bold hover:underline">Kembali ke Dashboard</a> |
| 22 | `` | **Setujui** | <div class="flex items-center justify-between p-4 {{ $item->status == 'pending' ? 'bg-amber-50' : ($item->status == 'approved' ? 'bg-emerald-50' : 'bg-red-50') }}"> |
| 24 | `` | **Setujui** | <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $item->status == 'pending' ? 'bg-amber-100 text-amber-600' : ($item->status == 'approved' ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600') }}"> |
| 33 | `` | **AKTIF** | <p class="font-bold text-gray-800">{{ $item->gadaiActive->item->nama_item }}</p> |
| 37 | `` | **Setujui** | <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $item->status == 'pending' ? 'bg-amber-200 text-amber-800' : ($item->status == 'approved' ? 'bg-emerald-200 text-emerald-800' : 'bg-red-200 text-red-800') }}"> |

## ðŸ„ `nasabah\guide\pinjaman-pembayaran.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 47 | `` | **Unggah** | <p class="text-sm text-gray-600 mt-0.5">Angsuran: daftar angsuran per pinjaman. Pembayaran: halaman untuk bayar angsuran (upload bukti transfer atau buat janji temu bayar tunai).</p> |
| 54 | `` | **Unggah** | <p class="text-sm text-gray-600 mt-0.5">Pilih pinjaman & angsuran yang akan dibayar, pilih metode (Transfer atau Janji Temu), upload bukti atau jadwalkan janji. Pantau di <strong>Status Pembayaran</strong>.</p> |
| 69 | `` | **Memuat** | loading="lazy" |
| 84 | `` | **Kirim** | <li>• Pantau <a href="{{ route('nasabah.pinjaman.status-pembayaran') }}" class="underline font-medium">Status Pembayaran</a> setelah submit.</li> |

## ðŸ„ `nasabah\guide\pinjaman-pengajuan.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 55 | `` | **Kirim** | <p class="text-sm text-gray-600 mt-0.5">Halaman pengajuan: isi nominal, pilih durasi (tenor), pilih metode pencairan (Transfer atau Janji Temu). Anda bisa gunakan <strong>Simulasi Angsuran</strong> di halaman itu untuk melihat estimasi sebelum submit.</p> |
| 167 | `` | **Memuat** | loading="lazy" |

## ðŸ„ `nasabah\guide\tabungan-penarikan.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 69 | `` | **Memuat** | loading="lazy" |

## ðŸ„ `nasabah\guide\tabungan-setoran.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 59 | `` | **Kirim** | <p class="text-sm text-gray-600 mt-0.5">Anda akan masuk ke halaman <strong>Nabung Sekarang</strong>: pilih setoran via Transfer atau Tunai (Janji Temu), isi nominal, upload bukti (untuk transfer), lalu submit.</p> |
| 75 | `` | **Memuat** | loading="lazy" |

## ðŸ„ `nasabah\notifications\index.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 18 | `` | **Kirim** | <button type="submit" class="px-4 py-2 bg-[#674c1d] text-white rounded-xl hover:bg-[#4a3514] transition-colors text-sm font-medium"> |
| 42 | `` | **Kirim** | <button type="submit" class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-medium">Filter</button> |
| 53 | `` | **Kirim** | <button type="submit" class="w-full text-left px-6 py-4 hover:bg-gray-100 transition-colors flex items-start gap-4"> |

## ðŸ„ `nasabah\pinjaman\angsuran.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 67 | `` | **Kirim** | <button type="submit" class="w-full md:w-auto bg-linear-to-r from-[#8b6f2f] to-[#a0824d] text-white font-semibold px-6 py-2 rounded-xl hover:shadow-lg transition-all"> |

## ðŸ„ `nasabah\pinjaman\janji-temu.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 76 | `` | **Kirim** | <form method="POST" action="{{ route('nasabah.pinjaman.submit-janji-temu') }}" class="space-y-6" id="form-janji-temu"> |
| 197 | `Submit` | **Kirim** | <!-- Submit Button --> |
| 249 | `Submit` | **Kirim** | <button onclick="verifyAndSubmit()" class="flex-1 px-4 py-3 bg-linear-to-r from-[#8b6f2f] to-[#a0824d] text-white rounded-xl font-semibold hover:from-[#6b5423] hover:to-[#8b6f2f] transition-all"> |
| 338 | `Submit` | **Kirim** | function verifyAndSubmit() { |
| 355 | `` | **Kirim** | form.submit(); |

## ðŸ„ `nasabah\pinjaman\pembayaran.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 76 | `` | **Kirim** | onchange="this.form.submit()" required> |
| 97 | `` | **Kirim** | onchange="this.form.submit()" required> |
| 171 | `` | **Kirim** | <form action="{{ route('nasabah.pinjaman.submit-pembayaran-transfer') }}" method="POST" |
| 208 | `Upload` | **Unggah** | <!-- Upload Bukti Transfer --> |
| 210 | `Upload` | **Unggah** | <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Bukti Transfer <span class="text-red-500">*</span></label> |
| 250 | `` | **Kirim** | <form action="{{ route('nasabah.pinjaman.submit-janji-temu-pembayaran') }}" method="POST" |
| 350 | `Submit` | **Kirim** | <button onclick="verifyAndSubmit()" |
| 415 | `` | **AKTIF** | function setActiveTab(activeId) { |
| 418 | `` | **AKTIF** | const isActive = tab.id === activeId; |
| 419 | `` | **AKTIF** | tab.setAttribute('aria-selected', isActive ? 'true' : 'false'); |
| 420 | `` | **AKTIF** | if (isActive) { |
| 433 | `` | **AKTIF** | setActiveTab('tab-transfer'); |
| 440 | `` | **AKTIF** | setActiveTab('tab-cash'); |
| 523 | `Submit` | **Kirim** | function verifyAndSubmit() { |
| 535 | `` | **Kirim** | document.getElementById('form-transfer').submit(); |
| 539 | `` | **Kirim** | document.getElementById('form-cash').submit(); |

## ðŸ„ `nasabah\pinjaman\pengajuan-pinjaman.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 137 | `` | **Kirim** | <form id="form-transfer" method="POST" action="{{ route('nasabah.pinjaman.submit-pengajuan-transfer') }}" class="space-y-6"> |
| 231 | `` | **Kirim** | <form id="form-tunai" method="POST" action="{{ route('nasabah.pinjaman.submit-janji-temu') }}" class="space-y-6"> |
| 427 | `` | **Kirim** | <button type="button" onclick="submitFormTransfer()" class="w-full py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-bold hover:shadow-lg transition-all">Konfirmasi</button> |
| 459 | `` | **Kirim** | <button type="button" onclick="submitFormTunai()" class="w-full py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-bold hover:shadow-lg transition-all">Konfirmasi</button> |
| 604 | `` | **Kirim** | function submitFormTransfer() { |
| 628 | `` | **Kirim** | document.getElementById('form-transfer').submit(); |
| 655 | `` | **Kirim** | function submitFormTunai() { |
| 679 | `` | **Kirim** | document.getElementById('form-tunai').submit(); |

## ðŸ„ `nasabah\pinjaman\pengajuan-transfer.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 76 | `` | **Kirim** | <form action="{{ route('nasabah.pinjaman.submit-pengajuan-transfer') }}" method="POST" id="formPengajuan"> |
| 175 | `Submit` | **Kirim** | <!-- Submit Button --> |
| 176 | `Submit` | **Kirim** | <button type="button" id="btnSubmitPengajuan" |
| 216 | `` | **Kirim** | <button type="submit" id="verifyPinButton" |
| 219 | `Loading` | **Memuat** | <span id="verifyPinButtonLoading" class="hidden">Memverifikasi...</span> |
| 232 | `Submit` | **Kirim** | const btnSubmitPengajuan = document.getElementById('btnSubmitPengajuan'); |
| 240 | `Loading` | **Memuat** | const verifyPinButtonLoading = document.getElementById('verifyPinButtonLoading'); |
| 338 | `Submit` | **Kirim** | btnSubmitPengajuan.addEventListener('click', function(e) { |
| 351 | `` | **Kirim** | pinForm.addEventListener('submit', function(e) { |
| 363 | `Loading` | **Memuat** | verifyPinButtonLoading.classList.remove('hidden'); |
| 389 | `` | **Kirim** | formPengajuan.submit(); |
| 395 | `Loading` | **Memuat** | verifyPinButtonLoading.classList.add('hidden'); |
| 406 | `Loading` | **Memuat** | verifyPinButtonLoading.classList.add('hidden'); |
| 417 | `Loading` | **Memuat** | verifyPinButtonLoading.classList.add('hidden'); |

## ðŸ„ `nasabah\pinjaman\status-pembayaran.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 47 | `` | **Kirim** | <button type="submit" class="px-6 py-2 bg-[#8b6f2f] text-white rounded-lg hover:bg-[#a0824d] transition-colors"> |

## ðŸ„ `nasabah\pinjaman\status-pengajuan.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 44 | `` | **Setujui** | <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option> |
| 56 | `` | **Kirim** | <button type="submit" class="w-full bg-linear-to-r from-[#8b6f2f] to-[#a0824d] text-white font-semibold py-2 rounded-xl hover:shadow-lg transition-all"> |

## ðŸ„ `nasabah\setting\index.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 152 | `` | **Kirim** | <button type="submit" class="w-full py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-bold shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.01]"> |
| 240 | `` | **Kirim** | <button type="button" onclick="submitResetPassword()" class="flex-1 py-3 bg-linear-to-r from-red-600 to-red-700 text-white rounded-xl font-bold shadow-lg hover:shadow-xl transition-all"> |
| 290 | `` | **Kirim** | <button type="submit" class="w-full py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-bold shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.01]"> |
| 393 | `` | **Kirim** | async function submitResetPassword() { |

## ðŸ„ `nasabah\tabungan\detail-janji-temu.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 118 | `` | **Memuat** | <iframe src="https://www.google.com/maps/embed?pb=!4v1771057242792!6m8!1m7!1sTDnmeXtVvimBtQeXmqSSCQ!2m2!1d-6.267415399913648!2d106.9806162945405!3f247.41483905689947!4f-35.52001210835799!5f0.7820865974627469" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Peta Lokasi Janji Temu"></iframe> |

## ðŸ„ `nasabah\tabungan\detail-transaksi.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 137 | `` | **Unggah** | <p class="text-sm text-gray-600 mb-4">Bukti transaksi yang di-upload oleh admin</p> |
| 145 | `Upload` | **Unggah** | <p class="text-xs text-gray-500 mb-2">Upload: {{ $bukti->created_at->format('d M Y, H:i') }}</p> |
| 163 | `Upload` | **Unggah** | <p class="text-xs text-gray-500 mb-2">Upload: {{ $bukti->created_at->format('d M Y, H:i') }}</p> |

## ðŸ„ `nasabah\tabungan\form-setoran.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 33 | `Upload` | **Unggah** | <span>Upload bukti transfer</span> |
| 67 | `` | **Kirim** | <form id="form-transfer" method="POST" action="{{ route('nasabah.tabungan.submit-setoran') }}" enctype="multipart/form-data" class="space-y-6"> |
| 83 | `Upload` | **Unggah** | <!-- Upload Bukti Transfer --> |
| 85 | `Upload` | **Unggah** | <label class="block text-sm font-semibold text-gray-700 mb-3">Upload Bukti Transfer *</label> |
| 94 | `Upload` | **Unggah** | <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG, JPEG (Max 5MB per file). Upload minimal 1 bukti transfer.</p> |
| 104 | `Submit` | **Kirim** | <!-- Submit Button --> |
| 119 | `` | **Kirim** | <form id="form-tunai" method="POST" action="{{ route('nasabah.tabungan.submit-janji-temu') }}" class="space-y-6"> |
| 179 | `Submit` | **Kirim** | <!-- Submit Button --> |

## ðŸ„ `nasabah\tabungan\index.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 29 | `` | **AKTIF** | .tab-btn.active { |
| 34 | `` | **AKTIF** | .tab-btn.active::after { |
| 47 | `` | **AKTIF** | .tab-pane.active { |
| 113 | `Actions` | **Aksi** | {{-- Quick Actions: Nabung & Tarik --}} |
| 116 | `` | **AKTIF** | class="flex items-center gap-3 bg-white/15 backdrop-blur-sm hover:bg-white/25 border border-white/25 rounded-2xl px-4 py-3 transition-all active:scale-95"> |
| 128 | `` | **AKTIF** | class="flex items-center gap-3 bg-white/15 backdrop-blur-sm hover:bg-white/25 border border-white/25 rounded-2xl px-4 py-3 transition-all active:scale-95"> |
| 151 | `` | **AKTIF** | <button type="button" class="tab-btn active" onclick="switchTab('trans', this)" id="tab-btn-trans"> |
| 196 | `` | **AKTIF** | <div id="pane-trans" class="tab-pane active" data-container="trans-container"> |
| 294 | `` | **AKTIF** | document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active')); |
| 295 | `` | **AKTIF** | document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active')); |
| 298 | `` | **AKTIF** | document.getElementById('pane-' + tabKey)?.classList.add('active'); |
| 299 | `` | **AKTIF** | btnEl.classList.add('active'); |

## ðŸ„ `nasabah\tabungan\janji-temu.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 67 | `` | **Kirim** | <form method="POST" action="{{ route('nasabah.tabungan.submit-janji-temu') }}" class="space-y-6" id="form-janji-temu"> |
| 130 | `Submit` | **Kirim** | <!-- Submit Button --> |
| 175 | `Submit` | **Kirim** | oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length === 6) verifyAndSubmit();"> |
| 182 | `Submit` | **Kirim** | <button onclick="verifyAndSubmit()" id="btn-verify-submit" class="flex-1 px-4 py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-semibold hover:from-[#4a3514] hover:to-[#674c1d] transition-all"> |
| 235 | `Submit` | **Kirim** | let isSubmitting = false; |
| 236 | `Submit` | **Kirim** | function verifyAndSubmit() { |
| 237 | `Submit` | **Kirim** | if (isSubmitting) return; |
| 245 | `Submit` | **Kirim** | isSubmitting = true; |
| 252 | `` | **Memproses** | nominalInput.value = nominalRaw; // Set as raw number for server processing |
| 262 | `` | **Kirim** | const submitBtn = document.getElementById('btn-verify-submit'); |
| 263 | `` | **Kirim** | submitBtn.disabled = true; |
| 264 | `` | **Kirim** | submitBtn.innerHTML = ` |
| 271 | `` | **Kirim** | form.submit(); |
| 297 | `Submit` | **Kirim** | verifyAndSubmit(); |

## ðŸ„ `nasabah\tabungan\modals-pin.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 26 | `` | **Kirim** | oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length === 6) submitFormTransfer();"> |
| 30 | `` | **AKTIF** | <button onclick="submitFormTransfer()" class="w-full py-4 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-2xl font-bold text-lg shadow-xl shadow-[#674c1d]/20 hover:shadow-2xl hover:scale-[1.01] active:scale-95 transition-all"> |
| 61 | `` | **Kirim** | oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length === 6) submitFormTunai();"> |
| 65 | `` | **AKTIF** | <button onclick="submitFormTunai()" class="w-full py-4 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-2xl font-bold text-lg shadow-xl shadow-[#674c1d]/20 hover:shadow-2xl hover:scale-[1.01] active:scale-95 transition-all"> |

## ðŸ„ `nasabah\tabungan\nabung-sekarang.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 114 | `` | **Unggah** | <p class="text-sm text-gray-600">Input nominal & upload bukti, lalu masukkan PIN Anda.</p> |
| 237 | `` | **AKTIF** | class="group relative inline-flex items-center gap-3 px-10 py-4 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-2xl font-bold text-lg shadow-xl hover:shadow-2xl hover:scale-[1.02] transform transition-all active:scale-95"> |
| 386 | `` | **Kirim** | async function submitFormTransfer() { |
| 389 | `` | **Kirim** | const submitBtn = document.querySelector('#pin-modal-transfer button[onclick="submitFormTransfer()"]'); |
| 394 | `` | **Kirim** | submitBtn.disabled = true; |
| 395 | `` | **Kirim** | const originalText = submitBtn.innerHTML; |
| 396 | `` | **Kirim** | submitBtn.innerHTML = `<svg class="animate-spin h-5 w-5 text-white mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`; |
| 406 | `` | **Kirim** | document.getElementById('form-transfer').submit(); |
| 410 | `` | **Kirim** | submitBtn.disabled = false; |
| 411 | `` | **Kirim** | submitBtn.innerHTML = originalText; |
| 414 | `` | **Kirim** | submitBtn.disabled = false; |
| 415 | `` | **Kirim** | submitBtn.innerHTML = originalText; |
| 419 | `` | **Kirim** | async function submitFormTunai() { |
| 422 | `` | **Kirim** | const submitBtn = document.querySelector('#pin-modal-tunai button[onclick="submitFormTunai()"]'); |
| 427 | `` | **Kirim** | submitBtn.disabled = true; |
| 428 | `` | **Kirim** | const originalText = submitBtn.innerHTML; |
| 429 | `` | **Kirim** | submitBtn.innerHTML = `<svg class="animate-spin h-5 w-5 text-white mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`; |
| 439 | `` | **Kirim** | document.getElementById('form-tunai').submit(); |
| 443 | `` | **Kirim** | submitBtn.disabled = false; |
| 444 | `` | **Kirim** | submitBtn.innerHTML = originalText; |
| 447 | `` | **Kirim** | submitBtn.disabled = false; |
| 448 | `` | **Kirim** | submitBtn.innerHTML = originalText; |

## ðŸ„ `nasabah\tabungan\penarikan-tabungan.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 99 | `` | **Kirim** | <form id="form-penarikan" method="POST" action="{{ route('nasabah.tabungan.submit-penarikan') }}" class="space-y-6"> |
| 204 | `Submit` | **Kirim** | <!-- Submit Button --> |
| 206 | `` | **Kirim** | <button type="button" onclick="showPinModal()" id="submit-btn" class="w-full py-4 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed"> |
| 297 | `Submit` | **Kirim** | oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length === 6) verifyAndSubmit();"> |
| 304 | `Submit` | **Kirim** | <button onclick="verifyAndSubmit()" id="btn-verify-submit" class="flex-1 px-4 py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-semibold hover:from-[#4a3514] hover:to-[#674c1d] transition-all"> |
| 385 | `` | **Kirim** | const submitBtn = document.getElementById('submit-btn'); |
| 389 | `` | **Kirim** | submitBtn.disabled = true; |
| 392 | `` | **Kirim** | submitBtn.disabled = false; |
| 450 | `Submit` | **Kirim** | let isSubmitting = false; |
| 451 | `Submit` | **Kirim** | function verifyAndSubmit() { |
| 452 | `Submit` | **Kirim** | if (isSubmitting) return; |
| 454 | `` | **Kirim** | console.log('=== VERIFY AND SUBMIT CALLED ==='); |
| 461 | `Submit` | **Kirim** | isSubmitting = true; |
| 478 | `Submit` | **Kirim** | console.log('Submitting form...'); |
| 481 | `` | **Kirim** | const verifyBtn = document.getElementById('btn-verify-submit'); |
| 490 | `` | **Kirim** | form.submit(); |
| 505 | `Submit` | **Kirim** | if (e.key === 'Enter') verifyAndSubmit(); |

## ðŸ„ `nasabah\tabungan\pengajuan-transfer.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 18 | `Upload` | **Unggah** | <p class="text-white/90 text-sm">Upload bukti transfer untuk pengajuan setoran</p> |
| 51 | `` | **Kirim** | <form id="form-transfer" method="POST" action="{{ route('nasabah.tabungan.submit-setoran') }}" enctype="multipart/form-data" class="space-y-6"> |
| 68 | `Upload` | **Unggah** | <!-- Upload Bukti Transfer --> |
| 70 | `Upload` | **Unggah** | <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Bukti Transfer *</label> |
| 80 | `` | **Unggah** | <p class="text-xs text-gray-500 mt-2">Minimal upload 1 bukti transfer. Anda bisa upload beberapa bukti jika melakukan transfer bertahap.</p> |
| 90 | `Submit` | **Kirim** | <!-- Submit Button --> |
| 142 | `Submit` | **Kirim** | <button onclick="verifyAndSubmit()" class="flex-1 px-4 py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-semibold hover:from-[#4a3514] hover:to-[#674c1d] transition-all"> |
| 207 | `` | **Unggah** | alert('Minimal upload 1 bukti transfer'); |
| 224 | `Submit` | **Kirim** | function verifyAndSubmit() { |
| 248 | `` | **Kirim** | form.submit(); |

## ðŸ„ `nasabah\tabungan\status-janji-temu.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 280 | `` | **Kirim** | <button type="submit" id="submit-btn" disabled class="flex-1 py-4 bg-rose-500 text-white rounded-2xl font-bold shadow-lg shadow-rose-200 opacity-50 cursor-not-allowed transition-all hover:bg-rose-600"> |
| 295 | `` | **Kirim** | const submitBtn = document.getElementById('submit-btn'); |
| 313 | `Submit` | **Kirim** | updateSubmitButton(); |
| 328 | `Submit` | **Kirim** | function updateSubmitButton() { |
| 333 | `` | **Kirim** | submitBtn.disabled = false; |
| 334 | `` | **Kirim** | submitBtn.classList.remove('opacity-50', 'cursor-not-allowed'); |
| 336 | `` | **Kirim** | submitBtn.disabled = true; |
| 337 | `` | **Kirim** | submitBtn.classList.add('opacity-50', 'cursor-not-allowed'); |
| 341 | `Submit` | **Kirim** | confirmInput.addEventListener('input', updateSubmitButton); |
| 344 | `Submit` | **Kirim** | updateSubmitButton(); |
| 347 | `` | **Kirim** | cancelForm.addEventListener('submit', function(e) { |
| 348 | `` | **Kirim** | submitBtn.disabled = true; |
| 349 | `` | **Kirim** | submitBtn.innerHTML = ` |

## ðŸ„ `nasabah\tabungan\status-pengajuan-setor.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 33 | `` | **Kirim** | <!-- Notifikasi sukses / error setelah submit --> |
| 270 | `` | **Kirim** | <button type="submit" id="submit-btn" disabled class="flex-1 py-4 bg-rose-500 text-white rounded-2xl font-bold shadow-lg shadow-rose-200 opacity-50 cursor-not-allowed transition-all hover:bg-rose-600"> |
| 285 | `` | **Kirim** | const submitBtn = document.getElementById('submit-btn'); |
| 303 | `Submit` | **Kirim** | updateSubmitButton(); |
| 318 | `Submit` | **Kirim** | function updateSubmitButton() { |
| 323 | `` | **Kirim** | submitBtn.disabled = false; |
| 324 | `` | **Kirim** | submitBtn.classList.remove('opacity-50', 'cursor-not-allowed'); |
| 326 | `` | **Kirim** | submitBtn.disabled = true; |
| 327 | `` | **Kirim** | submitBtn.classList.add('opacity-50', 'cursor-not-allowed'); |
| 331 | `Submit` | **Kirim** | confirmInput.addEventListener('input', updateSubmitButton); |
| 334 | `Submit` | **Kirim** | updateSubmitButton(); |
| 337 | `` | **Kirim** | cancelForm.addEventListener('submit', function(e) { |
| 338 | `` | **Kirim** | submitBtn.disabled = true; |
| 339 | `` | **Kirim** | submitBtn.innerHTML = ` |

## ðŸ„ `nasabah\tabungan\status-pengajuan-tarik.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 33 | `` | **Kirim** | <!-- Notifikasi sukses / error setelah submit --> |
| 297 | `` | **Kirim** | <button type="submit" id="submit-btn" disabled class="flex-1 py-4 bg-rose-500 text-white rounded-2xl font-bold shadow-lg shadow-rose-200 opacity-50 cursor-not-allowed transition-all hover:bg-rose-600"> |
| 312 | `` | **Kirim** | const submitBtn = document.getElementById('submit-btn'); |
| 330 | `Submit` | **Kirim** | updateSubmitButton(); |
| 345 | `Submit` | **Kirim** | function updateSubmitButton() { |
| 350 | `` | **Kirim** | submitBtn.disabled = false; |
| 351 | `` | **Kirim** | submitBtn.classList.remove('opacity-50', 'cursor-not-allowed'); |
| 353 | `` | **Kirim** | submitBtn.disabled = true; |
| 354 | `` | **Kirim** | submitBtn.classList.add('opacity-50', 'cursor-not-allowed'); |
| 358 | `Submit` | **Kirim** | confirmInput.addEventListener('input', updateSubmitButton); |
| 361 | `Submit` | **Kirim** | updateSubmitButton(); |
| 364 | `` | **Kirim** | cancelForm.addEventListener('submit', function(e) { |
| 365 | `` | **Kirim** | submitBtn.disabled = true; |
| 366 | `` | **Kirim** | submitBtn.innerHTML = ` |

## ðŸ„ `struk\tabungan-html.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 15 | `` | **Cetak** | font-family: Arial, Helvetica, sans-serif; /* Lebih terbaca di printer kasir */ |
| 57 | `` | **Cetak** | filter: grayscale(100%) contrast(1000%); /* Bantu hitam-putih untuk printer */ |
| 98 | `` | **Cetak** | @media print { |
| 205 | `` | **Setujui** | $approver = $pengajuanSetor && $pengajuanSetor->relationLoaded('approvedBy') ? $pengajuanSetor->approvedBy : null; |
| 206 | `` | **Setujui** | $petugasName = $approver ? $approver->nama : ($transaksi->adminPengelola ? $transaksi->adminPengelola->nama : 'Admin'); |
| 223 | `` | **Cetak** | window.print(); |
| 228 | `` | **Cetak** | window.onafterprint = function() { |

## ðŸ„ `struk\tabungan.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 22 | `` | **Setujui** | .approver { margin-top: 8px; padding: 6px 8px; background: #f5f5f5; border-radius: 4px; font-size: 9px; } |
| 23 | `` | **Setujui** | .approver-label { color: #555; } |
| 24 | `` | **Setujui** | .approver-value { font-weight: 600; } |
| 60 | `` | **Setujui** | $approver = $pengajuanSetor && $pengajuanSetor->relationLoaded('approvedBy') ? $pengajuanSetor->approvedBy : null; |
| 61 | `` | **Setujui** | $roleLabel = $approver ? (($approver->role === 'admin_utama' ? 'Admin Utama' : ($approver->role === 'admin_operasional' ? 'Admin Operasional' : 'Admin'))) : null; |
| 63 | `` | **Setujui** | @if($approver && $roleLabel) |
| 64 | `` | **Setujui** | <div class="approver"> |
| 65 | `` | **Setujui** | <span class="approver-label">Disetujui oleh</span><br /> |
| 66 | `` | **Setujui** | <span class="approver-value">{{ $roleLabel }} – {{ $approver->nama ?? 'N/A' }}</span> |

## ðŸ„ `vendor\pagination\bootstrap-4.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 26 | `` | **AKTIF** | <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li> |

## ðŸ„ `vendor\pagination\bootstrap-5.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 66 | `` | **AKTIF** | <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li> |

## ðŸ„ `vendor\pagination\default.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 26 | `` | **AKTIF** | <li class="active" aria-current="page"><span>{{ $page }}</span></li> |

## ðŸ„ `vendor\pagination\semantic-ui.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 21 | `` | **AKTIF** | <a class="item active" href="{{ $url }}" aria-current="page">{{ $page }}</a> |

## ðŸ„ `vendor\pagination\simple-tailwind.blade.php`

| Baris | Teks Inggris | Terjemahan | Isi Baris |
|-------|--------------|------------|-----------|
| 9 | `` | **AKTIF** | <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:focus:border-blue-700 dark:active:bg-gray-700 dark:active:text-gray-300"> |
| 16 | `` | **AKTIF** | <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:focus:border-blue-700 dark:active:bg-gray-700 dark:active:text-gray-300"> |

---

## Ringkasan per Folder

- **admin/**: 112 file
- **auth/**: 2 file
- **components/**: 5 file
- **landing/**: 4 file
- **nasabah/**: 40 file
- **struk/**: 2 file
- **vendor/**: 5 file
- **welcome.blade.php/**: 1 file
