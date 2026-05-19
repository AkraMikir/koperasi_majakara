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
        <div class="bg-white rounded-[1.5rem] p-5 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-all">
            <div class="absolute top-0 right-0 w-24 h-24 bg-majakara-gold/5 rounded-full blur-xl -translate-y-1/2 translate-x-1/2 group-hover:bg-majakara-gold/10 transition-colors"></div>
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Total Anggota</p>
                    <h3 class="text-2xl font-black text-gray-900 font-display">{{ number_format($stats['total_nasabah'] ?? 0, 0, ',', '.') }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center border border-gray-100 text-gray-400 group-hover:border-majakara-brown/20 group-hover:text-majakara-brown transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
            <div class="flex items-center gap-2 relative z-10">
                <span class="text-[10px] font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded-md border border-green-100">+12%</span>
                <span class="text-[10px] text-gray-400 font-medium">Aktif bulan ini</span>
            </div>
        </div>

        <!-- Total Aset -->
        <div class="bg-white rounded-[1.5rem] p-5 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-all">
            <div class="absolute top-0 right-0 w-24 h-24 bg-majakara-gold/5 rounded-full blur-xl -translate-y-1/2 translate-x-1/2 group-hover:bg-majakara-gold/10 transition-colors"></div>
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div class="w-full pr-4">
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Total Aset Masuk</p>
                    <h3 class="text-xl font-black text-gray-900 font-display truncate" title="Rp {{ number_format($totalAset, 0, ',', '.') }}">Rp {{ \App\Helpers\CurrencyHelper::formatBerbilang($totalAset) }}</h3>
                </div>
                <div class="w-10 h-10 shrink-0 rounded-xl bg-gray-50 flex items-center justify-center border border-gray-100 text-gray-400 group-hover:border-blue-500/20 group-hover:text-blue-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="flex items-center gap-2 relative z-10">
                <span class="text-[10px] text-gray-400 font-medium truncate">Total Tabungan & Deposito</span>
            </div>
        </div>

        <!-- Total Penyaluran -->
        <div class="bg-white rounded-[1.5rem] p-5 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-all">
            <div class="absolute top-0 right-0 w-24 h-24 bg-majakara-gold/5 rounded-full blur-xl -translate-y-1/2 translate-x-1/2 group-hover:bg-majakara-gold/10 transition-colors"></div>
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div class="w-full pr-4">
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Dana Disalurkan</p>
                    <h3 class="text-xl font-black text-gray-900 font-display truncate" title="Rp {{ number_format($totalPenyaluran, 0, ',', '.') }}">Rp {{ \App\Helpers\CurrencyHelper::formatBerbilang($totalPenyaluran) }}</h3>
                </div>
                <div class="w-10 h-10 shrink-0 rounded-xl bg-gray-50 flex items-center justify-center border border-gray-100 text-gray-400 group-hover:border-yellow-500/20 group-hover:text-yellow-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
            </div>
            <div class="flex items-center gap-2 relative z-10">
                <span class="text-[10px] text-gray-400 font-medium truncate">Total Pinjaman & Gadai Aktif</span>
            </div>
        </div>

        <!-- Pendapatan Bulan Ini -->
        <div class="bg-gradient-to-br from-majakara-brown to-majakara-dark-gold rounded-[1.5rem] p-5 shadow-md border border-majakara-gold/20 relative overflow-hidden group hover:shadow-lg transition-all text-white">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-majakara-gold/40 rounded-full blur-xl translate-y-1/2 -translate-x-1/2"></div>
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div class="w-full pr-4">
                    <p class="text-[10px] text-white/80 font-bold uppercase tracking-widest mb-1">Pendapatan Bunga</p>
                    <h3 class="text-xl font-black font-display truncate" title="Rp {{ number_format($stats['pendapatan_bulan'] ?? 0, 0, ',', '.') }}">Rp {{ \App\Helpers\CurrencyHelper::formatBerbilang($stats['pendapatan_bulan'] ?? 0) }}</h3>
                </div>
                <div class="w-10 h-10 shrink-0 rounded-xl bg-white/10 flex items-center justify-center border border-white/20 backdrop-blur-sm shadow-inner group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="flex items-center gap-2 relative z-10">
                <span class="text-[10px] font-bold text-white bg-white/20 px-2 py-0.5 rounded-md backdrop-blur-sm border border-white/10">Bulan Ini</span>
            </div>
        </div>
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
                        @else
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-gray-800 leading-snug group-hover:text-majakara-brown transition-colors truncate">{{ $aktivitas['deskripsi'] ?? 'Aktivitas' }}</p>
                        <p class="text-[10px] text-gray-400 font-medium mt-0.5">{{ $aktivitas['waktu'] ?? 'N/A' }}</p>
                    </div>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center h-full text-center opacity-50 py-8">
                    <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-xs text-gray-500 font-medium">Belum ada aktivitas</p>
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
        
        <div class="overflow-x-auto">
            <table class="w-full">
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
                                {{ $pengajuan['type'] }}
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
                                <form action="{{ route('admin.' . $pengajuan['type'] . '.approve', $pengajuan['id'] ?? 0) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="button" onclick="confirmApprove(this)" class="p-2 bg-green-50 text-green-600 hover:bg-green-500 hover:text-white rounded-lg transition-colors border border-green-100 hover:border-green-600 shadow-sm" title="Setujui">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    </button>
                                </form>
                                <form action="{{ route('admin.' . $pengajuan['type'] . '.reject', $pengajuan['id'] ?? 0) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="button" onclick="confirmReject(this)" class="p-2 bg-red-50 text-red-600 hover:bg-red-500 hover:text-white rounded-lg transition-colors border border-red-100 hover:border-red-600 shadow-sm" title="Tolak">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </form>
                                <a href="{{ route('admin.' . $pengajuan['type'] . '.index') }}" class="p-2 bg-gray-50 text-gray-500 hover:bg-gray-200 hover:text-gray-700 rounded-lg transition-colors border border-gray-200 shadow-sm" title="Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 mb-3 border border-gray-100">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <h3 class="text-sm font-bold text-gray-900 font-display">Semua Beres!</h3>
                            <p class="text-xs text-gray-400 mt-1 font-medium">Tidak ada pengajuan yang perlu persetujuan saat ini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#d1d5db',
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
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#d1d5db',
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


