@extends('layouts.admin')

@section('title', 'Daftar Deposito Aktif')

@section('content')
<div class="p-6">
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('admin.deposito.index') }}" class="hover:text-[#674c1d]">Deposito</a>
        <span>/</span>
        <span class="text-gray-800 font-medium">Daftar Deposito</span>
    </div>
    <div class="flex items-center justify-between mb-5">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Daftar Deposito</h1>
            <p class="text-sm text-gray-500">Semua deposito nasabah</p>
        </div>
    </div>

    <div class="flex flex-wrap items-center justify-between mb-5">
        <form method="GET" class="flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / no. deposito..." class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#674c1d] w-64">
            <select name="status" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#674c1d]">
                <option value="">Semua Status</option>
                <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="dicairkan" {{ request('status') === 'dicairkan' ? 'selected' : '' }}>Dicairkan</option>
                <option value="ditutup" {{ request('status') === 'ditutup' ? 'selected' : '' }}>Ditutup</option>
            </select>
            <button type="submit" class="bg-[#674c1d] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-[#8b6f2f]">Filter</button>
        </form>

        <a href="{{ route('admin.deposito.export-pdf', request()->all()) }}" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-700 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Cetak Laporan PDF
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[800px]">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="text-left px-4 py-3 font-semibold">No. Deposito</th>
                    <th class="text-left px-4 py-3 font-semibold">Nasabah</th>
                    <th class="text-left px-4 py-3 font-semibold">Nominal</th>
                    <th class="text-left px-4 py-3 font-semibold">Tenor</th>
                    <th class="text-left px-4 py-3 font-semibold">Bunga</th>
                    <th class="text-left px-4 py-3 font-semibold">Jatuh Tempo</th>
                    <th class="text-left px-4 py-3 font-semibold">Status</th>
                    <th class="text-left px-4 py-3 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($depositos as $d)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono text-xs text-gray-700">{{ $d->nomor_deposito }}</td>
                    <td class="px-4 py-3">
                        <p class="font-semibold text-gray-800">{{ $d->nasabah->user->nama ?? '-' }}</p>
                    </td>
                    <td class="px-4 py-3 font-bold text-gray-800">Rp {{ number_format($d->nominal_awal, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $d->tenor->tenor_bulan ?? '-' }} bulan</td>
                    <td class="px-4 py-3 text-[#674c1d] font-semibold">{{ number_format($d->bunga * 100, 2) }}% p.a.</td>
                    <td class="px-4 py-3 text-gray-500 text-xs">{{ $d->tgl_jatuh_tempo ? $d->tgl_jatuh_tempo->format('d M Y') : '-' }}</td>
                    <td class="px-4 py-3">
                        @if($d->status === 'aktif')
                            <span class="bg-green-50 text-green-700 px-2 py-0.5 rounded-full text-xs font-semibold">Aktif</span>
                        @elseif($d->status === 'dicairkan')
                            <span class="bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full text-xs font-semibold">Dicairkan</span>
                        @else
                            <span class="bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full text-xs font-semibold">Ditutup</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.deposito.deposito-detail', $d->id) }}" class="text-[#674c1d] hover:underline text-xs font-semibold">Detail →</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-10 text-gray-400">Tidak ada data deposito</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
        <div class="px-5 py-3 border-t border-gray-100">{{ $depositos->links() }}</div>
    </div>
</div>
@endsection
