@extends('layouts.admin')

@section('title', 'Slot Storage Grid - Master Data')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <a href="{{ route('admin.master-data.index') }}" class="text-sm text-gray-500 hover:text-[#674c1d] transition-colors">Master Data</a>
                <span class="text-gray-400 text-sm">/</span>
                <span class="text-sm text-gray-900 font-medium">Slot Storage</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 font-display">Manajemen Slot Storage</h1>
            <p class="text-gray-500 text-sm mt-1">Atur dimensi baris dan kolom untuk tempat penyimpanan Gadai</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="col-span-1 space-y-6">
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Pilih Kategori</h2>
                <form action="{{ route('admin.master-data.slot-storage.index') }}" method="GET" class="space-y-4">
                    <select name="kategori" class="w-full border-gray-200 rounded-xl focus:ring-[#674c1d] focus:border-[#674c1d]" onchange="this.form.submit()">
                        <option value="electronic" {{ $selectedKategori == 'electronic' ? 'selected' : '' }}>Elektronik</option>
                        <option value="vehicle" {{ $selectedKategori == 'vehicle' ? 'selected' : '' }}>Kendaraan</option>
                        <option value="gold" {{ $selectedKategori == 'gold' ? 'selected' : '' }}>Emas</option>
                    </select>
                </form>

                <div class="mt-6 p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <p class="text-sm font-semibold text-gray-700 mb-2">Dimensi Saat Ini:</p>
                    <div class="flex items-center gap-4">
                        <div>
                            <p class="text-xs text-gray-500">Baris</p>
                            <p class="text-xl font-bold text-gray-900">{{ $maxBaris }}</p>
                        </div>
                        <div class="text-gray-300">x</div>
                        <div>
                            <p class="text-xs text-gray-500">Kolom</p>
                            <p class="text-xl font-bold text-gray-900">{{ $maxKolom }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-span-2">
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Tambah Dimensi Grid</h2>
                <p class="text-sm text-gray-600 mb-6">Penambahan baris atau kolom akan otomatis menggenerate slot-slot kosong baru yang dapat digunakan.</p>
                
                <form action="{{ route('admin.master-data.slot-storage.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <input type="hidden" name="kategori" value="{{ $selectedKategori }}">
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tambah (Baris / Kolom)</label>
                            <select name="jenis" class="w-full border-gray-200 rounded-xl focus:ring-[#674c1d] focus:border-[#674c1d]" required>
                                <option value="baris">Baris (+ Keatas)</option>
                                <option value="kolom">Kolom (+ Kesamping)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                            <input type="number" name="jumlah" min="1" max="10" value="1" class="w-full border-gray-200 rounded-xl focus:ring-[#674c1d] focus:border-[#674c1d]" required>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white font-medium rounded-xl hover:shadow-lg transition-all">
                            Tambahkan Slot
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
