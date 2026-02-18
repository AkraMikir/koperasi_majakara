@extends('layouts.admin')

@section('title', 'Suku Bunga Deposito')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('admin.master-data.index') }}">Master Data</a>
                <span>/</span>
                <span class="text-gray-900 font-medium">Suku Bunga Deposito</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Suku Bunga Deposito</h1>
            <p class="text-gray-600 mt-1">Kelola bunga deposito berdasarkan tenor dan nominal</p>
        </div>
        @canCrudMasterData
        <a href="{{ route('admin.master-data.suku-bunga-deposito.create') }}" class="px-4 py-2 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-md text-sm font-medium">
            + Tambah Data
        </a>
        @endcanCrudMasterData
    </div>

    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
        <p class="text-green-700 font-medium">{{ session('success') }}</p>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-md border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Tenor</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Range Nominal</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Bunga (%)</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($data as $index => $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $data->firstItem() + $index }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            {{ $item->tenor ? $item->tenor->tenor_bulan . ' bulan' : '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            Rp {{ number_format($item->min_nominal, 0, ',', '.') }} - Rp {{ number_format($item->max_nominal, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4"><span class="text-sm font-bold text-[#d4af37]">{{ $item->bunga }}%</span></td>
                        <td class="px-6 py-4">
                            @canCrudMasterData
                            <form action="{{ route('admin.master-data.suku-bunga-deposito.toggle-status', $item->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-1 rounded-full text-xs font-medium {{ $item->status ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $item->status ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </form>
                            @else
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $item->status ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $item->status ? 'Aktif' : 'Nonaktif' }}
                            </span>
                            @endcanCrudMasterData
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center space-x-2">
                                @canCrudMasterData
                                <a href="{{ route('admin.master-data.suku-bunga-deposito.edit', $item->id) }}" class="p-2 text-[#674c1d] hover:bg-[#674c1d]/10 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.master-data.suku-bunga-deposito.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                                @else
                                <span class="text-xs text-gray-400">View Only</span>
                                @endcanCrudMasterData
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">Belum ada data</td>
                    </tr>
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
