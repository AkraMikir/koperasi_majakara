@extends('layouts.admin')

@section('title', 'Edit Jenis Akun')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('admin.master-data.index') }}" class="hover:text-gray-900">Master Data</a>
                <span>/</span>
                <a href="{{ route('admin.master-data.jns-akun.index') }}" class="hover:text-gray-900">Jenis Akun</a>
                <span>/</span>
                <span class="text-gray-900 font-medium">Edit</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Edit Jenis Akun</h1>
        </div>
        <a href="{{ route('admin.master-data.jns-akun.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium">
            ← Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <form method="POST" action="{{ route('admin.master-data.jns-akun.update', $data->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kode Akun *</label>
                    <input type="text" name="kode_akun" value="{{ old('kode_akun', $data->kode_akun) }}" required
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none font-mono">
                    @error('kode_akun')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Prefix ID *</label>
                    <input type="text" name="prefix_id" value="{{ old('prefix_id', $data->prefix_id) }}" required
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none font-mono">
                    @error('prefix_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Akun *</label>
                    <input type="text" name="nama_akun" value="{{ old('nama_akun', $data->nama_akun) }}" required
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none">
                    @error('nama_akun')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none resize-none">{{ old('deskripsi', $data->deskripsi) }}</textarea>
                    @error('deskripsi')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.master-data.jns-akun.index') }}" class="flex-1 px-4 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-colors text-center">
                    Batal
                </a>
                <button type="submit" class="flex-1 px-4 py-3 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-semibold hover:from-[#4a3514] hover:to-[#674c1d] transition-all">
                    Update Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
