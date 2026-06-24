@extends('layouts.admin')

@section('title', 'Kelola Gadai Fisik')

@section('content')
<div class="space-y-6">

    {{-- ===== PAGE HEADER ===== --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Kelola Gadai Fisik</h1>
            <p class="text-gray-500 mt-1 text-sm">Monitor dan kelola seluruh transaksi gadai nasabah</p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('admin.gadai_baru.storage') }}"
                class="flex items-center gap-2 px-4 py-2.5 bg-white/80 backdrop-blur-xl text-gray-700 border border-white/60 font-medium rounded-xl hover:bg-white/90 hover:shadow-md transition-all shadow-sm text-sm">
                <svg class="w-4 h-4 text-majakara-brown" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                <span>Peta Storage</span>
            </a>
            <a href="{{ route('admin.gadai_baru.pengajuan.index') }}"
                class="flex items-center gap-2 px-4 py-2.5 bg-white/80 backdrop-blur-xl text-gray-700 border border-white/60 font-medium rounded-xl hover:bg-white/90 hover:shadow-md transition-all shadow-sm text-sm">
                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <span>Antrean Pengajuan</span>
            </a>
            <a href="{{ route('admin.gadai_baru.create') }}"
                class="flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-majakara-brown to-majakara-dark-gold text-white font-medium rounded-xl shadow-lg shadow-majakara-brown/30 hover:shadow-xl hover:shadow-majakara-brown/40 transition-all hover:-translate-y-0.5 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Terima Gadai Baru</span>
            </a>
        </div>
    </div>

    {{-- ===== STAT SUMMARY CARDS ===== --}}
    @php
        $statAktif      = $globalStats['active'] ?? 0;
        $statTenggang   = $globalStats['grace_period'] ?? 0;
        $statHangus     = $globalStats['expired_final'] ?? 0;
        $statLunas      = $globalStats['lunas'] ?? 0;
        $statReturned   = $globalStats['returned'] ?? 0;
        $statAuctioned  = $globalStats['auctioned'] ?? 0;
        $totalAll       = array_sum($globalStats);
    @endphp
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4">
        <x-admin.stats-card 
            title="Aktif" 
            value="{{ $statAktif }}" 
            iconBg="bg-blue-50"
            iconColor="text-blue-600"
            iconHoverColor="group-hover:text-blue-700"
            iconHoverBorder="group-hover:border-blue-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
        </x-admin.stats-card>

        <x-admin.stats-card 
            title="Masa Tenggang" 
            value="{{ $statTenggang }}" 
            iconBg="bg-red-50"
            iconColor="text-red-600"
            iconHoverColor="group-hover:text-red-700"
            iconHoverBorder="group-hover:border-red-200">
            <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </x-admin.stats-card>

        <x-admin.stats-card 
            title="Hangus / Lelang" 
            value="{{ $statHangus }}" 
            iconBg="bg-amber-50"
            iconColor="text-amber-600"
            iconHoverColor="group-hover:text-amber-700"
            iconHoverBorder="group-hover:border-amber-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
        </x-admin.stats-card>

        <x-admin.stats-card 
            title="Lunas" 
            value="{{ $statLunas }}" 
            iconBg="bg-emerald-50"
            iconColor="text-emerald-600"
            iconHoverColor="group-hover:text-emerald-700"
            iconHoverBorder="group-hover:border-emerald-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
        </x-admin.stats-card>

        <x-admin.stats-card 
            title="Telah Diambil" 
            value="{{ $statReturned }}" 
            iconBg="bg-indigo-50"
            iconColor="text-indigo-600"
            iconHoverColor="group-hover:text-indigo-700"
            iconHoverBorder="group-hover:border-indigo-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
        </x-admin.stats-card>

        <x-admin.stats-card 
            title="Dilelang" 
            value="{{ $statAuctioned }}" 
            iconBg="bg-purple-50"
            iconColor="text-purple-600"
            iconHoverColor="group-hover:text-purple-700"
            iconHoverBorder="group-hover:border-purple-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/></svg>
        </x-admin.stats-card>
    </div>

    {{-- ===== FILTER SECTION ===== --}}
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/60 overflow-hidden">
        <div class="px-6 py-4 border-b border-white/60 bg-white/40 flex items-center gap-2">
            <svg class="w-4 h-4 text-majakara-brown" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
            <h3 class="font-bold text-gray-800 text-sm">Filter Pencarian</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.gadai_baru.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Kategori</label>
                    <select name="kategori" class="w-full border-white/60 bg-white/50 backdrop-blur-sm rounded-xl focus:ring-majakara-brown focus:border-majakara-brown text-sm transition-colors shadow-sm">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoriList as $kat)
                        <option value="{{ $kat->kode_kategori }}" {{ request('kategori') == $kat->kode_kategori ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Cabang</label>
                    <select name="cabang" class="w-full border-white/60 bg-white/50 backdrop-blur-sm rounded-xl focus:ring-majakara-brown focus:border-majakara-brown text-sm transition-colors shadow-sm">
                        <option value="">Semua Cabang</option>
                        @foreach($lokasiList as $lok)
                        <option value="{{ $lok->id }}" {{ request('cabang') == $lok->id ? 'selected' : '' }}>{{ $lok->nama_lokasi }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Status</label>
                    <select name="status" class="w-full border-white/60 bg-white/50 backdrop-blur-sm rounded-xl focus:ring-majakara-brown focus:border-majakara-brown text-sm transition-colors shadow-sm">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="grace_period" {{ request('status') == 'grace_period' ? 'selected' : '' }}>Tenggang</option>
                        <option value="expired_final" {{ request('status') == 'expired_final' ? 'selected' : '' }}>Hangus</option>
                        <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 flex justify-center items-center gap-2 px-4 py-2.5 bg-gray-800 hover:bg-gray-900 text-white font-medium rounded-xl transition-colors shadow-sm text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Terapkan
                    </button>
                    @if(request()->hasAny(['kategori','cabang','status']))
                    <a href="{{ route('admin.gadai_baru.index') }}" class="px-3 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl transition-colors text-sm font-medium" title="Reset Filter">✕</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- ===== DATA TABLE ===== --}}
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/60 overflow-hidden">
        <div class="px-6 py-4 border-b border-white/60 bg-white/40 flex items-center justify-between">
            <h3 class="font-bold text-gray-800 text-sm">Daftar Gadai <span class="text-gray-400 font-normal">({{ $totalAll }} data)</span></h3>
            {{-- Legend --}}
            <div class="hidden md:flex items-center gap-3 text-[10px] font-bold text-gray-500">
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-sm bg-red-100 border border-red-200"></span> Tenggang</span>
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-sm bg-amber-50 border border-amber-200"></span> Hangus</span>
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-sm bg-emerald-50 border border-emerald-200"></span> Lunas</span>
            </div>
        </div>
        <div class="overflow-hidden md:overflow-x-auto">
            <!-- Table View for Desktop -->
            <table class="w-full text-left border-collapse hidden md:table">
                <thead>
                    <tr class="bg-white/40 border-b border-white/60">
                        <th class="px-6 py-3.5 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Nasabah</th>
                        <th class="px-6 py-3.5 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Kategori & Item</th>
                        <th class="px-6 py-3.5 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Kode Slot</th>
                        <th class="px-6 py-3.5 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Nominal Deal</th>
                        <th class="px-6 py-3.5 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Jatuh Tempo / Sisa Hari</th>
                        <th class="px-6 py-3.5 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-[10px] font-bold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($gadiList as $gadai)
                    @php
                        $rowBg = '';
                        $today = now()->startOfDay();
                        if ($gadai->status == 'grace_period') {
                            $rowBg = 'bg-red-50/60';
                            $sisaHari = (int) $today->diffInDays(\Carbon\Carbon::parse($gadai->tgl_tenggang)->startOfDay(), false);
                        } elseif ($gadai->status == 'expired_final') {
                            $rowBg = 'bg-amber-50/60';
                            $sisaHari = null;
                        } elseif ($gadai->status == 'lunas') {
                            $rowBg = 'bg-emerald-50/40';
                            $sisaHari = null;
                        } else {
                            $sisaHari = (int) $today->diffInDays(\Carbon\Carbon::parse($gadai->tgl_jatuh_tempo)->startOfDay(), false);
                        }
                        // Format: "X bulan Y hari" atau "Y hari"
                        $formatSisa = function($hari) {
                            $abs = abs((int) $hari);
                            $bulan = (int) floor($abs / 30);
                            $sisa  = $abs % 30;
                            if ($bulan > 0 && $sisa > 0) return "{$bulan} bulan {$sisa} hari";
                            if ($bulan > 0) return "{$bulan} bulan";
                            return "{$sisa} hari";
                        };
                    @endphp
                    <tr class="hover:bg-slate-50/80 transition-colors group {{ $rowBg }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-majakara-brown to-majakara-dark-gold text-white flex items-center justify-center font-bold text-sm shadow-sm shrink-0">
                                    {{ substr($gadai->nasabah->user->nama ?? 'N', 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 text-sm">{{ $gadai->nasabah->user->nama ?? 'Tanpa Nama' }}</p>
                                    <p class="text-[10px] text-gray-400 mt-0.5">{{ $gadai->nasabah->user->nomor_hp ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[9px] font-black uppercase bg-amber-100 text-amber-800 mb-1">
                                {{ $gadai->kategori->nama_kategori }}
                            </span>
                            <p class="font-semibold text-gray-800 text-sm">{{ $gadai->item->head_1 }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-50 text-amber-900 border border-amber-200/80 rounded-lg text-xs font-mono font-bold shadow-xs">
                                <svg class="w-3.5 h-3.5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                                {{ $gadai->slot_kode }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-black text-emerald-600 text-sm">Rp {{ number_format($gadai->nominal_deal, 0, ',', '.') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @if($gadai->status == 'grace_period')
                                <div class="flex flex-col">
                                    <div class="flex items-center gap-1.5 text-red-600 font-bold text-xs">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ $gadai->tgl_tenggang->format('d M Y') }}
                                    </div>
                                    @if($sisaHari !== null && $sisaHari >= 0)
                                        <span class="mt-1 inline-flex items-center gap-1 px-2 py-0.5 bg-red-100 text-red-700 text-[9px] font-black rounded-lg">⚠ {{ $formatSisa($sisaHari) }} tersisa</span>
                                    @elseif($sisaHari !== null)
                                        <span class="mt-1 inline-flex items-center gap-1 px-2 py-0.5 bg-red-600 text-white text-[9px] font-black rounded-lg">LEWAT {{ $formatSisa($sisaHari) }}</span>
                                    @endif
                                </div>
                            @elseif(in_array($gadai->status, ['expired_final', 'auctioned']))
                                <span class="text-xs text-gray-400 italic font-medium">Barang Hangus</span>
                            @elseif($gadai->status == 'lunas')
                                <span class="text-xs text-emerald-600 font-bold">Selesai Lunas</span>
                            @else
                                <div class="flex flex-col">
                                    <div class="flex items-center gap-1.5 text-gray-700 font-medium text-xs">
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        {{ $gadai->tgl_jatuh_tempo->format('d M Y') }}
                                    </div>
                                    @if($sisaHari !== null && $sisaHari >= 0 && $sisaHari <= 7)
                                        <span class="mt-1 inline-flex items-center gap-1 px-2 py-0.5 bg-amber-100 text-amber-700 text-[9px] font-black rounded-lg">⚡ {{ $formatSisa($sisaHari) }} lagi</span>
                                    @elseif($sisaHari !== null && $sisaHari > 7)
                                        <span class="mt-1 text-[9px] text-gray-400 font-medium">{{ $formatSisa($sisaHari) }} lagi</span>
                                    @endif
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($gadai->status == 'active')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black bg-blue-100 text-blue-700 border border-blue-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span> Aktif
                                </span>
                            @elseif($gadai->status == 'grace_period')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black bg-red-100 text-red-700 border border-red-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-600 animate-pulse"></span> Tenggang
                                </span>
                            @elseif($gadai->status == 'lunas')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-700 border border-emerald-200">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg> Lunas
                                </span>
                            @elseif($gadai->status == 'returned')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black bg-indigo-100 text-indigo-700 border border-indigo-200">
                                    <svg class="w-3 h-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 11m8 4V11m-8 0v10l8 4"/></svg> Telah Diambil
                                </span>
                            @elseif($gadai->status == 'auctioned')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black bg-amber-100 text-amber-700 border border-amber-200">
                                    <svg class="w-3 h-3 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14.121 14.121L19 19m-7-7l-2.828-2.828 4.242-4.242 2.828 2.828-4.242 4.242zm-5.656 5.656l-2.828-2.828 1.414-1.414 2.828 2.828-1.414 1.414z"/></svg> Dilelang
                                </span>
                            @elseif($gadai->status == 'expired_final')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black bg-gray-100 text-gray-600 border border-gray-200">
                                    Hangus
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.gadai_baru.detail', $gadai->id) }}"
                               class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-white/60 text-gray-600 hover:bg-gradient-to-br hover:from-majakara-brown hover:to-majakara-dark-gold hover:text-white transition-all border border-white/60 shadow-sm hover:shadow-md group-hover:scale-105">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-0">
                            <x-admin.empty-state title="Tidak ada data gadai" message="Coba ubah filter pencarian di atas" />
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            @if($hasMore)
            <div class="text-center py-6 border-t border-gray-100 hidden md:block" id="load-more-container-desktop">
                <button type="button" onclick="loadMore()" class="px-6 py-2.5 bg-gray-50 text-gray-600 hover:bg-gray-100 font-medium rounded-xl transition-colors shadow-sm text-sm border border-gray-200" id="btn-load-more-desktop">
                    Muat Lebih Banyak
                </button>
            </div>
            @endif

            <!-- Card View for Mobile -->
            <div class="md:hidden divide-y divide-gray-100">
                @forelse($gadiList as $gadai)
                @php
                    $rowBg = '';
                    $today = now()->startOfDay();
                    if ($gadai->status == 'grace_period') {
                        $rowBg = 'bg-red-50/60';
                        $sisaHari = (int) $today->diffInDays(\Carbon\Carbon::parse($gadai->tgl_tenggang)->startOfDay(), false);
                    } elseif ($gadai->status == 'expired_final') {
                        $rowBg = 'bg-amber-50/60';
                        $sisaHari = null;
                    } elseif ($gadai->status == 'lunas') {
                        $rowBg = 'bg-emerald-50/40';
                        $sisaHari = null;
                    } else {
                        $sisaHari = (int) $today->diffInDays(\Carbon\Carbon::parse($gadai->tgl_jatuh_tempo)->startOfDay(), false);
                    }
                    $formatSisa = function($hari) {
                        $abs = abs((int) $hari);
                        $bulan = (int) floor($abs / 30);
                        $sisa  = $abs % 30;
                        if ($bulan > 0 && $sisa > 0) return "{$bulan} bulan {$sisa} hari";
                        if ($bulan > 0) return "{$bulan} bulan";
                        return "{$sisa} hari";
                    };
                @endphp
                <div class="p-4 hover:brightness-95 transition-colors {{ $rowBg }}">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-majakara-brown to-majakara-dark-gold text-white flex items-center justify-center font-bold text-sm shadow-sm shrink-0">
                                {{ substr($gadai->nasabah->user->nama ?? 'N', 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-gray-900 text-sm line-clamp-1">{{ $gadai->nasabah->user->nama ?? 'Tanpa Nama' }}</p>
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 mt-1 rounded-md text-[9px] font-black uppercase bg-amber-100 text-amber-800">
                                    {{ $gadai->kategori->nama_kategori }}
                                </span>
                            </div>
                        </div>
                        <div>
                            @if($gadai->status == 'active')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[9px] font-black bg-blue-100 text-blue-700 border border-blue-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span> Aktif
                                </span>
                            @elseif($gadai->status == 'grace_period')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[9px] font-black bg-red-100 text-red-700 border border-red-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-600 animate-pulse"></span> Tenggang
                                </span>
                            @elseif($gadai->status == 'lunas')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[9px] font-black bg-emerald-100 text-emerald-700 border border-emerald-200">
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg> Lunas
                                </span>
                            @elseif($gadai->status == 'returned')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[9px] font-black bg-indigo-100 text-indigo-700 border border-indigo-200">
                                    <svg class="w-2.5 h-2.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 11m8 4V11m-8 0v10l8 4"/></svg> Telah Diambil
                                </span>
                            @elseif($gadai->status == 'auctioned')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[9px] font-black bg-amber-100 text-amber-700 border border-amber-200">
                                    <svg class="w-2.5 h-2.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14.121 14.121L19 19m-7-7l-2.828-2.828 4.242-4.242 2.828 2.828-4.242 4.242zm-5.656 5.656l-2.828-2.828 1.414-1.414 2.828 2.828-1.414 1.414z"/></svg> Dilelang
                                </span>
                            @elseif($gadai->status == 'expired_final')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[9px] font-black bg-gray-100 text-gray-600 border border-gray-200">
                                    Hangus
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-y-2 gap-x-4 mb-3 p-3 bg-white/60 rounded-xl border border-gray-100">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide">Item Gadai</p>
                            <p class="font-medium text-gray-800 text-sm mt-0.5 line-clamp-1">{{ $gadai->item->head_1 }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide">Kode Slot</p>
                            <div class="inline-flex items-center gap-1.5 mt-0.5 px-2 py-0.5 bg-amber-50 text-amber-900 border border-amber-200/80 rounded-md text-xs font-mono font-bold">
                                <svg class="w-3 h-3 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                                <span>{{ $gadai->slot_kode }}</span>
                            </div>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide">Nominal</p>
                            <p class="font-black text-emerald-600 text-sm mt-0.5">Rp {{ number_format($gadai->nominal_deal, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide">
                                {{ $gadai->status == 'grace_period' ? 'Tenggang' : 'Jatuh Tempo' }}
                            </p>
                            <div class="mt-0.5">
                                @if($gadai->status == 'grace_period')
                                    <div class="flex flex-col">
                                        <div class="flex items-center gap-1 text-red-600 font-bold text-[11px]">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            {{ $gadai->tgl_tenggang->format('d M y') }}
                                        </div>
                                        @if($sisaHari !== null && $sisaHari >= 0)
                                            <span class="mt-0.5 text-[9px] font-black text-red-500">⚠ {{ $formatSisa($sisaHari) }}</span>
                                        @elseif($sisaHari !== null)
                                            <span class="mt-0.5 text-[9px] font-black text-red-600">LEWAT {{ $formatSisa($sisaHari) }}</span>
                                        @endif
                                    </div>
                                @elseif(in_array($gadai->status, ['expired_final', 'auctioned']))
                                    <span class="text-[11px] text-gray-400 italic font-medium">Barang Hangus</span>
                                @elseif($gadai->status == 'lunas')
                                    <span class="text-[11px] text-emerald-600 font-bold">Selesai Lunas</span>
                                @else
                                    <div class="flex flex-col">
                                        <div class="flex items-center gap-1 text-gray-700 font-medium text-[11px]">
                                            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            {{ $gadai->tgl_jatuh_tempo->format('d M y') }}
                                        </div>
                                        @if($sisaHari !== null && $sisaHari >= 0 && $sisaHari <= 7)
                                            <span class="mt-0.5 text-[9px] font-black text-amber-600">⚡ {{ $formatSisa($sisaHari) }}</span>
                                        @elseif($sisaHari !== null && $sisaHari > 7)
                                            <span class="mt-0.5 text-[9px] text-gray-400 font-medium">{{ $formatSisa($sisaHari) }}</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <a href="{{ route('admin.gadai_baru.detail', $gadai->id) }}"
                       class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-white/60 text-gray-700 hover:bg-gradient-to-r hover:from-majakara-brown hover:to-majakara-dark-gold hover:text-white transition-all border border-white/60 rounded-xl shadow-sm text-sm font-medium">
                        <span>Lihat Detail</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
                @empty
                <div class="p-0">
                    <x-admin.empty-state title="Tidak ada data gadai" message="Coba ubah filter pencarian di atas" />
                </div>
                @endforelse
            </div>
            @if($hasMore)
            <div class="text-center py-6 border-t border-gray-100 md:hidden" id="load-more-container-mobile">
                <button type="button" onclick="loadMore()" class="px-6 py-2.5 bg-gray-50 text-gray-600 hover:bg-gray-100 font-medium rounded-xl transition-colors shadow-sm text-sm border border-gray-200" id="btn-load-more-mobile">
                    Muat Lebih Banyak
                </button>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentPage = {{ $page }};
    let isLoading = false;

    async function loadMore() {
        if (isLoading) return;
        isLoading = true;
        
        const btnDesktop = document.getElementById('btn-load-more-desktop');
        const btnMobile = document.getElementById('btn-load-more-mobile');
        
        if(btnDesktop) btnDesktop.innerText = 'Memuat...';
        if(btnMobile) btnMobile.innerText = 'Memuat...';

        try {
            currentPage++;
            const url = new URL(window.location.href);
            url.searchParams.set('page', currentPage);

            const response = await fetch(url.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            });
            const html = await response.text();
            
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            // Desktop Table Append
            const newTableRows = doc.querySelectorAll('table tbody tr');
            const tbody = document.querySelector('table tbody');
            if (newTableRows.length > 0 && tbody) {
                // If it's empty state row, we might need to remove it first, but typically length > 0 handles it
                if(tbody.querySelector('td[colspan]')) {
                    tbody.innerHTML = '';
                }
                newTableRows.forEach(row => tbody.appendChild(row.cloneNode(true)));
            }

            // Mobile Cards Append
            const newCards = doc.querySelectorAll('.md\\:hidden.divide-y > div:not([id^="load-more"])');
            const mobileContainer = document.querySelector('.md\\:hidden.divide-y');
            if (newCards.length > 0 && mobileContainer) {
                if(mobileContainer.querySelector('.p-0')) {
                    mobileContainer.innerHTML = '';
                }
                newCards.forEach(card => {
                    // avoid appending the empty state or load more wrapper
                    if(card.classList.contains('p-4') && card.classList.contains('hover:brightness-95')) {
                        mobileContainer.appendChild(card.cloneNode(true));
                    }
                });
            }

            // Update Load More buttons
            const newHasMore = doc.getElementById('load-more-container-desktop') !== null;
            if (!newHasMore) {
                const contDesktop = document.getElementById('load-more-container-desktop');
                const contMobile = document.getElementById('load-more-container-mobile');
                if(contDesktop) contDesktop.remove();
                if(contMobile) contMobile.remove();
            }

        } catch (error) {
            console.error('Error loading more:', error);
            alert('Gagal memuat data.');
        } finally {
            isLoading = false;
            if(btnDesktop) btnDesktop.innerText = 'Muat Lebih Banyak';
            if(btnMobile) btnMobile.innerText = 'Muat Lebih Banyak';
        }
    }

    // Infinite scroll observer
    const observer = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting) {
            loadMore();
        }
    }, { rootMargin: '100px' });

    // Try observing load more containers for infinite scroll
    setTimeout(() => {
        const desktopContainer = document.getElementById('load-more-container-desktop');
        const mobileContainer = document.getElementById('load-more-container-mobile');
        if (desktopContainer && getComputedStyle(desktopContainer).display !== 'none') observer.observe(desktopContainer);
        if (mobileContainer && getComputedStyle(mobileContainer).display !== 'none') observer.observe(mobileContainer);
    }, 1000);

</script>
@endpush
