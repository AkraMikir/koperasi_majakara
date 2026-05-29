@extends('layouts.admin')

@section('title', 'Pencairan Pinjaman')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-money-bill-wave"></i> Pencairan Pinjaman #{{ $pinjaman->id }}</h5>
        <a href="{{ route('admin.pinjaman.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    
    <div class="card-body">
        {{-- Info Pinjaman --}}
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="card-title font-weight-bold">Informasi Pinjaman</h6>
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td><strong>Nasabah:</strong></td>
                                <td>{{ $pinjaman->nasabah->nama }}</td>
                            </tr>
                            <tr>
                                <td><strong>Bank Nasabah:</strong></td>
                                <td><span class="badge badge-primary">{{ $bankNasabah }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Nominal:</strong></td>
                                <td class="font-weight-bold text-success">Rp {{ number_format($pinjaman->jumlah_pinjam, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tenor:</strong></td>
                                <td>{{ $pinjaman->lama_pinjam }} bulan</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="card-title font-weight-bold">Saldo & Validasi</h6>
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td><strong>Saldo Tabungan Nasabah:</strong></td>
                                <td class="font-weight-bold">Rp {{ number_format($saldoNasabah, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Saldo Petty Cash Admin:</strong></td>
                                <td class="font-weight-bold {{ $adminSaldo >= $pinjaman->jumlah_pinjam ? 'text-success' : 'text-danger' }}">
                                    Rp {{ number_format($adminSaldo, 0, ',', '.') }}
                                </td>
                            </tr>
                            @if($adminSaldo < $pinjaman->jumlah_pinjam)
                            <tr>
                                <td colspan="2">
                                    <span class="badge badge-danger">⚠️ Petty cash tidak cukup!</span>
                                </td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Pencairan --}}
        <form action="{{ route('admin.pinjaman.cairkan', $pinjaman->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            {{-- Dropdown Bank Pengirim --}}
            <div class="form-group mb-3">
                <label for="bank_pengirim" class="font-weight-bold">
                    <i class="fas fa-university"></i> Bank Pengirim (Koperasi) <span class="text-danger">*</span>
                </label>
                <select name="bank_pengirim" id="bank_pengirim" class="form-control" required onchange="calculateBiaya()">
                    <option value="">-- Pilih Bank --</option>
                    <option value="BCA" selected>BCA</option>
                    <option value="BNI">BNI</option>
                    <option value="Mandiri">Mandiri</option>
                    <option value="BRI">BRI</option>
                </select>
                <small class="form-text text-muted">Bank utama koperasi (default: BCA)</small>
            </div>

            {{-- Upload Bukti TF --}}
            <div class="form-group mb-3">
                <label for="foto_bukti_tf_admin" class="font-weight-bold">
                    <i class="fas fa-image"></i> Bukti Transfer Admin <span class="text-danger">*</span>
                </label>
                <input type="file" 
                       name="foto_bukti_tf_admin" 
                       id="foto_bukti_tf_admin" 
                       class="form-control" 
                       required 
                       accept="image/*">
                <small class="form-text text-muted">Format: JPG, PNG. Max 5MB</small>
            </div>

            {{-- Container Biaya Admin --}}
            <div id="biaya-section" class="hidden p-4 bg-amber-50 border border-amber-200 rounded-lg">
                <h6 class="font-weight-bold mb-3"><i class="fas fa-calculator"></i> Rincian Biaya</h6>
                
                <div class="row mb-2">
                    <div class="col-6">
                        <p class="mb-0 text-muted">Biaya Transfer:</p>
                    </div>
                    <div class="col-6 text-right">
                        <p class="mb-0 font-weight-bold text-amber-700" id="biaya-display">Rp 0</p>
                    </div>
                </div>
                
                <div class="row mb-2">
                    <div class="col-6">
                        <p class="mb-0 text-muted">Total dipotong dari saldo:</p>
                    </div>
                    <div class="col-6 text-right">
                        <p class="mb-0 font-weight-bold" id="total-dipotong-display">Rp {{ number_format($pinjaman->jumlah_pinjam, 0, ',', '.') }}</p>
                    </div>
                </div>
                
                <div class="row mb-2">
                    <div class="col-6">
                        <p class="mb-0 text-muted">Nominal Diterima Nasabah:</p>
                    </div>
                    <div class="col-6 text-right">
                        <p class="mb-0 font-weight-bold text-success" id="total-display">Rp {{ number_format($pinjaman->jumlah_pinjam, 0, ',', '.') }}</p>
                    </div>
                </div>
                
                <hr class="my-2">
                <p class="text-xs text-amber-700 mb-0">
                    <i class="fas fa-info-circle"></i> Biaya admin ditanggung nasabah (dikurangi dari saldo tabungan)
                </p>
            </div>

            {{-- Alert jika biaya = 0 (bank sama) --}}
            <div id="biaya-nol-alert" class="alert alert-success hidden">
                <i class="fas fa-check-circle"></i> Bank sama, tidak ada biaya transfer!
            </div>

            {{-- Data Attribute untuk JS --}}
            <div id="pinjaman-data" class="hidden"
                data-nama-bank="{{ e($bankNasabah) }}"
                data-nominal="{{ (float) $pinjaman->jumlah_pinjam }}"
                data-biaya-list="{{ json_encode($biayaTransferList->map(fn($b) => [
                    'bank_pengirim' => $b->bank_pengirim,
                    'bank_penerima' => $b->bank_penerima,
                    'biaya_admin'   => (float)$b->biaya_admin
                ])->values()) }}"
            ></div>

            <hr class="my-4">
            
            <button type="submit" class="btn btn-success btn-lg">
                <i class="fas fa-check"></i> Cairkan Pinjaman
            </button>
            <a href="{{ route('admin.pinjaman.index') }}" class="btn btn-secondary btn-lg">
                <i class="fas fa-times"></i> Batal
            </a>
        </form>
    </div>
</div>

{{-- JavaScript --}}
<script>
function calculateBiaya() {
    const bankPengirim = document.getElementById('bank_pengirim').value;
    const dataEl       = document.getElementById('pinjaman-data');
    const bankPenerima = dataEl.getAttribute('data-nama-bank') || '';
    const nominal      = parseFloat(dataEl.getAttribute('data-nominal')) || 0;
    const biayaList    = JSON.parse(dataEl.getAttribute('data-biaya-list') || '[]');

    const biayaSection = document.getElementById('biaya-section');
    const biayaNolAlert = document.getElementById('biaya-nol-alert');

    if (!bankPengirim) {
        biayaSection.classList.add('hidden');
        return;
    }

    let biaya = 0;
    const match = biayaList.find(b =>
        b.bank_pengirim === bankPengirim && b.bank_penerima === bankPenerima
    );
    
    if (match) {
        biaya = match.biaya_admin || 0;
    } else if (biayaList.length) {
        biaya = biayaList[0].biaya_admin || 0;
    }

    // Tampilkan/hide container biaya
    if (biaya > 0) {
        biayaSection.classList.remove('hidden');
        biayaNolAlert.classList.add('hidden');
    } else {
        biayaSection.classList.add('hidden');
        biayaNolAlert.classList.remove('hidden');
    }

    // Update tampilan
    document.getElementById('biaya-display').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(biaya);
    document.getElementById('total-display').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(nominal);
    document.getElementById('total-dipotong-display').textContent =
        'Rp ' + new Intl.NumberFormat('id-ID').format(nominal + biaya);
}

// Auto calculate on load
document.addEventListener('DOMContentLoaded', function() {
    calculateBiaya();
});
</script>
@endsection
