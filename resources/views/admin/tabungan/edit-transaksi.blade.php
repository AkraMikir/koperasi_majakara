@extends('layouts.admin')

@section('title', 'Edit Transaksi')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Edit Transaksi</h1>
            <p class="text-gray-600 mt-1">ID: {{ $transaksi->id_transaksi ?? $transaksi->id }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.tabungan.detail-transaksi', $transaksi->id) }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium">
                ← Kembali
            </a>
        </div>
    </div>

    <!-- Alert Info -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <p class="text-sm font-semibold text-blue-700">Informasi Penting</p>
                <p class="text-sm text-blue-600 mt-1">Hanya transaksi yang dibuat manual yang dapat diedit. Jenis akun, nasabah, dan jenis transaksi tidak dapat diubah.</p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <form method="POST" action="{{ route('admin.tabungan.update-transaksi', $transaksi->id) }}" enctype="multipart/form-data" class="space-y-6" id="edit-form">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Info Nasabah (readonly) -->
                <div class="md:col-span-2 p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <p class="text-sm font-semibold text-gray-700 mb-2">Nasabah:</p>
                    <p class="font-bold text-gray-900">{{ $transaksi->nasabah->user->nama ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-600">{{ $transaksi->nasabah->user->email ?? '' }}</p>
                </div>

                <!-- Info Jenis dan Via (readonly) -->
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <p class="text-sm font-semibold text-gray-700 mb-2">Jenis Transaksi:</p>
                    <span class="inline-block px-3 py-1 {{ $transaksi->jenis == 'setoran' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} rounded-full text-sm font-semibold">
                        {{ ucfirst($transaksi->jenis) }}
                    </span>
                </div>

                <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <p class="text-sm font-semibold text-gray-700 mb-2">Via:</p>
                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                        {{ ucfirst($transaksi->via) }}
                    </span>
                </div>

                <!-- Nominal (editable) -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nominal *</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                        <input type="text" name="nominal" id="nominal" 
                            value="{{ number_format($transaksi->nominal, 0, '.', '') }}" required
                            class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none text-lg font-semibold"
                            oninput="formatCurrency(this)">
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Minimal: Rp 10.000</p>
                </div>

                <!-- Tanggal Transaksi (editable) -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Transaksi *</label>
                    <input type="datetime-local" name="tgl_transaksi" 
                        value="{{ $transaksi->tgl_transaksi->format('Y-m-d\TH:i') }}" required
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none">
                </div>

                <!-- Keterangan (editable - BERSIH TANPA PATH FOTO) -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan</label>
                    <textarea name="keterangan" rows="3" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none resize-none" placeholder="Tambahkan keterangan...">{{ $transaksi->keterangan }}</textarea>
                </div>

                <!-- Preview Bukti Foto yang Sudah Ada -->
                @php
                    $buktiFoto = $transaksi->buktiFoto ?? collect();
                @endphp
                @if($buktiFoto->count() > 0)
                <div class="md:col-span-2 p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <p class="text-sm font-semibold text-gray-700 mb-3">Bukti Transaksi:</p>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($buktiFoto as $foto)
                        <div class="relative group">
                            <img src="{{ Storage::url($foto->file_path) }}" alt="Bukti Transfer" 
                                class="w-full h-32 object-cover rounded-lg border-2 border-gray-200 shadow-sm cursor-pointer hover:border-[#674c1d] transition-all"
                                onclick="window.open('{{ Storage::url($foto->file_path) }}', '_blank')">
                            <span class="absolute top-2 right-2 bg-black/60 text-white text-xs px-2 py-1 rounded-full">
                                {{ $loop->iteration }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Upload Bukti Foto Baru (Multiple) -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Bukti Baru (Opsional)</label>
                    <input type="file" name="foto_bukti[]" accept="image/jpeg,image/png,image/jpg" multiple
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#674c1d] file:text-white hover:file:bg-[#4a3514] file:cursor-pointer"
                        onchange="previewMultipleFoto(this)">
                    <p class="text-xs text-gray-500 mt-1">Bisa upload lebih dari 1 foto. Foto baru akan ditambahkan ke bukti yang sudah ada.</p>
                    <div id="foto-preview" class="hidden mt-3 grid grid-cols-2 md:grid-cols-3 gap-3"></div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.tabungan.detail-transaksi', $transaksi->id) }}" class="flex-1 px-4 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-colors text-center">
                    Batal
                </a>
                <button type="submit" class="flex-1 px-4 py-3 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-semibold hover:from-[#4a3514] hover:to-[#674c1d] transition-all">
                    Update Transaksi
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function formatCurrency(input) {
        let value = input.value.replace(/[^\d]/g, '');
        if (value) {
            input.value = new Intl.NumberFormat('id-ID').format(value);
        }
    }

    function previewMultipleFoto(input) {
        const preview = document.getElementById('foto-preview');
        preview.innerHTML = ''; // Clear previous previews
        
        if (input.files && input.files.length > 0) {
            preview.classList.remove('hidden');
            
            Array.from(input.files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative group';
                    div.innerHTML = `
                        <img src="${e.target.result}" alt="Preview ${index + 1}" 
                            class="w-full h-32 object-cover rounded-lg border-2 border-gray-200 shadow-sm group-hover:border-[#674c1d] transition-all">
                        <span class="absolute top-2 left-2 bg-green-600 text-white text-xs px-2 py-1 rounded-full">
                            Baru ${index + 1}
                        </span>
                    `;
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        } else {
            preview.classList.add('hidden');
        }
    }

    // Convert formatted currency back to number before submit
    document.getElementById('edit-form').addEventListener('submit', function(e) {
        const nominalInput = document.getElementById('nominal');
        if (nominalInput) {
            nominalInput.value = nominalInput.value.replace(/[^\d]/g, '');
        }
    });
</script>
@endpush
@endsection
