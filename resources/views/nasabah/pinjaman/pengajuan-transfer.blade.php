@extends('layouts.nasabah')

@section('title', 'Pengajuan Pinjaman Transfer')

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
        <div class="bg-gradient-to-br from-[#8b6f2f] via-[#a0824d] to-[#d4af37] rounded-3xl shadow-2xl p-6 border-2 border-[#d4af37]/30">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white font-display mb-1">Pengajuan Pinjaman Transfer</h1>
                    <p class="text-white/90 text-sm">Isi form untuk pengajuan pinjaman via transfer</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-lg font-bold text-[#8b6f2f] mb-6 font-display">Form Pengajuan Pinjaman</h2>
            
            <form action="{{ route('nasabah.pinjaman.submit-pengajuan-transfer') }}" method="POST" id="formPengajuan">
                @csrf
                <input type="hidden" name="jenis_pencairan" value="transfer">
                
                <!-- Nominal -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nominal Pinjaman</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                        <input type="text" name="nominal" id="nominal" 
                            class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 transition-all"
                            placeholder="Masukkan nominal pinjaman" required value="{{ old('nominal') }}">
                        <input type="hidden" name="nominal_raw" id="nominal_raw" value="{{ old('nominal_raw') }}">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Minimum: Rp 100.000</p>
                    @error('nominal')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Durasi -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Durasi Pinjaman (Bulan)</label>
                    <select name="durasi" id="durasi" 
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 transition-all" required>
                        <option value="">Pilih durasi</option>
                        @for($i = 1; $i <= 24; $i++)
                            <option value="{{ $i }}" {{ old('durasi') == $i ? 'selected' : '' }}>{{ $i }} {{ $i == 1 ? 'bulan' : 'bulan' }}</option>
                        @endfor
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Pilih jangka waktu pinjaman (1-24 bulan)</p>
                    @error('durasi')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Keterangan -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan (Opsional)</label>
                    <textarea name="keterangan" id="keterangan" rows="3"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 transition-all"
                        placeholder="Tambahkan keterangan jika diperlukan">{{ old('keterangan') }}</textarea>
                </div>

                <!-- Kalkulator Estimasi -->
                <div class="mb-6 p-6 bg-gradient-to-br from-[#8b6f2f]/10 to-[#d4af37]/10 rounded-xl border border-[#8b6f2f]/20">
                    <h3 class="text-sm font-semibold text-[#8b6f2f] mb-4">Estimasi Pinjaman</h3>
                    <div class="space-y-3" id="estimasiSection">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Nominal Pinjaman:</span>
                            <span class="font-semibold text-gray-900" id="estimasiNominal">Rp 0</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Bunga:</span>
                            <span class="font-semibold text-gray-900" id="estimasiBunga">-</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Yang Diterima:</span>
                            <span class="font-semibold text-green-600" id="estimasiDiterima">Rp 0</span>
                        </div>
                        <div class="border-t border-gray-300 pt-3 flex justify-between items-center">
                            <span class="font-semibold text-[#8b6f2f]">Total yang Harus Dibayar:</span>
                            <span class="text-xl font-bold text-[#8b6f2f]" id="estimasiTotal">Rp 0</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Angsuran per bulan:</span>
                            <span class="font-semibold text-gray-900" id="estimasiAngsuran">Rp 0</span>
                        </div>
                    </div>
                </div>

                <!-- Simulasi Tabel Angsuran -->
                <div class="mb-6" id="simulasiTableSection" style="display: none;">
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-[#8b6f2f] to-[#a0824d] text-white p-4">
                            <h3 class="text-lg font-bold">Simulasi Angsuran Per Bulan</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Bulan</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Tanggal</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Pokok</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Bunga</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Total</th>
                                    </tr>
                                </thead>
                                <tbody id="simulasiTableBody" class="divide-y divide-gray-200">
                                    <!-- Data akan diisi oleh JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Hidden PIN Input -->
                <input type="hidden" name="pin" id="pinInput">

                <!-- Submit Button -->
                <button type="button" id="btnSubmitPengajuan"
                    class="w-full bg-gradient-to-r from-[#8b6f2f] to-[#a0824d] text-white font-semibold py-3 rounded-xl hover:shadow-lg transition-all">
                    Ajukan Pinjaman
                </button>
                <a href="{{ route('nasabah.pinjaman.pengajuan') }}" class="block w-full mt-3 py-3 text-center text-gray-600 hover:text-gray-800 transition-colors">
                    Kembali
                </a>
            </form>
        </div>
    </div>
</div>

<!-- Modal Verifikasi PIN -->
<div id="pinModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-[#8b6f2f]/10 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-[#8b6f2f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Verifikasi PIN</h3>
            <p class="text-sm text-gray-600">Masukkan PIN Anda untuk melanjutkan pengajuan pinjaman</p>
        </div>

        <form id="pinForm">
            <div class="space-y-4">
                <div>
                    <label for="pin" class="block text-sm font-medium text-gray-700 mb-2">PIN</label>
                    <input type="password" name="pin" id="pin" maxlength="6" required autofocus
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 transition-all outline-none text-center text-2xl tracking-widest font-mono"
                        placeholder="000000" autocomplete="off" inputmode="numeric">
                    <div id="pinError" class="mt-2 text-sm text-red-600 hidden"></div>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closePinModal()" 
                        class="flex-1 px-4 py-3 border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all font-medium">
                        Batal
                    </button>
                    <button type="submit" id="verifyPinButton"
                        class="flex-1 px-4 py-3 bg-[#8b6f2f] text-white rounded-xl hover:bg-[#6b5423] transition-all font-medium">
                        <span id="verifyPinButtonText">Verifikasi</span>
                        <span id="verifyPinButtonLoading" class="hidden">Memverifikasi...</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nominalInput = document.getElementById('nominal');
    const durasiSelect = document.getElementById('durasi');
    const formPengajuan = document.getElementById('formPengajuan');
    const btnSubmitPengajuan = document.getElementById('btnSubmitPengajuan');
    const pinModal = document.getElementById('pinModal');
    const pinForm = document.getElementById('pinForm');
    const pinInput = document.getElementById('pin');
    const pinInputHidden = document.getElementById('pinInput');
    const pinError = document.getElementById('pinError');
    const verifyPinButton = document.getElementById('verifyPinButton');
    const verifyPinButtonText = document.getElementById('verifyPinButtonText');
    const verifyPinButtonLoading = document.getElementById('verifyPinButtonLoading');
    const simulasiTableSection = document.getElementById('simulasiTableSection');
    const simulasiTableBody = document.getElementById('simulasiTableBody');
    
    let debounceTimer;
    
    // Format Rupiah saat input
    nominalInput.addEventListener('input', function(e) {
        let value = this.value.replace(/[^0-9]/g, '');
        if (value) {
            // Format dengan titik pemisah ribuan
            value = parseInt(value).toLocaleString('id-ID');
            this.value = value;
            document.getElementById('nominal_raw').value = value.replace(/\./g, '');
        } else {
            this.value = '';
            document.getElementById('nominal_raw').value = '';
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
    
    // Update estimasi dan simulasi
    function updateEstimasi() {
        const nominalRaw = document.getElementById('nominal_raw').value;
        const nominal = parseFloat(nominalRaw) || 0;
        const durasi = parseInt(durasiSelect.value) || 0;
        
        if (nominal < 100000 || durasi < 1) {
            document.getElementById('estimasiNominal').textContent = 'Rp 0';
            document.getElementById('estimasiBunga').textContent = '-';
            document.getElementById('estimasiDiterima').textContent = 'Rp 0';
            document.getElementById('estimasiTotal').textContent = 'Rp 0';
            document.getElementById('estimasiAngsuran').textContent = 'Rp 0';
            simulasiTableSection.style.display = 'none';
            return;
        }
        
        // Get simulasi dari server
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            fetch('{{ route("nasabah.pinjaman.simulasi-angsuran") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    nominal: nominal,
                    durasi: durasi
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update estimasi
                    document.getElementById('estimasiNominal').textContent = 'Rp ' + data.data.nominal.toLocaleString('id-ID');
                    document.getElementById('estimasiBunga').textContent = data.data.bunga_persen + '% (Rp ' + data.data.bunga_total.toLocaleString('id-ID') + ')';
                    document.getElementById('estimasiDiterima').textContent = 'Rp ' + data.data.nominal.toLocaleString('id-ID');
                    document.getElementById('estimasiTotal').textContent = 'Rp ' + data.data.total_yang_harus_dibayar.toLocaleString('id-ID');
                    document.getElementById('estimasiAngsuran').textContent = 'Rp ' + data.data.angsuran_per_bulan.toLocaleString('id-ID');
                    
                    // Update tabel simulasi
                    simulasiTableBody.innerHTML = '';
                    data.data.simulasi.forEach(item => {
                        const row = document.createElement('tr');
                        row.className = 'hover:bg-gray-50';
                        row.innerHTML = `
                            <td class="px-4 py-3 text-sm text-gray-900">${item.bulan}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">${item.tanggal}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 text-right">Rp ${item.pokok.toLocaleString('id-ID')}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 text-right">Rp ${item.bunga.toLocaleString('id-ID')}</td>
                            <td class="px-4 py-3 text-sm font-semibold text-[#8b6f2f] text-right">Rp ${item.total.toLocaleString('id-ID')}</td>
                        `;
                        simulasiTableBody.appendChild(row);
                    });
                    
                    simulasiTableSection.style.display = 'block';
                } else {
                    alert(data.message || 'Terjadi kesalahan saat menghitung simulasi');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }, 500);
    }
    
    durasiSelect.addEventListener('change', updateEstimasi);
    
    btnSubmitPengajuan.addEventListener('click', function(e) {
        e.preventDefault();
        
        if (!formPengajuan.checkValidity()) {
            formPengajuan.reportValidity();
            return;
        }
        
        pinModal.classList.remove('hidden');
        pinModal.classList.add('flex');
        pinInput.focus();
    });
    
    pinForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const pin = pinInput.value;
        
        if (pin.length !== 6) {
            pinError.textContent = 'PIN harus 6 digit';
            pinError.classList.remove('hidden');
            return;
        }
        
        verifyPinButtonText.classList.add('hidden');
        verifyPinButtonLoading.classList.remove('hidden');
        verifyPinButton.disabled = true;
        pinError.classList.add('hidden');
        
        fetch('{{ route("nasabah.pinjaman.verify-pin") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ pin: pin })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw new Error(err.message || 'Network response was not ok');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                pinInputHidden.value = pin;
                closePinModal();
                setTimeout(() => {
                    formPengajuan.submit();
                }, 100);
            } else {
                pinError.textContent = data.message || 'PIN yang Anda masukkan salah';
                pinError.classList.remove('hidden');
                verifyPinButtonText.classList.remove('hidden');
                verifyPinButtonLoading.classList.add('hidden');
                verifyPinButton.disabled = false;
                pinInput.value = '';
                pinInput.focus();
            }
        })
        .catch(error => {
            console.error('Error verifying PIN:', error);
            pinError.textContent = error.message || 'Terjadi kesalahan. Silakan coba lagi.';
            pinError.classList.remove('hidden');
            verifyPinButtonText.classList.remove('hidden');
            verifyPinButtonLoading.classList.add('hidden');
            verifyPinButton.disabled = false;
        });
    });
    
    window.closePinModal = function() {
        pinModal.classList.add('hidden');
        pinModal.classList.remove('flex');
        pinInput.value = '';
        pinError.classList.add('hidden');
        verifyPinButtonText.classList.remove('hidden');
        verifyPinButtonLoading.classList.add('hidden');
        verifyPinButton.disabled = false;
    };
    
    pinModal.addEventListener('click', function(e) {
        if (e.target === pinModal) {
            closePinModal();
        }
    });
    
    updateDurasiOptions();
    updateEstimasi();
});
</script>
@endsection

