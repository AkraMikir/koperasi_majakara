@extends('layouts.nasabah')

@section('title', 'Pengajuan Pinjaman')

@section('content')
<div class="w-full pb-6">
    <!-- Hero Section -->
    <div class="mx-4 mt-4 mb-6">
        <div class="bg-gradient-to-br from-[#8b6f2f] via-[#a0824d] to-[#d4af37] rounded-3xl shadow-2xl p-8 border-2 border-[#d4af37]/30 relative overflow-hidden">
            <div class="relative z-10">
                <h1 class="text-3xl font-bold text-white mb-2 font-display">Ajukan Pinjaman</h1>
                <p class="text-white/90 text-sm">Isi form di bawah untuk mengajukan pinjaman baru</p>
            </div>
        </div>
    </div>

    <!-- Form Pengajuan -->
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-lg font-bold text-[#8b6f2f] mb-6 font-display">Form Pengajuan Pinjaman</h2>
            
            <form action="{{ route('nasabah.pinjaman.submit-pengajuan') }}" method="POST" id="formPengajuan">
                @csrf
                
                <!-- Nominal -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nominal Pinjaman</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                        <input type="number" name="nominal" id="nominal" 
                            class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 transition-all"
                            placeholder="Masukkan nominal pinjaman" min="100000" step="10000" required>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Minimum: Rp 100.000</p>
                </div>

                <!-- Jenis Pinjaman -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Angsuran</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="relative">
                            <input type="radio" name="jenis" value="bulanan" class="peer hidden" checked required>
                            <div class="border-2 border-gray-200 rounded-xl p-4 cursor-pointer peer-checked:border-[#8b6f2f] peer-checked:bg-[#8b6f2f]/5 transition-all">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-[#8b6f2f]/10 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-[#8b6f2f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">Bulanan</p>
                                        <p class="text-xs text-gray-500">Angsuran per bulan</p>
                                    </div>
                                </div>
                            </div>
                        </label>
                        <label class="relative">
                            <input type="radio" name="jenis" value="mingguan" class="peer hidden" required>
                            <div class="border-2 border-gray-200 rounded-xl p-4 cursor-pointer peer-checked:border-[#8b6f2f] peer-checked:bg-[#8b6f2f]/5 transition-all">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-[#8b6f2f]/10 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-[#8b6f2f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">Mingguan</p>
                                        <p class="text-xs text-gray-500">Angsuran per minggu</p>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Durasi -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Durasi Pinjaman</label>
                    <select name="durasi" id="durasi" 
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 transition-all" required>
                        <option value="">Pilih durasi</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}">{{ $i }} {{ $i == 1 ? 'bulan' : 'bulan' }}</option>
                        @endfor
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Pilih jangka waktu pinjaman</p>
                </div>

                <!-- Keterangan -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan (Opsional)</label>
                    <textarea name="keterangan" id="keterangan" rows="3"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20 transition-all"
                        placeholder="Tambahkan keterangan jika diperlukan"></textarea>
                </div>

                <!-- Kalkulator Estimasi -->
                <div class="mb-6 p-6 bg-gradient-to-br from-[#8b6f2f]/10 to-[#d4af37]/10 rounded-xl border border-[#8b6f2f]/20">
                    <h3 class="text-sm font-semibold text-[#8b6f2f] mb-4">Estimasi Pinjaman</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Nominal Pinjaman:</span>
                            <span class="font-semibold text-gray-900" id="estimasiNominal">Rp 0</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Estimasi Bunga (5%):</span>
                            <span class="font-semibold text-gray-900" id="estimasiBunga">Rp 0</span>
                        </div>
                        <div class="border-t border-gray-300 pt-3 flex justify-between items-center">
                            <span class="font-semibold text-[#8b6f2f]">Total Pinjaman:</span>
                            <span class="text-xl font-bold text-[#8b6f2f]" id="estimasiTotal">Rp 0</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Angsuran per periode:</span>
                            <span class="font-semibold text-gray-900" id="estimasiAngsuran">Rp 0</span>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                    class="w-full bg-gradient-to-r from-[#8b6f2f] to-[#a0824d] text-white font-semibold py-3 rounded-xl hover:shadow-lg transition-all">
                    Ajukan Pinjaman
                </button>
            </form>
        </div>
    </div>

    <!-- Riwayat Pengajuan -->
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-lg font-bold text-[#8b6f2f] mb-6 font-display">Riwayat Pengajuan</h2>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-[#8b6f2f]/20">
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase">Nominal</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase">Jenis</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase">Durasi</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayatPengajuan ?? [] as $pengajuan)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors cursor-pointer" onclick="window.location.href='{{ route('nasabah.pinjaman.detail-pengajuan', $pengajuan->id) }}'">
                            <td class="px-4 py-3 text-sm">
                                <p class="font-medium text-gray-900">{{ $pengajuan->tgl_pengajuan->format('d M Y') }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <p class="font-semibold text-gray-900">Rp {{ number_format($pengajuan->nominal, 0, ',', '.') }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                                    {{ ucfirst($pengajuan->jenis) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $pengajuan->durasi }} bulan</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-between">
                                    <span class="px-3 py-1 {{ $pengajuan->pinjaman ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }} rounded-full text-xs font-semibold">
                                        {{ $pengajuan->pinjaman ? 'Disetujui' : 'Pending' }}
                                    </span>
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-gray-500">Belum ada riwayat pengajuan</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nominalInput = document.getElementById('nominal');
    const durasiSelect = document.getElementById('durasi');
    const jenisInputs = document.querySelectorAll('input[name="jenis"]');
    
    function updateEstimasi() {
        const nominal = parseFloat(nominalInput.value) || 0;
        const durasi = parseInt(durasiSelect.value) || 1;
        const jenis = document.querySelector('input[name="jenis"]:checked')?.value || 'bulanan';
        
        // Estimasi bunga 5% dari nominal
        const estimasiBunga = nominal * 0.05;
        const totalPinjaman = nominal + estimasiBunga;
        const angsuranPerPeriode = durasi > 0 ? totalPinjaman / durasi : 0;
        
        // Update display
        document.getElementById('estimasiNominal').textContent = 'Rp ' + nominal.toLocaleString('id-ID');
        document.getElementById('estimasiBunga').textContent = 'Rp ' + estimasiBunga.toLocaleString('id-ID');
        document.getElementById('estimasiTotal').textContent = 'Rp ' + totalPinjaman.toLocaleString('id-ID');
        document.getElementById('estimasiAngsuran').textContent = 'Rp ' + Math.ceil(angsuranPerPeriode).toLocaleString('id-ID') + ' / ' + jenis;
    }
    
    nominalInput.addEventListener('input', updateEstimasi);
    durasiSelect.addEventListener('change', updateEstimasi);
    jenisInputs.forEach(input => {
        input.addEventListener('change', updateEstimasi);
    });
    
    // Initial calculation
    updateEstimasi();
});
</script>
@endsection
