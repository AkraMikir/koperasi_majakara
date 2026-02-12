@extends('layouts.admin')

@section('title', 'Janji Temu Universal')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Janji Temu Nasabah</h1>
            <p class="text-gray-600 mt-1">Jadwal pertemuan untuk setoran tabungan & pengajuan pinjaman</p>
        </div>
        <div>
            <span class="px-4 py-2 bg-[#674c1d] text-white text-sm font-medium rounded-lg shadow-sm">
                Total: {{ $janjiTemu->total() }} Jadwal
            </span>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('admin.janji-temu.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Cari nama nasabah..." 
                    class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#674c1d] focus:border-transparent transition-all outline-none">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            
            <div>
                <select name="fitur" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#674c1d] focus:border-transparent transition-all outline-none">
                    <option value="">Semua Fitur</option>
                    <option value="Tabungan" {{ request('fitur') == 'Tabungan' ? 'selected' : '' }}>Tabungan</option>
                    <option value="Pinjaman" {{ request('fitur') == 'Pinjaman' ? 'selected' : '' }}>Pinjaman</option>
                </select>
            </div>

            <div>
                <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" 
                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#674c1d] focus:border-transparent transition-all outline-none">
            </div>

            <button type="submit" class="px-6 py-2.5 bg-[#674c1d] hover:bg-[#543d16] text-white font-medium rounded-xl transition-all shadow-lg shadow-[#674c1d]/20 flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                Filter
            </button>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">FITUR</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">NASABAH</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">TANGGAL & WAKTU</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">LOKASI</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">NOMINAL</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">KET</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($janjiTemu as $item)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col gap-1">
                                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $item->fitur == 'Tabungan' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                    {{ $item->fitur }}
                                </span>
                                @if($item->fitur == 'Tabungan' && isset($item->jenis))
                                    <span class="px-2 py-0.5 rounded-full text-[10px] uppercase font-bold text-center {{ $item->jenis == 'setoran' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                                        {{ $item->jenis == 'setoran' ? 'Setoran' : 'Penarikan' }}
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-[#674c1d]/10 flex items-center justify-center text-[#674c1d] font-bold text-xs mr-3">
                                    {{ substr($item->nama_anggota, 0, 1) }}
                                </div>
                                <span class="font-medium text-gray-900">{{ $item->nama_anggota }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $item->tanggal_janji_temu->format('d M Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $item->waktu_janji_temu }} WIB</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-700">{{ $item->lokasi }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-medium text-[#674c1d]">Rp {{ number_format($item->nominal, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-500">{{ Str::limit($item->keterangan, 30) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            @if($item->fitur == 'Tabungan')
                                <a href="{{ route('admin.tabungan.detail-janji-temu', $item->id_asli) }}" class="text-[#674c1d] hover:text-[#543d16]">Detail →</a>
                            @elseif($item->fitur == 'Pinjaman')
                                <!-- Asumsi ada route detail pinjaman -->
                                <a href="{{ route('admin.pinjaman.detail-pengajuan', $item->id_asli) }}" class="text-[#674c1d] hover:text-[#543d16]">Detail →</a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-lg font-medium">Belum ada janji temu</p>
                                <p class="text-sm text-gray-400 mt-1">Jadwal pertemuan dengan nasabah akan muncul di sini</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{ $janjiTemu->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
