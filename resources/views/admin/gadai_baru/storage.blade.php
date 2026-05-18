@extends('layouts.admin')

@section('title', 'Penyimpanan Gadai')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 font-display">Penyimpanan Gadai</h1>
            <p class="text-gray-500 text-sm mt-1">Lihat status ketersediaan grid penyimpanan (Slot Storage)</p>
        </div>
        <div>
            <form action="{{ route('admin.gadai_baru.storage') }}" method="GET" class="flex gap-2">
                <select name="kategori" class="border-gray-200 rounded-xl focus:ring-[#674c1d] focus:border-[#674c1d]" onchange="this.form.submit()">
                    <option value="electronic" {{ $kategori == 'electronic' ? 'selected' : '' }}>Elektronik</option>
                    <option value="vehicle" {{ $kategori == 'vehicle' ? 'selected' : '' }}>Kendaraan</option>
                    <option value="gold" {{ $kategori == 'gold' ? 'selected' : '' }}>Emas</option>
                </select>
            </form>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 overflow-x-auto">
        <div class="min-w-max space-y-4">
            @forelse($groupedGrid as $baris => $koloms)
                <div class="flex gap-4 items-center">
                    <div class="w-16 font-bold text-gray-500 text-sm text-right">Baris {{ $baris }}</div>
                    <div class="flex gap-4">
                        @foreach($koloms as $slot)
                            @if($slot->is_occupied)
                                <div class="w-32 h-32 bg-red-50 border-2 border-red-200 rounded-xl p-3 flex flex-col justify-between shadow-sm cursor-help" title="{{ $slot->nasabah_nama }} - {{ $slot->item_nama }}">
                                    <div class="text-xs font-bold text-red-800">{{ $slot->kode_slot }}</div>
                                    <div class="text-[10px] text-red-600 font-medium truncate">{{ $slot->item_nama }}</div>
                                    <div class="text-[10px] text-red-500 truncate">{{ $slot->nasabah_nama }}</div>
                                    <div class="mt-1 flex items-center justify-center">
                                        <span class="px-2 py-0.5 bg-red-100 text-red-700 text-[10px] rounded-full font-bold">Terisi</span>
                                    </div>
                                </div>
                            @else
                                <div class="w-32 h-32 bg-emerald-50 border-2 border-emerald-200 border-dashed rounded-xl p-3 flex flex-col justify-between hover:bg-emerald-100 transition-colors">
                                    <div class="text-xs font-bold text-emerald-800">{{ $slot->kode_slot }}</div>
                                    <div class="flex-1 flex items-center justify-center">
                                        <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 text-xs rounded-full font-bold">Kosong</span>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-center py-12 text-gray-500">
                    Tidak ada grid penyimpanan untuk kategori ini.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
