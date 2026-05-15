@extends('layouts.nasabah')

@section('title', 'Dashboard Tabungan')

@section('content')
<div class="w-full pb-6">
    <!-- Hero Section - Saldo Utama -->
        <div class="mx-4 mt-4 mb-6">
        <div class="bg-linear-to-br from-[#674c1d] via-[#8b6f2f] to-[#d4af37] rounded-3xl shadow-2xl p-8 border-2 border-[#d4af37]/30 relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white rounded-full -mr-32 -mt-32"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-white rounded-full -ml-24 -mb-24"></div>
            </div>
            
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <p class="text-white/90 text-sm font-medium mb-2">Saldo Tabungan Anda</p>
                        <h1 class="text-4xl md:text-5xl font-bold text-white mb-2 font-display">
                            Rp {{ number_format($tabunganInfo->saldo ?? 0, 0, ',', '.') }}
                        </h1>
                        @if(($tabunganInfo->saldo_hold ?? 0) > 0)
                            <div class="flex items-center gap-2 mb-2">
                                <span class="px-2 py-0.5 bg-amber-400 text-amber-900 rounded text-[10px] font-bold uppercase tracking-wider shadow-sm">Tertahan</span>
                                <span class="text-white/90 text-sm font-semibold">Rp {{ number_format($tabunganInfo->saldo_hold, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        <p class="text-white/80 text-sm">Status: <span class="font-semibold">{{ $tabunganInfo->status ?? 'Aktif' }}</span></p>
                    </div>
                    <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                        </div>
                    </div>
                    
                <!-- Quick Actions -->
                <div class="grid grid-cols-2 gap-3 mt-6">
                    <a href="{{ route('nasabah.tabungan.nabung-sekarang') }}" class="bg-white/20 backdrop-blur-sm hover:bg-white/30 rounded-xl p-4 transition-all border border-white/30">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </div>
                            <div>
                                <p class="text-white text-sm font-medium">Nabung</p>
                                <p class="text-white/80 text-xs">Tambah Tabungan</p>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('nasabah.tabungan.penarikan') }}" class="bg-white/20 backdrop-blur-sm hover:bg-white/30 rounded-xl p-4 transition-all border border-white/30">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                </div>
                            <div>
                                <p class="text-white text-sm font-medium">Tarik</p>
                                <p class="text-white/80 text-xs">Penarikan Tabungan</p>
                            </div>
                        </div>
                    </a>
                </div>
                
                <!-- Status Pengajuan Links -->
                <div class="grid grid-cols-2 gap-3 mt-3">
                    <a href="{{ route('nasabah.tabungan.status-pengajuan-setor') }}" class="bg-white/10 backdrop-blur-sm hover:bg-white/20 rounded-lg p-3 transition-all border border-white/20 text-center">
                        <p class="text-white text-xs font-medium">Status Setoran</p>
                    </a>
                    <a href="{{ route('nasabah.tabungan.status-janji-temu') }}" class="bg-white/10 backdrop-blur-sm hover:bg-white/20 rounded-lg p-3 transition-all border border-white/20 text-center">
                        <p class="text-white text-xs font-medium">Status Janji Temu</p>
                    </a>
                    <a href="{{ route('nasabah.tabungan.status-pengajuan-tarik') }}" class="bg-white/10 backdrop-blur-sm hover:bg-white/20 rounded-lg p-3 transition-all border border-white/20 text-center col-span-2">
                        <p class="text-white text-xs font-medium">Status Penarikan</p>
                    </a>
                    </div>
                </div>
            </div>
        </div>

    <!-- Container Riwayat Grid Layout -->
    <div class="mx-4 mb-10 space-y-6">
        <!-- Row 1: Transaksi & Janji Temu -->
        <div class="flex flex-col lg:flex-row gap-6">
            {{-- Tabel Riwayat Transaksi --}}
            <div id="trans-container" class="w-full lg:w-1/2 bg-white/80 backdrop-blur-md rounded-2xl shadow-xl border border-white/40 overflow-hidden flex flex-col transition-opacity duration-150 min-h-[600px]">
                <div class="p-6 pb-0">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-linear-to-br from-[#674c1d] to-[#8b6f2f] rounded-xl flex items-center justify-center shadow-md">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-[#674c1d] font-display">Riwayat Transaksi</h3>
                    </div>
                </div>

                <div class="ajax-content flex-1 flex flex-col">
                    @include('nasabah.tabungan.partials._table_trans')
                </div>
            </div>

            {{-- Tabel Riwayat Janji Temu --}}
            <div id="jt-container" class="w-full lg:w-1/2 bg-white/80 backdrop-blur-md rounded-2xl shadow-xl border border-white/40 overflow-hidden flex flex-col transition-opacity duration-150 min-h-[600px]">
                <div class="p-6 pb-0">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-linear-to-br from-[#d4af37] to-[#8b6f2f] rounded-xl flex items-center justify-center shadow-md">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-[#674c1d] font-display">Riwayat Janji Temu</h3>
                    </div>
                </div>

                <div class="ajax-content flex-1 flex flex-col">
                    @include('nasabah.tabungan.partials._table_jt')
                </div>
            </div>
        </div>

        <!-- Row 2: Pengajuan Setor & Tarik -->
        <div class="flex flex-col lg:flex-row gap-6">
            {{-- Tabel Riwayat Pengajuan Setor --}}
            <div id="setor-container" class="w-full lg:w-1/2 bg-white/80 backdrop-blur-md rounded-2xl shadow-xl border border-white/40 overflow-hidden flex flex-col transition-opacity duration-150 min-h-[600px]">
                <div class="p-6 pb-0">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-linear-to-br from-[#674c1d] via-[#8b6f2f] to-[#d4af37] rounded-xl flex items-center justify-center shadow-md">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-[#674c1d] font-display">Status Setoran (Transfer)</h3>
                    </div>
                </div>

                <div class="ajax-content flex-1 flex flex-col">
                    @include('nasabah.tabungan.partials._table_setor')
                </div>
            </div>

            {{-- Tabel Riwayat Pengajuan Tarik --}}
            <div id="tarik-container" class="w-full lg:w-1/2 bg-white/80 backdrop-blur-md rounded-2xl shadow-xl border border-white/40 overflow-hidden flex flex-col transition-opacity duration-150 min-h-[600px]">
                <div class="p-6 pb-0">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-linear-to-br from-[#d4af37] via-[#8b6f2f] to-[#674c1d] rounded-xl flex items-center justify-center shadow-md">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-[#674c1d] font-display">Status Penarikan (Transfer)</h3>
                    </div>
                </div>

                <div class="ajax-content flex-1 flex flex-col">
                    @include('nasabah.tabungan.partials._table_tarik')
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to handle AJAX pagination
            function handleAjaxPagination(containerId, sectionName) {
                const container = document.getElementById(containerId);
                if (!container) return; // Guard clause
                
                container.addEventListener('click', function(e) {
                    const link = e.target.closest('nav.flex a');
                    if (link && !link.hasAttribute('data-no-ajax')) {
                        e.preventDefault();
                        
                        const url = link.href;
                        loadSection(containerId, sectionName, url);
                    }
                });
            }

            function loadSection(containerId, sectionName, url) {
                const container = document.getElementById(containerId);
                const contentArea = container.querySelector('.ajax-content');
                
                // Add loading effect
                container.classList.add('opacity-60');
                container.style.pointerEvents = 'none';

                // Append section identifier to URL
                const ajaxUrl = new URL(url);
                ajaxUrl.searchParams.append('section', sectionName);

                fetch(ajaxUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.text();
                })
                .then(html => {
                    contentArea.innerHTML = html;
                    
                    // Smooth scroll to container top if needed
                    // container.scrollIntoView({ behavior: 'smooth', block: 'start' });
                })
                .catch(error => {
                    console.error('Pagination Error:', error);
                    // Fallback: full page reload if AJAX fails
                    window.location.href = url;
                })
                .finally(() => {
                    container.classList.remove('opacity-60');
                    container.style.pointerEvents = 'auto';
                });
            }

            // Initialize pagination for all tables
            handleAjaxPagination('trans-container', 'trans');
            handleAjaxPagination('jt-container', 'jt');
            handleAjaxPagination('setor-container', 'setor');
            handleAjaxPagination('tarik-container', 'tarik');
        });
    </script>
@endsection

