@extends('layouts.nasabah')

@section('title', 'Angsuran')

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
        <div class="bg-gradient-to-br from-[#8b6f2f] via-[#a0824d] to-[#d4af37] rounded-3xl shadow-2xl p-8 border-2 border-[#d4af37]/30 relative overflow-hidden">
            <div class="relative z-10">
                <h1 class="text-3xl font-bold text-white mb-2 font-display">Angsuran</h1>
                <p class="text-white/90 text-sm">Daftar semua angsuran pinjaman Anda</p>
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
                <p class="text-xs font-semibold text-gray-700">Filter Angsuran</p>
            </div>
            
            <form method="GET" action="{{ route('nasabah.pinjaman.angsuran') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-2">Jenis</label>
                        <select name="jenis" class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20">
                            <option value="bulanan" {{ request('jenis', 'bulanan') === 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                            <option value="mingguan" {{ request('jenis') === 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20">
                            <option value="">Semua Status</option>
                            <option value="belum" {{ request('status') === 'belum' ? 'selected' : '' }}>Belum</option>
                            <option value="lunas" {{ request('status') === 'lunas' ? 'selected' : '' }}>Lunas</option>
                            <option value="telat" {{ request('status') === 'telat' ? 'selected' : '' }}>Telat</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-2">Dari Tanggal</label>
                        <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" 
                            class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-2">Sampai Tanggal</label>
                        <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" 
                            class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20">
                    </div>
                </div>
                <div>
                    <button type="submit" class="w-full md:w-auto bg-gradient-to-r from-[#8b6f2f] to-[#a0824d] text-white font-semibold px-6 py-2 rounded-xl hover:shadow-lg transition-all">
                        Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- List Angsuran -->
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-[#8b6f2f]/20">
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase">No</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase">Jatuh Tempo</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase">Jumlah Tagihan</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase">Terbayar</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase">Sisa</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($angsuran ?? [] as $item)
                        @php
                            $sisa = max(0, $item->jumlah_tagihan - ($item->jumlah_terbayar ?? 0));
                            $isTelat = $item->tgl_jatuh_tempo < now() && $item->status_bayar !== 'lunas';
                        @endphp
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors cursor-pointer" onclick="window.location.href='{{ route('nasabah.pinjaman.detail-angsuran', ['id' => $item->id, 'jenis' => $jenis]) }}'">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $item->no_urut }}</td>
                            <td class="px-4 py-3 text-sm">
                                <p class="font-medium text-gray-900">{{ $item->tgl_jatuh_tempo->format('d M Y') }}</p>
                                @if($isTelat)
                                <p class="text-xs text-red-600">Telat {{ $item->tgl_jatuh_tempo->diffForHumans() }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <p class="font-semibold text-gray-900">Rp {{ number_format($item->jumlah_tagihan, 0, ',', '.') }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <p class="font-semibold {{ $item->jumlah_terbayar >= $item->jumlah_tagihan ? 'text-green-600' : 'text-orange-600' }}">
                                    Rp {{ number_format($item->jumlah_terbayar ?? 0, 0, ',', '.') }}
                                </p>
                            </td>
                            <td class="px-4 py-3">
                                <p class="font-semibold text-gray-900">Rp {{ number_format($sisa, 0, ',', '.') }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-between">
                                    <span class="px-3 py-1 {{ $item->status_bayar === 'lunas' ? 'bg-green-100 text-green-700' : ($isTelat ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }} rounded-full text-xs font-semibold">
                                        {{ $item->status_bayar === 'lunas' ? 'Lunas' : ($isTelat ? 'Telat' : 'Belum') }}
                                    </span>
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-gray-500">Belum ada angsuran</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if(isset($angsuran) && $angsuran->hasPages())
            <div class="mt-6">
                {{ $angsuran->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
