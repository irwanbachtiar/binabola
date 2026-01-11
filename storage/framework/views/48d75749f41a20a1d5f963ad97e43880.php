<?php $__env->startSection('title', 'Riwayat Absensi'); ?>

<?php $__env->startSection('page-title', 'Riwayat Absensi'); ?>

<?php $__env->startSection('content'); ?>
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Riwayat Absensi</h5>
                <a href="<?php echo e(route('absensi.index')); ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
            <div class="card-body">
                <form method="GET" class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" name="tanggal_mulai" value="<?php echo e($tanggalMulai); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Akhir</label>
                        <input type="date" class="form-control" name="tanggal_akhir" value="<?php echo e($tanggalAkhir); ?>">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Filter
                        </button>
                    </div>
                </form>

                <?php $__empty_1 = true; $__currentLoopData = $absensis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tanggal => $absensiPerHari): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <strong><?php echo e(\Carbon\Carbon::parse($tanggal)->isoFormat('dddd, D MMMM YYYY')); ?></strong>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Siswa</th>
                                            <th>Sesi</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $absensiPerHari; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $absensi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($loop->iteration); ?></td>
                                            <td><?php echo e($absensi->siswa->nama); ?></td>
                                            <td><span class="badge bg-info"><?php echo e($absensi->sesi); ?></span></td>
                                            <td>
                                                <?php if($absensi->status == 'Hadir'): ?>
                                                    <span class="badge bg-success">✓ Hadir</span>
                                                <?php elseif($absensi->status == 'Izin'): ?>
                                                    <span class="badge bg-primary">ℹ Izin</span>
                                                <?php elseif($absensi->status == 'Sakit'): ?>
                                                    <span class="badge bg-warning">+ Sakit</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">✗ Alpa</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-4">
                        <p class="text-muted">Tidak ada data absensi pada periode ini</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project ai\bina bola\binabola\resources\views/absensi/history.blade.php ENDPATH**/ ?>