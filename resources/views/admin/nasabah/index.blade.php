@extends('layouts.admin')

@section('title', 'Manajemen Nasabah')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Nasabah</h1>
        <a href="{{ route('admin.nasabah.pending-changes') }}" class="btn btn-warning">
            <i class="fas fa-clock"></i> Pengajuan Pending 
            @if($pendingChangesCount > 0)
                <span class="badge badge-light">{{ $pendingChangesCount }}</span>
            @endif
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Search & Filter -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-search"></i> Pencarian Nasabah
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.nasabah.index') }}">
                <div class="row">
                    <div class="col-md-10">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Cari berdasarkan nama, email, atau nomor HP..."
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </div>
                </div>
                @if(request('search'))
                    <div class="mt-2">
                        <a href="{{ route('admin.nasabah.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-times"></i> Reset Pencarian
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Nasabah List -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-users"></i> Daftar Nasabah ({{ $nasabahList->total() }})
            </h6>
        </div>
        <div class="card-body">
            @if($nasabahList->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>No. HP</th>
                                <th>NIK</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($nasabahList as $index => $nasabah)
                            <tr>
                                <td>{{ $nasabahList->firstItem() + $index }}</td>
                                <td class="text-center">
                                    @if($nasabah->user->foto && $nasabah->user->foto !== 'default-avatar.jpg')
                                        <img src="{{ asset('storage/' . $nasabah->user->foto) }}" 
                                             alt="Foto" class="rounded-circle" width="40" height="40">
                                    @else
                                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-center" 
                                             style="width: 40px; height: 40px; font-weight: bold;">
                                            {{ strtoupper(substr($nasabah->user->nama ?? 'N', 0, 1)) }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $nasabah->user->nama }}</strong><br>
                                    <small class="text-muted">ID: {{ $nasabah->id }}</small>
                                </td>
                                <td>{{ $nasabah->user->email }}</td>
                                <td>{{ $nasabah->user->nomor_hp }}</td>
                                <td>
                                    @if($nasabah->dataKtp)
                                        <code>{{ $nasabah->dataKtp->nik }}</code>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-success">
                                        <i class="fas fa-check-circle"></i> Aktif
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.nasabah.show', $nasabah->id) }}" 
                                       class="btn btn-sm btn-info" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $nasabahList->links() }}
                </div>
            @else
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle fa-3x mb-3"></i>
                    <h5>Tidak Ada Data Nasabah</h5>
                    @if(request('search'))
                        <p class="mb-0">Tidak ditemukan nasabah dengan kata kunci "{{ request('search') }}".</p>
                        <a href="{{ route('admin.nasabah.index') }}" class="btn btn-sm btn-primary mt-2">
                            Lihat Semua Nasabah
                        </a>
                    @else
                        <p class="mb-0">Belum ada nasabah yang terdaftar.</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
