@extends('layouts.app')

@section('title', 'Input Evaluasi - ' . $siswa->nama)

@section('page-title', 'Input Evaluasi Siswa')

@section('content')
<!-- Header Siswa -->
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
                    <div>
                        <h4 class="mb-1">{{ $siswa->nama }}</h4>
                        <p class="mb-0 text-muted">Umur: {{ $siswa->umur }} tahun</p>
                        <p class="mb-0 text-muted">Posisi: {{ $siswa->minat_posisi_string }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grafik -->
<div class="row mb-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0">Grafik Perkembangan Mingguan</h6>
            </div>
            <div class="card-body" style="height: 300px;">
                @if($weeks->count() > 0)
                    <canvas id="lineChart"></canvas>
                @else
                    <p class="text-center text-muted py-5">Belum ada data evaluasi</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0">Radar Evaluasi Pemain (Rata-rata)</h6>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center" style="height: 300px;">
                @if($weeks->count() > 0)
                    <canvas id="radarChart" style="max-height: 250px;"></canvas>
                @else
                    <p class="text-center text-muted">Belum ada data</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Form Input & Riwayat -->
<div class="row mb-3">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0">Input Penilaian Mingguan</h6>
            </div>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger alert-sm">{{ session('error') }}</div>
                @endif
                
                <div id="successAlert" class="alert alert-success alert-sm d-none"></div>

                <form id="evaluasiForm">
                    @csrf
                    <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">
                    <input type="hidden" name="minggu" id="mingguInput" value="{{ $weeks->max() + 1 ?? 1 }}">
                    <input type="hidden" name="tahun" value="{{ $currentYear }}">

                    <div class="mb-3">
                        <label class="form-label">Minggu ke-<strong id="mingguDisplay">{{ $weeks->max() + 1 ?? 1 }}</strong></label>
                    </div>

                    <div class="row mb-3">
                        @foreach($kategoris as $kategori)
                            <div class="col-md-4">
                                <label class="form-label small">{{ $kategori->nama }}</label>
                                <input 
                                    type="number" 
                                    class="form-control" 
                                    name="nilai[{{ $kategori->id }}]" 
                                    id="value_{{ $kategori->id }}"
                                    min="0" 
                                    max="100" 
                                    value="{{ $existingEvaluasi[$kategori->id] ?? 70 }}"
                                    required
                                >
                            </div>
                        @endforeach
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Dinilai Oleh</label>
                        <input type="text" class="form-control" name="dinilai_oleh" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea class="form-control" name="catatan" rows="2"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-2" id="btnSimpan">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                    
                    <a href="{{ route('evaluasi.index') }}" class="btn btn-secondary w-100">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </form>
            </div>
        </div>
    </div>

    <!-- Riwayat -->
    <div class="col-md-7">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0">Riwayat Penilaian Mingguan</h6>
            </div>
            <div class="card-body">
                @if($evaluasi->count() > 0)
                    @php
                        $groupedEvaluasi = $evaluasi->groupBy('minggu')->sortKeysDesc();
                    @endphp
                    @foreach($groupedEvaluasi as $minggu => $evals)
                        <div class="mb-2 p-2 border rounded d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Minggu {{ $minggu }}</strong> — 
                                @foreach($evals as $eval)
                                    {{ $eval->kategori->nama[0] }}:{{ $eval->nilai }}
                                    @if(!$loop->last) | @endif
                                @endforeach
                            </div>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-primary" onclick="editEvaluasi({{ $minggu }}, {{ $siswa->id }})">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                                <form action="{{ route('evaluasi.delete', ['siswa' => $siswa->id, 'minggu' => $minggu]) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus evaluasi minggu {{ $minggu }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-center text-muted">Belum ada riwayat penilaian</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let lineChart, radarChart;

// Initialize charts if data exists
@if($weeks->count() > 0)
    initCharts();
@endif

// Form submission with AJAX
document.getElementById('evaluasiForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const btnSimpan = document.getElementById('btnSimpan');
    btnSimpan.disabled = true;
    btnSimpan.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menyimpan...';
    
    const formData = new FormData(this);
    
    fetch('{{ route("evaluasi.store") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            const alert = document.getElementById('successAlert');
            alert.textContent = data.message;
            alert.classList.remove('d-none');
            setTimeout(() => alert.classList.add('d-none'), 3000);
            
            // Update minggu display and input
            const nextWeek = parseInt(document.getElementById('mingguInput').value) + 1;
            document.getElementById('mingguInput').value = nextWeek;
            document.getElementById('mingguDisplay').textContent = nextWeek;
            
            // Reset form values
            document.querySelectorAll('input[type="number"]').forEach(input => input.value = 70);
            document.querySelector('input[name="dinilai_oleh"]').value = '';
            document.querySelector('textarea[name="catatan"]').value = '';
            
            // Reload page to update charts and history
            setTimeout(() => location.reload(), 1500);
        }
    })
    .catch(error => {
        alert('Terjadi kesalahan saat menyimpan data');
        console.error(error);
    })
    .finally(() => {
        btnSimpan.disabled = false;
        btnSimpan.innerHTML = 'Simpan';
    });
});

function initCharts() {
    // Line Chart
    const lineCtx = document.getElementById('lineChart').getContext('2d');
    lineChart = new Chart(lineCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($weeks->map(fn($w) => 'M' . $w)) !!},
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
            maintainAspectRatio: false,
            plugins: { legend: { position: 'top' } },
            scales: {
                y: { beginAtZero: true, max: 100 }
            }
        }
    });

    // Radar Chart
    const radarCtx = document.getElementById('radarChart').getContext('2d');
    radarChart = new Chart(radarCtx, {
        type: 'radar',
        data: {
            labels: {!! json_encode(array_keys($radarChartData)) !!},
            datasets: [{
                label: 'Rata-rata Nilai',
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
            maintainAspectRatio: true,
            scales: {
                r: { beginAtZero: true, max: 100, ticks: { stepSize: 20 } }
            },
            plugins: { legend: { display: false } }
        }
    });
}

function getColor(index, alpha = 1) {
    const colors = [
        `rgba(54, 162, 235, ${alpha})`,
        `rgba(255, 99, 132, ${alpha})`,
        `rgba(255, 206, 86, ${alpha})`,
    ];
    return colors[index % colors.length];
}

// Function for edit
function editEvaluasi(minggu, siswaId) {
    // Change minggu value
    document.getElementById('mingguInput').value = minggu;
    document.getElementById('mingguDisplay').textContent = minggu;
    
    // Fetch existing data and populate form
    fetch(`/evaluasi/get-week/${siswaId}/${minggu}`)
        .then(response => response.json())
        .then(data => {
            data.forEach(item => {
                const input = document.getElementById(`value_${item.kategori_penilaian_id}`);
                if (input) input.value = item.nilai;
            });
            // Scroll to form
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
}
</script>
@endpush
