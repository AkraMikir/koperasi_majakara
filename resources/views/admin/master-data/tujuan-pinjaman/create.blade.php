@extends('layouts.admin')

@section('title', 'Tambah Master Tujuan Pinjaman')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('admin.master-data.index') }}" class="hover:text-gray-900">Master Data</a>
                <span>/</span>
                <a href="{{ route('admin.master-data.tujuan-pinjaman.index') }}" class="hover:text-gray-900">Master Tujuan Pinjaman</a>
                <span>/</span>
                <span class="text-gray-900 font-medium">Tambah Data</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Tambah Master Tujuan Pinjaman</h1>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <form action="{{ route('admin.master-data.tujuan-pinjaman.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Tujuan -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tujuan Pinjaman *</label>
                <input type="text" name="tujuan" value="{{ old('tujuan') }}" required placeholder="Contoh: Modal Usaha, Renovasi Rumah"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none transition-all">
                @error('tujuan')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status Aktif -->
            <div>
                <label class="flex items-center space-x-3">
                    <input type="hidden" name="status" value="0">
                    <input type="checkbox" name="status" value="1" {{ old('status', true) ? 'checked' : '' }}
                        class="w-5 h-5 text-[#674c1d] border-gray-300 rounded focus:ring-2 focus:ring-[#674c1d]/20">
                    <span class="text-sm font-medium text-gray-700">Status Aktif</span>
                </label>
            </div>

            <!-- Keterangan -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan</label>
                <textarea name="keterangan" rows="3" placeholder="Tambahkan keterangan jika diperlukan..."
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
                <a href="{{ route('admin.master-data.tujuan-pinjaman.index') }}" class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all font-medium">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
