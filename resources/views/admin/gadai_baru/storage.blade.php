@extends('layouts.admin')

@section('title', 'Peta Penyimpanan Gadai')

@section('content')
<div class="space-y-6">

    {{-- ===== PAGE HEADER ===== --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 font-display">Peta Storage Gadai</h1>
            <p class="text-gray-500 text-sm mt-1">Visualisasi ketersediaan slot penyimpanan per kategori</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.gadai_baru.index') }}"
                class="flex items-center gap-2 px-4 py-2.5 bg-white text-gray-700 border border-gray-200 font-medium rounded-xl hover:bg-gray-50 transition-colors shadow-sm text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Daftar
            </a>
            <form action="{{ route('admin.gadai_baru.storage') }}" method="GET">
                <select name="kategori" class="border-gray-200 rounded-xl focus:ring-[#674c1d] focus:border-[#674c1d] text-sm font-medium bg-white shadow-sm" onchange="this.form.submit()">
                    <option value="electronic" {{ $kategori == 'electronic' ? 'selected' : '' }}>📱 Elektronik</option>
                    <option value="vehicle" {{ $kategori == 'vehicle' ? 'selected' : '' }}>🚗 Kendaraan</option>
                    <option value="gold" {{ $kategori == 'gold' ? 'selected' : '' }}>💰 Emas</option>
                </select>
            </form>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
    <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl shadow-sm flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-sm font-semibold text-emerald-800">{{ session('success') }}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 font-bold text-xl leading-none">&times;</button>
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-sm font-semibold text-red-800">{{ session('error') }}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 font-bold text-xl leading-none">&times;</button>
    </div>
    @endif
    @if ($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm">
        <ul class="text-xs text-red-700 font-medium space-y-1">
            @foreach ($errors->all() as $error)<li>• {{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    {{-- ===== STATS BAR ===== --}}
    @php
        $allSlots     = collect($groupedGrid)->flatten(1);
        $totalSlots   = $allSlots->count();
        $filledSlots  = $allSlots->where('is_occupied', true)->count();
        $expiredSlots = $allSlots->where('gadai_status', 'expired_final')->count();
        $emptySlots   = $totalSlots - $filledSlots;
        $fillPct      = $totalSlots > 0 ? round(($filledSlots / $totalSlots) * 100) : 0;
    @endphp
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl p-4 border border-white/60 shadow-xl text-center">
            <p class="text-2xl font-black text-gray-900">{{ $totalSlots }}</p>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Total Slot</p>
        </div>
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl p-4 border border-red-200/50 shadow-xl text-center">
            <p class="text-2xl font-black text-red-600">{{ $filledSlots }}</p>
            <p class="text-[10px] font-bold text-red-400/80 uppercase tracking-widest mt-1">Terisi</p>
        </div>
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl p-4 border border-emerald-200/50 shadow-xl text-center">
            <p class="text-2xl font-black text-emerald-600">{{ $emptySlots }}</p>
            <p class="text-[10px] font-bold text-emerald-400/80 uppercase tracking-widest mt-1">Kosong</p>
        </div>
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl p-4 border border-amber-200/50 shadow-xl text-center">
            <p class="text-2xl font-black text-amber-600">{{ $expiredSlots }}</p>
            <p class="text-[10px] font-bold text-amber-400/80 uppercase tracking-widest mt-1">Hangus / Siap Lelang</p>
        </div>
    </div>

    {{-- ===== OCCUPANCY BAR ===== --}}
    @if($totalSlots > 0)
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl p-4 border border-white/60 shadow-xl">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-bold text-gray-500">Tingkat Hunian Storage</span>
            <span class="text-xs font-black text-gray-800">{{ $fillPct }}% terisi</span>
        </div>
        <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
            <div class="h-3 rounded-full bg-gradient-to-r from-[#674c1d] to-[#d4af37] transition-all duration-700" style="width: {{ $fillPct }}%"></div>
        </div>
     {{-- ===== LEGEND ===== --}}
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl p-4 border border-white/60 shadow-xl">
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Keterangan Warna Slot</p>
        <div class="flex flex-wrap gap-4">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 border-2 border-dashed border-emerald-300"></div>
                <span class="text-xs font-bold text-gray-600">Kosong (Hijau)</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-amber-50 border-2 border-amber-300"></div>
                <span class="text-xs font-bold text-gray-600">Terisi (Kuning)</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-red-50 border-2 border-red-400"></div>
                <span class="text-xs font-bold text-gray-600">Hangus (Merah)</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-amber-50 border-2 border-dashed border-[#d4af37] animate-pulse"></div>
                <span class="text-xs font-bold text-gray-600">Siap Diambil / Lunas (Kuning Emas)</span>
            </div>
        </div>
    </div>
    </div>

    {{-- ===== GRID STORAGE ===== --}}
    <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-3xl shadow-xl p-6 overflow-x-auto">
        <div class="min-w-max space-y-5">
            @forelse($groupedGrid as $baris => $koloms)
                <div class="flex gap-4 items-center">
                    <div class="w-20 font-bold text-gray-400 text-xs text-right shrink-0">Baris {{ $baris }}</div>
                    <div class="flex gap-3">
                        @foreach($koloms as $slot)
                            @if($slot->is_occupied)
                                @php 
                                    $isExpired = $slot->gadai_status === 'expired_final'; 
                                    $isLunas = $slot->gadai_status === 'lunas';
                                    
                                    if ($isExpired) {
                                        $cardClass = 'bg-red-50 border-2 border-red-400 hover:border-red-500';
                                        $textClass = 'text-red-900';
                                        $badgeClass = 'bg-red-200 text-red-900';
                                        $statusText = 'Hangus';
                                    } elseif ($isLunas) {
                                        $cardClass = 'bg-amber-50 border-2 border-dashed border-[#d4af37] hover:border-amber-500 animate-pulse';
                                        $textClass = 'text-[#674c1d]';
                                        $badgeClass = 'bg-[#674c1d] text-white';
                                        $statusText = 'Siap Diambil';
                                    } else {
                                        $cardClass = 'bg-amber-50 border-2 border-amber-300 hover:border-amber-400';
                                        $textClass = 'text-amber-900';
                                        $badgeClass = 'bg-amber-100 text-amber-700';
                                        $statusText = 'Terisi';
                                    }
                                 @endphp
                                 <div class="w-40 h-44 {{ $cardClass }} rounded-xl p-3 flex flex-col justify-between shadow-sm transition-all hover:shadow-md cursor-default group relative"
                                      x-data="{}" 
                                      title="{{ $slot->nasabah_nama }} — {{ $slot->item_nama }}">
                                      <div class="flex justify-between items-start">
                                          <div class="font-mono font-black text-sm {{ $textClass }}">{{ $slot->kode_slot }}</div>
                                          <span class="px-1.5 py-0.5 {{ $badgeClass }} text-[9px] font-black rounded uppercase tracking-wide">{{ $statusText }}</span>
                                      </div>
                                      <div class="flex-1 flex flex-col justify-center min-w-0 my-2">
                                          <div class="text-xs {{ $textClass }} font-black truncate leading-tight mb-1" title="{{ $slot->item_nama }}">{{ $slot->item_nama }}</div>
                                          <div class="text-[10px] text-gray-500 font-semibold truncate" title="{{ $slot->nasabah_nama }}">{{ $slot->nasabah_nama }}</div>
                                          @if($isLunas && $slot->tgl_ambil_limit)
                                              @php
                                                  $limit = \Carbon\Carbon::parse($slot->tgl_ambil_limit);
                                                  $now = now();
                                                  $diffHours = $now->diffInHours($limit, false);
                                                  $diffDays = $now->diffInDays($limit, false);
                                              @endphp
                                              <div class="text-[9px] text-red-500 font-black mt-1">
                                                  ⏱️ Sisa: {{ $diffDays > 0 ? $diffDays.' H' : ($diffHours > 0 ? $diffHours.' Jam' : 'Segera Hangus!') }}
                                              </div>
                                          @endif
                                      </div>
                                      @if($isExpired)
                                          <button onclick="openEmptyAuctionModal({{ $slot->active_gadai_id }}, '{{ $slot->kode_slot }}', '{{ addslashes($slot->nasabah_nama) }}', '{{ addslashes($slot->item_nama) }}')"
                                              class="w-full py-2 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 active:scale-95 text-white text-[10px] font-black rounded-lg transition-all shadow-sm uppercase tracking-wider">
                                              🔨 Kosongkan & Lelang
                                          </button>
                                      @elseif($isLunas)
                                          <button onclick="openAmbilModal({{ $slot->active_gadai_id }}, '{{ $slot->kode_slot }}', '{{ addslashes($slot->nasabah_nama) }}', '{{ addslashes($slot->item_nama) }}')"
                                              class="w-full py-2 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 active:scale-95 text-white text-[10px] font-black rounded-lg transition-all shadow-sm uppercase tracking-wider">
                                              📦 Serahkan Barang
                                          </button>
                                      @else
                                          <div class="w-full py-1.5 bg-gray-200 text-gray-600 text-[10px] font-bold rounded-lg text-center uppercase tracking-wide">{{ $statusText }} Tersimpan</div>
                                      @endif
                                 </div>
                            @else
                                <div class="w-40 h-44 bg-emerald-50/60 border-2 border-dashed border-emerald-300 rounded-xl p-3 flex flex-col justify-between hover:bg-emerald-50 hover:border-emerald-400 transition-colors group">
                                    <div class="font-mono font-black text-sm text-emerald-700">{{ $slot->kode_slot }}</div>
                                    <div class="flex-1 flex items-center justify-center">
                                        <div class="text-center">
                                            <svg class="w-8 h-8 text-emerald-300 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/></svg>
                                            <span class="text-[10px] font-black text-emerald-500 uppercase tracking-wider">Kosong</span>
                                        </div>
                                    </div>
                                    <div class="w-full py-1.5 bg-emerald-100 text-emerald-600 text-[10px] font-black rounded-lg text-center uppercase tracking-wider">Tersedia</div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-center py-20 flex flex-col items-center gap-4">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    </div>
                    <div>
                        <p class="text-gray-400 font-bold">Tidak ada grid penyimpanan</p>
                        <p class="text-gray-300 text-xs mt-1">untuk kategori yang dipilih.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- ===== MODAL EMPTY & AUCTION ===== --}}
<div id="emptyAuctionModal" class="fixed inset-0 z-50 overflow-y-auto hidden" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" onclick="closeEmptyAuctionModal()"></div>
        <div class="relative bg-white/95 backdrop-blur-2xl rounded-3xl shadow-2xl border border-white/60 max-w-lg w-full overflow-hidden z-10 animate-in fade-in zoom-in duration-300">
            <form action="{{ route('admin.gadai_baru.storage.empty-auction') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="gadai_id" id="modal_gadai_id">

                {{-- Modal Header --}}
                <div class="bg-gradient-to-r from-[#674c1d] to-[#d4af37] px-6 py-5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-white text-xl">🔨</div>
                        <div>
                            <h3 class="text-lg font-black text-white">Kosongkan Slot untuk Lelang</h3>
                            <p class="text-white/70 text-xs">Barang hangus akan dipindah ke proses lelang</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeEmptyAuctionModal()" class="w-8 h-8 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="p-6 space-y-5">
                    {{-- Warning --}}
                    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 flex items-start gap-3">
                        <span class="text-xl shrink-0 mt-0.5">⚠️</span>
                        <div>
                            <p class="text-xs font-black text-amber-900 uppercase tracking-wider mb-1">Perhatian!</p>
                            <p class="text-xs text-amber-700 font-medium leading-relaxed">Barang pada slot ini berstatus <strong class="underline">hangus</strong>. Konfirmasi ini akan mengosongkan slot dan mengubah status barang menjadi <strong>Sudah Dilelang</strong>.</p>
                        </div>
                    </div>

                    {{-- Slot Info --}}
                    <div class="grid grid-cols-2 gap-4 bg-gray-50 p-4 rounded-2xl border border-gray-100">
                        <div>
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-wider">Kode Slot</p>
                            <p id="modal_slot_kode" class="text-sm font-extrabold text-gray-900 mt-0.5 font-mono">-</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-wider">Nama Barang</p>
                            <p id="modal_item_nama" class="text-sm font-extrabold text-gray-900 mt-0.5">-</p>
                        </div>
                        <div class="col-span-2 pt-3 border-t border-gray-200">
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-wider">Nama Nasabah</p>
                            <p id="modal_nasabah_nama" class="text-sm font-semibold text-gray-700 mt-0.5">-</p>
                        </div>
                    </div>

                    {{-- Upload Foto --}}
                    <div>
                        <label class="block text-xs font-black text-gray-700 uppercase tracking-wider mb-2">Unggah Foto Bukti Pengambilan <span class="text-red-500">*</span></label>
                        <div class="relative border-2 border-dashed border-gray-200 hover:border-amber-400 rounded-2xl p-5 transition-all bg-gray-50/50 cursor-pointer">
                            <input type="file" name="foto_bukti[]" multiple required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewImages(event)">
                            <div class="text-center space-y-1.5">
                                <svg class="mx-auto h-10 w-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <p class="text-xs text-gray-500 font-semibold">Klik atau seret foto bukti di sini</p>
                                <p class="text-[10px] text-gray-400">JPEG, PNG (Maks. 2MB per foto)</p>
                            </div>
                        </div>
                        <div id="image_preview_container" class="flex flex-wrap gap-2 pt-3"></div>
                    </div>

                    {{-- Catatan --}}
                    <div>
                        <label class="block text-xs font-black text-gray-700 uppercase tracking-wider mb-2">Catatan Pengambilan <span class="text-red-500">*</span></label>
                        <textarea name="catatan" rows="3" required
                            placeholder="Contoh: Barang diambil oleh tim logistik A untuk dipindahkan ke balai lelang..."
                            class="w-full border-gray-200 rounded-2xl focus:ring-amber-500 focus:border-amber-500 text-sm font-medium placeholder:text-gray-300 resize-none"></textarea>
                    </div>
                </div>

                <div class="bg-white/50 backdrop-blur-md px-6 py-4 flex gap-3 justify-end rounded-b-3xl border-t border-gray-100">
                    <button type="button" onclick="closeEmptyAuctionModal()"
                        class="px-5 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-bold rounded-xl transition-all">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-[#674c1d] to-[#d4af37] hover:from-[#5a4118] hover:to-[#b3952f] active:scale-95 text-white text-sm font-black rounded-xl transition-all shadow-xl shadow-[#674c1d]/20 uppercase tracking-wide"
                        onsubmit="this.disabled=true;this.textContent='Memproses...'">
                        🔨 Konfirmasi & Proses Lelang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== MODAL AMBIL BARANG (LUNAS) ===== --}}
<div id="ambilModal" class="fixed inset-0 z-50 overflow-y-auto hidden" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" onclick="closeAmbilModal()"></div>
        <div class="relative bg-white/95 backdrop-blur-2xl rounded-3xl shadow-2xl border border-white/60 max-w-lg w-full overflow-hidden z-10 animate-in fade-in zoom-in duration-300">
            <form id="ambilForm" action="" method="POST" enctype="multipart/form-data">
                @csrf
                {{-- Modal Header --}}
                <div class="bg-gradient-to-r from-emerald-600 to-teal-500 px-6 py-5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-white text-xl">📦</div>
                        <div>
                            <h3 class="text-lg font-black text-white">Serahkan Barang ke Nasabah</h3>
                            <p class="text-white/70 text-xs">Penyerahan barang gadai yang telah lunas</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeAmbilModal()" class="w-8 h-8 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="p-6 space-y-5">
                    {{-- Info --}}
                    <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 flex items-start gap-3">
                        <span class="text-xl shrink-0 mt-0.5">ℹ️</span>
                        <div>
                            <p class="text-xs font-black text-emerald-900 uppercase tracking-wider mb-1">Konfirmasi Serah Terima</p>
                            <p class="text-xs text-emerald-700 font-medium leading-relaxed">Pastikan nasabah telah menandatangani bukti penyerahan barang di toko fisik sebelum memproses pengambilan barang ini.</p>
                        </div>
                    </div>

                    {{-- Slot Info --}}
                    <div class="grid grid-cols-2 gap-4 bg-gray-50 p-4 rounded-2xl border border-gray-100">
                        <div>
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-wider">Kode Slot</p>
                            <p id="ambil_slot_kode" class="text-sm font-extrabold text-gray-900 mt-0.5 font-mono">-</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-wider">Nama Barang</p>
                            <p id="ambil_item_nama" class="text-sm font-extrabold text-gray-900 mt-0.5">-</p>
                        </div>
                        <div class="col-span-2 pt-3 border-t border-gray-200">
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-wider">Nama Nasabah</p>
                            <p id="ambil_nasabah_nama" class="text-sm font-semibold text-gray-700 mt-0.5">-</p>
                        </div>
                    </div>

                    {{-- Struk Hilang Checkbox & Payment Method --}}
                    <div class="bg-amber-50/50 border border-amber-200/60 rounded-2xl p-4 space-y-3">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" name="struk_hilang" id="struk_hilang" value="1" 
                                   class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-gray-300"
                                   onchange="toggleDendaSection(this.checked)">
                            <label for="struk_hilang" class="text-xs font-black text-gray-700 uppercase tracking-wider cursor-pointer">
                                Struk Kehilangan / Hilang (Denda Rp {{ number_format($settings->extra_nilai_kehilangan ?? 0, 0, ',', '.') }})
                            </label>
                        </div>
                        
                        <div id="denda_payment_section" class="hidden space-y-2 pt-2 border-t border-amber-200/40">
                            <label class="block text-xs font-black text-gray-700 uppercase tracking-wider">Metode Pembayaran Denda <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition-colors">
                                    <input type="radio" name="metode_denda" value="cash" checked class="text-emerald-600 focus:ring-emerald-500 border-gray-300">
                                    <span class="text-xs font-bold text-gray-700">Tunai (Cash)</span>
                                </label>
                                <label class="flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition-colors">
                                    <input type="radio" name="metode_denda" value="transfer" class="text-emerald-600 focus:ring-emerald-500 border-gray-300">
                                    <span class="text-xs font-bold text-gray-700">Transfer Bank</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Upload Foto --}}
                    <div>
                        <label class="block text-xs font-black text-gray-700 uppercase tracking-wider mb-2">Unggah Foto Bukti Penyerahan/Serah Terima <span class="text-red-500">*</span></label>
                        <div class="relative border-2 border-dashed border-gray-200 hover:border-emerald-400 rounded-2xl p-5 transition-all bg-gray-50/50 cursor-pointer">
                            <input type="file" name="foto_bukti[]" multiple required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewAmbilImages(event)">
                            <div class="text-center space-y-1.5">
                                <svg class="mx-auto h-10 w-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <p class="text-xs text-gray-500 font-semibold">Klik atau seret foto bukti di sini</p>
                                <p class="text-[10px] text-gray-400">JPEG, PNG (Maks. 2MB per foto)</p>
                            </div>
                        </div>
                        <div id="ambil_image_preview_container" class="flex flex-wrap gap-2 pt-3"></div>
                    </div>
                </div>

                <div class="bg-white/50 backdrop-blur-md px-6 py-4 flex gap-3 justify-end rounded-b-3xl border-t border-gray-100">
                    <button type="button" onclick="closeAmbilModal()"
                        class="px-5 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-bold rounded-xl transition-all">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-700 hover:to-teal-600 active:scale-95 text-white text-sm font-black rounded-xl transition-all shadow-xl shadow-emerald-600/20 uppercase tracking-wide">
                        📦 Konfirmasi Penyerahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openEmptyAuctionModal(gadaiId, slotKode, nasabahNama, itemNama) {
        document.getElementById('modal_gadai_id').value = gadaiId;
        document.getElementById('modal_slot_kode').textContent = slotKode;
        document.getElementById('modal_nasabah_nama').textContent = nasabahNama;
        document.getElementById('modal_item_nama').textContent = itemNama;
        const modal = document.getElementById('emptyAuctionModal');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeEmptyAuctionModal() {
        document.getElementById('emptyAuctionModal').classList.add('hidden');
        document.body.style.overflow = '';
        document.getElementById('image_preview_container').innerHTML = '';
    }
    function previewImages(event) {
        const container = document.getElementById('image_preview_container');
        container.innerHTML = '';
        Array.from(event.target.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = e => {
                const div = document.createElement('div');
                div.className = 'relative w-14 h-14 rounded-xl overflow-hidden border border-gray-200 shadow-sm';
                div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                container.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }

    function openAmbilModal(gadaiId, slotKode, nasabahNama, itemNama) {
        const form = document.getElementById('ambilForm');
        form.action = `/admin/gadai_baru/${gadaiId}/ambil`;
        document.getElementById('ambil_slot_kode').textContent = slotKode;
        document.getElementById('ambil_nasabah_nama').textContent = nasabahNama;
        document.getElementById('ambil_item_nama').textContent = itemNama;
        const modal = document.getElementById('ambilModal');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeAmbilModal() {
        document.getElementById('ambilModal').classList.add('hidden');
        document.body.style.overflow = '';
        document.getElementById('ambil_image_preview_container').innerHTML = '';
        const checkbox = document.getElementById('struk_hilang');
        if (checkbox) checkbox.checked = false;
        toggleDendaSection(false);
    }
    function toggleDendaSection(isChecked) {
        const dendaSection = document.getElementById('denda_payment_section');
        if (dendaSection) {
            if (isChecked) {
                dendaSection.classList.remove('hidden');
            } else {
                dendaSection.classList.add('hidden');
            }
        }
    }
    function previewAmbilImages(event) {
        const container = document.getElementById('ambil_image_preview_container');
        container.innerHTML = '';
        Array.from(event.target.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = e => {
                const div = document.createElement('div');
                div.className = 'relative w-14 h-14 rounded-xl overflow-hidden border border-gray-200 shadow-sm';
                div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                container.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }
</script>
@endsection
