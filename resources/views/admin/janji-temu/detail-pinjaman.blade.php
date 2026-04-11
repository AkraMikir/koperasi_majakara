@extends('layouts.admin')

@section('title', 'Detail Janji Temu Pinjaman')

@section('content')
<div class="space-y-6">
    <div>
        <a href="{{ route('admin.janji-temu.index') }}"
            class="inline-flex items-center text-gray-500 hover:text-[[#674c1d]] transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
            Kembali ke Janji Temu
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Informasi Nasabah -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-[[#674c1d]]/5 rounded-full -mr-16 -mt-16"></div>
                <h2 class="text-xl font-bold text-gray-900 font-display mb-6 relative">Informasi Nasabah</h2>
                <div class="flex items-start gap-6 relative">
                    <div class="shrink-0">
                        <div
                            class="w-20 h-20 rounded-2xl bg-linear-to-br from-[[#674c1d]] to-[[#8b6f2f]] flex items-center justify-center shadow-lg transform rotate-3">
                            <span class="text-3xl font-bold text-white font-display">
                                {{ substr($janjiTemu->nasabah->user->nama ?? 'N', 0, 1) }}
                            </span>
                        </div>
                    </div>
                    <div class="space-y-4 flex-1">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Nama Lengkap</p>
                            <p class="font-bold text-gray-900 text-lg">{{ $janjiTemu->nasabah->user->nama ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Email</p>
                                <p class="font-medium text-gray-900">{{ $janjiTemu->nasabah->user->email ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Nomor Telepon</p>
                                <p class="font-medium text-gray-900">{{ $janjiTemu->nasabah->user->nomor_hp ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Janji Temu (data dari tbl_janji_temu_pinjaman) -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-xl font-bold text-gray-900 font-display mb-6">Detail Janji Temu</h2>
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <p class="text-sm text-gray-500 mb-1">Tanggal & Waktu</p>
                            <p class="font-bold text-gray-900 text-lg">
                                {{ $janjiTemu->tanggal_janji_temu ? \Carbon\Carbon::parse($janjiTemu->tanggal_janji_temu)->format('d M Y') : 'N/A' }}
                            </p>
                            <p class="text-sm text-[[#674c1d]] font-medium mt-1">
                                Pukul
                                {{ $janjiTemu->waktu_janji_temu ? \Carbon\Carbon::parse($janjiTemu->waktu_janji_temu)->format('H:i') : '-' }}
                                WIB
                            </p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <p class="text-sm text-gray-500 mb-1">Nominal Pinjaman</p>
                            <p class="font-bold text-gray-900 text-lg">Rp
                                {{ number_format($janjiTemu->nominal ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <p class="text-sm text-gray-500 mb-2">Lokasi Pertemuan</p>
                        <div class="flex items-start gap-3">
                            <div class="shrink-0 mt-1">
                                <svg class="w-5 h-5 text-[[#674c1d]]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $janjiTemu->lokasi->nama_lokasi ?? '-' }}</p>
                                @if($janjiTemu->lokasi && $janjiTemu->lokasi->alamat_lengkap)
                                <p class="text-sm text-gray-500 mt-1">{{ $janjiTemu->lokasi->alamat_lengkap }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($janjiTemu->pengajuan)
                    <div class="p-4 bg-blue-50 rounded-xl border border-blue-100">
                        <p class="text-sm text-gray-600 mb-2">Informasi Pinjaman (dari pengajuan)</p>
                        <p class="font-medium text-gray-900">Durasi {{ $janjiTemu->pengajuan->durasi }} bulan, Jenis
                            {{ ucfirst($janjiTemu->pengajuan->jenis ?? 'bulanan') }}</p>
                    </div>
                    @endif

                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <p class="text-sm text-gray-500 mb-2">Pesan / Keterangan</p>
                        <p class="text-gray-700 italic">"{{ $janjiTemu->keterangan ?? 'Tidak ada keterangan' }}"</p>
                    </div>

                    @if($janjiTemu->buktiFoto && $janjiTemu->buktiFoto->count() > 0)
                    <div class="pt-4 border-t border-gray-100">
                        <p class="text-sm font-bold text-gray-900 mb-3">Bukti Pencairan / Pertemuan</p>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            @foreach($janjiTemu->buktiFoto as $bukti)
                            <div class="aspect-square rounded-xl overflow-hidden border border-gray-200">
                                <img src="{{ asset('storage/' . $bukti->file_path) }}"
                                    class="w-full h-full object-cover" alt="Bukti">
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($janjiTemu->status == '2')
                    <div class="p-4 bg-green-50 rounded-xl border border-green-100">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <p class="font-bold text-green-800">Selesai Diproses</p>
                        </div>
                        @if($janjiTemu->keterangan_admin)
                        <p class="text-sm text-green-700 ml-11">{{ $janjiTemu->keterangan_admin }}</p>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar: Proses Janji Temu -->
        <div class="space-y-6 lg:sticky lg:top-6 lg:self-start">
            @if($janjiTemu->status == '1')
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 mb-4">Proses Janji Temu</h3>
                <p class="text-sm text-gray-500 mb-6">
                    Setelah pertemuan selesai dan uang diterima, proses janji temu ini untuk menyetujui dan mencairkan
                    pinjaman (sistem setujui + cairkan sama seperti pengajuan).
                </p>

                @if(!$janjiTemu->id_pengajuan)
                <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl text-amber-800 text-sm">
                    Janji temu ini belum terhubung ke pengajuan pinjaman. Tidak dapat diproses dari sini.
                </div>
                @else
                <form action="{{ route('admin.pinjaman.janji-temu.proses-pinjaman', ['id' => $janjiTemu->id]) }}"
                    method="post" enctype="multipart/form-data" accept-charset="UTF-8">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nominal Diterima</label>
                            <div class="relative">
                                <span class="absolute left-4 top-3 text-gray-500 font-medium">Rp</span>
                                <input type="text" value="{{ number_format($janjiTemu->nominal ?? 0, 0, ',', '.') }}"
                                    class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl font-bold text-gray-900"
                                    readonly>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Cair</label>
                            <input type="date" name="tgl_cair" value="{{ date('Y-m-d') }}" required
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[[#674c1d]] focus:border-transparent outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Foto (Opsional)</label>
                            <input type="file" name="bukti_transfer" accept="image/*"
                                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[[#674c1d]]/10 file:text-[[#674c1d]] hover:file:bg-[[#674c1d]]/20 transition-all">
                            <p class="text-xs text-gray-500 mt-1">Bisa upload foto uang / kwitansi / bukti penerimaan
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan Admin</label>
                            <textarea name="keterangan_admin" rows="3"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[[#674c1d]] focus:border-transparent outline-none"
                                placeholder="Catatan tambahan..."></textarea>
                        </div>
                        <button type="submit"
                            onclick="return confirm('Apakah Anda yakin uang sudah diterima? Pinjaman akan disetujui dan dicairkan (jadwal angsuran dibuat).')"
                            class="w-full py-3 bg-[[#674c1d]] hover:bg-[[#543d16]] text-white font-bold rounded-xl shadow-lg shadow-[[#674c1d]]/20 transition-all">
                            ✅ Selesaikan & Cairkan Pinjaman
                        </button>
                    </div>
                </form>
                @endif
            </div>
            @endif

            <div class="bg-[#674c1d] rounded-2xl shadow-lg p-6 text-white relative overflow-hidden" x-data="{ openCancel: false }">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-10 -mt-10"></div>
                <div class="relative z-10">
                    <h3 class="font-bold text-lg mb-2">Status Saat Ini</h3>
                    <span class="inline-flex px-4 py-1.5 rounded-full {{ str_replace('text-', 'text-white bg-opacity-20 border border-white/30 ', $janjiTemu->status_display['class']) }} font-semibold text-sm">
                        {{ $janjiTemu->status_display['label'] }}
                    </span>
                    
                    @if($janjiTemu->status == '1')
                    <div class="mt-6 pt-4 border-t border-white/10">
                        <button @click="openCancel = true" class="w-full px-4 py-2 bg-red-500/20 hover:bg-red-500/40 border border-red-500/30 text-white rounded-xl transition-all text-sm font-bold flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Batalkan Janji Temu
                        </button>
                    </div>

                    <!-- Modal Batal -->
                    <div x-show="openCancel" 
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 text-gray-900"
                        x-cloak>
                        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6" @click.away="openCancel = false">
                            <h3 class="text-xl font-bold mb-2">Konfirmasi Pembatalan</h3>
                            <p class="text-sm text-gray-600 mb-6">Harap berikan alasan pembatalan janji temu pinjaman ini.</p>
                            
                            <form action="{{ route('admin.janji-temu.cancel-pinjaman', $janjiTemu->id) }}" method="POST">
                                @csrf
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Pembatalan <span class="text-red-500">*</span></label>
                                    <textarea name="keterangan_admin" required rows="3" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none resize-none"
                                        placeholder="Alasan pembatalan..."></textarea>
                                </div>
                                <div class="flex gap-3">
                                    <button type="button" @click="openCancel = false" class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium">Batal</button>
                                    <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium">Konfirmasi Batal</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @else
                    <p class="mt-4 text-sm text-white/80">Janji temu ini sudah diproses atau dibatalkan.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection