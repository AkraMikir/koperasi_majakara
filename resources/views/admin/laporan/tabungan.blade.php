@extends('layouts.admin')
@section('title', 'Laporan Tabungan')
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Laporan Tabungan</h1>
            <p class="text-gray-600 mt-1">Mutasi transaksi setor dan penarikan per periode</p>
        </div>
        <a href="{{ route('admin.laporan.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-sm font-medium">← Daftar Laporan</a>
    </div>
    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <form method="GET" action="{{ route('admin.laporan.tabungan') }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Dari</label>
                <input type="date" name="tgl_dari" value="{{ $tgl_dari ?? now()->startOfMonth()->format('Y-m-d') }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d]">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Sampai</label>
                <input type="date" name="tgl_sampai" value="{{ $tgl_sampai ?? now()->format('Y-m-d') }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d]">
            </div>
            <button type="submit" class="px-4 py-2 bg-[#674c1d] text-white rounded-lg hover:bg-[#4a3514] font-medium">Tampilkan</button>
        </form>
    </div>
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-900">Periode: {{ $tgl_dari }} s/d {{ $tgl_sampai }}</h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.laporan.tabungan', ['tgl_dari' => $tgl_dari, 'tgl_sampai' => $tgl_sampai, 'export' => 'pdf']) }}" class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-sm font-medium">Export PDF</a>
                <a href="{{ route('admin.laporan.tabungan', ['tgl_dari' => $tgl_dari, 'tgl_sampai' => $tgl_sampai, 'export' => 'excel']) }}" class="px-3 py-1.5 bg-green-100 text-green-700 rounded-lg text-sm font-medium">Export Excel</a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-[#674c1d]/20 bg-[#674c1d]/5">
                        <th class="px-6 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Nasabah</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Jenis</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-[#674c1d] uppercase">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksi ?? [] as $i => $t)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="px-6 py-3 text-sm">{{ $i + 1 }}</td>
                        <td class="px-6 py-3 text-sm">{{ $t->tgl_transaksi ? $t->tgl_transaksi->format('d/m/Y H:i') : '-' }}</td>
                        <td class="px-6 py-3 text-sm">{{ $t->nasabah && $t->nasabah->user ? $t->nasabah->user->nama : '-' }}</td>
                        <td class="px-6 py-3 text-sm">{{ $t->jnsTransaksi ? $t->jnsTransaksi->nama : ($t->jenis ?? '-') }}</td>
                        <td class="px-6 py-3 text-sm text-right font-medium">Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">Tidak ada transaksi pada periode ini.</td></tr>
                    @endforelse
                </tbody>
                @if(isset($transaksi) && $transaksi->isNotEmpty())
                <tfoot>
                    <tr class="border-t-2 border-gray-200 bg-gray-50 font-semibold">
                        <td colspan="3" class="px-6 py-3">Total Setor</td>
                        <td class="px-6 py-3 text-right">Rp {{ number_format($total_setor ?? 0, 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                    <tr class="bg-gray-50 font-semibold">
                        <td colspan="3" class="px-6 py-3">Total Tarik</td>
                        <td class="px-6 py-3 text-right">Rp {{ number_format($total_tarik ?? 0, 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                    <tr class="bg-[#674c1d]/10 font-bold">
                        <td colspan="3" class="px-6 py-3">Net</td>
                        <td class="px-6 py-3 text-right">Rp {{ number_format($net ?? 0, 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection
