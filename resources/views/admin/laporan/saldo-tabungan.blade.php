@extends('layouts.admin')

@section('title', 'Laporan Saldo Tabungan')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Saldo Tabungan</h1>
            <p class="text-gray-600 mt-1">Saldo per nasabah pada tanggal cutoff</p>
        </div>
        <a href="{{ route('admin.laporan.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-sm font-medium">← Daftar Laporan</a>
    </div>

    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <form method="GET" action="{{ route('admin.laporan.saldo-tabungan') }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Cutoff</label>
                <input type="date" name="tgl_cutoff" value="{{ $tgl_cutoff ?? now()->format('Y-m-d') }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d]">
            </div>
            <button type="submit" class="px-4 py-2 bg-[#674c1d] text-white rounded-lg hover:bg-[#4a3514] font-medium">Tampilkan</button>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-900">Per {{ $tgl_cutoff }} · Total Saldo: Rp {{ number_format($total_saldo ?? 0, 0, ',', '.') }}</h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.laporan.saldo-tabungan', array_merge(request()->query(), ['export' => 'pdf'])) }}" class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-sm font-medium hover:bg-red-200">Export PDF</a>
                <a href="{{ route('admin.laporan.saldo-tabungan', array_merge(request()->query(), ['export' => 'excel'])) }}" class="px-3 py-1.5 bg-green-100 text-green-700 rounded-lg text-sm font-medium hover:bg-green-200">Export Excel</a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-[#674c1d]/20 bg-[#674c1d]/5">
                        <th class="px-6 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Nasabah</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-[#674c1d] uppercase">Total Setor</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-[#674c1d] uppercase">Total Tarik</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-[#674c1d] uppercase">Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($per_nasabah ?? [] as $i => $r)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="px-6 py-3 text-sm">{{ $i + 1 }}</td>
                        <td class="px-6 py-3 text-sm">{{ $r->nasabah && $r->nasabah->user ? $r->nasabah->user->nama : '-' }}</td>
                        <td class="px-6 py-3 text-sm text-right">Rp {{ number_format($r->total_setor ?? 0, 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-sm text-right">Rp {{ number_format($r->total_tarik ?? 0, 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-sm text-right font-semibold">Rp {{ number_format($r->saldo ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">Tidak ada data saldo.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
