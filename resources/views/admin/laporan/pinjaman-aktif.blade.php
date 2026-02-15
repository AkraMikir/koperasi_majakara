@extends('layouts.admin')

@section('title', 'Laporan Pinjaman Aktif')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Pinjaman Aktif (Outstanding)</h1>
            <p class="text-gray-600 mt-1">Daftar pinjaman belum lunas dan sisa pokok</p>
        </div>
        <a href="{{ route('admin.laporan.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-sm font-medium">← Daftar Laporan</a>
    </div>

    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-900">Total Outstanding: Rp {{ number_format($total_outstanding ?? 0, 0, ',', '.') }}</h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.laporan.pinjaman-aktif', ['export' => 'pdf']) }}" class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-sm font-medium hover:bg-red-200">Export PDF</a>
                <a href="{{ route('admin.laporan.pinjaman-aktif', ['export' => 'excel']) }}" class="px-3 py-1.5 bg-green-100 text-green-700 rounded-lg text-sm font-medium hover:bg-green-200">Export Excel</a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-[#674c1d]/20 bg-[#674c1d]/5">
                        <th class="px-6 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">ID Pinjaman</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Nasabah</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Tgl Pinjam</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-[#674c1d] uppercase">Nominal</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-[#674c1d] uppercase">Terbayar</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-[#674c1d] uppercase">Sisa Pokok</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-[#674c1d] uppercase">Sisa Angsuran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows ?? [] as $i => $r)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="px-6 py-3 text-sm">{{ $i + 1 }}</td>
                        <td class="px-6 py-3 text-sm font-mono">{{ $r->pinjaman->id }}</td>
                        <td class="px-6 py-3 text-sm">{{ $r->pinjaman->nasabah?->user?->nama ?? '-' }}</td>
                        <td class="px-6 py-3 text-sm">{{ $r->pinjaman->tgl_pinjam?->format('d/m/Y') }}</td>
                        <td class="px-6 py-3 text-sm text-right">Rp {{ number_format($r->pinjaman->jumlah_pinjam, 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-sm text-right">Rp {{ number_format($r->total_terbayar, 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-sm text-right font-semibold">Rp {{ number_format($r->sisa_pokok, 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-sm text-center">{{ $r->sisa_angsuran }}x</td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="px-6 py-8 text-center text-gray-500">Tidak ada pinjaman aktif.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
