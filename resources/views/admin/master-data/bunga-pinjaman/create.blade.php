@extends('layouts.admin')

@section('title', 'Tambah Master Bunga Pinjaman')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('admin.master-data.index') }}" class="hover:text-gray-900">Master Data</a>
                <span>/</span>
                <a href="{{ route('admin.master-data.bunga-pinjaman.index') }}" class="hover:text-gray-900">Master Bunga Pinjaman</a>
                <span>/</span>
                <span class="text-gray-900 font-medium">Tambah Data</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Tambah Master Bunga Pinjaman</h1>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <form action="{{ route('admin.master-data.bunga-pinjaman.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Durasi Min -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Durasi Minimum (Bulan) *</label>
                    <input type="number" name="durasi_min" value="{{ old('durasi_min') }}" required min="1"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none transition-all">
                    @error('durasi_min')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Durasi Max -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Durasi Maksimum (Bulan) *</label>
                    <input type="number" name="durasi_max" value="{{ old('durasi_max') }}" required min="1"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none transition-all">
                    @error('durasi_max')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Bunga Persen -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Persentase Bunga (%) *</label>
                <div class="relative">
                    <input type="number" name="bunga_persen" value="{{ old('bunga_persen') }}" required min="0" max="100" step="0.01"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none transition-all">
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">%</span>
                </div>
                <p class="text-xs text-gray-500 mt-1">Contoh: 10 untuk 10%</p>
                @error('bunga_persen')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status Aktif -->
            <div>
                <label class="flex items-center space-x-3">
                    <input type="hidden" name="status_aktif" value="0">
                    <input type="checkbox" name="status_aktif" value="1" {{ old('status_aktif', true) ? 'checked' : '' }}
                        class="w-5 h-5 text-[#674c1d] border-gray-300 rounded focus:ring-2 focus:ring-[#674c1d]/20">
                    <span class="text-sm font-medium text-gray-700">Status Aktif</span>
                </label>
            </div>

            <!-- Keterangan -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan</label>
                <textarea name="keterangan" rows="3"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none transition-all">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex items-center space-x-3 pt-4 border-t border-gray-200">
                <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl hover:from-[#4a3514] hover:to-[#674c1d] transition-all font-medium shadow-md">
                    Simpan Data
                </button>
                <a href="{{ route('admin.master-data.bunga-pinjaman.index') }}" class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all font-medium">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
