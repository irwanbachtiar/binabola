@extends('layouts.app')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('content')
<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1">Total Siswa</h6>
                        <h2 class="mb-0">{{ $totalSiswa }}</h2>
                        <small>Aktif: {{ $siswaAktif }} | Non-Aktif: {{ $siswaNonAktif }}</small>
                    </div>
                    <i class="bi bi-people fs-1"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1">Kehadiran Bulan Ini</h6>
                        <h2 class="mb-0">{{ $persentaseKehadiran }}%</h2>
                        <small>{{ $totalHadir }} dari {{ $totalHadir + $totalIzin + $totalSakit + $totalAlpa }} hari</small>
                    </div>
                    <i class="bi bi-calendar-check fs-1"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1">Evaluasi Bulan Ini</h6>
                        <h2 class="mb-0">{{ $totalEvaluasi }}</h2>
                        <small>Rata-rata: {{ $rataRataNilai }}</small>
                    </div>
                    <i class="bi bi-clipboard-data fs-1"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1">Tidak Hadir</h6>
                        <h2 class="mb-0">{{ $totalIzin + $totalSakit + $totalAlpa }}</h2>
                        <small>Izin:{{ $totalIzin }} Sakit:{{ $totalSakit }} Alpa:{{ $totalAlpa }}</small>
                    </div>
                    <i class="bi bi-exclamation-triangle fs-1"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <div class="col-md-8 mb-3">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-graph-up"></i> Tren Kehadiran 7 Hari Terakhir</h5>
            </div>
            <div class="card-body">
                <canvas id="kehadiranChart" height="80"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-award"></i> Top Kehadiran</h5>
            </div>
            <div class="card-body">
                @forelse($siswaTopKehadiran as $siswa)
                    <div class="d-flex align-items-center mb-2 pb-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                        @if($siswa->foto)
                            <img src="{{ asset($siswa->foto) }}" alt="Foto" class="rounded-circle me-2" style="width: 35px; height: 35px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                <i class="bi bi-person text-white"></i>
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <strong class="d-block">{{ $siswa->nama }}</strong>
                            <small class="text-muted">{{ $siswa->hadir_count }} hari hadir</small>
                        </div>
                        <span class="badge bg-success">{{ $loop->iteration }}</span>
                    </div>
                @empty
                    <p class="text-muted text-center">Belum ada data</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Aktivitas Evaluasi Terbaru</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Siswa</th>
                                <th>Kategori</th>
                                <th>Nilai</th>
                                <th>Minggu</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($evaluasiTerbaru as $evaluasi)
                            <tr>
                                <td>
                                    <strong>{{ $evaluasi->siswa->nama }}</strong>
                                </td>
                                <td>{{ $evaluasi->kategori->nama }}</td>
                                <td>
                                    <span class="badge 
                                        @if($evaluasi->nilai >= 80) bg-success
                                        @elseif($evaluasi->nilai >= 60) bg-warning
                                        @else bg-danger
                                        @endif">
                                        {{ $evaluasi->nilai }}
                                    </span>
                                </td>
                                <td>Minggu {{ $evaluasi->minggu }}</td>
                                <td><small class="text-muted">{{ $evaluasi->created_at->diffForHumans() }}</small></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <p class="text-muted">Belum ada evaluasi</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Kehadiran Chart
const ctx = document.getElementById('kehadiranChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($chartLabels) !!},
        datasets: [
            {
                label: 'Hadir',
                data: {!! json_encode($chartHadir) !!},
                borderColor: 'rgb(40, 167, 69)',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.4,
                fill: true
            },
            {
                label: 'Tidak Hadir',
                data: {!! json_encode($chartTidakHadir) !!},
                borderColor: 'rgb(220, 53, 69)',
                backgroundColor: 'rgba(220, 53, 69, 0.1)',
                tension: 0.4,
                fill: true
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'top',
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});
</script>
@endpush
