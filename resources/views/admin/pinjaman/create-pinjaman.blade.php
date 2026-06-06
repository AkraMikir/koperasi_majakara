@extends('layouts.admin')

@section('title', 'Tambah Pinjaman')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('admin.pinjaman.index') }}" class="hover:text-gray-900">Pinjaman</a>
                <span>/</span>
                <a href="{{ route('admin.pinjaman.pinjaman-aktif') }}" class="hover:text-gray-900">Pinjaman Aktif</a>
                <span>/</span>
                <span class="text-gray-900 font-medium">Tambah Pinjaman</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Tambah Pinjaman Baru</h1>
            <p class="text-gray-600 mt-1">Untuk nasabah yang ketemu langsung / janji temu</p>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <form action="{{ route('admin.pinjaman.store-pinjaman') }}" method="POST" class="space-y-6" onsubmit="let input = document.getElementById('nominal'); if(input) input.value = input.value.replace(/[^0-9]/g, '');">
            @csrf

            <!-- Pilih Nasabah -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Nasabah *</label>
                <select name="id_anggota" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none">
                    <option value="">Pilih Nasabah</option>
                    @foreach($nasabah as $item)
                        <option value="{{ $item->id }}" {{ old('id_anggota') == $item->id ? 'selected' : '' }}>
                            {{ $item->user->nama ?? 'N/A' }} - {{ $item->user->email ?? 'N/A' }}
                        </option>
                    @endforeach
                </select>
                @error('id_anggota')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nominal -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nominal Pinjaman *</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                    <input type="text" name="nominal" id="nominal" value="{{ old('nominal') }}" required
                        class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none"
                        oninput="formatCurrency(this)">
                </div>
                <p class="text-xs text-gray-500 mt-1">Minimum: Rp 100.000</p>
                @error('nominal')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Durasi -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Durasi Pinjaman (Bulan) *</label>
                <select name="durasi" id="durasi" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none">
                    <option value="">Pilih Durasi</option>
                    @for($i = 1; $i <= 24; $i++)
                        <option value="{{ $i }}" {{ old('durasi') == $i ? 'selected' : '' }}>{{ $i }} {{ $i == 1 ? 'bulan' : 'bulan' }}</option>
                    @endfor
                </select>
                @error('durasi')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tanggal Pinjam -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Pinjam *</label>
                <input type="date" name="tgl_pinjam" value="{{ old('tgl_pinjam', date('Y-m-d')) }}" required
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none">
                @error('tgl_pinjam')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Info Bunga & Denda -->
            <div id="infoSection" class="p-4 bg-[#674c1d]/5 border border-[#674c1d]/20 rounded-xl hidden">
                <h4 class="font-semibold text-[#674c1d] mb-2">Informasi Pinjaman</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Bunga:</span>
                        <span class="font-semibold text-gray-900" id="infoBunga">-</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Denda:</span>
                        <span class="font-semibold text-gray-900" id="infoDenda">-</span>
                    </div>
                    <div class="flex justify-between border-t pt-2">
                        <span class="text-gray-600">Total yang harus dibayar:</span>
                        <span class="font-bold text-[#674c1d]" id="infoTotal">-</span>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center space-x-3 pt-4 border-t border-gray-200">
                <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl hover:from-[#4a3514] hover:to-[#674c1d] transition-all font-medium shadow-md">
                    Simpan Pinjaman
                </button>
                <a href="{{ route('admin.pinjaman.pinjaman-aktif') }}" class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all font-medium">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function formatCurrency(input) {
    let value = input.value.replace(/[^0-9]/g, '');
    if (value) value = parseInt(value).toLocaleString('id-ID');
    input.value = value;
}

document.addEventListener('DOMContentLoaded', function() {
    const nominalInput = document.getElementById('nominal');
    const durasiSelect = document.getElementById('durasi');
    const infoSection = document.getElementById('infoSection');
    
    if (nominalInput && nominalInput.value) {
        formatCurrency(nominalInput);
    }
    
    function updateInfo() {
        const nominal = parseFloat(nominalInput.value.replace(/[^0-9]/g, '')) || 0;
        const durasi = parseInt(durasiSelect.value) || 0;
        
        if (nominal >= 100000 && durasi > 0) {
            // Estimasi bunga berdasarkan range
            let bungaPersen = 10;
            if (durasi >= 22) bungaPersen = 24;
            else if (durasi >= 19) bungaPersen = 22;
            else if (durasi >= 16) bungaPersen = 20;
            else if (durasi >= 13) bungaPersen = 18;
            else if (durasi >= 10) bungaPersen = 16;
            else if (durasi >= 7) bungaPersen = 14;
            else if (durasi >= 4) bungaPersen = 12;
            
            const bungaRp = (nominal * bungaPersen) / 100;
            const total = nominal + bungaRp;
            
            document.getElementById('infoBunga').textContent = bungaPersen + '% (Rp ' + bungaRp.toLocaleString('id-ID') + ')';
            document.getElementById('infoDenda').textContent = '0.3% per hari';
            document.getElementById('infoTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
            
            infoSection.classList.remove('hidden');
        } else {
            infoSection.classList.add('hidden');
        }
    }
    
    updateInfo();
    nominalInput.addEventListener('input', updateInfo);
    durasiSelect.addEventListener('change', updateInfo);
});
</script>
@endsection
