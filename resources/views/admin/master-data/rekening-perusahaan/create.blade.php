@extends('layouts.admin')

@section('title', 'Tambah Rekening Perusahaan')

@section('content')
<div class="space-y-6">
    <div>
        <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
            <a href="{{ route('admin.master-data.index') }}">Master Data</a>
            <span>/</span>
            <a href="{{ route('admin.master-data.rekening-perusahaan.index') }}">Rekening Perusahaan</a>
            <span>/</span>
            <span class="text-gray-900 font-medium">Tambah</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 font-display">Tambah Rekening Perusahaan</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <form action="{{ route('admin.master-data.rekening-perusahaan.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pemilik *</label>
                    <input type="text" name="pemilik" value="{{ old('pemilik') }}" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 outline-none" placeholder="Contoh: Admin, Bang Farhan">
                    @error('pemilik')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Akun (label) *</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 outline-none" placeholder="Contoh: Operasional BCA">
                    @error('nama')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Bank *</label>
                    <div class="relative">
                        <input type="text" name="bank" id="bank-input" value="{{ old('bank') }}" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 outline-none" placeholder="Contoh: BCA, Mandiri, BNI">
                        <div id="logo-preview" class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-gray-50 rounded-lg border border-gray-200 flex items-center justify-center overflow-hidden">
                            <img id="preview-img" src="" class="w-8 h-8 object-contain hidden">
                            <span id="preview-initial" class="text-xs font-bold text-gray-400">?</span>
                        </div>
                    </div>
                    @error('bank')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    <p class="text-[10px] text-gray-500 mt-1 italic">* Logo akan muncul otomatis jika nama bank sesuai sistem.</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">No. Rekening *</label>
                    <input type="text" name="no_rek" value="{{ old('no_rek') }}" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 outline-none" placeholder="Nomor rekening">
                    @error('no_rek')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kantor Cabang</label>
                    <input type="text" name="cabang" value="{{ old('cabang') }}" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 outline-none" placeholder="Contoh: KCU Malang">
                    @error('cabang')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kode Bank (Transfer)</label>
                    <input type="text" name="kode_bank" value="{{ old('kode_bank') }}" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 outline-none" placeholder="Contoh: 014">
                    @error('kode_bank')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status Rekening *</label>
                    <select name="status" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 outline-none">
                        <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="non-aktif" {{ old('status') == 'non-aktif' ? 'selected' : '' }}>Non-Aktif</option>
                    </select>
                    @error('status')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <script>
                const logoData = @json($logos);
                const bankInput = document.getElementById('bank-input');
                const imgPreview = document.getElementById('preview-img');
                const initialPreview = document.getElementById('preview-initial');

                bankInput.addEventListener('input', function() {
                    const value = this.value.trim().toLowerCase();
                    const match = logoData.find(l => l.nama_bank.toLowerCase() === value);

                    if (match) {
                        imgPreview.src = match.logo_url;
                        imgPreview.classList.remove('hidden');
                        initialPreview.classList.add('hidden');
                    } else {
                        imgPreview.classList.add('hidden');
                        initialPreview.classList.remove('hidden');
                        initialPreview.innerText = value ? value.substring(0, 2).toUpperCase() : '?';
                    }
                });
            </script>
            <div class="flex items-center space-x-3 pt-4 border-t">
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-[#8b6f2f] to-[#d4af37] text-white rounded-xl hover:from-[#674c1d] hover:to-[#8b6f2f] transition-all font-medium shadow-md">Simpan Data</button>
                <a href="{{ route('admin.master-data.rekening-perusahaan.index') }}" class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all font-medium">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
