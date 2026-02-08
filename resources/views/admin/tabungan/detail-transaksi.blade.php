@extends('layouts.admin')

@section('title', 'Detail Transaksi Tabungan')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Detail Transaksi Tabungan</h1>
            <p class="text-gray-600 mt-1">ID Transaksi: {{ $transaksi->id_transaksi ?? str_pad($transaksi->id, 5, '0', STR_PAD_LEFT) }}</p>
        </div>
        <div class="flex items-center space-x-3">
            @if(!$transaksi->id_pengajuan_setor && !$transaksi->id_pengajuan_tarik)
            <a href="{{ route('admin.tabungan.edit-transaksi', $transaksi->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                Edit Transaksi
            </a>
            <form method="POST" action="{{ route('admin.tabungan.destroy-transaksi', $transaksi->id) }}" class="inline" onsubmit="return confirm('Yakin hapus transaksi ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
                    Hapus
                </button>
            </form>
            @endif
            <a href="{{ route('admin.tabungan.transaksi') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium">
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
                        <p class="font-semibold text-gray-900">{{ $transaksi->nasabah->user->nama ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-semibold text-gray-900">{{ $transaksi->nasabah->user->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nomor HP</p>
                        <p class="font-semibold text-gray-900">{{ $transaksi->nasabah->user->nomor_hp ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">NIK</p>
                        <p class="font-semibold text-gray-900">{{ $transaksi->nasabah->dataKtp->nik ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Informasi Transaksi -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h2 class="text-lg font-bold text-primary font-display mb-4 pb-4 border-b border-gray-200">Informasi Transaksi</h2>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600">Tanggal Transaksi</p>
                        <p class="font-semibold text-gray-900">{{ $transaksi->tgl_transaksi->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Jenis</p>
                        <span class="inline-block mt-2 px-4 py-2 {{ $transaksi->jenis === 'setoran' ? 'bg-[#674c1d]/10 text-[#674c1d]' : 'bg-[#8b6f2f]/10 text-[#8b6f2f]' }} rounded-full text-sm font-semibold">
                            {{ ucfirst($transaksi->jenis) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nominal</p>
                        <p class="font-semibold text-[#674c1d] text-2xl">Rp {{ number_format($transaksi->nominal, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Via</p>
                        <p class="font-semibold text-gray-900">{{ ucfirst($transaksi->via) }}</p>
                    </div>
                    @if($transaksi->keterangan)
                    <div>
                        <p class="text-sm text-gray-600">Keterangan</p>
                        <p class="text-gray-900 mt-1">{{ $transaksi->keterangan }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Bukti Foto (jika setoran dari pengajuan) -->
            @if($transaksi->jenis === 'setoran' && $transaksi->pengajuanSetor && $transaksi->pengajuanSetor->buktiFoto->count() > 0)
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h2 class="text-lg font-bold text-primary font-display mb-4 pb-4 border-b border-gray-200">Bukti Foto Transfer</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($transaksi->pengajuanSetor->buktiFoto as $bukti)
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        @if($bukti->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($bukti->file_path))
                        <img src="{{ asset('storage/' . $bukti->file_path) }}" alt="Bukti Foto" class="w-full h-48 object-cover"
                            onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'200\'%3E%3Crect fill=\'%23f3f4f6\' width=\'400\' height=\'200\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%239ca3af\' font-family=\'Arial\' font-size=\'14\'%3EGambar tidak dapat dimuat%3C/text%3E%3C/svg%3E';">
                        @else
                        <div class="w-full h-48 bg-gray-100 flex items-center justify-center text-gray-500 text-sm">File tidak ditemukan</div>
                        @endif
                        <div class="p-3 bg-gray-50">
                            <p class="text-xs text-gray-600">{{ $bukti->keterangan ?? 'Bukti Transfer' }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @elseif($transaksi->jenis === 'penarikan' && $transaksi->pengajuanTarik && $transaksi->pengajuanTarik->foto_bukti_tf_admin)
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h2 class="text-lg font-bold text-primary font-display mb-4 pb-4 border-b border-gray-200">Bukti Transfer Admin</h2>
                <div class="max-w-md border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                    <img src="{{ asset('storage/' . $transaksi->pengajuanTarik->foto_bukti_tf_admin) }}" alt="Bukti Transfer" class="w-full h-auto cursor-pointer" onclick="window.open(this.src)">
                    <div class="p-3 bg-gray-50 text-center text-xs text-gray-500">
                        Bukti transfer di-upload saat persetujuan
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-primary font-display mb-4">Informasi</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">ID Transaksi</p>
                        <p class="font-semibold text-gray-900">#{{ $transaksi->id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Waktu Dibuat</p>
                        <p class="font-semibold text-gray-900">{{ $transaksi->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    @if($transaksi->pengajuanSetor)
                    <div>
                        <p class="text-sm text-gray-600">ID Pengajuan Setor</p>
                        <p class="font-semibold text-gray-900">#{{ $transaksi->pengajuanSetor->id }}</p>
                    </div>
                    @endif
                    @if($transaksi->pengajuanTarik)
                    <div>
                        <p class="text-sm text-gray-600">ID Pengajuan Tarik</p>
                        <p class="font-semibold text-gray-900">#{{ $transaksi->pengajuanTarik->id }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
