@extends('layouts.admin')

@section('title', 'Edit Kategori Deposito')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center space-x-4 bg-white p-6 rounded-2xl border border-gray-100 shadow-xs">
        <a href="{{ route('admin.master-data.kategori-deposito.index') }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-xl transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 font-display">Edit Kategori Deposito</h1>
            <p class="text-sm text-gray-500 mt-1">Perbarui master data kategori.</p>
        </div>
    </div>

    <!-- Form Section -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-xs overflow-hidden">
        <form action="{{ route('admin.master-data.kategori-deposito.update', $kategori->id) }}" method="POST" class="p-6 md:p-8 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Kategori -->
                <div class="col-span-1 md:col-span-2">
                    <label for="nama_kategori" class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_kategori" id="nama_kategori" required
                        value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
                        class="w-full rounded-xl border-gray-300 focus:border-[#8b6f2f] focus:ring focus:ring-[#8b6f2f]/20 transition-all placeholder-gray-400">
                    @error('nama_kategori')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="col-span-1 md:col-span-2">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="status" id="status" required
                            class="w-full rounded-xl border-gray-300 focus:border-[#8b6f2f] focus:ring focus:ring-[#8b6f2f]/20 transition-all appearance-none pr-10">
                            <option value="aktif" {{ old('status', $kategori->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status', $kategori->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    @error('status')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Keterangan -->
                <div class="col-span-1 md:col-span-2">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan (Opsional)</label>
                    <textarea name="keterangan" id="keterangan" rows="3"
                        class="w-full rounded-xl border-gray-300 focus:border-[#8b6f2f] focus:ring focus:ring-[#8b6f2f]/20 transition-all placeholder-gray-400">{{ old('keterangan', $kategori->keterangan) }}</textarea>
                    @error('keterangan')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('admin.master-data.kategori-deposito.index') }}" class="px-5 py-2.5 bg-gray-50 text-gray-700 font-medium rounded-xl border border-gray-200 hover:bg-gray-100 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white font-medium rounded-xl hover:shadow-lg hover:shadow-[#674c1d]/20 transition-all duration-300">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
