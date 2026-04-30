@extends('layouts.admin')

@section('title', 'Tambah Paket Deposito')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center space-x-4 bg-white p-6 rounded-2xl border border-gray-100 shadow-xs">
        <a href="{{ route('admin.deposito.paket.index') }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-xl transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 font-display">Tambah Paket Deposito</h1>
            <p class="text-sm text-gray-500 mt-1">Buat paket deposito baru untuk nasabah.</p>
        </div>
    </div>

    <!-- Form Section -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-xs overflow-hidden">
        <form action="{{ route('admin.deposito.paket.store') }}" method="POST" class="p-6 md:p-8 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Paket -->
                <div class="col-span-1 md:col-span-2">
                    <label for="nama_paket" class="block text-sm font-medium text-gray-700 mb-2">Nama Paket <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_paket" id="nama_paket" required
                        value="{{ old('nama_paket') }}"
                        class="w-full rounded-xl border-gray-300 focus:border-[#8b6f2f] focus:ring focus:ring-[#8b6f2f]/20 transition-all placeholder-gray-400"
                        placeholder="Contoh: Deposito 3 Bulan Ekstra">
                    @error('nama_paket')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tenor -->
                <div>
                    <label for="tenor_bulan" class="block text-sm font-medium text-gray-700 mb-2">Tenor (Bulan) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="tenor_bulan" id="tenor_bulan" required
                            class="w-full rounded-xl border-gray-300 focus:border-[#8b6f2f] focus:ring focus:ring-[#8b6f2f]/20 transition-all appearance-none pr-10">
                            <option value="">Pilih Tenor</option>
                            @foreach($tenors as $tenor)
                                <option value="{{ $tenor->tenor_bulan }}" {{ old('tenor_bulan') == $tenor->tenor_bulan ? 'selected' : '' }}>{{ $tenor->tenor_bulan }} Bulan</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    @error('tenor_bulan')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Suku Bunga -->
                <div>
                    <label for="suku_bunga" class="block text-sm font-medium text-gray-700 mb-2">Suku Bunga (%) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="number" step="0.01" min="0" name="suku_bunga" id="suku_bunga" required
                            value="{{ old('suku_bunga') }}"
                            class="w-full rounded-xl border-gray-300 focus:border-[#8b6f2f] focus:ring focus:ring-[#8b6f2f]/20 transition-all"
                            placeholder="Contoh: 5.50">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-500 font-medium">%</div>
                    </div>
                    @error('suku_bunga')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Minimal Nominal -->
                <div>
                    <label for="minimal_nominal" class="block text-sm font-medium text-gray-700 mb-2">Minimal Nominal <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-500 font-medium">Rp</div>
                        <input type="number" name="minimal_nominal" id="minimal_nominal" required min="0"
                            value="{{ old('minimal_nominal') }}"
                            class="w-full rounded-xl border-gray-300 focus:border-[#8b6f2f] focus:ring focus:ring-[#8b6f2f]/20 transition-all pl-12"
                            placeholder="Contoh: 1000000">
                    </div>
                    @error('minimal_nominal')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Maksimal Nominal -->
                <div>
                    <label for="maksimal_nominal" class="block text-sm font-medium text-gray-700 mb-2">Maksimal Nominal (Opsional)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-500 font-medium">Rp</div>
                        <input type="number" name="maksimal_nominal" id="maksimal_nominal" min="0"
                            value="{{ old('maksimal_nominal') }}"
                            class="w-full rounded-xl border-gray-300 focus:border-[#8b6f2f] focus:ring focus:ring-[#8b6f2f]/20 transition-all pl-12"
                            placeholder="Kosongkan jika tidak ada batas">
                    </div>
                    @error('maksimal_nominal')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="status" id="status" required
                            class="w-full rounded-xl border-gray-300 focus:border-[#8b6f2f] focus:ring focus:ring-[#8b6f2f]/20 transition-all appearance-none pr-10">
                            <option value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    @error('status')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori -->
                <div>
                    <label for="kategori_id" class="block text-sm font-medium text-gray-700 mb-2">Kategori Promo <span class="text-gray-400 font-normal">(Opsional)</span></label>
                    <div class="relative">
                        <select name="kategori_id" id="kategori_id"
                            class="w-full rounded-xl border-gray-300 focus:border-[#8b6f2f] focus:ring focus:ring-[#8b6f2f]/20 transition-all appearance-none pr-10">
                            <option value="">Pilih Kategori Promo</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama_kategori }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    @error('kategori_id')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Keterangan -->
                <div class="col-span-1 md:col-span-2">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan (Opsional)</label>
                    <textarea name="keterangan" id="keterangan" rows="3"
                        class="w-full rounded-xl border-gray-300 focus:border-[#8b6f2f] focus:ring focus:ring-[#8b6f2f]/20 transition-all placeholder-gray-400"
                        placeholder="Tambahkan informasi khusus mengenai paket ini (jika ada)">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('admin.deposito.paket.index') }}" class="px-5 py-2.5 bg-gray-50 text-gray-700 font-medium rounded-xl border border-gray-200 hover:bg-gray-100 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white font-medium rounded-xl hover:shadow-lg hover:shadow-[#674c1d]/20 transition-all duration-300">
                    Simpan Paket
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
