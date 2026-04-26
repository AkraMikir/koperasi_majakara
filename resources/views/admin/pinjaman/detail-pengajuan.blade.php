@extends('layouts.admin')

@section('title', 'Detail Pengajuan Pinjaman')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 font-display">Detail Pengajuan Pinjaman</h1>
                <p class="text-gray-600 mt-1">ID Pengajuan: #{{ $pengajuan->id }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.pinjaman.pengajuan') }}"
                    class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium">
                    ← Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Data Nasabah -->
                <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                    <h2 class="text-lg font-bold text-primary font-display mb-4 pb-4 border-b border-gray-200">Data Nasabah
                    </h2>
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
                        @if(($pengajuan->jenis_pencairan ?? '') === 'transfer' && $pengajuan->nasabah->dataRek)
                            <div>
                                <p class="text-sm text-gray-600">Nomor Rekening</p>
                                <p class="font-semibold text-gray-900">{{ $pengajuan->nasabah->dataRek->no_rekening ?? 'N/A' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Bank</p>
                                <p class="font-semibold text-gray-900">{{ $pengajuan->nasabah->dataRek->nama_bank ?? 'N/A' }}
                                </p>
                            </div>
                            @if($pengajuan->nasabah->dataRek->nama_pemilik_rekening ?? null)
                                <div>
                                    <p class="text-sm text-gray-600">Nama Pemilik Rekening</p>
                                    <p class="font-semibold text-gray-900">{{ $pengajuan->nasabah->dataRek->nama_pemilik_rekening }}
                                    </p>
                                </div>
                            @endif
                        @else
                            <div>
                                <p class="text-sm text-gray-600">NIK</p>
                                <p class="font-semibold text-gray-900">{{ $pengajuan->nasabah->dataKtp->nik ?? 'N/A' }}</p>
                            </div>
                        @endif
                        @if($pengajuan->nasabah->pekerjaan)
                            <div>
                                <p class="text-sm text-gray-600">Pekerjaan</p>
                                <p class="font-semibold text-gray-900">{{ $pengajuan->nasabah->pekerjaan->pekerjaan ?? 'N/A' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Penghasilan</p>
                                <p class="font-semibold text-gray-900">Rp
                                    {{ number_format((float) ($pengajuan->nasabah->pekerjaan->penghasilan ?? 0), 0, ',', '.') }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Informasi Pengajuan -->
                <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                    <h2 class="text-lg font-bold text-primary font-display mb-4 pb-4 border-b border-gray-200">Informasi
                        Pengajuan</h2>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-600">Tanggal Pengajuan</p>
                            <p class="font-semibold text-gray-900">{{ $pengajuan->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Nominal Pinjaman</p>
                            <p class="font-semibold text-[#674c1d] text-2xl">Rp
                                {{ number_format((float) $pengajuan->nominal, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Jenis</p>
                                <span
                                    class="inline-block mt-2 px-4 py-2 {{ $pengajuan->jenis === 'bulanan' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }} rounded-full text-sm font-semibold">
                                    {{ ucfirst($pengajuan->jenis) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Durasi</p>
                                <p class="font-semibold text-gray-900 mt-2">{{ $pengajuan->durasi }}
                                    {{ $pengajuan->jenis === 'bulanan' ? 'bulan' : 'minggu' }}
                                </p>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Metode Pencairan</p>
                            <span
                                class="inline-block mt-2 px-4 py-2 {{ ($pengajuan->jenis_pencairan ?? 'transfer') === 'transfer' ? 'bg-indigo-100 text-indigo-700' : 'bg-amber-100 text-amber-700' }} rounded-full text-sm font-semibold">
                                {{ ($pengajuan->jenis_pencairan ?? 'transfer') === 'transfer' ? 'Transfer' : 'Tunai (Janji Temu)' }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Status</p>
                            @if($pengajuan->status === '1')
                                <span
                                    class="inline-block mt-2 px-4 py-2 bg-yellow-100 text-yellow-700 rounded-full text-sm font-semibold">
                                    Pending
                                </span>
                            @elseif($pengajuan->status === '2')
                                <span
                                    class="inline-block mt-2 px-4 py-2 bg-red-100 text-red-700 rounded-full text-sm font-semibold">
                                    Ditolak
                                </span>
                            @elseif($pengajuan->status === '3')
                                <span
                                    class="inline-block mt-2 px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                                    Disetujui (Menunggu Pencairan)
                                </span>
                            @elseif($pengajuan->status === '4')
                                <span
                                    class="inline-block mt-2 px-4 py-2 bg-green-100 text-green-700 rounded-full text-sm font-semibold">
                                    Terlaksana
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Actions (sticky) -->
            <div class="space-y-6 lg:sticky lg:top-6 lg:self-start">
                <!-- Action Buttons -->
                <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-primary font-display mb-4">Tindakan</h3>

                    @if($pengajuan->status === '1')
                        <!-- Status Pending - Show Approve/Reject -->
                        <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <h4 class="font-semibold text-blue-900 mb-2">Informasi Bunga & Denda</h4>
                            @if($masterBunga)
                                <p class="text-sm text-blue-800 mb-1">
                                    <strong>Bunga:</strong> {{ $masterBunga->bunga_persen }}%
                                    (Durasi {{ $masterBunga->durasi_min }}-{{ $masterBunga->durasi_max }} bulan)
                                </p>
                                <p class="text-sm text-blue-800 mb-1">
                                    <strong>Bunga Total:</strong> Rp
                                    {{ number_format(((float) $pengajuan->nominal * (float) $masterBunga->bunga_persen) / 100, 0, ',', '.') }}
                                </p>
                                <p class="text-sm text-blue-800 mb-1">
                                    <strong>Yang Diterima Nasabah:</strong> Rp
                                    {{ number_format((float) $pengajuan->nominal, 0, ',', '.') }}
                                </p>
                                <p class="text-sm text-blue-800 mb-1">
                                    <strong>Total Tagihan:</strong> Rp
                                    {{ number_format((float) $pengajuan->nominal + (((float) $pengajuan->nominal * (float) $masterBunga->bunga_persen) / 100), 0, ',', '.') }}
                                </p>
                            @else
                                <p class="text-sm text-red-600">Bunga untuk durasi {{ $pengajuan->durasi }} bulan belum diatur di
                                    master data!</p>
                            @endif
                            @if($masterDenda)
                                <p class="text-sm text-blue-800">
                                    <strong>Denda:</strong> {{ $masterDenda->denda_persen }}% per hari dari POKOK per bulan
                                </p>
                            @else
                                <p class="text-sm text-red-600">Denda belum diatur di master data!</p>
                            @endif
                        </div>

                        <form method="POST" action="{{ route('admin.pinjaman.approve-pengajuan', $pengajuan->id) }}"
                            onsubmit="return confirm('Setujui pengajuan ini? Status akan berubah menjadi DISETUJUI. Anda masih perlu CAIRKAN dana setelah ini.')">
                            @csrf
                            @if(!$masterBunga || !$masterDenda)
                                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                                    <p class="text-sm text-red-600">Tidak dapat menyetujui pengajuan karena master data belum
                                        lengkap!</p>
                                </div>
                                <button type="submit" disabled
                                    class="w-full px-4 py-3 bg-gray-400 text-white rounded-lg cursor-not-allowed font-medium shadow-md mb-3">
                                    ✓ Setujui Pengajuan
                                </button>
                            @else
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan (Opsional)</label>
                                    <textarea name="keterangan_admin" rows="3"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none"
                                        placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                                </div>
                                <button type="submit"
                                    class="w-full px-4 py-3 bg-linear-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all font-medium shadow-md mb-3">
                                    ✓ Setujui Pengajuan
                                </button>
                            @endif
                        </form>

                        <button onclick="showRejectModal()"
                            class="w-full px-4 py-3 bg-linear-to-r from-red-600 to-red-700 text-white rounded-lg hover:from-red-700 hover:to-red-800 transition-all font-medium shadow-md">
                            ✗ Tolak Pengajuan
                        </button>

                    @elseif($pengajuan->status === '3')
                        <!-- Status Disetujui - Show Cairkan -->
                        <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-sm text-blue-800 font-semibold mb-2">✓ Pengajuan telah disetujui</p>
                            @if($pengajuan->keterangan_admin)
                                <p class="text-sm text-blue-700 mb-2 italic">
                                    <strong>Catatan Admin:</strong> "{{ $pengajuan->keterangan_admin }}"
                                </p>
                            @endif
                            <p class="text-sm text-blue-800 mb-1">
                                <strong>Bunga:</strong> {{ $pengajuan->bunga_persen }}%
                            </p>
                            <p class="text-sm text-blue-800 mb-1">
                                <strong>Total Bunga:</strong> Rp
                                {{ number_format(((float) $pengajuan->nominal * (float) $pengajuan->bunga_persen) / 100, 0, ',', '.') }}
                            </p>
                            <p class="text-sm text-blue-800 mb-1">
                                <strong>Yang Diterima Nasabah:</strong> Rp
                                {{ number_format((float) $pengajuan->nominal, 0, ',', '.') }}
                            </p>
                        </div>

                        <button onclick="showCairkanModal()"
                            class="w-full px-4 py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[#8b6f2f] hover:to-[#a0824d] transition-all font-medium shadow-md">
                            💰 Cairkan Pinjaman
                        </button>

                    @elseif($pengajuan->status === '2')
                        <!-- Status Ditolak -->
                        <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-sm text-red-800 font-semibold mb-2">✗ Pengajuan ditolak</p>
                            @if($pengajuan->keterangan_admin)
                                <p class="text-sm text-red-700">
                                    <strong>Alasan:</strong> {{ $pengajuan->keterangan_admin }}
                                </p>
                            @endif
                        </div>

                    @elseif($pengajuan->status === '4')
                        <!-- Status Terlaksana -->
                        <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                            <p class="text-sm text-green-800 font-semibold mb-2">✓ Pinjaman telah dicairkan</p>
                            @if($pengajuan->tgl_cair)
                                <p class="text-sm text-green-700">
                                    <strong>Tanggal Cair:</strong>
                                    {{ \Carbon\Carbon::parse($pengajuan->tgl_cair)->format('d M Y') }}
                                </p>
                            @endif
                            @if($pengajuan->pinjaman)
                                <a href="{{ route('admin.pinjaman.detail-pinjaman', $pengajuan->pinjaman->id) }}"
                                    class="mt-3 block w-full px-4 py-2 bg-[#674c1d] text-white text-center rounded-lg hover:bg-[#8b6f2f] transition-colors">
                                    Lihat Detail Pinjaman →
                                </a>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Reject Modal -->
                <div id="rejectModal"
                    class="hidden fixed inset-0 bg-gray-600/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-2xl p-6 max-w-md w-full">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Tolak Pengajuan</h3>
                        <form method="POST" action="{{ route('admin.pinjaman.reject-pengajuan', $pengajuan->id) }}">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan *</label>
                                <textarea name="keterangan_admin" rows="4" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none"
                                    placeholder="Masukkan alasan penolakan..."></textarea>
                            </div>
                            <div class="flex space-x-3">
                                <button type="button" onclick="hideRejectModal()"
                                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                    Tolak
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Cairkan Modal -->
                <div id="cairkanModal"
                    class="hidden fixed inset-0 bg-gray-600/50 backdrop-blur-sm z-50 flex items-center justify-center p-4 mt-16">
                    <div class="bg-white rounded-2xl p-6 max-w-lg w-full shadow-2xl border border-gray-100">
                        <div class="flex items-center justify-between mb-4 pb-4 border-b">
                            <h3 class="text-xl font-bold text-gray-900 font-display">Cairkan Pinjaman</h3>
                            <button onclick="hideCairkanModal()" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <form id="cairkanForm" method="POST"
                            action="{{ route('admin.pinjaman.cairkan-pinjaman', $pengajuan->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Cair *</label>
                                <input type="date" name="tgl_cair" required value="{{ date('Y-m-d') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none transition-all">
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-3">Metode Pencairan & Cek Saldo
                                    *</label>

                                <!-- Petty Cash Info Card (Only Transfer) -->
                                <div id="card_petty_tf"
                                    class="p-4 rounded-xl border-2 border-[#674c1d] bg-[#674c1d]/5 transition-all mb-4">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-[10px] uppercase tracking-wider text-gray-500 font-bold mb-1">
                                                Saldo Petty Cash (Transfer)</p>
                                            <p class="text-xl font-black text-[#674c1d]">Rp
                                                {{ number_format($adminSaldo->transfer, 0, ',', '.') }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <div
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $adminSaldo->transfer >= $pengajuan->nominal ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                                <span
                                                    class="w-2 h-2 rounded-full {{ $adminSaldo->transfer >= $pengajuan->nominal ? 'bg-green-500' : 'bg-red-500' }} mr-1.5"></span>
                                                {{ $adminSaldo->transfer >= $pengajuan->nominal ? 'Saldo Cukup' : 'Saldo Kurang' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Method Label & Hidden Input -->
                                <div
                                    class="p-3 bg-gray-50 border border-gray-200 rounded-lg flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Metode:</span>
                                    <span class="text-sm font-bold text-gray-900">Petty Cash (Transfer)</span>
                                </div>
                                <input type="hidden" name="metode_pencairan" value="petty_tf">
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Transaksi *</label>
                                <div class="relative group">
                                    <input type="file" name="bukti_transfer" accept="image/*" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none">
                                </div>
                                <p class="text-xs text-gray-500 mt-1 italic">Wajib upload bukti pencairan (JPG, PNG, Max
                                    5MB)</p>
                            </div>

                            <div id="warningBalance" class="hidden p-3 bg-red-50 border border-red-200 rounded-lg mb-4">
                                <p class="text-xs text-red-600 font-semibold flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Saldo Petty Cash tidak mencukupi untuk nominal Rp
                                    {{ number_format($pengajuan->nominal, 0, ',', '.') }}
                                </p>
                            </div>

                            <div class="flex space-x-3 mt-6">
                                <button type="button" onclick="hideCairkanModal()"
                                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium">
                                    Batal
                                </button>
                                <button id="btnSubmitCairkan" type="submit"
                                    class="flex-1 px-4 py-2 bg-[#674c1d] text-white rounded-lg hover:bg-[#8b6f2f] disabled:bg-gray-300 disabled:cursor-not-allowed transition-all font-bold shadow-lg shadow-[#674c1d]/20">
                                    ✓ Cairkan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const nominalPengajuan = {{ $pengajuan->nominal }};
        const saldoTransfer = {{ $adminSaldo->transfer }};

        function showRejectModal() {
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function hideRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }

        function showCairkanModal() {
            document.getElementById('cairkanModal').classList.remove('hidden');
            updateButtonState();
        }

        function hideCairkanModal() {
            document.getElementById('cairkanModal').classList.add('hidden');
        }

        function updateButtonState() {
            const warning = document.getElementById('warningBalance');
            const btn = document.getElementById('btnSubmitCairkan');

            const isInsufficient = (saldoTransfer < nominalPengajuan);

            if (isInsufficient) {
                warning.classList.remove('hidden');
                btn.disabled = true;
                btn.innerHTML = 'Saldo Tidak Cukup';
            } else {
                warning.classList.add('hidden');
                btn.disabled = false;
                btn.innerHTML = '✓ Cairkan';
            }
        }

        // Close modals on outside click
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                hideRejectModal();
                hideCairkanModal();
            }
        });

        document.getElementById('rejectModal')?.addEventListener('click', function (e) {
            if (e.target === this) hideRejectModal();
        });

        document.getElementById('cairkanModal')?.addEventListener('click', function (e) {
            if (e.target === this) hideCairkanModal();
        });
    </script>
@endsection