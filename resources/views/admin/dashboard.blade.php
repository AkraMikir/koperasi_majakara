@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Dashboard Admin</h1>
            <p class="text-gray-600 mt-1">Selamat datang kembali, {{ auth()->user()->nama ?? 'Admin' }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Export
            </button>
            <button class="px-4 py-2 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-md text-sm font-medium">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Data
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Nasabah -->
        <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-linear-to-br from-[#674c1d]/20 to-[#674c1d]/10 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">+12%</span>
            </div>
            <h3 class="text-sm font-medium text-gray-600 mb-1">Total Nasabah</h3>
            <p class="text-3xl font-bold text-[#674c1d] mb-1">{{ number_format($stats['total_nasabah'] ?? 0, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500">Aktif bulan ini</p>
        </div>

        <!-- Total Tabungan -->
        <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-linear-to-br from-[#8b6f2f]/20 to-[#8b6f2f]/10 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-[#8b6f2f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">+8%</span>
            </div>
            <h3 class="text-sm font-medium text-gray-600 mb-1">Total Tabungan</h3>
            <p class="text-3xl font-bold text-[#8b6f2f] mb-1">Rp {{ number_format($stats['total_tabungan'] ?? 0, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500">Saldo keseluruhan</p>
        </div>

        <!-- Total Pinjaman Aktif -->
        <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-linear-to-br from-[#4a3514]/20 to-[#4a3514]/10 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-[#4a3514]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">{{ $stats['pinjaman_aktif'] ?? 0 }}</span>
            </div>
            <h3 class="text-sm font-medium text-gray-600 mb-1">Pinjaman Aktif</h3>
            <p class="text-3xl font-bold text-[#4a3514] mb-1">Rp {{ number_format($stats['total_pinjaman'] ?? 0, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500">Total pinjaman aktif</p>
        </div>

        <!-- Pengajuan Pending -->
        <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-linear-to-br from-[#d4af37]/20 to-[#d4af37]/10 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">{{ $stats['pengajuan_pending'] ?? 0 }}</span>
            </div>
            <h3 class="text-sm font-medium text-gray-600 mb-1">Pengajuan Pending</h3>
            <p class="text-3xl font-bold text-[#d4af37] mb-1">{{ number_format($stats['pengajuan_pending'] ?? 0, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500">Perlu ditinjau</p>
        </div>
    </div>

    <!-- Additional Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Deposito -->
        <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-linear-to-br from-[#674c1d]/20 to-[#8b6f2f]/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
            <h3 class="text-sm font-medium text-gray-600 mb-1">Total Deposito</h3>
            <p class="text-2xl font-bold text-[#674c1d] mb-1">Rp {{ number_format($stats['total_deposito'] ?? 0, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500">{{ $stats['deposito_aktif'] ?? 0 }} deposito aktif</p>
        </div>

        <!-- Total Gadai -->
        <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-linear-to-br from-[#8b6f2f]/20 to-[#d4af37]/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#8b6f2f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
            </div>
            <h3 class="text-sm font-medium text-gray-600 mb-1">Total Gadai</h3>
            <p class="text-2xl font-bold text-[#8b6f2f] mb-1">Rp {{ number_format($stats['total_gadai'] ?? 0, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500">{{ $stats['gadai_aktif'] ?? 0 }} gadai aktif</p>
        </div>

        <!-- Pendapatan Bulan Ini -->
        <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-linear-to-br from-[#d4af37]/20 to-[#674c1d]/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
            <h3 class="text-sm font-medium text-gray-600 mb-1">Pendapatan Bulan Ini</h3>
            <p class="text-2xl font-bold text-[#d4af37] mb-1">Rp {{ number_format($stats['pendapatan_bulan'] ?? 0, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500">Dari bunga & biaya</p>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Pengajuan Pending (Left - 2 columns) -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-md p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-linear-to-br from-[#674c1d] to-[#8b6f2f] rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-primary font-display">Pengajuan Pending</h2>
                </div>
                <a href="{{ route('admin.tabungan.index') }}" class="text-sm text-[#674c1d] hover:underline font-medium">
                    Ke Tabungan →
                </a>
            </div>

            <div class="space-y-4">
                @forelse(($pengajuan_pending ?? []) as $pengajuan)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors border border-gray-200">
                    <div class="flex items-center space-x-4 flex-1">
                        <div class="w-12 h-12 bg-linear-to-br from-[#674c1d]/20 to-[#8b6f2f]/20 rounded-lg flex items-center justify-center">
                            @if($pengajuan['type'] === 'tabungan')
                                <svg class="w-6 h-6 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @elseif($pengajuan['type'] === 'pinjaman')
                                <svg class="w-6 h-6 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            @elseif($pengajuan['type'] === 'deposito')
                                <svg class="w-6 h-6 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            @else
                                <svg class="w-6 h-6 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ $pengajuan['nama'] ?? 'N/A' }}</h3>
                            <p class="text-sm text-gray-600">{{ ucfirst($pengajuan['type']) }} - Rp {{ number_format($pengajuan['nominal'] ?? 0, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $pengajuan['tanggal'] ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">Pending</span>
                        <button class="p-2 text-[#674c1d] hover:bg-[#674c1d]/10 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
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

        <!-- Aktivitas Terkini (Right - 1 column) -->
        <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-linear-to-br from-[#8b6f2f] to-[#d4af37] rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-primary font-display">Aktivitas Terkini</h2>
                </div>
            </div>

            <div class="space-y-4">
                @forelse(($aktivitas_terkini ?? []) as $aktivitas)
                <div class="flex items-start space-x-3 pb-4 border-b border-gray-100 last:border-0 last:pb-0">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0
                        @if($aktivitas['type'] === 'tabungan') bg-blue-100 text-blue-600
                        @elseif($aktivitas['type'] === 'pinjaman') bg-yellow-100 text-yellow-600
                        @elseif($aktivitas['type'] === 'deposito') bg-green-100 text-green-600
                        @else bg-purple-100 text-purple-600
                        @endif">
                        @if($aktivitas['type'] === 'tabungan')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        @elseif($aktivitas['type'] === 'pinjaman')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        @else
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">{{ $aktivitas['deskripsi'] ?? 'Aktivitas' }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $aktivitas['waktu'] ?? 'N/A' }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <p class="text-gray-500 text-sm">Tidak ada aktivitas terkini</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection


