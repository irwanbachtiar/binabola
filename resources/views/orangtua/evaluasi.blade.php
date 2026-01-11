@extends('layouts.app')

@section('title', 'Evaluasi - ' . $siswa->nama)

@push('styles')
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
@endpush

@section('content')
<div class="container-fluid mt-4">
    <!-- Header dengan Foto -->
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('orangtua.dashboard') }}" class="btn btn-secondary mb-3">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        @if($siswa->foto)
                            <img src="{{ asset($siswa->foto) }}" alt="{{ $siswa->nama }}" class="rounded-circle me-3" style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <div class="rounded-circle text-white d-inline-flex align-items-center justify-content-center me-3" 
                                 style="width: 80px; height: 80px; font-size: 32px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                {{ strtoupper(substr($siswa->nama, 0, 1)) }}
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <h4 class="mb-1">Progress Evaluasi: {{ $siswa->nama }}</h4>
                            <p class="mb-0 text-muted">{{ $siswa->umur_detail }} • {{ $siswa->minat_posisi_string }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Overview -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px;">
                        <i class="bi bi-clipboard-check text-primary" style="font-size: 28px;"></i>
                    </div>
                    <h4 class="fw-bold mb-1">{{ $stats['total_evaluasi'] }}</h4>
                    <p class="text-muted small mb-0">Total Evaluasi</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px;">
                        <i class="bi bi-graph-up text-success" style="font-size: 28px;"></i>
                    </div>
                    <h4 class="fw-bold mb-1">{{ $stats['rata_rata_keseluruhan'] }}</h4>
                    <p class="text-muted small mb-0">Rata-rata Keseluruhan</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card shadow-sm h-100 border-success">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px;">
                        <i class="bi bi-trophy text-success" style="font-size: 28px;"></i>
                    </div>
                    <h6 class="fw-bold mb-1 text-success">{{ $stats['kategori_terbaik'] }}</h6>
                    <p class="text-muted small mb-0">Kategori Terbaik</p>
                    <span class="badge bg-success">{{ $stats['nilai_terbaik'] }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card shadow-sm h-100 border-warning">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-warning bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px;">
                        <i class="bi bi-exclamation-triangle text-warning" style="font-size: 28px;"></i>
                    </div>
                    <h6 class="fw-bold mb-1 text-warning">{{ $stats['kategori_terlemah'] }}</h6>
                    <p class="text-muted small mb-0">Perlu Ditingkatkan</p>
                    <span class="badge bg-warning">{{ $stats['nilai_terlemah'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row mb-4">
        <div class="col-md-8 mb-3">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-graph-up"></i> Progress Mingguan</h5>
                </div>
                <div class="card-body">
                    @if($evaluasi->count() > 0)
                        <canvas id="lineChart" height="100"></canvas>
                    @else
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Belum ada data evaluasi untuk ditampilkan dalam chart.
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-radar"></i> Rata-rata Per Kategori</h5>
                </div>
                <div class="card-body">
                    @if($evaluasi->count() > 0)
                        <canvas id="radarChart"></canvas>
                    @else
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Belum ada data evaluasi.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Riwayat Evaluasi -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-list-ul"></i> Riwayat Evaluasi Detail</h5>
                </div>
                <div class="card-body">
                    @if($evaluasi->count() > 0)
                        @php
                            $evaluasiPerMinggu = $evaluasi->groupBy('minggu')->sortByDesc(function($item, $key) {
                                return $key;
                            });
                        @endphp
                        
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 100px;">Minggu</th>
                                        <th class="text-center" style="width: 120px;">Tanggal</th>
                                        @foreach($kategoris as $kategori)
                                            <th class="text-center">{{ $kategori->nama }}</th>
                                        @endforeach
                                        <th class="text-center" style="width: 100px;">Rata-rata</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($evaluasiPerMinggu as $minggu => $evaluasiMinggu)
                                    <tr>
                                        <td class="text-center">
                                            <span class="badge bg-primary">Minggu {{ $minggu }}</span>
                                        </td>
                                        <td class="text-center">
                                            <small>{{ $evaluasiMinggu->first()->tanggal_evaluasi ? \Carbon\Carbon::parse($evaluasiMinggu->first()->tanggal_evaluasi)->format('d/m/Y') : '-' }}</small>
                                        </td>
                                        @foreach($kategoris as $kategori)
                                            @php
                                                $nilaiEval = $evaluasiMinggu->where('kategori_penilaian_id', $kategori->id)->first();
                                                $nilai = $nilaiEval ? $nilaiEval->nilai : null;
                                            @endphp
                                            <td class="text-center">
                                                @if($nilai !== null)
                                                    <span class="badge 
                                                        @if($nilai >= 80) bg-success
                                                        @elseif($nilai >= 60) bg-warning
                                                        @else bg-danger
                                                        @endif">
                                                        {{ $nilai }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        @endforeach
                                        <td class="text-center">
                                            @php
                                                // Filter nilai yang tidak null (nilai 0 dari ketidakhadiran tetap dihitung)
                                                $nilaiValid = $evaluasiMinggu->filter(function($eval) {
                                                    return $eval->nilai !== null;
                                                });
                                                $avgMinggu = $nilaiValid->isNotEmpty() ? $nilaiValid->avg('nilai') : 0;
                                            @endphp
                                            <strong class="
                                                @if($avgMinggu >= 80) text-success
                                                @elseif($avgMinggu >= 60) text-warning
                                                @else text-danger
                                                @endif">
                                                {{ number_format($avgMinggu, 1) }}
                                            </strong>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Belum ada data evaluasi.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('vendor/chartjs/chart.min.js') }}"></script>
<script>
    // Line Chart - Weekly Progress
    @if($evaluasi->count() > 0)
    const lineCtx = document.getElementById('lineChart').getContext('2d');
    const weeks = {!! json_encode($weeks) !!};
    const lineChartData = {!! json_encode($lineChartData) !!};
    
    const datasets = Object.keys(lineChartData).map((kategori, index) => ({
        label: kategori,
        data: lineChartData[kategori],
        borderColor: getColor(index),
        backgroundColor: getColor(index, 0.1),
        tension: 0.3,
        fill: true
    }));
    
    const lineChart = new Chart(lineCtx, {
        type: 'line',
        data: {
            labels: weeks.map(w => 'Minggu ' + w),
            datasets: datasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: { stepSize: 20 }
                }
            },
            plugins: {
                legend: { display: true, position: 'top' },
                tooltip: { mode: 'index', intersect: false }
            }
        }
    });

    // Radar Chart - Average per Category
    const radarCtx = document.getElementById('radarChart').getContext('2d');
    const radarLabels = {!! json_encode(array_keys($radarChartData)) !!};
    const radarData = {!! json_encode(array_values($radarChartData)) !!};
    
    const radarChart = new Chart(radarCtx, {
        type: 'radar',
        data: {
            labels: radarLabels,
            datasets: [{
                label: 'Rata-rata Nilai',
                data: radarData,
                fill: true,
                backgroundColor: 'rgba(102, 126, 234, 0.2)',
                borderColor: 'rgb(102, 126, 234)',
                pointBackgroundColor: 'rgb(102, 126, 234)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgb(102, 126, 234)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                r: {
                    beginAtZero: true,
                    max: 100,
                    ticks: { stepSize: 20 }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
    @endif

    function getColor(index, alpha = 1) {
        const colors = [
            `rgba(102, 126, 234, ${alpha})`,
            `rgba(255, 99, 132, ${alpha})`,
            `rgba(54, 162, 235, ${alpha})`,
            `rgba(255, 206, 86, ${alpha})`,
            `rgba(75, 192, 192, ${alpha})`,
        ];
        return colors[index % colors.length];
    }
</script>
@endpush
