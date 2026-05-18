@extends('layouts.admin')

@section('title', 'Kelola Gadai Fisik')

@section('content')
<div class="space-y-8">

    {{-- ===== PAGE HEADER ===== --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Gadai Fisik</h1>
            <p class="text-gray-500 mt-1">Kelola daftar pengajuan dan status gadai nasabah</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.gadai_baru.create') }}" 
                class="flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white font-medium rounded-xl shadow-lg shadow-[#674c1d]/30 hover:shadow-xl hover:shadow-[#674c1d]/40 transition-all hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Terima Gadai Baru</span>
            </a>
        </div>
    </div>

    {{-- ===== FILTER SECTION ===== --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                Filter Pencarian
            </h3>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.gadai_baru.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori</label>
                    <select name="kategori" class="w-full border-gray-200 rounded-xl bg-gray-50 focus:ring-[#674c1d] focus:border-[#674c1d] text-sm transition-colors">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoriList as $kat)
                        <option value="{{ $kat->kode_kategori }}" {{ request('kategori') == $kat->kode_kategori ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Cabang</label>
                    <select name="cabang" class="w-full border-gray-200 rounded-xl bg-gray-50 focus:ring-[#674c1d] focus:border-[#674c1d] text-sm transition-colors">
                        <option value="">Semua Cabang</option>
                        @foreach($lokasiList as $lok)
                        <option value="{{ $lok->id }}" {{ request('cabang') == $lok->id ? 'selected' : '' }}>{{ $lok->nama_lokasi }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full border-gray-200 rounded-xl bg-gray-50 focus:ring-[#674c1d] focus:border-[#674c1d] text-sm transition-colors">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="grace_period" {{ request('status') == 'grace_period' ? 'selected' : '' }}>Tenggang</option>
                        <option value="expired_final" {{ request('status') == 'expired_final' ? 'selected' : '' }}>Hangus</option>
                        <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full flex justify-center items-center gap-2 px-4 py-2.5 bg-gray-800 hover:bg-gray-900 text-white font-medium rounded-xl transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== DATA TABLE ===== --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Nasabah</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Kategori & Item</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Kode Slot</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Nominal Deal</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Jatuh Tempo</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($gadiList as $gadai)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">
                                    {{ substr($gadai->nasabah->user->nama ?? 'N/A', 0, 2) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">{{ $gadai->nasabah->user->nama ?? 'Tanpa Nama' }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                        {{ $gadai->nasabah->user->nomor_hp ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800 mb-1">
                                {{ $gadai->kategori->nama_kategori }}
                            </span>
                            <p class="font-semibold text-gray-800">{{ $gadai->item->head_1 }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-900 text-white rounded-lg text-sm font-mono font-bold shadow-sm">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                                {{ $gadai->slot_kode }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-emerald-600">Rp {{ number_format($gadai->nominal_deal, 0, ',', '.') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @if($gadai->status == 'grace_period')
                                <div class="flex items-center gap-1.5 text-red-600 font-bold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $gadai->tgl_tenggang->format('d M Y') }}
                                </div>
                                <p class="text-xs text-red-400 mt-0.5 font-medium">(Masa Tenggang)</p>
                            @else
                                <div class="flex items-center gap-1.5 text-gray-700 font-medium">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ $gadai->tgl_jatuh_tempo->format('d M Y') }}
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($gadai->status == 'active')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span> Aktif
                                </span>
                            @elseif($gadai->status == 'grace_period')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-600 animate-pulse"></span> Tenggang
                                </span>
                            @elseif($gadai->status == 'lunas')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> Lunas
                                </span>
                            @elseif($gadai->status == 'auctioned')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">
                                    Dilelang
                                </span>
                            @elseif($gadai->status == 'expired_final')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-700">
                                    Hangus
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.gadai_baru.detail', $gadai->id) }}" 
                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-600 hover:bg-[#674c1d] hover:text-white transition-colors border border-gray-200 hover:border-transparent">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                                <p class="text-gray-500 font-medium">Tidak ada data gadai yang sesuai dengan filter.</p>
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
