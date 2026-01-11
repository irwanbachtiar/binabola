<?php $__env->startSection('title', 'Input Evaluasi - ' . $siswa->nama); ?>

<?php $__env->startSection('page-title', 'Input Evaluasi Siswa'); ?>

<?php $__env->startPush('styles'); ?>
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<!-- Header Siswa -->
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <?php if($siswa->foto): ?>
                        <img src="<?php echo e(asset($siswa->foto)); ?>" alt="Foto" class="rounded-circle me-3" style="width: 80px; height: 80px; object-fit: cover;">
                    <?php else: ?>
                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-person fs-3 text-white"></i>
                        </div>
                    <?php endif; ?>
                    <div>
                        <h4 class="mb-1"><?php echo e($siswa->nama); ?></h4>
                        <p class="mb-0 text-muted">Umur: <?php echo e($siswa->umur); ?> tahun</p>
                        <p class="mb-0 text-muted">Posisi: <?php echo e($siswa->minat_posisi_string); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grafik -->
<div class="row mb-3">
    <!-- Line Charts - One per Parent Category -->
    <?php if($weeks->count() > 0): ?>
        <?php $__currentLoopData = $lineChartData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parentNama => $chartData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><?php echo e($parentNama); ?></h6>
                </div>
                <div class="card-body" style="height: 300px;">
                    <canvas id="lineChart<?php echo e($loop->index); ?>"></canvas>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php else: ?>
        <div class="col-md-12 mb-3">
            <div class="card">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Grafik Perkembangan Mingguan</h6>
                </div>
                <div class="card-body" style="height: 300px;">
                    <p class="text-center text-muted py-5">Belum ada data evaluasi</p>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Radar Chart -->
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0">Radar Evaluasi Pemain (Rata-rata)</h6>
            </div>
            <div class="card-body" style="height: 300px;">
                <?php if($weeks->count() > 0): ?>
                    <canvas id="radarChart"></canvas>
                <?php else: ?>
                    <p class="text-center text-muted">Belum ada data</p>
                <?php endif; ?>
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
                <?php if(session('error')): ?>
                    <div class="alert alert-danger alert-sm"><?php echo e(session('error')); ?></div>
                <?php endif; ?>
                
                <?php if(isset($absensiHariIni)): ?>
                    <?php if($absensiHariIni->status === 'Hadir'): ?>
                        <div class="alert alert-success alert-sm">
                            <i class="bi bi-check-circle"></i> Siswa hadir hari ini - Dapat dinilai
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning alert-sm">
                            <i class="bi bi-exclamation-triangle"></i> Status hari ini: <strong><?php echo e($absensiHariIni->status); ?></strong>
                            <br><small>Siswa tidak hadir, penilaian akan otomatis bernilai 0</small>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="alert alert-info alert-sm">
                        <i class="bi bi-info-circle"></i> Belum ada data absensi hari ini.
                        <br><small>Jika siswa tidak hadir, penilaian akan otomatis bernilai 0</small>
                    </div>
                <?php endif; ?>
                
                <div id="successAlert" class="alert alert-success alert-sm d-none"></div>
                <div id="errorAlert" class="alert alert-danger alert-sm d-none"></div>

                <form id="evaluasiForm">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="siswa_id" value="<?php echo e($siswa->id); ?>">

                    <div class="mb-3">
                        <label class="form-label">Tanggal Evaluasi <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="tanggal_evaluasi" id="tanggalEvaluasi" value="<?php echo e(date('Y-m-d')); ?>" required>
                        <small class="text-muted">
                            Minggu ke-<?php echo e($weekOfMonth); ?> dalam bulan
                            <?php if($isLastSunday): ?>
                                <span class="badge bg-warning text-dark ms-1">
                                    <i class="bi bi-star-fill"></i> Minggu Terakhir - Termasuk Kategori Khusus
                                </span>
                            <?php endif; ?>
                        </small>
                    </div>

                    <!-- Penilaian Hierarki -->
                    <?php $__currentLoopData = $kategoris->where('parent_id', null)->sortBy('urutan'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($parent->minggu_terakhir && !$isLastSunday): ?>
                            
                            <?php continue; ?>
                        <?php endif; ?>
                        
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-folder"></i> <?php echo e($parent->nama); ?>

                                <?php if($parent->minggu_terakhir): ?>
                                    <span class="badge bg-warning text-dark"><i class="bi bi-calendar-check"></i> Minggu Terakhir Bulan</span>
                                <?php endif; ?>
                            </h6>
                            <div class="row">
                                <?php $__currentLoopData = $parent->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label small"><?php echo e($child->nama); ?></label>
                                        <input 
                                            type="number" 
                                            class="form-control" 
                                            name="nilai[<?php echo e($child->id); ?>]" 
                                            id="value_<?php echo e($child->id); ?>"
                                            min="0" 
                                            max="100" 
                                            value="<?php echo e($existingEvaluasi[$child->id] ?? 70); ?>"
                                            required
                                        >
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

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
                    
                    <a href="<?php echo e(route('evaluasi.index')); ?>" class="btn btn-secondary w-100">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </form>
            </div>
        </div>
    </div>

    <!-- Riwayat -->
    <div class="col-md-7">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Riwayat Penilaian Mingguan</h6>
                <small class="text-muted">
                    <i class="bi bi-info-circle"></i> Termasuk dari Penilaian Batch
                </small>
            </div>
            <div class="card-body">
                <?php if($evaluasi->count() > 0): ?>
                    <?php
                        $groupedEvaluasi = $evaluasi->groupBy('minggu')->sortKeysDesc();
                    ?>
                    <?php $__currentLoopData = $groupedEvaluasi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $minggu => $evals): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $firstEval = $evals->first();
                            $avgNilai = $evals->avg('nilai');
                        ?>
                        <div class="mb-2 p-2 border rounded d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Minggu <?php echo e($minggu); ?></strong> 
                                <span class="badge bg-info ms-1">Avg: <?php echo e(number_format($avgNilai, 1)); ?></span>
                                <br>
                                <small class="text-muted">
                                    <?php echo e(\Carbon\Carbon::parse($firstEval->tanggal_evaluasi)->format('d M Y')); ?> — 
                                    <?php $__currentLoopData = $evals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php echo e($eval->kategori->nama[0]); ?>:<?php echo e($eval->nilai); ?>

                                        <?php if(!$loop->last): ?> | <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </small>
                            </div>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-primary" onclick="editEvaluasi(<?php echo e($minggu); ?>, <?php echo e($siswa->id); ?>)">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                                <form action="<?php echo e(route('evaluasi.delete', ['siswa' => $siswa->id, 'minggu' => $minggu])); ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus evaluasi minggu <?php echo e($minggu); ?>?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <p class="text-center text-muted">Belum ada riwayat penilaian</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('vendor/chartjs/chart.min.js')); ?>"></script>
<script>
let lineCharts = [];
let radarChart;

// Check absensi when date changes
document.addEventListener('DOMContentLoaded', function() {
    const tanggalInput = document.getElementById('tanggalEvaluasi');
    const btnSimpan = document.getElementById('btnSimpan');
    
    tanggalInput.addEventListener('change', function() {
        checkAbsensi(this.value);
    });
    
    // Check absensi for initial date
    if (tanggalInput.value) {
        checkAbsensi(tanggalInput.value);
    }
});

function checkAbsensi(tanggal) {
    fetch(`/api/check-absensi?siswa_id=<?php echo e($siswa->id); ?>&tanggal=${tanggal}`, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        }
    })
    .then(response => response.json())
    .then(data => {
        const errorAlert = document.getElementById('errorAlert');
        const successAlert = document.getElementById('successAlert');
        const btnSimpan = document.getElementById('btnSimpan');
        const nilaiInputs = document.querySelectorAll('input[type="number"][name^="nilai"]');
        
        if (data.status === 'Hadir') {
            errorAlert.classList.add('d-none');
            successAlert.classList.add('d-none');
            btnSimpan.disabled = false;
            btnSimpan.innerHTML = '<i class="bi bi-save"></i> Simpan';
            
            // Enable inputs
            nilaiInputs.forEach(input => {
                input.disabled = false;
                input.classList.remove('bg-light');
            });
        } else {
            errorAlert.innerHTML = `<i class="bi bi-exclamation-triangle"></i> Status absensi: <strong>${data.status}</strong>. Sistem akan otomatis menyimpan dengan nilai 0 untuk semua kategori.`;
            errorAlert.classList.remove('d-none');
            successAlert.classList.add('d-none');
            
            // Disable inputs dan set ke 0
            nilaiInputs.forEach(input => {
                input.value = 0;
                input.disabled = true;
                input.classList.add('bg-light');
            });
            
            // Auto save with nilai 0
            btnSimpan.disabled = false;
            btnSimpan.innerHTML = '<i class="bi bi-save"></i> Simpan (Nilai 0)';
            
            // Auto submit after 2 seconds
            setTimeout(() => {
                autoSaveEvaluasiTidakHadir(tanggal, data.status);
            }, 2000);
        }
    })
    .catch(error => {
        console.error('Error checking absensi:', error);
    });
}

