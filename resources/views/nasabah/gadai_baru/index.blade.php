@extends('layouts.nasabah')

@section('title', 'Layanan Gadai')

@section('content')
<div class="w-full pb-10">

    {{-- Header Premium --}}
    <div class="mx-4 mt-4 mb-6">
        <div class="bg-gradient-to-br from-[#674c1d] via-[#8b6f2f] to-[#d4af37] rounded-3xl shadow-2xl p-7 border border-[#d4af37]/30 relative overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white rounded-full -mr-32 -mt-32"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-white rounded-full -ml-24 -mb-24"></div>
            </div>
            <div class="relative z-10 flex items-center justify-between flex-wrap gap-4">
                <div>
                    <p class="text-white/80 text-xs font-black uppercase tracking-widest mb-1">Layanan Cepat & Aman</p>
                    <h1 class="text-3xl font-black text-white font-display leading-tight">Gadai Majakara</h1>
                    <p class="text-white/70 text-sm mt-1.5 max-w-sm">Solusi pendanaan cepat dengan jaminan barang berharga Anda.</p>
                </div>
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/30 shrink-0">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Session Alerts --}}
    @foreach(['success' => 'green', 'warning' => 'amber', 'error' => 'red'] as $type => $color)
    @if(session($type))
    <div class="mx-4 mb-4">
        <div class="bg-{{ $color }}-50 border border-{{ $color }}-200 rounded-2xl p-4 text-{{ $color }}-700 text-sm font-bold shadow-sm">{{ session($type) }}</div>
    </div>
    @endif
    @endforeach

    <div class="flex flex-col">
        {{-- ===== GADAI AKTIF ===== --}}
        <div class="order-{{ $gadaiAktif->isEmpty() ? '2' : '1' }}">
            <div class="mx-4 mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-black text-gray-900 tracking-tight font-display">Gadai Aktif Saya</h2>
                    <span class="px-3 py-1 bg-amber-100 text-[#674c1d] text-[10px] font-black rounded-full border border-amber-200">{{ $gadaiAktif->count() }} Aktif</span>
                </div>
                @if($gadaiAktif->isEmpty())
                <div>
                    <p class="text-gray-500 font-bold">Tidak ada gadai aktif.</p>
                    <p class="text-xs text-gray-400 mt-1">Pilih kategori di bawah untuk pengajuan baru.</p>
                </div>
                @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @foreach($gadaiAktif as $gadai)
                    @php
                    $isLunas = $gadai->status == 'lunas';
                    $isTenggang = $gadai->status == 'grace_period';
                    $today = now()->startOfDay();
                    $jatuhTempo = \Carbon\Carbon::parse($gadai->tgl_jatuh_tempo)->startOfDay();
                    $tenggang   = \Carbon\Carbon::parse($gadai->tgl_tenggang)->startOfDay();
                    $sisaHari   = (int) ($isTenggang ? $today->diffInDays($tenggang, false) : $today->diffInDays($jatuhTempo, false));
                    $totalTagihan   = $gadai->nominal_deal + $gadai->biaya_jasa + $gadai->biaya_inap + ($gadai->extra_pinjaman_nominal ?? 0);
                    $totalPerpanjang = $gadai->biaya_jasa + $gadai->denda_aktif + $gadai->biaya_inap;
                    // Progress bar
                    $mulai = \Carbon\Carbon::parse($gadai->tgl_mulai)->startOfDay();
                    $totalDays = max(1, (int) $mulai->diffInDays($jatuhTempo));
                    $elapsedDays = min($totalDays, (int) $mulai->diffInDays($today));
                    $progressPct = round(($elapsedDays / $totalDays) * 100);
                    $progressColor = $progressPct >= 90 ? 'from-red-500 to-red-600' : ($progressPct >= 70 ? 'from-amber-400 to-amber-500' : 'from-emerald-400 to-emerald-500');
                    // Format helper
                    $formatSisa = function($hari) {
                        $abs = abs((int) $hari);
                        $bulan = (int) floor($abs / 30);
                        $sisa  = $abs % 30;
                        if ($bulan > 0 && $sisa > 0) return "{$bulan} bulan {$sisa} hari";
                        if ($bulan > 0) return "{$bulan} bulan";
                        return "{$sisa} hari";
                    };
                @endphp
                <div class="bg-white rounded-3xl shadow-md border {{ $isLunas ? 'border-emerald-200' : ($isTenggang ? 'border-red-200' : 'border-gray-100') }} relative overflow-hidden group hover:shadow-xl transition-all duration-300">
                    {{-- Urgency ribbon --}}
                    @if($isLunas)
                    <div class="bg-gradient-to-r from-emerald-600 to-teal-500 text-white text-[10px] font-black text-center py-1.5 tracking-widest uppercase animate-pulse">
                        🎉 Pelunasan Disetujui &mdash; Silakan Ambil Barang di Toko
                    </div>
                    @elseif($isTenggang)
                    <div class="bg-red-600 text-white text-[10px] font-black text-center py-1.5 tracking-widest uppercase">
                        ⚠ MASA TENGGANG &mdash; {{ $sisaHari >= 0 ? $formatSisa($sisaHari) : '0 hari' }} TERSISA
                    </div>
                    @elseif($sisaHari <= 7 && $sisaHari >= 0)
                    <div class="bg-amber-500 text-white text-[10px] font-black text-center py-1.5 tracking-widest uppercase">
                        ⚡ Jatuh tempo dalam {{ $formatSisa($sisaHari) }} &mdash; segera tindak lanjuti
                    </div>
                    @endif

                    <div class="p-6">
                        {{-- Header card --}}
                        <div class="flex justify-between items-start mb-4">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $gadai->kategori->nama_kategori }}</span>
                                    <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                                    <span class="text-[9px] font-black text-[#674c1d] uppercase tracking-widest font-mono">{{ $gadai->slot_kode }}</span>
                                </div>
                                <a href="{{ route('nasabah.gadai_baru.aktif-detail', $gadai->id) }}" class="group/t flex items-center gap-1.5">
                                    <h3 class="font-black text-gray-900 text-lg leading-tight group-hover/t:text-[#674c1d] transition-colors">{{ $gadai->nama_barang_display }}</h3>
                                    <svg class="w-4 h-4 text-gray-400 group-hover/t:text-[#674c1d] group-hover/t:translate-x-0.5 transition-all shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                                @if(!$gadai->nama_barang_manual && $gadai->item->head_2)
                                    <p class="text-[10px] text-gray-400 mt-1 italic">{{ $gadai->item->head_2 }}</p>
                                @endif
                            </div>
                            <span class="{{ $isLunas ? 'bg-emerald-600 shadow-emerald-200' : ($isTenggang ? 'bg-red-600 shadow-red-200' : 'bg-[#674c1d] shadow-amber-200') }} text-white text-[9px] font-black px-3 py-1 rounded-full shadow-lg shrink-0 animate-pulse">
                                {{ $isLunas ? 'SIAP DIAMBIL' : ($isTenggang ? 'TENGGANG' : 'AKTIF') }}
                            </span>
                        </div>

                        {{-- Stats --}}
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div class="bg-gray-50 rounded-2xl p-3 border border-gray-100">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Nilai Gadai</p>
                                <p class="font-black text-gray-900 text-base tracking-tight">Rp {{ number_format($gadai->nominal_deal, 0, ',', '.') }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-2xl p-3 border border-gray-100">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">
                                    {{ $isLunas ? 'Batas Pengambilan' : ($isTenggang ? 'Batas Tenggang' : 'Jatuh Tempo') }}
                                </p>
                                <p class="font-black {{ $isLunas ? 'text-emerald-600' : ($isTenggang ? 'text-red-600' : 'text-gray-900') }} text-base tracking-tight">
                                    {{ ($isLunas ? $gadai->tgl_ambil_limit : ($isTenggang ? $gadai->tgl_tenggang : $gadai->tgl_jatuh_tempo))->format('d M Y') }}
                                </p>
                            </div>
                        </div>

                        @if($isLunas)
                        {{-- Countdown JS Display --}}
                        <div class="mb-5 bg-amber-50 border border-amber-200 rounded-2xl p-3 text-center">
                            <p class="text-[9px] font-black text-amber-800 uppercase tracking-widest mb-1">⏱️ Sisa Waktu Pengambilan</p>
                            <p class="text-sm font-black text-amber-950 tracking-tight" id="countdown-{{ $gadai->id }}">-- H -- M -- S</p>
                            <script>
                                (function() {
                                    const limitTime = new Date("{{ $gadai->tgl_ambil_limit->toIso8601String() }}").getTime();
                                    const timerId = setInterval(function() {
                                        const now = new Date().getTime();
                                        const distance = limitTime - now;
                                        if (distance < 0) {
                                            clearInterval(timerId);
                                            document.getElementById("countdown-{{ $gadai->id }}").innerHTML = "HANGUS / SELESAI";
                                            return;
                                        }
                                        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                        document.getElementById("countdown-{{ $gadai->id }}").innerHTML = 
                                            (days > 0 ? days + " Hari " : "") + hours + " Jam " + minutes + " Menit " + seconds + " Detik";
                                    }, 1000);
                                })();
                            </script>
                        </div>
                        @else
                        {{-- Progress timeline --}}
                        <div class="mb-5">
                            <div class="flex justify-between text-[9px] font-bold text-gray-400 mb-1.5">
                                <span>{{ \Carbon\Carbon::parse($gadai->tgl_mulai)->format('d M Y') }}</span>
                                <span class="hidden sm:inline {{ $progressPct >= 80 ? 'text-red-500' : 'text-gray-400' }}">{{ $progressPct }}% berlalu</span>
                                <span>{{ $jatuhTempo->format('d M Y') }}</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                                <div class="h-2.5 rounded-full bg-gradient-to-r {{ $progressColor }} transition-all duration-700" style="width: {{ min(100, $progressPct) }}%"></div>
                            </div>
                        </div>
                        @endif

                        @if($isLunas)
                        <div class="w-full py-3.5 bg-gradient-to-r from-emerald-600 to-teal-500 text-white text-center font-black rounded-2xl text-xs uppercase tracking-widest shadow-md">
                            Ambil di Cabang (Bawa KTP)
                        </div>
                        @else
                        {{-- CTA Buttons --}}
                        <div class="grid grid-cols-2 gap-3">
                            <a href="{{ route('nasabah.gadai_baru.create-pengajuan', ['id' => $gadai->id, 'jenis' => 'lunas']) }}"
                               class="flex items-center justify-center gap-2 py-3 min-h-[44px] bg-emerald-600 hover:bg-emerald-700 text-white font-black rounded-2xl text-xs uppercase tracking-widest shadow-md shadow-emerald-600/20 active:scale-95 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Tebus
                            </a>
                            @if($gadai->jumlah_perpanjangan < $gadai->kategori->max_extend_default)
                            <a href="{{ route('nasabah.gadai_baru.create-pengajuan', ['id' => $gadai->id, 'jenis' => 'perpanjang']) }}"
                               class="flex items-center justify-center gap-2 py-3 min-h-[44px] bg-amber-500 hover:bg-amber-600 text-white font-black rounded-2xl text-xs uppercase tracking-widest shadow-md shadow-amber-500/20 active:scale-95 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                Perpanjang
                            </a>
                            @else
                            <div class="flex flex-col items-center justify-center py-3.5 bg-gray-100 border border-gray-200 rounded-2xl opacity-60 cursor-not-allowed">
                                <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest text-center">Batas Maks. Perpanjangan</span>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif
            </div>
        </div>

        {{-- ===== AJUKAN GADAI BARU ===== --}}
        <div class="order-{{ $gadaiAktif->isEmpty() ? '1' : '2' }}">
            <div class="mx-4 mb-10 space-y-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-4 flex-1">
                        <div class="h-px flex-1 bg-gradient-to-r from-transparent to-gray-200 hidden md:block"></div>
                        <h2 class="text-xl font-black text-gray-900 tracking-tight font-display whitespace-nowrap">Ajukan Gadai Baru</h2>
                        <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200 hidden md:block"></div>
                    </div>
                    
                    {{-- Search Bar --}}
                    <div class="relative w-full md:w-80 shrink-0 group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400 group-focus-within:text-[#674c1d] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" id="searchInput" placeholder="Cari barang (Emas, Laptop...)" class="w-full pl-11 pr-4 py-3.5 bg-white border border-gray-200 rounded-2xl text-sm font-bold text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-[#d4af37]/20 focus:border-[#d4af37] transition-all shadow-sm">
                        <button type="button" id="clearSearch" class="absolute inset-y-0 right-0 pr-4 flex items-center hidden hover:opacity-70 transition-opacity">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                <div id="searchResultsEmpty" class="hidden bg-white rounded-3xl p-10 shadow-sm border border-gray-100 text-center flex flex-col items-center gap-5">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mx-auto relative">
                        <div class="absolute inset-0 bg-amber-500/10 rounded-full animate-ping opacity-50"></div>
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-gray-900 font-black text-lg mb-1">Barang tidak ditemukan.</p>
                        <p class="text-xs text-gray-500 mb-5">Coba gunakan kata kunci lain, atau hubungi admin jika barang Anda belum terdaftar.</p>
                        <a href="https://wa.me/628139552626?text=Halo%20Admin%2C%20saya%20ingin%20mengajukan%20gadai%20untuk%20barang%20yang%20belum%20ada%20di%20daftar." target="_blank" 
                           class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-black rounded-2xl text-xs uppercase tracking-widest shadow-md shadow-emerald-500/20 active:scale-95 transition-all">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/></svg>
                            Tanya Admin via WhatsApp
                        </a>
                    </div>
                </div>

                <div class="flex flex-col gap-8" id="gadaiItemsContainer">
                    @php
                        $katConfig = [
                            'Emas'       => ['var' => $kategoriEmas,       'color' => 'amber',  'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                            'Elektronik' => ['var' => $kategoriElektronik, 'color' => 'blue',   'icon' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                            'Kendaraan'  => ['var' => $kategoriKendaraan,  'color' => 'green',  'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4'],
                        ];
                    @endphp
                    @foreach($katConfig as $label => $cfg)
                    @if($cfg['var'] && $cfg['var']->items->where('is_active', true)->isNotEmpty())
                    <div class="kategori-group" data-kategori="{{ strtolower($cfg['var']->nama_kategori) }}">
                        <div class="flex items-center justify-between mb-4 px-1">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] text-white rounded-xl flex items-center justify-center shadow-md shadow-[#d4af37]/20">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $cfg['icon'] }}"/></svg>
                                </div>
                                <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">{{ $cfg['var']->nama_kategori }}</h3>
                            </div>
                            <span class="text-[10px] text-gray-400 font-bold bg-gray-100 px-2.5 py-1 rounded-full">{{ $cfg['var']->items->where('is_active', true)->count() }} Item</span>
                        </div>
                        
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 sm:gap-4 px-1">
                            @foreach($cfg['var']->items->where('is_active', true) as $item)
                            <a href="{{ route('nasabah.gadai_baru.show', ['kategori' => $cfg['var']->kode_kategori, 'item' => $item->id]) }}"
                               class="gadai-item flex flex-col bg-white rounded-3xl shadow-sm border border-gray-100 hover:border-[#d4af37]/50 hover:shadow-lg hover:shadow-[#d4af37]/10 transition-all duration-300 group overflow-hidden relative"
                               data-search="{{ strtolower($item->head_1 . ' ' . $item->head_2 . ' ' . $item->nama_item) }}">
                                
                                {{-- Image Section (Dominant) --}}
                                <div class="w-full aspect-[4/3] bg-gray-50 relative overflow-hidden flex-shrink-0">
                                    @if($item->file_pic)
                                        <img src="{{ asset('storage/' . $item->file_pic) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-300 bg-gradient-to-br from-gray-50 to-gray-100">
                                            <svg class="w-10 h-10 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $cfg['icon'] }}"/></svg>
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                </div>

                                {{-- Content Section --}}
                                <div class="p-3.5 flex-1 flex flex-col">
                                    <div class="flex items-center justify-between gap-1 mb-1.5">
                                        <p class="text-[9px] font-black text-[#8b6f2f] uppercase tracking-widest leading-none">Taksiran s/d</p>
                                    </div>
                                    <p class="text-sm sm:text-base font-black text-gray-900 tracking-tight leading-tight mb-2">Rp {{ number_format($item->nominal_high, 0, ',', '.') }}</p>
                                    
                                    <div class="mt-auto">
                                        <p class="text-[11px] sm:text-xs font-bold text-gray-700 leading-snug line-clamp-2 group-hover:text-[#674c1d] transition-colors">{{ $item->head_1 ?? $item->nama_item }}</p>
                                        @if($item->head_2)
                                            <p class="text-[10px] text-gray-400 line-clamp-1 mt-1">{{ $item->head_2 }}</p>
                                        @endif
                                    </div>
                                </div>
                                
                                {{-- Subtle hover arrow --}}
                                <div class="absolute bottom-3 right-3 w-6 h-6 rounded-full bg-[#d4af37] text-white flex items-center justify-center opacity-0 group-hover:opacity-100 translate-y-2 group-hover:translate-y-0 transition-all duration-300 shadow-md">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ===== RIWAYAT ===== --}}
        <div class="order-3 mx-4 mb-10 grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-3xl p-6 shadow-md border border-gray-50">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-base font-black text-gray-900 tracking-tight">Riwayat Pengajuan</h3>
                    <a href="{{ route('nasabah.gadai_baru.status-pengajuan') }}" class="text-[10px] font-black text-[#674c1d] uppercase tracking-widest hover:underline">Lihat Semua</a>
                </div>
                <div class="space-y-3">
                    @forelse($pengajuanLunas->merge($pengajuanPerpanjang)->sortByDesc('created_at')->take(5) as $pengajuan)
                    <div class="flex items-center justify-between p-3 rounded-2xl bg-gray-50 border border-gray-100 hover:bg-gray-100 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 {{ $pengajuan->jenis_pengajuan == 'lunas' ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600' }}">
                                @if($pengajuan->jenis_pengajuan == 'lunas')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                @endif
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $pengajuan->jenis_pengajuan }}</p>
                                <p class="text-xs font-bold text-gray-900 leading-tight">{{ $pengajuan->gadaiActive->item->nama_item ?? 'Gadai Item' }}</p>
                                <p class="text-[9px] text-gray-400">{{ $pengajuan->created_at->format('d M H:i') }}</p>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-xs font-black text-gray-900">Rp {{ number_format($pengajuan->nominal, 0, ',', '.') }}</p>
                            <span class="text-[9px] font-black uppercase {{ $pengajuan->status == 'approved' ? 'text-emerald-600' : ($pengajuan->status == 'pending' ? 'text-amber-600' : 'text-red-600') }}">{{ $pengajuan->status }}</span>
                        </div>
                    </div>
                    @empty
                        <p class="text-center py-10 text-[10px] font-black text-gray-300 uppercase tracking-widest">Belum ada aktivitas pengajuan.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-md border border-gray-50">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-base font-black text-gray-900 tracking-tight">Riwayat Gadai Selesai</h3>
                    <a href="{{ route('nasabah.gadai_baru.riwayat') }}" class="text-[10px] font-black text-[#674c1d] uppercase tracking-widest hover:underline">Lihat Semua</a>
                </div>
                <div class="space-y-3">
                    @forelse($gadaiSelesai as $selesai)
                    <div class="flex items-center justify-between p-3 rounded-2xl bg-gray-50 border border-gray-100 hover:bg-gray-100 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-gray-100 text-gray-400 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $selesai->status }}</p>
                                <p class="text-xs font-bold text-gray-900 leading-tight">{{ $selesai->item->nama_item }}</p>
                                <p class="text-[9px] text-gray-400">{{ $selesai->updated_at->format('d M Y') }}</p>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-xs font-black text-gray-900">Rp {{ number_format($selesai->nominal_deal, 0, ',', '.') }}</p>
                            <span class="text-[9px] font-black uppercase text-gray-400">SELESAI</span>
                        </div>
                    </div>
                    @empty
                        <p class="text-center py-10 text-[10px] font-black text-gray-300 uppercase tracking-widest">Belum ada riwayat gadai selesai.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const clearBtn = document.getElementById('clearSearch');
        const gadaiItems = document.querySelectorAll('.gadai-item');
        const kategoriGroups = document.querySelectorAll('.kategori-group');
        const emptyState = document.getElementById('searchResultsEmpty');
        const container = document.getElementById('gadaiItemsContainer');

        function performSearch() {
            const query = searchInput.value.toLowerCase().trim();
            let totalVisible = 0;
            
            if (query.length > 0) {
                clearBtn.classList.remove('hidden');
            } else {
                clearBtn.classList.add('hidden');
            }

            // Loop per kategori
            kategoriGroups.forEach(group => {
                let groupHasVisibleItems = false;
                const itemsInGroup = group.querySelectorAll('.gadai-item');
                
                itemsInGroup.forEach(item => {
                    const searchData = item.getAttribute('data-search');
                    if (searchData.includes(query)) {
                        item.classList.remove('hidden');
                        groupHasVisibleItems = true;
                        totalVisible++;
                    } else {
                        item.classList.add('hidden');
                    }
                });
                
                // Sembunyikan kategori jika tidak ada item yang cocok di dalamnya
                if (groupHasVisibleItems) {
                    group.classList.remove('hidden');
                } else {
                    group.classList.add('hidden');
                }
            });

            if (totalVisible === 0) {
                emptyState.classList.remove('hidden');
                container.classList.add('hidden');
            } else {
                emptyState.classList.add('hidden');
                container.classList.remove('hidden');
            }
        }

        if (searchInput) {
            searchInput.addEventListener('input', performSearch);
            
            clearBtn.addEventListener('click', function() {
                searchInput.value = '';
                performSearch();
                searchInput.focus();
            });
        }
    });
