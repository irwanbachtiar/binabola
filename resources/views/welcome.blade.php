@extends('layouts.app')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('content')
<!-- Stats Cards -->
<div class="row mb-3">
    <div class="col-md-3 mb-2">
        <div class="card text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body py-2 px-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1 small">Total Siswa</h6>
                        <h3 class="mb-0 fw-bold">{{ $totalSiswa }}</h3>
                        <small style="font-size: 0.75rem;">Aktif: {{ $siswaAktif }} | Non: {{ $siswaNonAktif }}</small>
                    </div>
                    <i class="bi bi-people" style="font-size: 2.5rem; opacity: 0.4;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-2">
        <div class="card text-white" style="background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%);">
            <div class="card-body py-2 px-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1 small">Kelompok U-12</h6>
                        <h3 class="mb-0 fw-bold">{{ \App\Models\Siswa::whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 8 AND 12')->count() }}</h3>
                        <small style="font-size: 0.75rem;">Usia 8-12 tahun</small>
                    </div>
                    <i class="bi bi-people" style="font-size: 2.5rem; opacity: 0.4;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-2">
        <div class="card text-white" style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);">
            <div class="card-body py-2 px-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1 small">Kelompok U-7</h6>
                        <h3 class="mb-0 fw-bold">{{ \App\Models\Siswa::whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 3 AND 7')->count() }}</h3>
                        <small style="font-size: 0.75rem;">Usia 3-7 tahun</small>
                    </div>
                    <i class="bi bi-people" style="font-size: 2.5rem; opacity: 0.4;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-2">
        <div class="card text-white bg-success">
            <div class="card-body py-2 px-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1 small">Kehadiran</h6>
                        <h3 class="mb-0 fw-bold">{{ $persentaseKehadiran }}%</h3>
                        <small style="font-size: 0.75rem;">{{ $totalHadir }}/{{ $totalHadir + $totalIzin + $totalSakit + $totalAlpa }} hari</small>
                    </div>
                    <i class="bi bi-calendar-check" style="font-size: 2.5rem; opacity: 0.4;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Second Row Stats -->
<div class="row mb-3">
    <div class="col-md-4 mb-2">
        <div class="card text-white bg-info">
            <div class="card-body py-2 px-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1 small">Evaluasi Bulan Ini</h6>
                        <h3 class="mb-0 fw-bold">{{ $totalEvaluasi }}</h3>
                        <small style="font-size: 0.75rem;">Rata-rata: {{ $rataRataNilai }}</small>
                    </div>
                    <i class="bi bi-clipboard-data" style="font-size: 2.5rem; opacity: 0.4;"></i>
                </div>
            </div>
        </div>
    </div>
    
    {{-- <div class="col-md-4 mb-2">
        <div class="card text-white bg-warning">
            <div class="card-body py-2 px-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1 small">Tidak Hadir</h6>
                        <h3 class="mb-0 fw-bold">{{ $totalIzin + $totalSakit + $totalAlpa }}</h3>
                        <small style="font-size: 0.75rem;">I:{{ $totalIzin }} S:{{ $totalSakit }} A:{{ $totalAlpa }}</small>
                    </div>
                    <i class="bi bi-exclamation-triangle" style="font-size: 2.5rem; opacity: 0.4;"></i>
                </div>
            </div>
        </div>
    </div> --}}
    
    {{-- <div class="col-md-4 mb-2">
        <div class="card text-white bg-secondary">
            <div class="card-body py-2 px-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1 small">Status Siswa</h6>
                        <h3 class="mb-0 fw-bold">{{ $siswaAktif }}</h3>
                        <small style="font-size: 0.75rem;">Aktif: {{ $siswaAktif }} | Non: {{ $siswaNonAktif }}</small>
                    </div>
                    <i class="bi bi-check-circle" style="font-size: 2.5rem; opacity: 0.4;"></i>
                </div>
            </div>
        </div>
    </div> --}}
</div>

<!-- Charts Row -->
<div class="row mb-3">
    <div class="col-md-8 mb-2">
        <div class="card">
            <div class="card-header bg-white py-2">
                <h6 class="mb-0"><i class="bi bi-graph-up"></i> Tren Kehadiran 4 Minggu Terakhir</h6>
            </div>
            <div class="card-body py-2">
                <div style="position: relative; height: 300px; min-height: 300px; max-height: 300px; width: 100%; overflow: hidden;">
                    <canvas id="kehadiranChart" width="800" height="300" style="display: block; width: 100% !important; height: 300px !important;"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-2">
        <div class="card">
            <div class="card-header bg-white py-2">
                <h6 class="mb-0"><i class="bi bi-award"></i> TOP SISWA</h6>
            </div>
            <div class="card-body py-2">
                @forelse($siswaTopPenilaian as $siswa)
                    <div class="d-flex align-items-center mb-2 pb-1 {{ !$loop->last ? 'border-bottom' : '' }}">
                        @if($siswa->foto)
                            <img src="{{ asset($siswa->foto) }}" alt="Foto" class="rounded-circle me-2" style="width: 30px; height: 30px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                                <i class="bi bi-person text-white" style="font-size: 0.8rem;"></i>
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <strong class="d-block" style="font-size: 0.85rem;">{{ $siswa->nama }}</strong>
                            <small class="text-muted" style="font-size: 0.7rem;">Avg: {{ $siswa->avg_nilai ? number_format($siswa->avg_nilai,1) : '-' }}</small>
                        </div>
                        <span class="badge bg-success" style="font-size: 0.7rem;">{{ $loop->iteration }}</span>
                    </div>
                @empty
                    <p class="text-muted text-center mb-0">Belum ada data</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
{{-- <div class="row">
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
</div> --}}
@endsection

@push('scripts')
<script src="{{ asset('vendor/chartjs/chart.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Kehadiran Chart
    const canvas = document.getElementById('kehadiranChart');
    if (!canvas) return;
    
    const ctx = canvas.getContext('2d');
    const kehadiranChart = new Chart(ctx, {
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
            responsive: false,
            maintainAspectRatio: false,
            animation: false,
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
});
</script>
@endpush
