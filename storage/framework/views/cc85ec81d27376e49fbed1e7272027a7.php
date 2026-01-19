

<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('page-title', 'Dashboard BinaBola'); ?>

<?php $__env->startSection('content'); ?>
<!-- Summary Cards -->
<div class="row g-2 mb-3">
    <!-- Total Siswa -->
    <div class="col-6">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-people fs-2 text-primary"></i>
                <h4 class="mb-0 mt-2"><?php echo e($totalSiswa); ?></h4>
                <small class="text-muted">Total Siswa</small>
            </div>
        </div>
    </div>

    <!-- Siswa Aktif -->
    <div class="col-6">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-person-check fs-2 text-success"></i>
                <h4 class="mb-0 mt-2"><?php echo e($siswaAktif); ?></h4>
                <small class="text-muted">Siswa Aktif</small>
            </div>
        </div>
    </div>

    <!-- Kehadiran -->
    <div class="col-6">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-calendar-check fs-2 text-info"></i>
                <h4 class="mb-0 mt-2"><?php echo e($persentaseKehadiran); ?>%</h4>
                <small class="text-muted">Kehadiran</small>
            </div>
        </div>
    </div>

    <!-- Rata-rata Nilai -->
    <div class="col-6">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-star fs-2 text-warning"></i>
                <h4 class="mb-0 mt-2"><?php echo e($rataRataNilai); ?></h4>
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
                    <h5><?php echo e($totalHadir); ?></h5>
                    <small>Hadir</small>
                </div>
            </div>
            <div class="col-3">
                <div class="text-warning">
                    <h5><?php echo e($totalIzin); ?></h5>
                    <small>Izin</small>
                </div>
            </div>
            <div class="col-3">
                <div class="text-info">
                    <h5><?php echo e($totalSakit); ?></h5>
                    <small>Sakit</small>
                </div>
            </div>
            <div class="col-3">
                <div class="text-danger">
                    <h5><?php echo e($totalAlpa); ?></h5>
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
        <?php $__empty_1 = true; $__currentLoopData = $evaluasiTerbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="d-flex align-items-center p-2 border-bottom">
            <?php if($eval->siswa->foto): ?>
                <img src="<?php echo e(asset($eval->siswa->foto)); ?>" alt="<?php echo e($eval->siswa->nama); ?>" 
                     style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 10px;">
            <?php else: ?>
                <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; margin-right: 10px;">
                    <i class="bi bi-person text-white"></i>
                </div>
            <?php endif; ?>
            <div class="flex-grow-1">
                <strong style="font-size: 14px;"><?php echo e($eval->siswa->nama); ?></strong>
                <br>
                <small class="text-muted"><?php echo e($eval->kategori->nama); ?>: <strong><?php echo e($eval->nilai); ?></strong></small>
            </div>
            <small class="text-muted">W<?php echo e($eval->minggu); ?></small>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="text-center text-muted py-3 mb-0">Belum ada evaluasi bulan ini</p>
        <?php endif; ?>
    </div>
</div>

<!-- TOP SISWA -->
<div class="card mb-3">
    <div class="card-header">
        <strong><i class="bi bi-trophy"></i> TOP SISWA Bulan Ini</strong>
    </div>
    <div class="card-body p-2">
        <?php $__empty_1 = true; $__currentLoopData = $siswaTopPenilaian; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $siswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="d-flex align-items-center p-2 border-bottom">
            <div style="width: 30px; text-align: center; margin-right: 10px;">
                <?php if($index == 0): ?>
                    <i class="bi bi-trophy-fill text-warning fs-4"></i>
                <?php elseif($index == 1): ?>
                    <i class="bi bi-trophy-fill text-secondary fs-5"></i>
                <?php elseif($index == 2): ?>
                    <i class="bi bi-trophy-fill" style="color: #cd7f32; font-size: 1.2rem;"></i>
                <?php else: ?>
                    <span class="text-muted"><?php echo e($index + 1); ?></span>
                <?php endif; ?>
            </div>
            <?php if($siswa->foto): ?>
                <img src="<?php echo e(asset($siswa->foto)); ?>" alt="<?php echo e($siswa->nama); ?>" 
                     style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 10px;">
            <?php else: ?>
                <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; margin-right: 10px;">
                    <i class="bi bi-person text-white"></i>
                </div>
            <?php endif; ?>
            <div class="flex-grow-1">
                <strong style="font-size: 14px;"><?php echo e($siswa->nama); ?></strong>
            </div>
            <span class="badge bg-success"><?php echo e($siswa->avg_nilai ? number_format($siswa->avg_nilai,1) : '-'); ?></span>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="text-center text-muted py-3 mb-0">Belum ada data</p>
        <?php endif; ?>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-2 mb-3">
    <div class="col-6">
        <a href="<?php echo e(route('evaluasi.index')); ?>" class="btn btn-primary w-100">
            <i class="bi bi-clipboard-check"></i> Input Evaluasi
        </a>
    </div>
    <div class="col-6">
        <a href="<?php echo e(route('absensi.index')); ?>" class="btn btn-success w-100">
            <i class="bi bi-calendar-check"></i> Input Absensi
        </a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project ai\bina bola\binabola\resources\views/dashboard-mobile.blade.php ENDPATH**/ ?>