</script>
@endpush

@if(isset($hasAgreed) && !$hasAgreed)
<!-- Syarat & Ketentuan Gadai Modal -->
<div id="gadai-terms-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[100] flex items-center justify-center p-4" style="z-index: 100;">
    <div class="bg-white rounded-3xl shadow-2xl max-w-xl w-full p-6 md:p-8 flex flex-col max-h-[90vh] border border-gray-100">
        <div class="flex items-center gap-3 pb-4 border-b border-gray-100 mb-5">
            <div class="w-12 h-12 bg-gradient-to-br from-[#8b6f2f] to-[#d4af37] rounded-xl flex items-center justify-center shadow-md">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900 font-display">Syarat & Ketentuan Gadai</h3>
                <p class="text-xs text-gray-500">Koperasi Majakara</p>
            </div>
        </div>

        <!-- Scrollable T&C Content Box -->
        <div id="gadai-terms-content-box" class="flex-1 overflow-y-auto max-h-[45vh] sm:max-h-[350px] border border-gray-200 rounded-2xl p-4 bg-gray-50 text-sm text-gray-700 leading-relaxed space-y-4 mb-5">
            {!! $syaratGadai !!}
        </div>

        <!-- Bottom Actions -->
        <div class="pt-4 border-t border-gray-100 space-y-4">
            <!-- Checkbox -->
            <label class="flex items-start gap-3 cursor-pointer group">
                <input type="checkbox" id="gadai-terms-checkbox" disabled
                    class="mt-1 w-4 h-4 text-[#8b6f2f] border-gray-300 rounded focus:ring-[#8b6f2f] focus:ring-2 disabled:opacity-50 disabled:cursor-not-allowed">
                <span id="gadai-terms-checkbox-label" class="text-xs text-gray-500 select-none group-hover:text-gray-700 font-medium">
                    Harap gulir syarat dan ketentuan sampai ke bagian paling bawah untuk menyetujui.
                </span>
            </label>

            <!-- Buttons -->
            <div class="flex gap-3">
                <button type="button" id="btn-agree-gadai-terms" disabled
                    class="flex-1 py-3 px-5 bg-gray-300 text-gray-500 rounded-xl font-bold transition-all text-center cursor-not-allowed shadow-md">
                    Setuju & Lanjutkan
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    initGadaiTermsModal();
});

