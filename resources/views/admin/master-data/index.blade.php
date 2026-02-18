@extends('layouts.admin')

@section('title', 'Master Data')

@section('content')
<div class="space-y-8">

    {{-- ===== PAGE HEADER ===== --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Master Data</h1>
            <p class="text-gray-500 mt-1">Kelola semua konfigurasi dan data referensi sistem koperasi</p>
        </div>
        <div class="hidden md:flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-xl shadow-sm">
            <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
            <span class="text-sm text-gray-600 font-medium">Sistem Aktif</span>
        </div>
    </div>

    {{-- ===== RINGKASAN STATISTIK ===== --}}
    {{-- Baris 1: Pinjaman (2 kartu) + Gadai & Lainnya (3 kartu) --}}
    <div class="space-y-4">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Ringkasan Data</p>

        {{-- Baris 1: Pinjaman & Gadai --}}
        <div class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-4 gap-4">
            {{-- Bunga Pinjaman --}}
            <a href="{{ route('admin.master-data.bunga-pinjaman.index') }}"
                class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-[#674c1d]/30 transition-all duration-200">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] rounded-xl flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 group-hover:text-[#674c1d] transition-colors mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
                <p class="text-2xl font-bold text-gray-900 mb-0.5">{{ $stats['total_bunga_pinjaman'] }}</p>
                <p class="text-sm font-medium text-gray-700">Bunga Pinjaman</p>
                <p class="text-xs text-gray-400 mt-0.5">Data aktif</p>
            </a>

            {{-- Denda Pinjaman --}}
            <a href="{{ route('admin.master-data.denda-pinjaman.index') }}"
                class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-[#674c1d]/30 transition-all duration-200">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 group-hover:text-[#674c1d] transition-colors mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
                <p class="text-2xl font-bold text-gray-900 mb-0.5">{{ $stats['total_denda_pinjaman'] }}</p>
                <p class="text-sm font-medium text-gray-700">Denda Pinjaman</p>
                <p class="text-xs text-gray-400 mt-0.5">Data aktif</p>
            </a>

            {{-- Barang Gadai --}}
            <a href="{{ route('admin.master-data.barang-gadai.index') }}"
                class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-[#674c1d]/30 transition-all duration-200">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 group-hover:text-[#674c1d] transition-colors mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
                <p class="text-2xl font-bold text-gray-900 mb-0.5">{{ $stats['total_barang_gadai'] }}</p>
                <p class="text-sm font-medium text-gray-700">Barang Gadai</p>
                <p class="text-xs text-gray-400 mt-0.5">Jenis barang</p>
            </a>

            {{-- Lokasi Perusahaan --}}
            <a href="{{ route('admin.master-data.lokasi-perusahaan.index') }}"
                class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-[#674c1d]/30 transition-all duration-200">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 group-hover:text-[#674c1d] transition-colors mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
                <p class="text-2xl font-bold text-gray-900 mb-0.5">{{ $stats['total_lokasi_perusahaan'] }}</p>
                <p class="text-sm font-medium text-gray-700">Lokasi Perusahaan</p>
                <p class="text-xs text-gray-400 mt-0.5">Kantor aktif</p>
            </a>
        </div>

        {{-- Baris 2: Deposito & Lainnya --}}
        <div class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-4 gap-4">
            {{-- Tenor Deposito --}}
            <a href="{{ route('admin.master-data.tenor-deposito.index') }}"
                class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-[#674c1d]/30 transition-all duration-200">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-[#d4af37] to-[#b8960c] rounded-xl flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 group-hover:text-[#674c1d] transition-colors mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
                <p class="text-2xl font-bold text-gray-900 mb-0.5">{{ $stats['total_tenor_deposito'] }}</p>
                <p class="text-sm font-medium text-gray-700">Tenor Deposito</p>
                <p class="text-xs text-gray-400 mt-0.5">Data aktif</p>
            </a>

            {{-- Jenis Deposito --}}
            <a href="{{ route('admin.master-data.jenis-deposito.index') }}"
                class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-[#674c1d]/30 transition-all duration-200">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 group-hover:text-[#674c1d] transition-colors mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
                <p class="text-2xl font-bold text-gray-900 mb-0.5">{{ $stats['total_jenis_deposito'] }}</p>
                <p class="text-sm font-medium text-gray-700">Jenis Deposito</p>
                <p class="text-xs text-gray-400 mt-0.5">Tipe deposito</p>
            </a>

            {{-- Biaya Transfer --}}
            <a href="{{ route('admin.master-data.biaya-transfer.index') }}"
                class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-[#674c1d]/30 transition-all duration-200">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-sky-500 to-sky-600 rounded-xl flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 group-hover:text-[#674c1d] transition-colors mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
                <p class="text-2xl font-bold text-gray-900 mb-0.5">17</p>
                <p class="text-sm font-medium text-gray-700">Biaya Transfer</p>
                <p class="text-xs text-gray-400 mt-0.5">Antar bank</p>
            </a>

            {{-- Rekening Perusahaan --}}
            <a href="{{ route('admin.master-data.rekening-perusahaan.index') }}"
                class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-[#674c1d]/30 transition-all duration-200">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 group-hover:text-[#674c1d] transition-colors mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
                <p class="text-2xl font-bold text-gray-900 mb-0.5">{{ $stats['total_rekening_perusahaan'] ?? 0 }}</p>
                <p class="text-sm font-medium text-gray-700">Rekening Perusahaan</p>
                <p class="text-xs text-gray-400 mt-0.5">BCA, Mandiri, dll</p>
            </a>

            {{-- Admin Operasional (hanya tampil untuk Admin Utama) --}}
            @isAdminUtama
            <a href="{{ route('admin.master-data.admin-operasional.index') }}"
                class="group bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] rounded-2xl border border-[#674c1d] shadow-sm p-5 hover:shadow-lg transition-all duration-200">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <svg class="w-4 h-4 text-white/50 group-hover:text-white transition-colors mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
                <p class="text-2xl font-bold text-white mb-0.5">{{ $stats['total_admin_operasional'] }}</p>
                <p class="text-sm font-medium text-white/90">Admin Operasional</p>
                <p class="text-xs text-white/60 mt-0.5">Admin aktif</p>
            </a>
            @endisAdminUtama
        </div>
    </div>

    {{-- ===== MENU NAVIGASI MASTER DATA ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kolom 1: Pinjaman --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] flex items-center gap-3">
                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h2 class="text-white font-bold text-base">Master Data Pinjaman</h2>
            </div>
            <div class="p-4 space-y-2">
                <a href="{{ route('admin.master-data.bunga-pinjaman.index') }}"
                    class="flex items-center justify-between p-3.5 rounded-xl hover:bg-[#674c1d]/5 transition-colors group">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-[#674c1d]/10 rounded-lg flex items-center justify-center">
                            <svg class="w-4.5 h-4.5 text-[#674c1d]" style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Bunga Pinjaman</p>
                            <p class="text-xs text-gray-400">{{ $stats['total_bunga_pinjaman'] }} data aktif</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 group-hover:text-[#674c1d] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
                <a href="{{ route('admin.master-data.denda-pinjaman.index') }}"
                    class="flex items-center justify-between p-3.5 rounded-xl hover:bg-[#674c1d]/5 transition-colors group">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-red-50 rounded-lg flex items-center justify-center">
                            <svg class="w-4.5 h-4.5 text-red-500" style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Denda Pinjaman</p>
                            <p class="text-xs text-gray-400">{{ $stats['total_denda_pinjaman'] }} data aktif</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 group-hover:text-[#674c1d] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>

        {{-- Kolom 2: Tabungan & Deposito --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 bg-gradient-to-r from-[#8b6f2f] to-[#d4af37] flex items-center gap-3">
                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-white font-bold text-base">Tabungan & Deposito</h2>
            </div>
            <div class="p-4 space-y-2">
                <a href="{{ route('admin.master-data.tenor-deposito.index') }}"
                    class="flex items-center justify-between p-3.5 rounded-xl hover:bg-[#674c1d]/5 transition-colors group">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-yellow-50 rounded-lg flex items-center justify-center">
                            <svg class="w-4.5 h-4.5 text-yellow-600" style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Tenor Deposito</p>
                            <p class="text-xs text-gray-400">{{ $stats['total_tenor_deposito'] }} data aktif</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 group-hover:text-[#674c1d] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
                <a href="{{ route('admin.master-data.suku-bunga-deposito.index') }}"
                    class="flex items-center justify-between p-3.5 rounded-xl hover:bg-[#674c1d]/5 transition-colors group">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-[#d4af37]/10 rounded-lg flex items-center justify-center">
                            <svg class="w-4.5 h-4.5 text-[#d4af37]" style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Suku Bunga Deposito</p>
                            <p class="text-xs text-gray-400">Berdasarkan tenor</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 group-hover:text-[#674c1d] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
                <a href="{{ route('admin.master-data.jenis-deposito.index') }}"
                    class="flex items-center justify-between p-3.5 rounded-xl hover:bg-[#674c1d]/5 transition-colors group">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-purple-50 rounded-lg flex items-center justify-center">
                            <svg class="w-4.5 h-4.5 text-purple-500" style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Jenis Deposito</p>
                            <p class="text-xs text-gray-400">{{ $stats['total_jenis_deposito'] }} jenis</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 group-hover:text-[#674c1d] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>

        {{-- Kolom 3: Gadai, Transfer, & Admin --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 bg-gradient-to-r from-[#4a3514] to-[#674c1d] flex items-center gap-3">
                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h2 class="text-white font-bold text-base">Gadai & Operasional</h2>
            </div>
            <div class="p-4 space-y-2">
                <a href="{{ route('admin.master-data.barang-gadai.index') }}"
                    class="flex items-center justify-between p-3.5 rounded-xl hover:bg-[#674c1d]/5 transition-colors group">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-amber-50 rounded-lg flex items-center justify-center">
                            <svg class="w-4.5 h-4.5 text-amber-600" style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Barang Gadai</p>
                            <p class="text-xs text-gray-400">{{ $stats['total_barang_gadai'] }} jenis barang</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 group-hover:text-[#674c1d] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
                <a href="{{ route('admin.master-data.lokasi-perusahaan.index') }}"
                    class="flex items-center justify-between p-3.5 rounded-xl hover:bg-[#674c1d]/5 transition-colors group">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-emerald-50 rounded-lg flex items-center justify-center">
                            <svg class="w-4.5 h-4.5 text-emerald-600" style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Lokasi Perusahaan</p>
                            <p class="text-xs text-gray-400">{{ $stats['total_lokasi_perusahaan'] }} kantor</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 group-hover:text-[#674c1d] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
                <a href="{{ route('admin.master-data.biaya-transfer.index') }}"
                    class="flex items-center justify-between p-3.5 rounded-xl hover:bg-[#674c1d]/5 transition-colors group">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-sky-50 rounded-lg flex items-center justify-center">
                            <svg class="w-4.5 h-4.5 text-sky-600" style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Biaya Transfer</p>
                            <p class="text-xs text-gray-400">Antar bank</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 group-hover:text-[#674c1d] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>

                {{-- Admin Operasional link - hanya Admin Utama --}}
                @isAdminUtama
                <div class="border-t border-dashed border-gray-200 my-1"></div>
                <a href="{{ route('admin.master-data.admin-operasional.index') }}"
                    class="flex items-center justify-between p-3.5 rounded-xl bg-gradient-to-r from-[#674c1d]/5 to-[#8b6f2f]/5 hover:from-[#674c1d]/10 hover:to-[#8b6f2f]/10 border border-[#674c1d]/15 transition-colors group">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] rounded-lg flex items-center justify-center shadow-sm">
                            <svg class="w-4.5 h-4.5 text-white" style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5">
                                <p class="text-sm font-semibold text-gray-800">Admin Operasional</p>
                                <span class="px-1.5 py-0.5 bg-[#674c1d] text-white rounded text-xs font-bold leading-none">{{ $stats['total_admin_operasional'] }}</span>
                            </div>
                            <p class="text-xs text-gray-400">Manajemen akun admin</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-[#674c1d] group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
                @endisAdminUtama
            </div>
        </div>
    </div>

</div>
@endsection
