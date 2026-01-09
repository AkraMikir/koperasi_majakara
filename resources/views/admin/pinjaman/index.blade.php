@extends('layouts.admin')

@section('title', 'Dashboard Pinjaman')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Dashboard Pinjaman</h1>
            <p class="text-gray-600 mt-1">Kelola pengajuan dan pinjaman nasabah</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.pinjaman.angsuran') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium">
                Lihat Angsuran
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Pengajuan Pending -->
        <a href="{{ route('admin.pinjaman.pengajuan') }}" class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-[#674c1d]/20 to-[#674c1d]/10 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">{{ $stats['total_pengajuan_pending'] ?? 0 }}</span>
            </div>
            <h3 class="text-sm font-medium text-gray-600 mb-1">Pengajuan Pending</h3>
            <p class="text-3xl font-bold text-[#674c1d] mb-1">{{ number_format($stats['total_pengajuan_pending'] ?? 0, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500">Menunggu persetujuan</p>
        </a>

        <!-- Pinjaman Aktif -->
        <a href="{{ route('admin.pinjaman.pinjaman-aktif') }}" class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-[#8b6f2f]/20 to-[#8b6f2f]/10 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-[#8b6f2f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">{{ $stats['total_pinjaman_aktif'] ?? 0 }}</span>
            </div>
            <h3 class="text-sm font-medium text-gray-600 mb-1">Pinjaman Aktif</h3>
            <p class="text-3xl font-bold text-[#8b6f2f] mb-1">{{ number_format($stats['total_pinjaman_aktif'] ?? 0, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500">Total pinjaman aktif</p>
        </a>

        <!-- Total Nominal Pinjaman Aktif -->
        <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-[#4a3514]/20 to-[#4a3514]/10 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-[#4a3514]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <h3 class="text-sm font-medium text-gray-600 mb-1">Total Pinjaman Aktif</h3>
            <p class="text-3xl font-bold text-[#4a3514] mb-1">Rp {{ number_format($stats['total_nominal_pinjaman_aktif'] ?? 0, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500">Nominal keseluruhan</p>
        </div>

        <!-- Angsuran Telat -->
        <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-[#d4af37]/20 to-[#d4af37]/10 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">{{ $stats['total_angsuran_telat'] ?? 0 }}</span>
            </div>
            <h3 class="text-sm font-medium text-gray-600 mb-1">Angsuran Telat</h3>
            <p class="text-3xl font-bold text-[#d4af37] mb-1">{{ number_format($stats['total_angsuran_telat'] ?? 0, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500">Perlu ditindaklanjuti</p>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Pengajuan Terbaru -->
        <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-primary font-display">Pengajuan Terbaru</h2>
                </div>
                <a href="{{ route('admin.pinjaman.pengajuan') }}" class="text-sm text-[#674c1d] hover:underline font-medium">
                    Lihat Semua →
                </a>
            </div>

            <div class="space-y-4">
                @forelse($pengajuan_terbaru ?? [] as $pengajuan)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors border border-gray-200">
                    <div class="flex items-center space-x-4 flex-1">
                        <div class="w-12 h-12 bg-gradient-to-br from-[#674c1d]/20 to-[#8b6f2f]/20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ $pengajuan->nasabah->user->nama ?? 'N/A' }}</h3>
                            <p class="text-sm text-gray-600">Rp {{ number_format($pengajuan->nominal, 0, ',', '.') }} - {{ ucfirst($pengajuan->jenis) }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $pengajuan->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">Pending</span>
                        <a href="{{ route('admin.pinjaman.detail-pengajuan', $pengajuan->id) }}" class="p-2 text-[#674c1d] hover:bg-[#674c1d]/10 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                @empty
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-gray-500">Tidak ada pengajuan pending</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Pinjaman Aktif Terbaru -->
        <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-[#8b6f2f] to-[#d4af37] rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-primary font-display">Pinjaman Aktif Terbaru</h2>
                </div>
                <a href="{{ route('admin.pinjaman.pinjaman-aktif') }}" class="text-sm text-[#674c1d] hover:underline font-medium">
                    Lihat Semua →
                </a>
            </div>

            <div class="space-y-4">
                @forelse($pinjaman_aktif_terbaru ?? [] as $pinjaman)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors border border-gray-200">
                    <div class="flex items-center space-x-4 flex-1">
                        <div class="w-12 h-12 bg-gradient-to-br from-[#8b6f2f]/20 to-[#d4af37]/20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-[#8b6f2f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ $pinjaman->nasabah->user->nama ?? 'N/A' }}</h3>
                            <p class="text-sm text-gray-600">Rp {{ number_format($pinjaman->jumlah_pinjam, 0, ',', '.') }} - {{ ucfirst($pinjaman->jenis) }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $pinjaman->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">{{ ucfirst($pinjaman->status) }}</span>
                        <a href="{{ route('admin.pinjaman.detail-pinjaman', $pinjaman->id) }}" class="p-2 text-[#674c1d] hover:bg-[#674c1d]/10 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                @empty
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    <p class="text-gray-500">Tidak ada pinjaman aktif</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Angsuran Jatuh Tempo Hari Ini -->
    @if(isset($angsuran_jatuh_tempo) && $angsuran_jatuh_tempo->count() > 0)
    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-[#d4af37] to-[#8b6f2f] rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-primary font-display">Angsuran Jatuh Tempo Hari Ini</h2>
            </div>
            <a href="{{ route('admin.pinjaman.angsuran') }}" class="text-sm text-[#674c1d] hover:underline font-medium">
                Lihat Semua →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-[#674c1d]/20 bg-gradient-to-r from-[#674c1d]/5 to-[#8b6f2f]/5">
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Nasabah</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">No. Urut</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Jatuh Tempo</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Jumlah Tagihan</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($angsuran_jatuh_tempo as $item)
                    <tr class="border-b border-gray-100 hover:bg-gradient-to-r hover:from-[#674c1d]/5 hover:to-[#8b6f2f]/5 transition-all">
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-900">{{ $item->nasabah->user->nama ?? 'N/A' }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm">#{{ $item->no_urut }}</td>
                        <td class="px-6 py-4 text-sm">{{ $item->tgl_jatuh_tempo->format('d M Y, H:i') }}</td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-[#674c1d]">Rp {{ number_format($item->jumlah_tagihan, 0, ',', '.') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">Jatuh Tempo</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
