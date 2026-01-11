@extends('layouts.app')

@section('title', 'Data Siswa - Sekolah Sepak Bola')

@section('page-title', 'Data Siswa')

@section('content')
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0"><i class="bi bi-people-fill"></i> Daftar Siswa</h5>
        <a href="{{ route('siswa.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Siswa
        </a>
    </div>
    
    <!-- Filter Kelompok Umur -->
    <div class="card-body border-bottom">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link {{ $kelompok === 'semua' ? 'active' : '' }}" 
                   href="{{ route('siswa.index', ['kelompok' => 'semua']) }}">
                    <i class="bi bi-list"></i> Semua Siswa
                    <span class="badge bg-secondary ms-1">{{ \App\Models\Siswa::count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $kelompok === 'u7' ? 'active' : '' }}" 
                   href="{{ route('siswa.index', ['kelompok' => 'u7']) }}">
                    <i class="bi bi-people"></i> Kelompok U-7 (3-7 tahun)
                    <span class="badge bg-info ms-1">
                        {{ \App\Models\Siswa::whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 3 AND 7')->count() }}
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $kelompok === 'u12' ? 'active' : '' }}" 
                   href="{{ route('siswa.index', ['kelompok' => 'u12']) }}">
                    <i class="bi bi-people"></i> Kelompok U-12 (8-12 tahun)
                    <span class="badge bg-success ms-1">
                        {{ \App\Models\Siswa::whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 8 AND 12')->count() }}
                    </span>
                </a>
            </li>
        </ul>
    </div>
    
    <div class="card-body">
        @if($siswas->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="8%">Foto</th>
                            <th width="20%">Nama</th>
                            <th width="12%">Tanggal Lahir</th>
                            <th width="10%">Umur</th>
                            <th width="10%">Kelompok</th>
                            <th width="15%">Minat Posisi</th>
                            <th width="8%">Status</th>
                            <th width="12%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($siswas as $index => $siswa)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                @if($siswa->foto)
                                    <img src="{{ asset($siswa->foto) }}" 
                                         alt="Foto {{ $siswa->nama }}" 
                                         class="rounded-circle" 
                                         style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" 
                                         style="width: 40px; height: 40px;">
                                        <i class="bi bi-person text-white"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $siswa->nama }}</strong>
                                @if($siswa->telepon)
                                    <br><small class="text-muted"><i class="bi bi-phone"></i> {{ $siswa->telepon }}</small>
                                @endif
                            </td>
                            <td>{{ $siswa->tanggal_lahir->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-primary">{{ $siswa->umur_detail }}</span>
                            </td>
                            <td>
                                @if($siswa->kelompok_umur === 'U-7')
                                    <span class="badge bg-info">{{ $siswa->kelompok_umur }}</span>
                                @elseif($siswa->kelompok_umur === 'U-12')
                                    <span class="badge bg-success">{{ $siswa->kelompok_umur }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($siswa->minat_posisi && count($siswa->minat_posisi) > 0)
                                    @foreach($siswa->minat_posisi as $posisi)
                                        <span class="badge bg-info me-1 mb-1">{{ $posisi }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($siswa->status == 'Aktif')
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Non-Aktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('siswa.edit', $siswa->id) }}" 
                                       class="btn btn-sm btn-warning" 
                                       title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('siswa.destroy', $siswa->id) }}" 
                                          method="POST" 
                                          style="display: inline;"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus siswa ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-danger" 
                                                title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                <p class="text-muted mt-3">Belum ada data siswa</p>
                <a href="{{ route('siswa.create') }}" class="btn btn-primary mt-2">
                    <i class="bi bi-plus-circle"></i> Tambah Siswa Pertama
                </a>
            </div>
        @endif
    </div>
</div>

{{-- <div class="row mt-4">
    <div class="col-md-3">
        <div class="card text-center border-primary">
            <div class="card-body">
                <i class="bi bi-people-fill text-primary" style="font-size: 2rem;"></i>
                <h2 class="text-primary mt-2">{{ \App\Models\Siswa::count() }}</h2>
                <p class="text-muted mb-0">Total Siswa</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center border-info">
            <div class="card-body">
                <i class="bi bi-people text-info" style="font-size: 2rem;"></i>
                <h2 class="text-info mt-2">{{ \App\Models\Siswa::whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 3 AND 7')->count() }}</h2>
                <p class="text-muted mb-0">Kelompok U-7</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center border-success">
            <div class="card-body">
                <i class="bi bi-people text-success" style="font-size: 2rem;"></i>
                <h2 class="text-success mt-2">{{ \App\Models\Siswa::whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 8 AND 12')->count() }}</h2>
                <p class="text-muted mb-0">Kelompok U-12</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center border-warning">
            <div class="card-body">
                <i class="bi bi-check-circle text-warning" style="font-size: 2rem;"></i>
                <h2 class="text-warning mt-2">{{ \App\Models\Siswa::where('status', 'Aktif')->count() }}</h2>
                <p class="text-muted mb-0">Siswa Aktif</p>
            </div>
        </div>
    </div>
</div> --}}
@endsection
