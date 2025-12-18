@extends('layouts.app')

@section('title', 'Progress Evaluasi - ' . $siswa->nama)

@section('page-title', 'Progress & Evaluasi Siswa')

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
                    <a href="{{ route('evaluasi.create', $siswa->id) }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Input Nilai Baru
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-graph-up"></i> Progress Mingguan (Line Chart)</h5>
            </div>
            <div class="card-body">
                <canvas id="lineChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-radar"></i> Penilaian Terkini</h5>
            </div>
            <div class="card-body">
                <canvas id="radarChart"></canvas>
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
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Minggu</th>
                                    <th>Kategori</th>
                                    <th>Nilai</th>
                                    <th>Dinilai Oleh</th>
                                    <th>Catatan</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($evaluasi->sortByDesc('minggu') as $eval)
                                <tr>
                                    <td><span class="badge bg-info">Minggu {{ $eval->minggu }}</span></td>
                                    <td>{{ $eval->kategori->nama }}</td>
                                    <td>
                                        <strong class="
                                            @if($eval->nilai >= 80) text-success
                                            @elseif($eval->nilai >= 60) text-warning
                                            @else text-danger
                                            @endif
                                        ">{{ $eval->nilai }}</strong>
                                    </td>
                                    <td>{{ $eval->dinilai_oleh }}</td>
                                    <td>{{ $eval->catatan ?: '-' }}</td>
                                    <td><small>{{ $eval->created_at->format('d/m/Y') }}</small></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <p class="text-muted">Belum ada data evaluasi</p>
                        <a href="{{ route('evaluasi.create', $siswa->id) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle"></i> Input Evaluasi Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Line Chart - Weekly Progress
    const lineCtx = document.getElementById('lineChart').getContext('2d');
    const lineChart = new Chart(lineCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($weeks) !!}.map(w => 'Minggu ' + w),
            datasets: [
                @foreach($lineChartData as $kategori => $data)
                {
                    label: '{{ $kategori }}',
                    data: {!! json_encode($data) !!},
                    borderColor: getColor({{ $loop->index }}),
                    backgroundColor: getColor({{ $loop->index }}, 0.1),
                    tension: 0.4,
                    fill: true
                },
                @endforeach
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
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

    // Radar Chart - Latest Week
    const radarCtx = document.getElementById('radarChart').getContext('2d');
    const radarChart = new Chart(radarCtx, {
        type: 'radar',
        data: {
            labels: {!! json_encode(array_keys($radarChartData)) !!},
            datasets: [{
                label: 'Nilai Terkini',
                data: {!! json_encode(array_values($radarChartData)) !!},
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
