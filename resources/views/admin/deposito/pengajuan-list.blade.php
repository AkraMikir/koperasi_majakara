@extends('layouts.admin')

@section('title', 'Daftar Pengajuan Deposito')

@section('content')
<div class="p-6">
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('admin.deposito.index') }}" class="hover:text-[#674c1d]">Deposito</a>
        <span>/</span>
        <span class="text-gray-800 font-medium">Pengajuan</span>
    </div>
    <div class="flex items-center justify-between mb-5">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Pengajuan Deposito</h1>
            <p class="text-sm text-gray-500">Kelola semua pengajuan pembukaan deposito nasabah</p>
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" class="flex flex-wrap gap-3 mb-5">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama nasabah..." class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#674c1d] w-64">
        <select name="status" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#674c1d]">
            <option value="1" {{ request('status','1') === '1' ? 'selected' : '' }}>Pending</option>
            <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>Disetujui</option>
            <option value="3" {{ request('status') === '3' ? 'selected' : '' }}>Ditolak</option>
            <option value="" {{ request('status') === '' ? 'selected' : '' }}>Semua</option>
        </select>
        <select name="metode" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#674c1d]">
            <option value="">Semua Metode</option>
            <option value="transfer" {{ request('metode') === 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
            <option value="saldo_tabungan" {{ request('metode') === 'saldo_tabungan' ? 'selected' : '' }}>Saldo Tabungan</option>
        </select>
        <button type="submit" class="bg-[#674c1d] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-[#8b6f2f]">Filter</button>
    </form>

    @if(session('success'))
    <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="text-left px-4 py-3 font-semibold">Nasabah</th>
                    <th class="text-left px-4 py-3 font-semibold">Nominal</th>
                    <th class="text-left px-4 py-3 font-semibold">Tenor</th>
                    <th class="text-left px-4 py-3 font-semibold">Metode</th>
                    <th class="text-left px-4 py-3 font-semibold">Tanggal</th>
                    <th class="text-left px-4 py-3 font-semibold">Status</th>
                    <th class="text-left px-4 py-3 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($pengajuan as $p)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <p class="font-semibold text-gray-800">{{ $p->nasabah->user->nama ?? '-' }}</p>
                        <p class="text-xs text-gray-400">{{ $p->nasabah->user->email ?? '-' }}</p>
                    </td>
                    <td class="px-4 py-3 font-bold text-gray-800">Rp {{ number_format($p->nominal, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $p->tenor->tenor_bulan ?? '-' }} bulan</td>
                    <td class="px-4 py-3">
                        @if($p->metode_setor === 'transfer')
                            <span class="bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full text-xs font-semibold">Transfer Bank</span>
                        @else
                            <span class="bg-green-50 text-green-700 px-2 py-0.5 rounded-full text-xs font-semibold">Saldo Tabungan</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs">{{ $p->created_at->format('d M Y H:i') }}</td>
                    <td class="px-4 py-3">
                        @if($p->status === '1')
                            <span class="bg-amber-50 text-amber-600 px-2 py-0.5 rounded-full text-xs font-semibold">Pending</span>
                        @elseif($p->status === '2')
                            <span class="bg-green-50 text-green-700 px-2 py-0.5 rounded-full text-xs font-semibold">Disetujui</span>
                        @else
                            <span class="bg-red-50 text-red-600 px-2 py-0.5 rounded-full text-xs font-semibold">Ditolak</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.deposito.detail-pengajuan', $p->id) }}" class="text-[#674c1d] hover:underline text-xs font-semibold">Detail →</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-10 text-gray-400">Tidak ada data pengajuan</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-3 border-t border-gray-100">{{ $pengajuan->links() }}</div>
    </div>
</div>
@endsection
