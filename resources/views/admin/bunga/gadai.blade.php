@extends('layouts.admin')

@section('title', 'Biaya Gadai - Koperasi Majakara')

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
                    <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard Biaya Gadai</h1>
                </div>
                <p class="text-sm text-gray-500 mt-1 ml-10">Analisis pendapatan dari biaya jasa & biaya inap gadai</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-amber-50 border border-amber-200 text-xs font-semibold text-amber-700">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                Data Live
            </span>
            <span class="text-sm text-gray-500">{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</span>
        </div>
    </div>
</div>

{{-- Summary Banner --}}
<div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#a07840] to-[#674c1d] p-6 mb-6 shadow-xl text-white">
    <div class="absolute -right-8 -top-8 w-48 h-48 bg-white/5 rounded-full"></div>
    <div class="absolute -right-4 bottom-0 w-64 h-32 bg-white/5 rounded-full"></div>
    <div class="relative z-10 grid grid-cols-1 md:grid-cols-3 gap-6 divide-y md:divide-y-0 md:divide-x divide-white/10">
        <div class="space-y-1">
            <p class="text-amber-200 text-[10px] uppercase font-semibold tracking-wider">Proyeksi Portofolio Aktif</p>
            <p class="text-2xl font-bold">Rp {{ number_format($totalPendapatanProyeksi, 0, ',', '.') }}</p>
            <p class="text-amber-300 text-xs">Jasa Rp {{ number_format($biayaJasa, 0, ',', '.') }} + Inap Rp {{ number_format($biayaInap, 0, ',', '.') }}</p>
        </div>
        <div class="space-y-1 pt-4 md:pt-0 md:pl-6">
            <p class="text-amber-200 text-[10px] uppercase font-semibold tracking-wider">Realisasi Bunga Murni (Tebus)</p>
            <p class="text-2xl font-bold text-teal-300">Rp {{ number_format($realisasiBungaMurni, 0, ',', '.') }}</p>
            <p class="text-amber-300 text-xs">Selisih nominal tebus &minus; nilai deal</p>
        </div>
        <div class="space-y-1 pt-4 md:pt-0 md:pl-6">
            <p class="text-amber-200 text-[10px] uppercase font-semibold tracking-wider">Realisasi Perpanjang/Inap</p>
            <p class="text-2xl font-bold text-orange-300">Rp {{ number_format($realisasiAdminInap, 0, ',', '.') }}</p>
            <p class="text-amber-300 text-xs">Total payment perpanjang bulan ini</p>
        </div>
    </div>
</div>

{{-- Info Banner --}}
<div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-6 flex items-start gap-3">
    <svg class="w-5 h-5 text-amber-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <div>
        <p class="text-sm font-semibold text-amber-700">Catatan: Pemisahan Komponen Pendapatan Gadai</p>
        <p class="text-xs text-amber-600 mt-0.5">Pendapatan realisasi gadai dipisah menjadi dua komponen: <strong>Bunga Murni Tebus</strong> (selisih nominal tebus &minus; pokok/nilai deal) dan <strong>Biaya Perpanjang/Inap</strong> (biaya perpanjangan masa gadai & penitipan barang). Proyeksi (<strong>Biaya Jasa + Biaya Inap</strong>) dihitung dari akumulasi gadai aktif.</p>
    </div>
</div>

{{-- KPI Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
    {{-- Bunga Murni Tebus --}}
    <div class="bg-white rounded-2xl shadow-sm border border-teal-100 p-5 ring-1 ring-teal-100">
        <div class="flex items-start justify-between gap-2 mb-3">
            <div class="w-10 h-10 rounded-xl bg-teal-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-[10px] font-semibold text-teal-700 bg-teal-50 px-2 py-0.5 rounded-full whitespace-nowrap">Bunga Murni</span>
        </div>
        <p class="text-2xl font-bold text-teal-700">Rp {{ number_format($realisasiBungaMurni, 0, ',', '.') }}</p>
        <p class="text-xs text-gray-400 mt-2">Selisih tebus &minus; pokok, bulan ini</p>
    </div>

    {{-- Biaya Perpanjang/Inap (Admin) --}}
    <div class="bg-white rounded-2xl shadow-sm border border-orange-100 p-5 ring-1 ring-orange-100">
        <div class="flex items-start justify-between gap-2 mb-3">
            <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <span class="text-[10px] font-semibold text-orange-700 bg-orange-50 px-2 py-0.5 rounded-full whitespace-nowrap">Perpanjang/Inap</span>
        </div>
        <p class="text-2xl font-bold text-orange-600">Rp {{ number_format($realisasiAdminInap, 0, ',', '.') }}</p>
        <p class="text-xs text-gray-400 mt-2">Biaya perpanjangan & inap, bulan ini</p>
    </div>

    {{-- Proyeksi Biaya Jasa --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <span class="text-xs text-gray-500 font-medium">Proyeksi Biaya Jasa</span>
        </div>
        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($biayaJasa, 0, ',', '.') }}</p>
        <p class="text-xs text-gray-400 mt-2">{{ $totalPendapatanProyeksi > 0 ? round(($biayaJasa / $totalPendapatanProyeksi) * 100) : 0 }}% dari total proyeksi aktif</p>
    </div>

    {{-- Gadai Aktif --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <span class="text-xs text-gray-500 font-medium">Gadai Aktif</span>
        </div>
        <p class="text-2xl font-bold text-gray-900">{{ number_format($gadaiAktif, 0, ',', '.') }}</p>
        <p class="text-xs text-gray-400 mt-2">Total kontrak berjalan saat ini</p>
    </div>
</div>

{{-- Charts --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
    {{-- Trend Gadai --}}
    <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="font-semibold text-gray-900">Tren Pendapatan Biaya Gadai</h3>
                <p class="text-xs text-gray-400 mt-0.5">Proyeksi Biaya Jasa vs Biaya Inap per bulan (6 bulan terakhir)</p>
            </div>
        </div>
        <canvas id="gadaiTrendChart" height="130"></canvas>
    </div>

    {{-- Per Kategori --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-semibold text-gray-900 mb-1">Distribusi per Kategori</h3>
        <p class="text-xs text-gray-400 mb-5">Porsi gadai aktif berdasarkan jenis barang</p>
        <canvas id="kategoriGadaiChart" height="180"></canvas>
        <div class="mt-4 space-y-2 text-xs">
            @php
                $colors = ['#f59e0b', '#3b82f6', '#ec4899', '#8b5cf6', '#10b981', '#f97316'];
                $total = array_sum($katData);
            @endphp
            @foreach($katLabels as $idx => $label)
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm inline-block" style="background:{{ $colors[$idx % count($colors)] }}"></span>{{ $label }}</div>
                <span class="font-semibold text-gray-700">{{ $total > 0 ? round(($katData[$idx]/$total)*100) : 0 }}%</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Tabel Gadai Aktif --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-5">
        <div>
            <h3 class="font-semibold text-gray-900">Daftar Gadai Aktif</h3>
            <p class="text-xs text-gray-400 mt-0.5">Diurutkan berdasarkan total biaya terbesar</p>
        </div>
        <a href="{{ route('admin.gadai_baru.index') }}" class="text-xs text-[#674c1d] hover:underline font-medium">Lihat semua gadai →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="pb-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nasabah</th>
                    <th class="pb-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Barang Gadai</th>
                    <th class="pb-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Nilai Deal</th>
                    <th class="pb-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Biaya Jasa</th>
                    <th class="pb-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Biaya Inap</th>
                    <th class="pb-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Biaya</th>
                    <th class="pb-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($listGadai as $row)
                @php
                    $namaUser = optional(optional($row->nasabah)->user)->nama ?? 'Unknown';
                    $namaBarang = optional($row->item)->head_1 ?? 'Unknown';
                    $statusMap = [
                        'active'        => ['label' => 'Aktif',          'class' => 'bg-green-50 text-green-700'],
                        'extended'      => ['label' => 'Diperpanjang',   'class' => 'bg-blue-50 text-blue-700'],
                        'expired_grace' => ['label' => 'Masa Tenggang',  'class' => 'bg-yellow-50 text-yellow-700'],
                        'expired_final' => ['label' => 'Lewat Tempo',    'class' => 'bg-red-50 text-red-700'],
                    ];
                    $st = $statusMap[$row->status] ?? ['label' => ucfirst($row->status ?? '-'), 'class' => 'bg-gray-50 text-gray-600'];
                @endphp
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-3.5">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                {{ strtoupper(substr($namaUser, 0, 1)) }}
                            </div>
                            <span class="font-medium text-gray-900">{{ $namaUser }}</span>
                        </div>
                    </td>
                    <td class="py-3.5 text-gray-600 text-xs max-w-[150px]">
                        <span class="truncate block" title="{{ $namaBarang }}">{{ $namaBarang }}</span>
                        @if(optional($row->item)->head_2)
                            <span class="text-gray-400 block truncate text-[10px]">{{ $row->item->head_2 }}</span>
                        @endif
                    </td>
                    <td class="py-3.5 text-right text-gray-700 font-medium">Rp {{ number_format($row->nominal_deal ?? 0, 0, ',', '.') }}</td>
                    <td class="py-3.5 text-right text-teal-600 font-semibold">Rp {{ number_format($row->biaya_jasa ?? 0, 0, ',', '.') }}</td>
                    <td class="py-3.5 text-right text-orange-500">
                        @if(($row->biaya_inap ?? 0) > 0)
                            Rp {{ number_format($row->biaya_inap, 0, ',', '.') }}
                        @else
                            <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="py-3.5 text-right font-bold text-amber-700">Rp {{ number_format($row->sim_total ?? 0, 0, ',', '.') }}</td>
                    <td class="py-3.5 text-center">
                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $st['class'] }}">
                            {{ $st['label'] }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-10 text-center text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-2 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        Belum ada data gadai aktif.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between text-sm text-gray-500">
        <span>Menampilkan {{ $listGadai->count() }} dari {{ $gadaiAktif }} data</span>
    </div>
</div>
@endsection

@push('scripts')
@once
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endonce
<script>
document.addEventListener('DOMContentLoaded', function () {
    new Chart(document.getElementById('gadaiTrendChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [
                {
                    label: 'Biaya Jasa',
                    data: @json($jasaData),
                    backgroundColor: 'rgba(20,184,166,0.75)',
                    borderColor: '#14b8a6',
                    borderWidth: 1.5,
                    borderRadius: 8,
                    borderSkipped: false,
                },
                {
                    label: 'Biaya Inap',
                    data: @json($inapData),
                    backgroundColor: 'rgba(249,115,22,0.75)',
                    borderColor: '#f97316',
                    borderWidth: 1.5,
                    borderRadius: 8,
                    borderSkipped: false,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, padding: 14, font: { size: 12 } } },
                tooltip: { callbacks: { label: ctx => ' Rp ' + ctx.raw.toLocaleString('id-ID') } }
            },
            scales: {
                x: { grid: { display: false }, stacked: false },
                y: { grid: { color: 'rgba(0,0,0,0.04)' }, ticks: { callback: v => 'Rp ' + (v / 1000000).toFixed(1) + ' Jt' } }
            }
        }
    });

    new Chart(document.getElementById('kategoriGadaiChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: @json($katLabels),
            datasets: [{ data: @json($katData), backgroundColor: ['#f59e0b', '#3b82f6', '#ec4899', '#8b5cf6', '#10b981', '#f97316'], borderWidth: 0, hoverOffset: 8 }]
        },
        options: { responsive: true, cutout: '70%', plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ctx.label + ': ' + ctx.raw + ' unit' } } } }
    });
});
</script>
@endpush
