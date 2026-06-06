@extends('layouts.admin')

@section('title', 'Tambah Golongan Inap Kendaraan')

@section('content')
<div class="max-w-3xl mx-auto">
    {{-- Header Section --}}
    <div class="flex items-center justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('admin.master-data.index') }}" class="hover:text-gray-900">Master Data</a>
                <span>/</span>
                <a href="{{ route('admin.master-data.inap-kendaraan.index') }}" class="hover:text-gray-900">Master Inap Kendaraan</a>
                <span>/</span>
                <span class="text-gray-900 font-medium">Tambah</span>
            </div>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight font-display">Tambah Golongan Inap</h2>
            <p class="text-sm text-gray-500 mt-1">Tambahkan tarif inap kendaraan baru untuk gadai.</p>
        </div>
        <a href="{{ route('admin.master-data.inap-kendaraan.index') }}" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-600 font-bold rounded-xl hover:bg-gray-50 transition-all flex items-center gap-2 shadow-sm text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-2xl text-red-800 animate-fade-in">
            <ul class="list-disc list-inside text-sm font-bold">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
        <form action="{{ route('admin.master-data.inap-kendaraan.store') }}" method="POST" class="p-8 space-y-6" onsubmit="let input = document.getElementById('nominal_inap'); if(input) { input.value = input.value.replace(/[^0-9]/g, ''); }">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="golongan" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Golongan <span class="text-red-500">*</span></label>
                    <input type="text" name="golongan" id="golongan" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#674c1d] focus:border-transparent transition-all font-bold text-gray-700 uppercase" value="{{ old('golongan') }}" required placeholder="Contoh: G, H, I">
                    <p class="text-[10px] text-gray-400 mt-1">Masukkan huruf/kode golongan unik</p>
                </div>

                <div>
                    <label for="jenis_kendaraan" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Jenis Kendaraan <span class="text-red-500">*</span></label>
                    <input type="text" name="jenis_kendaraan" id="jenis_kendaraan" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#674c1d] focus:border-transparent transition-all font-bold text-gray-700" value="{{ old('jenis_kendaraan') }}" required placeholder="Contoh: motor matic, mobil sedan">
                </div>

                <div class="md:col-span-2">
                    <label for="nominal_inap" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Biaya Inap Per Hari (Rupiah) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-gray-400 font-bold text-sm">Rp</span>
                        </div>
                        <input type="text" name="nominal_inap" id="nominal_inap" class="w-full pl-10 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#674c1d] focus:border-transparent transition-all font-bold text-gray-700" value="{{ old('nominal_inap') ? number_format(old('nominal_inap'), 0, ',', '.') : '' }}" required placeholder="Contoh: 50.000" oninput="formatCurrency(this)">
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label for="keterangan" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" rows="4" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#674c1d] focus:border-transparent transition-all font-bold text-gray-700" placeholder="Contoh: motor sport 250cc, mobil premium SUV">{{ old('keterangan') }}</textarea>
                </div>
            </div>

            <div class="flex justify-end items-center gap-3 pt-6 border-t border-gray-100">
                <button type="reset" class="px-6 py-3 text-gray-500 font-bold hover:text-gray-700 transition-all text-sm">Reset</button>
                <button type="submit" class="px-8 py-3 bg-[#674c1d] text-white font-black rounded-2xl hover:bg-[#8b6f2f] transition-all shadow-lg shadow-amber-900/20 text-sm">
                    Simpan Golongan
                </button>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
    function formatCurrency(input) {
        let value = input.value.replace(/[^0-9]/g, '');
        if (value) value = parseInt(value).toLocaleString('id-ID');
        input.value = value;
    }
</script>
@endpush
@endsection
