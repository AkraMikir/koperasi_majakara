@extends('layouts.nasabah')

@section('title', 'Dashboard')

@section('content')
<div class="w-full pb-6">
    <!-- Hero Section - Total Assets Overview -->
    <div class="mx-4 mt-4 mb-6">
        <div class="bg-gradient-to-br from-[#674c1d] via-[#8b6f2f] to-[#d4af37] rounded-3xl shadow-2xl p-8 border-2 border-[#d4af37]/30 relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white rounded-full -mr-32 -mt-32"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-white rounded-full -ml-24 -mb-24"></div>
            </div>
            
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <p class="text-white/90 text-sm font-medium mb-2">Total Aset Anda</p>
                        <h1 class="text-4xl md:text-5xl font-bold text-white mb-2 font-display">
                            Rp {{ number_format($stats['total_assets'] ?? 0, 0, ',', '.') }}
                        </h1>
                    </div>
                    <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                
                <!-- Statistik Cards -->
                <div class="grid grid-cols-2 gap-3 mt-6">
                    <!-- Angsuran Terdekat -->
                    @if(isset($stats['angsuran_terdekat']) && $stats['angsuran_terdekat'])
                    <a href="{{ route('nasabah.pinjaman.angsuran') }}" class="bg-white/20 backdrop-blur-sm hover:bg-white/30 rounded-xl p-4 transition-all border border-white/30">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-white/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-white/80 text-xs mb-1">Angsuran Terdekat</p>
                                <p class="text-white font-bold text-sm truncate">Rp {{ number_format($stats['angsuran_terdekat']->jumlah_tagihan - $stats['angsuran_terdekat']->jumlah_terbayar, 0, ',', '.') }}</p>
                                <p class="text-white/70 text-xs">{{ $stats['angsuran_terdekat']->tgl_jatuh_tempo->diffForHumans() }}</p>
                            </div>
                        </div>
                    </a>
                    @else
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-white/60 text-xs mb-1">Angsuran Terdekat</p>
                                <p class="text-white/60 font-medium text-sm">Tidak ada</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Pengajuan Pending -->
                    @if(isset($stats['pengajuan_pending']) && $stats['pengajuan_pending'] > 0)
                    <a href="{{ route('nasabah.pengajuan-pending') }}" class="bg-white/20 backdrop-blur-sm hover:bg-white/30 rounded-xl p-4 transition-all border border-white/30 relative">
                        <div class="absolute top-2 right-2 w-5 h-5 bg-red-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-xs font-bold">{{ $stats['pengajuan_pending'] }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-white/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-white/80 text-xs mb-1">Pengajuan Pending</p>
                                <p class="text-white font-bold text-sm">{{ $stats['pengajuan_pending'] }} pengajuan</p>
                                <p class="text-white/70 text-xs">Menunggu persetujuan</p>
                            </div>
                        </div>
                    </a>
                    @else
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-white/60 text-xs mb-1">Pengajuan Pending</p>
                                <p class="text-white/60 font-medium text-sm">Tidak ada</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Transaksi Bulan Ini -->
                    <a href="{{ route('nasabah.tabungan.index') }}" class="bg-white/20 backdrop-blur-sm hover:bg-white/30 rounded-xl p-4 transition-all border border-white/30">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-white/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-white/80 text-xs mb-1">Transaksi Bulan Ini</p>
                                <p class="text-white font-bold text-sm">{{ $stats['transaksi_bulan_ini']['jumlah'] ?? 0 }} transaksi</p>
                                <p class="text-white/70 text-xs">
                                    @if(isset($stats['transaksi_bulan_ini']['saldo_bersih']))
                                        @if($stats['transaksi_bulan_ini']['saldo_bersih'] > 0)
                                            +Rp {{ number_format($stats['transaksi_bulan_ini']['saldo_bersih'], 0, ',', '.') }}
                                        @elseif($stats['transaksi_bulan_ini']['saldo_bersih'] < 0)
                                            -Rp {{ number_format(abs($stats['transaksi_bulan_ini']['saldo_bersih']), 0, ',', '.') }}
                                        @else
                                            Rp 0
                                        @endif
                                    @else
                                        Rp 0
                                    @endif
                                </p>
                            </div>
                        </div>
                    </a>

                    <!-- Notifikasi Penting -->
                    @if(isset($stats['notifikasi_penting']) && count($stats['notifikasi_penting']) > 0)
                    <a href="{{ $stats['notifikasi_penting'][0]['link'] }}" class="bg-white/20 backdrop-blur-sm hover:bg-white/30 rounded-xl p-4 transition-all border border-white/30 relative">
                        <div class="absolute top-2 right-2 w-5 h-5 bg-yellow-500 rounded-full flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-white/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-white/80 text-xs mb-1">Notifikasi</p>
                                <p class="text-white font-bold text-sm truncate">{{ $stats['notifikasi_penting'][0]['message'] }}</p>
                            </div>
                        </div>
                    </a>
                    @else
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-white/60 text-xs mb-1">Notifikasi</p>
                                <p class="text-white/60 font-medium text-sm">Tidak ada</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Product Cards Section -->
    <div class="mx-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Tabungan Card -->
            <a href="{{ route('nasabah.tabungan.index') }}" class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300 hover:border-[#674c1d]/30 group">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-[#674c1d]/20 to-[#674c1d]/10 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-[#674c1d] bg-[#674c1d]/10 px-3 py-1 rounded-full">Aktif</span>
                </div>
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Tabungan</h3>
                <p class="text-2xl font-bold text-[#674c1d] mb-1">Rp {{ number_format($stats['saldo_tabungan'] ?? 0, 0, ',', '.') }}</p>
                <p class="text-xs text-gray-500">Saldo saat ini</p>
                <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-xs text-gray-500">Lihat Detail</span>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-[#674c1d] group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>

            <!-- Pinjaman Card -->
            <a href="{{ route('nasabah.pinjaman.index') }}" class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300 hover:border-[#8b6f2f]/30 group">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-[#8b6f2f]/20 to-[#8b6f2f]/10 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-[#8b6f2f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    @if($stats['pinjaman_aktif_count'] > 0)
                        <span class="text-xs font-semibold text-orange-600 bg-orange-100 px-3 py-1 rounded-full">Aktif</span>
                    @else
                        <span class="text-xs font-semibold text-gray-600 bg-gray-100 px-3 py-1 rounded-full">Tidak Ada</span>
                    @endif
                </div>
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Pinjaman</h3>
                @if($stats['pinjaman_aktif_count'] > 0)
                    <p class="text-2xl font-bold text-gray-900 mb-1">Rp {{ number_format($stats['sisa_pinjaman'] ?? 0, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500">Sisa pinjaman ({{ $stats['pinjaman_aktif_count'] }} aktif)</p>
                @else
                    <p class="text-2xl font-bold text-gray-400 mb-1">Rp 0</p>
                    <p class="text-xs text-gray-500">Tidak ada pinjaman aktif</p>
                @endif
                <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-xs text-gray-500">Lihat Detail</span>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-[#8b6f2f] group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>

            <!-- Deposito Card -->
            <a href="#" class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300 hover:border-[#4a3514]/30 group">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-[#4a3514]/20 to-[#4a3514]/10 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-[#4a3514]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    @if($stats['deposito_aktif_count'] > 0)
                        <span class="text-xs font-semibold text-[#674c1d] bg-[#674c1d]/10 px-3 py-1 rounded-full">Aktif</span>
                    @else
                        <span class="text-xs font-semibold text-gray-600 bg-gray-100 px-3 py-1 rounded-full">Tidak Ada</span>
                    @endif
                </div>
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Deposito</h3>
                @if($stats['deposito_aktif_count'] > 0)
                    <p class="text-2xl font-bold text-[#674c1d] mb-1">Rp {{ number_format($stats['total_deposito'] ?? 0, 0, ',', '.') }}</p>
                    @if(isset($stats['deposito_terdekat']) && $stats['deposito_terdekat'])
                        <p class="text-xs text-gray-500">Jatuh tempo: {{ $stats['deposito_terdekat']->tgl_jatuh_tempo->format('d M Y') }}</p>
                    @else
                        <p class="text-xs text-gray-500">{{ $stats['deposito_aktif_count'] }} deposito aktif</p>
                    @endif
                @else
                    <p class="text-2xl font-bold text-gray-400 mb-1">Rp 0</p>
                    <p class="text-xs text-gray-500">Tidak ada deposito aktif</p>
                @endif
                <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-xs text-gray-500">Lihat Detail</span>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-[#4a3514] group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>

            <!-- Gadai Card -->
            <a href="#" class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300 hover:border-[#d4af37]/30 group">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-[#d4af37]/20 to-[#d4af37]/10 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    @if($stats['gadai_aktif_count'] > 0)
                        <span class="text-xs font-semibold text-[#674c1d] bg-[#674c1d]/10 px-3 py-1 rounded-full">Aktif</span>
                    @else
                        <span class="text-xs font-semibold text-gray-600 bg-gray-100 px-3 py-1 rounded-full">Tidak Ada</span>
                    @endif
                </div>
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Gadai</h3>
                @if($stats['gadai_aktif_count'] > 0)
                    <p class="text-2xl font-bold text-gray-900 mb-1">Rp {{ number_format($stats['total_gadai'] ?? 0, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500">{{ $stats['gadai_aktif_count'] }} gadai aktif</p>
                @else
                    <p class="text-2xl font-bold text-gray-400 mb-1">Rp 0</p>
                    <p class="text-xs text-gray-500">Tidak ada gadai aktif</p>
                @endif
                <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-xs text-gray-500">Lihat Detail</span>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-[#d4af37] group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>
        </div>
    </div>

    <!-- Aktivitas Terkini -->
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Aktivitas Terkini</h2>
                        <p class="text-xs text-gray-500">Transaksi terbaru Anda</p>
                    </div>
                </div>
                <a href="{{ route('nasabah.tabungan.index') }}" class="text-sm text-[#674c1d] font-medium hover:underline">Lihat Semua</a>
            </div>

            <div class="space-y-3">
                @forelse($transaksiTerbaru ?? [] as $transaksi)
                @php
                    $isSetoran = optional($transaksi->jnsTransaksi)->kode === 'STR';
                    $jenisNama = optional($transaksi->jnsTransaksi)->nama ?? 'Transaksi';
                    $viaNama = optional($transaksi->jnsVia)->nama ?? 'Manual';
                @endphp
                <a href="{{ route('nasabah.tabungan.detail-transaksi', $transaksi->id) }}" class="block p-4 bg-gray-50 rounded-xl border border-gray-200 hover:border-[#674c1d]/30 hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 {{ $isSetoran ? 'bg-green-100' : 'bg-red-100' }} rounded-lg flex items-center justify-center">
                                @if($isSetoran)
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ ucfirst($jenisNama) }}</p>
                                <p class="text-sm text-gray-500">{{ $transaksi->tgl_transaksi->format('d M Y, H:i') }} • {{ ucfirst($viaNama) }}</p>
                            </div>
                        </div>
                        <div class="text-right flex items-center gap-2">
                            <div>
                                <p class="font-bold {{ $isSetoran ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $isSetoran ? '+' : '-' }}Rp {{ number_format($transaksi->nominal, 0, ',', '.') }}
                                </p>
                                <p class="text-xs text-gray-500 font-mono">{{ $transaksi->id }}</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </a>
                @empty
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <p class="text-gray-500">Belum ada aktivitas</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    </div>
@endsection
