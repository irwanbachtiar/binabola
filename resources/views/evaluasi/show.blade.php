@extends('layouts.app')

@section('title', 'Progress Evaluasi - ' . $siswa->nama)

@section('page-title', 'Progress & Evaluasi Siswa')

@push('styles')
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
<style>
    /* Statistik kehadiran - penyesuaian ukuran dan jarak agar muat di kotak */
    .stat-card .card-body { padding: 0.9rem; }
    .stat-card .rounded-circle { width: 44px; height: 44px; font-size: 18px; }
    .stat-card .h4 { font-size: 1.25rem; margin-bottom: 0; line-height: 1; }
    .stat-card small { font-size: 0.85rem; }
    .stat-card .flex-grow-1 { padding-left: 4px; }
    @media (max-width: 576px) {
        .stat-card .rounded-circle { width: 40px; height: 40px; font-size: 16px; }
        .stat-card .h4 { font-size: 1.1rem; }
    }
</style>
@endpush

@section('content')
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    @if($siswa->foto)
                        <img src="{{ asset($siswa->foto) }}" alt="Foto" class="rounded-circle me-3" style="width: 80px; height: 80px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-person fs-3 text-white"></i>
                        </div>
                    @endif
                    <div class="flex-grow-1">
                        <h4 class="mb-1">{{ $siswa->nama }}</h4>
                        <p class="mb-1 text-muted">{{ $siswa->umur_detail }} • {{ $siswa->minat_posisi_string }}</p>
                    </div>
                    @can('manage-evaluasi')
                    <a href="{{ route('evaluasi.create', $siswa->id) }}" class="btn btn-primary disabled" role="button" aria-disabled="true" tabindex="-1" onclick="event.preventDefault();">
                        <i class="bi bi-plus-circle"></i> Input Nilai Baru
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Line Charts - One per Parent Category -->
    @foreach($lineChartData as $parentNama => $chartData)
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-graph-up"></i> {{ $parentNama }}</h5>
            </div>
            <div class="card-body">
                <canvas id="lineChart{{ $loop->index }}" height="100"></canvas>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Monthly Average Chart -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-calendar-month"></i> Rata-rata Bulanan</h5>
                <small class="text-muted">Rata-rata nilai per bulan (12 bulan terakhir)</small>
            </div>
            <div class="card-body">
                <canvas id="monthlyChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Radar Chart -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-radar"></i> Penilaian Terkini</h5>
            </div>
            <div class="card-body" style="height: 300px;">
                <canvas id="radarChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Attendance Information -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Statistik Kehadiran</h5>
            </div>
            <div class="card-body" style="height: 300px;">
                <div class="row align-items-center mb-3">
                    <div class="col-6 text-center">
                        <h1 class="text-primary mb-1" style="font-size: 2.4rem; font-weight: 700;">{{ $persenKehadiran }}%</h1>
                        <p class="text-muted mb-0">Tingkat Kehadiran</p>
                    </div>
                    <div class="col-6 text-center">
                        <div class="h4 mb-1 text-secondary" style="font-size:1.25rem;">{{ $totalLatihan }}</div>
                        <p class="text-muted mb-0">Total sesi latihan</p>
                    </div>
                </div>
                
                <div class="row g-2">
                    <div class="col-12 col-sm-6">
                        <div class="card border-success h-100">
                            <div class="card-body p-3 d-flex align-items-center gap-3">
                                <div class="d-flex align-items-center justify-content-center rounded-circle bg-success text-white" style="width:44px;height:44px;">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <div class="flex-grow-1 text-start">
                                    <div class="h4 mb-0">{{ $totalHadir }}</div>
                                    <small class="text-muted">Hadir</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="card border-warning h-100">
                            <div class="card-body p-3 d-flex align-items-center gap-3">
                                <div class="d-flex align-items-center justify-content-center rounded-circle bg-warning text-dark" style="width:44px;height:44px;">
                                    <i class="bi bi-info-circle"></i>
                                </div>
                                <div class="flex-grow-1 text-start">
                                    <div class="h4 mb-0">{{ $totalIzin }}</div>
                                    <small class="text-muted">Izin</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="card border-info h-100">
                            <div class="card-body p-3 d-flex align-items-center gap-3">
                                <div class="d-flex align-items-center justify-content-center rounded-circle bg-info text-white" style="width:44px;height:44px;">
                                    <i class="bi bi-heart-pulse"></i>
                                </div>
                                <div class="flex-grow-1 text-start">
                                    <div class="h4 mb-0">{{ $totalSakit }}</div>
                                    <small class="text-muted">Sakit</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="card border-danger h-100">
                            <div class="card-body p-3 d-flex align-items-center gap-3">
                                <div class="d-flex align-items-center justify-content-center rounded-circle bg-danger text-white" style="width:44px;height:44px;">
                                    <i class="bi bi-x-circle"></i>
                                </div>
                                <div class="flex-grow-1 text-start">
                                    <div class="h4 mb-0">{{ $totalAlpa }}</div>
                                    <small class="text-muted">Alpa</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-list-ul"></i> Riwayat Evaluasi</h5>
            </div>
            <div class="card-body">
                @if($evaluasi->count() > 0)
                    @php
                        $evaluasiPerMinggu = $evaluasi->groupBy('minggu')->sortByDesc(function($item, $key) {
                            return $key;
                        });
                        $parentKategoris = $allKategoris->whereNull('parent_id');
                    @endphp
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm">
                            <thead class="table-primary">
                                <tr>
                                    <th class="text-center align-middle" rowspan="2" style="width: 80px;">Minggu</th>
                                    <th class="text-center align-middle" rowspan="2" style="width: 100px;">Tanggal</th>
                                    
                                    @foreach($parentKategoris as $parent)
                                        @php
                                            $children = $allKategoris->where('parent_id', $parent->id);
                                        @endphp
                                        <th colspan="{{ $children->count() }}" class="text-center 
                                            @if($parent->nama === 'Teknik') bg-primary
                                            @elseif($parent->nama === 'Etika') bg-success
                                            @elseif($parent->minggu_terakhir) bg-warning
                                            @else bg-info
                                            @endif text-white">
                                            {{ $parent->nama }}
                                        </th>
                                    @endforeach
                                    
                                    <th class="text-center align-middle" rowspan="2" style="width: 80px;">Rata-rata</th>
                                    <th class="align-middle" rowspan="2" style="width: 150px;">Catatan</th>
                                </tr>
                                <tr>
                                    @foreach($parentKategoris as $parent)
                                        @php
                                            $children = $allKategoris->where('parent_id', $parent->id);
                                        @endphp
                                        @foreach($children as $child)
                                            <th class="text-center" style="min-width: 70px; font-size: 11px;">{{ $child->nama }}</th>
                                        @endforeach
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($evaluasiPerMinggu as $minggu => $evaluasiMinggu)
                                <tr>
                                    <td class="text-center">
                                        <span class="badge bg-primary">M{{ $minggu }}</span>
                                    </td>
                                    <td class="text-center">
                                        <small>{{ $evaluasiMinggu->first()->tanggal_evaluasi ? \Carbon\Carbon::parse($evaluasiMinggu->first()->tanggal_evaluasi)->format('d/m/Y') : '-' }}</small>
                                    </td>
                                    
                                    @foreach($parentKategoris as $parent)
                                        @php
                                            $children = $allKategoris->where('parent_id', $parent->id);
                                        @endphp
                                        @foreach($children as $child)
                                            @php
                                                $evalKategori = $evaluasiMinggu->firstWhere('kategori_penilaian_id', $child->id);
                                                $nilai = $evalKategori ? $evalKategori->nilai : null;
                                            @endphp
                                            <td class="text-center">
                                                @if($nilai !== null)
                                                    <span class="badge 
                                                        @if($nilai >= 80) bg-success
                                                        @elseif($nilai >= 60) bg-warning text-dark
                                                        @else bg-danger
                                                        @endif
                                                    " style="font-size: 11px; min-width: 35px;">
                                                        {{ $nilai }}
                                                    </span>
                                                @else
                                                    <span class="text-muted" style="font-size: 11px;">-</span>
                                                @endif
                                            </td>
                                        @endforeach
                                    @endforeach
                                    
                                    <td class="text-center">
                                        @php
                                            $catatanPertama = $evaluasiMinggu->first()->catatan;
                                            $isTidakHadir = $catatanPertama && str_contains($catatanPertama, 'Tidak hadir');
                                            // Filter hanya nilai yang tidak null (nilai 0 dari ketidakhadiran tetap dihitung)
                                            $nilaiValid = $evaluasiMinggu->filter(function($eval) {
                                                return $eval->nilai !== null;
                                            });
                                            $rataRata = $nilaiValid->isNotEmpty() ? $nilaiValid->avg('nilai') : 0;
                                        @endphp
                                        @if($isTidakHadir)
                                            <span class="badge bg-danger" style="font-size: 11px;">
                                                <i class="bi bi-exclamation-triangle"></i> {{ number_format($rataRata, 1) }}
                                            </span>
                                        @else
                                            <strong class="text-info" style="font-size: 12px;">{{ number_format($rataRata, 1) }}</strong>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $catatan = $evaluasiMinggu->first()->catatan ?: '-';
                                            $isTidakHadir = str_contains($catatan, 'Tidak hadir');
                                        @endphp
                                        @if($isTidakHadir)
                                            <span class="badge bg-danger" style="font-size: 11px;">
                                                <i class="bi bi-exclamation-triangle"></i> {{ $catatan }}
                                            </span>
                                        @else
                                            <small style="font-size: 11px;">{{ $catatan }}</small>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <p class="text-muted">Belum ada data evaluasi</p>
                        @can('manage-evaluasi')
                        <a href="{{ route('evaluasi.create', $siswa->id) }}" class="btn btn-primary btn-sm disabled" role="button" aria-disabled="true" tabindex="-1" onclick="event.preventDefault();">
                            <i class="bi bi-plus-circle"></i> Input Evaluasi Pertama
                        </a>
                        @endcan
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Histori Ketidakhadiran -->
@if($historiTidakHadir->count() > 0)
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-calendar-x"></i> Histori Ketidakhadiran</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm">
                        <thead class="table-secondary">
                            <tr>
                                <th class="text-center" style="width: 50px;">No</th>
                                <th class="text-center" style="width: 120px;">Tanggal</th>
                                <th class="text-center" style="width: 100px;">Sesi</th>
                                <th class="text-center" style="width: 100px;">Status</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($historiTidakHadir as $index => $absen)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="text-center">
                                    <small>{{ \Carbon\Carbon::parse($absen->tanggal)->format('d/m/Y') }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info">{{ $absen->sesi }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge 
                                        @if($absen->status === 'Izin') bg-warning text-dark
                                        @elseif($absen->status === 'Sakit') bg-primary
                                        @else bg-danger
                                        @endif
                                    ">
                                        @if($absen->status === 'Izin')
                                            <i class="bi bi-info-circle"></i>
                                        @elseif($absen->status === 'Sakit')
                                            <i class="bi bi-heart-pulse"></i>
                                        @else
                                            <i class="bi bi-x-circle"></i>
                                        @endif
                                        {{ $absen->status }}
                                    </span>
                                </td>
                                <td>
                                    <small>{{ $absen->keterangan ?: '-' }}</small>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Floating Back Button (bottom-right) -->
<button type="button" class="btn btn-secondary position-fixed" style="right:20px; bottom:20px; z-index:1000;" onclick="history.back()" aria-label="Kembali">
    <i class="bi bi-arrow-left-circle"></i> Kembali
</button>

@endsection

@push('scripts')
<script src="{{ asset('vendor/chartjs/chart.min.js') }}"></script>
<script>
    // Multiple Line Charts - One per Parent Category
    const lineChartData = {!! json_encode($lineChartData) !!};
    const weeks = {!! json_encode($weeks) !!};
    const weekLabels = weeks.map(w => 'Minggu ' + w);
    
    let chartIndex = 0;
    for (const [parentNama, subData] of Object.entries(lineChartData)) {
        const canvasId = 'lineChart' + chartIndex;
        const canvas = document.getElementById(canvasId);
        
        if (canvas) {
            const ctx = canvas.getContext('2d');
            const datasets = [];
            let colorIndex = 0;
            
            for (const [subNama, values] of Object.entries(subData)) {
                datasets.push({
                    label: subNama,
                    data: values,
                    borderColor: getColor(colorIndex),
                    backgroundColor: getColor(colorIndex, 0.1),
                    tension: 0.4,
                    fill: true
                });
                colorIndex++;
            }
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: weekLabels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { 
                            position: 'top',
                            labels: {
                                boxWidth: 12,
                                font: { size: 10 }
                            }
                        },
                        title: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            title: { display: true, text: 'Nilai' }
                        }
                    }
                }
            });
        }
        
        chartIndex++;
    }

    // Monthly Average Chart
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyLabels = {!! json_encode(array_values($monthlyData)) !!};
    const monthlyValues = {!! json_encode(array_values($monthlyAverages)) !!};
    
    const monthlyChart = new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: monthlyLabels,
            datasets: [{
                label: 'Rata-rata Bulanan',
                data: monthlyValues,
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.4,
                fill: true,
                spanGaps: true // Connect lines even if there's null data
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { 
                    display: false
                },
                title: { 
                    display: false 
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    title: { 
                        display: true, 
                        text: 'Nilai' 
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Bulan'
                    }
                }
            }
        }
    });

    // Radar Chart - Latest Week
    const radarCtx = document.getElementById('radarChart').getContext('2d');
    const radarLabels = {!! json_encode(array_keys($radarChartData)) !!};
    const radarData = {!! json_encode(array_values($radarChartData)) !!};
    
    const radarChart = new Chart(radarCtx, {
        type: 'radar',
        data: {
            labels: radarLabels,
            datasets: [{
                label: 'Nilai Terkini',
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
