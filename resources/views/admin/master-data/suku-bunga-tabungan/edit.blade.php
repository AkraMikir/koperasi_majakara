@extends('layouts.admin')

@section('title', 'Edit Suku Bunga Tabungan')

@section('content')
<div class="space-y-6">
    <div>
        <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
            <a href="{{ route('admin.master-data.index') }}">Master Data</a>
            <span>/</span>
            <a href="{{ route('admin.master-data.suku-bunga-tabungan.index') }}">Suku Bunga Tabungan</a>
            <span>/</span>
            <span class="text-gray-900 font-medium">Edit</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 font-display">Edit Suku Bunga Tabungan</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <form action="{{ route('admin.master-data.suku-bunga-tabungan.update', $data->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Bunga *</label>
                <input type="text" name="jenis_bunga" value="{{ old('jenis_bunga', $data->jenis_bunga) }}" required
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#4a3514] focus:ring-2 focus:ring-[#4a3514]/20 outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nilai Bunga (%) *</label>
                <div class="relative">
                    <input type="number" name="opsi_val" value="{{ old('opsi_val', $data->opsi_val) }}" required min="0" max="100" step="0.0001"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#4a3514] focus:ring-2 focus:ring-[#4a3514]/20 outline-none">
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500">%</span>
                </div>
            </div>

            <div class="flex items-center space-x-3 pt-4 border-t">
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-[#4a3514] to-[#674c1d] text-white rounded-xl hover:from-[#674c1d] hover:to-[#8b6f2f] transition-all font-medium shadow-md">
                    Update Data
                </button>
                <a href="{{ route('admin.master-data.suku-bunga-tabungan.index') }}" class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all font-medium">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
