@extends('layouts.nasabah')

@section('title', 'Dashboard Tabungan')

@section('content')
    <div class="w-full">
        <!-- Card Informasi Tabungan -->
        <div class="mx-4 mt-4 mb-6">
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h2 class="text-lg font-bold text-primary font-display text-center mb-6 pb-4 border-b border-gray-200">
                    INFORMASI TABUNGAN
                </h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Saldo -->
                    <div class="relative overflow-hidden bg-gradient-to-br from-[#674c1d]/10 via-[#8b6f2f]/10 to-[#d4af37]/10 rounded-xl p-5 border-2 border-[#674c1d]/20 hover:shadow-lg transition-all">
                        <div class="absolute top-2 right-2 w-16 h-16 bg-gradient-to-br from-[#674c1d]/20 to-[#8b6f2f]/20 rounded-full blur-xl"></div>
                        <div class="relative">
                            <div class="flex items-center justify-center mb-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-[#674c1d] to-[#4a3514] rounded-xl flex items-center justify-center shadow-md">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-xs text-gray-600 mb-2 font-medium">Saldo</p>
                            <p class="text-2xl font-bold text-[#674c1d]">Rp {{ number_format($tabunganInfo->saldo ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    
                    <!-- Bunga -->
                    <div class="relative overflow-hidden bg-gradient-to-br from-[#8b6f2f]/10 via-[#d4af37]/10 to-[#674c1d]/10 rounded-xl p-5 border-2 border-[#8b6f2f]/20 hover:shadow-lg transition-all">
                        <div class="absolute top-2 right-2 w-16 h-16 bg-gradient-to-br from-[#d4af37]/20 to-[#8b6f2f]/20 rounded-full blur-xl"></div>
                        <div class="relative">
                            <div class="flex items-center justify-center mb-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-[#8b6f2f] to-[#d4af37] rounded-xl flex items-center justify-center shadow-md">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-xs text-gray-600 mb-2 font-medium">Bunga</p>
                            <p class="text-2xl font-bold text-[#8b6f2f]">{{ $tabunganInfo->bunga ?? 0 }}%</p>
                        </div>
                    </div>
                    
                    <!-- Status -->
                    <div class="relative overflow-hidden bg-gradient-to-br from-[#4a3514]/10 via-[#674c1d]/10 to-[#8b6f2f]/10 rounded-xl p-5 border-2 border-[#4a3514]/20 hover:shadow-lg transition-all">
                        <div class="absolute top-2 right-2 w-16 h-16 bg-gradient-to-br from-[#4a3514]/20 to-[#674c1d]/20 rounded-full blur-xl"></div>
                        <div class="relative">
                            <div class="flex items-center justify-center mb-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-[#4a3514] to-[#674c1d] rounded-xl flex items-center justify-center shadow-md">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-xs text-gray-600 mb-2 font-medium">Status</p>
                            <span class="inline-flex items-center gap-1.5 px-4 py-2 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-full text-sm font-semibold shadow-sm">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $tabunganInfo->status ?? 'Aktif' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Data Tabungan -->
        <div class="mx-4 mb-6">
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <div class="flex items-center justify-center gap-3 mb-6 pb-4 border-b border-gray-200">
                    <div class="w-10 h-10 bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] rounded-xl flex items-center justify-center shadow-md">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-primary font-display">
                        DATA TABUNGAN ANDA
                    </h2>
                </div>
                
                <!-- Filter Section -->
                <div class="mb-6">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-gradient-to-br from-[#4a3514]/20 to-[#674c1d]/20 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-[#4a3514]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-gray-700">FILTER TABUNGAN</h3>
                    </div>
                    <x-nasabah.tabungan.filter-tabungan 
                        placeholder-tanggal="tanggal"
                        placeholder-jumlah="jumlah"
                        placeholder-id-transaksi="id transaksi" />
                </div>
                
                <!-- Table Section -->
                <div class="mt-6">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-gradient-to-br from-[#8b6f2f]/20 to-[#d4af37]/20 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-[#8b6f2f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-gray-700">Riwayat Tabungan Anda</h3>
                    </div>
                    <div class="border-t border-gray-200 pt-4">
                        <x-nasabah.tabungan.table-riwayat 
                            :data="$transaksiTabungan"
                            :columns="['tanggal' => 'Tanggal', 'jumlah' => 'Jumlah', 'id_transaksi' => 'ID Transaksi', 'jenis' => 'Jenis', 'via' => 'Via']" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Riwayat Janji Temu -->
        <div class="mx-4 mb-6">
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <div class="flex items-center justify-center gap-3 mb-6 pb-4 border-b border-gray-200">
                    <div class="w-10 h-10 bg-gradient-to-br from-[#d4af37] to-[#8b6f2f] rounded-xl flex items-center justify-center shadow-md">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-primary font-display">
                        RIWAYAT JANJI TEMU
                    </h2>
                </div>
                
                <!-- Filter Section -->
                <div class="mb-6">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-gradient-to-br from-[#4a3514]/20 to-[#674c1d]/20 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-[#4a3514]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-gray-700">FILTER TABUNGAN</h3>
                    </div>
                    <x-nasabah.tabungan.filter-tabungan 
                        placeholder-tanggal="tanggal"
                        placeholder-jumlah="jumlah"
                        placeholder-id-transaksi="id transaksi" />
                </div>
                
                <!-- Table Section -->
                <div class="mt-6">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-gradient-to-br from-[#8b6f2f]/20 to-[#d4af37]/20 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-[#8b6f2f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-gray-700">Riwayat Janji Temu</h3>
                    </div>
                    <div class="border-t border-gray-200 pt-4">
                        <x-nasabah.tabungan.table-riwayat 
                            :data="$riwayatJanjiTemu"
                            :columns="['tanggal' => 'Tanggal', 'waktu' => 'Waktu', 'lokasi' => 'Lokasi', 'nominal' => 'Nominal', 'status' => 'Status']" />
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

