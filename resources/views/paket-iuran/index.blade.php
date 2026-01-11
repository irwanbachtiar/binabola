@extends('layouts.app')

@section('title', 'Master Paket Iuran')

@section('page-title', 'Master Paket Iuran')

@section('content')
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

<div class="card border-0 shadow-sm">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-box-seam"></i> Daftar Paket Iuran</h5>
        <a href="{{ route('paket-iuran.create') }}" class="btn btn-light btn-sm">
            <i class="bi bi-plus-circle"></i> Tambah Paket
        </a>
    </div>
    <div class="card-body">
        @if($pakets->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%">No</th>
                            <th>Nama Paket</th>
                            <th>Kelompok Umur</th>
                            <th>Nominal</th>
                            <th>Durasi</th>
                            <th>Status</th>
                            <th style="width: 15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pakets as $index => $paket)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $paket->nama_paket }}</strong>
                                @if($paket->keterangan)
                                    <br><small class="text-muted">{{ $paket->keterangan }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $paket->kelompok_umur === 'U-7' ? 'bg-primary' : 'bg-success' }}">
                                    {{ $paket->kelompok_umur }}
                                </span>
                            </td>
                            <td><strong>{{ $paket->nominal_format }}</strong></td>
                            <td>{{ $paket->durasi_bulan }} bulan</td>
                            <td>
                                @if($paket->aktif)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('paket-iuran.edit', $paket->id) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('paket-iuran.destroy', $paket->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus paket ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Hapus">
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
                <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                <p class="text-muted">Belum ada paket iuran. Silakan tambah paket baru.</p>
                <a href="{{ route('paket-iuran.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah Paket
                </a>
            </div>
        @endif
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('pembayaran.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali ke Pembayaran
    </a>
</div>
@endsection
