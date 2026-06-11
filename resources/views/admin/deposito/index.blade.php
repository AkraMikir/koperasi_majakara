@extends('layouts.admin')

@section('title', 'Dashboard Deposito')

@section('content')
<div class="p-6">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard Deposito</h1>
            <p class="text-sm text-gray-500">Kelola pengajuan dan deposito aktif nasabah</p>
        </div>
        <a href="{{ route('admin.deposito.pengajuan-list') }}" class="inline-flex items-center gap-2 bg-[#674c1d] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-[#8b6f2f] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            Semua Pengajuan
        </a>
    </div>

    {{-- Stats cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <p class="text-xs text-gray-500 mb-1">Pengajuan Pending</p>
            <p class="text-2xl font-bold text-amber-600">{{ $stats['pengajuan_pending'] }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $stats['pending_transfer'] }} transfer · {{ $stats['pending_tabungan'] }} tabungan</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <p class="text-xs text-gray-500 mb-1">Jatuh Tempo (Bulan Ini)</p>
            <p class="text-2xl font-bold text-blue-600">{{ $stats['jatuh_tempo_bulan_ini'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <p class="text-xs text-gray-500 mb-1">Bunga Dibayar (Bulan Ini)</p>
            <p class="text-2xl font-bold text-green-600">Rp {{ number_format($stats['bunga_dibayar_bulan_ini'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <p class="text-xs text-gray-500 mb-1">Deposito Aktif</p>
            <p class="text-2xl font-bold text-[#674c1d]">{{ $stats['total_deposito_aktif'] }}</p>
            <p class="text-xs text-gray-400 mt-1">Rp {{ number_format($stats['total_nominal_aktif'], 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Chart Row --}}
    <div class="grid grid-cols-1 mb-6">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h2 class="font-bold text-gray-800 text-sm mb-4">Tren Pertumbuhan Deposito (6 Bulan Terakhir)</h2>
            <div class="h-64">
                <canvas id="depositoTrendChart"></canvas>
            </div>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        {{-- Pengajuan Pending --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-800 text-sm">Pengajuan Pending</h2>
                <a href="{{ route('admin.deposito.pengajuan-list') }}" class="text-xs text-[#674c1d] font-semibold hover:underline">Lihat semua →</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($pengajuan_terbaru as $p)
                <div class="flex items-center gap-3 px-5 py-3 hover:bg-[#674c1d]/5 transition">
                    <div class="w-9 h-9 rounded-full bg-amber-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <a href="{{ route('admin.deposito.detail-pengajuan', $p->id) }}" class="flex-1 min-w-0 group">
                        <p class="font-semibold text-gray-800 text-sm truncate group-hover:text-[#674c1d]">{{ $p->nasabah->user->nama ?? '-' }}</p>
                        <p class="text-xs text-gray-500">Rp {{ number_format($p->nominal, 0, ',', '.') }} · {{ $p->tenor->tenor_bulan ?? '-' }} bulan</p>
                    </a>
                    <div class="flex flex-col gap-1 items-end">
                        <span class="text-[10px] px-2 py-0.5 rounded-full {{ $p->metode_setor === 'transfer' ? 'bg-blue-50 text-blue-600' : 'bg-green-50 text-green-700' }}">
                            {{ $p->metode_setor === 'transfer' ? 'Transfer' : 'Tabungan' }}
                        </span>
                        <div class="flex items-center gap-1 mt-1">
                            <form action="{{ route('admin.deposito.approve', $p->id) }}" method="POST" class="inline flex items-center gap-1" onsubmit="return confirm('Setujui pengajuan ini?')">
                                @csrf
                                @if($p->metode_setor === 'transfer')
                                <select name="metode_bayar" class="text-[10px] py-0.5 px-1 border-gray-200 rounded">
                                    <option value="transfer_koperasi">Transfer Koperasi</option>
                                    <option value="transfer_admin">Petty Cash (TF)</option>
                                    <option value="cash">Petty Cash (Tunai)</option>
                                </select>
                                @endif
                                <button type="submit" class="p-1 rounded-md text-green-600 hover:bg-green-100" title="Setujui">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </button>
                            </form>
                            <form action="{{ route('admin.deposito.reject', $p->id) }}" method="POST" class="inline" onsubmit="return confirm('Tolak pengajuan ini?')">
                                @csrf
                                <input type="hidden" name="catatan_admin" value="Ditolak via Dashboard">
                                <button type="submit" class="p-1 rounded-md text-red-600 hover:bg-red-100" title="Tolak">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-5 py-8 text-center text-gray-400 text-sm">Tidak ada pengajuan pending</div>
                @endforelse
            </div>
        </div>

        {{-- Deposito Aktif Terbaru --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-800 text-sm">Deposito Aktif Terbaru</h2>
                <a href="{{ route('admin.deposito.deposito-list') }}" class="text-xs text-[#674c1d] font-semibold hover:underline">Lihat semua →</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($deposito_terbaru as $d)
                <a href="{{ route('admin.deposito.deposito-detail', $d->id) }}" class="flex items-center gap-3 px-5 py-3 hover:bg-[#674c1d]/5 transition">
                    <div class="w-9 h-9 rounded-full bg-green-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800 text-sm truncate">{{ $d->nasabah->user->nama ?? '-' }}</p>
                        <p class="text-xs text-gray-500">{{ $d->nomor_deposito }} · Rp {{ number_format($d->nominal_awal, 0, ',', '.') }}</p>
                    </div>
                    <p class="text-xs text-gray-400">{{ $d->tgl_jatuh_tempo ? $d->tgl_jatuh_tempo->format('d M Y') : '-' }}</p>
                </a>
                @empty
                <div class="px-5 py-8 text-center text-gray-400 text-sm">Belum ada deposito aktif</div>
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
        const ctx = document.getElementById('depositoTrendChart').getContext('2d');
        const trendLabels = {!! json_encode($trend_labels) !!};
        const trendData = {!! json_encode($trend_data) !!};
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: trendLabels,
                datasets: [{
                    label: 'Nominal Deposito Baru (Rp)',
                    data: trendData,
                    borderColor: '#674c1d',
                    backgroundColor: 'rgba(103, 76, 29, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#674c1d',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let value = context.raw;
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value, index, values) {
                                if (value >= 1000000) {
                                    return 'Rp ' + (value / 1000000) + ' Jt';
                                }
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
