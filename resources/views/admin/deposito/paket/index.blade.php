@extends('layouts.admin')

@section('title', 'Paket Deposito')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl border border-gray-100 shadow-xs">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 font-display">Paket Deposito</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola master data paket deposito yang tersedia untuk nasabah.</p>
        </div>
        <div>
            <a href="{{ route('admin.deposito.paket.create') }}"
               class="inline-flex items-center px-4 py-2.5 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white font-medium rounded-xl hover:shadow-lg hover:shadow-[#674c1d]/20 transition-all duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Paket
            </a>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center">
            <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Table Section -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Nama Paket</th>
                        <th class="px-6 py-4">Tenor</th>
                        <th class="px-6 py-4">Suku Bunga</th>
                        <th class="px-6 py-4">Minimal Nominal</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($paket as $item)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900 flex items-center gap-2">
                                    {{ $item->nama_paket }}
                                    @if($item->kategori)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#d4af37]/20 text-[#674c1d]">🏷️ {{ $item->kategori->nama_kategori }}</span>
                                    @endif
                                </div>
                                @if($item->keterangan)
                                    <div class="text-xs text-gray-500 mt-0.5 truncate max-w-xs">{{ $item->keterangan }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">{{ $item->tenor_bulan }} Bulan</td>
                            <td class="px-6 py-4 font-medium text-[#674c1d]">{{ rtrim(rtrim($item->suku_bunga, '0'), '.') }}%</td>
                            <td class="px-6 py-4">Rp {{ number_format($item->minimal_nominal, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                @if($item->status == 'aktif')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-3">
                                    <a href="{{ route('admin.deposito.paket.edit', $item->id) }}" class="text-[#8b6f2f] hover:text-[#674c1d] transition-colors" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    @if($item->status == 'aktif')
                                    <form action="{{ route('admin.deposito.paket.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan paket ini?');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 transition-colors" title="Nonaktifkan">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                    <p>Belum ada paket deposito.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
