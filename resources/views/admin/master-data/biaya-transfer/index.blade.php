@extends('layouts.admin')

@section('title', 'Master Biaya Transfer')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('admin.master-data.index') }}" class="hover:text-gray-900">Master Data</a>
                <span>/</span>
                <span class="text-gray-900 font-medium">Biaya Transfer</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Master Biaya Transfer</h1>
            <p class="text-gray-600 mt-1">Kelola biaya admin transfer antar bank</p>
        </div>
        @canCrudBiayaTransfer
        <a href="{{ route('admin.master-data.biaya-transfer.create') }}" class="px-4 py-2 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-md text-sm font-medium">
            + Tambah Data
        </a>
        @endcanCrudBiayaTransfer
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
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Bank Pengirim</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Bank Penerima</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Biaya Admin</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Min Saldo Non-BCA</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Keterangan</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($data as $index => $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $data->firstItem() + $index }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-sm font-semibold">{{ $item->bank_pengirim }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-sm font-semibold">{{ $item->bank_penerima }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold {{ $item->biaya_admin > 0 ? 'text-red-600' : 'text-green-600' }}">
                                Rp {{ number_format($item->biaya_admin, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-semibold text-gray-800">
                                Rp {{ number_format($item->min_saldo_non_bca ?? 0, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $item->keterangan ?? '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            @canCrudBiayaTransfer
                            <form action="{{ route('admin.master-data.biaya-transfer.toggle-status', $item->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-1 {{ $item->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }} rounded-full text-xs font-semibold">
                                    {{ $item->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </button>
                            </form>
                            @else
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $item->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $item->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                            @endcanCrudBiayaTransfer
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center space-x-2">
                                @canCrudBiayaTransfer
                                <a href="{{ route('admin.master-data.biaya-transfer.edit', $item->id) }}" class="p-2 text-[#674c1d] hover:bg-[#674c1d]/10 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.master-data.biaya-transfer.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                                @else
                                <span class="text-xs text-gray-400">View Only</span>
                                @endcanCrudBiayaTransfer
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                <p class="text-gray-500 text-lg font-medium">Belum ada data</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($data->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $data->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