document.addEventListener('turbo:load', function() {
    initGadaiTermsModal();
});

function initGadaiTermsModal() {
    const termsBox = document.getElementById('gadai-terms-content-box');
    const checkbox = document.getElementById('gadai-terms-checkbox');
    const label = document.getElementById('gadai-terms-checkbox-label');
    const btnAgree = document.getElementById('btn-agree-gadai-terms');
    const modal = document.getElementById('gadai-terms-modal');

    if (!termsBox || !checkbox || !btnAgree) return;

    // Check if content height is less than client height (fully visible without scroll)
    if (termsBox.scrollHeight <= termsBox.clientHeight) {
        enableCheckbox();
    } else {
        // Detect scroll to bottom
        termsBox.addEventListener('scroll', function scrollHandler() {
            // Give 10px buffer to prevent precision issues on mobile zoom
            if (termsBox.scrollHeight - termsBox.scrollTop - termsBox.clientHeight < 15) {
                enableCheckbox();
                termsBox.removeEventListener('scroll', scrollHandler);
            }
        });
    }

    function enableCheckbox() {
        checkbox.removeAttribute('disabled');
        if (label) {
            label.textContent = "Saya telah membaca, memahami, dan menyetujui seluruh Syarat & Ketentuan di atas.";
            label.classList.remove('text-gray-500');
            label.classList.add('text-gray-800');
        }
    }

    checkbox.addEventListener('change', function() {
        if (checkbox.checked) {
            btnAgree.removeAttribute('disabled');
            btnAgree.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
            btnAgree.classList.add('bg-gradient-to-r', 'from-[#674c1d]', 'to-[#8b6f2f]', 'text-white', 'hover:shadow-lg');
            btnAgree.style.cursor = 'pointer';
        } else {
            btnAgree.setAttribute('disabled', 'true');
            btnAgree.classList.add('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
            btnAgree.classList.remove('bg-gradient-to-r', 'from-[#674c1d]', 'to-[#8b6f2f]', 'text-white', 'hover:shadow-lg');
            btnAgree.style.cursor = 'not-allowed';
        }
    });

    btnAgree.addEventListener('click', function() {
        if (btnAgree.getAttribute('disabled') === 'true' || !checkbox.checked) return;

        btnAgree.setAttribute('disabled', 'true');
        btnAgree.textContent = 'Menyimpan...';

        fetch('{{ route("nasabah.gadai_baru.agree-terms") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                modal.classList.add('hidden');
                Swal.fire({
                    icon: 'success',
                    title: 'Persetujuan Disimpan',
                    text: 'Syarat & Ketentuan telah disetujui.',
                    showConfirmButton: false,
                    timer: 1500
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: data.message || 'Gagal menyimpan persetujuan.',
                    confirmButtonText: 'Coba Lagi'
                });
                btnAgree.removeAttribute('disabled');
                btnAgree.textContent = 'Setuju & Lanjutkan';
            }
        })
        .catch(err => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan jaringan.',
                confirmButtonText: 'Coba Lagi'
            });
            btnAgree.removeAttribute('disabled');
            btnAgree.textContent = 'Setuju & Lanjutkan';
        });
    });
}
</script>
@endpush
@endif
@endsection
