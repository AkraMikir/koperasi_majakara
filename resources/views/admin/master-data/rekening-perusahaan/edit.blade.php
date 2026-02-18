@extends('layouts.admin')

@section('title', 'Edit Rekening Perusahaan')

@section('content')
<div class="space-y-6">
    <div>
        <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
            <a href="{{ route('admin.master-data.index') }}">Master Data</a>
            <span>/</span>
            <a href="{{ route('admin.master-data.rekening-perusahaan.index') }}">Rekening Perusahaan</a>
            <span>/</span>
            <span class="text-gray-900 font-medium">Edit</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 font-display">Edit Rekening Perusahaan</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <form action="{{ route('admin.master-data.rekening-perusahaan.update', $data->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Pemilik *</label>
                <input type="text" name="pemilik" value="{{ old('pemilik', $data->pemilik) }}" required
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 outline-none">
                @error('pemilik')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama (nama bank / rekening) *</label>
                <input type="text" name="nama" value="{{ old('nama', $data->nama) }}" required
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 outline-none">
                @error('nama')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">No. Rekening *</label>
                <input type="text" name="no_rek" value="{{ old('no_rek', $data->no_rek) }}" required
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 outline-none">
                @error('no_rek')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Bank (BCA, Mandiri, BNI, BRI, dll) *</label>
                <input type="text" name="bank" value="{{ old('bank', $data->bank) }}" required
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 outline-none">
                @error('bank')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center space-x-3 pt-4 border-t">
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-[#8b6f2f] to-[#d4af37] text-white rounded-xl hover:from-[#674c1d] hover:to-[#8b6f2f] transition-all font-medium shadow-md">
                    Update Data
                </button>
                <a href="{{ route('admin.master-data.rekening-perusahaan.index') }}" class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all font-medium">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
