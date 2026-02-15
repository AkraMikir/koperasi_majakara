@extends('layouts.admin')
@section('title', 'Laporan Jatuh Tempo')
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Laporan Jatuh Tempo</h1>
            <p class="text-gray-600 mt-1">Angsuran jatuh tempo dalam periode</p>
        </div>
        <a href="{{ route('admin.laporan.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-sm font-medium">← Daftar Laporan</a>
    </div>
    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <form method="GET" action="{{ route('admin.laporan.jatuh-tempo') }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                <input type="month" name="bulan" value="{{ $bulan ?? now()->format('Y-m') }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d]">
            </div>
            <button type="submit" class="px-4 py-2 bg-[#674c1d] text-white rounded-lg hover:bg-[#4a3514] font-medium">Tampilkan</button>
        </form>
    </div>
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-900">Bulan {{ $bulan }}</h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.laporan.jatuh-tempo', ['bulan' => $bulan, 'export' => 'pdf']) }}" class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-sm font-medium">Export PDF</a>
                <a href="{{ route('admin.laporan.jatuh-tempo', ['bulan' => $bulan, 'export' => 'excel']) }}" class="px-3 py-1.5 bg-green-100 text-green-700 rounded-lg text-sm font-medium">Export Excel</a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-[#674c1d]/20 bg-[#674c1d]/5">
                        <th class="px-6 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Tgl Jatuh Tempo</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Pinjaman</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Nasabah</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-[#674c1d] uppercase">Tagihan</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-[#674c1d] uppercase">Terbayar</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-[#674c1d] uppercase">Sisa</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-[#674c1d] uppercase">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows ?? [] as $i => $r)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="px-6 py-3 text-sm">{{ $i + 1 }}</td>
                        <td class="px-6 py-3 text-sm">{{ $r->tempo && $r->tempo->tgl_jatuh_tempo ? $r->tempo->tgl_jatuh_tempo->format('d/m/Y') : '-' }}</td>
                        <td class="px-6 py-3 text-sm font-mono">{{ $r->pinjaman ? $r->pinjaman->id : '-' }}</td>
                        <td class="px-6 py-3 text-sm">{{ $r->pinjaman && $r->pinjaman->nasabah && $r->pinjaman->nasabah->user ? $r->pinjaman->nasabah->user->nama : '-' }}</td>
                        <td class="px-6 py-3 text-sm text-right">Rp {{ number_format($r->tagihan ?? 0, 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-sm text-right">Rp {{ number_format($r->terbayar ?? 0, 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-sm text-right font-medium">Rp {{ number_format($r->sisa ?? 0, 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-center text-sm">{{ $r->status_bayar ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="px-6 py-8 text-center text-gray-500">Tidak ada jatuh tempo pada periode ini.</td></tr>
                    @endforelse
                </tbody>
                @if(isset($rows) && $rows->isNotEmpty())
                <tfoot>
                    <tr class="border-t-2 border-gray-200 bg-gray-50 font-bold">
                        <td colspan="4" class="px-6 py-3">TOTAL</td>
                        <td class="px-6 py-3 text-right">Rp {{ number_format($total_tagihan ?? 0, 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-right">Rp {{ number_format($total_terbayar ?? 0, 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-right">Rp {{ number_format($total_sisa ?? 0, 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection
