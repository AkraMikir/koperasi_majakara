@extends('layouts.admin')

@section('title', 'Dashboard Bunga - Koperasi Majakara')

@section('content')
{{-- Header --}}
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard Analisis Bunga</h1>
            <p class="text-sm text-gray-500 mt-1">Ringkasan arus kas bunga — Pemasukan vs Pengeluaran</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-amber-50 border border-amber-200 text-xs font-semibold text-amber-700">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                Data Bulan Ini
            </span>
            <span class="text-sm text-gray-500">{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</span>
        </div>
    </div>
</div>

{{-- Net Cashflow Banner --}}
<div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#674c1d] to-[#a07840] p-6 mb-6 shadow-xl text-white">
    <div class="absolute -right-8 -top-8 w-48 h-48 bg-white/5 rounded-full"></div>
    <div class="absolute -right-4 bottom-0 w-64 h-32 bg-white/5 rounded-full"></div>
    <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 gap-8 divide-y md:divide-y-0 md:divide-x divide-white/10">
        {{-- Proyeksi Section --}}
        <div class="space-y-4 pr-0 md:pr-8">
            <div class="flex items-center justify-between">
                <span class="text-amber-200 text-xs font-semibold uppercase tracking-wider bg-white/10 px-2 py-0.5 rounded">Proyeksi Portofolio Aktif</span>
                <span class="text-xs text-amber-200">Estimasi Bunga Berjalan</span>
            </div>
            <div>
                <p class="text-3xl font-bold">{{ $netMargin >= 0 ? '+' : '-' }} Rp {{ number_format(abs($netMargin), 0, ',', '.') }}</p>
                <p class="text-amber-300 text-xs mt-1">Pemasukan Rp {{ number_format($pinjamanTotal + $gadaiTotal, 0, ',', '.') }} &minus; Pengeluaran Rp {{ number_format($depositoTotal, 0, ',', '.') }}</p>
            </div>
            <div class="grid grid-cols-2 gap-3 mt-2">
                <div class="bg-white/5 rounded-xl px-4 py-2 border border-white/5">
                    <p class="text-amber-200 text-[10px] uppercase font-medium">Pemasukan</p>
                    <p class="text-sm font-bold">Rp {{ number_format(($pinjamanTotal + $gadaiTotal), 0, ',', '.') }}</p>
                </div>
                <div class="bg-white/5 rounded-xl px-4 py-2 border border-white/5">
                    <p class="text-amber-200 text-[10px] uppercase font-medium">Pengeluaran</p>
                    <p class="text-sm font-bold">Rp {{ number_format($depositoTotal, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        {{-- Realisasi Section --}}
        <div class="space-y-4 pt-4 md:pt-0 pl-0 md:pl-8">
            <div class="flex items-center justify-between">
                <span class="text-amber-200 text-xs font-semibold uppercase tracking-wider bg-green-500/20 px-2 py-0.5 rounded text-green-300">Realisasi Kas (Bulan Ini)</span>
                <span class="text-xs text-amber-200">Arus Kas Riil Terbayar</span>
            </div>
            <div>
                <p class="text-3xl font-bold text-green-300">{{ $netRealisasi >= 0 ? '+' : '-' }} Rp {{ number_format(abs($netRealisasi), 0, ',', '.') }}</p>
                <p class="text-amber-300 text-xs mt-1">Pemasukan Rp {{ number_format($realisasiPinjaman + $realisasiGadai, 0, ',', '.') }} &minus; Pengeluaran Rp {{ number_format($realisasiDeposito, 0, ',', '.') }}</p>
            </div>
            <div class="grid grid-cols-2 gap-3 mt-2">
                <div class="bg-white/5 rounded-xl px-4 py-2 border border-white/5">
                    <p class="text-amber-200 text-[10px] uppercase font-medium">Masuk (Riil)</p>
                    <p class="text-sm font-bold text-green-300">Rp {{ number_format($realisasiPinjaman + $realisasiGadai, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white/5 rounded-xl px-4 py-2 border border-white/5">
                    <p class="text-amber-200 text-[10px] uppercase font-medium">Keluar (Riil)</p>
                    <p class="text-sm font-bold text-red-300">Rp {{ number_format($realisasiDeposito, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- KPI Cards Grid --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
    {{-- Bunga Pinjaman --}}
    <a href="{{ route('admin.bunga.pinjaman') }}" class="group bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center group-hover:bg-green-100 transition-colors">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <span class="flex items-center gap-1 text-[10px] font-semibold text-green-600 bg-green-50 px-2 py-0.5 rounded-full">
                Bunga Pinjaman
            </span>
        </div>
        <div class="flex flex-col gap-2">
            <div>
                <span class="text-[10px] text-gray-400 block uppercase font-semibold">Proyeksi Aktif</span>
                <span class="text-xl font-bold text-gray-900">Rp {{ number_format($pinjamanTotal, 0, ',', '.') }}</span>
            </div>
            <div class="pt-1.5 border-t border-gray-100">
                <span class="text-[10px] text-gray-400 block uppercase font-semibold">Realisasi Bulan Ini</span>
                <span class="text-sm font-bold text-green-600">Rp {{ number_format($realisasiPinjaman, 0, ',', '.') }}</span>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs text-[#674c1d] font-medium">
            Lihat Detail <svg class="w-3 h-3 ml-1 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
        </div>
    </a>

    {{-- Biaya Gadai --}}
    <a href="{{ route('admin.bunga.gadai') }}" class="group bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center group-hover:bg-amber-100 transition-colors">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <span class="flex items-center gap-1 text-[10px] font-semibold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">
                Biaya Jasa Gadai
            </span>
        </div>
        <div class="flex flex-col gap-2">
            <div>
                <span class="text-[10px] text-gray-400 block uppercase font-semibold">Proyeksi Aktif</span>
                <span class="text-xl font-bold text-gray-900">Rp {{ number_format($gadaiTotal, 0, ',', '.') }}</span>
            </div>
            <div class="pt-1.5 border-t border-gray-100">
                <span class="text-[10px] text-gray-400 block uppercase font-semibold">Realisasi Bulan Ini</span>
                <span class="text-sm font-bold text-green-600">Rp {{ number_format($realisasiGadai, 0, ',', '.') }}</span>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs text-[#674c1d] font-medium">
            Lihat Detail <svg class="w-3 h-3 ml-1 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
        </div>
    </a>

    {{-- Bunga Deposito (Keluar) --}}
    <a href="{{ route('admin.bunga.deposito') }}" class="group bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center group-hover:bg-red-100 transition-colors">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
            </div>
            <span class="flex items-center gap-1 text-[10px] font-semibold text-red-500 bg-red-50 px-2 py-0.5 rounded-full">
                Bunga Deposito
            </span>
        </div>
        <div class="flex flex-col gap-2">
            <div>
                <span class="text-[10px] text-gray-400 block uppercase font-semibold">Proyeksi Bersih</span>
                <span class="text-xl font-bold text-gray-900">Rp {{ number_format($depositoTotal, 0, ',', '.') }}</span>
            </div>
            <div class="pt-1.5 border-t border-gray-100">
                <span class="text-[10px] text-gray-400 block uppercase font-semibold">Realisasi Bulan Ini</span>
                <span class="text-sm font-bold text-red-500">Rp {{ number_format($realisasiDeposito, 0, ',', '.') }}</span>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs text-[#674c1d] font-medium">
            Lihat Detail <svg class="w-3 h-3 ml-1 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
        </div>
    </a>

    {{-- Net Margin --}}
    <div class="bg-gradient-to-br from-[#674c1d]/5 to-[#8b6f2f]/10 rounded-2xl border border-[#8b6f2f]/20 p-5">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-[#674c1d]/10 flex items-center justify-center">
                <svg class="w-6 h-6 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
            </div>
            <span class="flex items-center gap-1 text-[10px] font-semibold text-[#674c1d] bg-[#674c1d]/10 px-2 py-0.5 rounded-full">
                Margin Bersih
            </span>
        </div>
        @php
            $totalPemasukanMargin = $pinjamanTotal + $gadaiTotal;
            $marginPersen = $totalPemasukanMargin > 0 ? ($netMargin / $totalPemasukanMargin) * 100 : 0;
            
            $totalRealisasiPemasukan = $realisasiPinjaman + $realisasiGadai;
            $realisasiMarginPersen = $totalRealisasiPemasukan > 0 ? ($netRealisasi / $totalRealisasiPemasukan) * 100 : 0;
        @endphp
        <div class="flex flex-col gap-2">
            <div>
                <span class="text-[10px] text-gray-400 block uppercase font-semibold">Margin Proyeksi</span>
                <span class="text-xl font-bold text-[#674c1d]">{{ number_format($marginPersen, 1) }}%</span>
            </div>
            <div class="pt-1.5 border-t border-gray-100">
                <span class="text-[10px] text-gray-400 block uppercase font-semibold">Margin Realisasi</span>
                <span class="text-sm font-bold text-gray-700">{{ number_format($realisasiMarginPersen, 1) }}%</span>
            </div>
        </div>
    </div>
</div>

{{-- Charts Row --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
    {{-- Trend Chart --}}
    <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="font-semibold text-gray-900">Tren Arus Kas Bunga</h3>
                <p class="text-xs text-gray-400 mt-0.5">6 bulan terakhir (Pemasukan vs Pengeluaran)</p>
            </div>
            <select class="text-xs border border-gray-200 rounded-lg px-3 py-1.5 text-gray-600 focus:outline-none focus:border-[#8b6f2f]">
                <option>6 Bulan</option>
                <option>12 Bulan</option>
                <option>Tahun Ini</option>
            </select>
        </div>
        <canvas id="trendBungaChart" height="120"></canvas>
    </div>

    {{-- Pie Chart Komposisi --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="mb-6">
            <h3 class="font-semibold text-gray-900">Komposisi Pemasukan</h3>
            <p class="text-xs text-gray-400 mt-0.5">Porsi per sumber bunga</p>
        </div>
        <canvas id="komposisiChart" height="200"></canvas>
        <div class="mt-4 space-y-2">
            <div class="flex items-center justify-between text-xs">
                @php
                    $totalIncome = $pinjamanTotal + $gadaiTotal;
                    $pctPinjaman = $totalIncome > 0 ? ($pinjamanTotal / $totalIncome) * 100 : 0;
                    $pctGadai = $totalIncome > 0 ? ($gadaiTotal / $totalIncome) * 100 : 0;
                @endphp
                <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-sm bg-green-500 inline-block"></span> Pinjaman</div>
                <span class="font-semibold text-gray-700">{{ number_format($pctPinjaman, 1) }}%</span>
            </div>
            <div class="flex items-center justify-between text-xs">
                <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-sm bg-amber-500 inline-block"></span> Biaya Gadai</div>
                <span class="font-semibold text-gray-700">{{ number_format($pctGadai, 1) }}%</span>
            </div>
        </div>
    </div>
</div>

{{-- Summary Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
    <div class="flex items-center justify-between mb-5">
        <h3 class="font-semibold text-gray-900">Rekap Arus Kas Bunga — {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</h3>
        <span class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded-md font-medium">Perbandingan Proyeksi vs Realisasi</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="pb-3 text-left">Sumber</th>
                    <th class="pb-3 text-right">Pemasukan (Proyeksi)</th>
                    <th class="pb-3 text-right text-green-600 bg-green-50/50">Pemasukan (Realisasi)</th>
                    <th class="pb-3 text-right">Pengeluaran (Proyeksi)</th>
                    <th class="pb-3 text-right text-red-500 bg-red-50/50">Pengeluaran (Realisasi)</th>
                    <th class="pb-3 text-right">Net (Proyeksi)</th>
                    <th class="pb-3 text-right font-bold text-gray-700 bg-amber-50/50">Net (Realisasi)</th>
                    <th class="pb-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-3.5">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Bunga Pinjaman</p>
                            </div>
                        </div>
                    </td>
                    <td class="py-3.5 text-right font-semibold text-gray-600">Rp {{ number_format($pinjamanTotal, 0, ',', '.') }}</td>
                    <td class="py-3.5 text-right font-bold text-green-600 bg-green-50/30">Rp {{ number_format($realisasiPinjaman, 0, ',', '.') }}</td>
                    <td class="py-3.5 text-right text-gray-400">—</td>
                    <td class="py-3.5 text-right text-gray-400 bg-red-50/30">—</td>
                    <td class="py-3.5 text-right font-semibold text-green-600">+ Rp {{ number_format($pinjamanTotal, 0, ',', '.') }}</td>
                    <td class="py-3.5 text-right font-bold text-green-600 bg-amber-50/30">+ Rp {{ number_format($realisasiPinjaman, 0, ',', '.') }}</td>
                    <td class="py-3.5 text-center">
                        <a href="{{ route('admin.bunga.pinjaman') }}" class="text-xs text-[#674c1d] hover:underline font-medium">Detail →</a>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-3.5">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Biaya Gadai</p>
                            </div>
                        </div>
                    </td>
                    <td class="py-3.5 text-right font-semibold text-gray-600">Rp {{ number_format($gadaiTotal, 0, ',', '.') }}</td>
                    <td class="py-3.5 text-right font-bold text-green-600 bg-green-50/30">Rp {{ number_format($realisasiGadai, 0, ',', '.') }}</td>
                    <td class="py-3.5 text-right text-gray-400">—</td>
                    <td class="py-3.5 text-right text-gray-400 bg-red-50/30">—</td>
                    <td class="py-3.5 text-right font-semibold text-green-600">+ Rp {{ number_format($gadaiTotal, 0, ',', '.') }}</td>
                    <td class="py-3.5 text-right font-bold text-green-600 bg-amber-50/30">+ Rp {{ number_format($realisasiGadai, 0, ',', '.') }}</td>
                    <td class="py-3.5 text-center">
                        <a href="{{ route('admin.bunga.gadai') }}" class="text-xs text-[#674c1d] hover:underline font-medium">Detail →</a>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-3.5">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center">
                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Bunga Deposito (Dibayar)</p>
                            </div>
                        </div>
                    </td>
                    <td class="py-3.5 text-right text-gray-400">—</td>
                    <td class="py-3.5 text-right text-gray-400 bg-green-50/30">—</td>
                    <td class="py-3.5 text-right font-semibold text-red-500">Rp {{ number_format($depositoTotal, 0, ',', '.') }}</td>
                    <td class="py-3.5 text-right font-bold text-red-500 bg-red-50/30">Rp {{ number_format($realisasiDeposito, 0, ',', '.') }}</td>
                    <td class="py-3.5 text-right font-semibold text-red-500">− Rp {{ number_format($depositoTotal, 0, ',', '.') }}</td>
                    <td class="py-3.5 text-right font-bold text-red-500 bg-amber-50/30">− Rp {{ number_format($realisasiDeposito, 0, ',', '.') }}</td>
                    <td class="py-3.5 text-center">
                        <a href="{{ route('admin.bunga.deposito') }}" class="text-xs text-[#674c1d] hover:underline font-medium">Detail →</a>
                    </td>
                </tr>
                <tr class="bg-gradient-to-r from-[#674c1d]/5 to-transparent font-bold">
                    <td class="py-4 text-gray-900 pl-2">TOTAL NET</td>
                    <td class="py-4 text-right text-gray-700">Rp {{ number_format($pinjamanTotal + $gadaiTotal, 0, ',', '.') }}</td>
                    <td class="py-4 text-right text-green-700 bg-green-50/30">Rp {{ number_format($realisasiPinjaman + $realisasiGadai, 0, ',', '.') }}</td>
                    <td class="py-4 text-right text-red-500">Rp {{ number_format($depositoTotal, 0, ',', '.') }}</td>
                    <td class="py-4 text-right text-red-600 bg-red-50/30">Rp {{ number_format($realisasiDeposito, 0, ',', '.') }}</td>
                    <td class="py-4 text-right text-[#674c1d] text-base">{{ $netMargin >= 0 ? '+' : '-' }} Rp {{ number_format(abs($netMargin), 0, ',', '.') }}</td>
                    <td class="py-4 text-right text-[#674c1d] text-base bg-amber-50/30">{{ $netRealisasi >= 0 ? '+' : '-' }} Rp {{ number_format(abs($netRealisasi), 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Trend Chart
    const trendCtx = document.getElementById('trendBungaChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [
                {
                    label: 'Pemasukan (Pinjaman + Gadai)',
                    data: {!! json_encode($pemasukanData) !!},
                    borderColor: '#22c55e',
                    backgroundColor: 'rgba(34,197,94,0.08)',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#22c55e',
                    pointRadius: 4,
                    tension: 0.4,
                    fill: true,
                },
                {
                    label: 'Pengeluaran (Deposito)',
                    data: {!! json_encode($pengeluaranData) !!},
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239,68,68,0.06)',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#ef4444',
                    pointRadius: 4,
                    tension: 0.4,
                    fill: true,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 12 }, usePointStyle: true, padding: 16 } },
                tooltip: {
                    callbacks: {
                        label: ctx => ' Rp ' + ctx.raw.toLocaleString('id-ID')
                    }
                }
            },
            scales: {
                x: { grid: { display: false } },
                y: {
                    grid: { color: 'rgba(0,0,0,0.04)' },
                    ticks: {
                        callback: v => 'Rp ' + (v / 1000000).toFixed(1) + ' Jt'
                    }
                }
            }
        }
    });

    // Komposisi Pie Chart
    const pieCtx = document.getElementById('komposisiChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'doughnut',
        data: {
            labels: ['Bunga Pinjaman', 'Biaya Gadai'],
            datasets: [{
                data: [{{ $pinjamanTotal }}, {{ $gadaiTotal }}],
                backgroundColor: ['#22c55e', '#f59e0b'],
                borderWidth: 0,
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true,
            cutout: '70%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ' Rp ' + ctx.raw.toLocaleString('id-ID')
                    }
                }
            }
        }
    });
});
</script>
@endpush
