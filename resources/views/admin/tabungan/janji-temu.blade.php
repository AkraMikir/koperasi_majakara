@extends('layouts.admin')

@section('title', 'Janji Temu Tabungan')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Janji Temu Tabungan</h1>
            <p class="text-gray-600 mt-1">Kelola janji temu nasabah untuk setoran tabungan</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.tabungan.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium">
                ← Kembali
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <form method="GET" action="{{ route('admin.tabungan.janji-temu') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none">
            </div>
            <div>
                <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none">
            </div>
            <div>
                <button type="submit" class="w-full px-6 py-2 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-md font-medium">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-[#674c1d]/20 bg-gradient-to-r from-[#674c1d]/5 to-[#8b6f2f]/5">
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Nasabah</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Tanggal & Waktu</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Lokasi</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($janjiTemu as $item)
                    <tr class="border-b border-gray-100 hover:bg-gradient-to-r hover:from-[#674c1d]/5 hover:to-[#8b6f2f]/5 transition-all">
                        <td class="px-6 py-4 text-sm font-medium">#{{ $item->id }}</td>
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $item->pengajuan->nasabah->user->nama ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500">{{ $item->pengajuan->nasabah->user->email ?? '-' }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $item->tanggal_janji_temu->format('d M Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $item->tanggal_janji_temu->format('H:i') }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $item->lokasi->nama_lokasi ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500">{{ $item->lokasi->kota ?? '' }}, {{ $item->lokasi->provinsi ?? '' }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-[#674c1d]">Rp {{ number_format($item->nominal, 0, ',', '.') }}</p>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-gray-500">Tidak ada janji temu</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($janjiTemu->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $janjiTemu->links() }}
        </div>
        @endif
    </div>
</div>
@endsection


