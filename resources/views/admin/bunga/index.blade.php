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
<div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#674c1d] to-[#a07840] p-6 mb-6 shadow-xl">
    <div class="absolute -right-8 -top-8 w-48 h-48 bg-white/5 rounded-full"></div>
    <div class="absolute -right-4 bottom-0 w-64 h-32 bg-white/5 rounded-full"></div>
    <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <p class="text-amber-200 text-sm font-medium mb-1">Net Kas Bunga (Bulan Ini)</p>
            <p class="text-4xl font-bold text-white">{{ $netMargin >= 0 ? '+' : '-' }} Rp {{ number_format(abs($netMargin), 0, ',', '.') }}</p>
            <p class="text-amber-300 text-sm mt-2">Pemasukan Rp {{ number_format($pinjamanTotal + $gadaiTotal, 0, ',', '.') }} &minus; Pengeluaran Rp {{ number_format($depositoTotal, 0, ',', '.') }}</p>
        </div>
        <div class="flex gap-4">
            <div class="text-center bg-white/10 rounded-xl px-5 py-3">
                <p class="text-amber-200 text-xs mb-1">Pemasukan</p>
                <p class="text-white font-bold text-lg">Rp {{ number_format(($pinjamanTotal + $gadaiTotal), 0, ',', '.') }}</p>
            </div>
            <div class="text-center bg-white/10 rounded-xl px-5 py-3">
                <p class="text-amber-200 text-xs mb-1">Pengeluaran</p>
                <p class="text-white font-bold text-lg">Rp {{ number_format($depositoTotal, 0, ',', '.') }}</p>
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
            <span class="flex items-center gap-1 text-xs font-semibold text-green-600 bg-green-50 px-2 py-0.5 rounded-full">
                ▲ 12.5%
            </span>
        </div>
        <p class="text-xs text-gray-500 mb-1">Bunga Pinjaman</p>
        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($pinjamanTotal, 0, ',', '.') }}</p>
        <p class="text-xs text-gray-400 mt-2">Dari seluruh pinjaman aktif</p>
        <div class="mt-3 flex items-center text-xs text-[#674c1d] font-medium">
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
            <span class="flex items-center gap-1 text-xs font-semibold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">
                ▲ 5.8%
            </span>
        </div>
        <p class="text-xs text-gray-500 mb-1">Biaya Gadai (Jasa + Inap)</p>
        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($gadaiTotal, 0, ',', '.') }}</p>
        <p class="text-xs text-gray-400 mt-2">Dari transaksi gadai</p>
        <div class="mt-3 flex items-center text-xs text-[#674c1d] font-medium">
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
            <span class="flex items-center gap-1 text-xs font-semibold text-red-500 bg-red-50 px-2 py-0.5 rounded-full">
                Pengeluaran
            </span>
        </div>
        <p class="text-xs text-gray-500 mb-1">Bunga Deposito (Dibayar)</p>
        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($depositoTotal, 0, ',', '.') }}</p>
        <p class="text-xs text-gray-400 mt-2">Setelah dipotong pajak</p>
        <div class="mt-3 flex items-center text-xs text-[#674c1d] font-medium">
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
            <span class="flex items-center gap-1 text-xs font-semibold text-[#674c1d] bg-[#674c1d]/10 px-2 py-0.5 rounded-full">
                Net Margin
            </span>
        </div>
        <p class="text-xs text-gray-500 mb-1">Margin Bersih Bunga</p>
        @php
            $totalPemasukanMargin = $pinjamanTotal + $gadaiTotal;
            $marginPersen = $totalPemasukanMargin > 0 ? ($netMargin / $totalPemasukanMargin) * 100 : 0;
        @endphp
        <p class="text-2xl font-bold text-[#674c1d]">{{ number_format($marginPersen, 2) }}%</p>
        <p class="text-xs text-gray-500 mt-2">Dari total pendapatan bunga</p>
        <div class="mt-3 w-full bg-gray-200 rounded-full h-1.5">
            <div class="bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] h-1.5 rounded-full" style="width: {{ min(100, max(0, $marginPersen)) }}%"></div>
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
        <span class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded-md font-medium">Data Terkini</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="pb-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Sumber</th>
                    <th class="pb-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Pemasukan</th>
                    <th class="pb-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengeluaran</th>
                    <th class="pb-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Net</th>
                    <th class="pb-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
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
                    <td class="py-3.5 text-right font-semibold text-green-600">Rp {{ number_format($pinjamanTotal, 0, ',', '.') }}</td>
                    <td class="py-3.5 text-right text-gray-400">—</td>
                    <td class="py-3.5 text-right font-bold text-green-600">+ Rp {{ number_format($pinjamanTotal, 0, ',', '.') }}</td>
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
                    <td class="py-3.5 text-right font-semibold text-green-600">Rp {{ number_format($gadaiTotal, 0, ',', '.') }}</td>
                    <td class="py-3.5 text-right text-gray-400">—</td>
                    <td class="py-3.5 text-right font-bold text-green-600">+ Rp {{ number_format($gadaiTotal, 0, ',', '.') }}</td>
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
                    <td class="py-3.5 text-right font-semibold text-red-500">Rp {{ number_format($depositoTotal, 0, ',', '.') }}</td>
                    <td class="py-3.5 text-right font-bold text-red-500">− Rp {{ number_format($depositoTotal, 0, ',', '.') }}</td>
                    <td class="py-3.5 text-center">
                        <a href="{{ route('admin.bunga.deposito') }}" class="text-xs text-[#674c1d] hover:underline font-medium">Detail →</a>
                    </td>
                </tr>
                <tr class="bg-gradient-to-r from-[#674c1d]/5 to-transparent">
                    <td class="py-4 font-bold text-gray-900 pl-2">TOTAL NET</td>
                    <td class="py-4 text-right font-bold text-green-700">Rp {{ number_format($pinjamanTotal + $gadaiTotal, 0, ',', '.') }}</td>
                    <td class="py-4 text-right font-bold text-red-500">Rp {{ number_format($depositoTotal, 0, ',', '.') }}</td>
                    <td class="py-4 text-right font-bold text-[#674c1d] text-base">{{ $netMargin >= 0 ? '+' : '-' }} Rp {{ number_format(abs($netMargin), 0, ',', '.') }}</td>
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
