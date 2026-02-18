@extends('layouts.admin')

@section('title', 'Edit Tenor Deposito')

@section('content')
<div class="space-y-6">
    <div>
        <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
            <a href="{{ route('admin.master-data.index') }}">Master Data</a>
            <span>/</span>
            <a href="{{ route('admin.master-data.tenor-deposito.index') }}">Tenor Deposito</a>
            <span>/</span>
            <span class="text-gray-900 font-medium">Edit</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 font-display">Edit Tenor Deposito</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <form action="{{ route('admin.master-data.tenor-deposito.update', $data->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tenor (Hari) *</label>
                    <input type="number" name="tenor_hari" value="{{ old('tenor_hari', $data->tenor_hari) }}" required min="1"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#d4af37] focus:ring-2 focus:ring-[#d4af37]/20 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tenor (Bulan) *</label>
                    <input type="number" name="tenor_bulan" value="{{ old('tenor_bulan', $data->tenor_bulan) }}" required min="1"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#d4af37] focus:ring-2 focus:ring-[#d4af37]/20 outline-none">
                </div>
            </div>

            <div>
                <label class="flex items-center space-x-3">
                    <input type="hidden" name="aktif" value="0">
                    <input type="checkbox" name="aktif" value="1" {{ old('aktif', $data->aktif) ? 'checked' : '' }}
                        class="w-5 h-5 text-[#d4af37] border-gray-300 rounded focus:ring-2 focus:ring-[#d4af37]/20">
                    <span class="text-sm font-medium text-gray-700">Status Aktif</span>
                </label>
            </div>

            <div class="flex items-center space-x-3 pt-4 border-t">
                <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#d4af37] to-[#8b6f2f] text-white rounded-xl hover:from-[#8b6f2f] hover:to-[#674c1d] transition-all font-medium shadow-md">
                    Update Data
                </button>
                <a href="{{ route('admin.master-data.tenor-deposito.index') }}" class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all font-medium">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
