@extends('layouts.admin')

@section('title', 'Tambah Master Denda Pinjaman')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('admin.master-data.index') }}" class="hover:text-gray-900">Master Data</a>
                <span>/</span>
                <a href="{{ route('admin.master-data.denda-pinjaman.index') }}" class="hover:text-gray-900">Master Denda Pinjaman</a>
                <span>/</span>
                <span class="text-gray-900 font-medium">Tambah Data</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Tambah Master Denda Pinjaman</h1>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <form action="{{ route('admin.master-data.denda-pinjaman.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Persentase Denda per Hari (%) *</label>
                <div class="relative">
                    <input type="number" name="denda_persen" value="{{ old('denda_persen', 0.30) }}" required min="0" max="100" step="0.01"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 outline-none">
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">%</span>
                </div>
                <p class="text-xs text-gray-500 mt-1">Contoh: 0.30 untuk 0,3% per hari</p>
                @error('denda_persen')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="flex items-center space-x-3">
                    <input type="hidden" name="status_aktif" value="0">
                    <input type="checkbox" name="status_aktif" value="1" {{ old('status_aktif', true) ? 'checked' : '' }}
                        class="w-5 h-5 text-[#8b6f2f] border-gray-300 rounded focus:ring-2 focus:ring-[#8b6f2f]/20">
                    <span class="text-sm font-medium text-gray-700">Status Aktif</span>
                </label>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan</label>
                <textarea name="keterangan" rows="3"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 outline-none">{{ old('keterangan') }}</textarea>
            </div>

            <div class="flex items-center space-x-3 pt-4 border-t border-gray-200">
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-[#8b6f2f] to-[#d4af37] text-white rounded-xl hover:from-[#674c1d] hover:to-[#8b6f2f] transition-all font-medium shadow-md">
                    Simpan Data
                </button>
                <a href="{{ route('admin.master-data.denda-pinjaman.index') }}" class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all font-medium">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
