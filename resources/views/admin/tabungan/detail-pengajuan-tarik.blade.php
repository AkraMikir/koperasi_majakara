@extends('layouts.admin')

@section('title', 'Detail Pengajuan Penarikan')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Detail Pengajuan Penarikan</h1>
            <p class="text-gray-600 mt-1">ID Pengajuan: #{{ $pengajuan->id }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.tabungan.pengajuan-tarik') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium">
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
                </div>
            </div>

            <!-- Informasi Pengajuan -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-primary font-display">Informasi Pengajuan</h2>
                    @if($pengajuan->status == '1')
                    <div class="px-3 py-1 bg-blue-50 border border-blue-100 rounded-lg flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></div>
                        <span class="text-xs font-bold text-blue-700">Petty Cash TF: Rp {{ number_format($adminSaldo, 0, ',', '.') }}</span>
                    </div>
                    @endif
                </div>

                @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="text-sm font-bold text-red-800">{{ session('error') }}</p>
                        @if(str_contains(session('error'), 'Petty Cash'))
                        <p class="text-xs text-red-600 mt-1">Saldo Anda saat ini tidak mencukupi untuk melakukan transfer penarikan nasabah ini.</p>
                        @endif
                    </div>
                </div>
                @endif

                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600">Tanggal Pengajuan</p>
                        <p class="font-semibold text-gray-900">{{ $pengajuan->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nominal Penarikan</p>
                        <p class="font-semibold text-[#674c1d] text-2xl">Rp {{ number_format($pengajuan->nominal, 0, ',', '.') }}</p>
                    </div>
                    @if($pengajuan->metode_transfer == 'transfer' && isset($biayaDefault))
                    <div>
                        <p class="text-sm text-gray-600">Biaya Transfer (ditanggung nasabah)</p>
                        <p class="font-semibold text-gray-900">Rp {{ number_format($biayaDefault, 0, ',', '.') }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-sm text-gray-600">Metode</p>
                        <span class="inline-block mt-1 px-3 py-1 {{ $pengajuan->metode_transfer == 'transfer' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }} rounded-full text-sm font-semibold">
                            {{ ucfirst($pengajuan->metode_transfer ?? 'Tunai') }}
                        </span>
                    </div>
                    @if($pengajuan->metode_transfer == 'transfer')
                    <div>
                        <p class="text-sm text-gray-600">Bank Tujuan</p>
                        <p class="font-semibold text-gray-900">{{ $pengajuan->nama_bank ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nomor Rekening</p>
                        <p class="font-semibold text-gray-900 font-mono">{{ $pengajuan->no_rekening ?? 'N/A' }}</p>
                    </div>
                    @else
                    <div>
                        <p class="text-sm text-gray-600">Lokasi Pertemuan</p>
                        <p class="font-semibold text-gray-900">{{ $pengajuan->lokasi->nama_lokasi ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Jadwal Pertemuan</p>
                        <p class="font-semibold text-gray-900">
                            {{ $pengajuan->tanggal_janji_temu ? $pengajuan->tanggal_janji_temu->format('d M Y') : '-' }} , 
                            {{ $pengajuan->waktu_janji_temu ? \Carbon\Carbon::parse($pengajuan->waktu_janji_temu)->format('H:i') : '-' }} WIB
                        </p>
                    </div>
                    @endif
                    <div>
                        <p class="text-sm text-gray-600">Saldo Nasabah</p>
                        <p class="font-semibold text-[#674c1d] text-xl">Rp {{ number_format($saldo, 0, ',', '.') }}</p>
                        @php
                            $totalDipotong = $pengajuan->nominal + ($pengajuan->metode_transfer == 'transfer' ? (float)($biayaDefault ?? 0) : 0);
                        @endphp
                        @if($saldo < $totalDipotong)
                            <p class="text-sm text-red-600 mt-1 font-semibold">⚠ Saldo tidak mencukupi</p>
                            <p class="text-xs text-gray-500 mt-1">Total dipotong (nominal + biaya transfer): Rp {{ number_format($totalDipotong, 0, ',', '.') }}. Kekurangan: Rp {{ number_format($totalDipotong - $saldo, 0, ',', '.') }}</p>
                        @else
                            <p class="text-sm text-green-600 mt-1 font-semibold">✓ Saldo mencukupi</p>
                            <p class="text-xs text-gray-500 mt-1">Sisa setelah penarikan (setelah dikurangi nominal + biaya transfer): Rp {{ number_format($saldo - $totalDipotong, 0, ',', '.') }}</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        @php
                            $statusConfig = [
                                '1' => ['label' => 'Pending', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'],
                                '2' => ['label' => 'Disetujui', 'bg' => 'bg-green-100', 'text' => 'text-green-700'],
                                '3' => ['label' => 'Ditolak', 'bg' => 'bg-red-100', 'text' => 'text-red-700'],
                            ];
                            $status = $statusConfig[$pengajuan->status] ?? $statusConfig['1'];
                        @endphp
                        <span class="inline-block mt-2 px-4 py-2 {{ $status['bg'] }} {{ $status['text'] }} rounded-full text-sm font-semibold">
                            {{ $status['label'] }}
                        </span>
                    </div>
                    @if($pengajuan->keterangan)
                    <div>
                        <p class="text-sm text-gray-600">Keterangan</p>
                        <p class="text-gray-900 mt-1">{{ $pengajuan->keterangan }}</p>
                    </div>
                    @endif

                    @if($pengajuan->status == '2' && $pengajuan->foto_bukti_tf_admin)
                    <div class="pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-600 mb-2">Bukti Transfer (Admin)</p>
                        <div class="max-w-xs border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                            <img src="{{ asset('storage/' . $pengajuan->foto_bukti_tf_admin) }}" alt="Bukti Transfer" class="w-full h-auto cursor-pointer" onclick="window.open(this.src)">
                        </div>
                    </div>
                    @endif

                    @if($pengajuan->keterangan_admin)
                    <div class="mt-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                        <p class="text-sm font-semibold text-gray-700 mb-1">Catatan Admin:</p>
                        <p class="text-gray-600">{{ $pengajuan->keterangan_admin }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar Actions (sticky) -->
        <div class="space-y-6 lg:sticky lg:top-6 lg:self-start">
            @if($pengajuan->status == '1')
            <!-- Approve Form with Bank Selection -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-primary font-display mb-4">Setujui Penarikan</h3>
                <form method="POST" action="{{ route('admin.tabungan.approve-tarik', $pengajuan->id) }}" enctype="multipart/form-data" class="space-y-4" id="approve-form">
                    @csrf

                    @if($pengajuan->metode_transfer == 'transfer')
                    <!-- Bank Pengirim (Koperasi) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bank Pengirim (Koperasi)</label>
                        <select name="bank_pengirim" id="bank_pengirim" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none"
                            onchange="calculateBiaya()">
                            <option value="">Pilih Bank</option>
                            <option value="BCA">BCA</option>
                            <option value="BNI">BNI</option>
                            <option value="Mandiri">Mandiri</option>
                            <option value="BRI">BRI</option>
                        </select>
                    </div>

                    <!-- Biaya Admin (Auto Calculate) - ditanggung nasabah -->
                    <div id="biaya-section" class="hidden p-4 bg-amber-50 border border-amber-200 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm font-semibold text-gray-700">Biaya Transfer (ditanggung nasabah):</p>
                            <p class="font-bold text-amber-700" id="biaya-display">Rp 0</p>
                        </div>
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm font-semibold text-gray-700">Total dikurangi dari saldo:</p>
                            <p class="font-bold text-gray-900" id="total-dipotong-display">Rp {{ number_format($pengajuan->nominal, 0, ',', '.') }}</p>
                        </div>
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-semibold text-gray-700">Total Diterima Nasabah:</p>
                            <p class="font-bold text-green-600" id="total-display">Rp {{ number_format($pengajuan->nominal, 0, ',', '.') }}</p>
                        </div>
                        <p class="text-xs text-amber-700 mt-2 font-medium">Biaya admin ditanggung nasabah (dikurangi dari saldo)</p>
                    </div>

                    <!-- Upload Bukti TF Admin -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Bukti Transfer *</label>
                        <input type="file" name="foto_bukti_tf_admin" accept="image/jpeg,image/png,image/jpg" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#674c1d] file:text-white hover:file:bg-[#4a3514] file:cursor-pointer"
                            onchange="previewFoto(this)">
                        <div id="foto-preview" class="hidden mt-3">
                            <img src="" alt="Preview" class="max-w-full max-h-48 rounded-lg border border-gray-200 shadow-sm">
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Max 5MB, Format: JPG, PNG</p>
                    </div>
                    @endif

                    @php
                        $isNasabahSaldoCukup = $saldo >= $totalDipotong;
                        $isAdminSaldoCukup = ($pengajuan->metode_transfer == 'transfer') ? ($adminSaldo >= $pengajuan->nominal) : true;
                    @endphp

                    @if($isNasabahSaldoCukup && $isAdminSaldoCukup)
                    <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all font-medium shadow-md">
                        ✓ Setujui Penarikan
                    </button>
                    @elseif(!$isNasabahSaldoCukup)
                    <button disabled class="w-full px-4 py-3 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed font-medium">
                        Saldo Nasabah Kurang
                    </button>
                    @else
                    <button disabled class="w-full px-4 py-3 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed font-medium" title="Saldo Petty Cash (TF) Anda: Rp {{ number_format($adminSaldo, 0, ',', '.') }}">
                        Saldo Petty Cash Kurang
                    </button>
                    @endif
                </form>
            </div>

            <!-- Reject Button -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-primary font-display mb-4">Tindakan Lain</h3>
                <button onclick="showRejectModal()" class="w-full px-4 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg hover:from-red-700 hover:to-red-800 transition-all font-medium shadow-md">
                    ✗ Tolak Pengajuan
                </button>
            </div>

            <!-- Reject Modal -->
            <div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl p-6 max-w-md w-full">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Tolak Pengajuan</h3>
                    <form method="POST" action="{{ route('admin.tabungan.reject-tarik', $pengajuan->id) }}">
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

<div id="pengajuan-tarik-data" class="hidden"
    data-nama-bank="{{ e($pengajuan->nama_bank ?? '') }}"
    data-nominal="{{ (float) ($pengajuan->nominal ?? 0) }}"
    data-biaya-list="{{ json_encode(isset($biayaTransferList) ? $biayaTransferList->map(fn($b) => ['bank_pengirim' => $b->bank_pengirim, 'bank_penerima' => $b->bank_penerima, 'biaya_admin' => (float)$b->biaya_admin])->values() : []) }}"
></div>

@push('scripts')
<script>
    function calculateBiaya() {
        const bankPengirim = document.getElementById('bank_pengirim').value;
        const dataEl = document.getElementById('pengajuan-tarik-data');
        const bankPenerima = dataEl ? (dataEl.getAttribute('data-nama-bank') || '') : '';
        const nominal = dataEl ? (parseFloat(dataEl.getAttribute('data-nominal')) || 0) : 0;
        const biayaList = dataEl && dataEl.getAttribute('data-biaya-list') ? JSON.parse(dataEl.getAttribute('data-biaya-list')) : [];

        if (!bankPengirim) {
            return;
        }

        let biaya = 0;
        const match = biayaList.find(function(b) {
            return b.bank_pengirim === bankPengirim && (b.bank_penerima === bankPenerima || !bankPenerima);
        });
        if (match) {
            biaya = match.biaya_admin || 0;
        } else if (biayaList.length) {
            biaya = biayaList[0].biaya_admin || 0;
        }

        const biayaSection = document.getElementById('biaya-section');
        biayaSection.classList.remove('hidden');

        document.getElementById('biaya-display').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(biaya);
        document.getElementById('total-display').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(nominal);
        const totalDipotong = nominal + biaya;
        const totalDipotongEl = document.getElementById('total-dipotong-display');
        if (totalDipotongEl) {
            totalDipotongEl.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalDipotong);
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

    function showRejectModal() {
        document.getElementById('rejectModal').classList.remove('hidden');
    }
    
    function hideRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }
</script>
@endpush
@endsection
