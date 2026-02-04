@extends('layouts.admin')

@section('title', 'Detail Pengajuan Setor')


@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Detail Pengajuan Setor</h1>
            <p class="text-gray-600 mt-1">ID Pengajuan: #{{ $pengajuan->id }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.tabungan.pengajuan-setor') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium">
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
                        <p class="font-semibold text-gray-900">{{ $pengajuan->nasabah->user->nama ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-semibold text-gray-900">{{ $pengajuan->nasabah->user->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nomor HP</p>
                        <p class="font-semibold text-gray-900">{{ $pengajuan->nasabah->user->nomor_hp ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">NIK</p>
                        <p class="font-semibold text-gray-900">{{ $pengajuan->nasabah->dataKtp->nik ?? 'N/A' }}</p>
                    </div>
                    @if($pengajuan->nasabah->dataRek)
                    <div>
                        <p class="text-sm text-gray-600">No. Rekening</p>
                        <p class="font-semibold text-gray-900">{{ $pengajuan->nasabah->dataRek->no_rekening ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nama Bank</p>
                        <p class="font-semibold text-gray-900">{{ $pengajuan->nasabah->dataRek->nama_bank ?? 'N/A' }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Informasi Pengajuan -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h2 class="text-lg font-bold text-primary font-display mb-4 pb-4 border-b border-gray-200">Informasi Pengajuan</h2>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600">Tanggal Pengajuan</p>
                        <p class="font-semibold text-gray-900">{{ $pengajuan->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    
                    <!-- Nominal Setoran (Editable) -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm text-gray-600">Nominal Setoran</p>
                            @if($pengajuan->status == '1')
                            <button onclick="toggleEditNominal()" id="btn-edit-nominal" class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-xs font-semibold">
                                <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit
                            </button>
                            @endif
                        </div>
                        <div id="nominal-display">
                            <p class="font-semibold text-[#674c1d] text-2xl">Rp {{ number_format($pengajuan->nominal > 0 ? $pengajuan->nominal : ($pengajuan->janjiTemu->nominal ?? 0), 0, ',', '.') }}</p>
                        </div>
                        @if($pengajuan->status == '1')
                        <div id="nominal-edit" class="hidden">
                            <input type="text" id="input-nominal" value="{{ number_format($pengajuan->nominal > 0 ? $pengajuan->nominal : ($pengajuan->janjiTemu->nominal ?? 0), 0, '', '') }}" 
                                class="w-full px-4 py-2 border-2 border-blue-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none text-lg font-semibold"
                                oninput="formatNominalInput(this)">
                            <p class="text-xs text-gray-500 mt-1">Min: Rp 10.000</p>
                        </div>
                        @endif
                    </div>

                    <!-- Keterangan (Editable) -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm text-gray-600">Keterangan</p>
                            @if($pengajuan->status == '1')
                            <button onclick="toggleEditKeterangan()" id="btn-edit-keterangan" class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-xs font-semibold">
                                <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit
                            </button>
                            @endif
                        </div>
                        <div id="keterangan-display">
                            <p class="text-gray-900 mt-1">{{ $pengajuan->keterangan ?: 'Tidak ada keterangan' }}</p>
                        </div>
                        @if($pengajuan->status == '1')
                        <div id="keterangan-edit" class="hidden">
                            <textarea id="input-keterangan" rows="3" class="w-full px-4 py-2 border-2 border-blue-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none">{{ $pengajuan->keterangan }}</textarea>
                        </div>
                        @endif
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        @php
                            $statusConfig = [
                                '1' => ['label' => 'Pending', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'],
                                '3' => ['label' => 'Disetujui', 'bg' => 'bg-green-100', 'text' => 'text-green-700'],
                                '2' => ['label' => 'Ditolak', 'bg' => 'bg-red-100', 'text' => 'text-red-700'],
                            ];
                            $status = $statusConfig[$pengajuan->status] ?? $statusConfig['1'];
                        @endphp
                        <span class="inline-block mt-2 px-4 py-2 {{ $status['bg'] }} {{ $status['text'] }} rounded-full text-sm font-semibold">
                            {{ $status['label'] }}
                        </span>
                    </div>

                    <!-- Update & Approve Buttons -->
                    @if($pengajuan->status == '1')
                    <div class="pt-4 border-t border-gray-200 space-y-3">
                        <button onclick="updateAndApprove()" class="w-full px-4 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all font-medium shadow-md">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Update & Setujui
                        </button>
                        <button onclick="quickApprove()" class="w-full px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all font-medium shadow-md">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Setujui Cepat (Tanpa Edit)
                        </button>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Bukti Foto Transfer -->
            @if($pengajuan->buktiFoto->count() > 0)
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h2 class="text-lg font-bold text-primary font-display mb-4 pb-4 border-b border-gray-200">Bukti Foto Transfer</h2>
                <p class="text-sm text-gray-600 mb-4">Klik foto untuk memperbesar</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($pengajuan->buktiFoto as $index => $bukti)
                    <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow cursor-pointer"
                        onclick="showPhotoPreview('{{ asset('storage/' . $bukti->file_path) }}', 'Bukti Transfer #{{ $index + 1 }}')">
                        <div class="w-full h-64 bg-gray-100 overflow-hidden group">
                            @if(Storage::disk('public')->exists($bukti->file_path))
                                <img src="{{ asset('storage/' . $bukti->file_path) }}" 
                                     alt="Bukti Transfer" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="w-full h-full hidden items-center justify-center">
                                    <div class="text-center">
                                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <p class="text-sm text-gray-500">Gagal load gambar</p>
                                    </div>
                                </div>
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <div class="text-center">
                                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <p class="text-sm text-gray-500">File tidak ditemukan</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="p-3 bg-gray-50">
                            <p class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                </svg>
                                Bukti Transfer {{ $index + 1 }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">{{ $bukti->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Janji Temu -->
            @if($pengajuan->janjiTemu)
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h2 class="text-lg font-bold text-primary font-display mb-4 pb-4 border-b border-gray-200">Janji Temu</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Tanggal & Waktu</p>
                        <p class="font-semibold text-gray-900">{{ $pengajuan->janjiTemu->tanggal_janji_temu->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Lokasi</p>
                        <p class="font-semibold text-gray-900">{{ $pengajuan->janjiTemu->lokasi->nama_lokasi ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $pengajuan->janjiTemu->lokasi->alamat_lengkap ?? '' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nominal</p>
                        <p class="font-semibold text-[#674c1d]">Rp {{ number_format($pengajuan->janjiTemu->nominal, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-6">
            @if($pengajuan->status == '1')
            <!-- Tindakan -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-primary font-display mb-4">Tindakan</h3>
                <div class="space-y-3">
                    <button onclick="showRejectModal()" class="w-full px-4 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg hover:from-red-700 hover:to-red-800 transition-all font-medium shadow-md">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Tolak Pengajuan
                    </button>

                    <form method="POST" action="{{ route('admin.tabungan.delete-pengajuan-setor', $pengajuan->id) }}" onsubmit="return confirm('Yakin hapus? Tidak dapat dibatalkan!')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-gray-600 to-gray-700 text-white rounded-lg hover:from-gray-700 hover:to-gray-800 transition-all font-medium shadow-md">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Hapus Pengajuan
                        </button>
                    </form>
                </div>
            </div>

            <!-- Reject Modal -->
            <div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl p-6 max-w-md w-full">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Tolak Pengajuan</h3>
                    <form method="POST" action="{{ route('admin.tabungan.reject-setor', $pengajuan->id) }}">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan</label>
                            <textarea name="keterangan_admin" rows="4" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none" placeholder="Masukkan alasan penolakan..."></textarea>
                        </div>
                        <div class="flex space-x-3">
                            <button type="button" onclick="hideRejectModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                Batal
                            </button>
                            <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                Tolak
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>

    let editingNominal = false;
    let editingKeterangan = false;

    function toggleEditNominal() {
        editingNominal = !editingNominal;
        document.getElementById('nominal-display').classList.toggle('hidden');
        document.getElementById('nominal-edit').classList.toggle('hidden');
        
        if (!editingNominal) {
            const input = document.getElementById('input-nominal');
            document.getElementById('nominal-display').querySelector('p').textContent = 
                'Rp ' + parseInt(input.value.replace(/\D/g, '')).toLocaleString('id-ID');
        }
    }

    function toggleEditKeterangan() {
        editingKeterangan = !editingKeterangan;
        document.getElementById('keterangan-display').classList.toggle('hidden');
        document.getElementById('keterangan-edit').classList.toggle('hidden');
        
        if (!editingKeterangan) {
            const input = document.getElementById('input-keterangan');
            document.getElementById('keterangan-display').querySelector('p').textContent = 
                input.value || 'Tidak ada keterangan';
        }
    }

    function formatNominalInput(input) {
        let value = input.value.replace(/\D/g, '');
        input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function updateAndApprove() {
        if (!confirm('Update data dan setujui pengajuan ini?')) return;
        
        const nominal = document.getElementById('input-nominal').value.replace(/\D/g, '');
        const keterangan = document.getElementById('input-keterangan').value;
        
        if (parseInt(nominal) < 10000) {
            alert('Nominal minimal Rp 10.000');
            return;
        }

        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.tabungan.edit-pengajuan-setor", $pengajuan->id) }}';
        
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        
        const nominalInput = document.createElement('input');
        nominalInput.type = 'hidden';
        nominalInput.name = 'nominal';
        nominalInput.value = nominal;
        
        const keteranganInput = document.createElement('input');
        keteranganInput.type = 'hidden';
        keteranganInput.name = 'keterangan';
        keteranganInput.value = keterangan;
        
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = '2';
        
        form.appendChild(csrf);
        form.appendChild(nominalInput);
        form.appendChild(keteranganInput);
        form.appendChild(statusInput);
        
        document.body.appendChild(form);
        form.submit();
    }

    function quickApprove() {
        if (!confirm('Setujui pengajuan tanpa edit?')) return;
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.tabungan.approve-setor", $pengajuan->id) }}';
        
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        
        form.appendChild(csrf);
        document.body.appendChild(form);
        form.submit();
    }

    function showRejectModal() {
        document.getElementById('rejectModal').classList.remove('hidden');
    }
    
    function hideRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }
</script>
@endpush
@endsection
