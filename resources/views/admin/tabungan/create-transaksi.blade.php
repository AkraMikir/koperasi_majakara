@extends('layouts.admin')

@section('title', 'Buat Transaksi Manual')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Buat Transaksi Manual</h1>
            <p class="text-gray-600 mt-1">Input transaksi tabungan secara manual</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.tabungan.transaksi') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium">
                ← Kembali
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <form method="POST" action="{{ route('admin.tabungan.store-transaksi') }}" enctype="multipart/form-data" class="space-y-6" id="transaksi-form">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nasabah -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nasabah *</label>
                    <select name="id_anggota" id="id_anggota" required 
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none"
                        onchange="updateSaldoInfo()">
                        <option value="">Pilih Nasabah</option>
                        @foreach($nasabah as $n)
                        <option value="{{ $n->id }}" data-saldo="{{ $n->id }}">{{ $n->user->nama ?? 'N/A' }} - {{ $n->user->email ?? '' }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Jenis Akun -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Akun *</label>
                    <select name="id_jns_akun" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none">
                        <option value="">Pilih Jenis Akun</option>
                        @foreach($jnsAkun as $akun)
                        <option value="{{ $akun->id }}">{{ $akun->nama_akun }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Jenis Transaksi -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Transaksi *</label>
                    <select name="jenis" id="jenis" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none"
                        onchange="updateSaldoInfo()">
                        <option value="setoran">Setoran</option>
                        <option value="penarikan">Penarikan</option>
                    </select>
                </div>

                <!-- Nominal -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nominal *</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                        <input type="text" name="nominal" id="nominal" placeholder="0" required
                            class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none text-lg font-semibold"
                            oninput="formatCurrency(this); checkSaldo();">
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Minimal: Rp 10.000</p>
                    <div id="saldo-warning" class="hidden mt-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-sm text-red-600 font-medium">⚠ Saldo nasabah tidak mencukupi!</p>
                    </div>
                </div>

                <!-- Via -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Via *</label>
                    <select name="via" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none">
                        <option value="transfer">Transfer</option>
                        <option value="cash">Cash</option>
                    </select>
                </div>

                <!-- Tanggal Transaksi -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Transaksi *</label>
                    <input type="datetime-local" name="tgl_transaksi" value="{{ now()->format('Y-m-d\TH:i') }}" required
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none">
                </div>

                <!-- Keterangan -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan</label>
                    <textarea name="keterangan" rows="3" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none resize-none" placeholder="Tambahkan keterangan..."></textarea>
                </div>

                <!-- Upload Foto Bukti -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Bukti (Opsional)</label>
                    <input type="file" name="foto_bukti" accept="image/jpeg,image/png,image/jpg"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#674c1d] file:text-white hover:file:bg-[#4a3514] file:cursor-pointer"
                        onchange="previewFoto(this)">
                    <div id="foto-preview" class="hidden mt-3">
                        <img src="" alt="Preview" class="max-w-full max-h-48 rounded-lg border border-gray-200 shadow-sm">
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.tabungan.transaksi') }}" class="flex-1 px-4 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-colors text-center">
                    Batal
                </a>
                <button type="submit" id="submit-btn" class="flex-1 px-4 py-3 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-semibold hover:from-[#4a3514] hover:to-[#674c1d] transition-all">
                    Buat Transaksi
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Saldo data (akan di-load via AJAX atau embed)
    const saldoData = {};

    function formatCurrency(input) {
        let value = input.value.replace(/[^\d]/g, '');
        if (value) {
            input.value = new Intl.NumberFormat('id-ID').format(value);
        }
    }

    function previewFoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            const preview = document.getElementById('foto-preview');
            
            reader.onload = function(e) {
                preview.querySelector('img').src = e.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    async function updateSaldoInfo() {
        const nasabahId = document.getElementById('id_anggota').value;
        if (!nasabahId) return;

        // TODO: Fetch saldo via AJAX
        // For now, just enable/disable based on jenis
    }

    function checkSaldo() {
        // Will be implemented with AJAX to check realtime saldo
    }

    // Convert formatted currency back to number before submit
    document.getElementById('transaksi-form').addEventListener('submit', function(e) {
        const nominalInput = document.getElementById('nominal');
        if (nominalInput) {
            nominalInput.value = nominalInput.value.replace(/[^\d]/g, '');
        }
    });
</script>
@endpush
@endsection
