@extends('layouts.nasabah')

@section('title', 'Panduan Pengajuan Pinjaman')

@section('content')
<div class="w-full pb-8">
    <div class="mx-4 mt-4 mb-4">
        <nav class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('nasabah.guide') }}" class="hover:text-[#8b6f2f]">Guide</a>
            <span>/</span>
            <a href="{{ route('nasabah.pinjaman.index') }}" class="hover:text-[#8b6f2f]">Pinjaman</a>
            <span>/</span>
            <span class="text-[#674c1d] font-medium">Pengajuan</span>
        </nav>
    </div>

    <div class="mx-4 space-y-6">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-amber-100 rounded-2xl flex items-center justify-center">
                <svg class="w-8 h-8 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 font-display">Panduan Pengajuan Pinjaman</h1>
                <p class="text-gray-600 text-sm">Cara akses, simulasi angsuran, dan informasi bunga & denda</p>
            </div>
        </div>

        {{-- Cara akses --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-amber-800 font-display mb-4 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Cara akses dari aplikasi
            </h2>
            <ol class="space-y-4">
                <li class="flex gap-4">
                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-amber-600 text-white flex items-center justify-center font-bold text-sm">1</span>
                    <div>
                        <p class="font-semibold text-gray-900">Tekan icon <strong>Pinjaman</strong> di bottom navbar</p>
                        <p class="text-sm text-gray-600 mt-0.5">Icon berbentuk dokumen/uang. Di mobile: buka Menu lalu pilih Pinjaman.</p>
                    </div>
                </li>
                <li class="flex gap-4">
                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-amber-600 text-white flex items-center justify-center font-bold text-sm">2</span>
                    <div>
                        <p class="font-semibold text-gray-900">Anda masuk ke <strong>Dashboard Pinjaman</strong></p>
                        <p class="text-sm text-gray-600 mt-0.5">Menampilkan pinjaman aktif, angsuran terdekat, dan tombol Pengajuan Pinjaman.</p>
                    </div>
                </li>
                <li class="flex gap-4">
                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-amber-600 text-white flex items-center justify-center font-bold text-sm">3</span>
                    <div>
                        <p class="font-semibold text-gray-900">Klik <strong>Pengajuan Pinjaman</strong></p>
                        <p class="text-sm text-gray-600 mt-0.5">Halaman pengajuan: isi nominal, pilih durasi (tenor), pilih metode pencairan (Transfer atau Janji Temu). Anda bisa gunakan <strong>Simulasi Angsuran</strong> di halaman itu untuk melihat estimasi sebelum submit.</p>
                    </div>
                </li>
            </ol>
        </div>

        {{-- Sandbox: Lihat bunga & Denda --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-amber-800 font-display mb-4 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                Suku bunga & denda (informasi terkini)
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-semibold text-gray-900 mb-2">Bunga pinjaman per tenor</h3>
                    @if($bungaPinjaman->isNotEmpty())
                    <div class="overflow-x-auto rounded-xl border border-gray-200">
                        <table class="w-full text-sm">
                            <thead class="bg-amber-50">
                                <tr>
                                    <th class="px-4 py-2 text-left font-semibold text-gray-700">Durasi (bulan)</th>
                                    <th class="px-4 py-2 text-right font-semibold text-gray-700">Bunga (%)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($bungaPinjaman as $b)
                                <tr>
                                    <td class="px-4 py-2 text-gray-900">{{ $b->durasi_min }}–{{ $b->durasi_max }}</td>
                                    <td class="px-4 py-2 text-right font-medium text-amber-700">{{ number_format((float)$b->bunga_persen, 1, ',', '') }}%</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Suku bunga dapat berubah per periode (mis. per 3 bulan). Yang berlaku adalah saat Anda mengajukan.</p>
                    @else
                    <p class="text-gray-500 text-sm">Data bunga belum diatur. Cek di halaman pengajuan untuk simulasi.</p>
                    @endif
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 mb-2">Denda keterlambatan</h3>
                    @if($dendaPinjaman)
                    <div class="rounded-xl border border-gray-200 p-4 bg-red-50/50">
                        <p class="text-2xl font-bold text-red-700">{{ number_format((float)$dendaPinjaman->denda_persen, 1, ',', '') }}%</p>
                        <p class="text-sm text-gray-600 mt-1">Dikenakan jika angsuran dibayar terlambat dari jadwal jatuh tempo.</p>
                        @if($dendaPinjaman->keterangan)
                        <p class="text-xs text-gray-500 mt-2">{{ $dendaPinjaman->keterangan }}</p>
                        @endif
                    </div>
                    @else
                    <p class="text-gray-500 text-sm">Data denda belum diatur.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sandbox: Test simulasi angsuran --}}
        <div class="bg-white rounded-2xl shadow-lg border border-amber-200 p-6">
            <h2 class="text-lg font-bold text-amber-800 font-display mb-2 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                Sandbox: Coba simulasi angsuran
            </h2>
            <p class="text-sm text-gray-600 mb-4">Masukkan nominal dan durasi lalu klik Hitung. Hasil hanya perkiraan; tidak mengikat. Untuk pengajuan resmi gunakan halaman Pengajuan Pinjaman.</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nominal pinjaman (Rp)</label>
                    <input type="text" id="guideSimulasiNominal" placeholder="Contoh: 5.000.000" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500" value="5.000.000" oninput="formatCurrency(this)">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Durasi (bulan)</label>
                    <select id="guideSimulasiDurasi" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                        @foreach($durasiList as $d)
                        <option value="{{ $d->bulan ?? $d->ket }}">{{ $d->bulan ?? $d->ket }} bulan</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="button" id="guideSimulasiBtn" class="px-5 py-2.5 bg-amber-600 text-white rounded-xl font-semibold hover:bg-amber-700 transition-colors">
                Hitung simulasi
            </button>
            <div id="guideSimulasiResult" class="mt-5 hidden rounded-xl border border-amber-200 bg-amber-50/50 p-5">
                <h4 class="font-semibold text-gray-900 mb-3">Hasil simulasi</h4>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <p class="text-gray-600">Nominal</p>
                    <p id="guideResNominal" class="font-semibold text-right text-gray-900">-</p>
                    <p class="text-gray-600">Bunga</p>
                    <p id="guideResBunga" class="font-semibold text-right text-amber-700">-</p>
                    <p class="text-gray-600">Total kewajiban</p>
                    <p id="guideResTotal" class="font-semibold text-right text-gray-900">-</p>
                    <p class="text-gray-600">Angsuran per bulan (contoh)</p>
                    <p id="guideResAngsuran" class="font-semibold text-right text-amber-700">-</p>
                </div>
                <div class="mt-4 overflow-x-auto">
                    <table class="w-full text-sm" id="guideSimulasiTable">
                        <thead><tr><th class="text-left py-1">Bulan</th><th class="text-left py-1">Jatuh tempo</th><th class="text-right py-1">Total</th></tr></thead>
                        <tbody id="guideSimulasiTableBody"></tbody>
                    </table>
                </div>
            </div>
            <p id="guideSimulasiError" class="mt-3 text-sm text-red-600 hidden"></p>
        </div>

        {{-- Ketentuan Biaya Transfer Pencairan --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-amber-800 font-display mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Biaya Transfer Pencairan Pinjaman
            </h2>
            <div class="space-y-4 text-sm text-gray-600">
                <p>
                    Apabila pengajuan pinjaman Anda disetujui dan dicairkan oleh Admin Koperasi melalui metode <strong>Transfer Bank</strong>, sistem akan menerapkan kebijakan berikut:
                </p>
                <div class="bg-amber-50/60 rounded-xl p-4 border border-amber-200">
                    <ul class="space-y-2 text-xs text-amber-900/90 list-disc pl-4">
                        <li>
                            <strong>Biaya Transfer Pengiriman</strong>: Admin akan memilih bank pengirim dari pihak koperasi. Jika bank pengirim berbeda dengan bank rekening Anda, biaya transfer bank akan dihitung otomatis oleh sistem.
                        </li>
                        <li>
                            <strong>Potongan Saldo Tabungan</strong>: Biaya transfer pencairan tersebut <strong>tidak memotong nominal pinjaman</strong> yang Anda terima, melainkan akan <strong>didebet langsung dari saldo Tabungan Utama</strong> Anda di koperasi ketika admin melakukan pencairan.
                        </li>
                        <li>
                            <strong>Penting</strong>: Pastikan saldo rekening Tabungan Anda di koperasi mencukupi untuk menutupi biaya transfer ini saat pencairan disetujui oleh admin.
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-100 mt-6 pt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <span class="text-sm text-gray-500">Pantau proses pengajuan pinjaman Anda secara berkala.</span>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('nasabah.pinjaman.status-pengajuan') }}" 
                       class="px-4 py-2 text-sm font-semibold text-amber-700 bg-amber-100 rounded-xl hover:bg-amber-200 transition-colors">
                        Pantau Status Pengajuan
                    </a>
                    <a href="{{ route('nasabah.pinjaman.index') }}" 
                       class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                        Dashboard Pinjaman
                    </a>
                </div>
            </div>
        </div>

        {{-- Contoh tampilan & CTA --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-amber-800 font-display mb-2">Contoh tampilan halaman pengajuan</h2>
            <p class="text-sm text-gray-600 mb-4">Preview halaman Pengajuan Pinjaman. Di halaman asli Anda bisa mengisi form dan menjalankan simulasi langsung.</p>
            <div class="rounded-xl border-2 border-gray-200 overflow-hidden bg-gray-50">
                <iframe
                    src="{{ route('nasabah.pinjaman.pengajuan') }}"
                    title="Contoh: Halaman Pengajuan Pinjaman"
                    class="w-full border-0"
                    style="height: 70vh; min-height: 420px;"
                    loading="lazy"
                ></iframe>
            </div>
            <a href="{{ route('nasabah.pinjaman.pengajuan') }}" target="_blank" rel="noopener"
               class="mt-4 inline-flex items-center gap-2 px-5 py-3 bg-amber-600 text-white rounded-xl font-semibold hover:bg-amber-700 transition-colors">
                Buka halaman Pengajuan Pinjaman
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
function formatCurrency(input) {
    let value = input.value.replace(/[^0-9]/g, '');
    if (value) value = parseInt(value).toLocaleString('id-ID');
    input.value = value;
}

(function() {
    var btn = document.getElementById('guideSimulasiBtn');
    var nominalInput = document.getElementById('guideSimulasiNominal');
    var durasiSelect = document.getElementById('guideSimulasiDurasi');
    var resultDiv = document.getElementById('guideSimulasiResult');
    var errorP = document.getElementById('guideSimulasiError');
    var tableBody = document.getElementById('guideSimulasiTableBody');

    function formatRp(n) {
        return 'Rp ' + (typeof n === 'number' ? n : parseInt(n, 10)).toLocaleString('id-ID');
    }

    if (btn) {
        btn.addEventListener('click', function() {
            var nominalRaw = (nominalInput.value || '').replace(/\D/g, '');
            var nominal = parseInt(nominalRaw, 10);
            var durasi = parseInt(durasiSelect.value, 10) || 12;
            errorP.classList.add('hidden');
            resultDiv.classList.add('hidden');
            if (!nominal || nominal < 100000) {
                errorP.textContent = 'Nominal minimal Rp 100.000';
                errorP.classList.remove('hidden');
                return;
            }
            btn.disabled = true;
            btn.textContent = 'Menghitung...';
            var formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('nominal', nominal);
            formData.append('durasi', durasi);
            fetch('{{ route("nasabah.pinjaman.simulasi-angsuran") }}', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                btn.disabled = false;
                btn.textContent = 'Hitung simulasi';
                if (data.success && data.data) {
                    var d = data.data;
                    document.getElementById('guideResNominal').textContent = formatRp(d.nominal);
                    document.getElementById('guideResBunga').textContent = d.bunga_persen + '% = ' + formatRp(d.bunga_total);
                    document.getElementById('guideResTotal').textContent = formatRp(d.total_yang_harus_dibayar);
                    document.getElementById('guideResAngsuran').textContent = formatRp(d.angsuran_per_bulan);
                    tableBody.innerHTML = '';
                    (d.simulasi || []).forEach(function(item) {
                        var tr = document.createElement('tr');
                        tr.innerHTML = '<td class="py-2 text-gray-900">' + item.bulan + '</td><td class="py-2 text-gray-600">' + item.tanggal + '</td><td class="py-2 text-right font-semibold text-amber-700">' + formatRp(item.total) + '</td>';
                        tableBody.appendChild(tr);
                    });
                    resultDiv.classList.remove('hidden');
                } else {
                    errorP.textContent = data.message || 'Gagal menghitung simulasi';
                    errorP.classList.remove('hidden');
                }
            })
            .catch(function() {
                btn.disabled = false;
                btn.textContent = 'Hitung simulasi';
                errorP.textContent = 'Network error. Coba lagi.';
                errorP.classList.remove('hidden');
            });
        });
    }
})();
</script>
@endpush
@endsection
