@extends('layouts.nasabah')

@section('title', 'Pembayaran Pinjaman')

@section('content')
<div class="w-full pb-6">
    <!-- Hero Section -->
    <div class="mx-4 mt-4 mb-6">
        <div class="bg-gradient-to-br from-[#8b6f2f] via-[#a0824d] to-[#d4af37] rounded-3xl shadow-2xl p-8 border-2 border-[#d4af37]/30 relative overflow-hidden">
            <div class="relative z-10">
                <h1 class="text-3xl font-bold text-white mb-2 font-display">Pembayaran Pinjaman</h1>
                <p class="text-white/90 text-sm">Pilih pinjaman dan angsuran yang akan dibayar</p>
            </div>
        </div>
    </div>

    @if(session('error'))
    <div class="mx-4 mb-6">
        <div class="bg-red-50 border border-red-200 rounded-xl p-4">
            <p class="text-red-700 text-sm">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    @if(session('success'))
    <div class="mx-4 mb-6">
        <div class="bg-green-50 border border-green-200 rounded-xl p-4">
            <p class="text-green-700 text-sm">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <!-- Form Pembayaran -->
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-lg font-bold text-[#8b6f2f] mb-6 font-display">Form Pembayaran Pinjaman</h2>
            
            <form action="{{ route('nasabah.pinjaman.pembayaran') }}" method="GET" class="mb-6">
                <!-- Pilih Pinjaman -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Pinjaman</label>
                    <select name="pinjaman_id" id="pinjaman_id" 
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 transition-all"
                        onchange="this.form.submit()" required>
                        <option value="">-- Pilih Pinjaman --</option>
                        @foreach($pinjamanAktif ?? [] as $pj)
                        <option value="{{ $pj->id }}" {{ request('pinjaman_id') == $pj->id ? 'selected' : '' }}>
                            Pinjaman #{{ str_pad($pj->id, 6, '0', STR_PAD_LEFT) }} - 
                            Rp {{ number_format($pj->jumlah_pinjam, 0, ',', '.') }} 
                            ({{ ucfirst($pj->jenis) }})
                        </option>
                        @endforeach
                    </select>
                </div>
            </form>

            @if($selectedPinjaman)
            <!-- Pilih Angsuran -->
            <form action="{{ route('nasabah.pinjaman.pembayaran') }}" method="GET" class="mb-6">
                <input type="hidden" name="pinjaman_id" value="{{ $selectedPinjaman->id }}">
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Angsuran</label>
                    <select name="tempo_id" id="tempo_id" 
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 transition-all"
                        onchange="this.form.submit()" required>
                        <option value="">-- Pilih Angsuran --</option>
                        @foreach($angsuranList ?? [] as $angs)
                        @php
                            $sisa = max(0, $angs->jumlah_tagihan - ($angs->jumlah_terbayar ?? 0));
                            $denda = 0;
                            if ($angs->status_bayar !== 'lunas' && $angs->tgl_jatuh_tempo < now()) {
                                $hariTelat = now()->diffInDays($angs->tgl_jatuh_tempo, false);
                                if ($hariTelat > 0) {
                                    $dendaPersen = $selectedPinjaman->denda_persen ?? 0.02;
                                    $denda = $sisa * ($dendaPersen / 100) * $hariTelat;
                                    $dendaMax = $angs->jumlah_tagihan * 0.5;
                                    $denda = min($denda, $dendaMax);
                                }
                            }
                            $total = $sisa + $denda;
                        @endphp
                        <option value="{{ $angs->id }}" {{ request('tempo_id') == $angs->id ? 'selected' : '' }}>
                            Angsuran ke-{{ $angs->no_urut }} - 
                            Jatuh Tempo: {{ $angs->tgl_jatuh_tempo->format('d M Y') }} - 
                            Sisa: Rp {{ number_format($total, 0, ',', '.') }}
                            @if($denda > 0) (Denda: Rp {{ number_format($denda, 0, ',', '.') }}) @endif
                        </option>
                        @endforeach
                    </select>
                </div>
            </form>

            @if($selectedAngsuran)
            @php
                $sisaTagihan = max(0, $selectedAngsuran->jumlah_tagihan - ($selectedAngsuran->jumlah_terbayar ?? 0));
                $hariTelat = $selectedAngsuran->tgl_jatuh_tempo < now() && $selectedAngsuran->status_bayar !== 'lunas' 
                    ? now()->diffInDays($selectedAngsuran->tgl_jatuh_tempo, false) 
                    : 0;
                $denda = 0;
                if ($hariTelat > 0) {
                    $dendaPersen = $selectedPinjaman->denda_persen ?? 0.02;
                    $denda = $sisaTagihan * ($dendaPersen / 100) * $hariTelat;
                    $dendaMax = $selectedAngsuran->jumlah_tagihan * 0.5;
                    $denda = min($denda, $dendaMax);
                }
                $totalBayar = $sisaTagihan + $denda;
            @endphp

            <!-- Informasi Angsuran -->
            <div class="mb-6 p-6 bg-gradient-to-br from-[#8b6f2f]/10 to-[#d4af37]/10 rounded-xl border border-[#8b6f2f]/20">
                <h3 class="text-sm font-semibold text-[#8b6f2f] mb-4">Informasi Angsuran</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Jatuh Tempo:</span>
                        <span class="font-semibold text-gray-900">{{ $selectedAngsuran->tgl_jatuh_tempo->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Jumlah Tagihan:</span>
                        <span class="font-semibold text-gray-900">Rp {{ number_format($selectedAngsuran->jumlah_tagihan, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Terbayar:</span>
                        <span class="font-semibold text-green-600">Rp {{ number_format($selectedAngsuran->jumlah_terbayar ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Sisa Tagihan:</span>
                        <span class="font-semibold text-gray-900">Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</span>
                    </div>
                    @if($denda > 0)
                    <div class="flex justify-between">
                        <span class="text-sm text-red-600">Denda ({{ $hariTelat }} hari):</span>
                        <span class="font-semibold text-red-600">Rp {{ number_format($denda, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between pt-2 border-t border-gray-300">
                        <span class="font-semibold text-[#8b6f2f]">Total yang harus dibayar:</span>
                        <span class="text-xl font-bold text-[#8b6f2f]">Rp {{ number_format($totalBayar, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Tabs: Transfer / Cash -->
            <div class="mb-6">
                <div class="flex border-b border-gray-200">
                    <button type="button" onclick="showTransferForm()" id="tab-transfer" 
                        class="flex-1 px-4 py-3 font-semibold text-gray-700 border-b-2 border-[#8b6f2f] transition-colors">
                        Transfer
                    </button>
                    <button type="button" onclick="showCashForm()" id="tab-cash"
                        class="flex-1 px-4 py-3 font-semibold text-gray-500 hover:text-gray-700 transition-colors">
                        Janji Temu (Cash)
                    </button>
                </div>
            </div>

            <!-- Form Transfer -->
            <div id="form-transfer-section">
                <form action="{{ route('nasabah.pinjaman.submit-pembayaran-transfer') }}" method="POST" enctype="multipart/form-data" id="form-transfer">
                    @csrf
                    <input type="hidden" name="pinjaman_id" value="{{ $selectedPinjaman->id }}">
                    <input type="hidden" name="tempo_id" value="{{ $selectedAngsuran->id }}">
                    <input type="hidden" name="jenis_tempo" value="{{ $selectedPinjaman->jenis }}">
                    <input type="hidden" name="pin" id="pin-transfer">
                    
                    <!-- Input Nominal Pembayaran -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nominal Pembayaran *</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                            <input type="text" name="nominal_display" id="nominal-transfer" required
                                class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 transition-all"
                                placeholder="Masukkan nominal pembayaran">
                            <input type="hidden" name="nominal" id="nominal-transfer-raw">
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            Minimal: Rp 1 | Maksimal: Rp {{ number_format($totalBayar, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-yellow-600 mt-1">
                            💡 Anda bisa membayar sebagian (minimal Rp 1). Denda akan berhenti setelah ada pembayaran pertama.
                        </p>
                    </div>

                    <!-- Rekening Tujuan -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Rekening Tujuan *</label>
                        <input type="text" name="rekening_tujuan" required
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 transition-all"
                            placeholder="Masukkan rekening tujuan transfer">
                    </div>

                    <!-- Upload Bukti Transfer -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Bukti Transfer *</label>
                        <div id="bukti-container" class="space-y-3">
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-[#8b6f2f] transition-colors cursor-pointer" onclick="addBuktiField()">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="text-sm text-gray-600">Klik untuk tambah bukti transfer</p>
                                <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG (Max 5MB per file)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Keterangan -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan (Opsional)</label>
                        <textarea name="keterangan" rows="3"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 transition-all"
                            placeholder="Tambahkan keterangan jika diperlukan"></textarea>
                    </div>

                    <button type="button" onclick="showPinModal('transfer')" 
                        class="w-full bg-gradient-to-r from-[#8b6f2f] to-[#a0824d] text-white font-semibold py-3 rounded-xl hover:shadow-lg transition-all">
                        Ajukan Pembayaran
                    </button>
                </form>
            </div>

            <!-- Form Cash / Janji Temu -->
            <div id="form-cash-section" class="hidden">
                <form action="{{ route('nasabah.pinjaman.submit-janji-temu-pembayaran') }}" method="POST" id="form-cash">
                    @csrf
                    <input type="hidden" name="pinjaman_id" value="{{ $selectedPinjaman->id }}">
                    <input type="hidden" name="tempo_id" value="{{ $selectedAngsuran->id }}">
                    <input type="hidden" name="jenis_tempo" value="{{ $selectedPinjaman->jenis }}">
                    <input type="hidden" name="pin" id="pin-cash">
                    
                    <!-- Input Nominal Pembayaran -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nominal Pembayaran *</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                            <input type="text" name="nominal_display" id="nominal-cash" required
                                class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 transition-all"
                                placeholder="Masukkan nominal pembayaran">
                            <input type="hidden" name="nominal" id="nominal-cash-raw">
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            Minimal: Rp 1 | Maksimal: Rp {{ number_format($totalBayar, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-yellow-600 mt-1">
                            💡 Anda bisa membayar sebagian (minimal Rp 1). Denda akan berhenti setelah ada pembayaran pertama.
                        </p>
                    </div>

                    <!-- Pilih Lokasi -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Lokasi Kantor *</label>
                        <select name="lokasi_temu" required
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 transition-all">
                            <option value="">-- Pilih Lokasi --</option>
                            @foreach($lokasi ?? [] as $loc)
                            <option value="{{ $loc->id }}">{{ $loc->nama_lokasi }} - {{ $loc->kota }}, {{ $loc->provinsi }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tanggal Janji Temu -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Janji Temu *</label>
                        <input type="date" name="tanggal_janji_temu" required min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 transition-all">
                    </div>

                    <!-- Waktu Janji Temu -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Waktu Janji Temu *</label>
                        <input type="time" name="waktu_janji_temu" required
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 transition-all">
                    </div>

                    <!-- Keterangan -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan (Opsional)</label>
                        <textarea name="keterangan" rows="3"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 transition-all"
                            placeholder="Tambahkan keterangan jika diperlukan"></textarea>
                    </div>

                    <button type="button" onclick="showPinModal('cash')" 
                        class="w-full bg-gradient-to-r from-[#8b6f2f] to-[#a0824d] text-white font-semibold py-3 rounded-xl hover:shadow-lg transition-all">
                        Ajukan Janji Temu
                    </button>
                </form>
            </div>
            @endif
            @endif
        </div>
    </div>
</div>

<!-- PIN Modal -->
<div id="pin-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-900">Verifikasi PIN</h3>
            <button onclick="closePinModal()" class="text-gray-400 hover:text-gray-600">
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
            <input type="text" id="pin-input" maxlength="6" pattern="[0-9]*" inputmode="numeric"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 outline-none text-center text-2xl font-mono tracking-widest"
                placeholder="••••••"
                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
        </div>

        <div class="flex gap-3">
            <button onclick="closePinModal()" class="flex-1 px-4 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50">
                Batal
            </button>
            <button onclick="verifyAndSubmit()" class="flex-1 px-4 py-3 bg-gradient-to-r from-[#8b6f2f] to-[#a0824d] text-white rounded-xl font-semibold">
                Verifikasi
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
let buktiCount = 0;
let currentFormType = 'transfer';
const maxNominal = {{ $totalBayar }};

// Format Rupiah untuk nominal transfer
const nominalTransfer = document.getElementById('nominal-transfer');
const nominalTransferRaw = document.getElementById('nominal-transfer-raw');

if (nominalTransfer) {
    nominalTransfer.addEventListener('input', function(e) {
        let value = this.value.replace(/[^0-9]/g, '');
        if (value) {
            value = parseInt(value).toLocaleString('id-ID');
            this.value = value;
            nominalTransferRaw.value = value.replace(/\./g, '');
        } else {
            this.value = '';
            nominalTransferRaw.value = '';
        }
    });
    
    nominalTransfer.addEventListener('keypress', function(e) {
        const charCode = (e.which) ? e.which : e.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            e.preventDefault();
        }
    });
}

// Format Rupiah untuk nominal cash
const nominalCash = document.getElementById('nominal-cash');
const nominalCashRaw = document.getElementById('nominal-cash-raw');

if (nominalCash) {
    nominalCash.addEventListener('input', function(e) {
        let value = this.value.replace(/[^0-9]/g, '');
        if (value) {
            value = parseInt(value).toLocaleString('id-ID');
            this.value = value;
            nominalCashRaw.value = value.replace(/\./g, '');
        } else {
            this.value = '';
            nominalCashRaw.value = '';
        }
    });
    
    nominalCash.addEventListener('keypress', function(e) {
        const charCode = (e.which) ? e.which : e.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            e.preventDefault();
        }
    });
}

function showTransferForm() {
    document.getElementById('form-transfer-section').classList.remove('hidden');
    document.getElementById('form-cash-section').classList.add('hidden');
    document.getElementById('tab-transfer').classList.add('border-[#8b6f2f]', 'text-gray-700');
    document.getElementById('tab-transfer').classList.remove('text-gray-500');
    document.getElementById('tab-cash').classList.remove('border-[#8b6f2f]', 'text-gray-700');
    document.getElementById('tab-cash').classList.add('text-gray-500');
    currentFormType = 'transfer';
}

function showCashForm() {
    document.getElementById('form-transfer-section').classList.add('hidden');
    document.getElementById('form-cash-section').classList.remove('hidden');
    document.getElementById('tab-cash').classList.add('border-[#8b6f2f]', 'text-gray-700');
    document.getElementById('tab-cash').classList.remove('text-gray-500');
    document.getElementById('tab-transfer').classList.remove('border-[#8b6f2f]', 'text-gray-700');
    document.getElementById('tab-transfer').classList.add('text-gray-500');
    currentFormType = 'cash';
}

function addBuktiField() {
    buktiCount++;
    const container = document.getElementById('bukti-container');
    const div = document.createElement('div');
    div.className = 'border-2 border-gray-200 rounded-xl p-4 space-y-3';
    div.innerHTML = `
        <div class="flex items-center justify-between mb-2">
            <label class="text-sm font-semibold text-gray-700">Bukti Transfer ${buktiCount}</label>
            <button type="button" onclick="this.parentElement.parentElement.remove();" class="text-red-600 hover:text-red-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <input type="file" name="bukti_foto[]" accept="image/*" required 
            class="w-full px-4 py-2 border border-gray-300 rounded-lg">
    `;
    container.insertBefore(div, container.firstChild);
}

function showPinModal(formType) {
    // Validate form first
    const form = formType === 'transfer' ? document.getElementById('form-transfer') : document.getElementById('form-cash');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    // Validate nominal
    const nominalRaw = formType === 'transfer' 
        ? document.getElementById('nominal-transfer-raw').value 
        : document.getElementById('nominal-cash-raw').value;
    
    const nominal = parseFloat(nominalRaw) || 0;
    
    if (nominal < 1) {
        alert('Nominal pembayaran minimal Rp 1');
        return;
    }
    
    if (nominal > maxNominal) {
        alert('Nominal pembayaran maksimal Rp ' + maxNominal.toLocaleString('id-ID'));
        return;
    }
    
    currentFormType = formType;
    document.getElementById('pin-modal').classList.remove('hidden');
    document.getElementById('pin-modal').classList.add('flex');
    document.getElementById('pin-input').value = '';
    document.getElementById('pin-error').classList.add('hidden');
    document.getElementById('pin-input').focus();
}

function closePinModal() {
    document.getElementById('pin-modal').classList.add('hidden');
    document.getElementById('pin-modal').classList.remove('flex');
}

function verifyAndSubmit() {
    const pin = document.getElementById('pin-input').value;
    
    if (pin.length !== 6) {
        showPinError('PIN harus 6 digit');
        return;
    }

    // Add PIN to form
    if (currentFormType === 'transfer') {
        document.getElementById('pin-transfer').value = pin;
        closePinModal();
        document.getElementById('form-transfer').submit();
    } else {
        document.getElementById('pin-cash').value = pin;
        closePinModal();
        document.getElementById('form-cash').submit();
    }
}

function showPinError(message) {
    const errorDiv = document.getElementById('pin-error');
    errorDiv.querySelector('p').textContent = message;
    errorDiv.classList.remove('hidden');
}
</script>
@endpush
@endsection
