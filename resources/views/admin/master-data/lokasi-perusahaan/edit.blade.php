@extends('layouts.admin')

@section('title', 'Edit Lokasi Perusahaan')

@section('content')
<div class="space-y-6">
    <div>
        <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
            <a href="{{ route('admin.master-data.index') }}">Master Data</a>
            <span>/</span>
            <a href="{{ route('admin.master-data.lokasi-perusahaan.index') }}">Lokasi Perusahaan</a>
            <span>/</span>
            <span class="text-gray-900 font-medium">Edit</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 font-display">Edit Lokasi Perusahaan</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <form action="{{ route('admin.master-data.lokasi-perusahaan.update', $data->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lokasi *</label>
                <input type="text" name="nama_lokasi" value="{{ old('nama_lokasi', $data->nama_lokasi) }}" required
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap *</label>
                <textarea name="alamat_lengkap" rows="3" required
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none">{{ old('alamat_lengkap', $data->alamat_lengkap) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kota *</label>
                    <input type="text" name="kota" value="{{ old('kota', $data->kota) }}" required
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Provinsi *</label>
                    <input type="text" name="provinsi" value="{{ old('provinsi', $data->provinsi) }}" required
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tipe Lokasi *</label>
                <select name="tipe_lokasi" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none">
                    <option value="">Pilih Tipe</option>
                    <option value="kantor_pusat" {{ old('tipe_lokasi', $data->tipe_lokasi) == 'kantor_pusat' ? 'selected' : '' }}>Kantor Pusat</option>
                    <option value="kantor_cabang" {{ old('tipe_lokasi', $data->tipe_lokasi) == 'kantor_cabang' ? 'selected' : '' }}>Kantor Cabang</option>
                    <option value="agen" {{ old('tipe_lokasi', $data->tipe_lokasi) == 'agen' ? 'selected' : '' }}>Agen</option>
                </select>
            </div>

            <div>
                <label class="flex items-center space-x-3">
                    <input type="hidden" name="status_aktif" value="0">
                    <input type="checkbox" name="status_aktif" value="1" {{ old('status_aktif', $data->status_aktif) ? 'checked' : '' }}
                        class="w-5 h-5 text-[#674c1d] border-gray-300 rounded focus:ring-2 focus:ring-[#674c1d]/20">
                    <span class="text-sm font-medium text-gray-700">Status Aktif</span>
                </label>
            </div>

            <div class="flex items-center space-x-3 pt-4 border-t">
                <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl hover:from-[#4a3514] hover:to-[#674c1d] transition-all font-medium shadow-md">
                    Update Data
                </button>
                <a href="{{ route('admin.master-data.lokasi-perusahaan.index') }}" class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all font-medium">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
