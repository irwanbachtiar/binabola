<?php $__env->startSection('title', 'Statistik Kehadiran'); ?>

<?php $__env->startSection('page-title', 'Statistik Kehadiran'); ?>

<?php $__env->startSection('content'); ?>
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Statistik Kehadiran Siswa</h5>
                <a href="<?php echo e(route('absensi.index')); ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
            <div class="card-body">
                <form method="GET" class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Bulan</label>
                        <select class="form-select" name="bulan">
                            <?php for($i = 1; $i <= 12; $i++): ?>
                                <option value="<?php echo e($i); ?>" <?php echo e($bulan == $i ? 'selected' : ''); ?>>
                                    <?php echo e(\Carbon\Carbon::create()->month($i)->isoFormat('MMMM')); ?>

                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tahun</label>
                        <select class="form-select" name="tahun">
                            <?php for($i = date('Y'); $i >= date('Y') - 3; $i--): ?>
                                <option value="<?php echo e($i); ?>" <?php echo e($tahun == $i ? 'selected' : ''); ?>><?php echo e($i); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Filter
                        </button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Hadir</th>
                                <th>Izin</th>
                                <th>Sakit</th>
                                <th>Alpa</th>
                                <th>Total</th>
                                <th>Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $siswas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $siswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    $total = $siswa->total_hadir + $siswa->total_izin + $siswa->total_sakit + $siswa->total_alpa;
                                    $persentase = $total > 0 ? round(($siswa->total_hadir / $total) * 100, 1) : 0;
                                ?>
                            <tr>
                                <td><?php echo e($loop->iteration); ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php if($siswa->foto): ?>
                                            <img src="<?php echo e(asset($siswa->foto)); ?>" alt="Foto" class="rounded-circle me-2" style="width: 30px; height: 30px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                                                <i class="bi bi-person text-white" style="font-size: 14px;"></i>
                                            </div>
                                        <?php endif; ?>
                                        <strong><?php echo e($siswa->nama); ?></strong>
                                    </div>
                                </td>
                                <td><span class="badge bg-success"><?php echo e($siswa->total_hadir); ?></span></td>
                                <td><span class="badge bg-primary"><?php echo e($siswa->total_izin); ?></span></td>
                                <td><span class="badge bg-warning"><?php echo e($siswa->total_sakit); ?></span></td>
                                <td><span class="badge bg-danger"><?php echo e($siswa->total_alpa); ?></span></td>
                                <td><strong><?php echo e($total); ?></strong></td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar 
                                            <?php if($persentase >= 80): ?> bg-success
                                            <?php elseif($persentase >= 60): ?> bg-warning
                                            <?php else: ?> bg-danger
                                            <?php endif; ?>" 
                                            style="width: <?php echo e($persentase); ?>%">
                                            <?php echo e($persentase); ?>%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <p class="text-muted">Tidak ada data statistik</p>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project ai\bina bola\binabola\resources\views/absensi/statistik.blade.php ENDPATH**/ ?>