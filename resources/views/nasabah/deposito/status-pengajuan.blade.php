@extends('layouts.nasabah')

@section('title', 'Status Pengajuan Deposito')

@section('content')
<div class="w-full pb-6">

    {{-- ===== HEADER ===== --}}
    <div class="bg-gradient-to-r from-[#4a3514] to-[#8b6f2f] px-4 pt-6 pb-16">
        <div class="flex items-center gap-3">
            <a href="{{ route('nasabah.deposito.index') }}" class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center text-white hover:bg-white/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-white font-bold text-lg">Status Pengajuan</h1>
                <p class="text-white/70 text-xs">Deposito #{{ $pengajuan->id }}</p>
            </div>
        </div>
    </div>

    {{-- ===== STATUS CARD ===== --}}
    @php
        $status = $pengajuan->status;
        $statusConfig = [
            '1' => [
                'label'    => 'Sedang Diproses',
                'icon'     => '⏳',
                'desc'     => 'Pengajuan Anda sedang dalam antrian review. Proses evaluasi membutuhkan 1×24 jam kerja.',
                'steps'    => ['done' => 1, 'active' => 2],
                'bg'       => 'bg-amber-50',
                'border'   => 'border-amber-200',
                'badge'    => 'bg-amber-100 text-amber-700',
            ],
            '2' => [
                'label'    => 'Disetujui',
                'icon'     => '✅',
                'desc'     => 'Selamat! Pengajuan deposito Anda telah disetujui. Nomor deposito akan segera diterbitkan.',
                'steps'    => ['done' => 3, 'active' => 0],
                'bg'       => 'bg-green-50',
                'border'   => 'border-green-200',
                'badge'    => 'bg-green-100 text-green-700',
            ],
            '3' => [
                'label'    => 'Ditolak',
                'icon'     => '❌',
                'desc'     => 'Mohon maaf, pengajuan Anda tidak dapat diproses. Silakan hubungi CS kami untuk informasi lebih lanjut.',
                'steps'    => ['done' => 1, 'active' => 0],
                'bg'       => 'bg-red-50',
                'border'   => 'border-red-200',
                'badge'    => 'bg-red-100 text-red-700',
            ],
        ];
        $cfg = $statusConfig[$status] ?? $statusConfig['1'];
    @endphp

    <div class="mx-4 -mt-10 relative z-10 mb-4">
        <div class="bg-white rounded-2xl shadow-md overflow-hidden">
            {{-- Status Hero --}}
            <div class="{{ $cfg['bg'] }} {{ $cfg['border'] }} border-b p-6 text-center">
                <div class="text-5xl mb-3">{{ $cfg['icon'] }}</div>
                <span class="text-sm font-bold px-4 py-1.5 rounded-full {{ $cfg['badge'] }}">{{ $cfg['label'] }}</span>
                @if($pengajuan->status === '2' && $pengajuan->deposito)
                <div class="mt-3">
                    <a href="{{ route('nasabah.struk-deposito', $pengajuan->deposito->id) }}" target="_blank"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-xl transition-colors shadow-sm text-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download PDF Struk
                    </a>
                </div>
                @endif
                <p class="text-xs text-gray-600 mt-3 leading-relaxed">{{ $cfg['desc'] }}</p>
            </div>

            {{-- Timeline --}}
            <div class="p-5">
                <h3 class="font-bold text-[#674c1d] text-sm mb-4">Timeline Proses</h3>
                <div class="relative">
                    {{-- Vertical line --}}
                    <div class="absolute left-4 top-4 bottom-4 w-0.5 bg-gray-200"></div>

                    @php
                        $steps = [
                            ['label' => 'Pengajuan Dikirim', 'desc' => $pengajuan->created_at->format('d M Y, H:i'), 'done' => true],
                            ['label' => 'Dalam Review Admin', 'desc' => $status === '1' ? 'Menunggu persetujuan...' : ($status === '3' ? 'Ditolak' : 'Selesai ditinjau'), 'done' => in_array($status, ['2', '3']), 'active' => $status === '1'],
                            ['label' => 'Deposito Aktif', 'desc' => $status === '2' ? 'Nomor deposito diterbitkan' : 'Menunggu persetujuan', 'done' => $status === '2', 'active' => false, 'skip' => $status === '3'],
                        ];
                    @endphp

                    <div class="space-y-6">
                        @foreach($steps as $step)
                        @if(!($step['skip'] ?? false))
                        <div class="flex items-start gap-4">
                            <div class="relative z-10 w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0
                                {{ $step['done'] ? 'bg-green-500' : (($step['active'] ?? false) ? 'bg-gradient-to-br from-[#674c1d] to-[#d4af37]' : 'bg-gray-200') }}
                                shadow-sm">
                                @if($step['done'])
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                @elseif($step['active'] ?? false)
                                <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                                @else
                                <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                                @endif
                            </div>
                            <div class="pt-1">
                                <p class="font-semibold text-sm {{ $step['done'] ? 'text-gray-800' : (($step['active'] ?? false) ? 'text-[#674c1d]' : 'text-gray-400') }}">
                                    {{ $step['label'] }}
                                </p>
                                <p class="text-xs {{ $step['done'] || ($step['active'] ?? false) ? 'text-gray-500' : 'text-gray-300' }} mt-0.5">
                                    {{ $step['desc'] }}
                                </p>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== DETAIL PENGAJUAN ===== --}}
    <div class="mx-4 mb-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="p-4 border-b border-gray-100">
                <h3 class="font-bold text-[#674c1d] text-sm">Ringkasan Pengajuan</h3>
            </div>
            <div class="p-4">
                @foreach([
                    ['label' => 'ID Pengajuan', 'value' => '#' . $pengajuan->id, 'mono' => true],
                    ['label' => 'Tenor', 'value' => ($pengajuan->tenor?->tenor_bulan ?? '-') . ' Bulan'],
                    ['label' => 'Nominal', 'value' => 'Rp ' . number_format($pengajuan->nominal, 0, ',', '.'), 'bold' => true],
                    ['label' => 'Metode Setor', 'value' => ucwords(str_replace('_', ' ', $pengajuan->metode_setor))],
                    ['label' => 'Tanggal Pengajuan', 'value' => $pengajuan->created_at->format('d M Y, H:i')],
                ] as $row)
                <div class="flex justify-between items-center py-3 border-b border-gray-50 last:border-0">
                    <span class="text-sm text-gray-500">{{ $row['label'] }}</span>
                    <span class="text-sm {{ ($row['mono'] ?? false) ? 'font-mono text-gray-700' : (($row['bold'] ?? false) ? 'font-bold text-[#674c1d]' : 'font-semibold text-gray-700') }}">{{ $row['value'] }}</span>
                </div>
                @endforeach
            </div>

            {{-- Bukti Transfer jika ada --}}
            @if($pengajuan->foto_bukti_tf)
            <div class="px-4 pb-4">
                <p class="text-xs font-semibold text-gray-500 mb-2">Bukti Transfer</p>
                <img src="{{ asset('storage/' . $pengajuan->foto_bukti_tf) }}" alt="Bukti Transfer" class="w-full rounded-xl border border-gray-200 object-cover max-h-48">
            </div>
            @endif

            {{-- Catatan Penolakan --}}
            @if($pengajuan->status === '3' && $pengajuan->catatan)
            <div class="mx-4 mb-4 p-4 bg-red-50 border border-red-200 rounded-xl">
                <p class="text-xs font-bold text-red-700 mb-1">📋 Alasan Penolakan</p>
                <p class="text-xs text-red-600">{{ $pengajuan->catatan }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- ===== AKSI ===== --}}
    <div class="mx-4 space-y-3">
        @if($pengajuan->status === '3')
        <a href="{{ route('nasabah.deposito.pengajuan') }}" class="flex items-center justify-center gap-2 w-full bg-gradient-to-r from-[#674c1d] to-[#d4af37] text-white font-bold py-4 rounded-xl text-sm shadow-md active:scale-95 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Ajukan Ulang
        </a>
        @endif
        <a href="{{ route('nasabah.deposito.index') }}" class="flex items-center justify-center gap-2 w-full border-2 border-[#674c1d] text-[#674c1d] font-bold py-4 rounded-xl text-sm active:scale-95 transition-all">
            ← Kembali ke Deposito
        </a>
    </div>

</div>
@endsection
