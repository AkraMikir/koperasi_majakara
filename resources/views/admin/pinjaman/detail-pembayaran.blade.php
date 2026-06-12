@extends('layouts.admin')

@section('title', 'Detail Pembayaran Pinjaman')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Detail Pembayaran Pinjaman</h1>
            <p class="text-gray-600 mt-1">ID Pengajuan: #{{ $pengajuan->id }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.pinjaman.struk-pembayaran', $pengajuan->id) }}" target="_blank" class="px-4 py-2 bg-[#674c1d] text-white rounded-lg hover:bg-[#8b6f2f] transition-colors text-sm font-medium inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Download PDF Struk
            </a>
            <a href="{{ route('admin.pinjaman.pembayaran') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium">
                ← Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-r-lg shadow-sm">
                <p class="font-bold flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Validasi Gagal!
                </p>
                <ul class="list-disc pl-5 mt-2 space-y-1 text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            
            @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-r-lg shadow-sm">
                <p class="font-medium flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('error') }}
                </p>
            </div>
            @endif

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

            <!-- Informasi Pembayaran -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h2 class="text-lg font-bold text-primary font-display mb-4 pb-4 border-b border-gray-200">Informasi Pembayaran</h2>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600">Tanggal Pengajuan</p>
                        <p class="font-semibold text-gray-900">{{ $pengajuan->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nominal Pembayaran</p>
                        <p class="font-semibold text-[#674c1d] text-2xl">Rp {{ number_format($pengajuan->nominal, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Pinjaman</p>
                        <p class="font-semibold text-gray-900">#{{ str_pad($pengajuan->pinjaman_id, 6, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Metode Pembayaran</p>
                        @php $isTunai = ($pengajuan->metode_pembayaran ?? '') === 'tunai' || (!$pengajuan->rekening_tujuan && $pengajuan->janjiTemu); @endphp
                        @if($isTunai)
                        <span class="inline-block mt-2 px-4 py-2 bg-amber-100 text-amber-800 rounded-full text-sm font-semibold">
                            Tunai (Janji Temu)
                        </span>
                        <p class="text-xs text-gray-500 mt-1">Wajib upload bukti foto pertemuan setelah menyetujui.</p>
                        @else
                        <span class="inline-block mt-2 px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                            Transfer
                        </span>
                        @endif
                    </div>
                    @if($pengajuan->rekening_tujuan)
                    <div>
                        <p class="text-sm text-gray-600">Rekening Tujuan</p>
                        <p class="font-semibold text-gray-900">{{ $pengajuan->rekening_tujuan }}</p>
                    </div>
                    @endif
                    <div>
                        @php
                            $statusConfig = [
                                '1' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Pending'],
                                '2' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Ditolak'],
                                '3' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'Disetujui'],
                                '4' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Terlaksana'],
                            ];
                            $status = $statusConfig[$pengajuan->status] ?? $statusConfig['1'];
                        @endphp
                        <span class="inline-block mt-2 px-4 py-2 {{ $status['bg'] }} {{ $status['text'] }} rounded-full text-sm font-semibold">
                            {{ $status['label'] }}
                        </span>
                    </div>
                    @if($pengajuan->keterangan)
                    <div>
                        <p class="text-sm text-gray-600">Keterangan Nasabah</p>
                        <p class="text-gray-900 mt-1">{{ $pengajuan->keterangan }}</p>
                    </div>
                    @endif
                    @if($pengajuan->keterangan_admin)
                    <div>
                        <p class="text-sm text-gray-600">Keterangan Admin</p>
                        <p class="text-gray-900 mt-1">{{ $pengajuan->keterangan_admin }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Informasi Angsuran -->
            @if($angsuran)
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h2 class="text-lg font-bold text-primary font-display mb-4 pb-4 border-b border-gray-200">Informasi Angsuran</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">No. Urut:</span>
                        <span class="font-semibold text-gray-900">#{{ $angsuran->no_urut }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Jatuh Tempo:</span>
                        <span class="font-semibold text-gray-900">{{ $angsuran->tgl_jatuh_tempo->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Jumlah Tagihan:</span>
                        <span class="font-semibold text-gray-900">Rp {{ number_format($angsuran->jumlah_tagihan, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Terbayar:</span>
                        <span class="font-semibold text-green-600">Rp {{ number_format($angsuran->jumlah_terbayar ?? 0, 0, ',', '.') }}</span>
                    </div>
                    @php
                        $sisa = max(0, $angsuran->jumlah_tagihan - ($angsuran->jumlah_terbayar ?? 0));
                        $hariTelat = $angsuran->tgl_jatuh_tempo < now() && $angsuran->status_bayar !== 'lunas' 
                            ? (int) now()->diffInDays($angsuran->tgl_jatuh_tempo, false) 
                            : 0;
                        $denda = $angsuran->denda ?? 0;
                        if ($hariTelat > 0 && !$denda) {
                            $dendaPersen = $pengajuan->pinjaman->denda_persen ?? 0.02;
                            $denda = $sisa * ($dendaPersen / 100) * $hariTelat;
                            $dendaMax = $angsuran->jumlah_tagihan * 0.5;
                            $denda = min($denda, $dendaMax);
                        }
                    @endphp
                    @if($denda > 0)
                    <div class="flex justify-between">
                        <span class="text-sm text-red-600">Denda:</span>
                        <span class="font-semibold text-red-600">Rp {{ number_format($denda, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between pt-2 border-t border-gray-300">
                        <span class="font-semibold text-gray-900">Total Tagihan + Denda:</span>
                        <span class="font-bold text-[#674c1d]">Rp {{ number_format($angsuran->jumlah_tagihan + $denda, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Bukti Foto (Transfer / Serah Terima) -->
            @if($pengajuan->buktiFoto->count() > 0)
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h2 class="text-lg font-bold text-primary font-display mb-4 pb-4 border-b border-gray-200">Bukti Foto</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($pengajuan->buktiFoto as $bukti)
                    <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                        <div class="w-full h-52 bg-gray-100 overflow-hidden flex items-center justify-center">
                            @php
                                $fileExists = \Illuminate\Support\Facades\Storage::disk('public')->exists($bukti->file_path);
                                $imageUrl = asset('storage/' . $bukti->file_path);
                            @endphp
                            @if($fileExists)
                            <img src="{{ $imageUrl }}" alt="Bukti" class="w-full h-full object-contain cursor-pointer hover:opacity-95 transition-opacity" onclick="window.open('{{ $imageUrl }}', '_blank', 'noopener')" title="Klik untuk memperbesar">
                            @else
                            <div class="text-center p-4">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"></path></svg>
                                <p class="text-sm text-gray-500">File tidak ditemukan</p>
                            </div>
                            @endif
                        </div>
                        @if($bukti->keterangan)
                        <div class="p-3 bg-gray-50 border-t border-gray-100">
                            <p class="text-xs text-gray-600">{{ $bukti->keterangan }}</p>
                        </div>
                        @endif
                        @if($fileExists)
                        <div class="px-3 py-2 bg-white border-t border-gray-100">
                            <a href="{{ $imageUrl }}" target="_blank" rel="noopener" class="text-xs font-medium text-[#674c1d] hover:underline">Buka / Unduh</a>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Janji Temu (Cash) -->
            @if($pengajuan->janjiTemu)
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h2 class="text-lg font-bold text-primary font-display mb-4 pb-4 border-b border-gray-200">Janji Temu</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Tanggal & Waktu</p>
                        <p class="font-semibold text-gray-900">{{ $pengajuan->janjiTemu->tanggal_janji_temu->format('d M Y') }}, {{ $pengajuan->janjiTemu->waktu_janji_temu->format('H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Lokasi</p>
                        <p class="font-semibold text-gray-900">{{ $pengajuan->janjiTemu->lokasi->nama_lokasi ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $pengajuan->janjiTemu->lokasi->alamat_lengkap ?? '' }}</p>
                    </div>
                    @if($pengajuan->janjiTemu->keterangan)
                    <div>
                        <p class="text-sm text-gray-600">Keterangan</p>
                        <p class="text-gray-900 mt-1">{{ $pengajuan->janjiTemu->keterangan }}</p>
                    </div>
                    @endif
                </div>
            </div>

            @endif
        </div>

        <!-- Sidebar Actions (sticky) -->
        @php $isTunaiPembayaran = ($pengajuan->metode_pembayaran ?? '') === 'tunai' || (!$pengajuan->rekening_tujuan && $pengajuan->janjiTemu); @endphp
        <div class="space-y-6 lg:sticky lg:top-6 lg:self-start">
            @if($pengajuan->status === '1' && $isTunaiPembayaran)
            <!-- Tunai/Janji Temu: Langsung upload bukti foto (tanpa setujui dulu) -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-primary font-display mb-2">Bukti Foto Pertemuan</h3>
                <p class="text-sm text-gray-600 mb-4">Upload bukti foto bahwa admin dan nasabah telah bertemu serta pembayaran tunai diterima. Setelah upload, pembayaran akan otomatis dikonfirmasi.</p>
                <form method="POST" action="{{ route('admin.pinjaman.upload-serah-terima', $pengajuan->id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Foto Pertemuan Admin & Nasabah *</label>
                        <input type="file" name="foto_serah_terima" accept="image/*" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none">
                        <p class="text-xs text-gray-500 mt-1">Wajib upload. Format: JPG, PNG (Max 5MB)</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan (Opsional)</label>
                        <textarea name="keterangan" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none"
                            placeholder="Tambahkan keterangan..."></textarea>
                    </div>
                    <button type="submit" onclick="return confirm('Upload foto akan mengkonfirmasi pembayaran dan memperbarui angsuran. Lanjutkan?')" 
                        class="w-full px-4 py-3 bg-linear-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all font-medium shadow-md">
                        ✓ Upload Bukti & Konfirmasi Pembayaran
                    </button>
                </form>
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <button type="button" onclick="showRejectModal()" class="w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                        ✗ Tolak Pembayaran
                    </button>
                </div>
            </div>
            <div id="rejectModal" class="hidden fixed inset-0 bg-gray-600/50 backdrop-blur-sm z-50 flex items-center justify-center">
                <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Tolak Pembayaran</h3>
                    <form method="POST" action="{{ route('admin.pinjaman.reject-pembayaran', $pengajuan->id) }}">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan *</label>
                            <textarea name="keterangan_admin" rows="4" required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none"
                                placeholder="Masukkan alasan penolakan (akan dilihat nasabah)..."></textarea>
                        </div>
                        <div class="flex space-x-3">
                            <button type="button" onclick="hideRejectModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Batal</button>
                            <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">Tolak</button>
                        </div>
                    </form>
                </div>
            </div>
            @elseif($pengajuan->status === '1')
            <!-- Transfer: Approve/Reject dulu -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-primary font-display mb-4">Tindakan</h3>
                <form method="POST" action="{{ route('admin.pinjaman.approve-pembayaran', $pengajuan->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui pembayaran ini?')" class="mb-3">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Metode Penerimaan Duit *</label>
                        <select name="metode_penerimaan" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none">
                            <option value="rek_koperasi">Rekening Kas Utama (Koperasi)</option>
                            <option value="rek_admin">Rekening Admin (Petty Cash Transfer)</option>
                            <option value="cash_admin">Tunai Admin (Petty Cash Cash)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan (Opsional)</label>
                        <textarea name="keterangan_admin" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none"
                            placeholder="Tambahkan keterangan..."></textarea>
                    </div>
                    <button type="submit" class="w-full px-4 py-3 bg-linear-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all font-medium shadow-md">
                        ✓ Setujui Pembayaran
                    </button>
                </form>
                <button onclick="showRejectModal()" class="w-full px-4 py-3 bg-linear-to-r from-red-600 to-red-700 text-white rounded-lg hover:from-red-700 hover:to-red-800 transition-all font-medium shadow-md">
                    ✗ Tolak Pembayaran
                </button>
            </div>
            <div id="rejectModal" class="hidden fixed inset-0 bg-gray-600/50 backdrop-blur-sm z-50 flex items-center justify-center">
                <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Tolak Pembayaran</h3>
                    <form method="POST" action="{{ route('admin.pinjaman.reject-pembayaran', $pengajuan->id) }}">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan *</label>
                            <textarea name="keterangan_admin" rows="4" required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none"
                                placeholder="Masukkan alasan penolakan (akan dilihat nasabah)..."></textarea>
                        </div>
                        <div class="flex space-x-3">
                            <button type="button" onclick="hideRejectModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Batal</button>
                            <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">Tolak</button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            @if($pengajuan->status === '3' && $pengajuan->rekening_tujuan)
            <!-- Konfirmasi Pembayaran Transfer -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-primary font-display mb-4">Konfirmasi Pembayaran</h3>
                <form method="POST" action="{{ route('admin.pinjaman.konfirmasi-pembayaran', $pengajuan->id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Bukti Transfer (Opsional)</label>
                        <input type="file" name="bukti_transfer" accept="image/*"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan (Opsional)</label>
                        <textarea name="keterangan" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none"
                            placeholder="Tambahkan keterangan..."></textarea>
                    </div>
                    <button type="submit" onclick="return confirm('Konfirmasi pembayaran akan memperbarui angsuran. Lanjutkan?')" 
                        class="w-full px-4 py-3 bg-linear-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all font-medium shadow-md">
                        ✓ Konfirmasi Pembayaran
                    </button>
                </form>
            </div>
            @endif

            @if($pengajuan->status === '3' && $isTunaiPembayaran)
            <!-- Tunai sudah disetujui tapi belum upload: tampilkan form upload (fallback) -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-primary font-display mb-2">Bukti Foto Pertemuan</h3>
                <p class="text-sm text-gray-600 mb-4">Upload bukti foto bahwa admin dan nasabah telah bertemu serta pembayaran tunai diterima.</p>
                <form method="POST" action="{{ route('admin.pinjaman.upload-serah-terima', $pengajuan->id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Foto Pertemuan Admin & Nasabah *</label>
                        <input type="file" name="foto_serah_terima" accept="image/*" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none">
                        <p class="text-xs text-gray-500 mt-1">Wajib upload. Format: JPG, PNG (Max 5MB)</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan (Opsional)</label>
                        <textarea name="keterangan" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none"
                            placeholder="Tambahkan keterangan..."></textarea>
                    </div>
                    <button type="submit" onclick="return confirm('Upload foto akan mengkonfirmasi pembayaran dan memperbarui angsuran. Lanjutkan?')" 
                        class="w-full px-4 py-3 bg-linear-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all font-medium shadow-md">
                        ✓ Upload Bukti & Konfirmasi Pembayaran
                    </button>
                </form>
            </div>
            @endif

            @if($pengajuan->status === '4')
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <div class="text-center py-8">
                    <svg class="w-16 h-16 text-green-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="font-semibold text-gray-900">Pembayaran Sudah Terlaksana</p>
                    @if($pengajuan->tgl_pembayaran)
                    <p class="text-sm text-gray-600 mt-1">{{ $pengajuan->tgl_pembayaran->format('d M Y, H:i') }}</p>
                    @endif
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
