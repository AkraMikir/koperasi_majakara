@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6 pb-12 animate-fade-in">
    <!-- Welcome & Real-time Info Banner -->
    <div class="bg-gradient-to-r from-majakara-brown to-majakara-dark-gold rounded-3xl p-6 shadow-md text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.15),transparent)] pointer-events-none"></div>
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div>
                <h1 class="text-3xl font-black font-display tracking-tight text-white">Dashboard Koperasi Majakara</h1>
                <p class="text-sm text-white/80 mt-1.5 font-medium">Selamat datang kembali, <span class="text-majakara-gold font-bold font-display">{{ auth()->user()->nama ?? 'Admin' }}</span>. Pantau aktivitas koperasi hari ini.</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="px-4 py-2 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl flex flex-col items-center justify-center text-center shadow-inner min-w-[120px]">
                    <span class="text-[10px] text-white/60 font-black uppercase tracking-wider">Status Sistem</span>
                    <span class="text-xs font-bold text-green-400 mt-0.5 flex items-center gap-1.5">
                        <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                        Online
                    </span>
                </div>
                <div class="px-4 py-2 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl flex flex-col items-center justify-center text-center shadow-inner min-w-[140px]">
                    <span class="text-[10px] text-white/60 font-black uppercase tracking-wider">Antrean Pending</span>
                    <span class="text-xs font-bold text-white mt-0.5">
                        {{ $stats['pengajuan_pending'] ?? 0 }} Pengajuan
                    </span>
                </div>
            </div>
        </div>
    </div>

    @php
        if (!function_exists('formatIndonesianDuration')) {
            function formatIndonesianDuration($date) {
                if (!$date) return '-';
                
                $targetDate = \Carbon\Carbon::parse($date)->startOfDay();
                $nowDate = \Carbon\Carbon::now()->startOfDay();
                
                $diff = $nowDate->diff($targetDate);
                
                $parts = [];
                if ($diff->y > 0) {
                    $parts[] = $diff->y . ' tahun';
                }
                if ($diff->m > 0) {
                    $parts[] = $diff->m . ' bulan';
                }
                if ($diff->d > 0) {
                    $parts[] = $diff->d . ' hari';
                }
                
                if (empty($parts)) {
                    return '0 hari';
                }
                
                return implode(' ', $parts);
            }
        }
    @endphp

    <!-- Statistics Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1: Total Nasabah -->
        <div class="card-gradient-brown rounded-3xl p-6 relative overflow-hidden group transition-all duration-300 hover:scale-[1.02]">
            <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full blur-xl -translate-y-1/2 translate-x-1/2 group-hover:bg-white/20 transition-all"></div>
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div>
                    <p class="text-[10px] text-white/80 font-bold uppercase tracking-widest mb-1.5">Total Anggota</p>
                    <h3 class="text-3xl font-black text-white font-display">{{ number_format($stats['total_nasabah'] ?? 0, 0, ',', '.') }}</h3>
                </div>
                <div class="w-11 h-11 shrink-0 rounded-2xl bg-white/15 flex items-center justify-center border border-white/25 backdrop-blur-xs text-white transition-transform group-hover:scale-110">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
            <div class="flex items-center gap-2 relative z-10">
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-md text-white bg-white/20 border border-white/10">Bulan Ini</span>
                <span class="text-[10px] text-white/80 font-medium font-sans">Anggota terdaftar</span>
            </div>
        </div>

        <!-- Card 2: Total Aset Masuk -->
        <div class="card-gradient-premium rounded-3xl p-6 relative overflow-hidden group transition-all duration-300 hover:scale-[1.02]">
            <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full blur-xl -translate-y-1/2 translate-x-1/2 group-hover:bg-white/20 transition-all"></div>
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div>
                    <p class="text-[10px] text-white/80 font-bold uppercase tracking-widest mb-1.5">Total Aset Masuk</p>
                    <h3 class="text-2xl font-black text-white font-display truncate" title="Rp {{ number_format($totalAset, 0, ',', '.') }}">Rp {{ \App\Helpers\CurrencyHelper::formatBerbilang($totalAset) }}</h3>
                </div>
                <div class="w-11 h-11 shrink-0 rounded-2xl bg-white/15 flex items-center justify-center border border-white/25 backdrop-blur-xs text-white transition-transform group-hover:scale-110">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="flex items-center gap-2 relative z-10">
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-md text-white bg-white/20 border border-white/10">Simpanan</span>
                <span class="text-[10px] text-white/80 font-medium font-sans">Tabungan & Deposito</span>
            </div>
        </div>

        <!-- Card 3: Dana Disalurkan -->
        <div class="card-gradient-light-brown rounded-3xl p-6 relative overflow-hidden group transition-all duration-300 hover:scale-[1.02]">
            <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full blur-xl -translate-y-1/2 translate-x-1/2 group-hover:bg-white/20 transition-all"></div>
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div>
                    <p class="text-[10px] text-white/80 font-bold uppercase tracking-widest mb-1.5">Dana Disalurkan</p>
                    <h3 class="text-2xl font-black text-white font-display truncate" title="Rp {{ number_format($totalPenyaluran, 0, ',', '.') }}">Rp {{ \App\Helpers\CurrencyHelper::formatBerbilang($totalPenyaluran) }}</h3>
                </div>
                <div class="w-11 h-11 shrink-0 rounded-2xl bg-white/15 flex items-center justify-center border border-white/25 backdrop-blur-xs text-white transition-transform group-hover:scale-110">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
            </div>
            <div class="flex items-center gap-2 relative z-10">
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-md text-white bg-white/20 border border-white/10">Kredit Aktif</span>
                <span class="text-[10px] text-white/80 font-medium font-sans">Pinjaman & Gadai</span>
            </div>
        </div>

        <!-- Card 4: Pendapatan Bunga -->
        <div class="card-gradient-gold rounded-3xl p-6 relative overflow-hidden group transition-all duration-300 hover:scale-[1.02]">
            <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full blur-xl -translate-y-1/2 translate-x-1/2 group-hover:bg-white/20 transition-all"></div>
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div>
                    <p class="text-[10px] text-white/80 font-bold uppercase tracking-widest mb-1.5">Pendapatan Bunga</p>
                    <h3 class="text-2xl font-black text-white font-display truncate" title="Rp {{ number_format($stats['pendapatan_bulan'] ?? 0, 0, ',', '.') }}">Rp {{ \App\Helpers\CurrencyHelper::formatBerbilang($stats['pendapatan_bulan'] ?? 0) }}</h3>
                </div>
                <div class="w-11 h-11 shrink-0 rounded-2xl bg-white/15 flex items-center justify-center border border-white/25 backdrop-blur-xs text-white transition-transform group-hover:scale-110">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="flex items-center gap-2 relative z-10">
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-md text-white bg-white/20 border border-white/10">Bulan Ini</span>
                <span class="text-[10px] text-white/80 font-medium font-sans">Akumulasi Bunga</span>
            </div>
        </div>
    </div>

    <!-- Cooperative Module Breakdown Grid -->
    <div class="space-y-4">
        <h2 class="text-lg font-bold font-display text-gray-800 flex items-center gap-2">
            <span class="w-1.5 h-5 bg-majakara-brown rounded-full"></span>
            Rincian Modul Koperasi
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Tabungan Module -->
            <div class="bg-white rounded-3xl p-5 border border-majakara-brown/10 hover:border-majakara-brown/30 transition-all shadow-xs group flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-2.5 py-1 bg-majakara-brown/5 text-majakara-brown text-[10px] font-black uppercase tracking-wider rounded-lg border border-majakara-brown/10">Tabungan</span>
                        <span class="w-1.5 h-1.5 rounded-full bg-majakara-brown"></span>
                    </div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Total Simpanan</p>
                    <h4 class="text-lg font-black text-gray-900 mt-1 font-display truncate">Rp {{ number_format($stats_modul['tabungan_saldo'], 0, ',', '.') }}</h4>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-50 flex items-center justify-between text-[11px] text-gray-500">
                    <span class="font-medium">Anggota Aktif</span>
                    <span class="font-bold text-gray-800">{{ $stats_modul['tabungan_nasabah'] }} Anggota</span>
                </div>
            </div>

            <!-- Pinjaman Module -->
            <div class="bg-white rounded-3xl p-5 border border-yellow-500/10 hover:border-yellow-500/30 transition-all shadow-xs group flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-2.5 py-1 bg-yellow-50 text-yellow-600 text-[10px] font-black uppercase tracking-wider rounded-lg border border-yellow-100">Pinjaman</span>
                        @if($stats_modul['pinjaman_terlambat'] > 0)
                            <span class="px-2 py-0.5 bg-red-50 text-red-600 text-[9px] font-extrabold rounded-md border border-red-100 animate-pulse">{{ $stats_modul['pinjaman_terlambat'] }} Overdue</span>
                        @else
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                        @endif
                    </div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Total Penyaluran</p>
                    <h4 class="text-lg font-black text-gray-900 mt-1 font-display truncate">Rp {{ number_format($stats_modul['pinjaman_total'], 0, ',', '.') }}</h4>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-50 flex items-center justify-between text-[11px] text-gray-500">
                    <span class="font-medium">Kontrak Aktif</span>
                    <span class="font-bold text-gray-800">{{ $stats_modul['pinjaman_count'] }} Pinjaman</span>
                </div>
            </div>

            <!-- Deposito Module -->
            <div class="bg-white rounded-3xl p-5 border border-green-500/10 hover:border-green-500/30 transition-all shadow-xs group flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-2.5 py-1 bg-green-50 text-green-600 text-[10px] font-black uppercase tracking-wider rounded-lg border border-green-100">Deposito</span>
                        @if($stats_modul['deposito_jatuh'] > 0)
                            <span class="px-2 py-0.5 bg-yellow-50 text-yellow-600 text-[9px] font-extrabold rounded-md border border-yellow-100">{{ $stats_modul['deposito_jatuh'] }} Maturing</span>
                        @else
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                        @endif
                    </div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Simpanan Berjangka</p>
                    <h4 class="text-lg font-black text-gray-900 mt-1 font-display truncate">Rp {{ number_format($stats_modul['deposito_total'], 0, ',', '.') }}</h4>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-50 flex items-center justify-between text-[11px] text-gray-500">
                    <span class="font-medium">Rekening Aktif</span>
                    <span class="font-bold text-gray-800">{{ $stats_modul['deposito_count'] }} Deposito</span>
                </div>
            </div>

            <!-- Gadai Module -->
            <div class="bg-white rounded-3xl p-5 border border-purple-500/10 hover:border-purple-500/30 transition-all shadow-xs group flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-2.5 py-1 bg-purple-50 text-purple-600 text-[10px] font-black uppercase tracking-wider rounded-lg border border-purple-100">Gadai</span>
                        @if($stats_modul['gadai_jatuh'] > 0)
                            <span class="px-2 py-0.5 bg-yellow-50 text-yellow-600 text-[9px] font-extrabold rounded-md border border-yellow-100">{{ $stats_modul['gadai_jatuh'] }} Maturing</span>
                        @else
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                        @endif
                    </div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Pinjaman Gadai</p>
                    <h4 class="text-lg font-black text-gray-900 mt-1 font-display truncate">Rp {{ number_format($stats_modul['gadai_total'], 0, ',', '.') }}</h4>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-50 flex items-center justify-between text-[11px] text-gray-500">
                    <span class="font-medium">Gadai Aktif</span>
                    <span class="font-bold text-gray-800">{{ $stats_modul['gadai_count'] }} Gadai</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart & Aktivitas Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chart Likuiditas (2 Columns) -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 shadow-xs border border-gray-100 flex flex-col h-[400px]">
            <div class="flex items-center justify-between mb-4 border-b border-gray-50 pb-4">
                <div>
                    <h2 class="text-lg font-bold font-display text-gray-900 leading-none">Tren Likuiditas Koperasi</h2>
                    <p class="text-[10px] text-gray-400 mt-1.5 uppercase tracking-widest font-black">Arus Kas Masuk vs Keluar (15 Hari Terakhir)</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-500">
                        <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span> Masuk
                    </div>
                    <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-500">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span> Keluar
                    </div>
                </div>
            </div>
            <div class="relative w-full flex-1">
                <canvas id="likuiditasChart"></canvas>
            </div>
        </div>

        <!-- Aktivitas Terkini (1 Column) -->
        <div class="lg:col-span-1 bg-white rounded-3xl p-6 shadow-xs border border-gray-100 flex flex-col h-[400px]">
            <div class="flex items-center justify-between mb-4 border-b border-gray-50 pb-4">
                <div>
                    <h2 class="text-lg font-bold font-display text-gray-900 leading-none">Log Aktivitas</h2>
                    <p class="text-[10px] text-gray-400 mt-1.5 uppercase tracking-widest font-black">Aktivitas Sistem Terkini</p>
                </div>
            </div>
            <div class="flex-1 overflow-y-auto space-y-4 pr-1 scrollbar-thin">
                @forelse(($aktivitas_terkini ?? []) as $aktivitas)
                <div class="flex items-start gap-3 group">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 border border-gray-50 shadow-xs transition-transform group-hover:scale-110
                        @if($aktivitas['type'] === 'tabungan') bg-blue-50 text-blue-500
                        @elseif($aktivitas['type'] === 'pinjaman') bg-yellow-50 text-yellow-500
                        @elseif($aktivitas['type'] === 'deposito') bg-green-50 text-green-500
                        @elseif($aktivitas['type'] === 'gadai') bg-purple-50 text-purple-500
                        @else bg-gray-50 text-gray-500
                        @endif">
                        @if($aktivitas['type'] === 'tabungan')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        @elseif($aktivitas['type'] === 'pinjaman')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        @elseif($aktivitas['type'] === 'deposito')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        @elseif($aktivitas['type'] === 'gadai')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                        @else
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-gray-800 leading-snug group-hover:text-majakara-brown transition-colors line-clamp-2">{{ $aktivitas['deskripsi'] ?? 'Aktivitas' }}</p>
                        <p class="text-[10px] text-gray-400 font-medium mt-0.5">{{ $aktivitas['waktu'] ?? 'N/A' }}</p>
                    </div>
                </div>
                @empty
                <div class="opacity-50 my-auto">
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

    <!-- Operations Center (Tabbed Panel) -->
    <div x-data="{ activeTab: 'pending' }" class="bg-white rounded-3xl shadow-xs border border-gray-100 overflow-hidden flex flex-col">
        <!-- Header and Tabs Navigation -->
        <div class="p-6 border-b border-gray-100 flex flex-col xl:flex-row xl:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-majakara-brown/5 text-majakara-brown flex items-center justify-center border border-majakara-brown/10 shadow-inner">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold font-display text-gray-900 leading-none">Pusat Kontrol Operasional</h2>
                    <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-widest font-black">Kelola Antrean Persetujuan & Batas Tempo Koperasi</p>
                </div>
            </div>
            
            <!-- Tab Controls -->
            <div class="flex flex-wrap items-center gap-1.5 p-1 bg-gray-50 rounded-2xl border border-gray-100 self-start xl:self-auto">
                <button @click="activeTab = 'pending'" :class="activeTab === 'pending' ? 'bg-white text-majakara-brown shadow-xs border-gray-200' : 'text-gray-500 hover:text-gray-900 border-transparent'" class="px-4 py-2.5 text-xs font-bold rounded-xl transition-all border flex items-center gap-2 cursor-pointer">
                    <span>Antrean Pengajuan</span>
                    <span class="px-2 py-0.5 bg-red-100 text-red-600 rounded-md text-[9px] font-black">{{ $stats['pengajuan_pending'] ?? 0 }}</span>
                </button>
                <button @click="activeTab = 'overdue'" :class="activeTab === 'overdue' ? 'bg-white text-majakara-brown shadow-xs border-gray-200' : 'text-gray-500 hover:text-gray-900 border-transparent'" class="px-4 py-2.5 text-xs font-bold rounded-xl transition-all border flex items-center gap-2 cursor-pointer">
                    <span>Kredit Terlambat</span>
                    @if($stats_modul['pinjaman_terlambat'] > 0)
                        <span class="px-2 py-0.5 bg-red-500 text-white rounded-md text-[9px] font-black">{{ $stats_modul['pinjaman_terlambat'] }}</span>
                    @endif
                </button>
                <button @click="activeTab = 'deposito'" :class="activeTab === 'deposito' ? 'bg-white text-majakara-brown shadow-xs border-gray-200' : 'text-gray-500 hover:text-gray-900 border-transparent'" class="px-4 py-2.5 text-xs font-bold rounded-xl transition-all border flex items-center gap-2 cursor-pointer">
                    <span>Jatuh Tempo Deposito</span>
                    @if($stats_modul['deposito_jatuh'] > 0)
                        <span class="px-2 py-0.5 bg-yellow-500 text-white rounded-md text-[9px] font-black">{{ $stats_modul['deposito_jatuh'] }}</span>
                    @endif
                </button>
                <button @click="activeTab = 'gadai'" :class="activeTab === 'gadai' ? 'bg-white text-majakara-brown shadow-xs border-gray-200' : 'text-gray-500 hover:text-gray-900 border-transparent'" class="px-4 py-2.5 text-xs font-bold rounded-xl transition-all border flex items-center gap-2 cursor-pointer">
                    <span>Jatuh Tempo Gadai</span>
                    @if($stats_modul['gadai_jatuh'] > 0)
                        <span class="px-2 py-0.5 bg-purple-500 text-white rounded-md text-[9px] font-black">{{ $stats_modul['gadai_jatuh'] }}</span>
                    @endif
                </button>
            </div>
        </div>

        <!-- Tabs Content -->
        <div class="overflow-x-auto">
            <!-- Tab 1: Antrean Pengajuan -->
            <div x-show="activeTab === 'pending'" x-transition class="min-w-full">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/80 border-b border-gray-100">
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Nasabah</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Produk</th>
                            <th class="px-6 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Nominal</th>
                            <th class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Tanggal</th>
                            <th class="px-6 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Aksi Cepat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse(($pengajuan_pending ?? []) as $pengajuan)
                        <tr class="hover:bg-gray-50/40 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-majakara-gold/10 text-majakara-brown flex items-center justify-center font-bold text-sm uppercase shadow-xs shrink-0">
                                        {{ substr($pengajuan['nama'] ?? 'N', 0, 1) }}
                                    </div>
                                    <span class="text-sm font-bold text-gray-900">{{ $pengajuan['nama'] ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider border
                                    @if($pengajuan['type'] === 'tabungan') bg-blue-50 text-blue-600 border-blue-100
                                    @elseif($pengajuan['type'] === 'pinjaman') bg-yellow-50 text-yellow-600 border-yellow-100
                                    @elseif($pengajuan['type'] === 'deposito') bg-green-50 text-green-600 border-green-100
                                    @else bg-purple-50 text-purple-600 border-purple-100
                                    @endif">
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
                                <div class="flex items-center justify-end gap-2 opacity-100 lg:opacity-60 group-hover:opacity-100 transition-opacity">
                                    <form action="{{ $pengajuan['route_approve'] ?? '#' }}" method="POST" class="inline">
                                        @csrf
                                        <button type="button" onclick="confirmApprove(this)" class="p-2 bg-green-50 text-green-600 hover:bg-green-500 hover:text-white rounded-lg transition-colors border border-green-100 hover:border-green-600 shadow-xs cursor-pointer" title="Setujui">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                        </button>
                                    </form>
                                    <form action="{{ $pengajuan['route_reject'] ?? '#' }}" method="POST" class="inline">
                                        @csrf
                                        <button type="button" onclick="confirmReject(this)" class="p-2 bg-red-50 text-red-600 hover:bg-red-500 hover:text-white rounded-lg transition-colors border border-red-100 hover:border-red-600 shadow-xs cursor-pointer" title="Tolak">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </form>
                                    <a href="{{ $pengajuan['route_index'] ?? '#' }}" class="p-2 bg-gray-50 text-gray-500 hover:bg-gray-200 hover:text-gray-700 rounded-lg transition-colors border border-gray-200 shadow-xs" title="Detail">
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
                                    minHeight="py-16"
                                />
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Tab 2: Kredit Terlambat -->
            <div x-show="activeTab === 'overdue'" x-transition class="min-w-full">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/80 border-b border-gray-100">
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Nasabah</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">ID Pinjaman</th>
                            <th class="px-6 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Tagihan</th>
                            <th class="px-6 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Denda</th>
                            <th class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Jatuh Tempo</th>
                            <th class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Keterlambatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse(($angsuran_terlambat ?? []) as $overdue)
                        <tr class="hover:bg-gray-50/40 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-red-100 text-red-700 flex items-center justify-center font-bold text-sm uppercase shadow-xs shrink-0">
                                        {{ substr($overdue->pinjaman->nasabah->user->nama ?? 'N', 0, 1) }}
                                    </div>
                                    <span class="text-sm font-bold text-gray-900">{{ $overdue->pinjaman->nasabah->user->nama ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-xs font-mono font-bold text-gray-600">{{ $overdue->pinjaman_id }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <span class="text-sm font-black font-display text-gray-800">Rp {{ number_format($overdue->jumlah_tagihan, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <span class="text-sm font-bold text-red-600">Rp {{ number_format($overdue->denda, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="text-xs font-medium text-gray-500">{{ $overdue->tgl_jatuh_tempo->format('d M Y') }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @php
                                    $durationLate = formatIndonesianDuration($overdue->tgl_jatuh_tempo);
                                @endphp
                                <span class="px-2.5 py-1 bg-red-50 text-red-600 rounded-md text-[10px] font-black uppercase tracking-wider border border-red-100">
                                    {{ $durationLate }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-0">
                                <x-admin.empty-state 
                                    title="Kredit Lancar" 
                                    message="Tidak ada angsuran pinjaman yang terlambat saat ini."
                                    minHeight="py-16"
                                />
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Tab 3: Deposito Jatuh Tempo -->
            <div x-show="activeTab === 'deposito'" x-transition class="min-w-full">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/80 border-b border-gray-100">
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Nasabah</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">No. Rekening</th>
                            <th class="px-6 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Nominal</th>
                            <th class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Bunga</th>
                            <th class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Jatuh Tempo</th>
                            <th class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse(($jatuh_tempo_deposito ?? []) as $dep)
                        <tr class="hover:bg-gray-50/40 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-green-100 text-green-700 flex items-center justify-center font-bold text-sm uppercase shadow-xs shrink-0">
                                        {{ substr($dep->nasabah->user->nama ?? 'N', 0, 1) }}
                                    </div>
                                    <span class="text-sm font-bold text-gray-900">{{ $dep->nasabah->user->nama ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-xs font-mono font-bold text-gray-600">{{ $dep->nomor_deposito }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <span class="text-sm font-black font-display text-gray-800">Rp {{ number_format($dep->nominal_awal, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="text-xs font-bold text-green-600">{{ number_format($dep->bunga * 100, 2) }}%</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="text-xs font-medium text-gray-500">{{ $dep->tgl_jatuh_tempo->format('d M Y') }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @php
                                    $targetDate = $dep->tgl_jatuh_tempo->startOfDay();
                                    $today = now()->startOfDay();
                                    $isPast = $today->gt($targetDate);
                                    $isToday = $today->eq($targetDate);
                                    $duration = formatIndonesianDuration($dep->tgl_jatuh_tempo);
                                @endphp
                                @if($isToday)
                                    <span class="px-2.5 py-1 bg-green-50 text-green-600 rounded-md text-[10px] font-black uppercase border border-green-100">
                                        Hari Ini
                                    </span>
                                @elseif($isPast)
                                    <span class="px-2.5 py-1 bg-red-50 text-red-600 rounded-md text-[10px] font-black uppercase border border-red-100">
                                        Lewat {{ $duration }}
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-yellow-50 text-yellow-600 rounded-md text-[10px] font-black uppercase border border-yellow-100">
                                        {{ $duration }} Lagi
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-0">
                                <x-admin.empty-state 
                                    title="Belum Ada Yang Matang" 
                                    message="Tidak ada rekening deposito yang jatuh tempo dalam 7 hari ke depan."
                                    minHeight="py-16"
                                />
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Tab 4: Jatuh Tempo Gadai -->
            <div x-show="activeTab === 'gadai'" x-transition class="min-w-full">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/80 border-b border-gray-100">
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Nasabah</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">No. Surat</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Barang Gadai</th>
                            <th class="px-6 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Pinjaman</th>
                            <th class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Jatuh Tempo</th>
                            <th class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse(($jatuh_tempo_gadai ?? []) as $gad)
                        <tr class="hover:bg-gray-50/40 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center font-bold text-sm uppercase shadow-xs shrink-0">
                                        {{ substr($gad->nasabah->user->nama ?? 'N', 0, 1) }}
                                    </div>
                                    <span class="text-sm font-bold text-gray-900">{{ $gad->nasabah->user->nama ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-xs font-mono font-bold text-gray-600">{{ $gad->nomor_gadai }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-800">
                                {{ optional($gad->itemGadai)->masterBarang->nama_barang ?? 'Barang Gadai' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <span class="text-sm font-black font-display text-gray-800">Rp {{ number_format($gad->jumlah_pinjaman, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="text-xs font-medium text-gray-500">{{ $gad->tgl_jatuh_tempo->format('d M Y') }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @php
                                    $targetDateG = $gad->tgl_jatuh_tempo->startOfDay();
                                    $today = now()->startOfDay();
                                    $isPastG = $today->gt($targetDateG);
                                    $isTodayG = $today->eq($targetDateG);
                                    $durationG = formatIndonesianDuration($gad->tgl_jatuh_tempo);
                                @endphp
                                @if($isTodayG)
                                    <span class="px-2.5 py-1 bg-green-50 text-green-600 rounded-md text-[10px] font-black uppercase border border-green-100">
                                        Hari Ini
                                    </span>
                                @elseif($isPastG)
                                    <span class="px-2.5 py-1 bg-red-50 text-red-600 rounded-md text-[10px] font-black uppercase border border-red-100">
                                        Lewat {{ $durationG }}
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-yellow-50 text-yellow-600 rounded-md text-[10px] font-black uppercase border border-yellow-100">
                                        {{ $durationG }} Lagi
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-0">
                                <x-admin.empty-state 
                                    title="Aman Terkendali" 
                                    message="Tidak ada barang gadai yang jatuh tempo dalam 7 hari ke depan."
                                    minHeight="py-16"
                                />
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
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

            // Canvas linear gradients
            const ctx = likuiditasCtx.getContext('2d');
            const gradientMasuk = ctx.createLinearGradient(0, 0, 0, 300);
            gradientMasuk.addColorStop(0, 'rgba(16, 185, 129, 0.25)');
            gradientMasuk.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

            const gradientKeluar = ctx.createLinearGradient(0, 0, 0, 300);
            gradientKeluar.addColorStop(0, 'rgba(239, 68, 68, 0.15)');
            gradientKeluar.addColorStop(1, 'rgba(239, 68, 68, 0.0)');

            new Chart(likuiditasCtx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Kas Masuk',
                            data: dataMasuk,
                            borderColor: '#10b981',
                            backgroundColor: gradientMasuk,
                            borderWidth: 2.5,
                            tension: 0.35,
                            fill: true,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#10b981',
                            pointBorderWidth: 2,
                            pointRadius: 3,
                            pointHoverRadius: 5
                        },
                        {
                            label: 'Kas Keluar',
                            data: dataKeluar,
                            borderColor: '#ef4444',
                            backgroundColor: gradientKeluar,
                            borderWidth: 2.5,
                            tension: 0.35,
                            fill: true,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#ef4444',
                            pointBorderWidth: 2,
                            pointRadius: 3,
                            pointHoverRadius: 5
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
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.98)',
                            titleColor: '#1f2937',
                            bodyColor: '#4b5563',
                            borderColor: '#e5e7eb',
                            borderWidth: 1,
                            padding: 10,
                            boxPadding: 4,
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
                            grid: { display: false },
                            ticks: { font: { size: 9, weight: '600', family: "'Inter', sans-serif" }, color: '#9ca3af' }
                        },
                        y: {
                            grid: { color: '#f3f4f6' },
                            border: { dash: [4, 4] },
                            ticks: {
                                font: { size: 9, weight: '600', family: "'Inter', sans-serif" },
                                color: '#9ca3af',
                                maxTicksLimit: 5,
                                callback: function(value) {
                                    if (value >= 1000000) return 'Rp ' + (value / 1000000) + 'Jt';
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
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
