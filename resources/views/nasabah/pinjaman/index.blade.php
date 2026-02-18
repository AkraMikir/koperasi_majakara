@extends('layouts.nasabah')

@section('title', 'Dashboard Pinjaman')

@section('content')
<div class="w-full pb-6">
    <!-- Hero Section - Total Pinjaman Aktif -->
    <div class="mx-4 mt-4 mb-6">
        <div class="bg-linear-to-br from-[#8b6f2f] via-[#a0824d] to-[#d4af37] rounded-3xl shadow-2xl p-8 border-2 border-[#d4af37]/30 relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white rounded-full -mr-32 -mt-32"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-white rounded-full -ml-24 -mb-24"></div>
            </div>
            
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <p class="text-white/90 text-sm font-medium mb-2">Total Pinjaman Aktif</p>
                        <h1 class="text-4xl md:text-5xl font-bold text-white mb-2 font-display">
                            Rp {{ number_format($totalPinjamanAktif ?? 0, 0, ',', '.') }}
                        </h1>
                        <p class="text-white/80 text-sm">Sisa: <span class="font-semibold">Rp {{ number_format($sisaPinjaman ?? 0, 0, ',', '.') }}</span></p>
                    </div>
                    <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="grid grid-cols-2 gap-3 mt-6">
                    <a href="{{ route('nasabah.pinjaman.pengajuan') }}" class="bg-white/20 backdrop-blur-sm hover:bg-white/30 rounded-xl p-4 transition-all border border-white/30">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#8b6f2f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-white text-sm font-medium">Ajukan Pinjaman</p>
                                <p class="text-white/80 text-xs">Pinjaman Baru</p>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('nasabah.pinjaman.pembayaran') }}" class="bg-white/20 backdrop-blur-sm hover:bg-white/30 rounded-xl p-4 transition-all border border-white/30">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#8b6f2f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-white text-sm font-medium">Bayar Pinjaman</p>
                                <p class="text-white/80 text-xs">Transfer / Cash</p>
                            </div>
                        </div>
                    </a>
                </div>
                
                <!-- Status Links -->
                <div class="grid grid-cols-3 gap-3 mt-3">
                    <a href="{{ route('nasabah.pinjaman.status-pengajuan') }}" class="bg-white/10 backdrop-blur-sm hover:bg-white/20 rounded-lg p-3 transition-all border border-white/20 text-center">
                        <p class="text-white text-xs font-medium">Status Pengajuan</p>
                    </a>
                    <a href="{{ route('nasabah.pinjaman.pinjaman-aktif') }}" class="bg-white/10 backdrop-blur-sm hover:bg-white/20 rounded-lg p-3 transition-all border border-white/20 text-center">
                        <p class="text-white text-xs font-medium">Pinjaman Aktif</p>
                    </a>
                    <a href="{{ route('nasabah.pinjaman.status-pembayaran') }}" class="bg-white/10 backdrop-blur-sm hover:bg-white/20 rounded-lg p-3 transition-all border border-white/20 text-center">
                        <p class="text-white text-xs font-medium">Status Pembayaran</p>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="mx-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Jumlah Pinjaman Aktif -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-linear-to-br from-[#8b6f2f]/20 to-[#8b6f2f]/10 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#8b6f2f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Pinjaman Aktif</h3>
                <p class="text-2xl font-bold text-[#8b6f2f] mb-1">{{ $pinjamanAktif->count() ?? 0 }}</p>
                <p class="text-xs text-gray-500">Total pinjaman aktif</p>
            </div>

            <!-- Angsuran Terdekat -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-linear-to-br from-orange-100 to-orange-50 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Angsuran Terdekat</h3>
                <p class="text-2xl font-bold text-orange-600 mb-1">{{ $angsuranTerdekat->count() ?? 0 }}</p>
                <p class="text-xs text-gray-500">Jatuh tempo 7 hari ke depan</p>
            </div>

            <!-- Angsuran Telat -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-linear-to-br from-red-100 to-red-50 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Angsuran Telat</h3>
                <p class="text-2xl font-bold text-red-600 mb-1">{{ $totalAngsuranTelat ?? 0 }}</p>
                <p class="text-xs text-gray-500">Perlu perhatian</p>
            </div>
        </div>
    </div>

    <!-- Pinjaman Aktif & Pinjaman Lunas (setengah lebar masing-masing) -->
    <div class="mx-4 mb-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Pinjaman Aktif -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-linear-to-br from-[#8b6f2f] to-[#a0824d] rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-[#8b6f2f] font-display">Pinjaman Aktif</h2>
                </div>
                <a href="{{ route('nasabah.pinjaman.pinjaman-aktif') }}" class="text-sm text-[#8b6f2f] font-medium hover:underline">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-[#8b6f2f]/20">
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase">ID Pinjaman</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase">Tanggal Pinjam</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pinjamanAktif ?? [] as $pinjaman)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors cursor-pointer" onclick="window.location.href='{{ route('nasabah.pinjaman.detail-pinjaman', $pinjaman->id) }}'">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $pinjaman->id }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $pinjaman->tgl_pinjam->format('d M Y') }}</td>
                            <td class="px-4 py-3 font-semibold text-gray-900">Rp {{ number_format($pinjaman->jumlah_pinjam, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-gray-500">Belum ada pinjaman aktif</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pinjaman Lunas -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-linear-to-br from-green-600 to-green-700 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-[#8b6f2f] font-display">Pinjaman Lunas</h2>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-[#8b6f2f]/20">
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase">ID Pinjaman</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase">Jumlah Pinjam</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase">Terbayar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pinjamanLunas ?? [] as $pinjaman)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors cursor-pointer" onclick="window.location.href='{{ route('nasabah.pinjaman.detail-pinjaman', $pinjaman->id) }}'">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $pinjaman->id }}</td>
                            <td class="px-4 py-3 font-semibold text-gray-900">Rp {{ number_format($pinjaman->jumlah_pinjam, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 font-semibold text-green-600">Rp {{ number_format($pinjaman->total_terbayar ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-gray-500">Belum ada pinjaman lunas</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Angsuran Terdekat -->
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-linear-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-[#8b6f2f] font-display">Angsuran Terdekat</h2>
                </div>
                <a href="{{ route('nasabah.pinjaman.angsuran') }}" class="text-sm text-[#8b6f2f] font-medium hover:underline">Lihat Semua</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-[#8b6f2f]/20">
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase">Jatuh Tempo</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase">Jumlah Tagihan</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase">Terbayar</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($angsuranTerdekat ?? [] as $angsuran)
                        @php
                            $sisa = max(0, $angsuran->jumlah_tagihan - ($angsuran->jumlah_terbayar ?? 0));
                            $isTelat = $angsuran->tgl_jatuh_tempo < now() && $angsuran->status_bayar !== 'lunas';
                            $jenisAngsuran = $angsuran->pinjaman->jenis ?? 'bulanan';
                            $urlDetail = route('nasabah.pinjaman.detail-angsuran', ['id' => $angsuran->id, 'jenis' => $jenisAngsuran]);
                        @endphp
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors cursor-pointer" onclick="window.location.href='{{ $urlDetail }}'">
                            <td class="px-4 py-3 text-sm">
                                <p class="font-medium text-gray-900">{{ $angsuran->tgl_jatuh_tempo->format('d M Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $angsuran->tgl_jatuh_tempo->diffForHumans() }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <p class="font-semibold text-gray-900">Rp {{ number_format($angsuran->jumlah_tagihan, 0, ',', '.') }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <p class="font-semibold {{ $angsuran->jumlah_terbayar >= $angsuran->jumlah_tagihan ? 'text-green-600' : 'text-orange-600' }}">
                                    Rp {{ number_format($angsuran->jumlah_terbayar ?? 0, 0, ',', '.') }}
                                </p>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-between">
                                    <span class="px-3 py-1 {{ $angsuran->status_bayar === 'lunas' ? 'bg-green-100 text-green-700' : ($isTelat ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }} rounded-full text-xs font-semibold">
                                        {{ $angsuran->status_bayar === 'lunas' ? 'Lunas' : ($isTelat ? 'Telat' : 'Belum') }}
                                    </span>
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-gray-500">Tidak ada angsuran terdekat</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
