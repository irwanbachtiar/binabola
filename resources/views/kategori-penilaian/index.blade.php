@extends('layouts.app')

@section('title', 'Kategori Penilaian')

@section('page-title', 'Master Kategori Penilaian')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-list-check"></i> Kategori Penilaian</h2>
        <a href="{{ route('kategori-penilaian.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Kategori
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @php
        $kelompokU7 = $kategoris->where('kelompok_umur', 'U-7');
        $kelompokU12 = $kategoris->where('kelompok_umur', 'U-12');
    @endphp

    <!-- U-7 Section -->
    @if($kelompokU7->count() > 0)
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-trophy"></i> Kelompok Umur U-7</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Nama Kategori</th>
                            <th>Tipe</th>
                            <th>Deskripsi</th>
                            <th style="width: 80px;">Urutan</th>
                            <th style="width: 100px;">Minggu Terakhir</th>
                            <th style="width: 80px;">Status</th>
                            <th style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kelompokU7->whereNull('parent_id')->sortBy('urutan') as $index => $parent)
                            <tr class="table-info">
                                <td class="text-center fw-bold">{{ $index + 1 }}</td>
                                <td class="fw-bold">
                                    <i class="bi bi-folder"></i> {{ $parent->nama }}
                                </td>
                                <td><span class="badge bg-primary">Parent</span></td>
                                <td>{{ $parent->deskripsi ?? '-' }}</td>
                                <td class="text-center">{{ $parent->urutan }}</td>
                                <td class="text-center">
                                    @if($parent->minggu_terakhir)
                                        <span class="badge bg-warning text-dark"><i class="bi bi-calendar-check"></i> Ya</span>
                                    @else
                                        <span class="badge bg-light text-dark">Tidak</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($parent->aktif)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Non-aktif</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('kategori-penilaian.edit', $parent->id) }}" 
                                       class="btn btn-sm btn-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('kategori-penilaian.destroy', $parent->id) }}" 
                                          method="POST" class="d-inline" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @foreach($parent->children->sortBy('urutan') as $childIndex => $child)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}.{{ $childIndex + 1 }}</td>
                                    <td class="ps-4">
                                        <i class="bi bi-arrow-return-right"></i> {{ $child->nama }}
                                    </td>
                                    <td><span class="badge bg-secondary">Sub</span></td>
                                    <td>{{ $child->deskripsi ?? '-' }}</td>
                                    <td class="text-center">{{ $child->urutan }}</td>
                                    <td class="text-center">
                                        @if($child->minggu_terakhir)
                                            <span class="badge bg-warning text-dark"><i class="bi bi-calendar-check"></i> Ya</span>
                                        @else
                                            <span class="badge bg-light text-dark">Tidak</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($child->aktif)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Non-aktif</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('kategori-penilaian.edit', $child->id) }}" 
                                           class="btn btn-sm btn-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('kategori-penilaian.destroy', $child->id) }}" 
                                              method="POST" class="d-inline" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- U-12 Section -->
    @if($kelompokU12->count() > 0)
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-trophy"></i> Kelompok Umur U-12</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Nama Kategori</th>
                            <th>Tipe</th>
                            <th>Deskripsi</th>
                            <th style="width: 80px;">Urutan</th>
                            <th style="width: 100px;">Minggu Terakhir</th>
                            <th style="width: 80px;">Status</th>
                            <th style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kelompokU12->whereNull('parent_id')->sortBy('urutan') as $index => $parent)
                            <tr class="table-success">
                                <td class="text-center fw-bold">{{ $index + 1 }}</td>
                                <td class="fw-bold">
                                    <i class="bi bi-folder"></i> {{ $parent->nama }}
                                </td>
                                <td><span class="badge bg-primary">Parent</span></td>
                                <td>{{ $parent->deskripsi ?? '-' }}</td>
                                <td class="text-center">{{ $parent->urutan }}</td>
                                <td class="text-center">
                                    @if($parent->minggu_terakhir)
                                        <span class="badge bg-warning text-dark"><i class="bi bi-calendar-check"></i> Ya</span>
                                    @else
                                        <span class="badge bg-light text-dark">Tidak</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($parent->aktif)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Non-aktif</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('kategori-penilaian.edit', $parent->id) }}" 
                                       class="btn btn-sm btn-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('kategori-penilaian.destroy', $parent->id) }}" 
                                          method="POST" class="d-inline" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @foreach($parent->children->sortBy('urutan') as $childIndex => $child)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}.{{ $childIndex + 1 }}</td>
                                    <td class="ps-4">
                                        <i class="bi bi-arrow-return-right"></i> {{ $child->nama }}
                                    </td>
                                    <td><span class="badge bg-secondary">Sub</span></td>
                                    <td>{{ $child->deskripsi ?? '-' }}</td>
                                    <td class="text-center">{{ $child->urutan }}</td>
                                    <td class="text-center">
                                        @if($child->minggu_terakhir)
                                            <span class="badge bg-warning text-dark"><i class="bi bi-calendar-check"></i> Ya</span>
                                        @else
                                            <span class="badge bg-light text-dark">Tidak</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($child->aktif)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Non-aktif</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('kategori-penilaian.edit', $child->id) }}" 
                                           class="btn btn-sm btn-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('kategori-penilaian.destroy', $child->id) }}" 
                                              method="POST" class="d-inline" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    @if($kategoris->count() == 0)
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Belum ada kategori penilaian. Silakan tambahkan kategori baru.
        </div>
    @endif
</div>
@endsection
