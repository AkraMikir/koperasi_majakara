@extends('layouts.nasabah')

@section('title', 'Nabung Sekarang')

@section('content')
    <div class="w-full">
        <!-- Pilihan Metode Nabung -->
        <div class="mx-4 mt-4 mb-6">
            <div class="bg-gradient-to-br from-white via-[#674c1d]/5 to-[#8b6f2f]/5 rounded-2xl shadow-md p-6 border-2 border-[#674c1d]/20">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] rounded-xl flex items-center justify-center shadow-md">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-base font-semibold text-gray-700">mau nabung via apa?</span>
                        <span class="text-2xl font-bold text-[#d4af37]">→</span>
                    </div>
                    <div class="flex gap-3">
                        <button class="group px-6 py-3 bg-gradient-to-r from-[#674c1d] to-[#4a3514] text-white font-semibold rounded-xl hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-md hover:shadow-lg transform hover:scale-105 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            TUNAI
                        </button>
                        <button class="group px-6 py-3 bg-gradient-to-r from-[#8b6f2f] to-[#d4af37] text-white font-semibold rounded-xl hover:from-[#d4af37] hover:to-[#8b6f2f] transition-all shadow-md hover:shadow-lg transform hover:scale-105 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                            TRANSFER
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Riwayat Tabungan -->
        <div class="mx-4 mb-6">
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <div class="flex items-center justify-center gap-3 mb-6 pb-4 border-b border-gray-200">
                    <div class="w-10 h-10 bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] rounded-xl flex items-center justify-center shadow-md">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-primary font-display">
                        RIWAYAT TABUNGAN
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-gray-700">Riwayat Tabungan Anda</h3>
                    </div>
                    <div class="border-t border-gray-200 pt-4">
                        <x-nasabah.tabungan.table-riwayat 
                            :data="$riwayatTabungan"
                            :columns="['tanggal' => 'Tanggal', 'jumlah' => 'Jumlah', 'id_transaksi' => 'ID Transaksi', 'via' => 'Via']" />
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

