@extends('layouts.admin')

@section('title', 'Detail Nasabah - ' . $nasabah->user->nama)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.nasabah.index') }}" class="btn btn-secondary btn-sm mb-2">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <h1 class="h3 mb-0 text-gray-800">Detail Nasabah</h1>
        </div>
        @if($pendingChanges->count() > 0)
            <a href="{{ route('admin.nasabah.pending-changes') }}" class="btn btn-warning">
                <i class="fas fa-exclamation-triangle"></i> 
                {{ $pendingChanges->count() }} Pengajuan Pending
            </a>
        @endif
    </div>

    <!-- Profile Header -->
    <div class="card shadow mb-4">
        <div class="card-body bg-gradient-primary text-white">
            <div class="row align-items-center">
                <div class="col-auto">
                    @if($nasabah->user->foto && $nasabah->user->foto !== 'default-avatar.jpg')
                        <img src="{{ asset('storage/' . $nasabah->user->foto) }}" 
                             alt="Foto Profil" class="rounded-circle border border-white" width="100" height="100">
                    @else
                        <div class="bg-white text-primary rounded-circle d-inline-flex align-items-center justify-content-center border border-white" 
                             style="width: 100px; height: 100px; font-size: 40px; font-weight: bold;">
                            {{ strtoupper(substr($nasabah->user->nama ?? 'N', 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="col">
                    <h3 class="mb-2">{{ $nasabah->user->nama }}</h3>
                    <p class="mb-1"><i class="fas fa-envelope"></i> {{ $nasabah->user->email }}</p>
                    <p class="mb-1"><i class="fas fa-phone"></i> {{ $nasabah->user->nomor_hp }}</p>
                    @if($nasabah->dataKtp)
                        <p class="mb-0"><i class="fas fa-id-card"></i> NIK: {{ $nasabah->dataKtp->nik }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Data Pribadi -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 bg-primary text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-user"></i> Data Pribadi</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">Nama</th>
                            <td>{{ $nasabah->user->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $nasabah->user->email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>No. HP</th>
                            <td>{{ $nasabah->user->nomor_hp ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>No. KK</th>
                            <td>{{ $nasabah->no_kk ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tempat Lahir</th>
                            <td>{{ $nasabah->tempat_lahir ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Lahir</th>
                            <td>{{ $nasabah->tanggal_lahir ? $nasabah->tanggal_lahir->format('d M Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Jenis Kelamin</th>
                            <td>
                                @if($nasabah->jenis_kelamin == 'L')
                                    Laki-laki
                                @elseif($nasabah->jenis_kelamin == 'P')
                                    Perempuan
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>{{ $nasabah->alamat ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Data KTP -->
        @if($nasabah->dataKtp)
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 bg-info text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-id-card"></i> Data KTP</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">NIK</th>
                            <td><code>{{ $nasabah->dataKtp->nik ?? '-' }}</code></td>
                        </tr>
                        <tr>
                            <th>Nama Lengkap</th>
                            <td>{{ $nasabah->dataKtp->nama_lengkap ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tempat Lahir</th>
                            <td>{{ $nasabah->dataKtp->tempat_lahir ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Lahir</th>
                            <td>{{ $nasabah->dataKtp->tanggal_lahir ? $nasabah->dataKtp->tanggal_lahir->format('d M Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Jenis Kelamin</th>
                            <td>{{ $nasabah->dataKtp->jenis_kelamin ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>{{ $nasabah->dataKtp->alamat ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Data Pekerjaan -->
        @if($nasabah->pekerjaan)
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 bg-success text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-briefcase"></i> Data Pekerjaan</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">Pekerjaan</th>
                            <td>{{ $nasabah->pekerjaan->pekerjaan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Nama Perusahaan</th>
                            <td>{{ $nasabah->pekerjaan->nama_perusahaan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Penghasilan</th>
                            <td><strong>{{ $nasabah->pekerjaan->penghasilan ?? '-' }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Data Rekening -->
        @if($nasabah->dataRek)
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 bg-warning text-dark">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-university"></i> Data Rekening Bank</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">Nama Bank</th>
                            <td>{{ $nasabah->dataRek->nama_bank ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>No. Rekening</th>
                            <td><code>{{ $nasabah->dataRek->no_rekening ?? '-' }}</code></td>
                        </tr>
                        <tr>
                            <th>Nama Pemilik</th>
                            <td>{{ $nasabah->dataRek->nama_pemilik_rekening ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Kontak Darurat -->
        @if($nasabah->darurat)
        <div class="col-lg-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 bg-danger text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-phone-square"></i> Kontak Darurat</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">Nama Lengkap</th>
                                    <td>{{ $nasabah->darurat->nama_lengkap ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Hubungan</th>
                                    <td>{{ $nasabah->darurat->hubungan_peminjam ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>No. Telepon</th>
                                    <td>{{ $nasabah->darurat->no_telepon ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $nasabah->darurat->email ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">Pekerjaan</th>
                                    <td>{{ $nasabah->darurat->pekerjaan ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>NIK</th>
                                    <td><code>{{ $nasabah->darurat->no_ktp ?? '-' }}</code></td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td>{{ $nasabah->darurat->alamat ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Pending Changes (jika ada) -->
    @if($pendingChanges->count() > 0)
    <div class="card shadow mb-4 border-warning">
        <div class="card-header py-3 bg-warning">
            <h6 class="m-0 font-weight-bold text-dark">
                <i class="fas fa-exclamation-triangle"></i> 
                Pengajuan Perubahan Data Pending ({{ $pendingChanges->count() }})
            </h6>
        </div>
        <div class="card-body">
            <div class="alert alert-warning">
                <strong>Perhatian!</strong> Nasabah ini memiliki {{ $pendingChanges->count() }} pengajuan perubahan data yang menunggu persetujuan Anda.
            </div>
            <a href="{{ route('admin.nasabah.pending-changes') }}" class="btn btn-warning">
                <i class="fas fa-clipboard-list"></i> Lihat Semua Pengajuan Pending
            </a>
        </div>
    </div>
    @endif
</div>
@endsection
