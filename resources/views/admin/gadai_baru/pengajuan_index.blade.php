@extends('layouts.admin')

@section('title', 'Verifikasi Pengajuan Gadai')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 font-display">Verifikasi Pengajuan Gadai</h1>
            <p class="text-gray-500 text-sm">Daftar antrean pengajuan pelunasan dan perpanjangan gadai dari nasabah.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 bg-amber-100 text-[#674c1d] text-xs font-bold rounded-full border border-amber-200 shadow-sm">
                {{ $pengajuan->count() }} Antrean Menunggu
            </span>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Nasabah & Item</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Jenis & Nominal</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Metode & Bukti</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pengajuan as $item)
                    <tr class="hover:bg-gray-50/30 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-linear-to-br from-[#674c1d] to-[#8b6f2f] flex items-center justify-center text-white font-bold shadow-sm">
                                    {{ substr($item->nasabah->user->nama ?? 'N', 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900 leading-none mb-1">{{ $item->nasabah->user->nama }}</div>
                                    <div class="text-[11px] text-gray-500">{{ $item->gadaiActive->item->nama_item }} • <span class="font-mono bg-gray-100 px-1 rounded">{{ $item->gadaiActive->slot_kode }}/{{ $item->gadaiActive->slot_table }}</span></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="mb-1">
                                <span class="px-2 py-0.5 rounded-lg text-[9px] font-black uppercase {{ $item->jenis_pengajuan == 'lunas' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ $item->jenis_pengajuan }}
                                </span>
                            </div>
                            <div class="font-bold text-gray-900">Rp {{ number_format($item->nominal, 0, ',', '.') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-[10px] font-black text-gray-800 uppercase tracking-tighter">{{ $item->metode }}</span>
                                    @if($item->metode == 'transfer')
                                        <span class="w-1 h-1 rounded-full bg-blue-500"></span>
                                    @endif
                                </div>
                                
                                @if($item->metode == 'cash')
                                    <span class="text-[10px] text-gray-500 flex items-center gap-1 font-medium">
                                        <svg class="w-3 h-3 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ $item->tgl_janji_temu->format('d M, H:i') }}
                                    </span>
                                @else
                                    <div class="flex flex-wrap gap-1 mt-0.5">
                                        @if($item->files->count() > 0)
                                            @foreach($item->files as $file)
                                                <button onclick="showPhotoPreview('{{ asset('storage/'.$file->path_file) }}', 'Bukti Transfer {{ $item->nasabah->user->nama }}')" class="w-7 h-7 rounded-lg overflow-hidden border border-gray-200 hover:border-[#674c1d] transition-all shadow-xs scale-95 hover:scale-105">
                                                    <img src="{{ asset('storage/'.$file->path_file) }}" class="w-full h-full object-cover">
                                                </button>
                                            @endforeach
                                        @else
                                            <button onclick="showPhotoPreview('{{ asset('storage/'.$item->bukti_transfer) }}', 'Bukti Transfer {{ $item->nasabah->user->nama }}')" class="w-7 h-7 rounded-lg overflow-hidden border border-gray-200 hover:border-[#674c1d] transition-all shadow-xs scale-95 hover:scale-105">
                                                <img src="{{ asset('storage/'.$item->bukti_transfer) }}" class="w-full h-full object-cover">
                                            </button>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-3">
                                <button type="button" onclick="openDetailsModal({{ $item->id }})" 
                                    class="w-9 h-9 flex items-center justify-center bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-sm hover:shadow-blue-100 active:scale-90" title="Lihat Detail">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </button>
                                <button type="button" onclick="openApproveModal({{ $item->id }}, '{{ $item->nasabah->user->nama }}', '{{ strtoupper($item->jenis_pengajuan) }}')" 
                                    class="w-9 h-9 flex items-center justify-center bg-green-50 text-green-600 rounded-xl hover:bg-green-600 hover:text-white transition-all shadow-sm hover:shadow-green-100 active:scale-90" title="Setujui">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </button>
                                <button onclick="openRejectModal({{ $item->id }}, '{{ $item->nasabah->user->nama }}')" 
                                    class="w-9 h-9 flex items-center justify-center bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm hover:shadow-red-100 active:scale-90" title="Tolak">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-32 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center animate-pulse">
                                    <svg class="w-12 h-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                </div>
                                <div class="flex flex-col items-center gap-1">
                                    <h3 class="text-gray-400 font-bold tracking-tight">Antrean Kosong</h3>
                                    <p class="text-gray-300 text-xs">Belum ada pengajuan pembayaran gadai saat ini.</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Detail Pengajuan (Tailwind) -->
<div id="details-modal" class="fixed inset-0 bg-black/60 backdrop-blur-md z-[110] hidden items-center justify-center p-4">
    <div class="bg-white rounded-[2.5rem] shadow-2xl max-w-2xl w-full overflow-hidden animate-in fade-in zoom-in duration-300">
        <div class="p-8">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-amber-50 text-[#674c1d] rounded-2xl flex items-center justify-center shadow-inner">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-gray-900 tracking-tight">Detail Rincian Pengajuan</h3>
                        <p class="text-xs text-gray-500 font-medium">Informasi lengkap permohonan pelunasan/perpanjangan gadai</p>
                    </div>
                </div>
                <button onclick="closeDetailsModal()" class="w-10 h-10 bg-gray-50 hover:bg-gray-100 rounded-full flex items-center justify-center text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm mb-6 max-h-[60vh] overflow-y-auto pr-1">
                {{-- Data Nasabah --}}
                <div class="space-y-4">
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                        <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Nasabah & Barang</h4>
                        <p class="font-bold text-gray-800" id="detail-nasabah-name">-</p>
                        <p class="text-xs text-gray-500 font-medium mt-1" id="detail-barang-name">-</p>
                        <span class="inline-block mt-2 text-[10px] font-mono bg-amber-100 text-amber-800 px-2 py-0.5 rounded font-black" id="detail-slot-code">-</span>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                        <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 font-display">Rincian Transaksi</h4>
                        <div class="flex justify-between py-1 border-b border-gray-200/50">
                            <span class="text-xs text-gray-500">Jenis</span>
                            <span class="font-bold text-gray-800 uppercase text-xs" id="detail-jenis">-</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-200/50">
                            <span class="text-xs text-gray-500">Metode</span>
                            <span class="font-bold text-gray-800 uppercase text-xs" id="detail-metode">-</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-200/50" id="detail-janji-temu-row">
                            <span class="text-xs text-gray-500">Janji Temu</span>
                            <span class="font-bold text-gray-800 text-xs" id="detail-janji-temu">-</span>
                        </div>
                        <div class="flex justify-between pt-2">
                            <span class="text-xs text-gray-600 font-black">Nominal</span>
                            <span class="font-black text-emerald-600 text-base" id="detail-nominal">-</span>
                        </div>
                    </div>
                </div>

                {{-- Keterangan & Lampiran --}}
                <div class="space-y-4">
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                        <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Keterangan Nasabah</h4>
                        <p class="text-xs text-gray-700 italic leading-relaxed" id="detail-keterangan">-</p>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100" id="detail-foto-section">
                        <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Foto Lampiran / Bukti Transfer</h4>
                        <div class="grid grid-cols-3 gap-2" id="detail-foto-grid">
                            <!-- Photos will be inserted dynamically -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex gap-4 border-t border-gray-100 pt-5">
                <button type="button" onclick="closeDetailsModal()" 
                    class="flex-1 px-6 py-3.5 bg-gray-100 text-gray-500 font-bold rounded-2xl hover:bg-gray-200 transition-all active:scale-95 text-center text-sm">
                    Tutup
                </button>
                <div class="flex gap-2 flex-2">
                    <button type="button" id="detail-reject-btn"
                        class="flex-1 px-6 py-3.5 bg-red-50 text-red-600 font-bold rounded-2xl hover:bg-red-600 hover:text-white transition-all active:scale-95 text-center text-sm">
                        Tolak
                    </button>
                    <button type="button" id="detail-approve-btn"
                        class="flex-1 px-6 py-3.5 bg-green-600 text-white font-bold rounded-2xl hover:bg-green-700 transition-all shadow-xl shadow-green-200 active:scale-95 text-center text-sm">
                        Setujui
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Approve (Tailwind) -->
<div id="approve-modal" class="fixed inset-0 bg-black/60 backdrop-blur-md z-[110] hidden items-center justify-center p-4">
    <div class="bg-white rounded-[2.5rem] shadow-2xl max-w-lg w-full overflow-hidden animate-in fade-in zoom-in duration-300">
        <div class="p-8">
            <div class="flex items-center gap-5 mb-6">
                <div class="w-14 h-14 bg-green-100 text-green-600 rounded-[1.25rem] flex items-center justify-center shadow-inner">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h3 class="text-xl font-black text-gray-900 tracking-tight">Setujui Pengajuan</h3>
                    <p class="text-xs text-gray-500">Nasabah: <span id="approve-nasabah-name" class="font-bold text-green-600 bg-green-50 px-1.5 rounded"></span> | <span id="approve-jenis" class="font-bold"></span></p>
                </div>
            </div>

            <form id="formApprove" action="" method="POST" enctype="multipart/form-data" class="space-y-5" onsubmit="this.querySelector('button[type=submit]').disabled=true; this.querySelector('button[type=submit]').innerHTML='Mengolah...';">
                @csrf
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Upload Bukti Administrasi (Opsional)</label>
                    <div id="admin-bukti-container" class="grid grid-cols-3 gap-2 mb-2">
                        <!-- Add Button -->
                        <button type="button" onclick="addAdminBuktiField()" class="aspect-square rounded-xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center text-gray-400 hover:border-green-500 hover:text-green-500 transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            <span class="text-[8px] font-bold mt-1 uppercase">Tambah</span>
                        </button>
                    </div>
                    <p class="text-[9px] text-gray-400">Bukti penyerahan barang, kwitansi, atau foto dokumentasi admin.</p>
                </div>

                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Keterangan Admin (Opsional)</label>
                    <textarea name="admin_keterangan" rows="3" 
                        class="w-full px-5 py-3 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-green-500 focus:bg-white focus:ring-4 focus:ring-green-500/10 transition-all resize-none text-sm font-medium text-gray-700"
                        placeholder="Tambahkan catatan untuk nasabah jika ada..."></textarea>
                </div>

                <!-- Extra Pinjaman Section (Only for LUNAS) -->
                <div id="approve-extra-section" class="hidden space-y-4 pt-2 border-t border-gray-100">
                    <div>
                        <h4 class="text-sm font-bold text-gray-900">Biaya Tambahan / Denda (Opsional)</h4>
                        <p class="text-[10px] text-gray-500">Nominal Pengajuan Lunas: <span id="approve-nominal-display" class="font-bold text-gray-800"></span></p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Nominal Extra (Rp)</label>
                            <input type="number" name="extra_pinjaman_nominal" id="extra_pinjaman_nominal" min="0" value="0"
                                class="w-full px-5 py-3 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-green-500 focus:bg-white focus:ring-4 focus:ring-green-500/10 transition-all text-sm font-bold text-gray-900"
                                oninput="checkExtraReasonRequired()">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Alasan Extra</label>
                            <input type="text" name="extra_pinjaman_reason" id="extra_pinjaman_reason"
                                class="w-full px-5 py-3 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-green-500 focus:bg-white focus:ring-4 focus:ring-green-500/10 transition-all text-sm font-medium text-gray-700 placeholder:text-gray-400"
                                placeholder="Contoh: Denda struk hilang">
                        </div>
                    </div>
                </div>

                <div class="bg-amber-50 rounded-2xl p-4 border border-amber-100 flex gap-3">
                    <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-[10px] text-amber-800 font-medium">Dengan menyetujui, saldo Petty Cash akan bertambah secara otomatis sesuai dengan nominal pengajuan.</p>
                </div>

                <div class="flex gap-4 pt-2">
                    <button type="button" onclick="closeApproveModal()" 
                        class="flex-1 px-6 py-4 bg-gray-100 text-gray-500 font-bold rounded-2xl hover:bg-gray-200 transition-all active:scale-95">
                        Batal
                    </button>
                    <button type="submit" 
                        class="flex-2 px-6 py-4 bg-green-600 text-white font-bold rounded-2xl hover:bg-green-700 transition-all shadow-xl shadow-green-200 active:scale-95">
                        Konfirmasi & Setujui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="reject-modal" class="fixed inset-0 bg-black/60 backdrop-blur-md z-[110] hidden items-center justify-center p-4">
    <div class="bg-white rounded-[2.5rem] shadow-2xl max-w-md w-full overflow-hidden animate-in fade-in zoom-in duration-300">
        <div class="p-8">
            <div class="flex items-center gap-5 mb-8">
                <div class="w-14 h-14 bg-red-100 text-red-600 rounded-[1.25rem] flex items-center justify-center shadow-inner">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
                <div>
                    <h3 class="text-xl font-black text-gray-900 tracking-tight">Tolak Pengajuan</h3>
                    <p class="text-xs text-gray-500">Nasabah: <span id="reject-nasabah-name" class="font-bold text-red-600 bg-red-50 px-1.5 rounded"></span></p>
                </div>
            </div>

            <form id="formReject" action="" method="POST" class="space-y-5" onsubmit="this.querySelector('button[type=submit]').disabled=true; this.querySelector('button[type=submit]').innerHTML='Mengolah...';">
                @csrf
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Alasan Penolakan</label>
                    <textarea name="keterangan" rows="5" required 
                        class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-3xl focus:border-red-500 focus:bg-white focus:ring-4 focus:ring-red-500/10 transition-all resize-none text-sm font-medium text-gray-700 placeholder:text-gray-300"
                        placeholder="Berikan alasan jelas kenapa pengajuan ini ditolak..."></textarea>
                </div>

                <div class="flex gap-4 pt-2">
                    <button type="button" onclick="closeRejectModal()" 
                        class="flex-1 px-6 py-4 bg-gray-100 text-gray-500 font-bold rounded-2xl hover:bg-gray-200 transition-all active:scale-95">
                        Batal
                    </button>
                    <button type="submit" 
                        class="flex-2 px-6 py-4 bg-red-600 text-white font-bold rounded-2xl hover:bg-red-700 transition-all shadow-xl shadow-red-200 active:scale-95">
                        Tolak Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const pengajuanData = {
        @foreach($pengajuan as $item)
            "{{ $item->id }}": {
                id: {{ $item->id }},
                nasabahName: "{{ addslashes($item->nasabah->user->nama) }}",
                barangName: "{{ addslashes($item->gadaiActive->item->nama_item) }}",
                slotCode: "{{ $item->gadaiActive->slot_code }} / {{ $item->gadaiActive->slot_table }}",
                jenis: "{{ strtoupper($item->jenis_pengajuan) }}",
                metode: "{{ strtoupper($item->metode) }}",
                nominal: "Rp {{ number_format($item->nominal, 0, ',', '.') }}",
                janjiTemu: "{{ $item->tgl_janji_temu ? $item->tgl_janji_temu->format('d M Y, H:i') : '-' }}",
                keterangan: "{{ $item->keterangan ? addslashes(str_replace(array("\r", "\n"), ' ', $item->keterangan)) : 'Tidak ada keterangan dari nasabah.' }}",
                photos: [
                    @if($item->files->count() > 0)
                        @foreach($item->files as $file)
                            "{{ asset('storage/'.$file->path_file) }}",
                        @endforeach
                    @elseif($item->bukti_transfer)
                        "{{ asset('storage/'.$item->bukti_transfer) }}"
                    @endif
                ]
            },
        @endforeach
    };

    function openDetailsModal(id) {
        const data = pengajuanData[id];
        if (!data) return;

        document.getElementById('detail-nasabah-name').textContent = data.nasabahName;
        document.getElementById('detail-barang-name').textContent = data.barangName;
        document.getElementById('detail-slot-code').textContent = data.slotCode;
        document.getElementById('detail-jenis').textContent = data.jenis;
        document.getElementById('detail-metode').textContent = data.metode;
        
        const janjiTemuRow = document.getElementById('detail-janji-temu-row');
        if (data.metode === 'CASH') {
            janjiTemuRow.classList.remove('hidden');
            document.getElementById('detail-janji-temu').textContent = data.janjiTemu;
        } else {
            janjiTemuRow.classList.add('hidden');
        }

        document.getElementById('detail-nominal').textContent = data.nominal;
        document.getElementById('detail-keterangan').textContent = data.keterangan;

        // Render photos
        const grid = document.getElementById('detail-foto-grid');
        const photoSection = document.getElementById('detail-foto-section');
        grid.innerHTML = '';
        
        if (data.photos.length > 0) {
            photoSection.classList.remove('hidden');
            data.photos.forEach((photoUrl) => {
                grid.innerHTML += `
                    <button type="button" onclick="showPhotoPreview('${photoUrl}', 'Lampiran Foto ${data.nasabahName}')" class="aspect-square rounded-xl overflow-hidden border border-gray-200 hover:border-[#674c1d] transition-all shadow-xs scale-95 hover:scale-105">
                        <img src="${photoUrl}" class="w-full h-full object-cover">
                    </button>
                `;
            });
        } else {
            photoSection.classList.add('hidden');
        }

        // Bind actions to buttons inside detail modal
        document.getElementById('detail-approve-btn').onclick = function() {
            closeDetailsModal();
            openApproveModal(data.id, data.nasabahName, data.jenis);
        };
        document.getElementById('detail-reject-btn').onclick = function() {
            closeDetailsModal();
            openRejectModal(data.id, data.nasabahName);
        };

        const modal = document.getElementById('details-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeDetailsModal() {
        const modal = document.getElementById('details-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }

    // Close details modal on click outside
    document.getElementById('details-modal').addEventListener('click', function(e) {
        if (e.target === this) closeDetailsModal();
    });

    let adminBuktiCount = 0;
    function addAdminBuktiField() {
        adminBuktiCount++;
        const container = document.getElementById('admin-bukti-container');
        const div = document.createElement('div');
        div.className = 'relative aspect-square rounded-xl bg-gray-50 border-2 border-gray-100 overflow-hidden group animate-in zoom-in duration-200';
        div.innerHTML = `
            <input type="file" name="admin_bukti_foto[]" class="absolute inset-0 opacity-0 z-20 cursor-pointer" onchange="previewAdminFile(this)" required>
            <div class="absolute inset-0 z-10 flex items-center justify-center bg-gray-50 text-gray-400 group-hover:bg-gray-100 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <img class="absolute inset-0 z-30 w-full h-full object-cover hidden">
            <button type="button" onclick="this.closest('.relative').remove()" class="absolute top-1 right-1 z-40 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 shadow-sm">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        `;
        container.insertBefore(div, container.lastElementChild);
    }

    function previewAdminFile(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            const container = input.closest('.relative');
            const img = container.querySelector('img');
            const icon = container.querySelector('div');
            
            reader.onload = function(e) {
                img.src = e.target.result;
                img.classList.remove('hidden');
                icon.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function checkExtraReasonRequired() {
        const nominal = document.getElementById('extra_pinjaman_nominal').value;
        const reasonInput = document.getElementById('extra_pinjaman_reason');
        if (parseFloat(nominal) > 0) {
            reasonInput.setAttribute('required', 'required');
        } else {
            reasonInput.removeAttribute('required');
        }
    }

    function openApproveModal(id, name, jenis) {
        const data = pengajuanData[id];
        const modal = document.getElementById('approve-modal');
        const form = document.getElementById('formApprove');
        const nameDisplay = document.getElementById('approve-nasabah-name');
        const jenisDisplay = document.getElementById('approve-jenis');
        
        form.action = "{{ route('admin.gadai_baru.pengajuan.approve', ':id') }}".replace(':id', id);
        nameDisplay.textContent = name;
        jenisDisplay.textContent = jenis;
        
        // Handle Extra Pinjaman display
        const extraSection = document.getElementById('approve-extra-section');
        const nominalDisplay = document.getElementById('approve-nominal-display');
        if (jenis === 'LUNAS') {
            extraSection.classList.remove('hidden');
            nominalDisplay.textContent = data.nominal;
        } else {
            extraSection.classList.add('hidden');
        }
        
        // Reset extra inputs
        document.getElementById('extra_pinjaman_nominal').value = 0;
        document.getElementById('extra_pinjaman_reason').value = '';
        checkExtraReasonRequired();

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeApproveModal() {
        const modal = document.getElementById('approve-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }

    function openRejectModal(id, name) {
        const modal = document.getElementById('reject-modal');
        const form = document.getElementById('formReject');
        const nameDisplay = document.getElementById('reject-nasabah-name');
        
        form.action = "{{ route('admin.gadai_baru.pengajuan.reject', ':id') }}".replace(':id', id);
        nameDisplay.textContent = name;
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeRejectModal() {
        const modal = document.getElementById('reject-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }

    // Close modal on click outside
    document.getElementById('reject-modal').addEventListener('click', function(e) {
        if (e.target === this) closeRejectModal();
    });

    // ESC key to close
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeRejectModal();
    });
</script>
@endpush
@endsection
