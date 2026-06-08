@extends('layouts.admin')

@section('title', 'Bunga Pinjaman - Koperasi Majakara')

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
                    <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard Bunga Pinjaman</h1>
                </div>
                <p class="text-sm text-gray-500 mt-1 ml-10">Analisis pendapatan bunga dari modul pinjaman</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-green-50 border border-green-200 text-xs font-semibold text-green-700">
                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                Data Live
            </span>
            <span class="text-sm text-gray-500">{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</span>
        </div>
    </div>
</div>

{{-- Summary Banner --}}
<div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#1a8a5a] to-[#0f5f3d] p-6 mb-6 shadow-xl text-white">
    <div class="absolute -right-8 -top-8 w-48 h-48 bg-white/5 rounded-full"></div>
    <div class="absolute -right-4 bottom-0 w-64 h-32 bg-white/5 rounded-full"></div>
    <div class="relative z-10 grid grid-cols-1 md:grid-cols-3 gap-6 divide-y md:divide-y-0 md:divide-x divide-white/10">
        <div class="space-y-1">
            <p class="text-green-200 text-[10px] uppercase font-semibold tracking-wider">Total Bunga Portofolio Aktif</p>
            <p class="text-2xl font-bold">Rp {{ number_format($totalBunga, 0, ',', '.') }}</p>
            <p class="text-green-300 text-xs">{{ $pinjamanAktif }} pinjaman aktif berjalan</p>
        </div>
        <div class="space-y-1 pt-4 md:pt-0 md:pl-6">
            <p class="text-green-200 text-[10px] uppercase font-semibold tracking-wider">Pendapatan Bunga Bulan Ini</p>
            <p class="text-2xl font-bold text-yellow-300">Rp {{ number_format($bungaBulanIni, 0, ',', '.') }}</p>
            <p class="text-green-300 text-xs">Realisasi angsuran diterima bulan ini</p>
        </div>
        <div class="space-y-1 pt-4 md:pt-0 md:pl-6">
            <p class="text-green-200 text-[10px] uppercase font-semibold tracking-wider">Proyeksi Bulan Depan</p>
            <p class="text-2xl font-bold text-cyan-300">Rp {{ number_format($proyeksiBulanDepan, 0, ',', '.') }}</p>
            <p class="text-green-300 text-xs">Estimasi tren +5% dari portofolio aktif</p>
        </div>
    </div>
</div>

{{-- KPI Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-xs text-gray-500 font-medium">Total Bunga Masuk</span>
        </div>
        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalBunga, 0, ',', '.') }}</p>
        <div class="flex items-center gap-1.5 mt-2">
            <span class="text-xs text-gray-400">Total bunga dari pinjaman aktif</span>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <span class="text-xs text-gray-500 font-medium">Pinjaman Aktif</span>
        </div>
        <p class="text-2xl font-bold text-gray-900">{{ number_format($pinjamanAktif, 0, ',', '.') }}</p>
        <p class="text-xs text-gray-400 mt-2">Menghasilkan bunga aktif</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-xs text-gray-500 font-medium">Pendapatan Bunga Bulan Ini</span>
        </div>
        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($bungaBulanIni, 0, ',', '.') }}</p>
        <p class="text-xs text-gray-400 mt-2">Bunga bersih didapatkan bulan ini</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <span class="text-xs text-gray-500 font-medium">Proyeksi Bulan Depan</span>
        </div>
        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($proyeksiBulanDepan, 0, ',', '.') }}</p>
        <p class="text-xs text-gray-400 mt-2">Estimasi berdasarkan tren</p>
    </div>
</div>

