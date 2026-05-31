@extends('layouts.admin')

@section('title', 'Peringatan Jatuh Tempo Deposito')

@section('content')
<div class="p-6">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Peringatan Jatuh Tempo Deposito</h1>
            <p class="text-sm text-gray-500">Daftar deposito yang akan jatuh tempo dalam 7 hari ke depan — siapkan dana sebelum tenggat.</p>
        </div>
        <a href="{{ route('admin.deposito.index') }}" class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-200 transition">
            ← Kembali
        </a>
    </div>

    {{-- Alert --}}
    @if($stats['jatuh_tempo_hari_ini'] > 0)
    <div class="mb-4 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm font-medium">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
        <span><strong>{{ $stats['jatuh_tempo_hari_ini'] }} deposito</strong> jatuh tempo <strong>HARI INI</strong> — segera proses pencairan!</span>
    </div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <p class="text-xs text-gray-500 mb-1">Total Persiapan</p>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['total_persiapan'] }}</p>
            <p class="text-xs text-gray-400 mt-1">deposito</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 col-span-2 md:col-span-1">
            <p class="text-xs text-gray-500 mb-1">Total Dana Dibutuhkan</p>
            <p class="text-xl font-bold text-[#674c1d]">Rp {{ number_format($stats['total_dana'], 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">semua metode</p>
        </div>
        <div class="bg-orange-50 rounded-xl border border-orange-100 shadow-sm p-4">
            <p class="text-xs text-orange-600 mb-1">Butuh Transfer Bank</p>
            <p class="text-xl font-bold text-orange-700">Rp {{ number_format($stats['butuh_transfer'], 0, ',', '.') }}</p>
            <p class="text-xs text-orange-400 mt-1">ke rekening nasabah</p>
        </div>
        <div class="bg-purple-50 rounded-xl border border-purple-100 shadow-sm p-4">
            <p class="text-xs text-purple-600 mb-1">Butuh Petty Cash</p>
            <p class="text-xl font-bold text-purple-700">Rp {{ number_format($stats['butuh_petty_cash'], 0, ',', '.') }}</p>
            <p class="text-xs text-purple-400 mt-1">tunai via Admin</p>
        </div>
        <div class="bg-green-50 rounded-xl border border-green-100 shadow-sm p-4">
            <p class="text-xs text-green-600 mb-1">Ke Tabungan</p>
            <p class="text-xl font-bold text-green-700">Rp {{ number_format($stats['ke_tabungan'], 0, ',', '.') }}</p>
            <p class="text-xs text-green-400 mt-1">otomatis digital</p>
        </div>
    </div>

    {{-- Summary Per Hari --}}
    @if($summary->isNotEmpty())
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm mb-6">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="font-bold text-gray-800 text-sm">Ringkasan Dana per Tanggal Jatuh Tempo</h2>
        </div>
        <div class="p-4 overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                        <th class="text-left px-3 py-2 rounded-l">Tanggal JT</th>
                        <th class="text-right px-3 py-2">Ke Tabungan</th>
                        <th class="text-right px-3 py-2">Via Transfer</th>
                        <th class="text-right px-3 py-2">Via Petty Cash</th>
                        <th class="text-right px-3 py-2 rounded-r">Total Dana</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($summary as $tanggal => $items)
                    @php
                        $totalHari = $items->sum('total_dana');
                        $tabunganHari = $items->where('metode_cair', 'saldo_tabungan')->sum('total_dana');
                        $tfHari = $items->where('metode_cair', 'rek_nasabah')->sum('total_dana');
                        $pcHari = $items->where('metode_cair', 'petty_cash_operator')->sum('total_dana');
                        $isToday = \Carbon\Carbon::parse($tanggal)->isToday();
                        $isPast = \Carbon\Carbon::parse($tanggal)->isPast() && !$isToday;
                    @endphp
                    <tr class="{{ $isToday ? 'bg-red-50' : ($isPast ? 'bg-gray-50' : '') }} hover:bg-amber-50 transition">
                        <td class="px-3 py-3 font-semibold {{ $isToday ? 'text-red-700' : 'text-gray-800' }}">
                            {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d M Y') }}
                            @if($isToday)<span class="ml-1 text-xs bg-red-100 text-red-600 px-1.5 py-0.5 rounded-full">Hari ini</span>@endif
                            @if($isPast)<span class="ml-1 text-xs bg-gray-200 text-gray-500 px-1.5 py-0.5 rounded-full">Lewat</span>@endif
                        </td>
                        <td class="px-3 py-3 text-right text-green-700">{{ $tabunganHari > 0 ? 'Rp '.number_format($tabunganHari, 0, ',', '.') : '-' }}</td>
                        <td class="px-3 py-3 text-right text-orange-700">{{ $tfHari > 0 ? 'Rp '.number_format($tfHari, 0, ',', '.') : '-' }}</td>
                        <td class="px-3 py-3 text-right text-purple-700">{{ $pcHari > 0 ? 'Rp '.number_format($pcHari, 0, ',', '.') : '-' }}</td>
                        <td class="px-3 py-3 text-right font-bold text-gray-900">Rp {{ number_format($totalHari, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Filter --}}
    <form method="GET" action="{{ route('admin.deposito.peringatan.index') }}" class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Metode Cair</label>
                <select name="metode_cair" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                    <option value="">Semua Metode</option>
                    <option value="saldo_tabungan" {{ request('metode_cair') === 'saldo_tabungan' ? 'selected' : '' }}>Saldo Tabungan</option>
                    <option value="rek_nasabah" {{ request('metode_cair') === 'rek_nasabah' ? 'selected' : '' }}>Rekening Nasabah (TF)</option>
                    <option value="petty_cash_operator" {{ request('metode_cair') === 'petty_cash_operator' ? 'selected' : '' }}>Petty Cash Operator (Tunai)</option>
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Dari Tanggal JT</label>
                <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Sampai Tanggal JT</label>
                <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-[#674c1d] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-[#8b6f2f] transition">Filter</button>
                <a href="{{ route('admin.deposito.peringatan.index') }}" class="px-4 py-2 border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition">Reset</a>
            </div>
        </div>
    </form>

    {{-- Tabel Persiapan Cair --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-bold text-gray-800 text-sm">Daftar Deposito yang Perlu Disiapkan</h2>
            <span class="text-xs text-gray-400">{{ $persiapan->total() }} deposito</span>
        </div>

        @if($persiapan->isEmpty())
        <div class="text-center py-16">
            <p class="text-gray-500 font-medium">Tidak ada deposito yang perlu disiapkan saat ini.</p>
            <p class="text-sm text-gray-400 mt-1">Sistem akan otomatis menambahkan peringatan 7 hari sebelum jatuh tempo.</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide border-b border-gray-100">
                        <th class="text-left px-4 py-3">Nasabah</th>
                        <th class="text-left px-4 py-3">No. Deposito</th>
                        <th class="text-right px-4 py-3">Pokok</th>
                        <th class="text-right px-4 py-3">Bunga Bersih</th>
                        <th class="text-right px-4 py-3">Total Dibayar</th>
                        <th class="text-center px-4 py-3">Metode Cair</th>
                        <th class="text-center px-4 py-3">Status</th>
                        <th class="text-center px-4 py-3">Jatuh Tempo</th>
                        <th class="text-center px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($persiapan as $item)
                    @php
                        $jt = \Carbon\Carbon::parse($item->tgl_target_cair);
                        $isToday = $jt->isToday();
                        $isPast  = $jt->isPast() && !$isToday;
                        $daysLeft = now()->diffInDays($jt, false);
                    @endphp
                    <tr class="{{ $isToday ? 'bg-red-50' : ($isPast ? 'bg-gray-50' : 'hover:bg-amber-50') }} transition">
                        <td class="px-4 py-3">
                            <p class="font-semibold text-gray-800">{{ $item->nasabah->user->nama ?? '-' }}</p>
                            <p class="text-xs text-gray-400">{{ $item->nasabah->no_nasabah ?? '-' }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.deposito.deposito-detail', $item->deposito_id) }}" class="font-mono text-xs text-[#674c1d] hover:underline">
                                {{ $item->deposito->nomor_deposito ?? '-' }}
                            </a>
                            <p class="text-xs text-gray-400">{{ $item->deposito->tenor->tenor_bulan ?? '-' }} bln</p>
                        </td>
                        <td class="px-4 py-3 text-right text-gray-700">Rp {{ number_format($item->pokok, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right text-green-700">
                            Rp {{ number_format($item->bunga_bersih, 0, ',', '.') }}
                            <p class="text-xs text-gray-400">pajak: Rp {{ number_format($item->pajak, 0, ',', '.') }}</p>
                        </td>
                        <td class="px-4 py-3 text-right font-bold text-gray-900">Rp {{ number_format($item->total_dibayar, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($item->metode_cair === 'saldo_tabungan')
                                <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">Tabungan</span>
                            @elseif($item->metode_cair === 'rek_nasabah')
                                <span class="text-xs bg-orange-100 text-orange-700 px-2 py-1 rounded-full">Transfer Bank</span>
                            @else
                                <span class="text-xs bg-purple-100 text-purple-700 px-2 py-1 rounded-full">Petty Cash</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($item->status === 'tentatif')
                                <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full">Tentatif</span>
                            @elseif($item->status === 'diproses')
                                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">Diproses</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <p class="font-semibold {{ $isToday ? 'text-red-600' : ($isPast ? 'text-gray-400' : 'text-gray-700') }}">
                                {{ $jt->format('d M Y') }}
                            </p>
                            @if($isToday)
                                <span class="text-xs text-red-500 font-bold">HARI INI</span>
                            @elseif($isPast)
                                <span class="text-xs text-gray-400">{{ abs($daysLeft) }} hari lalu</span>
                            @else
                                <span class="text-xs text-gray-400">{{ $daysLeft }} hari lagi</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($item->pencairan_id)
                                <span class="text-xs text-gray-400">Pengajuan ada</span>
                            @else
                                <a href="{{ route('admin.deposito.deposito-detail', $item->deposito_id) }}"
                                   class="text-xs bg-[#674c1d] text-white px-3 py-1.5 rounded-lg hover:bg-[#8b6f2f] transition">
                                    Detail
                                </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($persiapan->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $persiapan->links() }}
        </div>
        @endif
        @endif
    </div>
</div>
@endsection
