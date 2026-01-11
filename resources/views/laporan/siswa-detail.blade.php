@extends('layouts.app')

@section('title', 'Laporan Detail Siswa')

@section('page-title', 'Laporan Detail Siswa')

@section('content')
<div class="container-fluid">
    <!-- Header dengan Foto & Info Siswa -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-auto">
                    @if($siswa->foto && file_exists(public_path($siswa->foto)))
                        <img src="{{ asset($siswa->foto) }}" alt="{{ $siswa->nama }}" 
                             class="rounded-circle" width="120" height="120" 
                             style="object-fit: cover; border: 4px solid #667eea;">
                    @else
                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 120px; height: 120px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: 4px solid #667eea;">
                            <span class="text-white" style="font-size: 48px; font-weight: bold;">
                                {{ strtoupper(substr($siswa->nama, 0, 1)) }}
                            </span>
                        </div>
                    @endif
                </div>
                <div class="col">
                    <h2 class="mb-2">{{ $siswa->nama }}</h2>
                    <div class="row g-3">
                        <div class="col-auto">
                            <small class="text-muted d-block">Umur</small>
                            <strong>{{ $umur }} Tahun</strong>
                        </div>
                        <div class="col-auto">
                            <small class="text-muted d-block">Tanggal Lahir</small>
                            <strong>{{ \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d M Y') }}</strong>
                        </div>
                        <div class="col-auto">
                            <small class="text-muted d-block">Posisi</small>
                            <strong>{{ $siswa->minat_posisi_string }}</strong>
                        </div>
                        <div class="col-auto">
                            <small class="text-muted d-block">Status</small>
                            <span class="badge {{ $siswa->status == 'Aktif' ? 'bg-success' : 'bg-secondary' }}">
                                {{ $siswa->status }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-auto">
                    <a href="{{ route('laporan.siswa.detail', $siswa->id) }}?pdf=1" class="btn btn-danger" target="_blank">
                        <i class="bi bi-file-pdf"></i> Export PDF
                    </a>
                    <a href="{{ route('laporan.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Kehadiran -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-calendar-check text-success" style="font-size: 36px;"></i>
                    <h3 class="mt-2 mb-0">{{ $statistikKehadiran['hadir'] }}</h3>
                    <small class="text-muted">Hadir</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-clipboard-check text-info" style="font-size: 36px;"></i>
                    <h3 class="mt-2 mb-0">{{ $statistikKehadiran['izin'] }}</h3>
                    <small class="text-muted">Izin</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-heart-pulse text-warning" style="font-size: 36px;"></i>
                    <h3 class="mt-2 mb-0">{{ $statistikKehadiran['sakit'] }}</h3>
                    <small class="text-muted">Sakit</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-x-circle text-danger" style="font-size: 36px;"></i>
                    <h3 class="mt-2 mb-0">{{ $statistikKehadiran['alpa'] }}</h3>
                    <small class="text-muted">Alpa</small>
                </div>
            </div>
        </div>
    </div>

    {{-- <!-- Persentase Kehadiran & Tren -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white text-center py-4">
                    <h6 class="text-white-50 mb-2">Tingkat Kehadiran</h6>
                    <h1 class="display-4 mb-0">{{ $statistikKehadiran['persentase_hadir'] }}%</h1>
                    <small>dari {{ $statistikKehadiran['total'] }} hari (30 hari terakhir)</small>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="bi bi-graph-up"></i> Tren Kehadiran (4 Minggu Terakhir)
                    </h5>
                    <canvas id="lineChartKehadiran" height="80"></canvas>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Statistik Evaluasi -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-clipboard-data text-primary" style="font-size: 36px;"></i>
                    <h3 class="mt-2 mb-0">{{ $statistikEvaluasi['total_evaluasi'] }}</h3>
                    <small class="text-muted">Total Evaluasi</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-star-fill text-warning" style="font-size: 36px;"></i>
                    <h3 class="mt-2 mb-0">{{ $statistikEvaluasi['rata_rata_keseluruhan'] }}</h3>
                    <small class="text-muted">Rata-rata Nilai</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-success">
                <div class="card-body text-center">
                    <i class="bi bi-trophy-fill text-success" style="font-size: 36px;"></i>
                    <h3 class="mt-2 mb-0">{{ $statistikEvaluasi['kategori_terbaik']['nama'] ?? '-' }}</h3>
                    <small class="text-muted">
                        Kategori Terbaik 
                        @if(isset($statistikEvaluasi['kategori_terbaik']))
                            ({{ $statistikEvaluasi['kategori_terbaik']['rata_rata'] }})
                        @endif
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-danger">
                <div class="card-body text-center">
                    <i class="bi bi-exclamation-triangle text-danger" style="font-size: 36px;"></i>
                    <h3 class="mt-2 mb-0">{{ $statistikEvaluasi['kategori_terlemah']['nama'] ?? '-' }}</h3>
                    <small class="text-muted">
                        Perlu Ditingkatkan
                        @if(isset($statistikEvaluasi['kategori_terlemah']))
                            ({{ $statistikEvaluasi['kategori_terlemah']['rata_rata'] }})
                        @endif
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts: Progress Nilai & Radar -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="bi bi-graph-up-arrow"></i> Progress Nilai per Minggu
                    </h5>
                    <canvas id="lineChartProgress" height="60"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3 text-center">
                        <i class="bi bi-radar"></i> Nilai per Kategori
                    </h5>
                    <canvas id="radarChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Evaluasi Grid -->\n    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-3">
                <i class="bi bi-list-ul"></i> Riwayat Evaluasi
            </h5>
            @if($evaluasiData->count() > 0)
                @php
                    $evaluasiPerMinggu = $evaluasiData->groupBy('minggu')->sortByDesc(function($item, $key) {
                        return $key;
                    });
                    $kategoris = \App\Models\KategoriPenilaian::where('aktif', true)->orderBy('urutan')->get();
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
                                <th style="width: 200px;">Catatan</th>
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
                                        $evalKategori = $evaluasiMinggu->firstWhere('kategori_penilaian_id', $kategori->id);
                                        $nilai = $evalKategori ? $evalKategori->nilai : null;
                                    @endphp
                                    <td class="text-center">
                                        @if($nilai !== null)
                                            <span class="badge 
                                                @if($nilai >= 80) bg-success
                                                @elseif($nilai >= 60) bg-warning text-dark
                                                @else bg-danger
                                                @endif
                                            " style="font-size: 13px; min-width: 40px;">
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
                                        $rataRata = $nilaiValid->isNotEmpty() ? $nilaiValid->avg('nilai') : 0;
                                    @endphp
                                    <strong class="text-info">{{ number_format($rataRata, 1) }}</strong>
                                </td>
                                <td>
                                    <small>{{ $evaluasiMinggu->first()->catatan ?: '-' }}</small>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="bi bi-inbox" style="font-size: 48px; color: #ccc;"></i>
                    <p class="text-muted mt-3">Belum ada data evaluasi</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('vendor/chartjs/chart.min.js') }}"></script>
<script>
    // Chart Line - Tren Kehadiran
    /* const ctxLine = document.getElementById('lineChartKehadiran').getContext('2d');
    new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: {!! json_encode($lineChartData['labels']) !!},
            datasets: [{
                label: 'Jumlah Kehadiran',
                data: {!! json_encode($lineChartData['data']) !!},
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
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
    }); */

    // Chart Line - Progress Nilai
    const ctxProgress = document.getElementById('lineChartProgress').getContext('2d');
    new Chart(ctxProgress, {
        type: 'line',
        data: {
            labels: {!! json_encode($progressChartData['labels']) !!},
            datasets: [{
                label: 'Rata-rata Nilai',
                data: {!! json_encode($progressChartData['data']) !!},
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.4,
                fill: true,
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });

    // Chart Radar - Per Kategori
    const ctxRadar = document.getElementById('radarChart').getContext('2d');
    new Chart(ctxRadar, {
        type: 'radar',
        data: {
            labels: {!! json_encode($radarChartData['labels']) !!},
            datasets: [{
                label: 'Nilai',
                data: {!! json_encode($radarChartData['data']) !!},
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.2)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                r: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        stepSize: 20
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>
@endpush
@endsection
