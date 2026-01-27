@extends('layouts.admin')

@section('title', 'Master Data')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Master Data</h1>
            <p class="text-gray-600 mt-1">Kelola semua data master sistem koperasi</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-7 gap-6">
        <!-- Bunga Pinjaman -->
        <a href="{{ route('admin.master-data.bunga-pinjaman.index') }}" class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-[#674c1d]/20 to-[#674c1d]/10 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <span class="px-3 py-1 bg-[#674c1d]/10 text-[#674c1d] rounded-full text-xs font-semibold">{{ $stats['total_bunga_pinjaman'] }}</span>
            </div>
            <h3 class="text-sm font-medium text-gray-600 mb-1">Bunga Pinjaman</h3>
            <p class="text-3xl font-bold text-[#674c1d] mb-1">{{ $stats['total_bunga_pinjaman'] }}</p>
            <p class="text-xs text-gray-500">Data aktif</p>
        </a>

        <!-- Denda Pinjaman -->
        <a href="{{ route('admin.master-data.denda-pinjaman.index') }}" class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-[#8b6f2f]/20 to-[#8b6f2f]/10 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-[#8b6f2f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="px-3 py-1 bg-[#8b6f2f]/10 text-[#8b6f2f] rounded-full text-xs font-semibold">{{ $stats['total_denda_pinjaman'] }}</span>
            </div>
            <h3 class="text-sm font-medium text-gray-600 mb-1">Denda Pinjaman</h3>
            <p class="text-3xl font-bold text-[#8b6f2f] mb-1">{{ $stats['total_denda_pinjaman'] }}</p>
            <p class="text-xs text-gray-500">Data aktif</p>
        </a>

        <!-- Suku Bunga Tabungan -->
        <a href="{{ route('admin.master-data.suku-bunga-tabungan.index') }}" class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-[#4a3514]/20 to-[#4a3514]/10 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-[#4a3514]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="px-3 py-1 bg-[#4a3514]/10 text-[#4a3514] rounded-full text-xs font-semibold">{{ $stats['total_suku_bunga_tabungan'] }}</span>
            </div>
            <h3 class="text-sm font-medium text-gray-600 mb-1">Suku Bunga Tabungan</h3>
            <p class="text-3xl font-bold text-[#4a3514] mb-1">{{ $stats['total_suku_bunga_tabungan'] }}</p>
            <p class="text-xs text-gray-500">Data master</p>
        </a>

        <!-- Tenor Deposito -->
        <a href="{{ route('admin.master-data.tenor-deposito.index') }}" class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-[#d4af37]/20 to-[#d4af37]/10 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <span class="px-3 py-1 bg-[#d4af37]/10 text-[#d4af37] rounded-full text-xs font-semibold">{{ $stats['total_tenor_deposito'] }}</span>
            </div>
            <h3 class="text-sm font-medium text-gray-600 mb-1">Tenor Deposito</h3>
            <p class="text-3xl font-bold text-[#d4af37] mb-1">{{ $stats['total_tenor_deposito'] }}</p>
            <p class="text-xs text-gray-500">Data aktif</p>
        </a>

        <!-- Barang Gadai -->
        <a href="{{ route('admin.master-data.barang-gadai.index') }}" class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-[#8b6f2f]/20 to-[#d4af37]/20 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-[#8b6f2f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <span class="px-3 py-1 bg-[#8b6f2f]/10 text-[#8b6f2f] rounded-full text-xs font-semibold">{{ $stats['total_barang_gadai'] }}</span>
            </div>
            <h3 class="text-sm font-medium text-gray-600 mb-1">Barang Gadai</h3>
            <p class="text-3xl font-bold text-[#8b6f2f] mb-1">{{ $stats['total_barang_gadai'] }}</p>
            <p class="text-xs text-gray-500">Jenis barang</p>
        </a>

        <!-- Lokasi Perusahaan -->
        <a href="{{ route('admin.master-data.lokasi-perusahaan.index') }}" class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-[#674c1d]/20 to-[#8b6f2f]/20 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <span class="px-3 py-1 bg-[#674c1d]/10 text-[#674c1d] rounded-full text-xs font-semibold">{{ $stats['total_lokasi_perusahaan'] }}</span>
            </div>
            <h3 class="text-sm font-medium text-gray-600 mb-1">Lokasi Perusahaan</h3>
            <p class="text-3xl font-bold text-[#674c1d] mb-1">{{ $stats['total_lokasi_perusahaan'] }}</p>
            <p class="text-xs text-gray-500">Kantor aktif</p>
        </a>

        <!-- Jenis Deposito -->
        <a href="{{ route('admin.master-data.jenis-deposito.index') }}" class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-[#d4af37]/20 to-[#8b6f2f]/20 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <span class="px-3 py-1 bg-[#d4af37]/10 text-[#d4af37] rounded-full text-xs font-semibold">{{ $stats['total_jenis_deposito'] }}</span>
            </div>
            <h3 class="text-sm font-medium text-gray-600 mb-1">Jenis Deposito</h3>
            <p class="text-3xl font-bold text-[#d4af37] mb-1">{{ $stats['total_jenis_deposito'] }}</p>
            <p class="text-xs text-gray-500">Tipe deposito</p>
        </a>

        <!-- Jenis Akun -->
        <a href="{{ route('admin.master-data.jns-akun.index') }}" class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-purple-500/20 to-purple-600/10 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-semibold">4</span>
            </div>
            <h3 class="text-sm font-medium text-gray-600 mb-1">Jenis Akun</h3>
            <p class="text-3xl font-bold text-purple-600 mb-1">4</p>
            <p class="text-xs text-gray-500">Tipe akun</p>
        </a>

        <!-- Biaya Transfer -->
        <a href="{{ route('admin.master-data.biaya-transfer.index') }}" class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-blue-500/20 to-blue-600/10 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                </div>
                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">17</span>
            </div>
            <h3 class="text-sm font-medium text-gray-600 mb-1">Biaya Transfer</h3>
            <p class="text-3xl font-bold text-blue-600 mb-1">17</p>
            <p class="text-xs text-gray-500">Antar bank</p>
        </a>
    </div>

    <!-- Menu Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Pinjaman Section -->
        <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-primary font-display">Master Data Pinjaman</h2>
                </div>
            </div>
            <div class="space-y-3">
                <a href="{{ route('admin.master-data.bunga-pinjaman.index') }}" class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors border border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-[#674c1d]/20 to-[#8b6f2f]/20 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Master Bunga Pinjaman</p>
                            <p class="text-xs text-gray-500">{{ $stats['total_bunga_pinjaman'] }} data aktif</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
                <a href="{{ route('admin.master-data.denda-pinjaman.index') }}" class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors border border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-[#674c1d]/20 to-[#8b6f2f]/20 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Master Denda Pinjaman</p>
                            <p class="text-xs text-gray-500">{{ $stats['total_denda_pinjaman'] }} data aktif</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Tabungan & Deposito Section -->
        <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-[#8b6f2f] to-[#d4af37] rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-primary font-display">Tabungan & Deposito</h2>
                </div>
            </div>
            <div class="space-y-3">
                <a href="{{ route('admin.master-data.suku-bunga-tabungan.index') }}" class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors border border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-[#8b6f2f]/20 to-[#d4af37]/20 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#8b6f2f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Suku Bunga Tabungan</p>
                            <p class="text-xs text-gray-500">{{ $stats['total_suku_bunga_tabungan'] }} data</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
                <a href="{{ route('admin.master-data.tenor-deposito.index') }}" class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors border border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-[#8b6f2f]/20 to-[#d4af37]/20 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#8b6f2f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Master Tenor Deposito</p>
                            <p class="text-xs text-gray-500">{{ $stats['total_tenor_deposito'] }} data aktif</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
                <a href="{{ route('admin.master-data.suku-bunga-deposito.index') }}" class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors border border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-[#8b6f2f]/20 to-[#d4af37]/20 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#8b6f2f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Suku Bunga Deposito</p>
                            <p class="text-xs text-gray-500">Data berdasarkan tenor</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
                <a href="{{ route('admin.master-data.jenis-deposito.index') }}" class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors border border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-[#8b6f2f]/20 to-[#d4af37]/20 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#8b6f2f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Jenis Deposito</p>
                            <p class="text-xs text-gray-500">{{ $stats['total_jenis_deposito'] }} jenis</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Gadai & Lainnya Section -->
    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-[#4a3514] to-[#674c1d] rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-primary font-display">Master Data Lainnya</h2>
            </div>
            <a href="{{ route('admin.master-data.barang-gadai.index') }}" class="text-sm text-[#674c1d] hover:underline font-medium">
                Lihat Semua →
            </a>
        </div>
        <div class="space-y-3">
            <a href="{{ route('admin.master-data.barang-gadai.index') }}" class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors border border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-[#4a3514]/20 to-[#674c1d]/20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-[#4a3514]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Master Barang Gadai</p>
                        <p class="text-xs text-gray-500">{{ $stats['total_barang_gadai'] }} jenis barang</p>
                    </div>
                </div>
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
            <a href="{{ route('admin.master-data.lokasi-perusahaan.index') }}" class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors border border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-[#674c1d]/20 to-[#8b6f2f]/20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Lokasi Perusahaan</p>
                        <p class="text-xs text-gray-500">{{ $stats['total_lokasi_perusahaan'] }} kantor</p>
                    </div>
                </div>
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
            <a href="{{ route('admin.master-data.jenis-deposito.index') }}" class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors border border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-[#d4af37]/20 to-[#8b6f2f]/20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Jenis Deposito</p>
                        <p class="text-xs text-gray-500">{{ $stats['total_jenis_deposito'] }} jenis</p>
                    </div>
                </div>
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>
</div>
@endsection
