@extends('layouts.nasabah')

@section('title', 'Penarikan Tabungan')

@section('content')
<div class="w-full pb-6">
    <!-- Info Saldo -->
    <div class="mx-4 mt-4 mb-6">
        <div class="bg-gradient-to-br from-[#674c1d] via-[#8b6f2f] to-[#d4af37] rounded-3xl shadow-2xl p-6 border-2 border-[#d4af37]/30">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/80 text-sm mb-2">Saldo Tersedia</p>
                    <p class="text-4xl font-bold text-white font-display">Rp {{ number_format($tabunganInfo->saldo ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    
    <div id="saldo-data" data-saldo="{{ $tabunganInfo->saldo ?? 0 }}" style="display: none;"></div>

    <!-- Pilihan Metode -->
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-lg font-bold text-gray-900 font-display mb-4">Pilih Metode Penarikan</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Metode Tunai -->
                <button onclick="selectMethod('tunai')" id="btn-tunai" class="group p-6 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl border-2 border-gray-200 hover:border-[#8b6f2f] transition-all text-left">
                    <div class="flex items-center gap-4 mb-3">
                        <div class="w-14 h-14 bg-gradient-to-br from-[#8b6f2f] to-[#d4af37] rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">Tunai</h3>
                            <p class="text-sm text-gray-600">Ambil langsung di kantor</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>Datang ke kantor terdekat</span>
                    </div>
                </button>

                <!-- Metode Transfer -->
                <button onclick="selectMethod('transfer')" id="btn-transfer" class="group p-6 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl border-2 border-gray-200 hover:border-[#674c1d] transition-all text-left">
                    <div class="flex items-center gap-4 mb-3">
                        <div class="w-14 h-14 bg-gradient-to-br from-[#674c1d] to-[#4a3514] rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">Transfer</h3>
                            <p class="text-sm text-gray-600">Transfer ke rekening Anda</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        <span>Masukkan data rekening</span>
                    </div>
                </button>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div id="form-section" class="mx-4 mb-6 hidden">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-lg font-bold text-gray-900 font-display mb-6">Formulir Penarikan</h2>
                
            <form id="form-penarikan" method="POST" action="{{ route('nasabah.tabungan.submit-penarikan') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="metode" id="metode-input" value="">

                <!-- Nominal Penarikan -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nominal Penarikan *</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                        <input type="text" name="nominal" id="nominal" placeholder="0" required
                            class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none text-lg font-semibold"
                            oninput="formatCurrency(this)" onblur="checkSaldo()">
                    </div>
                    <div id="saldo-warning" class="hidden mt-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <p class="text-sm text-red-600 font-medium">Saldo tidak mencukupi!</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Minimal: Rp 10.000 | Saldo tersedia: Rp {{ number_format($tabunganInfo->saldo ?? 0, 0, ',', '.') }}</p>
                </div>

                <!-- Bank Details (for Transfer) -->
                <div id="bank-section" class="hidden space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Bank *</label>
                        <select name="nama_bank" id="nama_bank" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none">
                            <option value="">Pilih Bank</option>
                            <option value="BCA">BCA</option>
                            <option value="BNI">BNI</option>
                            <option value="Mandiri">Mandiri</option>
                            <option value="BRI">BRI</option>
                            <option value="CIMB Niaga">CIMB Niaga</option>
                            <option value="Permata">Permata</option>
                            <option value="Bank Lainnya">Bank Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Rekening *</label>
                        <input type="text" name="no_rekening" id="no_rekening" placeholder="Masukkan nomor rekening tujuan"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        <p class="text-xs text-gray-500 mt-2">Pastikan nomor rekening sudah benar</p>
                    </div>
                </div>

                <!-- Keterangan -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan (Opsional)</label>
                    <textarea name="keterangan" rows="3" placeholder="Tambahkan keterangan jika diperlukan..."
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none resize-none"></textarea>
                </div>
                
                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" id="submit-btn" class="w-full py-4 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed">
                        Ajukan Penarikan
                    </button>
                    <a href="{{ route('nasabah.tabungan.index') }}" class="block w-full mt-3 py-3 text-center text-gray-600 hover:text-gray-800 transition-colors">
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Riwayat Penarikan -->
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-gray-900 font-display">Riwayat Penarikan</h2>
            </div>
            
            <div class="space-y-3">
                @forelse($riwayatPenarikan ?? [] as $riwayat)
                <a href="{{ route('nasabah.tabungan.detail-transaksi', $riwayat->id) }}" class="block p-4 bg-gray-50 rounded-xl border border-gray-200 hover:border-red-300 hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Penarikan</p>
                                <p class="text-sm text-gray-500">{{ $riwayat->tgl_transaksi->format('d M Y') }} • {{ $riwayat->via ? ucfirst($riwayat->via) : '-' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-red-600">-Rp {{ number_format(abs((float) $riwayat->nominal), 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500 font-mono">{{ $riwayat->id_transaksi ?? 'TRX-' . str_pad($riwayat->id, 5, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>
                    @if($riwayat->keterangan)
                    <p class="text-sm text-gray-600 mt-2 pl-13">{{ $riwayat->keterangan }}</p>
                    @endif
                </a>
                @empty
                <div class="text-center py-8">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-gray-500">Belum ada riwayat penarikan</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function selectMethod(metode) {
        document.getElementById('metode-input').value = metode;
        document.getElementById('form-section').classList.remove('hidden');
        
        // Update button styles
        const btnTunai = document.getElementById('btn-tunai');
        const btnTransfer = document.getElementById('btn-transfer');
        
        btnTunai.classList.remove('border-[#8b6f2f]', 'bg-gradient-to-br', 'from-[#8b6f2f]/10', 'to-[#d4af37]/10');
        btnTransfer.classList.remove('border-[#674c1d]', 'bg-gradient-to-br', 'from-[#674c1d]/10', 'to-[#4a3514]/10');
        
        if (metode === 'tunai') {
            btnTunai.classList.add('border-[#8b6f2f]', 'bg-gradient-to-br', 'from-[#8b6f2f]/10', 'to-[#d4af37]/10');
            document.getElementById('bank-section').classList.add('hidden');
            document.getElementById('nama_bank').removeAttribute('required');
            document.getElementById('no_rekening').removeAttribute('required');
        } else {
            btnTransfer.classList.add('border-[#674c1d]', 'bg-gradient-to-br', 'from-[#674c1d]/10', 'to-[#4a3514]/10');
            document.getElementById('bank-section').classList.remove('hidden');
            document.getElementById('nama_bank').setAttribute('required', 'required');
            document.getElementById('no_rekening').setAttribute('required', 'required');
        }
        
        // Scroll to form
        document.getElementById('form-section').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function formatCurrency(input) {
        let value = input.value.replace(/[^\d]/g, '');
        if (value) {
            input.value = new Intl.NumberFormat('id-ID').format(value);
        }
    }

    function checkSaldo() {
        const nominalInput = document.getElementById('nominal');
        const nominal = parseInt(nominalInput.value.replace(/[^\d]/g, '')) || 0;
        const saldo = parseInt(document.getElementById('saldo-data').dataset.saldo) || 0;
        const warning = document.getElementById('saldo-warning');
        const submitBtn = document.getElementById('submit-btn');

        if (nominal > saldo) {
            warning.classList.remove('hidden');
            submitBtn.disabled = true;
        } else {
            warning.classList.add('hidden');
            submitBtn.disabled = false;
        }
    }

    // Convert formatted currency back to number before submit
    document.getElementById('form-penarikan').addEventListener('submit', function(e) {
        const nominalInput = document.getElementById('nominal');
        if (nominalInput) {
            nominalInput.value = nominalInput.value.replace(/[^\d]/g, '');
        }
    });
</script>
@endpush
@endsection
