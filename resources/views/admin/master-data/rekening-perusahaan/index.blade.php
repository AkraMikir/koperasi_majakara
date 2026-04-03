@extends('layouts.admin')
@section('title', 'Master Rekening Perusahaan')
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('admin.master-data.index') }}">Master Data</a>
                <span>/</span>
                <span class="text-gray-900 font-medium">Rekening Perusahaan</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Master Rekening Perusahaan</h1>
            <p class="text-gray-600 mt-1">Kelola rekening bank perusahaan</p>
        </div>
        @canCrudMasterData
        <a href="{{ route('admin.master-data.rekening-perusahaan.create') }}" class="px-4 py-2 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg shadow-md text-sm font-medium">+ Tambah Data</a>
        @endcanCrudMasterData
    </div>
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg"><p class="text-green-700 font-medium">{{ session('success') }}</p></div>
    @endif
    <div class="bg-white rounded-2xl shadow-md border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Bank</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Pemilik</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">No. Rekening</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Cabang / Kode</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($data as $index => $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $data->firstItem() + $index }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                @if($item->logo_url)
                                <img src="{{ $item->logo_url }}" alt="{{ $item->bank }}" class="w-8 h-8 object-contain">
                                @endif
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs font-bold">{{ $item->bank }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $item->pemilik }}</div>
                            <div class="text-xs text-gray-500">{{ $item->nama }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm font-mono font-bold text-[#674c1d]">{{ $item->no_rek }}</td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $item->cabang ?? '-' }}</div>
                            <div class="text-xs text-gray-500">Kode: {{ $item->kode_bank ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if(($item->status ?? 'aktif') == 'aktif')
                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">AKTIF</span>
                            @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-bold">NON-AKTIF</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center space-x-2">
                                @canCrudMasterData
                                <a href="{{ route('admin.master-data.rekening-perusahaan.edit', $item->id) }}" class="p-2 text-[#674c1d] hover:bg-[#674c1d]/10 rounded-lg">Edit</a>
                                <form action="{{ route('admin.master-data.rekening-perusahaan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg">Hapus</button>
                                </form>
                                @else
                                <span class="text-xs text-gray-400">View Only</span>
                                @endcanCrudMasterData
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">Belum ada data rekening.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($data->hasPages())
        <div class="px-6 py-4 border-t">{{ $data->links() }}</div>
        @endif
    </div>
</div>
@endsection
