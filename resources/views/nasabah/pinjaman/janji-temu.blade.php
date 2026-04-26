@extends('layouts.nasabah')

@section('title', 'Janji Temu Pinjaman')

@section('content')
<div class="w-full pb-6">
    <!-- Alert Messages -->
    @if(session('success'))
    <div class="mx-4 mt-4 mb-4">
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-md">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <p class="font-semibold">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mx-4 mt-4 mb-4">
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-md">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <p class="font-semibold">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="mx-4 mt-4 mb-4">
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-md">
            <div class="flex items-start">
                <svg class="w-5 h-5 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <p class="font-semibold mb-2">Terjadi kesalahan:</p>
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Header Section -->
    <div class="mx-4 mt-4 mb-6">
        <div class="bg-linear-to-br from-[#8b6f2f] via-[#a0824d] to-[#d4af37] rounded-3xl shadow-2xl p-6 border-2 border-[#d4af37]/30">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white font-display mb-1">Buat Janji Temu Pinjaman</h1>
                    <p class="text-white/90 text-sm">Atur waktu untuk pencairan pinjaman tunai di kantor</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Janji Temu -->
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-lg font-bold text-[#8b6f2f] mb-6 font-display">Form Pengajuan Pinjaman</h2>

            <form method="POST" action="{{ route('nasabah.pinjaman.submit-janji-temu') }}" class="space-y-6" id="form-janji-temu">
                @csrf
                <input type="hidden" name="jenis_pencairan" value="cash">

                <!-- Nominal Pinjaman -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nominal Pinjaman *</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                        <input type="text" name="nominal" id="nominal" value="{{ old('nominal', request('nominal')) }}" placeholder="Masukkan nominal pinjaman" required
                            class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 outline-none text-lg font-semibold">
                        <input type="hidden" name="nominal_raw" id="nominal_raw" value="{{ old('nominal_raw') }}">
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Minimal pinjaman: Rp 100.000</p>
                    @error('nominal')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Durasi -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Durasi Pinjaman (Bulan) *</label>
                    <select name="durasi" id="durasi" required
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 outline-none">
                        <option value="">Pilih durasi</option>
                        @for($i = 1; $i <= 24; $i++)
                            <option value="{{ $i }}" {{ old('durasi') == $i ? 'selected' : '' }}>{{ $i }} {{ $i == 1 ? 'bulan' : 'bulan' }}</option>
                        @endfor
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Pilih jangka waktu pinjaman (1-24 bulan)</p>
                    <p class="text-xs text-gray-500 mt-2">Pilih jangka waktu pinjaman</p>
                    @error('durasi')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pilih Lokasi -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Lokasi Kantor *</label>
                    <select name="lokasi_temu" id="lokasi_temu" required 
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 outline-none">
                        <option value="">-- Pilih Lokasi --</option>
                        @foreach($lokasi ?? [] as $loc)
                        <option value="{{ $loc->id }}" {{ old('lokasi_temu') == $loc->id ? 'selected' : '' }}>{{ $loc->nama_lokasi }} - {{ $loc->kota }}, {{ $loc->provinsi }}</option>
                        @endforeach
                    </select>
                    @if($lokasi && $lokasi->count() > 0)
                    <div class="mt-3 space-y-2">
                        @foreach($lokasi as $loc)
                        <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <p class="font-semibold text-gray-900">{{ $loc->nama_lokasi }}</p>
                            <p class="text-sm text-gray-600">{{ $loc->alamat_lengkap }}</p>
                            <p class="text-xs text-gray-500">{{ $loc->kota }}, {{ $loc->provinsi }}</p>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    @error('lokasi_temu')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Janji Temu -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Janji Temu *</label>
                    <input type="date" name="tanggal_janji_temu" id="tanggal_janji_temu" value="{{ old('tanggal_janji_temu') }}" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" 
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 outline-none">
                    <p class="text-xs text-gray-500 mt-2">Pilih tanggal minimal besok</p>
                    @error('tanggal_janji_temu')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Waktu Janji Temu -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Waktu Janji Temu *</label>
                    <input type="time" name="waktu_janji_temu" id="waktu_janji_temu" value="{{ old('waktu_janji_temu') }}" required 
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 outline-none">
                    <p class="text-xs text-gray-500 mt-2">Jam operasional: 08:00 - 16:00</p>
                    @error('waktu_janji_temu')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Keterangan -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan Tambahan</label>
                    <textarea name="keterangan" rows="3" placeholder="Tambahkan keterangan jika diperlukan..."
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 outline-none resize-none">{{ old('keterangan', request('keterangan')) }}</textarea>
                </div>

                <!-- Kalkulator Estimasi -->
                <div class="p-6 bg-linear-to-br from-[#8b6f2f]/10 to-[#d4af37]/10 rounded-xl border border-[#8b6f2f]/20">
                    <h3 class="text-sm font-semibold text-[#8b6f2f] mb-4">Estimasi Pinjaman</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Nominal Pinjaman:</span>
                            <span class="font-semibold text-gray-900" id="estimasiNominal">Rp 0</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Estimasi Bunga (5%):</span>
                            <span class="font-semibold text-gray-900" id="estimasiBunga">Rp 0</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Yang Diterima:</span>
                            <span class="font-semibold" id="estimasiDiterima">Rp 0</span>
                        </div>
                        <div class="border-t border-gray-300 pt-3 flex justify-between items-center">
                            <span class="font-semibold text-[#8b6f2f]">Total yang Harus Dibayar:</span>
                            <span class="text-xl font-bold text-[#8b6f2f]" id="estimasiTotal">Rp 0</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Angsuran per periode:</span>
                            <span class="font-semibold text-gray-900" id="estimasiAngsuran">Rp 0</span>
                        </div>
                    </div>
                </div>

                <!-- Hidden PIN Input -->
                <input type="hidden" name="pin" id="pinInput">

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="button" onclick="showPinModal()" class="w-full py-4 bg-linear-to-r from-[#8b6f2f] to-[#a0824d] text-white rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.02]">
                        Buat Janji Temu
                    </button>
                    <a href="{{ route('nasabah.pinjaman.pengajuan') }}" class="block w-full mt-3 py-3 text-center text-gray-600 hover:text-gray-800 transition-colors">
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- PIN Modal -->
<div id="pin-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-linear-to-br from-[#8b6f2f] to-[#a0824d] rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Verifikasi PIN</h3>
                    <p class="text-sm text-gray-600">Masukkan PIN Anda untuk melanjutkan</p>
                </div>
            </div>
            <button onclick="closePinModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div id="pin-error" class="hidden mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
            <p class="text-red-700 text-sm"></p>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-2">PIN (6 digit)</label>
            <input type="password" id="pin-input" maxlength="6" pattern="[0-9]*" inputmode="numeric"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 outline-none text-center text-2xl font-mono tracking-widest"
                placeholder="••••••"
                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
        </div>

        <div class="flex gap-3">
            <button onclick="closePinModal()" class="flex-1 px-4 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-colors">
                Batal
            </button>
            <button onclick="verifyAndSubmit()" class="flex-1 px-4 py-3 bg-linear-to-r from-[#8b6f2f] to-[#a0824d] text-white rounded-xl font-semibold hover:from-[#6b5423] hover:to-[#8b6f2f] transition-all">
                Verifikasi
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nominalInput = document.getElementById('nominal');
    const nominalRawInput = document.getElementById('nominal_raw');
    const durasiSelect = document.getElementById('durasi');
    
    // Format Rupiah saat input
    nominalInput.addEventListener('input', function(e) {
        let value = this.value.replace(/[^0-9]/g, '');
        if (value) {
            // Format dengan titik pemisah ribuan
            value = parseInt(value).toLocaleString('id-ID');
            this.value = value;
            nominalRawInput.value = value.replace(/\./g, '');
        } else {
            this.value = '';
            nominalRawInput.value = '';
        }
        updateEstimasi();
    });
    
    // Validasi hanya angka
    nominalInput.addEventListener('keypress', function(e) {
        const charCode = (e.which) ? e.which : e.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            e.preventDefault();
        }
    });
    
    function updateEstimasi() {
        const nominalRaw = nominalRawInput.value;
        const nominal = parseFloat(nominalRaw) || 0;
        const durasi = parseInt(durasiSelect.value) || 0;
        
        if (nominal < 100000 || durasi < 1) {
            document.getElementById('estimasiNominal').textContent = 'Rp 0';
            document.getElementById('estimasiBunga').textContent = '-';
            document.getElementById('estimasiDiterima').textContent = 'Rp 0';
            document.getElementById('estimasiTotal').textContent = 'Rp 0';
            document.getElementById('estimasiAngsuran').textContent = 'Rp 0';
            return;
        }
        
        // Estimasi sederhana (akan dihitung lebih akurat di backend)
        const estimasiBungaPersen = 10; // Default, akan disesuaikan berdasarkan durasi
        const estimasiBunga = (nominal * estimasiBungaPersen) / 100;
        const yangDiterima = nominal; // Tidak dipotong di awal
        const totalYangHarusDibayar = nominal + estimasiBunga;
        const angsuranPerBulan = durasi > 0 ? totalYangHarusDibayar / durasi : 0;
        
        document.getElementById('estimasiNominal').textContent = 'Rp ' + nominal.toLocaleString('id-ID');
        document.getElementById('estimasiBunga').textContent = estimasiBungaPersen + '% (Rp ' + estimasiBunga.toLocaleString('id-ID') + ')';
        document.getElementById('estimasiDiterima').textContent = 'Rp ' + yangDiterima.toLocaleString('id-ID');
        document.getElementById('estimasiTotal').textContent = 'Rp ' + totalYangHarusDibayar.toLocaleString('id-ID');
        document.getElementById('estimasiAngsuran').textContent = 'Rp ' + Math.ceil(angsuranPerBulan).toLocaleString('id-ID') + ' / bulan';
    }
    
    durasiSelect.addEventListener('change', updateEstimasi);
    
    updateEstimasi();
});

function showPinModal() {
    const form = document.getElementById('form-janji-temu');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    document.getElementById('pin-modal').classList.remove('hidden');
    document.getElementById('pin-modal').classList.add('flex');
    document.getElementById('pin-input').focus();
}

function closePinModal() {
    document.getElementById('pin-modal').classList.add('hidden');
    document.getElementById('pin-modal').classList.remove('flex');
    document.getElementById('pin-input').value = '';
    document.getElementById('pin-error').classList.add('hidden');
}

function verifyAndSubmit() {
    const pin = document.getElementById('pin-input').value;
    
    if (pin.length !== 6) {
        showPinError('PIN harus 6 digit');
        return;
    }

    // Add PIN to form
    const form = document.getElementById('form-janji-temu');
    const pinInput = document.createElement('input');
    pinInput.type = 'hidden';
    pinInput.name = 'pin';
    pinInput.value = pin;
    form.appendChild(pinInput);

    // Submit form
    form.submit();
}

function showPinError(message) {
    const errorDiv = document.getElementById('pin-error');
    errorDiv.querySelector('p').textContent = message;
    errorDiv.classList.remove('hidden');
}

// Close modal on outside click
document.getElementById('pin-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePinModal();
    }
});
</script>
@endpush
@endsection

