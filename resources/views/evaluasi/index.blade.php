@extends('layouts.app')

@section('title', 'Evaluasi Siswa - Sekolah Sepak Bola')

@section('page-title', 'Penilaian & Evaluasi Siswa')

@section('content')
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="bi bi-clipboard-data"></i> Pilih Siswa untuk Evaluasi</h5>
    </div>
    <div class="card-body">
        @if($siswas->count() > 0)
            <div class="row">
                @foreach($siswas as $siswa)
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            @if($siswa->foto)
                                <img src="{{ asset($siswa->foto) }}" 
                                     alt="Foto {{ $siswa->nama }}" 
                                     class="rounded-circle mb-3" 
                                     style="width: 80px; height: 80px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mx-auto mb-3" 
                                     style="width: 80px; height: 80px;">
                                    <i class="bi bi-person fs-1 text-white"></i>
                                </div>
                            @endif
                            
                            <h6 class="card-title">{{ $siswa->nama }}</h6>
                            <p class="text-muted small mb-2">{{ $siswa->umur_detail }}</p>
                            
                            @if($siswa->minat_posisi && count($siswa->minat_posisi) > 0)
                                <div class="mb-3">
                                    @foreach($siswa->minat_posisi as $posisi)
                                        <span class="badge bg-info me-1">{{ $posisi }}</span>
                                    @endforeach
                                </div>
                            @endif
                            
                            <div class="btn-group" role="group">
                                <a href="{{ route('evaluasi.create', $siswa->id) }}" 
                                   class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil-square"></i> Input Nilai
                                </a>
                                <a href="{{ route('evaluasi.show', $siswa->id) }}" 
                                   class="btn btn-sm btn-success">
                                    <i class="bi bi-graph-up"></i> Lihat Progress
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                <p class="text-muted mt-3">Belum ada siswa aktif</p>
                <a href="{{ route('siswa.create') }}" class="btn btn-primary mt-2">
                    <i class="bi bi-plus-circle"></i> Tambah Siswa
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
