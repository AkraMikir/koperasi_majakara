@extends('layouts.admin')

@section('title', 'Master Inap Kendaraan')

@section('content')
<div class="space-y-6">
    {{-- Breadcrumb and Title --}}
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('admin.master-data.index') }}" class="hover:text-gray-900">Master Data</a>
                <span>/</span>
                <span class="text-gray-900 font-medium">Master Inap Kendaraan</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Master Inap Kendaraan</h1>
            <p class="text-gray-600 mt-1">Kelola golongan dan tarif biaya inap kendaraan (gadai)</p>
        </div>
        @canCrudMasterData
        <a href="{{ route('admin.master-data.inap-kendaraan.create') }}" class="px-4 py-2.5 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-md text-sm font-bold flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Golongan
        </a>
        @endcanCrudMasterData
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-100 p-4 rounded-xl flex items-center gap-3 text-emerald-800 animate-fade-in">
        <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
        </svg>
        <span class="text-sm font-bold">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Data Table --}}
    <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">No</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Golongan</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Volume</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Biaya Inap</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Contoh Item</th>
                        @canCrudMasterData
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Aksi</th>
                        @endcanCrudMasterData
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($data as $index => $item)
                    <tr class="hover:bg-gray-50/30 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-500 text-center">{{ $data->firstItem() + $index }}</td>
                        <td class="px-6 py-4 font-black text-gray-900">
                            <span class="px-2.5 py-1 bg-amber-50 text-amber-700 text-xs font-black rounded-lg border border-amber-100 uppercase">
                                Golongan {{ $item->golongan }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-800 capitalize">{{ $item->jenis_kendaraan }}</td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-sm font-black text-gray-900">Rp {{ number_format($item->nominal_inap, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-500 italic">{{ $item->contoh_item }}</td>
                        @canCrudMasterData
                        <td class="px-6 py-4">
                            <div class="flex justify-center items-center gap-2">
                                <a href="{{ route('admin.master-data.inap-kendaraan.edit', $item->id) }}" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('admin.master-data.inap-kendaraan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                        @endcanCrudMasterData
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-300">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                </div>
                                <p class="text-gray-400 font-bold tracking-tight">Belum ada data inap kendaraan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($data->hasPages())
        <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100">
            {{ $data->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
