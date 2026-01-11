

<?php $__env->startSection('title', 'Dashboard Orangtua'); ?>

<?php $__env->startSection('page-title', 'Dashboard Orangtua'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Welcome Header -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" 
                         style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <span class="text-white" style="font-size: 32px; font-weight: bold;">
                            <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                        </span>
                    </div>
                </div>
                <div class="col">
                    <h3 class="mb-1">Selamat Datang, <?php echo e($user->name); ?>!</h3>
                    <p class="text-muted mb-0">
                        <i class="bi bi-envelope"></i> <?php echo e($user->email); ?>

                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info border-0">
                <i class="bi bi-info-circle"></i> 
                <strong>Informasi:</strong> Anda memiliki <?php echo e($siswas->count()); ?> siswa terdaftar. Klik pada kartu siswa untuk melihat detail perkembangan.
            </div>
        </div>
    </div>

    <!-- Students List -->
    <div class="row">
        <?php $__currentLoopData = $siswas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $siswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100 hover-card" style="cursor: pointer;" onclick="window.location='<?php echo e(route('orangtua.siswa.progress', $siswa->id)); ?>'">
                <div class="card-body">
                    <div class="d-flex align-items-start mb-3">
                        <div class="me-3">
                            <?php if($siswa->foto && file_exists(public_path($siswa->foto))): ?>
                                <img src="<?php echo e(asset($siswa->foto)); ?>" alt="<?php echo e($siswa->nama); ?>" 
                                     class="rounded-circle" width="80" height="80" 
                                     style="object-fit: cover; border: 3px solid #667eea;">
                            <?php else: ?>
                                <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: 3px solid #667eea;">
                                    <span class="text-white" style="font-size: 32px; font-weight: bold;">
                                        <?php echo e(strtoupper(substr($siswa->nama, 0, 1))); ?>

                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-1"><?php echo e($siswa->nama); ?></h5>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge bg-<?php echo e($siswa->kelompok_umur == 'U-7' ? 'info' : 'primary'); ?>">
                                    <?php echo e($siswa->kelompok_umur); ?>

                                </span>
                                <span class="badge <?php echo e($siswa->status == 'Aktif' ? 'bg-success' : 'bg-secondary'); ?>">
                                    <?php echo e($siswa->status); ?>

                                </span>
                            </div>
                            <small class="text-muted">
                                <i class="bi bi-calendar"></i> <?php echo e(\Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d M Y')); ?> 
                                (<?php echo e($siswa->umur); ?> tahun)
                            </small>
                        </div>
                    </div>

                    <hr>

                    <!-- Quick Stats -->
                    <div class="row g-2 text-center">
                        <div class="col-4">
                            <div class="p-2 bg-light rounded">
                                <div class="d-flex align-items-center justify-content-center mb-1">
                                    <i class="bi bi-calendar-check text-success me-1"></i>
                                    <small class="text-muted">Kehadiran</small>
                                </div>
                                <h6 class="mb-0 text-success"><?php echo e($siswa->persentase_hadir); ?>%</h6>
                                <small class="text-muted">30 hari</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 bg-light rounded">
                                <div class="d-flex align-items-center justify-content-center mb-1">
                                    <i class="bi bi-star-fill text-warning me-1"></i>
                                    <small class="text-muted">Nilai</small>
                                </div>
                                <h6 class="mb-0 text-warning"><?php echo e($siswa->rata_rata_evaluasi); ?></h6>
                                <small class="text-muted"><?php echo e($siswa->total_evaluasi); ?> eval</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 bg-light rounded">
                                <div class="d-flex align-items-center justify-content-center mb-1">
                                    <i class="bi bi-credit-card text-<?php echo e($siswa->sudah_bayar_bulan_ini ? 'success' : ($siswa->jumlah_tunggakan > 0 ? 'danger' : 'secondary')); ?> me-1"></i>
                                    <small class="text-muted">Iuran</small>
                                </div>
                                <?php if($siswa->sudah_bayar_bulan_ini): ?>
                                    <h6 class="mb-0 text-success"><i class="bi bi-check-circle"></i></h6>
                                    <small class="text-muted">Lunas</small>
                                <?php elseif($siswa->jumlah_tunggakan > 0): ?>
                                    <h6 class="mb-0 text-danger"><?php echo e($siswa->jumlah_tunggakan); ?></h6>
                                    <small class="text-muted">Tunggakan</small>
                                <?php else: ?>
                                    <h6 class="mb-0 text-secondary">-</h6>
                                    <small class="text-muted">Belum ada</small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <?php if($siswa->latest_eval_date): ?>
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="bi bi-clock"></i> Evaluasi terakhir: 
                            <?php echo e(\Carbon\Carbon::parse($siswa->latest_eval_date)->diffForHumans()); ?>

                        </small>
                    </div>
                    <?php endif; ?>

                    <div class="mt-3 d-grid gap-2">
                        <a href="<?php echo e(route('orangtua.siswa.progress', $siswa->id)); ?>" class="btn btn-primary btn-sm">
                            <i class="bi bi-eye"></i> Lihat Detail Progress
                        </a>
                        <a href="<?php echo e(route('orangtua.siswa.pembayaran', $siswa->id)); ?>" class="btn btn-outline-success btn-sm">
                            <i class="bi bi-credit-card"></i> Monitoring Pembayaran
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .hover-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15) !important;
    }
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project ai\bina bola\binabola\resources\views/orangtua/dashboard.blade.php ENDPATH**/ ?>