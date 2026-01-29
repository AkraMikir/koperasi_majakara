@extends('layouts.nasabah')

@section('title', 'Nabung Sekarang')

@section('content')
<div class="w-full pb-6">
    <!-- Pilihan Metode -->
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                <h2 class="text-lg font-bold text-[#674c1d] font-display">Pilih Metode Setoran</h2>
                    </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Metode Tunai -->
                <button onclick="selectMethod('tunai')" id="btn-tunai" class="group p-6 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl border-2 border-gray-200 hover:border-[#674c1d] transition-all text-left">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-[#674c1d] to-[#4a3514] rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">Tunai</h3>
                            <p class="text-sm text-gray-600">Setor langsung di kantor</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>Datang ke kantor terdekat</span>
                    </div>
                        </button>

                <!-- Metode Transfer -->
                <button onclick="selectMethod('transfer')" id="btn-transfer" class="group p-6 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl border-2 border-gray-200 hover:border-[#8b6f2f] transition-all text-left">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-[#8b6f2f] to-[#d4af37] rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">Transfer</h3>
                            <p class="text-sm text-gray-600">Transfer via bank/mobile banking</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <span>Upload bukti transfer</span>
                    </div>
                </button>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div id="action-section" class="mx-4 mb-6 hidden">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div id="action-content"></div>
        </div>
    </div>

    <!-- Riwayat Setoran -->
        <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-[#674c1d] font-display">Riwayat Setoran</h2>
                </div>
                </div>
                
            <div class="space-y-3">
                @forelse($riwayatTabungan ?? [] as $riwayat)
                <a href="{{ route('nasabah.tabungan.detail-transaksi', $riwayat->id) }}" class="block p-4 bg-gray-50 rounded-xl border border-gray-200 hover:border-[#674c1d]/30 hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Setoran</p>
                                <p class="text-sm text-gray-500">{{ $riwayat->tgl_transaksi->format('d M Y') }} • {{ ucfirst($riwayat->via) }}</p>
                            </div>
                        </div>
                        <div class="text-right flex items-center gap-2">
                            <div>
                                <p class="font-bold text-green-600">+Rp {{ number_format($riwayat->nominal, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-500 font-mono">{{ $riwayat->id_transaksi ?? 'TRX-' . str_pad($riwayat->id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </a>
                @empty
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-gray-500">Belum ada riwayat setoran</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let selectedMethod = null;

    function selectMethod(method) {
        selectedMethod = method;
        
        // Update button styles
        document.querySelectorAll('[id^="btn-"]').forEach(btn => {
            btn.classList.remove('border-[#674c1d]', 'border-[#8b6f2f]', 'bg-gradient-to-br', 'from-[#674c1d]/10', 'to-[#8b6f2f]/10');
            btn.classList.add('border-gray-200');
        });
        
        const actionSection = document.getElementById('action-section');
        const actionContent = document.getElementById('action-content');
        
        if (method === 'tunai') {
            document.getElementById('btn-tunai').classList.add('border-[#674c1d]', 'bg-gradient-to-br', 'from-[#674c1d]/10', 'to-[#8b6f2f]/10');
            
            actionContent.innerHTML = `
                <div class="text-center space-y-4">
                    <div class="flex items-center justify-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Setor Tunai di Kantor</h3>
                        <p class="text-sm text-gray-600 mb-4">Untuk setoran tunai, Anda perlu membuat janji temu terlebih dahulu</p>
                        <a href="{{ route('nasabah.tabungan.janji-temu') }}" 
                            class="inline-block px-6 py-3 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-semibold hover:from-[#4a3514] hover:to-[#674c1d] transition-all">
                            Buat Janji Temu
                        </a>
                    </div>
                </div>
            `;
        } else {
            document.getElementById('btn-transfer').classList.add('border-[#8b6f2f]', 'bg-gradient-to-br', 'from-[#8b6f2f]/10', 'to-[#d4af37]/10');
            
            actionContent.innerHTML = `
                <div class="text-center space-y-4">
                    <div class="flex items-center justify-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-[#8b6f2f] to-[#d4af37] rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Setor via Transfer</h3>
                        <p class="text-sm text-gray-600 mb-4">Upload bukti transfer untuk pengajuan setoran</p>
                        <a href="{{ route('nasabah.tabungan.pengajuan-transfer') }}" 
                            class="inline-block px-6 py-3 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-semibold hover:from-[#4a3514] hover:to-[#674c1d] transition-all">
                            Buat Pengajuan Transfer
                        </a>
                    </div>
                </div>
            `;
        }
        
        actionSection.classList.remove('hidden');
        actionSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
</script>
@endpush
@endsection
