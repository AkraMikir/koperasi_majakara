@extends('layouts.admin')

@section('title', 'Tambah Bank Registrasi')

@section('content')
<div class="space-y-6 max-w-2xl">

    {{-- Header --}}
    <div>
        <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
            <a href="{{ route('admin.master-data.index') }}" class="hover:text-violet-600 transition-colors">Master Data</a>
            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
            </svg>
            <a href="{{ route('admin.master-data.bank-regis.index') }}" class="hover:text-violet-600 transition-colors">Bank Registrasi</a>
            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-900 font-medium">Tambah Bank</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 font-display">Tambah Bank Registrasi</h1>
        <p class="text-sm text-gray-500 mt-1">Tambahkan bank baru ke dalam daftar master bank registrasi nasabah.</p>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.master-data.bank-regis.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nama Bank --}}
                <div class="md:col-span-2">
                    <label for="nama_bank" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Bank <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nama_bank" name="nama_bank"
                        value="{{ old('nama_bank') }}"
                        placeholder="Contoh: Bank Central Asia (BCA)"
                        class="w-full px-4 py-3 border-2 rounded-xl text-sm transition-colors outline-none
                            {{ $errors->has('nama_bank') ? 'border-red-400 bg-red-50 focus:border-red-500' : 'border-gray-200 focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10' }}">
                    @error('nama_bank')
                    <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- Kode Bank --}}
                <div>
                    <label for="kode_bank" class="block text-sm font-semibold text-gray-700 mb-2">
                        Kode Bank
                        <span class="text-gray-400 font-normal text-xs ml-1">(opsional)</span>
                    </label>
                    <input type="text" id="kode_bank" name="kode_bank"
                        value="{{ old('kode_bank') }}"
                        placeholder="Contoh: 014"
                        maxlength="20"
                        class="w-full px-4 py-3 border-2 rounded-xl text-sm font-mono transition-colors outline-none
                            {{ $errors->has('kode_bank') ? 'border-red-400 bg-red-50 focus:border-red-500' : 'border-gray-200 focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10' }}">
                    @error('kode_bank')
                    <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $message }}
                    </p>
                    @enderror
                    <p class="mt-1.5 text-xs text-gray-400">Kode numerik/alfanumerik bank (maks. 20 karakter).</p>
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                    <div class="flex gap-3">
                        <label class="flex items-center gap-2.5 cursor-pointer group">
                            <input type="radio" name="status" value="1"
                                {{ old('status', '1') == '1' ? 'checked' : '' }}
                                class="w-4 h-4 text-violet-600 border-gray-300 focus:ring-violet-500 cursor-pointer">
                            <span class="text-sm text-gray-700 group-hover:text-violet-700 transition-colors">Aktif</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer group">
                            <input type="radio" name="status" value="0"
                                {{ old('status') == '0' ? 'checked' : '' }}
                                class="w-4 h-4 text-violet-600 border-gray-300 focus:ring-violet-500 cursor-pointer">
                            <span class="text-sm text-gray-700 group-hover:text-violet-700 transition-colors">Nonaktif</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.master-data.bank-regis.index') }}"
                    class="flex-1 px-4 py-3 border-2 border-gray-200 text-gray-600 rounded-xl text-sm font-semibold text-center hover:border-gray-300 hover:bg-gray-50 transition-all duration-200">
                    Batal
                </a>
                <button type="submit"
                    class="flex-1 px-4 py-3 bg-gradient-to-r from-violet-600 to-violet-700 text-white rounded-xl text-sm font-semibold shadow-sm hover:shadow-md hover:shadow-violet-500/20 transition-all duration-200 hover:-translate-y-0.5">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
