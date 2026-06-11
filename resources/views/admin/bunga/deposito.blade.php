@extends('layouts.admin')

@section('title', 'Bunga Deposito - Koperasi Majakara')

@section('content')
{{-- Header --}}
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.bunga.index') }}" class="p-2 rounded-xl hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard Bunga Deposito</h1>
                </div>
                <p class="text-sm text-gray-500 mt-1 ml-10">Analisis kewajiban bunga deposito yang dibayarkan ke nasabah</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-red-50 border border-red-200 text-xs font-semibold text-red-700">
                <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                Kewajiban
            </span>
            <span class="text-sm text-gray-500">{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</span>
        </div>
    </div>
</div>

{{-- Summary Banner --}}
<div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#b91c1c] to-[#7f1d1d] p-6 mb-6 shadow-xl text-white">
    <div class="absolute -right-8 -top-8 w-48 h-48 bg-white/5 rounded-full"></div>
    <div class="absolute -right-4 bottom-0 w-64 h-32 bg-white/5 rounded-full"></div>
    <div class="relative z-10 grid grid-cols-1 md:grid-cols-3 gap-6 divide-y md:divide-y-0 md:divide-x divide-white/10">
        <div class="space-y-1">
            <p class="text-red-200 text-[10px] uppercase font-semibold tracking-wider">Total Kewajiban Bersih</p>
            <p class="text-2xl font-bold">Rp {{ number_format($totalBersih, 0, ',', '.') }}</p>
            <p class="text-red-300 text-xs">Setelah pajak 20% ({{ $depositoAktif }} deposito)</p>
        </div>
        <div class="space-y-1 pt-4 md:pt-0 md:pl-6">
            <p class="text-red-200 text-[10px] uppercase font-semibold tracking-wider">Siap Cair (H-7 Jatuh Tempo)</p>
            <p class="text-2xl font-bold text-yellow-300">Rp {{ number_format($totalSiapCair, 0, ',', '.') }}</p>
            <p class="text-red-300 text-xs">Sudah masuk persiapan pencairan</p>
        </div>
        <div class="space-y-1 pt-4 md:pt-0 md:pl-6">
            <p class="text-red-200 text-[10px] uppercase font-semibold tracking-wider">Estimasi Akrual</p>
            <p class="text-2xl font-bold text-orange-300">Rp {{ number_format($totalEstimasiAkrual, 0, ',', '.') }}</p>
            <p class="text-red-300 text-xs">Perhitungan in-memory deposito aktif</p>
        </div>
    </div>
</div>


{{-- Alert Kewajiban --}}
<div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-6 flex items-start gap-3">
    <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
    <div>
        <p class="text-sm font-semibold text-red-700">Perhatian: Kewajiban Bunga Deposito</p>
        <p class="text-xs text-red-600 mt-0.5">Bunga deposito merupakan pengeluaran koperasi. Pantau rasio kewajiban terhadap pendapatan bunga secara rutin untuk menjaga likuiditas.</p>
    </div>
</div>

{{-- KPI Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
    {{-- Total Bunga Bersih --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-xs text-gray-500 font-medium">Total Kewajiban Bersih</span>
        </div>
        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalBersih, 0, ',', '.') }}</p>
        <div class="flex items-center gap-1.5 mt-2">
            <span class="text-xs text-gray-400">Total estimasi + siap cair (setelah pajak)</span>
        </div>
    </div>

    {{-- Siap Cair (dari persiapanCair) --}}
    <div class="bg-white rounded-2xl shadow-sm border border-red-100 p-5 ring-1 ring-red-100">
        <div class="flex items-center justify-between gap-3 mb-3">
            <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-[10px] font-semibold text-red-700 bg-red-50 px-2 py-0.5 rounded-full">Siap Cair</span>
        </div>
        <p class="text-2xl font-bold text-red-700">Rp {{ number_format($totalSiapCair, 0, ',', '.') }}</p>
        <p class="text-xs text-gray-400 mt-2">Bunga dari record persiapan cair</p>
    </div>

    {{-- Estimasi Akrual (fallback in-memory) --}}
    <div class="bg-white rounded-2xl shadow-sm border border-amber-100 p-5 ring-1 ring-amber-100">
        <div class="flex items-center justify-between gap-3 mb-3">
            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            </div>
            <span class="text-[10px] font-semibold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full">Estimasi Akrual</span>
        </div>
        <p class="text-2xl font-bold text-amber-700">Rp {{ number_format($totalEstimasiAkrual, 0, ',', '.') }}</p>
        <p class="text-xs text-gray-400 mt-2">Bunga akrual deposito belum jatuh tempo H-7</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <span class="text-xs text-gray-500 font-medium">Deposito Aktif</span>
        </div>
        <p class="text-2xl font-bold text-gray-900">{{ number_format($depositoAktif, 0, ',', '.') }}</p>
        <p class="text-xs text-gray-400 mt-2">Total nasabah deposito berjalan</p>
    </div>
</div>

{{-- Charts --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
    {{-- Trend Kewajiban Bunga --}}
    <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="font-semibold text-gray-900">Tren Kewajiban Bunga Deposito</h3>
                <p class="text-xs text-gray-400 mt-0.5">Bunga kotor vs Bunga bersih (setelah pajak 20%)</p>
            </div>
        </div>
        <canvas id="depositoTrendChart" height="130"></canvas>
    </div>

    {{-- Distribusi per Tenor --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-semibold text-gray-900 mb-1">Distribusi per Tenor</h3>
        <p class="text-xs text-gray-400 mb-5">Porsi kewajiban berdasarkan tenor deposito</p>
        <canvas id="tenorChart" height="180"></canvas>
        <div class="mt-4 space-y-2 text-xs">
            @php
                $colors = ['#ef4444', '#f97316', '#eab308', '#8b5cf6', '#10b981', '#3b82f6'];
                $total = array_sum($tenorData);
            @endphp
            @foreach($tenorLabels as $idx => $label)
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm inline-block" style="background:{{ $colors[$idx % count($colors)] }}"></span>{{ $label }}</div>
                <span class="font-semibold text-gray-700">{{ $total > 0 ? round(($tenorData[$idx]/$total)*100) : 0 }}%</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Tabel Deposito Aktif --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-5">
        <div>
            <h3 class="font-semibold text-gray-900">Daftar Deposito Aktif</h3>
            <p class="text-xs text-gray-400 mt-0.5">Proyeksi bunga yang harus dibayarkan</p>
        </div>
        <a href="{{ route('admin.deposito.deposito-list') }}" class="text-xs text-[#674c1d] hover:underline font-medium">Lihat semua deposito →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="pb-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nasabah</th>
                    <th class="pb-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Nominal</th>
                    <th class="pb-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Rate/Thn</th>
                    <th class="pb-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Bunga Kotor</th>
                    <th class="pb-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Pajak (20%)</th>
                    <th class="pb-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Bunga Bersih</th>
                    <th class="pb-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Sumber Data</th>
                    <th class="pb-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Jatuh Tempo</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($listDeposito as $row)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-3.5">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center text-white text-xs font-bold">
                                {{ substr(optional(optional($row->nasabah)->user)->nama ?? 'U', 0, 1) }}
                            </div>
                            <span class="font-medium text-gray-900">{{ optional(optional($row->nasabah)->user)->nama ?? 'Unknown' }}</span>
                        </div>
                    </td>
                    <td class="py-3.5 text-right text-gray-700">Rp {{ number_format($row->nominal_awal ?? 0, 0, ',', '.') }}</td>
                    <td class="py-3.5 text-center">
                        <span class="px-2 py-0.5 bg-blue-50 text-blue-700 text-xs font-semibold rounded-full">{{ floatval($row->bunga ?? 0) * 100 }}%/Thn</span>
                    </td>
                    <td class="py-3.5 text-right text-gray-600">Rp {{ number_format($row->bunga_kotor_rp ?? 0, 0, ',', '.') }}</td>
                    <td class="py-3.5 text-right text-orange-500 text-xs">Rp {{ number_format($row->pajak_rp ?? 0, 0, ',', '.') }}</td>
                    <td class="py-3.5 text-right font-semibold text-red-600">Rp {{ number_format($row->bunga_bersih_rp ?? 0, 0, ',', '.') }}</td>
                    <td class="py-3.5 text-center">
                        @if(($row->sumber_bunga ?? 'estimasi') === 'siap_cair')
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-50 text-green-700">Siap Cair</span>
                        @else
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-amber-50 text-amber-700">Estimasi</span>
                        @endif
                    </td>
                    <td class="py-3.5 text-center text-xs text-gray-500">
                        {{ $row->tgl_jatuh_tempo ? \Carbon\Carbon::parse($row->tgl_jatuh_tempo)->translatedFormat('d M Y') : \Carbon\Carbon::parse($row->tgl_mulai)->addMonths(1)->translatedFormat('d M Y') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="py-10 text-center text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-2 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        Belum ada data deposito aktif.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
@once
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endonce
<script>
document.addEventListener('DOMContentLoaded', function () {
    new Chart(document.getElementById('depositoTrendChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [
                {
                    label: 'Bunga Kotor',
                    data: @json($kotorData),
                    borderColor: '#f97316',
                    backgroundColor: 'rgba(249,115,22,0.06)',
                    borderWidth: 2,
                    borderDash: [5, 3],
                    tension: 0.4,
                    pointRadius: 3,
                },
                {
                    label: 'Bunga Bersih (setelah pajak)',
                    data: @json($bersihData),
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239,68,68,0.06)',
                    borderWidth: 2.5,
                    tension: 0.4,
                    pointRadius: 4,
                    fill: true,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 14, font: { size: 12 } } }, tooltip: { callbacks: { label: ctx => ' Rp ' + ctx.raw.toLocaleString('id-ID') } } },
            scales: {
                x: { grid: { display: false } },
                y: { grid: { color: 'rgba(0,0,0,0.04)' }, ticks: { callback: v => 'Rp ' + (v / 1000000).toFixed(1) + ' Jt' } }
            }
        }
    });

    new Chart(document.getElementById('tenorChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: @json($tenorLabels),
            datasets: [{ data: @json($tenorData), backgroundColor: ['#ef4444', '#f97316', '#eab308', '#8b5cf6', '#10b981', '#3b82f6'], borderWidth: 0, hoverOffset: 6 }]
        },
        options: { responsive: true, cutout: '68%', plugins: { legend: { display: false } } }
    });
});
</script>
@endpush
