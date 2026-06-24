@extends('layouts.nasabah')

@section('title', 'Status Pengajuan Pinjaman')

@section('content')
<div class="w-full pb-6">
    <!-- Back Button -->
    <div class="mx-4 mt-4 mb-4">
        <a href="{{ route('nasabah.pinjaman.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-white rounded-xl shadow hover:shadow-md transition-all text-gray-700 hover:text-[#674c1d]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

    <!-- Hero Section -->
    <div class="mx-4 mb-6">
        <div class="bg-gradient-to-br from-[#8b6f2f] via-[#a0824d] to-[#d4af37] rounded-3xl shadow-2xl p-4 sm:p-6 md:p-8 border-2 border-[#d4af37]/30 relative overflow-hidden">
            <div class="relative z-10">
                <h1 class="text-3xl font-bold text-white mb-2 font-display">Status Pengajuan Pinjaman</h1>
                <p class="text-white/90 text-sm">Lihat status pengajuan pinjaman Anda</p>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center gap-2 mb-4">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                <p class="text-xs font-semibold text-gray-700">Filter Pengajuan</p>
            </div>
            
            <form method="GET" action="{{ route('nasabah.pinjaman.status-pengajuan') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Jenis</label>
                    <select name="jenis" class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20">
                        <option value="">Semua Jenis</option>
                        <option value="bulanan" {{ request('jenis') === 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                        <option value="mingguan" {{ request('jenis') === 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-linear-to-r from-[#8b6f2f] to-[#a0824d] text-white font-semibold py-2 rounded-xl hover:shadow-lg transition-all">
                        Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- List Pengajuan -->
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-[#8b6f2f]/20">
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase">Nominal</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase">Jenis</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase">Durasi</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($pengajuan ?? []) as $item)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors cursor-pointer" role="button" tabindex="0" data-href="{{ route('nasabah.pinjaman.detail-pengajuan', $item->id) }}" onclick="window.location.href=this.dataset.href">
                            <td class="px-4 py-4 text-sm">
                                <p class="font-medium text-gray-900">{{ $item->tgl_pengajuan->format('d M Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $item->tgl_pengajuan->format('H:i') }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="font-semibold text-gray-900">Rp {{ number_format($item->nominal, 0, ',', '.') }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                                    {{ ucfirst($item->jenis) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-600">{{ $item->durasi }} bulan</td>
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="px-3 py-1 {{ $item->pinjaman ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }} rounded-full text-xs font-semibold">
                                        {{ $item->pinjaman ? 'Disetujui' : 'Pending' }}
                                    </span>
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-gray-500">Belum ada pengajuan</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if(isset($pengajuan) && $pengajuan->hasPages())
            <div class="mt-6">
                {{ $pengajuan->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
