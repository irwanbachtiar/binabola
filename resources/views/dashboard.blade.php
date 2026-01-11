@extends('layouts.app')

@section('title', 'Dashboard - Sekolah Sepak Bola')

@section('page-title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-subtitle mb-2">Total Siswa</h6>
                        <h2 class="card-title mb-0">{{ \App\Models\Siswa::count() }}</h2>
                    </div>
                    <div>
                        <i class="bi bi-people-fill" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white" style="background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-subtitle mb-2">Kelompok U-12</h6>
                        <h2 class="card-title mb-0">{{ \App\Models\Siswa::whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 8 AND 12')->count() }}</h2>
                        <small style="opacity: 0.9;">Umur 8-12 tahun</small>
                    </div>
                    <div>
                        <i class="bi bi-people" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white" style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-subtitle mb-2">Kelompok U-7</h6>
                        <h2 class="card-title mb-0">{{ \App\Models\Siswa::whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 3 AND 7')->count() }}</h2>
                        <small style="opacity: 0.9;">Umur 3-7 tahun</small>
                    </div>
                    <div>
                        <i class="bi bi-people" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-subtitle mb-2">Siswa Aktif</h6>
                        <h2 class="card-title mb-0">{{ \App\Models\Siswa::where('status', 'Aktif')->count() }}</h2>
                    </div>
                    <div>
                        <i class="bi bi-check-circle-fill" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-subtitle mb-2">Jadwal Hari Ini</h6>
                        <h2 class="card-title mb-0">0</h2>
                    </div>
                    <div>
                        <i class="bi bi-calendar-check-fill" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-subtitle mb-2">Pelatih</h6>
                        <h2 class="card-title mb-0">{{ \App\Models\User::where('role', 'pelatih')->count() }}</h2>
                    </div>
                    <div>
                        <i class="bi bi-person-badge-fill" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-secondary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-subtitle mb-2">Orang Tua</h6>
                        <h2 class="card-title mb-0">{{ \App\Models\User::where('role', 'orangtua')->count() }}</h2>
                    </div>
                    <div>
                        <i class="bi bi-person-hearts" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-graph-up"></i> Statistik Posisi Siswa</h5>
            </div>
            <div class="card-body">
                @php
                    $posisi = \App\Models\Siswa::select('posisi', \DB::raw('count(*) as total'))
                        ->groupBy('posisi')
                        ->orderBy('total', 'DESC')
                        ->get();
                @endphp
                
                @if($posisi->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Posisi</th>
                                    <th>Jumlah Siswa</th>
                                    <th>Persentase</th>
                                    <th>Grafik</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($posisi as $p)
                                @php
                                    $persentase = (\App\Models\Siswa::count() > 0) ? ($p->total / \App\Models\Siswa::count() * 100) : 0;
                                @endphp
                                <tr>
                                    <td><strong>{{ $p->posisi }}</strong></td>
                                    <td>{{ $p->total }} siswa</td>
                                    <td>{{ number_format($persentase, 1) }}%</td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-primary" 
                                                 role="progressbar" 
                                                 style="width: {{ $persentase }}%;" 
                                                 aria-valuenow="{{ $persentase }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <p class="text-muted">Belum ada data siswa</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-star-fill"></i> Menu Cepat</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('siswa.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-people"></i> Lihat Data Siswa
                    </a>
                    <a href="{{ route('siswa.create') }}" class="btn btn-outline-success">
                        <i class="bi bi-plus-circle"></i> Tambah Siswa Baru
                    </a>
                    <button class="btn btn-outline-secondary" disabled>
                        <i class="bi bi-calendar"></i> Jadwal Latihan
                    </button>
                    <button class="btn btn-outline-secondary" disabled>
                        <i class="bi bi-clipboard-data"></i> Laporan Penilaian
                    </button>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-bell-fill"></i> Pengumuman</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-0">
                    <small><i class="bi bi-info-circle"></i> Selamat datang di Sistem Manajemen Sekolah Sepak Bola BinaBola!</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        transition: transform 0.2s;
    }
    .card:hover {
        transform: translateY(-5px);
    }
</style>
@endpush
