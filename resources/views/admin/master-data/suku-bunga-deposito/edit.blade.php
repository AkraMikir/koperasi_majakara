@extends('layouts.admin')

@section('title', 'Edit Suku Bunga Deposito')

@section('content')
<div class="space-y-6">
    <div>
        <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
            <a href="{{ route('admin.master-data.index') }}">Master Data</a>
            <span>/</span>
            <a href="{{ route('admin.master-data.suku-bunga-deposito.index') }}">Suku Bunga Deposito</a>
            <span>/</span>
            <span class="text-gray-900 font-medium">Edit</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 font-display">Edit Suku Bunga Deposito</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <form action="{{ route('admin.master-data.suku-bunga-deposito.update', $data->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tenor *</label>
                <select name="tenor_id" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#d4af37] focus:ring-2 focus:ring-[#d4af37]/20 outline-none">
                    <option value="">Pilih Tenor</option>
                    @foreach($tenors as $tenor)
                        <option value="{{ $tenor->id }}" {{ old('tenor_id', $data->tenor_id) == $tenor->id ? 'selected' : '' }}>
                            {{ $tenor->tenor_bulan }} bulan ({{ $tenor->tenor_hari }} hari)
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nominal Minimum *</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                        <input type="text" name="min_nominal" id="min_nominal" value="{{ old('min_nominal', $data->min_nominal) ? number_format(old('min_nominal', $data->min_nominal), 0, ',', '.') : '' }}" required oninput="formatCurrency(this)"
                            class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#d4af37] focus:ring-2 focus:ring-[#d4af37]/20 outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nominal Maksimum *</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                        <input type="text" name="max_nominal" id="max_nominal" value="{{ old('max_nominal', $data->max_nominal) ? number_format(old('max_nominal', $data->max_nominal), 0, ',', '.') : '' }}" required oninput="formatCurrency(this)"
                            class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#d4af37] focus:ring-2 focus:ring-[#d4af37]/20 outline-none">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Bunga (%) *</label>
                <div class="relative">
                    <input type="number" name="bunga" value="{{ old('bunga', $data->bunga) }}" required min="0" max="100" step="0.0001"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#d4af37] focus:ring-2 focus:ring-[#d4af37]/20 outline-none">
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500">%</span>
                </div>
            </div>

            <div>
                <label class="flex items-center space-x-3">
                    <input type="hidden" name="status" value="0">
                    <input type="checkbox" name="status" value="1" {{ old('status', $data->status) ? 'checked' : '' }}
                        class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-2 focus:ring-purple-600/20">
                    <span class="text-sm font-medium text-gray-700">Status Aktif</span>
                </label>
            </div>

            <div class="flex items-center space-x-3 pt-4 border-t">
                <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#d4af37] to-[#8b6f2f] text-white rounded-xl hover:from-[#8b6f2f] hover:to-[#674c1d] transition-all font-medium shadow-md">
                    Update Data
                </button>
                <a href="{{ route('admin.master-data.suku-bunga-deposito.index') }}" class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all font-medium">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function formatCurrency(input) {
        let value = input.value.replace(/[^0-9]/g, '');
        if (value) value = parseInt(value).toLocaleString('id-ID');
        input.value = value;
    }

    document.querySelector('form').addEventListener('submit', function(e) {
        const minNominal = document.getElementById('min_nominal');
        const maxNominal = document.getElementById('max_nominal');
        if (minNominal) minNominal.value = minNominal.value.replace(/[^0-9]/g, '');
        if (maxNominal) maxNominal.value = maxNominal.value.replace(/[^0-9]/g, '');
    });
</script>
@endpush
