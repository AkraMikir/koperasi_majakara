@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-4 pb-12 animate-fade-in">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-2">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 font-display">Dashboard Admin</h1>
            <p class="text-xs text-gray-500 mt-1 font-medium tracking-wide">Selamat datang kembali, <span class="text-majakara-brown font-bold">{{ auth()->user()->nama ?? 'Admin' }}</span></p>
        </div>
        <div class="flex items-center space-x-2">
            <button class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors text-xs font-bold uppercase tracking-wider flex items-center gap-2 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Laporan
            </button>
            <button class="px-4 py-2 bg-gradient-to-r from-majakara-brown to-majakara-dark-gold text-white rounded-xl hover:shadow-lg hover:shadow-majakara-brown/20 transition-all text-xs font-bold uppercase tracking-wider flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                Nasabah
            </button>
        </div>
    </div>

    @php
        $totalAset = ($stats['total_tabungan'] ?? 0) + ($stats['total_deposito'] ?? 0);
        $totalPenyaluran = ($stats['total_pinjaman'] ?? 0) + ($stats['total_gadai'] ?? 0);
    @endphp

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Nasabah -->
        <x-admin.stats-card 
            title="Total Anggota" 
            value="{{ number_format($stats['total_nasabah'] ?? 0, 0, ',', '.') }}" 
            badgeText="+12%" 
            subtext="Aktif bulan ini"
        >
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        </x-admin.stats-card>

        <!-- Total Aset -->
        <x-admin.stats-card 
            title="Total Aset Masuk" 
            value="Rp {{ \App\Helpers\CurrencyHelper::formatBerbilang($totalAset) }}" 
            fullValue="Rp {{ number_format($totalAset, 0, ',', '.') }}"
            subtext="Total Tabungan & Deposito"
            iconHoverBorder="group-hover:border-blue-500/20"
            iconHoverColor="group-hover:text-blue-600"
        >
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </x-admin.stats-card>

        <!-- Total Penyaluran -->
        <x-admin.stats-card 
            title="Dana Disalurkan" 
            value="Rp {{ \App\Helpers\CurrencyHelper::formatBerbilang($totalPenyaluran) }}" 
            fullValue="Rp {{ number_format($totalPenyaluran, 0, ',', '.') }}"
            subtext="Total Pinjaman & Gadai Aktif"
            iconHoverBorder="group-hover:border-yellow-500/20"
            iconHoverColor="group-hover:text-yellow-600"
        >
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
        </x-admin.stats-card>

        <!-- Pendapatan Bulan Ini -->
        <x-admin.stats-card 
            title="Pendapatan Bunga" 
            value="Rp {{ \App\Helpers\CurrencyHelper::formatBerbilang($stats['pendapatan_bulan'] ?? 0) }}" 
            fullValue="Rp {{ number_format($stats['pendapatan_bulan'] ?? 0, 0, ',', '.') }}"
            badgeText="Bulan Ini"
            badgeClass="text-white bg-white/20 border border-white/10"
            gradient="true"
        >
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </x-admin.stats-card>
    </div>

    <!-- Chart & Aktivitas -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Chart Likuiditas -->
        <div class="lg:col-span-2 bg-white rounded-[1.5rem] p-5 lg:p-6 shadow-sm border border-gray-100 flex flex-col">
            <div class="flex items-center justify-between mb-4 border-b border-gray-50 pb-4">
                <div>
                    <h2 class="text-xl font-bold font-display text-gray-900 leading-none">Tren Likuiditas</h2>
                    <p class="text-[10px] text-gray-400 mt-1.5 uppercase tracking-widest font-bold">Arus Kas 15 Hari Terakhir</p>
                </div>
                <div class="px-3 py-1.5 bg-gray-50 rounded-lg text-[9px] font-black text-gray-500 uppercase tracking-[0.1em] border border-gray-100 flex items-center gap-1.5 shadow-inner">
                    <span class="w-2 h-2 rounded-full bg-green-500 block"></span>
                    <span class="w-2 h-2 rounded-full bg-red-500 block"></span>
                    Overview
                </div>
            </div>
            <div class="relative w-full h-64 flex-1 mt-2">
                <canvas id="likuiditasChart"></canvas>
            </div>
        </div>

        <!-- Aktivitas Terkini -->
        <div class="lg:col-span-1 bg-white rounded-[1.5rem] p-5 lg:p-6 shadow-sm border border-gray-100 flex flex-col">
            <div class="flex items-center justify-between mb-4 border-b border-gray-50 pb-4">
                <div>
                    <h2 class="text-xl font-bold font-display text-gray-900 leading-none">Aktivitas</h2>
                    <p class="text-[10px] text-gray-400 mt-1.5 uppercase tracking-widest font-bold">Log Sistem Terbaru</p>
                </div>
            </div>
            <div class="flex-1 overflow-y-auto space-y-4 pr-2 custom-scrollbar">
                @forelse(($aktivitas_terkini ?? []) as $aktivitas)
                <div class="flex items-start gap-3 group">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 border border-gray-50 shadow-sm transition-transform group-hover:scale-110
                        @if($aktivitas['type'] === 'tabungan') bg-blue-50 text-blue-500
                        @elseif($aktivitas['type'] === 'pinjaman') bg-yellow-50 text-yellow-500
                        @elseif($aktivitas['type'] === 'deposito') bg-green-50 text-green-500
                        @else bg-purple-50 text-purple-500
                        @endif">
                        @if($aktivitas['type'] === 'tabungan')
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        @elseif($aktivitas['type'] === 'pinjaman')
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        @elseif($aktivitas['type'] === 'deposito')
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        @else
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-gray-800 leading-snug group-hover:text-majakara-brown transition-colors truncate">{{ $aktivitas['deskripsi'] ?? 'Aktivitas' }}</p>
                        <p class="text-[10px] text-gray-400 font-medium mt-0.5">{{ $aktivitas['waktu'] ?? 'N/A' }}</p>
                    </div>
                </div>
                @empty
                <div class="opacity-50">
                    <x-admin.empty-state 
                        title="Belum ada aktivitas" 
                        message="Log aktivitas sistem akan muncul di sini."
                        minHeight="py-8"
                    >
                        <x-slot name="icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </x-slot>
                    </x-admin.empty-state>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Pengajuan Pending -->
    <div class="bg-white rounded-[1.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 lg:p-6 border-b border-gray-50 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-red-50 text-red-500 flex items-center justify-center border border-red-100 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold font-display text-gray-900 leading-none">Pengajuan Perlu Proses</h2>
                    <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-widest font-bold">Menunggu Persetujuan Admin</p>
                </div>
            </div>
            <span class="px-3 py-1.5 bg-red-100 text-red-600 rounded-lg text-[10px] font-black border border-red-200">{{ $stats['pengajuan_pending'] ?? 0 }} Antrean</span>
        </div>
        
        <div class="overflow-hidden md:overflow-x-auto">
            <!-- Table View for Desktop -->
            <table class="w-full hidden md:table">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100">
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Nasabah</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Produk</th>
                        <th class="px-6 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Nominal</th>
                        <th class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Tanggal</th>
                        <th class="px-6 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Aksi Cepat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse(($pengajuan_pending ?? []) as $pengajuan)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-majakara-gold/10 text-majakara-brown flex items-center justify-center font-bold text-sm uppercase shadow-sm">
                                    {{ substr($pengajuan['nama'] ?? 'N', 0, 1) }}
                                </div>
                                <span class="text-sm font-bold text-gray-900">{{ $pengajuan['nama'] ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-md text-[10px] font-black uppercase tracking-wider border border-gray-200">
                                {{ $pengajuan['label'] ?? $pengajuan['type'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <span class="text-sm font-black font-display text-gray-800">Rp {{ number_format($pengajuan['nominal'] ?? 0, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="text-xs font-medium text-gray-500">{{ $pengajuan['tanggal'] ?? 'N/A' }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-2 opacity-100 md:opacity-50 group-hover:opacity-100 transition-opacity">
                                <form action="{{ $pengajuan['route_approve'] ?? '#' }}" method="POST" class="inline">
                                    @csrf
                                    <button type="button" onclick="confirmApprove(this)" class="p-2 bg-green-50 text-green-600 hover:bg-green-500 hover:text-white rounded-lg transition-colors border border-green-100 hover:border-green-600 shadow-sm" title="Setujui">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    </button>
                                </form>
                                <form action="{{ $pengajuan['route_reject'] ?? '#' }}" method="POST" class="inline">
                                    @csrf
                                    <button type="button" onclick="confirmReject(this)" class="p-2 bg-red-50 text-red-600 hover:bg-red-500 hover:text-white rounded-lg transition-colors border border-red-100 hover:border-red-600 shadow-sm" title="Tolak">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </form>
                                <a href="{{ $pengajuan['route_index'] ?? '#' }}" class="p-2 bg-gray-50 text-gray-500 hover:bg-gray-200 hover:text-gray-700 rounded-lg transition-colors border border-gray-200 shadow-sm" title="Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-0">
                            <x-admin.empty-state 
                                title="Semua Beres!" 
                                message="Tidak ada pengajuan yang perlu persetujuan saat ini."
                                minHeight="py-12"
                            />
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Card List View for Mobile -->
            <div class="md:hidden flex flex-col divide-y divide-gray-50">
                @forelse(($pengajuan_pending ?? []) as $pengajuan)
                <div class="p-4 flex flex-col gap-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-majakara-gold/10 text-majakara-brown flex items-center justify-center font-bold text-sm uppercase shadow-sm shrink-0">
                                {{ substr($pengajuan['nama'] ?? 'N', 0, 1) }}
                            </div>
                            <div>
                                <span class="text-sm font-bold text-gray-900 block leading-tight">{{ $pengajuan['nama'] ?? 'N/A' }}</span>
                                <span class="text-[10px] text-gray-500 font-medium">{{ $pengajuan['tanggal'] ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-md text-[10px] font-black uppercase tracking-wider border border-gray-200 shrink-0">
                            {{ $pengajuan['label'] ?? $pengajuan['type'] }}
                        </span>
                    </div>
                    
                    <div class="flex items-end justify-between mt-1">
                        <div>
                            <span class="text-[10px] text-gray-400 uppercase tracking-wider block">Nominal</span>
                            <span class="text-sm font-black font-display text-gray-800">Rp {{ number_format($pengajuan['nominal'] ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <form action="{{ $pengajuan['route_approve'] ?? '#' }}" method="POST" class="inline">
                                @csrf
                                <button type="button" onclick="confirmApprove(this)" class="p-2 bg-green-50 text-green-600 hover:bg-green-500 hover:text-white rounded-lg transition-colors border border-green-100 shadow-sm" title="Setujui">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                </button>
                            </form>
                            <form action="{{ $pengajuan['route_reject'] ?? '#' }}" method="POST" class="inline">
                                @csrf
                                <button type="button" onclick="confirmReject(this)" class="p-2 bg-red-50 text-red-600 hover:bg-red-500 hover:text-white rounded-lg transition-colors border border-red-100 shadow-sm" title="Tolak">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </form>
                            <a href="{{ $pengajuan['route_index'] ?? '#' }}" class="p-2 bg-gray-50 text-gray-500 hover:bg-gray-200 rounded-lg transition-colors border border-gray-200 shadow-sm" title="Detail">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-0">
                    <x-admin.empty-state 
                        title="Semua Beres!" 
                        message="Tidak ada pengajuan yang perlu persetujuan saat ini."
                        minHeight="py-12"
                    />
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const likuiditasCtx = document.getElementById('likuiditasChart');
        if (likuiditasCtx) {
            // Data real dari database via DashboardController
            const labels = @json($grafik_likuiditas['labels']);
            const dataMasuk = @json($grafik_likuiditas['masuk']);
            const dataKeluar = @json($grafik_likuiditas['keluar']);

            // Hitung ringkasan total
            const totalMasuk = dataMasuk.reduce((a, b) => a + b, 0);
            const totalKeluar = dataKeluar.reduce((a, b) => a + b, 0);
            const hasData = totalMasuk > 0 || totalKeluar > 0;

            // Tampilkan info sumber data
            const chartWrapper = likuiditasCtx.closest('.relative') || likuiditasCtx.parentElement;
            if (!hasData) {
                // Tampilkan pesan "belum ada transaksi" di atas chart tapi tetap render
                const notice = document.createElement('div');
                notice.className = 'absolute inset-0 flex flex-col items-center justify-center pointer-events-none';
                notice.innerHTML = `
                    <svg class="w-10 h-10 text-gray-200 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <p class="text-xs font-semibold text-gray-300">Belum ada transaksi dalam 15 hari terakhir</p>
                `;
                if (chartWrapper && chartWrapper.style) {
                    chartWrapper.style.position = 'relative';
                    chartWrapper.appendChild(notice);
                }
            }

            new Chart(likuiditasCtx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Kas Masuk',
                            data: dataMasuk,
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#10b981',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        },
                        {
                            label: 'Kas Keluar',
                            data: dataKeluar,
                            borderColor: '#ef4444',
                            backgroundColor: 'rgba(239, 68, 68, 0.05)',
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#ef4444',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'end',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 8,
                                font: { size: 11, family: "'Inter', sans-serif", weight: 'bold' }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.98)',
                            titleColor: '#1f2937',
                            bodyColor: '#4b5563',
                            borderColor: '#f3f4f6',
                            borderWidth: 1,
                            padding: 12,
                            boxPadding: 6,
                            usePointStyle: true,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) label += ': ';
                                    if (context.parsed.y !== null) {
                                        label += 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false, drawBorder: false },
                            ticks: { font: { size: 10, weight: '500' }, color: '#9ca3af' }
                        },
                        y: {
                            grid: { color: '#f3f4f6', drawBorder: false },
                            border: { dash: [4, 4] },
                            ticks: {
                                font: { size: 10, weight: '500' },
                                color: '#9ca3af',
                                maxTicksLimit: 6,
                                callback: function(value) {
                                    if (value >= 1000000) return 'Rp ' + (value / 1000000) + 'Jt';
                                    return value;
                                }
                            },
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    });

    function confirmApprove(btn) {
        Swal.fire({
            title: 'Setujui Pengajuan?',
            text: "Pastikan data nasabah sudah valid.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Setujui',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                btn.closest('form').submit();
            }
        });
    }

    function confirmReject(btn) {
        Swal.fire({
            title: 'Tolak Pengajuan?',
            text: "Pengajuan ini akan dibatalkan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Tolak',
            cancelButtonText: 'Kembali'
        }).then((result) => {
            if (result.isConfirmed) {
                btn.closest('form').submit();
            }
        });
    }
</script>
@endpush


