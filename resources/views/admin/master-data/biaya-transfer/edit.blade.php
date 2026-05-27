@extends('layouts.admin')

@section('title', 'Edit Biaya Transfer')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('admin.master-data.index') }}" class="hover:text-gray-900">Master Data</a>
                <span>/</span>
                <a href="{{ route('admin.master-data.biaya-transfer.index') }}" class="hover:text-gray-900">Biaya Transfer</a>
                <span>/</span>
                <span class="text-gray-900 font-medium">Edit</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Edit Biaya Transfer</h1>
        </div>
        <a href="{{ route('admin.master-data.biaya-transfer.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium">
            ← Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <form method="POST" action="{{ route('admin.master-data.biaya-transfer.update', $data->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Bank Pengirim *</label>
                    <select name="bank_pengirim" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none">
                        <option value="">Pilih Bank</option>
                        <option value="BCA" {{ $data->bank_pengirim == 'BCA' ? 'selected' : '' }}>BCA</option>
                        <option value="BNI" {{ $data->bank_pengirim == 'BNI' ? 'selected' : '' }}>BNI</option>
                        <option value="Mandiri" {{ $data->bank_pengirim == 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                        <option value="BRI" {{ $data->bank_pengirim == 'BRI' ? 'selected' : '' }}>BRI</option>
                        <option value="CIMB Niaga" {{ $data->bank_pengirim == 'CIMB Niaga' ? 'selected' : '' }}>CIMB Niaga</option>
                        <option value="Permata" {{ $data->bank_pengirim == 'Permata' ? 'selected' : '' }}>Permata</option>
                    </select>
                    @error('bank_pengirim')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Bank Penerima *</label>
                    <select name="bank_penerima" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none">
                        <option value="">Pilih Bank</option>
                        <option value="BCA" {{ $data->bank_penerima == 'BCA' ? 'selected' : '' }}>BCA</option>
                        <option value="BNI" {{ $data->bank_penerima == 'BNI' ? 'selected' : '' }}>BNI</option>
                        <option value="Mandiri" {{ $data->bank_penerima == 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                        <option value="BRI" {{ $data->bank_penerima == 'BRI' ? 'selected' : '' }}>BRI</option>
                        <option value="CIMB Niaga" {{ $data->bank_penerima == 'CIMB Niaga' ? 'selected' : '' }}>CIMB Niaga</option>
                        <option value="Permata" {{ $data->bank_penerima == 'Permata' ? 'selected' : '' }}>Permata</option>
                        <option value="Bank Lainnya" {{ $data->bank_penerima == 'Bank Lainnya' ? 'selected' : '' }}>Bank Lainnya</option>
                    </select>
                    @error('bank_penerima')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Biaya Admin *</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                        <input type="text" name="biaya_admin" id="biaya_admin" value="{{ number_format($data->biaya_admin, 0, '.', '') }}" required
                            class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none text-lg font-semibold"
                            oninput="formatCurrency(this)">
                    </div>
                    @error('biaya_admin')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Min Saldo Non-BCA (Opsional)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                        <input type="text" name="min_saldo_non_bca" id="min_saldo_non_bca" value="{{ number_format($data->min_saldo_non_bca, 0, '.', '') }}"
                            class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none text-lg font-semibold"
                            oninput="formatCurrency(this)">
                    </div>
                    @error('min_saldo_non_bca')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    <p class="text-xs text-gray-500 mt-2">Batas minimum saldo nasabah non-BCA untuk akses fitur premium</p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan</label>
                    <textarea name="keterangan" rows="3" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none resize-none">{{ old('keterangan', $data->keterangan) }}</textarea>
                </div>
            </div>

            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.master-data.biaya-transfer.index') }}" class="flex-1 px-4 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-colors text-center">
                    Batal
                </a>
                <button type="submit" class="flex-1 px-4 py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-semibold hover:from-[#4a3514] hover:to-[#674c1d] transition-all">
                    Update Data
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function formatCurrency(input) {
        let value = input.value.replace(/[^\d]/g, '');
        if (value) {
            input.value = new Intl.NumberFormat('id-ID').format(value);
        } else {
            input.value = '0';
        }
    }

    document.querySelector('form').addEventListener('submit', function(e) {
        const biayaInput = document.getElementById('biaya_admin');
        if (biayaInput) {
            biayaInput.value = biayaInput.value.replace(/[^\d]/g, '');
        }
        
        const minSaldoInput = document.getElementById('min_saldo_non_bca');
        if (minSaldoInput) {
            minSaldoInput.value = minSaldoInput.value.replace(/[^\d]/g, '');
        }
    });
</script>
@endpush
@endsection
