@extends('layouts.app')

@section('title', 'Evaluasi Siswa - Sekolah Sepak Bola')

@section('page-title', 'Penilaian & Evaluasi Siswa')

@section('content')
{{-- <div class="alert alert-info mb-3">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <i class="bi bi-info-circle"></i> <strong>Penilaian Regular</strong> - Evaluasi mendalam per siswa dengan grafik progress
        </div>
        <a href="{{ route('evaluasi.batch') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-clipboard-check"></i> Coba Penilaian Batch
        </a>
    </div>
</div> --}}

<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="bi bi-clipboard-data"></i> Pilih Siswa untuk Evaluasi</h5>
    </div>
    <div class="card-body">
        <!-- Kelompok U-12 -->
        <div class="mb-5">
            <div class="d-flex align-items-center mb-3">
                <h5 class="mb-0 me-2">
                    <i class="bi bi-people-fill text-success"></i> Kelompok Umur U-12 (8-12 tahun)
                </h5>
                <span class="badge bg-success">{{ $siswaU12->count() }} Siswa</span>
            </div>
            
            @if($siswaU12->count() > 0)
                <div class="list-group">
                    @foreach($siswaU12 as $siswa)
                    <div class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            @if($siswa->foto)
                                <img src="{{ asset($siswa->foto) }}" 
                                     alt="Foto {{ $siswa->nama }}" 
                                     class="rounded-circle me-3" 
                                     style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-3" 
                                     style="width: 60px; height: 60px;">
                                    <i class="bi bi-person fs-4 text-white"></i>
                                </div>
                            @endif
                            
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $siswa->nama }}</h6>
                                <p class="mb-1 text-muted small">
                                    <i class="bi bi-calendar3"></i> {{ $siswa->umur_detail }}
                                    <span class="badge bg-success ms-2">{{ $siswa->kelompok_umur }}</span>
                                    @if($siswa->minat_posisi && count($siswa->minat_posisi) > 0)
                                        <span class="ms-2">
                                            <i class="bi bi-trophy"></i>
                                            @foreach($siswa->minat_posisi as $posisi)
                                                <span class="badge bg-info me-1">{{ $posisi }}</span>
                                            @endforeach
                                        </span>
                                    @endif
                                </p>
                            </div>
                            
                            <div class="btn-group" role="group">
                                {{-- <a href="{{ route('evaluasi.create', $siswa->id) }}" 
                                   class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil-square"></i> Input Nilai
                                </a> --}}
                                <a href="{{ route('evaluasi.show', $siswa->id) }}" 
                                   class="btn btn-sm btn-success">
                                    <i class="bi bi-graph-up"></i> Lihat Progress
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Tidak ada siswa aktif di kelompok U-12
                </div>
            @endif
        </div>

        <!-- Kelompok U-7 -->
        <div>
            <div class="d-flex align-items-center mb-3">
                <h5 class="mb-0 me-2">
                    <i class="bi bi-people-fill text-info"></i> Kelompok Umur U-7 (3-7 tahun)
                </h5>
                <span class="badge bg-info">{{ $siswaU7->count() }} Siswa</span>
            </div>
            
            @if($siswaU7->count() > 0)
                <div class="list-group">
                    @foreach($siswaU7 as $siswa)
                    <div class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            @if($siswa->foto)
                                <img src="{{ asset($siswa->foto) }}" 
                                     alt="Foto {{ $siswa->nama }}" 
                                     class="rounded-circle me-3" 
                                     style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-3" 
                                     style="width: 60px; height: 60px;">
                                    <i class="bi bi-person fs-4 text-white"></i>
                                </div>
                            @endif
                            
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $siswa->nama }}</h6>
                                <p class="mb-1 text-muted small">
                                    <i class="bi bi-calendar3"></i> {{ $siswa->umur_detail }}
                                    <span class="badge bg-info ms-2">{{ $siswa->kelompok_umur }}</span>
                                    @if($siswa->minat_posisi && count($siswa->minat_posisi) > 0)
                                        <span class="ms-2">
                                            <i class="bi bi-trophy"></i>
                                            @foreach($siswa->minat_posisi as $posisi)
                                                <span class="badge bg-info me-1">{{ $posisi }}</span>
                                            @endforeach
                                        </span>
                                    @endif
                                </p>
                            </div>
                            
                            <div class="btn-group" role="group">
                                {{-- <a href="{{ route('evaluasi.create', $siswa->id) }}" 
                                   class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil-square"></i> Input Nilai
                                </a> --}}
                                <a href="{{ route('evaluasi.show', $siswa->id) }}" 
                                   class="btn btn-sm btn-success">
                                    <i class="bi bi-graph-up"></i> Lihat Progress
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Tidak ada siswa aktif di kelompok U-7
                </div>
            @endif
        </div>
        
        @if($siswaU12->count() === 0 && $siswaU7->count() === 0)
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
