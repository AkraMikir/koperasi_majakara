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
                <h2 class="text-lg font-bold text-primary font-display mb-4 pb-4 border-b border-gray-200">Informasi Pengajuan</h2>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600">Tanggal Pengajuan</p>
                        <p class="font-semibold text-gray-900">{{ $pengajuan->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nominal Penarikan</p>
                        <p class="font-semibold text-[#674c1d] text-2xl">Rp {{ number_format($pengajuan->nominal, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Saldo Saat Ini</p>
                        <p class="font-semibold text-[#674c1d] text-2xl">Rp {{ number_format($saldo, 0, ',', '.') }}</p>
                        @if($saldo < $pengajuan->nominal)
                            <p class="text-sm text-red-600 mt-1 font-semibold">⚠ Saldo tidak mencukupi</p>
                            <p class="text-xs text-gray-500 mt-1">Kekurangan: Rp {{ number_format($pengajuan->nominal - $saldo, 0, ',', '.') }}</p>
                        @else
                            <p class="text-sm text-green-600 mt-1 font-semibold">✓ Saldo mencukupi</p>
                            <p class="text-xs text-gray-500 mt-1">Sisa setelah penarikan: Rp {{ number_format($saldo - $pengajuan->nominal, 0, ',', '.') }}</p>
                        @endif
                        @php
                            // Debug info untuk melihat detail saldo
                            $totalSetoranTrans = \App\Models\TransTabungan::where('id_anggota', $pengajuan->id_anggota)
                                ->where('jenis', 'setoran')
                                ->sum('nominal') ?? 0;
                            $totalPenarikanTrans = \App\Models\TransTabungan::where('id_anggota', $pengajuan->id_anggota)
                                ->where('jenis', 'penarikan')
                                ->sum('nominal') ?? 0;
                            $pengajuanApproved = \App\Models\PengajuanTabungan::where('id_anggota', $pengajuan->id_anggota)
                                ->where('status', '2')
                                ->whereDoesntHave('transTabungan')
                                ->with('buktiFoto', 'janjiTemu')
                                ->get();
                            $totalSetoranPending = 0;
                            foreach ($pengajuanApproved as $p) {
                                if ($p->buktiFoto && $p->buktiFoto->count() > 0) {
                                    $totalSetoranPending += $p->buktiFoto->sum('nominal');
                                } elseif ($p->janjiTemu) {
                                    $totalSetoranPending += $p->janjiTemu->nominal ?? 0;
                                }
                            }
                        @endphp
                        <div class="mt-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <p class="text-xs font-semibold text-gray-700 mb-2">Detail Saldo:</p>
                            <div class="space-y-1 text-xs text-gray-600">
                                <p>Total Setoran (Transaksi): Rp {{ number_format($totalSetoranTrans, 0, ',', '.') }}</p>
                                <p>Total Penarikan (Transaksi): Rp {{ number_format($totalPenarikanTrans, 0, ',', '.') }}</p>
                                @if($totalSetoranPending > 0)
                                <p class="text-blue-600">Setoran Pending (Belum Transaksi): Rp {{ number_format($totalSetoranPending, 0, ',', '.') }}</p>
                                @endif
                                <p class="font-semibold text-gray-900 pt-1 border-t border-gray-300">Saldo Akhir: Rp {{ number_format($saldo, 0, ',', '.') }}</p>
                            </div>
                        </div>
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
                </div>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-6">
            @if($pengajuan->status == '1')
            <!-- Action Buttons -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-primary font-display mb-4">Tindakan</h3>
                <div class="space-y-3">
                    @if($saldo >= $pengajuan->nominal)
                    <form method="POST" action="{{ route('admin.tabungan.approve-tarik', $pengajuan->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui pengajuan penarikan ini?')">
                        @csrf
                        <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all font-medium shadow-md">
                            ✓ Setujui Penarikan
                        </button>
                    </form>
                    @else
                    <button disabled class="w-full px-4 py-3 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed font-medium">
                        Saldo Tidak Mencukupi
                    </button>
                    @endif
                    
                    <button onclick="showRejectModal()" class="w-full px-4 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg hover:from-red-700 hover:to-red-800 transition-all font-medium shadow-md">
                        ✗ Tolak Pengajuan
                    </button>
                </div>
            </div>

            <!-- Reject Modal -->
            <div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Tolak Pengajuan</h3>
                    <form method="POST" action="{{ route('admin.tabungan.reject-tarik', $pengajuan->id) }}">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan</label>
                            <textarea name="keterangan" rows="4" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none" placeholder="Masukkan alasan penolakan..."></textarea>
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

<script>
    function showRejectModal() {
        document.getElementById('rejectModal').classList.remove('hidden');
    }
    
    function hideRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }
</script>
@endsection


