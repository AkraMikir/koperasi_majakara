@extends('layouts.nasabah')

@section('title', 'Detail Pinjaman')

@section('content')
<div class="w-full pb-6">
    <!-- Hero Section -->
    <div class="mx-4 mt-4 mb-6">
        <div class="bg-gradient-to-br from-[#8b6f2f] via-[#a0824d] to-[#d4af37] rounded-3xl shadow-2xl p-8 border-2 border-[#d4af37]/30 relative overflow-hidden">
            <div class="relative z-10">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2 font-display">Detail Pinjaman</h1>
                        <p class="text-white/90 text-sm">ID: #{{ str_pad($pinjaman->id, 6, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <span class="px-4 py-2 {{ $pinjaman->status === 'telaksana' ? 'bg-green-500' : 'bg-yellow-500' }} text-white rounded-full text-sm font-semibold">
                        {{ ucfirst($pinjaman->status) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="mx-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <p class="text-sm text-gray-500 mb-1">Total Pinjaman</p>
                <p class="text-2xl font-bold text-[#8b6f2f]">Rp {{ number_format($pinjaman->jumlah_pinjam, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <p class="text-sm text-gray-500 mb-1">Total Tagihan</p>
                <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <p class="text-sm text-gray-500 mb-1">Total Terbayar</p>
                <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalTerbayar, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <p class="text-sm text-gray-500 mb-1">Sisa Pinjaman</p>
                <p class="text-2xl font-bold text-orange-600">Rp {{ number_format($sisaPinjaman, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-[#8b6f2f] font-display">Progress Pelunasan</h2>
                <span class="text-sm font-semibold text-gray-700">{{ number_format($progress, 1) }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-4 mb-2">
                <div class="bg-gradient-to-r from-[#8b6f2f] to-[#d4af37] h-4 rounded-full transition-all duration-500" style="width: {{ number_format($progress, 2) }}%"></div>
            </div>
            <div class="flex justify-between text-xs text-gray-500">
                <span>{{ $angsuranLunas }} / {{ $totalAngsuran }} angsuran lunas</span>
                <span>Rp {{ number_format($totalTerbayar, 0, ',', '.') }} / Rp {{ number_format($totalTagihan, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Info Pinjaman -->
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-lg font-bold text-[#8b6f2f] mb-6 font-display">Informasi Pinjaman</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Tanggal Pinjam</p>
                    <p class="font-semibold text-gray-900">{{ $pinjaman->tgl_pinjam->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Jenis Angsuran</p>
                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                        {{ ucfirst($pinjaman->jenis) }}
                    </span>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Lama Pinjam</p>
                    <p class="font-semibold text-gray-900">{{ $pinjaman->lama_pinjam }} bulan</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Bunga</p>
                    <p class="font-semibold text-gray-900">{{ number_format($pinjaman->bunga * 100, 2) }}% (Rp {{ number_format($pinjaman->bunga_rp, 0, ',', '.') }})</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Denda</p>
                    <p class="font-semibold text-gray-900">{{ number_format($pinjaman->denda_persen, 2) }}%</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Status</p>
                    <span class="px-3 py-1 {{ $pinjaman->lunas === 'lunas' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }} rounded-full text-sm font-semibold">
                        {{ $pinjaman->lunas === 'lunas' ? 'Lunas' : 'Belum Lunas' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Jadwal Angsuran -->
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-lg font-bold text-[#8b6f2f] mb-6 font-display">Jadwal Angsuran</h2>
            
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
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors cursor-pointer" onclick="window.location.href='{{ route('nasabah.pinjaman.detail-angsuran', ['id' => $item->id, 'jenis' => $pinjaman->jenis]) }}'">
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
                                    <p class="text-gray-500">Belum ada jadwal angsuran</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    @if($pinjaman->lunas === 'belum')
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h3 class="text-lg font-bold text-[#8b6f2f] mb-4 font-display">Aksi</h3>
            <a href="{{ route('nasabah.pinjaman.pembayaran', ['pinjaman_id' => $pinjaman->id]) }}" 
                class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-[#8b6f2f] to-[#a0824d] text-white font-semibold py-3 rounded-xl hover:shadow-lg transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Bayar Pinjaman
            </a>
        </div>
    </div>
    @endif

    <!-- Back Button -->
    <div class="mx-4 mb-6">
        <a href="{{ route('nasabah.pinjaman.pinjaman-aktif') }}" 
            class="inline-flex items-center gap-2 text-[#8b6f2f] font-semibold hover:underline">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Pinjaman Aktif
        </a>
    </div>
</div>
@endsection
