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
            <a href="{{ route('admin.janji-temu.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium">
                ← Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Data Nasabah (dari pengajuan atau langsung dari janji temu) -->
            @php
                $nasabah = $janjiTemu->nasabah;  // Direct nasabah access
            @endphp
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h2 class="text-lg font-bold text-primary font-display mb-4 pb-4 border-b border-gray-200">Data Nasabah</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Nama Lengkap</p>
                        <p class="font-semibold text-gray-900">{{ $nasabah->user->nama ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-semibold text-gray-900">{{ $nasabah->user->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nomor HP</p>
                        <p class="font-semibold text-gray-900">{{ $nasabah->user->nomor_hp ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">NIK</p>
                        <p class="font-semibold text-gray-900">{{ $nasabah->dataKtp->nik ?? 'N/A' }}</p>
                    </div>
                    @if($nasabah->dataRek ?? null)
                    <div>
                        <p class="text-sm text-gray-600">No. Rekening</p>
                        <p class="font-semibold text-gray-900">{{ $nasabah->dataRek->no_rekening ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nama Bank</p>
                        <p class="font-semibold text-gray-900">{{ $nasabah->dataRek->nama_bank ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nama Pemilik Rekening</p>
                        <p class="font-semibold text-gray-900">{{ $nasabah->dataRek->nama_pemilik_rekening ?? 'N/A' }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Informasi Janji Temu -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h2 class="text-lg font-bold text-primary font-display mb-4 pb-4 border-b border-gray-200">Informasi Janji Temu</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Tanggal & Waktu</p>
                            <p class="font-semibold text-gray-900">{{ $janjiTemu->tanggal_janji_temu->format('d M Y, H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Jenis Transaksi</p>
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase {{ ($janjiTemu->jenis ?? 'setoran') == 'setoran' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                                {{ ($janjiTemu->jenis ?? 'setoran') == 'setoran' ? 'Setoran Tabungan' : 'Penarikan Tabungan' }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Lokasi</p>
                        <p class="font-semibold text-gray-900">{{ $janjiTemu->lokasi->nama_lokasi ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $janjiTemu->lokasi->alamat_lengkap ?? '' }}</p>
                        <p class="text-xs text-gray-500">{{ $janjiTemu->lokasi->kota ?? '' }}, {{ $janjiTemu->lokasi->provinsi ?? '' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-2">Peta Lokasi</p>
                        <div class="rounded-xl overflow-hidden border border-gray-200 w-full mt-2">
                            <iframe src="https://www.google.com/maps/embed?pb=!4v1771057242792!6m8!1m7!1sTDnmeXtVvimBtQeXmqSSCQ!2m2!1d-6.267415399913648!2d106.9806162945405!3f247.41483905689947!4f-35.52001210835799!5f0.7820865974627469" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Peta Lokasi Janji Temu"></iframe>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nominal</p>
                        <p class="font-semibold text-[#674c1d] text-2xl">Rp {{ number_format($janjiTemu->nominal, 0, ',', '.') }}</p>
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
                        // Calculate status based on datetime
                        $dateTime = \Carbon\Carbon::parse($janjiTemu->tanggal_janji_temu);
                        if (isset($janjiTemu->waktu_janji_temu)) {
                            $time = \Carbon\Carbon::parse($janjiTemu->waktu_janji_temu);
                            $dateTime->setTime($time->hour, $time->minute, $time->second);
                        }
                        $isPast = $dateTime->isPast();
                        $statusLabel = $isPast ? 'Sudah Lewat' : 'Akan Datang';
                        $statusColor = $isPast ? 'bg-gray-100 text-gray-700' : 'bg-yellow-100 text-yellow-700';
                    @endphp
                    <span class="inline-block px-4 py-2 {{ $statusColor }} rounded-full text-sm font-semibold">
                        {{ $statusLabel }}
                    </span>
                    <div>
                        <p class="text-sm text-gray-600">Waktu Tersisa</p>
                        <p class="font-semibold text-gray-900">
                            @if($janjiTemu->tanggal_janji_temu < now())
                                Sudah lewat
                            @else
                                {{ $janjiTemu->tanggal_janji_temu->diffForHumans() }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            @if(!$isPast)
            <!-- Form Create Transaksi -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-primary font-display mb-4">Buat Transaksi Tabungan</h3>
                <p class="text-sm text-gray-600 mb-4">Isi form di bawah untuk membuat transaksi {{ ($janjiTemu->jenis ?? 'setoran') == 'setoran' ? 'setoran' : 'penarikan' }} tabungan setelah janji temu selesai.</p>
                
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
                            <label class="block text-sm font-medium text-gray-700 mb-2">Foto Penerimaan (Max 3)</label>
                            <div id="foto-container" class="space-y-2 mb-2">
                                <div class="foto-upload-item">
                                    <div class="relative">
                                        <input type="file" name="foto_penerimaan[]" accept="image/*" 
                                            class="file-input w-full text-sm px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none
                                            file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold 
                                            file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                                    </div>
                                </div>
                            </div>
                            <button type="button" onclick="addFotoInput()" id="btn-add-foto" 
                                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 text-sm font-medium transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Tambah Foto
                            </button>
                            <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG (Max 5MB per file, maksimal 3 foto)</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan Admin</label>
                            <textarea name="keterangan_admin" rows="3" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none resize-none"
                                placeholder="Tambahkan catatan atau keterangan admin..."></textarea>
                            <p class="text-xs text-gray-500 mt-1">Catatan: Keterangan nasabah akan otomatis digunakan dalam transaksi</p>
                        </div>

                        <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all font-medium shadow-md">
                            ✓ Buat Transaksi Tabungan
                        </button>
                    </div>
                </form>
            </div>
            @else
            <!-- Info Sudah Diproses -->
            <div class="bg-green-50 rounded-2xl shadow-md p-6 border border-green-200">
                <div class="flex items-center gap-3 mb-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-green-800">Transaksi Sudah Dibuat</h3>
                </div>
                <p class="text-sm text-green-700">Janji temu telah diproses dan transaksi {{ ($janjiTemu->jenis ?? 'setoran') == 'setoran' ? 'setoran' : 'penarikan' }} tabungan sudah dibuat.</p>
                @if($janjiTemu->keterangan_admin)
                <div class="mt-4 p-3 bg-white rounded-lg">
                    <p class="text-xs text-gray-600 mb-1">Keterangan Admin:</p>
                    <p class="text-sm text-gray-900">{{ $janjiTemu->keterangan_admin }}</p>
                </div>
                @endif
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

    // Add/Remove Foto Input (Max 3)
    function addFotoInput() {
        const container = document.getElementById('foto-container');
        const currentCount = container.querySelectorAll('.foto-upload-item').length;
        
        if (currentCount >= 3) {
            alert('Maksimal 3 foto');
            return;
        }
        
        const newItem = document.createElement('div');
        newItem.className = 'foto-upload-item flex gap-2 items-center';
        newItem.innerHTML = `
            <div class="flex-1 relative">
                <input type="file" name="foto_penerimaan[]" accept="image/*" 
                    class="file-input w-full text-sm px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none
                    file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold 
                    file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
            </div>
            <button type="button" onclick="removeFotoInput(this)" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        `;
        container.appendChild(newItem);
        
        // Hide button if max reached
        if (currentCount + 1 >= 3) {
            document.getElementById('btn-add-foto').style.display = 'none';
        }
    }

    function removeFotoInput(button) {
        const item = button.closest('.foto-upload-item');
        const container = document.getElementById('foto-container');
        
        if (container.children.length > 1) {
            item.remove();
            // Show button again if less than max
            document.getElementById('btn-add-foto').style.display = 'flex';
        } else {
            alert('Minimal 1 foto harus ada');
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
