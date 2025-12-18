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
    <div class="card-body">
        @if($siswas->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="8%">Foto</th>
                            <th width="22%">Nama</th>
                            <th width="12%">Tanggal Lahir</th>
                            <th width="10%">Umur</th>
                            <th width="18%">Minat Posisi</th>
                            <th width="10%">Status</th>
                            <th width="15%" class="text-center">Aksi</th>
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

<div class="row mt-4">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h2 class="text-primary">{{ $siswas->count() }}</h2>
                <p class="text-muted mb-0">Total Siswa</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h2 class="text-success">{{ $siswas->where('posisi', 'Striker')->count() }}</h2>
                <p class="text-muted mb-0">Striker</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h2 class="text-info">{{ $siswas->where('posisi', 'Kiper')->count() }}</h2>
                <p class="text-muted mb-0">Kiper</p>
            </div>
        </div>
    </div>
</div>
@endsection
