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
                class="flex items-center gap-2 px-4 py-2.5 bg-white text-gray-700 border border-gray-200 font-medium rounded-xl hover:bg-gray-50 transition-colors shadow-sm text-sm">
                <svg class="w-4 h-4 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                <span>Peta Storage</span>
            </a>
            <a href="{{ route('admin.gadai_baru.pengajuan.index') }}"
                class="flex items-center gap-2 px-4 py-2.5 bg-white text-gray-700 border border-gray-200 font-medium rounded-xl hover:bg-gray-50 transition-colors shadow-sm text-sm">
                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <span>Antrean Pengajuan</span>
            </a>
            <a href="{{ route('admin.gadai_baru.create') }}"
                class="flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white font-medium rounded-xl shadow-lg shadow-[#674c1d]/30 hover:shadow-xl hover:shadow-[#674c1d]/40 transition-all hover:-translate-y-0.5 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Terima Gadai Baru</span>
            </a>
        </div>
    </div>

    {{-- ===== STAT SUMMARY CARDS ===== --}}
    @php
        $statAktif      = $gadiList->where('status', 'active')->count();
        $statTenggang   = $gadiList->where('status', 'grace_period')->count();
        $statHangus     = $gadiList->where('status', 'expired_final')->count();
        $statLunas      = $gadiList->where('status', 'lunas')->count();
        $totalAll       = $gadiList->count();
    @endphp
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <div>
                <p class="text-2xl font-black text-gray-900">{{ $statAktif }}</p>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-0.5">Aktif</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-red-100 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-red-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-2xl font-black text-red-600">{{ $statTenggang }}</p>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-0.5">Masa Tenggang</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-amber-100 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
            </div>
            <div>
                <p class="text-2xl font-black text-amber-700">{{ $statHangus }}</p>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-0.5">Hangus / Lelang</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div>
                <p class="text-2xl font-black text-gray-900">{{ $statLunas }}</p>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-0.5">Lunas</p>
            </div>
        </div>
    </div>

    {{-- ===== FILTER SECTION ===== --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center gap-2">
            <svg class="w-4 h-4 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
            <h3 class="font-bold text-gray-800 text-sm">Filter Pencarian</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.gadai_baru.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Kategori</label>
                    <select name="kategori" class="w-full border-gray-200 rounded-xl bg-gray-50 focus:ring-[#674c1d] focus:border-[#674c1d] text-sm transition-colors">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoriList as $kat)
                        <option value="{{ $kat->kode_kategori }}" {{ request('kategori') == $kat->kode_kategori ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Cabang</label>
                    <select name="cabang" class="w-full border-gray-200 rounded-xl bg-gray-50 focus:ring-[#674c1d] focus:border-[#674c1d] text-sm transition-colors">
                        <option value="">Semua Cabang</option>
                        @foreach($lokasiList as $lok)
                        <option value="{{ $lok->id }}" {{ request('cabang') == $lok->id ? 'selected' : '' }}>{{ $lok->nama_lokasi }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Status</label>
                    <select name="status" class="w-full border-gray-200 rounded-xl bg-gray-50 focus:ring-[#674c1d] focus:border-[#674c1d] text-sm transition-colors">
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
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
            <h3 class="font-bold text-gray-800 text-sm">Daftar Gadai <span class="text-gray-400 font-normal">({{ $totalAll }} data)</span></h3>
            {{-- Legend --}}
            <div class="hidden md:flex items-center gap-3 text-[10px] font-bold text-gray-500">
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-sm bg-red-100 border border-red-200"></span> Tenggang</span>
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-sm bg-amber-50 border border-amber-200"></span> Hangus</span>
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-sm bg-emerald-50 border border-emerald-200"></span> Lunas</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100">
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
                    <tr class="hover:brightness-95 transition-all group {{ $rowBg }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] text-white flex items-center justify-center font-bold text-sm shadow-sm shrink-0">
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
                            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-900 text-white rounded-lg text-xs font-mono font-bold shadow-sm">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
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
                            @elseif($gadai->status == 'auctioned')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black bg-amber-100 text-amber-700 border border-amber-200">
                                    🔨 Dilelang
                                </span>
                            @elseif($gadai->status == 'expired_final')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black bg-gray-100 text-gray-600 border border-gray-200">
                                    Hangus
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.gadai_baru.detail', $gadai->id) }}"
                               class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-white text-gray-600 hover:bg-[#674c1d] hover:text-white transition-all border border-gray-200 hover:border-transparent shadow-sm hover:shadow-md group-hover:scale-105">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center justify-center gap-4">
                                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center">
                                    <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                </div>
                                <div>
                                    <p class="text-gray-500 font-bold">Tidak ada data gadai</p>
                                    <p class="text-gray-300 text-xs mt-1">Coba ubah filter pencarian di atas</p>
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
@endsection
