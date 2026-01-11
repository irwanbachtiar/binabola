

<?php $__env->startSection('title', 'Absensi - ' . $siswa->nama); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <a href="<?php echo e(route('orangtua.dashboard')); ?>" class="btn btn-secondary mb-3">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>

            <div class="d-flex align-items-center mb-4">
                <?php if($siswa->foto): ?>
                    <img src="<?php echo e(asset($siswa->foto)); ?>" alt="<?php echo e($siswa->nama); ?>" class="rounded-circle me-3" style="width: 60px; height: 60px; object-fit: cover;">
                <?php else: ?>
                    <div class="rounded-circle text-white d-inline-flex align-items-center justify-content-center me-3" 
                         style="width: 60px; height: 60px; font-size: 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <?php echo e(strtoupper(substr($siswa->nama, 0, 1))); ?>

                    </div>
                <?php endif; ?>
                <div>
                    <h3 class="mb-0">Absensi: <?php echo e($siswa->nama); ?></h3>
                    <p class="text-muted mb-0"><?php echo e($siswa->umur_detail); ?> • <?php echo e($siswa->minat_posisi_string); ?></p>
                </div>
            </div>

            <!-- Statistik Absensi 30 Hari -->
            <div class="row mb-4">
                <div class="col-md-2 col-sm-4 col-6 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body text-center p-3">
                            <h5 class="fw-bold mb-1"><?php echo e($stats['total']); ?></h5>
                            <small class="text-muted">Total</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6 mb-3">
                    <div class="card shadow-sm border-success">
                        <div class="card-body text-center p-3">
                            <h5 class="fw-bold text-success mb-1"><?php echo e($stats['hadir']); ?></h5>
                            <small class="text-muted">Hadir</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6 mb-3">
                    <div class="card shadow-sm border-warning">
                        <div class="card-body text-center p-3">
                            <h5 class="fw-bold text-warning mb-1"><?php echo e($stats['izin']); ?></h5>
                            <small class="text-muted">Izin</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6 mb-3">
                    <div class="card shadow-sm border-info">
                        <div class="card-body text-center p-3">
                            <h5 class="fw-bold text-info mb-1"><?php echo e($stats['sakit']); ?></h5>
                            <small class="text-muted">Sakit</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6 mb-3">
                    <div class="card shadow-sm border-danger">
                        <div class="card-body text-center p-3">
                            <h5 class="fw-bold text-danger mb-1"><?php echo e($stats['alpa']); ?></h5>
                            <small class="text-muted">Alpa</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6 mb-3">
                    <div class="card shadow-sm bg-primary text-white">
                        <div class="card-body text-center p-3">
                            <h5 class="fw-bold mb-1"><?php echo e($stats['persentase_hadir']); ?>%</h5>
                            <small>Kehadiran</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Riwayat Absensi -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Riwayat Absensi (30 Hari Terakhir)</h5>
                </div>
                <div class="card-body">
                    <?php if($absensis->isEmpty()): ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Belum ada data absensi.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="20%">Tanggal</th>
                                        <th width="15%">Sesi</th>
                                        <th width="15%">Status</th>
                                        <th width="45%">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $absensis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $absensi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($absensis->firstItem() + $index); ?></td>
                                            <td>
                                                <strong><?php echo e($absensi->tanggal->format('d M Y')); ?></strong><br>
                                                <small class="text-muted"><?php echo e($absensi->tanggal->isoFormat('dddd')); ?></small>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary"><?php echo e($absensi->sesi); ?></span>
                                            </td>
                                            <td>
                                                <?php if($absensi->status == 'Hadir'): ?>
                                                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> Hadir</span>
                                                <?php elseif($absensi->status == 'Izin'): ?>
                                                    <span class="badge bg-warning"><i class="bi bi-exclamation-circle"></i> Izin</span>
                                                <?php elseif($absensi->status == 'Sakit'): ?>
                                                    <span class="badge bg-info"><i class="bi bi-thermometer"></i> Sakit</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Alpa</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($absensi->keterangan ?? '-'); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            <?php echo e($absensis->links()); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project ai\bina bola\binabola\resources\views/orangtua/absensi.blade.php ENDPATH**/ ?>