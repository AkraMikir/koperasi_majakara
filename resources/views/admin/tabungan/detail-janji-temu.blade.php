@extends('layouts.admin')

@section('title', 'Detail Janji Temu')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Detail Janji Temu</h1>
            <p class="text-gray-600 mt-1">ID Janji Temu: #{{ $janjiTemu->id }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.tabungan.janji-temu') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium">
                ← Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Data Nasabah -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h2 class="text-lg font-bold text-primary font-display mb-4 pb-4 border-b border-gray-200">Data Nasabah</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Nama Lengkap</p>
                        <p class="font-semibold text-gray-900">{{ $janjiTemu->pengajuan->nasabah->user->nama ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-semibold text-gray-900">{{ $janjiTemu->pengajuan->nasabah->user->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nomor HP</p>
                        <p class="font-semibold text-gray-900">{{ $janjiTemu->pengajuan->nasabah->user->nomor_hp ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">NIK</p>
                        <p class="font-semibold text-gray-900">{{ $janjiTemu->pengajuan->nasabah->dataKtp->nik ?? 'N/A' }}</p>
                    </div>
                    @if($janjiTemu->pengajuan->nasabah->dataRek)
                    <div>
                        <p class="text-sm text-gray-600">No. Rekening</p>
                        <p class="font-semibold text-gray-900">{{ $janjiTemu->pengajuan->nasabah->dataRek->no_rekening ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nama Bank</p>
                        <p class="font-semibold text-gray-900">{{ $janjiTemu->pengajuan->nasabah->dataRek->nama_bank ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nama Pemilik Rekening</p>
                        <p class="font-semibold text-gray-900">{{ $janjiTemu->pengajuan->nasabah->dataRek->nama_pemilik_rekening ?? 'N/A' }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Informasi Janji Temu -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h2 class="text-lg font-bold text-primary font-display mb-4 pb-4 border-b border-gray-200">Informasi Janji Temu</h2>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600">Tanggal & Waktu</p>
                        <p class="font-semibold text-gray-900">{{ $janjiTemu->tanggal_janji_temu->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Lokasi</p>
                        <p class="font-semibold text-gray-900">{{ $janjiTemu->lokasi->nama_lokasi ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $janjiTemu->lokasi->alamat_lengkap ?? '' }}</p>
                        <p class="text-xs text-gray-500">{{ $janjiTemu->lokasi->kota ?? '' }}, {{ $janjiTemu->lokasi->provinsi ?? '' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nominal</p>
                        <p class="font-semibold text-[#674c1d] text-2xl">Rp {{ number_format($janjiTemu->nominal, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">ID Pengajuan</p>
                        <p class="font-semibold text-gray-900">#{{ $janjiTemu->pengajuan->id }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-primary font-display mb-4">Status</h3>
                <div class="space-y-3">
                    @php
                        $isPast = $janjiTemu->tanggal_janji_temu < now();
                        $statusColor = $isPast ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700';
                        $statusLabel = $isPast ? 'Selesai' : 'Akan Datang';
                        $hasTransaksi = $janjiTemu->pengajuan->transTabungan->count() > 0;
                    @endphp
                    <span class="inline-block px-4 py-2 {{ $statusColor }} rounded-full text-sm font-semibold">
                        {{ $statusLabel }}
                    </span>
                    <div>
                        <p class="text-sm text-gray-600">Waktu Tersisa</p>
                        <p class="font-semibold text-gray-900">
                            @if($isPast)
                                Sudah lewat
                            @else
                                {{ $janjiTemu->tanggal_janji_temu->diffForHumans() }}
                            @endif
                        </p>
                    </div>
                    @if($hasTransaksi)
                    <div class="pt-3 border-t border-gray-200">
                        <p class="text-sm text-gray-600 mb-2">Status Transaksi</p>
                        <span class="inline-block px-4 py-2 bg-green-100 text-green-700 rounded-full text-sm font-semibold">
                            ✓ Sudah Dibuat
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            @if(!$hasTransaksi)
            <!-- Form Create Transaksi -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-primary font-display mb-4">Buat Transaksi Tabungan</h3>
                <p class="text-sm text-gray-600 mb-4">Isi form di bawah untuk membuat transaksi tabungan setelah janji temu selesai.</p>
                
                <form method="POST" action="{{ route('admin.tabungan.create-trans-from-janji-temu', $janjiTemu->id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nominal <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                                <input type="text" name="nominal" id="nominal" value="{{ number_format($janjiTemu->nominal, 0, ',', '.') }}" 
                                    required class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none"
                                    oninput="formatCurrency(this)">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Nominal default: Rp {{ number_format($janjiTemu->nominal, 0, ',', '.') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Transaksi <span class="text-red-500">*</span></label>
                            <input type="date" name="tgl_transaksi" value="{{ date('Y-m-d') }}" required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Foto Penerimaan (Opsional)</label>
                            <input type="file" name="foto_penerimaan" accept="image/*" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none">
                            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG (Max 5MB)</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                            <textarea name="keterangan" rows="3" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none resize-none"
                                placeholder="Tambahkan keterangan jika diperlukan..."></textarea>
                        </div>

                        <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all font-medium shadow-md">
                            ✓ Buat Transaksi Tabungan
                        </button>
                    </div>
                </form>
            </div>
            @else
            <!-- Info Transaksi Sudah Ada -->
            <div class="bg-green-50 rounded-2xl shadow-md p-6 border border-green-200">
                <div class="flex items-center gap-3 mb-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-green-800">Transaksi Sudah Dibuat</h3>
                </div>
                <p class="text-sm text-green-700 mb-4">Transaksi tabungan untuk janji temu ini sudah dibuat.</p>
                <a href="{{ route('admin.tabungan.detail-transaksi', $janjiTemu->pengajuan->transTabungan->first()->id) }}" 
                    class="block text-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                    Lihat Detail Transaksi
                </a>
            </div>
            @endif
        </div>
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

    // Convert formatted currency back to number before submit
    document.querySelector('form').addEventListener('submit', function(e) {
        const nominalInput = document.getElementById('nominal');
        if (nominalInput) {
            const value = nominalInput.value.replace(/[^\d]/g, '');
            // Create hidden input with numeric value
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'nominal';
            hiddenInput.value = value;
            nominalInput.name = 'nominal_formatted';
            this.appendChild(hiddenInput);
        }
    });
</script>
@endpush
@endsection
