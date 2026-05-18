@extends('layouts.admin')

@section('title', 'Edit Aturan Kategori')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.master-data.kategori-gadai.index') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:bg-gray-50 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Aturan: {{ $data->nama_kategori }}</h1>
            <p class="text-gray-500 text-sm mt-1">Sesuaikan kebijakan keuangan untuk kategori ini</p>
        </div>
    </div>

    <form action="{{ route('admin.master-data.kategori-gadai.update', $data->id) }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @csrf
        @method('PUT')
        
        <div class="p-8 space-y-8">
            {{-- Identitas --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Kategori</label>
                    <input type="text" name="nama_kategori" value="{{ old('nama_kategori', $data->nama_kategori) }}" class="w-full border-gray-200 rounded-xl bg-gray-50 focus:ring-[#674c1d] focus:border-[#674c1d]" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-400 mb-2">Kode (Read Only)</label>
                    <input type="text" value="{{ $data->kode_kategori }}" class="w-full border-gray-200 rounded-xl bg-gray-100 text-gray-500" readonly>
                </div>
            </div>

            <div class="border-t border-gray-100 pt-8">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6">Persentase Biaya (%)</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-blue-700 mb-2">Biaya Jasa/Admin (%)</label>
                        <div class="relative">
                            <input type="number" step="0.01" name="rate_jasa" value="{{ old('rate_jasa', $data->rate_jasa) }}" class="w-full pr-10 border-blue-200 rounded-xl bg-blue-50/30 focus:ring-blue-500 focus:border-blue-500 font-bold" required>
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-blue-400 font-bold">%</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-red-700 mb-2">Denda Telat (%)</label>
                        <div class="relative">
                            <input type="number" step="0.01" name="rate_denda" value="{{ old('rate_denda', $data->rate_denda) }}" class="w-full pr-10 border-red-200 rounded-xl bg-red-50/30 focus:ring-red-500 focus:border-red-500 font-bold" required>
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-red-400 font-bold">%</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-amber-700 mb-2">Biaya Inap (%)</label>
                        <div class="relative">
                            <input type="number" step="0.01" name="rate_inap_persen" value="{{ old('rate_inap_persen', $data->rate_inap_persen) }}" class="w-full pr-10 border-amber-200 rounded-xl bg-amber-50/30 focus:ring-amber-500 focus:border-amber-500 font-bold" required>
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-amber-400 font-bold">%</span>
                        </div>
                        <p class="text-[10px] text-amber-600 mt-1 italic">*Biasanya untuk Emas/Elektronik</p>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-100 pt-8">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6">Kebijakan Waktu & Perpanjangan</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Masa Gadai (Hari)</label>
                        <input type="number" name="masa_gadai_hari" value="{{ old('masa_gadai_hari', $data->masa_gadai_hari) }}" class="w-full border-gray-200 rounded-xl bg-gray-50 focus:ring-[#674c1d] focus:border-[#674c1d]" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Masa Tenggang (Hari)</label>
                        <input type="number" name="masa_tenggang_hari" value="{{ old('masa_tenggang_hari', $data->masa_tenggang_hari) }}" class="w-full border-gray-200 rounded-xl bg-gray-50 focus:ring-[#674c1d] focus:border-[#674c1d]" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Max Perpanjangan (Kali)</label>
                        <input type="number" name="max_extend_default" value="{{ old('max_extend_default', $data->max_extend_default) }}" class="w-full border-gray-200 rounded-xl bg-gray-50 focus:ring-[#674c1d] focus:border-[#674c1d]" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-8 py-6 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
            <p class="text-xs text-gray-500 italic">Perubahan akan langsung berdampak pada kalkulasi transaksi baru.</p>
            <button type="submit" class="px-6 py-2.5 bg-[#674c1d] text-white font-bold rounded-xl shadow-lg shadow-[#674c1d]/20 hover:shadow-xl hover:-translate-y-0.5 transition-all">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
