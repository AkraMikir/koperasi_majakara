@extends('layouts.nasabah')

@section('title', 'Nabung Sekarang')

@section('content')
<div class="w-full pb-6">
    <!-- Pilihan Metode -->
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-[#674c1d] font-display">Pilih Metode Setoran</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Metode Tunai -->
                <button onclick="selectMethod('tunai')" id="btn-tunai" class="group p-6 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl border-2 border-gray-200 hover:border-[#674c1d] transition-all text-left">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-[#674c1d] to-[#4a3514] rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">Tunai</h3>
                            <p class="text-sm text-gray-600">Setor langsung di kantor</p>
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
                <button onclick="selectMethod('transfer')" id="btn-transfer" class="group p-6 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl border-2 border-gray-200 hover:border-[#8b6f2f] transition-all text-left">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-[#8b6f2f] to-[#d4af37] rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">Transfer</h3>
                            <p class="text-sm text-gray-600">Transfer via bank/mobile banking</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <span>Upload bukti transfer</span>
                    </div>
                </button>
            </div>
        </div>
    </div>

    <!-- Form Section (akan muncul setelah pilih metode) -->
    <div id="form-section" class="mx-4 mb-6 hidden">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-[#8b6f2f] to-[#d4af37] rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-[#674c1d] font-display">Formulir Setoran</h2>
            </div>

            <form id="form-setoran" method="POST" action="{{ route('nasabah.tabungan.submit-setoran') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="metode" id="metode-input" value="">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nominal Setoran</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                        <input type="text" name="nominal" id="nominal" placeholder="0" 
                            class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none text-lg font-semibold"
                            oninput="formatCurrency(this)">
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Minimal setoran: Rp 10.000</p>
                </div>

                        <div id="bukti-section" class="hidden">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Bukti Transfer</label>
                    <div id="bukti-container" class="space-y-3">
                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-[#674c1d] transition-colors cursor-pointer" onclick="addBuktiField()">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <p class="text-sm text-gray-600">Klik untuk tambah bukti transfer</p>
                            <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG (Max 5MB)</p>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan (Opsional)</label>
                    <textarea name="keterangan" rows="3" placeholder="Tambahkan keterangan jika diperlukan..."
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none resize-none"></textarea>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-4 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.02]">
                        Ajukan Setoran
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Riwayat Setoran -->
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-[#674c1d] font-display">Riwayat Setoran</h2>
                </div>
            </div>
            
            <div class="space-y-3">
                @forelse($riwayatTabungan ?? [] as $riwayat)
                <a href="{{ route('nasabah.tabungan.detail-transaksi', $riwayat->id) }}" class="block p-4 bg-gray-50 rounded-xl border border-gray-200 hover:border-[#674c1d]/30 hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Setoran</p>
                                <p class="text-sm text-gray-500">{{ $riwayat->tgl_transaksi->format('d M Y') }} • {{ ucfirst($riwayat->via) }}</p>
                            </div>
                        </div>
                        <div class="text-right flex items-center gap-2">
                            <div>
                                <p class="font-bold text-green-600">+Rp {{ number_format($riwayat->nominal, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-500 font-mono">TRX{{ str_pad($riwayat->id, 3, '0', STR_PAD_LEFT) }}</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </a>
                @empty
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-gray-500">Belum ada riwayat setoran</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let selectedMethod = null;

    let buktiCount = 0;

    function selectMethod(method) {
        selectedMethod = method;
        document.getElementById('metode-input').value = method;
        
        // Update button styles
        document.querySelectorAll('[id^="btn-"]').forEach(btn => {
            btn.classList.remove('border-[#674c1d]', 'border-[#8b6f2f]', 'bg-gradient-to-br', 'from-[#674c1d]/10', 'to-[#8b6f2f]/10');
            btn.classList.add('border-gray-200');
        });
        
        if (method === 'tunai') {
            document.getElementById('btn-tunai').classList.add('border-[#674c1d]', 'bg-gradient-to-br', 'from-[#674c1d]/10', 'to-[#8b6f2f]/10');
            document.getElementById('bukti-section').classList.add('hidden');
        } else {
            document.getElementById('btn-transfer').classList.add('border-[#8b6f2f]', 'bg-gradient-to-br', 'from-[#8b6f2f]/10', 'to-[#d4af37]/10');
            document.getElementById('bukti-section').classList.remove('hidden');
        }
        
        document.getElementById('form-section').classList.remove('hidden');
        document.getElementById('form-section').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function addBuktiField() {
        buktiCount++;
        const container = document.getElementById('bukti-container');
        const div = document.createElement('div');
        div.className = 'border-2 border-gray-200 rounded-xl p-4 space-y-3';
        div.innerHTML = `
            <div class="flex items-center justify-between mb-2">
                <label class="text-sm font-semibold text-gray-700">Bukti Transfer ${buktiCount}</label>
                <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-red-600 hover:text-red-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <input type="file" name="bukti_foto[]" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required onchange="previewBukti(this)">
            <input type="text" name="nominal_foto[]" placeholder="Nominal (Rp)" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required oninput="formatCurrency(this)">
            <textarea name="keterangan_foto[]" rows="2" placeholder="Keterangan" class="w-full px-4 py-2 border border-gray-300 rounded-lg"></textarea>
            <div class="bukti-preview hidden">
                <img src="" alt="Preview" class="max-w-full max-h-32 rounded-lg">
            </div>
        `;
        container.appendChild(div);
    }

    function previewBukti(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = input.parentElement.querySelector('.bukti-preview');
                preview.querySelector('img').src = e.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function formatCurrency(input) {
        let value = input.value.replace(/[^\d]/g, '');
        if (value) {
            input.value = new Intl.NumberFormat('id-ID').format(value);
        }
    }

    document.getElementById('form-setoran').addEventListener('submit', function(e) {
        if (!selectedMethod) {
            e.preventDefault();
            alert('Pilih metode setoran terlebih dahulu');
            return;
        }
        if (selectedMethod === 'transfer' && buktiCount === 0) {
            e.preventDefault();
            alert('Minimal upload 1 bukti transfer');
            return;
        }
    });
</script>
@endpush
@endsection
