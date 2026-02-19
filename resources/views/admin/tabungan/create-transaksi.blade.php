@extends('layouts.admin')

@section('title', 'Buat Transaksi Manual')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] rounded-xl flex items-center justify-center shadow-lg shadow-[#674c1d]/20">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 font-display">Buat Transaksi Manual</h1>
                <p class="text-gray-500 text-sm mt-0.5">Input transaksi tabungan secara manual</p>
            </div>
        </div>
        <a href="{{ route('admin.tabungan.transaksi') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border-2 border-gray-200 rounded-xl text-gray-700 hover:border-[#674c1d]/40 hover:bg-[#674c1d]/5 transition-colors text-sm font-semibold">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <form method="POST" action="{{ route('admin.tabungan.store-transaksi') }}" enctype="multipart/form-data" id="transaksi-form">
            @csrf

            {{-- Section: Nasabah --}}
            <div class="p-6 sm:p-8 border-b border-gray-100">
                <div class="flex items-center gap-2 mb-5">
                    <span class="w-8 h-8 rounded-lg bg-[#674c1d]/10 flex items-center justify-center">
                        <svg class="w-4 h-4 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </span>
                    <h2 class="text-lg font-bold text-gray-900">Pilih Nasabah</h2>
                </div>
                <div>
                    <label for="id_anggota" class="block text-sm font-medium text-gray-700 mb-2">Nasabah <span class="text-red-500">*</span></label>
                    <select name="id_anggota" id="id_anggota" required
                        class="w-full px-4 py-3 bg-gray-50/80 border-2 border-gray-200 rounded-xl focus:bg-white focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none transition-colors"
                        onchange="updateSaldoInfo()">
                        <option value="">— Pilih Nasabah —</option>
                        @foreach($nasabah as $n)
                        <option value="{{ $n->id }}" data-saldo="{{ $n->id }}">{{ $n->user->nama ?? 'N/A' }} — {{ $n->user->email ?? '' }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Section: Jenis & Metode --}}
            <div class="p-6 sm:p-8 border-b border-gray-100 bg-gray-50/30">
                <div class="flex items-center gap-2 mb-5">
                    <span class="w-8 h-8 rounded-lg bg-[#674c1d]/10 flex items-center justify-center">
                        <svg class="w-4 h-4 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    </span>
                    <h2 class="text-lg font-bold text-gray-900">Jenis & Metode Transaksi</h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="jenis" class="block text-sm font-medium text-gray-700 mb-2">Jenis Transaksi <span class="text-red-500">*</span></label>
                        <select name="jenis" id="jenis" required
                            class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none transition-colors"
                            onchange="updateSaldoInfo()">
                            <option value="setoran">Setoran</option>
                            <option value="penarikan">Penarikan</option>
                        </select>
                    </div>
                    <div>
                        <label for="via" class="block text-sm font-medium text-gray-700 mb-2">Via <span class="text-red-500">*</span></label>
                        <select name="via" required
                            class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none transition-colors">
                            <option value="transfer">Transfer</option>
                            <option value="cash">Tunai</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Section: Nominal & Waktu --}}
            <div class="p-6 sm:p-8 border-b border-gray-100">
                <div class="flex items-center gap-2 mb-5">
                    <span class="w-8 h-8 rounded-lg bg-[#674c1d]/10 flex items-center justify-center">
                        <svg class="w-4 h-4 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </span>
                    <h2 class="text-lg font-bold text-gray-900">Nominal & Waktu</h2>
                </div>
                <div class="space-y-6">
                    <div>
                        <label for="nominal" class="block text-sm font-medium text-gray-700 mb-2">Nominal <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">Rp</span>
                            <input type="text" name="nominal" id="nominal" placeholder="0" required
                                class="w-full pl-12 pr-4 py-3.5 bg-gray-50/80 border-2 border-gray-200 rounded-xl focus:bg-white focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none text-lg font-semibold text-gray-900 transition-colors"
                                oninput="formatCurrency(this); checkSaldo();">
                        </div>
                        <p class="text-xs text-gray-500 mt-2 flex items-center gap-1.5">
                            <span class="w-1 h-1 rounded-full bg-gray-400"></span>
                            Minimal Rp 10.000
                        </p>
                        <div id="saldo-warning" class="hidden mt-3 p-4 bg-red-50 border border-red-200 rounded-xl flex items-center gap-3">
                            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-sm text-red-700 font-medium">Saldo nasabah tidak mencukupi untuk penarikan ini.</p>
                        </div>
                    </div>
                    <div>
                        <label for="tgl_transaksi" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Transaksi <span class="text-red-500">*</span></label>
                        <input type="datetime-local" name="tgl_transaksi" id="tgl_transaksi" value="{{ now()->format('Y-m-d\TH:i') }}" required
                            class="w-full px-4 py-3 bg-gray-50/80 border-2 border-gray-200 rounded-xl focus:bg-white focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none transition-colors">
                    </div>
                </div>
            </div>

            {{-- Section: Keterangan & Lampiran --}}
            <div class="p-6 sm:p-8 bg-gray-50/30">
                <div class="flex items-center gap-2 mb-5">
                    <span class="w-8 h-8 rounded-lg bg-[#674c1d]/10 flex items-center justify-center">
                        <svg class="w-4 h-4 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </span>
                    <h2 class="text-lg font-bold text-gray-900">Keterangan & Lampiran</h2>
                </div>
                <div class="space-y-6">
                    <div>
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" rows="3"
                            class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none resize-none placeholder:text-gray-400 transition-colors"
                            placeholder="Tambahkan keterangan (opsional)..."></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Transfer <span class="text-gray-400 font-normal">(opsional)</span></label>
                        <div class="relative">
                            <input type="file" name="foto_bukti[]" accept="image/jpeg,image/png,image/jpg" multiple
                                class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-[#674c1d] file:text-white hover:file:bg-[#4a3514] file:cursor-pointer file:transition-colors rounded-xl border-2 border-gray-200 border-dashed bg-gray-50/50 p-4">
                        </div>
                        <p class="text-xs text-gray-500 mt-2 flex items-center gap-1.5">
                            <span class="w-1 h-1 rounded-full bg-gray-400"></span>
                            Maksimal beberapa file. 5MB per foto.
                        </p>
                        <div id="foto-preview" class="hidden mt-4 grid grid-cols-2 sm:grid-cols-3 gap-3"></div>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="px-6 sm:px-8 py-5 bg-white border-t border-gray-200 flex flex-col-reverse sm:flex-row gap-3">
                <a href="{{ route('admin.tabungan.transaksi') }}" class="flex-1 sm:flex-initial inline-flex justify-center items-center gap-2 px-6 py-3 border-2 border-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 hover:border-gray-300 transition-colors">
                    Batal
                </a>
                <button type="submit" id="submit-btn" class="flex-1 sm:flex-initial inline-flex justify-center items-center gap-2 px-8 py-3 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-semibold hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-lg shadow-[#674c1d]/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Buat Transaksi
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const saldoData = {};

    function formatCurrency(input) {
        let value = input.value.replace(/[^\d]/g, '');
        if (value) {
            input.value = new Intl.NumberFormat('id-ID').format(value);
        }
    }

    function previewMultipleFoto(input) {
        const preview = document.getElementById('foto-preview');
        preview.innerHTML = '';
        if (input.files && input.files.length > 0) {
            preview.classList.remove('hidden');
            Array.from(input.files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative group';
                    div.innerHTML = `
                        <img src="${e.target.result}" alt="Preview ${index + 1}"
                            class="w-full h-32 object-cover rounded-xl border-2 border-gray-200 shadow-sm group-hover:border-[#674c1d] transition-all">
                        <span class="absolute top-2 right-2 bg-black/60 text-white text-xs px-2 py-1 rounded-lg">${index + 1}</span>
                    `;
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        } else {
            preview.classList.add('hidden');
        }
    }

    document.querySelector('input[name="foto_bukti[]"]').addEventListener('change', function() {
        previewMultipleFoto(this);
    });

    async function updateSaldoInfo() {
        var nasabahId = document.getElementById('id_anggota').value;
        if (!nasabahId) return;
    }

    function checkSaldo() {}

    document.getElementById('transaksi-form').addEventListener('submit', function(e) {
        var nominalInput = document.getElementById('nominal');
        if (nominalInput) {
            nominalInput.value = nominalInput.value.replace(/[^\d]/g, '');
        }
    });
</script>
@endpush
@endsection
