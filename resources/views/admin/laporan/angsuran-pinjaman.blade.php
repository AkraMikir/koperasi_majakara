@extends('layouts.admin')
@section('title', 'Laporan Angsuran Pinjaman')
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Laporan Angsuran Pinjaman</h1>
            <p class="text-gray-600 mt-1">Realisasi pembayaran angsuran per periode</p>
        </div>
        <a href="{{ route('admin.laporan.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-sm font-medium">← Daftar Laporan</a>
    </div>
    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <form method="GET" action="{{ route('admin.laporan.angsuran-pinjaman') }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Dari</label>
                <input type="date" name="tgl_dari" value="{{ $tgl_dari ?? now()->startOfMonth()->format('Y-m-d') }}" class="px-4 py-2 border border-gray-300 rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Sampai</label>
                <input type="date" name="tgl_sampai" value="{{ $tgl_sampai ?? now()->format('Y-m-d') }}" class="px-4 py-2 border border-gray-300 rounded-lg">
            </div>
            <button type="submit" class="px-4 py-2 bg-[#674c1d] text-white rounded-lg font-medium">Tampilkan</button>
        </form>
    </div>
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-900">Periode: {{ $tgl_dari }} s/d {{ $tgl_sampai }}</h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.laporan.angsuran-pinjaman', ['tgl_dari' => $tgl_dari, 'tgl_sampai' => $tgl_sampai, 'export' => 'pdf']) }}" class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-sm font-medium">Export PDF</a>
                <a href="{{ route('admin.laporan.angsuran-pinjaman', ['tgl_dari' => $tgl_dari, 'tgl_sampai' => $tgl_sampai, 'export' => 'excel']) }}" class="px-3 py-1.5 bg-green-100 text-green-700 rounded-lg text-sm font-medium">Export Excel</a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-[#674c1d]/20 bg-[#674c1d]/5">
                        <th class="px-6 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Tgl Bayar</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Pinjaman</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Nasabah</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-[#674c1d] uppercase">Pokok</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-[#674c1d] uppercase">Denda</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-[#674c1d] uppercase">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows ?? [] as $i => $r)
                    <tr class="border-b border-gray-100">
                        <td class="px-6 py-3 text-sm">{{ $i + 1 }}</td>
                        <td class="px-6 py-3 text-sm">{{ $r->tgl_bayar ? $r->tgl_bayar->format('d/m/Y') : '-' }}</td>
                        <td class="px-6 py-3 text-sm font-mono">{{ $r->pinjaman ? $r->pinjaman->id : '-' }}</td>
                        <td class="px-6 py-3 text-sm">{{ $r->pinjaman && $r->pinjaman->nasabah && $r->pinjaman->nasabah->user ? $r->pinjaman->nasabah->user->nama : '-' }}</td>
                        <td class="px-6 py-3 text-sm text-right">Rp {{ number_format($r->pokok ?? 0, 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-sm text-right">Rp {{ number_format($r->denda ?? 0, 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-sm text-right">Rp {{ number_format($r->total ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-8 text-center text-gray-500">Tidak ada angsuran pada periode ini.</td></tr>
                    @endforelse
                </tbody>
                @if(isset($rows) && $rows->isNotEmpty())
                <tfoot>
                    <tr class="border-t-2 border-gray-200 bg-gray-50 font-bold">
                        <td colspan="4" class="px-6 py-3">TOTAL</td>
                        <td class="px-6 py-3 text-right">Rp {{ number_format($total_pokok ?? 0, 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-right">Rp {{ number_format($total_denda ?? 0, 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-right">Rp {{ number_format($total_jumlah ?? 0, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection
