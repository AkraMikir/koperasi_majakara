@extends('layouts.admin')

@section('title', 'Master Data')

@section('content')
<div class="space-y-8 pb-12 animate-fade-in">

    {{-- ===== PAGE HEADER ===== --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-gray-100 pb-5">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Master Data</h1>
            <p class="text-gray-500 mt-1">Kelola semua konfigurasi, tarif, dan data referensi sistem Koperasi Majakara</p>
        </div>
        <div class="flex items-center gap-2.5 px-4 py-2 bg-white border border-gray-100 rounded-xl shadow-sm self-start md:self-auto">
            <span class="relative flex h-2.5 w-2.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
            </span>
            <span class="text-xs text-gray-600 font-bold uppercase tracking-wider">Sistem Aktif</span>
        </div>
    </div>

    {{-- ===== GRUP 1: PINJAMAN & DENDA ===== --}}
    <div class="space-y-4">
        <div class="flex items-center gap-3 border-l-4 border-majakara-brown pl-3">
            <h2 class="text-lg font-bold text-gray-900 font-display">Master Pinjaman & Denda</h2>
            <span class="w-1.5 h-1.5 bg-gray-300 rounded-full"></span>
            <p class="text-xs text-gray-500 font-medium">Bunga pinjaman dan sanksi keterlambatan</p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            {{-- Bunga Pinjaman --}}
            <a href="{{ route('admin.master-data.bunga-pinjaman.index') }}"
                class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-md hover:border-majakara-brown/30 transition-all duration-200 flex flex-col justify-between h-full">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-11 h-11 bg-gradient-to-br from-majakara-brown to-majakara-dark-gold rounded-xl flex items-center justify-center text-white shadow-sm transition-transform group-hover:scale-110">
                            <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <span class="px-2.5 py-1 bg-majakara-gold/10 text-majakara-brown rounded-lg text-xs font-bold border border-majakara-gold/20">
                            {{ $stats['total_bunga_pinjaman'] }} data aktif
                        </span>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 mb-1.5 group-hover:text-majakara-brown transition-colors">Bunga Pinjaman</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Atur besaran persentase bunga pinjaman berdasarkan jangka waktu (tenor) pengajuan.</p>
                </div>
                <div class="flex items-center justify-end mt-4 pt-3 border-t border-gray-50">
                    <span class="text-xs font-semibold text-majakara-brown group-hover:underline flex items-center gap-1">
                        Kelola
                        <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </span>
                </div>
            </a>

            {{-- Denda Pinjaman --}}
            <a href="{{ route('admin.master-data.denda-pinjaman.index') }}"
                class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-md hover:border-red-500/30 transition-all duration-200 flex flex-col justify-between h-full">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-11 h-11 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center text-white shadow-sm transition-transform group-hover:scale-110">
                            <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="px-2.5 py-1 bg-red-50 text-red-600 rounded-lg text-xs font-bold border border-red-100">
                            {{ $stats['total_denda_pinjaman'] }} denda aktif
                        </span>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 mb-1.5 group-hover:text-red-600 transition-colors">Denda Pinjaman</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Konfigurasi persentase denda keterlambatan pembayaran angsuran untuk nasabah pinjaman.</p>
                </div>
                <div class="flex items-center justify-end mt-4 pt-3 border-t border-gray-50">
                    <span class="text-xs font-semibold text-red-600 group-hover:underline flex items-center gap-1">
                        Kelola
                        <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </span>
                </div>
            </a>

            {{-- Pinjaman Debugger (hanya tampil untuk Admin Utama) --}}
            @isAdminUtama
            <a href="{{ route('admin.master-data.pinjaman-debugger.index') }}"
                class="group bg-white rounded-2xl border border-indigo-100 shadow-sm p-6 hover:shadow-md hover:border-indigo-500/30 transition-all duration-200 flex flex-col justify-between h-full">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-11 h-11 bg-gradient-to-br from-indigo-600 to-indigo-700 rounded-xl flex items-center justify-center text-white shadow-sm transition-transform group-hover:scale-110">
                            <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="px-2.5 py-1 bg-indigo-50 text-indigo-600 rounded-lg text-xs font-bold border border-indigo-100">
                            Simulator
                        </span>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 mb-1.5 group-hover:text-indigo-600 transition-colors">Pinjaman Debugger</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Simulator pengujian jatuh tempo pinjaman (time travel), simulasi kalkulasi bunga dan denda.</p>
                </div>
                <div class="flex items-center justify-end mt-4 pt-3 border-t border-gray-50">
                    <span class="text-xs font-semibold text-indigo-600 group-hover:underline flex items-center gap-1">
                        Kelola
                        <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </span>
                </div>
            </a>
            @endisAdminUtama
        </div>
    </div>

    {{-- ===== GRUP 2: DEPOSITO & TABUNGAN ===== --}}
    <div class="space-y-4">
        <div class="flex items-center gap-3 border-l-4 border-majakara-brown pl-3">
            <h2 class="text-lg font-bold text-gray-900 font-display">Master Deposito & Tabungan</h2>
            <span class="w-1.5 h-1.5 bg-gray-300 rounded-full"></span>
            <p class="text-xs text-gray-500 font-medium">Pengaturan jangka waktu dan bagi hasil deposito</p>
        </div>


        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            {{-- Tenor Deposito --}}
            <a href="{{ route('admin.master-data.tenor-deposito.index') }}"
                class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-md hover:border-yellow-600/30 transition-all duration-200 flex flex-col justify-between h-full">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-11 h-11 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center text-white shadow-sm transition-transform group-hover:scale-110">
                            <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <span class="px-2.5 py-1 bg-yellow-50 text-yellow-700 rounded-lg text-xs font-bold border border-yellow-100">
                            {{ $stats['total_tenor_deposito'] }} tenor aktif
                        </span>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 mb-1.5 group-hover:text-yellow-700 transition-colors">Tenor Deposito</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Kelola pilihan jangka waktu (bulan) untuk penempatan dana investasi deposito berjangka.</p>
                </div>
                <div class="flex items-center justify-end mt-4 pt-3 border-t border-gray-50">
                    <span class="text-xs font-semibold text-yellow-700 group-hover:underline flex items-center gap-1">
                        Kelola
                        <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </span>
                </div>
            </a>

            {{-- Kategori Deposito --}}
            <a href="{{ route('admin.master-data.kategori-deposito.index') }}"
                class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-md hover:border-orange-500/30 transition-all duration-200 flex flex-col justify-between h-full">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-11 h-11 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center text-white shadow-sm transition-transform group-hover:scale-110">
                            <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                        </div>
                        <span class="px-2.5 py-1 bg-orange-50 text-orange-600 rounded-lg text-xs font-bold border border-orange-100">
                            {{ $stats['total_kategori_deposito'] }} kategori
                        </span>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 mb-1.5 group-hover:text-orange-600 transition-colors">Kategori Deposito</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Atur kategori produk deposito, program khusus, maupun promo suku bunga investasi.</p>
                </div>
                <div class="flex items-center justify-end mt-4 pt-3 border-t border-gray-50">
                    <span class="text-xs font-semibold text-orange-600 group-hover:underline flex items-center gap-1">
                        Kelola
                        <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </span>
                </div>
            </a>

            {{-- Denda Deposito --}}
            <a href="{{ route('admin.master-data.denda-deposito.index') }}"
                class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-md hover:border-red-500/30 transition-all duration-200 flex flex-col justify-between h-full">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-11 h-11 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center text-white shadow-sm transition-transform group-hover:scale-110">
                            <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <span class="px-2.5 py-1 bg-red-50 text-red-600 rounded-lg text-xs font-bold border border-red-100">
                            {{ $stats['total_denda_deposito'] }} aktif
                        </span>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 mb-1.5 group-hover:text-red-600 transition-colors">Denda Deposito</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Atur persentase denda pembatalan deposito sebelum jatuh tempo.</p>
                </div>
                <div class="flex items-center justify-end mt-4 pt-3 border-t border-gray-50">
                    <span class="text-xs font-semibold text-red-600 group-hover:underline flex items-center gap-1">
                        Kelola
                        <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </span>
                </div>
            </a>

            {{-- Deposito Debugger (hanya tampil untuk Admin Utama) --}}
            @isAdminUtama
            <a href="{{ route('admin.master-data.deposito-debugger.index') }}"
                class="group bg-white rounded-2xl border border-indigo-100 shadow-sm p-6 hover:shadow-md hover:border-indigo-500/30 transition-all duration-200 flex flex-col justify-between h-full">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-11 h-11 bg-gradient-to-br from-indigo-600 to-indigo-700 rounded-xl flex items-center justify-center text-white shadow-sm transition-transform group-hover:scale-110">
                            <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="px-2.5 py-1 bg-indigo-50 text-indigo-600 rounded-lg text-xs font-bold border border-indigo-100">
                            Simulator
                        </span>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 mb-1.5 group-hover:text-indigo-600 transition-colors">Deposito Debugger</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Penguji jatuh tempo pencairan deposito (time travel), dan simulasi perolehan bagi hasil.</p>
                </div>
                <div class="flex items-center justify-end mt-4 pt-3 border-t border-gray-50">
                    <span class="text-xs font-semibold text-indigo-600 group-hover:underline flex items-center gap-1">
                        Kelola
                        <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </span>
                </div>
            </a>
            @endisAdminUtama
        </div>
    </div>

    {{-- ===== GRUP 3: GADAI & JAMINAN ===== --}}
    <div class="space-y-4">
        <div class="flex items-center gap-3 border-l-4 border-majakara-brown pl-3">
            <h2 class="text-lg font-bold text-gray-900 font-display">Master Gadai & Jaminan</h2>
            <span class="w-1.5 h-1.5 bg-gray-300 rounded-full"></span>
            <p class="text-xs text-gray-500 font-medium">Aturan taksiran barang, sewa tempat, dan penyimpanan jaminan</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            {{-- Kategori Gadai --}}
            <a href="{{ route('admin.master-data.kategori-gadai.index') }}"
                class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-md hover:border-blue-500/30 transition-all duration-200 flex flex-col justify-between h-full">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-11 h-11 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white shadow-sm transition-transform group-hover:scale-110">
                            <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <span class="px-2.5 py-1 bg-blue-50 text-blue-600 rounded-lg text-xs font-bold border border-blue-100">
                            {{ $stats['total_kategori_gadai'] ?? 0 }} aturan
                        </span>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 mb-1.5 group-hover:text-blue-600 transition-colors">Kategori Gadai</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Konfigurasi persentase jasa titip (bunga), biaya admin, dan denda keterlambatan tebus gadai.</p>
                </div>
                <div class="flex items-center justify-end mt-4 pt-3 border-t border-gray-50">
                    <span class="text-xs font-semibold text-blue-600 group-hover:underline flex items-center gap-1">
                        Kelola
                        <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </span>
                </div>
            </a>

            {{-- Item Gadai --}}
            <a href="{{ route('admin.master-data.item-gadai.index') }}"
                class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-md hover:border-amber-500/30 transition-all duration-200 flex flex-col justify-between h-full">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-11 h-11 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center text-white shadow-sm transition-transform group-hover:scale-110">
                            <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <span class="px-2.5 py-1 bg-amber-50 text-amber-700 rounded-lg text-xs font-bold border border-amber-100">
                            {{ $stats['total_barang_gadai'] }} Item
                        </span>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 mb-1.5 group-hover:text-amber-700 transition-colors">Item Gadai</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Daftar jenis barang jaminan (elektronik, emas, kendaraan, dsb) yang dapat digadaikan oleh nasabah.</p>
                </div>
                <div class="flex items-center justify-end mt-4 pt-3 border-t border-gray-50">
                    <span class="text-xs font-semibold text-amber-700 group-hover:underline flex items-center gap-1">
                        Kelola
                        <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </span>
                </div>
            </a>

            {{-- Inap Kendaraan --}}
            <a href="{{ route('admin.master-data.inap-kendaraan.index') }}"
                class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-md hover:border-indigo-500/30 transition-all duration-200 flex flex-col justify-between h-full">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-11 h-11 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center text-white shadow-sm transition-transform group-hover:scale-110">
                            <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 16c0 1.105-1.343 2-3 2s-3-.895-3-2M9 16c0 1.105-1.343 2-3 2s-3-.895-3-2m16 0V9a2 2 0 00-2-2H5a2 2 0 00-2 2v7m16 0h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 12H4"></path>
                            </svg>
                        </div>
                        <span class="px-2.5 py-1 bg-indigo-50 text-indigo-600 rounded-lg text-xs font-bold border border-indigo-100">
                            {{ $stats['total_inap_kendaraan'] ?? 0 }} golongan
                        </span>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 mb-1.5 group-hover:text-indigo-600 transition-colors">Inap Kendaraan</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Kelola penggolongan tarif inap harian kendaraan bermotor yang disimpan di gudang koperasi.</p>
                </div>
                <div class="flex items-center justify-end mt-4 pt-3 border-t border-gray-50">
                    <span class="text-xs font-semibold text-indigo-600 group-hover:underline flex items-center gap-1">
                        Kelola
                        <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </span>
                </div>
            </a>

            {{-- Slot Storage Grid --}}
            <a href="{{ route('admin.master-data.slot-storage.index') }}"
                class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-md hover:border-gray-400/30 transition-all duration-200 flex flex-col justify-between h-full">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-11 h-11 bg-gradient-to-br from-gray-500 to-gray-600 rounded-xl flex items-center justify-center text-white shadow-sm transition-transform group-hover:scale-110">
                            <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </div>
                        <span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs font-bold border border-gray-200">
                            Dimensi Grid
                        </span>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 mb-1.5 group-hover:text-gray-700 transition-colors">Slot Storage Grid</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Konfigurasi baris dan kolom untuk pengorganisasian rak/lemari penyimpanan barang jaminan gadai.</p>
                </div>
                <div class="flex items-center justify-end mt-4 pt-3 border-t border-gray-50">
                    <span class="text-xs font-semibold text-gray-600 group-hover:underline flex items-center gap-1">
                        Kelola
                        <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </span>
                </div>
            </a>

            {{-- Gadai Debugger (hanya tampil untuk Admin Utama) --}}
            @isAdminUtama
            <a href="{{ route('admin.master-data.gadai-debugger.index') }}"
                class="group bg-white rounded-2xl border border-indigo-100 shadow-sm p-6 hover:shadow-md hover:border-indigo-500/30 transition-all duration-200 flex flex-col justify-between h-full">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-11 h-11 bg-gradient-to-br from-indigo-600 to-indigo-700 rounded-xl flex items-center justify-center text-white shadow-sm transition-transform group-hover:scale-110">
                            <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="px-2.5 py-1 bg-indigo-50 text-indigo-600 rounded-lg text-xs font-bold border border-indigo-100">
                            Simulator
                        </span>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 mb-1.5 group-hover:text-indigo-600 transition-colors">Gadai Debugger</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Penguji jatuh tempo gadai (time travel debugger), simulasi kalkulasi bunga, dan denda gadai.</p>
                </div>
                <div class="flex items-center justify-end mt-4 pt-3 border-t border-gray-50">
                    <span class="text-xs font-semibold text-indigo-600 group-hover:underline flex items-center gap-1">
                        Kelola
                        <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </span>
                </div>
            </a>
            @endisAdminUtama
        </div>
    </div>

    {{-- ===== GRUP 4: OPERASIONAL & SISTEM ===== --}}
    <div class="space-y-4">
        <div class="flex items-center gap-3 border-l-4 border-majakara-brown pl-3">
            <h2 class="text-lg font-bold text-gray-900 font-display">Operasional & Sistem</h2>
            <span class="w-1.5 h-1.5 bg-gray-300 rounded-full"></span>
            <p class="text-xs text-gray-500 font-medium">Kantor cabang, akun perbankan, dan alat administrasi utama</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            {{-- Lokasi Perusahaan --}}
            <a href="{{ route('admin.master-data.lokasi-perusahaan.index') }}"
                class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-md hover:border-emerald-500/30 transition-all duration-200 flex flex-col justify-between h-full">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-11 h-11 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center text-white shadow-sm transition-transform group-hover:scale-110">
                            <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-xs font-bold border border-emerald-100">
                            {{ $stats['total_lokasi_perusahaan'] }} kantor
                        </span>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 mb-1.5 group-hover:text-emerald-600 transition-colors">Lokasi Perusahaan</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Daftar alamat unit kantor cabang resmi koperasi Majakara yang beroperasi saat ini.</p>
                </div>
                <div class="flex items-center justify-end mt-4 pt-3 border-t border-gray-50">
                    <span class="text-xs font-semibold text-emerald-600 group-hover:underline flex items-center gap-1">
                        Kelola
                        <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </span>
                </div>
            </a>

            {{-- Rekening Perusahaan --}}
            <a href="{{ route('admin.master-data.rekening-perusahaan.index') }}"
                class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-md hover:border-teal-500/30 transition-all duration-200 flex flex-col justify-between h-full">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-11 h-11 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center text-white shadow-sm transition-transform group-hover:scale-110">
                            <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                        <span class="px-2.5 py-1 bg-teal-50 text-teal-600 rounded-lg text-xs font-bold border border-teal-100">
                            {{ $stats['total_rekening_perusahaan'] ?? 0 }} rekening
                        </span>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 mb-1.5 group-hover:text-teal-600 transition-colors">Rekening Perusahaan</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Kelola rekening bank resmi milik koperasi (BCA, Mandiri, dll) untuk tujuan transfer nasabah.</p>
                </div>
                <div class="flex items-center justify-end mt-4 pt-3 border-t border-gray-50">
                    <span class="text-xs font-semibold text-teal-600 group-hover:underline flex items-center gap-1">
                        Kelola
                        <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </span>
                </div>
            </a>

            {{-- Biaya Transfer --}}
            <a href="{{ route('admin.master-data.biaya-transfer.index') }}"
                class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-md hover:border-sky-500/30 transition-all duration-200 flex flex-col justify-between h-full">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-11 h-11 bg-gradient-to-br from-sky-500 to-sky-600 rounded-xl flex items-center justify-center text-white shadow-sm transition-transform group-hover:scale-110">
                            <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                        </div>
                        <span class="px-2.5 py-1 bg-sky-50 text-sky-600 rounded-lg text-xs font-bold border border-sky-100">
                            17 bank
                        </span>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 mb-1.5 group-hover:text-sky-600 transition-colors">Biaya Transfer</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Konfigurasi tarif administrasi transaksi pengiriman dana antar bank anggota yang berbeda.</p>
                </div>
                <div class="flex items-center justify-end mt-4 pt-3 border-t border-gray-50">
                    <span class="text-xs font-semibold text-sky-600 group-hover:underline flex items-center gap-1">
                        Kelola
                        <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </span>
                </div>
            </a>

            {{-- Admin Operasional (hanya tampil untuk Admin Utama) --}}
            @isAdminUtama
            <a href="{{ route('admin.master-data.admin-operasional.index') }}"
                class="group bg-gradient-to-br from-majakara-brown to-majakara-dark-gold rounded-2xl p-6 shadow-sm hover:shadow-md hover:shadow-majakara-brown/20 transition-all duration-200 flex flex-col justify-between h-full text-white border border-majakara-brown">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-11 h-11 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center text-white shadow-inner">
                            <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <span class="px-2.5 py-1 bg-white/20 backdrop-blur-sm text-white rounded-lg text-xs font-bold border border-white/10">
                            {{ $stats['total_admin_operasional'] }} admin
                        </span>
                    </div>
                    <h3 class="text-base font-bold mb-1.5">Admin Operasional</h3>
                    <p class="text-xs text-white/80 leading-relaxed">Manajemen akun admin operasional, kelola kredensial login, profil, dan pembagian tugas lapangan.</p>
                </div>
                <div class="flex items-center justify-end mt-4 pt-3 border-t border-white/20">
                    <span class="text-xs font-semibold text-white group-hover:underline flex items-center gap-1">
                        Kelola
                        <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </span>
                </div>
            </a>
            @endisAdminUtama

        </div>
    </div>

</div>
@endsection
