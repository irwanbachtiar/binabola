@extends('layouts.app')

@section('title', 'Hari Libur')

@section('page-title', 'Master Hari Libur')

@section('content')
<div class="row mb-3">
    <div class="col-md-12">
        <h2><i class="bi bi-calendar-x"></i> Master Hari Libur</h2>
        <a href="{{ route('hari-libur.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Hari Libur
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">Daftar Hari Libur</h5>
    </div>
    <div class="card-body">
        @if($hariLibur->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                            <th>Jenis</th>
                            <th width="100" class="text-center">Status</th>
                            <th width="150" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($hariLibur as $index => $libur)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $libur->tanggal->format('d/m/Y') }}</td>
                            <td>{{ $libur->keterangan }}</td>
                            <td>
                                <span class="badge 
                                    @if($libur->jenis === 'Nasional') bg-danger
                                    @elseif($libur->jenis === 'Keagamaan') bg-success
                                    @elseif($libur->jenis === 'Sekolah') bg-primary
                                    @else bg-secondary
                                    @endif
                                ">
                                    {{ $libur->jenis }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($libur->aktif)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('hari-libur.edit', $libur->id) }}" 
                                   class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('hari-libur.destroy', $libur->id) }}" 
                                      method="POST" 
                                      style="display: inline;"
                                      onsubmit="return confirm('Yakin ingin menghapus hari libur ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center text-muted py-4">Belum ada data hari libur</p>
        @endif
    </div>
</div>
@endsection
