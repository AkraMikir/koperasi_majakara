@extends('layouts.admin')

@section('title', 'Detail Pengajuan Deposito')

@section('content')
<div class="p-6 max-w-3xl mx-auto">
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('admin.deposito.index') }}" class="hover:text-[#674c1d]">Deposito</a>
        <span>/</span>
        <a href="{{ route('admin.deposito.pengajuan-list') }}" class="hover:text-[#674c1d]">Pengajuan</a>
        <span>/</span>
        <span class="text-gray-800 font-medium">#{{ $pengajuan->id }}</span>
    </div>

    @if(session('success'))
    <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 mb-4">
        <div class="flex items-center justify-between mb-5">
            <h1 class="text-lg font-bold text-gray-800">Detail Pengajuan Deposito</h1>
            @if($pengajuan->status === '1')
                <span class="bg-amber-50 text-amber-600 px-3 py-1 rounded-full text-xs font-bold">Pending</span>
            @elseif($pengajuan->status === '2')
                <span class="bg-green-50 text-green-700 px-3 py-1 rounded-full text-xs font-bold">Disetujui</span>
            @else
                <span class="bg-red-50 text-red-600 px-3 py-1 rounded-full text-xs font-bold">Ditolak</span>
            @endif
        </div>

        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-400 text-xs mb-0.5">Nasabah</p>
                <p class="font-semibold text-gray-800">{{ $pengajuan->nasabah->user->nama ?? '-' }}</p>
                <p class="text-xs text-gray-500">{{ $pengajuan->nasabah->user->email ?? '-' }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs mb-0.5">Tanggal Pengajuan</p>
                <p class="font-semibold text-gray-800">{{ $pengajuan->created_at->format('d F Y, H:i') }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs mb-0.5">Nominal</p>
                <p class="font-bold text-xl text-[#674c1d]">Rp {{ number_format($pengajuan->nominal, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs mb-0.5">Tenor</p>
                <p class="font-semibold text-gray-800">{{ $pengajuan->tenor->tenor_bulan ?? '-' }} bulan ({{ $pengajuan->tenor->tenor_hari ?? '-' }} hari)</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs mb-0.5">Metode Setoran</p>
                @if($pengajuan->metode_setor === 'transfer')
                    <span class="bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full text-xs font-bold">Transfer Bank</span>
                @else
                    <span class="bg-green-50 text-green-700 px-2 py-0.5 rounded-full text-xs font-bold">Saldo Tabungan</span>
                @endif
            </div>
            <div>
                <p class="text-gray-400 text-xs mb-0.5">Catatan Nasabah</p>
                <p class="text-gray-600 text-sm">{{ $pengajuan->catatan ?: '-' }}</p>
            </div>
        </div>

        @if($pengajuan->metode_setor === 'transfer' && $pengajuan->foto_bukti_tf)
        <div class="mt-4 pt-4 border-t border-gray-100">
            <p class="text-xs text-gray-400 mb-2">Bukti Transfer</p>
            <img src="{{ Storage::url($pengajuan->foto_bukti_tf) }}" alt="Bukti Transfer" class="max-h-64 rounded-lg border border-gray-200 object-contain">
        </div>
        @elseif($pengajuan->metode_setor === 'transfer' && !$pengajuan->foto_bukti_tf)
        <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-lg text-sm text-amber-700">
            ⚠ Nasabah belum mengupload bukti transfer.
        </div>
        @endif

        @if($pengajuan->catatan_admin)
        <div class="mt-4 p-3 bg-gray-50 border border-gray-200 rounded-lg">
            <p class="text-xs text-gray-400 mb-1">Catatan Admin</p>
            <p class="text-sm text-gray-700">{{ $pengajuan->catatan_admin }}</p>
        </div>
        @endif
    </div>

    @if($pengajuan->status === '1')
    <div class="grid grid-cols-2 gap-4">
        {{-- Approve --}}
        <form action="{{ route('admin.deposito.approve', $pengajuan->id) }}" method="POST">
            @csrf
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 mb-3">
                <h2 class="font-bold text-green-700 text-sm mb-3">Setujui Pengajuan</h2>
                <label class="text-xs text-gray-600 block mb-1">Catatan Admin (opsional)</label>
                <textarea name="catatan_admin" rows="2" placeholder="Catatan untuk nasabah..." class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-green-500 resize-none mb-3"></textarea>

                @if($pengajuan->metode_setor === 'transfer')
                <label class="text-xs text-gray-600 block mb-1">Simpan Dana Ke <span class="text-red-500">*</span></label>
                <select name="metode_bayar" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-green-500 mb-3" required>
                    <option value="transfer_koperasi">Rekening Koperasi (Utama)</option>
                    <option value="transfer_admin">Rekening Admin (Petty Cash TF)</option>
                    <option value="cash">Cash ke Admin (Petty Cash Cash)</option>
                </select>
                @endif
            </div>
            <button type="submit" onclick="return confirm('Yakin ingin menyetujui pengajuan ini?')" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-xl text-sm transition">
                ✓ Setujui Deposito
            </button>
        </form>

        {{-- Reject --}}
        <form action="{{ route('admin.deposito.reject', $pengajuan->id) }}" method="POST">
            @csrf
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 mb-3">
                <h2 class="font-bold text-red-600 text-sm mb-3">Tolak Pengajuan</h2>
                <label class="text-xs text-gray-600 block mb-1">Alasan Penolakan <span class="text-red-500">*</span></label>
                <textarea name="catatan_admin" rows="2" placeholder="Jelaskan alasan penolakan..." class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-red-400 resize-none" required></textarea>
            </div>
            <button type="submit" onclick="return confirm('Yakin ingin menolak pengajuan ini?')" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-3 rounded-xl text-sm transition">
                ✕ Tolak Pengajuan
            </button>
        </form>
    </div>
    @endif
</div>
@endsection
