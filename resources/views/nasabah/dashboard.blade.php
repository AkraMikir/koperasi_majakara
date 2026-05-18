@extends('layouts.nasabah')

@section('title', 'Dashboard')

@section('content')
    <div class="w-full pb-20 px-2 max-w-full mx-auto animate-fade-in">
        <!-- Dashboard Top Section -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 mt-0.5 mb-4">
            
            <!-- Asset Distribution Chart -->
            <div class="lg:col-span-8 bg-white rounded-[1.5rem] p-4 lg:p-6 shadow-sm border border-gray-100 flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-black text-gray-900 leading-none">Distribusi Aset</h2>
                        <p class="text-[9px] text-gray-400 mt-1 uppercase tracking-widest font-bold">Portofolio Keuangan</p>
                    </div>
                    <div class="px-2.5 py-1 bg-majakara-gold/5 rounded-full text-[8px] font-black text-majakara-dark-gold uppercase tracking-widest border border-majakara-gold/10">Terverifikasi</div>
                </div>
                
                <div class="flex-1 flex flex-col md:flex-row items-center justify-center gap-8 lg:gap-12">
                    <div class="relative w-56 h-56 lg:w-72 lg:h-72 flex-shrink-0 animate-scale-in">
                        <canvas id="assetChart"></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                            <p class="text-[11px] font-black text-gray-400 uppercase tracking-[0.25em] mb-1">Total Aset</p>
                            <h4 class="text-2xl lg:text-3xl font-black text-gray-900 tracking-tight">100%</h4>
                        </div>
                    </div>
                    <div class="flex-1 w-full max-w-md space-y-2 lg:space-y-3">
                        @php
                            $chartItems = [
                                ['label' => 'Tabungan', 'value' => $stats['saldo_tabungan'], 'color' => '#674c1d', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                                ['label' => 'Deposito', 'value' => $stats['total_deposito'], 'color' => '#d4af37', 'icon' => 'M2 19l10-10 4 4 6-6m0 0v6m0-6h6'],
                                ['label' => 'Pinjaman', 'value' => $stats['total_pinjaman'], 'color' => '#8b6f2f', 'icon' => 'M2 10a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4zm0 0V6a2 2 0 012-2h14a2 2 0 012 2v4M6 12h.01M10 12h.01M14 12h.01M18 12h.01'],
                                ['label' => 'Gadai', 'value' => $stats['total_gadai'], 'color' => '#a67c52', 'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'],
                            ];
                            $totalVal = array_sum(array_column($chartItems, 'value'));
                        @endphp
                        @foreach($chartItems as $item)
                            @php
                                $percent = $totalVal > 0 ? ($item['value'] / $totalVal) * 100 : 0;
                            @endphp
                            <div class="group cursor-default p-2.5 rounded-2xl hover:bg-gray-50 transition-all duration-300 border border-transparent hover:border-gray-100">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-transform group-hover:scale-110" style="background-color: {{ $item['color'] }}20">
                                            <svg class="w-4 h-4" style="color: {{ $item['color'] }}" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="block text-[11px] font-black text-gray-500 uppercase tracking-wider">{{ $item['label'] }}</span>
                                            <span class="text-[10px] font-bold text-gray-400">{{ number_format($percent, 1) }}% Distribusi</span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="block text-sm font-black text-gray-900">Rp {{ number_format($item['value'], 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                <div class="w-full bg-gray-100 h-1.5 rounded-full overflow-hidden shadow-inner">
                                    <div class="h-full rounded-full transition-all duration-1000 ease-out group-hover:brightness-110" style="width: {{ $percent }}%; background-color: {{ $item['color'] }}"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Akses Cepat (Root2Root) -->
            <div class="lg:col-span-4 bg-white rounded-[1.5rem] p-4 lg:p-6 shadow-sm border border-gray-100 flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-black text-gray-900 leading-none">Akses Cepat</h2>
                        <p class="text-[9px] text-gray-400 mt-1 uppercase tracking-widest font-bold">Menu Navigasi</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2 flex-1">
                @php
                    $quickAccess = [
                        ['route' => 'nasabah.tabungan.nabung-sekarang', 'label' => 'Nabung', 'sub' => 'Sekarang', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['route' => 'nasabah.tabungan.penarikan', 'label' => 'Tarik', 'sub' => 'Tabungan', 'icon' => 'M15 13l-3 3m0 0l-3-3m3 3V8m0 13a9 9 0 110-18 9 9 0 010 18z'],
                        ['route' => 'nasabah.pinjaman.pembayaran', 'label' => 'Bayar', 'sub' => 'Pinjaman', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                        ['route' => 'nasabah.pinjaman.angsuran', 'params' => ['jenis' => 'bulanan'], 'label' => 'Angsuran', 'sub' => 'Bulanan', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['route' => 'nasabah.deposito.pengajuan', 'label' => 'Buka', 'sub' => 'Deposito', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                        ['route' => 'nasabah.gadai_baru.index', 'label' => 'Gadai', 'sub' => 'Barang', 'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'],
                        ['route' => 'nasabah.pengajuan-pending', 'label' => 'Status', 'sub' => 'Pengajuan', 'icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['route' => 'nasabah.pinjaman.pinjaman-aktif', 'label' => 'Pinjaman', 'sub' => 'Aktif', 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                    ];
                @endphp

                @foreach($quickAccess as $item)
                <a href="{{ route($item['route'], $item['params'] ?? []) }}" class="bg-white p-3 rounded-[1.15rem] border border-gray-100 shadow-sm hover:shadow-md hover:border-majakara-gold/40 transition-all group relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-majakara-gold/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="flex flex-col items-center text-center gap-1.5 relative z-10">
                        <div class="w-9 h-9 bg-majakara-brown/5 text-majakara-brown rounded-xl flex items-center justify-center group-hover:bg-gradient-to-br group-hover:from-majakara-brown group-hover:to-majakara-dark-gold group-hover:text-white transition-all duration-300 shadow-sm">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-900 leading-tight">{{ $item['label'] }}</p>
                            <p class="text-[7px] font-bold text-gray-400 uppercase tracking-wider mt-0.5">{{ $item['sub'] }}</p>
                        </div>
                    </div>
                </a>
                @endforeach
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white rounded-[1.5rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-black text-gray-900">Transaksi Terakhir</h2>
                    <p class="text-[10px] text-gray-500 mt-0.5 uppercase tracking-wider font-bold">Aktivitas Sistem</p>
                </div>
                <a href="{{ route('nasabah.tabungan.index') }}" class="px-6 py-2.5 bg-gradient-to-r from-majakara-brown to-majakara-dark-gold text-white text-[10px] font-black rounded-xl hover:shadow-lg transition-all uppercase tracking-[0.1em] text-center">Lihat Semua</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Data Waktu</th>
                            <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Jenis Transaksi</th>
                            <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Metode</th>
                            <th class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Nominal</th>
                            <th class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($transaksiTerbaru ?? [] as $transaksi)
                            @php
                                $isSetoran = optional($transaksi->jnsTransaksi)->kode === 'STR';
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition-all duration-200 group">
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <div class="text-sm font-black text-gray-900 group-hover:text-majakara-dark-gold transition-colors">{{ $transaksi->tgl_transaksi->format('d M Y') }}</div>
                                    <div class="text-[10px] text-gray-400 font-mono font-bold mt-0.5">{{ $transaksi->tgl_transaksi->format('H:i:s') }}</div>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ $isSetoran ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }}">
                                            @if($isSetoran)
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
                                            @else
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path></svg>
                                            @endif
                                        </div>
                                        <div>
                                            <span class="text-sm font-bold text-gray-800">{{ optional($transaksi->jnsTransaksi)->nama ?? 'Transaksi' }}</span>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">{{ $isSetoran ? 'Kredit Masuk' : 'Debit Keluar' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <span class="px-3 py-1.5 bg-gray-100 rounded-xl text-[10px] font-black text-gray-500 uppercase tracking-wider group-hover:bg-white group-hover:shadow-sm transition-all border border-transparent group-hover:border-gray-100">{{ optional($transaksi->jnsVia)->nama ?? 'Manual' }}</span>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap text-right">
                                    <span class="text-base font-black {{ $isSetoran ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $isSetoran ? '+' : '-' }} Rp {{ number_format($transaksi->nominal, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap text-right">
                                    <a href="{{ route('nasabah.tabungan.detail-transaksi', $transaksi->id) }}" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-gray-50 text-gray-400 group-hover:bg-majakara-dark-gold group-hover:text-white transition-all transform group-hover:rotate-45">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-300 mb-4">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                        </div>
                                        <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">Tidak Ada Data</h3>
                                        <p class="text-[10px] text-gray-400 font-bold mt-1">Belum ada transaksi yang tercatat di sistem.</p>
                                    </div>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('assetChart').getContext('2d');
            
            // Get data from PHP
            const chartData = @json($stats['chart_data']['data']);
            const hasData = chartData.some(val => val > 0);
            
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Tabungan', 'Deposito', 'Pinjaman', 'Gadai'],
                    datasets: [{
                        data: hasData ? chartData : [1],
                        backgroundColor: hasData ? [
                            '#674c1d', // Tabungan (Brown)
                            '#d4af37', // Deposito (Gold)
                            '#8b6f2f', // Pinjaman (Darker Gold)
                            '#a67c52'  // Gadai (Light Brown)
                        ] : ['#f3f4f6'],
                        borderWidth: 0,
                        hoverOffset: 10,
                        borderRadius: 10
                    }]
                },
                options: {
                    cutout: '75%',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: hasData,
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.formattedValue + '%';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush

