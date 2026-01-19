<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('page-title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<!-- Stats Cards -->
<div class="row mb-3">
    <div class="col-md-3 mb-2">
        <div class="card text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body py-2 px-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1 small">Total Siswa</h6>
                        <h3 class="mb-0 fw-bold"><?php echo e($totalSiswa); ?></h3>
                        <small style="font-size: 0.75rem;">Aktif: <?php echo e($siswaAktif); ?> | Non: <?php echo e($siswaNonAktif); ?></small>
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
                        <h3 class="mb-0 fw-bold"><?php echo e(\App\Models\Siswa::whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 8 AND 12')->count()); ?></h3>
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
                        <h3 class="mb-0 fw-bold"><?php echo e(\App\Models\Siswa::whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 3 AND 7')->count()); ?></h3>
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
                        <h3 class="mb-0 fw-bold"><?php echo e($persentaseKehadiran); ?>%</h3>
                        <small style="font-size: 0.75rem;"><?php echo e($totalHadir); ?>/<?php echo e($totalHadir + $totalIzin + $totalSakit + $totalAlpa); ?> hari</small>
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
                        <h3 class="mb-0 fw-bold"><?php echo e($totalEvaluasi); ?></h3>
                        <small style="font-size: 0.75rem;">Rata-rata: <?php echo e($rataRataNilai); ?></small>
                    </div>
                    <i class="bi bi-clipboard-data" style="font-size: 2.5rem; opacity: 0.4;"></i>
                </div>
            </div>
        </div>
    </div>
    
    
    
    
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
                <?php $__empty_1 = true; $__currentLoopData = $siswaTopPenilaian; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $siswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="d-flex align-items-center mb-2 pb-1 <?php echo e(!$loop->last ? 'border-bottom' : ''); ?>">
                        <?php if($siswa->foto): ?>
                            <img src="<?php echo e(asset($siswa->foto)); ?>" alt="Foto" class="rounded-circle me-2" style="width: 30px; height: 30px; object-fit: cover;">
                        <?php else: ?>
                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                                <i class="bi bi-person text-white" style="font-size: 0.8rem;"></i>
                            </div>
                        <?php endif; ?>
                        <div class="flex-grow-1">
                            <strong class="d-block" style="font-size: 0.85rem;"><?php echo e($siswa->nama); ?></strong>
                            <small class="text-muted" style="font-size: 0.7rem;">Avg: <?php echo e($siswa->avg_nilai ? number_format($siswa->avg_nilai,1) : '-'); ?></small>
                        </div>
                        <span class="badge bg-success" style="font-size: 0.7rem;"><?php echo e($loop->iteration); ?></span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-muted text-center mb-0">Belum ada data</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('vendor/chartjs/chart.min.js')); ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Kehadiran Chart
    const canvas = document.getElementById('kehadiranChart');
    if (!canvas) return;
    
    const ctx = canvas.getContext('2d');
    const kehadiranChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($chartLabels); ?>,
            datasets: [
                {
                    label: 'Hadir',
                    data: <?php echo json_encode($chartHadir); ?>,
                    borderColor: 'rgb(40, 167, 69)',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Tidak Hadir',
                    data: <?php echo json_encode($chartTidakHadir); ?>,
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project ai\bina bola\binabola\resources\views/welcome.blade.php ENDPATH**/ ?>