{{-- Charts --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
    {{-- Trend Bulanan --}}
    <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="font-semibold text-gray-900">Tren Pendapatan Bunga Pinjaman</h3>
                <p class="text-xs text-gray-400 mt-0.5">Akumulasi bunga per bulan (6 bulan terakhir)</p>
            </div>
        </div>
        <canvas id="pinjamanTrendChart" height="130"></canvas>
    </div>

    {{-- Distribusi per Durasi --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-semibold text-gray-900 mb-1">Distribusi per Durasi</h3>
        <p class="text-xs text-gray-400 mb-5">Porsi bunga berdasarkan tenor</p>
        <canvas id="durasiChart" height="180"></canvas>
        <div class="mt-4 space-y-2 text-xs">
            @php
                $colors = ['#10b981', '#3b82f6', '#8b5cf6', '#f59e0b', '#ef4444', '#14b8a6', '#f43f5e', '#6366f1'];
                $total = array_sum($durasiData);
            @endphp
            @foreach($durasiLabels as $idx => $label)
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm inline-block" style="background:{{ $colors[$idx % count($colors)] }}"></span>{{ $label }}</div>
                <span class="font-semibold text-gray-700">{{ $total > 0 ? round(($durasiData[$idx]/$total)*100) : 0 }}%</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Tabel Daftar Pinjaman Aktif --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-5">
        <div>
            <h3 class="font-semibold text-gray-900">Daftar Pinjaman Aktif (Berdasarkan Bunga)</h3>
            <p class="text-xs text-gray-400 mt-0.5">Diurutkan berdasarkan bunga terbesar</p>
        </div>
        <div class="flex items-center gap-2">
            <input type="text" placeholder="Cari nasabah..." class="text-sm border border-gray-200 rounded-xl px-3 py-1.5 focus:outline-none focus:border-[#8b6f2f] w-48">
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="pb-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nasabah</th>
                    <th class="pb-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Pokok Pinjaman</th>
                    <th class="pb-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Rate</th>
                    <th class="pb-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Bunga/Bln</th>
                    <th class="pb-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Tenor</th>
                    <th class="pb-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Progres</th>
                    <th class="pb-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($listPinjaman as $row)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-3.5">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] flex items-center justify-center text-white text-xs font-bold">
                                {{ substr(optional(optional($row->nasabah)->user)->nama ?? 'U', 0, 1) }}
                            </div>
                            <span class="font-medium text-gray-900">{{ optional(optional($row->nasabah)->user)->nama ?? 'Unknown' }}</span>
                        </div>
                    </td>
                    <td class="py-3.5 text-right text-gray-700">Rp {{ number_format($row->jumlah_pinjam ?? 0, 0, ',', '.') }}</td>
                    <td class="py-3.5 text-center">
                        <span class="px-2 py-0.5 bg-blue-50 text-blue-700 text-xs font-semibold rounded-full">{{ (float) $row->bunga }}%</span>
                    </td>
                    <td class="py-3.5 text-right font-semibold text-green-600">Rp {{ number_format($row->bunga_rp ?? 0, 0, ',', '.') }}</td>
                    <td class="py-3.5 text-center text-gray-500">{{ $row->lama_pinjam }} Bln</td>
                    <td class="py-3.5 text-center text-gray-500">{{ $row->payment_progress }}</td>
                    <td class="py-3.5 text-center">
                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ strtolower($row->lunas) === 'lancar' ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700' }}">
                            {{ ucfirst($row->lunas ?? 'Aktif') }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-10 text-center text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-2 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Belum ada data pinjaman aktif.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between text-sm text-gray-500">
        <span>Menampilkan {{ $listPinjaman->count() }} dari {{ $pinjamanAktif }} data</span>
        <a href="{{ route('admin.pinjaman.pinjaman-aktif') }}" class="text-[#674c1d] hover:underline font-medium text-xs">Lihat semua pinjaman →</a>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Trend Pinjaman
    new Chart(document.getElementById('pinjamanTrendChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Bunga Pinjaman',
                data: @json($trendData),
                backgroundColor: 'rgba(34,197,94,0.15)',
                borderColor: '#22c55e',
                borderWidth: 2,
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ' Rp ' + ctx.raw.toLocaleString('id-ID') } } },
            scales: {
                x: { grid: { display: false } },
                y: { grid: { color: 'rgba(0,0,0,0.04)' }, ticks: { callback: v => 'Rp ' + (v / 1000000).toFixed(0) + ' Jt' } }
            }
        }
    });

    // Durasi Doughnut
    new Chart(document.getElementById('durasiChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: @json($durasiLabels),
            datasets: [{ data: @json($durasiData), backgroundColor: ['#10b981', '#3b82f6', '#8b5cf6', '#f59e0b', '#ef4444', '#14b8a6', '#f43f5e', '#6366f1'], borderWidth: 0, hoverOffset: 6 }]
        },
        options: { responsive: true, cutout: '68%', plugins: { legend: { display: false } } }
    });
});
</script>
@endpush