function autoSaveEvaluasiTidakHadir(tanggal, status) {
    const form = document.getElementById('evaluasiForm');
    const formData = new FormData(form);
    const btnSimpan = document.getElementById('btnSimpan');
    
    btnSimpan.disabled = true;
    btnSimpan.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menyimpan otomatis...';
    
    fetch('<?php echo e(route("evaluasi.store")); ?>', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '<?php echo e(csrf_token()); ?>',
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => Promise.reject(err));
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            const successAlert = document.getElementById('successAlert');
            successAlert.innerHTML = '<i class="bi bi-check-circle"></i> Evaluasi otomatis tersimpan dengan nilai 0 (Status: ' + status + ')';
            successAlert.classList.remove('d-none');
            
            document.getElementById('errorAlert').classList.add('d-none');
            
            setTimeout(() => {
                window.location.href = window.location.href.split('?')[0] + '?t=' + Date.now();
            }, 1500);
        }
    })
    .catch(error => {
        const errorAlert = document.getElementById('errorAlert');
        errorAlert.innerHTML = '<i class="bi bi-x-circle"></i> Gagal menyimpan: ' + (error.message || 'Terjadi kesalahan');
        errorAlert.classList.remove('d-none');
        
        btnSimpan.disabled = false;
        btnSimpan.innerHTML = '<i class="bi bi-save"></i> Simpan Manual';
        
        console.error('Error:', error);
    });
}

// Initialize charts if data exists
<?php if($weeks->count() > 0): ?>
    initCharts();
<?php endif; ?>

// Form submission with AJAX
document.getElementById('evaluasiForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const btnSimpan = document.getElementById('btnSimpan');
    btnSimpan.disabled = true;
    btnSimpan.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menyimpan...';
    
    const formData = new FormData(this);
    
    fetch('<?php echo e(route("evaluasi.store")); ?>', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '<?php echo e(csrf_token()); ?>',
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => Promise.reject(err));
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Show success message
            const successAlert = document.getElementById('successAlert');
            let message = '<i class="bi bi-check-circle"></i> ' + data.message;
            
            if (data.warning) {
                message += '<br><small class="text-warning"><i class="bi bi-exclamation-triangle"></i> ' + data.warning + '</small>';
            }
            
            successAlert.innerHTML = message + ' - Memuat ulang data...';
            successAlert.classList.remove('d-none');
            
            // Hide error alert if visible
            document.getElementById('errorAlert').classList.add('d-none');
            
            // Hard reload page immediately to update charts and history
            // Add timestamp to bypass cache
            setTimeout(() => {
                window.location.href = window.location.href.split('?')[0] + '?t=' + Date.now();
            }, 1000);
        }
    })
    .catch(error => {
        // Show error message
        const errorAlert = document.getElementById('errorAlert');
        errorAlert.textContent = error.message || 'Terjadi kesalahan saat menyimpan data';
        errorAlert.classList.remove('d-none');
        
        // Hide success alert if visible
        document.getElementById('successAlert').classList.add('d-none');
        
        console.error('Error:', error);
    })
    .finally(() => {
        btnSimpan.disabled = false;
        btnSimpan.innerHTML = 'Simpan';
    });
});

function initCharts() {
    // Multiple Line Charts - One per Parent Category
    const lineChartData = <?php echo json_encode($lineChartData); ?>;
    const weeks = <?php echo json_encode($weeks->map(fn($w) => 'M' . $w)); ?>;
    
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
            
            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: weeks,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { 
                            position: 'top',
                            labels: {
                                boxWidth: 12,
                                font: { size: 10 }
                            }
                        } 
                    },
                    scales: {
                        y: { beginAtZero: true, max: 100 }
                    }
                }
            });
            
            lineCharts.push(chart);
        }
        
        chartIndex++;
    }

    // Radar Chart
    const radarCtx = document.getElementById('radarChart').getContext('2d');
    const radarLabels = <?php echo json_encode(array_keys($radarChartData)); ?>;
    const radarData = <?php echo json_encode(array_values($radarChartData)); ?>;
    
    console.log('Radar Chart Data:', {
        labels: radarLabels,
        data: radarData,
        fullData: <?php echo json_encode($radarChartData); ?>

    });
    
    radarChart = new Chart(radarCtx, {
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
    // Fetch existing data and populate form
    fetch(`/evaluasi/get-week/${siswaId}/${minggu}`)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                // Set tanggal from first record
                document.getElementById('tanggalEvaluasi').value = data[0].tanggal_evaluasi || new Date().toISOString().split('T')[0];
                
                data.forEach(item => {
                    const input = document.getElementById(`value_${item.kategori_penilaian_id}`);
                    if (input) input.value = item.nilai;
                });
            }
            // Scroll to form
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project ai\bina bola\binabola\resources\views/evaluasi/create.blade.php ENDPATH**/ ?>