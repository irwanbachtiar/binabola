@extends('layouts.mobile')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard BinaBola')

@section('content')
<!-- Summary Cards -->
<div class="row g-2 mb-3">
    <!-- Total Siswa -->
    <div class="col-6">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-people fs-2 text-primary"></i>
                <h4 class="mb-0 mt-2">{{ $totalSiswa }}</h4>
                <small class="text-muted">Total Siswa</small>
            </div>
        </div>
    </div>

    <!-- Siswa Aktif -->
    <div class="col-6">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-person-check fs-2 text-success"></i>
                <h4 class="mb-0 mt-2">{{ $siswaAktif }}</h4>
                <small class="text-muted">Siswa Aktif</small>
            </div>
        </div>
    </div>

    <!-- Kehadiran -->
    <div class="col-6">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-calendar-check fs-2 text-info"></i>
                <h4 class="mb-0 mt-2">{{ $persentaseKehadiran }}%</h4>
                <small class="text-muted">Kehadiran</small>
            </div>
        </div>
    </div>

    <!-- Rata-rata Nilai -->
    <div class="col-6">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-star fs-2 text-warning"></i>
                <h4 class="mb-0 mt-2">{{ $rataRataNilai }}</h4>
                <small class="text-muted">Rata-rata Nilai</small>
            </div>
        </div>
    </div>
</div>

<!-- Absensi Stats -->
<div class="card mb-3">
    <div class="card-header">
        <strong><i class="bi bi-calendar3"></i> Absensi Bulan Ini</strong>
    </div>
    <div class="card-body">
        <div class="row text-center">
            <div class="col-3">
                <div class="text-success">
                    <h5>{{ $totalHadir }}</h5>
                    <small>Hadir</small>
                </div>
            </div>
            <div class="col-3">
                <div class="text-warning">
                    <h5>{{ $totalIzin }}</h5>
                    <small>Izin</small>
                </div>
            </div>
            <div class="col-3">
                <div class="text-info">
                    <h5>{{ $totalSakit }}</h5>
                    <small>Sakit</small>
                </div>
            </div>
            <div class="col-3">
                <div class="text-danger">
                    <h5>{{ $totalAlpa }}</h5>
                    <small>Alpa</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Evaluasi Terbaru -->
<div class="card mb-3">
    <div class="card-header">
        <strong><i class="bi bi-clipboard-check"></i> Evaluasi Terbaru</strong>
    </div>
    <div class="card-body p-2">
        @forelse($evaluasiTerbaru as $eval)
        <div class="d-flex align-items-center p-2 border-bottom">
            @if($eval->siswa->foto)
                <img src="{{ asset($eval->siswa->foto) }}" alt="{{ $eval->siswa->nama }}" 
                     style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 10px;">
            @else
                <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; margin-right: 10px;">
                    <i class="bi bi-person text-white"></i>
                </div>
            @endif
            <div class="flex-grow-1">
                <strong style="font-size: 14px;">{{ $eval->siswa->nama }}</strong>
                <br>
                <small class="text-muted">{{ $eval->kategori->nama }}: <strong>{{ $eval->nilai }}</strong></small>
            </div>
            <small class="text-muted">W{{ $eval->minggu }}</small>
        </div>
        @empty
        <p class="text-center text-muted py-3 mb-0">Belum ada evaluasi bulan ini</p>
        @endforelse
    </div>
</div>

<!-- TOP SISWA -->
<div class="card mb-3">
    <div class="card-header">
        <strong><i class="bi bi-trophy"></i> TOP SISWA Bulan Ini</strong>
    </div>
    <div class="card-body p-2">
        @forelse($siswaTopPenilaian as $index => $siswa)
        <div class="d-flex align-items-center p-2 border-bottom">
            <div style="width: 30px; text-align: center; margin-right: 10px;">
                @if($index == 0)
                    <i class="bi bi-trophy-fill text-warning fs-4"></i>
                @elseif($index == 1)
                    <i class="bi bi-trophy-fill text-secondary fs-5"></i>
                @elseif($index == 2)
                    <i class="bi bi-trophy-fill" style="color: #cd7f32; font-size: 1.2rem;"></i>
                @else
                    <span class="text-muted">{{ $index + 1 }}</span>
                @endif
            </div>
            @if($siswa->foto)
                <img src="{{ asset($siswa->foto) }}" alt="{{ $siswa->nama }}" 
                     style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 10px;">
            @else
                <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; margin-right: 10px;">
                    <i class="bi bi-person text-white"></i>
                </div>
            @endif
            <div class="flex-grow-1">
                <strong style="font-size: 14px;">{{ $siswa->nama }}</strong>
            </div>
            <span class="badge bg-success">{{ $siswa->avg_nilai ? number_format($siswa->avg_nilai,1) : '-' }}</span>
        </div>
        @empty
        <p class="text-center text-muted py-3 mb-0">Belum ada data</p>
        @endforelse
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-2 mb-3">
    <div class="col-6">
        <a href="{{ route('evaluasi.index') }}" class="btn btn-primary w-100">
            <i class="bi bi-clipboard-check"></i> Input Evaluasi
        </a>
    </div>
    <div class="col-6">
        <a href="{{ route('absensi.index') }}" class="btn btn-success w-100">
            <i class="bi bi-calendar-check"></i> Input Absensi
        </a>
    </div>
</div>
@endsection
