@extends('layouts.admin')

@section('title', 'Kirim Dana ke Admin')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.petty-cash.dashboard') }}" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Kirim Dana ke Admin</h1>
            <p class="text-gray-600 mt-1">Transfer modal operasional ke admin teller</p>
        </div>
    </div>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl">
        <ul class="list-disc pl-5 space-y-1 text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6">
        <form action="{{ route('admin.petty-cash.penerimaan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Pilih Admin --}}
            <div>
                <label for="admin_id" class="block text-sm font-semibold text-gray-700 mb-2">Admin Tujuan <span class="text-red-500">*</span></label>
                <select name="admin_id" id="admin_id" required
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#674c1d] focus:border-transparent">
                    <option value="">Pilih Admin Operasional...</option>
                    @foreach($admins as $admin)
                    <option value="{{ $admin->id }}" {{ old('admin_id') == $admin->id ? 'selected' : '' }}>
                        {{ $admin->nama }} ({{ $admin->email }})
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Grid Nominal --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                {{-- Nominal Transfer --}}
                <div>
                    <label for="nominal_tf" class="block text-sm font-semibold text-gray-700 mb-2">Nominal Transfer (Bank)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-medium">Rp</span>
                        <input type="number" name="nominal_tf" id="nominal_tf" min="0" value="{{ old('nominal_tf', 0) }}"
                            class="w-full border border-gray-300 rounded-xl pl-10 pr-4 py-3 text-sm focus:ring-2 focus:ring-[#674c1d] focus:border-transparent"/>
                    </div>
                </div>

                {{-- Nominal Cash --}}
                <div>
                    <label for="nominal_cash" class="block text-sm font-semibold text-gray-700 mb-2">Nominal Cash (Fisik)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-medium">Rp</span>
                        <input type="number" name="nominal_cash" id="nominal_cash" min="0" value="{{ old('nominal_cash', 0) }}"
                            class="w-full border border-gray-300 rounded-xl pl-10 pr-4 py-3 text-sm focus:ring-2 focus:ring-[#674c1d] focus:border-transparent"/>
                    </div>
                </div>
            </div>

            {{-- Total Preview --}}
            <div class="bg-[#674c1d]/5 border border-[#674c1d]/20 rounded-xl p-4 flex justify-between items-center">
                <span class="text-sm font-medium text-[#674c1d]">Total yang Dikirim</span>
                <span id="total-display" class="text-xl font-bold text-[#674c1d]">Rp 0</span>
            </div>

            {{-- Upload Bukti TF --}}
            <div>
                <label for="bukti_tf" class="block text-sm font-semibold text-gray-700 mb-2">Bukti Transfer (Screenshot)</label>
                <input type="file" name="bukti_tf" id="bukti_tf" accept="image/*"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#674c1d]"/>
                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, WEBP. Maks 5MB</p>
            </div>

            {{-- Upload Foto Cash --}}
            <div>
                <label for="foto_cash" class="block text-sm font-semibold text-gray-700 mb-2">Foto Kantong/Amplop Cash</label>
                <input type="file" name="foto_cash" id="foto_cash" accept="image/*"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#674c1d]"/>
                <p class="text-xs text-gray-500 mt-1">Opsional – foto bukti fisik uang yang dikirim</p>
            </div>

            {{-- Keterangan --}}
            <div>
                <label for="keterangan" class="block text-sm font-semibold text-gray-700 mb-2">Keterangan</label>
                <textarea name="keterangan" id="keterangan" rows="3"
                    placeholder="Contoh: Modal operasional minggu pertama April 2026..."
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#674c1d] focus:border-transparent resize-none">{{ old('keterangan') }}</textarea>
            </div>

            {{-- Submit --}}
            <div class="flex gap-3">
                <a href="{{ route('admin.petty-cash.dashboard') }}"
                   class="flex-1 text-center px-6 py-3 border border-gray-300 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="flex-1 px-6 py-3 bg-[#674c1d] text-white rounded-xl text-sm font-bold hover:bg-[#4a3514] transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    Kirim Dana
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function formatRupiah(val) {
    return 'Rp ' + parseInt(val || 0).toLocaleString('id-ID');
}
function updateTotal() {
    const tf   = parseFloat(document.getElementById('nominal_tf').value  || 0);
    const cash = parseFloat(document.getElementById('nominal_cash').value || 0);
    document.getElementById('total-display').textContent = formatRupiah(tf + cash);
}
document.getElementById('nominal_tf').addEventListener('input', updateTotal);
document.getElementById('nominal_cash').addEventListener('input', updateTotal);
</script>
@endpush
@endsection
