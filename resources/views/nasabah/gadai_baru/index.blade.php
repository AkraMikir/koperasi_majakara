@extends('layouts.nasabah')

@section('title', 'Layanan Gadai')

@section('content')
<div class="w-full pb-10">
    <!-- Header Premium -->
    <div class="mx-4 mt-4 mb-6">
        <div class="bg-linear-to-br from-[#674c1d] via-[#8b6f2f] to-[#d4af37] rounded-3xl shadow-2xl p-8 border-2 border-[#d4af37]/30 relative overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white rounded-full -mr-32 -mt-32"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-white rounded-full -ml-24 -mb-24"></div>
            </div>
            
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <p class="text-white/90 text-sm font-black uppercase tracking-widest mb-2">Layanan Cepat & Aman</p>
                    <h1 class="text-4xl md:text-5xl font-black text-white mb-3 font-display leading-tight">Gadai Majakara</h1>
                    <p class="text-white/80 text-sm max-w-md">Solusi pendanaan cepat dengan jaminan barang berharga Anda. Aman, transparan, dan terpercaya.</p>
                </div>
                <div class="w-24 h-24 bg-white/20 backdrop-blur-md rounded-[2rem] flex items-center justify-center shadow-2xl border border-white/30 animate-pulse">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="mx-4 mb-6">
        <div class="bg-green-50 border border-green-200 rounded-2xl p-4 text-green-700 text-sm font-bold shadow-sm">
            {{ session('success') }}
        </div>
    </div>
    @endif

    @if(session('warning'))
    <div class="mx-4 mb-6">
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 text-amber-700 text-sm font-bold shadow-sm">
            {{ session('warning') }}
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mx-4 mb-6">
        <div class="bg-red-50 border border-red-200 rounded-2xl p-4 text-red-700 text-sm font-bold shadow-sm">
            {{ session('error') }}
        </div>
    </div>
    @endif

    <!-- Dashboard Section -->
    <div class="mx-4 mb-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-black text-gray-900 tracking-tight font-display">Gadai Aktif</h2>
            @if($gadaiAktif->isNotEmpty())
                <span class="px-3 py-1 bg-amber-100 text-[#674c1d] text-[10px] font-black rounded-full uppercase tracking-widest border border-amber-200">
                    {{ $gadaiAktif->count() }} Aktif
                </span>
            @endif
        </div>

        @if($gadaiAktif->isEmpty())
            <div class="bg-white rounded-3xl p-10 shadow-sm border border-gray-100 text-center flex flex-col items-center gap-4">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center text-gray-300">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                </div>
                <div>
                    <p class="text-gray-400 font-bold tracking-tight">Tidak ada gadai aktif.</p>
                    <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mt-1">Silakan pilih kategori di bawah untuk pengajuan baru.</p>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($gadaiAktif as $gadai)
                @php
                    $isTenggang = $gadai->status == 'grace_period';
                    $totalTagihan = $gadai->nominal_deal + $gadai->biaya_jasa + $gadai->biaya_inap;
                    $totalPerpanjang = $gadai->biaya_jasa + $gadai->denda_aktif + $gadai->biaya_inap;
                @endphp
                <div class="bg-white rounded-3xl p-6 shadow-xl border border-gray-50 relative overflow-hidden group hover:scale-[1.01] transition-all duration-300">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $gadai->kategori->nama_kategori }}</span>
                                <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                                <span class="text-[9px] font-black text-[#674c1d] uppercase tracking-widest">Slot {{ $gadai->slot_kode }}</span>
                            </div>
                            <a href="{{ route('nasabah.gadai_baru.aktif-detail', $gadai->id) }}" class="group/title flex items-center gap-1.5">
                                <h3 class="font-black text-gray-900 text-xl tracking-tight leading-none group-hover/title:text-[#674c1d] transition-colors">{{ $gadai->item->head_1 ?? $gadai->item->nama_item }}</h3>
                                <svg class="w-4 h-4 text-gray-400 group-hover/title:text-[#674c1d] group-hover/title:translate-x-0.5 transition-all shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                            @if($gadai->item->head_2)
                                <p class="text-[10px] text-gray-400 mt-1.5 italic">{{ $gadai->item->head_2 }}</p>
                            @endif
                        </div>
                        <div class="flex flex-col items-end gap-2 shrink-0">
                            @if($isTenggang)
                                <span class="bg-red-600 text-white text-[9px] font-black px-3 py-1 rounded-full shadow-lg shadow-red-200">TENGGANG</span>
                            @else
                                <span class="bg-[#674c1d] text-white text-[9px] font-black px-3 py-1 rounded-full shadow-lg shadow-amber-200">AKTIF</span>
                            @endif
                            <a href="{{ route('nasabah.gadai_baru.aktif-detail', $gadai->id) }}" class="text-[9px] font-black text-[#674c1d] uppercase tracking-widest hover:underline flex items-center gap-1">
                                Rincian
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="bg-gray-50 rounded-2xl p-3 border border-gray-100">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Nilai Gadai</p>
                            <p class="font-black text-gray-900 text-lg tracking-tighter">Rp {{ number_format($gadai->nominal_deal, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-2xl p-3 border border-gray-100">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Jatuh Tempo</p>
                            <p class="font-black {{ $isTenggang ? 'text-red-600' : 'text-gray-900' }} text-lg tracking-tighter">{{ $gadai->tgl_jatuh_tempo->format('d M Y') }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('nasabah.gadai_baru.create-pengajuan', ['id' => $gadai->id, 'jenis' => 'lunas']) }}" 
                           class="flex flex-col items-center justify-center p-4 bg-emerald-50 hover:bg-emerald-100 border border-emerald-100 rounded-2xl transition-all active:scale-95 group">
                            <svg class="w-6 h-6 text-emerald-600 mb-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-[10px] font-black text-emerald-800 uppercase tracking-widest text-center">Tebus / Pelunasan</span>
                            <span class="text-[9px] text-emerald-600 font-bold mt-1">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</span>
                        </a>
                        
                        @if($gadai->jumlah_perpanjangan < $gadai->kategori->max_extend_default)
                        <a href="{{ route('nasabah.gadai_baru.create-pengajuan', ['id' => $gadai->id, 'jenis' => 'perpanjang']) }}" 
                           class="flex flex-col items-center justify-center p-4 bg-amber-50 hover:bg-amber-100 border border-amber-100 rounded-2xl transition-all active:scale-95 group">
                            <svg class="w-6 h-6 text-amber-600 mb-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            <span class="text-[10px] font-black text-amber-800 uppercase tracking-widest text-center">Perpanjang</span>
                            <span class="text-[9px] text-amber-600 font-bold mt-1">Rp {{ number_format($totalPerpanjang, 0, ',', '.') }}</span>
                        </a>
                        @else
                        <div class="flex flex-col items-center justify-center p-4 bg-gray-50 border border-gray-200 rounded-2xl opacity-60 cursor-not-allowed">
                            <svg class="w-6 h-6 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest text-center leading-tight">Batas Maks.<br>Perpanjangan</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Riwayat Aktivitas -->
    <div class="mx-4 mb-10 grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Riwayat Pelunasan & Perpanjangan -->
        <div class="bg-white rounded-3xl p-6 shadow-xl border border-gray-50">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-black text-gray-900 tracking-tight font-display">Riwayat Pengajuan</h3>
                <a href="{{ route('nasabah.gadai_baru.status-pengajuan') }}" class="text-[10px] font-black text-[#674c1d] uppercase tracking-widest hover:underline">Lihat Semua</a>
            </div>
            
            <div class="space-y-4">
                @forelse($pengajuanLunas->merge($pengajuanPerpanjang)->sortByDesc('created_at')->take(5) as $pengajuan)
                <div class="flex items-center justify-between p-3 rounded-2xl bg-gray-50 border border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ $pengajuan->jenis_pengajuan == 'lunas' ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600' }}">
                            @if($pengajuan->jenis_pengajuan == 'lunas')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            @endif
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $pengajuan->jenis_pengajuan }}</p>
                            <p class="text-xs font-bold text-gray-900 leading-tight">{{ $pengajuan->gadaiActive->item->nama_item ?? 'Gadai Item' }}</p>
                            <p class="text-[10px] text-gray-400">{{ $pengajuan->created_at->format('d M H:i') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-black text-gray-900 tracking-tighter">Rp {{ number_format($pengajuan->nominal, 0, ',', '.') }}</p>
                        <span class="text-[9px] font-black uppercase tracking-widest {{ $pengajuan->status == 'approved' ? 'text-emerald-600' : ($pengajuan->status == 'pending' ? 'text-amber-600' : 'text-red-600') }}">{{ $pengajuan->status }}</span>
                    </div>
                </div>
                @empty
                    <p class="text-center py-10 text-[10px] font-black text-gray-300 uppercase tracking-widest">Belum ada aktivitas pengajuan.</p>
                @endforelse
            </div>
        </div>

        <!-- Riwayat Gadai Selesai -->
        <div class="bg-white rounded-3xl p-6 shadow-xl border border-gray-50">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-black text-gray-900 tracking-tight font-display">Riwayat Gadai</h3>
                <a href="{{ route('nasabah.gadai_baru.riwayat') }}" class="text-[10px] font-black text-[#674c1d] uppercase tracking-widest hover:underline">Lihat Semua</a>
            </div>
            
            <div class="space-y-4">
                @forelse($gadaiSelesai as $selesai)
                <div class="flex items-center justify-between p-3 rounded-2xl bg-gray-50 border border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gray-100 text-gray-400 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $selesai->status }}</p>
                            <p class="text-xs font-bold text-gray-900 leading-tight">{{ $selesai->item->nama_item }}</p>
                            <p class="text-[10px] text-gray-400">{{ $selesai->updated_at->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-black text-gray-900 tracking-tighter">Rp {{ number_format($selesai->nominal_deal, 0, ',', '.') }}</p>
                        <span class="text-[9px] font-black uppercase tracking-widest text-gray-400">SELESAI</span>
                    </div>
                </div>
                @empty
                    <p class="text-center py-10 text-[10px] font-black text-gray-300 uppercase tracking-widest">Belum ada riwayat gadai selesai.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Kategori Gadai Baru -->
    <div class="mx-4 space-y-8">
        <div class="flex items-center gap-4 mb-4">
            <div class="h-px flex-1 bg-gray-200"></div>
            <h2 class="text-xs font-black text-gray-400 uppercase tracking-[0.3em] font-display">Ajukan Gadai Baru</h2>
            <div class="h-px flex-1 bg-gray-200"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Emas -->
            @if($kategoriEmas)
            <div class="space-y-4">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-sm font-black text-gray-800 uppercase tracking-widest">{{ $kategoriEmas->nama_kategori }}</h3>
                </div>
                @foreach($kategoriEmas->items->where('is_active', true) as $item)
                <a href="{{ route('nasabah.gadai_baru.show', ['kategori' => $kategoriEmas->kode_kategori, 'item' => $item->id]) }}" class="block bg-white p-4 rounded-3xl shadow-md border border-gray-100 hover:border-amber-300 transition-all group relative overflow-hidden">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-2xl bg-gray-50 flex-shrink-0 overflow-hidden border border-gray-100 group-hover:border-amber-200 transition-colors">
                            @if($item->file_pic)
                                <img src="{{ asset('storage/' . $item->file_pic) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                            @endif
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Taksiran s/d</p>
                            <p class="text-base font-black text-gray-900 tracking-tighter mb-0.5">Rp {{ number_format($item->nominal_high, 0, ',', '.') }}</p>
                            <p class="text-xs font-bold text-amber-600 line-clamp-1">{{ $item->head_1 }}</p>
                            @if($item->head_2)
                                <p class="text-[9px] text-gray-400 line-clamp-1 italic">{{ $item->head_2 }}</p>
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            @endif

            <!-- Elektronik -->
            @if($kategoriElektronik)
            <div class="space-y-4">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-sm font-black text-gray-800 uppercase tracking-widest">{{ $kategoriElektronik->nama_kategori }}</h3>
                </div>
                @foreach($kategoriElektronik->items->where('is_active', true) as $item)
                <a href="{{ route('nasabah.gadai_baru.show', ['kategori' => $kategoriElektronik->kode_kategori, 'item' => $item->id]) }}" class="block bg-white p-4 rounded-3xl shadow-md border border-gray-100 hover:border-blue-300 transition-all group relative overflow-hidden">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-2xl bg-gray-50 flex-shrink-0 overflow-hidden border border-gray-100 group-hover:border-blue-200 transition-colors">
                            @if($item->file_pic)
                                <img src="{{ asset('storage/' . $item->file_pic) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Taksiran s/d</p>
                            <p class="text-base font-black text-gray-900 tracking-tighter mb-0.5">Rp {{ number_format($item->nominal_high, 0, ',', '.') }}</p>
                            <p class="text-xs font-bold text-blue-600 line-clamp-1">{{ $item->head_1 }}</p>
                            @if($item->head_2)
                                <p class="text-[9px] text-gray-400 line-clamp-1 italic">{{ $item->head_2 }}</p>
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            @endif

            <!-- Kendaraan -->
            @if($kategoriKendaraan)
            <div class="space-y-4">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 bg-green-100 text-green-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    </div>
                    <h3 class="text-sm font-black text-gray-800 uppercase tracking-widest">{{ $kategoriKendaraan->nama_kategori }}</h3>
                </div>
                @foreach($kategoriKendaraan->items->where('is_active', true) as $item)
                <a href="{{ route('nasabah.gadai_baru.show', ['kategori' => $kategoriKendaraan->kode_kategori, 'item' => $item->id]) }}" class="block bg-white p-4 rounded-3xl shadow-md border border-gray-100 hover:border-green-300 transition-all group relative overflow-hidden">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-2xl bg-gray-50 flex-shrink-0 overflow-hidden border border-gray-100 group-hover:border-green-200 transition-colors">
                            @if($item->file_pic)
                                <img src="{{ asset('storage/' . $item->file_pic) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                </div>
                            @endif
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Taksiran s/d</p>
                            <p class="text-base font-black text-gray-900 tracking-tighter mb-0.5">Rp {{ number_format($item->nominal_high, 0, ',', '.') }}</p>
                            <p class="text-xs font-bold text-green-600 line-clamp-1">{{ $item->head_1 }}</p>
                            @if($item->head_2)
                                <p class="text-[9px] text-gray-400 line-clamp-1 italic">{{ $item->head_2 }}</p>
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
